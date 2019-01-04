<?php

class RelationshipmanagerComponent extends Object{
    var $components = array('General', 'Shop', 'Auth');
    var $uses = array('DocManagement');

    function errorDescription($errCode){
        $errors = array('202'=>'Update App Version','101'=>'OTP sent successfully', '102'=>'Token is missing', '103'=>'Token is not authentic', '104'=>'Method does not exists', '105'=>'Not enough balance', '106'=>'Some mysql technical problem', '107'=>'Duplicate Txn Id', '108'=>'Wrong input parameters', 
                '109'=>'Txn not found', '110'=>'Txn already reversed', '111'=>'User does not exists', '112'=>'Txn found but amount not settled', '113'=>'No data found','114'=>'User is not active','115'=>'Mobile number is not valid','116'=>'is not valid','117'=>'User is not valid',
                '118'=>'App Request not authentic','119'=>'Input Parameters are coming wrong','120'=>'Your Mobile number or uuid should not blank',
                '121'=>'App version code is not valid','404'=>'Session does not exists','122'=>'You are not allowed to login here','123'=>'New pwd cannot be same as old pwd','124'=>'Pwd does not match','125'=>'Kindly create a strong password','126'=>'Other RM is using this device','131'=>'You have already checked in','132'=>'You have already checked out','133'=>'Ask for reason to checkin/checkout','134'=>'Tracking data inserted successfully','135'=>'RM has logged in from another device. Stop tracking this device.','136'=>'Login log inserted successfully','137'=>'Reason coming wrong','138'=>'Stop tracking for the day','145'=>'URL not valid');
        return $errors[$errCode];
    }

