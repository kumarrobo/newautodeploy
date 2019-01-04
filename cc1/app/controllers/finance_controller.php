<?php
class FinanceController extends AppController {

    var $name = 'Finance';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
	var $components = array('RequestHandler','Shop','General','Finance');
	var $uses = array('User','Slaves');

	function beforeFilter(){
                parent::beforeFilter();

                 $this->set('General', $this->General);
	}

    function overview(){
        if($this->Session->read('Finance') == ""){
            $this->autoRender = false;

            if($this->Session->read('Auth.User.group_id') == SUPER_ADMIN){
                $id = $this->Session->read('Auth.User.id');
                $mobile = $this->Session->read('Auth.User.mobile');

                $this->Finance->sendOTPViaEmail($id,$mobile);
            }else{
                $msg = "<b>Error</b> :  You dont have access to view this page"; 
                $this->Session->setFlash($msg);
            }
            $this->render('verify_otp');
        }

        $flag = true;

        $params = $this->params['form'];

        /*Set default date as well as form date*/
        $from_date = $params['from_date']!=""?date('Y-m-d',strtotime($params['from_date'])):date('Y-m-d',strtotime('-1 week'));
        $to_date = $params['to_date']!=""?date('Y-m-d',strtotime($params['to_date'])):date('Y-m-d',strtotime('-1 day'));

        $date1=date_create($from_date);
        $date2=date_create($to_date);

        $diff=date_diff($date1,$date2);

        $no_of_days = $diff->format("%a")+1;

        $this->set('from_date',$from_date);
        $this->set('to_date',$to_date);
        $this->set('no_of_days',$no_of_days);

        $all_services = $this->Shop->getAllServices();
        $services = "";
        foreach ($all_services as $key=>$service_name){
            $services .= $key.",";
        }
        $service = rtrim($services,",");

        /*Get Child Service based on Parent Service*/
        $child_service_array = $this->Shop->getServiceByParentId($service);
        $child_services = implode(',', $child_service_array);

        /*Get Recharge Child Service based on Parent Service*/
        $recharge_child_service_array = $this->Shop->getServiceByParentId(RECHARGE);
        $recharge_child_services = implode(',', $recharge_child_service_array);

        /*Get Utility Child Service based on Parent Service*/
        $utility_child_service_array = $this->Shop->getServiceByParentId(UTILITY);
        $utility_child_services = implode(',', $utility_child_service_array);

        /*Get DMT Child Service based on Parent Service*/
        $dmt_child_service_array = $this->Shop->getServiceByParentId(DMT);
        $dmt_child_services = implode(',', $dmt_child_service_array);   

        

        if($no_of_days > 31){
            $flag = false;
            $msg = "<b>Error</b> :  Date range cannot exceed 31 days";                  
        }

        if($flag){
            /**********************Recharge****************************/

            /*********Recharge Income*************/
            //$recharge_income_array = $this->Finance->recharge_income($from_date,$to_date);


            /********************Modem**********************/

            /*Modem Sale*/
            $total_modem_sale_array = $this->Finance->total_modem_sale($from_date,$to_date);
            /*End Modem Sale*/

            /*Modem Earning*/
            $modem_earning_array = $this->Finance->modem_earning($from_date,$to_date);        
            /*End Modem Earning*/

             /*Modem GST Asset*/
            $modem_gst_asset_array = $this->Finance->modem_gst_asset($from_date,$to_date);
            
            /*Modem GST Asset*/

            /********************API P2P**********************/

            /*API P2P Sale*/
            $total_api_p2p_sale_array = $this->Finance->total_api_p2p_sale($from_date,$to_date,$recharge_child_services);
            
            /*End API P2P Sale*/

            /*API P2P Earning*/
            $api_p2p_earning_array = $this->Finance->api_p2p_earning($from_date,$to_date,$recharge_child_services);
            
            /*End API P2P Earning*/

            /*API P2P GST Asset*/
            $api_p2p_gst_asset_array = $this->Finance->api_p2p_gst_asset($from_date,$to_date,$recharge_child_services);
            
            /*End API P2P GST Asset*/

            /********************API P2A**********************/

            /*API P2A Sale*/
            $total_api_p2a_sale_array = $this->Finance->total_api_p2a_sale($from_date,$to_date,$recharge_child_services);
            
            /*End API P2A Sale*/
            

            /*API P2A Earning*/
            $api_p2a_earning_array = $this->Finance->api_p2a_earning($from_date,$to_date,$recharge_child_services);
            
            /*End API P2A Earning*/

            /*API P2A GST Asset*/
            $api_p2a_gst_asset_array = $this->Finance->api_p2a_gst_asset($from_date,$to_date,$recharge_child_services);
            
            /*End API P2A GST Asset*/



            /*********End Recharge Income*************/

            /**********************End Recharge****************************/


            /**********************Other****************************/

            
             /********************Income************************/

             /*Retailer Service Charge*/

            $ret_service_charge = $this->Finance->ret_service_charge($from_date,$to_date,$child_services);

            $ret_service_charge_dmt = $this->Finance->ret_service_charge($from_date,$to_date,$dmt_child_services);

            /*Rental Charge*/
 
            $rental_charge = $this->Finance->rental_charge($from_date,$to_date,$child_services);

            /*Kit Charge*/

            $kit_charge = $this->Finance->kit_charge($from_date,$to_date,$child_services);

            /*Vendor Commision*/

            $vendor_commision_utility = $this->Finance->vendor_commision($from_date,$to_date,$utility_child_services,"api_vendors_sale_data","");
            $vendor_commision_other = $this->Finance->vendor_commision($from_date,$to_date,$child_services,"vendor_recon"," JOIN product_vendors pv ON vr.vendor_id = pv.id ");

            /*Vendor Commision Adjustment*/

            $vendor_comm_adjustment = $this->Finance->vendor_comm_adjustment($from_date,$to_date,$child_services);

            /*Vendor Settlement*/

            $vendor_settle = $this->Finance->vendor_set_up_fee($from_date,$to_date,$child_services);

            /*Vendor Incentive*/

            //$vendor_incentive = $this->Finance->vendor_incentive($from_date,$to_date,$child_services);
            $vendor_incentive = array();


            /********************End Income************************/

            /********************Expense************************/

            /*Adjustment*/
            $adjustment = $this->Finance->adjustment($from_date,$to_date);
            
            /*End Adjustment*/

            /*Vendor Service Charge*/

            $vendor_service_charge = $this->Finance->vendor_service_charge($from_date,$to_date,$child_services);
            $vendor_service_charge_dmt = $this->Finance->vendor_service_charge($from_date,$to_date,$dmt_child_services);

            /*Vendor Kit Payment*/

            $vendor_kit_payment = $this->Finance->vendor_kit_payment($from_date,$to_date,$child_services);

            /*Loss Data*/

            $loss = $this->Finance->loss($from_date,$to_date,$child_services);

            /********************End Expense************************/

            /*********Common Expense*************/

            /*Retailer Commision*/
            $ret_commision = $this->Finance->ret_commision($from_date,$to_date,$child_services);
            /*End Retailer Commision*/

            /*Retailer Incentive*/
            $ret_incentive = $this->Finance->ret_incentive($from_date,$to_date,$child_services);
            /*End Retailer Incentive*/

            /*Distributor Incentive*/
            $dist_incentive = $this->Finance->dist_incentive($from_date,$to_date,$child_services);
            /*End Distributor Incentive*/

            /*Distributor Commision*/
            $dist_commision = $this->Finance->dist_commision($from_date,$to_date,$child_services);         
            /*End Distributor Incentive*/

            /*********End Common Expense*************/

            /**********************End Other****************************/

            $total_sale_utility_array = $this->Finance->total_sale($from_date,$to_date,$utility_child_services,"api_vendors_sale_data","sale","");
            $total_sale_other_array = $this->Finance->total_sale($from_date,$to_date,$child_services,"vendor_recon","sale - refund"," JOIN product_vendors pv ON vr.vendor_id = pv.id ");


            $gross_sale_array = array();
            $service_income_array = array();
            $service_expense_array = array();
            $service_pnl_array = array();
            $gst_liability_array = array();
            $gst_vendor_liability_array = array();
            $gst_asset_array = array();
            $net_gst_array = array();

            foreach ($all_services as $service_id=>$service_name){

                for($i=0;$i<$no_of_days;$i++){

                    $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));

                    if($service_id == RECHARGE){ 

                        $recharge_income = $modem_earning_array[$date] + $api_p2p_earning_array[$date] + $api_p2a_earning_array[$date] + $rental_charge['rental_charge_array'][$service_id][$date];

                        $total_sale = $total_modem_sale_array[$date] + $total_api_p2p_sale_array[$date] + $total_api_p2a_sale_array[$date];

                        $gross_sale_array[$service_id][$date] = $total_sale;

                        
                            $service_income_array[$service_id][$date] = $recharge_income;
                            $service_expense_array[$service_id][$date] = 0;
                            $service_pnl_array[$service_id][$date] = $recharge_income;

                            $gst_liability_array[$date] += ROUND(((($total_sale - ($ret_commision[$service_id][$date] + $ret_incentive[$service_id][$date])) / 1.18 ) * 0.18)  + $rental_charge['gst_liability_rental_array'][$service_id][$date]);

                            $gst_asset_array[$date] += $modem_gst_asset_array[$date] + $api_p2p_gst_asset_array[$date] + $api_p2a_gst_asset_array[$date];
                        

                        

                    }else{

                        if($service_id == UTILITY){
                            if(isset($total_sale_utility_array[$service_id])){
                                $gross_sale_array[$service_id][$date] = $total_sale_utility_array[$service_id][$date];
                            }
                        }else{
                            if(isset($total_sale_other_array[$service_id])){
                                $gross_sale_array[$service_id][$date] = $total_sale_other_array[$service_id][$date]; 
                            }
                        }
                        
                        
                        
                        if(isset($ret_service_charge['ret_service_charge_array'][$service_id]) || isset($ret_service_charge_dmt['ret_service_charge_array'][$service_id])|| isset($rental_charge['rental_charge_array'][$service_id]) || isset($kit_charge['kit_charge_array'][$service_id]) || isset($vendor_commision_utility['vendor_commision_array'][$service_id]) || isset($vendor_commision_other['vendor_commision_array'][$service_id]) || isset($vendor_comm_adjustment['gst_asset_vendor_comm_adjustment_array'][$service_id]) || isset($vendor_settle['vendor_settle_array'][$service_id]) || isset($vendor_incentive['vendor_incentive_array'][$service_id])){
                            
                            $service_income_array[$service_id][$date] = $ret_service_charge['ret_service_charge_array'][$service_id][$date] + $ret_service_charge_dmt['ret_service_charge_array'][$service_id][$date] + $rental_charge['rental_charge_array'][$service_id][$date] + $kit_charge['kit_charge_array'][$service_id][$date] + $vendor_commision_utility['vendor_commision_array'][$service_id][$date] + $vendor_commision_other['vendor_commision_array'][$service_id][$date] + $vendor_comm_adjustment['vendor_comm_adjustment_array'][$service_id][$date] + $vendor_settle['vendor_settle_array'][$service_id][$date] + $vendor_incentive['vendor_incentive_array'][$service_id][$date];

                        }

                        $gst_liability_array[$date] += $ret_service_charge['gst_liability_ret_service_charge_array'][$service_id][$date] + $ret_service_charge_dmt['gst_liability_ret_service_charge_array'][$service_id][$date] + $rental_charge['gst_liability_rental_array'][$service_id][$date] + $kit_charge['gst_liability_kit_array'][$service_id][$date];

                        $gst_vendor_liability_array[$date] += $vendor_commision_utility['gst_liability_vendor_commision_array'][$service_id][$date] + $vendor_commision_other['gst_liability_vendor_commision_array'][$service_id][$date] + $vendor_settle['gst_liability_vendor_settle_array'][$service_id][$date] + $vendor_incentive['gst_liability_vendor_incentive_array'][$service_id][$date] + $vendor_comm_adjustment['gst_liability'][$service_id][$date];

                        $gst_asset_array[$date] += $vendor_service_charge['gst_asset_vendor_sc_array'][$service_id][$date] + $vendor_service_charge_dmt['gst_asset_vendor_sc_array'][$service_id][$date] + $vendor_kit_payment['gst_asset_vendor_kit_array'][$service_id][$date];
                    }

                    $net_gst_array[$date] = ($gst_liability_array[$date] + $gst_vendor_liability_array[$date]) - $gst_asset_array[$date];

                    if(isset($vendor_service_charge['vendor_service_charge_array'][$service_id]) || isset($vendor_service_charge_dmt['vendor_service_charge_array'][$service_id][$date]) || isset($vendor_kit_payment['vendor_kit_payment_array'][$service_id]) || isset($loss[$service_id]) || isset($ret_commision[$service_id]) || isset($ret_incentive[$service_id]) || isset($dist_incentive[$service_id]) || isset($dist_commision[$service_id])){

                        //echo $service_id."***".$date."***".$vendor_service_charge['vendor_service_charge_array'][$service_id][$date]."===".$vendor_kit_payment['vendor_kit_payment_array'][$service_id][$date]."===".$loss[$service_id][$date]."====".$ret_commision[$service_id][$date]."====".$ret_incentive[$service_id][$date]."===".$dist_incentive[$service_id][$date]."===".$dist_commision[$service_id][$date]."===".$adjustment[$date]."===<br>"; 

                        $service_expense_array[$service_id][$date] += $vendor_service_charge['vendor_service_charge_array'][$service_id][$date] + $vendor_service_charge_dmt['vendor_service_charge_array'][$service_id][$date] + $vendor_kit_payment['vendor_kit_payment_array'][$service_id][$date] + $loss[$service_id][$date] + $ret_commision[$service_id][$date] + $ret_incentive[$service_id][$date] + $dist_incentive[$service_id][$date] + $dist_commision[$service_id][$date];
                        
                        if($service_id == RECHARGE){
                            $service_expense_array[$service_id][$date] += $adjustment[$date];
                        }
                        
                    }

                    $service_pnl_array[$service_id][$date] = $service_income_array[$service_id][$date] - $service_expense_array[$service_id][$date];
                    
                }
            }
            
