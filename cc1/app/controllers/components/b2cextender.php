<?php

class B2cextenderComponent extends Object {

    var $components = array('General','Shop');
    
    function missed_call_dummy($number) {
        $dummy_stub = array();
        $dummy_stub['vendors_activations'] = array();
        $dummy_stub['vendors_activations']['id'] = '83731907';
        $dummy_stub['vendors_activations']['vendor_id'] = '10';
        $dummy_stub['vendors_activations']['product_id'] = '18';
        $dummy_stub['vendors_activations']['mobile'] = "$number";
        $dummy_stub['vendors_activations']['param'] = '01510217025';
        $dummy_stub['vendors_activations']['amount'] = '50';
        $dummy_stub['vendors_activations']['discount_commission'] = '3.75';
        $dummy_stub['vendors_activations']['txn_id'] = '302283731907';
        $dummy_stub['vendors_activations']['vendor_refid'] = 'GM4-7738832731-0718';
        $dummy_stub['vendors_activations']['operator_id'] = 'SREC99406298';
        $dummy_stub['vendors_activations']['shop_transaction_id'] = '164314449';
        $dummy_stub['vendors_activations']['retailer_id'] = '13';
        $dummy_stub['vendors_activations']['invoice_id'] = '';
        $dummy_stub['vendors_activations']['status'] = '1';
        $dummy_stub['vendors_activations']['prevStatus'] = '0';
        $dummy_stub['vendors_activations']['api_flag'] = '4';
        $dummy_stub['vendors_activations']['cause'] = 'Transaction Processed.';
        $dummy_stub['vendors_activations']['code'] = '31';
        $dummy_stub['vendors_activations']['timestamp'] = '2015-05-14 16:21:55';
        $dummy_stub['vendors_activations']['date'] = '2015-05-14';
        $dummy_stub['vendors_activations']['extra'] = '';
        $dummy_stub['vendors_activations']['complaintNo'] = '';
        return $dummy_stub;
    }

    /*
     * 
     * function to connect to b2c redis db
     */

    function rconnector() {
        $this->General->logData("/mnt/logs/b2cextender.txt", "inside redis_connect ");
        try {
            //$this->General->logData("/mnt/logs/b2cextender.txt"," host : ".REDIS_HOST." | port : ".REDIS_PORT." | passwd : ".REDIS_PASSWORD." ");
            App::import('Vendor', 'Predis', array('file' => 'Autoloader.php'));
            Predis\Autoloader::register();
            $redis_connection = new Predis\Client(array(
                    'host' => REDIS_HOST,
                    'port' => REDIS_PORT,
                    'database' => 3,
                    'persistent' => true
            ));
        } catch (Exception $e) {
            echo "Couldn't connected to Redis";
            $this->General->logData("/mnt/logs/b2cextender.txt", "issue in redis_connect ");
            echo $e->getMessage();
            $redis_connection = false;
        }
        return $redis_connection;
    }

    /*
     * This manage the request that belong to b2c users
     * 
     */

