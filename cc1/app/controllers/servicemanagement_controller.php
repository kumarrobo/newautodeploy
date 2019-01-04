<?php
// //RohitP(rohit3nov@gmail.com)
class ServicemanagementController extends AppController {
    var $name = 'Servicemanagement';
    var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv');
    var $uses = array('User','Slaves');
    var $components = array('RequestHandler','Shop','Servicemanagement','Email','Platform','Bridge','General','Serviceintegration','Documentmanagement');

    function beforeFilter()
    {
        parent::beforeFilter();
    }

    function index(){
        $this->layout = 'plain';
    }
    function getServices(){

        $mobile = '';
//        $role = RETAILER;  // retailer
        if( isset($this->params['url']['mobile']) ){
            $mobile = trim($this->params['url']['mobile']);
        }
//        if( isset($this->params['url']['role']) ){
//            $role = trim($this->params['url']['role']);
//        }
        $service_fields = array();
        if( !empty($mobile) && is_numeric($mobile) ){
                    $retailer_info = $this->Servicemanagement->getRetailerInfoByMobile($mobile);

                    if( count($retailer_info) > 0 ){
                        //$distributor_active_services = explode(',',$retailer_info[0]['d']['active_services']);
                        //$service_fields = Configure::read('service_fields');
                        $service_fields = $this->Servicemanagement->getServiceFields();
                        $service_fields = json_decode($service_fields,true);

                        $plans = $this->Serviceintegration->getServicePlans();
                        $plans = json_decode($plans,true);

                        $product_vendors = $this->Serviceintegration->getProductVendors();
                        $product_vendors = json_decode($product_vendors,true);



                        foreach($service_fields as $service_id => $fields){
                            if( array_key_exists($service_id,$plans) ){
                                $service_fields[$service_id]['plan']['label'] = 'Plan';
                                $service_fields[$service_id]['plan']['type'] = 'dropdown';
                                $service_fields[$service_id]['plan']['validation'] = 'require';
                                $service_fields[$service_id]['plan']['default_values'] = array();
                                foreach ($plans[$service_id] as $plan_key => $plan) {
                                    if($plan['is_visible']){
                                        $service_fields[$service_id]['plan']['default_values'][$plan_key] = $plan['plan_name'];
                                    }
                                }
                            }
                            if( array_key_exists($service_id,$product_vendors) ){
                                $service_fields[$service_id]['vendor']['label'] = 'Vendor';
                                $service_fields[$service_id]['vendor']['type'] = 'dropdown';
                                $service_fields[$service_id]['vendor']['validation'] = 'require';
                                $service_fields[$service_id]['vendor']['default_values'] = array();
                                foreach ($product_vendors[$service_id] as $vendor_id => $vendor) {
                                    // if($plan['is_visible']){
                                        $service_fields[$service_id]['vendor']['default_values'][$vendor_id] = $vendor['name'];
                                    // }
                                }
                            }
                        }


                        $distributor_active_services = array_keys($service_fields);


                        if( count($distributor_active_services) == 0 ){
                            $this->Session->setFlash("<b>Error</b> : No service activated for distributor.");
                            $this->redirect(array(
                                'controller' => 'servicemanagement',
                                'action' => 'index'
                            ));
                        }
                        $user_id = $retailer_info[0]['r']['user_id'];
                        $user_services = $this->Servicemanagement->getUserServices($user_id);


                        $services = $this->Serviceintegration->getServiceDetails();
                        $services = array_filter(json_decode($services,true),function($service){
                            return in_array($service['registration_type'],array(2,3,4));
                        });



                        // if( count($user_services) > 0 ){
                            foreach ( $service_fields as $service_id => $fields ) {
                                if( in_array($service_id, $distributor_active_services) && (array_key_exists($service_id,$services)) ){
                                    if( array_key_exists($service_id,$user_services) ){
                                        // $service_fields[$service_id]['plan']['extra']['new_user'] = 0;
                                        $service_fields[$service_id]['kit_flag']['value'] = $user_services[$service_id]['kit_flag'];
                                        $service_fields[$service_id]['service_flag']['value'] = $user_services[$service_id]['service_flag'];

                                        foreach (json_decode($user_services[$service_id]['params']) as $field_key => $field_value) {
                                            if(array_key_exists($field_key, $service_fields[$service_id])){
                                                $service_fields[$service_id][$field_key]['value'] = $field_value;
                                            } else if( ( $service_id == 8 ) && ($field_key == 'type') ){
                                                // $service_fields[$service_id]['plan']['extra'][$field_key] = $field_value;
                                            }
                                        }
                                        if($service_id == 12)
                                        {
                                            // $service_fields[$service_id]['ret_margin']['value'] = $user_services[$service_id]['param1'];
                                        }

                                    }
                                    else {
                                        $service_fields[$service_id]['kit_flag']['value'] = 'NA';
                                        $service_fields[$service_id]['service_flag']['value'] = 'NA';
                                    }
                                    // else if($service_id == 8){
                                        // $service_fields[$service_id]['plan']['extra']['new_user'] = 1;
                                    // }
                                    if( in_array($services[$service_id]['registration_type'],array(3,4)) ){
                                        unset($service_fields[$service_id]['kit_flag'],$service_fields[$service_id]['payment_mode']);
                                    }
                                } else {
                                    unset($service_fields[$service_id]);
                                }
                            }
                        // }


                    } else {
                        $this->Session->setFlash("<b>Error</b> : Mobile does not exist.");
                        $this->redirect(array(
                            'controller' => 'servicemanagement',
                            'action' => 'index'
                        ));
                    }

        } else {
            $this->Session->setFlash("<b>Error</b> : Mobile number missing or Invalid.");
            $this->redirect(array(
                'controller' => 'servicemanagement',
                'action' => 'index'
            ));
        }


        $this->set('mobile',$mobile);
        $this->set('user_id',$user_id);
        $this->set('retailer_id',$retailer_info[0]['r']['id']);
        $this->set('dist_id',$retailer_info[0]['d']['user_id']);
        $this->set('distributor_id',$retailer_info[0]['d']['id']);
        $this->set('service_fields',$service_fields);
        $this->set('services',$services);
        $this->set('kit_statuses',Configure::read('kit_status'));
        $this->set('service_statuses',Configure::read('service_status'));
        $this->layout = 'plain';
        $this->render('/servicemanagement/index');
    }
    function pullbackKit(){
        if($this->params['form']){
            $this->autoRender = false;
            $service_id = $this->params['form']['service_id'];
            $user_id = $this->params['form']['user_id'];
            $res = $this->Servicemanagement->pullbackKit($service_id,$user_id);
            return json_encode($res);
        }
    }
    function requestService(){
        if($this->params['form']){
            $this->autoRender = false;
            $service_id = $this->params['form']['service_id'];
            $user_id = $this->params['form']['user_id'];
            $res = $this->Servicemanagement->requestService($service_id,$user_id);
            return json_encode($res);
        }
    }

