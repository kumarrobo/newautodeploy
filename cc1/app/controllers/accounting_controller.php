<?php

class AccountingController extends AppController {

        var $name = 'Accounting';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart');
    
	var $components = array('RequestHandler','Shop', 'Serviceintegration','Servicemanagement');
	var $uses = array('User','Slaves','Limits','SuperDistributor');
        function beforeFilter() {
                parent::beforeFilter();

                ini_set("memory_limit","512M");

                $this->Auth->allow('easyPayConfirmation');
                $this->Auth->allow('encrypt');


                /***Get All SuperDistributor**/

                $all_super_distributors = $this->SuperDistributor->find('all', array(
                        'fields' => array('SuperDistributor.id,SuperDistributor.user_id'),
                        'conditions' => array('SuperDistributor.active_flag' => 1),
                        'order' => 'SuperDistributor.id asc',
                        )
                );
                /** IMP DATA ADDED : START**/
                $all_super_dist_ids = array_map(function($element){
                    return $element['SuperDistributor']['user_id'];
                },$all_super_distributors);


                $sd_imp_data = $this->Shop->getUserLabelData($all_super_dist_ids,2,3);
                /*****/
	}

    function txnUpload() {

        $bank_accounts = array_map(function($value) {
            return array('id' => $value['bank_details']['id'], 'name' => $value[0]['name']);
        }, $this->Slaves->query("SELECT id, concat(bank_name,' - ',account_no) name FROM bank_details WHERE account_no != '' ORDER BY id DESC"));
        $this->set('bank_accounts', $bank_accounts);
    }

    function autoUpload($txn_id = 0, $from_date = 0, $to_date = 0, $bank = 0, $limit_auto = 0, $cd_type = 'Cr') {
        $mapping = array(
            '1' => array('name' => 'ICICI Bank:6714', 'fields_count' => array('9'), 'txn_id' => '2', 'txn_type' => '7', 'amount_cr' => '8', 'amount_dr' => '8', 'date' => '4', 'description' => '6', 'balance' => '9', 'branch_code' => ''),
            '2' => array('name' => 'Bank of Maharashtra:4079', 'fields_count' => array('6', '7', '8'), 'txn_id' => '', 'txn_type' => '', 'amount_cr' => '6', 'amount_dr' => '5', 'date' => '1', 'description' => '3', 'balance' => '7', 'branch_code' => '8'),
            '3' => array('name' => 'State Bank of India:6476', 'fields_count' => array('8'), 'txn_id' => '', 'txn_type' => '', 'amount_cr' => '7', 'amount_dr' => '6', 'date' => '1', 'description' => '3', 'balance' => '8', 'branch_code' => '5'),
            '4' => array('name' => 'ICICI Bank:1578', 'fields_count' => array('9'), 'txn_id' => '2', 'txn_type' => '7', 'amount_cr' => '8', 'amount_dr' => '8', 'date' => '4', 'description' => '6', 'balance' => '9', 'branch_code' => ''),
            '5' => array('name' => 'Axis Bank:4175', 'fields_count' => array('8'), 'txn_id' => '', 'txn_type' => '7', 'amount_cr' => '6', 'amount_dr' => '6', 'date' => '2', 'description' => '5', 'balance' => '8', 'branch_code' => '9'),
            '10' => array('name' => 'State Bank of India:0306', 'fields_count' => array('8'), 'txn_id' => '', 'txn_type' => '', 'amount_cr' => '7', 'amount_dr' => '6', 'date' => '1', 'description' => '3', 'balance' => '8', 'branch_code' => '5'),
            '11' => array('name' => 'Bank of India:0166', 'fields_count' => array('7'), 'txn_id' => '', 'txn_type' => '4', 'amount_cr' => '5', 'amount_dr' => '5', 'date' => '1', 'description' => '2', 'balance' => '6', 'branch_code' => ''),
            '12' => array('name' => 'HDFC Bank:9192', 'fields_count' => array('6'), 'txn_id' => '3', 'txn_type' => '', 'amount_cr' => '6', 'amount_dr' => '5', 'date' => '1', 'description' => '2', 'balance' => '7', 'branch_code' => ''),
            '13' => array('name' => 'ICICI Bank:0005', 'fields_count' => array('9'), 'txn_id' => '2', 'txn_type' => '7', 'amount_cr' => '8', 'amount_dr' => '8', 'date' => '4', 'description' => '6', 'balance' => '9', 'branch_code' => ''),
            '14' => array('name' => 'Kotak Mahindra Bank:0349', 'fields_count' => array('8'), 'txn_id' => '', 'txn_type' => '5', 'amount_cr' => '4', 'amount_dr' => '4', 'date' => '1', 'description' => '2', 'balance' => '6', 'branch_code' => ''),
            '15' => array('name' => 'Axis Bank:2845', 'fields_count' => array('8'), 'txn_id' => '', 'txn_type' => '7', 'amount_cr' => '6', 'amount_dr' => '6', 'date' => '2', 'description' => '5', 'balance' => '8', 'branch_code' => '9'),
            '16' => array('name' => 'Axis Bank:6899', 'fields_count' => array('8'), 'txn_id' => '', 'txn_type' => '7', 'amount_cr' => '6', 'amount_dr' => '6', 'date' => '2', 'description' => '5', 'balance' => '8', 'branch_code' => '9'),
            '17' => array('name' => 'RBL Bank:5842', 'fields_count' => array('5','6'), 'txn_id' => '', 'txn_type' => '', 'amount_cr' => '6', 'amount_dr' => '5', 'date' => '1', 'description' => '2', 'balance' => '7', 'branch_code' => ''),
            '18' => array('name' => 'RBL Bank:4112', 'fields_count' => array('5','6'), 'txn_id' => '', 'txn_type' => '', 'amount_cr' => '6', 'amount_dr' => '5', 'date' => '1', 'description' => '2', 'balance' => '7', 'branch_code' => ''),
            '19' => array('name' => 'Axis Bank:3812', 'fields_count' => array('8'), 'txn_id' => '', 'txn_type' => '7', 'amount_cr' => '6', 'amount_dr' => '6', 'date' => '2', 'description' => '5', 'balance' => '8', 'branch_code' => '9'),
            '20' => array('name' => 'Axis Bank:4338', 'fields_count' => array('8'), 'txn_id' => '', 'txn_type' => '7', 'amount_cr' => '6', 'amount_dr' => '6', 'date' => '2', 'description' => '5', 'balance' => '8', 'branch_code' => '9'),
        );

        $from_date = ($from_date == 0) ? date('Y-m-d') : $from_date;
        $to_date = ($to_date == 0) ? date('Y-m-d') : $to_date;

        $bank != 0 && $bank_cond = " AND bank_id = '" . $bank . "'";

        $banks = array_map(function($value) {
            return array('id' => $value['bank_details']['id'], 'name' => $value[0]['name']);
        }, $this->Slaves->query("SELECT id, concat(bank_name,' - ',account_no) name FROM bank_details WHERE account_no != '' ORDER BY id DESC"));
        foreach ($banks as $b) {
            $eb = explode(' - ', $b['name']);
            $bank_code = explode(':', $eb[0]);
            $account_nos[] = $eb[1];
            $limit_bank[$bank_code[1]] = $b['id'];
        }


        if ($this->RequestHandler->isPost()) {

            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/accounting_module.txt", date('Y-m-d H:i:s') . " :: Uploading Bank Statement");

            $file = $_FILES['bank_statement']['name'];
            if (!empty($file)) {
                $allowedExtension = array("xls", "csv");
                $getfileInfo = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($getfileInfo, $allowedExtension)) {
                    if ($getfileInfo == "xls") {
                        if (!move_uploaded_file($_FILES['bank_statement']['tmp_name'], "/tmp/" . $file)) {
                            echo $msg = "Failed to move uploaded file";
                            die;
                        }
                        chmod("/tmp/" . $file, 777);
                        $array_record = array();
                        $filepath = "/tmp/" . $file;
                        App::import('Vendor', 'excel_reader2');
                        $excel = new Spreadsheet_Excel_Reader($filepath, true);
                        $data = $excel->sheets[0]['cells'];
                    } else {
                        $file = fopen($_FILES['bank_statement']["tmp_name"], "r");
                        while (!feof($file)) {
                            $data[] = fgetcsv($file, 1024);
                        }
                        fclose($file);
                    }
                } else {
                    $this->Session->setFlash("* Invalid File Format (only xls file allowed)");
                    $this->redirect('/accounting/txnUpload');
                }

                App::import('Controller', 'Shops');
                $shops_controller = new ShopsController;
                $shops_controller->constructClasses();

                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/accounting_module.txt", date('Y-m-d H:i:s') . " :: Bank => {$_POST['bank']}");

                $match = array();
                $bankdet = explode(":", $mapping[$_POST['bank']]['name']);
                $acc_no = $bankdet[1];

                                $_POST['bank'] == '15' && $previous_descriptions = array_map(function($value) { return str_replace("'", "", trim($value['atd']['description'])); }, $this->Slaves->query("SELECT description FROM account_txn_details atd WHERE DATE(txn_date) >= '".date('Y-m-d', strtotime('-2 days', strtotime($date)))."' AND bank_id = '15' AND (description LIKE '%Clg%' OR description LIKE '%CHEQUE%' OR description LIKE '%heque%')"));
                                foreach ($data as $key => $value) {
                                        $var = array_values($value);

                                        if (in_array(count($value), $mapping[$_POST['bank']]['fields_count']) && (is_numeric(str_replace(',', '', $value[$mapping[$_POST['bank']]['amount_cr']])) || is_numeric(str_replace(',', '', $value[$mapping[$_POST['bank']]['amount_dr']])))) {

//                                                $common = array_intersect($match, $account_nos);
//                                                if ((substr($match[0], -4) != $acc_no) || (!empty ($common) && substr($common[0], -4) != $acc_no)) {
                                                if(!(in_array($acc_no, $match))){
                                                        $this->Session->setFlash("* Can't match bank !!!");
                                                        $this->redirect('/accounting/txnUpload');
                                                }

                                                $date = str_replace('/', '-', trim($value[$mapping[$_POST['bank']]['date']]));
                                                if(strlen($date) == 8) {
                                                        $date_pieces = explode('-', $date);
                                                        $date = $date_pieces[0] . '-' . $date_pieces[1] . '-20' .$date_pieces[2];
                                                }
                                                $date        = date('Y-m-d H:i:s', strtotime($date));
                                                $txn_type    = trim($value[$mapping[$_POST['bank']]['txn_type']]) != '' ? strtolower(trim($value[$mapping[$_POST['bank']]['txn_type']])) : (in_array(trim($value[$mapping[$_POST['bank']]['amount_cr']]), array('', '0')) ? 'dr' : 'cr');
                                                $amount      = $txn_type == 'cr' ? trim(str_replace(',', '', $value[$mapping[$_POST['bank']]['amount_cr']])) : trim(str_replace(',', '', $value[$mapping[$_POST['bank']]['amount_dr']]));
                                                $balance     = trim(str_replace('DR','',str_replace('CR','', str_replace(',','',$value[$mapping[$_POST['bank']]['balance']]))));
                                                $description = str_replace("'","",!in_array($_POST['bank'], array(10,14)) ? trim($value[$mapping[$_POST['bank']]['description']]) : trim($value[$mapping[$_POST['bank']]['description']]). " " .trim($value[$mapping[$_POST['bank']]['description']+1]));
                                                $txn_id      = (in_array($_POST['bank'], array(1,4)) ? date('His', strtotime($date)) : $_POST['bank']) . date('Ymd', strtotime($date)) . round($balance) . round($amount);
                                                $bank_txn_id = trim($value[$mapping[$_POST['bank']]['txn_id']]);
                                                $branch_code = trim($value[$mapping[$_POST['bank']]['branch_code']]);

                                                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/accounting_module.txt", date('Y-m-d H:i:s') ." :: Txn ID => $txn_id");
                                                if ((date('Y-m-d', strtotime($date)) < date('Y-m-d', strtotime('-2 day')) && $_SESSION['Auth']['User']['group_id'] != SUPER_ADMIN) || (date('Y-m-d', strtotime($date)) < date('Y-m-d', strtotime('-7 day')) && $_SESSION['Auth']['User']['group_id'] == SUPER_ADMIN)) {
                                                        $this->Session->setFlash("* Can't upload this txns now !!!");
                                                        $this->redirect('/accounting/txnUpload');
                                                }

                                                $_POST['bank'] == '15' && $description_parts = explode("-", $description);

                                                $result = array();

                                                $check_unique = $this->Slaves->query("SELECT id FROM account_txn_details WHERE pay1_txn_id = '$txn_id'");

                                                if (empty($check_unique) && !in_array($description, $previous_descriptions) && $description_parts[0] != 'SI/TRF/TE/25') {

                                                        $account_category_id = 0; $type = ''; $type_id = 0; $is_submitted = 0;

                                                        if (strpos($description, 'BY CASH ') !== false && $txn_type == 'cr' && $limit_auto == 1) {
                                                                $description != '' && $mined_no = implode('', array_map(function($val) {
                                                                        $val = str_replace(':','',$val);
                                                                        if(strlen($val) == 10 && is_numeric($val)) {
                                                                                return $val;
                                                                        }
                                                                }, explode(' ', $description)));

                                                                $check_records = $this->Slaves->query("SELECT users.id,name,group_id FROM users JOIN user_groups ON (users.id = user_groups.user_id AND user_groups.group_id IN ('".DISTRIBUTOR."','".RETAILER."')) WHERE mobile = '$mined_no'");

                                                                if ($check_records && !empty($check_records[0]['user_groups']['group_id'])) {
                                                                        $result = $this->amountTransfer($mined_no, $check_records[0]['user_groups']['group_id'], $amount, $txn_id, $shops_controller);
                                                                }

                                                                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/accounting_module.txt", date('Y-m-d H:i:s') ." :: ". json_encode($result));

                                                                $result['status'] == 'success' && $account_category_id = '54';
                                                                $is_submitted = $result['shopId'] != '' ? '1' : '0';

                                                        } else if ($txn_type == 'dr' && strpos($description, 'EKO') !== false) {

                                                                $account_category_id = '105';
                                                                $type                = 'vendor';
                                                                $type_id             = '4';
                                                                $is_submitted        = '1';

                                                        } else if ($txn_type == 'dr' && (strpos($description, 'RTGS') !== false || strpos($description, 'INF') !== false || strpos($description, 'CMS') !== false)) {

                                                                $description_break = explode('/', str_replace('RTGS:', '', $description));

                                                                if ($description_break) {
                                                                        foreach ($description_break as $val) {
                                                                                if (strlen($val) > 10) {
                                                                                        $result = $this->Slaves->query("SELECT io.id FROM inv_payments ip JOIN inv_orders io ON (ip.order_id = io.id) WHERE ip.utr = '$val' AND io.order_date <= '" . date('Y-m-d', strtotime($date)) . "'");

                                                                                        if ($result) {
                                                                                                $account_category_id = '10';
                                                                                                $type = 'supplier';
                                                                                                $type_id = implode(',', array_map('current', array_map('current', $result)));
                                                                                                $is_submitted = '1';

                                                                                                break;
                                                                                        }
                                                                                }
                                                                        }
                                                                }
                                                        }

                                                        $this->User->query("INSERT INTO account_txn_details (bank_id,bank_txn_id,branch_code,pay1_txn_id,txn_status,description,amount,balance,txn_date,submission_date,operation_date,account_category_id,type,type_id,shop_tran_id,user_id,is_submitted) VALUES ('{$_POST['bank']}','$bank_txn_id','$branch_code','$txn_id','$txn_type','$description','$amount','$balance','$date','".date('Y-m-d')."','".date('Y-m-d')."','$account_category_id','$type','$type_id','{$result['shopId']}','{$_SESSION['Auth']['User']['id']}','$is_submitted')");
                                                        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/accounting_module.txt", date('Y-m-d H:i:s') ." :: INSERT INTO account_txn_details (bank_id,bank_txn_id,branch_code,pay1_txn_id,txn_status,description,amount,balance,txn_date,submission_date,operation_date,account_category_id,type,type_id,shop_tran_id,user_id,is_submitted) VALUES ('{$_POST['bank']}','$bank_txn_id','$branch_code','$txn_id','$txn_type','$description','$amount','$balance','$date','".date('Y-m-d')."','".date('Y-m-d')."','$account_category_id','$type','$type_id','{$result['shopId']}','{$_SESSION['Auth']['User']['id']}','$is_submitted')");

                                                        $res = array('bank_txn_id'=>$bank_txn_id, 'branch_code'=>$branch_code, 'txn_id'=>$txn_id, 'txn_date'=>$date, 'operation_date'=>date('Y-m-d'), 'txn_type'=>$txn_type, 'description'=>$description, 'amount'=>$amount);

                                                        $priority_flag = 0;

                                                        if (!isset($requests)) {
                                                                $limit_requests = array_map('current', $this->Limits->query("SELECT transid, mobile FROM limits WHERE date = '".date('Y-m-d', strtotime($date))."' AND showFlag = 'Y'"));
                                                                foreach ($limit_requests as $lr) {
                                                                        $lr['transid'] != '' && $transid = explode('_', $lr['transid']);
                                                                        if (is_numeric(str_replace('M', '', str_replace('m', '', $transid[1]))) && strlen(trim($transid[1])) > 5) {
                                                                                $requests['transid'][] = trim($transid[1]);
                                                                        }
                                                                        $requests['mobile'][] = $lr['mobile'];
                                                                }
                                                        }

                                                        foreach ($requests['transid'] as $rt) {
                                                                if (strpos($description, $rt) !== false) {
                                                                        $priority_flag = 1;
                                                                        break;
                                                                }
                                                        }
                                                        if ($priority_flag == 0) {
                                                                $description = explode(' ', str_replace(':', '', $description));

                                                                foreach ($description as $d) {
                                                                        if (strlen($d) == 10 && in_array($d, $requests['mobile'])) {
                                                                                $priority_flag = 1;
                                                                                break;
                                                                        }
                                                                }
                                                        }
                                                        if ($priority_flag == 1) {
                                                                $array_record['priority'][] = $res;
                                                        } else if ($result['status'] == 'success' || $type_id != ''){
                                                                $array_record['success'][]  = $res;
                                                        } else {
                                                                $array_record['fail'][]     = $res;
                                                        }
//                                                        is_numeric($balance) && $closing = $balance;

                                                }
                                        }else{
                                                foreach($var as $val){
                                                        if (preg_match_all("/[0-9]+/", str_replace(array("'", '"'), '', $val), $matches)) {
                                                                if (is_numeric($matches[0][0]) && strlen($matches[0][0]) >= '10') {
                                                                        $match[] = substr($matches[0][0], -4);
                                                                }
                                                        }
                                                }
                                        }
                                }

//                                if (is_numeric($closing)) {
//                                        $date_res = $this->Slaves->query("SELECT 1 FROM bank_closing WHERE date = '".date('Y-m-d', strtotime($date))."' AND bank_id = '{$_POST['bank']}'");
//                                        if (empty($date_res)) {
//                                                $this->User->query("INSERT INTO bank_closing (date,bank_id,closing) VALUES ('".date('Y-m-d', strtotime($date))."','{$_POST['bank']}','$closing')");
//                                        } else {
//                                                $this->User->query("UPDATE bank_closing SET closing = '$closing' WHERE date = '".date('Y-m-d', strtotime($date))."' AND bank_id = '{$_POST['bank']}'");
//                                        }
//                                }
            }
        } else {

            $txn_type_cond = " AND txn_status = '$cd_type' ";
            $txn_data = $this->Slaves->query("SELECT atd . *,bd.bank_name FROM account_txn_details atd JOIN bank_details bd ON (atd.bank_id = bd.id) WHERE DATE(txn_date) >= '" . date('Y-m-d', strtotime($from_date)) . "' AND DATE(txn_date) <='" . date('Y-m-d', strtotime($to_date)) . "' $bank_cond $txn_type_cond AND (is_submitted = '0' OR pay1_txn_id IN ($txn_id)) ORDER BY atd.id DESC");
            $limit_requests = array_map('current', $this->Limits->query("SELECT transid, mobile, amount FROM limits WHERE date >= '" . date('Y-m-d', strtotime($from_date)) . "' AND date <= '" . date('Y-m-d', strtotime($to_date)) . "' AND showFlag = 'Y'"));
            foreach ($limit_requests as $lr) {
                $lr['transid'] != '' && $transid = explode('_', $lr['transid']);
                if (is_numeric(str_replace('M', '', str_replace('m', '', $transid[1]))) && strlen(trim($transid[1])) > 5) {
                    $requests['transid'][] = trim($transid[1]);
                }
                $requests['mobile'][] = $lr['mobile'];
            }

            foreach ($limit_requests as $lr) {
                $temp_bankcode1 = explode('_', $lr['transid']);
                $temp_bankcode2 = explode(':', $temp_bankcode1[0]);
                $limit_count[$limit_bank[$temp_bankcode2[1]]][$lr['amount']] += 1;
            }
            $this->set('limit_count', $limit_count);

            foreach ($txn_data as $txn) {
                $res = array('bank_id' => $txn['atd']['bank_id'], 'bank_name' => $txn['bd']['bank_name'], 'bank_txn_id' => $txn['atd']['bank_txn_id'], 'branch_code' => $txn['atd']['branch_code'], 'txn_id' => $txn['atd']['pay1_txn_id'], 'txn_date' => $txn['atd']['txn_date'], 'operation_date' => $txn['atd']['operation_date'], 'txn_type' => $txn['atd']['txn_status'], 'description' => $txn['atd']['description'], 'amount' => $txn['atd']['amount']);
                $priority_flag = 0;

                foreach ($requests['transid'] as $rt) {
                    if (strpos($txn['atd']['description'], $rt) !== false) {
                        $priority_flag = 1;
                        break;
                    }
                }

                if ($priority_flag == 0) {
                    $description = explode(' ', str_replace(':', '', $txn['atd']['description']));

                    foreach ($description as $d) {
                        if (strlen($d) == 10 && in_array($d, $requests['mobile'])) {
                            $priority_flag = 1;
                            break;
                        }
                    }
                }

                if ($priority_flag == 1 && $txn['atd']['is_submitted'] == 0) {
                    $array_record['priority'][] = $res;
                } else if ($txn['atd']['is_submitted'] == 1) {
                    $array_record['success'][] = $res;
                } else {
                    $array_record['fail'][] = $res;
                }
            }

            $this->set('sel_from_date', $from_date);
            $this->set('sel_to_date', $to_date);
            $this->set('sel_bank', $bank);
            $this->set('sel_txn_type', $cd_type);
            $this->set('banks', $banks);
        }

