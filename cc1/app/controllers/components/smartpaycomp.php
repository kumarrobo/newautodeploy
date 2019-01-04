<?php

class SmartpaycompComponent extends Object {

    var $components = array('General', 'Shop', 'RequestHandler','B2cextender', 'Recharge', 'Documentmanagement','Serviceintegration');

    function getUsersBankDetails($bank_user_ids) {
        $Object = ClassRegistry::init('Smartpay');
        $bank_details = array();
        $users_data = 'Select user_id,acc_name,acc_no,ifsc_code,bank_name from users WHERE user_id IN ("'.implode('","', $bank_user_ids).'") ';
        $users = $Object->query($users_data);

        if (count($users) > 0) {
            foreach ($users as $value) {
                $bank_details[$value['users']['user_id']]['acc_name'] = $value['users']['acc_name'];
                $bank_details[$value['users']['user_id']]['acc_no'] = $value['users']['acc_no'];
                $bank_details[$value['users']['user_id']]['bank_name'] = $value['users']['bank_name'];
                $bank_details[$value['users']['user_id']]['ifsc_code'] = $value['users']['ifsc_code'];
            }
        }
        return $bank_details;
    }
    function fetchUserData($smartpay_user_ids){

        $Object = ClassRegistry::init('Slaves');
        $user_details = array();
        $users_detail = 'Select user_id,id,parent_id from retailers WHERE user_id IN ("'.implode('","', $smartpay_user_ids).'") ';
        $users = $Object->query($users_detail);

        if (count($users) > 0) {
            foreach ($users as $value) {
                $user_details[$value['retailers']['user_id']]['id'] = $value['retailers']['id'];
                $user_details[$value['retailers']['user_id']]['parent_id'] = $value['retailers']['parent_id'];
            }
        }


        return $user_details;
    }
    //////////////////////////////////////////////////////////////////////
    //PARA: Date Should In YYYY-MM-DD Format
    //RESULT FORMAT:
    // '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
    // '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
    // '%m Month %d Day'                                            =>  3 Month 14 Day
    // '%d Day %h Hours'                                            =>  14 Day 11 Hours
    // '%d Day'                                                        =>  14 Days
    // '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
    // '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
    // '%h Hours                                                    =>  11 Hours
    // '%a Days                                                        =>  468 Days
    // '%TH Total Hours
    //////////////////////////////////////////////////////////////////////
    function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        if($differenceFormat == '%TH'){
            $datetime1 = strtotime($date_1);
            $datetime2 = strtotime($date_2);
            $difference = abs($datetime2 - $datetime1)/3600;
            return $difference;
        }
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);

    }

    function fetchCustomerReport($data,$page) {
        ini_set("memory_limit","1024M");
        $from_date = $data['from_txn_date'];
        $to_date = $data['to_txn_date'];
        $txn_id = $data['txn_id'];
        $user_id = $data['user_id'];
        $txn_status = $data['txn_status'];
        $service_type = $data['service_type'];
        $settlement_mode = $data['settlement_mode'];
        $txn_type = $data['txn_type'];
        $device_type = $data['device_type'];
        $vendor_id = $data['vendor_id'];
        $mobile      = $data['mobile_no'];
        $error_code      = $data['error_code'];



        $merchant_txn_id_cond = '';
        if (!empty($txn_id)) {
            $merchant_txn_id_cond = ' AND txn.merchant_txn_id IN ("' . implode('","', explode(',', $txn_id)) . '") ';
        }

        $user_id_cond = '';
        if (!empty($user_id)) {
            $user_id_cond = ' AND txn.user_id = "'.$user_id.'" ';
        }

        $txn_status_cond = '';
        if (!empty($txn_status) && in_array($txn_status,array('P','S','F'))) {
            $txn_status_cond = ' AND txn.short_status = "'.$txn_status.'" ';
        }
        $service_type_cond = '';
        if (!empty($service_type)) {
            $service_type_cond = ' AND txn.product_id IN ("'.implode('","',explode(',',$service_type)).'" )';
        }
        $settlement_mode_cond = '';
        if (isset($settlement_mode) && in_array($settlement_mode,array('0','1'))) {
            $settlement_mode_cond = ' AND txn.settlement_flag = "'.$settlement_mode.'" ';
        }
        $txn_type_cond = '';
        if (!empty($txn_type) && in_array($txn_type,array('P','S')) ) {
            $txn_type_cond = ' AND txn.settlement_status = "'.$txn_type.'" ';
        }


        $device_partners_passed = array();
        if (!empty($device_type)){
            $device_types = Configure::read('device_type');
            foreach (explode(',',$device_type) as $typeid) {
                foreach ($device_types as $service_id => $types) {
                    if(array_key_exists($typeid,$types)){
                        $device_partners_passed[$service_id][] = $typeid;

                    }
                }
            }
        }
        $device_type_cond = '';
        if( count($device_partners_passed) > 0 ){
            $device_type_cond .= ' AND (';
            $mpos_set_flag = false;
            if(array_key_exists(8,$device_partners_passed)){
                $device_type_cond .= ' mpos_txn.device_partner_id IN ("'.implode('","',$device_partners_passed[8]).'" )';
                $mpos_set_flag = true;
            }

            if(array_key_exists(10,$device_partners_passed)){
                if($mpos_set_flag){
                    $device_type_cond .= ' OR ';
                }
                $device_type_cond .= ' aeps_txn.device_partner_id IN ("'.implode('","',$device_partners_passed[10]).'" ) ';
            }
            $device_type_cond .= ' )';
        }

        $service_vendors_passed = array();
        if (!empty($vendor_id)){
            $vendors = Configure::read('vendor');
            foreach (explode(',',$vendor_id) as $vendid) {
                foreach ($vendors as $service_id => $vendor) {
                    if(array_key_exists($vendid,$vendor)){
                        $service_vendors_passed[$service_id][] = $vendid;

                    }
                }
            }
        }
        $vendor_id_cond = '';
        if( count($service_vendors_passed) > 0 ){
            $vendor_id_cond .= ' AND (';
            $mpos_set_flag = false;
            if(array_key_exists(8,$service_vendors_passed)){
                $vendor_id_cond .= ' txn.vendor_id IN ("'.implode('","',$service_vendors_passed[8]).'" )';
                $mpos_set_flag = true;
            }

            if(array_key_exists(10,$service_vendors_passed)){
                if($mpos_set_flag){
                    $vendor_id_cond .= ' OR ';
                }
                $vendor_id_cond .= ' txn.vendor_id IN ("'.implode('","',$service_vendors_passed[10]).'" ) ';
            }
            $vendor_id_cond .= ' )';
        }


        // if (!empty($device_type) && in_array($device_type,array('1','2')) ) {
        //     $device_type_cond = ' AND mpos_txn.device_partner_id = "'.$device_type.'" ';
        // } else if ( !empty($device_type) && in_array($device_type,array('3','4','5'))  ){
        //     $device_type_cond = ' AND aeps_txn.device_partner_id = "'.$device_type.'" ';
        // }

        $mobileno_cond = '';
        if(!empty($mobile)){
           $mobileno_cond = ' AND txn.mobile_no = "'.addslashes($mobile).'" ';
        }


        $txn_date_cond = '';
        if ((!empty($from_date)) && (!empty($to_date))){
            if($from_date > $to_date){
                return json_encode(array(
                    'status'=>'failure',
                    'description'=> 'Start date can not be greater than end date'
                ));
            }
            $diff = $this->dateDifference($from_date,$to_date);

            if (!empty($user_id)) {

                /*if( $diff > 9 ){
                    return json_encode(array(
                        'status'=>'failure',
                        'description'=> 'Date Range can not be greater than 10 day'
                    ));
                }*/

            } else {
                if(($diff > 6) && ($page != 'download')){
                    return json_encode(array(
                        'status'=>'failure',
                        'description'=> 'Date Range can not be greater than 7 days'
                    ));
                }
                else if(($diff > 14) && ($page == 'download')){
                    return json_encode(array(
                        'status' => 'failure',
                        'description' => 'Can not download data for more than 15 days'
                    ));
                }
            }

            $txn_date_cond = ' AND txn.created_date >= "'. $from_date .'" and txn.created_date <= "'. $to_date.'" ';
        } else {
            $txn_date_cond = ' AND txn.created_date >= "'.date('Y-m-d',strtotime('-3 days')).'" and txn.created_date <= "'. date('Y-m-d').'" ';
        }

        $error_code_cond = '';
        if(!empty($error_code)){
            $error_code_cond = ' AND txn.service_id = 10 AND txn.product_id NOT IN(78) AND txn.response_code = "'.addslashes($error_code).'" ';
        }


        // upi_txn.*
        // LEFT JOIN upi_transactions AS upi_txn ON (txn.merchant_txn_id = upi_txn.merchant_txn_id)
        $Object = ClassRegistry::init('Smartpay');
        $txn_response = 'SELECT txn.*, aeps_txn.*,mpos_txn.*
                         FROM transactions AS txn
                         LEFT JOIN aeps_transactions AS aeps_txn ON (txn.merchant_txn_id = aeps_txn.merchant_txn_id)
                         LEFT JOIN mpos_transactions AS mpos_txn ON (txn.merchant_txn_id = mpos_txn.merchant_txn_id)
                         WHERE 1=1 AND txn.method IS NULL ' . $merchant_txn_id_cond.$user_id_cond.$txn_status_cond.$service_type_cond.
                        $settlement_mode_cond.$txn_type_cond.$device_type_cond.$vendor_id_cond.$mobileno_cond.$txn_date_cond.$error_code_cond.' order by txn.created_at desc';
//        echo $txn_response;

        $transactions = $Object->query($txn_response);


        if (count($transactions) > 0) {

            $response = array();
            $bank_details = array();
            $user_details = array();
            $bank_user_ids = array_unique(array_filter(array_map(function($element) {
                if ($element['txn']['settlement_flag'] == 1) {
                    return $element['txn']['user_id'];
                }
            }, $transactions)));

            if (count($bank_user_ids) > 0) {
                $bank_details = $this->getUsersBankDetails($bank_user_ids);
            }

            $smartpay_user_ids = array_unique(array_filter(array_map(function($element) {
                if ($element['txn']['user_id'] != '') {
                       return $element['txn']['user_id'];
                }
              }, $transactions)));

            if (count($smartpay_user_ids) > 0) {
                $user_details = $this->fetchUserData($smartpay_user_ids);
            }


            foreach ($transactions as $key => $transaction) {
                $key = $transaction['txn']['merchant_txn_id'];
                $response[$key]['user_id'] = isset($transaction['txn']['user_id']) ? $transaction['txn']['user_id'] : NULL;
                $response[$key]['service_id'] = isset($transaction['txn']['service_id']) ? $transaction['txn']['service_id'] : NULL;
                $response[$key]['product_id'] = isset($transaction['txn']['product_id']) ? $transaction['txn']['product_id'] : NULL;
                $response[$key]['txn_id'] = isset($transaction['txn']['merchant_txn_id']) ? $transaction['txn']['merchant_txn_id'] : NULL;
                $response[$key]['shop_txn_id'] = isset($transaction['txn']['shop_transaction_id']) ? $transaction['txn']['shop_transaction_id'] : NULL;
                $response[$key]['incentive_shop_txn_id'] = isset($transaction['txn']['inc_shop_txn_id']) ? $transaction['txn']['inc_shop_txn_id'] : NULL;
                $response[$key]['txn_amount'] = isset($transaction['txn']['amount']) ? $transaction['txn']['amount'] : NULL;
                $response[$key]['txn_time'] = isset($transaction['txn']['created_at']) ? $transaction['txn']['created_at'] : NULL; //['format('Y-m-d H:i:s') : NULL;
                $response[$key]['txn_status'] = isset($transaction['txn']['short_status']) ? $transaction['txn']['short_status'] : NULL;
                $response[$key]['settlement_flag'] = isset($transaction['txn']['settlement_flag']) ? $transaction['txn']['settlement_flag'] : NULL;
                $response[$key]['status_description'] = isset($transaction['txn']['status_description']) ? $transaction['txn']['status_description'] : NULL;



                $response[$key]['complaint_status'] = isset($transaction['txn']['complaint_status']) ? $transaction['txn']['complaint_status'] : 'P';
                $response[$key]['settlement_status'] = isset($transaction['txn']['settlement_status']) ? $transaction['txn']['settlement_status'] : 'P';
                $response[$key]['settled_at'] = isset($transaction['txn']['retailer_settlement_at']) ? $transaction['txn']['retailer_settlement_at'] : NULL;



                if (isset($transaction['txn']['shop_response'])) {

                    $response[$key]['wallet_details'] = json_decode($transaction['txn']['shop_response'], TRUE);
                    if (isset($response[$key]['wallet_details']['status'])) {
                        unset($response[$key]['wallet_details']['status']);
                    }
                    if (isset($response[$key]['wallet_details']['shop_transaction_id'])) {
                        unset($response[$key]['wallet_details']['shop_transaction_id']);
                    }
                    //                    $product = config('custom.productCharges.' . $transaction->product_id);
                    if (!array_key_exists('type', $response[$key]['wallet_details'])) {
                        $response[$key]['wallet_details']['type'] = 'cr';
                    }
                }

                if (isset($transaction['txn']['shop_inc_response'])) {
                    $response[$key]['incentive_details'] = json_decode($transaction['txn']['shop_inc_response'], TRUE);
                    if (isset($response[$key]['incentive_details']['status'])) {
                        unset($response[$key]['incentive_details']['status']);
                    }
                    if (isset($response[$key]['incentive_details']['shop_transaction_id'])) {
                        unset($response[$key]['incentive_details']['shop_transaction_id']);
                    }
                    //                    $product = config('custom.productCharges.' . $transaction->product_id);
                    if (!array_key_exists('type', $response[$key]['incentive_details'])) {
                        $response[$key]['incentive_details']['type'] = 'cr';
                    }
                }



                $response[$key]['rrn'] = NULL;
                $response[$key]['card_no'] = NULL;
                $response[$key]['acc_no'] = NULL;
                $response[$key]['vpa'] = NULL;

                //                $response[$key]['complaint_status'] = NULL;
                //                $response[$key]['settlement_status'] = NULL;

                $response[$key]['receipt_url'] = NULL;
                $response[$key]['auth_code'] = NULL;
                $response[$key]['payment_card_type'] = NULL;
                $response[$key]['tid'] = NULL;

                switch ($transaction['txn']['service_id']) {
                    case 9 : // config('custom.UPI.service_id'):
                        // $other_details = UpiTransaction::where('merchant_txn_id',$transaction['upi_txn']['merchant_txn_id'])->first();
//                        $other_details = $transaction['upi_txn'];
//
//                        if (count($other_details) > 0) {
//                            $response[$key]['vpa'] = isset($other_details['payer_vpa']) ? $other_details['payer_vpa'] : NULL; // Need to know
//                        }
                        break;
                    case 8 :// config('custom.MPOS.service_id'):
                        // $other_details = MposTransaction::where('merchant_txn_id',$transaction->merchant_txn_id)->first();
                        $other_details = $transaction['mpos_txn'];

                        if (count($other_details) > 0) {
                            $response[$key]['card_no'] = isset($other_details['formattedPan']) ? $other_details['formattedPan'] : NULL;
                            if ($response[$key]['txn_status'] == 'S') {
                                $response[$key]['receipt_url'] = isset($other_details['customerReceiptUrl']) ? $other_details['customerReceiptUrl'] : SMARTPAY_WEB_URL.'/r/' . sha1($transaction['txn']['merchant_txn_id']).'/'.$transaction['txn']['created_date'];
                            }
                            $response[$key]['auth_code'] = isset($other_details['authCode']) ? $other_details['authCode'] : NULL;
                            $response[$key]['payment_card_type'] = isset($other_details['paymentCardType']) ? $other_details['paymentCardType'] : NULL;
                            $response[$key]['tid'] = isset($other_details['tid']) ? $other_details['tid'] : NULL;
                            $response[$key]['paymentCardBrand'] = isset($other_details['paymentCardBrand']) ? $other_details['paymentCardBrand'] : NULL;
                            $response[$key]['rrn'] = isset($other_details['rrNumber']) ? $other_details['rrNumber'] : NULL;
                        }
                        break;
                    case 10 ://config('custom.AEPS.service_id'):

                        $other_details = $transaction['aeps_txn'];

                        if( count($other_details) > 0 ){
                            $response[$key]['card_no'] = isset($other_details['aadhar_no']) ? $other_details['aadhar_no'] : NULL;
                            if ($response[$key]['txn_status'] == 'S') {
                                $response[$key]['receipt_url'] = SMARTPAY_WEB_URL.'/r/aeps/' . sha1($transaction['txn']['merchant_txn_id']).'/'.$transaction['txn']['created_date'];
                            }
                        }
                        if (isset($transaction['txn']['response'])) {
                            $rbl_res = $response[$key]['response'] = json_decode($transaction['txn']['response'], TRUE);

                            $rrn = NULL;
                            if( isset($rbl_res[37]) && !empty($rbl_res[37]) ) {
                                $rrn = $rbl_res[37];
                            } else if( isset($rbl_res['object']['rrn']) && !empty($rbl_res['object']['rrn']) ){
                                $rrn = $rbl_res['object']['rrn'];
                            }
                            $response[$key]['rrn'] = $rrn;
                        }

                        break;
                    default:
                        break;
                }

                $device_types = Configure::read('device_type');
                $response[$key]['device_type'] = isset($other_details['device_partner_id']) ? $device_types[$transaction['txn']['service_id']][$other_details['device_partner_id']] : NULL;
                $vendors = Configure::read('vendor');
                $response[$key]['vendor_id'] = isset($transaction['txn']['vendor_id']) ? $vendors[$transaction['txn']['service_id']][$transaction['txn']['vendor_id']] : NULL;
                // $plans = Configure::read('plans');

                $service_plans = $this->Serviceintegration->getServicePlans();
                $service_plans = json_decode($service_plans,true);
                $plans = array();
                foreach($service_plans as $service_id => $plans_temp){
                    foreach($plans_temp as $plan_key => $plan){
                        $plans[$service_id][$plan_key] = $plan['plan_name'];
                    }
                }

                $response[$key]['plan'] = isset($other_details['plan']) ? $plans[$transaction['txn']['service_id']][$other_details['plan']] : NULL;

                $response[$key]['mobile_no'] = isset($transaction['txn']['mobile_no']) ? $transaction['txn']['mobile_no'] : NULL;
                $response[$key]['response_code'] = isset($transaction['txn']['response_code']) ? $transaction['txn']['response_code'] : NULL;
                $response[$key]['bank_settlement_status'] = isset($transaction['txn']['bank_settlement_status']) ? $transaction['txn']['bank_settlement_status'] : NULL;
                $response[$key]['bank_settled_at'] = isset($transaction['txn']['bank_settlement_at']) ? $transaction['txn']['bank_settlement_at'] : NULL;
                $response[$key]['utr_id'] = isset($transaction['txn']['utr_id']) ? $transaction['txn']['utr_id'] : NULL;
                $response[$key]['utr_date'] = isset($transaction['txn']['utr_date']) ? $transaction['txn']['utr_date'] : NULL;
                $response[$key]['utr_comments'] = isset($transaction['txn']['utr_comments']) ? $transaction['txn']['utr_comments'] : NULL;
                $response[$key]['settled_amount'] = isset($transaction['txn']['settled_amount']) ? $transaction['txn']['settled_amount'] : NULL;

                if ($response[$key]['settlement_flag'] == 1 && ($response[$key]['user_id'])) {
                    // $bank_details = getUser($response[$key]['user_id            echo '<pre>' . __FILE__ . '-' . __LINE__ . '<br>';

                    if (count($bank_details) > 0) {
                        $response[$key]['bank_details']['acc_name'] = $bank_details[$response[$key]['user_id']]['acc_name'];
                        $response[$key]['bank_details']['acc_no'] = $bank_details[$response[$key]['user_id']]['acc_no'];
                        $response[$key]['bank_details']['bank_name'] = $bank_details[$response[$key]['user_id']]['bank_name'];
                        $response[$key]['bank_details']['ifsc_code'] = $bank_details[$response[$key]['user_id']]['ifsc_code'];
                    }
                }
                if (count($user_details) > 0){
                    $response[$key]['user_details']['retailer_id'] = $user_details[$response[$key]['user_id']]['id'];
                    $response[$key]['user_details']['distributor_id'] = $user_details[$response[$key]['user_id']]['parent_id'];
                }
            }
            return json_encode(array(
                'status'=>'success',
                'transactions'=> $response
            ));
        }
        else {
            return json_encode(array(
                'status'=>'failure',
                'description'=> 'No transactions found'
            ));
        }
    }

    function fetchDisputeData($data,$page){

        if(!empty($data['error_code']) && !empty($data['from_txn_date']) && !empty($data['to_txn_date'])){
            $error_code = $data['error_code'];
            $from_date = $data['from_txn_date'];
            $to_date = $data['to_txn_date'];
            $txn_date_cond = '';
            if ((!empty($from_date)) && (!empty($to_date))){
                if($from_date > $to_date){
                    return json_encode(array(
                        'status'=>'failure',
                        'description'=> 'Start date can not be greater than end date'
                    ));
                }
                $diff = $this->dateDifference($from_date,$to_date);

                if(($diff > 6) && ($page != 'download')){
                    return json_encode(array(
                        'status'=>'failure',
                        'description'=> 'Date Range can not be greater than 7 days'
                    ));
                }else if(($diff > 14) && ($page == 'download')){
                    return json_encode(array(
                        'status' => 'failure',
                        'description' => 'Can not download data for more than 15 days'
                    ));
                }

                $txn_date_cond = ' AND txn.created_date >= "'. $from_date .'" and txn.created_date <= "'. $to_date.'" ';
            } else {
                $txn_date_cond = ' AND txn.created_date >= "'.date('Y-m-d',strtotime('-3 days')).'" and txn.created_date <= "'. date('Y-m-d').'" ';
            }
            $error_code_cond = '';
            $error_code_cond = ' AND txn.service_id = 10 AND txn.product_id NOT IN(78) AND txn.response_code = "'.addslashes($error_code).'" ';

            $Object = ClassRegistry::init('Smartpay');
            $txn_response = 'SELECT txn.*, aeps_txn.*
                            FROM transactions AS txn
                            LEFT JOIN aeps_transactions AS aeps_txn ON (txn.merchant_txn_id = aeps_txn.merchant_txn_id)
                            WHERE 1=1 AND txn.method IS NULL '.$txn_date_cond.$error_code_cond.' order by txn.created_at desc';
            // echo $txn_response;
            $transactions = $Object->query($txn_response);
            if (count($transactions) > 0) {

                // echo '<pre>';
                // print_r($transactions);exit;
                $dispute_data = array();
                foreach ($transactions as $transaction) {

                    $pay1_txn_id = $transaction['txn']['merchant_txn_id'];
                    $rbl_res = json_decode($transaction['txn']['response'],true);
                    $dispute_data[$pay1_txn_id]['participant_id'] = 'RNU';
                    $trxn_type_mapping = array(
                        73 => '04',
                        74 => '01',
                        78 => '05'
                    );
                    $dispute_data[$pay1_txn_id]['trxn_type'] = ( array_key_exists($transaction['txn']['product_id'],$trxn_type_mapping) ) ? $trxn_type_mapping[$transaction['txn']['product_id']] : '--';
                    $dispute_data[$pay1_txn_id]['device_id'] = $transaction['aeps_txn']['rbl_device_id'];

                    $rrn = '--';
                    if( isset($rbl_res[37]) && !empty($rbl_res[37]) ) {
                        $rrn = $rbl_res[37];
                    } else if( isset($rbl_res['object']['rrn']) && !empty($rbl_res['object']['rrn']) ){
                        $rrn = $rbl_res['object']['rrn'];
                    }
                    $dispute_data[$pay1_txn_id]['rrn'] = $rrn;
                    $dispute_data[$pay1_txn_id]['pan_number'] = '--';
                    $dispute_data[$pay1_txn_id]['system_trace_audit_number'] = '000000'.$transaction['aeps_txn']['stan'];
                    $dispute_data[$pay1_txn_id]['trxn_date'] = $transaction['txn']['created_date'];;
                    $dispute_data[$pay1_txn_id]['trxn_amount'] = $transaction['txn']['amount'];;
                    $dispute_data[$pay1_txn_id]['timing'] = date('H:i:s',strtotime($transaction['txn']['created_at']));
                    $dispute_data[$pay1_txn_id]['stan'] = $transaction['aeps_txn']['stan'];
                    $dispute_data[$pay1_txn_id]['npci_response_code'] = '--';

                }


                return json_encode(array(
                    'status'=>'success',
                    'data'=> $dispute_data
                ));
            } else {
                return json_encode(array(
                    'status'=>'failure',
                    'description'=> 'No transactions found'
                ));
            }
        }
        return json_encode(array(
            'status'=>'failure',
            'description'=> 'No transactions found'
        ));
    }

}
