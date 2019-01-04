<?php

class BridgeComponent extends Object {

    var $components = array('General','Shop','Auth','Documentmanagement','Servicemanagement');

    function errorDescription($errCode){
        $errors = array('101'=>'Server address is wrong', '102'=>'Token is missing', '103'=>'Token is not authentic', '104'=>'Method does not exists', '105'=>'Sorry, you don\'t have enough balance in your wallet', '106'=>'Some mysql technical problem','107'=>'Duplicate Txn Id', '108'=>'Wrong input parameters', '109'=>'Txn not found', '110'=>'Txn already reversed', '111'=>'User does not exists', '112'=>'Txn found but amount not settled', '113'=>'No data found','114'=>'Username/Password is invalid','115'=>'User is not active. You cannot login right now','116'=>'Transaction already settled','117'=>'Cannot settle the txn after same day','118'=>'Txn cannot be reversed','119'=>'User is already activated for this service','120'=>'You are already deactivated for this service. Kindly contact customer care','121'=>'Sorry, You dont have any credit limits','122'=>'Sorry, You have lesser credit limit left','123'=>'Sorry, you have not used this much credit limit earlier','124'=>'Dear Retailer, Your demo is blocked. Kindly contact your distributor','125'=>'Amount cannot be refunded as cancellation charges are greater than refund amount');
        return $errors[$errCode];
    }



    function requestValidation($params){
        Configure::load('bridge');

        $configs = Configure::read('secrets');

        if(empty($params['server']) ||  ! isset($configs[$params['server']])){
            return array('status'=>'failure', 'errCode'=>'101', 'description'=>$this->errorDescription('101'));
        }

        if(empty($params['token'])){
            return array('status'=>'failure', 'errCode'=>'102', 'description'=>$this->errorDescription('102'));
        }

//        if($params['method'] != 'authenticate') {
//            if(empty($params['user_id'])){
//                return array('status'=>'failure', 'errCode'=>'111', 'description'=>$this->errorDescription('111'));
//            }
//        }
        if (!in_array($params['method'], array('authenticate', 'cancellationRefundApi'))) {
            if (empty($params['user_id'])) {
                return array('status' => 'failure', 'errCode' => '111', 'description' => $this->errorDescription('111'));
            }
        } else if (strtolower($params['server']) != 'travel_irctc' && $params['method'] == 'cancellationRefundApi') {
            if (empty($params['user_id'])) {
                return array('status' => 'failure', 'errCode' => '111', 'description' => $this->errorDescription('111'));
            }
        }
        $service_ids = $configs[$params['server']]['service_ids'];
        if(!empty($params['service_id']) && !in_array($params['service_id'],explode(",",$service_ids))){
            return array('status'=>'failure', 'errCode'=>'108', 'description'=>$this->errorDescription('108'));
        }

        $token = $params['token'];
        unset($params['token']);
        $secret = $configs[$params['server']]['secret'];
        if( ! $this->General->tokenValidate($params, $secret, $token)){
            return array('status'=>'failure', 'errCode'=>'103', 'description'=>$this->errorDescription('103'));
        }

        return $this->methodValidation($params);
    }

    function methodValidation($params){
        if($params['method']== 'authenticate'){
            if(empty($params['mobile'])) {
                return array('status'=>'failure', 'errCode'=>'114', 'description'=>$this->errorDescription('114'));
            } else if($this->General->numberValidate($params['mobile']) == '1') {
                return array('status'=>'failure', 'errCode'=>'114', 'description'=>$this->errorDescription('114'));
            } else if(strlen($params['mobile']) != 10) {
                return array('status'=>'failure', 'errCode'=>'114', 'description'=>$this->errorDescription('114'));
            }

            if(empty($params['password'])) {
                return array('status'=>'failure', 'errCode'=>'114', 'description'=>$this->errorDescription('114'));
            }
        }
        return array('status'=>'success', 'errCode'=>'', 'description'=>'success');
    }

    /*function activateSmartPay($params,$userObj){
        $user_id = $params['user_id'];

        if(empty($params['service_id'])){ // smartpay registration
            $shop_name = $params['shop_name'];
            $shop_type = $params['shop_type'];
            $alternate_contact = $params['alternate_contact'];
            if(in_array(RETAILER,$params['groups'])){
                //add above data in retailers / unverified retailers table
                $shop_data = $this->Shop->getShopData($user_id,RETAILER);
                $userObj->query("UPDATE retailers SET shopname='".addslashes($shop_name)."', alternate_number='".addslashes($alternate_contact)."', shop_type_value='".addslashes($shop_type)."' WHERE user_id = $user_id");

                // IMP DATA ADDED : START
                $imp_update_data = array(
                    'shopname' => addslashes($shop_name),
                    'alternate_number' => addslashes($alternate_contact)
                );
                $response = $this->Shop->updateUserLabelData($user_id,$imp_update_data,$this->Session->read('Auth.User.id'),0);
                // IMP DATA ADDED : END/

                $userObj->query("UPDATE unverified_retailers SET shopname='".addslashes($shop_name)."', shop_type_value='".addslashes($shop_type)."' WHERE retailer_id = ".$shop_data['id']);
            }
        }
        else{
            $service_id = $params['service_id'];
            $param1 = (isset($params['param1'])) ? $params['param1'] : '';
            $param2 = (isset($params['param2'])) ? $params['param2'] : '';
            $kit_flag = $params['kit_flag'];
            $service_flag = $params['service_flag'];

            $services = $userObj->query("SELECT * FROM users_services WHERE user_id = '$user_id' AND service_id = '$service_id'");
            if(empty($services)){
                $userObj->query("INSERT INTO users_services (user_id,service_id,param1,param2,kit_flag,service_flag) VALUES ('$user_id','$service_id','".addslashes($param1)."','".addslashes($param2)."',$kit_flag,$service_flag)");
            }
            else {
                $query = "";
                if(!empty($param1)) $query .= ", param1='".addslashes($param1)."'";
                if(!empty($param2)) $query .= ", param2='".addslashes($param2)."'";
                $userObj->query("UPDATE users_services SET kit_flag='$kit_flag',service_flag='$service_flag' $query WHERE user_id='$user_id' AND service_id = '$service_id'");
            }

        }


        return array('status'=>'success');
    }*/

