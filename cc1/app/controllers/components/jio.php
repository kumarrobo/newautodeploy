<?php
class JioComponent extends  Object {
    var $components = array('General','Shop');
    
    function lockJioRetailer($data){
        $Id = $data['userId'];
        if($this->Shop->addMemcache("activeJioRet$Id",$data,5*60) ){
            return true;
        }
        
        return false;
    }
    
    function activeJioRetailer($amount,$zone){
        $transObj = ClassRegistry::init('User');
        $data = $transObj->query("SELECT * FROM `jio_retailer` WHERE balance >= $amount AND zone='$zone' AND active = 1 order by rand()");
        foreach($data as $row){
            $Id = $row['jio_retailer']['userId'];
            if($this->lockJioRetailer($row['jio_retailer'])){
                return $row['jio_retailer'];
            }
        }
        return;
    }
    
    function unlockJioRetailer($id){
        $this->Shop->delMemcache("activeJioRet$id");
    }
    
    function getJioRetailerBalance(){
        $transObj = ClassRegistry::init('Slaves');
        $data = $transObj->query("SELECT * FROM `jio_retailer` WHERE active = 1");
        
        $total = 0;
        foreach($data as $row){
            if($this->lockJioRetailer($row['jio_retailer'])){
                $jioBalance =  $this->pay1JioWallet($row['jio_retailer']);
                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":getJioRetailerBalance : ".$jioBalance); 
                $this->setJioRetailer(array('balance'=>$jioBalance),$row['jio_retailer']['userId']);
                $this->unlockJioRetailer($row['jio_retailer']['userId']);
            }
            else {
                $jioBalance = $row['jio_retailer']['balance'];
            }
            
            $total += $jioBalance;
        }
        
