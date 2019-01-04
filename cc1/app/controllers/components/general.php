<?php
class GeneralComponent extends Object {
	var $components = array('Auth','Shop','RequestHandler');

        private static $emailTemplates = array(
            "emailToAdminOnRmRegistration"=>array(
                                                    "subject" => "New RelationShip Manager ( R M ) Created in Pay1",
                                                    "body" => "New RelationShip Manager ( R M ) Created in Pay1 <br/>
                                                               Mobile No: @rm_mobile@ <br/>
                                                               RM Name: @rm_name@ <br/>
                                                               Distributor: @distributor_company@ <br/>"
                                                 ),
            "emailToAdminOnRmAddWithDistributor"=>array(
                                                    "subject" => "RM appointed to a distributor",
                                                    "body" => "RM Name: @rm_name@ <br/>
                                                               Master Distributor: @master_distributor_company@ <br/>
                                                               Distributor: @distributor_company@"
                                                 ),
            "emailToAdminOnRmAddWithSuperDistributor"=>array(
                                                    "subject" => "RM appointed to a super distributor",
                                                    "body" => "RM Name: @rm_name@ <br/>
                                                               Master Distributor: @master_distributor_company@ <br/>
                                                               Super Distributor: @super_distributor_company@"
                                                 ),

            "emailToAdminOnOverLimitSalesmenCreation"=>array(
                                                    "subject" => "OverLimit Salesmen Creation By Distributor",
                                                    "body" => "Distributor Name: @distributor_company@ <br/>
                                                               Distributor Id: @distributor_id@ <br/>
                                                               Salesman Name: @salesman_name@ <br/>
                                                               Salesman Mobile: @salesman_mobile@ <br/>"
                                                )
        );
        private static $smsTemplates = array(
            "smsToRMOnRmRegistration"=>array(
                                                    "msg" =>  "Congrats!!\nYou have registered with Pay1."
                                             ),
            "smsToMasterDistOnRmRegistration"=>array(
                                                    "msg" => "New RM created\nname: @rm_name@\nmobile: @rm_mobile@"
                                                 ),
            "smsToDistributorOnRmAddWithDistributor"=>array(
                                                    "msg" => "Pay1 have assigned a new RM @rm_name@ (@rm_mobile@) to you\nPlease contact him for any queries"
                                                 ),
            "smsToSuperDistributorOnRmAdd"=>array(
                                                    "msg" => "Pay1 have assigned a new RM @rm_name@ (@rm_mobile@) to you\nPlease contact him for any queries"
                                                 ),
	   "smsToRetailerOnDistributorChange"=>array(
                                                    "msg" => "Dear Retailer,\n Distributor of your area has been changed to @dist_name@ (@dist_mobile@). Kindly connect with your new distributor for limit top-up."
                                                 ),
            "smsToDistributorOnOldNoAboutMoblieNoChange"=>array(
                                                    "msg" => "Dear Distributor,
Your number has been shifted from @dist_old_mobile@ to @dist_new_mobile@ ")

        );

        function tokenGeneration($data,$secret,$algo="SHA512"){
           return hash_hmac($algo, json_encode(array_map("strval",$data)), $secret);
        }

        function tokenValidate($data,$secret,$token,$algo="SHA512"){
            $hashGen = $this->tokenGeneration($data, $secret, $algo);
            if(!empty($token) && $token === $hashGen){
                return true;
            }else{
                return false;
            }
        }


        function dumpLog($loggername,$loggerfilename){
                App::import('Vendor', 'logger/main/php',array('file'=>'Logger.php'));
                $this->logger = Logger::getLogger($loggername);
	        Logger::configure(array(
	            'rootLogger' => array(
	                'appenders' => array('default'),
	            ),
	            'appenders' => array(
	                'default' => array(
	                    'class' => 'LoggerAppenderFile',
	                    'layout' => array(
	                        'class' => 'LoggerLayoutPattern'
	                    ),
	                    'params' => array(
	                        'file' => '/mnt/logs/'.$loggerfilename.'_'.date('Ymd').'.log',
	                        'append' => true
	                    )
	                )
	            )
	        ));

		return $this->logger;
	}

        function enCrypt($data = null) {
		if ($data != null) {
			// Make an encryption resource using a cipher
			$td = mcrypt_module_open('cast-256', '', 'ecb', '');
			// Create and encryption vector based on the $td size and random
			$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
			// Initialize the module using the resource, my key and the string vector
			mcrypt_generic_init($td, encKey, $iv);
			// Encrypt the data using the $td resource
			$encrypted_data = mcrypt_generic($td, $data);
			// Encode in base64 for DB storage
			$encoded = base64_encode($encrypted_data);
			// Make sure the encryption modules get un-loaded
			if (!mcrypt_generic_deinit($td) || !mcrypt_module_close($td)) {
				$encoded = false;
			}
		} else {
			$encoded = false;
		}
		return $encoded;
	}
	/**
	 * This function will de-crypt the string that is passed to it
	 *
	 * @param String $data The string to be encrypted.
	 * @return String Returns the encrypted string or false
	 */
	function deCrypt($data = null) {
		if ($data != null) {
			// The reverse of encrypt.  See that function for details
			$data = (string) base64_decode(trim($data));
			$td = mcrypt_module_open('cast-256', '', 'ecb', '');
			$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
			mcrypt_generic_init($td, encKey, $iv);
			$data = (string) trim(mdecrypt_generic($td, $data));
			// Make sure the encryption modules get un-loaded
			if (!mcrypt_generic_deinit($td) || !mcrypt_module_close($td)) {
				$data = false;
			}
		} else {
			$data = false;
		}
		return $data;
	}

	function makeUrl($text){
		$text = preg_replace('/[^a-zA-Z0-9 -]/s', '', $text);
		$text = str_replace('  ', ' ', $text);
		$text = str_replace(' ','-',strtolower($text));
		return $text;
	}

	function makeCamelcase($str)
	{
		$str = trim($str);
		$str = ucwords(strtolower($str));

		return $str;
	}
	function dateFormat($date){
		return date('jS M, Y',strtotime($date));
	}

	function dateTimeFormat($date){
		return date('jS M, Y g:i A',strtotime($date));
	}

	function nameToUrl($name) {
		return $this->makeUrl($name);
	}

	function urlToName($url){
		$name = str_replace('-',' ',$url);
		return $this->makeCamelcase($name);
	}

	function generatePassword($characters,$mobile=null){
		$code = '';

		if($mobile == null){
			$possible = '0123456789';
			$i = 0;
			while ($i < $characters) {
				$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
				$i++;
			}
		}
		else {
			$code =  substr($mobile, -2, 1) .  substr($mobile, -4, 1) . substr($mobile, -6, 1) . substr($mobile, -8, 1);
		}

		return $code;
	}

	function sendPassword($mobile,$password,$flag,$missCall=null){
		if($flag == '1') {
			$alias = "SUBSCRIBE_PASSWORD";
			$vars[] = $password;
		}
		else if($flag == '0'){
			$alias = "FORGOT_PASSWORD";
			$vars[] = $password;
		}
		$message = $this->createMessage($alias,$vars);
		if($missCall == null) {
			$this->sendMessage('',$mobile,$message,'template');
		}
		else if($missCall != null){
			return $message;
		}
	}


	/*function balanceUpdate($price,$type,$userId=null){
		if($userId == null){
			$userId = $_SESSION['Auth']['User']['id'];
		}
		$bal = $this->getBalance($userId);
		$userObj = ClassRegistry::init('User');

		if($type == 'subtract'){
			$userObj->query("UPDATE users set balance = balance - $price where id = $userId");
			$balance = $bal - $price;
		}
		else if($type == 'add'){
			$userObj->query("UPDATE users set balance = balance + $price where id = $userId");
			$balance = $bal + $price;
		}

		return $balance;
	}*/

	function getBalance($userId){
		$userObj = ClassRegistry::init('User');
		$userObj->recursive = -1;
		$bal = $userObj->findById($userId);
		return $bal['User']['balance'];
	}

	function checkIfUserExists($mobile,$dataSource=null,$group_id=0){
                if($dataSource === null){
                    $dataSource = ClassRegistry::init('Slaves');
                }
//                $group_id == DISTRIBUTOR && $condition = " AND user_groups.group_id = '".DISTRIBUTOR."' ";
                $data = $dataSource->query("SELECT users.mobile FROM users JOIN user_groups ON (users.id = user_groups.user_id) WHERE users.mobile = '$mobile' $condition");
                $count = (!empty($data)) ? 1 : 0;

		if($count > 0) return true;
		return false;
	}

	function checkIfSalesmanExists($mobile){
		$userObj = ClassRegistry::init('Salesman');
		$count = $userObj->find('count',array('conditions' => array('Salesman.mobile' => $mobile)));

		if($count > 0) return true;
		return false;
	}
        function checkIfRmExists($mobile){
		$userObj = ClassRegistry::init('Rm');
		$count = $userObj->find('count',array('conditions' => array('Rm.mobile' => $mobile)));

		if($count > 0) return true;
		return false;
	}

	function getGroupId($name,$mobile=null){
		$groupObj = ClassRegistry::init('Group');
		$groupObj->recursive = -1;
		$groupId = $groupObj->find('first', array('fields' => array('Group.id'),'conditions' => array('Group.name' => $name)));

		$mobileNums = array('9892471157','9892609560','9819852204','9820595052','9004387418','9819032643');
		if($name != 'admin' && in_array($mobile,$mobileNums)){
			return $this->getGroupId('admin');
		}
		return $groupId['Group']['id'];
	}

	function getPrice($price){
		$str = '';
		if($price >= 1)
		$str = '<span><img class="rupee1" src="/img/rs.gif"></span>'.$price;
		else
		$str = 100*$price . ' paise';
		return $str;
	}

	function countChars($message){
		return strlen($message);
	}

	function getSMSNums($charcount){
		//$charcount = $charcount - DEFAULT_MESSAGE_LENGTH + ADSPACE;
		$num = ceil($charcount/DEFAULT_MESSAGE_LENGTH);
		return $num;
	}

	function getCharge($num_messages){
		return (EACH_MESSAGE_COST/100)*$num_messages;
	}


	function getTotalMessageAmount($message,$num){
		$chars = $this->countChars($message);
		$num_messages = $this->getSMSNums($chars);
		$amount = $this->getCharge($num_messages)*$num;
		return $amount;
	}

	function getMessageCharge($id){
		$msgObj = ClassRegistry::init('Message');
		$msgObj->recursive = -1;
		$msgData = $msgObj->find('first', array('fields' => array('Message.charCount','Message.content'),'conditions' => array('Message.id' => $id)));

		if($msgData['Message']['charCount'] == null){
			$chars = $this->countChars($msgData['Message']['content']);
		}
		else {
			$chars = $msgData['Message']['charCount'];
		}

		$num_messages = $this->getSMSNums($chars);
		$amount = $this->getCharge($num_messages);

		return $amount;
	}

	function addAsynchronousCall($random,$controller,$action,$params){
		$userObj = ClassRegistry::init('User');
		$userObj->query("INSERT INTO asynchronous_calls (random_id,controller,action,params) VALUES ($random,'".$controller."','".$action."','".addslashes(json_encode($params))."')");
	}


	function registerUser($mobile_number,$reg_type,$group=null, $password = null,$dataSource=null){
		//$userObj = (empty($dataSource)) ? ClassRegistry::init('User') : $dataSource;
		$userObj = ClassRegistry::init('User');
		$this->data = null;
		$this->data['User']['mobile'] = $mobile_number;
		if(isset($group)){
			if(isset($password)){
				$this->data['User']['passflag'] = 1;
			}
			else {
				$this->data['User']['passflag'] = 0;
			}
		}
		$password = $password ? $password : $this->generatePassword(4); //generate 4 character password
		$this->data['User']['password'] = $this->Auth->password($password); //encrypted password using hash salt

		$this->data['User']['balance'] = 0;
		if(empty($group))
		$this->data['User']['group_id'] = 1;
		else
		$this->data['User']['group_id'] = $group;
		$this->data['User']['dob'] = '0000-00-00';
		$this->data['User']['gender'] = 0; //0 means male

		$this->data['User']['login_count'] = 0;

		if($reg_type == ONLINE_REG)
		$this->data['User']['verify'] = 0;
		else if($reg_type == MISSCALL_REG)
		$this->data['User']['verify'] = -1;
		else if($reg_type == RETAILER_REG)
		$this->data['User']['verify'] = -2;
		else if($reg_type == REF_CODE_REG)
		$this->data['User']['verify'] = -3;

		$this->data['User']['dnd_flag'] = 0;
		$this->data['User']['syspass'] = $password;
		$this->data['User']['created'] = date("Y-m-d H:i:s");
		$this->data['User']['modified'] = date("Y-m-d H:i:s");
		if(isset($dnd['preference']))
		$this->data['User']['ncpr_pref'] =  $dnd['preference'];

		$userObj->create();
		if($userObj->save($this->data)) {
			$this->data['User']['id'] = $userObj->id;
			$this->logData('/mnt/logs/newUser.txt',"userdata : ".$this->data['User']['id']);
		}
		else {
		    $this->logData('/mnt/logs/newUser.txt',"userdata : data cannot be saved here");
		}
		if(!isset($this->data['User']['id']) || empty($this->data['User']['id'])){
			$this->data['User']['id'] = 1;
			$this->sendMails("Empty UserID in registerUser",json_encode($this->data),array('nandan.rana@pay1.in','ashish@pay1.in'),'mail');
		}
		return $this->data;
	}

	function isDND($mobile){
		if(true){
			$ret = $this->checkDND($mobile);
			return $ret['dnd'];
		}
		else{
			return 0;
		}
	}

	function makeOptIn247SMS($mobile){

		if(strlen($mobile) == 10) $mobile = "91$mobile";
		$adm = "EmailID=ashish@pay1.in&Password=123456&opt_Numbers=$mobile&opt_Status=2&opt_Date=".urlencode(date('m-d-Y h:i:s'))."&opt_Unique_ID=".time();

		//$out = $this->curl_post('http://optapi.24x7sms.com/api_1.0/bulk_reg_process.aspx?'.$adm,null,'GET');
		//echo $out['output'];
	}

	function checkTimeSlot($par = null){
		if(DND_FLAG){ //TRAI Changes
			if($par){
				if((intval($par) < (TIME_SLOT_START+30)) || (intval($par) > (TIME_SLOT_END))){
					return false;
				}else{
					return true;
				}
			}else{
				$current = date('Hi');
				if(intval($current) < TIME_SLOT_START || intval($current) > TIME_SLOT_END){
					return false;
				}else{
					return true;
				}
			}
		}
		else {
			return true;
		}
	}

	function addNonSentMessages($sender,$receivers,$message,$type,$app_name){
		$array_flag = 0;
		if(is_array($receivers)) {
			$receivers = implode(",",$receivers);
			$array_flag = 1;
		}
		if(!empty($receivers)){
			$userObj = ClassRegistry::init('User');
			$userObj->query("INSERT INTO log_notsent (sender,receivers,message,type,app_name,array_flag,timestamp) VALUES ('".$sender."','".$receivers."','".addslashes($message)."','".$type."','$app_name',$array_flag,'".date('Y-m-d H:i:s')."')");
		}
	}

	function createMessage($alias,$vars){
		$seperator = "@__123__@";
		$fname = $_SERVER['DOCUMENT_ROOT'] . "/templates.txt";
		$fh = fopen($fname,'r');
		$contents = fread($fh, filesize($fname));
		fclose($fh);
		$templates = json_decode($contents,true);
		//$this->printArray($templates);
		$message = $templates[$alias];

		foreach($vars as $var){
			$message = preg_replace('/@__123__@/', $var, $message, 1);
		}
		$message = preg_replace('/@__123__@/', 'N/A', $message);
		return $message;
	}

	function emailSMSToUsers($sms, $emails){
		$message = nl2br($sms);
		$message .= "<br/><br/><br/>Dear Customer, As your mobile number is in DND registry, You are receiving SMSTadka messages on email. If you wish to receive messages on your mobile, either opt out of DND registry or provide alternate non-DND mobile number.<br/>To know if your number is in DND, visit http://nccptrai.gov.in/nccpregistry/search.misc<br/>For any help, sms HELP to 09004-350-350.";
		$userObj = ClassRegistry::init('User');
		$str = array();
		foreach($emails as $email){
			$str[] = "('$email','".addslashes($message)."','".date('Y-m-d H:i:s')."')";
		}
		$data = $userObj->query("INSERT INTO log_mails (emailid,body,timestamp) VALUES " . implode(",",$str));
		$this->mailToUsers('Message From SMSTadka - '.date('jS M, Y'),$message,$emails);
	}


	function sendMessage($receivers,$message,$type=null,$extra=null, $group_id = RETAILER){

		if(SMS_FLAG == '1'){
			 $this->sendMessageViaOtherServer($receivers,$message,$type,$extra, $group_id);
		}
	
        }


        //LoadApiBalance for the purpose of reading Message Template from  msg_template
        function LoadApiBalance() {
            Configure::load('checkapibalance');
            return $smstemplate= Configure::read('msg_template');
        }

        //Replace Single word in Message Template
        function ReplaceWord($oldword, $newword, $stringcontainingword) {
            return str_replace($oldword, $newword, $stringcontainingword);
        }

        //Replace Multiple word in Message Template using ReplaceWord function
        function ReplaceMultiWord($paramdata,$content) {
            $missing_parameter = 0;
            foreach ($paramdata as $var=>$val){
                if(empty($val)){
                  $missing_parameter = 1;
                }
                $content = self::ReplaceWord('<'.strtoupper($var).'>', $val, $content);
            }
            //Sending Mail if Msg_Template Parameter is empty
            if($missing_parameter == 1){
                $Msg_Mail_sub = "Users_Controller Empty Msg_Template";
                $Msg_Mail_body = "Some Parameter are missed in Msg_Template-- ";
                $this->sendMails($Msg_Mail_sub,$Msg_Mail_body,array('ajay.tiwari@pay1.in'));
            }
        return $content;
        }

	function sendMails($subject,$mail_body,$emails=null,$type=null){

		if(MAIL_FLAG == '1'){
			 $this->sendMailsViaOtherServer($subject,$mail_body,$emails,$type);
		}

	}

	function sendEmailAttachments($subject,$mail_body,$sender_id=null,$emails=null,$attachment_urls,$type=null){
		if(MAIL_FLAG == '1'){
			if($this->sendEmailAttachmentsViaOtherServer($subject,$mail_body,$sender_id,$emails,$attachment_urls,$type))
                        {
                            return TRUE;
                        }
                        else
                        {
                            return FALSE;
                        }
		}
	}
        
        function sendFailureAfterSuccessData($trans = null,$vdate = null){
            		$userObj = ClassRegistry::init('User');
                $userObj->query("INSERT INTO txn_mismatch_status (txn_id,type,added_date,added_on,va_date) VALUES ($trans,1,'".date("Y-m-d")."','".date("Y-m-d H:i:s")."','$vdate')");
        }

	function findVar($var=null){
		$val = $this->Shop->getMemcache($var);
		if($val === false){
			$userObj = ClassRegistry::init('User');
			$data = $userObj->query("SELECT value FROM vars WHERE name = '$var'");
            if(count($data)>0){
			$val = $data['0']['vars']['value'];
            }

			$this->Shop->setMemcache($var,$val,24*60*60);
		}
		return $val;
	}



	function setVar($var,$val){
		$userObj = ClassRegistry::init('User');
		$data = $userObj->query("UPDATE vars SET value = '".addslashes($val)."' WHERE name = '$var'");
		$this->Shop->setMemcache($var,$val,24*60*60);
	}

	function getTaggings(){
		$val = $this->Shop->getMemcache("taggings");
		if($val === false){
			$userObj = ClassRegistry::init('User');
			$data = $userObj->query("select id, name, type from taggings where is_active = 1");

			$this->Shop->setMemcache("taggings",$data,24*60*60);
		}
		return $val;
	}

	function getCallTypes(){
		$val = $this->Shop->getMemcache("call_types");
		if($val === false){
			$userObj = ClassRegistry::init('User');
			$data = $userObj->query("select id, name from cc_call_types where is_active = 1");

			$this->Shop->setMemcache("call_types",$data,24*60*60);
		}
		return $val;
	}

	function set_defaultBlank($dataarray, $var) {
        return isset($dataarray[$var]) ? $dataarray[$var] : "";
    }

	function sendMessageViaOtherServer($receivers,$message,$type=null,$extra=null, $group_id = RETAILER){

		if($type != 'ussd'){
			//$url = SERVER_BACKUP . 'users/sendMsgMails';
			if(!is_array($receivers)) $receivers = explode(",",$receivers);

			if(in_array($type, array("notify", "special", "sms&notify", "notify_kyc"))){
				switch($type){
					case "notify":
					case "sms&notify":
						$not_type = "notification";
						break;
					case "notify_kyc":
						$not_type = "upload";
						break;
					default:
						$not_type = "banner";
				}
				$sms_rec = array();
				foreach($receivers as $rec){
					$res = "success";
					switch($group_id){
						case DISTRIBUTOR:
						case SALESMAN:
							$det = $this->Shop->getSalesmanDeviceData($rec);
							break;
						case RETAILER:
							$det = $this->Shop->getRetailerTrnsDetails($rec);
							break;
					}
					if( $det['trans_type']== "android"){
						$res = $this->sendGCMNotification($rec,$det['notification_key'],"Pay1 Notification",$message,$not_type);
						if($res['status'] == "failure"){
							$res = null;//$qr = "Error : ".$res['error_code']." - ".$res['description'];
						}else{
							$res = $res['description'];
						}
					}
					else if( $det['trans_type']== "android_fcm"){
					    $res = $this->sendGCMNotification($rec,$det['notification_key'],"Pay1 Notification",$message,$not_type,'merchant_new');
					    if($res['status'] == "failure"){
					        $res = null;//$qr = "Error : ".$res['error_code']." - ".$res['description'];
					    }else{
					        $res = $res['description'];
					    }
					}
					else if( $det['trans_type']== "android_distributor"){
						$res = $this->sendGCMNotification($rec, $det['notification_key'], "Pay1 Notification", $message, $not_type, 'channel_partner',$extra);
						if($res['status'] == "failure"){
							$res = null;//$qr = "Error : ".$res['error_code']." - ".$res['description'];
						}else{
							$res = $res['description'];
						}
					}else if($det['trans_type'] == "windows7"){
                        $paramArr=array();
                        $paramArr['devType'] = "windows7";
						$res = $this->sendWP8PNotification($rec,$det['notification_key'],"Pay1 Notification",$message,$not_type,$paramArr);//@TOTO create fun
						$res = null;
					}else if($det['trans_type'] == "windows8"){
						$paramArr=array();
                        $paramArr['devType'] = "windows8";
						$res = $this->sendWP8PNotification($rec,$det['notification_key'],"Pay1 Notification",$message,$not_type,$paramArr);
						$res = null;
					}else if($det['trans_type'] == "web"){
						$devType = "web";
						//@TODO create sendWebNotification method for web users
						$res = $this->sendWebNotification($rec,"Pay1 Notification",$message,$not_type,$det['notification_key']);
						$res = null;
					}else{
						$sms_rec[] = $rec;
						//$this->sendMessageViaOtherServer($receivers,$message,"payone",$extra);
					}
					if($res == null || $type == "sms&notify"){
						$sms_rec[] = $rec;
					}
				}
				$sms_rec = array_unique($sms_rec);
				$this->sendMessageViaOtherServer($sms_rec,$message,"payone",$extra);
			}
			else{


				//$url = SERVER_BACKUP . 'redis/insertInQsms';
				$data['sender'] = '';
				$data['mobile'] = implode(",",$receivers);
				$data['sms'] = $message;
				$data['root'] = $type;
				$data['app_name'] = $extra;
				if(!empty($receivers) && !empty($message)){
                                 $this->redis = $this->Shop->redis_connect();
					$val = json_encode($data);
                                        if($this->redis == false){
                                            $this->logData('/mnt/logs/outgoing_sms_.txt'," issue in redis connection smsdata : ".$val);
                                        }
					$this->logData('/mnt/logs/outgoing_sms_.txt',"smsdata : ".$val);
					$this->redis->lpush(SMSTADKA_SMSQ, $val);
//                                        $this->redis->quit();
				}

				/*if(!empty($data)){
					$this->curl_post_async($url,$data);
				}*/
			}
		}
		else {
			if(!is_array($receivers)){
				$receivers = array($receivers);
			}

			$var = $this->findVar('ussd');
			if($var == '1'){
				foreach($receivers as $receiver){
					$det = $this->Shop->getRetailerTrnsDetails($receiver);
					if($det['trans_type'] == "ussd"){
						$this->startUSSD(4,$receiver,$message);
					}
					else {
						$this->sendMessageViaOtherServer($receiver,$message,'shops');
					}
					/*$success = false;
					 $data = $this->getUserDataFromMobile($receiver);
					 $data1 = $this->getMobileDetails($receiver);
					 if($data1['operator'] == 'TD' && $data['ussd_flag'] == 1){
						$success = true;
						}

						if($success)$this->startUSSD(3,$receiver,$message);
						else $this->sendMessageViaOtherServer($receiver,$message,'shops');*/
				}
			}
			else {
				$this->sendMessageViaOtherServer($receivers,$message,'shops');
			}
		}
	}
	function sendGCMNotification($mobile, $key, $title="Pay1",$msg, $notificationType="notification", $app_type = 'merchant',$balance=null) {//sendGcmNotification($jsonStr = null) {// only for testing purpose
            try {
                App::import('Helper', 'gcm');
                $gcm = new GCM($app_type);
                $msgPass = json_encode(array(

                    "type" => $notificationType,
                    "title" => $title,
                    "msg" => $msg,
                	"balance" => $balance
                ));
                $message = array(
                    "data" => $msgPass
                );

                $response = $gcm->send_notification(array($key), $message);

                $qr = $response['description'];

                $userObj = ClassRegistry::init('User');
                $result = $userObj->query("
                        INSERT INTO `shops`.`notificationlog`
                            (`id`, `mobile`, `user_key`, `msg`, `notify_type`, `user_type`, `response`, `created`, `date`)
                        VALUES
                            (NULL, '$mobile', '$key', '".addslashes($msgPass)."', '$notificationType', 'android', '$qr', '" . date("y-m-d H:i:s") . "', '" . date("y-m-d H:i:s") . "');");

                return $response;
            } catch (Exception $e) {
                return $response['status']='failure';
            }
        }
        //windows 7 notification @TODO Change
        function sendWP7PNotification($userMobile=null , $userUrl=null, $title, $message , $notificationType="notification") {

                   //$this->autoRender = FALSE;
                   $msgArr = array(
                        "type" => $notificationType,
                        "msg" => $message,
                        "title" => $title
                   );
                   try {
                            App::import('Helper', 'wppn8');
                            $wppn8 = new WPPN8();
                   } catch (Exception $e) {
                            $response = "Error : WPPN8 INIT Error !";
                   }

                   if(empty($userUrl)){
                      $response = "Error : Empty push url found !";
                   }else{
                      $message  = json_encode($msgArr);
                      $response = $wppn8->send_notification($userUrl,$title, $message);
                      $userObj = ClassRegistry::init('User');
                      $result = $userObj->query("
                                INSERT INTO `shops`.`notificationlog`
                                    (`id`, `mobile`, `user_key`, `msg`, `notify_type`, `user_type`, `response`, `created`, `date`)
                                VALUES
                                    (NULL, '$userMobile', '$userUrl', '".addslashes($message)."', '$notificationType', 'windows8', '$response', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "');");

                   }
                   return $response;
	}
        //windows 8 notification @TODO Change
        function sendWP8PNotification($userMobile=null , $userUrl=null, $title, $message , $notificationType="notification", $paramArr=array()) {

                   //$this->autoRender = FALSE;
                   $paramArr ["type"] = $notificationType;
                   $devType = $paramArr['devType'];
                   try {
                            App::import('Helper', 'wppn8');
                            $wppn8 = new WPPN8();
                   } catch (Exception $e) {
                            $response = "Error : WPPN8 INIT Error !";
                   }

                   if(empty($userUrl)){
                      $response = "Error : Empty push url found !";
                   }else{
                      //$message  = json_encode($msgArr);
                      $msgId = time().$userMobile;
                      $created = date("Y-m-d H:i:s");
                      $response = $wppn8->send_notification($userUrl,$title, $message,$paramArr,$msgId,$created);
                      $userObj = ClassRegistry::init('User');
                      $result = $userObj->query("
                                INSERT INTO `notificationlog`
                                    (`id`, `msg_id` ,`mobile`, `user_key`, `msg`, `notify_type`, `user_type`, `response`,`received`, `created`, `date`)
                                VALUES
                                    (NULL, '$msgId' ,'$userMobile', '$userUrl', '".addslashes($message)."', '$notificationType', '$devType' , '$response', 0 , '" . $created . "', '" . date("Y-m-d H:i:s") . "');");

                   }
                   return $response;
	}
        //Web notification
        function sendWebNotification($userMobile=null ,$title, $message , $notificationType="notification",$key = null) {

                   if(is_array($userMobile)){
                      $userMobile = implode(",",$userMobile) ;
                   }
                   $msgArr = array(
                        "type" => $notificationType,
                        "title" => $title,
                        "msg" => $message,
                        "timestamp"=>date("d-m-Y g:ia")
                   );

                   try {
                            App::import('Helper', 'webpn');
                            $webpn = new WEBPN();
                   } catch (Exception $e) {
                            $response = "Error : WEBPN INIT Error !";
                   }

                  $message  = addslashes(json_encode($msgArr));
                  $response = $webpn->send_notification($key, $message);
                  $userObj = ClassRegistry::init('User');

                  $userMobile = explode(",", $userMobile);
                  $qStr = "";
                  foreach($userMobile as $mobile){
                      $qStr = $qStr . "( NULL , '$mobile', '$key', '".addslashes($message)."', '$notificationType', 'web', '$response', '" . date("y-m-d H:i:s") . "', '" . date("y-m-d H:i:s") . "')";
                  }

                  $result = $userObj->query("
                            INSERT INTO `shops`.`notificationlog`
                                (`id`, `mobile`, `user_key`, `msg`, `notify_type`, `user_type`, `response`, `created`, `date`)
                            VALUES
                                $qStr
                  ");
                  return $response;
	}

        function getUSSDData($type,$mobile=null,$number=null){
		if($type == 1 || $type == 4){
			$data = "Welcome to pay1!!\n\nEnter your request\ne.g *15*9769597418*10";
		}
		else if($type == 2){
			$ch = curl_init();
			$url = USSD_VENDOR_247_URL."$number.aspx?MobileNo=$mobile";
			curl_setopt($ch, CURLOPT_URL, $url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
			curl_setopt($ch, CURLOPT_TIMEOUT, 100);
			$data = trim(curl_exec($ch));
			curl_close($ch);
		}

		return $data;
	}

	function updateRetailerAddress($retailer_id, $user_id, $params){
		$userModel = ClassRegistry::init('User');
		$params['latitude'] = (float) $params['latitude'];
		$params['longitude'] = (float) $params['longitude'];

		if(empty($params['latitude']) || empty($params['longitude'])){
			$formatted_raw_address = $params['address'].", ".$params['area'].", ".$params['city'].", ".$params['state'];
			$standardLocation = $this->getLatLongByArea($formatted_raw_address);

			if(empty($standardLocation["lng"]) || empty($standardLocation["lat"])){
				$userLatLong = $userModel->query("select latitude, longitude
						from user_profile
						where user_id = ".$user_id."
						and latitude != 0 and longitude !=0 and latitude != '' and longitude != ''
						order by
							case when user_profile.device_type = 'online'
								then 1 else 2 end,
							updated desc
						limit 1");
				if(!empty($userLatLong)){
					$latitude = $userLatLong[0]['user_profile']['latitude'];
					$longitude = $userLatLong[0]['user_profile']['longitude'];

					$standardLocation = $this->getAreaByLatLong($longitude, $latitude);
				}
				else {
					return array("status" => "failure");
				}
			}
		}
		else {
			$standardLocation = $this->getAreaByLatLong($params['longitude'], $params['latitude']);
		}

		if(isset($standardLocation['state_name']) && !empty($standardLocation['state_name'])){
			$response['state_id'] = $this->stateInsert($standardLocation['state_name']);
		}
		if(isset($standardLocation['city_name']) && !empty($standardLocation['city_name'])){
			$response['city_id'] = $this->cityInsert($standardLocation['city_name'], $response['state_id']);
		}
		if(isset($standardLocation['area_name']) && !empty($standardLocation['area_name'])){
		    $response['area_id'] = $this->areaInsert($standardLocation['area_name'], $response['city_id'],$params['latitude'],$params['longitude'],$standardLocation['pincode']);
		}
		else {
			$response['area_id'] = 0;
		}

		$response['state'] = $standardLocation['state_name'];
		$response['city'] = $standardLocation['city_name'];
		$response['area'] = $standardLocation['area_name'];
		if(empty($params['latitude']) || empty($params['longitude'])){
			$response['longitude'] = $standardLocation["lng"];
			$response['latitude'] = $standardLocation["lat"];
		}
		else {
			$response['longitude'] = $params['longitude'];
			$response['latitude'] = $params['latitude'];
		}
		$response['pincode'] = empty($standardLocation["pincode"]) ? addslashes($params['pincode']) : $standardLocation["pincode"];
		$response['address'] = isset($params['address']) ? $params['address'] : "";

		if(!empty($params['verify_flag'])){
			$userProfile = $userModel->query("select up.*, u.mobile
								from users u
								left join user_profile up on up.user_id = u.id and up.device_type = 'online'
								where u.id = $user_id");

			if(!empty($userProfile[0]['up']['id'])){
				$result = $userModel->query("update user_profile
									set latitude = '".$response['latitude']."',
										longitude = '".$response['longitude']."',
										area_id = '".$response['area_id']."',
										updated = '".date("Y-m-d H:i:s")."',
                                                                                date = '".date("Y-m-d")."'
									where user_id = $user_id and device_type = 'online'");
			}
			else {
				$result = $userModel->query("insert into user_profile
						(user_id, gcm_reg_id, uuid, longitude, latitude, location_src, area_id, device_type,
							version, manufacturer, created, updated,date)
						values (".$user_id.", '".$userProfile[0]['u']['mobile']."', '', '".$response['longitude']."',
							'".$response['latitude']."', '', '".$response['area_id']."', 'online', '', '',
							'".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."','".date('Y-m-d')."')");
			}
		}
		else {
			$result = $userModel->query("update unverified_retailers
									set area_id = '".$response['area_id']."',
										address = '".addslashes($params['address'])."',
										pin = '".$response['pincode']."',
										latitude = '".$response['latitude']."',
										longitude = '".$response['longitude']."',
										modified = '".date("Y-m-d H:i:s")."'
									where retailer_id = $retailer_id");

			$result = $userModel->query("update retailers
									set area_id = '".$response['area_id']."',
										pin = '".$response['pincode']."',
										modified = '".date("Y-m-d H:i:s")."'
                                    where id = $retailer_id and area_id = 0");

            /** IMP DATA ADDED : START**/
            $imp_update_data = array(
                'address' => addslashes($params['address'])
            );
            $this->Shop->updateUserLabelData($retailer_id,$imp_update_data,$retailer_id,2);
            /** IMP DATA ADDED : END**/

		}

		if($result){
			$response['status'] = "success";
		}else{
			$response['status'] = "failure";
		}

		return $response;
	}

	function translate($text){
		preg_match("/[[:ascii:]]+/",$text,$match);
		$name = trim($match[0]);
		//$name = "";
		if(empty($name)){
			$geocode=file_get_contents(GOOGLE_TRANSLATE_API."&q=".urlencode($text)."&target=en");
	        $output= json_decode($geocode,true);
	        $text = $output['data']['translations'][0]['translatedText'];
		}
        //$name = (empty($name)) ? $text : $name;
		return $text;
	}

	function areaInsert($name , $cityId,$lat=null,$long=null,$pincode=null){

            $userObj = ClassRegistry::init('User');
            $name = $this->translate($name);
            $areaInfo = $userObj->query("select id , name from locator_area where name like '".$name."' AND city_id = $cityId;");
            if(empty($areaInfo[0]['locator_area']['id'])){
                $city = $userObj->query("SELECT name FROM locator_city WHERE id = $cityId");
                $lat_long = $this->getLatLongByArea($name . ', ' . $city[0]['locator_city']['name']);

                if(empty($lat_long['lat']))$lat_long['lat'] = $lat;
                if(empty($lat_long['lng']))$lat_long['lng'] = $long;
                if(empty($lat_long['pin_code']))$lat_long['pin_code'] = $pincode;
                $userObj->query("insert into locator_area ( city_id, name, lat, `long`, `pincode` , toShow ) values ( $cityId , '".addslashes($name)."', '" . $lat_long['lat'] . "', '" . $lat_long['lng'] . "', '" . $lat_long['pin_code'] . "', 1 );");
                $areaId = $userObj->query("SELECT LAST_INSERT_ID() as id FROM locator_area");
                $areaId = $areaId[0][0]['id'];
            	$this->logData('/mnt/logs/updateRetailer.txt',"inside areaInsert::area::not in database $ret $areaId");

            }else{
                $areaId = $areaInfo[0]['locator_area']['id'];
                $this->logData('/mnt/logs/updateRetailer.txt',"inside areaInsert::area::inside database".json_encode($areaInfo));

            }
            return $areaId;
        }
        function cityInsert($name , $stateId){
            $cityId = 0;
            $userObj = ClassRegistry::init('User');
             $name = $this->translate($name);
            $cityInfo = $userObj->query("select id , name from locator_city where name like '".$name."' AND state_id = $stateId;");
            if(empty($cityInfo[0]['locator_city']['id'])){
                $state = $userObj->query("SELECT name FROM locator_state WHERE id = $stateId");
                $lat_long = $this->getLatLongByArea($name . ', ' . $state[0]['locator_state']['name']);

                $userObj->query("insert into locator_city ( state_id , name , lat, `long`, toShow ) values ( $stateId , '$name', '" . $lat_long['lat'] . "', '" . $lat_long['lng'] . "', 1 );");
                $cityId = $userObj->query("SELECT LAST_INSERT_ID() as id FROM locator_city");
                $cityId = $cityId[0][0]['id'];
            }else{
                $cityId = $cityInfo[0]['locator_city']['id'];
            }
            return $cityId;
        }
        function stateInsert($name ){
            $cityId = 0;
            $mapping = array('Delhi (state)'=>'Delhi');
            $userObj = ClassRegistry::init('User');
             $name = $this->translate($name);

             $name = isset($mapping[$name]) ? $mapping[$name] : $name;
            $stateInfo = $userObj->query("select id , name from locator_state where name like '".$name."'");//echo "select id , name from locator_state where name like '".$name."'";

            if(empty($stateInfo[0]['locator_state']['id'])){
                $lat_long = $this->getLatLongByArea($name);

                $userObj->query("insert into locator_state (  name , lat, `long`, toShow ) values ( '$name', '" . $lat_long['lat'] . "', '" . $lat_long['lng'] . "', 1 ) ;");
                $stateId = $userObj->query("SELECT LAST_INSERT_ID() as id");
                $stateId = $stateId[0][0]['id'];
            }else{
                $stateId = $stateInfo[0]['locator_state']['id'];
            }
            return $stateId;
        }

        

        function getAreaByLatLong($long,$lat,$pin_code,$cache=true){//$lat,$long
            //19.188421,72
            //0.836591
            if(empty($long) || empty($lat)) return;
            if($cache){
                $ret = $this->Shop->getMemcache("arealatlong_".round($long,3)."_".round($lat,3));
            }
            else {
                $ret = false;
            }
            if($ret !== false) {
                $this->logData('googleapis.txt',date('Y-m-d H:i:s')."getAreaByLatLong - memcached $lat $long::".json_encode($ret));
                return $ret;
            }

            $url = PAY1_GOOGLE_MAP_API."&sensor=true";
            if($long!= "" && $lat!=""){
            	$url .= "&latlng=$lat,$long";
            }
            if($pin_code!= ""){
            	$url .= "&address=$pin_code";
            }

            $geocode=file_get_contents($url);

            $output= json_decode($geocode,true);
            $ret = array();
            $ret['area_name'] = "";//empty($output["results"][0]["address_components"][3]["long_name"]) ? "" :$output["results"][0]["address_components"][3]["long_name"];
            $ret['street_number'] = "";//$output["results"][0]["address_components"][0]["long_name"];
            $ret['route'] = "";//$output["results"][0]["address_components"][1]["long_name"];
            $ret['city_name'] = "";//$output["results"][0]["address_components"][5]["long_name"];//city , district
            $ret['state_name'] = "";//$output["results"][0]["address_components"][6]["long_name"];//state
            $ret['country_name'] = "";//$output["results"][0]["address_components"][7]["long_name"];//country
            $ret['pincode'] = "";//$output["results"][0]["address_components"][8]["long_name"];

            foreach($output["results"][0]["address_components"] as $arr){
                //echo "<pre>";print_r($arr);echo "</pre>";
                if(in_array("sublocality_level_1",$arr["types"])){
                    $ret['area_name']           = $arr["long_name"];
                }
                else if(in_array("sublocality_level_2",$arr["types"])){
                    $ret['area_name_1']           = $arr["long_name"];
                }
                else if($arr["types"][0] == "sublocality"){
                    $ret['area_name']           = $arr["long_name"];
                }else if($arr["types"][0] == "locality"){
                    $ret['city_name']  = $arr["long_name"];

                }else if($arr["types"][0] == "administrative_area_level_1"){
                    $ret['state_name'] = $arr["long_name"];
           	 	}
           	 	else if($arr["types"][0] == "administrative_area_level_2"){
                    $ret['extra'] = $arr["long_name"];
                }
                else if($arr["types"][0] == "country"){
                    $ret['country_name']  = $arr["long_name"];

                }else if($arr["types"][0] == "street_number"){
                    $ret['street_number']  = $arr["long_name"];

                }else if($arr["types"][0] == "route"){
                    $ret['route']  = $arr["long_name"];

                }else if($arr["types"][0] == "neighborhood"){
                    $ret['neighborhood']  = $arr["long_name"];
                    
                }else if($arr["types"][0] == "postal_code"){
                    $ret['pincode']  = $arr["long_name"];

                }
            }

            if(empty($ret['area_name'])){
                if(!empty($ret['area_name_1']))$ret['area_name'] = $ret['area_name_1'];

                if(empty($ret['area_name']) && !empty($ret['extra'])){
                    $ret['area_name'] = $ret['city_name'];
                    $ret['city_name'] = $ret['extra'];
                }
                if(empty($ret['area_name']) && !empty($ret['neighborhood'])){
                    $ret['area_name'] = $ret['neighborhood'];
                }
                if(empty($ret['area_name']) && !empty($ret['route'])){
                    $ret['area_name'] = $ret['route'];
                }
                
            }
            
            if(empty($ret['city_name']) && !empty($ret['extra']))  $ret['city_name'] = $ret['extra'];

            if(!empty($ret['city_name']) && !empty($ret['state_name']) && empty($ret['area_name'])) $ret['area_name'] = 'Unknown';
            
            $ret['formatted_address'] = $output["results"][0]["formatted_address"];
            $ret['lat']  = $output["results"][0]["geometry"]["location"]["lat"];
            $ret['lng']  = $output["results"][0]["geometry"]["location"]["lng"];

            $ret['geoURL']  = GOOGLE_MAP_API."?latlng=$lat,$long&sensor=true";
            $this->Shop->setMemcache("arealatlong_".round($long,3)."_".round($lat,3),$ret,7*24*60*60);
            $this->logData('googleapis.txt',date('Y-m-d H:i:s')."getAreaByLatLong $lat $long::".json_encode($output)."::".json_encode($ret));

            return $ret;
        }

        function findUserAgent($userAgentData){
            if(stripos($userAgentData,"Android")){
                return 'Android';
            }
            else if(stripos($userAgentData,"Windows Phone 8")){
                return 'Windows8';
            }
            else if(stripos($userAgentData,"Windows Phone")){
                return 'Windows';
            }
            else if(stripos($userAgentData,"MIDP")){
                return 'MIDP';
            }
            else if(stripos($userAgentData,"Chrome/")){
                return 'Chrome';
            }
            else if(stripos($userAgentData,"Firefox/")){
                return 'Firefox';
            }
            else return 'Mozilla';
        }

        function setSessionToken($user_id,$session_id,$agent){
            $sessionData = $this->Shop->getMemcache('tokenUser_'.$user_id);
            $sessionData[$agent] = $session_id;
            $this->Shop->setMemcache('tokenUser_'.$user_id,$sessionData);
        }

        function logoutUser($user_id,$group_id){
            if($group_id == DISTRIBUTOR){
                $sessionData = $this->Shop->getMemcache('tokenUser_'.$user_id);

                foreach($sessionData as $sess=>$token){
                    $this->Shop->delMemcache($token);
                }

                $userObj = ClassRegistry::init('User');
                $salesmanData = $userObj->query("SELECT salesmen.mobile FROM salesmen inner join distributors ON (distributors.id = salesmen.dist_id) WHERE distributors.user_id = $user_id");
                foreach($salesmanData as $salesman){
                    $sessionData = $this->Shop->getMemcache('tokenUser_'.$salesman['salesmen']['mobile']);
                    foreach($sessionData as $sess=>$token){
                        $this->Shop->delMemcache($token);
                    }
                }
            }
        }

        function getLatLongByArea($areaLine){//$lat,$long
            //Configure::write('debug',2);
            //echo "Hello";
            $areaLine = urlencode($areaLine);
            $geocode=file_get_contents(PAY1_GOOGLE_MAP_API."&address=$areaLine&sensor=true");

            $output= json_decode($geocode,true);
            //echo "http://maps.googleapis.com/maps/api/geocode/json?address=$areaLine&sensor=true";
            //echo "<pre>"; print_r($output);echo "</pre>";
            $ret = array();
            $ret['address']             = $output["results"][0]["address_components"][0]["long_name"];
            $ret['area_name']           = "";//$output["results"][0]["address_components"][1]["long_name"];
            $ret['city_name']           = "";//$output["results"][0]["address_components"][2]["long_name"];
            $ret['state_name']          = "";//$output["results"][0]["address_components"][3]["long_name"];
            $ret['country_name']        = "";//$output["results"][0]["address_components"][4]["long_name"];
            $ret['formatted_address']   = $output["results"][0]["formatted_address"];
            $ret['lat']  = $output["results"][0]["geometry"]["location"]["lat"];
            $ret['lng']  = $output["results"][0]["geometry"]["location"]["lng"];
            $ret['geoURL']  = PAY1_GOOGLE_MAP_API."&address=$areaLine&sensor=true";

            foreach($output["results"][0]["address_components"] as $arr){
                if(strpos($arr["types"][0],"sublocality") !== false){
                    $ret['area_name']           = $arr["long_name"];
                }else if($arr["types"][0] == "locality"){
                    $ret['city_name']  = $arr["long_name"];
                }else if($arr["types"][0] == "administrative_area_level_1"){
                    $ret['state_name'] = $arr["long_name"];
                }
            	else if($arr["types"][0] == "administrative_area_level_2"){
                    $ret['extra'] = $arr["long_name"];
                }
                else if($arr["types"][0] == "country"){
                    $ret['country_name']  = $arr["long_name"];
                }
                else if($arr["types"][0] == "postal_code"){
                    $ret['pin_code']  = $arr["long_name"];
                }
            }

        	if(!isset($ret['area_name'])){
            	$ret['area_name'] = $ret['city_name'];
            	if(isset($ret['extra']))$ret['city_name'] = $ret['extra'];
            }

            $this->logData('googleapis.txt',date('Y-m-d H:i:s')."getLatLongByArea $areaLine::".json_encode($output)."::".json_encode($ret));

            return $ret;
        }

        function getAreaIDByLatLong($long,$lat){

            if(!empty($lat) && !empty($long)){
                $loc_data = $this->getAreaByLatLong($long,$lat);
                if(isset($loc_data['state_name']) && !empty($loc_data['state_name'])){
                   $loc_data['state_id'] = $this->stateInsert($loc_data['state_name']);
                }
                if(isset($loc_data['city_name']) && !empty($loc_data['city_name'])){
                   $loc_data['city_id'] = $this->cityInsert($loc_data['city_name'], $loc_data['state_id']);
                }
                if(isset($loc_data['area_name']) && !empty($loc_data['area_name'])){
                   $loc_data['area_id'] = $this->areaInsert($loc_data['area_name'], $loc_data['city_id'],$lat,$long,$loc_data['pincode']);
                }
                else {
                   $loc_data['area_id'] = 0;
                }
            }else{
                $loc_data['area_id'] = 0;
            }
            return $loc_data['area_id'];
        }

        /*
         * Function return true if distance between two lat long is more than 500 KM otherwise return false/
         */
        function lat_long_distance($lat1, $lon1, $lat2, $lon2,$otp_distance=500){

            $unit = "K";     //unit is K for Kilometere , N for Nautical miles or M for miles
            
            $theta = $lon1 - $lon2;

            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
              $kilometer =  ($miles * 1.609344);
            } else if ($unit == "N") {
              $nautical_miles =  ($miles * 0.8684);
            } else {
              $miles;
            }

            if(!empty($otp_distance)){
                if(!is_nan($kilometer) && ($kilometer >= $otp_distance)){
                    return true;
                }else{
                    return false;
                }
            }
            else return $kilometer;
        }
        
        function isOTPRequired($mobile){
            $office_ips = explode(",",OFFICE_IPS);
            
            $client_ip = $this->getClientIP();
            if(!in_array($client_ip,$office_ips)){
                return true;
            }
            if(eregi("^7101000", $mobile)){
                return false;
            }
            
            return true;
        }


	function startUSSD($type,$mobile,$data=null,$number=null){
		$mobile = substr($mobile,-10);
		//make an entry logic here
		//retailer check here
		$session_id = $mobile . "-" . time();
		$extra = '';
		if(empty($type)) $type = 1;
		$rnum = rand(0,100);
		$vendor = ($type == 4 )?4:3;

		if($vendor == 3){
			//$vendor = 3;
			$session_id = "";
		}

        $userObj = ClassRegistry::init('User');
		if(in_array(substr($mobile,0,1),array('7','8','9'))){
			if(!empty($data))$extra = $data;
			if(!empty($number))$extra = $number;
			$userObj->query("INSERT INTO ussd_logs (mobile,type,vendor,sessionid,extra,date,time) VALUES ('$mobile',$type,$vendor,'$session_id','".addslashes($extra)."','".date('Y-m-d')."','".date('H:i:s')."')");
			$context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));

			if($vendor == 1){

				$url = SINFINI_USSD_URL;

				$pars = array();
				$pars['method'] = 'ussd';
				$pars['keyword'] = SINFINI_USSD_KEYWORD;
				$pars['mobile'] = $mobile;
				$pars['apikey'] = SINFINI_USSD_APIKEY;
				$out = $this->curl_post_async($url,$pars);

				$out = json_encode($out);
			}
			else if($vendor == 2){
				if(empty($data))$data = $this->getUSSDData($type,$mobile,$number);
				if($type == 1){
					$out = @file_get_contents(PROXY_VENDOR_USSD_URL."?msisdn=$mobile&msg=".urlencode($data)."&src=56263&tid=".PROXY_VENDOR_USSD_TID."&session=0&uid=".PROXY_VENDOR_USSD_UID."&keyword=".PROXY_VENDOR_USSD_KEYWORD,false,$context);
				}
				else if($type == 2 || $type == 3){
					$out = @file_get_contents(PROXY_VENDOR_USSD_URL."?msisdn=$mobile&msg=".urlencode($data)."&src=56263&tid=".PROXY_VENDOR_USSD_TID."&session=1&uid=".PROXY_VENDOR_USSD_UID."&keyword=".PROXY_VENDOR_USSD_KEYWORD,false,$context);
				}
			}
			else if($vendor == 3){
				$switch = $this->findVar("ussd_switch");

				if($switch == '1'){
					$url = TATA_LOCATION1_UUSD_URL;
				}
				else if($switch == '2'){
					$url = TATA_LOCATION2_UUSD_URL;
				}

				$pars = array();
				/*$pars['username'] = 'ussdmar';
				$pars['PASSWORD'] = 'marussd';
				$pars['MSISDN'] = $mobile;
				$pars['MsgType'] = 'USSDyn';
				$pars['UserText'] = '*6694#';*/
				$pars['username'] = TATA_USSD_USERNAME;
				$pars['PASSWORD'] = TATA_USSD_PASSWORD;
				$pars['MSISDN'] = $mobile;
				$pars['MsgType'] = TATA_USSD_MSGTYPE;
				$pars['UserText'] = TATA_USSD_USERTEXT;
				$pars['taskId'] = TATA_USSD_TASKID;
				$pars['circleId'] = TATA_USSD_CIRCLEID;
				$pars['OA'] = TATA_USSD_OA;
				$out = $this->curl_post_async($url,$pars,'GET');

				$out = json_encode($out);
			}
                        else if($vendor == 4){
                            $out = $this->sendUSSDResponse247_push($mobile, $data);
                        }

			$arr = json_decode($out,true);
			$userObj->query("UPDATE ussd_logs SET response='".json_encode($out)."',status='".$arr['status']."' WHERE sessionid='$session_id' AND level=0");

		}
		else {
			$userObj->query("UPDATE ussd_logs SET response='".json_encode($out)."',status='".$arr['status']."' WHERE sessionid='$session_id' AND level=0");
			$userObj->query("INSERT INTO ussd_logs (mobile,type,vendor,sessionid,date,time) VALUES ('$mobile',$type,$vendor,'$session_id','".date('Y-m-d')."','".date('H:i:s')."')");
		}
	}

        function sendUSSDResponse247_push($mobile, $message, $sessionId='0'){
            $url = V247_USSD_URL;
            $apiKey = V247_USSD_APIKEY;
            $urlId = V247_USSD_UID;				//fixed: given by the vendor
            $serviceName = V247_USSD_SERVICENAME;
            $response = "true";
            $eof = "true";
            $message = urlencode($message);
            $responseUrlRequested = "$url?APIKEY=$apiKey&MobileNo=$mobile&UrlID=$urlId&Message=$message&ServiceName=$serviceName&Response=$response&EOF=$eof&SessionID=$sessionId";

            $out = $this->curl_post($responseUrlRequested,null,'GET',30,10,true,true,true);
            return $out;
	}

	function sendMailsViaOtherServer($subject,$mail_body,$emails=null,$type=null){

		$data['mail_subject'] = $subject;
		$data['mail_body'] = $mail_body;
		if(!empty($emails)){
			$data['emails'] = implode(",",$emails);
		}

		if(!empty($data) && $type == 'mail'){

			$val = json_encode($data);
                        try{
                            $this->redis = $this->Shop->redis_connect();
                            $this->redis->lpush(SMSTADKA_MAILQ, $val);
                        }  catch (Exception $e){
                            $this->logData('redis_exception.log'," some error in send sms mail redis push : "+SMSTADKA_MAILQ+" ".$e->getMessage());
                        }
		}

	}

	function sendEmailAttachmentsViaOtherServer($subject,$mail_body,$sender_id=null,$emails=null,$attachment_urls,$type=null)
        {
            $data['mail_subject'] = $subject;
            $data['mail_body'] = $mail_body;
            $data['attachment_urls'] = $attachment_urls;

            if(!is_array($emails)){
                $emails = array($emails);
            }

            if(!empty($emails)){
                    $data['emails'] = implode(",",$emails);
            }

            if(!empty($data) && $type == 'mail'){

                    $val = json_encode($data);
                    try{
                        $this->redis = $this->Shop->redis_connect();
                        $this->redis->lpush(SMSTADKA_MAILQ, $val);
                        return TRUE;
                    }  catch (Exception $e){
                        $this->logData('redis_exception.log'," some error in send sms mail redis push : "+SMSTADKA_MAILQ+" ".$e->getMessage());
                        return FALSE;
                    }
            }
            return FALSE;
	}


	function printArray($txt){
		echo  '<pre>';
		print_r($txt);
		echo '</pre>';
	}

	function getUserDataFromMobile($mobile, $dbObj=null)
	{
                $profileQry = "";
                if(!empty($profileId)){
                   $profileQry = " AND user_profile.id =  $profileId";
                }
				$userObj = (empty($dbObj))? ClassRegistry::init('User') : $dbObj;

		//$query = "SELECT * FROM users WHERE mobile = '$mobile'";
                $query = "SELECT * FROM users WHERE users.mobile = '$mobile'";
		$res_arr = $userObj->query($query,false);

                $res = $res_arr['0']['users'];
		        if(!empty($res)){
                    $user_groups = $userObj->query("SELECT group_id FROM user_groups WHERE user_id = ".$res['id']);
                    $user_groups = $user_groups[0]['user_groups'];
                    $res['groups'] = $user_groups;
                }

                return $res;
	}

	function getUserDataFromId($id , $profileId=null)
	{       
                $userObj = ClassRegistry::init('User');
                $query = "SELECT * FROM users WHERE users.id = '$id'";
		//$query = "SELECT id,mobile,balance,active_flag,opening_balance,email,name,passflag,ussd_flag,update_flag FROM users WHERE users.id = '$id'";
		$res_arr = $userObj->query($query);
		$res = (!empty($res_arr)) ? $res_arr['0']['users'] : array();

		if(!empty($res_arr) && !empty($profileId)){
		    $res_arr = $userObj->query("SELECT * FROM user_profile WHERE id = '$profileId'");
		    if(!empty($res_arr)){
		        $res['profile_id'] = $res_arr['0']['user_profile']['id'];
		        $res['profile_uuid'] = $res_arr['0']['user_profile']['uuid'];
		        $res['profile_reg_id'] = $res_arr['0']['user_profile']['gcm_reg_id'];
		        $res['profile_longitude'] = $res_arr['0']['user_profile']['longitude'];
		        $res['profile_latitude'] = $res_arr['0']['user_profile']['latitude'];
		        $res['profile_device_type'] = $res_arr['0']['user_profile']['device_type'];
		        $res['profile_location_src'] = $res_arr['0']['user_profile']['location_src'];
		    }
		}

		if(!empty($res)){
		    $user_groups = $userObj->query("SELECT group_id FROM user_groups WHERE user_id = ".$res['id']);
		    $user_groups = $user_groups[0]['user_groups'];
		    $res['groups'] = $user_groups;
		}

                return $res;
	}

        function getMobileDetailsVia24x7($mobileNumber)
	{
                $response["shortCode"] = "";
                $response["operator"] = 0;

                return $response;
                $res = $this->curl_post(v247_MOBILE_DETAIL_URL."?MobileNo=".$mobileNumber,null,$type='GET',$timeout=10,$connect_timeout=2);
                $res = $res['output'];
                //{"Location":"BiharJharkhand","Carrier":"Airtel","Number":"7759000000","IsCDMA":"0"}
                if($res == "ZoneInfo not available for ".$mobileNumber){

                }else{
                    $resArr = json_decode($res,true);
                    $circleArr = array(
                                    "AP"                =>  "AP",
                                    "Assam"             =>  "AS",
                                    "BiharJharkhand"    =>  "BR",
                                    "TamilNadu"         =>  "TN",
                                    "Delhi"             =>  "DL",
                                    "Gujarat"           =>  "GJ",
                                    "HP"                =>  "HP",
                                    "Haryana"           =>  "HR",
                                    "JK"                =>  "JK",
                                    "Kerala"            =>  "KL",
                                    "Karnataka"         =>  "KA",
                                    "Kolkata"           =>  "KO",
                                    "Maharashtra"       =>  "MH",
                                    "MP"                =>  "MP",
                                    "Mumbai"            =>  "MU",
                                    "NorthEast"         =>  "NE",
                                    "Orissa"            =>  "OR",
                                    "Punjab"            =>  "PB",
                                    "Rajasthan"         =>  "RJ",
                                    "UPEast"            =>  "UE",
                                    "UPWest"            =>  "UW",
                                    "WestBengal"        =>  "WB",
                    				"Chennai"           =>  "CH"
                                );
                    $oprArr = array(
                                    "AircelDishnet0"   =>  "AC"	,
                                    "Airtel0"          =>  "AT"	,
                    				"Bsnl1"            =>  "CG"	,
                                    "Bsnl0"            =>  "CG"	,
                                    "Datacom0"         =>  "DC"	,
                    				"Videocon0"        =>  "DC"	,
                                    "DOLPHIN0"         =>  "DP"	,
                                    "Mtnl0"            =>  "DP"	,
                                    "Etisalat0"        =>  "ET"	,
                                    "Idea0"            =>  "ID"	,
                                    "Loop0"            =>  "LM"	,
                                    "Mts0"             =>  "MT"	,
                    				"Mts1"             =>  "MT"	,
                                    "PING0"            =>  "PG"	,
                                    "Reliance1"        =>  "RC"	,
                                    "Reliance0"        =>  "RG"	,
                                    "Spice0"           =>  "SP"	,
                                    "STel0"            =>  "ST"	,
                                    "Tata0"            =>  "TD"	,
                                    "Tata1"            =>  "TI"	,
                                    "Unitech0"         =>  "UN"	,
                                    "Hutch0"           =>  "VF"
                              );

                   $response["shortCode"] = isset($circleArr[trim($resArr["Location"])])?$circleArr[trim($resArr["Location"])]:"";
                   $response["operator"] = isset($oprArr[trim($resArr["Carrier"]).trim($resArr["IsCDMA"])])?$oprArr[trim($resArr["Carrier"]).trim($resArr["IsCDMA"])]:"";


                }
                return $response;
        }

        function getMobileDetailsNew($mobileNumber)//RG//MP
        {
        	if($this->mobileValidate($mobileNumber) == '1'){
        		return $ret_arr = array('area_name'=>'','area'=>'', 'opr_name'=>'', 'operator'=>'','product_id'=>'');
        	}
        	$mobNum = substr($mobileNumber, 0, 5);
        	$ret_arr = $this->Shop->getMemcache("numDet$mobNum");
        	if($ret_arr === false){
        		$query = "select mn.number, mna.area_name, mns.opr_name, mns.product_id, mn.area, mns.opr_code
        				from mobile_operator_area_map AS mn
        				LEFT JOIN mobile_numbering_area as mna ON mn.area = mna.area_code
        				LEFT JOIN mobile_numbering_service AS mns ON mn.operator = mns.opr_code
        				WHERE mn.number like '$mobNum'";
        		$userObj = ClassRegistry::init('User');

        		$data = $userObj->query($query);

        		$ret_arr = array('area_name'=>  empty($data['0']['mna']['area_name']) ? "" : $data['0']['mna']['area_name'],'area'=>empty($data['0']['mn']['area']) ? "" : $data['0']['mn']['area'], 'opr_name'=>empty($data['0']['mns']['opr_name']) ? "" :$data['0']['mns']['opr_name'], 'operator'=>empty($data['0']['mns']['opr_code'])?"":$data['0']['mns']['opr_code'],'product_id'=>  empty($data['0']['mns']['product_id']) ? "" : $data['0']['mns']['product_id']);
        		//$this->logData('/var/www/html/shops/abc.txt',date('Y-m-d H:i:s')."Final values for $mobileNumber: ".json_encode($ret_arr));

        		$this->Shop->setMemcache("numDet$mobNum",$ret_arr,24*60*60);
        	}

        	return $ret_arr;
        }

        function getMobileDetails($mobileNumber, $mobile_code_digits = NULL)//RG//MP
        {
        	if($this->mobileValidate($mobileNumber) == '1'){
        		return $ret_arr = array('area_name'=>'','area'=>'', 'opr_name'=>'', 'operator'=>'','product_id'=>'');
        	}

        	if(isset($mobile_code_digits)){
        		$mobNum = substr($mobileNumber, 0, 5);
        	}
        	else
        	$mobNum = substr($mobileNumber,0,4);

        	$ret_arr = $this->Shop->getMemcache("numDet$mobNum");


                if($ret_arr === false || empty($ret_arr['area']) || empty($ret_arr['operator'])){
					//$this->logData('/var/www/html/shops/abc.txt',date('Y-m-d H:i:s')."Memcached values for $mobileNumber: ".json_encode($ret_arr));
        		if(isset($mobile_code_digits)){
                	$query = "select mn.number, mna.area_name, mns.opr_name, mns.product_id, mn.area, mns.opr_code
        				from mobile_operator_area_map AS mn
        				LEFT JOIN mobile_numbering_area as mna ON mn.area = mna.area_code
        				LEFT JOIN mobile_numbering_service AS mns ON mn.operator = mns.opr_code
        				WHERE mn.number like '$mobNum'";
        		}
        		else {
        			$query = "select mn.number, mna.area_name, mns.opr_name, mns.product_id, mn.area, mns.opr_code
	        			from mobile_numbering AS mn
	        			LEFT JOIN mobile_numbering_area as mna ON mn.area = mna.area_code
	        			LEFT JOIN mobile_numbering_service AS mns ON mn.operator = mns.opr_code
	        			WHERE mn.number like '$mobNum'";
        		}

        		$userObj = ClassRegistry::init('User');

        		$data = $userObj->query($query);
        		//$this->logData('/var/www/html/shops/abc.txt',date('Y-m-d H:i:s')."Table values for $mobileNumber: ".json_encode($data));

        		if(count($data) == 0){
        			//call mobile no detail api of 24x7
        			$ret = $this->getMobileDetailsVia24x7($mobNum."000000");
        			//$this->logData
        			$shortCode = $ret["shortCode"];
        			$operator = $ret["operator"];
        			if(!empty($shortCode) || !empty($operator)){
        				if(isset($mobile_code_digits)){
        					$insert = $userObj->query("insert into mobile_operator_area_map
        							( number, operator, area, updated ) values ( '$mobNum' , '$operator' , '$shortCode', '".date('Y-m-d H:i:s')."') ");
        				}
        				else {
        					$insert = $userObj->query("insert into mobile_numbering
        							( number,operator,area ) values ( '$mobNum' , '$operator' , '$shortCode') ");
        				}
        				if($insert){
        					if(isset($mobile_code_digits)){
        						$query = "select mn.id, mn.number , mna.area_name, mns.opr_name, mns.product_id, mn.area,
        									mns.opr_code
        							from mobile_operator_area_map AS mn
        							LEFT JOIN mobile_numbering_area as mna ON mn.area = mna.area_code
        							LEFT JOIN mobile_numbering_service AS mns ON mn.operator = mns.opr_code
        							WHERE mn.number like '$mobNum'";
        					}
        					else {
        						$query = "select mn.id , mn.number , mna.area_name, mns.opr_name, mns.product_id, mn.area,
	        								mns.opr_code
	        						from mobile_numbering AS mn
	        						LEFT JOIN mobile_numbering_area as mna ON mn.area = mna.area_code
	        						LEFT JOIN mobile_numbering_service AS mns ON mn.operator = mns.opr_code
	        						WHERE mn.number like '$mobNum'";
        					}
        					$data = $userObj->query($query);
        				}

        			}

        		}else if (empty($data['0']['mn']['area']) || empty ($data['0']['mns']['opr_code']) ){
        			$ret = $this->getMobileDetailsVia24x7($mobileNumber);
        			$shortCode = $ret["shortCode"];
        			$operator = $ret["operator"];
        			if(!empty($shortCode) && !empty($operator)){
        				if(isset($mobile_code_digits)){
        					$update = $userObj->query("update mobile_operator_area_map
        							set operator = '$operator', area = '$shortCode', updated = '".date("Y-m-d H:i:s")."'
        							where number like '$mobNum'");

        					$query = "select mn.id, mn.number, mna.area_name, mns.opr_name, mns.product_id, mn.area,
        							mns.opr_code
	        					from mobile_operator_area_map AS mn
	        					LEFT JOIN mobile_numbering_area as mna ON mn.area = mna.area_code
	        					LEFT JOIN mobile_numbering_service AS mns ON mn.operator = mns.opr_code
	        					WHERE mn.number like '$mobNum'";
        				}
        				else {
        					$update = $userObj->query("update mobile_numbering
        							set operator = '$operator', area = '$shortCode', updated = '".date("Y-m-d H:i:s")."'
        							where number like '$mobNum'");

        					$query = "select mn.id, mn.number, mna.area_name, mns.opr_name, mns.product_id, mn.area,
        							mns.opr_code
	        					from mobile_numbering AS mn
	        					LEFT JOIN mobile_numbering_area as mna ON mn.area = mna.area_code
	        					LEFT JOIN mobile_numbering_service AS mns ON mn.operator = mns.opr_code
	        					WHERE mn.number like '$mobNum'";
        				}
        				$data = $userObj->query($query);
        			}
        		}

        		$ret_arr = array('area_name'=>  empty($data['0']['mna']['area_name']) ? "" : $data['0']['mna']['area_name'],'area'=>empty($data['0']['mn']['area']) ? "" : $data['0']['mn']['area'], 'opr_name'=>empty($data['0']['mns']['opr_name']) ? "" :$data['0']['mns']['opr_name'], 'operator'=>empty($data['0']['mns']['opr_code'])?"":$data['0']['mns']['opr_code'],'product_id'=>  empty($data['0']['mns']['product_id']) ? "" : $data['0']['mns']['product_id']);
        		//$this->logData('/var/www/html/shops/abc.txt',date('Y-m-d H:i:s')."Final values for $mobileNumber: ".json_encode($ret_arr));

        		$this->Shop->setMemcache("numDet$mobNum",$ret_arr,24*60*60);
        		//$this->logData('/var/www/html/shops/abc.txt',json_encode($ret_arr));
        	}
        	return $ret_arr;
        }

	function xml2array($contents, $get_attributes=1, $priority = 'tag') {
		if(!$contents) return array();

		if(!function_exists('xml_parser_create')) {
			//print "'xml_parser_create()' function not found!";
			return array();
		}

		//Get the XML parser of PHP - PHP must have this module for the parser to work
		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);

		if(!$xml_values) return;//Hmm...

		//Initializations
		$xml_array = array();
		$parents = array();
		$opened_tags = array();
		$arr = array();

		$current = &$xml_array; //Refference

		//Go through the tags.
		$repeated_tag_index = array();//Multiple tags with same name will be turned into an array
		foreach($xml_values as $data) {
			unset($attributes,$value);//Remove existing values, or there will be trouble

			//This command will extract these variables into the foreach scope
			// tag(string), type(string), level(int), attributes(array).
			extract($data);//We could use the array by itself, but this cooler.

			$result = array();
			$attributes_data = array();

			if(isset($value)) {
				if($priority == 'tag') $result = $value;
				else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
			}

			//Set the attributes too.
			if(isset($attributes) and $get_attributes) {
				foreach($attributes as $attr => $val) {
					if($priority == 'tag') $attributes_data[$attr] = $val;
					else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
				}
			}

			//See tag status and do the needed.
			if($type == "open") {//The starting of the tag '<tag>'
				$parent[$level-1] = &$current;
				if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
					$current[$tag] = $result;
					if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
					$repeated_tag_index[$tag.'_'.$level] = 1;

					$current = &$current[$tag];

				} else { //There was another element with the same tag name

					if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
						$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
						$repeated_tag_index[$tag.'_'.$level]++;
					} else {//This section will make the value an array if multiple tags with the same name appear together
						$current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
						$repeated_tag_index[$tag.'_'.$level] = 2;

						if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
							$current[$tag]['0_attr'] = $current[$tag.'_attr'];
							unset($current[$tag.'_attr']);
						}

					}
					$last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
					$current = &$current[$tag][$last_item_index];
				}

			} elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
				//See if the key is already taken.
				if(!isset($current[$tag])) { //New Key
					$current[$tag] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 1;
					if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

				} else { //If taken, put all things inside a list(array)
					if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

						// ...push the new element into that array.
						$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

						if($priority == 'tag' and $get_attributes and $attributes_data) {
							$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag.'_'.$level]++;

					} else { //If it is not an array...
						$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
						$repeated_tag_index[$tag.'_'.$level] = 1;
						if($priority == 'tag' and $get_attributes) {
							if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

								$current[$tag]['0_attr'] = $current[$tag.'_attr'];
								unset($current[$tag.'_attr']);
							}

							if($attributes_data) {
								$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
					}
				}

			} elseif($type == 'close') { //End of tag '</tag>'
				$current = &$parent[$level-1];
			}
		}

		return($xml_array);
	}

	function authenticatedMailToUsers($emails, $subject, $mail_body, $from, $attachments = null){
		$url = '/groups/shootMail';
		$params['sub'] = $subject;
		$params['body'] = $mail_body;
		$params['from'] = $from;

		if(!empty($attachments)){
			$params['path'] = implode(",",$attachments);
		}
		foreach($emails as $email){
			$params['email'] = trim($email);
			$this->curl_post_async($url,$params);
		}
	}

	function br2newline( $input ) {
		$out = str_replace( "<br>", "\n", $input );
		$out = str_replace( "<br/>", "\n", $out );
		$out = str_replace( "<br />", "\n", $out );
		$out = str_replace( "<BR>", "\n", $out );
		$out = str_replace( "<BR/>", "\n", $out );
		$out = str_replace( "<BR />", "\n", $out );
		return $out;
	}

	function endsWith($haystack,$needle,$case=true)
	{
	  	$expectedPosition = strlen($haystack) - strlen($needle);

	  	if($case) return strrpos($haystack, $needle, 0) === $expectedPosition;

	  	return strripos($haystack, $needle, 0) === $expectedPosition;
	}

	function getHTMLFromNode($node){
		$domDocument1 = new DOMDocument();

		foreach($node->childNodes as $childNode){
			$domDocument1->appendChild($domDocument1->importNode($childNode, true));
		}

		return trim(strip_tags($domDocument1->saveHTML()));
	}

	function RSS_Tags($item, $type)
	{
		$y = array();
		$tnl = $item->getElementsByTagName("title");
		$tnl = $tnl->item(0);
		$title = $tnl->firstChild->textContent;

		$tnl = $item->getElementsByTagName("link");
		$tnl = $tnl->item(0);
		$link = $tnl->firstChild->textContent;

		$tnl = $item->getElementsByTagName("pubDate");
		$tnl = $tnl->item(0);
		$date = $tnl->firstChild->textContent;

		$tnl = $item->getElementsByTagName("description");
		$tnl = $tnl->item(0);
		$description = $tnl->firstChild->textContent;

		$y["title"] = html_entity_decode($title,ENT_QUOTES);
		$y["link"] = $link;
		$y["date"] = date('Y-m-d H:i:s', strtotime($date));
		$y["description"] = html_entity_decode($description,ENT_QUOTES);
		$y["type"] = $type;

		return $y;
	}


	function RSS_Channel($channel)
	{
		$items = $channel->getElementsByTagName("item");

		// Processing channel

		$y = $this->RSS_Tags($channel, 0);		// get description of channel, type 0
		array_push($this->RSS_Content, $y);

		// Processing articles

		foreach($items as $item)
		{
			$y = $this->RSS_Tags($item, 1);	// get description of article, type 1
			array_push($this->RSS_Content, $y);
		}
	}

	function RSS_Retrieve($url)
	{
		$doc  = new DOMDocument();
		$doc->load($url);

		$channels = $doc->getElementsByTagName("channel");

		$this->RSS_Content = array();

		foreach($channels as $channel)
		{
			$this->RSS_Channel($channel);
		}

	}


	function RSS_RetrieveLinks($url)
	{
		$doc  = new DOMDocument();
		$doc->load($url);

		$channels = $doc->getElementsByTagName("channel");

		$this->RSS_Content = array();

		foreach($channels as $channel)
		{
			$items = $channel->getElementsByTagName("item");
			foreach($items as $item)
			{
				$y = $this->RSS_Tags($item, 1);	// get description of article, type 1
				array_push($this->RSS_Content, $y);
			}

		}

	}


	function RSS_Links($url, $size = 15,$mode)
	{
		$page = "";

		$this->RSS_RetrieveLinks($url);
		if($size > 0)
		$recents = array_slice($this->RSS_Content, 0, $size + 1);

		foreach($recents as $article)
		{
			$type = $article["type"];
			if($type == 0) continue;
			$title = $article["title"];
			$link = $article["link"];
			if($mode)
			{
				$page .= "<li><a href=\"$link\">$title</a></li>\n";
			}
			else
			{

				$page .= "# ".$title."<br>\n";
			}
		}

		$page .="\n";

		return $page;

	}

	function RSS_Display($url, $size = 15, $site = 0, $withdate = 0)
	{
		$opened = false;
		$page = "";
		$site = (intval($site) == 0) ? 1 : 0;

		$this->RSS_Retrieve($url);
		if($size > 0)
		$recents = array_slice($this->RSS_Content, $site, $size + 1 - $site);

		return $recents;
	}

	function extractPassword($mobile)
	{
		$userObj = ClassRegistry::init('User');
		$sysPass = $userObj->find('first', array('fields' => array('User.syspass'),'conditions' => array('User.mobile' => $mobile)));
		//$count = $userObj->find('count',array('conditions' => array('User.mobile' => $mobile)));

		if(!empty($sysPass)) return $sysPass['User']['syspass'];
		return false;

	}

	function getBitlyUrl($url){
		//$link = 'http://api.bit.ly/v3/shorten?login='.BITLY_USER.'&apiKey='.BITLY_KEY.'&longUrl='.$url.'&format=txt';
                $link = TINY_URL.'?url='.$url;
		//echo $link; exit;
		$ch = curl_init($link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		$data = curl_exec ($ch);
		curl_close ($ch);
		return $data;
	}


	function curl_post_async($url, $params=null,$type='POST')
	{
		foreach ($params as $key => &$val) {
			if (is_array($val)) $val = implode(',', $val);
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$parts=parse_url($url);

		$fp = pfsockopen($parts['host'],
		isset($parts['port'])?$parts['port']:80,
		$errno, $errstr, 30);

		if(!$fp){
                        $this->logData("curl_asnc.log","Couldn't open a socket to ".$url." (".$errstr.")","Couldn't open a socket to ".$url." (".$errstr.")");
			$this->sendMails("Couldn't open a socket to ".$url." (".$errstr.")","Couldn't open a socket to ".$url." (".$errstr.")",array('tadka@pay1.in'));
			fclose($fp);
			return array('status'=>'failure','errno'=>$errno,'error'=>$errstr);
		}
		else {
			if($type == "GET" && !empty($post_string))
				$parts['path'] .= '?'.$post_string;
			$out = "$type ".$parts['path']." HTTP/1.1\r\n";
			$out.= "Host: ".$parts['host']."\r\n";
			$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out.= "Content-Length: ".strlen($post_string)."\r\n";
			$out.= "Connection: Close\r\n\r\n";
			if ($type == 'POST' && isset($post_string)) $out.= $post_string;

			fwrite($fp, $out);
			fclose($fp);
			return array('status'=>'success');
		}
	}

	function cbzApi($url,$params){

	       $out = $this->curl_post($url,$params,"POST",30,10,true,true,true);
	       return $out;

	}

	function rduApi($url,$params){
		foreach ($params as $key => &$val) {
				$post_params[] = $key.'='.urlencode($val);
			}
			$post_string = implode('&', $post_params);
			$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
			return $out;

	}

	function uvaApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.$val;
		}
		$post_string = implode('&', $post_params);
		$data['uva'] = base64_encode($post_string);
		$out = $this->curl_post($url,$data,"POST",30,10,true,true,true);
		//$this->printArray($out);
		return $out;
	}

	function uniApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
		return $out;
	}

	function anandApi($url,$params){
	    foreach ($params as $key => &$val) {
				$post_params[] = $key.'='.urlencode($val);
			}
			$post_string = implode('&', $post_params);
			$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
			return $out;

	}

	function apnaApi($url,$params){
	    foreach ($params as $key => &$val) {
				$post_params[] = $key.'='.urlencode($val);
			}
			$post_string = implode('&', $post_params);
			$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
			return $out;
	}

	function magicApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
		return $out;
	}

	function rioApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
		return $out;
	}

	function gemApi($url,$params){
	       $out = $this->curl_post($url,$params,'POST',30,10,true,true,true);
		/*foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET');*/
		$this->logData($_SERVER['DOCUMENT_ROOT']."/logs/gem.txt",date('Y-m-d H:i:s').":Request Sent: ".$url."::".json_encode($params));

		return $out;
	}

	function durgaApi($url,$params){
	    foreach ($params as $key => &$val) {
				$post_params[] = $key.'='.urlencode($val);
			}
			$post_string = implode('&', $post_params);
			$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
			return $out;
	}

	function rkitApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);

		$out['output'] = $this->xml2array("<NODE>".$out['output']."</NODE>");
		$this->logData($_SERVER['DOCUMENT_ROOT']."/logs/rkitresponse.txt",date('Y-m-d H:i:s').":Request Sent: ".$url."::".json_encode($out));

		return $out;
	}

	function joinrecApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);

		$out['output'] = $this->xml2array($out['output']);
		return $out;
	}

	function a2zApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);