            /*echo "<pre>"; 
            //print_r($gross_sale_array);
            print_r($service_income_array);
            //print_r($service_expense_array);
            //print_r($service_pnl_array);
            exit;*/
            

            $this->set('all_services',$all_services);
            $this->set('gross_sale_array',$gross_sale_array);
            $this->set('service_income_array',$service_income_array);
            $this->set('service_expense_array',$service_expense_array);
            $this->set('service_pnl_array',$service_pnl_array);

            $this->set('gst_liability_array',$gst_liability_array);
            $this->set('gst_vendor_liability_array',$gst_vendor_liability_array);
            $this->set('gst_asset_array',$gst_asset_array);
            $this->set('net_gst_array',$net_gst_array);


            /**********************Get Expenses***********************************/
            $accounting_categories_expense = $this->Finance->accounting_categories_expense($from_date,$to_date);
            for($i=0;$i<$no_of_days;$i++){
                $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));
                if(!isset($accounting_categories_expense['total_expense'][$date])){
                    $accounting_categories_expense['total_expense'][$date] = 0;
                }
            } 

            $this->set('accounting_categories_expense',$accounting_categories_expense);
           /*echo "<pre>";
            print_r($accounting_categories_expense);exit;*/

        }else{
            $this->Session->setFlash($msg);
        }
    }

    function pnl() {


            $flag = true;

            $params = $this->params['form'];
            

            /*Set default date as well as form date*/
            $from_date = $params['from_date']!=""?date('Y-m-d',strtotime($params['from_date'])):date('Y-m-d',strtotime('-1 week'));
            $to_date = $params['to_date']!=""?date('Y-m-d',strtotime($params['to_date'])):date('Y-m-d',strtotime('-1 day'));

            $date1=date_create($from_date);
            $date2=date_create($to_date);

            $diff=date_diff($date1,$date2);

            $no_of_days = $diff->format("%a")+1;

            if($this->RequestHandler->isPost())
            {  
                if($no_of_days > 31){
                    $flag = false;
                    $msg = "<b>Error</b> :  Date range cannot exceed 31 days";                    
                }

                $service = $params['service'];
                if($service == ""){
                    $flag = false;
                    $msg = "<b>Error</b> :  Please select service";
                }

                $child_service_array = $this->Shop->getServiceByParentId($service);

                $child_services = implode(',', $child_service_array);

                if($flag){

                    $utility_array = array('4');

                    if(in_array($service, $utility_array)){
                        $table = "api_vendors_sale_data";
                        $column = "sale";
                    }else{
                        $table = "vendor_recon";
                        $column = "sale - refund";
                        $join = " JOIN 
                            product_vendors pv ON vr.vendor_id = pv.id ";
                    }


                    if($service == RECHARGE){

                        /************************Only for Recharge********************************/

                        /********************Modem**********************/

                        /*Modem Closing*/
                        $modem_closing_array = $this->Finance->modem_closing($from_date,$to_date);
                        if(!empty($modem_closing_array)){
                            $modem_closing_exist = true;
                        }
                        /*End Modem Closing*/

                        /*Modem Sale*/
                        $total_modem_sale_array = $this->Finance->total_modem_sale($from_date,$to_date);
                        if(!empty($total_modem_sale_array)){
                            $total_modem_sale_exist = true;
                        }
                        /*End Modem Sale*/

                        /*Modem Invested*/
                        $total_modem_invested_array = $this->Finance->total_modem_invested($from_date,$to_date);
                        if(!empty($total_modem_invested_array)){
                            $total_modem_invested_exist = true;
                        }
                        /*End Modem Invested*/

                        /*Modem Earning*/
                        $modem_earning_array = $this->Finance->modem_earning($from_date,$to_date);
                        if(!empty($modem_earning_array)){
                            $modem_earning_exist = true;
                        }
                        
                        /*End Modem Earning*/

                         /*Modem GST Asset*/
                        $modem_gst_asset_array = $this->Finance->modem_gst_asset($from_date,$to_date);
                        if(!empty($modem_gst_asset_array)){
                             $modem_gst_asset_exist = true;
                        }
                        
                        /*Modem GST Asset*/

                        /********************API P2P**********************/

                        /*API Closing*/
                        $api_closing_array = $this->Finance->api_closing($from_date,$to_date);
                        if(!empty($api_closing_array)){
                            $api_closing_exist = true;
                        }
                        /*End API Closing*/

                        /*API P2P Sale*/
                        $total_api_p2p_sale_array = $this->Finance->total_api_p2p_sale($from_date,$to_date,$child_services);
                        if(!empty($total_api_p2p_sale_array)){
                            $total_api_p2p_sale_exist = true;
                        }
                        /*End API P2P Sale*/

                        /*API P2P Invested*/
                        $total_api_p2p_invested_array = $this->Finance->total_api_p2p_invested($from_date,$to_date,$child_services);
                        if(!empty($total_api_p2p_invested_array)){
                            $total_api_p2p_invested_exist = true;
                        }
                        /*End API P2P Invested*/

                        /*API P2P Earning*/
                        $api_p2p_earning_array = $this->Finance->api_p2p_earning($from_date,$to_date,$child_services);
                        if(!empty($api_p2p_earning_array)){
                            $api_p2p_earning_exist = true;
                        }
                        /*End API P2P Earning*/

                        /*API P2P GST Asset*/
                        $api_p2p_gst_asset_array = $this->Finance->api_p2p_gst_asset($from_date,$to_date,$child_services);
                        if(!empty($api_p2p_gst_asset_array)){
                            $api_p2p_gst_asset_exist = true;
                        }
                        
                        /*End API P2P GST Asset*/

                        /********************API P2A**********************/

                        /*API P2A Sale*/
                        $total_api_p2a_sale_array = $this->Finance->total_api_p2a_sale($from_date,$to_date,$child_services);
                        if(!empty($total_api_p2a_sale_array)){                            
                            $total_api_p2a_sale_exist = true;
                        }
                        
                        /*End API P2A Sale*/

                        /*API P2A Invested*/
                        $total_api_p2a_invested_array = $this->Finance->total_api_p2a_invested($from_date,$to_date,$child_services);
                        if(!empty($total_api_p2a_invested_array)){
                            $total_api_p2a_invested_exist = true;
                        }
                        /*End API P2A Invested*/
                        

                        /*API P2A Earning*/
                        $api_p2a_earning_array = $this->Finance->api_p2a_earning($from_date,$to_date,$child_services);
                        if(!empty($api_p2a_earning_array)){                            
                            $api_p2a_earning_exist = true;
                        }
                        
                        /*End API P2A Earning*/

                        /*API P2A GST Asset*/
                        $api_p2a_gst_asset_array = $this->Finance->api_p2a_gst_asset($from_date,$to_date,$child_services);
                        if(!empty($api_p2a_gst_asset_array)){                            
                            $api_p2a_gst_asset_exist = true;
                        }
                        
                        /*End API P2A GST Asset*/

                        /*Adjustment*/
                        $adjustment_array = $this->Finance->adjustment($from_date,$to_date);
                        if(!empty($adjustment_array)){                            
                            $adjustment_exist = true;
                        }
                        
                        /*End Adjustment*/



                        /***************************End Recharge**********************************/
                    }else{
                        /*Total Sale*/
                        $total_sale_array = $this->Finance->total_sale($from_date,$to_date,$child_services,$table,$column,$join);
                        if(!empty($total_sale_array)){                            
                            $total_sale_exist = true;
                        }
                        
                        /*End Total Sale*/

                         /********************Income************************/

                         /*Retailer Service Charge*/
                        $ret_service_charge = $this->Finance->ret_service_charge($from_date,$to_date,$child_services);
                        if(!empty($ret_service_charge['ret_service_charge_array'])){                    
                            $ret_service_charge_exist = true;
                            $gst_liability_exist = true;
                        }
                        $ret_service_charge_array = $ret_service_charge['ret_service_charge_array'];
                        $gst_liability_ret_service_charge_array = $ret_service_charge['gst_liability_ret_service_charge_array'];
                        
                        /*End Retailer Service Charge*/

                        /*Kit Charge*/

                        $kit_charge = $this->Finance->kit_charge($from_date,$to_date,$child_services);
                        if(!empty($kit_charge['kit_charge_array'])){                    
                            $kit_charge_exist = true;
                            $gst_liability_exist = true;
                        }
                        $kit_charge_array = $kit_charge['kit_charge_array'];
                        $gst_liability_kit_array = $kit_charge['gst_liability_kit_array'];

                        /*End Kit Charge*/

                        /*Vendor Commision*/

                        $vendor_commision = $this->Finance->vendor_commision($from_date,$to_date,$child_services,$table,$join);
                        if(!empty($vendor_commision['vendor_commision_array'])){                    
                            $vendor_commision_exist = true;
                        }
                        $vendor_commision_array = $vendor_commision['vendor_commision_array'];
                        $gst_liability_vendor_commision_array = $vendor_commision['gst_liability_vendor_commision_array'];

                        /*Vendor Commision Adjustment*/

                       /* $vendor_comm_adjustment = $this->Finance->vendor_comm_adjustment($from_date,$to_date,$child_services);
                        if(!empty($vendor_comm_adjustment['vendor_comm_adjustment_array'])){          
                            $vendor_comm_adjustment_exist = true;
                        }
                        $vendor_comm_adjustment_array = $vendor_comm_adjustment['vendor_comm_adjustment_array'];
                        $gst_liability_vendor_comm_adjustment_array = $vendor_comm_adjustment['gst_liability'];
*/
                        

                        /*Vendor Settlement*/

                        $vendor_settle = $this->Finance->vendor_set_up_fee($from_date,$to_date,$child_services);
                        if(!empty($vendor_settle['vendor_settle_array'])){                    
                            $vendor_settle_exist = true;
                        }
                        $vendor_settle_array = $vendor_settle['vendor_settle_array'];
                        $gst_liability_vendor_settle_array = $vendor_settle['gst_liability_vendor_settle_array'];

                        

                        /*Vendor Incentive*/

                        /*$vendor_incentive = $this->Finance->vendor_incentive($from_date,$to_date,$child_services);
                        if(!empty($vendor_incentive['vendor_incentive_array'])){                    
                            $vendor_incentive_exist = true;
                        }
                        $vendor_incentive_array = $vendor_incentive['vendor_incentive_array'];
                        $gst_liability_vendor_incentive_array = $vendor_incentive['gst_liability_vendor_incentive_array'];*/

                        
                        /*End Vendor Commision*/

                        /********************End Income************************/

                        /********************Expense************************/

                        /*Vendor Service Charge*/

                        $vendor_service_charge = $this->Finance->vendor_service_charge($from_date,$to_date,$child_services);
                        if(!empty($vendor_service_charge['vendor_service_charge_array'])){                    
                            $vendor_service_charge_exist = true;
                            $gst_asset_exist = true;
                        }
                        $vendor_service_charge_array = $vendor_service_charge['vendor_service_charge_array'];
                        $gst_asset_vendor_sc_array = $vendor_service_charge['gst_asset_vendor_sc_array'];

                        
                        /*End Vendor Service Charge*/

                        /*Vendor Kit Payment*/

                        $vendor_kit_payment = $this->Finance->vendor_kit_payment($from_date,$to_date,$child_services);
                        if(!empty($vendor_kit_payment['vendor_kit_payment_array'])){                    
                            $vendor_kit_payment_exist = true;
                        }
                        $vendor_kit_payment_array = $vendor_kit_payment['vendor_kit_payment_array'];
                        $gst_asset_vendor_kit_array = $vendor_kit_payment['gst_asset_vendor_kit_array'];

                        
                        /*End Vendor Kit Charge*/

                        /*Loss Data*/
                        $loss_array = $this->Finance->loss($from_date,$to_date,$child_services);
                        if(!empty($loss_array)){                            
                            $loss_exist = true;
                        }
                        
                        /*End Vendor Service Charge*/

                        /********************End Expense************************/
                    }

                    /********************Common Income************************/

                    /*Rental Charge*/

                        $rental_charge = $this->Finance->rental_charge($from_date,$to_date,$child_services);
                        if(!empty($rental_charge['rental_charge_array'])){                    
                            $rental_charge_exist = true;
                            $gst_liability_exist = true;
                        }
                        $rental_charge_array = $rental_charge['rental_charge_array'];
                        $gst_liability_rental_array = $rental_charge['gst_liability_rental_array'];

                        /*End Rental Charge*/

                    
                     /********************End Common Income************************/
                   

                    /********************Common Expense************************/

                    /*Retailer Commision*/
                    $ret_commision_array = $this->Finance->ret_commision($from_date,$to_date,$child_services);
                        if(!empty($ret_commision_array)){                            
                            $ret_commision_exist = true;
                        }
                    /*End Retailer Commision*/

                    /*Retailer Incentive*/
                    $ret_incentive_array = $this->Finance->ret_incentive($from_date,$to_date,$child_services);
                        if(!empty($ret_incentive_array)){                            
                            $ret_incentive_exist = true;
                        }
                    
                    /*End Retailer Incentive*/

                    /*Distributor Incentive*/
                    $dist_incentive_array = $this->Finance->dist_incentive($from_date,$to_date,$child_services);
                        if(!empty($dist_incentive_array)){                            
                            $dist_incentive_exist = true;
                        }

                    
                    /*End Distributor Incentive*/

                    /*Distributor Commision*/
                    $dist_commision_array = $this->Finance->dist_commision($from_date,$to_date,$child_services);
                        if(!empty($dist_commision_array)){                            
                            $dist_commision_exist = true;
                        }

                    
                    /*End Distributor Incentive*/

                    
                     /********************End Common Expense************************/


                    /*******************Get all date wise data************************/

                    if($service == RECHARGE){
                        $modem_closing_data = array();  
                        $api_closing_data = array();  

                        $total_modem_sale_data = array();                        
                        $total_api_p2p_sale_data = array ();
                        $total_api_p2a_sale_data = array ();                        

                        $total_modem_invested_data = array();
                        $total_api_p2p_invested_data = array ();
                        $total_api_p2a_invested_data = array ();

                        $modem_earning_data = array ();
                        $api_p2p_earning_data = array ();
                        $api_p2a_earning_data = array ();

                        $adjustment_data = array ();

                        $total_income_data = array();

                        $modem_gst_asset_data = array ();
                        $api_p2p_gst_asset_data = array ();
                        $api_p2a_gst_asset_data = array ();
                        
                    }else{                        

                        $ret_service_charge_data = array();
                        
                        $kit_charge_data = array();
                        $vendor_commision_data = array();
                        $vendor_comm_adjustment_data = array();
                        $vendor_settle_data = array();
                        $vendor_incentive_data = array();

                        $vendor_service_charge_data = array();
                        $vendor_kit_payment_data = array();
                        $loss_data = array();

                        $gst_liability_ret_sc_data = array();                    
                                            
                        $gst_liability_kit_data = array();                    
                        $gst_liability_vendor_commision_data = array(); 
                        $gst_liability_vendor_settle_data = array();
                        $gst_liability_vendor_incentive_data = array();

                        $total_vendor_gst_liability = array();

                        $gst_asset_vendor_sc_data = array();                    
                        $gst_asset_vendor_kit_data = array(); 
                    }

                    $total_sale_data = array();

                    $rental_charge_data = array();
                    $gst_liability_rental_data = array();

                    $ret_commision_data = array();
                    $ret_incentive_data = array();
                    $dist_incentive_data = array();
                    $dist_commision_data = array();
                    

                    $profit_loss_data = array();

                    $total_gst_liability = array(); 
                     
                    $total_gst_asset = array();                    


                    for($i=0;$i<$no_of_days;$i++){
                        $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));

                        if($service == RECHARGE){

                            /*Modem Closing*/
                            $modem_closing_data[$i]['date'] = $date;
                            if(isset($modem_closing_array[$date])){
                                $modem_closing_data[$i]['value'] =  $modem_closing_array[$date];
                            }else{
                                $modem_closing_data[$i]['value'] = 0;
                            }

                            /*API Closing*/
                            $api_closing_data[$i]['date'] = $date;
                            if(isset($api_closing_array[$date])){
                                $api_closing_data[$i]['value'] =  $api_closing_array[$date];
                            }else{
                                $api_closing_data[$i]['value'] = 0;
                            }

                            /*Total Modem Sale*/
                            $total_modem_sale_data[$i]['date'] = $date;
                            if(isset($total_modem_sale_array[$date])){
                                $total_modem_sale_data[$i]['value'] =  $total_modem_sale_array[$date];
                            }else{
                                $total_modem_sale_data[$i]['value'] = 0;
                            }

                            /*Total API P2P sale*/
                            $total_api_p2p_sale_data[$i]['date'] = $date;
                            if(isset($total_api_p2p_sale_array[$date])){
                                $total_api_p2p_sale_data[$i]['value'] = $total_api_p2p_sale_array[$date];
                            }else{
                                $total_api_p2p_sale_data[$i]['value'] = 0;
                            }

                            /*Total API P2A sale*/
                            $total_api_p2a_sale_data[$i]['date'] = $date;
                            if(isset($total_api_p2a_sale_array[$date])){
                                $total_api_p2a_sale_data[$i]['value'] = $total_api_p2a_sale_array[$date];
                            }else{
                                $total_api_p2a_sale_data[$i]['value'] = 0;
                            }

                            $total_sale_data[$i]['date'] = $date;
                            $total_sale_data[$i]['value'] = $total_modem_sale_data[$i]['value'] + $total_api_p2p_sale_data[$i]['value'] + $total_api_p2a_sale_data[$i]['value'];

                        }else{
                            /*Total Sale*/
                            $total_sale_data[$i]['date'] = $date;
                            if(isset($total_sale_array[$service][$date])){
                                $total_sale_data[$i]['value'] = $total_sale_array[$service][$date];
                            }else{
                                $total_sale_data[$i]['value'] = 0;
                            }
                        }

                        /*Retailer Commision*/
                        $ret_commision_data[$i]['date'] = $date;
                        if(isset($ret_commision_array[$service][$date])){
                            $ret_commision_data[$i]['value'] = $ret_commision_array[$service][$date];
                        }else{
                            $ret_commision_data[$i]['value'] = 0;
                        }

                        /*Retailer Commision in %*/
                        $ret_commision_in_percentage_data[$i]['date'] = $date;
                        $ret_commision_in_percentage_data[$i]['value'] = ROUND(($ret_commision_data[$i]['value'] / $total_sale_data[$i]['value']) * 100,2);

                        /*Retailer Incentive*/
                        $ret_incentive_data[$i]['date'] = $date;
                        if(isset($ret_incentive_array[$service][$date])){
                            $ret_incentive_data[$i]['value'] = $ret_incentive_array[$service][$date];
                        }else{
                            $ret_incentive_data[$i]['value'] = 0;
                        }

                        /*Retailer Incentive in %*/
                        $ret_incentive_in_percentage_data[$i]['date'] = $date;
                        $ret_incentive_in_percentage_data[$i]['value'] = ROUND(($ret_incentive_data[$i]['value'] / $total_sale_data[$i]['value']) * 100,2);

                        /*Distributor Incentive*/
                        $dist_incentive_data[$i]['date'] = $date;
                        if(isset($dist_incentive_array[$service][$date])){
                            $dist_incentive_data[$i]['value'] = $dist_incentive_array[$service][$date];
                        }else{
                            $dist_incentive_data[$i]['value'] = 0;
                        }

                        /*Distributor Incentive in %*/
                        $dist_incentive_in_percentage_data[$i]['date'] = $date;
                        $dist_incentive_in_percentage_data[$i]['value'] = ROUND(($dist_incentive_data[$i]['value'] / $total_sale_data[$i]['value']) * 100,2);

                        /*Distributor Commision*/
                        $dist_commision_data[$i]['date'] = $date;
                        if(isset($dist_commision_array[$service][$date])){
                            $dist_commision_data[$i]['value'] = $dist_commision_array[$service][$date];
                        }else{
                            $dist_commision_data[$i]['value'] = 0;
                        }

                        /*Distributor Commision in %*/
                        $dist_commision_in_percentage_data[$i]['date'] = $date;
                        $dist_commision_in_percentage_data[$i]['value'] = ROUND(($dist_commision_data[$i]['value'] / $total_sale_data[$i]['value']) * 100,2);

                        /*Rental Charge*/
                        $rental_charge_data[$i]['date'] = $date;
                        if(isset($rental_charge_array[$service][$date])){
                            $rental_charge_data[$i]['value'] = $rental_charge_array[$service][$date];
                        }else{
                            $rental_charge_data[$i]['value'] = 0;
                        }
                        $rental_charge_in_percentage_data[$i]['date'] = $date;
                        $rental_charge_in_percentage_data[$i]['value'] = ROUND(($rental_charge_data[$i]['value'] / $total_sale_data[$i]['value']) * 100,2);

                        /*GST Liability Rental Charge Data*/
                        $gst_liability_rental_data[$i]['date'] = $date;
                        if(isset($gst_liability_rental_array[$service][$date])){
                            $gst_liability_rental_data[$i]['value'] = $gst_liability_rental_array[$service][$date];
                        }else{
                            $gst_liability_rental_data[$i]['value'] = 0;
                        }


                        if($service == RECHARGE){

                            /*Total Modem Invested*/
                            $total_modem_invested_data[$i]['date'] = $date;
                            if(isset($total_modem_invested_array[$date])){
                                $total_modem_invested_data[$i]['value'] = $total_modem_invested_array[$date];
                            }else{
                                $total_modem_invested_data[$i]['value'] = 0;
                            }

                            /*Total API P2P invested*/
                            $total_api_p2p_invested_data[$i]['date'] = $date;
                            if(isset($total_api_p2p_invested_array[$date])){
                                $total_api_p2p_invested_data[$i]['value'] = $total_api_p2p_invested_array[$date];
                            }else{
                                $total_api_p2p_invested_data[$i]['value'] = 0;
                            }                            

                            /*Total API P2A invested*/
                            $total_api_p2a_invested_data[$i]['date'] = $date;
                            if(isset($total_api_p2a_invested_array[$date])){
                                $total_api_p2a_invested_data[$i]['value'] = $total_api_p2a_invested_array[$date];
                            }else{
                                $total_api_p2a_invested_data[$i]['value'] = 0;
                            }

                            /*Total Modem Earning*/
                            $modem_earning_data[$i]['date'] = $date;
                            if(isset($modem_earning_array[$date])){
                                $modem_earning_data[$i]['value'] = $modem_earning_array[$date];
                            }else{
                                $modem_earning_data[$i]['value'] = 0;
                            }

                            /*Total Modem Earning in %*/
                            $modem_earning_in_percentage_data[$i]['date'] = $date;
                            $modem_earning_in_percentage_data[$i]['value'] = ROUND(($modem_earning_data[$i]['value'] / $total_modem_sale_data[$i]['value']) * 100,2);
                            

                            /*Total API P2P Earning*/
                            $api_p2p_earning_data[$i]['date'] = $date;
                            if(isset($api_p2p_earning_array[$date])){
                                $api_p2p_earning_data[$i]['value'] = $api_p2p_earning_array[$date];
                            }else{
                                $api_p2p_earning_data[$i]['value'] = 0;
                            }

                            /*Total API P2P Earning in %*/
                            $api_p2p_earning_in_percentage_data[$i]['date'] = $date;
                            $api_p2p_earning_in_percentage_data[$i]['value'] = ROUND(($api_p2p_earning_data[$i]['value'] / $total_api_p2p_sale_data[$i]['value']) * 100,2);

                            /*Total API P2A Earning*/
                            $api_p2a_earning_data[$i]['date'] = $date;
                            if(isset($api_p2a_earning_array[$date])){
                                $api_p2a_earning_data[$i]['value'] = $api_p2a_earning_array[$date];
                            }else{
                                $api_p2a_earning_data[$i]['value'] = 0;
                            }

                            /*Total API P2A Earning in %*/
                            $api_p2a_earning_in_percentage_data[$i]['date'] = $date;
                            $api_p2a_earning_in_percentage_data[$i]['value'] = ROUND(($api_p2a_earning_data[$i]['value'] / $total_api_p2a_sale_data[$i]['value']) * 100,2);

                            /*Modem GST Asset*/
                            $modem_gst_asset_data[$i]['date'] = $date;
                            if(isset($modem_gst_asset_array[$date])){
                                $modem_gst_asset_data[$i]['value'] = $modem_gst_asset_array[$date];
                            }else{
                                $modem_gst_asset_data[$i]['value'] = 0;
                            }

                            /*API P2P GST Asset*/
                            $api_p2p_gst_asset_data[$i]['date'] = $date;
                            if(isset($api_p2p_gst_asset_array[$date])){
                                $api_p2p_gst_asset_data[$i]['value'] = $api_p2p_gst_asset_array[$date];
                            }else{
                                $api_p2p_gst_asset_data[$i]['value'] = 0;
                            }

                            /*API P2A GST Asset*/
                            $api_p2a_gst_asset_data[$i]['date'] = $date;
                            if(isset($api_p2a_gst_asset_array[$date])){
                                $api_p2a_gst_asset_data[$i]['value'] = $api_p2a_gst_asset_array[$date];
                            }else{
                                $api_p2a_gst_asset_data[$i]['value'] = 0;
                            }

                            /*Income*/
                            $income = $modem_earning_data[$i]['value'] + $api_p2p_earning_data[$i]['value'] + $api_p2a_earning_data[$i]['value'] + $rental_charge_data[$i]['value'];

                            $total_income_exist = true;
                            $total_income_data[$i]['date'] = $date;
                            $total_income_data[$i]['value'] = $income;

                            $total_income_in_percentage_data[$i]['date'] = $date;
                            $total_income_in_percentage_data[$i]['value'] = ROUND(($income/$total_sale_data[$i]['value']) * 100,2);

                            $total_income = $income;

                            /*Adjustment*/
                            $adjustment_data[$i]['date'] = $date;
                            if(isset($adjustment_array[$date])){
                                $adjustment_data[$i]['value'] = $adjustment_array[$date];
                            }else{
                                $adjustment_data[$i]['value'] = 0;
                            }

                            /*GST Liability*/
                            $total_gst_liability[$i]['date'] = $date;
                            $total_gst_liability[$i]['value'] = ROUND(((($total_sale_data[$i]['value'] - $ret_commision_data[$i]['value'] - $ret_incentive_data[$i]['value']) / 1.18 ) * 0.18) + $gst_liability_rental_data[$i]['value']);

                            /*GST Asset*/
                            $total_gst_asset[$i]['date'] = $date;
                            $total_gst_asset[$i]['value'] = $modem_gst_asset_data[$i]['value'] + $api_p2p_gst_asset_data[$i]['value'] + $api_p2a_gst_asset_data[$i]['value'];

                            $vendor_service_charge_data[$i]['value'] = 0;
                            $vendor_kit_payment_data[$i]['value'] = 0;
                            $loss_data[$i]['value'] = 0;
                        }else{
                            

                            /*Retailer Service Charge*/
                            $ret_service_charge_data[$i]['date'] = $date;
                            if(isset($ret_service_charge_array[$service][$date])){
                                $ret_service_charge_data[$i]['value'] = $ret_service_charge_array[$service][$date];
                            }else{
                                $ret_service_charge_data[$i]['value'] = 0;
                            }

                            $ret_service_charge_in_percentage_data[$i]['date'] = $date;
                            $ret_service_charge_in_percentage_data[$i]['value'] = ROUND(($ret_service_charge_data[$i]['value']/$total_sale_data[$i]['value']) * 100,2);

                            /*Kit Charge*/
                            $kit_charge_data[$i]['date'] = $date;
                            if(isset($kit_charge_array[$service][$date])){
                                $kit_charge_data[$i]['value'] = $kit_charge_array[$service][$date];
                            }else{
                                $kit_charge_data[$i]['value'] = 0;
                            }

                            /*$kit_charge_in_percentage_data[$i]['date'] = $date;
                            $kit_charge_in_percentage_data[$i]['value'] = ROUND(($kit_charge_data[$i]['value']/$total_sale_data[$i]['value']) * 100,2);*/

                            /*Vendor Commision*/
                            $vendor_commision_data[$i]['date'] = $date;
                            if(isset($vendor_commision_array[$service][$date])){
                                $vendor_commision_data[$i]['value'] = $vendor_commision_array[$service][$date];
                            }else{
                                $vendor_commision_data[$i]['value'] = 0;
                            }

                            $vendor_commision_in_percentage_data[$i]['date'] = $date;
                            $vendor_commision_in_percentage_data[$i]['value'] = ROUND(($vendor_commision_data[$i]['value']/$total_sale_data[$i]['value']) * 100,2);

                            /*Vendor Commision Adjustment*/
                            $vendor_comm_adjustment_data[$i]['date'] = $date;
                            if(isset($vendor_comm_adjustment_array[$service][$date])){
                                $vendor_comm_adjustment_data[$i]['value'] = $vendor_comm_adjustment_array[$service][$date];
                            }else{
                                $vendor_comm_adjustment_data[$i]['value'] = 0;
                            }

                            /*Vendor Settlement*/
                            $vendor_settle_data[$i]['date'] = $date;
                            if(isset($vendor_settle_array[$service][$date])){
                                $vendor_settle_data[$i]['value'] = $vendor_settle_array[$service][$date];
                            }else{
                                $vendor_settle_data[$i]['value'] = 0;
                            }

                            /*Vendor Incentive*/
                            /*$vendor_incentive_data[$i]['date'] = $date;
                            if(isset($vendor_incentive_array[$service][$date])){
                                $vendor_incentive_data[$i]['value'] = $vendor_incentive_array[$service][$date];
                            }else{
                                $vendor_incentive_data[$i]['value'] = 0;
                            }*/

                            $total_income = $ret_service_charge_data[$i]['value'] + $rental_charge_data[$i]['value'] + $kit_charge_data[$i]['value'] + $vendor_commision_data[$i]['value'] + $vendor_comm_adjustment_data[$i]['value']  + $vendor_settle_data[$i]['value'];

                            /*Profit Loss Data */
                            $total_income_exist = true;
                            $total_income_data[$i]['date'] = $date;
                            $total_income_data[$i]['value'] = $total_income;

                            $total_income_in_percentage_data[$i]['date'] = $date;
                            $total_income_in_percentage_data[$i]['value'] = ROUND(($total_income/$total_sale_data[$i]['value']) * 100,2);


                            /*Vendor Service Charge*/
                            $vendor_service_charge_data[$i]['date'] = $date;
                            if(isset($vendor_service_charge_array[$service][$date])){
                                $vendor_service_charge_data[$i]['value'] = $vendor_service_charge_array[$service][$date];
                            }else{
                                $vendor_service_charge_data[$i]['value'] = 0;
                            }

                            $vendor_service_charge_in_percentage_data[$i]['date'] = $date;
                            $vendor_service_charge_in_percentage_data[$i]['value'] = ROUND(($vendor_service_charge_data[$i]['value']/$total_sale_data[$i]['value']) * 100,2);

                            /*Vendor Kit Payment*/
                            $vendor_kit_payment_data[$i]['date'] = $date;
                            if(isset($vendor_kit_payment_array[$service][$date])){
                                $vendor_kit_payment_data[$i]['value'] = $vendor_kit_payment_array[$service][$date];
                            }else{
                                $vendor_kit_payment_data[$i]['value'] = 0;
                            }

                            /*Loss*/
                            $loss_data[$i]['date'] = $date;
                            if(isset($loss_array[$service][$date])){
                                $loss_data[$i]['value'] = $loss_array[$service][$date];
                            }else{
                                $loss_data[$i]['value'] = 0;
                            }

                            /*GST Liability Retailer Service Charge Data*/
                            $gst_liability_ret_sc_data[$i]['date'] = $date;
                            if(isset($gst_liability_ret_service_charge_array[$service][$date])){
                                $gst_liability_ret_sc_data[$i]['value'] = $gst_liability_ret_service_charge_array[$service][$date];
                            }else{
                                $gst_liability_ret_sc_data[$i]['value'] = 0;
                            }

                            /*GST Liability Kit Charge Data*/
                            $gst_liability_kit_data[$i]['date'] = $date;
                            if(isset($gst_liability_kit_array[$service][$date])){
                                $gst_liability_kit_data[$i]['value'] = $gst_liability_kit_array[$service][$date];
                            }else{
                                $gst_liability_kit_data[$i]['value'] = 0;
                            }

                            /*GST Liability Vendor Commision Data*/
                            $gst_liability_vendor_commision_data[$i]['date'] = $date;
                            if(isset($gst_liability_vendor_commision_array[$service][$date])){
                                $gst_liability_vendor_commision_data[$i]['value'] = $gst_liability_vendor_commision_array[$service][$date];
                            }else{
                                $gst_liability_vendor_commision_data[$i]['value'] = 0;
                            }

                            /*GST Liability Vendor Incentive Data*/
                            /*$gst_liability_vendor_incentive_data[$i]['date'] = $date;
                            if(isset($gst_liability_vendor_incentive_array[$service][$date])){
                                $gst_liability_vendor_incentive_data[$i]['value'] = $gst_liability_vendor_incentive_array[$service][$date];
                            }else{
                                $gst_liability_vendor_incentive_data[$i]['value'] = 0;
                            }*/

                            /*GST Liability Vendor Settle Data*/
                            $gst_liability_vendor_settle_data[$i]['date'] = $date;
                            if(isset($gst_liability_vendor_settle_array[$service][$date])){
                                $gst_liability_vendor_settle_data[$i]['value'] = $gst_liability_vendor_settle_array[$service][$date];
                            }else{
                                $gst_liability_vendor_settle_data[$i]['value'] = 0;
                            }

                            $total_gst_liability[$i]['date'] = $date;
                            $total_gst_liability[$i]['value'] = $gst_liability_ret_sc_data[$i]['value'] + $gst_liability_rental_data[$i]['value'] + $gst_liability_kit_data[$i]['value'];

                            $total_vendor_gst_liability[$i]['date'] = $date;
                            $total_vendor_gst_liability[$i]['value'] = $gst_liability_vendor_commision_data[$i]['value'] + $gst_liability_vendor_settle_data[$i]['value'];

                            /*GST Asset Vendor Service Charge Data*/
                            $gst_asset_vendor_sc_data[$i]['date'] = $date;
                            if(isset($gst_asset_vendor_sc_array[$service][$date])){
                                $gst_asset_vendor_sc_data[$i]['value'] = $gst_asset_vendor_sc_array[$service][$date];
                            }else{
                                $gst_asset_vendor_sc_data[$i]['value'] = 0;
                            }

                            /*GST Asset Vendor Kit Charge Data*/
                            $gst_asset_vendor_kit_data[$i]['date'] = $date;
                            if(isset($gst_asset_vendor_kit_array[$service][$date])){
                                $gst_asset_vendor_kit_data[$i]['value'] = $gst_asset_vendor_kit_array[$service][$date];
                            }else{
                                $gst_asset_vendor_kit_data[$i]['value'] = 0;
                            }

                            $total_gst_asset[$i]['date'] = $date;
                            $total_gst_asset[$i]['value'] = $gst_asset_vendor_sc_data[$i]['value'] + $gst_asset_vendor_kit_data[$i]['value'];

                        }
                        

                        

                        /*Check expense exist*/
                        $total_expense = 0;
                        if(!empty($ret_commision_data[$i]['value']) || !empty($ret_incentive_data[$i]['value']) || !empty($dist_incentive_data[$i]['value']) || !empty($dist_commision_data[$i]['value']) || !empty($vendor_service_charge_data[$i]['value']) || !empty($vendor_kit_payment_data[$i]['value']) || !empty($loss_data[$i]['value']) || !empty($adjustment_data[$i]['value'])){

                            $total_expense = $ret_commision_data[$i]['value'] + $ret_incentive_data[$i]['value'] + $dist_incentive_data[$i]['value'] + $dist_commision_data[$i]['value'] + $vendor_service_charge_data[$i]['value'] + $vendor_kit_payment_data[$i]['value'] + $loss_data[$i]['value'] + $adjustment_data[$i]['value'];

                            
                        }

                        /*Total Expense Data */
                        $total_expense_exist = true;
                        $total_expense_data[$i]['date'] = $date;
                        $total_expense_data[$i]['value'] = $total_expense;

                        $total_expense_in_percentage_data[$i]['date'] = $date;
                        $total_expense_in_percentage_data[$i]['value'] = ROUND(($total_expense/$total_sale_data[$i]['value']) * 100,2);

                        


                        /*Profit Loss Data */
                        $profit_loss_data[$i]['date'] = $date;
                        $profit_loss_data[$i]['value'] = $total_income - $total_expense;

                        $profit_loss_in_percentage_data[$i]['date'] = $date;
                        $profit_loss_in_percentage_data[$i]['value'] = ROUND((($total_income - $total_expense)/$total_sale_data[$i]['value']) * 100,2);

                        /*Net GST Data */
                        $net_gst_exist = true;
                        $net_gst_data[$i]['date'] = $date;
                        $net_gst_data[$i]['value'] = ($total_gst_liability[$i]['value'] + $total_vendor_gst_liability[$i]['value']) - $total_gst_asset[$i]['value'];

                    }

                     /*Query data*/

                    if($service == RECHARGE){
                        $this->set('total_modem_sale_exist',$total_modem_sale_exist);
                        $this->set('total_modem_sale_data',$total_modem_sale_data);

                        $this->set('modem_closing_exist',$modem_closing_exist);
                        $this->set('modem_closing_data',$modem_closing_data);

                        $this->set('api_closing_exist',$api_closing_exist);
                        $this->set('api_closing_data',$api_closing_data);

                        $this->set('total_modem_invested_exist',$total_modem_invested_exist);
                        $this->set('total_modem_invested_data',$total_modem_invested_data);

                        $this->set('total_api_p2p_sale_exist',$total_api_p2p_sale_exist);
                        $this->set('total_api_p2p_sale_data',$total_api_p2p_sale_data);

                        $this->set('total_api_p2p_invested_exist',$total_api_p2p_invested_exist);
                        $this->set('total_api_p2p_invested_data',$total_api_p2p_invested_data);

                        $this->set('total_api_p2a_sale_exist',$total_api_p2a_sale_exist);
                        $this->set('total_api_p2a_sale_data',$total_api_p2a_sale_data);

                        $this->set('total_api_p2a_invested_exist',$total_api_p2a_invested_exist);
                        $this->set('total_api_p2a_invested_data',$total_api_p2a_invested_data);

                        $this->set('modem_earning_exist',$modem_earning_exist);
                        $this->set('modem_earning_data',$modem_earning_data);

                        $this->set('modem_earning_in_percentage_data',$modem_earning_in_percentage_data);
                        $this->set('api_p2p_earning_in_percentage_data',$api_p2p_earning_in_percentage_data);
                        $this->set('api_p2a_earning_in_percentage_data',$api_p2a_earning_in_percentage_data);

                        $this->set('api_p2p_earning_exist',$api_p2p_earning_exist);
                        $this->set('api_p2p_earning_data',$api_p2p_earning_data);

                        $this->set('api_p2a_earning_exist',$api_p2a_earning_exist);
                        $this->set('api_p2a_earning_data',$api_p2a_earning_data);

                        $this->set('adjustment_exist',$adjustment_exist);
                        $this->set('adjustment_data',$adjustment_data);

                        $this->set('modem_gst_asset_exist',$modem_gst_asset_exist);
                        $this->set('modem_gst_asset_data',$modem_gst_asset_data);

                        $this->set('api_p2p_gst_asset_exist',$api_p2p_gst_asset_exist);
                        $this->set('api_p2p_gst_asset_data',$api_p2p_gst_asset_data);

                        $this->set('api_p2a_gst_asset_exist',$api_p2a_gst_asset_exist);
                        $this->set('api_p2a_gst_asset_data',$api_p2a_gst_asset_data);
                    }else{
                        
                        $this->set('ret_service_charge_exist',$ret_service_charge_exist);
                        $this->set('ret_service_charge_data',$ret_service_charge_data);
                        $this->set('ret_service_charge_in_percentage_data',$ret_service_charge_in_percentage_data);

                        $this->set('kit_charge_exist',$kit_charge_exist);
                        $this->set('kit_charge_data',$kit_charge_data);
                        //$this->set('kit_charge_in_percentage_data',$kit_charge_in_percentage_data);

                        $this->set('vendor_commision_exist',$vendor_commision_exist);
                        $this->set('vendor_commision_data',$vendor_commision_data);
                        $this->set('vendor_commision_in_percentage_data',$vendor_commision_in_percentage_data);

                        $this->set('vendor_comm_adjustment_exist',$vendor_comm_adjustment_exist);
                        $this->set('vendor_comm_adjustment_data',$vendor_comm_adjustment_data);

                        $this->set('vendor_settle_exist',$vendor_settle_exist);
                        $this->set('vendor_settle_data',$vendor_settle_data);

                       /* $this->set('vendor_incentive_exist',$vendor_incentive_exist);
                        $this->set('vendor_incentive_data',$vendor_incentive_data);*/

                        $this->set('vendor_service_charge_exist',$vendor_service_charge_exist);
                        $this->set('vendor_service_charge_data',$vendor_service_charge_data);
                        $this->set('vendor_service_charge_in_percentage_data',$vendor_service_charge_in_percentage_data);

                        $this->set('vendor_kit_payment_exist',$vendor_kit_payment_exist);
                        $this->set('vendor_kit_payment_data',$vendor_kit_payment_data);

                        $this->set('loss_exist',$loss_exist);
                        $this->set('loss_data',$loss_data);

                        $this->set('total_vendor_gst_liability',$total_vendor_gst_liability);
                    }

                    $this->set('rental_charge_exist',$rental_charge_exist);
                    $this->set('rental_charge_data',$rental_charge_data);
                    $this->set('rental_charge_in_percentage_data',$rental_charge_in_percentage_data);
                    
                    $this->set('total_sale_exist',$total_sale_exist);
                    $this->set('total_sale_data',$total_sale_data);

                    $this->set('ret_commision_exist',$ret_commision_exist);
                    $this->set('ret_commision_data',$ret_commision_data);

                    $this->set('ret_incentive_exist',$ret_incentive_exist);
                    $this->set('ret_incentive_data',$ret_incentive_data);

                    $this->set('dist_incentive_exist',$dist_incentive_exist);
                    $this->set('dist_incentive_data',$dist_incentive_data);

                    $this->set('dist_commision_exist',$dist_commision_exist);
                    $this->set('dist_commision_data',$dist_commision_data);

                    $this->set('ret_commision_in_percentage_data',$ret_commision_in_percentage_data);
                    $this->set('ret_incentive_in_percentage_data',$ret_incentive_in_percentage_data);
                    $this->set('dist_incentive_in_percentage_data',$dist_incentive_in_percentage_data);
                    $this->set('dist_commision_in_percentage_data',$dist_commision_in_percentage_data);

                    $this->set('total_income_exist',$total_income_exist);
                    $this->set('total_income_data',$total_income_data);
                    
                    $this->set('total_income_in_percentage_data',$total_income_in_percentage_data);

                    $this->set('total_expense_exist',$total_expense_exist);
                    $this->set('total_expense_data',$total_expense_data);
                    
                    $this->set('total_expense_in_percentage_data',$total_expense_in_percentage_data);

                    $this->set('profit_loss_data',$profit_loss_data);
                    $this->set('profit_loss_in_percentage_data',$profit_loss_in_percentage_data);

                    $this->set('total_gst_liability',$total_gst_liability);

                    $this->set('total_gst_asset',$total_gst_asset);
                    $this->set('net_gst_exist',$net_gst_exist);
                    $this->set('net_gst_data',$net_gst_data);
                    
                }else{
                     $this->Session->setFlash($msg);
                }

            }           

            /*Get all services parent wise*/
            $services = $this->Shop->getAllServices();
            $this->set('services',$services);

            /*Post data*/
            $this->set('from_date',$from_date);
            $this->set('to_date',$to_date);
            $this->set('no_of_days',$no_of_days);
            $this->set('serviceval',$service);

           

           
    }

    function balanceSheet(){


        if($this->Session->read('Finance') == ""){
            $this->autoRender = false;

            if($this->Session->read('Auth.User.group_id') == SUPER_ADMIN){
                $id = $this->Session->read('Auth.User.id');
                $mobile = $this->Session->read('Auth.User.mobile');

                $this->Finance->sendOTPViaEmail($id,$mobile);
            }else{
                $msg = "<b>Error</b> :  You dont have access to view this page"; 
                $this->Session->setFlash($msg);
            }
            $this->render('verify_otp');
        }


        $flag = true;

        $params = $this->params['form'];
        

        /*Set default date as well as form date*/
        $from_date = $params['from_date']!=""?date('Y-m-d',strtotime($params['from_date'])):date('Y-m-d',strtotime('-1 week'));
        $to_date = $params['to_date']!=""?date('Y-m-d',strtotime($params['to_date'])):date('Y-m-d',strtotime('-1 day'));

        $date1=date_create($from_date);
        $date2=date_create($to_date);

        $diff=date_diff($date1,$date2);

        $no_of_days = $diff->format("%a")+1;

        $this->set('from_date',$from_date);
        $this->set('to_date',$to_date);
        $this->set('no_of_days',$no_of_days);

        $all_services = $this->Shop->getAllServices();
        $services = "";
        foreach ($all_services as $key=>$service){
            $services .= $key.",";
        }
        $service = rtrim($services,",");

        if($no_of_days > 31){
            $flag = false;
            $msg = "<b>Error</b> :  Date range cannot exceed 31 days";                  
        }

        if($flag){

            /*Assets*/

            $bank_balance_array = $this->Finance->bank_balance($from_date,$to_date);
            if(!empty($bank_balance_array)){
                $bank_balance_exist = true;
            }

            $recharge_utility_inventory_array = $this->Finance->recharge_utility_inventory($from_date,$to_date);
            if(!empty($recharge_utility_inventory_array)){
                $recharge_utility_inventory_exist = true;
            }

            $recharge_utility_advance_array = $this->Finance->recharge_utility_advance($from_date,$to_date);
            if(!empty($recharge_utility_advance_array)){
                $recharge_utility_advance_exist = true;
            }

            $vendor_prepaid_postpaid_array = $this->Finance->vendor_prepaid_postpaid($from_date,$to_date);
            if(!empty($vendor_prepaid_postpaid_array)){
                $vendor_prepaid_postpaid_exist = true;
            }

            $other_asset_array = $this->Finance->master_asset_liability($from_date,$to_date);
            if(!empty($other_asset_array)){
                $other_asset_exist = true;
            }

            /*End Assets*/

            /*Liability*/

            $float_balance_array = $this->Finance->float_balance($from_date,$to_date);
            if(!empty($float_balance_array)){
                $float_balance_exist = true;
            }

            $pending_limit_array = $this->Finance->pending_limit($from_date,$to_date);
            if(!empty($pending_limit_array)){
                $pending_limit_exist = true;
            }

            $recharge_utility_credit_array = $this->Finance->recharge_utility_credit($from_date,$to_date);
            if(!empty($recharge_utility_credit_array)){
                $recharge_utility_credit_exist = true;
            }


            $other_liability_array = $this->Finance->master_asset_liability($from_date,$to_date,2);
            if(!empty($other_liability_array)){
                $other_liability_exist = true;
            }

            /*End Assets*/

            $total_assets = array();
            $total_liability = array();
            $total_a_l = array();
            for($i=0;$i<$no_of_days;$i++){
                $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));
                if(!isset($bank_balance_array[$date])){
                    $bank_balance_array[$date] = 0;
                }

                if(!isset($recharge_utility_inventory_array[$date])){
                    $recharge_utility_inventory_array[$date] = 0;
                }

                if(!isset($recharge_utility_advance_array[$date])){
                    $recharge_utility_advance_array[$date] = 0;
                }

                if(!isset($float_balance_array[$date])){
                    $float_balance_array[$date] = 0;
                }

                if(!isset($pending_limit_array[$date])){
                    $pending_limit_array[$date] = 0;
                }

                if(!isset($recharge_utility_credit_array[$date])){
                    $recharge_utility_credit_array[$date] = 0;
                }

                $total_assets[$date] = $bank_balance_array[$date] + $recharge_utility_inventory_array[$date] + $recharge_utility_advance_array[$date];

                $total_liability[$date] = $float_balance_array[$date] + $pending_limit_array[$date] + $recharge_utility_credit_array[$date];
                
            }
            ksort($bank_balance_array);
            ksort($recharge_utility_inventory_array);
            ksort($recharge_utility_advance_array);
            ksort($float_balance_array);
            ksort($pending_limit_array);
            ksort($recharge_utility_credit_array);

            $this->set('bank_balance_exist',$bank_balance_exist);
            $this->set('bank_balance_array',$bank_balance_array);

            $this->set('recharge_utility_inventory_exist',$recharge_utility_inventory_exist);
            $this->set('recharge_utility_inventory_array',$recharge_utility_inventory_array);

            $this->set('recharge_utility_advance_exist',$recharge_utility_advance_exist);
            $this->set('recharge_utility_advance_array',$recharge_utility_advance_array);

            $this->set('vendor_prepaid_postpaid_exist',$vendor_prepaid_postpaid_exist);
            $this->set('vendor_prepaid_postpaid_array',$vendor_prepaid_postpaid_array);

            $this->set('other_asset_exist',$other_asset_exist);
            $this->set('other_asset_array',$other_asset_array);

            $this->set('float_balance_exist',$float_balance_exist);
            $this->set('float_balance_array',$float_balance_array);

            $this->set('pending_limit_exist',$pending_limit_exist);
            $this->set('pending_limit_array',$pending_limit_array);

            $this->set('recharge_utility_credit_exist',$recharge_utility_credit_exist);
            $this->set('recharge_utility_credit_array',$recharge_utility_credit_array);

            $this->set('other_liability_exist',$other_liability_exist);
            $this->set('other_liability_array',$other_liability_array);

            $this->set('total_assets',$total_assets);
            $this->set('total_liability',$total_liability);


        }else{
             $this->Session->setFlash($msg);
        }
    }

    function verifyOtp(){
        if($this->RequestHandler->isPost()) {
            $flag = true;
            $data = $this->params['form'];
            $mobile = $this->Session->read('Auth.User.mobile');

            if($data['verify_otp']){
                if($data['verify_otp'] == $this->Shop->getMemcache("otp_financePassword_$mobile")){
                    $this->autoRender = false;
                    $this->Shop->delMemcache("otp_financePassword_$mobile");
                    $this->Session->write('Finance','Allow');

                    $this->redirect(array('controller' => 'finance', 'action' => 'overview'));
                }else{
                    $flag = false;
                    $msg = "<b>Error</b> : OTP did not match"; 
                }

            }else{
                $flag = false;
                $msg = "<b>Error</b> : Please enter OTP"; 
            }

            if(!$flag){
                 $this->Session->setFlash($msg);
            }
        }
    }

        

       
        
        
}
