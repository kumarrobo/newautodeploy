<?php
class RechargesController extends AppController{
    var $name = 'Recharges';
    var $components = array('RequestHandler', 'Shop', 'Busvendors', 'General', 'B2cextender', 'Recharge', 'Jio','ApiRecharge');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator');
    var $uses = array('User', 'Slaves');
    var $sdiff = array();

    function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('*');
    }
    
    // reversal
    function updateModemBalance(){
        $vendor = trim($_REQUEST['vendor']);
        $this->General->logData("/mnt/logs/modem_balance.txt", date('Y-m-d H:i:s') . "::$vendor");
        
        $bal = trim($_REQUEST['balance']);
        $bal1 = json_decode($_REQUEST['balance'], true);
        
        $this->Shop->healthyVendor($vendor);
        
        if( ! empty($bal1)){
            $this->General->logData("/mnt/logs/modem_balance.txt", date('Y-m-d H:i:s') . "::$vendor::$bal");
            
            $this->Shop->setMemcache("balance_$vendor", $_REQUEST['balance'], 5 * 24 * 60 * 60);
            $this->Shop->setMemcache("balance_timestamp_$vendor" . "_last", date('Y-m-d H:i:s'), 5 * 24 * 60 * 60);
            if(isset($_REQUEST['ports'])){
                $this->Shop->setMemcache("balance_ports_$vendor", $_REQUEST['ports'], 5 * 24 * 60 * 60);
            }
            
            /*
             * Set SimPanel Data into Memcache & Devices table
             * Start
             */
            $this->sdiff = array();
            $this->pushSimsintoMemcache($vendor, $_REQUEST['balance']);
            $this->pushSimsintoDevices_data($vendor, $_REQUEST['balance']);
            /*
             * End
             */
        }
        else{
            $bal = $this->Shop->getMemcache("balance_$vendor");
            $this->General->logData("/mnt/logs/modem_balance.txt", date('Y-m-d H:i:s') . "::$vendor::3::$bal");
            
            if($bal === false){
                $this->Shop->setMemcache("balance_$vendor", json_encode(array()), 5 * 24 * 60 * 60);
                $this->Shop->setMemcache("balance_timestamp_$vendor" . "_last", date('Y-m-d H:i:s'), 5 * 24 * 60 * 60);
                if(isset($_REQUEST['ports'])){
                    $this->Shop->setMemcache("balance_ports_$vendor", $_REQUEST['ports'], 5 * 24 * 60 * 60);
                }
            }
        }
        
        $this->autoRender = false;
    }

    public function pushSimsintoDevices_data($modem_id, $simInfo = null){
        $this->autoRender = false;
        
        $temp_s_o_id = array();
        $sqlarray = array();
        $so_ids = $this->Slaves->query("Select id,supplier_id,operator_id from inv_supplier_operator");
        
        foreach($so_ids as $so_id):
            $temp_s_o_id[$so_id['inv_supplier_operator']['supplier_id'] . "_" . $so_id['inv_supplier_operator']['operator_id']] = $so_id['inv_supplier_operator']['id'];
        endforeach
        ;
        
        $vendor_id = $modem_id;
        
        $data = $simInfo;
        $date = date('Y-m-d');
        
        if( ! empty($data)):
            $data_before_insert = $this->Slaves->query("SELECT sum(sale) as totsale, count(id) as cts from devices_data where sync_date='{$date}' AND vendor_id='{$vendor_id}' ");
            sleep(1);
            
            $tot_sale = 0;
            $tot_count = 0;
            
            $data = json_decode($data);
            
            foreach($data as $sim):
                $sql = "";
                // if($sim->inv_supplier_id>0 && $sim->opr_id>0):
                
                $suppplier_operator_id = array_key_exists($sim->inv_supplier_id . "_" . $sim->opr_id, $temp_s_o_id) ? $temp_s_o_id[$sim->inv_supplier_id . "_" . $sim->opr_id] : 0;
                $curr_timestamp = date("Y-m-d H:i:s");
                $sql .= "('{$vendor_id}',";
                $sdiffkey = $sim->opr_id . "_" . $sim->mobile . "_" . $sim->vendor;
                $process_time = isset($sim->process_time) ? $sim->process_time : "";
                $server_diff = isset($this->sdiff[$sdiffkey]) ? $this->sdiff[$sdiffkey] : 0;
                $sql .= "'{$sim->id}','{$suppplier_operator_id}','{$sim->inv_supplier_id}','{$date}','{$curr_timestamp}','{$sim->machine_id}','{$sim->scid}','{$sim->mobile}','{$sim->operator}','{$sim->circle}','{$sim->type}',";
                $sql .= "'{$sim->pin}','{$sim->balance}','{$sim->opr_id}','{$sim->commission}','{$sim->limit}','{$sim->limit_today}','{$sim->roaming_limit}',";
                $sql .= "'{$sim->roaming_today}','" . addslashes($sim->vendor) . "','{$sim->last_block_date}','" . addslashes($sim->vendor_tag) . "','{$sim->block}',";
                $sql .= "'{$sim->device_num}','{$sim->bus}','{$sim->bus_dev}','{$sim->par_bal}','{$sim->state}',";
                $sql .= "'{$sim->active_flag}','{$sim->recharge_flag}','{$sim->receive_flag}','{$sim->stop_flag}','{$sim->signal}',";
                $sql .= "'{$sim->tfr}','{$sim->inc}','{$sim->opening}','{$sim->sale}','{$sim->success}','{$sim->last}','{$server_diff}','{$process_time}','{$sim->recharge_method}','{$sim->block_tag}','{$sim->bal_range}' ";
                $sql .= ")";
                $sqlarray[] = $sql;
                $tot_sale += $sim->sale;
                $tot_count ++ ;
                
                // endif;
            endforeach
            ;
            $this->General->logData("device_data.txt","$vendor_id:: $tot_sale:: $tot_count");
            
            $dataSource = $this->User->getDataSource();
            $dataSource->begin();
            
//            $isDeleted = $this->User->query("Delete from devices_data where sync_date='{$date}' AND vendor_id='{$vendor_id}' ");
            
            $batch = $this->createSqlBatch($sqlarray);
            $this->General->logData("device_data.txt","$vendor_id:: $tot_sale:: $tot_count::below batch");
            
            if( ! empty($batch)):
                $f = 1;
                foreach($batch as $q):
                    if( ! $this->User->query($q)):
                        $this->General->logData('/mnt/logs/realtimesyncdata' . date('Y-m-d') . '.txt', $q);
                        $f = 0;
                        break;
                    
                    
                                                                         endif;
                endforeach
                ;
            
            
                                            endif;
            
            sleep(1);
            // $data_after_insert = $this->User->query("SELECT sum(sale) as totsale, count(id) as cts from devices_data where sync_date='{$date}' AND vendor_id='{$vendor_id}' ");
            $mail_body = "Vendor: $vendor_id , timestamp:" . date('Y-m-d H:i:s');
            $mail_body .= "<br/>Sale before new data: " . $data_before_insert[0][0]['totsale'];
            $mail_body .= "<br/>Sale in new data: " . $tot_sale;
            
            $mail_body .= "<br/>Sim count before new data: " . $data_before_insert[0][0]['cts'];
            $mail_body .= "<br/>Sim count in new data: " . $tot_count;
            
            try{
                //if($isDeleted && $f && ($tot_sale - $data_before_insert[0][0]['totsale'] >=  - 10000 && $tot_sale - $data_before_insert[0][0]['totsale'] <= 50000) || (date('H:i') < '00:10')):
//                if($isDeleted && $f):
                if($f):
                
                $dataSource->commit();
                
                
                else:
                    $dataSource->rollback();
                    $this->General->sendMails('Modem data mismatch', $mail_body, array('ashish@pay1.in', 'nandan.rana@pay1.in', 'lalit.kumar@pay1.in', 'chetan.yadav@pay1.in'), 'mail');
                endif;
            }
            catch(Exception $e){
                $dataSource->rollback();
                $this->General->sendMails('Modem data mismatch', $mail_body, array('ashish@pay1.in', 'nandan.rana@pay1.in', 'lalit.kumar@pay1.in', 'chetan.yadav@pay1.in'), 'mail');
            }
            
            echo "Done";
        
            endif;
    }
    
    function ccaBillerInfo($biller_id) {
        
        $res = $this->ApiRecharge->ccaBillerInfo($biller_id);
        echo json_encode($res); die;
    }

    function ccaUtilityBillFetch() {
        
        $arr = array();
        $res = $this->ApiRecharge->ccaUtilityBillFetch($arr, '1');
        echo json_encode($res); die;
    }
    
    function ccaUtilityBillPayment() {
        
        $arr = array();
        $res = $this->ApiRecharge->ccaUtilityBillPayment('1', $arr, '1');
        echo json_encode($res); die;
    }
    
    function ccaUtilityBillPaymentQuickPay() {
        
        $arr = array();
        $res = $this->ApiRecharge->ccaUtilityBillPaymentQuickPay('1', $arr, '1');
        die;
    }
    
    function ccaTranStatus($tran_id) {
        
        $arr = array();
        $res = $this->ApiRecharge->ccaTranStatus($tran_id);
        die;
    }
    
    function ccaComplaintRegistration() {
        
        $arr = array();
        $res = $this->ApiRecharge->ccaComplaintRegistration();
        die;
    }
    
    function ccaComplaintTracking() {
        
        $arr = array();
        $res = $this->ApiRecharge->ccaComplaintTracking();
        die;
    }
    
    function ccaBalanceApi() {
        $arr = array();
        $res = $this->ApiRecharge->ccaBalanceCheckApi();
        echo "<pre>";print_r($res);
        die;
    }
    
    public function createSqlBatch($sqlarray){
        if( ! empty($sqlarray)):
            
            $tobeReturned = "";
            $mainsql = "REPLACE INTO devices_data(vendor_id," . "device_id,supplier_operator_id,inv_supplier_id,sync_date,sync_timestamp,machine_id,scid,mobile,operator,circle,type," . "pin,balance,opr_id,commission,`limit`,limit_today,roaming_limit," . "roaming_today,vendor,last_block_date,vendor_tag,block," . "device_num,bus,bus_dev,par_bal,state," . "active_flag,recharge_flag,receive_flag,stop_flag,`signal`," . "tfr,inc,opening,sale,success,`last`,server_diff,process_time,recharge_method,blocktag_id,bal_range) values";
            $i = 0;
            
            $temp = array_chunk($sqlarray, 100);
            
            foreach($temp as $key=>$value):
                $tobeReturned[] = $mainsql . implode(',', $value);
            endforeach
            ;
            
            return $tobeReturned;
        
        

                              endif;
        
        return;
    }

    public function pushSimsintoMemcache($modem_id, $simInfo = null){
        $this->autoRender = false;
        
        // $simInfo=$this->Shop->getMemcache("balance_$modem_id");
        
        $date = date('Y-m-d');
        
        if( ! empty($simInfo)):
            
            $simInfo = json_decode($simInfo);
            
            // Get Server Diff
            $diffs = $this->getServerDiffByModemId($modem_id, $date);
            
            // Push Server Diff into sims
            $simInfo = $this->setServerDiff($simInfo, $diffs);
            
            $UniqueSupplierList = array();
            
            // Iterate Operators array
            $operators = $this->Shop->getProducts();
            
            foreach($operators as $key=>$operator):
                
                foreach($simInfo as $value):
                    
                    $value = (object)$value;
                    
                    if($value->opr_id == $operator['products']['id']):
                        
                        $tobeReturned[$operator['products']['id']]['products']['name'] = $operator['products']['name'];
                        $tobeReturned[$operator['products']['id']]['products']['id'] = $operator['products']['id'];
                        
                        // $this->__fillUniqueSupplierList($value);
                        
                        if( ! in_array(strtolower(trim($value->vendor_tag)), $UniqueSupplierList) &&  ! empty($value->vendor_tag)):
                            
                            $UniqueSupplierList[] = strtolower(trim($value->vendor_tag));
                        
                        

                                                             endif;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalSims'] += 1;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalActiveSims']+=(($value->active_flag!=0) && ($value->block!=1) && ($value->stop_flag==0) && ( ($value->balance+10) >$value->bal_range)  && ($value->balance>=10) )?1:0;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalBalance'] += $value->balance;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalBlockedSims'] += $value->block ? 1 : 0;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalStoppedSims'] += ($value->state == 2) ? 1 : 0;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalBlockedBalance'] += ($value->block) ? $value->balance : 0;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalSale'] += $value->sale;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalOpening'] += $value->opening;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalIncoming'] += $value->tfr;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalIncomingClo'] += $value->inc ? $value->inc : 0;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalClosing'] += $value->closing ? $value->closing : 0;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalHomeSale'] += ($value->opr_id == '4' && $value->sale > 0) ? ($value->sale - $value->roaming_today) : 0;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalRoamingSale'] += $value->roaming_today ? $value->roaming_today : 0;
                        
                        @$tobeReturned[$operator['products']['id']]['products']['totalServerDiffnew'] += ($value->serverDiffnew > 0) ? $value->serverDiffnew : 0;
                    
                    


                                                       endif;
                endforeach
                ;
            endforeach
            ;
            
            foreach($tobeReturned as $key=>$value):
                
                $tobeReturned[$key]['products']['suppliers'] = $this->__getReportBySupplierName($value['products']['id'], $UniqueSupplierList, $simInfo);
            endforeach
            ;
            
            $data['operators'] = $tobeReturned;
            
            $data['last'] = $this->Shop->getMemcache("balance_timestamp_$modem_id" . "_last");
            $data['ports'] = $this->Shop->getMemcache("balance_ports_$modem_id");
            
            $this->Shop->setMemcache("DistinctOperatorwiseReportByModemId_$modem_id", json_encode($data, 5 * 24 * 60 * 60));
        
        
                                     
                              endif;
    }

    function __getReportBySupplierName($operator_id, $UniqueSupplier, $simInfo){
        $supplierList = $UniqueSupplier;
        
        foreach($supplierList as $key=>$supplier):
            
            $totalSims = 0;
            $totalActiveSims = 0;
            $totalBalance = 0;
            $totalBlockedSims = 0;
            $totalStoppedSims = 0;
            $totalBlockedBalance = 0;
            $totalSale = 0;
            $totalOpening = 0;
            $totalIncoming = 0;
            $totalClosing = 0;
            $totalIncomingClo = 0;
            $totalHomeSale = 0;
            $totalRoamingSale = 0;
            $totalServerDiffnew = 0;
            
            $sims = array();
            
            foreach($simInfo as $value):
                
                if((strtolower(trim($value->vendor_tag)) == strtolower($supplier)) && ($value->opr_id == $operator_id) &&  ! empty($value->vendor_tag)):
                    
                    $totalSims ++ ;
                     //$totalActiveSims+=(($value->active_flag!=0) && ($value->block!=1) && ($value->state!=2) && ( ($value->balance+10) >$value->bal_range) )?1:0;
                    $totalActiveSims+=(($value->active_flag!=0) && ($value->block!=1) && ($value->stop_flag==0) && ( ($value->balance+10) >$value->bal_range)  && ($value->balance>=10) )?1:0;
                    $totalBalance += $value->balance;
                    $totalBlockedSims += $value->block ? 1 : 0;
                    $totalStoppedSims += ($value->state == 2) ? 1 : 0;
                    $totalBlockedBalance += ($value->block) ? $value->balance : 0;
                    
                    $totalSale += $value->sale;
                    @$totalOpening += $value->opening;
                    $totalIncoming += $value->tfr;
                    $totalIncomingClo += $value->inc;
                    @$totalClosing += $value->closing;
                    
                    $totalHomeSale += ($value->opr_id == '4' && $value->sale > 0) ? ($value->sale - $value->roaming_today) : 0;
                    $totalRoamingSale += $value->roaming_today;
                    
                    $totalServerDiffnew += $value->serverDiffnew;
                    
                    // $value->serverDiff=$this->__setServerDiff($value);
                    $sims[] = $value;
                
                


                                                            endif;
            endforeach
            ;
            
            if($totalSims):
                
                $tobeReturned[$supplier] = array('totalSims'=>$totalSims, 'totalActiveSims'=>$totalActiveSims, 'totalBalance'=>$totalBalance, 'totalBlockedSims'=>$totalBlockedSims, 'totalStoppedSims'=>$totalStoppedSims, 'totalBlockedBalance'=>$totalBlockedBalance, 'totalSale'=>$totalSale, 
                        'totalOpening'=>$totalOpening, 'totalIncoming'=>$totalIncoming, 'totalClosing'=>$totalClosing, 'totalIncomingClo'=>$totalIncomingClo, 'totalHomeSale'=>$totalHomeSale, 'totalRoamingSale'=>$totalRoamingSale, 'totalServerDiffnew'=>$totalServerDiffnew);
                
                $tobeReturned[$supplier]['sims'] = $sims;
            
            

                                                    endif;
        endforeach
        ;
        
        return $tobeReturned;
    }

    function setServerDiff($simInfo, $diffs = array()){
        $serverdiff = 0;
        
        foreach($simInfo as $key=>$value):
            
            $simInfo[$key]->serverDiffnew = $serverdiff;
            
            $value = (object)$value;
            
            foreach($diffs as $k=>$v):
                
                if(($value->opr_id == $v['operator_id']) && ($value->mobile == $v['sim_num']) && (strtolower(trim($value->vendor)) == strtolower(trim($v['vendor'])))):
                    
                    $serverdiff = $v['server_diff'];
                    
                    $simInfo[$key]->serverDiffnew = $serverdiff;
                    
                    $this->sdiff[$value->opr_id . "_" . $value->mobile . "_" . $value->vendor] = $serverdiff;
                    
                    $serverdiff = 0;
                    
                    break;
                
                

                                                endif;
            endforeach
            ;
        endforeach
        ;
        
        return $simInfo;
    }

    function getServerDiffByModemId($modem_id, $date, $echo_flag = 0){
        App::import('Controller', 'Panels');
        
        $Obj = new PanelsController();
        
        $Obj->constructClasses();
        
        if( ! $echo_flag) return @$Obj->get_server_diff_by_vendor($modem_id, $date);
        else{
            echo json_encode(@$Obj->get_server_diff_by_vendor($modem_id, $date));
            $this->autoRender = false;
        }
    }

    function modemStatusCheck($time = '3', $ref_id_type = null){
        $time = date('Y-m-d H:i:s', strtotime('-' . $time . ' minutes'));
        if(empty($ref_id_type)){
            $data = $this->User->query("SELECT va.txn_id, va.vendor_id, GREATEST(va.timestamp, va.updated_timestamp) as time from vendors_activations va,vendors v WHERE va.vendor_id = v.id AND v.update_flag = 1 AND va.date between '" . date('Y-m-d') . "' and '" . date('Y-m-d') . "' AND (va.status = 0 OR (va.prevStatus = 0 AND va.status = 4)) AND va.timestamp <= '$time' AND va.updated_timestamp <= '$time' order by va.id desc");
        }
        else{
            $data = $this->User->query("SELECT va.txn_id, va.vendor_id, GREATEST(va.timestamp, va.updated_timestamp) as time from vendors_activations va,vendors v WHERE va.vendor_id = v.id AND v.update_flag = 1 AND va.date between '" . date('Y-m-d') . "' and '" . date('Y-m-d') . "' AND (va.status = 0 OR (va.prevStatus = 0 AND va.status = 4)) AND va.timestamp <= '$time' AND va.updated_timestamp <= '" . date('Y-m-d H:i:s', strtotime('-90 seconds')) . "' AND vendor_refid = '' order by va.id desc");
        }
        $ids_3 = array();
        $ids_6 = array();
        
        $time_6 = date('Y-m-d H:i:s', strtotime('-6 minutes'));
        foreach($data as $dt){
            if($dt['0']['time'] < $time_6){
                $ids_6[$dt['va']['vendor_id']][] = $dt['va']['txn_id'];
            }
            else{
                $ids_3[$dt['va']['vendor_id']][] = $dt['va']['txn_id'];
            }
        }
        
        foreach($ids_3 as $vendor=>$transids){
            if(count($transids) > 5){
                $chunks = array_chunk($transids, 10);
                foreach($chunks as $chunk){
                    $this->modemUpdateStatus($chunk, $vendor);
                }
            }
        }
        
        foreach($ids_6 as $vendor=>$transids){
            $chunks = array_chunk($transids, 10);
            foreach($chunks as $chunk){
                $this->modemUpdateStatus($chunk, $vendor);
            }
        }
        
        $this->autoRender = false;
    }

    function magicStatus(){
        // if($_SERVER['REMOTE_ADDR'] == '103.39.241.23'){
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/magic.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($_REQUEST));
        $transId = $_REQUEST['transid'];
        if (ctype_alnum($transId)) {
            $this->Shop->setMemcache("magic" . $transId, $_REQUEST, 10 * 60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 24");
        }
        // }
        $this->autoRender = false;
    }

    function rioStatus(){
        $client_ip = $this->General->getClientIP();
        
        if($client_ip == '117.218.64.210'){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rio.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($_REQUEST));
            $transId = $_REQUEST['transid'];
            if (ctype_alnum($transId)) {
                $this->Shop->setMemcache("rio" . $transId, $_REQUEST, 10 * 60);
                shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 36");
            }
        }
        $this->autoRender = false;
    }

    function rio2Status(){
        // if($_SERVER['REMOTE_ADDR'] == '117.218.64.210'){
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rio2.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($_REQUEST));
        $transId = $_REQUEST['transid'];
        if (ctype_alnum($transId)) {
            $this->Shop->setMemcache("rio2" . $transId, $_REQUEST, 10 * 60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 62");
        }
        // }
        $this->autoRender = false;
    }

    function durgaStatus(){
        $client_ip = $this->General->getClientIP();
        
        if($client_ip == '117.218.206.47'){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/durga.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($_REQUEST));
            $transId = $_REQUEST['tid'];
            if (ctype_alnum($transId)) {
                $this->Shop->setMemcache("durga" . $transId, $_REQUEST, 10 * 60);
                shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 30");
            }
        }
        $this->autoRender = false;
    }

    function rkitStatus(){
        $client_ip = $this->General->getClientIP();
        
        if($client_ip == '43.254.40.236'){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rkit.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($_REQUEST));
            $transId = trim($_REQUEST['TRANNO']);
            $this->Shop->setMemcache("rkit" . $transId, $_REQUEST, 10 * 60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 34");
        }
        $this->autoRender = false;
    }

    function a2zStatus(){
        // 117.205.177.110
        // if($_SERVER['REMOTE_ADDR'] == '182.18.175.162'){
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a2z.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($_REQUEST));
        $transId = trim($_REQUEST['TRANNO']);
        $this->Shop->setMemcache("a2z" . $transId, $_REQUEST, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 47");
        
        print_r($_REQUEST);
        // }
        $this->autoRender = false;
    }

    function mypayStatus(){
        $data = $_REQUEST;
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/mypay.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $txn_data = $this->Slaves->query("SELECT txn_id,vendor_refid FROM vendors_activations USE INDEX ( idx_vendorrefid ) WHERE vendor_refid = '" . $data['accountId'] . "'");
        
        $transId = isset($txn_data[0]['vendors_activations']['txn_id']) ? trim($txn_data[0]['vendors_activations']['txn_id']) : "";
        $this->Shop->setMemcache("mypay" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 57");
        
        $this->autoRender = false;
    }

    function smsdaakStatus(){
        $data = $_REQUEST;
        $this->General->logData("/mnt/logs/smsdaak.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['agent_id']);
        $this->Shop->setMemcache("smsdaak" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 58");
        $this->autoRender = false;
    }

    function joinrecStatus(){
        // if($_SERVER['REMOTE_ADDR'] == '184.107.11.157'){
        $data = $this->General->xml2array(trim($_REQUEST['xmldata']));
        $this->General->logData("/mnt/logs/joinrec.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['RechargeStatus']['MerOid']);
        $this->Shop->setMemcache("joinrec" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 48");
        // }
        $this->autoRender = false;
    }

    function statCheck($vendor_id, $time = '30', $ord = 'desc', $days = '1', $limit = '100'){
        $time = date('Y-m-d H:i:s', strtotime('-' . $time . ' minutes'));
        
        $data = $this->User->query("SELECT txn_id,vendor_refid,status,service_id,timestamp,product_id,operator_id,date FROM vendors_activations as va use index (idx_vend_date) ,products WHERE va.product_id=products.id AND vendor_id = $vendor_id AND (status = 0 OR (prevStatus = 0 AND status = 4)) AND va.date >= '" . date('Y-m-d', strtotime('-' . $days . ' days')) . "' AND va.timestamp <= '$time' AND va.updated_timestamp <= '$time' order by va.id $ord LIMIT $limit");
        
        foreach($data as $dt){
            $this->Recharge->getSetTransactionStatus($vendor_id, $dt, true);
        }
        $this->autoRender = false;
    }
    
    
    function statusCrons($time = '2', $ord = 'desc', $days = '1', $limit = '100'){
        //$time1 = date('Y-m-d H:i:s', strtotime('-' . $time . ' minutes'));
        
        $data = $this->Slaves->query("SELECT id FROM vendors WHERE show_flag = 1 AND update_flag = 0");
        
        foreach($data as $dt){
            $vendor_id = $dt['vendors']['id'];
            $url = DOMAIN_TYPE."://".$_SERVER['SERVER_NAME']. "/recharges/statCheck/$vendor_id/$time/$ord/$days/$limit";
            
            $this->General->curl_post($url, array(),'GET',1,5);
        }
        $this->autoRender = false;
    }

    /**
     * *************************************
     */
    function statusCheck($transId, $vendor_id){
        $this->autoRender = false;
        $vend_info = $this->Shop->getVendorInfo($vendor_id);
        $vend_name = $vend_info['shortForm'];
        $status = $this->Shop->getMemcache($vend_name . $transId);
        
        $this->General->logData("$vend_name" . ".txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($status));
        
        $data = $this->User->query("SELECT va.id,va.txn_id,va.vendor_refid,va.status,va.prevStatus,products.service_id,va.timestamp,va.product_id,va.date,va.operator_id,va.vendor_id,vendors.company,va.cc_userid FROM vendors_activations as va LEFT JOIN products ON (va.product_id=products.id) LEFT JOIN vendors ON (vendors.id = vendor_id) WHERE txn_id='$transId'");
        if(empty($data)) return;
        
        $apiStatus = $this->Recharge->apiAutoStatus($vendor_id, $status);
        $stat = $apiStatus['status'];
        $dbObj = $this->User;
        $dt = $data[0];
        
        $getTxnData = $this->Recharge->getTxnData($transId);
        if(!empty($getTxnData['vendors'])){
            $last_vendor_id = end($getTxnData['vendors']);
            if($vendor_id != $last_vendor_id) {
                if($stat == 'success')
                {
                    if( ! $this->Recharge->lockTransaction($transId)) return;
                    $this->Recharge->checkForPullBack($transId, $vendor_id, $dt, 'success', $dbObj, true);
                }
                $this->General->logData("status_check.txt", date('Y-m-d H:i:s') . ":$transId::AutoStatusUpdateLog: $vendor_id::$last_vendor_id::not matching");
                return ;
            }
            else {
                //$this->General->logData("status_check.txt", date('Y-m-d H:i:s') . ":$transId::AutoStatusUpdateLog: $vendor_id::$last_vendor_id");
            }
        }
        
        if($vendor_id == $dt['va']['vendor_id'] && ((in_array($dt['va']['status'], array(2, 3)) && $stat == 'failure') or (in_array($dt['va']['status'], array(1)) && $stat == 'success') or ($dt['va']['status'] == 4 && $dt['va']['prevStatus'] == 1 && $stat == 'success'))){ // same status
            return;
        }
        $this->General->logData("$vend_name" . ".txt", date('Y-m-d H:i:s') . ":I m here: $vendor_id $stat " . json_encode($dt));
        
        if( ! $this->Recharge->lockTransaction($transId)) return;
        
        if($vendor_id == $dt['va']['vendor_id'] && (in_array($dt['va']['status'], array(1,5)) || ($dt['va']['status'] == 4 && $dt['va']['prevStatus'] == 1)) && $stat == 'failure'){ // failure after success
            $this->General->sendMails('API: Failure after Success problem', "$transId <br/>Txn found for vendor " . $dt['vendors']['company'] . ", Previous status was " . $dt['va']['status'] . "<br/>Operator txnid: $operator_id", array('ashish@pay1.in', 'chirutha@pay1.in', 
                    'cc.support@pay1.in'), 'mail');
            $vadate = $dt['va']['date'];
            $this->General->sendFailureAfterSuccessData($transId,$vadate);
        }
        else if($vendor_id == $dt['va']['vendor_id'] && in_array($dt['va']['status'], array(2, 3)) && $stat == 'success'){ // success after failure
            $this->Recharge->checkForPullBack($transId, $vendor_id, $dt, 'failure', $dbObj, true);
        }
        else if($vendor_id != $dt['va']['vendor_id'] && $stat == 'success'){ // success after failure(double txn)
            $this->Recharge->checkForPullBack($transId, $vendor_id, $dt, 'success', $dbObj, true);
        }
        else if($vendor_id == $dt['va']['vendor_id']){
            $this->Recharge->updateTransactionStatus($vendor_id, $dt, $apiStatus, true);
        }
        
        $this->Recharge->unlockTransaction($transId);
    }

    function cpStatusCheck($time = '1', $ord = 'desc', $days = '1', $limit = 50){
        $time = date('Y-m-d H:i:s', strtotime('-' . $time . ' minutes'));
        $vendor_id = 8;
        $data = $this->User->query("SELECT txn_id,vendor_refid,status,service_id,timestamp,product_id,operator_id,date FROM vendors_activations as va use index (idx_vend_date) ,products WHERE va.product_id=products.id AND vendor_id = $vendor_id AND (status = 0 OR (prevStatus = 0 AND status = 4)) AND va.date >= '" . date('Y-m-d', strtotime('-' . $days . ' days')) . "' AND va.timestamp <= '$time' AND va.updated_timestamp <= '$time' order by va.id $ord LIMIT $limit");
        
        foreach($data as $dt){
            $this->Recharge->getSetTransactionStatus($vendor_id, $dt, true);
        }
        $this->autoRender = false;
    }

    function startTransaction($request_id = null){
        if(isset($request_id) && $request_id){
            $_REQUEST = $this->Recharge->fetch_formated_request_data($request_id);
        }
        else{
            $_SERVER['REMOTE_ADDR'] = $this->General->getClientIP();
            $sha_enc_hash = urlencode(strtoupper(sha1(encKey . $_REQUEST['tranId'])));
            if($sha_enc_hash !== $_REQUEST['encSign']){
                return;
            }
        }
        $transId = $_REQUEST['tranId'];
        
        $param = json_decode($_REQUEST['params'], true);
        $this->General->logData("/mnt/logs/request.txt", "Recharge request:: $transId");
        // --------tata adhock changes
        if(in_array($_REQUEST['product_id'], array(9))){
            $_REQUEST['product_id'] = 27;
        }
        
        // set all the parameters according to type of transaction
        $set_param = $this->Recharge->setAfterTransParameter($param, $_REQUEST);
        
        $circle = isset($param['area']) ? $param['area'] : "";
        
        $txnData = array();
        $txnData['ref_code'] = $_REQUEST['tranId'];
        $txnData['area'] = $circle;
        $txnData['service_id'] = $_REQUEST['service_id'];
        $txnData['product_id'] = $_REQUEST['product_id'];
        $txnData['mobile'] = $set_param['mobile'];
        $txnData['param'] = $set_param['par'];
        $txnData['amount'] = $set_param['amount'];
        $txnData['param1'] = (isset($set_param['param1']) ? $set_param['param1'] : '');
        $txnData['param2'] = (isset($set_param['param2']) ? $set_param['param2'] : '');
        $txnData['priorityList'] = json_decode($_REQUEST['vendors'],true);
        $txnData['vendors'] = array();
        $txnData['timestamp'] = date('Y-m-d H:i:s');
        $txnData['retailer_id'] = (isset($set_param['retailer_id']) ? $set_param['retailer_id'] : '');
        
        $this->Shop->addMemcache("txn$transId", $txnData, 15 * 60);
        
        // allowing api_partner(B2C) recharge window for 15 mins and other for 5 mins
        $minutes = (isset($param['api_partner']) && $param['api_partner'] == 6) ? 15 : 5;
        $this->Shop->addMemcache("txnExp$transId", time() + $minutes * 60, $minutes * 60);
        
        $this->Recharge->routeTransaction($transId, $txnData);
    }

    function b2cPay1Wallet($transId, $params, $prodId = null){
        $vendorId = 22;
        $url = B2C_URL.'actiontype/refillwallet/api/true';
        
        $_SESSION['Auth']['User']['id'] = empty($_SESSION['Auth']['User']['id']) ? "" : $_SESSION['Auth']['User']['id'];
        $lat_long = $this->Slaves->query("SELECT longitude,latitude FROM user_profile WHERE user_id=" . $_SESSION['Auth']['User']['id'] . " AND longitude != 0 AND latitude != 0 ORDER BY updated DESC LIMIT 1");
        
        $data = array('trans_id'=>$transId, 'mobile_number'=>$params['mobileNumber'], 'amount'=>$params['amount'], 'retailer_id'=>$params['retailer_code'], 'retailer_shop'=>$params['retailer_name'], 'retailer_mobile'=>$params['retailer_mobile']);
        if( ! empty($lat_long)){
            $data['latitude'] = $lat_long[0]['user_profile']['latitude'];
            $data['longitude'] = $lat_long[0]['user_profile']['longitude'];
        }
        
        $Rec_Data = $this->General->curl_post($url, $data);
        
        if( ! $Rec_Data['success']){
            if($Rec_Data['timeout']){
                return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>14, 'vendor_response'=>'Connectivity timeout from b2c server');
            }
        }
        
        $out = $Rec_Data['output'];
        $this->General->logData("/mnt/logs/pay1.txt", date('Y-m-d H:i:s') . ":Request Sent: " . $url . "::" . json_encode($data) . "::output: " . $out);
        
        $desc = json_decode($out, true);
        
        if($desc['status'] == 'success'){
            $txnId = $desc['description']['transaction_id'];
            return array('status'=>'success', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>13, 'vendor_response'=>'success');
        }
        else if($desc['status'] == 'failure'){
            $err_code = $desc['errCode'];
            $msg = $desc['description'];
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>14, 'vendor_response'=>"Error code: $err_code, " . $msg);
        }
        else{
            return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>15, 'vendor_response'=>'');
        }
    }

    function ongoTopup($transaction_id, $params, $product_id){
        $filename = "wallets_integration_" . date('Ymd') . ".txt";
        
        $this->General->logData('/mnt/logs/' . $filename, $label . "::" . json_encode(array($transaction_id, $params, $product_id)));
        
        App::import('Controller', 'Wallets');
        $obj = new WalletsController();
        $obj->constructClasses();
        $response = $obj->ongoTopup($params);
        
        $this->General->logData('/mnt/logs/' . $filename, $label . "::" . json_encode($response));
        
        if($response['status'] == 'success'){
            return array('status'=>'success', 'code'=>'31 ', 'description'=>$this->Shop->errors(31), 'tranId'=>$params['vendor_refid'], 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        else if($response['status'] == 'failure'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>$response['description']);
        }
        else{
            return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>'');
        }
    }

    /*
     * Api Online recharge
     */
    function aporecStatus(){
        $data = $_REQUEST;
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aporec.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['client_trans_id']);
        $this->Shop->setMemcache("aporec" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 63");
        $this->autoRender = false;
    }

    /*
     * hitech recharge
     */
    function hitechStatus(){
        $data = $_REQUEST;
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/hitech.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['rid']);
        $this->Shop->setMemcache("hitech" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 65");
        $this->autoRender = false;
    }


    function tranStatus($tranId, $vendor, $date = null, $refId = null){
        $status = $this->Recharge->tranStatus($tranId,$vendor,$date,$refId);
        $this->General->printArray($status);
        $this->autoRender = false;
    }

    function isAfterTransaction(){
        $this->autoRender = false;
        
        $tranId = $_REQUEST['id'];
        $vendor = $_REQUEST['vendor'];
        $date = $_REQUEST['date'];
        $refId = $_REQUEST['ref_id'];
         
        $response= $this->Recharge->tranStatus($tranId,$vendor,$date,$refId);
        //$response = json_decode($response1, true);

        if(isset($response['trans_history']) &&  ! empty($response['trans_history'])){
            if(isset($response['trans_history']['after1'])){
                echo "true";
            }
            else
                echo "false";
        }
        else if(isset($response['status']) &&  ! empty($response['status'])){
            if($response['status'] == 'pending'){                
                 echo "false";                
            }
            else {
                echo "true";
            }
        }
        
    }

    function simNo(){
        $this->autoRender = false;
        
        $tranId = $_REQUEST['id'];
        $vendor = $_REQUEST['vendor'];
        $date = $_REQUEST['date'];
        $refId = $_REQUEST['ref_id'];
        
        ob_start();
        $response1 = $this->tranStatus($tranId, $vendor, $date, $refId, true);
        ob_end_clean();
        $response = json_decode($response1, true);
        if(isset($response['sent_by']) &&  ! empty($response['sent_by'])){
            $nos = explode("/", $response['sent_by']);
            $simNumber = $nos[1];
            echo $simNumber;
        }
        else
            echo "NOT FOUND";
    }

    function modemUpdateStatus($tranId = null, $vendor = null){
        $ret = true;
        if($tranId == null){
            header('Access-Control-Allow-Origin: *');
            // header('Access-Control-Allow-Methods: GET, POST');
            $tranId = $_REQUEST['id'];
            $vendor = $_REQUEST['vendor'];
            $ret = false;
        }
        $count = 1;
        if(is_array($tranId)){
            $count = count($tranId);
            $tranId = implode(",", $tranId);
        }
        
        $adm = "query=update&transId=$tranId";
        $Rec_Data = $this->Shop->modemRequest($adm, $vendor);
        
        if($Rec_Data['status'] == 'failure'){
            $data['error_desc'] = 'Recharge modem not responding';
        }
        else{
            $Rec_Data = $Rec_Data['data'];
            $data = json_decode($Rec_Data, true);
        }
        
        $res = array();
        if($count == 1){
            $res[$tranId] = $data;
        }
        else{
            $res = $data;
        }
        
        if( ! empty($res)) foreach($res as $tranId1=>$data1){
            $this->Recharge->updateModemTransactionStatus($tranId1, $data1);
        }
        
        if( ! $ret){
            if(isset($data['error_desc'])) echo $data['error_desc'];
            else echo $data['status'];
            $this->autoRender = false;
        }
        else
            return;
    }

    function modemTransactionStatus(){
        $this->Recharge->updateModemTransactionStatus();
        $this->autoRender = false;
    }
    /*
     * Function to be called by dispatcher script
     */
    function modem_response_updater($request_id){
        $RESPONSE_HASH = "modems_response_data";
        $response_data = "";
        $this->General->logData("/mnt/logs/updaterchanges-" . date('Ymd') . ".txt", date('Y-m-d H:i:s') . ": in updater : data : " . $request_id);
        // --------------creating connection
        $redisObj = $this->Shop->openservice_redis();
        
        if($redisObj != false){
            $response_data = $redisObj->hget($RESPONSE_HASH, $request_id);
            $result = $redisObj->hdel($RESPONSE_HASH, $request_id);
            $_REQUEST = json_decode($response_data, true);
            $this->General->logData("/mnt/logs/updaterchanges.txt", date('Y-m-d H:i:s') . ": in updater : data : " . $RESPONSE_HASH . "| " . $request_id . " | " . $response_data);
            if($redisObj->hexists($RESPONSE_HASH, $request_id)){
                $this->General->logData("/mnt/logs/updaterchanges.txt", date('Y-m-d H:i:s') . ": hash key not deleted ");
                $redisObj->hdel($RESPONSE_HASH, $request_id);
            }
            
            $method = isset($_REQUEST['method_name']) ? $_REQUEST['method_name'] : "";
            if(method_exists($this, $method)){
                unset($_REQUEST['method_name']);
                $this->$method();
            }
            // -----closing connection
            // $redisObj->quit();
        }
        unset($_REQUEST);
        $this->autoRender = false;
    }

    function practicStatus(){
        $data = $_REQUEST;
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/practic.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['YRef']);
        $this->Shop->setMemcache("practic" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 68");
        $this->autoRender = false;
    }

    function simpleStatus(){
        $data = $_REQUEST;
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/simple.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['client_id']);
        $this->Shop->setMemcache("simple" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 69");
        $this->autoRender = false;
    }

    function manglamStatus(){
        $data = $_REQUEST;
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglam.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['uniqueid']);
        $this->Shop->setMemcache("manglam" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 87");
        $this->autoRender = false;
    }

    function bulkStatus(){
        $data = $_REQUEST;
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bulk.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['clientid']);
        $this->Shop->setMemcache("bulk" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 105");
        $this->autoRender = false;
    }

    function bimcoStatus(){
        $data = $_REQUEST;
        
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bimco.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['Merchantrefno']);
        $this->Shop->setMemcache("bimco" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 123");
        $this->autoRender = false;
    }

    function rajanStatus(){
        $data = $_REQUEST;
        
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rajan.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['uid']);
        $this->Shop->setMemcache("rajan" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 125");
        $this->autoRender = false;
    }

    function payApiStatus(){
        $data = $_REQUEST;
        
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payrecharge.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        
        $transId = trim($data['tid']);
        $this->Shop->setMemcache("payApi" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId 129");
        echo "ok";
        $this->autoRender = false;
    }
    
    function ShivaIdeaStatus(){
        $data = $_REQUEST;
    
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/ShivaIdea.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));
    
        $transId = trim($data['transid']);
        $this->Shop->setMemcache("ShivaIdea".$transId,$data,10*60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId 132");
        $this->autoRender = false;
    
    }
    
    function indiCoreStatus(){
        $data = $_REQUEST;
    
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/indicorerecharge.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));
    
        $transId = trim($data['tid']);
        $this->Shop->setMemcache("indiCore".$transId,$data,10*60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId 134");
        $this->autoRender = false;
    
    }
    
    function swamirajStatus(){
        //$data = $_REQUEST;
        foreach($_REQUEST as $key=>$v){
            $d = urldecode($key);
            parse_str($d,$data);
        }
        $vendor_id = SWAMIRAJ_VENDOR_ID;
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/swamiraj.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));
    
        $transId = trim($data['clientid']);
        $this->Shop->setMemcache("swamiraj".$transId,$data,10*60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        $this->autoRender = false;
    
    }
    
    function maxrechargStatus(){
        $data = $_REQUEST;
        $vendor_id = MAXRECHARGE_VENDOR_ID;
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/maxrecharge.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));
    
        $transId = trim($data['TNO']);
        $this->Shop->setMemcache("maxrecharg".$transId,$data,10*60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        $this->autoRender = false;
    
    }
        
    function IndiaRecStatus(){       
        $client_ip = $this->General->getClientIP();
        
         if($client_ip == '88.99.232.13'){
                $data = $_REQUEST;
                $vendor_id = INDIARECHARGE_VENDOR_ID;
                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/indiarerecharge.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

                $transId = trim($data['accountId']);
                $this->Shop->setMemcache("IndiaRec".$transId,$data,10*60);
                shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
         }
        $this->autoRender = false;
        
    }
    
    /*function loginJIOUsers($id=null){
        $this->autoRender = false;
        $string = "";
        if(!empty($id) && is_numeric($id)){
            $string = " AND userId = '$id'";
        }
        if($_SERVER['REMOTE_ADDR']=='127.0.0.1' || ($_SERVER['REMOTE_ADDR']=='14.192.27.170') || $_SERVER['REMOTE_ADDR'] == SMS_SERVER_IP){
            $data = $this->Slaves->query("SELECT * FROM `jio_retailer` WHERE active = 1 $string");
            
            foreach($data as $dt){
                $this->Jio->pay1JioLogin($dt['jio_retailer']['userId']);
            }
        }
        return;
    }
    
    function setPosId($id=null){
        $this->autoRender = false;
        $string = "";
        if(!empty($id) && is_numeric($id)){
            $string = " AND userId = '$id'";
        }
        if($_SERVER['REMOTE_ADDR']=='127.0.0.1' || ($_SERVER['REMOTE_ADDR']=='14.192.27.170')){
            $data = $this->Slaves->query("SELECT * FROM `jio_retailer` WHERE active = 1 $string");
            
            foreach($data as $dt){
                $this->Jio->setPosId($dt['jio_retailer']);
            }
        }
        return;
    }
    
    function pay1JioAllBalance(){
        $this->autoRender = false;
        $office_ips = explode(",",OFFICE_IPS);
        
        if(!in_array($_SERVER['REMOTE_ADDR'],$office_ips)){
            return;
        }
        $data = $this->Slaves->query("SELECT userId,zone,balance FROM `jio_retailer` WHERE active = 1");
        
        foreach($data as $dt){
            echo "Jio Retailer ".$dt['jio_retailer']['userId']." In zone ".$dt['jio_retailer']['zone']." with balance ".$dt['jio_retailer']['balance'];
            echo "<br>";
        }
    }*/
    
    function updateEarningLogs($date=null){
        $this->autoRender = false;
        if(empty($date))$date = date('Y-m-d',strtotime('-1 days'));
        $last_date = $date;
        
        $office_ips = explode(",",OFFICE_IPS);
        $client_ip = $this->General->getClientIP();
        
        if(!in_array($client_ip,$office_ips) && $client_ip != SMS_SERVER_IP){
            return;
        }
        
        $comm = $this->Slaves->query("SELECT sum(vendors_activations.amount) as sale,sum(vendors_activations.amount*vendors_activations.discount_commission/100) as expected, vendors_activations.vendor_id, vendors_activations.date, earnings_logs.opening,earnings_logs.closing FROM vendors_activations left join earnings_logs ON (earnings_logs.vendor_id = vendors_activations.vendor_id AND earnings_logs.date = vendors_activations.date) WHERE vendors_activations.product_id != 44 AND vendors_activations.status != 2 AND vendors_activations.status != 3 AND vendors_activations.date = '$last_date' group by vendors_activations.vendor_id,vendors_activations.date");
        
        $openingClosingdata = $this->Slaves->query("SELECT vendor_id,sum(opening) as opening,sum(closing) as closing,sync_date
                from devices_data
                where sync_date = '$date'
                group by vendor_id having opening>0 and closing>0 "
                );
        
        $data = array();
        foreach($comm as $com){
            $data[$com['vendors_activations']['vendor_id']][$com['vendors_activations']['date']]['sale'] = $com['0']['sale'];
            $data[$com['vendors_activations']['vendor_id']][$com['vendors_activations']['date']]['expected'] = $com['0']['expected'];
        }
        
        foreach ($openingClosingdata as $val){
            
            $data[$val['devices_data']['vendor_id']][$val['devices_data']['sync_date']]['opening'] = $val['0']['opening'];
            $data[$val['devices_data']['vendor_id']][$val['devices_data']['sync_date']]['closing'] = $val['0']['closing'];
        }
        
        
        
        
        foreach($data as $key=>$dt){
            $this->General->printArray($dt);
            $saas_flag = 0;
            if(in_array($key,explode(",",SAAS_VENDORS))){
                $saas_flag = 1;
            }
            
            if($this->User->query("INSERT into earnings_logs VALUES (NULL,$key,'','','".$dt[$date]['sale']."','','','".$dt[$date]['expected']."','','$last_date','','".date('Y-m-d H:i:s')."','$saas_flag')")){
                
            }
            else {
                $this->User->query("UPDATE earnings_logs SET sale='".$dt[$date]['sale']."',expected_earning='".$dt[$date]['expected']."' WHERE vendor_id = $key AND date = '$last_date'");
            }
            
            if(isset($dt[$date]['opening']) && isset($dt[$date]['closing'])){
                $this->User->query("UPDATE earnings_logs SET opening='".$dt[$date]['opening']."',closing='".$dt[$date]['closing']."' WHERE vendor_id = $key AND date = '$last_date'");
            }
        }
            
    }
    
    function memcacheKey($key, $action = 'get'){
        $this->autoRender = false;
        $office_ips = explode(",",OFFICE_IPS);
        $client_ip = $this->General->getClientIP();
        
        if(!in_array($client_ip,$office_ips)){
            return;
        }
        $value = $this->Shop->getMemcache($key);
        echo "key: ".$key."<br/>value: ".json_encode($value)."<br/>";
        
        if($action == 'delete'){
            $this->Shop->delMemcache($key);
            echo $key." deleted from memcache";
        }
    }
    
    function ambikaStatus(){
        $data = $_REQUEST;
        $vendor_id = AMBIKA_VENDOR_ID;
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/ambika.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));
    
        $transId = trim($data['agentid']);
        $this->Shop->setMemcache("ambika".$transId,$data,10*60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        $this->autoRender = false;
    
    }
    
    function ambikaroamStatus(){
        $data = $_REQUEST;
        $vendor_id = 155;
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/ambika.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));
    
        $transId = trim($data['agentid']);
        $this->Shop->setMemcache("ambikaroam".$transId,$data,10*60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        $this->autoRender = false;
    
    }
    
    function indiaoneStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/indiaone.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
        
        if($client_ip == '103.20.213.235'){
            $data = $_REQUEST;
            $vendor_id = 157;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/indiaone.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['TransRef']);
            $this->Shop->setMemcache("indiaone".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
            $this->autoRender = false;
        }
    }
    
    function a1recStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/a1rec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
