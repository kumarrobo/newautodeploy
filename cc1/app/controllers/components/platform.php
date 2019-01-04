<?php
class PlatformComponent extends Object{
    var $components = array('General', 'Shop', 'Auth','Documentmanagement','Serviceintegration','Bridge','Servicemanagement');
    var $uses = array('User', 'Slaves','DocManagement');

    function errorDescription($errCode){
        $errors = array('202'=>'Update App Version','101'=>'OTP sent successfully', '102'=>'Token is missing', '103'=>'Token is not authentic', '104'=>'Method does not exists', '105'=>'Not enough balance', '106'=>'Some mysql technical problem', '107'=>'Duplicate Txn Id', '108'=>'Wrong input parameters',
                '109'=>'Txn not found', '110'=>'Txn already reversed', '111'=>'User does not exists', '112'=>'Txn found but amount not settled', '113'=>'No data found','114'=>'User is not active','115'=>'Mobile number is not valid','116'=>'is not valid','117'=>'User is not valid',
                '118'=>'App Request not authentic','119'=>'Input Parameters are coming wrong','120'=>'Your Mobile number or uuid should not blank',
                '121'=>'App version code is not valid','404'=>'Session does not exists','122'=>'You are not allowed to login here','123'=>'New pwd cannot be same as old pwd','124'=>'Pwd does not match','125'=>'Kindly create a strong password');
        return $errors[$errCode];
    }

    function requestValidation($data){

        $data1 = $this->dataDecryption($data['req']);
        $json = json_decode($data1,true);

        if(!isset($json['method'])) {
            return array('status'=>'failure', 'code'=>'145', 'description'=>'URL not valid');
        }
        else if(isset($json['user_id']) && !is_numeric($json['user_id'])) {
            return array('status' => 'failure','code' => '111','description' => $this->errorDescription("111"));
        }

        if(isset($data['document']))$json['document'] = $data['document'];

//        $this->logApiRequest($json);

        $aclCheck = $this->aclCheck($json);
        if($aclCheck['status'] == 'success'){
            $json['session_data'] = isset($aclCheck['session_data'])?$aclCheck['session_data']:'';
        }
        else{
            return $aclCheck;
        }

        $method = $json['method']."Validation";

        Configure::load('platform');
        $app_names = Configure::read('app_names');
        $api_param_counts = Configure::read('api_param_counts');

        if(empty($json['app_name']) || !in_array($json['app_name'],$app_names)){
            return array('status'=>'failure', 'code'=>'118', 'description'=>$this->errorDescription(118));
        }
        else if(isset($api_param_counts[$method]) && count($json) != $api_param_counts[$method]){
            return array('status'=>'failure', 'code'=>'119', 'description'=>$this->errorDescription(119));
        }

       if(method_exists($this, $method)){
            $ret = $this->$method($json);
        }
        else {
            $ret = array('status'=>'success');
        }

        if($ret['status'] == 'success'){
            $ret['params'] = $json;
        }

        return $ret;
    }

    function authenticateValidation($params){

        if( ! isset($params['mobile']) || empty($params['mobile']) ||  ! isset($params['uuid']) || empty($params['uuid'])):
            return array('status'=>'failure', 'code'=>'120', 'description'=>$this->errorDescription(120));
        elseif($this->General->mobileValidate($params['mobile']) == '1'):
            return array('status'=>'failure', 'code'=>'115', 'description'=>$this->errorDescription(115));
        elseif(!ctype_alnum(trim($params['uuid']))):
            return array('status' => 'failure','code'=>'116','description' =>'UUID '.$this->errorDescription(116));
        elseif( ! isset($params['version_code']) || empty($params['version_code']) || strlen($params['version_code']) > 5):
            return array('status'=>'failure', 'code'=>'121', 'description'=>$this->errorDescription(121));
        elseif(!empty($params['gcm_reg_id']) && !preg_match("/^[a-zA-Z0-9\-\_\:]*$/",$params['gcm_reg_id'])):
            return array('status' => 'failure','code' => '108','description' => $this->errorDescription(108));
        endif;

        return array('status'=>'success');
    }

    function resendOTPAuthenticateValidation($params){

        if( ! isset($params['mobile']) || empty($params['mobile']) ||  ! isset($params['user_id']) || empty($params['user_id'])):
        return array('status'=>'failure', 'code'=>'120', 'description'=>$this->errorDescription(120));
        elseif($this->General->mobileValidate($params['mobile']) == '1'): // mobile no validation
        return array('status'=>'failure', 'code'=>'115', 'description'=>$this->errorDescription(115));
        elseif(!ctype_alnum(trim($params['user_id']))):
        return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        endif;

        return array('status'=>'success');
    }

    function verifyOTPAuthenticateValidation($params){

        if(!ctype_alnum($params['uuid']) || !is_numeric($params['user_id'])):
        return array('status' => 'failure','code' => '2003','description' => "Invalid uuid or user_id");
        elseif( ! isset($params['mobile']) || empty($params['mobile']) ||  ! isset($params['user_id']) || empty($params['user_id'])):
        return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif($this->General->mobileValidate($params['mobile']) == '1'): // mobile no validation
        return array('status'=>'failure', 'code'=>'115', 'description'=>$this->errorDescription(115));
        elseif(!ctype_alnum(trim($params['user_id']))):
        return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!is_numeric($params['otp'])):
        return array('status' => 'failure','code' => '2005','description' => "Invalid OTP");
        endif;

        return array('status'=>'success');
    }

    function updatePinValidation($params){
        if((!ctype_alnum($params['old_pin']) || !ctype_alnum($params['new_pin'])) && $params['session_data']['User']['group_id'] != RETAILER) {
            return array('status' => 'failure','code' => '2007','description' => "Pin should be Alpha-Numeric");
        }

        return array('status'=>'success');
    }

    function walletHistoryValidation($params){

        if($this->General->dateValidate($params['date_from']) == 0 || $this->General->dateValidate($params['date_to']) == 0){
            return array('status'=>'failure','code'=>'6','description'=>$this->Shop->apiErrors(6));
        }

        return array('status'=>'success');
    }

    function commissionCalculationValidation($params){
        if(!is_numeric($params['product_id'])):
        return array('status'=>'failure', 'code'=>'2005', 'description'=>'Invalid Product');
        elseif(!is_numeric($params['service_id'])):
        return array('status'=>'failure', 'code'=>'2005', 'description'=>'Invalid Service');
        elseif(!is_numeric($params['amount'])):
        return array('status' => 'failure','code' => '2005','description' => "Invalid amount");
        elseif(isset($params['vendor_amount']) && !is_numeric($params['vendor_amount'])):
        return array('status' => 'failure','code' => '2005','description' => "Invalid offer amount");
        endif;

        return array('status'=>'success');
    }

    function getCommissionsValidation($params){
        if(isset($params['service_id']) && !is_numeric($params['service_id'])):
        return array('status'=>'failure', 'code'=>'2005', 'description'=>'Invalid Service');
        endif;

        return array('status'=>'success');
    }

    function deviceInfoUpdateValidation($params){

        if(!ctype_alnum(str_replace(array('_','-'),'',$params['app_name']))):
        return array('status' => 'failure','code' => '2008','description' => "Invalid app type");
        elseif(!ctype_alnum($params['device_type'])):
        return array('status' => 'failure','code' => '2009','description' => "Invalid device type");
        elseif(! isset($params['uuid']) || empty($params['uuid'])):
        return array('status'=>'failure', 'code'=>'28', 'description'=>'Your Mobile number or uuid should not blank');
        elseif(!ctype_alnum(trim($params['uuid']))):
        return array('status' => 'failure','code'=>'116','description' =>'UUID '.$this->errorDescription(116));
        elseif( ! isset($params['version_code']) || empty($params['version_code']) || !$this->floatValidation($params['version_code'])):
        return array('status'=>'failure', 'code'=>'28', 'description'=>'App version code is not valid');
        elseif( ! isset($params['version']) || empty($params['version']) || !$this->versionValidation($params['version'])):
        return array('status'=>'failure', 'code'=>'28', 'description'=>'OS version code is not valid');
        elseif((!empty($params['longitude']) && !$this->floatValidation($params['longitude'])) || (!empty($params['latitude']) && !$this->floatValidation($params['latitude']))):
        return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are not coming right');
        elseif(isset($params['manufacturer']) && !preg_match("/^([a-zA-Z0-9\- \(\)\|\.]+)$/",$params['manufacturer'])):
        return array('status'=>'failure', 'code'=>'28', 'description'=>'Device_manufacturer is not valid');
        elseif(!ctype_alnum($params['device_type'])):
        return array('status' => 'failure','code' => '2009','description' => "Invalid device type");
        elseif(!ctype_alnum(str_replace(array('_','-'),'',$params['app_name']))):
        return array('status' => 'failure','code' => '2008','description' => "Invalid app type");
        endif;


        $verify = $this->Shop->checkAuthenticateDeviceType($params['device_type']);

        if($verify == 0): // if device_type is not found
        return array('status'=>'failure', 'code'=>'28', 'description'=>$this->Shop->errors(28));
        endif;

        return array('status'=>'success');

    }

    function mobileValidation($params){
        if( ! isset($params['mobile']) || empty($params['mobile'])):
        $returnData = array('status'=>'failure', 'code'=>'28', 'description'=>'Your Mobile number should not blank');

        elseif($this->General->mobileValidate($params['mobile']) == '1'): // mobile no validation
        $returnData = array('status'=>'failure', 'code'=>'28', 'description'=>'Your Mobile number is not right');
        else: $returnData = array('status'=>'success');
        endif;


        return $returnData;
    }

    function floatValidation($value){
        if(!empty($value)){
            return preg_match("/^-?(?:\d+|\d*\.\d+)$/",$value);
        }
        return 1;
    }

    function versionValidation($value){
        if(!empty($value)){
            return preg_match("/^-?(?:\d+|\d*\.\d*(.\d*)*+)$/",$value);
        }
        return 1;
    }


    function changePinValidation($params){
        $returnData = $this->mobileValidation($params);
        return $returnData;
    }

    function verifyOTPChangePINValidation($params){
        $returnData = $this->mobileValidation($params);
        if($returnData['status'] == 'failure') return $returnData;

        $otp_validate = $this->General->numberValidate($params['otp']);
        if($otp_validate == 1){
            return array('status'=>'failure', 'code'=>'28', 'description'=>'OTP is not valid');
        }
        $ret = $this->Shop->isStrongPassword($params['pin']);
        if( ! $ret){
            return array('status'=>'failure', 'code'=>'28', 'description'=>'Please enter strong password with minimum 4 characters');
        }
        return array('status'=>'success');
    }

    function uploadDocsValidation($params)
    {
        $Object = ClassRegistry::init('DocManagement');
        $Object->set($params);

        Configure::load('platform');
        Configure::load('bridge');
        $labels = $this->Documentmanagement->getImpLabels();
        $notification_url = Configure::read('notification_url');

        if (!$Object->validates(array('fieldList' => array('user_id','service_id','label_id')))) {
            $response = array('status' => 'failure','description' => $Object->validationErrors);
            return $response;
        }

        if (!in_array($params['label_id'], array_map(function($element){return $element['key'];},$labels))) {
            $response = array('status' => 'failure', 'errCode' => 110, 'description' => $this->Documentmanagement->errorCodes(110));
            return $response;
        }

        if (!array_key_exists($params['service_id'], $notification_url)) {
            $response = array('status' => 'failure', 'errCode' => 109, 'description' => $this->Documentmanagement->errorCodes(109));
            return $response;
        }

        $config_type=$this->Documentmanagement->getLabelConfig('key','type');

        if($config_type[$params['label_id']]==1){
          $config_id=$this->Documentmanagement->getLabelConfig('key','id');
          if(!$this->Documentmanagement->checkServiceLabelMapping($config_id[$params['label_id']],$params['service_id'])){
              $response = array('status' => 'failure', 'errCode' => 108, 'description' => $this->Documentmanagement->errorCodes(108));
              return $response;
          }
        }
        if($config_type[$params['label_id']]==2){
            if (!$Object->validates(array('fieldList' => array('label_description')))) {
                $response = array('status' => 'failure','description' => $Object->validationErrors);
                return $response;
            }
        }

        return array('status'=>'success');
    }

