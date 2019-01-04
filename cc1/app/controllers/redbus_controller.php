<?php
class RedbusController extends AppController {
	var $components = array('General','Busvendors');
        
        function beforeFilter() { 
		parent::beforeFilter();
		$this->Auth->allow('*');
                $this->autoLayout = $this->autoRender = FALSE;
	
        }
        
        public function allsources(){
            $auth = '1';
            if($auth){
                $logger = $this->General->dumpLog('Search Request', 'SourceSearch');
                
                $data =  $this->Shop->getMemcache("bus_sources");
                $logger->info("Available sources from memcache |count=".count($data['cities']));
                
                if($data === false){
                	$data = $this->Busvendors->getAllSources();
                	$data = json_decode($data,true);
                	$this->Shop->setMemcache("bus_sources",$data,24*60*60);
                	$logger->info("Available sources from redbus |count=".count($data['cities']));
                }
                
                $count = count($data['cities']);
                
                $sources = array();
                if($count){
                    $logger = $this->General->dumpLog('All sources request', 'success');
                    $sources['status'] = 'success';
                    $i = 0;
                    $sources['cities'] = $data['cities'];
                }
                
                foreach($data['cities'] as $dt){
                	$cities[] = $dt['name'];
                }
                array_multisort($cities,SORT_ASC,$data['cities']);
                
                $sources['cities'] = $data['cities'];
                    
            }else{
                $sources['status'] = 'failed';
            }
            echo json_encode($sources);
        }
        
        public function availabletrips($params){
            
            $source = $params['source'];
            $destination = $params['destination'];
            $doj = $params['doj'];
            
            $logger = $this->General->dumpLog('availabletrips request', 'availabletripssearch');
            $result = array();
	    	if(($source=="")||($destination=="")||($doj=="")){
                echo json_encode(array('status'=>'failure','code'=>'4','description'=>$this->Busvendors->errors(4)));
                $logger->info("Parameter blank |Result status=".$result['status']."|source=".$source."|destination=".$destination."|doj=".$doj);
            }else if($params['doj'] < date("Y-m-d")){
                echo json_encode(array('status'=>'failure','code'=>'6','description'=>$this->Busvendors->errors(6)));
                exit;
            }else{
            	$arr =  $this->Shop->getMemcache("bus_trips_$source"."_".$destination."_".$doj);
            	if($arr !== false){
            		if($arr == 'null'){
                		$logger->info("RedBus Result | No trips available");
                    	echo json_encode(array('status'=>'failure','code'=>'5','description'=>$this->Busvendors->errors('5')));
                    	exit;
                	}
                	echo json_encode($arr);exit;
            	}
                
            	$arr = $this->Busvendors->invokeGetRequest("availabletrips?source=".trim($source)."&destination=".trim($destination)."&doj=".trim($doj));         //echo "<pre>"; print_r($arr); exit;
                if($arr == 'null'){
                    $this->Shop->setMemcache("bus_trips_$source"."_".$destination."_".$doj,$arr,10*60);
                	$logger->info("RedBus Result | No trips available");
                    echo json_encode(array('status'=>'failure','code'=>'5','description'=>$this->Busvendors->errors('5')));
                    exit;
                }
                
                $arr = json_decode($arr);
                
                $newa = array();
                if(count($arr)>0){
                    foreach($arr->availableTrips as $single){
                        if(!is_array($single->fares)){ 
                            $single->fares = array($single->fares);
                            
                        }
                    $boardings = array();    
                    foreach($single->boardingTimes as $stop){
                        $stop->time = $this->convertMinuteToHI($stop->time);
                        $boardings[] = $stop;
                    }
                    
                    $single->boardingTimes = $boardings;
                    $droppings = array();    
                    foreach($single->droppingTimes as $stop){
                        $stop->time = $this->convertMinuteToHI($stop->time);
                        $droppings[] = $stop;
                    }
                    $canPolicy = $this->canPolicy($single->cancellationPolicy,count($single->fares),$single->fares);
                    $single->cancellationPolicy =  $canPolicy;
                    
                    //$single->droppingTimes = $droppings;
                    $single->arrivalTimeM = $single->arrivalTime;
                    $single->departureTimeM = $single->departureTime;
                    $travelDuration = $single->arrivalTime - $single->departureTime;
                    $travelDurationM = $travelDuration%60;
                    if($travelDurationM<10) $travelDurationM = "0".$travelDurationM;
                        $single->travelDuration = floor($travelDuration/60).":".$travelDurationM." Hours";
                        $single->arrivalTime = $this->convertMinuteToHI($single->arrivalTime);
                        $single->departureTime = $this->convertMinuteToHI($single->departureTime);

                    $newa[] = $single;
                    } 
                    $result['status'] = 'success';
                    
                    $result['availableTrips'] = $newa;
                    $this->Shop->setMemcache("bus_trips_$source"."_".$destination."_".$doj,$result,10*60);
            	
                    $logger->info("RedBus Result |Result status=".$result['status']."| Result Count=".count($arr));
                }    
            }
            
            //echo "<pre>"; print_r($result);
            //return array('status'=>'success','code'=>'0','description'=>json_encode($result));
            echo json_encode($result);
        }
        
