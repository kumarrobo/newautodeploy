<?php

class KitDeliverySystemController extends AppController {

    var $name = 'KitDeliverySystem';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator', 'GChart','Csv');
    var $components = array('RequestHandler', 'Shop', 'Bridge','Serviceintegration','Servicemanagement','General','Documentmanagement');
    var $uses = array('User','Slaves');


    function kitDeliveryPanel(){
        $this->layout= 'plain';

        $fromdate       = $this->params['form']['kit_deliveryFrom'];
        $todate         = $this->params['form']['kit_deliveryTo'];
        $service        = $this->params['form']['kit_deliveryServices'];
        $deliverystatus = $this->params['form']['kit_deliveryStatus'];
        $src            = $this->params['form']['kit_deliverySource'];
        $retId          = $this->params['form']['kit_deliveryRetailer'];
        $distId         = $this->params['form']['kit_deliveryDistributor'];

        $fromd          = ($fromdate)?$fromdate:date("Y-m-d");
        $tod            = ($todate)?$todate:date("Y-m-d");

        if($retId != ''){
         $retailerId = "AND r.id = $retId ";
        }else {
         $retailerId = '';
        }
        if($distId != '' && $distId != 0 ){
         $distributorId = "AND d.id = $distId ";
        }else{
            $distributorId = '';
        }
        if ($deliverystatus != '' && $deliverystatus != '-1') {
            $dstatus = "AND kdl.dispatch_status = $deliverystatus ";
        } else {
            $dstatus = '';
        }
        if ($service != '' && $service != 0 ) {
            $servc = "AND kdl.service_id = $service ";
        } else {
            $servc = '';
        }
        if ($src != '' && $src != 0) {
            $source = "AND kdl.group_id = $src ";
        } else {
            $source = '';
        }


        $kitInfo = $this->Slaves->query('Select kdl.*,r.id as retailer_id,r.parent_id,r.shopname,r.user_id,d.id as distributor_id,d.user_id,d.company,p.plan_name '
                . 'from kit_delivery_log as kdl '
                . 'LEFT JOIN retailers as r ON (kdl.ret_user_id = r.user_id) '
                . 'LEFT JOIN distributors as d ON (kdl.dist_user_id = d.user_id)'
                . 'LEFT JOIN service_plans as p ON (kdl.service_plan_id = p.id) where kdl.purchased_date >= "'.$fromd.'" AND kdl.purchased_date <= "'.$tod.'" '. $retailerId .' '
                . ''. $distributorId .' '. $dstatus .' '.$servc.' '.$source.'');

        $serviceName = $this->Serviceintegration->getServiceNames();

        $ret_userids = array_map(function($element) {
            return $element['kdl']['ret_user_id'];
        }, $kitInfo);
        $imp_data = $this->Shop->getUserLabelData($ret_userids, 2, 0);
        $dist_userid = array_map(function($element) {
            return $element['kdl']['dist_user_id'];
        }, $kitInfo);
        $imp_data2 = $this->Shop->getUserLabelData($dist_userid, 2, 0);

        $serviceprod = $this->Slaves->query("select id,name from services where id > '7' AND toShow = '1' ");
        $servicename = array();
        foreach($serviceprod as $servc){
        $servicename[$servc['services']['id']]   = $servc['services']['name'];
        }


        $i = 0;

            foreach($kitInfo as $kit) {
                $kitDetails[$i]['val']              = $kit['kdl']['id'];
                $kitDetails[$i]['dist_id']          = ($kit['r']['parent_id'])?$kit['r']['parent_id']:$kit['d']['distributor_id'];
                $kitDetails[$i]['id']               = ($kit['r']['retailer_id'])?$kit['r']['retailer_id']:$kit['d']['distributor_id'];
                $kitDetails[$i]['dist_userid']      =  $kit['kdl']['dist_user_id'];
                $kitDetails[$i]['company']          =  ($kit['kdl']['ret_user_id'])?$imp_data[$kit['kdl']['ret_user_id']]['imp']['shop_est_name']:$imp_data2[$kit['kdl']['dist_user_id']]['imp']['shop_est_name'];
                $kitDetails[$i]['source']           = ($kit['kdl']['group_id'] == '5')?'Distributor':'Retailers';
                $kitDetails[$i]['service']          =  $servicename[$kit['kdl']['service_id']];
                $kitDetails[$i]['serviceid']        =  $kit['kdl']['service_id'];
                $kitDetails[$i]['kits']             = $kit['kdl']['kits'];
                $kitDetails[$i]['plan_val']         = $kit['kdl']['service_plan_id'];
                $kitDetails[$i]['kit_plan']         = $kit['p']['plan_name'];
                $kitDetails[$i]['purchase_date']    = $kit['kdl']['purchased_date'];
                $kitDetails[$i]['delivery_by']      = $kit['kdl']['delivery_by'];
                $kitDetails[$i]['delivery_address'] = $kit['kdl']['delivery_address'];
                $kitDetails[$i]['device_id']        = $kit['kdl']['device_ids'];
                $kitDetails[$i]['dispatch_status']  = $kit['kdl']['dispatch_status'];
                $kitDetails[$i]['dispatch_date']    = $kit['kdl']['dispatch_date'];
                $kitDetails[$i]['delivery_date']    = $kit['kdl']['delivery_date'];
                $kitDetails[$i]['delivery_time']    = $kit['kdl']['delivery_timestamp'];
                $kitDetails[$i]['tracking_details'] = $kit['kdl']['tracking_details'];
                $kitDetails[$i]['comments']         = $kit['kdl']['comment'];
                $i++;
            }



        $this->set('kitInfo',$kitInfo);
        $this->set('kitDetails',$kitDetails);
        $this->set('serviceName',$serviceName);
        $this->set('fromdate',$fromd);
        $this->set('todate',$tod);
        $this->set('services',$service);
        $this->set('deliverystatus',$deliverystatus);
        $this->set('src',$src);
        $this->set('retId',$retId);
        $this->set('distId',$distId);

    }

