<?php
class ApisController extends AppController {

	var $name = 'Apis';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
	var $components = array('RequestHandler','Shop','Api','Invoice');
	var $uses = array('User','Retailer','AppReqLog','Slaves');
	var $validFormats = array('xml','json');
	var $appVersion = 1;
	var $validRecTypes = array('flexi','voucher');
	var $wrongSMS = "Please send correct code. Correct code is
Mobile:
*opr*mob*amt

DTH:
*opr*subid*mob*amt";

	function beforeFilter() {parent::beforeFilter();$this->Auth->allow('*');}
	function checkForAccess($method){
		$ret = true;
		$auth_dist = array('lastten','createRetailer','getRetailerList','getBalance','amountTransfer','sessionCheck','writetous','ledgerBalance','saleReport','updatePin','getTopupRequest','topupRequest','updateMobile');
		$auth_ret = array('lastten','topups','earnings','reversalTransactions','mobileTransactions','mobRecharge','dthRecharge',
				'getBalance','ledgerBalance','sessionCheck','writetous','lastTransactions','saleReport','updatePin','getTopupRequest',
		        'topupRequest','updateMobile','reversal','vasRecharge','getDistToRetlBalTransfer','mobBillPayment','utilityBillPayment','utilityBillFetch','bbpsTxnDetails','bbpsComplaints','bbpsComplainRegistration','bbpsComplainTracking',
				'updateRetailerAddress','pay1Wallet','pg','cashpgPayment','cashpgTxnList','clickToCall', 'authenticateMobileNumberChange',
		        'changeMobileNumber', 'kitActivationRequest', 'bankAccounts', 'walletTopup', 'banksAndTransferTypes', 'sendBalanceTopupRequest','updatePAN','getInvoiceList','getInvoiceData','getUserProfile','updateNewsletterFlag'
		);//,'mobBillPayment','getPlanDetails'
		if(in_array($method,$auth_dist) || in_array($method,$auth_ret)){
			if(!empty($_SESSION['Auth']) && !empty($_SESSION['Auth']['User']) && !empty($_SESSION['Auth']['User']['group_id'])){
				$ret = 404;

				$group_id = $_SESSION['Auth']['User']['group_id'];
				if($group_id == RETAILER && in_array($method,$auth_ret)){
					$ret = true;
				}else if($group_id == DISTRIBUTOR && in_array($method,$auth_dist)){
					$ret = true;
				}
				else {
					$ret = 403;
				}
			}else{
				$ret = 403;
			}
		}

		return $ret;
	}

	function checkForApiAccess($params,$partner){
		$transaction_id = empty ($params['trans_id'])? "" : trim(urldecode($params['trans_id']));//partner transaction id
		$hash_code = empty ($params['hash_code']) ? "" : trim(urldecode($params['hash_code']));//partner hash code

		//$logger = $this->General->dumpLog('ReceiveAPI Request', 'receiveAPI');

		$result = array();
		if(empty($transaction_id) || empty($hash_code) ){
			$result = array('access'=>false ,'code'=>'E003'); // insufficient input
		}else{
			if(!empty ($partner) ){// check for partner account
				//$logger->info("apiAccessHashCheck Parameters::".json_encode($params) . "::" . json_encode($partner));

				if($this->Shop->apiAccessHashCheck($params , $partner)){// check for a valid hash
					$ipAccess = $this->Shop->apiAccessIPCheck($partner);
					if( $ipAccess['access'] == false){
						$result = array('access'=>false ,'code'=>'E006');
					}else{
						if($params['operation_type'] == 1){// partner-operator-access check only for recharge api
							$operatorAccess = $this->Shop->apiAccessPartnerOperatorCheck($partner['Partner']['acc_id'],$params['operator']);
							if(!$operatorAccess['access']){// check for partner - operator access
								$result = array('access'=>false,'code'=>'E016');
							}else{
								$result = array('access'=>true);
							}
						}else{
							$result = array('access'=>true);
						}
					}
				}else{
					$result = array('access'=>false ,'code'=>'E005');// 1 - invalid hash
				}
			}else{
				$result = array('access'=>false ,'code'=>'E004');// 4 - invalid partnerId
			}
		}
		return $result;
	}


	// sms based system

        /* function getMobileDetails($mobileNo){
            $this->autoRender = false;
            $response = array();
            if( empty($mobileNo) || strlen( $mobileNo ) < 4){
                $response = array(
                    "status"=>"failure",
                    "error"=>"Wrong mobile no ."
                );
            }else{
                if(strlen($mobileNo) <  10){
                    $mobileNo = str_pad($mobileNo, 10, "1");
                }

                $oprData = $this->General->getMobileDetails($mobileNo);
                $response = array(
                    "status"=>"success",
                    "details"=>$oprData
                );
            }
            return json_encode($response);
        }
     */

	function lastRecharge($caller){
		if(strlen($caller) >= 10)
		$caller = substr($caller, -10);

//		$ret = $this->Slaves->query("SELECT retailers.id,users.balance,sum(retailers_logs.sale) as tot FROM retailers left join `retailers_logs` ON (retailer_id = retailers.id AND  month(date)='".date('m')."' AND year(date)='".date('Y')."') left join users ON (users.id = retailers.user_id) WHERE users.mobile = '$caller'");
		$ret = $this->Slaves->query("SELECT retailers.id,users.balance,SUM(rel.amount) as tot "
                        . "FROM retailers "
                        . "LEFT JOIN retailer_earning_logs rel ON (rel.ret_user_id = retailers.user_id AND MONTH(rel.date) = '".date('m')."' AND YEAR(rel.date) = '".date('Y')."') "
                        . "LEFT JOIN users u ON (users.id = retailers.user_id) "
                        . "WHERE users.mobile = '$caller' "
                        . "AND rel.service_id IN (1,2,4,5,6,7)");
		if(!isset($ret['0']['retailers']['id']))exit;

		$paramdata['SALE'] = $ret['0']['0']['tot'];
        $paramdata['RETAILER_BALANCE'] = round($ret['0']['users']['balance'],2);

		$data = $this->Slaves->query("SELECT mobile,products.name,products.service_id,param,vendors_activations.amount,vendors_activations.txn_id,vendors_activations.status FROM vendors_activations,products WHERE vendors_activations.retailer_id = ".$ret['0']['retailers']['id']." AND vendors_activations.product_id = products.id AND vendors_activations.date >= '".date('Y-m-d',strtotime('-7 days'))."' order by vendors_activations.id desc limit 1");
                if(!empty($data)){
			//$msg = "Last Txn";
			//$msg .= "\nTrans Id: " .  substr($data['0']['vendors_activations']['ref_code'],-5);
			///if($data['0']['products']['service_id'] == 2){
			//	$msg .= "\nSub Id: " .  $data['0']['vendors_activations']['param'];
			//}
			//$msg .= "\nMobile: " .  $data['0']['vendors_activations']['mobile'];
			//$msg .= "\nOperator: " .  $data['0']['products']['name'];
			//$msg .= "\nAmount: " .  $data['0']['vendors_activations']['amount'];

			$sTxt = '';
			if($data['0']['vendors_activations']['status'] == '0'){
				$sTxt = 'Success';
			}else if($data['0']['vendors_activations']['status'] == '1'){
				$sTxt = 'Success';
			}else if($data['0']['vendors_activations']['status'] == '2'){
				$sTxt = 'Reversed';
			}else if($data['0']['vendors_activations']['status'] == '3'){
				$sTxt = 'Reversed';
			}else if($data['0']['vendors_activations']['status'] == '4'){
				$sTxt = 'Complaint taken';
			}else if($data['0']['vendors_activations']['status'] == '5'){
				$sTxt = 'Success';
			}

			$lastThreeTopups = $this->Shop->lastThreeTopups($caller);
			if(!empty($lastThreeTopups)){
				$topup_report = "Topups: Rs.".$lastThreeTopups[0]['shop_transactions']['amount'];
				if(isset($lastThreeTopups[1]['shop_transactions']['amount'])){
					$topup_report .= ", Rs.".$lastThreeTopups[1]['shop_transactions']['amount'];
				}
				if(isset($lastThreeTopups[2]['shop_transactions']['amount'])){
					$topup_report .= ", Rs.".$lastThreeTopups[2]['shop_transactions']['amount'];
				}
				$paramdata['TOP_UP'] = $topup_report;
			}
//			$msg .= "\nStatus:" .$sTxt;

                        $paramdata['VENDORS_ACTIVATIONS_REF_CODE'] = substr($data['0']['vendors_activations']['txn_id'],-5);
                        $paramdata['MOBILE_NUMBER'] = $data['0']['vendors_activations']['mobile'];
                        $paramdata['OPERATOR_NAME'] = $data['0']['products']['name'];
                        $paramdata['AMOUNT'] = $data['0']['vendors_activations']['amount'];
                        $paramdata['SUCEESS_TEXT'] = $sTxt;
                        $MsgTemplate = $this->General->LoadApiBalance();
                        $content1 =  $MsgTemplate['LastRecharge_MSG'];
                        $msg = $this->General->ReplaceMultiWord($paramdata,$content1);

                }
		else {
//			$msg = "There is no last transaction found in last 7 days";
//                      $msg .= "\nSale this month: Rs ". $ret['0']['0']['tot'];
//		        $msg .= "\nYour bal: Rs.".round($ret['0']['retailers']['balance'],2);
						$MsgTemplate = $this->General->LoadApiBalance();
                        $content2 =  $MsgTemplate['LastRecharge_NoLastTrans_MSG'];
                        $msg = $this->General->ReplaceMultiWord($paramdata,$content2);
		}
//		$msg .= "\nSale this month: Rs ". $ret['0']['0']['tot'];
//		$msg .= "\nYour bal: Rs.".round($ret['0']['retailers']['balance'],2);

		$this->General->sendMessage($caller,$msg,'ussd');
		$this->autoRender = false;
	}



	function receiveICICI($option=null){
		$xml = file_get_contents('php://input');

		$fh = fopen("/mnt/logs/icici.txt","a+");
		fwrite($fh,date('Y-m-d H:i:s'). ":$xml\n");
		fclose($fh);
		echo 'success';
		$this->autoRender = false;
	}

	function startUSSD($type=null,$mobile=null,$number=null){
		if(empty($mobile))$mobile = $_REQUEST['mobile'];
		if(empty($type)) $type = 1;

		$fh = fopen("/mnt/logs/ussd.txt","a+");
		fwrite($fh,date('Y-m-d H:i:s'). ":$mobile\n");

		$this->General->startUSSD($type,$mobile,null,$number);
		$this->autoRender = false;
	}

	function receiveTataUSSD($option=null){
		$xml = file_get_contents('php://input');
		$array = $this->General->xml2array($xml);

		$fh = fopen("/mnt/logs/ussd.txt","a+");
		fwrite($fh,date('Y-m-d H:i:s'). ":$xml\n");

		if(isset($array['USSDDynMenuRequest'])){
			$req_id = trim($array['USSDDynMenuRequest']['requestId']);
			$msisdn = substr(trim($array['USSDDynMenuRequest']['msisdn']),-10);
			$timestamp = trim($array['USSDDynMenuRequest']['timeStamp']);
			$userData = trim(isset($array['USSDDynMenuRequest']['userData']) ? $array['USSDDynMenuRequest']['userData'] : "");

			if(empty($option)){
				$this->User->query("UPDATE ussd_logs SET sessionid = '$req_id' WHERE mobile='$msisdn' AND vendor = 3 AND level = 0 AND sessionid = ''");
			}
			header("Content-type: text/xml; charset=utf-8");
			$xml = $this->receiveUSSD($msisdn,$userData,$req_id);
			fwrite($fh,"$xml\n");
			echo $xml;
		}
		$this->autoRender = false;
	}

	function receiveErrorTataUSSD(){
		$xml = file_get_contents('php://input');
		$array = $this->General->xml2array($xml);

		$fh = fopen("/mnt/logs/ussd_error.txt","a+");
		fwrite($fh,date('Y-m-d H:i:s'). ":$xml\n");

		if(isset($array['USSDDynMenuError'])){
			$req_id = $array['USSDDynMenuError']['requestId'];
			$msisdn = $array['USSDDynMenuError']['msisdn'];
			$errCode = $array['USSDDynMenuError']['ErrCode'];
			$errMsg = $array['USSDDynMenuError']['errMsg'];

			$data = $this->User->query("SELECT * FROM ussd_logs WHERE mobile='$msisdn' AND vendor =3 AND date='".date('Y-m-d')."' ORDER by id DESC LIMIT 1");
			$session_id = isset($data['0']['ussd_logs']['sessionid']) ? $data['0']['ussd_logs']['sessionid'] : "";

			$type = isset($data['0']['ussd_logs']['type']) ? $data['0']['ussd_logs']['type']: "";
			$level = isset($data['0']['ussd_logs']['level']) ? $data['0']['ussd_logs']['level'] : "";
			//fwrite($fh,"INSERT INTO ussd_logs (mobile,request,sessionid,type,vendor,level,response,date,time,extra) VALUES ('$msisdn','".addslashes($errMsg)."','$session_id',$type,3,$level,'','".date('Y-m-d')."','".date('H:i:s')."','$errCode')\n");
			$this->User->query("INSERT INTO ussd_logs (mobile,request,sessionid,type,vendor,level,response,date,time,extra) VALUES ('$msisdn','".addslashes($errMsg)."','$session_id',$type,3,$level,'','".date('Y-m-d')."','".date('H:i:s')."','$errCode')");
		}
		$this->autoRender = false;
	}

	function receiveUSSD($mobile=null,$response=null,$reqid=null){
		if(empty($mobile)){
			$mobile = $_REQUEST['mobile'];
			$mobile = substr($mobile,-10);
		}
		if(empty($response) && isset($_REQUEST['response'])){
			$response = urldecode($_REQUEST['response']);
		}

		$data = $this->User->query("SELECT * FROM ussd_logs WHERE mobile='$mobile' AND date='".date('Y-m-d')."' ORDER by id DESC LIMIT 1");
		if(empty($data)){
			$this->autoRender = false;
			return;
		}
		$session_id = $data['0']['ussd_logs']['sessionid'];

		$type = $data['0']['ussd_logs']['type'];
		$level = $data['0']['ussd_logs']['level'];
		$vendor = $data['0']['ussd_logs']['vendor'];
		$extra = $data['0']['ussd_logs']['extra'];

		if(strpos(strtolower($response),"Network busy") === false && strpos(strtolower($response),"Try Again") === false && strpos(strtolower($response),"Err") === false && strpos(strtolower($response),"subscriber") === false && strpos($response,"configurable time from") === false && strpos($response,"Map Dialog") === false && strpos($response,"Timer expired for") === false && strpos($response,"Ussd Busy") === false && strpos($response,"System failure") === false){
			//$this->User->query("INSERT INTO ussd_logs (mobile,request,sessionid,type,vendor,level,response,date,time) VALUES ('$mobile','".addslashes($response)."','$session_id',$type,$vendor,".($level+1).",'".addslashes(json_encode($_REQUEST))."','".date('Y-m-d')."','".date('H:i:s')."')");
		}
		else {
			if(!empty($data))
			$this->User->query("INSERT INTO ussd_logs (mobile,request,sessionid,type,vendor,level,response,date,time) VALUES ('$mobile','".addslashes($response)."','$session_id',$type,$vendor,".($level+1).",'".addslashes($response)."','".date('Y-m-d')."','".date('H:i:s')."')");
			$this->autoRender = false;
			return;
		}


		if($vendor==1){
			header("Content-type: text/xml; charset=utf-8");
			if($level == 0 && $type == 1 && empty($response)){
				$ussdData = $this->General->getUSSDData($type,$mobile);
				$xml = '<?xml version="1.0" encoding="UTF-8"?>
<ussd>
<message>'.$this->General->getUSSDData($type,$mobile).'</message>
<navigation>
<link target="/apis/receiveUSSD/?mobile={mobile}&amp;response={response}"></link>
</navigation>
</ussd>';
				$this->User->query("INSERT INTO ussd_logs (mobile,sessionid,type,vendor,level,sent_xml,date,time) VALUES ('$mobile','$session_id',$type,$vendor,1,'".addslashes($ussdData)."','".date('Y-m-d')."','".date('H:i:s')."')");
			}
			else if($level == 1 && $type == 1 && !empty($response)){
				$reply = $this->receiveSMS($mobile,$response,null,1);

				$reply .="\n\nEnter new request";

				$xml = '<?xml version="1.0" encoding="UTF-8"?>
<ussd>
<message>'.$reply.'</message>
<navigation>
<link target="/apis/receiveUSSD/?mobile={mobile}&amp;response={response}"></link>
</navigation>
</ussd>';
				$this->User->query("INSERT INTO ussd_logs (mobile,request,sessionid,type,vendor,level,sent_xml,response,status,date,time) VALUES ('$mobile','".addslashes($response)."','$session_id',$type,$vendor,1,'".addslashes($reply)."','',200,'".date('Y-m-d')."','".date('H:i:s')."')");

			}
			else if($type == 2){
				$ussdData = $this->General->getUSSDData($type,$mobile,$extra);
				$xml = '<?xml version="1.0" encoding="UTF-8"?>
<ussd>
<message>'.$ussdData.'</message>
</ussd>';
				$this->User->query("INSERT INTO ussd_logs (mobile,sessionid,type,vendor,level,sent_xml,date,time) VALUES ('$mobile','$session_id',$type,$vendor,1,'".addslashes($ussdData)."','".date('Y-m-d')."','".date('H:i:s')."')");
			}
			else if($type == 3 && $level == 0){
				$xml = '<?xml version="1.0" encoding="UTF-8"?>
<ussd>
<message>'.$extra.'</message>
</ussd>';
				$this->User->query("INSERT INTO ussd_logs (mobile,sessionid,type,vendor,level,sent_xml,date,time) VALUES ('$mobile','$session_id',$type,$vendor,1,'".addslashes($extra)."','".date('Y-m-d')."','".date('H:i:s')."')");
			}
		}
		else if($vendor==2){
			if($level == 0 && $type == 1){
				$garbage = array();
				$reply = $this->receiveSMS($mobile,$response,null,1);

				$xml = $reply;
				$xml .="\n\nEnter new request";
				$this->User->query("INSERT INTO ussd_logs (mobile,request,sessionid,type,vendor,level,sent_xml,response,status,date,time) VALUES ('$mobile','".addslashes($response)."','$session_id',$type,$vendor,0,'".addslashes($xml)."','',200,'".date('Y-m-d')."','".date('H:i:s')."')");
			}
		}
		else if($vendor==3){

			if($type ==1){
				if($level == 0 || empty($response)){
					$ussdData = $this->General->getUSSDData($type,$mobile);
				}
				else {
					$ussdData = $this->receiveSMS($mobile,$response,null,1);
					$ussdData .="\n\nEnter new request";
				}

				$xml = '<?xml version="1.0" encoding="UTF-8"?>';
				$xml .= '<USSDDynMenuResponse>
<requestId>'.$reqid.'</requestId>
<msisdn>'.$mobile.'</msisdn>
<starCode>6699</starCode>
<dataSet>
<param>
<id>1</id>
<value>'.htmlspecialchars($ussdData).'</value>
<rspFlag>1</rspFlag>
<rspURL>http://54.235.193.96/apis/receiveTataUSSD/1</rspURL>
<appendIndex>0</appendIndex>
<default>1</default>
</param>
</dataSet>
<ErrCode>1</ErrCode>
<errURL>http://54.235.193.96/apis/receiveErrorTataUSSD/1</errURL>
<timeStamp>'.date('Y/m/d H:i:s').'</timeStamp>
</USSDDynMenuResponse>';
				$this->User->query("INSERT INTO ussd_logs (mobile,request,sessionid,type,vendor,level,sent_xml,response,status,date,time) VALUES ('$mobile','".addslashes($response)."','$session_id',$type,$vendor,1,'".addslashes($ussdData)."','',200,'".date('Y-m-d')."','".date('H:i:s')."')");
			}
			else if($type == 2 || $type == 3){
				if($type == 2){
					$ussdData = $this->General->getUSSDData($type,$mobile,$extra);
				}
				else {
					$ussdData = $extra;
				}
				$xml = '<?xml version="1.0" encoding="UTF-8"?>';
				$xml .= '<USSDDynMenuResponse>
<requestId>'.$reqid.'</requestId>
<msisdn>'.$mobile.'</msisdn>
<starCode>6699</starCode>
<dataSet>
<param>
<id>1</id>
<value>'.htmlspecialchars($ussdData).'</value>
<rspFlag>2</rspFlag>
</param>
</dataSet>
<ErrCode>1</ErrCode>
<errURL></errURL>
<timeStamp>'.date('Y/m/d H:i:s').'</timeStamp>
</USSDDynMenuResponse>';
				$this->User->query("INSERT INTO ussd_logs (mobile,sessionid,type,vendor,level,sent_xml,date,time) VALUES ('$mobile','$session_id',$type,$vendor,1,'".addslashes($ussdData)."','".date('Y-m-d')."','".date('H:i:s')."')");
			}
		}

		if(empty($mobile)){
			echo $xml;
			$this->autoRender = false;
		}
		else {
			return $xml;
		}
	}

	function receiveSMS($mobile=null,$msg=null,$power=null,$ussd=null){
	    $this->autoRender = false;
	    if(strpos($_SERVER['REQUEST_URI'], 'receiveSMS') !== false){
	        $client_ip = $this->General->getClientIP();
	        if($client_ip != SMS_SERVER_IP){
	            echo 'Access denied';
	            return;
	        }
	    }


		if(isset($_REQUEST['password'])){
			$pwd = $_REQUEST['password'];
			if($pwd != 's1tadka') return;
		}

		$msg = urldecode($msg);

		if(isset($_REQUEST['sha'])){
			$sha = strtoupper(sha1($_REQUEST['mobile'].$_REQUEST['message'].$_REQUEST['smstime'].$_REQUEST['code']."51gh2345"));

			if($_REQUEST['sha'] != $sha){
				$sub = "(SOS) Security breach";
				$body = "Request: " . json_encode($_REQUEST);
				$this->General->sendMails($sub,$body,array('tadka@pay1.in'));
			}
		}
		else if($power == null && $ussd == null){
			$sub = "Receive SMS: Recharge done via online api";
			$body = "Request: " . json_encode($_REQUEST);
			$this->General->sendMails($sub,$body,array('ashish@pay1.in'));
		}

		if(isset($_REQUEST['mobile']))$mobile = urldecode($_REQUEST['mobile']);
		$mobile = substr($mobile, -10);
		$code = "";
		$sms_time = "0000-00-00 00:00:00";
		if(isset($_REQUEST['message']))$msg = trim(urldecode($_REQUEST['message']));
		if(isset($_REQUEST['code']))$code = trim(urldecode($_REQUEST['code']));
		if(isset($_REQUEST['smstime']))$sms_time = trim(urldecode($_REQUEST['smstime']));

		$datetime = date('Y-m-d H:i:s');
		if($ussd == null)
		$this->User->query("INSERT INTO virtual_number (mobile,message,virtual_num,sms_time,timestamp,date) VALUES ('$mobile','".addslashes($msg)."','$code','$sms_time','$datetime','".date('Y-m-d')."')");

		$res = $this->Api->receiveSMS($mobile,$msg,$power,$ussd);
		if($ussd == null)
		$this->User->query("UPDATE virtual_number SET description = '".addslashes($res['msg'])."' WHERE mobile = '$mobile' AND timestamp = '$datetime'");

		if(!empty($res) && !empty($res['msg'])){
			if($ussd == null){
				$this->General->sendMessage($res['mobile'],$res['msg'],$res['root']);
				$this->autoRender = false;
			}
			else {
				return $res['msg'];
			}
		}
		else {
			if($ussd == null){
				$this->autoRender = false;
			}
			else {
				return;
			}
		}
	}