        public function getTripDetails($params){
            //$tripId = $params['tripId'];
            $result = $this->Busvendors->getTripDetails($params);
            echo "<pre>".$result;
        }
        
        public function getBoardingDropping($params){
            $bd = $params['bord'];
            if($bd=="") $bd='B';
            $chosenbusid = $params['busid'];
            $source = $params['source'];
            $destination = $params['destination'];
            $doj = $params['doj'];
            
            $logger = $this->General->dumpLog('Availabletrips Request', 'getBoardingDropping');
            $logger->info("GetBoardingDropping |Result status=".$result['status']."|source=".$source."|destination=".$destination."|doj=".$doj);
            if(($chosenbusid=="")||($source=="")||($destination=="")||($doj=="")){
                $logger->info("Missing Parameters |=".$result['status']);
                echo json_encode(array('status'=>'failure','code'=>'4','description'=>$this->Busvendors->errors(4)));
                
            }else{
                $json_response = $this->Busvendors->invokeGetRequest("availabletrips?source=".trim($source)."&destination=".trim($destination)."&doj=".trim($doj));
                if($json_response!=""){
                    $arr = json_decode($json_response);
                    $boardings = array();
                    $boardingsTime = array();
                    if(count($arr)>0){ 
                        $result['status'] = 'success';
                        foreach($arr->availableTrips as $single){
                            if($single->id == $chosenbusid){
                                if($bd == 'B'){
                                    foreach($single->boardingTimes as $stop){
                                        $stop->time = $this->convertMinuteToHI($stop->time);
                                        $boardingsTime[] = $stop;
                                    }
                                    $boardings[] =  $boardingsTime;
                                }else{
                                    $boardings[] =  $single->droppingTimes;
                                }
                            }
                        }
                    }else{
                        $result['status'] = 'failed';
                    }
                    $result['boardingTimes'] = $boardings; 
                    
                    $result['tripDetails'] = json_decode($this->Busvendors->getTripDetails($chosenbusid));
                    if($result['tripDetails']==""){
                        $logger->info("Get Boarding Dropping Trip Details Blank| Bus Id = ".$chosenbusid);
                    }else{
                        $logger->info("Get Boarding Dropping Trip Details Response Success");
                    }
                    
                }else{
                    $logger->info("Red Bus blank response ");
                }
            }
            echo json_encode($result);
            //echo "=======<pre>"; print_r($result);
            
        }
        
        public function getAvailableSeats($params){
        	$chosenbusid = $params['busid'];

        	$result = array();
        	$logger = $this->General->dumpLog('AvailableSeats Request', 'getAvailableSeats');
        	if($chosenbusid==""){
        		$logger->info("Missing Parameters");
        		echo json_encode(array('status'=>'failure','code'=>'4','description'=>$this->Busvendors->errors(4)));

        	}else{

        		$result['tripDetails'] = json_decode($this->Busvendors->getTripDetails($chosenbusid),true);
        		if($result['tripDetails']==""){
        			$logger->info("Got Trip Details Blank| Bus Id = ".$chosenbusid);
        		}else{
        			$logger->info("Got Trip Details Response Success");
        		}
        		
        		foreach($result['tripDetails']['seats'] as $dt){
                	$columns[] = $dt['column'];
                }
                array_multisort($columns,SORT_ASC,$result['tripDetails']['seats']);
                $result['status'] = 'success';
        	}

        	echo json_encode($result);
        }