		///$out['output'] = $this->xml2array($out['output']);
		return $out;
	}

    function smsdaakApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
		$this->logData($_SERVER['DOCUMENT_ROOT']."/logs/smsdaak.txt","url : ".$url."?".$post_string."  | output : ".json_encode($out));
		$out['output'] = $this->xml2array($out['output']);
		return $out;
	}

        function ccavenueApi($url, $xml, $trans_id = NULL) {

                $certificate = "*ccavenuecom.crt";
                $request_id = ($trans_id == NULL || strlen($trans_id) < 35) ? substr("pay1".md5(uniqid(rand(), true)),0,35) : $trans_id;
                $post_string = array('accessCode'=>CCAVENUE_ACCESS_CODE, 'requestId'=>$request_id, 'encRequest'=>$xml, 'ver'=>CCAVENUE_VERSION, 'instituteId'=>CCAVENUE_INSTITUTION_ID);
                $post_params = $post_string;
                $post_params['encRequest'] = $this->ccavenueEncrypt($post_string['encRequest'], CCAVENUE_KEY);
                $out = $this->curl_post($url,$post_params,'POST',100,10,true,true,true,false,$certificate);

		$this->logData($_SERVER['DOCUMENT_ROOT']."/logs/ccavenue.txt",date('Y-m-d H:i:s')." :: URL : ".$url." | Params : ".json_encode($post_string)." | Output : ".json_encode($out));

		$out['output'] = $this->xml2array($this->ccavenueDecrypt($out['output'], CCAVENUE_KEY));

		return $out;
        }

        function ccavenueEncrypt($plainText, $key) {
		$secretKey = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
	  	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
		$plainPad = $this->pkcs5_pad($plainText, $blockSize);
	  	if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1)
		{
		      $encryptedText = mcrypt_generic($openMode, $plainPad);
	      	      mcrypt_generic_deinit($openMode);

		}
		return bin2hex($encryptedText);
	}

        function ccavenueDecrypt($encryptedText, $key) {
		$secretKey = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText=$this->hextobin($encryptedText);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
		mcrypt_generic_init($openMode, $secretKey, $initVector);
		$decryptedText = mdecrypt_generic($openMode, $encryptedText);
		$decryptedText = rtrim($decryptedText, "\0");
	 	mcrypt_generic_deinit($openMode);
		return $decryptedText;
	}

        function pkcs5_pad ($plainText, $blockSize) {
                $pad = $blockSize - (strlen($plainText) % $blockSize);
                return $plainText . str_repeat(chr($pad), $pad);
	}

        function hextobin($hexString) {
                $length = strlen($hexString);
                $binString="";
                $count=0;
                while($count<$length) {
                        $subString =substr($hexString,$count,2);
                        $packedString = pack("H*",$subString);
                        if ($count==0) {
                                $binString=$packedString;
                        } else {
                                $binString.=$packedString;
                        }
                        $count+=2;
                }
                return $binString;
    	}

    function aporecApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
        //formatting output using anonymous function
        $format_output = function($input){ $input_arr = explode("~",$input); $output_arr = array(); foreach( $input_arr as $k=>$v ): if( ( $k ) % 2 == 0): continue;  else: $output_arr[ $input_arr[$k-1] ]=$v; endif; endforeach;  return $output_arr; };
        $output = $format_output($out['output']);

		$out['output'] = !empty($output) ? $output : $out['output'];
		return $out;
	}

    function mypayApi($url,$params){
        foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
		$this->logData($_SERVER['DOCUMENT_ROOT']."/logs/mypay.txt","url : ".$url."?".$post_string."  | output : ".$out);
		$out['output'] = $this->xml2array($out['output']);
		return $out;
	}

    function hitechrecApi($url,$params){
        foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
		//$out = $this->curl_post($url."?".$post_string,null,'GET',null,null,null,false);
		$this->logData($_SERVER['DOCUMENT_ROOT']."/logs/hitechrec.txt","url : ".$url."?".$post_string."  | output : ".$out);
		$out['output'] = $this->xml2array($out['output']);
		return $out;
	}

	function practicApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
		return $out;
	}



	function simpleApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);
		return $out;
	}
    /**
     * It will check trans_pullback table if record already exist it will update it or else insert it
     * @param type $data = array
     */
    function manage_transPullback($dataparam = array()){
        if(!empty($dataparam) && isset($dataparam['vendors_activations_id'])){
            $vend_actId = $dataparam['vendors_activations_id'];
            $qrystr = "SELECT * from `trans_pullback` where vendors_activations_id='$vend_actId'";
            $insertQry = "";
            $updateQry = "UPDATE `trans_pullback` SET ";
            $updateQry_ext = "";

            foreach($dataparam as $par=>$val){
                if(in_array($par,array('vendor_id','status','timestamp','pullback_by','pullback_time','reported_by','date'))){
                    $updateQry_ext = trim($updateQry_ext);
                    $updateQry_ext .= ( !empty($updateQry_ext)) ? ", ".$par."='$val'" : $par."='$val'";
                }
            }

            $updateQry .= $updateQry_ext." WHERE vendors_activations_id=".$dataparam['vendors_activations_id'];

            $insertQry .=  "INSERT INTO `trans_pullback` (".  implode(",",array_keys($dataparam)).") VALUES ('".  implode("','", array_values($dataparam))."')";

            $retailObj = ClassRegistry::init('Retailer');// create models object for db connection

            $pullback_result = $retailObj->query($qrystr);

            if(!empty($pullback_result)){
                $retailObj->query($updateQry);
            }else{
                $retailObj->query($insertQry);
            }
        }
    }

	function gitechApiOld($method,$requestXML,$func=null,$pars=null){

		App::import('vendor', 'soap', array('file' => 'soaplib/nusoap.php'));
		$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
		$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
		$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
		$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
		$useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
		$namespace  = GITECH_NAMESPACE;
		$client = new nusoap_client(GI_URL, false,$proxyhost, $proxyport, $proxyusername, $proxypassword);
		$client->setUseCurl($useCURL);
		$err = $client->getError();
                if($useCURL){
                    $client->setCurlOption(CURLOPT_PROXY,"http://".PROXY_IP_PRIVATE.":".PROXY_PORT);
                }
		$security = '<pobjSecurity><WebProviderId>0</WebProviderId><WebProviderLoginId>'.GI_LOGINID.'</WebProviderLoginId><WebProviderPassword>'.GI_PASSWORD.'</WebProviderPassword><IsAgent>false</IsAgent></pobjSecurity>';
		if(!empty($requestXML))$requestXML = "<PstrInput>".htmlspecialchars($requestXML)."</PstrInput>";
		$params = $security.$requestXML.'<PstrFinalOutPut /><pstrError />';
		$headers = '<ns1:clsSecurity soap:mustUnderstand="false" xmlns:ns1="'.GITECH_NAMESPACE1.'"><ns1:WebProviderLoginId>'.GI_LOGINID.'</ns1:WebProviderLoginId><ns1:WebProviderPassword>'.GI_PASSWORD.'</ns1:WebProviderPassword><ns1:IsAgent>false</ns1:IsAgent></ns1:clsSecurity>';

		$res =  $client->call($method, $params,$namespace,$namespace.''.$method,$headers);
		//echo $res;
		//print_r($res);
		return $this->xml2array($res['PstrFinalOutPut']);
	}

	function gitechApi_mnytfr($method,$requestXML){


		$url = GI_URL_MNYTFR."/".$method."?RequestData=".urlencode($requestXML);

		$out = $this->curl_post($url,null,'GET');
		return $out;
	}

        function gitechApi($url,$params)
        {
            $json_request = json_encode($params);
            $headers = array(
            'Content-Type: application/json',
            );
            // Build the cURL session
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_request);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_PROXY, "http://".PROXY_IP_PRIVATE.":".PROXY_PORT);

            // Send the request and check the response
            if (($result = curl_exec($ch)) === FALSE) {
		$this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", "Request :: $json_request Response :: " . $result);
            die('cURL error: '.curl_error($ch)."<br />\n");
            } else {
            return $result;
            }
            curl_close($ch);
        }

	function curl_post($url, $params=null,$type='POST',$timeout=30,$connect_timeout=10,$follow_loc=true,$user_agent=true,$useproxy=false,$addonlog=false,$client_cert=null,$httpHeader=NULL)
	{ 
		if(empty($params)){
			$post_string = "";
		}
		else if(is_array($params)){
			foreach ($params as $key => &$val) {
				if (is_array($val)) $val = implode(',', $val);
				if($key != 'uva')$post_params[] = $key.'='.urlencode($val);
				else $post_params[] = $val;
			}
			$post_string = implode('&', $post_params);
		}
		else {
			$post_string = $params;
		}
        //echo $url;
        //echo $params;
		$ch = curl_init($url);
		if($type == 'POST'){
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
		}
		else {
			curl_setopt($ch, CURLOPT_POST,0);
		}

                if(!empty($client_cert)) {
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                        curl_setopt($ch, CURLOPT_CAINFO,  getcwd().'/certificate/'.$client_cert);
                        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
                }

		$agent = 'Mozilla/4.73 [en] (X11; U; Linux 2.2.15 i686)';
		if($user_agent) curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		if($follow_loc) curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
		if($useproxy && USE_PROXY) curl_setopt($ch, CURLOPT_PROXY, "http://".PROXY_IP_PRIVATE.":".PROXY_PORT);

		curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS
                if(!empty($httpHeader))
                {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
                }
		curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		$out = trim(curl_exec($ch));
//		var_dump($out);
		$info = curl_getinfo($ch);

		if($info['connect_time'] > 10 || $info['total_time'] > 10 || $addonlog){
                    if($addonlog){
                        $this->logData('curl_log.txt',"[".date('Y-m-d H:i:s')."] Took ".$info['total_time']." seconds to send request of $post_string to $url & took ".$info['connect_time']." seconds to connect | response:".  json_encode($out));
                    }else{
                        $this->logData('curl_log.txt',"[".date('Y-m-d H:i:s')."] Took ".$info['total_time']." seconds to send request of $post_string to $url & took ".$info['connect_time']." seconds to connect");
                    }
		}
		if(!curl_errno($ch)){
			curl_close($ch);
			return array('output'=>$out,'success'=>true,'timeout'=>false);
		}
		else {
			$errno = curl_errno($ch);
			curl_close($ch);
			if(in_array($errno,array(6,7)) || $info['connect_time'] > $connect_timeout){
				$this->logData('curl_log_error.txt',"[".date('Y-m-d H:i:s')."] Curl Error $errno: Took ".$info['total_time']." seconds to send request of $post_string to $url & took ".$info['connect_time']." seconds to connect, default connection timeout is $connect_timeout, success: false, timeout: true");

                        return array('output'=>$out,'success'=>false,'timeout'=>true);//connection timeout
			}
			else {
				if($connect_timeout >= 10){
				    $this->logData('curl_log_error.txt',"[".date('Y-m-d H:i:s')."] Curl Error $errno: Took ".$info['total_time']." seconds to send request of $post_string to $url & took ".$info['connect_time']." seconds to connect, default connection timeout is $connect_timeout, success: false, timeout: false");
                                    $parseUrl = parse_url(trim($url));
                                    $domain_name = trim($parseUrl['host'] ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2)));
                                    shell_exec('nohup sh '. $_SERVER['DOCUMENT_ROOT'] . '/scripts/traceroute.sh '.$domain_name.' '.$post_string.' &');
				}
                                
				return array('output'=>$out,'success'=>false,'timeout'=>false);//timeout
			}
		}
	}

	function b2c_pullback($transId,$refid){
		$url = B2C_URL."actiontype/pullback/api/true";
		$data = array('client_req_id'=>$transId,'trans_id'=>$refid);
		$Rec_Data = $this->curl_post($url,$data,'POST',30,10,true,true,true);

		if(!$Rec_Data['success']){
			if($Rec_Data['timeout']){
				return array('status'=>'failure','description'=>'Cannot connect to server');
			}
			else {
				return array('status'=>'failure','description'=>'Response timeout, please try again');
			}
		}

		$this->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1.txt",date('Y-m-d H:i:s').":Request Sent: ".$url."::".json_encode($data)."::output: ".$Rec_Data['output']);

		$out = json_decode($Rec_Data['output'],true);
		if($out['status'] == 'failure' && in_array($out['errCode'],array('505','217'))){//505 if txn doesn't exists
			$out['status'] = 'success';
		}

		return $out;
	}

	function mobileValidate($phone){
		$err = '';
		if(!ereg("^[6-9]{1}[0-9]{9}$", $phone)) {
			$err = 1;
		}
		return $err;
	}

	function numberValidate($number){
		$err = '';
		if(!is_numeric($number)) {
			$err = 1;
		}
		return $err;
	}

	function priceValidate($price)
	{
		if(is_numeric($price)&& $price>0)
		return sprintf('%01.2f', round($price, 2));
		else
		return '';
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
        
        function getRetailerSignature($retailer_id){
		$retailObj = ClassRegistry::init('Retailer');
		$retailObj->recursive = -1;
		$sign = $retailObj->find('first',array('fields' => array('Retailer.signature', 'Retailer.signature_flag'), 'conditions' => array('Retailer.id' => $retailer_id)));
		return $sign;
	}
        
        function dateValidate($date){
                if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/",$date)) {
                        return true;
                } else if (preg_match("/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/", $date)) {
                        return true;            
                } else {
                        return false;
                }
        }
        
        function timeValidate($datetime){
            $date = date('Y-m-d',strtotime($datetime));
            if ($date == '1970-01-01') {
                return false;
            } else {
                return true;
            }
        }

	function checkDND($mobile){
		return;
		$url = DND_CHECK_URL;
		$params = "phoneno=$mobile"; //you must know what you want to post
		$user_agent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);

		$page=curl_exec ($ch);
		if($page)
		{
			$domDocument=new DOMDocument();
			$domDocument->loadHTML($page);
			$xpath = "//td[contains(concat(' ',normalize-space(@class),' '),' GridHeader ')]";

			$domXPath = new DOMXPath($domDocument);
			$domNodeList = $domXPath->query($xpath);
			$ret = -1;
			foreach($domNodeList as $node){
				$domDocument1 = new DOMDocument();
				foreach($node->childNodes as $childNode){
					$domDocument1->appendChild($domDocument1->importNode($childNode, true));
					$html = trim(strip_tags($domDocument1->saveHTML()));
					if($html == 'The number is not registered in NCPR'){
						$ret = 0;
					}
					else {
						$ret = 1;
					}
				}
			}
			$return['dnd'] = $ret;
			if($ret == 1){
				$xpath = "//tr[15]/td";

				$domXPath = new DOMXPath($domDocument);
				$domNodeList = $domXPath->query($xpath);
				foreach($domNodeList as $node){
					$domDocument1 = new DOMDocument();
					foreach($node->childNodes as $childNode){
						$domDocument1->appendChild($domDocument1->importNode($childNode, true));
					}
					$html = substr(trim(strip_tags($domDocument1->saveHTML())),-1,1);
					$return['preference'] = $html;
				}
			}
			return $return;
			//return $content;
		}
		else {
			$return['dnd'] = -1;
			return $return;
		}
		curl_close ($ch);
	}


	function updateLocation($area_id){
		$retailObj = ClassRegistry::init('Retailer');

		$areaArr = $retailObj->query("SELECT area_id FROM retailers where id = ".$area_id);
		foreach($areaArr as $a){
			$retailObj->query("update locator_area set toShow = 1 where id =".$a['retailers']['area_id']);
			$cityArr = 	$retailObj->query("SELECT city_id FROM locator_area where id = ".$a['retailers']['area_id']);
			foreach($cityArr as $c){
				$retailObj->query("update locator_city set toShow = 1 where id =".$c['locator_area']['city_id']);
				$stateArr = $retailObj->query("SELECT state_id FROM locator_city where id = ".$c['locator_area']['city_id']);
				foreach($stateArr as $s){
					$retailObj->query("update locator_state set toShow = 1 where id =".$s['locator_city']['state_id']);
				}
			}
		}

		$this->autoRender = false;
	}


	function getFileFromDirectory($type, $retId) {

        $filename = $_SERVER["DOCUMENT_ROOT"] . "/uploads/";
        $filetoget = $type . $retId;
        $array = array();
        $data = scandir($filename);
        foreach ($data as $key) {
            $explode = explode('_', $key);
            if (count($explode)>1) {
                if ($explode[0] . "_" . $explode[1] == $filetoget) {
                    $array[$explode[0]][] = $key;
                }
            }
        }
        return $array;
    }

    function maskNumber($n){
		return substr($n,0,6)."XXXX";
	}

	function logData($file,$data){
		$file = "/mnt/logs/".basename($file);
		$fh = fopen($file,'a+');
		fwrite($fh,date('Y-m-d H:i:s')."::$data\n");
		fclose($fh);
	}

	function matchTemplate($sms, $template,$varStart="@__",$varEnd="__@"){
		$sms = trim($sms);
		$template = trim($template);
		$template = str_replace($varStart,"|~|",$template);
		$template = str_replace($varEnd,"|~|",$template);

		$t=explode("|~|",$template);

		$vars = array();
		$ret = true;
		$i = 0;
		$start = 0;
		$log = "";

		$out['sms'] = $sms;
		for($i=0;$i<=count($t);$i=$i+2){
			if($t[$i] == null){
				if($i != 0){
					$vars[$start] = $sms;
					$vars[$t[$i-1]] = $sms;
				}
			}
			else {
				$log .= "Checking ".$t[$i];
				$index = strpos($sms,$t[$i]);
				$log .= ": $index\n";
				if($index === false){
					$ret = false;
					break;
				}
				else {
					$var = substr($sms,0,$index);
					if($i != 0){
						$vars[$start] = trim($var);
						$vars[$t[$i-1]] = trim($var);
						$start++;
					}
					$sms = substr($sms,$index+strlen($t[$i]));
				}
			}
		}

		if(count($t) == 1 && $out['sms'] != $template){
			$ret = false;
		}

		if($ret){
			$out['status'] = 'success';
			$out['vars'] = $vars;
		}
		else {
			$out['status'] = 'failure';
			$out['vars'] = $vars;
		}
		$out['logs'] = $log;
		return $out;
	}

	function createAppDownloadUrl($type,$app_number){
		App::import('vendor', 'md5Crypt', array('file' => 'md5Crypt.php'));
		$objMd5 = new Md5Crypt;
		if($type == DISTRIBUTOR){
			if($app_number == 1)$fname = DISTRIBUTOR_APP_FILE_1;
			else if($app_number == 2)$fname = DISTRIBUTOR_APP_FILE_2;
		}
		else if($type == RETAILER){
			if($app_number == 1)$fname = RETAILER_APP_FILE_1;
			else if($app_number == 2)$fname = RETAILER_APP_FILE_2;
		}

		if($app_number == 1){
			$url = $this->getBitlyUrl(PLAY_STORE_APP_URL);
		}
		else $url = PAY1_APP_URL.$fname;
		return $url;
		//return $this->getBitlyUrl($url);
	}

	function getRetailerList($dist_id,$sid=null,$xfer=false,$retId=null,$default_slmn=null,$servId = null){	
            $query = 1;
		if($sid != null){
			$query = "Retailer.maint_salesman = $sid";
		}
		if($retId!=null && $retId!=0){
			$query.= " AND Retailer.id = $retId";
		}
		if($servId!=null && $servId!=0){
			$query.= " AND us.service_id = $servId";
		}

		$retailObj = ClassRegistry::init('Retailer');
		$retailers = $retailObj->find('all', array(
					'fields' => array('Retailer.*', 'users.mobile','users.balance','users.opening_balance', 'ur.*', 'us.*'/* ,'max(user_profile.longitude != 0' */),
					'conditions' => array('Retailer.parent_id' => $dist_id, 'Retailer.toshow' => 1, $query),
					'joins' => array(
							array(
									'table' => 'users',
									'type' => 'left',
									'conditions' => array('Retailer.user_id = users.id')
							),
							array(
									'table' => 'unverified_retailers as ur',
									'type' => 'left',
									'conditions'=> array('ur.retailer_id = Retailer.id')
							),
							array(
									'table' => 'users_services as us',
									'type' => 'left',
									'conditions'=> array('us.user_id = Retailer.user_id')
							)                                                                                        
					),
					'order' => 'Retailer.shopname asc',
					'group' => 'Retailer.id'
					)
			);
                

        /** IMP DATA ADDED : START**/
         $ret_ids = array_map(function($element){
            return $element['Retailer']['id'];
        },$retailers);

        $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
        $retailer_imp_label_map = array(
            'pan_number' => 'pan_no',
            'shopname' => 'shop_est_name',
            'alternate_number' => 'alternate_mobile_no',
            'email' => 'email_id',
            'shop_structure' => 'shop_ownership',
            'shop_type' => 'business_nature'
        );
        foreach ($retailers as $key => $retailer) {
            foreach ($retailer['Retailer'] as $retailer_label_key => $value) {
                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['Retailer']['id']]['imp']) ){
                    $retailers[$key]['Retailer'][$retailer_label_key] = $imp_data[$retailer['Retailer']['id']]['imp'][$retailer_label_key_mapped];
                }
            }
            foreach ($retailer['ur'] as $retailer_label_key => $value) {
                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['Retailer']['id']]['imp']) ){
                    $retailers[$key]['ur'][$retailer_label_key] = $imp_data[$retailer['Retailer']['id']]['imp'][$retailer_label_key_mapped];
                }
            }
        } 
        /** IMP DATA ADDED : END**/


		foreach($retailers as $k => $r){
			foreach($retailers[$k]['ur'] as $key => $row){
				if(!in_array($key, array('id')))
					$retailers[$k]['Retailer'][$key] = $retailers[$k]['ur'][$key];
			}
		}

		if($xfer){
			$retArray = array();
			//$query = $retailObj->query("SELECT  sum(`shop_transactions`.`amount`) as xfer,target_id as retId  FROM `shop_transactions`   where `shop_transactions`.`date` = '".date('Y-m-d')."' AND `shop_transactions`.`type` IN ('".DIST_RETL_BALANCE_TRANSFER."','".SLMN_RETL_BALANCE_TRANSFER."') AND `shop_transactions`.`confirm_flag` != 1 and source_id IN ('".$dist_id."','".$default_slmn."') group by retId ORDER BY xfer DESC");

			$query = $retailObj->query("SELECT sum(`shop_transactions`.`amount`) as xfer,target_id as retId  FROM `shop_transactions` use index (type_date) LEFT JOIN `retailers` ON (`retailers`.`id` = `shop_transactions`.`target_id`) where `shop_transactions`.`date` = '".date('Y-m-d')."' AND `shop_transactions`.`type` IN ('".DIST_RETL_BALANCE_TRANSFER."','".SLMN_RETL_BALANCE_TRANSFER."') AND `shop_transactions`.`confirm_flag` != 1 and `retailers`.`parent_id` = '".$dist_id."' group by retId ORDER BY xfer DESC");

			foreach ($query as $key){
				$retArray[$key['shop_transactions']['retId']] = $key['0']['xfer'];
			}
			$record =  array();
			foreach ($retailers as $retdetails) {
				$record[$retdetails['Retailer']['id']] = $retdetails;
				if(!empty($retArray[$retdetails['Retailer']['id']])){
					$record[$retdetails['Retailer']['id']][]['xfer'] = $retArray[$retdetails['Retailer']['id']];
				}
				else {
					$record[$retdetails['Retailer']['id']][]['xfer'] = 0;
			 	}
			}

                        $record  = $this->array_sort_by_column($record,0);
                        $retRecords =  array();
                        foreach($record as $val){
                                $retRecords[$val[0]['Retailer']['id']] = $val[0];
                        }
                        $retailers = $retRecords;

		}

                $rets = array();
                foreach($retailers as $ret) {
                        $ret['Retailer']['balance'] = $ret['users']['balance'];
                        $ret['Retailer']['opening_balance'] = $ret['users']['opening_balance'];
                        $rets[] = $ret;
                }

		return $rets;
	}
        function sendTemplateEmailToAdmin($templateId,$varParseArr){
		$emailTemplate = $this->getTemplateEmail($templateId);
                $emailTemplate['body'] = $this->parseMsg($emailTemplate['body'],$varParseArr);
                $this->sendMails($emailTemplate['subject'],$emailTemplate['body']);

	}
        function getTemplateEmail($tempId){
                return GeneralComponent::$emailTemplates[$tempId];
        }
        function sendTemplateSMSToMobile($mobNo,$templateId,$varParseArr=array()){
		$smsTemplate = $this->getTemplateSMS($templateId);
                $smsTemplate['msg'] = $this->parseMsg($smsTemplate['msg'],$varParseArr);
                //$this->makeOptIn247SMS($mobNo);
                $this->sendMessage($mobNo,$smsTemplate['msg'],'shops');
	}
        function getTemplateSMS($tempId){
                return GeneralComponent::$smsTemplates[$tempId];
        }
        function parseMsg($msg , $varArr){
                $outPutStr = $msg;
                foreach($varArr as $key=>$value){
                    $outPutStr = str_replace('@'.$key.'@', $value, $outPutStr);
                }
                return $outPutStr;
        }
        function getTransferTypeName ($type){
            $trans_type = "";
            //$transaction['shop_transactions']['type']
            if($type == ADMIN_TRANSFER)
                $trans_type =  'Balance Transferred by Company';
            else if($type == MDIST_DIST_BALANCE_TRANSFER)
                $trans_type =  'Balance Transferred by Company';
            else if($type == DIST_RETL_BALANCE_TRANSFER)
                $trans_type =  'Balance Transferred by Distributor';
            else if($type == DISTRIBUTOR_ACTIVATION)
                $trans_type =  'DISTRIBUTOR ACTIVATION';
            else if($type == RETAILER_ACTIVATION)
                $trans_type =  'RETAILER ACTIVATION';
            else if($type == COMMISSION_MASTERDISTRIBUTOR)
                $trans_type =  'COMMISSION MASTERISTRIBUTOR';
            else if($type == COMMISSION_RETAILER)
                $trans_type =  'COMMISSION RETAILER';
            else if($type == TDS_MASTERDISTRIBUTOR)
                $trans_type =  'TDS MASTER DISTRIBUTOR';
            else if($type == TDS_DISTRIBUTOR)
                $trans_type =  'TDS DISTRIBUTOR';
            else if($type == TDS_RETAILER)
                $trans_type =  'TDS RETAILER';
            else if($type == REVERSAL_RETAILER)
                $trans_type =  'REVERSAL RETAILER';
            else if($type == REVERSAL_DISTRIBUTOR)
                $trans_type =  'REVERSAL DISTRIBUTOR';
            else if($type == REVERSAL_MASTERDISTRIBUTOR)
                $trans_type =  'REVERSAL MASTERDISTRIBUTOR';
            else if($type == DEBIT_NOTE)
                $trans_type =  'DEBIT NOTE';
            else if($type == CREDIT_NOTE)
                $trans_type =  'CREDIT NOTE';
            else if($type == SETUP_FEE)
                $trans_type =  'TDS DISTRIBUTOR';
            else if($type == REFUND)
                $trans_type =  'REFUND';
            else if($type == RENTAL)
                $trans_type =  'RENTAL';
            else if($type == PULLBACK_RETAILER)
                $trans_type =  'PULLBACK_RETAILER';
            else if($type == PULLBACK_DISTRIBUTOR)
                $trans_type =  'PULLBACK_DISTRIBUTOR';
            else if($type == PULLBACK_MASTERDISTRIBUTOR)
                $trans_type =  'PULLBACK_MASTERDISTRIBUTOR';
            else if($type == SERVICE_CHARGE)
                $trans_type =  'SERVICE_CHARGE';
            else if($type == DIST_SLMN_BALANCE_TRANSFER)
                $trans_type =  'DIST_SLMN_BALANCE_TRANSFER';
            else if($type == SLMN_RETL_BALANCE_TRANSFER)
                $trans_type =  'SLMN_RETL_BALANCE_TRANSFER';
            else if($type == PULLBACK_SALESMAN)
                $trans_type =  'PULLBACK_SALESMAN';

            return $trans_type;
        }

		function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {

		$sort_col = array();
		foreach ($arr as $key => $row) {
			$sort_col[$key] = $row[$col];
			$sort_col[$key][] = $row;
		}

		array_multisort($sort_col, $dir, $arr);
		return $sort_col;
	}

	function get_location_by_area_id($area_id){
		if($area_id){
			$Retailer = ClassRegistry::init('Retailer');
			$location = $Retailer->query("select c.id, a.name, s.id, c.name, s.name
					from locator_area a
					join locator_city c on c.id = a.city_id
					join locator_state s on s.id = c.state_id
					where a.id = '".$area_id."'");
			if($location){
				return array(	'area_id' => $area_id, 'area' => $location[0]['a']['name'],
								'city_id' => $location[0]['c']['id'], 'city' => $location[0]['c']['name'],
								'state_id' => $location[0]['s']['id'], 'state' => $location[0]['s']['name']
				);
			}
			else
				return null;
		}
		else
			return null;
	}

	function kyc_level($retailer_id){
		$filename = "kyc_level_".date('Ymd').".txt";
		$this->logData('/mnt/logs/'.$filename,"inside kyc_level retailer_id::".$retailer_id);
		if(isset($retailer_id)){
			$Retailer = ClassRegistry::init('Retailer');
			$retailer_documents = $Retailer->query("select *
				from retailers_details
				where type in ('idProof', 'addressProof', 'shop')
				and retailer_id = ".$retailer_id);
			$this->logData('/mnt/logs/'.$filename,"inside kyc_level retailer_documents::".json_encode($retailer_documents));
			if(!empty($retailer_documents)){
				$level = 0;
				$images_count = array(
						'addressProof' => 0,
						'idProof' => 0,
						'shop' => 0
				);
				foreach($retailer_documents as $rd){
					if($rd['retailers_details']['type'] == 'addressProof' && $rd['retailers_details']['verify_flag'] == '1')
						$images_count['addressProof'] += 1;
					else if($rd['retailers_details']['type'] == 'idProof' && $rd['retailers_details']['verify_flag'] == '1')
						$images_count['idProof'] += 1;
					else if($rd['retailers_details']['type'] == 'shop' && $rd['retailers_details']['verify_flag'] == '1')
						$images_count['shop'] += 1;
				}
				$images_count['addressProof'] > 0 && $level += 0.3;
				$images_count['idProof'] > 0 && $level += 0.3;
				$images_count['shop'] > 0 && $level += 0.4;

				$this->logData('/mnt/logs/'.$filename,"inside kyc_level image_count and level::".$level."::".json_encode($images_count));
				$level = $level > 1 ? 1 : $level;
				return $level;
			}
			else
				return 0;
		}
		else
			return 0;
	}

	function update_verify_flag($retailer_id){
		$filename = "update_verify_flag_".date('Ymd').".txt";
		$this->logData('/mnt/logs/'.$filename, "inside update_verify_flag::".$retailer_id);
		if(isset($retailer_id)){
			$Retailer = ClassRegistry::init('Retailer');
			$retailer_documents = $Retailer->query("select * from retailers_details
							where retailer_id = ".retailer_id);
			$this->logData('/mnt/logs/'.$filename, "inside update_verify_flag after retailer_documents::".json_encode($retailer_documents));
			$types = array();
			$verify_flag = 1;$documents_submitted = 1;
			foreach($retailer_documents as $rd){
				if(in_array($rd['retailers_details']['type'], array('idProof', 'addressProof', 'shop'))){
					$types[] = $rd['retailers_details']['type'];
					if($rd['retailers_details']['verify_flag'] == "0"){
						$verify_flag = 0;
					}
				}
			}
			$this->logData('/mnt/logs/'.$filename, "inside update_verify_flag type and verify_flag:".$verify_flag."::".json_encode($types));
			$unique_types = array_unique($types);
			$array_diff = array_diff(array('idProof', 'addressProof', 'shop'), $unique_types);

			if(!empty($array_diff)){
				$documents_submitted = 0;
			}
			if($documents_submitted && $verify_flag){
				$verify_flag = 1;
			}
			else if($documents_submitted && !$verify_flag){
				$verify_flag = 2;
			}
			else
				$verify_flag = 0;
			$this->logData('/mnt/logs/'.$filename, "inside update_verify_flag type after array_diff::verify_flag:".$verify_flag.":documents_submitted:".$documents_submitted."::".json_encode($array_diff));
			if($verify_flag == 1){
				$unverified_retailers = $Retailer->query("select * from unverified_retailers ur
						where ur.retailer_id = ".$retailer_id);
				$retailers = $Retailer->query("select * from retailers r
						where r.id = ".$retailer_id);
				if($unverified_retailers){
					$this->logData('/mnt/logs/'.$filename, "inside update_verify_flag unverified_retailers::".json_encode($unverified_retailers));
					$Retailer->query("update retailers
							set name = '".mysql_real_escape_string($unverified_retailers[0]['ur']['name'])."',
							shopname = '".mysql_real_escape_string($unverified_retailers[0]['ur']['shop_name'])."',
							area_id = '".$unverified_retailers[0]['ur']['area_id']."',
							area = '".$unverified_retailers[0]['ur']['area']."',
							address = '".mysql_real_escape_string($unverified_retailers[0]['ur']['address'])."',
							pin = '".$unverified_retailers[0]['ur']['pin_code']."',
							shop_type = '".$unverified_retailers[0]['ur']['shop_type']."',
							mobile_info = '".mysql_real_escape_string($unverified_retailers[0]['ur']['shop_type_value'])."',
							location_type = '".$unverified_retailers[0]['ur']['location_type']."',
							verify_flag = 1,
							modified = '".date('Y-m-d H:i:s')."'
                            where id = ".$retailer_id);

                /** IMP DATA ADDED : START**/
                $imp_update_data = array(
                    'name' => mysql_real_escape_string($unverified_retailers[0]['ur']['name']),
                    'shopname' => mysql_real_escape_string($unverified_retailers[0]['ur']['shop_name']),
                    'address' => mysql_real_escape_string($unverified_retailers[0]['ur']['address']),
                    'shop_type' => $unverified_retailers[0]['ur']['shop_type']
                );
                $response = $this->Shop->updateUserLabelData($retailer_id,$imp_update_data,$this->Session->read('Auth.User.id'),2);
                /** IMP DATA ADDED : END**/

					$this->logData('/mnt/logs/'.$filename, "inside update_verify_flag update retailers query::"."update retailers
							set name = '".mysql_real_escape_string($unverified_retailers[0]['ur']['name'])."',
							shopname = '".mysql_real_escape_string($unverified_retailers[0]['ur']['shop_name'])."',
							area_id = '".$unverified_retailers[0]['ur']['area_id']."',
							area = '".$unverified_retailers[0]['ur']['area']."',
							address = '".mysql_real_escape_string($unverified_retailers[0]['ur']['address'])."',
							pin = '".$unverified_retailers[0]['ur']['pin_code']."',
							shop_type = '".$unverified_retailers[0]['ur']['shop_type']."',
							mobile_info = '".mysql_real_escape_string($unverified_retailers[0]['ur']['shop_type_value'])."',
							location_type = '".$unverified_retailers[0]['ur']['location_type']."',
							verify_flag = 1,
							modified = '".date('Y-m-d H:i:s')."'
							where id = ".$retailer_id);
					$message = "Your KYC is now verified. Your Click-To-Call service is now activated.";
					$this->sendMessage($retailers[0]['r']['mobile'], $message, 'notify');
					$user_profile = $Retailer->query("select * from user_profile
							where user_id = ".$retailers[0]['r']['user_id']."
							and device_type = 'web' and app_type='recharge_app'
							order by updated desc
							limit 1");
					$this->logData('/mnt/logs/'.$filename, "inside update_verify_flag user_profile::".json_encode($user_profile));
					if(!empty($user_profile)){
						$Retailer->query("update user_profile
								set latitude = '".$unverified_retailers[0]['ur']['latitude']."',
								longitude = '".$unverified_retailers[0]['ur']['longitude']."',
								area_id = '".$unverified_retailers[0]['ur']['area_id']."',
                                                                date = '".date('Y-m-d')."',
								updated = '".date("Y-m-d H:i:s")."'
								where user_id = ".$retailers[0]['r']['user_id']."
								and device_type = 'web' and app_type='recharge_app'");

					}
					else {
						$Retailer->query("insert into `shops`.`user_profile`
								(`id`,`user_id`, `gcm_reg_id`, `uuid`, `longitude`, `latitude`, `location_src`,`area_id`, `device_type`,
								`version` , `manufacturer`, `created`, `updated`,`date`)
								VALUES (NULL, ".$retailers[0]['r']['user_id'].", '".$retailers[0]['r']['mobile']."',
								'".$retailers[0]['r']['mobile']."', '".$unverified_retailers[0]['ur']['longitude']."',
								'".$unverified_retailers[0]['ur']['latitude']."', '','".$unverified_retailers[0]['ur']['area_id']."' ,'web' ,'' ,'' ,
								'".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."','".date('Y-m-d')."')");

					}
				}
			}
			else {
				$Retailer->query("update retailers
								set verify_flag = ".$verify_flag.",
								modified = '".date('Y-m-d H:i:s')."'
								where id = ".retailer_id);
				$this->logData('/mnt/logs/'.$filename, "inside update_verify_flag verify_flag !=1 update retailers query::"."update retailers
								set verify_flag = ".$verify_flag.",
								modified = '".date('Y-m-d H:i:s')."'
								where id = ".retailer_id);
			}
		}
		$this->logData('/mnt/logs/'.$filename, "inside return verify_flag::".$verify_flag);
		return $verify_flag;
	}
        
        
        function getUserServices(){
                $serviceDet = $this->Slaves->query("Select * from services where toShow = '1'");

                            foreach($serviceDet as $servc){
                                
                                $service_name[$servc['services']['id']] = $servc['services']['name'];
                            }
      return $service_name;  

        }
   function manglamApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);


		return $out;
	}

        /**
         * It will convert comma separated key=val string into associative ar1ray
         * @param type $strdata
         * @return type array
         */
        function covert_comma_separated_to_array($strdata){
            parse_str(str_replace(',','&',$strdata),$output);
            return $output;
        }


        /**
         *
         * @param type $data
         * @return type
         */
        function object_to_array($data) {
            if(is_array($data) || is_object($data)){
                $result = array();
                foreach($data as $key => $value){
                    $decoded_value = json_decode($value,true);
                    $value = is_array($value) ? $value : (($decoded_value && json_last_error() === JSON_ERROR_NONE) ? $decoded_value : $value);
                    $result[$key] = $this->object_to_array($value);
                }
                return $result;
            }
            return $data;
        }
        
        
        function getClientIP(){
            return $this->RequestHandler->getClientIP(false);
        }


        /*
         * Block attacker
         */
        function block_attacker($checkflag = false,$additional_param1 = array(),$successflag = false){
            return true;
            $redis = $this->Shop->redis_connect();
            $ip = $this->getClientIP();
            
            $MAXTRY = 10;
            //$current_blocked_failed_attempt_count = $redis->hget("blockipset_".$ip,$ip); //to check if ip exists in this hash
            $current_blocked_failed_attempt_count = null;

            $whitelistedIP = $this->findVar('whitelistedIP');
            $whitelistedIP_Arr = explode(",",$whitelistedIP);

            $return_msg = array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));

            if(in_array($ip,$whitelistedIP_Arr)){ return true; }
            $additional_param1['mobile'] = isset($additional_param1['mobile'])?$additional_param1['mobile']:'';
            $user = $additional_param1['mobile'];

            if( $checkflag === false ){
                return true;
            }
            if(!isset($additional_param1['uuid_data_of_user']) || empty($additional_param1['uuid_data_of_user'])){
                return true;
            }
            if($successflag === true){
                $redis->del("unblockipset_".$user."_".$ip);
                return;
            }

            if(!empty($current_blocked_failed_attempt_count)){
                if( $checkflag === true ){

                    $current_blocked_failed_attempt_count += 1;
                    //$current_blocked_failed_expiry = $redis->ttl("blockipset_".$ip);
                    $redis->hset("blockipset_".$ip,$ip,$current_blocked_failed_attempt_count);

                    $redis->hset("blockuseripset_".$additional_param1['mobile']."_".$ip,$additional_param1['mobile']."_".$ip,'1');
                    $redis->expire("blockuseripset_".$additional_param1['mobile']."_".$ip,30*60);
                    //$redis->expire("blockipset_".$ip,($current_blocked_failed_expiry + 12*60*60));

                }
                return true;
                //return array('status' => 'failure','code'=>'909','description' => "Your password attempt limit has been exceeded. Your IP is $ip. Please give a missed call from your Registered number to 022-67242256/022-42932256 to activate your account");
            }else{
                if(!($checkflag === true)){ return $this->validate_user_by_profile($additional_param1); }

                $current_unblocked_failed_attempt_count = $redis->hget("unblockipset_".$user."_".$ip,$ip); //to check if ip exists in this hash
                $current_unblocked_failed_expiry = $redis->ttl("unblockipset_".$user."_".$ip);
                $current_unblocked_failed_expiry = ($current_unblocked_failed_expiry < 0) ? 0 : $current_unblocked_failed_expiry;
                $new_current_unblocked_failed_attempt_count = $current_unblocked_failed_attempt_count + 1;
                $pending_attempt = $MAXTRY - $new_current_unblocked_failed_attempt_count;

                if($current_unblocked_failed_attempt_count < 1){
                    $redis->hset("unblockipset_".$user."_".$ip,$ip,$new_current_unblocked_failed_attempt_count);
                    $redis->expire("unblockipset_".$user."_".$ip,($current_unblocked_failed_expiry + 60*60));
                    //$return_msg['description'] = "Login failed. You have $pending_attempt attempts left";
                }
                elseif($current_unblocked_failed_attempt_count < 3){
                    $redis->hset("unblockipset_".$user."_".$ip,$ip,$new_current_unblocked_failed_attempt_count);
                    $redis->expire("unblockipset_".$user."_".$ip,($current_unblocked_failed_expiry));
                    //$return_msg['description'] = "Login failed. You have $pending_attempt attempts left";
                }elseif($current_unblocked_failed_attempt_count < $MAXTRY){
                    $redis->hset("unblockipset_".$user."_".$ip,$ip,$new_current_unblocked_failed_attempt_count);
                    $redis->expire("unblockipset_".$user."_".$ip,($current_unblocked_failed_expiry + 60*60));
                    //$return_msg['description'] = "Your password attempt limit is 5. Only ".($pending_attempt + 1)." attempts left. Please enter carefully.";
                }else{
                    //$redis->hset("blockipset_".$ip,$ip,$new_current_unblocked_failed_attempt_count);
                    //$redis->hset("blockuseripset_".$additional_param1['mobile']."_".$ip,$additional_param1['mobile']."_".$ip,'1');
                    //$redis->expire("blockuseripset_".$additional_param1['mobile']."_".$ip,30*60);
                    //$redis->expire("blockipset_".$ip,($current_unblocked_failed_expiry + 6*60*60));
                    $redis->del("unblockipset_".$user."_".$ip);
                    $sub = "MULTIPLE ATTEMPT TO LOGIN WITH WRONG PASSWORD";
                    $msg = "USER tried login for more than 5 time user : ".$additional_param1['mobile']." from IP : $ip";
                    $this->sendMails($sub,$msg,array('nandan.rana@pay1.in','ashish@pay1.in','chirutha@pay1.in','customer.care@pay1.in'),'mail');
                    //$this->sendMails($sub,$msg,array('nandan.rana@pay1.in','ashish@pay1.in','ketan.parmar@pay1.in),'mail');
                    //return array('status' => 'failure','code'=>'909','description' => "Your password attempt limit has been exceeded. Your IP is $ip. Please give a missed call from your Registered number to 022-67242256/022-42932256 to activate your account");
                }
                return true;
                //return (!($checkflag === true)) ? $this->validate_user_by_profile($additional_param1) : $return_msg;
            }
        }
        
        function enterUserLocation($user_id,$group_id,$device_type,$latitude,$longitude){
            $user = ClassRegistry::init('User');
            $data = $user->query("SELECT * FROM user_device_location WHERE user_id='$user_id' AND group_id='$group_id' AND device_type='$device_type' AND date='".date('Y-m-d')."'");
            
            $found = 0;
            if(!empty($data)){
                foreach ($data as $dt){
                    $lat_long_distance = $this->lat_long_distance($dt['user_device_location']['latitude'],$dt['user_device_location']['longitude'],$latitude,$longitude,0);
                    if($lat_long_distance*1000 <= 50){
                        $found = 1;
                        $user->query("UPDATE user_device_location SET counter=counter + 1 WHERE id = ".$dt['user_device_location']['id']);
                        break;
                    }
                }
            }
            
            if($found == 0){
                $area_id = $this->getAreaIDByLatLong($longitude,$latitude); 
                $user->query("INSERT INTO user_device_location (user_id,group_id,device_type,latitude,longitude,area_id,counter,date) VALUES ('$user_id','$group_id','$device_type','$latitude','$longitude','$area_id','1','".date('Y-m-d')."')");
            }
            
            return true;
        }

        function validate_user_by_profile($param = array()){
            return true;
            if(empty($param)) return true;
            //check for missing parameter uuid and device_type
            if(!in_array('uuid',$param) || !in_array('device_type',$param)){
                //404 access denied
                return array('status' => 'failure','code'=>'411','description' =>$this->Shop->errors(404));
            }
            //check for blank device_type and uuid
            if(empty($params['device_type']) || empty($params['uuid'])){
                return array('status' => 'failure','code'=>'412','description' =>$this->Shop->errors(404));
            }
            //check device type
            $deviceType = strtolower(trim($params['device_type']));
            if(!in_array($deviceType,array('web','andriod','windows7','windows8','java'))){
                return array('status' => 'failure','code'=>'413','description' =>$this->Shop->errors(404));
            }
            //check for valid uuid
            if(trim($params['mobile']) == trim($params['uuid'])){
                return array('status' => 'failure','code'=>'414','description' =>$this->Shop->errors(404));
            }
        }

		function bulkApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET');


		return $out;
	}

        function bimcoApi($url,$params,$func=null,$data=null){


		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);

		return $out;

	}

        function rajanApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);


		return $out;
	}

         function payRechargeApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);


		return $out;
	}

        function ShivaIdea($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,true,true);


		return $out;
	}

        function IndicoreRechargeApi($url,$params){
		foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url,$post_string,'POST',30,10,true,true,true);
		return $out;
	}

    /*
     * API Integration for swamiraj by swapnilT on 16NOV2016
     */
        function swamirajApi($url,$params){
            foreach ($params as $key => &$val) {
                    $post_params[] = $key.'='.urlencode($val);
            }
            $post_string = implode('&', $post_params);
            $out = $this->curl_post($url,$post_string,'POST',30,10,true,true,true);

            return $out;

        }
    /*
     * API Integration for maxrecharge by swapnilT on 25 JAN 2016
     */
        function maxrechargeApi($url,$params){

            $soapUrl = "http://www.maxxrecharge.com/mRechargeAPI/Service.asmx?op=$url";
            $soapUser = $params['username']; // username
            $soapPassword = $params['pwd']; // password
            $xmlUID = $params['username'];
            $xmlPWD = $params['xmlpwd'];
            $transId = (isset($params['clientid']) || array_key_exists('clientid', $params)) ? $params['clientid'] : 0;
            if($params['type']=='recharge'){
                $operator = $params['operator'];
                $number = $params['number'];
                $amt = $params['amt'];
                $data = '<MRREQ><REQTYPE>MRCH</REQTYPE><UID>'.$xmlUID.'</UID><PWD>'.$xmlPWD.'</PWD><OPCODE>'.$operator.'</OPCODE><CMOBNO>'.$number.'</CMOBNO><AMT>'.$amt.'</AMT><STV>0</STV><TRNREFNO>'.$transId.'</TRNREFNO></MRREQ>';
            }
            else if($params['type']=='balance'){
                $data = '<MRREQ><REQTYPE>BAL</REQTYPE><UID>'.$xmlUID.'</UID><PWD>'.$xmlPWD.'</PWD></MRREQ>';
            }
            else if($params['type']=='status'){
                $data = '<MRREQ><REQTYPE>TRNST</REQTYPE><UID>'.$xmlUID.'</UID><PWD>'.$xmlPWD.'</PWD><TRNNO>'.$transId.'</TRNNO><CNO></CNO></MRREQ>';
            }
            $this->logData($_SERVER['DOCUMENT_ROOT']."/logs/maxrecharge.txt",date('Y-m-d H:i:s').":maxrechargeApi: ".$data);
            $raw_data = htmlentities($data);

            $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Header>
            <APIAuthHeader xmlns="http://tempuri.org/">
            <UserID>'.$soapUser.'</UserID>
            <Password>'.$soapPassword.'</Password>
            </APIAuthHeader>
            </soap:Header>
            <soap:Body>
            <'.$url.' xmlns="http://tempuri.org/">
            <sRequest>
            '.$raw_data.'
            </sRequest>
            </'.$url.'>
            </soap:Body>
            </soap:Envelope>';

            $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://tempuri.org/$url",
            "Content-length: ".strlen($xml_post_string),
            "UserID: ".$soapUser,
            "Password: ".$soapPassword,
            "Host: maxxrecharge.com",
            "POST: /$url/Service.asmx HTTP/1.1",
            );
            $this->logData($_SERVER['DOCUMENT_ROOT']."/logs/maxrecharge.txt",date('Y-m-d H:i:s').":maxrechargeApi xml : ".$xml_post_string);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $soapUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            $response = ($response);//exit;
            //curl_close($ch);
            $info = curl_getinfo($ch);

            if($info['connect_time'] > 10 || $info['total_time'] > 10){
                    $this->logData('/var/www/html/shops/curl_log.txt',"[".date('Y-m-d H:i:s')."] Took ".$info['total_time']." seconds to send request of $post_string to $url & took ".$info['connect_time']." seconds to connect");
            }
            $out = '';
            if(!curl_errno($ch)){
                    curl_close($ch);

                    $out  = $this->xml2array($response);
                    $this->logData($_SERVER['DOCUMENT_ROOT']."/logs/maxrecharge.txt",date('Y-m-d H:i:s').":maxrechargeApi response : ".$out);
                    if(is_array($out) && array_key_exists('soap:Envelope', $out)){
                        $response = trim(($out['soap:Envelope']['soap:Body'][$url.'Response'][$url.'Result']));
                        $response = $this->xml2array($response);
                    }
                    $out = $response['MRRESP'];
                    return array('output'=>$out,'success'=>true,'timeout'=>false);
            }
            else {
                    $errno = curl_errno($ch);
                    curl_close($ch);
                    $connect_timeout = 10;
                    if(in_array($errno,array(6,7)) || $info['connect_time'] > $connect_timeout){
                            $this->logData('/var/www/html/shops/curl_log_error.txt',"[".date('Y-m-d H:i:s')."] Curl Error $errno: Took ".$info['total_time']." seconds to send request of $post_string to $url & took ".$info['connect_time']." seconds to connect, default connection timeout is $connect_timeout, success: false, timeout: true");

                        return array('output'=>$out,'success'=>false,'timeout'=>true);//connection timeout
                    }
                    else {
                            $this->logData('/var/www/html/shops/curl_log_error.txt',"[".date('Y-m-d H:i:s')."] Curl Error $errno: Took ".$info['total_time']." seconds to send request of $post_string to $url & took ".$info['connect_time']." seconds to connect, default connection timeout is $connect_timeout, success: false, timeout: false");

                            return array('output'=>$out,'success'=>false,'timeout'=>false);//timeout
                    }
            }



        }

        function speedrecApi($url,$params){
            $soapUrl = "http://www.speedcharge.in/webservice/srservice.asmx?op=$url";
            $soapUser = $params['UserName']; // username
            $soapPassword = $params['Password']; // password
            $transId = (isset($params['APIAccountRef']) || array_key_exists('APIAccountRef', $params)) ? $params['APIAccountRef'] : 0;
            if($params['type']=='recharge'){
                $data = '<ApiCredentials>
                        <UserName>'.$params['UserName'].'</UserName>
                        <Password>'.$params['Password'].'</Password>
                        </ApiCredentials>
                        <MobileToRecharge>'.$params['MobileToRecharge'].'</MobileToRecharge>
                        <APIAccountRef>'.$params['APIAccountRef'].'</APIAccountRef>
                        <Amount>'.$params['Amount'].'</Amount>
                        <RechargeVia>'.$params['RechargeVia'].'</RechargeVia>
                        <TypeOfRecharge>'.$params['TypeOfRecharge'].'</TypeOfRecharge>
                        <OperatorCode>'.$params['OperatorCode'].'</OperatorCode>';
            }
            else if($params['type']=='balance'){
                $data = '<ApiCredentials>
                        <UserName>'.$params['UserName'].'</UserName>
                        <Password>'.$params['Password'].'</Password>
                        </ApiCredentials>';
            }
            else if($params['type']=='status'){
                $data = '<ApiCredentials>
                        <UserName>'.$params['UserName'].'</UserName>
                        <Password>'.$params['Password'].'</Password>
                        </ApiCredentials>
                        <APIAccountRef>'.$params['APIAccountRef'].'</APIAccountRef>';
            }
            $this->logData($_SERVER['DOCUMENT_ROOT']."/logs/speedrec.txt",date('Y-m-d H:i:s').":speedrecApi: ".$data);
            $raw_data = htmlentities($data);

            $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Header>
            <Information xmlns="http://www.speedcharge.in/">
            '.$data.'
            </Information>
            </soap:Header>
            <soap:Body>
            <'.$url.' xmlns="http://www.speedcharge.in/"/>
            </soap:Body>
            </soap:Envelope>';

            $headers = array(
            "POST: /WebService/SpeedRechargeAPI.asmx HTTP/1.1",
            "Host: www.speedcharge.in",
            "Content-type: text/xml;charset=\"utf-8\"",
//            "Accept: text/xml",
//            "Cache-Control: no-cache",
//            "Pragma: no-cache",
            "Content-length: ".strlen($xml_post_string),
            "SOAPAction: http://www.speedcharge.in/$url",
            );
            $this->logData($_SERVER['DOCUMENT_ROOT']."/logs/speedrec.txt",date('Y-m-d H:i:s').":speedrecApi xml : ".$xml_post_string);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $soapUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            $response = ($response);//exit;
            //curl_close($ch);
            $info = curl_getinfo($ch);

            if($info['connect_time'] > 10 || $info['total_time'] > 10){
                    $this->logData('curl_log.txt',"[".date('Y-m-d H:i:s')."] Took ".$info['total_time']." seconds to send request of $xml_post_string to $soapUrl & took ".$info['connect_time']." seconds to connect");
            }
            $out = '';
            if(!curl_errno($ch)){
                    curl_close($ch);

                    $out  = $this->xml2array($response);
                    $this->logData($_SERVER['DOCUMENT_ROOT']."/logs/speedrec.txt",date('Y-m-d H:i:s').":speedrecApi response : ".json_encode($out));
                    if(is_array($out) && array_key_exists('soap:Envelope', $out)){
                        $response = trim(($out['soap:Envelope']['soap:Body'][$url.'Response'][$url.'Result']));
                        //$response = $this->xml2array($response);
                    }
                    $out = $response;
                    return array('output'=>$out,'success'=>true,'timeout'=>false);
            }
            else {
                    $errno = curl_errno($ch);
                    curl_close($ch);
                    $connect_timeout = 10;

                    if(in_array($errno,array(6,7)) || $info['connect_time'] > $connect_timeout){
                            $this->logData('curl_log_error.txt',"[".date('Y-m-d H:i:s')."] Curl Error $errno: Took ".$info['total_time']." seconds to send request of $xml_post_string to $soapUrl & took ".$info['connect_time']." seconds to connect, default connection timeout is $connect_timeout, success: false, timeout: true");

                        return array('output'=>$out,'success'=>false,'timeout'=>true);//connection timeout
                    }
                    else {
                            $this->logData('curl_log_error.txt',"[".date('Y-m-d H:i:s')."] Curl Error $errno: Took ".$info['total_time']." seconds to send request of $xml_post_string to $soapUrl & took ".$info['connect_time']." seconds to connect, default connection timeout is $connect_timeout, success: false, timeout: false");

                            return array('output'=>$out,'success'=>false,'timeout'=>false);//timeout
                    }
            }
        }

        function getJioPlan(){
            $slaveObj = ClassRegistry::init('Slaves');
            $plan = $this->Shop->getMemcache("jio_plan");
            if(empty($plan)){
               $result = $slaveObj->query("SELECT offId,plan,plan_desc,plan_amt FROM jio_plan");
               $data = array();
               foreach($result as $row){
                  $data[$row['jio_plan']['plan_amt']] =$row['jio_plan'];
               }
               $data = json_encode($data);
               $time = 7*24*60*60;
               $this->Shop->setMemcache("jio_plan",$data,$time);
               $plan = $data;
            }
            $plan = json_decode($plan,true);
            return $plan;

        }

        function IndiaRechargeApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.urlencode($val);
            }
            $post_string = implode('&', $post_params);

            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);


            return $out;
        }

        function curl_header($url,$post_data,$header=null,$type='POST',$xml = 0,$httpHeader=0,$timeout=30,$connect_timeout=10,$follow_loc=true,$user_agent=true){
            $ch = curl_init($url);

            if($type=='GET')
                curl_setopt($ch, CURLOPT_POST,0);
                else{
                    curl_setopt($ch, CURLOPT_POST,1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                }

                curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
                curl_setopt($ch, CURLOPT_HEADER, $httpHeader);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

                $out = trim(curl_exec($ch));
                $info = curl_getinfo($ch);

                if($info['connect_time'] > 10 || $info['total_time'] > 10){
                    $this->logData('/var/www/html/shops/curl_log.txt',"[".date('Y-m-d H:i:s')."] Took ".$info['total_time']." seconds to send request of $post_data to $url & took ".$info['connect_time']." seconds to connect");
                }
                if(!curl_errno($ch)){
                    curl_close($ch);
                }
                else {
                    $errno = curl_errno($ch);
                    curl_close($ch);
                    if(in_array($errno,array(6,7)) || $info['connect_time'] > $connect_timeout){
                        $this->logData('/var/www/html/shops/curl_log_error.txt',"[".date('Y-m-d H:i:s')."] Curl Error $errno: Took ".$info['total_time']." seconds to send request of $post_data to $url & took ".$info['connect_time']." seconds to connect, default connection timeout is $connect_timeout, success: false, timeout: true");
                    }
                    else {
                        $this->logData('/var/www/html/shops/curl_log_error.txt',"[".date('Y-m-d H:i:s')."] Curl Error $errno: Took ".$info['total_time']." seconds to send request of $post_data to $url & took ".$info['connect_time']." seconds to connect, default connection timeout is $connect_timeout, success: false, timeout: false");

                    }
                }
                if($xml)
                    $out=  json_encode($this->xml2array($out));
                    return $out;
        }

        function ambikaApi($url,$params){
            foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);


		return $out;
        }

        function a1recApi($url,$params){
            foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);


		return $out;
        }

        function bigshoprecApi($url,$params){
            $url = 'http://bigshoprecharge.com/api/rechargeapi.asmx/'.$url;
            foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

		return $out;
        }

        function speedpayApi($url,$params){
            foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

		return $out;
        }

        function unrecApi($url,$params){
            foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);


		return $out;
        }

        function indiaoneApi($url,$params){
            foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

		return $out;
        }

        function emoneyApi($url,$params){
            foreach ($params as $key => &$val) {
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

		return $out;
        }

        function thinkwalApi($url,$params){
            foreach($params as $key => &$val) {
                $post_params[] = $key.'='.$val;
            }

            $post_string = implode('&', $post_params);

            $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic 9e6a55b6b4563e652a23be9d623ca5055c356940'
            );

            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true,false,null,$headers);

            return $out;
        }

        function champrecApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.$val;
            }
            $post_string = implode('&', $post_params);

            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

            return $out;
        }

         function ka2zrecApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.urlencode($val);
            }
            $post_string = implode('&', $post_params);
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
            return $out;
        }

        function yashicaenttokenGenerator($params,$secret_key){
            $token = hash_hmac('SHA512', json_encode(array_map("strval",$params)),
                       $secret_key);
            return $token;
        }

        function  yashicaentApi($url,$params){
            foreach ($params as $key => &$val){
                $post_params[] = $key .'='. $val;
            }
            $post_string = implode('&',$post_params);            
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
            return $out;
        }


        function ashw1tokenGenerator($params,$secret_key){
            $token = hash_hmac('SHA512', json_encode(array_map("strval",$params)),
                       $secret_key);
            return $token;
        }

        function  ashw1Api($url,$params){
            foreach ($params as $key => &$val){
                $post_params[] = $key .'='. $val;
            }
            $post_string = implode('&',$post_params);
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

            return $out;
        }

        function ctswalletApi($url,$params){
            foreach ($params as $key => &$val) {
                    $post_params[] = $key.'='.urlencode($val);
            }

            $post_string = implode('&', $post_params);

            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

            return $out;
        }