    function activate($params,$userObj){
        $user_id = $params['user_id'];
        $service_id = $params['service_id'];
        $data = json_decode($params['data'],true);

        $service = $userObj->query("SELECT * FROM services WHERE id = $service_id AND activation_type = 0");

        if( count($service) > 0 ){
            $services = $userObj->query("SELECT * FROM users_services WHERE user_id = '$user_id' AND service_id = '$service_id'");
            if( ( $service[0]['services']['registration_type'] == 3 && empty($services) ) || ( $service['services']['registration_type'] == 2 && !empty($services) && $services[0]['users_services']['kit_flag'] == 1 && $services[0]['users_services']['service_flag'] == 3 ) ){
                $service_plan = $userObj->query("SELECT id,plan_key FROM service_plans WHERE service_id = '$service_id' LIMIT 1");
                $service_plan_id = '';
                if( count($service_plan) > 0 ){
                    $service_plan_id = $service_plan[0]['service_plans']['id'];
                    $data['plan'] = $service_plan[0]['service_plans']['plan_key'];
                }

                $param1 = '';
                $service_fields = $this->Servicemanagement->getServiceFields();
                $service_fields = json_decode($service_fields,true);
                foreach ($data as $field_key => $value) {

                    $validation_rules = explode('|',$service_fields[$service_id][$field_key]['validation']);
                    if( count($validation_rules) > 0 && in_array('unique',$validation_rules) && !empty($value) ){
                        $param1 = $value;
                        break;
                    }
                }

                if( $service['services']['registration_type'] == 2 && !empty($services) && $services[0]['users_services']['kit_flag'] == 1 && $services[0]['users_services']['service_flag'] == 3 ){
                    $services = $userObj->query('UPDATE users_services'
                    . ' SET service_flag=1,'
                    . ' service_plan_id='.$service_plan_id.','
                    . ' param1="'.$param1.'",'
                    . ' created_on="'.date('Y-m-d H:i:s').'",'
                    . ' params=\''.addslashes(json_encode($data)).'\''
                    . ' WHERE user_id='.$user_id.''
                    . ' AND service_id='.$service_id);


                } else {
                    $services = $userObj->query("INSERT INTO users_services (user_id,service_id,param1,params,kit_flag,service_flag,created_on,service_plan_id) VALUES ('$user_id','$service_id','$param1',\"".addslashes(json_encode($data))."\",1,1,'".date('Y-m-d H:i:s')."','".$service_plan_id."')");
                }


                if($services){
                    $service_request_data = array(
                        'kit_purchase_date' => date("Y-m-d"),
                        'kit_purchase_timestamp' => date("Y-m-d h:i:s"),
                        'service_request_date' => date('Y-m-d'),
                        'service_request_timestamp' => date('Y-m-d H:i:s'),
                        'ret_user_id' => $user_id,
                        'source' => 'auto_activation_api',
                        'service_id' => $service_id
                    );
                    $service_request_res = $this->Servicemanagement->addServiceRequestLog($service_request_data);
                    if($service_request_res){
                        return array('status'=>'success','msg' => 'Service Activated Successfully');
                    } else {
                        return array('status'=>'failure', 'description'=>'Something went wrong. Please try again.');
                    }
                }
            }
            else if($services[0]['users_services']['service_flag'] == 1){
                return array('status'=>'failure','errCode'=>'119', 'description'=>$this->errorDescription('119'));
            }
            else if($services[0]['users_services']['service_flag'] == 0){
                return array('status'=>'failure','errCode'=>'120', 'description'=>$this->errorDescription('120'));
            }
        } else {
            return array('status'=>'failure', 'description'=>'This service is not eligible for auto activation');
        }

    }

    /*function activateMicrofinance($params,$userObj){
        $user_id = $params['user_id'];
        $service_id = $params['service_id'];
        $lender_id = $params['param1'];
        $borrower_id = $params['param2'];

        $services = $userObj->query("SELECT * FROM users_services WHERE user_id = '$user_id' AND service_id = '$service_id'");

        if(empty($services)){
            $services = $userObj->query("INSERT INTO users_services (user_id,service_id,param1,param2) VALUES ('$user_id','$service_id','$lender_id','$borrower_id')");
            $this->Shop->addUserGroup($user_id,BORROWER);
        }
        else {
            if(!empty($lender_id)){
                $userObj->query("UPDATE users_services SET param1='$lender_id' WHERE user_id='$user_id' AND service_id = '$service_id'");
            }

            if(!empty($borrower_id)){
                $userObj->query("UPDATE users_services SET param2='$borrower_id' WHERE user_id='$user_id' AND service_id = '$service_id'");
                $this->Shop->addUserGroup($user_id,BORROWER);
            }
        }

        return array('status'=>'success');
    }*/



    function userCreditApi($params,$dataSource){
        $user_id = $params['user_id'];
        $service_id = $params['service_id'];
        $data = $dataSource->query("SELECT params FROM users_services WHERE user_id = '$user_id' AND service_id='$service_id'");
        $insert = 0;
        if(empty($data)){
            $params = array('limit'=>100,'used'=>0);
            $data[0]['users_services']['params'] = json_encode($params);
            $insert = 1;
        }

        if(empty($data)){
            return array('status'=>'failure','errCode'=>'121', 'description'=>$this->errorDescription('121'));
        }
        else {
            $params = json_decode($data[0]['users_services']['params'],true);
            return array('status'=>'success','limit'=>$params['limit'],'used'=>$params['used'],'insert_flag'=>$insert);
        }
    }

    function utilizeUserCreditApi($params,$dataSource){
        $user_id = $params['user_id'];
        $service_id = $params['service_id'];
        $amount = $params['amount'];
        $txn_id = $params['txn_id'];

        $credits = $this->userCreditApi($params,$dataSource);
        if($credits['status']=='failure') return $credits;

        if($credits['limit'] < $amount){
            return array('status'=>'failure','errCode'=>'122', 'description'=>$this->errorDescription('122'));
        }
        else {
            $curl_ret = $this->General->curl_post(PRAGATICAP_URL."/createSmartBuyLoan",array('amount'=>$amount,'user_id'=>$user_id,'lender_user_id'=>55,'ref_num'=>$txn_id));
            $out = json_decode($curl_ret['output'],true);
            if($out['status'] == 'success'){
                $final['limit']= $credits['limit'] - $amount;
                $final['used'] = $credits['used'] + $amount;
                if($credits['insert_flag']){
                    $dataSource->query("INSERT INTO users_services (user_id,service_id,params) VALUES ($user_id,$service_id,'".json_encode($final)."')");
                }
                else {
                    $dataSource->query("UPDATE users_services SET params ='".json_encode($final)."' WHERE user_id = $user_id AND service_id = $service_id");
                }
                return array('status'=>'success','limit'=>$final['limit'],'used'=>$final['used']);
            }
            else {
                return array('status'=>'failure','description'=>'Credit cannot be allotted right now');
            }

        }

    }


    function addUserCreditApi($params,$dataSource){
        $user_id = $params['user_id'];
        $service_id = $params['loan_service_id'];
        $amount = $params['amount'];

        $params['service_id'] = $service_id;

        $credits = $this->userCreditApi($params,$dataSource);
        if($credits['status']=='failure') return $credits;

        if($credits['used'] < $amount){
            return array('status'=>'failure','errCode'=>'123', 'description'=>$this->errorDescription('123'));
        }
        else {
            $final['limit']= $credits['limit'] + $amount;
            $final['used'] = $credits['used'] - $amount;

            $dataSource->query("UPDATE users_services SET params ='".json_encode($final)."' WHERE user_id = $user_id AND service_id = $service_id");
            return array('status'=>'success','limit'=>$final['limit'],'used'=>$final['used']);
        }
    }

