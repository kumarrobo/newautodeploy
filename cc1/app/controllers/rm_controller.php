<?php

class RmController extends AppController {
    
    var $name = 'RelationshipManager';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
	var $components = array('RequestHandler','Shop','Relationshipmanager');
	var $uses = array('User','Slaves','Limits');

	function beforeFilter(){
                parent::beforeFilter();
                
                ini_set("memory_limit","-1");
                
//                $this->Auth->allow('*');
	}

        
    function rmAttendance(){
        $to_save = true; 
        $this->autoRender = false;
        if ($this->RequestHandler->isPost()) {

            $report_type = $this->params['form']['report_type'];
            $report_month = $this->params['form']['report_month'];
            $report_year = $this->params['form']['report_year'];


            if ($report_type == '') {
                $msg = "Please select Report Type";
                $to_save = false;
            } else if ($report_month == '') {
                    $msg = "Please select Report Month";
                    $to_save = false;
            } else if ($report_year == '') {
                    $msg = "Please select Report Year";
                    $to_save = false;
            }
        }else{
            $report_type = $this->Relationshipmanager->getCurrentWeek();
            $report_month = date('m');
            $report_year = date('Y');
        }

        if($report_type!= "" && $report_month!= "" && $report_year!= ""){

            $date_range = $this->Relationshipmanager->getdateRange($report_type,$report_month,$report_year);
            $from_date = $date_range['from_date'];
            $to_date = $date_range['to_date'];
            $total_days = $date_range['total_days'];


            $allRM = $this->Slaves->query("SELECT users.id,rm.name
             FROM users 
             INNER JOIN user_groups ON (users.id =user_groups.user_id) 
             INNER JOIN rm ON (users.id =rm.user_id) 
             WHERE 
             user_groups.group_id IN (".RELATIONSHIP_MANAGER.") AND
             rm.active_flag = 1 
             order by rm.user_id DESC");

             if(!empty($allRM)){
                $i=0;
                foreach($allRM as $RM){
                    $rm_user_id = $RM['users']['id'];
                    $calculateLeavesAndLateMark = $this->Relationshipmanager->calculateLeavesAndLateMark($rm_user_id,$from_date,$to_date,$total_days);
                    $allRM[$i]['rm']['leaves'] = $calculateLeavesAndLateMark['leaves'];
                    $allRM[$i]['rm']['half_day_count'] = $calculateLeavesAndLateMark['half_day_count'];
                    $i++;
                }
             } 
            $this->set("allRM",$allRM);
            $this->set("date_range",$date_range);
        }
        

        if(isset($msg)) {
                 $msg = "<div class='alert alert-".(($to_save==true)?"success":"danger")."'>".$msg."</div>";
                 $this->Session->setFlash($msg);
             }
       
        $this->set("report_type",$report_type);
        $this->set("report_month",$report_month);
        $this->set("report_year",$report_year);
        $this->render("/rm/rm_attendance/");

     }

     function rmAttendanceDetail(){ 

        if ($this->RequestHandler->isPost()) {

            $rm_user_id = $this->params['form']['rm_user_id'];
            $name = $this->params['form']['name'];
            $report_type = $this->params['form']['report_type'];
            $report_month = $this->params['form']['report_month'];
            $report_year = $this->params['form']['report_year'];
            $late_or_half_mark = $this->params['form']['late_or_half_mark'];
            $current_date = date('Y-m-d');
            $to_save = true;
            if ($report_type == '') {
                $msg = "Please select Report Type";
                $to_save = false;
            } else if ($report_month == '') {
                    $msg = "Please select Report Month";
                    $to_save = false;
            } else if ($report_year == '') {
                    $msg = "Please select Report Year";
                    $to_save = false;
            }

            if($to_save){
                $calculate_date_range = $this->Relationshipmanager->getdateRange($report_type,$report_month,$report_year);
                $from_date = $calculate_date_range['from_date'];
                $to_date = $calculate_date_range['to_date'];
                $total_days = $calculate_date_range['total_days'];

                $calculateLeavesAndLateMark = $this->Relationshipmanager->calculateLeavesAndLateMark($rm_user_id,$from_date,$to_date,$total_days);

                $leaves = $calculateLeavesAndLateMark['leaves'];
                $half_day_count = $calculateLeavesAndLateMark['half_day_count'];

               /* $from_date = $this->params['form']['from_date'];
                $to_date = $this->params['form']['to_date'];
                $total_days = $this->params['form']['total_days'];
                $half_day_count = $this->params['form']['half_day_count'];
                $leaves = $this->params['form']['leaves'];*/
                

                $date_range = array(
                    "from_date" => $from_date,
                    "to_date" => $to_date,
                    "total_days" => $total_days,
                    "half_day_count" => $half_day_count,
                    "leaves" => $leaves
                );
                
                $this->set("date_range",$date_range);
                $this->set("late_or_half_mark",$late_or_half_mark);

                $rm_attendance_detail = array();

                for($i=0;$i<$total_days;$i++){
                    $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));
                    $query = "SELECT * FROM rm_attendance WHERE date ='".$date."' and rm_user_id='".$rm_user_id."'";
                    if($late_or_half_mark){
                        $query .=  " and (IF((end_time!='00:00:00'),(TIMEDIFF(CONCAT(`date`,' ',end_time),CONCAT(`date`,' ',start_time)) < '09:00:00'),FALSE))";
                    }
                   
                    $attendance_detail = $this->Slaves->query($query);
                    if(!empty($attendance_detail)){
                        $check_in_time = $attendance_detail[0]['rm_attendance']['start_time'];
                        if($attendance_detail[0]['rm_attendance']['end_time']=="00:00:00"){
                            $check_out_time = '-';
                             $no_of_hour = "-";
                        }else{
                            $check_out_time = $attendance_detail[0]['rm_attendance']['end_time'];

                            $time1 = strtotime($date." ".$check_in_time);

                            $time2 = strtotime($date." ".$check_out_time);
                            $time_diff = $time2 - $time1;
                            $no_of_hour = gmdate('H:i:s',$time_diff);
                        }
                        
                       
                        $reason_type = $attendance_detail[0]['rm_attendance']['reason_type'];
                        $reason = $attendance_detail[0]['rm_attendance']['reason'];
                    }else{
                        
                        Configure::load('relationship_manager');
                        $holiday_date = Configure::read('holiday_date');

                        if(strtotime($date) > strtotime($current_date)){
                            $check_in_time = "-";
                            $check_out_time = "-";
                            $no_of_hour = "-";
                            $reason_type = "-";
                            $reason = "-";
                        }elseif(date('N',strtotime($date))==7 ){
                            $check_in_time = "Sunday";
                            $check_out_time = "-";
                            $no_of_hour = "-";
                            $reason_type = "-";
                            $reason = "-";
                        }elseif(in_array($date, $holiday_date)){
                            $check_in_time = "Holiday";
                            $check_out_time = "-";
                            $no_of_hour = "-";
                            $reason_type = "-";
                            $reason = "-";
                        }else{
                            $check_in_time = "Absent";
                            $check_out_time = "-";
                            $no_of_hour = "-";
                            $reason_type = "-";
                            $reason = "-";
                        }
                    }

                    $rm_attendance_detail[$i]['rm_user_id'] = $rm_user_id;
                    $rm_attendance_detail[$i]['date'] = $date;
                    $rm_attendance_detail[$i]['check_in_time'] = $check_in_time;
                    $rm_attendance_detail[$i]['check_out_time'] = $check_out_time;
                    $rm_attendance_detail[$i]['no_of_hour'] = $no_of_hour;
                    $rm_attendance_detail[$i]['reason_type'] = $reason_type;
                    $rm_attendance_detail[$i]['reason'] = $reason;
                }
                /*echo "<pre>";
                print_r($rm_attendance_detail);
                exit;*/
                $this->set("rm_attendance_detail",$rm_attendance_detail);
            }

            if(isset($msg)) {
                 $msg = "<div class='alert alert-".(($to_save==true)?"success":"danger")."'>".$msg."</div>";
                 $this->Session->setFlash($msg);
             }
             $this->set("rm_user_id",$rm_user_id);
                $this->set("name",$name);
              $this->set("report_type",$report_type);
                $this->set("report_month",$report_month);
                $this->set("report_year",$report_year);

        }else{
             $this->redirect(array("controller" => "rm",
                       "action" => "rmAttendance"));
        }

