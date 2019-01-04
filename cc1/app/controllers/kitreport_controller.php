<?php
// //RohitP(rohit3nov@gmail.com)
class KitreportController extends AppController {
    var $name = 'Kitreport';
    var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv');
    var $uses = array('User','Slaves');
    var $components = array('RequestHandler','Servicemanagement','Serviceintegration');

    function beforeFilter()
    {
        parent::beforeFilter();
    }
    function index() {
        $this->layout = "plain";
        $dist_mob = isset($this->params['form']['distmob']) && !empty($this->params['form']['distmob']) ? $this->params['form']['distmob'] : null;
        $service_id = isset($this->params['form']['service_id']) && !empty($this->params['form']['service_id']) ? $this->params['form']['service_id'] : 8;
        $dist_user = array();

        $services = $this->Serviceintegration->getAllServices();
        $services = json_decode($services,true);

        $distributors = $this->Servicemanagement->getDistributors($dist_mob);


        // for getting the distributor details and its's pending kits
        $dist_pending_kits = $this->Servicemanagement->fetchDistributorPendingkits(implode(',',array_keys($distributors)),$service_id,0);



        // for getting the distributor total purchased kits
        // $dist_total_purchased_kits = $this->Servicemanagement->fetchDistributorTotalPurchasedKit(implode(',',array_map(function($elm){return $elm['user_id'];},$distributors)),$service_id,0);
        $dist_total_purchased_kits = $this->Servicemanagement->fetchDistributorTotalPurchasedKit(implode(',',array_keys($distributors)),$service_id,0);
        // $dist_total_refunded_kits = $this->Servicemanagement->fetchDistributorRefundedKits(implode(',',array_map(function($elm){return $elm['user_id'];},$distributors)),$service_id,0);
        $dist_total_refunded_kits = $this->Servicemanagement->fetchDistributorRefundedKits(implode(',',array_keys($distributors)),$service_id,0);


        // $direct_buy_kits_by_distributors = $this->Servicemanagement->fetchDirectBuyKitsByDistributors(implode(',',array_map(function($elm){return $elm['user_id'];},$distributors)),$service_id,0);
        $direct_buy_kits_by_retailers = $this->Servicemanagement->fetchDirectBuyKitsByRetailers(implode(',',array_keys($distributors)),$service_id,0);
        // $ret_total_refunded_kits = $this->Servicemanagement->fetchRetailerRefundedKits(implode(',',array_keys($distributors)),$service_id,0);

        $assigned_kits_to_retailer = $this->Servicemanagement->assignedKitsToRetailer(implode(',',array_keys($distributors)),$service_id,0);
        // echo '<pre>';
        // print_r($total_assigned_kits_to_retailer);
        // exit;

        // $hothis->set('distributor_data', $distributor_data);
        // $this->set('dist_total_kits', $dist_total_kits);

        foreach ( $distributors as $dist_id => $distributor ) {
            if( ( !array_key_exists($dist_id,$dist_total_purchased_kits) || empty($dist_total_purchased_kits[$dist_id]) ) &&
                ( !array_key_exists($dist_id,$assigned_kits_to_retailer) || empty($assigned_kits_to_retailer[$dist_id]) ) &&
                ( !array_key_exists($dist_id,$dist_pending_kits) || empty($dist_pending_kits[$dist_id]) ) &&
                ( !array_key_exists($dist_id,$dist_total_refunded_kits) || empty($dist_total_refunded_kits[$dist_id]) ) &&
                ( !array_key_exists($dist_id,$direct_buy_kits_by_retailers) || empty($direct_buy_kits_by_retailers[$dist_id]) )
            ){
                unset($distributors[$dist_id]);
            }
        }


        $this->set('distributors', $distributors);
        $this->set('dist_pending_kits', $dist_pending_kits);
        $this->set('dist_total_purchased_kits', $dist_total_purchased_kits);
        $this->set('dist_total_refunded_kits', $dist_total_refunded_kits);
        // $this->set('direct_buy_kits_by_distributors', $direct_buy_kits_by_distributors);
        $this->set('direct_buy_kits_by_retailers', $direct_buy_kits_by_retailers);
        // $this->set('ret_total_refunded_kits', $ret_total_refunded_kits);
        $this->set('assigned_kits_to_retailer', $assigned_kits_to_retailer);

        $this->set('mobile', $dist_mob);
        $this->set('mobile', $dist_mob);
        $this->set('service_id', $service_id);
        $this->set('services', $services);
        $this->render('/servicemanagement/distributorkits');
    }
    function getKitEntries(){
        $this->autoRender = false;

        $dist_id = isset($this->params['form']['dist_id']) && !empty($this->params['form']['dist_id']) ? $this->params['form']['dist_id'] : null;
        $dist_user_id = isset($this->params['form']['dist_user_id']) && !empty($this->params['form']['dist_user_id']) ? $this->params['form']['dist_user_id'] : null;
        $type = isset($this->params['form']['type']) && !empty($this->params['form']['type']) ? $this->params['form']['type'] : null;
        $service_id = isset($this->params['form']['service_id']) && !empty($this->params['form']['service_id']) ? $this->params['form']['service_id'] : null;

        $kit_entries = array();
        if( $dist_id && $dist_user_id && $type && $service_id){

            switch ($type) {
                case 'dist_total_purchased_kits':
                    $kit_entries = $this->Servicemanagement->fetchDistributorTotalPurchasedKit($dist_id,$service_id,1);
                    // return $dist_total_purchased_kits;
                break;
                case 'direct_buy_kits_by_distributors':
                    $kit_entries = $this->Servicemanagement->fetchDirectBuyKitsByDistributors($dist_user_id,$service_id,1);
                    // return $direct_buy_kits_by_distributors;
                break;
                case 'direct_buy_kits_by_retailers':
                    $kit_entries = $this->Servicemanagement->fetchDirectBuyKitsByRetailers($dist_id,$service_id,1);
                    $plans = $this->Serviceintegration->getServicePlans();
                    $plans = json_decode($plans,true);
                    foreach ($kit_entries as $key => $value) {
                        $params = json_decode($value['us']['params'],true);
                        unset($kit_entries[$key]['us']['params']);
                        $kit_entries[$key]['us']['plan'] = $plans[$service_id][$params['plan']]['plan_name'];
                        $kit_entries[$key]['us']['plan_amount'] = $plans[$service_id][$params['plan']]['setup_amt'];
                    }
                    // return $direct_buy_kits_by_retailers;
                break;
                case 'assigned_kits_to_retailer':
                    $kit_entries = $this->Servicemanagement->assignedKitsToRetailer($dist_id,$service_id,1);
                    $plans = $this->Serviceintegration->getServicePlans();
                    $plans = json_decode($plans,true);
                    foreach ($kit_entries as $key => $value) {
                        $params = json_decode($value['us']['params'],true);
                        unset($kit_entries[$key]['us']['params']);
                        $kit_entries[$key]['us']['plan'] = $plans[$service_id][$params['plan']]['plan_name'];
                        $kit_entries[$key]['us']['plan_amount'] = $plans[$service_id][$params['plan']]['setup_amt'];
                    }


                    // return $assigned_kits_to_retailer;
                break;
                case 'total_dist_ret_refunded_kits':
                    $kit_entries = $this->Servicemanagement->fetchDistributorRefundedKits($dist_id,$service_id,1);
                //    $kit_entries_ret = $this->Servicemanagement->fetchRetailerRefundedKits($dist_id,$service_id,1);
                //    $kit_entries = array_merge($kit_entries_dist,$kit_entries_ret);
                    // $plans = $this->Serviceintegration->getServicePlans();
                    // $plans = json_decode($plans,true);
                    // foreach ($kit_entries as $key => $value) {
                    //     $params = json_decode($value['us']['params'],true);
                    //     unset($kit_entries[$key]['us']['params']);
                    //     $kit_entries[$key]['us']['plan'] = $plans[$service_id][$params['plan']]['plan_name'];
                    //     $kit_entries[$key]['us']['plan_amount'] = $plans[$service_id][$params['plan']]['setup_amt'];
                    // }


                    // return $assigned_kits_to_retailer;
                break;
                case 'dist_pending_kits':
                    $kit_entries = $this->Servicemanagement->fetchDistributorPendingkits($dist_id,$service_id,1);
                break;

                default:
                break;
            }

            return json_encode(array('status' => 'success','type' => $type,'data' => $kit_entries));

        }

        return json_encode(array('status' => 'failure','description' => 'Something went wrong. Please try again'));
    }
}