    function kitDeliveryUpdate(){
        $this->autoRender = False;


        $id              = $this->params['form']['id'];
        $deliveryBy      = $this->params['form']['deliveryBy'];
        $deliveryAddr    = $this->params['form']['deliveryAddr'];
        $deviceId        = $this->params['form']['deviceId'];
        $deliveryStatus  = $this->params['form']['deliveryStatus'];
        $trackingDet     = $this->params['form']['trackingDet'];
        $comment         = $this->params['form']['comment'];
        $deliveryDates    = trim($this->params['form']['delDate']);
        $service_id      = $this->params['form']['service_id'];
        $selected_plan   = $this->params['form']['selected_plan'];
        $requested_plan  = $this->params['form']['requested_plan'];
        $distributor_id  = $this->params['form']['dist_id'];
        $distuser_id     = $this->params['form']['dist_user_id'];
        if($deliveryStatus == 0){
                if( !empty($deliveryBy) || !empty($deliveryAddr) || !empty($deviceId) || !empty($trackingDet) )
                {
                    echo json_encode(
                            array(
                                'status' => 'failure',
                                'description' => 'Delivery status can not be pending'
                    ));
                    exit;
                }
                $kitupdDetails = $this->User->query('Update kit_delivery_log set '
                                                        . 'comment = "'.$comment.'" where id = '. $id);
        }
        elseif($deliveryStatus > 0){            //deliverystatus other than pending
            if(($deliveryBy == "" ) || ($deliveryAddr == "") || ($deviceId == "") || ($trackingDet == "") )
            {  echo json_encode(array('status' => 'failure',
                                        'description' => 'Delivery By,Delivery Address,Device Id,Tracking Det any of this is empty'
                ));exit;
            } elseif( in_array($deliveryStatus,array(1,2)) ) {

                if($deliveryStatus == 1 && $deliveryBy == 1){ // delivery by distributor and status is dispatched( provide commission to distributor)
                    $plans = $this->Serviceintegration->getServicePlans();
                    $temp = json_decode($plans,true);

                    foreach ($temp[$service_id] as $key => $plan ) {
                        $service_plans[$plan['id']] = $plan;

                    }
                 $services = $this->Serviceintegration->getServiceDetails();
                 $services = json_decode($services,true);
                   $req_plan_price = $service_plans[$requested_plan]['setup_amt'];
                   $sel_plan_price   = $service_plans[$selected_plan]['setup_amt'];
                   if($sel_plan_price ==  $req_plan_price){ //for same plan
                       $dist_comm_amt = $sel_plan_price;
                    }elseif ($sel_plan_price >  $req_plan_price) { //for greater selected same plan
                       $dist_comm_amt = $sel_plan_price - ( $service_plans[$selected_plan]['dist_commission'] - $service_plans[$requested_plan]['dist_commission'] );
                    }elseif ($sel_plan_price <  $req_plan_price) { //for smaller selected same plan
                        $dist_comm_amt = $sel_plan_price + ( $service_plans[$requested_plan]['dist_commission'] - $service_plans[$selected_plan]['dist_commission'] );
                    }
                     $dataSource = $this->User->getDataSource();
                     $dataSource->begin();
                    if($dist_comm_amt > 0) {
                        $description = 'commission for purchasing plan  '.$service_plans[$selected_plan]['plan_name'].' against '.$services[$service_id]['name'].' service for retailer  from distributor.';
                        $wallet_dist_res = $this->Servicemanagement->distCommission($dist_comm_amt, $distributor_id, $distuser_id, $service_id, $description, $dataSource, null, $settle_flag);
                        if ($wallet_dist_res['status'] == 'failure') {
                            $dataSource->rollback();
                            return json_encode(array(
                                'status' => 'failure',
                                'description' => 'Couldn\'t dispatch  the kits.' . $wallet_dist_res['description']
                            ));
                        }
                    }
                }elseif($deliveryStatus == 1 && $deliveryBy == 0) { // delivery by Inventory and status is dispatched( provide commission to distributor)
                    $plans = $this->Serviceintegration->getServicePlans();
                    $temp = json_decode($plans,true);
                    foreach ($temp[$service_id] as $key => $plan ) {
                        $service_plans[$plan['id']] = $plan;
                    }
                 $services = $this->Serviceintegration->getServiceDetails();
                 $services = json_decode($services,true);
                   $dist_comm_amt =  $service_plans[$requested_plan]['dist_commission'];

                   $dataSource = $this->User->getDataSource();
                     $dataSource->begin();
                        $description = 'commission for purchasing plan  '.$service_plans[$selected_plan]['plan_name'].' against  '.$services[$service_id]['name'].' service for retailer from inventory .';
                        $wallet_dist_res = $this->Servicemanagement->distCommission($dist_comm_amt, $distributor_id, $distuser_id, $service_id, $description, $dataSource, null, $settle_flag);
                        if ($wallet_dist_res['status'] == 'failure') {
                            $dataSource->rollback();
                            return json_encode(array(
                                'status' => 'failure',
                                'description' => 'Couldn\'t dispatch  the kits.' . $wallet_dist_res['description']
                            ));
                        }
                }
                $delivery_date = '';
                if(($deliveryStatus == 2) && !empty($deliveryDates) && ($_SESSION['Auth']['User']['group_id'] != SUPER_ADMIN)){

                    echo json_encode(array('status' => 'Failure',
                     'description' => 'Sorry you dont\'t  have enough permission'
                    ));exit;
                }
                else if(($deliveryStatus == 2) && ($_SESSION['Auth']['User']['group_id'] != SUPER_ADMIN)){
                    $delivery_date = ',delivery_date = "'.date('Y-m-d').'",delivery_timestamp = "'.date('Y-m-d H:i:s').'"';

                }

                $dataSource = $this->User->getDataSource();
                $dataSource->begin();
                $kitupdDetails = $dataSource->query('Update kit_delivery_log set '
                                                        . 'delivery_by  = '.$deliveryBy.',delivery_address = "'.$deliveryAddr.'", dispatch_date = "'. date('Y-m-d').'",dispatch_timestamp = "'. date('Y-m-d H:i:s').'",'
                                                        . 'device_ids  = "'.$deviceId.'", dispatch_status = '. $deliveryStatus .', tracking_details = "'. $trackingDet .'"'
                                                        . ', comment = "'.$comment.'" '.$delivery_date.' where id = '. $id);
                if($kitupdDetails){
                    $dataSource->commit();
                    echo json_encode(array('status' => 'success',
                    'msg' => 'Report Updated Successfully'
                   ));exit;
                }else{
                    $dataSource->rollback();
                     echo json_encode(array('status' => 'Failure',
                     'description' => 'Some issue occurred'
                    ));exit;

                }
            }
        }
   }
   function getkitsData(){
       $this->autoRender = FALSE;

       $id          = $this->params['form']['id'];
       $dist_id     = $this->params['form']['dist_id'];
       $service_id  = $this->params['form']['service_id'];


       $kits_details = $this->Slaves->query('Select dk.*,sp.plan_name from distributors_kits as dk INNER JOIN service_plans as sp '
               . 'ON (sp.id = dk.service_plans_id) where dk.distributor_id = '.$dist_id.' and dk.service_id = '.$service_id.'');

       return json_encode($kits_details);

   }