    function manage_request_from_b2c_user($req_data = array(), $type = 'success') {
        /** pattern in success 
          $req_data = array('trans_id'=>$transId,'area'=>$circle,'flag'=>$service_id,'product_id'=>$_REQUEST['product_id'],
          'mobile'=>$mobile,'param'=>$par,'amount'=>$amount,'timestamp'=>date('Y-m-d H:i:s'));
         * */
        try {
            $this->General->logData("/mnt/logs/b2cextender.txt", "inside manage req : " . $req_data['mobile'] . " | type : " . $type . "| data : " . json_encode($req_data));
            //$redis = $this->rconnector();
            $MsgTemplate = $this->General->LoadApiBalance();
            $this->redis = $this->Shop->redis_connect();
            $this->General->logData("/mnt/logs/b2cextender.txt", "redis connection established ");
            $this->redis->select(3);
            $this->General->logData("/mnt/logs/b2cextender.txt", "inside after rconnect ");
            if ($this->redis == FALSE) {
                $this->General->logData("/mnt/logs/b2cextender.txt", "issue in b2c redis connection mobile: " . $req_data['mobile']);
                return;
            }
            $this->General->logData("/mnt/logs/b2cextender.txt", "issue in manage : " . $req_data['mobile'] );
            if ($this->is_b2c_user($req_data['mobile'], $this->redis)) {
                if ($type == 'success') {
                    $this->General->logData("/mnt/logs/b2cextender.txt", "call add success function mobile: " . $req_data['mobile']);
                    $this->add_txn_to_b2c_reqQ($req_data, $this->redis);
                } elseif ($type == 'failure') {
                    $this->General->logData("/mnt/logs/b2cextender.txt", "call add failure function mobile: " . $req_data['mobile']);
                    $this->add_txn_to_b2c_failQ($req_data, $this->redis);
                }
            } else {
                $this->General->logData("/mnt/logs/b2cextender.txt", "user not found in b2c user list mobile: " . json_encode($req_data));
                $retailerList = array('6070', '17359', '20810');
                //if (in_array($req_data['retailer_code'], $retailerList)) {
                if (isset($req_data['b2c_campaign_flag']) && $req_data['b2c_campaign_flag'] == '1') {
                    /*
                     * send promotion sms to user
                     */
                    $this->General->logData("/mnt/logs/b2cextender.txt", "b2c_campaign_flag: " . $req_data['b2c_campaign_flag']);
                
					
                	$receivers = $req_data['mobile'];
                    $retailer_shop_name = trim($req_data['retailer_name']);
                    $missed_call_number = "02267242255";
                    $retailer_shop_name = (strlen($req_data['retailer_name']) > 0) ? ((strlen($req_data['retailer_name']) > 10) ? "'" . substr($req_data['retailer_name'], 0, 10) . "..'" : "") : "";
//                    $message = "Thank you for recharging at a PAY1 store\n$retailer_shop_name. Keep recharging from here to get\nexciting gifts. Just give a missed call to $missed_call_number to\nstart now!";
                    
                    /*$paramdata['RETAILER_SHOP_NAME'] = $retailer_shop_name;
                    $paramdata['MISSED_CALL_NUMBER'] = $missed_call_number;
                    $content =  $MsgTemplate['B2C_User_Request_MSG'];
                    $message = $this->General->ReplaceMultiWord($paramdata,$content);
                    
                    $this->General->sendMessage($receivers, $message, 'shops');*/
                }
            }
        } catch (Exception $ex) {
            $this->General->logData("/mnt/logs/b2cextender.txt", "user not found in b2c user list mobile: " . $req_data['mobile'] . "| error :" . $ex->getTraceAsString());
        }
        //$redis->quit();
    }

    /*
     * This checks whether the user is from b2c users list
     * 
     */

    function is_b2c_user($mobile, $redisObj) {
        $b2c_user_hash_name = "b2cUserList";
        if ($redisObj->hexists($b2c_user_hash_name, $mobile)) {
            return TRUE;
        }
        return FALSE;
    }

    /*
     * This insert request in b2c success Queue
     * 
     */

    function add_txn_to_b2c_reqQ($req_data = array(), $redisObj) {
        $this->General->logData("/mnt/logs/b2cextender.txt", "inside add_txn_to_b2c_reqQ mobile: " . $req_data['mobile']);
        $b2c_success_txn_Q_name = "B2B_shop_success_txn_Q";
        $redisObj->lpush($b2c_success_txn_Q_name, json_encode($req_data));
    }

    /*
     * This insert request in b2c failure Queue
     * 
     */

    function add_txn_to_b2c_failQ($req_data = array(), $redisObj) {
        $this->General->logData("/mnt/logs/b2cextender.txt", "inside add_txn_to_b2c_failQ mobile: " . $req_data['mobile']);
        $b2c_failure_txn_Q_name = "B2B_shop_failure_txn_Q";
        $redisObj->lpush($b2c_failure_txn_Q_name, json_encode($req_data));
    }
    
    }

?>
