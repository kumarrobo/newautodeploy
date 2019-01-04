<?php
class RechargeComponent extends Object{
    var $components = array('Shop', 'ApiRecharge', 'General', 'Smartpaycomp');

    // mobile recharges
    function mobRecharge($params = null){
        $prodIdRes = $this->setProdIdMobRecharge($params);
        
        if($prodIdRes['status'] == 'failure'){
            return $prodIdRes;
        }
        
        $prodId = $prodIdRes['description'];
        $mobileNo = $params['mobileNumber'];
        $opData = $this->General->getMobileDetailsNew($mobileNo); // Get circle, area of that mobile number
        
        /* Check if operator is same wrt mobile numbering of that series for reliance cdma & gsm. */
        if( ! isset($params['power']) || $params['power'] != 1){
            if($prodId == 7 || $prodId == 8){
                if(($opData['operator'] == 'RC' && $prodId == 8) || ($opData['operator'] == 'RG' && $prodId == 7)){
                    return array('status'=>'failure', 'code'=>'8', 'description'=>$this->Shop->errors(8));
                }
            }
        }
        
        /* decimal amount check -- This code can go inside validation checks */
        if(strpos($params['amount'], '.')){
            return array('status'=>'failure', 'code'=>'6', 'description'=>$this->Shop->errors(6));
        }
        
        // Product validations based on product related parameters
        $info = $this->Shop->getProdInfo($prodId);
        
        $validate = $this->productValidations($params, $info, $opData);
        if($validate['status'] == 'failure'){
            return $validate;
        }
        
        // get priority vendor list
        $additional_param = array('amount'=>$params['amount'], 'dist_id'=>$_SESSION['Auth']['parent_id'], 'retailer_created'=>$_SESSION['Auth']['created'], 'retailer_id'=>$_SESSION['Auth']['id'], 'api_partner'=>(isset($params['api_partner']) ? $params['api_partner'] : ''), 'area'=>$opData['area']);
        
        $vendorData = $this->getVendorPriorityList($prodId, $mobileNo, $additional_param, $info);
        if($vendorData['status'] == 'failure'){ // If there is no vendor for the product then recharge cannot happen
            return $vendorData;
        }
        
        /* Now transaction can be created */
        $createTran = $this->Shop->createTransaction($prodId, $vendorData['info']['vendors']['0']['vendor_id'], $params['api_flag'], $params['mobileNumber'], $params['amount'], null, $params['ip']);
        if($createTran['status'] == 'failure'){
            return $createTran;
        }
        
        $this->General->logData("/mnt/logs/request.txt", $createTran['tranId'] . "::$prodId::".$vendorData['info']['vendors']['0']['vendor_id']."::first::" . json_encode($_SESSION));
        $params['area'] = empty($opData['area']) ? "" : $opData['area'];
        
        // Send request to tps queue
        $this->send_request_via_tps($createTran['tranId'], $prodId, 1, $params, $vendorData['info']['vendors']);
        
        return array('status'=>'success', 'code'=>0, 'description'=>$createTran['tranId'], 'balance'=>$createTran['balance'], 'service_charge'=>$createTran['service_charge']);
    }

    // dth recharges
    function dthRecharge($params){
        $prodIdRes = $this->setProdIdDthRecharge($params);
        
        if($prodIdRes['status'] == 'failure'){
            return $prodIdRes;
        }
        
        $prodId = $prodIdRes['description'];
        
        /* decimal amount check */
        if(strpos($params['amount'], '.')){
            return array('status'=>'failure', 'code'=>'6', 'description'=>$this->Shop->errors(6));
        }
        
        // Product validations based on product related parameters
        $info = $this->Shop->getProdInfo($prodId);
        
        $validate = $this->productValidations($params, $info, array(), $prodId);
        if($validate['status'] == 'failure'){
            return $validate;
        }
        
        // get priority vendor list
        $additional_param = array('amount'=>$params['amount'], 'dist_id'=>$_SESSION['Auth']['parent_id'], 'retailer_created'=>$_SESSION['Auth']['created'], 'retailer_id'=>$_SESSION['Auth']['id'], 'api_partner'=>(isset($params['api_partner']) ? $params['api_partner'] : ''));
        
        $vendorData = $this->getVendorPriorityList($prodId, null, $additional_param, $info);
        if($vendorData['status'] == 'failure'){ // If there is no vendor for the product then recharge cannot happen
            return $vendorData;
        }
        // call to shop api to insert a record in produts_users
        $createTran = $this->Shop->createTransaction($prodId, $vendorData['info']['vendors']['0']['vendor_id'], $params['api_flag'], $params['mobileNumber'], $params['amount'], $params['subId'], $params['ip']);
        if($createTran['status'] == 'failure') return $createTran;
        
        // Send request to tps queue
        if(DISH_TV_POINTS && date('H') > 9 && date('H') < 22 && $prodId == 18 && $params['amount'] >= 400){
            $this->manualDishTvTxn($createTran['tranId'], $prodId, 2, $params, $vendorData['info']['vendors']);
        }
        else {
            $this->send_request_via_tps($createTran['tranId'], $prodId, 2, $params, $vendorData['info']['vendors']);
        }
        
        return array('status'=>'success', 'balance'=>$createTran['balance'], 'description'=>$createTran['tranId'], 'service_charge'=>$createTran['service_charge']);
    }
    