   function serviceRegistration(){
       $this->layout = 'plain';

        $fromdatea       = $this->params['form']['servc_registrationAssgnFrom'];
        $todatea         = $this->params['form']['servc_registrationAssgnTo'];
        $fromdater       = $this->params['form']['servc_registrationReqFrom'];
        $todater         = $this->params['form']['servc_registrationReqTo'];
        $services        = $this->params['form']['servc_registrationServices'];
        $status         = $this->params['form']['servc_registrationStatus'];
        $src            = $this->params['form']['servc_registrationSource'];
        $retId          = $this->params['form']['servc_registrationRetailer'];
        $distId         = $this->params['form']['servc_registrationDistributor'];
        $ret_mobile         = $this->params['form']['servc_registrationMobile'];


        $froms          = ($fromdater)?$fromdater:date("Y-m-d");
        $tos            = ($todater)?$todater:date("Y-m-d");

        $service = $this->Slaves->query("select id,name from services where id > '7' AND toShow = '1' ");

         if($retId != ''){
         $retailerId = "AND r.id = $retId ";
        }else {
         $retailerId = '';
        }

        if($ret_mobile != ''){
            $retailerMobile= "AND r.mobile = $ret_mobile ";
        } else {
            $retailerMobile = '';
        }

        if($distId != '' && $distId != 0 ){
         $distributorId = "AND r.parent_id = $distId ";
        }else{
            $distributorId = '';
        }
        if( $fromdatea != '' && $todatea != '') {
            $requestdate = " srl.kit_purchase_date >= '".$fromdatea."' and srl.kit_purchase_date <= '".$todatea."'  OR srl.service_request_date  >= '".$froms."' AND srl.service_request_date <= '".$tos."' ";
        }else {
            $requestdate = " srl.service_request_date  >= '". $froms ."' AND srl.service_request_date <= '". $tos ."' ";
        }

        if ($services != '' && $services != 0 ) {
            $servc = "AND srl.service_id = $services ";
        } else {
            $servc = '';
        }
        if ($src != '') {
            $source = "AND srl.source LIKE '%".$src ."%' ";
        } else {
            $source = '';
        }
        if ($status != '' && $status != '-1' ) {
            $status_v = "AND us.service_flag = $status ";
        } else {
            $status = -1;
            $status_v = '';
        }



     $serviceRegistration = $this->Slaves->query('Select srl.*,us.service_flag,us.created_on,r.id,r.mobile,r.parent_id,r.id from service_request_log as srl '
             . ' LEFT JOIN users_services as us ON (srl.ret_user_id = us.user_id AND srl.service_id = us.service_id)'
             . ' LEFT JOIN retailers as r ON (srl.ret_user_id = r.user_id)'
             . ' where '.$requestdate.''
             . '  '. $servc .' '. $status_v .' '. $retailerId.' '.$retailerMobile.' '.$distributorId.' '. $source .' ');

        $service_status  = Configure::read('service_status');



        $ret_userid = array_map(function($element) {
            return $element['srl']['ret_user_id'];
        }, $serviceRegistration);


        $imp_data = $this->Shop->getUserLabelData($ret_userid,2,0);

        $serviceprod = $this->Slaves->query("select id,name from services where id > '7' AND toShow = '1' ");
        $servicename = array();
        foreach($serviceprod as $servc){
        $servicename[$servc['services']['id']]   = $servc['services']['name'];
        }
        $i = 0;

            foreach($serviceRegistration as $servc) {
                $servcRegist[$i]['ret_mobile']          =  $servc['r']['mobile'];
                $servcRegist[$i]['id']                  =  $servc['srl']['id'];
                $servcRegist[$i]['kit_purchase']        = $servc['srl']['kit_purchase_date'];
                $servcRegist[$i]['service_request']     = $servc['srl']['service_request_date'];
                $servcRegist[$i]['retailer_id']         = $imp_data[$servc['srl']['ret_user_id']]['ret']['id'];
                $servcRegist[$i]['user_id']             = $servc['srl']['ret_user_id'];
                $servcRegist[$i]['shop_name']           = $imp_data[$servc['srl']['ret_user_id']]['imp']['shop_est_name'];
                $servcRegist[$i]['source']              = $servc['srl']['source'];
                $section_info = $this->Documentmanagement->getUserSectionReport($servc['srl']['ret_user_id'],$servc['srl']['service_id']);
                $section_status = array();
                foreach( $section_info[$servc['srl']['ret_user_id']][$servc['srl']['service_id']]['sections'] as $section_name => $ss ){
                    $section_status[] = $section_name.':<b>'.$ss.'</b>';
                }
                $servcRegist[$i]['doc_status']              = $section_status;

                $servcRegist[$i]['service']             = $servicename[$servc['srl']['service_id']];
                $servcRegist[$i]['service_id']          = $servc['srl']['service_id'];
                $servcRegist[$i]['status']              = $servc['us']['service_flag'];
                $servcRegist[$i]['activation_status']   = $service_status[$servc['us']['service_flag']];
                $servcRegist[$i]['activation_date']     = $servc['us']['created_on'];
                $servcRegist[$i]['vendor_activation']   = $servc['srl']['vendor_activation'];
                $servcRegist[$i]['comments']            = $servc['srl']['comment'];
                $i++;
            }

     $this->set('serviceRegistration',$servcRegist);
     $this->set('service_status',$service_status);
     $this->set('serviceName',$service);
     $this->set('fromassgndate',$fromdatea);
     $this->set('toassgndate',$todatea);
     $this->set('fromreqdate',$froms);
     $this->set('toreqdate',$tos);
     $this->set('distId',$distId);
     $this->set('status',$status);
     $this->set('src',$src);
     $this->set('services',$services);
     $this->set('retId',$retId);
     $this->set('distId',$distId);
     $this->set('ret_mobile',$ret_mobile);
   }

