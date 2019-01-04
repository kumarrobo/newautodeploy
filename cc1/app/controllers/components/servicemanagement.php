<?php
//RohitP(rohit3nov@gmail.com)
class ServicemanagementComponent extends Object{
    var $components = array('General', 'Shop','RequestHandler','Serviceintegration');
    var $Memcache = null;
    var $uses = array('User','Slaves');

    function getRetailerInfoByMobile($mobile = ''){
        $info = array();
        if( ($mobile != '') && is_numeric($mobile) ){
            $Object = ClassRegistry::init('Slaves');
            $info = $Object->query('SELECT r.id,r.user_id,d.user_id,d.active_services,d.id'
                    . ' FROM retailers r'
                . ' JOIN distributors d'
                    . ' ON (r.parent_id=d.id)'
                    . ' WHERE r.mobile='.addslashes($mobile));
        }
        return $info;
    }
    function getRetailerInfoByUserId($user_id = ''){
        $info = array();
        if( ($user_id != '') && is_numeric($user_id) ){
            $Object = ClassRegistry::init('Slaves');
            $info = $Object->query('SELECT r.id,r.user_id,d.user_id,d.id'
                    . ' FROM retailers r'
                . ' JOIN distributors d'
                    . ' ON (r.parent_id=d.id)'
                    . ' WHERE r.user_id='.$user_id);
        }
        return $info;
    }
    function getUserServices($user_id='',$service_id=''){
        $user_services = array();
        if( is_numeric($user_id) ){
            $Object = ClassRegistry::init('Slaves');
            $service_id_cond = '';
            if( is_numeric($service_id) ){
                $service_id_cond = ' AND users_services.service_id = '.$service_id;
            }
            $temp = $Object->query('SELECT users_services.service_id,kit_flag,service_flag,param1,params,service_plans.plan_key'
                    . ' FROM users_services LEFT JOIN service_plans ON (users_services.service_plan_id = service_plans.id)'
                    . ' WHERE user_id='.$user_id
                    .   $service_id_cond);

            if( count($temp) > 0 ){
                foreach ($temp as $index => $service) {
                    $user_services[$service['users_services']['service_id']]['kit_flag'] = $service['users_services']['kit_flag'];
                    $user_services[$service['users_services']['service_id']]['service_flag'] = $service['users_services']['service_flag'];
                    $user_services[$service['users_services']['service_id']]['param1'] = $service['users_services']['param1'];
                    //$user_services[$service['users_services']['service_id']]['param2'] = $service['users_services']['param2'];

                    $service['users_services']['params'] = json_decode($service['users_services']['params'],true);
                    $service['users_services']['params']['plan'] = $service['service_plans']['plan_key'];
                    $service['users_services']['params'] = json_encode($service['users_services']['params']);

                    $user_services[$service['users_services']['service_id']]['params'] = $service['users_services']['params'];

                }
            }

        }
        return $user_services;
    }
        function getDistributorData($service_id = null){
         if ($service_id && is_numeric($service_id)) {
            $Object = ClassRegistry::init('Slaves');
            $dist = $Object->query('Select id,name,mobile from distributors where active_flag = "' . 1 . '" ');

            /** IMP DATA ADDED : START**/
            $dist_ids = array_map(function($element){
                return $element['distributors']['id'];
            },$dist);

               $imp_data_dist = $this->Shop->getUserLabelData($dist_ids,2,3);

                foreach($dist as $key=>$d){
                    $dist[$key]['distributors']['name']= $imp_data_dist[$d['distributors']['id']]['imp']['shop_est_name'];
                }
                /** IMP DATA ADDED : END**/

            return $dist;
         }
            }
    function getActiveRetailersByService($service_id = null, $activated_from = null, $activated_to = null,$filter_distributors = null,$status_type = 0) {
        ini_set("memory_limit","512M");
        $active_retailers = array();
        $activated_date = "AND us.created_on >= '$activated_from 00:00:00' and us.created_on <= '$activated_to 23:59:59'";
        if(isset($filter_distributors) && !empty($filter_distributors)){
               $distributor_name = 'AND  dist.id IN  (' .$filter_distributors. ') ';
        }
        if($status_type == '0') {
                $status_type = "AND (us.kit_flag = 1 AND us.service_flag = 1)"; }
         elseif($status_type == '1') {
             $status_type = "AND (us.kit_flag = 0 OR us.service_flag in (0,2))";

        }

        if ($service_id && is_numeric($service_id)) {
            $Object = ClassRegistry::init('Slaves');
            if($status_type != '2'){

            $temp = $Object->query("SELECT us.*,dist.id,ret.id as retailer_id,ret.mobile as retailer_mobile,ret.name as retailer_name,ret.shopname as retailer_shopname,
                                    dist.name as distributor_name
                                FROM users_services us
                                LEFT JOIN retailers ret
                                ON (us.user_id = ret.user_id)
                                LEFT JOIN distributors dist
                                ON (dist.id = ret.parent_id)
                                WHERE us.service_id=".$service_id."
                                $activated_date $distributor_name $status_type");
            }

            else {

                $temp = $Object->query("SELECT distinct(us.user_id),us.*,dist.id,ret.id as retailer_id,rel.*,ret.mobile as retailer_mobile,ret.name as retailer_name,ret.shopname as retailer_shopname, dist.name as distributor_name
                                        FROM users_services us
                                        LEFT JOIN retailers ret ON (us.user_id = ret.user_id)
                                        LEFT JOIN distributors dist ON (dist.id = ret.parent_id)
                                        LEFT JOIN retailer_earning_logs rel on (rel.ret_user_id = us.user_id)
                                        WHERE us.service_id=".$service_id." and rel.service_id=".$service_id."
                                            $activated_date $distributor_name
                                        AND rel.date >= DATE_SUB(CURDATE(), INTERVAL 15 DAY) GROUP by us.user_id
                                        ");

            }
            if (count($temp) > 0) {
                /** IMP DATA ADDED : START**/
                $ret_mobiles = array_map(function($element){
                    return $element['ret']['retailer_mobile'];
                },$temp);

                $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);
                /** IMP DATA ADDED : END**/

                /** IMP DATA ADDED : START**/
                $dist_ids = array_map(function($element){
                    return $element['dist']['id'];
                },$temp);

                $imp_data_dist = $this->Shop->getUserLabelData($dist_ids,2,3);
                /** IMP DATA ADDED : END**/

                foreach ($temp as $index => $service) {
                    $active_retailers[$index]['retailer_user_id'] = $service['us']['user_id'];
                    $active_retailers[$index]['retailer_mobile'] = $service['ret']['retailer_mobile'];
                    $active_retailers[$index]['retailer_mobile'] = $service['ret']['retailer_mobile'];
                    $active_retailers[$index]['distributor_id'] =  $service['dist']['id'];
                    $active_retailers[$index]['distributor_name'] = $imp_data_dist[$service['dist']['id']]['imp']['shop_est_name'];

                    // $active_retailers[$index]['retailer_name'] = $service['ret']['retailer_name'];
                    // $active_retailers[$index]['retailer_shopname'] = $service['ret']['retailer_shopname'];
                    $active_retailers[$index]['retailer_id'] = $service['ret']['retailer_id'];
                    $active_retailers[$index]['retailer_name'] = $imp_data[$service['ret']['retailer_mobile']]['imp']['name'];
                    $active_retailers[$index]['retailer_shopname'] = $imp_data[$service['ret']['retailer_mobile']]['imp']['shop_est_name'];
                    $active_retailers[$index]['param1'] = json_decode($service['us']['param1'], true);

                    $active_retailers[$index]['params'] = json_decode($service['us']['params'], true);
                    $active_retailers[$index]['excelparams'] = $service['us']['params'];
                    $active_retailers[$index]['service_created_on'] = $service['us']['created_on'];
                    $active_retailers[$index]['kit_flag']           = $service['us']['kit_flag'];
                    $active_retailers[$index]['service_flag']       = $service['us']['service_flag'];
                }
            }
        }
        return $active_retailers;
    }
    function updateUserService($user_id= '',$service_id = '',$data,$dataSource,$service_plan_id){

        if( is_numeric($user_id) && is_numeric($service_id) ){

            // $param1 = isset($data['ret_margin'])?',param1='.$data['ret_margin'].'':'';
            $params = json_decode($data['params'],true);
            $device_id = $params['device_id'];

            $param1 = '';
            $service_fields = $this->getServiceFields();
            $service_fields = json_decode($service_fields,true);
            foreach ($params as $field_key => $value) {
                $validation_rules = explode('|',$service_fields[$service_id][$field_key]['validation']);
                if( count($validation_rules) > 0 && in_array('unique',$validation_rules) && !empty($value) ){
                    $param1 = ',param1 = "'.$value.'"';
                    break;
                }
            }

            // if($service_id == 12) {
                // $param = Configure::read('retailer_commission_12_default');
                // $ret_comm_config = Configure::read('retailer_commission_12');

                // $margin = isset($data['ret_margin'])?$data['ret_margin']:$param;
                // $ret_comm_config['ret_margin']['margin'] = $margin;
                // $params['ret_margin'] = $ret_comm_config['ret_margin'];
                // $params['min'] = $ret_comm_config['min'];
                // $params['max'] = $ret_comm_config['max'];
            // }

            $service_plan_id_cond = '';
            if( $service_plan_id ){
                $service_plan_id_cond = 'service_plan_id = "'.$service_plan_id.'",';
            }
            $params = json_encode($params);
            $temp = $dataSource->query('UPDATE users_services'
                    . ' SET kit_flag='.$data['kit_flag'].','
                    . ' service_flag='.$data['service_flag'].','
                    . ' '.$service_plan_id_cond.' '
                    . ' device_id="'.$device_id.'",'
                    . ' params=\''.addslashes($params).'\''
                    . ' '.$param1.' '
                    . ' WHERE user_id='.$user_id.''
                    . ' AND service_id='.$service_id);
            if($temp){
                return TRUE;
            }
        }
        return FALSE;
    }
    function updateServiceFlag($user_id= '',$service_id = '',$data,$dataSource){
        if( is_numeric($user_id) && is_numeric($service_id) ){

            $temp = $dataSource->query('UPDATE users_services'
                    . ' SET service_flag='.$data['service_flag'].''
                    . ' WHERE user_id='.$user_id.''
                    . ' AND service_id='.$service_id);
            if($temp){
                return TRUE;
            }
        }
        return FALSE;
    }
    function deleteUserService($user_id= '',$service_id = '',$dataSource){
        if( is_numeric($user_id) && is_numeric($service_id) ){

            $temp = $dataSource->query('DELETE FROM users_services'
                    . ' WHERE user_id='.$user_id.''
                    . ' AND service_id='.$service_id);
            if($temp){
                return TRUE;
            }
        }
        return FALSE;
    }
    function addUserService($user_id= '',$service_id = '',$data,$dataSource,$service_plan_id){

        // Configure::load('product_config');
        if( is_numeric($user_id) && is_numeric($service_id) ){

            $params = json_decode($data['params'],true);
            $device_id = isset($params['device_id'])?$params['device_id']:'';

            $param1 = '';
            $service_fields = $this->getServiceFields();
            $service_fields = json_decode($service_fields,true);
            foreach ($params as $field_key => $value) {
                $validation_rules = explode('|',$service_fields[$service_id][$field_key]['validation']);
                if( count($validation_rules) > 0 && in_array('unique',$validation_rules) && !empty($value) ){
                    $param1 = $value;
                    break;
                }
            }

            // if($service_id == 12) {
                // $param = Configure::read('retailer_commission_12_default');
                // $ret_comm_config = Configure::read('retailer_commission_12');

                // $param1 = isset($data['ret_margin'])?$data['ret_margin']:$param;
                // $ret_comm_config['ret_margin']['margin'] = $param1;
                // $params['ret_margin'] = $ret_comm_config['ret_margin'];
                // $params['min'] = $ret_comm_config['min'];
                // $params['max'] = $ret_comm_config['max'];
            // } else {

            // }
            $data['params'] = json_encode($params);
            $temp = $dataSource->query('INSERT INTO users_services '
                    . ' (user_id,service_id,device_id,param1,service_plan_id,kit_flag,service_flag,rental_activation_date,created_by,created_on,params)'
                    . ' VALUES(' . $user_id . ',' . $service_id . ',"'.$device_id.'","' . $param1 . '","' . $service_plan_id . '",' . $data['kit_flag'] . ',' . $data['service_flag'] . ',"'.date("Y-m-d").'",' . $_SESSION['Auth']['User']['id'] . ',"' . date("Y-m-d h:i:s") . '",\'' . addslashes($data['params']) . '\')');
            if($temp) {
                return TRUE;
            }
        }
        return FALSE;
    }

    function addKitDeliveryLog($data,$dataSource = null){
        $dataSource = (!$dataSource) ? ClassRegistry::init('User') : $dataSource;

        $temp = $dataSource->query('INSERT INTO kit_delivery_log '
                . ' ('.implode(',',array_keys($data)).')'
                . ' VALUES("'.implode('","',$data).'")');
        if($temp) {
            return TRUE;
        }
        return false;
    }
    function addServiceRequestLog($data,$dataSource = null){
        $dataSource = (!$dataSource) ? ClassRegistry::init('User') : $dataSource;
        $temp = $dataSource->query('INSERT INTO service_request_log '
                    . ' ('.implode(',',array_keys($data)).')'
                    . ' VALUES("'.implode('","',$data).'")');
        if($temp) {
            return TRUE;
        }
        return false;
    }
    function updateServiceRequestLog($user_id,$service_id,$data,$dataSource = null){
        $dataSource = (!$dataSource) ? ClassRegistry::init('User') : $dataSource;
                $temp = $dataSource->query('UPDATE service_request_log'
                    . ' SET service_request_date = "'.$data['service_request_date'].'",'
                    . ' service_request_timestamp = "'.$data['service_request_timestamp'].'",'
                    . ' source = "'.$data['source'].'"'
                    . ' WHERE ret_user_id='.$user_id.''
                    . ' AND service_id='.$service_id);
        if($temp) {
            return TRUE;
        }
        return false;
    }
    function checkServiceRequestLog($user_id,$service_id){
        $Object = ClassRegistry::init('Slaves');
        $temp = $Object->query('SELECT * '
                . 'FROM service_request_log '
                . 'WHERE ret_user_id = '.$user_id.' AND service_id = '.$service_id);

        return $temp;
    }
    function validateField($user_id,$service_id,$field_key,$field_value){
        // $service_fields = Configure::read('service_fields');
        $service_fields = $this->getServiceFields();
        $service_fields = json_decode($service_fields,true);
        $validation_rules = explode('|',$service_fields[$service_id][$field_key]['validation']);

        foreach( $validation_rules as $rule ) {
            $temp = explode(':',$rule);
            $rule = $temp[0];

            $rule_value = null;
            if(isset($temp[1])){
                $rule_value = $temp[1];
            }
            $fn = 'validate'.ucfirst($rule);
            if(method_exists($this, $fn)){
                $fn_validation = $this->$fn($user_id,$service_id,$field_key,$field_value,$service_fields[$service_id][$field_key]['label'],$rule_value);
                if( $fn_validation['status'] == 'failure' ){
                    return $fn_validation;
                }
            }
        }
        return array('status'=>'success');
    }
    function validateUnique($user_id,$service_id,$field_key,$field_value,$label){
        if( !empty($field_value) ){
            $Object = ClassRegistry::init('Slaves');
    //        $temp = $Object->query('SELECT params'
    //                . ' FROM users_services'
    //                . ' WHERE service_id= '.$service_id.''
    //                . ' AND user_id !='.$user_id);
            $temp = $Object->query('SELECT params'
                    . ' FROM users_services'
                    . ' WHERE service_id= '.$service_id.''
                    . ' AND user_id !='.$user_id.' AND params LIKE \'%"'.$field_key.'":"'.$field_value.'"%\'');

    //        $all_params = array_map('current',array_map('current',$temp));
    //
    //        $all_field_values = array_filter(array_map(function($element) use ($field_key){
    //            $element = json_decode($element,true);
    //            return $element[$field_key];
    //        },$all_params));

    //        if( in_array($field_value,$all_field_values,TRUE) ){
            if( ($temp) && count($temp) > 0 ){
                return array(
                    'status'=>'failure',
                    'description' => 'This '.$label.' already exists for other account.'
                );
            }
        }
        return array('status'=>'success');
    }
    function validateRequire($user_id,$service_id,$field_key,$field_value,$label){
        if( $field_value == '' ){
            return array(
                'status'=>'failure',
                'description' => $label.' missing'
            );
        }
        return array('status'=>'success');
    }
    function validateMin($user_id,$service_id,$field_key,$field_value,$label,$min_val){
        if( $field_value < $min_val){
            return array(
                'status'=>'failure',
                'description' => 'Margin value should be greater than '.$min_val
            );
        }
        return array('status'=>'success');
    }
    function validateMax($user_id,$service_id,$field_key,$field_value,$label,$max_val){
        if( $field_value > $max_val){
            return array(
                'status'=>'failure',
                'description' => 'Margin value should be less than '.$max_val
            );
        }
        return array('status'=>'success');
    }
    function validateNumeric($user_id,$service_id,$field_key,$field_value,$label){
        if( !is_numeric($field_value)){
            return array(
                'status'=>'failure',
                'description' => 'Value of '.$label.' should be numeric'
            );
        }
        return array('status'=>'success');
    }
    function addServiceLog($user_id,$service_id,$data,$action,$dataSource){
        $dataSource = (!$dataSource) ? ClassRegistry::init('User') : $dataSource;

        $temp = $dataSource->query('INSERT INTO users_services_log'
                    . ' (user_id,service_id,kit_flag,service_flag,params,action,ip,updated_by,updated_on)'
                    . ' VALUES('.$user_id.','
                    . ''.$service_id.','
                    . ''.$data['kit_flag'].','
                    . ''.$data['service_flag'].','
                    . '\''.addslashes($data['params']).'\','
                    . '\''.$action.'\','
                    . '\''.$this->RequestHandler->getClientIP().'\','
                    . ''.$_SESSION['Auth']['User']['id'].','
                    . '\''.date('Y-m-d H:i:s').'\')'
                );


        if($temp){
            return TRUE;
        }
        return FALSE;
    }

    function getLastLog($user_id,$service_id){
        $Object = ClassRegistry::init('Slaves');
        $last_log = $Object->query('SELECT * '
                . 'FROM users_services_log '
                . 'WHERE user_id = '.$user_id.' AND service_id = '.$service_id.' '
                . 'ORDER BY updated_on DESC '
                . 'LIMIT 1 ');
        return $last_log;
    }

    function addServiceReactLog($user_id,$service_id,$data,$action,$dataSource){
        $temp = $dataSource->query('INSERT INTO users_services_log'
                    . ' (user_id,service_id,service_flag,action,ip,updated_by,updated_on)'
                    . ' VALUES('.$user_id.','
                    . ''.$service_id.','
                    . ''.$data['service_flag'].','
                    . '\''.$action.'\','
                    . '\''.$this->RequestHandler->getClientIP().'\','
                    . ''.$_SESSION['Auth']['User']['id'].','
                    . '\''.date('Y-m-d H:i:s').'\')'
                );


        if($temp){
            return TRUE;
        }
        return FALSE;
    }
    function getDistributors($dist_mobile = null){
        $userObj = ClassRegistry::init('Slaves');
        $dist_mobile_cond = '';
        if ( !empty($dist_mobile) ) {
            $dist_mobile_cond = " AND mobile IN ($dist_mobile) ";
        }
        $sql = "SELECT  id,user_id,name,mobile FROM distributors where active_flag=1".$dist_mobile_cond;
        $result = $userObj->query($sql);
        $distributors = array();
        if( count($result) > 0 ){

            /** IMP DATA ADDED : START**/
            $dist_ids = array_map(function($element){
                return $element['distributors']['id'];
            },$result);

            $imp_data_dist = $this->Shop->getUserLabelData($dist_ids,2,3);
            /** IMP DATA ADDED : END**/

            foreach($result as $row){
                $distributors[$row['distributors']['id']] = array(
                    'name'=>$imp_data_dist[$row['distributors']['id']]['imp']['shop_est_name'],
                    'user_id'=>$row['distributors']['user_id'],
                    'mobile'=>$row['distributors']['mobile']
                );
            }
        }
        return $distributors;
    }

    function fetchDistributorPendingkits($dist_id = null,$service_id = null,$mode = 0) {
        $Object = ClassRegistry::init('Slaves');
        $dist_id_cond = '';
        if ( !empty($dist_id) ) {
            $dist_id_cond = " AND distributor_id IN ($dist_id) ";
        }
        $service_id_cond = '';
        if ( !empty($service_id) ) {
            $service_id_cond = " AND distributors_kits.service_id IN ($service_id) ";
        }
        $select_cond = ' distributor_id,kits ';
        $join_cond = '';
        if($mode == 1){
            $select_cond = ' distributor_id,kits,service_plans.plan_name ';
            $join_cond = ' LEFT JOIN service_plans ON(distributors_kits.service_plans_id = service_plans.id) ';
        }


        $temp = $Object->query("SELECT $select_cond FROM distributors_kits $join_cond WHERE 1=1 ".$dist_id_cond.$service_id_cond);
        if($mode == 1){
            return $temp;
        }

        $dist_pending_kits = array();
        if ( count($temp) > 0 ) {
            foreach ($temp as $kit) {
                $dist_pending_kits[$kit['distributors_kits']['distributor_id']] += $kit['distributors_kits']['kits'];
            }
        }
        return $dist_pending_kits;
    }



    function fetchDistributorTotalPurchasedKit($dist_id = null,$service_id = null,$mode = 0) {

        $dist_total_purchased_kits = array();
        if($dist_id && $service_id){
            $Object = ClassRegistry::init('Slaves');

            $select_cond = ' distributor_id,SUM(kits) as totalkits ';
            $group_by_cond = ' GROUP BY distributor_id ';
            $join_cond = '';
            if($mode == 1){
                $select_cond = '    distributor_id,service_plans.plan_name,service_plans.setup_amt as setup_amount,
                                    distributors_kits_log.amount as actual_amount,kits,
                                    users.name as created_by,amount,created_at as timestamp';
                $group_by_cond = '';
                $join_cond = ' LEFT JOIN users ON(distributors_kits_log.created_by = users.id)
                                LEFT JOIN service_plans ON(distributors_kits_log.service_plans_id = service_plans.id)
                                ';
            }

            $temp = $Object->query("SELECT  $select_cond FROM distributors_kits_log
                                                    $join_cond
                                                    WHERE distributor_id IN ($dist_id)
                                                    AND distributors_kits_log.service_id IN ($service_id)
                                                    AND action = 'debit' $group_by_cond order by created_at desc");

            if($mode == 1){
                return $temp;
            }
            if ( count($temp) > 0 ) {
                foreach ($temp as $totkits) {
                    $dist_total_purchased_kits[$totkits['distributors_kits_log']['distributor_id']] = $totkits[0]['totalkits'];
                }
            }
        }

        return $dist_total_purchased_kits;
    }

    function fetchDistributorRefundedKits($dist_id = null,$service_id = null,$mode = 0) {

        $dist_total_refunded_kits = array();
        if($dist_id && $service_id){
            $Object = ClassRegistry::init('Slaves');

            $select_cond = ' distributor_id,SUM(kits) as totalkits ';
            $group_by_cond = ' GROUP BY distributor_id ';
            $join_cond = '';
            if($mode == 1){
                $select_cond = '    distributor_id,service_plans.plan_name,service_plans.setup_amt as setup_amount,
                                    distributors_kits_log.amount as actual_amount,kits,
                                    users.name as created_by,amount,created_at as timestamp';
                $group_by_cond = '';
                $join_cond = ' LEFT JOIN users ON(distributors_kits_log.created_by = users.id)
                                LEFT JOIN service_plans ON(distributors_kits_log.service_plans_id = service_plans.id)
                                ';
            }

            $temp = $Object->query("SELECT  $select_cond FROM distributors_kits_log
                                                    $join_cond
                                                    WHERE distributor_id IN ($dist_id)
                                                    AND distributors_kits_log.service_id IN ($service_id)
                                                    AND action = 'refund' $group_by_cond order by created_at desc");

            if($mode == 1){
                return $temp;
            }
            if ( count($temp) > 0 ) {
                foreach ($temp as $totkits) {
                    $dist_total_refunded_kits[$totkits['distributors_kits_log']['distributor_id']] = $totkits[0]['totalkits'];
                }
            }
        }

        return $dist_total_refunded_kits;
    }

    function assignedKitsToRetailer($dist_id = null,$service_id = null,$mode = 0) {

        $assigned_kits_to_retailer = array();
        if($dist_id && $service_id){
            $Object = ClassRegistry::init('Slaves');

            $select_cond = ' ret.parent_id,count(us.id) as kits ';
            $group_by_cond = ' GROUP BY ret.parent_id ';
            if($mode == 1){
                $select_cond = ' case us.service_flag
                when "1" then "Active"
                when "0" then "Inactive"
                when "2" then "Deactivated due to Rental"
                END as service_status,us.created_on as activated_on,us.created_by as activated_by,user.name as activated_by_name,ret.id as ret_id,us.params as params';
                $group_by_cond = '';
            }

            $temp = $Object->query("SELECT $select_cond FROM users_services us
                                                    LEFT JOIN retailers ret ON(ret.user_id = us.user_id)
                                                    LEFT JOIN users user ON(us.created_by = user.id)
                                                    WHERE us.service_id IN ($service_id)
                                                    AND ret.parent_id IN($dist_id)
                                                    AND us.kit_flag = 1
                                                    AND params LIKE '%\"payment_mode\":\"4\"%'
                                                    $group_by_cond ");

            if($mode == 1){
                return $temp;
            }

            if ( count($temp) > 0 ) {
                foreach ($temp as $totkits) {
                    $assigned_kits_to_retailer[$totkits['ret']['parent_id']] = $totkits[0]['kits'];
                }
            }
        }

        return $assigned_kits_to_retailer;
    }


    function fetchDirectBuyKitsByRetailers($dist_id = null,$service_id = null,$mode = 0 ) {

        $total_assigned_kits_to_retailer = array();
        if($dist_id && $service_id){
            $Object = ClassRegistry::init('Slaves');

            $select_cond = ' ret.parent_id,count(us.id) as kits ';
            $group_by_cond = ' GROUP BY ret.parent_id ';
            if($mode == 1){
                $select_cond = ' case us.service_flag
                when "1" then "Active"
                when "0" then "Inactive"
                when "2" then "Deactivated due to Rental"
                END as service_status,us.created_on as activated_on,us.created_by as activated_by,user.name as activated_by_name,ret.id as ret_id,us.params as params';
                $group_by_cond = '';
            }

            $temp = $Object->query("SELECT $select_cond FROM users_services us
                                                    LEFT JOIN retailers ret ON(ret.user_id = us.user_id)
                                                    LEFT JOIN users user ON(us.created_by = user.id)
                                                    WHERE us.service_id IN ($service_id)
                                                    AND ret.parent_id IN($dist_id)
                                                    AND us.kit_flag = 1
                                                    AND ( (params LIKE '%\"payment_mode\":\"2\"%') OR (params LIKE '%\"payment_mode\":\"3\"%') )
                                                    $group_by_cond ");

            if($mode == 1){
                return $temp;
            }

            if ( count($temp) > 0 ) {
                foreach ($temp as $totkits) {
                    $total_assigned_kits_to_retailer[$totkits['ret']['parent_id']] = $totkits[0]['kits'];
                }
            }
        }

        return $total_assigned_kits_to_retailer;
    }
    function reactivate($user_id,$service_id,$dataSource){
        App::import('Controller', 'Crons');
        $crons = new CronsController;
        $crons->constructClasses();
        $response = $crons->debitRental($user_id,$service_id,'reactivate',$dataSource);

        $response = json_decode($response,true);
        if(($response) && $response['status'] == 'success'){
            return json_encode(array(
            'status'=>'success',
            'msg'=>'Service reactivated successfully',
            'notification_data'=>$response['data']
            ));
        } else {
            return json_encode(array(
                'status'=>$response['status'],
                'errCode'=>$response['errCode'],
                'description'=>$response['description']
            ));
        }
    }

    function updateUserServiceFlags($user_id= '',$service_id = '',$data,$dataSource)
    {
        if( is_numeric($user_id) && is_numeric($service_id) ){

            $temp = $dataSource->query('UPDATE users_services'
                    . ' SET service_flag = '.$data['service_flag'].','
                    . ' kit_flag = '.$data['kit_flag'].''
                    // . ' params = "'.$data['params'].'",'
                    // . ' service_plan_id = '.$data['service_plan_id'].''
                    . ' WHERE user_id='.$user_id.''
                    . ' AND service_id='.$service_id);
            if($temp){
                return TRUE;
            }
        }
        return FALSE;
    }

    function getServiceFields()
    {
        $Object = ClassRegistry::init('Slaves');
        // $service_fields = $this->Shop->getMemcache("service_fields");
        $service_fields = '';
        if(empty($service_fields))
        {
            $fields = $Object->query('SELECT * FROM service_fields');

            if(!empty($fields))
            {
                foreach($fields as $field)
                {
                    $field['service_fields']['default_values'] = json_decode($field['service_fields']['default_values'],true);
                    $service_fields[$field['service_fields']['service_id']][$field['service_fields']['key']] = $field['service_fields'];
                    unset($service_fields[$field['service_fields']['service_id']][$field['service_fields']['key']]['id']);
                    unset($service_fields[$field['service_fields']['service_id']][$field['service_fields']['key']]['service_id']);
                }

                $service_fields = json_encode($service_fields);
                $this->Shop->setMemcache("service_fields", $service_fields , 24*60*60);

            }
        }

        return $service_fields;
    }
    function distCommission($amt,$dist_id,$dist_user_id,$serviceId,$description,$dataSource=null,$discount=null,$settle_flag = '1'){

        $tax = $this->Shop->calculateTDS($amt);

        $closing_bal = $this->Shop->shopBalanceUpdate($amt-$tax,'add',$dist_user_id,null,$dataSource,1,0);
        if($closing_bal === false){
            return array('status'=>'failure','errCode'=>'105','description'=>$this->errorDescription('105'));
        }
        $type_flag = 0;


        $res = $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR,$amt,$dist_id,null,$serviceId,$discount,$type_flag,$description,$closing_bal-$amt+$tax,$closing_bal+$tax,null,null,$dataSource);
        if($res){
            $description_tds = "TDS Charges - " .$res;
            $ret = $this->Shop->shopTransactionUpdate(TDS, $tax,$dist_user_id, $res, $serviceId, null, $type_flag, $description_tds, $closing_bal+$tax , $closing_bal, null,null, $dataSource);

            if($ret){
                return array(
                    'status' => 'success',
                    'shop_txn_id' => $res
                );
            } else {
                return array(
                    'status'=>'failure',
                    'description'=>'Something went wrong. Please try again'
                );
            }
        } else {
            return array(
                'status'=>'failure',
                'description'=>'Something went wrong. Please try again'
            );
        }
    }

    function getServiceActivationTemplates(){
        $Object = ClassRegistry::init('Slaves');
        // $service_fields = $this->Shop->getMemcache("service_activation_templates");
        $service_activation_templates = '';
        if(empty($service_activation_templates))
        {
            $temp = $Object->query('SELECT * FROM service_activation_templates');

            if(!empty($temp))
            {
                foreach($temp as $template)
                {
                    $service_activation_templates[$template['service_activation_templates']['service_id']] = $template['service_activation_templates']['sms'];
                }

                $service_activation_templates = json_encode($service_activation_templates);
                // $this->Shop->setMemcache("service_activation_templates", $service_activation_templates , 24*60*60);

            }
        }

        return $service_activation_templates;
    }
    function pullbackKit($service_id,$user_id){
        $Object = ClassRegistry::init('User');
        $dataSource = $Object->getDataSource();
        $dataSource->begin();

        $kit_flag = 1;

        $temp = $dataSource->query('UPDATE users_services'
                . ' SET kit_flag='.$kit_flag.''
                . ' WHERE user_id='.$user_id.''
                . ' AND service_id='.$service_id);
        if($temp){
            $data['kit_flag'] = $kit_flag;
            $data['service_flag'] = 'null';
            $data['params'] = array();

            $log_response = $this->addServiceLog($user_id,$service_id,$data,'kit_pullback',$dataSource);
            if($log_response){
                $dataSource->commit();
                return array(
                    'status' => 'success',
                    'description' => 'Kit pulled back successfully'
                );
            }
        }
        $dataSource->rollback();
        return array(
            'status'=>'failure',
            'description'=>'Couldn\'t pull back kit. Something went wrong. Please try again'
        );
    }
    function requestService($service_id,$user_id){

        $services = $this->Serviceintegration->getServiceDetails();
        $services = json_decode($services,true);
        if( $services[$service_id]['activation_type'] == 1 ){ // if activation_type is manual

            $Object = ClassRegistry::init('User');
            $dataSource = $Object->getDataSource();
            $dataSource->begin();

            $kit_flag = 1;
            $service_flag = 4;

            $user_service = $this->getUserServices($user_id,$service_id);

            if( count($user_service) > 0 ){ // update users_services with service_flag = 4 if service_flag = 3/5

                $service_flag = 4;
                $temp = $dataSource->query('UPDATE users_services'
                    . ' SET service_flag='.$service_flag.''
                    . ' WHERE user_id='.$user_id.''
                    . ' AND service_id='.$service_id.''
                    . ' AND service_flag IN(3,5)'
                    );

            } else {

                $data['kit_flag'] = 1;
                $data['service_flag'] = 4;
                $temp = $this->addUserService($user_id,$service_id,$data,$dataSource,0);

            }
            if($temp){

                $check_service_request = $this->checkServiceRequestLog($user_id,$service_id);

                if( count($check_service_request) > 0  ){
                    $service_request_data = array(
                        'service_request_date' => date('Y-m-d'),
                        'service_request_timestamp' => date('Y-m-d H:i:s'),
                        'source' => 'service_activation_panel',
                    );
                    $service_request_res = $this->updateServiceRequestLog($user_id,$service_id,$service_request_data,$dataSource);

                } else {
                    $service_request_data = array(
                        'service_request_date' => date('Y-m-d'),
                        'service_request_timestamp' => date('Y-m-d H:i:s'),
                        'ret_user_id' => $user_id,
                        'source' => 'service_activation_panel',
                        'service_id' => $service_id
                    );
                    $service_request_res = $this->addServiceRequestLog($service_request_data,$dataSource);

                }
                if($service_request_res){
                    $dataSource->commit();
                    return array(
                        'status' => 'success',
                        'description' => 'Service request created successfully'
                    );
                }
            }
            $dataSource->rollback();
            return array(
                'status'=>'failure',
                'description'=>'Couldn\'t create service request. Something went wrong. Please try again'
            );
        }
        return array('status'=>'failure', 'description'=>'This service is not eligible for this action');
    }
}
