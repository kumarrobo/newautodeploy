<?php
class ApiComponent extends Object{
    var $components = array('Shop','General','Recharge','Smartpaycomp');
    var $wrongSMS = "Please send correct code. Correct code is
Mobile:
*opr*mob*amt

DTH:
*opr*subid*mob*amt";

 var $validRecTypes = array('flexi','voucher');


 function receiveSMS($mobile,$msg,$power=null,$ussd=null,$apiPartner=null,$extra_data=null,$main_ses=0){

     $check = $this->serviceCheck($ussd,$mobile);
     if($check['status'] == 'failure'){
         return $check;
     }

     $userObj = ClassRegistry::init('User');

     $msgPart = $this->formatIncomingMessage($msg);

     $data = $this->General->getUserDataFromMobile($mobile);
     if(empty($data)){
         if(strtolower($msgPart[0]) == 'help'){
             return array('mobile'=>$mobile,'msg'=>'We will get back to you soon.','root'=>'shops');
         }
         else {
             return array('mobile'=>$mobile,'msg'=>'Invalid demo mobile number','root'=>'shops');
         }
     }


     if(in_array(strtolower($msgPart[0]),array('app','reset','bal','help','bnk'))){
         return $this->genericSMSRequests($msgPart,$msg,$mobile,$data,$userObj,$extra_data);
     }

     if(in_array(strtolower($msgPart[0]),array('tb','rb'))){
         return $this->distributorSMSRequests($msgPart,$msg,$mobile,$data,$main_ses,$userObj);
     }

     return $this->retailerSMSRequests($msgPart,$msg,$mobile,$data,$power,$ussd,$apiPartner,$userObj);
 }

    function serviceCheck($request_flag, $mobile){
        if( ! empty($request_flag)){
            if($request_flag == 4 && API_SERVICE == 0){
                // ---api partner
                return array('status'=>'failure', 'mobile'=>$mobile, 'msg'=>'API System is down. Kindly try after some time.', 'root'=>'shops');
            }
            else if(USSD_SERVICE == 0){
                // ------ussd
                return array('status'=>'failure', 'mobile'=>$mobile, 'msg'=>'Missed Call System is down. Kindly try after some time.', 'root'=>'shops');
            }
        }
        else if(SMS_SERVICE == 0){
            // --------sms
            return array('status'=>'failure', 'mobile'=>$mobile, 'msg'=>'SMS System is down. Kindly try after some time.', 'root'=>'shops');
        }

        return array('status'=>'success');
    }

    function formatIncomingMessage($msg){
        $msg = preg_replace('!\s+!', ' ', $msg);

        if(strrpos(strtolower($msg), "pay1") !== false){
            $msg = trim(substr($msg, 4));
        }
        if(strrpos(strtolower($msg), "pay") !== false){
            $msg = trim(substr($msg, 3));
        }
        else if(strrpos(strtolower($msg), "*") !== false){
            $msg = trim(substr($msg, 1));
        }

        $msgPart = explode(" ", $msg);

        return $msgPart;
    }

    function createRetailer($params, $format){
        App::import('Controller', 'Shops');
        $ini = new ShopsController();
        $ini->constructClasses();

        if(isset($params['interest'])){
            $data = $ini->createRetailer($params);
        }
        else{
            $data = $ini->createRetailerApp($params, $format);
        }

        return $data;
    }

    function bbpsComplaintRegistration($params) {
            return $this->Recharge->bbpsComplaintRegistration($params);
    }

    function bbpsComplaintTracking() {

            $usrObj = ClassRegistry::init('User');
            $result = $usrObj->query("SELECT va.id, bc.bbps_complaint_id FROM vendors_activations va "
            . " LEFT JOIN complaints c ON (c.vendor_activation_id = va.id) "
            . " LEFT JOIN bbps_complaints bc ON (bc.vendor_activation_id = va.id) "
            . "WHERE va.vendor_id = '".BBPS_VENDOR."' AND bc.status != 'RESOLVED' AND bc.date >= '".date('Y-m-d',strtotime('-15 days'))."' ORDER BY va.id DESC");


            foreach($result as $res) {
                    $out = $this->Recharge->bbpsComplaintTracking($res['bc']);

                    if($out['status'] == 'success') {
                            $short = $out['description'];
                            $usrObj->query("UPDATE bbps_complaints SET status = '{$short['complaintStatus']}', assigned_to = '{$short['complaintAssigned']}' WHERE bbps_complaint_id = '{$short['complaintId']}'");
                    }
            }
            return 1;
    }

    function amountTransfer($params, $format){
        App::import('Controller', 'Shops');
        if(!isset($params['distId'])) {
            $info = $this->Shop->getShopDataById($params['salesmanId'], SALESMAN);
            $info['User']['group_id'] = SALESMAN;
            $info['User']['id'] = $info['user_id'];
            $info['User']['mobile'] = $info['mobile'];
            $info['User']['balance'] = $info['balance'];
            $_SESSION['Auth'] = $info;
        }
        $ini = new ShopsController();
        $ini->constructClasses();

        //return;
        $data = $ini->amountTransferNew($params, $_SESSION['Auth']);
        return $data;
    }

    function manageCreateRetailer($msgPart, $msg, $mobile, $salesman, $info){
        $rental_flag = 0;
        $slaveObj = ClassRegistry::init('Slaves');
        $count = $slaveObj->query("SELECT count(*) as counts FROM retailers where parent_id = " . $info['id']);
        $count = $count[0][0]['counts'];

        if($info['retailer_creation'] == 0){
            return array('mobile'=>$mobile, 'msg'=>"You cannot create a retailer, contact pay1", 'root'=>'shops');
        }
        else if($info['retailer_limit'] > 0 && $count == $info['retailer_limit']){
            $msg = "You have reached your retailer creation limit. You cannot create retailer now";
            return array('mobile'=>$mobile, 'msg'=>$msg, 'root'=>'shops');
        }
        else{
            $number = trim($msgPart[1]);
            $params['rental_flag'] = $rental_flag;
            $params['salesmanId'] = $salesman['0']['salesmen']['id'];
            $params['mobile'] = $number;

            $ret = $this->createRetailer($params, 'json');
            return array('mobile'=>$mobile, 'msg'=>$ret['description'], 'root'=>'shops');
        }
    }