    function checkWalletTxn($txn_id,$server,$dataSource){
        $data = $dataSource->query("SELECT * FROM wallets_transactions WHERE txn_id = '".$txn_id."' AND server='$server'");
        if(empty($data)){
           return array('status'=>'failure','errCode'=>'109', 'description'=>$this->errorDescription('109'));
        }
        else {
            return array('status'=>'success','data'=>$data[0]['wallets_transactions']);
        }
    }

    function addWalletTxn($params,$dataSource){
        $shop_transaction_id=time().rand();
        $source = -1;
        if(isset($params['source']) && !empty($params['source'])){
            if(trim($params['source']) == 'android') $source = 3;
            else if(trim($params['source']) == 'web') $source = 9;
        }

        $settle_flag = (isset($params['settle_flag'])) ? $params['settle_flag'] : 1;
        $vendor_id = (isset($params['vendor_id'])) ? $params['vendor_id'] : 0;
        $vendor_refid = (isset($params['vendor_refid'])) ? $params['vendor_refid'] : 0;
        $txn_type = isset($params['txn_type']) ? $params['txn_type'] : 'product';

        if($txn_type != 'product'){
            return array('status'=>'success','data'=>array());
        }

        if($settle_flag <= 1){
            $settlement_mode = ($settle_flag == 1) ? 0 : 1;
            if(!$dataSource->query("INSERT INTO wallets_transactions (txn_id,shop_transaction_id,server,user_id,product_id,settlement_mode,amount,cr_db,service_charge,commission,tax,description,date,created,source,vendor_id,vendor_refid) VALUES ('".$params['txn_id']."','".$shop_transaction_id."','".$params['server']."','".$params['user_id']."','".$params['product_id']."','".$settlement_mode."','".$params['amount']."','".$params['type']."','".$params['service_charge']."','".$params['commission']."','".$params['tax']."','".$params['description']."','".date('Y-m-d')."','".date('Y-m-d H:i:s')."','$source','$vendor_id','$vendor_refid')")){
                return array('status'=>'failure','errCode'=>'107', 'description'=>$this->errorDescription('107'));
            }
            $status = $this->checkWalletTxn($params['txn_id'],$params['server'],$dataSource);
        }
        else {//partial settlement
            $status = $this->checkWalletTxn($params['txn_id'],$params['server'],$dataSource);
            if($status['status'] == 'failure') return $status;
            else if($status['data']['status'] == '2') {
                return array('status'=>'failure','errCode'=>'110', 'description'=>$this->errorDescription('110'));
            }
            else if($settle_flag == 2 && date('Y-m-d') > date('Y-m-d',strtotime($status['data']['date'].' +1 day'))){
                return array('status'=>'failure','errCode'=>'117', 'description'=>$this->errorDescription('117'));
            }
            else {
                $amt_to_be_settled = $status['data']['amt_remaining_settlement'];
                if($amt_to_be_settled < $params['amount']){
                    return array('status'=>'failure','errCode'=>'116', 'description'=>$this->errorDescription('116'));
                }
                if($settle_flag == 3 && $amt_to_be_settled == 0){
                    return array('status'=>'failure','errCode'=>'116', 'description'=>$this->errorDescription('116'));
                }
            }
        }

        return $status;
    }

    function updateWalletTxn($txn_id,$shop_txnid,$amt_settled,$settle_flag,$server,$vendor_amount,$vendor_id,$commission,$service_charge,$tax,$vendor_comm,$vendor_sc,$dataSource){
        if($settle_flag == 0 || $settle_flag == 1){
            if($settle_flag == 0){
                $amt_remaining_settlement = $amt_settled;
            }
            else if($settle_flag == 1){
                $amt_remaining_settlement = 0;
            }
            $dataSource->query("UPDATE wallets_transactions SET shop_transaction_id = '$shop_txnid',amount_settled = '$amt_settled',amt_remaining_settlement='$amt_remaining_settlement',status=1,vendor_settled_amount='$vendor_amount',vendor_id='$vendor_id',commission='$commission',service_charge='$service_charge',tax='$tax',vendor_commission='$vendor_comm',vendor_service_charge='$vendor_sc' WHERE txn_id = '$txn_id' AND server='$server'");
        }
        else {
            $dataSource->query("UPDATE wallets_transactions SET amt_remaining_settlement=amt_remaining_settlement-'$amt_settled' WHERE txn_id = '$txn_id' AND server='$server' AND amt_remaining_settlement >= $amt_settled");
        }
    }


    function getDefaultVendor($service_id,$product_id,$dataSource){
        $vendor_id = $dataSource->query("SELECT pv.id FROM product_vendors as pv LEFT JOIN product_vendor_margins as pvm ON (pvm.vendor_id = pv.id) WHERE pvm.product_id = $product_id AND pv.service_id = $service_id ORDER by pvm.id asc Limit 1");
        return $vendor_id[0]['pv']['id'];
    }

    function calculateVendorSettlement($vendor_id,$product_id,$amount,$type,$dataSource){
        $vendor_data = $dataSource->query("SELECT pvm.margin,products.earning_type FROM product_vendor_margins as pvm LEFT JOIN products ON (products.id = pvm.product_id) WHERE pvm.product_id = $product_id AND pvm.vendor_id = $vendor_id");
        $vendor_margin = json_decode($vendor_data[0]['pvm']['margin'],true);

        $tax = 0; $service_charge=0;$commission=0;
        $denom = "1." . SERVICE_TAX_PERCENT;

        if($product_id == 84){
            $comm = $this->Shop->calculateCommissionDMT($amount,$vendor_margin,$vendor_id);
            $service_charge = $comm['service_charge'];
            $commission = $comm['commission'];
            $tax = $this->Shop->calculateTDS($commission);
            $commission = $commission*$denom;
        }
        else {
            $comm = $this->Shop->calculateCommission($amount,$vendor_margin);
            $comm = $comm['comm'];

            if($vendor_data[0]['products']['earning_type']==2){//service charge
                $service_charge = $comm;
            }
            else if($vendor_data[0]['products']['earning_type']==1){//commission
                $commission = $comm;
                $tax = $this->Shop->calculateTDS($commission/$denom);
            }
            else if($vendor_data[0]['products']['earning_type']==0){//discount .. no tds
                $commission = $comm;
            }

        }

        $vendor_amount = $commission - $service_charge - $tax;
        $vendor_amount = ($type == 'cr') ? ($amount + $vendor_amount) : ($amount - $vendor_amount);

        return array('vendor_amount'=>$vendor_amount,'vendor_commission'=>$commission,'vendor_service_charge'=>$service_charge);
    }

