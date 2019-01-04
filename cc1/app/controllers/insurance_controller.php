<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class InsuranceController extends AppController{
    var $name = 'insurance';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator', 'GChart','Csv');
    var $components = array('RequestHandler', 'Recharge','Api','Smartpaycomp','Comments','Serviceintegration','Servicemanagement','Documentmanagement','UserProfile','Bridge');
    var $uses = array('Insurance','Slaves');
    
    
    
    public function index($type = null){
       $this->layout  = "plain";
       $frmdates       = $this->params['form']['insfrmdate'];
       $todates        = $this->params['form']['instodate'];
       $custmobile    = $this->params['form']['custMob'];
       $retId         = $this->params['form']['retId'];
       $policyId     = $this->params['form']['instoPolicyno'];
       $productId    = $this->params['form']['instoProdId'];
       $status_value    = $this->params['form']['transval'];
       $transcationval = implode("','", $status_value);
       
       
        if (empty($frmdates))
            $frmdate = date('Y-m-d');
        else $frmdate = $frmdates;
        if (empty($todates))
            $todate = date('Y-m-d');
        else $todate = $todates;

        if (isset($custmobile) && $custmobile != '') {
            $customer_mob = 'AND  c.mobile = "'.$custmobile.'"';
        }       
        if (isset($retId) && $retId != '' ) {
            $retailer_Id = 'AND  t.user_id = "' .$retId .'"';
        }
        if (isset($policyId) && $policyId !='' ) {
            $policy_Id = 'AND  cp.policy_id = "' .$policyId .'"';
        }
        if (isset($productId) && $productId != '' ) {
            $product_id = ' AND  cp.product_id = "' .$productId .'"';
        }
        if (isset($transcationval) && $transcationval != '' ) {
            $statusval = " AND  cp.status IN (' $transcationval ')";
        }else {
         $statusval = '';
        }
        
        $InsuranceDetails = $this->Insurance->query('SELECT
                                                    cp.trans_ref_id, cp.policy_id, cp.start_date,cp.status,cp.end_date, t.transaction_date,t.wallet_transaction_id,t.user_id,c.name, c.mobile, p.premium, p.tax, replace("http://local.pay1.in/policy/:token" ,":token", cp.url) AS url, p.sum_insured, p.name as product,p.product_id,v.name as vendor, p.tenure, p.image,p.label 
                                                    FROM customers_policies as cp 
                                                    LEFT JOIN customers as c on cp.customer_id = c.customer_id 
                                                    LEFT JOIN products as p on cp.product_id = p.product_id
                                                    LEFT JOIN transactions as t on cp.trans_ref_id = t.trans_ref_id
                                                    LEFT JOIN vendors as v on v.vendor_id = p.vendor_id WHERE t.transaction_date >=  "'. $frmdate . '"
                                                        AND t.transaction_date <= "'. $todate.'"  '.$customer_mob.'  ' .$policy_Id . '
                                                         '. $product_id .'   '. $retailer_Id .' '.$statusval.'
                                                    ');

        
        //For Fetching the borrower name
        if (count($InsuranceDetails) > 0) {
            /** IMP DATA ADDED : START* */
            $userid = array_map(function($element) {
                return $element['t']['user_id'];
            }, $InsuranceDetails);

            $retailer_name = $this->Shop->getUserLabelData($userid, 2, 0);
        }
       //Status Type 
       $status_val = array(1 => 'Active', 2 => 'Expired',4 => 'Cancelled/Refunded');   
       
       //retailer Insurance data
        if(isset($frmdates) || isset($todates)){                   
                   $dateopt = ' and t.transaction_date >=  "'. $frmdate . '" AND t.transaction_date <= "'. $todate.'"' ;            
        }else {
            $dateopt = '';
        }
        
        if (isset($retId) && $retId != '' ) {
                $retInsuranceData = $this->Insurance->query('select IFNULL(SUM(commission), 0.00) AS earnings, COUNT(*) AS policies_sold FROM transactions as t
        WHERE trans_status = 2  '. $retailer_Id .' ' . $dateopt . ' ');                 
        }
       //product update        
        $productdet = $this->Insurance->query('SELECT `product_id`,`label` from products where`status` = 1');
       
        //Product Details
        $productDetail = $this->Insurance->query('select b.product_id, b.name, b.label, (b.premium + b.tax) as amount, count(1) as cnt, sum(amount) as total  from transactions a, products b where a.product_id = b.product_id  and trans_status = 2 and a.service_id = 14 group by a.product_id;');
       //Total Amt
        $totalDetail  = $this->Insurance->query('select sum(amount) as total from transactions where trans_status = 2 and service_id = 14');
        
       $this->set('statusval',$status_value); 
       $this->set('productDetail',$productDetail);
       $this->set('retailer_name',$retailer_name);
       $this->set('InsuranceDetails',$InsuranceDetails);
       $this->set('productDetail',$productDetail);
       $this->set('totalDetail',$totalDetail);
       $this->set('status_val',$status_val);
       $this->set('frmdate',$frmdate);
       $this->set('todate',$todate);
       $this->set('custmobile',$custmobile);
       $this->set('retId',$retId);
       $this->set('policyId',$policyId);
       $this->set('productId',$productId);
       $this->set('InsuranceDetails',$InsuranceDetails);
       $this->set('retInsuranceData',$retInsuranceData);
       $this->set('productdet',$productdet);
       $this->set('type',$type);
       

    } 
    
    public function mutualFundPanel($type = null){
        $this->layout  = "plain";        
        $frmdates       = $this->params['form']['mfdate'];
        $todates        = $this->params['form']['mtdate'];
        $userid         = $this->params['form']['mretuserid'];
        $mobile         = $this->params['form']['mretmobile']; 
        $custmobile     = $this->params['form']['mcustmobile'];
        $refercode      = $this->params['form']['mreferencecode'];
       
        if (empty($frmdates))
            $frmdate = date('Y-m-d',strtotime('-30 days'));
        else
            $frmdate = $frmdates;
        if (empty($todates))
            $todate = date('Y-m-d');
        else
            $todate = $todates;        
        if (isset($userid) && $userid != '') {
            $retuser_Id = 'AND  a.user_id = "' . trim($userid) . '"';
        }
        if (isset($custmobile) && $custmobile != '') {
            $cmobile = 'AND  a.mobile = "' . trim($custmobile) . '"';
        }
        if (isset($refercode) && $refercode != '') {
            $refcode = 'AND  a.reference_code = "'. trim($refercode) .'"';
        }        
        $retailer_userId = $this->Shop->getUserLabelData($mobile, 2, 1);         
        $ret_mob = isset($retailer_userId[$mobile]['ret']['user_id'])?$retailer_userId[$mobile]['ret']['user_id']:$retailer_userId[$mobile]['dist']['user_id'];
       
        if (isset($ret_mob) && $ret_mob != '') {
            $retuser_mob = 'AND  a.user_id = "' . $ret_mob . '"';
        }
       
        
        
            $mutualfundCount = $this->Insurance->query('select count(distinct(a.mobile)) as unique_customers, count(1) as total_sips
                                                        from mutualfund_customers AS a join mutualfund_customer_sips AS b on a.mfc_id = b.mfc_id
                                                        WHERE date(b.investment_start_date) >= "'.$frmdate.'"  AND date(b.investment_start_date) <= "'. $todate .'"  ' .$retuser_Id. ' ' . $retuser_mob . ' '. $refcode. ' '. $cmobile. '');      
            
            $mutualfundAmt   =  $this->Insurance->query('select sum(case when c.current_investment > 0 then c.current_investment else 0 end) as total_investment
                                                            from mutualfund_customers AS a
                                                            join mutualfund_customer_sips AS b on a.mfc_id = b.mfc_id
                                                            join mutualfund_sip_history AS c on b.mfcs_id = c.mfcs_id 
                                                            WHERE date(c.investment_date) >= "'.$frmdate.'"  AND date(c.investment_date) <= "'. $todate .'"  ' .$retuser_Id. ' ' . $retuser_mob . ' '. $refcode. ' '. $cmobile. '');      
            
            $mutualfundReport = $this->Insurance->query('select a.name,a.user_id,a.mobile, a.reference_code, c.current_investment as sip_amount, c.investment_date, b.investment_start_date 
                                                         from mutualfund_customers AS a
                                                         join mutualfund_customer_sips AS b on a.mfc_id = b.mfc_id
                                                         join mutualfund_sip_history AS c on b.mfcs_id = c.mfcs_id 
                                                         WHERE date(c.investment_date) >= "'.$frmdate.'"  AND date(c.investment_date) <= "'. $todate .'" AND c.current_investment > 0 ' .$retuser_Id. ' ' . $retuser_mob . ' '. $refcode. ' '. $cmobile. '');
        
            
//            $ret_det =  $this->Slaves->query('select user_id,mobile from retailers where toshow = 1');
            
        $this->set('userid',$userid);
        $this->set('mobile',$mobile);
        $this->set('frmdate',$frmdate);
        $this->set('todate',$todate);
        $this->set('custmobile',$custmobile);        
        $this->set('refercode',$refercode);
        $this->set('mutualfundCount',$mutualfundCount);
        $this->set('mutualfundAmt',$mutualfundAmt);
        $this->set('mutualfundReport',$mutualfundReport);
        $this->set('type',$type);
        $this->set('ret_mob',$ret_mob);
    }
}