    function manualDishTvTxn($request_id,$prodId,$service_id,$params,$data){
        $TPS_REQUEST_HASH = "TPS_DISHTV_DATA";
        $resquest_data = "";
        $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": in updater : data : " . $request_id);
        try{
            $redisObj = $this->Shop->redis_connector();
            if($redisObj == false){
                throw new Exception("cannot create redis object");
            }
            else{
                //$resquest_data = $redisObj->hget($TPS_REQUEST_HASH, $request_id);
                /*if($resquest_data !== false){
                    throw new Exception("txn id found in redis hash");
                }*/
                
                $pars = array('txn_id'=>$request_id,'prod_id'=>$prodId,'service_id'=>$service_id,'params'=>$params,'data'=>$data);
                $redisObj->hset($TPS_REQUEST_HASH, $request_id, json_encode($pars));
                $redisObj->setex("EXP_".$request_id, 15*60,1);
                
                $dbObj = ClassRegistry::init('User');
                
                $data = $dbObj->query("UPDATE vendors_activations SET vendor_id = 0 WHERE txn_id = '$request_id'");
                
            }
        }
        catch(Exception $ex){
            $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": not inserted data : " . $request_id);
        }
        
    }

    // vas recharge
    function vasRecharge($params){
        $dbObj = ClassRegistry::init('Slaves');
        
        $data = $dbObj->query("SELECT product_code,price,params FROM products_info WHERE product_id = " . $params['product']);
        
        if(empty($data)){
            return array('status'=>'failure', 'code'=>'9', 'description'=>$this->Shop->errors(9));
        }
        $prodId = $params['product'];
        if( ! empty($data['0']['products_info']['price'])){
            $params['amount'] = $data['0']['products_info']['price'];
        }
        else{
            $params['amount'] = $params['Amount'];
        }
        
        $allParams = json_decode($data['0']['products_info']['params'], true);
        $info = $this->Shop->getProdInfo($prodId);
        $validate = $this->productValidations($params, $info, array(), $prodId, $allParams);
        if($validate['status'] == 'failure'){
            return $validate;
        }
        
        // get active vendor
        $vendorData = $this->getVendorPriorityList($prodId, null, $additional_param, $info);
        
        /*
         * if($prodId == 35){
         * $pack = $this->getDittoPWList($params['Amount']);
         * $validAmt = $pack['status'];
         * //$denomDet = $pack['denomDet'];
         * //$type = $pack['type'];
         * if(!$validAmt){
         * return array('status'=>'failure','code'=>'6','description'=>"Invalid pack/wallet amount.");
         * }
         *
         * }
         */
        
        if(isset($params['param'])){
            $createTran = $this->Shop->createTransaction($prodId, $vendorData['info']['vendors'][0]['vendor_id'], $params['api_flag'], $params['Mobile'], $price, $params['param'], $params['ip']);
        }
        else{
            $createTran = $this->Shop->createTransaction($prodId, $vendorData['info']['vendors'][0]['vendor_id'], $params['api_flag'], $params['Mobile'], $price, null, $params['ip']);
        }
        if($createTran['status'] == 'failure') return $createTran;
        
        // Send request to tps queue
        $this->send_request_via_tps($createTran['tranId'], $prodId, 3, $params, $vendorData['info']['vendors']);
        
        return array('status'=>'success', 'balance'=>$createTran['balance'], 'description'=>$createTran['tranId']);
    }

    // bill payment
    function billPayment($params = null){
        $prodIdRes = $this->setProdIdBillPayment($params);
        
        if($prodIdRes['status'] == 'failure'){
            return $prodIdRes;
        }
        
        $prodId = $prodIdRes['description'];
        $mobileNo = $params['mobileNumber'];
        
        // Product validations based on product related parameters
        $info = $this->Shop->getProdInfo($prodId);
        if(in_array($_SESSION['Auth']['rental_flag'], array(1, 2)) && $info['service_id'] == 4){ // if retailer is on rental, he is not allowed to do bill payment
            return array('status'=>'failure', 'code'=>'44', 'description'=>$this->Shop->errors(44));
        }
        
        $validate = $this->productValidations($params, $info, array(), $prodId);
        if($validate['status'] == 'failure'){
            return $validate;
        }
        
        // get priority vendor list
        $additional_param = array('amount'=>$params['amount'], 'dist_id'=>$_SESSION['Auth']['parent_id'], 'retailer_created'=>$_SESSION['Auth']['created'], 'retailer_id'=>$_SESSION['Auth']['id'], 'api_partner'=>(isset($params['api_partner']) ? $params['api_partner'] : ''));
        
        $vendorData = $this->getVendorPriorityList($prodId, null, $additional_param, $info);
        
        if($vendorData['status'] == 'failure'){ // If there is no vendor for the product then recharge cannot happen
            return $vendorData;
        }
        
        $createTran = $this->Shop->createTransaction($prodId, $vendorData['info']['vendors']['0']['vendor_id'], $params['api_flag'], $params['mobileNumber'], $params['amount'], null, $params['ip']);
        if($createTran['status'] == 'failure') return $createTran;
        
        // Send request to tps queue
        $this->send_request_via_tps($createTran['tranId'], $prodId, 4, $params, $vendorData['info']['vendors']);
        
        return array('status'=>'success', 'balance'=>$createTran['balance'], 'description'=>$createTran['tranId'], 'service_charge'=>$createTran['service_charge']);
    }

    function pay1Wallet($params){
        $prodId = WALLET_ID;
        
        $mobileNo = $params['mobileNumber'];
        /* decimal amount check */
        if(strpos($params['amount'], '.')){
            return array('status'=>'failure', 'code'=>'6', 'description'=>$this->Shop->errors(6));
        }
        
        $info = $this->Shop->getProdInfo($prodId);
        
        $validate = $this->productValidations($params, $info, array(), $prodId);
        if($validate['status'] == 'failure'){
            return $validate;
        }
        
        // get priority vendor list
        $additional_param = array('amount'=>$params['amount'], 'dist_id'=>$_SESSION['Auth']['parent_id'], 'retailer_created'=>$_SESSION['Auth']['created'], 'retailer_id'=>$_SESSION['Auth']['id'], 'api_partner'=>$params['api_partner']);
        
        // get active vendor
        $vendorData = $this->getVendorPriorityList($prodId, null, $additional_param, $info);
        
        if($vendorData['status'] == 'failure'){ // If there is no vendor for the product then recharge cannot happen
            return $vendorData;
        }
        
        $createTran = $this->Shop->createTransaction($prodId, $vendorData['info']['vendors']['0']['vendor_id'], $params['api_flag'], $params['mobileNumber'], $params['amount'], null, $params['ip']);
        if($createTran['status'] == 'failure') return $createTran;
        
        // Send request to tps queue
        $this->send_request_via_tps($createTran['tranId'], $prodId, 5, $params, $vendorData['info']['vendors']);
        
        return array('status'=>'success', 'balance'=>$createTran['balance'], 'description'=>$createTran['tranId']);
    }
    
    function utilityBillInfo($params){
        
        Configure::load('billers');
        $billers = Configure::read('billers');
        $categories = Configure::read('categories');
        $dbObj = ClassRegistry::init('Slaves');
        
        Configure::load('product_config');
        $commissions = Configure::read('retailer_commission_6');
        
        $data = $dbObj->query("SELECT id,to_show,name FROM products WHERE service_id = 6");
        $billerData = array();
        foreach($data as $dt){
            $id = $dt['products']['id'];
            
            if(isset($billers[$id])){
                $billInfo = $billers[$id];
                $category = $billInfo['category'];
                $category_name = $categories[$category]['name'];
                $category_logo = $categories[$category]['logo'];
                $billInfo['show_flag'] = $dt['products']['to_show'];
                //$billInfo['Name'] = $dt['products']['name'];
                $billerData[$category_name]['data'][] = $billInfo;
                $billerData[$category_name]['icon'] = $category_logo;
            }
        }
        
        return array('status'=>'success','code'=>200,'description'=>$billerData,'service_charges'=>$commissions);
    }
    
    function utilityBillFetch($params){
        $prodIdRes = $this->setProdIdUtilityBillPayment($params);
        if($prodIdRes['status'] == 'failure'){
            return $prodIdRes;
        }
        $prodId = $prodIdRes['description'];
        $info = $this->Shop->getProdInfo($prodId);
        
        Configure::load('billers');
        $billers = Configure::read('billers');        
        $billerInfo = $billers[$prodId];
        $params['amount'] = 10;
        
        if($prodId == 150){
            $jharkhand_subdiv_codes = Configure::read('jharkhand_subdiv_codes');
            $params['param'] = $jharkhand_subdiv_codes[$params['param']];
        }
        
        $validate = $this->productValidations($params, $info, array(), $prodId);
        if($validate['status'] == 'failure'){
            return $validate;
        }
        
        // get active vendor
        $vendorData = $this->getVendorPriorityList($prodId, null, $additional_param, $info);
        
        if($vendorData['status'] == 'failure'){ // If there is no vendor for the product then recharge cannot happen
            $vendorData['code'] = 100;
            return $vendorData;
        }
        
        $vendors = $vendorData['info']['vendors'];
        $ret = array('status'=>'failure','code'=>400,'description'=>'bill info not found');
        
        foreach($vendors as $vend){
            $vendor_id = $vend['vendor_id'];
            $vinfo = $this->Shop->getVendorInfo($vendor_id);
            $vendor = $vinfo['shortForm'];
            $method = $vendor . "UtilityBillFetch";
            
            if(method_exists($this->ApiRecharge, $method)){
                $ret = $this->ApiRecharge->$method($params,$prodId);
                
                if($ret['status'] == 'success'){
                    $ret['code'] = 200;
                    return $ret;
                }
            }
            $this->General->logData('bill_fetch.txt',"Request::".json_encode($params)."::Response::".json_encode($ret));
        }
        $ret['code'] = 400;
        if(isset($params['test_flag']) && $params['test_flag'] == 1){
            $ret = array('status' => 'success', 'description' => array ( 'bill_number' => 274054776, 'bill_date' => '2018-07-17', 'due_date' => '2018-08-06', 'bill_amount' => 40, 'bill_period' => 'MONTHLY', 'customer_name' => 'RAMDARSH SINGH', 'acc_no_label' => 'Consumer No' ), 'code' => 200, 'bbps' => 1 ); 
        }
        return $ret;
    }

    function utilityBillPayment($params = null){
        $this->General->logData("/mnt/logs/zaptesting.txt", "Request::start" . date("Y-m-d H:i:s"));
        $prodIdRes = $this->setProdIdUtilityBillPayment($params);
        
        if($prodIdRes['status'] == 'failure'){
            $this->General->logData("/mnt/logs/zaptesting.txt", "Request::finish1" . date("Y-m-d H:i:s"));
            return $prodIdRes;
        }
        
        $prodId = $prodIdRes['description'];
        
        if($prodId == 150){
            Configure::load('billers');
            $jharkhand_subdiv_codes = Configure::read('jharkhand_subdiv_codes');
            $params['param'] = $jharkhand_subdiv_codes[$params['param']];
        }
        $info = $this->Shop->getProdInfo($prodId);
        
        $validate = $this->productValidations($params, $info, array(), $prodId);
        if($validate['status'] == 'failure'){
            $this->General->logData("/mnt/logs/zaptesting.txt", "Request::finish3" . date());
            return $validate;
        }
        
        // get priority vendor list
        $additional_param = array('amount'=>$params['amount'], 'dist_id'=>$_SESSION['Auth']['parent_id'], 'retailer_created'=>$_SESSION['Auth']['created'], 'retailer_id'=>$_SESSION['Auth']['id'], 'api_partner'=>$params['api_partner']);
        
        // get active vendor
        $vendorData = $this->getVendorPriorityList($prodId, null, $additional_param, $info);
        
        if($vendorData['status'] == 'failure'){ // If there is no vendor for the product then recharge cannot happen
            $this->General->logData("/mnt/logs/zaptesting.txt", "Request::finish4" . date());
            return $vendorData;
        }
        
        $par = $params['accountNumber'];
        if( ! empty($params['param'])) $par = "$par*" . $params['param'];
        if( ! empty($params['param1'])) $par = "$par*" . $params['param1'];
        
        $createTran = $this->Shop->createTransaction($prodId, $vendorData['info']['vendors']['0']['vendor_id'], $params['api_flag'], $params['mobileNumber'], $params['amount'], $par, $params['ip']);
        if($createTran['status'] == 'failure') return $createTran;
        
        // Send request to tps queue
        $this->send_request_via_tps($createTran['tranId'], $prodId, 6, $params, $vendorData['info']['vendors']);
        $this->General->logData("/mnt/logs/zaptesting.txt", "Request::finish 5" . date("Y-m-d H:i:s"));
        $ret = array('status'=>'success', 'code'=>200, 'balance'=>$createTran['balance'], 'description'=>$createTran['tranId'], 'service_charge'=>$createTran['service_charge'], 'timestamp'=>$createTran['timestamp']);
        
        if(isset($params['test_flag']) && $params['test_flag'] == 1){
            $ret = array('status' => 'success', 'code' => 200, 'balance' => 8228.42, 'description' => '302648819956', 'service_charge' => 0, 'timestamp' => '2018-08-06 12:32:24'); 
        }
        return $ret;
    }
    
    function bbpsComplaintRegistration($params) {
        
            $userObj = ClassRegistry::init('User');
            
            if($params['complaint_type'] == 'Service') {
                    if($params['participation_type'] == 'AGENT') {
                            $params['agent_id'] = $this->ApiRecharge->getAgentId($_SESSION['Auth']['id'],161);
//                            $res = $userObj->query("SELECT agent_id FROM bbps_agents WHERE retailer_id = '{$_SESSION['Auth']['id']}' AND vendor_id = 161");
//                            $params['agent_id'] = $res[0]['bbps_agents']['agent_id'];
                    }
            } else {
                    $res = $userObj->query("SELECT va.id, va.vendor_id, va.vendor_refid, va.timestamp, va.status, c.id FROM vendors_activations va LEFT JOIN complaints c ON (va.id = c.vendor_activation_id) WHERE va.txn_id = '{$params['txn_id']}'");
                    $params['txn_id'] = $res[0]['va']['vendor_refid'];
            }
            
                      
            
            if($res[0]['va']['vendor_id'] == BBPS_VENDOR) {
                    if(in_array($res[0]['va']['status'], array(1,5))){
                        $time_diff = $this->Smartpaycomp->dateDifference($res[0]['va']['timestamp'],date('Y-m-d H:i:s'),'%TH');
                        if($time_diff < 24)
                        {
                            return array('status'=>'failure','description'=>'Complaint should be raised after 24 hours.');
                        }
                    }
                    $result = $this->ApiRecharge->ccaComplaintRegistration($params);
            }
            
            if($result['status'] == 'success') {
                    $data = $result['description'];
                    $userObj->query("INSERT INTO bbps_complaints (vendor_activation_id, complaint_id, complaint_type, bbps_complaint_id, status, assigned_to, complaint_reason, date, timestamp) "
                            . "VALUES ('{$res[0]['va']['id']}', '{$res[0]['c']['id']}', '{$params['complaint_type']}', '{$data['complaintId']}', 'PENDING', '{$data['complaintAssigned']}', '{$data['complaint_reason']}', '".date('Y-m-d')."', '".date('Y-m-d H:i:s')."')");
                            
                    if($params['complaint_type'] == 'Transaction') {
                            $userObj->query("UPDATE complaints SET bbps_response = '".json_encode($data)."' WHERE vendor_activation_id = '{$res[0]['va']['id']}'");
                    }
            }

            return $result;
    }

    function bbpsComplaintTracking($params) {
            return $this->ApiRecharge->ccaComplaintTracking($params);
    }

    function tranStatus($tranId, $vendor, $date = null, $refId = null){
        if(intval($vendor) > 0){
            $vendor_id = $vendor;
            $vinfo = $this->Shop->getVendorInfo($vendor_id);
            $vendor = $vinfo['shortForm'];
        }
        else{
            $vendor_id = $this->Shop->getVendorId($vendor);
        }
        
        $method = $vendor . "TranStatus";
        
        if(method_exists($this->ApiRecharge, $method) && $method != 'modemTranStatus'){
            $status = $this->ApiRecharge->$method($tranId, $date, $refId);
        }
        else{
            if( ! empty($vendor_id)){
                $status = $this->ApiRecharge->modemTranStatus($tranId, $vendor_id, $date);
            }
            else{
                $status = array('status'=>'NA', 'description'=>'Not implemented yet :)');
            }
        }
        
        return $status;
    }

    function apiAutoStatus($vendor_id, $status){
        return $this->ApiRecharge->apiAutoStatus($vendor_id, $status);
    }

    function sendApibalanceLowAlert($data){
        Configure::load('checkapibalance');
        
        $smstemplate = Configure::read('apibalance.smstemplates');
        
        $mobile = Configure::read('apibalance.mobile');
        
        foreach($mobile as $row):
            if($data['min'] > 0){
                $messageString = sprintf($smstemplate, $data['apiname'], $data['min'], $data['currentbalance']);
                $this->General->sendMessage($row, $messageString, 'shops');
            }
        endforeach
        ;
    }

    function apiBalance($vendorId, $vendorKey = null, $cache = true){
        if(empty($vendorKey)){
            $info = $this->Shop->getVendorInfo($vendorId);
            $vendorKey = $info['shortForm'];
        }
        
        if($cache){
            $balance = $this->Shop->getMemcache("balance_" . $vendorId);
            if($balance === false) $cache = false;
        }
        
        if( ! $cache){
            $method = $vendorKey. "Balance";
            if(method_exists($this->ApiRecharge, $method)){
                $balance = $this->ApiRecharge->$method();
                $balance['last'] = $this->Shop->getMemcache("vendor$vendorId" . "_last");
                $this->Shop->setMemcache("balance_" . $vendorId, $balance, 24 * 60 * 60);
            }
        }
        
        return $balance;
    }

    function modemBalance($date, $vendorId, $sum_flag = false, $modem_src = true){
        $modemData = $this->ApiRecharge->modemBalance($date, $vendorId, $modem_src);
        
        if($sum_flag){
            $modem_bal = 0;
            foreach($modemData as $mds){
                $modem_bal += $mds['balance'];
            }
            return $modem_bal;
        }
        else
            return $modemData;
    }

    /*
     * cashpg payment main function
     */
    function cashpgPayment($params){
        App::Import('Model', 'C2d');
        $C2d = new C2d();
        $prodId = $params['operator'];
        $logger = $this->General->dumpLog('get_pending_request_by_mobile', 'cash_pg_payment');
        $logger->info("Received param : " . json_encode($params));
        
        $info = $this->Shop->getProdInfo($prodId);
        $validate = $this->productValidations($params, $info, array(), $prodId);
        if($validate['status'] == 'failure'){
            return $validate;
        }
        
        // get priority vendor list
        $additional_param = array('amount'=>$params['amount'], 'dist_id'=>$_SESSION['Auth']['parent_id'], 'retailer_created'=>$_SESSION['Auth']['created'], 'retailer_id'=>$_SESSION['Auth']['id'], 'api_partner'=>$params['api_partner']);
        
        // get active vendor
        $vendorData = $this->getVendorPriorityList($prodId, null, $additional_param, $info);
        
        if($vendorData['status'] == 'failure'){ // If there is no vendor for the product then recharge cannot happen
            return $vendorData;
        }
        
        $createTran = $this->Shop->createTransaction($prodId, $vendorData['info']['vendors']['0']['vendor_id'], $params['api_flag'], $params['mobileNumber'], $params['amount'], null, $params['ip']);
        
        if($createTran['status'] == 'failure') return $createTran;
        
        return $this->c2dPayment($createTran['tranId'], $params, $vendorData['info']['vendors']['0']['vendor_id'], $prodId, $logger, $C2d);
    }

    function setProdIdMobRecharge($params){
        $prodId = $this->ApiRecharge->mapping['mobRecharge'][$params['operator']][$params['type']]['id'];
        if(empty($prodId)) return array('status'=>'failure', 'code'=>'9', 'description'=>$this->Shop->errors(9));
        
        if(isset($params['special']) && $params['special'] == '1'){
            if($params['operator'] == '9' || $params['operator'] == '10'){
                $prodId = 27;
            }
            else if($params['operator'] == '11'){
                $prodId = 29;
            }
            else if($params['operator'] == '12'){
                $prodId = 28;
            }
            else if($params['operator'] == '30'){
                $prodId = 31;
            }
            else if($params['operator'] == '3'){
                $prodId = 34;
            }
        }
        
        if($prodId == 30){ // mtnl
            if((intval($params['amount']) % 10) != 0) $prodId = 31;
        }
        else if($prodId == 11){ // uninor
            if((intval($params['amount']) % 5) != 0) $prodId = 29;
        }
        
        return array('status'=>'success', 'code'=>0, 'description'=>$prodId);
    }

    function setProdIdDthRecharge($params){
        $prodId = $this->ApiRecharge->mapping['dthRecharge'][$params['operator']][$params['type']]['id'];
        if(empty($prodId)) return array('status'=>'failure', 'code'=>'9', 'description'=>$this->Shop->errors(9));
        
        return array('status'=>'success', 'code'=>0, 'description'=>$prodId);
    }

    function setProdIdBillPayment($params){
        $prodId = $this->ApiRecharge->mapping['billPayment'][$params['operator']][$params['type']]['id'];
        if(empty($prodId)) return array('status'=>'failure', 'code'=>'9', 'description'=>$this->Shop->errors(9));
        
        return array('status'=>'success', 'code'=>0, 'description'=>$prodId);
    }

    function setProdIdUtilityBillPayment($params){
        $prodId = $this->ApiRecharge->mapping['utilityBillPayment'][$params['operator']][$params['type']]['id'];
        if(empty($prodId)) return array('status'=>'failure', 'code'=>'9', 'description'=>$this->Shop->errors(9));
        
        return array('status'=>'success', 'code'=>0, 'description'=>$prodId);
    }

    function productValidations($params, $info, $opData = array(), $prodId = null, $extra = array()){
        
        // If the product is blocked for retailer's slab, then recharge cannot happen
        if(in_array($_SESSION['Auth']['slab_id'], $info['blocked_slabs'])){
            return array('status'=>'failure', 'code'=>'43', 'description'=>$this->Shop->errors(43));
        }
        
        // If operator is down then recharge cannot happen
        if($info['oprDown'] == '1'){
            return array('status'=>'failure', 'code'=>'43', 'description'=>$info['down_note']);
        }
        
        if(trim($params['amount']) < $info['min']){
            return array('status'=>'failure', 'code'=>'33', 'description'=>'Minimum recharge amount is Rs.' . $info['min']);
        }
        
        if(trim($params['amount']) > $info['max']){
            return array('status'=>'failure', 'code'=>'34', 'description'=>'Maximum recharge amount is Rs.' . $info['max']);
        }
        
        if(in_array($params['amount'], explode(",", $info['invalid']))){ // mtnl 125
            $desc = 'Recharge of Rs. ' . trim($params['amount']) . ' is temporary not available';
            return array('status'=>'failure', 'code'=>'29', 'description'=>$desc);
        }
        
        if( ! empty($opData)){
            if( ! empty($info['circles_yes']) &&  ! in_array($opData['area'], explode(",", $info['circles_yes']))){
                return array('status'=>'failure', 'code'=>'', 'description'=>'Cannot recharge on ' . $opData['area'] . ' circle', 'name'=>$info['name']);
            }
            
            if( ! empty($info['circles_no']) && in_array($opData['area'], explode(",", $info['circles_no']))){
                return array('status'=>'failure', 'code'=>'', 'description'=>'Cannot recharge on ' . $opData['area'] . ' circle', 'name'=>$info['name']);
            }
        }
        
        if($info['service_id'] == 2){
            return $this->dthValidations($params, $prodId);
        }
        else if($info['service_id'] == 3){
            return $this->verifyParams($params, $extra);
        }
        else if($info['service_id'] == 6){
            return $this->utilitybillValidations($params, $prodId);
        }
        
        return array('status'=>'success', 'code'=>200, 'description'=>'');
    }

    function dthValidations($params, $prodId){
        $len = strlen($params['subId']);
        if($prodId == 16 && ($len != 10 || substr($params['subId'], 0, 2) != "30")){ // Airtel DTH
            return array('status'=>'failure', 'code'=>'39', 'description'=>'Customer ID should start with 30 & should be 10 digit long');
        }
        else if($prodId == 17 && ($len != 12 || substr($params['subId'], 0, 2) != "20")){ // Big TV
            return array('status'=>'failure', 'code'=>'39', 'description'=>'Smart card no. should start with 20 & should be 12 digit long');
        }
        else if($prodId == 18 && ($len != 11)){ // Dish TV
            return array('status'=>'failure', 'code'=>'39', 'description'=>'Viewing card no. should start with 0 & should be 11 digit long');
        }
        else if($prodId == 19 && ($len != 11 ||  ! in_array(substr($params['subId'], 0, 1), array(1, 4)))){ // Sun TV
            return array('status'=>'failure', 'code'=>'39', 'description'=>'Smart card no. should start with 1 or 4 & should be 11 digit long');
        }
        else if($prodId == 20 && ($len != 10 || (substr($params['subId'], 0, 2) != "10" && substr($params['subId'], 0, 2) != "11" && substr($params['subId'], 0, 2) != "12"))){ // Tata Sky
            return array('status'=>'failure', 'code'=>'39', 'description'=>'Subscriber ID should start with 10, 11 or 12 & should be 10 digit long');
        }
        
        return array('status'=>'success', 'code'=>0, 'description'=>'');
    }

    function utilitybillValidations($params, $prodId){
        Configure::load('billers');
        $billers = Configure::read('billers');
        $billerInfo = $billers[$prodId];
        $fields = $billerInfo['fields'];
        
        foreach($fields as $field){
            $param = $field['param'];
            $label = $field['label'];
            $regex = $field['regex'];
            //$sample = $field['sample'];
            
            $val = isset($params[$param]) ? $params[$param] : '';
            if(!preg_match("/$regex/", $val) && !empty($val)){
                return array('status'=>'failure', 'code'=>'46', 'description'=>$label . ' is not valid. Please check the sample value');
            }
        }
        if($params['method'] == 'utilityBillPayment'){
            $after_due_date_flag = $billerInfo['after_due_date'];
            $amount_change_flag = $billerInfo['amount_change'];
            
            $bill_data = $this->Shop->getMemcache("bbps_".$prodId."_".$params['accountNumber']."_".$_SESSION['Auth']['id']."_161");
            if(!$bill_data){
                $bill_data = $this->Shop->getMemcache("bbps_".$prodId."_".$params['accountNumber']."_".$_SESSION['Auth']['id']."_8");
            }
            
            $this->General->logData('bill_fetch.txt',"bbps_key::"."bbps_".$prodId."_".$params['accountNumber']."_".$_SESSION['Auth']['id']."_161 ::Response::".json_encode($bill_data));
            
            if($prodId == 149 && (date('Y-m-d') > date('Y-m-d',strtotime($bill_data['due_date'].'-2 days')))){
                return array('status'=>'failure', 'code'=>0, 'description'=>'For Punjab operator,Kindly accept bill payments till 2 days before the due date.');
            }
            if(($after_due_date_flag === FALSE) && (date('Y-m-d') > $bill_data['due_date']))
            {
                return array('status'=>'failure', 'code'=>0, 'description'=>'Bill due date has expired.');
            }
            if(($amount_change_flag === true) && ($params['amount'] < $bill_data['bill_amount']))
            {
                return array('status'=>'failure', 'code'=>0, 'description'=>'Amount should be greater than or equal to bill amount.');
            }
        }
        
        return array('status'=>'success', 'code'=>0, 'description'=>'');
    }

    function setAfterTransParameter($param, $request){
//        $this->General->logData("/mnt/logs/setParam.txt", "Recharge request::" . json_encode($param) . "\n" . json_encode($request));
        
        if($request['service_id'] == 1){
            $set_param['funName'] = $request['vendor_short'] . "MobRecharge";
            $set_param['mobile'] = $param['mobileNumber'];
            $set_param['par'] = null;
            $set_param['amount'] = $param['amount'];
            $set_param['funName1'] = "modemMobRecharge";
        }
        else if($request['service_id'] == 2){
            $set_param['funName'] = $request['vendor_short'] . "DthRecharge";
            $set_param['mobile'] = $param['mobileNumber'];
            $set_param['par'] = $param['subId'];
            $set_param['amount'] = $param['amount'];
            $set_param['funName1'] = "modemDthRecharge";
        }
        else if($request['service_id'] == 3){
            $set_param['funName'] = $request['vendor_short'] . "Recharge";
            $set_param['mobile'] = $param['Mobile'];
            $set_param['par'] = null;
            $set_param['amount'] = $param['Amount'];
            $set_param['funName1'] = "modemRecharge";
        }
        else if($request['service_id'] == 4){
            $set_param['funName'] = $request['vendor_short'] . "BillPayment";
            $set_param['mobile'] = $param['mobileNumber'];
            $set_param['par'] = null;
            $set_param['amount'] = $param['amount'];
            $set_param['funName1'] = "modemBillPayment";
        }
        else if($request['service_id'] == 5){
            $set_param['funName'] = "b2cPay1Wallet";
            $set_param['mobile'] = $param['mobileNumber'];
            $set_param['par'] = null;
            $set_param['amount'] = $param['amount'];
            $set_param['funName1'] = "";
        }
        else if($request['service_id'] == 6){
            $set_param['funName'] = $request['vendor_short'] . "UtilityBillPayment";
            $set_param['mobile'] = $param['mobileNumber'];
            $set_param['par'] = $param['accountNumber'];
            $set_param['param1'] = $param['param'];
            $set_param['param2'] = $param['param1'];
            $set_param['retailer_id'] = $request['retailer_id'];
            $set_param['amount'] = $param['amount'];
            $set_param['funName1'] = "";
        }
        else if(isset($request['service_id'])){
            $set_param['funName'] = $request['vendor_short'] . "Topup";
            $set_param['mobile'] = $param['mobileNumber'];
            $set_param['par'] = $param['param'];
            $set_param['amount'] = $param['amount'];
            $set_param['funName1'] = "";
        }
        return $set_param;
    }

    function fetch_formated_request_data($request_id){
        $TPS_REQUEST_HASH = "TPS_REQUEST_DATA";
        $resquest_data = "";
        $this->General->logData("/mnt/logs/tps_changes-" . date('Ymd') . ".txt", date('Y-m-d H:i:s') . ": in updater : data : " . $request_id);
        try{
            $redisObj = $this->Shop->redis_connector();
            if($redisObj == false){
                throw new Exception("cannot create redis object");
            }
            else{
                $resquest_data = $redisObj->hget($TPS_REQUEST_HASH, $request_id);
                if(empty($resquest_data) || trim($resquest_data) == "" || $resquest_data === false){
                    throw new Exception("txn id not found in redis hash");
                }
                $result = $redisObj->hdel($TPS_REQUEST_HASH, $request_id);
                $_REQUEST = json_decode($resquest_data, true);
                $this->General->logData("/mnt/logs/tps_changes.txt", date('Y-m-d H:i:s') . ": in updater : data : " . $TPS_REQUEST_HASH . "| " . $request_id . " | " . $resquest_data);
                
                if($redisObj->hexists($TPS_REQUEST_HASH, $request_id)){
                    $this->General->logData("/mnt/logs/tps_changes.txt", date('Y-m-d H:i:s') . ": hash key not deleted ");
                    $redisObj->hdel($TPS_REQUEST_HASH, $request_id);
                }
            }
        }
        catch(Exception $ex){
            $EXCEPTION_MSG = "";
            try{
                $this->General->logData("/mnt/logs/tps_changes.txt", date('Y-m-d H:i:s') . " | $request_id : Exception msg : " . $ex->getMessage());
                $EXCEPTION_MSG = " redis exception : " . $ex->getMessage();
                $resquest_data = $this->Shop->getMemcache('TPS_REQUEST_DATA_' . $request_id);
                $_REQUEST = json_decode($resquest_data, true);
                if(empty($_REQUEST)){
                    throw new Exception("value not found in memcache");
                }
            }
            catch(Exception $ex1){
                $this->General->logData("/mnt/logs/tps_changes.txt", date('Y-m-d H:i:s') . " | $request_id : Exception msg : " . $ex1->getMessage());
                $EXCEPTION_MSG .= " memcach exception : " . $ex1->getMessage();
            }
            $this->General->sendMails('TSP redis connection issue', " date : " . date('Y-m-d H:i:s') . " | txnid : " . $request_id . "<br>" . $EXCEPTION_MSG, array('nandan.rana@pay1.in', 'ashish@pay1.in'), 'mail');
        }
        
        return $_REQUEST;
    }

    function getTxnData($transId){
        $data = $this->Shop->getMemcache("txn$transId");
        
        if($data){
            return $data;
        }
        else{
            $userObj = ClassRegistry::init('Slaves');
            $txnData = $userObj->query("SELECT va.mobile,va.product_id,va.amount,va.param,products.service_id FROM vendors_activations as va LEFT JOIN products ON (products.id = product_id) WHERE txn_id = '$transId'");
            $opData = $this->General->getMobileDetailsNew($txnData['0']['va']['mobile']);
            $priorityList = $this->getVendorPriorityList($txnData['0']['va']['product_id'], $txnData['0']['va']['mobile'], array(), array());
            $vendorData = $userObj->query("SELECT GROUP_CONCAT(distinct vm.service_vendor_id) as ids FROM vendors_messages as vm WHERE va_tran_id = '$transId'");
            
            $pars = explode("*", $txnData['0']['va']['param']);
            $data = array('ref_code'=>$transId, 'area'=>$opData['area'], 'service_id'=>$txnData['0']['products']['service_id'], 'product_id'=>$txnData['0']['va']['product_id'], 'mobile'=>$txnData['0']['va']['mobile'], 'param'=>$pars[0], 'amount'=>$txnData['0']['va']['amount'], 
                    'extra'=>(isset($pars[1])) ? $pars[1] : '', 'priorityList'=>$priorityList['info']['vendors'], 'vendors'=>( ! empty($vendorData[0][0]['ids'])) ? explode(",", $vendorData[0][0]['ids']) : array(), 'timestamp'=>$txnData['0']['va']['timestamp']);
            return $data;
        }
    }

    function checkIfTxnExpired($transId){
        $expData = $this->Shop->getMemcache("txnExp$transId");
        if($expData !== false){
            if($expData){
                return false;
            }
            else
                return true;
        }
        else{
            return true;
        }
    }

    function updateTransactionStatus($vendorId, $txnData, $status,$locked = false){
        try{
            $transId = $txnData['va']['txn_id'];
            $vaData = array();
            
            if(!$locked){
                if(! $this->lockTransaction($transId)) return;
            }
            
            $dbObj = ClassRegistry::init('User');
            $dbObj = $dbObj->getDataSource();
            $dbObj->begin();
            
            if(empty($txnData['va']['operator_id']) &&  ! empty($status['operator_id'])){
                $vaData['operator_id'] = $status['operator_id'];
            }
            if(empty($txnData['va']['vendor_refid']) &&  ! empty($status['vendor_id'])){
                $vaData['vendor_refid'] = $status['vendor_id'];
                $txnData['va']['vendor_refid'] = $status['vendor_id'];
            }
            
            if($status['status'] == 'success'){
                $this->Shop->setProdVendorHealth($vendorId, $txnData['va']['product_id'], 1);
                $this->update_in_vendors_activations(array_merge(array('prevStatus'=>$txnData['va']['status'], 'status'=>TRANS_SUCCESS), $vaData), array('txn_id'=>$transId), $dbObj);
                $this->log_in_vendor_message(array('va_tran_id'=>$transId, 'vendor_refid'=>$txnData['va']['vendor_refid'], 'service_id'=>$txnData['products']['service_id'], 'service_vendor_id'=>$vendorId, 'internal_error_code'=>13, 'response'=>addslashes($status['description']), 
                        'status'=>'success', 'timestamp'=>date("Y-m-d H:i:s"), 'vm_date'=>date('Y-m-d')), $dbObj);
            }
            else if($status['status'] == 'failure'){
                $this->Shop->setProdVendorHealth($vendorId, $txnData['va']['product_id'], 1);
                $this->log_in_vendor_message(array('va_tran_id'=>$transId, 'vendor_refid'=>$txnData['va']['vendor_refid'], 'service_id'=>$txnData['products']['service_id'], 'service_vendor_id'=>$vendorId, 'internal_error_code'=>14, 'response'=>addslashes($status['description']), 
                        'status'=>'failure', 'timestamp'=>date("Y-m-d H:i:s"), 'vm_date'=>date('Y-m-d')), $dbObj);
                $this->routeTransaction($transId, array(), true, true);
            }
            $this->unlockTransaction($transId);
            $dbObj->commit();
        }
        catch(Exception $e){
            $dbObj->rollback();
            $this->unlockTransaction($transId);
        }
    }

    function getSetTransactionStatus($vendorId, $txnData, $update_flag = false){
        $transId = $txnData['va']['txn_id'];
        
        $vendInfo = $this->Shop->getVendorInfo($vendorId);
        $vendName = $vendInfo['shortForm'];
        
        $method = $vendName . "TranStatus";
        $status = $this->ApiRecharge->$method($transId, $txnData['va']['date'], $txnData['va']['vendor_refid']);
        
        if($update_flag){
            $dbObj = ClassRegistry::init('User');
            $data = $dbObj->query("SELECT va.id,va.txn_id,va.vendor_refid,va.status,va.prevStatus,products.service_id,va.timestamp,va.product_id,va.date,va.operator_id,va.vendor_id,vendors.company,va.cc_userid FROM vendors_activations as va LEFT JOIN products ON (va.product_id=products.id) LEFT JOIN vendors ON (vendors.id = vendor_id) WHERE txn_id='$transId'");
            
            $this->General->logData("trans_status.txt","1::$transId :: ". $data[0]['va']['status'] . ":: ".$data[0]['va']['prevStatus']."::".$data[0]['va']['vendor_id']);
            if($vendorId == $data[0]['va']['vendor_id'] && ($data[0]['va']['status'] == 0 OR ($data[0]['va']['prevStatus'] == 0 AND $data[0]['va']['status'] == 4))){
                $this->General->logData("trans_status.txt","2::$transId :: ". $data[0]['va']['status'] . ":: ".$data[0]['va']['vendor_id']);
                
                $this->updateTransactionStatus($vendorId, $txnData, $status);
            }
        }
        
        return $status;
    }

    function routeTransaction($transId, $data = array(), $lock = false, $reverse_flag = false, $err_code = null){
        if(( ! $lock) && ( ! $this->lockTransaction($transId))){
            return false;
        }
        
        $this->General->logData("/mnt/logs/request.txt", "In routeTransaction::$transId");
        
        if(empty($data)){
            $expired = $this->checkIfTxnExpired($transId);
            if($expired){
                if($reverse_flag) $this->Shop->reverseTransaction($transId, 1, $err_code);
                
                $this->unlockTransaction($transId);
                return false;
            }
            
            $data = $this->getTxnData($transId);
        }
        
        if(in_array($data['product_id'], array(11,29)) && $data['area'] != 'BR'){
            $data['product_id'] = 2;
        }
        
        $amount = $data['amount'];
        $productId = $data['product_id'];
        $mobile = $data['mobile'];
        
        $inactive = $this->Shop->getInactiveVendors();
        
        $priorityList = $data['priorityList'];
        $vendors = $data['vendors'];
        $logData = array();
        $vendorReply = array();
        // $this->General->logData("/mnt/logs/request.txt", "In routeTransaction::$transId: ");
        
        foreach($priorityList as $vend){
            $vendor_id = $vend['vendor_id'];
            
            if(in_array($vendor_id, $vendors)) continue;
            $this->General->logData("/mnt/logs/request.txt", "In routeTransaction::$transId: $vendor_id::checking for inactive");
            if(in_array($vendor_id, $inactive)) continue;
            
            $this->General->logData("/mnt/logs/request.txt", "In routeTransaction::$transId: $vendor_id");
            // for API only
            if($vend['update_flag'] == 0){
                $key_vendor = $this->apicheckForCapacity($vendor_id, $data['product_id']);
                if($key_vendor === false) continue;
            }
            // Keeping last vendor in memcache
            $vendor_last = $vendor_id;
            $data['last_vendor'] = array('vendor_id'=>$vendor_id, 'discount_commission'=>$vend['discount_commission']);
            $this->Shop->setMemcache("txn$transId", $data, 60 * 60);
            
            $reply = $this->processTransaction($data, $vend, $transId);
            $data = $reply['data'];
            $vendorReply = $reply['reply'];
            $logData[$vendor_id] = $vendorReply;
            
            if($vendorReply['status'] != 'failure'){
                $this->Shop->setMemcache("vendor$vendor_id" . "_last", date('Y-m-d H:i:s'), 24 * 60 * 60);
                break;
            }
            else{
                if(isset($key_vendor) &&  ! empty($key_vendor)){
                    $this->Shop->incrementMemcache($key_vendor);
                }
            }
        }
        
        if(empty($vendorReply)){
            $vendorReply['status'] = 'failure';
            if(empty($vendor_last)){
                $vendor_last = $data['last_vendor']['vendor_id'];
                if(empty($vendor_last) && !empty($data['vendors'])){
                    $vendor_last = end($data['vendors']);
                }
            }
            if(empty($vendor_last)){
                $vendor_last = $priorityList[0]['vendor_id'];
            }
            $logData[$vendor_last] = $vendorReply;
        }
        $this->Shop->setMemcache("txn$transId", $data, 60 * 60);
        
        $vendorReply['operator_id'] = empty($vendorReply['operator_id']) ? "" : $vendorReply['operator_id'];
        $vendorReply['cc_userid'] = empty($vendorReply['pinRefNo']) ? "" : $vendorReply['pinRefNo'];
        $vendor_disc_comm = (isset($vendorReply['disc_comm']) &&  ! empty($vendorReply['disc_comm'])) ? $vendorReply['disc_comm'] : 0;
        $status = ($vendorReply['status'] == 'success') ? TRANS_SUCCESS : (($vendorReply['status'] == 'failure') ? TRANS_REVERSE : 0);
        
        $vendor_disc_comm = $this->calculateVendorCommission($amount, $vendor_disc_comm);
        $this->update_in_vendors_activations(array('vendor_id'=>$vendor_last, 'discount_commission'=>$vendor_disc_comm, 'vendor_refid'=>addslashes($vendorReply['tranId']), 'status'=>$status, 'code'=>$vendorReply['code'], 'cause'=>addslashes($vendorReply['description']), 
                'operator_id'=>addslashes($vendorReply['operator_id']), 'cc_userid'=>addslashes($vendorReply['cc_userid']), 'sim_num'=>'', 'tran_processtime'=>''), array('txn_id'=>$transId));
        $this->updateTransactionLogs($transId, $logData, $data['service_id']);
        $this->General->logData('/mnt/logs/vendor_status.txt', 'Vendor reply :: '.$vendor_last.'_'.$vendorReply['operator_id'].' : Response : '. json_encode($vendorReply));
        $this->Shop->setProdVendorHealth($vendor_last, $productId, $status); // to manage pending requests
        
        if($status == TRANS_REVERSE){
            $this->Shop->reverseTransaction($transId, 1, $vendorReply['code']);
        }
        
        if( ! $lock) $this->unlockTransaction($transId);
        
        return true;
    }
    
    function calculateVendorCommission ($amount,$comm){
        if(strpos($comm, '%') === false){
            $value = $comm;
            $var = $value*100/$amount;
        }
        elseif(strpos($comm, '%') !== false){
            $var = rtrim($comm,'%');
        }
        
        return $var;
    }

    function processTransaction($data, $vendor, $transId){
        $data['vendors'][] = $vendor['vendor_id'];
        $param = array();
        
        $param['mobileNumber'] = $data['mobile'];
        $param['amount'] = $data['amount'];
        $param['type'] = "flexi";
        $param['area'] = $data['area'];
        $param['circle'] = "";
        $param['subId'] = $data['param'];
        
        $opr = $this->Shop->smsProdCodes($data['product_id']);
        $param['operator'] = (empty($opr['operator'])) ? $data['product_id'] : $opr['operator'];
        $data['vendor_short'] = $vendor['shortForm'];
        
        $set_param = $this->setAfterTransParameter($param, $data);
        $funName = $set_param['funName'];
        $funName1 = $set_param['funName1'];
        
        if($data['service_id'] == 6){
            $param['retailer_id'] = $data['retailer_id'];
            $param['accountNumber'] = $data['param'];
            $param['param'] = $data['param1'];
            $param['param1'] = $data['param2'];
        }        
        //$this->General->logData("/mnt/logs/request.txt", "In processTransaction-2::$transId:" . $vendor['vendor_id'] . "::$funName::$funName1");
        
        try{
            if( ! method_exists($this->ApiRecharge, $funName) && method_exists($this->ApiRecharge, $funName1)){
                $vendorReply = $this->ApiRecharge->$funName1($transId, $param, $data['product_id'], $vendor['vendor_id'], $vendor['shortForm']);
                //$this->General->logData("/mnt/logs/request.txt", "In processTransaction-21::$transId:" . $vendor['vendor_id'] . "::$funName::$funName1: i am inside $funName1 function");
            }
            else if(method_exists($this->ApiRecharge, $funName)){
                $vendorReply = $this->ApiRecharge->$funName($transId, $param, $data['product_id']);
                //$this->General->logData("/mnt/logs/request.txt", "In processTransaction-22::$trasId:" . $vendor['vendor_id'] . "::$funName::$funName1: i am inside $funName function");
            }
            else{
                $vendorReply['status'] = 'failure';
            }
        }
        catch(Exception $e){
            $vendorReply['status'] = 'failure';
        }
        
        $vendorReply['disc_comm'] = $vendor['discount_commission'];
        $vendorReply['timestamp'] = date('Y-m-d H:i:s');
        
        $data['logData'][$vendor['vendor_id']] = $vendorReply;
        //$this->General->logData("/mnt/logs/request.txt", "In processTransaction-3::$transId:" . $vendor['vendor_id'] . "::$funName:" . json_encode($vendorReply));
        
        return array('data'=>$data, 'reply'=>$vendorReply);
    }

    function updateTransactionLogs($transId, $logData, $service_id){
        $vm_array = array();
        foreach($logData as $vendor=>$log){
            $internal_code = isset($log['internal_error_code']) ? $log['internal_error_code'] : 0;
            $vendor_response = isset($log['vendor_response']) ? $log['vendor_response'] : '';
            $timestamp = $log['timestamp'];
            $date = date('Y-m-d', strtotime($timestamp));
            $vm_array[] = "('$transId','" . $log['tranId'] . "','$service_id','$vendor','$internal_code','" . addslashes($vendor_response) . "','" . $log['status'] . "','$timestamp','$date')";
        }
        
        $userObj = ClassRegistry::init('User');
        $vm_array = array_chunk($vm_array, 20);
        //$this->General->logData('query_failure.txt', 'query_failed: ' . json_encode($vm_array));
        
        foreach($vm_array as $vm_arr){
            $qstring = "INSERT into vendors_messages(va_tran_id,vendor_refid,service_id,service_vendor_id,internal_error_code,response,status,timestamp,vm_date) values " . implode(",", $vm_arr);
            if( ! $userObj->query($qstring)){
                $this->General->logData('query_failure.txt', 'query_failed: ' . $qstring);
                $this->reQuery($qstring);
            }
        }
    }

    function apicheckForCapacity($vendor_id, $prodId){
        $arr_map = array('7'=>'8', '10'=>'9', '27'=>'9', '28'=>'12', '29'=>'11', '31'=>'30', '34'=>'3', '181'=>'18');
        $key_vendor = '';
        $prod = (isset($arr_map[$prodId])) ? $arr_map[$prodId] : $prodId;
        $data = $this->Shop->getMemcache("status_$prod" . "_$vendor_id");
        
        $this->General->logData("/mnt/logs/request.txt", "In apicheckForCapacity:: $vendor_id::$prodId::$data");
        if($data !== false && $data > 0){
            
            $data = $this->Shop->decrementMemcache("status_$prod" . "_$vendor_id");
            if($data >= 0){
                $key_vendor = "status_$prod" . "_$vendor_id";
                return $key_vendor;
            }
        }
        else if($data === false){
            return $key_vendor;
        }
        return false;
    }

    function isPriorityTxn($params){
        if(isset($params['api_partner']) && $params['api_partner'] == 6){ // b2c api partner
            return true;
        }
        
        if(isset($params['retailer_created'])){ // assign particular modem for new retailers otherwise b2c
            
            $diff = strtotime(date('Y-m-d')) - strtotime($params['retailer_created']);
            
            if($diff / (60 * 60 * 24) <= 30){
                return true;
            }
        }
        return false;
    }

    function getVendorPriorityList($prodId, $mobileNo, $additional_param, $info = array()){
        $vendor_list = array();
        $vendor_priority_list = array();
        
        if(in_array($prodId, array(11,29)) && $additional_param['area'] != 'BR'){
            $prodId = 2;
        }
        if(empty($info)){
            $info = $this->Shop->getProdInfo($prodId);
        }
        
        $non_primary = (isset($info['non_primary'])) ? $info['non_primary'] : array();
        
        $vendor_mapping = $this->generate_local_vendor_map($info, $prodId);
        $priority_txn = $this->isPriorityTxn($additional_param);
        $exceptional_vendors = array();
        foreach($vendor_mapping as $vendor_map){
            $exceptional_vendors = array_merge($exceptional_vendors, array_values($vendor_map));
        }
        $exceptional_vendors = array_unique($exceptional_vendors); // local setups
        //$this->General->logData("/mnt/logs/txnData.txt", "vendor mappng: " . json_encode($vendor_mapping) . "::" . json_encode($exceptional_vendors) . "::" . json_encode($additional_param));
        $counter = 0;
        foreach($info['vendors'] as $vend){
            $weight = 0;
            $counter ++ ;
            // Grade A = 3, B = 2, C = 1
            $grade = ($priority_txn) ? $vend['modem_grade'] : 1;
            //$this->General->logData("/mnt/logs/txnData.txt", "Vendor: " . json_encode($vend));
            
            $circle_yes = $vend['circles_yes'];
            $circle_no = $vend['circles_no'];
            
            $imp_yes = implode(",", $circle_yes);
            $imp_no = implode(",", $circle_no);
            
            if( ! empty($imp_yes) && isset($additional_param['area']) &&  ! in_array($additional_param['area'], $circle_yes)){
                //$this->General->logData("/mnt/logs/txnData.txt", "problem wrt circle yes");
                continue;
            }
            if( ! empty($imp_no) && isset($additional_param['area']) && in_array($additional_param['area'], $circle_no)){
                //$this->General->logData("/mnt/logs/txnData.txt", "problem wrt circle no");
                continue;
            }
            
            $denom_yes = $vend['denom_yes'];
            $denom_no = $vend['denom_no'];
            
            $imp_yes_denom = implode(",", $denom_yes);
            $imp_no_denom = implode(",", $denom_no);
            
            if( ! empty($imp_yes_denom) && isset($additional_param['amount']) &&  ! in_array($additional_param['amount'], $denom_yes)){
                //$this->General->logData("/mnt/logs/txnData.txt", "denom_yes");
                continue;
            }
            if( ! empty($imp_no_denom) && isset($additional_param['amount']) && in_array($additional_param['amount'], $denom_no)){
                //$this->General->logData("/mnt/logs/txnData.txt", "denom_no");
                continue;
            }
            
            // If a vendor is set to do transaction for some spectific time duration then keep it on #1 priority
            if(($vend['from_STD'] == '00:00:00' && $vend['to_STD'] == '00:00:00') || ($vend['from_STD'] != $vend['to_STD'] && date('H:i:s') >= $vend['from_STD'] && date('H:i:s') <= $vend['to_STD'])){
                
                if(in_array($vend['vendor_id'], $exceptional_vendors)){
                    $retailer_id = isset($additional_param['retailer_id']) ? $additional_param['retailer_id'] : 0;
                    $dist_id = isset($additional_param['dist_id']) ? $additional_param['dist_id'] : 0;
                    
                    if(isset($vendor_mapping["ret" . $retailer_id . "_" . $prodId]) && in_array($vend['vendor_id'], $vendor_mapping["ret" . $retailer_id . "_" . $prodId])){
                        $weight = 20000 - $counter;
                        $vend['counter'] = $counter;
                        $vendor_list[$weight] = $vend;
                        //$this->General->logData("/mnt/logs/txnData.txt", "LocalArea vendor:: Added vendor with weight wrt retailer: $weight");
                        continue;
                    }
                    else if(isset($vendor_mapping["dist" . $dist_id . "_" . $prodId]) && in_array($vend['vendor_id'], $vendor_mapping["dist" . $dist_id . "_" . $prodId])){
                        $weight = 20000 - $counter;
                        $vend['counter'] = $counter;
                        $vendor_list[$weight] = $vend;
                        //$this->General->logData("/mnt/logs/txnData.txt", "LocalArea vendor:: Added vendor with weight wrt distributor: $weight");
                        continue;
                    }
                    else{
                        //$this->General->logData("/mnt/logs/txnData.txt", "LocalArea vendor:: cannot add the vendor");
                        
                        continue;
                    }
                }
                
                if( ! empty($vend['denom_primary']) && isset($additional_param['amount']) && in_array($additional_param['amount'], explode(",", $vend['denom_primary']))){
                    if( ! empty($vend['denom_circle']) && in_array($additional_param['area'], explode(",", $vend['denom_circle']))){ // primary denomination
                        $weight = 8000 - $counter;
                        $vend['counter'] = $counter;
                        $vendor_list[$weight] = $vend;
                        //$this->General->logData("/mnt/logs/txnData.txt", "denom_circle case with primary denomination:: Added vendor with weight: $weight");
                        
                        // continue;
                    }
                    else if(empty($vend['denom_circle'])){
                        $weight = 8000 - $counter;
                        $vend['counter'] = $counter;
                        $vendor_list[$weight] = $vend;
                       // $this->General->logData("/mnt/logs/txnData.txt", "denom_primary case without denomination circle:: Added vendor with weight: $weight");
                        
                        // continue;
                    }
                }
                
                if( ! empty($vend['circle']) && isset($additional_param['area']) && in_array($additional_param['area'], explode(",", $vend['circle']))){
                    if($weight > 0) unset($vendor_list[$weight]);
                    $weight += 4000 - $counter;
                    $vend['counter'] = $counter;
                    
                    $vendor_list[$weight] = $vend;
                    //$this->General->logData("/mnt/logs/txnData.txt", "primary circle case:: Added vendor with weight: $weight");
                    
                    // continue;
                }
                
                if($vend['from_STD'] != $vend['to_STD']){ // time interval is set
                    if($weight > 0) unset($vendor_list[$weight]);
                    $weight += 6000 - $counter;
                    $vend['counter'] = $counter;
                    $vendor_list[$weight] = $vend;
                    //$this->General->logData("/mnt/logs/txnData.txt", "time interval priority:: Added vendor with weight: $weight");
                }
                else{
                    if($weight > 0) unset($vendor_list[$weight]);
                    $weight += 1000 * $grade - $counter;
                    $vend['counter'] = $counter;
                    $vendor_list[$weight] = $vend;
                    //$this->General->logData("/mnt/logs/txnData.txt", "normal case:: Added vendor with weight: $weight");
                }
                
                // $this->General->logData("/mnt/logs/txnData.txt", date('Y-m-d H:i:s') . ":weight::$weight" . json_encode($vend));
            }
        }
        
        foreach($vendor_list as $weight_old=>$vend){
            // if vendor's target is completed then there is no need to prioritize that vendor on top
            if($weight_old > 1000 && in_array($vend['vendor_id'], $non_primary)){
                $weight = 1000 - $vend['counter'];
                $vendor_list[$weight] = $vend;
                unset($vendor_list[$weight_old]);
            }
        }
        
        krsort($vendor_list);
        $vendors_queue = array_values($vendor_list);
        $info['vendors'] = $vendors_queue;
        
        //$this->General->logData("/mnt/logs/txnData.txt", date('Y-m-d H:i:s') . ":Final List::" . json_encode($info['vendors']));
        if(empty($info['vendors'])){
            return array('status'=>'failure', 'code'=>'29', 'description'=>$this->Shop->errors(29), 'name'=>$info['name'], 'info'=>$info);
        }
        else{
            return array('status'=>'success', 'code'=>200, 'description'=>'', 'info'=>$info);
        }
    }

    /**
     * Generate and arrange the vendors mapping in specified structured based on input
     *
     * @return type array
     */
    function generate_local_vendor_map($info, $prodId){
        $mapArr = $info['vendors'];
        $returnArr = array();
        foreach($mapArr as $data){
            $retailers = trim($data['retailers']);
            $distributors = trim($data['distributors']);
            if(empty($retailers) && empty($distributors)) continue;
            
            $vendorId = $data['vendor_id'];
            if( ! empty($retailers)){
                $opr_ret_Arr = explode(",", $retailers);
                foreach($opr_ret_Arr as $ret_Id){
                    $returnArr["ret" . $ret_Id . "_" . $prodId][] = $vendorId;
                }
            }
            
            if( ! empty($distributors)){
                $opr_dist_Arr = explode(",", $distributors);
                foreach($opr_dist_Arr as $dist_Id){
                    $returnArr["dist" . $dist_Id . "_" . $prodId][] = $vendorId;
                }
            }
        }
        return $returnArr;
    }

    function send_request_via_tps($txnid, $prodId, $service_id, $params, $vendors){
        $pars = array();
        $pars['vendors'] = json_encode($vendors);
        $pars['tranId'] = $txnid;
        $pars['service_id'] = $service_id;
        $pars['vendor_short'] = $vendors[0]['shortForm'];
        $params['retailer_code'] = $_SESSION['Auth']['id'];
        $params['retailer_name'] = isset($_SESSION['Auth']['shopname']) ? $_SESSION['Auth']['shopname'] : "";
        $params['retailer_mobile'] = isset($_SESSION['Auth']['mobile']) ? $_SESSION['Auth']['mobile'] : "";
        $params['b2c_campaign_flag'] = isset($_SESSION['Auth']['b2c_campaign_flag']) ? $_SESSION['Auth']['b2c_campaign_flag'] : "";
        
        $pars['params'] = json_encode($params);
        $pars['product_id'] = $prodId;
        
        $this->General->logData("/mnt/logs/new_request.txt", "Recharge request::" . $txnid);
        
        /*
         * $handler_Q = "TXN_REQUEST_QUEUE";
         * $TPS_REQUEST_HASH = "TPS_REQUEST_DATA";
         * try{
         * $redisObj = $this->Shop->redis_connector();
         * if($redisObj != false){
         * $tpsq_len = $redisObj->llen($handler_Q);
         * if($tpsq_len > 40){
         * $this->General->logData("/mnt/logs/new_request.txt", "Recharge request::" . $txnid . " :: Recharges queue length is greater than threshold . current size : $tpsq_len ");
         * $redisObj->incr("TPS_MARKER");
         * }
         * $redisObj->hset($TPS_REQUEST_HASH, $txnid, json_encode($pars));
         * $this->Shop->setMemcache('TPS_REQUEST_DATA_' . $txnid, json_encode($pars), 5 * 60);
         * usleep(100000);
         * $redisObj->lpush($handler_Q, $txnid);
         * }
         * else{
         * throw new Exception("Unable to connect redis");
         * }
         * }
         * catch(Exception $ex){
         */
        // $this->General->logData("/mnt/logs/new_tps_request.txt", "txn id = " . $txnid . " | exception : " . $ex->getMessage());
        $url = DOMAIN_TYPE . "://" . $_SERVER['SERVER_NAME'] . "/recharges/startTransaction";
        $pars['tranId'] = $txnid;
        $pars['encSign'] = urlencode(strtoupper(sha1(encKey . $txnid)));
        $pars['retailer_id'] = $_SESSION['Auth']['id'];
        $ret = $this->General->curl_post($url, $pars, 'POST', 1, 5);
        if($ret['success'] === false && $ret['timeout'] === true){ // txn timed out
            $this->Shop->reverseTransaction($txnid, 1, 3);
        }
        // }
        
    }

    function c2dPayment($transId, $params, $vendorId, $prodId, $logger, $C2d){
        if(isset($params['ref_id']) &&  ! empty($params['ref_id'])){
            $cash_txn_qry = "SELECT * FROM cash_payment_txn where id='" . $params['ref_id'] . "' and status=0 ";
            $cash_txn_detail = $C2d->query($cash_txn_qry);
            if(empty($cash_txn_detail)){
                $desc = array('status'=>'failure', 'errCode'=>'1001', 'description'=>'Invalid transaction Id');
            }
            elseif(isset($cash_txn_detail[0]['cash_payment_txn']['amount']) && $cash_txn_detail[0]['cash_payment_txn']['amount'] != $params['amount']){
                $desc = array('status'=>'failure', 'errCode'=>'1003', 'description'=>'Invalid transaction amount');
            }
            else{
                $processtime = date('Y-m-d H:i:s');
                $txn_update_qry = "UPDATE cash_payment_txn SET status=1,updated_time='" . $processtime . "',va_refcode='" . $transId . "' where id='" . $params['ref_id'] . "' and status=0";
                $txnstatus = $C2d->query($txn_update_qry);
                
                $logger->info("txn status : " . json_encode($txnstatus));
                
                if($txnstatus){
                    $clientId = $cash_txn_detail[0]['cash_payment_txn']['cash_client_id'];
                    $cash_client_detail_qry = "SELECT * FROM cash_payment_client WHERE id='" . $clientId . "'";
                    $cash_client_detail = $C2d->query($cash_client_detail_qry);
                    
                    $params_to_send['request_id'] = $params['ref_id'];
                    $params_to_send['client_ref_id'] = $cash_txn_detail[0]['cash_payment_txn']['id'];
                    $params_to_send['status'] = 1;
                    $params_to_send['timestamp'] = date('Y-m-d H:i:s');
                    $params_to_send['mobile'] = $params['mobileNumber'];
                    $params_to_send['amount'] = $params['amount'];
                    
                    $logger->info("params to send : " . json_encode($params_to_send));
                    
                    $client_callback_url = $cash_client_detail[0]['cash_payment_client']['callback_api'];
                    $this->General->curl_post_async($client_callback_url, $params_to_send);
                    $desc = array('status'=>'success', 'errCode'=>0, 'description'=>array('transaction_id'=>$params_to_send['client_ref_id']));
                }
            }
        }
        else{
            // --- Missing / Blank transaction
            $desc = array('status'=>'failure', 'errCode'=>'1002', 'description'=>'Missing ref_id');
            $logger->warn(" failure : " . json_encode($params_to_send));
        }
        
        $vndrId = isset($params['ref_id']) ? $params['ref_id'] : "";
        $logger->debug("out - " . json_encode($desc));
        $status = ($desc['status'] == 'success') ? TRANS_SUCCESS : (($desc['status'] == 'failure') ? TRANS_REVERSE : 0);
        
        if($desc['status'] == 'success'){
            $txnId = $desc['description']['transaction_id'];
            $logger->debug(" update param : " . $transId . "|" . $vndrId . "|" . $desc['status'] . "|31|" . $this->Shop->errors(31) . "|" . null . "|" . $prodId . "|" . $vendorId . "|" . $prodId);
            $this->update_in_vendors_activations(array('vendor_id'=>$vendorId, 'vendor_refid'=>$vndrId, 'status'=>$status, 'code'=>31, 'cause'=>addslashes($this->Shop->errors(31))), array('txn_id'=>$transId), $C2d);
            $this->log_in_vendor_message(array('va_tran_id'=>$transId, 'vendor_refid'=>$txnId, 'service_id'=>'7', 'service_vendor_id'=>$vendorId, 'internal_error_code'=>'13', 'response'=>'Successful', 'status'=>'success', 'timestamp'=>date("Y-m-d H:i:s"), 'vm_date'=>date('Y-m-d')), $C2d);
            return array('status'=>'success', 'balance'=>$createTran['balance'], 'description'=>$createTran['tranId']);
            // return array('status' => 'success', 'code' => '31', 'description' => $this->Shop->errors(31), 'tranId' => $txnId, 'pinRefNo' => '', 'operator_id' => '');
        }
        else if($desc['status'] == 'failure'){
            $err_code = $desc['errCode'];
            $msg = $desc['description'];
            $logger->debug(" update param : " . $transId . "|" . $vndrId . "|" . $desc['status'] . "|30|" . $this->Shop->errors(30) . "|" . null . "|" . $prodId . "|" . $vendorId . "|" . $prodId);
            $this->update_in_vendors_activations(array('vendor_id'=>$vendorId, 'vendor_refid'=>$vndrId, 'status'=>$status, 'code'=>30, 'cause'=>addslashes($this->Shop->errors(30))), array('txn_id'=>$transId), $C2d);
            
            $this->log_in_vendor_message(array('va_tran_id'=>$transId, 'vendor_refid'=>'', 'service_id'=>'7', 'service_vendor_id'=>$vendorId, 'internal_error_code'=>'14', 'response'=>addslashes("Error code: $err_code, " . $msg), 'status'=>'failure', 'timestamp'=>date("Y-m-d H:i:s"), 
                    'vm_date'=>date('Y-m-d')), $C2d);
            
            return array('status'=>'failure', 'code'=>$desc['errCode'], 'description'=>$desc['description']);
        }
    }

    function verifyParams($params, $mapping){
        $ret = true;
        $msg = "";
        foreach($mapping['allParams']['param'] as $param){
            $field = trim($param['field']);
            if( ! isset($params[$field])){
                $ret = false;
                $msg = $field . " not entered";
                break;
            }
            else if(strlen($params[$field]) > $param['length'] || empty($params[$field])){
                $msg = $field . ": Enter valid value";
                $ret = false;
                break;
            }
            else if($field == 'Mobile' || $field == 'PNR' || $field == 'Amount'){
                if(strlen($params[$field]) != $param['length']){
                    $msg = $field . ": Enter valid value";
                    $ret = false;
                    break;
                }
            }
        }
        if($ret){
            return array('status'=>'success','code'=>200);
        }
        else{
            return array('status'=>'failure', 'code'=>43, 'description'=>$msg);
        }
    }

    function lockTransaction($transId){
        $redisObj = $this->Shop->redis_connector();
        $this->General->logData("/mnt/logs/request.txt", "locking the txn::$transId");
        if($redisObj === false) return true;
        try{
            $ret = $redisObj->sadd("TXN_LOCKED", "$transId");
            if($ret){
                $redisObj->setex("temp$transId", 120, 1);
                return true;
            }
            
            $val = $redisObj->ttl("temp$transId");
            if($val < 30){ // If the transaction is locked from last 90 secs then clear the transaction
                $this->clearTransaction($transId);
            }
            
            return false;
        }
        catch(Exception $e){
            $this->General->logData("lockissues.txt", "Exception in locking of a txn, " . $e->getMessage());
            return true;
        }
    }

    function clearTransaction($transId){
        $txnData = $this->Shop->getMemcache("txn$transId");
        $this->General->logData("clear_txn.txt", "In clear txn function $transId");
        
        if($txnData !== false){
            $this->General->logData("clear_txn.txt", "Data is available in memcache $transId".json_encode($txnData));
            
            $vendorActObj = ClassRegistry::init('VendorsActivation');
            $vendorData = $vendorActObj->query("SELECT vendor_id,products.service_id,amount FROM vendors_activations left join products ON (products.id = product_id) WHERE txn_id = '$transId'");
            $vendor_id = $vendorData[0]['vendors_activations']['vendor_id'];
            $service_id = $vendorData[0]['products']['service_id'];
            $amount = $vendorData[0]['vendors_activations']['amount'];
            
            $last_vendor = $txnData['last_vendor']['vendor_id'];
            
            if($vendor_id != $last_vendor){
                $this->General->logData("clear_txn.txt", "$transId::Vendor id is different");
                
                $disc_comm = $this->calculateVendorCommission($amount, $txnData['last_vendor']['discount_commission']);
                $this->update_in_vendors_activations(array('vendor_id'=>$last_vendor, 'discount_commission'=>$disc_comm, 'vendor_refid'=>'', 'status'=>0), array('txn_id'=>$transId), $vendorActObj);
                $this->updateTransactionLogs($transId, $txnData['logData'], $service_id, $vendorActObj);
            }
        }
        $this->unlockTransaction($transId);
    }

    function unlockTransaction($transId){
        try{
            $redisObj = $this->Shop->redis_connector();
            $ret = $redisObj->srem("TXN_LOCKED", $transId);
        }
        catch(Exception $e){}
        
        return true;
    }

    function setModemDataVariables($transId = null, $data = null){
        $data = ($transId == null) ? $_REQUEST : $data;
        $this->ids = ( ! isset($data['trans_id'])) ? explode(",", trim($transId)) : explode(",", trim($data['trans_id']));
        
        $this->vendor = $data['vendor'];
        $this->cause = urldecode($data['cause']);
        $this->status = ( ! empty($data['error_desc']) && $data['error_desc'] == 'NA') ? 'failure' : $data['status'];
        $this->vendorIds = explode(",", $data['vendor_id']);
        $this->opr_ids = explode(",", $data['opr_id']);
        $this->products = explode(",", $data['prod_id']);
        $this->causes = explode(",", $this->cause);
        $this->timestamps = (isset($data['timestamp'])) ? explode(",", $data['timestamp']) : array();
        
        if(isset($data['processing_time'])){
            $this->process_times = explode(",", $data['processing_time']);
            $this->sim_bals = explode(",", $data['sim_balance']);
            $this->sim_nos = explode(",", $data['sim_no']);
        }
        else{
            $this->process_times = array();
            $this->sim_bals = array();
            $this->sim_nos = array();
        }
        
        $this->error = ($this->status== "failure" && empty($data['error'])) ? '' : $data['error'];
        return $data;
    }

    function adjustProcessingTime($req_timestamp, $vm_timestamp, $req_vendor, $va_vendor, $pro_time){
        if( ! empty($req_timestamp) &&  ! empty($vm_timestamp) && $req_vendor == $va_vendor){
            $timediff = strtotime($vm_timestamp) - strtotime($req_timestamp);
            $pro_time1 = $pro_time;
            $pro_time = date('Y-m-d H:i:s', strtotime($pro_time . '+ ' . $timediff . ' seconds'));
            if(time() - strtotime($pro_time) > 86400){
                $pro_time = $pro_time1;
            }
        }
        return $pro_time;
    }

    function checkForPullBack($transId, $vendorId, $transData, $status, $dbObj, $api_flag = false){
        $api = ($api_flag) ? 'API' : 'Modem';
        $dbObj = (empty($dbObj)) ? ClassRegistry::init('User') : $dbObj;
        $this->General->logData("pullback.txt","$api:: $transId:: $vendorId:: $status :: ".json_encode($transData)." Inside pullback function");
        $res = $dbObj->query("SELECT timestamp FROM vendors_messages as vm WHERE va_tran_id='$transId' AND service_vendor_id='$vendorId' AND status = '$status'");
        $this->General->logData("pullback.txt","$api:: $transId:: ".json_encode($res)." Inside pullback function2");
        
        if($status == 'failure'){ // success after failure .. can be pulled back
            if( ! empty($res) && empty($transData['va']['cc_userid']) && $res['0']['vm']['timestamp'] >= date('Y-m-d H:i:s', strtotime('-12 hours'))){
                $this->General->sendMails($api . ': Autopullback', "$transId <br/>Txn found for vendor " . $transData['vendors']['company'] . ", Previous status was " . $transData['va']['status'], array('ashish@pay1.in', 'chirutha@pay1.in', 'cc.support@pay1.in', 'backend@pay1.in'), 'mail');
                $this->Shop->autopullbackTransaction($transId, $vendorId, $dbObj);
            }
            else{
                $this->General->sendMails($api . ': Success after failure problem', "$transId <br/>Txn found for vendor " . $transData['vendors']['company'] . ", Previous status was " . $transData['va']['status'], array('ashish@pay1.in', 'chirutha@pay1.in','cc.support@pay1.in', 'backend@pay1.in'), 'mail');
                $dbObj->query("INSERT INTO trans_pullback (id,vendors_activations_id,vendor_id,status,timestamp,pullback_by,pullback_time,reported_by,date) values('','" . $transData['va']['id'] . "','" . $vendorId . "','1','" . date('Y-m-d H:i:s') . "','','','System','" . date('Y-m-d') . "')");
            }
        }
        else if($status == 'success'){ // double txn .. cannot be pulled back
            $this->General->sendMails($api . ': Success after failure problem (Double Transaction)', "$transId <br/>Txn found for vendor " . $transData['vendors']['company'] . ", Previous status was " . $transData['va']['status'], array('ashish@pay1.in', 'backend@pay1.in', 'cc.support@pay1.in',
                    'lalit.kumar@pay1.in'), 'mail');
            $dbObj->query("INSERT INTO trans_pullback (id,vendors_activations_id,vendor_id,status,timestamp,pullback_by,pullback_time,reported_by,date) values('','" . $transData['va']['id'] . "','" . $vendorId . "','1','" . date('Y-m-d H:i:s') . "','','','System','" . date('Y-m-d') . "')");
        }
    }

    function convertModemError($transId, $productId, $error){
        $errormapping = array("3"=>"6", "1"=>"37", "4"=>"5", "2"=>"47", "5"=>"47", "6"=>"6");
        
        if( ! empty($error)){
            $err_code = $error;
            
            // ----------------------tata adhock changes
            $err_flag_chk = $this->Shop->getMemcache("ERR_FLAG_" . $transId);
            if($err_flag_chk === false && in_array($productId, array(9, 10, 27)) && $err_code == 3){
                $this->Shop->setMemcache("ERR_FLAG_" . $transId, "1", 60 * 5);
                $err_code = 6;
            }
            $this->changeTataId($err_code, $product, $TransactionId);
            return $errormapping[$err_code];
        }
        
        return false;
    }
    
    function getSupplierCommission($vendorId,$sim_no,$dbObj){
        $comm = $this->Shop->getMemcache("comm_$vendorId"."_".$sim_no);
        if($comm === false){
            
            $comm = $dbObj->query("SELECT inv_supplier_operator.commission_type_formula,inv_supplier_operator.commission_type FROM devices_data left join inv_supplier_operator on (devices_data.supplier_operator_id = inv_supplier_operator.id) WHERE devices_data.vendor_id = $vendorId and devices_data.mobile='$sim_no' and devices_data.sync_date = '".date('Y-m-d')."' ");
            
            $comm_type = $comm[0]['inv_supplier_operator']['commission_type'];
            $comm = ($comm_type == 1) ? $comm[0]['inv_supplier_operator']['commission_type_formula'] : round($comm[0]['inv_supplier_operator']['commission_type_formula']*100/(100+$comm[0]['inv_supplier_operator']['commission_type_formula']),2);
            $this->Shop->setMemcache("comm_$vendorId"."_".$sim_no,$comm,3*60*60);
        }
        
        return $comm;
    }

    function updateModemTransactionStatus($transId = null, $data = null){
        $data = $this->setModemDataVariables($transId, $data);
        
        if(empty($this->ids) || !isset($this->vendor) || empty($this->vendor)) return;
        
        $this->General->logData("/mnt/logs/modemTxns.txt", date('Y-m-d H:i:s') . "::".$this->vendor.": " . json_encode($data) . json_encode($_REQUEST));
        $vendorActObj = ClassRegistry::init('VendorsActivation');
        
        $vendorActObj = $vendorActObj->getDataSource();
        $vendorActObj->begin();
        
        $i = -1;
        foreach($this->ids as $TransactionId){
            $i ++ ;
            
            $vendorId = $this->vendorIds[$i];
            $opr_id = $this->opr_ids[$i];
            $product = $this->products[$i];
            $pro_time = isset($this->process_times[$i]) ? $this->process_times[$i] : '';
            $timestamp = isset($this->timestamps[$i]) ? $this->timestamps[$i] : '';
            $sim_bal = isset($this->sim_bals[$i]) ? $this->sim_bals[$i] : 0;
            $sim_no = isset($this->sim_nos[$i]) ? $this->sim_nos[$i] : 0;
            $cause = isset($this->causes[$i]) ? $this->causes[$i] : '';
            $TransactionId = trim($TransactionId);
            $disc_comm = 0;
            
            if(!empty($sim_no))
                $disc_comm = $this->getSupplierCommission($this->vendor,$sim_no,$vendorActObj);
            
            $this->General->logData("/mnt/logs/modemTxns.txt", date('Y-m-d H:i:s') . "::".$this->vendor.": $TransactionId::1");
            try{
                if( ! $this->lockTransaction($TransactionId)) continue;
                $this->General->logData("/mnt/logs/modemTxns.txt", date('Y-m-d H:i:s') . "::".$this->vendor.": $TransactionId::10");
                $query = "SELECT va.id,va.vendor_refid,va.status,products.service_id,vendor_id,va.date,va.timestamp,product_id,date,operator_id,vm.timestamp,cc_userid,vendors.company FROM vendors_activations as va LEFT JOIN vendors ON (vendors.id = vendor_id) LEFT JOIN products ON (va.product_id=products.id) left join vendors_messages as vm ON (vm.va_tran_id=txn_id AND vm.service_vendor_id=vendor_id AND vm.status='pending') WHERE txn_id = '$TransactionId'";
                $vm = $vendorActObj->query($query);
                $this->General->logData("/mnt/logs/modemTxns.txt", date('Y-m-d H:i:s') . "::".$this->vendor.": $TransactionId::11".json_encode($vm));
                if(empty($vm)){
                    throw new Exception("No txn found for $TransactionId::query: $query");
                }
                else{
                    $vm = $vm[0];
                    $pro_time = $this->adjustProcessingTime($timestamp, $vm['vm']['timestamp'], $this->vendor, $vm['va']['vendor_id'], $pro_time);
                    
                    $this->General->logData("/mnt/logs/modemTxns.txt", date('Y-m-d H:i:s') . "::".$this->vendor.": $TransactionId::2::".json_encode($vm)."::".$this->status);
                    if($this->vendor == $vm['va']['vendor_id']){
                        if(strtolower($this->status) == 'success' && $vm['va']['status'] == TRANS_REVERSE){
                            $this->log_in_vendor_message(array('va_tran_id'=>$TransactionId, 'vendor_refid'=>$vendorId, 'service_id'=>$vm['products']['service_id'], 'service_vendor_id'=>$this->vendor, 'internal_error_code'=>13, 'response'=>addslashes($cause), 'status'=>'success', 
                                    'timestamp'=>date('Y-m-d H:i:s'), 'sim_num'=>$sim_no, 'processing_time'=>$pro_time, 'vm_date'=>date('Y-m-d')), $vendorActObj);
                            if( ! empty($pro_time)) $this->update_in_vendors_activations(array('sim_num'=>$sim_no, 'tran_processtime'=>$pro_time,'discount_commission'=>$disc_comm), array('txn_id'=>$TransactionId), $vendorActObj);
                            $this->checkForPullBack($TransactionId, $this->vendor, $vm, 'failure', $vendorActObj);
                        }
                        else if(strtolower($this->status) == 'success' &&  ! in_array($vm['va']['status'], array(TRANS_SUCCESS, TRANS_REVERSE))){
                            $this->log_in_vendor_message(array('va_tran_id'=>$TransactionId, 'vendor_refid'=>$vendorId, 'service_id'=>$vm['products']['service_id'], 'service_vendor_id'=>$this->vendor, 'internal_error_code'=>13, 'response'=>addslashes($cause), 'status'=>'success', 
                                    'timestamp'=>date('Y-m-d H:i:s'), 'sim_num'=>$sim_no, 'processing_time'=>$pro_time, 'vm_date'=>date('Y-m-d')), $vendorActObj);
                            $this->update_in_vendors_activations(array('prevStatus'=>PENDING, 'status'=>TRANS_SUCCESS, 'operator_id'=>$opr_id, 'sim_num'=>$sim_no, 'tran_processtime'=>$pro_time,'discount_commission'=>$disc_comm), array('txn_id'=>$TransactionId), $vendorActObj);
                        }
                        else if(strtolower($this->status) == 'failure' && $vm['va']['status'] == TRANS_SUCCESS){
                            $this->General->sendMails('Modem: Failure after Success problem', "Kindly check the txn and fail it manually<br/>$TransactionId <br/>Txn found for vendor " . $vm['vendors']['company'] . ", Previous status was " . $vm['va']['status'] . "<br/>Ref id: $vendorId<br/>Operator txnid: $opr_id<br/>IP: $ip", array(
                                    'ashish@pay1.in', 'chirutha@pay1.in', 'cc.support@pay1.in','backend@pay1.in'), 'mail');
                            $vadate = $vm['va']['date'];
                            $this->General->sendFailureAfterSuccessData($TransactionId,$vadate);                                    
                        }
                        else if(strtolower($this->status) == 'failure' &&  ! in_array($vm['va']['status'], array(TRANS_SUCCESS, TRANS_REVERSE))){
                            $this->log_in_vendor_message(array('va_tran_id'=>$TransactionId, 'vendor_refid'=>$vendorId, 'service_id'=>$vm['products']['service_id'], 'service_vendor_id'=>$this->vendor, 'internal_error_code'=>14, 'response'=>addslashes($cause), 'status'=>'failure', 
                                    'timestamp'=>date('Y-m-d H:i:s'), 'sim_num'=>$sim_no, 'processing_time'=>$pro_time, 'vm_date'=>date('Y-m-d')), $vendorActObj);
                            if( ! empty($pro_time)) $this->update_in_vendors_activations(array('sim_num'=>$sim_no, 'tran_processtime'=>$pro_time,'discount_commission'=>$disc_comm), array('txn_id'=>$TransactionId), $vendorActObj);
                            
                            $err_code = $this->convertModemError($TransactionId, $product, $this->error);
                            if($err_code == 5 || $err_code == 6){
                                $this->Shop->delMemcache("txnExp$TransactionId");
                            }
                            
                            $try = $this->routeTransaction($TransactionId, array(), true, true, $err_code);
                            
                        }
                        else if((strtolower($this->status) == 'pending' || strtolower($this->status) == 'inprocess') &&  ! in_array($vm['va']['status'], array(TRANS_SUCCESS, TRANS_REVERSE))){
                            if( ! empty($pro_time)) $this->update_in_vendors_activations(array('sim_num'=>$sim_no, 'tran_processtime'=>$pro_time,'discount_commission'=>$disc_comm), array('txn_id'=>$TransactionId), $vendorActObj);
                            if( ! empty($pro_time)) $this->update_in_vendors_messages(array('sim_num'=>$sim_no, 'processing_time'=>$pro_time), array('va_tran_id'=>$TransactionId, 'service_vendor_id'=>$this->vendor), $vendorActObj);
                        }
                    }
                    else if(strtolower($this->status) == 'success'){ // Different vendor is giving success status .. case of double txn
                        $this->checkForPullBack($TransactionId, $this->vendor, $vm, 'success', $vendorActObj);
                        if( ! empty($pro_time)) $this->update_in_vendors_messages(array('sim_num'=>$sim_no, 'processing_time'=>$pro_time), array('va_tran_id'=>$TransactionId, 'service_vendor_id'=>$this->vendor), $vendorActObj);
                    }
                }
                $vendorActObj->commit();
                $this->unlockTransaction($TransactionId);
            }
            catch(Exception $e){
                $this->General->logData("/mnt/logs/modemTxns.txt", $e->getMessage());
                $vendorActObj->rollback();
                $this->unlockTransaction($TransactionId);
            }
        }
    }

    function log_in_vendor_message($coldata, $dbCon = null){
        if( ! empty($coldata)){
            $cols = array_keys($coldata);
            $values = array_values($coldata);
            
            $query = "INSERT INTO vendors_messages (" . implode(",", $cols) . ") VALUES ('" . implode("','", $values) . "')";
            $dbCon = (empty($dbCon)) ? ClassRegistry::init('User') : $dbCon;
            if( ! $dbCon->query($query)){
                $this->reQuery($query);
                $this->General->sendMails("Db dependecy query failed ", "query : " . $query, array('nandan.rana@pay1.in', 'ashish@pay1.in'), 'mail');
            }
        }
    }

    /**
     * It will update $col_str columns in vendors_messages table wrt conditions defined in $cond_str
     *
     * @param type $col_str
     *            -> columns to be changed
     * @param type $cond_str
     *            -> where conditions
     * @return nothing
     */
    function update_in_vendors_activations($col_str = null, $cond_str = null, $dbCon = null){
        if( ! empty($col_str) &&  ! empty($col_str)){
            if( ! is_array($col_str)){
                $col_data = $this->covert_comma_separated_to_array($col_str);
                $col_cond = $this->covert_comma_separated_to_array($cond_str);
            }
            else{
                $col_data = $col_str;
                $col_cond = $cond_str;
            }
            
            $col_data['updated_timestamp'] = date('Y-m-d H:i:s');
            
            $ven_trans_data = array('table'=>'vendors_activations', 'type'=>'UPDATE', 'col_data'=>$col_data, 'cond_data'=>$col_cond);
            $dataArr = $ven_trans_data;
            if( ! empty($dataArr)){
                $qry = "UPDATE " . $dataArr['table'] . " SET ";
                $qry .= implode(', ', array_map(function ($v, $k){
                    $v = trim($v, "'");
                    return sprintf("`%s` = '%s'", $k, $v);
                }, $dataArr['col_data'], array_keys($dataArr['col_data'])));
                $qry .= " WHERE " . implode(' AND ', array_map(function ($v, $k){
                    $v = trim($v, "'");
                    return sprintf("`%s` = '%s'", $k, $v);
                }, $dataArr['cond_data'], array_keys($dataArr['cond_data'])));
                $dbCon = (empty($dbCon)) ? ClassRegistry::init('User') : $dbCon;
                if( ! $dbCon->query($qry)){
                    $this->reQuery($qry);
                    $this->General->sendMails("Db dependecy query failed ", "query : " . $qry, array('nandan.rana@pay1.in', 'ashish@pay1.in'), 'mail');
                }
            }
        }
    }

    function update_in_vendors_messages($col_str = null, $cond_str = null, $dbCon = null){
        if( ! empty($col_str) &&  ! empty($col_str)){
            if( ! is_array($col_str)){
                $col_data = $this->covert_comma_separated_to_array($col_str);
                $col_cond = $this->covert_comma_separated_to_array($cond_str);
            }
            else{
                $col_data = $col_str;
                $col_cond = $cond_str;
            }
            
            $ven_trans_data = array('table'=>'vendors_messages', 'type'=>'UPDATE', 'col_data'=>$col_data, 'cond_data'=>$col_cond);
            $dataArr = $ven_trans_data;
            if( ! empty($dataArr)){
                $qry = "UPDATE " . $dataArr['table'] . " SET ";
                $qry .= implode(', ', array_map(function ($v, $k){
                    $v = trim($v, "'");
                    return sprintf("`%s` = '%s'", $k, $v);
                }, $dataArr['col_data'], array_keys($dataArr['col_data'])));
                $qry .= " WHERE " . implode(' AND ', array_map(function ($v, $k){
                    $v = trim($v, "'");
                    return sprintf("`%s` = '%s'", $k, $v);
                }, $dataArr['cond_data'], array_keys($dataArr['cond_data'])));
                $dbCon = (empty($dbCon)) ? ClassRegistry::init('User') : $dbCon;
                if( ! $dbCon->query($qry)){
                    $this->reQuery($qry);
                    $this->General->sendMails("Db dependecy query failed ", "query : " . $qry, array('nandan.rana@pay1.in', 'ashish@pay1.in'), 'mail');
                }
            }
        }
    }

    function changeTataId($code, $prodId, $transId, $dbObj = NULL){
        if($code == 6 && in_array($prodId, array(9, 10, 27))){
            if($prodId == 9 || $prodId == 10) $prodId = 27;
            else $prodId = 9;
            
            $this->update_in_vendors_activations(array('product_id'=>$prodId), array('txn_id'=>$transId), $dbObj);
            
            $mdata = $this->Shop->getMemcache("txn$transId");
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/changetata.txt", " before change txnid = $transId |code = $code | mdata =" . json_encode($mdata));
            if($mdata !== false){
                $mdata['product_id'] = $prodId;
                $this->Shop->setMemcache("txn$transId", $mdata, 5 * 60);
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/changetata.txt", " after change txnid = $transId |code = $code | mdata =" . json_encode($mdata));
                return $prodId;
            }
        }
        return false;
    }

    function reQuery($qstring){
        $redisObj = $this->Shop->redis_connector();
        $redisObj->lpush("FAILED_QUERY", $qstring);
    }
}
?>