    function makeWalletEntries($params,$wallet_data,$dataSource){
        $amount = floatval($params['amount']);
        $vendor_amount = isset($params['vendor_amount']) ? $params['vendor_amount']: 0;
        $vendor_id = isset($params['vendor_id']) ? $params['vendor_id']: 0;
        $type = $params['type']; //cr / db
        $product_id = $params['product_id'];
        $service_charge = floatval($params['service_charge']);
        $commission = floatval($params['commission']);
        $tax = floatval($params['tax']); //tax in amount
        $user_id = $params['user_id'];
        $service_id = $params['service_id'];
        $description = $params['description'];
        $txn_id = $params['txn_id'];
        $settle_flag = isset($params['settle_flag']) ? $params['settle_flag']: 1;
        $txn_type = isset($params['txn_type']) ? $params['txn_type'] : 'product';
        $ref_id = isset($params['ref_id']) ? $params['ref_id'] : '';
        $vendor_service_charge = isset($params['vendor_service_charge']) ? $params['vendor_service_charge']: 0;
        $vendor_commission = isset($params['vendor_commission']) ? $params['vendor_commission']: 0;

        if($txn_type == 'incentive' && in_array(DISTRIBUTOR,$params['groups'])){
            $tax = $amount*TDS_PERCENT/100;
        }

        if($amount < 0 || $vendor_amount < 0){
            return array('status'=>'failure','errCode'=>'108','description'=>$this->errorDescription('108'));
        }

        if($txn_type == 'product' && $settle_flag < 2){

            $comm_data = $this->Shop->commissionCalculation($user_id,$service_id,$product_id,$amount,$vendor_amount,$vendor_service_charge,$vendor_commission);
            if(!empty($comm_data)){
                $commission = $comm_data['commission'];
                $service_charge = $comm_data['service_charge'];
                $tax = $comm_data['tax'];
            }

            if(empty($vendor_id)){
                $vendor_id = $this->getDefaultVendor($service_id,$product_id,$dataSource);
            }

            if(empty($vendor_amount)){
                $vendor_amount_arr = $this->calculateVendorSettlement($vendor_id,$product_id,$amount,$type,$dataSource);
                $vendor_amount = $vendor_amount_arr['vendor_amount'];
                $vendor_commission = $vendor_amount_arr['vendor_commission'];
                $vendor_service_charge = $vendor_amount_arr['vendor_service_charge'];
            }
            else {
                $vendor_amount = $comm_data['vendor_amount'];
                $vendor_commission = $comm_data['vendor_commission'];
                $vendor_service_charge = $comm_data['vendor_service_charge'];
            }

        }

        if($settle_flag == 2 || $settle_flag == 3){
            if($txn_type != 'product'){
                return array('status'=>'failure','errCode'=>'108','description'=>$this->errorDescription('108'));
            }

            if($settle_flag == 3){
                $amount = $wallet_data['amt_remaining_settlement'];
            }
        }

        $other_amount = 0;
        if($service_charge > 0){
            $other_amount -= $service_charge;
        }
        if($commission > 0){
            $other_amount += $commission;
        }
        if($tax > 0){
            $other_amount -= $tax;
        }

        $opening_bal = $this->Shop->getBalance($user_id,$dataSource);

        if($type == 'cr'){
            $amt_to_be_settled = $amount + $other_amount;
            $type_s = 'add';
            $txn_type = (($txn_type == 'incentive') ? REFUND : CREDIT_NOTE);
            $closing_bal = $opening_bal + $amount;
        }
        else if($type == 'db'){
            $amt_to_be_settled = $amount - $other_amount;
            $type_s = 'subtract';
            $txn_type = (($txn_type == 'rental') ? RENTAL : DEBIT_NOTE);
            $closing_bal = $opening_bal - $amount;
        }
        else {
            return array('status'=>'failure','errCode'=>'108','description'=>$this->errorDescription('108'));
        }


        if($settle_flag == 0){//no settlement
            $closing_bal = 0;
            $opening_bal = 0;
            $type_flag = 1;
        }
        else if($settle_flag == 1 || $settle_flag == 2){//1 means full settlement in wallet, 2 means partial settlement in wallet
            $allow_negative = 0;
            if( array_key_exists('allow_negative',$params) && ($params['allow_negative'] == 1) ){
                $allow_negative = $params['allow_negative'];
            }
            $clos_bal = $this->Shop->shopBalanceUpdate($amt_to_be_settled,$type_s,$user_id,null,$dataSource,1,$allow_negative);

            if($clos_bal === false) return array('status'=>'failure','errCode'=>'105','description'=>$this->errorDescription('105'));

            $type_flag = ($settle_flag == 1) ? 0 : 2;
        }

        if($settle_flag != 3){ // 3 means final settlement in bank
            if($txn_type != REFUND){
                $trans_id = $this->Shop->shopTransactionUpdate($txn_type, $amount, $user_id, $product_id, $service_id,null, $type_flag, $description, $opening_bal,$closing_bal,null,null,$dataSource);
            } else {
                $trans_id = $this->Shop->shopTransactionUpdate($txn_type, $amount, $user_id, null, $service_id,null, null, $description, $opening_bal,$closing_bal,null,null, $dataSource);
            }
            if($trans_id === false) return array('status'=>'failure','errCode'=>'106','description'=>'Transaction entry is not created');

            if($service_charge > 0){
                if($settle_flag == 0){//no settlement
                    $closing_bal = 0;
                    $opening_bal = 0;
                }
                else {
                    $opening_bal = $closing_bal;
                    $closing_bal = $closing_bal-$service_charge;
                }
                $description = "Service Charges - " . $trans_id;
                $ret = $this->Shop->shopTransactionUpdate(SERVICECHARGES, $service_charge, $user_id, $trans_id, $service_id, null, 0,$description, $opening_bal, $closing_bal, null, null, $dataSource);
                if($ret === false) return array('status'=>'failure','errCode'=>'106','description'=>'Service charge entry not created');

            }


            if($commission > 0){
                if($settle_flag == 0){//no settlement
                    $closing_bal = 0;
                    $opening_bal = 0;
                }
                else {
                    $opening_bal = $closing_bal;
                    $closing_bal = $closing_bal + $commission;
                }
                $description = "Commission - " . $trans_id;
                $ret = $this->Shop->shopTransactionUpdate(COMMISSION, $commission, $user_id, $trans_id, $service_id, null, $type_flag, $description, $opening_bal, $closing_bal, null, null,$dataSource);
                if($ret === false) return array('status'=>'failure','errCode'=>'106','description'=>'Commission entry not created');

            }

            if($tax > 0){
                if($settle_flag == 0){//no settlement
                    $closing_bal = 0;
                    $opening_bal = 0;
                }
                else {
                    $opening_bal = $closing_bal;
                    $closing_bal = $closing_bal - $tax;
                }
                $description = "TDS deducted - " .$trans_id;
                $ret = $this->Shop->shopTransactionUpdate(TDS, $tax, $user_id, $trans_id, $service_id, null, $type_flag, $description, $opening_bal , $closing_bal, null,null, $dataSource);
                if($ret === false) return array('status'=>'failure','errCode'=>'106','description'=>'TDS entry not created');
            }

        }

        $reference_id = (empty($trans_id)) ? $ref_id : $trans_id;
        $this->makeSettlementEntry($params,$amt_to_be_settled,$reference_id,$dataSource);

        return array('status'=>'success','closing'=>$closing_bal, 'shop_transaction_id'=>$reference_id, 'amt_settled'=>$amt_to_be_settled, 'type'=>$type,'vendor_amount'=>$vendor_amount,'vendor_id'=>$vendor_id,'commission'=>$commission,'service_charge'=>$service_charge,'tax'=>$tax,'vendor_commission'=>$vendor_commission,'vendor_service_charge'=>$vendor_service_charge);
    }

