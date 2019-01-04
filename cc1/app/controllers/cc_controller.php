<?php
class CcController extends AppController {

	var $name = 'Cc';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
	var $components = array('RequestHandler','Shop');
	var $uses = array('User','Retailer','Slaves');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
	}

        /*
         * It use to display call related alert at the top of the panel
         */
	function checkPendingCalls(){
		$callData = $this->pullCallData(1,"");
                $callDataDist = $this->pullCallData(1,"Distributor");
		//$callData['data']['failure'] = $this->pullFailureData();
		$cnt = count($callData['data']);
                $cntDist = count($callDataDist['data']);
		$failureMsg = $this->pullFailureData();
		$response = array(
                       "callDropped"=>$cnt,
                       "callDroppedDist"=>$cntDist,
                       'failureMsg'=> $failureMsg
		);
		echo json_encode($response);
		die;
		$this->autoRender = false;
	}

        /*
         * It is call as api from voice server
         */
	function retMisscall($mobile,$ourExt=0){
		//$ccdata = $this->Slaves->query("SELECT * FROM cc_login WHERE state != 0");

		$this->Retailer->query("INSERT INTO cc_misscalls (number,timestamp) VALUES ('$mobile','".date('Y-m-d H:i:s')."')");
		$sms = "";
		$hour = date('H');
		$retId = 0;
		$distId = 0;
		$block = FALSE;

		$this->General->logData("/mnt/logs/misscall.txt","Received a call drop from $mobile at Extension $ourExt");
		if($ourExt == '2290' || $ourExt == '2297'){
			$distdata = $this->Slaves->query("SELECT distributors.id,mobile FROM distributors WHERE distributors.mobile = '$mobile'");
			$distId = $distdata['0']['distributors']['id'];
            if(empty($distId)){
                $distdata = $this->Slaves->query("SELECT dist_id FROM salesmen WHERE salesmen.mobile = '$mobile'");
                if(!empty($distdata)){
                    $distId = $distdata['0']['salesmen']['dist_id'];

                }
            }
			$retId = 0;
			$type = "Distributor";
		}
		else if($ourExt == '2270'){
			$retdata = $this->Slaves->query("SELECT * FROM retailers WHERE mobile = '$mobile'");
			if(!empty ($retdata['0']['retailers']['id']) && $retdata['0']['retailers']['id'] > 0 ){
				$block = TRUE;
			}
			$distId = 0;
			$retId = 0;
			$type = "Recharge Card";
		}
        else if($ourExt == '2293'){

            $retdata = $this->Slaves->query("SELECT * FROM retailers WHERE mobile = '$mobile'");
			$retId = $retdata['0']['retailers']['id'];
			$distId = 0;
			$type = "Retailer Delhi";

        }
		else if($ourExt == '2269'){
        	$this->General->logData("/mnt/logs/misscall.txt","Received a call drop from $mobile at Extension $ourExt:: in click2call");

            //$retdata = $this->Slaves->query("SELECT * FROM retailers WHERE mobile = '$mobile'");
			//$retId = $retdata['0']['retailers']['id'];
			//$distId = 0;
			$type = "Toll-free Call";
			$message = "All our customer care executives are busy at the moment. You will receive a call shortly.";
			$this->General->sendMessage($mobile, $message, 'notify', null);
        }
        else if($ourExt == '2204'){
        	$this->General->logData("/mnt/logs/misscall.txt","Received a call drop from $mobile at Extension $ourExt:: in Online Leads");

        	$type = "Online Leads";
        }
        else if($ourExt == '2273'){
        	$this->General->logData("/mnt/logs/misscall.txt", "Received a call drop from $mobile at Extension $ourExt:: in Wholesaler Call");

        	$type = "Wholesaler";
        	$message = "All our customer care executives are busy at the moment. You will receive a call shortly.";
        	$this->General->sendMessage($mobile, $message, 'notify', null);
        }
        else if($ourExt == '2268'){
                $this->General->logData("/mnt/logs/marketing.txt", "Received a call drop from $mobile at Extension $ourExt:: in Marketing Call");

                $type = "Marketing";
                $message = "All our customer care executives are busy at the moment. You will receive a call shortly.";
                $this->Retailer->query("INSERT INTO cc_call_logging (number,time,date,type,call_status)
						VALUES ('$mobile','".date('H:i:s')."','".date('Y-m-d')."','$type','0')");
               //$this->General->sendMessage($mobile, $message, 'notify', null);
        }
        else if($ourExt == '2275'){
        	$this->General->logData("/mnt/logs/misscall.txt", "Received a call drop from $mobile at Extension $ourExt:: in Limit Call");

        	$type = "Limit";
        	$message = "All our customer care executives are busy at the moment. You will receive a call shortly.";
        	//$this->General->sendMessage($mobile, $message, 'notify', null);
        }
        else if($ourExt == '2276'){
            $this->General->logData("/mnt/logs/smartpay.txt", "Received a call drop from $mobile at Extension $ourExt:: in Smartpay Call");

            $type = "Smartpay";
            $message = "All our customer care executives are busy at the moment. You will receive a call shortly.";
            //$this->General->sendMessage($mobile, $message, 'notify', null);
        }
        else if($ourExt == '2263'){
            $this->General->logData("/mnt/logs/smartpay.txt", "Received a call drop from $mobile at Extension $ourExt:: in Smartpay Call");

            $type = "Dmt";
            $message = "All our customer care executives are busy at the moment. You will receive a call shortly.";
            //$this->General->sendMessage($mobile, $message, 'notify', null);
        }
        else if($ourExt == '2278'){
            $this->General->logData("/mnt/logs/misscall.txt", "Received a call drop from $mobile at Extension $ourExt:: in Travel Call");

            $type = "Travel";
            $message = "All our customer care executives are busy at the moment. You will receive a call shortly.";
            //$this->General->sendMessage($mobile, $message, 'notify', null);
        }
		else{
			$retdata = $this->Slaves->query("SELECT * FROM retailers WHERE mobile = '$mobile'");
			$retId = $retdata['0']['retailers']['id'];
			$distId = 0;
			$type = "Retailer";
		}
		if(($hour >= 0 && $hour <= 7) || $hour == 23){
			$this->Retailer->query("INSERT INTO cc_call_logging (number,retailer_id,distributor_id,time,date,call_status,type) VALUES ('$mobile',".$retId.",".$distId.",'".date('H:i:s')."','".date('Y-m-d')."',3,'$type')");

//			$sms = "Dear Sir, No customer care is available now. You can use this facility only between 8AM & 11PM";

                        $MsgTemplate = $this->General->LoadApiBalance();
		        $sms = $MsgTemplate['Retailer_Misscall_MSG'];

			//$this->General->sendMails("CC call dropped","$type: $mobile is trying to call but no customer care is available now",array('notifications@pay1.in'));
			exit;
		}
		else if(!($ourExt == '2290' && $distId == 0) && $block != TRUE){
			if($ourExt != '2269'){
				$this->Retailer->query("INSERT INTO cc_call_logging (number,retailer_id,distributor_id,time,date,type,call_status)
						VALUES ('$mobile','".$retId."','".$distId."','".date('H:i:s')."','".date('Y-m-d')."','$type','0')");
			}
			else {
				$this->General->logData("/mnt/logs/misscall.txt","Received a call drop from $mobile at Extension $ourExt:: in click2call:: final");

				$data = $this->Retailer->query("SELECT * FROM cc_call_logging WHERE type='$type' AND date='".date('Y-m-d')."' AND number = '$mobile' AND call_status = 1 order by id desc limit 1");
				$this->General->logData("/mnt/logs/misscall.txt","Received a call drop from $mobile at Extension $ourExt:: in click2call:: final:: data ".json_encode($data));

				$this->Retailer->query("UPDATE cc_call_logging SET call_status = 0 WHERE id=".$data[0]['cc_call_logging']['id']);
			}
		}
		/*if(!empty($retdata) && empty($ccdata)){//if retailer exists & customer care is not available
			$this->Retailer->query("INSERT INTO cc_call_logging (number,retailer_id,time,date,call_status) VALUES ('$mobile',".$data['0']['retailers']['id'].",'".date('H:i:s')."','".date('Y-m-d')."',3)");
			$sms = "Dear Retailer, No customer care is available now. You can use this facility only between 8AM & 11PM";
			$this->General->sendMails("OBD call dropped","Retailer: $mobile is trying to call but no customer care is available now",array('tadka@pay1.in'));
			}
			else*/
		//if(!empty($retdata)){//if retailer exists & customer care is available
		/*$data = $this->Retailer->query("SELECT number FROM cc_call_logging WHERE number = '$mobile' AND call_status is null");
		if(empty($data)){//new misscall
		$time = $this->calculateExpectedTime($mobile);

		$this->Retailer->query("INSERT INTO cc_call_logging (number,retailer_id,time,date,expected_pick_time) VALUES ('$mobile','".$retdata['0']['retailers']['id']."','".date('H:i:s')."','".date('Y-m-d')."','$time')");
		$sms = "Dear Sir, aapko kuchh time me callback aa jayega\nIs suvidha ko use karne ke liye dhanyabad";
		//$sms = "Dear Sir, aapko next $time mins me callback aa jayega\nIs suvidha ko use karne ke liye dhanyabad";
		}
		else {//if misscall already exists & not picked up yet
		$time = $this->Retailer->query("SELECT expected_pick_time FROM cc_call_logging WHERE number='$mobile' AND call_status is null");
		if(!empty($time)){
		$time = $time['0']['cc_call_logging']['expected_pick_time'];
		//$sms = "Dear Sir, aapka call line me hai.\nAapko $time mins me call aa jayega\nIs suvidha ko use karne ke liye dhanyabad";
		//$sms = "Dear Sir, aapka call line me hai.\nAapko kuchh time me call aa jayega\nIs suvidha ko use karne ke liye dhanyabad";
		}
		}*/
		//}

		$sms = "";
		if(!empty($sms)){
			$this->General->sendMessage($mobile,$sms,'notify');
		}
		$this->autoRender = false;
	}

	/*function calculateExpectedTime($mobile){
		$ccdata = $this->Slaves->query("SELECT * FROM cc_login where state = 1 or state =2");
		$avgTime = $this->Slaves->query("SELECT AVG(TIMESTAMPDIFF(SECOND, call_start, call_end)) as average FROM (SELECT call_start,call_end FROM cc_call_logging WHERE call_status = 1 LIMIT 10) as table1");
		$callData = $this->Slaves->query("SELECT * FROM cc_call_logging WHERE call_status = 0 or call_status is null");

		$total_cust = count($ccdata);
		$total_calls = count($callData);
		$avgTime = $avgTime['0']['0']['average']/60;

		if(empty($avgTime))$avgTime = 1;
		if($total_cust == 0)$total_cust =1;
		if($total_calls == 0)$total_calls =1;

		$callspercc = ceil($total_calls/$total_cust);
		$min = ceil(($callspercc-1)*$avgTime);
		if($min < 0) $min = 0;
		$max = ceil($callspercc*$avgTime);
		if($max ==0) $max = 1;
		if($min == $max)$max = $min + 1;
		return $min . "-" . $max;
	}*/

	/*function cronCheckCC(){//cron to check if cc is available .. Every 30 mins
		$ccdata = $this->Slaves->query("SELECT id,user_id FROM cc_login where state = 1 or state = 2");

		$time = date('Y-m-d H:i:s',strtotime('-30 minutes'));
		foreach($ccdata as $cc){
			$callData = $this->Retailer->query("SELECT * FROM cc_call_logging WHERE cc_id = " . $cc['cc_login']['id'] . " AND call_end > '$time'");
			if(empty($callData)){
				$this->Retailer->query("UPDATE cc_login SET state = 0 WHERE user_id = " . $cc['cc_login']['user_id']);
			}
		}

		$this->autoRender = false;
	}*/

	/*function ccStateChange($state = null){
		if($state == null)$state = $_REQUEST['state'];
		$user_id = $this->Session->read('Auth.User.id');
		$ccdata = $this->Retailer->query("SELECT id FROM cc_login where user_id = $user_id");
		if(empty($ccdata)){
			$this->Retailer->query("INSERT INTO cc_login (user_id,state) VALUES ($user_id,$state)");
		}
		else {
			$this->Retailer->query("UPDATE cc_login SET state = $state WHERE user_id = $user_id");
		}
		$this->autoRender = false;
	}*/

        /*
         * fetch data regarding retailer and distributor
         */
	function pullCallData($online=null,$type="",$date=null){
		if(empty($date))$date=date('Y-m-d');
		$user_id = $this->Session->read('Auth.User.id');
		//$ccdata = $this->Retailer->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
        $query = !empty($type) ? "AND cc_call_logging.type='$type'" : "";

		if($type=="Distributor"){

			$callData = $this->Slaves->query(
                             "SELECT    cc_call_logging.*,
                                        count(cccall.id) as calls,
                                        distributors.id,
                                        trim(distributors.company) as caller_name
                              FROM
                                        cc_call_logging
                              LEFT JOIN distributors ON (distributors.id = cc_call_logging.distributor_id)
                              LEFT JOIN cc_call_logging as cccall ON (cc_call_logging.number=cccall.number AND (cccall.call_status = 1 OR cccall.call_status = 2) AND cccall.date='$date')
                              WHERE     (cc_call_logging.call_status is null or cc_call_logging.call_status = 0)
                                        AND cc_call_logging.callback_flag is null AND cc_call_logging.type = 'Distributor' group by cc_call_logging.number order by cc_call_logging.id
                                        AND cc_call_logging.distributor_id != 0 AND cc_call_logging.date='$date'");

            /** IMP DATA ADDED : START**/
            $dist_ids = array_map(function($element){
                return $element['distributors']['id'];
            },$callData);
            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
            foreach($callData as $key => $dist){
                $callData[$key]['0']['caller_name'] = $imp_data[$dist['distributors']['id']]['imp']['shop_est_name'];
            } 
            /** IMP DATA ADDED : END**/

		}else if(empty($type)){
                        //cccall.number
            $callData = $this->Slaves->query("SELECT cc_call_logging.*,count(1) as calls,retailers.id,trim(retailers.shopname) as caller_name
												FROM cc_call_logging
												LEFT JOIN retailers ON (retailers.id = cc_call_logging.retailer_id)
												WHERE (cc_call_logging.call_status is null or cc_call_logging.call_status = 0)
												AND cc_call_logging.date='$date' AND cc_call_logging.callback_flag is null
												AND cc_call_logging.type not in ('Distributor', 'Wholesaler', 'Limit')
												GROUP BY cc_call_logging.number
												ORDER BY cc_call_logging.id");
// 			$callData = $this->Slaves->query("SELECT
//                                     cc_call_logging.*,count(cccall.id) as calls,
//                                     trim(retailers.shopname) as caller_name
//                                     FROM
//                                                 cc_call_logging
//                                     LEFT JOIN   retailers ON (retailers.id = cc_call_logging.retailer_id)
//                                     LEFT JOIN   cc_call_logging as cccall ON (cc_call_logging.number=cccall.number AND (cccall.call_status = 1 OR cccall.call_status = 2) AND cccall.date='$date')
//                                     WHERE       (cc_call_logging.call_status is null or cc_call_logging.call_status = 0)  AND cc_call_logging.date='$date' AND cc_call_logging.callback_flag is null AND cc_call_logging.type != 'Distributor' $query group by cc_call_logging.number order by cc_call_logging.id");
             /** IMP DATA ADDED : START**/
            $ret_ids = array_map(function($element){
                return $element['retailers']['id'];
            },$callData);
            $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
            foreach($callData as $key => $ret){
                $callData[$key]['0']['caller_name'] = $imp_data[$dist['retailers']['id']]['imp']['shop_est_name'];
            } 
            /** IMP DATA ADDED : END**/
		}
		else {
			$callData = $this->Slaves->query("SELECT
                                    cc_call_logging.*,count(*) as calls,
                                    retailers.id,
                                    trim(retailers.shopname) as caller_name
                                    FROM
                                                cc_call_logging
                                    LEFT JOIN   retailers ON (retailers.id = cc_call_logging.retailer_id)
                                    LEFT JOIN   cc_call_logging as cccall ON (cc_call_logging.number=cccall.number AND (cccall.call_status = 1 OR cccall.call_status = 2) AND cccall.date='$date')
                                    WHERE       (cc_call_logging.call_status is null or cc_call_logging.call_status = 0)  AND cc_call_logging.date='$date' AND cc_call_logging.callback_flag is null AND cc_call_logging.type = '$type' $query group by cc_call_logging.number order by cc_call_logging.id");

            /** IMP DATA ADDED : START**/
            $ret_ids = array_map(function($element){
                return $element['retailers']['id'];
            },$callData);
            $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
            foreach($callData as $key => $ret){
                $callData[$key]['0']['caller_name'] = $imp_data[$dist['retailers']['id']]['imp']['shop_est_name'];
            }
            /** IMP DATA ADDED : END**/
		}

		/*$data['cc_id'] = "";
		$data['cc_state'] = "";
		if(!empty($callData) && count($callData)>0){
			$data['cc_id'] = $ccdata['0']['cc_login']['id'];
			$data['cc_state'] = $ccdata['0']['cc_login']['state'];
		}*/

		$data['data'] = $callData;
		if($online == null){
			echo json_encode($data);
			$this->autoRender = false;
		}
		else {
			return $data;
		}
	}

	function pullFailureData($online=null){
		return $this->General->findVar('failures');
	}

        /*
         * This is a view part of call drop
         */
function panel($type="",$date=null){

		if(empty($date))$date=date('Y-m-d');
		$user_id = $this->Session->read('Auth.User.id');
		$ccdata = $this->Slaves->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
        $query = !empty($type) ? "AND cc_call_logging.type='$type'" : "";

	    $type = isset($type) ? $type : "";
        $this->set('type',$type);
		$callData = $this->pullCallData(1,$type,$date);
		//$callBack = $this->Retailer->query("SELECT cc_call_logging.*,users.name,retailers.shopname FROM cc_call_logging,cc_login,users,retailers WHERE retailers.id = cc_call_logging.retailer_id AND users.id = cc_login.user_id AND cc_call_logging.cc_id = cc_login.id AND cc_call_logging.callback_flag = 1");
		$this->set('callData',$callData);
		//$this->set('callBack',$callBack);
                if($type=="Distributor"){
                    $callDone = $this->Slaves->query(
                            "SELECT
                                cc_call_logging.*,
                                users.name,
                                trim(distributors.company) as caller_name,
                                trim(distributors.user_id) as userid,
                                TIMESTAMPDIFF(SECOND, call_start, call_end) as duration
                             FROM
                                        cc_call_logging
                             LEFT JOIN  cc_login on (cc_call_logging.cc_id = cc_login.id)
                             LEFT JOIN  users on (users.id = cc_login.user_id)
                             LEFT JOIN  distributors ON (distributors.id = cc_call_logging.distributor_id)
                             WHERE
                                        cc_call_logging.call_status = '1'                                         
                                        AND cc_call_logging.type= '$type' AND cc_call_logging.date= '$date'
                             ORDER BY   cc_call_logging.id desc ");

		}else if(empty($type)){
            $callDone = $this->Slaves->query("SELECT count(1) as c_count, cc_call_logging.*,users.name,trim(retailers.user_id) as userid,
            		trim(retailers.shopname) as caller_name,TIMESTAMPDIFF(SECOND, call_start, call_end) as duration
            		FROM cc_call_logging
            		left join cc_login on (cc_call_logging.cc_id = cc_login.id)
            		left join users on (users.id = cc_login.user_id)
            		left join retailers ON (retailers.id = cc_call_logging.retailer_id)
            		WHERE cc_call_logging.call_status = '1'
            		AND cc_call_logging.type not in ('Distributor', 'Wholesaler', 'Limit')
            		AND cc_call_logging.date= '$date'
            		GROUP BY cc_call_logging.number
            		order by cc_call_logging.id desc");
//                     $callDone = $this->Slaves->query("SELECT cc_call_logging.*,users.name,trim(retailers.shopname) as caller_name,TIMESTAMPDIFF(SECOND, call_start, call_end) as duration FROM cc_call_logging left join cc_login on (cc_call_logging.cc_id = cc_login.id) left join users on (users.id = cc_login.user_id) left join retailers ON (retailers.id = cc_call_logging.retailer_id) WHERE cc_call_logging.call_status = '1' AND cc_call_logging.type != 'Distributor' AND cc_call_logging.date= '$date' $query order by cc_call_logging.id desc");
		}
		else {
			$callDone = $this->Slaves->query("SELECT count(1) as c_count, cc_call_logging.*,users.name,trim(retailers.shopname) as caller_name,trim(retailers.user_id) as userid,TIMESTAMPDIFF(SECOND, call_start, call_end) as duration FROM cc_call_logging left join cc_login on (cc_call_logging.cc_id = cc_login.id) left join users on (users.id = cc_login.user_id) left join retailers ON (retailers.id = cc_call_logging.retailer_id) WHERE cc_call_logging.call_status = '1' AND cc_call_logging.type = '$type' AND cc_call_logging.date= '$date' $query group by cc_call_logging.number order by cc_call_logging.id desc");
		}
		
		$user_ids = array_map(function($element){
		    return $element['0']['userid'];
		},$callDone);
		
		    /** IMP DATA ADDED : START**/
		    $imp_data = $this->Shop->getUserLabelData($user_ids,2,0);
		    /** IMP DATA ADDED : END**/
		    
		    foreach($callDone as $key => $d){
		        $callDone[$key]['0']['caller_name'] = $imp_data[$d['0']['userid']]['imp']['shop_est_name'];
		    }
		
		$this->set('callDone',$callDone);
	}

        /*
         * This function is called from panel when call is done from call drop panel
         */
	function callDone(){
		$user_id = $this->Session->read('Auth.User.id');
		$ccdata = $this->Slaves->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
		$id = $_REQUEST['id'];
		$mobile = empty($_REQUEST['mobile']) ? 0 : $_REQUEST['mobile'];
		if(empty($ccdata)){
			$this->Retailer->query("INSERT INTO cc_login (user_id,state) VALUES ($user_id,0)");
			$ccdata = $this->Retailer->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
		}

		/*if(empty($ccdata)){
			$this->ccStateChange(1);
			$ccdata = $this->Retailer->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
		}*/
		//case1: cc not ready/busy on other call
		//case2: call already in process/call already picked
		/*if($ccdata['0']['cc_login']['state'] == 2){//busy on other call
			echo "You are busy on other call";
		}
		else {*/
			$callData = $this->Retailer->query("SELECT cc_call_logging.* FROM cc_call_logging WHERE id = $id");
			$state = $callData['0']['cc_call_logging']['call_status'];
			if($state == 1 || $state == 2){
				echo "Call already handled";
			}
			else if($state == 3){
				echo "Call dropped due to unavailability of customer care people";
			}
			else {
				//$this->Retailer->query("UPDATE cc_login SET state = 2 WHERE user_id = $user_id");
// Old query	$this->Retailer->query("UPDATE cc_call_logging SET cc_id = '".$ccdata['0']['cc_login']['id']."',call_start = '".date('Y-m-d H:i:s')."',call_end='".date('Y-m-d H:i:s')."',call_status = 1 WHERE number = '$mobile' AND ( isnull(call_status) OR call_status = 0) " );
				$this->Retailer->query("UPDATE cc_call_logging
						SET cc_id = '".$ccdata['0']['cc_login']['id']."',
						call_start = '".date('Y-m-d H:i:s')."',
						call_end='".date('Y-m-d H:i:s')."',
						call_status = 1
						WHERE number = '$mobile'
						AND ( call_status is null OR call_status = 0)
						and date = '".$callData[0]['cc_call_logging']['date']."'
						and type = '".$callData[0]['cc_call_logging']['type']."'" );//id = $id
				echo "1";
			}

			die;
		//}
		$this->autoRender = false;
	}

	/*function callStart(){
		$user_id = $this->Session->read('Auth.User.id');
		$ccdata = $this->Slaves->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
		$id = $_REQUEST['id'];

		if(empty($ccdata)){
			$this->ccStateChange(1);
			$ccdata = $this->Slaves->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
		}
		//case1: cc not ready/busy on other call
		//case2: call already in process/call already picked
		if(empty($ccdata) || $ccdata['0']['cc_login']['state'] == 0){//not ready
			echo "You are not ready";
		}
		else if($ccdata['0']['cc_login']['state'] == 2){//busy on other call
			echo "You are busy on other call";
		}
		else {
			$callData = $this->Slaves->query("SELECT cc_call_logging.* FROM cc_call_logging WHERE id = $id");
			$state = $callData['0']['cc_call_logging']['call_status'];
			if(!empty($state) && $state == 0){//in process
				echo "Call already in process";
			}
			else if($state == 1 || $state == 2){
				echo "Call already handled";
			}
			else if($state == 3){
				echo "Call dropped due to unavailability of customer care people";
			}
			else {
				$this->Retailer->query("UPDATE cc_login SET state = 2 WHERE user_id = $user_id");
				$this->Retailer->query("UPDATE cc_call_logging SET cc_id = ".$ccdata['0']['cc_login']['id'].",call_start = '".date('Y-m-d H:i:s')."',call_status=0 WHERE id = $id");
				echo "1";
			}
		}
		$this->autoRender = false;
	}


	function callEnd(){
		$user_id = $this->Session->read('Auth.User.id');
		$ccdata = $this->Slaves->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
		$id = $_REQUEST['id'];

		$callData = $this->Retailer->query("SELECT cc_call_logging.* FROM cc_call_logging WHERE id = $id AND cc_id = " . $ccdata['0']['cc_login']['id']);
		if($callData['0']['cc_call_logging']['call_status'] == 0){
			$this->Retailer->query("UPDATE cc_call_logging SET call_end = '".date('Y-m-d H:i:s')."',call_status=1 WHERE id = $id");
			$this->Retailer->query("UPDATE cc_login SET state = 1 WHERE user_id = $user_id");
			echo "1";
			$this->callLog($callData['0']['cc_call_logging']['id'],$ccdata['0']['cc_login']['id'],0);
		}
		else {
			echo "Cannot be done, Kindly refresh your page";
		}
		$this->autoRender = false;
	} */

	function callNotPicked(){
		$user_id = $this->Session->read('Auth.User.id');
		//$ccdata = $this->Slaves->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
		$id = $_REQUEST['id'];

		$callData = $this->Retailer->query("SELECT cc_call_logging.* FROM cc_call_logging WHERE id = $id AND (isnull(call_status) OR call_status = 0 or call_status = 3)");

		if(!empty($callData)){
			$this->Retailer->query("UPDATE cc_call_logging SET cc_id='',call_status=2 WHERE id = $id");
			//send message to retailer
			$this->General->sendMessage($callData['0']['cc_call_logging']['number'], "Sorry, we were unable to reach you. Kindly call us back.", 'notify');
			echo "Successful";
// 			$this->callLog($callData['0']['cc_call_logging']['id'],$ccdata['0']['cc_login']['id'],1);
		}
		else {
			echo "Cannot be done, Kindly refresh your page";
		}
		$this->autoRender = false;
	}
/*
	function callBack(){
		$user_id = $this->Session->read('Auth.User.id');
		$ccdata = $this->Slaves->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
		$id = $_REQUEST['id'];

		$callData = $this->Retailer->query("SELECT cc_call_logging.* FROM cc_call_logging WHERE id = $id AND callback_flag is null");

		if(!empty($callData)){
			if($callData['0']['cc_call_logging']['call_status'] == 1){
				$this->Retailer->query("UPDATE cc_call_logging SET cc_id=".$ccdata['0']['cc_login']['id'].",callback_flag=1 WHERE id = $id");
				echo "Successful";
			}
			else {
				echo "Cannot be done, Kindly refresh your page";
			}
		}
		else {
			echo "Cannot be done, Kindly refresh your page";
		}
		$this->autoRender = false;
	}

	function callBackDone(){
		$user_id = $this->Session->read('Auth.User.id');
		$ccdata = $this->Slaves->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
		$id = $_REQUEST['id'];

		$callData = $this->Retailer->query("SELECT cc_call_logging.* FROM cc_call_logging WHERE id = $id AND callback_flag = 1");

		if(!empty($callData)){
			$this->Retailer->query("UPDATE cc_call_logging SET cc_id=".$ccdata['0']['cc_login']['id'].",callback_flag=2,callback_time='".date('Y-m-d H:i:s')."' WHERE id = $id");
			echo "Successful";
			$this->callLog($callData['0']['cc_call_logging']['id'],$ccdata['0']['cc_login']['id'],2);
		}
		else {
			echo "Cannot be done, Kindly refresh your page";
		}
		$this->autoRender = false;
	}

	function callBackCancel(){
		$user_id = $this->Session->read('Auth.User.id');
		$ccdata = $this->Slaves->query("SELECT id,state FROM cc_login WHERE user_id = $user_id");
		$id = $_REQUEST['id'];

		$callData = $this->Retailer->query("SELECT cc_call_logging.* FROM cc_call_logging WHERE id = $id AND callback_flag = 1");

		if(!empty($callData)){
			$this->Retailer->query("UPDATE cc_call_logging SET cc_id=".$ccdata['0']['cc_login']['id'].",callback_flag=3,callback_time='".date('Y-m-d H:i:s')."' WHERE id = $id");
			echo "Successful";
			$this->callLog($callData['0']['cc_call_logging']['id'],$ccdata['0']['cc_login']['id'],3);
		}
		else {
			echo "Cannot be done, Kindly refresh your page";
		}
		$this->autoRender = false;
	}

	function callLog($call_misscall_id,$cc_id,$type){
		$this->Retailer->query("INSERT INTO cc_logs (cc_misscall_id,cc_id,type,timestamp) VALUES ($call_misscall_id,$cc_id,$type,'".date('Y-m-d H:i:s')."')");
	}*/

	function test(){
		echo "1";
		$this->autoRender = false;
	}
}