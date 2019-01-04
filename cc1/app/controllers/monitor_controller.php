<?php
class MonitorController extends AppController {
	var $name = 'Monitor';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
	var $components = array('RequestHandler','Shop');
	var $uses = array('Retailer','Slaves');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
	}

	function index(){
		//		if($this->Session->check('Auth.User')){
		//			if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER){
		//				$this->redirect(array('action' => 'view'));
		//			}
		//			else if($_SESSION['Auth']['User']['group_id'] == CUSTCARE){
		//				$this->redirect(array('controller' => 'panels','action' => 'index'));
		//			}
		//		}
		//		else if($_SERVER['SERVER_NAME'] == 'apis.signal7.in'){
		//			echo "Work going on";
		//			$this->autoRender = false;
		//		}
		//		else {
		//			$this->render('index');
		//		}
		echo "Monitor";

	}


	function smsIncomingMonitoring()
        {
                $this->autoRender=false;
                $redis = $this->Shop->redis_connect();
                $r=$redis->hgetall("VMNOnOff");
                $today=  date('Y-m-d');
                $d = empty($this->params['url']['datepicker'])?$today:$this->params['url']['datepicker'];
                $date= date("Y-m-d",strtotime($d));
                                
                $n1 = "7666888676";//modem nos
		$n2 = "7303897886";//modem nos
                $n3 = "9223178889";//vendor nos [Kunal (Number: 9920518145)]
		$n4 = "9821232431";//va nos [Subhanshu (Number: 9209179796)] (routeSMS)
		$n5 = "9289229929";//vendor nos [Kunal (Number: 9920518145)]
		$n6 = "9004350350";//vendor nos [Kunal (Number: 9920518145)]               
                $n7 = "9717594594";
                $n8 = "8652225225";
		
                $c1 = "Dharmesh (Number: 9821475993)(Email: dharmesh.chauhan@pay1.in) ";//modem nos
		$c2 = "Dharmesh (Number: 9821475993)(Email:dharmesh.chauhan@pay1.in ) ";//modem nos
                $c3 = "Kunal Ajmera (Number: 9920518145) (Email: kunal@tsmsworld.com, support@tsmsworld.com)" ;
                $c4 = "Subhanshu (Number:  9820162750 ,Office: 22 40337673) (Email: shubhanshu@routesms.com, support@routesms.com)" ;
                $c5 = "Kunal Ajmera (Number: 9920518145) (Email: kunal@tsmsworld.com, support@tsmsworld.com)" ;
                $c6 = "Sarfaraz Kasmani (Number:  9930216910, Office: 22 65652497) (Email: sarfaraz@iglobesolutions.com)" ;
                $c7 = "Sarfaraz Kasmani (Number:  9930216910, Office: 22 65652497) (Email: sarfaraz@iglobesolutions.com)" ;
                $c8 = "Pay1 Wasim (Number: 9870361431) (Email: wasim.a@pay1.in) ";
                
		//check 15 mins records
                             
                $vnos15min = $this->Slaves->query("
                                                    SELECT virtual_num, MAX(`timestamp`) as last_hit ,  count(*) as cnt
                                                    FROM `virtual_number` 
                                                    WHERE    `timestamp` >= '".date("$date H:i:s",time()-900)."'
                                                    AND `date` = '$date' 
                                                    GROUP BY `virtual_num`
                                                    ORDER BY `timestamp` DESC 
                                                   ");   
                
                //check todays records

		$vnostoday = $this->Slaves->query("
                                                    SELECT    virtual_num ,  count(*) as tcnt
                                                    FROM     `virtual_number` 
                                                    WHERE    `date` = '".$date."' 
                                                    GROUP BY `virtual_num`
                                                    ");  
                
               
                //check monthly records

               $vnosmonth = $this->Slaves->query("SELECT virtual_num, count(*) as mcnt
                                                    FROM virtual_number
                                                    WHERE month(date) = month('$date') and year(date) = year('$date')
                                                    GROUP BY virtual_num
                                                    ");
              
  
        //id, mobile, message, virtual_num, description, sms_time, timestamp, date
			
		// INCOMING CHECK
		$data = array(
                    $n1=>array('virtual_num'=>$n1,'per'=>0,'cnt'=>0,'tcnt'=>0,'mcnt'=>0,'last_hit'=>0,'contact_details'=>$c1),
                    $n2=>array('virtual_num'=>$n2,'per'=>0,'cnt'=>0,'tcnt'=>0,'mcnt'=>0,'last_hit'=>0,'contact_details'=>$c2),
                    $n3=>array('virtual_num'=>$n3,'per'=>0,'cnt'=>0,'tcnt'=>0,'mcnt'=>0,'last_hit'=>0,'contact_details'=>$c3),
                    $n4=>array('virtual_num'=>$n4,'per'=>0,'cnt'=>0,'tcnt'=>0,'mcnt'=>0,'last_hit'=>0,'contact_details'=>$c4),
                    $n5=>array('virtual_num'=>$n5,'per'=>0,'cnt'=>0,'tcnt'=>0,'mcnt'=>0,'last_hit'=>0,'contact_details'=>$c5),
                    $n6=>array('virtual_num'=>$n6,'per'=>0,'cnt'=>0,'tcnt'=>0,'mcnt'=>0,'last_hit'=>0,'contact_details'=>$c6),
                    $n7=>array('virtual_num'=>$n7,'per'=>0,'cnt'=>0,'tcnt'=>0,'mcnt'=>0,'last_hit'=>0,'contact_details'=>$c7),
                    $n8=>array('virtual_num'=>$n8,'per'=>0,'cnt'=>0,'tcnt'=>0,'mcnt'=>0,'last_hit'=>0,'contact_details'=>$c8)
               );
		//if(count($vnos15min) != 0){
		// problem in getting sms
		// check for modemNos / vendorNos
		$tot = 0;
                             
		foreach($vnos15min as $val)
                    {
			$data[$val['virtual_number']['virtual_num']]['virtual_num'] = $val['virtual_number']['virtual_num'];
			$data[$val['virtual_number']['virtual_num']]['last_hit'] = $val[0]['last_hit'];
			$data[$val['virtual_number']['virtual_num']]['cnt'] = (strtotime($date) == strtotime($today))?$val[0]['cnt']:0;
                        $tot = $tot + $val[0]['cnt'];
                        $data[$val['virtual_number']['virtual_num']]['per'] = (strtotime($date) == strtotime($today))?round($val[0]['cnt']/$tot * 100 , 0):0;
                    }
                               
                foreach($vnostoday as $val)
                    {
                    if(isset($data[$val['virtual_number']['virtual_num']]))
                      {
                        $data[$val['virtual_number']['virtual_num']]['tcnt']= $val[0]['tcnt'];
                      }
                    }
                    
                foreach($vnosmonth as $val)
                    {
                    if(isset($data[$val['virtual_number']['virtual_num']]))
                      {
                            $data[$val['virtual_number']['virtual_num']]['mcnt']= $val[0]['mcnt'];
                      }
                    }
                			
		$this->set("data", $data);
                $this->set("date", $date);
                $this->set("today", $today);
                $this->set("r", $r);

		$this->render("sms_incoming_monitoring");
		//$ids = $this->Retailer->query("SELECT * FROM `virtual_number` LIMIT 0 , 30");
        } 
             
        function setQueue()
        {
            /*echo "<pre>";
            print_r($this->params);
            die;
            echo "</pre>";*/
          
            $this->autoRender=false;
            $redis = $this->Shop->redis_connect();
            $num = $this->params['form']['num'];
            $query = $this->Retailer->query("select virtual_num,message from vmn_number where virtual_num='$num'");
            $datetime = date("Y-m-d H:i:s");
            $update_query= $this->Retailer->query("update vmn_number set status='0', last_updated='$datetime' where virtual_num='$num'"); 
         //print_r($update_query); die;
            $msg = $query[0]['vmn_number']['message']; 
            if(!empty($query)){
                $redis->hset("VMNOnOff",$num, $msg);
            }            
            echo json_encode(array('status'=>'done'));
                      
        } 
      
        
        function unsetQueue()
        {
            /*echo "<pre>";
            print_r($this->params);
            die;
            echo "</pre>"; */
            $this->autoRender=false;
            $redis = $this->Shop->redis_connect();
            $num = $this->params['form']['num'];
            $datetime = date("Y-m-d H:i:s");
            $update_query= $this->Retailer->query("update vmn_number set status='1', last_updated='$datetime' where virtual_num='$num'"); 
            $redis->hdel("VMNOnOff",$num);
            echo json_encode(array('status'=>'done'));


        }
                
	function smsOutgoingMonitoring($vendorsStr = null){
                $msg= urlencode('acvve 9833140202 10');
		$params = 'method=mobRecharge&mobileNumber=9892471157&operator=8&subId=9892471157&amount=10&circle=&type=flexi';
		//$params = "method=updateMobile&mobileNumber=9892471157";
		$url = 'http://www.smstadka.com/promotions/smsOutgoingMonitoring';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$str = trim(curl_exec($ch));
		$vendorsOutGoing = json_decode($str,true);
		$this->set('vendorsOutGoing',$vendorsOutGoing);
                $contactDetails = array(
                    "RouteSMS"=>"Subhanshu Saxena (Number:  9820162750, Office: 22 40337673) (Email: shubhanshu@routesms.com, support@routesms.com)",
                    "SMS24x7"=>"Sarfaraz Kasmani (Number:  9930216910, Office: 22 65652497) (Email: sarfaraz@iglobesolutions.com)",    
                    "VFirst"=>"Pinki Dhasmana (Office:  +91(0124)4632005,4632063, 4750126 ) (Email: pinki.dhasmana@vfirst.com ,support@vfirst.com )",
                    "Modem"=>"Vinay Rathore (Pay1): 9967264985"
                );
        $this->set('contactDetails',$contactDetails);
		$redis = $this->Shop->redis_connect();	
		$switchtype = $redis->get('smstadka_switch_type');
		$this->set('switchtype',$switchtype);
		$this->render("sms_outgoing_monitoring");
		//echo json_encode($vendorsOutGoing);
	}

	function USSDMonitoring($ven = null){

		//$n1 = "02267242289";//give a misscall on this no it will send a SMS in response
		$n1 = "02267242234";
		$n2 = "02267429393";
			
        
        $ussd30min = $this->Slaves->query("
            SELECT ussds.sessionid, 
                   GROUP_CONCAT( ussds.level
SEPARATOR ',' ) AS level,
                   ussds.response,
                   COUNT(*) as cnt,
                   GROUP_CONCAT( ussds.status SEPARATOR ',' ) as status , 
                   GROUP_CONCAT( ussds.extra SEPARATOR ',' ) as extra,
                   GROUP_CONCAT( ussds.extra SEPARATOR '_' ) as err  
            FROM `ussds`
            WHERE ussds.type = 1
            AND ussds.date = '".date("Y-m-d")."'
            AND ussds.time >= '".date("H:i:s",strtotime('-30 minutes'))."'
            AND ussds.time <= '".date("H:i:s",strtotime('-1 minutes'))."'
            GROUP BY ussds.sessionid");
		//ussds.level !=0 AND
		$total = 0;
		$failed = 0;
		$success = 0;
		$errFail = 0;
		$lvl0Fails2t = 0;
		$lvl0Failt2s = 0;
		$errArr = array();
			
        $check_level = empty($ven) ? '0' : '1';
        $counter_chk = empty($ven) ? '3' : '1';
        
		foreach($ussd30min as $data){
			$levels = explode(",",$data[0]['level']);
				
			if(in_array($check_level,$levels)){
				$total++;

				if(count($levels) >= $counter_chk){
					if(count($levels) == 3){// && empty($data[0]['extra'])
						$failed ++;//failed
						foreach(explode("_",$data[0]['err']) as $err ){
							$errArr[$err] = empty($errArr[$err]) ? 1: $errArr[$err]+1;
						}
						//$errArr[$data[0]['err']] = empty($errArr[$data[0]['err']]) ? 1: $errArr[$data[0]['err']]+1;
						//}else if(empty($data[0]['status']) && !empty($data[0]['extra'])){
						//    $errFail++;//error failed
					}else {
						$success++;
					}
				}else {
					if(count($levels) == 1){
						$failed ++;//failed
						$lvl0Fails2t++;//failed due to "TATA not connecting"
					}else {
						$failed ++;//failed
						$lvl0Failt2s++;//failed due to "No response from TATA"
					}
				}
			}
				

		}
		//$xArr = array("a"=>3,"c"=>9,"d"=>2,"g"=>9);
			
		$maxs = empty($errArr) ? array() : array_keys($errArr, max($errArr));
		foreach($maxs as $m){
			unset($errArr[$m]);
		}
		$strErr =  implode(" , ",$maxs);
			
			
		$this->set('counts',array(
                "total"=>$total,
                "failed"=>$failed,
                "success"=>$success,
		// "errFail"=>$errFail,
               "lvl0Fails2t"=>$lvl0Fails2t,
               "lvl0Failt2s"=>$lvl0Failt2s,  
               "error"=>$strErr
		));
		$this->set('countsPer',array(
                "failed"=>$total==0 ? 0 : round($failed  * 100 / $total,0),
                "success"=>$total==0 ? 0 : round($success * 100 / $total,0),
		// "errFail"=>$total==0 ? 0 : round($errFail * 100 / $total,0),
               "lvl0Fails2t"=>$total==0 ? 0 :round($lvl0Fails2t * 100 / $total,0),
               "lvl0Failt2s"=>$total==0 ? 0 :round($lvl0Failt2s * 100 / $total,0)
		));
		
		$switch = $this->General->findVar("ussd_switch");
		$this->set('switch',$switch);
		
		$this->render("ussd_monitoring");
		//check in pay1_missedcall
	}
	
	function switch_ussd(){
		$switch = trim($_REQUEST['switch']);
		
		if(!empty($switch)){
			$this->General->setVar("ussd_switch",$switch);
			
			$this->General->sendMails("USSD Tata Server Moved","Tata Ussd server moved to Server $switch due to some issues at previous server",array('ketan.parmar@pay1.in','nandan.rana@pay1.in'),'mail');
			echo "success";
		}
		$this->autoRender = false;
	}
	
	function test(){
		echo "1";
		$this->autoRender = false;
	}
	
	function setType() {

		$this->autoRender = false;
		
		if ($this->RequestHandler->isAjax()) {

			$type = isset($_REQUEST['switch_type']) ? $_REQUEST['switch_type'] : "";

			$redis = $this->Shop->redis_connect();

			if ($redis->set('smstadka_switch_type', $type)) {
				
				$this->General->sendMails("Switched to $type successfully",array('nandan.rana@pay1.in'),'mail');
				
				echo "Switched to $type successfully!!!";
				die;
				
			}
		}
	}

}
?>