    function makeSettlementEntry($params,$amt_to_be_settled,$reference_id,$dataSource){
        $settle_flag = isset($params['settle_flag']) ? $params['settle_flag']: 1;
        if($settle_flag == 2 || $settle_flag == 3){
            $settlement_mode = ($settle_flag == 2) ? 0 : 1;
            $dataSource->query("INSERT INTO settlement_history (txn_id,server,settlement_mode,settlement_ref_id,amount_settled,date,created) VALUES ('" . $params['txn_id'] . "','".$params['server']."',$settlement_mode,'$reference_id',$amt_to_be_settled,'" . date('Y-m-d') . "','".date('Y-m-d H:i:s')."')");
        }
    }

    function getSettlementEntries($params,$dataSource){
        $data = $dataSource->query("SELECT * FROM settlement_history WHERE txn_id = '".$params['txn_id']."' AND server='".$params['server']."'");
        if(empty($data)){
            return array('status'=>'failure','errCode'=>'109', 'description'=>$this->errorDescription('109'));
        }
        else {
            return array('status'=>'success','data'=>$data);
        }
    }

    function reverseWalletEntries($params,$wallet_data,$dataSource,$internal_flag=0){
        $shop_txn_id = $params['shop_transaction_id'];
        $user_id = $params['user_id'];
        $source = $wallet_data['source'];
        $reversal_charges = (isset($params['reversal_charges'])) ? $params['reversal_charges'] : 0;
        $service_charge = $wallet_data['service_charge'];
        $commission = $wallet_data['commission'];
        //$vendor_refid = (isset($params['reversal_charges'])) ? $params['reversal_charges'] : 0;

        $data = $dataSource->query("SELECT st.amount,st.target_id,st.confirm_flag,st.type,st.source_opening,st.source_closing,st.date,products.earning_type,products.earning_type_flag,products.expected_earning_margin FROM shop_transactions as st LEFT JOIN products ON (products.id=st.target_id) WHERE st.id = $shop_txn_id");
        if(empty($data)){
            $data = $dataSource->query("SELECT st.amount,st.target_id,st.confirm_flag,st.type,st.source_opening,st.source_closing,st.date,products.earning_type,products.earning_type_flag,products.expected_earning_margin FROM shop_transactions_logs as st LEFT JOIN products ON (products.id=st.target_id) WHERE st.id = $shop_txn_id");
        }

        if(empty($data)){
            return array('status'=>'failure','errCode'=>'109','description'=>$this->errorDescription('109'));
        }
        else if(!$this->Shop->lockReverseTransaction($shop_txn_id,$dataSource)){
            return array('status'=>'failure','errCode'=>'110','description'=>$this->errorDescription('110'));
        }
        else if($data[0]['st']['type'] == CREDIT_NOTE && $params['service_id'] != 11){
            return array('status'=>'failure','errCode'=>'118','description'=>$this->errorDescription('118'));
        }
        else if($data[0]['st']['date'] < date('Y-m-d',strtotime('-90 days')) && $internal_flag == 0){
            return array('status'=>'failure','errCode'=>'118','description'=>$this->errorDescription('118'));
        }
        else {
           $product_id = $data[0]['st']['target_id'];
           $txn_type = $data[0]['st']['type'];
           $amt_to_reverse = $wallet_data['amount_settled'] - $wallet_data['amt_remaining_settlement'];

           if($reversal_charges > 0 && $amt_to_reverse - $reversal_charges < 0){
               return array('status'=>'failure','errCode'=>'118','description'=>$this->errorDescription('118'));
           }

           if($data[0]['st']['type'] == CREDIT_NOTE){
               $closing_bal = $this->Shop->shopBalanceUpdate($amt_to_reverse - $reversal_charges,'subtract',$user_id,null,$dataSource,1,0);
               $opening_bal = $closing_bal + $amt_to_reverse - $reversal_charges;
               $closing_bal_rev = $opening_bal - $amt_to_reverse;
           }
           else if($data[0]['st']['type'] == DEBIT_NOTE){
               $closing_bal = $this->Shop->shopBalanceUpdate($amt_to_reverse - $reversal_charges,'add',$user_id,null,$dataSource,1,0);
               $opening_bal = $closing_bal - $amt_to_reverse + $reversal_charges;
               $closing_bal_rev = $opening_bal + $amt_to_reverse;
           }

           $description = "Reverse Entry: $shop_txn_id (Order id: ".$wallet_data['txn_id'].")";
           if($closing_bal === false) return array('status'=>'failure','errCode'=>'105','description'=>$this->errorDescription('105'));

           $trans_id = $this->Shop->shopTransactionUpdate(VOID_TXN, $amt_to_reverse, $user_id, $shop_txn_id, $params['service_id'],null, null, $description, $opening_bal, $closing_bal_rev, null,null,$dataSource);
           if($trans_id === false) return array('status'=>'failure','errCode'=>'106','description'=>'Transaction entry is not created');

           if($data[0]['st']['type'] == DEBIT_NOTE && $reversal_charges > 0){
               $description = "Reversal charges: $trans_id";
               $this->Shop->shopTransactionUpdate(SERVICECHARGES, $reversal_charges, $user_id, $trans_id, $params['service_id'],null, 1, $description, $closing_bal_rev, $closing_bal, null,null,$dataSource);
           }

           $dataSource->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE id = '$shop_txn_id'");
           $dataSource->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE target_id = '$shop_txn_id'");

            $amt = $data[0]['st']['amount'];
            $earning = $amt - $amt_to_reverse;
            $earning = ($earning > 0) ? $earning : (0-$earning);
            $earning_type = $data['0']['products']['earning_type'];
            $earning_type_flag = $data['0']['products']['earning_type_flag'];

            if($earning_type == 2){
                $percent_pos = strpos($data['0']['products']['expected_earning_margin'], '%');
                $percent = substr($data['0']['products']['expected_earning_margin'], 0, $percent_pos);
                $expected_earning = $percent_pos !== false?($amt*$percent)/100:$data['0']['products']['expected_earning_margin'];
            }else{
                $expected_earning = $earning;
            }


            if($date == $data[0]['st']['date']){
                $dataSource->query("UPDATE retailer_earning_logs SET txn_count=txn_count-1,amount=amount-$amt,earning=earning-$earning,expected_earning=expected_earning-$expected_earning,closing_amt=closing_amt-$amt,closing_txn_count=closing_txn_count-1,commission=commission-$commission,service_charge=service_charge-$service_charge WHERE service_id = '".$params['service_id']."' AND ret_user_id = '$user_id' AND date='".$data[0]['st']['date']."' AND txn_type = '$earning_type' AND txn_type_flag = '$earning_type_flag' and api_flag='$source' and type='$txn_type'");
            }
            else {
                $dataSource->query("UPDATE retailer_earning_logs SET txn_count=txn_count-1,amount=amount-$amt,earning=earning-$earning,expected_earning=expected_earning-$expected_earning,commission=commission-$commission,service_charge=service_charge-$service_charge WHERE service_id = '".$params['service_id']."' AND ret_user_id = '$user_id' AND date='".$data[0]['st']['date']."' AND txn_type = '$earning_type' AND txn_type_flag = '$earning_type_flag' and api_flag='$source' and type='$txn_type'");
            }

           $settlement_data = $this->getSettlementEntries($params,$dataSource);
           if($settlement_data['status'] == 'success'){
               foreach($settlement_data['data'] as $set_data){
                   if($set_data['settlement_history']['settlement_mode'] == 0){
                       $dataSource->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE id = '".$set_data['settlement_history']['settlement_ref_id']."'");
                   }
               }
           }

           if(isset($params['vendor_refid']) && !empty($params['vendor_refid'])){
               $dataSource->query("UPDATE wallets_transactions SET vendor_refid='".$params['vendor_refid']."',status=2,reversal_date='".date('Y-m-d')."' WHERE txn_id = '".$params['txn_id']."' AND server='".$params['server']."'");
           }
           else {
               $dataSource->query("UPDATE wallets_transactions SET status=2,reversal_date='".date('Y-m-d')."' WHERE txn_id = '".$params['txn_id']."' AND server='".$params['server']."'");
           }

           //$this->refundCredit($params,$dataSource,$wallet_data);
           return array('status'=>'success','closing'=>$closing_bal, 'shop_transaction_id'=>$trans_id, 'amt_settled'=>$amt_to_reverse);
        }

    }

