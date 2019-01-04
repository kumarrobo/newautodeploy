<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $helpers = array('Html','Ajax','Javascript','Minify');
	var $uses = array('User','Group', 'Slaves');
	var $components = array('RequestHandler','Shop','Platform');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
		
	}

	function add() {
		$this->set('groups', $this->User->Group->find('list'));

		if (!empty($this->data)) {
                    $this->data['User']['password_change'] = date('Y-m-d');

                    $this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
                   
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}


	//for change of retailer number,pwd sending
	/*function sendPassword1()
	{
		//echo "In users controller ";
		$password = $this->General->generatePassword(4);
		//echo "password is ".$password;
		$this->General->sendPassword($_REQUEST['mobile'],$password,0);
		echo $password;
		$this->autoRender=false;

	}*/

	function curl(){
		if($_REQUEST['type'] == 'GET'){
			foreach ($_REQUEST as $key => &$val) {
				$post_params[] = $key.'='.urlencode($val);
			}
			$post_string = implode('&', $post_params);
			$out = $this->General->curl_post($_REQUEST['url']."?".$post_string,null,'GET');	
		}
		else {
			$out = $this->General->curl_post($_REQUEST['url'],$_REQUEST);
		}
		echo $out['output'];
		$this->autoRender=false;
	}
	
	function app(){
		$this->autoRender=false;
		$agent = $_SERVER['HTTP_USER_AGENT'];
		
		if(stripos($agent,"Android")){
			$this->redirect('https://play.google.com/store/apps/details?id=com.mindsarray.pay1');
		}
		else if(stripos($agent,"Windows Phone 8")){
			$this->redirect('https://www.windowsphone.com/en-us/store/app/pay1-merchant/44aefe8a-fff1-40b9-8a2e-76c835339fdc');
		}
		else if(stripos($agent,"Windows Phone")){
			$this->redirect('https://www.windowsphone.com/en-us/store/app/pay1-merchant/7763f5f3-6404-41af-bf82-9659c0c5e3c7');
		}
		else if(stripos($agent,"MIDP")){
			$this->redirect(DISTPANEL_URL.'apis/downloadApp/'.RETAILER_APP_FILE_2);
		}
		else {
			$html = "<a href='https://play.google.com/store/apps/details?id=com.mindsarray.pay1'>Android Application</a>";
			$html .= "<br/><a href='https://www.windowsphone.com/en-us/store/app/pay1-merchant/44aefe8a-fff1-40b9-8a2e-76c835339fdc'>Windows8 Application</a>";
			$html .= "<br/><a href='https://www.windowsphone.com/en-us/store/app/pay1-merchant/7763f5f3-6404-41af-bf82-9659c0c5e3c7'>Windows7 Application</a>";
			$html .= "<br/><a href=".DISTPANEL_URL."'apis/downloadApp/".RETAILER_APP_FILE_2."'>Java Application</a>";
			$html .= "<br/><a href='".RETPANEL_URL."'>Retailer Web Link</a>";
			
			echo $html;
		}
	}
	
    /*function forgotPasswordCheck($direct=null,$mobile=null){

		if(!isset($_SESSION['Auth']['User']['group_id']))
		$this->redirect(array('action' => 'index'));
		if($mobile != null)$this->data['User']['mobile'] = $mobile;
		$exists = $this->General->checkIfUserExists($this->data['User']['mobile']);
		$this->Session->write('displayDiv','forgotPassword');
		if($exists){
			if($direct == '1' || $strtolower($this->data['User']['captchaText']) == $this->Session->read('security_code'))	{
				$this->data['User']['mobile'] = $this->data['User']['mobile'];
				$check = $this->User->find('first',array('fields' => array('syspass','dnd_flag'),'conditions' => array('mobile' => $this->data['User']['mobile'])));
				if(empty($check['User']['syspass']) || $check['User']['syspass'] == 'NULL' || SENDFLAG == '0'){
					$password = $this->General->generatePassword(4); //generate 4 character password
				}
				else {
					$password = $check['User']['syspass'];
				}

				$this->data['User']['password'] = $this->Auth->password($password); //encrypted password using hash salt
				//echo "<script>showLoader2('messagePopUpDiv');</script>";
				if($this->updatePassword($this->data['User']['mobile'],$password,'change')){
					if($direct == null){
						if(($check['User']['dnd_flag'] == 0 &&  $this->General->checkTimeSlot()) || TRANS_FLAG){
							$this->Session->setFlash(__('The password has been sent to your mobile. <br>Enter your password to sign in<br><br>', true));
							$this->set('mobile',$this->data['User']['mobile']);
							//					 /$this->set('forgot', 0);
							$this->render('/elements/login_user','ajax');
						}
						else if($check['User']['dnd_flag'] == 1){
							echo "Your number is registered in DND. Due to new Telecom regulations we cannot send you SMS. Kindly de-register by calling 1909";
							$this->autoRender = false;
						}
						else {
							echo "Due to New Telecom regulations we cannot send you SMS this time. Kindly re-try " . TIME_SLOT_PERIOD . " only ";
							$this->autoRender = false;
						}
					}
						
				}
			}
			else {
				$this->Session->setFlash(__('You have entered wrong code. Please try again<br><br>', true));
				echo "<script>$('popUpDiv').hide();forgetPassword();</script>";
				$this->autoRender = false;
			}

		}
		else {
			echo 'You are not registered. <a href="javascript:void(0);" onclick="register();">Click here to register.</a>';
			$this->autoRender = false;
		}

		//echo "<script> centerPos('popUpDiv');</script>";
	}


	function changeDetails(){
		if(!isset($_SESSION['Auth']['User']['group_id']))
		$this->redirect(array('action' => 'index'));
		$this->data['User']['id'] = $this->Session->read('Auth.User.id');
		if ($this->User->save($this->data)) {
			$_SESSION['Auth']['User']['name'] = $this->data['User']['name'];
			$_SESSION['Auth']['User']['email'] = $this->data['User']['email'];
			$_SESSION['Auth']['User']['gender'] = $this->data['User']['gender'];
			$_SESSION['Auth']['User']['city'] = $this->data['User']['city'];
			$_SESSION['Auth']['User']['dob'] = $this->data['User']['dob']['year'].'-'.$this->data['User']['dob']['month'].'-'.$this->data['User']['dob']['day'];
			$this->Session->setFlash(__('Details Saved Successfully', true));
			$this->render('/elements/personal_details','ajax');
		}
		else {
			$this->Session->setFlash(__('Please enter proper email id', true));
			$this->render('/elements/personal_details','ajax');
		}

	}


	function userPasswordChange(){
		if(!isset($_SESSION['Auth']['User']['group_id']))
		$this->redirect(array('action' => 'index'));
		$this->Session->write('param','pass');
		$this->redirect("/users/view/");
	}

	function register() {
		$this->render('/users/register','ajax');
	}


	function passwordChange(){
		$this->render('/elements/change_password','ajax');
	}
	 * 
	 * 
	 * 
	 * * * */
	 
	function changePassword($par=null){
		if(!isset($_SESSION['Auth']['User']['group_id']))
		$this->redirect(array('action' => 'index'));
		//all other checkings should be from javascript side

		//here i am assuming all other data is correct
		$this->User->recursive = -1;
		$password = $this->User->find('first', array('fields' => array('User.password'), 'conditions' => array('User.mobile' => $this->Session->read('Auth.User.mobile'))));

		if($password['User']['password'] != $this->Auth->password($this->data['User']['pass1']))	{
			$this->set('errFlag','1');
			if($par != null){
				$this->set('par',$par);
			}
			$this->render('/elements/change_password','ajax');
		}
		else{
			if($this->updatePassword($this->Session->read('Auth.User.mobile'),$this->data['User']['pass2'],'change','in')){
				echo "<script>alert('Password changed successfully');</script>";
                                echo '<script>window.location.href = "/shops/logout";</script>';
			}
			else {
				echo "Password can not be changed";
			}

			$this->autoRender = false;
		}
	}
	 
	 
	function resetPassword($mobile){
		/*if(!in_array($_SESSION['Auth']['User']['group_id'],array(ADMIN,RETAILER,DISTRIBUTOR,MASTER_DISTRIBUTOR)))
			$this->redirect(array('action' => 'index'));*/
			
		$password = $this->General->generatePassword(4);
                $this->updatePassword($mobile, $password, 'new',"resetPass");
		//$this->User->query("UPDATE users SET password = '".$this->Auth->password($password)."'  WHERE mobile = '$mobile'");
		$msg = "Dear Sir,\nYour password has been reset to $password";
		return $msg;
	}
	
	function updatePassword($mobile,$password,$type,$changeP=null){


		if($type == "new" || $changeP == null){//system generated
			$passFlag = '0';
			$sysPass = $password;
		}
		else if($type == "change"){
			$passFlag = '1';
			$sysPass = 'NULL';
		}

		if($this->User->query("UPDATE users SET password='".$this->Auth->password($password)."',passflag='$passFlag',syspass='$sysPass', password_change='".date('Y-m-d')."' WHERE mobile='$mobile'")){
		//if($this->User->updateAll(array('User.password' => "'".$this->Auth->password($password)."'", 'User.passflag' => $passFlag, 'User.syspass' => "$sysPass"), array('User.mobile' => $mobile))){
			if($changeP == null)
			$this->General->sendPassword($mobile,$password,0);

			if($this->Session->read('Auth.User')){
				$_SESSION['Auth']['User']['passflag'] = $passFlag;
			}

			return true;
		}

		return false;
	}

	function afterLogin($params = array()){
            ob_clean();
            $this->autoRender = false;
            
            isset($params['user_id']) && $_POST = $params;
            $response = array();
            $this->data = null;
            $this->data['User']['mobile'] = $_POST['mobile'];
            $this->data['User']['password'] = $this->Auth->password($_POST['password']);
            $this->User->recursive =  - 1;
            $uuid = $this->Shop->getMemcache("uuid_distributor_web_".$params['user_id']);
            if (isset($params['user_id']) && $uuid != '') {
                $this->Shop->delMemcache("uuid_distributor_web_".$params['user_id']);
                $usrData = $this->User->find('first', array('conditions'=>array('User.mobile'=>$this->data['User']['mobile'])));
            } else {
                $uuid = $_POST['uuid'];
                $usrData = $this->User->find('first', array('conditions'=>array('User.mobile'=>$this->data['User']['mobile'], 'User.password'=>$this->data['User']['password'])));
            }
            if(empty($usrData)){
                $response['status'] = "FALSE";
                $response['errors'] = array('code'=>'E000', 'msg'=>'Invalid LoginID or Password');
            }
            else if($usrData['User']['active_flag'] == 0){
                $response['status'] = "FALSE";
                $response['errors'] = array('code'=>'E000', 'msg'=>'User is inactive');
            }
            else{

                $group_id = (isset($_POST['group_id']) ? $_POST['group_id'] : 0);
                if($group_id == 0){
                    $user_groups = $this->User->query("SELECT group_id FROM user_groups WHERE user_id = " . $usrData['User']['id'] . " AND group_id not IN (".LENDER.",".BORROWER.",".SALESMAN.") limit 1");
                    $group_id = $user_groups['0']['user_groups']['group_id'];   
                }
                else {
                    $user_groups = $this->User->query("SELECT group_id FROM user_groups WHERE user_id = " . $usrData['User']['id'] . " AND group_id = $group_id");
                    $group_id = $user_groups['0']['user_groups']['group_id'];
                }

                if(empty($group_id)){
                    $response['status'] = "FALSE";
                    $response['errors'] = array('code'=>'E000', 'msg'=>'You are not logged in right role');
                    echo json_encode($response);
                    die();
                }

                $user_groups_all = $this->User->query("SELECT group_concat(group_id) as grps FROM user_groups WHERE user_id = " . $usrData['User']['id']);
                $group_ids = $user_groups_all['0']['0']['grps'];


                $info = $this->Shop->getShopData($usrData['User']['id'], $group_id);
                $info['User']['group_id'] = $group_id;
                $info['User']['group_ids'] = $group_ids;
                $info['User']['id'] = $usrData['User']['id'];
                $info['User']['mobile'] = $usrData['User']['mobile'];
                $info['User']['name'] = $usrData['User']['name'];
                $info['User']['passflag'] = $usrData['User']['passflag'];

                $group_access = $this->User->query("SELECT * FROM `groups` WHERE id = '$group_id' AND `outside_access` = '1'");

                if(in_array($_SERVER['SERVER_NAME'], Configure::read('login_domains'))){
                    $office_ips = explode(",", OFFICE_IPS);
                    $client_ip = $this->General->getClientIP();

                    if(in_array($client_ip, $office_ips) || $group_access || $usrData['User']['outside_access'] == 1){

                        $getUserProfileData = $this->User->query("SELECT id FROM user_profile WHERE user_id = '".$usrData['User']['id']."' AND uuid = '".$uuid."' AND app_type = 'distributor_web'");
                        
                        if(empty($getUserProfileData) && in_array($group_id, array(SUPER_DISTRIBUTOR, DISTRIBUTOR))){
                            $this->Shop->setMemcache("uuid_distributor_web_".$usrData['User']['id'], $uuid, 30*60);
                            $otp_data = $this->Platform->sendOTPToUserDeviceMapping($info['User']['mobile'],0,$info['User']['id']);
                            $response['status'] = "FALSE";
                            $response['errors'] = array('code'=>$otp_data['code'], 'user_id'=>$otp_data['user_id'], 'msg'=>$otp_data['description']);
                        } else {
                            $this->Session->write('Auth', $info);
                            $agent = $this->General->findUserAgent($_SERVER['HTTP_USER_AGENT']);
                            $this->General->setSessionToken($info['User']['id'],$this->Session->id(),$agent);	
                            $this->User->query("UPDATE `users` SET `last_login`= '".date('Y-m-d')."' WHERE `id` =".$info['User']['id']);
                            $response['status'] = "TRUE";
                            $response['success'] = array('code'=>'S001', 'msg'=>'Login Successful');
                        }
                    }
                    else{
                        $response['status'] = "FALSE";
                        $response['errors'] = array('code'=>'E001', 'msg'=>'Invalid Access Location');
                    }
                }

                // else if(in_array($usrData['User']['group_id'],array(CUSTCARE)) && $_SERVER['SERVER_NAME'] != 'cc.pay1.in'){
                else if( ! in_array($info['User']['group_id'], array(ADMIN, MASTER_DISTRIBUTOR, SUPER_DISTRIBUTOR, DISTRIBUTOR, RELATIONSHIP_MANAGER, VENDOR, RETAILER)) &&  ! in_array($_SERVER['SERVER_NAME'], Configure::read('login_domains'))){
                    $response['status'] = "FALSE";
                    $response['errors'] = array('code'=>'E002', 'msg'=>'Invalid Access Location');
                }
                else{
                    $this->Session->write('Auth', $info);
                    $agent = $this->General->findUserAgent($_SERVER['HTTP_USER_AGENT']);
                    $this->General->setSessionToken($info['User']['id'],$this->Session->id(),$agent);
                    $this->User->query("UPDATE `users` SET `last_login`= '".date('Y-m-d')."' WHERE `id` =".$info['User']['id']);
                    $response['status'] = "TRUE";
                    $response['success'] = array('code'=>'S001', 'msg'=>'Login Successful');
                }
            }

            if (isset($params['user_id']) && $response['status'] == "TRUE") {
                $this->redirect('/shops/view');
            } else {
                echo json_encode($response);
                die();
            }
        }
        
        function verifyLoginOtp ($mobile = null,$user_id = 0, $group_id = 0) {
            $this->set('mobile',$mobile);
            $this->set('user_id',$user_id);
            $this->set('group_id',$group_id);
        }
        
        function resendOTPAuthenticate(){
            $this->autoRender = false;

            $mobile = $_POST['mobile'];
            $user_id = $_POST['user_id'];

            $otp_data = $this->Platform->sendOTPToUserDeviceMapping($mobile,0,$user_id);
            
            echo json_encode($otp_data);die;
        }
        
        function verifyOTPAuthenticate(){
        
            $params = $_POST;
            
//            if(!ctype_alnum($params['uuid']) || !is_numeric($params['user_id'])) {
            if(!is_numeric($params['user_id'])) {
                    $this->Session->setFlash("* Invalid uuid or user_id");
                    $this->redirect("/users/verifyLoginOtp/{$params['mobile']}/{$params['user_id']}/{$params['group_id']}");
            }else if(!is_numeric($params['group_id'])) {
                    $this->Session->setFlash("* Invalid Group");
                    $this->redirect("/users/verifyLoginOtp/{$params['mobile']}/{$params['user_id']}/{$params['group_id']}");
            } else if($this->General->mobileValidate($params['mobile']) == 1) {
                    $this->Session->setFlash("* ".$this->Shop->apiErrors(67));
                    $this->redirect("/users/verifyLoginOtp/{$params['mobile']}/{$params['user_id']}/{$params['group_id']}");
            } else if(!is_numeric($params['otp'])) {
                    $this->Session->setFlash("* Invalid OTP");
                    $this->redirect("/users/verifyLoginOtp/{$params['mobile']}/{$params['user_id']}/{$params['group_id']}");
            } 

            $user_mobile = $params['mobile'];
            $user_id = $params['user_id'];
            $group_id = $params['group_id'];
            $uuid = $this->Shop->getMemcache("uuid_distributor_web_".$params['user_id']);
            $otp = $params['otp'];
            //$params['group_id'] = DISTRIBUTOR;

            $user_exists = $this->User->query("select * from users where mobile = '" . $user_mobile . "' AND id = '$user_id'");
            if(empty($user_exists)) {
                $this->Session->setFlash("* ".$this->Shop->apiErrors('49'));
            } else {
                if($otp == $this->Shop->getMemcache("otp_userProfileNewUuid_$user_mobile") || !$this->General->isOTPRequired($user_mobile)){
                    $this->Shop->delMemcache("otp_userProfileNewUuid_$user_mobile");
                    $user_insert_data = $this->User->query("INSERT INTO `user_profile` (`id`,`user_id`, `uuid`,`app_type`,`created`, `updated`,`date`) " . "VALUES (NULL, " . $user_id . ",'$uuid','distributor_web','" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "','".date('Y-m-d')."');");
                    $this->afterLogin($params);
                }
                else {
                    $this->Session->setFlash("* ".$this->Shop->apiErrors('54'));
                }
            }
            
            $this->redirect("/users/verifyLoginOtp/$user_mobile/$user_id/$group_id");
        }
    
	function rightHeader(){
		$this->render('/elements/right_header','ajax');
	}

	function er404($response){

        if(!isset($_SESSION['Auth']['User'])){
		
		  $this->redirect('/');
		}
		$this->set('response',$response);
		$this->layout = 'er404';
	}

	/*function initDB() {
		set_time_limit(0);
		ini_set("memory_limit","-1");

	 $group =&$this->User->Group;
	 //Allow admins to everything
	 $group->id = 2;
	 $this->Acl->allow($group, 'controllers');
	  
	 //author permissions
	 $group->id = 3;
	 $this->Acl->deny($group, 'controllers');

	 //user permissions
	 $group->id = 1;
	 $this->Acl->deny($group, 'controllers');

	 $member_acl['Users'] = array('index','add','edit','delete');

	 foreach($member_acl as $controller => $actions){
	 	$this->Acl->allow($group, 'controllers/'.$controller);
	 	foreach($actions as $action){
	 		$this->Acl->deny($group, 'controllers/'.$controller.'/'.$action);
	 	}
	 }

	 $superdistributor_acl['Shops'] = array('topupReceipts','printRequest','issue','issueReceipt','backReceipt','printReceipt','printInvoice', 'script','view','formDistributor','backDistributor','backDistEdit','createDistributor','allotCards','backAllotment','allotRetailCards','allRetailer','editRetailer','showDetails','editDistValidation','changePassword','accountHistory','cardsAllotted','invoices','transfer','amountTransfer','backTransfer');
	 $distributor_acl['Shops'] = array('topupReceipts','printRequest','issue','issueReceipt','backReceipt','printReceipt','printInvoice', 'script', 'view','activateCards','backActivation','activateRetailCards','allRetailer','editRetailer','showDetails','editRetValidation','formRetailer','backRetailer','createRetailer','changePassword','accountHistory','cardsAllotted','cardsActivated','invoices','transfer','amountTransfer','backTransfer');
	 $retailer_acl['Shops'] = array('topupReceipts','printRequest','issue','issueReceipt','backReceipt','printReceipt','setSignature','saveSignature','printInvoice', 'script', 'view','changePassword','accountHistory','cardsSold','cardsActivated','invoices','retailerProdActivation');

	 //super distributor permissions
	 $group->id = 4;
	 $this->Acl->deny($group, 'controllers');

	 foreach($member_acl as $controller => $actions){
		 $this->Acl->allow($group, 'controllers/'.$controller);
		 foreach($actions as $action){
		 	$this->Acl->deny($group, 'controllers/'.$controller.'/'.$action);
		 }
	 }
	 foreach($superdistributor_acl as $controller => $actions){
		 foreach($actions as $action){
		 	$this->Acl->allow($group, 'controllers/'.$controller.'/'.$action);
		 }
	 }

	 //distributor permissions
	 $group->id = 5;
	 $this->Acl->deny($group, 'controllers');

	 foreach($member_acl as $controller => $actions){
		 $this->Acl->allow($group, 'controllers/'.$controller);
		 foreach($actions as $action){
		 	$this->Acl->deny($group, 'controllers/'.$controller.'/'.$action);
		 }
	 }
	 foreach($distributor_acl as $controller => $actions){
		 foreach($actions as $action){
		 	$this->Acl->allow($group, 'controllers/'.$controller.'/'.$action);
		 }
	 }

	 //retailer permissions
	 $group->id = 6;
	 $this->Acl->deny($group, 'controllers');

	 foreach($member_acl as $controller => $actions){
		 $this->Acl->allow($group, 'controllers/'.$controller);
		 foreach($actions as $action){
		 	$this->Acl->deny($group, 'controllers/'.$controller.'/'.$action);
		 }
	 }
	 foreach($retailer_acl as $controller => $actions){
		 foreach($actions as $action){
		 	$this->Acl->allow($group, 'controllers/'.$controller.'/'.$action);
		 }
	 }

	 $this->autoRender = false;
	}*/
	
    
	function test(){
		echo "1"; exit;
	}

    function sendOtp($params){
    	$this->autoRender = false;
    	
    	if ($this->RequestHandler->isAjax()) {
    		$mobile = trim(isset($_POST["mobileNo"]) ? $_POST["mobileNo"] : "");
    	}
    	else if(!empty($params)){
    		$mobile = $params['mobile'];
    	}
    	
    	if(isset($mobile) && !empty($mobile)){
    		$retailers = $this->User->query("select id
    								from retailers
    								where mobile = '$mobile'");
    		if(!empty($retailers)){
    			$otp = $this->General->generatePassword(6);
    			
    			$MsgTemplate = $this->General->LoadApiBalance();
    			$paramdata['OTP'] = $otp;
    			$content =  $MsgTemplate['Send_OTP_MSG'];
    			$msg = $this->General->ReplaceMultiWord($paramdata, $content);
    			
    			$this->Shop->setMemcache("otp_changeMob_$mobile", $otp, 30*60);
    			$this->General->logData("/mnt/logs/MSG_TEMPLATE_CHECK.txt", "User_Controller--sendOtp::-".$msg."-::MSG_End");
    			$this->General->sendMessage($mobile, $msg, 'payone');
    			
    			if ($this->RequestHandler->isAjax()) {
    				echo json_encode(array("result" => "success", "number" => $mobile));
    				return;
    			}
    			else if(!empty($params)){
    				return array("status" => "success", "description" => "OTP has been sent to your mobile number");
    			}
    		}
    		else {
    			if ($this->RequestHandler->isAjax()) {
    				echo json_encode(array("result" => "failure", "desc" => "Mobile Number does not exist in system!!"));
    				return;
    			}
    			else if(!empty($params)){
    				return array("status" => "failure", "description" => $mobile." is not a registered retailer with Pay1");
    			}
    		}
    	}
    	else {
    		if ($this->RequestHandler->isAjax()) {
    			echo json_encode(array("result" => "failure", "desc" => "Mobile number not sent."));
    			return;
    		}
    		else if(!empty($params)){
    			return array("status" => "failure", "description" => "Mobile number not sent.");
    		}
    	}
    }
    
    public function getHashedPassword()
    {
        $this->autoRender=false;
       
        if(!empty($this->params['form']['payload'])):
                echo json_encode(array('data'=>$this->Auth->password($this->params['form']['payload'])));
        endif;
        
        die;
       
        
    }
    
    public function authenticate()
    {
        $this->autoRender = false;
        
        $params=  $this->params['form'];
        
        $hashPassword=$this->Auth->password($params['password']);
        
        $sql="SELECT iu . * , u.name,u.mobile,u.passflag "
         . " FROM users u "
         . " JOIN user_groups iu "
         . " ON u.id = iu.user_id "
         . " WHERE u.mobile = '{$params['mobile']}' "
//         . " AND u.password = '{$hashPassword}' AND iu.source='{$params['source']}' ";
         . " AND u.password = '{$hashPassword}'  ";
         
         $result=  $this->User->query($sql);
         
        //if(count($result)==1):
        if(!empty($result)):
            
                    $info=array();
                    $info['User']['group_id'] = $result[0]['iu']['group_id'];
                    $info['User']['id'] = $result[0]['iu']['user_id'];
                    $info['User']['mobile'] = $result[0]['u']['mobile'];
                    $info['User']['name'] = $result[0]['u']['name'];
                    $info['User']['passflag'] = $result[0]['u']['passflag'];
                  
                     echo json_encode(array('status'=>true,'type'=>true,'msg'=>'Login Successful','data'=>$info,'code'=>'s001'));
                  
      else:
                    echo json_encode(array('status'=>true,'type'=>false,'msg'=>'Invalid Login Credentials'));
      
      endif;
             
    }
    
    public function checkifValidHost($group_id)
    {
        $response['status'] = FALSE;
        $response['errors'] = array('code'=>'E001','msg'=>'Something went wrong');
                                                        
                if(in_array($_SERVER['SERVER_NAME'],array('cc.pay1.in','cc.pay1.me','internal.pay1.in','apptesting.pay1.in'))){
                                                $office_ips = explode(",",OFFICE_IPS);
                                                $ip = $this->General->getClientIP();

                                                if(in_array($ip,$office_ips) || in_array($group_id,array(ADMIN,LIMITS,VENDOR,BACKEND_ADMIN,INVENTORY_ADMIN,INVENTORY_EDITOR,INVENTORY_MEMBER,SUPER_ADMIN,ACCOUNTS,TECHNOLOGY,SYSTEM_ADMIN,CHANNEL_SALES))){
                                                        $response['status'] = TRUE;
                                                        $response['success'] = array('code'=>'S001','msg'=>'Login Successful.');
                                                }
                                                else {
                                                        $response['status'] = FALSE;
                                                        $response['errors'] = array('code'=>'E001','msg'=>'Invalid Access Location .');
                                                }
                                        }
                                       
            else if(!in_array($group_id,array(ADMIN,MASTER_DISTRIBUTOR,DISTRIBUTOR,RELATIONSHIP_MANAGER,VENDOR,RETAILER)) && !in_array($_SERVER['SERVER_NAME'],array('cc.pay1.in','internal.pay1.in'))){
                    $response['status'] = FALSE;
                    $response['errors'] = array('code'=>'E002','msg'=>'Invalid Access Location .');
            }
            
            
            return $response;
    }
    
   /* public function migrate()
    {
        $this->autoRender=false;
        $sql="SELECT u.id as user_id,u.group_id as ugroupid,iu.group_id as iugroupid,g1.source as usource,g2.source as iusource
FROM  users u join internal_users iu
on u.id=iu.user_id JOIN groups g1 ON g1.id=u.group_id
JOIN groups g2 ON  g2.id=iu.group_id";
        
        $result=  $this->User->query($sql);
        
        foreach($result as $val):
            echo "<pre>";
            print_r($val);
            echo "</pre>";

            if($val['g1']['usource']=="1"):
                $this->User->query("Insert into user_groups(user_id,group_id,source) values('{$val['u']['user_id']}','{$val['u']['ugroupid']}','1')");
            endif;
            if ($val['g2']['iusource']=="2"):
               $this->User->query("Insert into user_groups(user_id,group_id,source) values('{$val['u']['user_id']}','{$val['iu']['iugroupid']}','2')");
             endif;
        endforeach;
        
    }*/
    
    public function reset()
    {
        $this->layout="products";
        
        if ($this->RequestHandler->isPost()):
            $mobile=$this->params['form']['mobile'];
        
                if(!empty($mobile) && preg_match('/^[6-9]{1}[0-9]{9}$/',$mobile)):
                  $user=$this->User->query("select id from users where mobile='{$mobile}'");
                        if(!empty($user)):
                                 $otp = $this->General->generatePassword(6);
                                 $MsgTemplate = $this->General->LoadApiBalance();
                                 $paramdata['OTP'] = $otp;
                                 $content =  $MsgTemplate['Send_OTP_MSG'];
                                 $msg = $this->General->ReplaceMultiWord($paramdata, $content);
                                 $this->Shop->setMemcache("otp_verifyuser_$mobile", $otp, 3*60);
                                 $this->General->sendMessage($mobile, $msg, 'payone');
                                 $this->Session->setFlash("<b>Success</b> : Kindly enter OTP sent to your mobile");
                                 $this->redirect('/users/verify/'.$mobile);
                         else:
                             $this->Session->setFlash("<b>Errors</b> :  Mobile Number does not exist in system!!");
                             $this->redirect('reset');
                         endif;
                endif;
                
        endif;
    }
    
    public function verify($mobile)
    {
        $this->layout="products";
        
        if(!preg_match('/^[6-9]{1}[0-9]{9}$/',$mobile)):
            $this->Session->setFlash("<b>Errors</b> :  Something went wrong !!"); $this->redirect("reset");
        endif;
        
        if ($this->RequestHandler->isPost()):
            $otp=$this->params['form']['otp'];
        
            if(!empty($otp)):

                if($otp == $this->Shop->getMemcache("otp_verifyuser_$mobile")):
                
                    $this->Shop->delMemcache("otp_verifyuser_$mobile");
                    $this->Session->setFlash("<b>Success</b> : OTP has been verified successfully");
                    $this->Shop->setMemcache("reset_password_$mobile",$mobile,60);
                    $this->redirect('/users/savePassword/'.$mobile);

                else:

                    $this->Session->setFlash("<b>Errors</b> :  Invalid OTP");
                    $this->redirect('verify/'.$mobile);

                 endif;
            endif;
        endif;
    }
 
    public function savePassword($mobile)
    {
        $this->layout="products";
       
        if($this->Shop->getMemcache("reset_password_$mobile") != $mobile):
            exit("Something went wrong");
        endif;
            
         if ($this->RequestHandler->isPost() && preg_match('/^[6-9]{1}[0-9]{9}$/',$mobile)):
             $password=$this->params['form']['password'];
             $confpassword=$this->params['form']['confirm_password'];
         
            if(!empty($password) && !empty($confpassword)):
                if ((strlen($password) < 6) || (strlen($confpassword) < 6)):
                   $this->Session->setFlash("<b>Errors</b> : Your Password Must Contain At Least 6 Characters!");
                   $this->redirect('savePassword/'.$mobile);

               elseif($password != $confpassword):
                   $this->Session->setFlash("<b>Errors</b> : Password confirmation does not match password");
                   $this->redirect('savePassword/'.$mobile);

               else:
                       if($result=$this->__updateUserPassword($mobile, $password)):
                               $this->Session->setFlash("Password Updated Successfully!");
                               $this->redirect('/');
                       endif;
               endif;
            endif;
         endif;
    }
           
    public function __updateUserPassword($mobile, $password)
    {
        $newpwd=$this->Auth->password($password);
        
        if($this->User->query("update users set password='{$newpwd}', password_change='".date('Y-m-d')."' where mobile='{$mobile}' ")):
            return true;
        endif;          
               
        return false;
    }
    

    function distAgreementVal() {
  
        $this->autoRender = FALSE; 
        
        $proposition_agreement_val = $this->params['form']['val'];
        $dist_id = $_SESSION['Auth']['User']['id'];
        $distagree = $this->User->query("Update distributors set proposition_agreement = '$proposition_agreement_val' where user_id = '$dist_id'");
        $upval = $this->Session->Write('Auth.proposition_agreement', '1');
        echo json_encode($upval);
    }

    function distAgreementData() {
    	$this->layout = FALSE;
        $this->render('dist_agreement_data');  
    }
    
    function distContestVal() {
  
        $this->autoRender = FALSE; 
        
        $contest_val = $this->params['form']['con'];
        $dist_id = $_SESSION['Auth']['User']['id'];
        $distcontest = $this->User->query("Update distributors set contest_flag = '$contest_val' where user_id = '$dist_id'");
        $upval = $this->Session->Write('Auth.contest_flag', '1');
        echo json_encode($upval);
    }   
}
