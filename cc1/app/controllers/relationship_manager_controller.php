<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class RelationshipManagerController extends AppController{
    var $name = 'RelationshipManager';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator');
    var $components = array('RequestHandler', 'Shop', 'Relationshipmanager','General','Email','Platform');
    var $uses = array('User', 'Slaves', 'RelationshipManager');
    var $validFormats = array('xml', 'json');

    function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('*');
        $this->logId = rand() . time();
    }

    function log($data){
        $filename = "rm_apis.txt";
        $data = "LogId: " . $this->logId . ":::" . $data;
        $this->General->logData($filename, $data);
    }

    function apis(){
        $this->autoRender = false;
        $params = $_REQUEST;
        if(isset($this->params['form']['document'])){
            $params['document'] = $this->params['form']['document'];
        }
        $response = $this->Relationshipmanager->requestValidation($params);
        
        if($response['status'] == 'success'){
            $params = $response['params'];
            $method = $params['method'];
            $this->log("Request: " . json_encode($params) . "::ServerInfo: " . json_encode($_SERVER));
            $response = $this->$method($params);
        }

        $this->log("Response: " . json_encode($response));

        echo json_encode($response);

    }

    private function authenticate($params){
        $this->autoRender = false;

        $this->General->logData("rm_authenticateuser.txt", "in authenticateUser api: " . json_encode($params));

        Configure::load('relationship_manager');
        $group_app_mapping = Configure::read('group_app_mapping');

        $mobile = $params['mobile'];
        $password = $this->Auth->password($params['password']);
        $uuid = $params['uuid'];
        $latitude = $params['latitude'];
        $longitude = $params['longitude'];
        $app_version_code = $params['version_code'];
        $app_type = $params['app_name'];
        $groups = $group_app_mapping[$app_type];
         /***Check valid mobile no. and password**/
        $authenticateuser = $this->Relationshipmanager->__checkUserExist($mobile, $password, $groups, $params);
        $all_groups = explode(",",$authenticateuser[0]['groupids']);
        $group_id = $all_groups[0];
        if($authenticateuser['status'] == 'failure'):
            return $authenticateuser;
        elseif($authenticateuser['users']['active_flag'] == 0):
            return array('status'=>'failure', 'code'=>'114', 'description'=>$this->Relationshipmanager->errorDescription(114));
        elseif(!in_array($group_id,$all_groups)):
            return array('status'=>'failure', 'code'=>'122', 'description'=>$this->Relationshipmanager->errorDescription(122));    
        else: 
            /***Check latest version or not**/
            $updateAppVersion = $this->Relationshipmanager->checkAppVersion($app_version_code, $app_type);

            if($updateAppVersion['status'] == 'failure'):
                return $updateAppVersion;
            endif;

            /***Check this my device or not**/
            $chkUniqueDevice = $this->Relationshipmanager->chkUniqueDevice($authenticateuser['users']['id'], $params);
            if($chkUniqueDevice['status'] == 'failure'):
                return $chkUniqueDevice;
            endif;
            if(empty($authenticateuser['rm_device_detail']['device_id'])):
               
                /***First time login with this device, send OTP**/
                $otp_data = $this->Relationshipmanager->sendOTPToUserDeviceMapping($mobile,0,$authenticateuser['users']['id']);
                $otp_data['name'] = $authenticateuser['rm']['name'];
                $otp_data['parent_rm_id'] = $authenticateuser['rm']['parent_rm_id'];
                return $otp_data;
            else:
                /***fetch stored RM data and insert new login-log **/
                $params['device_id'] = $authenticateuser['rm_device_detail']['device_id'];
                $insertRMLoginLogDetail = $this->Relationshipmanager->insertRMLoginLogDetail($authenticateuser['users']['id'],$params);

                $info['User']['group_id'] = $group_id;
                $info['User']['id'] = $authenticateuser['users']['id'];
                $info['User']['mobile'] = $mobile;
                $info['User']['group_ids'] = implode(",",$all_groups);
                $info['User']['device_id'] = $authenticateuser['rm_device_detail']['device_id'];
                $info['User']['login_id'] = $insertRMLoginLogDetail['login_id'];
                /*$showCheckinOrCheckout = $this->Relationshipmanager->showCheckinOrCheckout($authenticateuser['users']['id']);
                $attendance_id = $showCheckinOrCheckout['attendance_id'];
                $check_in_status = $showCheckinOrCheckout['value'];*/
                
                $this->Session->write('Auth', $info);
                return array('status'=>'success', 'token'=>$this->Session->id(), 'rm_user_id'=>$authenticateuser['users']['id'], 'mobile'=>$mobile, 'device_id'=>$authenticateuser['rm_device_detail']['device_id'], 'name'=>$authenticateuser['rm']['name'], 'parent_rm_id'=>$authenticateuser['rm']['parent_rm_id'],'description'=>'Login successful','passflag'=>$authenticateuser['users']['passflag'],'login_id'=>$info['User']['login_id']);

            endif;
        endif;
    }

    private function showCheckinOrCheckout($params){
        $this->autoRender = false;

        $rm_user_id = $params['rm_user_id'];

        $showCheckinOrCheckout = $this->Relationshipmanager->showCheckinOrCheckout($rm_user_id);
        return $showCheckinOrCheckout;
    }

    private function resendOTPAuthenticate($params){
        $this->autoRender = false;

        $mobile = $params['mobile'];
        $rm_user_id = $params['rm_user_id'];

        $otp_data = $this->Relationshipmanager->sendOTPToUserDeviceMapping($mobile,0,$rm_user_id);
        return $otp_data;
    }

    // Verify OTP of User Mobile Number for User Device Mapping on Web
    private function verifyOTPAuthenticate($params){

        $user_mobile = $params['mobile'];
        $rm_user_id = $params['rm_user_id'];
        $uuid = $params['uuid'];
        $gcm_reg_id = $params['gcm_reg_id'];
        $device_type = $params['device_type'];
        $device_name = $params['device_name'];
        $otp = $params['otp'];
        $latitude = $params['latitude'];
        $longitude = $params['longitude'];

        $user_exists = $this->Slaves->query("SELECT * FROM users WHERE mobile = '" . $user_mobile . "' AND id = '$rm_user_id'");
        if(empty($user_exists)){
            return array('status'=>'failure', 'code'=>'49', 'description'=>$this->Shop->apiErrors('49'));
        }
        if($otp == $this->Shop->getMemcache("otp_rmDeviceNewUuid_$user_mobile")){
            $this->Shop->delMemcache("otp_rmDeviceNewUuid_$user_mobile");
            $rm_device_latest_data = $this->Slaves->query("SELECT device_id FROM rm_device_detail WHERE rm_user_id='".$rm_user_id."' and uuid = '".$uuid."'");
            if(empty($rm_device_latest_data[0]['rm_device_detail']['device_id'])){
                 $rm_device_insert_data = $this->User->query("INSERT INTO rm_device_detail(rm_user_id,uuid,gcm_reg_id,device_type,device_name,created_date) VALUES('".$rm_user_id."','".$uuid."','".$gcm_reg_id."','".$device_type."','".$device_name."','".date('Y-m-d H:i:s')."')");

                 if($rm_device_insert_data){
                    $rm_device_new_data = $this->Slaves->query("SELECT device_id FROM rm_device_detail WHERE rm_user_id='".$rm_user_id."' and uuid = '".$uuid."' and gcm_reg_id = '".$gcm_reg_id."'");

                     $params['device_id'] = $rm_device_new_data[0]['rm_device_detail']['device_id'];
                 }

            }else{
                $params['device_id'] = $rm_device_latest_data[0]['rm_device_detail']['device_id'];
            }

            $insertRMLoginLogDetail = $this->Relationshipmanager->insertRMLoginLogDetail($rm_user_id,$params);

            $all_groups = array(RELATIONSHIP_MANAGER);
            $group_id = $all_groups[0];
            $info['User']['group_id'] = $group_id;
            $info['User']['id'] = $rm_user_id;
            $info['User']['mobile'] = $user_mobile;
            $info['User']['group_ids'] = implode(",",$all_groups);
            $info['User']['device_id'] = $params['device_id'];
            $info['User']['login_id'] = $insertRMLoginLogDetail['login_id'];
            
            $this->Session->write('Auth', $info);
            return array('status'=>'success', 'token'=>$this->Session->id(), 'rm_user_id'=>$rm_user_id, 'mobile'=>$user_mobile, 'device_id'=>$params['device_id'],'login_id'=>$insertRMLoginLogDetail['login_id'], 'description'=>'OTP Matched Successfully');
        }
        else {
            return array('status'=>'failure', 'code'=>'54', 'description'=>$this->Shop->apiErrors('54'));
        }
    }

    private function markCheckIn($params){

        $rm_user_id = $params['rm_user_id'];        
        $date = date('Y-m-d');
        $start_time = date('H:i:s');
        $created_date = date('Y-m-d H:i:s');

        $day = date('N'); //1 (for Monday) through 7 (for Sunday)

        $reason_type = $params['reason_type'];
        $reason = $params['reason'];

        $uuid = $params['uuid'];
        $latitude = $params['latitude'];
        $longitude = $params['longitude'];

        /***Check if checkin is done or not, for today**/
        $attendance_exists = $this->Slaves->query("SELECT attendance_id FROM rm_attendance WHERE `date` = '" . $date . "' AND rm_user_id = '$rm_user_id'");
        if(!empty($attendance_exists)){
            return array('status'=>'failure', 'code'=>'131', 'description'=>$this->Relationshipmanager->errorDescription('131'));
        }else{

            Configure::load('relationship_manager');
            $holiday_date = Configure::read('holiday_date');

            /***Check if checkin is doing on sunday or non holidays**/
            if(in_array($date, $holiday_date) || $day==7){
                /***If doing checkout before 9 hour and not giving reason **/
                if($reason_type == "" || $reason == ""){
                     return array('status'=>'failure', 'code'=>'133', 'description'=>$this->Relationshipmanager->errorDescription('133'));
                }
               
            }


           /***If not done, mark check in and start tracking**/
            $rm_attendance_insert_data = $this->User->query("INSERT INTO rm_attendance(rm_user_id,date,start_time,checkin_reason_type,checkin_reason,created_date) VALUES('".$rm_user_id."','".$date."','".$start_time."','".$reason_type."','".mysql_escape_string($reason)."','".$created_date."')");
            $attendance_id = '';
            if($rm_attendance_insert_data){
                $get_attendance_id = $this->Slaves->query("select attendance_id from rm_attendance where date = '" . $date . "' AND rm_user_id = '".$rm_user_id."' and start_time='".$start_time."'");
                if(!empty($get_attendance_id)){
                    $attendance_id =$get_attendance_id[0]['rm_attendance']['attendance_id'];
                }
            }
             
           /* $area_id = $this->Relationshipmanager->getAreaId($latitude,$longitude);

            $rm_attendance_log_insert_data = $this->User->query("INSERT INTO rm_attendance_log(rm_user_id,uuid,date,time,latitude,longitude,area_id,created_date) VALUES('".$rm_user_id."','".$uuid."','".$date."','".$start_time."','".$latitude."','".$longitude."','".$area_id."','".$created_date."')");*/

            return array('status'=>'success','attendance_id'=>$attendance_id,'start_time'=>$start_time, 'description'=>'Checkin done successfully!');
        }
    }

    private function markCheckOut($params){

        $attendance_id = $params['attendance_id'];
        $rm_user_id = $params['rm_user_id']; 
        $date = date('Y-m-d');
        $end_datetime = date('Y-m-d H:i:s');
        $end_time = date('H:i:s');
        $created_date = date('Y-m-d H:i:s');
        $other_comments = $params['other_comments'];

        $reason_type = $params['reason_type'];
        $reason = $params['reason'];

        $uuid = $params['uuid'];
        $latitude = $params['latitude'];
        $longitude = $params['longitude'];

        $TrackingData = $params['tracking_data'];

        /***Check if checkout is done or not, for today**/
        $attendance_exists = $this->Slaves->query("SELECT attendance_id FROM rm_attendance WHERE attendance_id = '" . $attendance_id . "' AND (end_time != '' AND end_time != '00:00:00')");
        if(!empty($attendance_exists)){
            return array('status'=>'failure', 'code'=>'132', 'description'=>$this->Relationshipmanager->errorDescription('132'));
        }else{
            /***If not done, mark check out and stop tracking**/
            $chk_before_9hr = $this->Slaves->query("SELECT attendance_id FROM rm_attendance WHERE attendance_id = '" . $attendance_id . "' AND DATE_ADD(CONCAT('".$date."',' ',start_time), INTERVAL 9 HOUR) > '".$end_datetime."'");
            if(!empty($chk_before_9hr)){
                /***If doing checkout before 9 hour and not giving reason **/
                if($reason_type == "" || $reason == ""){
                     return array('status'=>'failure', 'code'=>'133', 'description'=>$this->Relationshipmanager->errorDescription('133'));
                }
            }
           
            $rm_attendance_update_data = $this->User->query("UPDATE rm_attendance SET end_time = '".$end_time."', reason_type = '".$reason_type."', reason = '".mysql_escape_string($reason)."',other_comments = '".mysql_escape_string($other_comments)."' WHERE attendance_id = '$attendance_id'");

            $AddTrackingData = $this->Relationshipmanager->insertTrackingAttendanceLog($rm_user_id,$uuid,$TrackingData);

            /*$area_id = $this->Relationshipmanager->getAreaId($latitude,$longitude);

            $rm_attendance_log_insert_data = $this->User->query("INSERT INTO rm_attendance_log(rm_user_id,uuid,date,time,latitude,longitude,area_id,created_date) VALUES('".$rm_user_id."','".$uuid."','".$date."','".$end_time."','".$latitude."','".$longitude."','".$area_id."','".$created_date."')");*/

            return array('status'=>'success', 'description'=>'Checkout done successfully!');
        }
    }

    private function addCommentsBeforeCheckout($params){

        $attendance_id = $params['attendance_id'];
        $other_comments = $params['other_comments'];

         $rm_other_comment_update_query = $this->User->query("UPDATE rm_attendance SET other_comments = '".mysql_escape_string($other_comments)."' WHERE attendance_id = '$attendance_id'");

            return array('status'=>'success', 'description'=>'Checkout comment added successfully!');
        
    }

    private function logout($params){
        /***Logout and store all tracking data**/
        $TrackingData = $params['tracking_data'];
        $rm_user_id = $params['rm_user_id'];
        $uuid = $params['uuid'];
        $login_id = $params['login_id'];
        $logout_datetime = date('Y-m-d H:i:s');
        if($TrackingData != ''){
            $AddTrackingData = $this->Relationshipmanager->insertTrackingAttendanceLog($rm_user_id,$uuid,$TrackingData);
        }

        $rm_attendance_log_insert_data = $this->User->query("UPDATE rm_login_log set logout_datetime = '".$logout_datetime."' WHERE login_id='".$login_id."'");
        
        $memcache = $this->Shop->memcacheConnection(MEMCACHE_MASTER);
        $token = $params['token'];
        
        $memcache->delete($token);
        session_destroy();
        return array('status'=>'success');
    }

    private function insertTrackingLog($params){
        /***Store all tracking data**/
        $rm_user_id = $params['rm_user_id'];
        $uuid = $params['uuid'];
        $TrackingData = $params['tracking_data'];
        if($TrackingData!=''){
            $AddTrackingData = $this->Relationshipmanager->insertTrackingAttendanceLog($rm_user_id,$uuid,$TrackingData);
            return $AddTrackingData;
        }else{
            return array('status'=>'failure');
        }  
        
    }

    function createRetDistNewLeads($params){
        $response = $this->Platform->createRetDistNewLeads($params);
        return $response;
    }

    function verifyRetDistNewLeads($params){
        $response = $this->Platform->verifyRetDistNewLeads($params);
        return $response;
    }

    function masterDistributor($params){
        $response = $this->Relationshipmanager->getRMDistributor($params);
        return $response;
    }

    function masterRetailer($params){
        $response = $this->Relationshipmanager->getDistributorRetailer($params);
        return $response;
    }

    function masterStatus($params){
        $response = $this->Relationshipmanager->getRMStatus($params);
        return $response;
    }

    function masterServices($params){
        $response = $this->Relationshipmanager->getRMServices($params);
        return $response;
    }

    function myAllLead($params){
        $response = $this->Relationshipmanager->getMyAllLead($params);
        return $response;
    }

    function updateFollowUpLead($params){

        $id = $params['id'];
        $followup_date = $params['followup_date']; 
        $followup_remark = $params['followup_remark'];
        $status = $params['status'];
        $converted_dist_id = $params['converted_dist_id'];
        $converted_date = date('Y-m-d');

        $query = "";
        if($converted_dist_id!=0 && $converted_dist_id!=""){
            $query = ",converted_date='".$converted_date."'";
        }
        

        $updateFollowUpLead = $this->User->query("UPDATE leads_new SET followup_date = '".$followup_date."',followup_remark = '".mysql_escape_string($followup_remark)."',status = '".$status."',converted_dist_id = '".$converted_dist_id."',lead_state = '".$status."'".$query." where id = '$id'");

        return array('status'=>'success', 'description'=>'Follow up done successfully!');
    }

    function feedback($params){

        $rm_user_id = $params['rm_user_id'];
        $feedback = $params['feedback'];
        
        $updateFollowUpLead = $this->User->query("INSERT INTO rm_feedback(rm_user_id,feedback,created_date) VALUES('".$rm_user_id."','".mysql_escape_string($feedback)."','".date('Y-m-d H:i:s')."')");

        return array('status'=>'success', 'description'=>'Feedback added successfully!');
    }

    function retailerDistributorVisit($params){

        $rm_user_id = $params['rm_user_id'];
        $parent_id = $params['parent_id'];
        $type = $params['type'];
        $business_update = $params['business_update'];
        $document_pick_up   = $params['document_pick_up'];
        $comments = $params['comments'];
        $document_services   = $params['document_services'];
        $shop_ownership   = $params['shop_ownership'];
        $nature_of_business   = $params['nature_of_business'];
        $area_of_business   = $params['area_of_business'];
        $created_date   = date('Y-m-d H:i:s');
        $rm_shop_update_date   = date('Y-m-d');
        $explode_service = explode(",", $document_services);


        if($type == 2 && $shop_ownership!=""){

            $chk_shop_name_exist = $this->Slaves->query("SELECT id FROM retailers WHERE id ='".$parent_id."' and rm_shop_update_date != ''");

            $update_query = "";
            if(empty($chk_shop_name_exist)){
                $update_query = ",rm_shop_update_date = '".$rm_shop_update_date."' ";
            }

            $update_new_shop_rm = $this->User->query("UPDATE retailers SET shop_ownership='".$shop_ownership."' ,nature_of_business='".$nature_of_business."' ,area_of_business='".$area_of_business."'".$update_query." where id='".$parent_id."'");
        }
        

        $insert_rm_visit = $this->User->query("INSERT INTO rm_visit(rm_user_id,parent_id,type,business_update,document_pick_up,comments,created_date) VALUES('".$rm_user_id."','".$parent_id."','".$type."','".$business_update."','".$document_pick_up."','".mysql_escape_string($comments)."','".$created_date."')");

          $rm_visit_last_id = $this->User->query("SELECT id FROM rm_visit WHERE parent_id='".$parent_id."' and rm_user_id = $rm_user_id and created_date = '".$created_date."'");
            if(!empty($rm_visit_last_id)){
                $rm_visit_id = $rm_visit_last_id[0]['rm_visit']['id'];
            }else{
                $rm_visit_id = '';
            }

        if($document_pick_up){
            foreach ($explode_service as $services) {
                $individual_service = explode(":",$services);
                $insert_rm_visit = $this->User->query("INSERT INTO rm_visit_document_pick_up(rm_visit_id,service_id,pick_up_count) VALUES('".$rm_visit_id."','".$individual_service[0]."','".$individual_service[1]."')");
            }
        }

        return array('status'=>'success', 'description'=>'Visit done successfully!');
    }

    function dailyReport($params){

        $rm_user_id = $params['rm_user_id'];
        $today_date   = date('Y-m-d');
        
        $distributor_lead = 0;
        $distributor_converted = 0;
        $distributor_visited = 0;
        $retailer_activated = 0;
        $retailer_visited = 0;
        /*$dmt_pickup = 0;
        $mpos_pickup = 0;
        $aeps_pickup = 0;*/

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

        /**Document Pick up**/

        $document_pick_up = array();
        $querygetRMServices = $this->Relationshipmanager->getRMServices();
        if(!empty($querygetRMServices['document_pickup_list'])){
            $p=0;
            foreach($querygetRMServices['document_pickup_list'] as $service){

                $document_pickup_count_query = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as document_pickup_count 
                FROM rm_visit rv
                JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
                WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d')='".$today_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_id = '".$service['id']."'");
                if(!empty($document_pickup_count_query)){
                    $document_pick_up[$p]['service_name'] = $service['name'];

                    $pickup_count = $document_pickup_count_query[0][0]['document_pickup_count'];
                    if($pickup_count==null || $pickup_count==''){
                        $pickup_count=0;
                    }
                    $document_pick_up[$p]['document_pickup_count'] = $pickup_count;
                }
                $p++;
            }
        }

        /**End Document Pick up**/

        /*$dmt_pickup_count = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as dmt_pickup 
            FROM rm_visit rv
            JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
            WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d')='".$today_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_name = 'dmt'");
        if(!empty($dmt_pickup_count)){
            $dmt_pickup = $dmt_pickup_count[0][0]['dmt_pickup'];
            if($dmt_pickup==null || $dmt_pickup==''){
                $dmt_pickup=0;
            }
        }

        $mpos_pickup_count = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as mpos_pickup FROM rm_visit rv
            JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
            WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d')='".$today_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_name = 'mpos'");
        if(!empty($mpos_pickup_count)){
            $mpos_pickup = $mpos_pickup_count[0][0]['mpos_pickup'];
            if($mpos_pickup == null || $mpos_pickup == ''){
                $mpos_pickup = 0;
            }
        }

        $aeps_pickup_count = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as aeps_pickup FROM rm_visit rv
            JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
            WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d')='".$today_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_name = 'aeps'");
        if(!empty($aeps_pickup_count)){
            $aeps_pickup = $aeps_pickup_count[0][0]['aeps_pickup'];
            if($aeps_pickup==null || $aeps_pickup==''){
                $aeps_pickup=0;
            }
        }

        $travel_pickup_count = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as travel_pickup FROM rm_visit rv
            JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
            WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d')='".$today_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_name = 'travel'");
        if(!empty($travel_pickup_count)){
            $travel_pickup = $travel_pickup_count[0][0]['travel_pickup'];
            if($travel_pickup==null || $travel_pickup==''){
                $travel_pickup=0;
            }
        }
*/
            $report_array = array(
                "today_date" => $today_date,
                "distributor_lead" => $distributor_lead,
                "distributor_converted" => $distributor_converted,
                "distributor_visited" => $distributor_visited,
                "retailer_activated" => $retailer_activated,
                "retailer_visited" => $retailer_visited,
                "document_pick_up" => $document_pick_up
            );

        return array('status'=>'success',"report_array" => $report_array, 'description'=>'Daily Report');
    }

    function getChildRM($params){

        $rm_user_id = $params['rm_user_id'];

        $all_child_rm = $this->Slaves->query("SELECT b . id, b . user_id, b . name
                        FROM  `rm` a
                        JOIN rm b ON a.id = b.parent_rm_id
                        WHERE a.`user_id` = '$rm_user_id'");

        $child_rm = array();
        $i=0;
        foreach ($all_child_rm as $child) {
           $child_rm[$i]['id'] = $child['b']['id'];
           $child_rm[$i]['user_id'] = $child['b']['user_id'];
           $child_rm[$i]['name'] = $child['b']['name'];
           $i++;
        }
        
        return array('status'=>'success',"child_rm"=>$child_rm, 'description'=>'Child RM');
    }

    function updateRMVisit($params){

        $attendance_id = $params['attendance_id'];
        $rm_visit_child_id = $params['rm_visit_child_id'];
        $rm_visit_area = $params['rm_visit_area'];
        
        $all_child_rm = $this->User->query("UPDATE rm_attendance set rm_visit_child_id = '".$rm_visit_child_id."', rm_visit_area = '".$rm_visit_area."' WHERE attendance_id = '$attendance_id'");

       
        return array('status'=>'success', 'description'=>'Visit done successfully');
    }

    function dashboard($params){

        $rm_user_id = $params['rm_user_id'];
        $report_type  = $params['report_type'];
        $report_month  = $params['report_month'];
        $report_year  = $params['report_year'];
        $today_date  = date('Y-m-d');

        $date_range = $this->Relationshipmanager->getdateRange($report_type,$report_month,$report_year);
        $from_date = $date_range['from_date'];
        $to_date = $date_range['to_date'];  
        $total_days = $date_range['total_days'];  


        $today_distributor_lead = 0;
        $distributor_lead = 0;
        $new_distributor_array = array();



        $distributor_converted_query = $this->Slaves->query("SELECT count(id) as value,leads_new.converted_date as `date`  FROM leads_new WHERE (converted_date BETWEEN '".$from_date."' AND '".$to_date."') and interest=2 and rm_user_id = $rm_user_id GROUP BY converted_date ");
        if(!empty($distributor_converted_query)){
            $distributor_converted = 0;
            $p=0;
            foreach($distributor_converted_query as $converted){
                $distributor_converted_value = $converted[0]['value'];
                $distributor_converted_date = $converted['leads_new']['date'];
                if($distributor_converted_date == $today_date){
                    $today_distributor_converted = (int) $distributor_converted_value;
                }
                $distributor_converted = $distributor_converted + $distributor_converted_value;
                $new_distributor_array[$p]['date'] = $distributor_converted_date;
                $new_distributor_array[$p]['value'] = (int) $distributor_converted_value;
                $p++;
            }
        }

        $distributor_lead_count = $this->Slaves->query("SELECT count(id) as distributor_new_lead  FROM leads_new WHERE (creation_date BETWEEN '".$from_date."' AND '".$to_date."') and rm_user_id = $rm_user_id");
        if(!empty($distributor_lead_count)){
            $distributor_lead = $distributor_lead_count[0][0]['distributor_new_lead'];
        }

        $distributor_visited_count = $this->Slaves->query("SELECT count(id) as distributor_visited FROM rm_visit WHERE (DATE_FORMAT(created_date ,'%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."') and rm_user_id = $rm_user_id and type=1");
        if(!empty($distributor_visited_count)){
            $distributor_visited = $distributor_visited_count[0][0]['distributor_visited'];
        }

        $today_retailer_activated = 0;
        $new_retailer_array = array();
        $retailer_activated = 0;

        $new_retailer_query = $this->Slaves->query("SELECT count(r.id) as value,r.rm_shop_update_date as `date`
            FROM retailers r 
            JOIN distributors d ON r.parent_id = d.id
            JOIN rm ON rm.id = d.rm_id
            WHERE (r.rm_shop_update_date BETWEEN '".$from_date."' AND '".$to_date."') and rm.user_id = $rm_user_id GROUP BY rm_shop_update_date ");
            if(!empty($new_retailer_query)){
                $retailer_activated = 0;
                $p=0;
                foreach($new_retailer_query as $new_retailer){
                    $new_retailer_value = $new_retailer[0]['value'];
                    $new_retailer_date = $new_retailer['r']['date'];
                    if($new_retailer_date == $today_date){
                        $today_retailer_activated = (int) $new_retailer_value;
                    }
                    $retailer_activated = $retailer_activated + $new_retailer_value;
                    $new_retailer_array[$p]['date'] = $new_retailer_date;
                    $new_retailer_array[$p]['value'] = (int) $new_retailer_value;
                    $p++;
                }
            }

       
        $retailer_visited_count = $this->Slaves->query("SELECT count(id) as retailer_visited FROM rm_visit WHERE (DATE_FORMAT(created_date ,'%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."') and rm_user_id = $rm_user_id and type=2");
        if(!empty($retailer_visited_count)){
            $retailer_visited = $retailer_visited_count[0][0]['retailer_visited'];
        }


        /**Document Pick up**/

        $document_pick_up = array();
        $querygetRMServices = $this->Relationshipmanager->getRMServices();
        if(!empty($querygetRMServices['document_pickup_list'])){
            $p=0;
            foreach($querygetRMServices['document_pickup_list'] as $service){

                $document_pickup_count_query = $this->Slaves->query("SELECT sum(rvd.pick_up_count) as document_pickup_count 
                FROM rm_visit rv
                JOIN rm_visit_document_pick_up rvd ON rvd.rm_visit_id = rv.id
                WHERE DATE_FORMAT(rv.created_date ,'%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."' and rv.rm_user_id = $rm_user_id and rv.document_pick_up = 1 and rvd.service_id = '".$service['id']."'");
                if(!empty($document_pickup_count_query)){
                    $document_pick_up[$p]['service_name'] = $service['name'];

                    $pickup_count = $document_pickup_count_query[0][0]['document_pickup_count'];
                    if($pickup_count==null || $pickup_count==''){
                        $pickup_count=0;
                    }
                    $document_pick_up[$p]['document_pickup_count'] = $pickup_count;
                }
                $p++;
            }
        }

        /**End Document Pick up**/

        

        /**Primary Secondary Data**/
        $current_primary = 0;
        $average_primary = 0;
        $primary_array = array();
        $current_secondary = 0;
        $average_secondary = 0;
        $secondary_array = array();

        

        $primary_query = $this->Slaves->query("
            SELECT 
                ul.date,sum(ul.topup_buy-ul.topup_reversed) primary_value 
            FROM 
                users_logs ul 
            JOIN 
                distributors d ON (ul.user_id = d.user_id) 
            JOIN 
                rm ON (rm.id = d.rm_id) 
            WHERE 
                ul.date  BETWEEN '".$from_date."' AND '".$to_date."' AND 
                rm.user_id = '".$rm_user_id."' 
            GROUP BY 
                ul.date
            ");
        if(!empty($primary_query)){
            $total_primary = 0;
            $p=0;
            foreach($primary_query as $primary){
                $primary_value = $primary[0]['primary_value'];
                $primary_date = $primary['ul']['date'];
                if($primary_date == $today_date){
                    $current_primary = (int) $primary_value;
                }
                $total_primary = $total_primary + $primary_value;
                $primary_array[$p]['date'] = $primary_date;
                $primary_array[$p]['value'] = (int) $primary_value;
                $p++;
            }

            $average_primary = (float) ($total_primary/$p);
            
        }

        

        $secondary_query = $this->Slaves->query("
            SELECT 
                ul.date,sum(ul.topup_sold) secondary_value 
            FROM 
                users_logs ul 
            JOIN 
                distributors d ON (ul.user_id = d.user_id) 
            JOIN 
                rm ON (rm.id = d.rm_id) 
            WHERE 
                ul.date  BETWEEN '".$from_date."' AND '".$to_date."' AND 
                rm.user_id = '".$rm_user_id."' 
            GROUP BY 
                ul.date
            ");
        if(!empty($secondary_query)){
            $total_secondary = 0;
            $p=0;
            foreach($secondary_query as $secondary){
                $secondary_value = $secondary[0]['secondary_value'];
                $secondary_date = $secondary['ul']['date'];
                if($secondary_date == $today_date){
                    $current_secondary = (int) $secondary_value;
                }
                $total_secondary = $total_secondary + $secondary_value;
                $secondary_array[$p]['date'] = $secondary_date;
                $secondary_array[$p]['value'] = (int) $secondary_value;
                $p++;
            }

            $average_secondary = (float) ($total_secondary/$p);
            
        }
        /**End Primary Secondary Data**/

        /** Active Retailer/Distributor Data**/

        $current_active_distributor = 0;
        $total_active_distributor = 0;
        $active_distributor_array = array();
        $current_active_retailer = 0;
        $total_active_retailer = 0;
        $active_retailer_array = array();

         /**Active Distributor**/

         $total_active_distributor_query = $this->Slaves->query("
            SELECT
                        count(distinct rel.dist_user_id) total_active_distributor
                     FROM 
                        retailer_earning_logs rel 
                     JOIN 
                        distributors d ON (rel.dist_user_id = d.user_id) 
                     JOIN 
                        rm ON (rm.id = d.rm_id) 
                     WHERE 
                        rel.date BETWEEN '".$from_date."' AND '".$to_date."' AND 
                        rm.user_id = '".$rm_user_id."'
        ");
         if(!empty($total_active_distributor_query)){
               $total_active_distributor = $total_active_distributor_query[0][0]['total_active_distributor'];
        
               
            }

         $active_distributor_query = $this->Slaves->query("
            SELECT 
                count(distinct id) as active_distributor,created 
            FROM 
                (SELECT
                        distinct rel.dist_user_id id, rel.date created 
                     FROM 
                        retailer_earning_logs rel 
                     JOIN 
                        distributors d ON (rel.dist_user_id = d.user_id) 
                     JOIN 
                        rm ON (rm.id = d.rm_id) 
                     WHERE 
                        rel.date BETWEEN '".$from_date."' AND '".$to_date."' AND 
                        rm.user_id = '".$rm_user_id."') transactions 
                GROUP BY 
                    created
        ");
         if(!empty($active_distributor_query)){
                $p=0;
        
                foreach($active_distributor_query as $active_distributor){
                    $active_distributor_value = $active_distributor[0]['active_distributor'];
                    $active_distributor_date = $active_distributor['transactions']['created'];
                    if($active_distributor_date == $today_date){
                        $current_active_distributor = (int) $active_distributor_value;
                    }
                    //$total_active_distributor = $total_active_distributor + $active_distributor_value;
                    $active_distributor_array[$p]['date'] = date('Y-m-d',strtotime($active_distributor_date));
                    $active_distributor_array[$p]['value'] = (int) $active_distributor_value;
                    $p++;
                }
            }

            /**Active Retailer**/

            $total_active_retailer_query = $this->Slaves->query("
                    SELECT
                         count(distinct rel.ret_user_id)  total_active_retailer
                    FROM 
                        retailer_earning_logs rel
                    JOIN 
                        retailers r ON (rel.ret_user_id = r.user_id)
                    JOIN 
                        distributors d ON (d.id = r.parent_id) 
                    JOIN 
                        rm ON (rm.id = d.rm_id) 
                    WHERE 
                        rel.date BETWEEN '".$from_date."' AND '".$to_date."' AND 
                        rm.user_id = '".$rm_user_id."'
        ");
         if(!empty($total_active_retailer_query)){
               $total_active_retailer = $total_active_retailer_query[0][0]['total_active_retailer'];
        
               
            }

            $active_retailer_query = $this->Slaves->query("
                SELECT 
                    count(distinct id) as active_retailer,created as date_created
                FROM 
                    (SELECT
                         distinct rel.ret_user_id id, rel.date created 
                    FROM 
                        retailer_earning_logs rel
                    JOIN 
                        retailers r ON (rel.ret_user_id = r.user_id)
                    JOIN 
                        distributors d ON (d.id = r.parent_id) 
                    JOIN 
                        rm ON (rm.id = d.rm_id) 
                    WHERE 
                        rel.date BETWEEN '".$from_date."' AND '".$to_date."' AND 
                        rm.user_id = '".$rm_user_id."') transactions 
                GROUP BY 
                    created
        ");
         if(!empty($active_retailer_query)){
                $p=0;
        
                foreach($active_retailer_query as $active_retailer){
                    $active_retailer_value = $active_retailer[0]['active_retailer'];
                    $active_retailer_date = $active_retailer['transactions']['date_created'];
                    if($active_retailer_date == $today_date){
                        $current_active_retailer = (int) $active_retailer_value;
                    }
                    //$total_active_retailer = $total_active_retailer + $active_retailer_value;
                    $active_retailer_array[$p]['date'] = $active_retailer_date;
                    $active_retailer_array[$p]['value'] = (int) $active_retailer_value;
                    $p++;
                }
            }

            /*** Services***/

        $services = array();
        $get_all_services = $this->Slaves->query("
                    SELECT 
                        s.parent_id as id,
                        s.parent_name as name,
                        count(distinct ret_user_id) as active_retailer,
                        count(distinct dist_user_id) as active_distributor,
                        sum(txn_count) as no_of_transaction
                    FROM 
                        retailer_earning_logs rl 
                    JOIN 
                        services s ON (rl.service_id = s.id) 
                    JOIN 
                        distributors d ON (rl.dist_user_id = d.user_id)
                    JOIN 
                        rm ON (d.rm_id = rm.id)
                    WHERE 
                        rl.date BETWEEN '".$from_date."' AND '".$to_date."' AND 
                        business_update_flag != 0 AND 
                        rm.user_id = '".$rm_user_id."' 
                    GROUP BY 
                        s.parent_id 
                    ORDER BY 
                        s.priority");
        if(!empty($get_all_services)){
            $p=0;
            foreach($get_all_services as $service){
                $service_id = $service['s']['id'];
                $service_name = $service['s']['name'];                
                $active_retailer = $service[0]['active_retailer'];
                $active_distributor = $service[0]['active_distributor'];
                $no_of_transaction = $service[0]['no_of_transaction'];

                $date_value = array();

                $get_date_wise_service = $this->Slaves->query("
                    SELECT  
                        s.parent_name as name,
                        rl.date, sum(amount) as amount 
                    FROM 
                        retailer_earning_logs rl 
                    JOIN 
                        services s ON (rl.service_id = s.id) 
                    JOIN 
                        distributors d ON (rl.dist_user_id = d.user_id)
                    JOIN 
                        rm ON (d.rm_id = rm.id)
                    WHERE 
                        rl.date BETWEEN '".$from_date."' AND '".$to_date."' AND 
                        rm.user_id = '".$rm_user_id."' AND 
                        s.parent_name = '".$service_name."'
                    GROUP BY 
                        s.parent_id,rl.date
                    ");
                if(!empty($get_date_wise_service)){
                    $s=0;
                    foreach($get_date_wise_service as $date_wise_service){
                        $amount_date = $date_wise_service['rl']['date'];
                        $amount = $date_wise_service[0]['amount'];

                        $date_value[$s]['amount_date'] = $amount_date;
                        $date_value[$s]['amount'] = (int) $amount;
                        $s++;
                    }
                }
                $services[$p]['service_id'] = $service_id;
                $services[$p]['service_name'] = $service_name;
                $services[$p]['active_retailer'] = (int) $active_retailer;
                $services[$p]['active_distributor'] = (int) $active_distributor;
                $services[$p]['no_of_transaction'] = (int) $no_of_transaction;

                /***Service Graph***/
                $service_graph = array();
                for($g=0;$g<$total_days;$g++){
                    $graph_date = date('Y-m-d', strtotime($from_date. ' + '.$g.' day'));

                    $service_graph[$g]['amount_date'] = $graph_date;
                     $graph_search_key = array_search($graph_date, array_map(function($term) { return $term['amount_date']; }, $date_value));


                    if($graph_search_key !== FALSE){
                        $service_graph[$g]['amount'] = $date_value[$graph_search_key]['amount'];
                    }else{
                        $service_graph[$g]['amount'] = 0;
                    }

                }

                $services[$p]['graph'] = $service_graph;
                /***End Graph***/
                $p++;
            }
        }



        /***End Services***/

                $primary_graph =array();
                $secondary_graph =array();
                $active_distributor_graph =array();
                $active_retailer_graph =array();
                $new_distributor_graph =array();
                $new_retailer_graph =array();

                for($i=0;$i<$total_days;$i++){
                    $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));

                    /***Primary Graph***/
                     $primary_graph[$i]['date'] = $date;
                     $search_key = array_search($date,array_map(function($term) { return $term['date']; }, $primary_array));
                    if($search_key !== FALSE){
                        $primary_graph[$i]['value'] = $primary_array[$search_key]['value'];
                    }else{
                        $primary_graph[$i]['value'] = 0;
                    }

                    /***Secondary Graph***/
                    $secondary_graph[$i]['date'] = $date;
                     $search_key = array_search($date,array_map(function($term) { return $term['date']; }, $secondary_array));
                    if($search_key !== FALSE){
                        $secondary_graph[$i]['value'] = $secondary_array[$search_key]['value'];
                    }else{
                        $secondary_graph[$i]['value'] = 0;
                    }
                    

                    /***Active Distributor Graph***/
                    $active_distributor_graph[$i]['date'] = $date;
                     $search_key = array_search($date,array_map(function($term) { return $term['date']; }, $active_distributor_array));
                    if($search_key !== FALSE){
                        $active_distributor_graph[$i]['value'] = $active_distributor_array[$search_key]['value'];
                    }else{
                        $active_distributor_graph[$i]['value'] = 0;
                    }

                    /***Active Retailer Graph***/
                    $active_retailer_graph[$i]['date'] = $date;
                     $search_key = array_search($date,array_map(function($term) { return $term['date']; }, $active_retailer_array));
                    if($search_key !== FALSE){
                        $active_retailer_graph[$i]['value'] = $active_retailer_array[$search_key]['value'];
                    }else{
                        $active_retailer_graph[$i]['value'] = 0;
                    }

                    /***New Distributor Graph***/
                    $new_distributor_graph[$i]['date'] = $date;
                     $search_key = array_search($date,array_map(function($term) { return $term['date']; }, $new_distributor_array));
                    if($search_key !== FALSE){
                        $new_distributor_graph[$i]['value'] = $new_distributor_array[$search_key]['value'];
                    }else{
                        $new_distributor_graph[$i]['value'] = 0;
                    }

                    /***New Retailer Graph***/
                    $new_retailer_graph[$i]['date'] = $date;
                     $search_key = array_search($date,array_map(function($term) { return $term['date']; }, $new_retailer_array));
                    if($search_key !== FALSE){
                        $new_retailer_graph[$i]['value'] = $new_retailer_array[$search_key]['value'];
                    }else{
                        $new_retailer_graph[$i]['value'] = 0;
                    }

                }


            $dashboard_array = array(
                "from_date" => $from_date,
                "to_date" => $to_date,
                "current_primary" => $current_primary,
                "average_primary" => $average_primary,
                "current_secondary" => $current_secondary,
                "average_secondary" => $average_secondary,
                "primary_graph" => $primary_graph,
                "secondary_graph" => $secondary_graph,
                "current_active_distributor" => $current_active_distributor,
                "total_active_distributor" => $total_active_distributor,
                "current_active_retailer" => $current_active_retailer,
                "total_active_retailer" => $total_active_retailer,
                "active_distributor_graph" => $active_distributor_graph,
                "active_retailer_graph" => $active_retailer_graph,
                "new_distributor_graph" => $new_distributor_graph,
                "new_retailer_graph" => $new_retailer_graph,
                "current_distributor_converted" => $today_distributor_converted,
                "total_distributor_lead" => $distributor_lead,
                "total_distributor_converted" => $distributor_converted,
                "total_distributor_visited" => $distributor_visited,
                "total_retailer_activated" => $retailer_activated,
                "current_retailer_activated" => $today_retailer_activated,
                "total_retailer_visited" => $retailer_visited,
                "document_pick_up" => $document_pick_up, 
                "services" => $services
            );

        return array('status'=>'success',"dashboard_array" => $dashboard_array, 'description'=>'Dashboard Report');
    }

    function dashboardDistributor($params){

        $dist_id = $params['dist_id'];
        $report_type  = $params['report_type'];
        $report_month  = $params['report_month'];
        $report_year  = $params['report_year'];
        $today_date  = date('Y-m-d');

        $date_range = $this->Relationshipmanager->getdateRange($report_type,$report_month,$report_year);
        $from_date = $date_range['from_date'];
        $to_date = $date_range['to_date'];  
        $total_days = $date_range['total_days'];  

        /*$from_date = "2017-07-22";
        $to_date = "2017-07-31";
        $dist_id = "118";
        $total_days = "10";*/


        /**Primary Secondary Data**/
        $current_primary = 0;
        $average_primary = 0;
        $primary_array = array();
        $current_secondary = 0;
        $average_secondary = 0;
        $secondary_array = array();

        

        $primary_query = $this->Slaves->query("
            SELECT 
                ul.date,sum(ul.topup_buy-ul.topup_reversed) primary_value 
            FROM 
                users_logs ul 
            JOIN 
                distributors d ON (ul.user_id = d.user_id)
            WHERE 
                ul.date  BETWEEN '".$from_date."' AND '".$to_date."' AND 
                d.id = '".$dist_id."'
            GROUP BY 
                ul.date
            ");
        if(!empty($primary_query)){
            $total_primary = 0;
            $p=0;
            foreach($primary_query as $primary){
                $primary_value = $primary[0]['primary_value'];
                $primary_date = $primary['ul']['date'];
                if($primary_date == $today_date){
                    $current_primary = (int) $primary_value;
                }
                $total_primary = $total_primary + $primary_value;
                $primary_array[$p]['date'] = $primary_date;
                $primary_array[$p]['value'] = (int) $primary_value;
                $p++;
            }

            $average_primary = (float) ($total_primary/$p);
            
        }

        

        $secondary_query = $this->Slaves->query("
            SELECT 
                ul.date,sum(ul.topup_sold) secondary_value 
            FROM 
                users_logs ul 
            JOIN 
                distributors d ON (ul.user_id = d.user_id)  
            WHERE 
                ul.date  BETWEEN '".$from_date."' AND '".$to_date."' AND 
                d.id = '".$dist_id."' 
            GROUP BY 
                ul.date
            ");
        if(!empty($secondary_query)){
            $total_secondary = 0;
            $p=0;
            foreach($secondary_query as $secondary){
                $secondary_value = $secondary[0]['secondary_value'];
                $secondary_date = $secondary['ul']['date'];
                if($secondary_date == $today_date){
                    $current_secondary = (int) $secondary_value;
                }
                $total_secondary = $total_secondary + $secondary_value;
                $secondary_array[$p]['date'] = $secondary_date;
                $secondary_array[$p]['value'] = (int) $secondary_value;
                $p++;
            }

            $average_secondary = (float) ($total_secondary/$p);
            
        }
        /**End Primary Secondary Data**/



        
        $current_active_retailer = 0;
        $total_active_retailer = 0;
        $active_retailer_array = array();

        /**Active Retailer**/

        $total_active_retailer_query = $this->Slaves->query("
                    SELECT
                         count(distinct rel.ret_user_id)  total_active_retailer
                    FROM 
                        retailer_earning_logs rel
                    JOIN 
                        retailers r ON (rel.ret_user_id = r.user_id)
                    JOIN 
                        distributors d ON (d.id = r.parent_id)
                    WHERE 
                        rel.date BETWEEN '".$from_date."' AND '".$to_date."' AND 
                        d.id = '".$dist_id."'
        ");
         if(!empty($total_active_retailer_query)){
               $total_active_retailer = $total_active_retailer_query[0][0]['total_active_retailer'];
        
               
            }

            $active_retailer_query = $this->Slaves->query("
                SELECT 
                    count(distinct id) as active_retailer,created as date_created 
                FROM 
                    (SELECT
                         distinct rel.ret_user_id id, rel.date created 
                    FROM 
                        retailer_earning_logs rel
                    JOIN 
                        retailers r ON (rel.ret_user_id = r.user_id)
                    JOIN 
                        distributors d ON (d.id = r.parent_id)
                    WHERE 
                        rel.date BETWEEN '".$from_date."' AND '".$to_date."' AND
                        d.id = '".$dist_id."') transactions 
                GROUP BY 
                    created
        ");
         if(!empty($active_retailer_query)){
                $p=0;
        
                foreach($active_retailer_query as $active_retailer){
                    $active_retailer_value = $active_retailer[0]['active_retailer'];
                    $active_retailer_date = $active_retailer['transactions']['date_created'];
                    if($active_retailer_date == $today_date){
                        $current_active_retailer = (int) $active_retailer_value;
                    }
                    //$total_active_retailer = $total_active_retailer + $active_retailer_value;
                    $active_retailer_array[$p]['date'] = $active_retailer_date;
                    $active_retailer_array[$p]['value'] = (int) $active_retailer_value;
                    $p++;
                }
            }

        /*** Services***/

        $services = array();
        $get_all_services = $this->Slaves->query("
                    SELECT 
                        s.parent_id as id,
                        s.parent_name as name,
                        count(distinct ret_user_id) as active_retailer,
                        count(distinct dist_user_id) as active_distributor,
                        sum(txn_count) as no_of_transaction
                    FROM 
                        retailer_earning_logs rl 
                    JOIN 
                        services s ON (rl.service_id = s.id) 
                    JOIN 
                        distributors d ON (rl.dist_user_id = d.user_id)
                    WHERE 
                        rl.date BETWEEN '".$from_date."' AND '".$to_date."' AND 
                        business_update_flag != 0 AND
                        d.id = '".$dist_id."' 
                    GROUP BY 
                        s.parent_id 
                    ORDER BY 
                        s.priority");
        if(!empty($get_all_services)){
            $p=0;
            foreach($get_all_services as $service){
                $service_id = $service['s']['id'];
                $service_name = $service['s']['name'];                
                $active_retailer = $service[0]['active_retailer'];
                $active_distributor = $service[0]['active_distributor'];
                $no_of_transaction = $service[0]['no_of_transaction'];

                $date_value = array();

                $get_date_wise_service = $this->Slaves->query("
                    SELECT  
                        s.parent_name as name,
                        rl.date, sum(amount) as amount 
                    FROM 
                        retailer_earning_logs rl 
                    JOIN 
                        services s ON (rl.service_id = s.id) 
                    JOIN 
                        distributors d ON (rl.dist_user_id = d.user_id)
                    WHERE 
                        rl.date BETWEEN '".$from_date."' AND '".$to_date."' AND
                        d.id = '".$dist_id."' AND 
                        s.parent_name = '".$service_name."'
                    GROUP BY 
                        s.parent_id,rl.date
                    ");
                if(!empty($get_date_wise_service)){
                    $s=0;
                    foreach($get_date_wise_service as $date_wise_service){
                        $amount_date = $date_wise_service['rl']['date'];
                        $amount = $date_wise_service[0]['amount'];

                        $date_value[$s]['amount_date'] = $amount_date;
                        $date_value[$s]['amount'] = (int) $amount;
                        $s++;
                    }
                }
                $services[$p]['service_id'] = $service_id;
                $services[$p]['service_name'] = $service_name;
                $services[$p]['active_retailer'] = (int) $active_retailer;
                $services[$p]['active_distributor'] = (int) $active_distributor;
                $services[$p]['no_of_transaction'] = (int) $no_of_transaction;

                /***Service Graph***/
                $service_graph = array();
                for($g=0;$g<$total_days;$g++){
                    $graph_date = date('Y-m-d', strtotime($from_date. ' + '.$g.' day'));

                    $service_graph[$g]['amount_date'] = $graph_date;
                     $graph_search_key = array_search($graph_date, array_map(function($term) { return $term['amount_date']; }, $date_value));


                    if($graph_search_key !== FALSE){
                        $service_graph[$g]['amount'] = $date_value[$graph_search_key]['amount'];
                    }else{
                        $service_graph[$g]['amount'] = 0;
                    }

                }

                $services[$p]['graph'] = $service_graph;
                /***End Graph***/
                $p++;
            }
        }

        /***End Services***/

                $primary_graph =array();
                $secondary_graph =array();

                for($i=0;$i<$total_days;$i++){
                    $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));

                    /***Primary Graph***/
                     $primary_graph[$i]['date'] = $date;
                     $search_key = array_search($date,array_map(function($term) { return $term['date']; }, $primary_array));
                    if($search_key !== FALSE){
                        $primary_graph[$i]['value'] = $primary_array[$search_key]['value'];
                    }else{
                        $primary_graph[$i]['value'] = 0;
                    }

                    /***Secondary Graph***/
                    $secondary_graph[$i]['date'] = $date;
                     $search_key = array_search($date,array_map(function($term) { return $term['date']; }, $secondary_array));
                    if($search_key !== FALSE){
                        $secondary_graph[$i]['value'] = $secondary_array[$search_key]['value'];
                    }else{
                        $secondary_graph[$i]['value'] = 0;
                    }
                }


            $dashboard_array = array(
                "from_date" => $from_date,
                "to_date" => $to_date,
                "total_sale" => $total_sale,
                "current_primary" => $current_primary,
                "average_primary" => $average_primary,
                "current_secondary" => $current_secondary,
                "average_secondary" => $average_secondary,
                "primary_graph" => $primary_graph,
                "secondary_graph" => $secondary_graph,
                "total_active_retailer" => $total_active_retailer,
                "services" => $services
            );

        return array('status'=>'success',"dashboard_array" => $dashboard_array, 'description'=>'Distributor Dashboard Report');
    }

    function dashboardRetailer($params){

        $ret_id = $params['ret_id'];
        $report_type  = $params['report_type'];
        $report_month  = $params['report_month'];
        $report_year  = $params['report_year'];
        $today_date  = date('Y-m-d');

        $date_range = $this->Relationshipmanager->getdateRange($report_type,$report_month,$report_year);
        $from_date = $date_range['from_date'];
        $to_date = $date_range['to_date'];  
        $total_days = $date_range['total_days'];

        /*$from_date = "2017-07-22";
        $to_date = "2017-07-31";
        $dist_id = "118";
        $total_days = "10";*/


        /*** Services***/

        $services = array();
        $get_all_services = $this->Slaves->query("
                    SELECT 
                        s.parent_id as id,
                        s.parent_name as name,
                        count(distinct ret_user_id) as active_retailer,
                        count(distinct dist_user_id) as active_distributor,
                        sum(txn_count) as no_of_transaction
                    FROM 
                        retailer_earning_logs rl 
                    JOIN 
                        services s ON (rl.service_id = s.id) 
                    JOIN 
                        retailers r ON (rl.ret_user_id = r.user_id)
                    WHERE 
                        rl.date BETWEEN '".$from_date."' AND '".$to_date."' AND 
                        business_update_flag != 0 AND
                        r.id = '".$ret_id."' 
                    GROUP BY 
                        s.parent_id 
                    ORDER BY 
                        s.priority");
        if(!empty($get_all_services)){
            $p=0;
            foreach($get_all_services as $service){
                $service_id = $service['s']['id'];
                $service_name = $service['s']['name'];                
                $active_retailer = $service[0]['active_retailer'];
                $active_distributor = $service[0]['active_distributor'];
                $no_of_transaction = $service[0]['no_of_transaction'];

                $date_value = array();

                $get_date_wise_service = $this->Slaves->query("
                    SELECT  
                        s.parent_name as name,
                        rl.date, sum(amount) as amount 
                    FROM 
                        retailer_earning_logs rl 
                    JOIN 
                        services s ON (rl.service_id = s.id) 
                    JOIN 
                        retailers r ON (rl.ret_user_id = r.user_id)
                    WHERE 
                        rl.date BETWEEN '".$from_date."' AND '".$to_date."' AND
                        r.id = '".$ret_id."' AND 
                        s.parent_name = '".$service_name."'
                    GROUP BY 
                        s.parent_id,rl.date
                    ");
                if(!empty($get_date_wise_service)){
                    $s=0;
                    foreach($get_date_wise_service as $date_wise_service){
                        $amount_date = $date_wise_service['rl']['date'];
                        $amount = $date_wise_service[0]['amount'];

                        $date_value[$s]['amount_date'] = $amount_date;
                        $date_value[$s]['amount'] = (int) $amount;
                        $s++;
                    }
                }
                $services[$p]['service_id'] = $service_id;
                $services[$p]['service_name'] = $service_name;
                $services[$p]['active_retailer'] = (int) $active_retailer;
                $services[$p]['active_distributor'] = (int) $active_distributor;
                $services[$p]['no_of_transaction'] = (int) $no_of_transaction;

                /***Service Graph***/
                $service_graph = array();
                for($g=0;$g<$total_days;$g++){
                    $graph_date = date('Y-m-d', strtotime($from_date. ' + '.$g.' day'));

                    $service_graph[$g]['amount_date'] = $graph_date;
                     $graph_search_key = array_search($graph_date, array_map(function($term) { return $term['amount_date']; }, $date_value));


                    if($graph_search_key !== FALSE){
                        $service_graph[$g]['amount'] = $date_value[$graph_search_key]['amount'];
                    }else{
                        $service_graph[$g]['amount'] = 0;
                    }

                }

                $services[$p]['graph'] = $service_graph;
                /***End Graph***/
                $p++;
            }
        }

        /***End Services***/

            $dashboard_array = array(
                "from_date" => $from_date,
                "to_date" => $to_date,
                "total_sale" => $total_sale,
                "services" => $services
            );

        return array('status'=>'success',"dashboard_array" => $dashboard_array, 'description'=>'Retailer Dashboard Report');
    }

    
}