    function refundCredit($params,$dataSource,$wallet_data){
        $service_id = $params['service_id'];
        $user_id = $params['user_id'];
        $order_id = $wallet_data['txn_id'];
        if($service_id == 13){//shop1
            $credits = $this->userCreditApi($params,$dataSource);
            if($credits['status']=='success' && $credits['used'] > 0){
                $curl_ret = $this->General->curl_post(PRAGATICAP_URL."/refundSmartBuyLoan",array('order_id'=>$order_id));
                $out = json_decode($curl_ret['output'],true);
                if($out['status'] == 'success' && $out['amount'] <= $credits['used']){
                    $amount = $out['amount'];
                    $final = array();
                    $final['limit']= $credits['limit'] + $amount;
                    $final['used'] = $credits['used'] - $amount;

                    $dataSource->query("UPDATE users_services SET params ='".json_encode($final)."' WHERE user_id = $user_id AND service_id = $service_id");

                }
            }
        }
        return array('status'=>'success');
    }

    function profileApi($params,$dataSource){
        $user_id = $params['user_id'];
        $user_info = $dataSource->query("SELECT users.id, users.mobile, users.created FROM users where users.id = '$user_id'");

        /** IMP DATA ADDED : START**/
        $temp = $this->Shop->getUserLabelData($user_id,2,0);
        $imp_data = $temp[$user_id];
        /** IMP DATA ADDED : END**/

        $name   = $imp_data['imp']['name'];
        $shop   = $imp_data['imp']['shop_est_name'];

        $basic_profile = array('mobile'=>$user_info[0]['users']['mobile'], 'name'=>$imp_data['imp']['name'], 'joined_on'=>$user_info[0]['users']['created'],'shop'=>$imp_data['imp']['shop_est_name']);

        $doc_info = $this->Documentmanagement->checkDocs($user_id,$params['service_id']);
        $basic_profile['name'] = $doc_info['textual']['name'];

        $user_services = $dataSource->query("SELECT us.service_id, us.kit_flag, us.service_flag,us.params,us.param1,sp.* FROM users_services us JOIN service_plans sp ON(us.service_plan_id = sp.id) WHERE us.user_id = '$user_id'");
        $service_info = array();
        foreach( $user_services as $us ) {
            $service_info[$us['us']['service_id']] = array('kit'=>$us['us']['kit_flag'], 'service_flag'=>$us['us']['service_flag'],'params'=>json_decode($us['us']['params'],true),'agent_id'=>$us['us']['param1'],'plan'=>$us['sp']);
        }

        return array('status'=>'success','basic_profile_info'=>$basic_profile, 'doc_info'=>$doc_info,'service_info'=>$service_info);
    }

    function checkUserExist($params,$dataSource){
        $mobile   = $params['mobile'];
        $password = $this->Auth->password($params['password']);
        $groups   = array(DISTRIBUTOR, RETAILER);

        $sqlQuery = "SELECT users.id,users.balance,group_concat(user_groups.group_id) as groupids FROM users INNER JOIN user_groups ON (users.id =user_groups.user_id) WHERE mobile = '" . $mobile . "' AND password = '" . $password . "' AND user_groups.group_id IN (" . implode(",", $groups) . ") group by user_groups.user_id";

        $data = $dataSource->query($sqlQuery);

        if(empty($data)):
            return array('status'=>'failure', 'errCode'=>'114', 'description'=>$this->errorDescription(114));
        else:
            return $data[0];
        endif;
    }

    function setMasterDataInMemcache(){
        $memcache = $this->Shop->memcacheConnection(MEMCACHE_MASTER);
        $usrObj = ClassRegistry::init('User');
        $services_data = $usrObj->query("SELECT * FROM services");
        $product_data = $usrObj->query("SELECT * FROM products");

        $prodData = array();
        $servData = array();

        foreach($product_data as $prod){
            $product_id = $prod['products']['id'];
            $prodData[$product_id] = $prod['products'];
        }

        foreach($services_data as $service){
            $service_id = $service['services']['id'];
            $servData[$service_id] = $service['services'];
        }

        $memcache->set('productsData',serialize($prodData),false,4*60*60);
        $memcache->set('servicesData',serialize($servData),false,4*60*60);
    }

    function validateUser($params,$dataSource){
        $mobile = $params['mobile'];
        $pwd = $params['pwd'];

        $pwd_hash = $this->Auth->password($pwd);

        $data = $dataSource->query("SELECT * FROM users WHERE mobile='$mobile' AND password = '$pwd_hash'");

        if(empty($data)){
            return array('status'=>'failure','errCode'=>'111','description'=>$this->errorDescription('111'));
        }
        else {
            return array('status'=>'success');
        }
    }

    function getUserData($params,$dataSource = null){
        $user_id = $params['user_id'];
        $date = (empty($params['date'])) ? date('Y-m-d',strtotime('-1 days')) : $params['date'];
        $dataSource = is_null($dataSource) ? ClassRegistry::init('Slaves') : $dataSource;

        $userData = $this->General->getUserDataFromId($user_id);

        $temp = $this->Shop->getUserLabelData($user_id,2,0);
        $imp_data = $temp[$user_id];
        /** IMP DATA ADDED : END**/

        $userData['name'] = $imp_data['imp']['name'];
        $userData['shop'] = $imp_data['imp']['shop_est_name'];

        if(empty($userData)){
            return array('status'=>'failure','errCode'=>'113','description'=>$this->errorDescription('113'));
        }
        else {
            return array('status'=>'success', 'sale'=>$sale, 'earning'=>$earning, 'data' => $userData);
        }
    }

