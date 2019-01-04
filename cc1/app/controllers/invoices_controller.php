<?php
class InvoicesController extends AppController{
    var $name = 'Invoices';
    var $components = array('RequestHandler', 'Shop', 'Busvendors', 'General', 'B2cextender', 'Recharge','Invoice','Documentmanagement','Serviceintegration');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator');
    var $uses = array('User', 'Slaves');

    function beforeFilter(){
        parent::beforeFilter();
        set_time_limit(0);
        ini_set("memory_limit", "-1");
        $this->Auth->allow('*');
        $whitelist_ips = explode(",",CRON_WHITELIST_IP);
        
        $client_ip = $this->General->getClientIP();
        
        if(!in_array($client_ip,$whitelist_ips)){
            return;
        }
    }

   /* function getPay1ToRetailerInvoiceOld()
    {
        $this->autoRender = false;
        $from_date = date('Y-m-01', strtotime('-1 month'));
        $to_date = date('Y-m-t', strtotime('-1 month'));

        Configure::load('product_config');
        $services=Configure::read('services');
        $invoiceDescriptions=Configure::read('invoiceDescriptions');
        $gst_state_code_mapping=Configure::read('gst_state_code_mapping');

        $retailer_gst_nos = $this->Documentmanagement->getLabelDescription(NULL,20);

        $get_retailer_data = "SELECT rel.ret_user_id as source_id,rel.service_id,rel.product_type,rel.type,sum(rel.amount) as total_sale,sum(rel.earning) as ret_earning "
                           . "FROM retailer_earning_logs rel "
                           . "LEFT JOIN distributors d "
                           . "ON (rel.dist_user_id=d.user_id) "
                           . "WHERE 1 "
                           . "AND rel.service_id IN (0,1,2,4,5,6,7,8,10,12) "
                           . "AND d.id NOT IN (".SAAS_DISTS.") "
//                               . "AND st.retailer_id IN (".implode(",",$retList).") "
                           . "AND rel.date >= '$from_date' "
                           . "AND rel.date <= '$to_date' "
                           . "GROUP BY rel.ret_user_id,rel.service_id,rel.type,rel.product_type ";

        $retailer_data = $this->Slaves->query($get_retailer_data);

        foreach($retailer_data as $data)
        {
            $response[$data['rel']['source_id']]['target_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';

            if((in_array($data['rel']['service_id'], array(1,2,5,7))) && ($data['rel']['product_type'] == 0))
            {
              $response[$data['rel']['source_id']][$data['rel']['service_id']]['total_sale'] = $data[0]['total_sale'];
              $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_commission'] = $data[0]['ret_earning'];
            }
            elseif((in_array($data['rel']['service_id'], array(4))) && ($data['rel']['product_type'] == 1))
            {
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['bill_payment_charges'] = $data[0]['ret_earning'];
            }
            elseif((in_array($data['rel']['service_id'], array(6))) && ($data['rel']['product_type'] == 1))
            {
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['bill_payment_charges'] += $data[0]['ret_earning'];
            }
            elseif(in_array($data['rel']['service_id'], array(8)))
            {
                if($data['rel']['type'] == CREDIT_NOTE)
                {
                    $response[$data['rel']['source_id']][$data['rel']['service_id']]['service_charges'] = $data[0]['ret_earning'];
                }
                elseif($data['rel']['type'] == RENTAL)
                {
                    $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_rental'] = $data[0]['total_sale'];
                }
            }
            elseif(in_array($data['rel']['service_id'], array(10)))
            {
                if($data['rel']['type'] == RENTAL)
                {
                    $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_rental'] = $data[0]['total_sale'];
                }
            }
            elseif(in_array($data['rel']['service_id'], array(12)))
            {
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['service_charges'] = $data[0]['ret_earning'];
            }
            elseif((in_array($data['rel']['service_id'], array(0))) && ($data['rel']['type'] == REFUND))
            {
                $response[$data['rel']['source_id']]['incentive'] = $data[0]['total_sale'];
            }
        }

        $get_ret_kit_charges = "SELECT st.source_id,st.user_id as service_id,SUM(st.amount) as payable_amt,SUM(st.discount_comission) as discount_comission " //source_id is for user_id and user_id is for service_id
                            . "FROM shop_transactions st "
                            . "JOIN retailers r "
                            . "ON (st.source_id = r.user_id) "
                            . "WHERE st.type = ".KITCHARGE." "
                            . "AND r.parent_id NOT IN (".SAAS_DISTS.") "
                            . "AND st.date >= '$from_date' "
                            . "AND st.date <= '$to_date' "
                            . "GROUP BY st.source_id,st.user_id";

        $kit_charges = $this->Slaves->query($get_ret_kit_charges);

        foreach($kit_charges as $data)
        {
            $response[$data['st']['source_id']]['target_gst_no'] = array_key_exists($data['st']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['st']['source_id']]:'';
            $response[$data['st']['source_id']][$data['st']['service_id']]['target_id'] = $data['st']['source_id'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['service_id'] = $data['st']['service_id'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['kit_charges'] = $data[0]['payable_amt'];
//            $response[$data['st']['source_id']][$data['st']['service_id']]['discount_comission'] = $data[0]['discount_comission'];
//            $response[$data['st']['source_id']][$data['st']['service_id']]['total_amt'] = $data[0]['payable_amt']+$data[0]['discount_comission'];
        }
        $get_retailer_state = $this->Invoice->getRetailerState();

        $response1 = $response;

        $dataSource = $this->User->getDataSource();
        $dataSource->begin();

        foreach ($response as $ret_user_id=>$invoice_data)
        {
            $source_gst_no = PAY1_GST_NO;
            $target_gst_no = (strlen($invoice_data['target_gst_no']) < 15)?NULL:$invoice_data['target_gst_no'];
            $incentive = (isset($invoice_data['incentive']))?$invoice_data['incentive']:0;
            unset($invoice_data['target_gst_no'],$invoice_data['incentive']);
            $source_state = 'Maharashtra';
            $invoicedate = date('Y-m-t', strtotime('-1 month'));
            $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
            $year = date('Y', strtotime($invoicedate));
            $state = isset($get_retailer_state[$ret_user_id])?$get_retailer_state[$ret_user_id]['state']:'Maharashtra';
            $target_state = !empty($target_gst_no) ? $gst_state_code_mapping[substr($target_gst_no,0,2)] : $state;
            $response1[$ret_user_id]['state'] = $target_state;
//                $ret_inc = 0;
            $flag = FALSE;

            $invoice_id= $this->Invoice->addTaxInvoice(0,$ret_user_id,0,6,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year);
            if($invoice_id)
            {
                foreach ($invoice_data as $service_id=>$data)
                {
                    $description = $invoiceDescriptions[$service_id];
                    $total_sale = isset($data['total_sale'])?$data['total_sale']:0;
                    $invoice_type = in_array($service_id, array(1,2,5,7))?0:1; //0 means discount model , 1 means commission model

                    if(in_array($service_id, array(1,2)))//Recharge,postpaid n utility bill payments
                    {
                      $hsn_no = 9984;
                    }
                    else
                    {
                      $hsn_no = 9971;
                    }

                    if(in_array($service_id, array(1,2,5,7)))
                    {
                        $payable_amt = $total_sale-$data['ret_commission'];

                        if($service_id==1 && (!$flag)){
                            if($payable_amt >= $incentive){
                                $payable_amt = $payable_amt-$incentive;
                                $flag = TRUE;
                            }
                        }

                        if($service_id==2 && (!$flag)){
                            if($payable_amt >= $incentive){
                                $payable_amt = $payable_amt-$incentive;
                                $flag = TRUE;
                            }
                        }

                        $response1[$ret_user_id][$service_id]['payable_amt'] =$payable_amt;
                    }
                    elseif(in_array($service_id, array(4,6))) //postpaid and utility bill payment services
                    {
                        $response1[$ret_user_id][$service_id]['payable_amt'] =$payable_amt = $data['bill_payment_charges'];
                    }
                    elseif(in_array($service_id, array(12))) //dmt services
                    {
                        $response1[$ret_user_id][$service_id]['payable_amt'] = $payable_amt = $data['service_charges'];
                    }

                    if($target_state == 'Maharashtra')
                    {
                        $response1[$ret_user_id][$service_id]['cgst'] =$cgst = 9;
                        $response1[$ret_user_id][$service_id]['sgst'] =$sgst = 9;
                        $response1[$ret_user_id][$service_id]['igst'] =$igst = 0;
                    }
                    else
                    {
                        $response1[$ret_user_id][$service_id]['cgst'] =$cgst = 0;
                        $response1[$ret_user_id][$service_id]['sgst'] =$sgst = 0;
                        $response1[$ret_user_id][$service_id]['igst'] =$igst = 18;
                    }

                    if(in_array($service_id,array(8,10)))
                    {
                        $service_name = $services[$service_id]['name'];
                        if(isset($data['service_charges']))
                        {
                            $description = $service_name.' Service Charges';
                            $payable_amt = $data['service_charges'];
                            $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description','$total_sale','$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                        }
                        if(isset($data['ret_rental']))
                        {
                            $description = $service_name.' Monthly Rental';
                            $payable_amt = $data['ret_rental'];
                            $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description','$total_sale','$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                        }
                        if(isset($data['kit_charges']))
                        {
                            $description = $invoiceDescriptions[15][$service_id];
                            $payable_amt = $data['kit_charges'];
                            $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description','$total_sale','$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                        }
                    }
                    else
                    {
                        $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description','$total_sale','$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                    }

                    if(!$insert_tax_invoices_logs_data)
                    {
                        // Transaction rollback
                        $dataSource->rollback();
                        $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                        return $response;
                    }
                    $dataSource->commit();
                }

            }

        }
    }
    */
    function getPay1ToRetailerInvoice()
    {
        $this->autoRender = false;
        $from_date = date('Y-m-01', strtotime('-1 month'));
        $to_date = date('Y-m-t', strtotime('-1 month'));
        
        $gst_state_code_mapping = Configure::read('gst_state_code_mapping');

        $retailer_gst_nos = $this->Documentmanagement->getLabelDescription(NULL,20);
                
        $retailer_txn_data = $this->Slaves->query('SELECT rel.ret_user_id as source_id,rel.service_id,s.name as service_name,s.parent_id,rel.txn_type,rel.type,SUM(rel.closing_amt-rel.txn_reverse_amt) AS total_sale,SUM(rel.earning) AS ret_earning,SUM(rel.service_charge) AS service_charge,SUM(rel.commission) AS commission,SUM(rel.cancellation_charges) AS cancellation_charges '
                           . 'FROM retailer_earning_logs rel '
                           . 'LEFT JOIN distributors d ON (rel.dist_user_id = d.user_id) '
                           . 'LEFT JOIN services s ON (rel.service_id = s.id) '
                           . 'WHERE 1 '
                           . 'AND d.id NOT IN ('.SAAS_DISTS.') '
                           . 'AND rel.date >= "'.$from_date.'" '
                           . 'AND rel.date <= "'.$to_date.'" '
//                           . 'AND rel.txn_type NOT IN (1,-1) '
                           . 'GROUP BY rel.ret_user_id,rel.service_id,rel.txn_type');
        
        foreach($retailer_txn_data as $data)
        {
            if(($data['rel']['txn_type'] == 0) || ($data['rel']['txn_type'] == 1 && ($data[0]['service_charge'] > 0 || $data[0]['cancellation_charges'] > 0))){
                $response[$data['rel']['source_id']]['target_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';
                $txn_type = $data[0]['cancellation_charges'] > 0?2:$data['rel']['txn_type'];
                $key = $data['rel']['source_id'].'_'.$data['rel']['service_id'].'_'.$txn_type;
                $parent_id = $data['s']['parent_id'];
                $response[$data['rel']['source_id']][$parent_id][$key]['target_id'] = $data['rel']['source_id'];
                $response[$data['rel']['source_id']][$parent_id][$key]['service_id'] = $data['rel']['service_id'];
                $response[$data['rel']['source_id']][$parent_id][$key]['service_name'] = $data['s']['service_name'];
    //            $response[$data['rel']['source_id']][$parent_id][$key]['type'] = $data['rel']['type'];
                $response[$data['rel']['source_id']][$parent_id][$key]['txn_type'] = $txn_type;

                if($txn_type == 0) //p2p
                {
                    $response[$data['rel']['source_id']][$parent_id][$key]['total_sale'] = $data[0]['total_sale'];
                    $response[$data['rel']['source_id']][$parent_id][$key]['ret_earning'] = $data[0]['commission'];
                    $response[$data['rel']['source_id']][$parent_id][$key]['payable_amt'] = $data[0]['total_sale'] - $data[0]['commission'];
                }
                elseif($txn_type == 1) //service charges
                {
                    $response[$data['rel']['source_id']][$parent_id][$key]['payable_amt'] = $data[0]['service_charge'];
                }
                else //cancellation_charges
                {
                    $response[$data['rel']['source_id']][$parent_id][$key]['payable_amt'] = $data[0]['cancellation_charges'];
                }
            }
        }
        
        $incentive_data = $this->Slaves->query('SELECT rel.user_id as source_id,rel.service_id,s.name as service_name,s.parent_id,rel.type,SUM(rel.amount-rel.txn_reverse_amt) AS payable_amt,SUM(rel.txn_reverse_amt) AS txn_reverse_amt,s.inc_adj_services,d.user_id '
                                            . 'FROM users_nontxn_logs rel '
                                            . 'JOIN retailers r ON (rel.user_id = r.user_id) '
                                            . 'LEFT JOIN distributors d ON (rel.user_id = d.user_id) '
                                            . 'LEFT JOIN services s ON (rel.service_id = s.id) '
                                            . 'WHERE 1 '
                                            . 'AND rel.date >= "'.$from_date.'" '
                                            . 'AND rel.date <= "'.$to_date.'" '
                                            . 'AND rel.type = '.REFUND.' '
                                            . 'GROUP BY rel.user_id,rel.service_id');
        
        foreach ($incentive_data as $data){
//            $incentive = $data[0]['payable_amt'] - $data[0]['txn_reverse_amt'];
            if($data['rel']['source_id'] != $data['d']['user_id']){
                $incentive = $data[0]['payable_amt'];
                $parent_id = $data['s']['parent_id'];
                if(!is_null($data['s']['inc_adj_services'])){
                    $inc_adj_services = explode(',',$data['s']['inc_adj_services']);
                        foreach($inc_adj_services as $service_id){
                            if(isset($response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$service_id.'_0']['payable_amt']) && $response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$service_id.'_0']['payable_amt'] > $incentive){
                                $response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$service_id.'_0']['payable_amt'] = $response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$service_id.'_0']['payable_amt'] - $incentive;
                                break;
                            }

                            if(isset($response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$service_id.'_1']['payable_amt']) && $response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$service_id.'_1']['payable_amt'] > $incentive){
                                $response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$service_id.'_1']['payable_amt'] = $response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$service_id.'_1']['payable_amt'] - $incentive;
                                break;
                            }                        
                        }    
                }
            }
        }
        
        $retailer_service_data = $this->Slaves->query('SELECT rel.user_id as source_id,rel.service_id,s.name as service_name,s.parent_id,rel.type,SUM(if(rel.type != '.KITCHARGE.',rel.amount-rel.txn_reverse_amt,rel.amount)) AS payable_amt,r.user_id,d.user_id '
                                            . 'FROM users_nontxn_logs rel '
                                            . 'JOIN retailers r ON (rel.user_id = r.user_id) '
                                            . 'LEFT JOIN distributors d ON (r.user_id = d.user_id) '
                                            . 'LEFT JOIN services s ON (rel.service_id = s.id) '
                                            . 'WHERE 1 '
                                            . 'AND rel.date >= "'.$from_date.'" '
                                            . 'AND rel.date <= "'.$to_date.'" '
                                            . 'AND rel.type IN ('.RENTAL.','.KITCHARGE.','.ONE_TIME_CHARGE.') '
                                            . 'GROUP BY rel.user_id,rel.service_id,rel.type');
        
        foreach ($retailer_service_data as $data){
            if($data['rel']['type'] == RENTAL || ($data['r']['user_id'] != $data['d']['user_id'] && in_array($data['rel']['type'],array(KITCHARGE,ONE_TIME_CHARGE)))){
                $key = $data['rel']['source_id'].'_'.$data['rel']['service_id'].'_'.$data['rel']['type'];
                $parent_id = $data['s']['parent_id'];      
                if($data['rel']['type'] == RENTAL && $data['rel']['service_id'] == 1){
                    if(isset($response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$data['rel']['service_id'].'_0']['payable_amt']) && $response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$data['rel']['service_id'].'_0']['payable_amt'] > $data[0]['payable_amt']){
                        $response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$data['rel']['service_id'].'_0']['payable_amt'] = $response[$data['rel']['source_id']][$parent_id][$data['rel']['source_id'].'_'.$data['rel']['service_id'].'_0']['payable_amt'] - $data[0]['payable_amt'];
                        break;
                    }
                }else{                    
                    $response[$data['rel']['source_id']]['target_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';
                    $response[$data['rel']['source_id']][$parent_id][$key]['target_id'] = $data['rel']['source_id'];
                    $response[$data['rel']['source_id']][$parent_id][$key]['service_id'] = $data['rel']['service_id'];
                    $response[$data['rel']['source_id']][$parent_id][$key]['service_name'] = $data['s']['service_name'];
                    $response[$data['rel']['source_id']][$parent_id][$key]['type'] = $data['rel']['type'];
        //            $response[$data['rel']['source_id']][$parent_id][$key]['txn_type'] = $txn_type;             
                    $response[$data['rel']['source_id']][$parent_id][$key]['payable_amt'] = $data[0]['payable_amt'];
                }
            }
        }
                
        $get_retailer_state = $this->Invoice->getRetailerState();
      
        foreach ($response as $ret_user_id=>$inv_data)
        {
            $source_gst_no = PAY1_GST_NO;
            $target_gst_no = (strlen($inv_data['target_gst_no']) < 15)?NULL:$inv_data['target_gst_no'];            
            unset($inv_data['target_gst_no']);
            foreach ($inv_data as $parent_service_id=>$invoice_data)
            {
                if(!empty($invoice_data))
                {
                    $source_state = 'Maharashtra';
                    $invoicedate = date('Y-m-t', strtotime('-1 month'));
                    $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
                    $year = date('Y', strtotime($invoicedate));
                    $state = isset($get_retailer_state[$ret_user_id])?$get_retailer_state[$ret_user_id]['state']:'Maharashtra';
                    $target_state = !empty($target_gst_no) ? $gst_state_code_mapping[substr($target_gst_no,0,2)] : $state;
                    $dataSource = $this->User->getDataSource();
                    $dataSource->begin();

                    $invoice_id = $this->Invoice->addTaxInvoice($parent_service_id,0,$ret_user_id,0,6,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year,0,$dataSource);
                    if($invoice_id)
                    {
                        foreach ($invoice_data as $data)
                        {
                            $service_id = $data['service_id'];

                            $total_sale = isset($data['total_sale']) ? $data['total_sale'] : 0;
                            $payable_amt = $data['payable_amt'];
                            $invoice_type = $data['txn_type'] == 0 ? 0 : 1; //0 means discount model , 1 means commission model
                            $hsn_no = $data['txn_type'] == 0 ? 9984 : 9971;

                            if(isset($data['txn_type']) && $data['txn_type'] == 0){
                                $description = $data['service_name'];
                            }elseif(isset($data['txn_type']) && $data['txn_type'] == 1){
                                $description = $data['service_name'].' Service Charges';
                            }elseif(isset($data['txn_type']) && $data['txn_type'] == 2){
                                $description = $data['service_name'].' Cancellation Charges';
                            }elseif(isset($data['type']) && $data['type'] == RENTAL){
                                $description = $data['service_name'].' Monthly Rental';
                            }elseif(isset($data['type']) && $data['type'] == KITCHARGE){
                                $description = $data['service_name'].' Setup Cost';
                            }elseif(isset($data['type']) && $data['type'] == ONE_TIME_CHARGE){
                                $description = $data['service_name'].' Service Fee';
                            }

                            if($target_state == 'Maharashtra')
                            {
                                $cgst = 9;
                                $sgst = 9;
                                $igst = 0;
                            }

                            else
                            {
                                $cgst = 0;
                                $sgst = 0;
                                $igst = 18;
                            }

                            $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description','$total_sale','$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");

                            if(!$insert_tax_invoices_logs_data)
                            {
                                // Transaction rollback
                                $dataSource->rollback();
                                $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                                return $response;
                            }
                            $dataSource->commit();
                        }
                    }
                    else
                    {
                        $dataSource->rollback();
                    }
                }
            }
        }
    }

    function getPay1ToRetailerInvoiceJuly() //july adjustments
    {
        $this->autoRender = false;
//        $date = '2017-07-01';
        $from_date = '2017-07-01';
        $to_date = '2017-07-31';

        Configure::load('product_config');
        $services=Configure::read('services');
        $invoiceDescriptions=Configure::read('invoiceDescriptions');

//        $split_size = 100;
//        $retList = array();
//        $retailers = $this->Slaves->query("SELECT distinct retailer_id FROM retailers_logs WHERE month(date) = month('$date') AND year(date) = year('$date')");
//        foreach($retailers as $rets){
//            $retList[] = $rets['retailers_logs']['retailer_id'];
//        }
        $response = array();
//        $retList = array_chunk($retList, $split_size);

        $retailer_gst_nos = $this->Documentmanagement->getLabelDescription(NULL,20);

        $get_retailer_data1 = "SELECT rel.ret_user_id as source_id,rel.service_id,rel.product_type,rel.type,sum(rel.amount) as total_sale,sum(rel.earning) as ret_earning "
                               . "FROM retailer_earning_logs rel "
                               . "LEFT JOIN distributors d "
                               . "ON (rel.dist_user_id=d.user_id) "
                               . "WHERE 1 "
                               . "AND rel.service_id IN (0,1,2,4,5,7,8,12) "
                               . "AND d.id NOT IN (".SAAS_DISTS.") "
//                               . "AND st.retailer_id IN (".implode(",",$retList).") "
                               . "AND rel.date >= '2017-07-05' "
                               . "AND rel.date <= '2017-07-31' "
                               . "GROUP BY rel.ret_user_id,rel.service_id,rel.type,rel.product_type ";

        $get_retailer_data2 = "SELECT rel.ret_user_id as source_id,rel.service_id,rel.product_type,rel.type,sum(rel.amount) as total_sale,sum(rel.earning) as ret_earning "
                               . "FROM retailer_earning_logs rel "
                               . "LEFT JOIN distributors d "
                               . "ON (rel.dist_user_id=d.user_id) "
                               . "WHERE 1 "
                               . "AND rel.service_id IN (0,1,2) "
                               . "AND d.id NOT IN (".SAAS_DISTS.") "
//                               . "AND st.retailer_id IN (".implode(",",$retList).") "
                               . "AND rel.date = '2017-07-04' "
                               . "AND d.id NOT IN (72,101,183,249,279,285,312,323,409,417,428,452,485,487,575,840,908,934,1522,1540,1569,1706,1883,2016,2120) "
                               . "GROUP BY rel.ret_user_id,rel.service_id,rel.type,rel.product_type ";

        $get_retailer_data3 = "SELECT rel.ret_user_id as source_id,rel.service_id,rel.product_type,rel.type,sum(rel.amount) as total_sale,sum(rel.earning) as ret_earning "
                               . "FROM retailer_earning_logs rel "
                               . "LEFT JOIN distributors d "
                               . "ON (rel.dist_user_id=d.user_id) "
                               . "WHERE 1 "
                               . "AND rel.service_id IN (4,5,7,8,12) "
                               . "AND d.id NOT IN (".SAAS_DISTS.") "
//                               . "AND st.retailer_id IN (".implode(",",$retList).") "
                               . "AND rel.date >= '2017-07-01' "
                               . "AND rel.date <= '2017-07-04' "
                               . "GROUP BY rel.ret_user_id,rel.service_id,rel.type,rel.product_type ";

        $retailer_data1 = $this->Slaves->query($get_retailer_data1);
        $retailer_data2 = $this->Slaves->query($get_retailer_data2);
        $retailer_data3 = $this->Slaves->query($get_retailer_data3);

        foreach($retailer_data1 as $data)
        {
            $response[$data['rel']['source_id']]['target_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';

            if((in_array($data['rel']['service_id'], array(1,2,5,7))) && ($data['rel']['product_type'] == 0))
            {
              $response[$data['rel']['source_id']][$data['rel']['service_id']]['total_sale'] += $data[0]['total_sale'];
              $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_commission'] += $data[0]['ret_earning'];
            }
            elseif((in_array($data['rel']['service_id'], array(4))) && ($data['rel']['product_type'] == 1))
            {
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['bill_payment_charges'] += $data[0]['ret_earning'];
            }
            elseif(in_array($data['rel']['service_id'], array(8)))
            {
                if($data['rel']['type'] == CREDIT_NOTE)
                {
                    $response[$data['rel']['source_id']][$data['rel']['service_id']]['service_charges'] += $data[0]['ret_earning'];
                }
                elseif($data['rel']['type'] == RENTAL)
                {
                    $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_rental'] += $data[0]['total_sale'];
                }
            }
            elseif(in_array($data['rel']['service_id'], array(12)))
            {
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['service_charges'] += $data[0]['ret_earning'];
            }
            elseif((in_array($data['rel']['service_id'], array(0))) && ($data['rel']['type'] == REFUND))
            {
                $response[$data['rel']['source_id']]['incentive'] += $data[0]['total_sale'];
            }
        }

        foreach($retailer_data2 as $data)
        {
            $response[$data['rel']['source_id']]['target_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';

            if((in_array($data['rel']['service_id'], array(1,2))) && ($data['rel']['product_type'] == 0))
            {
              $response[$data['rel']['source_id']][$data['rel']['service_id']]['total_sale'] += $data[0]['total_sale'];
              $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_commission'] += $data[0]['ret_earning'];
            }
            elseif((in_array($data['rel']['service_id'], array(0))) && ($data['rel']['type'] == REFUND))
            {
                $response[$data['rel']['source_id']]['incentive'] += $data[0]['total_sale'];
            }
        }

        foreach($retailer_data3 as $data)
        {
            $response[$data['rel']['source_id']]['target_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';

            if((in_array($data['rel']['service_id'], array(1,2,5,7))) && ($data['rel']['product_type'] == 0))
            {
              $response[$data['rel']['source_id']][$data['rel']['service_id']]['total_sale'] += $data[0]['total_sale'];
              $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_commission'] += $data[0]['ret_earning'];
            }
            elseif((in_array($data['rel']['service_id'], array(4))) && ($data['rel']['product_type'] == 1))
            {
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['bill_payment_charges'] += $data[0]['ret_earning'];
            }
            elseif(in_array($data['rel']['service_id'], array(8)))
            {
                if($data['rel']['type'] == CREDIT_NOTE)
                {
                    $response[$data['rel']['source_id']][$data['rel']['service_id']]['service_charges'] += $data[0]['ret_earning'];
                }
                elseif($data['rel']['type'] == RENTAL)
                {
                    $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_rental'] += $data[0]['total_sale'];
                }
            }
            elseif(in_array($data['rel']['service_id'], array(12)))
            {
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['service_charges'] += $data[0]['ret_earning'];
            }
            elseif((in_array($data['rel']['service_id'], array(0))) && ($data['rel']['type'] == REFUND))
            {
                $response[$data['rel']['source_id']]['incentive'] += $data[0]['total_sale'];
            }
        }

        $get_retailer_state = $this->Invoice->getRetailerState();

        $response1 = $response;

        $dataSource = $this->User->getDataSource();
        $dataSource->begin();

        foreach ($response as $ret_user_id=>$invoice_data)
        {
            $source_gst_no = PAY1_GST_NO;
            $target_gst_no = (strlen($invoice_data['target_gst_no']) < 15)?NULL:$invoice_data['target_gst_no'];
            $incentive = (isset($invoice_data['incentive']))?$invoice_data['incentive']:0;
            unset($invoice_data['target_gst_no'],$invoice_data['incentive']);
            $source_state = 'Maharashtra';
            $invoicedate = '2017-07-31';
            $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
            $year = date('Y', strtotime($invoicedate));
            $state = isset($get_retailer_state[$ret_user_id])?$get_retailer_state[$ret_user_id]['state']:'Maharashtra';
            $target_state = $state;
            $response1[$ret_user_id]['state'] = $state;
//            $ret_inc = 0;
            $flag = FALSE;

            $invoice_id= $this->Invoice->addTaxInvoice(0,$ret_user_id,0,6,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,0,$year);

            if($invoice_id)
            {
                foreach ($invoice_data as $service_id=>$data)
                {
                    $description = $invoiceDescriptions[$service_id];
                    $total_sale = isset($data['total_sale'])?$data['total_sale']:0;
                    $invoice_type = in_array($service_id, array(1,2,5,7))?0:1; //0 means discount model , 1 means commission model

                    if(in_array($service_id, array(1,2)))//Recharge,postpaid n utility bill payments
                    {
                      $hsn_no = 9984;
                    }
                    else
                    {
                      $hsn_no = 9971;
                    }

                    if(in_array($service_id, array(1,2,5,7)))
                    {
                        $payable_amt = $total_sale-$data['ret_commission'];

                        if($service_id==1 && (!$flag)){
                                if($payable_amt >= $incentive){
                                    $payable_amt = $payable_amt-$incentive;
                                    $flag = TRUE;
                                }
                        }

                        if($service_id==2 && (!$flag)){
                            if($payable_amt >= $incentive){
                                $payable_amt = $payable_amt-$incentive;
                                $flag = TRUE;
                            }
                        }
//                        if($service_id==1)
//                        {
//                            $ret_incentive = (($total_sale-$data['ret_commission']) >= $incentive)?$incentive:0;
//                            $total_commission = $data['ret_commission']+$ret_incentive;
//                            $ret_inc = ($ret_incentive>0)?0:$incentive;
//                        }
//                        elseif($service_id==2)
//                        {
//                            $ret_incentive = (($total_sale-$data['ret_commission']) >= $ret_inc)?$ret_inc:0;
//                            $total_commission = $data['ret_commission']+$ret_incentive;
//                        }
//                        else
//                        {
//                            $total_commission = $data['ret_commission'];
//                        }
//                        $response1[$ret_user_id][$service_id]['payable_amt'] =$payable_amt = $total_sale-$total_commission;
                        $response1[$ret_user_id][$service_id]['payable_amt'] =$payable_amt;
                    }
                    elseif(in_array($service_id, array(4))) //utility bill payment services
                    {
                        $response1[$ret_user_id][$service_id]['payable_amt'] =$payable_amt = $data['bill_payment_charges'];
                    }
                    elseif(in_array($service_id, array(12))) //dmt services
                    {
                        $response1[$ret_user_id][$service_id]['payable_amt'] = $payable_amt = $data['service_charges'];
                    }

                    if($target_state == 'Maharashtra')
                    {
                        $response1[$ret_user_id][$service_id]['cgst'] =$cgst = 9;
                        $response1[$ret_user_id][$service_id]['sgst'] =$sgst = 9;
                        $response1[$ret_user_id][$service_id]['igst'] =$igst = 0;
                    }
                    else
                    {
                        $response1[$data['source_id']][$service_id]['cgst'] =$cgst = 0;
                        $response1[$data['source_id']][$service_id]['sgst'] =$sgst = 0;
                        $response1[$data['source_id']][$service_id]['igst'] =$igst = 18;
                    }
                    if($service_id == 8)
                    {
                        if(isset($data['service_charges']))
                        {
                            $description = 'MPOS Service Charges';
                            $payable_amt = $data['service_charges'];
                            $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description','$total_sale','$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                        }
                        if(isset($data['ret_rental']))
                        {
                            $description = 'MPOS Monthly Rental';
                            $payable_amt = $data['ret_rental'];
                            $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description','$total_sale','$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                        }
                    }
                    else
                    {
                        $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description','$total_sale','$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                    }

                    if(!$insert_tax_invoices_logs_data)
                    {
                        // Transaction rollback
                        $dataSource->rollback();
                        $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                        return $response;
                    }
                    $dataSource->commit();
                }
            }
        }
    }

//    function getRetToPay1InvoiceOld()
//    {
//        $this->autoRender = false;
//        $from_date = date('Y-m-01', strtotime('-1 month'));
//        $to_date = date('Y-m-t', strtotime('-1 month'));
//
//        Configure::load('product_config');
//        $services=Configure::read('services');
//        $invoiceDescriptions=Configure::read('invoiceDescriptions');
//        $gst_state_code_mapping=Configure::read('gst_state_code_mapping');
//
//        $get_retailer_data = "SELECT rel.ret_user_id as source_id,rel.service_id,rel.product_type,sum(rel.amount) as total_sale,sum(rel.earning) as ret_earning "
//            . "FROM retailer_earning_logs rel "
//            . "LEFT JOIN distributors d "
//            . "ON (rel.dist_user_id=d.user_id) "
//            . "WHERE rel.service_id IN (1,2) "
////                . "AND rel.ret_user_id IN (".implode(",",$rets).") "
//            . "AND rel.product_type = 1 " //p2a
//            . "AND d.id NOT IN (".SAAS_DISTS.") "
//            . "AND rel.date >= '$from_date' "
//            . "AND rel.date <= '$to_date' "
//            . "GROUP BY rel.ret_user_id,rel.service_id ";
//
//        $retailer_data = $this->Slaves->query($get_retailer_data);
//
//        $retailer_gst_nos = $this->Documentmanagement->getLabelDescription(NULL,20);
//
//        $response = array();
//
//        foreach($retailer_data as $data)
//        {
//            $response[$data['rel']['source_id']][$data['rel']['service_id']]['source_id'] = $data['rel']['source_id'];
//            $response[$data['rel']['source_id']][$data['rel']['service_id']]['service_id'] = $data['rel']['service_id'];
//            $response[$data['rel']['source_id']]['source_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';
//            $response[$data['rel']['source_id']][$data['rel']['service_id']]['total_sale'] = $data[0]['total_sale'];
//            $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_earning'] = $data[0]['ret_earning'];
//        }
//
//        $get_retailer_state = $this->Invoice->getRetailerState();
//
//        $dataSource = $this->User->getDataSource();
//        $dataSource->begin();
//
//        foreach($response as $ret_user_id=>$invoice_data)
//        {
//            $source_id = $ret_user_id;
//            $source_gst_no = (strlen($invoice_data['source_gst_no']) < 15)?NULL:$invoice_data['source_gst_no'];
//
//            unset($invoice_data['source_gst_no']);
//            $target_id = 0;
//            $target_gst_no = PAY1_GST_NO;
//            $state = isset($get_retailer_state[$source_id])?$get_retailer_state[$source_id]['state']:'Maharashtra';
//            $source_state = !empty($source_gst_no) ? $gst_state_code_mapping[substr($source_gst_no,0,2)] : $state;
//            $response1[$ret_user_id]['state'] = $source_state;
//            $target_state = 'Maharashtra';
//            $hsn_no = 9984;
//
//            $invoicedate = date('Y-m-t', strtotime('-1 month'));
//            $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
//            $year = date('Y', strtotime($invoicedate));
//
//            $invoice_id= $this->Invoice->addTaxInvoice($source_id,$target_id,6,0,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year);
//
//            if($invoice_id)
//            {
//                $response_status_flag = 1; // success
//            }
//            else
//            {
//                $response_status_flag = 0; // failure
//            }
//
//            foreach ($invoice_data as $service_id=>$data)
//            {
//                $description = 'Commision for '.$invoiceDescriptions[$service_id];
//                $payable_amt = $data['ret_earning'];
//                $invoice_type = 1;
//
//                if(strtolower($source_state) == strtolower($target_state))
//                {
//                    $cgst = 9;
//                    $sgst = 9;
//                    $igst = 0;
//                }
//                else
//                {
//                    $cgst = 0;
//                    $sgst = 0;
//                    $igst = 18;
//                }
//
//                if($response_status_flag == 1)
//                {
//                    $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$hsn_no','$description',0,'$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
//                    if(!$insert_tax_invoices_logs_data)
//                    {
//                        // Transaction rollback
//                        $dataSource->rollback();
//                        $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
//                        return $response;
//                    }
//                    $dataSource->commit();
//                }
//            }
//        }
//    }
    function getRetToPay1Invoice()
    {
        $this->autoRender = false;
        $from_date = date('Y-m-01', strtotime('-1 month'));
        $to_date = date('Y-m-t', strtotime('-1 month'));

        $gst_state_code_mapping = Configure::read('gst_state_code_mapping');
        
        $retailer_gst_nos = $this->Documentmanagement->getLabelDescription(NULL,20);
        
        $get_retailer_data = 'SELECT rel.ret_user_id as source_id,rel.service_id,s.name as service_name,s.parent_id,rel.txn_type,rel.txn_type_flag,sum(rel.earning) as ret_earning,sum(rel.commission) as commission '
            . 'FROM retailer_earning_logs rel '
            . 'LEFT JOIN distributors d ON (rel.dist_user_id = d.user_id) '
            . 'LEFT JOIN services s ON (rel.service_id = s.id) '
            . 'WHERE 1 '
            . 'AND rel.txn_type = 1 ' //p2a
            . 'AND d.id NOT IN ('.SAAS_DISTS.') '
            . 'AND rel.date >= "'.$from_date.'" '
            . 'AND rel.date <= "'.$to_date.'" '
            . 'GROUP BY rel.ret_user_id,rel.service_id,rel.txn_type_flag';

        $retailer_data = $this->Slaves->query($get_retailer_data);
        
        $response = array();
        foreach($retailer_data as $data)
        {
            if($data[0]['commission'] > 0){
                $type = DEBIT_NOTE;
                $parent_id = $data['s']['parent_id'];
                $key = $data['rel']['source_id'].'_'.$data['rel']['service_id'].'_'.$type.'_'.$data['rel']['txn_type_flag'];            
                $response[$data['rel']['source_id']][$parent_id][$key]['source_id'] = $data['rel']['source_id'];
                $response[$data['rel']['source_id']][$parent_id][$key]['service_id'] = $data['rel']['service_id'];  
                $response[$data['rel']['source_id']][$parent_id][$key]['ret_earning'] = $data[0]['commission'];
                $response[$data['rel']['source_id']][$parent_id][$key]['type'] = $type;
                $response[$data['rel']['source_id']][$parent_id][$key]['type_flag'] = $data['rel']['txn_type_flag'];
                $response[$data['rel']['source_id']][$parent_id][$key]['service_name'] = $data['s']['service_name'];
                $response[$data['rel']['source_id']]['source_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';
            }
        }
        
        $retailer_service_data = $this->Slaves->query('SELECT rel.user_id as source_id,rel.service_id,s.name as service_name,s.parent_id,rel.type,SUM(rel.amount-rel.txn_reverse_amt) AS ret_earning,s.inc_type_flag,d.user_id '
                                            . 'FROM users_nontxn_logs rel '
                                            . 'JOIN retailers r ON (rel.user_id = r.user_id) '
                                            . 'LEFT JOIN distributors d ON (r.user_id = d.user_id) '
                                            . 'LEFT JOIN services s ON (rel.service_id = s.id) '
                                            . 'WHERE 1 '
                                            . 'AND rel.date >= "'.$from_date.'" '
                                            . 'AND rel.date <= "'.$to_date.'" '
                                            . 'AND rel.type = '.REFUND.' '
//                                            . 'AND s.inc_type_flag = 1 '
                                            . 'GROUP BY rel.user_id,rel.service_id');
        
        foreach($retailer_service_data as $data)
        {
            if($data['rel']['source_id'] != $data['d']['user_id']){
                if($data[0]['ret_earning'] > 0){
                    $parent_id = $data['s']['parent_id'];
                    $key = $data['rel']['source_id'].'_'.$data['rel']['service_id'].'_'.REFUND.'_'.$data['s']['inc_type_flag'];            
                    $response[$data['rel']['source_id']][$parent_id][$key]['source_id'] = $data['rel']['source_id'];
                    $response[$data['rel']['source_id']][$parent_id][$key]['service_id'] = $data['rel']['service_id']; 
                    $response[$data['rel']['source_id']][$parent_id][$key]['ret_earning'] = $data[0]['ret_earning'];
                    $response[$data['rel']['source_id']][$parent_id][$key]['type'] = $data['rel']['type'];
                    $response[$data['rel']['source_id']][$parent_id][$key]['type_flag'] = $data['s']['inc_type_flag'];
                    $response[$data['rel']['source_id']][$parent_id][$key]['service_name'] = $data['s']['service_name'];
                    $response[$data['rel']['source_id']]['source_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';
                }
            }
        }    
        
        $get_retailer_state = $this->Invoice->getRetailerState();
        
        foreach($response as $ret_user_id=>$inv_data)
        {
            $source_id = $ret_user_id;
            $source_gst_no = (strlen($inv_data['source_gst_no']) < 15)?NULL:$inv_data['source_gst_no'];

            unset($inv_data['source_gst_no']);
            foreach ($inv_data as $parent_service_id=>$invoice_data)
            {
                if(!empty($invoice_data)){
                    $target_id = 0;
                    $target_gst_no = PAY1_GST_NO;
                    $state = isset($get_retailer_state[$source_id])?$get_retailer_state[$source_id]['state']:'Maharashtra';
                    $source_state = !empty($source_gst_no) ? $gst_state_code_mapping[substr($source_gst_no,0,2)] : $state;
                    $target_state = 'Maharashtra';
                    $hsn_no = 9984;

                    $invoicedate = date('Y-m-t', strtotime('-1 month'));
                    $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
                    $year = date('Y', strtotime($invoicedate));
                    $dataSource = $this->User->getDataSource();
                    $dataSource->begin();
                    $invoice_id= $this->Invoice->addTaxInvoice($parent_service_id,$source_id,$target_id,6,0,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year,0,$dataSource);

                    if($invoice_id)
                    {
                        foreach ($invoice_data as $data)
                        {
                            $service_id = $data['service_id'];
                            $desc = $data['type'] == REFUND && $data['type_flag'] == 1?'Referral Bonus against ':($data['type'] == REFUND && $data['type_flag'] == 0?'Incentive against ':($data['type'] == DEBIT_NOTE && $data['type_flag'] == 1?'Referral Fee against ':'Commission against '));
                            $description = $desc.$data['service_name'];
                            $payable_amt = $data['ret_earning'];
                            $invoice_type = 1;

                            if(strtolower($source_state) == strtolower($target_state))
                            {
                                $cgst = 9;
                                $sgst = 9;
                                $igst = 0;
                            }
                            else
                            {
                                $cgst = 0;
                                $sgst = 0;
                                $igst = 18;
                            }

                            $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description',0,'$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                            if(!$insert_tax_invoices_logs_data)
                            {
                                // Transaction rollback
                                $dataSource->rollback();
                                $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                                return $response;
                            }
                            $dataSource->commit();                    
                        }
                    }
                    else
                    {
                       $dataSource->rollback(); 
                    }
                }
            }
        }
    }

    function getRetToPay1InvoiceJuly() // july adjustments
    {
        $this->autoRender = false;
        Configure::load('product_config');
        $services=Configure::read('services');
        $invoiceDescriptions=Configure::read('invoiceDescriptions');

        $split_size = 100;
        $retList = array();
//        $retailers = $this->Slaves->query("SELECT distinct ret_user_id FROM retailer_earning_logs WHERE date >= '$from_date' AND date <= '$to_date' ");
//
//        foreach($retailers as $rets){
//            $retList[] = $rets['retailer_earning_logs']['ret_user_id'];
//        }
//
//        $retList = array_chunk($retList, $split_size);
//
//        foreach($retList as $rets)
//        {

            $retailer_gst_nos = $this->Documentmanagement->getLabelDescription(NULL,20);

            $get_retailer_data1 = "SELECT rel.ret_user_id as source_id,rel.service_id,rel.product_type,sum(rel.amount) as total_sale,sum(rel.earning) as ret_earning "
                . "FROM retailer_earning_logs rel "
                . "LEFT JOIN distributors d "
                . "ON (rel.dist_user_id=d.user_id) "
                . "WHERE rel.service_id IN (1,2) "
//                . "AND rel.ret_user_id IN (".implode(",",$rets).") "
                . "AND rel.product_type = 1 " //p2a
                . "AND d.id NOT IN (".SAAS_DISTS.") "
                . "AND rel.date >= '2017-07-05' "
                . "AND rel.date <= '2017-07-31' "
                . "GROUP BY rel.ret_user_id,rel.service_id ";

            $retailer_data1 = $this->Slaves->query($get_retailer_data1);

            $get_retailer_data2 = "SELECT rel.ret_user_id as source_id,rel.service_id,rel.product_type,sum(rel.amount) as total_sale,sum(rel.earning) as ret_earning "
                . "FROM retailer_earning_logs rel "
                . "LEFT JOIN distributors d "
                . "ON (rel.dist_user_id=d.user_id) "
                . "WHERE rel.service_id IN (1,2) "
//                . "AND rel.ret_user_id IN (".implode(",",$rets).") "
                . "AND rel.product_type = 1 " //p2a
                . "AND d.id NOT IN (".SAAS_DISTS.") "
                . "AND d.id NOT IN (72,101,183,249,279,285,312,323,409,417,428,452,485,487,575,840,908,934,1522,1540,1569,1706,2016,1883,2120) "
                . "AND rel.date = '2017-07-04' "
                . "GROUP BY rel.ret_user_id,rel.service_id ";

            $retailer_data2 = $this->Slaves->query($get_retailer_data2);

            $response = array();

            foreach($retailer_data1 as $data)
            {
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['source_id'] = $data['rel']['source_id'];
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['service_id'] = $data['rel']['service_id'];
                $response[$data['rel']['source_id']]['source_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['total_sale'] += $data[0]['total_sale'];
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_earning'] += $data[0]['ret_earning'];
            }

            foreach($retailer_data2 as $data)
            {
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['source_id'] = $data['rel']['source_id'];
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['service_id'] = $data['rel']['service_id'];
                $response[$data['rel']['source_id']]['source_gst_no'] = array_key_exists($data['rel']['source_id'], $retailer_gst_nos)?$retailer_gst_nos[$data['rel']['source_id']]:'';
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['total_sale'] += $data[0]['total_sale'];
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['ret_earning'] += $data[0]['ret_earning'];
            }

            $get_retailer_state = $this->Invoice->getRetailerState();

            $dataSource = $this->User->getDataSource();
            $dataSource->begin();

            foreach($response as $ret_user_id=>$invoice_data)
            {
                $source_id = $ret_user_id;
                $source_gst_no = (strlen($invoice_data['source_gst_no']) < 15)?NULL:$invoice_data['source_gst_no'];
                unset($invoice_data['source_gst_no']);
                $target_id = 0;
                $target_gst_no = PAY1_GST_NO;
                $source_state = $state = isset($get_retailer_state[$source_id])?$get_retailer_state[$source_id]['state']:'Maharashtra';;
                $target_state = 'Maharashtra';
                $hsn_no = 9984;

                $invoicedate = '2017-07-31';
                $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
                $year = date('Y', strtotime($invoicedate));

                $invoice_id= $this->Invoice->addTaxInvoice($source_id,$target_id,6,0,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year);

                if($invoice_id)
                {
                    $response_status_flag = 1; // success
                }
                else
                {
                    $response_status_flag = 0; // failure
                }

                foreach ($invoice_data as $service_id=>$data)
                {
                    $description = 'Commision for '.$invoiceDescriptions[$service_id];
                    $payable_amt = $data['ret_earning'];
                    $invoice_type = 1;

                    if(strtolower($source_state) == strtolower($target_state))
                    {
                        $cgst = 9;
                        $sgst = 9;
                        $igst = 0;
                    }
                    else
                    {
                        $cgst = 0;
                        $sgst = 0;
                        $igst = 18;
                    }

                    if($response_status_flag == 1)
                    {
                        $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$hsn_no','$description',0,'$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                        if(!$insert_tax_invoices_logs_data)
                        {
                            // Transaction rollback
                            $dataSource->rollback();
                            $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                            return $response;
                        }
                        $dataSource->commit();
                    }
                }
            }
//        }
    }

   /* function getDistToPay1InvoiceOld()
    {
        $this->autoRender = false;
//        $date = date('Y-m-d', strtotime('-1 month'));
        //        $date = date('Y-m-d', strtotime('-1 month'));
        $from_date = date('Y-m-01', strtotime('-1 month'));
        $to_date = date('Y-m-t', strtotime('-1 month'));
//        $from_date = date('Y-m-01');
//        $to_date = date('Y-m-t');
        Configure::load('product_config');
        $services=Configure::read('services');
        $invoiceDescriptions=Configure::read('invoiceDescriptions');
        $response = array();

//        $distributors = $this->Slaves->query("SELECT distinct distributor_id FROM distributors_logs WHERE month(date) = month('$date') AND year(date) = year('$date') AND distributor_id NOT IN (".SAAS_DISTS.") group by distributor_id HAVING sum(retailer_total_sale) > 0");
//        foreach($distributors as $dists){
//            $dist_id = $dists['distributors_logs']['distributor_id'];
//            $retailers = $this->Slaves->query("SELECT distinct retailer_id FROM retailers_logs left join retailers ON (retailers.id = retailer_id) WHERE retailers.parent_id = $dist_id AND month(date) = month('$date') AND year(date) = year('$date')");
//            $retList = array();
//            foreach($retailers as $rets){
//                $retList[] = $rets['retailers_logs']['retailer_id'];
//            }
//
//            $get_retailer_sale = "SELECT p.service_id,SUM(st.amount) as total_sale,p.service_id,d.user_id as source_id,d.state as source_state,d.gst_no as source_gst_no "
//                               . "FROM vendors_activations st USE INDEX (idx_ret_date) "
//                               . "LEFT JOIN products p "
//                               . "ON (st.product_id = p.id) "
//                               . "LEFT JOIN distributors d "
//                               . "ON (st.distributor_id=d.id) "
//                               . "WHERE 1 "
//                               . "AND st.status NOT IN (2,3) "
//                               . "AND st.retailer_id IN (".implode(",",$retList).") "
//                               . "AND month(st.date) = month('$date') "
//                               . "AND year(st.date) = year('$date') "
//                               . "GROUP BY d.user_id,p.service_id,month($date),year($date)";

            $get_retailer_sale = "SELECT rel.dist_user_id as source_id,rel.service_id,rel.type,d.state as source_state,d.gst_no as source_gst_no,SUM(rel.amount) as total_sale "
                               . "FROM retailer_earning_logs rel "
                               . "LEFT JOIN distributors d "
                               . "ON (rel.dist_user_id=d.user_id) "
                               . "WHERE 1 "
                               . "AND rel.service_id IN (1,2,4,5,6,7,12) "
                               . "AND d.id NOT IN (".SAAS_DISTS.") "
//                               . "AND st.status NOT IN (2,3) "
//                               . "AND st.retailer_id IN (".implode(",",$retList).") "
                               . "AND rel.date >= '$from_date' "
                               . "AND rel.date <= '$to_date' "
                               . "GROUP BY rel.dist_user_id,rel.service_id,rel.type ";

            $retailer_sale = $this->Slaves->query($get_retailer_sale);

            foreach($retailer_sale as $data)
            {
                $response[$data['rel']['source_id']]['source_id'] = $data['rel']['source_id'];
                $response[$data['rel']['source_id']]['source_gst_no'] = $data['d']['source_gst_no'];
                $response[$data['rel']['source_id']]['source_state'] = $data['d']['source_state'];
                $response[$data['rel']['source_id']][$data['rel']['service_id']]['service_id'] = $data['rel']['service_id'];
                if(!in_array($data['rel']['service_id'], array(6)))
                {
                    $response[$data['rel']['source_id']][$data['rel']['service_id']]['total_sale'] = $data[0]['total_sale'];
                }
                else
                {
                    if($data['rel']['type']==4)
                    {
                        $response[$data['rel']['source_id']][$data['rel']['service_id']]['total_sale'] = $data[0]['total_sale'];
                    }
                }
            }
//        }

        $get_incentives = "SELECT st.source_id,SUM(st.amount) as incentive,d.gst_no as source_gst_no,d.state as source_state " //source_id is userid
                        . "FROM shop_transactions st "
                        . "JOIN distributors d "
                        . "ON (st.source_id=d.user_id) "
                        . "WHERE st.type=".REFUND." "
                        . "AND d.id NOT IN (".SAAS_DISTS.") "
                        . "AND st.confirm_flag = 0 "        
                        . "AND st.date >= '$from_date' "
                        . "AND st.date <= '$to_date' "
                        . "GROUP BY st.source_id";

        $incentive_data = $this->Slaves->query($get_incentives);

        foreach ($incentive_data as $data)
        {
            $response[$data['st']['source_id']]['source_id'] = $data['st']['source_id'];
            $response[$data['st']['source_id']]['source_gst_no'] = $data['d']['source_gst_no'];
            $response[$data['st']['source_id']]['source_state'] = $data['d']['source_state'];
            $response[$data['st']['source_id']]['incentive'] = $data[0]['incentive'];
        }

        $response1 = $response;

        $dataSource = $this->User->getDataSource();
        $dataSource->begin();

        foreach ($response as $dist_id=>$invoice_data)
        {
            $source_gst_no = (strlen($invoice_data['source_gst_no']) < 15)?NULL:$invoice_data['source_gst_no'];
            $target_gst_no = PAY1_GST_NO;
            $source_state = $invoice_data['source_state'];
            $target_state = 'Maharashtra';
            $incentive = isset($invoice_data['incentive'])?$invoice_data['incentive']:0;
            unset($invoice_data['source_id'],$invoice_data['source_gst_no'],$invoice_data['source_state'],$invoice_data['incentive']);
            $hsn_no = 9971;
            $invoice_type = 1; //0 means discount model , 1 means commission model
            $invoicedate = date('Y-m-t', strtotime('-1 month'));
            $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
            $year = date('Y', strtotime($invoicedate));
            $description = $invoiceDescriptions[13];

            $invoice_id= $this->Invoice->addTaxInvoice($dist_id,0,5,0,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year);

            if($invoice_id)
            {
                $response_status_flag = 1; // success
            }
            else
            {
                $response_status_flag = 0; // failure
            }

            $dist_commission = 0;

            foreach ($invoice_data as $data)
            {
                $commission = ($data['total_sale']*$services[$data['service_id']]['variable'])/100;
                $dist_commission += $commission;
            }

            $total_dist_commission = $dist_commission + $incentive;
            $response1[$dist_id]['total_dist_commission'] = $total_dist_commission;

            if(strtolower($source_state) == strtolower($target_state))
            {
                $response1[$dist_id]['cgst'] =$cgst = 9;
                $response1[$dist_id]['sgst'] =$sgst = 9;
                $response1[$dist_id]['igst'] =$igst = 0;
            }
            else
            {
                $response1[$dist_id]['cgst'] =$cgst = 0;
                $response1[$dist_id]['sgst'] =$sgst = 0;
                $response1[$dist_id]['igst'] =$igst = 18;
            }

            if($response_status_flag == 1)
            {
                $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$hsn_no','$description',0,'$total_dist_commission','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                if(!$insert_tax_invoices_logs_data)
                {
                    // Transaction rollback
                    $dataSource->rollback();
                    $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                    return $response;
                }
                $dataSource->commit();
            }
        }

    }
    */
    function getDistToPay1Invoice($from_date = NULL,$to_date = NULL,$flag = 0)
    {
        $this->autoRender = false;
        $from_date = date('Y-m-01', strtotime('-1 month'));
        $to_date = date('Y-m-t', strtotime('-1 month'));
        
        $dist_comm_data = $this->Slaves->query('SELECT dl.user_id as source_id,dl.service_id,s.parent_id,s.name as service_name,dl.type,SUM(dl.amount-dl.txn_reverse_amt) AS amount,d.gst_no AS source_gst_no,d.state AS source_state '
                                            . 'FROM users_nontxn_logs dl '
                                            . 'JOIN distributors d ON (dl.user_id = d.user_id) '
                                            . 'LEFT JOIN services s ON (dl.service_id = s.id) '
                                            . 'WHERE 1 '
                                            . 'AND dl.date >= "'.$from_date.'" '
                                            . 'AND dl.date <= "'.$to_date.'" '
                                            . 'AND dl.type IN ('.COMMISSION_DISTRIBUTOR.','.REFUND.') '
                                            . 'AND d.id NOT IN ('.SAAS_DISTS.') '
                                            . 'GROUP BY dl.user_id,dl.service_id '
                                            . 'HAVING amount > 0');
        
        $response = array();
        
        foreach($dist_comm_data as $data)
        {
            $parent_id = $data['s']['parent_id'];
            $service_id = $data['dl']['service_id'];
            $response[$data['dl']['source_id']][$parent_id][$service_id]['source_id'] = $data['dl']['source_id'];
            $response[$data['dl']['source_id']]['source_gst_no'] = $data['d']['source_gst_no'];
            $response[$data['dl']['source_id']]['source_state'] = $data['d']['source_state'];
            $response[$data['dl']['source_id']][$parent_id][$service_id]['commission'] = $data[0]['amount'];
            $response[$data['dl']['source_id']][$parent_id][$service_id]['service_name'] = $data['s']['service_name'];
        }
        
        foreach ($response as $dist_id=>$inv_data)
        {
            $source_gst_no = (strlen($inv_data['source_gst_no']) < 15)?NULL:$inv_data['source_gst_no'];            
            $source_state = $inv_data['source_state'];            
            unset($inv_data['source_gst_no'],$inv_data['source_state']);
            foreach ($inv_data as $parent_service_id=>$invoice_data)
            {
                if(!empty($invoice_data))
                {
                    $target_state = 'Maharashtra';
                    $target_gst_no = PAY1_GST_NO;
                    $hsn_no = 9971;
                    $invoice_type = 1; //0 means discount model , 1 means commission model
                    $invoicedate = date('Y-m-t', strtotime('-1 month'));
                    $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
                    $year = date('Y', strtotime($invoicedate));                    
                    $dataSource = $this->User->getDataSource();
                    $dataSource->begin();
                    $invoice_id= $this->Invoice->addTaxInvoice($parent_service_id,$dist_id,0,5,0,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year,0,$dataSource);

                    if($invoice_id)
                    {
                        foreach ($invoice_data as $service_id=>$data) 
                        {
                            $total_dist_commission = $data['commission'];
                            $description = $data['service_name'].' Commission';
                            
                            if(strtolower($source_state) == strtolower($target_state))
                            {
                                $cgst = 9;
                                $sgst = 9;
                                $igst = 0;
                            }
                            else
                            {
                                $cgst = 0;
                                $sgst = 0;
                                $igst = 18;
                            }

                            $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description',0,'$total_dist_commission','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                            if(!$insert_tax_invoices_logs_data)
                            {
                                // Transaction rollback
                                $dataSource->rollback();
                                $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                                return $response;
                            }
                            $dataSource->commit();
                        }
                    }
                    else
                    {
                        $dataSource->rollback();
                    }
                }            
            }
        }
    }

    /*function getPay1ToDistInvoiceOld()
    {
        $this->autoRender = false;
        $from_date = date('Y-m-01', strtotime('-1 month'));
        $to_date = date('Y-m-t', strtotime('-1 month'));
        $invoiceDescriptions = Configure::read('invoiceDescriptions');

        $kit_charges = $this->Slaves->query("SELECT st.source_id,st.user_id as service_id,count(st.amount) as quantity,st.amount,SUM(st.amount) as payable_amt,SUM(st.discount_comission) as discount_comission,d.gst_no as target_gst_no,d.state as target_state " //source_id is for user_id and user_id is for service_id
                                        . "FROM shop_transactions st "
                                        . "JOIN distributors d "
                                        . "ON (st.source_id=d.user_id) "
                                        . "WHERE st.type=".KITCHARGE." "
                                        . "AND d.id NOT IN (".SAAS_DISTS.") "
                                        . "AND st.date >= '$from_date' "
                                        . "AND st.date <= '$to_date' "
                                        . "GROUP BY st.source_id,st.user_id");

        $response = array();

        foreach ($kit_charges as $data)
        {
            $response[$data['st']['source_id']]['target_gst_no'] = $data['d']['target_gst_no'];
            $response[$data['st']['source_id']]['target_state'] = $data['d']['target_state'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['target_id'] = $data['st']['source_id'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['amount'] = $data['st']['amount'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['service_id'] = $data['st']['service_id'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['quantity'] = $data[0]['quantity'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['payable_amt'] = $data[0]['payable_amt'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['discount_comission'] = $data[0]['discount_comission'];
//            $response[$data['st']['source_id']][$data['st']['service_id']]['total_amt'] = $data[0]['payable_amt']+$data[0]['discount_comission'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['total_amt'] = 0;
        }

        $dataSource = $this->User->getDataSource();
        $dataSource->begin();

        foreach ($response as $dist_id=>$invoice_data)
        {
            $source_id = 0;
            $target_id = $dist_id;
            $source_gst_no = PAY1_GST_NO;
            $target_gst_no = (strlen($invoice_data['target_gst_no']) < 15)?NULL:$invoice_data['target_gst_no'];
            $source_state = 'Maharashtra';
            $target_state = $invoice_data['target_state'];
            unset($invoice_data['target_gst_no'],$invoice_data['target_state']);
            $invoicedate = date('Y-m-t', strtotime('-1 month'));
            $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
            $year = date('Y', strtotime($invoicedate));

            $invoice_id= $this->Invoice->addTaxInvoice($source_id,$target_id,0,5,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year);

            if($invoice_id)
            {
                $response_status_flag = 1; // success
            }
            else
            {
                $response_status_flag = 0; // failure
            }

            foreach ($invoice_data as $data)
            {
                $hsn_no = 9971;
                $service_id = $data['service_id'];
                $invoice_type = in_array($service_id, array(1,2,5,7))?0:1; //0 means discount model , 1 means commission model
                $description = $invoiceDescriptions[15][$service_id];
                $payable_amt = $data['payable_amt'];
                $total_amt = $data['total_amt'];

                if($target_state == 'Maharashtra')
                {
                     $cgst = 9;
                     $sgst = 9;
                     $igst = 0;
                }
                else
                {
                     $cgst = 0;
                     $sgst = 0;
                     $igst = 18;
                }

                if($response_status_flag == 1)
                {
                    $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$hsn_no','$description',$total_amt,'$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                    if(!$insert_tax_invoices_logs_data)
                    {
                        // Transaction rollback
                        $dataSource->rollback();
                        $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                        return $response;
                    }
                    $dataSource->commit();
                }
            }
        }

    }*/
    function getPay1ToDistInvoice()
    {
        $this->autoRender = false;
        $from_date = date('Y-m-01', strtotime('-1 month'));
        $to_date = date('Y-m-t', strtotime('-1 month'));
                
        $kit_charges = $this->Slaves->query('SELECT dl.user_id as source_id,dl.service_id,s.name as service_name,s.parent_id,dl.type,SUM(if(dl.type != '.KITCHARGE.',dl.amount-dl.txn_reverse_amt,dl.amount)) AS amount,SUM(dl.txn_reverse_amt) AS txn_reverse_amt,d.gst_no as target_gst_no,d.state as target_state '
                                            . 'FROM users_nontxn_logs dl '
                                            . 'JOIN distributors d ON (dl.user_id = d.user_id) '
                                            . 'LEFT JOIN services s ON (dl.service_id = s.id) '
                                            . 'WHERE 1 '
                                            . 'AND dl.date >= "'.$from_date.'" '
                                            . 'AND dl.date <= "'.$to_date.'" '
                                            . 'AND dl.type IN ('.KITCHARGE.','.ONE_TIME_CHARGE.') '
                                            . 'AND d.id NOT IN ('.SAAS_DISTS.') '
                                            . 'GROUP BY dl.user_id,dl.service_id,dl.type');
                                   
        $response = array();

        foreach ($kit_charges as $data)
        {
            $response[$data['dl']['source_id']]['target_gst_no'] = $data['d']['target_gst_no'];
            $response[$data['dl']['source_id']]['target_state'] = $data['d']['target_state'];
            $payable_amt = $data[0]['amount'];
            $parent_id = $data['s']['parent_id'];
            if($payable_amt > 0){
                $key = $data['dl']['source_id'].'_'.$data['dl']['service_id'].'_'.$data['dl']['type'];
                $response[$data['dl']['source_id']][$parent_id][$key]['target_id'] = $data['dl']['source_id'];
                $response[$data['dl']['source_id']][$parent_id][$key]['service_id'] = $data['dl']['service_id'];
                $response[$data['dl']['source_id']][$parent_id][$key]['service_name'] = $data['s']['service_name'];
                $response[$data['dl']['source_id']][$parent_id][$key]['type'] = $data['dl']['type'];
                $response[$data['dl']['source_id']][$parent_id][$key]['payable_amt'] = $payable_amt;
                $response[$data['dl']['source_id']][$parent_id][$key]['total_amt'] = 0;
            }
        }
        
        foreach ($response as $dist_id=>$inv_data)
        {
            $source_id = 0;
            $target_id = $dist_id;
            $source_gst_no = PAY1_GST_NO;
            $target_gst_no = (strlen($inv_data['target_gst_no']) < 15)?NULL:$inv_data['target_gst_no'];
            $source_state = 'Maharashtra';
            $target_state = $inv_data['target_state'];
            unset($inv_data['target_gst_no'],$inv_data['target_state']);
            foreach ($inv_data as $parent_service_id=>$invoice_data)
            {
                if(!empty($invoice_data))
                {
                    $invoicedate = date('Y-m-t', strtotime('-1 month'));
                    $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
                    $year = date('Y', strtotime($invoicedate));
                    $dataSource = $this->User->getDataSource();
                    $dataSource->begin();
                    $invoice_id = $this->Invoice->addTaxInvoice($parent_service_id,$source_id,$target_id,0,5,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year,0,$dataSource);

                    if($invoice_id)
                    {
                        foreach ($invoice_data as $data)
                        {
                            $hsn_no = 9971;
                            $service_id = $data['service_id'];
                            $invoice_type = 1; //0 means discount model , 1 means commission model
                            $description = $data['type'] == KITCHARGE ? $data['service_name'].' Setup Cost':($data['type'] == ONE_TIME_CHARGE ? $data['service_name'].' Service Fee':'');
                            $payable_amt = $data['payable_amt'];
                            $total_amt = $data['total_amt'];

                            if($target_state == 'Maharashtra')
                            {
                                 $cgst = 9;
                                 $sgst = 9;
                                 $igst = 0;
                            }
                            else
                            {
                                 $cgst = 0;
                                 $sgst = 0;
                                 $igst = 18;
                            }

                            $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description',$total_amt,'$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                            if(!$insert_tax_invoices_logs_data)
                            {
                                // Transaction rollback
                                $dataSource->rollback();
                                $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                                return $response;
                            }
                            $dataSource->commit();
                        }
                    }
                    else
                    {
                        $dataSource->rollback();
                    }
                }
            }            
        }
    }

   /* function getPay1ToDistInvoice2($user_id=null)
    {
        $this->autoRender = false;
        $date = '2017-07-01';
        $invoiceDescriptions = Configure::read('invoiceDescriptions');
        $str = (empty($user_id)) ? "1" : "d.user_id = $user_id";

        $kit_charges = $this->Slaves->query("SELECT st.source_id,st.user_id as service_id,count(st.amount) as quantity,st.amount,SUM(st.amount) as total_amt,SUM(st.discount_comission) as discount_comission,d.gst_no as target_gst_no,d.state as target_state " //source_id is for user_id and user_id is for service_id
                                        . "FROM shop_transactions st "
                                        . "JOIN distributors d "
                                        . "ON (st.source_id=d.user_id) "
                                        . "WHERE $str "
                                        . "AND d.id NOT IN (".SAAS_DISTS.") "
                                        . "AND st.type=".KITCHARGE." "
                                        . "AND month(st.date) = month('$date') "
                                        . "AND year(st.date) = year('$date') "
                                        . "GROUP BY st.source_id,st.user_id,month($date),year($date)");

        $response = array();
        $dataSource = $this->User->getDataSource();
        $dataSource->begin();


        foreach ($kit_charges as $data)
        {
            $source_id = 0;
            $source_gst_no = PAY1_GST_NO;
            $target_gst_no = (strlen($data['d']['target_gst_no']) < 15)?NULL:$data['d']['target_gst_no'];
            $source_state = 'Maharashtra';
            $target_state = $data['d']['target_state'];
            $hsn_no = 9971;
            $invoice_type = in_array($data['st']['service_id'], array(1,2,5,7))?0:1; //0 means discount model , 1 means commission model
            $description = $invoiceDescriptions[15][$data['st']['service_id']];
            $invoicedate = '2017-07-31';
            $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
            $year = date('Y', strtotime($invoicedate));

            $response[$data['st']['source_id']][$data['st']['service_id']]['target_id'] = $target_id = $data['st']['source_id'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['amount'] = $data['st']['amount'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['service_id'] = $service_id = $data['st']['service_id'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['quantity'] = $data[0]['quantity'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['payable_amt'] = $payable_amt = $data[0]['total_amt'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['discount_comission'] = $data[0]['discount_comission'];
            $response[$data['st']['source_id']][$data['st']['service_id']]['total_amt'] = $total_amt = $data[0]['total_amt']+$data[0]['discount_comission'];
            //$state = $data['d']['target_state'];

            if($target_state == 'Maharashtra')
            {
                 $cgst = 9;
                 $sgst = 9;
                 $igst = 0;
            }
            else
            {
                 $cgst = 0;
                 $sgst = 0;
                 $igst = 18;
            }

            $invoice_id= $this->Invoice->addTaxInvoice($source_id,$target_id,0,5,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year);
            //$insert_tax_invoices_data = $dataSource->query("INSERT INTO tax_invoices(source_id,target_id,source_group_id,target_group_id,source_gst_no,target_gst_no,source_state,target_state,invoice_date,month,year)VALUES('$source_id','$target_id',0,5,'$source_gst_no','$target_gst_no','$source_state','$target_state','$invoicedate','$month','$year')");

            if($invoice_id)
            {
                $response_status_flag = 1; // success
            }
            else
            {
                $response_status_flag = 0; // failure
            }

            if($response_status_flag == 1)
            {
                $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$hsn_no','$description',$total_amt,'$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                if(!$insert_tax_invoices_logs_data)
                {
                    // Transaction rollback
                    $dataSource->rollback();
                    $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                    return $response;
                }
                $dataSource->commit();
            }
        }

    }

    function getDistToPay1Invoice1() //june adjustments
    {
        $this->autoRender = false;
        $date = '2017-06-01';
        Configure::load('product_config');
        $services=Configure::read('services');
        $invoiceDescriptions=Configure::read('invoiceDescriptions');

        //adjustments in june invoice
        $get_retailer_sale1 = "SELECT p.service_id,SUM(st.amount) as total_sale,SUM(st.retailer_margin) as ret_commission,p.service_id,d.id as source_id,d.state as source_state,d.gst_no as source_gst_no "
                . "FROM vendors_activations st USE INDEX (idx_ret_date) "
                . "LEFT JOIN products p "
                . "ON (st.product_id = p.id) "
                . "LEFT JOIN distributors d "
                . "ON (st.distributor_id=d.id) "
                . "WHERE 1 "
                . "AND d.id NOT IN (".SAAS_DISTS.")"
                . "AND st.status NOT IN (2,3) "
                . "AND st.date = '2017-06-30' "
                . "GROUP BY d.id,p.service_id ";

        $get_retailer_sale2 = "SELECT p.service_id,SUM(st.retailer_margin) as ret_commission,p.service_id,d.id as source_id,d.state as source_state,d.gst_no as source_gst_no "
                . "FROM vendors_activations st USE INDEX (idx_ret_date) "
                . "LEFT JOIN products p "
                . "ON (st.product_id = p.id) "
                . "LEFT JOIN distributors d "
                . "ON (st.distributor_id=d.id) "
                . "WHERE 1 "
                . "AND d.id NOT IN (".SAAS_DISTS.")"
                . "AND st.status NOT IN (2,3) "
                . "AND st.date = '2017-07-04' "
                . "AND d.user_id IN (59311490,34932703,4313626,514981,5085939,16378083,79696922,5077915,24785348,53304,57743811,19818005,37886164,12167291,15950032,76625286,15453138,8689169,9337675,65485741,17972026,37040514,4164751,3307302) "
                . "AND p.service_id IN (1,2) "
                . "GROUP BY d.id,p.service_id ";

        $get_retailer_sale3 = "SELECT p.service_id,SUM(st.amount) as total_sale,SUM(st.retailer_margin) as ret_commission,p.service_id,d.id as source_id,d.state as source_state,d.gst_no as source_gst_no "
                             . "FROM vendors_activations st USE INDEX (idx_ret_date) "
                             . "LEFT JOIN products p "
                             . "ON (st.product_id = p.id) "
                             . "LEFT JOIN distributors d "
                             . "ON (st.distributor_id=d.id) "
                             . "WHERE 1 "
                             . "AND d.id NOT IN (".SAAS_DISTS.")"
                             . "AND st.status NOT IN (2,3) "
                             . "AND st.date >= '2017-07-01' "
                             . "AND st.date <= '2017-07-03' "
                             . "AND p.service_id IN (1,2) "
                             . "GROUP BY d.id,p.service_id ";

        $retailer_sale1 = $this->Slaves->query($get_retailer_sale1);
        $retailer_sale2 = $this->Slaves->query($get_retailer_sale2);
        $retailer_sale3 = $this->Slaves->query($get_retailer_sale3);
        $response = array();

        //4th july sale adjustment in dist_id => total sale format
        $sale_adjustment_array = array(1569=>313115,
                                        840=>299009,
                                        575=>296862,
                                        72=>272380,
                                        285=>270716,
                                        428=>243359,
                                        2120=>240208,
                                        183=>230661,
                                        1540=>221772,
                                        101=>216074,
                                        1522=>213232,
                                        487=>211096,
                                        934=>201775,
                                        323=>196819,
                                        417=>192386,
                                        2016=>190178,
                                        409=>181178,
                                        279=>179846,
                                        485=>179233,
                                        1706=>178173,
                                        452=>177879,
                                        908=>177046,
                                        312=>176982,
                                        249=>176352
                                    );


        foreach($retailer_sale1 as $data)
        {
            $response[$data['d']['source_id']]['source_id'] = $data['d']['source_id'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['service_id'] = $data['p']['service_id'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['total_sale'] += $data[0]['total_sale'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['ret_commission'] += $data[0]['ret_commission'];
        }

        foreach($retailer_sale2 as $data)
        {
            $response[$data['d']['source_id']]['source_id'] = $data['d']['source_id'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['service_id'] = $data['p']['service_id'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['ret_commission'] += $data[0]['ret_commission'];
            $response[$data['d']['source_id']]['adjusted_sale'] = $sale_adjustment_array[$data['d']['source_id']];
            $response[$data['d']['source_id']]['adjusted_sale_comm'] = ($sale_adjustment_array[$data['d']['source_id']]*0.5)/100;
        }

        foreach($retailer_sale3 as $data)
        {
            $response[$data['d']['source_id']]['source_id'] = $data['d']['source_id'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['service_id'] = $data['p']['service_id'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['total_sale'] += $data[0]['total_sale'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['ret_commission'] += $data[0]['ret_commission'];
        }

        $this->General->logData("/mnt/logs/june_taxinvoice.txt",date('Y-m-d H:i:s')." :: June invoice data : <br/>START <br/>".json_encode($response)." <br/>END");

        foreach ($response as $dist_id=>$invoice_data)
        {
            $dist_id = $invoice_data['source_id'];
            $adjusted_sale = isset($invoice_data['adjusted_sale'])?$invoice_data['adjusted_sale']:0;
            $adjusted_sale_comm = isset($invoice_data['adjusted_sale_comm'])?$invoice_data['adjusted_sale_comm']:0;
            unset($invoice_data['source_id'],$invoice_data['adjusted_sale'],$invoice_data['adjusted_sale_comm']);
            $invoice_type = 1; //0 means discount model , 1 means commission model
            $invoicedate = '2017-06-30';

            $dist_commission = 0;
            $total_sale = 0;
            $ret_commission = 0;
            foreach ($invoice_data as $data)
            {
                $total_sale += $data['total_sale'];
                $commission = ($data['total_sale']*$services[$data['service_id']]['variable'])/100;
                $dist_commission += $commission;
                $ret_commission += $data['ret_commission'];
            }
            $total_sale_amt = $total_sale + $adjusted_sale;
            $total_commission = $dist_commission + $adjusted_sale_amt + $ret_commission;
            $response1[$dist_id]['total_dist_commission'] = $total_commission;
            $query = "insert into invoices_data(distributor_id,topup_buy,earning,invoice_date,yearmonth_id,invoice_type)values('$dist_id','$total_sale_amt',$total_commission,'$invoicedate','".date('Ym', strtotime($invoicedate))."','1')";
            $this->General->logData("/mnt/logs/june_taxinvoice.txt",date('Y-m-d H:i:s')." :: June invoice query : ".$query);
            $insert_tax_invoices_data = $this->User->query("insert into invoices_data(distributor_id,topup_buy,earning,invoice_date,yearmonth_id,invoice_type)values('$dist_id','$total_sale_amt',$total_commission,'$invoicedate','".date('Ym', strtotime($invoicedate))."','1')");

        }

    }

    function getDistToPay1Invoice2($user_id=null) //july
    {
        $this->autoRender = false;
        $date = '2017-07-01';
        Configure::load('product_config');
        $services=Configure::read('services');
        $invoiceDescriptions=Configure::read('invoiceDescriptions');

        $str = (empty($user_id)) ? "1" : "d.user_id = $user_id";

        $response = array();

        $distributors = $this->Slaves->query("SELECT distinct distributor_id FROM distributors_logs left join distributors as d ON (d.id = distributor_id) WHERE $str AND month(date) = month('$date') AND year(date) = year('$date') AND distributor_id NOT IN (".SAAS_DISTS.") group by distributor_id");
        foreach($distributors as $dists){
            $dist_id = $dists['distributors_logs']['distributor_id'];
            $retailers = $this->Slaves->query("SELECT distinct retailer_id FROM retailers_logs left join retailers ON (retailers.id = retailer_id) WHERE retailers.parent_id = $dist_id AND month(date) = month('$date') AND year(date) = year('$date')");
            $retList = array();
            foreach($retailers as $rets){
                $retList[] = $rets['retailers_logs']['retailer_id'];
            }
            //adjustments in july invoice
            $get_retailer_sale1 = "SELECT p.service_id,SUM(st.amount) as total_sale,p.service_id,d.user_id as source_id,d.state as source_state,d.gst_no as source_gst_no "
                . "FROM vendors_activations st USE INDEX (idx_ret_date) "
                . "LEFT JOIN products p "
                . "ON (st.product_id = p.id) "
                . "LEFT JOIN distributors d "
                . "ON (st.distributor_id=d.id) "
                . "WHERE 1 "
                . "AND st.retailer_id IN (".implode(",",$retList).") "
                . "AND st.status NOT IN (2,3) "
                . "AND st.date >= '2017-07-05' "
                . "AND st.date <= '2017-07-31' "
                . "GROUP BY d.user_id,p.service_id,month('2017-07-01'),year('2017-07-01') ";

            $retailer_sale1 = $this->Slaves->query($get_retailer_sale1);

            foreach($retailer_sale1 as $data)
            {
                $response[$data['d']['source_id']]['source_id'] = $data['d']['source_id'];
                $response[$data['d']['source_id']]['source_gst_no'] = $data['d']['source_gst_no'];
                $response[$data['d']['source_id']]['source_state'] = $data['d']['source_state'];
                $response[$data['d']['source_id']][$data['p']['service_id']]['service_id'] = $data['p']['service_id'];
                $response[$data['d']['source_id']][$data['p']['service_id']]['total_sale'] += $data[0]['total_sale'];
            }

        }

        $get_retailer_sale2 = "SELECT p.service_id,SUM(st.amount) as total_sale,p.service_id,d.user_id as source_id,d.state as source_state,d.gst_no as source_gst_no "
                            . "FROM vendors_activations st "
                            . "LEFT JOIN products p "
                            . "ON (st.product_id = p.id) "
                            . "LEFT JOIN distributors d "
                            . "ON (st.distributor_id=d.id) "
                            . "WHERE $str "
                            . "AND st.status NOT IN (2,3) "
                            . "AND st.date = '2017-07-04' "
                            . "AND p.service_id IN (1,2) "
                            . "AND d.id NOT IN (".SAAS_DISTS.") "
                            . "AND d.user_id NOT IN (59311490,34932703,4313626,514981,5085939,16378083,79696922,5077915,24785348,53304,57743811,19818005,37886164,12167291,15950032,76625286,15453138,8689169,9337675,65485741,17972026,37040514,4164751,3307302) AND p.service_id IN (1,2) "
                            . "GROUP BY d.user_id,p.service_id ";

        $get_retailer_sale3 = "SELECT p.service_id,SUM(st.amount) as total_sale,p.service_id,d.user_id as source_id,d.state as source_state,d.gst_no as source_gst_no "
                             . "FROM vendors_activations st "
                             . "LEFT JOIN products p "
                             . "ON (st.product_id = p.id) "
                             . "LEFT JOIN distributors d "
                             . "ON (st.distributor_id=d.id) "
                             . "WHERE $str "
                             . "AND d.id NOT IN (".SAAS_DISTS.") "
                             . "AND st.status NOT IN (2,3) "
                             . "AND st.date >= '2017-07-01' "
                             . "AND st.date <= '2017-07-04' "
                             . "AND p.service_id NOT IN (1,2) "
                             . "GROUP BY d.user_id,p.service_id,month('2017-07-01'),year('2017-07-01') ";

        $retailer_sale2 = $this->Slaves->query($get_retailer_sale2);                                                                                                                                   $retailer_sale3 = $this->Slaves->query($get_retailer_sale3);


        foreach($retailer_sale2 as $data)
        {
            $response[$data['d']['source_id']]['source_id'] = $data['d']['source_id'];
            $response[$data['d']['source_id']]['source_gst_no'] = $data['d']['source_gst_no'];
            $response[$data['d']['source_id']]['source_state'] = $data['d']['source_state'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['service_id'] = $data['p']['service_id'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['total_sale'] += $data[0]['total_sale'];
        }

        foreach($retailer_sale3 as $data)
        {
            $response[$data['d']['source_id']]['source_id'] = $data['d']['source_id'];
            $response[$data['d']['source_id']]['source_gst_no'] = $data['d']['source_gst_no'];
            $response[$data['d']['source_id']]['source_state'] = $data['d']['source_state'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['service_id'] = $data['p']['service_id'];
            $response[$data['d']['source_id']][$data['p']['service_id']]['total_sale'] += $data[0]['total_sale'];
        }

        $debit_note = "SELECT st.user_id as service_id,sum(st.amount) as total_sale,d.user_id as source_id,d.state as source_state,d.gst_no as source_gst_no "
                    . "FROM shop_transactions st "
                    . "JOIN retailers r "
                    . "ON (st.source_id=r.user_id) "
                    . "JOIN distributors d "
                    . "ON (r.parent_id=d.id) "
                    . "WHERE $str "
                    . "AND d.id NOT IN (".SAAS_DISTS.") "
                    . "AND st.type=".DEBIT_NOTE." "
                    . "AND month(st.date) = month('$date') "
                    . "AND year(st.date) = year('$date') "
                    . "AND st.confirm_flag = 0 "
                    . "AND st.user_id=12 "
                    . "GROUP BY d.user_id,st.user_id,month($date),year($date)";

        $dmt_invoice_data = $this->Slaves->query($debit_note);

        foreach ($dmt_invoice_data as $data)
        {
            $response[$data['d']['source_id']]['source_id'] = $data['d']['source_id'];
            $response[$data['d']['source_id']]['source_gst_no'] = $data['d']['source_gst_no'];
            $response[$data['d']['source_id']]['source_state'] = $data['d']['source_state'];
            $response[$data['d']['source_id']][$data['st']['service_id']]['service_id'] = $data['st']['service_id'];
            $response[$data['d']['source_id']][$data['st']['service_id']]['total_sale'] = $data[0]['total_sale'];
        }

        $get_incentives = "SELECT st.source_id,SUM(st.amount) as incentive,d.gst_no as source_gst_no,d.state as source_state " //source_id is userid
                        . "FROM shop_transactions st "
                        . "JOIN distributors d "
                        . "ON (st.source_id=d.user_id) "
                        . "WHERE $str "
                        . "AND d.id NOT IN (".SAAS_DISTS.") "
                        . "AND st.type=".REFUND." "
                        . "AND st.confirm_flag = 0 "        
                        . "AND month(st.date) = month('$date') "
                        . "AND year(st.date) = year('$date') "
                        . "GROUP BY st.source_id,month($date),year($date)";

        $incentive_data = $this->Slaves->query($get_incentives);

        foreach ($incentive_data as $data)
        {
            $response[$data['st']['source_id']]['source_id'] = $data['st']['source_id'];
            $response[$data['st']['source_id']]['source_gst_no'] = $data['d']['source_gst_no'];
            $response[$data['st']['source_id']]['source_state'] = $data['d']['source_state'];
            $response[$data['st']['source_id']]['incentive'] = $data[0]['incentive'];
        }

        $response1 = $response;

        $dataSource = $this->User->getDataSource();
        $dataSource->begin();

        foreach ($response as $dist_id=>$invoice_data)
        {
            //$dist_id = $invoice_data['source_id'];
            $source_gst_no = (strlen($invoice_data['source_gst_no']) < 15)?NULL:$invoice_data['source_gst_no'];
            $target_gst_no = PAY1_GST_NO;
            $source_state = $invoice_data['source_state'];
            $target_state = 'Maharashtra';
            $incentive = isset($invoice_data['incentive'])?$invoice_data['incentive']:0;
            unset($invoice_data['source_id'],$invoice_data['source_gst_no'],$invoice_data['source_state'],$invoice_data['incentive']);
            $hsn_no = 9971;
            $invoice_type = 1; //0 means discount model , 1 means commission model
            $invoicedate = '2017-07-31';
            $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
            $year = date('Y', strtotime($invoicedate));
            $description = $invoiceDescriptions[13];
            //$dataSource = $this->User->getDataSource();
            //$dataSource->begin();

            $invoice_id= $this->Invoice->addTaxInvoice($dist_id,0,5,0,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year);

            //$insert_tax_invoices_data = $dataSource->query("INSERT INTO tax_invoices(source_id,target_id,source_group_id,target_group_id,source_gst_no,target_gst_no,source_state,target_state,invoice_date,month,year)VALUES('$dist_id','$ret_user_id',5,0,'$source_gst_no','$target_gst_no','$source_state','$target_state','$invoicedate','$month','$year')");

            if($invoice_id)
            {
                //$lastInserIdQuery = $dataSource->query("SELECT LAST_INSERT_ID() as id FROM tax_invoices limit 1 ");
                //$invoice_id = $lastInserIdQuery[0][0]['id'];
                $response_status_flag = 1; // success
            }
            else
            {
                $response_status_flag = 0; // failure
            }

            $dist_commission = 0;

            foreach ($invoice_data as $data)
            {
//                $total_sale = $data['total_sale'] + $data['total_sale1'] + $data['total_sale2'] + $data['total_sale3'];
                $total_sale = $data['total_sale'];
                $commission = ($total_sale*$services[$data['service_id']]['variable'])/100;
                $dist_commission += $commission;
            }

            $total_dist_commission = $dist_commission + $incentive;
            $response1[$dist_id]['total_dist_commission'] = $total_dist_commission;

            if(strtolower($source_state) == strtolower($target_state))
            {
                $response1[$dist_id]['cgst'] =$cgst = 9;
                $response1[$dist_id]['sgst'] =$sgst = 9;
                $response1[$dist_id]['igst'] =$igst = 0;
            }
            else
            {
                $response1[$dist_id]['cgst'] =$cgst = 0;
                $response1[$dist_id]['sgst'] =$sgst = 0;
                $response1[$dist_id]['igst'] =$igst = 18;
            }

            if($response_status_flag == 1)
            {
                $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$hsn_no','$description',0,'$total_dist_commission','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                if(!$insert_tax_invoices_logs_data)
                {
                    // Transaction rollback
                    $dataSource->rollback();
                    $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                    return $response;
                }
                $dataSource->commit();
            }

        }

    }*/

    function getPay1ToSupplierP2AInvoice()
    {
        $this->autoRender = false;

        $from_date = date('Y-m-01', strtotime('-1 month'));
        $to_date = date('Y-m-t', strtotime('-1 month'));

        $services_details = $this->Serviceintegration->getServiceDetails();
        $services_details = json_decode($services_details,true);
        $gst_state_code_mapping = Configure::read('gst_state_code_mapping');

        $modem_vendor_query = "SELECT group_concat(distinct(o.order_date)),s.id as supplier_id,s.gst_no,o.supplier_operator_id,s.name as supplier_name,p.name as operator_name,p.service_id,p.type,s.gst_no,"
                . "if(s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra'),'Maharashtra',if(s.location IN ('f','mh','ss','sss','Test','Testing') or s.location is null or s.location = ' ','Other',s.location)) as location,"
                . "so.commission_type_formula,sum(amount) as order_amt,sum(to_pay) as to_pay "
                . "FROM inv_orders o "
                . "JOIN inv_supplier_operator so ON (so.id = o.supplier_operator_id) "
                . "JOIN inv_suppliers s ON (s.id = so.supplier_id) "
                . "JOIN products p ON (p.id = so.operator_id) "
                . "WHERE o.order_date >= '$from_date' "
                . "AND o.order_date <= '$to_date' "
                . "AND o.is_payment_done = '1' "
                . "AND p.type = 1 "
                . "GROUP BY s.id,p.id "
                . "ORDER BY s.id,p.id";

        $modem_vendor_data = $this->Slaves->query($modem_vendor_query);

        $response = array();

        foreach ($modem_vendor_data as $data) {
            $response[$data['s']['supplier_id']][$data['p']['service_id']]['target_id'] = $data['s']['supplier_id'];
            $response[$data['s']['supplier_id']][$data['p']['service_id']]['service_id'] = $data['p']['service_id'];
            $response[$data['s']['supplier_id']]['target_gst_no'] = $data['s']['gst_no'];
            $response[$data['s']['supplier_id']]['target_state'] = $data[0]['location'];
            $response[$data['s']['supplier_id']][$data['p']['service_id']]['total_sale'] += $data[0]['to_pay'];
//            $response[$data['s']['supplier_id']][$data['p']['service_id']]['commission_type_formula'] = $data['so']['commission_type_formula'];
            $response[$data['s']['supplier_id']][$data['p']['service_id']]['commission'] += ($data[0]['to_pay'] * $data['so']['commission_type_formula'])/100;
        }

        $api_vendor_query = "SELECT avs.supplier_id,avs.service_id,sum(avs.sale) as sale,so.commission_type_formula,s.gst_no,"
                . "if(s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra'),'Maharashtra',if(s.location IN ('f','mh','ss','sss','Test','Testing') or s.location is null or s.location = ' ','Other',s.location)) as location "
                . "FROM api_vendors_sale_data avs "
                . "JOIN inv_supplier_operator so ON (avs.supplier_id = so.supplier_id AND avs.product_id = so.operator_id) "
                . "JOIN inv_suppliers s ON (s.id = avs.supplier_id) "
                . "WHERE avs.date >= '$from_date' "
                . "AND avs.date <= '$to_date' "
                . "AND avs.product_type = '1' "
                . "GROUP BY avs.supplier_id,avs.product_id "
                . "ORDER BY avs.supplier_id,avs.product_id";

        $api_vendor_data = $this->Slaves->query($api_vendor_query);

        foreach ($api_vendor_data as $data) {
            $response[$data['avs']['supplier_id']][$data['avs']['service_id']]['target_id'] = $data['avs']['supplier_id'];
            $response[$data['avs']['supplier_id']][$data['avs']['service_id']]['service_id'] = $data['avs']['service_id'];
            $response[$data['avs']['supplier_id']]['target_gst_no'] = $data['s']['gst_no'];
            $response[$data['avs']['supplier_id']]['target_state'] = $data[0]['location'];
            $response[$data['avs']['supplier_id']][$data['avs']['service_id']]['total_sale'] += $data[0]['sale'];
//            $response[$data['avs']['supplier_id']][$data['avs']['service_id']]['commission_type_formula'] = $data['so']['commission_type_formula'];
            $response[$data['avs']['supplier_id']][$data['avs']['service_id']]['commission'] += ($data[0]['sale'] * $data['so']['commission_type_formula'])/100;
        }

        foreach ($response as $supp_id => $invoice_data) {
            $source_gst_no = PAY1_GST_NO;
            $target_gst_no = (strlen($invoice_data['target_gst_no']) < 15)?NULL:$invoice_data['target_gst_no'];
            $state = $invoice_data['target_state'];
            unset($invoice_data['target_gst_no'],$invoice_data['target_state']);
            if(!empty($invoice_data))
            {  
                $dataSource = $this->User->getDataSource();
                $dataSource->begin();
                $source_state = 'Maharashtra';
                $invoicedate = date('Y-m-t', strtotime('-1 month'));
                $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
                $year = date('Y', strtotime($invoicedate));
                $target_state = !empty($target_gst_no) ? $gst_state_code_mapping[substr($target_gst_no,0,2)] : $state;
                $hsn_no = 9984;

                $invoice_id= $this->Invoice->addTaxInvoice(0,0,$supp_id,0,9,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year,0,$dataSource);

                if($invoice_id)
                {
                    foreach ($invoice_data as $service_id=>$data)
                    {
                        $description = 'Commision for '.$services_details[$service_id]['name'];
                        $payable_amt = $data['commission'];
                        $invoice_type = 1;

                        if(strtolower($source_state) == strtolower($target_state))
                        {
                            $cgst = 9;
                            $sgst = 9;
                            $igst = 0;
                        }
                        else
                        {
                            $cgst = 0;
                            $sgst = 0;
                            $igst = 18;
                        }
                        
                        $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$hsn_no','$description',0,'$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                        if(!$insert_tax_invoices_logs_data)
                        {
                            // Transaction rollback
                            $dataSource->rollback();
                            $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                            return $response;
                        }
                        $dataSource->commit();                        
                    }
                }
                else
                {
                    $dataSource->rollback();
                }
            }           
        }
    }
    
    function getPay1ToDistSalesReturnInvoices(){
        $this->autoRender = false;

        $from_date = date('Y-m-01', strtotime('-1 month'));
        $to_date = date('Y-m-t', strtotime('-1 month'));
        
        $kit_charges = $this->Slaves->query('SELECT dl.user_id as source_id,dl.service_id,s.name as service_name,s.parent_id,dl.type,SUM(dl.txn_reverse_amt) AS sale_return_amt,d.gst_no as target_gst_no,d.state as target_state '
                                            . 'FROM users_nontxn_logs dl '
                                            . 'JOIN distributors d ON (dl.user_id = d.user_id) '
                                            . 'LEFT JOIN services s ON (dl.service_id = s.id) '
                                            . 'WHERE 1 '
                                            . 'AND dl.date >= "'.$from_date.'" '
                                            . 'AND dl.date <= "'.$to_date.'" '
                                            . 'AND dl.type = '.KITCHARGE.' '
                                            . 'AND d.id NOT IN ('.SAAS_DISTS.') '
                                            . 'GROUP BY dl.user_id,dl.service_id,dl.type '
                                            . 'HAVING SUM(dl.txn_reverse_amt) > 0');
                                   
        $response = array();

        foreach ($kit_charges as $data)
        {
            $response[$data['dl']['source_id']]['target_gst_no'] = $data['d']['target_gst_no'];
            $response[$data['dl']['source_id']]['target_state'] = $data['d']['target_state'];
            $payable_amt = $data[0]['sale_return_amt'];
            $parent_id = $data['s']['parent_id'];
            if($payable_amt > 0){
                $key = $data['dl']['source_id'].'_'.$data['dl']['service_id'].'_'.$data['dl']['type'];
                $response[$data['dl']['source_id']][$parent_id][$key]['target_id'] = $data['dl']['source_id'];
                $response[$data['dl']['source_id']][$parent_id][$key]['service_id'] = $data['dl']['service_id'];
                $response[$data['dl']['source_id']][$parent_id][$key]['service_name'] = $data['s']['service_name'];
                $response[$data['dl']['source_id']][$parent_id][$key]['type'] = $data['dl']['type'];
                $response[$data['dl']['source_id']][$parent_id][$key]['payable_amt'] = $payable_amt;
                $response[$data['dl']['source_id']][$parent_id][$key]['total_amt'] = 0;
            }
        }
        
        foreach ($response as $dist_id=>$inv_data)
        {
            $source_id = 0;
            $target_id = $dist_id;
            $source_gst_no = PAY1_GST_NO;
            $target_gst_no = (strlen($inv_data['target_gst_no']) < 15)?NULL:$inv_data['target_gst_no'];
            $source_state = 'Maharashtra';
            $target_state = $inv_data['target_state'];
            unset($inv_data['target_gst_no'],$inv_data['target_state']);
            foreach ($inv_data as $parent_service_id=>$invoice_data)
            {
                if(!empty($invoice_data))
                {
                    $invoicedate = date('Y-m-t', strtotime('-1 month'));
                    $month = str_pad(date('m', strtotime($invoicedate)), 2, "0", STR_PAD_LEFT);
                    $year = date('Y', strtotime($invoicedate));
                    $dataSource = $this->User->getDataSource();
                    $dataSource->begin();
                    $invoice_id = $this->Invoice->addTaxInvoice($parent_service_id,$source_id,$target_id,0,5,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year,1,$dataSource);

                    if($invoice_id)
                    {
                        foreach ($invoice_data as $data)
                        {
                            $hsn_no = 9971;
                            $service_id = $data['service_id'];
                            $invoice_type = 1; //0 means discount model , 1 means commission model
                            $description = 'Kit Refund against '. $data['service_name'];
                            $payable_amt = $data['payable_amt'];
                            $total_amt = $data['total_amt'];

                            if($target_state == 'Maharashtra')
                            {
                                 $cgst = 9;
                                 $sgst = 9;
                                 $igst = 0;
                            }
                            else
                            {
                                 $cgst = 0;
                                 $sgst = 0;
                                 $igst = 18;
                            }

                            $insert_tax_invoices_logs_data = $dataSource->query("INSERT INTO tax_invoices_logs(invoice_id,service_id,hsn_no,description,total_amt,payable_amt,cgst,sgst,igst,invoice_type,invoice_date,month,year)VALUES('$invoice_id','$service_id','$hsn_no','$description',$total_amt,'$payable_amt','$cgst','$sgst','$igst','$invoice_type','$invoicedate','$month','$year')");
                            if(!$insert_tax_invoices_logs_data)
                            {
                                // Transaction rollback
                                $dataSource->rollback();
                                $response = array('status' => 'failure', 'description' => 'Something went wrong. Please try again.');
                                return $response;
                            }
                            $dataSource->commit();
                        }
                    }
                    else
                    {
                        $dataSource->rollback();
                    }
                }
            }            
        }
    }
}