        function bookTicket($param){
            //echo 'bookticket=='.$param['chosenbus'].','.$param['boardingpointsList'].','.$param['seatnames'].','.$param['chosendestination'].'<pre>'; 
            $logger = $this->General->dumpLog('Booking Request', 'bookTicket');
            $logger->info("Parameters |".json_encode($_REQUEST));
            if(($param['chosenbus'] == "")||($param['boardingpointsList'] == "")||($param['seatnames'] == "")||($param['chosendestination'] == "")){
                echo "NA";
                exit;
            }else{  
                $json=array();
                $user_name=array();
                $user_gender=array();
                $user_age=array();
                $user_primary=array();
                $user_title=array();
                $inventoryItems= array(array());
                $passenger = array(array());
                $chosenbusid = $param['chosenbus'];
                $sourceid = $param['chosensource'];
                $destinationid = $param['chosendestination'];
                $boardingpointid = $param['boardingpointsList'];
                $checkbox_no = $param['chkchk'];
                for ($i=0; $i <$checkbox_no ; $i++) { 
                    $user_name[$i] = $param['fname'.$i.''];
                    $user_gender[$i] = $param['sex'.$i.''];
                    $user_age[$i] = $param['age'.$i.''];
                    $user_title[$i] = $param['Title'.$i.''];
                }
                
                $user_mobile=$param['mobile'];
                $user_email=$param['email_id'];
                $user_address=$param['address'];
                $user_id_no=$param['id_no'];
                $user_idproof_type=$param['id_proof'];
                for ($i=0; $i <$checkbox_no ; $i++) { 
                  if ($i==0) {
                    $user_primary[$i]='true';
                  }
                  else
                    { $user_primary[$i]='false';

                    }
                 }
                
                $chosenbusid = $param['chosenbus'];
                $tripdetails = $this->Busvendors->getTripDetails($chosenbusid);
                $tripdetails2 = json_decode($tripdetails);
                //echo "tripdetails="; print_r($tripdetails);
                $logger->info("Parameters getTripDetails |".$tripdetails);
                $seatschosen = $param['seatnames'];
                $seats=explode(",", $seatschosen);
                for ($i=0; $i <$checkbox_no ; $i++) { 
                    foreach ($tripdetails2 as $key => $value) {
                        if(is_array($value))
                        {
                            foreach ($value as $k => $v) { 
                               foreach ($v as $k1 => $v1) {
                                   if(isset($v->name))
                                   {  
                                       if(!strcmp($v->name, trim($seats[$i])))
                                       { 
                                            $passenger[$i]['age']=$user_age[$i];
                                            $passenger[$i]['gender']=$user_gender[$i];
                                            $passenger[$i]['name']=$user_name[$i];
                                            $passenger[$i]['primary']=$user_primary[$i];
                                            $passenger[$i]['title']=$user_title[$i];

                                            if ($i==0) {
                                                $passenger[$i]['address']=$user_address;
                                                $passenger[$i]['email']=$user_email;
                                                $passenger[$i]['idNumber']=$user_id_no;
                                                $passenger[$i]['idType']=$user_idproof_type;
                                                $passenger[$i]['mobile']=$user_mobile;
                                            }
                                            $inventoryItems[$i]['fare']=$v->fare;
                                            $inventoryItems[$i]['ladiesSeat']=$v->ladiesSeat;
                                            $inventoryItems[$i]['passenger']=$passenger[$i];
                                            $inventoryItems[$i]['seatName']=$v->name;

                                        }
                                    } 
                                }
                            }
                        }

                    }
                }

                $json['availableTripId']=$chosenbusid;
                $json['boardingPointId']=$boardingpointid;
                $json['destination']=$destinationid;
                $json['inventoryItems']=$inventoryItems;
                $json['source']=$sourceid;
                $json_2 = json_encode($json);
                echo "json_2=".$json_2."<br/>";
                $logger->info("Parameters request to RedBus |".$json_2);
                //$block_key =  $this->Busvendors->blockTicket($json_2);
                $logger->info("Tentative booked ticket response from RedBus |".$block_key);
                
                //$result =  $this->Busvendors->confirmTicket($block_key);
                $logger->info("Confirmed booked ticket from RedBus |".json_encode($result));
                
                if($result=='success'){
                    echo "success";
                    exit;
                }else{
                    echo "failed";
                    exit;
                }
            }

        }
        
