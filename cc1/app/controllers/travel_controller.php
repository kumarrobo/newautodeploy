<?php

class TravelController extends AppController{
    var $name = 'Travel';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator', 'GChart', 'Csv', 'NumberToWord');
    var $components = array('RequestHandler', 'Shop', 'Bridge','Serviceintegration');    
    var $uses = array('Slaves', 'User','Travel');
//
    function beforeFilter() {
        parent::beforeFilter();        
        if(empty($_SESSION['Auth']))
            return;
    }
    

    function  index(){
        $this->layout = "plain";
    }
    
    function travelRetailersReport($retmob = null, $retId = null) {
        $this->layout = "plain";
        
        $retFrom = $this->params['form']['travel_from'];
        $retTill = $this->params['form']['travel_till'];
        $retService = $this->params['form']['ret_service'];
        $retailersArray = array();
        $i = 0;
        $retshopname = str_replace("%20", " ", $retshopname);
        if(empty($retFrom))
            $retFrom = date('Y-m-d');
        if(empty($retTill))
            $retTill = date('Y-m-d');
        $retFrom_date = date("Y-m-d", strtotime($retFrom));
        $retTill_date = date("Y-m-d", strtotime($retTill));
        if(empty($retId))
            $retailer_Id = '';
        else
            $retailer_Id = " and r.id = $retId";
        if(empty($retmob))
            $retsmob = "";
        else
            $retsmob = " and r.mobile =  $retmob "; 
        $services = $this->Slaves->query("select id, name from services where toshow = 1 and id IN (18,19,23)");
        
        $retnameArray = array();
        $retailerdata = $this->Slaves->query("Select us.balance,ds.company,ss.name,r.* from retailers as r
                                                      join distributors as ds on (ds.id = r.parent_id)
                                                      join salesmen as ss on (ss.id = r.salesman)
                                                      join users as us on (us.id = r.user_id)
                                                      where 1=1  $retsmob  $retailer_Id");
//        
//        $user_id = array_map(function($element) {
//            return $element['r']['user_id'];
//        }, $retailerdata);
//
//echo $retmob;
//        $imp_data_retId = $this->Shop->getUserLabelData($retmob,2,1);
        /** IMP DATA ADDED : START**/
        $ret_ids = array_map(function($element){
            return $element['r']['id'];
        },$retailerdata);
        $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);

        $sales_data = $this->Slaves->query("select id,name from salesmen");        
        $salesman = array();
        foreach($sales_data as $sd){
            $salesman[$sd['salesmen']['id']] = $sd['salesmen']['name'];
        }
        
        foreach ($retailerdata as $ret) {
            $ret['r']['shopname'] = $imp_data[$ret['r']['id']]['imp']['shop_est_name'];
            $retnameArray[$ret['r']['shopname']] = $ret['r']['mobile'];
            $retnameArray[$ret['r']['id']] = $ret['r']['mobile'];
            $retUserId['user_id'] = $imp_data[$ret['r']['id']]['ret']['user_id'];
            
        }
        
        $ruser_id = $retUserId['user_id'];
        $retailershop = $retnameArray[$retailerdata[0]['r']['shopname']];
        $ret_mob_id = $retailerdata[0]['r']['id'];
        $mobile = ($retailerdata[0]['r']['mobile']);
        $user_id = $retailerdata[0]['r']['user_id'];
        
        if(empty($retId))
            $retid = "and fb.user_id = $ruser_id";
        else
            $retid = " and fb.user_id = $ruser_id";

        
        if(empty($retService)) {
            $retServices = "";
            $retServices1 = "";
            $retServices2 = ""; }           
        else{
            $retServices = " and tb.service_id = $retService";
            $retServices1 = " and ts.service_id = $retService";
            $retServices2 = " and tc.service_id = $retService";
            }

                $retailerTransaction = $this->Travel->query('SELECT * 
                                FROM   ((SELECT fb.user_id, 
                                                fb.fb_id, 
                                                fb.vendor_booking_id, 
                                                fb.pnr, 
                                                fb.pax_count, 
                                                0 AS cancel_count,
                                                fb.markup, 
                                                tb.api_response, 
                                                tb.txn_id, 
                                                tb.shop_transaction_id, 
                                                tb.transaction_date, 
                                                tb.trans_status, 
                                                tb.product_id, 
                                                tb.updated_at, 
                                                tb.source, 
                                                tb.service_id 
                                         FROM   flight_booking fb 
                                                JOIN flight_tbo_booking ftb 
                                                  ON ( fb.fb_id = ftb.fb_id ) 
                                                JOIN transactions tb 
                                                  ON ( ftb.txn_id = tb.txn_id ) 
                                         WHERE  tb.transaction_date  >= "'.$retFrom_date.'" and tb.transaction_date <=  "'.$retTill_date.'" '. $retid .' '.$retServices.') 
                                        UNION 
                                        (SELECT fb.user_id, 
                                                fb.fb_id, 
                                                fb.vendor_booking_id, 
                                                fb.pnr, 
                                                0 AS pax_count, 
                                                0 AS cancel_count,
                                                0 AS markup, 
                                                ts.api_response, 
                                                ts.txn_id, 
                                                ts.shop_transaction_id, 
                                                ts.transaction_date, 
                                                ts.trans_status, 
                                                ts.product_id, 
                                                ts.updated_at, 
                                                ts.source, 
                                                ts.service_id 
                                         FROM   flight_booking fb 
                                                JOIN flight_booking_ssr fbs 
                                                  ON ( fb.fb_id = fbs.fb_id ) 
                                                JOIN transactions ts 
                                                  ON ( fbs.txn_id = ts.txn_id or fbs.txn_id = ts.book_txn_id ) 
                                         WHERE  ts.transaction_date >=  "'.$retFrom_date.'" and ts.transaction_date <=  "'.$retTill_date.'" '. $retid .' '.$retServices1.') 
                                        UNION 
                                        (SELECT fb.user_id, 
                                                fb.fb_id, 
                                                fb.vendor_booking_id, 
                                                fb.pnr, 
                                                fb.pax_count,
                                                (LENGTH(ftc.ticket_id) - LENGTH(REPLACE(ftc.ticket_id, ":", "")) + 1) AS cancel_count, 
                                                0 AS markup, 
                                                tc.api_response, 
                                                tc.txn_id, 
                                                tc.shop_transaction_id, 
                                                tc.transaction_date, 
                                                tc.trans_status, 
                                                tc.product_id, 
                                                tc.updated_at, 
                                                tc.source, 
                                                tc.service_id 
                                         FROM   flight_booking fb 
                                                JOIN flight_tbo_cancellation ftc 
                                                  ON ( fb.fb_id = ftc.fb_id ) 
                                                JOIN transactions tc 
                                                  ON ( ftc.txn_id = tc.txn_id or ftc.txn_id = tc.book_txn_id ) 
                                         WHERE  tc.transaction_date  >= "'.$retFrom_date.'" and tc.transaction_date <=  "'.$retTill_date.'" '. $retid .' '.$retServices2.')) 
                                       AS A 
                                ORDER  BY transaction_date DESC');
        $serviceName =  $this->Serviceintegration->getServices();                   
        $rettransaction = array();
        $i = 0;
        foreach($retailerTransaction as $rettrans){
            $rettransaction[$i]['tdate']               =  $rettrans['A']['transaction_date'];
            $rettransaction[$i]['vendor_txn_id']       =  $rettrans['A']['vendor_booking_id'];
            $rettransaction[$i]['shop_txn_id']         =  $rettrans['A']['shop_transaction_id'];
            $rettransaction[$i]['cancellation_id']     =  $rettrans['A']['transaction_dat'];
            $rettransaction[$i]['response']            =  json_decode($rettrans['A']['api_response'], true);
            $rettransaction[$i]['amount']              =  abs($rettransaction[$i]['response']['amt_settled']);
            $rettransaction[$i]['mark_up']             =  $rettrans['A']['markup'];
            $rettransaction[$i]['comm']                =  $rettransaction[$i]['response']['commission'];
            $rettransaction[$i]['tds']                 =  $rettransaction[$i]['response']['tax'];
            $rettransaction[$i]['gst']                 =  $rettransaction[$i]['response']['service_charge'] - (($rettransaction[$i]['response']['service_charge'] * 100)/118);
            $rettransaction[$i]['charges']             =  $rettransaction[$i]['response']['service_charge'] - $rettransaction[$i]['gst'];
            $rettransaction[$i]['pay1_txn_id']         =  $rettrans['A']['fb_id'];
            $rettransaction[$i]['rdate']               =  $rettrans['A']['transaction_date'];
            $rettransaction[$i]['status']              =  $rettrans['A']['trans_status'];
            $rettransaction[$i]['pnr']                 =  $rettrans['A']['pnr'];
            $rettransaction[$i]['update']              =  $rettrans['A']['updated_at'];
            $rettransaction[$i]['source']              =  $rettrans['A']['source'];
            $rettransaction[$i]['service']             =  $serviceName[$rettrans['A']['service_id']];            
            $rettransaction[$i]['user_id']             =  $rettrans['A']['user_id'];
            $rettransaction[$i]['pass']                =  ($rettrans['A']['pax_count'] == 0)?'NA':$rettrans['A']['pax_count'];
            $rettransaction[$i]['cancel']              =  $rettrans['A']['cancel_count'] . '/' . $rettrans['A']['pax_count'];
            $i++;
        }     
        $service_id = 23;
        $travel_data = $this->Slaves->query("select * from users_services where service_id = $service_id and user_id = $user_id");
//        $plan_id = $travel_data[0]['users_services']['service_plan_id'];
        $plans = $this->Serviceintegration->getServicePlans();
        $temp = json_decode($plans, true);
        foreach ($temp[$service_id] as $key => $plan) {
            $service_plans[$plan['id']] = $plan['plan_name'];
        }
        
        // For Getting      
        $this->set('retfrom', $retFrom_date);
        $this->set('rettill', $retTill_date);
        $this->set('retailerdet', $retailerdata);
        $this->set('retailertrans', $rettransaction);
        $this->set('travel_data', $travel_data);
        $this->set('service_plans',$service_plans);
        $this->set('services',$services);
        $this->set('service',$retService);
        $this->set('ret_imp',$imp_data);
        $this->set('salesman',$salesman);
        
        

    }
    
    function travelTransactionReport($pnr = null, $txnid = null, $shopid = null,$vendid = null){
        $this->layout = "plain";
        if(empty($pnr))
            $pnr_no  = '';
        else
            $pnr_no  = "  fb.pnr = '$pnr'";

        if(empty($txnid))
            $trans_id  = '';
        else
            $trans_id  = "  fb.fb_id = '$txnid'";

        if(empty($shopid))
            $shop_id  = '';
        else
            $shop_id  = "  fb.pnr = '$shopid'";
        
        if(empty($shopid)){
            $shop_id  = "";
            $shop_id1 = "";
            $shop_id2 = ""; 
        }
        else {
            $shop_id    = " tb.shop_transaction_id IN  ('$shopid')";
            $shop_id1   = " ts.shop_transaction_id  IN  ('$shopid')";
            $shop_id2   = " tc.shop_transaction_id  IN  ('$shopid')";
        }
        if(empty($vendid))
            $vendor_id  = '';
        else
            $vendor_id  = "fb.vendor_booking_id = '$vendid'";

        $travelTransaction = $this->Travel->query('SELECT * 
                            FROM   ((SELECT fb.user_id, 
                                            fb.fb_id, 
                                            fb.vendor_booking_id, 
                                            fb.pnr, 
                                            fb.pax_count, 
                                            0 AS cancel_count,
                                            fb.markup, 
                                            tb.api_response, 
                                            tb.txn_id, 
                                            tb.shop_transaction_id, 
                                            tb.transaction_date, 
                                            tb.trans_status, 
                                            tb.vendor_id, 
                                            tb.product_id, 
                                            tb.updated_at, 
                                            tb.source, 
                                            tb.service_id 
                                     FROM   flight_booking fb 
                                            JOIN flight_tbo_booking ftb 
                                              ON ( fb.fb_id = ftb.fb_id ) 
                                            JOIN transactions tb 
                                              ON ( ftb.txn_id = tb.txn_id ) 
                                     WHERE  ' . $pnr_no . ' ' . $trans_id . ' ' . $vendor_id . ' ' . $shop_id . ') 
                                    UNION 
                                    (SELECT fb.user_id, 
                                            fb.fb_id, 
                                            fb.vendor_booking_id, 
                                            fb.pnr, 
                                            0 AS pax_count, 
                                            0 AS cancel_count,
                                            0 AS markup, 
                                            ts.api_response, 
                                            ts.txn_id, 
                                            ts.shop_transaction_id, 
                                            ts.transaction_date, 
                                            ts.trans_status, 
                                            ts.vendor_id, 
                                            ts.product_id, 
                                            ts.updated_at, 
                                            ts.source, 
                                            ts.service_id 
                                     FROM   flight_booking fb 
                                            JOIN flight_booking_ssr fbs 
                                              ON ( fb.fb_id = fbs.fb_id ) 
                                            JOIN transactions ts 
                                              ON ( fbs.txn_id = ts.txn_id or fbs.txn_id = ts.book_txn_id ) 
                                            WHERE  ' . $pnr_no . ' ' . $trans_id . ' ' . $vendor_id . ' ' . $shop_id1 . ') 
                                    UNION 
                                    (SELECT fb.user_id, 
                                            fb.fb_id, 
                                            fb.vendor_booking_id, 
                                            fb.pnr, 
                                            fb.pax_count,
                                            (LENGTH(ftc.ticket_id) - LENGTH(REPLACE(ftc.ticket_id,":", "")) + 1) AS cancel_count, 
                                            0 AS markup, 
                                            tc.api_response, 
                                            tc.txn_id, 
                                            tc.shop_transaction_id, 
                                            tc.transaction_date, 
                                            tc.trans_status, 
                                            tc.vendor_id, 
                                            tc.product_id, 
                                            tc.updated_at, 
                                            tc.source, 
                                            tc.service_id 
                                     FROM   flight_booking fb 
                                            JOIN flight_tbo_cancellation ftc 
                                              ON ( fb.fb_id = ftc.fb_id ) 
                                            JOIN transactions tc 
                                              ON ( ftc.txn_id = tc.txn_id or ftc.txn_id = tc.book_txn_id ) 
                                     WHERE ' . $pnr_no . ' ' . $trans_id . ' ' . $vendor_id . ' ' . $shop_id2 . '  )) 
                                            AS A 
                                        ORDER  BY transaction_date DESC');


        $userid = array_map(function($element) { //For returning id based on given user_id
            return $element['A']['user_id'];
        }, $travelTransaction);
        
        $vendorDet = $this->Travel->query('select platform_vendor_id, name from vendors');
        $vendorName = array();
        foreach($vendorDet as $det){
            $vendorName[$det['vendors']['platform_vendor_id']] = $det['vendors']['name'];
        }
        
        $retailer = $this->Shop->getUserLabelData($userid,2,0);            
        $serviceName =  $this->Serviceintegration->getServices();                   
        $transaction = array();
        $j = 0;
        foreach($travelTransaction as $trans){
            $transaction[$j]['tdate']               =  $trans['A']['transaction_date'];
            $transaction[$j]['vendor_txn_id']       =  $trans['A']['vendor_booking_id'];
            $transaction[$j]['shop_txn_id']         =  $trans['A']['shop_transaction_id'];            
            $transaction[$j]['response']            =  json_decode($trans['A']['api_response'], true);
            $transaction[$j]['amount']              = abs($transaction[$j]['response']['amt_settled']);
            $transaction[$j]['mark_up']             =  $trans['A']['markup'];
            $transaction[$j]['comm']                = $transaction[$j]['response']['commission'];
            $transaction[$j]['tds']                 =  $transaction[$j]['response']['tax'];
            $transaction[$j]['gst']                 =  $transaction[$j]['response']['service_charge'] - ($transaction[$j]['response']['service_charge'] * 100)/118;
            $transaction[$j]['charges']             = $transaction[$j]['response']['service_charge'] - $transaction[$j]['gst'];            
            $transaction[$j]['pay1_txn_id']         =  $trans['A']['fb_id'];
            $transaction[$j]['rdate']               =  $trans['A']['transaction_date'];
            $transaction[$j]['status']              =  $trans['A']['trans_status'];
            $transaction[$j]['pnr']                 =  $trans['A']['pnr'];
            $transaction[$j]['update']              =  $trans['A']['updated_at'];
            $transaction[$j]['source']              =  $trans['A']['source'];
            $transaction[$j]['service_id']          =  $serviceName[$trans['A']['service_id']];            
            $transaction[$j]['user_id']             =  $trans['A']['user_id'];
            $transaction[$j]['retId']               =  $retailer[$transaction[$j]['user_id']]['ret']['id'];
            $transaction[$j]['vendorId']            =  $trans['A']['vendor_id'];  
            $transaction[$j]['vendor']              =  $vendorName[$trans['A']['vendor_id']];
            $transaction[$j]['pass']                =  ($trans['A']['pax_count'] == 0)?'NA':$trans['A']['pax_count'];
            $transaction[$j]['cancel']              =  $trans['A']['cancel_count'] . '/' . $trans['A']['pax_count'];
            $j++;
        }     
        
        
        $this->set('pay1txnData',$transaction);
    }
    
    function travelFromTo($recs = 100){
        $this->layout = "plain";
                
        $getval         = $this->params['form']['search'];
        $travelfrm      = $this->params['form']['travel_from'];
        $travelto       = $this->params['form']['travel_till'];
        $transact       = $this->params['form']['transtatus'];
        $pnr            = $this->params['form']['travel_pnr'];
        $txntype        = $this->params['form']['trantype'];
        $pages          = $this->params['form']['txnpage'];
        $export         = $this->params['form']['fer_fld'];
        $transgrp = implode("','", $transact);        
        $nodays = (strtotime($travelto) - strtotime($travelfrm)) / (60 * 60 * 24);
        $nodays += 1;
        
        $url = Travel_AgencyBal;        
        $status = $this->General->curl_post($url, null, 'GET');
        $jsonval = json_decode($status['output'], TRUE);
        $agency_bal =  $jsonval['balance'];
        
        if(empty($travelfrm))
            $travelfrm = date('Y-m-d');
        if(empty($travelto))
            $travelto = date('Y-m-d');
        $from_date = date("Y-m-d", strtotime($travelfrm));
        $till_date = date("Y-m-d", strtotime($travelto));
        
        if(empty($transact)){
            $transstatus  = "";
            $transstatus1 = "";
            $transstatus2 = ""; 
        }
        else {
            $transstatus    = " and tb.trans_status IN  ('$transgrp')";
            $transstatus1   = " and ts.trans_status IN  ('$transgrp')";
            $transstatus2   = " and tc.trans_status IN  ('$transgrp')";
        }
        if(empty($pnr)){
            $pnrno = "";           
        }else {
            $pnrno = "and fb.pnr = '$pnr'";
        }
        
        if($txntype == '-1' ||  $txntype == '') {
            $transtype  = '';
            $transtype1 = '';
            $transtype2 = ''; }
        else{
            $transtype  = " and tb.source = '$txntype'";
            $transtype1 = " and ts.source = '$txntype'";
            $transtype2 = " and tc.source = '$txntype'";
        }
        
        //Total Sale Amount         
        $totSaleAmt = $this->Travel->query('SELECT * 
FROM   ((SELECT fb.user_id, 
                fb.fb_id, 
                fb.vendor_booking_id, 
                fb.pnr, 
                fb.pax_count, 
                0 AS cancel_count,
                fb.markup, 
                tb.api_response, 
                tb.txn_id, 
                tb.shop_transaction_id, 
                tb.transaction_date, 
                tb.trans_status, 
                tb.product_id, 
                tb.updated_at, 
                tb.source, 
                tb.service_id 
         FROM   flight_booking fb 
                JOIN flight_tbo_booking ftb 
                  ON ( fb.fb_id = ftb.fb_id ) 
                JOIN transactions tb 
                  ON ( ftb.txn_id = tb.txn_id ) 
         WHERE  tb.transaction_date >=  "'.$from_date.'" and tb.transaction_date <=  "'.$till_date.'"
                AND tb.trans_status = 2) 
        UNION 
        (SELECT fb.user_id, 
                fb.fb_id, 
                fb.vendor_booking_id, 
                fb.pnr, 
                0 AS pax_count, 
                0 AS cancel_count,
                0 AS markup, 
                ts.api_response, 
                ts.txn_id, 
                ts.shop_transaction_id, 
                ts.transaction_date, 
                ts.trans_status, 
                ts.product_id, 
                ts.updated_at, 
                ts.source, 
                ts.service_id 
         FROM   flight_booking fb 
                JOIN flight_booking_ssr fbs 
                  ON ( fb.fb_id = fbs.fb_id ) 
                JOIN transactions ts 
                  ON ( fbs.txn_id = ts.txn_id or fbs.txn_id = ts.book_txn_id ) 
         WHERE  ts.transaction_date >=  "'.$from_date.'" and ts.transaction_date <=  "'.$till_date.'" AND ts.trans_status = 2 )) AS A order by transaction_date desc');
        
             $transactionamt =  array();                                   
            foreach($totSaleAmt as $amt){
            $transactionamt[$h]['response']            =  json_decode($amt['A']['api_response'], true);
                $transactionamt[$h]['amount']          =  abs($transactionamt[$h]['response']['amt_settled']); 
                    $h++;                
            }
            $amount = array();
            foreach($transactionamt as $amt){
                $amount['amount']   +=  $amt['amount'];
            }
            
         $totAmount =  implode(" ", $amount);
         
        $TransDetails = $this->paginate_query('SELECT *
        FROM   ((SELECT fb.user_id, 
                        fb.fb_id, 
                        fb.vendor_booking_id, 
                        fb.pnr, 
                        fb.pax_count, 
                        0 AS cancel_count,
                        fb.markup, 
                        tb.api_response, 
                        tb.txn_id, 
                        tb.shop_transaction_id, 
                        tb.transaction_date, 
                        tb.trans_status, 
                        tb.product_id, 
                        tb.updated_at, 
                        tb.source, 
                        tb.service_id 
                 FROM   flight_booking fb 
                        JOIN flight_tbo_booking ftb 
                          ON ( fb.fb_id = ftb.fb_id ) 
                        JOIN transactions tb 
                          ON ( ftb.txn_id = tb.txn_id ) 
                 WHERE  tb.transaction_date >= "'.$from_date.'" and tb.transaction_date <=  "'.$till_date.'"
                                                '.$transstatus.' '.$pnrno.' '.$transtype.')
                                                UNION 
                (SELECT fb.user_id, 
                        fb.fb_id, 
                        fb.vendor_booking_id, 
                        fb.pnr, 
                        0 AS pax_count, 
                        0 AS cancel_count,
                        0 AS markup, 
                        ts.api_response, 
                        ts.txn_id, 
                        ts.shop_transaction_id, 
                        ts.transaction_date, 
                        ts.trans_status, 
                        ts.product_id, 
                        ts.updated_at, 
                        ts.source, 
                        ts.service_id 
                 FROM   flight_booking fb 
                        JOIN flight_booking_ssr fbs 
                          ON ( fb.fb_id = fbs.fb_id ) 
                        JOIN transactions ts 
                          ON ( fbs.txn_id = ts.txn_id or fbs.txn_id = ts.book_txn_id ) 
                 WHERE ts.transaction_date >= "'.$from_date.'" and ts.transaction_date  <= "'.$till_date.'"
                                                '.$transstatus1.' '.$pnrno.' '.$transtype1.')
                                                UNION 
                (SELECT fb.user_id, 
                        fb.fb_id, 
                        fb.vendor_booking_id, 
                        fb.pnr, 
                        fb.pax_count,
                        (LENGTH(ftc.ticket_id) - LENGTH(REPLACE(ftc.ticket_id,":", "")) + 1) AS cancel_count, 
                        0 AS markup, 
                        tc.api_response, 
                        tc.txn_id, 
                        tc.shop_transaction_id, 
                        tc.transaction_date, 
                        tc.trans_status, 
                        tc.product_id, 
                        tc.updated_at, 
                        tc.source, 
                        tc.service_id 
                 FROM   flight_booking fb 
                        JOIN flight_tbo_cancellation ftc 
                          ON ( fb.fb_id = ftc.fb_id ) 
                        JOIN transactions tc 
                          ON ( ftc.txn_id = tc.txn_id or ftc.txn_id = tc.book_txn_id ) 
                 WHERE tc.transaction_date >= "'.$from_date.'" and tc.transaction_date <= "'.$till_date.'" '.$transstatus2.' '. $pnrno .' '.$transtype2.'
                        )) 
               AS A 
        ORDER  BY transaction_date DESC', $recs, array(), 'Travel');
        
        $transaction = array();
        $i = 0;
        foreach($TransDetails as $trans){
            $transaction[$i]['tdate']               =  $trans['A']['transaction_date'];
            $transaction[$i]['vendor_txn_id']       =  $trans['A']['vendor_booking_id'];
            $transaction[$i]['cancellation_id']     =  $trans['A']['transaction_dat'];
            $transaction[$i]['booking_id']          =  $trans['A']['vendor_booking_id'];
            $transaction[$i]['response']            =  json_decode($trans['A']['api_response'], true);
            $transaction[$i]['amount']              =  abs($transaction[$i]['response']['amt_settled']);
            $transaction[$i]['mark_up']             =  $trans['A']['markup'];
            $transaction[$i]['comm']                =  $transaction[$i]['response']['commission'];
            $transaction[$i]['tds']                 =  $transaction[$i]['response']['tax'];
            $transaction[$i]['gst']                 =  $transaction[$i]['response']['service_charge'] - (($transaction[$i]['response']['service_charge'] * 100)/118);            
            $transaction[$i]['charges']             =  $transaction[$i]['response']['service_charge'] - $transaction[$i]['gst'];
            $transaction[$i]['rdate']               =  $trans['A']['transaction_date'];
            $transaction[$i]['status']              =  $trans['A']['trans_status'];
            $transaction[$i]['pnr']                 =  $trans['A']['pnr'];
            $transaction[$i]['update']              =  $trans['A']['updated_at'];
            $transaction[$i]['source']              =  $trans['A']['source'];
            $transaction[$i]['user_id']             =  $trans['A']['user_id'];
            $transaction[$i]['service_id']          =  $trans['A']['service_id'];
            $transaction[$i]['tds_gst']             =  $transaction[$i]['tax'];
            $transaction[$i]['pass']                =  ($trans['A']['pax_count'] == 0)?'NA':$trans['A']['pax_count'];
            $transaction[$i]['cancel']              =  $trans['A']['cancel_count'] . '/' . $trans['A']['pax_count'];
            $i++;
                    
        }
        if($export == '' ){
        $this->set('report_data',$transaction);
        $this->set('frm',$travelfrm);
        $this->set('to',$travelto);
        $this->set('pnr',$pnr);
        $this->set('txntype',$txntype);
        $this->set('transactatus',$transact);
        $this->set('hidden_fld', $getval);
        $this->set('totAmount',$totAmount);
        $this->set('recs', $recs);
        $this->set('agency_bal',$agency_bal);
     //   $this->set('days',$nodays);
        $this->set('export',$export);                    
        }else {
            $this->autoRender = false;
            App::import('Helper', 'csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();
            $line = array();
            $resultarr = array();            
            $retailerId = array();
            $traveltxn_status = Configure::read('Travel_pay1_status');
            
            
                $line = array('0' => 'Retailer Id', '1' => 'Shop Txn Id', '2' => 'Travel Txn Id', '3' => 'Vendor Txn Id', '4' => 'Pnr No','5' => 'Booking/Refund Amount', '6' => 'Passenger Count','7' => 'Mark up',
                    '8' => 'Commission', '9'=>'Charges','10' => 'Tds','11' => 'Gst', '12' => 'Refund Processes','13' => 'Service Name','14' => 'Status','15' => 'Source','16' => 'Transaction date','17'=>'State'
                );                

                $resultEx = $this->Travel->query('SELECT *
                                                    FROM   ((SELECT fb.user_id, 
                                                                    fb.fb_id, 
                                                                    fb.vendor_booking_id, 
                                                                    fb.pnr, 
                                                                    fb.pax_count, 
                                                                    0 AS cancel_count,
                                                                    fb.markup, 
                                                                    tb.api_response, 
                                                                    tb.txn_id, 
                                                                    tb.shop_transaction_id, 
                                                                    tb.transaction_date, 
                                                                    tb.trans_status, 
                                                                    tb.product_id, 
                                                                    tb.updated_at, 
                                                                    tb.source, 
                                                                    tb.service_id 
                                                             FROM   flight_booking fb 
                                                                    JOIN flight_tbo_booking ftb 
                                                                      ON ( fb.fb_id = ftb.fb_id ) 
                                                                    JOIN transactions tb 
                                                                      ON ( ftb.txn_id = tb.txn_id ) 
                                                             WHERE  tb.transaction_date >= "'.$from_date.'" and tb.transaction_date <=  "'.$till_date.'"
                                                                                            '.$transstatus.' '.$pnrno.' '.$transtype.')
                                                                                            UNION 
                                                            (SELECT fb.user_id, 
                                                                    fb.fb_id, 
                                                                    fb.vendor_booking_id, 
                                                                    fb.pnr, 
                                                                    0 AS pax_count, 
                                                                    0 AS cancel_count,
                                                                    0 AS markup, 
                                                                    ts.api_response, 
                                                                    ts.txn_id, 
                                                                    ts.shop_transaction_id, 
                                                                    ts.transaction_date, 
                                                                    ts.trans_status, 
                                                                    ts.product_id, 
                                                                    ts.updated_at, 
                                                                    ts.source, 
                                                                    ts.service_id 
                                                             FROM   flight_booking fb 
                                                                    JOIN flight_booking_ssr fbs 
                                                                      ON ( fb.fb_id = fbs.fb_id ) 
                                                                    JOIN transactions ts 
                                                                      ON ( fbs.txn_id = ts.txn_id or fbs.txn_id = ts.book_txn_id ) 
                                                             WHERE ts.transaction_date >= "'.$from_date.'" and ts.transaction_date  <= "'.$till_date.'"
                                                                                            '.$transstatus1.' '.$pnrno.' '.$transtype1.')
                                                                                            UNION 
                                                            (SELECT fb.user_id, 
                                                                    fb.fb_id, 
                                                                    fb.vendor_booking_id, 
                                                                    fb.pnr, 
                                                                    fb.pax_count,
                                                                    (LENGTH(ftc.ticket_id) - LENGTH(REPLACE(ftc.ticket_id,":", "")) + 1) AS cancel_count, 
                                                                    0 AS markup, 
                                                                    tc.api_response, 
                                                                    tc.txn_id, 
                                                                    tc.shop_transaction_id, 
                                                                    tc.transaction_date, 
                                                                    tc.trans_status, 
                                                                    tc.product_id, 
                                                                    tc.updated_at, 
                                                                    tc.source, 
                                                                    tc.service_id 
                                                             FROM   flight_booking fb 
                                                                    JOIN flight_tbo_cancellation ftc 
                                                                      ON ( fb.fb_id = ftc.fb_id ) 
                                                                    JOIN transactions tc 
                                                                      ON ( ftc.txn_id = tc.txn_id or ftc.txn_id = tc.book_txn_id ) 
                                                             WHERE tc.transaction_date >= "'.$from_date.'" and tc.transaction_date <= "'.$till_date.'" '.$transstatus2.' '. $pnrno .' '.$transtype2.'
                                                                    )) 
                                                           AS A 
                                                    ORDER  BY transaction_date DESC');
           
        $userid = array_map(function($element) { //For returning id based on given user_id
            return $element['A']['user_id'];
        }, $resultEx);

        $retailer = $this->Shop->getUserLabelData($userid,2,0);            
        $serviceName =  $this->Serviceintegration->getServices();                   
        $transaction = array();
        $j = 0;
        foreach($resultEx as $trans){
            $transaction[$j]['tdate']               =  $trans['A']['transaction_date'];
            $transaction[$j]['vendor_txn_id']       =  $trans['A']['vendor_booking_id'];
            $transaction[$j]['shop_txn_id']         =  $trans['A']['shop_transaction_id'];
            $transaction[$j]['cancellation_id']     =  $trans['A']['transaction_dat'];
            $transaction[$j]['response']            =  json_decode($trans['A']['api_response'], true);
            $transaction[$j]['amount']              =  abs($transaction[$j]['response']['amt_settled']);
            $transaction[$j]['mark_up']             =  $trans['A']['markup'];
            $transaction[$j]['comm']                =  $transaction[$j]['response']['commission'];
            $transaction[$j]['gst']                 =  $transaction[$j]['response']['service_charge'] - (($transaction[$j]['response']['service_charge'] * 100)/118);
            $transaction[$j]['tds']                 =  $transaction[$j]['response']['tax'];
            $transaction[$j]['charges']             =  $transaction[$j]['response']['service_charge'] - $transaction[$j]['gst'];            
            $transaction[$j]['pay1_txn_id']         =  $trans['A']['fb_id'];
            $transaction[$j]['rdate']               =  $trans['A']['transaction_date'];
            $transaction[$j]['status']              =  $trans['A']['trans_status'];
            $transaction[$j]['pnr']                 =  $trans['A']['pnr'];
            $transaction[$j]['update']              =  $trans['A']['updated_at'];
            $transaction[$j]['source']              =  $trans['A']['source'];
            $transaction[$j]['service_id']          =  $trans['A']['service_id'];            
            $transaction[$j]['user_id']             =  $trans['A']['user_id'];
            $transaction[$j]['retId']               =  $retailer[$transaction[$j]['user_id']]['ret']['id'];
            $transaction[$j]['pass']                =  ($trans['A']['pax_count'] == 0)?'NA':$trans['A']['pax_count'];
            $transaction[$j]['cancel']              =  $trans['A']['cancel_count'] . '|' . $trans['A']['pax_count'];
            
            $j++;
        }     
            foreach ($transaction as $retus) {                
                $retailerId[] = $retus['retId'];               
            }            
                $retid = implode(',', $retailerId);
            $retLocation = $this->Slaves->query("select rl.retailer_id,rl.area_id,la.city_id,la.name,lc.state_id,lc.name,
                                     ls.name from retailers_location as rl left join locator_area as la on (rl.area_id = la.id)
                                     left join locator_city as lc  on (la.city_id = lc.id)
                                     left join locator_state as ls on (lc.state_id = ls.id)
                                     where rl.retailer_id IN ($retid)");
            foreach ($retLocation as $retLoc) {
                //$retailer_city[$retLoc['rl']['retailer_id']] = $retLoc['lc']['name'];
                $retailer_state[$retLoc['rl']['retailer_id']] = $retLoc['ls']['name'];
            }
            $i = 17;
            $csv->addRow($line);
        foreach($transaction as $transac):
            $temp[0]     =  $transac['retId'];
            $temp[1]     =  $transac['shop_txn_id'];
            $temp[2]     =  $transac['pay1_txn_id'];
            $temp[3]     =  $transac['vendor_txn_id'];
            $temp[4]     =  $transac['pnr'];
            $temp[5]     =  $transac['amount'];
            $temp[6]     =  ($transac['status'] == '6')?$transac['cancel']:$transac['pass']; 
            $temp[7]     =  $transac['mark_up'];
            $temp[8]     =  isset($transac['comm'])?$transac['comm']:0;
            $temp[9]     =  isset($transac['charges'])?$transac['charges']:0;
            $temp[10]     =  isset($transac['tds'])?$transac['tds']:0;
            $temp[11]     = isset($transac['gst'])?$transac['gst']:0;            
            $temp[12]     =  $transac['update'];
            $temp[13]    =  $serviceName[$transac['service_id']];
            $temp[14]    =  $traveltxn_status[$transac['status']];
            $temp[15]    =  $transac['source'];
            $temp[16]    =  $transac['tdate'];
            $temp[17]    =  $retailer_state[$transac['retId']];

            $csv->addRow($temp);
            $i ++;
            endforeach;
            echo $csv->render('Travel_' . $from_date . '_' . $till_date . '.csv');

      }
    }

    
    function travelTicket(){
        $this->autoRender = FALSE;
        
        $booking_id  = $this->params['form']['booking_id'];
        $pnr         = $this->params['form']['pnr'];
        $data = array('BookingId'=>$booking_id,'PNR'=>$pnr);
        $key = Travel_ticket_key;
        $url = Travel_ticket_url;
        

        $encode_data = $this->travelencrypt($data);

        $result = $url . $encode_data;

        echo  json_encode($result);

    }
    
    public function travelencrypt($data) {

//generate random string of 16 bit 
$iv = mt_rand(1000000000000000,9999999999999999);


//retrive key from config
        $key = Travel_ticket_key;

//encode array to json
        $data = json_encode($data);        
//encryption
        $cipher = mcrypt_encrypt(
                MCRYPT_RIJNDAEL_128, $key, $this->travelpkcs7_pad($data), MCRYPT_MODE_CBC, $iv
        );
        
//convert binary data (cipher & iv) to hex concad and encode using base64
        $result = base64_encode(bin2hex($cipher) . '::' . bin2hex($iv));
        return $result;
    }

    function travelpkcs7_pad($str)
{
    $len = mb_strlen($str, '8bit');
    $c = 16 - ($len % 16);
    $str .= str_repeat(chr($c), $c);
    return $str;
}
}