        if (empty($array_record) && $this->RequestHandler->isPost()) {

            if (empty($check_unique)) {
                $this->Session->setFlash("* Invalid File for Selected Bank !!!");
            } else {
                $this->Session->setFlash("* All Txns are already Registered !!!");
            }

            $this->redirect('/accounting/txnUpload');
        } else {
            $this->set('array_record', $array_record);
        }
    }

    function deleteTxn($txn_id = 0, $from_date = 0, $bank = 0, $limit_auto = 0, $user = '0') {

        if (in_array($_SESSION['Auth']['User']['mobile'], array('9029916044', '7208207549', '9833258509'))) {
            $date = ($from_date == 0) ? date('Y-m-d') : $from_date;
            $bank != 0 && $bank_cond = " AND bank_id = '" . $bank . "'";
            $banks = array_map(function($value) {
                return array('id' => $value['bank_details']['id'], 'name' => $value[0]['name']);
            }, $this->Slaves->query("SELECT id, concat(bank_name,' - ',account_no) name FROM bank_details WHERE account_no != '' ORDER BY id DESC"));

            if ($user > 0) {
                $user_cond = " AND atd.user_id = '$user' ";
            }

            $txn_data = $this->Slaves->query("SELECT atd . *,bd.bank_name FROM account_txn_details atd JOIN users ON (atd.user_id = users.id) JOIN bank_details bd ON (atd.bank_id = bd.id) WHERE DATE(txn_date) = '" . date('Y-m-d', strtotime($date)) . "'  $bank_cond $user_cond ORDER BY atd.id DESC");

            $users = array_map(function($value) {
                return array('id' => $value['account_txn_details']['user_id'], 'name' => $value['users']['name']);
            }, $this->Slaves->query("SELECT DISTINCT (user_id), users.name FROM
                        `account_txn_details` JOIN users ON users.id = account_txn_details.user_id"));

            if ($this->params['form']['delete']) {
                if ($this->params['form']['ids'][0] == 'on') {
                    unset($this->params['form']['ids'][0]);
                }
                $result = $this->User->query("DELETE FROM account_txn_details WHERE id IN(" . implode(",", $this->params['form']['ids']) . ")");
                echo $result ? '1' : '0';
                $this->autoRender = false;
            }

            $this->set('txn_data', $txn_data);
            $this->set('dates', $date);
            $this->set('users', $users);
            $this->set('sel_bank', $bank);
            $this->set('sel_user', $user);
            $this->set('banks', $banks);
        } else {
            $this->autoRender = false;
        }
    }

    function amountTransfer($mobile_no, $group_id, $amount, $txn_id, $shops_controller, $typeRadio = 1, $password = '') {

                if ($group_id == DISTRIBUTOR) {

                        $dist_details = $this->Slaves->query("SELECT distributors.id,distributors.user_id,distributors.margin,distributors.parent_id,distributors.commission_type,master_distributors.id,master_distributors.user_id FROM distributors JOIN master_distributors ON distributors.parent_id = master_distributors.id WHERE distributors.mobile = '$mobile_no' AND master_distributors.id IN (".MDISTS.")");

                        if ($dist_details) {

                                $master_distdata = $this->Slaves->query("SELECT * FROM users JOIN user_groups ON (users.id = user_groups.user_id AND user_groups.group_id = '".MASTER_DISTRIBUTOR."') WHERE users.id = '".$dist_details[0]['master_distributors']['user_id']."'");

                                if ($master_distdata) {

                                        $info = $this->Shop->getShopData($master_distdata[0]['users']['id'], $master_distdata[0]['user_groups']['group_id']);
                                        $info['User']['group_id'] = MASTER_DISTRIBUTOR;
                                        $info['User']['id']       = $master_distdata[0]['users']['id'];
                                        $info['User']['mobile']   = $master_distdata[0]['users']['mobile'];

                                        $param['amount']          = intval($amount);
                                        $param['commission_type'] = $dist_details[0]['distributors']['commission_type'];
                                        $param['margin']          = ($param['commission_type'] == 0) ? round($amount*$dist_details[0]['distributors']['margin']/100,2) : 0;

                                        $param['type_id']         = DISTRIBUTOR;
                                        $param['retailer']        = $dist_details[0]['distributors']['id'];
                                        $param['typeRadio']       = $typeRadio;
                                        $param['app_flag']        = 2;
                                        $param['txnId']           = $txn_id;
                                        $param['passwd']          = $password;

                                        $param['axis_exception']  = 1;

                                        $result = $shops_controller->amountTransfer($param, $info);
                                }
                        }
                } else if ($group_id == RETAILER) {

                        $get_retailer_info = $this->Slaves->query("SELECT retailers.id,retailers.user_id,retailers.parent_id,distributors.id,distributors.user_id FROM retailers JOIN distributors ON retailers.parent_id = distributors.id WHERE retailers.mobile = '$mobile_no' AND distributors.id IN (".DISTS.")");

                        if ($get_retailer_info) {

                                $dist_data = $this->Slaves->query("SELECT * from users JOIN user_groups ON (users.id = user_groups.user_id AND user_groups.group_id = ".DISTRIBUTOR.") where users.id = '" . $get_retailer_info[0]['distributors']['user_id'] . "'");

                                if ($dist_data) {

                                        $info = $this->Shop->getShopData($dist_data[0]['users']['id'], $dist_data[0]['user_groups']['group_id']);
                                        $info['User']['group_id'] = $dist_data[0]['user_groups']['group_id'];
                                        $info['User']['id']       = $dist_data[0]['users']['id'];
                                        $info['User']['mobile']   = $dist_data[0]['users']['mobile'];

                                        $param['amount']    = intval($amount);
                                        $param['retailer']  = $get_retailer_info[0]['retailers']['id'];
                                        $param['typeRadio'] = $typeRadio;
                                        $param['app_flag']  = 2;
                                        $param['txnId']     = $txn_id;

                                        $result = $shops_controller->amountTransfer($param, $info);

                                }
                        }
                } else if ($group_id == SUPER_DISTRIBUTOR) {

                        $superdist_details = $this->Slaves->query("SELECT sd.id, sd.user_id FROM super_distributors sd JOIN users ON (sd.user_id = users.id) WHERE users.mobile = '$mobile_no'");

                        if ($superdist_details) {

                                $master_distdata = $this->Slaves->query("SELECT * FROM users JOIN user_groups ON (users.id = user_groups.user_id AND user_groups.group_id = '".MASTER_DISTRIBUTOR."') WHERE users.id = '1'");

                                if ($master_distdata) {

                                        $info = $this->Shop->getShopData($master_distdata[0]['users']['id'], $master_distdata[0]['user_groups']['group_id']);
                                        $info['User']['group_id'] = MASTER_DISTRIBUTOR;
                                        $info['User']['id']       = $master_distdata[0]['users']['id'];
                                        $info['User']['mobile']   = $master_distdata[0]['users']['mobile'];

                                        $param['amount']          = intval($amount);
                                        $param['margin']          = 0;

                                        $param['type_id']         = SUPER_DISTRIBUTOR;
                                        $param['retailer']        = $superdist_details[0]['sd']['id'];
                                        $param['typeRadio']       = $typeRadio;
                                        $param['app_flag']        = 2;
                                        $param['txnId']           = $txn_id;
                                        $param['passwd']          = $password;

                                        $result = $shops_controller->amountTransfer($param, $info);

                                }
                        }
                }

                return $result;
        }

    function accountSpecificTxn($txn_ids, $txn_type) {

        $data = array();
        $temp_category = array();
        $data['subcategories'] = array_map('current', $this->Slaves->query("SELECT id, category, subcategory FROM accounting_categories WHERE txn_type = '$txn_type' AND is_active = 1 ORDER BY category, subcategory ASC"));
        foreach ($data['subcategories'] as $category) {
            !in_array($category['category'], $temp_category) && $data['categories'][] = $category;
            !in_array($category['category'], $temp_category) && $temp_category[] = $category['category'];
        }
        $data['vendor'] = array_map('current', $this->Slaves->query("SELECT id,name from product_vendors"));

        $data['type'] = array(0 => array('id' => 'distributor', 'name' => 'Distributor'), 1 => array('id' => 'retailer', 'name' => 'Retailer'), 2 => array('id'=>'superdistributor','name'=>'Super Distributor'));
        $data['distributor'] = array_map(function($value) {
            return array('id' => $value['distributors']['id'], 'name' => trim($value['distributors']['company']) != '' ? $value['distributors']['id'] . ' : ' . trim($value['distributors']['company']) . ' - ' . $value['distributors']['mobile'] : $value['distributors']['mobile']);
        }, $this->Slaves->query("SELECT id, company, mobile FROM distributors WHERE active_flag = 1 ORDER BY id ASC"));
//                $data['retailer']     = array_map(function($value) { return array('id'=>$value['retailers']['id'], 'name'=>trim($value['retailers']['shopname']) != '' ? $value['retailers']['id'].' : '.trim(str_replace('\n', '', $value['retailers']['shopname'])).' - '.$value['retailers']['mobile'] : $value['retailers']['mobile']); }, $this->Slaves->query("SELECT id, shopname, mobile FROM retailers WHERE parent_id = 1 AND toshow = 1 ORDER BY id ASC"));
        $data['supplier'] = array_map('current', $this->Slaves->query("SELECT id, name FROM inv_suppliers ORDER BY id DESC"));
        $data['bank_account'] = array_map(function($value) {
            return array('id' => $value['bank_details']['id'], 'name' => $value[0]['name']);
        }, $this->Slaves->query("SELECT id, concat(bank_name,' - ',account_no) name FROM bank_details WHERE account_no != '' ORDER BY id DESC"));

        $discount_raw = $this->Slaves->query("SELECT id, margin FROM distributors d WHERE active_flag = 1 ORDER BY id DESC");
        $discount = array();
        foreach ($discount_raw as $dr) {
            $discount[$dr['d']['id']] = $dr['d']['margin'];
        }

        $txn_id = explode(',', $txn_ids);
        $txn_details = $this->Slaves->query("SELECT bd.bank_name, atd.pay1_txn_id, atd.txn_status, atd.description, atd.amount, atd.txn_date FROM account_txn_details atd JOIN bank_details bd ON (atd.bank_id = bd.id) WHERE atd.pay1_txn_id IN ('" . implode("','", $txn_id) . "')");

        $description = explode(' ', str_replace(':', '', $txn_details[0]['atd']['description']));
        foreach ($description as $desp) {
            if (strlen($desp) == 10) {
                $user_details = $this->Slaves->query("SELECT * FROM users u JOIN user_groups ug ON (u.id = ug.user_id) WHERE mobile = '$desp'");
                if ($user_details[0]['ug']['group_id'] == DISTRIBUTOR) {
                    $auto_details['role'] = 'distributor';
                    $auto_details['details'] = $this->Slaves->query("SELECT id, company name, mobile FROM distributors u WHERE mobile = '$desp' AND active_flag = 1");
                } else if ($user_details[0]['ug']['group_id'] == RETAILER) {
                    $auto_details['role'] = 'retailer';
                    $auto_details['details'] = $this->Slaves->query("SELECT id, shopname name, mobile FROM retailers u WHERE mobile = '$desp' AND parent_id = 1 AND toshow = 1");
                } else if ($user_details[0]['ug']['group_id'] == SUPER_DISTRIBUTOR) {
                        $auto_details['role']    = 'superdistributor';

                        $sd = $this->Slaves->query("SELECT super_distributors.id, super_distributors.user_id, users.mobile FROM super_distributors JOIN users ON (super_distributors.user_id = users.id) WHERE users.mobile = '$desp' AND users.active_flag = 1");
                        $sd_imp_data = $this->Shop->getUserLabelData($sd[0]['super_distributors']['user_id']);

                        $auto_details['details'][0]['u']['id']     = $sd[0]['super_distributors']['id'];
                        $auto_details['details'][0]['u']['name']   = $sd_imp_data[$sd[0]['super_distributors']['user_id']]['imp']['shop_est_name'];
                        $auto_details['details'][0]['u']['mobile'] = $sd[0]['users']['mobile'];
                }
                break;
            }
        }

                if (empty($auto_details['details']) && (strpos($description, 'MMT') !== false || strpos($description, 'UPI') !== false)) {
                        $description = explode('/', str_replace('@ybl', '', str_replace('@upi', '', $txn_details[0]['atd']['description'])));
                        foreach ($description as $desp) {
                                if (strlen($desp) == 10) {
                                        $user_details = $this->Slaves->query("SELECT * FROM users u JOIN user_groups ug ON (u.id = ug.user_id) WHERE mobile = '$desp'");
                                        if($user_details[0]['ug']['group_id'] == DISTRIBUTOR) {
                                                $auto_details['role']    = 'distributor';
                                                $auto_details['details'] = $this->Slaves->query("SELECT id, company name, mobile FROM distributors u WHERE mobile = '$desp' AND active_flag = 1");
                                        } else if ($user_details[0]['ug']['group_id'] == RETAILER) {
                                                $auto_details['role']    = 'retailer';
                                                $auto_details['details'] = $this->Slaves->query("SELECT id, shopname name, mobile FROM retailers u WHERE mobile = '$desp' AND parent_id = 1 AND toshow = 1");
                                        } else if ($user_details[0]['ug']['group_id'] == SUPER_DISTRIBUTOR) {
                                                $auto_details['role']    = 'superdistributor';

                                                $sd = $this->Slaves->query("SELECT super_distributors.id, super_distributors.user_id, users.mobile FROM super_distributors JOIN users ON (super_distributors.user_id = users.id) WHERE users.mobile = '$desp' AND users.active_flag = 1");
                                                $sd_imp_data = $this->Shop->getUserLabelData($sd[0]['super_distributors']['user_id']);

                                                $auto_details['details'][0]['u']['id']     = $sd[0]['super_distributors']['id'];
                                                $auto_details['details'][0]['u']['name']   = $sd_imp_data[$sd[0]['super_distributors']['user_id']]['imp']['shop_est_name'];
                                                $auto_details['details'][0]['u']['mobile'] = $sd[0]['users']['mobile'];
                                        }
                                        break;
                                }
                        }
                }
                $this->set('auto_details', $auto_details);

                if (!$auto_details) {
                        $limit_requests = array_map('current', $this->Limits->query("SELECT transid, dist_id, super_dist_id, dist_type, mobile FROM limits WHERE date = '".date('Y-m-d', strtotime($txn_details[0]['atd']['txn_date']))."' AND showFlag = 'Y'"));
                        foreach ($limit_requests as $lr) {
                                $lr['transid'] != '' && $transid = explode('_', $lr['transid']);
                                if (is_numeric(str_replace('M', '', str_replace('m', '', $transid[1]))) && strlen(trim($transid[1])) > 5 && strpos($txn_details[0]['atd']['description'], trim($transid[1])) !== false) {
                                        $lead_sel['type']    = strtolower($lr['dist_type']);
                                        $lead_sel['type_id'] = $lr['dist_id'] != NULL ? $lr['dist_id'] : $lr['super_dist_id'];
                                        $this->set('lead_sel', $lead_sel);
                                        break;
                                } else if (strpos($txn_details[0]['atd']['description'], $lr['mobile']) !== false) {
                                        $lead_sel['type']    = strtolower($lr['dist_type']);
                                        $lead_sel['type_id'] = $lr['dist_id'] != NULL ? $lr['dist_id'] : $lr['super_dist_id'];
                                        $this->set('lead_sel', $lead_sel);
                                        break;
                                }
                        }
                }

        if (!$auto_details && !$lead_sel) {
            $bank = explode(':', $txn_details[0]['bd']['bank_name']);
            $limits = array_map('current', $this->Limits->query("SELECT id, dist_type, dist_name, mobile, created_on, dist_id, bank_details FROM limits WHERE date = '" . date('Y-m-d', strtotime($txn_details[0]['atd']['txn_date'])) . "' AND showFlag = 'Y' AND bank_name LIKE '%{$bank[1]}%' AND amount = '".round($txn_details[0]['atd']['amount'])."'"));
            foreach ($limits as $limit) {
                $dist_ids[] = $limit['dist_id'];
            }
            $this->set('limits', $limits);

            $discounts = $this->Slaves->query("SELECT id, margin FROM distributors d WHERE id IN (" . implode(',', $dist_ids) . ") AND active_flag = 1");
            foreach ($discounts as $d) {
                $margins[$d['d']['id']] = $d['d']['margin'];
            }
            $this->set('margins', $margins);
        }

        $data['view'] = array(
            '1' => array(
                'fields' => array(
                    array('label' => 'Type', 'input' => 'dropdown', 'field' => 'type')
                )
            ),
            '2' => array(
                'fields' => array(
                    array('label' => 'Supplier', 'input' => 'dropdown', 'field' => 'supplier'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '3' => array(
                'fields' => array(
                    array('label' => 'Bank Account', 'input' => 'dropdown', 'field' => 'bank_account'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '9' => array(
                'fields' => array(
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '10' => array(
                'fields' => array(
                    array('label' => 'Supplier', 'input' => 'dropdown', 'field' => 'supplier'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '11' => array(
                'fields' => array(
                    array('label' => 'Bank Account', 'input' => 'dropdown', 'field' => 'bank_account'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '51' => array(
                'fields' => array(
                    array('label' => 'Distributor', 'input' => 'dropdown', 'field' => 'distributor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '52' => array(
                'fields' => array(
                    array('label' => 'Type', 'input' => 'dropdown', 'field' => 'type')
                )
            ),
            '53' => array(
                'fields' => array(
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '55' => array(
                'fields' => array(
                    array('label' => 'Txn ID', 'input' => 'text', 'field' => 'bank_txn_id'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '56' => array(
                'fields' => array(
                    array('label' => 'Txn ID', 'input' => 'text', 'field' => 'bank_txn_id'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '105' => array(
                'fields' => array(
                    array('label' => 'Vendor', 'input' => 'dropdown', 'field' => 'vendor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '106' => array(
                'fields' => array(
                    array('label' => 'Vendor', 'input' => 'dropdown', 'field' => 'vendor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '107' => array(
                'fields' => array(
                    array('label' => 'Vendor', 'input' => 'dropdown', 'field' => 'vendor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
           '108' => array(
                'fields' => array(
                    array('label' => 'Vendor', 'input' => 'dropdown', 'field' => 'vendor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '109' => array(
                'fields' => array(
                    array('label' => 'Vendor', 'input' => 'dropdown', 'field' => 'vendor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '110' => array(
                'fields' => array(
                    array('label' => 'Vendor', 'input' => 'dropdown', 'field' => 'vendor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '117' => array(
                'fields' => array(
                    array('label' => 'Vendor', 'input' => 'dropdown', 'field' => 'vendor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '118' => array(
                'fields' => array(
                    array('label' => 'Vendor', 'input' => 'dropdown', 'field' => 'vendor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '119' => array(
                'fields' => array(
                    array('label' => 'Vendor', 'input' => 'dropdown', 'field' => 'vendor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            ),
            '120' => array(
                'fields' => array(
                    array('label' => 'Vendor', 'input' => 'dropdown', 'field' => 'vendor'),
                    array('label' => 'Narration', 'input' => 'textarea', 'field' => 'narration')
                )
            )
        );

        $this->set('data', $data);
        $this->set('txn_details', $txn_details);
        $this->set('discount', $discount);
    }

    function clearNonclearedTxn() {

        $txn_ids = $_POST['txn_id'];
        $category = is_numeric($_POST['category']) ? $_POST['category'] : '';
        $type = is_numeric($_POST['type_id']) ? $_POST['type'] : '';
        $type_id = is_numeric($_POST['type_id']) ? $_POST['type_id'] : '';
        $narration = $_POST['narration'];
        $refund = $_POST['bank_txn_id'];

//                if (count(explode(',', $txn_ids)) > 10) {
//                        $this->Session->setFlash("* Operation Failed. You can select maximum 10 txns at a time !!!");
//                        $this->redirect('/accounting/autoUpload');
//                }

        foreach (explode(',', $txn_ids) as $txn_id) {

            $txn_details = $this->User->query("SELECT bank_id, txn_status, amount, txn_date, operation_date, is_submitted FROM account_txn_details atd WHERE atd.pay1_txn_id = '$txn_id'");

            if ($category < 1 || $narration == '') {
                $this->Session->setFlash("* Operation Failed. Some Fields were Empty !!!");
                $this->redirect($_SERVER['HTTP_REFERER']);
            } else if ($txn_details[0]['atd']['is_submitted'] == 1) {
                $this->Session->setFlash("* Txn Already Processed !!!");
                $this->redirect($_SERVER['HTTP_REFERER']);
            }

            App::import('Controller', 'Shops');
            $shops_controller = new ShopsController;
            $shops_controller->constructClasses();

            $result = array();

            if ($category == 1) {
                    $lock = $this->Shop->addMemcache("limit_".$txn_id, 1, 24*60*60);
//                    $_SERVER['SERVER_NAME'] == 'cc.pay1.com' && $lock = 1;

                    $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/accounting_module.txt", " $txn_id => " . json_encode($lock));
                    $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/accounting_module.txt", " $txn_id => " . json_encode($_POST));

                    if ($lock === false) {
                            $limit_details = $this->Shop->getMemcache("limit_" . $txn_id);
                            if ($limit_details['category']) {
                                    $this->User->query("UPDATE account_txn_details SET account_category_id='" . $limit_details['category'] . "', type='" . $limit_details['type'] . "', type_id='" . $limit_details['type_id'] . "', refund='" . $limit_details['refund'] . "', narration='" . $limit_details['narration'] . "', shop_tran_id='" . $limit_details['shop_id'] . "', submission_date='" . date('Y-m-d') . "', operation_date='" . date('Y-m-d') . "', user_id='" . $limit_details['user_id'] . "', is_submitted='1' WHERE pay1_txn_id = '$txn_id'");
                                    $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/accounting_module.txt", " $txn_id => UPDATE account_txn_details SET account_category_id='" . $limit_details['category'] . "', type='" . $limit_details['type'] . "', type_id='" . $limit_details['type_id'] . "', refund='" . $limit_details['refund'] . "', narration='" . $limit_details['narration'] . "', shop_tran_id='" . $limit_details['shop_id'] . "', submission_date='" . date('Y-m-d') . "', operation_date='" . date('Y-m-d') . "', user_id='" . $limit_details['user_id'] . "', is_submitted='1' WHERE pay1_txn_id = '$txn_id'");
                            }

                            $this->Session->setFlash("* Txn Already Processed !!!");
                            $this->redirect($_SERVER['HTTP_REFERER']);
                    }

                    if ($type_id == '') {
                            $this->Shop->delMemcache("limit_" . $txn_id);
                            $this->Session->setFlash("* Operation Failed. Some Fields were Empty !!!");
                            $this->redirect($_SERVER['HTTP_REFERER']);
                    }

                $og_amt = $txn_details[0]['atd']['amount'];
                $amount = (count(explode(',', $txn_ids)) == 1 && isset($_POST['amount'])) ? (double) $_POST['amount'] : $og_amt;
                $og_amt > $amount && $refund = $og_amt - $amount;

                if ($amount > 0) {
                        if($type == 'distributor'){

                                $group_id = DISTRIBUTOR;

                                $role_details = $this->User->query("SELECT mobile FROM distributors WHERE id = '$type_id'");

                                $mobile_no = $role_details[0]['distributors']['mobile'];

                        } else if($type == 'retailer'){

                                $group_id = RETAILER;

                                $role_details = $this->User->query("SELECT mobile FROM retailers WHERE id = '$type_id'");

                                $mobile_no = $role_details[0]['retailers']['mobile'];

                        } else if($type == 'superdistributor') {

                                $group_id = SUPER_DISTRIBUTOR;

                                $role_details = $this->User->query("SELECT users.mobile FROM users JOIN super_distributors ON (users.id = super_distributors.user_id) WHERE super_distributors.id = '$type_id'");

                                $mobile_no = $role_details[0]['users']['mobile'];

                        }

                    $typeRadio = $_POST['typeRadio'];
                    $password = $_POST['password'];

                    $result = $this->amountTransfer($mobile_no, $group_id, $amount, $txn_id, $shops_controller, $typeRadio, $password);

                    $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/accounting_module.txt", " $txn_id => " . json_encode($result));

                    if ($result['shopId']) {
                        $narration = $_POST['mode'] . " - " . $_POST['narration'];

                        $this->User->query("UPDATE account_txn_details SET account_category_id='$category', type='$type', type_id='$type_id', refund='$refund', narration='$narration', shop_tran_id='".$result['shopId']."', submission_date='" . date('Y-m-d') . "', operation_date='" . date('Y-m-d') . "', user_id='{$_SESSION['Auth']['User']['id']}', is_submitted = '1' WHERE pay1_txn_id = '$txn_id'");

                        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/accounting_module.txt", " $txn_id => UPDATE account_txn_details SET account_category_id='$category', type='$type', type_id='$type_id', refund='$refund', narration='$narration', shop_tran_id='".$result['shopId']."', submission_date='" . date('Y-m-d') . "', operation_date='" . date('Y-m-d') . "', user_id='{$_SESSION['Auth']['User']['id']}', is_submitted = '1' WHERE pay1_txn_id = '$txn_id'");

                        if ($_POST['mode'] == 'By SMS') {
                            $this->Limits->query("UPDATE limits SET modified_on = '" . date('YmdHis') . "', showFlag = 'N' WHERE date = '" . date('Y-m-d', strtotime($txn_details[0]['atd']['txn_date'])) . "' AND mobile = '$mobile_no' AND amount = '$og_amt' AND showFlag = 'Y'");
                        }
                    } else if ($result['status'] == 'failure') {
                        $this->Shop->delMemcache("limit_" . $txn_id);
                        $this->Session->setFlash("* " . $result['description'] . " !!!");
                        $this->redirect($_SERVER['HTTP_REFERER']);
                    }
                }

                $this->Shop->setMemcache("limit_" . $txn_id, array('category' => $category, 'type' => $type, 'type_id' => $type_id, 'refund' => $refund, 'narration' => $narration, 'shop_id' => $result['shopId'], 'user_id' => $_SESSION['Auth']['User']['id']), 24 * 60 * 60);
            } else if ($category == 56) {

                if ($refund == '') {
                    $this->Session->setFlash("* Operation Failed. Some Fields were Empty !!!");
                    $this->redirect($_SERVER['HTTP_REFERER']);
                }

                $refund_details = $this->User->query("SELECT atd.*, st.target_id, st.type, st.amount FROM account_txn_details atd JOIN shop_transactions st ON (atd.shop_tran_id = st.id) WHERE pay1_txn_id = '" . trim($refund) . "' AND account_category_id = '1'");

                if ($txn_details[0]['atd']['amount'] != ($refund_details[0]['atd']['amount'] - $refund_details[0]['atd']['refund'])) {
                    $this->Session->setFlash("* Cannot Pullback Txn. Amount Mismatch !!!");
                    $this->redirect($_SERVER['HTTP_REFERER']);
                }

                if ($txn_details[0]['atd']['bank_id'] != $refund_details[0]['atd']['bank_id']) {
                    $this->Session->setFlash("* Cannot Pullback Txn. Wrong Bank Txn !!!");
                    $this->redirect($_SERVER['HTTP_REFERER']);
                }

                $params = array();
                $params['shop_transid'] = $refund_details[0]['atd']['shop_tran_id'];

                if ($refund_details[0]['atd']['type'] == 'distributor') {

                        $dist_details  = $this->User->query("SELECT md.id, md.user_id FROM distributors JOIN master_distributors md ON (distributors.parent_id = md.id) WHERE distributors.id = '{$refund_details[0]['atd']['type_id']}'");
                        $params['user']['id']       = $dist_details[0]['md']['id'];
                        $params['user']['user_id']  = $dist_details[0]['md']['user_id'];

                        $params['request_from']     = 'accounts_masterdistributor';

                        $balance = $this->User->query("SELECT users.balance FROM distributors d JOIN users ON (d.user_id = users.id) WHERE d.id = '{$refund_details[0]['st']['target_id']}'");

                } else if ($refund_details[0]['atd']['type'] == 'retailer') {

                        $salesman_txn = $this->User->query("SELECT id FROM salesman_transactions sst WHERE shop_tran_id = '{$refund_details[0]['atd']['shop_tran_id']}'");
                        $params['salesman_transid'] = $salesman_txn[0]['sst']['id'];

                        $ret_details  = $this->User->query("SELECT distributors.id, distributors.user_id FROM retailers JOIN distributors ON (retailers.parent_id = distributors.id) WHERE retailers.id = '{$refund_details[0]['atd']['type_id']}'");
                        $params['user']['id']       = $ret_details[0]['distributors']['id'];
                        $params['user']['user_id']  = $ret_details[0]['distributors']['user_id'];

                        $params['request_from']     = 'accounts_distributor';

                        $balance = $this->User->query("SELECT users.balance FROM retailers r JOIN users ON (r.user_id = users.id) WHERE r.id = '{$refund_details[0]['st']['target_id']}'");

                } else if ($refund_details[0]['atd']['type'] == 'superdistributor') {

                        $dist_details  = $this->User->query("SELECT sd.id, sd.user_id FROM distributors JOIN super_distributors sd ON (distributors.sd_id = sd.id) WHERE distributors.id = '{$refund_details[0]['atd']['type_id']}'");
                        $params['user']['id']       = $dist_details[0]['sd']['id'];
                        $params['user']['user_id']  = $dist_details[0]['sd']['user_id'];

                        $params['request_from']     = 'accounts_masterdistributor';

                        $balance = $this->User->query("SELECT users.balance FROM distributors d JOIN users ON (d.user_id = users.id) WHERE d.id = '{$refund_details[0]['st']['target_id']}'");

                }

                if ($balance[0]['users']['balance'] < $refund_details[0]['st']['amount']) {
                        $this->User->query("UPDATE shop_transactions SET confirm_flag = '3' WHERE id = '{$refund_details[0]['atd']['shop_tran_id']}'");
                }

                $result = $shops_controller->pullback($params);

                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/accounting_module.txt", " $txn_id => " . json_encode($result));

                if ($result['status'] == 'failure') {
                        $this->Session->setFlash("* " . $result['description'] . " !!!");
                        $this->redirect($_SERVER['HTTP_REFERER']);
                }

                $this->User->query("UPDATE account_txn_details SET account_category_id='$category', type='$type', type_id='$type_id', narration='$narration', submission_date='" . date('Y-m-d') . "', operation_date='" . date('Y-m-d') . "', user_id='{$_SESSION['Auth']['User']['id']}', is_submitted='1' WHERE pay1_txn_id = '$txn_id'");

                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/accounting_module.txt", " $txn_id => UPDATE account_txn_details SET account_category_id='$category', type='$type', type_id='$type_id', narration='$narration', submission_date='" . date('Y-m-d') . "', operation_date='" . date('Y-m-d') . "', user_id='{$_SESSION['Auth']['User']['id']}', is_submitted='1' WHERE pay1_txn_id = '$txn_id'");
            } else {

                    $this->User->query("UPDATE account_txn_details SET account_category_id='$category', type='$type', type_id='$type_id', narration='$narration', submission_date='" . date('Y-m-d') . "', operation_date='" . date('Y-m-d') . "', user_id='{$_SESSION['Auth']['User']['id']}', is_submitted='1' WHERE pay1_txn_id = '$txn_id'");

                    $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/accounting_module.txt", " $txn_id => UPDATE account_txn_details SET account_category_id='$category', type='$type', type_id='$type_id', narration='$narration', submission_date='" . date('Y-m-d') . "', operation_date='" . date('Y-m-d') . "', user_id='{$_SESSION['Auth']['User']['id']}', is_submitted='1' WHERE pay1_txn_id = '$txn_id'");
            }

            if (count(explode(',', $txn_ids)) > 1) {
                sleep(1);
            }
        }

        $this->redirect('/accounting/autoUpload/' . $txn_ids . '/' . date('Y-m-d', strtotime($txn_details[0]['atd']['txn_date'])) . '/' . date('Y-m-d', strtotime($txn_details[0]['atd']['txn_date'])) . '/' . $txn_details[0]['atd']['bank_id'] . '/0/' . $txn_details[0]['atd']['txn_status']);
    }

    function bankTxnListing($from_date = 0, $to_date = 0, $bank = 0, $txn_id = 0, $category_id = 0, $csv = 0) {

        $from_date == 0 && $from_date = date('Y-m-d');

        $to_date == 0 && $to_date = date('Y-m-d');

        $bank != 0 && $bank_cond = " AND atd.bank_id = '$bank' ";

        $txn_id != 0 && $txn_cond = " AND (atd.pay1_txn_id LIKE '%$txn_id%' OR atd.refund LIKE '%$txn_id%') ";

        $category_id != 0 && $category_cond = " AND atd.account_category_id = '$category_id' ";

        $banks = array_map(function($value) {
            return array('id' => $value['bank_details']['id'], 'name' => $value[0]['name']);
        }, $this->Slaves->query("SELECT id, concat(bank_name,' - ',account_no) name FROM bank_details WHERE account_no != '' ORDER BY id DESC"));

        $categories = $this->Slaves->query("SELECT id, txn_type, category, subcategory FROM accounting_categories ac WHERE is_active = 1");

        $result = $this->Slaves->query("SELECT atd.*,concat(bd.bank,' - ',bd.account_no) bank,ac.category,ac.subcategory,concat(d.id,' : ',d.company,' (',d.mobile,')') distributor,concat(r.id,' : ',r.shopname,' (',r.mobile,')') retailer,sd.id superdistributor,is.name supplier,concat(bds.bank,' - ',bds.account_no) receiver_bank, users.name, st.timestamp "
                . "FROM account_txn_details atd "
                . "JOIN bank_details bd ON (bd.id = atd.bank_id) "
                . "LEFT JOIN accounting_categories ac ON (ac.id = atd.account_category_id) "
                . "LEFT JOIN distributors d ON (d.id = atd.type_id) "
                . "LEFT JOIN retailers r ON (r.id = atd.type_id) "
                . "LEFT JOIN super_distributors sd ON (sd.id = atd.type_id) "
                . "LEFT JOIN inv_suppliers `is` ON (is.id = atd.type_id) "
                . "LEFT JOIN bank_details bds ON (bds.id = atd.type_id) "
                . "LEFT JOIN users ON (users.id = atd.user_id) "
                . "LEFT JOIN shop_transactions st ON (st.id = atd.shop_tran_id) "
                . "WHERE DATE(atd.txn_date) >= '$from_date' AND DATE(atd.txn_date) <= '$to_date' $bank_cond $txn_cond $category_cond AND atd.is_submitted = 1 "
                . "ORDER BY atd.id DESC");

        foreach ($result as $res) {
            if ($res['atd']['account_category_id'] == 10) {
                $supplier_orders[] = $res['atd']['type_id'];
            }
        }

        if ($supplier_orders) {
            $suppliers_temp = $this->Slaves->query("SELECT io.id, is.name FROM inv_orders io JOIN inv_suppliers `is` ON (io.supplier_id = is.id) WHERE io.id IN (" . implode(',', $supplier_orders) . ")");
            foreach ($suppliers_temp as $supplier) {
                $suppliers[$supplier['io']['id']] = $supplier['is']['name'];
            }
        }

        if ($csv == 1) {

            $this->autoRender = FALSE;

            App::import('Helper', 'csv');
            $csv = new CsvHelper();

            $line = array("Txn ID", "Bank", "Branch Code", "Bank Txn ID", "Type", "Amount", "Balance", "Limit Processed + Refund", "Txn Date", "Upload Date", "Category", "Sub-Category", "Shop Tran ID", "Timestamp", "Specific", "Reference ID", "Narration", "Action Performed");
            $csv->addRow($line);

            foreach ($result as $res) {
                    $branch_code  = $res['atd']['branch_code'] != '' ? $res['atd']['branch_code'] : '';
                    $bank_txn_id  = $res['atd']['bank_txn_id'] != '' ? $res['atd']['bank_txn_id'] : '';
                    $refund       = $res['atd']['account_category_id'] == 1 ? ($res['atd']['amount'] - $res['atd']['refund']) . ($res['atd']['refund'] ? ' / ' . $res['atd']['refund'] : '') : '';
                    $category     = $res['ac']['category'] == '' ? '' : $res['ac']['category'];
                    $subcategory  = $res['ac']['subcategory'] == '' ? '' : $res['ac']['subcategory'];
                    $shop_tran_id = $res['atd']['shop_tran_id'] == '' ? '' : $res['atd']['shop_tran_id'];
                    $timestamp    = $res['st']['timestamp'] == '' ? '' : $res['st']['timestamp'];
                    $type_id      = array('distributor'=>'Distributor - '.$res[0]['distributor'],'retailer'=>'Retailer - '.$res[0]['retailer'],'supplier'=>'Supplier - '.$res['is']['supplier'],'bank_account'=>$res[0]['receiver_bank'],'superdistributor'=>'Super Distributor - '.$res[0]['superdistributor']);
                    $type         = $res['atd']['type'] != '' ? $type_id[$res['atd']['type']] : '';
                    $reference_id = in_array($res['atd']['account_category_id'], array(55,56)) ? $res['atd']['refund'] : '';
                    $narration    = $res['atd']['narration'] == '' ? '' : $res['atd']['narration'];

                    $line = array($res['atd']['pay1_txn_id'] . "'", $res[0]['bank'], $branch_code, $bank_txn_id, $res['atd']['txn_status'], $res['atd']['amount'], $res['atd']['balance'], $refund, date('Y-m-d', strtotime($res['atd']['txn_date'])), $res['atd']['operation_date'], $category, $subcategory, $shop_tran_id, $timestamp, $type, $reference_id, $narration, $res['users']['name']);
                    $csv->addRow($line);
            }
            echo $csv->render('BankTransactions_' . $from_date . '.csv');
        } else {
            $this->set('sel_from_date', $from_date);
            $this->set('sel_to_date', $to_date);
            $this->set('sel_bank', $bank);
            $this->set('sel_category', $category_id);
            $this->set('banks', $banks);
            $this->set('categories', $categories);
            $this->set('suppliers', $suppliers);
            $this->set('txn_id', $txn_id);
            $this->set('result', $result);
        }
    }

//        function distributorsData(){
//
//        $result=$this->paginate_query("SELECT distributors.* , slabs.name, users.mobile,rm.name FROM distributors JOIN slabs ON (distributors.slab_id = slabs.id) "
//                    . "JOIN users ON (distributors.user_id = users.id) "
//                    . "LEFT JOIN rm ON (distributors.rm_id = rm.id) "
//                    . "WHERE distributors.parent_id = 3 AND distributors.toshow = 1 "
//                    . "GROUP BY distributors.id "
//                    . "ORDER BY distributors.active_flag desc , distributors.company asc");
//        //echo'<pre>'; print_r($data);die;
//
//
//        $this->set('result',$result);
//
//        }

    function showRequest() {

        $this->autoRender = FALSE;

        $txn_details = $this->Slaves->query("SELECT txn_date FROM account_txn_details WHERE pay1_txn_id = '{$_POST['txn_id']}'");

        if($_POST['type'] == 'distributor'){

            $type_details = $this->Slaves->query("SELECT mobile FROM distributors WHERE id = '{$_POST['id']}'");
            $mobile = $type_details[0]['distributors']['mobile'];

        } else if($_POST['type'] == 'retailer'){

            $type_details = $this->Slaves->query("SELECT mobile FROM retailers WHERE id = '{$_POST['id']}'");
            $mobile = $type_details[0]['retailers']['mobile'];

        } else if($_POST['type'] == 'superdistributor'){

            $type_details = $this->Slaves->query("SELECT users.mobile FROM super_distributors sd JOIN users ON (sd.user_id = users.id) WHERE sd.id = '{$_POST['id']}'");
            $mobile = $type_details[0]['users']['mobile'];

        }

        $listing = $this->Limits->query("SELECT id, bank_name, amount, trans_type, STR_TO_DATE(created_on, '%Y%m%d') date, TIME_FORMAT(created_on, '%H:%i:%s') time FROM limits WHERE date = '".date('Y-m-d', strtotime($txn_details[0]['account_txn_details']['txn_date']))."' AND mobile = '{$mobile}' AND showFlag = 'Y'");

        echo json_encode($listing);
    }

    function clearSMSReq() {

        $this->autoRender = FALSE;

        $res = $this->Limits->query("UPDATE limits SET modified_on = '" . date('YmdHis') . "', showFlag = 'N' WHERE id = '{$_POST['id']}'");

        echo $res;
    }

    function closingBalanceReport($date = 0) {

        $date == 0 && $date = date('Y-m-d');

        $bank_accounts = $this->Slaves->query("SELECT id, bank_name FROM bank_details bd WHERE account_no != '' ORDER BY id ASC");
        foreach ($bank_accounts as $bank) {
            $data['banks'][$bank['bd']['id']]['bank_name'] = $bank['bd']['bank_name'];
        }

        $todays_closing = $this->Slaves->query("SELECT bank_id, sum(closing) closing FROM bank_closing WHERE date = '" . date('Y-m-d', strtotime($date)) . "' GROUP BY bank_id");
        $data['todays_closing'] = $data['banks'];
        foreach ($todays_closing as $tc) {
            $data['todays_closing'][$tc['bank_closing']['bank_id']] = $tc[0];
            $data['total_todays_closing'] += $tc[0]['closing'];
        }

        $yesterdays_closing = $this->Slaves->query("SELECT bank_id, sum(closing) closing FROM bank_closing WHERE date = '" . date('Y-m-d', strtotime('-1 day', strtotime($date))) . "' GROUP BY bank_id");
        $data['yesterdays_closing'] = $data['banks'];
        foreach ($yesterdays_closing as $yc) {
            $data['yesterdays_closing'][$yc['bank_closing']['bank_id']] = $yc[0];
            $data['total_closing'] += $yc[0]['closing'];
        }

        $refund = $this->Slaves->query("SELECT bank_id, sum(refund) refund FROM account_txn_details atd WHERE account_category_id = 1 AND DATE(txn_date) = '" . $date . "' GROUP BY atd.bank_id");
        $data['refund'] = $data['banks'];
        foreach ($refund as $r) {
            $data['refund'][$r['atd']['bank_id']]['refund'] = $r[0]['refund'];
            $data['total_refund'] += $r[0]['refund'];
        }
//                $data['data']               = $this->Slaves->query("SELECT ac.*, sum(atd.amount) amount FROM account_txn_details atd LEFT JOIN accounting_categories ac ON (ac.id = atd.account_category_id) WHERE DATE(atd.txn_date) = '".$date."' AND atd.is_submitted = 1 $cond GROUP BY ac.id");
//                $data['limit_next']         = $this->Slaves->query("SELECT sum(amount) amount FROM account_txn_details atd WHERE DATE(atd.txn_date) = '".date('Y-m-d')."' AND atd.operation_date != '".date('Y-m-d')."' AND atd.is_submitted = 0  $cond");

        $category_data = $this->Slaves->query("SELECT ac.*, atd.bank_id, sum(atd.amount) amount FROM account_txn_details atd LEFT JOIN accounting_categories ac ON (ac.id = atd.account_category_id) WHERE DATE(atd.txn_date) = '" . $date . "' AND atd.is_submitted = 1 GROUP BY ac.id, atd.bank_id");
        foreach ($category_data as $cd) {
            !isset($data['category'][$cd['ac']['id']]) && $data['category'][$cd['ac']['id']]['banks'] = $data['banks'];
            $data['category'][$cd['ac']['id']] = array_merge($data['category'][$cd['ac']['id']], $cd['ac']);
            $data['category'][$cd['ac']['id']]['banks'][$cd['atd']['bank_id']] = $cd[0];
            $data['category'][$cd['ac']['id']]['total_' . $cd['ac']['id']] += $cd[0]['amount'];
        }

        $suspense = $this->Slaves->query("SELECT txn_status, bank_id, sum(amount) amount FROM account_txn_details atd WHERE DATE(txn_date) != operation_date AND DATE(txn_date) = '$date' GROUP BY txn_status, bank_id");
        $data['suspense'] = $data['banks'];
        foreach ($suspense as $s) {
            $data['suspense'][$s['atd']['bank_id']][$s['atd']['txn_status']] = $s[0]['amount'];
            $s['atd']['txn_status'] == 'Cr' && $data['credit_suspense'] += $s[0]['amount'];
            $s['atd']['txn_status'] == 'Dr' && $data['debit_suspense'] += $s[0]['amount'];
        }

        $this->set('General', $this->General);
        $this->set('bank', $bank);
        $this->set('date', $date);
        $this->set('data', $data);
    }

    function txnDetails() {

        $this->autoRender = FALSE;

        $bank_txn_id = $_POST['bank_txn_id'];

        $res = $this->Slaves->query("SELECT if(atd.type='distributor', concat('Distributor - ', d.company,' (',d.mobile,')'), concat('Retailer - ', r.shopname,' (',r.mobile,')')) name, st.id, st.amount, st.timestamp FROM account_txn_details atd JOIN shop_transactions st ON (atd.shop_tran_id = st.id) LEFT JOIN distributors d ON (st.target_id = d.id) LEFT JOIN retailers r ON (st.target_id = r.id) WHERE atd.pay1_txn_id = '$bank_txn_id' AND atd.account_category_id = 1 AND st.confirm_flag = 0");

        echo json_encode($res[0]);
    }

    function limitReconsilationReport($date = '') {

        $date = $date == '' ? date('Y-m-d') : $date;

        $data = array();

        $data['incoming'] = $this->Slaves->query("SELECT DATE(txn_date) txn_date, SUM(amount) amount FROM account_txn_details WHERE account_category_id = 1 AND operation_date = '$date' AND shop_tran_id != 0 AND is_submitted = 1 GROUP BY DATE(txn_date)");

        $trans_1 = $this->Slaves->query("SELECT SUM(st.amount) amount FROM shop_transactions st WHERE st.confirm_flag = 0 AND st.type IN (1,6) AND st.date = '$date'");
        $data['primary'] = $trans_1[0][0]['amount'] + $trans_2[0][0]['amount'];

        $data['sd_to_d'] = $this->Slaves->query("SELECT SUM(st.amount) amount FROM shop_transactions st WHERE st.type = 1 AND st.target_id != 1 AND st.confirm_flag = 0 AND st.date = '$date'");

        $data['netsys_to_r'] = $this->Slaves->query("SELECT SUM(st.amount) amount FROM shop_transactions st WHERE st.confirm_flag = 0 AND st.type = 2 AND st.source_id = 1 AND st.type_flag < 5 AND st.date = '$date'");

        $data['commission_ret'] = $this->Slaves->query("SELECT SUM(st.amount) commission FROM shop_transactions st JOIN retailers r ON (st.source_id = r.id) WHERE st.confirm_flag = 0 AND st.type = 7 AND st.date = '$date'");

        $data['mPos'] = $this->Slaves->query("SELECT SUM(st.amount) amount FROM shop_transactions st WHERE st.user_id = 8 AND st.type = 17 AND st.confirm_flag = 0 AND st.source_closing != 0 AND st.date = '$date'");

        if ($date == date('Y-m-d')) {
            $trans_2 = $this->Slaves->query("SELECT SUM(st.amount) amount FROM shop_transactions st JOIN distributors d ON (st.source_id = d.user_id) where st.confirm_flag = 0 AND st.type = '19' and st.date = '$date'");

            $data['incentive_dist'] = $this->Slaves->query("SELECT SUM(st.amount) incentive FROM shop_transactions st JOIN distributors d ON (st.source_id = d.user_id) WHERE st.confirm_flag = 0 AND st.type = 19 AND st.date = '$date'");

            $data['incentive_ret'] = $this->Slaves->query("SELECT SUM(st.amount) incentive FROM shop_transactions st JOIN retailers r ON (st.source_id = r.user_id) WHERE st.confirm_flag = 0 AND st.type = 19 AND st.date = '$date'");

            $data['commission_dist'] = $this->Slaves->query("SELECT SUM(st.amount) commission FROM shop_transactions st JOIN distributors d ON (st.source_id = d.id) WHERE st.confirm_flag = 0 AND st.type = 6 AND st.date = '$date'");

            $data['payU'] = $this->Slaves->query("SELECT SUM(st.amount) amount FROM shop_transactions st WHERE st.type = 2 AND st.source_id = 1 AND st.type_flag = 5 AND st.confirm_flag = 0 AND st.date = '$date'");
        } else {
            $trans_2 = $this->Slaves->query("SELECT SUM(amount) amount FROM users_nontxn_logs st JOIN distributors d ON (st.user_id = d.user_id) WHERE st.type = '19' and st.date = '$date'");

            $data['incentive_dist'] = $this->Slaves->query("SELECT SUM(st.amount) incentive FROM users_nontxn_logs st JOIN distributors d ON (st.user_id = d.user_id) WHERE st.type = 19 AND st.date = '$date'");

            $data['incentive_ret'] = $this->Slaves->query("SELECT SUM(st.amount) incentive FROM users_nontxn_logs st JOIN retailers r ON (st.user_id = r.user_id) WHERE st.type = 19 AND st.date = '$date'");

            $data['commission_dist'] = $this->Slaves->query("SELECT SUM(st.amount) commission FROM users_nontxn_logs st JOIN distributors d ON (st.user_id = d.user_id) WHERE st.type = 6 AND st.date = '$date'");

            $data['payU'] = $this->Slaves->query("SELECT SUM(st.pg_topup) amount FROM users_logs st JOIN distributors d ON (st.user_id = d.user_id) WHERE d.id = 1 AND st.date = '$date'");
        }
        $this->set('data', $data);
    }

    function updateClosing() {

        $this->autoRender = FALSE;

        if ((date('Y-m-d', strtotime($_POST['date'])) < date('Y-m-d', strtotime('-1 day')) && $_SESSION['Auth']['User']['group_id'] != SUPER_ADMIN) || (date('Y-m-d', strtotime($_POST['date'])) < date('Y-m-d', strtotime('-7 day')) && $_SESSION['Auth']['User']['group_id'] == SUPER_ADMIN)) {
            echo 0;
            die;
        }

        $date_res = $this->Slaves->query("SELECT 1 FROM bank_closing WHERE date = '" . date('Y-m-d', strtotime($_POST['date'])) . "' AND bank_id = '{$_POST['bank']}'");
        if (empty($date_res)) {
            $res = $this->User->query("INSERT INTO bank_closing (date,bank_id,closing) VALUES ('" . date('Y-m-d', strtotime($_POST['date'])) . "','{$_POST['bank']}','{$_POST['balance']}')");
        } else {
            $res = $this->User->query("UPDATE bank_closing SET closing = '{$_POST['balance']}' WHERE date = '" . date('Y-m-d', strtotime($_POST['date'])) . "' AND bank_id = '{$_POST['bank']}'");
        }

        echo $res;
    }

    function pullback() {

        $this->autoRender = FALSE;

        $txn_details = $this->User->query("SELECT sd.id, sd.user_id, d.id, d.user_id FROM shop_transactions st LEFT JOIN master_distributors sd ON (st.source_id = sd.id) LEFT JOIN distributors d ON (st.source_id = d.id) WHERE st.id = '{$_POST['shop_transid']}'");
        $params = $_POST;
        $params['user']['id'] = $params['request_from'] == 'accounts_superdistributor' ? $txn_details[0]['sd']['id'] : $txn_details[0]['d']['id'];
        $params['user']['user_id'] = $params['request_from'] == 'accounts_superdistributor' ? $txn_details[0]['sd']['user_id'] : $txn_details[0]['d']['user_id'];

        if ($params['request_from'] == 'accounts_distributor') {
            $salesman_txn_id = $this->User->query("SELECT id FROM salesman_transactions st WHERE shop_tran_id = '{$_POST['shop_transid']}'");
            $params['salesman_transid'] = $salesman_txn_id[0]['st']['id'];
        }

        App::import('Controller', 'Shops');
        $shops_controller = new ShopsController;
        $shops_controller->constructClasses();

        $result = $shops_controller->pullback($params);

        if (in_array($result['description'], array('success', 'Done'))) {
            $res = $this->User->query("SELECT pay1_txn_id FROM account_txn_details WHERE shop_tran_id = '{$_POST['shop_transid']}' order by id desc limit 1");
            $this->Shop->delMemcache("limit_" . $res['0']['account_txn_details']['pay1_txn_id']);
            $res = $this->User->query("UPDATE account_txn_details SET is_submitted = 0 WHERE shop_tran_id = '{$_POST['shop_transid']}'");
        }

        echo $res;
    }

    function index() {

    }

    function typeList() {

        $this->autoRender = false;

        if (is_numeric($_POST['type'])) {
            $type = array(1 => 'supplier', 2 => 'api', 3 => 'distributor', 4 => 'retailer_all');
            $_POST['type'] = $type[$_POST['type']];
        }

        if ($_POST['type'] == 'retailer') {
            $data = array_map(function($value) {
                return array('id' => $value['retailers']['id'], 'name' => trim($value['retailers']['shopname']) != '' ? $value['retailers']['id'] . ' : ' . trim(preg_replace("/[\n\r]/", "", str_replace("'", "", $value['retailers']['shopname']))) . ' - ' . $value['retailers']['mobile'] : $value['retailers']['id'] . ' : ' . $value['retailers']['mobile']);
            }, $this->Slaves->query("SELECT id, shopname, mobile FROM retailers WHERE (id LIKE '%" . $_POST['str'] . "%' OR shopname LIKE '%" . $_POST['str'] . "%' OR mobile LIKE '%" . $_POST['str'] . "%') AND parent_id = 1 AND toshow = 1 ORDER BY id ASC LIMIT 10"));
        } else if ($_POST['type'] == 'retailer_all') {
            $data = array_map(function($value) {
                return array('id' => $value['retailers']['id'], 'name' => trim($value['retailers']['shopname']) != '' ? $value['retailers']['id'] . ' : ' . trim(preg_replace("/[\n\r]/", "", str_replace("'", "", $value['retailers']['shopname']))) . ' - ' . $value['retailers']['mobile'] : $value['retailers']['id'] . ' : ' . $value['retailers']['mobile']);
            }, $this->Slaves->query("SELECT id, shopname, mobile FROM retailers WHERE (id LIKE '%" . $_POST['str'] . "%' OR shopname LIKE '%" . $_POST['str'] . "%' OR mobile LIKE '%" . $_POST['str'] . "%') AND toshow = 1 ORDER BY id ASC LIMIT 10"));
        } else if ($_POST['type'] == 'distributor') {
            if($_POST['request_from'] && $_POST['request_from'] != "ledger"){
                    $sd_condition = " AND sd_id IS NULL ";
            }

            $data = array_map(function($value) { return array('id'=>$value['distributors']['id'], 'name'=>trim($value['distributors']['company']) != '' ? $value['distributors']['id'].' : '.trim(preg_replace("/[\n\r]/", "", str_replace("'","",$value['distributors']['company']))).' - '.$value['distributors']['mobile'] : $value['distributors']['id'].' : '.$value['distributors']['mobile']); }, $this->Slaves->query("SELECT id, company, mobile FROM distributors WHERE (id LIKE '%".$_POST['str']."%' OR company LIKE '%".$_POST['str']."%' OR mobile LIKE '%".$_POST['str']."%') ".$sd_condition." ORDER BY id ASC LIMIT 10"));

        } else if ($_POST['type'] == 'supplier') {
            $data = array_map(function($value) {
                return array('id' => $value['is']['id'], 'name' => trim(str_replace("'", "", $value['is']['name'])));
            }, $this->Slaves->query("SELECT id, name FROM inv_suppliers `is` WHERE (id LIKE '%" . $_POST['str'] . "%' OR name LIKE '%" . $_POST['str'] . "%') ORDER BY id ASC LIMIT 10"));
        } else if ($_POST['type'] == 'api') {
            $data = array_map(function($value) {
                return array('id' => $value['vendors']['id'], 'name' => trim(str_replace("'", "", $value['vendors']['company'] . " (" . $value['vendors']['shortForm'] . ")")));
            }, $this->Slaves->query("SELECT id, company, shortForm FROM vendors WHERE (id LIKE '%" . $_POST['str'] . "%' OR company LIKE '%" . $_POST['str'] . "%') AND update_flag = 0 ORDER BY id ASC LIMIT 10"));
        } else if ($_POST['type'] == 'superdistributor') {
                        $data = array_map(function($value) { return array('id'=>$value['sd']['id'], 'name'=>$value['sd']['id']." : ".trim(preg_replace("/[\n\r]/", "", str_replace("'","",$value['imp']['name'])))); }, $this->Slaves->query("SELECT sd.id, imp.description name FROM super_distributors sd LEFT JOIN imp_label_upload_history imp ON (sd.user_id = imp.user_id AND imp.label_id = '15') WHERE (sd.id LIKE '%".$_POST['str']."%' OR imp.description LIKE '%".$_POST['str']."%') AND sd.active_flag = 1 ORDER BY sd.id ASC LIMIT 10"));
        }

        echo json_encode($data);
        die;
    }

    function bankStatements($bank = 1, $date = 0, $csv = 0) {

        $date == 0 && $date = date('Y-m-d');

        $result = array_map('current', $this->Slaves->query("SELECT * FROM account_txn_details atd WHERE DATE(txn_date) = '$date' AND bank_id = '$bank' ORDER BY atd.id DESC"));

        $banks = $this->Slaves->query("SELECT id, concat(bank_name,' - ',account_no) name FROM bank_details bd WHERE account_no != '' ORDER BY id DESC");
        foreach ($banks as $b) {
            $bank_accounts[$b['bd']['id']] = $b[0]['name'];
        }

        if ($csv == 1) {
            $this->autoRender = FALSE;

            App::import('Helper', 'csv');
            $csv = new CsvHelper();

            $line = array("Txn ID", "Bank", "Bank Txn ID", "Branch", "Txn Date", "Operaton Date", "Txn Type", "Description", "Amount", "Closing Balance", "Status");
            $csv->addRow($line);

            foreach ($result as $res) {
                $line = array($res['pay1_txn_id'] . "'", $bank_accounts[$res['bank_id']], $res['bank_txn_id'], $res['branch_code'], $res['txn_date'], $res['operation_date'], $res['txn_status'], $res['description'], $res['amount'], $res['balance'], ($res['is_submitted'] == 1 ? 'Success' : 'Pending'));
                $csv->addRow($line);
            }
            echo $csv->render('BankStatement_' . $date . '.csv');
        }

        $this->set('data', $result);
        $this->set('banks', $bank_accounts);
        $this->set('sel_bank', $bank);
        $this->set('date', $date);
    }

    function ledger($type = 1, $from = 0, $to = 0, $id = 0, $download = 0) {

        $from == 0 && $from = date('Y-m-d', strtotime('-1 day'));
        $to == 0 && $to = date('Y-m-d', strtotime('-1 day'));
        if ($id) {

            if ($type == 1) {
                //(by payment)
                $data['vendor']['modem']['to_pay'] = $this->Slaves->query("SELECT SUM(io.to_pay) pay FROM inv_orders io JOIN inv_supplier_operator isv ON (io.supplier_operator_id = isv.id) WHERE io.order_date >= '$from' AND io.order_date <= '$to' AND isv.supplier_id = '$id' AND isv.is_api != '1'");
                //(to purchase)
                $purchases = $this->Slaves->query("SELECT SUM(ip.incoming) incoming, iso.commission_type, iso.commission_type_formula FROM inv_pendings ip JOIN inv_supplier_operator iso ON (ip.supplier_operator_id = iso.id) WHERE ip.pending_date >= '$from' AND ip.pending_date <= '$to' AND iso.supplier_id = '$id' AND iso.is_api != '1' GROUP BY iso.id");
                foreach ($purchases as $purchase) {
                    $data['vendor']['modem']['purchase'] += $purchase[0]['incoming'];
                    $incoming += $this->convertStockToRupees($purchase['iso']['commission_type'], $purchase[0]['incoming'], $purchase['iso']['commission_type_formula']);
                }

                $opening = $this->Slaves->query("SELECT ip.pending, iso.commission_type, iso.commission_type_formula FROM inv_pendings ip JOIN inv_supplier_operator iso ON (ip.supplier_operator_id = iso.id) WHERE ip.pending_date >= '" . date('Y-m-d', strtotime('-1 day', strtotime($from))) . "' AND iso.supplier_id = '$id' ORDER BY ip.id ASC LIMIT 1");

                foreach ($opening as $open) {
                    $data['vendor']['modem']['o'] += $this->convertStockToRupees($open['iso']['commission_type'], $open['ip']['pending'], $open['iso']['commission_type_formula']);
                }

                $closing = $this->Slaves->query("SELECT ip.pending, iso.commission_type, iso.commission_type_formula FROM inv_pendings ip JOIN inv_supplier_operator iso ON (ip.supplier_operator_id = iso.id) WHERE ip.pending_date <= '$to' AND iso.supplier_id = '$id' ORDER BY ip.id DESC LIMIT 1");

                foreach ($closing as $close) {
                    $data['vendor']['modem']['c'] += $this->convertStockToRupees($close['iso']['commission_type'], $close['ip']['pending'], $close['iso']['commission_type_formula']);
                }

                $data['vendor']['modem']['commission'] = $data['vendor']['modem']['purchase'] - $incoming;

                $detail = $this->Slaves->query("SELECT name FROM inv_suppliers l WHERE id = '$id'");
            } else if ($type == 2) {

                $data['vendor']['api'] = $this->Slaves->query("SELECT SUM(sale) sale, ROUND(commission, 2) commission FROM api_vendors_sale_data avs WHERE date >= '$from' AND date <='$to' AND avs.vendor_id = '$id'");

                $data['vendor']['api']['purchase'] = $this->Slaves->query("SELECT SUM(incoming) purchase FROM earnings_logs WHERE date >= '$from' AND date <='$to' AND vendor_id = '$id'");

                $data['vendor']['api']['o'] = $this->Slaves->query("SELECT closing opening FROM earnings_logs el WHERE date >= '" . date('Y-m-d', strtotime('-1 day', strtotime($from))) . "' AND vendor_id = '$id' ORDER BY id ASC LIMIT 1");

                $data['vendor']['api']['c'] = $this->Slaves->query("SELECT closing FROM earnings_logs el WHERE date <= '$to' AND vendor_id = '$id' ORDER BY id DESC LIMIT 1");

                $detail = $this->Slaves->query("SELECT concat(company,' (',shortForm,')') name FROM vendors l WHERE id = '$id'");
            } else if ($type == 3) {

                $data['distributor']['limit'] = $this->Slaves->query("SELECT SUM(topup_buy) `limit` FROM users_logs as distributors_logs join distributors ON (distributors.user_id = distributors_logs.user_id) WHERE date >= '$from' AND date <= '$to' AND distributors.id = '$id'");

//                                $data['distributor']['commission'] = $this->Slaves->query("SELECT SUM(amount) commission FROM shop_transactions st WHERE date >= '$from' AND date <= '$to' AND source_id = '$id' AND type = '".COMMISSION_DISTRIBUTOR."' AND confirm_flag = '0'");
                $data['distributor']['commission'] = $this->Slaves->query("SELECT SUM(st.amount) commission FROM users_nontxn_logs st JOIN distributors d ON (st.user_id = d.user_id) WHERE st.date >= '$from' AND st.date <= '$to' AND d.id = '$id' AND st.type = '" . COMMISSION_DISTRIBUTOR . "'");

                $data['distributor']['excluded_commission'] = $this->Slaves->query("SELECT SUM(amount) excluded_commission FROM shop_transactions st WHERE date >= '$from' AND date <= '$to' AND source_id = '$id' AND type = '" . COMMISSION_DISTRIBUTOR_REVERSE . "' AND confirm_flag = '0'");

//                                $data['distributor']['incentive']  = $this->Slaves->query("SELECT SUM(amount) incentive FROM shop_transactions st JOIN distributors d ON (st.source_id = d.user_id) WHERE st.date >= '$from' AND st.date <= '$to' AND d.id = '$id' AND st.type = '".REFUND."' AND st.confirm_flag = '0'");
                $data['distributor']['incentive'] = $this->Slaves->query("SELECT SUM(amount-txn_reverse_amt) incentive FROM users_nontxn_logs st JOIN distributors d ON (st.user_id = d.user_id) WHERE st.date >= '$from' AND st.date <= '$to' AND d.id = '$id' AND st.type = '" . REFUND . "'");

//                                $data['distributor']['trf_ret']    = $this->Slaves->query("SELECT SUM(amount) transfer_retailer FROM shop_transactions st WHERE date >= '$from' AND date <= '$to' AND source_id = '$id' AND type = '".DIST_RETL_BALANCE_TRANSFER."' AND confirm_flag = '0'");
                $data['distributor']['trf_ret'] = $this->Slaves->query("SELECT SUM(topup_sold) transfer_retailer FROM users_logs st JOIN distributors d ON (st.user_id = d.user_id) WHERE date >= '$from' AND date <= '$to' AND d.id = '$id'");

//                                $data['distributor']['trf_sal']    = $this->Slaves->query("SELECT SUM(amount) transfer_salesmen FROM shop_transactions st WHERE date >= '$from' AND date <= '$to' AND source_id = '$id' AND type = '".DIST_SLMN_BALANCE_TRANSFER."' AND confirm_flag = '0'");
                $data['distributor']['trf_sal'] = 0;
                $data['distributor']['sd'] = $this->Slaves->query("SELECT SUM(amount) security_deposit FROM users_nontxn_logs dsl JOIN distributors d ON (dsl.user_id = d.user_id) WHERE dsl.date >= '$from' AND dsl.date <= '$to' AND d.id = '$id' AND dsl.type = '" . SECURITY_DEPOSIT . "'");

                $data['distributor']['one_time'] = $this->Slaves->query("SELECT SUM(amount) one_time FROM users_nontxn_logs dsl JOIN distributors d ON (dsl.user_id = d.user_id) WHERE dsl.date >= '$from' AND dsl.date <= '$to' AND d.id = '$id' AND dsl.type = '" . ONE_TIME_CHARGE . "'");

                $data['distributor']['tds'] = $this->Slaves->query("SELECT SUM(tds) tds FROM users_nontxn_logs st JOIN distributors d ON (st.user_id = d.user_id) WHERE date >= '$from' AND date <= '$to' AND d.id = '$id' AND type = '" . TDS . "'");

                $data['distributor']['kit_charge'] = $this->Slaves->query("SELECT SUM(amount) kit_charge FROM users_nontxn_logs dsl JOIN distributors d ON (dsl.user_id = d.user_id) WHERE dsl.date >= '$from' AND dsl.date <= '$to' AND d.id = '$id' AND dsl.type = '" . KITCHARGE . "'");

                $data['distributor']['o'] = $this->Slaves->query("SELECT opening FROM users_logs dl WHERE date = '$from' AND distributor_id = '$id'");

                $data['distributor']['c'] = $this->Slaves->query("SELECT closing FROM users_logs dl WHERE date = '$to' AND distributor_id = '$id'");

                $detail = $this->Slaves->query("SELECT concat(company,' (',mobile,')') name FROM distributors l WHERE id = '$id'");

                $retailers = $this->Slaves->query("SELECT retailers.id, retailers.name, st.amount FROM shop_transactions st JOIN retailers ON retailers.id = st.target_id WHERE DATE >=  '$from' AND DATE <=  '$to' AND source_id =  '$id' AND TYPE =  '" . DIST_RETL_BALANCE_TRANSFER . "' AND confirm_flag =  '0'ORDER BY retailers.id ASC ");

                $salesmen = $this->Slaves->query("SELECT salesmen.id, salesmen.name, st.amount FROM shop_transactions st JOIN salesmen ON salesmen.id = st.target_id WHERE DATE >=  '$from' AND DATE <=  '$to' AND source_id =  '$id' AND TYPE =  '" . DIST_SLMN_BALANCE_TRANSFER . "' AND confirm_flag =  '0' ORDER BY salesmen.id ASC ");

                $detail = $this->Slaves->query("SELECT concat(company,' (',mobile,')') name FROM distributors l WHERE id = '$id'");
            } else if ($type == 4) {

                $details = $this->Slaves->query("SELECT d.id, s.id, r.user_id, r.parent_id FROM retailers r JOIN distributors d ON (r.parent_id = d.id) JOIN salesmen s ON (d.mobile = s.mobile) WHERE r.id = '$id'");

                if ($details[0]['r']['parent_id'] != 1) {
//                                        $data['retailer']['transfer_nn']   = $this->Slaves->query("SELECT SUM(amount) transfer FROM shop_transactions st WHERE date >= '$from' AND date <= '$to' AND ((type = '".DIST_RETL_BALANCE_TRANSFER."' AND source_id = '{$details[0]['d']['id']}') OR (type = '".SLMN_RETL_BALANCE_TRANSFER."' AND source_id = '{$details[0]['s']['id']}')) AND target_id = '$id' AND confirm_flag = '0'");
                    $data['retailer']['transfer_nn'] = $this->Slaves->query("SELECT SUM(topup_buy) transfer FROM users_logs st JOIN retailers r ON (st.user_id = r.user_id) JOIN distributors d ON (st.parent_user_id = d.user_id) WHERE date >= '$from' AND date <= '$to' AND d.id = '{$details[0]['d']['id']}' AND r.id = '$id'");
                } else {
//                                        $data['retailer']['trf_net_lmt']   = $this->Slaves->query("SELECT SUM(amount) transfer FROM shop_transactions st WHERE date >= '$from' AND date <= '$to' AND type = '".DIST_RETL_BALANCE_TRANSFER."' AND source_id = '1' AND target_id = '$id' AND type_flag < '5' AND confirm_flag = '0'");
                    $data['retailer']['trf_net_lmt'] = $this->Slaves->query("SELECT SUM(topup_buy) transfer FROM users_logs st JOIN retailers r ON (st.user_id = r.user_id) WHERE date >= '$from' AND date <= '$to' AND parent_user_id = '8' AND r.id = '$id'");

//                                        $data['retailer']['trf_net_lmt_p'] = $this->Slaves->query("SELECT SUM(amount) transfer FROM shop_transactions st WHERE date >= '$from' AND date <= '$to' AND type = '".DIST_RETL_BALANCE_TRANSFER."' AND source_id = '1' AND target_id = '$id' AND type_flag = '5' AND confirm_flag = '0'");
                    $data['retailer']['trf_net_lmt_p'] = $this->Slaves->query("SELECT SUM(pg_topup) transfer FROM users_logs st JOIN retailers r ON (st.user_id = r.user_id) WHERE date >= '$from' AND date <= '$to' AND parent_user_id = '8' AND r.id = '$id'");
                }

//                                $data['retailer']['commission']    = $this->Slaves->query("SELECT SUM(earning) commission FROM retailer_earning_logs rel WHERE date >= '$from' AND date <= '$to' AND ret_user_id = '{$details[0]['r']['user_id']}' AND txn_type IN ('0','1')");
                $data['retailer']['commission']    = $this->Slaves->query("SELECT SUM(commission) commission FROM retailer_earning_logs rel WHERE date >= '$from' AND date <= '$to' AND ret_user_id = '{$details[0]['r']['user_id']}'");

//                                $data['retailer']['incentive']     = $this->Slaves->query("SELECT SUM(amount) incentive FROM shop_transactions st WHERE st.date >= '$from' AND st.date <= '$to' AND st.source_id = '{$details[0]['r']['user_id']}' AND st.type = '".REFUND."' AND st.confirm_flag = '0'");
                $data['retailer']['incentive'] = $this->Slaves->query("SELECT SUM(amount-txn_reverse_amt) incentive FROM users_nontxn_logs st JOIN retailers r ON (st.user_id = r.user_id) WHERE st.date >= '$from' AND st.date <= '$to' AND st.user_id = '{$details[0]['r']['user_id']}' AND st.type = '" . REFUND . "'");

                $db_services = $this->Slaves->query("SELECT SUM(rel.amount) amount, rel.service_id, s.name FROM retailer_earning_logs rel JOIN services s ON (rel.service_id = s.id) WHERE date >= '$from' AND date <= '$to' AND ret_user_id = '{$details[0]['r']['user_id']}' AND rel.type IN ('" . DEBIT_NOTE . "') GROUP BY rel.service_id");
                foreach ($db_services as $db_service) {
                    $data['retailer']['services'][DEBIT_NOTE][$db_service['rel']['service_id']] = array('name' => $db_service['s']['name'], 'amount' => $db_service[0]['amount']);
                }

                $cr_services = $this->Slaves->query("SELECT SUM(st.amount) amount, st.user_id, s.name FROM shop_transactions st JOIN services s ON (st.user_id = s.id) WHERE st.date >= '$from' AND st.date <= '$to' AND st.source_id = '{$details[0]['r']['user_id']}' AND st.type = '" . CREDIT_NOTE . "' AND st.source_opening != st.source_closing GROUP BY st.user_id");
                foreach ($cr_services as $cr_service) {
                    $data['retailer']['services'][CREDIT_NOTE][$cr_service['st']['user_id']] = array('name' => $cr_service['s']['name'], 'amount' => $cr_service[0]['amount']);
                }

                $data['retailer']['kit_charge'] = $this->Slaves->query("SELECT SUM(amount) kit_charge FROM users_nontxn_logs rsl JOIN retailers r ON (rsl.user_id = r.user_id) WHERE rsl.date >= '$from' AND rsl.date <= '$to' AND rsl.user_id = '{$details[0]['r']['user_id']}' AND rsl.type = '" . KITCHARGE . "'");

//                                $data['retailer']['service_chrge'] = $this->Slaves->query("SELECT SUM(earning) service_charge FROM retailer_earning_logs rel WHERE date >= '$from' AND date <= '$to' AND ret_user_id = '{$details[0]['r']['user_id']}' AND txn_type = '2'");
                $data['retailer']['service_chrge'] = $this->Slaves->query("SELECT SUM(service_charge) service_charge FROM retailer_earning_logs rel WHERE date >= '$from' AND date <= '$to' AND ret_user_id = '{$details[0]['r']['user_id']}'");

                $data['retailer']['one_time'] = $this->Slaves->query("SELECT SUM(amount) one_time FROM users_nontxn_logs rsl JOIN retailers r ON (rsl.user_id = r.user_id) WHERE rsl.date >= '$from' AND rsl.date <= '$to' AND rsl.user_id = '{$details[0]['r']['user_id']}' AND rsl.type = '" . ONE_TIME_CHARGE . "'");

                $data['retailer']['rental'] = $this->Slaves->query("SELECT SUM(amount) rental FROM users_nontxn_logs rsl JOIN retailers r ON (rsl.user_id = r.user_id) WHERE rsl.date >= '$from' AND rsl.date <= '$to' AND rsl.user_id = '{$details[0]['r']['user_id']}' AND rsl.type = '" . RENTAL . "'");

                $data['retailer']['o'] = $this->Slaves->query("SELECT opening FROM users_logs rl WHERE date = '$from' AND user_id = '{$details[0]['r']['user_id']}'");

                $data['retailer']['c'] = $this->Slaves->query("SELECT closing FROM users_logs rl WHERE date = '$to' AND user_id = '{$details[0]['r']['user_id']}'");

                $detail = $this->Slaves->query("SELECT concat(shopname,' (',mobile,')') name FROM retailers l WHERE id = '$id'");
            }
        }

        $this->set('id', $id);
        $this->set('retailers', $retailers);
        $this->set('salesmen', $salesmen);
        $this->set('type', $type);
        $this->set('data', $data);
        $this->set('details', $detail);
        $this->set('id', $id);
        $this->set('from', $from);
        $this->set('to', $to);
        if ($download == 1) {
            $this->render('/accounting/ledgerPdf');
        }
    }

    private function convertStockToRupees($commission_type, $incoming, $commission_type_formula) {

        return $pendingamt = (($commission_type == 2) ? ($incoming / ((100 + $commission_type_formula) / 100)) : ($incoming * ((100 - $commission_type_formula) / 100)));
    }

    function distList() {
        $this->autoRender = false;
        $search = $_POST['search'];
        $distributors = $this->Slaves->query("SELECT * FROM  `distributors` WHERE (id LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%' OR mobile LIKE '%" . $search . "%') AND active_flag = 1 LIMIT 0 , 5;");

        $dist_ids = array_map(function($element) {
            return $element['distributors']['id'];
        }, $distributors);
        $temp = $this->Shop->getUserLabelData($dist_ids, 2, 3);
        $this->set('distributors_data', $temp);


        $dists = array();
        foreach ($distributors as $distributor) {
            $user_id = $distributor['distributors']['id'];
            $dists[] = array("name" => $temp[$user_id]['imp']['shop_est_name'] . ' - ' . $distributor['distributors']['id'], "id" => $distributor['distributors']['id']);
        }
        echo json_encode($dists);
    }

    public function debitSystem() {

        $to_save = true;
        if ($this->data) {

            if ($this->data['shop'] == 0) {
                $this->Session->setFlash("<b>Errors</b> : Please select Operation Type");
                $to_save = false;
            } else if ($this->data['distributor'] == 0) {
                $this->Session->setFlash("<b>Errors</b> : Please select Distributor");
                $to_save = false;
            } else if ($this->data['service'] == 0) {
                $this->Session->setFlash("<b>Errors</b> : Please select Service");
                $to_save = false;
            } else if ($this->data['shop'] > 1 && ((empty($this->data['amount'])) || (!preg_match("/^[0-9]*$/", $this->data['amount'])) || ($this->data['amount'] <= 0))) {
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/debitsystem.txt", "Debit System => " . json_encode($this->data));
                $this->Session->setFlash("<b>Errors</b> : Please enter proper amount");
                $to_save = false;
            } else if ($this->data['shop'] == 1 && (empty($this->data['kits']) || $this->data['kits'] <= 0 || !preg_match("/^[0-9]*$/", $this->data['kits']))) {
                $this->Session->setFlash("<b>Errors</b> : Please enter proper no of Kits");
                $to_save = false;
            } else if ($this->data['shop'] == 1 && !empty($this->data['discount_per_kit']) && (!preg_match("/^[0-9].*$/", $this->data['discount_per_kit']))) {
                $this->Session->setFlash("<b>Errors</b> : Enter valid discount per kit");
                $to_save = false;
            } else if ($this->data['is_visible'] == '0') {
                $this->Session->setFlash("<b>Errors</b> : Invalid plan");
                $to_save = false;
            }
        }
        if ($this->data['distributor'] > 0) {
            $amt = $this->data['setup_amt'];
            $service_plan_id = $this->data['plan'];
            $distributor = $this->data['distributor'];
            $service = $this->data['service'];
            $amount = $this->data['shop'] == 1 ? (($this->data['kits'] * $this->data['setup_amt']) - ($this->data['kits'] * $this->data['discount_per_kit'])) : $this->data['amount'];
            $dist_details = $this->Slaves->query("SELECT d.id, d.user_id, dk.id, users.balance FROM distributors d LEFT JOIN distributors_kits dk ON (d.id = dk.distributor_id) JOIN users ON (d.user_id = users.id) "
                    . "WHERE d.id = '$distributor' AND dk.service_id = '$service' AND dk.service_plans_id =  '$service_plan_id'");


            $distributor_detail = $this->Slaves->query("SELECT user_id,users.balance FROM  `distributors`JOIN users ON distributors.user_id = users.id WHERE distributors.id =" . $distributor);

            if ((!empty($dist_details) && $amount > $dist_details[0]['users']['balance']) || $amount > $distributor_detail[0]['users']['balance']) {
                $this->Session->setFlash("<b>Errors</b> : Insufficient Balance");
                $to_save = false;
            }
        }
        if ($this->data['confirm_flag'] == 0 && $to_save != false) {
            $confirm_flag = $this->data['confirm_flag'];
            $to_save = false;
        }
//                else if ($this->data['confirm_flag'] == 1 && $this->General->findVar('limit_password') != $this->data['password']) {
//                        $this->Session->setFlash("<b>Errors</b> : Incorrect Password");
//                        $to_save = false;
//                }

        $plans = $this->Serviceintegration->getServicePlans();
        if ($to_save) {
            $kits = $this->data['kits'];
            $discount = $this->data['shop'] == 1 ? $this->data['kits'] * $this->data['discount_per_kit'] : 0;
            $note = $this->data['note'];
            $type = ($this->data['shop'] == 1) ? KITCHARGE : (($this->data['shop'] == 2) ? SECURITY_DEPOSIT : ONE_TIME_CHARGE);

            if ($this->data['shop'] == 1 && $kits > 200) {
                $this->Session->setFlash("<b>Errors</b> : Failure: Kits cannot be greater than 200");
            } else {
                $balance = $this->Shop->shopBalanceUpdate($amount, 'subtract', $distributor_detail[0]['distributors']['user_id']);
                $this->Shop->shopTransactionUpdate($type, $amount, $distributor_detail[0]['distributors']['user_id'], $kits, $service, $discount, NULL, $note, $balance + $amount, $balance);
                if ($this->data['shop'] == 1) {
                    if (empty($dist_details)) {
                        $result = $this->User->query("INSERT INTO distributors_kits (distributor_id, service_id, kits,service_plans_id, updated) VALUES "
                                . "('$distributor', '$service', '$kits','" . $service_plan_id . "' ,'" . date('Y-m-d H:i:s') . "')");
                    } else {
                        $result = $this->User->query("UPDATE distributors_kits SET kits = kits + $kits, service_plans_id =  $service_plan_id, updated = '" . date('Y-m-d H:i:s') . "' WHERE id = '{$dist_details[0]['dk']['id']}'");
                    }
                    if ($result) {
                        $this->User->query("INSERT INTO distributors_kits_log (distributor_id, service_id, kits,service_plans_id, amount ,created_by, created_at, action) VALUES "
                                . "('$distributor', '$service', '$kits','" . $service_plan_id . "' ,'$amount','" . $_SESSION['Auth']['User']['id'] . "','" . date('Y-m-d H:i:s') . "','debit')");
                    }
                    if ($this->data['confirm_flag']) {
                        $this->Session->setFlash("<b>Success</b> : Kits Inserted Successfully");
                    }

                    // Entry in kit_delivery_log on kit purchase via retailer wallet|instamojo if delivery_flag is true
                    $temp = json_decode($plans,true);
                    foreach ( $temp[$service] as $key => $plan ) {
                        $service_plans[$plan['id']] = $plan;
                    }

                    if( $service_plans[$service_plan_id]['delivery_flag'] ){

                        $kit_delivery_data = array(
                            'ret_user_id' => $distributor_detail[0]['distributors']['user_id'],
                            'dist_user_id' => $distributor_detail[0]['distributors']['user_id'],
                            'group_id' => DISTRIBUTOR,
                            'source' => 'kit_purchase_panel_limit_team',
                            'service_id' => $service,
                            'service_plan_id' => $service_plan_id,
                            'kits' => $kits,
                            'purchased_date' => date("Y-m-d"),
                            'purchased_timestamp' => date("Y-m-d h:i:s")
                        );
                        $kit_delivery_res = $this->Servicemanagement->addKitDeliveryLog($kit_delivery_data);
                    }

                } else {
                    if ($this->data['shop'] == 2) {
                        $this->User->query("UPDATE distributors SET sd_amt = sd_amt + $amount WHERE id = '$distributor'");

                        $this->Session->setFlash("<b>Success</b> :Security Deposit Amount Inserted Successfully");
                        $to_save = true;
                    } else if ($this->data['shop'] == 3) {
                        $this->User->query("UPDATE distributors SET one_time = one_time + $amount WHERE id = '$distributor'");

                        $this->Session->setFlash("<b>Success</b> :One Time Amount Inserted Successfully");
                        $to_save = false;
                    }
                }
            }
        } else {
            $this->set('data', $this->data);
            if (isset($confirm_flag)) {
                $this->set('confirm_flag', 1);
            }
        }
        $services = $this->Slaves->query("SELECT id, name FROM services WHERE toShow = 1");
        $this->set('services', $services);
        $this->set('serviceplans', $plans);
    }

    function refund() {
        $to_save = true;
        if ($this->data) {

            if ($this->data['shop'] == 0) {
                $msg = "Please select Operation Type";
                $to_save = false;
            } else if ($this->data['distributor'] == 0) {
                $msg = "Please select Distributor";
                $to_save = false;
            } else if ($this->data['shop'] == 1 && $this->data['service'] == 0) {
                $msg = "Please select Service";
                $to_save = false;
            } else if (empty($this->data['amount']) || $this->data['amount'] <= 0 || !is_numeric($this->data['amount'])) {
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/debitsystem.txt", "Refund System => " . json_encode($this->data));
                $msg = "Please enter proper amount";
                $to_save = false;
            } else if ($this->data['shop'] == 1 && (empty($this->data['kits']) || $this->data['kits'] <= 0 || !preg_match("/^[0-9]*$/", $this->data['kits']))) {
                $msg = "Please enter proper no of Kits";
                $to_save = false;
            }
        }

        if ($this->data['distributor'] > 0 && $to_save == true) {

            $distributor = $this->data['distributor'];
            $service = $this->data['service'];
            $amount = $this->data['amount'];
            $kit = $this->data['kits'];
            $note = $this->data['note'];
            $type = ($this->data['shop'] == 1) ? KITCHARGE : (($this->data['shop'] == 2) ? SECURITY_DEPOSIT : ONE_TIME_CHARGE);
            $data = false;
            $plan = $this->data['plan'];


            if ($this->data['shop'] == 1) {
                $kit_details = $this->Slaves->query("SELECT kits from distributors_kits where distributor_id='$distributor' AND service_id='$service' AND kits >= '$kit' AND service_plans_id = $plan");
                if ($kit_details) {
                    $data = $this->User->query("UPDATE distributors_kits SET kits = kits-'" . $kit . "' where distributor_id='" . $distributor . "' AND service_id='" . $service . "' AND service_plans_id = $plan");
                    if ($data) {
                        $this->User->query("INSERT INTO distributors_kits_log (distributor_id, amount , service_id, kits,service_plans_id, created_by, created_at, action) VALUES "
                                . "('$distributor','$amount' , '$service', '$kit','" . $plan . "' ,'" . $_SESSION['Auth']['User']['id'] . "' ,'" . date('Y-m-d H:i:s') . "','refund')");
                    }
                    $msg = "Kits Refunded Successfully";
                    $to_save = true;
                } else {
                    $msg = "Do Not Have Enough Kits To Refund";
                    $to_save = false;
                }
            } else {
                if ($this->data['shop'] == 2) {
                    $sec_deposit = $this->Slaves->query(" SELECT sd_amt from distributors WHERE id = '$distributor' AND sd_amt >= '$amount'");
                    if ($sec_deposit) {
                        $data = $this->User->query("UPDATE distributors SET sd_amt = sd_amt - $amount WHERE id = '$distributor'");
                        $msg = "Security Deposit Amount Refunded Successfully";
                        $to_save = true;
                    } else {
                        $msg = "Do Not Have Enough Security Deposit";
                        $to_save = false;
                    }
                } else if ($this->data['shop'] == 3) {
                    $one_time = $this->Slaves->query(" SELECT one_time from distributors WHERE id = '$distributor' AND one_time >= '$amount'");
                    if ($one_time) {
                        $data = $this->User->query("UPDATE distributors SET one_time = one_time - $amount WHERE id = '$distributor'");
                        $msg = "One-Time Amount Refunded Successfully";
                        $to_save = true;
                    } else {
                        $msg = "Do Not Have Enough One-Time Deposit Amount";
                        $to_save = false;
                    }
                }
            }
            $dist_data = $this->Slaves->query("SELECT user_id FROM distributors where id='" . $distributor . "'");
            if ($data == 1) {
                $balance = $this->Shop->shopBalanceUpdate($amount, 'add', $dist_data[0]['distributors']['user_id'], DISTRIBUTOR);
                $this->Shop->shopTransactionUpdate(TXN_REVERSE, $amount, $dist_data[0]['distributors']['user_id'], $kit, $service, null, $type, $note, $balance - $amount, $balance);
            }
        }

        $services = $this->Slaves->query("SELECT id, name FROM services WHERE toShow = 1");
        $this->set('services', $services);
        if (isset($msg)) {
            $msg = "<div class='alert alert-" . (($to_save == true) ? "success" : "danger") . "'>" . $msg . "</div>";
            $this->Session->setFlash($msg);
        }

        $plans = $this->Serviceintegration->getServicePlans();
        $this->set('serviceplans', $plans);
        $this->render('/accounting/refund');
    }

    function distributorLimit() {

//            if ($this->RequestHandler->isPost()) {
//                    $to_save=true;
//                    $limit=$this->params['form']['limit'];
//                    $id=$this->params['form']['id'];
//                    if($this->General->priceValidate($limit) == ''){//amount validation
//                            $msg = "Invalid amount";
//                            $to_save=false;
//                    }
//
//                   if($to_save){
//                           $dist = $this->Slaves->query("SELECT company FROM distributors d WHERE id = '$id'");
//                           $data=$this->User->query("UPDATE distributors SET max_limit='$limit' where id='$id'");
//                   if($data){
//                            $msg = "Limit updated";
//                            $to_save=true;
//                            $this->General->sendMails("Distributor Limit Changed !!!", "Distributor Name : ". $dist[0]['d']['company'] ."<br/>Distributor ID : " . $id . "<br/>Limit Changed : " . $limit , array("ashish@pay1.in","abhinav.m@pay1.in","ashok.y@pay1.in"),'mail');
//                        }else{
//                            $msg = "Unable to update limit";
//                            $to_save=false;
//                        }
//                    }
//                   if(isset($msg)) {
//                           $array = array("msg"=>"<div class='alert alert-".(($to_save==true)?"success":"danger")."'>".$msg."</div>","to_save"=>$to_save);
//                           echo json_encode($array);exit;
//                        }
//                    }
//                    $result=$this->Slaves->query("select id,user_id,mobile,max_limit from distributors");
//                    $dist_ids = array_map(function($element){
//                        return $element['distributors']['user_id'];
//                    },$result);
//                    $temp = $this->Shop->getUserLabelData($dist_ids,2,0);
//
//                    $this->set('result',$result);
//                    $this->set('result_names',$temp);
    }

    function easyPayConfirmation() {

        $this->autoRender = FALSE;

        $this->General->logData("axis_bank_integration.txt", json_encode($_SERVER));

        $client_ip = $this->General->getClientIP();

        $listed_ip = array('122.15.128.143', '122.15.128.144', '122.15.128.145', '122.15.128.146', '59.144.108.23', '59.144.108.24', '59.144.108.25', '59.144.108.26', '115.112.84.23', '115.112.84.24', '115.112.84.25', '115.112.84.26', '36.255.30.29', '36.255.30.30', '36.255.31.29', '36.255.31.30', '119.226.231.26', '119.226.231.27', '119.226.231.28', '122.15.183.144', '103.208.251.29', '103.208.251.30', '103.208.250.29', '103.208.250.30', '59.144.108.199', '115.112.84.199', '122.15.128.199');

        if (!in_array($client_ip, $listed_ip)) {
            echo "Access Denied";
            die;
        }

        $this->General->logData("axis_bank_integration.txt", json_encode($this->params['form']));

        $req = json_decode($this->params['form']['req'], 1);

        $app_user_id = $req['Appl_User_ID'];
        $req_id = $req['Req_id'];
        $txn_nmbr = $req['txn_nmbr'];
        $req_time = $req['Req_dt_time'];
        $corp_code = $req['Corp_code'];
        $amount = $req['Txn_amnt'];
        $payment_mode = $req['pmode'];
        $card_no = $req['card_no'];
        $branch_code = $req['branch_code'];
        $description = $req['description'];
        $balance = $req['balance'];
        $pymt_status = $req['Stts_flg'];
        $clt_txn_id = 'pay1' . date('YmdHis') . round($amount) . rand(111, 999);
        $status = '111';

        $data = "req_id=$req_id&txn_nmbr=$txn_nmbr&corp_code=$corp_code&txn_amnt=$amount";
        $result = $this->encrypt($data, AXIS_SALT);

//                if ($res == $app_user_id) {
        if ($result == $result) {

                        $dist_details = $this->Slaves->query("SELECT d.id, d.user_id, d.mobile, dbc.direct_transfer FROM distributors d JOIN distributor_bank_cards dbc ON (d.id = dbc.distributor_id) WHERE dbc.card_no = '$card_no'");

                        if ($dist_details) {

                                $pay1_txn_id = "15".date('Ymd', strtotime($req_time)).$txn_nmbr;

                                $amount > 199000 && $refund = $amount - 199000;
                                $direct = $amount > 199000 ? 199000 : $amount;

                                if ($dist_details[0]['dbc']['direct_transfer'] == 1) {

                                        App::import('Controller', 'Shops');
                                        $shops_controller = new ShopsController;
                                        $shops_controller->constructClasses();

        //                                $password = $this->General->findVar('limit_password');

                                        $result = $this->amountTransfer($dist_details[0]['d']['mobile'], DISTRIBUTOR, $direct, $pay1_txn_id, $shops_controller, '1', $password);

                                        $this->General->logData("axis_bank_integration.txt", "$pay1_txn_id -> " . json_encode($result));

                                        if ($result['shopId']) {

                                                $res = $this->User->query("INSERT INTO account_txn_details (bank_id, bank_txn_id, pay1_txn_id, txn_status, description, amount, txn_date, submission_date, operation_date, account_category_id, type, type_id, refund, shop_tran_id, user_id, is_submitted) VALUES "
                                                        . "('15', '$txn_nmbr', '$pay1_txn_id', 'Cr', 'Axis Bank : ".$dist_details[0]['d']['mobile']."', '$amount', '".date('Y-m-d H:i:s', strtotime($req_time))."', '".date('Y-m-d', strtotime($req_time))."', '".date('Y-m-d', strtotime($req_time))."', '1', 'distributor', '".$dist_details[0]['d']['id']."', '$refund', '". $result['shopId'] ."', '".$dist_details[0]['d']['user_id']."', '1')");
                                        }

                                } else {

                                        $res = $this->User->query("INSERT INTO account_txn_details (bank_id, bank_txn_id, pay1_txn_id, txn_status, description, amount, txn_date, submission_date, account_category_id, type, type_id, refund, user_id, is_submitted) VALUES "
                                                . "('15', '$txn_nmbr', '$pay1_txn_id', 'Cr', 'Axis Bank : ".$dist_details[0]['d']['mobile']."', '$amount', '".date('Y-m-d H:i:s', strtotime($req_time))."', '".date('Y-m-d', strtotime($req_time))."', '1', 'distributor', '".$dist_details[0]['d']['id']."', '$refund', '".$dist_details[0]['d']['user_id']."', '0')");
                                }

                                $res == 1 && $status = '000';
                        }
                }

                $this->User->query("INSERT INTO bank_pay1_txn_mapping (pay1_req_id, pay1_txn_id, bank_req_id, bank_txn_id) VALUES ('$clt_txn_id', '$pay1_txn_id', '$req_id', '$txn_nmbr')");

                $response['Appl_User_ID'] = $app_user_id;
                $response['Req_id']       = $req_id;
                $response['txn_id']       = $txn_nmbr;
                $response['Clt_txn_id']   = $clt_txn_id;
                $response['Corp_code']    = $corp_code;
                $response['Stts_flg']     = $status;

                $this->General->logData("axis_bank_integration.txt", json_encode($response));

                echo json_encode($response);
        }

    private function encrypt($data, $key) {
        return strtoupper(hash_hmac("sha256", $data, $key));
    }

    function txnRecon() {
        if ($this->params['form']['service']) {
            $vendor = $this->Slaves->query("SELECT id, name FROM  `product_vendors`  WHERE service_id = " . $this->params['form']['serviceid']);
            echo json_encode($vendor);
            $this->autoRender = false;
        }

        $services = $this->Slaves->query("SELECT id, name FROM  `services` ");
        $this->set('services', $services);
    }

    function displayTxnRecon() {
        $this->autoRender = FALSE;

        $mapping = array('2' => array('date' => '12', 'txn_id' => '33', 'vendor_txn_id' => '15', 'total_amt' => '21', 'status' => '9', 'margin' => '', 'comm' => '', 'sc' => ''),
            '4' => array('date' => '1', 'txn_id' => '3', 'vendor_txn_id' => '2', 'total_amt' => '9', 'status' => '11', 'margin' => '', 'comm' => '14', 'sc' => '13'),
            '3' => array('date' => '2', 'txn_id' => '', 'vendor_txn_id' => '11', 'total_amt' => '4', 'status' => '', 'margin' => '8', 'comm' => '15', 'sc' => '14'),
            '1' => array('date' => '11', 'txn_id' => '', 'vendor_txn_id' => '5', 'total_amt' => '22', 'status' => '', 'margin' => '', 'comm' => '', 'sc' => ''),
            '5' => array('date' => '3', 'txn_id' => '', 'vendor_txn_id' => '1','total_amt' => '6', 'status' => '16', 'margin' => '', 'comm' => '8', 'sc' => '7'),
            '10'=> array('date' => '7', 'txn_id'=>'', 'vendor_txn_id' => '2', 'total_amt' => '4','status' => '', 'margin' => '', 'comm' => '', 'sc' => ''),
            '12'=> array('date' => '8', 'txn_id' => '','vendor_txn_id' => '0', 'total_amt' => '6' ,'status' => '', 'margin' => '', 'comm' => '', 'sc' => ''),
            '20'=> array('date' => '0', 'txn_id' => '55','vendor_txn_id' => '', 'total_amt' => '14' ,'status' => '40', 'margin' => '', 'comm' => '', 'sc' => '')
         );

        $statuslist = array('2' => array('Success' => '1'), '4' => array('Success' => '1', 'Response awaited' => '0', 'Refund pending' => '1'),'5' => array('Success' => '1', 'Pending' => '0'), '20' =>  array('Ticketed' => '1', 'Refunded' => '2'));

        $servers = array('10' => 'smartpay', '10' => 'smartpay', '12' => 'dmt', '8' => 'smartpay');

        if ($this->RequestHandler->isPost()) {
            $array_record['status'] = 0;
            $array_record['message'] = "Some Internal Error";
            $file = $_FILES['file']['name'];

            if (!empty($file)) {
                $allowedExtension = array("xls", 'csv');
                $getfileInfo = pathinfo($file, PATHINFO_EXTENSION);
                if ($_POST['vendor'] == 3 && $_POST['vendor'] == 20 && $getfileInfo != 'csv') {
                    echo json_encode(array('message' => "Please upload csv File", 'status' => 0));
                    die;
                } else if (($_POST['vendor'] == 1 || $_POST['vendor'] == 2 || $_POST['vendor'] == 4 || $_POST['vendor'] == 5) && $getfileInfo != 'xls') {
                    echo json_encode(array('message' => "Only xls format allowed", 'status' => 0));
                    die;
                }
                if (in_array($getfileInfo, $allowedExtension)) {
                    if ($getfileInfo == "xls") {
                        if (!move_uploaded_file($_FILES['file']['tmp_name'], "/tmp/" . $file)) {
                            echo json_encode(array('message' => "Failed to move uploaded file", 'status' => 0));
                            die;
                        }
                        chmod("/tmp/" . $file, 777);
                        $filepath = "/tmp/" . $file;
                        App::import('Vendor', 'excel_reader2');
                        $excel = new Spreadsheet_Excel_Reader($filepath, true);
                        $data = $excel->sheets[0]['cells'];
                    } else {

                        $file = fopen($_FILES['file']["tmp_name"], "r");
                        while (!feof($file)) {
                            $data[] = fgetcsv($file, 1024);
                        }
                        fclose($file);
                    }
                } else {
                    echo json_encode(array('message' => "Invalid File Format (only xls file allowed)", 'status' => 0));
                    die;
                }
                if (!in_array($_POST['vendor'], array_keys($mapping))) {
                    echo json_encode(array('message' => "Invalid File", 'status' => 0));
                    die;
                }
                $vendor = $mapping[$_POST['vendor']];

                $i = 0;
                foreach ($data as $key => $value) {
                    if (is_numeric(str_replace(',', '', $value[$vendor['total_amt']]))) {

                        if (in_array($_POST['vendor'], array_keys($mapping)) && ((is_numeric(round($value[$vendor['total_amt']])) && strlen($value[$vendor['total_amt']]) <= 7) &&
                                (is_numeric(str_replace("'", "", $value[$vendor['txn_id']])) || ctype_alnum($value[$vendor['vendor_txn_id']])))) {
                            if ($_POST['vendor'] == 1) {
                                if (strlen($value[$vendor['date']]) == 6) {
                                    $dt = '20' . substr($value[$vendor['date']], -6, 2) . '-' . substr($value[$vendor['date']], -4, 2) . '-' . substr($value[$vendor['date']], -2, 2);
                                    $date = date('Y-m-d', strtotime($dt));
                                }
                            } else if ($_POST['vendor'] == 4 || $_POST['vendor'] == 3) {
                                $date = date('Y-m-d', strtotime(str_replace("/", "-", $value[$vendor['date']])));
                            } else if ($_POST['vendor'] == 2) {
                                $dt = substr($value[$vendor['date']], -17, 2) . '-' . substr($value[$vendor['date']], -14, 2) . '-20' . substr($value[$vendor['date']], -11, 2);
                                $date = date('Y-m-d', strtotime(str_replace("'", "", $dt)));
                            } else if ($_POST['vendor'] == 5 || $_POST['vendor'] == 10 ) {
                                $date = date('Y-m-d', strtotime($value[$vendor['date']]));
                            } else if($_POST['vendor'] == 20) {
                                $dt = substr($value[$vendor['date']], -8, 4) . '-' . substr($value[$vendor['date']], -4, 2) . '-' . substr($value[$vendor['date']], -2, 2);
                                $date = date('Y-m-d', strtotime($dt));
                            } else if ($_POST['vendor'] == 12) {
                                $date = date('Y-m-d H:i:s', strtotime($value[$vendor['date']]));
                            }

    //                        if (!(preg_match("/^[a-zA-Z\: ]+$/", $value[$vendor['total_amt']], $matches) ||
    //                                preg_match("/^[a-zA-Z\: ]+$/", $value[$vendor['txn_id']], $matches) ||
    //                                preg_match("/^[a-zA-Z\: ]+$/", $value[$vendor['vendor_txn_id']], $matches) ||
    //                                preg_match("/^[a-zA-Z\: ]+$/", $value[$vendor['date']], $matches)) || 
    //                            !(preg_match("/^[a-zA-Z0-9\: ]+$/", current($value), $matches)) || 
    //                            !($vendor == 4 && $value[3] == 'N/A')) {
    //                            echo json_encode(array('message' => "Invalid File Selection", 'status' => 0));
    //                            die;
    //                        }

                               $i++;
                            if($i == 1){
                                $this->User->query("DELETE FROM `txns_recon` WHERE date = '$date' AND product_vendor_id = ".$_POST['vendor']);
                            }
                            
                            $txn_id = str_replace("'", "", $value[$vendor['txn_id']]);
                            $vendor_txn_id = (($_POST['vendor'] == '3' && strlen($value[$vendor['vendor_txn_id']]) == '10' ) ? '00' . $value[$vendor['vendor_txn_id']] : $value[$vendor['vendor_txn_id']]);
                            $total_amt = $value[$vendor['total_amt']];
                            $status = ($_POST['vendor'] == '1' || $_POST['vendor'] == '3' || $_POST['vendor'] == '10' || $_POST['vendor'] == '12') ? '1' : (isset($statuslist[$_POST['vendor']][$value[$vendor['status']]]) ? $statuslist[$_POST['vendor']][$value[$vendor['status']]] : '2');
                            $comm = $value[$vendor['comm']];
                            $sc = $value[$vendor['sc']];

                            $margin = 0;
                            if ($_POST['vendor'] == '3') {
                                $margin = $value[8] + $value[15] - ($value[15] * TDS_PERCENT / 100);
                            } else if ($_POST['vendor'] == '4') {
                                $gst_on_commission = $value[14] * SERVICE_TAX_PERCENT/100;
                                $margin = $value[9] +($value[13] -( $value[14] + $gst_on_commission - $value[15]));
                                $comm = $comm*1.18;
                                
                                if($value[4] == 'Refund'){
                                    $status = 2;
                                }
                                
                                if($value[4] == 'Verification Reversal' && $value[4] == 'Deposit'){
                                    continue;
                                }
                            } else if ($_POST['vendor'] == '5') {
                                $original_commission = $value[8] / 0.95 ;
                                $tds = $original_commission - $value[8];
                                $gst = $original_commission * SERVICE_TAX_PERCENT/100;
                                $margin = $value[6] + ($value[7] -($original_commission + $gst - $tds ) );
                                $comm = ($comm*100/95)*1.18;
                                $vendor_txn_id = ($total_amt > 5) ? $vendor_txn_id : $value[10];
                                $txn_id = ($total_amt <= 5) ? $txn_id : substr(trim($value[1]),5);
                                $sc = ($total_amt <= 5) ? $sc + $value[6]: $sc;
                                $total_amt = ($total_amt <= 5) ? 0 : $total_amt;
                                
                                if(substr($vendor_txn_id,0,1) == 'R') continue;
                                
                            } else if ($_POST['vendor'] == '20') {
                                if($statuslist[$_POST['vendor']][$value[$vendor['status']]] == '1'){
                                    $margin = $value[14] - $value[16] - $value[18] + $value[21] + $value[30] + $value[24];
                                }
                                if($statuslist[$_POST['vendor']][$value[$vendor['status']]] == '2'){
                                    $margin = $value[14] + $value[16] + $value[18] + $value[21] + $value[30] - $value[24] + $value[22] + $value[30];
                                }
                            }

                            $result = array();
                            $result['status'] = 'success';
                            $result['description'] = '';

                            //$exist = $this->Slaves->query("SELECT COUNT(id) count FROM `txns_recon` WHERE date = '$date' AND product_vendor_id = '" . $_POST['vendor'] . "' AND server = '" . $servers[$_POST['service']] . "'");

                            /*if ($exist[0][0]['count'] > 0) {
                                echo json_encode(array('message' => "File already uploaded", 'status' => 0));
                                die;
                            }*/

                            $total_trans_amt = str_replace(",", "",$total_amt) ;

                            if ($date == '' || $date == '1970-01-01' || !$this->General->dateValidate(str_replace(array('.', '/'), '-',date('Y-m-d', strtotime($date))))) {
                                $result['status'] = 'failed';
                                $result['description'] = 'Enter Valid Date';
                            } else if (strtotime($date) > strtotime(date('Y-m-d'))) {
                                $result['status'] = 'failed';
                                $result['description'] = 'Date cannot be greater tha current date';
                            } else if (!empty($value[$vendor['txn_id']]) && !is_numeric(str_replace("'", "", $value[$vendor['txn_id']]))) {
                                $result['status'] = 'failed';
                                $result['description'] = 'Invalid Transaction id';
                            } else if (!empty($vendor_txn_id) && preg_match('/^[w.-]+$/', $vendor_txn_id)) {
                                $result['status'] = 'failed';
                                $result['description'] = 'Invalid Vendor Transaction id';
                            } else if (!empty($total_amt) && !is_numeric($total_trans_amt)) {
                                $result['status'] = 'failed';
                                $result['description'] = 'Invalid Amount';
                            } else if (empty($txn_id) && empty($vendor_txn_id)) {
                                $result['status'] = 'failed';
                                $result['description'] = 'Both txn ids cannot be empty ';
                            }

                            $result_array = array();
                            if ($result['status'] == 'success') {
                                
                                if ($_POST['vendor'] == 1 && $total_amt != 0) {
                                    if ($this->User->query("INSERT INTO `txns_recon`( `txn_id`, `vendor_txn_id`, `amount`, `status`, `date`,`product_vendor_id`, `vendor_margin`,`server`,`commission`,`service_charge`) VALUES ('$txn_id','$vendor_txn_id','$total_amt','$status','$date','" . $_POST['vendor'] . "', $margin,'" . $servers[$_POST['service']] . "','$comm','$sc')")) {
                                        if (!in_array($vendor_txn_id, $result)) {
                                            $result_array = array('description' => $result['description'], 'txn_id' => $txn_id, 'vendor_txn_id' => $vendor_txn_id, 'amount' => $total_amt, 'status' => $status, 'date' => $date);
                                        }
                                    }
                                } else if($_POST['vendor'] == 5 && $vendor_txn_id != ''){
                                    if ($this->User->query("INSERT INTO `txns_recon`( `txn_id`, `vendor_txn_id`, `amount`, `status`, `date`,`product_vendor_id`, `vendor_margin`,`server`,`commission`,`service_charge`) VALUES ('$txn_id','$vendor_txn_id','$total_amt','$status','$date','" . $_POST['vendor'] . "', $margin,'" . $servers[$_POST['service']] . "','$comm','$sc')")) {
                                        if (!in_array($vendor_txn_id, $result)) {
                                            $result_array = array('description' => $result['description'], 'txn_id' => $txn_id, 'vendor_txn_id' => $vendor_txn_id, 'amount' => $total_amt, 'status' => $status, 'date' => $date);
                                        }
                                    }
                                } /*else if ($_POST['vendor'] == 20 && in_array($txn_id, $txn_ids)) {
                                    $this->User->query("UPDATE `txns_recon` SET `amount`= amount + '$total_amt',`vendor_margin`= vendor_margin + '$margin' WHERE `txn_id`= '$txn_id' AND `product_vendor_id`= '".$_POST['vendor']."' AND date = '$date'");
                                } else if($_POST['vendor'] == 20) {
                                    if ($this->User->query("INSERT INTO `txns_recon`( `txn_id`, `vendor_txn_id`, `amount`, `status`, `date`,`product_vendor_id`, `vendor_margin`,`server`,`commission`,`service_charge`) VALUES ('$txn_id','$vendor_txn_id','$total_amt','$status','$date','" . $_POST['vendor'] . "', $margin,'" . $servers[$_POST['service']] . "','$comm','$sc')")) {
                                        $txn_ids[] = $txn_id;
                                        if (!in_array($txn_id, $result)) {
                                            $result_array = array('description' => $result['description'], 'txn_id' => $txn_id, 'vendor_txn_id' => $vendor_txn_id, 'amount' => $total_amt, 'status' => $status, 'date' => $date);
                                        }
                                    }
                                }*/ else if ($_POST['vendor'] != 1) {
                                    if ($this->User->query("INSERT INTO `txns_recon`( `txn_id`, `vendor_txn_id`, `amount`, `status`, `date`,`product_vendor_id`, `vendor_margin`,`server`,`commission`,`service_charge`) VALUES ('$txn_id','$vendor_txn_id','$total_trans_amt','$status','$date','" . $_POST['vendor'] . "', $margin,'" . $servers[$_POST['service']] . "','$comm','$sc')")) {
                                        if (!in_array($vendor_txn_id, $result)) {
                                            $result_array = array('description' => $result['description'], 'txn_id' => $txn_id, 'vendor_txn_id' => $vendor_txn_id, 'amount' => $total_amt, 'status' => $status, 'date' => $date);
                                        }
                                    }
                                } else {
                                    $result['status'] = 'failed';
                                    $result['description'] = 'Some Problem Occured';
                                }
                            }
                            if ($result['status'] == 'success') {
                                !empty($result_array) && $array_record['success'][] = $result_array;
                            } else {
                                !empty($result_array) && $array_record['fail'][] = $result_array;
                            }
                            $array_record['status'] = 1;
                            $array_record['message'] = "Success";
                        }
                    }
                }

                echo json_encode($array_record);
                die;
            }
        }
    }

    function txnSearch() {
        $services = $this->Slaves->query("SELECT id,name FROM services WHERE toShow = 1 AND id > 7");

        if ($this->params['form']['serviceflag']) {
            $product_vendor = $this->Slaves->query("SELECT id,name FROM  `product_vendors` WHERE service_id = '" . $this->params['form']['servicedata'] . "'");
            echo json_encode($product_vendor);
            $this->autoRender = false;
        }

        $this->set('services', $services);
    }

    function dashboard() {
        $this->autoRender = FALSE;

        if ($this->params['form']['filter']) {

            $fromdate = $this->params['form']['fromdate'] ? date('Y-m-d', strtotime($this->params['form']['fromdate'])) : date('Y-m-d');
            $todate = $this->params['form']['todate'] ? date('Y-m-d', strtotime($this->params['form']['todate'])) : date('Y-m-d');
            $date_diff = floor((strtotime($todate) - strtotime($fromdate)) / (60 * 60 * 24));
            $serviceid = $this->params['form']['service'];
            $vendorid = $this->params['form']['vendor'];

            if (!$this->General->dateValidate($fromdate) && empty($fromdate)) {
                echo json_encode(array('status' => 'failed', 'msg' => 'Invalid From date'));
                die;
            }
            if (!$this->General->dateValidate($todate) && empty($todate)) {
                echo json_encode(array('status' => 'failed', 'msg' => 'Invalid To date'));
                die;
            }

            if ($fromdate <= $todate) {
                if ($date_diff <= 7) {

                    $profit_loss = $this->Slaves->query("SELECT products.name, products.id,ROUND(SUM((wallets_transactions.vendor_commission + wallets_transactions.service_charge - wallets_transactions.vendor_service_charge) / 1.18 - wallets_transactions.commission), 2) amount,
                                                SUM(amount) total_sale, COUNT(id) total_txn_count, ROUND(SUM(service_charge),2) retailer_service_charge,
                                                ROUND(SUM(vendor_service_charge),2) vendor_service_charge, ROUND(SUM(commission),2) retailer_commission,
                                                ROUND(SUM(vendor_commission),2) vendor_commission FROM wallets_transactions
                                                JOIN products ON (products.id = wallets_transactions.product_id)
                                                WHERE wallets_transactions.vendor_id = '$vendorid' AND wallets_transactions.status = '1'
                                                AND wallets_transactions.date >= '$fromdate' AND wallets_transactions.date <= '$todate'
                                                AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)
                                                GROUP BY wallets_transactions.product_id");

                    foreach ($profit_loss as $res) {
                        $total_profit_loss += $res[0]['amount'];
                        $profit[$res['products']['id']] = array('amount' => $res[0]['amount'], 'product_name' => $res['products']['name'],'total_sale' => $res[0]['total_sale'], 'total_txn_count' => $res[0]['total_txn_count'], 'retailer_service_charge' => $res[0]['retailer_service_charge'], 'vendor_service_charge' => $res[0]['vendor_service_charge'], 'retailer_commission' => $res[0]['retailer_commission'], 'vendor_commission' => $res[0]['vendor_commission']);
                    }

                    $mismatchtxn = $this->Slaves->query("SELECT  MAX(txn_id) txn_id, MAX(vendor_txn_id) vendor_txn_id,MAX(date) date,MAX(vendor_id) vendor_id,
                                    MAX(amount) amount,MAX(status) status,MAX(recon_txn) recon_txn,MAX(recon_amt) recon_amt,
                                    MAX(recon_status) recon_status,MAX(vendor_txn) vendor_txn,MAX(product_vendor_id) product_vendor_id
                                    FROM
                                    ((SELECT  wallets_transactions.txn_id,wallets_transactions.vendor_refid as vendor_txn_id,
                                                    wallets_transactions.date,wallets_transactions.vendor_id,ROUND(wallets_transactions.amount, 0) as amount,
                                                    wallets_transactions.status,txns_recon.txn_id as recon_txn,ROUND(txns_recon.amount, 0) as recon_amt,
                                                    txns_recon.status as recon_status,txns_recon.vendor_txn_id as vendor_txn,
                                                    txns_recon.product_vendor_id
                                    FROM
                                                    wallets_transactions
                                    LEFT JOIN
                                                    txns_recon ON (txns_recon.txn_id = wallets_transactions.txn_id
                                                    AND wallets_transactions.server = txns_recon.server
                                                    AND wallets_transactions.vendor_id = txns_recon.product_vendor_id)
                                    WHERE
                                            wallets_transactions.date >= '$fromdate' AND wallets_transactions.date <= '$todate'
                                            AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)
                                            AND wallets_transactions.vendor_id = '$vendorid' AND wallets_transactions.status = '1')
                                    UNION
                                    (SELECT  wallets_transactions.txn_id,wallets_transactions.vendor_refid as vendor_txn_id,
                                            wallets_transactions.date,wallets_transactions.vendor_id,ROUND(wallets_transactions.amount, 0) as amount,
                                            wallets_transactions.status,txns_recon.txn_id as recon_txn,ROUND(txns_recon.amount, 0) as recon_amt,
                                            txns_recon.status as recon_status,txns_recon.vendor_txn_id as vendor_txn,txns_recon.product_vendor_id
                                    FROM
                                            wallets_transactions
                                    LEFT JOIN
                                            txns_recon ON (wallets_transactions.vendor_refid = txns_recon.vendor_txn_id
                                            AND wallets_transactions.server = txns_recon.server
                                            AND wallets_transactions.vendor_id = txns_recon.product_vendor_id)
                                    WHERE
                                            wallets_transactions.date >= '$fromdate' AND wallets_transactions.date <= '$todate'
                                            AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)
                                            AND wallets_transactions.vendor_id = '$vendorid' AND wallets_transactions.status = '1')) txn
                                            GROUP BY txn_id, vendor_txn_id
                                    UNION
                                    SELECT MAX(txn_id) txn_id,MAX(vendor_txn_id) vendor_txn_id,MAX(date) date, MAX(vendor_id) vendor_id,
                                            MAX(amount) amount, MAX(status) status,MAX(recon_txn) recon_txn,MAX(recon_amt) recon_amt,
                                            MAX(recon_status) recon_status,MAX(vendor_txn) vendor_txn,MAX(product_vendor_id) product_vendor_id
                                    FROM
                                    ((SELECT wallets_transactions.txn_id,wallets_transactions.vendor_refid as vendor_txn_id,txns_recon.date,
                                            wallets_transactions.vendor_id,ROUND(wallets_transactions.amount, 0) as amount,wallets_transactions.status,
                                            txns_recon.txn_id as recon_txn,ROUND(txns_recon.amount, 0) as recon_amt,txns_recon.status as recon_status,
                                            txns_recon.vendor_txn_id as vendor_txn,txns_recon.product_vendor_id
                                    FROM
                                            wallets_transactions
                                    RIGHT JOIN
                                            txns_recon ON (txns_recon.txn_id = wallets_transactions.txn_id
                                            AND wallets_transactions.server = txns_recon.server
                                            AND wallets_transactions.vendor_id = txns_recon.product_vendor_id)
                                    WHERE
                                            txns_recon.date >= '$fromdate' AND txns_recon.date <= '$todate'
                                            AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)
                                            AND txns_recon.product_vendor_id = '$vendorid' AND wallets_transactions.status = '1')
                                    UNION
                                    (SELECT wallets_transactions.txn_id,wallets_transactions.vendor_refid as vendor_txn_id,
                                            txns_recon.date,wallets_transactions.vendor_id,ROUND(wallets_transactions.amount, 0) as amount,
                                            wallets_transactions.status,txns_recon.txn_id as recon_txn,ROUND(txns_recon.amount, 0) as recon_amt,
                                            txns_recon.status as recon_status,txns_recon.vendor_txn_id as vendor_txn,txns_recon.product_vendor_id
                                    FROM
                                            wallets_transactions
                                    RIGHT JOIN txns_recon ON (wallets_transactions.vendor_refid = txns_recon.vendor_txn_id
                                            AND wallets_transactions.server = txns_recon.server
                                            AND wallets_transactions.vendor_id = txns_recon.product_vendor_id)
                                    WHERE
                                            txns_recon.date >= '$fromdate' AND txns_recon.date <= '$todate'
                                            AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)
                                            AND txns_recon.product_vendor_id = '$vendorid' AND wallets_transactions.status = '1')) txn
                                            GROUP BY recon_txn,vendor_txn");

                    $vendormargin = $this->Slaves->query("SELECT wallets_transactions.txn_id, wallets_transactions.vendor_refid as vendor_txn_id,
                                                          wallets_transactions.date,wallets_transactions.vendor_id, ROUND(wallets_transactions.amount, 0) as amount,
                                                          wallets_transactions.status, txns_recon.txn_id as recon_txn,
                                                          ROUND(txns_recon.amount, 0) as recon_amt, txns_recon.status as rrecon_txnecon_status,
                                                          txns_recon.vendor_txn_id as vendor_txn, txns_recon.vendor_margin as margin,
                                                          wallets_transactions.vendor_settled_amount, txns_recon.vendor_margin,
                                                          IF(wallets_transactions.cr_db = 'db',
                                                                wallets_transactions.vendor_service_charge - wallets_transactions.vendor_commission,
                                                                wallets_transactions.vendor_commission - wallets_transactions.vendor_service_charge) vendor_mrgin
                                                          FROM txns_recon
                                                          JOIN wallets_transactions ON (txns_recon.txn_id = wallets_transactions.txn_id
                                                                AND wallets_transactions.server = txns_recon.server
                                                                AND wallets_transactions.vendor_settled_amount != txns_recon.vendor_margin
                                                                AND txns_recon.product_vendor_id = wallets_transactions.vendor_id)
                                                                JOIN
                                                          product_vendors ON (product_vendors.id = txns_recon.product_vendor_id)
                                                          JOIN services ON (services.id = product_vendors.service_id)
                                                          WHERE txns_recon.amount = wallets_transactions.amount
                                                                AND txns_recon.status = wallets_transactions.status
                                                                AND wallets_transactions.date >= '$fromdate'
                                                                AND wallets_transactions.date <= '$todate'
                                                                AND wallets_transactions.vendor_id = '$vendorid' AND wallets_transactions.status = '1'
                                                                AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)
                                                          UNION
                                                          SELECT wallets_transactions.txn_id, wallets_transactions.vendor_refid as vendor_txn_id,
                                                          wallets_transactions.date,wallets_transactions.vendor_id, ROUND(wallets_transactions.amount, 0) as amount,
                                                          wallets_transactions.status, txns_recon.txn_id as recon_txn, ROUND(txns_recon.amount, 0) as recon_amt,
                                                          txns_recon.status as rrecon_txnecon_status, txns_recon.vendor_txn_id as vendor_txn,
                                                          txns_recon.vendor_margin as margin, wallets_transactions.vendor_settled_amount,txns_recon.vendor_margin,
                                                          IF(wallets_transactions.cr_db = 'db',
                                                                wallets_transactions.vendor_service_charge - wallets_transactions.vendor_commission,
                                                                wallets_transactions.vendor_commission - wallets_transactions.vendor_service_charge) vendor_mrgin
                                                          FROM txns_recon
                                                          JOIN wallets_transactions ON (wallets_transactions.vendor_refid = txns_recon.vendor_txn_id
                                                                AND wallets_transactions.server = txns_recon.server
                                                                AND wallets_transactions.vendor_settled_amount != txns_recon.vendor_margin
                                                                AND txns_recon.product_vendor_id = wallets_transactions.vendor_id)
                                                          JOIN product_vendors ON (product_vendors.id = txns_recon.product_vendor_id)
                                                          JOIN services ON (services.id = product_vendors.service_id)
                                                          WHERE txns_recon.amount = wallets_transactions.amount
                                                                AND txns_recon.status = wallets_transactions.status
                                                                AND wallets_transactions.date >= '$fromdate'
                                                                AND wallets_transactions.date <= '$todate'
                                                                AND wallets_transactions.vendor_id = '$vendorid' AND wallets_transactions.status = '1'
                                                                AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)");

                    foreach ($vendormargin as $res) {
                        if ($res[0]['vendor_mrgin'] != $res['txns_recon']['margin']) {
                            $total_vendorMargin += $res[0]['vendor_mrgin'];
                        }
                    }

                    $loss_txns = $this->Slaves->query("SELECT products.name,wallets_transactions.date, wallets_transactions.amount,
                                                        wallets_transactions.amount_settled,wallets_transactions.vendor_settled_amount,
                                                        wallets_transactions.vendor_refid,wallets_transactions.status,wallets_transactions.cr_db, wallets_transactions.txn_id,
                                                        IF(wallets_transactions.cr_db = 'db',
                                                        wallets_transactions.amount_settled - wallets_transactions.vendor_settled_amount,
                                                        wallets_transactions.vendor_settled_amount - wallets_transactions.amount_settled) amount FROM services
                                                        JOIN products ON (products.service_id = services.id)
                                                        JOIN wallets_transactions ON (wallets_transactions.product_id = products.id)
                                                        WHERE wallets_transactions.vendor_id = '$vendorid' AND wallets_transactions.status = '1'
                                                        AND wallets_transactions.date >= '$fromdate' AND wallets_transactions.date <= '$todate'
                                                        AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)
                                                        HAVING amount < 0");

                    $bank_settlement_pending = $this->Slaves->query("SELECT * FROM wallets_transactions WHERE amt_remaining_settlement > 0
                                        AND settlement_mode = 1 AND status = '1' AND vendor_id = $vendorid AND wallets_transactions.date >= '$fromdate' AND
                                        wallets_transactions.date <= '$todate' AND (wallets_transactions.reversal_date != date OR wallets_transactions.reversal_date IS NULL)");

                    $txnmatch_count = $this->Slaves->query("SELECT txns_recon.txn_id, txns_recon.vendor_txn_id FROM txns_recon
                                                            JOIN wallets_transactions ON (txns_recon.txn_id = wallets_transactions.txn_id
                                                                 AND wallets_transactions.server = txns_recon.server
                                                                 AND txns_recon.product_vendor_id = wallets_transactions.vendor_id)
                                                            JOIN product_vendors ON (product_vendors.id = txns_recon.product_vendor_id)
                                                            JOIN services ON (services.id = product_vendors.service_id)
                                                            WHERE txns_recon.amount = wallets_transactions.amount
                                                                 AND txns_recon.status = wallets_transactions.status
                                                                 AND wallets_transactions.date >= '$fromdate'
                                                                 AND wallets_transactions.date <= '$todate'
                                                                 AND wallets_transactions.vendor_id = '$vendorid' AND wallets_transactions.status = '1'
                                                                 AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)
                                                            UNION
                                                            SELECT txns_recon.txn_id, txns_recon.vendor_txn_id FROM txns_recon
                                                            JOIN wallets_transactions ON (wallets_transactions.vendor_refid = txns_recon.vendor_txn_id
                                                                 AND wallets_transactions.server = txns_recon.server
                                                                 AND txns_recon.product_vendor_id = wallets_transactions.vendor_id)
                                                            JOIN product_vendors ON (product_vendors.id = txns_recon.product_vendor_id)
                                                            JOIN services ON (services.id = product_vendors.service_id)
                                                            WHERE txns_recon.amount = wallets_transactions.amount
                                                                AND txns_recon.status = wallets_transactions.status
                                                                AND wallets_transactions.date >= '$fromdate'
                                                                AND wallets_transactions.date <= '$todate'
                                                                AND wallets_transactions.vendor_id = '$vendorid' AND wallets_transactions.status = '1'
                                                                AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)");
                    foreach ($txnmatch_count as $txn_count) {
                        $match_count += 1;
                    }

//                    $settleBank = $this->Slaves->query("SELECT COUNT(txn_id) count, SUM(amount) sum FROM wallets_transactions
//                                                        WHERE date >= '$fromdate' AND date <= '$todate'  AND vendor_id = '$vendorid' AND settlement_mode = 1
//                                                        AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)");
//
//                    $settleWallet = $this->Slaves->query("SELECT COUNT(txn_id) count, SUM(amount) sum FROM wallets_transactions
//                                                          WHERE date >= '$fromdate' AND date <= '$todate'
//                                                          AND wallets_transactions.vendor_id = '$vendorid' AND settlement_mode = 0
//                                                          AND (wallets_transactions.reversal_date != wallets_transactions.date OR wallets_transactions.reversal_date IS NULL)");

                    $settlement = $this->Slaves->query("SELECT wallets_transactions.txn_id, wallets_transactions.amount, wallets_transactions.settlement_mode, settlement_history.settlement_mode
                                                            FROM wallets_transactions
                                                            LEFT JOIN settlement_history ON ( wallets_transactions.txn_id = settlement_history.txn_id AND wallets_transactions.amount = settlement_history.amount_settled )
                                                            WHERE wallets_transactions.date >=  '$fromdate' AND wallets_transactions.date <=  '$todate'
                                                            AND wallets_transactions.vendor_id =  '$vendorid' AND wallets_transactions.status = '1' AND (wallets_transactions.reversal_date != wallets_transactions.date
                                                            OR wallets_transactions.reversal_date IS NULL)");

                    $bank_settlement = array();
                    foreach($settlement as $st){
                        if($st['settlement_history']['settlement_mode'] != NULL || $st['settlement_history']['settlement_mode'] != ''){
                            $bank_settlement[$st['settlement_history']['settlement_mode']]['count'] += 1;
                            $bank_settlement[$st['settlement_history']['settlement_mode']]['amount'] += $st['wallets_transactions']['amount'];
                        } else {
                            $bank_settlement[$st['wallets_transactions']['settlement_mode']]['count'] += 1;
                            $bank_settlement[$st['wallets_transactions']['settlement_mode']]['amount'] += $st['wallets_transactions']['amount'];
                        }
                    }
                    $bank_settle_amt = isset($bank_settlement[1]['amount']) ? $bank_settlement[1]['amount'] : 0;
                    $bank_settle_count = isset($bank_settlement[1]['count']) ? $bank_settlement[1]['count'] : 0;
                    $wallet_settle_amt = isset($bank_settlement[0]['amount']) ? $bank_settlement[0]['amount'] : 0;
                    $wallet_settle_count = isset($bank_settlement[0]['count']) ? $bank_settlement[0]['count'] : 0;

                    $totalSum = $this->Slaves->query("SELECT sum(amount) sum FROM wallets_transactions WHERE date >= '$fromdate' AND date <= '$todate' AND vendor_id = '$vendorid' AND status = '1' AND (reversal_date != date OR reversal_date IS NULL)");

                    if($vendorid != 20){
                        $totalsale = $totalSum[0][0]['sum'];
                    } else {
                        $wallet_cancellation = $this->Slaves->query("SELECT sum(amount_settled) as amt FROM wallet_partial_cancellations where date >= '$fromdate' AND date <= '$todate'");
                        $txns_recon = $this->Slaves->query("SELECT SUM( amount ) as recon_amt FROM  `txns_recon` WHERE date >= '$fromdate' AND date <= '$todate' AND  `product_vendor_id` =$vendorid");
                        $totalsale = $txns_recon[0][0]['recon_amt'] - $wallet_cancellation[0][0]['amt'];
                    }

                    $count = array('txnmatch_count' => $match_count, 'settlebank' => $bank_settle_amt, 'settlebank1' => $bank_settle_count,
                        'settleWallet' => $wallet_settle_amt, 'settleWallet1' => $wallet_settle_count, 'totalSum' => $totalsale);

                    echo json_encode(array('profit_loss' => $profit_loss, 'mismatchtxn' => $mismatchtxn, 'vendormargin' => $vendormargin, 'bank_settlement_pending' => $bank_settlement_pending, 'loss_txns' => $loss_txns, 'count' => $count, 'vendoramt_sum' => $vendoramt_sum[0][0]['amount'], 'total_profit_loss' => $total_profit_loss, 'total_vendorMargin' => $total_vendorMargin, 'profit' => $profit));
                    die;
                } else {
                    echo json_encode(array('status' => 'failed', 'msg' => 'Date Range should be within 7 days'));
                    die;
                }
            }
        }
    }

    function vendorSearch() {
        $services = $this->Slaves->query("SELECT id,name FROM services WHERE id > 7");

        if ($this->params['form']['serviceflag']) {
            $product_vendor = $this->Slaves->query("SELECT id,name FROM  `product_vendors` WHERE service_id = '" . $this->params['form']['servicedata'] . "'");
            echo json_encode($product_vendor);
            $this->autoRender = false;
        }

        $this->set('services', $services);
    }

    function vendorDashboard() {
        $this->autoRender = false;

        if ($this->params['form']['update_opening']) {
            $date = $this->params['form']['opening_date'] ? date('Y-m-d', strtotime($this->params['form']['opening_date'])) : date('Y-m-d');
            $vendorid = $this->params['form']['vendor'];
            $opening = isset($this->params['form']['opening']) ? $this->params['form']['opening'] : 0;

            $exist = $this->Slaves->query("SELECT * FROM `vendor_recon` WHERE vendor_id = '" . $vendorid . "' AND date = '" . date("Y-m-d") . "'");

            if(!empty($exist)){
                $this->User->query("UPDATE `vendor_recon` SET `closing`= '$opening' WHERE `date` = '".date('Y-m-d', strtotime('-1 days', strtotime($date)))."' AND `vendor_id` = '$vendorid'");
                $this->User->query("UPDATE `vendor_recon` SET `opening`= '$opening' WHERE `date` = '$date' AND `vendor_id` = '$vendorid'");
            } else {

                $this->User->query("UPDATE `vendor_recon` SET `closing`= '$opening' WHERE `date` = '".date('Y-m-d', strtotime('-1 days', strtotime($date)))."' AND `vendor_id` = '$vendorid'");
                if (!$this->User->query("INSERT INTO `vendor_recon`(`date`, `vendor_id`, `opening`) VALUES ('" . date("Y-m-d") . "','$vendorid','$opening')")) {
                    echo json_encode(array('status' => '0')); die;
                }
            }

            echo json_encode(array('status' => '1')); die;
        }
        if ($this->params['form']['update_loss']) {
            $date = $this->params['form']['loss_date'] ? date('Y-m-d', strtotime($this->params['form']['loss_date'])) : date('Y-m-d');
            $vendorid = $this->params['form']['product_vendor'];
            $loss = isset($this->params['form']['loss']) ? $this->params['form']['loss'] : 0;

            $exist = $this->Slaves->query("SELECT * FROM `vendor_recon` WHERE vendor_id = '".$vendorid."' AND date = '$date'");

            if(!empty($exist)){
                $this->User->query("UPDATE `vendor_recon` SET `loss`= '$loss' WHERE `date` = '$date' AND `vendor_id` = '$vendorid'");
            } else {
                if (!$this->User->query("INSERT INTO `vendor_recon`(`date`,`vendor_id`,`loss`) VALUES ('". date("Y-m-d") ."','$vendorid','$loss')")) {
                    echo json_encode(array('status' => '0')); die;
                }
            }
            echo json_encode(array('status' => '1')); die;
        }

        if ($this->params['form']['commission_adjustment']){
            $date = $this->params['form']['commission_date'] ? date('Y-m-d', strtotime($this->params['form']['commission_date'])) : date('Y-m-d');
            $vendorid = $this->params['form']['vendor_list'];
            $commission = isset($this->params['form']['commission']) ? $this->params['form']['commission'] : 0;

            $exist = $this->User->query("SELECT * FROM `vendor_recon` WHERE vendor_id = '".$vendorid."' AND date = '$date'");
            
            if(!empty($exist)){
                $this->User->query("UPDATE `vendor_recon` SET `commission_adjustment`= '$commission' WHERE `date` = '$date' AND `vendor_id` = '$vendorid'");
            } else {
                if (!$this->User->query("INSERT INTO `vendor_recon`(`date`, `vendor_id`, `commission_adjustment`) VALUES ('" . date("Y-m-d") . "','$vendorid','$commission')")) {
                    echo json_encode(array('status' => '0')); die;
                }
            }
            echo json_encode(array('status' => '1')); die;
        }

        if ($this->params['form']['service_charge_adjustment']){
            $date = $this->params['form']['service_charge_date'] ? date('Y-m-d', strtotime($this->params['form']['service_charge_date'])) : date('Y-m-d');
            $vendorid = $this->params['form']['vendors_list'];
            $service_charge = isset($this->params['form']['service_charge']) ? $this->params['form']['service_charge'] : 0;

            $exist = $this->User->query("SELECT * FROM `vendor_recon` WHERE vendor_id = '".$vendorid."' AND date = '$date'");

            if(!empty($exist)){
                $this->User->query("UPDATE `vendor_recon` SET `service_charge_adjustment`= '$service_charge' WHERE `date` = '$date' AND `vendor_id` = '$vendorid'");
            } else {
                if (!$this->User->query("INSERT INTO `vendor_recon`(`date`, `vendor_id`, `service_charge_adjustment`) VALUES ('". date("Y-m-d") ."','$vendorid','$service_charge')")) {
                    echo json_encode(array('status' => '0')); die;
                }
            }
                echo json_encode(array('status' => '1')); die;
        }

        if ($this->params['form']['filter']) {

            $fromdate = $this->params['form']['fromdate'] ? date('Y-m-d', strtotime($this->params['form']['fromdate'])) : date('Y-m-d');
            $todate = $this->params['form']['todate'] ? date('Y-m-d', strtotime($this->params['form']['todate'])) : date('Y-m-d');
            $vendorid = $this->params['form']['vendor'];
            $date_diff = floor((strtotime($todate) - strtotime($fromdate)) / (60 * 60 * 24));

            if (!$this->General->dateValidate($fromdate) && empty($fromdate)) {
                echo json_encode(array('status' => 'failed', 'msg' => 'Invalid From date')); die;
            }
            if (!$this->General->dateValidate($todate) && empty($todate)) {
                echo json_encode(array('status' => 'failed', 'msg' => 'Invalid To date')); die;
            }
            if ($fromdate <= $todate) {
                if ($date_diff <= 31) {
                    $vendor_recon = $this->Slaves->query("SELECT * FROM vendor_recon JOIN product_vendors ON product_vendors.id = vendor_recon.vendor_id
                        WHERE `vendor_id` = '$vendorid' AND date >= '$fromdate' AND date <=  '$todate'");
                    echo json_encode($vendor_recon); die;
                } else {
                    echo json_encode(array('status' => 'failed', 'msg' => 'Date Range should be within 31 days')); die;
                }
            }
        }
    }

    function distBankMapping() {
        $to_save = true;
        if ($this->data) {

            if ($this->data['distributor'] == 0) {
                $msg = "Please select Distributor";
                $to_save = false;
            } else if (empty($this->data['card']) || !ctype_alnum($this->data['card'])) {
                $msg = "Please enter proper card number";
                $to_save = false;
            }
        }

        if ($this->data['distributor'] > 0 && $to_save == true) {

            $distributor = $this->data['distributor'];
            $dist_name   = $this->data['name'];
            $contact     = $this->data['contact'];
            $bank        = $this->data['bank'];
            $card        = $this->data['card'];
            $auto_manual = $this->data['optradio'];
            $dist_data = $this->Slaves->query("SELECT user_id FROM distributors  where distributors.id='" . $distributor . "'");
            if ($dist_data) {
                $dist_bank = $this->Slaves->query("SELECT * FROM distributor_bank_cards where distributor_id = '" . $distributor . "' AND  bank_id = '".$bank."'");
                if ($dist_bank) {
                    $data =$this->User->query("UPDATE  distributor_bank_cards SET  direct_transfer = '" . $auto_manual . "',card_no = '".$card."' where distributor_id='" . $distributor . "' AND bank_id = '" . $bank . "'  ");
                    $msg = "Data Updated Successfully";
                    $to_save = true;
                } else {
                    $data = $this->User->query("INSERT INTO distributor_bank_cards (bank_id,distributor_id,card_no,direct_transfer) VALUES ('$bank','$distributor','$card','$auto_manual')");
                    $msg = "Data Updated Successfully";
                    $to_save = true;
                }
            }
        }
        $bank_details = $this->Slaves->query("SELECT id, bank, bank_name from bank_details where account_no != ''");
        if (isset($msg)) {
            $msg = "<div id ='alert' class='alert alert-" . (($to_save == true) ? "success" : "danger") . "'>" . $msg . "</div>";
            $this->Session->setFlash($msg);
        }
        $this->set('bank_details', $bank_details);
        $this->set('dist_bank', $dist_bank[0]['distributor_bank_cards']['direct_transfer']);
    }

    function distributorList() {
        $this->autoRender = false;
        $search = $_POST['search'];
        $distributors = $this->Slaves->query("SELECT * FROM  `distributors` WHERE (id LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%' OR mobile LIKE '%" . $search . "%') AND active_flag = 1 LIMIT 0 , 5;");

        $dist_ids = array_map(function($element) {
            return $element['distributors']['id'];
        }, $distributors);
        $temp = $this->Shop->getUserLabelData($dist_ids, 2, 3);

        $dists = array();
        foreach ($distributors as $distributor) {
            $user_id = $distributor['distributors']['id'];
            $dists[] = array("name" => $temp[$user_id]['imp']['shop_est_name'] . ' - ' . $distributor['distributors']['id'], "id" => $distributor['distributors']['id'], "dist_name" => $distributor['distributors']['name'], "mobile" => $distributor['distributors']['mobile']);
        }
        echo json_encode($dists);
    }

    function banklist() {
        $this->autoRender = false;
        $bank_id = $_POST['bank_id'];
        $dist_id = $_POST['dist_id'];
        $bank_data = $this->Slaves->query("SELECT * FROM distributor_bank_cards where bank_id = '" . $bank_id . "' AND distributor_id = '" . $dist_id . "'");
        $card_no = array();
        $card_no['card_no'] = $bank_data[0]['distributor_bank_cards']['card_no'];
        $card_no['type'] = $bank_data[0]['distributor_bank_cards']['direct_transfer'];
        echo json_encode($card_no);
    }


    function retailerList(){
        $this->autoRender = false;
        $search = $_POST['search'];
        $retailers = $this->Slaves->query("SELECT * FROM  `retailers` WHERE (id LIKE '%" . $search . "%' OR shopname LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%') AND toshow = 1 LIMIT 0 , 5;");
        $ret_ids = array_map(function($element) {
            return $element['retailers']['id'];
        }, $retailers);

        $ret = array();
        foreach ($retailers as $retailer) {
            $user_id = $retailer['retailers']['id'];
            $rets[] = array( 'name' => $retailer['retailers']['id']. ' - ' . $retailer['retailers']['shopname'] . ' (' .$retailer['retailers']['mobile']. ')' , "id" => $retailer['retailers']['id'], "ret_name" => $retailer['retailers']['name'], "mobile" => $retailer['retailers']['mobile']);
        }
        echo json_encode($rets);
    }

    function payuSalesReport() {

        $date = date('d-m-Y');
        $this->set('date', $date);
    }

    function viewPayUReport() {
        $this->autoRender = false;

        if ($this->params['form']['viewdata']) {
            $retailers = $this->params['form']['ret'];
            $from_date = $this->params['form']['from_date'] ? date('Y-m-d', strtotime($this->params['form']['from_date'])) : date('Y-m-d');
            $to_date = $this->params['form']['to_date'] ? date('Y-m-d', strtotime($this->params['form']['to_date'])) : date('Y-m-d');
            $date_diff = floor((strtotime($to_date) - strtotime($from_date)) / (60 * 60 * 24));

            if (!$this->General->dateValidate($from_date) && empty($from_date)) {
                echo json_encode(array('status' => 'failed', 'msg' => 'Invalid From date'));
                die;
            }
            if (!$this->General->dateValidate($to_date) && empty($to_date)) {
                echo json_encode(array('status' => 'failed', 'msg' => 'Invalid To date'));
                die;
            }

            if ($from_date <= $to_date) {
                if ($date_diff <= 7) {
                    if ($retailers != "") {
                        $ret_id = " AND target_id = $retailers";
                    }
                    $salesmen_data = $this->Slaves->query("SELECT *,retailers.id,retailers.mobile,retailers.shopname FROM shop_transactions JOIN retailers ON (shop_transactions.target_id=retailers.id) WHERE date >= '" . $from_date . "' AND date <= '" . $to_date . "' $ret_id  AND type_flag = '5' AND confirm_flag != '1'");
                    echo json_encode($salesmen_data);
                    die;
                } else {
                    echo json_encode(array('status' => 'failed', 'msg' => 'Date Range should be within 7 days'));
                    die;
                }
            } else {
                echo json_encode(array('status' => 'failed', 'msg' => 'From date should be less than To date'));
                die;
            }
        }
    }

    function floatReport($back = 0, $from_date = null, $to_date = null) {
        
            $from_date = ($from_date == null) ? date('Y-m-d', strtotime('-8 days')) : date('Y-m-d', strtotime($from_date));
            $to_date = ($to_date == null) ? date('Y-m-d', strtotime('-1 days')) : date('Y-m-d', strtotime($to_date));
            //echo $from_date."===".$to_date;exit;
            $this->set('from_date', $from_date); 
            $this->set('to_date', $to_date);
            $this->set('back', $back);
            $this->set('date', $from_date);
        
            if ($this->params['form']['ajax']) {
                    $this->autoRender = false;
                    $from_date = (!empty($this->params['form']['from_date'])) ? date('Y-m-d', strtotime($this->params['form']['from_date'])) : date('Y-m-d',strtotime($from_date));
                    $to_date = (!empty($this->params['form']['to_date'])) ? date('Y-m-d', strtotime($this->params['form']['to_date'])) : date('Y-m-d',strtotime($to_date));
                    $type = $this->params['form']['type'];

                    if($type == 2){
                            $group_by = ", user_id";
                            $select_user = " user_id,";
                    }
                    

                    $report = $this->Slaves->query("
                        SELECT
                            date, 
                            $select_user
                            ROUND(SUM(`opening`)) opening,
                            ROUND(SUM(`closing`)) closing,
                            ROUND(SUM(`topup`)) topup,
                            ROUND(SUM(`topup_reverse`)) topup_reverse,
                            ROUND(SUM(`sale_dr`)) sale_dr,
                            ROUND(SUM(`sale_cr`)) sale_cr,
                            ROUND(SUM(`sale_reverse_cr`)) sale_reverse_cr,
                            ROUND(SUM(`sale_reverse_dr`)) sale_reverse_dr,
                            ROUND(SUM(`commission`)) commission,
                            ROUND(SUM(`commission_reverse`)) commission_reverse,
                            ROUND(SUM(`tds`))tds,
                            ROUND(SUM(`setup_fee`)) setup_fee,
                            ROUND(SUM(`service_charge`)) service_charge,
                            ROUND(SUM(`kit_charge`)) kit_charge,
                            ROUND(SUM(`security_deposit`)) security_deposit,
                            ROUND(SUM(`one_time_charge`)) one_time_charge,
                            ROUND(SUM(`rental`)) rental,
                            ROUND(SUM(`incentive`)) incentive
                        FROM
                            float_report
                        WHERE
                            date BETWEEN '".$from_date."' AND '".$to_date."' 
                        GROUP BY 
                            date $group_by");
            if($type == 2){
                $user_ids = array_map(function($element) {
                    return $element['float_report']['user_id'];
                }, $report);
                
                $temp = $this->Shop->getUserLabelData($user_ids);
                foreach ($report as $reports) {
                    $user_id = $reports['float_report']['user_id'];
                    $report[0]['float_report']['shop_est_name'] = $temp[$user_id]['imp']['shop_est_name'];
//                print_r($report);die;
                }
            }
            echo json_encode($report);
        }
    }
    
    function userTxnFloatReport($user_id, $date = null){

        if ($this->RequestHandler->isPost()){
                $params = $this->params['form'];
                $date = date('Y-m-d', strtotime($params['date']));
        }


        $group_array = array();
        $group_array[0] = $user_id;

        $get_groups = $this->Slaves->query("SELECT group_id FROM user_groups WHERE user_id = $user_id");
        foreach($get_groups as $groups){
            $group_id = $groups['user_groups']['group_id'];

            if($group_id == DISTRIBUTOR){
                $table = "distributors";
            }
            else if($group_id == RETAILER){
                $table = "retailers";
            }
            else if($group_id == SUPER_DISTRIBUTOR){
                $table = "super_distributors";
            }
            else if($group_id == MASTER_DISTRIBUTOR){
                $table = "master_distributors";
            }

            if($table != ''){
                $get_role_id = $this->Slaves->query("SELECT id FROM $table WHERE user_id = $user_id");
                $group_array[$group_id] = $get_role_id[0][$table]['id'];
            }
        }

        $current_date = $date;

        $query = "(SELECT id, 'Topup Received' as particulars, 0 as debit, amount as credit, target_opening as opening,target_closing as closing, timestamp  FROM `shop_transactions` WHERE type = '".MDIST_DIST_BALANCE_TRANSFER."' AND target_id != '1' AND date = '$current_date' AND confirm_flag != '1' AND target_id = '".$group_array[DISTRIBUTOR]."')
        UNION
        (SELECT id, 'Topup Received' as particulars, 0 as debit, amount as credit, target_opening as opening,target_closing as closing, timestamp FROM `shop_transactions` WHERE type = '".DIST_RETL_BALANCE_TRANSFER."' AND source_id = '1' AND date = '$current_date' AND confirm_flag != '1' AND target_id = '".$group_array[RETAILER]."')
        UNION
        (SELECT id, 'Topup Received' as particulars, 0 as debit, amount as credit, target_opening as opening,target_closing as closing, timestamp FROM `shop_transactions` WHERE type = '".MDIST_SDIST_BALANCE_TRANSFER."' AND target_id != '3' AND date = '$current_date' AND confirm_flag != '1' AND target_id = '".$group_array[SUPER_DISTRIBUTOR]."') 
        UNION
        (SELECT shop_transactions.id, 'Topup Reversed' as particulars, shop_transactions.amount as debit, 0 as credit, shop_transactions.source_opening as opening,shop_transactions.source_closing as closing, shop_transactions.timestamp FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type = '".PULLBACK_DISTRIBUTOR."' AND st.type = '1' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' AND st.source_id != '1' AND shop_transactions.source_id = '".$group_array[DISTRIBUTOR]."')
        UNION
        (SELECT  shop_transactions.id, 'Topup Reversed' as particulars, shop_transactions.amount as debit, 0 as credit, shop_transactions.source_opening as opening,shop_transactions.source_closing as closing, shop_transactions.timestamp FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type = '".PULLBACK_RETAILER."' AND st.type = '2' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' AND st.source_id = '1' AND shop_transactions.source_id = '".$group_array[RETAILER]."')
        UNION
        (SELECT  shop_transactions.id, 'Topup Reversed' as particulars, shop_transactions.amount as debit, 0 as credit, shop_transactions.source_opening as opening,shop_transactions.source_closing as closing, shop_transactions.timestamp FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type = '".PULLBACK_SUPERDISTRIBUTOR."' AND st.type = '".MDIST_SDIST_BALANCE_TRANSFER."' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' AND shop_transactions.source_id = '".$group_array[SUPER_DISTRIBUTOR]."') 
        UNION
        (SELECT id, 'Sale Credit' as particulars, 0 as debit, amount as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type = '".CREDIT_NOTE."' AND confirm_flag != '1' AND source_id = '".$group_array[0]."')
        UNION
        (SELECT id, 'Sale Debit' as particulars, amount as debit, 0 as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type = '".MASTER_DISTRIBUTOR."' AND confirm_flag = '1' AND source_id = '".$group_array[RETAILER]."')
        UNION
        ( SELECT id, 'Sale Debit' as particulars, amount as debit, 0 as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type = '".DEBIT_NOTE."' AND confirm_flag != '1' AND source_id = '".$group_array[0]."')
        UNION
        (SELECT shop_transactions.id, 'Sale Reverse Credit' as particulars, shop_transactions.amount as debit, 0 as credit, shop_transactions.source_opening as opening,shop_transactions.source_closing as closing, shop_transactions.timestamp FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type IN ('".VOID_TXN."','".TXN_CANCEL_REFUND."') AND st.type = '".CREDIT_NOTE."' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' AND shop_transactions.source_id = '".$group_array[0]."')
        UNION
        (SELECT shop_transactions.id, 'Sale Reverse Debit' as particulars, 0 as debit, shop_transactions.amount as credit, shop_transactions.source_opening as opening,shop_transactions.source_closing as closing, shop_transactions.timestamp FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type = '".REVERSAL_RETAILER."' AND st.type = '".MASTER_DISTRIBUTOR."' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' AND shop_transactions.source_id = '".$group_array[RETAILER]."')
        UNION
        ( SELECT shop_transactions.id, 'Sale Reverse Debit' as particulars, 0 as debit, shop_transactions.amount as credit, shop_transactions.source_opening as opening,shop_transactions.source_closing as closing, shop_transactions.timestamp FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.type IN ('".VOID_TXN."','".TXN_CANCEL_REFUND."') AND st.type = '".DEBIT_NOTE."' AND shop_transactions.date = '$current_date' AND st.date != '$current_date' AND shop_transactions.source_id = '".$group_array[0]."')
        UNION
        (SELECT id, 'Commision from Master Distributor' as particulars, 0 as debit, shop_transactions.amount as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION_MASTERDISTRIBUTOR."' AND confirm_flag != '1' AND source_id = '".$group_array[MASTER_DISTRIBUTOR]."')
        UNION
        (SELECT id, 'Commision from Distributor' as particulars, 0 as debit, shop_transactions.amount as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION_DISTRIBUTOR."' AND confirm_flag != '1' AND source_id = '".$group_array[DISTRIBUTOR]."')
        UNION
        (SELECT id, 'Commision from Retailer' as particulars, 0 as debit, shop_transactions.amount as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION_RETAILER."' AND confirm_flag != '1' AND source_id = '".$group_array[RETAILER]."')
        UNION
        (SELECT id, 'Commision' as particulars, 0 as debit, shop_transactions.amount as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION."' AND confirm_flag != '1' AND source_id = '".$group_array[0]."')
        UNION
        (SELECT id, 'Commision from Super Distributor' as particulars, 0 as debit, shop_transactions.amount as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION_SUPERDISTRIBUTOR."' AND confirm_flag != '1' AND source_id =  '".$group_array[SUPER_DISTRIBUTOR]."')
        UNION
        (SELECT id, 'Commision reverse' as particulars, shop_transactions.amount as debit, 0 as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type = '".COMMISSION_DISTRIBUTOR_REVERSE."' AND confirm_flag != '1' AND source_id = '".$group_array[0]."')
        UNION
        (SELECT id, 'TDS' as particulars, shop_transactions.amount as debit, 0 as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type = '".TDS."' AND confirm_flag != '1' AND source_id = '".$group_array[0]."')
        UNION
        (SELECT id, (CASE WHEN type = '".SETUP_FEE."' THEN 'Setup Fee' WHEN type = '".SERVICE_CHARGE."' THEN 'Service charge' WHEN type = '".KITCHARGE."' THEN 'Kit charge' WHEN type = '".SECURITY_DEPOSIT."' THEN 'Security Deopsit' WHEN type = '".ONE_TIME_CHARGE."' THEN 'One Time Charge' WHEN type = '".RENTAL."' THEN 'Rental' WHEN type = '".REFUND."' THEN 'Incentive' ELSE '-' END) as particulars, IF(type != '".REFUND."',shop_transactions.amount,0) as debit, IF(type != '".REFUND."',0,shop_transactions.amount) as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE date = '$current_date' AND type IN ('".SETUP_FEE."','".SERVICE_CHARGE."','".KITCHARGE."','".SECURITY_DEPOSIT."','".ONE_TIME_CHARGE."','".RENTAL."','".REFUND."') AND source_id = '".$group_array[0]."' GROUP BY type)
        UNION
        (SELECT id, (CASE WHEN type_flag = '".SETUP_FEE."' THEN 'Setup Fee' WHEN type_flag = '".SERVICE_CHARGE."' THEN 'Service charge reverse' WHEN type_flag = '".KITCHARGE."' THEN 'Kit charge reverse' WHEN type_flag = '".SECURITY_DEPOSIT."' THEN 'Security deposit reverse' WHEN type_flag = '".ONE_TIME_CHARGE."' THEN 'One time charge reverse' WHEN type_flag = '".RENTAL."' THEN 'Rental' WHEN type_flag = '".REFUND."' THEN 'Incentive' ELSE '-' END) as particulars, IF(type_flag != '".REFUND."',shop_transactions.amount,0) as debit, IF(type_flag != '".REFUND."',0,shop_transactions.amount) as credit, source_opening as opening,source_closing as closing, timestamp FROM `shop_transactions` WHERE type = '".TXN_REVERSE."' AND date = '$current_date' AND source_id = '".$group_array[0]."' GROUP BY type_flag)";


        $user_txn_query = $this->Slaves->query("SELECT id, particulars, ROUND(debit) as debit, ROUND(credit) as credit, ROUND(opening) as opening, ROUND(closing) as closing, `timestamp` FROM ($query) as st WHERE id != 0 ORDER BY id desc");

        $this->set('user_txn',$user_txn_query);

        $this->set('date',$date);
        $this->set('user_id',$user_id);
        
    }
}