    function manageBalanceTransfer($msgPart, $msg, $mobile, $salesman, $info){
        $amount = trim($msgPart[2]);
        $number = trim($msgPart[1]);

        $this->General->logData("/tmp/amt.txt", "in amount transfer" . json_encode($msgPart));

        $dist_mobile = $info['mobile'];
        $data = $this->General->getUserDataFromMobile($number);

        if( ! empty($data)){
            $balance = $data['balance'];
            $data = $this->Shop->getShopData($data['id'], RETAILER);
        }

        if(empty($data)){
            return array('mobile'=>$mobile, 'msg'=>"Retailer $number does not exist.", 'root'=>'shops');
        }

        $retailerShopName = $data['shopname'];

        if($salesman['0']['salesmen']['id'] != $data['maint_salesman'] && $dist_mobile != $mobile){
            $message = "Retailer $retailerShopName($number) is not under you. You cannot transfer balance to him";
            return array('mobile'=>$mobile, 'msg'=>$message, 'root'=>'shops');
        }

        if($data['block_flag'] != 0){
            $message = "Retailer $retailerShopName($number) is blocked. Kindly call admin for any problem or to unblock it";
            return array('mobile'=>$mobile, 'msg'=>$message, 'root'=>'shops');
        }

        $params['amount'] = $amount;
        $params['retailer'] = $data['id'];
        $params['salesmanId'] = $salesman['0']['salesmen']['id'];
        $params['salesmanName'] = $salesman['0']['salesmen']['name'];

        if($dist_mobile == $mobile){
            $params['distId'] = 0;
        }
        $ret = $this->amountTransfer($params, 'json');
        return array('mobile'=>$mobile, 'msg'=>$ret['description'], 'root'=>'shops');
    }

    function getRetailerBalance($msgPart, $msg, $mobile, $salesman, $info){
        $number = trim($msgPart[1]);
        $data = $this->General->getUserDataFromMobile($number);
        if( ! empty($data)){
            $balance = $data['balance'];
            $data = $this->Shop->getShopDataById($data['id'], RETAILER);
        }

        if(empty($data)){
            return array('mobile'=>$mobile, 'msg'=>"Retailer $number does not exist.", 'root'=>'shops');
        }

        if($info['id'] != $data['parent_id']){
            $message = "Retailer " . $data['shopname'] . "($number) is not under you. You cannot check balance of him";
            return array('mobile'=>$mobile, 'msg'=>$message, 'root'=>'shops');
        }

        $slaveObj = ClassRegistry::init('Slaves');
        $successToday = $slaveObj->query("SELECT sum(st.amount) as amts FROM shop_transactions as st WHERE st.confirm_flag = 1 AND st.source_id = " . $data['id'] . " AND st.type=" . RETAILER_ACTIVATION . " AND st.date= '" . date('Y-m-d', strtotime('-1 day')) . "'");
//        $averageResult = $slaveObj->query("select avg(sale) as total from (select sale from retailers_logs where retailer_id = " . $data['id'] . " ORDER by id desc limit 15) as table1");
        $averageResult = $slaveObj->query("SELECT AVG(sale) AS total "
                . "FROM (SELECT rel.date,SUM(rel.amount) AS sale "
                . "FROM retailer_earning_logs rel "
                . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                . "WHERE r.id = " . $data['id'] . " "
                . "AND rel.service_id IN (1,2,4,5,6,7) "
                . "GROUP BY rel.date "
                . "ORDER BY rel.date DESC "
                . "LIMIT 15) AS table1");

        $message = "Retailer: " . substr($data['shopname'], 0, 15) . " (" . $number . ")\nBalance: Rs." . $balance;
        $index = intval($averageResult['0']['0']['total'] / 500);
        $message .= "\nAverage Sale: " . $index * 500 . " - " . ($index + 1) * 500;
        if(empty($successToday['0']['0']['amts'])) $sale = 0;
        else $sale = $successToday['0']['0']['amts'];
        $message .= "\nYesterday's Sale: $sale";

        return array('mobile'=>$mobile, 'msg'=>$message, 'root'=>'shops');
    }

    function manageTopupRequest($msgPart, $msg, $mobile, $info, $type, $extra_data = null){
        $amt = $msgPart[1];

        $i = 0;
        $id = "";
        foreach($msgPart as $val){
            if($i > 1) $id .= $val . " ";
            $i ++ ;
        }

        if($this->General->priceValidate($amt) == ''){
            return array('mobile'=>$mobile, 'msg'=>"Code $msg is not valid", 'root'=>'shops');
        }

        if($info['mobile'] == $mobile){
            $message = "Dear $type, We have received your request. You will get your topup in sometime";

            if(strrpos(strtolower($id), "_") !== false){
                $idPart = explode("_", $id);
                switch($idPart[0]){
                    case "icici-1578" :
                        $bank = "ICICI Bank";
                        $accId = "1578";
                        break;
                    case "icici-0005" :
                        $bank = "ICICI Bank";
                        $accId = "0005";
                        break;
                    case "bom-4079" :
                        $bank = "Bank Of Maharashtra Bank";
                        $accId = "4079";
                        break;
                    default :
                        $bank = "ICICI Bank";
                        $accId = "";
                        break;
                }
            }

            if($type == 'Distributor'){
                $company = $info['company'];
            }
            else{
                $company = $info['shopname'];
            }

            $msg = "$type: " . $company . " deposited Rs $amt in our $bank account (TransID: $id)<br/>Mobile: " . $mobile;
            $data = array('msg'=>$msg, 'sender'=>'PAY1', 'process'=>'limits', 'id'=>$info['id'], 'type'=>$type, 'name'=>$company, 'mobile'=>$mobile, 'amount'=>$amt, 'transid'=>$id, 'bank_details'=>$extra_data);

            $this->General->curl_post($this->General->findVar('limit_url'), $data);
            return array('mobile'=>$mobile, 'msg'=>$message, 'root'=>'shops');
        }
    }