//    function checkDocsValidation($params)
//    {
//        $Object = ClassRegistry::init('DocManagement');
//        $Object->set($params);
//        Configure::load('platform');
//        $notification_url = Configure::read('notification_url');
//
//        if (!$Object->validates(array('fieldList' => array('user_id','service_id')))) {
//            $response = array('status' => 'failure','description' => $Object->validationErrors);
//            return $response;
//        }
//        if(!array_key_exists($params['service_id'], $notification_url))
//        {
//            $response = array('status' => 'failure', 'errCode' => 109, 'description' => $this->Documentmanagement->errorCodes(109));
//            return $response;
//        }
////        if(!$this->Documentmanagement->checkUserServiceMapping($params['user_id'],$params['service_id']))
////        {
////            $response = array('status' => 'failure', 'errCode' => 108, 'description' => $this->Documentmanagement->errorCodes(108));
////            return $response;
////        }
//
//
//        return array('status'=>'success');
//    }
//
//
//    function sendDocumentDetailsEmailValidation($params)
//    {
//        $Object = ClassRegistry::init('DocManagement');
//        $Object->set($params);
//
//        if (!$Object->validates(array('fieldList' => array('user_id','email_id')))) {
//            $response = array('status' => 'failure','description' => $Object->validationErrors);
//            return $response;
//        }
//        $ref_code = array_filter($params['ref_code']);
//
//        if (empty($ref_code)) {
//            $response = array('status' => 'failure', 'errCode' => 111, 'description' => $this->Documentmanagement->errorCodes(111));
//            return $response;
//        }
//
//        return array('status'=>'success');
//    }
//
    function listInvoicesValidation($params)
    {
        if(!is_numeric($params['month'])) {
            return array('status' => 'failure','code' => '2019','description' => "Invalid month");
        }else if(!is_numeric($params['year'])) {
            return array('status' => 'failure','code' => '2020','description' => "Invalid year");
        }

        return array('status'=>'success');
    }

    function getInvoiceValidation($params)
    {
        //$Object = ClassRegistry::init('TaxInvoice');
        //$Object->set($params);

        Configure::load('platform');

       /* if (!$Object->validates(array('fieldList' => array('user_id','invoice_id','type')))) {
            $response = array('status' => 'failure','description' => $Object->validationErrors);
            return $response;
        }*/

        if($params['type']==2)
        {
            if(!isset($params['email_id']))
            {
                $response = array('status' => 'failure','description' => 'Email id required');
                return $response;
            }
            /*if (!$Object->validates(array('fieldList' => array('email_id'))))
            {
                $response = array('status' => 'failure','description' => $Object->validationErrors);
                return $response;
            } */
        }

        return array('status'=>'success');
    }

    function createRetDistNewLeadsValidation($params){
        if($this->General->mobileValidate($params['mobile']) == 1) {
            return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
        }else if(!ctype_alnum(str_replace(' ','',$params['name']))) {
            return array('status' => 'failure','code' => '2025', 'description' => "Invalid name");
        }else if(!is_numeric($params['pin_code'])) {
            return array('status' => 'failure', 'code' => '50', 'description' => $this->Shop->apiErrors(50));
        }else if(!ctype_alnum(str_replace(' ','',($params['shop_name'])))) {
            return array('status' => 'failure','code' => '2026', 'description' => "Invalid shop name");
        }else if(!ctype_alnum(trim($params['uuid']))){
            return array('status' => 'failure','code' => '2027','description' => "Invalid id");
        }

        return array('status'=>'success');
    }

    function verifyRetDistNewLeadsValidation($params){
        if($this->General->mobileValidate($params['mobile']) == 1) {
            return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
        }else if(!ctype_alnum($params['pin']) && !(isset($params['interest']) && $params['interest'] == 'Distributor')) {
            return array('status' => 'failure', 'code' => '50', 'description' => $this->Shop->apiErrors(50));
        }else if(!ctype_alnum($params['confirm_pin']) && !(isset($params['interest']) && $params['interest'] == 'Distributor')) {
            return array('status' => 'failure', 'code' => '50', 'description' => $this->Shop->apiErrors(50));
        }else if(!ctype_alnum(str_replace(' ','',($params['interest'])))) {
            return array('status' => 'failure','code' => '2029', 'description' => "Invalid type");
        }else if(!is_numeric($params['otp'])) {
            return array('status' => 'failure', 'code' => '50', 'description' => "Invalid OTP");
        }

        return array('status'=>'success');
    }

    function getPlansValidation($params){
        if(! isset($params['service_id']) || empty($params['service_id'])){
            return array('status'=>'failure','description'=>'Service ID missing');
        }
        return array('status'=>'success');
    }
    function purchaseKitValidation($params){
        if( !isset($params['service_id']) || empty($params['service_id']) ){
            return array('status'=>'failure','description'=>'Service ID missing');
        }
        if( !isset($params['plan_id']) || empty($params['plan_id']) ){
            return array('status'=>'failure','description'=>'Plan missing');
        }
        if( !isset($params['user_id']) || empty($params['user_id']) ){
            return array('status'=>'failure','description'=>'User ID missing');
        }
        return array('status'=>'success');
    }
    function upgradePlanValidation($params){
        if( !isset($params['service_id']) || empty($params['service_id']) ){
            return array('status'=>'failure','description'=>'Service ID missing');
        }
        if( !isset($params['plan_id']) || empty($params['plan_id']) ){
            return array('status'=>'failure','description'=>'Plan missing');
        }
        if( !isset($params['user_id']) || empty($params['user_id']) ){
            return array('status'=>'failure','description'=>'User ID missing');
        }
        return array('status'=>'success');
    }
    function requestServiceValidation($params){
        if( !isset($params['service_id']) || empty($params['service_id']) ){
            return array('status'=>'failure','description'=>'Service ID missing');
        }
        // if( !isset($params['plan_id']) || empty($params['plan_id']) ){
        //     return array('status'=>'failure','description'=>'Plan missing');
        // }
        if( !isset($params['user_id']) || empty($params['user_id']) ){
            return array('status'=>'failure','description'=>'User ID missing');
        }
        return array('status'=>'success');
    }
    function dataDecryption($code) {
        Configure::load('bridge');

        $key = Configure::read('requestKey');
        $hex_iv = '00000000000000000000000000000000';
        $key = hash('sha256', $key, true);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $key, $this->hexToStr($hex_iv));
        $str = mdecrypt_generic($td, base64_decode($code));
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $this->strippadding($str);
    }


    function strippadding($string) {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }

    function hexToStr($hex)
    {
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2)
        {
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }

    function aclCheck($data){
        Configure::load('platform');

        $whitelist_methods = Configure::read('whitelist_apis');
        // session check & group id check
        if(in_array($data['method'], $whitelist_methods)) return array('status'=>'success');
        $sess_token = $data['token'];
        $memcache = $this->Shop->memcacheConnection(MEMCACHE_MASTER);

        $val = $memcache->get($sess_token);

        if($val !== false){
            $val = unserialize(substr($val, strpos($val, "Auth|") + 5));
            $group_ids = explode(',',$val['User']['group_ids']);
            $user_id = $val['User']['id'];
            $method = $data['method'];

            if($user_id != $data['user_id']) return array('status'=>'failure','code'=>'403','description'=>$this->errorDescription(117));

            Configure::load('platform');
            $method_mapping= Configure::read('acl');

            if(!isset($method_mapping[$method])){
                return array('status'=>'failure','description'=>'Some technical problem. Please try again');
            }
            foreach($group_ids as $group_id) {
                if(in_array($group_id,$method_mapping[$method])){
                    return array('status'=>'success', 'code'=>0, 'session_data'=>$val);
                }
            }
        } else {
            return array('status'=>'failure','code'=>'403','description'=>$this->errorDescription(404));
        }

        return array('status'=>'failure','code'=>'103','description'=>$this->errorDescription(103));
    }


    function logApiRequest($data){
        $client_ip = $this->General->getClientIP();

        if(isset($data['password'])) { $data['password'] = 'xxxx'; }

        $transObj = ClassRegistry::init('PlatformLog');
        $this->data['PlatformLog']['method'] = $data['method'];
        $this->data['PlatformLog']['params'] = json_encode($data);
        $this->data['PlatformLog']['user_id'] = $data['user_id'];
        $this->data['PlatformLog']['ip'] = $client_ip;
        $this->data['PlatformLog']['timesatmp'] = date('Y-m-d H:i:s');
        $this->data['PlatformLog']['date'] = date('Y-m-d');

        $root = isset($_GET['root']) ? $_GET['root'] : "";

//        $transObj->create();
//        if($transObj->save($this->data)){
//            $app_log_id = $transObj->getInsertID();
//        }

        $filename = "platformapis.txt";
        $data = "Request : " . json_encode($this->data['PlatformLog']) . " :: Server Info : " . json_encode($_SERVER);
        $this->General->logData($filename, $data);

        return $app_log_id;
    }

    function getCommissions($user_id,$service){
        $dbObj = ClassRegistry::init('Slaves');
        $plan = $dbObj->query("SELECT spp.ret_params,products.earning_type,products.name,products.id from users_services left join service_plans on (service_plans.id = users_services.service_plan_id) left join service_product_plans as spp on (spp.service_plan_id = service_plans.id) left join products on (products.id = spp.product_id) WHERE user_id = '$user_id' AND service_plans.service_id = '$service'");

        $ret = array();
        foreach($plan as $p){
            $prod_name = $p['products']['name'];
            $product_id = $p['products']['id'];
            $plan_params = json_decode($p['spp']['ret_params'],true);
            if(!empty($plan_params)){
                $table = array();
                foreach($plan_params as $key=>$p_p){
                    $data = array();
                    $data['ret_margin']['margin'] = $p_p['margin'];
                    $data['min'] = $p_p['min'];
                    $data['max'] = $p_p['max'];
                    $data['ret_margin']['service_tax'] = 0;

                    $prod_type = $plan[0]['products']['earning_type'];
                    if($prod_type == 2){
                        $data['ret_margin']['service_charge'] = 1;
                    }
                    else {
                        $data['ret_margin']['service_charge'] = 0;
                    }

                    if($key=='0-0'){
                        $key = 'All';
                    }
                    $table[$key] = $data;
                }
                $ret[$product_id]['name'] = $prod_name;
                $ret[$product_id]['margins'] = $table;
            }

        }

        return $ret;
    }

    function __checkUserExist($mobile, $password, $groups,$params = null){
        $params['app_name'] = (!isset($params['app_name'])) ? 'recharge_app' : $params['app_name'];

        $sqlQuery = "SELECT users.*,group_concat(user_groups.group_id) as groupids,user_profile.id FROM users INNER JOIN user_groups ON (users.id =user_groups.user_id) LEFT JOIN user_profile on (user_profile.user_id = users.id and user_profile.uuid = '" . $params['uuid'] . "' And user_profile.app_type ='" . $params['app_name'] . "') WHERE mobile = '" . $mobile . "' AND password = '" . $password . "' AND user_groups.group_id IN (" . implode(",", $groups) . ") group by user_profile.user_id";

        $dbObj = ClassRegistry::init('Slaves');
        $data = $dbObj->query($sqlQuery);

        if(empty($data)):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->Shop->errors(28));
        else:
            return $data[0];
        endif;
    }


    function checkAppVersion($appVersionCode,$app_name){

        Configure::load('platform');
        $app_versions = Configure::read('app_versions_force_upgrade');

        /*if($app_name == 'dmt' && isset($app_versions[$app_name]) && $appVersionCode < $app_versions[$app_name]):
            return array("status"=>"failure", "code"=>"202", "description"=>$this->errorDescription(202), "forced_upgrade_flag" => 1);
        endif;

        if($app_name == 'smartpay' && isset($app_versions[$app_name]) && $appVersionCode < $app_versions[$app_name]):
            return array("status"=>"failure", "code"=>"202", "description"=>$this->errorDescription(202), "forced_upgrade_flag" => 1);
        endif;*/

        if(isset($app_versions[$app_name]) && $appVersionCode < $app_versions[$app_name]):
            return array("status"=>"failure", "code"=>"202", "description"=>$this->errorDescription(202), "forced_upgrade_flag" =>1);
        endif;


        return array("status"=>"success");
    }

    // Send OTP on User Mobile Number for User Device Mapping on Web
    function sendOTPToUserDeviceMapping($mobile, $otp_via_call, $user_id, $lat_long_dist=0){
        $otp = $this->General->generatePassword(6);
        $MsgTemplate = $this->General->LoadApiBalance();
        $paramdata['OTP'] = $otp;

        $dbObj = ClassRegistry::init('Slaves');
        $data = $dbObj->query("SELECT id FROM users WHERE id = $user_id AND mobile='$mobile'");
        if(empty($data)){
            return array("status"=>"failure", 'code'=>'62', "description"=>'Data is not correct');
        }

        if($lat_long_dist){
            $content = $MsgTemplate['Retailer_LocationVerify_OTP_MSG'];
        }
        else{
            $content = $MsgTemplate['Retailer_DeviceVerify_OTP_MSG'];
        }
        $message = $this->General->ReplaceMultiWord($paramdata, $content);
        $this->General->logData("api_authenticate_OTP.txt", "in authenticate_new api: " . json_encode($message));

        $this->Shop->setMemcache("otp_userProfileNewUuid_$mobile", $otp, 30 * 60);

        if($otp_via_call == 1){

            if($this->Shop->getMemcache("user_otp_via_call_$mobile")){
                return array("status"=>"failure", 'code'=>'62', "description"=>$this->Shop->apiErrors('62'));
            }
            $this->Shop->setMemcache("user_otp_via_call_$mobile", $otp_via_call, 1 * 10);
            $this->General->curl_post_async("http://click2call.ddns.net/otp.php", array('mobile'=>'2294', 'incoming_route'=>$mobile, 'otp'=>$otp));

            return array("status"=>"success",'code'=>'101', 'user_id' => $user_id,'description'=>'Request sent successfully. You should receive a call in sometime');
        }
        $this->General->sendMessage($mobile, $message, 'payone', null);

        return array('status'=>'failure','code'=>'101','user_id' => $user_id,'description'=>'You should receive an OTP in sometime');
     }

     function createRetDistNewLeads ($params) {
            $create_lead['name'] = $params['name'];
            $create_lead['mobile'] = $params['mobile'];
            $create_lead['email'] = $params['email'];
            $create_lead['pin_code'] = $params['pin_code'];
            $create_lead['shop_name'] = $params['shop_name'];
            $create_lead['interest'] = $params['interest'];
//            $create_lead['req_by'] = $params['req_by'];
            $create_lead['current_business'] = $params['current_business'];
            $create_lead['lead_source'] = $params['lead_source'];
            $create_lead['lead_campaign'] = $params['lead_campaign'];
            $create_lead['longitude'] = $params['longitude'];
            $create_lead['latitude'] = $params['latitude'];
            $create_lead['uuid'] = $params['uuid'];
            $create_lead['device_type'] = $params['device_type'];
            $create_lead['app_type'] = $params['lead_source'];
            $create_lead['app_name'] = $params['app_name'];
            $create_lead['rm_user_id'] = $params['rm_user_id'];
            $create_lead['followup_date'] = $params['followup_date'];
            $create_lead['followup_remark'] = $params['followup_remark'];
            $create_lead['status'] = $params['status'];
            $create_lead['dist_id'] = $params['dist_id'];
            $create_lead['converted_dist_id'] = $params['converted_dist_id'];
            $create_lead['lead_type'] = $params['lead_type'];
            $create_lead['converted_date'] = $params['converted_date'];

            if(array_key_exists('utm_params',$params)){
            	$this->General->logData('/mnt/logs/'.$filename, 'utm params in $params');
            	if (strpos($params['utm_params'], 'utm_campaign') !== false){
            		$utmUrl=$params['utm_params'];
            		$utmArray=explode('?',$utmUrl);
            		$utmArray = (explode('&',$utmArray[1]));
            		foreach($utmArray as $value){
            			if (strpos($value, 'utm_campaign') !== false){
            				$valueArr=explode('=',$value);
            				$utmCampaign=$valueArr[1];
            				$create_lead['utm_campaign'] =$utmCampaign;
            				break;
            			}
            		}
            	}
            }


            Configure::load('platform');
            $lead_states_mapping = Configure::read('lead_state_mapping');
            $lead_source = Configure::read('lead_source');
            $create_lead['lead_state'] = isset($lead_states_mapping[$params['lead_source']])?$lead_states_mapping[$params['lead_source']]:3;
            $create_lead['lead_source'] = isset($lead_source[$params['lead_source']])?$lead_source[$params['lead_source']]:'';

            $validate_mobile = $this->General->mobileValidate($params['mobile']);

            $dbObj = ClassRegistry::init('User');

            if($validate_mobile != '1')
            {
                $get_state_city = "SELECT p.state_id,p.city_id,gs.name AS state,gc.name AS city "
                                . "FROM pincodes p "
                                . "JOIN geo_locations gs "
                                . "ON (p.state_id = gs.id AND gs.parent_id IS NULL) "
                                . "JOIN geo_locations gc "
                                . "ON (p.city_id = gc.id AND gc.parent_id = gs.id) "
                                . "WHERE pincode = '".$params['pin_code']."' "
                                . "GROUP BY pincode";

                $state_city = $dbObj->query($get_state_city);

                if(!empty($state_city))
                {
                    $create_lead['state'] = $state_city[0]['gs']['state'];
                    $create_lead['city'] = $state_city[0]['gc']['city'];
                }
                else
                {
                    $area = $this->General->getAreaByLatLong($params['longitude'],$params['latitude'],$params['pin_code']);
                    $create_lead['state'] = $area['state_name'];
                    $create_lead['city'] = $area['city_name'];
                }

                if($params['ref']){
                    $create_lead['req_by'] = base64_decode($ref);
                }

                if(strlen( $params['mobile']) != 10){
                    return array('status' => 'failure','code'=>'50','description' => $this->Shop->apiErrors('50'));
                }

                //Only Users, Group ID with 1 can became Retailer or Distrubtor
                $user_exist = $dbObj->query("Select group_id from user_groups inner join users ON (users.id = user_groups.user_id) where mobile = '".$create_lead['mobile']."' and user_groups.group_id != 1 ");

                if($user_exist && $create_lead['interest']!='Distributor'){
                   return array('status' => 'failure','code'=>'60','description' => $this->Shop->apiErrors('60'));
                }

                if($create_lead['interest'] == 'Retailer'){
                   $retailer_exists = $dbObj->query("select * from retailers where mobile = '".$create_lead['mobile']."'");
                }else if($create_lead['interest'] == 'Distributor'){
                   $distributor_exists = $dbObj->query("select * from users us join distributors d on d.user_id = us.id where us.mobile = '".$create_lead['mobile']."' ");
                }else{
                    return array('status' => 'failure','code'=>'58','description' => $this->Shop->apiErrors('58'));
                }

                if($retailer_exists){
                    return array('status' => 'failure','code'=>'52','description' => $this->Shop->apiErrors('52'));
                }

                if($distributor_exists){
                    return array('status' => 'failure','code'=>'53','description' => $this->Shop->apiErrors('53'));
                }

                $lead_exists = $dbObj->query("select * from leads where phone = '".$create_lead['mobile']."'");
                $lead_exists2 = $dbObj->query("select * from leads_new where phone = '".$create_lead['mobile']."'");
                $signup_count = $lead_exists2[0]['leads_new']['signup_count'] + 1;
                if((empty($lead_exists) || (!empty($lead_exists) && $create_lead['interest']=='Distributor' && $lead_exists[0]['leads']['interest']!='Distributor' )) && (empty($lead_exists2) || (!empty($lead_exists2) && $create_lead['interest']=='Distributor' && $lead_exists2[0]['leads_new']['interest']!=2 ))){



                        if($create_lead['app_name'] == "rm_app"){



                            $lead_source_id = 0;
                            $lead_source_query = $dbObj->query("select * from lead_attributes_values where lead_values = '".$create_lead['app_type']."'");
                            if(!empty($lead_source_query))
                            {
                                $lead_source_id = $lead_source_query[0]['lead_attributes_values']['id'];
                            }

                            $lead_mobile = $create_lead['mobile'];
                            $token = md5($lead_mobile);
                            $lead_status = $create_lead['status'];
                            $lead_state =  $lead_status;
                            $sub_status = 20;
                            $interest = 2;
                            $dbObj->query("insert into leads_new
                                (interest, name, shop_name, email, phone,city, state, pin_code, lead_source,lead_campaign,lead_state ,status,sub_status,otp_flag,creation_date, lead_timestamp,current_business,signup_count,token,rm_user_id,followup_date,followup_remark,dist_id,converted_dist_id,lead_type,converted_date)
                                values ('$interest', '".mysql_escape_string($create_lead['name'])."', '".mysql_escape_string($create_lead['shop_name'])."',"
                                    . "'".$create_lead['email']."','$lead_mobile', '".$create_lead['city']."', '".$create_lead['state']."',"
                                    . "'".$create_lead['pin_code']."','".$lead_source_id."','".$create_lead['lead_campaign']."',"
                                    . "'".$lead_state."','$lead_status','$sub_status','1', '".date('Y-m-d')."', '".date('Y-m-d H:i:s')."',"
                                    . "'".mysql_escape_string($create_lead['current_business'])."','1','$token','".$create_lead['rm_user_id']."',"
                                    . "'".$create_lead['followup_date']."','".mysql_escape_string($create_lead['followup_remark'])."',"
                                    . "'".$create_lead['dist_id']."','".$create_lead['converted_dist_id']."',"
                                    . "'".$create_lead['lead_type']."','".$create_lead['converted_date']."')");

                            if($create_lead['interest'] == 'Distributor') {
                                $zoho_id = $this->Shop->addLeadsIntoZoho($create_lead);
                                $this->Shop->setMemcache("zoho_lead_id_$lead_mobile", $zoho_id);
                            }

                            $subject = "I want to become a ".$create_lead['interest'];

                            $body = "
                            </br> Contact       : ".$create_lead['mobile']."
                            </br> Interested In : ".$create_lead['interest']."
                            </br> Source        : ".$create_lead['lead_source'];

                            $this->General->sendMails($subject, $body, array('sales@pay1.in', 'info@pay1.in'), 'mail');

                            $filename = "lead_management_".date('Ymd').".txt";
                            $this->General->logData('/mnt/logs/'.$filename, json_encode($create_lead));

                            if(trim($create_lead['interest']) == "Distributor"){

                                $columns = array();
                                $columns['mx_Shop_Name'] = $create_lead['shop_name'];
                                $columns['mx_Retailer_Name'] = $create_lead['name'];
                                $columns['EmailAddress'] = $lead_mobile.'@pay1.in';
                                $columns['Mobile'] = $create_lead['mobile'];
                                $columns['mx_State'] = $create_lead['state'];
                                $columns['mx_City'] = $create_lead['city'];
                                $columns['mx_Pin_Code'] = $create_lead['pin_code'];
                                $columns['mx_Messages'] = $create_lead['messages'];
                                $columns['mx_Date'] = date('Y-m-d');
                                $columns['mx_Timestamp'] = date('Y-m-d H:i:s');
                                $columns['Source'] = $create_lead['lead_source'];
                                $columns['mx_Interest'] = $create_lead['interest'];

                                $this->General->logData('/mnt/logs/'.$filename, json_encode($columns));

                                App::import('Controller', 'Leadmanagement');
                                $obj = new LeadmanagementController;
                                $obj->constructClasses();
                                $obj->createLead($columns);
                                $mobile = $create_lead['mobile'];

                                $lead_base_url = LEAD_BASE_URL;
                                $lead_form_url = $this->Shop->shortenurl('http://'.$lead_base_url.'/lead/index/'.$mobile.'/'.$token);

                                $paramdata['URL'] = $lead_form_url['id'];

                                $MsgTemplate = $this->General->LoadApiBalance();

                                $content =  $MsgTemplate['Lead_Application_Form_MSG'];
                                $message = $this->General->ReplaceMultiWord($paramdata,$content);
                                $this->General->sendMessage($create_lead['mobile'], $message, "payone");

                                $this->General->logData('/mnt/logs/'.$filename, "lead_url ".$lead_form_url['id']);

                                $sub = "Distributor Application Form";
                                $body = "Thank you for showing interest to become Pay1 distributor. To know more about the proposal, click here. http://pay1.in/lead/index/".$create_lead['mobile']."/".$token."?src=email";
                                $this->General->sendMails($sub,$body,array($create_lead['email']));
                            }

                            return array('status' => 'success', 'code'=>'55', 'description' => $this->Shop->apiErrors('55'));

                        } else {

                            $redis = $this->Shop->redis_connect();
                            foreach ($create_lead as $key => $value) {
    //                           $redis->hSet('Retailers_Distributors_Leads', $key, $value);
                               $redis->hSet('Retailers_Distributors_Leads_'.$create_lead['mobile'], $key, $value);
                            }

                            App::import('Controller', 'Apis');
                            $obj = new ApisController;
                            $obj->constructClasses();

                            $data = $obj->sendOTPToRetDistLeads($create_lead);
                            return $data;
                        }

                }
                elseif(((!empty($lead_exists) && $create_lead['interest']=='Distributor' && $lead_exists[0]['leads']['interest']=='Distributor' )) || ((!empty($lead_exists2) && $create_lead['interest']=='Distributor' && $lead_exists2[0]['leads_new']['interest']==2 )))
                {
                    $update_signup_count = $dbObj->query("UPDATE leads_new SET signup_count = '$signup_count' WHERE phone = '".$create_lead['mobile']."' ");

    //                    $MsgTemplate = $this->General->LoadApiBalance();
    //                    $message =  $MsgTemplate['Duplicate_Lead_Request'];
    //                    $this->General->sendMessage($create_lead['mobile'], $message, "payone");

                    return array('status' => 'failure','code'=>'65','description' => $this->Shop->apiErrors('65'));
                }
                else{
                    return array('status' => 'failure','code'=>'51','description' => $this->Shop->apiErrors('51'));
                }
            }
            else
            {
                return array('status' => 'failure','code'=>'67','description' => $this->Shop->apiErrors('67'));
            }
     }

     function verifyRetDistNewLeads ($params) {

        $dbObj = ClassRegistry::init('User');

            $lead_mobile = $params['mobile'];
            $otp = $params['otp'];
            $interest = trim($params['interest']);
            $pin = $params['pin'];
            $confrimPin  = $params['confirm_pin'];
            $lead_base_url = LEAD_BASE_URL;

            if($interest=='Retailer'){
                if($pin!=$confrimPin){
                     return array('status' => 'failure','code'=>'57','description' => $this->Shop->apiErrors('57'));
                }

            }

            if((strlen($otp) != 6) || (strlen($lead_mobile) != 10)){
                return array('status' => 'failure','code'=>'57','description' => $this->Shop->apiErrors('57'));
            }

            if(trim($lead_mobile)){

                $retailer_exists = $dbObj->query("select * from retailers where mobile = '".$lead_mobile."'");
                if($retailer_exists){
                    return array('status' => 'failure','code'=>'52','description' => $this->Shop->apiErrors('52'));
                }

                $distributor_exists =  $dbObj->query("select * from users us join distributors d on d.user_id = us.id where us.mobile = '".$lead_mobile."' ");
                if($distributor_exists){
                    return array('status' => 'failure','code'=>'53','description' => $this->Shop->apiErrors('53'));
                }

                $leads_exists = $dbObj->query("select * from leads_new where phone = '".$lead_mobile."'");

                    if(empty($leads_exists)){

                      if($otp == $this->Shop->getMemcache("otp_RetDist_Registration_$lead_mobile")){
                            $this->Shop->delMemcache("otp_RetDist_Registration_$lead_mobile");
//                            $token = $this->General->generatePassword(10);
                            $token = md5($lead_mobile);
                            $redis = $this->Shop->redis_connect();
//                            $create_lead =  $redis->hgetall('Retailers_Distributors_Leads');
                            $create_lead =  $redis->hgetall('Retailers_Distributors_Leads_'.$lead_mobile);
                            $lead_status = ($create_lead['interest'] == "Retailer")?17:16;
                            $sub_status = ($create_lead['interest'] == "Retailer")?23:20;
                            $interest = ($create_lead['interest'] == "Retailer")?1:2;

                            $dbObj->query("insert into leads_new
                                (interest, name, shop_name, email, phone,city, state, pin_code, lead_source,lead_campaign,lead_state ,status,sub_status,otp_flag,creation_date, lead_timestamp,current_business,signup_count,token)
                                values ('$interest', '".$create_lead['name']."', '".$create_lead['shop_name']."',
                                        '".$create_lead['email']."',
                                        '$lead_mobile', '".$create_lead['city']."', '".$create_lead['state']."','".$create_lead['pin_code']."',
                                        '".$create_lead['lead_source']."','".$create_lead['lead_campaign']."','".$create_lead['lead_state']."','$lead_status','$sub_status','1', '".date('Y-m-d')."', '".date('Y-m-d H:i:s')."','".$create_lead['current_business']."','1','$token')");

                            if($create_lead['interest'] == 'Distributor')
                            {
                                $zoho_id = $this->Shop->addLeadsIntoZoho($create_lead);
                                $this->Shop->setMemcache("zoho_lead_id_$lead_mobile", $zoho_id);
                            }

                            /* Remove Key from redis */
                            $key='Retailers_Distributors_Leads_'.$lead_mobile;

                            array_map(function($a) use ($redis,$key) {$redis->hdel($key,$a);},array_keys($create_lead));

                            $subject = "I want to become a ".$create_lead['interest'];

                            $body = "
                            </br> Contact       : ".$create_lead['mobile']."
                            </br> Interested In : ".$create_lead['interest']."
                            </br> Source        : ".$create_lead['lead_source'];

                            $this->General->sendMails($subject, $body, array('sales@pay1.in', 'info@pay1.in'), 'mail');

                            $filename = "lead_management_".date('Ymd').".txt";
                            $this->General->logData('/mnt/logs/'.$filename, json_encode($create_lead));

                            if(trim($create_lead['interest']) == "Distributor"){

                                $columns = array();
                                $columns['mx_Shop_Name'] = $create_lead['shop_name'];
                                $columns['mx_Retailer_Name'] = $create_lead['name'];
                                $columns['EmailAddress'] = $lead_mobile.'@pay1.in';
                                $columns['Mobile'] = $create_lead['mobile'];
                                $columns['mx_State'] = $create_lead['state'];
                                $columns['mx_City'] = $create_lead['city'];
                                $columns['mx_Pin_Code'] = $create_lead['pin_code'];
                                $columns['mx_Messages'] = $create_lead['messages'];
                                $columns['mx_Date'] = date('Y-m-d');
                                $columns['mx_Timestamp'] = date('Y-m-d H:i:s');
                                $columns['Source'] = $create_lead['lead_source'];
                                $columns['mx_Interest'] = $create_lead['interest'];

                                $this->General->logData('/mnt/logs/'.$filename, json_encode($columns));

                                App::import('Controller', 'Leadmanagement');
                                $obj = new LeadmanagementController;
                                $obj->constructClasses();
                                $obj->createLead($columns);
                                $mobile = $create_lead['mobile'];
//                                $paramdata['MOBILE'] = $create_lead['mobile'];
//                                $paramdata['TOKEN'] = $token;
                                $lead_form_url = $this->Shop->shortenurl('http://'.$lead_base_url.'/lead/index/'.$mobile.'/'.$token);
                                $paramdata['URL'] = $lead_form_url['id'];
                                $MsgTemplate = $this->General->LoadApiBalance();
                                $content =  $MsgTemplate['Lead_Application_Form_MSG'];
                                $message = $this->General->ReplaceMultiWord($paramdata,$content);
                                $this->General->sendMessage($create_lead['mobile'], $message, "payone");

                                $this->General->logData('/mnt/logs/'.$filename, "lead_url ".$lead_form_url['id']);

                                $sub = "Distributor Application Form";
                                $body = "Thank you for showing interest to become Pay1 distributor. To know more about the proposal, click here. http://pay1.in/lead/index/".$create_lead['mobile']."/".$token."?src=email";
                                $this->General->sendMails($sub,$body,array($create_lead['email']));
                            }

                            if(trim($create_lead['interest']) == "Retailer"){
                                $MsgTemplate = $this->General->LoadApiBalance();
                                $message =  $MsgTemplate['Create_Ret_Leads_MSG'];
                                $this->General->sendMessage($create_lead['mobile'], $message, "payone");

                                $retailer = $dbObj->query("select interest, name , shop_name , email, state , city , pin_code,phone , lead_timestamp, lead_source, creation_date  from leads_new where phone = '$lead_mobile'");

                                $retailer = $retailer[0]['leads_new'];
                                $retailer['distributor_user_id'] = 8;
                                $retailer['api_flow'] = "verify_lead";
                                $retailer['name'] = $retailer['name'];
                                $retailer['r_u_d'] = $params['r_u_d'];
                                $retailer['pin'] = $pin;

                                App::import('Controller', 'Apis');
                                $api_obj = new ApisController;
                                $api_obj->constructClasses();

                                $register_user = $api_obj->createRetailer($retailer);
                                $this->General->logData('/mnt/logs/'.$filename, "resister_user".json_encode($register_user));
                                if(($register_user['status'] == 'success') && (isset($register_user['description']['User']['Retailer']['user_id'])) && (!empty($register_user['description']['User']['Retailer']['user_id'])))
                                {
                                    $user_info['user_id'] = $register_user['description']['User']['Retailer']['user_id'];
                                    $user_info['uuid'] = $create_lead['uuid'];
                                    $user_info['device_type'] = $create_lead['device_type'];
                                    $user_info['app_name'] = $create_lead['app_name'];
                                    if($create_lead['device_type'] != 'web')
                                    {
                                        $this->deviceInfoUpdate($user_info);
                                    }
                                }

                                if(array_key_exists('utm_campaign', $create_lead)){
                                	$this->callReferralApi($user_info['user_id'], $create_lead['utm_campaign']);
                                }
                                return array('status' => 'success', 'code'=>'55', 'description' => $this->Shop->apiErrors('55'));

                            }else if(trim($create_lead['interest']) == "Distributor"){

                                return array('status' => 'success', 'code'=>'55', 'description' => $this->Shop->apiErrors('55'));
                            }
                            return array('status' => 'failure','code'=>'56','description' => $this->Shop->apiErrors('56'));
                        }
                        else{
                            return array('status' => 'failure','code'=>'54','description' => $this->Shop->apiErrors('54'));
                        }
                    }
                    elseif(!empty($leads_exists) && ($leads_exists[0]['leads_new']['otp_flag'] == 0))
                    {
                        if($otp == $this->Shop->getMemcache("otp_RetDist_Registration_$lead_mobile")){
                            $this->Shop->delMemcache("otp_RetDist_Registration_$lead_mobile");

                            $dbObj->query("UPDATE leads_new SET otp_flag = '1' WHERE phone = '$lead_mobile' ");

                            return array('status' => 'success', 'code'=>'66', 'description' => $this->Shop->apiErrors('66'));
                        }
                        else{
                            return array('status' => 'failure','code'=>'54','description' => $this->Shop->apiErrors('54'));
                        }
                    }
                    else{
                        return array('status' => 'failure','code'=>'51','description' => $this->Shop->apiErrors('51'));
                    }

            }
            else
                    return array('status' => 'failure','code'=>'50','description' => "Mobile ".$this->Shop->apiErrors('50'));
     }

    function deviceInfoUpdate($params){

        $dbObj = ClassRegistry::init('User');

       /* if((round($params['longitude'],1) == '77.0' && round($params['latitude'],1) == '20.0') || ($params['longitude'] == '77' && $params['latitude'] == '20')){
            $params['longitude'] = '';
            $params['latitude'] = '';
        }*/

        $server_lat = (isset($_SERVER['GEOIP_LATITUDE']) &&  ! empty($_SERVER['GEOIP_LATITUDE'])) ? $_SERVER['GEOIP_LATITUDE'] : "";
        $server_long = (isset($_SERVER['GEOIP_LONGITUDE']) &&  ! empty($_SERVER['GEOIP_LONGITUDE'])) ? $_SERVER['GEOIP_LONGITUDE'] : "";
        $uuid = $params['uuid'];
        $device_type = $params['device_type'];

        if($device_type != 'android'){
            $longitude = empty($params['longitude']) ? $server_long : $params['longitude'];
            $latitude = empty($params['latitude']) ? $server_lat : $params['latitude'];
        }
        else {
            $longitude = $params['longitude'];
            $latitude = $params['latitude'];
        }

        $this->General->enterUserLocation($params['user_id'],RETAILER,$device_type,$latitude,$longitude);

        $app_version_code = $params['version_code'];

        $gcm_reg_id = empty($params['gcm_reg_id']) ? "" : $params['gcm_reg_id'];
        $location_src = 'network';
        $device_ver = $params['version'];
        $device_manufacturer = $params['manufacturer'];
        $area_id = $this->General->getAreaIDByLatLong($longitude, $latitude);
        $app_type = $params['app_name'];
        $user_id = $params['user_id'];
        $mac_id = (isset($params['mac_id'])) ? $params['mac_id'] : '';
        $device_manufacturer = (isset($params['mac_id'])) ?  $device_manufacturer . ":" . $mac_id : $device_manufacturer;

        $getUserProfileData = $dbObj->query("SELECT user_profile.id,users.mobile FROM user_profile left join users ON (users.id = user_id) WHERE user_profile.uuid = '$uuid' And user_profile.app_type ='$app_type' AND user_profile.user_id = '$user_id'");
        if(empty($getUserProfileData[0]['user_profile']['id'])):
            $dbObj->query("INSERT INTO `shops`.`user_profile` (`id`,`user_id`, `gcm_reg_id`, `uuid`, `longitude`, `latitude`, `location_src` , `area_id`,`device_type` ,`version` ,`app_type`, `manufacturer`, `created`, `updated`,`date`) " . "VALUES (NULL,'$user_id', '$gcm_reg_id', '$uuid', '$longitude', '$latitude', '" . $location_src . "' ,'$area_id','" . $device_type . "' ,'" . $device_ver . "' ,'" . $app_type . "','" . $device_manufacturer . "' ,'" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "','".date('Y-m-d')."');");
        else:
            $dbObj->query("UPDATE `shops`.`user_profile` set `gcm_reg_id`= '$gcm_reg_id',location_src='$location_src' , `longitude` = '$longitude',  `latitude` = '$latitude', `device_type` = '$device_type' , `version` = '$device_ver' ,`area_id`='$area_id', `manufacturer` ='$device_manufacturer',`updated` = '" . date("Y-m-d H:i:s") . "',`date`='".date('Y-m-d')."'  where user_id = '$user_id' AND uuid = '$uuid' and app_type = '" . $app_type . "'"); // `uuid` = '$uuid',
        endif;

        $this->redis = $this->Shop->redis_connect();
        $this->redis->set($app_type."_".$user_id."_".$device_type, $gcm_reg_id);
        $this->redis->expire($app_type."_".$user_id."_".$device_type, 30*24*60*60);

        if($app_type == 'recharge_app'){
            $device_mapping = array('android'=>'android_fcm','web'=>'web');
            $this->Shop->setRetailerTrnsDetails($getUserProfileData[0]['users']['mobile'],array('trans_type'=>$device_mapping[$device_type],'notification_key'=>$gcm_reg_id));
        }

        return array('status'=>'success');
    }

    function callReferralApi($userId, $uniqueKey) {
            try{
                    $curl_url = GAMIFICATION_REFERRAL_API;
                    $curl_params = array('user_id'=>$userId, 'user_unique_key'=>$uniqueKey);
                    $this->General->curl_post($curl_url, $curl_params);
            }
            catch(Exception $e){
                    $filename = 'gamification'.date('Ymd').'.txt';
                    $this->General->logData('/mnt/logs/'.$filename, 'Value of exception '. $e->getMessage());
            }

    }

    function getPanStatusValidation($params)
    {
        if( !isset($params['pan_no']) || empty($params['pan_no']) )
        {
            $response = array('status' => 'failure','description' => 'PAN required');
            return $response;
        }
        $pan_validation = $this->validatePan($params['pan_no']);
        return $pan_validation;
    }

    function profileApiValidation($params){
        if(isset($params['service_id'])){
            $regex = '/^\d+(?:,\d+)*$/';
            if (!preg_match($regex, $params['service_id'])){
                return array('status'=>'failure','description'=>'Invalid service ids.');
            }
        }
        return array('status' => 'success');
    }

    function validatePan($pan_nos){
        foreach(explode(',',$pan_nos) as $pan_no){
            if( !preg_match("/[A-Za-z]{5}\d{4}[A-Za-z]{1}/",$pan_no) ){
                return array('status'=>'failure','description'=> 'PAN number '.$pan_no. ' is invalid');
            }
        }
        return array('status' => 'success');
    }

    function getPanStatus($pan_nos = array()){
        if( count($pan_nos) > 0 ){

            Configure::load('platform');
            $pan_errors = Configure::read('pan_errors');
            $pan_statuses = Configure::read('pan_statuses');
            $pan_config = Configure::read('pan_config');

            $panNoStr = implode("^", $pan_nos);
            // PAN Verification api call
            $file = 'oupt'.strtotime(date("Y-m-d").date("H:i:s")).'.sig';
            $fileName = $pan_config['PAN_CODE_PATH']."/".$pan_config['SIGNATURE_FOLDER']."/".$file;
            $panUser = $pan_config['NSDL_USER_ID'];
            $panPassword = $pan_config['PFX_PSWD'];

            $finalArray = array();
            $output = shell_exec("sh ".$_SERVER["DOCUMENT_ROOT"]."/scripts/pan_verification.sh ".$pan_config['PAN_CODE_PATH']." ".$panPassword." ".$panUser."^".$panNoStr." ".$fileName." ".$file);
            $output = trim($output);
            $panArray = explode("^", $output);
            if ($panArray[0] == 1) {
                $newArr = array_slice($panArray, 1);
                $recordsCnt = count($newArr)/$pan_config['DATA_LIMIT'];
                for ($i = 0; $i < $recordsCnt; $i++) {
                    $temp = array();
                    $j = $i * 10;
                    $temp['pan_number'] = $newArr[$j];
                    $temp['status_code'] = $newArr[$j+1];
                    $temp['status'] = $pan_statuses[$newArr[$j+1]];
                    $temp['last_name'] = $newArr[$j+2];
                    $temp['first_name'] = $newArr[$j+3];
                    $temp['middle_name'] = $newArr[$j+4];
                    $temp['pan_title'] = $newArr[$j+5];
                    $temp['last_update_date'] = $newArr[$j+6];
                //    $temp['filler1'] = $newArr[$j+7];
                //    $temp['filler2'] = $newArr[$j+8];
                //    $temp['filler3'] = $newArr[$j+9];
                    $finalArray[$temp['pan_number']] = $temp;
                }
                $response = array('status' => 'success', 'data' => $finalArray);
            } else {
                $response = array('status' => 'failure', 'description' => $pan_errors[$panArray[0]]);
            }
            @unlink($fileName);
            return $response;
        }
        return array('status'=>'failure', 'description'=>'PAN required');
    }

    function getPlans($service_id = ''){
        $response = array($service_id => array());
        $plans = $this->Serviceintegration->getServicePlans();
        $plans = json_decode($plans,true);
        if( count($plans) > 0 ){
            $product_plans = $this->Serviceintegration->getServiceProductPlans();
            $product_plans = json_decode($product_plans,true);

            foreach ($plans as $ser_id => $plan) {
                if( $service_id == $ser_id ){
                    foreach ( $plan as $plan_key => $plan_details ) {
                        if( array_key_exists($plan_details['id'],$product_plans) ){
                            $plan[$plan_key]['products'] = $product_plans[$plan_details['id']];
                        }
                    }
                    foreach ($plan as $key => $value) {
                        $response[$ser_id][] = $value;
                    }
                }
            }

        }
        return $response[$service_id];
    }
    function purchaseKit($service_id = '',$plan_id = '',$user_id = '',$settle_flag = 1){
        $plans = $this->Serviceintegration->getServicePlans();
        $plans = json_decode($plans,true);
        $selected_plan = array();
        if( array_key_exists($service_id,$plans) ){
            foreach ($plans[$service_id] as $plan_key => $plan) {
                if( $plan_id == $plan['id'] ){
                    $selected_plan = $plan;
                    break;
                }
            }
            if( count($selected_plan) > 0 ){
                $services = $this->Serviceintegration->getServiceDetails();
                $services = json_decode($services,true);
                $plan_amount = $selected_plan['setup_amt'];
                $description = 'Activated '.$selected_plan['plan_name'].' plan against '.$services[$service_id]['name'].' service for retailer -'.$user_id;

                $Object = ClassRegistry::init('User');
                $dataSource = $Object->getDataSource();
                $dataSource->begin();
                $wallet_res = $this->Bridge->kitCharge($plan_amount,$user_id,$service_id,$description,$dataSource,null,$settle_flag);
                if( $wallet_res['status'] == 'success' ){

                     $service_request_data = array(
                        'kit_purchase_date' => date("Y-m-d"),
                        'kit_purchase_timestamp' => date("Y-m-d h:i:s"),
                        'ret_user_id' => $user_id,
                        'source' => 'kit_purchase_api',
                        'service_id' => $service_id
                    );
                    $service_request_res = $this->Servicemanagement->addServiceRequestLog($service_request_data,$dataSource);

                    if($service_request_res){


                        $retailer_info = $this->Servicemanagement->getRetailerInfoByUserId($user_id);
                        if( count($retailer_info) > 0 ){
                            $dist_id = $retailer_info[0]['d']['id'];
                            $dist_user_id = $retailer_info[0]['d']['user_id'];
                        }

                        // Entry in kit_delivery_log on kit purchase via retailer wallet|instamojo if delivery_flag is true
                        if( $selected_plan['delivery_flag'] ){
                            $kit_delivery_data = array(
                                'ret_user_id' => $user_id,
                                'dist_user_id' => $dist_user_id,
                                'group_id' => RETAILER,
                                'source' => 'kit_purchase_api',
                                'service_id' => $service_id,
                                'service_plan_id' => $selected_plan['id'],
                                'kits' => 1,
                                'purchased_date' => date("Y-m-d"),
                                'purchased_timestamp' => date("Y-m-d h:i:s")
                            );
                            $kit_delivery_res = $this->Servicemanagement->addKitDeliveryLog($kit_delivery_data,$dataSource);
                            if(!$kit_delivery_res){
                                $dataSource->rollback();
                                return array(
                                    'status' => 'failure',
                                    'description' => 'Couldn\'t purchase the kit.Something went wrong.'
                                );
                            }
                        } else {
                            $description = 'Commission for kit purchase '.$selected_plan['plan_name'].' against '.$services[$service_id]['name'].' service for retailer -'.$user_id;
                            $wallet_dist_res = $this->Servicemanagement->distCommission($selected_plan['dist_commission'],$dist_id,$dist_user_id,$service_id,$description,$dataSource,null);
                            if( $wallet_dist_res['status'] == 'failure' ){
                                $dataSource->rollback();
                                return array(
                                    'status' => 'failure',
                                    'description' => 'Couldn\'t purchase the kit.Something went wrong.'
                                );
                            }
                        }

                    } else {
                        $dataSource->rollback();
                        return array(
                            'status' => 'failure',
                            'description' => 'Couldn\'t purchase the kit.Something went wrong.'
                        );
                    }

                    $params = array(
                        'plan' => $selected_plan['plan_key'],
                        'payment_mode' => 2
                    );
                    $res = $this->activateKit($user_id,$service_id,$selected_plan['id'],json_encode($params),$dataSource);
                    if( $res['status'] == 'success' ){
                        $dataSource->commit();
                        return array(
                            'status' => 'success',
                            'description' => 'Kit purchased successfully'
                        );
                    } else {
                        $dataSource->rollback();
                        return array(
                            'status' => 'failure',
                            'description' => 'Couldn\'t purchase the kit.'. $res['description']
                        );
                    }
                } else {
                    $dataSource->rollback();
                    return array(
                        'status' => 'failure',
                        'description' => 'Couldn\'t purchase the kit.'. $wallet_res['description']
                    );
                }
            } else {
                return array('status'=>'failure', 'description'=>'Invalid Plan');
            }


        } else {
            return array('status'=>'failure', 'description'=>'Invalid Service');
        }

    }
    function activateKit($user_id,$service_id,$service_plan_id,$data,$dataSource){
        $services = $dataSource->query("SELECT * FROM users_services WHERE user_id = '$user_id' AND service_id = '$service_id'");
        if(empty($services)){
            $temp = $dataSource->query("INSERT INTO users_services (user_id,service_id,service_plan_id,params,kit_flag,service_flag,created_on) VALUES ('$user_id','$service_id','$service_plan_id',".json_encode($data).",1,3,'".date('Y-m-d H:i:s')."')");
        } else {

            if( $services[0]['users_services']['kit_flag'] != 1 ){
                $temp = $dataSource->query('UPDATE users_services'
                        . ' SET kit_flag = 1,'
                        . ' service_flag= 3,'
                        . ' service_plan_id= '.$service_plan_id.','
                        . ' params=\''.$data.'\''
                        . ' WHERE user_id='.$user_id.''
                        . ' AND service_id='.$service_id.''
                        . ' AND kit_flag != 1');
            } else {
                return array('status'=>'failure','description' => 'Kit already active for this user');
            }
        }
        if( $temp ){
            return array('status'=>'success');
        } else {
            return array('status'=>'failure','description' => 'Something went wrong. Please try again');
        }

    }
    function upgradeKit($user_id,$service_id,$selected_plan,$dataSource){
        $services = $dataSource->query("SELECT * FROM users_services WHERE user_id = '$user_id' AND service_id = '$service_id'");
        if(empty($services)){
            return array('status'=>'failure','description' => 'No kit entry found');
        } else {
            $params = json_decode($services[0]['users_services']['params'],true);
            $params['plan'] = $selected_plan['plan_key'];
            $params = json_encode($params);

            if( $services[0]['users_services']['kit_flag'] == 1 ){

                $temp = $dataSource->query('UPDATE users_services'
                        . ' SET service_plan_id= '.$selected_plan['id'].','
                        . ' params=\''.$params.'\''
                        . ' WHERE user_id='.$user_id.''
                        . ' AND service_id='.$service_id.''
                        . ' AND kit_flag = 1');
            } else {
                return array('status'=>'failure','description' => 'Kit not present for this user');
            }
        }
        if( $temp ){
            return array('status'=>'success');
        } else {
            return array('status'=>'failure','description' => 'Something went wrong. Please try again');
        }

    }
    function upgradePlan($service_id = '',$plan_id = '',$user_id = '',$settle_flag = 1){
        $plans = $this->Serviceintegration->getServicePlans();
        $plans = json_decode($plans,true);
        $selected_plan = array();
        if( array_key_exists($service_id,$plans) ){
            foreach ($plans[$service_id] as $plan_key => $plan) {
                if( $plan_id == $plan['id'] ){
                    $selected_plan = $plan;
                    break;
                }
            }
            if( count($selected_plan) > 0 ){
                $services = $this->Serviceintegration->getServiceDetails();
                $services = json_decode($services,true);

                $plan_amount = $selected_plan['setup_amt'];
                $user_service = $this->Servicemanagement->getUserServices($user_id,$service_id);
                $previous_params = json_decode($user_service[$service_id]['params'],true);
                if( isset($previous_params['plan']) && !empty($previous_params['plan']) ){
                    $plan_amount = $plan_amount - $plans[$service_id][$previous_params['plan']]['setup_amt'];
                }

                $description = 'Activated '.$selected_plan['plan_name'].' plan against '.$services[$service_id]['name'].' service for retailer -'.$user_id;

                $Object = ClassRegistry::init('User');
                $dataSource = $Object->getDataSource();
                $dataSource->begin();
                $wallet_res = $this->Bridge->kitCharge($plan_amount,$user_id,$service_id,$description,$dataSource,null,$settle_flag);
                if( $wallet_res['status'] == 'success' ){


                    //  $service_request_data = array(
                    //     'kit_purchase_date' => date("Y-m-d"),
                    //     'kit_purchase_timestamp' => date("Y-m-d h:i:s"),
                    //     'ret_user_id' => $user_id,
                    //     'kit_purchase_date' => date('Y-m-d'),
                    //     'source' => 'kit_purchase_api',
                    //     'service_id' => $service_id
                    // );
                    // $service_request_res = $this->Servicemanagement->addServiceRequestLog($service_request_data,$dataSource);

                    // if($service_request_res){
                    //     // Entry in kit_delivery_log on kit purchase via retailer wallet|instamojo if delivery_flag is true
                    //     if( $selected_plan['delivery_flag'] ){
                    //         $kit_delivery_data = array(
                    //             'ret_user_id' => $user_id,
                    //             'dist_user_id' => $dist_id,
                    //             'group_id' => RETAILER,
                    //             'source' => 'kit_purchase_api',
                    //             'service_id' => $service_id,
                    //             'service_plan_id' => $selected_plan['id'],
                    //             'kits' => 1,
                    //             'purchased_date' => date("Y-m-d"),
                    //             'purchased_timestamp' => date("Y-m-d h:i:s")
                    //         );
                    //         $kit_delivery_res = $this->Servicemanagement->addKitDeliveryLog($kit_delivery_data,$dataSource);
                    //         if(!$kit_delivery_res){
                    //             $dataSource->rollback();
                    //             return array(
                    //                 'status' => 'failure',
                    //                 'description' => 'Couldn\'t purchase the kit.Something went wrong.'
                    //             );
                    //         }
                    //     }

                    // } else {
                    //     $dataSource->rollback();
                    //     return array(
                    //         'status' => 'failure',
                    //         'description' => 'Couldn\'t purchase the kit.Something went wrong.'
                    //     );
                    // }

                    $retailer_info = $this->Servicemanagement->getRetailerInfoByUserId($user_id);
                    if( count($retailer_info) > 0 ){
                        $dist_id = $retailer_info[0]['d']['id'];
                        $dist_user_id = $retailer_info[0]['d']['user_id'];
                    }

                    $description = 'Commission for plan upgrade from  '.$plans[$service_id][$previous_params['plan']]['plan_name'].' to '.$selected_plan['plan_name'].' against '.$services[$service_id]['name'].' service for retailer -'.$user_id;
                    $wallet_dist_res = $this->Servicemanagement->distCommission($selected_plan['dist_commission']-$plans[$service_id][$previous_params['plan']]['dist_commission'],$dist_id,$dist_user_id,$service_id,$description,$dataSource,null);
                    if( $wallet_dist_res['status'] == 'failure' ){
                        $dataSource->rollback();
                        return array(
                            'status' => 'failure',
                            'description' => 'Couldn\'t purchase the kit.Something went wrong.'
                        );
                    }


                    // $params = array(
                    //     'plan' => $selected_plan['plan_key'],
                    //     'payment_mode' => 2
                    // );
                    $res = $this->upgradeKit($user_id,$service_id,$selected_plan,$dataSource);
                    if( $res['status'] == 'success' ){
                        $dataSource->commit();
                        return array(
                            'status' => 'success',
                            'description' => 'Plan upgraded successfully'
                        );
                    }
                } else {
                    $dataSource->rollback();
                    return array(
                        'status' => 'failure',
                        'description' => 'Couldn\'t purchase the kit.'. $wallet_res['description']
                    );
                }
            } else {
                return array('status'=>'failure', 'description'=>'Invalid Plan');
            }


        } else {
            return array('status'=>'failure', 'description'=>'Invalid Service');
        }

    }
    function requestService($service_id = '',$user_id = ''){

        $services = $this->Serviceintegration->getServiceDetails();
        $services = json_decode($services,true);

        if( $services[$service_id]['activation_type'] == 1 ){ // if activation_type is manual

            $Object = ClassRegistry::init('User');
            $dataSource = $Object->getDataSource();
            $dataSource->begin();

            $user_service = $this->Servicemanagement->getUserServices($user_id,$service_id);

            if( count($user_service) > 0 ){ // update users_services with service_flag = 4 if service_flag = 3/5

                $service_flag = 4;
                $temp = $dataSource->query('UPDATE users_services'
                    . ' SET service_flag='.$service_flag.''
                    . ' WHERE user_id='.$user_id.''
                    . ' AND service_id='.$service_id.''
                    . ' AND service_flag IN(3,5)'
                    );

            } else { // insert in users_services with service_flag = 4

                $data['kit_flag'] = 1;
                $data['service_flag'] = 4;
                $temp = $this->Servicemanagement->addUserService($user_id,$service_id,$data,$dataSource,0);

            }

            if($temp){
                $check_service_request = $this->Servicemanagement->checkServiceRequestLog($user_id,$service_id);

                if( count($check_service_request) > 0  ){

                    $service_request_data = array(
                        'service_request_date' => date('Y-m-d'),
                        'service_request_timestamp' => date('Y-m-d H:i:s'),
                        'source' => 'service_request_api',
                    );
                    $service_request_res = $this->Servicemanagement->updateServiceRequestLog($user_id,$service_id,$service_request_data,$dataSource);

                } else {
                    $service_request_data = array(
                        'service_request_date' => date('Y-m-d'),
                        'service_request_timestamp' => date('Y-m-d H:i:s'),
                        'ret_user_id' => $user_id,
                        'source' => 'service_request_api',
                        'service_id' => $service_id
                    );
                    $service_request_res = $this->Servicemanagement->addServiceRequestLog($service_request_data,$dataSource);
                }

                if($service_request_res){
                    $dataSource->commit();
                    return array(
                        'status' => 'success',
                        'description' => 'Service request created successfully'
                    );
                }
            }
            $dataSource->rollback();
            return array('status'=>'failure', 'description'=>'Something went wrong. Please try again');

        }
        return array('status'=>'failure', 'description'=>'This service is not eligible for this action');
    }

    function accountHistory ($user_id, $from_date, $to_date) {

            $dbObj = ClassRegistry::init('Slaves');

            $groups = $dbObj->query("SELECT user_groups.user_id, user_groups.group_id, distributors.id, distributors.mobile, retailers.id FROM user_groups LEFT JOIN distributors ON (user_groups.user_id = distributors.user_id) LEFT JOIN retailers ON (user_groups.user_id = retailers.user_id) WHERE user_groups.user_id = '$user_id'");

                $query = "(";

                foreach($groups as $group) {

                        if($group['user_groups']['group_id'] == DISTRIBUTOR) {

                                $distributor_id = $group['distributors']['id'];

                                $query = $query . "(SELECT shop_transactions.* FROM shop_transactions INNER JOIN distributors ON (distributors.id = shop_transactions.target_id) WHERE target_id = ".$distributor_id. " AND type = " . SDIST_DIST_BALANCE_TRANSFER . ")
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$distributor_id." AND type = " . COMMISSION_DISTRIBUTOR . ")
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$distributor_id." AND type = " . COMMISSION_DISTRIBUTOR_REVERSE. ")
                                            UNION
                                            (SELECT st.* FROM shop_transactions st JOIN salesmen ON (st.target_id = salesmen.id AND salesmen.mobile != ".$group['distributors']['mobile'].") WHERE st.source_id = ".$distributor_id." AND st.type = ".DIST_SLMN_BALANCE_TRANSFER." AND st.type_flag != 5)
                                            UNION
                                            (SELECT st.* FROM shop_transactions st JOIN salesmen ON (salesmen.id = st.source_id AND salesmen.dist_id = ".$distributor_id.") WHERE st.type = ".SLMN_RETL_BALANCE_TRANSFER." AND st.user_id != 0 AND st.type_flag != 5)
                                            UNION
                                            (SELECT st.* FROM shop_transactions st JOIN salesmen ON (st.source_id = salesmen.id) WHERE salesmen.dist_id = ".$distributor_id." AND st.type = ".PULLBACK_SALESMAN." AND st.user_id IS NOT NULL)
                                            UNION
                                            (SELECT st.* FROM shop_transactions st JOIN salesmen ON (st.source_id = salesmen.id) WHERE salesmen.dist_id = ".$distributor_id." AND st.type = ".PULLBACK_SALESMAN." AND (st.user_id IS NULL OR st.user_id = 0))
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$distributor_id. " AND type = " . PULLBACK_DISTRIBUTOR . ")
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$user_id. " AND type = " . REFUND . ")
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$distributor_id. " AND user_id = ".DISTRIBUTOR." AND type = " . SERVICE_CHARGE . ")
                                            UNION ";

                        } else if($group['user_groups']['group_id'] == RETAILER) {

                                $retailer_id = $group['retailers']['id'];

                                $query = $query . "(SELECT shop_transactions.* FROM shop_transactions WHERE target_id = ".$retailer_id." AND type = " . DIST_RETL_BALANCE_TRANSFER . " AND note is not null AND (confirm_flag != 1 OR type_flag != 5))
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$retailer_id." AND type = " . RETAILER_ACTIVATION . ")
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$retailer_id." AND type = " . COMMISSION_RETAILER . ")
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$retailer_id." AND type = " . PULLBACK_RETAILER . ")
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$user_id. " AND type = " . REFUND . ")
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$retailer_id. " AND user_id = ".RETAILER." AND type = " . SERVICE_CHARGE . ")
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$user_id. " AND type = " . RENTAL . ")
                                            UNION
                                            (SELECT shop_transactions.* FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.source_id = ".$retailer_id . " AND shop_transactions.type = ".REVERSAL_RETAILER.")
                                            UNION ";

                        }
                }

                $query = $query . "(SELECT shop_transactions.* FROM shop_transactions WHERE type_flag in (0,2) AND source_id = ".$user_id." AND type in (" . DEBIT_NOTE . ",".CREDIT_NOTE.",".COMMISSION.",".SERVICECHARGES.",".SERVICE_TAX.",".TDS.",".VOID_TXN.",".SECURITY_DEPOSIT.",".ONE_TIME_CHARGE."))
                                UNION
                                (SELECT shop_transactions.* FROM shop_transactions WHERE type_flag = 0 AND (source_id = ".$user_id." OR target_id = ".$user_id.") AND type in (" . WALLET_TRANSFER . "))
                                UNION
                                (SELECT shop_transactions.* FROM shop_transactions WHERE type_flag = 0 AND (source_id = ".$user_id." OR user_id = ".$user_id.") AND type in (" . WALLET_TRANSFER_REVERSED . "))
                                UNION
                                (SELECT shop_transactions.* FROM shop_transactions WHERE source_id = ".$user_id." AND type in (" . TXN_REVERSE. ",".TXN_CANCEL_REFUND."))
                                UNION
                                (SELECT shop_transactions.* FROM shop_transactions WHERE type_flag = 0 AND source_id = ".$user_id." AND type = " . KITCHARGE . "))";

                $txns = $dbObj->query("SELECT st.* FROM $query st WHERE st.date >= '$from_date' AND st.date <= '$to_date' ORDER BY st.id DESC");

                $data = array();
                $services_all = $this->Shop->getServices();
                $data_last = array();
                if(!empty($txns)) {
                        foreach($txns as $txn) {

                            if(in_array($txn['st']['type'],array(1))){
                                $opening = $txn['st']['target_opening'];
                                $closing = $txn['st']['target_closing'];
                                $credit = $txn['st']['amount'];
                                $debit = 0;
                                $note = "Topup Transferred by pay1";
                            }
                            else if(in_array($txn['st']['type'],array(2,25,26))){
                                $opening = (!empty($distributor_id)) ? $txn['st']['source_opening'] : $txn['st']['target_opening'];
                                $closing = (!empty($distributor_id)) ? $txn['st']['source_closing'] : $txn['st']['target_closing'];

                                $credit = (!empty($distributor_id)) ? 0 : $txn['st']['amount'];
                                $debit = (!empty($distributor_id)) ? $txn['st']['amount']: 0;
                                $note = (!empty($distributor_id)) ? "Topup Transferred" : "Topup received";
                            }
                            else if(in_array($txn['st']['type'],array(21,27))){
                                $opening = (!empty($distributor_id)) ? $txn['st']['target_opening'] : $txn['st']['source_opening'];
                                $closing = (!empty($distributor_id)) ? $txn['st']['target_closing'] : $txn['st']['source_closing'];

                                $credit = (!empty($distributor_id)) ? $txn['st']['amount']: 0;
                                $debit = (!empty($distributor_id)) ? 0: $txn['st']['amount'];
                                $note = "Topup pulled back";
                            }
                            else if(in_array($txn['st']['type'],array(4,9,10,22,24))){
                                $opening = $txn['st']['source_opening'];
                                $closing = $txn['st']['source_closing'];
                                $credit = 0;
                                $debit = $txn['st']['amount'];
                                $note = ($txn['st']['type'] == 4) ? "Recharge/Bill Payment" : (($txn['st']['type'] == 22) ? "Topup pulled back" : (($txn['st']['type'] == 24) ? "Service Charge" : "TDS"));
                            }
                            else if(in_array($txn['st']['type'],array(6,7,11,12))){
                                $opening = $txn['st']['source_opening'];
                                $closing = $txn['st']['source_closing'];
                                $credit = $txn['st']['amount'];
                                $debit = 0;
                                $note = ($txn['st']['type'] == 6 || $txn['st']['type'] == 7) ? "Commission" : "Txn Reversed";
                            }
                            else {
                                $opening = $txn['st']['source_opening'];
                                $closing = $txn['st']['source_closing'];
                                $credit = ($txn['st']['source_opening'] > $txn['st']['source_closing']) ? 0 : $txn['st']['amount'];
                                $debit = ($txn['st']['source_opening'] < $txn['st']['source_closing']) ? 0 : $txn['st']['amount'];
                                $note = $txn['st']['note'];
                            }
                            $service = (in_array($txn['st']['type'],array(4,7,11,24))) ? 'Recharge' : 'All';

                            if($service == 'All'){
                                if(in_array($txn['st']['type'],array(16,17,19,20)) || $txn['st']['type'] >= 28){
                                    $service_a = $txn['st']['user_id'];
                                    if(!empty($service_a)){
                                        $service = $services_all[$service_a];
                                    }
                                }
                                else if(in_array($txn['st']['type'],array(1,2,12,21,22,25,26,27))){
                                    $service='Topup';
                                }
                            }

                            $txn_temp = array('description'=>$note,'timestamp'=>$txn['st']['timestamp'],'opening'=>$opening,'closing'=>$closing,'credit'=>$credit,'debit'=>$debit);
                            $st_txns[] = $txn['st']['id'];
                            $st_txns_targets[] = $txn['st']['target_id'];

                            //$service = 'recharge';
                            $txn_temp['service'] = $service;
                            $txn_temp['order_id'] = '';
                            $txn_temp['shop_transaction_id'] = $txn['st']['id'];
                            $data_last[$txn['st']['id']] = $txn_temp;
                        }
                }
                $txn_ids = array_merge($st_txns,$st_txns_targets);
                $txns1 = $dbObj->query("SELECT txn_id,shop_transaction_id,server FROM wallets_transactions wt WHERE shop_transaction_id IN (".implode(",",$txn_ids).") ORDER BY shop_transaction_id asc");
                if(!empty($txns1)) {
                    foreach($txns1 as $txn) {
                        $service = $txn['wt']['txn_id'] != NULL ? $txn['wt']['server'] : 'recharge';
                        $service = $service == 'dmt' ? 'Remit' : ucfirst($service);

                        $shop_txn_id = $txn['wt']['shop_transaction_id'];
                        $txn_temp = $data_last[$shop_txn_id];
                        $txn_temp['order_id'] = $txn['wt']['txn_id'];
                        $txn_temp['shop_transaction_id'] = $shop_txn_id;
                        //$txn_temp['service'] = $service;
                        $data_last[$shop_txn_id] = $txn_temp;
                    }
                }

                if(!empty($data_last)) {
                    foreach($data_last as $txn_temp) {
                        $service = $txn_temp['service'];
                        unset($txn_temp['service']);
                        $data['data']['All'][]    = $txn_temp;
                        if($service != 'All'){
                            $data['data'][$service][] = $txn_temp;
                        }

                    }
                }

                return $data;
    }

}
