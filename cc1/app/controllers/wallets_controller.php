<?php
class WalletsController extends AppController{
    public $helpers = array();
    public $components = array('Auth', 'Recharge', 'Shop','General');
    public $uses = array('User', 'Retailer', 'Slaves');
    private $CREDS = array(
            'ONGO'=>array('api_url'=>"https://www.myongo.co.in/ONGOWALLET/prepaidapi.aspx", // "http://221.135.139.43:7781/ONGOWALLET/prepaidapi.aspx",
'mobile'=>"8879647666", // "9167787891",
'password'=>"pay1@Ongo", // "8879647666",//pay@123",
'GUID'=>"RTLB2315-0A67-4C3F-BDFB-64DDD198A2B3", 'role'=>"A", 'default_session_key'=>"AGSIndiaSwitch12"));

    function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('*');
    }

    function log($label, $data){
        $filename = "wallets_integration_" . date('Ymd') . ".txt";
        
        if(is_array($data)){
            $data = json_encode($data);
        }
        $this->General->logData('/mnt/logs/' . $filename, $label . "::" . $data);
    }

    function request($url, $params, $method, $label){
        $this->log("ongoRequest::inside request", json_encode($url, $params, $method, $label));
        
        if($method == 'GET'){
            if( ! empty($params)){
                $params_string = array();
                foreach($params as $k=>$p){
                    $params_string[] = $k . "=" . $p;
                }
                $query_string = implode("&", $params_string);
                $url .= "?" . $query_string;
                $params = array();
            }
        }
        $this->log("Curl request::" . $label . "::" . $url . "::" . $method, $params);
        $response = $this->General->curl_post($url, $params, $method);
        $this->log("Curl response::" . $label . "::" . $url . "::" . $method, $response);
        
        return $response;
    }

    function validate($args){
        if( ! ctype_digit($args['amount']) || $args['amount'] < 1){
            return "Invalid amount";
        }
        
        return true;
    }

    function test(){
        $this->autoRender = false;
        $vendorData = $this->Shop->getActiveVendor(65, "9167787891");
        
        var_dump($vendorData);
    }

    function addMoney($args){
        $validation = $this->validate($args);
        if($validation !== true){
            return array("status"=>"failure", "code"=>"00", "description"=>$validation);
        }
        
        $info = $this->Shop->getProdInfo($args['product_id']);
        $validate = $this->Recharge->productValidations($args, $info, array(), $args['product_id']);
        if($validate['status'] == 'failure'){
            return $validate;
        }
        
        $additional_param = array('amount'=>$args['amount'], 'dist_id'=>$_SESSION['Auth']['parent_id'], 'retailer_created'=>$_SESSION['Auth']['created'], 'retailer_id'=>$_SESSION['Auth']['id'], 'api_partner'=>$args['api_partner']);
        
        // get priority vendor list
        $vendorData = $this->Recharge->getVendorPriorityList($args['product_id'], null, $additional_param, $info);
        if($vendorData['status'] == 'failure'){ // If there is no vendor for the product then recharge cannot happen
            return $vendorData;
        }
        
        $transaction = $this->Shop->createTransaction($args['product_id'], $vendorData['info']['vendors']['0']['vendor_id'], 3, $args['mobileNumber'], $args['amount'], $args['param'], $args['ip']);
        if($transaction['status'] == 'failure') return $transaction;
        
        $this->Recharge->send_request_via_tps($transaction['tranId'], $args['product_id'], $vendorData['info']['service_id'], $args, $vendorData['info']['vendors']);
        return array('status'=>'success', 'balance'=>$transaction['balance'], 'description'=>$transaction['tranId'], 'service_charge'=>$transaction['service_charge']);
    }

    // Connecting to ONGO API for Top-Up as Merchant
    function ongoTopup($args){
        $this->log("ongoTopup", $args);
        
        $this->CREDS['ONGO']['session_key'] = $this->CREDS['ONGO']['default_session_key'];
        $ongoSession = $this->ongoLogin();
        
        $this->log("ongoTopup::ongoSession", $ongoSession);
        
        if($ongoSession['status'] == "success"){
            $this->CREDS['ONGO']['session_key'] = trim($ongoSession['sessionkey']);
            
            $ongoTransaction = $this->ongoFundTransfer($args);
            
            $this->log("ongoTopup::ongoFundTransfer response::", $ongoTransaction);
            
            if($ongoTransaction['status'] == "success"){
                $ongoTransaction['vendor_refid'] = $ongoTransaction['Stan'];
                
                $this->log("ongoTopup::ongoTransaction response::", $ongoTransaction);
                
                return $ongoTransaction;
            }
            else{
                return $ongoTransaction;
            }
        }
        else{
            return $ongoSession;
        }
    }

    function ongoRequest($params, $msg_string, $label){
        $this->log("ongoRequest::inside::", json_encode($params, $msg_string, $label));
        
        $params['Msg'] = str_replace("/", "_", $this->ongoEncrypt($msg_string, $this->CREDS['ONGO']['session_key']));
        
        $this->log("ongoRequest::inside request", json_encode($this->CREDS['ONGO']['api_url'], $params, 'GET', $label));
        
        $response = $this->request($this->CREDS['ONGO']['api_url'], $params, 'GET', $label);
        $ongo_response = array();
        if($response['success']){
            $response_object = json_decode($response['output']);
            if($response_object->status == "00"){
                $decrypted_response = $this->ongoDecrypt($response_object->Msg, $this->CREDS['ONGO']['default_session_key']);
                
                $this->log("Response decryption::" . $label . "::", $decrypted_response);
                
                if( ! empty($decrypted_response)){
                    try{
                        $response_string = explode("|", $decrypted_response);
                        
                        foreach($response_string as $rs){
                            $param = explode("=", $rs);
                            $ongo_response[$param[0]] = $param[1];
                        }
                        $ongo_response['status'] = "success";
                    }
                    catch(Exception $e){
                        $ongo_response['status'] = "failure";
                        $ongo_response['description'] = "Could not decipher cryptic message";
                        return $ongo_response;
                    }
                    
                    return $ongo_response;
                }
                else{
                    $ongo_response['status'] = "failure";
                    $ongo_response['description'] = "Could not decipher cryptic message";
                    return $ongo_response;
                }
            }
            else{
                $ongo_response['status'] = "failure";
                if(isset($response_object->errormsg)){
                    if($response_object->errormsg == "null"){
                        $ongo_response['description'] = "Something went wrong. Please try again.";
                        return $ongo_response;
                    }
                    else{
                        $ongo_response['description'] = $response_object->errormsg;
                        return $ongo_response;
                    }
                }
                else{
                    $ongo_response['description'] = "Something went wrong at Ongo";
                    return $ongo_response;
                }
            }
        }
        else{
            $ongo_response['status'] = "failure";
            $ongo_response['description'] = $response['output'];
            return $ongo_response;
        }
    }

    function ongoLogin(){
        $this->log("ongoLogin::", "inside Login");
        
        $params['TxnType'] = "Login";
        $params['SOURCEID'] = $this->CREDS['ONGO']['GUID'];
        
        $msg_string = "MOBILENO=" . $this->CREDS['ONGO']['mobile'] . "|" . "PASS=" . $this->CREDS['ONGO']['password'] . "|" . "ROLE=" . $this->CREDS['ONGO']['role'] . "|" . "GUID=" . $this->CREDS['ONGO']['GUID'];
        
        $this->log("ONGO Login Message String", $msg_string);
        
        $response = $this->ongoRequest($params, $msg_string, "ONGO Login");
        
        return $response;
    }

    function ongoFundTransfer($args){
        $this->log("ongoFundTransfer::", json_encode($args));
        
        $params['TxnType'] = "FUNDTRANSFER";
        $params['SOURCEID'] = $this->CREDS['ONGO']['GUID'];
        $params['MOBILENO'] = $this->CREDS['ONGO']['mobile'];
        
        $msg_string = "FROMMOB=" . $this->CREDS['ONGO']['mobile'] . "|" . "TOMOB=" . $args['mobileNumber'] . "|" . "AMOUNT=" . ($args['amount'] * 100) . "|" . "TRANTYPE=TOPUP|" . "GUID=" . $this->CREDS['ONGO']['GUID'];
        
        $this->log("ONGO Fund Transfer Message String", $msg_string);
        
        $response = $this->ongoRequest($params, $msg_string, "ONGO Topup");
        
        return $response;
    }

    // Encrypt Function
    function ongoEncrypt($plainText, $key){
        $this->log("ongoEncrypt::", array($plainText, $key));
        try{
            $passcrypt = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, trim($key), $this->addPadding($plainText), MCRYPT_MODE_CBC, trim($key)));
            $encode = base64_encode($passcrypt);
        }
        catch(Exception $e){
            $encode = NULL;
        }
        return $encode;
    }

    function addPadding($string){
        $block = mcrypt_get_block_size('rijndael_128', 'cbc');
        $pad = $block - (strlen($string) % $block);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }

    // Decrypt Function
    function ongoDecrypt($crypt, $key){
        try{
            $decoded = base64_decode($crypt);
            $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, trim($key), trim($decoded), MCRYPT_MODE_CBC, trim($key)));
        }
        catch(Exception $e){
            $decrypted = NULL;
        }
        return $decrypted;
    }
}	