    //    function getDistributorData(){
    //        $this->autoRender = FALSE;
         //if ($service_id && is_numeric($service_id)) {
//            //$Object = ClassRegistry::init('Slaves');
//            $dist = $this->Slaves->query('Select id,name,mobile from distributors where active_flag = "' . 1 . '" ');
//            $dist_name = array();
//            $i = 0;
//            foreach($dist as $dists){
//                $dist_name[$i] = $dists['distributors']['name'];
//                $i++;
//             }
//            echo  json_encode($dist_name);

         //}
            // }


    function addUpdateServices(){

        if($this->params['form']){
            $this->autoRender = false;

            $service_id = $this->params['form']['service_id'];
            $mobile = $this->params['form']['mobile'];
            $user_id = $this->params['form']['user_id'];
            $retailer_id = $this->params['form']['retailer_id'];
            $dist_id = $this->params['form']['dist_id'];
            $distributor_id = $this->params['form']['distributor_id'];
            $payment_mode = $this->params['form']['payment_mode'];
            // $ret_margin = $this->params['form']['ret_margin'];



//            $service_urls = Configure::read('service_urls');
            Configure::load('bridge');
            $service_urls = Configure::read('notification_url');
            $service_activation_sms_templates = $this->Servicemanagement->getServiceActivationTemplates();
            $service_activation_sms_templates = json_decode($service_activation_sms_templates,true);


            unset($this->params['form']['service_id']);
            unset($this->params['form']['mobile']);
            unset($this->params['form']['user_id']);
            unset($this->params['form']['retailer_id']);
            unset($this->params['form']['dist_id']);
            unset($this->params['form']['distributor_id']);
//            unset($this->params['form']['ret_margin']);


            if(!array_key_exists('kit_flag', $this->params['form'])){
                $this->params['form']['kit_flag'] = 0;
            }
            if(!array_key_exists('service_flag', $this->params['form'])){
                $this->params['form']['service_flag'] = 0;
            }
            $data = array(
                'kit_flag' => $this->params['form']['kit_flag'],
                'service_flag' => $this->params['form']['service_flag']
            );

            $service_details = $this->Serviceintegration->getServiceDetails();
            $service_details = json_decode($service_details,true);
            if( in_array($service_details[$service_id]['registration_type'],array(3,4)) && ($data['service_flag'] != 0) ){
                $data['kit_flag'] = 1;
            }


            unset($this->params['form']['kit_flag']);
            unset($this->params['form']['service_flag']);

            $otp = null;
            if(array_key_exists('otp', $this->params['form'])){
                $otp = $this->params['form']['otp'];
                unset($this->params['form']['otp']);
            }
            // if(!empty($ret_margin))
            // {
            //     $data['ret_margin'] = $ret_margin;
            // }
            $data['params'] = json_encode($this->params['form']);
            $user_service = $this->Servicemanagement->getUserServices($user_id,$service_id);

            $Object = ClassRegistry::init('User');
            $dataSource = $Object->getDataSource();
            $dataSource->begin();

            $action = '';
            // check user service log for latest action
            $last_log = $this->Servicemanagement->getLastLog($user_id,$service_id);
            $last_action = $last_log[0]['users_services_log']['action'];

            $service_plans = $this->Serviceintegration->getServicePlans();
            $service_plans = json_decode($service_plans,true);
            $service_plans = $service_plans[$service_id];

            if( $service_details[$service_id]['registration_type'] == 2 ){ // kit based
                if( array_key_exists($this->params['form']['plan'],$service_plans) && (!$service_plans[$this->params['form']['plan']]['is_visible']) ){
                    $dataSource->rollback();
                    return json_encode(array(
                        'status' => 'failure',
                        'description' => 'Invalid Plan'
                    ));
                }
            }

            if( count($user_service) > 0 && !in_array($last_action,array('kit_deactivated','kit_refunded','service_deactivated')) ){
                $previous_params = json_decode($user_service[$service_id]['params'],true);

                if( $data['kit_flag'] == 0 ){

                    /*** CHARGING AMOUNT TO DISTRIBUTOR FOR KIT REINITIATION ***/
                    if( in_array($previous_params['payment_mode'],array(1)) && ($service_details[$service_id]['registration_type'] == 2) ){
                        $amount_temp = Configure::read('distDeactivatePlanCharges');
                        $amount = $amount_temp[$service_id];
                        $discount_temp = Configure::read('distDeactivatePlanDiscountCharges');
                        $discount = $discount_temp[$service_id];
                        if( $amount > 0 ){
                            $services = $service_details;
                            $description = 'Charges against kit reinitiation against '.$services[$service_id].' service for retailer .'.$mobile.'-'.$user_id;
                            $wallet_res = $this->Bridge->kitCharge($amount,$dist_id,$service_id,$description,$dataSource,$discount);
                            if( $wallet_res['status'] == 'success' ){

                                $service_plans_id = $service_plans[$previous_params['plan']]['id'];

                                $kits = $this->Slaves->query("SELECT kits
                                                FROM distributors_kits
                                                WHERE distributor_id IN($distributor_id)
                                                AND service_plans_id = $service_plans_id
                                                AND service_id = $service_id");

                                if( count($kits) > 0 ){
                                    $result = $dataSource->query("UPDATE distributors_kits SET kits = kits +1 WHERE distributor_id IN
                                            ($distributor_id) AND service_plans_id = $service_plans_id AND service_id = $service_id");
                                } else {
                                    $result = $dataSource->query('INSERT INTO distributors_kits(distributor_id,service_id,service_plans_id,kits,updated)
                                                    VALUES('.$distributor_id.','.$service_id.','.$service_plans_id.',1,"'.date('Y-m-d H:i:s').'")');
                                }

                            }
                            if( $wallet_res['status'] == 'failure' ){
                                $dataSource->rollback();
                                return json_encode(array(
                                    'status' => 'failure',
                                    'description' => 'Couldn\'t deactivate the KIT.'. $wallet_res['description']
                                ));
                            }
                        }
                    }
                    /*** CHARGING AMOUNT TO DISTRIBUTOR FOR KIT REINITIATION ***/
                    if( in_array($service_details[$service_id]['registration_type'],array(3,4)) ){
                        $data['service_flag'] = 0;
                    }


                    // if( !in_array($service_details[$service_id]['registration_type'],array(3,4)) ){
                        // $data['params'] = '[]';
                    // }
//                    $response = $this->Servicemanagement->deleteUserService($user_id,$service_id,$dataSource);

                    $response = $this->Servicemanagement->updateUserService($user_id,$service_id,$data,$dataSource,$service_plans[$this->params['form']['plan']]['id']);
                    $action = 'kit_deactivated';
                    if( in_array($service_details[$service_id]['registration_type'],array(3,4)) ){
                        $action = 'service_deactivated';
                    }

                } else {

                    // FIELD VALIDAION : START
                    foreach($this->params['form'] as $field_key => $field_value){
                        $field_validation_res = $this->Servicemanagement->validateField($user_id,$service_id,$field_key,$field_value);
                        if($field_validation_res['status'] == 'failure'){
                            return json_encode(array(
                                'status' => 'failure',
                                'description' => $field_validation_res['description']
                            ));

                        }
                        if( in_array($service_details[$service_id]['registration_type'],array(2,3,4)) && ($field_key == 'plan') && ($field_value == '') ){
                            return json_encode(array(
                                'status' => 'failure',
                                'description' => 'Plan missing'
                            ));
                        }
//                        if ($field_key == 'used') {
//                            $service_fields = Configure::read('service_fields');
//                            $validation_rules = explode('|', $service_fields[$service_id][$field_key]['validation']);
//
//                            if (in_array('readonly', $validation_rules)) {
//                                $temp = json_decode($data['params'], true);
//                                $temp[$field_key] = $previous_params[$field_key];
//                                $data['params'] = json_encode($temp);
//                            }
//                        }
                    }
                    // FIELD VALIDAION : END

                    // check kyc verification
                    // if( $data['service_flag'] == 1 ){
                    //     $kyc = true;
                    //     $doc_info = $this->Documentmanagement->checkDocs($user_id,$service_id);
                    //     if( array_key_exists('document',$doc_info) && array_key_exists($service_id,$doc_info['document']) ){
                    //         foreach( $doc_info['document'][$service_id] as $doc_key => $doc ) {
                    //             if( !array_key_exists('pay1_status',$doc) || (strtolower($doc['pay1_status']) != 'approved') ){
                    //                 $kyc = false;
                    //                 break;
                    //             }
                    //         }
                    //     }

                    //     if( !$kyc ){
                    //         return json_encode(array(
                    //             'status' => 'failure',
                    //             'description' => 'KYC pending for this retailer. Can not activate service'
                    //         ));
                    //     }
                    // }

                        $temp = json_decode($data['params'],true);
                        $data['params'] = json_encode(array_merge($previous_params,$temp));

                    $response = $this->Servicemanagement->updateUserService($user_id,$service_id,$data,$dataSource,$service_plans[$this->params['form']['plan']]['id']);
                    $action = 'update';
                }

            } else {

                if( in_array($service_details[$service_id]['registration_type'],array(3,4))  && ($data['service_flag'] == 0) ){
                    return json_encode(array(
                        'status' => 'failure',
                        'description' => 'Service Flag cant be empty.'
                    ));
                }

                if( $data['kit_flag'] == 0 ){
                    return json_encode(array(
                        'status' => 'failure',
                        'description' => 'Kit Flag cant be empty.'
                    ));
                }



                // FIELD VALIDAION : START
                foreach($this->params['form'] as $field_key => $field_value){
                    $field_validation_res = $this->Servicemanagement->validateField($user_id,$service_id,$field_key,$field_value);
                    if($field_validation_res['status'] == 'failure'){
                        return json_encode(array(
                            'status' => 'failure',
                            'description' => $field_validation_res['description']
                        ));
                    }
                    if( in_array($service_details[$service_id]['registration_type'],array(2,3,4)) && ($field_key == 'plan') && ($field_value == '') ){
                        return json_encode(array(
                            'status' => 'failure',
                            'description' => 'Plan missing'
                        ));
                    }
                }
                // FIELD VALIDAION : END

                // check kyc verification
                // if( $data['service_flag'] == 1 ){
                //     $kyc = true;
                //     $doc_info = $this->Documentmanagement->checkDocs($user_id,$service_id);
                //     if( array_key_exists('document',$doc_info) && array_key_exists($service_id,$doc_info['document']) ){
                //         foreach( $doc_info['document'][$service_id] as $doc_key => $doc ) {
                //             if( !array_key_exists('pay1_status',$doc) || (strtolower($doc['pay1_status']) != 'approved') ){
                //                 $kyc = false;
                //                 break;
                //             }
                //         }
                //     }

                //     if( !$kyc ){
                //         return json_encode(array(
                //             'status' => 'failure',
                //             'description' => 'KYC pending for this retailer. Can not activate service'
                //         ));
                //     }
                // }


                if( $service_id == 8 ){
                    $temp = json_decode($data['params'],true);
                    $temp['type'] = 1;
                    $data['params'] = json_encode($temp);
                }

                if( in_array($last_action,array('kit_deactivated','kit_refunded','service_deactivated')) ){
                    $response = $this->Servicemanagement->updateUserService($user_id,$service_id,$data,$dataSource,$service_plans[$this->params['form']['plan']]['id']);
                } else {
                    $response = $this->Servicemanagement->addUserService($user_id,$service_id,$data,$dataSource,$service_plans[$this->params['form']['plan']]['id']);
                }

                // Entry in kit_delivery_log on kit purchase via retailer wallet|instamojo if delivery_flag is true
                $kit_delivery_res = true;
                if( in_array($payment_mode,array(2,3)) && ($service_plans[$this->params['form']['plan']]['delivery_flag']) ){
                    $kit_delivery_data = array(
                        'ret_user_id' => $user_id,
                        'dist_user_id' => $dist_id,
                        'group_id' => RETAILER,
                        'source' => 'service_activation_panel',
                        'service_id' => $service_id,
                        'service_plan_id' => $service_plans[$this->params['form']['plan']]['id'],
                        'kits' => 1,
                        'purchased_date' => date("Y-m-d"),
                        'purchased_timestamp' => date("Y-m-d h:i:s")
                    );
                    $kit_delivery_res = $this->Servicemanagement->addKitDeliveryLog($kit_delivery_data,$dataSource);
                }
                // Entry in service_request_log on kit purchase/kit assign via dist_kit|retailer wallet| instamojo
                if( in_array($payment_mode,array(2,3,4)) ){
                    $service_request_data = array(
                        'kit_purchase_date' => date("Y-m-d"),
                        'kit_purchase_timestamp' => date("Y-m-d h:i:s"),
                        'service_request_date' => date('Y-m-d'),
                        'service_request_timestamp' => date('Y-m-d H:i:s'),
                        'ret_user_id' => $user_id,
                        'source' => 'service_activation_panel',
                        'service_id' => $service_id
                    );
                    $service_request_res = $this->Servicemanagement->addServiceRequestLog($service_request_data,$dataSource);
                }

                $action = 'add';
            }

            if( $action != 'add' ){
                $kit_delivery_res = true;
                $service_request_res = true;
            }

            if( $response && $kit_delivery_res && $service_request_res ){
                $data['service_id'] = $service_id;
                $data['user_id'] = $user_id;
                $data['action'] = $action;
                switch ($service_id) {
                    case !in_array($service_id,array(12)):


                        /*** CHARGING AMOUNT TO DISTRIBUTOR||RETAILER FOR PLAN ACTIVATION ***/
                        if( $service_details[$service_id]['registration_type'] == 2 ){

                            $params = json_decode($data['params'],true);
                            if( ($action == 'add') && in_array($payment_mode,array(1,4)) ){


                                $service_plans_id = $service_plans[$params['plan']]['id'];

                                $kits = $this->Slaves->query("SELECT kits
                                                FROM distributors_kits
                                                WHERE distributor_id IN($distributor_id) AND service_plans_id = $service_plans_id AND kits > 0 AND service_id = ".$service_id);
                                if( count($kits) == 0 ){
                                    $dataSource->rollback();
                                    return json_encode(array(
                                        'status' => 'failure',
                                        'description' => 'Distributor doesn\'t have enough kits of this plan'
                                    ));
                                }
                            }



                            // $distPlanCharges_temp = Configure::read('distPlanCharges');
                            // $distPlanCharges = $distPlanCharges_temp[$service_id];
                            $distPlanCharges =  $service_plans;
                            if( in_array($action,array('add','update')) && array_key_exists($params['plan'],$distPlanCharges) ){
                                $settle_flag = '1';
                                switch ($payment_mode) {
                                    case ($payment_mode == 1 || $payment_mode == 4): // Distributor Wallet,Distributor Kit
                                        $amount = ($distPlanCharges[$params['plan']]['setup_amt']-$distPlanCharges[$params['plan']]['dist_commission']);
                                        if( $action == 'update' ){
                                            if( isset($previous_params['plan']) && !empty($previous_params['plan']) ){
                                                $amount = $amount - ($distPlanCharges[$previous_params['plan']]['setup_amt']-$distPlanCharges[$previous_params['plan']]['dist_commission']);
                                            }
                                        }
                                        $user_id = $dist_id;
                                        if($payment_mode == 4){
                                            $settle_flag = '0';
                                        }
                                    break;
                                    case ($payment_mode == 2 || $payment_mode == 3): // Retailer Wallet,Instamojo

                                        // $retPlanCharges_temp = Configure::read('retPlanCharges');
                                        // $retPlanCharges = $retPlanCharges_temp[$service_id];
                                        $retPlanCharges = $service_plans;
                                        $amount = $retPlanCharges[$params['plan']]['setup_amt'];
                                        $dist_comm_amt = $retPlanCharges[$params['plan']]['dist_commission'];
                                        if( $action == 'update' ){
                                            if( isset($previous_params['plan']) && !empty($previous_params['plan']) ){
                                                $amount = $amount - $retPlanCharges[$previous_params['plan']]['setup_amt'];
                                                $dist_comm_amt = $dist_comm_amt - $retPlanCharges[$previous_params['plan']]['dist_commission'];
                                            }
                                        }

                                        if($payment_mode == 3){
                                            $settle_flag = '0';
                                        }
                                    break;
                                    default:
                                    break;
                                }


                                if( in_array($action,array('add','update')) && ($amount > 0) && ($payment_mode != 4) ){
                                    // $plans_temp = Configure::read('plans');
                                    // $plans = $plans_temp[$service_id];
                                    $plans = $service_plans;
                                    // $services = Configure::read('services');
                                    $services = $service_details;

                                    if( $payment_mode == 2 ){   // retailer wallet

                                        if($otp){

                                            if( $otp == $this->Shop->getMemcache("otp_mposkitplanactivation_$mobile"."_$service_id") || !$this->General->isOTPRequired($mobile)){
                                                $this->Shop->delMemcache("otp_mposkitplanactivation_$mobile"."_$service_id");
                                            } else {
                                                return json_encode(array(
                                                    'status'=>'failure',
                                                    'description'=>'Invalid OTP'
                                                ));
                                            }

                                        } else {

                                            // send OTP here
                                            $otp = $this->General->generatePassword(6);
                                            $this->Shop->setMemcache("otp_mposkitplanactivation_$mobile"."_$service_id", $otp, 30 * 60);
                                            // $message = 'One Time Password(OTP) to activate '.$plans[$params['plan']]['plan_name'].' plan against '.$services[$service_id]['name'].' service is '.$otp.'. This is valid for next 30 mins. Do not share it with anyone.';
                                            $message = 'Kindly share the pin '.$otp.' with the company to activate the plan '.$plans[$params['plan']]['plan_name'].' for the '.$services[$service_id]['name'].' service. Pin is valid for the next 30 min.';
                                            $this->General->sendMessage($mobile, $message, 'payone', null);
                                            return json_encode(array(
                                                'status'=>'failure',
                                                'action'=>'otpsent',
                                                'description'=>'OTP has been sent to mobile no.'.$mobile
                                            ));
                                        }
                                    }
                                    $description = 'Activated '.$plans[$params['plan']]['plan_name'].' plan against '.$services[$service_id]['name'].' service for retailer .'.$mobile.'-'.$user_id;

                                    $wallet_res = $this->Bridge->kitCharge($amount,$user_id,$service_id,$description,$dataSource,null,$settle_flag);
                                    if( $wallet_res['status'] == 'failure' ){
                                        $dataSource->rollback();
                                        return json_encode(array(
                                            'status' => 'failure',
                                            'description' => 'Couldn\'t activate the plan.'. $wallet_res['description']
                                        ));
                                    }
                                    if( ($payment_mode == 2 || $payment_mode == 3)  && ($dist_comm_amt > 0 ) ){ // give commission to distributor
                                        $description = 'Commission for activating plan '.$plans[$params['plan']]['plan_name'].' against '.$services[$service_id]['name'].' service for retailer .'.$mobile.'-'.$user_id;
                                        $wallet_dist_res = $this->Servicemanagement->distCommission($dist_comm_amt,$distributor_id,$dist_id,$service_id,$description,$dataSource,null,$settle_flag);
                                        if( $wallet_dist_res['status'] == 'failure' ){
                                            $dataSource->rollback();
                                            return json_encode(array(
                                                'status' => 'failure',
                                                'description' => 'Couldn\'t activate the plan.'. $wallet_dist_res['description']
                                            ));
                                        }
                                    }

                                }
                            }
                        }
                        /*** CHARGING AMOUNT TO DISTRIBUTOR FOR PLAN ACTIVATION ***/

                        $user_id = $data['user_id'];

                        /*** SENDING DATA TO PRODUCT ***/
                        if($service_urls[$service_id]){
                            $product_response = $this->General->curl_post(
                                $service_urls[$service_id],
                                $data,
                                'POST'
                            );
                            $product_response = json_decode($product_response['output'],true);


                            if( count($product_response) > 0 && $product_response['status'] == 'success' ){
                                $log_response = $this->Servicemanagement->addServiceLog($user_id,$service_id,$data,$action,$dataSource);

                                $dataSource->commit();

                                /*** decrement kit count from distributor account for mpos service  - Ajay ***/

                                $obj = json_decode($data['params'], true);
                                if( ($action == 'add') && in_array($payment_mode,array(1,4)) && ($service_details[$service_id]['registration_type'] == 2) ){  //payment mode :1 -> Distributor wallet,4->Distributor Kit
                                    $ret_userid = $data['user_id'];

                                    $service_plans_id = $service_plans[$obj['plan']]['id'];
                                    // if( ($data['kit_flag'] == 1) && !empty($obj['device_id']) ){
                                    if( $data['kit_flag'] == 1 ){
                                        $result = $this->User->query("UPDATE distributors_kits SET kits = kits -1 WHERE kits > 0 and distributor_id IN
                                            ($distributor_id) AND service_plans_id = $service_plans_id AND service_id = ".$service_id);
                                    }
                                }

                                // sending sms to retailer for mpos and aeps service activation
                                if( array_key_exists($service_id,$service_activation_sms_templates) && !empty($service_activation_sms_templates[$service_id]) ){
                                    $send_sms = false;
                                    if( ($action == 'add') && $data['service_flag'] == 1 ){
                                        $send_sms = true;
                                    }
                                    if( ($action == 'update') && ($user_service[$service_id]['service_flag'] != 1) && ($data['service_flag'] == 1) ){
                                        $send_sms = true;
                                    }

                                    if( $send_sms ){
                                        $sms = $service_activation_sms_templates[$service_id];
                                        foreach($obj as $field => $value){
                                            $sms = str_replace('<'.$field.'>',$value, $sms);
                                        }
                                        $this->General->sendMessage($mobile, $sms, 'payone', null);
                                    }
                                }

                                return json_encode(array(
                                    'status'=>'success',
                                    'msg'=> 'Service updated successfully.',
                                    'product_response' => $product_response,
                                    'action' =>$action
                                ));
                            } else {
                                $dataSource->rollback();
                                return json_encode(array(
                                    'status' => 'failure',
                                    'description' => 'Something went wrong. Please try again.',
                                    'product_response' => $product_response
                                ));
                            }
                        }
                        /*** SENDING DATA TO PRODUCT ***/

                    break;
                    case 12:

                        $data['retailer_id'] = $retailer_id;
                        $data['mobile'] = $mobile;

                        if($service_urls[$service_id]){
                            $product_response = $this->General->curl_post(
                                $service_urls[$service_id],
                                $data,
                                'POST'
                            );
                            $product_response = json_decode($product_response['output'],true);

                            if( count($product_response) > 0 && $product_response['status'] == 'success'){
                                $log_response = $this->Servicemanagement->addServiceLog($user_id,$service_id,$data,$action,$dataSource);
                                $dataSource->commit();

                                $obj = json_decode($data['params'], true);
                                $kyc_flag = ($data['service_flag'] == 1) ? 1 : 0;
                                $dataSource->query("UPDATE retailers SET kyc_flag = '$kyc_flag' WHERE id = $retailer_id");


                                // sending sms to retailer for dmt service activation
                                if( array_key_exists($service_id,$service_activation_sms_templates) && !empty($service_activation_sms_templates[$service_id]) ){
                                    $send_sms = false;
                                    if( ($action == 'add') && $data['service_flag'] == 1 ){
                                        $send_sms = true;
                                    }
                                    if( ($action == 'update') && ($user_service[$service_id]['service_flag'] != 1) && ($data['service_flag'] == 1) ){
                                        $send_sms = true;
                                    }

                                    if( $send_sms ){
                                        $sms = $service_activation_sms_templates[$service_id];
                                        foreach($obj as $field => $value){
                                            $sms = str_replace('<'.$field.'>',$value, $sms);
                                        }
                                        $this->General->sendMessage($mobile, $sms, 'payone', null);
                                    }
                                }

                                return json_encode(array(
                                    'status'=>'success',
                                    'msg'=> 'Service updated successfully.',
                                    'product_response' => $product_response,
                                    'action' =>$action
                                ));
                            } else {
                                $dataSource->rollback();
                                return json_encode(array(
                                    'status' => 'failure',
                                    'description' => 'Something went wrong. Please try again.',
                                    'product_response' => $product_response
                                ));
                            }
                        }

                    break;
                    default:

                    break;
                }
                $log_response = $this->Servicemanagement->addServiceLog($user_id,$service_id,$data,$action,$dataSource);
                $dataSource->commit();

                $obj = json_decode($data['params'], true);
                if( ($action == 'add') && in_array($payment_mode,array(1,4)) && ($service_details[$service_id]['registration_type'] == 2) ){  //payment mode :1 -> Distributor wallet,4->Distributor Kit
                    $ret_userid = $data['user_id'];

                    $service_plans_id = $service_plans[$obj['plan']]['id'];
                    // if( ($data['kit_flag'] == 1) && !empty($obj['device_id']) ){
                    if( $data['kit_flag'] == 1 ){
                        $result = $this->User->query("UPDATE distributors_kits SET kits = kits -1 WHERE kits > 0 and distributor_id IN
                            ($distributor_id) AND service_plans_id = $service_plans_id AND service_id = ".$service_id);
                    }
                }


                // sending sms to retailer for dmt service activation
                if( array_key_exists($service_id,$service_activation_sms_templates) && !empty($service_activation_sms_templates[$service_id]) ){
                    $send_sms = false;
                    if( ($action == 'add') && $data['service_flag'] == 1 ){
                        $send_sms = true;
                    }
                    if( ($action == 'update') && ($user_service[$service_id]['service_flag'] != 1) && ($data['service_flag'] == 1) ){
                        $send_sms = true;
                    }

                    if( $send_sms ){
                        $sms = $service_activation_sms_templates[$service_id];
                        foreach($obj as $field => $value){
                            $sms = str_replace('<'.$field.'>',$value, $sms);
                        }
                        $this->General->sendMessage($mobile, $sms, 'payone', null);
                    }
                }


                return json_encode(array(
                    'status'=>'success',
                    'msg'=> 'Service updated successfully.',
                    'action' =>$action
                ));
            } else {
                $dataSource->rollback();
                return json_encode(array(
                    'status' => 'failure',
                    'description' => 'Something went wrong. Please try again'
                ));
            }
        }
    }

//     function reactivateService(){
//         if($this->params['form']){
//             $this->autoRender = false;
//             $service_id = $this->params['form']['service_id'];
//             $user_id = $this->params['form']['user_id'];
//             Configure::load('bridge');
//             $service_urls = Configure::read('notification_url');
//             unset($this->params['form']['service_id']);
//             $data = array(
//                 'service_flag' => $this->params['form']['service_flag']
//             );
//             unset($this->params['form']['service_flag']);

//             $Object = ClassRegistry::init('User');
//             $dataSource = $Object->getDataSource();
//             $dataSource->begin();

// //            $response = $this->Servicemanagement->updateServiceFlag($user_id,$service_id,$data,$dataSource);
//             $action = 'service_reactivated';

// //            if($response){
//                 $data['service_id'] = $service_id;
//                 $data['user_id'] = $user_id;
//                 $data['action'] = $action;


//                 /*** SENDING DATA TO PRODUCT ***/
// //                if($service_urls[$service_id]){
//                     $response = $this->Servicemanagement->reactivate($user_id,$service_id,$dataSource);
//                     $response = json_decode($response,true);
//                     $notification_data = $response['notification_data'];

//                     if(($response) && $response['status'] == 'success'){
//                         if($service_urls[$service_id]){
//                             $product_response = $this->General->curl_post(
//                                 $service_urls[$service_id],
//                                 $data,
//                                 'POST'
//                             );
//                             $product_response = json_decode($product_response['output'],true);
//                             if( ($product_response) && $product_response['status'] == 'success'){
//                                 $response = $this->Servicemanagement->updateServiceFlag($user_id,$service_id,$data,$dataSource);
//                                 $log_response = $this->Servicemanagement->addServiceReactLog($user_id,$service_id,$data,$action,$dataSource);

//                                 $dataSource->commit();
//                                 $this->Shop->sendNotification($notification_data['user_id'],$notification_data['service_id'],$notification_data['mobile'],$notification_data['amount'],$notification_data['next_rental_debit_date'],$notification_data['deducted_month'],$notification_data['mode']);
//                                 return json_encode(array(
//                                     'status'=>'success',
//                                     'msg'=> 'Service reactivated successfully.',
//                                     'product_response' => $product_response,
//                                     'action' =>$action
//                                 ));
//                             } else {
//                                 $dataSource->rollback();

//                                 return json_encode(array(
//                                     'status' => 'failure',
//                                     'description' => 'Something went wrong. Please try again'
//                                 ));


//                             }
//                         }
//                     }
//                     else{
//                         $dataSource->rollback();
//                         return json_encode(array(
//                             'status' => $response['status'],
//                             'description' => $response['description']
//                         ));
//                     }
// //                }
//                 /*** SENDING DATA TO PRODUCT ***/


//                 $log_response = $this->Servicemanagement->addServiceReactLog($user_id,$service_id,$data,$action,$dataSource);
//                 $dataSource->commit();
//                 return json_encode(array(
//                     'status'=>'success',
//                     'msg'=> 'Service updated successfully.',
//                     'action' =>$action
//                 ));

// //            } else {
// //                $dataSource->rollback();
// //                return json_encode(array(
// //                    'status' => 'failure',
// //                    'description' => 'Something went wrong. Please try again'
// //                ));
// //            }
//         }
//     }



}