   function UpdserviceRegistration(){
       $this->autoRender = FALSE;

       $id          = $this->params['form']['id'];
       $status      = $this->params['form']['status'];
       $comment     = $this->params['form']['comment'];
       $user_id     = $this->params['form']['user_id'];
       $service_id  = $this->params['form']['service_id'];
       $vendorAct   = $this->params['form']['vendorAct'];


       $currStatus = $this->Slaves->query('select service_flag from  users_services where user_id = '. $user_id . ' and service_id =  ' . $service_id .' ');

       $neededStatus = array("5","6","7");
       $currentStatus = ($currStatus[0]['users_services']['service_flag']);
//
       $dataSource = $this->User->getDataSource();
        $dataSource->begin();

       if( isset($id) && ($currentStatus != $status) ){
           $service_status  = Configure::read('service_status');
            if( !in_array($status,$neededStatus) ){

                echo json_encode(array('status' => 'Failure',
                    'description' => 'You are not allowed to change the status to '.$service_status[$status]
                ));
                exit;
            }

            if($status  == 5 && !in_array($currentStatus,array(4))){
                echo json_encode(array('status' => 'Failure',
                    'description' => 'You are not allowed to change the status to '.$service_status[$status]
                ));
                exit;
            }
            if( $status  == 6 && !in_array($currentStatus,array(4,5)) ){
                echo json_encode(array('status' => 'Failure',
                    'description' => 'You are not allowed to change the status to '.$service_status[$status]
                ));
                exit;

            }
            if( $status  == 7 && !in_array($currentStatus,array(6)) ){
                echo json_encode(array('status' => 'Failure',
                    'description' => 'You are not allowed to change the status to '.$service_status[$status]
                ));
                exit;
            }
            if( $status == 6 ){
                $section_info = $this->Documentmanagement->getUserSectionReport($user_id,$service_id);

                $all_sections_approved = true;
                foreach( $section_info[$user_id][$service_id]['sections'] as $section_name => $ss ){
                    if( strtolower($ss) != 'approved' ){
                        $all_sections_approved = false;
                        break;
                    }
                }
                if( !$all_sections_approved && ($_SESSION['Auth']['User']['group_id'] != SUPER_ADMIN) ){
                    echo json_encode(array('status' => 'Failure',
                        'description' => 'All documents need to be approved in order to perform this action'
                    ));
                    exit;
                }
            }
            $updUsersServices  = $dataSource->query("Update `users_services` SET service_flag = '$status' where user_id =  $user_id  and service_id =   $service_id ");
            if($updUsersServices){
                    $ServiceRegHistory = $dataSource->query("INSERT INTO `service_request_history`(`service_request_id`, `service_flag`, `date`, `timestamp`, `action_by`) "
               . "                                 VALUES ($id,$status,'".date("Y-m-d")."','".date("Y-m-d H:i:s")."',".$_SESSION['Auth']['User']['id'].")");

            }
       }
        $updServiceRequest = $dataSource->query("Update `service_request_log` SET  vendor_activation = '$vendorAct',comment =  '$comment' where id = $id");
        if($updServiceRequest) {
            $dataSource->commit();
                echo json_encode(array('status' => 'success',
                'msg' => 'Report Updated Successfully'
            ));
            exit;
        } else {
            $dataSource->rollback();
            echo json_encode(array('status' => 'Failure',
            'description' => 'Some error Occured while Updation'
            ));
            exit;
        }
    }
}