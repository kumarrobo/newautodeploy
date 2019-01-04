<?php
class UssdApisController extends AppController {
	var $name = 'UssdApis';
	var $helpers = array (
			'Html',
			'Ajax',
			'Javascript',
			'Minify',
			'Paginator' 
	);
	var $components = array (
			'RequestHandler',
			'General' 
	);
	var $uses = array (
			'User',
			'Retailer',
			'AppReqLog' 
	);
	var $validFormats = array (
			'xml',
			'json' 
	);
	var $appVersion = 1;
	var $validRecTypes = array (
			'flexi',
			'voucher' 
	);
	function beforeFilter() {
		parent::beforeFilter ();
		$this->Auth->allow ( '*' );
	}
	
	/**
	 *
	 * @param string $mobile:
	 *        	number in which the USSD session has to be started, In table insertion 10 digit mobile number is used, while
	 *          calling in API, 12 digit mobile number (including 91, India code) is used
	 * @param string $message:
	 *        	The message length should be less than 1000 characters and 1 SMS credit per
	 *        	contact should be deducted for each 160 characters message
	 * @param string $response:
	 *        	Response Expected True / False
	 * @param string $eof:
	 *        	End of the Flow True / False
	 */
	function startUSSD247($mobile = NULL, $message = NULL, $response = NULL, $eof = NULL) {

		$this->autoRender = false;
		
		$logFilePath = "/mnt/logs/ussd247.txt";
		$fh = fopen ( $logFilePath, "a+" );
        
        
		$mobile = isset ( $_REQUEST['mobile'] ) ? $_REQUEST['mobile'] : "";
		fwrite ( $fh, "\n" . date ( 'Y-m-d H:i:s' ) . " mobile :: $mobile\n" );
		//In API starting ID is zero
		$sessionId = 0;
		$userObj = ClassRegistry::init('User');
        
		$vendor = 4;
		//$apiKey = "sC44obBySX5";
		$mobileNumber = substr($mobile,-10);
// 		$mobile = "918989892410";
		//$urlId = "242";				//fixed: given by the vendor
		$message = urlencode($this->getUSSDData($mobileNumber));
		//$serviceName = "USSD";
		//$response = isset ( $_REQUEST ['response'] ) ? $_REQUEST ['response'] : "true";
		//$eof = isset ( $_REQUEST ['eof'] ) ? $_REQUEST ['eof'] : "false";
		$sessionId = isset ( $_REQUEST['sessionId'] ) ? $_REQUEST['sessionId'] : 0;
		$insertingTable = "ussd_logs";
        
		//Inserting the initial details in the database
		$in_query = "INSERT INTO $insertingTable
					(mobile,type,vendor,level,sessionid,date,time) VALUES
					('$mobileNumber','1','$vendor','0','$sessionId','".date('Y-m-d')."','".date('H:i:s')."')";					
        $userObj->query($in_query);
        fwrite ( $fh, "\n Content Query :: " . $in_query );  // Data log of the response received
		//$url = "http://smsapi.24x7sms.com/api_2.0/SendUSSD.aspx";
// 		$pars = array (
// 				"APIKEY" => $apiKey,
// 				"MobileNo" => $mobile,
// 				"UrlID" => $urlId,
// 				"Message" => $message,
// 				"ServiceName" => $serviceName,
// 				"Response" => $response,
// 				"EOF" => $eof,
// 				"SessionID" => $sessionId 
// 		);
		// $this->General->startUSSD($type,$mobile,null,$number);
// 		$output = $this->curl_post ( $url, $pars, "GET", 30, 30 );
//        $responseUrlRequested = "$url?APIKEY=$apiKey&MobileNo=$mobile&UrlID=$urlId&Message=$message&ServiceName=$serviceName&Response=$response&EOF=$eof&SessionID=$sessionId";
//        $output = @file_get_contents($responseUrlRequested);
        $output = $this->sendUSSDResponse247($mobile, $sessionId, $message);
        $out = json_encode ( $output );
		fwrite ( $fh, "\n Content Out :: " . $out );  // Data log of the response received
// 		print_r($output);
                if(strtoupper(trim($out)) == "INSUFFICIENT_CREDIT" ){
                    $sub = "INSUFFICIENT_CREDIT in USSD account of pay1";
                    $body = "INSUFFICIENT_CREDIT in USSD account of pay1";
                    $this->General->sendMails($sub,$body,array('ashish@pay1.in','nandan.rana@pay1.in','sarfaraz@iglobesolutions.com'),'mail');
                }
		$params = explode("|", $output);
		
		/**
		 * $param [0] gives the sessionStatus: success or not
		 * $param [1] gives the mobile number
		 * $param [2] gives the session ID
		 */
		foreach ($params as $index => $val){
			$params[$index] = trim($val);
		}
		
		echo "<br> Get type of response :  ". gettype($output). "<br><pre>";
		print_r($params);
		
		$sessionId = isset ($params[2]) ? $params[2] : $sessionId;
		$sessionStatus = isset ($params[0]) ? trim($params[0]) : "";
	
		$mobileNumber = substr($mobile,-10);			//removes (91) code of India
		$insertingTable = "ussd_logs";				//data updation in table
		
		//If the session is created and number is Number is valid, then do the entry in the database, else just log the response
		if( $sessionStatus == "success" && in_array( substr($mobileNumber,0,1),array('7','8','9'))){
			$query = "UPDATE $insertingTable 
						SET request = '$responseUrlRequested', sessionid = '$sessionId', response = '$sessionStatus', status = '$sessionStatus'
						WHERE sessionid = 0 and mobile = '$mobileNumber' and level = 0";
			$userObj->query($query);
		}
		else{
			//session status will be failure
			$query = "UPDATE $insertingTable 
						SET request = '$responseUrlRequested', response = '', status = '$sessionStatus'
						WHERE sessionid = 0 and mobile = '$mobileNumber' and level = 0";
			$userObj->query($query);
		}
	}
	
