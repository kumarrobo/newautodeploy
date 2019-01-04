<?php

class DistributorsController extends AppController{

	public $helpers = array('Ajax','Javascript','Paginator');
	public $components = array('Shop', 'Auth', 'General','Platform');
	public $uses = array('User', 'Retailer','Slaves');

	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('*');
	}

	function api(){
		try{
			$method = $_REQUEST['method'];

			$this->log();
			$save = false;
			if($method == 'updateRetailer')$save = true;
			if($save)$this->General->logData("/mnt/logs/updateRetailer.txt","line 1");
			if(!method_exists($this, $method)){
				if($save)$this->General->logData("/mnt/logs/updateRetailer.txt","line 2: method does not exists");
				$this->send(array("status" => "failure", 'code'=>'2','description'=>$this->Shop->errors(2)));
				exit;
			}
			$access_code = $this->access($method);
			if($save)$this->General->logData("/mnt/logs/updateRetailer.txt","line 3: method does not ::$access_code");

			if($access_code !== true){
				$this->send(array("status" => "failure", "code" => $access_code, "description" => $this->Shop->errors($access_code)));
				exit;
			}
			if($save)$this->General->logData("/mnt/logs/updateRetailer.txt","line 4: calling method here ::".json_encode($_REQUEST));

			$this->send($this->$method($_REQUEST));
		}
		catch(Exception $e){
			$this->send(array("status" => "failure", 'code'=>'30', 'description'=>$this->Shop->errors(30)));
			exit;
		}
		$this->autoRender = false;
	}

	private function send($message){
		$root = isset($_GET['root']) ? $_GET['root'] : "";
		if($root)
			echo $root .'(['.json_encode($message).']);';
		else
			echo json_encode($message);
	}

	function access($method){
		$open_methods = array("authenticate", "getRetailers", "sendOTP", "resetPin", "serverLog", "verifyOTPAuthenticate", "resendOTPAuthenticate");
		$auth_methods = array("transferBalance", "balance", "createRetailer", "retailerBalanceReport",
				"uploadRetailerDocuments", "getRetailer", "updateRetailer", "logout", "changePin", "lastBalanceTransfer",
				"createTrialRetailer", "editRetailer", "uploadKYCDocuments", "bankAccounts", "banksAndTransferTypes", "sendBalanceTopupRequest");
		$distributor_only_methods = array("pullback");
		$salesman_only_methods = array();
		$retailer_only_methods = array("uploadRetailerDocuments", "getRetailer", "updateRetailer", "editRetailer",
				"uploadKYCDocuments");

		if(in_array($method, $open_methods)){
			return true;
		}

		if(isset($_SESSION['Auth']['User']['group_id'])){
			$group_id = $_SESSION['Auth']['User']['group_id'];
			if($group_id == DISTRIBUTOR && (in_array($method, $auth_methods) || in_array($method, $distributor_only_methods))){
				return true;
			}
			else if($group_id == SALESMAN && (in_array($method, $auth_methods) || in_array($method, $salesman_only_methods))){
				return true;
			}
			else if($group_id == RETAILER && in_array($method, $retailer_only_methods)){
				return true;
			}
			else
				return 404;
		}
		else
			return 403;
	}

	function log(){
		$method = $_REQUEST['method'];
		$auth_id = isset($_SESSION['Auth']) ? $_SESSION['Auth']['id'] : 0;
                $salesman_id = '';
                $distributor_id = '';

                if(isset($_SESSION['Auth']) && $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
                        $distributor_id = $auth_id;
                }
                else if(isset($_SESSION['Auth']) && $_SESSION['Auth']['User']['group_id'] == SALESMAN){
                        $salesman_id = $auth_id;
                        $distributor_id = $_SESSION['Auth']['dist_id'];
                }
                if(isset($_REQUEST['d']) && $this->General->dateValidate($_REQUEST['d']) != 1){
                        $this->send(array("status" => "failure",'code' => '1010','description' => "Invalid date"));
                        exit;
                }
		//if(isset($_SESSION['Auth']) && $_SESSION['Auth']['User']['group_id'] == RETAILER){
			/*$this->User->query("insert into app_req_log
					(method, params, user_id, timesatmp, date)
					values ('$method', '".json_encode($_REQUEST)."', '$auth_id', '".date('Y-m-d H:i:s')."', '".date('Y-m-d')."')");*/
		//}
		//else {
//			$this->User->query("insert into distributor_app_log
//				(method, params, distributor_id, salesman_id, timestamp, date)
//				values ('$method', '".json_encode($_REQUEST)."', '$distributor_id', '$salesman_id', '".date('Y-m-d H:i:s')."', '".date('Y-m-d')."')");
		//}
                $filename = "distributor.txt";
                $data = "LogId: " . $this->logId . ":::" . json_encode($_REQUEST) . " :: Server Info : " . json_encode($_SERVER);
                $this->General->logData($filename, $data);
	}

	function authenticate($params){
		$params['pin'] = $this->Auth->password($params['pin']);

		if(!empty($params['gcm_reg_id']) && !preg_match("/^[a-zA-Z0-9\-\_\:]*$/",$params['gcm_reg_id'])){
		    return array('status' => 'failure','code' => '71','description' => $this->Shop->apiErrors(71));
		}
                else if ($this->General->mobileValidate($params['mob']) == 1) {
                        return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
                } else if(!empty($params['d_id']) && !ctype_alnum(trim($params['d_id']))) {
                    return array('status' => 'failure','code' => '71','description' => $this->Shop->apiErrors(71));
                }
                else if(!empty($params['version_code']) && !is_numeric(trim($params['version_code']))){
                    return array('status' => 'failure','code'=>'71','description' =>$this->Shop->apiErrors(71));
                }
                else if(!empty($params['v']) && !$this->General->versionValidation(trim($params['v']))){
                    return array('status' => 'failure','code'=>'71','description' =>$this->Shop->apiErrors(71));
                }
                else if(!empty($params['d_t']) && !ctype_alnum(trim($params['d_t']))){
                    return array('status' => 'failure','code'=>'71','description' =>$this->Shop->apiErrors(71));
                }
                else if(!empty($params['man']) && !ctype_alnum(trim($params['man']))){
                    return array('status' => 'failure','code'=>'71','description' =>$this->Shop->apiErrors(71));
                }
                else if(!empty($params['latitude']) && !$this->General->floatValidation(trim($params['latitude']))){
                    return array('status' => 'failure','code'=>'71','description' =>$this->Shop->apiErrors(71));
                }
                else if(!empty($params['longitude']) && !$this->General->floatValidation(trim($params['longitude']))){
                    return array('status' => 'failure','code'=>'71','description' =>$this->Shop->apiErrors(71));
                }

                $users = $this->Slaves->query("SELECT users.*,user_groups.group_id
    			FROM users inner join user_groups ON (users.id = user_groups.user_id) WHERE users.mobile = '".$params['mob']."'
    			AND user_groups.group_id in (".DISTRIBUTOR.",".SALESMAN.") AND users.password = '".$params['pin']."'");

                //To update app version
                $app_version_code = empty($params['version_code']) ? "" : $params['version_code'];
                $app_type = 'distributor_app';
                $uuid = empty($params['d_id']) ? "" : $params['d_id'];
		$gcm_reg_id = empty($params['gcm_reg_id']) ? "" : $params['gcm_reg_id'];
		$device_type = empty($params['d_t']) ? "" : $params['d_t'];
		$device_ver = empty($params['v']) ? "" : $params['v'];
		$device_manufacturer = empty($params['man']) ? "" : $params['man'];
                $latitude  = empty($params['latitude']) ? "" : $params['latitude'];
                $longitude  = empty($params['longitude']) ? "" : $params['longitude'];
                $params['app_type'] = $app_type;
		$params['group_id'] = $users[0]['user_groups']['group_id'];
                if(isset($params['version_code']) && !empty($app_version_code)){

                    $update_version_code = $this->General->findVar("pay1_distributor_update_version");


                    if($update_version_code){

                        if($app_version_code < $update_version_code){

                            return array("status" => "failure", "code" => "48", "forced_upgrade_flag" => "1", "description" => $this->Shop->errors(48));

                        }
                    }
                }
                
                $getUserProfileData = $this->Shop->getUserInfo($params['mob'],$params['pin'],$params);

                if(empty($getUserProfileData[0]['users']['id'])):
                                     $returnData = array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                                     return $returnData;
                elseif($getUserProfileData[0]['users']['active_flag'] == 0):
                                $returnData = array('status' => 'failure','code'=>'404','description' =>$this->Shop->errors(404));
                                     return $returnData;
                elseif(empty($getUserProfileData[0]['user_profile']['id'])):
                        if ($app_version_code > 23) {
                                $otp_data = $this->Platform->sendOTPToUserDeviceMapping($params['mob'],0,$getUserProfileData[0]['users']['id']);
                                return $otp_data;
                        } else {
                                $this->User->query("INSERT INTO `shops`.`user_profile` (`id`,`user_id`, `gcm_reg_id`, `uuid`, `longitude`, `latitude`, `location_src` , `area_id`,`device_type` ,`version` ,`app_type`, `manufacturer`, `created`, `updated`,`date`) "
                                . "VALUES (NULL, ".$getUserProfileData['0']['users']['id'].", '$gcm_reg_id', '$uuid', '$longitude', '$latitude', '".$location_src."' ,'$area_id','".$device_type."' ,'".$device_ver."' ,'".$app_type."','".$device_manufacturer."' ,'".  date("Y-m-d H:i:s")."', '".  date("Y-m-d H:i:s")."','".date('Y-m-d')."')");
                        }
                endif;
                
		if(count($users) == 1){
			return $this->authenticateSalesman($params, $users);
		}
		else if(count($users) == 2){
			return $this->authenticateDistributor($params, $users);
		}
		else
			return array('status' => 'failure','code'=>'28','description' => $this->Shop->apiErrors('28'));
	}
        
        function resendOTPAuthenticate($params){
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
        
        function verifyOTPAuthenticate($params){
        
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
                $user_insert_data = $this->User->query("INSERT INTO `user_profile` (`id`,`user_id`, `uuid`,`app_type`,`created`, `updated`,`date`) " . "VALUES (NULL, " . $user_id . ",'$uuid','distributor_app','" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "','".date('Y-m-d')."');");

                return array('status'=>'success', 'description'=>'OTP Matched Successfully');
            }
            else {
                return array('status'=>'failure', 'code'=>'54', 'description'=>$this->Shop->apiErrors('54'));
            }
        }

	function logout(){
		if(isset($_SESSION['Auth']['id'])){
			if(session_destroy())return array('status'=>'success');
			else return array('status'=>'failure','code'=>'30','description'=>$this->Shop->errors(30));
		}else{
			return array('status'=>'success');
		}
	}

	function authenticateSalesman($params,$userData){

		$salesmen = $this->Slaves->query("select s.*,distributors.system_used,users.balance from salesmen s JOIN users ON (users.id = s.user_id) left join distributors ON (distributors.id = s.dist_id)
				where s.mobile = '".$params['mob']."'
				and s.active_flag = 1");
                $salesmen[0]['s']['balance'] = $salesmen[0]['users']['balance'];

                $passflag  =  $salesmen[0]['s']['passflag'];

                if($salesmen){
                    $salesman = $salesmen[0]['s'];

                    $uuid = empty($params['d_id']) ? "" : $params['d_id'];
                    $gcm_reg_id = empty($params['gcm_reg_id']) ? "" : $params['gcm_reg_id'];
                    $device_type = empty($params['d_t']) ? "" : $params['d_t'];
                    $device_ver = empty($params['v']) ? "" : $params['v'];
                    $device_manufacturer = empty($params['man']) ? "" : $params['man'];

			$this->Shop->setSalesmanDeviceData($params['mob'], array('trans_type' => $device_type, 'notification_key' => $gcm_reg_id));

                        $distributor_d = $this->Slaves->query("SELECT system_used FROM distributors WHERE id = ".$salesman['dist_id']);

			$info = $salesman;
                        $info['system_used'] = $distributor_d[0]['distributors']['system_used'];
			$info['User']['group_id'] = SALESMAN;
			$info['User']['id'] = $salesman['user_id'];
			$info['User']['mobile'] = $params['mob'];

			$info['vars']['trial_period'] = $this->General->findVar("trial_period");

			if($gcm_reg_id)
				$this->User->query("update salesmen set gcm_reg_id = '".$gcm_reg_id."' where id = '".$salesman['id']."'");
			$this->Session->write('Auth', $info);
			$this->General->setSessionToken($params['mob'],$this->Session->id(),$gcm_reg_id);
			return array(
				'status' 		=> 'success',
				'description' 	=> $info,
                                'passFlag'      => $passflag,
				'salesman' 		=> $salesman
// 				'retailers'		=> $this->retailersBySalesman($salesman['id'], $salesman['dist_id'])
			);
		}
		else {
			return array('status' => 'failure','code'=>'28','description' => $this->Shop->apiErrors('28'));
		}
	}

	public function authenticateDistributor($params, $users){

                $passflag  =  $users[0]['users']['passflag'];

                $distributors = $this->Slaves->query("select distributors.*,salesmen.*,users.balance from distributors JOIN users ON (users.id = distributors.user_id) left join salesmen ON (salesmen.mobile=distributors.mobile) where distributors.mobile = '".$params['mob']."'");
                $distributors[0]['distributors']['balance'] = $distributors[0]['users']['balance'];

                /** IMP DATA ADDED : START**/
                $temp = $this->Shop->getUserLabelData($params['mob'],2,1);
                $imp_data = $temp[$params['mob']];

                $dist_imp_label_map = array(
                    'pan_number' => 'pan_no',
                    'company' => 'shop_est_name',
                    'alternate_number' => 'alternate_mobile_no',
                    'email' => 'email_id'
                );
                foreach ($distributors[0]['distributors'] as $dist_label_key => $value) {
                    $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
                    if( array_key_exists($dist_label_key_mapped,$imp_data['imp']) ){
                        $distributors[0]['distributors'][$dist_label_key] = $imp_data['imp'][$dist_label_key_mapped];
                    }
                }
                /** IMP DATA ADDED : END**/



                $salesmen = $this->Slaves->query("select s.*,group_concat(sub.name) as subs
				from salesmen s
				left join salesmen_subarea ssa on(ssa.salesmen_id = s.id)
				left join subarea sub on(sub.id = ssa.subarea_id)
				WHERE s.dist_id = ".$distributors['0']['distributors']['id']."
				AND s.mobile != '".$params['mob']."'
				AND s.active_flag = 1
				group by s.id order by s.id asc  ");


                $default_salesman = $distributors['0']['salesmen'];

		$uuid = empty($params['d_id']) ? "" : $params['d_id'];
		$gcm_reg_id = empty($params['gcm_reg_id']) ? "" : $params['gcm_reg_id'];
		$device_type = empty($params['d_t']) ? "" : $params['d_t'];
		$device_ver = empty($params['v']) ? "" : $params['v'];
		$device_manufacturer = empty($params['man']) ? "" : $params['man'];

		$this->Shop->setSalesmanDeviceData($params['mob'], array('trans_type' => $device_type, 'notification_key' => $gcm_reg_id));

		if($gcm_reg_id)
			$this->User->query("update salesmen set gcm_reg_id = '".$gcm_reg_id."' where id = '".$default_salesman['id']."'");

		$info = $distributors['0']['distributors'];

		$info['User']['group_id'] = DISTRIBUTOR;
		$info['User']['id'] = $info['user_id'];
		$info['User']['mobile'] = $params['mob'];
		$info['User']['passflag'] = $passflag;

		$info['User']['auth_mobile'] = $uuid = $params['d_id'];

		$info['vars']['trial_period'] = $this->General->findVar("trial_period");


		$this->Session->write('Auth', $info);
		$this->General->setSessionToken($info['User']['id'],$this->Session->id(),$gcm_reg_id);
		return  array(
			'status'      	=> 'success',
			'description' 	=> $info,
			'distributor'	=> $distributors[0]['distributors'],
			'uuid'        	=> $uuid,
                        'passFlag'      => $passflag,
			'salesmen'	  	=> $salesmen
// 			'retailers'	  	=> $this->retailersByDistributor($info['id'])
		);
	}

	function testAPP(){
		$params = array(
				'r_id' => 13856,
				'a' => 10,
				't_t' => 1

		);
		//echo $this->transferBalance($params);
	}

	function transferBalance($params){
            if(!is_numeric($params['r_id'])){
                return array('status' => 'failure','code' => '1003', 'description' => "Invalid retailer id");
            }else if(isset($params['s_id']) && !is_numeric($params['s_id'])){
                return array('status' => 'failure','code' => '1004', 'description' => "Invalid salesman id");
            }else if($this->General->priceValidate($params['a']) == ''){
                return array('status'=>'failure','code'=>'6','description'=>$this->Shop->apiErrors(6));
            }else if(!is_numeric($params['t_t'])) {
                return array('status' => 'failure', 'code' => '150', 'description' => "Invalid typeradio");
            }else if(!ctype_alnum(str_replace(' ','',($params['note']))) && !empty($params['note'])) {
                return array('status' => 'failure', 'code' => '150', 'description' => "Invalid note");
            }

            if(isset($params['r_id']) && isset($params['a']) && isset($params['t_t'])){
			$transferBalanceArray['retailer'] = $params['r_id'];
			$transferBalanceArray['amount'] = $params['a'];
			$transferBalanceArray['typeRadio'] = $params['t_t'];
			$transferBalanceArray['description'] = $params['note'];
			$transferBalanceArray['group'] = DISTRIBUTOR;
			$transferBalanceArray['app_flag'] = 1;
                        if(isset($params['type'])) {
                                $transferBalanceArray['type'] = $params['type'];
                        }


                        if(!isset($params['type']) || $params['type'] == 'Retailer' || $params['g_id'] == SALESMAN) {
                                $retailers = $this->Slaves->query("select * from retailers where id = '".$params['r_id']."'");
                                if(empty($retailers)){
                                        $message = "Retailer does not exist.";
                                        return array("status" => "failure", 'description' => $message);
                                }
                                if ($retailers[0]['retailers']['maint_salesman'] != $_SESSION['Auth']['id'] && $retailers[0]['retailers']['parent_id'] != $_SESSION['Auth']['id']) {
                                        return array("status" => "failure", 'code'=>'61','description'=>$this->Shop->errors(61));
                                }
                                $retailer = $retailers[0]['retailers'];

                                if($retailer['block_flag'] != 0){
                                        $temp = $this->Shop->getUserLabelData($params['r_id'],2,2);
                                        $imp_data = $temp[$params['r_id']];
                                        // $message = "Retailer, ".$retailer['shopname'].", is blocked. Kindly call admin for any problem or to unblock it";
                                        $message = "Retailer, ".$imp_data['imp']['shop_est_name'].", is blocked. Kindly call admin for any problem or to unblock it";
                                        return array("status" => "failure", 'description' => $message);
                                }
                        } else if($params['g_id'] == DISTRIBUTOR && $params['type'] == 'Salesman') {
                                $sal = $this->Slaves->query("select * from salesmen where id = '".$params['r_id']."'");
                                if(empty($sal)){
                                        $message = "Salesman does not exist.";
                                        return array("status" => "failure", 'description' => $message);
                                }
                                if($sal[0]['salesmen']['dist_id'] != $_SESSION['Auth']['id']){
                                    return array("status" => "failure", 'code'=>'61','description'=>$this->Shop->errors(61));
                                }
                        }
//         			if($retailer['trial_flag'] == 2){
//         				$message = "Retailer, ".$retailer['shopname'].", is suspended temporarily as the trial period has ended. Kindly, submit the KYC documents to transfer balance";
//         				return array("status" => "failure", 'description' => $message);
//         			}
			if($params['g_id'] == SALESMAN){
				$salesmen = $this->Slaves->query("select salesmen.*,users.balance,distributors.user_id,distributors.system_used "
                                                                . "from salesmen "
                                                                . "inner join users on (users.id = salesmen.user_id)"
                                                                . " inner join distributors ON (distributors.id = salesmen.dist_id)"
                                                                . " where salesmen.id = '".$params['s_id']."'");
				$salesman = $salesmen['0']['salesmen'];

                                if(!isset($_SESSION['Auth']['system_used'])) {
                                        $_SESSION['Auth']['system_used'] = $salesmen[0]['distributors']['system_used'];
                                }

				if($retailer['maint_salesman'] != $salesman['id']){
                                        $message = "Retailer ".$retailer['shopname']." is not under you. You cannot transfer balance to him";
                                        return array("status" => "failure", 'description' => $message);
                                }

                                if(isset($salesman['block_flag']) && $salesman['block_flag'] == 1){
                                        $message = "Dear Salesman, your pay1 account is blocked now. Kindly call your manager to unblock it";
                                        return array("status" => "failure", 'description' => $message);
                                }



                                /*if($salesman['balance'] < $params['a']){
                                        $message = "Your balance transfer limit of Rs.".$salesman['tran_limit']." is exceeded. Kindly contact your distributor.";
                                        return array("status" => "failure", "description" => $message);
                                }*/
                                 if($_SESSION['Auth']['system_used'] == 0) {
                                    $distributor_balance = $this->Shop->getBalance($salesmen['0']['distributor']['user_id']);
                                    if($distributor_balance < $params['a']){
                                            $message = "Contact your distributor, he doesn't have enough balance.";
                                            return array("status" => "failure", "description" => $message);
                                    }
                                 }
                                 else {
                                     $distributor_balance = $this->Shop->getBalance($salesman['user_id']);
                                     if($distributor_balance < $params['a']){
                                         $message = "You don't have enough balance.";
                                         return array("status" => "failure", "description" => $message);
                                     }
                                 }
                                $transferBalanceArray['salesmanId'] = $params['s_id'];
                                $transferBalanceArray['salesmanName'] = $params['s_n'];
                                $transferBalanceArray['app_flag'] = 3;
                        }

			App::import('Controller', 'Shops');
			$ShopsController = new ShopsController;
			$ShopsController->constructClasses();

                        if(isset($_SESSION['Auth']['system_used'])) {
                                if($_SESSION['Auth']['system_used'] == 1) {
                                            return $ShopsController->amountTransferNew($transferBalanceArray, null);
                                } else if($_SESSION['Auth']['system_used'] == 0) {
                                        return $ShopsController->amountTransfer($transferBalanceArray, null);
                                }
                        } else {
                                return array("status" => "failure", "description" => "Some problem occured. Close your app and login again");
                        }
		}
		else {
			return array("status" => "failure", "description" => "Required parameters not set for transfer");
		}
	}

	function balance($params){
                if(!is_numeric($params['id'])) {
                        return array('status' => 'failure','code' => '1005','description' => "Invalid retailer id ");
                }else if(!is_numeric($params['g_id'])) {
                        return array('status' => 'failure','code' => '1007','description' => "Invalid group id ");
                }
                $dist = $this->Slaves->query("SELECT parent_id,maint_salesman FROM retailers WHERE id = '".$params['id']."'");
                if(isset($params['id']) && ($dist[0]['retailers']['parent_id'] == $_SESSION['Auth']['id'] || $dist[0]['retailers']['maint_salesman'] == $_SESSION['Auth']['id'])){
                        $data = $this->Shop->getShopDataById($params['id'],$params['g_id']);
		        $balance = $this->Shop->getBalance($data['user_id']);
			return array("status" => "success", "description" => $balance);
		}
		else {
			return array("status" => "failure", 'code'=>'61','description'=>$this->Shop->errors(61));
		}
	}

	function createRetailer($params){
		App::import('Controller', 'Shops');
		$ShopsController = new ShopsController;
		$ShopsController->constructClasses();

		return $ShopsController->createRetailer($params);
	}

	function retailerBalanceReport($params){

                if(!empty($params['s_id']) && !is_numeric($params['s_id'])) {
                        return array('status' => 'failure','code' => '1008','description' => "Invalid salesman id");
                } else if (!empty($params['r_id']) && !is_numeric($params['r_id'])) {
                        return array('status' => 'failure','code' => '1009','description' => "Invalid retailer id");
                } else if($this->General->dateValidate($params['d']) != 1){
                        return array('status' => 'failure','code' => '1010','description' => "Invalid date");
                }
                
		if(isset($params['d'])){
			App::import('Controller', 'Shops');
			$ShopsController = new ShopsController;
			$ShopsController->constructClasses();

                        if($this->Session->read('Auth.User.group_id') == DISTRIBUTOR){
				$distributor_id = $this->Session->read('Auth.id');
			}
			else {
				$distributor_id = $this->Session->read('Auth.dist_id');
			}

                        $dist_detail = $this->Slaves->query("SELECT system_used FROM distributors WHERE id = $distributor_id");

                        if(!isset($params['type'])) {
                                $params['type'] = null;
                        }

			return $ShopsController->salesmanReport($params['d'], $params['d'], $params['s_id'], $params['r_id'], $params['type'], $dist_detail[0]['distributors']['system_used']);
		}
		else
			return array("status" => "failure", "description" => "Provide proper date");
	}

	function pullback($params){

                App::import('Controller', 'Shops');
                $ShopsController = new ShopsController;
                $ShopsController->constructClasses();

                if(!is_numeric($params['shop_transid'])) {
                            return array('status' => 'failure','code' => '1011','description'=>"Invalid transaction id") ;
                } else if(!is_numeric($params['salesman_transid'])) {
                            return array('status' => 'failure','code' => '1012', 'description'=>"Invalid salesman transaction id") ;
                }

		if(isset($params['salesman_transid']) && isset($params['shop_transid'])){
                        if($params['salesman_transid'] == $params['shop_transid']) {
                                return $ShopsController->pullbackNew($params);
                        } else {
                                return $ShopsController->pullback($params);
                        }
		} else {
			return array("status" => "failure", "description" => "Specify a transaction");
		}
	}

	function uploadRetailerDocuments($params){
		$filename = "uploadRetailerDocuments_".date('Ymd').".txt";
		$this->General->logData('/mnt/logs/'.$filename,"inside uploadRetailerDocuments::".json_encode($params));
		$retailer_id = $params['r_id'];
		if($retailer_id){
			if($_FILES['addressProof'] || $_FILES['idProof'] || $_FILES['shop'] || $params['remove']){
				App::import('Controller', 'Shops');
				$ShopsController = new ShopsController;
				$ShopsController->constructClasses();

				$this->General->logData('/mnt/logs/'.$filename,"inside uploadRetailerDocuments::files".json_encode($_FILES));
				if($_FILES['addressProof']){
					$add_response = $ShopsController->uploadImages("addressProof", "addressProof_" . $retailer_id);
					$this->General->logData('/mnt/logs/'.$filename,"inside uploadRetailerDocuments::add_response".json_encode($add_response));
				}
				if($_FILES['idProof']){
					$id_response = $ShopsController->uploadImages("idProof", "idProof_" . $retailer_id);
					$this->General->logData('/mnt/logs/'.$filename,"inside uploadRetailerDocuments::id_response".json_encode($id_response));
				}
				if($_FILES['shop']){
					$shop_response = $ShopsController->uploadImages("shop", "shop_" . $retailer_id);
					$this->General->logData('/mnt/logs/'.$filename,"inside uploadRetailerDocuments::shop_response".json_encode($shop_response));
				}
				if($params['remove']){
					foreach($params['remove'] as $imgURL){
							$remove_response[] = $this->Shop->deleteDocument($imgURL);
					}
					$this->General->logData('/mnt/logs/'.$filename,"inside uploadRetailerDocuments::remove_response".json_encode($remove_response));
				}
				$this->setToPendingVerification($retailer_id);
				$this->General->update_verify_flag($retailer_id);
				$this->General->logData('/mnt/logs/'.$filename,"inside uploadRetailerDocuments::after verify flag update");
				return array(
					"status" => "success",
					"description" => "Uploaded",
					"addressProof_response" => $add_response,
					"idProof_response" => $id_response,
					"shopPhotos_response" => $shop_response,
					"removal_response" => $remove_response
				);
			}
			else
				return array("status" => "failure", "description" => "No image found");
		}
		else
			return array("status" => "failure", "description" => "No retailer specified");
	}

	function uploadKYCDocuments($params){
		$filename = "uploadKYCDocuments".date('Ymd').".txt";
		$this->General->logData('/mnt/logs/'.$filename,"inside uploadKYCDocuments::".json_encode($params));
		if(!is_numeric($params['r_id'])){
                    return array('status' => 'failure', 'description' => "Invalid retailer id");
                }
                $retailer_id = $params['r_id'];

		if($retailer_id){
			$kycSectionMap = $this->Shop->kycSectionMap();
			$document_types = array();
			$reverseMap = array();
			foreach($kycSectionMap as $section_id => $ksp){
				$document_types = array_merge($document_types, $ksp['documents']);
				foreach($ksp['documents'] as $document_type){
					$reverseMap[$document_type] = $section_id;
				}
			}
			$response = array();
			$send_message = false;
			$this->General->logData('/mnt/logs/'.$filename,"inside uploadKYCDocuments before upload::files::".json_encode($_FILES));

			App::import('Controller', 'Shops');
			$ShopsController = new ShopsController;
			$ShopsController->constructClasses();
			foreach($document_types as $type){
				if($_FILES[$type]){
					$response[$type] = $ShopsController->uploadImages($type, $type . "_" . $retailer_id);
					$this->General->logData('/mnt/logs/'.$filename,"inside uploadKYCDocuments after upload::files::".json_encode($response[$type]));
					if($response[$type][0]['status'] == "success"){
						$this->Shop->setKYCState($retailer_id, $reverseMap[$type], 0);
						$send_message = true;
					}
				}
			}
			if($send_message){
				$retailers = $this->Slaves->query("select *
						from retailers r
						where r.id = ".$retailer_id);
				$message = "You are one step closer to Toll free calling. Your information is under review.";
				$this->General->sendMessage($retailers[0]['r']['mobile'], $message, 'notify');
			}
			$this->General->logData('/mnt/logs/'.$filename,"inside uploadKYCDocuments before remove::files::".json_encode($params));
			if(!empty($params['remove'])){
				foreach($params['remove'] as $src){
					$verified_retailers_docs = $this->User->query("select * from retailers_docs
							where src = '$src'");
					if(!empty($verified_retailers_docs)){
						$this->User->query("delete from retailers_details
								where image_name = '$src'");
						$response['remove'][] = "$src removed";
					}
					else
						$response['remove'][] = $this->Shop->deleteDocument($src);
				}
			}
			$this->General->logData('/mnt/logs/'.$filename,"inside uploadKYCDocuments final response::files::".json_encode($response));
			return array(
				"status" => "success",
				"description" => $response
			);
		}
		else
			return array("status" => "failure", "description" => "No retailer specified");
	}

	function setToPendingVerification($retailer_id){
		$image_details = $this->User->query("select * from retailers_details where retailer_id = $retailer_id");
		$images_count = array(
				'addressProof' => 0,
				'idProof' => 0,
				'shop' => 0
		);
		foreach($image_details as $id){
			if(in_array($id['retailers_details']['type'], array('addressProof')))
				$images_count['addressProof'] += 1;
			else if(in_array($id['retailers_details']['type'], array('idProof')))
				$images_count['idProof'] += 1;
			else if(in_array($id['retailers_details']['type'], array('shop')))
				$images_count['shop'] += 1;
		}
		if($images_count['addressProof'] AND $images_count['idProof'] AND $images_count['shop']){
			$this->User->query("update retailers
					set verify_flag = 2,
					modified = '".date('Y-m-d H:i:s')."'
					where id = $retailer_id");
			$this->User->query("update unverified_retailers
					set documents_submitted = 1,
					modified = '".date('Y-m-d H:i:s')."'
					where retailer_id = $retailer_id");
			$retailers = $this->Slaves->query("select * from retailers
					where id = $retailer_id");
			$subject = "Documents submitted for retailer mobile no. ".$retailers[0]['retailers']['mobile'];
			$body = "Verify this retailer. Click <a href='https://cc.pay1.in/panels/retailerVerification/".$retailer_id."'>here</a> to verify.";
			$this->General->sendMails($subject, $body, array('sohail.shaikh@pay1.in'), 'mail');
		}
	}

	function getRetailer($params){

                if(!is_numeric($params['r_id'])){
                        return array('status' => 'failure','code' => '1013', 'description' => "Invalid retailer id");
                }
                $retailer_id = $params['r_id'];

		if($retailer_id){
			$retailers = $this->Slaves->query("select r.*, ur.*, up.latitude, up.longitude
	    			from retailers r
	    			left join unverified_retailers ur on ur.retailer_id = r.id
					left join user_profile up on (up.user_id = r.user_id AND up.updated = (select max(updated) from user_profile as up1 where up1.user_id = up.user_id))
	    			where r.id = '".$retailer_id."'");

                        if($retailers[0]['r']['parent_id'] != $_SESSION['Auth']['id'] && $retailers[0]['r']['maint_salesman'] != $_SESSION['Auth']['id']){
                                return array('status' => 'failure', 'code'=>'61', 'description' => $this->Shop->errors(61));
                        }
                        
                /** IMP DATA ADDED : START**/
                    $temp = $this->Shop->getUserLabelData($retailer_id,2,2);
                    $imp_data = $temp[$retailer_id];
                    $retailer_imp_label_map = array(
                        'pan_number' => 'pan_no',
                        'shopname' => 'shop_est_name',
                        'alternate_number' => 'alternate_mobile_no',
                        'email' => 'email_id',
                        'shop_structure' => 'shop_ownership',
                        'shop_type' => 'business_nature'
                    );
                    foreach ($retailers[0]['r'] as $retailer_label_key => $value) {
                        $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                        if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                            $retailers[0]['r'][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
                        }
                    }
                    foreach ($retailers[0]['ur'] as $retailer_label_key => $value) {
                        $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                        if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                            $retailers[0]['ur'][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
                        }
                    }
                /** IMP DATA ADDED : END**/

            $retailer = $this->retailer($retailers[0], true);

			return array("status" => "success", 'description' => $retailer);
		}
		else {
			return array("status" => "failure", "description" => "No retailer specified");
		}
	}

	function editRetailer($retailer){
	        if(!empty($retailer['r_id']) && !is_numeric($retailer['r_id'])){
                    return array('status' => 'failure','code' => '1014', 'description' => "Invalid retailer id");
	        }else if (!empty($retailer['r_a']) && !ctype_alnum(str_replace(' ','',$retailer['r_a']))){
                    return array('status' => 'failure','code' => '1015','description' =>"Invalid area");
                }else if(!empty($retailer['d_uid']) && !is_numeric($retailer['d_uid'])){
                    return array('status' => 'failure','code' => '1016', 'description' => "Invalid distributor id");
                }else if(!empty($retailer['r_n']) && !ctype_alnum(str_replace(' ','',$retailer['r_n']))){
                    return array('status' => 'failure','code' => '1017', 'description' => "Invalid retailer name");
                }else if(!empty($retailer['s_n']) && !ctype_alnum(str_replace(' ','',$retailer['s_n']))){
                    return array('status' => 'failure','code' => '1018', 'description' => "Invalid shop name");
                }else if(!empty($retailer['r_add']) && !ctype_alnum(str_replace(' ','',$retailer['r_add']))){
                    return array('status' => 'failure','code' => '1019', 'description' => "Invalid retailer address");
                }

		if(isset($retailer['r_id'])){
			$this->data['dist'] = $retailer['d_uid'];

			$authData = $this->Session->read('Auth');
			if($authData['User']['group_id'] == SALESMAN){
				$distributor = $this->Shop->getShopDataById($authData['dist_id'], DISTRIBUTOR);
			}
			else if($authData['User']['group_id'] == DISTRIBUTOR){
				$distributor = $this->Shop->getShopDataById($authData['id'], DISTRIBUTOR);
			}
			else if($authData['User']['group_id'] == RETAILER){
				$distributor = $this->Shop->getShopDataById($authData['parent_id'], DISTRIBUTOR);
			}

			$retailer_data = $this->Slaves->query("	select *
                                                    from retailers r
                                                    where r.id = ".$retailer['r_id']);

			if($distributor['id'] != $retailer_data['0']['r']['parent_id']){
				return array("status" => "failure", "description" => "You cannot edit details of this retailer");
			}

			$retailer_id = $retailer['r_id'];
			$this->data['Retailer']['name'] = $retailer['r_n'];
			$this->data['Retailer']['shopname'] = $retailer['s_n'];
			$this->data['Retailer']['address'] = $retailer['r_add'];
			if(isset($retailer['l_t'])){
				$location_type_index = array_search($retailer['l_t'], $this->Shop->location_typeTypes());
				if($location_type_index){
					$this->data['Retailer']['location_type'] = $location_type_index;
				}
			}
			$this->data['Retailer']['shop_type_value'] = $retailer['s_t'];
			if(isset($retailer['s_t'])){
				$shop_type_index = array_search($retailer['s_t'], $this->Shop->business_natureTypes());
				if(!$shop_type_index){
					$this->data['Retailer']['shop_type'] = 8;

				}
				else {
					$this->data['Retailer']['shop_type'] = $shop_type_index;
				}
			}
 
			$this->data['Retailer']['latitude'] = $retailer['r_la'];
			$this->data['Retailer']['longitude'] = $retailer['r_lo'];
			$this->data['Retailer']['pin'] = $retailer['r_pc'];

			$this->data['address']['update'] = "true";
			$this->data['address']['address'] = $retailer['r_add'];
			$this->data['address']['area'] = $retailer['r_a'];
			$this->data['address']['city'] = $retailer['r_c'];
			$this->data['address']['state'] = $retailer['r_s'];
			$this->data['address']['pincode'] = $retailer['r_pc'];
			$this->data['address']['latitude'] = $retailer['r_la'];
			$this->data['address']['longitude'] = $retailer['r_lo'];

			$current_unverified_retailer = $this->Slaves->query("	select *
													from unverified_retailers ur
													where ur.retailer_id = ".$retailer['r_id']);

			$kycSectionMap = $this->Shop->kycSectionMap();
			$changed_sections = array();
			foreach($kycSectionMap as $section_id => $ksm){
				$changed_sections[$section_id] = 0;
				foreach($ksm['fields'] as $field){
					if($field == 'area_id'){
						$area = $this->Slaves->query("select *
								from locator_area a
								where a.name = '".$this->data['address']['area']."'");
						if($area[0]['a']['id'] != $current_unverified_retailer[0]['ur'][$field]){
							$changed_sections[2] = 1;
						}
					}
					else {
						if($this->data['Retailer'][$field] != $current_unverified_retailer[0]['ur'][$field]){
							$changed_sections[$section_id] = 1;
						}
					}
				}
			}

			foreach($changed_sections as $section_id => $flag){
				if($flag == 1){
					$this->Shop->setKYCState($retailer_id, $section_id, 0);
				}
			}

			$update_query = "update unverified_retailers set ";

			foreach($this->data['Retailer'] as $k => $r){
				if($r){
                    $imp_update_data[$k] = $r;
					$update_query .= " $k = '$r', ";
				}
			}

			$update_query .= " modified = '".date('Y-m-d H:i:s')."'
							where retailer_id = ".$retailer_id;

            $this->User->query($update_query);

            /** IMP DATA ADDED : START**/
            $this->Shop->updateUserLabelData($retailer_id,$imp_update_data,$retailer_id,2);
            /** IMP DATA ADDED : END**/

			$this->General->updateRetailerAddress($retailer_id, $retailer['description']['user_id'], $this->data['address']);

			$retailer = $this->getRetailer(array("r_id" => $retailer_id));

			return array("status" => "success", "description" => array("retailer" => $retailer['description']));
		}
		else
			return array("status" => "failure", "description" => "No retailer specified");
	}

	function updateRetailer($retailer){
		$rand = rand(0,10000);
		$this->General->logData('/mnt/logs/updateRetailer.txt',"i m at first line:$rand:". json_encode($retailer));
		if(isset($retailer['r_id'])){
			$this->data['dist'] = $retailer['d_uid'];

			$authData = $this->Session->read('Auth');
			if($authData['User']['group_id'] == SALESMAN){
				$distributor = $this->Shop->getShopDataById($authData['dist_id'], DISTRIBUTOR);
			}
			else if($authData['User']['group_id'] == DISTRIBUTOR){
				$distributor = $this->Shop->getShopDataById($authData['id'], DISTRIBUTOR);
			}
			else if($authData['User']['group_id'] == RETAILER){
				$distributor = $this->Shop->getShopDataById($authData['parent_id'], DISTRIBUTOR);
			}

			$retailer_current_data = $this->Slaves->query("select *
															from retailers r
															where r.id = ".$retailer['r_id']);

			if($distributor['id'] != $retailer_current_data['0']['r']['parent_id']){
				return array("status" => "failure", "description" => "You cannot edit details of this retailer");
			}

// 			if($retailer_current_data['0']['r']['rental_flag'] != 0 && $retailer['r_t'] == 0 && $distributor['kits'] == 0){
// 				return array("status" => "failure", "description" => "You have 0 kits left. Buy more retailer kits to enjoy this benefit");
// 			}
			$retailer_id = $retailer['r_id'];
			$this->data['Retailer']['name'] = $retailer['r_n'];
			$this->data['Retailer']['shop_name'] = $retailer['s_n'];
			$this->data['Retailer']['address'] = $retailer['r_add'];
			if(isset($retailer['l_t'])){
				$location_type_index = array_search($retailer['l_t'], $this->Shop->location_typeTypes());
				if($location_type_index){
					$this->data['Retailer']['location_type'] = $location_type_index;
				}
			}

			$this->data['Retailer']['rental_flag'] = $retailer['r_t'];

			if(isset($retailer['s_t'])){
				$shop_type_index = array_search($retailer['s_t'], $this->Shop->business_natureTypes());
				if(!$shop_type_index){
					$this->data['Retailer']['shop_type'] = 8;
					$this->data['Retailer']['shop_type_value'] = $retailer['s_t'];
				}
				else {
					$this->data['Retailer']['shop_type'] = $shop_type_index;
				}
			}
			$this->data['Retailer']['latitude'] = $retailer['r_la'];
			$this->data['Retailer']['longitude'] = $retailer['r_lo'];

			$this->data['address']['update'] = "true";
			$this->data['address']['address'] = $retailer['r_add'];
			$this->data['address']['area'] = $retailer['r_a'];
			$this->data['address']['city'] = $retailer['r_c'];
			$this->data['address']['state'] = $retailer['r_s'];
			$this->data['address']['pincode'] = $retailer['r_pc'];
			$this->data['address']['latitude'] = $retailer['r_la'];
			$this->data['address']['longitude'] = $retailer['r_lo'];

			$this->data['Retailer']['modified'] = date('Y-m-d H:i:s');

			$this->General->logData('/mnt/logs/updateRetailer.txt',"Data is formed here:$rand:". json_encode($this->data));

			$kyc_check_map = array(
					'shop_name' => 'shopname',
					'area_id' 	=> 'area_id',
					'address' 	=> 'address',
					'pin_code'	=> 'pin'
			);

			$update_query = "update unverified_retailers set ";

			$i = 0;
			$needs_verification = false;
			foreach($this->data['Retailer'] as $k => $r){
				if($r){
					$update_query .= " $k = '$r'";
					if ($i != count($this->data['Retailer']) - 1) {
						$update_query .= ", ";
					}
				}
				if($retailer_current_data['0']['r'][$kyc_check_map[$k]] && $r != $retailer_current_data['0']['r'][$kyc_check_map[$k]]){
					$needs_verification = true;
				}
				$i++;
			}
			$update_query .= " where retailer_id = ".$retailer_id;

			$this->User->query($update_query);
			$this->General->logData('/mnt/logs/updateRetailer.txt',"Update query $rand is $update_query");

			$this->setToPendingVerification($retailer_id);
			if($needs_verification)
				$this->User->query("update retailers set verify_flag = 0 where id = ".$retailer_id);

			$retailer = $this->getRetailer(array("r_id" => $retailer_id));
			$this->General->logData('/mnt/logs/updateRetailer.txt',"Before update retailer address $rand::".json_encode($retailer));

			$this->General->updateRetailerAddress($retailer_id, $retailer['description']['user_id'], $this->data['address']);

			$this->General->logData('/mnt/logs/updateRetailer.txt',"after update retailer address $rand::".json_encode($retailer));

			return array("status" => "success", "description" => array("retailer" => $retailer['description']));
		}
		else
			return array("status" => "failure", "description" => "No retailer specified");
	}

	function getRetailers($params){
	    if(!empty($params['salesman_id']) && !is_numeric($params['salesman_id'])){
	        return array('status' => 'failure','code' => '1014', 'description' => "Invalid salesman id");
	    }
	    else if(!empty($params['distributor_id']) && !is_numeric($params['distributor_id'])){
	        return array('status' => 'failure','code' => '1014', 'description' => "Invalid distributor id");
	    }
//	    else if(!empty($params['modified']) && !$this->General->timeValidate($params['modified'])){
//	        return array('status' => 'failure','code' => '1014', 'description' => "Invalid parameter");
//	    }
                $salesman_id = isset($params['salesman_id'])?$params['salesman_id']:'';
                $distributor_id = isset($params['distributor_id'])?$params['distributor_id']:'';

		$logger = $this->General->dumpLog('SERVER', 'Pay1ChannelPartner');
		$logger->info("getRetailers just inside method -:- ".date('Y-m-d H:i:s'). " -:- ".$salesman_id." / ".$distributor_id." -:- ".json_encode($params));
		if(!empty($distributor_id)){
			if(isset($params['modified']) && $params['modified'])
				$params['modified'] = date("Y-m-d H:i:s", $params['modified']);
			if(!empty($salesman_id)){
				$retailers = $this->retailersBySalesman($salesman_id, $distributor_id, $params['modified']);
			}
			else {
				$retailers = $this->retailersByDistributor($distributor_id, $params['modified']);
			}
			$logger->info("getRetailers after retailers fetched -:- ".date('Y-m-d H:i:s'). " -:- ".$salesman_id." / ".$distributor_id." -:- ".json_encode($retailers));
			if($retailers)
				return array("status" => "success", "description" => $retailers);
			else
				return array("status" => "failure", "description" => "No retailer found");
		}
		else
			return array("status" => "failure", "description" => "No distributor specified");
	}

	function retailersByDistributor($distributor_id, $modified = null){
		$logger = $this->General->dumpLog('SERVER', 'Pay1ChannelPartner');
		$logger->info("retailersByDistributor just inside method -:- ".date('Y-m-d H:i:s'). " -:- ".$distributor_id." -:- ".$distributor_id.":".$modified);
		$retailers = array();
		if(isset($distributor_id)){
			$logger->info("retailersByDistributor before query -:- ".date('Y-m-d H:i:s'). " -:- ".$distributor_id." -:- ".$distributor_id.":".$modified);
			$time_query = "";
			if($modified)
// 				$time_query = " and (case
// 									when r.modified > up.updated
// 									then r.modified > '".$modified."'
// 									else ( CASE WHEN up.updated is null THEN r.created > '".$modified."' ELSE up.updated > '".$modified."' END)
// 								end) ";
				$time_query = " and (r.modified > '".$modified."' or up.updated > '".$modified."' or ur.modified > '".$modified."')";
            	$retailers_list = $this->Slaves->query("select r.id, r.parent_id, r.maint_salesman, r.mobile, r.name, r.shopname, r.verify_flag, r.created, r.trial_flag,
					 ur.name, ur.shop_name, ur.shopname, ur.latitude, ur.longitude, up.latitude, up.longitude
	    			from retailers r
					left join unverified_retailers ur on ur.retailer_id = r.id
	    			left join user_profile up on (up.user_id = r.user_id and up.updated = (select max(updated) from user_profile as up1 where up1.user_id = r.user_id))
                    where r.toshow = 1 and r.parent_id = '".$distributor_id."'".$time_query);


                /** IMP DATA ADDED : START**/
                $ret_ids = array_map(function($element){
                    return $element['r']['id'];
                },$retailers_list);

                $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
                $retailer_imp_label_map = array(
                    'pan_number' => 'pan_no',
                    'shopname' => 'shop_est_name',
                    'shop_name' => 'shop_est_name',
                    'alternate_number' => 'alternate_mobile_no',
                    'email' => 'email_id',
                    'shop_structure' => 'shop_ownership',
                    'shop_type' => 'business_nature'
                );
                foreach ($retailers_list as $index => $ret_data) {
                    foreach ($ret_data['r'] as $retailer_label_key => $value) {
                        $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                        if( array_key_exists($retailer_label_key_mapped,$imp_data['imp'][$value['id']]) ){
                            $retailers_list[$index]['r'][$retailer_label_key] = $imp_data['imp'][$value['id']][$retailer_label_key_mapped];
                        }
                    }
                }
                /** IMP DATA ADDED : END**/


            	$logger->info("retailersByDistributor after query -:- ".date('Y-m-d H:i:s'). " -:- ".$distributor_id." -:- ".json_encode($retailers_list));
			foreach($retailers_list as $r)
				$retailers[] = $this->retailer($r);
		}
		$logger->info("retailersByDistributor before return -:- ".date('Y-m-d H:i:s'). " -:- ".$distributor_id." -:- ".json_encode($retailers));
		return $retailers;
	}

	function retailersBySalesman($salesman_id, $distributor_id, $modified = null){
		$logger = $this->General->dumpLog('SERVER', 'Pay1ChannelPartner');
		$logger->info("retailersBySalesman just inside method -:- ".date('Y-m-d H:i:s'). " -:- ".$salesman_id." -:- ".$salesman_id.":".$distributor_id.":".$modified);
		$retailers = array();
		if(isset($salesman_id) && isset($distributor_id)){
			$logger->info("retailersBySalesman before query -:- ".date('Y-m-d H:i:s'). " -:- ".$salesman_id." -:- ".$salesman_id.":".$distributor_id.":".$modified);
			$time_query = "";
			if($modified)
// 				$time_query = " and (case
// 									when r.modified > up.updated
// 									then r.modified > '".$modified."'
// 									else ( CASE WHEN up.updated is null THEN r.created > '".$modified."' ELSE up.updated > '".$modified."' END)
// 								end) ";
				$time_query = " and (r.modified > '".$modified."' or up.updated > '".$modified."' or ur.modified > '".$modified."')";
				$retailers_list = $this->Slaves->query("select r.id, r.parent_id, r.maint_salesman, r.mobile, r.name, r.shopname, r.verify_flag, r.created, r.trial_flag,
					 ur.name, ur.shop_name, ur.shopname, ur.latitude, ur.longitude, up.latitude, up.longitude
	    			from retailers r
					left join unverified_retailers ur on ur.retailer_id = r.id
	    			left join user_profile up on (up.user_id = r.user_id and up.updated = (select max(updated) from user_profile as up1 where up1.user_id = r.user_id))
					left join salesmen s on s.id = r.maint_salesman and r.parent_id = s.dist_id
	    			where r.toshow = 1 and r.maint_salesman = '".$salesman_id."'
                    and r.parent_id = '".$distributor_id."'".$time_query);

                    /** IMP DATA ADDED : START**/
                    $ret_ids = array_map(function($element){
                        return $element['r']['id'];
                    },$retailers_list);

                    $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
                    $retailer_imp_label_map = array(
                        'pan_number' => 'pan_no',
                        'shopname' => 'shop_est_name',
                        'alternate_number' => 'alternate_mobile_no',
                        'email' => 'email_id',
                        'shop_structure' => 'shop_ownership',
                        'shop_type' => 'business_nature'
                    );
                    foreach ($retailers_list as $index => $ret_data) {
                        foreach ($ret_data['r'] as $retailer_label_key => $value) {
                            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                            if( array_key_exists($retailer_label_key_mapped,$imp_data['imp'][$value['id']]) ){
                                $retailers_list[$index]['r'][$retailer_label_key] = $imp_data['imp'][$value['id']][$retailer_label_key_mapped];
                            }
                        }
                    }
                    /** IMP DATA ADDED : END**/

				$logger->info("retailersBySalesman after query -:- ".date('Y-m-d H:i:s'). " -:- ".$salesman_id." -:- ".json_encode($retailers_list));
			foreach($retailers_list as $r)
				$retailers[] = $this->retailer($r);
		}
		$logger->info("retailersBySalesman before return -:- ".date('Y-m-d H:i:s'). " -:- ".$salesman_id." -:- ".json_encode($retailers));
		return $retailers;
	}

	function retailer($r, $editFlag = false){
		foreach($r['ur'] as $key => $row){
			if($key != 'id')
				$r['r'][$key] = $r['ur'][$key];
		}

		$retailer = $r['r'];
		if($editFlag){
			if(!empty($retailer['shop_type'])){
				$shop_type = $this->Shop->business_natureTypes($retailer['shop_type']);
				if($shop_type == "Others")
					$retailer['shop_type'] = $retailer['shop_type_value'];
				else
					$retailer['shop_type'] = $shop_type;
			}
			if(!empty($retailer['location_type']))
				$retailer['location_type'] = $this->Shop->location_typeTypes($retailer['location_type']);
			$retailer_image_detail = $this->Slaves->query("select rd.*
	        				from retailers_details rd
	        				where retailer_id = ".$retailer['id']);

			$location = $this->General->get_location_by_area_id($retailer['area_id']);
			$retailer['area'] = isset($location) ? $location['area'] : "";
			$retailer['city'] = isset($location) ? $location['city'] : "";
			$retailer['state'] = isset($location) ? $location['state'] : "";
			$retailer['image_detail'] = $this->filterDocuments($retailer_image_detail);

			$retailer_kyc_states = $this->Slaves->query("select rks.*
					from retailers_kyc_states rks
					where rks.retailer_id = ".$retailer['id']);
			$retailer['kyc_states'] = $this->kycStateDocuments($retailer_kyc_states);
		}

		return $retailer;
	}

	function kycStateDocuments($retailer_kyc_states){
		$map = $this->Shop->kycSectionMap();
		foreach($retailer_kyc_states as $krid => $rid){
			$retailer_id = $rid['rks']['retailer_id'];
			$types = implode("','", $map[$rid['rks']['section_id']]['documents']);
			$retailer_kyc_states[$krid]['rks']['documents'] = $this->Slaves->query("select rd.*
							from retailers_details rd
							where rd.retailer_id = ".$retailer_id."
							and rd.type in ('".$types."')");
		}

		return $retailer_kyc_states;
	}

	function filterDocuments($retailer_image_detail){
		$documents = array();
		foreach($retailer_image_detail as $krid => $rid){
			if($rid['rd']['type'] == "idProof"){
				if($rid['rd']['verify_flag'] == 1)
					$documents['idProof'][1][0] = $krid;
				else
					$documents['idProof'][0][0] = $krid;
			}
			if($rid['rd']['type'] == "addressProof"){
				if($rid['rd']['verify_flag'] == 1)
					$documents['addressProof'][1][0] = $krid;
				else
					$documents['addressProof'][0][0] = $krid;
			}
			if($rid['rd']['type'] == "shop"){
				if($rid['rd']['verify_flag'] == 1)
					$documents['shop'][1][] = $krid;
				else
					$documents['shop'][0][] = $krid;
			}
		}
		$details = array();
		if(count($documents) > 0){
			if(count($documents['idProof'][0]) > 0){
				if(isset($retailer_image_detail[$documents['idProof'][1][0]]))
					unset($retailer_image_detail[$documents['idProof'][1][0]]);
			}
			if(count($documents['addressProof'][0]) > 0){
				if(isset($retailer_image_detail[$documents['addressProof'][1][0]]))
					unset($retailer_image_detail[$documents['addressProof'][1][0]]);
			}
			if(count($documents['shop'][0]) > 0){
				if(isset($retailer_image_detail[$documents['shop'][1][0]]))
					unset($retailer_image_detail[$documents['shop'][1][0]]);
				if(isset($retailer_image_detail[$documents['shop'][1][1]]))
					unset($retailer_image_detail[$documents['shop'][1][1]]);
				if(isset($retailer_image_detail[$documents['shop'][1][2]]))
					unset($retailer_image_detail[$documents['shop'][1][2]]);
				if(isset($retailer_image_detail[$documents['shop'][1][3]]))
					unset($retailer_image_detail[$documents['shop'][1][3]]);
			}
		}

		return $retailer_image_detail;
	}

	/*function encryptSalesmenPins(){
		$crypt = $this->Auth->password("1234");
		$this->User->query("update salesmen set password = '".$crypt."'");//cf496949e943a5f1a1adf5296f50e5edd2785ba4
		echo "done";
		exit;
	}*/

	function sendOTP($params){
		if ($this->General->mobileValidate($params['m']) == 1) {
                        return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
		}
                $mobile = $params['m'];
		if($mobile){
			$users = $this->Slaves->query("select * from users
					where mobile = '".$mobile."'");
			$send_otp_flag = false;
			if(empty($users)){
				return array("status" => "failure", "description" => "You are not registered as a Distributor or Salesman at PAY1");
			}
			else {
				$send_otp_flag = true;
			}
			if($send_otp_flag){
				$otp = $this->General->generatePassword(6);

//				$message = "Use OTP ".$otp." to reset pin.
//    						Do not share it with anyone";

                                $paramdata['OTP'] = $otp;
                                $MsgTemplate = $this->General->LoadApiBalance();
                                $content =  $MsgTemplate['Distributors_OTP_MSG'];
                                $message = $this->General->ReplaceMultiWord($paramdata,$content);


				$this->General->sendMessage($mobile, $message, 'payone', null);
				$this->Shop->setMemcache("otp_resetPin_$mobile", $otp, 30*60);

				return array("status" => "success", "description" => "OTP: ".$otp);
			}
		}
		return array("status" => "failure", "description" => "No mobile number specified");
	}

	function resetPin($params){
            $mobile = $params['m'];
            
            if(isset($_SESSION['Auth']['mobile']) && $mobile != $_SESSION['Auth']['mobile']){
                return array("status" => "failure", 'code'=>'61','description'=>$this->Shop->errors(61));
            }
            if ($this->General->mobileValidate($params['m']) == 1) {
                return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
            }

        $otp = $params['otp'];
        $pin = $params['pin'];
        $MsgTemplate = $this->General->LoadApiBalance();
        if(strlen($otp) != 6 || trim($pin) == ""){
        	return array('status' => 'failure', 'code'=>'E026', 'description' => $this->Shop->apiErrors('E026'));
        }

        if(trim($mobile)){
            if($otp == $this->Shop->getMemcache("otp_resetPin_$mobile") || !$this->General->isOTPRequired($mobile)){
        		$crypt = $this->Auth->password($pin);
        		$users = $this->Slaves->query("select * from users
        			where mobile = '".$mobile."'");
        		if($users){
        			$this->User->query("update users
        					set password = '".$crypt."'
        					where mobile = '".$mobile."'");

//        			$message = "Your PIN was reset from Pay1 Channel Partner app. Your new PIN is ".$pin;
                                $paramdata['PIN'] = $pin;
                                $content =  $MsgTemplate['Distributors_Pin_ResetOrChange_MSG'];
                                $message = $this->General->ReplaceMultiWord($paramdata,$content);
        			$this->General->sendMessage($mobile, $message, 'payone', null);

        			return array("status" => "success", "description" => "Pin reset is successful");
        		}
        		else {
        			return array('status' => 'failure', 'description' => "Mobile number is not registered with Pay1");
        		}
        	}
        	else
        		return array('status' => 'failure','code'=>'E027','description' => $this->Shop->apiErrors('E027'));
        }
        else
        	return array('status' => 'failure', 'code'=>'E024', 'description' => "Mobile ".$this->Shop->apiErrors('E024'));
	}

	function changePin($params){
                $mobile = $params['m'];
            
                if($mobile != $_SESSION['Auth']['mobile']){
                    return array("status" => "failure", 'code'=>'61','description'=>$this->Shop->errors(61));
                }
                
		$pin = $params['pin'];
		$new_pin = $params['new_pin'];

		if(trim($pin) == "" || trim($new_pin) == ""){
			return array('status' => 'failure','code' => '1020', 'description' => "Empty pin");
		}else if(!ctype_alnum($pin)){
                        return array('status' => 'failure', 'code' => '50', 'description' => $this->Shop->apiErrors(50));
                }else if(!ctype_alnum($new_pin)){
                        return array('status' => 'failure','code' => '1021','description' => "invalid pin");
                }else if ($this->General->mobileValidate($mobile) == '1') {
			return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
                }

		$MsgTemplate = $this->General->LoadApiBalance();
		$pin_crypt = $this->Auth->password($pin);
		$new_pin_crypt = $this->Auth->password($new_pin);

		if(trim($mobile)){
			$users = $this->Slaves->query("select * from users
        			where mobile = '".$mobile."'
        			and password = '".$pin_crypt."' AND id = ".$this->Session->read('Auth.User.id'));

			if(empty($users)){
			    return array('status' => 'failure', 'description' => "Wrong username or password");
			}
			else if($users[0]['users']['active_flag'] == 0){
			    return array('status' => 'failure', 'description' => "User is not active");
			}
			else {
                                $this->User->query("update users
        				set password = '".$new_pin_crypt."', modified = '".  date("Y-m-d H:i:s")."', password_change = '".  date("Y-m-d H:i:s")."', passflag = 1
        				where mobile = '".$mobile."'");

//				$message = "Your PIN was reset from Pay1 Channel Partner app. Your new PIN is ".$new_pin;
                                $paramdata['PIN'] = $new_pin;
                                $content =  $MsgTemplate['Distributors_Pin_ResetOrChange_MSG'];
                                $message = $this->General->ReplaceMultiWord($paramdata,$content);
				$this->General->sendMessage($mobile, $message, 'payone', null);

				return array("status" => "success", "description" => "Pin reset for distributor");
			}

		}
		else
			return array('status' => 'failure', 'code'=>'E024', 'description' => "Mobile ".$this->Shop->apiErrors('E024'));
	}

	function lastBalanceTransfer($params){
                if(!is_numeric($params['r_id'])){
                    return array('status' => 'failure','code' => '1022', 'description' => "Invalid retailer id");
                }

		if(isset($params['r_id'])){
			$retailer_id = $params['r_id'];
			$type = "2,25,26";
			$comm_type = 7;

			$transactions = $this->Slaves->query("SELECT st1.id,st1.amount,st1.note,st1.timestamp
								FROM shop_transactions as st1
								WHERE st1.target_id = $retailer_id
								AND st1.type in ($type) AND st1.date >='".date('Y-m-d',strtotime('-7 days'))."'
								order by st1.id desc limit 1");
			if($transactions){
				$lastTransaction = array(
					"amount" => $transactions[0]['st1']['amount'],
					"time_stamp" => $transactions[0]['st1']['timestamp']
				);
				return array("status" => "success", "description" => $lastTransaction);
			}
			else
				return array("status" => "failure", "description" => "No transactions");
		}
		else
			return array("status" => "failure", "description" => "No retailer specified");
	}

	/*function changeAndSendSalesmanPins(){
		$salesmen = $this->User->query("select * from salesmen");
                $MsgTemplate = $this->General->LoadApiBalance();
		foreach($salesmen as $salesman){
			$password = $this->General->generatePassword(4);
			$crypt = $this->Auth->password($password);

			$this->User->query("update salesmen set password = '".$crypt."' where mobile = '".$salesman['salesmen']['mobile']."'");
//			$message = 	"You can login to Pay1 Channel Partner Android App with pin: $password. Kindly, change your pin from the app.";

                        $paramdata['PASSWORD'] = $password;
                        $content =  $MsgTemplate['Salesman_Pin_ChangeAndSend_MSG'];
                        $message = $this->General->ReplaceMultiWord($paramdata,$content);

                        $this->General->sendMessage($salesman['salesmen']['mobile'], $message, 'shops');
		}
		echo "Done";
		exit;
	}*/

	/*function setUnverifiedRetailers(){
		$c = 0;
		do {
			$offset = $c * 1000;
			$retailers = $this->User->query("select * from retailers r
					left join user_profile up on up.user_id = r.user_id and up.device_type = 'web'
					group by r.id
					limit $offset, 1000");
			$query = "insert into unverified_retailers
					(retailer_id, name, shop_name, area_id, area, address, pin_code, latitude, longitude, rental_flag, shop_type,
					shop_type_value, documents_submitted, created, modified)
					values ";
			$i = 1;
			$retailers_count = count($retailers);

			foreach($retailers as $r){
				$shop_type_value = ($r['r']['shop_type'] == 8) ? $r['r']['mobile_info'] : "";
				$documents_submitted = $r['r']['verify_flag'] ? 1 : 0;
				$query .= "('".$r['r']['id']."', '".mysql_real_escape_string($r['r']['name'])."',
						'".mysql_real_escape_string($r['r']['shopname'])."', '".$r['r']['area_id']."',
						'".mysql_real_escape_string($r['r']['area'])."', '".mysql_real_escape_string($r['r']['address'])."',
						'".$r['r']['pin']."', '".$r['up']['latitude']."', '".$r['up']['longitude']."',
						'".$r['r']['rental_flag']."', '".$r['r']['shop_type']."',
						'".mysql_real_escape_string($shop_type_value)."', '".$documents_submitted."',
						'".$r['r']['created']."', '".$r['r']['modified']."')";
				if($i != $retailers_count)
					$query .= ", ";
				$i++;
			}
			if($retailers_count > 0)
				$this->User->query($query);
			$c++;
		}
		while(!empty($retailers));
		echo "Done";
		exit;
	}*/

	function createTrialRetailer($params){
	    if(isset($_SESSION['Auth']['dist_id'])){
	        $dist_id = $_SESSION['Auth']['dist_id'];
	    }
	    else {
	        $dist_id = $_SESSION['Auth']['id'];
	    }
	        if ($params['distributor_id'] != $dist_id){
                    return array('status' => 'failure','code'=>'61','description' => $this->Shop->errors('61'));
                }
                
                if ($this->General->mobileValidate($params['mobile']) == 1) {
                        return array('status' => 'failure', 'code' => '67', 'description' => $this->Shop->apiErrors(67));
		}else if(!is_numeric($params['distributor_id'])) {
                        return array('status' => 'failure','code' => '1023', 'discription'=>"Invalid distributor id");
                }else if(!ctype_alnum(str_replace(array(' ','_','-'),'',$params['shop_name']))) {
                        return array('status' => 'failure','code' => '1024', 'discription'=>"Invalid shop name");
                }else if(!is_numeric($params['app_version_code'])) {
                        return array('status' => 'failure','code' => '1025', 'discription'=>"Invalid version code");
                }
                
		$retailer['api_flow'] = "trial";
		$retailer['shop_name'] = $params['shop_name'];
		$retailer['mobile'] = $params['mobile'];
                $retailer['otp'] = isset($params['otp']) ? $params['otp'] : "";
                $retailer['otp_verify_flag'] = isset($params['otp_verify_flag']) ? $params['otp_verify_flag'] : "";
		$retailer['name'] = "";
                $retailer['retailer_type']  = $params['retailer_type'];
                $retailer['location_type']  = $params['location_type'];
                $retailer['turnover_type']  = $params['turnover_type'];
                $retailer['ownership_type'] = $params['ownership_type'];

                //For new app update feature
                $retailer['app_version_code'] = isset($params['app_version_code']) ? $params['app_version_code'] : "";
                
		$distributors = $this->Slaves->query("select * from distributors where id = ".$params['distributor_id']);
		$retailer['distributor_user_id'] = $distributors[0]['distributors']['user_id'];

                if($retailer['mobile'] && $retailer['shop_name'] && $retailer['distributor_user_id']){

                    ob_start();  //for output buffering

                    App::import('Controller', 'Shops');
                    $ShopsController = new ShopsController;
                    $ShopsController->constructClasses();
                    $response = $ShopsController->createRetailer($retailer);

                    ob_end_clean();

                    return $response;

		}
		else
			return array("status" => "failure", "description" => "Parameters missing");
	}

	function serverLog($params){
		if(isset($params['mobile'])){
			$logger = $this->General->dumpLog('APP', 'Pay1ChannelPartner');
			$logger->info($params['title']." -:- ".$params['time']. " -:- ".$params['mobile']." -:- ".$params['message']);
		}
	}

	function banksAndTransferTypes($params){
		$banks = $this->Slaves->query("select *
				from bank_details
				where visible_to_distributor_flag = 1");
		$bank_names = array();
		foreach($banks as $bank){
			$bank_names[] = $bank['bank_details']['bank_name'];
		}
		$data = array(
				"banks" => $bank_names,
				"transfer_types" => array(
						"NEFT-RTGS:NEFT/RTGS",
						"ATM-Transfer:ATM-Transfer",
						"CASH:CASH",
						"Cheque:Cheque"
				)
		);

		return array("status" => "success", "description" => $data);
	}

	function sendBalanceTopupRequest($params){
//                $this->General->logData("/mnt/logs/fileupload.log",json_encode($params).json_encode($_REQUEST).json_encode($_FILES));
		if(empty($params['bank_acc_id']) || empty($params['trans_type_id'])){
			return array("status" => "failure",'code' => '1026', "description" => "Fields cannot be left empty");
		}
		if($this->General->priceValidate($params['amount']) == ''){
			return array("status" => "failure",'code' => '1027', "description" => "Invalid amount ");
		}

		if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
			$distributor_id = $_SESSION['Auth']['id'];
		}
		else if($_SESSION['Auth']['User']['group_id'] == SALESMAN){
			$distributor_id = $_SESSION['Auth']['dist_id'];
		}
                $imgUrl = '';
                if($_FILES['bank_slip']['name'] != '') {
                        if($_FILES['bank_slip']['size'] > 5000000) {   // 5 MB
                                return array('status' => 'failure','description' => 'File size should not be more than 5 MB');
                        } else {
                                $img_name = 'bank_slip';
                                $imgUrl   = $this->uploadImage($img_name);
                        }
                }
                        if(!is_numeric($distributor_id)){
                                return array('status' => 'failure', 'description' => "Enter valid distributor id");
                        }
		$distData = $this->Slaves->query("select *
						from distributors
						left join users on users.id = distributors.user_id
                        where distributors.id = ".$distributor_id);

        /** IMP DATA ADDED : START**/
        $temp = $this->Shop->getUserLabelData($distributor_id,2,3);
        $imp_data = $temp[$distributor_id];
        $distData['0']['distributors']['company'] = $imp_data['imp']['shop_est_name'];
        /** IMP DATA ADDED : END**/

		$message = "We have received your request. You will get your topup in sometime";
		$body = "Distributor Shop Name ".$distData['0']['distributors']['company']." deposited Rs
				".$params['amount']." in our ".$params['bank_acc_id']." account (TransID:
				".$params['bank_trans_id'].")<br/>Mobile: ".$distData['0']['users']['mobile'];
		$this->General->sendMails($sub,$body,array('limits@pay1.in'));
		$data1 = array();
		$data1['time'] =  date("Y-m-d H:i:s");
		$data1['msg'] =  $body;
		$data1['sender'] =  "PAY1";
		$data1['process'] =  "limits";
                $data1['id'] = $distData['0']['distributors']['id'];
		$data1['type'] = "Distributor";
		$data1['name'] = $distData['0']['distributors']['company'];
		$data1['mobile'] = $distData['0']['users']['mobile'];
		$data1['amount'] = $params['amount'];
		$data1['transid'] = $params['bank_acc_id'] . "_".$params['bank_trans_id'] . "_" . $params['trans_type_id'];
                $data1['bank_details'] = '';
                if($params['branch_name'] != '' || $params['branch_code'] != '' || $imgUrl != '') {
                        $data1['bank_details'] = json_encode(array(
                                                    'branch_name' => $params['branch_name'],
                                                    'branch_code' => $params['branch_code'],
                                                    'bank_slip'   => $imgUrl
                                                ));
                }

                $this->General->curl_post($this->General->findVar('limit_url'), $data1);
//		$this->General->curl_post('http://apptesting.pay1.in/limits/server.php',$data1);

		return array("status" => "success", "description" => $message);
	}

        function uploadImage($image_name, $bucket=s3limitBucket) {

                $rand1     = rand(1000,9999);
                $rand2     = rand(1000,9999);
                $exp       = explode('.', $_FILES[$image_name]['name']);
                $file_name = 'limits_'.$rand1.strtotime(date('YmdHis')).$rand2.'.'.$exp[count($exp)-1];

                App::import('vendor', 'S3', array('file' => 'S3.php'));
                $s3 = new S3(awsAccessKey, awsSecretKey);
                $s3->putObjectFile($_FILES[$image_name]['tmp_name'], $bucket, $file_name, S3::ACL_PUBLIC_READ);

                return 'http://' . $bucket . '.s3.amazonaws.com/' . $file_name;
        }

	function bankAccounts($params){
		$accounts = $this->Slaves->query("select *
				from bank_details
				where visible_to_distributor_flag = 1");
		$accounts_table = array();
		foreach($accounts as $key => $row){
			$account = array();
			$account["bank"] = $row["bank_details"]["bank"];
			$account["account_no"] = $row["bank_details"]["account_no"];
			$account["transfer_modes"] = $row["bank_details"]["transfer_modes"];
			$account["account_name"] = $row["bank_details"]["account_name"];
			$account["account_type"] = $row["bank_details"]["account_type"];
			$account["ifsc"] = $row["bank_details"]["ifsc"];
			$account["branch"] = $row["bank_details"]["branch"];

			$accounts_table[] = $account;
		}

		return array("status" => "success", "description" => $accounts_table);
	}

}

?>