    function genericSMSRequests($msgPart, $msg, $mobile, $data, $userObj, $extra_data = null){
        if(strtolower($msgPart[0]) == 'app'){
            $sub = "CallBack - Pay1 Application";
            $body = "Retailer Mobile: " . $mobile . " sent message: " . $msg;
            $this->General->sendMails($sub, $body, array('chirutha@pay1.in'));
            return array('mobile'=>$mobile, 'msg'=>'We will get back to you soon.', 'root'=>'shops');
        }

        if(strtolower($msgPart[0]) == 'reset'){
            if( ! empty($data)){
                App::import('Controller', 'Users');
                $ini = new UsersController();
                $ini->constructClasses();
                $msg = $ini->resetPassword($mobile);
            }
            else{
                $msg = "Sorry, This is not a valid number";
            }
            return array('mobile'=>$mobile, 'msg'=>$msg, 'root'=>'payone');
        }

        if(strtolower($msgPart[0]) == 'bal'){
            $balance = $data['balance'];
            $msg = "Dear User (" . $data['mobile'] . "),\nYour current balance is: " . $balance;
            return array('mobile'=>$mobile, 'msg'=>$msg, 'root'=>'shops');
        }

        if(strtolower($msgPart[0]) == 'help'){
            $sub = "Pay1 - User HELP";
            $body = "Pay1 User: " . $data['mobile'] . " needs help. Please connect as soon as possible";
            $this->General->sendMails($sub, $body, array('customer.care@pay1.in'), 'mail');
            return array('mobile'=>$mobile, 'msg'=>"We will get back to you soon.", 'root'=>'shops');
        }

        if(strtolower($msgPart[0]) == 'bnk'){
            $groups = $data['groups'];
            $slaveObj = ClassRegistry::init('Slaves');

            if(in_array(DISTRIBUTOR, $groups) || in_array(SALESMAN, $groups)){
                $salesman = $slaveObj->query("SELECT * FROM salesmen WHERE mobile = '$mobile'");
                $info = $this->Shop->getShopDataById($salesman['0']['salesmen']['dist_id'], DISTRIBUTOR);
                return $this->manageTopupRequest($msgPart, $msg, $mobile, $info, 'Distributor', $extra_data);
            }
            else if(in_array(SUPER_DISTRIBUTOR, $groups)){
                $info = $this->Shop->getShopData($data['id'], SUPER_DISTRIBUTOR);


                $imp_data = $this->Shop->getUserLabelData($info['user_id']);

                $info['shopname'] = $imp_data[$info['user_id']]['imp']['shop_est_name'];
                $info['mobile'] = $data['mobile'];

                return $this->manageTopupRequest($msgPart, $msg, $mobile, $info, 'Super Distributor', $extra_data);
            }
            else if(in_array(RETAILER, $groups)){
                $info = $this->Shop->getShopData($data['id'], RETAILER);
                $partnersData = $slaveObj->query("SELECT * FROM partners  WHERE partners.retailer_id = " . $info['id']);
                if(empty($partnersData)){
                    $userType = "Retailer";
                }
                else{
                    $userType = "Partner";
                }
                return $this->manageTopupRequest($msgPart, $msg, $mobile, $info, $userType);
            }
            else{
                return array('mobile'=>$mobile, 'msg'=>"Your request is not valid", 'root'=>'shops');
            }
        }
    }

    function distributorSMSRequests($msgPart, $msg, $mobile, $userData, $main_ses, $userObj){
        $salesman = $userObj->query("SELECT * FROM salesmen WHERE mobile = '$mobile'");

        if(empty($salesman)){
            $message = "Request is not valid";
            return array('mobile'=>$mobile, 'msg'=>$message, 'root'=>'shops');
        }

        if(isset($salesman['0']['salesmen']['block_flag']) && $salesman['0']['salesmen']['block_flag'] == 1){
            $message = "Dear Salesman, your pay1 account is blocked now. Kindly call your manager to unblock it";
            return array('mobile'=>$mobile, 'msg'=>$message, 'root'=>'shops');
        }

        $info = $this->Shop->getShopDataById($salesman['0']['salesmen']['dist_id'], DISTRIBUTOR);
        $info['User']['group_id'] = DISTRIBUTOR;
        $info['User']['id'] = $info['user_id'];
        $info['User']['mobile'] = $info['mobile'];
        $info['User']['balance'] = $userData['balance'];
        if($main_ses == 0){
            $_SESSION['Auth'] = $info;
        }

        if(strtolower($msgPart[0]) == 'cr'){
            return $this->manageCreateRetailer($msgPart, $msg, $mobile, $salesman, $info);
        }
        else if(strtolower($msgPart[0]) == 'tb'){
            return $this->manageBalanceTransfer($msgPart, $msg, $mobile, $salesman, $info);
        }
        else if(strtolower($msgPart[0]) == 'rb'){
            return $this->getRetailerBalance($msgPart, $msg, $mobile, $salesman, $info);
        }

        $_SESSION['Auth'] = array();
    }

    function retailerSMSRequests($msgPart, $msg, $mobile, $data, $power, $ussd, $apiPartner, $userObj){
        $slaveObj = ClassRegistry::init('Slaves');

        $retailer_check = $slaveObj->query("SELECT * FROM user_groups WHERE group_id = ".RETAILER." AND user_id = ".$data['id']);
        if(empty($retailer_check)){
            return array('mobile'=>$mobile,'msg'=>'Invalid demo mobile number','root'=>'shops');
        }

        if(strtolower($msgPart[0]) == 'rev'){
            return $this->manageRetailerComplaint($msgPart, $msg, $mobile, $data, $userObj, $slaveObj);
        }

        if(substr($msg, 0, 1) == '#'){
            if(strlen($msg) < 11){ // sent for repeat txn
                return $this->manageRepeatTxn($msg, $mobile, $userObj, $slaveObj);
            }
            else{ // search txn
                return $this->searchTxn($msg, $mobile, $data);
            }
        }

        $msg = trim(substr($msg, 1));
        $msgPart = explode("*", $msg);
        if($msgPart[0] == ''){
            return array('mobile'=>$mobile, 'msg'=>$this->wrongSMS, 'root'=>'shops', 'code'=>'4');
        }

        return $this->manageRechargeRequest($msgPart, $msg, $data, $power, $ussd, $apiPartner, $userObj, $slaveObj);
    }