	/**
	 * Receives the response from USSD by sending parameters
	 * This is the registered call back URL
	   Response Format: { "url":"ussdapis\/receiveUSSDResponse247",
						  "urlid":"242",
						  "sessionid":"14718",
						  "content":"test",
						  "msisdn":"917738832731",
						  "timestamp":"2015-09-28 15:56:01"
						}
	 */
	function receiveUSSDResponse247() {
		// $urlFormat = "http://www.example.com/response.aspx?UrlID=24&SessionID=1942&DataInput=2&MobileNo=919930216910&TimeStamp=01-05-2015 13:05:55";
		$pathLogFile = "/mnt/logs/ussd247.txt";
		$logTable  = "ussd_logs";
		$vendor = 4;
        
		//response received
		//$urlReceived = isset ($_REQUEST ['url'] ) ? $_REQUEST ['url'] : "";
        $urlReceived = V247_USSD_URL;
		$urlIdReceived = isset( $_REQUEST['urlid'] ) ? $_REQUEST['urlid'] : V247_USSD_UID  ;
		$SessionIdReceived = isset( $_REQUEST['sessionid'] ) ? $_REQUEST['sessionid'] : "";
		$contentReceived = isset( $_REQUEST['content'] )? $_REQUEST['content'] : "";
		$contentReceivedLowerCase = strtolower($contentReceived);
		$msisdnReceived = isset( $_REQUEST['msisdn'])? substr($_REQUEST['msisdn'],-10) : "";
		$timestampReceived = isset( $_REQUEST['timestamp']) ? $_REQUEST['timestamp'] : "";
		$mobileNumber = substr($msisdnReceived,-10);
		
		//Writing response received in the log file
		$response = json_encode ( $_REQUEST );
		$fileHandle = fopen ( $pathLogFile, "a+" );
		fwrite ( $fileHandle, "\n" . $response );
		
		//getting data for USSD
		App::import('Controller','Apis');
		
		$apisObj=new ApisController();		
		$apisObj->constructClasses();
		
		$userObj = ClassRegistry::init('User');
		/**
		 * make query to get level based on the number and session ID
		 */
		$query = "SELECT status, MAX(level) as level from $logTable WHERE sessionid = $SessionIdReceived AND mobile = $mobileNumber";
		$data = $userObj->query($query);
		$level = $data [0][$logTable]['level'];
		$status = $data [0][$logTable]['status'];
        $type = 1;
		$new_level = 1;
        //$new_level = $level;
        //$qry2 = "INSERT INTO ussd_logs (mobile,request,sessionid,type,vendor,level,sent_xml,response,status,date,time) "
        //        . "VALUES ('$msisdnReceived','".addslashes($contentReceived)."','$SessionIdReceived','$type','$vendor','$new_level','','',200,'".date('Y-m-d')."','".date('H:i:s')."')";
        //$this->User->query($qry2);
		$request = "";
        //fwrite ( $fileHandle, "\n Q2 :" .  $qry2);
		//Get new data from sms
		$ussdData = "";
		
		//getting processed USSD data to send for next request
		$ussdData = $apisObj->receiveSMS ( $msisdnReceived, $contentReceived, null, 1 );
		$ussdData .= "\n\nEnter new request";
		$ussdData_raw = $ussdData;
        
        
		//Hit the URL for next response
		if (strpos ( $contentReceivedLowerCase, "network busy" ) !== FALSE || strpos ( $contentReceivedLowerCase, "try again" ) !== FALSE
				|| strpos ( $contentReceivedLowerCase, "err" ) !== FALSE || strpos ( $contentReceivedLowerCase, "subscriber" ) !== FALSE
				|| strpos ( $contentReceivedLowerCase, "configurable time from" ) !== FALSE || strpos ( $contentReceivedLowerCase, "map dialog" ) !== FALSE
				|| strpos ( $contentReceivedLowerCase, "timer expired for" ) !== FALSE || strpos ( $contentReceivedLowerCase, "ussd busy" ) !== FALSE
				|| strpos ( $contentReceivedLowerCase, "system failure" ) !== FALSE || strpos( $contentReceivedLowerCase, "app procedure cancelled" ) !== FALSE
				|| strpos ( $contentReceivedLowerCase, "user specific reason" ) !== FALSE || strpos( $contentReceivedLowerCase, "abort user") !== FALSE
                || strpos ( $contentReceivedLowerCase, "No reseponse" ) !== FALSE)
		{
			// Do nothing
		}
		else{
            $ussdData = urlencode($ussdData);
			$request = $this->sendUSSDResponse247($msisdnReceived, $SessionIdReceived, $ussdData);
		
        
            $this->User->query("INSERT INTO ussd_logs (mobile,request,sessionid,type,vendor,level,sent_xml,response,status,date,time) "
                    . "VALUES ('$msisdnReceived','".addslashes($contentReceived)."','$SessionIdReceived','$type','$vendor','$new_level','".addslashes($ussdData_raw)."','',200,'".date('Y-m-d')."','".date('H:i:s')."')");		

            if ($status == "success" && $level == 0) {
                $query = "UPDATE $logTable SET request = '', response = '$response', timestamp = $timestampReceived
                            WHERE mobile = $mobileNumber AND sessionid = $SessionIdReceived AND level=0";
                $userObj->query($query);
            }
            else {
                $query = "UPDATE $logTable SET request = $request, response = '$response', timestamp = $timestampReceived, level = 1
                            WHERE mobile = $mobileNumber AND sessionid = $SessionIdReceived AND response = ''";
                $userObj->query($query);
            }
            fwrite ( $fileHandle, "\n Q3 :" .  $query);
        }
		$this->autoRender = false;
	}
	