        $this->render("/rm/rm_attendance_detail/");
    
     }

     function rmMapRoutine(){ 
       if($_SESSION['Auth']['User']['group_id'] != SUPER_ADMIN){
           $this->redirect(array("controller" => "rm",
                       "action" => "rmTask"));
       }
        if ($this->RequestHandler->isGet()) {
             $halt_minute = "00:15:00";

            $rm_user_id = $this->params['url']['rm_user_id'];
            $date = $this->params['url']['date'];
            if($this->params['url']['halt_minute']!=''){
                 $halt_minute = $this->params['url']['halt_minute'];
            }
           

            /***Get RM Tracking log**/
            $rm_attendance_log_detail = $this->Slaves->query("SELECT * FROM rm_attendance_log WHERE `date` ='".$date."' and rm_user_id='".$rm_user_id."'");
            $rm_lat_long = array();
            if(!empty($rm_attendance_log_detail)){
                foreach($rm_attendance_log_detail as $rm) {
                    $show_marker = 1;
                    if($rm['rm_attendance_log']['duration_spent'] != null && $rm['rm_attendance_log']['duration_spent']<$halt_minute){
                        $show_marker = 0;
                    }

                    $rm_lat_long[] = array(
                        'lat' => $rm['rm_attendance_log']['latitude'],
                        'lng' => $rm['rm_attendance_log']['longitude'],
                        'time' => $rm['rm_attendance_log']['time'],
                        'duration_spent' => $rm['rm_attendance_log']['duration_spent'],
                        'show_marker' => $show_marker
                    );
                }
            }

            $rm_lat_long_length = count($rm_lat_long);
            if($rm_lat_long_length>=1){
                $rm_lat_long_end = $rm_lat_long_length-1;
            }else{
                $rm_lat_long_end =0;
            }
            
            
            $this->set("halt_minute",$halt_minute);
            $this->set("rm_lat_long",$rm_lat_long);
            $this->set('start', array(($rm_lat_long[0]['lat'] == '') ? 23.3302095 : $rm_lat_long[0]['lat'] , ($rm_lat_long[0]['lng'] == '') ? 78.0576766 : $rm_lat_long[0]['lng']));
            $this->set('end', array(($rm_lat_long[$rm_lat_long_end]['lat'] == '') ? 23.3302095 : $rm_lat_long[$rm_lat_long_end]['lat'] , ($rm_lat_long[$rm_lat_long_end]['lng'] == '') ? 78.0576766 : $rm_lat_long[$rm_lat_long_end]['lng']));

            /***Get RM Distributor*/
            $distributor_detail = $this->Slaves->query("SELECT d.id,d.company,rl.latitude,rl.longitude 
                FROM distributors d
                JOIN rm ON rm.id = d.rm_id  
                JOIN retailers_location rl ON rl.user_id = d.user_id
                WHERE rm.user_id='".$rm_user_id."'");

            $distributor_lat_long = array();
            if(!empty($distributor_detail)){
                foreach($distributor_detail as $dist) {
                    $distributor_lat_long[] = array(
                        'id' => $dist['d']['id'],
                        'company' => $dist['d']['company'],
                        'lat' => $dist['rl']['latitude'],
                        'lng' => $dist['rl']['longitude']
                    );
                }
            }
            
            $this->set("distributor_lat_long",$distributor_lat_long);
            
        }else{
             $this->redirect(array("controller" => "rm",
                       "action" => "rmAttendance"));
        }

        $this->render("/rm/rm_map_routine/");
    
     }

     function rmTask(){

        $to_save = true;
        
        if ($this->RequestHandler->isPost()) {

            $report_type = $this->params['form']['report_type'];
            $report_month = $this->params['form']['report_month'];
            $report_year = $this->params['form']['report_year'];


            if ($report_type == '') {
                $msg = "Please select Report Type";
                $to_save = false;
            } else if ($report_month == '') {
                    $msg = "Please select Report Month";
                    $to_save = false;
            } else if ($report_year == '') {
                    $msg = "Please select Report Year";
                    $to_save = false;
            }
        }else{
            $report_type = $this->Relationshipmanager->getCurrentWeek();
            $report_month = date('m');
            $report_year = date('Y');
        }

        if($report_type!= "" && $report_month!= "" && $report_year!= ""){

            $date_range = $this->Relationshipmanager->getdateRange($report_type,$report_month,$report_year);
            $from_date = $date_range['from_date'];
            $to_date = $date_range['to_date'];
            $total_days = $date_range['total_days'];
            $document_pick_up = array();

            $allRM = $this->Slaves->query("SELECT users.id,rm.name
             FROM users 
             INNER JOIN user_groups ON (users.id =user_groups.user_id) 
             INNER JOIN rm ON (users.id =rm.user_id) 
             WHERE 
             user_groups.group_id IN (".RELATIONSHIP_MANAGER.") AND
             rm.active_flag = 1 
             order by rm.user_id DESC");

             if(!empty($allRM)){
                $i=0;
                foreach($allRM as $RM){
                    $rm_user_id = $RM['users']['id'];

                    $distributor_lead_count = $this->Slaves->query("SELECT count(id) as distributor_lead  FROM leads_new WHERE creation_date BETWEEN '".$from_date."' AND '".$to_date."' and rm_user_id = $rm_user_id");
                    if(!empty($distributor_lead_count)){
                        $allRM[$i]['rm']['distributor_lead'] = $distributor_lead_count[0][0]['distributor_lead'];
                    }

                    $distributor_converted_count = $this->Slaves->query("SELECT count(id) as distributor_converted  FROM leads_new WHERE converted_date BETWEEN '".$from_date."' AND '".$to_date."' and rm_user_id = $rm_user_id");
                    if(!empty($distributor_converted_count)){
                        $allRM[$i]['rm']['distributor_converted'] = $distributor_converted_count[0][0]['distributor_converted'];
                    }
                    
                    $distributor_visited_count = $this->Slaves->query("SELECT count(id) as distributor_visited FROM rm_visit WHERE (DATE_FORMAT(created_date ,'%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."') and rm_user_id = $rm_user_id and type=1");
                    if(!empty($distributor_visited_count)){
                        $allRM[$i]['rm']['distributor_visited'] = $distributor_visited_count[0][0]['distributor_visited'];
                    }

                    $retailer_activated_count = $this->Slaves->query("SELECT count(r.id) as retailer_activated FROM retailers r 
                        JOIN distributors d ON r.parent_id = d.id
                        JOIN rm ON rm.id = d.rm_id
                        WHERE r.rm_shop_update_date BETWEEN '".$from_date."' AND '".$to_date."' and rm.user_id = $rm_user_id");
                    if(!empty($retailer_activated_count)){
                        $allRM[$i]['rm']['retailer_activated'] = $retailer_activated_count[0][0]['retailer_activated'];
                    }

                    $retailer_visited_count = $this->Slaves->query("SELECT count(id) as retailer_visited FROM rm_visit WHERE DATE_FORMAT(created_date ,'%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."' and rm_user_id = $rm_user_id and type=2");
                    if(!empty($retailer_visited_count)){
                        $allRM[$i]['rm']['retailer_visited'] = $retailer_visited_count[0][0]['retailer_visited'];
                    }

                    $querygetRMServices = $this->Relationshipmanager->getRMServices();
                    if(!empty($querygetRMServices['document_pickup_list'])){
                        $p=0;
                        foreach($querygetRMServices['document_pickup_list'] as $service){

                            $document_pick_up[$p]['service_name'] = $service['name'];
                            $document_pick_up[$p]['document_pickup_count'] = 0;
                            $document_pickup_count_query = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as document_pickup_count 
                            FROM rm_visit rv
                            JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
                            WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_id = '".$service['id']."'");
                            if(!empty($document_pickup_count_query)){
                                

                                $pickup_count = $document_pickup_count_query[0][0]['document_pickup_count'];
                                if($pickup_count==null || $pickup_count==''){
                                    $pickup_count=0;
                                }
                                $document_pick_up[$p]['document_pickup_count'] = $pickup_count;
                            }
                            $p++;
                        }
                    }
                    $allRM[$i]['document_pick_up'] = $document_pick_up;

                    /*$dmt_pickup_count = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as dmt_pickup 
                        FROM rm_visit rv
                        JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
                        WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_name = 'dmt'");
                    if(!empty($dmt_pickup_count)){
                        $dmt_pickup = $dmt_pickup_count[0][0]['dmt_pickup'];
                        if($dmt_pickup==null || $dmt_pickup==''){
                            $dmt_pickup=0;
                        }
                        $allRM[$i]['rm']['dmt_pickup'] = $dmt_pickup;
                    }

                    $mpos_pickup_count = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as mpos_pickup FROM rm_visit rv
                        JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
                        WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_name = 'mpos'");
                    if(!empty($mpos_pickup_count)){
                        $mpos_pickup = $mpos_pickup_count[0][0]['mpos_pickup'];
                        if($mpos_pickup==null || $mpos_pickup==''){
                            $mpos_pickup=0;
                        }
                        $allRM[$i]['rm']['mpos_pickup'] = $mpos_pickup;
                    }

                    $aeps_pickup_count = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as aeps_pickup FROM rm_visit rv
                        JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
                        WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_name = 'aeps'");
                    if(!empty($aeps_pickup_count)){
                        $aeps_pickup = $aeps_pickup_count[0][0]['aeps_pickup'];
                        if($aeps_pickup==null || $aeps_pickup==''){
                            $aeps_pickup=0;
                        }
                        $allRM[$i]['rm']['aeps_pickup'] = $aeps_pickup;
                    }*/

                    $i++;
                }
             } 
            $this->set("allRM",$allRM);
            $this->set("document_pick_up",$document_pick_up);
            $this->set("date_range",$date_range);

        }
        

        if(isset($msg)) {
                 $msg = "<div class='alert alert-".(($to_save==true)?"success":"danger")."'>".$msg."</div>";
                 $this->Session->setFlash($msg);
             }
       
        
        $this->set("report_type",$report_type);
        $this->set("report_month",$report_month);
        $this->set("report_year",$report_year);
         $this->render("/rm/rm_task/");
     }

     function rmTaskDetail(){ 

        $to_save = true;

        if ($this->RequestHandler->isPost()) {

            $rm_user_id = $this->params['form']['rm_user_id'];
            $name = $this->params['form']['name'];
            $report_type = $this->params['form']['report_type'];
            $report_month = $this->params['form']['report_month'];
            $report_year = $this->params['form']['report_year'];


            if ($report_type == '') {
                $msg = "Please select Report Type";
                $to_save = false;
            } else if ($report_month == '') {
                    $msg = "Please select Report Month";
                    $to_save = false;
            } else if ($report_year == '') {
                    $msg = "Please select Report Year";
                    $to_save = false;
            } else if ($name == '') {
                    $msg = "Please select RM";
                    $to_save = false;
            }

             $chk_rm_name_query = $this->Slaves->query("SELECT name  FROM rm WHERE `name`='".$name."'");
             if(empty($chk_rm_name_query)){
                $msg = "Invalid RM name";
                    $to_save = false;
             }
            if($to_save){
                $calculate_date_range = $this->Relationshipmanager->getdateRange($report_type,$report_month,$report_year);
                $from_date = $calculate_date_range['from_date'];
                $to_date = $calculate_date_range['to_date'];
                $total_days = $calculate_date_range['total_days'];

                $date_range = array(
                    "from_date" => $from_date,
                    "to_date" => $to_date,
                    "total_days" => $total_days
                );
                
                $this->set("date_range",$date_range);
               

                $rm_task_detail = array();
                $document_pick_up = array();

                $querygetRMServices = $this->Relationshipmanager->getRMServices();

                for($i=0;$i<$total_days;$i++){
                    $today_date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));

                    $other_comments_query = $this->Slaves->query("SELECT other_comments  FROM rm_attendance WHERE `date`='".$today_date."' and rm_user_id = $rm_user_id");
                    if(!empty($other_comments_query)){
                        $other_comments = $other_comments_query[0]['rm_attendance']['other_comments'];
                    }else{
                        $other_comments = '';
                    }

                    $distributor_lead_count = $this->Slaves->query("SELECT count(id) as distributor_lead  FROM leads_new WHERE creation_date='".$today_date."' and rm_user_id = $rm_user_id");
                    if(!empty($distributor_lead_count)){
                        $distributor_lead = $distributor_lead_count[0][0]['distributor_lead'];
                    }

                    $distributor_converted_count = $this->Slaves->query("SELECT count(id) as distributor_converted  FROM leads_new WHERE converted_date='".$today_date."' and rm_user_id = $rm_user_id");
                    if(!empty($distributor_converted_count)){
                        $distributor_converted = $distributor_converted_count[0][0]['distributor_converted'];
                    }

                    $distributor_visited_count = $this->Slaves->query("SELECT count(id) as distributor_visited FROM rm_visit WHERE DATE_FORMAT(created_date ,'%Y-%m-%d')='".$today_date."' and rm_user_id = $rm_user_id and type=1");
                    if(!empty($distributor_visited_count)){
                        $distributor_visited = $distributor_visited_count[0][0]['distributor_visited'];
                    }

                    $retailer_activated_count = $this->Slaves->query("SELECT count(r.id) as retailer_activated FROM retailers r 
                        JOIN distributors d ON r.parent_id = d.id
                        JOIN rm ON rm.id = d.rm_id
                        WHERE r.rm_shop_update_date='".$today_date."' and rm.user_id = $rm_user_id");
                    if(!empty($retailer_activated_count)){
                        $retailer_activated = $retailer_activated_count[0][0]['retailer_activated'];
                    }

                    $retailer_visited_count = $this->Slaves->query("SELECT count(id) as retailer_visited FROM rm_visit WHERE DATE_FORMAT(created_date ,'%Y-%m-%d')='".$today_date."' and rm_user_id = $rm_user_id and type=2");
                    if(!empty($retailer_visited_count)){
                        $retailer_visited = $retailer_visited_count[0][0]['retailer_visited'];
                    }

                    
                    
                    if(!empty($querygetRMServices['document_pickup_list'])){
                        $p=0;
                        foreach($querygetRMServices['document_pickup_list'] as $service){

                            $document_pick_up[$p]['service_name'] = $service['name'];
                            $document_pick_up[$p]['document_pickup_count'] = 0;
                            $document_pickup_count_query = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as document_pickup_count 
                            FROM rm_visit rv
                            JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
                            WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d') = '".$today_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_id = '".$service['id']."'");
                            if(!empty($document_pickup_count_query)){
                                

                                $pickup_count = $document_pickup_count_query[0][0]['document_pickup_count'];
                                if($pickup_count==null || $pickup_count==''){
                                    $pickup_count=0;
                                }
                                $document_pick_up[$p]['document_pickup_count'] = $pickup_count;
                            }
                            $p++;
                        }
                    }
                    

                    $rm_task_detail[$i]['rm_user_id'] = $rm_user_id;
                    $rm_task_detail[$i]['date'] = $today_date;
                    $rm_task_detail[$i]['distributor_lead'] = $distributor_lead;
                    $rm_task_detail[$i]['distributor_converted'] = $distributor_converted;
                    $rm_task_detail[$i]['distributor_visited'] = $distributor_visited;
                    $rm_task_detail[$i]['retailer_activated'] = $retailer_activated;
                    $rm_task_detail[$i]['retailer_visited'] = $retailer_visited;
                    $rm_task_detail[$i]['other_comments'] = $other_comments;  
                    $rm_task_detail[$i]['document_pick_up'] = $document_pick_up;       
                }
                /*echo "<pre>";
                print_r($rm_task_detail);
                exit;*/
                $this->set("rm_task_detail",$rm_task_detail);
                $this->set("document_pick_up",$document_pick_up);

                
            }

            

            $allRM = $this->Slaves->query("SELECT users.id,rm.name
                 FROM users 
                 INNER JOIN user_groups ON (users.id =user_groups.user_id) 
                 INNER JOIN rm ON (users.id =rm.user_id) 
                 WHERE 
                 user_groups.group_id IN (".RELATIONSHIP_MANAGER.") AND
                 rm.active_flag = 1 
                 order by rm.user_id DESC");
                

                 $this->set("allRM",$allRM);

             if(isset($msg)) {
                 $msg = "<div class='alert alert-".(($to_save==true)?"success":"danger")."'>".$msg."</div>";
                 $this->Session->setFlash($msg);
             }
             $this->set("rm_user_id",$rm_user_id);
                $this->set("name",$name);
              $this->set("report_type",$report_type);
                $this->set("report_month",$report_month);
                $this->set("report_year",$report_year);
            
        }else{
             $this->redirect(array("controller" => "rm",
                       "action" => "rmTask"));
        }
        $this->render("/rm/rm_task_detail/");
     }

     function rmRoutine(){ 

        $allRM = $this->Slaves->query("SELECT users.id,rm.name
                 FROM users 
                 INNER JOIN user_groups ON (users.id =user_groups.user_id) 
                 INNER JOIN rm ON (users.id =rm.user_id)
                 WHERE 
                 user_groups.group_id IN (".RELATIONSHIP_MANAGER.") AND
                 rm.active_flag = 1
                 order by rm.user_id DESC");

        $to_save = true;

        if ($this->RequestHandler->isPost()) {

            $rm_user_id = $this->params['form']['rm_user_id'];
            $name = $this->params['form']['name'];
            $report_type = $this->params['form']['report_type'];
            $report_month = $this->params['form']['report_month'];
            $report_year = $this->params['form']['report_year'];
            $onload = 0;
        }else{
            /*$rm_user_id = $allRM[0]['users']['id'];
            $name = $allRM[0]['rm']['name'];*/
            $rm_user_id = '';
            $name = '';
            $report_type = $this->Relationshipmanager->getCurrentWeek();
            $report_month = date('m');
            $report_year = date('Y');
            $onload = 1;
            $to_save = false;
        }
            if ($report_type == '') {
                $msg = "Please select Report Type";
                $to_save = false;
            } else if ($report_month == '') {
                    $msg = "Please select Report Month";
                    $to_save = false;
            } else if ($report_year == '') {
                    $msg = "Please select Report Year";
                    $to_save = false;
            } else if ($name == '' && !$onload) {
                    $msg = "Please select RM";
                    $to_save = false;
            }

            if(!$onload && $name!=""){
                 $chk_rm_name_query = $this->Slaves->query("SELECT name  FROM rm WHERE `name`='".$name."'");
                 if(empty($chk_rm_name_query)){
                    $msg = "Invalid RM name";
                        $to_save = false;
                 }
            }
            
            if($to_save){
                $calculate_date_range = $this->Relationshipmanager->getdateRange($report_type,$report_month,$report_year);
                $from_date = $calculate_date_range['from_date'];
                $to_date = $calculate_date_range['to_date'];
                $total_days = $calculate_date_range['total_days'];

                $date_range = array(
                    "from_date" => $from_date,
                    "to_date" => $to_date,
                    "total_days" => $total_days
                );
                
                $this->set("date_range",$date_range);
               

                $rm_task_detail = array();

                for($i=0;$i<$total_days;$i++){
                    $today_date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));

                    $total_halt_query = $this->Slaves->query("SELECT count(attendance_log_id) as total_halt  FROM rm_attendance_log WHERE `date`='".$today_date."' and rm_user_id = $rm_user_id and duration_spent >= '00:15:00'");
                    if(!empty($total_halt_query)){
                        $total_halt = $total_halt_query[0][0]['total_halt'];
                    }

                    $minute15_halt_query = $this->Slaves->query("SELECT count(attendance_log_id) as minute15_halt  FROM rm_attendance_log WHERE `date`='".$today_date."' and rm_user_id = $rm_user_id and duration_spent >= '00:15:00' and duration_spent < '00:30:00'");
                    if(!empty($minute15_halt_query)){
                        $minute15_halt = $minute15_halt_query[0][0]['minute15_halt'];
                    }
                    
                    $minute30_halt_query = $this->Slaves->query("SELECT count(attendance_log_id) as minute30_halt  FROM rm_attendance_log WHERE `date`='".$today_date."' and rm_user_id = $rm_user_id and duration_spent >= '00:30:00' and duration_spent < '01:00:00'");
                    if(!empty($minute30_halt_query)){
                        $minute30_halt = $minute30_halt_query[0][0]['minute30_halt'];
                    }

                    $more_than_hour_halt_query = $this->Slaves->query("SELECT count(attendance_log_id) as more_than_hour_halt  FROM rm_attendance_log WHERE `date`='".$today_date."' and rm_user_id = $rm_user_id and duration_spent >= '01:00:00'");
                    if(!empty($more_than_hour_halt_query)){
                        $more_than_hour_halt = $more_than_hour_halt_query[0][0]['more_than_hour_halt'];
                    }
                    
                    $rm_task_detail[$i]['rm_user_id'] = $rm_user_id;
                    $rm_task_detail[$i]['date'] = $today_date;
                    $rm_task_detail[$i]['total_halt'] = $total_halt;
                    $rm_task_detail[$i]['minute15_halt'] = $minute15_halt;
                    $rm_task_detail[$i]['minute30_halt'] = $minute30_halt;   
                    $rm_task_detail[$i]['more_than_hour_halt'] = $more_than_hour_halt;   
                }
                /*echo "<pre>";
                print_r($rm_task_detail);
                exit;*/
                $this->set("rm_task_detail",$rm_task_detail);
                
            }
            

             if(isset($msg)) {
                 $msg = "<div class='alert alert-".(($to_save==true)?"success":"danger")."'>".$msg."</div>";
                 $this->Session->setFlash($msg);
             }
             $this->set("rm_user_id",$rm_user_id);
                $this->set("name",$name);
              $this->set("report_type",$report_type);
                $this->set("report_month",$report_month);
                $this->set("report_year",$report_year);
            
        

        
                

                 $this->set("allRM",$allRM);
         $this->render("/rm/rm_routine/");
     }

      
}
