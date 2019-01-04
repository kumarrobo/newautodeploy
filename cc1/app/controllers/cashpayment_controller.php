<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CashpaymentController extends AppController {

    var $name = 'Cashpayment';
    var $components = array('RequestHandler', 'Shop', 'General',);
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator');
    var $uses = array('Retailer','User','Slaves','C2d');

    function beforeFilter() {
    	parent::beforeFilter ();
        $this->Auth->allow('*');
//         header('Content-Type: application/json');
    }

    function checkForAccess($method){
		$ret = true;
		$auth_dist = array();
		$auth_ret = array();

		if(in_array($method,$auth_dist) || in_array($method,$auth_ret)){
			if(!empty($_SESSION['Auth']) && !empty($_SESSION['Auth']['User']) && !empty($_SESSION['Auth']['User']['group_id'])){
				$ret = 404;

				$group_id = $_SESSION['Auth']['User']['group_id'];
				if($group_id == RETAILER && in_array($method,$auth_ret)){
					$ret = true;
				}else if($group_id == DISTRIBUTOR && in_array($method,$auth_dist)){
					$ret = true;
				}
				else {
					$ret = 403;
				}
			}else{
				$ret = 403;
			}
		}
		return $ret;
	}
    
    function test() {
        $rec_data = $this->params['form']; // get data from post
        
        $logger = $this->General->dumpLog('cashpg client test function ', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($rec_data));
        
        //echo $this->generate_password();echo "<hr>";
        $client_data = $this->C2d->query("SELECT * FROM cash_payment_client");
        //json_encode($client_data);
        //echo "<hr>";
        //print_r($client_data);
        $user_arr = array();
        foreach ($client_data as $k => $v) {
            $user_arr[$v['cash_payment_client']['id']] = $v['cash_payment_client'];
        }
        //echo "<hr>";
        //echo "<pre>";print_r($user_arr);echo "</pre>";
        //echo "1";echo "<hr>";
        $rec_data = $this->params;
        $req_data = $rec_data['form'];
        //print_r($rec_data);exit();
        $user_data = $this->dummy_data_source($req_data['key']);
        $salt = $user_data[$req_data['key']]['salt'];
        $server_signature = $this->generate_signature($req_data, $salt);
        echo "=====> ( " . $server_signature . " )<=====";
        $this->autoRender = false;
    }

    /*
     * source to fetch data related user
     */

    function dummy_data_source($key = null) {
        $dummy_response = array();
        
        $logger = $this->General->dumpLog('dummy_data_source ', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($key));
        
        if (is_null($key)) {
            $logger->warn("key param is null ");
            $dummy_response = array();
        } else {
            $cash_client_qry = "SELECT * FROM cash_payment_client where id='" . $key . "' ";
            $logger->info("Query : ".$cash_client_qry);
            
            $client_data = $this->C2d->query($cash_client_qry);
            $logger->info("Query Data : ".json_encode($client_data));
            
            if (!empty($client_data)){
                $dummy_response = array($client_data[0]['cash_payment_client']['id'] => $client_data[0]['cash_payment_client']);
            }
            
        }
        
        $logger->info("return : ".json_encode($dummy_response));

        return $dummy_response;
    }

    /*
     * authhorize client
     */

    function authorize_client($req_data = array()) {
        
        $logger = $this->General->dumpLog('cashpg auth', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($req_data));
        
        if (!empty($req_data)) {
            
            $user_data = $this->dummy_data_source($req_data['key']);
            $logger->info("user data : ".json_encode($user_data));
            
            if( count($user_data) < 1){
                return array('status' => 'failure', 'error' => 124, 'description' => 'Invalid / Missing key param');;
            }
            
            $salt = $user_data[$req_data['key']]['salt'];
            $server_signature = $this->generate_signature($req_data, $salt);
            $req_data['server_signature'] = $server_signature;

            return $this->validate_signature($req_data);
        }        header('Content-Type: application/json');

        return array('status' => 'failure', 'error' => 123, 'description' => 'request without parameter(s)');;
    }

    /*
     * validate signature
     */

    function validate_signature($req_data = array()) {
        
        if (!empty($req_data) && $req_data['signature'] === $req_data['server_signature']) {

            return array('status' => 'success', 'error' => 0, 'description' => '');
        }

        return array('status' => 'failure', 'error' => 115, 'description' => 'Invalid signature');
    }

    /*
     * Generate signature from data
     */

    function generate_signature($req_data = array(), $salt = null) {

        unset($req_data['signature']);
        return hash("sha256", implode(array_values($req_data), "|") . "|" . $salt);
    }

    /*
     * create client
     */

    function create_cashpayment_client() {
        header('Content-Type: application/json');
        $rec_data = $this->params['form']; // get data from post
        
        $logger = $this->General->dumpLog('cashpg client creation', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($rec_data));
        
        $passwd = $this->generate_password();
        $user_data = array('company_name' => '', 'password' => md5($passwd), 'salt' => $this->generate_salt(),
                'callback_api' => '', 'status' => '1', 'created_date' => date('Y-m-d H:i:s'), 'commission' => '');

        foreach ($user_data as $key => $val) {
            if (array_key_exists($key, $rec_data)) {
                $user_data[$key] = $rec_data[$key];
            }
        }

        $col_name_string = implode(",", array_keys($user_data));
        $col_val_string = implode("','", array_values($user_data));
        $qrystr = "INSERT INTO cash_payment_client (" . $col_name_string . ") VALUES('" . $col_val_string . "') ";
        $client_data = $this->C2d->query($qrystr);

        if ($client_data) {

            $client_id = $this->C2d->query("SELECT LAST_INSERT_ID() as id FROM cash_payment_client limit 1");
            $client_id = $client_id[0][0]['id'];

            $response = array('status' => 'success', 'errCode' => 0, 'description' => array('passwd' => $passwd, 'key' => $client_id));
        } else {

            $response = array('status' => 'failure', 'errCode' => 101, 'description' => 'unable to create user');
        }

        $logger->info("Response : ".json_encode($response));
        echo json_encode($response);
        $this->autoRender = false;
    }

    /*
     * collection request
     * This function is take a request from client to collect cash from customer
     * @param : key, mobile, amount, ref_id, expiry_time YYYY-MM-DD HH:MM:SS, hash
     */

    function collection_req() {
        header('Content-Type: application/json');
        $rec_data_in = $this->params;
        $req_data_in = $rec_data_in['form'];
        
        $logger = $this->General->dumpLog('cashpg collection request', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($rec_data));
        
        //file_put_contents("/tmp/cashpg_".date('Ymd').".log", date('Y-m-d H:i:s')." - in collection_req ".json_encode($req_data)."\n", FILE_APPEND | LOCK_EX);
        $req_data = array("key"=>$req_data_in['key'],"mobile"=>$req_data_in['mobile'],"amount"=>$req_data_in['amount'],"expiry_time"=>$req_data_in['expiry_time'],
                "ref_id"=>$req_data_in['ref_id'],"signature"=>$req_data_in['signature']);
        
        $auth_response = $this->authorize_client($req_data);
        
        if ($auth_response['status'] === "success") {

            $this->C2d->query("BEGIN");
            $current_time = date('Y-m-d H:i:s');
            $request_arr = array('cash_client_id' => $req_data['key'], 'mobile' => $req_data['mobile'], 'amount' => $req_data['amount'],
                    'intime' => $current_time, 'expiry_time' => $req_data['expiry_time'], 'client_ref_id' => $req_data['ref_id'],
                    'in_date'=>date('Y-m-d'));

            //--- checking duplicate request            
            $duplicate_req_chk_qry = "SELECT * FROM cash_payment_txn WHERE client_ref_id='" . $req_data['ref_id'] . "' ";
            $ref_id_Data = $this->C2d->query($duplicate_req_chk_qry);

            if (count($ref_id_Data) > 0) {

                $response = array('status' => 'failure', 'errCode' => 121, 'description' => 'Duplicate request');
            } else {
                // -- inserting request in cash_payment_txn

                $req_qry = "INSERT INTO cash_payment_txn (" . implode(",", array_keys($request_arr)) . ")  "
                        . "VALUES('" . implode("','", array_values($request_arr)) . "') ";

                if ($this->C2d->query($req_qry)) {

                    $request_id = $this->C2d->query("SELECT LAST_INSERT_ID() as id FROM cash_payment_txn limit 1"); // fetching last id of the last row inserted by this connection
                    $request_id = $request_id[0][0]['id'];

                    $this->User->query("COMMIT");
                    $response = array('status' => 'success', 'errCode' => 0, 'description' => array('request_id' => $request_id));
                } else {
                    $response = array('status' => 'success', 'errCode' => 102, 'description' => 'unable to accept request');
                }
            }
        } else {
            
            $response = $auth_response;
            //$response = array('status' => 'failure', 'errCode' => 115, 'description' => 'Un-Authorise request');
        }

        // -displaying success response
        $logger->info("Response : ".json_encode($response));
        echo json_encode($response);

        $this->autoRender = false;
    }

    /*
     * reset password
     */

    function reset_password() {
        
        $rec_data = $this->params;
        $post_data = $rec_data['form'];
        
        $logger = $this->General->dumpLog('cashpg reset password', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($post_data));

        if (isset($post_data['key']) && isset($post_data['salt'])) {

            $newPassword = $this->generate_password();
            $client_id = $post_data['key'];
            //---- qry
            $salt_update_qry = "UPDATE cash_payment_client SET password='$newPassword' WHERE id='$client_id'";

            if ($this->C2d->query($salt_update_qry)) {

                return TRUE;
            }

            return FALSE;
        }

        return FALSE;
    }

    /*
     * reset salt
     */

    function reset_salt() {
        
        $rec_data = $this->params;
        $post_data = $rec_data['form'];

        $logger = $this->General->dumpLog('cashpg reset salt', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($post_data));
        
        if (isset($post_data['key'])) {

            $newSalt = $this->generate_salt();
            $client_id = $post_data['key'];
            //---- qry
            $salt_update_qry = "UPDATE cash_payment_client SET salt='$newSalt' WHERE id='$client_id'";

            if ($this->C2d->query($salt_update_qry)) {

                return TRUE;
            }

            return FALSE;
        }

        return FALSE;
    }

    /*
     * check status
     */

    function check_status() {
        header('Content-Type: application/json');
        $rec_data1 = $this->params;
        $post_data = $rec_data1['form'];        
        
        $logger = $this->General->dumpLog('cashpg check status', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($post_data));
        $post_data = array("key"=>$post_data['key'],"ref_id"=>$post_data['ref_id'],"request_id"=>$post_data['request_id'],"signature"=>$post_data['signature']);
        $auth_response = $this->authorize_client($post_data);    
        if ($auth_response['status'] === "success") {

            if (!empty($post_data) && ( isset($post_data['ref_id']) || isset($post_data['request_id']) )) {

                $cash_txn_id = empty($post_data['request_id']) ? "" : $post_data['request_id'];
                $client_ref_id = empty($post_data['ref_id']) ? "" : $post_data['ref_id'];
                $check_qry = "SELECT * FROM (SELECT ct.*, cs.settled_date "
                        . "FROM cash_payment_txn as ct "
                        . "LEFT JOIN `cash_payment_settlement` as cs ON ct.id = cs.cash_payment_id "
                        . "WHERE  ct.id = '$cash_txn_id' OR ct.client_ref_id = '$client_ref_id') as cash_payment_txn ";
                
                $status_qry_result = $this->C2d->query($check_qry);

                if ($status_qry_result) {

                    $status_result = $status_qry_result[0]['cash_payment_txn'];
                    $response = array('status' => 'success', 'errCode' => 0, 'description' =>
                            array('mobile' => $status_result['mobile'],'amount' => $status_result['amount'], 'ref_id' => $status_result['client_ref_id'],
                                    'intime' => $status_result['intime'], 'collected_time' => $status_result['updated_time'],
                                    'settled_time' => $status_result['settled_date'],
                                    'expiry_time' => $status_result['expiry_time'], 'status' => $status_result['status']));
                } else {

                    $response = array('status' => 'success', 'errCode' => 118, 'description' => 'Invalid Parameter value');
                }
            } else {

                $response = array('status' => 'failure', 'errCode' => 120, 'description' => 'Missing Parameter');
            }
        } else {

            //$response = array('status' => 'failure', 'errCode' => 115, 'description' => 'Un-Authorise request');
            $response = $auth_response;
        }

        $logger->info("Response : ".json_encode($response));
        echo json_encode($response);

        $this->autoRender = false;
    }

    /*
     * get transaction list
     */

    function get_transaction_list() {

        header('Content-Type: application/json');
        $rec_data = $this->params;
        $post_data = $rec_data['form'];
        
        $logger = $this->General->dumpLog('cashpg get user txn list', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($post_data));
        
        $auth_response = $this->authorize_client($post_data);    
        if ($auth_response['status'] === "success") {
            if (!empty($post_data) && isset($post_data['key']) && !empty($post_data['key'])) {
                $continue_flag = 0 ;
                if( !(isset($post_data['start_date']) || isset($post_data['end_date']) )){
                    $continue_flag = 1 ;
                    $response = array('status' => 'failure', 'errCode' => 129, 'description' => "start_date or end_date paramater missing");
                }
                $post_data['start_date'] = empty($post_data['start_date'])?date("Y-m-d"):$post_data['start_date'];
                $post_data['end_date'] = empty($post_data['end_date'])?date("Y-m-d"):$post_data['end_date'];
                
                if($continue_flag ==0 && ( strtotime($post_data['start_date']) > strtotime($post_data['start_date']) ) ){                    
                    $start_date_chk = checkdate(substr($post_data['start_date'],5,2), substr($post_data['start_date'],8,2) ,substr($post_data['start_date'],0,4));
                    $end_date_chk = checkdate(substr($post_data['end_date'],5,2), substr($post_data['end_date'],8,2) ,substr($post_data['end_date'],0,4));
                    $max_report_days = 30;
                    
                    if(!($start_date_chk && $end_date_chk)){
                        $response = array('status' => 'failure', 'errCode' => 130, 'description' => "Incorrect date format");                 
                        $continue_flag = 1 ;
                    }elseif( (strtotime($post_data['end_date']) - strtotime($post_data['start_date'])/(60*60*24)) > $max_report_days ){
                        $response = array('status' => 'failure', 'errCode' => 132, 'description' => "Date range exceeding 30 days");
                        $continue_flag = 1 ;
                    }else{
                        $response = array('status' => 'failure', 'errCode' => 131, 'description' => "Incorrect date range");
                        $continue_flag = 1 ;
                    }
                }
                
                if($continue_flag == 0){
                    $mob_cond = "";
                    $date_cond = " AND date(intime) >= '".$post_data['start_date']."' AND date(intime) <= '".$post_data['end_date']."'  ";
                    
//                    if(isset($post_data['mobile']) && !empty($post_data['mobile'])){
//                        $mob_number = trim($post_data['mobile']);
//                        $mob_cond = " and ct.mobile='$mob_number' ";
//                    }
                    
                    $cash_client_id = $post_data['key'];
                    $client_detail_qry = "SELECT * FROM cash_payment_client where id='$cash_client_id'";
                    $client_detail = $this->C2d->query($client_detail_qry);
                    $txn_list_qry = "SELECT * FROM (SELECT ct.id as request_id, ct.mobile, ct.amount, ct.client_ref_id as ref_id, "
                            . "ct.intime, ct.expiry_time, ct.updated_time  as collected_time, cs.settled_date as settled_time, ct.status "
                            . "FROM cash_payment_txn as ct "
                            . "LEFT JOIN `cash_payment_settlement` as cs ON ct.id = cs.cash_payment_id "
                            . "WHERE  ct.cash_client_id ='$cash_client_id' $mob_cond) as cash_payment_txn ";

                    $txn_list_result = $this->C2d->query($txn_list_qry);
                    if (!empty($client_detail)) {

                        if (count($txn_list_result) > 0) {                        
                            $format_txn_list_result = array();

                            foreach($txn_list_result as $key=>$val){
                                $format_txn_list_result[] = $val['cash_payment_txn'];
                            }
                            $response = array('status' => 'success', 'errCode' => 0, 'description' => array('data' => $format_txn_list_result));
                        } else {

                            $response = array('status' => 'success', 'errCode' => 0, 'description' => array('data' => 'No Record Found'));
                        }
                    } else {

                        $response = array('status' => 'failure', 'errCode' => 118, 'description' => "Invalid Parameter Value");
                    }
                }
            } else {

                $response = array('status' => 'success', 'errCode' => 120, 'description' => "Missing Parameter");
            }
        } else {
            //$response = array('status' => 'failure', 'errCode' => 115, 'description' => 'Un-Authorise request');
            $response = $auth_response;
        }

        $logger->info("Response : ".json_encode($response));
        echo json_encode($response);

        $this->autoRender = false;
    }

    /*
     * cancel transaction
     * only cancel if it is in pending state i.e 0
     * 0-pending , 1-receipt , 2 -settled , 3-cancel, 4-expiry 
     */

    function cancel_transaction() {

        header('Content-Type: application/json');
        $rec_data = $this->params;
        $post_data = $rec_data['form'];
        
        $logger = $this->General->dumpLog('cashpg cancel txn request', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($post_data));
        
        $auth_response = $this->authorize_client($post_data);    
        if ($auth_response['status'] === "success") {

            if (!empty($post_data) && isset($post_data['ref_id']) && !empty($post_data['ref_id'])) {

                $client_ref_id = $post_data['ref_id'];
                $this->C2d->query("BEGIN");
                //$select_txn_qry = "SELECT status FROM cash_payment_txn WHERE  client_ref_id = '$client_ref_id' and status = '0' FOR UPDATE";
                $select_txn_qry = "SELECT status FROM cash_payment_txn WHERE  client_ref_id = '$client_ref_id' FOR UPDATE";
                $select_txn_result = $this->C2d->query($select_txn_qry);
                
                $update_flag = 0;
                if (count($select_txn_result) > 0) {
                    
                    if($select_txn_result[0]['cash_payment_txn']['status'] == 0 ){
                        $update_txn_qry = "UPDATE cash_payment_txn SET status=3 WHERE client_ref_id = '$client_ref_id' and status = '0'";

                        if ($this->C2d->query($update_txn_qry)) {

                            $this->C2d->query("COMMIT");
                            $response = array('status' => 'success', 'errCode' => 0, 'description' => array('message' => 'Transaction cancelled successfully'));
                        } else {

                            $this->C2d->query("ROLLBACK");
                            $response = array('status' => 'failure', 'errCode' => 119, 'description' => 'Unable to update request');
                        }
                    }else{
                        $status_flag = $select_txn_result[0]['cash_payment_txn']['status'];
                        //1-receipt , 2 -settled , 3-cancel, 4-expiry 
                        $status_flag_err = array(
                                    1 =>array('errCode' =>'125','errMsg' => 'Transaction cannot be cancelled as transaction amount is collected'),
                                    2 =>array('errCode' =>'126','errMsg' => 'Transaction cannot be cancelled as transaction amount is settled'),
                                    3 =>array('errCode' =>'127','errMsg' => 'Transaction cannot be cancelled as transaction is already cancel'),
                                    4 =>array('errCode' =>'128','errMsg' => 'Transaction cannot be cancelled as transaction is expired'),
                                );
                        $response = array('status' => 'failure', 'errCode' => $status_flag_err[$status_flag]['errCode'], 'description' => $status_flag_err[$status_flag]['errMsg']);
                        
                    }
                } else {

                    $this->C2d->query("ROLLBACK");
                    $response = array('status' => 'failure', 'errCode' => 118, 'description' => 'Invalid parameter value for :ref_id');
                }
            } else {

                $response = array('status' => 'failure', 'errCode' => 117, 'description' => 'Parameter cannot be empty');
            }
        } else {

            //$response = array('status' => 'failure', 'errCode' => 115, 'description' => 'Un-Authorise request');
            $response = $auth_response;
        }

        $logger->info("Response : ".json_encode($response));
        echo json_encode($response);
        $this->autoRender = false;
    }

    /*
     * update callback api
     */

    function update_callback_api() {

        header('Content-Type: application/json');
        $rec_data = $this->params;
        $post_data = $rec_data['form'];
        
        $logger = $this->General->dumpLog('cashpg update callback API', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($post_data));

        $auth_response = $this->authorize_client($post_data);    
        if ($auth_response['status'] === "success") {
            $chk_flag = 0;
            $req_arr = array('key', 'callback_api');
            $post_data['key'] = isset($post_data['key'])?$post_data['key']:"";
            $post_data['callback_api'] = isset($post_data['callback_api'])?$post_data['callback_api']:"";

            foreach ($post_data as $k => $v) {
                $post_data[$k] = addslashes($v);
                $chk_flag = (!empty($post_data[$k])) ? 0 : "param  $k cannot be empty";
                if ($chk_flag != 0)
                    break;
            }

            if ($chk_flag != 0) {

                $response = array('status' => 'failure', 'errCode' => 117, 'description' => $chk_flag);
            }

            if (!empty($post_data) && !empty($post_data['key']) && !empty($post_data['callback_api']) && $chk_flag == 0) {

                $callback_api = $post_data['callback_api'];
                $user_id = $post_data['key'];
                $update_qry = "UPDATE cash_payment_client SET `callback_api`='$callback_api' WHERE id='$user_id' ";
                if ($this->C2d->query($update_qry)) {

                    $response = array('status' => 'success', 'errCode' => 0, 'description' => array('message' => 'Callback API updated successfully'));
                } else {

                    $response = array('status' => 'failure', 'errCode' => 119, 'description' => 'Unable to update request');
                }
            }
        } else {

            //$response = array('status' => 'failure', 'errCode' => 115, 'description' => 'Un-Authorise request');
            $response = $auth_response;
        }
        
        $logger->info("Response : ".json_encode($response));
        echo json_encode($response);
        $this->autoRender = false;
    }

    /*
     * generate salt
     */

    function generate_salt($user_key = null) {

        return md5($user_key . "|" . rand() . "|" . time());
    }

    /*
     * generate password
     */

    function generate_password($len = 5) {

        $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $result = str_shuffle($charset);

        return substr($result, -$len);
    }

    /*
     * generate verification code
     */

    function generate_verification_code($len = 6) {
        $charset = time() . "0123456789" . time();
        $result = str_shuffle($charset);
        return substr($result, -$len);
    }

    /*
     * will be required from application
     */

    function get_client_detail_by_ref_id() {
        
    }

    /*
     * This function is used fetch transaction detail by request id 
     */

    function transaction_detail_by_request_id($request_id = null) {

        header('Content-Type: application/json');
        $logger = $this->General->dumpLog('cash pg txn detail by reqID', 'cash_pg_payment');
        $logger->info("Received param : ".json_encode($request_id));
        if (!is_null($request_id)) {

            $check_qry = "SELECT * FROM cash_payment_txn WHERE  id = '$request_id' LIMIT 0,1";
            $status_qry_result = $this->C2d->query($check_qry);

            if ($status_qry_result) {

                $status_result = $status_qry_result[0]['cash_payment_txn'];
                $response = array('status' => 'success', 'errCode' => 0, 'description' =>
                        array('mobile' => $status_result['mobile'], 'ref_id' => $status_result['client_ref_id'],
                                'intime' => $status_result['intime'], 'settled_time' => $status_result['updated_time'],
                                'expiry_time' => $status_result['expiry_time'], 'status' => $status_result['status']));
            } else {

                $response = array('status' => 'success', 'errCode' => 118, 'description' => 'Invalid Parameter value');
            }
        } else {

            $response = array('status' => 'failure', 'errCode' => 120, 'description' => 'Missing Parameter');
        }
        
        $logger->info("Response : ".json_encode($response));
        echo json_encode($response);
        $this->autoRender = false;
    }

    /*
     * It handle all the request from Pay1 Application
     */
    
    function cashpayment_api_manager($param){
        
        $post_data = $param;
        
        $method = isset($param['method'])?$param['method']:"";
        
        $logger = $this->General->dumpLog('cash pg txn detail by reqID', 'cash_pg_payment');
        $logger->info("Received param : ".  json_encode($post_data));
        
        /*App::import('Controller', 'apis');
		$obj = new ApisController;
		$obj->constructClasses();
        
        $acl = $obj->checkForAccess($method);
        if($acl !== true){
            $logger->info("$requestid: Access issues::" . $this->Shop->errors($acl));
            $obj->displayWeb(array('status'=>'failure','code'=>$acl,'description'=>$obj->Shop->errors($acl),'app_log_id'=>$app_log_id), $format);exit;
        }*/
        
        $action = isset($post_data['action'])?strtolower(trim($post_data['action'])):"";
        
        if(!empty($action)){
            switch ($action){
                case 'get_txn_list':
                    $action = 'get_pending_request_by_mobile';
                    break;                
            }
            return $this->$action($post_data);
        }
        
        return false;
    }
    
    
    /*
     * This function will return list of pending cashpg transaction for a particular mobile number
     */
    
    function get_pending_request_by_mobile($param_data = array()){
        
        $logger = $this->General->dumpLog('get_pending_request_by_mobile', 'cash_pg_payment');
        $logger->info("Received param : ".  json_encode($param_data));
        
        if(!empty($param_data)){
            $mobile = $param_data['mobileNumber'];
            $txn_list_qry = "SELECT * FROM "
                            . "(SELECT cpt.id,cpc.company_name,cpt.mobile,cpt.amount,cpt.status,cpt.expiry_time, cpc.logo_url as 'img_url', cpc.product_id as operator "
                            . "FROM cash_payment_txn as cpt "
                                . "LEFT JOIN cash_payment_client as cpc ON cpt.cash_client_id = cpc.id "
                                . "WHERE  cpt.mobile = '$mobile' and cpt.status=0 ) as cpg ";
            
            $logger->info("Query : ". $txn_list_qry );
            $txn_list_qry_result = $this->C2d->query($txn_list_qry);
            foreach($txn_list_qry_result as $key=>$val){
                $txn_list_qry_result_new[] = $val['cpg'];
            }
            $txn_list_qry_result = $txn_list_qry_result_new;
            $logger->info("Query Result : ".  json_encode($txn_list_qry_result));
            if(!empty($txn_list_qry_result)){
                $result = array('status'=>'success','code'=>0,'description'=>$txn_list_qry_result);
            }else{
                $result = array('status'=>'failure','code'=>'1004','description'=>'No Record available');
            }
            return $result;
        }
    }


    //date format: 'Y-m-d H:i:s'
    /**
     * @author: Rishabh Gupta
     * Loads the basic filtering options of the transactions
     */
    function index (){
//     	$this->autoRender = false;
//     	echo "<h1>working</h1>";
    	$this->layout = 'cashpayment';
        
    	$query = "SELECT id, company_name FROM cash_payment_client";
    	$clientDetailsData = $this->C2d->query($query);
    	$clientDetails = array ();
    	foreach ($clientDetailsData as $arr){
    		$clientDetails [] =  array("value" => $arr ['cash_payment_client']['company_name'],"data"=> $arr ['cash_payment_client']['id']);
    	}
    	 
    	$this->set('clientDetails',json_encode($clientDetails));
    }
    
    /**
     * loads the transactiond details for a particular client,
     * between range of dates, and status of payments
     */
    function loadTransactionData(){
    	$this->autoRender = false;
    	$fromDate = $_POST['from_date'];
    	$toDate = $_POST['to_date'];
    	$status = $_POST['status'];
    	$clientId = $_POST['client_id'];
        if ($this->General->dateValidate($fromDate) == 1 && $this->General->dateValidate($toDate) == 1 && ctype_alnum($status) && ctype_alnum($clientId)) {
            $query = "SELECT
            CPT.id,
            CPC.company_name,
            CPT.amount,
            CPT.status,
            CPT.intime,
            CPT.updated_time,
            CPS.settled_date,
            CPT.expiry_time
            FROM
            cash_payment_txn AS CPT
            LEFT JOIN
            cash_payment_settlement AS CPS
            ON
            CPT.id = CPS.cash_payment_id
            INNER JOIN
            cash_payment_client AS CPC
            ON
            CPT.cash_client_id = CPC.id
            WHERE
            CPT.in_date BETWEEN '$fromDate' AND '$toDate'
            AND CPT.status = $status
            AND CPC.id = $clientId";

            $dataCashPayment = $this->C2d->query($query);

            foreach ($dataCashPayment as $index => $arr){
                    foreach ($arr as $data){
                            foreach ($data as $key => $value){
                                    $cashPayment [$index][$key] = $value;
                            }
                    }
            }

            $data = array();
            if(!empty($cashPayment)){
                    $data['success_status'] = "success";
                    $data['response'] = $cashPayment;
            }
            else
                    $data['success_status'] = "failure";
        }
        echo json_encode($data);
    }
    
    /**
     * Loads the modal form with prefilled amounts (transaction, settlement, commission amt)
     * and emptyl columns of 'settled_by' and 'comments' column
     */
    function loadSettlementData(){
    	$this->autoRender = false;
    	 
    	$txnId = $_POST['txn_id'];
    	$vendorId = 56;
//    	$query = "SELECT
//    	CPT.id, CPT.amount, VC.discount_commission
//    	FROM
//    	vendors_commissions as VC,
//    	cash_payment_client as CPC,
//    	cash_payment_txn as CPT
//    	WHERE
//    	VC.product_id = CPC.product_id
//    	and VC.vendor_id = $vendorId
//    	and CPT.cash_client_id = CPC.id
//    	and CPT.id = $txnId";
        
        $query = "SELECT
    	CPT.id, CPT.amount,CPC.commission 
    	FROM
    	cash_payment_client as CPC,
    	cash_payment_txn as CPT
    	WHERE 1
    	and CPT.cash_client_id = CPC.id
    	and CPT.id = $txnId";
    	 
    	$dataSettlement = $this->C2d->query($query);
    	 
    	$settlement = array();
    	foreach ($dataSettlement as $arr){
    		$settlement ['transaction_id'] = $txnId;
    		$txnAmount = $arr['CPT']['amount'];
    		$settlement ['transaction_amount'] = $txnAmount;
    		$commissionAmount = $arr ['CPC']['commission'] * 0.01 * $txnAmount;
    		$settlement ['commission_amount'] = $commissionAmount;
    		$settlement ['settlement_amount'] = $txnAmount - $commissionAmount;
    	}
    	 
    	//     	$txnAmount = $dataSettlement[0]['CPT']['amount'];
    	//     	$commissionAmount = $dataSettlement[0]['discount_commission'] * 0.01 * $txnAmount;
    	//     	$settlementAmount = $txnAmount - $commissionAmount;
    	 
    	if(!empty($txnAmount)){
    		$data["status"] = "success";
    		$data["response"] = $settlement;
    	}
    	else
    		$data["status"] = "failure";
    	echo json_encode($data);
    }
    
    /**
     * Inserts the settlement data in 'cash_payment_client' table
     */
    function insertSettlementData(){
    	$this->autoRender = false;
    	$settlementDetails = array();
    	$settlementDetails  = $_POST;
    	$dateStamp = date ('Y-m-d H:i:s');
    	$date =  date ('Y-m-d');
    	 
    	// default set to 0(zero)
    	//     	$settledBy = $settlementDetails ['settled'];
    	$settledBy = 0;
    	$query = "INSERT INTO cash_payment_settlement
    				(cash_payment_id, txn_amount, commission_amount, settlement_amount, settled_by, date, settled_date, comment)
    			   VALUES (
    						".$settlementDetails ['txn_id'].",
    						".$settlementDetails ['txn_amt'].",
    						".$settlementDetails ['commission_amt'].",
    						".$settlementDetails ['settlement_amt'].",
    						".$settledBy.",
    						'".$date."',
    						'".$dateStamp."',
    						'".$settlementDetails ['comment']."'
    				)";
    	//     	$query = "INSERT INTO cash_payment_settlement
    	//     				(cash_payment_id, txn_amount, commission_amount, settlement_amount, settled_by, date, settled_date, comment)
    	//     			   VALUES (0,0,0,0,0,
    	//     						'".$settlementDetails ['settled']."',
    	//     						'".$date.",'
    	//     						'".$dateStamp.",'
    	//     						'".$settlementDetails ['comment']."'
    	//     				)";
    	//     	echo "$query";
    	//     	die;
    	 
    	//insert settled data in table
    	$this->C2d->query($query);
    	$data = array();
    	if(!empty($settlementDetails)){
    		$data["status"] = "success";
    	}
    	else{
    		$data["status"] = "failure";
    	}
    	echo json_encode($data);
    	 
    }
    
	
}