    function getUserCommission($params,$dataSource){
        $user_id = $params['user_id'];
        $service_id = $params['service_id'];

        $userData = $this->General->getUserDataFromId($user_id);
        if(in_array(RETAILER,$params['groups'])){
            $plan = $dataSource->query("SELECT spp.ret_params,products.earning_type from users_services left join service_plans on (service_plans.id = users_services.service_plan_id) left join service_product_plans as spp on (spp.service_plan_id = service_plans.id) left join products on (products.id = spp.product_id) WHERE user_id = '$user_id' AND service_plans.service_id = '$service_id'");
            $plan_params = json_decode($plan[0]['spp']['ret_params'],true);

            $commission_per = $plan_params['0-0']['margin'];
            $commission_per = rtrim($commission_per,'%');
            $min = $plan_params['0-0']['min'];
            $max = $plan_params['0-0']['max'];

            $commission = array('percent'=>$commission_per,'min'=>$min,'max'=>$max);
            return array('status'=>'success', 'commission'=>$commission);
        }
        else {
            return array('status'=>'failure','errCode'=>'111','description'=>$this->errorDescription('111'));
        }

    }

    function kitCharge($amt,$userId,$serviceId,$description,$dataSource=null,$discount=null,$settle_flag = '1'){

        if($settle_flag == '0'){
            $closing_bal = 0;
            $opening_bal = 0;
            $type_flag = 1;
        } else {
            $closing_bal = $this->Shop->shopBalanceUpdate($amt,'subtract',$userId,null,$dataSource,1,0);
            if($closing_bal === false){
                return array('status'=>'failure','errCode'=>'105','description'=>$this->errorDescription('105'));
            }
            $type_flag = 0;
        }

        $res = $this->Shop->shopTransactionUpdate(KITCHARGE,$amt,$userId,null,$serviceId,$discount,$type_flag,$description,$closing_bal+$amt,$closing_bal,null,null,$dataSource);
        if($res){
            return array(
                'status' => 'success',
                'shop_txn_id' => $res
            );
        } else {
            return array(
                'status'=>'failure',
                'description'=>'Something went wrong. Please try again'
            );
        }

    }
    function walletApi($params,$dataSource){

            $shop_data = $this->Shop->getShopData($params['user_id'],RETAILER);
            if($params['type'] == 'db' && isset($shop_data['block_flag']) && $shop_data['block_flag'] == 2){//fully blocked
                return array('status'=>'failure','errCode'=>'124','description'=>$this->errorDescription('124'));
            }

            $settle_flag = isset($params['settle_flag']) ? $params['settle_flag']: 1;

            if( in_array($settle_flag,array(0,1)) ){
                $services = $dataSource->query("SELECT services.registration_type,users_services.service_flag FROM services left join users_services on (users_services.user_id=".$params['user_id']." AND service_id = services.id) WHERE services.id = ".$params['service_id']);
                if(!empty($services) && $services[0]['services']['registration_type'] != 1 && $services[0]['users_services']['service_flag'] != 1){
                    return array('status'=>'failure','errCode'=>'120','description'=>$this->errorDescription('120'));
                }
            }

            if($params['service_id'] == 12 && $params['amount'] == 4 && $params['product_id'] == 84){
                $params['amount'] = 0;
                $params['product_id']= 160;
            }

            $return = $this->addWalletTxn($params,$dataSource);
            if($return['status'] == 'failure'){
                return $return;
            }
            $return = $this->makeWalletEntries($params,$return['data'],$dataSource);
            if($return['status'] == 'failure'){
                return $return;
            }


            $this->updateWalletTxn($params['txn_id'],$return['shop_transaction_id'],$return['amt_settled'],$settle_flag,$params['server'],$return['vendor_amount'],$return['vendor_id'],$return['commission'],$return['service_charge'],$return['tax'],$return['vendor_commission'],$return['vendor_service_charge'],$dataSource);

            return $return;
    }

    function updateVendorRefId($params,$dataSource){
        if(isset($params['vendor_refid']) && !empty($params['vendor_refid'])){
            $vendor_refid = $params['vendor_refid'];
            $txn_id = $params['txn_id'];
            $server = $params['server'];
            $dataSource->query("UPDATE wallets_transactions SET vendor_refid='$vendor_refid' WHERE txn_id='$txn_id' AND server='$server'");
            return array('status'=>'success','errCode'=>'0','description'=>'Updated successfully');
        }
        else {
            return array('status'=>'failure','errCode'=>'108','description'=>$this->errorDescription('108'));
        }
    }

    function cancellationRefundApi($params,$dataSource){
        $return = $this->checkWalletTxn($params['txn_id'],$params['server'],$dataSource);
        if($return['status'] == 'failure'){
            return $return;
        }

        $return = $this->refundAgainstTxn($params,$return['data'],$dataSource);
        if($return['status'] == 'failure'){
            return $return;
        }
        return $return;
    }
    function refundAgainstTxn($params,$wallet_data,$dataSource){
        $shop_txn_id = $wallet_data['shop_transaction_id'];
        $order_id = $wallet_data['txn_id'];
        $user_id = (isset($params['user_id']) && !empty($params['user_id'])) ? $params['user_id'] : $wallet_data['user_id'];
        $source = $wallet_data['source'];
        $cancellation_charges = (isset($params['cancel_charges']))?$params['cancel_charges']:0;
        $vendor_cancellation_charges = (isset($params['vendor_cancel_charges']))?$params['vendor_cancel_charges']:0;
        $cancellation_charges = $cancellation_charges + $vendor_cancellation_charges;

        if(isset($params['refund_amount'])){
            $refund_amt = $params['refund_amount'];
            $sale_refund = $refund_amt;
        }
        else {
            $refund_amt = $this->calculateRefundAmount($params);
            $sale_refund = $params['sale_refund'];
        }

        $data = $dataSource->query("SELECT st.amount,st.target_id,st.confirm_flag,st.type,st.source_opening,st.source_closing,st.date FROM shop_transactions as st WHERE st.id = $shop_txn_id");
        $amount = $wallet_data['amount'];
        if(empty($data)){
            return array('status'=>'failure','errCode'=>'109','description'=>$this->errorDescription('109'));
        }
        else if($data[0]['st']['type'] == CREDIT_NOTE){
            return array('status'=>'failure','errCode'=>'118','description'=>$this->errorDescription('118'));
        }
        else if($data[0]['st']['date'] < date('Y-m-d',strtotime('-90 days'))){
            return array('status'=>'failure','errCode'=>'118','description'=>$this->errorDescription('118'));
        }
        else if( ($sale_refund+$wallet_data['cancel_refunded_amount']) > $amount){
            return array('status'=>'failure','errCode'=>'118','description'=>$this->errorDescription('118'));
        }
        else {

            $product_id = $data[0]['st']['target_id'];
            $txn_type = $data[0]['st']['type'];
            $amt_to_reverse = $refund_amt;

            if($amt_to_reverse < -1)
                return array('status'=>'failure','errCode'=>'125','description'=>$this->errorDescription('125'));

            if($data[0]['st']['type'] == DEBIT_NOTE){
                $closing_bal = $this->Shop->shopBalanceUpdate($amt_to_reverse,'add',$user_id,null,$dataSource,1,0);
                $opening_bal = $closing_bal - $amt_to_reverse;
            }

            if(isset($params['description']) && !empty($params['description'])){
                $description = $params['description'];
            }
            else {
                $description = "Txn Cancellation Refund Entry: Booking ID $order_id";
            }

           if($closing_bal === false) return array('status'=>'failure','errCode'=>'105','description'=>$this->errorDescription('105'));

           $trans_id = $this->Shop->shopTransactionUpdate(TXN_CANCEL_REFUND, $amt_to_reverse+$cancellation_charges, $user_id, $shop_txn_id, $params['service_id'],null, null, $description, $opening_bal, $opening_bal+$amt_to_reverse+$cancellation_charges, null,null,$dataSource);

           if($trans_id === false) return array('status'=>'failure','errCode'=>'106','description'=>'Transaction entry is not created');

           if($cancellation_charges > 0){
               if(isset($params['description']) && !empty($params['description'])){
                   $description = "Cancellation charges on (".$params['description'].")";
               }
               else {
                   $description="Cancellation charges against booking $order_id";
               }
               $this->Shop->shopTransactionUpdate(SERVICECHARGES, $cancellation_charges, $user_id, $trans_id, $params['service_id'],null, null, $description, $opening_bal+$amt_to_reverse+$cancellation_charges, $closing_bal, null,null,$dataSource);
           }
           $this->makePartialCancellationEntry($params,$wallet_data,$dataSource);

           $dataSource->query("UPDATE wallets_transactions SET cancel_refunded_amount = cancel_refunded_amount+$sale_refund WHERE txn_id = '".$params['txn_id']."' AND server='".$params['server']."'");
           return array('status'=>'success','closing'=>$closing_bal, 'shop_transaction_id'=>$trans_id, 'amt_settled'=>$amt_to_reverse);
        }

    }