//        if($_SERVER['REMOTE_ADDR'] == '52.172.206.196'){
            $data = $_REQUEST;
            $vendor_id = A1REC_VENDOR_ID;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/a1rec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['reqid']);
            $this->Shop->setMemcache("a1rec".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
//        }
        $this->autoRender = false;
    
    }
    
    function bigshoprecStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/bigshoprec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        $data = $_REQUEST;
        $vendor_id = BIGSHOPREC_VENDOR_ID;
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/bigshoprec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

        $transId = trim($data['RequestId']);
        $this->Shop->setMemcache("bigshoprec".$transId,$data,10*60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        
        $this->autoRender = false;
    
    }
    
    function emoneyStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/emoney.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
                
        if($client_ip == '202.66.172.42')
        {
            $data = $_REQUEST;
            $vendor_id = 158;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/emoney.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['agentid']);
            $this->Shop->setMemcache("emoney".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    
    }
    
    function speedpayStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/speedpay.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
               
        if($client_ip == '88.99.147.57')
        {
            $data = $_REQUEST;
            $vendor_id = 160;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/speedpay.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['accountId']);
            $this->Shop->setMemcache("speedpay".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    
    }
    
    function thinkwalStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/thinkwal.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
                
        if($client_ip == '13.126.124.112')
        {
            $data = $_REQUEST;
            $vendor_id = 162;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/thinkwal.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['clientId']);
            $this->Shop->setMemcache("thinkwal".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }
    
    function champrecStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/champrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
                
        if($client_ip == '166.62.121.237')
        {
            $data = $_REQUEST;
            $vendor_id = 163;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/champrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['uniqueid']);
            $this->Shop->setMemcache("champrec".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }
    
    function yashicaentStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/yashicaent.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == '')
       // {
            $data = $_REQUEST;
            $vendor_id = 164;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/yashicaent.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_ref_id']);
            $this->Shop->setMemcache("yashicaent".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
       // }
        
        $this->autoRender = false;
    }
    
        function ka2zrecStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/ka2zrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));                
                
        if(in_array($client_ip, array('78.47.229.128','188.40.110.18')))
         {
            $data = $_REQUEST;
            $vendor_id = 165;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/ka2zrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['myTxid']);
            $this->Shop->setMemcache("ka2zrec".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

    function roundpayStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/roundpay.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
                
        if($client_ip == '205.147.109.161')
        {
            $data = $_REQUEST;
            $vendor_id = 166;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/roundpay.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['agentid']);
            $this->Shop->setMemcache("roundpay".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

    function maxxrecStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/maxxrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
        
        if($client_ip== '138.201.207.104')
          {
            $data = $_REQUEST;
            $vendor_id = 167;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/maxxrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['reqid']);
            $this->Shop->setMemcache("maxxrec".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

    function erecpointStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/erecpoint.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
                
        if($client_ip == '94.130.35.138')
        {
            $data = $_REQUEST;
            $vendor_id = 168;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/erecpoint.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['reqid']);
            $this->Shop->setMemcache("erecpoint".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }
    
     function pay1allStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1all.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
                
        if($client_ip == '43.240.64.245')
         {
            $data = $_REQUEST;
            $vendor_id = 170;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1all.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['agentid']);
            $this->Shop->setMemcache("pay1all".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }
    
    function urecStatus(){
             $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/urec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == '')
       // {
            $data = $_REQUEST;
            $vendor_id = 169;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/urec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));
//
            $transId = trim($data['TransRef']);
            $this->Shop->setMemcache("urec".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
       // }
        
        $this->autoRender = false;
     
    }

    function precStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/prec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
             
        if($client_ip == '103.20.212.40')
        {
            $data = $_REQUEST;
            $vendor_id = 171;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/prec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['TransRef']);
            $this->Shop->setMemcache("prec".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    
    }
    

//        
//    function zplusStatus(){
//             $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/zplus.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
//        
//        //if($_SERVER['REMOTE_ADDR'] == '13.126.124.112')
//       // {
//            $data = $_REQUEST;
//            $vendor_id = 165;
//            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/zplus.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));
//
//            $transId = trim($data['client_ref_id']);
//            $this->Shop->setMemcache("zplus".$transId,$data,10*60);
//            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
//       // }
//        
//        $this->autoRender = false;
//    
//    }
    function unrecStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/unrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
        
        if($client_ip == '166.62.88.64'){
            $data = $_REQUEST;
            $vendor_id = UNREC_VENDOR_ID;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/unrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['ReferenceId']);
            $this->Shop->setMemcache("unrec".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        $this->autoRender = false;
    
    }
        function ashw1Status(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/ashw1.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == '')
       // {
            $data = $_REQUEST;
            $vendor_id = 172;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/ashw1.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_ref_id']);
            $this->Shop->setMemcache("ashw1".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
       // }
        
        $this->autoRender = false;
    }
    
        
    function pay1clickStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1click.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
        
        if($client_ip == '109.203.112.41')
        {
            $data = $_REQUEST;
            $vendor_id = 173;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1click.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['RequestID']);
            $this->Shop->setMemcache("pay1click".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }    
    
    function kracrecStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/kracrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
                
        if($client_ip == '138.201.28.249')
         {
            $data = $_REQUEST;
            $vendor_id = 174;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/kracrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['accountId']);
            $this->Shop->setMemcache("kracrec".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }
    
    function stelcomStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/stelcom.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
       
        if($client_ip == '103.224.243.252'){
            $data = $_REQUEST;
            $vendor_id = 175;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/stelcom.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_key']);
            
            $this->Shop->setMemcache("stelcom".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        $this->autoRender = false;
    }    

    function manimasterStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/manimaster.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
        
        if($client_ip == '103.20.214.94')
        {
            $data = $_REQUEST;
            $vendor_id = 176;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/manimaster.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['TransRef']);
            $this->Shop->setMemcache("manimaster".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    
    }
    
    function wellbornStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/wellborn.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
        
        if($client_ip == '219.90.67.191'){
            $data = $_REQUEST;
            $vendor_id = 177;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/wellborn.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_key']);
            
            $this->Shop->setMemcache("wellborn".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        $this->autoRender = false;
    }   
    
    function ctswalletStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/ctswallet.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == ''){
            $data = $_REQUEST;
            $vendor_id = CTSWALLET_VENDOR_ID;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/ctswallet.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['options_mobilenumber']);
            $this->Shop->setMemcache("ctswallet".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        //}
        $this->autoRender = false;
    
    }
     function nishiStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/nishi.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == '')
       // {
            $data = $_REQUEST;
            $vendor_id = 178;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/nishi.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_ref_id']);
            $this->Shop->setMemcache("nishi".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
       // }
        
        $this->autoRender = false;
    } 
     function supersaasStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/supersaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == '')
       // {
            $data = $_REQUEST;
            $vendor_id = 179;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/supersaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_ref_id']);
            $this->Shop->setMemcache("supersaas".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
       // }
        
        $this->autoRender = false;
    }    
 
    function speedrecStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/speedrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
        
        if($client_ip == '176.9.11.140'){
            $data = $_REQUEST;
            $vendor_id = SPEEDREC_VENDOR_ID;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/speedrec.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['ACCOUNTID']);
            
            $this->Shop->setMemcache("speedrec".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        $this->autoRender = false;
    
    }
    
        function myetopupStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/myetopup.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/myetopupserver.txt",date('Y-m-d H:i:s'),json_encode($_SERVER));
               

        //if(in_array($client_ip, array('88.99.6.176','10.0.20.112')))
          //{
            $data = $_REQUEST;
            $vendor_id = 180;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/myetopup.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['reqid']);
            $this->Shop->setMemcache("myetopup".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        //}
        
        $this->autoRender = false;
    }
     function balajisaasStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/balajisaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == '')
       // {
            $data = $_REQUEST;
            $vendor_id = 179;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/balajisaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_ref_id']);
            $this->Shop->setMemcache("balajisaas".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
       // }
        
        $this->autoRender = false;
    } 
      function pratisaasStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pratisaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == '')
       // {
            $data = $_REQUEST;
            $vendor_id = 182;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pratisaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_ref_id']);
            $this->Shop->setMemcache("pratisaas".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
       // }
        
        $this->autoRender = false;
    }

    function osssaasStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/osssaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == '')
       // {
            $data = $_REQUEST;
            $vendor_id = 183;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/osssaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_ref_id']);
            $this->Shop->setMemcache("osssaas".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
       // }
        
        $this->autoRender = false;
    }
    
    function manglamvodStatus() {
        $data = $_REQUEST;
        $vendor_id = 184;
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglamvod.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));

        $transId = trim($data['uniqueid']);
        $this->Shop->setMemcache("manglamvod" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId $vendor_id");
        $this->autoRender = false;
    }
    
    function swamirapiStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/swamirapi.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
        
        if($client_ip == '176.9.11.140'){
            $data = $_REQUEST;
            $vendor_id = SWAMIRAJAPI_VENDOR_ID;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/swamirapi.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['ACCOUNTID']);
            
            $this->Shop->setMemcache("swamirapi".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        $this->autoRender = false;
    
    }
    
        function rajsaasStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/rajsaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == '')
       // {
            $data = $_REQUEST;
            $vendor_id = 186;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/rajsaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_ref_id']);
            $this->Shop->setMemcache("rajsaas".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
       // }
        
        $this->autoRender = false;
    }
    function kumarsaasStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/kumarsaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        //if($_SERVER['REMOTE_ADDR'] == '')
       // {
            $data = $_REQUEST;
            $vendor_id = 187;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/kumarsaas.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['client_ref_id']);
            $this->Shop->setMemcache("kumarsaas".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
       // }
        
        $this->autoRender = false;
    }    
    
        function techmateStatus(){
        $client_ip = $this->General->getClientIP();
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/techmate.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$client_ip."::Data::".json_encode($_REQUEST));
                
        if($client_ip == '205.147.109.72')
        {
            $data = $_REQUEST;
            $vendor_id = 188;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/techmate.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['agentid']);
            $this->Shop->setMemcache("techmate".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    
    }
    
        function varsharoboStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/varsharobo.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 189;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/varsharobo.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("varsharobo".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
         }
        
        $this->autoRender = false;
    }    

        function qubaroboStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/qubarobo.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 190;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/qubarobo.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("qubarobo".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }
 
        function aventideaStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/aventidea.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 191;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/aventidea.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("aventidea".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }    
        function threeplusStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/threeplus.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 192;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/threeplus.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("threeplus".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

        function pintooslsStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pintoosls.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 193;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pintoosls.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("pintoosls".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

        function aventerpStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/aventerp.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 194;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/aventerp.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("aventerp".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

        function jasscommStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/jasscomm.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;  
            $vendor_id = 195;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/jasscomm.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("jasscomm".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

        function nkagencyStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/nkagency.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 196;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/nkagency.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("nkagency".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

        function anilkirStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/anilkir.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 197;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/anilkir.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("anilkir".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

    function jeevnrkhStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/jeevnrkh.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 198;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/jeevnrkh.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("jeevnrkh".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }
    
      function payclickStatus(){
      
        $data = $_REQUEST;
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payclick.txt", date('Y-m-d H:i:s') . ":AutoStatusUpdateLog: " . json_encode($data));
        $vendor_id = 199;
        $transId = trim($data['rc_id']);
        $this->Shop->setMemcache("payclick" . $transId, $data, 10 * 60);
        shell_exec("sh " . $_SERVER['DOCUMENT_ROOT'] . "/scripts/status.sh $transId $vendor_id");
        $this->autoRender = false;
        
      }

     function starcomStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/starcom.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 200;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/starcom.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("starcom".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
     }
     
    function moderntradStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/moderntrad.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 201;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/moderntrad.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("moderntrad".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

    function vjyotraderStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/vjyotrader.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 202;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/vjyotrader.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("vjyotrader".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

    function aftraderStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/aftrader.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')
         {
            $data = $_REQUEST;
            $vendor_id = 203;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/aftrader.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("aftrader".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

    function starmbroboStatus(){
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/starmbrobo.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: IP::".$_SERVER['REMOTE_ADDR']."::Data::".json_encode($_REQUEST));
        
        if($_SERVER['REMOTE_ADDR'] == '66.199.225.74')  
         {
            $data = $_REQUEST;
            $vendor_id = 204;
            $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/starmbrobo.txt",date('Y-m-d H:i:s').":AutoStatusUpdateLog: ".json_encode($data));

            $transId = trim($data['MEMBERREQID']);
            $this->Shop->setMemcache("starmbrobo".$transId,$data,10*60);
            shell_exec("sh " . $_SERVER['DOCUMENT_ROOT']."/scripts/status.sh $transId $vendor_id");
        }
        
        $this->autoRender = false;
    }

    
    function getUserID($mobile) {
        $user_id = $this->Slaves->query("SELECT id FROM users WHERE mobile = '$mobile'");
        
        echo trim(json_encode(array($mobile=>$user_id[0]['users']['id'])));
        $this->autoRender = false;
    }
    
    function testRechargeApis($vendor_id,$m,$prodId,$mobile,$amount,$operator,$tranId,$retailer_id,$param,$param1){
        $this->autoRender = false;
        //if(empty($_SESSION['Auth']['User']['id']))return;        
        $vinfo = $this->Shop->getVendorInfo($vendor_id);
        $vendor = $vinfo['shortForm'];
        
        $method = $vendor . $m;
        
        if(method_exists($this->ApiRecharge, $method)){
            $params = array();
            if(($m == 'MobRecharge') || ($m == 'BillPayment')){
                $params['operator'] = $operator;
                $params['type'] = 'flexi';
                $params['mobileNumber'] = $mobile;
                $params['amount'] = $amount;
            }
            elseif($m == 'DthRecharge'){
                $params['operator'] = $operator;
                $params['type'] = 'flexi';
                $params['subId'] = $mobile;
                $params['amount'] = $amount;
            }
            elseif($m == 'UtilityBillFetch' || $m == 'UtilityBillPayment'){
                $params['operator'] = $operator;
                $params['type'] = 'flexi';
                $params['accountNumber'] = $mobile;
                $params['retailer_id'] = $retailer_id;
                $params['amount'] = $amount;
                $params['param'] = $param;
                $params['param1'] = $param1;
            }
            
            if($m == 'UtilityBillFetch')
            {
                $ret = $this->ApiRecharge->$method($params,$prodId);    
            }
            else 
            {
                $ret = $this->ApiRecharge->$method($tranId, $params,$prodId);
            }
        }
        
        
        $this->General->printArray($ret);
    }
    
    function testBalanceApis($vendor_id){
        $vinfo = $this->Shop->getVendorInfo($vendor_id);
        $vendor = $vinfo['shortForm'];
        
        $method = $vendor . "Balance";
        
        if(method_exists($this->ApiRecharge, $method)){
            
            $ret = $this->ApiRecharge->$method();
            
            
        }
        
        $this->General->printArray($ret);
        $this->autoRender = false;
    }
    
    function testStatusApis($vendor_id,$tranId=null,$refId=null){
        $vinfo = $this->Shop->getVendorInfo($vendor_id);
        $vendor = $vinfo['shortForm'];
        
        $method = $vendor . "TranStatus";
        
        if(method_exists($this->ApiRecharge, $method)){
            $ret = $this->ApiRecharge->$method($tranId, date('Y-m-d'),$refId);
              
        }
        $this->General->printArray($ret);
        $this->autoRender = false;
    }
    
    
//    function sendNotificationToDistRet() {
//
//            $form_data  = array(
//                            'title'         => $title,
//                            'description'   => $description,
//                            'alert_type'    => $alert_type,
//                            'type'          => $type,
//                        );
//
//            $payload    = array(
//                            "type"  => "ServiceFeed",
//                            "title" => "New Feed",
//                            "msg"   => $form_data,
//                        );
//
//            $wrapper    = array(
//                            "data"          => $payload,
//                            "time_to_live"  => "86400"
//                        );
//        
//            $testing = " AND retailers.mobile IN ('8108681401','7101000450')";
//            $batches = $this->Slaves->query("SELECT * FROM (SELECT retailers.mobile, up.id, up.gcm_reg_id FROM retailers 
//                                            JOIN user_profile up ON (retailers.user_id = up.user_id) 
//                                            WHERE $testing ORDER BY up.id DESC) a GROUP BY retailers.mobile");
//            
//        
//            $this->autoRender = false;
//    }
}