        function getTicketDetails($params){
            $ticketId = $params['ticketId'];
            $logger = $this->General->dumpLog('Search Request', 'bookTicket');
            $logger->info("Ticket details request | Ticket Id = ".$ticketId);
            if($cancelRequest != ""){
                echo $result = $this->Busvendors->getTicket($cancelRequest);
            }
            $logger->info("Ticket details response |Response = ".json_encode($result));
        }
        
        function rbCancelTicket($params){
            $ticketId = $params['ticketId'];
            $logger = $this->General->dumpLog('Search Request', 'bookTicket');
            $logger->info("Ticket cancel request | Ticket Id = ".$ticketId);
            if($ticketId != ""){
                echo $result = $this->Busvendors->cancelTicket($ticketId);
            }
            $logger->info("Ticket cancel response |Response|".json_encode($result));
        }
        
        public function convertMinuteToHI($minute){
            $h = floor($minute/60);
            if($h>=24){
                $h = $h -24; 
                if($h<10){
                    $h = '0' . $h;
                }
                $m = $minute%60;
                if ($m < 10) {
                    $m = '0' . $m;
                }else if($m == 0){
                    $m = '00';
                }
                $ampm = "AM";
                return $h.":".$m." ".$ampm; 
            } 
            $m = $minute%60;
            if ($m < 10) {
                $m = '0' . $m;
            }else if($m == 0){
                $m = '00';
            }
            if($h>=12){
                $h = $h - 12;
                if($h==0){
                    $h = 12;
                    
                }
                if ($h < 10) {
                    $h = '0' . $h;
                }
                $ampm = "PM";
            }else{
                if (($h-12) < 10) {
                    $h = '0' . $h;
                }
                $ampm = "AM";
            }
            return $h.":".$m." ".$ampm; 
        }
       
        
    function canPolicy($v1,$countrows,$fareArr)
    {
        $STORE='';
        $cancellationcharges = explode(';', $v1);
        $limit = count($cancellationcharges);
        for ($i=0; $i <$limit ; $i++) { 
            $ccount= strlen($cancellationcharges[$i]);
            $substr = str_split($cancellationcharges[$i]);
            $colon_count=0;
            $p1='';
            $p2='';
            for ($j=0; $j <$ccount ; $j++) { 
            if($substr[$j]==':')
            {
                $colon_count++;
            }
            if($colon_count<=1)
            {
              $p1.=$substr[$j];
            }
            else
            {
              $p2.=$substr[$j];
            }
        }
        $p2=ltrim($p2,':');
        $p1=explode(':', $p1);
        $p2=explode(':', $p2);
        if($p1[0]==0)
        {
            $STORE.=" From ".$p1[1]." hrs to the time of departure ";
        }
        elseif ($p1[1]==-1) {
            $STORE.= " Till ".$p1[0]." hrs before the departure time ";
        }
        else
        {
            $STORE.= " Between ".$p1[1]." hrs and ".$p1[0]." hrs before the departure time";
        }
        $canCharge='Rs.';
        if($p2[1]=='0')
        { 

                        foreach($fareArr as $fare)
                        { 
                            $temp=($p2[0]/100)*$fare;
                            $canCharge.=$temp."/";
                            $p++;
                        }


                    $canCharge=rtrim($canCharge,"/");

                     }

                     $STORE.= $canCharge."<br>";


        }



        return $STORE;
        }    
        
}

?>