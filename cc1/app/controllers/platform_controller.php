<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class PlatformController extends AppController{
    var $name = 'Platform';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator','NumberToWord');
    var $components = array('RequestHandler', 'Shop', 'Platform','Documentmanagement','General','Invoice','Email');
    var $uses = array('User', 'Slaves', 'PlatformLog','DocManagement');
    var $validFormats = array('xml', 'json');

    function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('*');
        $this->logId = rand() . time();
    }

    function log($data){
        $filename = "platformapis.txt";
        $data = "LogId: " . $this->logId . ":::" . $data;
        $this->General->logData($filename, $data);
    }

    function apis(){
        $this->autoRender = false;
        $params = $_REQUEST;

        $origin = $_SERVER['HTTP_ORIGIN'];
        if(!empty($origin) && (stristr($origin, '.pay1.com') !== FALSE || stristr($origin, '.pay1.in') !== FALSE || stristr($origin, 'pay1.in') !== FALSE || stristr($origin, 'pay1travel.in') !== FALSE)) {
            header("Access-Control-Allow-Origin: $origin");
            header("Access-Control-Allow-Credentials: true");
        }

        if(isset($this->params['form']['document'])){
            $params['document'] = $this->params['form']['document'];
        }

        $response = $this->Platform->requestValidation($params);
        if($response['status'] == 'success'){
            $params = $response['params'];
            $method = $params['method'];
            $this->log("Request: " . json_encode($params) . "::ServerInfo: " . json_encode($_SERVER));
            $response = $this->$method($params);
        }

        $this->log("Response: " . json_encode($response));


        if(isset($_GET['callback'])){
            echo  trim($_GET['callback'].'('.json_encode($response).');');
        }
        else {
            echo json_encode($response);
        }

    }

    private function authenticate($params){
        $this->autoRender = false;

        $this->General->logData("authenticateuser.txt", "in authenticateUser api: " . json_encode($params));

        Configure::load('platform');
        $group_app_mapping = Configure::read('group_app_mapping');
        $app_names_services = Configure::read('app_names_services');

        if(!ctype_alnum(str_replace(array('_','-'),'',$params['app_name']))) {
                return array('status' => 'failure','code' => '2001','description' => "Invalid app type");
        } else if($this->General->mobileValidate($params['mobile']) == 1) {
                return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
        }

        $mobile = $params['mobile'];
        $password = $this->Auth->password($params['password']);
        $uuid = $params['uuid'];
        $app_version_code = $params['version_code'];
        $app_type = $params['app_name'];

        $groups = $group_app_mapping[$app_type];
        $authenticateuser = $this->Platform->__checkUserExist($mobile, $password, $groups, $params);
        $all_groups = explode(",",$authenticateuser[0]['groupids']);
        $group_id = isset($params['group_id']) ? $params['group_id'] : $all_groups[0];

        if($app_type == "recharge_app") {
            $userRow = $this->Slaves->query("SELECT id from users_services where user_id = '".$authenticateuser['users']['id']."' AND service_id=12 and service_flag=1");

            if(!empty($userRow) && $app_version_code < 122) {
                return array('status'=>'failure', 'code'=>'202', 'description'=>'Please update your app to use new features','forced_upgrade_flag' =>1);
            }
        }

        $flag = 1;
        if($group_id == RETAILER){
            $dist_details = $this->Slaves->query("SELECT distributors.active_services, retailers.shopname, distributors.id FROM retailers JOIN distributors ON (retailers.parent_id = distributors.id) WHERE retailers.mobile = '$mobile'");

            if(in_array($dist_details[0]['distributors']['id'],array(explode(',',SAAS_DISTS)))){
                $flag = 0;
            }
            //$flag = 0;
            /*foreach($app_names_services[$app_type] as $service) {
                in_array($service, explode(',', $dist_details[0]['distributors']['active_services'])) && $flag = 1;
            }*/
        }

        if($authenticateuser['status'] == 'failure'):
            return $authenticateuser;
        elseif($authenticateuser['users']['active_flag'] == 0):
            return array('status'=>'failure', 'code'=>'114', 'description'=>$this->Platform->errorDescription(114));
        elseif($flag == 0):
            return array('status'=>'failure', 'code'=>'214', 'description'=>'Your Distributor is Inactive for this service');
        elseif(!in_array($group_id,$all_groups)):
            return array('status'=>'failure', 'code'=>'122', 'description'=>$this->Platform->errorDescription(122));

        else:
            $updateAppVersion = $this->Platform->checkAppVersion($app_version_code, $app_type);
            if($updateAppVersion['status'] == 'failure'):
                return $updateAppVersion;
            endif;

            $info = $this->Shop->getShopData($authenticateuser['users']['id'],$group_id);

            if(empty($authenticateuser['user_profile']['id'])):
                $otp_data = $this->Platform->sendOTPToUserDeviceMapping($mobile,0,$authenticateuser['users']['id']);
                return $otp_data;
            else:
                $info['User']['group_id'] = $group_id;
                $info['User']['id'] = $authenticateuser['users']['id'];
                $info['User']['mobile'] = $mobile;
                $info['User']['balance'] = $authenticateuser['users']['balance'];
                $info['User']['group_ids'] = implode(",",$all_groups);
                $info['User']['passflag'] = $authenticateuser['users']['passflag'];

                $info['User']['profile_id'] = $authenticateuser['user_profile']['id'];
                $this->User->query("UPDATE `users` SET `last_login`= '".date('Y-m-d')."' WHERE `id` =".$info['User']['id']);
                $this->Session->write('Auth', $info);
                $label_data = $this->Shop->getUserLabelData($authenticateuser['users']['id'],2);
                return array('status'=>'success', 'token'=>$this->Session->id(), 'user_id'=>$authenticateuser['users']['id'], 'shopname'=>$label_data[$authenticateuser['users']['id']]['imp']['shop_est_name'], 'mobile'=>$mobile, 'profile_id'=>$authenticateuser['user_profile']['id'],'description'=>'Login successful','passflag'=>$authenticateuser['users']['passflag']);

            endif;
        endif;
    }

    private function resendOTPAuthenticate($params){
        $this->autoRender = false;

        $mobile = $params['mobile'];
        $user_id = $params['user_id'];

        if($params['mode'] == 'sms'){
            $otp_data = $this->Platform->sendOTPToUserDeviceMapping($mobile,0,$user_id);
            return $otp_data;
        }
        else if($params['mode'] == 'call'){
            $otp_data = $this->Platform->sendOTPToUserDeviceMapping($mobile,1,$user_id);
            return $otp_data;
        }

        return array("status"=>"failure", 'code'=>'116', "description"=>'Mode is not valid');
    }

    // Verify OTP of User Mobile Number for User Device Mapping on Web
    private function verifyOTPAuthenticate($params){

        if(!ctype_alnum($params['uuid']) || !is_numeric($params['user_id'])) {
                return array('status' => 'failure','code' => '2003','description' => "Invalid uuid or user_id");
        } else if(!ctype_alnum(str_replace(array('_','-'),'',$params['app_name']))) {
                return array('status' => 'failure','code' => '2004','description' => "Invalid app type");
        } else if($this->General->mobileValidate($params['mobile']) == 1) {
                return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
	} else if(!is_numeric($params['otp'])) {
                return array('status' => 'failure','code' => '2005','description' => "Invalid OTP");
        }
        $user_mobile = $params['mobile'];
        $user_id = $params['user_id'];
        $uuid = $params['uuid'];
        $otp = $params['otp'];

        $user_exists = $this->User->query("select * from users where mobile = '" . $user_mobile . "' AND id = '$user_id'");
        if(empty($user_exists)){
            return array('status'=>'failure', 'code'=>'49', 'description'=>$this->Shop->apiErrors('49'));
        }

        if($otp == $this->Shop->getMemcache("otp_userProfileNewUuid_$user_mobile") || !$this->General->isOTPRequired($user_mobile)){
            $this->Shop->delMemcache("otp_userProfileNewUuid_$user_mobile");
            $user_insert_data = $this->User->query("INSERT INTO `user_profile` (`id`,`user_id`, `uuid`,`app_type`,`created`, `updated`,`date`) " . "VALUES (NULL, " . $user_id . ",'$uuid','" . $params['app_name'] . "','" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "','".date('Y-m-d')."');");

            return array('status'=>'success', 'description'=>'OTP Matched Successfully');
        }
        else {
            return array('status'=>'failure', 'code'=>'54', 'description'=>$this->Shop->apiErrors('54'));
        }
    }

    private function updatePin($params) {
        $userId = $params['user_id'];
        $oldPassword = $this->Auth->password($params['old_pin']);
        $newPassword = $this->Auth->password($params['new_pin']);

        if($oldPassword == $newPassword){
            return array('status' => 'failure','code'=>'123','description' =>$this->Platform->errorDescription(123));
        }
        $data = $this->User->query("SELECT mobile FROM users WHERE id = '".$userId."' AND password='$oldPassword' AND active_flag = 1");
        if(empty($data)){
            return array('status' => 'failure','code'=>'124','description' =>$this->Platform->errorDescription(124));
        }
        if(!$this->Shop->isStrongPassword($params['new_pin'])):
            return array('status' => 'failure','code'=>'125','description' =>$this->Platform->errorDescription(125));
        endif;

        try{
            App::import('Controller', 'Users');
            $ini = new UsersController;
            $ini->constructClasses();
            $ini->updatePassword($data['0']['users']['mobile'], $params['new_pin'], "change", "updatePass");

            $MsgTemplate = $this->General->LoadApiBalance();
            $sms_msg = $MsgTemplate['App_PinUpdated_MSG'];
            $this->General->sendMessage($data['0']['users']['mobile'],$sms_msg,'shops');

			session_destroy();
            return array('status' => 'success','code' => '2008','description'=>'Pin Updated successfully. You will have to login again.');



        }catch(Exception $e){
            return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
        }
    }


    function deviceInfoUpdate($params){
        $this->autoRender = false;

        $this->Platform->deviceInfoUpdate($params);

        return array('status'=>'success');
    }

    private function logout($params){
        $memcache = $this->Shop->memcacheConnection(MEMCACHE_MASTER);
        $token = $params['token'];

        $memcache->delete($token);
        session_destroy();
        return array('status'=>'success');
    }

    private function changePin($params){
        if(isset($params['mobile']) && $this->General->mobileValidate($params['mobile']) == 1) {
                return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
	}
        $mobile = $params['mobile'];
        $user_details = $this->User->query("SELECT * FROM users WHERE mobile = '$mobile'");

        if(!empty($user_details)){
            $otp = $this->General->generatePassword(6);
            $MsgTemplate = $this->General->LoadApiBalance();
            $paramdata['OTP'] = $otp;
            $content = $MsgTemplate['Forget_Password_MSG'];

            $message = $this->General->ReplaceMultiWord($paramdata, $content);
            $this->Shop->setMemcache("otp_changePIN_$mobile", $otp, 10 * 60);
            $this->General->sendMessage($mobile, $message, 'payone', null);

            return array('status'=>'success', 'description'=>'OTP sent successfully');
        }
        else{
            return array('status'=>'failure', 'code'=>'57', 'description'=>'User does not exist');
        }
    }

    private function verifyOTPChangePIN($params){
        if($this->General->mobileValidate($params['mobile']) == 1) {
                return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
	}
        $mobile = $params['mobile'];
        $user_details = $this->User->query("SELECT * FROM users WHERE mobile = '$mobile'");

        if(!empty($user_details)){
            $otp = $params['otp'];
            $otp_system = $this->Shop->getMemcache("otp_changePIN_$mobile");

            if($otp != $otp_system && $this->General->isOTPRequired($mobile)){
                return array('status'=>'failure', 'code'=>'57', 'description'=>'OTP does not match');
            }
            else {
                $new_pwd = $params['pin'];
                $password = $this->Auth->password($new_pwd);
                $this->User->query("update users
                    		set password='" . $password . "',
                    		passflag = 1
                    		where mobile='".$mobile."'");

                return array('status'=>'success', 'description'=>'Password set successfully');
            }
        }
        else{
            return array('status'=>'failure', 'code'=>'57', 'description'=>'User does not exist');
        }
    }

    private function commissionCalculation($params){
        $user_id     = $params['user_id'];
        $service_id  = $params['service_id'];
        $product_id  = $params['product_id'];
        $amount  = $params['amount'];
        $vendor_amount  = $params['vendor_amount'];

        $comm_data = $this->Shop->commissionCalculation($user_id,$service_id,$product_id,$amount,$vendor_amount);
        if(empty($comm_data)){
            return array('status' => 'failure','code'=>'2005','description' => 'No data found');
        }
        else {
            return array('status' => 'success','code'=>'0','description' => $comm_data);
        }
    }

    private function getCommissions($params){
        $user_id  = $params['user_id'];
        $app_type = $params['app_name'];
        $services = (isset($params['service_id'])) ? array($params['service_id']) : array();

        if(empty($services)){
            Configure::load('platform');
            $service_mapping = Configure::read('app_names_services');
            $services = $service_mapping[$app_type];
        }

        foreach($services as $service){
            $arr[$service] = $this->Platform->getCommissions($user_id,$service);
        }

        return array('status' => 'success','code'=>0,'description' => $arr);
    }

        private function walletHistory($params) {

                $user_id     = $params['user_id'];

                $from_date   = ($params['date_from'] == NULL) ? date('Y-m-d') : $params['date_from'];

                $to_date     = ($params['date_to'] == NULL) ? date('Y-m-d') : $params['date_to'];

                $data        = $this->Platform->accountHistory($user_id, $from_date, $to_date);

                $res = $this->balance($params);
                $data['balance'] = $res['balance']['c_1'];

                return array('status' => 'success','code'=>'0','description' => $data);
//                return $data;
        }

        private function balance($params) {
                $user_id = $params['user_id'];

                $balance = $this->User->query("SELECT balance FROM users WHERE id = '$user_id'");

                if(!empty($balance)) {
                        return array('status' => 'success','balance'=>array('c_1'=>$balance[0]['users']['balance']));
                } else {
                        return array('status' => 'failure','code'=>'57','balance'=>'Invalid User');
                }
        }

        private function updateTextualInfo($params)
        {
            $this->autoRender = false;
            $user_id = $params['user_id'];
            $service_id = $params['service_id'];
            $longitude = $params['longitude'];
            $latitude = $params['latitude'];
            $textual_info = json_decode($params['textual_info'],TRUE);

            $config_id = $this->Documentmanagement->getLabelConfig('key','id');

            $failed_labels = array();
            foreach($textual_info as $key => $val)
            {
                if(isset($config_id[$key]) && !empty($config_id[$key]))
                {
                    $response = $this->Documentmanagement->updateTextualInfo($user_id,$config_id[$key],$service_id,$val,$user_id);
                    if($response['status'] == 'success'){
                        if(!empty($longitude) && !empty($latitude)){
                            $this->Documentmanagement->updateRetShopLocation($user_id,$longitude,$latitude);
                        }
                    }elseif($response['status'] == 'failure'){
                        $failed_labels[] = $response['label'];
                    }
                }
            }
            if( count($failed_labels) > 0 && (count($failed_labels) == count($textual_info)) ){
               return array('status' => 'failure', 'description' => 'Failed to update info');
            }
            if( count($failed_labels) > 0 ){
                $response['failed_labels'] = implode(',',$failed_labels);
            }
            return $response;
        }

        private function uploadDocs($params) {
            $this->autoRender = false;
            $response_status_flag = '';

            $user_id = $params['user_id'];
            $service_id = $params['service_id'];
            $label_id = $params['label_id'];

            $documents = $params['document'];
            Configure::load('platform');
            $labels = $this->Documentmanagement->getImpLabels();
            $config_type=$this->Documentmanagement->getLabelConfig('key','type');
            $config_id=$this->Documentmanagement->getLabelConfig('key','id');

            if($config_type[$params['label_id']]==1)
            {
                $response=$this->Documentmanagement->updateDocumentInfo($user_id,$service_id,$config_id[$label_id],$documents,$user_id);
                if($response['status'] == 'success' && $config_id[$label_id] == 2){
                    $this->Documentmanagement->updateAadhar($user_id);
                }
                return $response;
            }
            else
            {
                $response=$this->Documentmanagement->updateTextualInfo($user_id,$config_id[$label_id],$service_id,$params['label_description'],$user_id);
                return $response;
            }
        }

        private function profileApi($params) {
                $user_id = $params['user_id'];
                $this->autoRender = false;

                $app_type = $params['app_name'];
                $service_id = $params['service_id'];

                $user_info = $this->User->query("SELECT users.id, users.mobile, users.balance, distributors.name, distributors.company, retailers.name, retailers.shopname, d.user_id, d.company, d.mobile,GROUP_CONCAT(user_groups.group_id) as group_id FROM users LEFT JOIN distributors ON (users.id = distributors.user_id) LEFT JOIN retailers ON (users.id = retailers.user_id) LEFT JOIN distributors d ON (d.id = retailers.parent_id) LEFT JOIN user_groups on (user_groups.user_id = users.id) where users.id = '$user_id'");

                /** IMP DATA ADDED : START**/
                $temp = $this->Shop->getUserLabelData($user_id,2,0);
                $imp_data = $temp[$user_id];
                /** IMP DATA ADDED : END**/

                // $name   = $user_info[0]['distributors']['name'] != '' ? $user_info[0]['distributors']['name'] : $user_info[0]['retailers']['name'];
                $name   = $imp_data['imp']['name'];
                // $shop   = $user_info[0]['distributors']['company'] != '' ? $user_info[0]['distributors']['company'] : $user_info[0]['retailers']['shopname'];
                $shop   = $imp_data['imp']['shop_est_name'];

                $basic_profile = array('mobile'=>$user_info[0]['users']['mobile'], 'name'=>$imp_data['imp']['name'], 'shop'=>$imp_data['imp']['shop_est_name'] ,'group_id'=>explode(',',$user_info[0][0]['group_id']));
                if($user_info[0]['d']['user_id'] != '') {
                    $temp = $this->Shop->getUserLabelData($user_info[0]['d']['user_id'],2,0);
                    $imp_data = $temp[$user_id];
                    $basic_profile['distributor_company'] = $imp_data['imp']['shop_est_name'];
                    $basic_profile['distributor_mobile']  = $user_info[0]['d']['mobile'];
                }

                if(empty($service_id)){
                    Configure::load('platform');
                    $service_mapping = Configure::read('app_names_services');
                    $services = $service_mapping[$app_type];
                }else{
                    $services = explode(',',$service_id);
                }

                $doc_info = $this->Documentmanagement->checkDocs($user_id,$services);
                $basic_profile['name'] = $doc_info['textual']['name'];
                $wallet = array('c1'=>$user_info[0]['users']['balance']);

                $user_services = $this->User->query("SELECT us.service_id, us.kit_flag, us.service_flag,us.params,us.param1,sp.* FROM users_services us JOIN service_plans sp ON(us.service_plan_id = sp.id) WHERE us.user_id = '$user_id'");
                $service_info = array();
                foreach( $user_services as $us ) {
                    $service_info[$us['us']['service_id']] = array('kit'=>$us['us']['kit_flag'], 'service_flag'=>$us['us']['service_flag'],'params'=>json_decode($us['us']['params'],true),'agent_id'=>$us['us']['param1'],'plan'=>$us['sp']);
                }

                return array('status'=>'success','basic_profile_info'=>$basic_profile, 'doc_info'=>$doc_info, 'wallet'=>$wallet,'service_info'=>$service_info);
        }
        private function serviceInfoApi($params){
            $user_id = $params['user_id'];
            $this->autoRender = false;

            $user_services = $this->User->query("SELECT us.service_id, us.kit_flag, us.service_flag,us.params,us.param1,sp.*,srl.* FROM users_services us JOIN service_plans sp ON(us.service_plan_id = sp.id) JOIN service_request_log srl ON( us.service_id = srl.service_id AND us.user_id = srl.ret_user_id ) WHERE us.user_id = '$user_id'");
            $service_info = array();
            foreach( $user_services as $us ) {
                $service_info[$us['us']['service_id']] = array('kit'=>$us['us']['kit_flag'], 'service_flag'=>$us['us']['service_flag'],'params'=>json_decode($us['us']['params'],true),'agent_id'=>$us['us']['param1'],'plan'=>$us['sp'],'service_request' => $us['srl'] );
            }

            return array('status'=>'success','service_info'=>$service_info);
        }

        private function bankAccounts($params) {

                $this->autoRender = false;

                $accounts = $this->Slaves->query("SELECT * FROM bank_details WHERE visible_to_retailer_flag = 1");

		$accounts_table = array();
		foreach($accounts as $key => $row) {
			$account = array();
			$account['bank']           = $row['bank_details']['bank'];
			$account['account_no']     = $row['bank_details']['account_no'];
			$account['transfer_modes'] = $row['bank_details']['transfer_modes'];
			$account['account_name']   = $row['bank_details']['account_name'];
			$account['account_type']   = $row['bank_details']['account_type'];
			$account['ifsc']           = $row['bank_details']['ifsc'];
			$account['branch']         = $row['bank_details']['branch'];

			$accounts_table[] = $account;
		}

		return array('status'=>'success', 'description'=>$accounts_table);

        }

        private function listInvoices($params)
        {
            $this->autoRender = false;

            $user_id = $params['user_id'];
            $month = $params['month'];
            $year = $params['year'];
            $type = $params['type']; //0/1 (0->invoices raised to me, 1->invoices raised by me)

            $invoice_data = $this->Invoice->getInvoiceList($user_id,$month,$year,$type);

            $response = array('status' => 'success','data'=> json_encode($invoice_data));

            return $response;
        }

        function getInvoice($params)
        {
            if(!is_numeric($params['user_id'])) {
                    return array('status' => 'failure','code' => '2021','description' => "Invalid id");
            } else if(!is_numeric($params['invoice_id'])) {
                    return array('status' => 'failure','code' => '2022','description' => "Invalid invoice  id");
            } else if(!is_numeric($params['month'])) {
                    return array('status' => 'failure','code' => '2023','description' => "Invalid month");
            }else if(!is_numeric($params['year'])) {
                    return array('status' => 'failure','code' => '2024','description' => "Invalid year");
            }
            $this->autoRender = false;
            $user_id = $params['user_id'];
            $invoice_id = $params['invoice_id'];
            $month = $params['month'];
            $year = $params['year'];
            $email_id = $params['email_id'];
            $type = $params['type']; //0/1/2 (0 -> ‘view’, 1 -> ‘download’, 2-> ‘email’)

            if($type==0 || $type==1 || $type=='invoice')
            {
                $invoice_data = $this->Invoice->getInvoiceData($user_id,$invoice_id,$month,$year,$type,$email_id);
                $response = $this->Invoice->generatePdf($invoice_data,$type);
            }
            elseif($type==2)
            {
                $invoice_id = (array)$invoice_id;
                $response = $this->Invoice->sendMail($user_id,$invoice_id,$month,$year,$type,$email_id);
            }

            return $response;
        }

        function createRetDistNewLeads($params){
            $response = $this->Platform->createRetDistNewLeads($params);
            return $response;
        }

        function verifyRetDistNewLeads($params){
            $response = $this->Platform->verifyRetDistNewLeads($params);
            return $response;
        }

        function sendOTPToRetDistLeads($params) {
                $this->autoRender = false;
                if($this->General->mobileValidate($params['mobile']) == 1) {
                    return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
                }else if(!ctype_alnum(str_replace(' ','',($params['interest'])))) {
                    return array('status' => 'failure','code' => '2030', 'description' => "Invalid type");
                }
                $intreseted_lead = isset($params['interest']) ? $params['interest'] : $_REQUEST['interest'];
                $mobile = isset($params['mobile']) ? $params['mobile']: $_REQUEST['mobile'];
                $changeMobile = isset($_REQUEST['changeMobile']) ? $_REQUEST['changeMobile'] : 0 ;

                //if mobile number incorrect and intreseted_lead is blank
                if((trim($intreseted_lead) == "") || (strlen($mobile) != 10)){
                  return array('status' => 'failure','code'=>'58','description' => $this->Shop->apiErrors('58'));
                }

                if(trim($mobile)){

        			$otp = $this->General->generatePassword(6);
                                $MsgTemplate = $this->General->LoadApiBalance();

                                $paramdata['INTRESTED_LEAD_NAME'] = $intreseted_lead;
                                $paramdata['OTP'] = $otp;


                                if(isset($params['change_dist_mob_otp_flag']) && ($params['change_dist_mob_otp_flag'] == 1)){

                                    $content =  $MsgTemplate['Dist_New_Mobile_Change_By_SuperDist_MSG'];

                                }else if(isset($params['create_dist_otp_flag']) && ($params['create_dist_otp_flag'] == 1)){

                                    $content =  $MsgTemplate['Distributor_Create_By_SuperDistributor_MSG'];

                                }else if(isset($params['create_saleman_otp_flag']) && ($params['create_saleman_otp_flag'] == 1)){

                                    $content =  $MsgTemplate['Salesman_Create_By_Distributor_MSG'];

                                }else if(isset($params['create_ret_otp_flag']) && ($params['create_ret_otp_flag'] == 1)){

                                    $content =  $MsgTemplate['Retailer_Create_By_Distributor_MSG'];

                                }else if($changeMobile){

                                    $content =  $MsgTemplate['Retailer_New_Mobile_Change_By_Distributor_MSG'];

                                }else{
                                    $content =  $MsgTemplate['Retailer_Distributor_Registered_MSG'];
                                }

                                $message = $this->General->ReplaceMultiWord($paramdata,$content);

                                $this->General->logData("api_authenticate_OTP.txt","in authenticate_new api: ".json_encode($message));

                                $this->General->sendMessage($mobile, $message, 'payone', null);
        			$this->Shop->setMemcache("otp_RetDist_Registration_$mobile", $otp, 30*60);
        			$OTA_Fee = $this->General->findVar("OTA_Fee");

                                return array('status' => 'success', 'code'=>'59', 'OTA_Fee' => $OTA_Fee, 'description' => $this->Shop->apiErrors('59'));
                }
        	else{
        		return array('status' => 'failure','code'=>'58','description' => "Mobile ".$this->Shop->apiErrors('58'));
                }

        }

        function callReferralApi($userId, $uniqueKey) {
                $this->Platform->callReferralApi($userId, $uniqueKey);
        }

        private function getPanStatus($params){
            $this->autoRender = false;
            $data = $this->Platform->getPanStatus(explode(",", $params['pan_no']));
            return $data;
        }
        private function getPlans($params){
            $this->autoRender = false;
            $plans = $this->Platform->getPlans($params['service_id']);
            return array('status' => 'success','plans'=> $plans);
        }
        private function purchaseKit($params){
            $this->autoRender = false;
            $response = $this->Platform->purchaseKit($params['service_id'],$params['plan_id'],$params['user_id'],1);
            return $response;
        }
        private function upgradePlan($params){
            $this->autoRender = false;
            $response = $this->Platform->upgradePlan($params['service_id'],$params['plan_id'],$params['user_id'],1);
            return $response;
        }
        private function requestService($params){
            $this->autoRender = false;
            $response = $this->Platform->requestService($params['service_id'],$params['user_id']);
            return $response;
        }
}