    function requestValidation($data){
        $data1 = $this->dataDecryption($data['req']);
        //print_r($data1)."===";exit;
        $json = json_decode($data1,true);
        //print_r($json);exit;
        if(!isset($json['method'])) {
            return array('status'=>'failure', 'code'=>'145', 'description'=>$this->errorDescription(145));
        }
        
        //if(isset($data['document']))$json['document'] = $data['document'];
        
        $this->logApiRequest($json);
        
      $aclCheck = $this->aclCheck($json);
        if($aclCheck['status'] == 'success'){
            $json['session_data'] = isset($aclCheck['session_data'])?$aclCheck['session_data']:'';
        }
        else{
            return $aclCheck;
        }
        
        $method = $json['method']."Validation";
        Configure::load('relationship_manager');
        $app_names = Configure::read('app_names');
        $api_param_counts = Configure::read('api_param_counts');
        //echo $api_param_counts[$method]."===".count($json)."===".$api_param_counts[$method];exit;
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
        elseif(! isset($params['longitude']) || ! isset($params['latitude'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are missing');
        elseif(!$this->floatValidation($params['longitude']) || !$this->floatValidation($params['latitude'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are not coming right');
        endif;
        
        return array('status'=>'success');
    }

    function resendOTPAuthenticateValidation($params){
        
        if( ! isset($params['mobile']) || empty($params['mobile']) ||  ! isset($params['rm_user_id']) || empty($params['rm_user_id'])):
        return array('status'=>'failure', 'code'=>'120', 'description'=>$this->errorDescription(120));
        elseif($this->General->mobileValidate($params['mobile']) == '1'): // mobile no validation
        return array('status'=>'failure', 'code'=>'115', 'description'=>$this->errorDescription(115));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
        return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        endif;
        
        return array('status'=>'success');
    }
    
    function verifyOTPAuthenticateValidation($params){
        
        if( ! isset($params['mobile']) || empty($params['mobile']) ||  ! isset($params['rm_user_id']) || empty($params['rm_user_id']) || !isset($params['uuid']) || empty($params['uuid'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif($this->General->mobileValidate($params['mobile']) == '1'): // mobile no validation
            return array('status'=>'failure', 'code'=>'115', 'description'=>$this->errorDescription(115));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(! isset($params['longitude']) || ! isset($params['latitude'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are missing');
        elseif(!$this->floatValidation($params['longitude']) || !$this->floatValidation($params['latitude'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are not coming right');
        elseif(!ctype_alnum(trim($params['uuid']))):
            return array('status' => 'failure','code'=>'116','description' =>'UUID '.$this->errorDescription(116));
        endif;
        
        return array('status'=>'success');
    }

    function showCheckinOrCheckoutValidation($params){
        
        if(! isset($params['rm_user_id']) || empty($params['rm_user_id'])):
            return array('status'=>'failure', 'code'=>'120', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        endif;
        
        return array('status'=>'success');
    }

    function markCheckInValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id']) || !isset($params['uuid']) || empty($params['uuid']) ):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['uuid']))):
            return array('status' => 'failure','code'=>'116','description' =>'UUID '.$this->errorDescription(116));
        elseif(! isset($params['longitude']) || ! isset($params['latitude'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are missing');
        elseif(!$this->floatValidation($params['longitude']) || !$this->floatValidation($params['latitude'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are not coming right');
        elseif(!empty($params['reason']) && !ctype_alnum(trim($params['reason']))):
            return array('status' => 'failure','code'=>'137','description' =>$this->errorDescription(137));
        elseif(!empty($params['reason_type']) && !ctype_alnum(trim($params['reason_type']))):
            return array('status' => 'failure','code'=>'137','description' =>$this->errorDescription(137));

        endif;
        
        return array('status'=>'success');
    }

    function markCheckOutValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id']) || !isset($params['uuid']) || empty($params['uuid']) ):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['attendance_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'Attendance id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['uuid']))):
            return array('status' => 'failure','code'=>'116','description' =>'UUID '.$this->errorDescription(116));
        elseif(! isset($params['longitude']) || ! isset($params['latitude'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are missing');
        elseif(!$this->floatValidation($params['longitude']) || !$this->floatValidation($params['latitude'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are not coming right');

        endif;

        if(!empty($params['tracking_data'])){
            return $this->trackingValidation($params['tracking_data']);
        }
        
        return array('status'=>'success');
    }

    function logoutValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id']) || !isset($params['uuid']) || empty($params['uuid']) ):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['login_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'Login id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['uuid']))):
            return array('status' => 'failure','code'=>'116','description' =>'UUID '.$this->errorDescription(116));

        endif;

        if(!empty($params['tracking_data'])){
            return $this->trackingValidation($params['tracking_data']);
        }
        
        return array('status'=>'success');
    }

    function masterDistributorValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id']) ):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function insertTrackingLogValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id']) || !isset($params['uuid']) || empty($params['uuid']) ):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['uuid']))):
            return array('status' => 'failure','code'=>'116','description' =>'UUID '.$this->errorDescription(116));

        endif;
        
        if(!empty($params['tracking_data'])){
            return $this->trackingValidation($params['tracking_data']);
        }

        return array('status'=>'success');
    }

    function createRetDistNewLeadsValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id']) || !isset($params['mobile']) || empty($params['mobile']) || !isset($params['uuid']) || empty($params['uuid'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif($this->General->mobileValidate($params['mobile']) == '1'): // mobile no validation
            return array('status'=>'failure', 'code'=>'115', 'description'=>$this->errorDescription(115));
        elseif(!is_numeric(trim($params['pin_code']))):
            return array('status' => 'failure','code'=>'116','description' =>'Pin code '.$this->errorDescription(116));
        elseif($params['interest'] != "Distributor"):
            return array('status' => 'failure','code'=>'116','description' =>'Interest '.$this->errorDescription(116));
        elseif($params['lead_source'] != "rm_app"):
            return array('status' => 'failure','code'=>'116','description' =>'Lead source '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['uuid']))):
            return array('status' => 'failure','code'=>'116','description' =>'UUID '.$this->errorDescription(116));
        elseif(!is_numeric(trim($params['status']))):
            return array('status' => 'failure','code'=>'116','description' =>'Status '.$this->errorDescription(116));
        endif;
        
        return array('status'=>'success');
    }

    function masterRetailerValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id']) || !isset($params['dist_id']) || empty($params['dist_id']) ):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['dist_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'Distributor id '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function masterStatusValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function myAllLeadValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function updateFollowUpLeadValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id']) || !isset($params['id']) || empty($params['id']) ):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['id']))):
            return array('status' => 'failure','code'=>'116','description' =>'Id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['status']))):
            return array('status' => 'failure','code'=>'116','description' =>'Status Id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['converted_dist_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'Converted Distributor Id '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function feedbackValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function retailerDistributorVisitValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!is_numeric(trim($params['parent_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'Parent id '.$this->errorDescription(116));
        elseif(!is_numeric(trim($params['type']))):
            return array('status' => 'failure','code'=>'116','description' =>'Type '.$this->errorDescription(116));
        elseif(!is_numeric(trim($params['document_pick_up']))):
            return array('status' => 'failure','code'=>'116','description' =>'Document pick up '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function dailyReportValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function getChildRMValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id']) || !isset($params['parent_rm_id']) || empty($params['parent_rm_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['parent_rm_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'Parent User id '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function updateRMVisitValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id']) || !isset($params['attendance_id']) || empty($params['attendance_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['attendance_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'Attendance id '.$this->errorDescription(116));
        elseif(!ctype_alnum(trim($params['rm_visit_child_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'RM child id '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function dashboardValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif(!is_numeric(trim($params['report_type']))):
            return array('status' => 'failure','code'=>'116','description' =>'Report type '.$this->errorDescription(116));
        elseif(!is_numeric(trim($params['report_month']))):
            return array('status' => 'failure','code'=>'116','description' =>'Report month '.$this->errorDescription(116));
        elseif(!is_numeric(trim($params['report_year']))):
            return array('status' => 'failure','code'=>'116','description' =>'Report year '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }


    function dashboardDistributorValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif( !isset($params['dist_id']) || empty($params['dist_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['dist_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'Distributor id '.$this->errorDescription(116));

        endif;
        
        return array('status'=>'success');
    }

    function dashboardRetailerValidation($params){
        
        if( !isset($params['rm_user_id']) || empty($params['rm_user_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['rm_user_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'User id '.$this->errorDescription(116));
        elseif( !isset($params['ret_id']) || empty($params['ret_id'])):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->errorDescription(120));
        elseif(!ctype_alnum(trim($params['ret_id']))):
            return array('status' => 'failure','code'=>'116','description' =>'Retailer id '.$this->errorDescription(116));

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
        Configure::load('relationship_manager');
        
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
            
            if($user_id != $data['rm_user_id']) return array('status'=>'failure','code'=>'403','description'=>$this->errorDescription(117));
            
            Configure::load('relationship_manager');
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
            return array('status'=>'failure','code'=>'404','description'=>$this->errorDescription(404));
        }
       
        return array('status'=>'failure','code'=>'103','description'=>$this->errorDescription(103));
    }
    

    function logApiRequest($data){
        $client_ip = $this->General->getClientIP();
        if(isset($data['password'])) { $data['password'] = 'xxxx'; }
        
        $transObj = ClassRegistry::init('RelationshipManager');
        $this->data['RelationshipManager']['method'] = $data['method'];
        $this->data['RelationshipManager']['params'] = json_encode($data);
        $this->data['RelationshipManager']['rm_user_id'] = $data['rm_user_id'];
        $this->data['RelationshipManager']['ip'] = $client_ip;
        $this->data['RelationshipManager']['timestamp'] = date('Y-m-d H:i:s');
        $this->data['RelationshipManager']['date'] = date('Y-m-d');
        $this->data['RelationshipManager']['password'] = $data['password'];
        
        
        $transObj->create();
        if($transObj->save($this->data)){
            $app_log_id = $transObj->getInsertID();
        }
        
        return $app_log_id;
    }
    
    function __checkUserExist($mobile, $password, $groups,$params = null){
        $params['app_name'] = (!isset($params['app_name'])) ? 'rm_app' : $params['app_name'];
        
        $sqlQuery = "SELECT users.*,group_concat(user_groups.group_id) as groupids,rm_device_detail.device_id,rm.name,rm.parent_rm_id
         FROM users 
         INNER JOIN user_groups ON (users.id =user_groups.user_id) 
         INNER JOIN rm ON (users.id =rm.user_id) 
         LEFT JOIN rm_device_detail on (rm_device_detail.rm_user_id  = users.id and rm_device_detail.uuid = '" . $params['uuid'] . "') 
         WHERE 
         users.mobile = '" . $mobile . "' AND users.password = '" . $password . "' 
         AND 
         user_groups.group_id IN (" . implode(",", $groups) . ") AND
         rm.active_flag = 1
         group by rm_device_detail.rm_user_id";
        
        $dbObj = ClassRegistry::init('Slaves'); 
        $data = $dbObj->query($sqlQuery);
        
        if(empty($data)):
            return array('status'=>'failure', 'code'=>'28', 'description'=>$this->Shop->errors(28));
        else:
            return $data[0];
        endif;
    }
    
    
    function checkAppVersion($appVersionCode,$app_name){
        
        Configure::load('relationship_manager');
        $app_versions = Configure::read('app_versions_force_upgrade');
        
        if(isset($app_versions[$app_name]) && $appVersionCode < $app_versions[$app_name]):
            return array("status"=>"failure", "code"=>"202", "description"=>$this->errorDescription(202), "forced_upgrade_flag" => 0);
        endif;
       
        return array("status"=>"success");     
    }

    function chkUniqueDevice($rm_user_id,$params){

        $dbObj = ClassRegistry::init('Slaves');
        $data = $dbObj->query("SELECT rm_user_id,device_id FROM rm_device_detail WHERE uuid='".$params['uuid']."'");

        if(!empty($data)){

            if($data[0]['rm_device_detail']['rm_user_id'] == $rm_user_id){
                /***This is my device**/
                return array("status"=>"success","device_id"=>$data[0]['rm_device_detail']['device_id']);
            }else{
                /***Some other RM is using this device**/
                return array("status"=>"failure", 'code'=>'126', "description"=>$this->errorDescription(126));
            }
            
        }else{
            /***Completely new device**/
            return array("status"=>"success");
        
        }
             
    }

    function showCheckinOrCheckout($rm_user_id){

        $current_date= date('Y-m-d');
        $current_time= date('H:i:s');

        Configure::load('relationship_manager');
        $attendance = Configure::read('attendance');

        $checkin_time = $attendance['checkin_time'];
        $checkout_time = $attendance['checkout_time'];
        $halt_distance = $attendance['halt_distance'];
        
        /***Check if time is not between minimum and maximum threshold time**/
        if($current_time < $checkin_time || $current_time > $checkout_time){
            return array("status"=>"failure", 'code'=>'127','attendance_id'=>'', "description"=>'Disable checkin/checkout','value'=>0,'halt_distance'=>$halt_distance);
        }

        /***Check if RM's todays entry exist or not**/
        $dbObj = ClassRegistry::init('Slaves');
        $data = $dbObj->query("SELECT attendance_id,start_time,end_time FROM rm_attendance WHERE rm_user_id='".$rm_user_id."' and date = '".$current_date."'");
        if(!empty($data)){
            /***If RM's todays entry exist, and if endtime is null, means RM has not marked checkout time, else, RM has checked out for the day.**/
            if($data[0]['rm_attendance']['end_time'] == "" || $data[0]['rm_attendance']['end_time'] == "00:00:00"){
                return array("status"=>"success", 'code'=>'128','attendance_id'=>$data[0]['rm_attendance']['attendance_id'],'start_time'=>$data[0]['rm_attendance']['start_time'], "description"=>'Show checkout','value'=>2,'halt_distance'=>$halt_distance);
            }else{
                return array("status"=>"success", 'code'=>'130','attendance_id'=>'', "description"=>'Attendance marked for today','value'=>3,'halt_distance'=>$halt_distance);
            }            
        }else{
            /***If RM's todays entry does not exist, show RM for checked in for the day.**/
            return array("status"=>"success", 'code'=>'129','attendance_id'=>'', "description"=>'Show checkin','value'=>1,'halt_distance'=>$halt_distance);
        }
             
    }

    function trackingValidation($TrackingData){

        foreach ($TrackingData as $value) {

             $latitude = $value['latitude'];
             $longitude = $value['longitude'];

            if(! isset($longitude) || ! isset($latitude)):
                return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are missing');
            elseif(!$this->floatValidation($longitude) || !$this->floatValidation($latitude)):
                return array('status'=>'failure', 'code'=>'28', 'description'=>'Latitude & Longitude are not coming right');
            endif;

        }

        return array("status"=>"success");
    }

    function insertTrackingAttendanceLog($rm_user_id,$uuid,$TrackingData){
        //print_r($TrackingData);exit;
         //$DecodeTrackingData = json_decode($TrackingData);
         $new_device = 0;
         foreach ($TrackingData as $value) {
             $rm_user_id = $rm_user_id;
             $uuid = $uuid;
             $date = date('Y-m-d');
             $time = $value['time'];
             $latitude = $value['latitude'];
             $longitude = $value['longitude'];
             $duration_spent = $value['duration_spent'];
             $created_date = date('Y-m-d H:i:s');

            /***Check login with new device or not**/
             $dbObj2 = ClassRegistry::init('Slaves');
              $data2 = $dbObj2->query("SELECT device_id,uuid FROM rm_device_detail WHERE rm_user_id = '$rm_user_id' ORDER BY modified_date DESC LIMIT 1");
              if(!empty($data2)){
                    if($uuid != $data2[0]['rm_device_detail']['uuid']){
                        $new_device = 1;
                        $new_device_id = $data2[0]['rm_device_detail']['device_id'];
                     }
                }

                if(!$new_device){
                    /***If not login with new device, store all tracking detail**/
                     $area_id = $this->getAreaId($latitude,$longitude);
                     $dbObj1 = ClassRegistry::init('User');
                     $rm_attendance_log_insert_data = $dbObj1->query("INSERT INTO rm_attendance_log(rm_user_id,uuid,date,time,latitude,longitude,duration_spent,area_id,created_date) VALUES('".$rm_user_id."','".$uuid."','".$date."','".$time."','".$latitude."','".$longitude."','".$duration_spent."','".$area_id."','".$created_date."')");
                 }else{
                    /***If login with new device, store the tracking upto new device login time**/
                    $dbObj3 = ClassRegistry::init('Slaves');
                      $data3 = $dbObj3->query("SELECT login_id,DATE_FORMAT(login_datetime,'%H:%i:%s') as login_time FROM rm_login_log WHERE rm_user_id = '$rm_user_id' and device_id = '$new_device_id' order by created_date DESC LIMIT 1");
                      if(!empty($data3)){
                            if($time < $data3[0]['rm_login_log']['login_time']){
                                $area_id = $this->getAreaId($latitude,$longitude);
                                 $dbObj1 = ClassRegistry::init('User');
                                 $rm_attendance_log_insert_data = $dbObj1->query("INSERT INTO rm_attendance_log(rm_user_id,uuid,`date`,`time`,latitude,longitude,area_id,created_date) VALUES('".$rm_user_id."','".$uuid."','".$date."','".$time."','".$latitude."','".$longitude."','".$area_id."','".$created_date."')");
                             }
                        }
                 }
         }

         $current_time = date('H:i:s');
         Configure::load('relationship_manager');
        $attendance = Configure::read('attendance');
        $checkout_time = $attendance['checkout_time'];

         if($current_time >= $checkout_time){
            return array("status"=>"success", 'code'=>'138', "description"=>$this->errorDescription(138));
         }else{
            if(!$new_device){
                return array("status"=>"success", 'code'=>'134', "description"=>$this->errorDescription(134));
             }else{
                return array("status"=>"success", 'code'=>'135', "description"=>$this->errorDescription(135));
             }
         }
         
    }

    function insertRMLoginLogDetail($rm_user_id, $params){

         $area_id = $this->getAreaId($params['latitude'],$params['longitude']);
         $login_datetime = date('Y-m-d H:i:s');
          $dbObj1 = ClassRegistry::init('User');
          $data = $dbObj1->query("INSERT INTO rm_login_log(rm_user_id,device_id,login_datetime,latitude,longitude,area_id,created_date) VALUES('".$rm_user_id."','".$params['device_id']."','".$login_datetime."','".$params['latitude']."','".$params['longitude']."','".$area_id."','".date('Y-m-d H:i:s')."')");

          /***Update latest login device id with modified time**/
          $update_latest_device = $dbObj1->query("UPDATE rm_device_detail SET modified_date='".date('Y-m-d H:i:s')."' WHERE device_id = '".$params['device_id']."'");

          /***Get last inserted login id**/
          $dbObj = ClassRegistry::init('Slaves');
          $data2 = $dbObj->query("SELECT login_id FROM rm_login_log WHERE login_datetime='".$login_datetime."' and rm_user_id = $rm_user_id");
            if(!empty($data2)){
                $login_id = $data2[0]['rm_login_log']['login_id'];
            }else{
                $login_id = '';
            }

           return array("status"=>"success", 'code'=>'136' , "login_id"=>$login_id, "description"=>$this->errorDescription(136));
    }

    function getAreaId($latitude,$longitude){
        $area_id = 0;
         $dbObj = ClassRegistry::init('Slaves');
        $data = $dbObj->query("SELECT id FROM locator_area WHERE lat = '".$latitude."' and long = '".$longitude."'");
         if(!empty($data)){
            $area_id = $data[0]['locator_area']['id'];
         }
         return $area_id;
    }

    function getdateRange($report_type,$report_month,$report_year){

        $number_of_days_in_month = cal_days_in_month(CAL_GREGORIAN,$report_month,$report_year);

        if($report_type == 1){
            $from_date = $report_year."-".$report_month."-01";
            $to_date = $report_year."-".$report_month."-07";
            $total_days = 7;
        }elseif($report_type == 2){
            $from_date = $report_year."-".$report_month."-08";
            $to_date = $report_year."-".$report_month."-14";
            $total_days = 7;
        }elseif($report_type == 3){
            $from_date = $report_year."-".$report_month."-15";
            $to_date = $report_year."-".$report_month."-21";
            $total_days = 7;
        }elseif($report_type == 4){
            $from_date = $report_year."-".$report_month."-22";
            $to_date = $report_year."-".$report_month."-".$number_of_days_in_month;
            $total_days = ($number_of_days_in_month-22)+1;
        }elseif($report_type == 5){
            $from_date = $report_year."-".$report_month."-01";
            $to_date = $report_year."-".$report_month."-".$number_of_days_in_month;
            $total_days = $number_of_days_in_month;
        }

        $countSunday = $this->countSunday($from_date,$to_date);
        $countHoliday = $this->countHolidayDate($from_date,$to_date,$total_days);
       

        $working_days = $total_days-$countSunday-$countHoliday;
        
        $date_range = array(
                "from_date" => $from_date,
                "to_date" => $to_date,
                "total_days" => $total_days,
                "working_days" => $working_days
            );

        return $date_range;

    }

    function getCurrentWeek(){
        $date = date('j');
        if($date>=1 && $date<=7){
            return 1;
        }elseif($date>=8 && $date<=14){
            return 2;
        }elseif($date>=15 && $date<=21){
            return 3;
        }else{
            return 4;
        }
    }

    function countFutureDate($from_date,$to_date,$total_days) {
        $future_date = 0;
        $current_date = date('Y-m-d');
        
        for($i=0;$i<$total_days;$i++){
            $range_date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));
            if($range_date > $current_date && date('N',strtotime($range_date))!=7 ){
                $future_date++;
            }
        }

        return $future_date;
    }

    function countHolidayDate($from_date,$to_date,$total_days) {
        $count_holiday_date = 0;
        $current_date = date('Y-m-d');

        Configure::load('relationship_manager');
        $holiday_date = Configure::read('holiday_date');
        
        for($i=0;$i<$total_days;$i++){
            $range_date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));
            if(in_array($range_date, $holiday_date)){
                $count_holiday_date++;
            }
        }

        return $count_holiday_date;
    }

    function countSunday($from_date,$to_date) {
        $start = new DateTime($from_date);
        $end = new DateTime($to_date);
        $days = $start->diff($end, true)->days;

        $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

        return $sundays;
    }

    function calculateLeavesAndLateMark($rm_user_id,$from_date,$to_date,$total_days) {

        $leaves = 0;
        $half_day_count = 0;       

        $dbObj = ClassRegistry::init('Slaves');

         $present_chk = $dbObj->query("SELECT count(attendance_id) as present_count FROM rm_attendance WHERE (`date` BETWEEN '".$from_date."' and '".$to_date."') and rm_user_id='".$rm_user_id."'");
          if(!empty($present_chk)){
                $present_count = $present_chk[0][0]['present_count'];
                $countSunday = $this->countSunday($from_date,$to_date);
                $countFutureDate = $this->countFutureDate($from_date,$to_date,$total_days);
                $countHoliday = $this->countHolidayDate($from_date,$to_date,$total_days);
                $leaves = $total_days - $present_count - $countSunday - $countFutureDate - $countHoliday;
          } 
 

          $half_day_chk = $dbObj->query("SELECT count(attendance_id) as half_day_count FROM rm_attendance WHERE (`date` BETWEEN '".$from_date."' and '".$to_date."') and (IF((end_time!='00:00:00'),(TIMEDIFF(CONCAT(`date`,' ',end_time),CONCAT(`date`,' ',start_time)) < '09:00:00'),FALSE)) and rm_user_id='".$rm_user_id."'");
          if(!empty($half_day_chk)){
                $half_day_count = $half_day_chk[0][0]['half_day_count'];
          }

          $array = array(
                "leaves"=>$leaves,
                "half_day_count"=>$half_day_count,
                "rm_user_id"=>$rm_user_id
            );

        return $array;
    }
    
    // Send OTP on User Mobile Number for User Device Mapping on Web
    function sendOTPToUserDeviceMapping($mobile, $otp_via_call, $rm_user_id, $lat_long_dist=0){
        $otp = $this->General->generatePassword(6);
        $MsgTemplate = $this->General->LoadApiBalance();
        $paramdata['OTP'] = $otp;
        $dbObj = ClassRegistry::init('Slaves');
        $data = $dbObj->query("SELECT id FROM users WHERE id = $rm_user_id AND mobile='$mobile'");
        if(empty($data)){
            return array("status"=>"failure", 'code'=>'62', "description"=>'Data is not correct');
        }
        
        
        $content = $MsgTemplate['Relationship_managerVerify_OTP_MSG'];

        $message = $this->General->ReplaceMultiWord($paramdata, $content);
        $this->General->logData("rm_api_authenticate_OTP.txt", "in authenticate_new api: " . json_encode($message));
        $this->Shop->setMemcache("otp_rmDeviceNewUuid_$mobile", $otp, 30 * 60);
        $this->General->sendMessage($mobile, $message, 'payone', null);
        return array('status'=>'failure','code'=>'101','rm_user_id' => $rm_user_id,'description'=>'You should receive an OTP in sometime');
     }

     function getRMDistributor($params){

          $dbObj = ClassRegistry::init('Slaves');
          $distributors = $dbObj->query("SELECT * FROM distributors 
              JOIN rm ON rm.id = distributors.rm_id
              WHERE distributors.active_flag = 1 AND rm.user_id = '".$params['rm_user_id']."'");
          $dist_ids = array_map(function($element){
          return $element['distributors']['id'];
          },$distributors); 
          $temp = $this->Shop->getUserLabelData($dist_ids,2,3);
          $distributor_list = array();
          $i=0;
          foreach($temp as $list){
             $dist_id = $dist_ids[$i];
         
             $distributor_list[$i]['id'] = $list['dist']['id'];
             $distributor_list[$i]['name'] = $list['imp']['name'];
             $distributor_list[$i]['mobile'] = $list['dist']['mobile'];
             if($list['imp']['shop_est_name']==null){
                $distributor_list[$i]['shop_est_name'] = '';
             }else{
                $distributor_list[$i]['shop_est_name'] = $list['imp']['shop_est_name'];
             }

             $i++;
          }
          return array('status'=>'success','distributor_list' => $distributor_list,'description'=>'RM Master Distributor List');
       }

      function getDistributorRetailer($params){

        $dbObj = ClassRegistry::init('Slaves');

          $retailers = $dbObj->query("SELECT retailers.id,retailers.shop_ownership,retailers.nature_of_business,retailers.area_of_business,DATE_FORMAT(retailers.created ,'%Y-%m-%d') as created_date,retailers.name FROM retailers 
             JOIN distributors ON distributors.id = retailers.parent_id
             WHERE retailers.parent_id = '".$params['dist_id']."'");

          $ret_ids = array_map(function($element){
          return $element['retailers']['id'];
          },$retailers);
          $temp = $this->Shop->getUserLabelData($ret_ids,2,2);
        
          $retailers_list = array();
          $i=0;
          foreach($temp as $list){
             $retailers_list[$i]['id'] = $list['ret']['id'];
             if($list['retailers']['name'] == null){
                $retailers_list[$i]['name'] = "Retailer-".$list['ret']['id'];
             }else{
                $retailers_list[$i]['name'] = $retailers[$i]['retailers']['name'];
             }
             
             $retailers_list[$i]['mobile'] = $list['ret']['mobile'];
             if($retailers[$i]['retailers']['shop_ownership'] == null){
                $retailers_list[$i]['shop_ownership'] = '';
             }else{
                $retailers_list[$i]['shop_ownership'] = $retailers[$i]['retailers']['shop_ownership'];
             }
             
             if($retailers[$i]['retailers']['nature_of_business'] == null){
                $retailers_list[$i]['nature_of_business'] = '';
             }else{
                $retailers_list[$i]['nature_of_business'] = $retailers[$i]['retailers']['nature_of_business'];
             }

             if($retailers[$i]['retailers']['area_of_business'] == null){
                $retailers_list[$i]['area_of_business'] = '';
             }else{
                $retailers_list[$i]['area_of_business'] = $retailers[$i]['retailers']['area_of_business'];
             }
             
             if($list['imp']['shop_est_name']==null){
                $retailers_list[$i]['shop_est_name'] = '';
             }else{
                $retailers_list[$i]['shop_est_name'] = $list['imp']['shop_est_name'];
             }

             if( strtotime($retailers[$i][0]['created_date']) > strtotime('-7 day') ) {
                $retailers_list[$i]['new'] = 1;
            }else{
                 $retailers_list[$i]['new'] = 0;
            }

             $i++;
          } 
          return array('status'=>'success','retailers_list' => $retailers_list,'description'=>'Distributor Master Retailer List');
      }

      function getRMStatus($params){
        $dbObj = ClassRegistry::init('Slaves');
         $status_list = $dbObj->query("SELECT id,lead_values FROM lead_attributes_values
             WHERE type_id = '1' and lead_values NOT IN('Warm')");
         $status_data = array();
         $i=0;
         foreach ($status_list as $status) {
            $status_data[$i]['id'] = $status['lead_attributes_values']['id'];
            $status_data[$i]['lead_values'] = $status['lead_attributes_values']['lead_values'];
            $i++;
         }

         return array('status'=>'success','status_list' => $status_data,'description'=>'RM Status List');
     }

      function getMyAllLead($params){
        $dbObj = ClassRegistry::init('Slaves'); 

        $query = "SELECT ln.id,ln.name,ln.current_business,ln.phone,ln.shop_name,ln.pin_code,ln.city,ln.state,ln.lead_type,ln.rm_user_id,ln.dist_id,ln.converted_dist_id,ln.creation_date,ln.followup_date,ln.followup_remark,ln.lead_state,ln.status,ln.converted_date,lav.lead_values FROM leads_new ln
            JOIN lead_attributes_values lav ON lav.id = ln.lead_state
             WHERE ln.rm_user_id = '".$params['rm_user_id']."' ";
             $query .= " order by ln.followup_date desc";

         $lead_list = $dbObj->query($query);
         $lead_data = array();
         $i=0;
         foreach ($lead_list as $lead) {
            $lead_data[$i] = $lead['ln'];
            $lead_data[$i]['lead_values'] = $lead['lav']['lead_values'];
            $i++;
         }

         return array('status'=>'success','lead_list' => $lead_data,'description'=>'RM Lead List');
      }

      function getRMServices(){
        $dbObj = ClassRegistry::init('Slaves');
        
         $business_update_list = $dbObj->query("SELECT parent_id,parent_name FROM services where business_update_flag != 0 GROUP BY parent_id ORDER BY priority");
         $business_update = array();
         $i=0;
         foreach ($business_update_list as $service) {
            $business_update[$i]['id'] = $service['services']['parent_id'];
            $business_update[$i]['name'] = $service['services']['parent_name'];
            $i++;
         }

         $document_pickup_list = $dbObj->query("SELECT parent_id,parent_name FROM services where document_pickup_flag != 0 GROUP BY parent_id ORDER BY priority");
         $document_pickup = array();
         $i=0;
         foreach ($document_pickup_list as $service) {
            $document_pickup[$i]['id'] = $service['services']['parent_id'];
            $document_pickup[$i]['name'] = $service['services']['parent_name'];
            $i++;
         }

         return array('status'=>'success','business_update_list' => $business_update,'document_pickup_list' => $document_pickup,'description'=>'RM Service List');
     } 
    
}