	//Check abort condition
	function sendUSSDResponse247($mobile, $sessionId, $message){
        $url = V247_USSD_URL;
        $apiKey = V247_USSD_APIKEY;
		$urlId = V247_USSD_UID;				//fixed: given by the vendor
		$serviceName = V247_USSD_SERVICENAME;
		$response = "true";
		$eof = "false";
        
        $responseUrlRequested = "$url?APIKEY=$apiKey&MobileNo=$mobile&UrlID=$urlId&Message=$message&ServiceName=$serviceName&Response=$response&EOF=$eof&SessionID=$sessionId";
        $output = @file_get_contents($responseUrlRequested);
		return $output;
        
	}
	
	function sendUSSDResponse247_push($mobile, $message, $sessionId='0'){
        $url = V247_USSD_URL;
        $apiKey = V247_USSD_APIKEY;
        $urlId = V247_USSD_UID;				//fixed: given by the vendor
        $serviceName = V247_USSD_SERVICENAME;
        $response = "true";
        $eof = "true";
        
        $responseUrlRequested = "$url?APIKEY=$apiKey&MobileNo=$mobile&UrlID=$urlId&Message=$message&ServiceName=$serviceName&Response=$response&EOF=$eof&SessionID=$sessionId";
       	
        $this->General->curl_post($responseUrlRequested,null,'GET');
		$this->autoRender = false;
        
	}
	
