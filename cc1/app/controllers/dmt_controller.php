<?php

class DmtController extends AppController {

    var $name = 'Dmt';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator', 'GChart', 'Csv', 'NumberToWord');
    var $components = array('RequestHandler', 'Shop', 'Bridge','Serviceintegration');
    var $uses = array('Slaves', 'User','Ekonew');

    function beforeFilter() {                     
        parent::beforeFilter();
        $this->layout = 'dmtreport';
        ini_set('memory_limit','2048M');
        if(empty($_SESSION['Auth']))
            return;

    }

    function index($bankType = null) {
        if(!isset($bankType)) {
            $bankType = "eko";
        }
        $this->set('ibanktype', $bankType);
    }

    function retailersReport($bankType = null, $retmob = null, $retId = null) {
        $retFrom = $this->params['form']['dmt_from'];
        $retTill = $this->params['form']['dmt_till'];
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

        $retnameArray = array();
        $retailerdata = $this->Slaves->query("Select us.balance,ds.company,ss.name,r.* from retailers as r
                                                      join distributors as ds on (ds.id = r.parent_id)
                                                      join salesmen as ss on (ss.id = r.salesman)
                                                      join users as us on (us.id = r.user_id)
                                                      where 1=1  $retsmob  $retailer_Id");

        /** IMP DATA ADDED : START**/
        $ret_ids = array_map(function($element){
            return $element['r']['id'];
        },$retailerdata);
        $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
        /** IMP DATA ADDED : END**/

        foreach ($retailerdata as $ret) {
            $ret['r']['shopname'] = $imp_data[$ret['r']['id']]['imp']['shop_est_name'];
            $retnameArray[$ret['r']['shopname']] = $ret['r']['mobile'];
            $retnameArray[$ret['r']['id']] = $ret['r']['mobile'];
        }
        $retailershop = $retnameArray[$retailerdata[0]['r']['shopname']];
        $ret_mob_id = $retailerdata[0]['r']['id'];
        $mobile = ($retailerdata[0]['r']['mobile']);
        $user_id = $retailerdata[0]['r']['user_id'];

        //For getting dmt data (i.e activation date,activated agent name,service status)
        $dmt_data = $this->Slaves->query("select * from users_services where service_id = '12' and user_id = $user_id");
        $dmtdeviceId = $dmt_data[0]['users_services']['param1'];
//        $dmtdeviceid = json_decode($dmtdevice);
//        $dmtdeviceId = $dmtdeviceid->bc_agent;

        if($bankType == "ekonew") {
            if(empty($retId))
                $retid = "and ds.retailer_id = $ret_mob_id";
            else
                $retid = " and ds.retailer_id = $retId ";

            $retailertxn = $this->Ekonew->query("Select * from (SELECT
                                                rs.id as remid,
                                                rs.mobile as remmob,
                                                rs.name,
                                                ds.id as dsid,
                                                ds.service_flag,
                                                ds.kit_flag,
                                                ds.bcagent_id,
                                                ds.mobile,
                                                ds.retailer_id,
                                                ts.*,
                                                tt.vendor_txn_id,tt.updated_at as updates
                                              FROM transactions_master ts
                                              JOIN remitter_master rs
                                                ON (rs.id = ts.remitter_id)
                                              JOIN transaction_trails tt
                                                ON (tt.txn_id = ts.id)
                                              JOIN dmt_users ds
                                                ON (ds.id = ts.dmtuser_id)
                                               where ts.transaction_date between '$retFrom_date' and '$retTill_date'
                                                   $retid
                                              order by tt.id desc
                                                  ) as t
                                                  group by t.id
                                                  order by t.transaction_date desc
                                                   ");


            foreach ($retailertxn as $rettxn) {
                $retailersArray[$i]['service_flag'] = $rettxn['t']['service_flag'];
                $retailersArray[$i]['kit_flag'] = $rettxn['t']['kit_flag'];
                $retailersArray[$i]['order_id'] = $rettxn['t']['id'];
                $retailersArray[$i]['bank_txn_id'] = $rettxn['t']['vendor_txn_id'];
                $retailersArray[$i]['wallet_id'] = $rettxn['t']['shop_transaction_id'];
                $retailersArray[$i]['sendername'] = $rettxn['t']['name'];
                $retailersArray[$i]['sendermob'] = $rettxn['t']['remmob'];
                $retailersArray[$i]['amount'] = $rettxn['t']['amount'];
                $retailersArray[$i]['pay1charges'] = $rettxn['t']['pay1_charge'];
                $retailersArray[$i]['status'] = $rettxn['t']['pay1_status'];
                $retailersArray[$i]['date'] = $rettxn['t']['transaction_date'];
                $retailersArray[$i]['updated_at'] = $rettxn['t']['updates']?$rettxn['t']['updates']:$rettxn['t']['updated_at'];
                $retailersArray[$i]['bc_agent'] = $rettxn['t']['bcagent_id'];
                $retailersArray[$i]['mobile'] = $rettxn['t']['mobile'];
                $retailersArray[$i]['ret_id'] = $rettxn['t']['retailer_id'];
                $i++;
            }
            $dmtuserData = $this->Ekonew->query("Select * from dmt_users where mobile = $mobile ");


        $retailerUserId = $retailerdata[0]['t']['user_id'];
        $user_profile = $this->Slaves->query("select * from user_profile where user_id = '$retailerUserId' AND app_type = 'dmt' order by updated desc limit 1");
        // For getting opening closing on the basis of transaction id
        foreach ($retailertxn as $rettxn) {
            $rettxn_array[] = $rettxn['t']['shop_transaction_id'];
        }
        $EkonewTxnDet = implode("','", $rettxn_array);
        $ret_opclose = $this->Slaves->query("select * FROM shop_transactions where id IN ('$EkonewTxnDet')");
        $opening = array();
        $closing = array();
        foreach ($ret_opclose as $opclose) {
            $opening[$opclose['shop_transactions']['id']] = $opclose['shop_transactions']['source_opening'];
            $closing[$opclose['shop_transactions']['id']] = $opclose['shop_transactions']['source_closing'];
        }
        } else {
            if(empty($retId))
                $retid = "and ds.retailer_id = $ret_mob_id";
            else
                $retid = " and ds.retailer_id = $retId ";

            $retailertxn = $this->Eko->query("Select ds.*,ts.* from transactions ts
                                                        join dmt_users ds on (ds.id = ts.dmtuser_id)
                                                        where ts.transaction_date between '$retFrom_date'and '$retTill_date'  $retid order by ts.updated_at desc ");
            foreach ($retailertxn as $rettxn) {
                $retailersArray[$i]['service_flag'] = $rettxn['ds']['service_flag'];
                $retailersArray[$i]['kit_flag'] = $rettxn['ds']['kit_flag'];
                $retailersArray[$i]['order_id'] = $rettxn['ts']['id'];
                $retailersArray[$i]['bank_txn_id'] = $rettxn['ts']['bank_ref_num'];
                $retailersArray[$i]['wallet_id'] = $rettxn['ts']['shop_transaction_id'];
                $retailersArray[$i]['sendername'] = $rettxn['ts']['sendername'];
                $retailersArray[$i]['sendermob'] = $rettxn['ts']['sender_mobile'];
                $retailersArray[$i]['amount'] = $rettxn['ts']['amount'];
                $retailersArray[$i]['pay1charges'] = $rettxn['ts']['pay1_charge'];
                $retailersArray[$i]['status'] = $rettxn['ts']['pay1_status'];
                $retailersArray[$i]['date'] = $rettxn['ts']['transaction_date'];
                $retailersArray[$i]['updated_at'] = $rettxn['ts']['updated_at'];
                $retailersArray[$i]['bc_agent'] = $rettxn['ds']['bcagent_id'];
                $retailersArray[$i]['mobile'] = $rettxn['ds']['mobile'];
                $retailersArray[$i]['ret_id'] = $rettxn['ds']['retailer_id'];
                $i++;
            } $dmtuserData = $this->Eko->query("Select * from dmt_users where mobile = $mobile ");

        $retailerUserId = $retailerdata[0]['r']['user_id'];
        $user_profile = $this->Slaves->query("select * from user_profile where user_id = '$retailerUserId' AND app_type = 'dmt' order by updated desc limit 1");
        // For getting opening closing on the basis of transaction id
        foreach ($retailertxn as $rettxn) {
            $rettxn_array[] = $rettxn['ts']['shop_transaction_id'];
        }
        $EkonewTxnDet = implode("','", $rettxn_array);
        $ret_opclose = $this->Slaves->query("select * FROM shop_transactions where id IN ('$EkonewTxnDet')");
        $opening = array();
        $closing = array();
        foreach ($ret_opclose as $opclose) {
            $opening[$opclose['shop_transactions']['id']] = $opclose['shop_transactions']['source_opening'];
            $closing[$opclose['shop_transactions']['id']] = $opclose['shop_transactions']['source_closing'];
        }
        }
        //end;;
        // For Getting
        $this->set('retfrom', $retFrom_date);
        $this->set('rettill', $retTill_date);
        $this->set('user_profile', $user_profile['0']['user_profile']);
        $this->set('retailerdet', $retailerdata);
        $this->set('retailertrans', $retailersArray);
        $this->set('retopening', $opening);
        $this->set('dmtuserdata', $dmtuserData);
        $this->set('retclosing', $closing);
        $this->set('dmt_data', $dmt_data);
        $this->set('dmtdeviceId', $dmtdeviceId);
    }

    function sendersReport($bankType = null, $sendNum = null, $sendName = null) {
        $senderArray = array();
        $beneArray = array();
        if($bankType == "ekonew") {
            if($sendNum == 0)
                $senderMob = '';
            else
                $senderMob = ' and rm.mobile = ' . $sendNum . '';
            if(empty($sendName))
                $senderName = '';
            else
                $senderName = " and rm.name Like '%$sendName%' ";

            $senderRes = $this->Ekonew->query("Select * from (SELECT
                                                ds.mobile as dmtmob,
                                                ds.retailer_id,
                                                ts.*,
                                                rm.mobile,
                                                rm.name,
                                                tt.vendor_txn_id,tt.updated_at as updates,
                                                b.acc_no,
                                                b.real_name,
                                                rbm.bene_mobile,
                                                rbm.show_flag
                                              FROM transactions_master AS ts
                                              JOIN dmt_users AS ds
                                                ON (ds.id = ts.dmtuser_id)
                                              JOIN remitter_master AS rm
                                                ON (rm.id = ts.remitter_id)
                                              JOIN transaction_trails AS tt
                                                ON (tt.txn_id = ts.id)
                                              JOIN bene_master AS b
                                                ON (b.id = ts.bene_id)
                                              LEFT JOIN remitter_bene_vendor_mapping AS rbm
                                                ON (rbm.bene_id = b.id
                                                AND rbm.remitter_id = ts.remitter_id)
                                                where rbm.show_flag = 1 $senderMob $senderName
                                            order by tt.id desc
                                            ) as t
                                            group by t.id
                                            order by t.updated_at desc");

            $bendet = $this->Ekonew->query("Select distinct bs.id,bs.real_name,rbm.bene_mobile,bs.acc_no,rm.*
                                             from remitter_bene_vendor_mapping as rbm
                                             join remitter_master as rm on (rm.id = rbm.remitter_id)
                                             join bene_master as bs on (bs.id = rbm.bene_id)
                                             where 1=1  $senderMob $senderName
                                            ");

            $i = 0;
            foreach ($bendet as $bene) {
                $beneArray[$i]['bene_id'] = $bene['bs']['id'];
                $beneArray[$i]['benename'] = $bene['bs']['real_name'];
                $beneArray[$i]['beneacc'] = $bene['bs']['acc_no'];
                $i++;
            }
            $i = 0;
            foreach ($senderRes as $sender) {
                $senderArray[$i]['sender_name'] = $sender['t']['name'];
                $senderArray[$i]['sender_mob'] = $sender['t']['mobile'];
                $senderArray[$i]['bene_id'] = $sender['t']['receiver_id'];
                $senderArray[$i]['bene_name'] = $sender['t']['real_name'];
                $senderArray[$i]['bene_mobile'] = $sender['t']['bene_mobile'];
                $senderArray[$i]['bene_email'] = $sender['ts'][''];
                $senderArray[$i]['bene_accno'] = $sender['t']['acc_no'];
                $senderArray[$i]['order_id'] = $sender['t']['id'];
                $senderArray[$i]['bank_refno'] = $sender['t']['bank_ref_num'];
                $senderArray[$i]['wallet_id'] = $sender['t']['shop_transaction_id'];
                $senderArray[$i]['bank_txn_id'] = $sender['t']['vendor_txn_id'];
                $senderArray[$i]['mobile'] = $sender['t']['dmtmob'];
                $senderArray[$i]['ret_id'] = $sender['t']['retailer_id'];
                $senderArray[$i]['gross_amt'] = $sender['t']['gross_amount'];
                $senderArray[$i]['status'] = $sender['t']['pay1_status'];
                $senderArray[$i]['updated_at'] = $sender['t']['updates']?$sender['t']['updates']:$sender['t']['updated_at'];;
                $i++;
            }

        foreach ($senderRes as $srmob) {
                $retMobileid[] = $srmob['t']['retailer_id'];
            }
            $ret_Mobile_id = implode(",", $retMobileid);

            $sret_shopname = $this->Slaves->query("Select id,mobile,shopname from retailers where id IN ($ret_Mobile_id)");
            $sretailerForm = array();
            $sretailerFormMob = array();

            /** IMP DATA ADDED : START* */
            $imp_data = $this->Shop->getUserLabelData($ret_Mobile_id, 2, 2);
            /** IMP DATA ADDED : END* */
            foreach ($sret_shopname as $srshop) {
                // $sretailerForm[$srshop['retailers']['id']] = $srshop['retailers']['shopname'];
                $sretailerForm[$srshop['retailers']['id']] = $imp_data[$srshop['retailers']['id']]['imp']['shop_est_name'];
                $sretailerFormMob[$srshop['retailers']['id']] = $srshop['retailers']['mobile'];
            }
        } else {
            if($sendNum == 0)
                $senderMob = '';
            else
                $senderMob = ' and ts.sender_mobile = ' . $sendNum . '';
            if(empty($sendName))
                $senderName = '';
            else
                $senderName = " and ts.sendername Like '%$sendName%' ";

            $senderRes = $this->Eko->query("Select ds.mobile,ds.retailer_id,ts.* from transactions as ts
                                                   join dmt_users as ds on (ds.id = ts.dmtuser_id)
                                                   where 1=1 $senderMob $senderName order by ts.updated_at desc");

            $bendet = $this->Eko->query("Select DISTINCT receiver_id,receivername,accountnumber from transactions as ts
                                            where 1=1 $senderMob $senderName");

            $i = 0;
            foreach ($bendet as $bene) {
                $beneArray[$i]['bene_id'] = $bene['ts']['receiver_id'];
                $beneArray[$i]['benename'] = $bene['ts']['receivername'];
                $beneArray[$i]['beneacc'] = $bene['ts']['accountnumber'];
                $i++;
            }
            $i = 0;
            foreach ($senderRes as $sender) {
                $senderArray[$i]['sender_name'] = $sender['ts']['sendername'];
                $senderArray[$i]['sender_mob'] = $sender['ts']['sender_mobile'];
                $senderArray[$i]['bene_id'] = $sender['ts']['receiver_id'];
                $senderArray[$i]['bene_name'] = $sender['ts']['receivername'];
                $senderArray[$i]['bene_mobile'] = $sender['ts']['receiver_mobile'];
                $senderArray[$i]['bene_email'] = $sender['ts'][''];
                $senderArray[$i]['bene_accno'] = $sender['ts']['accountnumber'];
                $senderArray[$i]['order_id'] = $sender['ts']['id'];
                $senderArray[$i]['bank_refno'] = $sender['ts']['bank_ref_num'];
                $senderArray[$i]['wallet_id'] = $sender['ts']['shop_transaction_id'];
                $senderArray[$i]['bank_txn_id'] = $sender['ts']['eko_txn_id'];
                $senderArray[$i]['mobile'] = $sender['ds']['mobile'];
                $senderArray[$i]['ret_id'] = $sender['ds']['retailer_id'];
                $senderArray[$i]['gross_amt'] = $sender['ts']['gross_amount'];
                $senderArray[$i]['status'] = $sender['ts']['pay1_status'];
                $senderArray[$i]['updated_at'] = $sender['ts']['updated_at'];
                $i++;
            }


        foreach ($senderRes as $srmob) {
            $retMobileid[] = $srmob['ds']['retailer_id'];
        }
        $ret_Mobile_id = implode(",", $retMobileid);

        $sret_shopname = $this->Slaves->query("Select id,mobile,shopname from retailers where id IN ($ret_Mobile_id)");
        $sretailerForm = array();
        $sretailerFormMob = array();

        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($ret_Mobile_id,2,2);
        /** IMP DATA ADDED : END**/

        foreach ($sret_shopname as $srshop) {
            // $sretailerForm[$srshop['retailers']['id']] = $srshop['retailers']['shopname'];
            $sretailerForm[$srshop['retailers']['id']] = $imp_data[$srshop['retailers']['id']]['imp']['shop_est_name'];
            $sretailerFormMob[$srshop['retailers']['id']] = $srshop['retailers']['mobile'];
        }
        }
        $this->set("senderData", $senderArray);
        $this->set("beneArray", $beneArray);
        $this->set("ret_array", $sretailerForm);
        $this->set("ret_array_Mob", $sretailerFormMob);
    }

    function transactionReport($bankType = null, $pay1txn = null, $txnid = null, $accno = null) {
        $transArray = array();
        if(empty($pay1txn))
            $pay1txnId = '';
        else
            $pay1txnId = "and ts.shop_transaction_id = '$pay1txn'";
        if(empty($txnid))
            $txnId = '';
        else
            $txnId = "and ts.id = '$txnid'";

        if($bankType == "ekonew") {
            if(empty($accno))
                $accnum = '';
            else
                $accnum = "and bs.acc_no = '$accno'";

		    $tag = $this->params['form']['comm_tag'];
		    $comm = $this->params['form']['comm'];
		    $user_id = $this->params['form']['user_id'];
		    $order_id = $this->params['form']['order_id'];
		    $cc_id    = $_SESSION['Auth']['User']['id'];

    $txnRes = $this->Ekonew->query("Select * from (SELECT rs.id as rid,
                                                    rs.name,
                                                    rs.mobile as rmob,
                                                    bs.real_name,
                                                    bs.acc_no,
                                                    rbm.bene_mobile,
                                                    ds.mobile,
                                                    ds.retailer_id,
                                                    ds.user_id,
                                                    tt.vendor_txn_id,
                                                    tt.vendor_txn_status,
                                                    tt.reason,tt.updated_at as updates,
                                                    rbm.show_flag,
                                                    vm.vendor_name,
                                                    ts.*
                                             FROM   transactions_master AS ts
                                             JOIN   remitter_master     AS rs
                                             ON     (rs.id = ts.remitter_id)
                                             JOIN   bene_master AS bs
                                             ON     (bs.id = ts.bene_id)
                                             LEFT JOIN   remitter_bene_vendor_mapping AS rbm
                                             ON     (rbm.bene_id = bs.id && rbm.remitter_id = ts.remitter_id)
                                             JOIN dmt_users AS ds
                                             ON     (ds.id = ts.dmtuser_id)
                                             LEFT JOIN   transaction_trails AS tt
                                             ON     (tt.txn_id = ts.id)
                                            inner JOIN vendor_master as vm on (vm.id = ts.vendor_id)
                                            where  rbm.vendor_id  =  ts.vendor_id $pay1txnId $txnId $accnum
                                            order by tt.id desc
                                            ) as t
                                            group by t.id
                                            order by t.transaction_date");

            if (count($txnRes) > 0) {

            $vendortxnid = array_map(function($element) {
                return $element['t']['id'];
            }, $txnRes);

              $vendorid = implode("','", $vendortxnid);

              $shoptxnid = array_map(function($element) {
                    return $element['t']['shop_transaction_id'];
              }, $txnRes);

             $shop_id = implode("','", $shoptxnid);
            }

            $tdsAmount = $this->Slaves->query("Select amount,target_id from shop_transactions where target_id IN ('$shop_id') and type = 31");

            $tdsamt = array();
            foreach($tdsAmount as $tds){
                $tdsamt[$tds['shop_transactions']['target_id']] =  $tds['shop_transactions']['amount'];
            }

            $statusval = $this->Ekonew->query("Select * from (select txn_id,vendor_txn_status,updated_at,remarks from transaction_trails where txn_id IN ('". $vendorid ."') order by id) as transaction_trails group by transaction_trails.txn_id order by transaction_trails.txn_id desc");
            foreach($statusval as $status){
                $statusvalue[$status['transaction_trails']['txn_id']] = $status['transaction_trails']['vendor_txn_status'];
                $statustime[$status['transaction_trails']['txn_id']] = $status['transaction_trails']['updated_at'];
                $statusRes[$status['transaction_trails']['txn_id']] = $status['transaction_trails']['remarks'];
            }
           /* for opening closing */
            foreach ($txnRes as $bal) {
                $bal_array[] = $bal['t']['shop_transaction_id'];
            }

            $TxnDet = implode("','", $bal_array);
            $ret_opclose = $this->Slaves->query("select * FROM shop_transactions where id IN ('$TxnDet')");

            $opening = array();
            $closing = array();
            foreach ($ret_opclose as $opclose) {
                $opening[$opclose['shop_transactions']['id']] = $opclose['shop_transactions']['source_opening'];
                $closing[$opclose['shop_transactions']['id']] = $opclose['shop_transactions']['source_closing'];
            }

            //for getting refund time
            $i = 0;
            foreach ($txnRes as $txn) {
                $transArray[$i]['mobile']       = $txn['t']['mobile'];
                $transArray[$i]['ret_id']       = $txn['t']['retailer_id'];
                $transArray[$i]['eko_txn_id']   = $txn['t']['vendor_txn_id'];
                $transArray[$i]['wallet_id']    = $txn['t']['shop_transaction_id'];
                $transArray[$i]['tds']          = $tdsamt[$txn['t']['shop_transaction_id']];
                $transArray[$i]['bank_id']      = $txn['t']['bank_ref_num'];
                $transArray[$i]['user_id']      = $txn['t']['user_id'];
                $transArray[$i]['order_id']     = $txn['t']['id'];
                $transArray[$i]['send_name']    = $txn['t']['name'];
                $transArray[$i]['send_mob']     = $txn['t']['rmob'];
                $transArray[$i]['bene_name']    = $txn['t']['real_name'];
                $transArray[$i]['bene_accno']   = $txn['t']['acc_no'];
                $transArray[$i]['amount']       = $txn['t']['gross_amount'];
                $transArray[$i]['status']       = $txn['t']['pay1_status'];
                $transArray[$i]['created_at']   = $txn['t']['created_at'];
                $transArray[$i]['remarks']      = $statusRes[$txn['t']['id']];
                $transArray[$i]['bank_status']  = $txn['t']['vendor_txn_status'];
                $transArray[$i]['npci_status']  = $txn['t']['npci_response_code'];
                $transArray[$i]['updated_at']   = $txn['t']['updates']?$txn['t']['updates']:$txn['t']['updated_at'];
                $transArray[$i]['reason']       = $txn['t']['reason'];
                $transArray[$i]['trans_type']   = $txn['t']['transaction_type'];
                $transArray[$i]['vendor']       = $txn['t']['vendor_name'];
                $transArray[$i]['gross_amount'] = $txn['t']['gross_amount'];
                $transArray[$i]['commission']  = $txn['t']['commission'];
                $transArray[$i]['pay1_charge'] = $txn['t']['pay1_charge'];
                $transArray[$i]['opening']     = $opening[$transArray[$i]['wallet_id']];
                $transArray[$i]['closing']     = $closing[$transArray[$i]['wallet_id']];

                $i++;
            }
            $shoptxn = $transArray[0]['wallet_id'];
            $refund_det = $this->Ekonew->query("Select * from refunds where shop_transaction_id = '$shoptxn' ");

                    // For getting retailer shopname
        foreach ($txnRes as $Mob) {
            $txnMob_id = $Mob['t']['retailer_id'];
        }
        $txn_Shop = $this->Slaves->query("Select id,mobile,Shopname from retailers where id = $txnMob_id");
        $txn_shopname = array();
        $txn_mob_id = array();

        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($txnMob_id,2,2);
        /** IMP DATA ADDED : END**/


        foreach ($txn_Shop as $txn_Shopdet) {
            //$txn_shopname[$txn_Shopdet['retailers']['id']] = $txn_Shopdet['retailers']['Shopname'];
            $txn_shopname[$txn_Shopdet['retailers']['id']] = $imp_data[$txn_Shopdet['retailers']['id']]['imp']['shop_est_name'];
            $txn_mob_id[$txn_Shopdet['retailers']['id']] = $txn_Shopdet['retailers']['mobile'];
        }
        //fetching tags
         $complain_tag = $this->Slaves->query('Select * from taggings_new where module_id = 62');
            $tagname = array();
            foreach ($complain_tag as $tags) {
                $tagname[$tags['taggings_new']['id']] = $tags['taggings_new']['name'];
            }
            //Inserting comments

            if (!empty($tag) && !empty($comm)) {
                $InsComment = $this->User->query('INSERT INTO `comments_new`(`user_id`, `ref_id`, `module_id`, `tag_id`, `subtag_id`, `comment`, `cc_id`, `created_date`, `created_at`)
                                            VALUES (' . $user_id . ',' . $order_id . ',62,0,' . $tag . ',"' . $comm . '",' . $cc_id . ',"' . date("Y-m-d") . '","' . date("Y-m-d H:i:s") . '")');
                
                header("location: /dmt/transactionReport/ekonew/$pay1txn/$txnid/$accno");
            //exit;   
            }
            //Selecting Comments
            if (!$accno) {
                $orderid = $transArray[0]['order_id'];
                $dmt_comments = $this->Slaves->query('Select * from comments_new where ref_id = ' . $orderid . '');
            }
            $username = $this->Slaves->query("Select id,name from users");

            $users = array();
            foreach ($username as $usr) {
                $users[$usr['users']['id']] = $usr['users']['name'];
            }

            //ended
            $this->set('pay1txnData', $transArray);
            $this->set('txn_shopname', $txn_shopname);
            $this->set('refund_det', $refund_date);
            $this->set('dmt_comments', $dmt_comments);
            $this->set('txn_mob_id', $txn_mob_id);
            $this->set('dmttags', $complain_tag);
            $this->set('tagname', $tagname);
            $this->set('users', $users);

            //ended
        } else {

            if(empty($accno))
                $accnum = '';
            else
                $accnum = "and ts.accountnumber = '$accno'";
            $txnRes = $this->Eko->query("Select ds.mobile,ds.retailer_id,ts.* from transactions as ts
                                                join dmt_users as ds on (ds.id = ts.dmtuser_id)
                                                where 1=1 $pay1txnId $txnId $accnum ");
            $i = 0;
            foreach ($txnRes as $txn) {
                $transArray[$i]['mobile'] = $txn['ds']['mobile'];
                $transArray[$i]['eko_txn_id'] = $txn['ts']['eko_txn_id'];
                $transArray[$i]['ret_id'] = $txn['ds']['retailer_id'];
                $transArray[$i]['wallet_id'] = $txn['ts']['shop_transaction_id'];
                $transArray[$i]['bank_id'] = $txn['ts']['bank_ref_num'];
                $transArray[$i]['order_id'] = $txn['ts']['id'];
                $transArray[$i]['send_name'] = $txn['ts']['sendername'];
                $transArray[$i]['send_mob'] = $txn['ts']['sender_mobile'];
                $transArray[$i]['bene_name'] = $txn['ts']['receivername'];
                $transArray[$i]['bene_accno'] = $txn['ts']['accountnumber'];
                $transArray[$i]['amount'] = $txn['ts']['gross_amount'];
                $transArray[$i]['status'] = $txn['ts']['pay1_status'];
                $transArray[$i]['created_at'] = $txn['ts']['created_at'];
                $transArray[$i]['remarks'] = $txn['ts']['remarks'];
                $transArray[$i]['reason']  = $txn['ts']['reason'];
                $transArray[$i]['bank_status'] = $txn['ts']['eko_transaction_status'];
                $transArray[$i]['npci_status'] = $txn['ts']['npci_response_code'];
                $transArray[$i]['updated_at'] = $txn['ts']['updated_at'];
                $transArray[$i]['trans_type'] = $txn['ts']['transaction_type'];
                $i++;
            }
            $shoptxn = $transArray[0]['wallet_id'];
            $refund_det = $this->Eko->query("Select * from refunds where shop_transaction_id = '$shoptxn' ");

                    // For getting retailer shopname
        foreach ($txnRes as $Mob) {
            $txnMob_id = $Mob[0]['ds']['retailer_id'];
        }
        if(empty($refund_det)) {
            $refund_date = "NA";
        } else {
            $refund_date = $refund_det[0]['refunds']['refund_date'];
        }
        // For getting retailer shopname
        foreach ($txnRes as $Mob) {
            $txnMob_id = $txnRes[0]['ds']['retailer_id'];
        }
        $txn_Shop = $this->Slaves->query("Select id,mobile,Shopname from retailers where id = $txnMob_id");
        $txn_shopname = array();
        $txn_mob_id = array();

        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($txnMob_id,2,2);
        /** IMP DATA ADDED : END**/


        foreach ($txn_Shop as $txn_Shopdet) {
            //$txn_shopname[$txn_Shopdet['retailers']['id']] = $txn_Shopdet['retailers']['Shopname'];
            $txn_shopname[$txn_Shopdet['retailers']['id']] = $imp_data[$txn_Shopdet['retailers']['id']]['imp']['shop_est_name'];
            $txn_mob_id[$txn_Shopdet['retailers']['id']] = $txn_Shopdet['retailers']['mobile'];
        }}

    }

    function dmtFromto($bankType = null, $recs = 100) {
        ini_set('memory_limit','2048M');
        $dmtData    = array();
        $getval     = $this->params['form']['search'];
        $dmtfrm     = $this->params['form']['dmt_from'];
        $dmtto      = $this->params['form']['dmt_till'];
        $trans      = $this->params['form']['transtatus'];
        $txntype    = $this->params['form']['trantype'];
        $txnmode    = $this->params['form']['transmode'];
        $txngrp_id  = $this->params['form']['dmt_groupid'];
        $vendor     = $this->params['form']['vendorType'];
        $pages = $this->params['form']['txnpage'];
        $export = $this->params['form']['fer_fld'];
        $transgrp = implode("','", $trans);
        $banktype = $this->params['form']['bankType'];
        $nodays = (strtotime($dmtto) - strtotime($dmtfrm)) / (60 * 60 * 24);
        $nodays += 1;

        if(!isset($bankType)) {
            $bankType = "eko";
        }
        if(!isset($txntype)) {
            $txntype = "-1";
        }
        if(empty($dmtfrm))
            $dmtfrm = date('Y-m-d');
        if(empty($dmtto))
            $dmtto = date('Y-m-d');
        $from_date = date("Y-m-d", strtotime($dmtfrm));
        $till_date = date("Y-m-d", strtotime($dmtto));
        if(empty($pages))
            $pages = 100;
        if(empty($trans))
            $transstatus = " and ts.pay1_status  NOT IN  ('2')";
        else
            $transstatus = " and ts.pay1_status IN  ('$transgrp')";
        if($txntype == '-1')
            $transtype = '';
        else
            $transtype = " and ts.src = '$txntype'";
         if(empty($txnmode))
            $transmode = '';
        else
            $transmode = " and ts.transaction_type = '$txnmode'";

        if(empty($txngrp_id))
            $txn_grp_id = '';

        else
            $txn_grp_id = " and ts.group_tag_id = '$txngrp_id'";

        if(empty($vendor))
            $vendor_id = '';

        else
            $vendor_id = " and vm.platform_vendor_id = '$vendor'";

        //for putting data and forwarding it to front end
        if($bankType == "ekonew") {

            $result = $this->paginate_query('Select *,ds.* from
                                            (Select
                                            ts.*,
                                            bs.acc_no,
                                            rs.name,
                                            rs.mobile,
                                            tt.vendor_txn_id,tt.vendor_txn_status,vm.vendor_name,vm.platform_vendor_id,tt.updated_at as updates
                                            from
                                            transactions_master as ts
                                            LEFT JOIN transaction_trails as tt on (tt.txn_id = ts.id) and (tt.vendor_id = ts.vendor_id)
                                            inner JOIN remitter_master as rs on (rs.id = ts.remitter_id)
                                            inner JOIN bene_master as bs on (bs.id = ts.bene_id)
                                            inner JOIN vendor_master as vm on (vm.id = ts.vendor_id)
                                             where ts.transaction_date  >= "' . $from_date . '" and ts.transaction_date <= "' . $till_date . '"
                                            ' . $transstatus . '  ' . $transtype . ' ' . $transmode . ' '. $txn_grp_id .'  '.$vendor_id.'
                                            order by tt.id desc
                                            ) as t
                                            join dmt_users as ds on (ds.id = t.dmtuser_id)
                                            group by t.id
                                            order by t.transaction_date'
                                            , $recs, array(), 'Ekonew');

        //For Fetching the vendor txn Id
        if (count($result) > 0) {
            /** IMP DATA ADDED : START* */
            $vendortxnid = array_map(function($element) {
                return $element['t']['id'];
            }, $result);

            $vendorid = implode("','", $vendortxnid);

              $shoptxnid = array_map(function($element) {
                    return $element['t']['shop_transaction_id'];
              }, $result);

             $shop_id = implode("','", $shoptxnid);
            }

            $tdsAmount = $this->Slaves->query("Select amount,target_id from shop_transactions where target_id IN ('$shop_id') and type = 31");

            $tdsamt = array();
            foreach($tdsAmount as $tds){
                $tdsamt[$tds['shop_transactions']['target_id']] =  $tds['shop_transactions']['amount'];
            }

            $statusval = $this->Ekonew->query("Select * from (select txn_id,vendor_txn_status,updated_at from transaction_trails where txn_id IN ('". $vendorid ."') order by id) as transaction_trails group by transaction_trails.txn_id order by transaction_trails.txn_id desc");

            foreach($statusval as $status){
                $statusid[$status['transaction_trails']['txn_id']] = $status['transaction_trails']['txn_id'];
                $statusvalue[$status['transaction_trails']['txn_id']] = $status['transaction_trails']['vendor_txn_status'];
                $statustime[$status['transaction_trails']['txn_id']] = $status['transaction_trails']['updated_at'];
            }
            foreach ($result as $bal) {
                $bal_array[] = $bal['t']['shop_transaction_id'];
            }

            $TxnDet = implode("','", $bal_array);
            $ret_opclose = $this->Slaves->query("select * FROM shop_transactions where id IN ('$TxnDet')");

            $opening = array();
            $closing = array();
            foreach ($ret_opclose as $opclose) {
                $opening[$opclose['shop_transactions']['id']] = $opclose['shop_transactions']['source_opening'];
                $closing[$opclose['shop_transactions']['id']] = $opclose['shop_transactions']['source_closing'];
            }

            $i = 0;
            foreach ($result as $res) {
                $dmtData[$i]['ret_id']      = $res['ds']['retailer_id'];
                $dmtData[$i]['mobile']      = $res['ds']['mobile'];
                $dmtData[$i]['order_id']    = $res['t']['id'];
                $dmtData[$i]['bank_txn_id'] = $res['t']['vendor_txn_id'];
                $dmtData[$i]['wallet_id']   = $res['t']['shop_transaction_id'];
                $dmtData[$i]['tds']         = $tdsamt[$dmtData[$i]['wallet_id']];
                $dmtData[$i]['send_id']     = $res['t']['remitter_id'];
                $dmtData[$i]['send_name']   = $res['t']['name'];
                $dmtData[$i]['send_mob']    = $res['t']['mobile'];
                $dmtData[$i]['bene_accntno'] = $res['t']['acc_no'];
                $dmtData[$i]['amount']      = $res['t']['amount'];
                $dmtData[$i]['pay1_status'] = $res['t']['pay1_status'];
                $dmtData[$i]['bank_status'] = $res['t']['vendor_txn_status'] ;
                $dmtData[$i]['trans_type'] = $res['t']['transaction_type'];
                $dmtData[$i]['remarks']     = $res['t']['remarks'];
                $dmtData[$i]['type']        = $res['t']['src'];
                $dmtData[$i]['date']        = $res['t']['transaction_date'];
                $dmtData[$i]['group_id']    = $res['t']['group_tag_id'];
                $dmtData[$i]['created_at'] = $res['t']['created_at'];
                $dmtData[$i]['updated_at'] = $res['t']['updates']?$res['t']['updates']:$res['t']['updated_at'];
                $dmtData[$i]['gross_amount'] = $res['t']['gross_amount'];
                $dmtData[$i]['vendor']      = $res['t']['vendor_name'];
                $dmtData[$i]['commission']  = $res['t']['commission'];
                $dmtData[$i]['pay1_charge'] = $res['t']['pay1_charge'];
                $dmtData[$i]['opening']     = $opening[$dmtData[$i]['wallet_id']];
                $dmtData[$i]['closing']     = $closing[$dmtData[$i]['wallet_id']];
                $i++;
            }

            //exit;
        //For success txn Count and amount
        $successTxn = $this->Ekonew->query("Select COUNT(id) as success_txn,SUM(amount) as amount from transactions_master where transaction_date  >= '$from_date' and transaction_date <= '$till_date' and pay1_status = '1' ");
        //For Pending txn Count and amount
        $pendingTxn = $this->Ekonew->query("Select COUNT(id) as success_txn,SUM(amount) as amount from transactions_master where transaction_date  >= '$from_date' and transaction_date <= '$till_date' and pay1_status = '-1' ");

        } else {

            if($nodays <= 10 ) {
            $result = $this->paginate_query('Select ds.*,ts.* from transactions as ts
                                            LEFT JOIN dmt_users as ds on (ds.id = ts.dmtuser_id)
                                            where ts.transaction_date  >= "' . $from_date . '" and ts.transaction_date <= "' . $till_date . '"
                                    ' . $transstatus . '  ' . $transtype . ' ' . $transmode . ' '. $txn_grp_id .' order by ts.updated_at desc', $recs, array(), 'Eko');
            }
            $i = 0;
            foreach ($result as $res) {
                $dmtData[$i]['ret_id'] = $res['ds']['retailer_id'];
                $dmtData[$i]['mobile'] = $res['ds']['mobile'];
                $dmtData[$i]['order_id'] = $res['ts']['id'];
                $dmtData[$i]['bank_txn_id'] = $res['ts']['eko_txn_id'];
                $dmtData[$i]['wallet_id'] = $res['ts']['shop_transaction_id'];
                $dmtData[$i]['send_name'] = $res['ts']['sendername'];
                $dmtData[$i]['send_mob'] = $res['ts']['sender_mobile'];
                $dmtData[$i]['bene_accntno'] = $res['ts']['accountnumber'];
                $dmtData[$i]['amount'] = $res['ts']['amount'];
                $dmtData[$i]['pay1_status'] = $res['ts']['pay1_status'];
                $dmtData[$i]['bank_status'] = $res['ts']['eko_transaction_status'];
                $dmtData[$i]['remarks'] = $res['ts']['remarks'];
                $dmtData[$i]['type'] = $res['ts']['src'];
                $dmtData[$i]['date'] = $res['ts']['transaction_date'];
                $dmtData[$i]['created_at'] = $res['ts']['created_at'];
                $dmtData[$i]['updated_at'] = $res['ts']['updated_at'];
                $dmtData[$i]['gross_amount'] = $res['ts']['gross_amount'];
                $dmtData[$i]['trans_type'] = $res['ts']['transaction_type'];
                $dmtData[$i]['group_id'] = $res['ts']['group_tag_id'];
                $dmtData[$i]['vendor']      = $res['t']['vendor_name']; // need to map on eko
                $i++;
            }

                //For success txn Count and amount
        $successTxn = $this->Eko->query("Select COUNT(id) as success_txn,SUM(amount) as amount from transactions where transaction_date  >= '$from_date' and transaction_date <= '$till_date' and pay1_status = '1' ");
        //For Pending txn Count and amount
        $pendingTxn = $this->Eko->query("Select COUNT(id) as success_txn,SUM(amount) as amount from transactions where transaction_date  >= '$from_date' and transaction_date <= '$till_date' and pay1_status = '-1' ");

        }
        foreach ($result as $retmobid) {
            $retmobId[] = $retmobid['ds']['retailer_id'];
        }

        $retailer_Mobile_id = implode(",", $retmobId);

        $ret_shopname = $this->Slaves->query("Select id,user_id,mobile,shopname from retailers where id IN ($retailer_Mobile_id)");
        $retailerForm = array();
        $retailerFormMobid = array();

        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($retailer_Mobile_id,2,2);
        /** IMP DATA ADDED : END**/


        foreach ($ret_shopname as $rshop) {
            //$retailerForm[$rshop['retailers']['id']] = $rshop['retailers']['shopname'];
            $retailerForm[$rshop['retailers']['id']] = $imp_data[$rshop['retailers']['id']]['imp']['shop_est_name'];
            $retailerFormMobid[$rshop['retailers']['id']] = $rshop['retailers']['mobile'];
        }
            //for getting vendor details.
            $vendorDet = $this->Ekonew->query('Select * from vendor_master where  is_service_active = 1 ');
        if($export == '') {
            $this->set('report_data', $dmtData);
            $this->set('ret_sname', $ret_shopname);
            $this->set('ret_array', $retailerForm);
            $this->set('ret_arrayMobid', $retailerFormMobid);
            $this->set('vendorDet',$vendorDet);
            $this->set('transtatus', $trans);
            $this->set('pages', $pages);
            $this->set('frm', $from_date);
            $this->set('tos', $till_date);
            $this->set('hidden_fld', $getval);
            $this->set('txntype', $txntype);
            $this->set('transmode',$txnmode);
            $this->set('recs', $recs);
            $this->set('banktype', $bankType);
            $this->set('successTxn',$successTxn);
            $this->set('pendingTxn',$pendingTxn);
            $this->set('txngrp_id',$txngrp_id);
            $this->set('days',$nodays);
            $this->set('export',$export);
            $this->set('vendor',$vendor);
        } else {
            $this->autoRender = false;
            App::import('Helper', 'csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();
            $line = array();
            $resultarr = array();
            $txnid = array();
            $retailerUser = array();
            $retailerId = array();
            $retMargin = array();
            $dist_id = array();
            $retmob_id = array();
            $pay1txn_status = Configure::read('Remit_pay1_status');
            $src_status = array(1 => 'Web', 0 => 'App');

            if($bankType == "ekonew") {
                $line = array('0' => 'Distributor Id', '1' => 'Retailer Id', '2' => 'Retailer Mobile', '3' => 'Agent Id', '4' => 'Order Id','5' => 'Bank TransactionId', '6' => 'Pay1 TrasactionId',
                    '7' => 'Eko TransactionId', '8' => 'Sender Mobile', '9' => 'Benefiary Account No','10' => 'Bank','11' => 'Ifsc code',
                    '12' => 'Amount', '13' => 'Pay1 Charges', '14' => 'Eko Charges', '15' => 'Plan','16' => 'Pay1 Margin','17' => 'Mode', '18' => 'Pay1 Status', '19' => 'Bank Status', '20' => 'Remarks',
                    '21' => 'Type','22' => 'City' ,'23' => 'State','24' => 'Transaction Date', '25' => 'Transaction Created On', '26' => 'Transaction Updated On','27' => 'Reason','28' => 'Group Id','29' => 'Dist_Shopname'
                    ,'30' => 'Dist_Area','31' => 'Dist_City','32' => 'Dist_State','33' => 'Vendor','34' => 'Transaction Amount','35' => 'Service Charge','36' => 'Commission','37'=>'Opening','38'=>'Closing','39'=>'Tds'
                );
                $banktxn_status = Configure::read('Remit_bank_status.eko');
                $resultEx = $this->Ekonew->query('Select *,ds.* from
                                            (Select
                                            ts.*,
                                            bs.acc_no,bs.ifsc,bs.id as bene,bs.bank,
                                            rs.name,
                                            rs.mobile,
                                            tt.vendor_txn_id,tt.vendor_txn_status,tt.updated_at as updates,
                                            tt.reason,vm.vendor_name
                                            from
                                            transactions_master as ts
                                            LEFT JOIN transaction_trails as tt on (tt.txn_id = ts.id) and (tt.vendor_id = ts.vendor_id)
                                            inner JOIN remitter_master as rs on (rs.id = ts.remitter_id)
                                            inner JOIN bene_master as bs on (bs.id = ts.bene_id)
                                            inner JOIN vendor_master as vm on (vm.id = ts.vendor_id)
                                             where ts.transaction_date  >= "' . $from_date . '" and ts.transaction_date <= "' . $till_date . '"
                                            ' . $transstatus . '  ' . $transtype . ' ' . $transmode . ' '. $txn_grp_id .' '.$vendor_id.'
                                            order by tt.id desc
                                            ) as t
                                            join dmt_users as ds on (ds.id = t.dmtuser_id)
                                            group by t.id
                                            order by t.transaction_date');
                /* For Getting the margin through retailer userid */
                foreach ($resultEx as $retus) {
                    $retailerUser[] = $retus['ds']['user_id']; //for getting retailer user_id from dmt/eko
                    $retailerId[] = $retus['ds']['retailer_id'];
                    $bankId[]     = $retus['t']['bene'];
                    $bal_array[]  = $retus['t']['shop_transaction_id'];
                }

              if (count($resultEx) > 0) {
              $shoptxnid = array_map(function($element) {
                    return $element['t']['shop_transaction_id'];
              }, $resultEx);

             $shop_id = implode("','", $shoptxnid);
            }

            $tdsAmount = $this->Slaves->query("Select amount,target_id from shop_transactions where target_id IN ('$shop_id') and type = 31");

            $tdsamt = array();
            foreach($tdsAmount as $tds){
                $tdsamt[$tds['shop_transactions']['target_id']] =  $tds['shop_transactions']['amount'];
            }

                 // Opening closing
                $TxnDet = implode("','", $bal_array);
                $ret_opclose = $this->Slaves->query("select * FROM shop_transactions where id IN ('$TxnDet')");

                $opening = array();
                $closing = array();
                foreach ($ret_opclose as $opclose) {
                    $opening[$opclose['shop_transactions']['id']] = $opclose['shop_transactions']['source_opening'];
                    $closing[$opclose['shop_transactions']['id']] = $opclose['shop_transactions']['source_closing'];
                }
                //
                $retUsersid = implode(',', $retailerUser);
                $retid = implode(',', $retailerId);
                $bankid = implode(',',$bankId);
                //For getting the Margin from user Services table
                $retSlab = $this->Slaves->query("Select user_id,users_services.param1,service_plans.plan_name as param2 from users_services LEFT JOIN service_plans ON (users_services.service_plan_id = service_plans.id) where user_id IN ($retUsersid) and users_services.service_id = '12'");
                foreach ($retSlab as $slab) {
                    $retMargin[$slab['users_services']['user_id']] = $slab['service_plans']['param2'];
                    $retAgentId[$slab['users_services']['user_id']] = $slab['users_services']['param1'];
                }
                /* End */
                //For getting the distributor id */
                $distId = $this->Slaves->query("Select rs.parent_id,rs.user_id,rs.id,rs.mobile,ds.area_range,ds.city,ds.state,ds.company  from retailers as rs
                            INNER JOIN distributors as ds ON (ds.id = rs.parent_id)where rs.id IN ($retid)");

                foreach ($distId as $dist) {
                    $dist_id[$dist['rs']['id']]     = $dist['rs']['parent_id'];
                    $retmob_id[$dist['rs']['id']]   = $dist['rs']['mobile'];
                    $dshop[$dist['rs']['id']]       = $dist['ds']['company'];
                    $d_arearange[$dist['rs']['id']] = $dist['ds']['area_range'];
                    $d_city[$dist['rs']['id']]      = $dist['ds']['city'];
                    $d_state[$dist['rs']['id']]     = $dist['ds']['state'];
                }

                //For getting the bank name */
                $bank_name = $this->Ekonew->query("Select id,bank_name from banks where id IN ($bankid)");
                foreach($bank_name as $bankn){
                    $bank_id[$bankn['banks']['id']] = $bankn['banks']['bank_name'];
                }
        $retLocation = $this->Slaves->query("select rl.retailer_id,rl.area_id,la.city_id,la.name,lc.state_id,lc.name,
                                             ls.name from retailers_location as rl left join locator_area as la on (rl.area_id = la.id)
                                             left join locator_city as lc  on (la.city_id = lc.id)
                                             left join locator_state as ls on (lc.state_id = ls.id)
                                             where rl.retailer_id IN ($retid)");

        foreach ($retLocation as $retLoc) {
            $retailer_city[$retLoc['rl']['retailer_id']] = $retLoc['lc']['name'];
            $retailer_state[$retLoc['rl']['retailer_id']] = $retLoc['ls']['name'];
        }

        //For Fetching the vendor txn Id
                if (count($resultEx) > 0) {
                    /** IMP DATA ADDED : START* */
                    $vendortxnid = array_map(function($element) {
                        return $element['t']['id'];
                    }, $result);

                    $vendorid = implode("','", $vendortxnid);
                }

                $statusval = $this->Ekonew->query("Select * from (Select vendor_txn_id,txn_id,vendor_fee,vendor_txn_status,updated_at from transaction_trails where txn_id IN ('" . $vendorid . "') order by id) as transaction_trails group by transaction_trails.txn_id order by transaction_trails.txn_id desc");

                foreach ($statusval as $status) {
                    $statusvalue[$status['transaction_trails']['txn_id']]   = $status['transaction_trails']['vendor_txn_status'];
                    $statustime[$status['transaction_trails']['txn_id']]    = $status['transaction_trails']['updated_at'];
                    $Ekocharge[$status['transaction_trails']['txn_id']]     = $status['transaction_trails']['vendor_fee'];
                    $reason[$status['transaction_trails']['txn_id']]        = $status['transaction_trails']['reason'];
                }
                $i = 39;
                $csv->addRow($line);

                foreach ($resultEx as $data):

                    $temp[0] = $dist_id[$data['ds']['retailer_id']];
                    $temp[1] = $data['ds']['retailer_id'];
                    $temp[2] = $retmob_id[$data['ds']['retailer_id']];
                    $temp[3] = $retAgentId[$data['ds']['user_id']];
                    $temp[4] = $data['t']['id'];
                    $temp[5] = $data['t']['bank_ref_num'];
                    $temp[6] = $data['t']['shop_transaction_id'];
                    $temp[7] = $data['t']['vendor_txn_id'];
                    $temp[8] = $data['t']['mobile'];
                    $temp[9] = $data['t']['acc_no'];
                    $temp[10] = $data['t']['bank'];
                    $temp[11] = $data['t']['ifsc'];
                    $temp[12] = $data['t']['amount'];
                    $temp[13] = $data['t']['pay1_charge'];
                    $temp[14] = $Ekocharge[$data['t']['id']];
                    $temp[15] = $retMargin[$data['ds']['user_id']];
                    $temp[16] = ($data['t']['pay1_charge'] > 10)?round($data['t']['pay1_charge']*100/$data['t']['amount'],PHP_ROUND_HALF_EVEN):$retMargin[$data['ds']['user_id']];
                    $temp[17] = ($data['t']['transaction_type'] == 1)? 'NEFT':'IMPS';
                    $temp[18] = $pay1txn_status[$data['t']['pay1_status']];
                    $temp[19] = $banktxn_status[$data['t']['vendor_txn_status']];
                    $temp[20] = $data['t']['remarks'];
                    $temp[21] = $src_status[$data['t']['src']];
                    $temp[22] = $retailer_city[$data['ds']['retailer_id']];
                    $temp[23] = $retailer_state[$data['ds']['retailer_id']];
                    $temp[24] = $data['t']['transaction_date'];
                    $temp[25] = $data['t']['created_at'];
                    $temp[26] = $data['t']['updates']?$data['t']['updates']:$data['t']['updated_at'];
                    $temp[27] = $data['t']['reason'];
                    $temp[28] = $data['t']['group_tag_id'];
                    $temp[29] = $dshop[$data['ds']['retailer_id']];
                    $temp[30] = $d_arearange[$data['ds']['retailer_id']];
                    $temp[31] = $d_city[$data['ds']['retailer_id']];
                    $temp[32] = $d_state[$data['ds']['retailer_id']];
                    $temp[33] = $data['t']['vendor_name'];
                    $temp[34] = $data['t']['gross_amount'];
                    $temp[35] = $data['t']['pay1_charge'];
                    $temp[36] = $data['t']['commission'];
                    $temp[37] = $opening[$data['t']['shop_transaction_id']];
                    $temp[38] = $closing[$data['t']['shop_transaction_id']];
                    $temp[39] = $tdsamt[$data['t']['shop_transaction_id']];
                    $csv->addRow($temp);
                    $i ++;
                endforeach;
                echo $csv->render('EKONEW_' . $from_date . '_' . $till_date . '.csv');
            }
            else {
                $line = array('0' => 'Distbutor Id', '1' => 'Retailer Id', '2' => 'Retailer Mobile', '3' => 'Agent Id', '4' => 'Order Id','5' => 'Bank TransactionId', '6' => 'Pay1 TrasactionId',
                    '7' => 'Eko TransactionId', '8' => 'Sender Mobile', '9' => 'Benefiary Account No','10' => 'Bank','11' => 'Ifsc code',
                    '12' => 'Amount', '13' => 'Pay1 Charges', '14' => 'Eko Charges', '15' => 'Plan','16' => 'Pay1 Margin','17' => 'Mode', '18' => 'Pay1 Status', '19' => 'Bank Status', '20' => 'Remarks',
                    '21' => 'Type','22' => 'City' ,'23' => 'State','24' => 'Transaction Date', '25' => 'Transaction Created On', '26' => 'Transaction Updated On','27' => 'Reason','28' => 'Group Id','29' => 'Dist_Shopname'
                    ,'30' => 'Dist_Area','31' => 'Dist_City','32' => 'Dist_State'
                );
                $banktxn_status = Configure::read('Remit_bank_status.eko');
                $resultEx = $this->Eko->query("Select ds.*,ts.* from transactions as ts
                                                join dmt_users as ds on (ds.id = ts.dmtuser_id)
                                                where ts.transaction_date  between '" . $from_date . "' and '" . $till_date . "'
                                                $transstatus  $transtype $transmode $txn_grp_id order by ts.updated_at desc");
                /* For Getting the margin through retailer userid */
                foreach ($resultEx as $retus) {
                    $retailerUser[] = $retus['ds']['user_id']; //for getting retailer user_id from dmt/eko
                    $retailerId[] = $retus['ds']['retailer_id'];
                }
                $retUsersid = implode(',', $retailerUser);
                $retid = implode(',', $retailerId);
                //For getting the Margin from user Services table
                $retSlab = $this->Slaves->query("Select user_id,service_plans.plan_name as param1 from users_services LEFT JOIN service_plans ON (users_services.service_plan_id = service_plans.id) where user_id IN ($retUsersid) and users_services.service_id = '12'");
                foreach ($retSlab as $slab) {
                    $retMargin[$slab['users_services']['user_id']] = $slab['service_plans']['param1'];
                }
                /* End */

                //For getting the distributor id */
                $distId = $this->Slaves->query("Select rs.parent_id,rs.user_id,rs.id,rs.mobile,ds.area_range,ds.city,ds.state,ds.company  from retailers as rs
                            INNER JOIN distributors as ds ON (ds.id = rs.parent_id)where rs.id IN ($retid)");

                foreach ($distId as $dist) {
                    $dist_id[$dist['rs']['id']]     = $dist['rs']['parent_id'];
                    $retmob_id[$dist['rs']['id']]   = $dist['rs']['mobile'];
                    $dshop[$dist['rs']['id']]       = $dist['ds']['company'];
                    $d_arearange[$dist['rs']['id']] = $dist['ds']['area_range'];
                    $d_city[$dist['rs']['id']]      = $dist['ds']['city'];
                    $d_state[$dist['rs']['id']]     = $dist['ds']['state'];
                }


                //For getting city and state name

            $retLocation = $this->Slaves->query("select rl.retailer_id,rl.area_id,la.city_id,la.name,lc.state_id,lc.name,
                                             ls.name from retailers_location as rl left join locator_area as la on (rl.area_id = la.id)
                                             left join locator_city as lc  on (la.city_id = lc.id)
                                             left join locator_state as ls on (lc.state_id = ls.id)
                                             where rl.retailer_id IN ($retid)");

               foreach ($retLocation as $retLoc) {
                   $retailer_city[$retLoc['rl']['retailer_id']] = $retLoc['lc']['name'];
                   $retailer_state[$retLoc['rl']['retailer_id']] = $retLoc['ls']['name'];
             }

                $i = 32;
                $csv->addRow($line);

                foreach ($resultEx as $data):

                    $temp[0] = $dist_id[$data['ds']['retailer_id']];
                    $temp[1] = $data['ds']['retailer_id'];
                    $temp[2] = $retmob_id[$data['ds']['retailer_id']];
                    $temp[3] = $data['ds']['bcagent_id'];
                    $temp[4] = $data['ts']['id'];
                    $temp[5] = $data['ts']['bank_ref_num'];
                    $temp[6] = $data['ts']['shop_transaction_id'];
                    $temp[7] = $data['ts']['eko_txn_id'];
                    $temp[8] = $data['ts']['sender_mobile'];
                    $temp[9] = $data['ts']['accountnumber'];
                    $temp[10] = $data['ts']['bank'];
                    $temp[11] = $data['ts']['ifsc_bankcode'];
                    $temp[12] = $data['ts']['amount'];
                    $temp[13] = $data['ts']['pay1_charge'];
                    $temp[14] = $data['ts']['service_charge'];
                    $temp[15] = $retMargin[$data['ds']['user_id']];
                    $temp[16] = ($data['ts']['pay1_charge'] > 10)?round($data['ts']['pay1_charge']*100/$data['ts']['amount'],PHP_ROUND_HALF_EVEN):$retMargin[$data['ds']['user_id']];
                    $temp[17] = ($data['ts']['transaction_type'] == 1)? 'NEFT':'IMPS';
                    $temp[18] = $pay1txn_status[$data['ts']['pay1_status']];
                    $temp[19] = $banktxn_status[$data['ts']['eko_transaction_status']];
                    $temp[20] = $data['ts']['remarks'];
                    $temp[21] = $src_status[$data['ts']['src']];
                    $temp[22] = $retailer_city[$data['ds']['retailer_id']];
                    $temp[23] = $retailer_state[$data['ds']['retailer_id']];
                    $temp[24] = $data['ts']['transaction_date'];
                    $temp[25] = $data['ts']['created_at'];
                    $temp[26] = $data['ts']['updated_at'];
                    $temp[27] = $data['ts']['reason'];
                    $temp[28] = $data['ts']['group_tag_id'];
                    $temp[29] = $dshop[$data['ds']['retailer_id']];
                    $temp[30] = $d_arearange[$data['ds']['retailer_id']];
                    $temp[31] = $d_city[$data['ds']['retailer_id']];
                    $temp[32] = $d_state[$data['ds']['retailer_id']];
                    $csv->addRow($temp);
                    $i ++;
                endforeach;
                echo $csv->render('EKO_' . $from_date . '_' . $till_date . '.csv');
            }
        }

     //   $this->render('dmtfromto');
    }

    function beneficiaryData() {
        $this->autoRender = FALSE;
        $id = $this->params['form']['benid'];
        $banktype = $this->params['form']['banktype'];
        $bene_Array = array();
        if($banktype == "ekonew") {
            $ben = $this->Ekonew->query("select rbm.bene_mobile,b.* from bene_master as b
                join remitter_bene_vendor_mapping as rbm on (rbm.bene_id = b.id) where b.id = '$id'");
            foreach ($ben as $b) {
                $bene_Array['name'] = $b['b']['real_name'];
                $bene_Array['accno'] = $b['b']['acc_no'];
                $bene_Array['mob'] = $b['rbm']['bene_mobile'];
                $bene_Array['email'] = $b['b']['email'];
                $bene_Array['bank_name'] = $b['b']['bank'];
                $bene_Array['ifsc_code'] = $b['b']['ifsc'];
            }
        } else {
            $ben = $this->Eko->query("select * from transactions as ts where receiver_id  = '$id'");
            foreach ($ben as $b) {
                $bene_Array['name'] = $b['ts']['receivername'];
                $bene_Array['accno'] = $b['ts']['accountnumber'];
                $bene_Array['mob'] = $b['ts']['receiver_mobile'];
                $bene_Array['email'] = $b['ts']['receiver_email'];
                $bene_Array['bank_name'] = $b['ts']['bank'];
                $bene_Array['ifsc_code'] = $b['ts']['ifsc_bankcode'];
            }
        }
        echo json_encode($bene_Array);
    }

    function dmtCheckRefund() {
        $this->autoRender = FALSE;
        $mobile = $this->params['form']['ret_mob'];
        //$result = $this->Slaves->query("Select user_id from retailers where mobile = $mobile");
        $params1 = $this->params['form']['txn_id'];
        $type   = $this->params['form']['type'];
        if($type == 'eko'){
        $param = DMT_CHECKSTATUS;
        }else{
         $param = NEWEKO_CHECKSTATUS;
        }
        $params3 = $_COOKIE['CAKEPHP'];

        $url = $param."api/checkstatus?txid=$params1&token=$params3";

        $status = $this->General->curl_post($url, null, 'GET');
        $jsonval = json_decode($status['output'], TRUE);
        return $jsonval['description']['data']['tobeRefunded'];

    }

    function accvalidationreport($bankType = null) {
        $this->layout=false;
        $dmtfrm = $this->params['form']['dmt_from'];
        $dmtto = $this->params['form']['dmt_till'];

        if (empty($dmtfrm))
            $dmtfrm = date('Y-m-d');
        if (empty($dmtto))
            $dmtto = date('Y-m-d');
        $from_date = date("Y-m-d", strtotime($dmtfrm));
        $till_date = date("Y-m-d", strtotime($dmtto));


        if($bankType == 'ekonew') {
        $export = $this->params['form']['fer_fld'];

        $sql = "Select accountvalidations.*,banks_master.bank_name,if(vendor_status = 1, '1', '0') AS wasretailerdebited,
       dmt_users.mobile,dmt_users.user_id,vendor_master.vendor_name"
                . " from accountvalidations "
                . "  join banks_master"
                . " on accountvalidations.bank_id=banks_master.id  "
                . " JOIN dmt_users  "
                . " ON dmt_users.id = accountvalidations.dmtuser_id "
                . "inner JOIN vendor_master on (vendor_master.id = accountvalidations.vendor_id)"
                . "  where accountvalidations.created_date >= '". $from_date ."' and accountvalidations.created_date <= '". $till_date ."' order by created_timestamp DESC";
        $results = $this->Ekonew->query($sql);

        $user_ids = array_map(function($element){
            return $element['dmt_users']['user_id'];
        },$results);

        $users = implode('","',$user_ids);

        $margin = $this->Serviceintegration->getPlanRetMarginDetails(160,12,$users);
        $acc_Array = array();
            $i = 0;

            foreach($results as $acc) {
                $acc_Array[$i]['mobile']        = $acc['dmt_users']['mobile'];
                $acc_Array[$i]['ret_margin']    = json_decode($margin[$acc['dmt_users']['user_id']],true);
                $acc_Array[$i]['accno']         = $acc['accountvalidations']['acc_no'];
                $acc_Array[$i]['bank_name']     = $acc['banks_master']['bank_name'];
                $acc_Array[$i]['bene_name']     = $acc['accountvalidations']['benename'];
                $acc_Array[$i]['eko_status']    = $acc['accountvalidations']['vendor_status'];
                $acc_Array[$i]['debited']       = $acc['0']['wasretailerdebited'];
                $acc_Array[$i]['timestamp']     = $acc['accountvalidations']['created_timestamp'];
                $acc_Array[$i]['eko_response']  = $acc['accountvalidations']['vendor_response'];
                $acc_Array[$i]['vendor']        = $acc['vendor_master']['vendor_name'];
                $acc_Array[$i]['arr']           = json_decode($acc_Array[$i]['eko_response'], true);
                $acc_Array[$i]['tid']           = $acc_Array[$i]['arr']['data']['tid'];
                $acc_Array[$i]['bankittxnId']   = $acc_Array[$i]['arr']['data']['txnId'];
                $acc_Array[$i]['fee']           = $acc_Array[$i]['arr']['data']['fee'];
                $acc_Array[$i]['bankit_fee']    = $acc_Array[$i]['arr']['errorCode'];
                $i++;
            }
        }


      if($export == "") {
            $this->set('results_count', count($acc_Array));
            $this->set('banktype',$bankType);
            $this->set('results', $acc_Array);
            $this->set('frm', $from_date);
            $this->set('tos', $till_date);
      }
      else {
            $this->autoRender = false;
            App::import('Helper', 'csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();
            $line = array();

             $line = array('0' => 'Retailer no', '1' => 'Acc', '2' => 'Bank','3' => 'Benename', '4' => 'Vendor', '5' => 'Status','6' => 'tid', '7' => 'wallet debited',
                    '8' => 'Amount debited from retailers', '9' => 'Amount debited from pay1', '10' => 'Created at', '11' => 'Response'
                );

                $i = 12;
                $csv->addRow($line);

                foreach ($acc_Array as $data){
                $margin = array();
                    $temp[0] = $data['mobile'];
                    $temp[1] = $data['accno'];
                    $temp[2] = $data['bank_name'];
                    $temp[3] = $data['bene_name'];
                    $temp[4] = $data['vendor'];
                    $temp[5] = $data['eko_status'];
                    $temp[6] = ($data['tid']) ? $data['tid'] : $data['bankittxnId'];
                    $temp[7] = $data['debited'] == '1' ? 'yes' : 'no';
                    foreach($data['ret_margin'] as $key => $value) {
                       $margin[] =  $value['margin'];
                    }
                    $temp[8] = implode('',$margin);
//                 $data['debited'] == '1' ? '4.00' : '0.00';
                    if($temp[4] == 'bankit' && $data['bankit_fee'] == '00'){
                    $temp[9] = '2.00';
                    }else {
                    $temp[9] = ($data['fee']) ? $data['fee'] : "NA";
                    }
                    $temp[10] = $data['timestamp'];
                    $temp[11] = $data['eko_response'];
                    $csv->addRow($temp);
                    $i ++;
                }
                echo $csv->render('AccValidation_' . $from_date . '_' . $till_date . '.csv');
        }

    }
function dmtAdminPanel(){
    $this->layout = "plain";
    
    
      
    $vendorDet = $this->Ekonew->query('Select platform_vendor_id,vendor_name  from vendor_master where is_vendor_active = 1');
    $notfDet   = $this->Ekonew->query('Select * from communications');

    $planDet  =  $this->Slaves->query('Select id,plan_name from service_plans where service_id = 12 and is_active = 1');

    $this->set('vendorDet',$vendorDet);
    $this->set('notfDet',$notfDet);
    $this->set('planDet',$planDet);
    if($this->RequestHandler->isAjax()){
      $fromd      = $this->params['form']['from'];
      $fromt      = $this->params['form']['fromt'];
      $tod        = $this->params['form']['to'];
      $tot        = $this->params['form']['tot'];
    $priority   = $this->params['form']['priority'];
    $vendor     = $this->params['form']['vendor'];
    $message    = $this->params['form']['message'];
    $plan       = $this->params['form']['plan'];
    $user       = $_SESSION['Auth']['User']['id'];

    $from = $fromd .' '. $fromt;
    $to   = $tod .' '. $tot;  
     //$main_url = Configure::read('DMT_URLS');         
    $url    = NEWEKO_URL.'api/insertMessage';                
    Configure::load('bridge');
    $configs = Configure::read('secrets');
    $secret = $configs['dmt']['secret'];

    if($fromd == '' || $tod == '') {
    $data = array('message' => $message,
                   'panel_user_id' => $user,'vendor_id' => $vendor,'priority' => $priority,'plan' => $plan);

    $token = $this->General->tokenGenerator($data, $secret);

    $datas = array('token' => $token,'message' => $message,
                   'panel_user_id' => $user,'vendor_id' => $vendor,'priority' => $priority,'plan' => $plan);

    } else {
        $data = array('message' => $message,'display_from' => $from,'display_to' => $to ,
                   'panel_user_id' => $user,'vendor_id' => $vendor,'priority' => $priority,'plan' => $plan);

        $token = $this->General->tokenGenerator($data, $secret);

        $datas = array('token' => $token,'message' => $message,'display_from' => $from,'display_to' => $to,
                   'panel_user_id' => $user,'vendor_id' => $vendor,'priority' => $priority,'plan' => $plan);
    }
    $out = $this->General->dmtNotification($url, $datas);

    $out = json_decode($out['output'], TRUE);
    $status = $out['status'];
    $msg = ($out['description']['msg']);

    $response = array(
        'status' => $status,
        'description' => $msg

    );

    echo json_encode($response);exit;
    }


}

    function dmtUpdateNotification(){
        $this->autoRender = 'FALSE';

        $id       = $this->params['form']['id'];
        $from       = $this->params['form']['from'];
        $to         = $this->params['form']['to'];
        $priority   = $this->params['form']['priority'];
        $vendor     = $this->params['form']['vendor'];
        $message    = $this->params['form']['message'];
        $flag       = $this->params['form']['flag'];
        $plan       = $this->params['form']['plan'];
        $user       = $_SESSION['Auth']['User']['id'];
//        $main_url = Configure::read('DMT_URLS');         
        $url     =  NEWEKO_URL.'api/updateMessage';    
        Configure::load('bridge');
        $configs = Configure::read('secrets');
        $secret = $configs['dmt']['secret'];

        $data = array('message' => $message,'display_from' => $from,'display_to' => $to ,
                       'panel_user_id' => $user,'vendor_id' => $vendor,'priority' => $priority,'id'=> $id,'show_flag'=> $flag,'plan' => $plan);
        $token = $this->General->tokenGenerator($data, $secret);
        $datas = array('token' => $token,'message' => $message,'display_from' => $from,'display_to' => $to,
                       'panel_user_id' => $user,'vendor_id' => $vendor,'priority' => $priority,'id'=> $id,'show_flag'=> $flag,'plan' => $plan);
        $out = $this->General->dmtNotification($url, $datas);

        $out = json_decode($out['output'], TRUE);
        $status = $out['status'];
        $msg = ($out['description']['msg']);

        $response = array(
            'status' => $status,
            'description' => $msg

        );

        echo json_encode($response);exit;


        }

        function serviceToggle(){
            $this->layout = "plain";
            $vendorDet = $this->Ekonew->query('Select * from vendor_master where  is_service_active = 1 ');
            $this->set('vendorDet',$vendorDet);


            if($this->RequestHandler->isAjax()){
            $show       = $this->params['form']['toggle_show'];
            $type       = $this->params['form']['toggle_type'];
            $vendor     = $this->params['form']['toggle_vendor'];
            $user       = $_SESSION['Auth']['User']['id'];
            
            //$main_url = Configure::read('DMT_URLS');         
            $url     =  NEWEKO_URL.'api/toggleVendorService'; 
            Configure::load('bridge');
            $configs = Configure::read('secrets');
            $secret = $configs['dmt']['secret'];

            $data = array('flag' => $show,'type' => $type,'vendor_id' => $vendor);
            $token = $this->General->tokenGenerator($data, $secret);
            $datas = array('token' => $token,'flag' => $show,'type' => $type,'vendor_id' => $vendor);
            $out = $this->General->dmtNotification($url, $datas);

            $out = json_decode($out['output'], TRUE);
            $status = $out['status'];
            $msg = ($out['description']['msg']);

            $response = array(
                'status' => $status,
                'description' => $msg

            );

            echo json_encode($response);exit;
            }
        }

        function refundPanel(){
            $this->layout = 'plain';
            $interval = "-90 days";
                    $result = $this->Ekonew->query('Select *,ds.* from
                                            (Select
                                            ts.*,
                                            bs.acc_no,
                                            rs.name,
                                            rs.mobile,
                                            tt.vendor_txn_id,tt.vendor_txn_status,vm.vendor_name,vm.platform_vendor_id,tt.updated_at as updates
                                            from
                                            transactions_master as ts
                                            inner JOIN transaction_trails as tt on (tt.txn_id = ts.id) and (tt.vendor_id = ts.vendor_id)
                                            inner JOIN remitter_master as rs on (rs.id = ts.remitter_id)
                                            inner JOIN bene_master as bs on (bs.id = ts.bene_id)
                                            inner JOIN vendor_master as vm on (vm.id = ts.vendor_id)
                                             where (ts.transaction_date < DATE_SUB(now(), INTERVAL 3 MONTH)) and (ts.pay1_status = 3) and (tt.vendor_txn_status = 3)
                                            order by tt.id desc
                                            ) as t
                                            join dmt_users as ds on (ds.id = t.dmtuser_id)
                                            group by t.id
                                            order by t.transaction_date');


       $dmtData = array();
            $i = 0;
            foreach ($result as $res) {
                $dmtData[$i]['ret_id']       = $res['ds']['retailer_id'];
                $dmtData[$i]['mobile']       = $res['ds']['mobile'];
                $dmtData[$i]['order_id']     = $res['t']['id'];
                $dmtData[$i]['bank_txn_id']  = $res['t']['vendor_txn_id'];
                $dmtData[$i]['wallet_id']    = $res['t']['shop_transaction_id'];
                $dmtData[$i]['send_id']      = $res['t']['remitter_id'];
                $dmtData[$i]['send_name']    = $res['t']['name'];
                $dmtData[$i]['send_mob']     = $res['t']['mobile'];
                $dmtData[$i]['bene_accntno'] = $res['t']['acc_no'];
                $dmtData[$i]['amount']       = $res['t']['amount'];
                $dmtData[$i]['pay1_status']  = $res['t']['pay1_status'];
                $dmtData[$i]['bank_status']  = $res['t']['vendor_txn_status'] ;
                $dmtData[$i]['trans_type']   = $res['t']['transaction_type'];
                $dmtData[$i]['remarks']      = $res['t']['remarks'];
                $dmtData[$i]['type']         = $res['t']['src'];
                $dmtData[$i]['date']         = $res['t']['transaction_date'];
                $dmtData[$i]['group_id']     = $res['t']['group_tag_id'];
                $dmtData[$i]['created_at']   = $res['t']['created_at'];
                $dmtData[$i]['updated_at']   = $res['t']['updates']?$res['t']['updates']:$res['t']['updated_at'];
                $dmtData[$i]['gross_amount']= $res['t']['gross_amount'];
                $dmtData[$i]['vendor']      = $res['t']['vendor_name'];
                $i++;
            }

            $this->set('result',$dmtData);
            if($this->RequestHandler->isAjax()){
            $txn_id       = $this->params['form']['id'];

            $txnDetails = $this->Ekonew->query('Select ds.user_id,tm.shop_transaction_id from transactions_master as tm '
                                                . 'JOIN dmt_users as ds ON (tm.dmtuser_id = ds.id)'
                    . 'where tm.id = '.$txn_id.'');
            $params = array();
            foreach($txnDetails as $txn){
                $params['user_id']              = $txn['ds']['user_id'];
                $params['shop_transaction_id']  = $txn['tm']['shop_transaction_id'];
                $params['service_id'] = 12;
            }
            //$main_url = Configure::read('DMT_URLS');        
            $url     =  NEWEKO_URL.'api/getRefundStatus';                       
            Configure::load('bridge');
            $configs = Configure::read('secrets');
            $secret = $configs['dmt']['secret'];
            $data = array('txn_id' => $txn_id);
            $token = $this->General->tokenGenerator($data, $secret);
            $datas = array('token' => $token,'txn_id' => $txn_id);
            $out = $this->General->dmtNotification($url, $datas);

            $out = json_decode($out['output'],TRUE);
            $val = $out['description']['data']['toBeRefunded'];
            $isRefund =   json_encode($val);

             $response = array(
                        $status = $out['status'],
                        $msg    = $out['description']['msg']
                );


            if($isRefund == 'true'){
                $userObj = ClassRegistry::init('User');
                $dataSource = $userObj->getDataSource();
                 Configure::load('bridge');
                 $server='dmt';
                 //checking the availability of txn
                  $refDet = $this->Bridge->checkWalletTxn($txn_id,$server,$dataSource);
                  if($refDet['data'] != ''){ //arrraykeyexist and !empty
                      $refundData = $this->Bridge->reverseWalletEntries($params,$refDet['data'],$dataSource,1); //passing 1 in an flag for reversing txn olfder than 3 month
                      if($refundData['status'] == 'success'){
                          //if txn got reversed at shop's than the update to be given to dmt end too.
                          $uprurl     =  NEWEKO_URL.'api/updateRefundStatus';                                                      
                        $data = array('txn_id' => $txn_id, 'shop_transaction_id' => $refundData['shop_transaction_id']);
                        $token = $this->General->tokenGenerator($data, $secret);
                        $datas = array('token' => $token,'txn_id' => $txn_id, 'shop_transaction_id' => $refundData['shop_transaction_id']);
                        $out = $this->General->dmtNotification($uprurl, $datas);
                        $out = json_decode($out['output'], TRUE);

                        $response = array(
                            'status' => $out['status'],
                            'description' => $out['description']['msg']
                        );

                    }else{
                        $response = array(
                            'status' => $refundData['status'],
                            'description' => $refundData['description'],
                        );
                    }

                  }else{
                    $response = array(

                        'status' => $refDet['status'],
                        'description' => $refDet['description']
                    );
                }
            } else{
                    $response = array(
                        'status' => $status,
                        'description' => $msg
                    );

            }
            echo  json_encode($response);
            exit;
            }

        }

        function  dmtCommentSystem(){
            $this->layout = 'plain';

            $CommentDet = $this->Slaves->query('Select * from  comments_new where module_id = 62');
            //fetching tags
            $complain_tag = $this->Slaves->query('Select * from taggings_new where module_id = 62');
            $tagname = array();
            foreach ($complain_tag as $tags) {
                $tagname[$tags['taggings_new']['id']] = $tags['taggings_new']['name'];
            }
            $username = $this->Slaves->query("Select id,name from users");
            $users = array();
            foreach ($username as $usr) {
                $users[$usr['users']['id']] = $usr['users']['name'];
            }

            $this->set('comm',$CommentDet);
            $this->set('tagname',$tagname);
            $this->set('users',$users);
        }

}