	function sendTopUpRequest(){
		$this->autoRender = false;

		$mobile = $_REQUEST['mobile'];
		$transId = str_replace( " ", "", $_REQUEST['bank_acc_id']."_".$_REQUEST['bank_trans_id']."_".$_REQUEST['trans_type_id'] );
		$msg = "bnk ".$_REQUEST['amount']." ".$transId;//'bnk amt bankAcc_bankTransId_bankTransType'

                $imgUrl     = '';
                $image_name = 'bank_slip';
                $extra_data = NULL;
                $main_ses   = 1;
                
                $allowed_exts = array("gif", "jpeg", "jpg", "png");

                $ext = explode('.', $_FILES[$image_name]['name']);
                $extension = $ext[sizeof($ext) - 1];
                
                if(!empty($_FILES[$image_name]['name']) && !in_array($_FILES[$image_name]['type'], array('image/png','image/jpeg','image/jpg','image/gif')) && !in_array($extension, $allowed_exts)) {
                    $res['msg'] = "<b>*Allowed file types:</b> gif, jpeg, jpg, png";
                }
                else if($_FILES[$image_name]['size'] > 5242000) {
                        $res['msg'] = "Image size can't be more than 5 MB";
                } else {
                        if($_FILES[$image_name]['name'] != '') {
                                $imgUrl = $this->uploadImage($image_name);

                        }

                        if($_REQUEST['branch_name'] != '' || $_REQUEST['branch_code'] != '' || $imgUrl != '') {
                                $extra_data = json_encode(array(
                                            'branch_name' => $_REQUEST['branch_name'],
                                            'branch_code' => $_REQUEST['branch_code'],
                                            'bank_slip'   => $imgUrl
                                        ));
                        }

                        $res = $this->Api->receiveSMS($mobile, $msg, null,"",null,$extra_data,$main_ses);
                }

		$this->set('msg', $res['msg']);
                $this->render('/shops/dist_topup_request');
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


	function logout(){
		if(isset($_SESSION['Auth']['id'])){
			if(session_destroy())return array('status'=>'success');
			else return array('status'=>'failure','code'=>'30','description'=>$this->Shop->errors(30));
		}else{
			return array('status'=>'success');
		}
	}

	function chkUpdate($params,$format){
		if($this->appVersion > $params['version'])
		return array('status'=>'success','description'=>'Kindly update the software. Click the below link.');
		else
		return array('status'=>'failure');
	}

	function log($params){
		$file = fopen("/tmp/android_debug_log.txt","a+");


		foreach($params as $k=>$p){
			fwrite($file, $k."::".$p."\n\n");
		}
		fclose($file);
		return array("status" => "success", "description" => "Done");
	}


	function reversal($params,$format=null,$user_id=null){
		return $this->Api->reversal($params,$format,$user_id);
	}

       /* function billers() {
		$billers = $this->Api->billers();
                $this->displayWeb($billers, 'json');
        }*/

        function bbpsComplainRegistration() {

                try {
                        $ret = $this->Api->bbpsComplaintRegistration($_REQUEST);
                        $this->displayWeb($ret, 'json');
                } catch(Exception $e) {
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
        }

        function bbpsComplaints($params = array()) {
		return $this->Api->bbpsComplaints($params);
        }

        function bbpsTxnDetails($params = array()) {
		return $this->Api->bbpsTxnDetails($params['txn_id']);
        }

//        function bbpsComplainTracking() {
//
//                try {
//                        $ret = $this->Api->bbpsComplaintTracking();
//                } catch(Exception $e) {
//			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
//		}
//        }

	function displayWeb($msg,$format,$log_id=null){
		if(empty($log_id))$log_id = $msg['app_log_id'];

		if(isset($msg['description']) && !is_array($msg['description']))
		$this->User->query("update app_req_log set description = '".addslashes($msg['description'])."' where id = $log_id");

		//}
		if($format == 'json'){
			header('Content-Type: application/javascript');
                        ob_end_clean();
			$root_a = isset($_GET['root']) ? $_GET['root'] : "";
			if(isset($_REQUEST['root']) && !preg_match("/^[a-zA-Z]+[0-9]*(\_)*[a-zA-Z0-9]*$/",$_REQUEST['root'])){
			    $root_a = '';
			}
			echo  trim($root_a .'(['.json_encode($msg).']);');
			//echo  $_GET['root'] .'(['.json_encode($msg).']);';
		}else if("xml"){
			header('Content-Type: application/xml');
			$xml = new XmlWriter();
			$xml->openMemory();
			$xml->startDocument('1.0', 'UTF-8');
			$xml->startElement('root');

			function write(XMLWriter $xml, $msg){
				foreach($msg as $key => $value){
					if(is_array($value)){
						$xml->startElement($key);
						write($xml, $value);
						$xml->endElement();
						continue;
					}
					$xml->writeElement($key, $value);
				}
			}
			write($xml, $msg);
			$xml->endElement();
			echo $xml->outputMemory(true);
		}
		$this->autoRender = false;
	}

        function testBbps() {

                $curl_url   = "http://180.179.175.40/billpay/extMdmCntrl/mdmRequest/xml";
                $key        = "722B238A25F88250A482C86DA254DC03";
                $input      = "billerId=CCAV00000MAH01&billerName=OTME&billerCategory=Mobile&billerAdhoc=true&billerCoverage=IND&billerFetchRequiremet=MANDATORY&billerPaymentExactness=Exact";
                $access_code = "AVXU70UC11PJ72CTVK";
                $institute_id = "PY01";

                $params   = "accessCode=$access_code&encRequest=b86a68c3c2df5bda678f12f547d4e533bfa286cf6679ffb102e75580dd8f9abd2b4e68a9305942ec074c59813623ec6e10a95d5c61bd33112c76cf3c3f4024ce8e239f5b5054b1372ad6fec624507c224f3333bfbc43fd6743caa6279d48c81c091ca11eef8419bdbdeaa7995f0123982824bca6f1382443f8193e4f270091dfb1f9d189d50911af921175f971338155573359a86f377dbb2af0768c8d9cfba76bd9fdd2b5f3fcb8c61d013829684150707eaa7bb16fb8ff37946ff942c23a91ca9b41992dc75cba901f8799679d97439f7a9024a3c6ccaf93b2a7b47f325f1b0a3d0d4cd7a1e936ae20133541184b4f813547a2fd2f5c82291178d6e700b9c07c1ed44da53a4233fab6f9e3d3e980626e5c929fb9b7a836d10c71e8969ecfef0b22c3be3acee46dd2d34d37f2a4de5f183abdf8a0e183cf18a48a8494588b031d19d5881324c411308ee5738a3312b1&requestId=1001&ver=1.0&instituteId=$institute_id";

                $res = $this->General->curl_post($curl_url, $params, 'POST', 30, 10, true, true, true, false, 'ccavenuecom.crt');
                echo "<pre>";print_r($res);die;

		$this->autoRender = false;
        }

        function encrypt($plainText,$key) {
		$secretKey = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
	  	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
		$plainPad = pkcs5_pad($plainText, $blockSize);
	  	if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1)
		{
		      $encryptedText = mcrypt_generic($openMode, $plainPad);
	      	      mcrypt_generic_deinit($openMode);

		}
		return bin2hex($encryptedText);
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

        function arrayToXml($arr) {
                $xml = new SimpleXMLElement('<root/>');
                array_walk_recursive($arr, array ($xml, 'addChild'));
                return $xml->asXML();
        }

    function receiveWeb($u,$p,$format='json'){
        $logger = $this->General->dumpLog('ReceiveWeb Request', 'receiveWeb');
        $MsgTemplate = $this->General->LoadApiBalance();
        if(!in_array($format,$this->validFormats))$format = 'json';
        $method = $_REQUEST['method'];
        if($method == 'sendBalanceTopupRequest'){
            header('Access-Control-Allow-Origin: *');
        }
        
        if(isset($_REQUEST['root']) && !preg_match("/^[a-zA-Z]+[0-9]*(\_)*[a-zA-Z0-9]*$/",$_REQUEST['root'])){
            $this->displayWeb(array('status'=>'failure','code'=>'4','description'=>$this->Shop->errors(4)), $format); exit;
        }
        
        if(isset($_REQUEST['callback']) && !preg_match("/^[a-zA-Z]+[0-9]*(\_)*[0-9]*$/",$_REQUEST['callback'])){
            $this->displayWeb(array('status'=>'failure','code'=>'4','description'=>$this->Shop->errors(4)), $format); exit;
        }

        $deviceType = empty($_REQUEST['device_type']) ? "" : $_REQUEST['device_type'];
        //if(empty ($_SESSION['Auth']['id'])){//if user is not logged in
        //$this->displayWeb(array('status'=>'failure','code'=>'0','description'=>$this->Shop->errors(0)), $format); exit;

        //}else{
        $authId = empty ($_SESSION['Auth']['id']) ? 0 : $_SESSION['Auth']['id'];
        $requestid = rand() . time();
        $client_ip = $this->General->getClientIP();
        $_REQUEST['ip'] = $client_ip;
        $php_processId = getmypid();
        $logger->info("Request Parameters of $authId $method: $requestid | $php_processId |Result status of $authId=|".$u."|".$p."|".json_encode($_REQUEST) . "| SERVER: " . $client_ip );
		//$this->User->query("INSERT INTO app_req_log (method,params,ret_id,timesatmp,date) VALUES ('$method','".json_encode($_REQUEST)."',".$authId.",'".date('Y-m-d H:i:s')."','".date('Y-m-d')."')");

		$this->data['AppReqLog']['method'] = $method;
		$this->data['AppReqLog']['params'] = json_encode($_REQUEST);
		$this->data['AppReqLog']['ret_id'] = $authId;
		$this->data['AppReqLog']['timesatmp'] = date('Y-m-d H:i:s');
		$this->data['AppReqLog']['date'] = date('Y-m-d');

		$this->AppReqLog->create();
		if ($this->AppReqLog->save($this->data)) {
			$app_log_id = $this->AppReqLog->getInsertID();
		}

		if(!method_exists($this, $method)){
			$logger->info("$requestid: Response::" . $this->Shop->errors(2));
			$this->displayWeb(array('status'=>'failure','code'=>'2','description'=>$this->Shop->errors(2),'app_log_id'=>$app_log_id), $format); exit;
		}

		try{
            $acl = $this->checkForAccess($method);
			if($acl !== true){
				$logger->info("$requestid: Access issues::" . $this->Shop->errors($acl));
				$this->displayWeb(array('status'=>'failure','code'=>$acl,'description'=>$this->Shop->errors($acl),'app_log_id'=>$app_log_id), $format);exit;
			}


			if(in_array($method,array('mobRecharge','dthRecharge','vasRecharge','mobBillPayment','utilityBillPayment','getBusTicket','pay1Wallet','walletTopup','cashpgPayment'))){

                                $service_mapp = array('mobRecharge'=>1,'dthRecharge'=>2,'vasRecharge'=>3,'mobBillPayment'=>4,'utilityBillPayment'=>6,'pay1Wallet'=>5,'walletTopup'=>5,'cashpgPayment'=>7);

                                $dist_details = $this->Slaves->query("SELECT active_services FROM distributors WHERE id = '".$_SESSION['Auth']['parent_id']."'");

                                if(!in_array($service_mapp[$method], explode(',', $dist_details[0]['distributors']['active_services']))) {
                                        $this->displayWeb(array('status'=>'failure', 'code'=>'214', 'description'=>'Your Distributor is Inactive for this service', 'app_log_id'=>$app_log_id), $format); exit;
                                }

				$info = $this->Shop->getShopDataById($authId,RETAILER);

				$profile_id = empty($_REQUEST['profile_id']) ? "" : $_REQUEST['profile_id'];
				$profile_id = trim($profile_id);
				$profile_id = intval($profile_id);
				$profile_id = empty($profile_id) ? null : $profile_id;
				$userData = $this->General->getUserDataFromId($info['user_id'],$profile_id);

                //If profileid from app is not matching with the user's profile id then logout the user
				if(!empty($profile_id) && $profile_id != $_SESSION['Auth']['User']['profile_id']){
					$this->logout();
					$this->General->sendMails("Profile id not matching",json_encode($_REQUEST) . "<br/>".$profile_id . json_encode($_SESSION),array('ashish@pay1.in'),'mail');
					$this->displayWeb(array('status'=>'failure','code'=>'403','description'=>$this->Shop->errors('403'),'app_log_id'=>$app_log_id), $format);exit;
				}
				//$userData = $this->General->getUserDataFromId($info['user_id']);

				if($method == 'mobRecharge' || $method == 'dthRecharge' || $method == 'mobBillPayment' || $method == 'utilityBillPayment' || $method == 'pay1Wallet' || $method == 'walletTopup'){
					if($method == 'mobRecharge' || $method == 'mobBillPayment' || $method == 'pay1Wallet' || $method == 'walletTopup')
					$mobT = $_REQUEST['mobileNumber'];
					else if($method == 'dthRecharge')
					$mobT = $_REQUEST['subId'];
					else if($method == 'utilityBillPayment'){
					    $mobT = $_REQUEST['accountNumber'];
					    $_REQUEST['param'] = ($_REQUEST['param'] == 'null') ? '' : $_REQUEST['param'];
					    $_REQUEST['param1'] = ($_REQUEST['param1'] == 'null') ? '' : $_REQUEST['param1'];

					    if(isset($_REQUEST['param']) && !empty($_REQUEST['param'])){
					        $mobT = $mobT ."*". $_REQUEST['param'];
					    }
					    if(isset($_REQUEST['param1']) && !empty($_REQUEST['param1'])){
					        $mobT = $mobT ."*". $_REQUEST['param1'];
					    }
					}

					$amt = $_REQUEST['amount'];
					$opr = isset($_REQUEST['operator']) ? $_REQUEST['operator'] : "";

					if($method == 'pay1Wallet' || $method == 'walletTopup')$opr = WALLET_ID;
					//$id = $this->Shop->addAppRequest($method,$mobT,$_REQUEST['amount'],$_REQUEST['operator'],1);
				}else if($method == 'getBusTicket'){
					$mobT = $_REQUEST['mobileNumber'];
					$amt = $_REQUEST['amount'];
					$opr = $_REQUEST['operator'];
					//$id = $this->Shop->addAppRequest($method,$_REQUEST['Mobile'],$amt,$_REQUEST['product'],1);

				}elseif ($method == "cashpgPayment") {  //handle cashpg Payment request
                                        $mobT = $_REQUEST['mobileNumber'];
					$amt = $_REQUEST['amount'];
                                        $opr = $_REQUEST['operator'];
                }
                else {
					if(!isset($_REQUEST['Amount'])){
						$d = $this->Shop->getProdInfo($_REQUEST['product']);
						$amt = $d['price'];
					}
					else $amt = $_REQUEST['Amount'];
					$mobT = $_REQUEST['Mobile'];
					$opr = $_REQUEST['product'];
					//$id = $this->Shop->addAppRequest($method,$_REQUEST['Mobile'],$amt,$_REQUEST['product'],1);
				}

				//if($_REQUEST['test']=='1'){
				//  echo "=============";
				//}

				if($info['block_flag'] == 2){
					$sub = "Pay1 - Fully blocked Retailer trying to do transactions via app";
					$body = "Retailer Mobile: ".$_SESSION['Auth']['mobile'];
					$this->General->sendMails($sub,$body,array('notifications@pay1.in'));
					//$this->Shop->deleteAppRequest($mobT,$amt);
					$logger->info("$requestid: Demo blocked::" . "Dear Retailer, Your demo is blocked. Kindly contact your distributor");

					$this->displayWeb(array('status'=>'failure','code'=>'38','description'=>"Dear Retailer, Your demo is blocked. Kindly contact your distributor",'app_log_id'=>$app_log_id), $format); exit;
				}

				//check for correct hash
				$mobNo = empty($_REQUEST['subId']) ?  isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : "" : $_REQUEST['subId'];


				$hash_check = true;

				if($deviceType == "android"){
					$_REQUEST['api_flag'] = 3;
					$hash_check = false;
				}else if($deviceType == "java"){
					$manufacturer = (isset($userData['manufacturer']) && !empty($userData['manufacturer'])) ? explode(":",$userData['manufacturer']) : array();
					if(isset($manufacturer[1]) && $manufacturer[1] <= "2.0"){
						$hash_check = false;
					}
					$_REQUEST['api_flag'] = 5;
					$hash_check = false;
				}else if($deviceType == "windows7"){
					$_REQUEST['api_flag'] = 7;
				}else if($deviceType == "windows8"){
					$_REQUEST['api_flag'] = 8;
				}else if($deviceType == "web"){
					$_REQUEST['api_flag'] = 9;
					$hash_check = false;
				}else{
					$_REQUEST['api_flag'] = 1;
					$hash_check = false;
				}

				if($hash_check && !$this->Shop->appRechargeAccessHashCheck($userData['profile_uuid'], $mobT , (!isset($_REQUEST['amount'])?'':$_REQUEST['amount']) ,$_REQUEST['timestamp'] ,$_REQUEST['hash_code'] )){//appRechargeAccessHashCheck
					$logger->info("Hash not matched of $requestid::" . $this->Shop->errors(0));

					$this->displayWeb(array('status'=>'failure','code'=>'0','description'=>$this->Shop->errors(0),'app_log_id'=>$app_log_id), $format); exit;
				}

				if($method=='dthRecharge'){
					$opr = $opr+15;
				} else if($method=='mobBillPayment'){
					$opr = $opr+35;
				} else if($method=='utilityBillPayment' && $opr < 10){
					$opr = $opr+44;
				}

				$id = $this->Shop->addAppRequest($method,$mobT,$amt,$opr,1,$authId);

				$this->General->logData("addrequest.txt","method =>".$method."<br/>"."mobile =>".$mobT."<br/>"."amount=>".$amt."</br>"."operator=>".$opr."<br/>"."retailerid=>".$authId);

				if(empty($id)){
					$logger->info("$requestid: Response::" . $this->Shop->errors(38));

					//$this->General->logData("apprequest.txt","method =>".$method."<br/>"."mobile =>".$mobT."<br/>"."amount=>".$amt."</br>"."operator=>".$opr."<br/>"."retailerid=>".$authId);
					//echo "--".$method."---".$mobT."---".$amt."---".$opr."---";
					$this->displayWeb(array('status'=>'failure','code'=>'38','description'=>$this->Shop->errors(38),'app_log_id'=>$app_log_id), $format); exit;
				}
				else {
				    $params = $_REQUEST;
				    if(isset($params['root'])){
				        $check_sameRequest = $this->Shop->addMemcache($params['root'],1,3*24*60*60);
				        if(!$check_sameRequest){
				            $logger->info("$requestid: Same request Browser::".json_encode($params));
				            //$this->General->sendMails("Same request for web api",json_encode($params),array('ashish@pay1.in'),'mail');
				            $this->displayWeb(array('status'=>'failure','code'=>'3','description'=>'Error: Duplicate Request','app_log_id'=>$app_log_id), $format); exit;
				            //return array('status'=>'failure','code'=>'3','description'=>"Error: Duplicate Request");
				        }

				        /*$time_milli = round(microtime(true) * 1000);
				         if(isset($params['_'])){
				         $diff = abs($time_milli - $params['_']) ;
				         if($diff > 5000000){
				         $this->General->sendMails("Problem in web request: Time issues: ",$diff . "::".json_encode($params),array('ashish@pay1.in'),'mail');
				         }
				         }*/
				    }
					$ret = $this->$method($_REQUEST, $format);

					if($ret['status'] == 'failure'){
					    $this->Shop->deleteAppRequest($mobT,$amt,$opr,$authId);
					    if(isset($params['root']))$this->Shop->delMemcache($params['root']);
					}

				}
				$logger->info("Response of $requestid::".json_encode($ret));

			}
			else {

				$logger->info("Request parameters calling method for $requestid=".$method);
				$ret = $this->$method($_REQUEST,$format);
				//$this->displayWeb($ret, $format,$app_log_id);

                                if(($deviceType == "web") && ($deviceType == "android")){
                                 if($method == 'authenticate_new')$logger->info("Response of $requestid::".json_encode($ret));
                                }else{
                                 if($method == 'authenticate')$logger->info("Response of $requestid::".json_encode($ret));
                                }
                            }

			if(isset($ret['status']) && $ret['status'] =='success' && $method == "mobBillPayment"){
//				$msg_user = "Dear User\nYour request of bill payment of Rs $amt accepted successfully from Pay1. Wait for some time for your operator's confirmation. \nYour pay1 txnid: " . $ret['description'];

                                $paramdata['VENDORS_ACTIVATIONS_AMOUNT'] = $amt;
                                $paramdata['TRANSID'] =  $ret['description'];
                                $content =  $MsgTemplate['UserRequest_Of_MobBill_Payment_MSG'];
                                $msg_user = $this->General->ReplaceMultiWord($paramdata,$content);

                                $this->General->sendMessage(array($mobT),$msg_user,'shops');
			}
			else if(isset($ret['status']) && $ret['status'] == 'success' && $method == "utilityBillPayment"){
//				$msg_user = "Dear User\nYour request of utility bill payment of Rs $amt accepted successfully from Pay1. Give us 24-48 hours to complete this payment for you.\nYour pay1 txnid: " . $ret['description'];

                                $paramdata['AMOUNT'] = $amt;
                                $paramdata['TRANSID'] =  $ret['description'];
                                $content =  $MsgTemplate['UserRequest_Of_UtilBill_Payment_MSG'];
                                $msg_user = $this->General->ReplaceMultiWord($paramdata,$content);

                                $this->General->sendMessage(array($_REQUEST['mobileNumber']),$msg_user,'shops');
			}

			$this->displayWeb($ret, $format,$app_log_id);

		}catch(Exception $e){
			$logger->info("Response of $requestid::".$this->Shop->errors(30));
			$this->displayWeb(array('status'=>'failure','code'=>'30','description'=>$this->Shop->errors(30),'app_log_id'=>$app_log_id), $format); exit;
		}

		$this->autoRender = false;
	}
    /*
     * This function will return list of pending cash pg request
     */
    function cashpgTxnList($params,$format){
        $params = $_REQUEST;
        /*if($params['mobileNumber'] != $_SESSION['Auth']['mobile']){
                return array('status'=>'failure','code'=>'61','description'=>$this->Shop->errors(61));
        }*/
        if($this->General->mobileValidate($params['mobileNumber']) == 1){//mobile no validation
                return array('status'=>'failure','code'=>'5','description'=>$this->Shop->errors(5));
        }
        $params['method'] = "cashpgPayment";
        App::import('Controller', 'Cashpayment');
		$obj = new CashpaymentController;
		$obj->constructClasses();
		$ret = $obj->cashpayment_api_manager($params);
		return $ret;
    }

    function dashboard($params,$format){
        $top_trending_prods = TOP_TRENDING_PRODUCTS;
        $data = array();
        $data['trending_product'] = explode(',',$top_trending_prods);
        $data['vmn_list'] = $this->Shop->getVMNList('fromLogin');
        $data['topup_flag'] = 0;

        if(isset($_SESSION['Auth'])){
            $dist_id = $_SESSION['Auth']['parent_id'];
            $pg_check = $this->Slaves->query("SELECT active_flag,service_charge FROM pg_checks WHERE distributor_id = '".$dist_id."'");
            if(!empty($pg_check)){
                $data['topup_flag'] = $pg_check[0]['pg_checks']['active_flag'];
            }
        }

        return  array(
                'status' => 'success',
                'code' => 0,
                'data' => $data,
                'msg' => ''
        );
    }


	function apiRecharge($params,$partner){
		$ret = array();
		$operator = trim(urldecode($params['operator']));
		$amount = trim(urldecode($params['amount']));
		$mobile = trim(urldecode($params['mobile']));
		$trans_id = trim(urldecode($params['trans_id']));

		$mobDthNo = trim(urldecode($params['mobile']));

		if(isset($params['special']))$special = trim(urldecode($params['special']));
		else $special = 0;
		if(isset($params['subid']))$subid = trim(urldecode($params['subid']));
		else $subid = 0;

		if(empty($subid)){
			$msg = "*$operator*$mobile*$amount";
		}
		else {
			$msg = "*$operator*$subid*$mobile*$amount";
			$mobDthNo = $subid;
		}

		if($special == 1)$msg .= "#";


		$data = $this->Slaves->query("SELECT * FROM partners_log WHERE partner_id = ". $partner['Partner']['id'] . " AND partner_req_id = '$trans_id'");
		if(!empty($data)){
			return array('status'=>'failure','errCode'=>'E014','description'=>$this->Shop->apiErrors('E014'));
		}

		$response = $this->Api->receiveSMS($partner['retailers']['mobile'],$msg,1,4,$partner['Partner']['id']);


		$desc = "";
		// because create vendor activation is returning vendor_actvation_id inside the description field

		if($response['status'] == 'success'){
			$ven_act_id = $response['transid'];
			$desc = "Successful transaction .";
			$err_code = 0;

		}else{
			$response['status'] == 'failure';
			$ven_act_id = 0;
			$err_code = $this->Shop->mapApiErrs($response['code']);
			$desc = $this->Shop->apiErrors($err_code);

			if(empty($desc)){
				$err_code = 'E000';
				$desc = $this->Shop->apiErrors('E000');
			}
		}

		$reqObj = ClassRegistry::init('PartnerLog');
		$this->data = array();
		$this->data['PartnerLog']['partner_req_id'] = $trans_id;
		$this->data['PartnerLog']['partner_id'] = $partner['Partner']['id'];
		$this->data['PartnerLog']['vendor_actv_id'] =  $ven_act_id;
		$this->data['PartnerLog']['mob_dth_no'] =  $mobDthNo;
		$this->data['PartnerLog']['amount'] =  $amount;
		$this->data['PartnerLog']['product_id'] =  $operator;
		$this->data['PartnerLog']['err_code'] =  $err_code;
		$this->data['PartnerLog']['description'] =  $desc;
		$this->data['PartnerLog']['created'] =  date('Y-m-d H:i:s');
		$this->data['PartnerLog']['date'] =  date('Y-m-d');
		$reqObj->create();
		if($reqObj->save($this->data)){
			$ref_code =  "2082" . sprintf('%06d', $reqObj->id);
			$ret = array('status'=>$response['status'],'partner_reqid'=>$trans_id,'ref_code'=>$ref_code,'errCode'=>$err_code,'description'=>$desc);
			if($response['status']=='success'){
				$ret['balance'] = $response['balance'];
			}
		}


		return $ret;
	}

	function receiveApi(){
		$ret = array();

		$logger = $this->General->dumpLog('ReceiveAPI Request', 'receiveAPI');
		$this->General->logData("api_vendors.txt","in receive api: ".json_encode($_REQUEST));
		$operation_type = trim(urldecode($_REQUEST['operation_type']));//method
		$partner_id = trim(urldecode($_REQUEST['partner_id']));//partner account number

		$partnerRegObj = ClassRegistry::init('Partner');
		$partner = $partnerRegObj->query("SELECT Partner.*,retailers.mobile,users.balance FROM partners as Partner,retailers,users WHERE Partner.acc_id = '$partner_id' AND retailers.id = Partner.retailer_id AND retailers.user_id = users.id");
		$partner = $partner['0'];
		//$partner = $partnerRegObj->find('first',array('Partner.acc_id'=>$_REQUEST['partner_id']));

		//$logger->info("Request Parameters of ".$_REQUEST['partner_id'] .": ".json_encode($_REQUEST));

		$accessRes = $this->checkForApiAccess($_REQUEST,$partner);

		if($accessRes['access']){
			if($operation_type == 1){//call recharge api
				$params = $_REQUEST;
				if(isset($params['number'])){

					$prodCode = $this->Shop->smsProdCodes($params['operator']);
					if($prodCode['method']=='mobRecharge' || $prodCode['method']=='mobBillPayment'){
						$params['mobile'] = isset($params['number']) ? $params['number'] : $params['mobile'];
					}elseif($prodCode['method']=='dthRecharge'){
						$params['subid'] = isset($params['subid'])?$params['subid']:$params['number'];
						$params['mobile'] = isset($params['mobile']) ? $params['mobile'] : "7010101010";//fake no.@TODO change this no if required
					}
					elseif($prodCode['method']=='utilityBillPayment'){
						$params['accountNumber'] = $params['number'];
						$params['param'] = $params['param'];
						$params['mobile'] = isset($params['mobile']) ? $params['mobile'] : "7010101030";//fake no.@TODO change this no if required
					}
				}
				//SMS Tadka Balance is less then 5000 then send a mail to admin
				if($partner['Partner']['acc_id']=="P000001" && $partner['users']['balance'] <= 10000 ){
					$this->General->sendMails("SMSTDKA api account balance is less then 10000.","SmsTdka recharge api account balance is less then 10000 , current balance is ".$partner['users']['balance'],array('tadka@pay1.in'),'mail');
				}
				else if($partner['Partner']['acc_id']=="P000002" && $partner['users']['balance'] <= 200000 ){
					$this->General->sendMails("PAY1 B2C api account balance is less then 200000.","PAY1 B2C api account balance is less then 200000 , current balance is ".$partner['users']['balance'],array('tadka@pay1.in','limits@pay1.in'),'mail');
				}
				$ret = $this->apiRecharge($params,$partner);
			}
			else if($operation_type == 2){//call balance check api
				$ret = array('status'=>'success','balance'=>$partner['users']['balance']);
			}
			else if($operation_type == 3){// call transaction status api
				$ret = $this->apiStatus($_REQUEST,$partner);
			}
			else if($operation_type == 4){// call for transaction complaint
				$ret = $this->apiComplaint($_REQUEST,$partner);
			}
			else {//invalid operator type
				$ret = array('status'=>'failure','errCode'=>'E001','description'=>$this->Shop->apiErrors('E001'));
			}
		}
		else {//invalid access
			$ret = array('status'=>'failure','errCode'=>$accessRes['code'],'description'=>$this->Shop->apiErrors($accessRes['code']));
		}

		echo json_encode($ret);
		$this->autoRender = false;
	}

	function apiStatus($params,$partner){
		$partner_reqid = trim(urldecode(empty($params['trans_id'])?0:$params['trans_id']));//partner account number

		$reqIdArr = explode(",", $partner_reqid);
		if(count($reqIdArr) > 10){
			$ret = array('status'=>'failure','code'=>'E017','description'=>$this->Shop->apiErrors('E017'));
		}else{


			if(count($reqIdArr) == 1 ){
				$val =  $reqIdArr[0] ;
			}else{
				$val = implode("','", $reqIdArr) ;
			}


			$partnerLogs =  $this->User->query("SELECT partners_log.* FROM partners_log WHERE partners_log.partner_req_id in ( '$val' )");
			$received = array();
			$arr = array();

			foreach($partnerLogs as $partnerLog){
				$pay1_trans_id = "2082" . sprintf('%06d', $partnerLog['partners_log']['id']);

				if(empty($partnerLog['partners_log']['err_code'])){
					$retTrans = array('req_id'=>$partnerLog['partners_log']['partner_req_id'],'status'=>'success','errCode'=>$partnerLog['partners_log']['err_code'],'description'=>$partnerLog['partners_log']['description'],'trans_id'=>$pay1_trans_id);
				}
				else {
					$retTrans = array('req_id'=>$partnerLog['partners_log']['partner_req_id'],'status'=>'failure','errCode'=>$partnerLog['partners_log']['err_code'],'description'=>$partnerLog['partners_log']['description'],'trans_id'=>$pay1_trans_id);
				}
				$received[] = $partnerLog['partners_log']['partner_req_id'];
				array_push($arr, $retTrans);
			}

			foreach($reqIdArr as $reqId){
				if(!in_array($reqId,$received)){
					$retTrans = array('req_id'=>$reqId,'status'=>'failure','errCode'=>E015,'description'=>$this->Shop->apiErrors('E015'),'trans_id'=>'');
					array_push($arr, $retTrans);
				}
			}

			$ret = array('status'=>$arr);
		}
		return $ret;
	}

	function apiComplaint($params,$partner){
		$partner_reqid = trim(urldecode(empty($params['trans_id'])?0:$params['trans_id']));//partner account number

		$partnerLogs =  $this->User->query("SELECT vendors_activations.id FROM partners_log left join vendors_activations ON (partners_log.vendor_actv_id = vendors_activations.txn_id) WHERE partners_log.partner_req_id in ( '$partner_reqid' ) AND partners_log.partner_id = $partner");

		if(!empty($partnerLogs['0']['vendors_activations']['id'])){
			$params['id'] = $partnerLogs['0']['vendors_activations']['id'];
			$ret = $this->reversal($params);
			if($ret['status'] == 'failure') $ret['errCode'] = 'E000';
		}
		else {
			$ret = array('status'=>'failure','description'=>'Invalid Transaction id','errCode'=>'E015');
		}
		return $ret;
	}

	function busbooking($params){

		$listAction = array('allsources','availabletrips','bookTicket','getAvailableSeats','getTicketDetails','rbCancelTicket','canPolicy');
		if(!in_array($params['action'],$listAction)){
			$logger->info("Request Parameters"," Invalid Action");
			return array('status'=>'failure','code'=>'3','description'=>$this->Shop->errors(3));
		}

		if($params['action'] == 'bookTicket'){
			$result = $this->Recharge->busBooking($params);
			exit;
		}

		App::import('Controller', 'Redbus');
		$obj = new RedbusController;
		$obj->constructClasses();
		$result = $obj->$params['action']($params);
		exit;

	}

	function getBusTicket($params){
		$result = $this->Recharge->busBooking($params);
	}

        //Send OTP on User Mobile Number for User Device Mapping on Web
        function sendOTPToUserDeviceMapping($mobile,$otp_via_call,$user_id,$lat_long_dist){
        	$user_mobile = $mobile;

        	if(trim($user_mobile)){
        		$user_data = $this->User->query("select mobile from users where id = '".$user_id."'");
                        if($user_data){
        			$otp = $this->General->generatePassword(6);
                                $MsgTemplate = $this->General->LoadApiBalance();
                                $paramdata['OTP'] = $otp;

                                if($lat_long_dist){
                                  $content =  $MsgTemplate['Retailer_LocationVerify_OTP_MSG'];
                                }else{
                                  $content =  $MsgTemplate['Retailer_DeviceVerify_OTP_MSG'];
                                }

                                $message = $this->General->ReplaceMultiWord($paramdata,$content);

                                $this->General->logData("api_authenticate_OTP.txt","in authenticate_new api: ".json_encode($message));


        			$this->Shop->setMemcache("otp_userProfileNewUuid_$user_mobile", $otp, 30*60);

                                if($otp_via_call == 1){

                                    if($this->Shop->getMemcache("user_otp_via_call_$user_mobile")){

                                       return array("status" => "failure", 'code'=>'62', "description" => $this->Shop->apiErrors('62'));

                                    }
                                    $this->Shop->setMemcache("user_otp_via_call_$user_mobile", $otp_via_call, 1*10);
                                    $this->General->curl_post_async("http://click2call.ddns.net/otp.php",
    							array('mobile'=>'2294', 'incoming_route'=>$user_mobile,'otp'=>$otp));

                                    return array("status" => "success", 'code'=>'61', "description" => $this->Shop->apiErrors('61'));
                                }

                                $this->General->sendMessage($user_mobile, $message, 'payone', null);


        			$OTA_Fee = $this->General->findVar("OTA_Fee");
        	            return array('status' => 'success', 'code'=>'59', 'OTA_Fee' => $OTA_Fee, 'description' => $this->Shop->apiErrors('59'));
        		}
        		else
        		    return array('status' => 'failure','code'=>'E025','description' => $this->Shop->apiErrors('E025'));
        	}
        	else
        	    return array('status' => 'failure','code'=>'46','description' => "Mobile ".$this->Shop->apiErrors('46'));
        }

        //Verify OTP of User Mobile Number for User Device Mapping on Web
        function verifyOTPOfUserDeviceMapping($params){

                if(!isset($params['mobile']) || empty($params['mobile']) || !isset($params['otp']) || empty($params['otp'])){
                    return array('status' => 'failure','code'=>'57','description' => $this->Shop->apiErrors('57'));
                }

                $verify = $this->checkAuthenticateDeviceType($params['device_type']);
                //if device_type is not found
                if(!$verify){
                   return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                }

                if ($this->General->mobileValidate($params['mobile']) == '1') {//mobile no validation
                    return array('status' => 'failure', 'code' => '28', 'description' => $this->Shop->errors(28));
                }

                $this->General->logData("api_vendors.txt","in verifyOTPOfUserDeviceMapping api: ".json_encode($params));
                $user_mobile = $params['mobile'];
                $uuid = $params['uuid'];
                $otp = $params['otp'];
                $location_src = empty($params['location_src']) ? "" : $params['location_src'];
                $device_ver = empty($params['version']) ? "" : $params['version'];
                $device_manufacturer = empty($params['manufacturer']) ? "" : $params['manufacturer'];
                if(strlen($otp) != 6){
                        return array('status' => 'failure','code'=>'54','description' => $this->Shop->apiErrors('54'));
                }

                $user_exists = $this->User->query("select * from users where mobile = '".$user_mobile."'");
                if(empty($user_exists)){
                    return array('status' => 'failure','code'=>'49','description' => $this->Shop->apiErrors('49'));
                }
                $user_id = $user_exists[0]['users']['id'];

                if(trim($user_mobile)){
                        $user_data = $this->User->query("select * from users where id = '".$user_id."' ");

                    if(!empty($user_data)){

                        $area_id = $this->General->getAreaIDByLatLong($params['longitude'],$params['latitude']);
                        if($otp == $this->Shop->getMemcache("otp_userProfileNewUuid_$user_mobile") || !$this->General->isOTPRequired($user_mobile)){

                            $this->Shop->delMemcache("otp_userProfileNewUuid_$user_mobile");

                            $user_insert_data = $this->User->query("INSERT INTO `user_profile` (`id`,`user_id`, `gcm_reg_id`, `uuid`, `longitude`, `latitude`, `location_src` , `area_id`,`device_type` ,`version` , `manufacturer`, `created`, `updated`,`date`) "
                                        . "VALUES (NULL, ".$user_id.", '".$params['gcm_reg_id']."', '".$params['uuid']."', '".$params['longitude']."', '".$params['latitude']."', '".$location_src."' ,".$area_id.",'".$params['device_type']."' ,'".$device_ver."' ,'".$device_manufacturer."' ,'".  date("Y-m-d H:i:s")."', '".  date("Y-m-d H:i:s")."','".date('Y-m-d')."');");


                            if(empty($user_insert_data)){

                              $update_update_data = $this->User->query("UPDATE `user_profile` set `longitude` = '".$params['longitude']."', `latitude` = '".$params['latitude']."', location_src='$location_src' , `area_id` = '$area_id',  `device_type` = '".$params['device_type']."' , `updated` = '".  date("Y-m-d H:i:s")."',`date`='".date('Y-m-d')."'  where user_id = ".$user_id. " and uuid = '".$params['uuid']."' and app_type='recharge_app'");

                                if(empty($update_update_data)){

                                     return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                                }

                            }

                            $user = $this->User->query("select id ,user_id ,gcm_reg_id,uuid,longitude,latitude,location_src,device_type,created
                                                    from user_profile WHERE user_id=".$user_id." AND uuid = '".$uuid."' and app_type='recharge_app'");

                            $user[0]['user_profile']['mobile'] = $user_mobile;
                            $user[0]['user_profile']['otp_verify_flag'] = 1;

                            if(isset($params['verify_otp_from']) && $params['verify_otp_from']=='Web'){

                                $data =   $this->authenticateUser($user[0]['user_profile'],$format);

                            } else {
                               $data =   $this->authenticate_new($user[0]['user_profile'],$format);
                            }
                            return $data;
                        }else{
                                    return array('status' => 'failure','code'=>'54','description' => $this->Shop->apiErrors('54'));
                            }
                    }else
        			return array('status' => 'failure','code'=>'52','description' => $this->Shop->apiErrors('52'));
        	}else{
        		return array('status' => 'failure','code'=>'57','description' => "Mobile ".$this->Shop->apiErrors('57'));
                }

        }

        //New Authenticate Function for Users Device Mapping (Right now Only for Web & Android)
        function authenticate_new($params,$format){

            if(!isset($params['mobile']) || empty($params['mobile']) || !isset($params['uuid']) || empty($params['uuid'])){
                        return array('status' => 'failure','code'=>'28','description' =>'Your Mobile number or uuid should not blank');
                }

                $group_id = RETAILER;

                $this->General->logData("api_vendors.txt","in authenticate_new api: ".json_encode($params));

                //$group_id = RETAILER;
                if(isset($params['password']) && !empty($params['password']))
                {
                    $password = $this->Auth->password($params['password']);
                }

				if ($this->General->mobileValidate($params['mobile']) == '1') {//mobile no validation
				return array('status' => 'failure', 'code' => '28', 'description' => $this->Shop->errors(28));
			    }


	        if((round($params['longitude'],1) == '77.0' && round($params['latitude'],1) == '20.0') || ($params['longitude'] == '77' && $params['latitude'] == '20')){
	             $params['longitude'] = '';
	             $params['latitude'] = '';
	        }
	        //Server GEOIP_LATITUDE & GEOIP_LONGITUDE

                $server_lat        = (isset($_SERVER['GEOIP_LATITUDE']) && !empty($_SERVER['GEOIP_LATITUDE'])) ? $_SERVER['GEOIP_LATITUDE'] : "" ;
                $server_long        = (isset($_SERVER['GEOIP_LONGITUDE']) && !empty($_SERVER['GEOIP_LONGITUDE'])) ? $_SERVER['GEOIP_LONGITUDE'] : "" ;

                if(!empty($params['uuid']) && !ctype_alnum(trim($params['uuid']))){
                    $this->General->logData("uuid_check.txt","UUID $uuid is not numeric");
                    return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                }

                $uuid        = empty($params['uuid']) ? "" : $params['uuid'];
                $gcm_reg_id  = empty($params['gcm_reg_id']) ? "" : $params['gcm_reg_id'];
                $device_type  = empty($params['device_type']) ? "" : $params['device_type'];
                $longitude   = empty($params['longitude']) ? $server_long : $params['longitude'];
                $latitude    = empty($params['latitude']) ? $server_lat : $params['latitude'];
                $location_src = empty($params['location_src']) ? "" : $params['location_src'];
                $device_ver   = empty($params['version']) ? "" : $params['version'];
                $app_version_code    = empty($params['version_code']) ? "" : $params['version_code'];
                $device_manufacturer = empty($params['manufacturer']) ? "" : $params['manufacturer'];
                $area_id = $this->General->getAreaIDByLatLong($longitude,$latitude);

                $lat_long_distance = 0;

                if(!(isset($params['device_type']) && trim($params['device_type']) == 'java')){
			session_regenerate_id(true);
		}

                if(isset($params['version_code']) && !empty($app_version_code)){
                            $update_version_code = $this->General->findVar("pay1_merchant_update_version");
                            if($update_version_code){
                                    if($app_version_code < $update_version_code){
                                            return array("status" => "failure", "code" => "48", "forced_upgrade_flag" => "1", "description" => $this->Shop->errors(48));
                                    }
                            }
                }

                $verify = $this->checkAuthenticateDeviceType($params['device_type']);
                    //for web only
                    if($verify == 9){
                        $gcm_reg_id = $params['uuid'];
                        $uuid = $params['uuid'];
                    }else if($verify == 0){ //if device_type is not found
                     return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                    }

                if(isset($params['otp_verify_flag']) && $params['otp_verify_flag'] == 1){
                    $data = $this->Slaves->query("SELECT users.id, users.passflag, user_profile.id, gcm_reg_id,uuid,longitude,latitude,location_src,device_type,balance FROM users left join user_profile on (user_profile.user_id = users.id and user_profile.uuid = '".$uuid."' AND user_profile.app_type IN ('recharge_app','merchant_app')) inner join user_groups ON (users.id = user_groups.user_id) WHERE mobile = '".$params['mobile']."' AND user_groups.group_id= '".$group_id."' ");
                    $this->General->logData("api_vendors.txt","otp_profile_data: ".json_encode($data));
                    if(empty($data)){
                        return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                    }
                }else{

                    $data = $this->Slaves->query("SELECT users.id, users.balance,users.passflag, users.active_flag,user_profile.id, gcm_reg_id,uuid,longitude,latitude,location_src,device_type,balance FROM users left join user_profile on (user_profile.user_id = users.id and user_profile.uuid = '".$uuid."' AND user_profile.app_type IN ('recharge_app','merchant_app')) inner join user_groups ON (users.id = user_groups.user_id) WHERE mobile = '".$params['mobile']."' AND user_groups.group_id= '".$group_id."' AND password='$password'");
                    $this->General->logData("api_vendors.txt","profile_data: ".json_encode($data));
                    if(empty($data)){
                        return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                    }
                    else if($data[0]['users']['active_flag'] == 0){
                        return array('status' => 'failure','code'=>'404','description' =>$this->Shop->errors(404));
                    }

                    if( ($longitude && $latitude) && ($data[0]['users']['id'] && $data[0]['user_profile']['id']) ){

                        $user_last_lat_long = $this->Slaves->query("SELECT longitude, latitude  FROM `user_profile` WHERE `user_id` = '".$data[0]['users']['id']."' and  `uuid` = '".$uuid."' AND user_profile.app_type IN ('recharge_app','merchant_app') order by updated desc limit 0,1");

                        if($user_last_lat_long['0']['user_profile']['longitude'] && $user_last_lat_long['0']['user_profile']['longitude']){

                            $last_longitude =  $user_last_lat_long['0']['user_profile']['longitude'];
                            $last_latitude =  $user_last_lat_long['0']['user_profile']['latitude'];

                        //Lat Long distance more than 500 KM
                        //$lat_long_distance = $this->General->lat_long_distance($latitude,$longitude,$last_latitude,$last_longitude);
                        //$lat_long_distance = 0;
                        }

                    }

                }

                $uuid_to_be_checked = (isset($params['device_id']) && !empty($params['device_id'])) ? $params['device_id'] : (isset($params['uuid']) && !empty($params['uuid']) ? $params['uuid'] : "");
                if(!empty($uuid_to_be_checked) && !ctype_alnum(trim($uuid_to_be_checked))){
                    $this->General->logData("uuid_check.txt","UUID $uuid is not numeric");
                    return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                }
                $uuid_data_of_user = $this->Slaves->query("SELECT up.uuid as device_id FROM users as u LEFT JOIN user_profile up ON u.id=up.user_id WHERE u.mobile = '".$params['mobile']."' and up.uuid =  '".$uuid_to_be_checked."' AND up.app_type IN ('recharge_app','merchant_app')");
                $params['uuid_data_of_user'] = $uuid_data_of_user;

                if(empty($data[0]['users']['id'])){
                        $this->General->block_attacker(true,$params);
                        return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));

                }else if(empty($data[0]['user_profile']['id']) || $lat_long_distance){

                    $this->General->block_attacker(false,$params,true);//reset blocker counter

                    //for Auto Signup using pay1 partner OTP not sent to users
                    if(isset($params['type']) && $params['type'] === 'a_auth'){
                        $this->User->query("INSERT INTO `shops`.`user_profile` (`id`,`user_id`, `gcm_reg_id`, `uuid`, `longitude`, `latitude`, `location_src` , `area_id`,`device_type` ,`version` , `manufacturer`, `created`, `updated`,`date`) "
                            . "VALUES (NULL, ".$data['0']['users']['id'].", '$gcm_reg_id', '$uuid', '$longitude', '$latitude', '".$location_src."' ,'$area_id','".$device_type."' ,'".$device_ver."' ,'".$device_manufacturer."' ,'".  date("Y-m-d H:i:s")."', '".  date("Y-m-d H:i:s")."','".date('Y-m-d')."');");

                        $userProfile = $this->User->query("select id ,user_id ,gcm_reg_id,uuid,longitude,latitude,location_src,device_type,created
                                                        from user_profile WHERE user_id=".$data['0']['users']['id']." AND uuid = '".$uuid."' AND app_type='recharge_app'");
                    }else{
                        $this->General->logData("api_vendors.txt","send otp: ".json_encode($params)."lat_long_distance". json_encode($lat_long_distance));
                        $otp_data =  $this->sendOTPToUserDeviceMapping($params['mobile'],$params['otp_via_call'],$data['0']['users']['id'],$lat_long_distance);
                            return array(
                                'status'      			=> 'successOTP',
                                'description' 			=> $otp_data,
                                'passFlag'    			=> $data['0']['users']['passflag'],
                                'vmnList'     			=> $this->Shop->getVMNList('fromLogin'),
                            );

                    }
                }else{
                    if($verify == 9){//for web users only
                            if(empty($data[0]['user_profile']['longitude']) || empty($data[0]['user_profile']['latitude'])){//if existing lat,long is empty then
                                $this->User->query("UPDATE `shops`.`user_profile` set `gcm_reg_id`= '$gcm_reg_id',location_src='$location_src' , `longitude` = '$longitude',  `latitude` = '$latitude', `area_id` = '$area_id', `device_type` = '$device_type', `updated` = '".  date("Y-m-d H:i:s")."',`date`='".date('Y-m-d')."'  where user_id = ".$data['0']['users']['id'] . " AND uuid = '$uuid' AND app_type='recharge_app'");
                            }else{//if lat , long is already exist then don't update
                                    $this->User->query("UPDATE `shops`.`user_profile` set `gcm_reg_id`= '$gcm_reg_id',location_src='$location_src' , `area_id` = '$area_id',  `device_type` = '$device_type' , `updated` = '".  date("Y-m-d H:i:s")."',`date`='".date('Y-m-d')."'  where user_id = ".$data['0']['users']['id'] . " AND uuid = '$uuid' AND app_type='recharge_app'");
                            }
                    }else{
                            $this->User->query("UPDATE `shops`.`user_profile` set `gcm_reg_id`= '$gcm_reg_id',location_src='$location_src' , `longitude` = '$longitude',  `latitude` = '$latitude', `area_id` = '$area_id', `device_type` = '$device_type' , `version` = '".$device_ver."' , `manufacturer` ='".$device_manufacturer."',`updated` = '".  date("Y-m-d H:i:s")."',`date`='".date('Y-m-d')."'  where user_id = ".$data['0']['users']['id'] . " AND uuid = '$uuid' AND app_type IN ('recharge_app','merchant_app')");
                    }

                    $userProfile = $this->User->query("select id ,user_id ,gcm_reg_id,uuid,longitude,latitude,location_src,device_type,created
                                                        from user_profile WHERE user_id=".$data['0']['users']['id']." AND uuid = '".$uuid."' AND app_type IN ('recharge_app','merchant_app')");
                }

                $info = $this->Shop->getShopData($data['0']['users']['id'],$group_id);

                if($verify == 3){  //for java
                    $info['version'] = $this->General->findVar('java_version');
                }

                $pg_check = $this->Slaves->query("SELECT active_flag,service_charge FROM pg_checks WHERE distributor_id = '".$info['parent_id']."'");

                $info['balance'] = $data['0']['users']['balance'];
                $info['User']['group_id'] = $group_id;
                $info['User']['group_ids'] = $group_id;
                $info['User']['id'] = $data['0']['users']['id'];
                $info['User']['mobile'] = $params['mobile'];
                $info['User']['passflag'] = $data['0']['users']['passflag'];
                $userProfileID = $userProfile['0']['user_profile']['id'];
                $info['User']['profile_id'] = $userProfileID;
                $info['User']['device_type'] = $userProfile['0']['user_profile']['device_type'];
                $info['User']['notification_id'] = $userProfile['0']['user_profile']['gcm_reg_id'];
                $info['balance'] = $data['0']['users']['balance'];
                $this->Session->write('Auth',$info);

                $this->Shop->setRetailerTrnsDetails($params['mobile'],array('trans_type'=>$device_type,'notification_key'=>$gcm_reg_id));

                $info['latitude'] = $latitude;
                $info['longitude'] = $longitude;

                    return  array(
                        'status'      			=> 'success',
                        'description' 			=> $info,
                        'disApp'      			=> '' ,
                        'passFlag'    			=> $data['0']['users']['passflag'],
                        'show_wholesale'    		=> $info['show_wholesale'],
                        'vmnList'     			=> $this->Shop->getVMNList('fromLogin'),
                        'profile_id'  			=> $userProfileID,
                        'uuid'        			=> $uuid,
                        'pg_flag'     			=> (empty($pg_check)) ? 0 : $pg_check['0']['pg_checks']['active_flag'],
                        'service_charge' 		=> (empty($pg_check)) ? 0 : $pg_check['0']['pg_checks']['service_charge'],
                        'min_amount_for_prompt'         => $this->General->findVar("min_amount_for_prompt"),
                        'CAKEPHP' 		        => $this->Session->id()
                    );
	}


        function checkAuthenticateDeviceType($device_type){
            if(!empty($device_type) && $device_type == "android") {
                                $verify = 2;
                        }else if(!empty($device_type) && $device_type == "windows7") {
                                $verify = 7;
                        }else if(!empty($device_type) && $device_type == "windows8") {
                                $verify = 8;
                        }else if(!empty($device_type) && $device_type == "java") {
                                $verify = 3;
                        }else if(!empty($device_type) && $device_type == "web") {
                                $verify = 9;
                        }else{
                          $verify = 0;
                        }
            return $verify;
        }


	function authenticate($params,$format){
		if($params['type'] == 1) $group_id = RETAILER;
		else if($params['type'] == 2) $group_id = DISTRIBUTOR;
		$password = $this->Auth->password($params['password']);
		if(!(isset($params['device_type']) && trim($params['device_type']) == 'java')){
			session_regenerate_id(true);
		}

                $check_login_blocker = $this->General->block_attacker(false,$params);
                $app_type = (isset($params['app_type'])) ? $params['app_type'] : 'recharge_app';
                $uuid_to_be_checked = (isset($params['device_id']) && !empty($params['device_id'])) ? $params['device_id'] : (isset($params['uuid']) && !empty($params['uuid']) ? $params['uuid'] : "");
                $uuid_data_of_user = $this->Slaves->query("SELECT up.uuid as device_id FROM users as u LEFT JOIN user_profile up ON u.id=up.user_id WHERE u.mobile = '".$params['mobile']."' and up.uuid =  '".$uuid_to_be_checked."' AND up.app_type='$app_type'");
                $params['uuid_data_of_user'] = $uuid_data_of_user;


                if(isset($params['server'])){
                    $group_id = RETAILER;
                    $data = $this->Slaves->query("SELECT users.id,users.balance,passflag,active_flag FROM users inner join user_groups ON (users.id = user_groups.user_id) WHERE mobile = '".$params['mobile']."' AND user_groups.group_id=$group_id AND password='$password'");
                    if(empty($data)){
                        return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));

                    }
                    else if($data[0]['users']['active_flag'] == 0){
                        return array('status' => 'failure','code'=>'404','description' =>$this->Shop->errors(404));
                    }
                    else{
                        $info = $this->Shop->getShopData($data['0']['users']['id'],$group_id);
                        $info['balance'] = $data['0']['users']['balance'];
                        $info['User']['group_id'] = $group_id;
                        $info['User']['id'] = $data['0']['users']['id'];
                        $info['User']['passflag'] = $data['0']['users']['passflag'];
                        $_SESSION['Auth'] = $info;
                        return array(
                                'status' => 'success',
                                'description' => $info,
                                'CAKEPHP' => $this->Session->id()
                        );
                    }
                }
		else if(isset($params['uuid']) && !empty($params['uuid']) && empty($params['mobile'])){//for devices having uuid
			$data = $this->Slaves->query("SELECT users.id ,users.balance,passflag,active_flag,user_groups.group_id FROM users inner join user_groups ON (users.id = user_groups.user_id)  WHERE auth_mobile = '".$params['uuid']."' AND user_groups.group_id=$group_id AND password='$password'");

                    if(!($check_login_blocker === true)){ return $check_login_blocker; }

			if(empty($data)){
                                $check_blocker = $this->General->block_attacker(true,$params);
                                if($check_blocker === true){
                                    return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                                }else{
                                    return $check_blocker;
                                }
			}
			else if($data[0]['users']['active_flag'] == 0){
			    return array('status' => 'failure','code'=>'404','description' =>$this->Shop->errors(404));
			}
			else{
				$info = $this->Shop->getShopData($data['0']['users']['id'],$group_id);
				$info['balance'] = $data['0']['users']['balance'];
				$info['User']['group_id'] = $group_id;
				$info['User']['id'] = $data['0']['users']['id'];
				$info['User']['passflag'] = $data['0']['users']['passflag'];
				$_SESSION['Auth'] = $info;
				//$this->Session->write('Auth',$info);
				//$dis = $this->Shop->disabledApps($data['0']['users']['id']);
				return array(
                                    'status' => 'success',
                                    'description' => $info,
                                    'disApp'=>'' ,
                                    'passFlag'=>$data['0']['users']['passflag'],
                                    'vmnList'=>  $this->Shop->getVMNList('fromLogin')
				);
			}
		}else{

			if ($this->General->mobileValidate($params['mobile']) == '1') {//mobile no validation
				return array('status' => 'failure', 'code' => '28', 'description' => $this->Shop->errors(28));
			}

			$data = $this->Slaves->query("SELECT users.id,users.balance,passflag,active_flag,user_groups.group_id FROM users inner join user_groups ON (users.id = user_groups.user_id) WHERE mobile = '".$params['mobile']."' AND user_groups.group_id=$group_id AND password='$password'");

                        if(!($check_login_blocker === true)){ return $check_login_blocker; }

                        if(empty($data)){
				$check_blocker = $this->General->block_attacker(true,$params);
                                if($check_blocker === true){
                                    return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                                }else{
                                    return $check_blocker;
                                }

			}
			else if($data[0]['users']['active_flag'] == 0){
			    return array('status' => 'failure','code'=>'404','description' =>$this->Shop->errors(404));
			}
			else{
				//$uuid = empty($params['device_id']) ? $this->Auth->password($data['0']['users']['id']) : $params['device_id'];
				$this->General->block_attacker(false,$params,true);//reset blocker counter

				$uuid = empty($params['device_id']) ? "" : $params['device_id'];
				$gcm_reg_id = empty($params['gcm_reg_id']) ? "" : $params['gcm_reg_id'];
				$longitude = empty($params['longitude']) ? "" : $params['longitude'];
				$latitude = empty($params['latitude']) ? "" : $params['latitude'];
				$location_src = empty($params['location_src']) ? "" : $params['location_src'];
				$device_type = empty($params['device_type']) ? "" : $params['device_type'];
				$device_ver = empty($params['version']) ? "" : $params['version'];
				$app_version_code = empty($params['version_code']) ? "" : $params['version_code'];
				$device_manufacturer = empty($params['manufacturer']) ? "" : $params['manufacturer'];

                            if(isset($params['version_code']) && !empty($app_version_code)){
                                    $update_version_code = $this->General->findVar("pay1_merchant_update_version");
                                    if($update_version_code){
                                            if($app_version_code < $update_version_code){
                                                    return array("status" => "failure", "code" => "48", "forced_upgrade_flag" => "1", "description" => $this->Shop->errors(48));
                                            }
                                    }
                            }

				if(!empty($params['device_type']) && $params['device_type'] == "android") {
					$verify = 2;
				}else if(!empty($params['device_type']) && $params['device_type'] == "windows7") {
					$verify = 7;
				}else if(!empty($params['device_type']) && $params['device_type'] == "windows8") {
					$verify = 8;
				}else if(!empty($params['device_type']) && $params['device_type'] == "java") {
					$verify = 3;
				}else if(!empty($params['device_type']) && $params['device_type'] == "web") {
					$verify = 9;
					$uuid = empty($params['uuid']) ? $params['mobile'] : $params['uuid'];
					$gcm_reg_id = empty($params['gcm_reg_id']) ? $params['mobile'] : $params['gcm_reg_id'];
				}else{
					$verify = 1;
				}

                                if($verify == 1){
                                    return array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                                }

                            	if(empty($uuid)){
					$dt = $this->Slaves->query("select * from user_profile WHERE user_id=".$data['0']['users']['id'] ." AND device_type = '".$params['device_type']."' AND app_type='$app_type' order by updated desc limit 1");
					$uuid = $dt['0']['user_profile']['uuid'];
					$longitude = $dt['0']['user_profile']['longitude'];
					$latitude = $dt['0']['user_profile']['latitude'];
					$device_ver = $dt['0']['user_profile']['version'];
					$device_manufacturer = $dt['0']['user_profile']['manufacturer'];
					$gcm_reg_id = $dt['0']['user_profile']['gcm_reg_id'];
				}

			    $info = $this->Shop->getShopData($data['0']['users']['id'],$group_id);

				//patch to be commented in case of any issue
				/*if(empty($info['area_id']) && !empty($longitude) && !empty($latitude)){
					$loc_data = $this->General->getAreaByLatLong($longitude,$latitude);
					$loc_data['state'] = $loc_data['state_name'];
					$loc_data['area'] = $loc_data['area_name'];
					$loc_data['city'] = $loc_data['city_name'];
					$loc_data['latitude'] = $loc_data['lat'];
					$loc_data['longitude'] = $loc_data['lng'];
					$loc_data['address'] = $info['address'];
					$loc_data['update'] = 1;
					$this->General->updateRetailerAddress($info['id'],$data['0']['users']['id'],$loc_data);
				}*/

                            $addDetails = $this->Slaves->query("SELECT
                                                    `locator_area`.`id`,
                                                    `locator_area`.`name`,
                                                    `locator_city`.`id`,
                                                    `locator_city`.`name`,
                                                    `locator_state`.`id`,
                                                    `locator_state`.`name`
                                                FROM
                                                     `locator_area`
                                                     LEFT JOIN  `locator_city`    ON `locator_area`.`city_id`  = `locator_city`.`id`
                                                     LEFT JOIN  `locator_state`  ON `locator_city`.`state_id` = `locator_state`.`id`
                                                WHERE
                                                    `locator_area`.`id` = ".( empty($info['area_id']) ? "0" : $info['area_id'] ));

				if (!empty($addDetails)) {
                    $info['area_name'] = $addDetails['0']['locator_area']['name'];
                    $info['city_id'] = $addDetails['0']['locator_city']['id'];
                    $info['city_name'] = $addDetails['0']['locator_city']['name'];
                    $info['state_id'] = $addDetails['0']['locator_state']['id'];
                    $info['state_name'] = $addDetails['0']['locator_state']['name'];
                } else {

                    $info['area_name'] = "";
                    $info['city_id'] = "";
                    $info['city_name'] = "";
                    $info['state_id'] = "";
                    $info['state_name'] = "";
                }


                if($verify == 3){//java
					$info['version'] = $this->General->findVar('java_version');
				}

				$info['balance'] = $data['0']['users']['balance'];
				$info['User']['group_id'] = $group_id;
				$info['User']['id'] = $data['0']['users']['id'];
				$info['User']['mobile'] = $params['mobile'];
				$info['User']['passflag'] = $data['0']['users']['passflag'];
				$this->Session->write('Auth',$info);
				//$dis = $this->Shop->disabledApps($data['0']['users']['id']);
				$info['User']['auth_mobile'] = $uuid;
				$this->User->query("UPDATE users SET auth_mobile='$uuid' , verify = $verify WHERE id=".$data['0']['users']['id']);
				$userProfileID = 0;

				$pg_check = $this->Slaves->query("SELECT active_flag,service_charge FROM pg_checks WHERE distributor_id = '".$info['parent_id']."'");
				$pg_flag = (empty($pg_check)) ? 0 : $pg_check['0']['pg_checks']['active_flag'];

				if($verify != 1){
					$userProfile = $this->Slaves->query("select id ,user_id ,gcm_reg_id,uuid,longitude,latitude,location_src,device_type
                                                                            from user_profile WHERE user_id=".$data['0']['users']['id']." AND uuid = '".$uuid."' AND app_type='$app_type'");
					/*if(!empty($longitude) && !empty($latitude)){
						$loc_data = $this->General->getAreaByLatLong($longitude,$latitude);
						if(!empty($loc_data['state_name']))$state_id = $this->General->stateInsert($loc_data['state_name']);
						if(!empty($loc_data['city_name']))$city_id = $this->General->cityInsert($loc_data['city_name'],$state_id);
						if(!empty($loc_data['area_name']))$area_id = $this->General->areaInsert($loc_data['area_name'],$city_id);
					}*/

					if(count($userProfile) == 0 || empty($userProfile['0']['user_profile']['id'])){
						$this->User->query("INSERT INTO `shops`.`user_profile` (`id`,`user_id`, `gcm_reg_id`, `uuid`, `longitude`, `latitude`, `location_src` , `area_id`,`device_type` ,`version` , `manufacturer`, `created`, `updated`,`date`) VALUES (NULL, ".$data['0']['users']['id'].", '$gcm_reg_id', '$uuid', '$longitude', '$latitude', '".$location_src."' ,'$area_id','".$device_type."' ,'".$device_ver."' ,'".$device_manufacturer."' ,'".  date("Y-m-d H:i:s")."', '".  date("Y-m-d H:i:s")."','".date('Y-m-d')."');");

						$userProfile = $this->User->query("select id ,user_id ,gcm_reg_id,uuid,longitude,latitude,location_src,device_type,created
                                                                                    from user_profile WHERE user_id=".$data['0']['users']['id']." AND uuid = '".$uuid."' AND app_type='$app_type'");

					}else{
						if($verify == 9){//for web users
							if(empty($userProfile['0']['user_profile']['longitude']) || empty($userProfile['0']['user_profile']['latitude'])){//if existing lat,long is empty then
								$this->User->query("UPDATE `shops`.`user_profile` set `gcm_reg_id`= '$gcm_reg_id',location_src='$location_src' , `longitude` = '$longitude',  `latitude` = '$latitude', `device_type` = '$device_type', `updated` = '".  date("Y-m-d H:i:s")."',`date`='".date('Y-m-d')."'  where user_id = ".$data['0']['users']['id'] . " AND uuid = '$uuid'  AND app_type='$app_type'");//`uuid` = '$uuid',
							}else{//if lat , long is already exist then don't update
								$this->User->query("UPDATE `shops`.`user_profile` set `gcm_reg_id`= '$gcm_reg_id',location_src='$location_src' , `device_type` = '$device_type' , `updated` = '".  date("Y-m-d H:i:s")."',`date`='".date('Y-m-d')."'  where user_id = ".$data['0']['users']['id'] . " AND uuid = '$uuid' AND app_type='$app_type'");//`uuid` = '$uuid',
							}
						}else{
							$this->User->query("UPDATE `shops`.`user_profile` set `gcm_reg_id`= '$gcm_reg_id',location_src='$location_src' , `longitude` = '$longitude',  `latitude` = '$latitude', `device_type` = '$device_type' , `version` = '".$device_ver."' , `manufacturer` ='".$device_manufacturer."',`updated` = '".  date("Y-m-d H:i:s")."',`date`='".date('Y-m-d')."'  where user_id = ".$data['0']['users']['id'] . " AND uuid = '$uuid' AND app_type='$app_type'");//`uuid` = '$uuid',
						}
					}
					//}

					$this->Shop->setRetailerTrnsDetails($params['mobile'],array('trans_type'=>$device_type,'notification_key'=>$gcm_reg_id));
					$userProfileID = $userProfile['0']['user_profile']['id'];
					$info['latitude'] = $userProfile['0']['user_profile']['latitude'];
					$info['longitude'] = $userProfile['0']['user_profile']['longitude'];

					$this->Session->write('Auth.User.profile_id',$userProfileID);

				}

				return  array(
                        	'status'      			=> 'success',
                            'description' 			=> $info,
                            'disApp'      			=> '' ,
                            'passFlag'    			=> $data['0']['users']['passflag'],
                            'vmnList'     			=> $this->Shop->getVMNList('fromLogin'),
                            'profile_id'  			=> $userProfileID,
                            'uuid'        			=> $uuid,
							'pg_flag'     			=> (empty($pg_check)) ? 0 : $pg_check['0']['pg_checks']['active_flag'],
							'service_charge' 		=> (empty($pg_check)) ? 0 : $pg_check['0']['pg_checks']['service_charge'],
							'min_amount_for_prompt' => $this->General->findVar("min_amount_for_prompt"),
							'CAKEPHP' 				=> $this->Session->id()
				);
			}
		}
	}

	function getRetailerList($params,$format){
		try{
			return array('status' => 'success','data' => $this->General->getRetailerList($this->session->read('Auth.User.id')));
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','description'=>$this->Shop->errors(30));
		}
	}
	function getDistToRetlBalTransfer($params,$format){
		//$this->autoRender = false;
		$id = $_SESSION['Auth']['id'];
		$group_id = $_SESSION['Auth']['User']['group_id'];
		//$userObj = ClassRegistry::init('shop_transactions');

		$date_from = $params['date_from'];
		$date_to = $params['date_to'];
		$pageNo = $params['page_no'];
		$itemsPerPage = $params['items_per_page'];

		if ($this->General->dateValidate($date_from) == false || $this->General->dateValidate($date_to) == false) {

			return array('status' => 'failure', 'description' => 'Something went wrong!!!');
		}


		$result = array();
		if($itemsPerPage <= 0 || $pageNo <= 0){
			// $queryPart = " limit 0 , ".PAGE_LIMIT;
			$queryPart = " limit 0 ,100";
		}else{
			$ll = $itemsPerPage * ( $pageNo - 1 ) ;//+ 1; // lower limit
			$ul = $itemsPerPage  ;// upper limit//* $pageNo
			$queryPart = " limit $ll , $ul ";

		}

		$strQ = "SELECT `shop_transactions`.`id`,`shop_transactions`.`amount`,
                                     `shop_transactions`.`timestamp`, `distributors`.`company` ,
                                     `shop_transactions`.`target_opening`,`shop_transactions`.`target_closing`
                                    FROM `shop_transactions`
                                    LEFT OUTER JOIN `retailers` ON `shop_transactions`.`target_id`= `retailers`.`id`
                                    LEFT OUTER JOIN `distributors` ON `retailers`.`parent_id`= `distributors`.`id`
                                    WHERE `shop_transactions`.`date` >= '".$date_from."' AND
                                          `shop_transactions`.`date` <= '".$date_to."' AND
                                          (`shop_transactions`.`type` = '".DIST_RETL_BALANCE_TRANSFER."' OR `shop_transactions`.`type` = '".SLMN_RETL_BALANCE_TRANSFER."') AND
                                        `shop_transactions`.`target_id` = ".$id." AND `shop_transactions`.note is not null AND 
                                          `shop_transactions`.`confirm_flag` != 1
                                    ORDER BY `shop_transactions`.`timestamp` DESC".$queryPart ;

		$r = $this->Slaves->query($strQ);


                if(!empty($r)){
                    $i= 0;
                    foreach ($r as $val){

                        $return['shop_transactions']['id'] = $val['shop_transactions']['id'];
                        $return['shop_transactions']['amount'] = $val['shop_transactions']['amount'];
                        $return['shop_transactions']['timestamp'] = $val['shop_transactions']['timestamp'];
                        $return['opening_closing']['opening'] = $val['shop_transactions']['target_opening'];
                        $return['opening_closing']['closing'] = $val['shop_transactions']['target_closing'];
                        $return['distributors']['company'] = $val['distributors']['company'];
                        $result['data'][$i] = $return;
                        $i++;
                                //opening_closing
                    }
                }



		$trans_count_qry = $strQ = "SELECT count(*) as cnt
                                    FROM `shop_transactions`
                                    LEFT OUTER JOIN `retailers` ON `shop_transactions`.`target_id`= `retailers`.`id`
                                    LEFT OUTER JOIN `distributors` ON `retailers`.`parent_id`= `distributors`.`id`
                                    WHERE `shop_transactions`.`date` >= '".$date_from."' AND
                                          `shop_transactions`.`date` <= '".$date_to."' AND
                                          (`shop_transactions`.`type` = '".DIST_RETL_BALANCE_TRANSFER."' OR `shop_transactions`.`type` = '".SLMN_RETL_BALANCE_TRANSFER."')  AND
                                          `shop_transactions`.`target_id` = ".$id." AND `shop_transactions`.note is not null AND 
                                          `shop_transactions`.`confirm_flag` != 1
                                    ORDER BY `shop_transactions`.`timestamp` DESC";//.$queryPart
		$res = $this->Slaves->query($trans_count_qry);
		$n = $res[0][0]['cnt'];
		$result['status'] = 'success';
		$result['total_count'] = $n;
		return $result;

	}


	function amountTransfer($params,$format){
		return $this->Api->amountTransfer($params,$format);
	}

	function createRetailer($params,$format){
		return $this->Api->createRetailer($params,$format);
	}

	function mobRecharge($params,$format){
	    return $this->Api->mobRecharge($params,$format);
	}

	function mobBillPayment($params,$format){
	    return $this->Api->mobBillPayment($params,$format);
	}

	function pay1Wallet($params,$format){
	    return $this->Api->pay1Wallet($params,$format);
	}

	function serviceCommission($params,$format){
	    return $this->Api->serviceCommission($params,$format);
	}

	function utilityBillFetch($params,$format){
	    return $this->Api->utilityBillFetch($params,$format);
	}

	function utilityBillInfo($params,$format){
	    return $this->Api->utilityBillInfo($params,$format);
	}

	function utilityBillPayment($params,$format){
		return $this->Api->utilityBillPayment($params,$format);
	}

	function dthRecharge($params,$format){
	    return $this->Api->dthRecharge($params,$format);
	}

	function vasRecharge($params,$format){
	     return $this->Api->vasRecharge($params,$format);
	}

	/*
	 * This function will be called after collection cash payment from pay1 outlet
	 */
	function cashpgPayment($params,$format){
	    return $this->Api->cashpgPayment($params,$format);
	}

	function getApps($mobile){

                $MsgTemplate = $this->General->LoadApiBalance();
		$sms = $MsgTemplate['GetApps_MSG'];

		//$data = $this->User->query("SELECT users.* FROM retailers,users WHERE retailers.user_id = users.id AND retailers.mobile = '".$mobile."'");

		/*if(!empty($data)){
			$sms .= "\nUsername: $mobile";
			$sms .= "\nPassword: " . $data['0']['users']['syspass'];
			$sms .= "\nIf password is not correct please call on 02261512288";
			}*/
		$this->General->sendMessage($mobile,$sms,'shops');
		$this->autoRender = false;
	}



	function getCommissions($params,$format){
		try{
            $_SESSION['Auth']['id'] = isset($_SESSION['Auth']['id']) ? $_SESSION['Auth']['id'] : "";
            $_SESSION['Auth']['User']['group_id'] = isset($_SESSION['Auth']['User']['group_id']) ? $_SESSION['Auth']['User']['group_id'] : "";
            $_SESSION['Auth']['slab_id'] = isset($_SESSION['Auth']['slab_id']) ? $_SESSION['Auth']['slab_id'] : "";
                if(isset($params['mobile']) && $params['mobile'] != $_SESSION['Auth']['mobile']){
                    return array('status'=>'failure','code'=>'61','description'=>$this->Shop->errors(61));
                }
		if(!is_numeric($params['service'])){
                    return array('status' => 'failure','code'=>'3','description'=>$this->Shop->errors(3));
                }
                if(isset($params['version'])){
		    $arr = $this->Shop->getAllCommissions_new($_SESSION['Auth']['User']['id'],$_SESSION['Auth']['User']['group_id'],$_SESSION['Auth']['slab_id'],$params['service']);
		    return array('status' => 'success','description' => $arr);

		}
		else {
		    $arr = $this->Shop->getAllCommissions($_SESSION['Auth']['id'],$_SESSION['Auth']['User']['group_id'],$_SESSION['Auth']['slab_id'],$params['service']);
		    return array('status' => 'success','description' => array($arr));

		}
               }catch(Exception $e){
			return array('status' => 'failure','code'=>'30','description'=>$this->Shop->errors(30));
		}
	}


	function updateMobile($params,$format){
		try{
			$msg = '';
			$group_id = $_SESSION['Auth']['User']['group_id'];
			if($group_id == MASTER_DISTRIBUTOR){ $msg .= 'Master distributor '; }
			if($group_id <= DISTRIBUTOR){ $msg .= 'Distributor '; }
			if($group_id <= RETAILER){ $msg .= 'Retailer '; }
			$msg .= $_SESSION['Auth']['name'].'('.$_SESSION['Auth']['mobile'].') wants to change his mobile number to '.$params['mobileNumber'];
			$this->General->sendMails('Mobile no. change request',$msg,array('customer.care@pay1.in'));
			return array('status' => 'success');
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','description'=>$this->Shop->errors(30));
		}
	}

	function writetous($params,$format){
		try{
			$sub = "Feedback: ".urldecode($params['sub']);
			$msg = '';
			$group_id = $_SESSION['Auth']['User']['group_id'];
			if($group_id == MASTER_DISTRIBUTOR){ $msg .= 'Master distributor: '; }
			if($group_id <= DISTRIBUTOR){ $msg .= 'Distributor: '; }
			if($group_id <= RETAILER){ $msg .= 'Retailer: '; }
			$msg .= $_SESSION['Auth']['name'].'('.$_SESSION['Auth']['mobile'].')<br/><br/>Message: '.nl2br(urldecode($params['bdy']));

			$this->General->sendMails($sub,$msg,array('customer.care@pay1.in'),'mail');
			return array('status' => 'success');
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','description'=>$this->Shop->errors(30));
		}
	}

	function sessionCheck(){
		return array('status' => 'success');
	}

	function updateBal($params,$format){
		try{
            $_SESSION['Auth']['id'] = isset($_SESSION['Auth']['id']) ? $_SESSION['Auth']['id'] : "";
			$balance = $this->Shop->getBalance($_SESSION['Auth']['User']['id']);
			//$data = $this->User->query("SELECT balance FROM retailers WHERE user_id = '".$_SESSION['Auth']['user_id']."'");
			//if(isset($data['0']['retailers']['balance']))
			if(!empty($balance))
			    return array('status' => 'success','login'=>1,'description'=>$balance,'token'=>$this->Session->id(),'id'=>$this->Session->read('Auth.User.id'),'shopname'=>$this->Session->read('Auth.shopname'));
			else
			    return array('status' => 'success','login'=>0,'description'=>'0','token'=>$this->Session->id(),'id'=>$this->Session->read('Auth.User.id'),'shopname'=>$this->Session->read('Auth.shopname'));
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','description'=>$this->Shop->errors(30));
		}
	}

	function updatePin($params,$format){
		$userId = $_SESSION['Auth']['user_id'];
		$oldPassword = $this->Auth->password($params['oldPin']);
		$newPassword = $this->Auth->password($params['newPin']);
                
                if(!is_numeric($userId)){
			return array('status' => 'failure','code'=>'3','description' =>$this->Shop->errors(3));
		}
		$data = $this->User->query("SELECT mobile FROM users WHERE id = '".$userId."' AND password='$oldPassword' AND active_flag = 1");
		if(empty($data)){
			return array('status' => 'failure','code'=>'32','description' =>$this->Shop->errors(32));
		}
		if(!$this->Shop->isStrongPassword($params['newPin'])):
                                            return array('status' => 'failure','code'=>'55','description' =>$this->Shop->errors(55));
                endif;

		try{
			//$this->User->query("update users set password = '".$newPassword."',syspass='".$params['newPin']."' WHERE id = '".$userId."'");

                        App::import('Controller', 'Users');
                        $ini = new UsersController;
                        $ini->constructClasses();
                        $ini->updatePassword($data['0']['users']['mobile'], $params['newPin'], "change", "updatePass");

                        session_destroy();

                        $MsgTemplate = $this->General->LoadApiBalance();
                        $sms_msg = $MsgTemplate['App_PinUpdated_MSG'];
                        $this->General->sendMessage($data['0']['users']['mobile'],$sms_msg,'shops');

//			$this->General->sendMessage($data['0']['users']['mobile'],'Your Pay1 App Pin Updated successfully. If you have not updated your pin, send SMS: PAY1 HELP to 09004350350','shops');
			return array('status' => 'success','description'=>'Pin Updated successfully. You will have to login again.');
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function topupRequest($params,$format){
		$userId = $_SESSION['Auth']['user_id'];
		$topupAmt = $params['topupAmt'];
		$topupType = $params['topupType'];
		$groupId = 	$_SESSION['Auth']['User']['group_id'];
		try{
			if($group_id <= RETAILER){ $msg = 'Retailer '; }
			$msg .= $_SESSION['Auth']['name']."(".$_SESSION['Auth']['mobile'].") wants to top-up his account by Rs. $topupAmt";
			$this->General->sendMails('Top-up request',$msg,array('limits@pay1.in'));

			$this->User->query("insert into topup_request(user_id,type,amount,created) values ('".$userId."','".$topupType."','".$topupAmt."','".date('Y-m-d H:i:s')."')");
			return array('status' => 'success','description'=>'TopUp request sent successfully');
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function getTopupRequest($params,$format){
		try{
			$page = $params['page'];
			$limit = PAGE_LIMIT*($page-1);
			$data = $this->Slaves->query("SELECT * FROM topup_request WHERE user_id = '".$_SESSION['Auth']['user_id']."' order by id desc LIMIT $limit," . PAGE_LIMIT);
			return array('status' => 'success','description'=>array($data), 'count' => $page+1);
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function ledgerBalance($params,$format){
		/*if ($this->General->dateValidate($params['date']) == false) {
				return array('status' => 'failure', 'description' => 'Something went wrong!!!');
		}*/

		try{
			App::import('Controller', 'Shops');
			$obj = new ShopsController;
			$obj->constructClasses();
			$ret = $obj->accountHistory($params);
			$i = 0;
                        Configure::load('billers');
                        $billers = Configure::read('billers');
			foreach($ret['transactions'] as $r){
                            $ret['transactions'][$i]['transactions']['credit'] = round($r['transactions']['credit'],2);
                            $ret['transactions'][$i]['transactions']['debit']  = round($r['transactions']['debit'],2);
			    $ret['transactions'][$i]['opening_closing']['opening'] = round($r['transactions']['opening'],2);
			    $ret['transactions'][$i]['opening_closing']['closing'] = round($r['transactions']['closing'],2);
                            $ret['transactions'][$i]['transactions']['logo_url'] = $billers[$r['transactions']['refid']]['logo_url'];
			    $i++;
			}
			//$this->printArray($ret);
			return array('status' => 'success','description' => array($ret));
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function updatePAN($params,$format){

	    $this->General->sendMails('PAN number update', json_encode($params), array('ashish@pay1.in'), 'mail');
	    $name_structure = explode(' ', $params['name']);
	    $name_initial = $name_structure[count($name_structure) - 1][0];
	    $pan = $params['pan'];

	    $status = (strlen($pan) != 10 || !ctype_alpha($pan[0].$pan[1].$pan[2].$pan[3].$pan[4].$pan[9]) || !in_array($pan[3], array('C','P','H','F','A','T','B','L','J','G')) || $name_initial != $pan[4] || !is_numeric($pan[5].$pan[6].$pan[7].$pan[8])) ? 'Failure' : 'Success';

	    return array('status' => $status);
	}


	function getBalance($params,$format){
		$balance = $this->Shop->getBalance($_SESSION['Auth']['User']['id']);

		//$shop = $this->Shop->getShopDataById($_SESSION['Auth']['id'],$_SESSION['Auth']['User']['group_id']);
		return array('status' => 'success','balance' => $balance);
	}

	function saleReport($params,$format){
		try{
			App::import('Controller', 'Shops');
			$obj = new ShopsController;
			$obj->constructClasses();
			//params should have date & service as params

			$ret = $obj->saleReport($params);
			return array('status' => 'success','description' => array($ret));
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function earnings($params,$format){

		try{
			$ret = $this->Shop->earnings($params);
			return array('status' => 'success','description' => array($ret[0]),'today' => array($ret[1]),'prevWeek' => array($ret[2]), 'nextWeek' => array($ret[3]), 'currWeek' => array($ret[4]));
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function topups($params,$format){
		try{
			$ret = $this->Shop->topups($params);
			return array('status' => 'success','description' => array($ret[0]),'prevWeek' => array($ret[1]), 'nextWeek' => array($ret[2]), 'currWeek' => array($ret[3]));
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function lastTransactions($params,$format){
		try{
			if(!isset($params['date'])) $date = '';
			else $date = $params['date'];

			$pageNo = empty($params['page_no'])?0:$params['page_no'];
			$itemsPerPage = empty($params['items_per_page'])?0:$params['items_per_page'];
			$date2 = empty($params['date2'])?'':$params['date2'];

			if ($this->General->dateValidate($date) == false || $this->General->dateValidate($date2) == false) {
				return array('status' => 'failure', 'description' => 'Something went wrong!!!');
			}

			$service = $params['service'];
			$page = $params['page'];
                        $is_page_wise = isset($params['is_page_wise']) ? $params['is_page_wise'] : 1;
			$ret = $this->Shop->getLastTransactions($date,$page,$service,$date2,$itemsPerPage,null,0,$is_page_wise);
			return array('status' => 'success','description' => array($ret['ret']), 'count' => $page+1,'today' => array($ret['today']),'prev' => array($ret['prev']),'next' => array($ret['next']),'more' => $ret['more']);
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}
	function getInvoiceList($params,$format){
		try{
			$pageNo = empty($params['page_no'])?0:$params['page_no'];
			$itemsPerPage = empty($params['items_per_page'])?0:$params['items_per_page'];

            $year = !empty($params['year']) ? $params['year'] : null;
			$month = !empty($params['month']) ? $params['month'] : null;
			$invoice_type = !empty($params['invoice_type']) ? $params['invoice_type'] : null;

			$page = $params['page'];
            $is_page_wise = isset($params['is_page_wise']) ? $params['is_page_wise'] : 1;

            // $_SESSION['Auth']['User']['id'] = 40649519;

            $invoice_list =  $this->Invoice->getInvoiceList($_SESSION['Auth']['User']['id'],$month,$year,$invoice_type);
			return array('status' => 'success','description' => $invoice_list, 'count' => $page+1,'today' => '','prev' => '','next' => '','more' => '');
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}
	function getInvoiceData($params,$format){
		try{
			$pageNo = empty($params['page_no'])?0:$params['page_no'];
			$itemsPerPage = empty($params['items_per_page'])?0:$params['items_per_page'];

            $year = !empty($params['year']) ? $params['year'] : null;
			$month = !empty($params['month']) ? $params['month'] : null;
			$type = !empty($params['type']) ? $params['type'] : null;//0-> view
			$invoice_id = !empty($params['invoice_id']) ? $params['invoice_id'] : null;

			$page = $params['page'];
            $is_page_wise = isset($params['is_page_wise']) ? $params['is_page_wise'] : 1;

            // $_SESSION['Auth']['User']['id'] = 40649519;
            $invoice_data = $this->Invoice->getInvoiceData($_SESSION['Auth']['User']['id'],$invoice_id,$month,$year);
            $response = $this->Invoice->generatePdf($invoice_data,$type);
            return array('status' => 'success','description' => $response);

		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}
	function lastten($params,$format){
		try{
			$service = $params['service'];
			$ret = $this->Shop->lastten($service);
			return array('status' => 'success','description' => array($ret['ret']));
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function pg($params,$format){
		try{
			$amount = trim($params['amount']);
			if($this->General->priceValidate($amount) == ''){//amount validation
			return array('status'=>'failure','code'=>'6','description'=>$this->Shop->errors(6));
		    }
			$via = trim($params['device_type']);
			return $this->Shop->payment_gateway($amount,$via);

		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function payu_status($status = 'success') {
        $response_data = $_POST;

        $logger = $this->General->dumpLog('Payu', 'receivePayuStatus');
        $logger->info("Payu return : " . json_encode($response_data));

        //if($status != 'success')$response_data['status'] = 'failure';
        $res = json_encode($this->Shop->update_pg_payu($response_data));
        $logger->info("return : " . $res);
        $mem_data = $this->Shop->getMemcache("pg_" . $response_data['txnid']);

        $transData = $this->Retailer->query("SELECT shop_transactions.amount,shop_transactions.source_id,shop_transactions.target_id,
        		retailers.shopname,retailers.mobile,users.balance, retailers.rental_flag
        		FROM shop_transactions
        		left join retailers ON (retailers.id = shop_transactions.target_id)
                        Inner Join users ON (users.id = retailers.user_id)
        		WHERE shop_transactions.id =" . $response_data['txnid']);



        /** IMP DATA ADDED : START**/
        $ret_mobiles = array_map(function($element){
            return $element['retailers']['mobile'];
        },$transData);

        $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);

        $retailer_imp_label_map = array(
            'pan_number' => 'pan_no',
            'shopname' => 'shop_est_name',
            'alternate_number' => 'alternate_mobile_no',
            'email' => 'email_id',
            'shop_structure' => 'shop_ownership',
            'shop_type' => 'business_nature'
        );
        foreach ($transData as $key => $value) {
            foreach ($value['retailers'] as $retailer_label_key => $value) {
                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                    $transData[$key]['retailers'][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
                }
            }
        }
        /** IMP DATA ADDED : END**/


        if ($mem_data == 'web') {
            $this->set('status', $status);
            $this->set('transData', $transData);
            $this->set('response_data', $response_data);

            $this->render('/elements/payu_response', "");
        } else {
            echo $response_data['status'];
            $this->autoRender = false;
        }

        if($status == 'success' || $response_data['status'] == "success"){
        	if($transData[0]['retailers']['rental_flag'] == 2){
        		$this->Retailer->query("update retailers
        				set rental_flag = 1, modified = '".date('Y-m-d H:i:s')."'
        				where mobile = '".$transData[0]['retailers']['mobile']."'");
        	}
        }

    }

	function mobileTransactions($params,$format){
                try{
			if(!isset($params['service'])) $service = '';
			else $service = $params['service'];

			$ret = $this->Shop->mobileTransactions($params['mobile'],$service);
			return array('status' => 'success','description' => array($ret['ret']));
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function reversalTransactions($params,$format){
		try{
			if(!isset($params['date'])) $date = '';
			else $date = $params['date'];

			$ret = $this->Shop->reversalTransactions($date,$params['service']);
			return array('status' => 'success','description' => array($ret['ret']),'today' => array($ret['today']),'prev' => array($ret['prev']),'next' => array($ret['next']));
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function getVASProducts($params,$format){
		try{
			$data = $this->Slaves->query("SELECT prods.* FROM products,products_info as prods WHERE prods.product_id = products.id AND products.service_id = 3 AND products.active = 1");
			$k = 0;
			foreach($data as $d){
				$data[$k]['prods']['params'] = json_decode(stripslashes($d['prods']['params']),true);
				$k++;
			}
			return array('status' => 'success','description'=>$data);
		}catch(Exception $e){
			return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
		}
	}

	function mobileRechargeReq(){
		/*$example = array('a' => 'apple', 'b' => 'banana');
		 $postfields = http_build_query($example);
		 echo $postfields; exit;
		 */
		$msg= urlencode('acvve 9833140202 10');
		$params = 'method=mobRecharge&mobileNumber=9892471157&operator=8&subId=9892471157&amount=10&circle=&type=flexi';
		//$params = "method=updateMobile&mobileNumber=9892471157";
		$url = 'http://www.dshops.com/apis/receiveWeb';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		$str = trim(curl_exec($ch));

		echo $str;

		$this->autoRender = false;
	}

	function repeatedTrans(){
		$msg = urldecode($_REQUEST['message']);
		$sender = urldecode($_REQUEST['mobile']);
		$type = $_REQUEST['type'];
		$this->Shop->addRepeatTransaction($msg,$sender,$type);
		$this->autoRender = false;
	}


	function dropped(){
		$type = $_REQUEST['type'];
		$msg = $_REQUEST['msg'];
		$sender = $_REQUEST['sender'];
		$time = $_REQUEST['time'];
                $MsgTemplate = $this->General->LoadApiBalance();
		if($type == 'late'){
			$time1 = date('Y-m-d H:i:s',strtotime('- ' . TIME_DURATION . ' minutes'));
			if($time1 < $time){
				$data = $this->User->query("SELECT timestamp FROM virtual_number WHERE mobile = '$sender' AND message = '".addslashes($msg)."' AND date = '".date('Y-m-d')."' ORDER BY id desc LIMIT 1");

				if(empty($data) || $data['0']['virtual_number']['timestamp'] < $time1){
//					$sms = "Dropped: Your request '$msg' is dropped due to late sms delivery. Please try again";

                                    $sms = $this->General->ReplaceWord('<MSG>',$msg,$MsgTemplate['Dropped_DueToLate_MSG']);

				}
			}
		}
		else if($type == 'repeat'){
//			$sms = "Duplicate: Your request $msg already received";

                        $sms = $this->General->ReplaceWord('<MSG>',$sms,$MsgTemplate['Dropped_Duplicate_MSG']);
			if(strpos($msg,'*') == 0){
				$params = explode("*",$msg);
				$retailer = $this->User->query("SELECT * FROM retailers WHERE mobile = '$sender'");

				if(count($params) == 4){
					$data = $this->User->query("SELECT * FROM vendors_activations WHERE product_id = " . $params[1] . " AND mobile = '". $params[2] ."' AND amount = " . $params[3]);
				}
				else if(count($params) == 5){
					$data = $this->User->query("SELECT * FROM vendors_activations WHERE product_id = " . $params[1] . " AND param = '". $params[2] ."' AND amount = " . $params[4]);
				}
				if(!empty($retailer) && !empty($data)){
//                                        $sms = "Duplicate: Your request $msg already received";
//					$sms .= "\nTo know your transaction status give a misscall on 02267242287";

                                        $sms = $this->General->ReplaceWord('<MSG>',$msg,$MsgTemplate['Dropped_DuplicateStatus_MSG']);
				}
				else {
					$sms = "";
				}
			}
			else if(strpos($msg,'*') !== false){
				$sms = "";
			}
		}

		$sms = "";

		if(!empty($sms)){
			$this->General->sendMessage($sender,$sms,'notify');
		}
		$this->autoRender = false;
	}

	function shiftSlab($type,$id,$slab){
		if($this->Session->read('Auth.User.group_id') != MASTER_DISTRIBUTOR)$this->redirect('/shops/view');

		$data = $this->Slaves->query("SELECT * FROM slabs WHERE id = $slab");
		if(empty($data)){
			echo "Slab not found";
			exit;
		}

		if($type == 'd'){
            $data = $this->Slaves->query("SELECT * FROM distributors WHERE id = $id");

            /** IMP DATA ADDED : START**/
            $temp = $this->Shop->getUserLabelData($id,2,3);
            $imp_data = $temp[$id];
            $data['0']['distributors']['company'] = $imp_data['imp']['shop_est_name'];
            /** IMP DATA ADDED : END**/

			if(empty($data)){
				echo "Distributor not found";
				exit;
			}
			$old_slab = $data['0']['distributors']['slab_id'];
			if($old_slab == $slab){
				echo "Distributor " . $data['0']['distributors']['company'] . " is already in slab $slab";
			}
			else {
				$this->User->query("UPDATE distributors SET slab_id = $slab WHERE id = $id");
				$this->Shop->updateSlab($slab,$id,DISTRIBUTOR);
				echo "Slab changed of Distributor " . $data['0']['distributors']['company'] . " from $old_slab to $slab";

				$data = $this->Slaves->query("SELECT * FROM retailers WHERE parent_id = $id");
				foreach($data as $dt){
					$old_slab = $dt['retailers']['slab_id'];
					if($old_slab == $slab){
						echo "Retailer " . $dt['retailers']['mobile'] . " is already in slab $slab";
					}
					else {
						$this->User->query("UPDATE retailers SET slab_id = $slab, modified = '".date('Y-m-d H:i:s')."' WHERE id = ". $dt['retailers']['id']);
						$this->Shop->updateSlab($slab,$dt['retailers']['id'],RETAILER);
						echo "Slab changed of Retailer " . $dt['retailers']['id'] . " from $old_slab to $slab";
					}
				}
			}
		}
		else if($type == "r"){
			$data = $this->Slaves->query("SELECT * FROM retailers WHERE id = $id");
			if(empty($data)){
				echo "Retailer not found";
				exit;
			}
			$old_slab = $data['0']['retailers']['slab_id'];
			if($old_slab == $slab){
				echo "Retailer " . $data['0']['retailers']['mobile'] . " is already in slab $slab";
			}
			else {
				$this->User->query("UPDATE retailers SET slab_id = $slab, modified = '".date('Y-m-d H:i:s')."' WHERE id = $id");
				$this->Shop->updateSlab($slab,$id,RETAILER);
				echo "Slab changed of Retailer " . $data['0']['retailers']['mobile'] . " from $old_slab to $slab";
			}
		}

		$this->autoRender = false;
	}

	function downloadApp($fname){
		$info = pathinfo($fname);
		$ext = $info['extension'];

		if($ext == 'apk'){
			$mimeType = "application/vnd.android.package-archive";
		}
		else if($ext == 'jar'){
			$mimeType = "application/java-archive";
		}
		else if($ext == 'jad'){
			$mimeType = "text/vnd.sun.j2me.app-descriptor";
		}
		else {
			$mimeType = "application/force-download";
		}

		$file_path = $_SERVER['DOCUMENT_ROOT'].'/apps/'.$fname;

		if (!is_file($file_path)) {
			die("File does not exist. Make sure you specified correct file name.");
		}
		$fsize = filesize($file_path);

		// set headers
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Type: $mimeType");
		header("Content-Disposition: attachment; filename=\"$fname\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . $fsize);

		// download
		// @readfile($file_path);
		$file = @fopen($file_path,"rb");
		if ($file) {
			while(!feof($file)) {
				print(fread($file, 1024*8));
				flush();
				if (connection_status()!=0) {
					@fclose($file);
					die();
				}
			}
			@fclose($file);
		}
		//download will start

		$this->autoRender = false;
	}

	function test(){
		echo "1"; exit;
		$next = $this->Shop->getNextVendor(4,'302105340706');
		$this->printArray($next);
		$this->autoRender = false;
	}
	function getVMNList($type="nos"){
		$this->autoRender = false;
		$v = $this->Shop->getVMNList();
		$response = $v;
                if($type == "nos"){
			$response = array();
			foreach ($v as $key => $value) {

				array_push($response,$value["no"]);
			}
		}

		return json_encode($response);
	}
	//function getMobileDetails($mobileNo){
        function updateRetailerAddress($params, $format){
            //$this->autoRender = false;
            $mobileNo = $params["mobile"];
            $response = array();
            if( empty($mobileNo) || strlen($mobileNo) <  10){
                $response = array(
                    "status"=>"failure",
                    "error"=>"Invalid mobile no ."
                );
            }else{
                //$userObj = ClassRegistry::init('User');
                //$retInfo = $this->User->query("SELECT * FROM `retailers` WHERE `mobile` LIKE '$mobileNo'");
                if(empty($_SESSION['Auth']['id'])){
                //if(empty($retInfo[0]['retailers']['id'])){
                    $response = array(
                        "status"=>"failure",
                        "error"=>"Retailer mobile no does not exist ."
                    );
                }else{
                    //$oprData = $this->General->updateRetailerAddress($retInfo[0]['retailers']['id'],$retInfo[0]['retailers']['user_id'],$params);
                    $oprData = $this->General->updateRetailerAddress($_SESSION['Auth']['id'],$_SESSION['Auth']['User']['id'],$params);
                    $response = array(
                        "status"=>$oprData["status"],
                        "description"=>$oprData
                    );
                }

            }
            return ($response);//json_encode
        }

        //function to insert leads
        function addLeads($params, $format){
            //$this->autoRender = false;
            $full_name = $params["full_name"];
            $email = $params["email"];
            $state = $params["state"];
            $city = $params["city"];
            $comment = $params["comment"];
            $req_by = $params["req_by"];
            $contact_no = $params["contact_no"];
            $pin_code = $params["pin_code"];
            $shop_name = $params["firm_name"];
            $interest = "";

            $response = array();
            if(false){
                $response = array(
                    "status"=>"failure",
                    "error"=>"Invalid Data ."
                );
            }else{

                $lead = $this->User->query("INSERT INTO `shops`.`leads`
                    (`id`, `name`, `email`, `state`, `city`, `fax`, `messages`, `pin_code`, `shop_name`, `phone`,`date`, `timestamp`, `req_by`) VALUES
                    (NULL, '$full_name', '$email', '$state', '$city', NULL, '$comment', '$pin_code', '$shop_name', '$contact_no', '".date("Y-m-d")."','".Date("Y-m-d H:i:s")."', '$req_by');");


                $subject = "Pay1 Retailer Merchant Request - from $full_name ($req_by)";

                $body = "
                </br> From          : $full_name
                </br> Email-ID      : $email
                </br> Contact       : $contact_no
                </br> State         : $state
                </br> City          : $city
                </br> Interested In : $interest
                </br> Source        : $req_by
                </br> Comment       : $comment";

                $this->General->sendMails($subject,$body,array('sales@pay1.in','info@pay1.in'),'mail');

                $response = array(
                    "status"=>"success",
                    "description"=>"Lead updated successfully."
                );
            }
            return ($response);//json_encode
        }

        function ccNotResponding($params, $format){

        	$user  = $params['user'];
        	$supportId  = $params['support_id'];
        	$time  = $params['time'];
        	$device_type  = $params['device_type'];
        	$subject = "No response on chat for retailer $user";
        	$body = "From : $user<br/>
                   Support-ID : $supportId<br/>
                   Time       : $time.<br/>
                   Device Type: $device_type";

        	if(!empty($supportId)){
        		$data = $this->Shop->getMemcache("chat_$user");
        		if($data === false){
        			$this->General->sendMails($subject,$body,array('customer.care@pay1.in','dharmesh.chauhan@pay1.in','tadka@pay1.in'),'mail');
        			$this->Shop->setMemcache("chat_$user",1,5*60);
        		}
        	}

        	return 'success';
        }

        //function getPlanDetails($operator , $circle , $mobile=null){
        function getPlanDetails($params, $format){
        	//$this->autoRender = false;
//            ini_set("memory_limit", "-1");
        	$operator = isset($params["operator"]) ? $params["operator"] : "";
        	$circle   = isset($params["circle"]) ? $params["circle"] : "";
        	$mobile   = isset($params["mobile"]) ? $params["mobile"] : "";
                $lastUpdateTimeFlag = isset($params["lastUpdateTimeFlag"]) ? $params["lastUpdateTimeFlag"] : 0;

                if((!empty($operator) && !is_numeric($operator)) || (!empty($mobile) && $this->General->mobileValidate($mobile) == 1)){
                    return array('status' => 'failure','code'=>'5','description' => $this->Shop->errors(5));
                } else if(!ctype_alnum($params['circle']) && !empty($circle)){
                    return array('status' => 'failure','code'=>'3','description' => 'Invalid Circle');
                }
                
        	if(!empty($operator) && !empty($circle)){
        		//
        	}else if(!empty ($mobile)){
        		$n4 = substr($mobile, 0, 5);
                        $oprCircle = $this->General->getMobileDetailsNew($mobile);

        		/*$oprCircle = $this->User->query("SELECT opr_code , product_id ,area
        		 FROM `mobile_numbering`,`mobile_numbering_service` where mobile_numbering.operator = mobile_numbering_service.opr_code  AND  number =  $n4");*/
        		if( !empty($oprCircle) ){
        			$operator = $oprCircle['product_id'] ;
        			$circle = $oprCircle['area'] ;
        			$opName =  $oprCircle['opr_name'] ;
        			$arName =  $oprCircle['area_name'] ;
        		}else{
        			$operator = 0;
        			$circle = 0;
        			$opName =  '';
        			$arName = '';
        		}
        	}

        	$det = false;
        	if($circle == "") $circle = "all";

        	if(!isset($params['timestamp']) || empty($params['timestamp']))
        	$det = $this->Shop->getMemcache("plans_".$operator."_".$circle);

        	if($det === false){
        		$qry = "AND show_flag =1";
				if(isset($params['timestamp']))$qry = " AND updated >= '". $params['timestamp']."'";

				if($operator=='all' && $circle=='all'){
        			$plans = $this->Slaves->query("SELECT *
	                                                    FROM `circle_plans` WHERE 1 $qry order by updated, opr_name , c_name, plan_amt ");
        		}else if($circle=='all'){
        			$plans = $this->Slaves->query("SELECT *
	                                                    FROM `circle_plans`
	                                                    WHERE `prod_code_pay1` =$operator $qry order by opr_name , c_name, plan_amt");
        		}else if($operator=='all'){
        			$plans = $this->Slaves->query("SELECT *
	                                                    FROM `circle_plans`
	                                                    WHERE `c_code_pay1` LIKE '$circle' $qry order by opr_name , c_name, plan_amt");
        		}else{
        			$plans = $this->Slaves->query("SELECT *
	                                                    FROM `circle_plans`
	                                                    WHERE `c_code_pay1` LIKE '$circle'
	                                                    AND `prod_code_pay1` =$operator $qry order by plan_amt, opr_name , c_name");
        		}

        		$response = array();
        		if(count($plans) > 0){
        			$det = array();
        			foreach ($plans as $key => $arr) {

        				$det[$arr['circle_plans']['prod_code_pay1']]['prod_code_pay1'] = $arr['circle_plans']['prod_code_pay1'];
        				$det[$arr['circle_plans']['prod_code_pay1']]['opr_name'] = $arr['circle_plans']['opr_name'];
        				if(empty($det[$arr['circle_plans']['prod_code_pay1']]['circles'])){
        					$det[$arr['circle_plans']['prod_code_pay1']]['circles'] = array();
        				}
        				$det[$arr['circle_plans']['prod_code_pay1']]['circles'][$arr['circle_plans']['c_code_pay1']]["circle_id"]=$arr['circle_plans']['c_code_pay1'];
        				$det[$arr['circle_plans']['prod_code_pay1']]['circles'][$arr['circle_plans']['c_code_pay1']]["circle_name"] = $arr['circle_plans']['c_name'];

        				if(empty($det[$arr['circle_plans']['prod_code_pay1']]['circles'][$arr['circle_plans']['c_code_pay1']]["plans"][$arr['circle_plans']['plan_type']])){
        					$det[$arr['circle_plans']['prod_code_pay1']]['circles'][$arr['circle_plans']['c_code_pay1']]["plans"][$arr['circle_plans']['plan_type']]= array();
        				}
        				array_push($det[$arr['circle_plans']['prod_code_pay1']]['circles'][$arr['circle_plans']['c_code_pay1']]["plans"][$arr['circle_plans']['plan_type']],array(
	                            	"plan_amt" => 	$arr['circle_plans']['plan_amt'],
	                                "plan_validity" =>$arr['circle_plans']['plan_validity'],
	                                "plan_desc" =>$arr['circle_plans']['plan_desc'],
        							"show_flag" => $arr['circle_plans']['show_flag']
        				));
        			}
        		}

        		if(!isset($params['timestamp'])  || empty($params['timestamp']))$this->Shop->setMemcache("plans_".$operator."_".$circle,$det,3*60*60);
        	}


        	if(empty($det)){
        		$det = '{"'.$operator.'":{"prod_code_pay1":"'.$operator.'","opr_name":"'.(isset($opName)?$opName:"").'","circles":{"'.$circle.'":{"circle_id":"'.$circle.'","circle_name":"'.(isset($arName)?$arName:"").'","plans":{"None":[{"plan_amt":"0","plan_validity":"0","plan_desc":"No plans found ."}]}}}}}';
        		$det = json_decode($det,true);
        	}

        	if($lastUpdateTimeFlag == 1)
        		$det = array('last_update_time' => round(microtime(true) * 1000), 'plans' => $det);

        	return $det;

        }

        //function getMobileDetails($mobileNo){
 		function getMobileDetails($params, $format){
            //$this->autoRender = false;
            $mobileNo = $params["mobile"];
            $lastUpdateTimeFlag = isset($params["lastUpdateTimeFlag"]) ? $params["lastUpdateTimeFlag"] : 0;

            $response = array();
            if( empty($mobileNo)){
                $response = array(
                    "status"=>"failure",
                    "error"=>"Wrong mobile no ."
                );
            }else{

            	if(strtolower($mobileNo) == 'all'){
            		$updated = "1";
            		if(!empty($params['timestamp'])){
            			$updated = "mn.updated >= '".$params['timestamp']."'";
            		}
            		if(isset($params['mobile_code_digits']) && $params['mobile_code_digits'] == "5"){
            			$query = "select mn.number, mna.area_name, mns.opr_name, mns.product_id, mn.area, mns.opr_code
            				from mobile_operator_area_map AS mn
            				LEFT JOIN mobile_numbering_area as mna ON mn.area = mna.area_code
            				LEFT JOIN mobile_numbering_service AS mns ON mn.operator = mns.opr_code WHERE $updated";
            		}
            		else {
            			$query = "select mn.number, mna.area_name, mns.opr_name, mns.product_id, mn.area, mns.opr_code
	            			from mobile_numbering AS mn
	            			LEFT JOIN mobile_numbering_area as mna ON mn.area = mna.area_code
	            			LEFT JOIN mobile_numbering_service AS mns ON mn.operator = mns.opr_code WHERE $updated";
            		}
        			$data = $this->Slaves->query($query);
        			$oprData = array();

        			foreach($data as $dt){
        				$ret_arr = array('area_name'=>$dt['mna']['area_name'],'area'=>$dt['mn']['area'], 'opr_name'=>$dt['mns']['opr_name'], 'operator'=>$dt['mns']['opr_code'],'product_id'=>$dt['mns']['product_id'],'number'=>$dt['mn']['number']);
        				$oprData[] = $ret_arr;
        			}
            	}
            	else {
	                if(strlen($mobileNo) <  10){
	                    $mobileNo = str_pad($mobileNo, 10, "1");
	                }

	                if(isset($params['mobile_code_digits']) && $params['mobile_code_digits'] == "5"){
	                	$oprData = $this->General->getMobileDetailsNew($mobileNo);
	                }
	                else {
	                	$oprData = $this->General->getMobileDetails($mobileNo);
	                }

            	}
            	$response = array(
	                    "status"=>"success",
	                    "details"=>$oprData
	                );
            	if($lastUpdateTimeFlag == 1)
            		$response['last_update_time'] = round(microtime(true) * 1000);
            }
            return ($response);//json_encode
        }

        function getSessionVar(){
             print_r($_SESSION);
         }

        public function allsources($userId,$password){
            //$auth = check_user($userId,$password);
            $auth = '1';
            if($auth){
                $logger = $this->General->dumpLog('Search Request', 'SourceSearch');
                $sql = "select sources,vsrc_id  from transp_sources where status='1' order by sources asc";
                $rs = mysql_query($sql);
                $count = mysql_num_rows($rs);
                //$logger->info("Available sources count |count=".$count);
                $sources['allsources'] = array();
                if($count){
                    $i = 0;
                    while($arr = mysql_fetch_object($rs)){
                        $sources['allsources'][$i]['sources'] = $arr->sources;
                        $sources['allsources'][$i]['vsrc_id'] = $arr->vsrc_id;
                        $i++;
                    }

                    echo json_encode($sources);

                }else{
                    echo "NA";
                }
            }else{
                echo "NA1";
            }
        }
        function pullNotifications($params,$format){
            $mobile = $_SESSION['Auth']['User']['mobile'];
            $notifications = $this->Slaves->query("SELECT * FROM `notificationlog` WHERE mobile = '$mobile' AND `received` = 0 AND user_type = '".$params["device_type"]."' AND NOT isnull( `msg_id` )");
            $response = array("notifications"=>array());

            foreach ($notifications as $key => $notification) {
                $temp = array(
                    "id" => $notification['notificationlog']['msg_id'],
                    "msg" => $notification['notificationlog']['msg'],
                    "created" => $notification['notificationlog']['created']

                );
                array_push($response["notifications"], $temp);
            }
            $this->User->query("Update `notificationlog` Set `received` = 1 WHERE mobile = '$mobile' AND `received` = 0");
            return $response;
        }
function getNearByRetailer($params) {




        $this->autoRender = false;
            if(isset($params['mobile']) && (empty($params['lat']) || empty($params['lng'])))
            {
            	$lat_long = $this->Slaves->query("SELECT user_profile.latitude,user_profile.longitude,
            				mobile_numbering_area.latitude,mobile_numbering_area.longitude
            			FROM vendors_activations
            			left join retailers ON (retailers.id = vendors_activations.retailer_id)
            			left join user_profile ON (retailers.user_id = user_profile.user_id)
            			left join mobile_operator_area_map ON (number = substr(vendors_activations.mobile,1,5))
            			left join mobile_numbering_area ON (mobile_numbering_area.area_code=mobile_numbering.area)
            			WHERE vendors_activations.mobile = '".$params['mobile']."'
            			AND  user_profile.longitude != 0 AND  user_profile.latitude != 0
            			ORDER BY user_profile.updated DESC
            			LIMIT 1");
				if(!empty($lat_long)){
					if(!empty($lat_long['0']['user_profile']['latitude'])){
						$params['lat'] = $lat_long[0]['user_profile']['latitude'];
						$params['lng'] = $lat_long[0]['user_profile']['longitude'];
					}
					else if(!empty($lat_long['0']['mobile_numbering_area']['latitude'])){
						$params['lat'] = $lat_long[0]['mobile_numbering_area']['latitude'];
						$params['lng'] = $lat_long[0]['mobile_numbering_area']['longitude'];
					}
				}
            }

            $lat = deg2rad(floatval($params['lat']));//deg2rad(19.1850214);
            $lng = deg2rad(floatval($params['lng']));//deg2rad(72.8320166);
            $limit = isset($params['limit']) ? $params['limit'] : "";//deg2rad(72.8320166);
            if(empty($limit))$limit = 50;

            if(isset($params['distance'])) {
            	$distance = $params['distance'];
            	$limit = 10;
            	$x = $limit/$distance;
            }


            if($limit == -1){
            	$limit = "";
            }
            else {
            	$limit = "limit $limit";
            }

            $R = 6371;
            $rad = 1;
            //$serverPath = "/uploads/";
            $serverPath = DISTPANEL_URL."uploads/";

            if(isset($params['distance'])){
          		$distance = $params['distance'];

          		/*$str = "SELECT * from (select * from (
Select retailers.shopname , retailers_logs.sale, up.latitude,  up.longitude , retailers.user_id,retailers.address,retailers.pin,locator_area.name as area_name,locator_city.name as city_name,locator_state.name as state_name,
                        acos(sin($lat)*sin(radians(up.latitude)) + cos($lat)*cos(radians(up.latitude))*cos(radians(up.longitude)-$lng)) * $R As D,retailers_details.image_name as imagepath
                    From
                        user_profile as up
                        LEFT JOIN retailers ON ( retailers.user_id = up.user_id )
                        LEFT JOIN retailers_details ON ( retailers.id = retailers_details.retailer_id AND retailers_details.type='image')
                        LEFT JOIN locator_area ON ( locator_area.id = retailers.area_id )
                        LEFT JOIN locator_city ON ( locator_area.city_id = locator_city.id )
                        LEFT JOIN locator_state ON ( locator_city.state_id = locator_state.id )
                        LEFT JOIN retailers_logs ON ( retailers.id = retailers_logs.retailer_id AND date = '".date('Y-m-d',strtotime('-2 days'))."' )
                    Where
                        retailers_logs.sale >= 200 AND retailers.address != '' AND retailers.area_id != 0 AND ! isnull(retailers.address) AND retailers.kyc_flag!= 1 AND up.latitude != '' AND up.longitude != '' AND up.latitude != 0 AND up.longitude != 0  AND (up.device_type = 'online')
                    Order By case when up.device_type = 'online' then 1 else 2 end, up.updated desc
) as v WHERE v.D < $distance group by v.user_id order by v.sale desc) as t group by floor(t.D*$x) $limit";*/

//          		$str = "SELECT * from (select * from (
//Select 'XXXXXXXXXX' as mobile, retailers.shopname , retailers_logs.sale, up.latitude,  up.longitude , retailers.user_id,retailers.address,retailers.pin,locator_area.name as area_name,locator_city.name as city_name,locator_state.name as state_name,
//                        acos(sin($lat)*sin(radians(up.latitude)) + cos($lat)*cos(radians(up.latitude))*cos(radians(up.longitude)-$lng)) * $R As D,retailers_details.image_name as imagepath
//                    From
//                        user_profile as up
//                        LEFT JOIN retailers ON ( retailers.user_id = up.user_id )
//                        LEFT JOIN retailers_details ON ( retailers.id = retailers_details.retailer_id AND retailers_details.type='image')
//                        LEFT JOIN locator_area ON ( locator_area.id = retailers.area_id )
//                        LEFT JOIN locator_city ON ( locator_area.city_id = locator_city.id )
//                        LEFT JOIN locator_state ON ( locator_city.state_id = locator_state.id )
//                        LEFT JOIN retailers_logs ON ( retailers.id = retailers_logs.retailer_id AND date = '".date('Y-m-d',strtotime('-2 days'))."' )
//                    Where
//                        retailers_logs.sale >= 200 AND retailers.address != '' AND retailers.area_id != 0 AND ! isnull(retailers.address) AND retailers.kyc_flag!= 1 AND up.latitude != '' AND up.longitude != '' AND up.latitude != 0 AND up.longitude != 0
//                    Order By case when up.device_type = 'online' then 1 else 2 end, up.updated desc
//) as v WHERE v.D < $distance group by v.user_id order by v.sale desc) as t group by floor(t.D*$x) $limit";
          		$str = "SELECT * FROM "
                                . "(SELECT * FROM "
                                . "(SELECT 'XXXXXXXXXX' AS mobile,retailers.shopname,SUM(rel.amount) AS sale,up.latitude,up.longitude,retailers.user_id,retailers.address,retailers.pin,locator_area.name AS area_name,locator_city.name AS city_name,locator_state.name AS state_name,acos(sin($lat)*sin(radians(up.latitude)) + cos($lat)*cos(radians(up.latitude))*cos(radians(up.longitude)-$lng)) * $R AS D,retailers_details.image_name AS imagepath "
                                . "FROM user_profile AS up "
                                . "LEFT JOIN retailers ON ( retailers.user_id = up.user_id ) "
                                . "LEFT JOIN retailers_details ON ( retailers.id = retailers_details.retailer_id AND retailers_details.type='image') "
                                . "LEFT JOIN locator_area ON ( locator_area.id = retailers.area_id ) "
                                . "LEFT JOIN locator_city ON ( locator_area.city_id = locator_city.id ) "
                                . "LEFT JOIN locator_state ON ( locator_city.state_id = locator_state.id ) "
                                . "LEFT JOIN retailer_earning_logs rel ON ( retailers.user_id = rel.ret_user_id AND date = '".date('Y-m-d',strtotime('-2 days'))."' ) "
                                . "WHERE retailers.address != '' "
                                . "AND retailers.area_id != 0 "
                                . "AND ! isnull(retailers.address) "
                                . "AND retailers.kyc_flag!= 1 "
                                . "AND up.latitude != '' "
                                . "AND up.longitude != '' "
                                . "AND up.latitude != 0 "
                                . "AND up.longitude != 0 "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "ORDER BY CASE WHEN up.device_type = 'online' THEN 1 else 2 END, up.updated DESC ) AS v "
                                . "WHERE v.D < $distance "
                                . "GROUP BY v.user_id "
                                . "HAVING v.sale >= 200 "
                                . "ORDER BY v.sale DESC) AS t "
                                . "GROUP BY FLOOR(t.D*$x) $limit";
            }
           	else {
//           		$str = "select * from (
//Select 'XXXXXXXXXX' as mobile, retailers.shopname , retailers_logs.sale, up.latitude,  up.longitude , retailers.user_id,retailers.address,retailers.pin,locator_area.name as area_name,locator_city.name as city_name,locator_state.name as state_name,
//                        acos(sin($lat)*sin(radians(up.latitude)) + cos($lat)*cos(radians(up.latitude))*cos(radians(up.longitude)-$lng)) * $R As D,retailers_details.image_name as imagepath
//                    From
//                        user_profile as up
//                        LEFT JOIN retailers ON ( retailers.user_id = up.user_id )
//                        LEFT JOIN retailers_details ON ( retailers.id = retailers_details.retailer_id AND retailers_details.type='image')
//                        LEFT JOIN locator_area ON ( locator_area.id = retailers.area_id )
//                        LEFT JOIN locator_city ON ( locator_area.city_id = locator_city.id )
//                        LEFT JOIN locator_state ON ( locator_city.state_id = locator_state.id )
//                        LEFT JOIN retailers_logs ON ( retailers.id = retailers_logs.retailer_id AND date = '".date('Y-m-d',strtotime('-2 days'))."' )
//                    Where
//                        retailers_logs.sale >= 200 AND retailers.address != '' AND retailers.area_id != 0 AND ! isnull(retailers.address) AND retailers.kyc_flag!= 1 AND up.latitude != '' AND up.longitude != '' AND up.latitude != 0 AND up.longitude != 0  AND (up.device_type = 'online' OR (up.device_type != 'online' AND up.date >= '".date('Y-m-d',strtotime('-7 days'))."'))
//                    Order By case when up.device_type = 'online' then 1 else 2 end, up.updated desc
//) as t
//group by t.user_id order by t.D $limit";
           		$str = "SELECT * FROM "
                                . "(SELECT 'XXXXXXXXXX' as mobile,retailers.shopname,SUM(rel.amount) as sale, up.latitude,  up.longitude , retailers.user_id,retailers.address,retailers.pin,locator_area.name as area_name,locator_city.name as city_name,locator_state.name as state_name,acos(sin($lat)*sin(radians(up.latitude)) + cos($lat)*cos(radians(up.latitude))*cos(radians(up.longitude)-$lng)) * $R As D,retailers_details.image_name as imagepath "
                                . "FROM user_profile AS up "
                                . "LEFT JOIN retailers ON ( retailers.user_id = up.user_id ) "
                                . "LEFT JOIN retailers_details ON ( retailers.id = retailers_details.retailer_id AND retailers_details.type='image') "
                                . "LEFT JOIN locator_area ON ( locator_area.id = retailers.area_id ) "
                                . "LEFT JOIN locator_city ON ( locator_area.city_id = locator_city.id ) "
                                . "LEFT JOIN locator_state ON ( locator_city.state_id = locator_state.id ) "
                                . "LEFT JOIN retailer_earning_logs rel ON ( retailers.user_id = rel.ret_user_id AND date = '".date('Y-m-d',strtotime('-2 days'))."' ) "
                                . "WHERE retailers.address != '' "
                                . "AND retailers.area_id != 0 "
                                . "AND ! isnull(retailers.address) "
                                . "AND retailers.kyc_flag!= 1 "
                                . "AND up.latitude != '' "
                                . "AND up.longitude != '' "
                                . "AND up.latitude != 0 "
                                . "AND up.longitude != 0 "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "AND (up.device_type = 'online' OR (up.device_type != 'online' AND up.date >= '".date('Y-m-d',strtotime('-7 days'))."')) "
                                . "ORDER BY CASE WHEN up.device_type = 'online' THEN 1 ELSE 2 END, up.updated DESC ) as t "
                                . "GROUP BY t.user_id "
                                . "HAVING t.sale >= 200 "
                                . "ORDER BY t.D $limit";
           	}

            $result = $this->Slaves->query($str);

            /** IMP DATA ADDED : START**/
            $user_ids = array_map(function($element){
                return $element['retailers']['user_id'];
            },$result);

            $imp_data = $this->Shop->getUserLabelData($user_ids,2,0);
            $retailer_imp_label_map = array(
                'pan_number' => 'pan_no',
                'shopname' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id',
                'shop_structure' => 'shop_ownership',
                'shop_type' => 'business_nature'
            );
            foreach ($result as $key => $value) {
                foreach ($value['retailers'] as $retailer_label_key => $value) {
                    $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                    if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                        $result[$key]['retailers'][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
                    }
                }
            }
            /** IMP DATA ADDED : END**/

            return $result;
        }

        function forgetPassword($params) {
        	$this->autoRender = false;
                $MsgTemplate = $this->General->LoadApiBalance();
        	if (isset($params['mobileNo']) && !empty($params['mobileNo'])) {
        		$mobileNo = $params['mobileNo'];
        		$checkUserExist = $this->User->query("Select mobile from users where mobile = '" . $mobileNo . "'");
        		if (!empty($checkUserExist[0]['users']['mobile'])) {
        			$otp = $this->General->generatePassword(4);
//        			$msg = "Dear User, Your One Time Password(OTP) to reset your password is $otp";

                                $paramdata['OTP'] = $otp;
                                $content =  $MsgTemplate['Forget_Password_MSG'];
                                $msg = $this->General->ReplaceMultiWord($paramdata,$content);

        			$this->General->sendMessage($mobileNo, $msg, 'shops');
        			$this->Shop->setMemcache("otp_reset_$mobileNo",$otp,30*60);

        			$response = array("status" => "success", "description" => "OTP Send Successfully");
        		} else {
        			$response = array("status" => "failure", "description" => "Mobile No does not exist");
        		}
        	}
        	return $response;
        }

    function updatePassword($params) {
        $this->autoRender = false;
        if (isset($params['mobileNo']) && !empty($params['mobileNo'])) {
            $mobileNo = trim($params['mobileNo']);
            if (isset($params['otp']) && !empty($params['otp'])) {
                $otp = trim($params['otp']);
            }
            $checkUserExist = $this->User->query("Select mobile from users where mobile = '" . $mobileNo . "'");
            if (!empty($checkUserExist[0]['users']['mobile'])) {
                if (empty($params['password']) || empty($params['confirm_password']) || empty($params['otp'])) {
                    $response = array("status" => "failure", "description" => "Please Enter all details");
                }

                $otp_system = $this->Shop->getMemcache("otp_reset_$mobileNo");

                if (empty($otp_system) || $otp != $otp_system) {
                    $response = array("status" => "failure", "description" => "OTP does not match. Please retry again");
                }
                else if($params['password']!=$params['confirm_password']) {
                    $response = array("status" => "failure", "description" => "Passwords does not match");
                }
		else if(!$this->Shop->isStrongPassword($params['password'])){
                     $response = array("status" => "failure","code"=>"55" ,"description" =>"Kindly create a strong password");
                }
                else {
                    $password = $this->Auth->password($params['password']);
                    $this->User->query("update users
                    		set password='" . $password . "',
                    		passflag = 1
                    		where mobile='".$mobileNo."'");
                    $response = array("status" => "success", "description" => "Password reset Successfully!!!");
                }
            } else {
                $response = array("status" => "failure", "description" => "Mobile Number does not exist");
            }
        }
        return $response;
    }

    function sendNotification() {
    	return array();
        //$this->autoRender = false;
        //$getNotification = $this->General->findVar('notification');
        //$response = array("notification" => $getNotification);
        //return $response;
    }


function addMissedCallsLeads($mobile,$type=null){
            $response = array();
            if(empty($mobile)){
                $response = array(
                    "status"=>"failure",
                    "error"=>"Invalid Data ."
                );
            }else{
                $lead = $this->User->query("INSERT INTO `shops`.`distributors_leads`
                    (`id`, `name`, `email`, `state`, `city`, `fax`, `messages`, `phone`,`date`, `timestamp`, `req_by`) VALUES
                    (NULL, '', '', '', '', NULL, '', '$mobile','" . date("Y-m-d") . "','" . Date("Y-m-d H:i:s") . "', '');");
			if ($type != null){
				$subject = "Pay1 Distributor " . ucfirst($type) . " - from $mobile";
				$body = "
                </br> From : $mobile";

                               $MsgTemplate = $this->General->LoadApiBalance();
                               $msg = $MsgTemplate['Missed_CallsLeads_MSG'];
                               $this->General->sendMessage($mobile,$msg, 'notify');

//				$this->General->sendMessage($mobile, "Dear sir/madam,<br/> Thank you for showing your intrest in our business. We shall reach you out within 72 hours. PAY1( website:www.pay1.in)", 'notify');
				$this->General->sendMails($subject, $body, array('suraj@pay1.in', 'info@pay1.in'), 'mail');
			}
			$response = array(
                    "status"=>"success",
                    "description"=>"Lead updated successfully."
                );
            }
            return ($response);//json_encode
       }

        function createRetailerLeads($params){
            $create_lead['interest'] = $params['reg_i'];
        	$create_lead['name'] = $params['r_n'];
        	$create_lead['shop_name'] = $params['r_sn'];
        	$create_lead['email'] = $params['r_e'];
        	$create_lead['mobile'] = $params['r_m'];
        	$create_lead['area'] = $params['r_a'];
        	$create_lead['city'] = $params['r_c'];
        	$create_lead['pin_code'] = $params['r_p'];
        	$create_lead['state'] = $params['r_s'];
        	//$create_lead['comment'] = $params['c'];
                $create_lead['messages'] = $params['c'];
        	$create_lead['req_by'] = $params['req_by'];
        	$ref = $params['ref'];

        	if($ref){
        		$create_lead['req_by'] = base64_decode($ref);
        	}

        	foreach($create_lead as $kcl => $cl){
        		//if($kcl != 'comment')
                        if($kcl != 'messages'){
        			if(trim($cl) == ""){
        				return array('status' => 'failure','code'=>'E024','description' => $kcl." ".$this->Shop->apiErrors('E024'));
        			}
        		}

                        /*
                         *  Restricting user from submitting any html tags
                         *  Start
                         */
                        $data=trim($cl);
                        $data = stripslashes($data);
                        $data = htmlspecialchars($data);
                        $create_lead[$kcl]=$data;
                        /*
                         * End
                         */
        	}
        	$retailer_exists = $this->User->query("select * from retailers where mobile = '".$create_lead['mobile']."'");
        	if($retailer_exists)
        		return array('status' => 'failure','code'=>'E027','description' => "Retailer already exists with this mobile number");

        	$retailer_lead_exists = $this->User->query("select * from leads
    												where phone = '".$create_lead['mobile']."'");
                if(!$retailer_lead_exists){
        		$this->User->query("insert into leads
    							(interest, name, shop_name, email, phone, city, state, messages, area, pin_code, req_by, date, timestamp)
    							values ('".$create_lead['interest']."', '".$create_lead['name']."', '".$create_lead['shop_name']."',
        							'".$create_lead['email']."',
    								'".$create_lead['mobile']."', '".$create_lead['city']."', '".$create_lead['state']."',
    								'".$create_lead['messages']."', '".$create_lead['area']."', '".$create_lead['pin_code']."',
        							'".$create_lead['req_by']."', '".date('Y-m-d')."', '".date('Y-m-d H:i:s')."')");

        		if($create_lead['interest'] == "Retailer"){
        			$message = "Thank you for choosing PAY1 - India's Fastest Growing Retail Network!
        						For Info call on 022-67242288
								Check out more on Youtube: https://www.youtube.com/c/Pay1Inapp";
        			$this->General->sendMessage($create_lead['mobile'], $message, "payone");
        		}

        		$subject = "I want to become a ".$create_lead['interest'];

        		$body = "
        		</br> From          : ".$create_lead['name']."
        		</br> Shop          : ".$create_lead['shop_name']."
        		</br> Email-ID      : ".$create_lead['email']."
        		</br> Contact       : ".$create_lead['mobile']."
        		</br> State         : ".$create_lead['state']."
        		</br> City          : ".$create_lead['city']."
        		</br> Area	    : ".$create_lead['area']."
        		</br> Pin Code	    : ".$create_lead['pin_code']."
        		</br> Interested In : ".$create_lead['interest']."
        		</br> Source        : ".$create_lead['req_by']."
        		</br> Comment       : ".$create_lead['messages'];

        		$this->General->sendMails($subject, $body, array('sales@pay1.in', 'info@pay1.in'), 'mail');

        		$filename = "lead_management_".date('Ymd').".txt";
        		$this->General->logData('/mnt/logs/'.$filename, json_encode($create_lead));

        		$columns = array();
        		$columns['mx_Shop_Name'] = $create_lead['shop_name'];
        		$columns['mx_Retailer_Name'] = $create_lead['name'];
        		$columns['EmailAddress'] = $create_lead['email'];
        		$columns['Mobile'] = $create_lead['mobile'];
        		$columns['mx_State'] = $create_lead['state'];
        		$columns['mx_City'] = $create_lead['city'];
        		$columns['mx_Area'] = $create_lead['area'];
        		$columns['mx_Pin_Code'] = $create_lead['pin_code'];
        		$columns['mx_Messages'] = $create_lead['messages'];
        		$columns['mx_Date'] = date('Y-m-d');
        		$columns['mx_Timestamp'] = date('Y-m-d H:i:s');
        		$columns['Source'] = $create_lead['req_by'];
        		$columns['mx_Interest'] = $create_lead['interest'];

        		$this->General->logData('/mnt/logs/'.$filename, json_encode($columns));

        		App::import('Controller', 'Leadmanagement');
                $obj = new LeadmanagementController;
        		$obj->constructClasses();
        		$obj->createLead($columns);
        	}
        	else {
        		$this->User->query("update leads
    							set interest = '".$create_lead['interest']."', name = '".$create_lead['name']."',
        						shop_name = '".$create_lead['shop_name']."',
    							email = '".$create_lead['email']."', city = '".$create_lead['city']."',
    							state = '".$create_lead['state']."', messages = '".$create_lead['messages']."',
        						area = '".$create_lead['area']."', pin_code = '".$create_lead['pin_code']."',
        						req_by = '".$create_lead['req_by']."', date = '".date('Y-m-d')."',
        						timestamp = '".date('Y-m-d H:i:s')."'
    							where phone = '".$create_lead['mobile']."'");
        	}
        	return array('status' => 'success', 'code'=>'E0000', 'description' => "Retailer lead generated");
        }


        function sendOTPToRetailer($params){
        	$retailer_mobile = $params['r_m'];
        	if(trim($retailer_mobile)){
        		$retailer = $this->User->query("select * from leads where phone = '".$retailer_mobile."'");
        		if($retailer){
        			$otp = $this->General->generatePassword(6);

//        			$message = "You have registered as a Retailer with Pay1. Use OTP ".$otp." to verify your mobile number.
//    						Do not share it with anyone";

                                $MsgTemplate = $this->General->LoadApiBalance();
                                $paramdata['OTP'] = $otp;
                                $content =  $MsgTemplate['Retailer_Registered_MSG'];
                                $message = $this->General->ReplaceMultiWord($paramdata,$content);

        			$this->General->sendMessage($retailer_mobile, $message, 'payone', null);
        			$this->Shop->setMemcache("otp_retailerRegistration_$retailer_mobile", $otp, 30*60);
        			$OTA_Fee = $this->General->findVar("OTA_Fee");
        			return array('status' => 'success', 'OTA_Fee' => $OTA_Fee, 'description' => "OTP has been sent to your mobile number");
        		}
        		else
        			return array('status' => 'failure','code'=>'E025','description' => $this->Shop->apiErrors('E025'));
        	}
        	else
        		return array('status' => 'failure','code'=>'E024','description' => "Mobile ".$this->Shop->apiErrors('E024'));
        }

        function verifyRetailerLead($params){
        	$retailer_mobile = $params['r_m'];
        	$otp = $params['otp'];
        	$pin = $params['pin'];
        	$r_u_d = $params['r_u_d'];

        	if(strlen($otp) != 6 || trim($pin) == ""){
        		return array('status' => 'failure','code'=>'E026','description' => $this->Shop->apiErrors('E026'));
        	}

        	if(trim($retailer_mobile)){
        		$retailer_exists = $this->User->query("select * from retailers where mobile = '".$retailer_mobile."'");
        		if($retailer_exists)
        			return array('status' => 'failure','code'=>'E027','description' => "Retailer Already Created");
        		$retailer = $this->User->query("select * from leads where phone = '".$retailer_mobile."'");

        		if($retailer){
        			if($otp == $this->Shop->getMemcache("otp_retailerRegistration_$retailer_mobile")){
                                	$this->Shop->delMemcache("otp_retailerRegistration_$retailer_mobile");
        				$retailer = $retailer[0]['leads'];
        				$retailer['pin'] = $pin;
        				$retailer['distributor_user_id'] = 8;
        				$retailer['api_flow'] = "verify_lead";
        				$retailer['r_u_d'] = $r_u_d;

        				return $this->createRetailer($retailer);
        			}
        			else
        				return array('status' => 'failure','code'=>'E027','description' => $this->Shop->apiErrors('E027'));
        		}
        		else
        			return array('status' => 'failure','code'=>'E025','description' => $this->Shop->apiErrors('E025'));
        	}
        	else
        		return array('status' => 'failure','code'=>'E024','description' => "Mobile ".$this->Shop->apiErrors('E024'));
        }

    function pgPayUSeamless(){
		foreach(array_keys($_POST) as $post_key){
			$this->set($post_key, $_POST[$post_key]);
		}

		$banks = $this->Slaves->query("select * from banks");
		$this->set('banks', $banks);

		$this->render('/elements/pg_payu', "");
    }

    function lastTransaction($params){
    	$number = $params['number'];
    	$product_id = $params['product_id'];
    	$retailer_id = $params['retailer_id'];

    	if($number && $product_id && $retailer_id){
	    	$lastTransaction = $this->Slaves->query("select va.id, va.timestamp, va.amount
	 					from vendors_activations va
						join products p on(p.id = va.product_id)
						join services on (p.service_id = services.id)
						where (va.mobile = '$number' or va.param = '$number')
	 					and va.product_id = '$product_id'
	 					and va.retailer_id = '$retailer_id'
	    				and va.status = 1
	    	 			group by va.id
	    	 			order by va.timestamp desc
	    				limit 1");
	    	if($lastTransaction){
	    		return array("status" => "success", "description" => array("time_stamp" => $lastTransaction[0]['timestamp'], "amount" => $lastTransaction[0]['amount']));
	    	}
	    	else
	    		return array("status" => "failure", "description" => "No transaction found");
    	}
    	else
    		return array("status" => "failure", "description" => "Invalid parameters given");
    }


    function lastFiveTransactions($params){
    	try{
    		$transactions = $this->Shop->lastFiveTransactions();
    		return array('status' => 'success','description' => array($transactions));
    	}catch(Exception $e){
    		return array('status' => 'failure','code'=>'30','descrption'=>$this->Shop->errors(30));
    	}
    }

    function complaintStats($params){
    	$retailer_id = $_SESSION['Auth']['id'];
    	if($retailer_id){
    		$complaints = $this->Shop->complaintStats($retailer_id);

    		$resolved = $complaints[0]['complaints']['resolve_flag'] ? $complaints[0][0]['count'] : $complaints[1][0]['count'];
    		if(!$resolved) $resolved = 0;
    		$total_complaints = count($complaints);
    		$stats = array('complaints' => $total_complaints + "", 'resolved' => $resolved + "", 'unresolved' => ($total_complaints - $resolved) + "");
    		return array("status" => "success", "description" => $stats);
    	}
    	else {
    		return array("status" => "failure", "description" => "Invalid parameters");
    	}
    }

    function searchTransactionsHistory($params){
    	$mob_or_sub_id = $params['param'];
    	$retailer_id = $_SESSION['Auth']['id'];
    	if($mob_or_sub_id && $retailer_id){
    		$transactions = $this->Shop->searchTransactionsHistory($mob_or_sub_id, $retailer_id);
    		if($transactions){
    			return array("status" => "success", "description" => array($transactions));
    		}
    		else {
    			return array("status" => "failure", "description" => "No transactions found for the given parameter");
    		}
    	}
    	else {
    		return array("status" => "failure", "description" => "Invalid parameters");
    	}
    }

    function clickToCall($params){
    	if($params['mobile']){
    		$retailers = $this->Slaves->query("select r.*, rks.*
    				from retailers r
    				left join retailers_kyc_states rks on rks.retailer_id = r.id
    				where r.mobile = '".$params['mobile']."'");
    		if(!empty($retailers)){
    			if($retailers[0]['r']['kyc_score'] == "100"){
    				if(date("H") >= 8 && date('H') < 23){
    					$this->General->curl_post_async("http://click2call.ddns.net/index.php",
    							array('mobile'=>$params['mobile'], 'incoming_route'=>'2288'));

    					$this->Retailer->query("INSERT INTO cc_misscalls
	    					(number, timestamp)
	    					VALUES ('".$params['mobile']."', '".date('Y-m-d H:i:s')."')");

    					$this->Retailer->query("INSERT INTO cc_call_logging
	    					(number, retailer_id, distributor_id, time, date, call_status, type)
	    					VALUES ('".$params['mobile']."', ".$retailers[0]['r']['id'].", '','".date('H:i:s')."', '".date('Y-m-d')."', 1, 'Toll-free Call')");

    					return array("status" => "success", "description" => "We will call you shortly!");
    				}
    				else {
    					return array("status" => "failure", "code" => "E104", "description" => $this->Shop->apiErrors('E104'));
    				}
    			}
    			else if($retailers[0]['rks']['id'] == NULL){
    				return array("status" => "failure", "code" => "E101",
    						"description" => $this->Shop->apiErrors('E101'));
    			}
    			else {
    				$submitted = true;
    				$rejected = false;
    				foreach($retailers as $r){
    					if($r['rks']['document_state'] == 1){
    						$rejected = true;
    					}
    				}
    				if($rejected){
    					return array("status" => "failure", "code" => "E102",
    							"description" => $this->Shop->apiErrors('E102'));
    				}
    				else if(count($retailers) < 3){
    					return array("status" => "failure", "code" => "E105",
    							"description" => $this->Shop->apiErrors('E105'));
    				}
    				else {
    					return array("status" => "failure", "code" => "E103",
    							"description" => $this->Shop->apiErrors('E103'));
    				}
    			}
    		}
    		else {
    			return array("status" => "failure", "description" => "No retailer found");
    		}
    	}
    	else {
    		return array("status" => "failure", "description" => "No mobile given");
    	}
    }

	function checkRetailerExist($params){

		if(isset($params['mobileNo']) && !empty($params['mobileNo'])){

			$mobileno = $params['mobileNo'];

			$todate = date('Y-m-d');

			$frmdate = date("Y-m-d", strtotime("-1 Month", strtotime($todate)));

			$retQuery = $this->Slaves->query("SELECT * from retailers where mobile ='".$mobileno."'");


			if(empty($retQuery)){

				return array("status" => "success","description" => "No retailer found","code"=>"0");
			} else {

				$checkdeviceType = $this->Slaves->query("SELECT count(*) from vendors_activations "
													. "inner join retailers "
							                        . "ON (retailers.id = vendors_activations.retailer_id)"
						                         	. " WHERE date between '".$frmdate."' and '".$todate."' "
													. "and retailers.mobile = '".$mobileno."' and api_flag NOT IN (0,2) group by vendors_activations.id ");

				if(!empty($checkdeviceType)){

					return array("status" => "success","description" => "App or web user","code"=>"1");

				} else {

					$ret = $this->forgetPassword($params);  // send otp to ussd and sms user

					if($ret){

						return array("status" => "success","description" => $ret,"code"=>"2");
					}


				}
			}
		} else {

			return array("status" => "failure","description" => 'Mobile no can not be blank');
		}


		$this->autoRender = FALSE;

	}

	/*function createMposTransaction($params){
		if(isset($params['product_id']) && !empty($params['amount'])){
			if($this->General->priceValidate($params['amount']) == ''){//amount validation
				return array('status'=>'failure','code'=>'6','description'=>$this->Shop->errors(6));
			}

			App::import('Controller', 'Mpos');
			$obj = new MposController;
			$obj->constructClasses();
			return $obj->createTransaction($params);
		}
		else {
			return array("status" => "failure", "description" => "Invalid details provided.");
		}
	}

	function mposTransactionResponse($params){
		if(isset($params['shop_transaction_id']) && isset($params['card_transaction_response'])){
			App::import('Controller', 'Mpos');
			$obj = new MposController;
			$obj->constructClasses();
			return $obj->completeTransaction($params);
		}
		else {
			return array("status" => "failure", "description" => "Invalid details provided.");
		}
	}

	function mposTransactionsHistory($params){
		$params['date'] = !empty($params['date']) ? $params['date'] : date('Y-m-d');

		App::import('Controller', 'Mpos');
		$obj = new MposController;
		$obj->constructClasses();
		return $obj->transactionsHistory($params);
	}

	function isServiceActivated($params){
		$retailer_id = $_SESSION['Auth']['id'];
		$service_id = $params['service_id'];

		if(!empty($service_id)){
			$retailers_services = $this->Slaves->query("select *
					from retailers_services rs
					where rs.service_id = ".$service_id."
					and rs.retailer_id = ".$retailer_id);
			if(!empty($retailers_services)){
				return array("status" => "success", "description" => "Your service is active.");
			}
			else {
				return array("status" => "failure", "description" => "This service is not activated.");
			}
		}
		else
			return array("status" => "failure", "description" => "Service ID not provided.");
	}*/



        function getAreaUsingLatLong($lat,$long){
                $res = $this->General->getAreaByLatLong($long,$lat);
                echo json_encode($res);
                die;
        }

	/*function serviceActivationRequest($params){
		$retailer_id = $_SESSION['Auth']['id'];
		$service_id = $params['service_id'];

		if(!empty($service_id)){
			switch($service_id){
				case '8':
					$mPOS_leads = $this->Slaves->query("select * from mPOS_leads
							where retailer_id = ".$retailer_id);
					if(empty($mPOS_leads)){
						$this->User->query("insert into mPOS_leads
								(retailer_id, created)
								values ('$retailer_id', '".date('Y-m-d H:i:s')."')");
						return array("status" => "success", "description" => "Our team will call you within 48 hours or you may contact your distributor.");
					}
					else {
						return array("status" => "failure", "description" => "Your service activation request has already been registered.");
					}
					break;
			}
			return array("status" => "failure", "description" => "This service is not available for activation.");
		}
		else
			return array("status" => "failure", "description" => "Service ID not provided.");
	}*/

	function changeMobileNumber($params){
                if($params['oldNumber'] != $_SESSION['Auth']['mobile']){
                    return array('status'=>'failure','code'=>'61','description'=>$this->Shop->errors(61));
                }
		if(empty($params['newNumber']) || empty($params['password']) || empty($params['oldNumber'])){
			return array("status" => "failure", "code" => "44", "description" => $this->Shop->apiErrors(44));
		}

		$password = $this->Auth->password($params['password']);
		$users = $this->Slaves->query("select users.*,user_groups.group_id
						from users inner join user_groups ON (users.id = user_groups.user_id)
						where mobile = '".$_SESSION['Auth']['mobile']."'
						and password = '".$password."' AND active_flag = 1
						ORDER by group_id asc limit 1");

		if(!empty($users)){
		    if($users[0]['user_groups']['group_id'] == DISTRIBUTOR){
		        return array("status" => "failure", "code" => "70", "description" => $this->Shop->apiErrors(70));
		    }
			$newUsers = $this->Slaves->query("select *
    					from users
						where mobile = '".$params['newNumber']."'");
			if(!empty($newUsers) && $newUsers[0]['user_groups']['group_id'] != MEMBER){
				return array("status" => "failure", "code" => "45", "description" => $this->Shop->apiErrors(45));
			}

			$otp = $this->General->generatePassword(6);

    		$MsgTemplate = $this->General->LoadApiBalance();
    		$paramdata['OTP'] = $otp;
    		$content =  $MsgTemplate['Send_OTP_MSG'];
    		$msg = $this->General->ReplaceMultiWord($paramdata, $content);

    		$this->Shop->setMemcache("changeMobileNumber_otp_".$_SESSION['Auth']['mobile'], $otp."_".$params['newNumber'], 30*60);
    		$this->General->sendMessage($_SESSION['Auth']['mobile'], $msg, 'payone');

    		return array("status" => "success", "description" => "You will receive One Time Password (OTP) vis SMS on ".$_SESSION['Auth']['mobile']);
		}
		else {
			return array("status" => "failure", "code" => "46", "description" => $this->Shop->apiErrors(46));
		}
	}

	function authenticateMobileNumberChange($params){
		$system_otp_mobile = $this->Shop->getMemcache("changeMobileNumber_otp_".$_SESSION['Auth']['mobile']);
		if($system_otp_mobile !== false){
			$system_otp_mobile = explode("_", $system_otp_mobile);
			$otp = $system_otp_mobile[0];
			$newMobile = $system_otp_mobile[1];

			if($otp == $params['otp'] || !$this->General->isOTPRequired($system_otp_mobile)){
				$newUsers = $this->Slaves->query("select *
    							from users
								where mobile = '$newMobile'");
				if(!empty($newUsers)){
					$currentUsers = $this->Slaves->query("select *
    							from users
								where mobile = '".$_SESSION['Auth']['mobile']."'");
					$newM = substr("temp".$newUsers[0]['users']['id'],0,10);
					$this->User->query("update users
							set mobile = '{$newM}'
							where id = ".$newUsers[0]['users']['id']); //relacing new num by 'temp_str' in users table.
					$this->User->query("update users
    						set mobile = '".$newMobile."',
    						ussd_flag = 0
    						where id = ".$currentUsers[0]['users']['id']);
					$this->User->query("update retailers
    						set mobile = '".$newMobile."',
    						modified = '".date('Y-m-d H:i:s')."'
    						where user_id = ".$currentUsers[0]['users']['id']);
					$this->User->query("update users
    						set mobile = '".$_SESSION['Auth']['mobile']."',
							ussd_flag = 0
							where id = ".$newUsers[0]['users']['id']);
					
					$MsgTemplate = $this->General->LoadApiBalance();
					$paramdata['OLD_NUMBER'] = $_SESSION['Auth']['mobile'];
					$paramdata['NEW_NUMBER'] = $newMobile;
					$content1 =  $MsgTemplate['Retailer_addNewNumber_MSG'];
					$msg1 = $this->General->ReplaceMultiWord($paramdata, $content1);
					$this->General->sendMessage($newMobile, $msg1, 'shops');

					return array("status" => "success", "description" => $msg1);
				}
				else {
					$this->User->query("update users
    							set mobile = '".$newMobile."',
    							ussd_flag = 0
    							where mobile = '".$_SESSION['Auth']['mobile']."'");
					$this->User->query("update retailers
    							set mobile = '".$newMobile."',
    							modified = '".date('Y-m-d H:i:s')."'
    							where mobile = '".$_SESSION['Auth']['mobile']."'");

					$MsgTemplate = $this->General->LoadApiBalance();
					$paramdata['OLD_NUMBER'] = $_SESSION['Auth']['mobile'];
					$paramdata['NEW_NUMBER'] = $newMobile;
					$content1 =  $MsgTemplate['Retailer_addNewNumber_MSG'];
					$msg1 = $this->General->ReplaceMultiWord($paramdata, $content1);
					$this->General->sendMessage($newMobile, $msg1, 'shops');

					return array("status" => "success", "description" => $msg1);
				}
			}
			else {
				return array("status" => "failure", "code" => "47", "description" => $this->Shop->apiErrors(47));
			}
		}
		else {
			return array("status" => "failure", "code" => "48", "description" => $this->Shop->apiErrors(48));
		}
	}

	function kitActivationRequest($params){
		$distributors = $this->Slaves->query("select *
				from distributors d
				join users u on u.id = d.user_id
				join retailers r on r.id = ".$_SESSION['Auth']['id']."
				where d.id = ".$_SESSION['Auth']['parent_id']);

        /** IMP DATA ADDED : START**/
        $temp = $this->Shop->getUserLabelData($_SESSION['Auth']['id'],2,2);
        $imp_data = $temp[$_SESSION['Auth']['id']];
        $distributors[0]['r']['shopname'] = $imp_data['imp']['shop_est_name'];
        /** IMP DATA ADDED : END**/

		if($distributors[0]['d']['id'] == 1){
			return array("status" => "success", "description" => "Contact Pay1 Customer Care for details.");
		}
		else {
			$message = $distributors[0]['r']['shopname']." (".$distributors[0]['r']['mobile'].") wants to activate kit.";
			$this->General->sendMessage($distributors[0]['u']['mobile'], $message, 'payone');

			return array("status" => "success", "description" => "Contact your distributor for details.");
		}
	}

	function bankAccounts($params){
		$accounts = $this->Slaves->query("select *
				from bank_details
				where visible_to_retailer_flag = 1");
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

	function walletTopup($params){
		if($this->General->mobileValidate($params['mobileNumber']) == '1'){//mobile no validation
			return array('status'=>'failure','code'=>'5','description'=>$this->Shop->errors(5));
		}else if($this->General->priceValidate($params['amount']) == ''){//amount validation
			return array('status'=>'failure','code'=>'6','description'=>$this->Shop->errors(6));
		}

		if(in_array('product_id',$params)){
		    $params['product_id'] = (int)$params['product_id'];
		}

		if($params['product_id'] == '44'){
		        $ret = $this->Api->pay1Wallet($params);
			return $ret;
		}
		else {
			App::import('Controller', 'Wallets');
			$obj = new WalletsController;
			$obj->constructClasses();
			return $obj->addMoney($params);
		}
	}

	function banksAndTransferTypes($params){
		$banks = $this->Slaves->query("select *
				from bank_details
				where visible_to_retailer_flag = 1");
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
		if(empty($params['bank_acc_id']) || empty($params['trans_type_id'])){
			return array("status" => "failure", "description" => "Fields cannot be left empty");
		}
		if($this->General->priceValidate($params['amount']) == ''){
			return array("status" => "failure", "description" => "Invalid amount entered");
		}
                
                $allowed_exts = array("gif", "jpeg", "jpg", "png");
                
                $ext = explode('.', $_FILES['bank_slip']['name']);
                $extension = $ext[sizeof($ext) - 1];
                
                $imgUrl = '';
                
                if($_FILES['bank_slip']['name'] != '') {
                        if($_FILES['bank_slip']['size'] > 5000000) {   // 5 MB
                                return array('status' => 'failure','description' => 'File size should not be more than 5 MB');
                        } else if (!in_array($_FILES['bank_slip']['type'], array('image/png','image/jpeg','image/jpg','image/gif')) && !in_array($extension, $allowed_exts)) {
                                return array('status' => 'failure','description' => 'Allowed file types: gif, jpeg, jpg, png');
                        } else {
                                $img_name = 'bank_slip';
                                $imgUrl   = $this->uploadImage($img_name);
                        }
                }

		// $data = $this->Slaves->query("select *
		// 				from retailers
		// 				where id = ".$_SESSION['Auth']['id']);

        $temp = $this->Shop->getUserLabelData($_SESSION['Auth']['id'],2,2);
        $imp_data = $temp[$_SESSION['Auth']['id']];

		$message = "We have received your request. You will get your topup in sometime";
		$sub = "Retailer deposited money in bank";

        // $body = "Retailer Shop Name ".$data['0']['retailers']['shopname']." deposited Rs ".$params['amount']." in our
		// 		".$params['bank_acc_id']." account (TransID: ".$params['bank_trans_id'].")<br/>Mobile:
        //         ".$data['0']['retailers']['mobile'];

        $body = "Retailer Shop Name ".$imp_data['imp']['shop_est_name']." deposited Rs ".$params['amount']." in our
				".$params['bank_acc_id']." account (TransID: ".$params['bank_trans_id'].")<br/>Mobile:
                ".$imp_data['ret']['mobile'];

		$this->General->sendMails($sub,$body,array('limits@pay1.in'));
		$data1 = array();
		$data1['time'] = date("Y-m-d H:i:s");
		$data1['msg'] =  $body;
		$data1['sender'] = "PAY1";
		$data1['process'] = "limits";
		// $data1['id'] =  $data['0']['retailers']['id'];
		$data1['id'] =  $imp_data['ret']['id'];
		$data1['type'] = "Retailer";
		// $data1['name'] = $data['0']['retailers']['shopname'];
		$data1['name'] = $imp_data['imp']['shop_est_name'];
		// $data1['mobile'] = $data['0']['retailers']['mobile'];
		$data1['mobile'] = $imp_data['ret']['mobile'];
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
//		$this->General->curl_post('http://apptesting.pay1.in/limits/server.php', $data1);

		return array("status" => "success", "description" => $message);
	}

        function createRetDistLeads($params){

            $create_lead['mobile'] = $params['r_m'];
            $create_lead['interest'] = $params['reg_i'];

            if(strlen( $params['r_m']) != 10 || trim($params['r_p']) == ""){
               return array('status' => 'failure','code'=>'50','description' => $this->Shop->apiErrors('50'));
            }

            //Only Users, Group ID with 1 can became Retailer or Distrubtor
            $user_exist = $this->User->query("Select user_groups.group_id from user_groups inner join users ON (users.id = user_groups.user_id) where mobile = '".$create_lead['mobile']."' and user_groups.group_id != 1 ");

            if($user_exist){
               return array('status' => 'failure','code'=>'60','description' => $this->Shop->apiErrors('60'));
            }

            if($create_lead['interest'] == 'Retailer'){
               $retailer_exists = $this->User->query("select * from retailers where mobile = '".$create_lead['mobile']."'");
            }else if($create_lead['interest'] == 'Distributor'){
               $distributor_exists = $this->User->query("select * from users us join distributors d on d.user_id = us.id where mobile = '".$create_lead['mobile']."' ");
            }else{
                return array('status' => 'failure','code'=>'58','description' => $this->Shop->apiErrors('58'));
            }

            if($retailer_exists){
                return array('status' => 'failure','code'=>'52','description' => $this->Shop->apiErrors('52'));
            }

            if($distributor_exists){
                return array('status' => 'failure','code'=>'53','description' => $this->Shop->apiErrors('53'));
            }

            $lead_exists = $this->User->query("select * from leads where phone = '".$create_lead['mobile']."'");
            if(empty($lead_exists)){

                    $create_lead['name'] = $params['r_n'];
                    $create_lead['email'] = $params['r_e'];
                    $create_lead['address'] = $params['r_a_d'];
                    $create_lead['area'] = $params['r_a_r'];
                    $create_lead['pin_code'] = $params['r_p'];
                    $create_lead['shop_name'] = $params['r_s_n'];
                    $create_lead['req_by'] = $params['req_by'];

                    $create_lead['business_nature_type'] = $params['r_b_n'];
                    $create_lead['password'] = isset($params['r_p_d']) ? $params['r_p_d'] : '' ;
                    $create_lead['business_area_dist_of'] = isset($params['r_b_a']) ? $params['r_b_a'] : $params['d_c_d'] ;
                    $create_lead['dist_emp_strength'] = isset($params['d_e_l']) ? $params['d_e_l'] : 0 ;

                    $redis = $this->Shop->redis_connect();

                    foreach ($create_lead as $key => $value) {
                       $redis->hSet('Retailers_Distributors_Leads_'.$create_lead['mobile'], $key, $value);
                    }

                    $data = $this->sendOTPToRetDistLeads($create_lead);
                    return $data;
            }else{
                return array('status' => 'failure','code'=>'51','description' => $this->Shop->apiErrors('51'));
            }


        }

        function sendOTPToRetDistLeads($params){

                $this->autoRender = false;

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

                                }else if(isset($params['create_super_dist_otp_flag']) && ($params['create_super_dist_otp_flag'] == 1)){

                                    $content =  $MsgTemplate['Retailer_Distributor_Registered_MSG'];

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



        function verifyRetDistLeads($params){

            $lead_mobile = $params['r_m'];
            $otp = $params['otp'];

            if((strlen($otp) != 6) || (strlen($lead_mobile) != 10)){
                return array('status' => 'failure','code'=>'57','description' => $this->Shop->apiErrors('57'));
            }

            if(trim($lead_mobile)){

                $retailer_exists = $this->User->query("select * from retailers where mobile = '".$lead_mobile."'");
                if($retailer_exists){
                    return array('status' => 'failure','code'=>'52','description' => $this->Shop->apiErrors('52'));
                }

                $distributor_exists =  $this->User->query("select * from users us join distributors d on d.user_id = us.id where mobile = '".$lead_mobile."' ");
                if($distributor_exists){
                    return array('status' => 'failure','code'=>'53','description' => $this->Shop->apiErrors('53'));
                }

                $leads_exists = $this->User->query("select * from leads where phone = '".$lead_mobile."'");

                    if(empty($leads_exists)){

                      if($otp == $this->Shop->getMemcache("otp_RetDist_Registration_$lead_mobile")){
                            $this->Shop->delMemcache("otp_RetDist_Registration_$lead_mobile");

                            $redis = $this->Shop->redis_connect();
                            $create_lead =  $redis->hgetall('Retailers_Distributors_Leads_'.$lead_mobile);

                            $this->User->query("insert into leads
                                (interest, name, shop_name, email, phone, city, state, messages, area, pin_code, business_nature_type , business_area_dist_of, dist_emp_strength , req_by, date, timestamp)
                                values ('".$create_lead['interest']."', '".$create_lead['name']."', '".$create_lead['shop_name']."',
                                        '".$create_lead['email']."',
                                        '$lead_mobile', '".$create_lead['city']."', '".$create_lead['state']."',
                                        '".$create_lead['messages']."', '".$create_lead['area']."', '".$create_lead['pin_code']."',
                                        '".$create_lead['business_nature_type']."', '".$create_lead['business_area_dist_of']."', '".$create_lead['dist_emp_strength']."',
                                        '".$create_lead['req_by']."', '".date('Y-m-d')."', '".date('Y-m-d H:i:s')."')");

                         $key='Retailers_Distributors_Leads_'.$lead_mobile;

                         array_map(function($a) use ($redis,$key) {$redis->hdel($key,$a);},array_keys($create_lead));

                        $subject = "I want to become a ".$create_lead['interest'];

        		$body = "
        		</br> From          : ".$create_lead['name']."
        		</br> Shop          : ".$create_lead['shop_name']."
        		</br> Email-ID      : ".$create_lead['email']."
        		</br> Contact       : ".$create_lead['mobile']."
        		</br> State         : ".$create_lead['state']."
        		</br> City          : ".$create_lead['city']."
        		</br> Area	    : ".$create_lead['area']."
        		</br> Pin Code	    : ".$create_lead['pin_code']."
        		</br> Interested In : ".$create_lead['interest']."
        		</br> Source        : ".$create_lead['req_by']."
        		</br> Comment       : ".$create_lead['messages'];

        		$this->General->sendMails($subject, $body, array('sales@pay1.in', 'info@pay1.in'), 'mail');

        		$filename = "lead_management_".date('Ymd').".txt";
        		$this->General->logData('/mnt/logs/'.$filename, json_encode($create_lead));

        		$columns = array();
        		$columns['mx_Shop_Name'] = $create_lead['shop_name'];
        		$columns['mx_Retailer_Name'] = $create_lead['name'];
        		$columns['EmailAddress'] = $create_lead['email'];
        		$columns['Mobile'] = $create_lead['mobile'];
        		$columns['mx_State'] = $create_lead['state'];
        		$columns['mx_City'] = $create_lead['city'];
        		$columns['mx_Area'] = $create_lead['area'];
        		$columns['mx_Pin_Code'] = $create_lead['pin_code'];
        		$columns['mx_Messages'] = $create_lead['messages'];
        		$columns['mx_Date'] = date('Y-m-d');
        		$columns['mx_Timestamp'] = date('Y-m-d H:i:s');
        		$columns['Source'] = $create_lead['req_by'];
        		$columns['mx_Interest'] = $create_lead['interest'];

        		$this->General->logData('/mnt/logs/'.$filename, json_encode($columns));

        		App::import('Controller', 'Leadmanagement');
                        $obj = new LeadmanagementController;
        		$obj->constructClasses();
        		$obj->createLead($columns);


                            $MsgTemplate = $this->General->LoadApiBalance();
                            $message =  $MsgTemplate['Create_RetDist_Leads_MSG'];
                            $this->General->sendMessage($create_lead['mobile'], $message, "payone");

                            if(trim($create_lead['interest']) == "Retailer"){

                                $retailer = $this->User->query("select interest, name , shop_name , email, state , city ,fax, messages, area , pin_code,phone , timestamp, req_by, date  from leads where phone = '$lead_mobile'");

                                $retailer = $retailer[0]['leads'];
                                $retailer['distributor_user_id'] = 8;
                                $retailer['api_flow'] = "verify_lead";
                                $retailer['r_u_d'] = $params['r_u_d'];
                                $retailer['pin'] = $create_lead['password'];

                                $this->createRetailer($retailer);
                                return array('status' => 'success', 'code'=>'55', 'description' => $this->Shop->apiErrors('55'));

                            }else if(trim($create_lead['interest']) == "Distributor"){

                                return array('status' => 'success', 'code'=>'55', 'description' => $this->Shop->apiErrors('55'));
                            }
                            return array('status' => 'failure','code'=>'56','description' => $this->Shop->apiErrors('56'));
                        }
                        else{
                            return array('status' => 'failure','code'=>'54','description' => $this->Shop->apiErrors('54'));
                        }
                    }else{
                        return array('status' => 'failure','code'=>'51','description' => $this->Shop->apiErrors('51'));
                    }

            }
            else
                    return array('status' => 'failure','code'=>'50','description' => "Mobile ".$this->Shop->apiErrors('50'));
        }


        function verifyOTP($params){
                $this->autoRender = false;
                $verify_name = $params['interest'];
                $otp = $params['otp'];
        	$dist_mobile = isset($params['dist_mobile']) ? $params['dist_mobile'] : 0 ;
                $change_mobile = isset($params['changeMobile']) ? $params['changeMobile'] : 0 ;
                $retailer_mobile = isset($params['mobile']) ? $params['mobile'] : $params['newMobile'];
                if(strlen($retailer_mobile) != 10 || strlen($otp) != 6 || trim($verify_name) == ""){

                    return array('status' => 'failure','code'=>'57','description' => $this->Shop->apiErrors('57'));
                }

        	if(trim($retailer_mobile)){
                        //if($verify_name == 'Retailer')
        		//$retailer_exists = $this->User->query("select * from retailers where mobile = '".$retailer_mobile."'");

        		if(empty($retailer_exists)){

                            if($change_mobile) { $retailer_mobile =  $dist_mobile ; }

                            
                            if($otp == $this->Shop->getMemcache("otp_RetDist_Registration_$retailer_mobile") || !$this->General->isOTPRequired($retailer_mobile)){

                                       $this->Shop->delMemcache("otp_RetDist_Registration_$retailer_mobile");
        						return array('status' => 'success','code'=>'E027','description' => $verify_name." is verify");
                            }
        		    else
        			    return array('status' => 'failure','code'=>'48','description' => $this->Shop->apiErrors('48'));
                        }
        		else
        		    return array('status' => 'failure','code'=>'52','description' => $this->Shop->apiErrors('52'));
        	}
        	else
                    return array('status' => 'failure','code'=>'49','description' => "Mobile ".$this->Shop->apiErrors('49'));
        }


        function createRetDistNewLeads($params){
            $create_lead['name'] = $params['r_n'];
            $create_lead['mobile'] = $params['r_m'];
            $create_lead['email'] = $params['r_e'];
            $create_lead['pin_code'] = $params['r_p'];
            $create_lead['shop_name'] = $params['r_s_n'];
            $create_lead['interest'] = $params['reg_i'];
            $create_lead['req_by'] = $params['req_by'];
            $create_lead['current_business'] = $params['r_c_b'];

            if($params['ref']){
                $create_lead['req_by'] = base64_decode($ref);
            }

            if(strlen( $params['r_m']) != 10){
                return array('status' => 'failure','code'=>'50','description' => $this->Shop->apiErrors('50'));
            }

            //Only Users, Group ID with 1 can became Retailer or Distrubtor
            $user_exist = $this->User->query("Select group_id from user_groups inner join users ON (users.id = user_groups.user_id) where mobile = '".$create_lead['mobile']."' and user_groups.group_id != 1 ");

            if($user_exist && $create_lead['interest']!='Distributor'){
               return array('status' => 'failure','code'=>'60','description' => $this->Shop->apiErrors('60'));
            }

            if($create_lead['interest'] == 'Retailer'){
               $retailer_exists = $this->User->query("select * from retailers where mobile = '".$create_lead['mobile']."'");
            }else if($create_lead['interest'] == 'Distributor'){
               $distributor_exists = $this->User->query("select * from users us join distributors d on d.user_id = us.id where mobile = '".$create_lead['mobile']."' ");
            }else{
                return array('status' => 'failure','code'=>'58','description' => $this->Shop->apiErrors('58'));
            }

            if($retailer_exists){
                return array('status' => 'failure','code'=>'52','description' => $this->Shop->apiErrors('52'));
            }

            if($distributor_exists){
                return array('status' => 'failure','code'=>'53','description' => $this->Shop->apiErrors('53'));
            }

            $lead_exists = $this->User->query("select * from leads where phone = '".$create_lead['mobile']."'");
            if(empty($lead_exists) || (!empty($lead_exists) && $create_lead['interest']=='Distributor' && $lead_exists[0]['leads']['interest']!='Distributor' )){

                    //$create_lead['req_by'] = $params['req_by'];
                    $redis = $this->Shop->redis_connect();
                    foreach ($create_lead as $key => $value) {
                       $redis->hSet('Retailers_Distributors_Leads_'.$create_lead['mobile'], $key, $value);
                    }
                    $data = $this->sendOTPToRetDistLeads($create_lead);
                    return $data;
            }else{
                return array('status' => 'failure','code'=>'51','description' => $this->Shop->apiErrors('51'));
            }


        }

        function createRetDistNewLeads2($params){
            $create_lead['name'] = $params['r_n'];
            $create_lead['mobile'] = $params['r_m'];
            $create_lead['email'] = $params['r_e'];
            $create_lead['pin_code'] = $params['r_p'];
            $create_lead['shop_name'] = $params['r_s_n'];
            $create_lead['interest'] = $params['reg_i'];
//            $create_lead['req_by'] = $params['req_by'];
            $create_lead['current_business'] = $params['r_c_b'];
            $create_lead['lead_source'] = $params['lead_source'];
            $create_lead['lead_campaign'] = $params['lead_campaign'];
            $create_lead['longitude'] = $params['r_ln'];
            $create_lead['latitude'] = $params['r_lt'];
            $create_lead['uuid'] = $params['uuid'];
            $create_lead['device_type'] = $params['device_type'];
            $create_lead['app_type'] = $params['lead_source'];
            $create_lead['app_name'] = $params['app_name'];

            Configure::load('platform');
            $lead_states_mapping = Configure::read('lead_state_mapping');
            $lead_source = Configure::read('lead_source');
            $create_lead['lead_state'] = isset($lead_states_mapping[$params['lead_source']])?$lead_states_mapping[$params['lead_source']]:3;
            $create_lead['lead_source'] = isset($lead_source[$params['lead_source']])?$lead_source[$params['lead_source']]:'';

            $validate_mobile = $this->General->mobileValidate($params['r_m']);

            if($validate_mobile != '1')
            {
                $get_state_city = "SELECT p.state_id,p.city_id,gs.name AS state,gc.name AS city "
                                . "FROM pincodes p "
                                . "JOIN geo_locations gs "
                                . "ON (p.state_id = gs.id AND gs.parent_id IS NULL) "
                                . "JOIN geo_locations gc "
                                . "ON (p.city_id = gc.id AND gc.parent_id = gs.id) "
                                . "WHERE pincode = '".$params['r_p']."' "
                                . "GROUP BY pincode";

                $state_city = $this->User->query($get_state_city);

                if(!empty($state_city))
                {
                    $create_lead['state'] = $state_city[0]['gs']['state'];
                    $create_lead['city'] = $state_city[0]['gc']['city'];
                }
                else
                {
                    $area = $this->General->getAreaByLatLong($params['r_ln'],$params['r_lt']);
                    $create_lead['state'] = $area['state_name'];
                    $create_lead['city'] = $area['city_name'];
                }

                if($params['ref']){
                    $create_lead['req_by'] = base64_decode($ref);
                }

                if(strlen( $params['r_m']) != 10){
                    return array('status' => 'failure','code'=>'50','description' => $this->Shop->apiErrors('50'));
                }

                //Only Users, Group ID with 1 can became Retailer or Distrubtor
                $user_exist = $this->User->query("Select group_id from user_groups inner join users ON (users.id = user_groups.user_id) where mobile = '".$create_lead['mobile']."' and user_groups.group_id != 1 ");

                if($user_exist && $create_lead['interest']!='Distributor'){
                   return array('status' => 'failure','code'=>'60','description' => $this->Shop->apiErrors('60'));
                }

                if($create_lead['interest'] == 'Retailer'){
                   $retailer_exists = $this->User->query("select * from retailers where mobile = '".$create_lead['mobile']."'");
                }else if($create_lead['interest'] == 'Distributor'){
                   $distributor_exists = $this->User->query("select * from users us join distributors d on d.user_id = us.id where mobile = '".$create_lead['mobile']."' ");
                }else{
                    return array('status' => 'failure','code'=>'58','description' => $this->Shop->apiErrors('58'));
                }

                if($retailer_exists){
                    return array('status' => 'failure','code'=>'52','description' => $this->Shop->apiErrors('52'));
                }

                if($distributor_exists){
                    return array('status' => 'failure','code'=>'53','description' => $this->Shop->apiErrors('53'));
                }

                //$lead_exists = $this->User->query("select * from leads where phone = '".$create_lead['mobile']."'");
                if($create_lead['interest'] != 'Retailer')
                $lead_exists2 = $this->User->query("select * from leads_new where phone = '".$create_lead['mobile']."'");
                $signup_count = $lead_exists2[0]['leads_new']['signup_count'] + 1;

                if((empty($lead_exists) || (!empty($lead_exists) && $create_lead['interest']=='Distributor' && $lead_exists[0]['leads']['interest']!='Distributor' )) && (empty($lead_exists2) || (!empty($lead_exists2) && $create_lead['interest']=='Distributor' && $lead_exists2[0]['leads_new']['interest']!=2 ))){
                        $redis = $this->Shop->redis_connect();
                        foreach ($create_lead as $key => $value) {
                           $redis->hSet('Retailers_Distributors_Leads_'.$create_lead['mobile'], $key, $value);
                        }
                        $data = $this->sendOTPToRetDistLeads($create_lead);
                        return $data;
                }
                elseif(((!empty($lead_exists) && $create_lead['interest']=='Distributor' && $lead_exists[0]['leads']['interest']=='Distributor' )) || ((!empty($lead_exists2) && $create_lead['interest']=='Distributor' && $lead_exists2[0]['leads_new']['interest']==2 )))
                {
                    $update_signup_count = $this->User->query("UPDATE leads_new SET signup_count = '$signup_count' WHERE phone = '".$create_lead['mobile']."' ");

                    return array('status' => 'failure','code'=>'65','description' => $this->Shop->apiErrors('65'));
                }
                else{
                    return array('status' => 'failure','code'=>'69','description' => $this->Shop->apiErrors('69'));
                }
            }
            else
            {
                return array('status' => 'failure','code'=>'67','description' => $this->Shop->apiErrors('67'));
            }
        }


        function verifyRetDistNewLeads($params){

            $lead_mobile = $params['r_m'];
            $otp = $params['otp'];
            $Intreset = trim($params['reg_i']);
            $pin = $params['pin'];
            $confrimPin  = $params['confirm_pin'];


            if($Intreset=='Retailer'){
                if($pin!=$confrimPin){
                     return array('status' => 'failure','code'=>'57','description' => $this->Shop->apiErrors('57'));
                }

            }

            if((strlen($otp) != 6) || (strlen($lead_mobile) != 10)){
                return array('status' => 'failure','code'=>'57','description' => $this->Shop->apiErrors('57'));
            }

            if(trim($lead_mobile)){

                $retailer_exists = $this->User->query("select * from retailers where mobile = '".$lead_mobile."'");
                if($retailer_exists){
                    return array('status' => 'failure','code'=>'52','description' => $this->Shop->apiErrors('52'));
                }

                $distributor_exists =  $this->User->query("select * from users us join distributors d on d.user_id = us.id where mobile = '".$lead_mobile."' ");
                if($distributor_exists){
                    return array('status' => 'failure','code'=>'53','description' => $this->Shop->apiErrors('53'));
                }

                $leads_exists = $this->User->query("select * from leads where phone = '".$lead_mobile."'");

                    if(empty($leads_exists)){

                      if($otp == $this->Shop->getMemcache("otp_RetDist_Registration_$lead_mobile")){
                            $this->Shop->delMemcache("otp_RetDist_Registration_$lead_mobile");

                            $redis = $this->Shop->redis_connect();
                            $create_lead =  $redis->hgetall('Retailers_Distributors_Leads_'.$lead_mobile);

                            $this->User->query("insert into leads
                                (interest, name, shop_name, email, phone, city, state, messages, area, pin_code, business_nature_type , business_area_dist_of, dist_emp_strength , req_by, date, timestamp,current_business)
                                values ('".$create_lead['interest']."', '".$create_lead['name']."', '".$create_lead['shop_name']."',
                                        '".$create_lead['email']."',
                                        '$lead_mobile', '', '',
                                        '', '', '".$create_lead['pin_code']."',
                                        '', '', '',
                                        '".$create_lead['req_by']."', '".date('Y-m-d')."', '".date('Y-m-d H:i:s')."','".$create_lead['current_business']."')");

                         $key='Retailers_Distributors_Leads_'.$lead_mobile;

                        array_map(function($a) use ($redis,$key) {$redis->hdel($key,$a);},array_keys($create_lead));

                        $subject = "I want to become a ".$create_lead['interest'];

        		$body = "
        		</br> Contact       : ".$create_lead['mobile']."
        		</br> Interested In : ".$create_lead['interest']."
        		</br> Source        : ".$create_lead['req_by'];

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
        		$columns['mx_Area'] = $create_lead['area'];
        		$columns['mx_Pin_Code'] = $create_lead['pin_code'];
        		$columns['mx_Messages'] = $create_lead['messages'];
        		$columns['mx_Date'] = date('Y-m-d');
        		$columns['mx_Timestamp'] = date('Y-m-d H:i:s');
        		$columns['Source'] = $create_lead['req_by'];
        		$columns['mx_Interest'] = $create_lead['interest'];

        		$this->General->logData('/mnt/logs/'.$filename, json_encode($columns));

        		App::import('Controller', 'Leadmanagement');
                        $obj = new LeadmanagementController;
        		$obj->constructClasses();
        		$obj->createLead($columns);

                        }


                            $MsgTemplate = $this->General->LoadApiBalance();
                            $message =  $MsgTemplate['Create_RetDist_Leads_MSG'];
                            $this->General->sendMessage($create_lead['mobile'], $message, "payone");

                            if(trim($create_lead['interest']) == "Retailer"){

                                $retailer = $this->User->query("select interest, name , shop_name , email, state , city ,fax, messages, area , pin_code,phone , timestamp, req_by, date  from leads where phone = '$lead_mobile'");

                                $retailer = $retailer[0]['leads'];
                                $retailer['distributor_user_id'] = 8;
                                $retailer['api_flow'] = "verify_lead";
                                $retailer['name'] = $lead_mobile;
                                $retailer['r_u_d'] = $params['r_u_d'];
                                $retailer['pin'] = $pin;

                                $this->createRetailer($retailer);
                                return array('status' => 'success', 'code'=>'55', 'description' => $this->Shop->apiErrors('55'));

                            }else if(trim($create_lead['interest']) == "Distributor"){

                                return array('status' => 'success', 'code'=>'55', 'description' => $this->Shop->apiErrors('55'));
                            }
                            return array('status' => 'failure','code'=>'56','description' => $this->Shop->apiErrors('56'));
                        }
                        else{
                            return array('status' => 'failure','code'=>'54','description' => $this->Shop->apiErrors('54'));
                        }
                    }else{
                        return array('status' => 'failure','code'=>'51','description' => $this->Shop->apiErrors('51'));
                    }

            }
            else
                    return array('status' => 'failure','code'=>'50','description' => "Mobile ".$this->Shop->apiErrors('50'));
        }

        function verifyRetDistNewLeads2($params){

            $lead_mobile = $params['r_m'];
            $otp = $params['otp'];
            $interest = trim($params['reg_i']);
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

                $retailer_exists = $this->User->query("select * from retailers where mobile = '".$lead_mobile."'");
                if($retailer_exists){
                    return array('status' => 'failure','code'=>'52','description' => $this->Shop->apiErrors('52'));
                }

                $distributor_exists =  $this->User->query("select * from users us join distributors d on d.user_id = us.id where mobile = '".$lead_mobile."' ");
                if($distributor_exists){
                    return array('status' => 'failure','code'=>'53','description' => $this->Shop->apiErrors('53'));
                }

                if($interest != 'Retailer')
                $leads_exists = $this->User->query("select * from leads_new where phone = '".$lead_mobile."'");

                    if(empty($leads_exists)){

                      if($otp == $this->Shop->getMemcache("otp_RetDist_Registration_$lead_mobile")){
                            $this->Shop->delMemcache("otp_RetDist_Registration_$lead_mobile");
//                            $token = $this->General->generatePassword(10);
                            $token = md5($lead_mobile);
                            $redis = $this->Shop->redis_connect();
                            $create_lead =  $redis->hgetall('Retailers_Distributors_Leads_'.$lead_mobile);
                            $lead_status = ($create_lead['interest'] == "Retailer")?17:16;
                            $sub_status = ($create_lead['interest'] == "Retailer")?23:20;
                            $interest = ($create_lead['interest'] == "Retailer")?1:2;

                            $this->User->query("insert into leads_new
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

                                $retailer = $this->User->query("select interest, name , shop_name , email, state , city , pin_code,phone , lead_timestamp, lead_source, creation_date  from leads_new where phone = '$lead_mobile'");

                                $retailer = $retailer[0]['leads_new'];

                                // $imp_update_data = array(
                                //     'email' => addslashes($retailer['email']),
                                //     'name' => addslashes($retailer['name']),
                                //     'shopname' => addslashes($retailer['shop_name'])
                                // );

                                $retailer['distributor_user_id'] = 8;
                                $retailer['api_flow'] = "verify_lead";
                                // $retailer['name'] = $lead_mobile;
                                $retailer['name'] = $create_lead['name'];
                                $retailer['r_u_d'] = $params['r_u_d'];
                                $retailer['pin'] = $pin;

                                $register_user = $this->createRetailer($retailer);
                                $this->General->logData('/mnt/logs/'.$filename, "resister_user".json_encode($register_user));
                                if(($register_user['status'] == 'success') && (isset($register_user['description']['User']['Retailer']['user_id'])) && (!empty($register_user['description']['User']['Retailer']['user_id'])))
                                {
                                    $this->General->logData('/mnt/logs/'.$filename, "status :".json_encode($register_user['status'])." user id: ".json_encode($register_user['description']['User']['Retailer']['user_id']));
                                    App::import('Controller', 'Platform');
                                    $p_obj = new PlatformController;
                                    $p_obj->constructClasses();

                                    $user_info['user_id'] = $register_user['description']['User']['Retailer']['user_id'];
                                    $user_info['uuid'] = $create_lead['uuid'];
                                    $user_info['device_type'] = $create_lead['device_type'];
                                    $user_info['app_name'] = $create_lead['app_name'];
                                    if($create_lead['device_type'] != 'web')
                                    {
                                        $p_obj->deviceInfoUpdate($user_info);
                                    }

                                    /** IMP DATA ADDED : START**/
                                    // $this->Shop->updateUserLabelData($user_info['user_id'],$imp_update_data,NULL,0);
                                    /** IMP DATA ADDED : END**/

                                    $MsgTemplate = $this->General->LoadApiBalance();
                                    $message =  $MsgTemplate['Create_Ret_Leads_MSG'];
                                    $this->General->sendMessage($create_lead['mobile'], $message, "payone");


                                    return array('status' => 'success', 'code'=>'55', 'description' => $this->Shop->apiErrors('55'));
                                } else {
                                    $this->User->query("delete from leads_new where phone = '".$lead_mobile."'");
                                    return array('status' => 'failure','code'=>'56','description' => $this->Shop->apiErrors('56'));
                                }

                            }else if(trim($create_lead['interest']) == "Distributor"){

                                return array('status' => 'success', 'code'=>'55', 'description' => $this->Shop->apiErrors('55'));
                            }
                        }
                        else{
                            return array('status' => 'failure','code'=>'54','description' => $this->Shop->apiErrors('54'));
                        }
                    }
                    elseif(!empty($leads_exists) && ($leads_exists[0]['leads_new']['otp_flag'] == 0))
                    {
                        if($otp == $this->Shop->getMemcache("otp_RetDist_Registration_$lead_mobile")){
                            $this->Shop->delMemcache("otp_RetDist_Registration_$lead_mobile");

                            $this->User->query("UPDATE leads_new SET otp_flag = '1' WHERE phone = '$lead_mobile' ");

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

        function authenticateUser($params){


                 $root = isset($_GET['root']) ? $_GET['root'] : "";

                if(!isset($params['mobile']) || empty($params['mobile']) || !isset($params['uuid']) || empty($params['uuid'])) {
                    $returnData =  array('status' => 'failure', 'code' => '28', 'description' => 'Your Mobile number or uuid should not blank');
                    echo  trim($root .'('.json_encode($returnData).')');
                    die;
                 }

                 $app_type = 'recharge_app';

                if(isset($params['user_group_id']) && !empty($params['user_group_id'])){
                    $group_id  = $params['user_group_id'];
                } else {
                    $getUserData = $this->Slaves->query("Select user_groups.group_id from user_groups inner join users ON (users.id = user_groups.user_id) where user_groups.group_id in (".RETAILER.",".DISTRIBUTOR.") AND mobile = '".$params['mobile']."' order by user_groups.group_id asc limit 1");
                    if(!empty($getUserData)){
                        $group_id = $getUserData[0]['user_groups']['group_id'];
                    }
                }
                $this->General->logData("/var/www/html/shops/authenticateuser.txt","in authenticateUser api: ".json_encode($params));

	        if ($this->General->mobileValidate($params['mobile']) == '1') { //mobile no validation
                   $returnData = array('status' => 'failure', 'code' => '28', 'description' => $this->Shop->errors(28));
                   echo  trim($root .'('.json_encode($returnData).')');
                   die;
                }

                if((round($params['longitude'],1) == '77.0' && round($params['latitude'],1) == '20.0') || ($params['longitude'] == '77' && $params['latitude'] == '20')){
                    $params['longitude'] = '';
                    $params['latitude'] = '';
                }

                $server_lat        = (isset($_SERVER['GEOIP_LATITUDE']) && !empty($_SERVER['GEOIP_LATITUDE'])) ? $_SERVER['GEOIP_LATITUDE'] : "" ;
                $server_long        = (isset($_SERVER['GEOIP_LONGITUDE']) && !empty($_SERVER['GEOIP_LONGITUDE'])) ? $_SERVER['GEOIP_LONGITUDE'] : "" ;

                $uuid        = empty($params['uuid']) ? "" : $params['uuid'];
                $gcm_reg_id  = empty($params['gcm_reg_id']) ? "" : $params['gcm_reg_id'];
                $device_type  = empty($params['device_type']) ? "" : $params['device_type'];
                $longitude   = empty($params['longitude']) ? $server_long : $params['longitude'];
                $latitude    = empty($params['latitude']) ? $server_lat : $params['latitude'];
                $location_src = empty($params['location_src']) ? "" : $params['location_src'];
                $device_ver   = empty($params['version']) ? "" : $params['version'];
                $app_version_code    = empty($params['version_code']) ? "" : $params['version_code'];
                $device_manufacturer = empty($params['manufacturer']) ? "" : $params['manufacturer'];
                $area_id = $this->General->getAreaIDByLatLong($longitude,$latitude);
                $password = $this->Auth->password($params['password']);
                $mobile = $params['mobile'];

                $lat_long_distance = 0;

                if(!(isset($params['device_type']) && trim($params['device_type']) == 'java')){
			session_regenerate_id(true);
		}

                 $verify = $this->checkAuthenticateDeviceType($params['device_type']);
                    //for web only
                    if($verify == 9){
                        $gcm_reg_id = $params['uuid'];
                        $uuid = $params['uuid'];
                    }else if($verify == 0){ //if device_type is not found
                       $returnData = array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                        echo trim($root .'('.json_encode($returnData).')');
                       die;
                    }

                     if(isset($params['otp_verify_flag']) && $params['otp_verify_flag'] == 1){

                          $UserData = $this->Slaves->query("SELECT users.*,user_groups.group_id
                                                           FROM users inner join user_groups ON (users.id = user_groups.user_id)
                                                           WHERE mobile = '".$mobile."' and user_groups.group_id = '".$group_id."'"
                                                         );

                       } else {
                          $UserData = $this->Slaves->query("SELECT users.*,user_groups.group_id
                                                          FROM users inner join user_groups ON (users.id = user_groups.user_id)
                                                          WHERE mobile = '".$mobile."' AND password = '".$password."' and user_groups.group_id = '".$group_id."'"
                                                         );
                        }


                        if(empty($UserData)){
                             $returnData = array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                             echo trim($root .'('.json_encode($returnData).')');
                             die;

                        }
                        else if($UserData[0]['users']['active_flag'] == 0){
                            return array('status' => 'failure','code'=>'404','description' =>$this->Shop->errors(404));
                        }

                         if($UserData[0]['user_groups']['group_id']==RETAILER && !empty($UserData)){

                            if(isset($params['version_code']) && !empty($app_version_code)){

                            $update_version_code = $this->General->findVar("pay1_merchant_update_version");

                            if($update_version_code){
                                    if($app_version_code < $update_version_code){
                                            $returnData = array("status" => "failure",
                                                          "code" => "48",
                                                          "forced_upgrade_flag" => "1",
                                                           "description" => $this->Shop->errors(48)
                                                        );
                                          echo trim($root .'('.json_encode($returnData).')');
                                            die;
                                    }
                            }
                          }

                          if(isset($params['otp_verify_flag']) && $params['otp_verify_flag'] == 1){
                           $getUserProfileData = $this->Slaves->query("SELECT users.id, users.balance,users.active_flag,users.passflag, user_profile.id, gcm_reg_id,uuid,longitude,latitude,location_src,device_type FROM users left join user_profile on (user_profile.user_id = users.id and user_profile.uuid = '".$uuid."') inner join user_groups ON (users.id = user_groups.user_id) WHERE mobile = '".$params['mobile']."' AND user_groups.group_id= '".RETAILER."' ");
                           } else {

                          $getUserProfileData = $this->Slaves->query("SELECT users.id, users.balance,users.passflag, users.active_flag,user_profile.id, gcm_reg_id,
                                                                       uuid,longitude,latitude,location_src,device_type
                                                                       FROM users
                                                                       LEFT JOIN user_profile ON (user_profile.user_id = users.id and user_profile.uuid = '".$uuid."' AND user_profile.app_type='$app_type')
                                                                       WHERE mobile = '".$mobile."'
                                                                       AND password='$password'"
                                                                    );

                         $this->General->logData("/var/www/html/shops/authenticateuser.txt","in authenticateUser api: after getuserprofiledata".  json_encode($getUserProfileData));

                          if( ($longitude && $latitude) && ($getUserProfileData[0]['users']['id'] && $getUserProfileData[0]['user_profile']['id']) ){

                            $user_last_lat_long = $this->Slaves->query("SELECT longitude, latitude
                                                                        FROM `user_profile`
                                                                        WHERE `user_id` = '".$getUserProfileData[0]['users']['id']."'
                                                                        AND  `uuid` = '".$uuid."' AND user_profile.app_type='$app_type'
                                                                        ORDER BY updated desc
                                                                        limit 0,1"
                                                                      );

                            if($user_last_lat_long['0']['user_profile']['longitude'] && $user_last_lat_long['0']['user_profile']['longitude']){

                            $last_longitude =  $user_last_lat_long['0']['user_profile']['longitude'];
                            $last_latitude =  $user_last_lat_long['0']['user_profile']['latitude'];
                        //Lat Long distance more than 500 KM
                            $lat_long_distance = $this->General->lat_long_distance($latitude,$longitude,$last_latitude,$last_longitude);
                        //$lat_long_distance = 0;
                              }

                           }
                   }

                           $uuid_to_be_checked = (isset($params['device_id']) && !empty($params['device_id'])) ? $params['device_id'] : (isset($params['uuid']) && !empty($params['uuid']) ? $params['uuid'] : "");
                           $uuid_data_of_user = $this->Slaves->query("SELECT up.uuid as device_id
                                                                       FROM users as u
                                                                       LEFT JOIN user_profile up ON u.id=up.user_id
                                                                       WHERE u.mobile = '".$mobile."'
                                                                       AND up.uuid =  '".$uuid_to_be_checked."' AND up.app_type='$app_type'");
                           $params['uuid_data_of_user'] = $uuid_data_of_user;

                            if(empty($getUserProfileData[0]['users']['id'])){
                                    $this->General->block_attacker(true,$params);
                                    $returnData = array('status' => 'failure','code'=>'28','description' =>$this->Shop->errors(28));
                                    echo trim($root .'('.json_encode($returnData).')');
                                    die;

                            }
                            if($getUserProfileData[0]['users']['active_flag'] == 0){
                                $returnData = array('status' => 'failure','code'=>'404','description' =>$this->Shop->errors(404));
                                echo trim($root .'('.json_encode($returnData).')');
                                die;

                            }
                            else if(empty($getUserProfileData[0]['user_profile']['id'])){

                            $this->General->block_attacker(false,$params,true);//reset blocker counter

                    //for Auto Signup using pay1 partner OTP not sent to users
                            if(isset($params['type']) && $params['type'] === 'a_auth'){
                                $this->User->query("INSERT INTO `shops`.`user_profile` (`id`,`user_id`, `gcm_reg_id`, `uuid`, `longitude`, `latitude`, `location_src` , `area_id`,`device_type` ,`version` , `manufacturer`, `created`, `updated`,`date`) "
                                    . "VALUES (NULL, ".$getUserProfileData['0']['users']['id'].", '$gcm_reg_id', '$uuid', '$longitude', '$latitude', '".$location_src."' ,'$area_id','".$device_type."' ,'".$device_ver."' ,'".$device_manufacturer."' ,'".  date("Y-m-d H:i:s")."', '".  date("Y-m-d H:i:s")."','".date('Y-m-d')."');");

                            $userProfile = $this->User->query("SELECT id ,user_id ,gcm_reg_id,uuid,longitude,latitude,location_src,device_type,created
                                                                FROM user_profile
                                                                WHERE user_id='".$getUserProfileData['0']['users']['id']."'
                                                                AND uuid = '".$uuid."' AND user_profile.app_type='$app_type'"
                                                             );

                             $this->General->logData("/var/www/html/shops/authenticateuser.txt","in authenticateUser api: inside update user_profile ");

                            }else{
                             $otp_data =  $this->sendOTPToUserDeviceMapping($params['mobile'],$params['otp_via_call'],$getUserProfileData['0']['users']['id'],$lat_long_distance);
                             $returnData =  array(
                                'status'      			=> 'successOTP',
                                'description' 			=> $otp_data,
                                'passFlag'    			=> $getUserProfileData['0']['users']['passflag'],
                                'vmnList'     			=> $this->Shop->getVMNList('fromLogin'),
                            );
                            echo trim($root .'('.json_encode($returnData).')');
                             die;

                    }
                  }else{
                      if($verify == 9){//for web users only
                            if(empty($getUserProfileData[0]['user_profile']['longitude']) || empty($getUserProfileData[0]['user_profile']['latitude'])){//if existing lat,long is empty then
                                $this->User->query("UPDATE `shops`.`user_profile` set `gcm_reg_id`= '$gcm_reg_id',location_src='$location_src' , `longitude` = '$longitude',  `latitude` = '$latitude', `area_id` = '$area_id', `device_type` = '$device_type', `updated` = '".  date("Y-m-d H:i:s")."',`date`='".date('Y-m-d')."'  where user_id = ".$getUserProfileData['0']['users']['id'] . " AND uuid = '$uuid' AND user_profile.app_type='$app_type'");
                            }else{//if lat , long is already exist then don't update
                                    $this->User->query("UPDATE `shops`.`user_profile` set `gcm_reg_id`= '$gcm_reg_id',location_src='$location_src' , `area_id` = '$area_id',  `device_type` = '$device_type' , `updated` = '".  date("Y-m-d H:i:s")."',`date`='".date('Y-m-d')."'  where user_id = ".$getUserProfileData['0']['users']['id'] . " AND uuid = '$uuid' AND user_profile.app_type='$app_type'");
                            }
                    }else{
                            $this->User->query("UPDATE `shops`.`user_profile` set `gcm_reg_id`= '$gcm_reg_id',location_src='$location_src' , `longitude` = '$longitude',  `latitude` = '$latitude', `area_id` = '$area_id', `device_type` = '$device_type' , `version` = '".$device_ver."' , `manufacturer` ='".$device_manufacturer."',`updated` = '".  date("Y-m-d H:i:s")."',`date`='".date('Y-m-d')."'  where user_id = ".$getUserProfileData['0']['users']['id'] . " AND uuid = '$uuid' AND user_profile.app_type='$app_type'");
                    }

                     }


                    $info = $this->Shop->getShopData($getUserProfileData['0']['users']['id'],RETAILER);

                if($verify == 3){  //for java
                    $info['version'] = $this->General->findVar('java_version');
                }



                $pg_check = $this->Slaves->query("SELECT active_flag,service_charge FROM pg_checks WHERE distributor_id = '".$info['parent_id']."'");

                $info['balance'] = $getUserProfileData['0']['users']['balance'];

                $data['Retailer'] = $info;

                $info['User']['group_id'] = RETAILER;
                $info['User']['group_ids'] = RETAILER;
                $info['User']['id'] = $getUserProfileData['0']['users']['id'];
                $info['User']['mobile'] = $params['mobile'];
                $info['User']['passflag'] = $getUserProfileData['0']['users']['passflag'];
                $userProfileID = $getUserProfileData['0']['user_profile']['id'];
                $info['User']['profile_id'] = $userProfileID;
                $this->Session->write('Auth',$info);

                $this->Shop->setRetailerTrnsDetails($params['mobile'],array('trans_type'=>$device_type,'notification_key'=>$gcm_reg_id));

                $data['Retailer']['passflag'] = $getUserProfileData['0']['users']['passflag'];
                $data['Retailer']['profile_id'] = $userProfileID;
                $data['Retailer']['disApp'] = '';
                $data['Retailer']['uuid'] = $uuid;
                $data['Retailer']['pg_flag'] = (empty($pg_check)) ? 0 : $pg_check['0']['pg_checks']['active_flag'];
                $data['Retailer']['vmnList'] = $this->Shop->getVMNList('fromLogin');
                $data['Retailer']['service_charge'] = (empty($pg_check)) ? 0 : $pg_check['0']['pg_checks']['service_charge'];
                $data['Retailer']['min_amount_for_prompt'] = $this->General->findVar("min_amount_for_prompt");
                $data['Retailer']['CAKEPHP'] = $this->Session->id();

                    $returnData =  array(
                        'status'      			=> 'success',
                        'description' 			=> $data,
                        'type' => 'Retailer'

                    );

                    $this->General->logData("/var/www/html/shops/authenticateuser.txt","in authenticateUser api: ".  json_encode($returnData));
                    echo trim($root .'('.json_encode($returnData).')');
                    die;



                }
                 else if($UserData[0]['user_groups']['group_id']==DISTRIBUTOR && !empty($UserData)){
                    if(isset($params['version_code']) && !empty($app_version_code)){

                    $update_version_code = $this->General->findVar("pay1_distributor_update_version");

                     if($update_version_code){

                        if($app_version_code < $update_version_code){

                            $returnData = array("status" => "failure", "code" => "48", "forced_upgrade_flag" => "1", "description" => $this->Shop->errors(48));
                            echo trim($root .'('.json_encode($returnData).')');
                            die;
                        }
                    }
                   }

                $this->Shop->setSalesmanDeviceData($mobile, array('trans_type' => $device_type, 'notification_key' => $gcm_reg_id));

                $info = $this->Shop->getShopData($UserData[0]['users']['id'],DISTRIBUTOR);

                $info['balance'] = $UserData[0]['users']['balance'];

                if($gcm_reg_id)
			$this->User->query("update salesmen set gcm_reg_id = '".$gcm_reg_id."' where id = '".$info['id']."'");

                $salesmen = $this->Slaves->query("select s.*,group_concat(sub.name) as subs
				from salesmen s
				left join salesmen_subarea ssa on(ssa.salesmen_id = s.id)
				left join subarea sub on(sub.id = ssa.subarea_id)
				WHERE s.dist_id = ".$info['id']."
				AND s.mobile != '".$info['User']['mobile']."'
				AND s.active_flag = 1
				group by s.id order by s.id asc  ");

                $data['Distributor'] = $info;
                $data['Distributor']['group_id'] = DISTRIBUTOR;
                $data['Distributor']['passFlag'] = $UserData['0']['users']['passflag'];
                $data['Distributor']['salesmen'] = $salesmen;
                $data['Distributor']['CAKEPHP'] = $this->Session->id();

		$info['User']['group_id'] = DISTRIBUTOR;
		$info['User']['group_ids'] = DISTRIBUTOR;
		$info['User']['id'] = $UserData['0']['users']['id'];
		$info['User']['mobile'] = $mobile;
		$info['User']['passflag'] = $UserData['0']['users']['passflag'];
		$info['User']['auth_mobile'] = $uuid;
		$info['vars']['trial_period'] = $this->General->findVar("trial_period");

		$this->Session->write('Auth', $info);

                 $returnData =  array(
                        'status'=> 'success',
                         'description' => $data,
                          'type' => 'Distributor'

                    );

                 echo trim($root .'('.json_encode($returnData).')');
                  die;

              } else {

                   $UserData = $this->Slaves->query("SELECT salesmen.*,users.balance,users.active_flag FROM  salesmen inner join users ON (users.id = salesmen.user_id)
                                                          WHERE users.mobile = '".$mobile."'
                                                          AND users.password = '".$password."'
                                                          AND salesmen.active_flag = 1"
                                                          );

                  if($UserData[0]['users']['active_flag'] == 0){
                     $returnData =  array('status' => 'failure','code'=>'404','description' => $this->Shop->apiErrors('404'));
                     echo trim($root .'('.json_encode($returnData).')');
                     die;
                  }
                  else if(!empty($UserData)){
                     $passflag  =  $UserData[0]['salesmen']['passflag'];
                     $salesman = $UserData[0]['salesmen'];
                     $distributor_d = $this->Slaves->query("SELECT system_used FROM distributors WHERE id = ".$salesman['dist_id']);

                     $info = $salesman;
                     $info['balance'] = $UserData[0]['users']['balance'];
                     $info['system_used'] = $distributor_d[0]['distributors']['system_used'];
	             $info['User']['group_id'] = SALESMAN;
		     $info['User']['id'] = 0;
		     $info['User']['mobile'] = $mobile;
		     $info['vars']['trial_period'] = $this->General->findVar("trial_period");
                     $this->Shop->setSalesmanDeviceData($mobile, array('trans_type' => $device_type, 'notification_key' => $gcm_reg_id));

                     if($gcm_reg_id)
				$this->User->query("update salesmen set gcm_reg_id = '".$gcm_reg_id."' where id = '".$salesman['id']."'");

                     $this->Session->write('Auth', $info);

                     $data['Salesmen'] = $salesman;
                     $data['Salesmen']['CAKEPHP'] = $this->Session->id();

                     $returnData =  array(
				'status' => 'success',
				'description' 	=> $data,
                                'type' => 'Salesmen'

			);

                      echo trim($root .'('.json_encode($returnData).')');
                      die;

                  }   else {
                      $returnData =  array('status' => 'failure','code'=>'28','description' => $this->Shop->apiErrors('28'));
                       echo trim($root .'('.json_encode($returnData).')');
                       die;
                  }

              }




        }

        function sendAppLink($params){
            $mobile = $params['mobile'];
            $retailerLink = 'https://play.google.com/store/apps/details?id=com.mindsarray.pay1&hl=en';
            $distributorLink = 'https://play.google.com/store/apps/details?id=com.mindsarray.pay1distributor';
            $message = "Please Download our Retailer  App \n";
             $message = $message.$retailerLink."\n or Distributor App ".$distributorLink;
            $this->General->sendMessage($mobile, $message, 'payone', null);
            $this->autoRender = false;
        }


        /**
         *
         */
       /* function getUserProfile(){
            $session_data = $this->Session->read('Auth');
            if(isset($session_data['User']['group_id']) && $session_data['User']['group_id'] == 6){
                $user_id = $session_data['User']['id'];
                $user_detail_query = "SELECT r.name as retailer_name, "
                    ."r.alternate_number as alternate_mob_number, "
                    ."r.email as email_id, "
                    ."r.shopname as shop_name, "
                    ."r.shop_structure as ownership_type, "
                    ."r.shop_type as nature_of_business, "
                    ."rl.longitude as lat, "
                    ."rl.latitude as long, "
                    ."r.address as address_l1, "
                    ."la.name as address_l2, "
                    ."la.pincode as pincode, "
                    ."lc.name as city, "
                    ."ls.name as state "
                  ."FROM retailers as r "
                  ."LEFT JOIN retailers_location as rl ON (r.id = rl.retailer_id) "
                  ."LEFT JOIN locator_area as la ON (rl.area_id = la.id) "
                  ."LEFT JOIN locator_city as lc ON (la.city_id = lc.id) "
                  ."LEFT JOIN locator_state as ls ON (lc.state_id = ls.id) "
                   . "WHERE "
                    ."r.user_id = '".$user_id."'";
                $UserDetails = $this->Slaves->query($user_detail_query);
                print_r($UserDetails);
                $returnData = array('status'=>'success','code'=>200,'description'=>$UserDetails);
                echo trim($root .'('.json_encode($returnData).')');
                die;
            }
        } */

        function getDistributorsData($params)
        {
            $this->autoRender = false;
            $mobile = $params['mobile'];
            $token = $params['token'];
            $dist_lead_data = $this->User->query("SELECT * FROM leads_new WHERE phone = '$mobile' AND token = '$token' AND interest = '2' ");
            $signup_count = $dist_lead_data[0]['leads_new']['signup_count'];

            if(!empty($dist_lead_data) && $signup_count == 1)
            {
                return array('status' => 'success','description'=>$dist_lead_data[0]['leads_new']);
            }
            elseif(!empty($dist_lead_data) && $signup_count != 1)
            {
                return array('status' => 'failure','code'=>'68','description' => $this->Shop->apiErrors('68'));
            }
            else
            {
                return array('status' => 'failure','code'=>'63','description' => $this->Shop->apiErrors('63'));
            }

        }

        function submitDistributorApplicationForm($params)
        {
            $this->autoRender = false;
            $mobile = $params['mobile'];
            $no_of_curr_business_years = $params['no_of_curr_business_years'];
            $no_of_salesmen = $params['no_of_salesmen'];
            $no_of_retailers = $params['no_of_retailers'];
            $curr_turnover_per_month = $params['curr_turnover_per_month'];
            $gst_flag = $params['gst_flag'];
            $has_curr_account = $params['has_curr_account'];
            $token = $params['token'];
            $interested_products = implode(',',$params['interested_products']);
            $dist_lead_data = $this->User->query("SELECT * FROM leads_new WHERE phone = '$mobile' AND token = '$token' AND interest = '2' ");
            $signup_count = $dist_lead_data[0]['leads_new']['signup_count'] + 1;

            $update_dist_form_data = $this->User->query("UPDATE leads_new "
                                                    . "SET no_of_curr_business_years = '$no_of_curr_business_years',"
                                                    . "no_of_salesmen = '$no_of_salesmen',"
                                                    . "no_of_retailers = '$no_of_retailers',"
                                                    . "curr_turnover_per_month = '$curr_turnover_per_month',"
                                                    . "gst_flag = '$gst_flag',"
                                                    . "has_curr_account = '$has_curr_account',"
                                                    . "interested_products = '$interested_products',"
                                                    . "updated_at = '".date('Y-m-d H:i:s')."',"
                                                    . "signup_count = '$signup_count' "
                                                    . "WHERE phone = '$mobile' "
                                                    . "AND token = '$token' ");

            if($update_dist_form_data)
            {
                $params['zoho_id'] = $this->Shop->getMemcache("zoho_lead_id_$mobile");
                $this->Shop->updateZohoLeads($params);
                //for facebook leads as their mobile no is not otp verified
                $check_otp_flag = $this->User->query("SELECT otp_flag "
                                                    . "FROM leads_new "
                                                    . "WHERE phone = '$mobile' ");

                if($check_otp_flag[0]['leads_new']['otp_flag'] == 0)
                {
                    $dist_data['interest'] = 'Distributor';
                    $dist_data['mobile'] = $mobile;
                    $data = $this->sendOTPToRetDistLeads($dist_data);
                    return $data;
                }
                else
                {
                    $MsgTemplate = $this->General->LoadApiBalance();
                    $message =  $MsgTemplate['Distributor_Lead_Registered_MSG'];
                    $this->General->sendMessage($mobile, $message, "payone");
                    return array('status' => 'success','code'=>'64','description' => $this->Shop->apiErrors('64'));
                }
            }
            else
            {
                return array('status' => 'failure','code'=>'49','description' => $this->Shop->apiErrors('49'));
            }

        }


        public function zohowebhooks()
        {
            $this->autoRender=false;

           $this->General->logData('zoholeadshooks.log',json_encode(array($this->params['url'])));

           $lead_states=$this->Slaves->query("Select id from lead_attributes_values where  lead_values = '{$this->params['url']['lead_status']}' limit 1 ");

           $lead_status=!empty($lead_states)?$lead_states[0]['lead_attributes_values']['id']:'0';

           $lead_sources=$this->Slaves->query("Select id from lead_attributes_values where  lead_values = '{$this->params['url']['lead_source']}' limit 1 ");

           $lead_source=!empty($lead_sources)?$lead_sources[0]['lead_attributes_values']['id']:'0';

           $interest_in=$this->params['url']['interested_in']=='Distributor'?'2':'1';

           $sql="update leads_new  set name='{$this->params['url']['lead_name']}',interest='{$interest_in}',lead_state='{$lead_status}',lead_source='{$lead_source}' where  phone='{$this->params['url']['mobile']}'   ";

           $this->General->logData('zoholeadshooks.log',$sql);

           $result=$this->User->query($sql);

           $this->General->logData('zoholeadshooks.log',json_encode(array($result)));

        }

        function updateNewsletterFlag() {

            $res = $this->User->query("UPDATE retailers SET newsletter = '1' WHERE mobile = '{$_GET['retailer_mobile']}'");
            $this->displayWeb($res, 'json');

        }

}