    function makePartialCancellationEntry($params,$wallet_data,$dataSource){
        $vendor_cancellation_charges = (isset($params['vendor_cancel_charges']))?$params['vendor_cancel_charges']:0;
        $commission_refund= (isset($params['commission_refund']))?$params['commission_refund']:0;
        $vendor_commission_refund= (isset($params['vendor_commission_refund']))?$params['vendor_commission_refund']:0;
        $sale_refund= (isset($params['sale_refund']))?$params['sale_refund']:$params['refund_amount'];
        $pay1_cancellation_charges = (isset($params['cancel_charges']))?$params['cancel_charges']:0;
        $product_cancellation_charges = (isset($params['product_cancel_charges']))?$params['product_cancel_charges']:0;
        if(isset($params['refund_amount'])){
            $amount_settled = $params['refund_amount'];
        }
        else {
            $amount_settled= $this->calculateRefundAmount($params);
        }

        $shop_txn_id = $wallet_data['shop_transaction_id'];
        $order_id = $wallet_data['txn_id'];
        $server = $wallet_data['server'];
        $product_id = $wallet_data['product_id'];

        $dataSource->query("INSERT INTO wallet_partial_cancellations (txn_id,server,product_id,amount_refunded,amount_settled,cancellation_charges,vendor_cancellation_charges,product_cancellation_charges,commission_refund,vendor_commission_refund,date,created) VALUES ('" . $order_id. "','".$server."','$product_id','$sale_refund','$amount_settled','$pay1_cancellation_charges','$vendor_cancellation_charges','$product_cancellation_charges','$commission_refund','$vendor_commission_refund','" . date('Y-m-d') . "','".date('Y-m-d H:i:s')."')");
    }

    function calculateRefundAmount($params){
        $vendor_cancellation_charges = (isset($params['vendor_cancel_charges']))?$params['vendor_cancel_charges']:0;
        $commission_refund= (isset($params['commission_refund']))?$params['commission_refund']:0;
        $vendor_commission_refund= (isset($params['vendor_commission_refund']))?$params['vendor_commission_refund']:0;
        $sale_refund= (isset($params['sale_refund']))?$params['sale_refund']:0;
        $pay1_cancellation_charges = (isset($params['cancel_charges']))?$params['cancel_charges']:0;
        $product_cancellation_charges = (isset($params['product_cancel_charges']))?$params['product_cancel_charges']:0;

        $tax = $this->Shop->calculateTDS($commission_refund);

        $refund_amount = $sale_refund - $vendor_cancellation_charges - $pay1_cancellation_charges - $product_cancellation_charges - $commission_refund + $tax;
        return $refund_amount;
    }

    function getLabelInfo($params,$dataSource){
        $user_id = $params['user_id'];
        $labels = explode(',', $params['labels']);

        App::import('vendor', 'S3', array('file' => 'S3.php'));
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $config_id = $this->Documentmanagement->getLabelConfig('key','id');
        $config_type = $this->Documentmanagement->getLabelConfig('key','type');

        $label_ids = array();
        foreach ($labels as $label){
            $label_ids[] = $config_id[$label];
        }
        $label_ids = array_filter($label_ids);
        $label_info = $this->Documentmanagement->userStatusCheck($user_id,$label_ids,1,$dataSource);

        $response = array();
        $response['user_id'] = $user_id;
        foreach ($labels as $label){
            $response['labels'][$label] = '';
        }

        if(!empty($label_info)){
            foreach ($label_info[$user_id] as $label_id=>$data){
                $response['user_id'] = $user_id;
                $label_key = array_search($label_id,$config_id);
                if($config_type[$label_key] == 1){
                    $response['labels'][$label_key] = array_map(function($value) use($s3){
                                    return $s3->aws_s3_link(awsAccessKey,awsSecretKey,docbucket,'/'.$value,time() - strtotime(date('Y-m-d'))+600);},
                                    explode(',',$data['description'])
                                    );
                }else{
                    $response['labels'][$label_key] = $data['description'];
                }
            }
        }

        return array('status'=>'success','data'=>$response);
    }

    function updateLabelStatus($params,$dataSource){
        $user_id = $params['user_id'];
        $config_id = $this->Documentmanagement->getLabelConfig('key','id');
        $label_ids = array();
        $failed_flag = array();
        foreach($params['labels'] as $key=>$val){
            $label_id = $config_id[$key];
            $labelcheck = $this->Documentmanagement->checkIfLabelExists($user_id,$label_id,$dataSource);
            if($labelcheck['status'] == 'success'){
                $response = $this->Documentmanagement->updateLabelStatus($user_id,$label_id,$val,$dataSource);
                if($response['status'] == 'failure'){
                    $failed_flag[] = $key;
                }
            }else{
                $failed_flag[] = $key;
            }
        }

        if(count($failed_flag) > 0){
            if(count($failed_flag) == count($params['labels'])){
                return array('status'=>'failure');
            }
        }

        return array('status'=>'success','failed_labels' => implode(',',$failed_flag));
    }
}

?>