    function extractRechargeMessage($method,$msgPart,$params){
        if($method == 'dthRecharge'){
            if(isset($msgPart[3]) && $this->General->endsWith($msgPart[3], "#")){
                $msgPart[3] = substr($msgPart[3], 0, strlen($msgPart[3]) - 1);
                $params['special'] = 1;
            }
            $params['subId'] = $msgPart[1];
            $params['mobileNumber'] = "7010101020";

            if(count($msgPart) == 3){
                $params['amount'] = $msgPart[2];
            }
            else{
                $params['amount'] = $msgPart[3];
                if(strlen($msgPart[2]) == 10){
                    $params['mobileNumber'] = $msgPart[2];
                }
            }
            $checkParams = array($params['mobileNumber'],$params['amount'],$params['subId']);
            $unique = $params['subId'];
        }
        else if($method == 'mobRecharge' || $method == 'pay1Wallet'){
            $msgPart[2] = isset($msgPart[2]) ? $msgPart[2] : "";
            if($this->General->endsWith($msgPart[2], "#")){
                $msgPart[2] = substr($msgPart[2], 0, strlen($msgPart[2]) - 1);
                $params['special'] = 1;
            }
            $msgPart[2] = isset($msgPart[2]) ? $msgPart[2] : "";
            $params['mobileNumber'] = $msgPart[1];
            $params['amount'] = $msgPart[2];
            $checkParams = array($params['mobileNumber'],$params['amount'],null);
            $unique = $params['mobileNumber'];
        }
        else if($method == 'vasRecharge'){
            $params['Mobile'] = $msgPart[1];
            $params['product'] = $params['operator'];

            if($params['type'] != 'flexi'){
                $params['Amount'] = null;
                $params['param'] = $msgPart[2];
            }
            else{
                $params['Amount'] = $msgPart[2];
                $params['param'] = $msgPart[3];
            }
            $checkParams = array($params['Mobile'],$params['Amount'],$params['param']);
            $unique = $params['Mobile'];
        }
        else if($method == 'mobBillPayment'){
            $params['mobileNumber'] = $msgPart[1];
            $params['subId'] = $msgPart[1];
            $params['amount'] = $msgPart[2];
            $params['param'] = $msgPart[3];
            $checkParams = array($params['mobileNumber'],$params['amount'],$params['param']);
            $unique = $params['mobileNumber'];
        }
        else if($method == 'utilityBillPayment'){
            $params['mobileNumber'] = $msgPart[1];
            $params['accountNumber'] = $msgPart[2];
            $params['amount'] = $msgPart[3];
            $params['param'] = $msgPart[4];
            $checkParams = array($params['mobileNumber'],$params['amount'],$params['accountNumber']);
            $unique = $params['accountNumber'];
        }
        return array('params'=>$params,'checkParams'=>$checkParams,'unique'=>$unique);
    }