	function getUSSDData($mobile=null){
		$data = "Welcome to pay1!!\n\nEnter your request\ne.g *15*9769597418*10";
		return $data;
	}
	
// 	function curl_post($url, $params = null, $type = 'POST', $timeout = 30, $connect_timeout = 10) {
// 		if (empty ( $params )) {
// 			$post_string = "";
// 		} else if (is_array ( $params )) {
// 			foreach ( $params as $key => &$val ) {
// 				if (is_array ( $val ))
// 					$val = implode ( ',', $val );
// 				if ($key != 'uva')
// 					$post_params [] = $key . '=' . urlencode ( $val );
// 				else
// 					$post_params [] = $val;
// 			}
// 			$post_string = implode ( '&', $post_params );
// 		} else {
// 			$post_string = $params;
// 		}
// 		if ($type == 'GET') {
// 			$url = $url . "?" . $post_string;
// 		}
// 		echo $url . " :: ";
// 		// echo $params;
// 		$ch = curl_init ( $url );
// 		if ($type == 'POST') {
// 			curl_setopt ( $ch, CURLOPT_POST, 1 );
// 			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_string );
// 		} else {
// 			curl_setopt ( $ch, CURLOPT_POST, 0 );
// 		}
	
// 		$agent = 'Mozilla/4.73 [en] (X11; U; Linux 2.2.15 i686)';
// 		curl_setopt ( $ch, CURLOPT_USERAGENT, $agent );
// 		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
// 		curl_setopt ( $ch, CURLOPT_HEADER, 0 ); // DO NOT RETURN HTTP HEADERS
// 		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // RETURN THE CONTENTS OF THE CALL
// 		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout );
// 		curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
// 		$out = trim ( curl_exec ( $ch ) );
	
// 		$info = curl_getinfo ( $ch );
	
// 		if ($info ['connect_time'] > 10 || $info ['total_time'] > 10) {
// 			$this->logData ( '/var/www/html/shops/curl_log.txt', "[" . date ( 'Y-m-d H:i:s' ) . "] Took " . $info ['total_time'] . " seconds to send request of $post_string to $url & took " . $info ['connect_time'] . " seconds to connect" );
// 		}
// 		if (! curl_errno ( $ch )) {
// 			curl_close ( $ch );
// 			return array (
// 					'output' => $out,
// 					'success' => true,
// 					'timeout' => false
// 			);
// 		} else {
// 			$errno = curl_errno ( $ch );
// 			curl_close ( $ch );
// 			$this->logData ( '/var/www/html/shops/curl_log_error.txt', "[" . date ( 'Y-m-d H:i:s' ) . "] Curl Error $errno: Took " . $info ['total_time'] . " seconds to send request of $post_string to $url & took " . $info ['connect_time'] . " seconds to connect" );
// 			if (in_array ( $errno, array (
// 					6,
// 					7
// 			) ) || $info ['connect_time'] > $connect_timeout) {
// 				return array (
// 						'output' => $out,
// 						'success' => false,
// 						'timeout' => true
// 				); // connection timeout
// 			} else {
// 				return array (
// 						'output' => $out,
// 						'success' => false,
// 						'timeout' => false
// 				); // timeout
// 			}
// 		}
// 	}

}

?>