//
//        function zplusApi($url,$params){
//            foreach ($params as $key => &$val){
//                $post_params[]  = $key .'='. $val;
//            }
//
//            $post_string = implode('&', $post_params);
//            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,false);
//            return $out;
//        }
//
        function roundpayApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.$val;
            }
            $post_string = implode('&', $post_params);

            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
            return $out;

        }

         function maxxrecApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.$val;
            }
            $post_string = implode('&', $post_params);

            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

            return $out;
         }

         function erecpointApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.$val;
            }
            $post_string = implode('&', $post_params);

            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

            return $out;
         }

         function urecApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.urlencode($val);
            }
            $post_string = implode('&', $post_params);

            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

            return $out;
         }

        function pay1allApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.$val;
            }
            $post_string = implode('&', $post_params);
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

            return $out;
        }

        function precApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.urlencode($val);
            }
            $post_string = implode('&', $post_params);
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

            return $out;
         }


        function pay1clickApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.urlencode($val);
            }               
            $post_string = implode('&', $post_params);                               
                     
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);     
            $out = str_replace('<?xml version="1.0" encoding="utf-16"?>','', $out);
            
            return $out;
        }
         
        function kracrecApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.urlencode($val);
            }
            $post_string = implode('&', $post_params);
            
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
           
            return $out;
        }
        
        function stelcomApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.$val;
            }
            $post_string = implode('&', $post_params);
            
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
            
            return $out;
        }
        
               
         function manimasterApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.urlencode($val);
            }
            $post_string = implode('&', $post_params);
            
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
            
            return $out;
         }
                 
        function wellbornApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.$val;
            }
            $post_string = implode('&', $post_params);
            
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
            
            return $out;
        }
        
        function nishitokenGenerator($params,$secret_key){
            $token = hash_hmac('SHA512', json_encode(array_map("strval",$params)),
                       $secret_key);
            return $token;
        }

        function  nishiApi($url,$params){
            foreach ($params as $key => &$val){
                $post_params[] = $key .'='. $val;
            }
            $post_string = implode('&',$post_params);            
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
            return $out;
        }        
        function supersaastokenGenerator($params,$secret_key){
            $token = hash_hmac('SHA512', json_encode(array_map("strval",$params)),
                       $secret_key);
            return $token;
        }

        function  supersaasApi($url,$params){
            foreach ($params as $key => &$val){
                $post_params[] = $key .'='. $val;
            }
            $post_string = implode('&',$post_params);                        
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
            return $out;
        }
        
	function myetopupApi($url,$params){
            foreach ($params as $key => &$val) {
                $post_params[] = $key.'='.$val;
            }
            $post_string = implode('&', $post_params);            
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);            
            return $out;
         }
        function balajisaastokenGenerator($params,$secret_key){
            $token = hash_hmac('SHA512', json_encode(array_map("strval",$params)),
                       $secret_key);
            return $token;
        }

        function  balajisaasApi($url,$params){
            foreach ($params as $key => &$val){
                $post_params[] = $key .'='. $val;
            }
            $post_string = implode('&',$post_params);                                                  
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);
            return $out;
        }  
        function pratisaastokenGenerator($params,$secret_key){
            $token = hash_hmac('SHA512', json_encode(array_map("strval",$params)),
                       $secret_key);
            return $token;
        }

        function  pratisaasApi($url,$params){
            foreach ($params as $key => &$val){
                $post_params[] = $key .'='. $val;
            }
            $post_string = implode('&',$post_params);    
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);

            return $out;
        }

        function osssaastokenGenerator($params,$secret_key){
            $token = hash_hmac('SHA512', json_encode(array_map("strval",$params)),
                       $secret_key);
            return $token;
        }

        function  osssaasApi($url,$params){
            foreach ($params as $key => &$val){
                $post_params[] = $key .'='. $val;
            }
            $post_string = implode('&',$post_params);                
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);            
            return $out;
        }        
        
    function manglamvodApi($url, $params) {
        foreach ($params as $key => &$val) {
            $post_params[] = $key . '=' . urlencode($val);
        }
        $post_string = implode('&', $post_params);
        $out = $this->curl_post($url . "?" . $post_string, null, 'GET', 30, 10, true, false,true);
        return $out;
    }       

        
        function swamirapiApi($url,$params){
            
            $soapUrl = "http://www.payself.in/WebService/SRService.asmx?op=$url";
            $soapUser = $params['UserName']; // username
            $soapPassword = $params['Password']; // password            
            $transId = r(isset($params['APIAccountRef']) || array_key_exists('APIAccountRef', $params)) ? $params['APIAccountRef'] : 0;
            if($params['type']=='recharge'){                
                $data = '<ApiCredentials>
                        <UserName>'.$params['UserName'].'</UserName>
                        <Password>'.$params['Password'].'</Password>
                        </ApiCredentials>
                        <MobileToRecharge>'.$params['MobileToRecharge'].'</MobileToRecharge>
                        <APIAccountRef>'.$params['APIAccountRef'].'</APIAccountRef>
                        <Amount>'.$params['Amount'].'</Amount>
                        <RechargeVia>'.$params['RechargeVia'].'</RechargeVia>
                        <TypeOfRecharge>'.$params['TypeOfRecharge'].'</TypeOfRecharge>
                        <OperatorCode>'.$params['OperatorCode'].'</OperatorCode>';
            }
            else if($params['type']=='balance'){                
                $data = '<ApiCredentials>
                        <UserName>'.$params['UserName'].'</UserName>
                        <Password>'.$params['Password'].'</Password>
                        </ApiCredentials>';
            }
            else if($params['type']=='status'){                
                $data = '<ApiCredentials>
                        <UserName>'.$params['UserName'].'</UserName>
                        <Password>'.$params['Password'].'</Password>
                        </ApiCredentials>
                        <APIAccountRef>'.$params['APIAccountRef'].'</APIAccountRef>';
            }
            $this->logData($_SERVER['DOCUMENT_ROOT']."/logs/swamirajapi.txt",date('Y-m-d H:i:s').":swamirajApi: ".$data);
            $raw_data = htmlentities($data);


            $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Header>
            <Information xmlns="http://payself.in/">
            '.$data.'
            </Information>
            </soap:Header>
            <soap:Body>
            <'.$url.' xmlns="http://payself.in/"/>
            </soap:Body>
            </soap:Envelope>';
 

            $headers = array(
            "POST: /WebService/SRService.asmx HTTP/1.1",
            "Host: payself.in",
            "Content-type: text/xml;charset=\"utf-8\"",
//            "Accept: text/xml",
//            "Cache-Control: no-cache",
//            "Pragma: no-cache",
            "Content-length: ".strlen($xml_post_string),
            "SOAPAction: http://payself.in/$url",
            );
            $this->logData($_SERVER['DOCUMENT_ROOT']."/logs/swamirajapi.txt",date('Y-m-d H:i:s').":swamirajApi xml : ".$xml_post_string);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $soapUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);            
            $response = ($response);//exit;xm
            //curl_close($ch);
            $info = curl_getinfo($ch);

            if($info['connect_time'] > 10 || $info['total_time'] > 10){
                    $this->logData('curl_log.txt',"[".date('Y-m-d H:i:s')."] Took ".$info['total_time']." seconds to send request of $xml_post_string to $soapUrl & took ".$info['connect_time']." seconds to connect");
            }
            $out = '';
            if(!curl_errno($ch)){
                    curl_close($ch);

                    $out  = $this->xml2array($response);
                    $this->logData($_SERVER['DOCUMENT_ROOT']."/logs/swamirajapi.txt",date('Y-m-d H:i:s').":swamirajApi response : ".json_encode($out));
                    if(is_array($out) && array_key_exists('soap:Envelope', $out)){
                        $response = trim(($out['soap:Envelope']['soap:Body'][$url.'Response'][$url.'Result']));
                        //$response = $this->xml2array($response);
                    }
                    $out = $response;
                    return array('output'=>$out,'success'=>true,'timeout'=>false);
            }
            else {
                    $errno = curl_errno($ch);
                    curl_close($ch);
                    $connect_timeout = 10;

                    if(in_array($errno,array(6,7)) || $info['connect_time'] > $connect_timeout){
                            $this->logData('curl_log_error.txt',"[".date('Y-m-d H:i:s')."] Curl Error $errno: Took ".$info['total_time']." seconds to send request of $xml_post_string to $soapUrl & took ".$info['connect_time']." seconds to connect, default connection timeout is $connect_timeout, success: false, timeout: true");

                        return array('output'=>$out,'success'=>false,'timeout'=>true);//connection timeout
                    }
                    else {
                            $this->logData('curl_log_error.txt',"[".date('Y-m-d H:i:s')."] Curl Error $errno: Took ".$info['total_time']." seconds to send request of $xml_post_string to $soapUrl & took ".$info['connect_time']." seconds to connect, default connection timeout is $connect_timeout, success: false, timeout: false");

                            return array('output'=>$out,'success'=>false,'timeout'=>false);//timeout
                    }
            }
        }
        
        function saastokenGenerator($params,$secret_key){


            $token = hash_hmac('SHA512', json_encode(array_map("strval",$params)),

                       $secret_key);
            return $token;
		}       

	function tokenGenerator($params,$secret_key){
            $token = hash_hmac('SHA512', json_encode(array_map("strval",$params)),
                       $secret_key);
            return $token;

        }


        function  saasApi($url,$params){

            foreach ($params as $key => &$val){
                $post_params[] = $key .'='. $val;
            }
            $post_string = implode('&',$post_params);               
            $out = $this->curl_post($url."?".$post_string,null,'GET',30,10,true,false,true);            
            return $out;

        }

    function techmateApi($url, $params) {
        foreach ($params as $key => &$val) {
            $post_params[] = $key . '=' . urlencode($val);
        }
        $post_string = implode('&', $post_params);  
        $out = $this->curl_post($url . "?" . $post_string, null, 'GET', 30, 10, true, false,true);
        return $out;
    }      

        
    function  panService($url,$params){
            foreach ($params as $key => &$val){
                $post_params[] = $key .'='. $val;
            }
            $post_string = implode('&',$post_params);                
            $out = $this->curl_post($url."?".$post_string,null,'POST',30,10,true,false,false);
            return $out;
        }        


    function IND_money_format($money){
    	if (strpos($money, '-') !== false) {
    		$has_minus = 1;
    	}
        $len = strlen($money);
        if($has_minus){
        	$len--;
        }
        $m = '';
        $money = strrev($money);
        for($i=0;$i<$len;$i++){
            if(( $i==3 || ($i>3 && ($i-1)%2==0) ) && $i!=$len){
                $m .=',';
            }
            $m .=$money[$i];
        }
        return ($has_minus)?"-".strrev($m):strrev($m);
    }    
        

    function  dmtNotification($url,$params){
            foreach ($params as $key => &$val){
                $post_params[] = $key .'='. $val;
            }
            $post_string = implode('&',$post_params);                            
            $out = $this->curl_post($url."?".$post_string,null,'POST',30,10,true,false,false);           
            return $out;
    }    

                      

    function RoboApi($url, $params) {
        foreach ($params as $key => &$val) {
            $post_params[] = $key . '=' . urlencode($val);
        }
        $post_string = implode('&', $post_params);           
        $out = $this->curl_post($url . "?" . $post_string, null, 'GET', 30, 10, true, false,true);        
        return $out;
    }      
    
    function payclickApi($url, $params) {
        foreach ($params as $key => &$val) {
            $post_params[] = $key . '=' . urlencode($val);
        }
        $post_string = implode('&', $post_params);
        $out = $this->curl_post($url . "?" . $post_string, null, 'GET', 30, 10, true, false,true);                    
        return $out;
    }

}



?>