        return $total;
    }
    
    function updateJioRetailerBalance($userId,$amount){
        $transObj = ClassRegistry::init('User');
        $transObj->query("UPDATE jio_retailer SET balance = balance - $amount where userId='$userId'");
    }
    
    function setJioRetailer($params,$userId){
        $transObj = ClassRegistry::init('User');
        $set = ''; 
        foreach($params as $key=>$val){
            if(!empty($set))
                $set .=',';
            $set .= $key." = '$val' ";
        }
        $transObj->query("UPDATE jio_retailer SET $set where userId='$userId'");
    }
    
    function getJioRetailer($userId){
        $transObj = ClassRegistry::init('Slaves');
        
        $data = $transObj->query("SELECT * FROM  `jio_retailer` where userId='$userId'");
        $jioRetailer = json_encode($data[0]['jio_retailer']);
            
        return $jioRetailer;
    }
    
    function getJioRetailerInfo(){
        $jioRetailers = $this->Shop->getMemcache("pay1JioRetailerInfo");
        if(empty($jioRetailers)){
            $transObj = ClassRegistry::init('Slaves');

            $data = $transObj->query("SELECT * FROM  `jio_retailer`");
            $jioRetailers = $data;
            $this->Shop->setMemcache("pay1JioRetailerInfo",$jioRetailers,24*60*60);
        }
        
        $len = count($jioRetailers) - 1;
        $rand = rand(0,$len);
        
        return $jioRetailers[$rand]['jio_retailer'];
    }
    
    function pay1JioLogin($uid){ 
        $jioRetailer = $this->getJioRetailer($uid);
        $datetime = new DateTime();
        $DeviceDate = $datetime->format('d-m-Y H:i:s');

        $jioRetailer = json_decode($jioRetailer,1);

        $macId = $jioRetailer['macId'];
        $uid = $jioRetailer['userId'];
        $passwd = $jioRetailer['password'];
        $version = $jioRetailer['version'];
        $data = array('DeviceDate'=>$DeviceDate,'Guid'=>'','MacID'=>$macId,'Password'=>$passwd,'UserID'=>$uid,'VersionNo'=>$version);
        $post_string = json_encode($data);

        $AuthKey = base64_encode($uid.':P@ssw0rd');
        $apiKey = PAY1JIO_APIKEY;
        $header = array('Accept: application/json','Content-Type: application/json','Authorization: Basic '.$AuthKey,'X-API-Key: '.$apiKey,'Cache-Control: no-cache');
        $url= 'https://api.ril.com/v1/rpos/west/RposWS_Accounts/api/v1.0/AgentAuthentication';
        $out = $this->General->curl_header($url,$post_string,$header);
        $data = json_decode($out,1);
        if(empty($data)){
            return;
        }
        $jioData = array();
        if(array_key_exists('ErrorMsg',$data) && $data['ErrorMsg']=='Success' && array_key_exists('objSupervisorLogin',$data) && array_key_exists('lstUrl',$data['objSupervisorLogin'])){
            $jioData['guId'] = $data['objDeviceSave']['Msg'];
            $jioData['circleId'] = $data['objSupervisorLogin']['Circle_Response']['CircleId'];
            $jioData['mobile'] = $data['objSupervisorLogin']['Circle_Response']['mobile'];
            $jioData['org'] = $data['objSupervisorLogin']['Circle_Response']['org'];
            $jioData['orgname'] = $data['objSupervisorLogin']['Circle_Response']['orgname'];
            $jioData['storestate'] = $data['objSupervisorLogin']['Store_State'];
            $jioData['clientSecretKey'] = $data['objSupervisorLogin']['ClientSecretKey'];
            $jioData['orderingAPIKey'] = $data['objSupervisorLogin']['OrderingAPIKey'];
            $uid = $data['objSupervisorLogin']['Circle_Response']['uid'];
            $jioData['apiKey'] = $data['objSupervisorLogin']['APIKey'];
            $date = new DateTime(); 
            $jioData['updatedAt'] = $date->format('d/m/Y H:i:s');
            $urls = array();
            foreach($data['objSupervisorLogin']['lstUrl'] as $val){
                $urls[$val['Name']] = $val['Value'];
            }
            $jioData['urls'] = json_encode($urls);
            
            $this->setJioRetailer($jioData,$uid);
            
        }
        
        return;
    }
    
    function setPosId($jioRetailer){
        $userId = $jioRetailer['userId'];
        $passwd = $jioRetailer['password'];
        $AuthKey = base64_encode($userId.':P@ssw0rd');
        $url = $this->getURLPrefix($jioRetailer,'RPOSWS_URL');
        $ApiKey = PAY1JIO_APIKEY;
        $guId = $jioRetailer['guId'];
        $deviseTime = date('d-m-Y H:i:s');
        $macId = $jioRetailer['macId'];
        
        $post_string = "<v:Envelope xmlns:i='http://www.w3.org/2001/XMLSchema-instance' "
                . "xmlns:d='http://www.w3.org/2001/XMLSchema' "
                . "xmlns:c='http://schemas.xmlsoap.org/soap/encoding/' "
                . "xmlns:v='http://schemas.xmlsoap.org/soap/envelope/'><v:Header /><v:Body>"
                . "<LaunchDeviceSellDIB xmlns='http://tempuri.org/' id='o0' c:root='1'>"
                . "<MacId i:type='d:string'>".$macId."</MacId><EmpID i:type='d:string'>"
                . $userId."</EmpID><PassWord i:type='d:string'>".$passwd."</PassWord>"
                . "<FetchDeviceDetails i:type='d:boolean'>true</FetchDeviceDetails>"
                . "<StoreID i:type='d:string'></StoreID><POSID i:type='d:string'></POSID>"
                . "<buttonVersion i:type='d:string'></buttonVersion><tenderVersion i:type='d:string'>"
                . "</tenderVersion><DeviceDate i:type='d:string'>".$deviseTime
                . "</DeviceDate><guId i:type='d:string'>".$guId
                . "</guId></LaunchDeviceSellDIB></v:Body></v:Envelope>";
        
                    $header = array('Content-Type: text/xml','Authorization: Basic '.$AuthKey,'X-API-Key: '.$ApiKey);
                    $out = $this->General->curl_header($url,$post_string,$header,'POST',1);
                    
                    $data = json_decode($out,1);
                    if(empty($data)){
                        return ; 
                    }
                    $jioData = array();
                    $arr = $data['soap:Envelope']['soap:Body']['LaunchDeviceSellDIBResponse']['LaunchDeviceSellDIBResult']['objGetStore'];
                    if(strtolower($arr['ErrorMsg'])=='success' && array_key_exists('POSID', $arr)){
                        $jioData['posId'] = $arr['POSID'];
                        $this->setJioRetailer($jioData,$userId);
                        return $arr['POSID'];
                        
                    }
                    
                    return;
    }
    
    function getProductDetails($jioRetailer,$productId){
        $userId = $jioRetailer['userId'];
        $passwd = $jioRetailer['password'];
        $AuthKey = base64_encode($userId.':P@ssw0rd');
        $url = $this->getURLPrefix($jioRetailer,'RPOSWS_URL');
        $ApiKey = PAY1JIO_APIKEY;
        $guId = $jioRetailer['guId'];
        $deviseTime = date('d-m-Y H:i:s');
        $macId = $jioRetailer['macId'];
        $storeId = $jioRetailer['org'];
        
        $post_string = "<v:Envelope xmlns:i='http://www.w3.org/2001/XMLSchema-instance' "
                . "xmlns:d='http://www.w3.org/2001/XMLSchema' "
                . "xmlns:c='http://schemas.xmlsoap.org/soap/encoding/' "
                . "xmlns:v='http://schemas.xmlsoap.org/soap/envelope/'><v:Header /><v:Body>"
                . "<GetItemMrpDetailsRPOS xmlns='http://tempuri.org/' id='o0' c:root='1'>"
                . "<ProductID i:type='d:string'>$productId</ProductID>"
                . "<StoreID i:type='d:string'>$storeId</StoreID>"
                . "<guId i:type='d:string'>$guId</guId></GetItemMrpDetailsRPOS></v:Body></v:Envelope>";
        
                    $header = array('Content-Type: text/xml','Authorization: Basic '.$AuthKey,'X-API-Key: '.$ApiKey);
                    $out = $this->General->curl_header($url,$post_string,$header,'POST',1);
                    $data = json_decode($out,1);
                    if(empty($data)){
                        return ; 
                    }
                    $arr = $data['soap:Envelope']['soap:Body']['GetItemMrpDetailsRPOSResponse']['GetItemMrpDetailsRPOSResult'];
                    if(strtolower($arr['Errormsg'])=='success' && array_key_exists('Taxid', $arr)){
                        return array('rechargeDesc'=>$arr['Desc'],'Taxid'=>$arr['Taxid'],'Taxrate'=>$arr['Taxrate'],'Taxchar'=>$arr['Taxchar'],'Itemtype'=>$arr['Itemtype'],'MaximumQtyAllowed'=>$arr['MaximumQtyAllowed'],'Markdownallowed'=>$arr['Markdownallowed'],'unitOfMeasure'=>$arr['unitOfMeasure'],'itemMeasurementType'=>$arr['itemMeasurementType']);
                        
                    }
                    $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":product details: :: $userId : $productId  ".$out);
                    return;
    }
    
    function getTaxDetails($jioRetailer,$productId,$price){
        $userId = $jioRetailer['userId'];
        $passwd = $jioRetailer['password'];
        $AuthKey = base64_encode($userId.':P@ssw0rd');
        $url = $this->getURLPrefix($jioRetailer,'RPOSWS_URL');
        $ApiKey = PAY1JIO_APIKEY;
        $guId = $jioRetailer['guId'];
        $deviseTime = date('d-m-Y H:i:s');
        $macId = $jioRetailer['macId'];
        $storeId = $jioRetailer['org'];
        
        
        $post_string = "<v:Envelope xmlns:i='http://www.w3.org/2001/XMLSchema-instance'"
                . " xmlns:d='http://www.w3.org/2001/XMLSchema' "
                . "xmlns:c='http://schemas.xmlsoap.org/soap/encoding/' "
                . "xmlns:v='http://schemas.xmlsoap.org/soap/envelope/'><v:Header /><v:Body>"
                . "<CalculateTaxSummary xmlns='http://tempuri.org/' id='o0' c:root='1'>"
                . "<strXmlProduct i:type='d:string'>&lt;?xml version='1.0' "
                . "encoding='UTF-8'?&gt;&lt;DocumentElement&gt;&lt;ProductTax&gt;&lt;SEQ_ID&gt;1"
                . "&lt;/SEQ_ID&gt;&lt;ITEM_ID&gt;$productId&lt;/ITEM_ID&gt;&lt;STORENUMBER&gt;"
                . "$storeId&lt;/STORENUMBER&gt;&lt;SELLING_PRICE&gt;$price&lt;/SELLING_PRICE&gt;"
                . "&lt;QTY&gt;1&lt;/QTY&gt;&lt;OrgPerUnitPrice&gt;$price&lt;/OrgPerUnitPrice&gt;&lt;/ProductTax&gt;&lt;/DocumentElement&gt;</strXmlProduct><guId i:type='d:string'>$guId</guId></CalculateTaxSummary></v:Body></v:Envelope>";
        
                    $header = array('Content-Type: text/xml','Authorization: Basic '.$AuthKey,'X-API-Key: '.$ApiKey);
                    $out = $this->General->curl_header($url,$post_string,$header,'POST',1);
                    $data = json_decode($out,1);
                    if(empty($data)){
                        return ; 
                    }
                    $arr = $data['soap:Envelope']['soap:Body']['CalculateTaxSummaryResponse']['CalculateTaxSummaryResult'];
                    if(strtolower($arr['Errormsg'])=='success' && array_key_exists('ListTax', $arr) && array_key_exists('TaxEntity', $arr['ListTax'])){
                        $result = $arr['ListTax']['TaxEntity'];
                        if(is_array($result) && !empty($result)){
                            return array('MainTax_Id'=>$result[0]['MainTax_Id'],'AdditionalTax_Id'=>$result[0]['AdditionalTax_Id'],'AdditionalTax_Percent'=>$result[0]['AdditionalTax_Percent'],'Tax_Desc'=>$result[0]['Tax_Desc'],'addition_tax'=>$result[0]['Tax_After_AdditionalTax'],'totaltax'=>$result[0]['Tax_Amount']);
                        }
                        
                    }
                    $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":tax details: :: $userId : $productId : $price ".$out);
                    return;
    }
    
    function getURLPrefix($jioRetailer,$key){
        $urls = json_decode($jioRetailer['urls'],true);
        $url_prefix= $urls[$key];
        return $url_prefix;
    }
    
    function pay1JioUuid($jioRetailer){
        $userId = $jioRetailer['userId'];
        $token = $this->Shop->getMemcache("authTokenUuid$userId");
        if($token === false){
            $data = array('AgentID'=>$userId,'Guid'=>'','Type'=>'');
            $post_string = json_encode($data);

            $AuthKey = base64_encode($userId.':P@ssw0rd');
            $ApiKey = $jioRetailer['orderingAPIKey'];//OrderingAPIKey from Login User Api
            $header = array('Content-Type: application/json','Authorization: Basic '.$AuthKey,'X-API-Key: '.$ApiKey,'Cache-Control: no-cache');
            $url_prefix = $this->getURLPrefix($jioRetailer,'ENHANCES_SERVER_DOMAIN');
            $url= $url_prefix.'GetJioTokenDealerStockDetails';
            $out = $this->General->curl_header($url,$post_string,$header);

            $data = json_decode($out,1);
            if(array_key_exists('Guid', $data)){
                $token = $data['Guid'];
                $this->Shop->setMemcache("authTokenUuid$userId",$token,60*60);
            }else{
                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":Guid retailer id: :: $userId ".json_encode($data));
            }
        }
        
        return $token;
    }
    
    function pay1JioAuthToken($jioRetailer,$power=null){
        //access_token required to generate orderid
        $userId = $jioRetailer['userId'];
        $token = $this->Shop->getMemcache("authToken$userId");
        if($token === false || $power == 1){
            $state = urlencode($jioRetailer['storestate']); // from login user api
            $Client_secrest = $jioRetailer['clientSecretKey'];  // from login user api
            $Client_id = PAY1JIO_APIKEY;//constant api key
            $params = "state=$state&scope=agent&client_id=$Client_id&client_secret=$Client_secrest&grant_type=password";
            $url_prefix = $this->getURLPrefix($jioRetailer,'ACCESS_TOKEN_URL');
            $url = $url_prefix.'?'.$params;
            
            $password = $jioRetailer['password'];
            $header = array('Content-Type: application/x-www-form-urlencoded','username: '.$userId,'password: '.$password);
            $out = $this->General->curl_header($url,'',$header);
            $data = json_decode($out,1);
            
            if(array_key_exists('access_token', $data)){
                $token = $data['access_token'];
                $this->Shop->setMemcache("authToken$userId",$token,60*60);
            }else{
                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":Authtoken retailer id: :: $userId ".json_encode($data));
            }
        }
        return $token;
    }
    
    function pay1JioPlan($jioRetailer,$userCircle='MU',$amount='1',$guId){  
         $url_prefix = $this->getURLPrefix($jioRetailer,'ACCOUNTS_URL');
         $url = $url_prefix.'GetRechargePlans';
         $ApiKey = PAY1JIO_APIKEY;
         $userId = $jioRetailer['userId'];
         $circleId = $jioRetailer['circleId'];
         $AuthKey = base64_encode($userId.':P@ssw0rd');
         $header = array('Accept: application/json','Content-Type: application/json','Authorization: Basic '.$AuthKey,'X-API-Key: '.$ApiKey,'Cache-Control: no-cache');
         
         $segment = json_encode(array("0001","015001","025001"),JSON_UNESCAPED_UNICODE,2);
         $guid = $guId;//guid key
         $postData = array('Agent_Circle_ID'=>"$circleId",'Circle_ID'=>"$userCircle",'RT_Flag'=>'R','Segments'=>array("0001","015001","025001"),'guId'=>$guid);
         $postData = json_encode($postData);
         $out = $this->General->curl_header($url,$postData,$header);
        $data = json_decode($out,1);
        $result = array();
        if(array_key_exists('ErrorCode',$data) && $data['ErrorCode']==0 ){
                foreach($data['SegmentType'][0]['RechargePlansSegmentWise'] as $plan){
                    if($amount == $plan['RECHARGE_AMOUNT']){
                        return array('status'=>'success','planId'=>$plan['RECHARGE_CODE']);
                    }
                    
                }
        }
       $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":pay1JioPlan : ".$out." Reatiler : ".$userId);
       return array('status'=>'fail','description'=>'Recharge Plan with amount '.$amount.' not exist');
        
    }
    
    function pay1JioOrderId($jioRetailer,$authId){
        $url_prefix = $this->getURLPrefix($jioRetailer,'ORN_URL');
        $url = $url_prefix.'?transactionType=BR&count=1';
        $ApiKey = PAY1JIO_APIKEY;
        $AuthKey = $authId;//access token key from auth token api
        
        $header = array('X-API-Key: '.$ApiKey,'Authorization: Bearer '.$AuthKey,'Content-Type: application/json');
        $out = $this->General->curl_header($url,'',$header,'GET');
        $data = json_decode($out,1); 
        
        if(array_key_exists('success', $data) && $data['success']==1 
                 && array_key_exists('transactionRefNumber', $data)){
            
            return $data['transactionRefNumber'][0];
        }
        
        return;
    }
    
    function pay1JioWallet($jioRetailer){ 
        
        $url = $this->getURLPrefix($jioRetailer,'WALLET_BALANCE_URL');  
        
        $ApiKey = PAY1JIO_APIKEY;
        $merchantCode = $jioRetailer['org'];//org key from login Api
        $postData = array('merchantCodes'=>array($merchantCode));
        $postData = json_encode($postData);

        $AuthKey = $this->pay1JioAuthToken($jioRetailer);//access token key from auth token api
        if(empty($AuthKey)) return;
        $header = array('Content-Type: application/json','Authorization: Bearer '.$AuthKey,'X-API-Key: '.$ApiKey,'Cache-Control: no-cache');
        $out = $this->General->curl_header($url,$postData,$header);
        $data = json_decode($out,1);
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":Pay1jiowallet : ".$out." retailerId : ".$jioRetailer['userId']." org : ".$postData); 
        if(array_key_exists('success', $data) && $data['success']==1 && array_key_exists('merchants', $data)){
            
            return $data['merchants'][0]['balance'];
            
         }
         else if(array_key_exists('errors', $data) && $data['errors']['reason'] == 'invalid_request'){
             $this->pay1JioAuthToken($jioRetailer,1);
         }
         
         return ;
         
    }
    
    function pay1JioTransaction($jioRetailer,$guId){
        
        $url_prefix = $this->getURLPrefix($jioRetailer,'RPOSWS_URL');
        
        $url = $url_prefix;
        $userId = $jioRetailer['userId'];
        $posId = $jioRetailer['posId'];
        $guId = $guId;
        $storeId = $jioRetailer['org'];
        $postData = '<v:Envelope xmlns:i="http://www.w3.org/2001/XMLSchema-instance" xmlns:d="http://www.w3.org/2001/XMLSchema" xmlns:c="http://schemas.xmlsoap.org/soap/encoding/" xmlns:v="http://schemas.xmlsoap.org/soap/envelope/"><v:Header/><v:Body><GetTransactionId xmlns="http://tempuri.org/" id="o0" c:root="1"><StoreID i:type="d:string">'.$storeId.'</StoreID><PosID i:type="d:string">'.$posId.'</PosID><guId i:type="d:string">'.$guId.'</guId></GetTransactionId></v:Body></v:Envelope>';
        $ApiKey = PAY1JIO_APIKEY;
        $AuthKey = base64_encode($userId.':P@ssw0rd');
        $header = array('Content-Type: text/xml','X-API-Key: '.$ApiKey,'Authorization: Basic '.$AuthKey,'SOAPAction: http://tempuri.org/GetTransactionId','Cache-Control: no-cache');
        $out = $this->General->curl_header($url,$postData,$header,'POST',1);
        $data = json_decode($out,1);
        
        if(is_array($data) && array_key_exists('soap:Envelope', $data) && 
                array_key_exists('soap:Body', $data['soap:Envelope']) && 
                array_key_exists('GetTransactionIdResponse', $data['soap:Envelope']['soap:Body']) && array_key_exists('GetTransactionIdResult', $data['soap:Envelope']['soap:Body']['GetTransactionIdResponse']) &&  array_key_exists('ErrorCode', $data['soap:Envelope']['soap:Body']['GetTransactionIdResponse']['GetTransactionIdResult']) &&
                $data['soap:Envelope']['soap:Body']['GetTransactionIdResponse']['GetTransactionIdResult']['ErrorCode']==0){
          
            return $data['soap:Envelope']['soap:Body']['GetTransactionIdResponse']['GetTransactionIdResult']['TxnID'];
            
        }
        
        return;
         
    }
    
    function pay1JioUserInfo($mobile){
        
        $jioRetailer= $this->getJioRetailerInfo();
        
        $url_prefix = $this->getURLPrefix($jioRetailer,'FIND_CUSTOMER_ACCOUNTS_CCI_URL');
        
        $uid = $jioRetailer['userId'];
        $authId = $this->pay1JioAuthToken($jioRetailer);
        if(empty($authId))
            return array('status'=>'fail','description'=>'Not getting Authtoken');
       
        
        $url = $url_prefix;
        $postData = array('customerId'=>'','serviceStatusList'=>array('',''),'accountType'=>'','identifiers'=>array(array('value'=>$mobile,'subCategory'=>'2')),'filterKey'=>'','channel'=>'40');
        $postData = json_encode($postData);
        $ApiKey = PAY1JIO_APIKEY;
        $AuthKey = $authId; //access token
        $header = array('X-API-Key: '.$ApiKey,'Authorization: Bearer '.$AuthKey,'Content-Type: application/json','Cache-Control: no-cache');
        $out = $this->General->curl_header($url,$postData,$header,'POST',0,1);
        $str1 = strstr($out,'jioroute:');
        if(strlen($str1) > 0){
            $jioroute= trim(substr($str1,strlen('jioroute:'),strpos($str1,'Content-Type')-strlen('jioroute:')));
            
            $str2 = strstr($out,'{');
            $data = json_decode($str2,1);
            $zone = substr($jioroute,0,2);
            if(!in_array($zone, array('WE','EA','SO','NO'))){
                return array('status'=>'fail','description'=>'Not getting proper jio route '.$jioroute);
            }
            
            $userInfo = array();
            
            if(is_array($data) && array_key_exists('success', $data) && $data['success']==1){
                $userInfo = array('status'=>'success','jioroute'=>$jioroute,'customerId'=>$data['customerId'],'circleId'=>$data['accounts'][0]['circleId'], 'mobile'=>$mobile  ,'accountId' =>$data['accounts'][0]['accountId'],'zone'=>$zone);
                return $userInfo;
            }
        
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":userinfo: ::".$mobile." :: ".json_encode($out));
        return;
       
    }
    
    function getTransId($userData,$jioRetailer){
            $authId = $this->pay1JioAuthToken($jioRetailer);
             if(empty($authId))
                 return array('status'=>'fail','description'=>'Not getting Authtoken');
       
            $guId = $this->pay1JioUuid($jioRetailer);
            if(empty($guId))
                return array('status'=>'fail','description'=>'Not getting Guid');

            $txnId = $this->pay1JioTransaction($jioRetailer,$guId);
            if(empty($txnId))
                return array('status'=>'fail','description'=>'Not getting txnId');
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":guid: $guId :: Authid : $authId ");
            
            $orderId = $this->pay1JioOrderId($jioRetailer,$authId); 
            if(empty($orderId))
                return array('status'=>'fail','description'=>'Not getting orderId');
            $userData['transId'] = $txnId;
            $userData['orderId'] = $orderId;
            $userData['guiId'] = $guId;
            $userData['authId'] = $authId;
            
            return $userData;
            
    }
    
    function pay1JioRecharge($userInfo,$jioRetailer,$mobile,$amount){
        
        $userData= $this->getTransId($userInfo,$jioRetailer);
        if(empty($userData) || $userData['status']=='fail'){
                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').": mobile: :: $mobile ".json_encode($userData)." retailer id :".$jioRetailer['userId']);
                return array('status'=>'fail','description'=>'Problem in getting user info or session expired');
            }
            
            $circle = $userData['circleId'];
            $guId = $userData['guiId'];
            
            if(strstr($amount,'.0',1)){
                $recAmt = strstr($amount,'.0',1);
            }else{
                $recAmt = $amount;
                $amount = $amount.".00";
            }

            $checkPlan = $this->pay1JioPlan($jioRetailer,$circle,$recAmt,$guId);
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').": planId: :: $mobile ".json_encode($checkPlan).": TransactionInfo : ::".json_encode($userData)." user info :: ". json_encode($userInfo)." :: retailer id :: ".$jioRetailer['userId']);
            $panId = '';
            if($checkPlan['status']=='fail'){
               return array('status'=>'fail','description'=>$checkPlan['description']);
            }
            else
                $planId = $checkPlan['planId'];
                
                if(!empty($planId)){
                    $out = $this->payJioTransaction($jioRetailer,$userData,$amount,$planId);
                    $data = json_decode($out,1);
                    $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s')." $mobile :pay1jioTransaction: ::".$out." :: retailer id :: ".$jioRetailer['userId']);
                    if(empty($data)){
                        return array('status'=>'pending','description'=>'Request timed out'); 
                    }
                    $msg = $data['soap:Envelope']['soap:Body']['UploadCAFTransactionDataResponse']['UploadCAFTransactionDataResult']['Statusmsg'];
                    if($data['soap:Envelope']['soap:Body']['UploadCAFTransactionDataResponse']['UploadCAFTransactionDataResult']['Errorcode']=='0' || !empty($msg)){
                        
                        if(strpos($msg,'Success')!==false){
                            $arr = explode('|',$msg);
                            $txnId = $arr[1];
                            if($txnId>$userData['transId']){
                                $txnId = $userData['orderId']."-". $jioRetailer['userId'];
                                return array('status'=>'success','txnId'=>$txnId,'description'=>'success'); 
                            }
                            else{
                                return array('status'=>'fail','description'=>$msg); 
                            }
                        }
                            
                        return array('status'=>'fail','description'=>$msg); 
                        
                    }
                    return array('status'=>'fail','description'=>'session expired'); 
            }

            return array('status'=>'fail','description'=>'PlanId not found'); 
        }
        
        function payJioTransaction($jioRetailer,$userInfo,$amount,$planId,$retry = 0){
            //header('Content-Type: text/xml');
            $url_prefix = $this->getURLPrefix($jioRetailer,'RPOSWS_URL');
            $url = $url_prefix;
            $ApiKey = PAY1JIO_APIKEY;
            $userId = $jioRetailer['userId'];
            $AuthKey = base64_encode($userId.':P@ssw0rd');
            $header = array('SOAPAction: http://tempuri.org/UploadCAFTransactionData','X-API-Key: '.$ApiKey,'Authorization: Basic'.$AuthKey,'Content-Type: text/xml','Cache-Control: no-cache');
            $orderId = $userInfo['orderId'];
            $transId = $userInfo['transId'];
            $StoreNo = $jioRetailer['org'];
            $posId = $jioRetailer['posId'];
            $rechargeAmt=$amount;
            $txnId = $transId;
            $num = 4-strlen($txnId);
            $transactionId='';
            for($i=0;$i<$num;$i++){
                $transactionId.="0";
            }
            $transactionId.=$txnId;
            $ProductVersion = $jioRetailer['version'];
            
            $LogonTime = $jioRetailer['updatedAt'];
            
            
            $currentdate = new DateTime();
            //$currentdate->add($interval);
            $ScanTime = $currentdate->format('d/m/Y H:i:s');
            
            $interval = new DateInterval('PT5S');
            $currentdate->add($interval);
            $txnStartTime = $currentdate->format('d/m/Y H:i:s');
            
            $currentdate->add($interval);
            $txnEndTime = $currentdate->format('d/m/Y H:i:s');
            
            $currentdate = new DateTime();
            $interval = new DateInterval('PT7S');
            $currentdate->add($interval);
            $PaymentStartTime = $currentdate->format('d/m/Y H:i:s');
            
            $currentdate->add($interval);
            $PaymentEndTime = $currentdate->format('d/m/Y H:i:s');
            $DeviceID = $StoreNo.$posId;
            $TransactionType = 'SALE';
            $ReceiptRefID = $StoreNo.$posId.$transactionId.date('dmY');
            $ReceiptTextPath="C:\\RPOSStoreReceipt\\".date("dmY")."\\".$ReceiptRefID.".txt";
            
            $ProductID = $planId;
            $planId=$planId;
            
            $mobile = $userInfo['mobile'];
            $userCircle = $userInfo['circleId'];
            $customerId = $userInfo['customerId'];
            $accountID = $userInfo['accountId'];
            $retailerCircle= $jioRetailer['circleId'];;
            $jioRoute = $userInfo['jioroute'];
            $CAFFields = "$orderId|1||$planId|$rechargeAmt|$mobile|$userCircle|$customerId|$mobile||$accountID|$retailerCircle|$jioRoute";
            $guId = $userInfo['guiId'];
            $jioRetailer['guId'] = $guId;
            $rechargePlan = $this->getProductDetails($jioRetailer,$planId);
            $taxDetails = $this->getTaxDetails($jioRetailer,$planId,$amount);
            
            $TotalTaxAmount = '';
            $TotalAddnlTax = '';
            $Markdownallowed = 1;
            $MaximumQuantity = 999;
            $Description = '';
            $ItemType ='TYPE_GRAB_AND_GO';
            $TaxID = 1014;
            $TaxRate = 4;
            $TotalVAT = '';
            $TaxChar = 'VO';
            $TaxableAmount = '0.96';
            $tax1Desc = 'ADD VAT';
            $tax1Amount = '';
            $tax1Rate = '1.0';
            $tax1Id = '1015';
            $UnitOfMeasure = 'EA';
            $itemMeasurementType = 'MEASURE_TYPE_QUANTITY';
            
            if(!empty($rechargePlan) && !empty($taxDetails)){
                $TaxableAmount = $amount-$taxDetails['totaltax'];
                $TotalVAT = $taxDetails['totaltax']-$taxDetails['addition_tax'];
                $TotalAddnlTax = $taxDetails['addition_tax'];
                $TotalTaxAmount = $taxDetails['totaltax'];
                $ItemType = $rechargePlan['Itemtype'];
                $Description = $rechargePlan['rechargeDesc'];
                $MaximumQuantity = $rechargePlan['MaximumQtyAllowed'];
                $Markdownallowed = $rechargePlan['Markdownallowed'];
                $TaxID = $rechargePlan['Taxid'];
                $TaxChar = $rechargePlan['Taxchar'];
                $TaxRate = $rechargePlan['Taxrate'];
                $tax1Desc = $taxDetails['Tax_Desc'];
                $tax1Amount = $taxDetails['addition_tax'];
                $tax1Id = $taxDetails['AdditionalTax_Id'];
                $tax1Rate = $taxDetails['AdditionalTax_Percent'];
                $itemMeasurementType = $rechargePlan['itemMeasurementType'];
                $UnitOfMeasure =  $rechargePlan['unitOfMeasure'];
            }
            
            
            $soapData = '<v:Envelope xmlns:i="http://www.w3.org/2001/XMLSchema-instance"'.
                    ' xmlns:d="http://www.w3.org/2001/XMLSchema" '.
                    ' xmlns:c="http://schemas.xmlsoap.org/soap/encoding/" '.
                    ' xmlns:v="http://schemas.xmlsoap.org/soap/envelope/"><v:Header />'.
                    '<v:Body><UploadCAFTransactionData xmlns="http://tempuri.org/" id="o0" c:root="1">'.
                    '<StoreNo i:type="d:string">'.$StoreNo.'</StoreNo>'.
                    '<transXml i:type="d:string">&lt;TxnInfo&gt;&lt;TxnHeader&gt;&lt;TxnId&gt;'.$transId.
                    '&lt;/TxnId&gt;&lt;ESOrderID&gt;'.$orderId.'&lt;/ESOrderID&gt;&lt;OrderType&gt;'.
                    'RECHARGE&lt;/OrderType&gt;&lt;ProductVersion&gt;'.
                    $ProductVersion.'&lt;/ProductVersion&gt;&lt;PanCard&gt;&lt;/PanCard&gt;'.
                    '&lt;IsVatExtra&gt;false&lt;/IsVatExtra&gt;&lt;ReceiptRefID&gt;'.$ReceiptRefID.
                    '&lt;/ReceiptRefID&gt;&lt;TxnTotal&gt;'.$rechargeAmt.
                    '&lt;/TxnTotal&gt;&lt;TxnStatus&gt;COMPLETED&lt;/TxnStatus&gt;&lt;TxnStartTime&gt;'.
                    $txnStartTime.'&lt;/TxnStartTime&gt;&lt;TxnEndTime&gt;'.$txnEndTime.
                    '&lt;/TxnEndTime&gt;&lt;VoidTxnSupervisorID&gt;&lt;/VoidTxnSupervisorID&gt;&lt;'.
                    'RefundTxnSupervisorID&gt;&lt;/RefundTxnSupervisorID&gt;&lt;TxnMarkDownReason&gt;'.
                    '&lt;/TxnMarkDownReason&gt;&lt;TxnMarkDownReasonDesc&gt;&lt;/TxnMarkDownReasonDesc'.
                    '&gt;&lt;RoundOffConfigValue&gt;0.50&lt;/RoundOffConfigValue&gt;&lt;RoundOffAmount&gt;'.
                    '-0.00&lt;/RoundOffAmount&gt;&lt;PaymentStartTime&gt;'.$PaymentStartTime.
                    '&lt;/PaymentStartTime&gt;&lt;PaymentEndTime&gt;'.$PaymentEndTime.'&lt;/PaymentEndTime&gt;'.
                    '&lt;UserID&gt;'.$userId.'&lt;/UserID&gt;&lt;DeviceID&gt;'.$DeviceID.'&lt;/DeviceID&gt;&lt;'.
                    'IsAddressCaptured&gt;&lt;/IsAddressCaptured&gt;&lt;CustomerName&gt;&lt;/CustomerName&gt;&lt;'.
                    'EmailID&gt;&lt;/EmailID&gt;&lt;ContactNumber&gt;&lt;/ContactNumber&gt;&lt;FlatNo&gt;'.
                    '&lt;/FlatNo&gt;&lt;FloorNo&gt;&lt;/FloorNo&gt;&lt;BlockNo&gt;&lt;/BlockNo&gt;&lt;BuildingName'.
                    '&gt;&lt;/BuildingName&gt;&lt;SocietyName&gt;&lt;/SocietyName&gt;&lt;PlotNo&gt;&lt;/PlotNo&gt;'.
                    '&lt;Street&gt;&lt;/Street&gt;&lt;Sector&gt;&lt;/Sector&gt;&lt;Area&gt;&lt;/Area&gt;&lt;City&gt;'.
                    '&lt;/City&gt;&lt;State&gt;&lt;/State&gt;&lt;Pincode&gt;&lt;/Pincode&gt;&lt;CustomerID&gt;'.
                    '&lt;/CustomerID&gt;&lt;ChangeDue&gt;0.00&lt;/ChangeDue&gt;&lt;isMDTApplied&gt;&lt;/isMDTApplied'.
                    '&gt;&lt;TxnDiscValue&gt;&lt;/TxnDiscValue&gt;&lt;TxnDiscValueFlag&gt;&lt;/TxnDiscValueFlag&gt;'.
                    '&lt;TxnAppliedDiscValue&gt;0.00&lt;/TxnAppliedDiscValue&gt;&lt;TxnDiscSupervisorID&gt;'.
                    '&lt;/TxnDiscSupervisorID&gt;&lt;TransactionType&gt;SALE&lt;/TransactionType&gt;&lt;'.
                    'TxnSalesManID&gt;'.$userId.'&lt;/TxnSalesManID&gt;&lt;PromoTotal&gt;0&lt;/PromoTotal&gt;&lt;'.
                    'TaxableAmount&gt;'.$TaxableAmount.'&lt;/TaxableAmount&gt;&lt;CurReferenceID&gt;1&lt;/CurReferenceID&gt;&lt;'.
                    'TxnDiscAppliedTime&gt;&lt;/TxnDiscAppliedTime&gt;&lt;ResumeOrgTxnID&gt;&lt;/ResumeOrgTxnID&gt;'.
                    '&lt;ResumeOrgPosNum&gt;&lt;/ResumeOrgPosNum&gt;&lt;ResumeOrgDateTime&gt;&lt;/ResumeOrgDateTime'.
                    '&gt;&lt;LogonTime&gt;'.$LogonTime.'&lt;/LogonTime&gt;&lt;ItemCount&gt;1&lt;/ItemCount&gt;'.
                    '&lt;IsCouponApplied &gt;&lt;/IsCouponApplied &gt;&lt;ExpectedDeliveryDate&gt;'.
                    '&lt;/ExpectedDeliveryDate&gt;&lt;ExpectedDeliveryTime&gt;&lt;/ExpectedDeliveryTime&gt;&lt;'.
                    'CouponID &gt;&lt;/CouponID &gt;&lt;CouponType &gt;&lt;/CouponType &gt;&lt;CouponValue&gt;0.00'.
                    '&lt;/CouponValue&gt;&lt;CouponValueType &gt;&lt;/CouponValueType &gt;&lt;CouponValueParam '.
                    '&gt;&lt;/CouponValueParam &gt;&lt;TxnCouponSupervisorID&gt;&lt;/TxnCouponSupervisorID&gt;&lt;'.
                    'IsLoyaltyCardCaptured&gt;&lt;/IsLoyaltyCardCaptured&gt;&lt;LoyaltyCardNumber&gt;'.
                    '&lt;/LoyaltyCardNumber&gt;&lt;IsLoyaltyRedeemed&gt;&lt;/IsLoyaltyRedeemed&gt;&lt;RedeemAmount'.
                    '&gt;&lt;/RedeemAmount&gt;&lt;BalanceLoyaltyPoints&gt;&lt;/BalanceLoyaltyPoints&gt;&lt;'.
                    'IsValidLoyaltyRedemption&gt;&lt;/IsValidLoyaltyRedemption&gt;&lt;IDProofCaptureStatus&gt;'.
                    '&lt;/IDProofCaptureStatus&gt;&lt;IsIDProofRequiredFlag&gt;&lt;/IsIDProofRequiredFlag&gt;&lt;'.
                    'ReceiptTextPath&gt;'.$ReceiptTextPath.'&lt;/ReceiptTextPath&gt;&lt;TotalVAT&gt;'.$TotalVAT.'&lt;/TotalVAT'.
                    '&gt;&lt;TotalAddnlTax&gt;'.$TotalAddnlTax.'&lt;/TotalAddnlTax&gt;&lt;TotalSurcharge&gt;0.00&lt;/TotalSurcharge'.
                    '&gt;&lt;TotalTaxAmount&gt;'.$TotalTaxAmount.'&lt;/TotalTaxAmount&gt;&lt;IsTaxCalculationRequired&gt;1'.
                    '&lt;/IsTaxCalculationRequired&gt;&lt;/TxnHeader&gt;&lt;TxnItemList&gt;&lt;TxnItem&gt;&lt;'.
                    'ProductID&gt;'.$ProductID.'&lt;/ProductID&gt;&lt;SequenceID&gt;1&lt;/SequenceID&gt;&lt;'.
                    'OrgPrice&gt;'.$rechargeAmt.'&lt;/OrgPrice&gt;&lt;SellingPrice&gt;'.$rechargeAmt.
                    '&lt;/SellingPrice&gt;&lt;Quantity&gt;1&lt;/Quantity&gt;&lt;QuantityAdded&gt;'.
                    '&lt;/QuantityAdded&gt;&lt;DiscountID&gt;&lt;/DiscountID&gt;&lt;DiscountAmount&gt;0.00'.
                    '&lt;/DiscountAmount&gt;&lt;DiscountPercentage&gt;0.00&lt;/DiscountPercentage&gt;&lt;'.
                    'ItemEmpDiscSupervisorID&gt;&lt;/ItemEmpDiscSupervisorID&gt;&lt;SerialNumber&gt;'.
                    '&lt;/SerialNumber&gt;&lt;IMEI&gt;&lt;/IMEI&gt;&lt;DemoReqdorSerialNo&gt;'.
                    '&lt;/DemoReqdorSerialNo&gt;&lt;GiftVoucherNo&gt;&lt;/GiftVoucherNo&gt;&lt;GiftorPrepaidCardNo'.
                    '&gt;&lt;/GiftorPrepaidCardNo&gt;&lt;RefNo&gt;&lt;/RefNo&gt;&lt;MobileNo&gt;&lt;/MobileNo&gt;'.
                    '&lt;OrderNo&gt;&lt;/OrderNo&gt;&lt;AssemblyRequire&gt;&lt;/AssemblyRequire&gt;&lt;TransactionID'.
                    '&gt;&lt;/TransactionID&gt;&lt;WiproLimited&gt;&lt;/WiproLimited&gt;&lt;EnterEANorSKU&gt;'.
                    '&lt;/EnterEANorSKU&gt;&lt;SalesmanCode&gt;&lt;/SalesmanCode&gt;&lt;EmployeeCode&gt;'.
                    '&lt;/EmployeeCode&gt;&lt;ItemType&gt;'.$ItemType.'&lt;/ItemType&gt;&lt;Description&gt;'.$Description.
                    '&lt;/Description&gt;&lt;EntryMethod&gt;S&lt;/EntryMethod&gt;&lt;ScanTime&gt;'.$ScanTime.'&lt;/ScanTime&gt;'.
                    '&lt;ItemMarkDownReason&gt;&lt;/ItemMarkDownReason&gt;&lt;ItemMarkDownDescription&gt;'.
                    '&lt;/ItemMarkDownDescription&gt;&lt;ItemMarkDownSupervisorID&gt;&lt;/ItemMarkDownSupervisorID'.
                    '&gt;&lt;isMDIApplied&gt;0&lt;/isMDIApplied&gt;&lt;ItemSellingPrice&gt;'.$rechargeAmt.
                    '&lt;/ItemSellingPrice&gt;&lt;ItemUnitPriceAfterDiscount&gt;'.$rechargeAmt.'&lt;/ItemUnitPriceAfterDiscount'.
                    '&gt;&lt;ItemMarkDownDiscountValue&gt;0.00&lt;/ItemMarkDownDiscountValue&gt;&lt;'.
                    'ItemMarkDownDiscountFlag&gt;&lt;/ItemMarkDownDiscountFlag&gt;&lt;ItemMarkDownDiscountParam&gt;'.
                    '0.00&lt;/ItemMarkDownDiscountParam&gt;&lt;ItemEmployeeDiscount&gt;0.00&lt;/ItemEmployeeDiscount'.
                    '&gt;&lt;isDiscountApplied&gt;0&lt;/isDiscountApplied&gt;&lt;MaximumQuantity&gt;'.$MaximumQuantity.
                    '&lt;/MaximumQuantity&gt;&lt;MarkDownAllowed&gt;'.$Markdownallowed.'&lt;/MarkDownAllowed&gt;&lt;StockCheckRequired'.
                    '&gt;0&lt;/StockCheckRequired&gt;&lt;StockCount&gt;&lt;/StockCount&gt;&lt;QuantityFlag&gt;0'.
                    '&lt;/QuantityFlag&gt;&lt;SalesManID&gt;&lt;/SalesManID&gt;&lt;SupervisorID&gt;&lt;/SupervisorID'.
                    '&gt;&lt;VoidSupervisorID&gt;&lt;/VoidSupervisorID&gt;&lt;isPromotionAvailableFlag&gt;0'.
                    '&lt;/isPromotionAvailableFlag&gt;&lt;isLinkedItemsPresentFlag&gt;0&lt;/isLinkedItemsPresentFlag'.
                    '&gt;&lt;Status&gt;&lt;/Status&gt;&lt;VoidedDateTime&gt;&lt;/VoidedDateTime&gt;&lt;ItemDiscAppliedTime'.
                    '&gt;&lt;/ItemDiscAppliedTime&gt;&lt;QtyChangeDateTime&gt;&lt;/QtyChangeDateTime&gt;&lt;'.
                    'MDIAppliedDateTime&gt;&lt;/MDIAppliedDateTime&gt;&lt;TaxID&gt;'.$TaxID.'&lt;/TaxID&gt;&lt;TaxChar&gt;'.
                    $TaxChar.'&lt;/TaxChar&gt;&lt;TaxRate&gt;'.$TaxRate.'&lt;/TaxRate&gt;&lt;OtherDiscounts&gt;0.00&lt;/OtherDiscounts'.
                    '&gt;&lt;TaxAmount&gt;'.$TotalVAT.'&lt;/TaxAmount&gt;&lt;TaxableAmount&gt;'.$TaxableAmount.'&lt;/TaxableAmount&gt;&lt;'.
                    'TaxType&gt;TAX_TYPE_PERCENTAGE &lt;/TaxType&gt;&lt;TaxCodeType&gt;TAX_TYPE_INCL&lt;/TaxCodeType'.
                    '&gt;&lt;EffectivePrice&gt;'.$rechargeAmt.'&lt;/EffectivePrice&gt;&lt;AddlTax1_Desc&gt;'.$tax1Desc.
                    '&lt;/AddlTax1_Desc&gt;&lt;AddlTax1_Rate&gt;'.$tax1Rate.'&lt;/AddlTax1_Rate&gt;&lt;AddlTax1_Type&gt;'.
                    'TAX_TYPE_PERCENTAGE &lt;/AddlTax1_Type&gt;&lt;AddlTax1_Amount&gt;'.$tax1Amount.'&lt;/AddlTax1_Amount&gt;&lt;'.
                    'AddlTax1_ID&gt;'.$tax1Id.'&lt;/AddlTax1_ID&gt;&lt;AddlTaxIsSubTax1&gt;False&lt;/AddlTaxIsSubTax1&gt;'.
                    '&lt;ExpectedDeliveryDate&gt;&lt;/ExpectedDeliveryDate&gt;&lt;ExpectedDeliveryTime&gt;'.
                    '&lt;/ExpectedDeliveryTime&gt;&lt;DeliveryLocationId&gt;&lt;/DeliveryLocationId&gt;&lt;'.
                    'DeliveryLocation&gt;&lt;/DeliveryLocation&gt;&lt;OriginalBarcode &gt;&lt;/OriginalBarcode '.
                    '&gt;&lt;UnitOfMeasure&gt;'.$UnitOfMeasure.'&lt;/UnitOfMeasure&gt;&lt;ItemMesurementType&gt;'.
                    $itemMeasurementType.'&lt;/ItemMesurementType&gt;&lt;OriginalPriceWithQty&gt;'.$rechargeAmt.
                    '&lt;/OriginalPriceWithQty&gt;&lt;DiscountWithQty&gt;0.00&lt;/DiscountWithQty&gt;&lt;'.
                    'isTradeInItem &gt;0&lt;/isTradeInItem &gt;&lt;IsCouponApplied &gt;0&lt;/IsCouponApplied &gt;'.
                    '&lt;CouponID &gt;&lt;/CouponID &gt;&lt;CouponType &gt;&lt;/CouponType &gt;&lt;CouponValue'.
                    '&gt;&lt;/CouponValue&gt;&lt;CouponValueType &gt;&lt;/CouponValueType &gt;&lt;CouponValueParam'.
                    ' &gt;&lt;/CouponValueParam &gt;&lt;CouponSupervisorID&gt;&lt;/CouponSupervisorID&gt;&lt;'.
                    'EmbeddedTotalPrice&gt;0.00&lt;/EmbeddedTotalPrice&gt;&lt;ValidatedPosMsgId&gt;&lt;/ValidatedPosMsgId'.
                    '&gt;&lt;ParentReferenceID&gt;&lt;/ParentReferenceID&gt;&lt;isVoidAllowed&gt;0'.
                    '&lt;/isVoidAllowed&gt;&lt;LocationType&gt;&lt;/LocationType&gt;&lt;StoreID&gt;&lt;/StoreID'.
                    '&gt;&lt;ExpiryDate&gt;&lt;/ExpiryDate&gt;&lt;BatchNumber&gt;&lt;/BatchNumber&gt;&lt;'.
                    'OrgSeqID&gt;&lt;/OrgSeqID&gt;&lt;/TxnItem&gt;&lt;/TxnItemList&gt;&lt;TxnTenderList&gt;&lt;'.
                    'TenderItem&gt;&lt;Type&gt;TENDERTYPE_CASH&lt;/Type&gt;&lt;Description&gt;CASH&lt;/Description'.
                    '&gt;&lt;MOPID&gt;1&lt;/MOPID&gt;&lt;ReceiptTypeDescription&gt;CASH&lt;/ReceiptTypeDescription'.
                    '&gt;&lt;Amount&gt;1.00&lt;/Amount&gt;&lt;Status&gt;SUCCESS&lt;/Status&gt;&lt;TenderMode&gt;'.
                    'DEBIT&lt;/TenderMode&gt;&lt;/TenderItem&gt;&lt;/TxnTenderList&gt;&lt;/TxnInfo&gt;</transXml>'.
                    '<CAF i:type="d:string">R</CAF><CAFFields i:type="d:string">'.$CAFFields.'</CAFFields><POAFile i:type="d:string"></POAFile><POIFile i:type="d:string">'.
                    '</POIFile><guId i:type="d:string">'.$guId.'</guId></UploadCAFTransactionData></v:Body></v:Envelope>';
                    $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio_xml.txt",date('Y-m-d H:i:s').": xml : ::".$soapData);
                    $AuthKey = base64_encode($userId.':P@ssw0rd');
                    $header = array('Content-Type: text/xml','X-API-Key: '.$ApiKey,'Authorization: Basic '.$AuthKey,'SOAPAction: http://tempuri.org/UploadCAFTransactionData','Cache-Control: no-cache');
                    $out = $this->General->curl_header($url,$soapData,$header,'POST',1);
                    
                    return $out;
        }
        
}