    function manageRechargeRequest($msgPart, $msg, $data, $power, $ussd, $apiPartner, $userObj, $slaveObj){
        $params = $this->Shop->smsProdCodes(strtoupper(trim($msgPart[0])));
        $method = isset($params['method']) ? $params['method'] : "";

        $mobile = $data['mobile'];
        if( ! method_exists($this, $method)){
            return array('mobile'=>$mobile, 'msg'=>$this->wrongSMS, 'root'=>'shops', 'code'=>'4');
        }

        $msgPart[1] = trim($msgPart[1]);
        if(isset($msgPart[2])) $msgPart[2] = trim($msgPart[2]);
        if(isset($msgPart[3])) $msgPart[3] = trim($msgPart[3]);
        if($power != null) $params['power'] = 1;
        else $params['power'] = null;

        $params['api_partner'] = null;
        if( ! empty($ussd)){
            if($ussd == 4){
                $params['api_flag'] = 4; // api
                $params['api_partner'] = $apiPartner; // partner id
            }
            else
                $params['api_flag'] = 2; // ussd
        }
        else{
            $params['api_flag'] = 0; // sms
        }

        $group_id = RETAILER;
        $info = $this->Shop->getShopData($data['id'], $group_id);
        $info['User']['group_id'] = $group_id;
        $info['User']['id'] = $data['id'];

        $_SESSION['Auth'] = $info;
//         /$this->Session->write('Auth', $info);

        if($info['block_flag'] == 2){
            return array('mobile'=>$mobile, 'msg'=>"Dear Retailer, Your demo is blocked. Kindly contact your distributor", 'root'=>'shops', 'code'=>'1');
        }
        $params['special'] = isset($params['special']) ? $params['special'] : 0;

        $extract = $this->extractRechargeMessage($method,$msgPart,$params);
        $params = $extract['params'];
        $checkParams = $extract['checkParams'];

        $ret = $this->Shop->checkPossibility($msgPart[0], $checkParams[0], $checkParams[1], $params['power'], $checkParams[2], $params['special'], $params['api_flag']);
        if($ret != null){
            $ret = array('status'=>'failure', 'code'=>'37', 'description'=>$ret);
        }
        else {
            // wch method to call mobile recharge, dth recharge
            $format = 'json';
            try{
                if($ussd != 4){
                    $id = $this->Shop->addAppRequest($method, $extract['unique'], $checkParams[1], $params['operator'], $params['api_flag'], $info['id']);
                    if(empty($id)){
                        $ret = array('status'=>'failure', 'code'=>'38', 'description'=>$this->Shop->errors(38));
                    }
                }

                if(empty($id) && $ussd != 4){
                    $ret = array('status'=>'failure', 'code'=>'38', 'description'=>$this->Shop->errors(38));
                }
                else{
                    if( ! isset($params['ip'])) $params['ip'] = NULL;
                    $ret = $this->$method($params, $format);
                    if($ret['status'] == 'failure'){
                        $this->Shop->deleteAppRequest($extract['unique'], $checkParams[1], $params['operator'], $info['id']);
                        if(empty($checkParams[2])) $number = $checkParams[0];
                        else $number = $checkParams[2];
                        $this->Shop->unlockTransactionDuplicates($msgPart[0], $number, $checkParams[1]);
                    }
                    else{
                        if(empty($ussd)) $this->Shop->setRetailerTrnsDetails($mobile, array('trans_type'=>'sms'));
                        else $this->Shop->setRetailerTrnsDetails($mobile, array('trans_type'=>'ussd'));
                    }
                }
            }
            catch(Exception $e){
                $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30));
            }
        }

        if(isset($ret['code'])){
            if($ret['code'] == '5' || $ret['code'] == '6'){
                return array('status'=>'failure', 'code'=>$ret['code'], 'mobile'=>$mobile, 'msg'=>$this->wrongSMS, 'root'=>'shops');
            }
            else if($ret['code'] == '37'){
                return array('status'=>'failure', 'code'=>$ret['code'], 'mobile'=>$mobile, 'msg'=>$ret['description'], 'root'=>'shops');
            }
        }

        $_SESSION = array();
        return $this->displaySMS($mobile, $ret, '1', $params['api_flag'],$userObj);
    }

    function displaySMS($sender,$sms,$type,$apiflag = null,$dbObj){
        //explode $msg array. crate reply sms. send back to the $sender
        $root = 'shops';
        $transId = 0;
        if($sms['status'] == 'success' && $type == '1'){
            $msg = "Request accepted.";
            $transId = $sms['description'];
            $data = $dbObj->query("SELECT mobile,products.name,products.service_id,param,vendors_activations.amount,api_flag FROM vendors_activations,products WHERE vendors_activations.txn_id = '".$transId."' AND vendors_activations.product_id = products.id");
            $msg .= "\nTrans Id: " .  substr($transId,-5);
            if($data['0']['products']['service_id'] == 2){
                $msg .= "\nSub Id: " .  $data['0']['vendors_activations']['param'];
            }
            $msg .= "\nMob: " .  $data['0']['vendors_activations']['mobile'];
            $msg .= "\nOperator: " .  $data['0']['products']['name'];
            $msg .= "\nAmt: " .  $data['0']['vendors_activations']['amount'];
            //$root = 'ussd';
            if(isset($sms['service_charge']) && !empty($sms['service_charge'])){
                $msg .= "\nTxn Charges:Rs " .  $sms['service_charge'];
                if($apiflag == 0 ){
                    $msg .= "\nTo avoid Txn charges. Please download our app ".$this->General->createAppDownloadUrl(RETAILER,1);
                }
            }

            $root = 'shops';

            if($data['0']['products']['service_id'] == 4 && $data['0']['vendors_activations']['api_flag'] != 4){
                //			$msg_user = "Dear User\nYour request of bill payment of Rs ".intval($data['0']['vendors_activations']['amount'])." accepted successfully from Pay1. Wait for some time for your operator's confirmation. \nYour pay1 txnid: $transId";

                $paramdata['VENDORS_ACTIVATIONS_AMOUNT'] = intval($data['0']['vendors_activations']['amount']);
                $paramdata['TRANSID'] = $transId;
                $MsgTemplate = $this->General->LoadApiBalance();
                $content =  $MsgTemplate['UserRequest_Of_MobBill_Payment_MSG'];
                $msg_user = $this->General->ReplaceMultiWord($paramdata,$content);

                $this->General->sendMessage(array($data['0']['vendors_activations']['mobile']),$msg_user,'notify');
            }
        }else{
            $msg = $sms['status'].': '.$sms['description'];
        }

        $bal = 0;
        if($type == '1'){
            $data = $dbObj->query("SELECT users.balance FROM users WHERE users.mobile = '".$sender."'");
            $bal = round($data['0']['users']['balance'],2);
            $msg .= "\n\nYour bal:Rs $bal";
        }
        if(!isset($sms['code'])){
            $sms['code'] = 0;
        }
        return array('mobile'=>$sender,'msg'=>$msg,'root'=>$root,'transid'=>$transId,'status'=>$sms['status'],'code'=>$sms['code'],'balance'=>$bal);
    }

    function manageRepeatTxn($msg, $mobile, $userObj, $slaveObj){
        $id_r = trim(substr($msg, 2));
        $id_r = intval($id_r);
        $now = date('Y-m-d H:i:s', strtotime('- 30 minutes'));

        $repeat_msg = $slaveObj->query("SELECT msg,timestamp FROM repeated_transactions WHERE sender = '$mobile' AND id = $id_r AND send_flag = 0 AND timestamp >= '$now'");
        if( ! empty($repeat_msg)){
            $msg = $repeat_msg['0']['repeated_transactions']['msg'];
            $userObj->query("UPDATE repeated_transactions SET send_flag = 1 WHERE id = $id_r");
            return array('mobile'=>$mobile, 'msg'=>"Request is taken. Recharge will be done soon", 'root'=>'shops');
        }
        else{
            return array('mobile'=>$mobile, 'msg'=>"Invalid request sent. Please call Pay1 customer care - 02267242288", 'root'=>'shops');
        }
    }

    function searchTxn($msg, $mobile, $data){
        $search = trim(substr($msg, 1));
        $shopData = $this->Shop->getShopData($data['id'], RETAILER);
        $res = $this->Shop->searchTransactions($search, $shopData['id']);
        if(empty($res)){
            $msg = "Search result for $search is failed";
        }
        else{
            $msg = "Search result for $search\n";
            $num = 1;
            foreach($res as $r){
                $msg .= "$num)" . $r['products']['name'];
                if($r['services']['id'] == 1){
                    $msg .= " - " . $r['vendors_activations']['mobile'];
                }
                else if($r['services']['id'] == 2){
                    $msg .= " - " . $r['vendors_activations']['param'];
                }
                $msg .= " - " . $r['vendors_activations']['amount'];
                if($r['vendors_activations']['status'] == 2 || $r['vendors_activations']['status'] == 3){
                    $msg .= " - Reversed";
                }
                else{
                    $msg .= " - Success";
                }
                $msg .= " - " . date('d/m H:i', strtotime($r['vendors_activations']['timestamp'])) . "\n";
                $num ++ ;
            }
        }
        return array('mobile'=>$mobile, 'msg'=>$msg, 'root'=>'shops');
    }

    function manageRetailerComplaint($msgPart, $msg, $mobile, $data, $userObj, $slaveObj){
        $ref_code = $msgPart[1];
        $group_id = RETAILER;
        $info = $this->Shop->getShopData($data['id'], $group_id);
        $info['User']['group_id'] = $group_id;
        $info['User']['id'] = $data['id'];
        $_SESSION['Auth'] = $info;
        //$this->Session->write('Auth', $info);

        $id = $slaveObj->query("SELECT vendors_activations.id,vendors_activations.status,vendors_activations.txn_id FROM vendors_activations WHERE txn_id like '%" . $ref_code . "' AND vendors_activations.retailer_id = " . $info['id'] . " AND vendors_activations.date >= '" . date('Y-m-d', strtotime('-20 days')) . "' order by id desc limit 1");
        if( ! empty($id)){
            $data = $this->reversal(array('id'=>$id['0']['vendors_activations']['id'], 'mobile'=>$mobile), 'json', null, $userObj, $slaveObj);
            return array('mobile'=>$mobile, 'msg'=>(isset($data['msg']) ? $data['msg'] : $data['description']), 'root'=>'shops');
        }
        else{
            return array('mobile'=>$mobile, 'msg'=>"Wrong Transaction Id sent.", 'root'=>'shops');
        }
    }

    function reversal($params, $format = null, $user_id = null, $userObj = null, $slaveObj = null){
        try{
            $slaveObj = ( ! empty($slaveObj)) ? $slaveObj : ClassRegistry::init('Slaves');
            $userObj = ( ! empty($userObj)) ? $userObj : ClassRegistry::init('User');

            $join = 'left join bbps_txnid_mapping on (bbps_txnid_mapping.payment_txnid = vendors_activations.txn_id) ';
            $bbps_data = ',bbps_txnid_mapping.id as bbps_txnid,bbps_txnid_mapping.bill_data ';
            
            $prevSt = $slaveObj->query("select users.id,vendors_activations.id as vendor_activation_id,status,vendors_activations.vendor_id,txn_id,
					vendor_refid,resolve_flag, vendors_activations.timestamp
                                        $bbps_data
					from vendors_activations left
					join complaints ON (vendor_activation_id =vendors_activations.id ANd resolve_flag = 0)
					left join users ON (users.mobile = vendors_activations.mobile)
                                        $join
					WHERE vendors_activations.id=" . $params['id']);            
            
            if(isset($prevSt['0']['bbps_txnid_mapping']['bbps_txnid']) && !empty($prevSt['0']['bbps_txnid_mapping']['bbps_txnid']))
            {
                $time_diff = $this->Smartpaycomp->dateDifference($prevSt['0']['vendors_activations']['timestamp'],date('Y-m-d H:i:s'),'%TH');
                if($time_diff < 24)
                {
                    return array('status'=>'failure','description'=>'Complaint should be raised after 24 hours.');
                }
            }
            if($prevSt['0']['vendors_activations']['vendor_id'] != BBPS_VENDOR || $params['bbps'] == 1) {
                
                if(in_array($prevSt['0']['vendors_activations']['status'], array(0, 1))){

                    if($prevSt['0']['complaints']['resolve_flag'] == '0'){
                        return array('status'=>'failure', 'code'=>'45', 'description'=>$this->Shop->errors(45));
                    }

                    $userObj->query("UPDATE vendors_activations SET status='" . TRANS_REVERSE_PENDING . "', prevStatus = '" . $prevSt['0']['vendors_activations']['status'] . "' WHERE id=" . $params['id']);

                    if(isset($params['turnaround_time'])) $turnaround_duration = $params['turnaround_time'];
                    else if(isset($params['tag'])) $turnaround_duration = $this->getTurnaroundTime($prevSt['0']['vendors_activations']['vendor_activation_id'], $params['tag']);
                    else{
                        $params['tag'] = "Customer Not Got Balance";
                        $turnaround_duration = $this->getTurnaroundTime($prevSt['0']['vendors_activations']['vendor_activation_id'], $params['tag']);
                    }

                    if(isset($turnaround_duration)){
                        $pre_adjusted_turnaround_time = time() + ($turnaround_duration * 60 * 60);
                        $pre_adjusted_date = new DateTime(date("Y-m-d H:i:s", $pre_adjusted_turnaround_time));
                        $date = new DateTime(date("Y-m-d H:i:s", $pre_adjusted_turnaround_time));
                        if(date("H", $pre_adjusted_turnaround_time) < 8){
                            $date->setTime(10, 0, 0);
                            $turnaround_time = $date->format("Y-m-d H:i:s");
                        }
                        else if(date("H", $pre_adjusted_turnaround_time) == 23){
                            $date->add(new DateInterval('P1D'));
                            $date->setTime(10, 0, 0);
                            $turnaround_time = $date->format("Y-m-d H:i:s");
                        }
                        else{
                            $turnaround_time = date('Y-m-d H:i:s', $pre_adjusted_turnaround_time);
                        }

                        $turnaround_duration += $date->diff($pre_adjusted_date)->h;
                    }
                    $userObj->query("INSERT INTO complaints (vendor_activation_id,takenby,in_date,in_time, turnaround_time)
                                                    VALUES (" . $params['id'] . ",'$user_id','" . date('Y-m-d') . "','" . date('H:i:s') . "', '" . $turnaround_time . "')");

                    if(isset($params['mobile'])){
                        $retInfo = $slaveObj->query("select id, user_id, name,shopname from retailers WHERE  mobile='" . $params['mobile'] . "'");
                    }

                    $complaint = $userObj->query("select id, turnaround_time from complaints where vendor_activation_id = " . $params['id']);

                    $paramdata['vendors_activations_ref_code'] = $prevSt['0']['vendors_activations']['txn_id'];
                    $paramdata['complaints_id'] = $complaint[0]['complaints']['id'];
                    $paramdata['turnaround_duration'] = $turnaround_duration;
                    $MsgTemplate = $this->General->LoadApiBalance();

                    if($turnaround_duration < 1){
                        $paramdata['turnaround_duration'] = 60 * $turnaround_duration;
                        $content = $MsgTemplate['Retailer_Reversal_MSG_MINS'];
                    }
                    else{
                        $content = $MsgTemplate['Retailer_Reversal_MSG'];
                    }
                    $message = $this->General->ReplaceMultiWord($paramdata, $content);

                    if(isset($params['tag'])){
                        $tag = $slaveObj->query("select id from taggings where name = '" . $params['tag'] . "'");
                        if($tag){
                            if(isset($retInfo) &&  ! isset($params['turnaround_time'])){
                                $this->Shop->addComment($prevSt['0']['users']['id'], $retInfo[0]['retailers']['id'], $prevSt['0']['vendors_activations']['txn_id'], $params['tag'], $params['mobile'], null, $tag['0']['taggings']['id'], 13);
                            }
                            else{
                                $this->Shop->addComment($prevSt['0']['users']['id'], 0, $prevSt['0']['vendors_activations']['txn_id'], $params['tag'], 0, null, $tag['0']['taggings']['id'], 13);
                            }
                        }
                    }

                    if(isset($params['mobile'])){
                        return array('status'=>'success', 'mobile'=>$params['mobile'], 'turnaround_time'=>$turnaround_duration, 'msg'=>"Dear Retailer, Your Complaint has been taken successfully. We will get back to you soon.");
                    }
                    else if(isset($params['type'])){
                        return array('status'=>'success', 'turnaround_time'=>$turnaround_duration, 'description'=>$complaint[0]['complaints']['id'], 'msg'=>$message);
                    }
                    else{
                        return array('status'=>'success', 'turnaround_time'=>$turnaround_duration, 'description'=>'Complaint taken successfully', 'msg'=>$message);
                    }
                }
                else if(in_array($prevSt['0']['vendors_activations']['status'], array(3))){
                    return array('status'=>'failure', 'code'=>'30', 'description'=>'Recharge is already failed');
                }
                else if(in_array($prevSt['0']['vendors_activations']['status'], array(4))){
                    return array('status'=>'failure', 'code'=>'30', 'description'=>'Complaint is already taken');
                }
                else if(in_array($prevSt['0']['vendors_activations']['status'], array(5))){
                    return array('status'=>'failure', 'code'=>'30', 'description'=>'Complaint is declined for this recharge');
                }
                else{
                    return array('status'=>'failure', 'code'=>'30', 'description'=>'Complaint cannot be taken for this recharge');
                }
            } else {
                return array('status'=>'failure', 'code'=>'30', 'description'=>'Complaint cannot be taken because it is a BBPS Transaction');
            }
        }
        catch(Exception $e){
            return array('status'=>'failure', 'code'=>'30', 'description'=>'Cannot take complaint now. Please try after some time');
        }
    }

    function billers() {
            Configure::load('billers');
            return Configure::read('billers');
    }

    function bbpsComplaints($params) {

            if(!empty($params['complaint_id'])) {
                $cond = " AND va.txn_id LIKE '%{$params['complaint_id']}%' ";
            } else if(!empty($params['mobile'])) {
                $cond = " AND va.mobile = '{$params['mobile']}' ";
            } else if(!empty($params['from_date']) && !empty($params['to_date'])) {
                $cond = " AND va.date >= '".date('Y-m-d', strtotime($params['from_date']))."' AND va.date <= '".date('Y-m-d', strtotime($params['to_date']))."' ";
            }

            $retailer_id = $params['retailer_id'] != '' ? $params['retailer_id'] : $_SESSION['Auth']['id'];

            $slavesObj = ClassRegistry::init('Slaves');
            $res = $slavesObj->query("SELECT p.name,va.id,va.mobile,va.amount,va.txn_id,c.id,va.status,bc.bbps_complaint_id,bc.status bc_status,bc.complaint_reason,bc.timestamp "
                    . "FROM vendors_activations va "
                    . "JOIN products p ON (p.id = va.product_id) "
                    . "JOIN complaints c ON (c.vendor_activation_id = va.id) "
                    . "JOIN bbps_complaints bc ON (bc.vendor_activation_id = va.id) "
                    . "WHERE va.vendor_id = '".BBPS_VENDOR."' AND va.retailer_id = '".$retailer_id."' $cond ORDER BY va.id DESC");

            return array('status'=>'success', 'res'=>$res);
    }

    function bbpsTxnDetails($txn_id) {
            $slavesObj = ClassRegistry::init('Slaves');
            $res = $slavesObj->query("SELECT p.name, va.id, va.mobile, r.mobile, va.date, va.amount, va.status, va.txn_id, va.timestamp "
                    . "FROM vendors_activations va "
                    . "LEFT JOIN products p ON (va.product_id = p.id) "
                    . "LEFT JOIN retailers r ON (va.retailer_id = r.id) "
                    . "WHERE va.txn_id = '$txn_id'");

            return array('status'=>'success', 'res'=>$res[0]);
    }

    function getTurnaroundTime($va_id, $tag, $turnaround_time = 24){
        // $turnaround_time = 24;
        $slaveObj = ClassRegistry::init('Slaves');
        if($tag == "Customer Not Got Balance"){
            $tat = $slaveObj->query("select vc.tat_time
                    from vendors_commissions vc
                    join vendors_activations va on va.vendor_id = vc.vendor_id and va.product_id = vc.product_id
                    where va.id = $va_id");
            $turnaround_time = $tat['0']['vc']['tat_time'];
        }
        else{
            switch($tag){
                case "Wrong Operator Recharge" :
                    $turnaround_time = 0.5;
                    break;
                case "Wrong Benefit Recharge" :
                case "Late Recharge Success" :
                case "Double Recharge Success" :
                    $turnaround_time = 24;
                    break;
                case "Wrong Sub ID Recharge" :
                case "Wrong Number Recharge" :
                case "Wrong Amount Recharge" :
                    $turnaround_time = 100;
                    break;
            }
        }

        return $turnaround_time;
    }

    function mobRecharge($params, $format){
        // parameter checks
        $noParams = 6;
        if(count($params) < $noParams){
            return array('status'=>'failure', 'code'=>'4', 'description'=>$this->Shop->errors(4));
        }
        else if($this->General->mobileValidate($params['mobileNumber']) == '1'){ // mobile no validation
            return array('status'=>'failure', 'code'=>'5', 'description'=>$this->Shop->errors(5));
        }
        else if($this->General->priceValidate($params['amount']) == ''){ // amount validation
            return array('status'=>'failure', 'code'=>'6', 'description'=>$this->Shop->errors(6));
        }
        else if(!in_array($params['type'], $this->validRecTypes)){
            return array('status'=>'failure', 'code'=>'7', 'description'=>$this->Shop->errors(7));
        }



        $currentRecharge = time() . "_" . $params['amount'];
        if(isset($params['recharge_prompt_flag']) &&  ! empty($params['recharge_prompt_flag'])){
            if(isset($params['no_operator_check_flag']) && empty($params['no_operator_check_flag'])){
                $slaveObj = ClassRegistry::init('Slaves');
                $mobile_code = substr($params['mobileNumber'], 0, 5);
                $operator_id = $this->Shop->getMemcache("mappedOperator_" . $mobile_code);
                if($operator_id == false){
                    $operator_code_mapping = $slaveObj->query("select *
							from mobile_operator_area_map moam
							left join mobile_numbering_service mns on mns.opr_code = moam.operator
							where moam.number like '" . $mobile_code . "'");
                    if( ! empty($operator_code_mapping)){
                        $operator_id = $operator_code_mapping[0]['mns']['product_id'];
                        $this->Shop->setMemcache("mappedOperator_" . $mobile_code, $operator_id, 3600 * 24 * 7);
                    }
                }
                if($operator_id !== false){
                    if($operator_id != $params['operator']){
                        $lastRecharge = $slaveObj->query("select *
												from vendors_activations va
												join products p on p.id = va.product_id
												where va.date >= '" . date('Y-m-d', strtotime('-7 days')) . "'
												and va.status = 1
												and va.mobile like '" . $params['mobileNumber'] . "'
												and p.service_id in (1, 4)
												order by va.timestamp desc
												limit 1");
                        if( ! empty($lastRecharge)){
                            if($lastRecharge[0]['va']['product_id'] != $params['operator']){
                                $products = $slaveObj->query("select service_id
										from products
										where id = " . $lastRecharge[0]['va']['product_id']);
                                $non_prepaid = ($products[0]['products']['service_id'] == 1) ? "0" : "1";

                                return array("status"=>"failure", "product_id"=>$lastRecharge[0]['va']['product_id'], "non_prepaid_operator_flag"=>$non_prepaid, "code"=>"43", "description"=>$this->Shop->apiErrors(43));
                            }
                        }
                        else{
                            $products = $slaveObj->query("select service_id
										from products
										where id = " . $operator_id);
                            $non_prepaid = ($products[0]['products']['service_id'] == 1) ? "0" : "1";

                            return array("status"=>"failure", "product_id"=>$operator_id, "non_prepaid_operator_flag"=>$non_prepaid, "code"=>"43", "description"=>$this->Shop->apiErrors(43));
                        }
                    }
                }
            }

            if(isset($params['no_prompt_within_one_hour_flag']) && empty($params['no_prompt_within_one_hour_flag'])){
                $recharge_done = $this->Shop->getMemcache("recharge_" . $_SESSION['Auth']['id'] . "_" . $params['operator'] . "_" . $params['mobileNumber'] . "_" . $params['amount']);

                if($recharge_done !== false){
                    $lastRecharge = explode("_", $recharge_done);
                    $recharge_time = date("g:i A", $lastRecharge[0]);
                    $time_elapsed = floor((time() - $lastRecharge[0]) / 60);

                    return array("status"=>"failure",
                            "lastRecharge"=>array('recharge_time'=>$recharge_time, 'time_elapsed'=>$time_elapsed, 'amount'=>$lastRecharge[1]), 'code'=>'42', "description"=>$this->Shop->apiErrors(42));
                }
            }
        }

        $ret = $this->Recharge->mobRecharge($params);

        if($ret['status'] == "success") $this->Shop->setMemcache("recharge_" . $_SESSION['Auth']['id'] . "_" . $params['operator'] . "_" . $params['mobileNumber'] . "_" . $params['amount'], $currentRecharge, 60 * 60);

        return $ret;
    }

    function mobBillPayment($params, $format){
        // parameter checks
        $noParams = 6;
        if(count($params) < $noParams){
            return array('status'=>'failure', 'code'=>'4', 'description'=>$this->Shop->errors(4));
        }
        else if($this->General->mobileValidate($params['mobileNumber']) == '1'){ // mobile no validation
            return array('status'=>'failure', 'code'=>'5', 'description'=>$this->Shop->errors(5));
        }
        else if($this->General->priceValidate($params['amount']) == ''){ // amount validation
            return array('status'=>'failure', 'code'=>'6', 'description'=>$this->Shop->errors(6));
        } 
        else if (!is_numeric($params['api_flag'])) {
            return array('status' => 'failure', 'code' => '3', 'description' => $this->Shop->errors(3));
        }
        $ret = $this->Recharge->billPayment($params);
        return $ret;
    }

    function pay1Wallet($params, $format){
        // parameter checks
        if($this->General->mobileValidate($params['mobileNumber']) == '1'){ // mobile no validation
            return array('status'=>'failure', 'code'=>'5', 'description'=>$this->Shop->errors(5));
        }
        else if($this->General->priceValidate($params['amount']) == ''){ // amount validation
            return array('status'=>'failure', 'code'=>'6', 'description'=>$this->Shop->errors(6));
        }
        $ret = $this->Recharge->pay1Wallet($params);
        return $ret;
    }

    function serviceCommission($params,$format){
        Configure::load('product_config');
        $charges = Configure::read('retailer_commission_utility');
        return array('status'=>'success','description'=>json_encode($charges));
    }

    function utilityBillFetch($params,$format){
        return $this->Recharge->utilityBillFetch($params);
    }

    function utilityBillInfo($params,$format){
        return $this->Recharge->utilityBillInfo($params);
    }

    function utilityBillPayment($params, $format){
        if($params['operator'] < 10){
            $mapping = array('1'=>'45','2'=>'46','3'=>'47','4'=>'48','5'=>'49','6'=>'50','7'=>'51');
            $params['operator'] = $mapping[$params['operator']];
        }
        return $this->Recharge->utilityBillPayment($params);
    }

    function dthRecharge($params, $format){
        // parameter checks
        $noParams = 6;
        if(count($params) < $noParams){
            return array('status'=>'failure', 'code'=>'4', 'description'=>$this->Shop->errors(4));
        }
        else if($this->General->mobileValidate($params['mobileNumber']) == '1'){ // mobile no validation
            return array('status'=>'failure', 'code'=>'5', 'description'=>$this->Shop->errors(5));
        }
        else if($this->General->priceValidate($params['amount']) == ''){ // amount validation
            return array('status'=>'failure', 'code'=>'6', 'description'=>$this->Shop->errors(6));
        }
        else if( ! in_array($params['type'], $this->validRecTypes)){
            return array('status'=>'failure', 'code'=>'7', 'description'=>$this->Shop->errors(7));
        }

        $ret = $this->Recharge->dthRecharge($params);
        return $ret;
    }

    function vasRecharge($params, $format){
        // parameter checks
        $ret = $this->Recharge->vasRecharge($params);
        return $ret;
    }

    function cashpgPayment($params, $format){
        /*if($params['mobileNumber'] != $_SESSION['Auth']['mobile']){
            return array('status'=>'failure','code'=>'61','description'=>$this->Shop->errors(61));
        }*/
        if($this->General->mobileValidate($params['mobileNumber']) == '1'){//mobile no validation
            return array('status'=>'failure','code'=>'5','description'=>$this->Shop->errors(5));
        }else if($this->General->priceValidate($params['amount']) == ''){//amount validation
            return array('status'=>'failure','code'=>'6','description'=>$this->Shop->errors(6));
        }

        $ret = $this->Recharge->cashpgPayment($params);
        return $ret;
    }
}

?>