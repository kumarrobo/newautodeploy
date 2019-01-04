<?php

class PanServicesController extends AppController {
    
    var $name = 'PanServices';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator', 'GChart','Csv');
    var $components = array('RequestHandler', 'Shop', 'Bridge','General','Serviceintegration');           
    var $uses = array('User','Slaves','Pan'); 

    function panServicePanel(){
        $this->layout = 'plain';

        $getval     = $this->params['form']['search'];
        $panfrm     = $this->params['form']['pan_serviceFrom'];
        $panto      = $this->params['form']['pan_serviceTo'];
        $panstatus  = $this->params['form']['pan_serviceStatus'];
        $ret_id    = trim($this->params['form']['pan_serviceRetid']);
        $act_id    = trim($this->params['form']['pan_serviceAgId']);
        $export    = $this->params['form']['pan_excel'];    

        if (empty($panfrm))
        $panfrm = date('Y-m-d');
        if(empty($panto))
            $panto = date('Y-m-d');
        $from = date("Y-m-d", strtotime($panfrm));
        $to   = date("Y-m-d", strtotime($panto));

        if(empty($panstatus)){
            $status = '';
        }else {
            
            $status = "and status = $panstatus";
        }
        
        if(empty($act_id)){
            $agent_id = '';
        }else {
            
            $agent_id = "and agent_id = '$act_id'";
        }
     
        if(empty($ret_id)){
        
            $retailer_id = "";
        }else {
        //for getting userid from entered Retailer id.
        $imp_data_retId = $this->Shop->getUserLabelData($ret_id,2,2);
        $retailerid =  ($imp_data_retId[$ret_id]['ret']['user_id']);       
        $retailer_id =  "and user_id = $retailerid";
        }
        
        
        $panDetails = $this->Pan->query('Select * from coupon_requests' 
                . ' where status NOT IN (0,3) AND created_date  >= "' . $from . '" and created_date <= "' . $to . '" '. $status .' '. $agent_id .' '. $retailer_id .' order by id desc');
       
        $otherDetails = $this->Pan->query('SELECT status,SUM(quantity) as num,SUM(total_amount) as amount FROM `coupon_requests` where created_date  >= "' . $from . '" and created_date <= "' . $to . '" group by status');
        $couponCount = array();
        $couponAmt   = array(); 
        foreach($otherDetails as $other){
            $couponCount[$other['coupon_requests']['status']] =  $other[0]['num'];
            $couponAmt[$other['coupon_requests']['status']] =  $other[0]['amount'];
        }
        
        $reailerId = array();
        //if(count($panDetails) > 0 ){

            /** IMP DATA ADDED : START**/
            $ret_ids = array_map(function($element){
                return $element['coupon_requests']['user_id'];
            },$panDetails);
            
           $ret_userid =  implode("','", $ret_ids);            
            $user_id = array_map(function($element) {
                return $element['coupon_requests']['alloted_by'];
            }, $panDetails);
            $user = implode("','",$user_id);
        $username = $this->Slaves->query("Select id,name from users where id IN (' $user ')");    
            
        $users = array();
        foreach($username as $usr){
            $users[$usr['users']['id']] = $usr['users']['name'];
        }        
            $imp_data_retId = $this->Shop->getUserLabelData($ret_ids,2,0);
            
                /** IMP DATA ADDED : END**/
           $DistDet = $this->Slaves->query("Select r.parent_id,r.user_id,d.id,d.company,d.user_id "
                   . "from retailers as r LEFT JOIN distributors as d ON (r.parent_id = d.id) "
                   . "where r.user_id IN ('" . $ret_userid ."' )");
//        }        
           $dist_userids = array();
           $dist_company = array();
           $dist_id     = array();
           foreach($DistDet as $distDetails){
             $dist_id[$distDetails['r']['user_id']]        = $distDetails['d']['id'];
             $dist_userids[$distDetails['r']['user_id']]   = $distDetails['d']['user_id'];
             $dist_company[$distDetails['r']['user_id']]   = $distDetails['d']['company'];
           }
           
        $dist_userid = array_map(function($element) {
            return $element['d']['user_id'];
        }, $DistDet);
        
        
        $distUsrid = implode("','",$dist_userid);
        $distLocation = $this->Slaves->query("select rl.user_id,rl.area_id,la.city_id,la.name,lc.state_id,lc.name,
                                             ls.name from retailers_location as rl left join locator_area as la on (rl.area_id = la.id)
                                             left join locator_city as lc  on (la.city_id = lc.id)
                                             left join locator_state as ls on (lc.state_id = ls.id)
                                             where rl.user_id IN ('$distUsrid')");
        foreach ($distLocation as $distLoc) {
            $distributor_city[$distLoc['rl']['user_id']] = $distLoc['lc']['name'];
            $distributor_state[$distLoc['rl']['user_id']] = $distLoc['ls']['name'];
        }
        
        $pan_status = array(-1 => 'Pending',1 => 'Alloted',2 => 'Refunded');
                $i = 0;
            foreach($panDetails as $row){
                $pans[$i]['id']             = $row['coupon_requests']['id'];
                $pans[$i]['ret_id']         = $imp_data_retId[$row['coupon_requests']['user_id']]['ret']['id'];
                $pans[$i]['shop_name']      = $imp_data_retId[$row['coupon_requests']['user_id']]['imp']['shop_est_name'];
                $pans[$i]['dist_id']        = $dist_id[$row['coupon_requests']['user_id']];
                $pans[$i]['dist_company']   = $dist_company[$row['coupon_requests']['user_id']];
                $pans[$i]['dist_userid']    = $dist_userids[$row['coupon_requests']['user_id']];
                $pans[$i]['dist_city']      = $distributor_city[$pans[$i]['dist_userid']];
                $pans[$i]['dist_state']     = $distributor_state[$pans[$i]['dist_userid']];
                $pans[$i]['mobile']         = $row['coupon_requests']['retailer_number'];
                $pans[$i]['agent_id']       = $row['coupon_requests']['agent_id'];
                $pans[$i]['quantity']       = $row['coupon_requests']['quantity'];
                $pans[$i]['amount']         = $row['coupon_requests']['total_amount'];
                $pans[$i]['couponamount']   = $row['coupon_requests']['amount'];
                $pans[$i]['status']         = $pan_status[$row['coupon_requests']['status']];
                $pans[$i]['statusval']      = $row['coupon_requests']['status'];
                $pans[$i]['request_date']   = $row['coupon_requests']['created_at'];
                $pans[$i]['alloted_date']   = $row['coupon_requests']['updated_at'];
                $pans[$i]['alloted_by']     = $users[$row['coupon_requests']['alloted_by']];
                $pans[$i]['comment']        = $row['coupon_requests']['comment'];                               
                $i++;                        
           }
           
          
        if($export == "") {
        $this->set('pandetails',$pans);
        $this->set('panfrm',$panfrm);
        $this->set('panto',$panto);
        $this->set('status',$panstatus);
        $this->set('ret_id',$ret_id);
        $this->set('act_id',$act_id);
        $this->set('couponCount',$couponCount);
        $this->set('couponAmt',$couponAmt);
}else {
    $this->autoRender = false;
            App::import('Helper', 'csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();
            $line = array();
            
             $line = array('0' => 'Retailer Id', '1' => 'Retailer ShopName', '2' => 'Retailer Mobile', '3' => 'Distributor Id', '4' => 'Distributor Name','5' => 'Distributor City', '6' => 'Distributor State',
                    '7' => 'Product Activation Id', '8' => 'Coupon count', '9' => 'Coupon amount', '10' => 'Deducted amount','11' => 'Coupon Request Time','12' => 'Alloted Time',
                    '13' => 'Alloted By', '14' => 'Status'
                );
                          
                $i = 15;
                $csv->addRow($line);

                foreach ($pans as $data):

                    $temp[0] = $data['ret_id'];
                    $temp[1] = $data['shop_name'];
                    $temp[2] = $data['mobile'];
                    $temp[3] = $data['dist_id'];
                    $temp[4] = $data['dist_company'];
                    $temp[5] = $data['dist_city'];
                    $temp[6] = $data['dist_state'];
                    $temp[7] = $data['agent_id'];
                    $temp[8] = $data['quantity'];
                    $temp[9] = $data['couponamount'];
                    $temp[10] = $data['amount'];
                    $temp[11] = $data['request_date'];
                    $temp[12] = $data['alloted_date'];
                    $temp[13] = $data['alloted_by'];
                    $temp[14] = $data['status'];
                    $csv->addRow($temp);
                    $i ++;
                endforeach;
                echo $csv->render('PAN_SERVICE_' . $panfrm . '_' . $panto . '.csv');
           
           
           //$DistDet = $this->Slaves->query('Select r.parent_id,d.id,d.company,');
           
        }        
//    }
            
    }
    function panCouponProccess(){
        $this->autoRender = False;
        
        $id     = $this->params['form']['id'];
        $status = $this->params['form']['status'];
        $user   = $_SESSION['Auth']['User']['id'];
        $url    = PAN_COUPON_URL;                
         Configure::load('bridge');
        $configs = Configure::read('secrets');
        $secret = $configs['pan_service']['secret'];

        $data = array('user_id' => $user, 'id' => $id,'status'=>$status);
        $token = $this->General->tokenGenerator($data, $secret);        
        $datas = array('user_id' => $user, 'id' => $id,'status'=>$status,'token' => $token);
        $out = $this->General->panService($url, $datas);
    
        $out = json_decode($out['output'], TRUE);        
        $status = $out['status'];        
        $msg = ($out['description']['msg']);
        
        $response = array(
            'status' => $status,
            'description' => $msg
        );
        echo json_encode($response);exit;

    }    
    
    
    function panDetailUpdate(){
         $this->autoRender = False;
        
        $id     = $this->params['form']['id'];
        $comment = $this->params['form']['comment'];
        
        $updDetail = $this->Pan->query('Update coupon_requests set comment = "'.$comment.'" where id = '.$id.'');
        
        if($updDetail){
            
        $response = array(
            'status' => 'success',
            'msg' => 'Updated Successfully'
        );
        
        }else{
            
        $response = array(
            'status' => 'failure',
            'description' => 'Something went wrong..'
        );
        }
        
        echo json_encode($response);exit;
    }
    
    function leadReport(){
     $this->layout = "plain";
     
        $leadDetails = $this->Pan->query("Select * from leads");
     
           $service =  $this->Serviceintegration->getServices();           
           $i = 0;
        foreach ($leadDetails as $row) {
            $leads[$i]['id']             = $row['leads']['id'];
            $leads[$i]['service']        = $service[$row['leads']['service_id']];
            $leads[$i]['retailer_mob']   = $row['leads']['retailer_number'];
            $leads[$i]['lead_mob']       = $row['leads']['lead_number'];
            $leads[$i]['date']           = $row['leads']['updated_at'];
            $leads[$i]['status']         = $row['leads']['status'];
            $leads[$i]['comment']        = $row['leads']['comment'];

            $i++;
        }
    
        
        $this->set('leadDetails',$leads);
        }
    
    
    function leadProccess(){
        $this->autoRender = False;
        
        $id     = $this->params['form']['id'];
        $status = $this->params['form']['status'];
        $user   = $_SESSION['Auth']['User']['id'];
        $url    =  PAN_LEAD_URL;                
         Configure::load('bridge');
        $configs = Configure::read('secrets');
        $secret = $configs['pan_service']['secret'];

        $data = array('user_id' => $user, 'id' => $id,'status'=>$status);
        $token = $this->General->tokenGenerator($data, $secret);        
        $datas = array('user_id' => $user, 'id' => $id,'status'=>$status,'token' => $token);
        $out = $this->General->panService($url, $datas);
    
        $out = json_decode($out['output'], TRUE);        
        $status = $out['status'];        
        $msg = ($out['description']['msg']);
        
        $response = array(
            'status' => $status,
            'description' => $msg
        );
        echo json_encode($response);exit;

    }    
    
    
    function leadDetailUpdate(){
         $this->autoRender = False;
        
        $id     = $this->params['form']['id'];
        $comment = $this->params['form']['comment'];
        
        $updDetail = $this->Pan->query('Update leads set comment = "'.$comment.'" where id = '.$id.'');
        
        if($updDetail){
            
        $response = array(
            'status' => 'success',
            'msg' => 'Updated Successfully'
        );
        
        }else{
            
        $response = array(
            'status' => 'failure',
            'description' => 'Something went wrong..'
        );
        }
        
        echo json_encode($response);exit;
    }    
    
    
}