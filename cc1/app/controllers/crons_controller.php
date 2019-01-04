<?php

class CronsController extends AppController {

    var $name = 'Crons';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'GCM');
    var $components = array('RequestHandler', 'Shop','General','Recharge','Api','Scheme','Smartpaycomp','Bridge','Serviceintegration');
    var $uses = array('Retailer','Slaves','User');

    function beforeFilter() {
        set_time_limit(0);
        ini_set("memory_limit", "1024M");
        parent::beforeFilter();
        $whitelist_ips = explode(",",CRON_WHITELIST_IP);

        $client_ip = $this->General->getClientIP();

        if(!in_array($client_ip,$whitelist_ips)){
            return;
        }

        $this->Auth->allow('*');
    }

    function vendorMonitoring(){
        $data = $this->Slaves->query("SELECT shortForm,balance,id,company FROM `vendors` WHERE update_flag = 0 AND balance > 0 AND show_flag = 1");

        foreach($data as $dt){
            $min = $dt['vendors']['balance'];
            $balance = $this->Recharge->apiBalance($dt['vendors']['id'],$dt['vendors']['shortForm']);
            $bal = $balance['balance'];
            if($balance['balance'] < $min){
                $this->General->sendMails($dt['vendors']['shortForm']." Balance below " . $min, "Current balance: Rs." . $bal, array('backend@pay1.in', 'limits@pay1.in'), 'mail');
                $this->Recharge->sendApibalanceLowAlert(array('apiname'=>$dt['vendors']['shortForm'], 'min'=>$min, 'currentbalance'=>$bal));
            }
        }

        $this->autoRender = false;
    }


    function issueDetection() {//every 15 mins
        if (date('H') < 8)
            exit;

        $data = $this->Slaves->query("
                   SELECT
                        count(vendors_activations.id) as ids,
                        vendors_activations.vendor_id,
                        vendors_activations.product_id,
                        products.name,
                        vendors.shortForm,
                        sum(if( vendors_activations.status != 3,amount,0)) as sale,
                        sum(if(vendors_activations.status =0,1,0)) as process,
                        sum(if(vendors_activations.status =2 OR vendors_activations.status =3,1,0)) as failure,
                        vendors.update_flag
                   FROM
                        `vendors_activations`,
                        products,
                        vendors
                   WHERE
                        products.id = vendors_activations.product_id
                   AND
                        vendors.id = vendors_activations.vendor_id
                   AND
                        vendors_activations.date = '" . date('Y-m-d') . "'
                   AND
                        vendors_activations.timestamp <= '" . date('Y-m-d H:i:s', strtotime('-3 minutes')) . "'
                   AND
                        vendors_activations.timestamp >= '" . date('Y-m-d H:i:s', strtotime('-30 minutes')) . "'
                   GROUP BY
                        vendors_activations.product_id,vendors_activations.vendor_id
                   ORDER BY
                        vendors_activations.product_id"
        );
        $dataVC = $this->Slaves->query("
                   SELECT
                        vendors_activations.vendor_id,
                        vendors.shortForm,
                        sum(if( vendors_activations.status != 3,amount,0)) as sale
                   FROM
                        `vendors_activations`,
                        vendors
                   WHERE
                        vendors.id = vendors_activations.vendor_id
                   AND
                        vendors_activations.date = '" . date('Y-m-d') . "'

                   GROUP BY
                        vendors_activations.vendor_id
                   ORDER BY
                        vendors.id"
        );

        $vendorConsumption = array();
        foreach ($dataVC as $x) {
            $vendorConsumption[$x['vendors_activations']['vendor_id']] = $x[0]['sale'];
        }


        $prods = array();
        $vendorCurConsmption = array();
        foreach ($data as $dt) {
            if (empty($prods[$dt['vendors_activations']['product_id']])) {
                $prods[$dt['vendors_activations']['product_id']]['total'] = 0;
                $prods[$dt['vendors_activations']['product_id']]['process'] = 0;
                $prods[$dt['vendors_activations']['product_id']]['failure'] = 0;
                //$prods[$dt['vendors_activations']['product_id']]['active'] = 0;
                $prods[$dt['vendors_activations']['product_id']]['success'] = 0;
                $prods[$dt['vendors_activations']['product_id']]['sale'] = 0;
            }

            $prods[$dt['vendors_activations']['product_id']]['total'] += $dt['0']['ids'];
            $prods[$dt['vendors_activations']['product_id']]['process'] += $dt['0']['process'];
            $prods[$dt['vendors_activations']['product_id']]['failure'] += $dt['0']['failure'];
            $prods[$dt['vendors_activations']['product_id']]['success'] += $dt['0']['ids'] - $dt['0']['failure'];
            $prods[$dt['vendors_activations']['product_id']]['sale'] += $dt['0']['sale'];

            $prods[$dt['vendors_activations']['product_id']]['name'] = $dt['products']['name']; //$dt['vendors_activations']['name']; //@TODO1


            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['total'] = $dt['0']['ids'];
            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['process'] = $dt['0']['process'];
            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['failure'] = $dt['0']['failure'];

            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['sale'] = $dt['0']['sale'];
            if (!isset($vendorCurConsmption[$dt['vendors_activations']['vendor_id']])) {
                $vendorCurConsmption[$dt['vendors_activations']['vendor_id']] = $dt['0']['sale'];
            } else {
                $vendorCurConsmption[$dt['vendors_activations']['vendor_id']] += $dt['0']['sale'];
            }

            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['success'] = $dt['0']['ids'] - $dt['0']['failure'];
            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['vendor'] = $dt['vendors']['shortForm']; //$dt['vendors_activations']['shortForm'];//@TODO1
            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['vendor_id'] = $dt['vendors_activations']['vendor_id']; //$dt['vendors_activations']['vendor_id'];//@TODO1

            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['modem_flag'] = $dt['vendors']['update_flag']; //$dt['vendors_activations']['update_flag'];//@TODO1
        }

        $allDet = $prods;
        $array = array();
        $data = $this->Slaves->query("
                    SELECT
                        products.id,
                        products.name,
                        vendors_commissions.vendor_id,
                        vendors.update_flag
                    FROM
                        products,
                        vendors_commissions,
                        vendors
                    WHERE
                        product_id = products.id
                    AND
                        vendors_commissions.vendor_id = vendors.id
                    AND vendors_commissions.active = 1
                    AND monitor = 1"
        );

        $primary = array();
        foreach ($data as $prod) {
            $primary[$prod['products']['id']] = $prod['vendors_commissions']['vendor_id'];
        }


        foreach ($data as $prod) {
            if (!isset($prods[$prod['products']['id']])) {
                $array['no'][] = $prod['products']['name'];
            } else if (($prods[$prod['products']['id']]['total'] >= 5 && $prods[$prod['products']['id']]['failure'] * 100 / $prods[$prod['products']['id']]['total'] >= 5) || $prods[$prod['products']['id']]['failure'] * 100 / $prods[$prod['products']['id']]['total'] >= 50) {
                $array['failure'][$prod['products']['id']] = $prods[$prod['products']['id']];
            } else if ($prods[$prod['products']['id']]['total'] >= 20 && $prods[$prod['products']['id']]['process'] * 100 / $prods[$prod['products']['id']]['total'] >= 25) {
                $array['process'][$prod['products']['id']] = $prods[$prod['products']['id']];
            }

            $active_vendor = $prod['vendors_commissions']['vendor_id'];
            $prods[$prod['products']['id']]['active_total'] = isset($prods[$prod['products']['id']]['vendors'][$active_vendor]['total']) ? $prods[$prod['products']['id']]['vendors'][$active_vendor]['total'] : 0;

            //if($prods[$prod['products']['id']]['total'] >= 5 && $prods[$prod['products']['id']]['active_total']*100/$prods[$prod['products']['id']]['total'] <= 90){
            $array['secondary'][$prod['products']['id']] = $prods[$prod['products']['id']];
            //}
        }

        $mail_body = "<h3>Pay1 Last 30 minutes transactions status:</h3>";
        if (!empty($array['no'])) {
            $mail_body .= "<b>No transactions happening in:</b><br/>";
            foreach ($array['no'] as $val) {
                $mail_body .= $val . ", ";
            }
        }

        if (!empty($array['failure'])) {
            $mail_body .= "<br/><br/><b>Lot of failures in:</b><br/>";
            foreach ($array['failure'] as $prod => $val) {
                $mail_body .= $val['name'] . "(" . intval($val['failure'] * 100 / $val['total']) . "%), ";
            }
        }

        if (!empty($array['process'])) {
            $mail_body .= "<br/><br/><b>Lot of transactions in process in:</b><br/>";
            foreach ($array['process'] as $prod => $val) {
                $mail_body .= $val['name'] . "(" . intval($val['process'] * 100 / $val['total']) . "%), ";
            }
        }


        $tempArr = array();
        if (!empty($array['secondary'])) {
            $tempStr .= "<br/><br/><b>Secondary routed transactions:</b><br/>";
            foreach ($array['secondary'] as $prod => $val) {
                if (!empty($val['name'])) {
                    $tempStr = "";
                    foreach ($val['vendors'] as $vend) {
                        if ($vend['success'] > 0 && $val['success'] > 0 && intval($vend['success'] * 100 / $val['success']) > 0) {
                            $color = $primary[$prod] == $vend['vendor_id'] ? "BLUE" : "";
                            $color = "1" != $vend['modem_flag'] ? "6e062f" : $color;
                            $tempStr .= "<span style='color:$color'>" . $vend['vendor'] . " (" . intval($vend['success'] * 100 / $val['success']) . "% " . "), " . "</span>";
                        }
                    }
                    $tempArr[$prod] = $tempStr;
                }
            }
        }

        $finalArr = array();
        if (!empty($prods)) {

            foreach ($prods as $pid => $val) {
                if (!empty($val['name'])) {

                    foreach ($val['vendors'] as $vid => $vend) {

                        $per = empty($val['success']) ? 0 : intval($vend['success'] * 100 / $val['success']);
                        if ($vend['modem_flag'] != 1) {// if vendor
                            $finalArr[$vend['vendor']]['today'] = $vendorConsumption[$vend['vendor_id']];
                            $finalArr[$vend['vendor']]['current'] = $vendorCurConsmption[$vend['vendor_id']];
                            $finalArr[$vend['vendor']]['vid'] = $vid;
                            if (($per > 5 && $val['sale'] > 2000) || ($primary[$pid] == $vend['vendor_id'])) { // if($per > 5 && $val['sale'] > 2000){
                                /* $finalArr[$vend['vendor']]['today'] = $vendorConsumption[$vend['vendor_id']];
                                  $finalArr[$vend['vendor']]['current'] =  $vendorCurConsmption[$vend['vendor_id']]; */

                                $finalArr[$vend['vendor']]['arr'][$val['name']]['per'] = $per; //$vend['success'];
                                $finalArr[$vend['vendor']]['arr'][$val['name']]['amt'] = $vend['sale'];
                                $finalArr[$vend['vendor']]['arr'][$val['name']]['sale'] = $val['sale'];

                                $finalArr[$vend['vendor']]['arr'][$val['name']]['vid'] = $vid;
                                $finalArr[$vend['vendor']]['arr'][$val['name']]['pid'] = $pid;

                                if ((!empty($primary[$vend['vendor_id']])) && ($primary[$pid] != $vend['vendor_id'])) {
                                    $finalArr[$vend['vendor']]['arr'][$val['name']]['color'] = "RED";
                                } else {
                                    $finalArr[$vend['vendor']]['arr'][$val['name']]['color'] = "WHITE";
                                }
                            }
                        } else if ($primary[$pid] == $vend['vendor_id'] || $per > 0) {
                            // $finalArr["modem"][$val['name']] = $tempArr[$pid];
                            //$finalArr["modem"][$val['name']] = $per < 20 ? "<span style='color:RED'>".$tempArr[$pid]."</span>" : $tempArr[$pid];

                            $modemBal[$vend['vendor']]['today'] = $vendorConsumption[$vend['vendor_id']];
                            $modemBal[$vend['vendor']]['current'] = $vendorCurConsmption[$vend['vendor_id']];
                            $modemBal[$vend['vendor']]['name'] = $vend['vendor'];

                            $finalArr["modem"][$val['name']]['det'] = $tempArr[$pid];
                            $finalArr["modem"][$val['name']]['color'] = ($primary[$pid] == $vend['vendor_id'] && $per < 20) ? "RED" : "";
                        }
                    }
                }
            }
        }

        $vendorBal = array();
        $vendors = $this->Slaves->query("SELECT * FROM `vendors` where update_flag = 0 AND active_flag != 2 AND show_flag = 1");
        foreach ($vendors as $vend) {
            $name = $vend['vendors']['shortForm'];
            $bal = $this->Recharge->apiBalance($vend['vendors']['id'],$name);
            if ($bal !== false) {
                $vendorBal[$name] = $bal;
            }
        }

        $mail_body .= "<br/><br/><b>Secondary routed transactions:</b><br/>";
        foreach ($finalArr as $v => $arr) {
            if ($v != "modem") {
                $mail_body .= "<br/>";
                $mail_body .= "<table width='100%' border='1' style='border-collapse:collapse;font-size: 14px;'><tr ><td colspan='3'><b>" . $v . ":</b> Sale in Last 30 Mins(<b>" . $arr['current'] . "</b>) Today's(<b>" . $arr['today'] . "</b>) Available Balance(<b>" . (empty($vendorBal[$v]) ? 0 : floatval($vendorBal[$v]['balance'])) . "</b>)</td></tr>";
                foreach ($arr['arr'] as $opr => $val) {
                    if ($opr != 'today' && $opr != 'current')
                        $mail_body .= "<tr " . ($val['color'] == "RED" ? "style='color:RED'" : "") . "><td width='50%'>" . $opr . " </td><td width='25%'>" . $val['per'] . "%</td><td width='25%'>" . $val['amt'] . " </td></tr>";
                }

                foreach ($primary as $opr => $val) {
                    if ($val == $arr['vid'] && !isset($allDet[$opr]['vendors'][$arr['vid']])) {
                        $oprDet = $this->Shop->getProdInfo($opr);
                        $mail_body .= "<tr style='color:orange'><td width='50%'>" . $oprDet['name'] . " </td><td width='25%'>0%</td><td width='25%'>0</td></tr>";
                    }
                }

                $mail_body .= "</table >";
            }
        }
        $mail_body .= "<br/>";
        $mail_body .= "<table width='100%' border='1' style='border-collapse:collapse;font-size: 14px;'><tr ><td colspan='2'><b>Modem</b></td></tr>";
        foreach ($finalArr["modem"] as $opr => $val) {
            $mail_body .= "<tr " . ($val['color'] == "RED" ? "style='color:RED'" : "") . "><td width='30%'>" . $opr . " </td><td width='70%'>";
            $mail_body .=$val['det'];
            $mail_body .= "</td></tr>";
        }
        $mail_body .= "</table >";

        $mail_body .= "<br/>";
        $mail_body .= "<table width='100%' border='1' style='border-collapse:collapse;font-size: 14px;'><tr ><td><b>Modem</b></td><td><b>Sale in Last 30 Mins</b></td><td><b>Today's Sale</b></td></tr>";
        foreach ($modemBal as $vid => $arr) {
            $mail_body .= "<tr " . ($val['color'] == "RED" ? "style='color:RED'" : "") . ">
                                    <td width='40%'>" . $arr['name'] . "</td>
                                    <td width='30%'>" . $arr['current'] . "</td>
                                    <td width='30%'>" . $arr['today'] . "</td>";
            $mail_body .= "</tr>";
        }

        $mail_body .= "</table >";

        //$this->printArray($array);
        $mail_subject = "(IMP) Pay1 Transactions Status of last 30 minutes";
        $this->General->sendMails($mail_subject, $mail_body, array('backend@pay1.in',
            'ekta.singh@pay1.in','hitesh.savaliya@pay1.in','khalid.shaikh@pay1.in','dharmesh.chauhan@pay1.in','prerna.pawar@pay1.in'), 'mail');
        //echo $mail_body;
        $this->autoRender = false;
    }

    function checkInternet(){
        $this->autoRender = false;
        $data = $this->Slaves->query("SELECT id FROM vendors WHERE update_flag = 1 AND active_flag = 1");
        foreach($data as $dt){
            $vendor = $dt['vendors']['id'];
            $ip = $this->Shop->getMemcache("vendorip_$vendor");

            if($ip === false){
                $open_redis = $this->Shop->openservice_redis();
                $this->General->logData('ip_testing.txt',$vendor . "::". $ip);
                if(!$open_redis->exists("activestatus_".$vendor)){
                    $this->General->logData('ip_testing.txt',"activestatus is also empty here::".$vendor . "::". $ip);
                    $this->Shop->unHealthyVendor($vendor,20);
                }
            }
        }
    }

    function initializeOpeningBalance(){
        $this->autoRender = false;
        $date = date('Y-m-d',strtotime('-1 days'));
        $this->Retailer->query("UPDATE users_logs,users SET users_logs.opening=users.opening_balance,users_logs.closing=users.balance WHERE users.balance > 0 AND users_logs.user_id = users.id AND users_logs.date = '$date'");
        $this->Retailer->query("INSERT IGNORE INTO users_logs (user_id, opening, closing, date) (SELECT users.id, users.opening_balance, users.balance,'$date' FROM users WHERE balance > 0)");
        
        $this->Retailer->query("UPDATE users SET opening_balance = balance");
        //$this->Retailer->query("INSERT INTO retailers_closing (retailer_id, closing, date) (SELECT retailers.id, users.balance, CURDATE() FROM retailers JOIN users ON (retailers.user_id = users.id))");
    }

    /*function terToPri() {
        $this->autoRender = false;

        $dist_details = $this->Retailer->query("SELECT users.id,distributors.id,users.balance FROM distributors JOIN users ON (distributors.user_id = users.id) WHERE distributors.margin = '0.00'");

        foreach($dist_details as $dt) {
            $user_id        = $dt['users']['id'];
            $distributor_id = $dt['distributors']['id'];
            $balance        = $dt['users']['balance'];
            $commission     = intval($balance * 0.005);

            if($commission > 0) {
                $trans_id = $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR, $commission, $distributor_id, 0, null, null, null, 'Commision towards primary balance',$balance,$balance+$commission);
                $this->Shop->shopBalanceUpdate($commission, 'add', $user_id, DISTRIBUTOR);
            }

            $this->Retailer->query("UPDATE distributors SET margin = '0.5' WHERE id = '".$distributor_id."'");
        }
    }*/

    function cronRepeatedTrans(){
        $data = $this->Retailer->query("SELECT * FROM repeated_transactions WHERE send_flag = 1 LIMIT 10");

        foreach($data as $req){
            $this->Retailer->query("UPDATE repeated_transactions SET send_flag = 2 WHERE id = " . $req['repeated_transactions']['id']);
            $this->Api->receiveSMS($req['repeated_transactions']['sender'],$req['repeated_transactions']['msg'],1);
        }
        $this->autoRender = false;
    }


    function updateRetailerLogs($date_in=null){

        //set up entries changes
        $last_date = (empty($date_in)) ? date('Y-m-d',strtotime('-1 days')) : $date_in;
        //$last_date_7 = (empty($date_in)) ? date('Y-m-d',strtotime('-7 days')) : $date_in;
        $data1 = $this->Slaves->query("SELECT sum(amount) as amts,target_id,date FROM shop_transactions WHERE confirm_flag != 1 AND type IN ('".DIST_RETL_BALANCE_TRANSFER."','".SLMN_RETL_BALANCE_TRANSFER."') AND date = '$last_date' AND type_flag != 5 group by date,target_id");
        $retData =  array();
        $getAllRetailers = $this->Slaves->query("SELECT retailers.id,retailers.parent_id as distributor_id from retailers WHERE toshow = 1");
        foreach ($getAllRetailers as $ret){
            $retData[$ret['retailers']['id']]['distributor_id'] = $ret['retailers']['distributor_id'];
        }

        foreach($data1 as $dt){
            $datas[$dt['shop_transactions']['date']][$dt['shop_transactions']['target_id']]['topup'] = $dt['0']['amts'];
        }

        $pg_topup = $this->Slaves->query('SELECT target_id,sum(amount) as pg_topup,date '
                . 'FROM shop_transactions '
                . 'WHERE date = "'.$last_date.'" '
                . 'AND type IN ("'.DIST_RETL_BALANCE_TRANSFER.'","'.SLMN_RETL_BALANCE_TRANSFER.'")'
                . 'AND type_flag = 5 '
                . 'AND confirm_flag != 1 '
                . 'GROUP BY target_id');

        foreach($pg_topup as $data){
            $datas[$data['shop_transactions']['date']][$data['shop_transactions']['target_id']]['pg_topup'] = $data[0]['pg_topup'];
        }

        //topup reversed
        $topup_reversed = $this->Slaves->query('SELECT SUM(st1.amount) AS topup_reversed,st1.date AS pullback_date,st2.date AS txn_date,st1.source_id '
                . 'FROM shop_transactions st1 '
                . 'JOIN shop_transactions st2 ON (st1.target_id = st2.id) '
                . 'WHERE st1.type = '.PULLBACK_RETAILER.' '
                . 'AND st1.date = "'.$last_date.'" '
                . 'AND st1.date != st2.date '
                . 'GROUP BY st1.source_id');
        foreach($topup_reversed as $data){
            $datas[$data['st1']['source_id']]['topup_reversed'] = $data[0]['topup_reversed'];
        }

        foreach($datas as $date=>$dt){
            foreach($dt as $ret=>$val){
                $val['topup'] = isset($val['topup']) ? $val['topup'] : 0;
                $val['topup_reversed'] = isset($val['topup_reversed']) ? $val['topup_reversed'] : 0;
                $val['pg_topup'] = isset($val['pg_topup']) ? $val['pg_topup'] : 0;

                $this->Retailer->query("INSERT INTO retailers_logs (retailer_id,distributor_id,topup,date,topup_reversed,pg_topup) VALUES ($ret,".$retData[$ret]['distributor_id'].",".$val['topup'].",'$date','".$val['topup_reversed']."','".$val['pg_topup']."')");
            }
        }
        $this->autoRender = false;
    }

    /*function updateRetailerLogsNew($date_in=null){
        ini_set("memory_limit", "1024M");
        //set up entries changes
        $last_date = (empty($date_in)) ? date('Y-m-d',strtotime('-1 days')) : $date_in;
        $days_90_older_date = date('Y-m-d', strtotime('-90 days'));

        $data = $this->Slaves->query("SELECT sum(amount) as amts,retailer_id,date,count(id) as cts,sum(retailer_margin) as earning,sum(if(api_flag=1,amount,0)) as app_sale,sum(if(api_flag=3,amount,0)) as android_sale ,sum(if(api_flag=7,amount,0)) as windows7_sale,sum(if(api_flag=8,amount,0)) as windows8_sale,sum(if(api_flag=5,amount,0)) as java_sale,sum(if(api_flag=9,amount,0)) as web_sale,sum(if(api_flag=0,amount,0)) as sms_sale,sum(if(api_flag=2,amount,0)) as ussd_sale FROM vendors_activations WHERE status not in (2,3) AND date = '$last_date' group by retailer_id");
        //$data = $this->Slaves->query("SELECT sum(amount) as amts,source_id,date,count(id) as cts,sum(amount*discount_comission/100) as earning FROM shop_transactions WHERE type = ".COMMISSION_RETAILER." AND confirm_flag = 1 AND date >= '$last_date_7' AND date <= '$last_date' group by date,source_id");
        $data1 = $this->Slaves->query("SELECT sum(amount) as amts,target_id,date FROM shop_transactions WHERE confirm_flag != 1 AND type IN ('".DIST_RETL_BALANCE_TRANSFER."','".SLMN_RETL_BALANCE_TRANSFER."') AND date = '$last_date' group by date,target_id");
        //$data2 = $this->Slaves->query("SELECT sum(if(api_flag=1,amount,0)) as app_sale,sum(if(api_flag=3,amount,0)) as android_sale ,sum(if(api_flag=7,amount,0)) as windows7_sale,sum(if(api_flag=8,amount,0)) as windows8_sale,sum(if(api_flag=5,amount,0)) as java_sale,sum(if(api_flag=9,amount,0)) as web_sale,sum(if(api_flag=0,amount,0)) as sms_sale,sum(if(api_flag=2,amount,0)) as ussd_sale,retailer_id,date FROM `vendors_activations` FORCE INDEX (idx_date) WHERE status !=2 AND status !=3 AND date >= '$last_date_7' AND date <= '$last_date' group by date,retailer_id");

        $retData =  array();
        $getAllRetailers = $this->Slaves->query("SELECT retailers.id,retailers.parent_id as distributor_id,users.opening_balance from retailers inner join users ON (users.id  = retailers.user_id) WHERE toshow = 1");
        foreach ($getAllRetailers as $ret){
            $retData[$ret['retailers']['id']]['closing_balance'] = $ret['users']['opening_balance'];
            $retData[$ret['retailers']['id']]['distributor_id'] = $ret['retailers']['distributor_id'];
        }

        $datas = array();
        foreach($data as $dt){
            $datas[$dt['vendors_activations']['retailer_id']]['sale'] = $dt['0']['amts'];
            $datas[$dt['vendors_activations']['retailer_id']]['transactions'] = $dt['0']['cts'];
            $datas[$dt['vendors_activations']['retailer_id']]['topup'] = 0;
            $datas[$dt['vendors_activations']['retailer_id']]['earning'] = $dt['0']['earning'];
            $datas[$dt['vendors_activations']['retailer_id']]['app_sale'] = $dt['0']['app_sale'];
            $datas[$dt['vendors_activations']['retailer_id']]['android_sale'] = $dt['0']['android_sale'];
            $datas[$dt['vendors_activations']['retailer_id']]['java_sale'] = $dt['0']['java_sale'];
            $datas[$dt['vendors_activations']['retailer_id']]['windows7_sale'] = $dt['0']['windows7_sale'];
            $datas[$dt['vendors_activations']['retailer_id']]['windows8_sale'] = $dt['0']['windows8_sale'];
            $datas[$dt['vendors_activations']['retailer_id']]['web_sale'] = $dt['0']['web_sale'];
            $datas[$dt['vendors_activations']['retailer_id']]['sms_sale'] = $dt['0']['sms_sale'];
            $datas[$dt['vendors_activations']['retailer_id']]['ussd_sale'] = $dt['0']['ussd_sale'];
            $datas[$dt['vendors_activations']['retailer_id']]['closing_balance'] = isset($retData[$dt['vendors_activations']['retailer_id']]['closing_balance']) ? $retData[$dt['vendors_activations']['retailer_id']]['closing_balance'] : 0;

        }

        foreach($data1 as $dt){
            if(!isset($datas[$dt['shop_transactions']['target_id']])){
                $datas[$dt['shop_transactions']['target_id']]['sale'] = 0;
                $datas[$dt['shop_transactions']['target_id']]['transactions'] = 0;
                $datas[$dt['shop_transactions']['target_id']]['earning'] = 0;
                $datas[$dt['shop_transactions']['source_id']]['app_sale'] = 0;
                $datas[$dt['shop_transactions']['source_id']]['android_sale'] = 0;
                $datas[$dt['shop_transactions']['source_id']]['java_sale'] = 0;
                $datas[$dt['shop_transactions']['source_id']]['windows7_sale'] = 0;
                $datas[$dt['shop_transactions']['source_id']]['windows8_sale'] = 0;
                $datas[$dt['shop_transactions']['source_id']]['web_sale'] = 0;
            }
            $datas[$dt['shop_transactions']['target_id']]['topup'] = $dt['0']['amts'];
        }

        $getOpeningBalance = $this->Slaves->query('SELECT * FROM '
                                    . '(SELECT retailer_id,date,closing_balance '
                                    . 'FROM retailers_logs '
                                    . 'WHERE date >= "'.$days_90_older_date.'" '
                                    . 'ORDER BY date desc) AS rl '
                                    . 'GROUP BY retailer_id ');

        foreach($getOpeningBalance as $dt){
            $datas[$dt['rl']['retailer_id']]['opening_balance'] = $dt['rl']['closing_balance'];
        }

        $pg_topup = $this->Slaves->query('SELECT target_id,SUM(amount) AS pg_topup,date '
                . 'FROM shop_transactions '
                . 'WHERE date = "'.$last_date.'" '
                . 'AND type = '.DIST_RETL_BALANCE_TRANSFER.' '
                . 'AND type_flag = 5 '
                . 'AND confirm_flag = 0 '
                . 'GROUP BY target_id');

        foreach($pg_topup as $data){
            $datas[$data['shop_transactions']['target_id']]['pg_topup'] = $data[0]['pg_topup'];
        }

        //topup reversed
        $topup_reversed = $this->Slaves->query('SELECT SUM(st1.amount) AS topup_reversed,st1.date AS pullback_date,st2.date AS txn_date,st1.source_id '
                                            . 'FROM shop_transactions st1 '
                                            . 'JOIN shop_transactions st2 ON (st1.target_id = st2.id) '
                                            . 'WHERE st1.type = '.PULLBACK_RETAILER.' '
                                            . 'AND st1.date = "'.$last_date.'" '
                                            . 'AND st2.date < "'.$last_date.'" '
                                            . 'AND st1.date != st2.date '
                                            . 'GROUP BY st1.source_id');

        foreach($topup_reversed as $data){
            $datas[$data['st1']['source_id']]['topup_reversed'] = $data[0]['topup_reversed'];
        }

        foreach($datas as $ret=>$val){
            $val['sale'] = isset($val['sale']) ? $val['sale'] : 0;
            $val['app_sale'] = isset($val['app_sale']) ? $val['app_sale'] : 0;
            $val['sms_sale'] = isset($val['sms_sale']) ? $val['sms_sale'] : 0;
            $val['ussd_sale'] = isset($val['ussd_sale']) ? $val['ussd_sale'] : 0;
            $val['android_sale'] = isset($val['android_sale']) ? $val['android_sale'] : 0;
            $val['java_sale'] = isset($val['java_sale']) ? $val['java_sale'] : 0;
            $val['windows7_sale'] = isset($val['windows7_sale']) ? $val['windows7_sale'] : 0;
            $val['windows8_sale'] = isset($val['windows7_sale']) ? $val['windows7_sale'] : 0;
            $val['web_sale'] = isset($val['web_sale']) ? $val['web_sale'] : 0;
            $val['transactions'] = isset($val['transactions']) ? $val['transactions'] : 0;
            $val['topup'] = isset($val['topup']) ? $val['topup'] : 0;
            $val['earning'] = isset($val['earning']) ? $val['earning'] : 0;
            $val['opening_balance'] = isset($val['opening_balance']) ? $val['opening_balance'] : 0;
            $val['closing_balance'] = isset($val['closing_balance']) ? $val['closing_balance'] : 0;
            $val['topup_reversed'] = isset($val['topup_reversed']) ? $val['topup_reversed'] : 0;
            $val['pg_topup'] = isset($val['pg_topup']) ? $val['pg_topup'] : 0;

            $this->Retailer->query("INSERT INTO retailers_logs (retailer_id,distributor_id,sale,app_sale,sms_sale,ussd_sale,android_sale,java_sale,windows7_sale,windows8_sale,web_sale,transactions,topup,earning,opening_balance,closing_balance,date,topup_reversed,pg_topup) VALUES ($ret,".$retData[$ret]['distributor_id'].",".$val['sale'].",".$val['app_sale'].",".$val['sms_sale'].",".$val['ussd_sale'].",".$val['android_sale'].",".$val['java_sale'].",".$val['windows7_sale'].",".$val['windows8_sale'].",".$val['web_sale'].",".$val['transactions'].",".$val['topup'].",".$val['earning'].",'".$val['opening_balance']."','".$val['closing_balance']."','$last_date','".$val['topup_reversed']."','".$val['pg_topup']."')");
        }
        $this->autoRender = false;
    }*/

    /*function updateDistributorLogs($last_date=null){

        //set up entries changes
        $last_date = (empty($last_date)) ? date('Y-m-d',strtotime('-1 days')) : $last_date;
        $datas = array();
        //topup buy from SD
        $data = $this->Slaves->query("SELECT sum(amount) as amts,count(id) as primary_txn,date,target_id FROM shop_transactions WHERE type = ".MDIST_DIST_BALANCE_TRANSFER." AND confirm_flag != 1 AND date = '$last_date' group by target_id");
        foreach($data as $dt){
            $datas[$dt['shop_transactions']['target_id']]['buy'] = $dt['0']['amts'];
            $datas[$dt['shop_transactions']['target_id']]['primary_txn'] = $dt['0']['primary_txn'];
        }
        //commission SD
        $data = $this->Slaves->query("SELECT sum(amount) as amts,date,source_id FROM shop_transactions WHERE type = ".COMMISSION_DISTRIBUTOR." AND user_id <= 7 AND confirm_flag != 1 AND date = '$last_date' group by source_id");
        foreach($data as $dt){
            $datas[$dt['shop_transactions']['source_id']]['commission'] = $dt['0']['amts'];
        }

        //commission debited
        $data = $this->Slaves->query("SELECT sum(amount) as amts,date,source_id FROM shop_transactions WHERE type = ".COMMISSION_DISTRIBUTOR_REVERSE." AND user_id <= 7 AND date = '$last_date' group by source_id");
        foreach($data as $dt){
            if(!isset($datas[$dt['shop_transactions']['source_id']]['commission']))$datas[$dt['shop_transactions']['source_id']]['commission'] = 0;
            $datas[$dt['shop_transactions']['source_id']]['commission'] -= $dt['0']['amts'];
        }
        //topup sold to retailers
        $data = $this->Slaves->query("(SELECT sum(amount) as amts,date,source_id,count(distinct target_id) as cts FROM shop_transactions WHERE type = '".DIST_RETL_BALANCE_TRANSFER."' AND confirm_flag != 1 AND date = '$last_date' group by source_id) UNION (SELECT sum(st.amount) amts,st.date,salesmen.dist_id source_id,count(distinct st.target_id) as cts FROM shop_transactions st JOIN salesmen ON (st.source_id = salesmen.id) WHERE st.type = '".SLMN_RETL_BALANCE_TRANSFER."' AND st.confirm_flag != 1 AND st.date = '$last_date' GROUP BY salesmen.dist_id)");
        foreach($data as $dt){
            if(isset($datas[$dt[0]['source_id']]['sold'])) {
                $datas[$dt[0]['source_id']]['sold'] += $dt[0]['amts'];
                $datas[$dt[0]['source_id']]['unique'] += $dt[0]['cts'];
            } else {
                $datas[$dt[0]['source_id']]['sold'] = $dt[0]['amts'];
                $datas[$dt[0]['source_id']]['unique'] = $dt[0]['cts'];
            }
        }
        //total retailers
        $data = $this->Slaves->query("SELECT count(retailers.id) as cts,retailers.parent_id FROM retailers inner join distributors on (retailers.parent_id = distributors.id) group by retailers.parent_id");
        foreach($data as $dt){
            $datas[$dt['retailers']['parent_id']]['retailers'] = $dt['0']['cts'];
        }
        //total transacting retailers
        $data = $this->User->query("SELECT count(distinct retailer_earning_logs.id) as cts,distributors.id FROM retailer_earning_logs left join distributors ON (retailer_earning_logs.dist_user_id = distributors.user_id) WHERE retailer_earning_logs.date = '$last_date' group by retailer_earning_logs.dist_user_id");
        foreach($data as $dt){
            $datas[$dt['distributors']['id']]['transacting'] = $dt['0']['cts'];
        }

        //pullback distributor
        $pullback = $this->Slaves->query('SELECT SUM(st1.amount) AS pullback_amt,st1.date AS pullback_date,st2.date AS txn_date,st1.source_id '
                                            . 'FROM shop_transactions st1 '
                                            . 'JOIN shop_transactions st2 ON (st1.target_id = st2.id) '
                                            . 'WHERE st1.type = '.PULLBACK_DISTRIBUTOR.' '
                                            . 'AND st1.date = "'.$last_date.'" '
                                            . 'AND st2.date < "'.$last_date.'" '
                                            . 'AND st1.date != st2.date '
                                            . 'GROUP BY st1.source_id');
        foreach($pullback as $data){
            $datas[$data['st1']['source_id']]['pullback_amt'] = $data[0]['pullback_amt'];
        }

        foreach($datas as $dist=>$val){
            if(!isset($val['buy']))$val['buy'] = 0;
            if(!isset($val['sold']))$val['sold'] = 0;
            if(!isset($val['unique']))$val['unique'] = 0;
            if(!isset($val['retailers']))$val['retailers'] = 0;
            if(!isset($val['transacting']))$val['transacting'] = 0;
            if(!isset($val['commission']))$val['commission'] = 0;
            if(!isset($val['primary_txn']))$val['primary_txn'] = 0;
            if(!isset($val['pullback_amt']))$val['pullback_amt'] = 0;
            $this->Retailer->query("INSERT INTO distributors_logs (distributor_id,retailers,transacting,topup_sold,topup_buy,topup_unique,earning,primary_txn,date,pullback_amt) VALUES ($dist,".$val['retailers'].",".$val['transacting'].",".$val['sold'].",".$val['buy'].",".$val['unique'].",".$val['commission'].",'".$val['primary_txn']."','$last_date','".$val['pullback_amt']."')");
        }

        $min_date = date('Y-m-d',strtotime('-7 days'));
         $data = $this->User->query("SELECT count(retailers_logs.id) as cts,retailers.parent_id,retailers_logs.date FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers_logs.date >= '$min_date' group by retailers_logs.date,retailers.parent_id order by parent_id");
         foreach($data as $dt){
         $this->User->query("UPDATE distributors_logs SET transacting = ".$dt['0']['cts']." WHERE distributor_id = " . $dt['retailers']['parent_id']. " AND date = '".$dt['retailers_logs']['date']."'");
         }

        $this->autoRender = false;
    }*/

    /**
     * Insets the data in the distributor_logs_quarted
     * Fetches from shop_transactions
     * Runs a single time at midnight
     * author: Rishabh Gupta
     */
    function updateDistributorsLogsQuarter($last_date=null) {

        $this->autoRender = false;

        if(empty($last_date))
            $last_date = date('Y-m-d',strtotime('-1 days'));



            $amount = array();


            $dataPrimary = $this->Slaves->query("
                    SELECT
                    target_id as distributor_id, SUM(amount) AS primary_amt, Hour(timestamp)  AS hr, date
                    FROM
                    shop_transactions
                    WHERE
                    date = '$last_date'
                    AND
                    confirm_flag = 0
                    AND
                    type = '".MDIST_DIST_BALANCE_TRANSFER."'
							GROUP BY
								target_id, hr"
                    );

            foreach ($dataPrimary as $index => $arr){

                if(!isset($amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['00to06']))
                    $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['00to06'] = 0;
                    if(!isset($amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['06to12']))
                        $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['06to12'] = 0;
                        if(!isset($amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['12to18']))
                            $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['12to18'] = 0;
                            if(!isset($amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['18to24']))
                                $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['18to24'] = 0;

                                if($arr[0]['hr'] < 6){
                                    $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['00to06'] += $arr[0]['primary_amt'];
                                }
                                elseif ($arr[0]['hr'] < 12){
                                    $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['06to12'] += $arr[0]['primary_amt'];
                                }
                                elseif ($arr[0]['hr'] < 18){
                                    $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['12to18'] += $arr[0]['primary_amt'];
                                }
                                elseif ($arr[0]['hr']){
                                    $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['primary']['18to24'] += $arr[0]['primary_amt'];
                                }
            }

            $dataSecondary = $this->Slaves->query("
                    SELECT
                    source_id AS distributor_id, SUM(amount) AS secondary_amt, Hour(timestamp) as hr, date
                    FROM
                    shop_transactions
                    WHERE
                    date = '$last_date'
                    AND
                    type = '".DIST_RETL_BALANCE_TRANSFER."'
							AND
								confirm_flag = 0
							GROUP BY
								source_id, hr"
                    );

            foreach ($dataSecondary as $index => $arr){

                if (!isset($amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['00to06']))
                    $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['00to06'] = 0;
                    if (!isset($amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['06to12'] ))
                        $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['06to12'] = 0;
                        if(!isset($amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['12to18']))
                            $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['12to18'] = 0;
                            if (!isset($amount[$arr['shop_transactions']['distributor_id']]['secondary']['18to24']))
                                $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['18to24'] = 0;

                                if($arr[0]['hr'] < 6){
                                    $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['00to06'] += $arr[0]['secondary_amt'];
                                }
                                elseif ($arr[0]['hr'] < 12){
                                    $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['06to12'] += $arr[0]['secondary_amt'];
                                }
                                elseif ($arr[0]['hr'] < 18){
                                    $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['12to18'] += $arr[0]['secondary_amt'];
                                }
                                elseif ($arr[0]['hr'] < 24){
                                    $amount[$arr['shop_transactions']['distributor_id']][$arr['shop_transactions']['date']]['secondary']['18to24'] += $arr[0]['secondary_amt'];
                                }
            }

            $dataTetiary = $this->Slaves->query("
                    SELECT
                    R.parent_id AS distributor_id, SUM(ST.amount) AS tertiary_amt, hour(ST.timestamp) as hr, ST.date as date
                    FROM
                    shop_transactions  AS ST, retailers AS R
                    WHERE
                    R.id = ST.source_id
                    AND
                    ST.date = '$last_date'
                    AND
                    ST.type = '".RETAILER_ACTIVATION."'
							AND
								confirm_flag = 1
							GROUP BY
								R.parent_id, hr"
                    );

            foreach ($dataTetiary as $index => $arr){

                if (!isset($amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['00to06']))
                    $amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['00to06'] = 0;
                    if (!isset($amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['06to12']))
                        $amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['06to12'] = 0;
                        if (!isset($amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['12to18']))
                            $amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['12to18'] = 0;
                            if (!isset($amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['18to24']))
                                $amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['18to24'] = 0;

                                if($arr[0]['hr'] < 6){
                                    $amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['00to06'] += $arr[0]['tertiary_amt'];
                                }
                                elseif ($arr[0]['hr'] < 12){
                                    $amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['06to12'] += $arr[0]['tertiary_amt'];
                                }
                                elseif ($arr[0]['hr'] < 18){
                                    $amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['12to18'] += $arr[0]['tertiary_amt'];
                                }
                                elseif ($arr[0]['hr'] < 24){
                                    $amount[$arr['R']['distributor_id']][$arr['ST']['date']]['tertiary']['18to24'] += $arr[0]['tertiary_amt'];
                                }
            }



            foreach ($amount as $index => $arr){
                foreach ($arr as $date => $priSecTert){
                    if (!isset($priSecTert['primary']['00to06']))
                        $amount[$index][$date]['primary']['00to06'] = 0;
                        if (!isset($priSecTert['primary']['06to12']))
                            $amount[$index][$date]['primary']['06to12'] = 0;
                            if (!isset($priSecTert['primary']['12to18']))
                                $amount[$index][$date]['primary']['12to18'] = 0;
                                if (!isset($priSecTert['primary']['18to24']))
                                    $amount[$index][$date]['primary']['18to24'] = 0;

                                    if (!isset($priSecTert['secondary']['00to06']))
                                        $amount[$index][$date]['secondary']['00to06'] = 0;
                                        if (!isset($priSecTert['secondary']['06to12']))
                                            $amount[$index][$date]['secondary']['06to12'] = 0;
                                            if (!isset($priSecTert['secondary']['12to18']))
                                                $amount[$index][$date]['secondary']['12to18'] = 0;
                                                if (!isset($priSecTert['secondary']['18to24']))
                                                    $amount[$index][$date]['secondary']['18to24'] = 0;

                                                    if (!isset($priSecTert['tertiary']['00to06']))
                                                        $amount[$index][$date]['tertiary']['00to06'] = 0;
                                                        if (!isset($priSecTert['tertiary']['06to12']))
                                                            $amount[$index][$date]['tertiary']['06to12'] = 0;
                                                            if (!isset($priSecTert['tertiary']['12to18']))
                                                                $amount[$index][$date]['tertiary']['12to18'] = 0;
                                                                if (!isset($priSecTert['tertiary']['18to24']))
                                                                    $amount[$index][$date]['tertiary']['18to24'] = 0;
                }
            }


            //INSERT DATA INTO THE TABLE
            $stringInsert = "INSERT INTO distributor_logs_quarter
							 (distributor_id,
							 primary_00to06, primary_06to12,primary_12to18, primary_18to24,
		 					 secondary_00to06, secondary_06to12, secondary_12to18, secondary_18to24,
							 tertiary_00to06, tertiary_06to12, tertiary_12to18, tertiary_18to24, date)
						 VALUES ";

            foreach ($amount as $id => $dateArr){

                foreach ($dateArr as $date => $priSecTert){

                    $stringInsert .= "(".$id.",";

                    $stringInsert .= $priSecTert['primary']['00to06'].",";
                    $stringInsert .= $priSecTert['primary']['06to12'].",";
                    $stringInsert .= $priSecTert['primary']['12to18'].",";
                    $stringInsert .= $priSecTert['primary']['18to24'].",";

                    $stringInsert .= $priSecTert['secondary']['00to06'].",";
                    $stringInsert .= $priSecTert['secondary']['06to12'].",";
                    $stringInsert .= $priSecTert['secondary']['12to18'].",";
                    $stringInsert .= $priSecTert['secondary']['18to24'].",";

                    $stringInsert .= $priSecTert['tertiary']['00to06'].",";
                    $stringInsert .= $priSecTert['tertiary']['06to12'].",";
                    $stringInsert .= $priSecTert['tertiary']['12to18'].",";
                    $stringInsert .= $priSecTert['tertiary']['18to24'].",";
                    $stringInsert .= "'$date'";

                    $stringInsert .= "), ";
                }
            }
            $stringInsert = trim(trim($stringInsert),",");
            //echo "<br> $stringInsert<br>";

            $this->Retailer->query($stringInsert);



    }

    /*function correctOldEntries($min_date=null){
        set_time_limit(0);
        ini_set("memory_limit","-1");

        $min_date = (empty($min_date)) ? date('Y-m-d',strtotime('-7 days')) : $min_date;
        $last_date= (empty($last_date)) ? date('Y-m-d',strtotime('-1 days')) : $min_date;
//        $data = $this->Slaves->query("SELECT count(retailers_logs.id) as cts,sum(retailers_logs.sale) as totalsale, retailers.parent_id,retailers_logs.date FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers_logs.date >= '$min_date' AND retailers_logs.date <= '$last_date' group by retailers_logs.date,retailers.parent_id order by parent_id");
        $data = $this->Slaves->query("SELECT COUNT(distinct(rel.ret_user_id)) AS cts,SUM(rel.amount) AS totalsale,rel.date,d.id as dist_id "
                . "FROM retailer_earning_logs rel "
                . "JOIN retailers r ON (r.user_id = rel.ret_user_id)"
                . "JOIN distributors d ON (rel.dist_user_id = d.user_id)  "
                . "WHERE rel.date >= '$min_date' "
                . "AND rel.date <= '$last_date' "
                . "AND rel.service_id IN (1,2,4,5,6,7) "
                . "GROUP BY rel.date,d.id "
                . "ORDER BY d.id");
        foreach($data as $dt){
            $this->Retailer->query("UPDATE users_logs SET transacting = ".$dt['0']['cts']." WHERE user_id = " . $dt['d']['user_id']. " AND date = '".$dt['rel']['date']."'");
            //$this->User->query("UPDATE distributors SET transacting_retailer = ".$dt['0']['cts']." ,WHERE distributor_id = " . $dt['retailers']['parent_id']. " AND transacting_retailer < " . $dt['0']['cts']);
            //$this->User->query("UPDATE distributors SET benchmark_value = ".$dt['0']['totalsale']." WHERE distributor_id = " . $dt['retailers']['parent_id']. " AND benchmark_value < " . $dt['0']['totalsale']);
        }

        $this->autoRender = false;
    }*/


    function allBalance($last = 0){
            $data = $this->Retailer->query("SELECT * FROM vendors WHERE show_flag = 1 AND active_flag NOT IN (0)");

            $date = date('Y-m-d');
            $modems = array();
            $map = array();
            $balances = array();
            $total = 0;
            $modem_bals = array();


            foreach($data as $dt){
                $id = $dt['vendors']['id'];
                $name = $dt['vendors']['shortForm'];

                if($dt['vendors']['update_flag'] == 1){
                    $modem_bal = $this->Recharge->modemBalance($date, $id, false, false);
                    if(empty($modem_bal)) continue;

                    $modems[$id] = $modem_bal;
                    $map[$id] = $dt['vendors']['company'];
                }
                else {
                    $balances[$name] = $this->Recharge->apiBalance($id, $name);
                    $total += $balances[$name]['balance'];
                    $modem_bals[$id] = $balances[$name]['balance'];
                }
            }

            $body = "";

            foreach($modems as $key=>$modemc){
                $body .= "<br/>" . $map[$key] . " Balances:";
                if(isset($modemc['lasttime'])) $body .= " (" . $modemc['lasttime'] . ")<br/>";
                $modem_bal = 0;
                $opening = 0;
                $closing = 0;
                $tfr = 0;
                $sale = 0;
                $diff_tot = 0;
                $inc = 0;
                $body .= "<table border=1>";
                $body .= "<tr>
							<th>Device Id/Port</th>
							<th>Signal</th>
							<th>Vendor</th>
							<th>Operator</th>
							<th>Number</th>
							<th>Margin</th>
		  					<th>Curr Bal</th>
		  					<th>Opening</th>
		  		    		<th>Closing</th>
		  		    		<th>Incoming</th>
		  		    		<th>Sale</th>
		  		    		<th>Inc</th>
		  		    		<th>Diff</th>
		  		    		<th>Action</th>
		  				</tr>";
                foreach($modemc as $md){
                    if($md['active_flag'] == 0) $color = '#99ff99';
                    else $color = '#008000';
                    $body .= "<tr id='device" . $md['id'] . "' style='bgcolor:$color'>";
                    $body .= "<td>" . $md['id'] . "/" . $md['device_num'] . "</td>";
                    $body .= "<td>" . $md['signal'] . "</td>";
                    $body .= "<td>" . $md['vendor_tag'] . "/" . $md['vendor'] . "</td>";
                    $body .= "<td>" . $md['operator'] . "</td>";
                    $body .= "<td>" . $md['mobile'] . "</td>";
                    $body .= "<td>" . $md['commission'] . "%</td>";
                    $body .= "<td>" . $md['balance'] . "</td>";
                    $body .= "<td>" . $md['opening'] . "</td>";
                    $body .= "<td>" . $md['closing'] . "</td>";
                    $body .= "<td>" . $md['tfr'] . "</td>";
                    $body .= "<td>" . $md['sale'] . "</td>";
                    $body .= "<td>" . intval($md['inc']) . "</td>";

                    $opening += $md['opening'];
                    $closing += $md['closing'];
                    $inc += intval($md['inc']);
                    $tfr += $md['tfr'];
                    $sale += $md['sale'];

                    if($date != date('Y-m-d')){
                        $diff = $md['sale'] - ($md['opening'] + $md['tfr'] - $md['closing']);
                    }
                    else{
                        $diff = $md['sale'] - ($md['opening'] + $md['tfr'] - $md['balance']);
                    }

                    $diff = $diff - $md['inc'];
                    $diff_tot += $diff;
                    $body .= "<td>$diff</td>";
                    $body .= "<td><a href='javascript:void(0)' onclick='resetDevice(" . $md['id'] . ")'>Reset</a></td>";

                    $modem_bal += $md['balance'];
                    $body .= "</tr>";
                }

                $body .= "<tr><td></td><td></td><td></td><td></td><td></td><td><b>Total<b/></td><td><b>$modem_bal<b/></td><td><b>$opening<b/></td><td><b>$closing<b/></td><td><b>$tfr<b/></td><td><b>$sale<b/></td><td><b>$inc<b/></td><td><b>$diff_tot<b/></td></tr>";
                $body .= "</table>";
                $body .= "<b>Total " . $map[$key] . " Balance: $modem_bal (" . ($tfr + $diff_tot) . ")<br/>";
                $total += $modem_bal;
                $modem_bals[$key] = $modem_bal;
            }

            foreach($balances as $vend=>$bal){
                $body .= "<br/><br/>" . strtoupper($vend) . " Balance: " . $bal['balance'] . " (" . $bal['last'] . ")";
            }
            $body .= "<br/><br/>Total Balance: $total";

            $last_date = date('Y-m-d', strtotime("- $last days"));
            $days = 4;

            $comm = $this->Slaves->query("SELECT sum(vendors_activations.amount) as sale,sum(vendors_activations.amount*vendors_activations.discount_commission/100) as expected, vendors_activations.vendor_id, vendors_activations.date, earnings_logs.opening,earnings_logs.closing FROM vendors_activations left join earnings_logs ON (earnings_logs.vendor_id = vendors_activations.vendor_id AND earnings_logs.date = vendors_activations.date) WHERE vendors_activations.product_id != 44 AND vendors_activations.status != 2 AND vendors_activations.status != 3 AND vendors_activations.date >= '" . date('Y-m-d', strtotime('-' . $days . ' days')) . "' AND vendors_activations.date <= '$last_date' group by vendors_activations.vendor_id,vendors_activations.date");

            $reversals = $this->Slaves->query("SELECT SUM( vendors_activations.amount ) AS reversal, vendors_activations.vendor_id
                    FROM vendors_activations use index (idx_reversal_date)
                    WHERE vendors_activations.reversal_date = '$last_date' AND vendors_activations.product_id != 44
                    AND vendors_activations.status = 3
                    AND vendors_activations.date != vendors_activations.reversal_date
                    GROUP BY vendors_activations.vendor_id");

            $data = array();
            foreach($comm as $com){
                $data[$com['vendors_activations']['vendor_id']][$com['vendors_activations']['date']]['sale'] = $com['0']['sale'];
                $data[$com['vendors_activations']['vendor_id']][$com['vendors_activations']['date']]['expected'] = $com['0']['expected'];
                $data[$com['vendors_activations']['vendor_id']][$com['vendors_activations']['date']]['opening'] = $com['earnings_logs']['opening'];
                $data[$com['vendors_activations']['vendor_id']][$com['vendors_activations']['date']]['closing'] = $com['earnings_logs']['closing'];
            }

            foreach($reversals as $revs){
                $data[$revs['vendors_activations']['vendor_id']][$last_date]['reversal'] = $revs['0']['reversal'];
            }

            foreach($modem_bals as $key=>$bal){
                if( ! isset($data[$key][$last_date])){
                    $data[$key][$last_date]['sale'] = 0;
                    $data[$key][$last_date]['expected'] = 0;
                }
                $data[$key][$last_date]['closing'] = $bal;
            }



            foreach($data as $key=>$dt){
                foreach($dt as $date=>$vals){
                    $vals['opening'] = intval($vals['opening']);
                    $vals['closing'] = intval($vals['closing']);
                    if(isset($modem_bals[$key]) && $date != $last_date &&  (empty($vals['opening']) || empty($vals['closing']))){
                        $modem_bal = $this->Recharge->modemBalance($date, $key, false, false);
                        $opening = 0;
                        $closing = 0;
                        foreach($modem_bal as $md){
                            $opening += $md['opening'];
                            $closing += $md['closing'];
                        }
                        if(empty($vals['opening']))$vals['opening'] = $opening;
                        if(empty($vals['closing']))$vals['closing'] = $closing;
                    }

                    $saas_flag = 0;
                    if(in_array($key,explode(",",SAAS_VENDORS))){
                        $saas_flag = 1;
                    }

                    $this->Retailer->query("UPDATE earnings_logs SET opening='" . $vals['opening'] . "',closing='" . $vals['closing'] . "', sale='" . $vals['sale'] . "',old_reversal='" . $vals['reversal'] . "',expected_earning='" . $vals['expected'] . "',saas_flag='$saas_flag' WHERE vendor_id = $key AND date = '$date'");
                    $this->Retailer->query("INSERT INTO earnings_logs (opening,closing,sale,expected_earning,old_reversal,vendor_id,date,saas_flag) VALUES ('".$vals['opening']."','" . $vals['closing'] . "','" . $vals['sale'] . "','" . $vals['expected'] . "','" . $vals['reversal'] . "',$key,'" . $date . "','$saas_flag')");

                    if($date == $last_date){
                        $this->Retailer->query("INSERT INTO earnings_logs (opening,vendor_id,date,saas_flag) VALUES ('" . $vals['closing'] . "',$key,'" . date('Y-m-d', strtotime($date . ' +1 days')) . "','$saas_flag')");
                    }


                }
            }


            $this->General->sendMails('Closing Balance', $body, array('backend@pay1.in', 'vinit@pay1.in','orders@pay1.in'), 'mail');

            $this->autoRender = false;
    }



    function logs() {//every 1 hour log
        $hour = date('H') + 1;
        //float
        $float = $this->Slaves->query("SELECT SUM(balance) as bal FROM users WHERE users.id NOT IN (".WHITELIST_USERS.") AND balance > 0");
        $sas_float = $this->Slaves->query("(SELECT SUM(balance) as bal FROM users LEFT JOIN retailers ON (retailers.user_id = users.id) WHERE retailers.parent_id IN (".SAAS_DISTS.") AND users.balance > 0) UNION (SELECT SUM(balance) as bal FROM users LEFT JOIN retailers ON (retailers.user_id = users.id) WHERE retailers.parent_id IN (".SAAS_DISTS.") AND users.balance > 0)");
        $ret_float_without_b2c_retailer = $this->Slaves->query("SELECT balance  FROM users WHERE balance > 0 and id = 44");

        $float = intval($float['0']['0']['bal']);
        $float = $float - $sas_float['0']['0']['bal'] - $sas_float['1']['0']['bal'];
        $float_without_b2c_retailer = intval($float-$ret_float_without_b2c_retailer[0]['users']['balance']);

        //amount transferred
        $sds_trans = $this->Slaves->query("SELECT SUM(amount) as amt FROM shop_transactions WHERE date='" . date('Y-m-d') . "' AND type = " . ADMIN_TRANSFER . " AND confirm_flag != 1 AND target_id NOT IN (" . MDISTS . ")");
        //$dis_trans = $this->Slaves->query("SELECT SUM(amount) as amt FROM shop_transactions WHERE date='" . date('Y-m-d') . "' AND type = " . MDIST_DIST_BALANCE_TRANSFER . " AND confirm_flag != 1 AND source_id IN (" . MDISTS . ") AND target_id NOT IN (" . DISTS . ")");

        $dis_trans = $this->Slaves->query("SELECT SUM(amount) as amt FROM shop_transactions WHERE date='" . date('Y-m-d') . "' AND type = " . MDIST_DIST_BALANCE_TRANSFER . " AND confirm_flag != 1 AND source_id IN (" . MDISTS . ") AND target_id NOT IN (" . DISTS . ") AND target_id NOT IN (".SAAS_DISTS.")");
        $ret_trans = $this->Slaves->query("SELECT SUM(amount) as amt FROM shop_transactions WHERE date='" . date('Y-m-d') . "' AND type = " . DIST_RETL_BALANCE_TRANSFER . " AND confirm_flag != 1 AND source_id IN (" . DISTS . ")");

        //$pullback = $this->Retailer->query("SELECT SUM(amount) as amt FROM shop_transactions WHERE date='".date('Y-m-d')."' AND ((type = ".PULLBACK_RETAILER . " AND ref1_id IN (".DISTS.")) OR  (type = ".PULLBACK_DISTRIBUTOR . " AND ref1_id IN (".MDISTS.") AND ref2_id NOT IN (".DISTS.")) OR (type = ".PULLBACK_MASTERDISTRIBUTOR." AND ref2_id NOT IN (".MDISTS.")))");

        $transferred = intval($sds_trans['0']['0']['amt'] + $dis_trans['0']['0']['amt'] + $ret_trans['0']['0']['amt']);

        //inventory already in system
        $inventory = 0;
        $vendors = $this->Slaves->query("SELECT * FROM `vendors` where update_flag = 0 AND active_flag = 1");
        foreach ($vendors as $vend) {
            $name = $vend['vendors']['shortForm'];
            $balance = $this->Recharge->apiBalance($vend['vendors']['id'],$name);
            if ($balance !== false) {
                $inventory += intval($balance['balance']);
            }
        }

        $modem_bal = 0;

        $data = $this->Shop->getVendors();
        foreach ($data as $dt) {
            if ($dt['vendors']['update_flag'] == 1) {
                $modem_bal += $this->Recharge->modemBalance(date('Y-m-d'),$dt['vendors']['id'],true,false);
            }
        }

        $inventory += $modem_bal;

        //sale
        $sale_all = $this->Retailer->query("SELECT SUM(if(confirm_flag = 1 AND type = 4,amount,0)) as amt, SUM(if(type_flag = 1 AND type = 11 ,amount,0)) as reversal FROM shop_transactions WHERE date='" . date('Y-m-d') . "' AND source_id != 13 AND type in (4,11)");
        $sale = intval($sale_all['0']['0']['amt']);
        $reversals = intval($sale_all['0']['0']['reversal']);

        //comm
        $comm = $this->Retailer->query("SELECT SUM( IF( TYPE =6 AND distributors.parent_id = 3, amount, 0 ) ) AS comm_d, SUM( IF( TYPE =5, amount, 0 ) ) AS comm_sd, SUM( IF( confirm_flag =1 AND TYPE =4, amount * discount_comission /100, 0 ) ) AS comm_r, SUM( IF( TYPE =24, amount, 0 ) ) AS service_charge FROM shop_transactions LEFT JOIN distributors ON (distributors.id = source_id AND distributors.parent_id = 3) WHERE DATE =  '" . date('Y-m-d') . "' AND TYPE IN ( 4, 5, 6, 24 )");
        $comm_tot = intval($comm['0']['0']['comm_d'] + $comm['0']['0']['comm_sd'] + $comm['0']['0']['comm_r']);


        $this->Retailer->query("INSERT INTO float_logs VALUES (NULL,'$float','$transferred','$inventory','$sale','$comm_tot','$reversals','" . date('Y-m-d') . "','" . date('H:i:s') . "',$hour,'".$comm['0']['0']['service_charge']."','".$float_without_b2c_retailer."')");

        $this->autoRender = false;
    }



    function cleanLogs() {
        $this->Retailer->query("DELETE FROM app_req_log WHERE date <= DATE_SUB(curdate(),INTERVAL 90 DAY)");
        $this->Retailer->query("DELETE FROM ussds WHERE date <= DATE_SUB(curdate(),INTERVAL 30 DAY)");
        $this->Retailer->query("DELETE FROM vendors_messages WHERE Date(timestamp) <= DATE_SUB(curdate(),INTERVAL 60 DAY)");
        $this->Retailer->query("DELETE FROM virtual_number WHERE date <= DATE_SUB(curdate(),INTERVAL 60 DAY)");

        //$this->Retailer->query("DELETE FROM vendors_transactions WHERE date <= DATE_SUB(curdate(),INTERVAL 30 DAY)");


        $this->Retailer->query("DELETE FROM repeated_transactions WHERE Date(timestamp) <= DATE_SUB(curdate(),INTERVAL 15 DAY)");
        $this->Retailer->query("DELETE FROM requests_dropped WHERE Date(timestamp) <= DATE_SUB(curdate(),INTERVAL 30 DAY)");

        $this->Retailer->query("DELETE FROM cc_call_logging WHERE date <= DATE_SUB(curdate(),INTERVAL 10 DAY)");

        $this->Retailer->query("DELETE FROM opening_closing WHERE Date(timestamp) <= DATE_SUB(curdate(),INTERVAL 90 DAY)");
        $this->Retailer->query("DELETE FROM complaints WHERE in_date <= DATE_SUB(curdate(),INTERVAL 60 DAY)");

        $this->Retailer->query("DELETE FROM temp_reversed WHERE date <= DATE_SUB(curdate(),INTERVAL 30 DAY)");
        $this->Retailer->query("DELETE FROM temp_transaction WHERE date <= DATE_SUB(curdate(),INTERVAL 1 DAY)");

        if ($this->Retailer->query("INSERT INTO shop_transactions_logs (SELECT * FROM shop_transactions WHERE date <= DATE_SUB(curdate(),INTERVAL 90 DAY))")) {
            $this->Retailer->query("DELETE FROM shop_transactions WHERE date <= DATE_SUB(curdate(),INTERVAL 90 DAY)");
        }

        if ($this->Retailer->query("INSERT INTO vendors_messages_logs (SELECT * FROM vendors_messages WHERE date <= DATE_SUB(curdate(),INTERVAL 90 DAY))")) {
            $this->Retailer->query("DELETE FROM vendors_messages WHERE date <= DATE_SUB(curdate(),INTERVAL 90 DAY)");
        }

        /*if ($this->Retailer->query("INSERT INTO vendors_activations_logs (SELECT * FROM vendors_activations WHERE date <= DATE_SUB(curdate(),INTERVAL 60 DAY))")) {
            $this->Retailer->query("DELETE FROM vendors_activations WHERE date <= DATE_SUB(curdate(),INTERVAL 60 DAY)");
        }*/

        $this->Shop->Memcache->flush();
        $this->autoRender = false;
    }


    function proc_cron() {
        $this->Retailer->query("call wall()");
        $this->autoRender = false;
    }

    function proc_cron_ussd() {
        $this->Retailer->query("call removeUSSDLogs()");
        $this->autoRender = false;
    }

    function test() {
        echo "1232";
    	/*$data = $this->Slaves->query("SELECT * FROM vendors");
    	print_r($data);
        $at = array('asd' => 'asasas');
        $key = key($at);
        echo $at[$key];*/
        //echo "1"; exit;

        $this->autoRender = false;
    }

    function incentiveReminderForRetailer() {// send a reminder about incentive to retailer
        $send == false;
        $sms = "";

        if (date('d') == 1) {//On 1st day of month
            $send = true;
        } else if (date('d') == 15) {
            $send = true;
        } else if (date('d') == 22) {
            $send = true;
        } else if (date('d') == 28 && date('m') == 2) {//if month is feb
            $send = true;
        } else if (date('d') == 29) {
            $send = true;
        }
        $MsgTemplate = $this->General->LoadApiBalance();
        if ($send) {
            if (date('d') == 1) {// to all retailers ( via app ) only
//                $retailers = $this->Slaves->query("SELECT retailers.mobile FROM `retailers_logs`,retailers where retailers.id = retailer_id AND retailers_logs.date > '" . date('Y-m-d', strtotime('-7 days')) . "' group by retailer_id having (sum(retailers_logs.sale) > 0)");
                $retailers = $this->Slaves->query("SELECT r.mobile "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE rel.date > '" . date('Y-m-d', strtotime('-7 days')) . "' "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id "
                        . "HAVING (SUM(rel.amount) > 0)");
            } else {// to active retailers ( via app ) only
//                $retailers = $this->Slaves->query("SELECT retailers.mobile , sum(retailers_logs.app_sale) as total_app_sale FROM `retailers_logs`,retailers where retailers.id = retailer_id AND retailers_logs.date > '" . date('Y-m-d', strtotime('- ' . date('d') . ' days')) . "' group by retailer_id having (sum(retailers_logs.app_sale) > 0)");
                $retailers = $this->Slaves->query("SELECT r.mobile,SUM(rel.amount) AS total_app_sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE rel.date > '" . date('Y-m-d', strtotime('- ' . date('d') . ' days')) . "' "
                        . "AND rel.api_flag = 3 "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id "
                        . "HAVING (SUM(rel.amount) > 0)");
            }
            foreach ($retailers as $dt) {

                $mobile = $dt['retailers']['mobile'];
                $sale = isset($dt['0']['total_app_sale']) ? $dt['0']['total_app_sale'] : 0;

                if (date('d') == 1) {  // for day 1
//                    $sms = "Dear Retailer,
//Application/Web se 25000 sale par kamaiye 50Rs. bonus aur 50000 par 100Rs. bonus !!
//Aaj hi application download kare, misscall on 02267242289";

                  $content =  $MsgTemplate['Retailer_IncentiveReminder_forDay1_MSG'];

                } else if ($sale < 25000) { // if retailer's SALE < 25000
//                    $sms = "Dear Retailer,
//Is month ka application sale hai Rs $sale. 25000 poore karne par milega 50Rs. aur 50000 par 100Rs. bonus !!";

                  $paramdata['SALE'] = $sale;
                  $content =  $MsgTemplate['Retailer_IncentiveReminder_forSale25K_MSG'];

                } else if (25000 <= $sale && $sale < 50000) { // if retailer's sale  25000 < SALE < 50000
//                    $sms = "Dear Retailer,
//Is month ka application sale hai Rs $sale. Aap already 25000 ka target complete kar chuke ho, 50000 complete karne par 100Rs. bonus paiye !!";

		  $paramdata['SALE'] = $sale;
                  $content =  $MsgTemplate['Retailer_IncentiveReminder_forSale50K_MSG'];

                }
                $sms = $this->General->ReplaceMultiWord($paramdata,$content);
                if (!empty($sms)) {

                    $this->General->sendMessage($mobile, $sms, 'notify');
                }
            }
        }
        $this->autoRender = false;
    }

    /*function rentalReminderForRetailer() {

        $retailers = $this->Slaves->query("SELECT retailers.mobile , sum(retailers_logs.app_sale) as total_app_sale FROM `retailers_logs`,retailers where retailers.id = retailer_id AND retailers_logs.date >= '" . date('Y-m-d', strtotime('-4 days')) . "' group by retailer_id having (sum(retailers_logs.app_sale) > 0)");
        $MsgTemplate = $this->General->LoadApiBalance();
        foreach ($retailers as $dt) {

            $mobile = $dt['retailers']['mobile'];
            $sale = $dt['0']['total_app_sale'];

            if ($sale < 25000) { // if retailer's SALE < 25000
                $diff = 25000 - $sale;
//                $sms = "Dear Retailer,
//Apki monthly sale $sale Rs. ho chuki hai. !!
//Agar aap 25000 tak sale puri krte hai to aap apka 50 Rs. rental 0 kar skate hai !!";

                $paramdata['SALE'] = $sale;
                $content =  $MsgTemplate['Retailer_RentalReminder_MSG'];
                $sms = $this->General->ReplaceMultiWord($paramdata,$content);

                $send == true;
            } else {
                $send == false;
            }

            // send a reminder about avoid rental to retailer
            if (intval(date('d')) > ( intval(date('t')) - 3 )) {//On Last 3 days of the month
                if ($send == true) {
                    $this->General->sendMessage($mobile, $sms, 'notify');
                }
            }
        }
        $this->autoRender = false;
    }*/

    function killIdleConnections($slave=0) {
    	if($slave==1){
    		$result = $this->Slaves->query("SHOW FULL PROCESSLIST");
    		$sub = "(Slave)";
    		$time = 200;
    	}
    	else {
    		$result = $this->Retailer->query("SHOW FULL PROCESSLIST");
    		$sub = "";
    		$time = 50;
    	}

        foreach ($result as $res) {
            $process_id = $res['0']['Id'];
            if ($res['0']['Time'] > $time && date('H') > 8 && date('H') < 23  && $res['0']['Command'] == 'Query') {
            	$mail_body = $process_id . " " . json_encode($res);

                $this->General->sendMails("Killing mysql connection$sub", $mail_body, array('ashish@pay1.in','nandan.rana@pay1.in'), 'mail');
                if($slave == 1){
                	$this->Slaves->query("KILL $process_id");
                }
                else $this->Retailer->query("KILL $process_id");
            }
        }
        $this->autoRender = false;
    }


    function issueShowOnBanner() {
        $this->autoRender = false;
        $data = $this->Slaves->query("
                   SELECT
                        count(vendors_activations.id) as ids,
                        vendors_activations.vendor_id,
                        vendors_activations.product_id,
                        products.name,
                        vendors.shortForm,
                        sum(if( vendors_activations.status != 3,amount,0)) as sale,
                        sum(if(vendors_activations.status =0,1,0)) as process,
                        sum(if(vendors_activations.status =2 OR vendors_activations.status =3,1,0)) as failure,
                        vendors.update_flag
                   FROM
                        `vendors_activations`,
                        products,
                        vendors
                   WHERE
                        products.id = vendors_activations.product_id
                   AND
                        vendors.id = vendors_activations.vendor_id
                   AND
                        vendors_activations.date = '" . date('Y-m-d') . "'
                   AND
                        vendors_activations.timestamp <= '" . date('Y-m-d H:i:s', strtotime('-1 minutes')) . "'
                   AND
                        vendors_activations.timestamp >= '" . date('Y-m-d H:i:s', strtotime('-5 minutes')) . "'
                   GROUP BY
                        vendors_activations.product_id,vendors_activations.vendor_id
                   ORDER BY
                        vendors_activations.product_id"
        );


        $prods = array();
        $vendorCurConsmption = array();
        foreach ($data as $dt) {
            if (empty($prods[$dt['vendors_activations']['product_id']])) {
                $prods[$dt['vendors_activations']['product_id']]['total'] = 0;
                $prods[$dt['vendors_activations']['product_id']]['process'] = 0;
                $prods[$dt['vendors_activations']['product_id']]['failure'] = 0;
                //$prods[$dt['vendors_activations']['product_id']]['active'] = 0;
                $prods[$dt['vendors_activations']['product_id']]['success'] = 0;
                $prods[$dt['vendors_activations']['product_id']]['sale'] = 0;
            }

            $prods[$dt['vendors_activations']['product_id']]['total'] += $dt['0']['ids'];
            $prods[$dt['vendors_activations']['product_id']]['process'] += $dt['0']['process'];
            $prods[$dt['vendors_activations']['product_id']]['failure'] += $dt['0']['failure'];
            $prods[$dt['vendors_activations']['product_id']]['success'] += $dt['0']['ids'] - $dt['0']['failure'];
            $prods[$dt['vendors_activations']['product_id']]['sale'] += $dt['0']['sale'];

            $prods[$dt['vendors_activations']['product_id']]['name'] = $dt['products']['name']; //$dt['vendors_activations']['name']; //@TODO1


            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['total'] = $dt['0']['ids'];
            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['process'] = $dt['0']['process'];
            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['failure'] = $dt['0']['failure'];

            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['sale'] = $dt['0']['sale'];

            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['success'] = $dt['0']['ids'] - $dt['0']['failure'];
            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['vendor'] = $dt['vendors']['shortForm']; //$dt['vendors_activations']['shortForm'];//@TODO1
            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['vendor_id'] = $dt['vendors_activations']['vendor_id']; //$dt['vendors_activations']['vendor_id'];//@TODO1

            $prods[$dt['vendors_activations']['product_id']]['vendors'][$dt['vendors_activations']['vendor_id']]['modem_flag'] = $dt['vendors']['update_flag']; //$dt['vendors_activations']['update_flag'];//@TODO1
        }

        $allDet = $prods;
        $array = array();
        $data = $this->Slaves->query("
                    SELECT
                        products.id,
                        products.name,
                        vendors_commissions.vendor_id,
                        vendors.update_flag
                    FROM
                        products,
                        vendors_commissions,
                        vendors
                    WHERE
                        product_id = products.id
                    AND
                        vendors_commissions.vendor_id = vendors.id
                    AND vendors_commissions.active = 1
                    AND monitor = 1"
        );

        foreach ($data as $prod) {
            if (!isset($prods[$prod['products']['id']])) {
                $array['no'][] = $prod['products']['name'];
            } else if (($prods[$prod['products']['id']]['total'] >= 5 && $prods[$prod['products']['id']]['failure'] * 100 / $prods[$prod['products']['id']]['total'] >= 10) || $prods[$prod['products']['id']]['failure'] * 100 / $prods[$prod['products']['id']]['total'] >= 50) {
                $array['failure'][$prod['products']['id']] = $prods[$prod['products']['id']];
            } else if ($prods[$prod['products']['id']]['total'] >= 20 && $prods[$prod['products']['id']]['process'] * 100 / $prods[$prod['products']['id']]['total'] >= 25) {
                $array['process'][$prod['products']['id']] = $prods[$prod['products']['id']];
            }

            $active_vendor = $prod['vendors_commissions']['vendor_id'];
            $prods[$prod['products']['id']]['active_total'] = isset($prods[$prod['products']['id']]['vendors'][$active_vendor]['total']) ? $prods[$prod['products']['id']]['vendors'][$active_vendor]['total'] : 0;
        }

        $mail_body = "";
        if (!empty($array['no'])) {
            $mail_body .= "<b style='font-size:15px;font-weight:bold;color:blue;' >No transactions :</b>";
            foreach ($array['no'] as $val) {
                $mail_body .= $val . ", ";
            }
            $mail_body .= "</br>";
        }
        //$mail_body ="<b style='font-size:15px;font-weight:bold;color:blue;'> No transactions happening in:</b>Aircel, Idea, Loop, MTS, Reliance CDMA, Reliance GSM, Videocon, Sun TV DTH, Airtel, BSNL,</br>";//

        if (!empty($array['failure'])) {
            $mail_body .= "<b style='font-size:15px;font-weight:bold;color:blue;'>Failures in:</b>";
            foreach ($array['failure'] as $prod => $val) {
                $mail_body .= $val['name'] . "(" . intval($val['failure'] * 100 / $val['total']) . "%), ";
            }
        }
        //echo  $mail_body .="<b style='font-size:15px;font-weight:bold;color:blue;'>Failures :</b>MTS(5%), BSNL(9%), Airtel DTH(6%), Uninor(24%), Aircel(6%), BSNL SV(13%), Airtel(10%), ";//
        $this->Shop->setMemcache('failures', $mail_body);

        $mail_body = addslashes($mail_body);
        $faildata = $this->Retailer->query("update vars set `value` = '$mail_body' WHERE `name` = 'failures'");
        //echo $mail_body;
    }

    function autoShiftPay1Topup() {

        $url = B2C_URL."api/true/actiontype/Daily_transaction/?url=1&date=".date('Y-m-d');

    	$data = $this->General->curl_post($url,null,'GET',30,30);
    	$info = json_decode($data['output'],true);

    	//$sale_all = $this->Retailer->query("SELECT SUM(if(confirm_flag = 1 AND type = 4,amount,0)) as amt, SUM(if(type_flag = 1 AND type = 11 ,amount,0)) as reversal FROM shop_transactions WHERE date='" . date('Y-m-d') . "' AND ref2_id = 44 AND type in (4,11)");
    	$balance = $this->Shop->getShopDataById(13,RETAILER);
		$mail_subject = "Balance tfrd to Pay1 B2C Retailer";
    	if(empty($info)){
    		$this->General->sendMails($mail_subject, "Not found any data from b2c", array("tadka@pay1.in","finance@pay1.in"), 'mail');
    		return;
    	}

    	$this->General->logData('b2c.txt',"B2C Data::".json_encode($info)."::".json_encode($balance));

    	$money_to_tfr = $info['total_wallet_balnce'] - $balance['balance'];
        if ($money_to_tfr > 0) {
    		$type = 'add';
    	}
    	else {
    		$type = 'subtract';
    		$money_to_tfr = 0 - $money_to_tfr;
    	}

    	/*  Added as changes for DB optimization  */
        $dataSource = $this->Retailer->getDataSource();

        try {
                $dataSource->begin();
                $bal1 = $this->Shop->shopBalanceUpdate($money_to_tfr, $type, 44, RETAILER, $dataSource);
                $recId = $this->Shop->shopTransactionUpdate(DIST_RETL_BALANCE_TRANSFER, $money_to_tfr, 0, 13, 1,null,null,null,null,null,$bal1-$money_to_tfr,$bal1,$dataSource);

                if($recId === false || empty($recId)) {
                        throw new Exception ('Entry Failed');
                }
                $dataSource->commit();
				$mail_body = "Amount transferred: $money_to_tfr<br/>Total balance now is $bal1<br/>Blocked balance is ".$info['total_blocked_amt'];

				$this->General->sendMails($mail_subject, $mail_body, array("tadka@pay1.in","finance@pay1.in"), 'mail');

        } catch (Exception $e) {
                $dataSource->rollback();
        }
//        $this->Shop->addOpeningClosing(13, RETAILER, $recId, $bal1 - $money_to_tfr, $bal1);

        $this->autoRender = false;
    }

    /*function tfredAmount($date = null) {
        if (empty($date))
            $date = date('Y-m-d');
        //amount transferred
        $sds_trans = $this->Slaves->query("SELECT SUM(amount) as amt FROM shop_transactions WHERE date='$date' AND type = " . ADMIN_TRANSFER . " AND confirm_flag != 1 AND target_id NOT IN (" . MDISTS . ")");
        $dis_trans = $this->Slaves->query("SELECT SUM(amount) as amt FROM shop_transactions WHERE date='$date' AND type = " . MDIST_DIST_BALANCE_TRANSFER . " AND confirm_flag != 1 AND source_id IN (" . MDISTS . ") AND target_id NOT IN (" . DISTS . ")");
        $ret_trans = $this->Slaves->query("SELECT SUM(amount) as amt FROM shop_transactions WHERE date='$date' AND type = " . DIST_RETL_BALANCE_TRANSFER . " AND confirm_flag != 1 AND source_id IN (" . DISTS . ")");
        if(empty($sds_trans[0][0]['amt'])){
			$sds_trans = $this->Slaves->query("SELECT SUM(amount) as amt FROM shop_transactions_logs WHERE date='$date' AND type = " . ADMIN_TRANSFER . " AND confirm_flag != 1 AND target_id NOT IN (" . MDISTS . ")");
		}

		if(empty($dis_trans[0][0]['amt'])){
			$dis_trans = $this->Slaves->query("SELECT SUM(amount) as amt FROM shop_transactions_logs WHERE date='$date' AND type = " . MDIST_DIST_BALANCE_TRANSFER . " AND confirm_flag != 1 AND source_id IN (" . MDISTS . ") AND target_id NOT IN (" . DISTS . ")");
		}

		if(empty($ret_trans[0][0]['amt'])){

			$ret_trans = $this->Slaves->query("SELECT SUM(amount) as amt FROM shop_transactions_logs WHERE date='$date' AND type = " . DIST_RETL_BALANCE_TRANSFER . " AND confirm_flag != 1 AND source_id IN (" . DISTS . ")");
		}

        //$pullback = $this->Retailer->query("SELECT SUM(amount) as amt FROM shop_transactions WHERE date='".date('Y-m-d')."' AND ((type = ".PULLBACK_RETAILER . " AND ref1_id IN (".DISTS.")) OR  (type = ".PULLBACK_DISTRIBUTOR . " AND ref1_id IN (".MDISTS.") AND ref2_id NOT IN (".DISTS.")) OR (type = ".PULLBACK_MASTERDISTRIBUTOR." AND ref2_id NOT IN (".MDISTS.")))");

        $transferred = intval($sds_trans['0']['0']['amt'] + $dis_trans['0']['0']['amt'] + $ret_trans['0']['0']['amt']);

        echo "Amount transferred by Admin: " . intval($sds_trans['0']['0']['amt']);
        echo "<br/>Amount transferred by Mindsarray (SD): " . intval($dis_trans['0']['0']['amt']);
        echo "<br/>Amount transferred by Distributors: " . intval($ret_trans['0']['0']['amt']);
        echo "<br/>Total amount transferred: $transferred";
        $this->Retailer->query("UPDATE float_logs SET transferred = '$transferred' WHERE date='$date' AND hour = 24");
        $this->autoRender = false;
    }*/

    function checkVendors($id = 0) {//exit;
        //$this->layout = null;
        $this->autoRender = false;

        $vendors = $this->Slaves->query("SELECT `id` , `company` , `shortForm` ,last30bal FROM `vendors` WHERE `update_flag` =0");

        // print_r($vendors);
        //$this->autoRender = false;
        //exit ;
        $vendorsData = array(); //print_r($vendors);
        foreach ($vendors as $key => $value) {
            $vendorsData[trim($value["vendors"]["shortForm"])]["shortForm"] = $value ["vendors"]["shortForm"];
            $vendorsData[trim($value["vendors"]["shortForm"])]["company"] = $value["vendors"]["company"];
            $vendorsData[trim($value["vendors"]["shortForm"])]["last30bal"] = $value["vendors"]["last30bal"];
            $vendorsData[trim($value["vendors"]["shortForm"])]["id"] = $value["vendors"]["id"];
        }


        $dataSale = $this->Slaves->query("
                                               SELECT
                                                    count(vendors_activations.id) as ids,
                                                    vendors_activations.vendor_id,
                                                    vendors.shortForm,
                                                    sum(if( vendors_activations.status != 3,amount,0)) as sale,
                                                    sum(if( vendors_activations.status != 3,discount_commission*amount/100,0)) as comm
                                               FROM
                                                    `vendors_activations`,
                                                    vendors
                                               WHERE
                                                    vendors.id = vendors_activations.vendor_id
                                               AND
                                                    vendors.update_flag = 0
                                               AND
                                                    vendors_activations.date = '" . date('Y-m-d') . "'
                                               AND
                                                    vendors_activations.timestamp >= '" . date('Y-m-d H:i:s', strtotime('-30 minutes')) . "'
                                               GROUP BY
                                                    vendors_activations.vendor_id
                                              "
        );




        foreach ($dataSale as $key => $value) {

            $bal = $this->Recharge->apiBalance($value["vendors_activations"]["vendor_id"],$value["vendors"]["shortForm"]);

            if (!empty($bal)) {
                $bal = $bal['balance'];
                $this->Retailer->query("Update vendors set last30bal = '$bal',update_time='" . date('Y-m-d H:i:s') . "' where shortForm = '" . $value["vendors"]["shortForm"] . "'");

                $diff = ($vendorsData[$value["vendors"]["shortForm"]]["last30bal"] - $bal) - ($value["0"]["sale"] - $value["0"]["comm"]);
                if ($diff > 1000) {
                    //stop vendor
                    $pid = $vendorsData[$value["vendors"]["shortForm"]]["id"];
                    //send email
                    $subject = "(SOS)System disabled $vendor";
                    $this->General->sendMails($subject, "Problem in Vendors Balance in last 30 minutes!!! </br>Vendor : " . $vendorsData[$value["vendors"]["shortForm"]]["company"] . "</br>
                                                    Balance (Before 30 min) : " . $vendorsData[$value["vendors"]["shortForm"]]["last30bal"] . "</br>
                                                    Balance (Current) : " . $bal . "</br>
                                                    Sale in last 30 mins : " . ($value["0"]["sale"] - $value["0"]["comm"]) . "</br>
                                                    Diff : " . $diff . "</br>
                                                    ", array('ashish@pay1.in'), 'mail'
                    );

                }
            }
        }
    }

    function autoDeclineComplaints() {//5,8 exception scenario

//		$vendor = array('58','8','87','149','68');
		$vendor = Configure::read('AutoDeclinedVendorIds');

//		$product = array('3','34','30','31','7','8');
		$product = Configure::read('AutoDeclinedProductIds');

                $data = $this->Slaves->query("SELECT vendors_activations.txn_id, complaints.resolve_flag,vendors_messages.status,vendors_messages.response
										FROM complaints
										inner join vendors_activations
										ON (vendors_activations.id = vendor_activation_id)
										INNER JOIN vendors ON
										(vendors.id = vendors_activations.vendor_id)
										LEFT JOIN vendors_messages
										ON ( vendors_messages.va_tran_id = vendors_activations.txn_id AND vendors_activations.vendor_id = vendors_messages.service_vendor_id )
                                        WHERE
										in_date >= '" . date('Y-m-d', strtotime('-1 days')) . "' AND vendors.update_flag= 1 AND vendors_messages.response = 'Successful' AND complaints.takenby = 0   group by vendor_activation_id having (count(complaints.id) = 1 AND complaints.resolve_flag = 0)");
                
                $apiQuery = $this->Slaves->query("SELECT vendors_activations.txn_id,vendors_activations.vendor_id,vendors_activations.product_id,complaints.resolve_flag,vendors_activations.timestamp,complaints.in_date,complaints.in_time,TIMESTAMPDIFF(SECOND,vendors_activations.timestamp,concat(complaints.in_date,' ',complaints.in_time)) as timediff
                                                                                    FROM complaints
                                                                                    inner join vendors_activations
                                                                                    ON (vendors_activations.id = vendor_activation_id)
                                                                                    inner JOIN vendors ON
                                                                                    (vendors.id = vendors_activations.vendor_id)
                                                                                    WHERE
                                                                                    in_date >= '" . date('Y-m-d', strtotime('-1 days')) . "'  and vendors.update_flag= 0 AND  complaints.takenby = 0 ANd operator_id != '' and  operator_id is not null group by vendor_activation_id having (count(complaints.id) = 1 AND complaints.resolve_flag = 0)");
                
                foreach ($data as $dt) {
                    $ret = $this->Shop->reversalDeclined($dt['vendors_activations']['txn_id'], 1);
                }

		 foreach ($apiQuery as $dt) {
                        if((in_array($dt['vendors_activations']['vendor_id'],$vendor) && in_array($dt['vendors_activations']['product_id'],$product)) || (in_array($dt['vendors_activations']['vendor_id'],array(165,173)) && in_array($dt['vendors_activations']['product_id'],array(2,83))) || (in_array($dt['vendors_activations']['vendor_id'],array(57,177)) && in_array($dt['vendors_activations']['product_id'],array(83))) || (in_array($dt['vendors_activations']['vendor_id'],array(180)) && in_array($dt['vendors_activations']['product_id'],array(2)))){
                                $ret = $this->Shop->reversalDeclined($dt['vendors_activations']['txn_id'], 1);
                        } elseif(in_array($dt['vendors_activations']['vendor_id'], array(24,68,87,135,165,177,180)) && in_array($dt['vendors_activations']['product_id'],array(2,83))){
                               if($dt[0]['timediff'] <= 300){
                                   $ret = $this->Shop->reversalDeclined($dt['vendors_activations']['txn_id'], 1);
                               }
                        } else {
                            if(!in_array($dt['vendors_activations']['product_id'],$product)){
                                if(!(in_array($dt['vendors_activations']['vendor_id'],array(68,180)) && in_array($dt['vendors_activations']['product_id'],array(83))) && !(in_array($dt['vendors_activations']['vendor_id'],array(87)) && in_array($dt['vendors_activations']['product_id'],array(2)))){
                                    $ret = $this->Shop->reversalDeclined($dt['vendors_activations']['txn_id'], 1);
                                }
                            }
                        }

        }
        $this->autoRender = false;
    }

    function payu_check_cron() {
        $txnqry = "SELECT shop_transaction_id,productinfo FROM `pg_payuIndia` WHERE status ='pending' AND shop_transaction_id is not null AND addedon <= '" . date('Y-m-d H:i:s', strtotime('- 10 minutes')) . "' order by id limit 100";
        $txn_result = $this->Retailer->query($txnqry);

        if (empty($txn_result)) {
            exit();
        }

        $txnids = array();
        foreach ($txn_result as $txn) {
            $txnids[] = $txn['pg_payuIndia']['shop_transaction_id'];
        }

        //$logger = $this->General->dumpLog('Payu', 'receivePayuStatus');

        $var1 = implode('|', $txnids);
        $service_api_data = array('var1' => $var1, 'command' => 'verify_payment', 'key' => PAYU_KEY);
        $hash = hash("sha512", PAYU_KEY . "|" . $service_api_data['command'] . "|" . $service_api_data['var1'] . "|" . PAYU_SALT);
        $service_api_data['hash'] = $hash;

        $out = $this->General->curl_post(PAYU_SERVICE_URL, $service_api_data);

        if ($out['success']) {
            $result_data = unserialize($out['output']);
            if (count($result_data['transaction_details']) > 0) {
                foreach ($result_data['transaction_details'] as $txn_num => $txndetail) {
                    if ($txndetail['status'] === "Not Found") {
                        $txndetail['txnid'] = $txn_num;
                        $txndetail['status'] = 'failure';
                        unset($txndetail['mihpayid']);
                        $this->Shop->update_pg_payu($txndetail, false);
                    } else {
                        $txndetail['amount'] = isset($txndetail['amount']) ? $txndetail['amount'] : "";
                        $txndetail['amount'] = ($txndetail['amount'] == "" && isset($txndetail['amt'])) ? $txndetail['amt'] : "";
                        $this->Shop->update_pg_payu($txndetail, false);
                    }
                }
            }
            //return array('txn_id'=>$var1,'result'=>$result_data);
        }
        $this->autoRender = false;
    }



    private function arrayRecursiveDiff($aArray1, $aArray2) {
        $aReturn = array();

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = $this->arrayRecursiveDiff($mValue, $aArray2[$mKey]);

                    if (count($aRecursiveDiff)) {
                        $aReturn[$mKey] = $aRecursiveDiff;
                    }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
        return $aReturn;
    }

    function getAPIBalance(){
        $vendors = $this->Slaves->query("SELECT * FROM `vendors` where update_flag = 0 AND show_flag = 1");

        foreach ($vendors as $vend) {
            $this->Recharge->apiBalance($vend['vendors']['id'],$vend['vendors']['shortForm'],false);
        }
        $this->autoRender =false;
    }


    //CRON FOR UPDATING RECORD ONCE
    /*function updateRetailersSale() {

        $prevDate = date('Y-m-d', strtotime("-6 Months"));
        $currDate = date('Y-m-01', strtotime("-1 Months"));

		//$currDate = '2015-01-01';
		//$prevDate = '2015-07-01';

//		 $data = $this->Slaves->query("SELECT sum(sale) AS benchmark_value,count(distinct retailers_logs.retailer_id) as transacting,trim(retailers.parent_id) AS disId,retailers_logs.date FROM retailers_logs use index (uniq_ret_date) INNER JOIN retailers ON retailers.id = retailers_logs.retailer_id WHERE retailers_logs.DATE<='$currDate' AND retailers_logs.DATE>='$prevDate' GROUP BY retailers.parent_id,month(retailers_logs.date)");
		 $data = $this->Slaves->query("SELECT SUM(rel.amount) AS benchmark_value,COUNT(DISTINCT rel.ret_user_id) AS transacting,r.parent_id AS disId,rel.date "
                         . "FROM retailer_earning_logs rel "
                         . "INNER JOIN retailers r ON (rel.ret_user_id = r.id) "
                         . "WHERE rel.date <= '$currDate' "
                         . "AND rel.date >= '$prevDate'"
                         . "AND rel.service_id IN (1,2,4,5,6,7) "
                         . "GROUP BY r.parent_id,MONTH(rel.date)");
         $transacting = array();

        foreach($data as $dt){
        	$dist = $dt['0']['disId'];
			$monthdays = date('t',  strtotime($dt['rel']['date']));
        	if(!isset($transacting[$dist]['benchmark'])){
        		$transacting[$dist]['benchmark'] = intval($dt['0']['benchmark_value']/$monthdays);
        		$transacting[$dist]['transacting'] = intval($dt['0']['transacting']);
        	}else {
        		if($dt['0']['benchmark_value']/$monthdays > $transacting[$dist]['benchmark']){
        			$transacting[$dist]['benchmark'] = intval($dt['0']['benchmark_value']/$monthdays);
        		}

        		if($dt['0']['transacting'] > $transacting[$dist]['transacting']){
        			$transacting[$dist]['transacting'] = intval($dt['0']['transacting']);
        		}

        	}
        }

        foreach ($transacting as $key=>$val) {
        	//$this->Retailer->query("UPDATE distributors SET benchmark_value = '" . $val['benchmark'] . "',transacting_retailer = '" . $val['transacting']  . "' where id ='" . $key . "'");
        }

        foreach ($primarytxn as $dt) {
            //$this->Retailer->query("UPDATE distributors_logs SET primary_txn = '" . $dt[0]['primarytxn'] . "' where distributor_id ='" . $dt[0]['rid'] . "' AND date = '" . $dt['shop_transactions']['date'] . "'");
        }

        $this->autoRender = false;
    }*/

	function sendSlowLogData() {

		$data = $this->Retailer->query("SELECT *
										FROM mysql.slow_log
										WHERE DATE_FORMAT(start_time,  '%Y-%m-%d' ) =  '" . date('Y-m-d') . "'
										AND time_to_sec(query_time) >10 ORDER BY query_time DESC LIMIT 20");

		$data1 = $this->Slaves->query("SELECT *
										FROM mysql.slow_log
										WHERE DATE_FORMAT(start_time,  '%Y-%m-%d' ) =  '" . date('Y-m-d') . "'
										AND time_to_sec(query_time) >10 ORDER BY query_time DESC LIMIT 20");

		$subject = "Slow query";
		$body = "";
		if (count($data) > 0) {
			foreach ($data as $val) {
				$body .="Query:&nbsp;&nbsp;&nbsp;" . $val['slow_log']['sql_text'] . "<br/></br/>Host Machine:&nbsp;&nbsp;&nbsp;" . $val['slow_log']['user_host'] . "<br/><br/>Start time:&nbsp;&nbsp;&nbsp;" . $val['slow_log']['start_time'] . "<br/><br/>Execution time:&nbsp;&nbsp;&nbsp;" . $val['slow_log']['query_time'] . "<br/><br/>";
			}
			$this->General->sendMails($subject, $body, array('ashish@pay1.in','nandan.rana@pay1.in'), 'mail');
		}

		if (count($data1) > 0) {
			foreach ($data1 as $val) {
				$body .="Query:&nbsp;&nbsp;&nbsp;" . $val['slow_log']['sql_text'] . "<br/></br/>Host Machine:&nbsp;&nbsp;&nbsp;" . $val['slow_log']['user_host'] . "<br/><br/>Start time:&nbsp;&nbsp;&nbsp;" . $val['slow_log']['start_time'] . "<br/><br/>Execution time:&nbsp;&nbsp;&nbsp;" . $val['slow_log']['query_time'] . "<br/><br/>";
			}
			$this->General->sendMails($subject . "(Slave Server)", $body, array('ashish@pay1.in'), 'mail');
		}

		//echo $body;
		$this->autoRender = false;
	}

	/*function updateopeningClosing() {
		$distOpening = $this->Retailer->query("SELECT * FROM (SELECT amount,opening,closing,shop_id,date FROM `shop_transactions` left join opening_closing ON (shop_transaction_id = shop_transactions.id AND group_id = 5) where date between '".date('Y-m-d',strtotime('-12 days'))."' AND '".date('Y-m-d',strtotime('-2 days'))."' AND ((ref1_id = shop_id AND type = 2) OR (ref2_id = shop_id AND type = 1))  order by opening_closing.timestamp desc)as t group by t.shop_id,t.date");
		if(count($distOpening)>0){
			foreach ($distOpening as $dt){
			$this->Retailer->query("UPDATE distributors_logs set closing_balance = '".$dt['t']['closing']."' WHERE date ='".$dt['t']['date']."' AND distributor_id = '".$dt['t']['shop_id']."'");
			}
		}



		$this->autoRender = false;
	}*/

	function setAPILimit(){
		$data = $this->Slaves->query("SELECT * FROM vendors_commissions WHERE cap_per_min > 0");
		$sale = $this->Slaves->query("SELECT sum(amount) as totalsale,product_id,vendor_id FROM vendors_activations WHERE status NOT IN (2,3) AND date = '".date('Y-m-d')."' GROUP BY vendor_id, IF(product_id = 9 OR product_id = 10 OR product_id = 27, 9, IF(product_id = 11 OR product_id = 29, 11, IF(product_id = 3 OR product_id = 34, 3 , IF (product_id = 7 OR product_id = 8, 8, IF (product_id = 12 OR product_id = 28, 12, IF(product_id = 30 OR product_id = 31,30,product_id)) ))))");

		$arr_map = array('7'=>'8','10'=>'9','27'=>'9','28'=>'12','29'=>'11','31'=>'30','34'=>'3','181'=>'18');

		foreach($data as $dt){
			$prod = $dt['vendors_commissions']['product_id'];
			$vendor_id = $dt['vendors_commissions']['vendor_id'];
			$capacity = $dt['vendors_commissions']['cap_per_min'];
			$prod = (isset($arr_map[$prod]))? $arr_map[$prod] :  $prod;

			$this->Shop->setMemcache("cap_api_$prod"."_$vendor_id",$capacity,120);
			$this->Shop->setMemcache("status_$prod"."_$vendor_id",$capacity,120);
            $this->Shop->setMemcache("cap_inprocess_$prod"."_$vendor_id",$capacity,120);
		}

		foreach($sale as $sl){
			$prod = $sl['vendors_activations']['product_id'];
			$prods = explode(",",$this->Shop->getOtherProds($prod));
			$vendor_id = $sl['vendors_activations']['vendor_id'];
			$totalsale = $sl['0']['totalsale'];

			foreach($prods as $prod){
				$this->Shop->setMemcache("sale_$prod"."_$vendor_id",$totalsale,120);
			}
		}

		$this->autoRender = false;
	}


	/*function tranferFileS3Aws() {

		App::import('vendor', 'S3', array('file' => 'S3.php'));
		$bucket = 'pay1bucket';
		$path = $_SERVER["DOCUMENT_ROOT"] . "/uploads/";
		$s3 = new S3(awsAccessKey, awsSecretKey);
		$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
		$scandir = scandir($path);

		foreach ($scandir as $val) {
			if ($val != '.' && $val != '..') {
				$actual_image_name = $val;
				if ($s3->putObjectFile($path . $actual_image_name, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ)) {
					$imgUrl = 'http://' . $bucket . '.s3.amazonaws.com/' . $actual_image_name;

					$this->Retailer->query("UPDATE retailers_details SET image_name = '" . addslashes($imgUrl) . "' WHERE image_name = '".addslashes($actual_image_name)."'");
					//echo "<img src = " . $imgUrl . " height='300px;' width='300px;'>";
				}
			}
		}

		$this->autoRender = false;
	}*/



	/*function updateCCReport(){
		$this->autoRender = false;
		$date = "2015-05-16";
		$comments = $this->Retailer->query("select ref_code, date, retailers_id from comments where date >= '$date'");
		foreach($comments as $c){
			if($c['comments']['ref_code']){
				$comments_count = $this->Retailer->query("select ref_code, count from comments_count where ref_code = '".$c['comments']['ref_code']."' and date = '".$c['comments']['date']."'");
				$va = $this->Retailer->query("select vendor_id, product_id, api_flag from vendors_activations where ref_code = '".$c['comments']['ref_code']."'");

				if($comments_count)
					$this->Retailer->query("update comments_count set count = count + 1 where ref_code = '".$c['comments']['ref_code']."' and date = '".$c['comments']['date']."'");
				else
					$this->Retailer->query("insert into comments_count(ref_code, count, vendor_id, product_id, retailer_id, medium, date) values('".$c['comments']['ref_code']."', 1, ".$va[0]['vendors_activations']['vendor_id'].", ".$va[0]['vendors_activations']['product_id'].", '".$c['comments']['retailers_id']."', ".$va[0]['vendors_activations']['api_flag'].", '".$c['comments']['date']."')");
			}
		}
		echo "Done";
	}

	function getMemcacheKeys() {
		$memcache = new Memcache;
		$memcache->connect('127.0.0.1', 11211) or die("Could not connect to memcache server");

		$list = array();
		$allSlabs = $memcache->getExtendedStats('slabs');

		$items = $memcache->getExtendedStats('items');

		foreach ($allSlabs as $server => $slabs) {
			foreach ($slabs AS $slabId => $slabMeta) {
				$cdump = $memcache->getExtendedStats('cachedump', (int) $slabId);

				foreach ($cdump AS $keys => $arrVal) {
					if (!is_array($arrVal))
						continue;
					foreach ($arrVal AS $k => $v) {
						echo $k . '<br>';
					}
				}
			}
		}
		$this->autoRender = false;
	}*/



    function unlock_txn_before_hour(){
        $curTime = date('Y-m-d H:i:s');
        $qry = "DELETE FROM `temp_txn` WHERE time_to_sec(timediff('$curTime',timestamp)) >  (60*15)";
        $this->Retailer->query($qry);
        $this->autoRender = false;
    }



	/**
	 * Function for updating retailers benchmark sale
	 */
	/*function updateRetailersBenchmark	(){

		$this->autoRender = false;

		$prevDate = date('Y-m-d', strtotime("-30 days "));
		$currDate = date('Y-m-d', strtotime("-1 days"));

		//last 30 days sale
//		$dataCurrentAverageSale = $this->Slaves->query("
//							SELECT
//								retailers.id, sum(sale) AS base_value
//							FROM
//								retailers_logs use index (uniq_ret_date)
//							INNER JOIN
//								retailers
//							ON
//								retailers.id = retailers_logs.retailer_id
//							WHERE
//								retailers_logs.DATE between '$prevDate' AND '$currDate'
//							GROUP BY
//								retailers.id"
//						);
		$dataCurrentAverageSale = $this->Slaves->query("SELECT r.id, SUM(rel.amount) AS base_value "
                        . "FROM retailer_earning_logs rel "
                        . "INNER JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE rel.date BETWEEN '$prevDate' AND '$currDate' "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id");
// 		echo "<pre>";
// 		print_r($dataBaseSale);
		$currentAverageSale = array();

		foreach ($dataCurrentAverageSale as  $index => $arr){
			$currentAverageSale[$arr['retailers']['id']] = $arr[0]['base_value']/30;
		}

		//GETTING PREVIOUS BENCHMARK SET
		$dataBenchmark = $this->Slaves->query("
							SELECT
								id, ret_benchmark_value
							FROM
								retailers"
						);
		$benchmark = array();

		foreach ($dataBenchmark as $index => $arr){
			$benchmark[$arr['retailers']['id']] = $arr['retailers']['ret_benchmark_value'];
		}

// 		print_r($currentAverageSale);
// 		print_r($benchmark);

		foreach ($currentAverageSale as $id => $amt){
			if(!isset($benchmark[$id]))
				$benchmark[$id] = 0;
			if($benchmark[$id] < $amt)
				$benchmark[$id] = $amt;
		}

		foreach ($benchmark as $id=>$amt) {
			if($amt != 0){
				$query = "UPDATE retailers 	SET ret_benchmark_value = '$amt' WHERE id = $id ";
// 				echo "$query<br>";
				$this->Retailer->query($query);
			}
		}

	}*/

	function blockNonworkingRetailers(){
//		$query = "SELECT retailers.id, max( retailers_logs.date ) AS maxdate
//FROM `retailers`
//LEFT JOIN retailers_logs ON ( retailer_id = retailers.id )
//WHERE parent_id =1
//AND toshow =1
//AND date( created ) < '".date('Y-m-d',strtotime('- 30 days'))."'
//GROUP BY retailers.id
//ORDER BY maxdate limit 5000";
		$query = "SELECT r.id,MAX(rel.date) AS maxdate "
                        . "FROM retailers r "
                        . "LEFT JOIN retailer_earning_logs rel ON (r.user_id = rel.ret_user_id) "
                        . "WHERE r.parent_id = 1 "
                        . "AND r.toshow = 1 "
                        . "AND date(r.created) < '".date('Y-m-d',strtotime('- 30 days'))."'"
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id "
                        . "ORDER BY maxdate "
                        . "LIMIT 5000";

		$data = $this->Slaves->query($query);
		foreach($data as $dt){
			if(empty($dt['0']['maxdate']) || $dt['0']['maxdate'] < date('Y-m-d',strtotime('- 30 days')) ){
				$ids[] = $dt['r']['id'];
			}
			echo count($ids) . "<br/>";
		}

		$this->Retailer->query("UPDATE retailers SET toshow = 0,modified='".date('Y-m-d H:i:s')."' WHERE id in (".implode(",",$ids).")");
		$this->autoRender = false;
	}

	function checkVendorHealth(){
		$time = date('Y-m-d H:i:s');
                $date = date('Y-m-d');
		$query = "select max(sync_timestamp),vendor_id,TIME_TO_SEC(TIMEDIFF('$time',sync_timestamp)) FROM devices_data where sync_date='$date' AND TIME_TO_SEC(TIMEDIFF('$time',sync_timestamp)) > 300 group by vendor_id";

		$data = $this->Slaves->query($query);
		foreach($data as $dt){
			$vendor = $dt['devices_data']['vendor_id'];
			$info = $this->Shop->getVendorInfo($vendor);
			if($info['active_flag'] == 1 && $info['update_flag'] == 1){
				$this->Shop->unHealthyVendor($vendor,20);
			}
		}

		$this->autoRender = false;
	}

    /**
     * API vendor generate insert data in api_transactions
     * @param type $autoflag : true to dump record of all 24 hrs
     * @param type $prev_day_num : 1= yesterday, 2=day before yesterday ...
     * @param type $vendorId : vendor ID of api vendor in case to process individually
     * @param type $hour : hour in 24 hr format in case to process for single hour
     */
	function sync_api_txn_data($autoflag = false, $prev_day_num = 1, $vendorId = null, $hour_num = null) {

        if (empty($vendorId)):
            $API_vendor_qry = "SELECT id,shortForm FROM vendors where update_flag=0 and id not in (22) order by 1 desc ";
        else:
            $API_vendor_qry = "SELECT id,shortForm FROM vendors where update_flag=0 and id='$vendorId' order by 1 desc ";
        endif;

        $txn_hour = is_null($hour_num) ? date("H") : $hour_num;

        $API_vendorList = $this->Slaves->query($API_vendor_qry);

        $statusList = array('0' => 'pending', '1' => 'success', '2' => 'failure', '3' => 'failure', '4' => 'success', '5' => 'success');//---status list

        $search_date = date('Y-m-d', strtotime('-' . $prev_day_num . ' days'));//----- generation date from 'no_of_days_before_yesterday'

        $hoursList = ($autoflag == true) ? array(range(0, 23)) : array(array($txn_hour)) ; // if autoflage is true generator for all hours in a day

        foreach ($hoursList[0] as $hour):

            foreach ($API_vendorList as $key => $val):

                $vendorId = $val['vendors']['id'];
                $chk_exist_qry = "SELECT count(1) as total FROM api_rec_log where date='$search_date' and vendor_id='$vendorId' and hour='$hour' and status=1";

                $chk_qry_data = $this->Retailer->query($chk_exist_qry);

                if ($chk_qry_data[0][0]['total'] > 0):
                    echo "$vendorId :: $hour continue<hr>";
                    continue;
                endif;

                // Query to get combine data from vendor_activataion and vendor_messages
                $txn_qry = "SELECT * FROM (SELECT distinct va.id,va.date,(CASE WHEN va.vendor_id = vm.service_vendor_id THEN va.status ELSE '2' END) as status,"
                        . " vm.vendor_refid,va.txn_id,va.amount,va.vendor_id "
                        . "FROM `vendors_activations` as va "
                        . "LEFT JOIN vendors_messages as vm ON (vm.va_tran_id=va.txn_id) "
                        . "WHERE date = '$search_date' AND vm.service_vendor_id=$vendorId and va.hr=$hour "
                        . "ORDER BY vm.timestamp desc) as api_txn_data "
                        . "GROUP BY vendor_id,txn_id";

                $apiTXNs = $this->Slaves->query($txn_qry);

                $i = 1;// Setting initial counter

                $batch_qry = array();// Initializing batch query array

                foreach ($apiTXNs as $txndata):

                    $transId = $txndata['api_txn_data']['txn_id'];
                    $transdate = $txndata['api_txn_data']['date'];
                    $transrefId = $txndata['api_txn_data']['vendor_refid'];
                    $transamount = $txndata['api_txn_data']['amount'];
                    $transStatus = $statusList[$txndata['api_txn_data']['status']];

                    $current_datetime = date('Y-m-d H:i:s');
                    $current_date = date('Y-m-d');

                    $batch_qry['type'] = "INSERT";
                    $batch_qry['predata'] = "INSERT INTO `api_transactions` (`vendor_id`,`txn_id`,`ref_code`,`amount`,`vendor_status`,`server_status`,`updated_time`,`date`,`hour`) VALUES";
                    $batch_qry['data'][] = "('$vendorId','$transId','$transrefId','$transamount','null','$transStatus','$current_datetime','$search_date','$hour')";

                    if ( ($i % 100) == 0 ): //process batch of 100 query together
                        file_put_contents('/mnt/logs/api_auto_recon.txt', date('Y-m-d H:i:s') . " :: INSERT :: $i :: vendor :: $vendorId :: calling batch query \n", FILE_APPEND | LOCK_EX);
                        $this->run_batch($batch_qry);
                        $batch_qry = array();
                    endif;

                    $i++;

                endforeach;

                if ( !empty($batch_qry) ): // handled cases if last batch consist less then 100 queries
                    file_put_contents('/mnt/logs/api_auto_recon.txt', date('Y-m-d H:i:s') . " :: INSERT :: $i :: vendor :: $vendorId :: calling batch query \n", FILE_APPEND | LOCK_EX);
                    $this->run_batch($batch_qry);
                    $batch_qry = array();
                endif;

                $api_rec_log_in_qry = "INSERT INTO api_rec_log (`vendor_id`,`date`,`hour`,`status`) values('$vendorId','$search_date','$hour','1')";
                $this->Retailer->query($api_rec_log_in_qry);//--- log process complete of vendor for particular date and hour

            endforeach;
            sleep(2);
        endforeach;

        $this->autoRender = false;
    }

    /**
     * This function update the vendor response of txn on hourly basis
     * @param type $prev_day_num : 1= yesterday, 2=day before yesterday ...
     * @param type $vendorId : vendor ID of api vendor in case to process individually
     * @param type $hour : hour in 24 hr format in case to process for single hour
     * @param type $forkflag : default false, change true to run vendor wise process
     */
    function update_api_recon($forkflag = "false", $prev_day_num = 1, $vendorId = null, $hour_num = null) {
        file_put_contents('/mnt/logs/api_auto_recon.txt', date("Y-m-d H:i:s")." called function :: vendor :: ".$vendorId." and param: |$forkflag|$prev_day_num|$vendorId|$hour_num \n" , FILE_APPEND | LOCK_EX);
        if (empty($vendorId)):
            $API_vendor_qry = "SELECT id,shortForm FROM vendors where update_flag=0 and id not in (22,58,149,155)  order by 1 desc ";
        else:
            $API_vendor_qry = "SELECT id,shortForm FROM vendors where update_flag=0  and id='$vendorId' order by 1 desc ";
        endif;

        $hour = (is_null($hour_num) or ($hour_num == "null")) ? date("H") : $hour_num;

        $API_vendorList = $this->Slaves->query($API_vendor_qry);

        $statusList = array('0' => 'pending', '1' => 'success', '2' => 'failure', '3' => 'failure', '4' => 'pending', '5' => 'success'); //---status list

        $search_date = date('Y-m-d', strtotime('-' . $prev_day_num . ' days')); //----- generation date from 'no_of_days_before_yesterday'

        //$_SERVER['DOCUMENT_ROOT'] = "/var/www/html/shops/app/webroot";


        foreach ($API_vendorList as $key => $val):
            $vendorId = $val['vendors']['id'];

            //fork individual process for each vendor if forkflag is true
            if( $forkflag == "true"):
                file_put_contents('/mnt/logs/api_auto_recon.txt', date("Y-m-d H:i:s")." forking process for update :: vendor :: ".$val['vendors']['shortForm']." \n" , FILE_APPEND | LOCK_EX);
                $forkflag_frwd = "false";
                shell_exec("nohup sh  ".$_SERVER['DOCUMENT_ROOT']."/scripts/api_auto_recon.sh $forkflag_frwd $prev_day_num $vendorId $hour  2>&1 > /dev/null & ");
                continue;
            endif;


           // if (in_array($function_name, $obj_funct_list)):

                $flag = $prev_day_num - 1;
                $qry_api_txn = "SELECT * FROM `api_transactions` where date='$search_date' and hour='$hour' and vendor_id='$vendorId'";
                $vendor_txn_list = $this->Retailer->query($qry_api_txn);
                $vendor_txn_ids = array();
                foreach ($vendor_txn_list as $txn_data):
                    $vendor_txn_ids[] = $txn_data['api_transactions']['txn_id'];
                endforeach;

                if(empty($vendor_txn_ids)):
                    continue;
                endif;

                $txn_list_string = implode(",", $vendor_txn_ids);

                // Query to get combine data from vendor_activataion and vendor_messages
                $txn_qry = "SELECT * FROM (SELECT distinct va.id,va.date,(CASE WHEN va.vendor_id = vm.service_vendor_id THEN va.status ELSE '2' END) as status,"
                        . " vm.vendor_refid,va.txn_id,va.amount,vm.service_id, va.vendor_id, vm.service_vendor_id "
                        . "FROM `vendors_activations` as va "
                        . "LEFT JOIN vendors_messages as vm ON (vm.va_tran_id=va.txn_id) "
                        . "WHERE date = '$search_date' AND vm.service_vendor_id=$vendorId and va.hr=$hour and "
                        . "vm.va_tran_id in ($txn_list_string) ) as api_txn_data ";

                $apiTXNs = $this->Slaves->query($txn_qry);

                $i = 1;// Setting initial counter
                $healthcount = 0;
                $batch_qry = array();// Initializing batch query array
                $missmatchedData_serversuccess = $missmatchedData_serverfailure = array();
                foreach ($apiTXNs as $txndata):

                    $transId = $txndata['api_txn_data']['txn_id'];
                    $transdate = $txndata['api_txn_data']['date'];
                    $transrefId = $txndata['api_txn_data']['vendor_refid'];
                    $transamount = $txndata['api_txn_data']['amount'];
                    $transStatuscode = $txndata['api_txn_data']['status'];
                    $transStatus = $statusList[$txndata['api_txn_data']['status']];
                    $transServiceId = $txndata['api_txn_data']['service_id'];
                    $vendor_actId = $txndata['api_txn_data']['id'];
                    $transVendorId = $txndata['api_txn_data']['vendor_id'];

                    $vendordata = $this->Recharge->tranStatus($transId, $vendorId, $transdate, $transrefId);


                    if(empty($vendordata)){
                        $healthcount++;
                    }else{
                        $healthcount = 0;
                    }

                    if($healthcount > 5){
                        break;
                    }
                    $current_datetime = date('Y-m-d H:i:s');
                    $current_date = date('Y-m-d');

                    $batch_qry['type'] = "UPDATE";
                    $batch_qry['predata'] = "UPDATE `api_transactions` SET ";
                    $batch_qry['vendor'] = true;
                    $batch_qry['data'][$transId] = array('vendor_status'=>$vendordata['status'],'server_status'=>$transStatus,'vendor_id'=>$vendorId,'flag'=>0);

                    file_put_contents('/mnt/logs/api_auto_recon_data.txt', date("Y-m-d H:i:s")."::$transId:: vendordata: ".json_encode($vendordata) ."::transstaus: $transStatus, transvendorid $transVendorId, vendorid $vendorId\n" , FILE_APPEND | LOCK_EX);

                    if(strtolower($transStatus) != strtolower($vendordata['status'])){ // action in case of difference
                        if(strtolower($transStatus) == "success" && $vendordata['status'] == "failure" && $transVendorId == $vendorId){
                            $message = "";
                            file_put_contents('/mnt/logs/api_auto_recon_data.txt', date("Y-m-d H:i:s")."::$transId:: vendordata: ".json_encode($vendordata) ."::failing txn here\n" , FILE_APPEND | LOCK_EX);
                			$batch_qry['data'][$transId]['flag'] = 1;
                            $missmatchedData_serversuccess['data'][] = array('transId'=>$transId,'refCode'=>$transrefId,'serverStatus'=>$transStatus,'venderStatus'=>$vendordata['status'],'date'=>$search_date);
                            //$transStatus = "failure";
                        }elseif(strtolower($transStatus) == "failure" && $vendordata['status'] == "success"  && $transVendorId == $vendorId){
                            //$this->Retailer->query("INSERT into `trans_pullback` (`vendors_activations_id`,`vendor_id`,`status`,`timestamp`,`pullback_by`,`pullback_time`,`reported_by`,`date`) values('$vendor_actId','$vendorId','$transStatuscode','$current_datetime','0','0000-00-00 00:00:00','System','$current_date')");
                            $trans_pullbackdata = array('vendors_activations_id'=>$vendor_actId,
                                                        'vendor_id'=>$vendorId,
                                                        'status'=>$transStatuscode,
                                                        'timestamp'=>$current_datetime,
                                                        'pullback_by'=>'0',
                                                        'pullback_time'=>'0000-00-00 00:00:00',
                                                        'reported_by'=>'System',
                                                        'date'=>$current_date);
                            $this->General->manage_transPullback($trans_pullbackdata);
                            file_put_contents('/mnt/logs/api_auto_recon_data.txt', date("Y-m-d H:i:s")."::$transId:: vendordata: ".json_encode($vendordata) ."::wrong failure by system\n" , FILE_APPEND | LOCK_EX);

                            $missmatchedData_serverfailure['data'][] = array('transId'=>$transId,'refCode'=>$transrefId,'serverStatus'=>$transStatus,'venderStatus'=>$vendordata['status'],'date'=>$search_date);
                        }

                    }

                    //$batch_qry['data'][$transId] = array('vendor_status'=>$vendordata['status'],'server_status'=>$transStatus,'vendor_id'=>$vendorId);
                    if ( ($i % 5) == 0 ): //process queries in batch
                        file_put_contents('/mnt/logs/api_auto_recon.txt', date('Y-m-d H:i:s') . " :: UPDATE :: $i :: vendor :: $vendorId :: calling batch query ".  json_encode($batch_qry)." \n", FILE_APPEND | LOCK_EX);
                        $this->run_batch($batch_qry);
                        $batch_qry = array();
                    endif;

                    usleep(1000000/2);//sleep to set gap between to request
                    $i++;

                endforeach;

                if ( !empty($batch_qry) ): // handled cases if last batch consist less then 100 queries
                    file_put_contents('/mnt/logs/api_auto_recon.txt', date('Y-m-d H:i:s') . " :: UPDATE :: $i :: vendor :: $vendorId :: calling batch query ".  json_encode($batch_qry)." \n", FILE_APPEND | LOCK_EX);
                    $this->run_batch($batch_qry);
                    $batch_qry = array();
                endif;

                //send mail of txn success at server and fail at vendor
                if(isset($missmatchedData_serversuccess['data']) && !empty($missmatchedData_serversuccess['data'])):
                     $missmatchedData_serversuccess['colhead'] = array_keys($missmatchedData_serversuccess['data'][0]);
                     $missmatchedData_serversuccess['colval'] = $missmatchedData_serversuccess['data'];
                     $missmatchedData_serversuccess['subject'] = "Auto reconcilation report for server success : ".$val['vendors']['shortForm'];
                     $this->sendFormatedmail($missmatchedData_serversuccess);
                 endif;

                 //send mail of txn fail at server and success at vendor
                 if(isset($missmatchedData_serverfailure['data']) && !empty($missmatchedData_serverfailure['data'])):
                     $missmatchedData_serverfailure['colhead'] = array_keys($missmatchedData_serverfailure['data'][0]);
                     $missmatchedData_serverfailure['colval'] = $missmatchedData_serverfailure['data'];
                     $missmatchedData_serverfailure['subject'] = "Auto reconcilation report for server failure : ".$val['vendors']['shortForm'];
                     $this->sendFormatedmail($missmatchedData_serverfailure);
                 endif;

            //endif;

        endforeach;

        $this->autoRender = false;
    }

    /**
     * This will clear all txn those are having vendor status as error
     * @param type $prev_day_num : default 1 i.e yesterday
     * @param type $batch_size : number of records to check on each execuution
     */
    function update_api_recon_clear_pending_in_batch($prev_day_num=1,$batch_size=100){
        $search_date = date('Y-m-d', strtotime('-'.$prev_day_num.' days'));

        $date_condition = " date='$search_date'";
        $limit_qry = ($batch_size < 0) ? "" : " limit $batch_size";

        $date_range = explode(",",$prev_day_num);

        $statusList = array('0' => 'pending', '1' => 'success', '2' => 'failure', '3' => 'failure', '4' => 'success', '5' => 'success');//---status list
        if(count($date_range)>1){
            $start_date = date('Y-m-d', strtotime('-'.$date_range[0].' days'));
            $end_date = date('Y-m-d', strtotime('-'.$date_range[1].' days'));
            $date_condition = "date >= '$start_date' and date <= '$end_date'";
        }

        $api_txn_qry = "SELECT * FROM `api_transactions` LEFT JOIN `vendors` ON vendor_id = vendors.id WHERE $date_condition and (vendor_status not in ('success','failure','incomplete') or server_status not in ('success','failure','incomplete')) order by rand() $limit_qry";
        //$vendor_txn_list = $this->Retailer->query($api_txn_qry);

        //$qry_api_txn = "SELECT * FROM `api_transactions` where date='$search_date' and status='error'";
        $vendor_txn_list = $this->Slaves->query($api_txn_qry);
        $vendor_txn_ids = array();
        $vendor_list_arr = array();
        $vendor_txn_combination = array();
        foreach ($vendor_txn_list as $txn_data):
            $vendor_txn_ids[$txn_data['api_transactions']['txn_id']] = $txn_data['vendors']['id'];
            $vendor_list_arr[$txn_data['vendors']['id']] = $txn_data['vendors']['shortForm'];
            $vendor_txn_combination[] = $txn_data['api_transactions']['txn_id']."_".$txn_data['vendors']['id'];
        endforeach;

        if(empty($vendor_txn_ids)):
            echo "no pending data";
            exit();
        endif;
        $txn_list_string = implode("','", array_keys($vendor_txn_ids));
        $vendor_txn_combination_string = implode("','",$vendor_txn_combination);
        // Query to get combine data from vendor_activataion and vendor_messages
        $txn_qry = "SELECT * FROM (SELECT distinct va.id,va.date,(CASE WHEN va.vendor_id = vm.service_vendor_id THEN va.status ELSE '2' END) as status,"
                . " vm.vendor_refid,va.txn_id,va.amount,vm.service_id,va.vendor_id, vm.service_vendor_id, CONCAT(vm.va_tran_id,'_',vm.service_vendor_id) as combo_id "
                . "FROM `vendors_activations` as va "
                . "LEFT JOIN vendors_messages as vm ON (vm.va_tran_id=va.txn_id) "
                . "WHERE $date_condition AND "
                . "vm.va_tran_id in ('$txn_list_string') ) as api_txn_data where combo_id in ('$vendor_txn_combination_string')";

        $apiTXNs = $this->Slaves->query($txn_qry);

        $healthcount = 0;
        $batch_qry = array();// Initializing batch query array
        $missmatchedData_serversuccess = $missmatchedData_serverfailure = array();
        $unhealthy_vendor_array = array();


        //$_SERVER['DOCUMENT_ROOT'] = "/var/www/html/shops/app/webroot";

        foreach ($apiTXNs as $txndata){
            $transId = $txndata['api_txn_data']['txn_id'];
            $vendorId = $vendor_txn_ids[$transId];
            $shortForm = $vendor_list_arr[$vendorId];

            $function_name = $shortForm . "TranStatus";


            //if (in_array($function_name, $obj_funct_list)):
                $transServiceId = $txndata['api_txn_data']['service_id'];
                $transdate = $txndata['api_txn_data']['date'];
                $transrefId = $txndata['api_txn_data']['vendor_refid'];
                $transamount = $txndata['api_txn_data']['amount'];
                $transStatus = $statusList[$txndata['api_txn_data']['status']];
                $transVendorId = $txndata['api_txn_data']['vendor_id'];
                $transId = $txndata['api_txn_data']['txn_id'];
                $transStatuscode = $txndata['api_txn_data']['status'];
                $vendor_actId = $txndata['api_txn_data']['id'];

                $vendordata = $this->Recharge->tranStatus($transId,$transVendorId,$transdate,$transrefId);

                file_put_contents('/mnt/logs/clear_api_auto_recon.txt', date('Y-m-d H:i:s') . " txnId $transId :: ".  json_encode($vendordata)." \n", FILE_APPEND | LOCK_EX);
                $current_datetime = date('Y-m-d H:i:s');
                $current_date = date('Y-m-d');

                $batch_qry['type'] = "UPDATE";
                $batch_qry['predata'] = "UPDATE `api_transactions` SET ";
                $batch_qry['vendor'] = true;

                $batch_qry['data'][$transId] = array('vendor_status'=>$vendordata['status'],'server_status'=>$transStatus,'vendor_id'=>$vendorId,'flag'=>0);

                file_put_contents('/mnt/logs/api_auto_recon_data.txt', date("Y-m-d H:i:s")."::$transId:: vendordata: ".json_encode($vendordata) ."::transstaus: $transStatus, transvendorid $transVendorId, vendorid $vendorId\n" , FILE_APPEND | LOCK_EX);

                if(strtolower($transStatus) != strtolower($vendordata['status'])){ // action in case of difference

                    if(strtolower($transStatus) == "success" && $vendordata['status'] == "failure" && $transVendorId == $vendorId){
                        file_put_contents('/mnt/logs/api_auto_recon_data.txt', date("Y-m-d H:i:s")."::$transId:: vendordata: ".json_encode($vendordata) ."::failing txn here\n" , FILE_APPEND | LOCK_EX);

                    	$message = "";
                        $batch_qry['data'][$transId]['flag'] = 1;
                        $missmatchedData_serversuccess['data'][] = array('transId'=>$transId,'refCode'=>$transrefId,'serverStatus'=>'failure','venderStatus'=>$vendordata['status'],'date'=>$search_date);
                        //$transStatus = "failure";
                    }elseif(strtolower($transStatus) == "failure" && $vendordata['status'] == "success"){
                        $trans_pullbackdata = array('vendors_activations_id'=>$vendor_actId,
                                                        'vendor_id'=>$vendorId,
                                                        'status'=>$transStatuscode,
                                                        'timestamp'=>$current_datetime,
                                                        'pullback_by'=>'0',
                                                        'pullback_time'=>'0000-00-00 00:00:00',
                                                        'reported_by'=>'System',
                                                        'date'=>$current_date);
                        $this->General->manage_transPullback($trans_pullbackdata);
                        file_put_contents('/mnt/logs/api_auto_recon_data.txt', date("Y-m-d H:i:s")."::$transId:: vendordata: ".json_encode($vendordata) ."::wrong failure by system\n" , FILE_APPEND | LOCK_EX);

                        $missmatchedData_serverfailure['data'][] = array('transId'=>$transId,'refCode'=>$transrefId,'serverStatus'=>$transStatus,'venderStatus'=>$vendordata['status'],'date'=>$search_date);
                    }

                }

                //$batch_qry['data'][$transId] = array('vendor_status'=>$vendordata['status'],'server_status'=>$transStatus,'vendor_id'=>$vendorId);

                if ( ($i % 2) == 0 ): //process queries in batch
                    file_put_contents('/mnt/logs/clear_api_auto_recon.txt', date('Y-m-d H:i:s') . " :: 5 min :: UPDATE :: $i :: vendor :: $vendorId :: calling batch query ".  json_encode($batch_qry)." \n", FILE_APPEND | LOCK_EX);
                    $this->run_batch($batch_qry);
                    $batch_qry = array();
                endif;

                usleep(1500000);//sleep to set gap between to request
                $i++;
            //endif;
        }

        if ( !empty($batch_qry) ): // handled cases if last batch consist less then 100 queries
            file_put_contents('/mnt/logs/clear_api_auto_recon.txt', date('Y-m-d H:i:s') . "  :: 5 min :: UPDATE :: $i :: vendor :: $vendorId :: calling batch query ".  json_encode($batch_qry)." \n", FILE_APPEND | LOCK_EX);
            $this->run_batch($batch_qry);
            $batch_qry = array();
        endif;

        //send mail of txn success at server and fail at vendor
        if(isset($missmatchedData_serversuccess['data']) && !empty($missmatchedData_serversuccess['data'])):
             $missmatchedData_serversuccess['colhead'] = array_keys($missmatchedData_serversuccess['data'][0]);
             $missmatchedData_serversuccess['colval'] = $missmatchedData_serversuccess['data'];
             $missmatchedData_serversuccess['subject'] = "Auto reconcilation report for server success : ".$shortForm;
             $this->sendFormatedmail($missmatchedData_serversuccess);
         endif;

         //send mail of txn fail at server and success at vendor
         if(isset($missmatchedData_serverfailure['data']) && !empty($missmatchedData_serverfailure['data'])):
             $missmatchedData_serverfailure['colhead'] = array_keys($missmatchedData_serverfailure['data'][0]);
             $missmatchedData_serverfailure['colval'] = $missmatchedData_serverfailure['data'];
             $missmatchedData_serverfailure['subject'] = "Auto reconcilation report for server failure : ".$shortForm;
             $this->sendFormatedmail($missmatchedData_serverfailure);
         endif;

        $this->autoRender = false;
    }

    /*
     * API vendor Auto reconcilation
     * should run for no_of_days_before_yesterday t+1, t+2
     */
    /*function auto_reconcilation($vendorId=null,$no_of_days_before_yesterday=2,$limitstart=null,$limitend=NULL){

        //get list of api vendor if not provided as parameter
        if(empty($vendorId)){
            $API_vendor_qry = "SELECT id,shortForm FROM vendors where (machine_id is null or machine_id < 1) and id in (5,8,19,34,35,36,48,56,58,62)";
            //$API_vendor_qry = "SELECT id,shortForm FROM vendors where (machine_id is null or machine_id < 1) and id in (62)";
            //exit();
        }else{
            $API_vendor_qry = "SELECT id,shortForm FROM vendors where (machine_id is null or machine_id < 1) and id='$vendorId'";
        }

        $API_vendorList = $this->Slaves->query($API_vendor_qry);


        //---status list
        $statusList = array('0'=>'pending','1'=>'success','2'=>'failure','3'=>'failure','4'=>'success','5'=>'success');

        //----- generation date from 'no_of_days_before_yesterday'
        $search_date = date('Y-m-d', strtotime('-'.$no_of_days_before_yesterday.' days'));

        foreach($API_vendorList as $key=>$val):

            $vendorId = $val['vendors']['id'];
            $function_name = $val['vendors']['shortForm']."TranStatus";

            if(in_array($function_name,$obj_funct_list)){
                 if(is_null($limitstart) || is_null($limitend)){
                     file_put_contents('/mnt/logs/api_auto_recon.txt', " forking process for :: vendor :: ".$val['vendors']['shortForm']." \n" , FILE_APPEND | LOCK_EX);
                     $this->auto_recon_process_fork($vendorId, $search_date, $no_of_days_before_yesterday, $limitstart,$limitend);
                     file_put_contents('/mnt/logs/api_auto_recon.txt', " after forking process for :: vendor :: ".$val['vendors']['shortForm']."  :: $vendorId : $search_date : $no_of_days_before_yesterday : $limitstart : $limitend \n" , FILE_APPEND | LOCK_EX);
                     continue;
                 }

                 file_put_contents('/mnt/logs/api_auto_recon.txt', " Starting Recon chk for :: vendor :: ".$val['vendors']['shortForm']." \n" , FILE_APPEND | LOCK_EX);
                 //$txn_qry = "SELECT * FROM `vendors_activations` where vendor_id='".$val['vendors']['id']."' and date='$search_date'";

                 $txn_qry = "SELECT * FROM (SELECT distinct va.id,va.date,(CASE WHEN va.vendor_id = vm.service_vendor_id THEN va.status ELSE '2' END) as status,"
                        . " vm.vendor_refid,va.ref_code,va.amount "
                        . "FROM `vendors_activations` as va "
                        . "LEFT JOIN vendors_messages as vm ON (vm.va_tran_id=va.txn_id) "
                        . "WHERE date = '$search_date' AND vm.service_vendor_id=$vendorId) as api_txn_data "
                        . "LIMIT ".$limitstart." , ".$limitend;

                 $apiTXNs = $this->Slaves->query($txn_qry);
                 $i=1;

                 $batch_qry = array();
                 $missmatchedData = array();

                 foreach($apiTXNs as $txndata):

                    $transId = $txndata['api_txn_data']['ref_code'];
                    $transdate = $txndata['api_txn_data']['date'];
                    $transrefId = $txndata['api_txn_data']['vendor_refid'];
                    $transamount = $txndata['api_txn_data']['amount'];
                    $transStatus = $statusList[$txndata['api_txn_data']['status']];

                    $vendordata = $this->Recharge->tranStatus($transId,$transdate,$transrefId);

                    $current_datetime = date('Y-m-d H:i:s');
                    $current_date = date('Y-m-d');

                    if($no_of_days_before_yesterday == 2){
                        $batch_qry['type'] = "INSERT";
                        $batch_qry['predata'] = "INSERT INTO `api_transactions` (`vendor_id`,`txn_id`,`ref_code`,`amount`,`vendor_status`,`server_status`,`updated_time`,`date`) VALUES";
                        $batch_qry['data'][] = "('$vendorId','$transId','$transrefId','$transamount','".$vendordata['status']."','$transStatus','$current_datetime','$search_date')";
                    }elseif($no_of_days_before_yesterday > 2){
                        $batch_qry['type'] = "UPDATE";
                        $batch_qry['predata'] = "UPDATE `api_transactions` SET ";
                        $batch_qry['data'][$transId] = array('vendor_status'=>$vendordata['status'],'server_status'=>$transStatus);
                    }

                    //if($statusList[$transStatus] != strtolower($vendordata['status'])){ // action in case of difference
                    if(strtolower($transStatus) != strtolower($vendordata['status'])){ // action in case of difference
                        $missmatchedData['data'][] = array('transId'=>$transId,'refCode'=>$transrefId,'serverStatus'=>$transStatus,'venderStatus'=>$vendordata['status'],'date'=>$search_date);
                    }

                    if(($i % 25) == 0) {//process batch of 100 query together
                        file_put_contents('/mnt/logs/api_auto_recon.txt', date('Y-m-d H:i:s')." :: $i :: vendor :: $vendorId :: calling batch query \n" , FILE_APPEND | LOCK_EX);
                        $this->run_batch($batch_qry);
                        $batch_qry = array();
                    }
                    $i++;

                 endforeach;

                 if(!empty($batch_qry)){// handled cases if last batch consist less then 100 queries
                     file_put_contents('/mnt/logs/api_auto_recon.txt', date('Y-m-d H:i:s')." :: $i :: vendor :: $vendorId :: calling batch query \n" , FILE_APPEND | LOCK_EX);
                     $this->run_batch($batch_qry);
                     $batch_qry = array();
                 }

                 if(isset($missmatchedData['data']) && !empty($missmatchedData['data'])){
                     $missmatchedData['colhead'] = array_keys($missmatchedData['data'][0]);
                     $missmatchedData['colval'] = $missmatchedData['data'];
                     $missmatchedData['subject'] = "Auto reconcilation report for ".$val['vendors']['shortForm'];
                     $this->sendFormatedmail($missmatchedData);
                 }
            }
        endforeach;
        $this->autoRender = false;
    } */

    /*
     * send html formated mail
     */
    private function sendFormatedmail($data=array()){
        if(!empty($data)){
            $colhead = $datarow = $reportdata = "";

            foreach($data['colhead'] as $colh):
                $colhead .="<th>".$colh."</th>";
            endforeach;

            $mail_report = "<table border='1' >";
            $mail_report .= "<tr>$colhead</tr>";

            foreach($data['colval'] as $colv):
                $reportdata .= "<tr>";
                foreach ($colv as $row):
                    $reportdata .= "<td>".$row."</td>";
                endforeach;
                $reportdata .= "</tr>";
            endforeach;

            $mail_report .= $reportdata;
            $mail_report .= "</table>";

            $this->General->sendMails($data['subject'],$mail_report,array('nandan.rana@pay1.in','cc.support@pay1.in','naziya.khan@pay1.in'),'mail');
        }
    }

    /*
     * fork multiple process for auto_reconcilation limit
     */
   /* function auto_recon_process_fork($vendorId=null,$search_date,$no_of_days_before_yesterday=2,$limitstart=null,$limitend=NULL){
        //$total_cnt_qry = "SELECT count(1) as total FROM `vendors_activations` where vendor_id='".$vendorId."' and date='$search_date'";
        file_put_contents('/mnt/logs/api_auto_recon.txt', " inside auto_recon_process_fork  \n" , FILE_APPEND | LOCK_EX);
        $total_cnt_qry = "SELECT count(1) as total FROM (SELECT distinct va.id,va.date,(CASE WHEN va.vendor_id = vm.service_vendor_id THEN 1 ELSE 2 END) as status, vm.vendor_refid "
                         . "FROM `vendors_activations` as va "
                         . "LEFT JOIN vendors_messages as vm ON (vm.va_tran_id=va.txn_id) "
                         . "WHERE date = '$search_date' AND vm.service_vendor_id=$vendorId) as api_txn_data";

        $total_cnts = $this->Slaves->query($total_cnt_qry);
        $totalrecord = isset($total_cnts[0][0]['total']) ? $total_cnts[0][0]['total'] : 0;
        $limitgap = 1000;
        $attempt = 0;
        while($totalrecord > 0):
            $limitstart = $limitgap * $attempt;
            $limitend = $limitgap;
            $attempt ++;
            $totalrecord -= 1000;
            //echo "<br>IN fork method : ".$vendorId."/".$no_of_days_before_yesterday."/".$limitstart."/".$limitend;
            //shell_exec("nohup lynx http://localhost/crons/auto_reconcilation/".$vendorId."/".$no_of_days_before_yesterday."/".$limitstart."/".$limitend);
            file_put_contents('/mnt/logs/api_auto_recon.txt', " forking  api_auto_recon.sh with :: $vendorId :: $no_of_days_before_yesterday :: $limitstart :: $limitend \n" , FILE_APPEND | LOCK_EX);
            shell_exec("nohup sh  ".$_SERVER['DOCUMENT_ROOT']."/scripts/api_auto_recon.sh $vendorId $no_of_days_before_yesterday $limitstart $limitend");
        endwhile;
    }*/

    /*
     * Execute query in batch for auto recon
     */
    private function run_batch($querydata = array()){
        $mainQry = "";
        $curTimestamp = date("Y-m-d H:i:s");
        if($querydata['type']){
            switch (strtoupper($querydata['type'])):
                case 'INSERT':
                    $mainQry .= $querydata['predata'];
                    $mainQry .= implode(",", $querydata['data']);
                    break;
                case 'UPDATE':
                    $mainQry .= $querydata['predata'];
                    $mainQry .= (isset($querydata['vendor']) && $querydata['vendor'])? " vendor_status=(CASE CONCAT(txn_id,'_',vendor_id) ":" vendor_status=(CASE txn_id ";

                    foreach ($querydata['data'] as $txnId => $txndata):
                        $txnId = isset($txndata['vendor_id'])? $txnId."_".$txndata['vendor_id']:$txnId;
                        $mainQry .= "WHEN '$txnId' THEN '".$txndata['vendor_status']."' ";
                    endforeach;

                    $mainQry .= " END) ,";
                    $mainQry .= (isset($querydata['vendor']) && $querydata['vendor'])? " server_status=(CASE CONCAT(txn_id,'_',vendor_id) ":" server_status=(CASE txn_id ";

                    foreach ($querydata['data'] as $txnId => $txndata):
                        $txnId = isset($txndata['vendor_id'])? $txnId."_".$txndata['vendor_id']:$txnId;
                        $mainQry .= "WHEN '$txnId' THEN '".$txndata['server_status']."' ";
                    endforeach;

                    $mainQry .= " END) ,";
                    $mainQry .= (isset($querydata['vendor']) && $querydata['vendor'])? " flag=(CASE CONCAT(txn_id,'_',vendor_id) ":" flag=(CASE txn_id ";

                    foreach ($querydata['data'] as $txnId => $txndata):
                        $txnId = isset($txndata['vendor_id'])? $txnId."_".$txndata['vendor_id']:$txnId;
                        $mainQry .= "WHEN '$txnId' THEN '".$txndata['flag']."' ";
                    endforeach;

                    $mainQry .= " END) , updated_time='$curTimestamp' ";
                    $mainQry .= "WHERE txn_id in (".implode(",",array_keys($querydata['data'])).")";

                    break;
            endswitch;
            if(!empty($mainQry)):
                    file_put_contents('/mnt/logs/api_auto_recon.txt', "\n Query :: $mainQry  \n" , FILE_APPEND | LOCK_EX);
                    $this->Retailer->query($mainQry);
            endif;
        }
    }

	/*function updateRetailersPin(){

		$this->autoRender = false;


		$retailersRecord = $this->Slaves->query("
                                            SELECT retailer_id,r.mobile,sum(rl.sale) as total_sale, sum(rl.ussd_sale + rl.sms_sale) as ussd_sms_sale, (sum(rl.sale) - (sum(rl.ussd_sale + rl.sms_sale))) as sale_diff FROM `retailers_logs` as rl inner join retailers r on rl.retailer_id = r.id where date >= '2015-12-01' and date <= '2015-12-22' group by 1 having ussd_sms_sale > 0 and (((total_sale - ussd_sms_sale)/total_sale) < 0.1)");

		$date = date('Y-m-d');

		if($date == '2015-12-23'){

		foreach ($retailersRecord as $val){

			$password = $this->General->generatePassword(4);

			echo $password;

			echo "<br/>";



			$crypt = $this->Auth->password($password);

			echo $crypt;
			die;

			$this->Retailer->query("update users set password = '".$crypt."' where mobile = '".$val['r']['mobile']."'");

			if($val[0]['sale_diff']>0){
			$message = 	"Aapke App Ki Security ke liye appka Password reset kiya gaya hai.Agar aapko PAY1 App mei login problem ho raha hain to app 'GENERATE NEW PIN' se naya password chun sakte hain.\n PAY1";
			$this->General->sendMessage($val['r']['mobile'], $message, 'shops');
			}
			$this->General->logData("/mnt/logs/updatepin.txt",date("Y-m-d H:i:s")."Mob Number => ".$val['r']['mobile']);

			//$message = 	"You can login to Pay1 Channel Partner Android App with pin: $password. Kindly, change your pin from the app.";

			//$this->General->sendMessage($val['r']['mobile'], $message, 'shops');

		///}


	}
	}


	echo "Password Updated Successfully!!!";
	}*/




	function sendSupplierTargets(){
		$query="SELECT devices_data.vendor_id, devices_data.opr_id, devices_data.inv_supplier_id,inv_planning_sheet.max_sale_capacity,sum(devices_data.sale) as totalsale,sum(if(devices_data.device_id = devices_data.par_bal AND devices_data.block = '0' AND devices_data.stop_flag=0 AND devices_data.balance > 10 AND devices_data.recharge_flag = 1 AND devices_data.active_flag = 1, 1, 0)) as sims, inv_modem_planning_sheet.target
FROM inv_planning_sheet
INNER JOIN devices_data ON (devices_data.supplier_operator_id = inv_planning_sheet.supplier_operator_id)
INNER JOIN inv_modem_planning_sheet ON (inv_modem_planning_sheet.supplier_operator_id = devices_data.supplier_operator_id AND devices_data.vendor_id =inv_modem_planning_sheet.vendor_id)
WHERE devices_data.sync_date = '".date('Y-m-d')."' group by devices_data.vendor_id,devices_data.supplier_operator_id having (sims > 0) order by vendor_id";
		$data = $this->Retailer->query($query);

		$vendorData = array();
		foreach($data as $dt){
			$vendor_id = $dt['devices_data']['vendor_id'];
			$opr_id = $dt['devices_data']['opr_id'];
			$weight = ($dt['inv_modem_planning_sheet']['target'] - $dt['0']['totalsale'])/$dt['0']['sims'];
			$vendorData[$vendor_id][$opr_id][$weight] =  $dt['devices_data']['inv_supplier_id'];
		}

		foreach($vendorData as $vendor_id => $vdata){

			$data_to_send = array();
			foreach($vdata as $opr_id => $opdata){
				krsort($opdata);
				$data_to_send[$opr_id] = array_values($opdata);
			}
			$query = "query=settarget&data=".urlencode(json_encode($data_to_send));
			$this->Shop->modemRequest($query,$vendor_id);
		}

		$this->autoRender = false;

	}

        function clean_olddata($date=null){
            if(empty($date))$date = date('Y-m-d');
            $date = date('Y-m-d', strtotime($date . '-90 days'));
            $rowcount_log = 1;
            $rowcount = 1;

            try{
                $datasource = $this->Retailer->getDataSource();
                $datasource->begin();

                $vendors_activations_cnt_data = $this->Slaves->query("SELECT count(1) as cnt FROM vendors_activations WHERE date = DATE_SUB(curdate(),INTERVAL 90 DAY)");
                $vendors_activations_logs_cnt_data = $this->Slaves->query("SELECT count(1) as cnt FROM vendors_activations_logs WHERE date = DATE_SUB(curdate(),INTERVAL 90 DAY)");
                if($vendors_activations_cnt_data[0][0]['cnt'] > $vendors_activations_logs_cnt_data[0][0]['cnt']){
                    while($rowcount_log > 0){
                        $qry = "delete from vendors_activations_logs where date = '$date' order by id asc limit 2000";
                        $result = $this->Retailer->query($qry);
                        $rowcount_log = $this->Retailer->getAffectedRows();
                    }

                    $qry2 = "INSERT INTO vendors_activations_logs (SELECT * FROM vendors_activations WHERE date = '$date' order by id asc)";
                    if(!$this->Retailer->query($qry2)){
                        throw new Exception();
                    }
                }

                $vendor_a_count = $vendors_activations_cnt_data;
                $vendor_a_l_count = $vendors_activations_logs_cnt_data;

                if($vendor_a_count[0][0]['cnt'] > 0) {
                    if($vendor_a_count[0][0]['cnt'] == $vendor_a_l_count[0][0]['cnt']) {
                        while($rowcount > 0){
                            $query = "delete from vendors_activations where date = '$date' order by id asc limit 2000";
                            $res = $this->Retailer->query($query);
                            $rowcount = $this->Retailer->getAffectedRows();
                        }
                    }
                }
                if(!$datasource->commit()){
                    $this->General->sendMails("cleaning vendors_activation status : ".$date, "Some issue occured while cleaning in datasource val : ".  json_encode($datasource), array('nandan.rana@pay1.in'), 'mail');
                }
                $this->General->sendMails("cleaning vendors_activation status : ".$date, " cleaned table successfully. datasource val : ".  json_encode($datasource), array('nandan.rana@pay1.in'), 'mail');
            } catch (Exception $ex) {

                if(!$datasource->rollback()){
                    $this->General->sendMails("cleaning vendors_activation status : ".$date, "(rollback) Some issue occured while cleaning in datasource val : ".  json_encode($datasource), array('nandan.rana@pay1.in'), 'mail');
                }
                $this->General->sendMails("cleaning vendors_activation status : ".$date, "(rollback) cannot able to clean table. exception val : ".  json_encode($ex), array('nandan.rana@pay1.in'), 'mail');
            }
            $this->autoRender = false;
        }

	/*function endRetailerTrial(){
		$trial_period = $this->General->findVar("trial_period");

		$retailers = $this->User->query("select id, datediff(NOW(), created) as days from retailers
				where trial_flag = 1");

		foreach($retailers as $retailer){
			if($retailer['0']['days'] > $trial_period){
				$this->User->query("update retailers
						set trial_flag = 2,
						modified = '".date('Y-m-d H:i:s')."'
						where id = ".$retailer['retailers']['id']);
			}
		}
		echo "Done";
		$this->autoRender = false;
	}*/



/*function updateAreaIDUserProfile1(){
		App::Import('Model', 'Retailer');
        $ret = new Retailer();
        $data = $ret->query("SELECT * FROM (SELECT id,user_id,latitude,longitude from user_profile where latitude > 0 AND user_id in (
        SELECT retailers.user_id  FROM `unverified_retailers` as ret inner join retailers ON (retailers.id = ret.retailer_id) inner join locator_area as la ON (la.id = ret.area_id) inner join locator_city as lc ON (lc.id = la.city_id) inner join locator_state as ls ON (ls.id = lc.state_id) WHERE  (BINARY substr(la.name,1,1) = BINARY lower(substr(la.name,1,1)) OR BINARY substr(lc.name,1,1) = BINARY lower(substr(lc.name,1,1)) OR BINARY substr(ls.name,1,1) = BINARY lower(substr(ls.name,1,1)) OR ls.name like '%,%' OR la.name like '%,%' OR lc.name like '%,%')
        ) order by updated desc) as t group by t.user_id");
        //$data = $ret->query("select t.id,t.user_id,t.latitude,t.longitude from (select * from  `user_profile` WHERE area_id > 0 AND latitude >0 AND updated < '2016-04-13 00:00:00' order by updated desc) as t group by t.user_id");

	foreach($data as $dt){
			$loc_data['state_id'] = 0;
			$loc_data['city_id'] = 0;
			$loc_data['area_id'] = 0;
			$loc_data = false;
			//$loc_data = $this->Shop->getMemcache("area_".round($dt['t']['longitude'],3)."_".round($dt['t']['latitude'],3));
			if($loc_data === false){
				sleep(1);

				$loc_data = $this->General->getAreaByLatLong($dt['t']['longitude'],$dt['t']['latitude']);
				if(!empty($loc_data['state_name']))$loc_data['state_id'] = $this->General->stateInsert($loc_data['state_name']);
				if(!empty($loc_data['city_name']))$loc_data['city_id'] = $this->General->cityInsert($loc_data['city_name'],$loc_data['state_id']);
				if(!empty($loc_data['area_name']))$loc_data['area_id'] = $this->General->areaInsert($loc_data['area_name'],$loc_data['city_id']);
				$this->Shop->setMemcache("area_".round($dt['t']['longitude'],3)."_".round($dt['t']['latitude'],3),$loc_data,7*24*60*60);
			}

			if(!empty($loc_data['area_id'])){
				if(!$ret->query("UPDATE `user_profile` SET area_id = ".$loc_data['area_id']." WHERE id = ". $dt['t']['id'])){
					$ret = new Retailer();
				}
				$this->General->logData("user_profile.txt","updated area_id ".$loc_data['area_id']." of ". $dt['t']['id']);
				//echo "\nupdated area_id ".$loc_data['area_id']." of ". $dt['t']['id'];
			}
		}
        echo "Done";
		$this->autoRender = false;
	}*/


        function updatePincode(){

            $data = $this->Retailer->query("SELECT locator_area.*,locator_city.name,locator_state.name FROM `locator_area` left join locator_city on (locator_city.id = city_id) left join locator_state on (locator_state.id = state_id) WHERE pincode !=0 AND locator_area.toshow = 1 limit 100");
            foreach($data as $dt){
                $address = $dt['locator_area']['name'] .", " .$dt['locator_city']['name'] . ", ". $dt['locator_state']['name'];
                $loc_data = $this->General->getLatLongByArea($address);

                $address .= ", " . $dt['locator_area']['pincode']. ", " . $dt['locator_area']['lat']. ", " . $dt['locator_area']['long'];

                if(empty($loc_data['pin_code'])){
                    $loc_data = $this->General->getAreaByLatLong($loc_data['lng'],$loc_data['lat']);
                    $loc_data['pin_code'] = $loc_data['pincode'];
                }
                if(!empty($loc_data) && $loc_data['pin_code'] != $dt['locator_area']['pincode']){
                    //$this->Retailer->query("UPDATE `locator_area` SET pincode = '".$loc_data['pin_code']."',lat='".$loc_data['lat']."',`long`='".$loc_data['lng']."' WHERE id = ".$dt['locator_area']['id']);
                    echo "<br/> Data from google api :: ".json_encode($loc_data)." of $address";
                }
            }
            $this->autoRender = false;
        }

    function updateAreaIDUserProfile($search_date=null){
		if(empty($search_date))$search_date = date('Y-m-d', strtotime('-1 days'));
		App::Import('Model', 'Retailer');
        $ret = new Retailer();
		$data = $ret->query("SELECT * FROM  `user_profile` WHERE date =  '$search_date' AND longitude != 0 AND latitude != 0 AND area_id = 0");
		foreach($data as $dt){
		    $loc_data = false;
        		/*$loc_data = $ret->query("select distinct area_id from user_profile where substr(latitude,1,7) = '".round($dt['user_profile']['latitude'],4)."' and substr(longitude,1,7)='".round($dt['user_profile']['longitude'],4)."' and area_id != 0 order by id desc limit 1");

        		if(empty($loc_data)){
        		    $loc_data = $ret->query("select distinct area_id from user_profile where substr(latitude,1,6) = '".round($dt['user_profile']['latitude'],3)."' and substr(longitude,1,6)='".round($dt['user_profile']['longitude'],3)."' and area_id != 0 order by id desc limit 1");
        		}*/

		        if(!empty($loc_data)){
		            $loc_data['area_id'] = $loc_data['0']['user_profile']['area_id'];
		            echo "<br/> locator_area area_id :: ".$loc_data['area_id']." of ". $dt['user_profile']['id'];
		        }
		        else {
		                //$loc_data = $ret->query("SELECT distinct area_id FROM user_profile  WHERE `latitude` like '".round($dt['user_profile']['latitude'],3)."%' AND `longitude` like '".round($dt['user_profile']['longitude'],3)."%' limit 1");

        			$loc_data = $this->Shop->getMemcache("area1_".round($dt['user_profile']['longitude'],3)."_".round($dt['user_profile']['latitude'],3));
        			if($loc_data === false){
        				sleep(1);
        				$loc_data = $this->General->getAreaByLatLong($dt['user_profile']['longitude'],$dt['user_profile']['latitude']);
        				if(!empty($loc_data['state_name']))$loc_data['state_id'] = $this->General->stateInsert($loc_data['state_name']);
        				if(!empty($loc_data['city_name']))$loc_data['city_id'] = $this->General->cityInsert($loc_data['city_name'],$loc_data['state_id']);
        				if(!empty($loc_data['area_name']))$loc_data['area_id'] = $this->General->areaInsert($loc_data['area_name'],$loc_data['city_id'],$dt['user_profile']['latitude'],$dt['user_profile']['longitude'],$loc_data['pincode']);
        				$this->Shop->setMemcache("area1_".round($dt['user_profile']['longitude'],3)."_".round($dt['user_profile']['latitude'],3),$loc_data,7*24*60*60);
        				echo "<br/> area_id from google api :: ".$loc_data['area_id']." of ". $dt['user_profile']['id'];
        			}
		        }
			if(!empty($loc_data['area_id'])){
				if(!$ret->query("UPDATE `user_profile` SET area_id = ".$loc_data['area_id']." WHERE id = ". $dt['user_profile']['id'])){
					$ret = new Retailer();
				}
				echo "<br/>updated area_id ".$loc_data['area_id']." of ". $dt['user_profile']['id'];
			}
		}
		echo "Done";

		$this->autoRender = false;
	}




        /*function unblock_user_via_ip($ip=null){
            if(!empty($ip)){
            $redis = $this->Shop->redis_connect();
                if($redis->del("blockipset_".$ip)){
                    $sub = "UNBLOCKED USER IP";
                    $msg = "UNBLOCKED IP : $ip";
                    echo "user IP unblocked successfully";
                    $this->General->sendMails($sub,$msg,array('nandan.rana@pay1.in','ashish@pay1.in'),'mail');
                }
            }
            $this->autoRender = false;
        }

        function unblock_user_via_call(){
            //echo "<pre>";
            $ip = (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && $_SERVER["HTTP_X_FORWARDED_FOR"] != "") ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
            $user = trim($_REQUEST['mobile']);
            try{
                $whitelistedIP = $this->General->findVar('whitelistedIP');
                $whitelistedIP_Arr = explode(",",$whitelistedIP);
                //print_r($whitelistedIP_Arr);
                $retailer_query = "select * from retailers where mobile='$user'";
                $retailer_data = $this->Slaves->query($retailer_query);
                if(empty($retailer_data)){ return; }
                if(!in_array($ip,$whitelistedIP_Arr)){ return; }

                $redis = $this->Shop->redis_connect();
                $user_search_cmd = "blockuseripset_".$user."*";
                $blockuserdatamap = $redis->keys($user_search_cmd);
                //print_r($blockuserdatamap);
                $user_ip_cnt = array();
                foreach($blockuserdatamap as $blockuserdata){
                    $blockuserdata_arr = explode('_',$blockuserdata);
                    $user_ip = $blockuserdata_arr['2'];
                    $user_ip_search_cmd = "blockuseripset_*_".$user_ip;
                    $blockuser_ip_datamap = $redis->keys($user_ip_search_cmd);
                    $user_ip_cnt[$user_ip] = sizeof($blockuser_ip_datamap);
                    //print_r($blockuser_ip_datamap);
                }
                $validIP = array_search(min($user_ip_cnt),$user_ip_cnt);
                //echo "<hr>".$validIP."<hr>";
                if($redis->del("blockipset_".$validIP)){
                    $redis->del("blockuseripset_".$user."_".$validIP);
                    $sub = "UNBLOCKED USER IP VIA MISSED CALL";
                    $msg = "UNBLOCKED USER : $user | IP : $validIP";
                    $this->General->sendMails($sub,$msg,array('nandan.rana@pay1.in','ashish@pay1.in','customer.care@pay1.in'),'mail');
                }
            }catch(Exception $e){
                $sub = "UNABLE TO UNBLOCKED USER IP VIA MISSED CALL";
                $msg = "UNBLOCKED IP : $ip <br> ERROR MSG : ".$e->getMessage();
                $this->General->sendMails($sub,$msg,array('nandan.rana@pay1.in'),'mail');
            }
            $this->autoRender = false;
        }*/

        /**
         * set and reset airtel operator of specific distributor
         * @param type $stop_flag
         */
        /*function stop_airtel_for_distributor($stop_flag = null){
            if(in_array($stop_flag,array(0,1))){
                if($this->General->setVar("stop_airtel_by_distributor",$stop_flag)){
                    echo "done";
                }
            }
        }*/

        /**
         * Block ddos suspected ip
         * @return type
         * @throws Exception
         */
        /*function block_ddos_suspect(){
            $ip = $_REQUEST['ip'];
            try{
                $whitelistedIP = $this->General->findVar('whitelistedIP');
                $whitelistedIP_Arr = explode(",",$whitelistedIP);
                if(empty($ip)){ echo "empty ip "; return;}
                if(in_array($ip,$whitelistedIP_Arr)){ echo "whitelisted ip "; return; }
                $redis = $this->Shop->redis_connect();
                if($redis->hget('DDOS_SUSPECT_IP_SET',$ip)){
                    throw new Exception("IP already blocked");
                }
                if($redis->hset('DDOS_SUSPECT_IP_SET',$ip,'1')){
                    $sub = "DDOS SUSPECTED IP BLOCK";
                    $msg = "BLOCKED IP : $ip";
                    $this->General->sendMails($sub,$msg,array('nandan.rana@pay1.in','milind@pay1.in'),'mail');
                }else{
                    throw new Exception("unable to BLOCK DDOS SUSPECT");
                }
            }catch(Exception $e){
                $sub = "DDOS SUSPECTED IP BLOCK issue";
                echo $msg = "BLOCKED IP : $ip <br> ERROR MSG : ".$e->getMessage();
                $this->General->sendMails($sub,$msg,array('nandan.rana@pay1.in'),'mail');
            }
            $this->autoRender = false;
        }*/

       /* function fork_vm_table_process(){
            $this->autoRender = false;
            $diff = ASYNC_BATCH_INTERVAL;
            $is_start = 1;
            while(true){
                shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/async_db_process.sh $is_start vm");
                $is_start = 0;
                sleep($diff);
            }
        }

        function fork_vt_table_process(){
            $this->autoRender = false;
            $diff = 5;
            $is_start = 1;
            while(true){
                shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/async_db_process.sh $is_start vt");
                $is_start = 0;
                sleep($diff);
            }
        }
       */

        /**
         *
         * @param type $diff (in seconds)
         */
        /*function db_table_async_process($is_start=0,$tb = "vm"){
            $this->autoRender = false;
            $pid = microtime(true)*10000;
            $start_time = time();
            if($tb == "vm"){
                $this->General->execute_async_query(1,0,$is_start);
                $this->General->logData('/mnt/logs/async_db_query_perfomance.txt',"vm PID: $pid | total time in (seconds) : ".(time() - $start_time));
            }elseif($tb == "vt"){
                $this->General->execute_async_update_query($is_start);
                $this->General->logData('/mnt/logs/async_db_query_perfomance.txt',"vt PID: $pid | total time in (seconds): ".(time() - $start_time));
            }
        }*/

        /**
         * It will clear pending queries from redis  every min
         */
        /*function clear_async_db_backlog(){
            $this->autoRender = false;
            $this->General->execute_async_query(0,1);
        }*/

        /**
         * It will run every minute from cron and get the count of time TPS was greater then threshold
         */
        /*function monitor_tps_alert(){
            $redisObj = $this->Shop->redis_connector();
            $tps_threshold_crossed_count = $redisObj->get("TPS_MARKER");
            $redisObj->set("TPS_MARKER",0);
            if(!empty($tps_threshold_crossed_count)){
                $MOBILETO="9819032643,9221770571,7738832731";
                $sms = "Recharges queue length is greater than threshold .".$tps_threshold_crossed_count." times in last min";
                $this->General->sendMessage($MOBILETO,$sms,'shops');
            }
//            $redisObj->quit();
        }*/

	/*function updateMobileCodes(){
		$this->autoRender = false;

		$mobile_numbering = $this->Slaves->query("select *
					from mobile_numbering");
		foreach($mobile_numbering as $mn){
			$code = $mn['mobile_numbering']['number'];
			for($i = $code."0"; $i <= $code."9"; $i++){
				$existing_codes = $this->Slaves->query("select *
						from mobile_operator_area_map moam
						where moam.number = '$i'");
				if(!empty($existing_codes)){
					if(empty($existing_codes[0]['moam']['area'])){
						$this->User->query("update mobile_operator_area_map
								set area = '".$mn['mobile_numbering']['area']."'
								where number = '$i'");
					}
				}
				else {
					$this->User->query("insert into mobile_operator_area_map
							(operator, area, number)
							values ('".$mn['mobile_numbering']['operator']."', '".$mn['mobile_numbering']['area']."',
							'$i')");
				}
			}
		}

		echo "Done";
	}*/

        /**
         * This function will execute and get data from modem via url
         * @param type $vendorId : modemId
         * @param type $param: query string
         * @return type json string
         */
        /*function modem_via_api($vendorId,$param){
            $this->autoRender = false;
            $logger = $this->General->dumpLog('modem_api_route', 'modem_api_route');
            $logger->info("Received data : $vendorId param : ".  json_encode($param));
            $response = json_encode($this->Shop->modemRequest($param, $vendorId));
            $logger->info("Response data : ".$response);
            return $response;
        }*/

       /* function getData($vendor_id,$date){
        	//$date = date('Y-m-d');
        	$sql = "SELECT va.id,  va.discount_commission,va.amount,va.txn_id,va.shop_transaction_id,va.txn_id,"
                         ."va.retailer_id,va.vendor_id,va.product_id,va.timestamp,va.date,va.retailer_id,vm.id as vmid, vm.vendor_refid,"
                        ."vm.service_id,vm.va_tran_id, vm.service_vendor_id,vm.internal_error_code, "
                        ."vm.response, vm.status,vm.timestamp as endtimestamp,ret.parent_id "
                        ."FROM vendors_activations AS va "
                        ." JOIN vendors_messages AS vm ON ( vm.va_tran_id = va.txn_id) "
                        ." LEFT JOIN retailers as ret ON (va.retailer_id = ret.id) "
                        ."WHERE date = '$date'  AND va.vendor_id=$vendor_id ";
			$data = $this->Slaves->query($sql);
			//$this->General->logData("mongo_db.txt",json_encode($data));
			$ret = gzcompress(json_encode($data),9);
			//$this->General->logData("mongo_db_2.txt",$ret);


			echo $ret;
			$this->autoRender = false;
        }*/

        /*function getVendors(){
        	$data = $this->Slaves->query("SELECT id,update_flag FROM vendors where show_flag = 1");
        	echo json_encode($data);
        	$this->autoRender = false;
        }*/

//        send birthday wishes to distributors
          function sendBirthdayWishEmail()
        {
            $this->autoRender = false;
            $MsgTemplate = $this->General->LoadApiBalance();
            $date = date("Y-m-d");
            $query="SELECT d.id,d.name,d.email,u.mobile "
                    . "FROM distributors d "
                    . "JOIN users u "
                    . "ON (u.id=d.user_id) "
                    . "WHERE DAYOFMONTH(d.dob)=DAYOFMONTH('$date') and month(d.dob)=month('$date')";
            $users=$this->Slaves->query($query);

            /** IMP DATA ADDED : START**/
            $dist_ids = array_map(function($element){
                return $element['d']['id'];
            },$users);
            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);

            foreach($users as $key => $user){
                $users[$key]['d']['name'] = (isset($imp_data[$user['d']['id']])) ? $imp_data[$user['d']['id']]['imp']['name'] : $user['d']['name'];
                $users[$key]['d']['email'] = (isset($imp_data[$user['d']['id']])) ? $imp_data[$user['d']['id']]['imp']['email_id'] : $user['d']['email'];
            }
            /** IMP DATA ADDED : END**/

            $this->General->logData('/mnt/logs/sendBirthdayWishEmail'.date('Y-m-d').'.log',json_encode($users),FILE_APPEND | LOCK_EX);

            foreach($users as $user):
                $content =  $MsgTemplate['Birthday Wishes'];
                $params['NAME'] = $user['d']['name'];
                 $mail_body = $this->General->ReplaceMultiWord($params,$content);
                $mail_subject='Birthday Wishes';
                $this->General->sendMails ( $mail_subject, $mail_body, array($user['d']['email']), 'mail' );
                $sms =  $MsgTemplate['Birthday_Wish_sms'];
                $this->General->sendMessage ( $user['u']['mobile'], $sms, 'shops' );
            endforeach;
        }

//        send anniversary wishes to distributors
        function sendAnniversaryEmail()
        {
            $this->autoRender = false;
            $MsgTemplate = $this->General->LoadApiBalance();
            $date = date("Y-m-d");
            $query="SELECT d.id,d.name,d.email,u.mobile "
                    . "FROM distributors d "
                    . "JOIN users u "
                    . "ON (u.id=d.user_id) "
                    . "WHERE DAYOFMONTH(d.created)=DAYOFMONTH('$date') and month(d.created)=month('$date')";
            $users=$this->Slaves->query($query);

            /** IMP DATA ADDED : START**/
            $dist_ids = array_map(function($element){
                return $element['d']['id'];
            },$users);
            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);

            foreach($users as $key => $user){
                $users[$key]['d']['name'] = (isset($imp_data[$user['d']['id']])) ? $imp_data[$user['d']['id']]['imp']['name'] : $user['d']['name'];
                $users[$key]['d']['email'] = (isset($imp_data[$user['d']['id']])) ? $imp_data[$user['d']['id']]['imp']['email_id'] : $user['d']['email'];
            }
            /** IMP DATA ADDED : END**/

            $this->General->logData('/mnt/logs/sendAnniversaryEmail'.date('Y-m-d').'.log',json_encode($users),FILE_APPEND | LOCK_EX);

            foreach($users as $user):
                $content =  $MsgTemplate['Anniversary Wishes'];
                $params['NAME'] = $user['d']['name'];
                 $mail_body = $this->General->ReplaceMultiWord($params,$content);
                $mail_subject = 'Happy Anniversary from Pay1';
                $this->General->sendMails ( $mail_subject, $mail_body,array($user['d']['email']), 'mail' );
                $sms =  $MsgTemplate['Anniversary_Wish_sms'];
                $this->General->sendMessage ( $user['u']['mobile'], $sms, 'shops' );
            endforeach;
        }





		function floatAlert(){

			$this->autoRender =false;

			$query = "SELECT * FROM `float_logs`  order by id desc limit 0,2";

			$fetchdata=$this->Slaves->query($query);

			if(!empty($fetchdata) && count($fetchdata)>1){

			$closing = $fetchdata[0]['float_logs']['float_without_b2c']-$fetchdata[1]['float_logs']['float_without_b2c'];

			$sale = $fetchdata[0]['float_logs']['sale']-$fetchdata[1]['float_logs']['sale'];

			$transfer = $fetchdata[0]['float_logs']['transferred']-$fetchdata[1]['float_logs']['transferred'];

			$commision = $fetchdata[0]['float_logs']['commissions']-$fetchdata[1]['float_logs']['commissions'];

			$reversal = $fetchdata[0]['float_logs']['old_reversals']-$fetchdata[1]['float_logs']['old_reversals'];

			$service_charge = $fetchdata[0]['float_logs']['service_charge']-$fetchdata[1]['float_logs']['service_charge'];

			$diff = $closing -($commision+$reversal+$transfer-$sale-$service_charge);

			if(!empty($diff) && $diff<0){

				$this->General->sendMessage('9819032643,9833258509,7208207549', 'Float balance is gone negative of  '.$diff.' between '.($fetchdata[1]['float_logs']['time']).' and ' . ($fetchdata[0]['float_logs']['time']), 'payone');
			}

			}

		}

//		function saveInvoiceData($fromdate,$todate)
/*		function saveInvoiceData($date)
		{
		    if(is_null($date)):
                        $date=date('Y-m-d',strtotime('-1 days'));
		    endif;

		    $sql1="SELECT group_concat(rl.retailer_id),count(rl.retailer_id) as count,sum(rl.sale) as totalsale,sum(rl.earning) as ret_earning,rl.date as tdate,d.id as did "
                        . "FROM retailers_logs rl "
                        . "join retailers r "
                        . "on (r.id=rl.retailer_id) "
                        . "join distributors d "
                        . "on (r.parent_id=d.id) "
                        . "WHERE  rl.date='$date' "
//		    . "WHERE  rl.date>='$fromdate' and rl.date<='$todate' "
                        . "GROUP BY d.id,tdate";

		    $sql2="SELECT group_concat(st.id) as txn_id,sum(st.amount) as topup_buy,st.target_id,st.date as txndate,d.id as distid,d.company as dname "
                        . "FROM shop_transactions st "
                        . "LEFT JOIN distributors d "
                        . "ON (d.id=st.target_id) "
                        . "WHERE st.date='$date'  and st.source_id='3' "
//		    . "WHERE st.date>='$fromdate' and st.date<='$todate' and st.source_id='3' "
                        . "AND st.type='1' "
                        . "GROUP BY st.target_id,st.date ";

                    $sql3="SELECT source_id,group_concat(target_id),sum(amount) as dist_earning,date "
                        . "FROM shop_transactions "
                        . "WHERE type=6 and date='$date' "
//                    . "WHERE type=6 and date>='$fromdate' and date<='$todate' "
                        . "GROUP BY source_id,date";

                    $finalsql="SELECT * FROM ($sql1) as a LEFT JOIN ($sql2) AS b ON ( a.did = b.target_id and a.tdate=b.txndate) left join ($sql3) as c on (a.did=c.source_id and a.tdate=c.date) GROUP BY a.did,a.tdate ORDER BY a.did,a.tdate";

                    $invoicedata=$this->Slaves->query($finalsql);

                    $i=1;
                    $batch_qry = array();
                    foreach($invoicedata as $data):
                    //                        $shop_txn_id=$data['a']['txn_id'];
                    $dist_id=$data['a']['did'];
                    $topup_buy=$data['b']['topup_buy'];
                    $totalsale=$data['a']['totalsale'];
                    $earning=$data['c']['dist_earning']+$data['a']['ret_earning'];
                    $invoicedate=$data['a']['tdate'];
                    $invoice_type=($earning > 0) ? 0 : 1;//0 means discount model , 1 means commission model
                    $batch_qry['type'] = "INSERT";
                    $batch_qry['predata'] = "insert into invoices_data(distributor_id,topup_buy,earning,invoice_date,yearmonth_id,invoice_type)values";
                    $batch_qry['data'][] = "('$dist_id','$totalsale','$earning','$invoicedate','".date('Ym', strtotime($invoicedate))."','$invoice_type')";

                    if ( ($i % 100) == 0 ): //process batch of 100 query together
                    $this->run_batch($batch_qry);
                    $batch_qry = array();
                    endif;

                    $i++;
                    endforeach;

                    if ( !empty($batch_qry) ): // handled cases if last batch consist less then 100 queries
                    $this->run_batch($batch_qry);
                    $batch_qry = array();
                    endif;
                    $this->autoRender =false;
		}
*/

		function commissionApiRetailer($days = 0){
		    $this->autoRender =false;
		    $rets = $this->Slaves->query("SELECT id,user_id FROM retailers WHERE retailer_type = 1 AND id NOT IN (".B2C_RETAILER.")");
		    $last_date = date('Y-m-d',strtotime("$days days"));

		    foreach($rets as $ret){
		        $retailer_id = $ret['retailers']['id'];
		        $user_id = $ret['retailers']['user_id'];
		        $get_retailer_data = "SELECT p.type as product_type,SUM(st.amount) as total_sale,SUM(st.retailer_margin) as ret_commission "
		                . "FROM vendors_activations st "
		                . "LEFT JOIN retailers r "
		                . "ON (st.retailer_id = r.id) "
		                . "LEFT JOIN vendors_commissions vc "
		                . "ON (st.vendor_id=vc.vendor_id AND st.product_id=vc.product_id) "
		                . "LEFT JOIN products p "
		                . "ON (st.product_id = p.id) "
		                . "WHERE p.service_id IN (1,2) "
		                . "AND (p.type=1 OR (p.type = 0 AND vc.type = 1)) "
		                . "AND st.status NOT IN (2,3) "
		                . "AND st.retailer_id = '$retailer_id' "
		                . "AND st.date = '$last_date' "
		                . "GROUP BY r.user_id";
		        $get_retailer_data = $this->Slaves->query($get_retailer_data);
		        $sale = $get_retailer_data[0][0]['total_sale'];
		        $commission = $get_retailer_data[0][0]['ret_commission'];
		        if($commission > 0){
		          $denom = "1.".SERVICE_TAX_PERCENT;
		          $tds = ($commission/$denom)*TDS_PERCENT/100;

		          $closing_bal= $this->Shop->shopBalanceUpdate($tds, 'subtract', $user_id, RETAILER);

		          $description = "$service_name: TDS deducted on commission of P2A txn sale ($sale)";
		          $trans_id = $this->Shop->shopTransactionUpdate(TDS, $tds, $user_id, null, null, null, null, $description,$closing_bal+$tds,$closing_bal);
		        }
		    }





		}
		
		
		
		/*function commissionDistributorReverse(){
		    $this->autoRender =false;
		    $query = "SELECT sum(st.amount) as amts,st.source_id,st.user_id,distributors.user_id FROM `shop_transactions` as st inner join distributors on (distributors.id = st.source_id) WHERE st.type = '6' AND st.date = '2018-10-31' AND st.timestamp >= '2018-10-31 20:28:00' group by st.user_id,st.source_id order by st.source_id desc";
		    
		    $data = $this->Retailer->query($query);
		    
		    foreach($data as $dt){
		      $service_id = $dt['st']['user_id'];
		      $source_id = $dt['st']['source_id'];
		      $amount = $dt['0']['amts'];
		      $user_id = $dt['distributors']['user_id'];
		      
		      $bal = $this->Shop->shopBalanceUpdate($amount, 'subtract', $user_id, DISTRIBUTOR);
		      
		      $description = "Reversing wrong commission transferred";
		      
		      $trans_id = $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR_REVERSE, $amount, $source_id, 0, $service_id, null, null, $description,$bal+$amount,$bal);
		      
		      
		    }
		}*/
		
		
		/*function TDSDistributorReverse(){
		 $this->autoRender =false;
		 $query = "SELECT sum(st.amount) as amts,st.source_id,st.user_id,distributors.id FROM `shop_transactions` as st inner join distributors on (distributors.user_id = st.source_id) WHERE st.type = '31' AND st.date = '2018-10-31' AND st.timestamp >= '2018-10-31 20:28:00' AND st.timestamp <= '2018-10-31 20:40:00' group by st.user_id,st.source_id order by st.source_id desc";
		 
		 $data = $this->Retailer->query($query);
		 
		 foreach($data as $dt){
		 $service_id = $dt['st']['user_id'];
		 $user_id = $dt['st']['source_id'];
		 $amount = $dt['0']['amts'];
		 $source_id = $dt['distributors']['id'];
		 
		 $bal = $this->Shop->shopBalanceUpdate($amount, 'add', $user_id, DISTRIBUTOR);
		 
		 $description = "Crediting TDS amount wrongly debited";
		 
		 $trans_id = $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR, $amount, $source_id, 0, $service_id, null, null, $description,$bal-$amount,$bal);
		 
		 
		 }
		 }*/
		

		
		/*
		 * Commission on the basis of % or fixed on no. of txns
		 * Rental incentives
		 * Seperate commission with GST incentive & commission - ??
		 * Negate commission of 0.5% if distributor is on primary & txns are of debit type
		 */
		
		function commissionDistributor($days = 0){
		    $this->autoRender =false;
		    $last_date = date('Y-m-d',strtotime("- $days days"));
		    Configure::load('product_config');
		    $services_config = Configure::read('services');
		    
		    $dists = $this->Slaves->query("SELECT distributors.id,distributors.user_id,distributors.mobile,distributors.commission_type,distributors.margin,distributors.incentive,distributors.gst_no FROM distributors WHERE active_flag = 1 AND id NOT IN (".DISTS.") AND id NOT in (".SAAS_DISTS.")");
		    /** IMP DATA ADDED : START**/
		    $dist_ids = array_map(function($element){
		        return $element['distributors']['user_id'];
		    },$dists);
		    $imp_data = $this->Shop->getUserLabelData($dist_ids,2,0);
		    
		    $prodWiseSale = $this->Slaves->query("(SELECT st.id,st.amount,st.type,st.target_id,products.service_id,distributors.user_id,'' as dist_margins FROM shop_transactions as st use index (type_date) LEFT JOIN retailers ON (retailers.id = st.source_id) LEFT JOIN products ON (products.id = st.target_id) left join distributors ON (distributors.id = retailers.parent_id) WHERE distributors.active_flag = 1 AND st.target_id != 0 AND st.confirm_flag != 0 AND st.date ='$last_date' AND st.type = 4) UNION (SELECT st.id,st.amount,st.type,st.target_id,products.service_id,distributors.user_id,trim(spp.dist_params) as dist_margins FROM shop_transactions as st use index (type_date) LEFT JOIN retailers ON (retailers.user_id = st.source_id) LEFT JOIN products ON (products.id = st.target_id) left join distributors ON (distributors.id = retailers.parent_id) left join users_services on (products.service_id = users_services.service_id AND st.source_id = users_services.user_id) left join service_plans on (service_plans.id = users_services.service_plan_id) left join service_product_plans as spp on (spp.service_plan_id = service_plans.id AND spp.product_id = products.id) WHERE distributors.active_flag = 1 AND st.target_id != 0 AND st.confirm_flag != 1 AND st.date ='$last_date' AND st.type in (16,17))");
		    //Old reversed txns to be considered here
		    
		    $prodWiseSale_reversed = $this->Slaves->query("(SELECT st.id,st.amount,st.type,st.target_id,products.service_id,distributors.user_id,'' as dist_margins FROM shop_transactions as st1 use index (type_date) LEFT JOIN shop_transactions as st ON (st1.target_id = st.id) LEFT JOIN retailers ON (retailers.id = st.source_id) LEFT JOIN products ON (products.id = st.target_id) left join distributors ON (distributors.id = retailers.parent_id) WHERE distributors.active_flag = 1 AND st.target_id != 0 AND st.confirm_flag = 0 AND st1.date ='$last_date' AND st1.type = 11 AND st.date < st1.date) UNION (SELECT st.id,st.amount,st.type,st.target_id,products.service_id,distributors.user_id,trim(spp.dist_params) as dist_margins FROM shop_transactions as st1 use index (type_date) LEFT JOIN shop_transactions st ON (st1.target_id = st.id) LEFT JOIN retailers ON (retailers.user_id = st.source_id) LEFT JOIN products ON (products.id = st.target_id) left join distributors ON (distributors.id = retailers.parent_id) left join users_services on (products.service_id = users_services.service_id AND st.source_id = users_services.user_id) left join service_plans on (service_plans.id = users_services.service_plan_id) left join service_product_plans as spp on (spp.service_plan_id = service_plans.id AND spp.product_id = products.id) WHERE distributors.active_flag = 1 AND st.target_id != 0 AND st.confirm_flag = 1 AND st1.date ='$last_date' AND st1.type in (32) AND st.date < st1.date)");
		    
		    //Partial reversed txns to be considered as well
		    
		    $partialcanSale = $this->Slaves->query("SELECT wpc.amount_refunded as amount, wt.product_id, '16' as type, products.service_id, distributors.user_id, trim(spp.dist_params) as dist_margins FROM wallet_partial_cancellations as wpc left join wallets_transactions as wt ON (wt.txn_id = wpc.txn_id and wt.server = wpc.server) LEFT JOIN products ON (products.id = wt.product_id) LEFT JOIN retailers ON (retailers.user_id = wt.user_id) left join distributors ON (distributors.id = retailers.parent_id) left join users_services on (products.service_id = users_services.service_id AND wt.user_id = users_services.user_id) left join service_plans on (service_plans.id = users_services.service_plan_id) left join service_product_plans as spp on (spp.service_plan_id = service_plans.id AND spp.product_id = products.id) where distributors.active_flag = 1 and wpc.date = '$last_date'");
		    $serviceWiseData = array();
		    
		    foreach($prodWiseSale as $prodSale){
		        $service_id = $prodSale['0']['service_id'];
		        $product_id = $prodSale['0']['target_id'];
		        $dist_user_id = $prodSale['0']['user_id'];
		        $sale = $prodSale['0']['amount'];
		        $sale_type = $prodSale['0']['type'];
		        $sale_type= ($sale_type == 4) ? 16 : $sale_type;
		        $config = array();
		        
		        if(empty($prodSale[0]['dist_margins']) && isset($services_config[$service_id])){
		            $config = $services_config[$service_id];
		            $variable = $config['variable'];
		            $config = array('0-0'=>array('margin'=>$variable.'%','min'=>0,'max'=>''));
		        }
		        else if(!empty($prodSale[0]['dist_margins'])){
		            $config = json_decode($prodSale[0]['dist_margins'],true);
		        }
		        
		        if(!empty($config)){
		            if(empty($serviceWiseData[$dist_user_id][$service_id][$sale_type])){
		                $serviceWiseData[$dist_user_id][$service_id][$sale_type]['sale'] = 0;
		                $serviceWiseData[$dist_user_id][$service_id][$sale_type]['count'] = 0;
		                $serviceWiseData[$dist_user_id][$service_id][$sale_type]['commission'] = 0;
		            }
		            $amount_comm = $this->Shop->calculateCommission($sale, $config);
		            $serviceWiseData[$dist_user_id][$service_id][$sale_type]['sale'] += $sale;
		            $serviceWiseData[$dist_user_id][$service_id][$sale_type]['count'] ++;
		            $serviceWiseData[$dist_user_id][$service_id][$sale_type]['commission'] += $amount_comm['comm'];
		            $serviceWiseData[$dist_user_id][$service_id][$sale_type]['commission_on'] = $amount_comm['type'];//0 sale, 1 count
		        }
		       
		    }
		    
		    foreach($prodWiseSale_reversed as $prodSale){
		        $service_id = $prodSale['0']['service_id'];
		        $product_id = $prodSale['0']['target_id'];
		        $dist_user_id = $prodSale['0']['user_id'];
		        $sale = $prodSale['0']['amount'];
		        $sale_type = $prodSale['0']['type'];
		        $config = array();
		        
		        if(empty($prodSale[0]['dist_margins']) && isset($services_config[$service_id])){
		            $config = $services_config[$service_id];
		            $variable = $config['variable'];
		            $config = array('0-0'=>array('margin'=>$variable.'%','min'=>0,'max'=>''));
		        }
		        else if(!empty($prodSale[0]['dist_margins'])){
		            $config = json_decode($prodSale[0]['dist_margins'],true);
		        }
		        
		        if(!empty($config)){
		            $amount_comm = $this->Shop->calculateCommission($sale, $config);
		            if(isset($serviceWiseData[$dist_user_id][$service_id][$sale_type]['sale'])){
		                $serviceWiseData[$dist_user_id][$service_id][$sale_type]['sale'] -= $sale;
		                $serviceWiseData[$dist_user_id][$service_id][$sale_type]['count'] --;
		                $serviceWiseData[$dist_user_id][$service_id][$sale_type]['commission'] -= $amount_comm['comm'];
		            }
		        }
		    }
		    
		    
		    foreach($partialcanSale as $prodSale){
		        $service_id = $prodSale['products']['service_id'];
		        $product_id = $prodSale['wt']['product_id'];
		        $dist_user_id = $prodSale['distributors']['user_id'];
		        $sale = $prodSale['wpc']['amount'];
		        $sale_type = $prodSale['0']['type'];
		        $config = array();
		        
		        if(empty($prodSale[0]['dist_margins']) && isset($services_config[$service_id])){
		            $config = $services_config[$service_id];
		            $variable = $config['variable'];
		            $config = array('0-0'=>array('margin'=>$variable.'%','min'=>0,'max'=>''));
		        }
		        else if(!empty($prodSale[0]['dist_margins'])){
		            $config = json_decode($prodSale[0]['dist_margins'],true);
		        }
		        
		        if(!empty($config)){
		            $amount_comm = $this->Shop->calculateCommission($sale, $config);
		            if(isset($serviceWiseData[$dist_user_id][$service_id][$sale_type]['sale'])){
		                $serviceWiseData[$dist_user_id][$service_id][$sale_type]['sale'] -= $sale;
		                $serviceWiseData[$dist_user_id][$service_id][$sale_type]['commission'] -= $amount_comm['comm'];
		            }
		        }
		    }
		    
		    $check_exist = array();
                    //$check_exist = array_map(function($element) { return $element['shop_transactions']['source_id']; }, $this->Retailer->query("SELECT source_id FROM shop_transactions WHERE type = '".COMMISSION_DISTRIBUTOR."' AND target_id = '0' AND date = '$last_date' AND confirm_flag = 0"));
		    
		    foreach($dists as $dist){
                        if (!in_array($dist['distributors']['id'], $check_exist)) {
                            $dist_id = $dist['distributors']['id'];
                            $dist_user_id = $dist['distributors']['user_id'];
                            $dist['distributors']['gst_no'] = $imp_data[$dist_user_id]['imp']['gst_no'];
                            $mobile = $dist['distributors']['mobile'];
                            $tertiary_flag = $dist['distributors']['commission_type'];
                            $dist_margin = $dist['distributors']['margin'];
                            $gst_flag = (strlen($dist['distributors']['gst_no']) < 15) ? false : true;

                            $prodWiseSale = $serviceWiseData[$dist_user_id];
                            $distData = array();

                            $inc_data = $this->checkRentalIncentives($dist_id,$last_date);
                            foreach($inc_data as $service_id=>$inc){
                                $distData[$service_id]['rental_incentive'] = $inc['incentive'];
                                $distData[$service_id]['rental_tds'] = $this->Shop->calculateTDS($inc['incentive']);
                            }

                            foreach($prodWiseSale as $service_id=>$prodSale){
                                foreach($prodSale as $sale_type=>$saleData){
                                    if($tertiary_flag == 0 && $sale_type == '16'){//16 is debit type
                                        $distData[$service_id]['negate_comm'] = $saleData['sale']*$dist_margin/100;
                                    }
                                    $distData[$service_id]['commission'] += $saleData['commission'];
                                    $distData[$service_id]['sale'] += $saleData['sale'];
                                    $distData[$service_id]['count'] += $saleData['count'];
                                    $distData[$service_id]['commission_on'] = $saleData['commission_on'];
                                }
                                if(!empty($distData[$service_id]['commission']) && $distData[$service_id]['commission'] > 0)
                                    $distData[$service_id]['tds_comm'] = $this->Shop->calculateTDS($distData[$service_id]['commission']);
                            }

                            $this->createShopTxnEntries($distData, $dist_id, $dist_user_id, $mobile,$dist_margin);
                        }
		    }
		}
		
		private function createShopTxnEntries($serviceWiseData, $distributor_id, $user_id, $mobile,$dist_margin){
		    $services_data = $this->Slaves->query("SELECT id,name FROM services WHERE toShow = 1");
		    $services = array();
		    foreach($services_data as $service){
		        $id = $service['services']['id'];
		        $services[$id]['name'] =  $service['services']['name'];
		    }
		    
		    try{
		        $msg = ""; $amount = 0;
		        
		        $balance = $this->Shop->getBalance($user_id);
		        $amt_to_be_settled = 0;
		        $closing_bal = $balance;
		        
		        foreach($serviceWiseData as $service_id=>$serviceData){
		            $service_name = $services[$service_id]['name'];
		            $amt_to_be_settled = 0;
		            
		            if(isset($serviceData['negate_comm']) && $serviceData['negate_comm'] > 0){
		                $amt_to_be_settled -= $serviceData['negate_comm'];
		                $description = "$service_name: Debited commission ($dist_margin %) given on primary against total sale: " . $serviceData['sale'];
		                
		                $trans_id = $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR_REVERSE, $serviceData['negate_comm'], $distributor_id, 0, 0, null, null, $description,$closing_bal,$closing_bal-$serviceData['negate_comm']);
		                $closing_bal -= $serviceData['negate_comm'];
		            }
		            
		            if(isset($serviceData['commission']) && $serviceData['commission'] > 0){
		                if($serviceData['commission_on'] == 0){
		                    $msg .= "\n$service_name: ".$serviceData['commission']. " (" . $serviceData['sale'] . ")";
		                    $description = "$service_name: Total sale: " . $serviceData['sale'];
		                }
		                else {
		                    $msg .= "\n$service_name: ".$serviceData['commission']. " (Total Txns: " . $serviceData['count'] . ")";
		                    $description = "$service_name: Total txns: " . $serviceData['count'];
		                }
		                
		                $amount += $serviceData['commission'];
		                $amt_to_be_settled += $serviceData['commission'];
		                $trans_id = $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR, $serviceData['commission'], $distributor_id, 0, $service_id, null, null, $description,$closing_bal,$closing_bal+$serviceData['commission']);
		                $closing_bal += $serviceData['commission'];
		            }
		            
		            if(isset($serviceData['tds_comm']) && $serviceData['tds_comm'] > 0){
		                $amt_to_be_settled -= $serviceData['tds_comm'];
		                $description = "$service_name: TDS deducted on commission";
		                $this->Shop->shopTransactionUpdate(TDS, $serviceData['tds_comm'], $user_id, $trans_id, $service_id, null, null, $description,$closing_bal,$closing_bal-$serviceData['tds_comm']);
		                $closing_bal -= $serviceData['tds_comm'];
		            }
		            
		            
		            if(isset($serviceData['rental_incentive']) && $serviceData['rental_incentive'] > 0){
		                $amount_this = $serviceData['rental_incentive'];
		                if(isset($serviceData['rental_tds'])){
		                    $amount_this-= $serviceData['rental_tds'];
		                }
		                $amt_to_be_settled += $amount_this;
		                
		                $description = "$service_name: Rental incentive";
		                $trans_id = $this->Shop->shopTransactionUpdate(REFUND, $serviceData['rental_incentive'], $user_id, 0, $service_id, null, null, $description,$closing_bal,$closing_bal+$serviceData['rental_incentive']);
		                
		                if(isset($serviceData['rental_tds'])){
		                    $description = "$service_name: TDS deducted on last incentive: $trans_id";
		                    $trans_id = $this->Shop->shopTransactionUpdate(TDS, $serviceData['rental_tds'], $user_id, $trans_id, $service_id, null, null, $description,$closing_bal+$serviceData['rental_incentive'],$closing_bal+$amount_this);
		                }
		                
		                $closing_bal += $amount_this;
		            }
		            
		            $this->Shop->shopBalanceUpdate($amt_to_be_settled, 'add', $user_id, DISTRIBUTOR);
		            if($trans_id === false) throw new Exception('Cannot create a txn here');
		            
		            
		            $balance += $amt_to_be_settled;
		        }
		        
		        if($amount > 0){
		            $MsgTemplate = $this->General->LoadApiBalance();
		            $content =  $MsgTemplate['Distributor_Commission_MSG'];
		            $sms   = $this->General->ReplaceMultiWord(array('AMOUNT'=>$amount,'MID_MSG'=>$msg,'BALANCE'=>$balance),$content);
		            $this->General->sendMessage($mobile,$sms,'shops');
		        }
		        
		    }
		    catch(Exception $e){
		        return json_decode($e->getMessage());
		    }
		}

		


    private function checkRentalIncentives($dist_id, $date){
        $prodWiseSale = $this->Retailer->query("SELECT count(st.id) as cts,sum(st.amount) as sale,trim(st.user_id) as service_id,service_plans.dist_rental_commission FROM shop_transactions as st LEFT JOIN retailers ON (retailers.user_id = st.source_id) left join users_services on (st.source_id = users_services.user_id AND st.user_id = users_services.service_id) left join service_plans on (service_plans.id = users_services.service_plan_id) WHERE st.date ='$date' AND st.type in (20) AND retailers.parent_id = '$dist_id' group by service_id");
        $data = array();
        foreach($prodWiseSale as $prods){
            $service_id = $prods[0]['service_id'];
            $incentive = $prods['service_plans']['dist_rental_commission'];
            
            if($incentive > 0){
                $count = $prods[0]['cts'];
                $sale = $prods[0]['sale'];
                $data[$service_id]['incentive'] = $incentive * $count;
            }
        }
        
        return $data;
    }

    	
		private function calculateAmount($sale,$count,$configs,$tertiary_flag,$dist_margin,$incentive,$gst_flag){
		    $gst_inc = 0;
		    $gst_inc_tds = 0;
		    if($configs['commission'] && $configs['variable'] > 0){
		        $comm_per = $configs['variable'];
		        $comm = $sale*$comm_per/100;
		        $denom = "1.".SERVICE_TAX_PERCENT;

		        if($gst_flag){
		            $tds_comm = round(($comm/$denom)*TDS_PERCENT/100,3);
		        }
		        else {
		            if(isset($configs['gst_incentive']) && $configs['gst_incentive']){
		                $comm_per_new = round($comm_per/$denom,3);
		                $comm = $sale*$comm_per_new/100;
		                $gst_inc = $sale*($comm_per - $comm_per_new)/100;
		                $gst_inc_tds = round($gst_inc*TDS_PERCENT/100,3);
		                $configs['variable'] = $comm_per_new;
		            }
		            $tds_comm = round($comm*TDS_PERCENT/100,3);

		        }
		    }

		    if($configs['tertiary_flag'] == $tertiary_flag){
		        $type = 'credit';
		    }
		    else if($configs['tertiary_flag'] != $tertiary_flag && $configs['allow_negative'] && !empty($configs['variable'])){
		        $diff = $configs['variable'] - $dist_margin;
		        if($diff < 0){
		            $type = 'debit';
		            $configs['variable'] = 0-$diff;
		        }
		    }
		    else if($configs['incentive_flag'] && $incentive > 0){
		        $type = 'credit_inc';
		    }

		    $incentive_amount = 0; $tds = 0; $commission = 0;
		    $ret = array();
		    if(!empty($type)){
		            if($type != 'credit_inc'){
                		    if(empty($configs['fixed']) && !empty($configs['variable'])){
                		        $commission = round($sale*$configs['variable']/100,3);
                		    }
                		    else if(empty($configs['variable']) && !empty($configs['fixed'])){
                		        $commission = $configs['fixed'];
                		    }
		            }
		            else $type = 'credit';

        		    if($configs['incentive_flag'] && $incentive > 0){
        		        $incentive_amount = round($sale*$incentive/100,3);
        		    }

        		    if(!$configs['commission'] && $commission > 0){
        		        $incentive_amount = $commission;
        		        $commission = 0;
        		    }

        		    if($incentive_amount > 0){
        		        if($gst_flag){
        		            $denom = "1.".SERVICE_TAX_PERCENT;
        		            $tds = round(($incentive_amount/$denom)*TDS_PERCENT/100,3);
        		        }
        		        else {
        		            $tds = round($incentive_amount*TDS_PERCENT/100,3);
        		        }
        		    }

        		    $ret = array('commission'=>$commission,'type'=>$type,'incentive'=>$incentive_amount,'tds'=>$tds,'gst_inc'=>$gst_inc,'gst_inc_tds'=>$gst_inc_tds);

		    }


		    if($tds_comm > 0){
		        $ret['tds_comm'] = $tds_comm;
		        if(!isset($ret['commission']))$ret['commission'] = 0;
		    }

		    return $ret;
		}
		
		
		

		private function createShopTxnEntries_old($serviceWiseData, $distributor_id, $user_id, $mobile){
		    $services_data = $this->Slaves->query("SELECT id,name FROM services WHERE toShow = 1");
		    $services = array();
		    foreach($services_data as $service){
		        $id = $service['services']['id'];
		        $services[$id]['name'] =  $service['services']['name'];
		    }

		    try{
		        $msg = ""; $amount = 0;

		        $balance = $this->Shop->getBalance($user_id);
		        $amt_to_be_settled = 0;
		        $closing_bal = $balance;

		        foreach($serviceWiseData as $service_id=>$serviceData){
		            $service_name = $services[$service_id]['name'];
		            $amt_to_be_settled = 0;
		            if(isset($serviceData['commission']) && $serviceData['commission'] > 0){
		                if(isset($serviceData['gst_inc'])){
		                    $comm_amount = $serviceData['commission'] + $serviceData['gst_inc'];
		                }
		                else {
		                    $comm_amount = $serviceData['commission'];
		                }
		                $msg .= "\n$service_name: ".$serviceData['commission']. " (" . $serviceData['sale'] . ")";
		                $amount += $serviceData['commission'];
		                $description = "$service_name: Total sale: " . $serviceData['sale'];

		                $amt_to_be_settled += $serviceData['commission'];
		                $trans_id = $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR, $serviceData['commission'], $distributor_id, 0, $service_id, null, null, $description,$closing_bal,$closing_bal+$serviceData['commission']);
		                //$type = 'add';
		                $closing_bal += $serviceData['commission'];
		            }
		            else if(isset($serviceData['charges'])  && $serviceData['charges'] > 0){
		                $amt_to_be_settled -= $serviceData['charges'];
		                $description = "$service_name: Debited extra commission given against total sale: " . $serviceData['sale'];

		                $trans_id = $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR_REVERSE, $serviceData['charges'], $distributor_id, 0, $service_id, null, null, $description,$closing_bal,$closing_bal-$serviceData['charges']);
		                $closing_bal -= $serviceData['charges'];
		            }

		            if(isset($serviceData['tds_comm']) && $serviceData['tds_comm'] > 0){
		                $amt_to_be_settled -= $serviceData['tds_comm'];
		                $description = "$service_name: TDS deducted on commission";
		                $this->Shop->shopTransactionUpdate(TDS, $serviceData['tds_comm'], $user_id, $trans_id, $service_id, null, null, $description,$closing_bal,$closing_bal-$serviceData['tds_comm']);
		                $closing_bal -= $serviceData['tds_comm'];
		            }

		            if(isset($serviceData['incentive']) && $serviceData['incentive'] > 0){
		                $amount_this = $serviceData['incentive'];
		                if(isset($serviceData['tds'])){
		                    $amount_this -= $serviceData['tds'];
		                }
		                $amt_to_be_settled += $amount_this;

		                $description = "$service_name: Extra incentive on total sale: " . $serviceData['sale'];
		                $trans_id = $this->Shop->shopTransactionUpdate(REFUND, $serviceData['incentive'], $user_id, 0, $service_id, null, null, $description,$closing_bal,$closing_bal+$serviceData['incentive']);

		                if(isset($serviceData['tds'])){
    		                    $description = "$service_name: TDS deducted on last incentive: $trans_id";
    		                    $trans_id = $this->Shop->shopTransactionUpdate(TDS, $serviceData['tds'], $user_id, $trans_id, $service_id, null, null, $description,$closing_bal+$serviceData['incentive'],$closing_bal+$amount_this);
    		                }

    		                $closing_bal += $amount_this;
		            }

		            if(isset($serviceData['gst_inc']) && $serviceData['gst_inc'] > 0){
		                $amount_this = $serviceData['gst_inc'];
		                if(isset($serviceData['gst_inc_tds'])){
		                    $amount_this -= $serviceData['gst_inc_tds'];
		                }
		                $amt_to_be_settled += $amount_this;

		                $description = "$service_name: GST incentive on total sale: " . $serviceData['sale'];
		                $trans_id = $this->Shop->shopTransactionUpdate(REFUND, $serviceData['gst_inc'], $user_id, 0, $service_id, null, null, $description,$closing_bal,$closing_bal+$serviceData['gst_inc']);

		                if(isset($serviceData['gst_inc_tds'])){
		                    $description = "$service_name: TDS deducted on GST Incentive: $trans_id";
		                    $trans_id = $this->Shop->shopTransactionUpdate(TDS, $serviceData['gst_inc_tds'], $user_id, $trans_id, $service_id, null, null, $description,$closing_bal+$serviceData['gst_inc'],$closing_bal+$amount_this);
		                }

		                $closing_bal += $amount_this;
		            }

		            if(isset($serviceData['rental_incentive']) && $serviceData['rental_incentive'] > 0){
		                $amount_this = $serviceData['rental_incentive'];
		                if(isset($serviceData['rental_tds'])){
		                    $amount_this-= $serviceData['rental_tds'];
		                }
		                $amt_to_be_settled += $amount_this;

		                $description = "$service_name: Rental incentive";
		                $trans_id = $this->Shop->shopTransactionUpdate(REFUND, $serviceData['rental_incentive'], $user_id, 0, $service_id, null, null, $description,$closing_bal,$closing_bal+$serviceData['rental_incentive']);

		                if(isset($serviceData['rental_tds'])){
		                    $description = "$service_name: TDS deducted on last incentive: $trans_id";
		                    $trans_id = $this->Shop->shopTransactionUpdate(TDS, $serviceData['rental_tds'], $user_id, $trans_id, $service_id, null, null, $description,$closing_bal+$serviceData['rental_incentive'],$closing_bal+$amount_this);
		                }

		                $closing_bal += $amount_this;
		            }

		            $this->Shop->shopBalanceUpdate($amt_to_be_settled, 'add', $user_id, DISTRIBUTOR);
		            if($trans_id === false) throw new Exception('Cannot create a txn here');


		            $balance += $amt_to_be_settled;
		        }

		        if($amount > 0){
		            $MsgTemplate = $this->General->LoadApiBalance();
		            $content =  $MsgTemplate['Distributor_Commission_MSG'];
		            $sms   = $this->General->ReplaceMultiWord(array('AMOUNT'=>$amount,'MID_MSG'=>$msg,'BALANCE'=>$balance),$content);
		            $this->General->sendMessage($mobile,$sms,'shops');
		        }

		    }
		    catch(Exception $e){
		        return json_decode($e->getMessage());
		    }
		}


		public function getCrons(){
		    $this->autoRender = false;

		    $dists = $this->Slaves->query("SELECT id,minute,hour,day_of_month,month,day_of_week,url FROM cron_master WHERE isactive=1");

		    $crons = array();
		    foreach($dists as $dist){

		        $ret = $this->checkExpr($dist['cron_master']['minute'], date('i'));
		        if(!$ret)continue;

		        $ret = $this->checkExpr($dist['cron_master']['hour'], date('H'));
		        if(!$ret)continue;

		        $ret = $this->checkExpr($dist['cron_master']['month'], date('m'));
		        if(!$ret)continue;

		        $ret = $this->checkExpr($dist['cron_master']['day_of_month'], date('d'));
		        if(!$ret)continue;

		        $ret = $this->checkExpr($dist['cron_master']['day_of_week'], date('w'));
		        if(!$ret)continue;

		        $id = $dist['cron_master']['id'];
		        $crons[$id] = $dist['cron_master']['url'];
		    }
		    return json_encode($crons);

		}


private function checkExpr($exp, $value){
        $value = (int)$value;
        $exp = trim($exp);
        $vals = explode("/", $exp);
        $res = 1;
        $values = array();

        $every = isset($vals[1]) ? trim($vals[1]) : 1;
        if($vals[0] == '*'){
            $res = $value % $every;
        }
        else if(strpos($vals[0], '-') !== false){
            $range = explode("-", $vals[0]);
            if(count($range) > 1){
                $min = (int)$range[0];
                $max = (int)$range[1];

                if($value >= $min && $value <= $max){
                    $res = ($value - $min) % $every;
                }
            }
        }
        else{
            $vals[0] = str_replace(' ', '', $vals[0]);
            $values = explode(",", $vals[0]);
        }

        if($res == 0 || in_array($value, $values)){
            return true;
        }
        return false;
    }


		function reQuery(){
		    $this->autoRender = false;

		    $redisObj = $this->Shop->redis_connector();

		    while(true){
		        try{
		            $query = $redisObj->rpop("FAILED_QUERY");
		            if($query === NULL){
		                sleep(2);
		                break;
		            }
		            if( ! $this->Retailer->query($query)){
		                sleep(2);
		                $this->Recharge->reQuery($query);
		            }

		        }
		        catch(Exception $ex){
		            logData("redis.txt", 'Retrying to connect redis server');
		            sleep(10);
		            $redisObj = $this->Shop->redis_connector();
		            continue;
		        }
		    }
		}


                function julyCampaign() {

//                        $data = $this->Slaves->query("SELECT retailer_id, retailers.mobile, avg(sale)*30*1.3 sale FROM retailers_logs USE INDEX(idx_date) JOIN retailers ON (retailers_logs.retailer_id = retailers.id AND retailers.parent_id = 1) where date >= '2017-06-01' AND date < '2017-07-01' GROUP BY retailer_id HAVING sum(sale) > 500");
                        $data = $this->Slaves->query("SELECT r.id AS retailer_id, r.mobile, AVG(SUM(amount))*30*1.3 sale "
                                . "FROM retailer_earning_logs rel "
                                . "JOIN retailers r ON (rel.ret_user_id = r.user_id AND r.parent_id = 1) "
                                . "WHERE rel.date >= '2017-06-01' "
                                . "AND rel.date < '2017-07-01' "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "GROUP BY r.id "
                                . "HAVING SUM(rel.amount) > 500");

                        foreach($data as $d) {
                                $hindi_sms   = "MONSOON OFFER, Pay1 par zyada recharges kare, July month me " . ceil($d[0]['sale']) . " ka business kare aur paye 0.2% extra commission pure month ke sale pe. Happy Recharging";
                                $english_sms = "MONSOON OFFER, Recharge more with Pay1 and achieve the sale of " . ceil($d[0]['sale']) . " for the month of July and earn 0.2% commission. Happy Recharging";
                                $this->General->sendMessage($d['r']['mobile'],$hindi_sms,'shops');
                                $this->General->sendMessage($d['r']['mobile'],$english_sms,'shops');
                        }

                        $this->autoRender = FALSE;
                }

    /*function updateRetailerEarningLogs($date = NULL)
    {
        ini_set("memory_limit", "1024M");
        $this->autoRender = false;
        $date = empty($date)?date('Y-m-d', strtotime('-1 day')):$date;

        Configure::load('product_config');
        $services=Configure::read('services');
        $invoiceDescriptions=Configure::read('invoiceDescriptions');

        //Get total sale and retailer commission of service ids 1,2,5,7 i.e P2P services
        $get_retailer_data = "SELECT SUM(st.amount) as total_sale,SUM(st.retailer_margin) as ret_commission, st.api_flag, r.user_id, p.id, p.name, p.service_id,p.type,vc.type "
            . "FROM vendors_activations st USE INDEX (idx_ret_date) "
            . "JOIN retailers r "
            . "ON (st.retailer_id = r.id) "
            . "JOIN vendors_commissions vc "
            . "ON (st.vendor_id=vc.vendor_id AND st.product_id=vc.product_id) "
            . "JOIN products p "
            . "ON (st.product_id = p.id) "
            . "WHERE 1 "
            . "AND p.service_id IN (1,2,4,5,6,7) "
            . "AND st.status NOT IN (2,3) "
            . "AND st.date = '$date' "
            . "GROUP BY r.user_id,p.id,p.type,vc.type,st.api_flag";

        $retailer_data = $this->Slaves->query($get_retailer_data);

        foreach($retailer_data as $data)
        {
            $product_type = ($data['p']['type']==0 && $data['vc']['type']==0)?0:1;
            $api_flag = $data['st']['api_flag'];
            $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['ret_user_id'] = $data['r']['user_id'];
            $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['service_id'] = $data['p']['service_id'];
            $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['api_flag'] = $api_flag;

            if(!in_array($data['p']['service_id'],array(4,6)))
            {
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['product_type'] = $product_type; //0->p2p,1->p2a
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['txn_type'] = ($product_type==0)?0:1;    //0->discount,1->commission,2->service charges
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['type'] = 4;
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['amount'] += $data[0]['total_sale'];
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['earning'] += $data[0]['ret_commission'];
            }
            elseif( in_array($data['p']['service_id'],array(6)) ){ // utility total sale
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['product_type'] = 1;
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['type'][4]['type'] = 4;
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['type'][4]['txn_type'] = 2;
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['type'][4]['amount'] += $data[0]['total_sale'];
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['type'][4]['earning'] += ($data[0]['ret_commission'] * (-1));
            }
            else { //postpaid service
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['amount'] += $data[0]['total_sale'];
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['earning'] += ($data[0]['ret_commission'] * (-1));
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['type'] = 4;
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['product_type'] = $product_type;
                $response[$data['r']['user_id']][$data['p']['service_id']][$product_type][$api_flag]['txn_type'] = 2;
            }
        }

        //Get service charges of service ids 4,6 i.e postpaid n utility bill payments(P2A)
//        $get_bill_payment_charges = "SELECT st.source_id,sum(amount) as bill_payment_charges,r.user_id "
//                . "FROM shop_transactions st "
//                . "JOIN retailers r "
//                . "ON (st.source_id=r.id) "
//                . "WHERE type=".SERVICE_CHARGE." "
////                    . "AND r.parent_id NOT IN (".SAAS_DISTS.") "
//                . "AND st.date = '$date' "
//                . "GROUP BY st.source_id ";
//
//        $bill_payment_charges = $this->Slaves->query($get_bill_payment_charges);
//
//        foreach($bill_payment_charges as $data)
//        {
//            $service_id = 4;
//            $product_type = 1;
//            $api_flag = -1;
//            $response[$data['r']['user_id']][$service_id][$product_type][$api_flag]['ret_user_id'] = $data['r']['user_id'];
//            $response[$data['r']['user_id']][$service_id][$product_type][$api_flag]['service_id'] = $service_id;
//            $response[$data['r']['user_id']][$service_id][$product_type][$api_flag]['product_type'] = 1;
//            $response[$data['r']['user_id']][$service_id][$product_type][$api_flag]['earning'] = $data[0]['bill_payment_charges'];
//            $response[$data['r']['user_id']][$service_id][$product_type][$api_flag]['txn_type'] = 2;
//            $response[$data['r']['user_id']][$service_id][$product_type][$api_flag]['type'] = 4;
//        }

        //retailer incentives
        $get_ret_incentives = "SELECT st.source_id as user_id,SUM(st.amount) as amount,st.type " //source_id is userid
            . "FROM shop_transactions st "
            . "JOIN retailers r "
            . "ON (st.source_id=r.user_id) "
            . "WHERE st.type=".REFUND." "
            . "AND st.confirm_flag = 0 "
            . "AND st.date = '$date' "
            . "GROUP BY st.source_id";

        $ret_incentives = $this->Slaves->query($get_ret_incentives);
        foreach($ret_incentives as $data)
        {
            $service_id = 0;
            $product_type = 1;
            $api_flag = -1;
            $response[$data['st']['user_id']][$service_id][$product_type][$api_flag]['ret_user_id'] = $data['st']['user_id'];
            $response[$data['st']['user_id']][$service_id][$product_type][$api_flag]['service_id'] = $service_id;
            $response[$data['st']['user_id']][$service_id][$product_type][$api_flag]['product_type'] = 1;
            $response[$data['st']['user_id']][$service_id][$product_type][$api_flag]['txn_type'] = 1;
            $response[$data['st']['user_id']][$service_id][$product_type][$api_flag]['amount'] = $data[0]['amount'];
            $response[$data['st']['user_id']][$service_id][$product_type][$api_flag]['earning'] = 0;
            $response[$data['st']['user_id']][$service_id][$product_type][$api_flag]['type'] = $data['st']['type'];
            $response[$data['st']['user_id']][$service_id][$product_type][$api_flag]['api_flag'] = $api_flag;
        }

        //Get service charges of service ids 8,9,10,12 i.e. smartpay services n DMT(P2A),rental and incentives

        $service_charges = "SELECT st.source_id,st.user_id as service_id,sum(st.amount) as amount,st.type "
                        . "FROM shop_transactions st "
                        . "JOIN retailers r "
                        . "ON (st.source_id=r.user_id) "
                        . "WHERE st.type IN (".SERVICECHARGES.",".RENTAL.",".DEBIT_NOTE.",".CREDIT_NOTE.") "
                        . "AND st.confirm_flag = 0 "
                        . "AND st.type_flag in (0,1) "
                        . "and st.date = '$date' "
//                            . "AND r.parent_id NOT IN (".SAAS_DISTS.") "
                        . "GROUP BY st.source_id,st.user_id,st.type";

        $ret_service_charges = $this->Slaves->query($service_charges);

        foreach ($ret_service_charges as $data)
        {
            $service_id = $data['st']['service_id'];
            $product_type = 1;
            $api_flag = -1;
            $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['ret_user_id'] = $data['st']['source_id'];
            $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['service_id'] = $service_id;
            $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['product_type'] = 1; // p2a
            $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['api_flag'] = $api_flag;

            switch ($service_id) {
                case 6:
                    if(in_array($data['st']['type'], array(DEBIT_NOTE)) ){
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['type'] = $data['st']['type'];
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['txn_type'] = 1;
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['amount'] = $data[0]['amount'];

                    }
                    $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['earning'] = 0;
                    break;

                default:
                case 8:

                    if( in_array($data['st']['type'], array(RENTAL)) ){
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['txn_types'][1]['txn_type'] = 1;
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['txn_types'][1]['amount'] = $data[0]['amount'];
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['txn_types'][1]['type'] = $data['st']['type'];
                    } else {
                        if( in_array($data['st']['type'], array(CREDIT_NOTE)) ){
                            $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['txn_types'][2]['amount'] = $data[0]['amount'];
                            $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['txn_types'][2]['type'] = $data['st']['type'];
                            $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['txn_types'][2]['txn_type'] = 2;
                        }
                        if( in_array($data['st']['type'], array(SERVICECHARGES)) ){
                            $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['txn_types'][2]['earning'] = $data[0]['amount'];
                        }

                    }

                    break;
                case 10:
                    if( in_array($data['st']['type'], array(RENTAL,DEBIT_NOTE,CREDIT_NOTE)) ){
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['amount'] = $data[0]['amount'];
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['txn_type'] = 1;
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['type'] = $data['st']['type'];
                    }

                    break;
                case 11:
                    if(in_array($data['st']['type'], array(DEBIT_NOTE,CREDIT_NOTE)) ){
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['amount'] = $data[0]['amount'];
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['txn_type'] = 2;
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['type'] = $data['st']['type'];
                    }
                    if( in_array($data['st']['type'], array(SERVICECHARGES)) ){
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'][$data['st']['type']]['earning'] = $data[0]['amount'];
                    }
                    break;

                default:
                case 12:
                    $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['txn_type'] = 2;
                    $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'] = 16;
                    if( in_array($data['st']['type'], array(DEBIT_NOTE)) ){
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['amount'] = $data[0]['amount'];
                    }
                    if( in_array($data['st']['type'], array(SERVICECHARGES)) ){
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['earning'] = $data[0]['amount'];
                    }
                    break;
                case 13:
                    $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['txn_type'] = 2;
                    $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['type'] = 16;
                    if( in_array($data['st']['type'], array(DEBIT_NOTE)) ){
                        $response[$data['st']['source_id']][$service_id][$product_type][$api_flag]['amount'] = $data[0]['amount'];
                    }

                    break;

                default:
                break;
            }
        }

        $get_dist_user_ids = "SELECT d.user_id,r.user_id "
                . "FROM distributors d "
                . "JOIN retailers r "
                . "ON (d.id=r.parent_id) "
                . "WHERE r.user_id IN (".implode(',', array_keys($response)).") ";

        $dist_user_ids = $this->Slaves->query($get_dist_user_ids);

        foreach ($dist_user_ids as $data)
        {
            $response[$data['r']['user_id']]['dist_user_id'] = $data['d']['user_id'];
        }

        $i=1;
        $batch_qry = array();

        foreach ($response as $ret_user_id => $earning_data)
        {
            $dist_user_id = $earning_data['dist_user_id'];
            unset($earning_data['dist_user_id']);

            foreach($earning_data as $service_id => $servicewise_data)
            {
                foreach($servicewise_data as $product_type => $prod_data)
                {
                    foreach($prod_data as $app_type => $data)
                    {
                        $product_type = $data['product_type'];

                        $batch_qry['type'] = 'INSERT';
                        $batch_qry['predata'] = 'INSERT INTO retailer_earning_logs(date,ret_user_id,dist_user_id,service_id,product_type,amount,earning,txn_type,type,api_flag)VALUES';

                        if(!in_array($service_id, array(6,8,10,11)))
                        {
                            $type = isset($data['type'])?$data['type']:0;
                            $amount = $data['amount'];
                            $earning = $data['earning'];
                            $txn_type = $data['txn_type'];

                            $batch_qry['data'][] = '("'.$date.'","'.$ret_user_id.'","'.$dist_user_id.'","'.$service_id.'",'
                                                    . '"'.$product_type.'","'.$amount.'","'.$earning.'","'.$txn_type.'","'.$type.'","'.$app_type.'")';

                            if (($i % 100) == 0){ //process batch of 100 queries together
                            $this->run_batch($batch_qry);
                            $batch_qry = array();
                            }
                            $i++;
                        }
                        else
                        {
                            if(array_key_exists('type', $data))
                            {
                                foreach ($data['type'] as $type => $txn_data)
                                {
                                    $amount = $txn_data['amount'];
                                    $earning = $txn_data['earning'];
                                    $txn_type = $txn_data['txn_type'];
                                    $type = $txn_data['type'];

                                    $batch_qry['data'][] = '("'.$date.'","'.$ret_user_id.'","'.$dist_user_id.'","'.$service_id.'",'
                                                    . '"'.$product_type.'","'.$amount.'","'.$earning.'","'.$txn_type.'","'.$type.'","'.$app_type.'")';

                                    if (($i % 100) == 0){ //process batch of 100 queries together
                                    $this->run_batch($batch_qry);
                                    $batch_qry = array();
                                    }
                                    $i++;
                                }
                            }

                            if(array_key_exists('txn_types', $data))
                            {
                                foreach ($data['txn_types'] as $txn_type => $mpos_data)
                                {
                                    $amount = $mpos_data['amount'];
                                    $txn_type = $mpos_data['txn_type'];
                                    $type = $mpos_data['type'];
                                    $earning = ($txn_type == 2)?$mpos_data['earning']:0;

                                    $batch_qry['data'][] = '("'.$date.'","'.$ret_user_id.'","'.$dist_user_id.'","'.$service_id.'",'
                                                    . '"'.$product_type.'","'.$amount.'","'.$earning.'","'.$txn_type.'","'.$type.'","'.$app_type.'")';

                                    if (($i % 100) == 0){ //process batch of 100 queries together
                                    $this->run_batch($batch_qry);
                                    $batch_qry = array();
                                    }
                                    $i++;
                                }
                            }
                        }
                    }
                }
            }
        }

        if(!empty($batch_qry)) // handled cases if last batch consist less then 100 queries
        {
            $this->run_batch($batch_qry);
            $batch_qry = array();
        }
    }*/

    function updateRetailerEarningLogs($date = NULL,$flag = 0)
    {
        $this->autoRender = false;
        $date = empty($date)?date('Y-m-d') : ($date == 1?date('Y-m-d',strtotime('-1 days')):$date);
        $tbl1 = $flag == 0?'vendors_activations':'vendors_activations_logs';
        $tbl2 = $flag == 0?'shop_transactions':'shop_transactions_logs';

        //Get total sale and retailer commission of service ids 1,2,5,7 i.e P2P services
        $retailer_data = $this->Slaves->query('SELECT st.date,st.reversal_date,if((st.reversal_date IS NULL OR st.reversal_date = "0000-00-00"),COUNT(st.id),0) AS txn_count,SUM(if((st.reversal_date IS NULL OR st.reversal_date = "0000-00-00"),st.amount,0)) as total_sale,SUM(if((st.reversal_date IS NULL OR st.reversal_date = "0000-00-00"),st.retailer_margin,0)) as ret_commission,SUM(if((st.reversal_date IS NULL OR st.reversal_date = "0000-00-00"),s.amount,0)) AS tds, st.api_flag, r.user_id, p.id, p.name, p.service_id,p.type,p.earning_type,p.earning_type_flag,vc.type,p.expected_earning_margin'
                                    . ',if(((st.reversal_date IS NOT NULL AND st.reversal_date != st.date) OR st.reversal_date IS NULL),COUNT(st.txn_id),0) as closing_txn_count,SUM(if(((st.reversal_date IS NOT NULL AND st.reversal_date != st.date) OR st.reversal_date IS NULL),st.amount,0)) as closing '
                                    . 'FROM '.$tbl1.' st '
                                    . 'JOIN retailers r ON (st.retailer_id = r.id) '
                                    . 'JOIN vendors_commissions vc ON (st.vendor_id = vc.vendor_id AND st.product_id = vc.product_id) '
                                    . 'JOIN products p ON (st.product_id = p.id) '
                                    . 'LEFT JOIN '.$tbl2.' s ON (st.shop_transaction_id = s.target_id AND s.type = '.TDS.') '
                                    . 'WHERE 1 '
                                    . 'AND st.date = "'.$date.'" '
                                    . 'GROUP BY r.user_id,p.id,p.service_id,vc.type,st.api_flag,st.reversal_date');
                            
        foreach($retailer_data as $data)
        {
            if($data['st']['date'] != $data['st']['reversal_date']){
                $earning_type = $data['vc']['type'] == 1 && $data['p']['earning_type'] == 0?1:$data['p']['earning_type'];
                $product_type = $data['vc']['type'] == 1 && $data['p']['type'] == 0?1:$data['p']['type'];
                $api_flag = $data['st']['api_flag'];
                $key = $data['r']['user_id'].'_'.$data['p']['service_id'].'_'.$api_flag.'_'.DEBIT_NOTE.'_'.$product_type.'_'.$data['p']['earning_type_flag'];
                $response[$data['r']['user_id']][$key]['ret_user_id'] = $data['r']['user_id'];
                $response[$data['r']['user_id']][$key]['service_id'] = $data['p']['service_id'];
                $response[$data['r']['user_id']][$key]['api_flag'] = $api_flag;
                $response[$data['r']['user_id']][$key]['type'] = DEBIT_NOTE;
                $response[$data['r']['user_id']][$key]['txn_type'] = $earning_type;    //0->discount,1->commission,2->service charges
                $response[$data['r']['user_id']][$key]['product_type'] = $product_type;    //0->p2p,1->p2a
                $response[$data['r']['user_id']][$key]['txn_type_flag'] = $data['p']['earning_type_flag'];    //For commission - 0->commission,1->referral fee
                $response[$data['r']['user_id']][$key]['amount'] += $data[0]['total_sale'];
                $response[$data['r']['user_id']][$key]['service_charge'] += ($earning_type == 2)?abs($data[0]['ret_commission']):0;
                $response[$data['r']['user_id']][$key]['commission'] += in_array($earning_type,array(0,1))?abs($data[0]['ret_commission']):0;
                $response[$data['r']['user_id']][$key]['closing'] += $data[0]['closing'];
                $response[$data['r']['user_id']][$key]['earning'] += abs($data[0]['ret_commission']);
                if($earning_type == 2){ //if service charge,calculate expected earning
                    $percent_pos = strpos($data['p']['expected_earning_margin'], '%');
                    $percent = substr($data['p']['expected_earning_margin'], 0, $percent_pos);
                    $response[$data['r']['user_id']][$key]['expected_earning'] += $percent_pos !== false?($data[0]['total_sale']*$percent)/100:$data['p']['expected_earning_margin']*$data[0]['txn_count'];
                }else{
                    $response[$data['r']['user_id']][$key]['expected_earning'] += ($data[0]['ret_commission'] < 0)?($data[0]['ret_commission'] * (-1)):$data[0]['ret_commission'];
                }
                $response[$data['r']['user_id']][$key]['txn_count'] += $data[0]['txn_count'];
                $response[$data['r']['user_id']][$key]['closing_txn_count'] += $data[0]['closing_txn_count'];
                $response[$data['r']['user_id']][$key]['tds'] += $data[0]['tds'];
            }
        }
        
        $reversed_txns = $this->Slaves->query('SELECT SUM(st.amount) AS txn_reverse_amt,st.api_flag, r.user_id, p.id, p.name, p.service_id,p.type,p.earning_type,p.earning_type_flag,vc.type '
                . 'FROM '.$tbl1.' st '
                . 'JOIN retailers r ON (st.retailer_id = r.id) '
                . 'LEFT JOIN vendors_commissions vc ON (st.vendor_id = vc.vendor_id AND st.product_id = vc.product_id) '
                . 'JOIN products p ON (st.product_id = p.id) '
                . 'WHERE st.date != st.reversal_date '
                . 'AND st.reversal_date = "'.$date.'" '
                . 'AND st.date < "'.$date.'" '
                . 'GROUP BY r.user_id,p.id,p.service_id,vc.type,st.api_flag');

        foreach ($reversed_txns as $key => $data)
        {
            $earning_type = $data['vc']['type'] == 1 && $data['p']['earning_type'] == 0?1:$data['p']['earning_type'];
            $product_type = $data['vc']['type'] == 1 && $data['p']['type'] == 0?1:$data['p']['type'];
            $api_flag = $data['st']['api_flag'];
            $key = $data['r']['user_id'].'_'.$data['p']['service_id'].'_'.$api_flag.'_'.DEBIT_NOTE.'_'.$product_type.'_'.$data['p']['earning_type_flag'];
            $response[$data['r']['user_id']][$key]['service_id'] = $data['p']['service_id'];
            $response[$data['r']['user_id']][$key]['api_flag'] = $api_flag;
            $response[$data['r']['user_id']][$key]['txn_type'] = $earning_type;
            $response[$data['r']['user_id']][$key]['product_type'] = $product_type;    //0->p2p,1->p2a
            $response[$data['r']['user_id']][$key]['txn_type_flag'] = $data['p']['earning_type_flag'];
            $response[$data['r']['user_id']][$key]['type'] = DEBIT_NOTE;
            $response[$data['r']['user_id']][$key]['txn_reverse_amt'] = $data[0]['txn_reverse_amt'];
        }
        
        $ret_debit_credit_data = $this->Slaves->query('SELECT wt.user_id,wt.date,wt.reversal_date,if((wt.reversal_date IS NULL OR wt.reversal_date = "0000-00-00"),COUNT(wt.txn_id),0) AS txn_count,SUM(if((wt.reversal_date IS NULL OR wt.reversal_date = "0000-00-00"),wt.amount,0)) AS amount,SUM(if((wt.reversal_date IS NULL OR wt.reversal_date = "0000-00-00"),wt.amount-wt.amount_settled,0)) AS earning,SUM(if((wt.reversal_date IS NULL OR wt.reversal_date = "0000-00-00"),st.amount,0)) AS tds,p.service_id,p.earning_type,p.earning_type_flag,wt.source,wt.cr_db,wt.settlement_mode,if(wt.settlement_mode = 1,SUM(if((wt.reversal_date IS NULL OR wt.reversal_date = "0000-00-00"),wt.amount,0)),0) AS bank_settlement,p.expected_earning_margin '
                        . ',if(((wt.reversal_date IS NOT NULL AND wt.reversal_date != wt.date) OR wt.reversal_date IS NULL OR wt.reversal_date = "0000-00-00"),COUNT(wt.txn_id),0) as closing_txn_count,SUM(if(((wt.reversal_date IS NOT NULL AND wt.reversal_date != wt.date) OR wt.reversal_date IS NULL OR wt.reversal_date = "0000-00-00"),wt.amount,0)) as closing,SUM(if(((wt.reversal_date IS NULL OR wt.reversal_date = "0000-00-00") AND p.id NOT IN (84,215)),service_charge,0)) AS service_charge,SUM(if((wt.reversal_date IS NULL OR wt.reversal_date = "0000-00-00"),commission,0)) AS commission,p.type '
                        . 'FROM wallets_transactions wt '
                        . 'LEFT JOIN '.$tbl2.' st ON (wt.shop_transaction_id = st.target_id AND st.type = '.TDS.') '
                        . 'JOIN products p ON (wt.product_id = p.id) '
                        . 'WHERE wt.date = "'.$date.'" '
                        . 'GROUP BY wt.user_id,p.id,p.service_id,wt.cr_db,wt.source,wt.settlement_mode,wt.reversal_date');

        foreach ($ret_debit_credit_data as $data)
        {
            if($data['wt']['date'] != $data['wt']['reversal_date']){
                $shop_txn_type = $data['wt']['cr_db'] == 'db'?DEBIT_NOTE:CREDIT_NOTE;
                $product_type = $data['p']['type'];
                $key = $data['wt']['user_id'].'_'.$data['p']['service_id'].'_'.$data['wt']['source'].'_'.$shop_txn_type.'_'.$product_type.'_'.$data['p']['earning_type_flag'];
                $response[$data['wt']['user_id']][$key]['ret_user_id'] = $data['wt']['user_id'];
                $response[$data['wt']['user_id']][$key]['service_id'] = $data['p']['service_id'];
                $response[$data['wt']['user_id']][$key]['api_flag'] = $data['wt']['source'];
                $response[$data['wt']['user_id']][$key]['amount'] += $data[0]['amount'];    
                $response[$data['wt']['user_id']][$key]['service_charge'] += abs($data[0]['service_charge']);
                $response[$data['wt']['user_id']][$key]['commission'] += abs($data[0]['commission']);
                $response[$data['wt']['user_id']][$key]['earning'] += abs($data[0]['commission']-$data[0]['service_charge']);
                if($data['p']['earning_type'] == 2){
                    $percent_pos = strpos($data['p']['expected_earning_margin'], '%');
                    $percent = substr($data['p']['expected_earning_margin'], 0, $percent_pos);
                    $response[$data['wt']['user_id']][$key]['expected_earning'] += $percent_pos !== false?($data[0]['amount']*$percent)/100:$data['p']['expected_earning_margin']*$data[0]['txn_count'];
                }else{
                    $response[$data['wt']['user_id']][$key]['expected_earning'] += $data[0]['earning'];
                }
                $response[$data['wt']['user_id']][$key]['tds'] += $data[0]['tds'];
                $response[$data['wt']['user_id']][$key]['bank_settlement'] += $data[0]['bank_settlement'];
                $response[$data['wt']['user_id']][$key]['product_type'] = $product_type;
                $response[$data['wt']['user_id']][$key]['txn_type'] = $data['p']['earning_type'];
                $response[$data['wt']['user_id']][$key]['txn_type_flag'] = $data['p']['earning_type_flag'];
                $response[$data['wt']['user_id']][$key]['type'] = $shop_txn_type;
                $response[$data['wt']['user_id']][$key]['txn_count'] += $data[0]['txn_count'];
                $response[$data['wt']['user_id']][$key]['closing_txn_count'] += $data[0]['closing_txn_count'];
                $response[$data['wt']['user_id']][$key]['closing'] += $data[0]['closing'];
            }
        }
        
        $reversed_trans = $this->Slaves->query('SELECT wt.user_id,SUM(wt.amount) AS txn_reversed,p.service_id,p.earning_type,p.earning_type_flag,wt.source,wt.cr_db,wt.settlement_mode,if(wt.settlement_mode = 1,SUM(wt.amount),0) AS bank_settlement,p.type '
                        . 'FROM wallets_transactions wt '
                        . 'JOIN products p ON (wt.product_id = p.id) '
                        . 'WHERE wt.date != wt.reversal_date '
                        . 'AND wt.reversal_date = "'.$date.'" '
                        . 'AND date < "'.$date.'" '
                        . 'GROUP BY wt.user_id,p.id,p.service_id,wt.cr_db,wt.source,wt.settlement_mode');

        foreach($reversed_trans as $data){
            $shop_txn_type = $data['wt']['cr_db'] == 'db'?DEBIT_NOTE:CREDIT_NOTE;
            $product_type = $data['p']['type'];
            $key = $data['wt']['user_id'].'_'.$data['p']['service_id'].'_'.$data['wt']['source'].'_'.$shop_txn_type.'_'.$product_type.'_'.$data['p']['earning_type_flag'];
            $response[$data['wt']['user_id']][$key]['service_id'] = $data['p']['service_id'];
            $response[$data['wt']['user_id']][$key]['api_flag'] = $data['wt']['source'];
            $response[$data['wt']['user_id']][$key]['product_type'] = $product_type;
            $response[$data['wt']['user_id']][$key]['txn_type'] = $data['p']['earning_type'];
            $response[$data['wt']['user_id']][$key]['txn_type_flag'] = $data['p']['earning_type_flag'];
            $response[$data['wt']['user_id']][$key]['type'] = $shop_txn_type;
            $response[$data['wt']['user_id']][$key]['bank_settlement'] += $data[0]['bank_settlement'];
            $response[$data['wt']['user_id']][$key]['txn_reverse_amt'] += $data[0]['txn_reversed'];
        }
        
        $txn_cancel_refund = $this->Slaves->query('SELECT st.source_id as user_id,p.id,p.service_id,SUM(st.amount) as txn_cancel_refund,wt.cr_db,wt.source,p.earning_type,p.earning_type_flag,wt.settlement_mode,p.type '
                . 'FROM shop_transactions st '
                . 'JOIN wallets_transactions wt ON (st.target_id = wt.shop_transaction_id) '
                . 'JOIN products p ON (wt.product_id = p.id) '
                . 'WHERE st.type = '.TXN_CANCEL_REFUND.' '
                . 'AND st.date = "'.$date.'" '
                . 'GROUP BY st.source_id,p.id,p.service_id,wt.cr_db,wt.source,wt.settlement_mode');
        
        foreach ($txn_cancel_refund as $data){
            $shop_txn_type = $data['wt']['cr_db'] == 'db'?DEBIT_NOTE:CREDIT_NOTE;
            $product_type = $data['p']['type'];
            $key = $data['st']['user_id'].'_'.$data['p']['service_id'].'_'.$data['wt']['source'].'_'.$shop_txn_type.'_'.$product_type.'_'.$data['p']['earning_type_flag'];
            $response[$data['st']['user_id']][$key]['service_id'] = $data['p']['service_id'];
            $response[$data['st']['user_id']][$key]['api_flag'] = $data['wt']['source'];
            $response[$data['st']['user_id']][$key]['product_type'] = $product_type;
            $response[$data['st']['user_id']][$key]['txn_type'] = $data['p']['earning_type'];
            $response[$data['st']['user_id']][$key]['txn_type_flag'] = $data['p']['earning_type_flag'];
            $response[$data['st']['user_id']][$key]['type'] = $shop_txn_type;
            $response[$data['st']['user_id']][$key]['txn_reverse_amt'] += $data[0]['txn_cancel_refund'];
        }
           
        $cancellation_charges = $this->Slaves->query('SELECT wt.user_id,p.service_id,s.name as service_name,s.parent_id,SUM(wc.amount_refunded) AS amount_refunded,SUM(wc.commission_refund) AS commission_refund,SUM(wc.cancellation_charges) AS cancellation_charges,wt.cr_db,wt.source,p.earning_type,p.earning_type_flag,wt.settlement_mode,p.type '
                . 'FROM wallet_partial_cancellations wc '
                . 'JOIN wallets_transactions wt ON (wc.txn_id = wt.txn_id) '
                . 'JOIN products p ON (wc.product_id = p.id) '
                . 'LEFT JOIN services s ON (p.service_id = s.id) '
                . 'WHERE 1 '
                . 'AND wc.date = "'.$date.'" '
                . 'GROUP BY wt.user_id,p.id,p.service_id,wt.cr_db,wt.source,wt.settlement_mode');
        
        foreach($cancellation_charges as $data){
            $shop_txn_type = $data['wt']['cr_db'] == 'db'?DEBIT_NOTE:CREDIT_NOTE;
            $product_type = $data['p']['type'];
            $key = $data['wt']['user_id'].'_'.$data['p']['service_id'].'_'.$data['wt']['source'].'_'.$shop_txn_type.'_'.$product_type.'_'.$data['p']['earning_type_flag'];
            $response[$data['wt']['user_id']][$key]['service_id'] = $data['p']['service_id'];
            $response[$data['wt']['user_id']][$key]['api_flag'] = $data['wt']['source'];
            $response[$data['wt']['user_id']][$key]['product_type'] = $product_type;
            $response[$data['wt']['user_id']][$key]['txn_type'] = $data['p']['earning_type'];
            $response[$data['wt']['user_id']][$key]['txn_type_flag'] = $data['p']['earning_type_flag'];
            $response[$data['wt']['user_id']][$key]['type'] = $shop_txn_type;
            $response[$data['wt']['user_id']][$key]['amount'] -= $data[0]['amount_refunded'];
            $response[$data['wt']['user_id']][$key]['commission'] -= $data[0]['commission_refund'];
            $response[$data['wt']['user_id']][$key]['earning'] -= $data[0]['commission_refund'];
            $response[$data['wt']['user_id']][$key]['tds'] -= $this->Shop->calculateTDS($data[0]['commission_refund']);
            $response[$data['wt']['user_id']][$key]['cancellation_charges'] += $data[0]['cancellation_charges'];
        }
        
        $get_dist_user_ids = 'SELECT d.user_id,r.user_id '
                . 'FROM distributors d '
                . 'JOIN retailers r ON (d.id = r.parent_id) '
                . 'WHERE r.user_id IN ('.implode(',', array_keys($response)).') ';

        $dist_user_ids = $this->Slaves->query($get_dist_user_ids);

        foreach ($dist_user_ids as $data)
        {
            $response[$data['r']['user_id']]['dist_user_id'] = $data['d']['user_id'];
        }

        $i=1;
        $batch_qry = array();
        
        if($date != date('Y-m-d')){
            $this->Retailer->query("DELETE FROM retailer_earning_logs where date='$date'");
        }

        foreach ($response as $ret_user_id => $earning_data)
        {
            $dist_user_id = $earning_data['dist_user_id'];
            unset($earning_data['dist_user_id']);
            foreach($earning_data as $data)
            {
                $bank_settlement = isset($data['bank_settlement']) && !empty($data['bank_settlement'])?$data['bank_settlement']:0;
                $batch_qry['type'] = 'INSERT';
                $batch_qry['predata'] = 'REPLACE INTO retailer_earning_logs(date,ret_user_id,dist_user_id,service_id,amount,earning,expected_earning,service_charge,commission,cancellation_charges,tds,bank_settlement,txn_type,txn_type_flag,type,txn_reverse_amt,api_flag,txn_count,closing_txn_count,closing_amt)VALUES';

                $batch_qry['data'][] = '("'.$date.'","'.$ret_user_id.'","'.$dist_user_id.'","'.$data['service_id'].'",'
                                        . '"'.$data['amount'].'","'.$data['earning'].'","'.$data['expected_earning'].'","'.$data['service_charge'].'","'.$data['commission'].'","'.$data['cancellation_charges'].'","'.$data['tds'].'","'.$bank_settlement.'","'.$data['product_type'].'","'.$data['txn_type_flag'].'","'.$data['type'].'","'.$data['txn_reverse_amt'].'","'.$data['api_flag'].'","'.$data['txn_count'].'","'.$data['closing_txn_count'].'","'.$data['closing'].'")';

                if (($i % 100) == 0){ //process batch of 100 queries together
                $this->run_batch($batch_qry);
                $batch_qry = array();
                }
                $i++;
            }
        }

        if(!empty($batch_qry)) // handled cases if last batch consist less then 100 queries
        {
            $this->run_batch($batch_qry);
            $batch_qry = array();
        }
    }

    function checkDishTvTxn(){
        $this->autoRender = false;
        $TPS_REQUEST_HASH = "TPS_DISHTV_DATA";
        try{
            $redisObj = $this->Shop->redis_connector();
            if($redisObj == false){
                throw new Exception("cannot create redis object");
            }
            else{
                $resquest_data = $redisObj->hgetall($TPS_REQUEST_HASH);

                foreach($resquest_data as $key => $req){
                    $req= json_decode($req,true);
                    $request_id = $req['txn_id'];
                    $ttl = $redisObj->ttl("EXP_".$req['txn_id']);
                    $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": processing txn here : " . $request_id);
                    if($ttl < 300){
                        $data = $this->Retailer->query("SELECT * FROM vendors_activations where txn_id = '$request_id' AND status in (0,4) AND vendor_id = 0");
                        if(!empty($data)){
                            $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": processing txn:: locking it : " . $request_id);

                            if(!$this->Recharge->lockTransaction($request_id)) continue;
                            $redisObj->hdel($TPS_REQUEST_HASH, $request_id);

                            $this->Recharge->unlockTransaction($request_id);
                            $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": processing txn:: finally processing : " . $request_id);

                            $this->Recharge->send_request_via_tps($request_id, $req['prod_id'], $req['service_id'], $req['params'], $req['data']);
                        }

                        if($ttl <= 0){
                            $redisObj->hdel($TPS_REQUEST_HASH, $request_id);
                        }
                    }
                }
            }
        }
        catch(Exception $ex){
            $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": not inserted data : " . $request_id);
        }
    }

    function updateRetailerLocation($last_days = 1) {

            $this->autoRender = FALSE;
            //return;

            $this->Retailer->query("INSERT IGNORE INTO retailers_location (retailer_id,user_id) SELECT id,user_id FROM retailers ORDER BY id ASC");

            $data = $this->Slaves->query("SELECT retailers.id, user_profile.area_id, user_profile.user_id,user_profile.latitude, user_profile.longitude, user_profile.updated
                                    FROM user_profile
                                    JOIN (
                                            SELECT user_id, MAX(updated) updated
                                            FROM user_profile
                                            WHERE user_profile.latitude != 0 AND user_profile.longitude != 0 AND user_profile.area_id > 0 AND user_profile.date >= '".date('Y-m-d',strtotime('- 1 month'))."'
                                            GROUP BY user_id
                                        ) a
                                    ON (user_profile.user_id = a.user_id AND user_profile.updated = a.updated)
                                    JOIN retailers ON (user_profile.user_id = retailers.user_id)
                                    WHERE user_profile.latitude != 0 AND user_profile.longitude != 0 AND user_profile.area_id != 0 AND user_profile.date >= '".date('Y-m-d',strtotime('-'.$last_days. 'days'))."'");

            $data = array_chunk($data, 100);

            foreach ($data as $d_m) {
                    $query = "UPDATE retailers_location SET ";
                    $area = "area_id = (CASE retailer_id ";
                    $latitude = "latitude = (CASE retailer_id ";
                    $longitude = "longitude = (CASE retailer_id ";
                    $updated = "updated = (CASE retailer_id ";
                    $user_id = "user_id = (CASE retailer_id ";

                    $retailers = array();
                    foreach ($d_m as $d) {
                            $area .= "WHEN '{$d['retailers']['id']}' THEN '{$d['user_profile']['area_id']}' ";
                            $latitude .= "WHEN '{$d['retailers']['id']}' THEN '{$d['user_profile']['latitude']}' ";
                            $longitude .= "WHEN '{$d['retailers']['id']}' THEN '{$d['user_profile']['longitude']}' ";
                            $updated .= "WHEN '{$d['retailers']['id']}' THEN '".date('Y-m-d', strtotime($d['user_profile']['updated']))."' ";
                            $user_id .= "WHEN '{$d['retailers']['id']}' THEN '{$d['user_profile']['user_id']}' ";

                            $retailers[] = $d['retailers']['id'];
                    }

                    $area .= "END), ";
                    $latitude .= "END), ";
                    $longitude .= "END), ";
                    $updated .= "END) ";
                    $query .= $area . $latitude . $longitude . $updated . "WHERE verified = 0 AND retailer_id IN (". implode(',', $retailers).")";

                  //  $this->Retailer->query($query);
            }


            $data = $this->Retailer->query("SELECT area_id FROM retailers_location where area_id > 0 and updated > '".date('Y-m-d',strtotime('-1 month'))."' group by area_id");

            $this->Retailer->query("UPDATE locator_area set toshow = 0");
            $this->Retailer->query("UPDATE locator_city set toshow = 0");
            $this->Retailer->query("UPDATE locator_state set toshow = 0");

            $areas = array();
            foreach($data as $dt){
                $areas[] = $dt['retailers_location']['area_id'];
            }

            $areas_chunk = array_chunk($areas, 1000);
            foreach($areas_chunk as $area_chunk){
                $this->Retailer->query("UPDATE locator_area set toshow = 1 WHERE id in (".implode(",",$area_chunk).")");
            }

            $this->Retailer->query("UPDATE locator_city left join locator_area ON (city_id = locator_city.id) set locator_city.toshow = 1 WHERE locator_area.toShow = 1");
            $this->Retailer->query("UPDATE locator_state left join locator_city ON (state_id = locator_state.id) set locator_state.toshow = 1 WHERE locator_city.toShow = 1");


    }

    function updateApiVendorsSalesData($date = NULL,$flag = NULL)
    {
        $this->autoRender = FALSE;

        $date = empty($date)?date('Y-m-d', strtotime('-1 days')):$date;

        $tbl = ($flag == 1)?'vendors_activations_logs':'vendors_activations';

        $api_vendor_query = "SELECT v.id as vendor_id,svm.supplier_id,p.id as product_id,p.service_id,p.name,p.type,vc.type,SUM(va.amount) as sale,SUM(va.amount*va.discount_commission/100) as commission "
                . "FROM ".$tbl." va "
                . "JOIN vendors v ON (v.id = va.vendor_id) "
                . "JOIN vendors_commissions vc ON (va.vendor_id = vc.vendor_id AND va.product_id = vc.product_id) "
                . "JOIN products p ON (p.id = va.product_id) "
                . "JOIN inv_supplier_vendor_mapping svm ON (va.vendor_id = svm.vendor_id) "
//                . "JOIN inv_supplier_operator so ON (so.supplier_id = svm.supplier_id AND so.operator_id = p.id) "
                . "JOIN inv_suppliers s ON (s.id = svm.supplier_id) "
//                . "LEFT JOIN inv_supplier_operator so ON (so.supplier_id = s.id AND so.operator_id = p.id) "
                . "WHERE v.update_flag = 0 "
                . "AND va.date = '$date' "
                . "AND va.status NOT IN (2,3) "
                . "GROUP BY svm.supplier_id,p.id,p.type,vc.type "
                . "ORDER BY svm.supplier_id,p.id";

        $api_vendor_data = $this->Slaves->query($api_vendor_query);

        $i=1;
        $batch_qry = array();

        foreach ($api_vendor_data as $data) {
            $product_type = (($data['p']['type'] == 0) && ($data['vc']['type'] == 0))?0:1;
            $batch_qry['type'] = "INSERT";
            $batch_qry['predata'] = 'INSERT INTO api_vendors_sale_data(vendor_id,product_id,supplier_id,service_id,product_type,commission,sale,date)VALUES';

            $batch_qry['data'][] = '("'.$data['v']['vendor_id'].'","'.$data['p']['product_id'].'","'.$data['svm']['supplier_id'].'","'.$data['p']['service_id'].'",'
                                            . '"'.$product_type.'","'.$data[0]['commission'].'","'.$data[0]['sale'].'","'.$date.'")';

            if (($i % 100) == 0) //process batch of 100 queries together
            {
                $this->run_batch($batch_qry);
                $batch_qry = array();
            }
            $i++;
        }

        if(!empty($batch_qry)) // handled cases if last batch consist less then 100 queries
        {
            $this->run_batch($batch_qry);
            $batch_qry = array();
        }
    }

        function ccaComplainTracking() {

                $this->autoRender = FALSE;

                $ret = $this->Api->bbpsComplaintTracking();
        }

        function sendDailyTransactionsReport($flag,$date = NULL)
        {
            $this->autoRender = FALSE;
                    ini_set("memory_limit", "2048M");
            if($flag == 1)
            {
                $date = empty($date)?date('Y-m-d', strtotime('-1 day')):$date;
            }
            else
            {
                $date = empty($date)?date('Y-m-d', strtotime('-2 days')):$date;
            }

            $query = "SELECT users.name,v.company,v.shortForm,va.distributor_id, r.name,r.shopname,r.id,r.mobile,p.name,va.mobile,va.txn_id, va.amount, va.amount*va.discount_commission/100 as comm, va.status, va.prevStatus, va.cause ,va.timestamp ,va.vendor_refid, va.api_flag, va.tran_processtime, va.updated_timestamp as updated_time, group_concat(vm.response) as causes,max(vm.timestamp) as vm_timestamp "
                    . "FROM vendors_activations va "
                    . "LEFT JOIN vendors_messages vm ON (vm.va_tran_id = va.txn_id AND va.vendor_id = vm.service_vendor_id) "
                    . "INNER JOIN retailers r ON (va.retailer_id = r.id) "
                    . "LEFT JOIN products p ON (p.id = va.product_id) "
                    . "JOIN vendors v ON (v.id = va.vendor_id) "
                    . "LEFT JOIN users ON (users.id=va.cc_userid) "
                    . "WHERE va.date = '$date' OR va.reversal_date = '$date' "
                    . "GROUP BY va.txn_id "
                    . "ORDER BY va.id ";

            $txn_data = $this->Slaves->query($query);

            $getMobileNumberingDetails = $this->Slaves->query("select number, area from mobile_operator_area_map");

            foreach($getMobileNumberingDetails as $val){
                $mobArea[$val['mobile_operator_area_map']['number']] = $val['mobile_operator_area_map']['area'];
            }

            $bucket = dailytxnsbucket;
            $document_path = $filename = '/tmp/transaction_log_'.$date . '.zip';
            $file = 'transaction_log_'.$date . '.zip';
            //create zip file
            $zip = new ZipArchive;
            $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $fd = fopen('php://temp/maxmemory:1048576', 'w');
            if (false === $fd) {
                die('Failed to create temporary file');
            }
            $headers = array('Row', 'TransId', 'VendorTransId', 'Distributor ID', 'Shop', 'Vendor', 'Operator', 'Circle', 'Amt', 'Comm', 'Status', 'Previous Status', 'Date', 'Processing Time', 'Updated Time', 'TypeStatus', 'Cause', 'Sub-Cause', 'CC', 'TxnBy');
            fputcsv($fd, $headers);

            $i = 1;
            foreach($txn_data as $data){
                $type = "";
                if($data['va']['status'] == '2' || $data['va']['status'] == '3'){
                    $type = "Failed";
                }
                else{
                    $type = "Success";
                }

                $api_flag = $data['va']['api_flag'];
                $api_array = array('0'=>'sms', '1'=>'old apps', '2'=>'ussd', '3'=>'android', '4'=>'api partner', '5'=>'java', '7'=>'win7', '8'=>'win8', '9'=>'web');

                $retailerLink = strcmp($data['r']['name'], '') != 0 ? $data['r']['name'] : $data['r']['mobile'];
                $ps = '';
                if($data['va']['status'] == '0'){
                    $ps = 'In Process';
                }
                else if($data['va']['status'] == '1'){
                    $ps = 'Successful';
                }
                else if($data['va']['status'] == '2'){
                    $ps = 'Failed';
                }
                else if($data['va']['status'] == '3'){
                    $ps = 'Reversed';
                }
                else if($data['va']['status'] == '4'){
                    $ps = 'Reversal In Process';
                }
                else if($data['va']['status'] == '5'){
                    $ps = 'Reversal declined';
                }

                $ps_p = "";
                if($data['va']['prevStatus'] == '0'){
                    $ps_p = 'In Process';
                }
                else if($data['va']['prevStatus'] == '1'){
                    $ps_p = 'Successful';
                }
                else if($data['va']['prevStatus'] == '2'){
                    $ps_p = 'Failed';
                }
                else if($data['va']['prevStatus'] == '3'){
                    $ps_p = 'Reversed';
                }
                else if($data['va']['prevStatus'] == '4'){
                    $ps_p = 'Reversal In Process';
                }
                else if($data['va']['prevStatus'] == '5'){
                    $ps_p = 'Reversal declined';
                }

                if($data['va']['tran_processtime'] == '' || $data['va']['tran_processtime'] == '0000-00-00 00:00:00'){
                    $processTime = $data[0]['vm_timestamp'];
                }
                else{
                    $processTime = $data['va']['tran_processtime'];
                }

                $mobnum = substr($data['va']['mobile'], 0, 5);
                $sub_cause = explode(",", $data[0]['causes']);
                $sub_cause = end($sub_cause);
                $sub_cause = ($data['va']['status'] == 2 || $data['va']['status'] == 3) ? $sub_cause : "";

                $line = array($i, $data['va']['txn_id'], $data['va']['vendor_refid'], $data['va']['distributor_id'], $data['r']['name'], $data['v']['shortForm'], $data['p']['name'], $mobArea[$mobnum], $data['va']['amount'], round($data['0']['comm'], 2), $ps, $ps_p, $data['va']['timestamp'],
                        $processTime, $data[0]['vm_timestamp'], $type, $data['va']['cause'], $sub_cause, $data['users']['name'], $api_array[$api_flag]);

                fputcsv($fd, $line);
                $i++ ;
            }
            rewind($fd);
            $zip->addFromString('transaction_log_'.$date.'.csv', stream_get_contents($fd) );
            fclose($fd);
            $zip->close();

            //upload daily txns file on s3 bucket
            App::import('vendor', 'S3', array('file' => 'S3.php'));
            $s3 = new S3(awsAccessKey, awsSecretKey);
            $response = $s3->putObjectFile($document_path, $bucket, $file, S3::ACL_PRIVATE);

            if($flag != 1)
            {
                if($response)
                {
                    $mail_subject = 'Transaction Report for Date : '.$date;
                    $mail_body = 'PFA';
                    $sender_id = null;
                    $rec_email_ids = array('ashok.y@pay1.in','esmerald.fernandes@pay1.in','manoj.mahadik@pay1.in');
                    $presigned_url = $s3->aws_s3_link(awsAccessKey,awsSecretKey,$bucket,'/'.$file,time() - strtotime(date('Y-m-d'))+50);

                    $this->General->sendEmailAttachments($mail_subject,$mail_body,$sender_id,$rec_email_ids,$presigned_url,'mail');
                }
            }
        }

//        function updateUTR () {
//
//                $this->autoRender = FALSE;
//
//                $atd_data = $this->Slaves->query("SELECT id, description FROM account_txn_details atd WHERE operation_date = '".date('Y-m-d')."' AND txn_status = 'Dr' AND is_submitted = '0'");
//
//                foreach ($atd_data as $ad) {
//
//                        if (strpos($ad['atd']['description'], 'RTGS') !== false || strpos($description, 'INF') !== false || strpos($description, 'CMS') !== false) {
//
//                                $description_break = explode('/', str_replace('RTGS:', '', $ad['atd']['description']));
//
//                                if ($description_break) {
//                                        foreach ($description_break as $val) {
//                                                if (strlen($val) > 10) {
//                                                        $result = $this->Slaves->query("SELECT io.id FROM inv_payments ip JOIN inv_orders io ON (ip.order_id = io.id) WHERE ip.utr = '$val' AND io.order_date <= '" . date('Y-m-d') . "'");
//
//                                                        if ($result) {
//                                                                $type_id = implode(',', array_map('current', array_map('current', $result)));
//
//                                                                $this->Retailer->query("UPDATE account_txn_details SET account_category_id = '10', type = 'supplier', type_id = '$type_id', is_submitted = '1' WHERE id = '{$ad['atd']['id']}'");
//                                                                break;
//                                                        }
//                                                }
//                                        }
//                                }
//                        }
//                }
//        }

        function updateDailyTxnReport($date = null)
        {
            $this->autoRender = FALSE;
            $date = empty($date)?date('Y-m-d', strtotime('-1 day')):$date;

            $txn_dates = $this->Slaves->query("SELECT DISTINCT date FROM vendors_activations va WHERE date != reversal_date AND reversal_date = '$date' AND date < '$date'");

            foreach ($txn_dates as $date)
            {
//                $this->sendDailyTransactionsReport(1,$date['va']['date']);

                shell_exec("nohup wget -O/dev/null ".SITE_NAME."/crons/sendDailyTransactionsReport/1/".$date['va']['date']." &");
            }
        }
        
        function clearSmartpayUTR ($from = null, $to = null) {
            
                $this->autoRender = FALSE;
                
                $from == null && $from = date('Y-m-d', strtotime('-2 days'));
                $to   == null && $to   = date('Y-m-d');
                
                /************** Paynear Payment UTR Clearance **************/

                $data = array('from_txn_date' => $from,
                            'to_txn_date' => $to,
                            'txn_status' => 'S',
                            'settlement_mode' => '1');

                $response = $this->Smartpaycomp->fetchCustomerReport($data);

                $user_data = json_decode($response, true);

                foreach ($user_data['transactions'] as $udt) {

                        $date        = date('Y-m-d', strtotime($udt['txn_time']));
                        $utr         = $udt['utr_id'];
                        $category_id = $udt['service_id'] == 8 ? 49 : 98;

                        if ($utr != '') {
                                $this->User->query("UPDATE account_txn_details SET account_category_id = '$category_id', is_submitted = '1' "
                            . "WHERE txn_date >= '$date 00:00:00' AND txn_date <= '".date('Y-m-d', strtotime('+2 days', strtotime($date)))." 23:59:59' "
                            . "AND description LIKE '%".$utr."%' AND is_submitted = '0'");
                        }
                }
                
                /************** Paynear Payment UTR Clearance - End **************/
                
                echo "Done !!!";
        }
        
        function clearInvUTR ($from = null, $to = null) {
            
                $this->autoRender = FALSE;
                
                $from == null && $from = date('Y-m-d', strtotime('-2 days'));
                $to   == null && $to   = date('Y-m-d');
                
                /************** Inventory UTR Clearance **************/
                
                $utrs = $this->Slaves->query("SELECT io.id, ip.utr FROM inv_payments ip JOIN inv_orders io ON (ip.order_id = io.id) WHERE io.order_date >= '$from' AND io.order_date <= '$to' AND ip.utr != ''");

                foreach ($utrs as $utr) {
                        $this->User->query("UPDATE account_txn_details SET account_category_id = '10', type = 'supplier', type_id = '{$utr['io']['id']}', is_submitted = '1' WHERE description LIKE '%{$utr['ip']['utr']}%' AND DATE(txn_date) >= '$from' AND DATE(txn_date) <= '$to' AND is_submitted = '0'");
                }
                
                /************** Inventory UTR Clearance - End**************/
                
                echo "Done !!!";
        }

        function updateSchemeSale($manual_date=null){
            $this->Scheme->updateSchemeSale($manual_date);
            $this->autoRender = false;
        }

        function insertSchemeTarget($manual_date=null){
            $this->Scheme->schemeinsertCron($manual_date);
            $this->autoRender = false;
        }

        function updateSchemeTarget($manual_date=null){
            $this->Scheme->schemeupdateCron($manual_date);
            $this->autoRender = false;
        }

        function setIncentive($manual_date=null){
            $this->Scheme->setIncentive($manual_date);
            $this->autoRender = false;
        }
        
        /*function reverseCommission(){
            $this->autoRender = FALSE;
            $data = $this->Slaves->query("select shop_transactions.id,amount,shop_transactions.user_id,source_id,distributors.user_id from shop_transactions left join distributors on (distributors.id = shop_transactions.source_id) where type = 6 and date = '2018-09-05' and timestamp >= '2018-09-05 23:30:00' and confirm_flag = 0 and shop_transactions.user_id > 0");
            foreach($data as $dt){
                
                $closing_bal = $this->Shop->shopBalanceUpdate($dt['shop_transactions']['amount'], 'subtract', $dt['distributors']['user_id'], DISTRIBUTOR);
                
                $description = "Reversed extra commission given yesterday night: " . $dt['shop_transactions']['id'];
                
                $trans_id = $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR_REVERSE, $dt['shop_transactions']['amount'], $dt['shop_transactions']['source_id'], 0, $dt['shop_transactions']['user_id'], null, null, $description,$closing_bal+$dt['shop_transactions']['amount'],$closing_bal);
            }
        }*/

        function debitRental($user_id = null,$service_id = null,$mode = null,$dataSource = null)
        {
            $this->autoRender = FALSE;
            $filename = "rental_details_".date('Ymd').".txt";
            $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . "rental :: Start");

            Configure::load('bridge');
            $service_urls = Configure::read('notification_url');
            Configure::load('platform');
            $product_base_urls = Configure::read('product_base_url');
            $current_date = date('Y-m-d');
            $days_30_older_date = date('Y-m-d', strtotime('-30 days'));
            $days_90_older_date = date('Y-m-d', strtotime('-90 days'));
            $excluded_users = array(5711708,11470265,31918718,47007315,47007593,47007845,47199184,77769009,37255496);

            $qry = '';

            if(!empty($user_id)){
                $qry .= 'AND user_id = '.$user_id.' ';
            }
            if(!empty($service_id)){
                $qry .= 'AND service_id = '.$service_id.' ';
            }
            if(!empty($mode) && $mode == 'reactivate'){
                $qry .= 'AND service_flag = 2 ';
            } else {
                $qry .= 'AND service_flag = 1 ';
            }

            $query = 'SELECT * '
                    . 'FROM users_services us '
                    . 'WHERE 1 '
                    . ''.$qry.' '
                    . 'AND kit_flag = 1 '
                    . 'AND device_id IS NOT NULL '
                    . 'AND rental_activation_date IS NOT NULL '
                    . 'AND user_id NOT IN ('.implode(',',$excluded_users).') '
                    . 'AND rental_activation_date <= "'.$days_30_older_date.'" '
                    . 'AND (next_rental_debit_date IS NULL OR next_rental_debit_date <= "'.$current_date.'") ';

            $users = $this->Slaves->query($query);

            if(!empty($users))
            {
                $service_plans = $this->Serviceintegration->getServicePlans();
                $service_plans = json_decode($service_plans,TRUE);
                $services = $this->Serviceintegration->getAllServices();
                $services = json_decode($services,true);

                foreach($users as $user)
                {
                    $dataSource = $this->User->getDataSource();
                    $dataSource->begin();
                    $user_data = $this->Bridge->getUserData(array('user_id'=>$user['us']['user_id']));
                    $mobile = !empty($user_data['data']['mobile'])?$user_data['data']['mobile']:'';

                    if($user['us']['service_id'] == 8)
                    { // 3 months free rental for mpos service
                        $first_3_month_free = array("09811109116123015224","09813309116121610025","09813309116121610027",
                        "09811109116123015159","09813309116121610025","09811109116123014933","09811109116123015160",
                        "09811109116123014934","09811109116123014937","09813309116121610027","09811109116123014935",
                        "09811109116123014936","09811109116123015224","09813309116123004545","09813309116123004539",
                        "09813309116123004542","09811109116123015183","09813309116123004529","09813309116123004554",
                        "09813309116123004555","09813309116123004562","09813309116123004552",
                        "09813309116123004573","09813309116123004579");
                        if( in_array("'" . $user['us']['device_id'] . "'",$first_3_month_free) && ($user['us']['rental_activation_date'] > $days_90_older_date) )
                        {
                            return json_encode(array(
                                                'status'=>'failure',
                                                'errCode'=>0,
                                                'description'=> '3 months free rental for mpos service'
                                                ));
                        }
                    }

                    $plan = null;
                    $params = json_decode($user['us']['params'],true);

                    if( count($params) > 0 && array_key_exists('plan',$params) && !empty($params['plan']) )
                    {
                        $plan = $params['plan'];
                    }

                    if(!empty($plan))
                    {
                        $rental_monthly_amount = isset($service_plans[$user['us']['service_id']][$plan]) ? $service_plans[$user['us']['service_id']][$plan]['rental_amt'] : 0 ;
//
                        if( $rental_monthly_amount > 0 )
                        { // rental amount is 0 for Plan D - Rs. 5999

                            if( !empty($user['us']['next_rental_debit_date']) && ($user['us']['next_rental_debit_date'] != '0000-00-00') )
                            { // next rental date is set
                                $days_delay = $this->Smartpaycomp->dateDifference($user['us']['next_rental_debit_date'],$current_date);
                                $amount = $rental_monthly_amount;
                                $deducted_month = date('F-y',strtotime($user['us']['next_rental_debit_date']));
                                $next_rental_debit_date = date('Y-m-d', strtotime($user['us']['next_rental_debit_date'].'+1 month'));
                            }
                            else
                            { // next rental date is NULL this is first time user's rental is being debited
                                $days_to_add = '1 month';
                                if( ($user['us']['service_id'] == 8) && in_array("'".$user['us']['device_id']."'",$first_3_month_free) )
                                {
                                    $days_to_add = '3 month';
                                }
                                $day = date('d',strtotime($user['us']['rental_activation_date']));
                                $next_rental_debit_date = date('Y-m-'.$day.'', strtotime($user['us']['rental_activation_date'].'+'.$days_to_add.' + 1 month' ));

                                $days_delay = $this->Smartpaycomp->dateDifference(date('Y-m-d', strtotime($user['us']['rental_activation_date'].'+'.$days_to_add)),$current_date);
                                $amount = $rental_monthly_amount;
                                $deducted_month = date('F-y', strtotime($user['us']['rental_activation_date'].'+'.$days_to_add));
                            }

                            if( $amount > $rental_monthly_amount )
                            {
                                $amount = $rental_monthly_amount;
                            }

                            $data = array('service_id' => $user['us']['service_id'],'user_id' => $user['us']['user_id'],'amount' => $amount,'type' => 'db','txn_type' => 'rental','product_id' => '','service_charge' => 0,'commission' => 0,'tax' => 0,'deducted_month'=>$deducted_month);

                            $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": rental wallet request :: ". json_encode($data));

                            $response = $this->Shop->deductRental($data,$dataSource);

                            $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": rental wallet response :: ". json_encode($response));
                            if($response['status'] == 'success')
                            {
                                $set_rental_date = $this->Shop->setNextRentalDebitDate($user['us']['user_id'],$user['us']['service_id'],$next_rental_debit_date,$dataSource);
                                if($set_rental_date['status'] == 'success')
                                {
                                    if(empty($mode))
                                    {
                                        $dataSource->commit();
                                        $this->Shop->sendNotification($user['us']['user_id'],$user['us']['service_id'],$mobile,$amount,$next_rental_debit_date,$deducted_month,$mode);
                                    }
                                    elseif(!empty($mode) && $mode == 'reactivate')
                                    {
                                        $data = array('user_id'=>$user['us']['user_id'],'service_id'=>$user['us']['service_id'],'mobile'=>$mobile,'amount'=>$amount,'next_rental_debit_date'=>$next_rental_debit_date,'deducted_month'=>$deducted_month,'mode'=>$mode,'device_id'=>$user['us']['device_id']);
                                        return json_encode(array('status' => 'success','data' => $data));
                                    }
                                }
                                else
                                {
                                    if(empty($mode))
                                    {
                                        $dataSource->rollback();
                                    }
                                    else
                                    {
                                        return json_encode(array('status' => 'failure','description' => 'Something went wrong. Please try again'));
                                    }
                                }
                            }
                            else
                            {
                                $product_response = array();
                                //check remaining amt in wallet transactions
                                $bank_txn_details = $this->Shop->getBankTxnDetails($user['us']['user_id'],$user['us']['service_id'],$amount);
                                $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": Bank txn details :: ". json_encode($bank_txn_details));
                                if(!empty($bank_txn_details))
                                {
                                    //call wallet api for partial settlement
                                    unset($bank_txn_details['amt_remaining_settlement']);
                                    $bank_txn_details['settle_flag'] = 2;
                                    $bank_txn_details['amount'] = $amount;
                                    $settlement_response = $this->Shop->settleBankTxn($bank_txn_details,$dataSource);
                                    $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": Settlement response :: ". json_encode($settlement_response));
                                    if($settlement_response['status'] == 'success')
                                    {
                                        $rental_response = $this->Shop->deductRental($data,$dataSource,false);
                                        $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": Rental response :: ". json_encode($rental_response));
                                        if($rental_response['status'] == 'success')
                                        {
                                            $set_rental_date = $this->Shop->setNextRentalDebitDate($user['us']['user_id'],$user['us']['service_id'],$next_rental_debit_date,$dataSource);
                                            $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": Set next rental date :: ". json_encode($set_rental_date));
                                            if($set_rental_date['status'] == 'success')
                                            {
                                                $txndata = array('user_id' => $user['us']['user_id'],'txn_id' => $bank_txn_details['txn_id'],'settled_amount' => $amount);
                                                if(isset($product_base_urls[$user['us']['service_id']]) && !empty($product_base_urls[$user['us']['service_id']])){
                                                    $product_response = $this->General->curl_post($product_base_urls[$user['us']['service_id']].'/partialSettleTxn',$txndata,'POST');
                                                    $product_response = json_decode($product_response['output'],true);
                                                    $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": Url : ".$product_base_urls[$user['us']['service_id']].'/partialSettleTxn'." Txn data : ".json_encode($txndata)." Produt response :: ". json_encode($product_response));
                                                }
                                                if( ($product_response) && $product_response['status'] == 'success')
                                                {
                                                    if(empty($mode))
                                                    {
                                                        $dataSource->commit();
                                                        $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": Success");
                                                        $MsgTemplate = $this->General->LoadApiBalance();
                                                        $paramdata['AMOUNT'] = $amount;
                                                        $paramdata['TXN_ID'] = $bank_txn_details['txn_id'];
                                                        $paramdata['TXN_DATE'] = date('jS M,Y', strtotime($bank_txn_details['date']));

                                                        $content = $MsgTemplate['PARTIAL_SETTLEMENT_TEMPLATE'];
                                                        $message = $this->General->ReplaceMultiWord($paramdata, $content);
                                                        $this->General->sendMessage($mobile, $message, 'notify');
                                                        $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": Partial settlement msg :: ".$message);

                                                        $this->Shop->sendNotification($user['us']['user_id'],$user['us']['service_id'],$mobile,$amount,$next_rental_debit_date,$deducted_month,$mode);
                                                    }
                                                    elseif(!empty($mode) && $mode == 'reactivate')
                                                    {
                                                        $data = array('user_id'=>$user['us']['user_id'],'service_id'=>$user['us']['service_id'],'mobile'=>$mobile,'amount'=>$amount,'next_rental_debit_date'=>$next_rental_debit_date,'deducted_month'=>$deducted_month,'mode'=>$mode,'device_id'=>$user['us']['device_id']);
                                                        $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": Mode : reactivate, Notification data :: ". json_encode($data));
                                                        return json_encode(array('status' => 'success','data' => $data));
                                                    }
                                                }
                                                else
                                                {
                                                    if(empty($mode))
                                                    {
                                                        $dataSource->rollback();
                                                    }
                                                    else
                                                    {
                                                        return json_encode(array('status' => 'failure','description' => 'Something went wrong. Please try again'));
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                if(empty($mode))
                                                {
                                                    $dataSource->rollback();
                                                }
                                                else
                                                {
                                                    return json_encode(array('status' => 'failure','description' => 'Something went wrong. Please try again'));
                                                }
                                            }
                                        }
                                        else
                                        {
                                            if(empty($mode))
                                            {
                                                $dataSource->rollback();
                                            }
                                            else
                                            {
                                                return json_encode(array('status' => 'failure','description' => 'Something went wrong. Please try again'));
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if(empty($mode))
                                        {
                                            $dataSource->rollback();
                                        }
                                        else
                                        {
                                            return json_encode(array('status' => 'failure','description' => 'Something went wrong. Please try again'));
                                        }
                                    }
                                }
                                else if(!empty($mode) && $mode == 'reactivate')
                                {
                                    return json_encode($response);
                                }
                                else if( ($response['errCode'] == 105) && ($days_delay > 0) && ($days_delay < Rental_Delay_Limit) )
                                {
                                    // send alert to user
                                    $deactivated_date = date('Y-m-d', strtotime('+'.(Rental_Delay_Limit - $days_delay).' days'));

                                    $MsgTemplate = $this->General->LoadApiBalance();
                                    $paramdata['DATE'] = $deactivated_date;
                                    $paramdata['MONTH'] = $deducted_month;
                                    $paramdata['AMOUNT'] = $amount;
                                    $paramdata['SERVICE_NAME'] = $services[$user['us']['service_id']];

                                    $content = $MsgTemplate['RENTAL_DEDUCT_NOTIFY_TEMPLATE'];

                                    $current_hour = date('H');
                                    if($current_hour == '14')
                                    {
                                        $message = $this->General->ReplaceMultiWord($paramdata, $content);
                                        $this->General->sendMessage($mobile, $message, 'notify');
                                        $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": sms sent to user :: ".$message);
                                        if( $days_delay > 4 )
                                        {
                                            $mail_subject = $paramdata['SERVICE_NAME'].' Alert : Retailer Rental Delay ALert';
                                            $notify_body = "Retailer Rental Delayed By ".$days_delay." days <br>Service : ".$paramdata['SERVICE_NAME']."<br>User ID : ".$user['us']['user_id']."<br>Mobile : ".$mobile."<br>SMS Sent : ".$message;
                                            $this->General->sendEmailAttachments($mail_subject,$notify_body,NULL,array('pay1swipe@pay1.in','pay1digi@pay1.in','collection@pay1.in'),NULL,'mail');
                                        }
                                    }
                                }
                                else if($response['errCode'] == 105 && $days_delay >= Rental_Delay_Limit)
                                {
                                    $update_service_flag = $this->Shop->deactivateService($user['us']['user_id'],$user['us']['service_id'],$dataSource);
                                    $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": deactivateService response :: ". json_encode($update_service_flag));
                                    if($update_service_flag['status'] == 'success')
                                    {
                                        /*** SENDING DATA TO PRODUCT ***/
                                        $data = array('user_id'=>$user['us']['user_id'],'service_id'=>$user['us']['service_id'],'service_flag'=>2);
                                        $product_response = $this->General->curl_post(
                                            $service_urls[$user['us']['service_id']],
                                            $data,
                                            'POST'
                                        );
                                        $product_response = json_decode($product_response['output'],true);
                                        $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": product response :: ". json_encode($product_response));
                                        if( ($product_response) && $product_response['status'] == 'success'){
                                            $dataSource->commit();
                                            $MsgTemplate = $this->General->LoadApiBalance();
                                            $paramdata['SERVICE_NAME'] = $services[$user['us']['service_id']];

                                            $content = $MsgTemplate['RENTAL_DEDUCT_SERVICE_DEACT_TEMPLATE'];
                                            $message = $this->General->ReplaceMultiWord($paramdata, $content);
                                            $this->General->sendMessage($mobile, $message, 'notify');
                                            $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": sms sent to user :: ".$message);

                                            $mail_subject = $service_name.' Alert : Retailer Service Deactivated';
                                            $notify_body = "Service : ".$paramdata['SERVICE_NAME']."<br>User ID : ".$user['us']['user_id']."<br>Mobile : ".$mobile."<br>SMS Sent : ".$message;
                                            $this->General->sendEmailAttachments($mail_subject,$notify_body,NULL,array('pay1swipe@pay1.in','pay1digi@pay1.in','collection@pay1.in'),NULL,'mail');
                                        }
                                        else
                                        {
                                            $dataSource->rollback();
                                        }
                                    }
                                    else
                                    {
                                        $dataSource->rollback();
                                    }
                                }
                            }
                        }
                        else {
                            $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . "User ID : ".$user['us']['user_id']." Rental not applied to this plan :: ".$plan);
                        }
                    }else {
                        $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . "plan not found for :: ".json_encode($user));
                        if($mode && $mode == 'reactivate'){
                        return json_encode(array(
                        'status'=>'failure',
                        'errCode'=>0,
                        'description'=> ' Plan not found'
                        ));
                        }
                        echo '<pre> Plan not found for : ';
                        print_r($user);
                    }
                }
            }
            else
            {
                if($mode && $mode == 'reactivate')
                {
                    return json_encode(array(
                    'status'=>'failure',
                    'errCode'=>0,
                    'description'=> 'Record not found'
                    ));
                }
                echo 'No pending rental to process';
            }
        }

        function forcePasswordChange() { 

                $data = $this->User->query("SELECT id, password_change FROM users WHERE password_change <= '".date('Y-m-d', strtotime('-3 months'))."' AND passflag = 1");

                foreach ($data as $d) {
                        if (rand() % 3 == 0) {
                                $this->User->query("UPDATE users SET passflag = 0 WHERE id = '". $d['users']['id'] ."'");
                        }
                }

                $this->autoRender = false;
        }
        
        function updateRetailerServiceLogs($date = NULL,$flag = 0){
            $this->autoRender = false;
            $date = empty($date)?date('Y-m-d', strtotime('-1 day')):$date;
            $tbl = $flag == 0?'shop_transactions':'shop_transactions_logs';
            $response = array();

            $txn_details = $this->Slaves->query('SELECT st1.source_id,st1.user_id AS service_id,SUM(st1.amount) AS amount,SUM(st2.amount) AS tds,st1.type,GROUP_CONCAT(DISTINCT(g.group_id)) AS group_ids '
                        . 'FROM '.$tbl.' st1 '
                        . 'JOIN retailers r ON (st1.source_id = r.user_id) '
                        . 'JOIN user_groups g ON (st1.source_id = g.user_id) '
                        . 'LEFT JOIN '.$tbl.' st2 ON (st1.id = st2.target_id AND st2.type = '.TDS.') '
                        . 'WHERE st1.type IN ('.REFUND.','.RENTAL.','.KITCHARGE.','.SECURITY_DEPOSIT.','.ONE_TIME_CHARGE.') '
                        . 'AND st1.confirm_flag = 0 '
                        . 'AND st1.date = "'.$date.'" '
                        . 'GROUP BY st1.source_id,st1.user_id,st1.type');

            foreach($txn_details as $data){
                $group_ids = explode(',', $data[0]['group_ids']);
                if(!in_array(5,$group_ids)){
                    $key = $data['st1']['source_id'].'_'.$data['st1']['service_id'].'_'.$data['st1']['type'];
                    $response[$data['st1']['source_id']][$key]['ret_user_id'] = $data['st1']['source_id'];
                    $response[$data['st1']['source_id']][$key]['service_id'] = $data['st1']['service_id'];
                    $response[$data['st1']['source_id']][$key]['amount'] = $data[0]['amount'];
                    $response[$data['st1']['source_id']][$key]['tds'] = $data[0]['tds'];
                    $response[$data['st1']['source_id']][$key]['type'] = $data['st1']['type'];
                }
            }

            $txn_reversed = $this->Slaves->query('SELECT st.source_id,st.user_id as service_id,sum(st.amount) as txn_reverse_amt,st.type,st.type_flag,GROUP_CONCAT(DISTINCT(g.group_id)) AS group_ids '
                    . 'FROM '.$tbl.' st '
                    . 'JOIN retailers r ON (st.source_id = r.user_id) '
                    . 'JOIN user_groups g ON (st.source_id = g.user_id) '
                    . 'WHERE st.type = '.TXN_REVERSE.' '
                    . 'AND st.date = "'.$date.'" '
                    . 'GROUP BY st.source_id,st.user_id,st.type_flag');

            foreach($txn_reversed as $data){
                $group_ids = explode(',', $data[0]['group_ids']);
                if(!in_array(5,$group_ids)){
                    $key = $data['st']['source_id'].'_'.$data['st']['service_id'].'_'.$data['st']['type_flag'];
                    $response[$data['st']['source_id']][$key]['txn_reverse_amt'] = $data[0]['txn_reverse_amt'];
                }
            }

            $dist_user_ids = $this->Slaves->query('SELECT d.user_id,r.user_id '
                . 'FROM distributors d '
                . 'JOIN retailers r '
                . 'ON (d.id = r.parent_id) '
                . 'WHERE r.user_id IN ('.implode(',', array_keys($response)).') ');

            foreach ($dist_user_ids as $data)
            {
                $response[$data['r']['user_id']]['dist_user_id'] = $data['d']['user_id'];
            }

            $i=1;
            $batch_qry = array();

            foreach ($response as $ret_user_id => $earning_data)
            {
                $dist_user_id = $earning_data['dist_user_id'];
                unset($earning_data['dist_user_id']);

                foreach($earning_data as $data)
                {
                    $tds = isset($data['tds']) && !empty($data['tds'])?$data['tds']:0;
                    $txn_reverse_amt = isset($data['txn_reverse_amt']) && !empty($data['txn_reverse_amt'])?$data['txn_reverse_amt']:0;
                    $batch_qry['type'] = 'INSERT';
                    $batch_qry['predata'] = 'INSERT INTO retailer_service_logs(date,ret_user_id,dist_user_id,service_id,amount,tds,txn_reverse_amt,type)VALUES';

                    $batch_qry['data'][] = '("'.$date.'","'.$ret_user_id.'","'.$dist_user_id.'","'.$data['service_id'].'",'
                                            . '"'.$data['amount'].'","'.$tds.'","'.$txn_reverse_amt.'","'.$data['type'].'")';

                    if (($i % 100) == 0){ //process batch of 100 queries together
                    $this->run_batch($batch_qry);
                    $batch_qry = array();
                    }
                    $i++;
                }
            }

            if(!empty($batch_qry)) // handled cases if last batch consist less then 100 queries
            {
                $this->run_batch($batch_qry);
                $batch_qry = array();
            }
        }

        function updateDistributorServiceLogs($date = NULL,$flag = 0){
            $this->autoRender = false;
            $date = empty($date)?date('Y-m-d', strtotime('-1 day')):$date;
            $tbl = $flag == 0?'shop_transactions':'shop_transactions_logs';
            $response = array();
            
            $txn_details = $this->Slaves->query('SELECT st1.source_id,st1.user_id AS service_id,SUM(st1.amount) AS amount,SUM(st2.amount) AS tds,SUM(st1.discount_commission) as commission,st1.type '
                        . 'FROM '.$tbl.' st1 '
                        . 'JOIN distributors d ON (st1.source_id = d.user_id) '
                        . 'LEFT JOIN '.$tbl.' st2 ON (st1.id = st2.target_id AND st2.type = '.TDS.') '
                        . 'WHERE st1.type IN ('.REFUND.','.RENTAL.','.KITCHARGE.','.SECURITY_DEPOSIT.','.ONE_TIME_CHARGE.') '
                        . 'AND st1.confirm_flag = 0 '
                        . 'AND st1.date = "'.$date.'" '
                        . 'GROUP BY st1.source_id,st1.user_id,st1.type');

            foreach($txn_details as $data){
                $key = $data['st1']['source_id'].'_'.$data['st1']['service_id'].'_'.$data['st1']['type'];
                $response[$data['st1']['source_id']][$key]['dist_user_id'] = $data['st1']['source_id'];
                $response[$data['st1']['source_id']][$key]['service_id'] = $data['st1']['service_id'];
                $response[$data['st1']['source_id']][$key]['amount'] = $data[0]['amount'];
                $response[$data['st1']['source_id']][$key]['tds'] = $data[0]['tds'];
                $response[$data['st1']['source_id']][$key]['commission'] = $data['st1']['type'] == KITCHARGE?$data[0]['discount_commission']:0;
                $response[$data['st1']['source_id']][$key]['type'] = $data['st1']['type'];
            }

            $comm_dist = $this->Slaves->query('SELECT d.user_id,st1.user_id as service_id,sum(st1.amount) as amount,SUM(st2.amount) AS tds,st1.type '
                        . 'FROM '.$tbl.' st1 '
                        . 'JOIN distributors d ON (st1.source_id = d.id) '
                        . 'LEFT JOIN '.$tbl.' st2 ON (st1.id = st2.target_id AND st2.type = '.TDS.')  '
                        . 'WHERE st1.type IN ('.COMMISSION_DISTRIBUTOR.','.COMMISSION_DISTRIBUTOR_REVERSE.') '
                        . 'AND st1.confirm_flag = 0 '
                        . 'AND st1.date = "'.$date.'" '
                        . 'GROUP BY d.user_id,st1.user_id,st1.type');

            foreach($comm_dist as $data){
                $key = $data['d']['user_id'].'_'.$data['st1']['service_id'].'_'.COMMISSION_DISTRIBUTOR;
                $response[$data['d']['user_id']][$key]['dist_user_id'] = $data['d']['user_id'];
                $response[$data['d']['user_id']][$key]['service_id'] = $data['st1']['service_id'];
                if($data['st1']['type'] == COMMISSION_DISTRIBUTOR){
                    $response[$data['d']['user_id']][$key]['amount'] += $data[0]['amount'];
                    $response[$data['d']['user_id']][$key]['tds'] += $data[0]['tds'];
                }
                else {
                    $response[$data['d']['user_id']][$key]['amount'] -= $data[0]['amount'];
                    $response[$data['d']['user_id']][$key]['tds'] -= $data[0]['tds'];
                }
                $response[$data['d']['user_id']][$key]['amount'] = $data[0]['amount'];
                $response[$data['d']['user_id']][$key]['type'] = COMMISSION_DISTRIBUTOR;
            }

            $txn_reversed = $this->Slaves->query('SELECT st.source_id,st.user_id as service_id,sum(st.amount) as txn_reverse_amt,st.type,st.type_flag '
                    . 'FROM '.$tbl.' st '
                    . 'JOIN distributors d ON (st.source_id = d.user_id) '
                    . 'WHERE st.type = '.TXN_REVERSE.' '
                    . 'AND st.date = "'.$date.'" '
                    . 'GROUP BY st.source_id,st.user_id,st.type_flag');

            foreach($txn_reversed as $data){
                $key = $data['st']['source_id'].'_'.$data['st']['service_id'].'_'.$data['st']['type_flag'];
                $response[$data['st']['source_id']][$key]['txn_reverse_amt'] = $data[0]['txn_reverse_amt'];
            }

            $i=1;
            $batch_qry = array();

            foreach ($response as $dist_user_id => $txn_data)
            {
                foreach($txn_data as $data)
                {
                    $tds = isset($data['tds']) && !empty($data['tds'])?$data['tds']:0;
                    $txn_reverse_amt = isset($data['txn_reverse_amt']) && !empty($data['txn_reverse_amt'])?$data['txn_reverse_amt']:0;
                    $batch_qry['type'] = 'INSERT';
                    $batch_qry['predata'] = 'INSERT INTO distributor_service_logs(date,dist_user_id,service_id,amount,earning,tds,txn_reverse_amt,type)VALUES';

                    $batch_qry['data'][] = '("'.$date.'","'.$dist_user_id.'","'.$data['service_id'].'",'
                                            . '"'.$data['amount'].'","'.$data['commission'].'","'.$tds.'","'.$txn_reverse_amt.'","'.$data['type'].'")';

                    if (($i % 100) == 0){ //process batch of 100 queries together
                    $this->run_batch($batch_qry);
                    $batch_qry = array();
                    }
                    $i++;
                }
            }
            if(!empty($batch_qry)) // handled cases if last batch consist less then 100 queries
            {
                $this->run_batch($batch_qry);
                $batch_qry = array();
            }
        }

        function makeAllDistAsRets() {

                $this->autoRender = false;

                $all_dist = $this->Slaves->query("SELECT d.*,s.* FROM distributors d LEFT JOIN salesmen s ON (d.mobile = s.mobile) WHERE d.id != 1 AND d.active_flag = 1");

                foreach ($all_dist as $dist) {

                        $retailerData = $this->Slaves->query("SELECT * FROM retailers WHERE mobile = '".$dist['d']['mobile']."'");
                        if (!$retailerData) {
                                $this->Retailer->query("INSERT INTO retailers (user_id,parent_id,slab_id,mobile,email,name,shopname,salesman,maint_salesman,created,modified,trial_flag) VALUES "
                                        . "('".$dist['d']['user_id']."','".$dist['d']['id']."','13','".$dist['d']['mobile']."','".$dist['d']['email']."',"
                                        . "'".$dist['d']['name']."','".$dist['d']['company']."','".$dist['s']['id']."','".$dist['s']['id']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','1')");
                                $this->Shop->addUserGroup($dist['d']['user_id'],RETAILER);
                                $retailerData = $this->Retailer->query("SELECT * FROM retailers WHERE mobile = '".$dist['d']['mobile']."'");
                                $this->Retailer->query("INSERT INTO unverified_retailers (retailer_id,name,shopname,created,modified) VALUES "
                                        . "('".$retailerData[0]['retailers']['id']."','".$retailerData[0]['retailers']['name']."','".$retailerData[0]['retailers']['shopname']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')");
                                $this->Retailer->query("INSERT INTO user_profile (user_id,location_src,area_id,device_type,version,manufacturer,created,updated,date) VALUES "
                                        . "('".$dist['d']['user_id']."','network','0','web','5','Chrome','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','".date('Y-m-d')."')");
                                unset($retailerData);
                        }
                }
                echo "Done !!!";
        }
        
        function updateUsersNontxnLogs($date = NULL,$flag = 0){
            $this->autoRender = false;
            $date = empty($date)?date('Y-m-d') : ($date == 1?date('Y-m-d',strtotime('-1 days')):$date);
            $tbl = $flag == 0?'shop_transactions':'shop_transactions_logs';
            $response = array();

            $txn_details = $this->Slaves->query('SELECT st1.source_id,st1.user_id AS service_id,SUM(st1.amount) AS amount,SUM(st2.amount) AS tds,st1.type '
                        . 'FROM '.$tbl.' st1 '
                        . 'LEFT JOIN '.$tbl.' st2 ON (st1.id = st2.target_id AND st2.type = '.TDS.') '
                        . 'WHERE st1.type IN ('.REFUND.','.RENTAL.','.KITCHARGE.','.SECURITY_DEPOSIT.','.ONE_TIME_CHARGE.') '
                        . 'AND st1.date = "'.$date.'" '
                        . 'GROUP BY st1.source_id,st1.user_id,st1.type');

            foreach($txn_details as $data){
                $service_id = $data['st1']['service_id'] == 0?1:$data['st1']['service_id'];
                $key = $data['st1']['source_id'].'_'.$service_id.'_'.$data['st1']['type'];
                $response[$data['st1']['source_id']][$key]['user_id'] = $data['st1']['source_id'];
                $response[$data['st1']['source_id']][$key]['service_id'] = $service_id;
                $response[$data['st1']['source_id']][$key]['amount'] += $data[0]['amount'];
                $response[$data['st1']['source_id']][$key]['tds'] += $data[0]['tds'];
                $response[$data['st1']['source_id']][$key]['type'] = $data['st1']['type'];
            }

            $comm_dist = $this->Slaves->query('SELECT d.user_id,st1.user_id as service_id,sum(st1.amount) as amount,SUM(st2.amount) AS tds,st1.type '
                        . 'FROM '.$tbl.' st1 '
                        . 'JOIN distributors d ON (st1.source_id = d.id) '
                        . 'LEFT JOIN '.$tbl.' st2 ON (st1.id = st2.target_id AND st2.type = '.TDS.')  '
                        . 'WHERE st1.type IN ('.COMMISSION_DISTRIBUTOR.','.COMMISSION_DISTRIBUTOR_REVERSE.') '
                        . 'AND st1.confirm_flag = 0 '
                        . 'AND st1.date = "'.$date.'" '
                        . 'GROUP BY d.user_id,st1.user_id,st1.type');

            foreach($comm_dist as $data){
                $service_id = $data['st1']['service_id'] == 0?1:$data['st1']['service_id'];
                $key = $data['d']['user_id'].'_'.$service_id.'_'.COMMISSION_DISTRIBUTOR;
                $response[$data['d']['user_id']][$key]['user_id'] = $data['d']['user_id'];
                $response[$data['d']['user_id']][$key]['service_id'] = $service_id;
                if($data['st1']['type'] == COMMISSION_DISTRIBUTOR){
                    $response[$data['d']['user_id']][$key]['amount'] += $data[0]['amount'];                     
                }
                else {
                    $response[$data['d']['user_id']][$key]['amount'] -= $data[0]['amount'];
                }
                $response[$data['d']['user_id']][$key]['tds'] += $data[0]['tds'];
                $response[$data['d']['user_id']][$key]['type'] = COMMISSION_DISTRIBUTOR;
            }
            
            $comm_tds = $this->Slaves->query('SELECT st1.source_id,st1.user_id as service_id,SUM(st1.amount) AS tds '
                        . 'FROM '.$tbl.' st1 '
                        . 'WHERE st1.type = '.TDS.' '
                        . 'AND st1.confirm_flag = 0 '
                        . 'AND st1.date = "'.$date.'" '
                        . 'AND st1.target_id = 0 '
                        . 'GROUP BY st1.source_id,st1.user_id');
            
            foreach($comm_tds as $data){
                $service_id = $data['st1']['service_id'] == 0?1:$data['st1']['service_id'];
                $key = $data['st1']['source_id'].'_'.$service_id.'_'.COMMISSION_DISTRIBUTOR;
                $response[$data['st1']['source_id']][$key]['user_id'] = $data['st1']['source_id'];
                $response[$data['st1']['source_id']][$key]['service_id'] = $data['st1']['service_id'];
                $response[$data['st1']['source_id']][$key]['tds'] += $data[0]['tds'];
                $response[$data['st1']['source_id']][$key]['type'] = COMMISSION_DISTRIBUTOR;
            }
            
            $txn_reversed = $this->Slaves->query('SELECT st.source_id,st.user_id as service_id,sum(st.amount) as txn_reverse_amt,st.type,st.type_flag '
                    . 'FROM '.$tbl.' st '
                    . 'WHERE st.type = '.TXN_REVERSE.' '
                    . 'AND st.date = "'.$date.'" '
                    . 'GROUP BY st.source_id,st.user_id,st.type_flag');
            
            foreach($txn_reversed as $data){                
                $key = $data['st']['source_id'].'_'.$data['st']['service_id'].'_'.$data['st']['type_flag'];
                $response[$data['st']['source_id']][$key]['service_id'] = $data['st']['service_id'];                
                $response[$data['st']['source_id']][$key]['type'] = $data['st']['type_flag'];                
                $response[$data['st']['source_id']][$key]['txn_reverse_amt'] = $data[0]['txn_reverse_amt'];                
            }
            
            $parent_ids = $this->Shop->getParentIds(array_filter(array_keys($response)));
            
            $i=1;
            $batch_qry = array();
            
            if($date != date('Y-m-d')){
                $this->Retailer->query("DELETE FROM users_nontxn_logs where date='$date'");
            }

            foreach ($response as $user_id => $earning_data)
            {
                $parent_user_id = isset($parent_ids[$user_id])?$parent_ids[$user_id]:0;

                foreach($earning_data as $data)
                {
                    $tds = isset($data['tds']) && !empty($data['tds'])?$data['tds']:0;
                    $txn_reverse_amt = isset($data['txn_reverse_amt']) && !empty($data['txn_reverse_amt'])?$data['txn_reverse_amt']:0;
                    $batch_qry['type'] = 'INSERT';
                    $batch_qry['predata'] = 'REPLACE INTO users_nontxn_logs(date,user_id,parent_user_id,service_id,amount,tds,txn_reverse_amt,type)VALUES';

                    $batch_qry['data'][] = '("'.$date.'","'.$user_id.'","'.$parent_user_id.'","'.$data['service_id'].'",'
                                            . '"'.$data['amount'].'","'.$tds.'","'.$txn_reverse_amt.'","'.$data['type'].'")';

                    if (($i % 100) == 0){ //process batch of 100 queries together
                    $this->run_batch($batch_qry);
                    $batch_qry = array();
                    }
                    $i++;
                }
            }

            if(!empty($batch_qry)) // handled cases if last batch consist less then 100 queries
            {
                $this->run_batch($batch_qry);
                $batch_qry = array();
            }
        }
        
        function updateUsersLogs($date = NULL,$flag = 0){
            $this->autoRender = false;
            $date = empty($date)?date('Y-m-d') : ($date == 1?date('Y-m-d',strtotime('-1 days')):$date);
            $tbl = $flag == 0?'shop_transactions':'shop_transactions_logs';
            
            $datas = array();
            //topup buy 
            $data = $this->Slaves->query('SELECT SUM(st.amount) as amts,st.target_id,COUNT(st.id) as primary_txn,date,IF(st.type IN ('.DIST_RETL_BALANCE_TRANSFER.','.SLMN_RETL_BALANCE_TRANSFER.'),r.user_id,IF(st.type IN ('.SDIST_DIST_BALANCE_TRANSFER.','.MDIST_DIST_BALANCE_TRANSFER.'),d.user_id,IF(st.type = '.DIST_SLMN_BALANCE_TRANSFER.',s.user_id,IF(st.type = '.MDIST_SDIST_BALANCE_TRANSFER.',sd.user_id,0)))) as user_id '
                    . 'FROM '.$tbl.' st '
                    . 'LEFT JOIN retailers r ON (st.target_id = r.id AND st.type IN ('.DIST_RETL_BALANCE_TRANSFER.','.SLMN_RETL_BALANCE_TRANSFER.')) '
                    . 'LEFT JOIN distributors d ON (st.target_id = d.id AND st.type IN ('.SDIST_DIST_BALANCE_TRANSFER.','.MDIST_DIST_BALANCE_TRANSFER.')) '
                    . 'LEFT JOIN super_distributors sd ON (st.target_id = sd.id AND st.type = '.MDIST_SDIST_BALANCE_TRANSFER.') '
                    . 'LEFT JOIN salesmen s ON (st.target_id = s.id AND st.type = '.DIST_SLMN_BALANCE_TRANSFER.' AND d.user_id != s.user_id) '
                    . 'WHERE st.confirm_flag != 1 '
                    . 'AND st.type IN ('.MDIST_SDIST_BALANCE_TRANSFER.','.SDIST_DIST_BALANCE_TRANSFER.','.MDIST_DIST_BALANCE_TRANSFER.','.DIST_RETL_BALANCE_TRANSFER.','.SLMN_RETL_BALANCE_TRANSFER.','.DIST_SLMN_BALANCE_TRANSFER.') '
                    . 'AND st.date = "'.$date.'" '
                    . 'GROUP BY st.target_id,st.type');
            
            foreach($data as $dt){
                $datas[$dt[0]['user_id']]['buy'] += $dt['0']['amts'];
                $datas[$dt[0]['user_id']]['primary_txn'] += $dt['0']['primary_txn'];
            }
            
            //topup sold
            $data = $this->Slaves->query('SELECT SUM(st.amount) as amts,st.source_id,COUNT(st.id) as cts,st.date,IF(st.type IN ('.DIST_RETL_BALANCE_TRANSFER.','.DIST_SLMN_BALANCE_TRANSFER.'),d.user_id,IF(st.type = '.SLMN_RETL_BALANCE_TRANSFER.',s.user_id,IF(st.type = '.SDIST_DIST_BALANCE_TRANSFER.',sd.user_id,0))) as user_id '
                    . 'FROM '.$tbl.' st '
                    . 'LEFT JOIN distributors d ON (st.source_id = d.id AND st.type IN ('.DIST_RETL_BALANCE_TRANSFER.','.DIST_SLMN_BALANCE_TRANSFER.')) '
                    . 'LEFT JOIN salesmen s ON (st.source_id = s.id AND st.type = '.SLMN_RETL_BALANCE_TRANSFER.' AND d.user_id != s.user_id) '
                    . 'LEFT JOIN super_distributors sd ON (st.source_id = sd.id AND st.type = '.SDIST_DIST_BALANCE_TRANSFER.') '
                    . 'WHERE st.confirm_flag != 1 '
                    . 'AND st.type IN ('.SDIST_DIST_BALANCE_TRANSFER.','.DIST_RETL_BALANCE_TRANSFER.','.SLMN_RETL_BALANCE_TRANSFER.','.DIST_SLMN_BALANCE_TRANSFER.') '
                    . 'AND st.date = "'.$date.'" '
                    . 'GROUP BY st.source_id,st.type');
            
            foreach($data as $dt){
                $datas[$dt[0]['user_id']]['sold'] += $dt['0']['amts'];
                $datas[$dt[0]['user_id']]['unique'] += $dt['0']['cts'];
            }
            
            //total retailers
            $data = $this->Slaves->query('SELECT COUNT(r.id) as cts,d.user_id '
                    . 'FROM retailers r '
                    . 'JOIN distributors d ON (r.parent_id = d.id) '
                    . 'GROUP BY r.parent_id');
            
            foreach($data as $dt){
                $datas[$dt['d']['user_id']]['retailers'] = $dt['0']['cts'];
            }
            
            //total transacting retailers
            $data = $this->User->query('SELECT COUNT(DISTINCT rel.ret_user_id) as cts,rel.dist_user_id '
                    . 'FROM retailer_earning_logs rel '
                    . 'LEFT JOIN distributors d ON (rel.dist_user_id = d.user_id) '
                    . 'WHERE rel.date = "'.$date.'" '
                    . 'GROUP BY rel.dist_user_id');
            
            foreach($data as $dt){
                $datas[$dt['rel']['dist_user_id']]['transacting'] = $dt['0']['cts'];
            }
            
            $pg_topup = $this->Slaves->query('SELECT st.target_id,SUM(st.amount) as pg_topup,st.date,r.user_id '
                . 'FROM '.$tbl.' st '
                . 'JOIN retailers r ON (st.target_id = r.id) '
                . 'WHERE st.date = "'.$date.'" '
                . 'AND st.type IN ("'.DIST_RETL_BALANCE_TRANSFER.'","'.SLMN_RETL_BALANCE_TRANSFER.'") '
                . 'AND st.type_flag = 5 '
                . 'AND st.confirm_flag != 1 AND st.note is not null '
                . 'GROUP BY st.target_id');

            foreach($pg_topup as $data){
                $datas[$data['r']['user_id']]['pg_topup'] = $data[0]['pg_topup'];
            }
            
            //topup reversed
            $topup_reversed = $this->Slaves->query('SELECT SUM(st1.amount) AS topup_reversed,st1.date AS pullback_date,st2.date AS txn_date,st1.source_id,IF(st1.type = '.PULLBACK_RETAILER.',r.user_id,IF(st1.type = '.PULLBACK_DISTRIBUTOR.',d.user_id,IF(st1.type = '.PULLBACK_SALESMAN.',s.user_id,IF(st1.type = '.PULLBACK_SUPERDISTRIBUTOR.',sd.user_id,0)))) as user_id '
                    . 'FROM '.$tbl.' st1 '
                    . 'JOIN '.$tbl.' st2 ON (st1.target_id = st2.id) '
                    . 'LEFT JOIN retailers r ON (st1.source_id = r.id AND st1.type = '.PULLBACK_RETAILER.') '
                    . 'LEFT JOIN distributors d ON (st1.source_id = d.id AND st1.type = '.PULLBACK_DISTRIBUTOR.') '
                    . 'LEFT JOIN salesmen s ON (st1.source_id = s.id AND st1.type = '.PULLBACK_SALESMAN.' AND d.user_id != s.user_id) '
                    . 'LEFT JOIN super_distributors sd ON (st1.source_id = sd.id AND st1.type = '.PULLBACK_SUPERDISTRIBUTOR.') '
                    . 'WHERE st1.type IN ('.PULLBACK_RETAILER.','.PULLBACK_DISTRIBUTOR.','.PULLBACK_SUPERDISTRIBUTOR.','.PULLBACK_SALESMAN.') '
                    . 'AND st1.date = "'.$date.'" '
                    . 'AND st1.date != st2.date '
                    . 'GROUP BY st1.source_id,st1.type');
            
            foreach($topup_reversed as $data){
                $datas[$data[0]['user_id']]['topup_reversed'] += $data[0]['topup_reversed'];
            }
            
            $users_data = $this->Slaves->query('SELECT * FROM users_logs WHERE date = "'.$date.'" ');
            $users_logs = array();
            foreach($users_data as $data){
                $users_logs[$data['users_logs']['user_id']] = $data['users_logs'];
            }
            $user_ids = array_merge(array_keys($users_logs),array_keys($datas));
            
            $parent_ids = $this->Shop->getParentIds(array_filter($user_ids));
            
            foreach ($users_logs as $user_id=>$val) {
                $parent_user_id = isset($parent_ids[$user_id])?$parent_ids[$user_id]:0;
                $opening = $val['opening'];
                $closing = $val['closing'];
                $this->Retailer->query("REPLACE INTO users_logs (user_id,parent_user_id,date,opening,closing) VALUES ($user_id,$parent_user_id,'$date','$opening','$closing')");
            }
            
            foreach($datas as $user_id=>$val){
                if(empty($user_id))$user_id = 0;
                if(!isset($val['buy']))$val['buy'] = 0;
                if(!isset($val['sold']))$val['sold'] = 0;
                if(!isset($val['unique']))$val['unique'] = 0;
                if(!isset($val['retailers']))$val['retailers'] = 0;
                if(!isset($val['transacting']))$val['transacting'] = 0;
                if(!isset($val['primary_txn']))$val['primary_txn'] = 0;
                if(!isset($val['topup_reversed']))$val['topup_reversed'] = 0;
                if(!isset($val['pg_topup']))$val['pg_topup'] = 0;
                $parent_user_id = isset($parent_ids[$user_id])?$parent_ids[$user_id]:0;
                
                $opening = (isset($users_logs[$user_id]['opening'])) ? $users_logs[$user_id]['opening'] : 0;
                $closing = (isset($users_logs[$user_id]['closing'])) ? $users_logs[$user_id]['closing'] : 0;
                
                
//                if(isset($users_logs[$user_id])){
//                    $this->Retailer->query('UPDATE users_logs '
//                            . 'SET parent_user_id = '.$parent_user_id.',topup_buy = '.$val['buy'].',topup_sold = '.$val['sold'].',retailers = '.$val['retailers'].',transacting = '.$val['transacting'].',pg_topup = '.$val['pg_topup'].',topup_unique = '.$val['unique'].',primary_txn = '.$val['primary_txn'].',topup_reversed = '.$val['topup_reversed'].' '
//                            . 'WHERE user_id = "'.$user_id.'" AND date = "'.$date.'" ');                    
//                }else{
//                    $bal = $this->Retailer->query('SELECT opening_balance,balance FROM users WHERE id = '.$user_id.' AND balance > 0');
//                    $opening_bal = isset($bal[0]['users']['opening_balance'])?$bal[0]['users']['opening_balance']:0;
//                    $closing_bal = isset($bal[0]['users']['balance'])?$bal[0]['users']['balance']:0;
                    $this->Retailer->query("REPLACE INTO users_logs (user_id,parent_user_id,retailers,transacting,topup_buy,topup_sold,pg_topup,topup_unique,primary_txn,date,topup_reversed,opening,closing) VALUES ($user_id,$parent_user_id,".$val['retailers'].",".$val['transacting'].",".$val['buy'].",".$val['sold'].",".$val['pg_topup'].",".$val['unique'].",'".$val['primary_txn']."','$date','".$val['topup_reversed']."','$opening','$closing')");
//                }
            }
        }
                
        function vendorRecon($date = NULL){
            
            $date = ($date == NULL) ? date('Y-m-d') : $date;
            $product_vendor = $this->Slaves->query("SELECT id, name,service_id FROM product_vendors");
            
            foreach($product_vendor as $vendor){
                $vendorid        = $vendor['product_vendors']['id'];
                $sale            = $this->Slaves->query("SELECT SUM(wallets_transactions.amount) sale, SUM(vendor_service_charge) service_charge, SUM(vendor_commission) commission,sum(txns_recon.commission) as actual_commission, sum(txns_recon.service_charge) as actual_service_charge FROM `wallets_transactions` left join txns_recon on (wallets_transactions.server = txns_recon.server AND wallets_transactions.date = txns_recon.date AND wallets_transactions.vendor_refid = txns_recon.vendor_txn_id) WHERE wallets_transactions.`date` = '".$date."' AND (`reversal_date` != '".$date."' OR `reversal_date` IS NULL) AND `vendor_id` = '".$vendorid."'");
                
                if($vendor['product_vendors']['service_id'] != 11){
                    $sales = $sale[0][0]['sale'];
                }else{
                    $pragati         = $this->Slaves->query("SELECT `vendor_id`, `product_id`, SUM(amount) amount FROM  `wallets_transactions` WHERE `vendor_id` = '$vendorid' AND date = '".$date."' AND `product_id` IN (75,76) AND (`reversal_date` != '".$date."' OR `reversal_date` IS NULL) GROUP BY `product_id`");
                    foreach($pragati as $pr){
                        $sl[$vendorid][$pr['wallets_transactions']['product_id']] = $pr[0]['amount'];
                    }
                    $sales = (isset($sl[$vendorid][76]) ? $sl[$vendorid][76] : 0) - (isset($sl[$vendorid][75]) ? $sl[$vendorid][75] : 0);
                }
                
                $refund          = $this->Slaves->query("SELECT SUM(wallets_transactions.amount) refund, SUM(vendor_service_charge) service_charge, SUM(vendor_commission) commission,sum(txns_recon.commission) as actual_commission, sum(txns_recon.service_charge) as actual_service_charge FROM `wallets_transactions` left join txns_recon on (wallets_transactions.server = txns_recon.server AND wallets_transactions.date = txns_recon.date AND wallets_transactions.vendor_refid = txns_recon.vendor_txn_id) WHERE wallets_transactions.`date` != '".$date."' AND `reversal_date` = '".$date."' AND `vendor_id` =  '".$vendorid."'");
                $partial_refund  = $this->Slaves->query("SELECT SUM(wpc.amount_refunded) partial_refund FROM wallet_partial_cancellations wpc JOIN wallets_transactions wt ON (wpc.txn_id = wt.txn_id and wpc.server = wt.server) WHERE wpc.date = '".$date."' AND wt.vendor_id = '".$vendorid."'");
                $acc_cat_data    = $this->Slaves->query("SELECT account_category_id, SUM(amount) FROM `account_txn_details` WHERE `account_category_id` IN (105, 106, 107, 108, 109, 110) AND `type_id` = '".$vendorid."' AND DATE(txn_date) = '".$date."' GROUP BY account_category_id ORDER BY account_category_id ASC");

                foreach($acc_cat_data as $dt){
                    $amount[$vendorid][$dt['account_txn_details']['account_category_id']] = $dt['0']['SUM(amount)'];
                }
                
                $topup                   = (isset($amount[$vendorid][105]) ? $amount[$vendorid][105] : 0) - (isset($amount[$vendorid][106]) ? $amount[$vendorid][106] : 0) + (isset($amount[$vendorid][107]) ? $amount[$vendorid][107] : 0);
                $extra_incentive         = isset($amount[$vendorid][107]) ? $amount[$vendorid][107] : 0;
                $setup_fee               = isset($amount[$vendorid][108]) ? $amount[$vendorid][108] : 0;
                $kit_payment             = isset($amount[$vendorid][109]) ? $amount[$vendorid][109] : 0;
                $vendor_security_deposit = isset($amount[$vendorid][110]) ? $amount[$vendorid][110] : 0;
                
                $carry_forward_diff  = $this->Slaves->query("SELECT * FROM `vendor_recon` WHERE vendor_id = '".$vendorid."' AND date < '".$date."' ORDER BY date DESC, id DESC LIMIT 1");
                $exist               = $this->Slaves->query("SELECT * FROM `vendor_recon` WHERE vendor_id = '".$vendorid."' AND date = '".$date."' ORDER BY id DESC LIMIT 1");
                
                //$vendor_comm_sc      = $this->Slaves->query("SELECT SUM(commission) vendor_comm, SUM(service_charge) vendor_sc FROM `txns_recon` WHERE product_vendor_id = '".$vendorid."' AND date = '".$date."' AND status = '1'");

                $vendor_comm_sc[0][0]['vendor_sc'] = ($sale[0][0]['actual_service_charge'] - $refund[0][0]['actual_service_charge']) == '' ? 0 : ($sale[0][0]['actual_service_charge'] - $refund[0][0]['actual_service_charge']);
                $vendor_comm_sc[0][0]['vendor_comm'] = ($sale[0][0]['actual_commission'] - $refund[0][0]['actual_commission']) == '' ? 0 : ($sale[0][0]['actual_commission'] - $refund[0][0]['actual_commission']);
                
                if(!empty($exist)){
                    $this->User->query("UPDATE `vendor_recon` SET `sale`= '".$sales."',`topup`= '".$topup."',`refund`= '".(($refund[0][0]['refund'] == '' ? 0 : $refund[0][0]['refund']) + $partial_refund[0][0]['partial_refund'])."',`service_charge`= '".(($sale[0][0]['service_charge'] - $refund[0][0]['service_charge']) == '' ? 0 : ($sale[0][0]['service_charge'] - $refund[0][0]['service_charge']))."',`commission`= '".(($sale[0][0]['commission'] - $refund[0][0]['commission']) == '' ? 0 : ($sale[0][0]['commission'] - $refund[0][0]['commission']))."',`kit_payment`= '".$kit_payment."',`extra_incentive`= '".$extra_incentive."', `setup_fee` = '".$setup_fee."', `security_deposit` = '".($vendor_security_deposit + $carry_forward_diff[0]['vendor_recon']['security_deposit'])."', `vendor_sc` = '".$vendor_comm_sc[0][0]['vendor_sc']."', `vendor_comm` = '".$vendor_comm_sc[0][0]['vendor_comm']."' WHERE `vendor_id` = '".$vendor['product_vendors']['id']."' AND `date`= '".$date."'");
                } else {
                    $this->User->query("INSERT INTO `vendor_recon`(`date`, `vendor_id`, `closing`, `opening`, `sale`, `topup`, `refund`, `service_charge`, `commission`, `kit_payment`,`extra_incentive`,`setup_fee`, `security_deposit`, `vendor_sc`, `vendor_comm`) VALUES ('".$date."','".$vendor['product_vendors']['id']."',0,0,'".$sales."','".$topup."','".(($refund[0][0]['refund'] == '' ? 0 : $refund[0][0]['refund']) + $partial_refund[0][0]['partial_refund'])."','".(($sale[0][0]['service_charge'] - $refund[0][0]['service_charge']) == '' ? 0 : ($sale[0][0]['service_charge'] - $refund[0][0]['service_charge']))."','".(($sale[0][0]['commission'] - $refund[0][0]['commission']) == '' ? 0 : ($sale[0][0]['commission'] - $refund[0][0]['commission']))."','".$kit_payment."','".$extra_incentive."', '".$setup_fee."','".($vendor_security_deposit + $carry_forward_diff[0]['vendor_recon']['security_deposit'])."','".$vendor_comm_sc[0][0]['vendor_sc']."','".$vendor_comm_sc[0][0]['vendor_comm']."')");
                }
            }
            
            die;
        }
        
        function lastLogin() { 
            $this->autoRender = false;

            $user = $this->Slaves->query("SELECT users.id FROM `users` WHERE last_login <= '".date('Y-m-d', strtotime('-15 day'))."'");
            foreach($user as $usr){
                if($this->User->query("DELETE  FROM `user_profile` WHERE `user_id` = ".$usr['users']['id'])){
                    $this->User->query("UPDATE `users` SET `last_login`= '".date('Y-m-d')."' WHERE `id` =".$usr['users']['id']);
                }
            }
	}

        function deductDailyRental($date = NULL){
            $this->autoRender = false;
            $date = !empty($date)?$date:date('Y-m-d',strtotime('-1 days'));
            $dist_user_ids = Configure::read('SCHEME_DISTRIBUTOR_IDS');
            $ret_sales_data = $this->Slaves->query('SELECT rel.ret_user_id,SUM(rel.amount) as sale,r.mobile FROM retailer_earning_logs rel JOIN retailers r ON (rel.ret_user_id = r.user_id) WHERE rel.date = "'.$date.'" AND rel.dist_user_id IN ('. implode(',', $dist_user_ids).') AND rel.service_id IN (1,2) GROUP BY rel.ret_user_id HAVING (SUM(rel.amount) > 0) ');
            
            foreach ($ret_sales_data as $data) {
                if($data[0]['sale'] < 2000){
                    $user_id = $data['rel']['ret_user_id'];
                    $rental_amt = 5;
                    $description = 'Mobile recharge daily rental';
                    $dataSource = $this->User->getDataSource();
                    $dataSource->begin();
                    $bal = $this->Shop->shopBalanceUpdate($rental_amt, 'subtract', $data['rel']['ret_user_id'], RETAILER,$dataSource);
                    if($bal){
                        $res = $this->Shop->shopTransactionUpdate(RENTAL,$rental_amt,$user_id,0,1,null,null,$description,$bal+$rental_amt,$bal,null,null,$dataSource);
                        if($res){
                            $dataSource->commit();
                            $mobile = $data['r']['mobile'];
                            $MsgTemplate = $this->General->LoadApiBalance();
                            $paramdata['RENTAL_AMOUNT'] = 5;
                            $paramdata['SALE_AMT'] = 2000;

                            $content = $MsgTemplate['DAILY_RENTAL_DEDUCTION_MSG'];
                            $message = $this->General->ReplaceMultiWord($paramdata, $content);
                            $this->General->sendMessage($mobile, $message, 'notify');
                            $this->General->logData("/mnt/logs/ret_daily_rental.txt", date('Y-m-d H:i:s') . " Mobile : ".$mobile." Rental deduction msg :: ".$message);
                        }else{
                            $dataSource->rollback();
                        }
                    }
                }
            }
        }

        function updateAssetLiabilityLogs() { 
                $this->autoRender = false;

                $previous_date = $_REQUEST['date']!=""?date('Y-m-d',strtotime($_REQUEST['date']." -1 days")):date('Y-m-d',strtotime("-2 days"));
                $current_date = $_REQUEST['date']!=""?date('Y-m-d',strtotime($_REQUEST['date'])):date('Y-m-d',strtotime("-1 days"));


                /*Get Previous Day Closing*/
                $prev_closing_array = array();
                $assets_liability_log = $this->Slaves->query("
                    SELECT 
                        master_id,closing 
                    FROM 
                        asset_liability_logs al 
                    WHERE 
                        date = '".$previous_date."' 
                    GROUP BY
                        master_id
                    ");
                    if(!empty($assets_liability_log)){
                        foreach($assets_liability_log as $closing_log){
                            $master_id = $closing_log['al']['master_id'];
                            $closing = $closing_log['al']['closing'];

                            $prev_closing_array[$master_id] = $closing;
                        }                         
                    }
                /*End previous closing*/

                /*Get Todays Incoming and Outgoing*/
                $incoming_array = array();
                $outgoing_array = array();
                $get_current_incoming_outgoing = $this->Slaves->query("
                    SELECT 
                        mal.id as master_id,
                        mal.parent_name,
                        SUM(Case When atd.txn_status = 'Cr' AND DATE_FORMAT(txn_date, '%Y-%m-%d') = '".$current_date."'
                                 Then amount Else 0 End) incoming,
                        SUM(Case When atd.txn_status = 'Dr' AND DATE_FORMAT(txn_date, '%Y-%m-%d') = '".$current_date."'
                                 Then amount Else 0 End) outgoing
                    FROM 
                        `master_assets_liability` mal
                    LEFT JOIN 
                        account_txn_details atd ON atd.account_category_id = mal.account_category_id
                    WHERE
                        mal.status=1
                    GROUP BY mal.parent_id
                    ");
                if(!empty($get_current_incoming_outgoing)){

                        foreach($get_current_incoming_outgoing as $current_incoming_outgoing){
                            $master_id = $current_incoming_outgoing['mal']['master_id'];
                            $incoming = $current_incoming_outgoing[0]['incoming'];
                            $outgoing = $current_incoming_outgoing[0]['outgoing'];

                            $incoming_array[$master_id] = $incoming;
                            $outgoing_array[$master_id] = $outgoing;
                        }                       
                    }
                /*End Incoming and outgoing*/
                               
                $master_assets_liability = $this->Slaves->query("
                    SELECT 
                        * 
                    FROM 
                        master_assets_liability mal 
                    WHERE 
                        mal.status = 1
                    GROUP BY 
                        mal.parent_id   
                ");
                foreach($master_assets_liability as $master){

                    $master_id = $master['mal']['id'];
                    $type = $master['mal']['type'];

                    if(!empty($prev_closing_array)){
                        $opening = $prev_closing_array[$master_id];
                        $incoming = $incoming_array[$master_id];
                        $outgoing = $outgoing_array[$master_id];
                        if($type == 1){
                            $closing = $opening - $incoming + $outgoing;
                        }else{
                            $closing = $opening + $incoming - $outgoing;
                        }
                        

                        $chk_log_exist = $this->Slaves->query("
                            SELECT
                                id
                            FROM
                                asset_liability_logs
                            WHERE
                                master_id = '".$master_id ."' AND
                                date = '".$current_date."'
                            ");
                        if(empty($chk_log_exist)){
                            $this->User->query("INSERT INTO asset_liability_logs(master_id, opening, closing, incoming, outgoing, `date`)  VALUES('".$master_id."','".$opening."','".$closing."','".$incoming."','".$outgoing."','".$current_date."')");
                        }else{
                            $this->User->query("UPDATE asset_liability_logs SET opening = '".$opening."', closing = '".$closing."', incoming = '".$incoming."', outgoing = '".$outgoing."' WHERE  master_id = '".$master_id ."' AND date = '".$current_date."'");
                        }
                    }

                }
        }

        function updateFloatReport($date = NULL){
            
                $this->autoRender = false;

                $current_date = ($date != NULL) ? date('Y-m-d', strtotime($date)) : date('Y-m-d', strtotime("-1 days"));

                /*Topup*/
                $topup_array = array();
                $topup_distributor_query = $this->Slaves->query("
                    SELECT SUM(amount) amount,target_id as role_id FROM `shop_transactions` st WHERE type = '".MDIST_DIST_BALANCE_TRANSFER."' AND target_id != '1' AND date = '$current_date' AND confirm_flag != '1' GROUP BY target_id
                    ");
                if(!empty($topup_distributor_query)){
                    foreach ($topup_distributor_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['st']['role_id'];
                        $topup_array[DISTRIBUTOR][$role_id] = $amount;
                    }
                }

                $topup_retailer_query = $this->Slaves->query("
                    SELECT SUM(amount) amount,target_id as role_id FROM `shop_transactions` st WHERE type = '".DIST_RETL_BALANCE_TRANSFER."' AND source_id = '1' AND date = '$current_date' AND confirm_flag != '1' GROUP BY target_id
                    ");
                if(!empty($topup_retailer_query)){
                    foreach ($topup_retailer_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['st']['role_id'];
                        $topup_array[RETAILER][$role_id] = $amount;
                    }
                }

                $topup_sd_query = $this->Slaves->query("
                    SELECT SUM(amount) amount,target_id as role_id FROM `shop_transactions` st WHERE type = '".MDIST_SDIST_BALANCE_TRANSFER."' AND target_id != '3' AND date = '$current_date' AND confirm_flag != '1' GROUP BY target_id
                    ");
                if(!empty($topup_sd_query)){
                    foreach ($topup_sd_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['st']['role_id'];
                        $topup_array[SUPER_DISTRIBUTOR][$role_id] = $amount;
                    }
                }

                /*Topup Reverse*/
                $topup_reverse_array = array();

                $topup_reverse_distributor_query = $this->Slaves->query("
                    SELECT SUM(shop_transactions.amount) amount, shop_transactions.source_id as role_id FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type = '".PULLBACK_DISTRIBUTOR."' AND st.type = '".MDIST_DIST_BALANCE_TRANSFER."' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' AND st.source_id != '1' GROUP BY shop_transactions.source_id");
                 if(!empty($topup_reverse_distributor_query)){
                    foreach ($topup_reverse_distributor_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $topup_reverse_array[DISTRIBUTOR][$role_id] = $amount;
                    }
                }

                $topup_reverse_retailer_query = $this->Slaves->query("
                    SELECT SUM(shop_transactions.amount) amount, shop_transactions.source_id as role_id FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type = '".PULLBACK_RETAILER."' AND st.type = '".DIST_RETL_BALANCE_TRANSFER."' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' AND st.source_id = '1' GROUP BY shop_transactions.source_id");
                 if(!empty($topup_reverse_retailer_query)){
                    foreach ($topup_reverse_retailer_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $topup_reverse_array[RETAILER][$role_id] = $amount;
                    }
                }

                $topup_reverse_sd_query = $this->Slaves->query("
                    SELECT SUM(shop_transactions.amount) amount, shop_transactions.source_id as role_id  FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type = '".PULLBACK_SUPERDISTRIBUTOR."' AND st.type = '".MDIST_SDIST_BALANCE_TRANSFER."' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' GROUP BY shop_transactions.source_id");
                 if(!empty($topup_reverse_sd_query)){
                    foreach ($topup_reverse_sd_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $topup_reverse_array[SUPER_DISTRIBUTOR][$role_id] = $amount;
                    }
                }

                

                /*Sale Cr*/
                $sale_cr_array = array();
                $sale_cr_query = $this->Slaves->query("SELECT SUM(amount) amount,source_id as role_id FROM `shop_transactions` WHERE date = '$current_date' AND type = '".CREDIT_NOTE."' AND confirm_flag != '1' GROUP BY source_id");
                if(!empty($sale_cr_query)){
                    foreach ($sale_cr_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $sale_cr_array[0][$role_id] = $amount;
                    }
                }

                /*Sale Dr*/
                $sale_dr_array = array();
                $sale_dr_retailer_query = $this->Slaves->query("SELECT SUM(amount) amount,source_id as role_id FROM `shop_transactions` WHERE date = '$current_date' AND type = '".RETAILER_ACTIVATION."' AND confirm_flag = '1' GROUP BY source_id");
                if(!empty($sale_dr_retailer_query)){
                    foreach ($sale_dr_retailer_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $sale_dr_array[RETAILER][$role_id] = $amount;
                    }
                }

                $sale_dr_user_query = $this->Slaves->query("SELECT SUM(amount) amount,source_id as role_id FROM `shop_transactions` WHERE date = '$current_date' AND type = '".DEBIT_NOTE."' AND confirm_flag != '1' GROUP BY source_id ");
                if(!empty($sale_dr_user_query)){
                    foreach ($sale_dr_user_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $sale_dr_array[0][$role_id] = $amount;
                    }
                }

                /*Sale Reverse Cr*/
                $sale_reverse_cr_array = array();
                $sale_reverse_cr_query = $this->Slaves->query("SELECT SUM(shop_transactions.amount) amount,shop_transactions.source_id as role_id FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type IN ('".VOID_TXN."','".TXN_CANCEL_REFUND."') AND st.type = '".CREDIT_NOTE."' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' GROUP BY shop_transactions.source_id");
                if(!empty($sale_reverse_cr_query)){
                    foreach ($sale_reverse_cr_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $sale_reverse_cr_array[0][$role_id] = $amount;
                    }
                }

                /*Sale Reverse Dr*/
                $sale_reverse_dr_array = array();
                $sale_reverse_retailer_dr_query = $this->Slaves->query("SELECT SUM(shop_transactions.amount) amount,shop_transactions.source_id as role_id FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type = '".REVERSAL_RETAILER."' AND st.type = '".MASTER_DISTRIBUTOR."' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' GROUP BY shop_transactions.source_id");
                if(!empty($sale_reverse_retailer_dr_query)){
                    foreach ($sale_reverse_retailer_dr_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $sale_reverse_dr_array[RETAILER][$role_id] = $amount;
                    }
                }

                $sale_reverse_user_dr_query = $this->Slaves->query("SELECT SUM(shop_transactions.amount) amount,shop_transactions.source_id as role_id FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type IN ('".VOID_TXN."','".TXN_CANCEL_REFUND."') AND st.type = '".DEBIT_NOTE."' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' GROUP BY shop_transactions.source_id");
                if(!empty($sale_reverse_user_dr_query)){
                    foreach ($sale_reverse_user_dr_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $sale_reverse_dr_array[0][$role_id] = $amount;
                    }
                }


                /*Commision*/
                $commission_array = array();
                $commision_md_query = $this->Slaves->query("SELECT SUM(amount) amount, source_id as role_id FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION_MASTERDISTRIBUTOR."' AND confirm_flag != '1' GROUP BY source_id");
                if(!empty($commision_md_query)){
                    foreach ($commision_md_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['st']['role_id'];
                        $commission_array[MASTER_DISTRIBUTOR][$role_id] = $amount;
                    }
                }

                $commision_dist_query = $this->Slaves->query("SELECT SUM(amount) amount FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION_DISTRIBUTOR."' AND confirm_flag != '1' GROUP BY source_id");
                if(!empty($commision_dist_query)){
                    foreach ($commision_dist_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['st']['role_id'];
                        $commission_array[DISTRIBUTOR][$role_id] = $amount;
                    }
                }

                $commision_ret_query = $this->Slaves->query("SELECT SUM(amount) amount FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION_RETAILER."' AND confirm_flag != '1' GROUP BY source_id");
                if(!empty($commision_ret_query)){
                    foreach ($commision_ret_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['st']['role_id'];
                        $commission_array[RETAILER][$role_id] = $amount;
                    }
                }

                $commision_user_query = $this->Slaves->query("SELECT SUM(amount) amount FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION."' AND confirm_flag != '1' GROUP BY source_id");
                if(!empty($commision_user_query)){
                    foreach ($commision_user_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['st']['role_id'];
                        $commission_array[0][$role_id] = $amount;
                    }
                }

                $commision_sd_query = $this->Slaves->query("SELECT SUM(amount) amount FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION_SUPERDISTRIBUTOR."' AND confirm_flag != '1' GROUP BY source_id");
                if(!empty($commision_sd_query)){
                    foreach ($commision_sd_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['st']['role_id'];
                        $commission_array[SUPER_DISTRIBUTOR][$role_id] = $amount;
                    }
                }

                

                /*Commision reverse*/
                $commission_reverse_array = array();
                $commission_reverse_query = $this->Slaves->query("SELECT SUM(amount) amount, source_id as role_id FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION_DISTRIBUTOR_REVERSE."' AND confirm_flag != '1' GROUP BY source_id");
                if(!empty($commission_reverse_query)){
                    foreach ($commission_reverse_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $commission_reverse_array[0][$role_id] = $amount;
                    }
                }

                /*TDS*/
                $tds_array = array();
                $tds_query = $this->Slaves->query("SELECT SUM(amount) amount, source_id as role_id FROM `shop_transactions` WHERE date = '$current_date' AND type = '".TDS."' AND confirm_flag != '1' GROUP BY source_id");
                if(!empty($tds_query)){
                    foreach ($tds_query as $data) {
                        $amount = $data[0]['amount'];
                        $role_id = $data['shop_transactions']['role_id'];
                        $tds_array[0][$role_id] = $amount;
                    }
                }

                /*Other Charges*/
                $other_charge_array = array();
                $other_charge_query = $this->Slaves->query("SELECT type, SUM(amount) amount,source_id as role_id FROM `shop_transactions` WHERE date = '$current_date' AND type IN ('".SETUP_FEE."','".SERVICE_CHARGE."','".KITCHARGE."','".SECURITY_DEPOSIT."','".ONE_TIME_CHARGE."','".RENTAL."','".REFUND."') GROUP BY type,source_id");
                foreach($other_charge_query as $oc){
                        $role_id = $oc['shop_transactions']['role_id'];
                        $type = $oc['shop_transactions']['type'];
                        $amount = $oc[0]['amount'];

                        $other_charge_array[$role_id][$type] = $amount;
                }

                /*Other Charges Reverse*/
                $other_charge_reverse_array = array();
                $other_charge_reverse_query = $this->Slaves->query("SELECT type_flag, SUM(amount) amount,source_id as role_id FROM `shop_transactions` WHERE type = '".TXN_REVERSE."' AND date = '$current_date' GROUP BY type_flag,source_id");
                foreach($other_charge_reverse_query as $orc){
                        $role_id = $orc['shop_transactions']['role_id'];
                        $type_flag = $orc['shop_transactions']['type_flag'];
                        $amount = $orc[0]['amount'];

                        $other_charge_reverse_array[$role_id][$type_flag] = $amount;
                }

                /*User groups*/
                $group_array = array();
                $get_groups = $this->Slaves->query("SELECT group_id,user_id FROM user_groups WHERE group_id IN (".DISTRIBUTOR.",".RETAILER.",".SUPER_DISTRIBUTOR.",".MASTER_DISTRIBUTOR.")");
                foreach($get_groups as $groups){
                        $user_id = $groups['user_groups']['user_id'];
                        $group_id = $groups['user_groups']['group_id'];
                        $table = array(DISTRIBUTOR => 'distributors', RETAILER => 'retailers', SUPER_DISTRIBUTOR => 'super_distributors', MASTER_DISTRIBUTOR => 'master_distributors');

                        if($table[$group_id] != ''){
                                $get_role_id = $this->Slaves->query("SELECT id FROM $table[$group_id] WHERE user_id = '$user_id'");
                                $group_array[$user_id][$group_id] = $get_role_id[0][$table[$group_id]]['id'];
                        }
                }

                /*Opening Closing*/
                $opening_array = array();
                $closing_array = array();
                 $get_all_users = $this->Slaves->query("SELECT users.id, users_logs.opening, users_logs.closing FROM users LEFT JOIN users_logs ON (users.id = users_logs.user_id AND users_logs.date = '$current_date')");
                 if(!empty($get_all_users)){
                    foreach ($get_all_users as $data) {
                        $user_id = $data['users']['id'];
                        $opening_array[$user_id] = $data['users_logs']['opening'];
                        $closing_array[$user_id] = $data['users_logs']['closing'];
                    }
                }

                if(!empty($group_array)){

                    $insert_data = array();

                    foreach($group_array as $user_id=>$group_ids){

                        $opening = $opening_array[$user_id];
                        $closing = $closing_array[$user_id];

                        $topup = $topup_array[DISTRIBUTOR][$group_ids[DISTRIBUTOR]] + $topup_array[RETAILER][$group_ids[RETAILER]] + $topup_array[SUPER_DISTRIBUTOR][$group_ids[SUPER_DISTRIBUTOR]];

                        $topup_reverse = $topup_reverse_array[DISTRIBUTOR][$group_ids[DISTRIBUTOR]] + $topup_reverse_array[RETAILER][$group_ids[RETAILER]] + $topup_reverse_array[SUPER_DISTRIBUTOR][$group_ids[SUPER_DISTRIBUTOR]];

                        $sale_cr = $sale_cr_array[0][$user_id];

                        $sale_dr = $sale_dr_array[RETAILER][$group_ids[RETAILER]] + $sale_dr_array[0][$user_id];

                        $sale_reverse_cr = $sale_reverse_cr_array[0][$user_id];

                        $sale_reverse_dr = $sale_reverse_dr_array[RETAILER][$group_ids[RETAILER]] + $sale_reverse_dr_array[0][$user_id];

                        $commission = $commission_array[MASTER_DISTRIBUTOR][$group_ids[MASTER_DISTRIBUTOR]] + $commission_array[DISTRIBUTOR][$group_ids[DISTRIBUTOR]] + $commission_array[RETAILER][$group_ids[RETAILER]] + $commission_array[0][$user_id] + $commission_array[SUPER_DISTRIBUTOR][$group_ids[SUPER_DISTRIBUTOR]];

                        $commission_reverse = $commission_reverse_array[0][$user_id];

                        $tds = $tds_array[0][$user_id];

                        $setup_fee = $other_charge_array[$user_id][SETUP_FEE] - $other_charge_reverse_array[$user_id][SETUP_FEE];

                        $service_charge = $other_charge_array[$user_id][SERVICE_CHARGE] - $other_charge_reverse_array[$user_id][SERVICE_CHARGE];

                        $kit_charge = $other_charge_array[$user_id][KITCHARGE] - $other_charge_reverse_array[$user_id][KITCHARGE];

                        $security_deposit = $other_charge_array[$user_id][SECURITY_DEPOSIT] - $other_charge_reverse_array[$user_id][SECURITY_DEPOSIT];

                        $one_time_charge = $other_charge_array[$user_id][ONE_TIME_CHARGE] - $other_charge_reverse_array[$user_id][ONE_TIME_CHARGE];

                        $rental = $other_charge_array[$user_id][RENTAL] - $other_charge_reverse_array[$user_id][RENTAL];

                        $incentive = $other_charge_array[$user_id][REFUND] - $other_charge_reverse_array[$user_id][REFUND];

                         $insert_data[] = "('$current_date',$user_id, '$opening','$closing','$topup','$topup_reverse','$sale_dr','$sale_cr','$sale_reverse_cr','$sale_reverse_dr','$commission','$commission_reverse','$tds','$setup_fee','$service_charge','$kit_charge','$security_deposit','$one_time_charge','$rental','$incentive')";

                    }


                    if(!empty($insert_data)){
                        $chunk_data = array_chunk($insert_data, 500);

                        foreach ($chunk_data as $value) {

                            $this->User->query("REPLACE INTO float_report(date, user_id, opening, closing, topup, topup_reverse, sale_dr, sale_cr, sale_reverse_cr, sale_reverse_dr, commission, commission_reverse, tds, setup_fee, service_charge, kit_charge, security_deposit, one_time_charge, rental, incentive)  VALUES " . implode(', ', $value));
                        }

                    }
                }    
        }

}
