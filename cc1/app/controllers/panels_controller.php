<?php
class PanelsController extends AppController{
    var $name = 'Panels';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator', 'GChart','Csv');
    var $components = array('RequestHandler', 'Recharge','Api','Smartpaycomp','Comments','Serviceintegration','Servicemanagement','Documentmanagement','UserProfile','Bridge','Platform');

    var $uses = array('Retailer', 'Distributor', 'MasterDistributor', 'User', 'ModemRequestLog', 'Slaves','Dmt','Ekonew','Smartpay','Microfinance');

    var $turnaround_time = array(0.5, 1, 2, 12, 24, 48, 100);

    var $api_medium_map = array("SMS", "API", "USSD", "Android", "", "Java", "", "Windows 7", "Windows 8", "Web");

    function beforeFilter(){
        set_time_limit(0);
        ini_set("memory_limit", "512M");
        //ini_set("display_errors", "off");
        // error_reporting(0);

        parent::beforeFilter();
        $this->layout = 'module';

        $reversalDD = array('None', 'PPI Reversal', 'OSS Reversal', 'Modem Reversal', 'System Reversal', 'Manual Reversal', 'Manual Check', 'Drop SMS', 'Manual Transaction', 'Paytronics Reversal');
        $this->set('reversalDD', $reversalDD);

        $taggings = $this->General->getTaggings();
        $this->set('taggings', $taggings);

        $this->set('turnaround_time', $this->turnaround_time);

        $call_types = $this->General->getCallTypes();
        $this->set('call_types', $call_types);

        $moduleListing = $this->Slaves->query("SELECT modules.module_name,module_group_mapping.module_id,module_full_name,module_group_mapping.group_id from modules Inner join module_group_mapping ON (modules.id=module_group_mapping.module_id) WHERE group_id = " . $this->Session->read('Auth.User.group_id') . "  order by modules.id ASC");

        Configure::load('acl');

        $mappedModule = Configure::read('acl.modules');

        $bypassmodule = Configure::read('acl.bypass');

        foreach($moduleListing as $moduleval):

            if(array_key_exists($moduleval['modules']['module_name'], $mappedModule)):

                if( ! empty($moduleval['modules']['module_full_name'])):

                    $this->modulearray[$moduleval['modules']['module_full_name']] = array("action"=>$mappedModule[$moduleval['modules']['module_name']]['url'][0]);

				endif;

				endif;


        endforeach
        ;

        $this->set('modulelist', $this->modulearray);
    }

    /*
     * function findUser($userId=null)
     * {
     * if($userId==null)
     * {
     * $userId=$_REQUEST['userId'];
     * } //echo $userId;
     * $usersResult=$this->User->query("select mobile from users where id=$userId ");
     * $num=$usersResult['0']['users']['mobile'];
     * //echo $num;
     * $this->userInfo($num);
     * //$this->render('/panels/userInfo');
     * //$this->redirect(array('panels' => 'userInfo'));
     * }
     */
    function createTag(){
        $tagName = $_REQUEST['tagName'];
        $tagType = $_REQUEST['tagType'];
        $tag_exists = $this->Slaves->query("select name from taggings where name like '$tagName' and type like '$tagType'");

        if(empty($tag_exists)){
            $insertion = $this->User->query("insert into taggings (name, type) values ('$tagName', '$tagType')");
            $this->Shop->delMemcache("taggings");
            if($insertion) echo "success";
        }

        $this->autoRender = false;
    }

    function removeTag(){
        $tagId = $_REQUEST['tagId'];
        $tag_exists = $this->Slaves->query("select name from taggings where id = " . $tagId);

        if( ! empty($tag_exists)){
            $this->User->query("update taggings set is_active = 0 where id = " . $tagId);
            $this->Shop->delMemcache("taggings");
            echo "success";
        }

        $this->autoRender = false;
    }

    function manualRequest(){
        $loggedInUserId = $_SESSION['Auth']['User']['id'];
        $retailerMobile = $_REQUEST['retMobile'];
        $amount = $_REQUEST['Amount'];
        $operator = $_REQUEST['Operator'];
        $subscriberId = $_REQUEST['subId'];
        $userMobile = $_REQUEST['UserMobile'];
        $date = Date("Y-m-d");
        $power = 0;

        if($this->Session->read('Auth.User.group_id') != MASTER_DISTRIBUTOR){
            $data = $this->Slaves->query("SELECT * FROM repeated_transactions WHERE type = 3 AND msg like '%$userMobile%' AND added_by = $loggedInUserId");
            if( ! empty($data)){
                echo "Your repeated request cannot be submitted. Contact admin";
                $this->autoRender = false;
                exit();
            }
        }

        /** IMP DATA ADDED : START**/
        $temp = $this->Shop->getUserLabelData($retailerMobile,2,1);
        $imp_data = $temp[$retailerMobile];
        /** IMP DATA ADDED : END**/

        // add retailers shop name in email
        $retailerShopNameResult = $this->Slaves->query("select shopname from retailers where mobile='$retailerMobile'");
        // $retailerShopName = $retailerShopNameResult['0']['retailers']['shopname'];
        $retailerShopName = $imp_data['imp']['shop_est_name'];

        $loggedInUserName = $this->Slaves->query("select name,mobile from users where id=$loggedInUserId");

        $operatorIdResult = $this->Slaves->query("select id from products where name ='$operator' ");
        $operatorId = $operatorIdResult['0']['products']['id'];

        // $manualTransTagIdResult=$this->User->query("select id,name from taggings where name ='Manual Transaction'");
        // $manualTransTagId=$manualTransTagIdResult['0']['taggings']['id'];
        // $manualTransTagName=$manualTransTagIdResult['0']['taggings']['name'];

        $prodInfo = $this->Shop->smsProdCodes($operatorId);

        if($prodInfo['method'] == "vasRecharge"){
            if($prodInfo['type'] == "fix") $msg = '*' . $operatorId . '*' . $userMobile;
            else $msg = '*' . $operatorId . '*' . $userMobile . '*' . $amount;
        }
        else{
            if(empty($subscriberId)) $msg = '*' . $operatorId . '*' . $userMobile . '*' . $amount;
            else $msg = '*' . $operatorId . '*' . $subscriberId . '*' . $userMobile . '*' . $amount;
        }

        $body = "Manual Prepaid Request made by " . $loggedInUserName['0']['users']['name'] . " (" . $loggedInUserName['0']['users']['mobile'] . ")";
        $body .= "<br><b>Transaction Details</b>";
        $body .= "<br><br>Customer Mobile Number: " . $userMobile;
        $body .= "<br>Retailer Mobile Number: " . $retailerMobile;
        $body .= "<br>Retailer Shop Name: " . $retailerShopName;
        $body .= "<br>Operator: " . $operator;
        $body .= "<br>Amount: Rs." . $amount;
        // echo "Email :".$body;
        // echo "</br>";
        $subject = 'Pay1-Manual Recharge Request from Panel';
        // echo $subject;
        // echo "message is: ".$msg;
        $this->User->query("insert into repeated_transactions(sender,msg,send_flag,type,added_by,processed_by,timestamp) values('$retailerMobile','$msg',1,3,$loggedInUserId,$loggedInUserId,'$date')");
        echo "Your request is successfully submitted.";

        $this->General->sendMails($subject, $body, array('chirutha@pay1.in'));

        // for tagging 'Manual Transaction'
        $usersChkResult = $this->Slaves->query("select id,mobile from users where mobile='$userMobile'");
        $userIdChk = $usersChkResult['0']['users']['id'];
        // $usersMobileChk=$usersChkResult['0']['users']['mobile'];
        // $this->User->query("insert into user_taggings (tagging_id,user_id,timestamp) values($manualTransTagId,$userIdChk,'$date') ");

        $this->autoRender = false;
    }

    function updateCommentsForReversalNew(){
        $current_date = date('Ymd');
        $this->General->logData('/mnt/logs/comments_' . $current_date . '.txt', "inside updateCommentsForReversalNew::" . json_encode($_REQUEST));
        $loggedInUser = $_SESSION['Auth']['User']['mobile'];
        $tId = $_REQUEST['tId'];
        $reason = $_REQUEST['reason'];
        $userMobile = $_REQUEST['userMobile'];
        $flag = $_REQUEST['flag'];
        $retMobile = $_REQUEST['retMobile'];
        $retId = $_REQUEST['retId'];
        $callTypeId = $_REQUEST['callTypeId'];
        $tagId = $_REQUEST['tagId'];

        $callTypeId == 'none' && $callTypeId = null;
        $tagId == 'none' && $tagId = null;

        if($flag == 3){
            $message = "Complaint for transactin id " . $tId . "has been declined . ";

            $this->General->sendMessage($retMobile, $message, 'notify');
        }

        $userIdResult = $this->User->query("select id from users where mobile='$userMobile' ");
        $userId = empty($userIdResult) ? 0 : $userIdResult['0']['users']['id'];
        $this->General->logData('/mnt/logs/comments_' . $current_date . '.txt', "inside updateCommentsForReversalNew ADD COMMENT::" . json_encode(array($userId, $retId, $tId, $reason, $loggedInUser, null, $tagId, $callTypeId)));
        $this->Shop->addComment($userId, $retId, $tId, $reason, $loggedInUser, null, $tagId, $callTypeId);
        $comment = $this->User->query("SELECT c.comments,c.ref_code,u.name,u.mobile,c.created
					from comments c join users u on(c.mobile=u.mobile)
					where c.ref_code = '$tId'  order by c.created desc limit 1");
        echo "<tr bgcolor='#CEF6F5' style='border: 2px solid white'>";
        echo "<td><span style='font-size:12px;'>By " . $comment[0]['u']['name'] . " @ " . $comment[0]['c']['created'] . " on " . $comment[0]['c']['ref_code'] . "</span></br>" . $comment[0]['c']['comments'] . "</td>";
        echo "</tr>";
        $this->General->logData('/mnt/logs/comments_' . $current_date . '.txt', "end updateCommentsForReversalNew" . json_encode($comment));
        $this->autoRender = false;
    }

    /*
     * function updateCommentsForReversal()
     * {
     * $loggedInUser= $_SESSION['Auth']['User']['mobile'];
     * $tId=$_REQUEST['tId'];
     * $reason=$_REQUEST['reason'];
     * $userMobile=$_REQUEST['userMobile'];
     * $flag=$_REQUEST['flag'];
     * $retMobile=$_REQUEST['retMobile'];
     * $retId=$_REQUEST['retId'];
     * echo "Retailer Mobile is ".$retMobile;
     * //$retIdResult=$this->User->query("select id from retailers where mobile='$retMobile' ");
     * //$retId=$retIdResult['0']['retailers']['id'];
     *
     * if($flag==3) // for tagging of transaction function, flag=3.
     * {
     * $message="Complaint for transactin id ".$tId."has been declined . ";
     * echo "user mobile is ".$userMobile;
     * echo "Message is ".$message;
     *
     * $this->General->sendMessage($retMobile,$message,'notify');
     *
     * }
     *
     * echo "retailer id is ".$retId;
     * // echo "Transactin Id is ".$tId;
     * $userIdResult=$this->User->query("select id from users where mobile='$userMobile' ");
     * $userId=$userIdResult['0']['users']['id'];
     *
     * $this->Shop->addComment($userId,$retId,$tId,$reason,$loggedInUser);
     * //echo "userid for mobile ".$usermobile." is ".$userId;
     *
     * //comments entered by
     * $commentsEnteredByResult=$this->User->query("select mobile,name from users where id=$loggedInUser");
     * $commentsEnteredBy=$commentsEnteredByResult['0']['users']['mobile'];
     * $commentsEnteredByName=$commentsEnteredByResult['0']['users']['name'];
     * //echo "Comments entered by : ".$commentsEnteredBy;
     * $newDate=date("Y-m-d H:i:s");
     * $this->User->query("insert into comments(users_id,retailers_id,mobile,ref_code,flag,comments,created) values ($userId,$retId,$commentsEnteredBy,$tId,$flag,'".addslashes($reason)."','$newDate') ");
     *
     * if(empty($commentsEnteredByName))
     * $var=$commentsEnteredBy;
     * else
     * $var=$commentsEnteredByNamel;
     * echo $var."&nbsp;&nbsp&nbsp;&nbsp;".$newDate."&nbsp;</br>".$reason;
     * //echo "Inserted in comments table";
     * $this->autoRender=false;
     * }
     */
    function addNewNumber($flag = null){
        // if retInfo number change then flag=1
        $oldNum = $_REQUEST['oldNumber'];
        $newNum = $_REQUEST['newNumber'];
        $otp = isset($_REQUEST['otp']) ? $_REQUEST['otp'] : "";

        $dataSource = $this->User->getDataSource();

        try{
            $dataSource->begin();
            $oldIDForLogsResult = $dataSource->query("select users.id,retailers.maint_salesman,salesmen.mobile,retailers.shopname,retailers.parent_id from users,retailers left join salesmen ON (salesmen.id = retailers.maint_salesman) where users.mobile='$oldNum' AND users.id = retailers.user_id");
            $oldIDForLogs = $oldIDForLogsResult['0']['users']['id'];

            /**
             * IMP DATA ADDED : START*
             */
            $temp = $this->Shop->getUserLabelData($oldNum, 2, 1);
            $imp_data = $temp[$oldNum];
            /**
             * IMP DATA ADDED : END*
             */

            $oldIDForLogsResult['0']['retailers']['shopname'] = $imp_data['imp']['shop_est_name'];
            $otpSystem = $this->Shop->getMemcache("otp_changeMob_$oldNum");
            $this->General->logData("number_change.txt", "$oldNum::$newNum::$otp::$oldIDForLogs::$otpSystem");

            if( ! empty($oldIDForLogs)){
                $result = $dataSource->query("Select id,mobile from users where mobile='$newNum'");
                // for retailers table start
                // $newNumRetailerCheck=$this->User->query("Select mobile from retailers where mobile='$newNum'");
                // retailers table end
                $groups_data = $dataSource->query("SELECT group_id FROM user_groups where user_id = $oldIDForLogs AND group_id = ". DISTRIBUTOR);
                if(!empty($groups_data)){
                    $msg = "You are already a distributor, we cannot change your number from here";
                    $successs = 0;
                }
                else if(count($result) > 0){ // checking if new number already present in system.
                    $this->General->logData("number_change.txt", "$oldNum::$newNum::New number already exists");

                    $msg = "This user cannot be shifted to a retailer number.";
                    $successs = 0;
                }
                else if(count($result) > 0 && $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR && $otp == ''){
                    $this->General->logData("number_change.txt", "$oldNum::$newNum::Distributor with no otp");

                    $newId = $result['0']['users']['id'];
                    $newM = substr("temp".$newId,0,10);
                    $dataSource->query("update users set mobile='{$newM}' where id=$newId"); // relacing new num by 'temp_str' in users table.
                                                                                              // if retailer number to b changed start
                    $this->General->makeOptIn247SMS($newNum);

                    $oldId = $oldIDForLogs;
                    $dataSource->query("update users set mobile='" . $newNum . "',ussd_flag=0 where id=$oldId");
                    $dataSource->query("update retailers set mobile='" . $newNum . "', modified = '" . date('Y-m-d H:i:s') . "' where user_id=$oldId");
                    $dataSource->query("update users set mobile='" . $oldNum . "',ussd_flag=0 where id=$newId");
                    // ret end
                    $successs = 1;
                }
                else if( ! count($result) && $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
                    $this->General->logData("number_change.txt", "$oldNum::$newNum::Distributor: new number does not exists");

                    $this->General->makeOptIn247SMS($newNum);
                    $result = $dataSource->query("UPDATE users SET mobile='$newNum',ussd_flag=0 WHERE mobile='$oldNum'");
                    $result1 = $dataSource->query("UPDATE retailers SET mobile='$newNum', modified = '" . date('Y-m-d H:i:s') . "' WHERE mobile='$oldNum'");
                    $successs = 1;
                }
                else if(count($result) > 0 &&  ! empty($otpSystem) && ($otp == $otpSystem || !$this->General->isOTPRequired($oldNum))){ // old number and new number get swapped.
                    $this->General->logData("number_change.txt", "$oldNum::$newNum::Distributor: otp matched & new number exists as well");
                    // echo "New Number already present";
                    $newId = $result['0']['users']['id'];
                    $newM = substr("temp".$newId,0,10);
                    $dataSource->query("update users set mobile='{$newM}' where id=$newId"); // relacing new num by 'temp_str' in users table.
                                                                                              // if retailer number to b changed start
                    $this->General->makeOptIn247SMS($newNum);

                    $oldId = $oldIDForLogs;
                    $dataSource->query("update users set mobile='" . $newNum . "',ussd_flag=0 where id=$oldId");
                    $dataSource->query("update retailers set mobile='" . $newNum . "', modified = '" . date('Y-m-d H:i:s') . "' where user_id=$oldId");
                    $dataSource->query("update users set mobile='" . $oldNum . "',ussd_flag=0 where id=$newId");
                    // ret end
                    $successs = 1;
                }
                else if( ! count($result) && ($otp == $otpSystem || !$this->General->isOTPRequired($oldNum))){
                    $this->General->logData("number_change.txt", "$oldNum::$newNum::Distributor: otp matched");

                    // echo "New Number not present";
                    $result = $dataSource->query("UPDATE users SET mobile='$newNum',ussd_flag=0 WHERE mobile='$oldNum'");
                    $result1 = $dataSource->query("UPDATE retailers SET mobile='$newNum', modified = '" . date('Y-m-d H:i:s') . "' WHERE mobile='$oldNum'");
                    $successs = 1;
                }
                else{
                    $this->General->logData("number_change.txt", "$oldNum::$newNum::Distributor: otp does not match");

                    $msg = "OTP does not match. Please try again";
                    $successs = 0;
                }

                $msg1 = "Dear Retailer,\nYour number has been shifted from $oldNum to your new number " . $newNum;
                $msg2 = "Dear Distributor,\nDemo number of retailer (" . $oldIDForLogsResult['0']['retailers']['shopname'] . ") changed from $oldNum to new number " . $newNum;

                $dist_mob = $dataSource->query("SELECT mobile FROM distributors  WHERE distributors.id=" . $oldIDForLogsResult['0']['retailers']['parent_id']);

                if($successs == 1){
                    echo $successs . "^^^" . $msg1;
                    // if($_SESSION['Auth']['User']['group_id'] != DISTRIBUTOR){
                    $this->General->sendMessage($oldNum, $msg1, 'shops');
                    // }
                    if( ! empty($dist_mob['0']['distributors']['mobile'])) $this->General->sendMessage($dist_mob['0']['distributors']['mobile'], $msg2, 'shops');
                }
                else{
                    echo $successs . "^^^" . $msg;
                }
            }
            else{
                echo "0^^^Retailer does not exists";
            }

            $dataSource->commit();
        }
        catch(Exception $e){
            $dataSource->rollback();
            echo "0^^^Retailer number cannot be changed";
        }
        $this->autoRender = false;
    }

    function manualSuccess(){
        $this->autoRender = false;
        $tranId = $_REQUEST['id'];
        $txn_id = $this->User->query("SELECT vendors_activations.vendor_id, vendors_activations.vendor_refid, products.service_id FROM vendors_activations join  products on (vendors_activations.product_id = products.id) WHERE vendors_activations.txn_id= '" . $tranId . "' AND vendors_activations.status not in (2,3)");

        if( ! empty($txn_id)){
            if( ! $this->Recharge->lockTransaction($tranId)){
                echo "Error";
                return;
            }

            $txn_id['0']['vendors_activations']['vendor_id'] = (empty($txn_id['0']['vendors_activations']['vendor_id'])) ? 29 : $txn_id['0']['vendors_activations']['vendor_id'];

            $this->Recharge->update_in_vendors_activations(array('vendor_id'=>$txn_id['0']['vendors_activations']['vendor_id'],'status'=>TRANS_SUCCESS, 'cc_userid'=>(empty($_SESSION['Auth']['User']['id']) ? 0 : $_SESSION['Auth']['User']['id'])), array('txn_id'=>$tranId), $this->User);
            $this->Recharge->log_in_vendor_message(array('va_tran_id'=>$tranId, 'vendor_refid'=>$txn_id['0']['vendors_activations']['vendor_refid'], 'service_id'=>$txn_id['0']['products']['service_id'], 'service_vendor_id'=>$txn_id['0']['vendors_activations']['vendor_id'],
                    'internal_error_code'=>'13', 'response'=>"Manual Success by " . $_SESSION['Auth']['User']['name'], 'status'=>'success', 'timestamp'=>date("Y-m-d H:i:s"), 'vm_date'=>date('Y-m-d')), $this->User);
            $this->Recharge->unlockTransaction($tranId);

            echo "Done";
        }
        else{
            echo "Not found";
        }
    }

    function processDishTvTransaction(){
        $this->autoRender = false;
        $tranId = $_REQUEST['id'];

        $TPS_REQUEST_HASH = "TPS_DISHTV_DATA";
        try{
            $redisObj = $this->Shop->redis_connector();
            if($redisObj == false){
                throw new Exception("cannot create redis object");
            }
            else{
                $req = $redisObj->hget($TPS_REQUEST_HASH,$tranId);
                $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": processDishTvTransaction : " . $tranId);

                $req= json_decode($req,true);
                $data = $this->Retailer->query("SELECT * FROM vendors_activations where txn_id = '$tranId' AND status in (0,4) AND vendor_id = 0");
                if(!empty($data)){
                    $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": processDishTvTransaction : locking txn here" . $tranId);

                    if(!$this->Recharge->lockTransaction($tranId)) continue;
                    $redisObj->hdel($TPS_REQUEST_HASH, $tranId);

                    $this->Recharge->unlockTransaction($tranId);
                    $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": processDishTvTransaction : finally processing::" . $tranId);

                    $this->Recharge->send_request_via_tps($tranId, $req['prod_id'], $req['service_id'], $req['params'], $req['data']);
                }

            }
        }
        catch(Exception $ex){
            $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": transaction not processed data : " . $request_id);
        }
    }

    function reverseTransaction($ref_id, $session = null){
        $this->autoRender = false;
        $grpId = $_SESSION['Auth']['User']['group_id'];
        $usrId = $_SESSION['Auth']['User']['id'];
        $usrName = $_SESSION['Auth']['User']['name'];


        // if($grpId ==ADMIN || $grpId ==CUSTCARE || $usrId == 1){

        // /echo "group";

        $ref_code = $this->Slaves->query("SELECT vendors_activations.*,vendors.update_flag,vendors.shortForm, products.service_id,products.id " . "FROM vendors_activations join  products on (vendors_activations.product_id = products.id) " . "LEFT JOIN vendors ON (vendors_activations.vendor_id = vendors.id) " . "WHERE vendors_activations.txn_id= '" . $ref_id . "'");

        if( ! empty($ref_code)){
            $vendor = $ref_code[0]['vendors_activations']['vendor_id'];

            if($ref_code['0']['products']['id'] == WALLET_ID){
                $out = $this->General->b2c_pullback($ref_code['0']['vendors_activations']['vendor_refid'], $ref_id);
                if($out['status'] == 'failure'){
                    $this->Recharge->log_in_vendor_message(array('va_tran_id'=>$ref_id, 'vendor_refid'=>$ref_code['0']['vendors_activations']['vendor_refid'], 'service_id'=>$ref_code['0']['products']['service_id'], 'service_vendor_id'=>$ref_code['0']['vendors_activations']['vendor_id'],
                            'internal_error_code'=>13, 'response'=>addslashes("Manual reversal by $usrName, Amount is less in user account, so cannot be pulled back"), 'status'=>'success', 'timestamp'=>date('Y-m-d H:i:s'), 'vm_date'=>date('Y-m-d')));

                    echo "Amount is less in user account, so cannot be pulled back";
                    return;
                }
            }
            else{

                $result = $ref_code;
                $statusList = array('0'=>'pending', '1'=>'success', '2'=>'failure', '3'=>'failure', '4'=>'success', '5'=>'success'); // ---status list
                $transId = $result['0']['vendors_activations']['txn_id'];
                $transvendor = $result['0']['vendors_activations']['vendor_id'];
                $transvendorname = trim($result['0']['vendors']['shortForm']);
                $transdate = $result['0']['vendors_activations']['date'];
                $transvrefId = $result['0']['vendors_activations']['vendor_refid'];
                $transoprId = $result['0']['vendors_activations']['operator_id'];
                $trans_server_Status = $statusList[$result['0']['vendors_activations']['status']];
                $vendors_activations_id = $result['0']['vendors_activations']['id'];


                if($ref_code[0]['vendors']['update_flag'] == 0){ // contraints for API
                    $MAX_ALLOWED = 2000;
                    $tranResponse_status = isset($tranResponse['status']) ? strtolower(trim($tranResponse['status'])) : "";
                    $allowed_status = array('pending', 'success');
                    if($tranResponse_status != 'failure'){
                        // if(in_array($tranResponse_status, $allowed_status)){
                        $amountqry = "SELECT sum(amount) as total " . "FROM `vendors_activations` " . "INNER JOIN `trans_pullback` ON (`vendors_activations`.id = `trans_pullback`.vendors_activations_id) " . "LEFT JOIN vendors ON  (`vendors_activations`.vendor_id = vendors.id) " . "WHERE `trans_pullback`.date = '" . date('Y-m-d') . "' AND vendors.update_flag=0 AND `trans_pullback`.pullback_by = 0 " . "AND `trans_pullback`.reported_by = 'Non-system'";

                        $total_used_amount_arr = $this->User->query($amountqry);
                        $total_used_amount = empty($total_used_amount_arr[0][0]['total']) ? 0 : $total_used_amount_arr[0][0]['total'];
                    }
                }
                else if($ref_code[0]['vendors']['update_flag'] == 1){ // contraints for MODEM
                    $tranResponse_arr = json_decode($tranResponse, true);
                    $tranResponse['status'] = isset($tranResponse_arr['status']) ? strtolower($tranResponse_arr['status']) : "";
                    if( ! in_array($tranResponse['status'], array('failure', 'invalid')) && empty($transoprId)){
                        $sim_num = $result['0']['vendors_activations']['sim_num'];
                        $trans_oprId = $this->Shop->getOtherProds($result['0']['vendors_activations']['product_id']);
                        $sim_qry = "SELECT * FROM `devices_data` WHERE mobile='" . substr($sim_num,  - 10) . "' AND vendor_id='$vendor' AND opr_id in ($trans_oprId) AND sync_date='$transdate'";
                        $sim_result = $this->User->query($sim_qry);
                        if( ! empty($sim_result)){
                            $sim_result[0]['devices_data']['closing'] = ($transdate == date('Y-m-d')) ? $sim_result[0]['devices_data']['balance'] : $sim_result[0]['devices_data']['closing'];
                            $diff = $sim_result[0]['devices_data']['sale'] - $sim_result[0]['devices_data']['opening'] - $sim_result[0]['devices_data']['tfr'] + $sim_result[0]['devices_data']['closing'] - $sim_result[0]['devices_data']['inc'];

                            $this->General->logData('/mnt/logs/reverse.txt', "transId: $transId ::diff: $diff:: server_diff:" . $sim_result[0]['devices_data']['server_diff']);
                        }
                    }
                }
            }
            $this->Recharge->log_in_vendor_message(array('va_tran_id'=>$ref_id, 'vendor_refid'=>$ref_code['0']['vendors_activations']['vendor_refid'], 'service_id'=>$ref_code['0']['products']['service_id'], 'service_vendor_id'=>$ref_code['0']['vendors_activations']['vendor_id'],
                    'internal_error_code'=>14, 'response'=>addslashes('Manual reversal by ' . $usrName), 'status'=>'failure', 'timestamp'=>date("Y-m-d H:i:s"), 'vm_date'=>date('Y-m-d')));

            $this->Shop->reverseTransaction($ref_id, null, null, $grpId, $usrId, 1);
        }

}

function manualFailure(){
    $ref_code = $_REQUEST['id'];
    $va = $this->User->query("select va.retailer_id
				from vendors_activations va
				where va.txn_id = '" . $ref_code . "'");

    if( ! empty($va)){
        $mobile = $_SESSION['Auth']['User']['mobile'];
        $this->reverseTransaction($ref_code);
        $this->Shop->addComment($_SESSION['Auth']['User']['id'], $va[0]['va']['retailer_id'], $ref_code, null, $mobile, null, 29, 12);
        echo "DONE";
        die();
    }
    else{
        echo "NOT FOUND";
    }
    $this->autoRender = false;
}

function reversalDeclined($ref_id, $send){
    $this->Shop->reversalDeclined($ref_id, $send);
    $this->autoRender = false;
}

function search($from = null, $to = null, $rShop = null){
    if( ! isset($from)) $from = date('d-m-Y');
    if( ! isset($to)) $to = date('d-m-Y');

    // echo "from".$from;
    // echo "to".$to;

    $this->set('from', $from);
    $this->set('to', $to);

    if( ! empty($rShop)){

        $retailerShopResults = $this->Slaves->query("select id,name,mobile,shopname from retailers where shopname like '%$rShop%' ");
        $this->set('retailerShopResults', $retailerShopResults);
        $this->set('rShop', $rShop);
    }
}

function view(){
}

function retMsg(){
    // $ret=$this->Retailer->query("SELECT retailers.mobile FROM `retailers_logs`,retailers where retailers.id = retailer_id AND retailers_logs.date >= '".date('Y-m-d',strtotime('-4 days'))."' group by retailer_id having (sum(retailers_logs.sale) > 0)");
    $tmp = $this->Slaves->query("SELECT * from msg_templates order by name asc");
    $this->set('templates', $tmp);

    $root = 'payone';
    if(isset($_REQUEST['user'])){
        $retArr = array();
        if($_REQUEST['user'] == 'test'){
            $_REQUEST['testMobile'] = str_replace(' ', '', $_REQUEST['testMobile']);
            $retArr = explode(",", $_REQUEST['testMobile']);
            $root = 'notify';
        }
        else if($_REQUEST['user'] == 'multi'){
            foreach($_REQUEST['multiRet'] as $k=>$v){
                array_push($retArr, $v);
            }
        }
        else if($_REQUEST['user'] == 'rotation'){

            $retArr1 = $retArr2 = $retArr3 = $retArr4 = array();

//            $ret = $this->Slaves->query("SELECT retailers.mobile FROM `retailers_logs`,retailers where retailers.id = retailer_id AND retailers_logs.date >= '" . date('Y-m-d', strtotime('-4 days')) . "' group by retailer_id having (sum(retailers_logs.sale) > 0)");
            $ret = $this->Slaves->query("SELECT r.mobile "
                    . "FROM retailer_earning_logs rel "
                    . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                    . "WHERE rel.date >= '" . date('Y-m-d', strtotime('-4 days')) . "' "
                    . "AND rel.service_id IN (1,2,4,5,6,7) "
                    . "GROUP BY r.id "
                    . "HAVING (SUM(rel.amount) > 0)");
            $companyrm = $this->Slaves->query("SELECT rm.mobile from rm where master_dist_id = '3' AND active_flag='1'");
            // $distributors = $this->Slaves->query("SELECT users.mobile from users inner join distributors on (users.id = distributors.user_id) WHERE distributors.active_flag = '1'");
            // $saleman = $this->Slaves->query("SELECT salesmen.mobile from salesmen WHERE active_flag = '1'");

            foreach($companyrm as $rm){
                array_push($retArr1, $rm['rm']['mobile']);
            }
            // foreach ($distributors as $dist) {
            // array_push($retArr2, $dist['users']['mobile']);
            // }
            // foreach ($saleman as $sm) {
            // array_push($retArr3, $sm['salesmen']['mobile']);
            // }
            foreach($ret as $r){
                array_push($retArr4, $r['r']['mobile']);
            }

            $this->General->sendMessage($retArr1, $_REQUEST['message1'], 'shops');
            // $this->General->sendMessage($retArr2,$_REQUEST['message1'],'shops');
            // $this->General->sendMessage($retArr3,$_REQUEST['message1'],'shops');
            $this->General->sendMessage($retArr4, $_REQUEST['message1'], 'shops');

            $this->General->logData("/tmp/notifaction.txt", date("Y-m-d H:i:s") . " :: " . $_REQUEST['user'] . " ::message=>::" . $_REQUEST['message1'] . "mobno=>" . json_encode($retArr1) . json_encode($retArr4));

            $root = 'special';
        }
        else if($_REQUEST['user'] == 'app_rotation'){
//            $ret = $this->Slaves->query("SELECT retailers.mobile FROM `retailers_logs`,retailers where retailers.id = retailer_id AND retailers_logs.date >= '" . date('Y-m-d', strtotime('-4 days')) . "' group by retailer_id having (sum(retailers_logs.sale - retailers_logs.sms_sale - retailers_logs.ussd_sale) > 0)");
            $ret = $this->Slaves->query("SELECT *,SUM(ret_logs.sale) as tot_sale "
                    . "FROM (SELECT r.mobile,if(rel.api_flag = 0,SUM(amount),0) AS sms_sale,if(rel.api_flag = 2,SUM(amount),0) AS ussd_sale,SUM(rel.amount) AS sale "
                    . "FROM retailer_earning_logs rel "
                    . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                    . "WHERE rel.date >= '" . date('Y-m-d', strtotime('-4 days')) . "' "
                    . "AND rel.service_id IN (1,2,4,5,6,7) "
                    . "GROUP BY r.id,rel.api_flag) as ret_logs "
                    . "GROUP BY ret_logs.ret_user_id "
                    . "HAVING (SUM(ret_logs.sale) - sms_sale - ussd_sale) > 0");

            foreach($ret as $r){
                array_push($retArr, $r['ret_logs']['mobile']);
            }
            $root = 'notify';
        }
        else if($_REQUEST['user'] == 'sms_rotation'){
//            $ret = $this->Slaves->query("SELECT retailers.mobile FROM `retailers_logs`,retailers where retailers.id = retailer_id AND retailers_logs.date >= '" . date('Y-m-d', strtotime('-4 days')) . "' group by retailer_id having (sum(retailers_logs.sms_sale) > 0)");
            $ret = $this->Slaves->query("SELECT r.mobile "
                    . "FROM retailer_earning_logs rel "
                    . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                    . "WHERE rel.date >= '" . date('Y-m-d', strtotime('-4 days')) . "' "
                    . "AND rel.service_id IN (1,2,4,5,6,7) "
                    . "AND rel.api_flag = 0 "
                    . "GROUP BY r.id "
                    . "HAVING (SUM(rel.amount) > 0)");

            foreach($ret as $r){
                array_push($retArr, $r['r']['mobile']);
            }
        }
        else if($_REQUEST['user'] == 'ussd_rotation'){
//            $ret = $this->Slaves->query("SELECT retailers.mobile FROM `retailers_logs`,retailers where retailers.id = retailer_id AND retailers_logs.date >= '" . date('Y-m-d', strtotime('-4 days')) . "' group by retailer_id having (sum(retailers_logs.ussd_sale) > 0)");
            $ret = $this->Slaves->query("SELECT r.mobile "
                    . "FROM retailer_earning_logs rel "
                    . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                    . "WHERE rel.date >= '" . date('Y-m-d', strtotime('-4 days')) . "' "
                    . "AND rel.service_id IN (1,2,4,5,6,7) "
                    . "AND rel.api_flag = 2 "
                    . "GROUP BY r.id "
                    . "HAVING (SUM(rel.amount) > 0)");
            foreach($ret as $r){
                array_push($retArr, $r['r']['mobile']);
            }
        }
        else if($_REQUEST['user'] == 'distributors'){
            $distributors = $this->Slaves->query("SELECT distributors.mobile from users_logs ,distributors  WHERE users_logs.user_id = distributors.user_id  AND users_logs.date >= '" . date('Y-m-d', strtotime('-4 days')) . "' group by distributors.id having (sum(users_logs.topup_sold) > 0)");

            // $distributors = $this->User->query("select users.mobile from distributors , users WHERE distributors.user_id = users.id and toshow = 1");

            foreach($distributors as $distributor){
                array_push($retArr, $distributor['distributors']['mobile']);
            }
        }
        else if($_REQUEST['user'] == 'salesman'){
            // $ret=$this->Retailer->query("SELECT mobile from retailers where toshow = 1");
            $salesmen = $this->Slaves->query("select * from salesmen WHERE block_flag=0 AND active_flag=1");

            foreach($salesmen as $salesman){
                array_push($retArr, $salesman['salesmen']['mobile']);
            }
        }
        else if($_REQUEST['user'] == 'pay1_salesmen'){
            // $ret=$this->Retailer->query("SELECT mobile from retailers where toshow = 1");
            $salesmenStr = $_REQUEST['salesmen_no'];
            $retArr = explode(",", $salesmenStr);
        }

        $this->General->logData("/tmp/notifaction.txt", date("Y-m-d H:i:s") . " :: " . $_REQUEST['user'] . " ::message=> ::" . $_REQUEST['message1'] . "mobno=>" . json_encode($retArr));
        $this->General->sendMessage($retArr, $_REQUEST['message1'], $root);
        unset($retArr);
        $this->set('Error', "Sent successfully!");
        $this->redirect('/panels/retMsg');
    }
    // $this->set('retailer',$ret);
}

/*
 * function saveMaintenanceSM($maintenanceSMId=null,$retMobile=null)
 * {
 * echo "maint sm id is ".$maintenanceSMId;
 * echo "retailer mobile is ".$retMobile;
 *
 * $this->User->query("update retailers set maint_salesman=$maintenanceSMId where mobile='$retMobile' ");
 * $this->redirect('/panels/retColl');
 * }
 */
function retColl($distId = null, $smId = null){
    ini_set("memory_limit", "1024M");
    $this->layout = 'products';
    // flag=1 => search retailers by mainenance Salesman
    // flag=2 => search retailers by acquision Salesman

    $query = "";
    $query1 = "";

    $distList = $this->Slaves->query("select distributors.id,distributors.company,users.mobile from distributors join users on (distributors.user_id = users.id) order by distributors.company");

    /** IMP DATA ADDED : START**/
    $dist_ids = array_map(function($element){
        return $element['distributors']['id'];
    },$distList);

    $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
    foreach ($distList as $key => $value) {
        $distList[$key]['distributors']['company'] = $imp_data[$value['distributors']['id']]['imp']['shop_est_name'];
    }
    /** IMP DATA ADDED : END**/

    $this->set('distList', $distList);

    if($distId == null) $distId = 1;
    $this->set('distId', $distId);
    $salesmenList = $this->Slaves->query("select id,name,mobile from salesmen WHERE active_flag=1 AND dist_id = $distId");
    $this->set('salesmenList', $salesmenList);

    // echo $distId;
    // echo $distId;

    if($smId == null || $smId == 0){

        $query = " AND parent_id = $distId";
        $query1 = " INNER JOIN retailers ON (retailers.parent_id = $distId AND retailers.id = source_id)";
    }
    else{

        $this->set('sid', $smId);
        $query = " AND parent_id = $distId AND maint_salesman = $smId";
        $query1 = " INNER JOIN retailers ON (retailers.parent_id = $distId AND retailers.maint_salesman = $smId AND retailers.id = source_id)";
    }

    $search_query = trim($_POST['search_term']) ? " and (ret.mobile like '%" . trim($_POST['search_term']) . "%' or ret.shopname like '%" . $_POST['search_term'] . "%') " : "";
    $this->set('search_term', trim($_POST['search_term']));
    // echo $query1;
    // echo $query;
    $amountTransaferredQuery = "select salesmen.name, salesmen.mobile,salesmen.id,ret.block_flag, ret.id,ret.name,
					ret.maint_salesman,ur.shopname,ret.rental_flag, ret.mobile, ret.parent_id, users.balance, ret.toShow ,
					Date(ret.created) as created
				from retailers ret left join salesmen on (salesmen.id = ret.maint_salesman)
                                inner join unverified_retailers ur ON (ret.id = ur.retailer_id)
                                inner join users ON (users.id =ret.user_id)
				where 1 $query $search_query group by ret.id order by ret.id desc";
    // $amountTransferred = $this->Slaves->query ( "select salesmen.name, salesmen.mobile,salesmen.id,ret.block_flag, ret.id,ret.name,ret.maint_salesman,ret.shopname,ret.rental_flag, ret.mobile, ret.parent_id, ret.balance, ret.toShow , Date(ret.created) as created from retailers ret left join salesmen on (salesmen.id = ret.salesman) where 1 $query group by ret.id order by ret.id desc" ); // ret.toshow=1
    $amountTransferred = $this->paginate_query($amountTransaferredQuery);

    /** IMP DATA ADDED : START**/
    $ret_ids = array_map(function($element){
        return $element['ret']['id'];
    },$amountTransferred);

    $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
    foreach ($amountTransferred as $key => $value) {
        $amountTransferred[$key]['ret']['name'] = $imp_data[$value['ret']['id']]['imp']['name'];
        $amountTransferred[$key]['ur']['shopname'] = $imp_data[$value['ret']['id']]['imp']['shop_est_name'];
    }
    /** IMP DATA ADDED : END**/

    $this->set('amountTransferred', $amountTransferred);

    // $amountCollected = array();
    // $averageResult=$this->Slaves->query("SELECT avg(sale) as avg_ret, retailer_id from retailers_logs use index(idx_date),retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id = $distId AND retailers_logs.date > '".date('Y-m-d',strtotime('-30 days'))."' group by retailer_id");
    // foreach($averageResult as $avg){
    // $amountCollected[$avg['retailers_logs']['retailer_id']]['average'] = $avg['0']['avg_ret'];
    // }
    // $averageResult1=$this->Slaves->query("SELECT avg(sale) as avg_ret, retailer_id from retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id = $distId group by retailer_id");
    // foreach($averageResult1 as $avg){
    // $amountCollected[$avg['retailers_logs']['retailer_id']]['average1'] = $avg['0']['avg_ret'];
    // }
    // $this->set('amountCollected',$amountCollected);

    // $res=$this->Slaves->query("select st.ref2_id, sum(slt.collection_amount) as sm from salesman_transactions slt join shop_transactions st on (slt.shop_tran_id = st.id) inner join retailers ON (retailers.id = st.ref2_id AND retailers.parent_id = $distId) where st.type = 18 group by st.ref2_id");
    // $setupCollected = array();
    // foreach($res as $ac){
    // $setupCollected[$ac['st']['ref2_id']] = $ac['0']['sm'];
    // }
    // $this->set('setupCollected',$setupCollected);
}

function changeDistributor(){
    $distid = $_REQUEST['sid'];
    $rid = $_REQUEST['rid'];

    $data = $this->Retailer->query("SELECT name,mobile,slab_id,user_id FROM distributors WHERE id = $distid");
    $dist_user = $data['0']['distributors']['user_id'];
    $slab = $data['0']['distributors']['slab_id'];
    $dist_mobile = $data['0']['distributors']['mobile'];

    $imp_data_dist = $this->Shop->getUserLabelData(array($distid),2,3);
    $distributor = $imp_data_dist[$distid]['imp']['shop_est_name'];

    $data_sm = $this->Retailer->query("SELECT id FROM salesmen WHERE user_id = $dist_user");
    $salesman_id = $data_sm['0']['salesmen']['id'];

    if($distid != 0){
        $retailers = $this->User->query("select retailers.mobile,users.balance,rental_flag from retailers inner join users ON (users.id = retailers.user_id) where retailers.id = $rid");
        $rental_flag = $retailers[0]['retailers']['rental_flag'] ? 1 : 0;
        $retailer_balance = $retailers[0]['users']['balance'];
	$retailer_mobile = $retailers[0]['retailers']['mobile'];
        if(($retailer_balance <= 500) || (($retailer_balance > 500) && ($_SESSION['Auth']['User']['group_id'] == SUPER_ADMIN))){
            $this->Retailer->query("UPDATE retailers
                                            SET parent_id = $distid,maint_salesman=$salesman_id,salesman=$salesman_id,slab_id=$slab, rental_flag = $rental_flag, modified = '" . date('Y-m-d H:i:s') . "'
                                            WHERE id = $rid");
            $this->Shop->updateSlab($slab, $rid, RETAILER);
	//send sms
            $varParseArr = array (
                        'dist_mobile'           =>  $dist_mobile,
                        'dist_name'             =>  $distributor,
                           );
            $this->General->sendTemplateSMSToMobile($retailer_mobile,"smsToRetailerOnDistributorChange",$varParseArr);
        }
    }
    $this->autoRender = false;
}

/**
 * @params : distributor id and multiple retailer ids
 * @does : updates distributor for multiple retailers
 */
function changeDistributorForMultipleRetailers(){
    $distributor_id = $this->params['form']['distributor'];
    $retailer_ids = $this->params['form']['retailers'];
    $response = array();

    $data = $this->Retailer->query("SELECT name,mobile,slab_id,user_id FROM distributors WHERE id = $distributor_id");
    $dist_user = $data['0']['distributors']['user_id'];
    $slab = $data['0']['distributors']['slab_id'];
    $dist_mobile = $data['0']['distributors']['mobile'];

    $imp_data_dist = $this->Shop->getUserLabelData(array($distributor_id),2,3);
    $distributor = $imp_data_dist[$distributor_id]['imp']['shop_est_name'];

    $data_sm = $this->Retailer->query("SELECT id FROM salesmen WHERE user_id = $dist_user");
    $salesman_id = $data_sm['0']['salesmen']['id'];

    if($distributor_id != 0){
        if(is_array($retailer_ids) && count($retailer_ids) > 0){
            foreach($retailer_ids as $retailer_id){
                $retailers = $this->User->query('select retailers.mobile,retailers.name,users.balance,rental_flag from retailers inner join users ON (users.id =retailers.user_id) where id = ' . $retailer_id);
                $rental_flag = $retailers[0]['retailers']['rental_flag'] ? 1 : 0;
                $retailer_balance = $retailers[0]['users']['balance'];
                $retailer_name = $retailers[0]['retailers']['name'];
		$retailer_mobile = $retailers[0]['retailers']['mobile'];
                if(($retailer_balance <= 500) || (($retailer_balance > 500) && ($_SESSION['Auth']['User']['group_id'] == SUPER_ADMIN))){
                    $this->Retailer->query("UPDATE retailers
                                            SET parent_id = " . $distributor_id . ",maint_salesman = $salesman_id,salesman=$salesman_id,slab_id='$slab',rental_flag = '$rental_flag',modified = '" . date('Y-m-d H:i:s') . "' WHERE id = " . $retailer_id);
                    $this->Shop->updateSlab($slab, $retailer_id, RETAILER);
                    $response['success_shifts'][$retailer_id] = $retailer_name;
		//send sms
                    $varParseArr = array (
                        'dist_mobile'           =>  $dist_mobile,
                        'dist_name'             =>  $distributor,
                           );
                    $this->General->sendTemplateSMSToMobile($retailer_mobile,"smsToRetailerOnDistributorChange",$varParseArr);
                }
                else{
                    $response['failed_shifts'][$retailer_id] = $retailer_name;
                }
            }
        }
    }
    if(count($response) > 0){
        echo json_encode($response);
    }
    $this->autoRender = false;
}

function retailerSale($frm = null, $to = null, $frms_time=null, $tos_time=null, $vendor = 0, $dist = null){
    // if($this->Session->read('Auth.User.group_id') !=ADMIN && $this->Session->read('Auth.User.id') != 1)
    // $this->redirect('/shops/view');
    if(isset($_REQUEST['from'])){
        $frm = $_REQUEST['from'];
        $to = $_REQUEST['to'];
    }
       if(!isset($frm))$frm = date('d-m-Y');
       if(!isset($to)) $to  = date('d-m-Y');
       $frms_time = !isset($frms_time) ? '00:00:00' : str_replace('.', ':', $frms_time.'.00');
       $tos_time  = !isset($tos_time) ? '23:59:59' : str_replace('.', ':', $tos_time.'.00');

    $nodays = (strtotime($to) - strtotime($frm)) / (60 * 60 * 24);
    $nodays += 1;

    if($nodays <= 8){
        $fdarr = explode("-", $frm);
        $tdarr = explode("-", $to);

        $fd   = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
        $ft   = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];
		$frmt = $fd.' '.$frms_time;
        $tot  = $ft.' '.$tos_time;


        $vendorDDResult = $this->Slaves->query("select id,company from vendors");
        $distDDResult = $this->Slaves->query("select id,company from distributors where active_flag = 1 AND parent_id = 3 order by company");

        /** IMP DATA ADDED : START**/
        $dist_ids = array_map(function($element){
            return $element['distributors']['id'];
        },$distDDResult);

        $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
        foreach ($distDDResult as $key => $value) {
            $distDDResult[$key]['distributors']['company'] = $imp_data[$value['distributors']['id']]['imp']['shop_est_name'];
        }
        /** IMP DATA ADDED : END**/

        $this->set('vendorDDResult', $vendorDDResult);
        $this->set('distDDResult', $distDDResult);

        if($vendor == 0) $vndStr = '';
        else $vndStr = ' and va.vendor_id=' . $vendor . ' ';

        if(empty($dist)) $distStr = '';
        else $distStr = ' and va.distributor_id=' . $dist . ' ';

        /*
         * $success=$this->Retailer->query("SELECT sum(if(va.status != ".TRANS_FAIL." AND va.status != ".TRANS_REVERSE.",va.amount,0)) as Amount,sum(if(va.status != ".TRANS_FAIL." AND va.status != ".TRANS_REVERSE.",1,0)) as success,sum(if(va.api_flag=1 AND va.status != ".TRANS_FAIL." AND va.status != ".TRANS_REVERSE.",1,0)) as app_success,sum(if(va.api_flag=2 AND va.status != ".TRANS_FAIL." AND va.status != ".TRANS_REVERSE.",1,0)) as ussd_success,sum(if(va.status = ".TRANS_FAIL." OR va.status = ".TRANS_REVERSE.",1,0)) as failure from vendors_activations as va WHERE va.date between '".$fd."' and '".$ft."' $vndStr order by va.id desc");
         * $this->set('success',$success);
         */

        // for operator wise SUCCESSFUL reversal
        // SET @@group_concat_max_len = 25000;

        $operatorSale = $this->Slaves->query("
                        SELECT
                            p.name,
                            p.id,
                            va.retailer_id,
                            count(va.id) as count,
                            sum(if(va.retailer_id != 13 AND va.status != " . TRANS_FAIL . " AND va.status != " . TRANS_REVERSE . ",va.amount,0)) as b2bsuccess,
                            sum(if(va.status != " . TRANS_FAIL . " AND va.status != " . TRANS_REVERSE . ",va.amount,0)) as success,
							sum(if(va.status != " . TRANS_FAIL . " AND va.status != " . TRANS_REVERSE . " and vendors.update_flag=0,va.amount,0)) as api_success,
							sum(if(va.status != " . TRANS_FAIL . " AND va.status != " . TRANS_REVERSE . " and vendors.update_flag=1,va.amount,0)) as modem_success,
                            sum(if(va.status != " . TRANS_FAIL . " AND va.status != " . TRANS_REVERSE . ",1,0)) as scount,
                            sum(if(va.api_flag=1,1,0)) as app_sale,
                            sum(if(va.api_flag=2,1,0)) as ussd_sale ,
                            sum(if(va.api_flag=3,1,0)) as android_sale ,
                            sum(if(va.api_flag=5,1,0)) as java_sale,
                            sum(if(va.api_flag=9,1,0)) as web_sale,
                            sum(if(va.status not in (2,3),va.retailer_margin*100+va.amount*(if(distributors.parent_id = 3,distributors.margin*100/(100+distributors.margin) ,0) + (master_distributors.margin*100/(100+master_distributors.margin))),0)) as comm,
                            sum(va.amount) as tot
                        FROM
                            vendors_activations va JOIN products p on (va.product_id=p.id)
                            left join retailers ON (retailers.id = va.retailer_id)
							left join vendors ON (vendors.id = va.vendor_id)
							inner join distributors ON (distributors.id = retailers.parent_id)
							left join master_distributors ON (master_distributors.id = distributors.parent_id)
                        WHERE
                            va.date between '" . $fd . "' and '" . $ft . "' and va.timestamp between '".$frmt."' and '".$tot."' $vndStr $distStr
                        GROUP BY
                            p.id
                        ORDER BY success desc");

        $this->set('operatorSale', $operatorSale);

        $retCount = $this->Slaves->query("  SELECT
                                                             va.api_flag ,count( DISTINCT va.retailer_id ) as ret_count
                                                        FROM
                                                            vendors_activations va
                                                            left join retailers ON (retailers.id = va.retailer_id)
                                                            WHERE
                                                            va.date between '" . $fd . "' and '" . $ft . "' and va.timestamp between '".$frmt."' and '".$tot."' $vndStr $distStr
                                                        GROUP BY
                                                            va.api_flag
                                                        ");
        $retCountArr = array();
        foreach($retCount as $cnt){
            $retCountArr[$cnt['va']['api_flag']] = $cnt[0]['ret_count'];
        }
        $this->set('retCountArr', $retCountArr);

        $success = array();
        $success['sale'] = 0;
        $success['success'] = 0;
        $success['ussd'] = 0;
        $success['app'] = 0;
        $success['android'] = 0;
        $success['java'] = 0;
        $success['windows7'] = 0;
        $success['windows8'] = 0;
        $success['web'] = 0;
        $success['tot'] = 0;
        $success['comm'] = 0;
        $success['failed'] = 0;
        $success['api_success'] = 0;
        $success['modem_success'] = 0;

        if( ! empty($operatorSale) && count($operatorSale) > 0){
            foreach($operatorSale as $sale){
                // if (($vendor == 0 && $sale['va']['retailer_id'] != 13) || $vendor != 0){
                // $this->General->logData("/tmp/saledata.txt",date("Y-m-d H:i:s")." :: ".$sale['p']['name']." ::sale ::".$sale['0']['success']." total :: ".$success['sale']);

                $success['sale'] += ($vendor == 0) ? $sale['0']['b2bsuccess'] : $sale['0']['success'];
                $success['success'] += $sale['0']['scount'];
                $success['failed'] += $sale['0']['count'] - $sale['0']['scount'];
                $success['ussd'] += $sale['0']['ussd_sale'];
                $success['app'] += $sale['0']['app_sale'];
                $success['android'] += $sale['0']['android_sale'];
                $success['java'] += $sale['0']['java_sale'];
                $success['windows7'] += isset($sale['0']['windows7_sale']) ? $sale['0']['windows7_sale'] : 0;
                $success['windows8'] += isset($sale['0']['windows8_sale']) ? $sale['0']['windows8_sale'] : 0;
                $success['web'] += $sale['0']['web_sale'];
                $success['tot'] += $sale['0']['tot'];
                $success['comm'] += $sale['0']['comm'];
                $success['api_success'] += $sale['0']['api_success'];
                $success['modem_success'] += $sale['0']['modem_success'];
                // }
            }
        }
        $this->set('success', $success);

        /*
         * //for operator wise sale of successful transaction
         * $operatorSuccessSale=$this->User->query("select p.name,sum(va.amount) as Total,count(va.id) as count from vendors_activations va join products p on (va.product_id=p.id)
         * WHERE (va.status<>'".TRANS_FAIL."' and va.status<>'".TRANS_REVERSE."')
         * and va.date between '".$fd."' and '".$ft."' $vndStr group by p.name order by Total desc");
         *
         * $this->set('operatorSuccessSale',$operatorSuccessSale);
         */
        /*
         * $retailers = array();
         * foreach($successSaleResult as $result)
         * {
         * if(!empty($result['r']['name']))
         * {
         * $name = $result['r']['name'];
         * }
         * else $name = $result['r']['mobile'];
         *
         * $retailers[$result['r']['id']] = array('name' => $name,'mobile' => $result['r']['mobile'],'shopname' => $result['r']['shopname'],'tot' => $result['0']['Amount'],'balance' => $result['r']['balance'],'min' => $result['0']['firstDay'],'sale' => 0);
         *
         * $this->set('retailers',$retailers);
         * }
         */
}
        $this->set('frm', $frm);
        $this->set('to', $to);
        $this->set('days', $nodays);
        $this->set('vendor', $vendor);
        $this->set('dist', $dist);
        $this->set('frms_time',$frms_time);
        $this->set('tos_time',$tos_time);
}

function regReversal(){
    App::import('Controller', 'Apis');
    $obj = new ApisController();
    $obj->constructClasses();
    $ret = $obj->reversal(array('id'=>$_REQUEST['id'],'bbps'=>1, 'mobile'=>$_REQUEST['mobile'], 'user_id'=>$_SESSION['Auth']['User']['id'], 'turnaround_time'=>$_REQUEST['turnaroundTime']), 'json', $_SESSION['Auth']['User']['id']);
    // echo $ret['status'];
    if(isset($ret['mobile'])){
        // $this->General->sendMessage($ret['mobile'],$ret['msg'],$ret['root']);
    }
    echo json_encode($ret);
    $this->autoRender = false;
}

/*
 * function operatorStatus($frm=null,$to=null){
 * if(isset($_REQUEST['from'])){
 * $frm=$_REQUEST['from'];
 * $to=$_REQUEST['to'];
 * }
 * //echo "From retailer panel";
 * if(!isset($frm))$frm = date('d-m-Y');
 * if(!isset($to))$to = date('d-m-Y');
 *
 * //echo "FROM".$frm;
 * //echo "TO".$to;
 * $fdarr = explode("-",$frm);
 * $tdarr = explode("-",$to);
 *
 * $fd = $fdarr[2]."-".$fdarr[1]."-".$fdarr[0];
 * $ft = $tdarr[2]."-".$tdarr[1]."-".$tdarr[0];
 *
 * //for operator wise SUCCESSFUL reversal
 * $operatorReversalsResult=$this->Slaves->query("select p.name,sum(va.amount) as total from vendors_activations va join products p on (va.product_id=p.id)
 * where (va.status='".TRANS_REVERSE."') and va.date between '".$fd."' and '".$ft."' group by p.name");
 * $this->set('operatorReversalResult',$operatorReversalsResult);
 *
 * //for operator wise sale of successful transaction
 * $operatorSuccessSale=$this->Slaves->query("select p.name,sum(va.amount) as Total from vendors_activations va join products p on (va.product_id=p.id)
 * WHERE (va.status<>'".TRANS_FAIL."' and va.status<>'".TRANS_REVERSE."')
 * and va.date between '".$fd."' and '".$ft."' group by p.name order by Total desc");
 *
 * $this->set('operatorSuccessSale',$operatorSuccessSale);
 * $this->set('frm',$frm);
 * $this->set('to',$to);
 * }
 */
function tranReversal($frm = null, $to = null, $vendor = null,$products = null,$type = null){

    //For getting vendor in Array
    $v = explode(',',$vendor);
    $vendorss = implode("','",$v);
    //For getting product in Array
    $p = explode(',',$products);
    $productss = implode("','",$p);

    if(isset($_REQUEST['from'])){
        $frm = $_REQUEST['from'];
        $to = $_REQUEST['to'];
    }
    $query = "";
    if(isset($_REQUEST['b2c_flag'])){
        $query .= "AND va.retailer_id =" . B2C_RETAILER;
        $this->set('b2c_flag', $_REQUEST['b2c_flag']);
    }

    if( ! isset($frm)) $frm = date('d-m-Y');
    if( ! isset($to)) $to = date('d-m-Y');

    $fdarr = explode("-", $frm);
    $tdarr = explode("-", $to);

    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    $qpart = "";
    if( ! empty($vendor)){
        $qpart = "AND v.id IN ('$vendorss')";
    }
    $productpart = "";
    if( ! empty($products)){
        $productpart = "AND p.id IN ('$productss')";
    }

    $retcords = $this->Shop->getMemcache("newretailers");

    if(empty($retcords)){

        $retcords= $this->Slaves->query("select retailers.id from retailers where date(created)>='" . date("Y-m-d", strtotime("-30 day")) . "' and date(created)<='" . date("Y-m-d") . "'");

        $this->Shop->setMemcache("newretailers", $retcords, 1 * 24 * 60 * 60);
    }

    if( ! empty($retcords)){

        foreach($retcords as $val){

            $retailerData[$val['retailers']['id']] = $val['retailers']['id'];
            $retailersList[] = $val['retailers']['id'];
        }
    }
    //Getting Newly created Retailer
    /*foreach($getNewRetailer as $newRet){
        $retailersList[] = $newRet['retailers']['id'];
    }*/
    $newRetailer = implode("','",$retailersList);

if($type == 1) {
    $query2 = "AND r.id != 13 AND va.retailer_id NOT IN ('$newRetailer') AND complaints.takenby = 0";
    // None User
    $success = $this->Slaves->query("SELECT c.comments,t.name,v.shortForm,v.company,r.name,r.id,r.mobile,r.shopname,p.name,va.id,va.vendor_refid,va.mobile, va.txn_id, va.amount, va.status, va.timestamp, complaints.in_date,complaints.in_time,complaints.id,complaints.takenby,complaints.turnaround_time
				from complaints inner join vendors_activations va ON (complaints.vendor_activation_id = va.id) join comments c on(va.txn_id=c.ref_code) join taggings t on(t.id = c.tag_id) join retailers r on(va.retailer_id=r.id) join products p on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id)
				where  complaints.in_date between '$fd' and '$ft' AND complaints.resolve_flag = 0  $query $query2 $qpart $productpart group  by txn_id order by if(UNIX_TIMESTAMP(complaints.turnaround_time) > 0, complaints.turnaround_time, '3020-01-01 00:00:00') asc, complaints.id desc");
}
else if($type == 2){
    //New Retailer but not with call complaint
    $query2 = "AND va.retailer_id IN ('$newRetailer') AND complaints.takenby = 0 ";
    $success = $this->Slaves->query("SELECT c.comments,t.name,v.shortForm,v.company,r.name,r.id,r.mobile,r.shopname,p.name,va.id,va.vendor_refid,va.mobile, va.txn_id, va.amount, va.status, va.timestamp, complaints.in_date,complaints.in_time,complaints.id,complaints.takenby,complaints.turnaround_time
				from complaints inner join vendors_activations va ON (complaints.vendor_activation_id = va.id) join comments c on(va.txn_id=c.ref_code) join taggings t on(t.id = c.tag_id) join retailers r on(va.retailer_id=r.id) join products p on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id)
				where  complaints.in_date between '$fd' and '$ft' AND complaints.resolve_flag = 0  $query $query2 $qpart $productpart group  by txn_id order by if(UNIX_TIMESTAMP(complaints.turnaround_time) > 0, complaints.turnaround_time, '3020-01-01 00:00:00') asc, complaints.id desc");
}
else if($type == 3){
    //New Retailer but with call complaint
    $query2 = " AND va.retailer_id IN ('$newRetailer') AND complaints.takenby != 0 ";
    $success = $this->Slaves->query("SELECT c.comments,t.name,v.shortForm,v.company,r.name,r.id,r.mobile,r.shopname,p.name,va.id,va.vendor_refid,va.mobile, va.txn_id, va.amount, va.status, va.timestamp, complaints.in_date,complaints.in_time,complaints.id,complaints.takenby,complaints.turnaround_time
				from complaints inner join vendors_activations va ON (complaints.vendor_activation_id = va.id) join comments c on(va.txn_id=c.ref_code) join taggings t on(t.id = c.tag_id) join retailers r on(va.retailer_id=r.id) join products p on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id)
				where  complaints.in_date between '$fd' and '$ft' AND complaints.resolve_flag = 0  $query $query2 $qpart $productpart group  by txn_id order by if(UNIX_TIMESTAMP(complaints.turnaround_time) > 0, complaints.turnaround_time, '3020-01-01 00:00:00') asc, complaints.id desc");
}
else if($type == 4){
    // call with complaints
    $query2 = "and complaints.takenby != 0 AND va.retailer_id NOT IN ('$newRetailer')";
    $success = $this->Slaves->query("SELECT c.comments,t.name,v.shortForm,v.company,r.name,r.id,r.mobile,r.shopname,p.name,va.id,va.vendor_refid,va.mobile, va.txn_id, va.amount, va.status, va.timestamp, complaints.in_date,complaints.in_time,complaints.id,complaints.takenby,complaints.turnaround_time
				from complaints inner join vendors_activations va ON (complaints.vendor_activation_id = va.id) join comments c on(va.txn_id=c.ref_code) join taggings t on(t.id = c.tag_id) join retailers r on(va.retailer_id=r.id) join products p on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id)
				where  complaints.in_date between '$fd' and '$ft' AND complaints.resolve_flag = 0  $query $query2 $qpart $productpart group  by txn_id order by if(UNIX_TIMESTAMP(complaints.turnaround_time) > 0, complaints.turnaround_time, '3020-01-01 00:00:00') asc, complaints.id desc");
}else {

    $success = $this->Slaves->query("SELECT c.comments,t.name,v.shortForm,v.company,r.name,r.id,r.mobile,r.shopname,p.name,va.id,va.vendor_refid,va.mobile, va.txn_id, va.amount, va.status, va.timestamp, complaints.in_date,complaints.in_time,complaints.id,complaints.takenby,complaints.turnaround_time
				from complaints inner join vendors_activations va ON (complaints.vendor_activation_id = va.id) join comments c on(va.txn_id=c.ref_code) join taggings t on(t.id = c.tag_id) join retailers r on(va.retailer_id=r.id) join products p on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id)
				where  complaints.in_date between '$fd' and '$ft' AND complaints.resolve_flag = 0  $query  $qpart $productpart group  by txn_id order by if(UNIX_TIMESTAMP(complaints.turnaround_time) > 0, complaints.turnaround_time, '3020-01-01 00:00:00') asc, complaints.id desc");
}
    /** IMP DATA ADDED : START**/
    $ret_mobiles = array_map(function($element){
        return $element['r']['mobile'];
    },$success);

    $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);

    $retailer_imp_label_map = array(
        'pan_number' => 'pan_no',
        'shopname' => 'shop_est_name',
        'alternate_number' => 'alternate_mobile_no',
        'email' => 'email_id',
        'shop_structure' => 'shop_ownership',
        'shop_type' => 'business_nature'
    );
    foreach ($success as $key => $retailer) {
        foreach ($retailer['r'] as $retailer_label_key => $value) {
            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
            if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['mobile']]['imp']) ){
                $success[$key]['r'][$retailer_label_key] = $imp_data[$retailer['r']['mobile']]['imp'][$retailer_label_key_mapped];
            }
        }
    }

    $newRetailer=array();
    /** IMP DATA ADDED : END**/

    foreach($success as $val){

        if(in_array($val['r']['id'], $retailerData)){
            $newRetailer[$val['va']['txn_id']] = $val;
        }
        else{
            $data[] = $val;
        }
    }

    foreach($newRetailer as $newretval){

        $successdata[] = $newretval;
    }

    foreach($data as $dataval){

        $successdata[] = $dataval;
    }

    $retailerNameResult = $success['0']['r']['name'];

    // $closed_count = $this->Slaves->query("SELECT count(*) as count
    // from complaints
    // inner join vendors_activations va ON (complaints.vendor_activation_id = va.id)
    // join products p on(p.id=va.product_id)
    // left join users ON (users.id = complaints.closedby)
    // join vendors v on(v.id=va.vendor_id)
    // where complaints.resolve_date between '$fd' and '$ft'
    // AND complaints.resolve_flag = 1 $qpart $query");


    $closed_count = $this->Slaves->query("SELECT count(distinct vendor_activation_id) count FROM complaints
                                            WHERE resolve_date BETWEEN '$fd' AND '$ft' AND resolve_flag = 1");

    $this->set("closed_count", $closed_count[0][0]['count']);

    $this->set('success', $successdata);

    $this->set('frm', $frm);
    $this->set('to', $to);
    $this->set('vendorss', $v);
    $this->set('productss', $p);
    $this->set('vendor', $vendor);
    $this->set('products', $products);

    $this->set('type', $type);
    $vendorDDResult = $this->Slaves->query("select id,company from vendors");
    $productDDResult = $this->Slaves->query("select id,name from products where to_show = '1'");
    $this->set('vendorDDResult', $vendorDDResult);
    $this->set('productDDResult', $productDDResult);

        $this->set('retailerData', $retailerData);


    /// Inprocess Data will come in marquee
    $time = date('Y-m-d H:i:s', strtotime('-5 minutes'));

    $process = $this->Slaves->query("SELECT v.id,v.company,p.id,p.name
                    from vendors_activations va
                    join products p on(p.id=va.product_id)
                    join vendors v on(v.id=va.vendor_id)
                    left join vendors_transactions vt on vt.vendor_id = v.id and vt.ref_id = va.txn_id
                    left join devices_data dd on dd.vendor_id = v.id and dd.mobile = vt.sim_num and  dd.sync_date = '$fd'
                    where v.update_flag = 0 and va.date between '$fd' and '$ft'
                    AND (va.status = 0 OR (va.prevStatus = 0 AND va.status = 4))
                    AND va.timestamp <= '$time'
                    group by va.id
                    order by va.timestamp");

    $in_process_vendors = array();
    $in_process_products = array();
    $in_process_vendors_count = array();
    $in_process_products_count = array();

    $vendors = array();
    $product = array();
    foreach($process as $p){
            $in_process_vendors_count[$p['v']['id']] = isset($in_process_vendors_count[$p['v']['id']]) ? $in_process_vendors_count[$p['v']['id']] + 1 : 0;
            $in_process_products_count[$p['p']['id']] = isset($in_process_products_count[$p['p']['id']]) ? $in_process_products_count[$p['p']['id']] + 1 : 0;
            $in_process_vendors[$p['v']['id']] = $p['v']['company'];
            $in_process_products[$p['p']['id']] = $p['p']['name'];
            $vendors[$p['v']['id']] = $p['v']['id'];
            $product[$p['p']['id']] = $p['p']['id'];
    }

    $total_count = count($process);
    $vendor_wise_count = array_filter(
            $in_process_vendors_count,
            function ($value) use($total_count) {
                    return ($value > $total_count/10);
            }
    );

    $top_vendors = array_keys($vendor_wise_count);
    $product_wise_count = array_filter(
            $in_process_products_count,
            function ($value) use($total_count) {
                    return ($value > $total_count/10);
            }
    );
    $top_products = array_keys($product_wise_count);
    $this->set('vendors',$vendors);
    $this->set('product',$product);
    $this->set('products',$products);
    $this->set('top_vendors', $top_vendors);
    $this->set('top_products', $top_products);
    $this->set('in_process_vendors', $in_process_vendors);
    $this->set('in_process_products', $in_process_products);
    $this->set('in_process_vendors_count', $in_process_vendors_count);
    $this->set('in_process_products_count', $in_process_products_count);
}

function closedComplaints($frm = null, $to = null, $vendor = null,$product = null, $b2c_flag = null){
    if( ! isset($frm)) $frm = date('d-m-Y');
    if( ! isset($to)) $to = date('d-m-Y');
    $fdarr = explode("-", $frm);
    $tdarr = explode("-", $to);

    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    $query = "";

    $v = explode(',',$vendor);
    $vendorss = implode("','",$v);


    $p = explode(',',$product);
    $productss = implode("','",$p);

    if(isset($b2c_flag)){
        $query .= " AND va.retailer_id =" . B2C_RETAILER;
    }
    if( ! empty($vendor)){
        $query .= " AND v.id IN ('$vendorss')";
    }
    if( ! empty($product)){
        $query .= " AND p.id IN ('$productss')";
    }


    $closed_temp = $this->Slaves->query("SELECT max(created) comment_created, a.* FROM (SELECT c.comments,c.created,t.name t_name,v.shortForm, v.company,users.name users_name,p.name,va.id va_id,va.vendor_refid,va.mobile, va.txn_id,
				va.amount, va.status, va.timestamp, complaints.*
				from complaints
				inner join vendors_activations va ON (complaints.vendor_activation_id = va.id)
                                join comments c on(va.txn_id=c.ref_code)
                                join taggings t on(t.id = c.tag_id)
				join products p on(p.id=va.product_id)
				left join users ON (users.id = complaints.closedby)
				join vendors v on(v.id=va.vendor_id)
				where complaints.resolve_date between '$fd' and '$ft' AND complaints.resolve_flag = 1 $query
                                order by complaints.id desc) a GROUP BY va_id ORDER BY a.resolve_date desc, a.resolve_time desc");

    $i = 0;
    foreach($closed_temp as $c_t){
        $closed[$i]['c'] = array('comments'=>$c_t['a']['comments'], 'created'=>$c_t[0]['comment_created']);
        $closed[$i]['t'] = array('name'=>$c_t['a']['t_name']);
        $closed[$i]['v'] = array('shortForm'=>$c_t['a']['shortForm'], 'company'=>$c_t['a']['company']);
        $closed[$i]['users'] = array('name'=>$c_t['a']['users_name']);
        $closed[$i]['p'] = array('name'=>$c_t['a']['name']);
        $closed[$i]['va'] = array('id'=>$c_t['a']['va_id'], 'vendor_refid'=>$c_t['a']['vendor_refid'], 'mobile'=>$c_t['a']['mobile'], 'txn_id'=>$c_t['a']['txn_id'], 'amount'=>$c_t['a']['amount'], 'status'=>$c_t['a']['status'], 'timestamp'=>$c_t['a']['timestamp']);
        $closed[$i]['complaints'] = array('id'=>$c_t['a']['id'], 'vendor_activation_id'=>$c_t['a']['vendor_activation_id'], 'takenby'=>$c_t['a']['takenby'], 'closedby'=>$c_t['a']['closedby'], 'in_date'=>$c_t['a']['in_date'], 'in_time'=>$c_t['a']['in_time'], 'resolve_date'=>$c_t['a']['resolve_date'],
                'resolve_time'=>$c_t['a']['resolve_time'], 'turnaround_time'=>$c_t['a']['turnaround_time'], 'resolve_flag'=>$c_t['a']['resolve_flag']);
        $i ++ ;
    }

    $this->set("closed", $closed);

    $temp_closed = array();
    foreach($closed as $close){
        $temp_closed[] = $close['va']['txn_id'];
    }

    $resolution_tag = $this->Slaves->query("SELECT * FROM (SELECT taggings.name, comments.ref_code FROM comments
                                            LEFT JOIN taggings ON (taggings.id = comments.tag_id)
                                            WHERE comments.ref_code IN ('" . implode("','", $temp_closed) . "')
                                            ORDER BY comments.created DESC) as t GROUP BY t.ref_code");

    $temp_rs = array();
    foreach($resolution_tag as $tag){
        $temp_rs[$tag['t']['ref_code']] = $tag['t']['name'];
    }

    $this->set('resolution_tag', $temp_rs);
}

function dishtvTxns(){

    $TPS_REQUEST_HASH = "TPS_DISHTV_DATA";
    $return = array();
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
                $data = $this->Retailer->query("SELECT * FROM vendors_activations as va where txn_id = '$request_id' AND status = 0 AND vendor_id = 0");

                if(empty($data))continue;
                $return[] = $data[0];

            }
        }

    }
    catch(Exception $ex){
        $this->General->logData("/mnt/logs/dishtv.txt", date('Y-m-d H:i:s') . ": not inserted data : " . $request_id);
    }

    $this->set('data',$return);
}

function inProcessTransactions($fromDate = null, $toDate = null, $vendorIds = null, $productIds = null){
    $fromDate = isset($_REQUEST['fromDate']) ? $_REQUEST['fromDate'] : date('d-m-Y');
    $toDate = isset($_REQUEST['toDate']) ? $_REQUEST['toDate'] : date('d-m-Y');

    $query = "";

    // if(isset($_REQUEST['b2c_flag']) && $_REQUEST['b2c_flag']){
    // $query.= " and va.retailer_id = ".B2C_RETAILER;
    // $this->set('b2c_flag', $_REQUEST['b2c_flag']);
    // }

    $modem_flag = isset($_REQUEST['modem_flag']) ? $_REQUEST['modem_flag'] : 1;
    $api_flag = isset($_REQUEST['api_flag']) ? $_REQUEST['api_flag'] : 1;
    if($modem_flag &&  ! $api_flag){
        $query .= " and v.update_flag=1 ";
    }
    else if( ! $modem_flag && $api_flag){
        $query .= " and v.update_flag=0 ";
    }
    else{
        $modem_flag = $api_flag = 1;
    }
    $this->set('modem_flag', $modem_flag);
    $this->set('api_flag', $api_flag);

    $vendorIds = isset($_REQUEST['vendorIds']) ? $_REQUEST['vendorIds'] : $vendorIds;
    if( ! empty($vendorIds)){
        $query .= " and v.id IN ($vendorIds) ";
        $vendorIds = explode(',', $vendorIds);
    }

    $productIds = isset($_REQUEST['productIds']) ? $_REQUEST['productIds'] : $productIds;
    if( ! empty($productIds)){
        $query .= " and p.id IN ($productIds) ";
        $productIds = explode(',', $productIds);
    }

    $toDate = $fromDate;
    $fdarr = explode("-", $fromDate);
    $tdarr = explode("-", $toDate);
    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    $time = date('Y-m-d H:i:s', strtotime('-5 minutes'));

    // in process transactions
    $process = $this->Slaves->query("SELECT v.*,p.id,p.name,va.mobile,r.mobile, va.retailer_id, va.txn_id, va.vendor_refid, va.amount,
					va.status, va.timestamp, va.date, (count(c.id) - sum(c.resolve_flag)) as complaint_flag, va.sim_num, dd.device_id,
					v.active_flag
				from vendors_activations va
				join products p on(p.id=va.product_id)
				left join vendors v on(v.id=va.vendor_id)
				left join complaints c on c.vendor_activation_id = va.id
				left join retailers r on r.id = va.retailer_id
				left join devices_data dd on dd.vendor_id = v.id and dd.mobile = va.sim_num and dd.sync_date = '$fd'
				where va.date between '$fd' and '$ft'
				AND (va.status = 0 OR (va.prevStatus = 0 AND va.status = 4))
				AND va.timestamp <= '$time' $query
				group by va.id
				order by va.timestamp");

    $vidarr     = array();
    $areaname   = array();

   foreach($process as $processval){
       $vidarr[] = "'".substr($processval['r']['mobile'],0,5)."'";
    }

    $circle_number  = implode(",", $vidarr);
    $circle_name    = $this->Slaves->query("select number, area from mobile_operator_area_map AS mn WHERE number IN ($circle_number)");

    foreach ($circle_name as $circle){

        $areaname[$circle['mn']['number']] = $circle['mn']['area'];
    }

    $this->set('circle', $areaname);

    $retcords = $this->Shop->getMemcache("newretailers");

    if(empty($retcords)){

        $getNewRetailer = $this->Slaves->query("select retailers.id from retailers where date(created)>='" . date("Y-m-d", strtotime("-30 day")) . "' and date(created)<='" . date("Y-m-d") . "'");

        $this->Shop->setMemcache("newretailers", $getNewRetailer, 1 * 24 * 60 * 60);
    }

    if( ! empty($retcords)){

        foreach($retcords as $val){

            $retailerData[$val['retailers']['id']] = $val['retailers']['id'];
        }
    }

    $b2c = array();
    $complaint = array();
    $rest = array();
    $novendor = array();
    $newRetailer = array();

    $vendor_wise_count = array();
    $product_wise_count = array();
    $in_process_vendors = array();
    $in_process_products = array();
    $in_process_vendors_count = array();
    $in_process_products_count = array();
    foreach($process as $p){
        $in_process_vendors_count[$p['v']['id']] = isset($in_process_vendors_count[$p['v']['id']]) ? $in_process_vendors_count[$p['v']['id']] + 1 : 1;
        $in_process_products_count[$p['p']['id']] = isset($in_process_products_count[$p['p']['id']]) ? $in_process_products_count[$p['p']['id']] + 1 : 1;

        if(in_array($p['va']['retailer_id'], $retailerData)){
            $newRetailer[$p['va']['txn_id']] = $p;
        }
        else if($p['va']['retailer_id'] == 13) $b2c[] = $p;
        else if($p[0]['complaint_flag']) $complaint[] = $p;
        else $rest[] = $p;

        if($p['v']['active_flag'] == 0){
            $disabled_modem[] = $p;
        }
        if(empty($p['va']['vendor_refid'])) $novendor[] = $p;

        $in_process_vendors[$p['v']['id']] = $p['v']['company'];
        $in_process_products[$p['p']['id']] = $p['p']['name'];
    }

    $this->set('b2c_count', count($b2c));
    $this->set('complaint_count', count($complaint));
    $this->set('disabled_modem_count', count($disabled_modem));
    $this->set('novendor_count', count($novendor));
    $this->set('normal_count', count($process) - count($b2c) - count($complaint) - count($novendor));
    $this->set('new_retailer', count($newRetailer));

    $total_count = count($process);
    $vendor_wise_count = array_filter($in_process_vendors_count, function ($value) use ($total_count){
        return ($value > $total_count / 10);
    });

    $top_vendors = array_keys($vendor_wise_count);
    $product_wise_count = array_filter($in_process_products_count, function ($value) use ($total_count){
        return ($value > $total_count / 10);
    });
    $top_products = array_keys($product_wise_count);
    $this->set('top_vendors', $top_vendors);
    $this->set('top_products', $top_products);
    $this->set('in_process_vendors', $in_process_vendors);
    $this->set('in_process_products', $in_process_products);
    $this->set('in_process_vendors_count', $in_process_vendors_count);
    $this->set('in_process_products_count', $in_process_products_count);

    $sorted_process = array();

    foreach($newRetailer as $k=>$n){
        $sorted_process[] = $n;
    }
    foreach($b2c as $b){
        $sorted_process[] = $b;
    }
    foreach($complaint as $c){
        $sorted_process[] = $c;
    }
    foreach($rest as $r){
        $sorted_process[] = $r;
    }
    $this->set('process', $sorted_process);

    $vendors = $this->Retailer->query("select id, company from vendors WHERE show_flag = 1 order by company");
    $this->set('vendors', $vendors);
    $this->set('vendorIds', $vendorIds);

    $products = $this->Slaves->query("select id, name from products where to_show = 1");
    $this->set('products', $products);
    $this->set('productIds', $productIds);

    $this->set('fromDate', $fromDate);
    $this->set('toDate', $toDate);
    $this->set('retailerData', $retailerData);

    $this->layout = 'sims';
}

/*
 * function tranProcess($frm=null,$to=null,$vendorId=null)
 * {
 * if(isset($_REQUEST['from'])){
 * $frm=$_REQUEST['from'];
 * $to=$_REQUEST['to'];
 * }
 *
 * $query = "";
 * //echo "From retailer panel";
 * if(!isset($frm))$frm = date('d-m-Y');
 * if(!isset($to))$to = date('d-m-Y');
 *
 *
 * if(isset($_REQUEST['b2c_flag'])){
 * $query.= "AND va.retailer_id =".B2C_RETAILER;
 * $this->set('b2c_flag',$_REQUEST['b2c_flag']);
 *
 * }
 *
 * $vendorIdQry = "";
 * if (!empty($vendorId)) {
 * $vendor = array();
 * $vendorIdQry = " AND v.id IN ($vendorId)";
 * $vendorId = explode(',', $vendorId);
 * }
 * $to = $frm;
 * $fdarr = explode("-",$frm);
 * $tdarr = explode("-",$to);
 *
 * $fd = $fdarr[2]."-".$fdarr[1]."-".$fdarr[0];
 * $ft = $tdarr[2]."-".$tdarr[1]."-".$tdarr[0];
 *
 * $time = date('Y-m-d H:i:s',strtotime('-5 minutes'));
 * //in process transactions
 * $process=$this->Slaves->query("SELECT v.*,p.name,va.mobile, va.retailer_id, va.txn_id, va.vendor_refid, va.amount,
 * va.status, va.timestamp, va.date, (count(c.id) - sum(c.resolve_flag)) as complaint_flag
 * from vendors_activations va
 * join products p on(p.id=va.product_id)
 * join vendors v on(v.id=va.vendor_id)
 * left join complaints c on c.vendor_activation_id = va.id
 * where va.date between '$fd' and '$ft' $vendorIdQry
 * AND (va.status = 0 OR (va.prevStatus = 0 AND va.status = 4))
 * AND va.timestamp <= '$time' $query
 * group by va.id
 * order by va.timestamp");
 * $b2c = array();
 * $complaint = array();
 * $rest = array();
 * foreach($process as $p){
 * if($p['va']['retailer_id'] == 13)
 * $b2c[] = $p;
 * else if($p[0]['complaint_flag'])
 * $complaint[] = $p;
 * else
 * $rest[] = $p;
 * }
 * $sorted_process = array();
 * foreach($b2c as $b){
 * $sorted_process[] = $b;
 * }
 * foreach($complaint as $c){
 * $sorted_process[] = $c;
 * }
 * foreach($rest as $r){
 * $sorted_process[] = $r;
 * }
 * $this->set('process',$sorted_process);
 *
 * $vendors=$this->Retailer->query("select id,company from vendors WHERE show_flag = 1 order by company");
 * $this->set('vendors',$vendors);
 * $this->set('vendorId',$vendorId);
 *
 * $this->set('frm',$frm);
 * $this->set('to',$to);
 * }
 */
function showComments(){
    if($refCode = $_REQUEST['refCode']){
        $commentsResult = $this->Slaves->query("SELECT c.comments,c.ref_code,u1.name,u1.mobile,c.created,t.name
					from comments c join users u1 on(c.mobile=u1.mobile)
					left join taggings t on t.id = c.tag_id
					where c.ref_code = '$refCode' order by c.created desc");
        echo "<div style='height:284px;overflow:auto;'>";
        echo "<table id='past_comments_" . $refCode . "' cellpadding='4' style='width:100%;'>";
        if($commentsResult){
            foreach($commentsResult as $cm){
                echo "<tr bgcolor='#CEF6F5' style='border: 2px solid white'>";
                echo "<td><span style='font-size:12px;'>By " . $cm['u1']['name'] . " @ " . $cm['c']['created'] . " on " . $cm['c']['ref_code'] . " (" . $cm['t']['name'] . ") </span></br>" . $cm['c']['comments'] . "</td>";
                echo "</tr>";
            }
        }
        else
            echo "<tr><td>No comments</td></tr>";

        echo "</table>";
        echo "</div>";
    }
    $this->autoRender = false;
}

function showTransaction(){
    if($trans = $_REQUEST['id']){
        $detailedTransaction = $this->Slaves->query("SELECT vm.*, vendors.shortForm
				FROM vendors_messages vm INNER JOIN vendors ON ( vendors.id = vm.service_vendor_id )
				WHERE vm.va_tran_id='" . $trans . "' order by vm.id desc");

        $query = $this->Slaves->query("Select source_opening,source_closing,shop_transactions.timestamp from shop_transactions
                                                   inner join vendors_activations ON (shop_transactions.id = vendors_activations.shop_transaction_id)
                                                   where vendors_activations.txn_id = '" . $trans . "'
                                                   UNION
                                                   SELECT source_opening,source_closing,shop_transactions.timestamp from shop_transactions
                                                   inner join vendors_activations ON (shop_transactions.target_id = vendors_activations.shop_transaction_id)
                                                   where vendors_activations.txn_id = '" . $trans . "' AND shop_transactions.type = '" . REVERSAL_RETAILER . "'");

        // $query= $this->Slaves->query("SELECT opening_closing.opening,opening_closing.closing,opening_closing.timestamp,vendors_activations.ref_code
        // from opening_closing
        // left join vendors_activations on ( opening_closing.shop_transaction_id = vendors_activations.shop_transaction_id)
        // where vendors_activations.ref_code = '".$trans."'");

        if($query){
            echo '
				<table border="1" cellpadding="0" cellspacing="0" width="100%">
                                <caption>Retailer Opening Closing</caption>
				<tr>
				<th>Opening</th>
				<th>Closing</th>
                                <th>Timestamp</th>
                                </tr>';
            foreach($query as $q){
                echo "<tr>";
                echo "<td style='text-align:center;'>" . $q[0]['source_opening'] . "</td>";
                echo "<td style='text-align:center;'>" . $q[0]['source_closing'] . "</td>";
                echo "<td style='text-align:center;'>" . $q[0]['timestamp'] . "</td>";
                echo "</tr>";
            }
            echo '</table>';
            echo '<br/>';
        }

        if($detailedTransaction){
            echo '<br/>';
            echo '

				<table border="1" cellpadding="0" cellspacing="0" width="100%">
				<tr>
				<th>Ref Code</th>
				<th>Vendor</th>
				<th>Vendor Id</th>
				<th>Internal Response</th>
				<th>Provider Response</th>
				<th>Status</th>
				<th>Timestamp</th>
				<th>Processing Time</th>
				</tr>';

            foreach($detailedTransaction as $d){
                $vendor = strtoupper($d['vendors']['shortForm']);
                echo "<tr>";
                echo "<td>" . $d['vm']['va_tran_id'] . "</td>";
                echo "<td>" . $vendor . "</td>";
                echo "<td>" . $d['vm']['vendor_refid'] . "</td>";
                echo "<td>" . $this->Shop->errors($d['vm']['internal_error_code']) . "</td>";
                echo "<td>" . $d['vm']['response'] . "</td>";
                echo "<td>" . $d['vm']['status'] . "</td>";
                echo "<td>" . $d['vm']['timestamp'] . "</td>";
                echo "<td>" . $d['vm']['processing_time'] . "</td>";
                echo "</tr>";
            }
            echo '</table>';
        }
        else
            echo "No transaction history";
    }
    else
        echo "Invalid transaction number";
    $this->autoRender = false;
}

function userInfo($value = null, $parameter = 'mobno', $fromDate = null, $toDate = null){
    if(empty($value)){}
    else if($parameter == 'mobno' || $parameter == 'subid'){
        // $fromDate = date('Y-m-d',strtotime($fromDate));
        // $toDate = date('Y-m-d',strtotime($toDate));

        if($parameter == 'mobno') $qStr = 'va.mobile';
        else if($parameter == 'subid') $qStr = 'va.param';
        $offset = 0;
        if($this->RequestHandler->isAjax()){
            $page = $_REQUEST['page'];
            $offset = $page * 3;
            $this->autoRender = false;
        }

        $retcords = $this->Shop->getMemcache("newretailers");

        if(empty($retcords)){

            $getNewRetailer = $this->Slaves->query("select retailers.id from retailers where date(created)>='" . date("Y-m-d", strtotime("-30 day")) . "' and date(created)<='" . date("Y-m-d") . "'");

            $this->Shop->setMemcache("newretailers", $getNewRetailer, 1 * 24 * 60 * 60);
        }

        if( ! empty($retcords)){

            foreach($retcords as $val){

                $retailerData[$val['retailers']['id']] = $val['retailers']['id'];
            }
        }

        $userTransResult = $this->Slaves->query("
					select va.id,va.vendor_refid,va.mobile,va.txn_id,va.date,va.vendor_refid,va.param,va.operator_id,services.name,vendors.company,vendors.shortForm,va.mobile,p.id,p.name,va.amount,
						va.timestamp,va.status,va.shop_transaction_id,r.id,r.mobile,r.shopname,r.name,r.address,sum(c.resolve_flag) as resolve_flag,count(c.resolve_flag) as count_resolve_flag
 					from vendors_activations va
					join vendors on(va.vendor_id=vendors.id)
					join products p on(p.id=va.product_id)
					join services on (p.service_id=services.id)
					join retailers as r  on r.id  = va.retailer_id
					left join complaints c on c.vendor_activation_id = va.id
 					where " . $qStr . " ='$value'
					group by va.id
                    order by va.timestamp desc limit 3 offset " . $offset);


        /** IMP DATA ADDED : START**/
        $ret_mobiles = array_map(function($element){
            return $element['r']['mobile'];
        },$userTransResult);

        $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);

        $retailer_imp_label_map = array(
            'pan_number' => 'pan_no',
            'shopname' => 'shop_est_name',
            'alternate_number' => 'alternate_mobile_no',
            'email' => 'email_id',
            'shop_structure' => 'shop_ownership',
            'shop_type' => 'business_nature'
        );
        foreach ($userTransResult as $key => $retailer) {
            foreach ($retailer['r'] as $retailer_label_key => $val) {
                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['mobile']]['imp']) ){
                    $userTransResult[$key]['r'][$retailer_label_key] = $imp_data[$retailer['r']['mobile']]['imp'][$retailer_label_key_mapped];
                }
            }
        }
        /** IMP DATA ADDED : END**/

        $this->set('userTrans', $userTransResult);

        if($userTransResult){
            foreach($userTransResult as $utr){
                $retailers[$utr['r']['id']] = array('shop'=>$utr['r']['shopname'], 'mobile'=>$utr['r']['mobile']);
            }
            $this->set('retailers', $retailers);
        }

        if($parameter == 'mobno'){
            $this->set('mobno', $value);
            $usersResult = $this->Slaves->query("select users.*,groups.name,groups.id from users join user_groups on (users.id = user_groups.user_id) join groups on (user_groups.group_id = groups.id) where users.mobile='$value'");
            $all_roles = array();
            foreach($usersResult as $uR){
                $all_roles[] = $uR['groups']['name'];
            }
            $usersResult[0]['groups']['name'] = implode(', ', $all_roles);
            $data = array('mobile_no'=>$value, 'token'=>$_COOKIE['CAKEPHP']);
//            $response = $this->General->curl_post(SMARTPAY_URL . '/fetchCustReport', $data, 'POST');

            $userData['transactions'] = array();
            if( isset($value) && !empty($value) ){
                $response = $this->Smartpaycomp->fetchCustomerReport($data);
                $userData = json_decode($response, true);
            }

            $userids = array();

            foreach($userData['transactions'] as $data):
                $userids[] = $data['user_id'];
            endforeach
            ;

            $userids = count($userids) > 1 ? implode(",", $userids) : ( ! empty($userids) ? $userids[0] : $userids);

            $mobilenos = $this->User->query("select id,user_id,mobile,shopname from retailers where user_id in ($userids)");
            $userDetails = array();

            /** IMP DATA ADDED : START**/
            $imp_data = $this->Shop->getUserLabelData($userids,2,0);
            /** IMP DATA ADDED : END**/

            foreach($mobilenos as $key => $val){
                $mobilenos[$key]['retailers']['shopname'] = $imp_data[$val['retailers']['user_id']]['imp']['shop_est_name'];
            }

            foreach($userData['transactions'] as $data){
                foreach($mobilenos as $val){
                    if($val['retailers']['user_id'] == $data['user_id']){
                        $data['retailer_id'] = $val['retailers']['id'];
                        $data['mobile'] = $val['retailers']['mobile'];
                    }
                }
                $userDetails[]=$data;
            }

			}
			else if($parameter == 'subid'){
				$this->set('subid', $value);
				$usersResult=$this->Slaves->query("select users.*,groups.name from users join user_groups on (users.id = user_groups.user_id) join groups on (user_groups.group_id = groups.id)  where users.mobile='".$userTransResult['0']['va']['mobile']."'");
                                $all_roles = array();
                                foreach($usersResult as $uR) {
                                        $all_roles[] = $uR['groups']['name'];
                                }
                                $usersResult[0]['groups']['name'] = implode(', ',$all_roles);
				$this->set('mobno', $usersResult['0']['users']['mobile']);
				$value = $usersResult['0']['users']['mobile'];
			}
			$this->set('uData', $usersResult);
			$this->set('retailerData', $retailerData);
			$this->set('userData', $userDetails);

			if($this->RequestHandler->isAjax()){
				foreach($userTransResult as $key => $data){

					if (in_array($data['r']['id'], $retailerData)) {

						$class = "background-color: rgba(255, 0, 0, 0.2)";
					} else {
						$class = '';
					}

					echo "<tr style = '".$class."'>";
					echo ">";
					echo "</td>";
					echo "<td> <a target='_blank' href='/panels/transaction/".$data['va']['txn_id']."'>".$data['va']['txn_id']."</a></td>";
					echo "<td><a target='_blank' href='/panels/retInfo/".$data['r']['mobile']."'>".$data['r']['mobile']."</a></td>";
					echo "<td><a target='_blank' href='/recharges/tranStatus/" . $data['va']['txn_id'] . "/" . $data ['vendors'] ['shortForm'] . "/" . $data ['va'] ['date'] . "/" . $data ['va'] ['vendor_refid'] . "'>".$data['vendors']['shortForm']."</a>";
					echo "&nbsp;/".$data['va']['vendor_refid']."&nbsp;</td>";
					echo "<td>".$data['va']['param']."</td>";
					echo "<td>".$data['va']['operator_id']."&nbsp;</td>";
				    echo "<td>".$data['p']['name']."&nbsp;</td>";
					echo "<td>".$data['va']['amount']."&nbsp;</td>";
					//	echo "<td>".$objShop->errors($data['vm']['internal_error_code'])."&nbsp;</td>";
					//  echo "<td>".$data['vm']['response']."&nbsp;</td>";
					//	echo "<td>".$data['vm']['status']."&nbsp;</td>";

					$status = '';
		  		    if($data['va']['status'] == '0'){
					$status = 'In Process';
		     		}else if($data['va']['status'] == '1'){
					$status = 'Successful';
			    	}else if($data['va']['status'] == '2'){
					$status = 'Failed';
				   }else if($data['va']['status'] == '3'){
					$status = 'Reversed';
				   }else if($data['va']['status'] == '4'){
					$status = 'Reversal In Process';
		     		}else if($data['va']['status'] == '5'){
					$status = 'Reversal declined';
			     	}

					$resolve_factor = $data ['0'] ['count_resolve_flag'] ? floor($data ['0'] ['resolve_flag'] / $data ['0'] ['count_resolve_flag']) : $data ['0'] ['resolve_flag'];

			     	$status_icon = 'icon_caution.png';
			     	$icon_complaint = 'resend.png';
			     	if(in_array($data ['va']['status'], array(0)))
			     		$status_icon = "hourglass.png";
			     	if(in_array($data['va']['status'], array(1, 4, 5)))
						$status_icon = "green-tick.png";

			     	$complaint_status = '';
			     	if (strlen($resolve_factor) > 0 && $resolve_factor == 0){
			     		$icon_complaint = "hourglass.png";
			     		$complaint_status = 'Complaint pending';
			     	}
			     	else if(strlen($resolve_factor) > 0 && $resolve_factor == 1){
			     		$icon_complaint = "doubletick.png";
			     		$complaint_status = 'Complaint resolved';
			     	}

					$reversalStats = "<img title='".$status."' style='max-height:15px;' src='/img/".$status_icon."' />&nbsp;&nbsp;&nbsp;";
					if($icon_complaint == 'resend.png'){
	// 					$reversalStats .= "<img id='icon_complaint' src='/img/".$icon_complaint."' style='max-height:15px;cursor:pointer' onclick='check_complaint(".$data['va']['id'].");' />";
					}
					else{
						$reversalStats .= "<img title='".$complaint_status."' id='icon_complaint' src='/img/".$icon_complaint."' style='max-height:15px;' />";
					}

					echo "<td>".$reversalStats."&nbsp;</td>";
					echo "<td>".$data['va']['timestamp']."&nbsp;</td>";
					//echo "<td><input type=button value=\"Request Reversal\" ></td>";
					echo "<td><a href=javascript:modal_factory('transaction','".$data['va']['txn_id']."','Transaction-History');>Transaction</a></td>";
					echo "<td><a name='transactionInfo' data-refCode='".$data['va']['txn_id'].
					"' data-tId='".$data['va']['id']."' data-userMobile='".$usersResult['0']['users']['mobile']."' data-retMobile='".$data['r']['mobile'].
					"' data-retId='".$data['r']['id']."' data-shopTId='".$data['va']['shop_transaction_id']."' data-clicked='false' ";

					if ($data ['va'] ['status'] == '0' || $data ['va'] ['status'] == '1' || $data ['va'] ['status'] == '4') {
						echo " data-actionReverse=true ";
						if (strlen($resolve_factor) > 0 && $resolve_factor == 0)
							echo " data-actionDecline=true ";
					} else if ($data ['va'] ['status'] == '5')
						echo " data-actionOpenTransaction=true ";
					else
						echo " data-actionPullBack=true ";
					if(!(strlen($resolve_factor) > 0 && $resolve_factor == 0))
						echo "data-complaint=true";
					echo " href=javascript:showActionModal();selectActions('".$data['va']['txn_id']."');>Comment</a> ";
					//echo "<td>".$data ['0'] ['resolve_flag']."&".$data ['0'] ['count_resolve_flag']."&".$resolve_factor."</td>";
					echo "</tr>";
				}
				exit;
			}
			//for user mobile -operator mapping

                        $mobileDetails = $this->General->getMobileDetailsNew($value);
			$this->set('mobileDetails', $mobileDetails);

			//$userId = empty($usersResult['0']['users']['id']) ? "" : $usersResult['0']['users']['id'];

			/*$taggingResult=$this->Retailer->query("select distinct t.name from taggings t join user_taggings tu on(t.id=tu.tagging_id) where tu.user_id=$userId order by tu.id desc");
			//$tags=$taggingResult['0']['t']['name'];
			if(empty($taggingResult)) $taggingResult = array();
			$this->set('tags',$taggingResult);*/
			/*if($userId){
				$commentsResult=$this->Slaves->query("SELECT c.comments,c.ref_code,u1.name,u1.mobile,c.created
					from comments c join users u on (u.id=c.users_id) join users u1 on(c.mobile=u1.mobile)
					where u.id=$userId  order by c.created desc");
            if(empty($commentsResult)) $commentsResult = array();
            $this->set('comment', $commentsResult);
        }*/
        // User to retailer Link
        // $userToRetailerResult=$this->User->query("select distinct r.mobile, r.shopname,r.name,r.address from retailers r join shop_transactions st on (r.id = st.ref1_id ) where st.user_id=$userId and st.type='".RETAILER_ACTIVATION."'");
        // if(empty($userToRetailerResult)) $userToRetailerResult = array();
        // $this->set('userRetailerResult', $userToRetailerResult);
    }
    // $fromDate = date_format(date_create($fromDate), 'd-m-Y');
    // $toDate = date_format(date_create($toDate), 'd-m-Y');
    // $this->set('fromDate', $fromDate);
    // $this->set('toDate', $toDate);
}

function retInfo($retMobile = null, $retid = null, $from = null, $to = null, $chk = null){
    $service_type = Configure::read('service_type');
    $pageType = empty($_GET['res_type']) ? "" : $_GET['res_type'];
    $this->set('objShop', $this->Shop);
    if( ! is_null($retid) && $retid != '-1' && trim($retMobile) == 'temp'){
        $retailerIdResult = $this->Slaves->query("select id,user_id,maint_salesman,mobile from retailers where id='" . $retid . "'");
        $retMobile = $retailerIdResult['0']['retailers']['mobile'];
    }
    else if( ! empty($retMobile)){
        $retailerIdResult = $this->Slaves->query("select id,user_id,maint_salesman from retailers where mobile='$retMobile'");
    }
    else{
        return;
    }

    if(empty($retailerIdResult)) return;
    if( ! isset($from)) $from = date('d-m-Y', strtotime('-1 day'));
    if( ! isset($to)) $to = date('d-m-Y');

    // echo "from".$from;
    // echo "to".$to;

    $this->set('from', $from);
    $this->set('to', $to);

    $fdarr = explode("-", $from);
    $tdarr = explode("-", $to);

    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    // $retailerIdResult=$this->Retailer->query("select id,user_id,maint_salesman from retailers where mobile='$retMobile'");
    $retId = isset($retailerIdResult['0']['retailers']['id']) ? $retailerIdResult['0']['retailers']['id'] : "";

    // $retailerUserId=$this->User->query("select id from users where mobile='".$retMobile."' ");
    $userId = isset($retailerIdResult[0]['retailers']['user_id']) ? $retailerIdResult[0]['retailers']['user_id'] : "";

    //
    $userServices = array();
    //$settings['settings'] = array();
    $reports['transactions'] = array();

    if( ! empty($userId)):
        $data = array('user_id'=>$userId, 'token'=>$_COOKIE['CAKEPHP'], 'from_txn_date'=>$fd, 'to_txn_date'=>$ft);

        $user_reports = $this->Smartpaycomp->fetchCustomerReport($data);
        $reports = json_decode($user_reports, true);

		endif;

    $this->set('service_type', $service_type);
    $salesmenResult = $this->Slaves->query("select distinct sm.name,sm.mobile,r.name,sm.created from salesmen sm join retailers r on(r.salesman=sm.id OR r.maint_salesman=sm.id) where r.id=$retId");
    if(empty($salesmenResult)){
        $salesmenResult = array();
    } else {
        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($retId,2,2);
        $salesmenResult[0]['r']['name'] = $imp_data[$retId]['imp']['name'];
        /** IMP DATA ADDED : END**/
    }

    $this->set('salesmenResult', $salesmenResult);
    $this->set('operators', $this->Shop->getProducts());

    // app based retailer request.
    $appRequestsResult = $this->Slaves->query("select params,method,description , timesatmp from app_req_log  where ret_id = $retId AND (method = 'mobRecharge' OR method = 'dthRecharge' OR method = 'vasRecharge' OR method = 'mobBillPayment' OR method = 'utilityBillPayment' OR method = 'getBusTicket') AND date between '" . $fd . "' and '" . $ft . "' order by id desc");
    $appRequests = isset($appRequestsResult['0']['app_req_log']['params']) ? $appRequestsResult['0']['app_req_log']['params'] : "";
    $this->set('appRequests', $appRequestsResult);

    $retInfo = $this->Slaves->query("select  retailers.*, users.balance,distributors.id,distributors.name, distributors.company, salesmen.name, ur.*
        		from  retailers
        		left join salesmen on (salesmen.id = retailers.maint_salesman)
        		left join users on (retailers.user_id = users.id)
        		left join distributors on (retailers.parent_id=distributors.id)
        		left join unverified_retailers ur on ur.retailer_id = retailers.id
        		where retailers.id =  '" . $retId . "' ");
    if(empty($retInfo)) $retInfo = array();
    else{
        foreach($retInfo[0]['ur'] as $key=>$row){
            if( ! in_array($key, array('id'))) $retInfo[0]['retailers'][$key] = $retInfo[0]['ur'][$key];
        }
    }

    /** IMP DATA ADDED : START**/
    $ret_ids = array_map(function($element){
        return $element['retailers']['id'];
    },$retInfo);
    $dist_ids = array_map(function($element){
        return $element['distributors']['id'];
    },$retInfo);
    $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
    $imp_data_dist = $this->Shop->getUserLabelData($dist_ids,2,3);

    $retailer_imp_label_map = array(
        'pan_number' => 'pan_no',
        'shopname' => 'shop_est_name',
        'alternate_number' => 'alternate_mobile_no',
        'email' => 'email_id',
        'shop_structure' => 'shop_ownership',
        'shop_type' => 'business_nature'
    );
    $dist_imp_label_map = array(
            'pan_number' => 'pan_no',
            'company' => 'shop_est_name',
            'alternate_number' => 'alternate_mobile_no',
            'email' => 'email_id'
        );
    foreach ($retInfo as $key => $retailer) {
        foreach ($retailer['retailers'] as $retailer_label_key => $value) {
            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
            if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['retailers']['id']]['imp']) ){
                $retInfo[$key]['retailers'][$retailer_label_key] = $imp_data[$retailer['retailers']['id']]['imp'][$retailer_label_key_mapped];
            }
        }

        if(isset($imp_data[$retailer['retailers']['id']]['imp']['annual_turnover'])){
        	$retInfo[$key]['retailers']['annual_turnover'] = $imp_data[$retailer['retailers']['id']]['imp']['annual_turnover'];
        }
        if(isset($imp_data[$retailer['retailers']['id']]['imp']['shop_area_type'])){
        	$retInfo[$key]['retailers']['shop_area_type'] = $imp_data[$retailer['retailers']['id']]['imp']['shop_area_type'];
        }

        if(isset($imp_data[$retailer['retailers']['id']]['imp']['shop_ownership'])){
        	$retInfo[$key]['retailers']['shop_ownership'] = $imp_data[$retailer['retailers']['id']]['imp']['shop_ownership'];
        }


        foreach ($retailer['ur'] as $retailer_label_key => $value) {
            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
            if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['retailers']['id']]['imp']) ){
                $retInfo[$key]['ur'][$retailer_label_key] = $imp_data[$retailer['retailers']['id']]['imp'][$retailer_label_key_mapped];
            }
        }

        foreach ($retailer['distributors'] as $dist_label_key => $value) {
            $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
            if( array_key_exists($dist_label_key_mapped,$imp_data_dist[$retailer['distributors']['id']]['imp']) ){
                $retInfo[$key]['distributors'][$dist_label_key] = $imp_data_dist[$retailer['distributors']['id']]['imp'][$dist_label_key_mapped];
            }
        }
    }
    /** IMP DATA ADDED : END**/


    $this->set('info', $retInfo);
    if(count($retInfo) > 0){
        $retailerAreaId = $retInfo['0']['retailers']['area_id'];
        $retailerUserId = $retInfo['0']['retailers']['user_id'];
    }
    else{
        $retailerAreaId = "";
        $retailerUserId = "";
    }

    $user_profile = $this->Slaves->query("select * from user_profile where user_id = '$retailerUserId' order by updated desc limit 1");
    if( ! empty($user_profile)){
        if($retInfo[0]['retailers']['verify_flag'] != 1){
            $user_profile['0']['user_profile']['latitude'] = $retInfo[0]['retailers']['latitude'];
            $user_profile['0']['user_profile']['longitude'] = $retInfo[0]['retailers']['longitude'];
        }
        $this->set('user_profile', $user_profile['0']['user_profile']);
    }

    if( ! is_null($retailerAreaId)){
        $stateCityResult = $this->Slaves->query("Select la.name,lc.name,ls.name from locator_city lc join locator_area la on (la.city_id=lc.id) join locator_state ls on (lc.state_id=ls.id) where la.id=$retailerAreaId");
        $this->set('areaCityState', $stateCityResult);
    }

    $dataAll = $this->Slaves->query("
                    SELECT
                            v.shortForm,
                            s.name,
                            r.name,
                            r.id,
                            r.mobile,
                            if(st1.type=".REVERSAL_RETAILER.",st1.source_opening,st.source_opening) source_opening,
                            if(st1.type=".REVERSAL_RETAILER.",st1.source_closing,st.source_closing) source_closing,
                            p.name,
                            va.mobile,
                            va.txn_id,
                            va.amount,
                            va.status,
                            va.api_flag,
                            va.cause,
                            GREATEST(va.timestamp,st1.timestamp) as timestamp,
                            st.timestamp,
                            va.timestamp

                    FROM    vendors_activations va
                    JOIN    retailers r on(va.retailer_id=r.id)
                    JOIN    products p on(p.id=va.product_id)
                    JOIN    vendors v on(v.id=va.vendor_id)
                    JOIN    services s on (p.service_id=s.id)
		LEFT JOIN   shop_transactions as st ON (st.id = va.shop_transaction_id)
		LEFT JOIN   shop_transactions as st1 ON (st1.target_id = va.shop_transaction_id)
                    WHERE
                            (   (va.status='" . TRANS_REVERSE_PENDING . "')
                                OR (va.status != 2 AND va.status !=3)
                                OR (va.status=" . TRANS_REVERSE . " OR va.status=" . TRANS_FAIL . ")
                            )
                    AND     va.retailer_id=$retId
                    AND     va.date BETWEEN '" . $fd . "'  AND     '" . $ft . "'

                    ORDER BY   va.timestamp desc ");

    $dataAllTemp = array();
    foreach($dataAll as $da) {
            $da['st'] = array('source_opening'=>$da[0]['source_opening'], 'source_closing'=>$da[0]['source_closing']);
            $dataAllTemp[] = $da;
    }
    $dataAll = $dataAllTemp;
    $transPanelResult = array();
    $reversalInProcessResults = array();
    $alreadyReversed = array();

    $retcords = $this->Shop->getMemcache("newretailers");

    if(empty($retcords)){

        $getNewRetailer = $this->Slaves->query("select retailers.id from retailers where date(created)>='" . date("Y-m-d", strtotime("-30 day")) . "' and date(created)<='" . date("Y-m-d") . "'");

        $this->Shop->setMemcache("newretailers", $getNewRetailer, 1 * 24 * 60 * 60);
    }

    if( ! empty($retcords)){

        foreach($retcords as $val){

            $retailerData[$val['retailers']['id']] = $val['retailers']['id'];
        }
    }

    $this->set('retailerData', $retailerData);
    $this->set('retId', $retId);


    $other_transactions = $this->Slaves->query("select st.*,(if(wt.cr_db='db',wt.amount - wt.amount_settled,wt.amount_settled-wt.amount)) as earning
				from shop_transactions st
                                left join wallets_transactions as wt ON (wt.shop_transaction_id = st.id and wt.date=st.date)
				where st.source_id = " . $retailerUserId . "
				and st.type in (" . DEBIT_NOTE . "," . CREDIT_NOTE . ",".REFUND."," . VOID_TXN . ",".RENTAL.",".KITCHARGE.",".TXN_CANCEL_REFUND.")
				and st.date between '$fd' and '$ft'
				order by st.timestamp desc");

  $shopid = array();
  $orderid = array();
  $dmt_failed = array();
  foreach($other_transactions as $othertxn){
    $shopid[] = $othertxn['st']['id'];
    if( ($othertxn['st']['user_id'] == 12) &&  ($othertxn['st']['confirm_flag'] != 0) ){
        $dmt_failed[] = $othertxn;
    }
    }
    $shop_id = implode(',',$shopid);
    $order_det_rbl = $this->Ekonew->query("select id,shop_transaction_id from transactions_master where shop_transaction_id IN ($shop_id) ");

//    $order_det_eko = $this->Eko->query("select id,shop_transaction_id from transactions where shop_transaction_id IN ($shop_id) ");

    foreach ($order_det_rbl as $orderdetrbl){
        $orderid[$orderdetrbl['transactions_master']['shop_transaction_id']] = $orderdetrbl['transactions_master']['id'];
    }
//    foreach ($order_det_eko as $orderdeteko){
//        $orderid[$orderdeteko['transactions']['shop_transaction_id']] = $orderdeteko['transactions']['id'];
//    }
    //End
    // For getting creation date of DMT user
//    $dmt_activation_date = array();
//    $activation_date_rbl = $this->Dmt->query("select * from dmt_users where mobile = '$retMobile' and service_flag = '1' and kit_flag = '1'");
//    $activation_date_eko = $this->Eko->query("select * from dmt_users where mobile = '$retMobile' and service_flag = '1' and kit_flag = '1'");
//    if(!empty($activation_date_rbl)){
//    $dmt_activation_date = $activation_date_rbl;
//    }
//    else {
//    $dmt_activation_date = $activation_date_eko;
//    }

    $service_data = $this->Slaves->query("select us.*,sp.plan_name,u.name,s.name from users_services as us"
            . " LEFT JOIN services as s ON (us.service_id = s.id)"
            . "LEFT JOIN service_plans as sp ON (us.service_plan_id = sp.id)"
            . " LEFT JOIN users as u ON (u.id = us.created_by)  "
            . " where us.user_id = $userId order by us.created_on asc");


    $serviceName =  $this->Serviceintegration->getServices();
//    $kitStatus   =  $this->Serviceintegration->getKitStatus();
//    $servcStatus   =  $this->Serviceintegration->getServiceStatus(2);
    //End
    $kitStatus   = Configure::read('kit_status');
    $servcStatus = Configure::read('service_status');

    //For getting mpos data (i.e activation date,activated agent name,service status)
    $mpos_data = $this->Slaves->query("select * from users_services where service_id = '8' and user_id = $userId");
    //For getting dmt data (i.e activation date,activated agent name,service status)
    $dmt_data  = $this->Slaves->query("select * from users_services where service_id = '12' and user_id = $userId");
    //For getting aeps data (i.e activation date,activated agent name,service status)
    $aeps_data = $this->Slaves->query("select * from users_services where service_id = '10' and user_id = $userId");

        $aeps_datas = $aeps_data[0]['users_services']['params'];
        $aepsdevice = json_decode($aeps_datas);
        $aepsdeviceId = $aepsdevice->device_id;
        $aepscsp_id = $aepsdevice->csp_id;


        //For formating the creation date
    $mpos_createdate  = new DateTime($mpos_data[0]['users_services']['created_on']);
    $mpos_created_at  = $mpos_createdate->format('Y-m-d');
    //For activated or deactivated
    if($mpos_data[0]['users_services']['kit_flag'] == 1 && $mpos_data[0]['users_services']['service_flag'] == 1 && $mpos_data[0]['users_services']['params'] != '' ){
        $mpos_service = 'Active';
        //for getting the user device Id
        $mposdevice = $mpos_data[0]['users_services']['params'];
    }
    else{
        $mpos_service = 'Deactive';
    }


    //For activated or deactivated AEPS
    if($aeps_data[0]['users_services']['kit_flag'] == 1 && $aeps_data[0]['users_services']['service_flag'] == 1 && !empty($aepsdeviceId)){
         $userServices['aeps'] = "Yes | " . $aepsdeviceId . " | " . $aepscsp_id;
    }
    else{
        $userServices['aeps'] = "No";
    }


$dmtdevice = $dmt_data[0]['users_services']['params'];
    $dmtdeviceid = json_decode($dmtdevice);
     $dmtdeviceId   = $dmtdeviceid->bc_agent;
    $dmtcreatedby = $dmt_data[0]['users_services']['created_by'];
    //for getting the username on the basis of user_id
    $createdby = $mpos_data[0]['users_services']['created_by'];
//decoding the params which were stored in database
     $mposdeviceid = json_decode($mposdevice);
//assigning the device_id to varable
     if(!empty($mposdeviceid)){
      $mposdeviceId = ' | '.$mposdeviceid->device_id;
     }
     else {
         $mposdeviceId = '';
     }


$settings = $this->Smartpay->query("Select * from users where user_id = '$userId'");

$settings['settings'] = $settings[0]['users'];

//Converting userid to username
    $mpos_username = $this->Slaves->query("select name from users where id = '$createdby'");
    $dmt_username = $this->Slaves->query("select name from users where id = '$dmtcreatedby'");
    $mposrental_data = $this->Slaves->query("SELECT * FROM shop_transactions WHERE TYPE =  '20' AND source_id = '$userId' AND user_id =  '8' order by timestamp desc");
    $this->set('dmt_username',$dmt_username);
    $this->set('userServices',$userServices);
    $this->set('settings', $settings['settings']);
    $this->set('reports', $reports['transactions']);
    $this->set('mpos_username',$mpos_username);
    $this->set('mpos_status',$mpos_status);
    $this->set('mpos_data',$mpos_data);
    $this->set('mposrental_data',$mposrental_data);
    $this->set('mpos_created_at',$mpos_created_at);
    $this->set('mpos_service',$mpos_service);
    $this->set('mposdeviceId',$mposdeviceId);
    $this->set('dmt_date',$dmt_activation_date);
    $this->set('order_id',$orderid);
    $this->set('other_transactions', $other_transactions);
    $this->set('dmt_data',$dmt_data);
    $this->set('dmt_failed',$dmt_failed);
    $this->set('dmtdeviceId',$dmtdeviceId);
    $this->set('service_data',$service_data);
    $this->set('serviceName',$serviceName);
    $this->set('kitStatus',$kitStatus);
    $this->set('servcStatus',$servcStatus);

    if(empty($dataAll)) $dataAll = array();
    foreach($dataAll as $key=>$data){
        if(($data['va']['status'] != 2 and $data['va']['status'] != 3)){
            array_push($transPanelResult, $data);
        }
        if($data['va']['status'] == TRANS_REVERSE_PENDING){
            array_push($reversalInProcessResults, $data);
        }
        if(($data['va']['status'] == TRANS_REVERSE || $data['va']['status'] == TRANS_FAIL)){
            array_push($alreadyReversed, $data);
        }
    }

    $reversalInProcessResults = Set::sort($reversalInProcessResults, '{n}.va.id', 'desc');
    // for transactoins by retailer
    $this->set('transRecords', $transPanelResult);

    $this->set('reversalInProcess', $reversalInProcessResults);
    // $reversalInProcessRefCode=$reversalInProcessResults['0']['va']['ref_code'];

    // for transactions that are reversed
    $this->set('alreadyReversed', $alreadyReversed);
    $this->set('transRecords', $transPanelResult);

    // for comments
    $commentsResult = $this->Slaves->query("SELECT c.ref_code,c.comments,u1.name,u1.mobile,c.created
		from comments c left join users u on (u.id=c.users_id) join users u1 on(c.mobile=u1.mobile)
		where c.retailers_id=$retId order by c.created  desc");

    if(empty($commentsResult)) $commentsResult = array();
    $this->set('comment', $commentsResult);
    $this->set('mob', $retMobile);

    // for sms sent by retailers
    $smsResult = $this->Slaves->query("select vn.virtual_num,vn.message,vn.timestamp , vn.description from virtual_number vn where vn.mobile='$retMobile' and vn.date between '" . $fd . "' and '" . $ft . "' order by vn.timestamp desc ");
    if(empty($smsResult)) $smsResult = array();
    $this->set('smsResult', $smsResult);

    // for ussd sent by retailers
    $ussdResult = $this->Slaves->query("select ussd_logs.request,ussd_logs.time,ussd_logs.date ,ussd_logs.extra,ussd_logs.sent_xml from ussds as ussd_logs where ussd_logs.request != '*6699#' AND ussd_logs.mobile='$retMobile' AND ussd_logs.request is not null AND ussd_logs.sent_xml is not null AND ussd_logs.date between '" . $fd . "' and '" . $ft . "' order by ussd_logs.date desc,ussd_logs.time desc");
    if(empty($ussdResult)) $ussdResult = array();
    $this->set('ussdResult', $ussdResult);

    // for top-up requests by retailers
    /*
     * $topUpResult=$this->User->query("select tr.amount,tr.type,tr.created from topup_request tr where tr.user_id=$retId and Date(tr.created) between '".$fd."' and '".$ft."' order by tr.created desc");
     * $this->set('topUpResult',$topUpResult);
     */

    // for amount transferred to retailer
    $amountTransferred = $this->Slaves->query("(select s.name s_name,d.name,d.company,st.amount,st.timestamp,st.source_id,st.target_id,sum(sst.collection_amount) as colAmt from shop_transactions st join distributors d on(d.id=st.source_id) join salesman_transactions sst on(st.id=sst.shop_tran_id) join salesmen s on (s.id=sst.salesman) where st.type='" . DIST_RETL_BALANCE_TRANSFER . "' AND st.confirm_flag != 1 AND st.note is not null AND st.target_id=$retId and st.date between '" . $fd . "' and '" . $ft . "' group by sst.shop_tran_id order by st.timestamp desc) UNION (SELECT s.name s_name,'','',st.amount,st.timestamp,'','','' FROM shop_transactions st JOIN salesmen s ON (st.source_id = s.id) WHERE st.type = " . SLMN_RETL_BALANCE_TRANSFER . " AND st.target_id = $retId AND st.confirm_flag = 0 AND st.date between '" . $fd . "' and '" . $ft . "' ORDER BY st.timestamp DESC)");
    if(empty($amountTransferred)) $amountTransferred = array();
    $this->set('amountTransferred', $amountTransferred);
    $this->set('pageType', $pageType);
    if($pageType == 'csv'){

        App::import('Helper', 'csv');
        $this->layout = null;
        $this->autoLayout = false;
        $csv = new CsvHelper();
        // $line = array("Txn Date","Txn Id","Signal7 T_ID","Number","Operator","Amount","Opening","Closing","Earning","Reversal Date","Description","Status");
        $line = array("S.No.", "Tran Id", "Recharge", "Vendor", "Cust Mob", "Operator", "Amount", "Status", "Reason", "Via", "Opening", "Closing", "Earning", "Timestamp");
        $csv->addRow($line);
        $i = 1;
        foreach($transPanelResult as $data){

            $tot = $tot + $data['va']['amount'];
            $earn = ($data['va']['amount'] - ($data['oc']['opening'] - $data['oc']['closing']));
            $tot_earn = $tot_earn + $earn;

            $reversalStats = '';
            if($data['va']['status'] == '0'){
                $reversalStats = 'In Process';
            }
            else if($data['va']['status'] == '1'){
                $reversalStats = 'Successful';
            }
            else if($data['va']['status'] == '2'){
                $reversalStats = 'Failed';
            }
            else if($data['va']['status'] == '3'){
                $reversalStats = 'Reversed';
            }
            else if($data['va']['status'] == '4'){
                $reversalStats = 'Complaint In Process';
            }
            else if($data['va']['status'] == '5'){
                $reversalStats = 'Complaint declined';
            }
            // echo "<td>".$reversalStats."&nbsp;</td>";
            $via = "";
            if($data['va']['api_flag'] == 0){
                $via = 'SMS';
            }
            else if($data['va']['api_flag'] == 1){
                $via = 'API';
            }
            else if($data['va']['api_flag'] == 2){
                $via = 'USSD';
            }
            else if($data['va']['api_flag'] == 3){
                $via = 'ANDROID';
            }
            else if($data['va']['api_flag'] == 5){
                $via = 'JAVA';
            }
            else if($data['va']['api_flag'] == 4){
                $via = 'PARTNER';
            }
            // echo "<td>$via</td>";
            // echo "<td>".$data['oc']['opening']."&nbsp;</td>";
            // echo "<td>".$data['oc']['closing']."&nbsp;</td>";
            // echo "<td>".round($earn,2)."&nbsp;</td>";
            // echo "<td>".$data['0']['timestamp']."&nbsp;</td>";
            // echo "</tr>";
            $temp = array($i, $data['va']['txn_id'], $data['s']['name'], $data['v']['shortForm'], $data['va']['mobile'], $data['p']['name'], $data['va']['amount'], $reversalStats, $data['va']['cause'], $via, $data['st']['source_opening'], $data['st']['source_closing'], round($earn, 2),
                    $data['0']['timestamp']);
            $csv->addRow($temp);
            $i ++ ;
        }

        // Retailers "REVERSED" Transactions
        $count = 1;
        $csv->addRow(array('Retailers "REVERSED" Transactions'));
        $csv->addRow(array("S.No.", "Tran Id", "Recharge", "Vendor", "Cust Mob", "Operator", "Amount", "Status", "Reason", "Via", "Opening", "Closing", "Timestamp"));
        foreach($alreadyReversed as $d){
            $ps = '';
            if($d['va']['status'] == '0') $ps = 'In Process';
            else if($d['va']['status'] == '1') $ps = 'Successful';
            else if($d['va']['status'] == '2') $ps = 'Failed';
            else if($d['va']['status'] == '3') $ps = 'Reversed';
            else if($d['va']['status'] == '4') $ps = 'Reversal In Process';
            else if($d['va']['status'] == '5') $ps = 'Reversal declined';

            if($d['va']['api_flag'] == 0){
                $via = 'SMS';
            }
            else if($d['va']['api_flag'] == 1){
                $via = 'API';
            }
            else if($d['va']['api_flag'] == 2){
                $via = 'USSD';
            }
            else if($data['va']['api_flag'] == 3){
                $via = 'ANDROID';
            }
            else if($data['va']['api_flag'] == 5){
                $via = 'JAVA';
            }
            else if($data['va']['api_flag'] == 4){
                $via = 'PARTNER';
            }

            $temp = array($count, $d['va']['txn_id'], $d['s']['name'], $d['v']['shortForm'], $d['va']['mobile'], $d['p']['name'], $d['va']['amount'], $ps, $d['va']['cause'], $via, $d['st']['source_opening'], $d['st']['source_closing'], $d['0']['timestamp']);
            $csv->addRow($temp);
            $count ++ ;
        }
        // Smartpay Transactions
        $j = 1;
        $csv->addRow(array('SmartPay Transaction Information'));
        $csv->addRow(array("S.No", "Pay1 Txn ID", "Description", "Customer Mobile", "Amount", "Txn Status", "Settlement Status", "Opening", "Closing", "Earning", "Time/Date", "Settlement Timestamp"));

        foreach($reports['transactions'] as $report){
            if(in_array($report['txn_status'], array('P', 'S'))){
                $card_no = explode('-', $report['card_no']);
                $card_num = end($card_no);

                if($report['service_id'] == 8){
                    if($report['product_id'] == $service_type[8]['MPOS Withdrawal : Non VISA']){
                        $description = "CW - DD : Non VISA " . $card_num;
                    } else if( $report['product_id'] == $service_type[8]['MPOS Withdrawal : VISA'] ){
                        $description = "CW - DD : VISA " . $card_num;
                    }
                    elseif( ($report['product_id'] == $service_type[8]['Sale - CC : EMI']) || ($report['product_id'] == $service_type[8]['Sale - CC']) || ($report['product_id'] == $service_type[8]['Sale - DC'])){
                        $cardtype = '--';
                        if( strtolower($report['payment_card_type']) == "debit" ){
                            $cardtype = "DC";
                        } else if( strtolower($report['payment_card_type']) == "credit" ){

                            $cardtype = "CC";
                            if( $report['product_id'] == $service_type[8]['Sale - CC : EMI'] ){
                                $cardtype = "CC : EMI";
                            }
                        }
                        $description="Sale - ".$cardtype." : ".$card_num;
                    }
                }
                elseif($report['service_id'] == 9){
                    $description = "UPI - " . $report['vpa'];
                }
                elseif($report['service_id'] == 10){
                    // $description = "Aadhar - " . $card_num;
                    $description="AEPS";
                    if(in_array($report['product_id'],$service_type[$report['service_id']])){
                        $description = array_search($report['product_id'],$service_type[$report['service_id']]);
                    }
                }

                $txn_status = $report['txn_status'] == "P" ? "Pending" : (($report['txn_status'] == "S") ? "Success" : "Failed - ");
                $settlement_flag = $report['settlement_flag'] == 0 ? "W - " : "B - ";
                $status = $report['settlement_status'] == "P" ? "Pending" : (($report['settlement_status'] == "S") ? "Settled" : "Failed");
                $settlement_status = $settlement_flag . $status;
                $opening = (strtolower($report['wallet_details']['type']) == "cr") ? ($report['wallet_details']['closing'] - $report['wallet_details']['amt_settled']) : ($report['wallet_details']['closing'] + $report['wallet_details']['amt_settled']);
                $earning = $report['wallet_details']['amt_settled'] - $report['txn_amount'];

                $temp = array($j, $report['txn_id'], $description, $report['mobile_no'], $report['txn_amount'], $txn_status . " " . $report['status_description'], $settlement_status, $opening, $report['wallet_details']['closing'], $earning, $report['txn_time'], $report['settled_at']);

                $csv->addRow($temp);
                $j ++ ;
            }
        }
        // Smartpay Reversed Transactions
        $k = 1;
        $csv->addRow(array('SmartPay Reversed Transaction'));
        $csv->addRow(array("S.No", "Pay1 Txn ID", "Description", "Customer Mobile", "Amount", "Txn Status", "Settlement Status", "Opening", "Closing", "Earning", "Time/Date", "Settlement Timestamp"));

        foreach($reports['transactions'] as $report){
            if( ! in_array($report['txn_status'], array('P', 'S'))){
                $card_no = explode('-', $report['card_no']);
                $card_num = end($card_no);

                if($report['service_id'] == 8){
                    if($report['product_id'] == $service_type[8]['MPOS Withdrawal']){
                        $description = "CW - DD : " . $card_num;
                    }
                    elseif(($report['product_id'] == $service_type[8]['Sale - CC']) || ($report['product_id'] == $service_type[8]['Sale - DC'])){
                        $cardtype = '--';
                        if( strtolower($report['payment_card_type']) == "debit" ){
                            $cardtype = "DC";
                        } else if( strtolower($report['payment_card_type']) == "credit" ){
                            $cardtype = "CC";
                        }
                        $description="Sale - ".$cardtype." : ".$card_num;
                    }
                }
                elseif($report['service_id'] == 9){
                    $description = "UPI - " . $report['vpa'];
                }
                elseif($report['service_id'] == 10){
                    // $description = "Aadhar - " . $card_num;
                    $description="AEPS";
                    if(in_array($report['product_id'],$service_type[$report['service_id']])){
                        $description = array_search($report['product_id'],$service_type[$report['service_id']]);
                    }
                }

                $txn_status = $report['txn_status'] == "P" ? "Pending" : (($report['txn_status'] == "S") ? "Success" : "Failed - ");
                $settlement_flag = $report['settlement_flag'] == 0 ? "W - " : "B - ";
                $status = $report['settlement_status'] == "P" ? "Pending" : (($report['settlement_status'] == "S") ? "Settled" : "Failed");
                $settlement_status = $settlement_flag . $status;
                $opening = (strtolower($report['wallet_details']['type']) == "cr") ? ($report['wallet_details']['closing'] - $report['wallet_details']['amt_settled']) : ($report['wallet_details']['closing'] + $report['wallet_details']['amt_settled']);
                $earning = $report['wallet_details']['amt_settled'] - $report['txn_amount'];

                $temp = array($j, $report['txn_id'], $description, $report['mobile_no'], $report['txn_amount'], $txn_status . " " . $report['status_description'], $settlement_status, $opening, $report['wallet_details']['closing'], $earning, $report['txn_time'], $report['settled_at']);

                $csv->addRow($temp);
                $k ++ ;
            }
        }
        echo $csv->render('RetailerTransactions_' . $fd . '_' . $ft . '.csv');
    }
    else{
        $from = date('Y-m-d', strtotime($from));
        $to = date('Y-m-d', strtotime($to));

        $data = $this->Platform->accountHistory($retailerIdResult['0']['retailers']['user_id'], $from, $to);

        $this->set('txns', $data['data']['All']);
        $this->render("ret_info");
    }

    // $this->Autorender = false;
}

function ussdLogs($retMobile, $from = null, $to = null){
    $this->set('objShop', $this->Shop);
    if( ! isset($from) ||  ! isset($to)){
        $from = date('d-m-Y', strtotime('-5 days'));
        $to = date('d-m-Y');
    }

    $fdarr = explode("-", $from);
    $tdarr = explode("-", $to);

    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    $this->set('from', $from);
    $this->set('to', $to);
    $this->set('mob', $retMobile);
    $logs = $this->Slaves->query("select ul.* FROM `ussds` ul where mobile='" . $retMobile . "' and level = 1 and `date`>=  '$fd' and `date`<=  '$ft' order by timestamp desc");
    $this->set('logs', $logs);
    $this->render("retailer_ussd_log");
}

function appNotificationLogs($retMobile, $from = null, $to = null){
    $this->set('objShop', $this->Shop);
    if( ! isset($from) ||  ! isset($to)){
        $from = date('d-m-Y', strtotime('-5 days'));
        $to = date('d-m-Y');
    }

    $fdarr = explode("-", $from);
    $tdarr = explode("-", $to);

    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    $this->set('from', $from);
    $this->set('to', $to);
    $this->set('mob', $retMobile);
    // $logs=$this->User->query("select ul.* FROM `ussds` ul where mobile='".$retMobile."' and level = 1 and `date`>= '$fd' and `date`<= '$ft' order by timestamp desc" );
    $logs = $this->Slaves->query("select id , mobile , user_key , msg , notify_type , user_type , response ,created ,`date` FROM `notificationlog` where mobile='" . $retMobile . "' and `date`>=  '$fd' and `date`<=  '$ft' order by created desc");
    // echo "select id , mobile , user_key , msg , notify_type , user_type , response ,created , modified FROM `notificationlog` where mobile='".$retMobile."' and `date`>= '$fd' and `date`<= '$ft' order by created desc";
    $output = array();
    foreach($logs as $log){

        if( ! empty($log["notificationlog"]["msg"])){
            try{
                $msgJ = json_decode($log["notificationlog"]["msg"], true);
                $log["notificationlog"]["msg"] = empty($msgJ["msg"]) ? $log["notificationlog"]["msg"] : $msgJ["msg"];
            }
            catch(Exception $e){
                // $log["notificationlog"]["msg"] = $log["notificationlog"]["msg"];
            }
        }
        $output[] = $log;
    }
    $this->set('logs', $output);
    $this->render("retailer_app_noti_log");
}

function editRetailer($retMobile, $chk = null){
    if( ! is_null($chk)){
        $this->Retailer->query("update retailers
					set email='" . addslashes($_REQUEST['retailerEmail']) . "',
					alternate_number='" . $_REQUEST['alternate'] . "',
					block_flag=" . $_REQUEST['retailerBlockFlag'] . ",
					modified = '" . date('Y-m-d H:i:s') . "'
                    where mobile='$retMobile'");

        /** IMP DATA ADDED : START**/
        $imp_update_data = array(
            'email' => addslashes($_REQUEST['retailerEmail']),
            'alternate_number' => $_REQUEST['alternate'],
            'name' => addslashes($_REQUEST['retailerName']),
            'business_nature'=>$_REQUEST['retailerShopType'],
        	'annual_turnover' => $_REQUEST['retailerAnnualTurnover'],
        	'shop_area_type' => $_REQUEST['retailerShopAreaType'],
        	'shop_ownership' => $_REQUEST['retailerShopOwnership']

            // 'shop_name' => addslashes($_REQUEST['retailerShopName']),
            // 'address' => addslashes($_REQUEST['retailerAddress'])
        );
        $response = $this->Shop->updateUserLabelData($retMobile,$imp_update_data,$this->Session->read('Auth.User.id'),1);
        /** IMP DATA ADDED : END**/

        $this->Retailer->query("update unverified_retailers
					set name='" . addslashes($_REQUEST['retailerName']) . "',
					shop_name='" . addslashes($_REQUEST['retailerShopName']) . "',
					area='" . addslashes($_REQUEST['retailerArea']) . "',
					address='" . addslashes($_REQUEST['retailerAddress']) . "',
					pin_code='" . $_REQUEST['retailerPin'] . "',
					modified = '" . date('Y-m-d H:i:s') . "'
					where retailer_id = " . $_REQUEST['rId']);
    }

    $retInfo = $this->Retailer->query("select retailers.*,distributors.id,distributors.company, ur.name, ur.shop_name, ur.area,
					ur.address, ur.pin_code
				from  retailers
				left join unverified_retailers ur on ur.retailer_id = retailers.id
				left join users on (retailers.user_id = users.id)
				left join  distributors on (retailers.parent_id=distributors.id)
				WHERE retailers.mobile = '$retMobile'");
    foreach($retInfo[0]['ur'] as $key=>$row){
        if($key != 'id') $retInfo[0]['retailers'][$key] = $retInfo[0]['ur'][$key];
    }

    /** IMP DATA ADDED : START**/
    $ret_ids = array_map(function($element){
        return $element['retailers']['id'];
    },$retInfo);
    $dist_ids = array_map(function($element){
        return $element['distributors']['id'];
    },$retInfo);

    $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
    $imp_data_dist = $this->Shop->getUserLabelData($dist_ids,2,3);

    $retailer_imp_label_map = array(
        'pan_number' => 'pan_no',
        'shopname' => 'shop_est_name',
        'alternate_number' => 'alternate_mobile_no',
        'email' => 'email_id',
        'shop_structure' => 'shop_ownership',
        'shop_type' => 'business_nature'
    );
    $dist_imp_label_map = array(
            'pan_number' => 'pan_no',
            'company' => 'shop_est_name',
            'alternate_number' => 'alternate_mobile_no',
            'email' => 'email_id'
        );
    foreach ($retInfo as $key => $retailer) {
        foreach ($retailer['retailers'] as $retailer_label_key => $value) {
            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
            if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['retailers']['id']]['imp']) ){
                $retInfo[$key]['retailers'][$retailer_label_key] = $imp_data[$retailer['retailers']['id']]['imp'][$retailer_label_key_mapped];
            }
        }
        if(isset($imp_data[$retailer['retailers']['id']]['imp']['annual_turnover'])){
        	$retInfo[$key]['retailers']['annual_turnover'] = $imp_data[$retailer['retailers']['id']]['imp']['annual_turnover'];
        }
        if(isset($imp_data[$retailer['retailers']['id']]['imp']['shop_area_type'])){
        	$retInfo[$key]['retailers']['shop_area_type'] = $imp_data[$retailer['retailers']['id']]['imp']['shop_area_type'];
        }
        if(isset($imp_data[$retailer['retailers']['id']]['imp']['shop_ownership'])){
        	$retInfo[$key]['retailers']['shop_ownership'] = $imp_data[$retailer['retailers']['id']]['imp']['shop_ownership'];
        }
        foreach ($retailer['ur'] as $retailer_label_key => $value) {
            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
            if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['retailers']['id']]['imp']) ){
                $retInfo[$key]['ur'][$retailer_label_key] = $imp_data[$retailer['retailers']['id']]['imp'][$retailer_label_key_mapped];
            }
        }

        foreach ($retailer['distributors'] as $dist_label_key => $value) {
            $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
            if( array_key_exists($dist_label_key_mapped,$imp_data_dist[$retailer['distributors']['id']]['imp']) ){
                $retInfo[$key]['distributors'][$dist_label_key] = $imp_data_dist[$retailer['distributors']['id']]['imp'][$dist_label_key_mapped];
            }
        }
    }
    /** IMP DATA ADDED : END**/

    $this->set('info', $retInfo);
    $this->set('retMobile', $retMobile);
    $this->set('shop_types', $this->Shop->business_natureTypes());
    $this->set('turnover_types',$this->Shop->annual_turnoverTypes());
    $this->set('shop_area_types',$this->Shop->shop_area_typeTypes());
    $this->set('shop_ownership_types',$this->Shop->shop_ownershipTypes());

    if( ! is_null($chk)){
        $this->redirect('/panels/retInfo/' . $retMobile);
    }
}

/*
 * function retailerTransaction($retId)
 * {
 * $retailerId=$retId;
 * //$retailerTransactionResult=$this->Retailer->query("select st.id from shop_transactions st ,vendors_activations va where st.ref1_id=20 and st.id=va.shop_transaction_id");
 * //foreach ($transId as $retailerTransactionResult)
 * //{
 * $transPanelResult=$this->Retailer->query("select va.shop_transaction_id,va.txn_id,services.name,vendors.company,va.mobile,va.amount,vm.internal_error_code,vm.response,vm.status,va.timestamp
 * from vendors_activations va join shop_transactions st on(st.id=va.shop_transaction_id) join vendors_messages vm on (va.txn_id=vm.shop_tran_id) join services on (vm.service_id=services.id) join vendors on(vm.service_vendor_id=vendors.id)
 * where st.ref1_id=$retId");
 * $this->set('transRecords',$transPanelResults);
 * }
 */
function transaction($trans = null, $par = null){
    if( ! empty($trans)){

        $qStr = 'va.txn_id';
        if(isset($par) && $par == 1){
            $qStr = 'va.vendor_refid';
        }
        else if(isset($par) && $par == 2){ // for signal7 api transactions
            $partnerLog = $this->Slaves->query("SELECT  *
                                                                    FROM partners_log pl
                                                                    LEFT JOIN partners p ON pl.partner_id = p.id
                                                                    LEFT JOIN retailers r ON p.retailer_id = r.id
                                                                    WHERE pl.partner_req_id = '$trans'");

            $this->set('ptran', $trans);
            $trans = $partnerLog[0]['pl']['vendor_actv_id'];
            $qStr = 'va.txn_id';

            if($partnerLog != null && count($partnerLog > 0)){ // $trans == 0 &&
                                                                // render partners log here
                $this->set('partnerLog', $partnerLog);
                $this->set('renderTesting', true);
            }
        }

        if(empty($trans)) return;
        // $taggingResult=$this->Retailer->query("select distinct t.name from taggings t join user_taggings tu on(t.id=tu.tagging_id) where tu.transaction_id=$trans order by tu.id desc");
        // $tags=$taggingResult['0']['t']['name'];
        // $this->set('tags',$taggingResult);

        $transPanelResult_temp = $this->Slaves->query("SELECT a.*, complaints.id, complaints.turnaround_time, bc.id FROM (SELECT services.id services_id, va.*, r.id r_id, r.name r_name, r.mobile r_mobile, r.shopname, services.name services_name,
                        vendors.id vendors_id, vendors.company, vendors.shortForm, p.name p_name, users.name users_name, users.id users_id,
                        sum(c.resolve_flag) as resolve_flag, count(c.resolve_flag) as count_resolve_flag, max(c.turnaround_time) as tat, c.id c_id, c.takenby
			FROM vendors_activations va
			JOIN products p ON ( va.product_id = p.id )
			JOIN services ON ( p.service_id = services.id )
			LEFT JOIN vendors ON ( va.vendor_id = vendors.id )
			JOIN retailers r ON ( va.retailer_id = r.id )
			LEFT JOIN users ON ( users.id = va.cc_userid )
			LEFT JOIN complaints c ON (c.vendor_activation_id = va.id)
			WHERE " . $qStr . " =  '" . $trans . "'
			group by va.id order by va.id desc) a
                        LEFT JOIN complaints ON (complaints.vendor_activation_id = a.id)
                        LEFT JOIN bbps_complaints bc ON (bc.vendor_activation_id = a.id) ORDER BY complaints.id DESC LIMIT 1");


            /** IMP DATA ADDED : START**/
            $ret_mobiles = array_map(function($element){
                return $element['a']['r_mobile'];
            },$transPanelResult_temp);

            $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);

            $retailer_imp_label_map = array(
                'pan_number' => 'pan_no',
                'shopname' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id',
                'shop_structure' => 'shop_ownership',
                'shop_type' => 'business_nature',
                'r_name' => 'name'
            );
            foreach ($transPanelResult_temp as $key => $retailer) {
                foreach ($retailer['a'] as $retailer_label_key => $value) {
                    $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                    if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['a']['r_mobile']]['imp']) ){
                        $transPanelResult_temp[$key]['a'][$retailer_label_key] = $imp_data[$retailer['a']['r_mobile']]['imp'][$retailer_label_key_mapped];
                    }
                }
            }
            /** IMP DATA ADDED : END**/


        $transPanelResult[0]['services'] = array('id'=>$transPanelResult_temp[0]['a']['services_id'], 'name'=>$transPanelResult_temp[0]['a']['services_name']);
        $transPanelResult[0]['va'] = array('id'=>$transPanelResult_temp[0]['a']['id'], 'vendor_id'=>$transPanelResult_temp[0]['a']['vendor_id'], 'product_id'=>$transPanelResult_temp[0]['a']['product_id'], 'mobile'=>$transPanelResult_temp[0]['a']['mobile'],
                'param'=>$transPanelResult_temp[0]['a']['param'], 'amount'=>$transPanelResult_temp[0]['a']['amount'], 'discount_commission'=>$transPanelResult_temp[0]['a']['discount_commission'], 'txn_id'=>$transPanelResult_temp[0]['a']['txn_id'],
                'vendor_refid'=>$transPanelResult_temp[0]['a']['vendor_refid'], 'operator_id'=>$transPanelResult_temp[0]['a']['operator_id'], 'shop_transaction_id'=>$transPanelResult_temp[0]['a']['shop_transaction_id'], 'retailer_id'=>$transPanelResult_temp[0]['a']['retailer_id'],
                'invoice_id'=>$transPanelResult_temp[0]['a']['invoice_id'], 'status'=>$transPanelResult_temp[0]['a']['status'], 'prevStatus'=>$transPanelResult_temp[0]['a']['prevStatus'], 'api_flag'=>$transPanelResult_temp[0]['a']['api_flag'], 'cause'=>$transPanelResult_temp[0]['a']['cause'],
                'code'=>$transPanelResult_temp[0]['a']['code'], 'timestamp'=>$transPanelResult_temp[0]['a']['timestamp'], 'date'=>$transPanelResult_temp[0]['a']['date'], 'extra'=>$transPanelResult_temp[0]['a']['extra'], 'complaintNo'=>$transPanelResult_temp[0]['a']['complaintNo']);
        $transPanelResult[0]['r'] = array('id'=>$transPanelResult_temp[0]['a']['r_id'], 'name'=>$transPanelResult_temp[0]['a']['r_name'], 'mobile'=>$transPanelResult_temp[0]['a']['r_mobile'], 'shopname'=>$transPanelResult_temp[0]['a']['shopname']);
        $transPanelResult[0]['vendors'] = array('id'=>$transPanelResult_temp[0]['a']['vendors_id'], 'company'=>$transPanelResult_temp[0]['a']['company'], 'shortForm'=>$transPanelResult_temp[0]['a']['shortForm']);
        $transPanelResult[0]['p'] = array('name'=>$transPanelResult_temp[0]['a']['p_name']);
        $transPanelResult[0]['users'] = array('name'=>$transPanelResult_temp[0]['a']['users_name'], 'id'=>$transPanelResult_temp[0]['a']['users_id']);
        $transPanelResult[0][0] = array('resolve_flag'=>$transPanelResult_temp[0]['a']['resolve_flag'], 'count_resolve_flag'=>$transPanelResult_temp[0]['a']['count_resolve_flag'], 'tat'=>$transPanelResult_temp[0]['complaints']['turnaround_time']);
        $transPanelResult[0]['c'] = array('id'=>$transPanelResult_temp[0]['a']['c_id'], 'takenby'=>$transPanelResult_temp[0]['a']['takenby']);
        $transPanelResult[0]['bc'] = array('id'=>$transPanelResult_temp[0]['bc']['id']);

        $retcords = $this->Shop->getMemcache("newretailers");

        if(empty($retcords)){

            $getNewRetailer = $this->Slaves->query("select retailers.id from retailers where date(created)>='" . date("Y-m-d", strtotime("-30 day")) . "' and date(created)<='" . date("Y-m-d") . "'");

            $this->Shop->setMemcache("newretailers", $getNewRetailer, 1 * 24 * 60 * 60);
        }

        if( ! empty($retcords)){

            foreach($retcords as $val){

                $retailerData[$val['retailers']['id']] = $val['retailers']['id'];
            }
        }

        $this->set('retailerData', $retailerData);

        if(empty($transPanelResult)) return;
        $confirm_flag = $this->Slaves->query("SELECT confirm_flag FROM shop_transactions WHERE id = " . $transPanelResult['0']['va']['shop_transaction_id']);

        $this->set('confirm_flag', $confirm_flag['0']['shop_transactions']['confirm_flag']);

        $this->set('individualTransaction', $transPanelResult);

        $trans = $transPanelResult['0']['va']['txn_id'];
        $this->set('tran', $trans);
        $this->set('tran1', $transPanelResult['0']['va']['vendor_refid']);

        // for another table for response from OSS/PPI
        $detailedTransResult = $this->Slaves->query("SELECT vm.*,vendors.shortForm FROM vendors_messages vm INNER JOIN vendors ON ( vendors.id = vm.service_vendor_id )  WHERE vm.va_tran_id='" . $trans . "'  order by vm.timestamp desc,vm.id desc");
        $this->set('detailedTransaction', $detailedTransResult);

        // for comments
        $commentsResult = $this->Slaves->query("SELECT c.comments,u.name,u.mobile,c.created,c.ref_code,t.name
					from comments c
					left join users u on(c.mobile=u.mobile)
					left join taggings t on t.id = c.tag_id
					where c.ref_code='" . $trans . "'  order by c.created  desc ");
        $this->set('commentsResult', $commentsResult);
    }
}

function updateCallComplain(){
    $this->autoRender = false;

    $takenby = $this->params['form']['takenby'];
    $complaint_id = $this->params['form']['complaint_id'];

    $this->Retailer->query("Update complaints SET takenby = $takenby WHERE id = $complaint_id");

    echo $takenby == 0 ? $_SESSION['Auth']['User']['id'] : 0;
}

function openTransaction(){
    $id = $_REQUEST['id'];
    $shopid = $_REQUEST['shopid'];
    $turnaround_time = $_REQUEST['turnaround_time'];
    $data1 = $this->Retailer->query("SELECT status,txn_id FROM vendors_activations WHERE id = $id AND shop_transaction_id = $shopid");
    $data2 = $this->Retailer->query("SELECT confirm_flag FROM shop_transactions WHERE id = $shopid");

    if($data1['0']['vendors_activations']['status'] == TRANS_REVERSE_DECLINE && $data2['0']['shop_transactions']['confirm_flag'] == 1){
        $this->Retailer->query("UPDATE vendors_activations SET status = " . TRANS_REVERSE_PENDING . " WHERE id = $id");
        $tags = $this->Retailer->query("select t.name
					from comments c
					inner join taggings t on t.id = c.id
					where t.type = 'Online Complaint'
					and c.ref_code = '" . $data1['0']['vendors_activations']['txn_id'] . "'
					order by c.id desc");

        /*App::import('Controller', 'Apis');
        $ApisController = new ApisController();
        $ApisController->constructClasses();*/
        $turnaround_duration = $this->Api->getTurnaroundTime($id, $tags[0]['t']['name'], $turnaround_time);

        if(isset($turnaround_duration)){
            $pre_adjusted_turnaround_time = time() + ($turnaround_duration * 60 * 60);
            $pre_adjusted_date = new DateTime(date("Y-m-d H:i:s", $pre_adjusted_turnaround_time));
            $date = new DateTime(date("Y-m-d H:i:s", $pre_adjusted_turnaround_time));
            if(date("H", $pre_adjusted_turnaround_time) < 8){
                $date->setTime(10, 0, 0);
                $turnaround_time = $date->format("Y-m-d H:i:s");
            }
            else if(date("H", $pre_adjusted_turnaround_time) == 23){
                $date->add(new DateInterval('P1D'));
                $date->setTime(10, 0, 0);
                $turnaround_time = $date->format("Y-m-d H:i:s");
            }
            else{
                $turnaround_time = date('Y-m-d H:i:s', $pre_adjusted_turnaround_time);
            }
        }

        $this->Retailer->query("INSERT INTO complaints
					(vendor_activation_id,takenby,in_date,in_time,turnaround_time)
					VALUES (" . $id . ",'" . $_SESSION['Auth']['User']['id'] . "','" . date('Y-m-d') . "','" . date('H:i:s') . "', '" . $turnaround_time . "')");

        // $this->Retailer->query("UPDATE complaints SET in_date='".date('Y-m-d')."',in_time='".date('H:i:s')."',resolve_flag = 0 WHERE vendor_activation_id = $id");

        echo "success";
        $this->General->sendMails("Transaction reopened", "Transaction id: " . $data1['0']['vendors_activations']['ref_code'], array('chirutha@pay1.in'), 'mail');
    }
    else{
        echo "failure";
    }
    $this->autoRender = false;
}

function prodVendor($pid = null, $suppliers = null){
    if(isset($pid)){
        $vcId = $_REQUEST['vendor' . $pid];
        if(isset($vcId)){
            $this->Retailer->query("update vendors_commissions set active='0' where product_id = '" . $pid . "'");
            $this->Retailer->query("update vendors_commissions set active='1' where id = '" . $vcId . "'");
            $this->Shop->setProdInfo($pid);
        }
        $this->Retailer->query("UPDATE inv_supplier_operator SET priority_flag = '0' WHERE operator_id = '" . $pid . "'");
        if(isset($suppliers) and $suppliers != NULL){
            $this->Retailer->query("UPDATE inv_supplier_operator SET priority_flag = '1' WHERE operator_id = '" . $pid . "' AND supplier_id IN (" . $suppliers . ")");
        }
        $this->redirect('prodVendor');
    }
    $this->set('prods', $this->Slaves->query("SELECT products.id, products.name, products.oprDown,products.blocked_slabs,products.auto_check,products.service_id,slabs_products.percent FROM products,slabs_products WHERE products.id = slabs_products.product_id AND slab_id = 13 AND to_show=1 "));
    $this->set('comm', $this->Slaves->query("SELECT vendors_commissions.*,vendors.company,vendors.shortForm ,users.name FROM vendors_commissions join vendors on (vendors_commissions.vendor_id = vendors.id AND vendors.show_flag = 1) left join users on (vendors_commissions.updated_by = users.id) where vendors_commissions.is_deleted=0"));
    $this->set('vendors', $this->Slaves->query("SELECT vendors.id,vendors.shortForm,vendors.active_flag FROM vendors WHERE show_flag = 1"));
    $this->set('slabs', $this->Slaves->query("SELECT * FROM `slabs` WHERE `active_flag` = 1 ;"));
    $inv_supplier_operator = $this->Slaves->query("SELECT supplier_id,name,operator_id,priority_flag " . "FROM `inv_supplier_operator` " . "JOIN `inv_suppliers` ON (inv_suppliers.id = inv_supplier_operator.supplier_id)");
    $inv_suppliers = array();
    $isos = array();
    foreach($inv_supplier_operator as $iso){
        $inv_suppliers[$iso['inv_supplier_operator']['operator_id']][] = array('supplier_id'=>$iso['inv_supplier_operator']['supplier_id'], 'name'=>$iso['inv_suppliers']['name']);
        if($iso['inv_supplier_operator']['priority_flag'] == 1){
            $isos[$iso['inv_supplier_operator']['operator_id']][] = $iso['inv_supplier_operator']['supplier_id'];
        }
    }
    $this->set('inv_suppliers', $inv_suppliers);
    $this->set('inv_supplier_operator', $isos);
    // print_r($this->Retailer->query("SELECT * FROM `slabs` WHERE `to_show` = 1 ;"));
}

function disableVendor(){
    $pid = $_REQUEST['pid'];
    $flag = $_REQUEST['flag'];
    $status = 'fail';
    if(isset($pid)){
        if($flag == 0) $flag = 2;
        else if($flag >= 1) $flag = 0;

        $this->Retailer->query("UPDATE vendors_commissions SET oprDown = $flag , updated_by = " . $this->Session->read('Auth.User.id') . " WHERE id = $pid");
        $this->Shop->setProdInfo($_REQUEST['product']);
        /*
         * $this->Shop->delMemcache("disabled_".$_REQUEST['vendor']."_".$_REQUEST['product']);
         * if($flag == 0){
         * $key = "health_".$_REQUEST['vendor']."_".$_REQUEST['product'];
         * $this->Shop->setMemcache($key,0,30*60);
         * }
         */
        $status = 'success';
    }
    echo $status;
    $this->autoRender = false;
}

function blockSlab(){
    $slab_id = $_REQUEST['slab_id'];
    $prod_id = $_REQUEST['prod_id'];
    $slab_status = $_REQUEST['status'];
    $status = 'fail';
    $flag = $slab_status;
    $key = "slab_" . $slab_id . "_" . $prod_id;

    // $result = $this->Retailer->query("SELECT blocked_slabs from products WHERE id = $prod_id");
    // $value = $result[0]["products"]["blocked_slabs"];
    $value = $this->Shop->getProdInfo($prod_id);
    $valueArr = $value["blocked_slabs"];

    // $valueArr = explode(",", $value);
    if($flag == 1){
        $keyArr = array_search($slab_id, $valueArr);
        if($keyArr !== NULL){
            array_push($valueArr, $slab_id);
        }
    }
    else{
        $keyArr = array_search($slab_id, $valueArr);
        if($keyArr !== NULL){
            unset($valueArr[$keyArr]);
        }
    }
    foreach($valueArr as $key=>$val){
        if(empty($val)){
            unset($valueArr[$key]);
        }
    }
    $valueNew = join(",", $valueArr);
    $this->Retailer->query("UPDATE products SET blocked_slabs = '$valueNew'  WHERE id = $prod_id"); // , updated_by = ".$this->Session->read('Auth.User.id')."
    $this->Shop->setProdInfo($prod_id);
    $status = 'success';
    echo $status;
    $this->autoRender = false;
}

function reports(){
}

function refreshCache(){
    $data = $this->Slaves->query("SELECT name FROM vars");
    foreach($data as $dt){
        $this->Shop->delMemcache($dt['vars']['name']);
    }

    $vendors = $this->Shop->getVendors();
    foreach($vendors as $vd){
        $this->Shop->setVendorInfo($vd['vendors']['id']);
    }
    echo 'success';

    $this->autoRender = false;
}

function deactivateVendor(){
    $pid = $_REQUEST['pid'];
    $flag = $_REQUEST['flag'];
    $status = 'fail';
    if(isset($pid)){
        $data = $this->Shop->getVendorInfo($pid);
        $user = $this->Session->read('Auth.User.name');
        $vendor = $data['shortForm'];

        if($flag == 0 || $flag == 2){
            $flag = 1;
            $subject = "Manually enabled $vendor from Provider Switching";
            $body = "User: $user";
        }
        else if($flag == 1){
            $flag = 2;
            $subject = "Manually disabled $vendor from Provider Switching";
            $body = "User: $user";
        }
        $this->General->sendMails($subject, $body, array('backend@pay1.in'), 'mail');

        $data1 = array();
        $data1['active_flag'] = $flag;
        $data1['health_factor'] = 0;
        $this->Shop->setVendorInfo($pid, $data1);
        $this->Shop->setInactiveVendors();
        $status = 'success';
    }
    echo $status;
    $this->autoRender = false;
}

function oprEnable(){
    $pid = $_REQUEST['pid'];
    $flag = $_REQUEST['flag'];
    $status = 'fail';
    if(isset($pid)){
        $msg = $_REQUEST['msg'];
        $qp = " , down_note = '" . addslashes($msg) . "' ";

        $this->Retailer->query("update products set oprDown=" . $flag . " $qp where id = " . $pid);
        $status = 'success';
        $opr = $this->Shop->setProdInfo($pid);
        // sending Message
        // $this->General->sendMessage($retMobile,$msg,'notify');
        // $userid = $this->Session->read('Auth.User.id');

        if($flag == '1'){
            $this->General->logData("/mnt/logs/alert.txt", date('Y-m-d H:i:s') . " :: " . 'log statrted for flag 1');
            $data = array('user'=>$this->Session->read('Auth.User.id'), 'type'=>'product disabled', 'sms'=>$opr['service_name'] . ": " . $opr['name'] . " product disabled");
            $this->General->logData("/mnt/logs/alert.txt", date('Y-m-d H:i:s') . " :: " . json_encode($data));
            $this->General->curl_post("http://inv.pay1.in/alertsystem/alertreport/addInAlert", $data, 'POST');
            $this->General->sendMails($opr['service_name'] . ": " . $opr['name'] . " product disabled", $opr['service_name'] . ": " . $opr['name'] . " product disabled", array('backend@pay1.in', 'rm@pay1.in'), 'mail');
        }
        else if($flag == '0'){
            $this->General->logData("/mnt/logs/alert.txt", date('Y-m-d H:i:s') . " :: " . 'log statrted for flag 1');
            $data = array('user'=>$this->Session->read('Auth.User.id'), 'type'=>'product enabled', 'sms'=>$opr['service_name'] . ": " . $opr['name'] . " product enabled");
            $this->General->logData("/mnt/logs/alert.txt", date('Y-m-d H:i:s') . " :: " . json_encode($data));
            $this->General->curl_post("http://inv.pay1.in/alertsystem/alertreport/addInAlert", $data, 'POST');
            $this->General->sendMails($opr['service_name'] . ": " . $opr['name'] . " product enabled", $opr['service_name'] . ": " . $opr['name'] . " product enabled", array('backend@pay1.in', 'rm@pay1.in'), 'mail');
        }
    }
    echo $status;
    $this->autoRender = false;
}

function manageOpr($flag, $operator, $comments){
    $date = date('Y-m-d');
    if($flag == 1) $flag = 0;
    elseif($flag == 0) $flag = 1;
    $this->Report->query("Insert into oprator_managment (flag,operator_id,coments,date) VALUES ($flag,$operator,'" . addslashes($comments) . "','$date')");
}

function tranRange($frm = null, $to = null, $vendorIds = null, $operatorIds = null, $page = null, $transType = "all", $lim = 1000, $f_time = null, $t_time = null){
   ini_set("memory_limit", "2524M"); // added inline memory
    if($frm == null && $to == null){
        $frm = date('d-m-Y');
        $to = date('d-m-Y');
    }
    $fdarr = explode("-", $frm);
    $tdarr = explode("-", $to);
    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    if(($f_time == null || $t_time == null) || ($f_time == 0 && $t_time == 0)){
        $f_time = 0;
        $t_time = 0;
        $VAtimeCondition = '';
        $ShopTranstimeCondition = '';
    }
    else{
        $frm_time = $fd . ' ' . str_replace('.', ':', $f_time) . ':00';
        $to_time = $ft . ' ' . str_replace('.', ':', $t_time) . ':00';
        $VAtimeCondition = "AND va.timestamp >= '{$frm_time}' and va.timestamp <= '{$to_time}'";
        $ShopTranstimeCondition = "AND st1.timestamp >= '{$frm_time}' and st1.timestamp <= '{$to_time}'";
    }

    if(( ! empty($vendorIds)) && ($vendorIds !== null)){
        $vndStr = " and va.vendor_id IN ($vendorIds) ";
        $vendorIds = explode(',', $vendorIds);
    }
    else{
        $vndStr = '';
    }

    if( ! empty($operatorIds) && ($operatorIds !== null)){
        $opStr = " and va.product_id IN ($operatorIds) ";
        $operatorIds = explode(',', $operatorIds);
    }
    else{
        $opStr = '';
    }

    $page = empty($_GET['res_type']) ? $page : $_GET['res_type'];
    $success1 = array();

    $transactionType = "Transactions";
    if(empty($page)) $page = 1;
    $limit = $lim * ($page - 1) . ",$lim";
    $limit = $page == "csv" ? "" : "limit " . $limit;

    // $getMobileNumberingDetails = $this->Slaves->query("select number,area from mobile_numbering");
    $getMobileNumberingDetails = $this->Slaves->query("select number, area from mobile_operator_area_map");
    foreach($getMobileNumberingDetails as $val){
        $mobArea[$val['mobile_operator_area_map']['number']] = $val['mobile_operator_area_map']['area'];
    }
    $vendorDDResult = $this->Slaves->query("select id,company from vendors order by company asc");
    $this->set('vendorDDResult', $vendorDDResult);
    $opResult = $this->Slaves->query("select products.id,products.name,products.service_id , services.name from products LEFT JOIN services ON products.service_id = services.id  where products.service_id != 0  order by  services.name , products.name ");
    $opResultTw = array();
    foreach($opResult as $tr){
        $opResultTw[$tr['products']['service_id']]["products"][] = $tr['products'];
        $opResultTw[$tr['products']['service_id']]["service_name"] = $tr['services']['name'];
    }
    $this->set('opResult', $opResultTw);

    if($transType == "reverse"){
        $success = $this->Slaves->query("SELECT users.name,v.company,v.shortForm, va.distributor_id, r.name,r.shopname,r.id,r.mobile,p.name,va.mobile,va.txn_id, va.amount, va.amount*va.discount_commission/100 as comm, va.status, va.prevStatus, va.cause ,va.timestamp ,va.vendor_refid, va.api_flag, va.tran_processtime,updated_timestamp as updated_time, group_concat(vm.response) as causes
			from vendors_activations va left join vendors_messages as vm ON (vm.va_tran_id=va.txn_id AND va.vendor_id = vm.service_vendor_id) join retailers r on(va.retailer_id=r.id) join products p on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id)  left join users ON (users.id=va.cc_userid)
				 where va.date between '" . $fd . "' and '" . $ft . "' AND  va.status in ( 2,3) $VAtimeCondition $vndStr $opStr group by va.txn_id order by va.id desc $limit");
    }
    else if($transType == "success"){
        $success = $this->Slaves->query("SELECT users.name,v.company,v.shortForm, va.distributor_id, r.name,r.shopname,r.id,r.mobile,p.name,va.mobile,va.txn_id, va.amount, va.amount*va.discount_commission/100 as comm, va.status, va.prevStatus, va.cause ,va.timestamp ,va.vendor_refid, va.api_flag, va.tran_processtime, updated_timestamp as updated_time, group_concat(vm.response) as causes
			from vendors_activations va left join vendors_messages as vm ON (vm.va_tran_id=va.txn_id AND va.vendor_id = vm.service_vendor_id) join retailers r on(va.retailer_id=r.id) join products p on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id)  left join users ON (users.id=va.cc_userid)
			 where va.date between '" . $fd . "' and '" . $ft . "' AND  va.status not in ( 0,2,3) $VAtimeCondition $vndStr $opStr group by va.txn_id order by va.id desc $limit");
    }
    else{
        $success = $this->Slaves->query("SELECT users.name,v.company,v.shortForm, va.distributor_id, r.name,r.shopname,r.id,r.mobile,p.name,va.mobile,va.txn_id, va.amount, va.amount*va.discount_commission/100 as comm, va.status, va.prevStatus, va.cause , va.timestamp ,va.vendor_refid, va.api_flag, va.tran_processtime, updated_timestamp as updated_time, group_concat(vm.response) as causes
			from vendors_activations va left join vendors_messages as vm ON (vm.va_tran_id=va.txn_id AND va.vendor_id = vm.service_vendor_id) join retailers r on(va.retailer_id=r.id) join products p on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id) left join users ON (users.id=va.cc_userid)
				 where va.date between '" . $fd . "' and '" . $ft . "' $VAtimeCondition $vndStr $opStr group by va.txn_id order by va.id desc $limit");
    }

    /** IMP DATA ADDED : START**/
   $ret_mobiles = array_map(function($element){
        return $element['r']['mobile'];
    },$success);

    $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);

    $retailer_imp_label_map = array(
        'pan_number' => 'pan_no',
        'shopname' => 'shop_est_name',
        'alternate_number' => 'alternate_mobile_no',
        'email' => 'email_id',
        'shop_structure' => 'shop_ownership',
        'shop_type' => 'business_nature'
    );
    foreach ($success as $key => $retailer) {
        foreach ($retailer['r'] as $retailer_label_key => $value) {
            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
            if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['mobile']]['imp']) ){
                $success[$key]['r'][$retailer_label_key] = $imp_data[$retailer['r']['mobile']]['imp'][$retailer_label_key_mapped];
            }
        }
    }
    /** IMP DATA ADDED : END**/


    $this->set('limitPerPage', $lim);
    if($page != "csv"){
        $this->set('vendorIds', $vendorIds);
        $this->set('operatorIds', $operatorIds);
        $this->set('success', $success);
        $this->set('frm', $frm);
        $this->set('to', $to);
        $this->set('f_time', $f_time);
        $this->set('t_time', $t_time);
        $this->set('page', $page);
        $this->set('transType', $transType);
    }
    else{
        $this->set('page', $page);
        App::import('Helper', 'csv');
        $this->layout = null;
        $this->autoLayout = false;
        $csv = new CsvHelper();
        // "v.company,v.shortForm, r.name,r.shopname,r.id,r.mobile,p.name,va.mobile, va.txn_id, va.amount, va.status, va.timestamp"

        // ---------------------------
        $line = array('Row', 'TransId', 'VendorTransId', 'Distributor ID', 'Retailer Mobile', 'Shop', 'Vendor', 'Cust Mob', 'Operator', 'Circle', 'Amt', 'Comm', 'Status', 'Previous Status', 'Date', 'Processing Time', 'Updated Time', 'TypeStatus', 'Cause', 'Sub-Cause', 'CC', 'TxnBy');
        $csv->addRow($line);

        $i = 1;

        // $dateArray = array();
        //
        // $vendorId = array();
        //
        // $oldreversalQuery = "SELECT st1.date,st2.* FROM `shop_transactions` as st1 left join `shop_transactions` as st2 ON (st1.ref2_id = st2.id) WHERE st2.type = 4 AND st2.date != st1.date AND st1.`type` = 11 AND st1.`date` >= '$fd' and st1.date<='$ft' $ShopTranstimeCondition ";
        //
        // $oldreversalQuery = $this->Slaves->query($oldreversalQuery);
        //
        //
        // foreach ($oldreversalQuery as $val) {
        // $vendorId[] = $val['st2']['id'];
        //
        // $dateArray[$val['st2']['date']] = $val['st2']['date'];
        //
        // $retailerId[$val['st2']['ref1_id']] = $val['st2']['ref1_id'];
        // }
        // $dateArray = implode("','", $dateArray);
        //
        // $vendorId = implode(',', $vendorId);
        //
        // $retailerId = implode(',', $retailerId);
        $query = "SELECT
					     users.name,v.company,v.shortForm,va.distributor_id, r.name,r.shopname,r.id,r.mobile,p.name,va.mobile,va.txn_id, va.amount, va.amount*va.discount_commission/100 as comm, va.status, va.prevStatus, va.cause ,va.timestamp ,va.vendor_refid, va.api_flag, va.tran_processtime, va.updated_timestamp as updated_time, group_concat(vm.response) as causes,min(vm.timestamp) as process_time,max(vm.timestamp) as vm_timestamp
			         FROM
					 vendors_activations va

					 LEFT JOIN
					      vendors_messages as vm ON (vm.va_tran_id=va.txn_id AND va.vendor_id = vm.service_vendor_id)
					 INNER JOIN
					      retailers r on(va.retailer_id=r.id)
					 LEFT JOIN products p
					      on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id)

					 LEFT JOIN
					     users ON (users.id=va.cc_userid)

					 WHERE

					 ((va.date between '$fd' and '$ft') OR (va.reversal_date between '$fd' and '$ft')) $VAtimeCondition $vndStr $opStr group by va.txn_id order by va.id desc";

        $success = $this->Slaves->query($query);

        // $success = array_merge_recursive($success,$success1);

        foreach($success as $d){
            $type = "";
            if($d['va']['status'] == '2' || $d['va']['status'] == '3'){
                $type = "Failed";
            }
            else{
                $type = "Success";
            }

            $api_flag = $d['va']['api_flag'];
            $api_array = array('0'=>'sms', '1'=>'old apps', '2'=>'ussd', '3'=>'android', '4'=>'api partner', '5'=>'java', '7'=>'win7', '8'=>'win8', '9'=>'web');

            $retailerLink = strcmp($d['r']['name'], '') != 0 ? $d['r']['name'] : $d['r']['mobile'];
            $ps = '';
            if($d['va']['status'] == '0'){
                $ps = 'In Process';
            }
            else if($d['va']['status'] == '1'){
                $ps = 'Successful';
            }
            else if($d['va']['status'] == '2'){
                $ps = 'Failed';
            }
            else if($d['va']['status'] == '3'){
                $ps = 'Reversed';
            }
            else if($d['va']['status'] == '4'){
                $ps = 'Reversal In Process';
            }
            else if($d['va']['status'] == '5'){
                $ps = 'Reversal declined';
            }

            $ps_p = "";
            if($d['va']['prevStatus'] == '0'){
                $ps_p = 'In Process';
            }
            else if($d['va']['prevStatus'] == '1'){
                $ps_p = 'Successful';
            }
            else if($d['va']['prevStatus'] == '2'){
                $ps_p = 'Failed';
            }
            else if($d['va']['prevStatus'] == '3'){
                $ps_p = 'Reversed';
            }
            else if($d['va']['prevStatus'] == '4'){
                $ps_p = 'Reversal In Process';
            }
            else if($d['va']['prevStatus'] == '5'){
                $ps_p = 'Reversal declined';
            }

            if($d['va']['tran_processtime'] == '' || $d['va']['tran_processtime'] == '0000-00-00 00:00:00'){
                $processTime = $d[0]['process_time'];
            }
            else{
                $processTime = $d['va']['tran_processtime'];
            }

            $mobnum = substr($d['va']['mobile'], 0, 5);
            $sub_cause = explode(",", $d[0]['causes']);
            $sub_cause = end($sub_cause);
            $sub_cause = ($d['va']['status'] == 2 || $d['va']['status'] == 3) ? $sub_cause : "";

            $line = array($i, $d['va']['txn_id'], $d['va']['vendor_refid'], $d['va']['distributor_id'], $d['r']['mobile'], $d['r']['name'], $d['v']['shortForm'], $d['va']['mobile'], $d['p']['name'], $mobArea[$mobnum], $d['va']['amount'], round($d['0']['comm'], 2), $ps, $ps_p, $d['va']['timestamp'],
                    $processTime, $d[0]['vm_timestamp'], $type, $d['va']['cause'], $sub_cause, $d['users']['name'], $api_array[$api_flag]);
            $csv->addRow($line);
            $i ++ ;
        }

        echo $csv->render($transactionType . "_" . $frm . "_" . $to . ".csv");
    }
}

/*
 * function tags($tagname=null)
 * {
 * if(in_array($_SESSION['Auth']['User']['group_id'],array(ADMIN,CUSTCARE)) || $_SESSION['Auth']['User']['id'] == 1){
 * $this->set('selectedTagName',$tagname);
 * $tagNameResult=$this->User->query("select distinct name,id from taggings t order by name asc");
 * $this->set('tagNames',$tagNameResult);
 * if(!is_null($tagname))
 * $tagsResult = $this->User->query("select va.amount,u.name,u.mobile,u.group_id,t.name,tu.transaction_id,tu.timestamp,r.shopname from taggings t join user_taggings tu on(t.id=tu.tagging_id) join users u on (u.id=tu.user_id) join retailers r on (r.user_id=u.id) left join vendors_activations va on (va.txn_id=tu.transaction_id) where t.name='".$tagname."' order by t.name asc ");
 *
 * $this->set('tagsResult',$tagsResult);
 * } else {
 * $this->redirect('/');
 * }
 *
 * }
 */
function reconsile($vendor = null, $date = null){
    $vendor_list = $this->Slaves->query("select id, company from vendors where update_flag=1 and active_flag != 2");
    $this->set('vendor_list', $vendor_list);

    $operator_list = $this->Slaves->query("select id, name from products");
    $this->set('operator_list', $operator_list);

    if( ! is_null($vendor)){
        $reconsile_status_arr = array('va'=>array('0'=>'in-process', '1'=>'success', '2'=>'failure', '3'=>'refund', '4'=>'complain', '5'=>'reversal-decline'), 'vt'=>array('0'=>'in-process', '1'=>'success', '2'=>'failure'));
        $va_qry = "SELECT concat(txn_id,'_',(CASE WHEN status in (1,4,5) THEN 1 ELSE (CASE WHEN status in (2,3) THEN 2 ELSE 0 END) END),'_',vendor_id) as cid,txn_id as id from vendors_activations where vendor_id='" . $vendor . "'";
        $vt_qry = "SELECT concat(va_tran_id,'_',if(status='failure',2,if(status='success','1','0')),'_',service_vendor_id) as cid, va_tran_id as id from vendors_messages where service_vendor_id='" . $vendor . "' ";

        if(is_null($date) && trim($_REQUEST['rdate']) == ""){
            $date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
        }
        else{
            $date_part = explode("-", $_REQUEST['rdate']);
            $date = date("Y-m-d", mktime(0, 0, 0, $date_part[1], $date_part[0], $date_part[2]));
        }
        $va_qry .= " AND date='" . $date . "'";
        $vt_qry .= " AND vm_date='" . $date . "'";
        $un_qry = "SELECT cid, id FROM (($va_qry) UNION ALL ($vt_qry))as t1 group by 1 having count(1) = 1 order by 1";
        $txn_list = array();
        $txn_list_str = "";
        $id_results = $this->Slaves->query($un_qry);
        foreach($id_results as $k=>$v){
            if( ! isset($txn_list[$v['t1']['id']])){
                array_push($txn_list, $v['t1']['id']);
            }
        }
        $txn_list_str = implode(",", $txn_list);
        $result_qry = "SELECT * FROM (" . "SELECT vm.sim_num as sim_num, va.product_id as operator,va.amount as amount,va.txn_id as txnid," . "(CASE WHEN va.vendor_id = vm.service_vendor_id THEN va.status ELSE 2 END )as ser_status," . "if(vm.status='failure',2,if(vm.status='success','1','0')) as vend_status,va.timestamp " . "FROM vendors_messages as vm LEFT JOIN vendors_activations as va  on va.txn_id = vm.va_tran_id  " . "WHERE vm.service_vendor_id='" . $vendor . "' and vm.va_tran_id in (" . $txn_list_str . ") and va.date='" . $date . "')as t " . "where ser_status != vend_status";
        $result_data = $this->Slaves->query($result_qry);
        $sims_details = $this->get_reconsile_opn_clg($date, $vendor);
        $sim_wise_sale_result = $this->get_sim_wise_sale($vendor, $date);
        $result_data1 = $this->reconsile_formater($result_data);
        $sim_reports = $this->get_sorted_sim_wise_data($sim_wise_sale_result, $sims_details, $operator_list);
        $this->set('sim_reports', $sim_reports);
        $this->set('sims_details', $sims_details);
        $this->set('data_Result', $result_data);
        $this->set('data_Result1', $result_data1);
        $this->set('status_arr', $reconsile_status_arr);
        $this->set('vendor_id', $vendor);
        $this->set('opng_clg_diff', $this->get_sim_with_diff_without_sale($sims_details, $operator_list));
        $this->set('sim_wise_sale', $sim_wise_sale_result);
    }
}

function get_sim_with_diff_without_sale($open_closing = array(), $operator_list = array()){
    $diff_array = array();
    $opr_list = array();
    foreach($operator_list as $k=>$v){
        $opr_list[$v['products']['id']] = $v['products']['name'];
    }
    foreach($open_closing as $k=>$v){
        foreach($v as $k1=>$v1){
            if(($v1['closing'] - $v1['opening']) > 0 && ($v1['sale'] < 1 || is_null($v1['sale']))){
                array_push($diff_array, $k . "_" . $opr_list[$k1]);
            }
        }
    }
    return $diff_array;
}

function get_sorted_sim_wise_data($sim_wise_sale, $sims_details, $operator_list = array()){
    $final_result = array();
    $opr_list = array();
    foreach($operator_list as $k=>$v){
        $opr_list[$v['products']['id']] = $v['products']['name'];
    }
    $i = 0;
    foreach($sim_wise_sale as $k=>$v){
        if(isset($sims_details[$v['t']['sim_num']])){
            //array_push($server_sim_num, $v['t']['sim_num']);
            $modem_diff = $sims_details[$v['t']['sim_num']][$v['t']['opr']]['sale'] + $sims_details[$v['t']['sim_num']][$v['t']['opr']]['closing'] - $sims_details[$v['t']['sim_num']][$v['t']['opr']]['opening'] - $sims_details[$v['t']['sim_num']][$v['t']['opr']]['diff'] - $sims_details[$v['t']['sim_num']][$v['t']['opr']]['inc'];
            $server_diff = floatval($sims_details[$v['t']['sim_num']][$v['t']['opr']]['sale']) - floatval($v['t']['sale']);
            $incomming = $sims_details[$v['t']['sim_num']][$v['t']['opr']]['diff'];
            $vendor = $sims_details[$v['t']['sim_num']][$v['t']['opr']]['vendor'];
            $approx_sale = $incomming + $modem_diff - $server_diff;
        }
        $final_result[$i]['operator'] = ((strlen(trim($v['t']['opr'])) > 0 && isset($opr_list[$v['t']['opr']])) ? $opr_list[$v['t']['opr']] : 'N.A');
        $final_result[$i]['operator_id'] = ((strlen(trim($v['t']['opr'])) > 0) ? $v['t']['opr'] : 'N.A');
        $final_result[$i]['vendor'] = (isset($vendor) ? $vendor : 'N.A');
        $final_result[$i]['sim_num'] = $v['t']['sim_num'];
        $final_result[$i]['opening'] = (isset($sims_details[$v['t']['sim_num']][$v['t']['opr']]['opening']) ? $sims_details[$v['t']['sim_num']][$v['t']['opr']]['opening'] : 'N.A');
        $final_result[$i]['closing'] = (isset($sims_details[$v['t']['sim_num']][$v['t']['opr']]['closing']) ? $sims_details[$v['t']['sim_num']][$v['t']['opr']]['closing'] : 'N.A');
        $final_result[$i]['incomming'] = (isset($incomming) ? $incomming : 'N.A');
        $final_result[$i]['approx_sale'] = (isset($approx_sale) ? $approx_sale : 'N.A');
        $final_result[$i]['modem_sale'] = (isset($sims_details[$v['t']['sim_num']][$v['t']['opr']]) ? $sims_details[$v['t']['sim_num']][$v['t']['opr']]['sale'] : 'N.A');
        $final_result[$i]['server_sale'] = $v['t']['sale'];
        $final_result[$i]['modem_diff'] = (isset($sims_details[$v['t']['sim_num']][$v['t']['opr']]) ? $modem_diff : 'N.A');
        $final_result[$i]['server_diff'] = (isset($sims_details[$v['t']['sim_num']][$v['t']['opr']]) ? $server_diff : 'N.A');
        $i ++ ;
    }
    asort($final_result);
    return $final_result;
}

function get_reconsile_opn_clg($date, $vendor){
    $result = $this->format_sim_details($this->Recharge->modemBalance($date, $vendor));
    return $result;
}

function format_sim_details($data){
    $finalresult = array();
    foreach($data as $k=>$v){
        if(in_array(trim($k), array('lasttime', 'ports'))){
            continue;
        }
        if(isset($finalresult[$v['mobile']][$v['opr_id']])){
            $finalresult[$v['mobile']][$v['opr_id']]['opening'] += $v['opening'];
            $finalresult[$v['mobile']][$v['opr_id']]['closing'] += isset($v['closing'])?$v['closing']:0;
            $finalresult[$v['mobile']][$v['opr_id']]['diff'] += isset($v['diff'])?$v['diff']:0;
            $finalresult[$v['mobile']][$v['opr_id']]['sale'] += $v['sale'];
        }
        else{
            $finalresult[$v['mobile']][$v['opr_id']] = array('opening'=>$v['opening'], 'closing'=>isset($v['closing'])?$v['closing']:0, 'diff'=>$v['tfr'], 'sale'=>$v['sale'], 'vendor'=>$v['vendor'], 'inc'=>$v['inc']);
        }
        unset($data[$k]);
    }
    return $finalresult;
}

function get_sim_wise_sale($vendor, $date){
    $sim_wise_sale_qry = "SELECT * FROM (" . "SELECT va.sim_num, (CASE WHEN va.product_id in ('10','27') THEN 9 ELSE " . "(CASE WHEN va.product_id='29' THEN 11 ELSE " . "(CASE WHEN va.product_id='28' THEN 12 ELSE " . "(CASE WHEN va.product_id='31' THEN 30 ELSE " . "(CASE WHEN va.product_id='34' THEN 3 ELSE " . "(CASE WHEN va.product_id='7' THEN 8 ELSE va.product_id END ) END) END) END)" . " END) END ) as opr,  sum(va.amount) as sale " . "FROM `vendors_activations` as va " .
    // . "INNER JOIN `vendors_activations` as va "
    // . "ON vt.ref_id=va.txn_id AND vt.date=va.date AND vt.vendor_id=va.vendor_id "
    "WHERE va.vendor_id='" . $vendor . "' AND va.sim_num is not null " . "AND va.sim_num !='' and va.sim_num !='0' and va.status not in (2,3) and va.date ='" . $date . "' group by 1 ,2 )t order by 2";
    $sim_wise_sale_result = $this->Slaves->query($sim_wise_sale_qry);
    return $sim_wise_sale_result;
}

function reconsile_formater($data){
    $consolidate_arr = array();
    $combine_mapper = array(10=>9, 27=>9, 29=>11, 28=>12, 31=>30, 34=>3);
    foreach($data as $d){
        if( ! isset($consolidate_arr[$d['t']['sim_num']][$d['t']['operator']]['total'])){
            $consolidate_arr[$d['t']['sim_num']][$d['t']['operator']]['total'] = 0;
            $consolidate_arr[$d['t']['sim_num']][$d['t']['operator']]['count'] = 0;
        }
        $d['t']['operator'] = in_array($d['t']['operator'], array_keys($combine_mapper)) ? $combine_mapper[$d['t']['operator']] : $d['t']['operator'];
        $consolidate_arr[$d['t']['sim_num']][$d['t']['operator']]['total'] += $d['t']['amount'];
        $consolidate_arr[$d['t']['sim_num']][$d['t']['operator']]['count'] += 1;
        $consolidate_arr[$d['t']['sim_num']][$d['t']['operator']]['operator'] = $d['t']['operator'];
        $consolidate_arr[$d['t']['sim_num']][$d['t']['operator']]['sim_num'] = $d['t']['sim_num'];
        if($consolidate_arr[$d['t']['sim_num']][$d['t']['operator']]['sim_num'] == $d['t']['sim_num'] && $consolidate_arr[$d['t']['sim_num']][$d['t']['operator']]['operator'] == $d['t']['operator']){
            $consolidate_arr[$d['t']['sim_num']][$d['t']['operator']]['data'][] = $d;
        }
    }
    return $consolidate_arr;
}

// function update_reconsile(){
// $this->layout = null;
// $this->autoLayout = false;
// $qry = "";
// if(isset($_REQUEST['r_flag'])){
// $update_qry = "UPDATE vendors_transactions SET resolve_flag='".$_REQUEST['r_flag']."' "
// . "WHERE ref_id='".$_REQUEST['id']."' AND vendor_id='".$_REQUEST['r_vendor']."'";
// }
// if(isset($_REQUEST['r_comment'])){
// $update_qry = "UPDATE vendors_transactions SET comment='".$_REQUEST['r_comment']."' "
// . "WHERE ref_id='".$_REQUEST['id']."' AND vendor_id='".$_REQUEST['r_vendor']."'";
// }
// if(strlen(trim($update_qry)) > 0){
// $result_data = $this->User->query($update_qry);
// }
// $this->autoRender = false;
// }
function pullback(){
    $this->autoRender = false;
    $transId = $_REQUEST['id'];
    $vendorActObj = $this->User->getDataSource();
    $vendorActObj->begin();
    try{

        $result = $vendorActObj->query("SELECT vendors_activations.*,vendors.shortForm,trans_vendor.shortForm,trans_vendor.update_flag, " . "vendors.update_flag, trans_pullback.vendor_id, retailers.mobile,products.service_id,products.earning_type,products.earning_type_flag,products.expected_earning_margin, partners_log.id,partners_log.partner_req_id " . "FROM vendors_activations left join retailers ON (retailer_id = retailers.id) " . "LEFT JOIN products ON (products.id = product_id) " . "LEFT JOIN partners_log ON (vendors_activations.txn_id = partners_log.vendor_actv_id) " . "LEFT JOIN vendors ON (vendors_activations.vendor_id = vendors.id) " . "LEFT JOIN `trans_pullback` ON (vendors_activations.id = trans_pullback.vendors_activations_id) " . "LEFT JOIN vendors as trans_vendor ON (trans_pullback.vendor_id = trans_vendor.id) " . "WHERE vendors_activations.id=$transId AND (vendors_activations.status =2 OR vendors_activations.status = 3)");

        if(empty($result)){
            throw new Exception('No data found');
        }

        if( ! $this->Recharge->lockTransaction($result['0']['vendors_activations']['txn_id'])){
            echo 'Transaction is locked';
            return;
        }

        if($result['0']['vendors_activations']['retailer_id'] == B2C_RETAILER &&  ! empty($result['0']['partners_log']['id'])){ // b2c partner
            $ref_code = "2082" . sprintf('%06d', $result['0']['partners_log']['id']);
            $out = $this->General->b2c_pullback($result['0']['partners_log']['partner_req_id'], $ref_code);
            if($out['status'] == 'failure'){
                $desc = $out['description'];
                if(empty($desc)) $desc = 'Amount is less in user account';
                $this->Recharge->log_in_vendor_message(array('va_tran_id'=>$result['0']['vendors_activations']['txn_id'], 'vendor_refid'=>$result['0']['vendors_activations']['vendor_refid'], 'service_id'=>$result['0']['products']['service_id'],
                        'service_vendor_id'=>$result['0']['vendors_activations']['vendor_id'], 'internal_error_code'=>14, 'response'=>'Cannot be pulled back by ' . $_SESSION['Auth']['User']['name'] . ', ' . $desc, 'status'=>'failure', 'timestamp'=>date('Y-m-d H:i:s'), 'vm_date'=>date('Y-m-d')));
                throw new Exception('Cannot be pulled back');
            }
        }
        elseif($this->Session->read('Auth.User.group_id') != ADMIN){

            $trans_refcode = $result['0']['vendors_activations']['txn_id'];
            $transdate = $result['0']['vendors_activations']['date'];
            $transvrefId = $result['0']['vendors_activations']['vendor_refid'];
            $transvendorname =  ! empty($result['0']['trans_vendor']['shortForm']) ? trim($result['0']['trans_vendor']['shortForm']) : trim($result['0']['vendors']['shortForm']);

            $tranResponse = $this->Recharge->tranStatus($trans_refcode, $transvendorname, $transdate, $transvrefId);
            $vendor_type =  ! empty($result['0']['trans_vendor']['shortForm']) ? trim($result['0']['trans_vendor']['update_flag']) : trim($result['0']['vendors']['update_flag']);

            if($vendor_type == 0){ // for API vendors and non admin user
                $tranResponse['status'] = isset($tranResponse['status']) ? strtolower($tranResponse['status']) : "";
                if(isset($tranResponse['status']) && strtolower(trim($tranResponse['status']) != "success")){
                    $this->Recharge->log_in_vendor_message(array('va_tran_id'=>$result['0']['vendors_activations']['txn_id'], 'vendor_refid'=>$result['0']['vendors_activations']['vendor_refid'], 'service_id'=>$result['0']['products']['service_id'],
                            'service_vendor_id'=>$result['0']['vendors_activations']['vendor_id'], 'internal_error_code'=>14, 'response'=>'Cannot be pulled back by ' . $_SESSION['Auth']['User']['name'] . ' as transaction with status (success) is allowed', 'status'=>'failure',
                            'timestamp'=>date('Y-m-d H:i:s'), 'vm_date'=>date('Y-m-d')), $vendorActObj);
                    throw new Exception('Cannot be pulled back as this transaction (Only success transactions are allowed)');
                }
            }
            else{ // constrain for modem vendor and non admin user
                $tranResponse_arr = json_decode($tranResponse, true);
                if(isset($tranResponse_arr['status']) &&  ! (in_array(strtolower(trim($tranResponse_arr['status'])), array("pending", "success")))){
                    $this->Recharge->log_in_vendor_message(array('va_tran_id'=>$result['0']['vendors_activations']['txn_id'], 'vendor_refid'=>$result['0']['vendors_activations']['vendor_refid'], 'service_id'=>$result['0']['products']['service_id'],
                            'service_vendor_id'=>$result['0']['vendors_activations']['vendor_id'], 'internal_error_code'=>14, 'response'=>'Cannot be pulled back by ' . $_SESSION['Auth']['User']['name'] . ' as transaction with status (pending,success) are allowed', 'status'=>'failure',
                            'timestamp'=>date('Y-m-d H:i:s'), 'vm_date'=>date('Y-m-d')), $vendorActObj);
                    throw new Exception('Cannot be pulled back as this transaction (Only success & pending transactions are allowed) ');
                }
            }
        }
        if(time() - strtotime($result['0']['vendors_activations']['timestamp']) > 12 * 60 * 60 && $this->Session->read('Auth.User.group_id') != BACKEND_ADMIN){
            $this->Recharge->log_in_vendor_message(array('va_tran_id'=>$result['0']['vendors_activations']['txn_id'], 'vendor_refid'=>$result['0']['vendors_activations']['vendor_refid'], 'service_id'=>$result['0']['products']['service_id'],
                    'service_vendor_id'=>$result['0']['vendors_activations']['vendor_id'], 'internal_error_code'=>14, 'response'=>'Cannot be pulled back by ' . $_SESSION['Auth']['User']['name'] . ' as transaction is older than 12 hours', 'status'=>'failure', 'timestamp'=>date('Y-m-d H:i:s'),
                    'vm_date'=>date('Y-m-d')), $vendorActObj);
            throw new Exception("Cannot be pulled back as this transaction is older than 12 hours");
        }
        // $this->User->query("START TRANSACTION");
        $shop_id = $result['0']['vendors_activations']['shop_transaction_id'];
        $mobile = $result['0']['vendors_activations']['mobile'];
        $param = $result['0']['vendors_activations']['param'];
        $amount = $result['0']['vendors_activations']['amount'];
        $retMobile = $result['0']['retailers']['mobile'];
        $vendors_activations_id = $result[0]['vendors_activations']['id'];
        $vendorId = $result[0]['vendors_activations']['vendor_id'];
        $date = $result['0']['vendors_activations']['date'];

        $MsgTemplate = $this->General->LoadApiBalance();
        $checktrans = $vendorActObj->query("SELECT id,vendor_id from trans_pullback where trans_pullback.vendors_activations_id = '" . $vendors_activations_id . "'");
        if( ! empty($checktrans)){
            $vendorId = $checktrans[0]['trans_pullback']['vendor_id'];
        }

        $this->Recharge->update_in_vendors_activations(array('vendor_id'=>$vendorId, 'prevStatus'=>$result['0']['vendors_activations']['status'], 'status'=>TRANS_SUCCESS, 'reversal_date'=>'', 'cc_userid'=>(empty($_SESSION['Auth']['User']['id']) ? 0 : $_SESSION['Auth']['User']['id'])), array(
                'id'=>$transId), $vendorActObj);
        $this->Recharge->log_in_vendor_message(array('va_tran_id'=>$result['0']['vendors_activations']['txn_id'], 'vendor_refid'=>$result['0']['vendors_activations']['vendor_refid'], 'service_id'=>$result['0']['products']['service_id'],
                'service_vendor_id'=>$result['0']['vendors_activations']['vendor_id'], 'internal_error_code'=>13, 'response'=>'Pulled back by ' . $_SESSION['Auth']['User']['name'], 'status'=>'success', 'timestamp'=>date('Y-m-d H:i:s'), 'vm_date'=>date('Y-m-d')), $vendorActObj);
        $vendorActObj->query("UPDATE shop_transactions SET confirm_flag=1 WHERE id=$shop_id");
        $vendorActObj->query("DELETE FROM shop_transactions WHERE target_id = $shop_id AND type = " . REVERSAL_RETAILER);
        $amt = $result['0']['vendors_activations']['amount'] - $result['0']['vendors_activations']['retailer_margin'];
        $ret_id = $result['0']['vendors_activations']['retailer_id'];

        $retailerInfo = $this->Shop->getShopDataById($ret_id, RETAILER);
        $service_id = $result['0']['products']['service_id'];
        $ret_id= $result[0]['vendors_activations']['retailer_id'];
        $api_flag= $result[0]['vendors_activations']['api_flag'];
        $retailer_margin = $result['0']['vendors_activations']['retailer_margin'];
        $service_charge = $result['0']['vendors_activations']['retailer_margin'] < 0?abs($result['0']['vendors_activations']['retailer_margin']):0;
        $commission = $result['0']['vendors_activations']['retailer_margin'] > 0?$result['0']['vendors_activations']['retailer_margin']:0;
        $earning_type = $result['0']['products']['earning_type'];
        $earning_type_flag = $result['0']['products']['earning_type_flag'];
        $v_id = $result[0]['vendors_activations']['vendor_id'];
        $p_id = $result[0]['vendors_activations']['product_id'];
        if($earning_type == 2){
            $percent_pos = strpos($result['0']['products']['expected_earning_margin'], '%');
            $percent = substr($result['0']['products']['expected_earning_margin'], 0, $percent_pos);
            $expected_earning = $percent_pos !== false?($amount*$percent)/100:$result['0']['products']['expected_earning_margin'];
        }else{
            $expected_earning = $retailer_margin;
        }

        $vendorActObj->query("UPDATE retailer_earning_logs rel "
                    . "JOIN retailers r "
                    . "ON (rel.ret_user_id=r.user_id) "
                    . "SET txn_count=txn_count+1,rel.amount = rel.amount + $amount,rel.earning=rel.earning+$retailer_margin,rel.expected_earning=rel.expected_earning+$expected_earning,rel.commission=rel.commission+$commission,rel.service_charge=rel.service_charge+$service_charge "
                    . "WHERE r.id = '$ret_id' "
                    . "AND rel.date = '$date' "
                    . "AND rel.service_id='$service_id' "
                    . "AND rel.txn_type='$earning_type' AND rel.txn_type_flag='$earning_type_flag' AND rel.api_flag=$api_flag AND rel.type = ".DEBIT_NOTE." ");
        if($date != date('Y-m-d')){
            $vendor_comm = $result[0]['vendors_activations']['discount_commission']*$amount/100;

            $vendorActObj->query("UPDATE api_vendors_sale_data SET sale = sale + $amount,commission = commission + $vendor_comm WHERE vendor_id = $v_id AND product_id = $p_id AND date = '$date'");
            $vendorActObj->query("UPDATE earnings_logs SET sale = sale + $amount,expected_earning= expected_earning + $vendor_comm WHERE vendor_id = $v_id AND date = '$date'");
        }

        $bal = $this->Shop->shopBalanceUpdate($amt, 'subtract', $retailerInfo['user_id'], RETAILER, $vendorActObj);
        // $this->Shop->addOpeningClosing ( $ret_id, RETAILER, $shop_id, $bal + $amt, $bal, $vendorActObj );
        if(empty($checktrans)){
            $vendorActObj->query("INSERT INTO trans_pullback (id,vendors_activations_id,vendor_id,status,timestamp,pullback_by,pullback_time,reported_by,date) values('','" . $vendors_activations_id . "','" . $vendorId . "','1','" . date('Y-m-d H:i:s') . "','" . $_SESSION['Auth']['User']['id'] . "','" . date('Y-m-d H:i:s') . "','Non-system','" . date('Y-m-d') . "')");
        }
        else{
            $vendorActObj->query("UPDATE trans_pullback SET pullback_by='" . (empty($_SESSION['Auth']['User']['id']) ? 0 : $_SESSION['Auth']['User']['id']) . "',pullback_time='" . date('Y-m-d H:i:s') . "' WHERE vendors_activations_id=$vendors_activations_id");
        }

        $this->Shop->unlockReverseTransaction($shop_id, $vendorActObj);
        if($result['0']['vendors_activations']['date'] <= date('Y-m-d', strtotime('-1 days'))){
            $this->Shop->updateDeviceData($result['0']['vendors_activations']['txn_id'], $result['0']['vendors_activations']['vendor_id'], $result['0']['vendors_activations']['product_id'], $result['0']['vendors_activations']['amount'], false);
        }

        $paramdata['PULLBACKTO'] = (empty($param)) ? "Mobile: $mobile\n" : "Subscriber Id: $param\n";
        $paramdata['PULLED_AMOUNT'] = $amt;
        $paramdata['TRANSID'] = substr($transId,  - 5);
        $paramdata['AMOUNT'] = $amount;
        $paramdata['BALANCE'] = $bal;
        $content = $MsgTemplate['Panels_Pullback_MSG'];
        $msg = $this->General->ReplaceMultiWord($paramdata, $content);

        $this->General->sendMessage($retMobile, $msg, 'notify');
        $vendorActObj->query("COMMIT");

        $this->Recharge->unlockTransaction($result['0']['vendors_activations']['txn_id']);
    }
    catch(Exception $e){
        echo $e->getMessage();
        $this->Recharge->unlockTransaction($result['0']['vendors_activations']['txn_id']);
        $vendorActObj->query("ROLLBACK");
    }
}

function addComment(){
    $loggedInUser = $_SESSION['Auth']['User']['mobile'];
    $userMobile = empty($_REQUEST['userMobile']) ? "" : $_REQUEST['userMobile'];
    $test = empty($_REQUEST['text']) ? "" : $_REQUEST['text'];
    $retId = empty($_REQUEST['retId']) ? "" : $_REQUEST['retId'];
    $transId = empty($_REQUEST['transId']) ? "" : $_REQUEST['transId'];
    $callTypeId = $_REQUEST['callTypeId'];
    $tagId = $_REQUEST['tagId'];

    $callTypeId == 'none' && $callTypeId = null;
    $tagId == 'none' && $tagId = null;

    if(empty($test)) exit();
    $userId = 0;
    if( ! empty($userMobile)){
        $usersResult = $this->Slaves->query("select id,name,mobile from users where mobile='$userMobile'");
        $userId = empty($usersResult) ? 0 : $usersResult['0']['users']['id'];
    }
    $this->Shop->addComment($userId, $retId, $transId, $test, $loggedInUser, null, $tagId, $callTypeId);
    if($transId){
        $comment = $this->User->query("SELECT c.comments,c.ref_code,u.name,u.mobile,c.created
						from comments c join users u on c.mobile = u.mobile
						where c.ref_code = '$transId'  order by c.created desc limit 1");
        if($comment){
            echo "<tr bgcolor='#CEF6F5' style='border: 2px solid white'>";
            echo "<td><span style='font-size:12px;'>By " . $comment[0]['u']['name'] . " @ " . $comment[0]['c']['created'] . " on " . $comment[0]['c']['ref_code'] . "</span></br>" . $comment[0]['c']['comments'] . "</td>";
            echo "</tr>";
        }
    }
    $this->autoRender = false;
}

function salesmanReport($distId = null, $salesmanId = null, $from = null, $to = null){
    if( ! isset($from)) $from = date('d-m-Y');
    if( ! isset($to)) $to = date('d-m-Y');

    if($distId == null) $distId = 1;

    $fdarr = explode("-", $from);
    $tdarr = explode("-", $to);

    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    $this->set('from', $from);
    $this->set('to', $to);
    $this->set('distId', $distId);

    // for salesman dropdown
    $distResult = $this->Slaves->query("select Distributor.* from distributors as Distributor where Distributor.toshow = 1");

    /** IMP DATA ADDED : START**/
    $dist_ids = array_map(function($element){
        return $element['Distributor']['id'];
    },$distResult);

    $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);

    $dist_imp_label_map = array(
        'pan_number' => 'pan_no',
        'company' => 'shop_est_name',
        'alternate_number' => 'alternate_mobile_no',
        'email' => 'email_id'
    );
    foreach ($distResult as $key => $distributor) {
        foreach ($distributor['Distributor'] as $dist_label_key => $value) {
            $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
            if( array_key_exists($dist_label_key_mapped,$imp_data[$distributor['Distributor']['id']]['imp']) ){
                $distResult[$key]['Distributor'][$dist_label_key] = $imp_data[$distributor['Distributor']['id']]['imp'][$dist_label_key_mapped];
            }
        }
    }
    /** IMP DATA ADDED : END**/

    $this->set('distributors', $distResult);

    // for salesman dropdown
    $salesmanResult = $this->Slaves->query("select s.id,s.name,s.mobile from salesmen s where s.dist_id = $distId AND s.active_flag = 1");
    $this->set('salesman', $salesmanResult);

    // for table in salesman Reports
    // echo "Sales Mobile= ".$salesmanMobile;
    $this->set('salesmanId', $salesmanId);

    if($salesmanId != 0){
        $salesResult = $this->Slaves->query("SELECT * FROM ((select st.id,r.name,r.mobile,ur.shopname,st.amount,trim(sst.collection_amount) as collection_amount,trim(sst.created) as created,trim(sst.payment_type) as payment_type,sm.name sm_name from salesman_transactions sst USE INDEX (idx_collDate) join  salesmen sm on (sm.id=sst.salesman) join shop_transactions st on(sst.shop_tran_id=st.id) join retailers r on(r.id=st.target_id) join unverified_retailers ur on(r.id = ur.retailer_id) where sm.id=$salesmanId AND sst.collection_date between '" . $fd . "' and '" . $ft . "' AND st.confirm_flag != '1') " . "UNION " . "(SELECT st.id,r.name,r.mobile,ur.shopname,st.amount, trim(st.amount) as collection_amount,trim(st.timestamp) as created,trim(st.type_flag) as payment_type,sm.name sm_name FROM shop_transactions st JOIN retailers r ON (st.target_id = r.id) join unverified_retailers ur on(r.id = ur.retailer_id) JOIN salesmen sm ON (r.maint_salesman = sm.id) WHERE sm.id=$salesmanId  AND st.type = '" . SLMN_RETL_BALANCE_TRANSFER . "' AND st.confirm_flag != '1' AND st.date BETWEEN '" . $fd . "' and '" . $ft . "')) a GROUP BY id");

        $retAcquired = $this->Slaves->query("select count(retailers.mobile) as num, salesmen.name,salesmen.id from salesmen left join retailers on (retailers.salesman=salesmen.id)  where salesman=" . $salesmanId);
        $retAcquiredDtRng = $this->Slaves->query("select count(retailers.mobile) as num, salesmen.name,salesmen.id from salesmen left join retailers on (retailers.salesman=salesmen.id) WHERE date(retailers.created) between '" . $fd . "' and '" . $ft . "' AND salesman=" . $salesmanId);
        $setupDtRng = $this->Slaves->query("select sum(sst.collection_amount) as sc,salesman from salesman_transactions sst where sst.salesman=$salesmanId and sst.payment_type = 1 AND Date(sst.collection_date) between '" . $fd . "' and '" . $ft . "'");
        $setup = $this->Slaves->query("select sum(sst.collection_amount) as sc,salesman from salesman_transactions sst where sst.salesman=$salesmanId AND sst.payment_type = 1 ");
    }
    else{
        $salesResult = $this->Slaves->query("SELECT * FROM ((select st.id,r.name,r.mobile,ur.shopname,st.amount,trim(sst.collection_amount) as collection_amount,trim(sst.created) as created,trim(sst.payment_type) as payment_type,sm.name sm_name from salesman_transactions sst USE INDEX (idx_collDate) join  salesmen sm on (sm.id=sst.salesman) join shop_transactions st on(sst.shop_tran_id=st.id) join retailers r on (r.id=st.target_id) join unverified_retailers ur on (r.id = ur.retailer_id) where r.parent_id=$distId and sst.collection_date between '" . $fd . "' and '" . $ft . "' AND st.confirm_flag != '1')
                            UNION
                            (SELECT st.id,r.name,r.mobile,ur.shopname,st.amount, trim(st.amount) as collection_amount,trim(st.timestamp) as created,trim(st.type_flag) as payment_type,sm.name sm_name FROM shop_transactions st JOIN retailers r ON (st.target_id = r.id) join unverified_retailers ur on(r.id = ur.retailer_id) JOIN salesmen sm ON (r.maint_salesman = sm.id) WHERE r.parent_id = '$distId' AND st.type = '" . SLMN_RETL_BALANCE_TRANSFER . "' AND st.confirm_flag != '1' AND st.date BETWEEN '" . $fd . "' and '" . $ft . "')) a GROUP BY id");

        $retAcquiredDtRng = $this->Slaves->query("select count(retailers.mobile) as num, salesmen.name,salesmen.id from salesmen left join retailers on (retailers.salesman=salesmen.id AND Date(retailers.created) between '" . $fd . "' and '" . $ft . "') WHERE retailers.parent_id = $distId AND salesmen.active_flag = 1 group by salesmen.id");
        $retAcquired = $this->Slaves->query("select count(retailers.mobile) as num, salesmen.name,salesmen.id from salesmen left join retailers on (retailers.salesman=salesmen.id) WHERE retailers.parent_id = $distId AND salesmen.active_flag = 1 group by salesmen.id");
        $setupDtRng = $this->Slaves->query("select sum(sst.collection_amount) as sc,salesman from salesman_transactions sst inner join salesmen ON (salesmen.id = sst.salesman AND salesmen.dist_id=$distId) where Date(sst.collection_date) between '" . $fd . "' and '" . $ft . "' AND sst.payment_type = 1 group by sst.salesman");
        $setup = $this->Slaves->query("select sum(sst.collection_amount) as sc,salesman from salesman_transactions sst inner join salesmen ON (salesmen.id = sst.salesman AND salesmen.dist_id=$distId) WHERE sst.payment_type = 1 group by sst.salesman");
    }

    /** IMP DATA ADDED : START**/
    $ret_mobiles = array_map(function($element){
        return $element['a']['mobile'];
    },$salesResult);

    $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);
    foreach ($salesResult as $key => $retailer) {
        $salesResult[$key]['a']['shopname'] = $imp_data[$retailer['a']['mobile']]['imp']['shop_est_name'];
    }
    /** IMP DATA ADDED : END**/

    $this->set('salesResult', $salesResult);
    $this->set('retAcquiredDtRng', $retAcquiredDtRng);
    $this->set('retAcquired', $retAcquired);
    $setup_date = array();
    foreach($setupDtRng as $s){
        $id = $s['sst']['salesman'];
        $setup_date[$id] = $s['0']['sc'];
    }
    $setup_all = array();
    foreach($setup as $s){
        $id = $s['sst']['salesman'];
        $setup_all[$id] = $s['0']['sc'];
    }
    $this->set('setupDtRng', $setup_date);
    $this->set('setup', $setup_all);
}

function tranDate($retMobile, $flag = null){
    $retailerIdResult = $this->Slaves->query("select id from retailers where mobile='" . $retMobile . "'");
    $retId = $retailerIdResult['0']['retailers']['id'];

    if(isset($_REQUEST['from'])){
        $frm = $_REQUEST['from'];
        $to = $_REQUEST['to'];
    }
    if(empty($frm)) $frm = date('d-m-Y');
    if(empty($to)) $to = date('d-m-Y');

    $fdarr = explode("-", $frm);
    $tdarr = explode("-", $to);

    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    if($flag == 0) // search retailer transactions by date , not by status
{
        $reversalInProcessResults = $this->Slaves->query("SELECT v.company,s.name,r.name,r.id,r.mobile,p.name,va.mobile, va.txn_id, va.amount, va.status, va.timestamp
	 				from vendors_activations va join retailers r on(va.retailer_id=r.id) join products p on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id) join services s on (p.service_id=s.id)
	 				where va.retailer_id=$retId and va.date between '" . $fd . "' and '" . $ft . "' order by va.id desc");
    }
    else // search retailer transaction having status = reversal in process.
        $reversalInProcessResults = $this->Slaves->query("SELECT v.company,s.name,r.name,r.id,r.mobile,p.name,va.mobile, va.txn_id, va.amount, va.status, va.timestamp
	 			from vendors_activations va join retailers r on(va.retailer_id=r.id) join products p on(p.id=va.product_id) join vendors v on(v.id=va.vendor_id) join services s on (p.service_id=s.id)
	 			where r.toshow = 1 AND va.status='" . TRANS_REVERSE_PENDING . "' and va.retailer_id=$retId and va.date between '" . $fd . "' and '" . $ft . "' order by va.id desc");
    $this->set('flag', $flag);
    $this->set('reversalInProcess', $reversalInProcessResults);
}

function modemRequest(){
    $response = array();
    if(empty($_SESSION['Auth'])){
        $response = array('status'=>'failure', 'errno'=>'0', 'data'=>$this->Shop->errors(0));
    }
    else{
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
        $vendor = isset($_REQUEST['vendor']) ? $_REQUEST['vendor'] : "";
        $device = isset($_REQUEST['device']) ? $_REQUEST['device'] : "";
        $ret = "";
        $mdmArr = array();
        if($type == 1){ // to send SMS
            $mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : "";
            $msg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : "";
            $adm = "query=command&type=1&device=$device&mobile=$mobile&msg=" . urlencode($msg); // create query to send SMS // @TODO
            $mdmArr['query'] = "command";
            $mdmArr['type'] = "1";
            $mdmArr['device'] = $device;
            $mdmArr['mobile'] = $mobile;
            $mdmArr['msg'] = urlencode($msg);
            // $ret = $this->Shop->modemRequest($adm,$vendor);// @TODO
        }
        else if($type == 2){ // to execute Command
            $cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : "";
            $command = isset($_REQUEST['command']) ? $_REQUEST['command'] : "";
            $wait = isset($_REQUEST['wait']) ? $_REQUEST['wait'] : "";
            $adm = "query=command&type=2&device=$device&command=$cmd&time=$wait"; // create query to run AT Command // @TODO
            $mdmArr['query'] = "command";
            $mdmArr['type'] = "2";
            $mdmArr['device'] = $device;
            $mdmArr['command'] = $cmd;
            $mdmArr['time'] = $wait;
            // $ret = $this->Shop->modemRequest($adm,$vendor);
        }
        else if($type == 3){ // to USSD Command
            $cmd = isset($_REQUEST['ussd']) ? $_REQUEST['ussd'] : "";
            $command = isset($_REQUEST['command']) ? $_REQUEST['command'] : "";
            $wait = isset($_REQUEST['wait']) ? $_REQUEST['wait'] : "";
            $adm = "query=command&type=3&device=$device&command=$cmd&time=$wait"; // create query to run AT Command // @TODO
            $mdmArr['query'] = "command";
            $mdmArr['type'] = "3";
            $mdmArr['device'] = $device;
            $mdmArr['command'] = $cmd;
            $mdmArr['time'] = $wait;
            // $ret = $this->Shop->modemRequest($adm,$vendor);
        }
        else if($type == 4){ // to Reset Bus Devide Command

            $device = isset($_REQUEST['device']) ? $_REQUEST['device'] : "";
            $adm = "query=command&type=4&device=$device"; // create query to run device reset command // @TODO
            $mdmArr['query'] = "command";
            $mdmArr['type'] = "4";
            $mdmArr['device'] = $device;
            // $ret = $this->Shop->modemRequest($adm,$vendor);
        }
        else if($type == 5){ // to Reboot System

            $adm = "query=command&type=5&device=0"; // create query to run Reboot Command // @TODO
            $mdmArr['query'] = "command";
            $mdmArr['type'] = "5";
            // $ret = $this->Shop->modemRequest($adm,$vendor);
        }
        else if($type == 6){ // to insert new device

            $adm = "query=command&type=6&dev_act_flag=" . $_REQUEST['dev_act_flag'] . "&dev_balance=" . $_REQUEST['dev_balance'] . "&dev_commission=" . $_REQUEST['dev_commission'] . "&dev_mobile=" . $_REQUEST['dev_mobile'] . "&dev_opr_id=" . $_REQUEST['dev_opr_id'] . "&dev_opr_name=" . $_REQUEST['dev_opr_name'] . "&dev_par_bal=" . $_REQUEST['dev_par_bal'] . "&dev_pin=" . $_REQUEST['dev_pin'] . "&dev_rch_flag=" . $_REQUEST['dev_rch_flag'] . "&dev_sim_id=" . $_REQUEST['dev_sim_id'] . "&dev_type_id=" . $_REQUEST['dev_type_id'] . "&dev_vendor_nm=" . $_REQUEST['dev_vendor_nm'];
            $mdmArr['query'] = "command";
            $mdmArr['type'] = "6";
            $mdmArr['dev_balance'] = $_REQUEST['dev_balance'];
            $mdmArr['dev_commission'] = $_REQUEST['dev_commission'];
            $mdmArr['dev_mobile'] = $_REQUEST['dev_mobile'];
            $mdmArr['dev_opr_id'] = $_REQUEST['dev_opr_id'];
            $mdmArr['dev_opr_name'] = $_REQUEST['dev_opr_name'];
            $mdmArr['dev_par_bal'] = $_REQUEST['dev_par_bal'];
            $mdmArr['dev_pin'] = $_REQUEST['dev_pin'];
            $mdmArr['dev_sim_id'] = $_REQUEST['dev_sim_id'];
            $mdmArr['dev_type_id'] = $_REQUEST['dev_type_id'];
            $mdmArr['dev_vendor_nm'] = $_REQUEST['dev_vendor_nm'];
        }
        else if($type == 7){ // to Show-Hide Sim
            $date = date("Y-m-d H:i:s");
            // echo $date;
            // query to obtain last transaction timestamp
            $query = "SELECT last  FROM `devices_data` WHERE `device_id` = $device AND `vendor_id` = $vendor AND sale > 0 order by last desc limit 1";
            $lasttxnquery = $this->Slaves->query($query);
            $last = $lasttxnquery[0]['devices_data']['last'];
            $lastdate = strtotime($last);
            $curdate = strtotime($date);
            $diff = round(($curdate - $lastdate) / (60 * 60 * 24));
            $opr_id = $this->params['url']['opr_id'];
            if($diff > 10):
                $adm = "query=simhide&device=$device&opr_id=$opr_id"; // create query to run Reboot Command // @TODO
                $mdmArr['query'] = "simhide";
                $mdmArr['type'] = "7";
                $mdmArr['device'] = $device;
                $mdmArr['opr_id'] = $opr_id;
                // $mdmArr['flag'] = $_REQUEST['flag'];
                $ret = $this->Shop->modemRequest($adm, $vendor);
                $ret = $ret['data'];
                echo $ret;
                die();

            else:
                echo "failure";
                die();
            endif;
        }
        else{ // wrong type
            $response = array('status'=>'failure', 'errno'=>'2', 'data'=>$this->Shop->errors(2));
        }

        if( ! isset($response['status'])){
            // Make an entry in modem_request_log

            $this->data['ModemRequestLog']['input'] = json_encode($mdmArr);
            $this->data['ModemRequestLog']['output'] = "";
            $this->data['ModemRequestLog']['vendor'] = $vendor;
            $this->data['ModemRequestLog']['created'] = date('Y-m-d H:i:s');
            $this->data['ModemRequestLog']['modified'] = date('Y-m-d H:i:s');

            $this->ModemRequestLog->create();
            if($this->ModemRequestLog->save($this->data)){
                $id = $this->ModemRequestLog->getInsertID();
                $adm = $adm . "&request=$id";
                $ret = $this->Shop->modemRequest($adm, $vendor);
            }
            if(empty($ret)) $response = array('status'=>'failure', 'errno'=>'2', 'data'=>'Device Busy');
            else $response = array('status'=>'success', 'data'=>$ret);
        }
    }

    echo json_encode($response);
    die();
    $this->autoRender = false;
}

function request(){
    $data1 = $this->Slaves->query("select count(dd.id) as ct,dd.opr_id,products.name from devices_data as dd inner join vendors ON (vendors.id = dd.vendor_id) inner join products ON (products.id = dd.opr_id) inner join vendors_commissions as vc ON (vc.vendor_id = dd.vendor_id AND vc.product_id=dd.opr_id) where sync_date = '" . date('Y-m-d') . "' and vc.oprDown = 0 and dd.block = '0' AND dd.stop_flag = 0 and dd.device_num > 0 AND dd.active_flag = 1 AND dd.balance > 10 group by dd.opr_id");

    $data2 = $this->Slaves->query("SELECT count(vendors_activations.id) as ct,vendors.update_flag,vendors_activations.product_id,vendors_activations.timestamp FROM `vendors_activations` inner join vendors ON (vendors.id = vendor_id) WHERE date='" . date('Y-m-d') . "' AND timestamp >= '" . date('Y-m-d H:i:s', strtotime('-5 minutes')) . "' group by vendors.update_flag,vendors_activations.product_id,minute(vendors_activations.timestamp)");

    $requests = array();

    foreach($data1 as $dt){
        $prod = $dt['dd']['opr_id'];
        $requests[$prod]['devices'] = $dt[0]['ct'];
        $requests[$prod]['name'] = $dt['products']['name'];
    }

    foreach($data2 as $dt){
        $prod = $dt['vendors_activations']['product_id'];
        $prod = $this->Shop->getParentProd($prod);
        $minute = date('Y-m-d H:i', strtotime($dt['vendors_activations']['timestamp']));

        if(isset($requests[$prod])){
            if( ! isset($requests[$prod][$minute])) $requests[$prod][$minute] = array('api_txns'=>0, 'modem_txns'=>0);

            $requests[$prod][$minute]['api_txns'] = ($dt['vendors']['update_flag'] == 0) ? $dt['0']['ct'] + $requests[$prod][$minute]['api_txns'] : $requests[$prod][$minute]['api_txns'];
            $requests[$prod][$minute]['modem_txns'] = ($dt['vendors']['update_flag'] == 1) ? $dt['0']['ct'] + $requests[$prod][$minute]['modem_txns'] : $requests[$prod][$minute]['modem_txns'];
        }
    }

    // $this->set('devices',$data1);
    $this->set('requests', $requests);

    // $this->printArray($requests);
    // $this->autoRender = false;
}

function test(){
    echo "1";
    exit();
}

/*
 * function retList($distId=null, $retId=null){
 * if($this->Session->read('Auth.User.group_id') !=ADMIN && $this->Session->read('Auth.User.id') != 1)
 * $this->redirect('/shops/view');
 * $verify_flag = isset($_GET['verify_flag']) ? $_GET['verify_flag'] : 2;
 * $verify_query = "";
 * if($verify_flag === "0" || in_array($verify_flag, array(1, 2))){
 * $verify_query = " and verify_flag = $verify_flag";
 * $this->set("verify_flag", $verify_flag);
 * }
 *
 * $query = "";
 * $query1 = "";
 * // if($distId == null) $distId = 1;
 *
 * $this->set('distId', $distId);
 * // $this->set('sid',$smId);
 * if($distId)
 * $query = " AND parent_id = $distId";
 *
 * if ($retId != null) {
 * $this->Retailer->query("UPDATE retailers SET verify_flag = '1', modified = '".date('Y-m-d H:i:s')."' WHERE id = $retId");
 * }
 * $retilerData=$this->Slaves->query("select * from
 * (select retailers.block_flag,retailers.area,retailers.address,retailers.pin,retailers.id,retailers.name,
 * retailers.shopname,retailers.mobile,retailers.parent_id,retailers.verify_flag,retailers.balance,
 * Date(retailers.created) as created,user_profile.latitude, user_profile.longitude, user_profile.device_type,
 * user_profile.updated,locator_area.name AS areaname, locator_city.name AS cityname, locator_state.name AS statename,
 * locator_area.city_id, locator_city.state_id
 * from retailers
 * LEFT JOIN user_profile ON (user_profile.user_id = retailers.user_id and user_profile.device_type='online')
 * LEFT JOIN locator_area ON locator_area.id = retailers.area_id
 * LEFT JOIN locator_city ON locator_city.id = locator_area.city_id
 * LEFT JOIN locator_state ON locator_state.id = locator_city.state_id
 * where 1 $query $verify_query
 * order By
 * case when user_profile.device_type = 'online' then 1 else 2 end,
 * user_profile.updated desc) as retailers
 * group by retailers.id");
 *
 * $distList=$this->Slaves->query("select distributors.id,distributors.company,users.mobile from distributors,users WHERE users.id =distributors.user_id order by company");
 *
 * $this->set('distList',$distList);
 * $distarray = array();
 * foreach ($distList as $key){
 * $distarray[$key['distributors']['id']] = $key['users']['mobile'];
 * }
 *
 * $this->set('distMobileNumber', $distarray);
 * $averageSale = array();
 * $retailerarray = array();
 *
 * $rl_query = "";
 * if($distId)
 * $rl_query = " AND retailers.parent_id = $distId ";
 * $averageResult = $this->Slaves->query("SELECT avg(sale) as avg_ret, retailer_id
 * from retailers_logs,retailers
 * WHERE retailers.id = retailers_logs.retailer_id $rl_query
 * group by retailer_id order by avg_ret desc");
 *
 * // $averageResult = $this->Retailer->query("SELECT avg(sale) as avg_ret, retailer_id from retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id = $distId AND retailers_logs.date between CURDATE() - INTERVAL 5 DAY and CURDATE() group by retailer_id order by avg_ret desc");
 * foreach ($retilerData as $retkey) {
 * $retailerarray[$retkey['retailers']['id']] = $retkey;
 * }
 * /// var_dump($retailerarray);
 * $data = array();
 * if (!empty($averageResult) && isset($averageResult)) {
 * foreach ($averageResult as $key) {
 * if (isset($retailerarray[$key['retailers_logs']['retailer_id']])) {
 * $data[$key['retailers_logs']['retailer_id']] = array($key[0]['avg_ret'],$retailerarray[$key['retailers_logs']['retailer_id']]);
 * }
 * }
 * }
 * $this->set('retList',$data);
 * }
 */
function leads($recs = 100){
    $this->layout = "plain";
    //if($this->RequestHandler->isPost()){
        $frm = $_POST['from'];
        $to = $_POST['to'];
        $interest   = $_POST['interest'];
        if(!isset($frm)){
            $frm = date('d-m-Y');
        }
        if(!isset($to)){
            $to = date('d-m-Y');
        }
        if(!isset($interest)){
            $interest = 2;
        }
        $leadstatefltr = $_POST['leadstate'];
        $page = isset($_POST['download']) ? $_POST['download'] : "";
        $fromdate = explode("-", $frm);
        $todate = explode("-", $to);
        $fd = $fromdate[2] . "-" . $fromdate[1] . "-" . $fromdate[0];
        $ft = $todate[2] . "-" . $todate[1] . "-" . $todate[0];
        //For filtering the value through search box
        $query_where = "";
        if($_POST['search'] != ''){
            $query_where .= " and (name LIKE '%" . $_POST['search'] . "%' or email LIKE '%" . $_POST['search'] . "%' or phone LIKE '%" . $_POST['search'] . "%' or shop_name LIKE '%" . $_POST['search']."%')";
        }
        if($_POST['city'] != ''){
            $query_where .= " and city = '" . $_POST['city'] . "' ";
        }
        if($_POST['state'] != ''){
            $query_where .= "and state = '" . $_POST['state'] . "' ";
        }
        if($interest != 'All'){
            $query_where .= " and interest = '" . $interest . "' ";
        }
        if($leadstatefltr != 'All'){
            $query_where .= " and lead_state = '".$leadstatefltr."'";
        }
        //for specifying lead source
        $leadSource = $this->Slaves->query("Select id,lead_values from lead_attributes_values where type_id = '2'");
        //for specifying lead state
        $leadState = $this->Slaves->query("Select id,lead_values from lead_attributes_values where type_id = '1'");
        //Main Query
        $leadsDataQuery = "select * from leads_new USE INDEX(idx_date) where creation_date >='" . $fd . "' AND creation_date <='" . $ft . "' $query_where group by phone order by id desc";
        $leadsData = $this->paginate_query($leadsDataQuery,$recs);
        foreach($leadsData as $k=>$ld){
            $retailer = $this->User->query("select * from retailers where mobile = '" . $ld['leads_new']['phone'] . "'");
            if(isset($retailer['0'])) $leadsData[$k]['leads_new']['is_retailer'] = "Yes";
            else $leadsData[$k]['leads_new']['is_retailer'] = "No";
        }
        //for specifying lead status
        $leadsStatus = $this->Slaves->query("Select * from lead_attributes_values where type_id = '3' and parent_id = '0'");
        //for specifying lead substatus
        $leadSubstatus = $this->Slaves->query("Select * from lead_attributes_values where  parent_id != '0'");
        //for getting Comment
        $leadid = array();
        $i = 0;
        foreach($leadsData as $leadId){
            $leadid[$i] = $leadId['leads_new']['id'];
            $i++;
        }
        $leadIdval = implode(',',$leadid);
            $leadComment   = $this->Slaves->query("SELECT *
                                                    FROM lead_comments USE INDEX(idx_date) WHERE created_at IN (
                                                      SELECT MAX(created_at)
                                                        FROM lead_comments USE INDEX(idx_date) WHERE lead_id IN($leadIdval)GROUP BY lead_id)
                                                        ORDER BY lead_id ASC , created_at DESC");
         $leadComm = array();
        foreach($leadComment as $leadC){
         $leadComm[$leadC['lead_comments']['lead_id']] = $leadC['lead_comments']['comment'];
        }

        //end;
        if($page == 'download'){
            $this->set('page', $page);
            App::import('Helper', 'csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();

            $line = array('Row', 'Name', 'Email', 'city', 'Message', 'Phone', 'timestamp', 'Comment', 'Required by', 'Interest', 'Remark', 'Status', 'Agent name', 'Is_retailer', 'Follow up');
            $csv->addRow($line);
            $mapRemark = array(1=>'Interested', 2=>'Not Contactable', 3=>'Fake Lead', 4=>'Not Interested');
            $i = 1;
            foreach($leadsData as $val):
                if($val['leads_new']['status'] == '0'){
                    $status = "Open";
                }
                else{
                    $status = "Closed";
                }

                $line = array($i, $val['leads_new']['name'], $val['leads_new']['email'], $val['leads_new']['city'], $val['leads_new']['messages'], $val['leads']['phone'], $val['leads_new']['timestamp'], $val['leads_new']['comment'], $val['leads_new']['req_by'], $val['leads_new']['interest'], $mapRemark[$val[0]['remark']], $status,
                        $val['leads_new']['agentname'], $val['leads_new']['is_retailer'], $val['leads_new']['followup_date']);

                $csv->addRow($line);
                $i ++ ;
            endforeach
            ;

            echo $csv->render("leads_" . $frm . "_" . $to . ".csv");
        }
        $this->set('leadData', $leadsData);
        $this->set('fromdate', $frm);
        $this->set('todate', $to);
        $this->set('interest', $interest);
        $this->set('leadstatefltr',$leadstatefltr);
        $this->set('leadState',$leadState);
        $this->set('leadsStatus',$leadsStatus);
        $this->set('leadSubstatus',$leadSubstatus);
        $this->set('leadSource',$leadSource);
        $this->set('leadComment',$leadComment);
        $this->set('leadComm',$leadComm);
        $this->set('recs', $recs);
        if($this->RequestHandler->isAjax()){

            $idUp           = trim($_POST["id"]);
            $leadstateUp    = trim($_POST["leadstate"]);
            $leadcampUp     = trim($_POST["camp"]);
            $leadstatusUp   = trim($_POST['status']);
            $leadsubstUp    = trim($_POST['substatus']);
            $commUp         = trim($_POST["comm"]);
            $followremUp    = trim($_POST["remarks"]);
            $followdateUP   = trim($_POST["followup"]);
            $created_at     = date('Y-m-d H:i:s');
            $followdate     = explode("-", $followdateUP);
            $interstchg     = trim($_POST["change"]);
            $followupd = $followdate[2] . "-" . $followdate[1] . "-" . $followdate[0];
            //For Updating Leads_new table
            if(isset($idUp) &&  !empty($idUp)) $this->Retailer->query("UPDATE leads_new SET lead_state = '$leadstateUp',"
                    . "lead_campaign ='$leadcampUp',status ='$leadstatusUp',sub_status = '$leadsubstUp',followup_date='$followupd',agent_name = '" . $this->Session->read('Auth.User.name') . "' ,"
                    . " followup_remark = '$followremUp',updated_at ='" . date('Y-m-d H:i:s') . "',interest_change = '$interstchg' WHERE id = $idUp");
            //while updating inserting in comment table
            if(isset($commUp) &&  !empty($commUp)) $this->Retailer->query("Insert into lead_comments(lead_id,comment,user_id,created_at)"
                                            . "values($idUp,'$commUp','".$this->Session->read('Auth.User.id')."','$created_at')");
            echo json_encode(array("status"=>"success", "msg"=>"Data updated successfully"));
            exit;
        }
    //}
    $cities = $this->User->query("SELECT distinct city FROM leads_new");

    $city_temp = array();
    foreach($cities as $city){
        $city_temp[] = $city['leads_new']['city'];
    }
    $this->set('cities', $city_temp);

    }
function leadStates(){
    $this->autoRender = False;
    $term = $this->params['url']['term'];
    $states = $this->User->query("SELECT distinct state FROM leads_new where state LIKE '%$term%'");
    $state_name = array();
    $i = 0;
    foreach($states as $state){
        $state_name[$i] = $state['leads_new']['state'];
        $i++;
    }
    echo json_encode($state_name);
}

function leadSubstatus(){
    $this->autoRender = False;
    $id = $this->params['form']['lstatus'];
    $sub_status = $this->Slaves->query("Select lead_values from lead_attributes_values where parent_id = '$id'");

    $status = array();
    $i = 0;
    foreach($sub_status as $sub){
        $status[$i] = $sub['lead_attributes_values']['lead_values'];
        $i++;
    }
    echo json_encode($status);
}
function changeInterest(){
    $this->autoRender = FALSE;

    $id = $this->params['form']['id'];
    $interest = $this->params['form']['interest'];

    echo $res = $this->Retailer->query("UPDATE leads SET interest = '$interest' WHERE id = $id");
}

function complainReport($frm_date = null, $to_date = null, $frm_time = null, $to_time = null){
    if($frm_date != NULL && $to_date != NULL && $frm_time != NULL && $to_time != NULL){
        $fd = $frm_date;
        $td = $to_date;
        $ft = str_replace(".", ":", $frm_time) . ":00";
        $tt = str_replace(".", ":", $to_time) . ":00";
    }
    else{
        $fd = date('Y-m-d');
        $td = date('Y-m-d');
        $ft = "00:00:00";
        $tt = "23:59:59";
    }
    $this->layout = 'sims';

    $getTurnaroundTime = $this->Slaves->query("Select vendors_commissions.tat_time,vendor_id,product_id from vendors_commissions");

    foreach($getTurnaroundTime as $tatval){
        $tatArray[$tatval['vendors_commissions']['vendor_id']][$tatval['vendors_commissions']['product_id']] = $tatval['vendors_commissions']['tat_time'];
    }

    // $transResult = $this->Slaves->query("Select COUNT(comp.id) as complaint,
    // SUM(if(comp.resolve_date='' OR comp.resolve_date IS NULL ,1,0)) as open,
    // SUM(if(comp.resolve_date!='' OR comp.resolve_date IS NOT NULL,1,0)) as closed,
    // SUM(if(vendors_activations.complaintNo IS NOT NULL AND comp.takenby = 0,1,0)) as manualreverse,
    // SUM(if(vendors_activations.complaintNo IS NULL,1,0)) as autoreverse,
    // SUM(if(comp.takenby!=0 AND vendors_activations.complaintNo!=0 AND vendors_activations.complaintNo IS NOT NULL,1,0)) as manualComplaintreverse,
    // comp.vendor_activation_id, CONCAT_WS( ' ', resolve_date,resolve_time) as resolvetime, turnaround_time,
    // users.name,
    // users.id as user_id,
    // vendors.id,
    // vendors.company,
    // vendors.update_flag,
    // products.name,
    // vendors_activations.product_id,
    // vendors_activations.vendor_id
    // FROM complaints as comp
    // FORCE INDEX (idx_in_date)
    // LEFT JOIN vendors_activations
    // ON comp.vendor_activation_id = vendors_activations.id
    // LEFT JOIN users
    // ON users.id = comp.closedby
    // LEFT JOIN vendors on vendors.id = vendors_activations.vendor_id
    // LEFT JOIN products ON vendors_activations.product_id = products.id
    // WHERE comp.in_date >= '$fd' AND comp.in_date <= '$td' AND comp.in_time >= '$ft' AND comp.in_time <= '$tt'
    // GROUP BY comp.id"
    // );

    $transRes = $this->Slaves->query("Select COUNT(comp.id) as complaint,
            SUM(if(comp.resolve_date='' OR comp.resolve_date IS NULL ,1,0)) as open,
            SUM(if(comp.resolve_date!='' OR comp.resolve_date IS NOT NULL,1,0)) as closed,
            SUM(if(vendors_activations.cc_userid IS NOT NULL AND  comp.takenby = 0,1,0)) as manualreverse,
            SUM(if(vendors_activations.cc_userid IS NULL,1,0)) as autoreverse,
            SUM(if(comp.takenby!=0 AND vendors_activations.cc_userid!=0 AND vendors_activations.cc_userid IS NOT NULL,1,0)) as manualComplaintreverse,
            comp.vendor_activation_id, CONCAT_WS( ' ', resolve_date,resolve_time) as resolvetime, turnaround_time,users.name,
            users.id as user_id,vendors.id,vendors.company,vendors.update_flag,products.name,vendors_activations.txn_id,vendors_activations.product_id,vendors_activations.vendor_id
            FROM  complaints as comp
			FORCE INDEX (idx_in_date)
            LEFT JOIN vendors_activations ON comp.vendor_activation_id = vendors_activations.id
            LEFT JOIN users ON users.id = comp.closedby
            LEFT JOIN vendors on vendors.id = vendors_activations.vendor_id
            LEFT JOIN products ON vendors_activations.product_id = products.id
            WHERE comp.in_date >= '$fd' AND comp.in_date <= '$td' AND comp.in_time >= '$ft' AND comp.in_time <= '$tt'
            GROUP BY comp.id ORDER BY comp.id DESC");

    foreach($transRes as $transResTemp){
        if( ! in_array($transResTemp['vendors_activations']['txn_id'], $ref_array)){
            $transResult[] = $transResTemp;
            $ref_array[] = $transResTemp['vendors_activations']['txn_id'];
        }
    }

    $data = array();
    $totalComplaint = 0;
    $totalClosed = 0;
    $totalOpen = 0;
    $totalManualReversed = 0;
    $totalAutoReversed = 0;
    $outOfTat = 0;
    $reopen = 0;
    $reopenData = array();

    foreach($transResult as $transkey=>$transval){
        $totalComplaint += $transval[0]['complaint'];
        $totalClosed += $transval[0]['closed'];
        $totalOpen += $transval[0]['open'];
        $totalManualReversed += $transval[0]['manualreverse'];
        $totalAutoReversed += $transval[0]['autoreverse'];
        $totalmanualComplaintReversed += $transval[0]['manualComplaintreverse'];

        if( ! isset($data[$transval['users']['user_id']])){
            $data[$transval['users']['user_id']]['user']['open'] = 0;
            $data[$transval['users']['user_id']]['user']['closed'] = 0;
        }
        if( ! isset($data[$transval['vendors']['vendor_id']])){
            $data[$transval['vendors']['id']]['vendor']['open'] = 0;
            $data[$transval['vendors']['id']]['vendor']['closed'] = 0;
            $data[$transval['vendors']['id']]['vendor']['total'] = 0;
            $data[$transval['vendors']['id']]['vendor']['outoftat'] = 0;
        }
        if( ! isset($data[$transval['vendors_activations']['product_id']])){
            $data[$transval['vendors_activations']['product_id']]['product']['open'] = 0;
            $data[$transval['vendors_activations']['product_id']]['product']['closed'] = 0;
            $data[$transval['vendors_activations']['product_id']]['product']['total'] = 0;
            $data[$transval['vendors_activations']['product_id']]['product']['outoftat'] = 0;
        }
        $reopenCount[$transval['vendors']['id']][$transval['comp']['vendor_activation_id']]['vendor'][] = $transval['comp']['vendor_activation_id'];
        $reopenCount[$transval['vendors_activations']['product_id']][$transval['comp']['vendor_activation_id']]['product'][] = $transval['comp']['vendor_activation_id'];

        $data[$transval['vendors']['id']]['vendor']['open'] += isset($transval[0]['open']) ? $transval[0]['open'] : 0;

        $data[$transval['vendors']['id']]['vendor']['total'] += isset($transval[0]['complaint']) ? $transval[0]['complaint'] : 0;

        $data[$transval['vendors']['id']]['vendor']['closed'] += isset($transval[0]['closed']) ? $transval[0]['closed'] : 0;

        $data[$transval['vendors']['id']]['vendor']['name'] = $transval['vendors']['company'];

        $data[$transval['vendors']['id']]['vendor']['update_flag'] = $transval['vendors']['update_flag'];

        $data[$transval['users']['user_id']]['user']['open'] += isset($transval[0]['open']) ? $transval[0]['open'] : 0;

        $data[$transval['users']['user_id']]['user']['closed'] += isset($transval[0]['closed']) ? $transval[0]['closed'] : 0;

        $data[$transval['users']['user_id']]['user']['name'] = $transval['users']['name'];

        $data[$transval['vendors_activations']['product_id']]['product']['open'] += isset($transval[0]['open']) ? $transval[0]['open'] : 0;

        $data[$transval['vendors_activations']['product_id']]['product']['closed'] += isset($transval[0]['closed']) ? $transval[0]['closed'] : 0;

        $data[$transval['vendors_activations']['product_id']]['product']['name'] = $transval['products']['name'];

        $data[$transval['vendors_activations']['product_id']]['product']['total'] += $transval[0]['complaint'];

        if(strtotime($transval[0]['resolvetime']) > strtotime($transval['comp']['turnaround_time'])){
            $data[$transval['vendors_activations']['vendor_id']]['vendor']['outoftat'] += $transval[0]['complaint'];
            $data[$transval['vendors_activations']['product_id']]['product']['outoftat'] += $transval[0]['complaint'];
            // $data1[$transval['vendors']['id']][] = $transval;
            $outOfTat ++ ;
        }
    }

    foreach($reopenCount as $key=>$val){
        if(isset($key) &&  ! empty($key)){
            foreach($val as $k=>$v){
                if(count($v['vendor']) > 1){
                    $reopenData[$key][] = $v;
                }
                if(count($v['product']) > 1){
                    $reopenData[$key][] = $v;
                }
            }
        }
    }

    $rcount = array();
    foreach($reopenData as $rkey=>$rval){
        foreach($rval as $k=>$v){
            if(isset($v['vendor'])){
                if( ! isset($rcount[$rkey]['vendor'])){
                    $rcount[$rkey]['vendor'] = 0;
                }
                $rcount[$rkey]['vendor'] ++ ;
            }
            if(isset($v['product'])){
                if( ! isset($rcount[$rkey]['product'])){
                    $rcount[$rkey]['product'] = 0;
                }
                $rcount[$rkey]['product'] ++ ;
            }
        }
    }

    $dataArray = array();

    foreach($data as $key=>$val):

        if( ! empty($key)):

            if(isset($val['vendor'])){

                if($val['vendor']['update_flag'] == 1){

                    $dataArray['modem'][$key][] = $val['vendor'];
                }
                else{
                    $dataArray['api'][$key][] = $val['vendor'];
                }
                if(isset($rcount[$key]['vendor']) && $val['vendor']['update_flag'] == 1){

                    $dataArray['modem'][$key][0]['reopen'] = $rcount[$key]['vendor'];
                }
                else if(isset($rcount[$key]['vendor']) && $val['vendor']['update_flag'] == 0){

                    $dataArray['api'][$key][0]['reopen'] = $rcount[$key]['vendor'];
                }
            }
            if(isset($val['product'])){

                $dataArray['product'][$key][] = $val['product'];

                if(isset($rcount[$key]['product'])){

                    $dataArray['product'][$key][0]['reopen'] = $rcount[$key]['product'];
                }
            }
            if(isset($val['user'])){

                $dataArray['user'][$key][] = $val['user'];
            }

			endif;

    endforeach
    ;

    $hourWiseReport = $this->Slaves->query("SELECT count( * ) AS count,
                                                CAST(time_to_sec( timediff(CONCAT_WS( ' ', resolve_date, resolve_time ) , CONCAT_WS( ' ', in_date, in_time ) ) ) /3600
                                                AS UNSIGNED INTEGER)
                                                AS diff
                                                FROM complaints
                                                WHERE complaints.in_date >= '$fd' AND complaints.in_date <= '$td' AND complaints.in_time >= '$ft' AND complaints.in_time <= '$tt'
                                                GROUP BY diff");

    foreach($hourWiseReport as $hourkey=>$hourval){
        if($hourval[0]['diff'] <= 9){
            $dataArray['hour'][$hourval[0]['diff']] = $hourval[0]['count'];
        }
    }
    $dayWiseReport = $this->Slaves->query("SELECT count( * ) AS closedCount,DATEDIFF(resolve_date,in_date) AS days
                                                FROM complaints where resolve_date >= '$fd' AND resolve_date <= '$td' AND resolve_time >= '$ft' AND resolve_time <= '$tt'
                                                AND resolve_flag = '1'
                                                group by days");

    $opentransReport = $this->Slaves->query("SELECT count( * ) AS openCount,DATEDIFF(CURDATE(),in_date) AS days
                                                FROM complaints where in_date >= '$fd' AND in_date <= '$td' AND in_time >= '$ft' AND in_time <= '$tt'
                                                AND (resolve_date IS NULL
                                                OR resolve_date = '') and vendor_activation_id !=0
                                                group by days");

    foreach($opentransReport as $opentranskey=>$opentransval){
        $dataArray['opencount'][$opentransval[0]['days']] = $opentransval[0]['openCount'];
    }

    foreach($dayWiseReport as $daykey=>$dayval){
        $dataArray['days'][$dayval[0]['days']] = $dayval[0]['closedCount'];
    }
    $this->set('dataset', $dataArray);
    $this->set('totalComplaint', $totalComplaint);
    $this->set('totalClosed', $totalClosed);
    $this->set('totalOpen', $totalOpen);
    $this->set('totalManualReversed', $totalManualReversed);
    $this->set('totalAutoReversed', $totalAutoReversed);
    $this->set('fromDate', $fd);
    $this->set('toDate', $td);
    $this->set('fromTime', $ft);
    $this->set('toTime', $tt);
    $this->set('outoftat', $outOfTat);
    $this->set('totalManualComplaintReversed', $totalmanualComplaintReversed);
}

function reOpenDetails($type, $id, $fdate, $tdate, $ftime, $ttime){
    if(isset($fdate) && isset($tdate) && isset($ftime) && isset($ttime)){
        $fd = $fdate;
        $td = $tdate;
        $ft = str_replace('.', ':', $ftime);
        $tt = str_replace('.', ':', $ttime);
    }
    else{
        $fd = date('Y-m-d');
        $td = date('Y-m-d');
        $ft = "00:00:00";
        $tt = "23:59:59";
    }

    if($type == 'opr'){
        $query1 = "WHERE vendors_activations.product_id = '$id'
                    AND complaints.in_date between '$fd' AND '$td' AND complaints.in_time between '$ft' AND '$tt'";
    }
    else{
        $query1 = "WHERE vendors_activations.vendor_id ='$id'
                    AND complaints.in_date between '$fd' AND '$td' AND complaints.in_time between '$ft' AND '$tt'";
    }

    $query = "SELECT count(*) as reopenCount,vendors_activations.*,products.name,vendors.company " . " FROM " . " complaints INNER JOIN" . " vendors_activations " . " on complaints.vendor_activation_id = vendors_activations.id " . " inner join products on products.id = vendors_activations.product_id " . " inner join vendors on vendors.id = vendors_activations.vendor_id " . " $query1" . " group by vendors_activations.id " . " having reopenCount>1";

    $getReopenCount = $this->Slaves->query($query);
    $this->set('transDetails', $getReopenCount);
}

function ccReport(){
    $REPORT_INIT_DATE = "2015-05-16";
    $REPORT_INIT_TIMESTAMP = strtotime($REPORT_INIT_DATE); // 1431754200

    $fromDate = isset($_REQUEST['fromDate']) ? $_REQUEST['fromDate'] : date('Y-m-d');
    $toDate = isset($_REQUEST['toDate']) ? $_REQUEST['toDate'] : $fromDate;
    $days = (strtotime($toDate) - strtotime($fromDate)) / (60 * 60 * 24);
    if(strtotime($fromDate) < $REPORT_INIT_TIMESTAMP) $fromDate = $REPORT_INIT_DATE;
    if(strtotime($toDate) < strtotime($fromDate)) $toDate = $fromDate;

    if(strtotime($fromDate) < 1431754200 || strtotime($toDate) < 1431754200) if($days > 31) $fromDate = date('Y-m-d', strtotime($toDate) - (31 * 60 * 60 * 24));

    $date_range = " where cc.date between '$fromDate' and '$toDate' ";
    $date_range_c = " where c.date between '$fromDate' and '$toDate' ";

    $call_type_where = isset($_REQUEST['callTypeId']) &&  ! empty($_REQUEST['callTypeId']) ? " and c.call_type_id = " . $_REQUEST['callTypeId'] . " " : "";
    $via_where = isset($_REQUEST['via']) &&  ! empty($_REQUEST['via']) ? " and cc.medium = " . $_REQUEST['via'] . " " : "";
    $tag_where = isset($_REQUEST['tagId']) &&  ! empty($_REQUEST['tagId']) ? " and c.tag_id = " . $_REQUEST['tagId'] . " " : "";
    $user_where = isset($_REQUEST['mobile']) &&  ! empty($_REQUEST['mobile']) ? " and c.mobile = '" . $_REQUEST['mobile'] . "' " : "";
    $vendor_where = isset($_REQUEST['vendorId']) &&  ! empty($_REQUEST['vendorId']) ? " and cc.vendor_id = " . $_REQUEST['vendorId'] . " " : "";
    $product_where = isset($_REQUEST['productId']) &&  ! empty($_REQUEST['productId']) ? " and cc.product_id = " . $_REQUEST['productId'] . " " : "";
    $vendors_activations_join = $vendor_where . $product_where ? " left join vendors_activations va on va.txn_id = c.ref_code " : "";
    $comments_count_join = $via_where . $vendor_where . $product_where ? " left join comments_count cc on c.ref_code = cc.ref_code and c.date = cc.date " : "";
    $comments_join = $call_type_where . $tag_where . $user_where ? " right join comments c on c.ref_code = cc.ref_code and c.date = cc.date " : "";

    $call_type_inclusion = ""; // " and c.call_type_id in (0, 5, 6, 7, 8, 9) ";
    $where = $date_range . $call_type_where . $tag_where . $user_where . $vendor_where . $product_where . $via_where;
    $where_c = $date_range_c . $call_type_where . $tag_where . $user_where . $vendor_where . $product_where . $via_where;
    $subject = "";

    $report_users = "(1, 7, 16, 19, 21)";

    if($fromDate == $toDate){
        $fromTime = isset($_REQUEST['from_time']) ? $_REQUEST['from_time'] : "";
        $toTime = isset($_REQUEST['to_time']) ? $_REQUEST['to_time'] : "";

        $via_where_c = isset($_REQUEST['via']) &&  ! empty($_REQUEST['via']) ? " and c.medium = " . $_REQUEST['via'] . " " : "";
        $vendor_where_va = isset($_REQUEST['vendorId']) &&  ! empty($_REQUEST['vendorId']) ? " and va.vendor_id = " . $_REQUEST['vendorId'] . " " : "";
        $product_where_va = isset($_REQUEST['productId']) &&  ! empty($_REQUEST['productId']) ? " and va.product_id = " . $_REQUEST['productId'] . " " : "";
        if($fromTime and $toTime){
            $date_time_where = " $date_range_c and c.created between '" . $fromDate . " " . $fromTime . ":00:00'
    					and '" . $toDate . " " . $toTime . ":00:00' ";
            $where_time = $date_time_where . $call_type_where . $tag_where . $user_where . $vendor_where_va . $product_where_va . $via_where_c;
            $this->set('from_time', $fromTime);
            $this->set('to_time', $toTime);
        }
    }
    switch(true){
        case $call_type_where :
            $call_type = $this->Slaves->query("select ct.name from cc_call_types ct where ct.id = " . $_REQUEST['callTypeId']);
            if($call_type) $subject .= " " . $call_type[0]['ct']['name'];
            break;
        case $tag_where :
            $tag = $this->Slaves->query("select t.name from taggings t where t.id = " . $_REQUEST['tagId']);
            if($tag) $subject .= " " . $tag[0]['t']['name'];
            break;
        case $user_where :
            $user = $this->Slaves->query("select u.name from users u where u.mobile = '" . $_REQUEST['mobile'] . "'");
            if($user) $subject .= " " . $user[0]['u']['name'];
            break;
        case $vendor_where :
            $vendor = $this->Slaves->query("select v.company from vendors v where v.id = " . $_REQUEST['vendorId']);
            if($vendor) $subject .= " " . $vendor[0]['v']['company'];
            break;
        case $product_where :
            $product = $this->Slaves->query("select p.name from products p where p.id = " . $_REQUEST['productId']);
            if($product) $subject .= " " . $product[0]['p']['name'];
            break;
    }
    $this->set('subject', $subject);
    if($user_where) $this->set('user_mobile', $_REQUEST['mobile']);

    $last1Month = date('Y-m-d', strtotime('-30 days'));
    $usersList = $this->Slaves->query("select c.mobile as mobile, u.name as user
    									from comments c
										left join users u on u.mobile = c.mobile
                                                                                left join user_groups ug on u.id = ug.user_id
										where ug.group_id in $report_users and c.date > '" . $last1Month . "'
										group by c.mobile");
    $this->set('usersList', $usersList);

    if($where_time){
        $usersCallTypes = $this->Slaves->query("
    				select count(c.comments) as count, u.name as user, ct.name as call_type,
						c.mobile as mobile, c.call_type_id as call_type_id
					from comments c
					left join users u on u.mobile = c.mobile
                                        left join user_groups ug on u.id = ug.user_id
					left join cc_call_types ct on ct.id = c.call_type_id
    				$where_time
					and ug.group_id in $report_users
					group by c.mobile, c.call_type_id");
    }
    else{
        $usersCallTypes = $this->Slaves->query("select count(c.comments) as count, u.name as user, ct.name as call_type,
    				c.mobile as mobile, c.call_type_id as call_type_id
    				from comments c
    				left join users u on u.mobile = c.mobile
                                left join user_groups ug on u.id = ug.user_id
    				left join cc_call_types ct on ct.id = c.call_type_id
    				$comments_count_join
    				$where_c
    				$call_type_inclusion
    				and ug.group_id in $report_users
    				group by c.mobile, c.call_type_id");
    }
    $callTypes = $usersCallTypesCounts = array();
    $users = $call_types = array();
    foreach($usersCallTypes as $uct){
        isset($callTypes[ - 1]) or ($callTypes[ - 1]['name'] = 'Total' and $callTypes[ - 1]['count'] = 0);
        $callTypes[ - 1]['count'] += $uct['0']['count'];
        $uct['ct']['call_type'] or $uct['ct']['call_type'] = "None";
        isset($callTypes[$uct['c']['call_type_id']]) or ($callTypes[$uct['c']['call_type_id']]['name'] = $uct['ct']['call_type'] and $callTypes[$uct['c']['call_type_id']]['count'] = 0);
        $callTypes[$uct['c']['call_type_id']]['count'] += $uct['0']['count'];

        $uct['u']['name'] = $uct['u']['user'];
         ! empty($uct['u']['name']) or $uct['u']['name'] = $uct['c']['mobile'];
        isset($usersCallTypesCounts[$uct['c']['mobile']][ - 1]) or ($usersCallTypesCounts[$uct['c']['mobile']][ - 1]['name'] = $uct['u']['name'] and $usersCallTypesCounts[$uct['c']['mobile']][ - 1]['count'] = 0);
        isset($usersCallTypesCounts[$uct['c']['mobile']][$uct['c']['call_type_id']]) or $usersCallTypesCounts[$uct['c']['mobile']][$uct['c']['call_type_id']]['count'] = 0;
        $usersCallTypesCounts[$uct['c']['mobile']][$uct['c']['call_type_id']]['count'] += $uct['0']['count'];
        $usersCallTypesCounts[$uct['c']['mobile']][ - 1]['count'] += $uct['0']['count'];

        if( ! array_key_exists( - 1, $call_types)) $call_types[ - 1] =  - 1;
        $users[$uct['c']['mobile']] = $uct['c']['mobile'];
        $call_types[$uct['c']['call_type_id']] = $uct['c']['call_type_id'];
    }
    foreach($users as $u){
        foreach($call_types as $c){
            if( ! isset($usersCallTypesCounts[$u][$c])) $usersCallTypesCounts[$u][$c]['count'] = 0;
        }
    }
    $this->set('callTypes', $callTypes);
    $this->set('usersCallTypesCounts', $usersCallTypesCounts);
    $this->set('users', $users);
    $this->set('call_types', $call_types);

    if($where_time){
        $viaCounts = $this->Slaves->query("	select count(*) as count, va.api_flag as via
    				from comments c
    				left join vendors_activations va on va.txn_id = c.ref_code
    				$where_time
    				group by va.api_flag");
    }
    else{
        $viaCounts = $this->Slaves->query("	select sum(cc.count) as count, cc.medium as via
											from comments_count cc
    										$comments_join
											$where
											group by cc.medium");
    }
    foreach($viaCounts as $kvc=>$vc){
        $medium = isset($vc['cc']['via']) ? $vc['cc']['via'] : $vc['va']['via'];
        switch($medium){
            case 0 :
                $viaCounts[$kvc]['cc']['name'] = "SMS";
                break;
            case 1 :
                $viaCounts[$kvc]['cc']['name'] = "API";
                break;
            case 2 :
                $viaCounts[$kvc]['cc']['name'] = "USSD";
                break;
            case 3 :
                $viaCounts[$kvc]['cc']['name'] = "Android";
                break;
            case 4 :
                $viaCounts[$kvc]['cc']['name'] = "Partner";
                break;
            case 5 :
                $viaCounts[$kvc]['cc']['name'] = "Java";
                break;
            case 6 :
                $viaCounts[$kvc]['cc']['name'] = "";
                break;
            case 7 :
                $viaCounts[$kvc]['cc']['name'] = "Windows 7";
                break;
            case 8 :
                $viaCounts[$kvc]['cc']['name'] = "Windows 8";
                break;
            case 9 :
                $viaCounts[$kvc]['cc']['name'] = "Web";
                break;
            default :
                $viaCounts[$kvc]['cc']['name'] = "None";
                break;
        }
    }
    $this->set('viaCounts', $viaCounts);

    if($where_time){
        $vendorsProductsCalls = $this->Slaves->query("
					select va.vendor_id as vendor_id, va.product_id as product_id, count(*) as count,
						v.company as vendor, p.name as product
					from comments c
					left join vendors_activations va on va.txn_id = c.ref_code
					left join vendors v on v.id = va.vendor_id
					left join products p on p.id = va.product_id
					$where_time
					group by va.vendor_id, va.product_id");
    }
    else{
        $vendorsProductsCalls = $this->Slaves->query("	select cc.vendor_id as vendor_id, cc.product_id as product_id,
														sum(cc.count) as count, v.company as vendor, p.name as product
														from comments_count cc
														left join vendors v on v.id = cc.vendor_id
														left join products p on p.id = cc.product_id
														$comments_join
														$where
														group by cc.vendor_id, cc.product_id");
    }
    $vendor_ids = $product_ids = array();
    isset($vendorsProducts[ - 1][ - 1]) or ($vendorsProducts[ - 1][ - 1]['name'] = 'Total' and $vendorsProducts[ - 1][ - 1]['count'] = 0);
    foreach($vendorsProductsCalls as $vp){
        $vendorsProducts[ - 1][ - 1]['count'] += $vp['0']['count'];

        isset($vp['va']['product_id']) && $vp['cc']['product_id'] = $vp['va']['product_id'];
        isset($vp['va']['vendor_id']) && $vp['cc']['vendor_id'] = $vp['va']['vendor_id'];

        isset($vendorsProducts[ - 1][$vp['cc']['product_id']]) or ($vendorsProducts[ - 1][$vp['cc']['product_id']]['name'] = $vp['p']['product'] and $vendorsProducts[ - 1][$vp['cc']['product_id']]['count'] = 0);
        $vendorsProducts[ - 1][$vp['cc']['product_id']]['count'] += $vp['0']['count'];

        isset($vendorsProducts[$vp['cc']['vendor_id']][ - 1]) or ($vendorsProducts[$vp['cc']['vendor_id']][ - 1]['name'] = $vp['v']['vendor'] and $vendorsProducts[$vp['cc']['vendor_id']][ - 1]['count'] = 0);
        $vendorsProducts[$vp['cc']['vendor_id']][ - 1]['count'] += $vp['0']['count'];

        isset($vendorsProducts[$vp['cc']['vendor_id']][$vp['cc']['product_id']]) or $vendorsProducts[$vp['cc']['vendor_id']][$vp['cc']['product_id']]['count'] = 0;
        $vendorsProducts[$vp['cc']['vendor_id']][$vp['cc']['product_id']]['count'] += $vp['0']['count'];

        $vendor_ids[$vp['cc']['vendor_id']] = $vp['cc']['vendor_id'];
        $product_ids[$vp['cc']['product_id']] = $vp['cc']['product_id'];
    }
    foreach($vendor_ids as $v){
        foreach($product_ids as $p){
            if( ! isset($vendorsProducts[$v][$p])) $vendorsProducts[$v][$p]['count'] = 0;
        }
    }
    $vendor_ids[ - 1] =  - 1;
    $product_ids[ - 1] =  - 1;
    $this->set('vendors', $vendor_ids);
    $this->set('products', $product_ids);
    $this->set('vendorsProducts', $vendorsProducts);

    if($where_time){
        $tags = $this->Slaves->query("	select count(c.comments) as count, t.name as tag, t.id as tag_id, t.type as type
										from comments c
										left join taggings t on t.id = c.tag_id
										$vendors_activations_join
										$where_time
										group by c.tag_id");
    }
    else{
        $tags = $this->Slaves->query("	select count(c.comments) as count, t.name as tag, t.id as tag_id, t.type as type
					from comments c
					left join taggings t on t.id = c.tag_id
					$comments_count_join
					$where_c
					$call_type_inclusion
					group by c.tag_id");
    }
    $totalCTagsCount = $totalResTagsCount = $totalRetTagsCount = 0;
    foreach($tags as $tag){
        switch($tag['t']['type']){
            case 'Customer' :
                $totalCTagsCount += $tag['0']['count'];
                break;
            case 'Resolution' :
                $totalResTagsCount += $tag['0']['count'];
                break;
            case 'Retailer' :
                $totalRetTagsCount += $tag['0']['count'];
                break;
            case 'Online Complaint' :
                $totalOCTagsCount += $tag['0']['count'];
                break;
        }
    }
    $this->set('tags', $tags);
    $this->set('totalCTagsCount', $totalCTagsCount);
    $this->set('totalResTagsCount', $totalResTagsCount);
    $this->set('totalRetTagsCount', $totalRetTagsCount);
    $this->set('totalOCTagsCount', $totalOCTagsCount);
    $this->set('callTypeId', $_REQUEST['callTypeId']);

    $fromDate = date_format(date_create($fromDate), 'd-m-Y');
    $toDate = date_format(date_create($toDate), 'd-m-Y');
    $this->set('fromDate', $fromDate);
    $this->set('toDate', $toDate);
}

function newLead(){
    if($this->RequestHandler->isPost()){
        $name = $_REQUEST['name'];
        $shop_name = $_REQUEST['shop_name'];
        $email = $_REQUEST['email'];
        $state = $_REQUEST['state'];
        $city = $_REQUEST['city'];
        $pin_code = $_REQUEST['pin_code'];
        $fax = null;
        $messages = $_REQUEST['message'];
        $phone = $_REQUEST['phone'];
        // $comment = $_REQUEST['comment'];
        $date = date('Y-m-d');
        $timestamp = date('Y-m-d H:i:s');
//        $req_by = 'C C Leads';
//        $interest = $_REQUEST['interest'];
        $interest = ($_REQUEST['interest'] == 'Retailer')?'1':($_REQUEST['interest'] == 'Distributor'?'2':'');
        Configure::load('platform');
        $lead_states_mapping = Configure::read('lead_state_mapping');
        $lead_source_mapping = Configure::read('lead_source');
        $status = 16;
        $sub_status = 20;
        $lead_source = $lead_source_mapping[$_REQUEST['lead_source']];
        $lead_state = $lead_states_mapping[$_REQUEST['lead_source']];
        $current_business = (!empty($_REQUEST['signupcurrbusinessothers']) && ($_REQUEST['interest'] == 'Distributor'))?$_REQUEST['signupcurrbusinessothers']:(($_REQUEST['interest'] == 'Distributor')?$_REQUEST['signupcurrbusiness']:'');
        $updated_by = $_SESSION['Auth']['User']['id'];
        $agent_name = $_SESSION['Auth']['User']['name'];
//        $token = $this->General->generatePassword(10);
        $token = md5($phone);
        $lead_base_url = LEAD_BASE_URL;
        $create_lead['name'] = $name;
        $create_lead['mobile'] = $phone;
        $create_lead['email'] = $email;
        $create_lead['pin_code'] = $pin_code;
        $create_lead['shop_name'] = $shop_name;
        $create_lead['interest'] = $_REQUEST['interest'];
        $create_lead['current_business'] = $current_business;
        $create_lead['lead_source'] = $lead_source;
        $create_lead['lead_state'] = $lead_state;

        $leads_exists = $this->User->query("select * from leads_new where phone = '".$phone."'");

        if(empty($leads_exists))
        {
            $response = $this->User->query("insert into leads_new
                            (name, shop_name, email, state, city, pin_code, messages, phone, creation_date, lead_timestamp,current_business, lead_source, lead_state, interest, status, sub_status, agent_name, otp_flag, signup_count, token)
                            values ('$name', '$shop_name', '$email', '$state', '$city','$pin_code', '$messages', '$phone', '$date', '$timestamp','$current_business', '$lead_source','$lead_state', '$interest','$status','$sub_status', '$agent_name','0','1','$token')");

            $zoho_id = $this->Shop->addLeadsIntoZoho($create_lead);
            $this->Shop->setMemcache("zoho_lead_id_$phone", $zoho_id);

            if($_REQUEST['interest'] == 'Distributor')
            {
                $filename = "lead_management_".date('Ymd').".txt";
                $lead_form_url = $this->Shop->shortenurl('http://'.$lead_base_url.'/lead/index/'.$phone.'/'.$token);
                $paramdata['URL'] = $lead_form_url['id'];
                $MsgTemplate = $this->General->LoadApiBalance();
                $content =  $MsgTemplate['Lead_Application_Form_MSG'];
                $message = $this->General->ReplaceMultiWord($paramdata,$content);
                $this->General->sendMessage($phone, $message, "payone");

                $this->General->logData('/mnt/logs/'.$filename, "lead_url ".$lead_form_url['id']);

                $sub = "Distributor Application Form";
                $body = "http://pay1.in/lead/index/".$phone."/".$token."?src=email";
                $this->General->sendMails($sub,$body,$email);
            }
            $notif = "Lead reported. The sales team has been notified.";
            $error = false;
        }
        else
        {
            $notif = "Lead already exists for this number";
            $error = true;
        }

        $this->set('notif', $notif);
        $this->set('error', $error);
        $this->set('lead_source_mapping', $lead_source_mapping);
    }
}

function pullbackReport($frm = null, $to = null){
    if( ! isset($frm)) $frm = date('d-m-Y');
    if( ! isset($to)) $to = date('d-m-Y');

    $fdarr = explode("-", $frm);
    $tdarr = explode("-", $to);

    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    $result = $this->Slaves->query("SELECT vendors_activations.*, products.name, va.company,vendors.company," . " trans_pullback.pullback_by, trans_pullback.pullback_time, trans_pullback.reported_by," . " trans_pullback.timestamp, users.name from vendors_activations" . " inner join trans_pullback on vendors_activations.id = trans_pullback.vendors_activations_id" . " inner join products on products.id  = vendors_activations.product_id" . " inner join vendors on vendors.id  = vendors_activations.vendor_id" . " inner join vendors as va ON va.id = trans_pullback.vendor_id" . " left join users on users.id = trans_pullback.pullback_by" . " where trans_pullback.date>='$fd' AND trans_pullback.date<='$ft'");

    $r_c_temp = array(0);
    // $v_a_temp = array(0);
    foreach($result as $res){
        $r_c_temp[] = $res['vendors_activations']['txn_id'];
        // $v_a_temp[] = $res['vendors_activations']['id'];
    }

    $commentsResult = $this->Slaves->query("SELECT * FROM (SELECT c.ref_code, t.name from comments c
                                    left join taggings t on t.id = c.tag_id
                                    where c.ref_code IN (" . implode(',', $r_c_temp) . ") ORDER BY c.created DESC ) AS tbl GROUP BY tbl.ref_code");

    $comments = array();
    foreach($commentsResult as $cR){
        if($cR['tbl']['ref_code'] != ''){
            $comments[$cR['tbl']['ref_code']] = $cR['tbl']['name'];
        }
    }

    /**
     * ************* Code to add Tag Timing **************
     */

    // $complaints = array();
    // $complaintsResult = $this->Slaves->query("SELECT vendor_activation_id, turnaround_time FROM complaints"
    // . " WHERE vendor_activation_id IN (" . implode(',', $v_a_temp) . ")");
    // foreach($complaintsResult as $cmR) {
    // $complaints[$cmR['complaints']['vendor_activation_id']] = $cmR['complaints']['turnaround_time'];
    // }

    $this->set('comments', $comments);
    // $this->set('complaints', $complaints);
    $this->set('result', $result);
    $this->set('frm', $frm);
    $this->set('to', $to);
}

function manualReversalReport($frm = null, $to = null){
    if( ! isset($frm)) $frm = date('d-m-Y');
    if( ! isset($to)) $to = date('d-m-Y');

    $fdarr = explode("-", $frm);
    $tdarr = explode("-", $to);

    $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
    $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

    $query = " SELECT vendors_activations.*,vendors.id,vendors.company,products.name,users.name" . " FROM " . "vendors_activations" . " INNER JOIN" . " vendors ON vendors_activations.vendor_id  = vendors.id" . " INNER JOIN products ON products.id  = vendors_activations.product_id" . " INNER JOIN users ON users.id = vendors_activations.cc_userid" . " WHERE vendors_activations.date >='$fd' AND vendors_activations.date <='$ft'" . "AND (vendors_activations.cc_userid IS NOT NULL AND  vendors_activations.cc_userid!='' AND vendors_activations.cc_userid!=0)" . "AND vendors_activations.status IN ('2','3')" . "Order BY vendors_activations.timestamp desc";

    $result = $this->Slaves->query($query);
    $this->set('result', $result);
    $this->set('frm', $frm);
    $this->set('to', $to);
}

function get_server_diff_by_vendor($vendor = null, $date = null){
    $operator_list = $this->User->query("select id, name from products");
    $sims_details = $this->get_reconsile_opn_clg($date, $vendor);
    $sim_wise_sale_result = $this->get_sim_wise_sale($vendor, $date);
    $sim_reports = $this->get_sorted_sim_wise_data($sim_wise_sale_result, $sims_details, $operator_list);
    // echo "<pre>";print_r($sim_reports);echo "</pre>";
    return $sim_reports;
    // $this->autoRender=false;
}

function tagReport($tagId, $fromDate, $toDate, $executive_mobile, $time, $callTypeId = ''){
    if($time == 0){
        $time = '';
    }
    if($callTypeId != '' || $callTypeId != 0){
        $whr = "c.call_type_id = $callTypeId and";
    }

    $fromDate = date_format(date_create($fromDate), 'Y-m-d');
    $toDate = date_format(date_create($toDate), 'Y-m-d');

    if($fromDate == $toDate){
        if($time){
            $from_to = explode("_", $time);
            $from_time = $from_to[0];
            $to_time = $from_to[1];
        }
    }

    $query_where = $executive_mobile ? " and c.mobile = '$executive_mobile' " : "";
    $time_where = $from_time && $to_time ? " and c.created between '$fromDate $from_time:00:00' and '$toDate $to_time:00:00' " : "";

    if($time_where){
        $calls = $this->Slaves->query("	select r.user_id, u2.name, va.mobile, c.ref_code, v.company, p.name, va.amount, c.created,
					va.status, va.timestamp, r.mobile, r.shopname, va.api_flag
					from comments c
					left join vendors_activations va on va.txn_id = c.ref_code
					left join vendors v on v.id = va.vendor_id
					left join products p on p.id = va.product_id
					left join users u on u.id = c.users_id
					left join users u2 on u2.mobile = c.mobile
					left join retailers r on r.id = c.retailers_id
					where c.tag_id = $tagId and $whr c.date between '$fromDate' and '$toDate'
					$query_where
					$time_where
					order by c.created desc");
    }
    else{
        $calls = $this->Slaves->query("	select r.user_id, u2.name, va.mobile, c.ref_code, v.company, p.name, va.amount, c.created,
					va.status, va.timestamp, r.mobile, r.shopname, cc.medium
					from comments c
					left join comments_count cc on c.ref_code = cc.ref_code and c.date = cc.date
					left join vendors v on v.id = cc.vendor_id
					left join products p on p.id = cc.product_id
					left join users u on u.id = c.users_id
					left join vendors_activations va on va.txn_id = c.ref_code
					left join users u2 on u2.mobile = c.mobile
					left join retailers r on r.id = c.retailers_id
					where c.tag_id = $tagId and $whr c.date between '$fromDate' and '$toDate'
					$query_where
					order by c.created desc");
    }

    $temp_calls = array();
    foreach($calls as $call){
        $call['r']['user_id'] != '' && $temp_calls[] = $call['r']['user_id'];
    }

    $device = $this->Slaves->query("select * from (select user_id, device_type from user_profile" . " where user_id IN (" . implode(',', $temp_calls) . ") order by updated desc) as tb1 group by user_id");

    $device_type = array();
    foreach($device as $d_t){
        if($d_t != '') $device_type[$d_t['tb1']['user_id']] = $d_t['tb1']['device_type'];
    }
    $this->set('device_type', $device_type);

    if(count($calls) > 0){
        $tag = $this->Slaves->query("select name from taggings where id = $tagId");
        $fromDate = date_format(date_create($fromDate), 'd-m-Y');
        $toDate = date_format(date_create($toDate), 'd-m-Y');

        foreach($calls as $k=>$d){
            if($time_where) $calls[$k]['cc']['medium'] = $d['va']['api_flag'];
            $status = '';
            if($d['va']['status'] == '0'){
                $status = 'In Process';
            }
            else if($d['va']['status'] == '1'){
                $status = 'Successful';
            }
            else if($d['va']['status'] == '2'){
                $status = 'Failed';
            }
            else if($d['va']['status'] == '3'){
                $status = 'Reversed';
            }
            else if($d['va']['status'] == '4'){
                $status = 'Reversal In Process';
            }
            else if($d['va']['status'] == '5'){
                $status = 'Reversal declined';
            }
            $calls[$k]['va']['status'] = $status;
        }

        $this->set("tag", $tag);
        $this->set("from_date", $fromDate);
        $this->set("to_date", $toDate);
        $this->set("calls", $calls);
        $this->set('medium_map', $this->api_medium_map);
        $this->set('from_time', $from_time);
        $this->set('to_time', $to_time);
    }
    else
        echo "No calls were tagged";
}

function retailerRegistrationReport($fromDate, $toDate){
    $this->layout = 'sims';

    if(empty($fromDate) || empty($toDate)){
        $fromDate = $toDate = date("d-m-Y", time());
    }
    $from_date = date("Y-m-d", strtotime($fromDate));
    $to_date = date("Y-m-d", strtotime($toDate));
//    $retailers = $this->Slaves->query("select r.id, r.user_id, r.mobile, r.name, r.email, r.created, r.area, r.area_id, users.balance,
//				max(l.date) as last_sale_date, l.sale
//				from retailers r
//				left join retailers_logs l on r.id = l.retailer_id
//                                inner join users ON (users.id = r.user_id)
//				where r.created between '$from_date' and date_add('$to_date', interval 1 day)
//				and r.retailer_type = 3
//				group by r.id
//                order by r.created desc");
    $retailers = $this->Slaves->query("SELECT r.id, r.user_id, r.mobile, r.name, r.email, r.created, r.area, r.area_id, users.balance,"
				."MAX(l.date) AS last_sale_date, SUM(l.amount) AS sale "
				."FROM retailers r "
				."LEFT JOIN retailer_earning_logs l ON (r.user_id = l.ret_user_id) "
                                ."INNER JOIN users ON (users.id = r.user_id) "
				."WHERE r.created BETWEEN '$from_date' AND DATE_ADD('$to_date', INTERVAL 1 day) "
				."AND r.retailer_type = 3 "
				."GROUP BY r.id "
                                ."ORDER BY r.created DESC");

    $user_ids = array_map(function($element){
        return $element['r']['user_id'];
    },$retailers);

    /** IMP DATA ADDED : START**/
    $imp_data = $this->Shop->getUserLabelData($user_ids,2,0);
    /** IMP DATA ADDED : END**/

    foreach ($retailers as $key => $retailer) {
        if(array_key_exists($retailer['r']['user_id'],$imp_data)){
            $retailers[$key]['r']['name'] = $imp_data[$retailer['r']['user_id']]['imp']['name'];
            $retailers[$key]['r']['email'] = $imp_data[$retailer['r']['user_id']]['imp']['email_id'];
        }
    }

    $this->set('count', count($retailers));
    $this->set('retailers', $retailers);
    $this->set('fromDate', $fromDate);
    $this->set('toDate', $toDate);
}

function getProcessTime($frm_date = null, $to_date = null, $frm_time = null, $to_time = null){
    if($frm_date == null || $to_date == null || $frm_time == null || $to_time == null){
        $frm_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $frm_time = (date('H') - 1) . ':00:00';
        $to_time = (date('H')) . ':00:00';
    }
    else{
        $frm_time = str_replace('.', ':', $frm_time . '.00');
        $to_time = str_replace('.', ':', $to_time . '.00');
    }

    $frm = $frm_date . ' ' . $frm_time;
    $to = $to_date . ' ' . $to_time;

    $getModemProcessingTime = $this->Slaves->query("SELECT *,COUNT(*) as totalcount,Avg(TIME_TO_SEC(TIMEDIFF(process_time,timestamp))) AS processtime "
            . "FROM (SELECT vendors_activations.txn_id,vendors_activations.timestamp, vendors_activations.product_id,products.name,products.benchmark_failure,products.benchmark_processtime,if(tran_processtime != '0000-00-00 00:00:00',tran_processtime,min(vendors_messages.timestamp)) as process_time "
            . "FROM vendors_activations "
            . "LEFT JOIN vendors_messages ON (vendors_messages.va_tran_id=vendors_activations.txn_id AND vendors_activations.vendor_id = vendors_messages.service_vendor_id) "
            . "INNER JOIN products ON (products.id = vendors_activations.product_id) "
            . "INNER JOIN vendors ON (vendors.id = vendors_activations.vendor_id) "
            . "WHERE vendors_activations.timestamp BETWEEN '$frm' AND '$to' AND vendors_activations.date BETWEEN '$frm_date' AND '$to_date' AND vendors.update_flag='1' "
            . "GROUP BY vendors_activations.txn_id) as va GROUP BY product_id");

    $getApiProcessingTime = $this->Slaves->query("SELECT *,COUNT(*) as totalcount,Avg(TIME_TO_SEC(TIMEDIFF(process_time,timestamp))) AS processtime "
            . "FROM (SELECT vendors_activations.txn_id,vendors_activations.timestamp, vendors_activations.product_id,products.name,products.benchmark_failure,products.benchmark_processtime,if(vendors_activations.tran_processtime != '0000-00-00 00:00:00',vendors_activations.tran_processtime,MIN(vendors_messages.timestamp)) as process_time "
            . "FROM vendors_activations "
            . "LEFT JOIN vendors_messages ON (vendors_messages.va_tran_id = vendors_activations.txn_id AND vendors_activations.vendor_id = vendors_messages.service_vendor_id) "
            . "INNER JOIN products ON (products.id = vendors_activations.product_id) "
            . "INNER JOIN vendors ON (vendors.id = vendors_activations.vendor_id) "
            . "WHERE vendors_activations.timestamp BETWEEN '$frm' AND '$to' AND vendors_activations.date BETWEEN '$frm_date' AND '$to_date' AND vendors.update_flag='0' "
            . "GROUP BY vendors_activations.txn_id) as va GROUP BY product_id");

    $percentageModems = $this->Slaves->query("SELECT *,Avg(TIME_TO_SEC(TIMEDIFF(process_time,timestamp))) AS processtime FROM (SELECT vendors_activations.product_id,products.name,vendors_activations.txn_id,vendors_activations.status, vendors_activations.timestamp,if(vendors_activations.tran_processtime != '0000-00-00 00:00:00',vendors_activations.tran_processtime,MIN(vendors_messages.timestamp)) AS process_time "
                                            . "FROM vendors_activations "
                                            . "LEFT JOIN vendors_messages ON (vendors_messages.va_tran_id = vendors_activations.txn_id AND vendors_activations.vendor_id = vendors_messages.service_vendor_id) "
                                            . "INNER JOIN products ON (products.id = vendors_activations.product_id) "
                                            . "INNER JOIN vendors ON (vendors.id = vendors_activations.vendor_id) "
                                            . "WHERE vendors_activations.timestamp BETWEEN '$frm' AND '$to' AND vendors_activations.date BETWEEN '$frm_date' AND '$to_date' AND vendors.update_flag='1' "
                                            . "GROUP BY vendors_activations.txn_id) as va GROUP BY txn_id");

    $getModemFaliure = $this->Slaves->query("SELECT product_id,count(*) as totalcount FROM `vendors_activations` inner join vendors on vendors.id = vendors_activations.vendor_id WHERE vendors_activations.timestamp between '$frm' and '$to' and vendors_activations.date between '$frm_date' and '$to_date' and status IN('2','3') and vendors.update_flag = '1' group by product_id");

    $getApiFaliure = $this->Slaves->query("SELECT product_id,count(*) as totalcount FROM `vendors_activations` inner join vendors on vendors.id = vendors_activations.vendor_id WHERE vendors_activations.timestamp between '$frm' and '$to' and vendors_activations.date between '$frm_date' and '$to_date' and status IN('2','3') and vendors.update_flag = '0' group by product_id");

    $getTotalModemCount = $this->Slaves->query("SELECT product_id,count(*) as totalcount FROM `vendors_activations` inner join vendors on vendors.id = vendors_activations.vendor_id WHERE vendors_activations.timestamp between '$frm' and '$to' and vendors_activations.date between '$frm_date' and '$to_date' and vendors.update_flag = '1' group by product_id");

    $getTotalApiCount = $this->Slaves->query("SELECT product_id,count(*) as totalcount FROM `vendors_activations` inner join vendors on vendors.id = vendors_activations.vendor_id WHERE vendors_activations.timestamp between '$frm' and '$to' and vendors_activations.date between '$frm_date' and '$to_date'  and vendors.update_flag = '0' group by product_id");

    $getOverallFaliure = $this->Slaves->query("Select product_id,count(*) as totalcount FROM `vendors_activations` inner join vendors on vendors.id = vendors_activations.vendor_id WHERE vendors_activations.timestamp between '$frm' and '$to' and vendors_activations.date between '$frm_date' and '$to_date' AND vendors_activations.status IN ('2','3') group by product_id");

    $getOverallCount = $this->Slaves->query("Select product_id,count(*) as totalcount FROM `vendors_activations` inner join vendors on vendors.id = vendors_activations.vendor_id WHERE vendors_activations.timestamp between '$frm' and '$to'  and vendors_activations.date between '$frm_date' and '$to_date' group by product_id");

    $modemProcessTime = array();

    foreach($getModemFaliure as $val){

        $faliure[$val['vendors_activations']['product_id']]['modemfaliure'] = $val[0]['totalcount'];
    }
    foreach($getApiFaliure as $val){

        $faliure[$val['vendors_activations']['product_id']]['apifaliure'] = $val[0]['totalcount'];
    }

    foreach($getTotalModemCount as $val){

        $faliure[$val['vendors_activations']['product_id']]['modemcount'] = $val[0]['totalcount'];
    }
    foreach($getTotalApiCount as $val){

        $faliure[$val['vendors_activations']['product_id']]['apicount'] = $val[0]['totalcount'];
    }

    foreach($getOverallFaliure as $val){

        $faliure[$val['vendors_activations']['product_id']]['overallfaliure'] = $val[0]['totalcount'];
    }
    foreach($getOverallCount as $val){

        $faliure[$val['vendors_activations']['product_id']]['overaallcount'] = $val[0]['totalcount'];
    }

    foreach($getModemProcessingTime as $val){

        $modemProcessTime[$val['va']['product_id']]["modemprocesstime"] = array("oprname"=>$val['va']['name'], "processtime"=>$val[0]['processtime'], "totatcount"=>$val[0]['totalcount']);
        $modemProcessTime[$val['va']['product_id']][] = array("benchmark_failure"=>$val['va']['benchmark_failure'], "benchmark_processtime"=>$val['va']['benchmark_processtime']);
    }

    foreach($getApiProcessingTime as $val){

        $modemProcessTime[$val['va']['product_id']]["apiprocesstime"] = array("oprname"=>$val['va']['name'], "processtime"=>$val[0]['processtime'], "totatcount"=>$val[0]['totalcount']);
        if( ! isset($modemProcessTime[$val['va']['product_id']][0]['benchmark_failure'])){
            $modemProcessTime[$val['va']['product_id']][] = array("benchmark_failure"=>$val['va']['benchmark_failure'], "benchmark_processtime"=>$val['va']['benchmark_processtime']);
        }
    }

    $data = array();

    foreach($percentageModems as $val){

        if(isset($val[0]['processtime']) && $val['va']['status'] != '2' && $val['va']['status'] != '3'){

            if($val[0]['processtime'] <= 40){

                $data[$val['va']['product_id']]['0-40'][] = $val['va']['txn_id'];
            }
            else if($val[0]['processtime'] > 40 && $val[0]['processtime'] <= 60){

                $data[$val['va']['product_id']]['40-60'][] = $val['va']['txn_id'];
            }
            else if($val[0]['processtime'] > 60 && $val[0]['processtime'] <= 90){

                $data[$val['va']['product_id']]['60-90'][] = $val['va']['txn_id'];
            }
            else{

                $data[$val['va']['product_id']]['90-100'][] = $val['va']['txn_id'];
            }
        }
    }

    $this->set("processtime", $modemProcessTime);
    $this->set("data", $data);
    $this->set('frm', $frm_date);
    $this->set('to', $to_date);
    $this->set('frm_time', explode(':', $frm_time));
    $this->set('to_time', explode(':', $to_time));
    $this->set('faliure', $faliure);
}

function failureInfo(){
    $productArray = array();
    $datestr = '';
    $frmstr = '';
    $timerangestr = '';
    $oprStr = '';
    $status = '';
    $vendorquery = '';

    if(isset($_REQUEST['date']) &&  ! empty($_REQUEST['date'])){
        $date = $_REQUEST['date'];
        $datestr = "AND vendors_activations.date = '" . $date . "'";
    }
    if(isset($_REQUEST['frm']) && isset($_REQUEST['to'])){
        $frm = $_REQUEST['frm'];
        $to = $_REQUEST['to'];
        $frm_time = date('Y-m-d H:i:s', strtotime($date . ' ' . $frm));
        $to_time = date('Y-m-d H:i:s', strtotime($date . ' ' . $to));
        $frmstr = "AND vendors_activations.timestamp between  '$frm_time' and '$to_time'";
    }

    if(isset($_REQUEST['timerange']) &&  ! empty($_REQUEST['timerange'])){
        $timerange = $_REQUEST['timerange'];

        $time_range = explode("-", $timerange);

        if(count($time_range) > 1){

            if($time_range[0] == '90'){
                $timerangestr = "having  processtime > 90";
                $timerange = 'more than 90';
            }
            else{
                $timerangestr = "having  processtime between $time_range[0] and $time_range[1]";
            }
        }
    }

    if(isset($_REQUEST['oprId']) &&  ! empty($_REQUEST['oprId'])){

        $oprId = $_REQUEST['oprId'];

        $oprStr = "And vendors_activations.product_id= " . $oprId;
    }

    if(isset($_REQUEST['type']) &&  ! empty($_REQUEST['type'])){

        $status = " AND vendors_activations.status IN ('2','3')";

        if($_REQUEST['type'] == 'modem'){
            $vendorquery = " AND vendors.update_flag = '1' AND vendors.active_flag='1' ";
        }
        else if($_REQUEST['type'] == 'api'){
            $vendorquery = " AND vendors.update_flag = '0' AND vendors.active_flag='1' ";
        }
    }
    else{
        $status = " AND vendors_activations.status IN ('0','1')";
    }

    if(isset($_REQUEST['transtype']) &&  ! empty($_REQUEST['transtype'])){

        if($_REQUEST['transtype'] == 'modem'){

            $vendorquery = " AND vendors.update_flag = '1' AND vendors.active_flag='1' ";
        }
        else if($_REQUEST['transtype'] == 'api'){

            $vendorquery = " AND vendors.update_flag = '0' AND vendors.active_flag='1' ";
        }
    }

    $failure = $this->Slaves->query("SELECT va.product_id,va.vendor_id,vm.response,vm.status,va.txn_id,va.status,hr  as hour,va.date  as cdate" . "   from vendors_messages as vm " . "  left join vendors_activations as va on (va.txn_id = vm.va_tran_id and vm.service_vendor_id = va.vendor_id)" . " where va.date = '$date' and  va.status IN (2,3) and  va.timestamp between '$frm_time' and '$to_time' and  vm.status =  'failure' and va.product_id =" . $oprId);

    foreach($failure as $val):

        if(($val['va']['status'] == '2' || $val['va']['status'] == '3')){

            $failureArray[$val[0]['cdate']][$val[0]['hour']][] = $val;

            $failurearraydate[$val[0]['cdate']][] = $val;

            if(strpos($val['vm']['response'], 'Manual reversal') !== false){

                $val['vm']['response'] = 'Manual reversal';
            }
            else if(strpos($val['vm']['response'], '24 :: Error of connection') !== false){

                $val['vm']['response'] = 'error_connection';
            }

            if( ! isset($refcode[$val['va']['txn_id']])){

                $refcode[$val['va']['txn_id']] = $val['va']['txn_id'];

                $failureType[$val[0]['cdate']][$val[0]['hour']][$val['vm']['response']][] = $val;
            }

            // $errorType[str_replace(',','',$val['vm']['response'])] = str_replace(',','',$val['vm']['response']);

            $errorType[$val['vm']['response']] = $val['vm']['response'];
        }
    endforeach
    ;

    $alltransdata = $this->Slaves->query("SELECT *,Avg(TIME_TO_SEC(TIMEDIFF(process_time,va_timestamp))) AS processtime FROM (SELECT vendors_activations.product_id,vendors_activations.vendor_id,vendors_activations.mobile,vendors_activations.vendor_refid,vendors_activations.txn_id,vendors_activations.amount,vendors_activations.status,vendors_activations.shop_transaction_id,vendors_activations.timestamp as va_timestamp,vendors_activations.hr  as hour,vendors_activations.date  as cdate,products.name,vendors.company,products.benchmark_failure,products.benchmark_processtime,vendors_messages.internal_error_code,vendors_activations.timestamp,if(vendors_activations.tran_processtime != '0000-00-00 00:00:00',vendors_activations.tran_processtime,MIN(vendors_messages.timestamp)) AS process_time,retailers.shopname,retailers.mobile as ret_mobile,vendors.update_flag,vendors.active_flag "
            . "FROM vendors_activations "
            . "INNER JOIN products ON (products.id = vendors_activations.product_id) "
            . "LEFT JOIN retailers ON (retailers.id = vendors_activations.retailer_id) "
            . "LEFT JOIN vendors ON (vendors.id = vendors_activations.vendor_id) "
            . "LEFT JOIN vendors_messages ON (vendors_activations.txn_id = vendors_messages.va_tran_id and vendors_activations.vendor_id = vendors_messages.service_vendor_id) "
            . "WHERE vendors_activations.date = '$date' AND  vendors_activations.timestamp between '$frm_time' and '$to_time' $oprStr  $vendorquery "
            . "GROUP BY vendors_activations.txn_id) as va GROUP BY txn_id");

    $transdata = $this->Slaves->query("SELECT *,Avg(TIME_TO_SEC(TIMEDIFF(process_time,timestamp))) AS processtime "
            . "FROM (SELECT * FROM (SELECT t.*, vendors_messages.timestamp as update_time "
            . "FROM (SELECT vendors_activations.product_id,vendors_activations.mobile,vendors_activations.vendor_refid,vendors_activations.txn_id,vendors_activations.amount,vendors_activations.status,vendors_activations.shop_transaction_id,vendors_activations.timestamp,products.name,vendors.company,products.benchmark_failure,products.benchmark_processtime, vendors_messages.response, if(vendors_activations.tran_processtime != '0000-00-00 00:00:00',vendors_activations.tran_processtime,MIN(vendors_messages.timestamp)) AS process_time, retailers.shopname,retailers.mobile as retailer_mobile,mobile_numbering_area.area_name "
            . "FROM vendors_activations "
            . "INNER JOIN products ON (products.id = vendors_activations.product_id) "
            . "LEFT JOIN retailers ON (retailers.id = vendors_activations.retailer_id) "
            . "LEFT JOIN vendors on vendors.id = vendors_activations.vendor_id "
//            . "LEFT JOIN vendors_messages ON (vendors_activations.txn_id = vendors_messages.va_tran_id and vendors_activations.vendor_id = vendors_messages.service_vendor_id and vendors_messages.status='failure') "
            . "LEFT JOIN vendors_messages ON (vendors_activations.txn_id = vendors_messages.va_tran_id and vendors_activations.vendor_id = vendors_messages.service_vendor_id) "
            . "LEFT JOIN mobile_operator_area_map on (mobile_operator_area_map.number = LEFT(vendors_activations.mobile, 5)) "
            . "LEFT JOIN mobile_numbering_area on (mobile_numbering_area.area_code = mobile_operator_area_map.area) "
            . "WHERE vendors_activations.date = '$date' AND  vendors_activations.timestamp between '$frm_time' and '$to_time' $oprStr $status $vendorquery "
            . "GROUP BY vendors_activations.txn_id $timerangestr ) as t "
            . "LEFT JOIN vendors_messages ON (t.txn_id = vendors_messages.va_tran_id) "
            . "ORDER BY vendors_messages.timestamp DESC) as z GROUP BY z.txn_id) as va GROUP BY txn_id");

    $transdata_temp = array();
    $i = 0;
    foreach($transdata as $td){
        $transdata_temp[$i]['vendors_activations'] = array('product_id'=>$td['va']['product_id'], 'mobile'=>$td['va']['mobile'], 'vendor_refid'=>$td['va']['vendor_refid'], 'txn_id'=>$td['va']['txn_id'], 'amount'=>$td['va']['amount'], 'status'=>$td['va']['status'],
                'shop_transaction_id'=>$td['va']['shop_transaction_id'], 'timestamp'=>$td['va']['timestamp']);
        $transdata_temp[$i]['products'] = array('name'=>$td['va']['name'], 'benchmark_failure'=>$td['va']['benchmark_failure'], 'benchmark_processtime'=>$td['va']['benchmark_processtime']);
        $transdata_temp[$i]['vendors'] = array('company'=>$td['va']['company']);
        $transdata_temp[$i]['vendors_messages'] = array('response'=>$td['va']['response'], 'update_time'=>$td['va']['update_time']);
        $transdata_temp[$i][0] = array('processtime'=>$td[0]['processtime']);
        $transdata_temp[$i]['retailers'] = array('shopname'=>$td['va']['shopname'], 'mobile'=>$td['va']['retailer_mobile']);
        $transdata_temp[$i ++ ]['mobile_numbering_area'] = array('area_name'=>$td['va']['area_name']);
    }

    $transdata = $transdata_temp;

    if(isset($_REQUEST['timerange']) &&  ! empty($_REQUEST['timerange']) || $_REQUEST['type'] == 'all'){

        foreach($alltransdata as $val){
            // failure data

            $datearray[$val[0]['cdate']] = $val[0]['cdate'];

            $totalcount[$val[0]['cdate']][$val[0]['hour']][] = $val;

            $totalcountdate[$val[0]['cdate']][] = $val;

            $modemType[$val['va']['vendor_id']] = $val['va']['company'];

            $hourarray[$val[0]['hour']] = $val[0]['hour'];
            // success data with processtime,daywiseprocesstime
            if(($val['va']['status'] != '2' && $val['va']['status'] != '3') && ($val['va']['update_flag'] == '1') && ($val['va']['active_flag'] == '1')){

                if($val[0]['processtime'] <= 40){

                    $processstimearray[$val[0]['cdate']][$val[0]['hour']]['0-40'][] = $val['va']['txn_id'];

                    $daywiseProcesstime[$val[0]['cdate']]['0-40'][] = $val['va']['txn_id'];

                    $modemCount[$val[0]['cdate']][$val[0]['hour']][$val['va']['vendor_id']]['0-40'][] = $val['va']['txn_id'];
                }
                else if($val[0]['processtime'] > 40 && $val[0]['processtime'] <= 60){

                    $processstimearray[$val[0]['cdate']][$val[0]['hour']]['40-60'][] = $val['va']['txn_id'];

                    $daywiseProcesstime[$val[0]['cdate']]['40-60'][] = $val['va']['txn_id'];

                    $modemCount[$val[0]['cdate']][$val[0]['hour']][$val['va']['vendor_id']]['30-60'][] = $val['va']['txn_id'];
                }
                else if($val[0]['processtime'] > 60 && $val[0]['processtime'] <= 90){

                    $processstimearray[$val[0]['cdate']][$val[0]['hour']]['60-90'][] = $val['va']['txn_id'];

                    $daywiseProcesstime[$val[0]['cdate']]['60-90'][] = $val['va']['txn_id'];

                    $modemCount[$val[0]['cdate']][$val[0]['hour']][$val['va']['vendor_id']]['60-90'][] = $val['va']['txn_id'];
                }
                else{

                    $processstimearray[$val[0]['cdate']][$val[0]['hour']]['90-100'][] = $val['va']['txn_id'];

                    $daywiseProcesstime[$val[0]['cdate']]['90-100'][] = $val['va']['txn_id'];

                    $modemCount[$val[0]['cdate']][$val[0]['hour']][$val['va']['vendor_id']]['90-100'][] = $val['va']['txn_id'];
                }
            }

            if($val['va']['update_flag'] == '1' && $val['va']['active_flag'] = '1'){
                $successcount[$val[0]['cdate']][$val[0]['hour']][] = $val;

                $sucesscountdatearray[$val[0]['cdate']][] = $val;
            }
        }

        foreach($datearray as $datval){
            foreach($hourarray as $hourval){
                foreach($modemType as $modkey=>$modval){
                    $data[$datval][$hourval] = isset($failureArray[$datval][$hourval]) ? round(count($failureArray[$datval][$hourval]) / count($totalcount[$datval][$hourval]) * 100, 2) : 0;
                    $processTimedata[$datval][$hourval]['0-40'] = isset($processstimearray[$datval][$hourval]['0-40']) ? round(count($processstimearray[$datval][$hourval]['0-40']) / count($successcount[$datval][$hourval]) * 100, 2) : 0;
                    $processTimedata[$datval][$hourval]['40-60'] = isset($processstimearray[$datval][$hourval]['40-60']) ? round(count($processstimearray[$datval][$hourval]['40-60']) / count($successcount[$datval][$hourval]) * 100, 2) : 0;
                    $processTimedata[$datval][$hourval]['60-90'] = isset($processstimearray[$datval][$hourval]['60-90']) ? round(count($processstimearray[$datval][$hourval]['60-90']) / count($successcount[$datval][$hourval]) * 100, 2) : 0;
                    $processTimedata[$datval][$hourval]['90-100'] = isset($processstimearray[$datval][$hourval]['90-100']) ? round(count($processstimearray[$datval][$hourval]['90-100']) / count($successcount[$datval][$hourval]) * 100, 2) : 0;

                    if(isset($modemCount[$datval][$hourval][$modkey]['0-40'])){
                        $modemProcessTime[$datval][$hourval]['0-40'][$modval] = round(count($modemCount[$datval][$hourval][$modkey]['0-40']) / count($processstimearray[$datval][$hourval]['0-40']) * 100, 2);
                        $modemProcessTime[$datval][$hourval]['0-40']['success'] = $processTimedata[$datval][$hourval]['0-40'];
                        $modemProcessTime[$datval][$hourval]['0-40']['count'][$modval] = count($modemCount[$datval][$hourval][$modkey]['0-40']);
                    }
                    if(isset($modemCount[$datval][$hourval][$modkey]['40-60'])){

                        $modemProcessTime[$datval][$hourval]['40-60'][$modval] = round(count($modemCount[$datval][$hourval][$modkey]['40-60']) / count($processstimearray[$datval][$hourval]['40-60']) * 100, 2);
                        $modemProcessTime[$datval][$hourval]['40-60']['success'] = $processTimedata[$datval][$hourval]['40-60'];
                        $modemProcessTime[$datval][$hourval]['40-60']['count'][$modval] = count($modemCount[$datval][$hourval][$modkey]['40-60']);
                    }
                    if(isset($modemCount[$datval][$hourval][$modkey]['60-90'])){

                        $modemProcessTime[$datval][$hourval]['60-90'][$modval] = round(count($modemCount[$datval][$hourval][$modkey]['60-90']) / count($processstimearray[$datval][$hourval]['60-90']) * 100, 2);
                        $modemProcessTime[$datval][$hourval]['60-90']['success'] = $processTimedata[$datval][$hourval]['60-90'];
                        $modemProcessTime[$datval][$hourval]['60-90']['count'][$modval] = count($modemCount[$datval][$hourval][$modkey]['60-90']);
                    }
                    if(isset($modemCount[$datval][$hourval][$modkey]['90-100'])){

                        $modemProcessTime[$datval][$hourval]['90-100'][$modval] = round(count($modemCount[$datval][$hourval][$modkey]['90-100']) / count($processstimearray[$datval][$hourval]['90-100']) * 100, 2);
                        $modemProcessTime[$datval][$hourval]['90-100']['success'] = $processTimedata[$datval][$hourval]['90-100'];
                        $modemProcessTime[$datval][$hourval]['90-100']['count'][$modval] = count($modemCount[$datval][$hourval][$modkey]['90-100']);
                    }
                }
                foreach($errorType as $errkey=>$errval){
                    if(isset($failureType[$datval][$hourval][$errval])){
                        $failureTypepercentage[$datval][$hourval][$errval] = round(count($failureType[$datval][$hourval][$errval]) / count($totalcount[$datval][$hourval]) * 100, 2);

                        $failureTypepercentage[$datval][$hourval]['failure'] = $data[$datval][$hourval];
                        $failureTypepercentage[$datval][$hourval]['totalCount'] = count($totalcount[$datval][$hourval]);
                        $failureTypepercentage[$datval][$hourval]['totalFail'] = count($failureType[$datval][$hourval][$errval]);
                    }
                }
            }

            $dateWiseFailuredata[$datval][] = round(count($failurearraydate[$datval]) / count($totalcountdate[$datval]) * 100, 2);
            $failuredata[] = round(count($failurearraydate[$datval]) / count($totalcountdate[$datval]) * 100, 2);
            $totalfailuredata[$datval] = round(count($failurearraydate[$datval]) / count($totalcountdate[$datval]) * 100, 2);
            $dateWiseProcesstimedata[$datval]['0-40'] = round(count($daywiseProcesstime[$datval]['0-40']) / count($sucesscountdatearray[$datval]) * 100, 2);
            $dateWiseProcesstimedata[$datval]['40-60'] = round(count($daywiseProcesstime[$datval]['40-60']) / count($sucesscountdatearray[$datval]) * 100, 2);
            $dateWiseProcesstimedata[$datval]['60-90'] = round(count($daywiseProcesstime[$datval]['60-90']) / count($sucesscountdatearray[$datval]) * 100, 2);
            $dateWiseProcesstimedata[$datval]['90-100'] = round(count($daywiseProcesstime[$datval]['90-100']) / count($sucesscountdatearray[$datval]) * 100, 2);
        }

        foreach($data as $key=>$val){
            foreach($val as $k=>$v){
                $data1[$key]['name'] = $key;
                $data1[$key]['data'][] = $v;
                $processarray[$key]['name'] = $key;
                $processarray[$key]['data']['0-40'][] = $processTimedata[$key][$k]['0-40'];
                $processarray[$key]['data']['40-60'][] = $processTimedata[$key][$k]['40-60'];
                $processarray[$key]['data']['60-90'][] = $processTimedata[$key][$k]['60-90'];
                $processarray[$key]['data']['90-100'][] = $processTimedata[$key][$k]['90-100'];
                $dateprocesstime[$key]['name'] = $key;
                $dateprocesstime[$key]['data']['0'] = $dateWiseProcesstimedata[$key]['0-40'];
                $dateprocesstime[$key]['data']['1'] = $dateWiseProcesstimedata[$key]['40-60'];
                $dateprocesstime[$key]['data']['2'] = $dateWiseProcesstimedata[$key]['60-90'];
                $dateprocesstime[$key]['data']['3'] = $dateWiseProcesstimedata[$key]['90-100'];
            }
        }

        foreach($data1 as $val){
            $data2[] = $val;
        }

        $data6 = array();
        foreach($processarray as $val){
            $data3['0-40'][] = array("name"=>$val['name'], "data"=>$val['data']['0-40']);
            $data3['40-60'][] = array("name"=>$val['name'], "data"=>$val['data']['40-60']);
            $data3['60-90'][] = array("name"=>$val['name'], "data"=>$val['data']['60-90']);
            $data3['90-100'][] = array("name"=>$val['name'], "data"=>$val['data']['90-100']);
        }

        foreach($data3 as $key=>$val){
            $data4[$key] = $val;
        }
    }

    $productInfo = $this->Slaves->query("Select * from products ");

    foreach($productInfo as $val){

        $productArray[$val['products']['id']] = $val['products']['name'];
    }

    $this->set('failuredata', $transdata);
    $this->set('products', $productArray);
    $this->set('timerange', isset($timerange) ? $timerange : "");
    $this->set('oprId', $oprId);
    $this->set('data2', $data2);
    $this->set('hourarray', $hourarray);
    $this->set('fromDate', $frmdate);
    $this->set('toDate', $todate);
    $this->set('processtimedata', $data4);
    $this->set('frm', $frm);
    $this->set('to', $to);
    $this->set('date', $date);
    $this->set('oprId', $oprId);
    $this->set('dayFailure', isset($countFailuredata) ? $countFailuredata : " ");
    $this->set('datearray', $datearray);
    $this->set('errortype', $errorType);
    $this->set('failurepercen', $failureTypepercentage);
    $this->set('modemSuccesspercent', json_encode($modemProcessTime));
    $this->set('totalfailure', json_encode($totalfailuredata));
    $this->set('type', isset($_REQUEST['type']) ? $_REQUEST['type'] : "");
}

function graphReport(){
    $this->layout = 'sims';

    if(isset($_REQUEST['date'])){
        $date = $_REQUEST['date'];
    }
    else{
        $date = date('Y-m-d');
    }

    $oprId = '';
    $frm = '';
    $to = '';
    $todate = $date;
    $hourquery = '';
    $frmdate = date("Y-m-d", strtotime("-2 day", strtotime($todate)));

    if(isset($_REQUEST['oprId'])){

        $oprId = $_REQUEST['oprId'];
    }
    if(isset($_REQUEST['frm'])){

        $frm = $_REQUEST['frm'];
    }
    if(isset($_REQUEST['to'])){

        $to = $_REQUEST['to'];
    }

    if(isset($_REQUEST['frm']) && isset($_REQUEST['to'])){
        $hourquery = " AND vendors_activations.hr between '$frm' and '$to'";
    }

    if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'weekwise'){

        $frmdate = date("Y-m-d", strtotime("-6 day", strtotime($todate)));
        $type = $_REQUEST['type'];
    }

    $failureArray = array();

    $transarray = array();

    $totalcount = array();

    $hourarray = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23);

    $datearray = array();

    $processstimearray = array();

    $failurearraydate = array();

    $totalcountdate = array();

    $dayWiseFailuredata = array();

    $sucesscountdatearray = array();

    $failureType = array();

    $failureTypepercentage = array();

    $modemCount = array();

    $modemType = array();

    $processarray = array();

    $dateprocesstime = array();

    $processTimedata = array();

    $dateWiseFailuredata = array();

    $dateWiseProcesstimedata = array();

    while(strtotime($frmdate) <= strtotime($todate)){

        $failure = $this->Slaves->query("SELECT va.product_id,va.vendor_id,vm.response,vm.status,va.txn_id,va.status,va.hr  as hour,va.date as cdate" . "   from vendors_messages as vm " . "  left join vendors_activations as va on (va.txn_id = vm.va_tran_id and vm.service_vendor_id = va.vendor_id)" . " where va.date = '$frmdate' and  va.status IN (2,3) and vm.status =  'failure' and va.product_id =" . $oprId);

        foreach($failure as $val):

            if(($val['va']['status'] == '2' || $val['va']['status'] == '3')){

                $failureArray[$val['va']['cdate']][$val['va']['hour']][] = $val;

                $failurearraydate[$val['va']['cdate']][] = $val;

                if(strpos($val['vm']['response'], 'Manual reversal') !== false){

                    $val['vm']['response'] = 'Manual reversal';
                }
                else if(strpos($val['vm']['response'], '24 :: Error of connection') !== false){

                    $val['vm']['response'] = 'error_connection';
                }

                if( ! isset($refcode[$val['va']['txn_id']])){

                    $refcode[$val['va']['txn_id']] = $val['va']['txn_id'];

                    $failureType[$val['va']['cdate']][$val['va']['hour']][$val['vm']['response']][] = $val;
                }

                $errorType[$val['vm']['response']] = $val['vm']['response'];
            }
        endforeach
        ;

        $result = $this->Slaves->query("SELECT vendors_activations.product_id,count(*) as totalcount,vendors_activations.status,vendors_activations. vendor_id,vendors_activations.txn_id,vendors_activations.timestamp,Avg(TIME_TO_SEC(TIMEDIFF(vendors_activations.tran_processtime,vendors_activations.timestamp))) AS processtime," . " vendors.company,vendors_activations.hr as hour,vendors_activations.date  as cdate," . " vendors.update_flag,vendors.active_flag,vendors_messages.internal_error_code  " . " FROM " . " vendors_activations" .
        // . " LEFT JOIN "
        // . " vendors_transactions "
        // . " ON (vendors_transactions.ref_id = vendors_activations.ref_code and vendors_activations.vendor_id = vendors_transactions.vendor_id)"
        " LEFT JOIN vendors_messages ON  (vendors_activations.txn_id = vendors_messages.va_tran_id and vendors_activations.vendor_id = vendors_messages.service_vendor_id) " . " LEFT JOIN vendors " . " ON (vendors_activations.vendor_id = vendors.id) " . " WHERE" . " vendors_activations.date ='$frmdate'" . " $hourquery" . " AND vendors_activations.product_id = '" . $oprId . "'" . "  group by vendors_activations.txn_id order by vendors_activations.date asc ");

        foreach($result as $val){
            // failure data

            $datearray[$val['vendors_activations']['cdate']] = $val['vendors_activations']['cdate'];

            $totalcount[$val['vendors_activations']['cdate']][$val['vendors_activations']['hour']][] = $val;

            $totalcountdate[$val['vendors_activations']['cdate']][] = $val;

            $modemType[$val['vendors_activations']['vendor_id']] = $val['vendors']['company'];

            // success data with processtime,daywiseprocesstime
            if(($val['vendors_activations']['status'] != '2' && $val['vendors_activations']['status'] != '3') && ($val['vendors']['update_flag'] == '1') && ($val['vendors']['active_flag'] == '1')){

                if($val[0]['processtime'] <= 40){

                    $processstimearray[$val['vendors_activations']['cdate']][$val['vendors_activations']['hour']]['0-40'][] = $val['vendors_activations']['txn_id'];

                    $daywiseProcesstime[$val['vendors_activations']['cdate']]['0-40'][] = $val['vendors_activations']['txn_id'];

                    $modemCount[$val['vendors_activations']['cdate']][$val['vendors_activations']['hour']][$val['vendors_activations']['vendor_id']]['0-40'][] = $val['vendors_activations']['txn_id'];
                }
                else if($val[0]['processtime'] > 40 && $val[0]['processtime'] <= 60){

                    $processstimearray[$val['vendors_activations']['cdate']][$val['vendors_activations']['hour']]['40-60'][] = $val['vendors_activations']['txn_id'];

                    $daywiseProcesstime[$val['vendors_activations']['cdate']]['40-60'][] = $val['vendors_activations']['txn_id'];

                    $modemCount[$val['vendors_activations']['cdate']][$val['vendors_activations']['hour']][$val['vendors_activations']['vendor_id']]['40-60'][] = $val['vendors_activations']['txn_id'];
                }
                else if($val[0]['processtime'] > 60 && $val[0]['processtime'] <= 90){

                    $processstimearray[$val['vendors_activations']['cdate']][$val['vendors_activations']['hour']]['60-90'][] = $val['vendors_activations']['txn_id'];

                    $daywiseProcesstime[$val['vendors_activations']['cdate']]['60-90'][] = $val['vendors_activations']['txn_id'];

                    $modemCount[$val['vendors_activations']['cdate']][$val['vendors_activations']['hour']][$val['vendors_activations']['vendor_id']]['60-90'][] = $val['vendors_activations']['txn_id'];
                }
                else{

                    $processstimearray[$val['vendors_activations']['cdate']][$val['vendors_activations']['hour']]['90-100'][] = $val['vendors_activations']['txn_id'];

                    $daywiseProcesstime[$val['vendors_activations']['cdate']]['90-100'][] = $val['vendors_activations']['txn_id'];

                    $modemCount[$val['vendors_activations']['cdate']][$val['vendors_activations']['hour']][$val['vendors_activations']['vendor_id']]['90-100'][] = $val['vendors_activations']['txn_id'];
                }
            }
            if(($val['vendors']['update_flag'] == '1') && ($val['vendors']['active_flag'] == '1')){

                $successcount[$val['vendors_activations']['cdate']][$val['vendors_activations']['hour']][] = $val;

                $sucesscountdatearray[$val['vendors_activations']['cdate']][] = $val;
            }
        }
        $frmdate = date("Y-m-d", strtotime("+1 day", strtotime($frmdate)));
    }

    // calculation of percentage of modem

    foreach($datearray as $datval){
        foreach($hourarray as $hourval){
            foreach($errorType as $errkey=>$errval){
                foreach($modemType as $modkey=>$modval){

                    $data[$datval][$hourval] = isset($failureArray[$datval][$hourval]) ? round(count($failureArray[$datval][$hourval]) / count($totalcount[$datval][$hourval]) * 100, 2) : 0;
                    $processTimedata[$datval][$hourval]['0-40'] = isset($processstimearray[$datval][$hourval]['0-40']) ? round(count($processstimearray[$datval][$hourval]['0-40']) / count($successcount[$datval][$hourval]) * 100, 2) : 0;
                    $processTimedata[$datval][$hourval]['40-60'] = isset($processstimearray[$datval][$hourval]['40-60']) ? round(count($processstimearray[$datval][$hourval]['40-60']) / count($successcount[$datval][$hourval]) * 100, 2) : 0;
                    $processTimedata[$datval][$hourval]['60-90'] = isset($processstimearray[$datval][$hourval]['60-90']) ? round(count($processstimearray[$datval][$hourval]['60-90']) / count($successcount[$datval][$hourval]) * 100, 2) : 0;
                    $processTimedata[$datval][$hourval]['90-100'] = isset($processstimearray[$datval][$hourval]['90-100']) ? round(count($processstimearray[$datval][$hourval]['90-100']) / count($successcount[$datval][$hourval]) * 100, 2) : 0;
                    if(isset($failureType[$datval][$hourval][$errval])){
                        $failureTypepercentage[$datval][$hourval][$errval] = round(count($failureType[$datval][$hourval][$errval]) / count($totalcount[$datval][$hourval]) * 100, 2);

                        $failureTypepercentage[$datval][$hourval]['failure'] = $data[$datval][$hourval];
                        $failureTypepercentage[$datval][$hourval]['totalCount'] = count($totalcount[$datval][$hourval]);
                        $failureTypepercentage[$datval][$hourval]['totalFail'] = count($failureType[$datval][$hourval][$errval]);
                    }

                    if(isset($modemCount[$datval][$hourval][$modkey]['0-40'])){
                        $modemProcessTime[$datval][$hourval]['0-40'][$modval] = round(count($modemCount[$datval][$hourval][$modkey]['0-40']) / count($processstimearray[$datval][$hourval]['0-40']) * 100, 2);
                        $modemProcessTime[$datval][$hourval]['0-40']['success'] = $processTimedata[$datval][$hourval]['0-40'];
                        $modemProcessTime[$datval][$hourval]['0-40']['count'][$modval] = count($modemCount[$datval][$hourval][$modkey]['0-40']);
                    }
                    if(isset($modemCount[$datval][$hourval][$modkey]['40-60'])){

                        $modemProcessTime[$datval][$hourval]['40-60'][$modval] = round(count($modemCount[$datval][$hourval][$modkey]['40-60']) / count($processstimearray[$datval][$hourval]['40-60']) * 100, 2);
                        $modemProcessTime[$datval][$hourval]['40-60']['success'] = $processTimedata[$datval][$hourval]['40-60'];
                        $modemProcessTime[$datval][$hourval]['40-60']['count'][$modval] = count($modemCount[$datval][$hourval][$modkey]['40-60']);
                    }
                    if(isset($modemCount[$datval][$hourval][$modkey]['60-90'])){

                        $modemProcessTime[$datval][$hourval]['60-90'][$modval] = round(count($modemCount[$datval][$hourval][$modkey]['60-90']) / count($processstimearray[$datval][$hourval]['60-90']) * 100, 2);
                        $modemProcessTime[$datval][$hourval]['60-90']['success'] = $processTimedata[$datval][$hourval]['60-90'];
                        $modemProcessTime[$datval][$hourval]['60-90']['count'][$modval] = count($modemCount[$datval][$hourval][$modkey]['60-90']);
                    }
                    if(isset($modemCount[$datval][$hourval][$modkey]['90-100'])){

                        $modemProcessTime[$datval][$hourval]['90-100'][$modval] = round(count($modemCount[$datval][$hourval][$modkey]['90-100']) / count($processstimearray[$datval][$hourval]['90-100']) * 100, 2);
                        $modemProcessTime[$datval][$hourval]['90-100']['success'] = $processTimedata[$datval][$hourval]['90-100'];
                        $modemProcessTime[$datval][$hourval]['90-100']['count'][$modval] = count($modemCount[$datval][$hourval][$modkey]['90-100']);
                    }
                }
            }
        }

        $dateWiseFailuredata[$datval][] = round(count($failurearraydate[$datval]) / count($totalcountdate[$datval]) * 100, 2);
        $failuredata[] = round(count($failurearraydate[$datval]) / count($totalcountdate[$datval]) * 100, 2);
        $totalfailuredata[$datval] = round(count($failurearraydate[$datval]) / count($totalcountdate[$datval]) * 100, 2);
        $dateWiseProcesstimedata[$datval]['0-40'] = round(count($daywiseProcesstime[$datval]['0-40']) / count($sucesscountdatearray[$datval]) * 100, 2);
        $dateWiseProcesstimedata[$datval]['40-60'] = round(count($daywiseProcesstime[$datval]['40-60']) / count($sucesscountdatearray[$datval]) * 100, 2);
        $dateWiseProcesstimedata[$datval]['60-90'] = round(count($daywiseProcesstime[$datval]['60-90']) / count($sucesscountdatearray[$datval]) * 100, 2);
        $dateWiseProcesstimedata[$datval]['90-100'] = round(count($daywiseProcesstime[$datval]['90-100']) / count($sucesscountdatearray[$datval]) * 100, 2);
    }

    foreach($data as $key=>$val){
        foreach($val as $k=>$v){
            $data1[$key]['name'] = $key;
            $data1[$key]['data'][] = $v;
            $processarray[$key]['name'] = $key;
            $processarray[$key]['data']['0-40'][] = $processTimedata[$key][$k]['0-40'];
            $processarray[$key]['data']['40-60'][] = $processTimedata[$key][$k]['40-60'];
            $processarray[$key]['data']['60-90'][] = $processTimedata[$key][$k]['60-90'];
            $processarray[$key]['data']['90-100'][] = $processTimedata[$key][$k]['90-100'];
            $dateprocesstime[$key]['name'] = $key;
            $dateprocesstime[$key]['data']['0'] = $dateWiseProcesstimedata[$key]['0-40'];
            $dateprocesstime[$key]['data']['1'] = $dateWiseProcesstimedata[$key]['40-60'];
            $dateprocesstime[$key]['data']['2'] = $dateWiseProcesstimedata[$key]['60-90'];
            $dateprocesstime[$key]['data']['3'] = $dateWiseProcesstimedata[$key]['90-100'];
        }
    }

    foreach($data1 as $val){
        $data2[] = $val;
    }

    $data6 = array();
    foreach($processarray as $val){
        $data3['0-40'][] = array("name"=>$val['name'], "data"=>$val['data']['0-40']);
        $data3['40-60'][] = array("name"=>$val['name'], "data"=>$val['data']['40-60']);
        $data3['60-90'][] = array("name"=>$val['name'], "data"=>$val['data']['60-90']);
        $data3['90-100'][] = array("name"=>$val['name'], "data"=>$val['data']['90-100']);
    }

    foreach($data3 as $key=>$val){
        $data4[$key] = $val;
    }

    if($_REQUEST['type'] == 'weekwise'){

        foreach($dateWiseFailuredata as $key=>$val){
            $dayWiseFailuredata[$key]['name'] = $key;
            $dayWiseFailuredata[$key]['data'] = $failuredata;
        }
        foreach($dateprocesstime as $key=>$val){
            $data6[] = $val;
        }

        foreach($dayWiseFailuredata as $val){

            $countFailuredata[] = $val;
        }
    }

    $this->set('data2', $data2);
    $this->set('hourarray', $hourarray);
    $this->set('fromDate', $frmdate);
    $this->set('toDate', $todate);
    $this->set('processtimedata', $data4);
    $this->set('frm', $frm);
    $this->set('to', $to);
    $this->set('date', $date);
    $this->set('oprId', $oprId);
    $this->set('dayFailure', isset($countFailuredata) ? $countFailuredata : " ");
    $this->set('datearray', $datearray);
    $this->set('type', isset($type) ? $type : "");
    $this->set('data6', $data6);
    $this->set('errortype', $errMsg);
    $this->set('failurepercen', $failureTypepercentage);
    $this->set('modemSuccesspercent', json_encode($modemProcessTime));
    $this->set('totalfailure', json_encode($totalfailuredata));
}

function exceedComplainDetails($id, $fdate, $tdate, $ftime, $ttime){
    if(isset($fdate) && isset($tdate) && isset($ftime) && isset($ttime)){
        $fd = $fdate;
        $td = $tdate;
        $ft = str_replace('.', ':', $ftime);
        $tt = str_replace('.', ':', $ttime);
    }
    else{
        $fd = date('Y-m-d');
        $td = date('Y-m-d');
        $ft = "00:00:00";
        $tt = "23:59:59";
    }

    $key = "";
    if($id != 'total'){
        $key = "vendors_activations.vendor_id ='$id' AND";
    }

    // $query = "SELECT vendors_activations.*,products.name,vendors.company,vendors.id,CONCAT_WS( ' ', resolve_date,resolve_time) as resolvetime,turnaround_time"
    // . " FROM "
    // . " complaints LEFT JOIN"
    // . " vendors_activations "
    // . " on complaints.vendor_activation_id = vendors_activations.id "
    // . " inner join products on products.id = vendors_activations.product_id "
    // . " inner join vendors on vendors.id = vendors_activations.vendor_id "
    // . " WHERE $key complaints.in_date between '$fd' AND '$td' AND complaints.in_time between '$ft' AND '$tt' group by vendors_activations.id order by vendors_activations.date desc";

    $query = "SELECT * FROM (SELECT vendors_activations.*,products.name,vendors.company,complaints.takenby,vendors.id vendors_id,CONCAT_WS( ' ', resolve_date,resolve_time) as resolvetime,turnaround_time" . " FROM " . " complaints LEFT JOIN" . " vendors_activations " . " on complaints.vendor_activation_id = vendors_activations.id " . " inner join products on products.id = vendors_activations.product_id " . " inner join vendors on vendors.id = vendors_activations.vendor_id " . " WHERE $key complaints.in_date between '$fd' AND '$td' AND complaints.in_time between '$ft' AND '$tt'" . " order by vendors_activations.date desc, complaints.turnaround_time DESC) a GROUP BY id";

    $getexceedresult = $this->Slaves->query($query);

    $i = 0;
    foreach($getexceedresult as $ger_temp){
        $getexceedres[$i]['vendors_activations'] = array('id'=>$ger_temp['a']['id'], 'vendor_id'=>$ger_temp['a']['vendor_id'], 'product_id'=>$ger_temp['a']['product_id'], 'mobile'=>$ger_temp['a']['mobile'], 'param'=>$ger_temp['a']['param'], 'amount'=>$ger_temp['a']['amount'],
                'discount_commission'=>$ger_temp['a']['discount_commission'], 'txn_id'=>$ger_temp['a']['txn_id'], 'vendor_refid'=>$ger_temp['a']['vendor_refid'], 'operator_id'=>$ger_temp['a']['operator_id'], 'shop_transaction_id'=>$ger_temp['a']['shop_transaction_id'],
                'retailer_id'=>$ger_temp['a']['retailer_id'], 'invoice_id'=>$ger_temp['a']['invoice_id'], 'status'=>$ger_temp['a']['status'], 'prevStatus'=>$ger_temp['a']['prevStatus'], 'api_flag'=>$ger_temp['a']['api_flag'], 'cause'=>$ger_temp['a']['cause'], 'code'=>$ger_temp['a']['code'],
                'timestamp'=>$ger_temp['a']['timestamp'], 'date'=>$ger_temp['a']['date'], 'extra'=>$ger_temp['a']['extra'], 'complaintNo'=>$ger_temp['a']['complaintNo']);
        $getexceedres[$i]['products'] = array('name'=>$ger_temp['a']['name']);
        $getexceedres[$i]['vendors'] = array('company'=>$ger_temp['a']['company'], 'id'=>$ger_temp['a']['vendors_id']);
        $getexceedres[$i][0] = array('resolvetime'=>$ger_temp['a']['resolvetime']);
        $getexceedres[$i]['complaints'] = array('turnaround_time'=>$ger_temp['a']['turnaround_time'],'takenby'=>$ger_temp['a']['takenby']);
        $i ++ ;
    }

    foreach($getexceedres as $val):

        if(strtotime($val[0]['resolvetime']) > strtotime($val['complaints']['turnaround_time'])):

            $exceedresult[] = $val;

	endif;
    endforeach
    ;


        $resolution_tag = array();
    foreach($getexceedres as $res){
        $resolution_tag[] = $res['vendors_activations']['txn_id'];
    }

    $resolution_tag = $this->Slaves->query("SELECT * FROM (SELECT taggings.name, comments.ref_code FROM comments
                                            LEFT JOIN taggings ON (taggings.id = comments.tag_id)
                                            WHERE comments.ref_code IN ('" . implode("','", $resolution_tag) . "')
                                            ORDER BY comments.created DESC) as t GROUP BY t.ref_code");

    $temp_ers = array();
    foreach($resolution_tag as $tag){
        $temp_ers[$tag['t']['ref_code']] = $tag['t']['name'];
    }

       $this->set('resolution_tag', $temp_ers);
      $this->set('exceedresult', $exceedresult);
}

function retailers(){
    $fromDateV = isset($_POST['fromDateV']) ? $_POST['fromDateV'] : "";
    $toDateV = isset($_POST['toDateV']) ? $_POST['toDateV'] : "";
    $fromDateD = isset($_POST['fromDateD']) ? $_POST['fromDateD'] : "";
    $toDateD = isset($_POST['toDateD']) ? $_POST['toDateD'] : "";

    $retailers_query = "select r.*, ur.*, rks.*, max(rks.document_timestamp) as d_date, max(rks.verified_timestamp) as v_date,
					group_concat(rks.verified_state) as gvs, group_concat(rks.document_state) as gds, '' as service_ids
				from retailers r
				left join unverified_retailers ur on ur.retailer_id = r.id
				left join retailers_kyc_states rks on rks.retailer_id = r.id
				where 1";

    $trained = isset($_POST['trained']) ? $_POST['trained'] : "";
    $distributor_id = $_POST['distributor_id'] ? $_POST['distributor_id'] : "";
    $search_term = isset($_POST['search_term']) ? trim($_POST['search_term']) : "";
    $document_state = isset($_POST['document_state']) ? $_POST['document_state'] : "";
    $verified_state = isset($_POST['verified_state']) ? $_POST['verified_state'] : "";

    if($trained === "0" || $trained){
        $retailers_query .= " and r.trained = $trained ";
    }
    if($distributor_id){
        $retailers_query .= " and r.parent_id = $distributor_id ";
    }
    if($search_term){
        $retailers_query .= " and (r.mobile like '%" . $search_term . "%' or ur.name like '%" . $search_term . "%'
									or ur.shopname like '%" . $search_term . "%') ";
    }

    $retailers_having_query = array();
    if( ! empty($fromDateV) &&  ! empty($toDateV)){
        $fdarr = explode("-", $fromDateV);
        $tdarr = explode("-", $toDateV);
        $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
        $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

        $retailers_having_query[] = " max(rks.verified_date) between '$fd' and '$ft' ";
    }
    else{
        $fromDateV = $toDateV = "";
    }

    if( ! empty($fromDateD) &&  ! empty($toDateD)){
        $fdarr = explode("-", $fromDateD);
        $tdarr = explode("-", $toDateD);
        $fd = $fdarr[2] . "-" . $fdarr[1] . "-" . $fdarr[0];
        $ft = $tdarr[2] . "-" . $tdarr[1] . "-" . $tdarr[0];

        $retailers_having_query[] = " max(rks.document_date) between '$fd' and '$ft' ";
    }
    else{
        $fromDateD = $toDateD = "";
    }

    if($verified_state === "0"){
        $retailers_having_query[] = " avg(rks.verified_state) = 0 ";
    }
    else if($verified_state == 1){
        $retailers_having_query[] = " group_concat(rks.verified_state) like '%1%'
										and group_concat(rks.verified_state) not like '1,1,1'";
    }
    else if($verified_state == 2){
        $retailers_having_query[] = " group_concat(rks.verified_state) like '1,1,1' ";
    }

    if($document_state === "0"){
        $retailers_having_query[] = " group_concat(rks.document_state) like '%0%' ";
    }
    else if($document_state == 1){
        $retailers_having_query[] = " group_concat(rks.document_state) like '%1%' ";
    }

    $retailers_having_query = implode(" and ", $retailers_having_query);

    if($retailers_having_query){
        $retailers_having_query = " having $retailers_having_query";
    }

    $retailers_query .= " 	group by r.id
								$retailers_having_query
								order by max(rks.document_date) desc, r.modified desc ";

    $distributors = $this->Slaves->query("select distributors.id, distributors.company, distributors.mobile
												from distributors
                                                order by company");
    /** IMP DATA ADDED : START**/
    $dist_ids = array_map(function($element){
        return $element['distributors']['id'];
    },$distributors);
    $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
    /** IMP DATA ADDED : END**/

    $distributor_mobile = array();
    foreach($distributors as $key => $d){
        $distributors[$key]['distributors']['company'] = $imp_data[$d['distributors']['id']]['imp']['shop_est_name'];
        $distributor_mobile[$d['distributors']['id']] = $d['distributors']['mobile'];
    }

    $memcache_options = array('memcache_key'=>"retailers_kyc_panel", 'memcache_duration'=>"3600");
    $retailers = $this->paginate_query($retailers_query, 100, $memcache_options);

    /** IMP DATA ADDED : START**/
    $ret_ids = array_map(function($element){
        return $element['r']['id'];
    },$retailers);

    $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);

    $retailer_imp_label_map = array(
        'pan_number' => 'pan_no',
        'shopname' => 'shop_est_name',
        'alternate_number' => 'alternate_mobile_no',
        'email' => 'email_id',
        'shop_structure' => 'shop_ownership',
        'shop_type' => 'business_nature'
    );
    foreach ($retailers as $key => $retailer) {
        foreach ($retailer['r'] as $retailer_label_key => $value) {
            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
            if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['id']]['imp']) ){
                $retailers[$key]['r'][$retailer_label_key] = $imp_data[$retailer['r']['id']]['imp'][$retailer_label_key_mapped];
            }
        }
        foreach ($retailer['ur'] as $retailer_label_key => $value) {
            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
            if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['id']]['imp']) ){
                $retailers[$key]['ur'][$retailer_label_key] = $imp_data[$retailer['r']['id']]['imp'][$retailer_label_key_mapped];
            }
        }
    }
    /** IMP DATA ADDED : END**/


    $this->set('retailers', $retailers);
    $this->set('distributors', $distributors);
    $this->set('verify_flag', $verify_flag);
    $this->set('trained', $trained);
    $this->set('distributor_id', $distributor_id);
    $this->set('search_term', $search_term);
    $this->set('distributor_mobile', $distributor_mobile);
    $this->set('fromDateD', $fromDateD);
    $this->set('toDateD', $toDateD);
    $this->set('fromDateV', $fromDateV);
    $this->set('toDateV', $toDateV);
    $this->set('verified_state', $verified_state);
    $this->set('document_state', $document_state);

    $this->layout = 'sims';
}

/**
 * Retailer Trial And Verification (RTV) State Machine
 * ===================================================
 *
 * verify_flag state values
 * 0 => Unverified and documents not submitted
 * 1 => Documents submitted and verfied
 * 2 => Documents submitted and unverified
 *
 * trial_flag state values
 * 0 => Full-time retailer, verify_flag = [0, 1, 2] (states possible)
 * 1 => Unverified retailer in trial period, verify_flag = [0, 2]
 * 2 => Trial period ended and retailer unverified, verify_flag = [0, 2]
 *
 * ----------------------------------------------------
 *
 * RTV state codes
 * code verify_flag trial_flag value
 * 00 0 0 Full-time retailer, unverified, documents not submitted
 * 10 1 0 Full-time retailer, verified
 * 20 2 0 Full-time retailer, documents submitted (pending verification), unverified
 * 01 0 1 Unverified retailer on trial, documents not submitted
 * 11 1 1 <State not permissible>
 * 21 2 1 Unverified retailer on trial, documents submitted (pending verification)
 * 02 0 2 Suspened retailer, trial period ended, unverified, documents not submitted
 * 12 1 2 <State not permissible>
 * 22 2 2 Suspened retailer, trial period ended, unverified, documents submitted (pending verification)
 *
 * -----------------------------------------------------
 *
 * current_codes verify_event result_code action and comment
 * [00, 01, 02, 11, 12] 0 <NA> <Event not permissible>
 * 10 0 00 Verification fault, retailer has to submit new documents
 * 20 0 00 Verification failed, retailer has to submit new documents
 * 21 0 01 Verification failed, retailer has to submit new documents
 * 22 0 02 Verification failed, retailer has to submit new documents
 * [00, 01, 02, 10, 11, 12] 1 <NA> <Event not permissible>
 * 20 1 10 Full-time retailer verified, copy unverified details to retailers table
 * 21 1 10 Trial to full-time retailer verified, copy unverified details to retailers table
 * 22 1 10 Suspended to full-time retailer, copy unverified details to retailers table
 * [00, 10] 2 20 Full-time retailer, documents submitted (pending verification)
 * [20, 11, 21, 12, 22] 2 <NA> <Event not permissible>
 * 01 2 21 Retailer on trial, documents submitted (pending verification)
 * 02 2 22 Suspended retailer, trial period ended, unverified, documents submitted (pending verification)
 */
function setVerifyFlag(){
    if(isset($_POST['verify_flag']) && $_POST['retailer_id']){
        $retailer_id = $_POST['retailer_id'];
        $verify_event = $_POST['verify_flag'];

        if(in_array($verify_event, array("0", "2"))){
            $this->User->query("update retailers
								set verify_flag = " . $verify_event . ",
								modified = '" . date('Y-m-d H:i:s') . "'
								where id = " . $retailer_id);
            echo "true";
        }
        else if($verify_event == 1){
            $verify_flag = $this->General->update_verify_flag($retailer_id);
            if($verify_flag == 1){
                echo "true";
            }
            else{
                echo "false";
            }
        }
    }
    else
        echo "false";
    exit();
}

function toggleTrained(){
    $this->autoRender = false;
    $retailer_id = $_POST['retailer_id'];
    if($retailer_id){
        $retailers = $this->User->query("select * from retailers r where id = $retailer_id ");
        if($retailers){
            $this->User->query("update retailers
						set trained = " . (1 - $retailers[0]['r']['trained']) . ",
						modified = '" . date('Y-m-d H:i:s') . "'
						where id = $retailer_id ");
            echo "true";
        }
        else
            echo "false";
    }
    else
        echo "false";
    exit();
}

function retailerVerification($retailer_id){
    if($retailer_id){
        if(isset($_POST['verify_flag'])){
            $retailer = array();
            isset($_POST['shopname']) and $retailer['shopname'] = $_POST['shopname'];
            isset($_POST['name']) and $retailer['name'] = $_POST['name'];
            // isset($_POST['pincode']) AND $retailer['pin'] = $_POST['pincode'];
            // isset($_POST['address']) AND $retailer['address'] = $_POST['address'];
            isset($_POST['shop_type']) and $retailer['shop_type'] = $_POST['shop_type'];
            isset($_POST['shop_type_value']) and $retailer['shop_type_value'] = $_POST['shop_type_value'];
            isset($_POST['location_type']) and $retailer['location_type'] = $_POST['location_type'];

            if( ! empty($retailer)){
                if($_POST['verify_flag'] == 1){
                    $update_query = "update retailers set ";
                    foreach($retailer as $k=>$r){
                        $imp_update_data[$k] = $r;
                        $update_query .= " $k = '$r', ";
                    }
                    $update_query .= " modified = '" . date('Y-m-d H:i:s') . "'
                            where id = " . $retailer_id;

                }
                else{
                    $update_query = "update unverified_retailers set ";
                    foreach($retailer as $k=>$r){
                        $imp_update_data[$k] = $r;
                        $update_query .= " $k = '$r', ";
                    }
                    $update_query .= " modified = '" . date('Y-m-d H:i:s') . "'
    						where retailer_id = " . $retailer_id;
                        }

                    /** IMP DATA ADDED : START**/
                    $response = $this->Shop->updateUserLabelData($retailer_id,$imp_update_data,$this->Session->read('Auth.User.id'),2);
                    /** IMP DATA ADDED : END**/
                    $this->User->query($update_query);


                $retailers = $this->User->query("select * from retailers
							where id = " . $retailer_id);
                $this->General->updateRetailerAddress($retailer_id, $retailers['0']['retailers']['user_id'], $_POST);
            }

            foreach($_FILES as $document_type=>$file){
                if( ! empty($file['name'][0])){
                    $this->Shop->removeDocument($retailer_id, $_POST['section_id'], $_POST['verify_flag']);
                    App::import('Controller', 'Shops');
                    $ShopsController = new ShopsController();
                    $ShopsController->constructClasses();

                    $ShopsController->uploadImages($document_type, $document_type . "_" . $retailer_id);
                    $this->Shop->setKYCState($retailer_id, $_POST['section_id'], 0);
                }
            }
            $this->redirect("/panels/retailerVerification/" . $retailer_id);
        }

        $retailers = $this->User->query("select r.*, ur.*, up.latitude, up.longitude,
						a.name, c.name, s.name, ua.name, uc.name, us.name, u.mobile, d.id,d.name,
						'' as service_ids
	    			from retailers r
	    			left join unverified_retailers ur on ur.retailer_id = r.id
					left join distributors d on d.id = r.parent_id
					left join users u on u.id = d.user_id
					left join user_profile up on up.user_id = r.user_id and up.device_type = 'online'
					left join locator_area a ON a.id = r.area_id
					left join locator_city c ON c.id = a.city_id
					left join locator_state s ON s.id = c.state_id
					left join locator_area ua ON ua.id = ur.area_id
					left join locator_city uc ON uc.id = ua.city_id
					left join locator_state us ON us.id = uc.state_id
	    			where r.id = " . $retailer_id . "
                    group by r.id");

        /** IMP DATA ADDED : START**/

        $temp = $this->Shop->getUserLabelData($retailers[0]['d']['id'],2,3);
        $imp_data_dist = $temp[$retailers[0]['d']['id']];
        $temp = $this->Shop->getUserLabelData($retailer_id,2,2);
        $imp_data = $temp[$retailer_id];
        $retailer_imp_label_map = array(
            'pan_number' => 'pan_no',
            'shopname' => 'shop_est_name',
            'alternate_number' => 'alternate_mobile_no',
            'email' => 'email_id',
            'shop_structure' => 'shop_ownership',
            'shop_type' => 'business_nature'
        );
        $dist_imp_label_map = array(
            'pan_number' => 'pan_no',
            'company' => 'shop_est_name',
            'alternate_number' => 'alternate_mobile_no',
            'email' => 'email_id'
        );
        foreach ($retailers[0]['r'] as $retailer_label_key => $value) {
            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
            if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                $retailers[0]['r'][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
            }
        }
        foreach ($retailers[0]['ur'] as $retailer_label_key => $value) {
            $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
            if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                $retailers[0]['ur'][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
            }
        }
        foreach ($retailers[0]['d'] as $dist_label_key => $value) {
            $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
            if( array_key_exists($dist_label_key_mapped,$imp_data_dist['imp']) ){
                $retailers[0]['d'][$dist_label_key] = $imp_data_dist['imp'][$dist_label_key_mapped];
            }
        }
        /** IMP DATA ADDED : END**/




        $retailer = $retailers[0];
        $retailer_images = $this->User->query("select * from retailers_details rd
					left join users u on u.id = rd.uploader_user_id
					left join groups g on g.id = u.group_id
					where rd.retailer_id = " . $retailer_id);
        $retailer['images'] = $retailer_images;

        $retailer_verified_images = $this->User->query("select * from retailers_docs rd
					left join users u on u.id = rd.uploader_user_id
					left join groups g on g.id = u.group_id
					where rd.retailer_id = " . $retailer_id);
        $retailer['verified_images'] = $retailer_verified_images;

        $retailer_kyc_states = $this->User->query("select * from retailers_kyc_states rks
					where retailer_id = " . $retailer_id);
        $retailer['kyc_states'] = $retailer_kyc_states;

        $this->set('retailer', $retailer);

        $shop_types = $this->Shop->business_natureTypes();
        $location_types = $this->Shop->location_typeTypes();

        $this->set('shop_types', $shop_types);
        $this->set('location_types', $location_types);
    }
    $this->layout = 'sims';
}

function verifySection(){
    $this->autoRender = false;
    $retailer_id = $_POST['retailer_id'];
    $section_id = $_POST['section_id'];

    $retailer_before_score_change = $this->User->query("select *
				from retailers r
				where r.id = " . $retailer_id);
    $this->Shop->setKYCState($retailer_id, $section_id, 2);
    $retailer_after_score_change = $this->User->query("select *
				from retailers r
				where r.id = " . $retailer_id);

    $message = "";
    if($retailer_before_score_change[0]['r']['kyc_score'] < 100 && $retailer_after_score_change[0]['r']['kyc_score'] == 100){
        $message = "Congratulations! You can now use our toll free calling.";
    }
    else if($retailer_before_score_change[0]['r']['kyc_score'] + $retailer_after_score_change[0]['r']['kyc_score'] == 200){
        $message = "You are a Verified Pay1 Retail Partner now. Customers will be able to locate you easily.";
    }
    if($message){
        $this->General->sendMessage($retailer_after_score_change[0]['r']['mobile'], $message, 'notify');
    }
}

function rejectSection(){
    $this->autoRender = false;
    $retailer_id = $_POST['retailer_id'];
    $section_id = $_POST['section_id'];
    $reason = $_POST['reason'];

    $this->Shop->setKYCState($retailer_id, $section_id, 1, $reason);

    $retailers = $this->User->query("select *
						from retailers r
						where r.id = " . $retailer_id);
    $message = "Your information has been rejected because: $reason. Kindly update details to enjoy toll free calling.";
    $this->General->sendMessage($retailers[0]['r']['mobile'], $message, 'notify');
}

function verifyDocuments(){
    $this->autoRender = false;
    $retailer_id = $_POST['retailer_id'];
    $document_type = $_POST['document_type'];
    if( ! empty($retailer_id) && in_array($document_type, array('idProof', 'addressProof', 'shop'))){
        $retailer_documents = $this->User->query("select * from retailers_details
					where retailer_id = " . retailer_id . "
					and type = '" . $document_type . "'");
        if( ! empty($retailer_documents)){
            $this->User->query("delete from retailers_details
						where verify_flag = 1
						and retailer_id = " . retailer_id . "
						and type = '" . $document_type . "'");
            if($this->User->query("update retailers_details
						set verify_flag = 1
						where verify_flag = 0
						and retailer_id = " . retailer_id . "
						and type = '" . $document_type . "'")){
                $this->General->update_verify_flag($retailer_id);
                echo "done";
            }
            else
                echo "Could not update documents.";
        }
        else
            echo "No documents on record to verify.";
    }
    else
        echo "Proper parameters not provided";
    exit();
}

function rejectDocument(){
    $this->autoRender = false;
    $retailer_id = $_POST['retailer_id'];
    $document_type = $_POST['document_type'];
    $reason = $_POST['reason'];

    if( ! empty($retailer_id) && in_array($document_type, array('idProof', 'addressProof', 'shop')) && $reason){
        $retailer_documents = $this->User->query("select * from retailers_details
					where retailer_id = " . $retailer_id . "
					and type = '" . $document_type . "'
					and verify_flag = 0");
        if( ! empty($retailer_documents)){
            $this->User->query("update retailers_details
					set verify_flag = -1,
					comment = '" . $reason . "'
					where retailer_id = " . $retailer_id . "
					and type = '" . $document_type . "'
					and verify_flag = 0");
            $this->User->query("update unverified_retailers
					set documents_submitted = -1,
					modified = '" . date('Y-m-d H:i:s') . "'
					where retailer_id = " . $retailer_id);
            $this->User->query("update retailers
					set verify_flag = -1,
					modified = '" . date('Y-m-d H:i:s') . "'
					where id = " . $retailer_id);

            $retailers = $this->User->query("select * from retailers r where r.id = " . $retailer_id);
            $message = "Your KYC (" . $document_type . " photo ) was rejected. Reason: $reason
				Kindly, upload appropriate documents.";
            $this->General->sendMessage($retailers[0]['r']['mobile'], $message, 'notify');

            echo "done";
        }
        else
            echo "No documents on record to verify.";
    }
    else
        echo "Provide a proper reason for rejection";
    exit();
}

function deleteDocument(){
    $this->autoRender = false;
    $src = $_POST['src'];
    $reason = $_POST['reason'];

    if($src && $reason){
        $response = $this->Shop->deleteDocument($src);
        $retailers = $this->User->query("select rd.*, r.*
					from retailers_details rd
					join retailers on rd.retailer_id = r.id
					where image_name like '$src'");
        $this->User->query("update unverified_retailers
					set documents_submitted = -1,
					modified = '" . date('Y-m-d H:i:s') . "'
					where retailer_id = " . $retailers[0]['r']['id']);
        $kyc_score = $this->General->kyc_level($retailers[0]['r']['id']);
        if($kyc_score < 1){
            $this->User->query("update retailers
						set verify_flag = 0,
						modified = '" . date('Y-m-d H:i:s') . "'
						where id = " . $retailers[0]['r']['id']);
        }
        // $message = "Your KYC (".$retailers[0]['rd']['type']." photo ) was unverified. Reason: $reason
        // Kindly, upload appropriate documents.";

        $paramdata['RETAILERS_TYPE'] = $retailers[0]['rd']['type'];
        $paramdata['REASON'] = $reason;
        $MsgTemplate = $this->General->LoadApiBalance();
        $content = $MsgTemplate['Retailer_DeleteKYCDocs_MSG'];
        $message = $this->General->ReplaceMultiWord($paramdata, $content);

        $this->General->sendMessage($retailers[0]['r']['mobile'], $message, 'notify');

        echo $response;
    }
    else
        echo "Provide proper url for the image";
    exit();
}

function vendorsCommissions(){
    $this->layout = 'sims';
    // $results_per_page = 10;

    $vendor_id = $_POST['vendor_id'] ? $_POST['vendor_id'] : "";
    $product_id = $_POST['product_id'] ? $_POST['product_id'] : "";

    // $page = $_POST['page'] ? $_POST['page'] : 1;
    // $offset = ($page - 1) * $results_per_page;

    // $limit_query = " limit $offset, $results_per_page ";

    // $vendors = $this->Slaves->query("select id, company from vendors where show_flag = 1");
    // $products = $this->Slaves->query("select id, name from products where to_show = 1");
    $vendors = $this->Slaves->query("select id, company from vendors");
    $products = $this->Slaves->query("select id, name from products");
    $circles = $this->Slaves->query("select area_code as id, area_name from mobile_numbering_area");

    if($vendor_id . $product_id){
        $vendors_commissions_query = "select vc.is_deleted,vc.id, v.id, p.id, v.company, p.name, vc.discount_commission,vc.commission_fixed, vc.active, vc.cap_per_min, vc.tat_time,
	                        vc.oprDown, vc.circle, vc.circles_yes, vc.circles_no, vc.timestamp, vc.updated_by, u.name,if(v.update_flag=0 and machine_id=0,1,0) as is_api
	                    from vendors_commissions as vc
	                    LEFT JOIN vendors v on (vc.vendor_id = v.id)
	                    LEFT JOIN products p on (vc.product_id = p.id)
						LEFT JOIN users u on u.id = vc.updated_by
                                               where 1";
        // where vc.is_deleted=0";

        if($vendor_id){
            $vendors_commissions_query .= " and v.id = " . $vendor_id;
            $this->set("vendor_id", $vendor_id);
        }
        if($product_id){
            $vendors_commissions_query .= " and p.id = " . $product_id;
            $this->set("product_id", $product_id);
        }
        // $total_count = count($this->User->query($vendors_commissions_query));
        $vendors_commissions = $this->Slaves->query($vendors_commissions_query);
    }
    $this->set("vendors", $vendors);
    $this->set("products", $products);
    $this->set("circles", $circles);
    $this->set("vendors_commissions", $vendors_commissions);

    // $this->set('page', $page);
    // $this->set('total_count', $total_count);
    // $this->set('total_pages', ceil($total_count / $results_per_page));
}

function saveVendorCommission($vc_id){
    $vendor_id = $_POST['add_vendor_id'];
    $product_id = $_POST['add_product_id'];
    $circle_code = $_POST['circle_id'];
    $circles_yes = $_POST['cy'];
    $circles_no = $_POST['cn'];
    $discount_commission = $_POST['is_api'] == '0' ? $_POST['discount_commission'] : 0;
    $commission_fixed = $_POST['commission_fixed'];
    $tat_time = $_POST['tat_time'] > 0 ? $_POST['tat_time'] : 0;
    $cap_per_min = $_POST['cap_per_min'];
    $user_id = $_SESSION['Auth']['User']['id'];

    if($vc_id){

        // $sql="update vendors_commissions set vendor_id = '$vendor_id',product_id = '$product_id',circle = '$circle_code',"
        // . " circles_yes = '$circles_yes',circles_no = '$circles_no',tat_time = '$tat_time',cap_per_min = '$cap_per_min',updated_by = '$user_id' ";
        $sql = "update vendors_commissions set tat_time = '$tat_time',cap_per_min = '$cap_per_min',commission_fixed = '$commission_fixed',updated_by = '$user_id' ";

        $sql .= $_POST['is_api'] == '0' ? ",discount_commission='$discount_commission' " : " ";

        $sql .= " where id = $vc_id";

        $this->User->query($sql);
    }
    else{
        $this->User->query("insert into vendors_commissions
						(vendor_id, product_id, circle, circles_yes, circles_no,tat_time,timestamp, updated_by)
				values ('$vendor_id', '$product_id', '$circle_code', '$circles_yes', '$circles_no','$tat_time', '" . date('Y-m-d H:i:s') . "', '$user_id')");
    }
    exit();
}

function apiRecon($date = null){
    $this->layout = 'sims';
    $date = empty($_REQUEST['date']) ? date("Y-m-d") : $_REQUEST['date'];
    $serverstatusQuery = '';
    $vendorstatusQuery = '';
    $vendorQuery = '';
    if($_REQUEST['status'] == 'All' || ( ! isset($_REQUEST['status']))){

        $serverstatusQuery = "AND `api_transactions`.server_status IN ('success','failure','pending')";

        $vendorstatusQuery = "AND `api_transactions`.vendor_status IN ('success','failure','pending')";
    }
    else if(isset($_REQUEST['status']) &&  ! empty($_REQUEST['status'])){
        $serverstatusQuery = "AND `api_transactions`.server_status = '{$_REQUEST['status']}'";

        $vendorstatusQuery = "AND `api_transactions`.vendor_status = '{$_REQUEST['status']}'";
    }

    $vendor_activation_cond = "";
    if(isset($_REQUEST['vendor']) &&  ! empty($_REQUEST['vendor'])){
        $vendorQuery = "AND `api_transactions`.vendor_id = '{$_REQUEST['vendor']}'";
    }
    $qry = "SELECT `api_transactions`.*, vendors.id, vendors.company, vendors.shortForm, vendors_activations.vendor_refid, vendors_activations.status FROM `api_transactions` " . " LEFT JOIN vendors ON (vendors.id = api_transactions.vendor_id ) " . " LEFT JOIN vendors_activations ON (api_transactions.txn_id = vendors_activations.txn_id AND vendors_activations.date = '$date' and api_transactions.date = vendors_activations.date and api_transactions.vendor_id = vendors_activations.vendor_id ) " . "WHERE `api_transactions`.date='$date' $vendorstatusQuery " . "  $serverstatusQuery AND `api_transactions`.vendor_status != `api_transactions`.server_status $vendorQuery ";

    $api_result = $this->Slaves->query($qry);
    $apiVendors = $this->Slaves->query("Select vendors.id,vendors.company from vendors where update_flag = '0'");

    $status_mapping = array("Inprocess", "Success", "Failed", "Reverse", "Reversal in process", "Reversal declined");

    $this->set('apiVendors', $apiVendors);
    $this->set('date', $date);
    $this->set('status_map', $status_mapping);
    $this->set('apiResult', $api_result);
    $this->set('vendorId', isset($_REQUEST['vendor']) ? $_REQUEST['vendor'] : "");
    $this->set('status', isset($_REQUEST['status']) ? $_REQUEST['status'] : "");
}

function apiReconSuccessTxn($id){
    $this->User->query("UPDATE api_transactions SET vendor_status='success',flag=0 WHERE id = $id");
    $this->autoRender = false;
}

function inprocessReport(){
    $frmdate = isset($_REQUEST['frmdate']) ? $_REQUEST['frmdate'] : date('Y-m-d ');
    $todate = isset($_REQUEST['frmdate']) ? $_REQUEST['frmdate'] : date('Y-m-d ');

    // $date1 = date_create($frmdate);
    // $date2 = date_create($todate);
    // $diff=date_diff($date1,$date2);
    // $days = $diff->format("%a");

    $from_time = isset($_REQUEST['from_time']) ? $_REQUEST['from_time'] : '';
    $to_time = isset($_REQUEST['to_time']) ? $_REQUEST['to_time'] : '';
    $Modem_flag = isset($_REQUEST['modem_flag']) ? $_REQUEST['modem_flag'] : 1;
    $API_flag = isset($_REQUEST['api_flag']) ? $_REQUEST['api_flag'] : 1;

    $frm = '';
    $to = '';
    // if($days>=2){
    // $todate = date("Y-m-d", strtotime("+2 day", strtotime($frmdate)));
    // }

    // If Both Modem_flag and API_flag are checked i.e. $Modem_flag & $API_flag = 1
    if(($Modem_flag) && ($API_flag)){
        $API_Modem_Condition = '';
    }
    else{
        if($Modem_flag){
            $API_Modem_Condition = "and vendors.update_flag = 1"; // (If MODEM than vendors.update_flag = 1 )
        }
        else if($API_flag){
            $API_Modem_Condition = "and vendors.update_flag = 0"; // (If API than vendors.update_flag = 0 )
        }
    }
    if( ! empty($from_time) && ($from_time > 0)){
        $frm = $from_time;
        $from_time_Condition = "and vendors_activations.timestamp >='$frmdate $from_time:00:00'";
    }
    if( ! empty($to_time) && ($to_time > 0)){
        $to = $to_time;
        $to_time_Condition = "and vendors_activations.timestamp <='$todate $to_time:00:00'";
    }

    $inprocessdata = $this->Slaves->query("Select vendors_activations.id,vendors_activations.status,vendors_activations.txn_id,vendors.update_flag,vendors_activations.timestamp as vatimestamp,
						       vendors_activations.cc_userid,vendors_activations.date,
						       TIME_TO_SEC(TIMEDIFF(vendors_activations.updated_timestamp,vendors_activations.timestamp)) as processtime
					               from
						       vendors_activations use index(idx_date)
					               inner join vendors on (vendors.id = vendors_activations.vendor_id)
                                                       where vendors_activations.date between '$frmdate' and  '$todate' $from_time_Condition $to_time_Condition $API_Modem_Condition
						        group by vendors_activations.txn_id  order by vendors_activations.id desc");

    if( ! empty($inprocessdata)):
        $manualfailue = 0;
        $manualsuccess = 0;
        $modemInprocess = 0;
        $apiinProcess = 0;
        $autosuccess = 0;
        $autofailure = 0;
        $exceedautoSuccess = 0;
        $exceedautoFailure = 0;
        $exceedmanualfailure = 0;
        $exceedmanualsuccess = 0;
        $pending = 0;
        $totalexceed = 0;

        // Total Auto Success and Failed Transaction above 5 mins or 300 secs
        $autoTotalSuccees = 0;
        $autoTotalFailed = 0;

        // Total Manually Success and Failed Transaction above 5 mins or 300 secs
        $manualTotalSuccees = 0;
        $manualTotalFailed = 0;

        // Auto Success Proccessing timings
        $autoSuccessProcessing_Time_5_to_15 = 0;
        $autoSuccessProcessing_Time_15_to_45 = 0;
        $autoSuccessProcessing_Time_45_to_115 = 0;
        $autoSuccessProcessing_Time_115_to_2 = 0;
        $autoSuccessProcessing_Time_200_to_more = 0;

        // Manual Success Proccessing timings
        $manuallySuccessProcessing_Time_5_to_15 = 0;
        $manuallySuccessProcessing_Time_15_to_45 = 0;
        $manuallySuccessProcessing_Time_45_to_115 = 0;
        $manuallySuccessProcessing_Time_115_to_2 = 0;
        $manuallySuccessProcessing_Time_200_to_more = 0;

        // Auto Failed Proccessing timings
        $autoFailProcessing_Time_5_to_15 = 0;
        $autoFailProcessing_Time_15_to_45 = 0;
        $autoFailProcessing_Time_45_to_115 = 0;
        $autoFailProcessing_Time_115_to_2 = 0;
        $autoFailProcessing_Time_200_to_more = 0;

        // Manual Failed Proccessing timings
        $manuallyFailProcessing_Time_5_to_15 = 0;
        $manuallyFailProcessing_Time_15_to_45 = 0;
        $manuallyFailProcessing_Time_45_to_115 = 0;
        $manuallyFailProcessing_Time_115_to_2 = 0;
        $manuallyFailProcessing_Time_200_to_more = 0;

        $userdata = array();
        $manualsuccesscount = array();
        $manualfailurecount = array();
        $datearray = array();

        foreach($inprocessdata as $val):
            if( ! isset($datearray[$val['vendors_activations']['date']]['pending'])){
                $datearray[$val['vendors_activations']['date']]['pending'] = 0;
            }
            // if(!isset($datearray[$val['t']['date']]['totalexceed'])){
            // $datearray[$val['t']['date']]['totalexceed'] = 0;
            // }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manualfailure'])){
                $datearray[$val['vendors_activations']['date']]['manualfailure'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manualsuccess'])){
                $datearray[$val['vendors_activations']['date']]['manualsuccess'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autosuccess'])){
                $datearray[$val['vendors_activations']['date']]['autosuccess'] = 0;
            }
            // if(!isset($datearray[$val['t']['date']]['exceedautoSuccess'])){
            // $datearray[$val['t']['date']]['exceedautoSuccess'] = 0;
            // }
            // if(!isset($datearray[$val['t']['date']]['apiinProcess'])){
            // $datearray[$val['t']['date']]['apiinProcess'] = 0;
            // }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autofailure'])){
                $datearray[$val['vendors_activations']['date']]['autofailure'] = 0;
            }
            // if(!isset($datearray[$val['t']['date']]['modemInprocess'])){
            // $datearray[$val['t']['date']]['modemInprocess'] = 0;
            // }
            // if(!isset($datearray[$val['t']['date']]['exceedautoFailure'])){
            // $datearray[$val['t']['date']]['exceedautoFailure'] = 0;
            // }
            // if(!isset($datearray[$val['t']['date']]['exceedmanualsuccess'])){
            // $datearray[$val['t']['date']]['exceedmanualsuccess'] = 0;
            // }
            // if(!isset($datearray[$val['t']['date']]['exceedmanualfailure'])){
            // $datearray[$val['t']['date']]['exceedmanualfailure'] = 0;
            // }

            // Total Auto Success and Failed Transaction above 5 mins or 300 secs
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoTotalSuccees'])){
                $datearray[$val['vendors_activations']['date']]['autoTotalSuccees'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoTotalFailed'])){
                $datearray[$val['vendors_activations']['date']]['autoTotalFailed'] = 0;
            }

            // Total Manually Success and Failed Transaction above 5 mins or 300 secs
            if( ! isset($datearray[$val['vendors_activations']['date']]['manualTotalSuccees'])){
                $datearray[$val['vendors_activations']['date']]['manualTotalSuccees'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manualTotalFailed'])){
                $datearray[$val['vendors_activations']['date']]['manualTotalFailed'] = 0;
            }

            // Decalre AutoSuceessProcessing Time Variable
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_5_to_15'])){
                $datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_5_to_15'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_15_to_45'])){
                $datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_15_to_45'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_45_to_115'])){
                $datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_45_to_115'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_115_to_2'])){
                $datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_115_to_2'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_200_to_more'])){
                $datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_200_to_more'] = 0;
            }

            // Decalre ManualSuceessProcessing Time Variable
            if( ! isset($datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_5_to_15'])){
                $datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_5_to_15'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_15_to_45'])){
                $datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_15_to_45'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_45_to_115'])){
                $datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_45_to_115'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_115_to_2'])){
                $datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_115_to_2'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_200_to_more'])){
                $datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_200_to_more'] = 0;
            }

            // Decalre AutoFailedProcessing Time Variable
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_5_to_15'])){
                $datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_5_to_15'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_15_to_45'])){
                $datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_15_to_45'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_45_to_115'])){
                $datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_45_to_115'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_115_to_2'])){
                $datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_115_to_2'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_200_to_more'])){
                $datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_200_to_more'] = 0;
            }

            // Decalre ManualFailedProcessing Time Variable
            if( ! isset($datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_5_to_15'])){
                $datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_5_to_15'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_15_to_45'])){
                $datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_15_to_45'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_45_to_115'])){
                $datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_45_to_115'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_115_to_2'])){
                $datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_115_to_2'] = 0;
            }
            if( ! isset($datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_200_to_more'])){
                $datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_200_to_more'] = 0;
            }

            if($val['vendors_activations']['status'] == 0){
                // pending transactions
                $datearray[$val['vendors_activations']['date']]['pending'] ++ ;
            }

            $processtime = $val[0]['processtime'];
            // if($processtime){
            // echo 'Process Time:-'.$processtime/60;
            // //$totalexceed++;
            //
            // }

            // Counting AutoSuccess Process Time
            if((($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5')) && (($processtime >= 300) && ($processtime <= 900))){
                $datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_5_to_15'] ++ ;
            }
            if((($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5')) && (($processtime > 900) && ($processtime <= 2700))){
                $datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_15_to_45'] ++ ;
            }
            if((($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5')) && (($processtime > 2700) && ($processtime <= 4500))){
                $datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_45_to_115'] ++ ;
            }
            if((($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5')) && (($processtime > 4500) && ($processtime <= 7200))){
                $datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_115_to_2'] ++ ;
            }
            if((($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5')) && ($processtime > 7200)){
                $datearray[$val['vendors_activations']['date']]['autoSuccessProcessing_Time_200_to_more'] ++ ;
            }

            // Total Auto Success and Failed Transaction above 5 mins or 300 secs
            if(($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5') && ($processtime >= 300)){
                $datearray[$val['vendors_activations']['date']]['autoTotalSuccees'] ++ ;
            }
            if(($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3') && ($processtime >= 300)){
                $datearray[$val['vendors_activations']['date']]['autoTotalFailed'] ++ ;
            }

            // Total Manually Success and Failed Transaction above 5 mins or 300 secs
            if( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5') && ($processtime >= 300)){
                $datearray[$val['vendors_activations']['date']]['manualTotalSuccees'] ++ ;
            }
            if( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3') && ($processtime >= 300)){
                $datearray[$val['vendors_activations']['date']]['manualTotalFailed'] ++ ;
            }

            // Counting ManualSuccess Process Time
            if(( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5')) && (($processtime >= 300) && ($processtime <= 900))){
                $datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_5_to_15'] ++ ;
            }
            if(( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5')) && (($processtime > 900) && ($processtime <= 2700))){
                $datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_15_to_45'] ++ ;
            }
            if(( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5')) && (($processtime > 2700) && ($processtime <= 4500))){
                $datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_45_to_115'] ++ ;
            }
            if(( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5')) && (($processtime > 4500) && ($processtime <= 7200))){
                $datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_115_to_2'] ++ ;
            }
            if(( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '1' || $val['vendors_activations']['status'] == '5')) && ($processtime > 7200)){
                $datearray[$val['vendors_activations']['date']]['manuallySuccessProcessing_Time_200_to_more'] ++ ;
            }

            // Counting AutoFailed Process Time
            if((($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')) && (($processtime >= 300) && ($processtime <= 900))){
                $datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_5_to_15'] ++ ;
            }
            if((($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')) && (($processtime > 900) && ($processtime <= 2700))){
                $datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_15_to_45'] ++ ;
            }
            if((($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')) && (($processtime > 2700) && ($processtime <= 4500))){
                $datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_45_to_115'] ++ ;
            }
            if((($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')) && (($processtime > 4500) && ($processtime <= 7200))){
                $datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_115_to_2'] ++ ;
            }
            if((($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')) && ($processtime > 7200)){
                $datearray[$val['vendors_activations']['date']]['autoFailProcessing_Time_200_to_more'] ++ ;
            }

            // Counting ManualFailed Process Time
            if(( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')) && (($processtime >= 300) && ($processtime <= 900))){
                $datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_5_to_15'] ++ ;
            }
            if(( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')) && (($processtime > 900) && ($processtime <= 2700))){
                $datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_15_to_45'] ++ ;
            }
            if(( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')) && (($processtime > 2700) && ($processtime <= 4500))){
                $datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_45_to_115'] ++ ;
            }
            if(( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')) && (($processtime > 4500) && ($processtime <= 7200))){
                $datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_115_to_2'] ++ ;
            }
            if(( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')) && ($processtime > 7200)){
                $datearray[$val['vendors_activations']['date']]['manuallyFailProcessing_Time_200_to_more'] ++ ;
            }

            // if($processtime>=900){
            // //$totalexceed++;
            // $datearray[$val['vendors_activations']['date']]['totalexceed']++;
            // }

            if( ! empty($val['vendors_activations']['cc_userid']) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3')){
                // /$manualfailue++; // manual failure
                // $manualfailurecount[$val['vendors_activations']['cc_userid']]['manualfailure'][]= $val['vendors_activations']['ref_code'];

                $datearray[$val['vendors_activations']['date']]['manualfailure'] ++ ;
                // $datearray[$date]['manualfailurecount'] = $manualfailurecount;
            }

            if( ! empty($val['vendors_activations']['cc_userid']) && $val['vendors_activations']['status'] == '1'){
                // $manualsuccess++; // manual success
                // $manualsuccesscount[$val['vendors_activations']['cc_userid']]['manualsuccess'][]= $val['vendors_activations']['ref_code'];
                // $datearray[$val['vendors_activations']['date']]['manualsuccess'] = $manualsuccess;

                $datearray[$val['vendors_activations']['date']]['manualsuccess'] ++ ;
                // $datearray[$date]['manualsuccesscount'] = $manualsuccesscount;
            }

            if(($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '1') && ($processtime >= 60)){

                // $autosuccess++; // auto success
                // $datearray[$val['vendors_activations']['date']]['autosuccess'] = $autosuccess;

                $datearray[$val['vendors_activations']['date']]['autosuccess'] ++ ;
            }

            if(($val['vendors_activations']['cc_userid'] == 0) && ($val['vendors_activations']['status'] == '2' || $val['vendors_activations']['status'] == '3') && ($processtime >= 60)){

                // $autofailure++; // auto failure

                $datearray[$val['vendors_activations']['date']]['autofailure'] ++ ;
                // $datearray[$val['vendors_activations']['date']]['autofailure'] = $autofailure;
            }

            if($val['vendors']['update_flag'] == 0 && $processtime >= 300){
                // $apiinProcess++; // api inprocess

                $datearray[$val['vendors_activations']['date']]['apiinProcess'] ++ ;
            }
            if($val['vendors']['update_flag'] == 1 && $processtime >= 300){

                // $modemInprocess++; // modem in process
                $datearray[$val['vendors_activations']['date']]['modemInprocess'] ++ ;
            }

            // if($processtime>=900 && ($val['t']['complaintNo']==0 && $val['t']['status']=='1')){
            // //$exceedautoSuccess++; // excedd auto success transaction more than 15 mins
            //
            // $datearray[$val['t']['date']]['exceedautoSuccess']++;
            // }

            // if($processtime>=900 && $val['t']['complaintNo']==0 &&
            // ($val['t']['status']== '2' || $val['t']['status']== '3')){
            // ///$exceedautoFailure++;
            //
            // $datearray[$val['t']['date']]['exceedautoFailure']++;
            // }

            // if($processtime>=900 && !empty($val['t']['complaintNo']) && $val['t']['status']== '1'){
            //
            // //$exceedmanualsuccess++;
            //
            // $datearray[$val['t']['date']]['exceedmanualsuccess']++;
            // }

            // if($processtime>=900 && !empty($val['t']['complaintNo']) &&
            // ($val['t']['status']== '2' || $val['t']['status']== '3')){
            // // $exceedmanualfailure++;
            //
            // $datearray[$val['t']['date']]['exceedmanualfailure']++;
            // }

            // if($val['t']['complaintNo']!=0){
            //
            // $userdata[$val['users']['name']] = array("failure" => count($manualfailurecount[$val['vendors_activations']['complaintNo']]['manualfailure']),"success" => count($manualsuccesscount[$val['vendors_activations']['complaintNo']]['manualsuccess']));
            // }
        endforeach
        ;

        $this->set('frmdate', $frmdate);
        $this->set('todate', $todate);
        $this->set('frm', $frm);
        $this->set('to', $to);
        $this->set('datearray', $datearray);
        $this->set('modem_flag', $Modem_flag);
        $this->set('api_flag', $API_flag);

		endif;

}

function inProcessTransactionList($frmdate, $todate, $from_time, $to_time, $Modem_flag, $API_flag, $cat, $page = 1, $recs = 100){
    $from_time = empty($from_time) ? '' : $from_time;
    $to_time = empty($to_time) ? '' : $to_time;
    $limit = ($page - 1) * $recs . ',' . $recs;

    if(($Modem_flag) && ($API_flag)){
        $API_Modem_Condition = '';
    }
    else{
        if($Modem_flag){
            $API_Modem_Condition = "and vendors.update_flag = 1"; // (If MODEM than vendors.update_flag = 1 )
        }
        else if($API_flag){
            $API_Modem_Condition = "and vendors.update_flag = 0"; // (If API than vendors.update_flag = 0 )
        }
    }
    if( ! empty($from_time) && ($from_time > 0)){
        $frm = $from_time;
        $from_time_Condition = "and vendors_activations.timestamp >='$frmdate $from_time:00:00'";
    }
    if( ! empty($to_time) && ($to_time > 0)){
        $to = $to_time;
        $to_time_Condition = "and vendors_activations.timestamp <='$todate $to_time:00:00'";
    }

    if($cat == 'whole_total'){
        $query = " and vendors_activations.status IN (1,2,3,5) ";
        $having = " having processtime >= 300 ";
    }
    else if($cat == 'total_success'){
        $query = " and vendors_activations.status IN (1,5) ";
        $having = " having processtime >= 300 ";
    }
    else if($cat == 'total_fail'){
        $query = " and vendors_activations.status IN (2,3) ";
        $having = " having processtime >= 300 ";
    }
    else if($cat == 'success_5_to_15'){
        $query = " and vendors_activations.status IN (1,5) ";
        $having = " having processtime >= 300 and processtime <= 900 ";
    }
    else if($cat == 'fail_5_to_15'){
        $query = " and vendors_activations.status IN (2,3) ";
        $having = " having processtime >= 300 and processtime <= 900 ";
    }
    else if($cat == 'success_15_to_45'){
        $query = " and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 900 and processtime <= 2700 ";
    }
    else if($cat == 'fail_15_to_45'){
        $query = " and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 900 and processtime <= 2700 ";
    }
    else if($cat == 'success_45_to_115'){
        $query = " and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 2700 and processtime <= 4500 ";
    }
    else if($cat == 'fail_45_to_115'){
        $query = " and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 2700 and processtime <= 4500 ";
    }
    else if($cat == 'success_115_to_2'){
        $query = " and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 4500 and processtime <= 7200 ";
    }
    else if($cat == 'fail_115_to_2'){
        $query = " and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 4500 and processtime <= 7200 ";
    }
    else if($cat == 'success_200_to_more'){
        $query = " and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 7200 ";
    }
    else if($cat == 'fail_200_to_more'){
        $query = " and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 7200 ";
    }
    else if($cat == 'whole_auto'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (1,2,3,5) ";
        $having = " having processtime >= 300 ";
    }
    else if($cat == 'auto_total_success'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (1,5) ";
        $having = " having processtime >= 300 ";
    }
    else if($cat == 'auto_total_fail'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (2,3) ";
        $having = " having processtime >= 300 ";
    }
    else if($cat == 'auto_success_5_to_15'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (1,5) ";
        $having = " having processtime >= 300 and processtime <= 900 ";
    }
    else if($cat == 'auto_fail_5_to_15'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (2,3) ";
        $having = " having processtime >= 300 and processtime <= 900 ";
    }
    else if($cat == 'auto_success_15_to_45'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 900 and processtime <= 2700 ";
    }
    else if($cat == 'auto_fail_15_to_45'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 900 and processtime <= 2700 ";
    }
    else if($cat == 'auto_success_45_to_115'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 2700 and processtime <= 4500 ";
    }
    else if($cat == 'auto_fail_45_to_115'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 2700 and processtime <= 4500 ";
    }
    else if($cat == 'auto_success_115_to_2'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 4500 and processtime <= 7200 ";
    }
    else if($cat == 'auto_fail_115_to_2'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 4500 and processtime <= 7200 ";
    }
    else if($cat == 'auto_success_200_to_more'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 7200 ";
    }
    else if($cat == 'auto_fail_200_to_more'){
        $query = " and (vendors_activations.cc_userid IS NULL or vendors_activations.cc_userid = '' or vendors_activations.cc_userid = '0') and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 7200 ";
    }
    else if($cat == 'whole_manual'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (1,2,3,5) ";
        $having = " having processtime >= 300 ";
    }
    else if($cat == 'manual_total_success'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (1,5) ";
        $having = " having processtime >= 300 ";
    }
    else if($cat == 'manual_total_fail'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (2,3) ";
        $having = " having processtime >= 300 ";
    }
    else if($cat == 'manual_success_5_to_15'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (1,5) ";
        $having = " having processtime >= 300 and processtime <= 900 ";
    }
    else if($cat == 'manual_fail_5_to_15'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (2,3) ";
        $having = " having processtime >= 300 and processtime <= 900 ";
    }
    else if($cat == 'manual_success_15_to_45'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 900 and processtime <= 2700 ";
    }
    else if($cat == 'manual_fail_15_to_45'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 900 and processtime <= 2700 ";
    }
    else if($cat == 'manual_success_45_to_115'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 2700 and processtime <= 4500 ";
    }
    else if($cat == 'manual_fail_45_to_115'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 2700 and processtime <= 4500 ";
    }
    else if($cat == 'manual_success_115_to_2'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 4500 and processtime <= 7200 ";
    }
    else if($cat == 'manual_fail_115_to_2'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 4500 and processtime <= 7200 ";
    }
    else if($cat == 'manual_success_200_to_more'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (1,5) ";
        $having = " having processtime > 7200 ";
    }
    else if($cat == 'manual_fail_200_to_more'){
        $query = " and vendors_activations.cc_userid > 0 and vendors_activations.status IN (2,3) ";
        $having = " having processtime > 7200 ";
    }

    $countrecords = $this->Slaves->query("SELECT count(1) FROM (SELECT * from (Select vendors_activations.id,vendors_activations.status,vendors_activations.txn_id,vendors_activations.timestamp as vatimestamp,vendors_messages.timestamp as vmtimestamp,
                                vendors_activations.date,TIME_TO_SEC(TIMEDIFF(vendors_activations.updated_timestamp,vendors_activations.timestamp)) as processtime
                                from vendors_activations use index(idx_date)
                                inner join vendors_messages on (vendors_messages.va_tran_id = vendors_activations.txn_id and vendors_messages.service_vendor_id = vendors_activations.vendor_id)
                                left join vendors on (vendors.id = vendors_activations.vendor_id)
                                left join products on (products.id = vendors_activations.product_id)
                                left join users on (users.id = vendors_activations.cc_userid)
                                where vendors_activations.date between '$frmdate' and  '$todate' $from_time_Condition $to_time_Condition $API_Modem_Condition $query
                                order by vendors_messages.id desc) as t group by t.txn_id $having) as vendor");

    $totalpages = ceil($countrecords[0][0]['count(1)'] / $recs);

    $inprocessdata = $this->Slaves->query("SELECT * FROM (Select vendors_activations.id,vendors.company,products.name,vendors_activations.product_id,vendors_activations.status,vendors_activations.txn_id,vendors_activations.timestamp as vatimestamp,
                                vendors_activations.cc_userid,users.name as username,users.email,vendors_activations.date,vendors_activations.updated_timestamp vmtimestamp,
                                TIME_TO_SEC(TIMEDIFF(vendors_activations.updated_timestamp,vendors_activations.timestamp)) as processtime, vendors.update_flag
                                from vendors_activations use index(idx_date)
                                inner join vendors_messages on (vendors_messages.va_tran_id = vendors_activations.txn_id and vendors_messages.service_vendor_id = vendors_activations.vendor_id)
                                left join vendors on (vendors.id = vendors_activations.vendor_id)
                                left join products on (products.id = vendors_activations.product_id)
                                left join users on (users.id = vendors_activations.cc_userid)
                                where vendors_activations.date between '$frmdate' and  '$todate' $from_time_Condition $to_time_Condition $API_Modem_Condition $query
                                order by vendors_messages.id desc) as t group by t.txn_id $having ORDER BY 1 DESC LIMIT $limit");

    $this->set('inprocessdata', $inprocessdata);
    $this->set('totalrecords', $countrecords[0][0]['count(1)']);
    $this->set('totalpages', $totalpages);
    $this->set('page', $page);
    $this->set('recs', $recs);
    $this->set('modem_flag', $Modem_flag);
    $this->set('api_flag', $API_flag);
}

function inprocessReportMongo(){
    // error_reporting(E_ALL);
    // ini_set("display_errors", 1);
    $frmdate = isset($_REQUEST['frmdate']) ? $_REQUEST['frmdate'] : date('Y-m-d ');
    $todate = isset($_REQUEST['todate']) ? $_REQUEST['todate'] : date('Y-m-d ');
    $from_time = isset($_REQUEST['from_time']) ? $_REQUEST['from_time'] : '';
    $to_time = isset($_REQUEST['to_time']) ? $_REQUEST['to_time'] : '';
    if( ! empty($from_time) && ($from_time > 0)){

        $start = "$frmdate $from_time:00:00";
    }
    else{
        $start = "$frmdate 00:00:00";
    }
    if( ! empty($to_time) && ($to_time > 0)){
        $end = "$todate $to_time:00:00";
    }
    else{
        $end = "$todate 24:00:00";
    }
    try{
        $m = new MongoClient("mongodb://52.72.28.148:27017");
    }
    catch(Exception $e){
        die('Error connecting to MongoDB server');
    }

    $db = $m->shops;
    $col = $db->VAVM;

    $start = new MongoDate(strtotime($start));
    $end = new MongoDate(strtotime($end));

    $Modem_flag = isset($_REQUEST['modem_flag']) ? $_REQUEST['modem_flag'] : 1;
    $API_flag = isset($_REQUEST['api_flag']) ? $_REQUEST['api_flag'] : 1;
    $is_api = '1';
    if(($Modem_flag) && ($API_flag)){
        $is_api = array('$in'=>array(0, 1));
    }
    else{

        if($Modem_flag){
            $is_api = 0; // (If MODEM than vendors.update_flag = 1 )
        }
        else if($API_flag){
            $is_api = 1; // (If API than vendors.update_flag = 0 )
        }
    }
    $ops = array(array('$match'=>array('$and'=>array(array('date'=>array('$gte'=>$start, '$lte'=>$end)), array('is_api'=>$is_api)))), array('$unwind'=>'$vendors_messages'),
            array(
                    '$group'=>array('_id'=>'$id', 'vatimestamp'=>array('$first'=>'$timestamp'), 'vmtimestamp'=>array('$last'=>'$vendors_messages.timestamp'), 'transactionday'=>array('$first'=>'$date'), 'status'=>array('$first'=>'$status'), 'complaintNo'=>array('$first'=>'$complaintNo'))),
            array('$project'=>array('transactionday'=>1, 'status'=>1, 'complaintNo'=>array('$ifNull'=>array('$complaintNo', '0')), 'datediff'=>array('$divide'=>array(array('$subtract'=>array('$vmtimestamp', '$vatimestamp')), 1000)))),
            array(
                    '$project'=>array('transactionday'=>1,
                            'range'=>array(
                                    '$concat'=>array(
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gte'=>array('$datediff', 300)), array('$lte'=>array('$datediff', 900)), array('$eq'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '1')),
                                                                                            // array(
                                                                                            // '$eq'=>array('$status','4')
                                                                                            // ),
                                                                                            array('$eq'=>array('$status', '5')))))), 'auto success range 5-15 min', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gte'=>array('$datediff', 300)), array('$lte'=>array('$datediff', 900)), array('$eq'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '2')), array('$eq'=>array('$status', '3')))))), 'auto fail range 5-15 min', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gte'=>array('$datediff', 300)), array('$lte'=>array('$datediff', 900)), array('$ne'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '1')),
                                                                                            // array(
                                                                                            // '$eq'=>array('$status','4')
                                                                                            // ),
                                                                                            array('$eq'=>array('$status', '5')))))), 'manual success range 5-15 min', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gte'=>array('$datediff', 300)), array('$lte'=>array('$datediff', 900)), array('$ne'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '2')), array('$eq'=>array('$status', '3')))))), 'manual fail range 5-15 min', '')),

                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 900)), array('$lte'=>array('$datediff', 2700)), array('$eq'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '1')),
                                                                                            // array(
                                                                                            // '$eq'=>array('$status','4')
                                                                                            // ),
                                                                                            array('$eq'=>array('$status', '5')))))), 'auto success range 15-45 min', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 900)), array('$lte'=>array('$datediff', 2700)), array('$eq'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '2')), array('$eq'=>array('$status', '3')))))), 'auto fail range 15-45 min', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 900)), array('$lte'=>array('$datediff', 2700)), array('$ne'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '1')),
                                                                                            // array(
                                                                                            // '$eq'=>array('$status','4')
                                                                                            // ),
                                                                                            array('$eq'=>array('$status', '5')))))), 'manual success range 15-45 min', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 900)), array('$lte'=>array('$datediff', 2700)), array('$ne'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '2')), array('$eq'=>array('$status', '3')))))), 'manual fail range 15-45 min', '')),

                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 2700)), array('$lte'=>array('$datediff', 4500)), array('$eq'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '1')),
                                                                                            // array(
                                                                                            // '$eq'=>array('$status','4')
                                                                                            // ),
                                                                                            array('$eq'=>array('$status', '5')))))), 'auto success range 45-1.5 hr', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 2700)), array('$lte'=>array('$datediff', 4500)), array('$eq'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '2')), array('$eq'=>array('$status', '3')))))), 'auto fail range 45-1.5 hr', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 2700)), array('$lte'=>array('$datediff', 4500)), array('$ne'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '1')),
                                                                                            // array(
                                                                                            // '$eq'=>array('$status','4')
                                                                                            // ),
                                                                                            array('$eq'=>array('$status', '5')))))), 'manual success range 45-1.5 hr', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 2700)), array('$lte'=>array('$datediff', 4500)), array('$ne'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '2')), array('$eq'=>array('$status', '3')))))), 'manual fail range 45-1.5 hr', '')),

                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 4500)), array('$lte'=>array('$datediff', 7200)), array('$eq'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '1')),
                                                                                            // array(
                                                                                            // '$eq'=>array('$status','4')
                                                                                            // ),
                                                                                            array('$eq'=>array('$status', '5')))))), 'auto success range 1.5-2 hr', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 4500)), array('$lte'=>array('$datediff', 7200)), array('$eq'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '2')), array('$eq'=>array('$status', '3')))))), 'auto fail range 1.5-2 hr', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 4500)), array('$lte'=>array('$datediff', 7200)), array('$ne'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '1')),
                                                                                            // array(
                                                                                            // '$eq'=>array('$status','4')
                                                                                            // ),
                                                                                            array('$eq'=>array('$status', '5')))))), 'manual success range 1.5-2 hr', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 4500)), array('$lte'=>array('$datediff', 7200)), array('$ne'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '2')), array('$eq'=>array('$status', '3')))))), 'manual fail range 1.5-2 hr', '')),

                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 7200)), array('$ne'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '1')),
                                                                                            // array(
                                                                                            // '$eq'=>array('$status','4')
                                                                                            // ),
                                                                                            array('$eq'=>array('$status', '5')))))), 'manual success range 2+ hr', '')),
                                            array('$cond'=>array(array('$and'=>array(array('$gt'=>array('$datediff', 7200)), array('$eq'=>array('$complaintNo', '0')), array('$eq'=>array('$status', '1')))), 'auto success range 2+ hr', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 7200)), array('$ne'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '2')), array('$eq'=>array('$status', '3')))))), 'manual fail range 2+ hr', '')),
                                            array(
                                                    '$cond'=>array(
                                                            array(
                                                                    '$and'=>array(array('$gt'=>array('$datediff', 7200)), array('$eq'=>array('$complaintNo', '0')),
                                                                            array(
                                                                                    '$or'=>array(array('$eq'=>array('$status', '2')), array('$eq'=>array('$status', '3')))))), 'auto fail range 2+ hr', ''))
                                    )))), array('$group'=>array('_id'=>array('range'=>'$range', 'day'=>'$transactionday'), 'quantity'=>array('$sum'=>1))), array('$sort'=>array('_id'=> - 1)));
    $options = array('allowDiskUse'=>true);

    $cursor = $col->aggregate($ops, $options);
    $values = array();

    $i = 0;
    foreach($cursor['result'] as $row){
        $result[date('Y-m-d', $row['_id']['day']->sec)][$row['_id']['range']] = $row['quantity'];
        $i ++ ;
    }

    $this->set('frmdate', $frmdate);
    $this->set('todate', $todate);
    $this->set('frm', $frm);
    $this->set('to', $to);
    $this->set('datearray', $result);
    $this->set('modem_flag', $Modem_flag);
    $this->set('api_flag', $API_flag);
}

function updateDSN(){
    $this->autoRender = false;

    $retailer_id = $_POST['retailer_id'];
    $dsn = $_POST['dsn'];

    $this->User->query("update retailers
    			set device_serial_no = '$dsn',
    			modified = '" . date('Y-m-d H:i:s') . "'
    			where id = " . $retailer_id);

    echo "true";
    return;
}

function activateMPOS(){
    $this->autoRender = false;

    $retailer_id = $_POST['retailer_id'];
    $activate_flag = $_POST['activate_flag'];

    /*
     * $retailers = $this->Slaves->query("select *
     * from retailers r
     * left join retailers_services rs on rs.retailer_id = r.id and rs.service_id = 8
     * where r.id = ".$retailer_id);
     *
     * if($activate_flag == 1){
     * if(empty($retailers[0]['r']['device_serial_no'])){
     * echo "No device serial no found. Add device serial no to activate mPOS service";
     * return;
     * }
     * $this->User->query("insert into retailers_services
     * (retailer_id, service_id)
     * values ('$retailer_id', '8')");
     * echo "mPOS service activated.";
     * }
     * else {
     * $this->User->query("delete from retailers_services
     * where retailer_id = ".$retailer_id."
     * and service_id = 8");
     * echo "mPOS service deactivated.";
     * }
     */
    echo "not working currently";
    return;
}

function hide(){
    $this->autoRender = false;
    $vid = $this->params['form']['vid'];
    // echo $vid;
    $pid = $this->params['form']['pid'];
    // echo $pid;
    $query = "update vendors_commissions set is_deleted=1 where vendor_id=$vid and product_id=$pid";
    // echo $query;
    $isdeleted = $this->User->query($query);
    echo json_encode(array('status'=>'done'));
}

function updateOperatorFlag(){
    if($this->RequestHandler->isAjax()){
        if($_POST['auto_check'] == "true"):
            $autocheck = 1;

        else:
            $autocheck = 0;
        endif;

        $oprId = isset($_POST['oprid']) ? $_POST['oprid'] : 0;
        $updateQuery = $this->User->query("UPDATE  products SET auto_check = '" . $autocheck . "',modified = '" . date('Y-m-d H:i:s') . "' where id IN ($oprId)");
    }
    $this->autoRender = false;
}

function errorMsg(){
    $this->autoRender = false;
    echo "<span style='color:red;font-size:20'>You don't have permission to access this page</span>";
    echo "<img title=''  src='/img/no.png' ></img>";
}

function show(){
    $this->autoRender = false;
    $vid = $this->params['form']['vid'];
    $pid = $this->params['form']['pid'];
    $query = "update vendors_commissions set is_deleted=0 where vendor_id=$vid and product_id=$pid";
    $isdeleted = $this->User->query($query);
    echo json_encode(array('status'=>'done'));
}

/**
 * It will get current status of API transaction from vendor
 */
function check_current_api_txn_status(){
    $this->autoRender = false;

    $transId = $_REQUEST['id'];
    $vendor = $_REQUEST['vendor'];
    $date = $_REQUEST['date'];
    $refId = $_REQUEST['ref_id'];
    $vendor_id = $_REQUEST['vendor_id'];

    $dt = $this->Slaves->query("SELECT txn_id,vendor_refid,status,service_id,timestamp,product_id,operator_id,date FROM vendors_activations as va use index (idx_vend_date) ,products WHERE va.product_id=products.id AND vendor_id = $vendor_id AND va.date = '$date'  and txn_id='$transId'");

    $vend_status = $this->Recharge->getSetTransactionStatus($vendor_id, $dt);
    if( ! in_array(strtolower(trim($vend_status['status'])), array('success', 'failure'))){
        echo "pending";
        return;
    }

    $this->User->query("UPDATE api_transactions set vendor_status = '" . $vend_status['status'] . "' where txn_id='$transId' and vendor_id='$vendor_id'");

    $vend_status['status'] = (isset($vend_status['status']) && in_array(strtolower(trim($vend_status['status'])), array('success', 'failure'))) ? $vend_status['status'] : "pending";

    echo $vend_status['status'];
}

function shiftbalance(){
    if($this->RequestHandler->isAjax()){

        $supplierId = $_REQUEST['supplier_id'];
        $oprId = $_REQUEST['oprId'];
        $bal = $_REQUEST['bal'];
        $newSimId = $_REQUEST['new_sim_id'];
        $oldSimId = $_REQUEST['old_sim_id'];
        $sourceVendorId = $_REQUEST['modemId'];

        $checkifexists = $this->Slaves->query("Select id,scid,vendor_id from devices_data where opr_id='{$oprId}'  AND inv_supplier_id='{$supplierId}' AND scid ='{$newSimId}' and sync_date = '" . date('Y-m-d') . "' and device_num>0");

        if(empty($checkifexists)):
            echo json_encode(array('data'=>'Error'));
            exit();
            endif;

        $updatenewsim = "query=shiftbalance&target_vendor_id={$checkifexists[0]['devices_data']['vendor_id']}&source_vendor_id={$sourceVendorId}&balance={$bal}&opr_id={$oprId}&supplier_id={$supplierId}&new_scid={$newSimId}&old_scid={$oldSimId}&reqtype=update_target_vendor";

        $updatedeviceData = $this->Shop->modemRequest($updatenewsim, $checkifexists[0]['devices_data']['vendor_id']);

        if($updatedeviceData['status'] == 'success' && $updatedeviceData['data'] == 'Success'){

            $updatedeviceData = json_decode($updatedeviceData['data'], TRUE);

            $updateoldsim = "query=shiftbalance&source_vendor_id={$sourceVendorId}&opr_id={$oprId}&supplier_id={$supplierId}&scid={$oldSimId}";

            $updatedeviceData = $this->Shop->modemRequest($updateoldsim, $sourceVendorId);

            if($updatedeviceData['status'] == 'success'){

                echo json_encode($updatedeviceData);
                die();
            }
        }
    }

    $this->autoRender = false;
}

function tranDiffReport($frm_date = NULL, $to_date = NULL, $vendor = 'all', $product = 'all'){
    if($frm_date == NULL || $to_date == NULL){
        $frm_date = $to_date = date('Y-m-d');
    }

    $vendor_q = "";
    if($vendor != 'all'){
        $vendor_q = " va.vendor_id = '$vendor' AND ";
    }

    $product_q = "";
    if($product != 'all'){
        $product_q = " va.product_id = '$product' AND ";
    }

    $tran_data = $this->Slaves->query("SELECT * FROM (SELECT va.id va_id,va.vendor_id,va.product_id,va.amount,va.txn_id,va.status va_status,va.vendor_refid,va.date,va.timestamp,vm.id vm_id,vm.va_tran_id,vm.status vm_status,vm.response
                                    FROM vendors_activations va
                                    JOIN vendors_messages vm ON (va.txn_id = vm.va_tran_id)
                                    WHERE $vendor_q $product_q va.date >= '$frm_date' AND va.date <= '$to_date' AND va.status = " . TRANS_REVERSE . " AND vm.status = 'success' ORDER BY vm.id DESC) a
                                    GROUP BY txn_id ORDER BY va_id DESC");

    $vendors_temp = $this->Slaves->query("SELECT id,company FROM vendors");

    foreach($vendors_temp as $v_t){
        $vendors[$v_t['vendors']['id']] = $v_t['vendors']['company'];
    }

    $products_temp = $this->Slaves->query("SELECT id,name FROM products");

    foreach($products_temp as $p_t){
        $products[$p_t['products']['id']] = $p_t['products']['name'];
    }

    $this->set('tran_data', $tran_data);
    $this->set('vendors', $vendors);
    $this->set('products', $products);
    $this->set('sel_vendor', $vendor);
    $this->set('sel_product', $product);
    $this->set('frm_date', $frm_date);
    $this->set('to_date', $to_date);

    $this->layout = "plain";
}

function vendors($page = 1, $recs = 100){
    $this->layout = "plain";

    $limit = ($page - 1) * $recs . ',' . $recs;

    $count_records = $this->Slaves->query("SELECT count(1) count FROM vendors");

    $listing_data = $this->Slaves->query("SELECT vendors.id,vendors.user_id,vendors.company,vendors.shortForm,users.mobile,vendors.update_flag,vendors.show_flag,vendors.svn_flag,vendors.machine_id,vendors.update_time FROM vendors LEFT JOIN users ON (vendors.user_id = users.id) ORDER BY vendors.id DESC LIMIT $limit");

    $this->set('listing_data', $listing_data);
    $this->set('totalrecords', $count_records[0][0]['count']);
    $this->set('page', $page);
    $this->set('recs', $recs);
}

// function deleteRec() {
//
// $this->autoRender = FALSE;
//
// $id = $_POST['id'];
//
// $data = $this->User->query("DELETE FROM vendors WHERE id = $id");
//
// return json_encode($data);
// }
function addEditVendor($id = NULL){
    $this->layout = 'plain';

    if($id != NULL){
        $data = $this->Slaves->query("SELECT id,user_id user,company,shortForm,show_flag,machine_id FROM vendors WHERE id = $id");
        $this->set('vendor_data', $data[0]['vendors']);
    }
}

function addEditBackVendor($id = NULL){
    $this->autoRender = FALSE;

    $machine_id = $_POST['machine_id'];
    $company = $_POST['company'];
    $shortform = $_POST['shortform'];

    if($id == NULL){

        $show_flag = $_POST['show_flag'];

        $this->User->query("INSERT INTO vendors (company,shortForm,balance,ip,bridge_ip,port,bridge_flag,update_flag,active_flag,show_flag,svn_flag,health_factor,machine_id,update_time,last30bal)
                            VALUES ('$company','$shortform','0.00',0,'','',0,1,1,'$show_flag',0,0,'$machine_id','" . date('Y-m-d H:i:s') . "','0.00')");
    }
    else{

        $user = $_POST['user'];

        if(strlen($user) >= 10){
            $exist = $this->checkMobileExist($user);
            if($exist != ''){
                $user = $exist;
            }
            else{
                $new_user = $this->General->registerUser($user, ONLINE_REG, VENDOR);
                $user = $new_user['User']['id'];
            }
        }
        $this->Shop->addUserGroup($user, VENDOR);
        $this->User->query("UPDATE vendors SET user_id='$user',company='$company',shortForm='$shortform',update_time='" . date('Y-m-d H:i:s') . "' WHERE id = $id");
    }

    $record = $id == NULL ? "Inserted" : "Updated";
    $this->Session->setFlash("Record " . $record . " Successfully !!!");

    $this->redirect('vendors');
}

function checkMobileExist($mobile = NULL){
    $this->autoRender = FALSE;

    if($mobile == NULL){
        $mobile = $_POST['mobile'];
        $column = "count(*) count";
    }
    else{
        $column = "id";
    }

    $exist = $this->Slaves->query("SELECT $column FROM users WHERE mobile = '$mobile'");

    if(isset($_POST['mobile'])){
        echo json_encode($exist[0][0]['count']);
    }
    else{
        return $exist[0]['users']['id'];
    }
}

function changeFlag(){
    $this->autoRender = FALSE;

    $id = $_POST['id'];
    $flag = $_POST['flag'];

    $exist_val = $this->Slaves->query("SELECT $flag FROM vendors WHERE id = $id");

    $up_val = $exist_val[0]['vendors'][$flag] == '0' ? '1' : '0';

    $res = $this->User->query("UPDATE vendors SET $flag = '$up_val' WHERE id = $id");

    return json_encode($res);
}

function changeSVNFlag(){
    $this->autoRender = FALSE;

    $update_flag = $_POST['update_flag'];

    $res = $this->User->query("UPDATE vendors SET svn_flag = '$update_flag' WHERE id > 0");

    return json_encode($res);
}

function getApiReconData()
{
    $this->layout = 'sims';
    $params = $this->params['form'];
    $date = $this->params['form']['recon_date'];
    $vendor_id = $this->params['form']['vendor_id'];
    $file = $_FILES['apifile']['name'];

    $apiVendors = $this->Slaves->query("Select vendors.id,vendors.company from vendors where update_flag = '0'");
    $this->set('apiVendors', $apiVendors);

    if($this->RequestHandler->isAjax()){
            if(!empty($vendor_id))
            {
                if($_FILES['apifile']['size'] > 0)
                {
                    $allowedExtension = array("csv");
                    $getfileInfo = pathinfo($file, PATHINFO_EXTENSION);
                    if (in_array($getfileInfo, $allowedExtension))
                    {
                        App::import('vendor', 'S3', array('file' => 'S3.php'));
                        $bucket = apireconbucket;
                        $document_path = $_FILES['apifile']['tmp_name'];
                        $filename = $vendor_id.'_'.$date.'_txns.csv';
                        $s3 = new S3(awsAccessKey, awsSecretKey);
                        $checkIfFileExists = $s3->getObjectInfo($bucket, $filename ,true);

                        if(empty($checkIfFileExists)){
                            $response = array('status'=>'success','type'=>'0');
                        }
                        else
                        {
                            $response = array('status'=>'failure','type'=>'1');
                        }
                    }
                    else
                    {
                        $response = array('status'=>'failure','description'=>'Invalid File Format!','type'=>'2');
                    }
                }
                else
                {
                    $response = array('status'=>'failure','description'=>'Kindly Upload your API excel','type'=>'2');
                }
            }
            else
            {
                $response = array('status'=>'failure','description'=>'Please select vendor.','type'=>'2');
            }

        echo json_encode($response);
        exit;
    }

}

function apiReconData()
{
    $this->autoRender = false;
    $search_date = $this->params['form']['search_date'];
    $vendor_id = $this->params['form']['vendorid'];
    $vendor_status = $this->params['form']['vendor_status'];
    $server_status = $this->params['form']['server_status'];
    $serverstatusQuery = '';
    $vendorstatusQuery = '';
    $vendorQuery = '';
    $statusList = array('0' => 'pending', '1' => 'success', '2' => 'failure', '3' => 'failure', '4' => 'pending', '5' => 'success');

    if(isset($vendor_id) &&  ! empty($vendor_id)){
        $at_vendor_qry = "AND at.vendor_id = '$vendor_id'";
        $va_vendor_qry = "AND va.vendor_id = '$vendor_id'";
    }

    if(isset($vendor_status) &&  ! empty($vendor_status) && $vendor_status != 'All')
    {
        $vendorstatusQuery = "AND at.vendor_status = '$vendor_status'";
    }

    if(isset($server_status) &&  ! empty($server_status) && $server_status != 'All')
    {
        $serverstatusQuery = "AND at.server_status = '$server_status'";
    }

    $api_txn_qry = "SELECT * FROM "
                . "(SELECT at.*, v.company as vendor_name, v.shortForm "
                . "FROM api_transactions at "
                . "LEFT JOIN vendors v ON (v.id = at.vendor_id) "
                . "WHERE at.date = '$search_date' "
                . "$vendorstatusQuery $serverstatusQuery $at_vendor_qry "
                . "AND at.flag != '12' "
                . ") as api_txn_data ";

    $apiTXNs = $this->Retailer->query($api_txn_qry);

    $va_txn_qry = "SELECT va.id as va_id, va.txn_id, va.vendor_refid, va.vendor_id, va.status as current_status "
                . "FROM vendors_activations va  "
                . "WHERE va.date = '$search_date' "
                . "$va_vendor_qry ";

    $vaTXNs = $this->Retailer->query($va_txn_qry);

    $va_txn_data = array();

    foreach($vaTXNs as $txndata)
    {
        $va_txn_data[$txndata['va']['txn_id']][$txndata['va']['vendor_id']]['current_status'] = $txndata['va']['current_status'];
    }
    $result = array();

    if(!empty($apiTXNs))
    {
        foreach ($apiTXNs as $res)
        {
            $current_status = $res['api_txn_data']['current_status'] = !empty($va_txn_data[$res['api_txn_data']['txn_id']][$res['api_txn_data']['vendor_id']]['current_status'])?$statusList[$va_txn_data[$res['api_txn_data']['txn_id']][$res['api_txn_data']['vendor_id']]['current_status']]:$res['api_txn_data']['server_status'];
            $vendor_status = $res['api_txn_data']['vendor_status'];

            if($current_status != $vendor_status)
            {
                if(!($current_status == "failure" && $vendor_status == "null") && !($current_status == "null" && $vendor_status == "failure"))
                {
                    $result[] = $res['api_txn_data'];
                }
            }
        }
    }

    $response = array('status'=>'success','type'=>'0','data'=>$result);
    echo json_encode($response);
    exit;
}

function uploadReconExcel1()
{
    $this->autoRender = false;
    $params = $this->params['form'];
    $date = $this->params['form']['recon_date'];
    $vendor_id = $this->params['form']['vendor_id'];
    $statusList = array('0' => 'pending', '1' => 'success', '2' => 'failure', '3' => 'failure', '4' => 'pending', '5' => 'success');
    $file = $_FILES['apifile']['name'];

    if($this->RequestHandler->isAjax()){
            if($_FILES['apifile']['size'] > 0)
            {
                App::import('vendor', 'S3', array('file' => 'S3.php'));
                $bucket = apireconbucket;
                $document_path = $_FILES['apifile']['tmp_name'];
                $filename = $vendor_id.'_'.$date.'_txns.csv';
                $s3 = new S3(awsAccessKey, awsSecretKey);
                $s3_response = $s3->putObjectFile($document_path, $bucket, $filename, S3::ACL_PUBLIC_READ);

                if($s3_response)
                {
                    if (($handle = fopen($document_path, "r")) !== FALSE)
                    {
                        $row = 1;
                        while (($data = fgetcsv($handle, 0, ",")) !== FALSE):

                                $num = count($data); // Get total Field count

                                for ($c=0; $c < $num; $c++):

                                            $temp[$row][]=$data[$c];

                                endfor;

                                $row++;

                        endwhile;

                        fclose($handle);
                    }
                    array_shift($temp);

                    Configure::load('api_recon');
                    $recharge_apis = Configure::read('recharge_apis');

                    $txn_id_col = isset($recharge_apis[$vendor_id]['txn_id'])?$recharge_apis[$vendor_id]['txn_id']:'';
                    $ref_id_col = $recharge_apis[$vendor_id]['vendor_ref_id'];
                    $status_col = $recharge_apis[$vendor_id]['status'];
                    $api_status = $recharge_apis[$vendor_id]['api_status'];
                    $amount_col = $recharge_apis[$vendor_id]['amount'];
                    $type = $recharge_apis[$vendor_id]['type'];
                    $txn_ids = array();
                    $ref_ids = array();
                    $api_response = array();

                    if(!empty($temp))
                    {
                        foreach($temp as $data)
                        {
                            $txn_id = preg_replace("/[^0-9]/", "", $data[$txn_id_col]);
                            $ref_id = preg_replace("/[^a-zA-Z0-9]/", "", $data[$ref_id_col]);
                            $amount = preg_replace("/[^0-9.]/", "", $data[$amount_col]);
                            $vend_status = preg_replace("/[^a-zA-Z- ]/", "", $data[$status_col]);
//                            $status_code = (($vendor_id == 63) && strstr(strtolower($vend_status),'success'))?1:$api_status[$vend_status];
                            $vendor_status =(($vendor_id == 63) && strstr(strtolower($vend_status),'success'))?'success':$statusList[$api_status[strtolower($vend_status)]];
                            $update_val = "";

                            if($type == 0)//if txn id and ref id both are given
                            {
                                $qry_cond = "AND ((ref_code != '' AND txn_id = '$txn_id' AND ref_code = '$ref_id') OR (ref_code = '' AND txn_id = '$txn_id'))";
                                $update_val = "ref_code = '$ref_id',";
                                $insert_qry_val = "VALUES ('$vendor_id','$txn_id','$ref_id','$amount','$vendor_status','null','".date('Y-m-d H:i:s')."','$date','11')";
                            }
                            elseif($type == 1)//if only ref id exists
                            {
                                $qry_cond = "AND ref_code = '$ref_id'";
                                $insert_qry_val = "VALUES ('$vendor_id','null','$ref_id','$amount','$vendor_status','null','".date('Y-m-d H:i:s')."','$date','11')";
                            }
                            elseif($type == 2)//if only txn id exists
                            {
                                $qry_cond = "AND txn_id = '$txn_id'";
                                $insert_qry_val = "VALUES ('$vendor_id','$txn_id','null','$amount','$vendor_status','null','".date('Y-m-d H:i:s')."','$date','11')";
                            }

                            $result = $this->Retailer->query("SELECT * "
                                                . "FROM api_transactions "
                                                . "WHERE 1 "
                                                . "$qry_cond AND date = '$date' AND vendor_id = '$vendor_id' ");
                            if(!empty($result))
                            {
                                $update_qry = "UPDATE api_transactions "
                                            . "SET ".$update_val."vendor_status = '$vendor_status',updated_time = '".date('Y-m-d H:i:s')."' "
                                            . "WHERE 1 "
                                            . "$qry_cond AND date = '$date' AND vendor_id = '$vendor_id' ";

                                $this->Retailer->query($update_qry);
                            }
                            else
                            {
                                $insert_query = "INSERT INTO api_transactions (vendor_id,txn_id,ref_code,amount,vendor_status,server_status,updated_time,date,flag) $insert_qry_val";

                                $this->Retailer->query($insert_query);
                            }
                        }

                        $txn_qry = "SELECT * FROM "
                                . "(SELECT at.*, v.company, v.shortForm, va.id as va_id, va.vendor_refid, va.status as current_status "
                                . "FROM api_transactions at "
                                . "LEFT JOIN vendors v ON (v.id = at.vendor_id) "
                                . "LEFT JOIN vendors_activations va ON (at.txn_id = va.txn_id AND va.date = '$date' and at.date = va.date and at.vendor_id = va.vendor_id ) "
                                . "WHERE at.date = '$date' "
 //                               . "AND at.vendor_status != at.server_status "
//                                . "AND at.vendor_status != 'null' "
                                . "AND at.vendor_id = '$vendor_id' "
//                                . "AND at.flag != '12' "
                                . ") as api_txn_data ";

                        $apiTXNs = $this->Slaves->query($txn_qry);

                        $api_transactions = array();
                        $missmatchedData_serversuccess = $missmatchedData_serverfailure = array();

                        if(!empty($apiTXNs))
                        {
                            foreach ($apiTXNs as $txndata)
                            {
                                $api_txn_id = $txndata['api_txn_data']['id'];
                                $trans_id = $txndata['api_txn_data']['txn_id'];
                                $trans_ref_id = $txndata['api_txn_data']['ref_code'];
                                $trans_vendor_id = $txndata['api_txn_data']['vendor_id'];
                                $vendor_act_id = $txndata['api_txn_data']['va_id'];
                                $vendor_status = $txndata['api_txn_data']['vendor_status'];
                                $trans_status = $txndata['api_txn_data']['server_status'];
                                $current_status = !empty($txndata['api_txn_data']['current_status'])?$statusList[$txndata['api_txn_data']['current_status']]:$txndata['api_txn_data']['server_status'];
                                $trans_status_code = !empty($txndata['api_txn_data']['current_status'])?$txndata['api_txn_data']['current_status']:array_search($txndata['api_txn_data']['status'],$statusList);
                                $current_datetime = date('Y-m-d H:i:s');
                                $current_date = date('Y-m-d');

                                if(strtolower($current_status) != strtolower($vendor_status))
                                { // action in case of difference
                                    $flag = 0;

                                    if(strtolower($current_status) == "failure" && $vendor_status == "success"  && $trans_vendor_id == $vendor_id)
                                    {
            //                                $this->Retailer->query("INSERT into trans_pullback (vendors_activations_id,vendor_id,status,timestamp,pullback_by,pullback_time,reported_by,date) values('$vendor_act_id','$vendor_id','$trans_status_code','$current_datetime','0','0000-00-00 00:00:00','System','$current_date')");
                                        $trans_pullbackdata = array('vendors_activations_id'=>$vendor_act_id,
                                                                    'vendor_id'=>$vendor_id,
                                                                    'status'=>$trans_status_code,
                                                                    'timestamp'=>$current_datetime,
                                                                    'pullback_by'=>'0',
                                                                    'pullback_time'=>'0000-00-00 00:00:00',
                                                                    'reported_by'=>'System',
                                                                    'date'=>$current_date);
                                        $this->General->manage_transPullback($trans_pullbackdata);

                                        $server_failure_data = array('transId'=>$trans_id,'refCode'=>$trans_ref_id,'serverStatus'=>$trans_status,'current_status'=>$current_status,'vendorStatus'=>$vendor_status,'date'=>$date);

                                        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/api_auto_recon_data.txt", date('Y-m-d H:i:s') . "transId : $trans_id Server failure : ". json_encode($server_failure_data));

                                        $missmatchedData_serverfailure['data'][] = $server_failure_data;
                                    }
                                    else
                                    {
                                        if(!(strtolower($current_status) == "failure" && $vendor_status == "null") && !(strtolower($current_status) == "null" && $vendor_status == "failure"))
                                        {
                                            $api_transactions[$api_txn_id]['txn_id'] = $trans_id;
                                            $api_transactions[$api_txn_id]['vend_short_name'] = $vendor_name = $txndata['api_txn_data']['shortForm'];
                                            $api_transactions[$api_txn_id]['vendor_name'] = $vendor_name = $txndata['api_txn_data']['company'];
                                            $api_transactions[$api_txn_id]['date'] = $trans_date = $txndata['api_txn_data']['date'];
                                            $api_transactions[$api_txn_id]['vendor_refid'] = $trans_ref_id;
                                            $api_transactions[$api_txn_id]['amount'] = $trans_amount = $txndata['api_txn_data']['amount'];
                                            $api_transactions[$api_txn_id]['status_code'] = $trans_status_code;
                                            $api_transactions[$api_txn_id]['server_status'] = $trans_status;
                                            $api_transactions[$api_txn_id]['va_id'] = $vendor_act_id;
                                            $api_transactions[$api_txn_id]['at_id'] = $api_txn_id;
                                            $api_transactions[$api_txn_id]['vendor_id'] = $trans_vendor_id;
                                            $api_transactions[$api_txn_id]['vendor_status'] = $vendor_status;
                                            $api_transactions[$api_txn_id]['current_status'] = $current_status;
                                            $api_transactions[$api_txn_id]['flag'] = $flag;

                                            $update_qry = "UPDATE api_transactions "
                                                        . "SET flag = 1,updated_time = '".date('Y-m-d H:i:s')."' "
                                                        . "WHERE 1 "
                                                        . "AND txn_id = '$trans_id' AND date = '$date' AND vendor_id = '$vendor_id' ";

                                            $this->Retailer->query($update_qry);

                                            $api_transactions[$api_txn_id]['flag'] = $flag = 1;
                                            $server_success_data = array('transId'=>$trans_id,'refCode'=>$trans_ref_id,'serverStatus'=>$trans_status,'current_status'=>$current_status,'vendorStatus'=>$vendor_status,'date'=>$date);

                                            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/api_auto_recon_data.txt", date('Y-m-d H:i:s') . "transId : $trans_id Server success : ". json_encode($server_success_data));

                                            $missmatchedData_serversuccess['data'][] = $server_success_data;
                                        }
                                    }
                                }
                            }

                            //send mail of txn success at server and fail at vendor
                            if(isset($missmatchedData_serversuccess['data']) && !empty($missmatchedData_serversuccess['data']))
                            {
                                 $missmatchedData_serversuccess['colhead'] = array_keys($missmatchedData_serversuccess['data'][0]);
                                 $missmatchedData_serversuccess['colval'] = $missmatchedData_serversuccess['data'];
                                 $missmatchedData_serversuccess['subject'] = "Auto reconcilation report for server success : ".$apiTXNs[0]['api_txn_data']['shortForm'];
                                 $this->sendFormatedmail($missmatchedData_serversuccess);
                            }

                            //send mail of txn fail at server and success at vendor
                            if(isset($missmatchedData_serverfailure['data']) && !empty($missmatchedData_serverfailure['data']))
                            {
                                $missmatchedData_serverfailure['colhead'] = array_keys($missmatchedData_serverfailure['data'][0]);
                                $missmatchedData_serverfailure['colval'] = $missmatchedData_serverfailure['data'];
                                $missmatchedData_serverfailure['subject'] = "Auto reconcilation report for server failure : ".$apiTXNs[0]['api_txn_data']['shortForm'];
                                $this->sendFormatedmail($missmatchedData_serverfailure);
                            }
                            $response = array('status'=>'success','type'=>'0','data'=>$api_transactions);
                        }
                        else
                        {
                            $response = array('status'=>'failure','description'=>'No records found!','type'=>'2');
                        }
                    }
                    else
                    {
                        $response = array('status'=>'failure','description'=>'Empty file!','type'=>'2');
                    }
                }
                else
                {
                    $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.','type'=>'2');
                }
            }
            else
            {
                $response = array('status'=>'failure','description'=>'Kindly Upload your API excel','type'=>'2');
            }
            $this->set('api_txns', $api_transactions);
            $this->set('params', $params);
            echo json_encode($response);
            exit;
        }
    }

    function uploadReconExcel()
    {
        $this->autoRender = false;
        $params = $this->params['form'];
        $date = $this->params['form']['recon_date'];
        $vendor_id = $this->params['form']['vendor_id'];
        $statusList = array('0' => 'pending', '1' => 'success', '2' => 'failure', '3' => 'failure', '4' => 'pending', '5' => 'success');
        $file = $_FILES['apifile']['name'];

        if($this->RequestHandler->isAjax()){
            if($_FILES['apifile']['size'] > 0)
            {
                App::import('vendor', 'S3', array('file' => 'S3.php'));
                $bucket = apireconbucket;
                $document_path = $_FILES['apifile']['tmp_name'];
                $filename = $vendor_id.'_'.$date.'_txns.csv';
                $s3 = new S3(awsAccessKey, awsSecretKey);
                $s3_response = $s3->putObjectFile($document_path, $bucket, $filename, S3::ACL_PUBLIC_READ);

                if($s3_response)
                {
                    if (($handle = fopen($document_path, "r")) !== FALSE)
                    {
                        $row = 1;
                        while (($data = fgetcsv($handle, 0, ",")) !== FALSE):

                        $num = count($data); // Get total Field count

                        for ($c=0; $c < $num; $c++):

                        $temp[$row][]=$data[$c];

                        endfor;

                        $row++;

                        endwhile;

                        fclose($handle);
                    }
                    array_shift($temp);

                    Configure::load('api_recon');
                    $recharge_apis = Configure::read('recharge_apis');

                    $txn_id_col = isset($recharge_apis[$vendor_id]['txn_id'])?$recharge_apis[$vendor_id]['txn_id']:'';
                    $ref_id_col = $recharge_apis[$vendor_id]['vendor_ref_id'];
                    $status_col = $recharge_apis[$vendor_id]['status'];
                    $api_status = $recharge_apis[$vendor_id]['api_status'];
                    $amount_col = $recharge_apis[$vendor_id]['amount'];
                    $type = $recharge_apis[$vendor_id]['type'];
                    $txn_ids = array();
                    $ref_ids = array();
                    $api_response = array();
                    $txnid_data = array();
                    $refid_data = array();

                    if(!empty($temp)){
                        foreach($temp as $data)
                        {
                            $txn_id = preg_replace("/[^0-9]/", "", $data[$txn_id_col]);
                            $ref_id = preg_replace("/[^a-zA-Z0-9]/", "", $data[$ref_id_col]);

                            if(!empty($txn_id)){
                                $txn_ids[] = $txn_id;
                            }
                            if(!empty($ref_id)){
                                $ref_ids[] = $ref_id;
                            }
                        }

                        if(!empty($txn_ids)){
                            $query = "SELECT * FROM api_transactions WHERE txn_id in ('".implode("','",$txn_ids)."') AND date = '$date' AND vendor_id = '$vendor_id'";
                            $txnid_d = $this->Retailer->query($query);
                            foreach($txnid_d as $txndata){
                                $txn_id = $txndata['api_transactions']['txn_id'];
                                $txnid_data[$txn_id] = $txndata;
                            }
                        }
                        if(!empty($ref_ids)){
                            $query = "SELECT * FROM api_transactions WHERE ref_code in ('".implode("','",$ref_ids)."') AND date = '$date' AND vendor_id = '$vendor_id'";
                            $refid_d = $this->Retailer->query($query);
                            foreach($refid_d as $txndata){
                                $ref_id = $txndata['api_transactions']['ref_code'];
                                $refid_data[$ref_id] = $txndata;
                            }
                        }


                    }

                    $insert_qry_val = array();
                    $update_val = array();

                    if(!empty($temp))
                    {
                        foreach($temp as $data)
                        {
                            $txn_id = preg_replace("/[^0-9]/", "", $data[$txn_id_col]);
                            $ref_id = preg_replace("/[^a-zA-Z0-9]/", "", $data[$ref_id_col]);

                            $amount = preg_replace("/[^0-9.]/", "", $data[$amount_col]);
                            $vend_status = preg_replace("/[^a-zA-Z- ]/", "", $data[$status_col]);
                            $vendor_status =(($vendor_id == 63) && strstr(strtolower($vend_status),'success'))?'success':$statusList[$api_status[trim(strtolower($vend_status))]];

                            if($type == 0)//if txn id and ref id both are given
                            {
                                $result = (isset($txnid_data[$txn_id])) ? $txnid_data[$txn_id]: array();
                                $result1 = (isset($refid_data[$ref_id])) ? $refid_data[$ref_id]: array();

                                if(!empty($result) && !empty($result1)){
                                    $diff = array_diff($result['api_transactions'],$result1['api_transactions']);
                                    if(!empty($diff)){
                                        $result = array();
                                    }
                                }
                                else if(!empty($result) && empty($result1) && !empty($ref_id)){
                                    $this->Retailer->query("UPDATE api_transactions SET ref_code = '$ref_id' WHERE txn_id='$txn_id' AND vendor_id='$vendor_id");
                                }

                            }
                            elseif($type == 1)//if only ref id exists
                            {
                                $result = (isset($refid_data[$ref_id])) ? $refid_data[$ref_id]: array();
                            }
                            elseif($type == 2)//if only txn id exists
                            {
                                $result = (isset($txnid_data[$txn_id])) ? $txnid_data[$txn_id]: array();
                            }

                            if(empty($result) && !empty($vendor_status)){
                                $insert_qry_val[] = "('$vendor_id','$txn_id','$ref_id','$amount','$vendor_status','null','".date('Y-m-d H:i:s')."','$date','11')";
                            }
                            else if(!empty($result)){
                                $update_id = $result['api_transactions']['id'];
                                $update_val[$update_id] = array('vendor_status'=>$vendor_status,'updated_time'=>date('Y-m-d H:i:s'));
                            }
                        }

                        if(!empty($insert_qry_val)){
                            $insert_qry_val = array_chunk($insert_qry_val,1000,true);

                            foreach($insert_qry_val as $insert_q){
                                $insert_query = "INSERT INTO api_transactions (vendor_id,txn_id,ref_code,amount,vendor_status,server_status,updated_time,date,flag) VALUES ".implode(",",$insert_q);
                                $this->Retailer->query($insert_query);
                            }
                        }

                        if(!empty($update_val)){
                            $update_val_chunk = array_chunk($update_val,1000,true);

                            foreach($update_val_chunk as $update_val){
                                $update_ids = array_keys($update_val);
                                foreach($update_val as $key=>$val){
                                    $update_qry_1 .= " WHEN $key THEN '".$val['vendor_status']."'";
                                    $update_qry_2 .= " WHEN $key THEN '".$val['updated_time']."'";
                                }
                                $update_query = "UPDATE api_transactions SET vendor_status = CASE id $update_qry_1 ELSE vendor_status END, updated_time = CASE id $update_qry_2 ELSE updated_time END WHERE id in (".implode(",",$update_ids).")";
                                $this->Retailer->query($update_query);
                            }
                        }

                        $txn_qry1 = "SELECT * FROM "
                                . "(SELECT at.*, v.company, v.shortForm "
                                . "FROM api_transactions at "
                                . "LEFT JOIN vendors v ON (v.id = at.vendor_id) "
                                . "WHERE at.date = '$date' "
                                . "AND at.vendor_id = '$vendor_id' "
                                . ") as api_txn_data ";

                        $apiTXNs = $this->Retailer->query($txn_qry1);

                        $txn_qry2 = "SELECT va.id as va_id, va.txn_id, va.vendor_refid, va.status as current_status "
                                    . "FROM vendors_activations va  "
                                    . "WHERE va.date = '$date' "
                                    . "AND va.vendor_id = '$vendor_id' ";

                        $vaTXNs = $this->Retailer->query($txn_qry2);

                        $va_txn_data = array();

                        foreach($vaTXNs as $txndata)
                        {
                            $va_txn_data[$txndata['va']['txn_id']]['va_id'] = $txndata['va']['va_id'];
                            $va_txn_data[$txndata['va']['txn_id']]['current_status'] = $txndata['va']['current_status'];
                        }

                        $api_transactions = array();
                        $missmatchedData_serversuccess = $missmatchedData_serverfailure = array();

                        if(!empty($apiTXNs))
                        {
                            foreach ($apiTXNs as $txndata)
                            {
                                $api_txn_id = $txndata['api_txn_data']['id'];
                                $trans_id = $txndata['api_txn_data']['txn_id'];
                                $trans_ref_id = $txndata['api_txn_data']['ref_code'];
                                $trans_vendor_id = $txndata['api_txn_data']['vendor_id'];
                                $vendor_act_id = isset($va_txn_data[$txndata['api_txn_data']['txn_id']])?$va_txn_data[$txndata['api_txn_data']['txn_id']]['va_id']:0;
                                $vendor_status = $txndata['api_txn_data']['vendor_status'];
                                $trans_status = $txndata['api_txn_data']['server_status'];
                                $current_status = !empty($va_txn_data[$txndata['api_txn_data']['txn_id']]['current_status'])?$statusList[$va_txn_data[$txndata['api_txn_data']['txn_id']]['current_status']]:$txndata['api_txn_data']['server_status'];
                                $trans_status_code = !empty($va_txn_data[$txndata['api_txn_data']['txn_id']]['current_status'])?$va_txn_data[$txndata['api_txn_data']['txn_id']]['current_status']:array_search($txndata['api_txn_data']['status'],$statusList);
                                $current_datetime = date('Y-m-d H:i:s');
                                $current_date = date('Y-m-d');
                                $flag = $txndata['api_txn_data']['flag'];

                                if(strtolower($current_status) != strtolower($vendor_status))
                                { // action in case of difference
                                    if(strtolower($current_status) == "failure" && $vendor_status == "success"  && $trans_vendor_id == $vendor_id)
                                    {
                                        //                                $this->Retailer->query("INSERT into trans_pullback (vendors_activations_id,vendor_id,status,timestamp,pullback_by,pullback_time,reported_by,date) values('$vendor_act_id','$vendor_id','$trans_status_code','$current_datetime','0','0000-00-00 00:00:00','System','$current_date')");
                                        $trans_pullbackdata = array('vendors_activations_id'=>$vendor_act_id,
                                                'vendor_id'=>$vendor_id,
                                                'status'=>$trans_status_code,
                                                'timestamp'=>$current_datetime,
                                                'pullback_by'=>'0',
                                                'pullback_time'=>'0000-00-00 00:00:00',
                                                'reported_by'=>'System',
                                                'date'=>$current_date);
                                        $this->General->manage_transPullback($trans_pullbackdata);

                                        $server_failure_data = array('transId'=>$trans_id,'refCode'=>$trans_ref_id,'amount'=>$txndata['api_txn_data']['amount'],'serverStatus'=>$trans_status,'current_status'=>$current_status,'vendorStatus'=>$vendor_status,'date'=>$date);

                                        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/api_auto_recon_data.txt", date('Y-m-d H:i:s') . "transId : $trans_id Server failure : ". json_encode($server_failure_data));

                                        $missmatchedData_serverfailure['data'][] = $server_failure_data;
                                    }
                                    else
                                    {
                                        if(!(strtolower($current_status) == "failure" && $vendor_status == "null") && !(strtolower($current_status) == "null" && $vendor_status == "failure"))
                                        {
                                            $api_transactions[$api_txn_id]['txn_id'] = $trans_id;
                                            $api_transactions[$api_txn_id]['vend_short_name'] = $vendor_name = $txndata['api_txn_data']['shortForm'];
                                            $api_transactions[$api_txn_id]['vendor_name'] = $vendor_name = $txndata['api_txn_data']['company'];
                                            $api_transactions[$api_txn_id]['date'] = $trans_date = $txndata['api_txn_data']['date'];
                                            $api_transactions[$api_txn_id]['vendor_refid'] = $trans_ref_id;
                                            $api_transactions[$api_txn_id]['amount'] = $trans_amount = $txndata['api_txn_data']['amount'];
                                            $api_transactions[$api_txn_id]['status_code'] = $trans_status_code;
                                            $api_transactions[$api_txn_id]['server_status'] = $trans_status;
                                            $api_transactions[$api_txn_id]['va_id'] = $vendor_act_id;
                                            $api_transactions[$api_txn_id]['at_id'] = $api_txn_id;
                                            $api_transactions[$api_txn_id]['vendor_id'] = $trans_vendor_id;
                                            $api_transactions[$api_txn_id]['vendor_status'] = $vendor_status;
                                            $api_transactions[$api_txn_id]['current_status'] = $current_status;
                                            $api_transactions[$api_txn_id]['flag'] = $flag;

                                            $server_success_data = array('transId'=>$trans_id,'refCode'=>$trans_ref_id,'amount'=>$trans_amount,'serverStatus'=>$trans_status,'current_status'=>$current_status,'vendorStatus'=>$vendor_status,'date'=>$date);

                                            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/api_auto_recon_data.txt", date('Y-m-d H:i:s') . "transId : $trans_id Server success : ". json_encode($server_success_data));

                                            $missmatchedData_serversuccess['data'][] = $server_success_data;
                                        }
                                    }
                                }
                            }

                            //send mail of txn success at server and fail at vendor
                            if(isset($missmatchedData_serversuccess['data']) && !empty($missmatchedData_serversuccess['data']))
                            {
                                $missmatchedData_serversuccess['colhead'] = array_keys($missmatchedData_serversuccess['data'][0]);
                                $missmatchedData_serversuccess['colval'] = $missmatchedData_serversuccess['data'];
                                $missmatchedData_serversuccess['subject'] = "Auto reconcilation report for server success : ".$apiTXNs[0]['api_txn_data']['shortForm'];
                                $this->sendFormatedmail($missmatchedData_serversuccess);
                            }

                            //send mail of txn fail at server and success at vendor
                            if(isset($missmatchedData_serverfailure['data']) && !empty($missmatchedData_serverfailure['data']))
                            {
                                $missmatchedData_serverfailure['colhead'] = array_keys($missmatchedData_serverfailure['data'][0]);
                                $missmatchedData_serverfailure['colval'] = $missmatchedData_serverfailure['data'];
                                $missmatchedData_serverfailure['subject'] = "Auto reconcilation report for server failure : ".$apiTXNs[0]['api_txn_data']['shortForm'];
                                $this->sendFormatedmail($missmatchedData_serverfailure);
                            }
                            $response = array('status'=>'success','type'=>'0','data'=>$api_transactions);
                        }
                        else
                        {
                            $response = array('status'=>'failure','description'=>'No records found!','type'=>'2');
                        }
                    }
                    else
                    {
                        $response = array('status'=>'failure','description'=>'Empty file!','type'=>'2');
                    }
                }
                else
                {
                    $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.','type'=>'2');
                }
            }
            else
            {
                $response = array('status'=>'failure','description'=>'Kindly Upload your API excel','type'=>'2');
            }
            $this->set('api_txns', $api_transactions);
            $this->set('params', $params);
            echo json_encode($response);
            exit;
        }
    }

function sendFormatedmail($data=array())
{
    if(!empty($data))
    {
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

        $this->General->sendMails($data['subject'],$mail_report,array('nandan.rana@pay1.in','cc.support@pay1.in','naziya.khan@pay1.in','payments@pay1.in'),'mail');
    }
}

function ccaComplaints($from_date=NULL, $to_date=NULL) {

    if($from_date == NULL) {
        $from_date = date('Y-m-d');
    }
    if($to_date == NULL) {
        $to_date = date('Y-m-d');
    }

    $complaints = $this->Slaves->query("SELECT if(complaints.takenby = 0,'System',users.name) as user_name,va.id,p.name,va.amount,va.txn_id,va.timestamp,bc.timestamp,bc.bbps_complaint_id,bc.status bc_status,bc.complaint_type,bc.assigned_to,bc.complaint_reason,resolve_date,resolve_time FROM vendors_activations va "
                    . "JOIN products p ON (p.id = va.product_id) "
                    . "LEFT JOIN bbps_complaints bc ON (bc.vendor_activation_id = va.id) "
                    . "LEFT JOIN complaints ON (complaints.vendor_activation_id = va.id) "
                    . "LEFT JOIN users ON (complaints.takenby = users.id) "
                    . "WHERE va.vendor_id = '".BBPS_VENDOR."' AND bc.date >= '".date('Y-m-d', strtotime($from_date))."' AND bc.date <= '".date('Y-m-d', strtotime($to_date))."' GROUP BY bc.bbps_complaint_id ORDER BY va.id DESC");

    $this->set('complaints', $complaints);
    $this->set('fromDate', $from_date);
    $this->set('toDate', $to_date);
}

function resolveApiReconTxn($id)
{
    $this->autoRender = false;
    $this->User->query("UPDATE api_transactions "
                    . "SET flag = '12',updated_time = '".date('Y-m-d H:i:s')."' "
                    . "WHERE id = $id");
}

function downloadTxnReport()
{
    $this->layout = 'plain';
    $date = $this->params['form']['txn_date'];
    if($this->RequestHandler->isPost())
    {
        App::import('vendor', 'S3', array('file' => 'S3.php'));
        $bucket = dailytxnsbucket;
        $filename = 'transaction_log_'.$date.'.zip';
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $checkIfFileExists = $s3->getObjectInfo($bucket, $filename ,true);

        if(!empty($checkIfFileExists))
        {
            $presigned_url = $s3->aws_s3_link(awsAccessKey,awsSecretKey,$bucket,'/'.$filename,time() - strtotime(date('Y-m-d'))+50);
            header('Location: ' . $presigned_url);
        }
        else
        {
            $this->Session->setFlash("<b>Error</b> :  File not found.");
        }
    }
}

function bbpsComplainRegistration() {

    $this->autoRender = FALSE;

    $params = $_POST;
    $params['complaint_type'] = 'Transaction';
    $params['complaint_disposition'] = $params['disposition'];
    $params['complaint_description'] = $params['description'];
    $result = $this->Api->bbpsComplaintRegistration($params);

    echo json_encode($result);
}

function slabReport(){
    $this->layout = 'plain';

    $slab_det = $this->Slaves->query("Select * from slabs  where group_id = '6' order by id asc");
    $prod_res = $this->Slaves->query("Select id,name from products where to_show != '0' order by id asc ");
    $dist_tot = $this->Slaves->query("Select slab_id,count(id) as number from distributors where active_flag != 0 group by slab_id");
    foreach($dist_tot as $slab){
    $slab_detid[$slab['distributors']['slab_id']] = $slab['0']['number'];
    }
    $this->set('slab_det',$slab_det);
    $this->set('prod_name',$prod_res);
    $this->set('slab_detid',$slab_detid);
}

function slabInfo($val){
    $this->layout = 'plain';

        $slab_desp = $this->Slaves->query("select s.id,s.name,s.commission_dist,sp.id,sp.service_charge,sp.percent,sp.service_tax,p.id,p.name,serv.id,serv.name from slabs as s left join slabs_products as sp on (s.id =sp.slab_id)
                                           left join products as p on(p.id = sp.product_id)
                                           left join services as serv on (p.service_id = serv.id) where s.active_flag != 0 and p.to_show != 0 and serv.id IN (1,2,4) and s.id = '$val' group by p.id,s.id ORDER BY 1,2");

        foreach($slab_desp as $slabs_des){
        $slab_prod_id[] = $slabs_des['p']['id'];
        }

       $prod_id = implode(',',$slab_prod_id);
       $prod_det   = $this->Slaves->query("select id,name from products where id NOT IN ($prod_id) and to_show != 0 and service_id IN (1,2,4) ");
       $this->set('prod_det',$prod_det);
       $this->set('slab_desp',$slab_desp);

}

function slabDataUpdate() {
        $this->autoRender = FALSE;
        // Slab updation
        $id = $this->params['form']['slab_id'];
        $name = $this->params['form']['slab_name'];
        $slab_comm = $this->params['form']['slab_comm'];
        //slab product updation
        $slab_prod_id = $this->params['form']['sp_id'];
        $prod_dis = $this->params['form']['p_dis'];
        $prod_serv = $this->params['form']['p_sc'];
        $prod_tax = $this->params['form']['p_tax'];
        //slab product insert
        $ins_slab_id = $this->params['form']['ins_id'];
        $ins_prod_dis = $this->params['form']['ins_p_dis'];
        $ins_prod_name = $this->params['form']['prod_name'];
        $ins_prod_serv = $this->params['form']['ins_p_sc'];
        $ins_prod_tax = $this->params['form']['ins_p_tax'];

        // For updating Slab name and commisionid
        if (isset($id)) {
            $slabDetUPD = $this->User->query("update slabs set name = '$name',commission_dist = '$slab_comm' where id = $id");
            echo json_encode($slabDetUPD);
        } elseif (isset($slab_prod_id)) {

            $slabDetUPD = $this->User->query("update slabs_products set percent = '$prod_dis',service_charge = '$prod_serv',service_tax='$prod_tax' where id = '$slab_prod_id'");
            echo json_encode($slabDetUPD);
        } elseif (isset($ins_slab_id)) {
            $slabprodINS = $this->User->query("Insert into slabs_products(slab_id,product_id,percent,service_charge,service_tax) "
                    . "values($ins_slab_id,$ins_prod_name,$ins_prod_dis,$ins_prod_serv,$ins_prod_tax)");
            echo json_encode($slabprodINS);
        }
    }

    function slabCreation(){
        $this->autoRender = FALSE;
        //Slab Creation
        $slab_name = $this->params['form']['name'];
        $dataSource = $this->User->getDataSource();
        $dataSource->begin();
        if(isset($slab_name)) {
            $slabCRT = $dataSource->query("Insert into `slabs`(`name`, `group_id`, `commission_dist`, `active_flag`)"
                    . " VALUES ('$slab_name',6,0.50,'1')");
            $slabid = $dataSource->lastInsertId();
            if($slabid) {
                $slabprodCRT = $dataSource->query("Insert into slabs_products(slab_id,product_id,percent,service_charge,service_tax) "
                        . "Select '$slabid',product_id,percent,service_charge,service_tax from slabs_products where slab_id = '13'");

                if($slabCRT) {
                    $dataSource->commit();
                    echo json_encode($slabprodCRT);
                } else {
                    $dataSource->rollback();
                }
            } else {
                $dataSource->rollback();
            }
        }
    }
    function slabchangeFlag(){
        $this->autoRender = FALSE;

        $id = $_POST['id'];
        $flag = $_POST['flag'];
        $exist_val = $this->Slaves->query("SELECT $flag FROM slabs  WHERE id = $id");

        $up_val = $exist_val[0]['slabs'][$flag] == '0' ? '1' : '0';

        $res = $this->User->query("UPDATE slabs SET $flag = '$up_val' WHERE id = $id");

        return json_encode($res);
    }

function failureAfterSuccess(){
       $this->layout ='plain';
       //initializing the variables
       $from_date       = $this->params['form']['fas_from'];
       $to_date         = $this->params['form']['fas_to'];
       $ftxn_id         = $this->params['form']['fas_txn_id'];
       $fvendor         = $this->params['form']['vendor_id'];
       $foperator       = $this->params['form']['product_id'];
       $fservices       = $this->params['form']['service_id'];
       $modem_flag      = $this->params['form']['modem_flag'];
       $api_flag        = $this->params['form']['api_flag'];
       $excel_download  = $this->params['form']['fasexcel_fld'];

       //setting the value
       $status = array('0' => 'pending', '1' => 'success', '2' => 'failure', '3' => 'failure', '4' => 'pending', '5' => 'success');

       $fvendorno = implode("','",$fvendor);
       $fproductno = implode("','",$foperator);

       $fservicesid = implode ("','",$fservices);

        if((!isset($from_date)) && (!isset($to_date)) ){
            $from_date = date("Y-m-d");
            $to_date   = date("Y-m-d");
        }

        //For Fetching Vendors
        $vendors = $this->Retailer->query("select id, company from vendors WHERE show_flag = 1 order by company");
        //For Fetching Operators
        $products = $this->Slaves->query("select id, name from products where to_show = 1");
        //For Fetching the Users
        $users = $this->Slaves->query("select id, name from users");
        //For Fetching the Services
        $services = $this->Slaves->query("select id, name from services where toshow = 1");

        foreach($users as $user){
            $username[$user['users']['id']] = $user['users']['name'];
        }

        //Conditions
        if((isset($ftxn_id)) && (!empty($ftxn_id))){
            $fastxn_id = 'AND tms.txn_id = "' .$ftxn_id .'"';
        }
        if((isset($fvendor)) && (!empty($fvendor))){
            $fasvendor = "AND va.vendor_id IN ('$fvendorno')";
        }
        if((isset($foperator)) && (!empty($foperator))){
            $fasoperator = "AND va.product_id IN ('$fproductno')";
        }
        if((isset($fservices)) && (!empty($fservices))){
            $fasservices = "AND p.service_id IN ('$fservicesid')";
        }
        if((isset($modem_flag)) && (!empty($modem_flag))) {
            $vendor_type = 'AND v.update_flag = 1';
        }
        if((isset($api_flag)) && (!empty($api_flag))) {
            $vendor_type = 'AND v.update_flag = 0';
        }

        $fas_date = "va.date >= '$from_date' AND  va.date <= '$to_date' ";
        $fas_data = $this->Slaves->query("select va.vendor_id,va.product_id,va.mobile,va.amount,va.txn_id,va.vendor_refid,va.operator_id,va.status,va.date,va.timestamp,va.prevStatus, v.company,p.name,p.service_id,tms.* FROM vendors_activations as va
                                                right JOIN txn_mismatch_status as tms  on (va.txn_id = tms.txn_id and va.date=tms.va_date)
                                                    LEFT JOIN vendors as v on(va.vendor_id = v.id)
                                                    LEFT  JOIN products as p on (va.product_id = p.id)
                                                where
                                                 $fas_date $fastxn_id $fasvendor $fasoperator $vendor_type $fasservices order by va.id asc;");

        if($excel_download == '1'){
            $this->autoRender = false;
            App::import('Helper', 'csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();
            $line = array();
            $line = array('0' => 'Transaction Id', '1' => 'Vendor Id', '2' => 'Vendor', '3' => 'Operator', '4' => 'Amount','5' => 'Prev Status',
               '6' => 'Curr Status','7' => 'Vendor Status', '8' => 'Date','9'=>'Action Taken On','10' =>'Action Taken By');
            $i = 10;
            $csv->addRow($line);

            foreach($fas_data as $fas):
                $temp[0] = $fas['tms']['txn_id'];
                $temp[1] = $fas['va']['vendor_refid'];
                $temp[2] = $fas['v']['company'];
                $temp[3] = $fas['p']['name'];
                $temp[4] = $fas['va']['amount'];
                $temp[5] = $status[$fas['va']['prevStatus']];
                $temp[6] = $status[$fas['va']['status']];
                $temp[7] = ($fas['tms']['type'] == 1)?"failure":'';
                $temp[8] = $fas['tms']['added_on'];
                $temp[9] = $fas['tms']['handled_on'];
                $temp[10] = $username[$fas['tms']['user_id']];
                $csv->addRow($temp);
                $i ++;
            endforeach;
           echo $csv->render('Failure_After_Success' . $from_date . '_' . $to_date . '.csv');
        }
        else {
        $this->set('vendors', $vendors);
        $this->set('products', $products);
        $this->set('services',$services);
        $this->set('from_date',$from_date);
        $this->set('to_date',$to_date);
        $this->set('ftxn_id',$ftxn_id);
        $this->set('fvendor',$fvendor);
        $this->set('foperator',$foperator);
        $this->set('fservices',$fservices);
        $this->set('fas_data',$fas_data);
        $this->set('modem_flag',$modem_flag);
        $this->set('api_flag',$api_flag);
        $this->set('status',$status);
        $this->set('username',$username);
        $this->render('fas_report');
      }
   }


   function fasManualFailure(){
        $ref_code = $_REQUEST['id'];
        $fas_time = date("Y-m-d H:i:s");
        $fas_userid = $_SESSION['Auth']['User']['id'];
        // For checking whether the userid or handling time is define previously or not
        $tms = $this->User->query("select tms.user_id,tms.handled_on from txn_mismatch_status tms where tms.txn_id = '" . $ref_code."' ");
        $user_id = $tms[0]['tms']['user_id'];
        $upd_time = $tms[0]['tms']['handled_on'];
        //updating the user_id and handling time of a user when he/she will fail the transation.
        if(((!isset($user_id) || empty($user_id))) && ((!isset($upd_time) || empty($upd_time)))){
            $upfas =  $this->User->query('update txn_mismatch_status set user_id = "'.$fas_userid.'" , handled_on = "'.$fas_time.'" where txn_id = "' . $ref_code.'"');
        }
        $this->manualFailure($ref_code);

    $this->autoRender = false;
    }

    function getRecoveryData()
    {
        $this->layout = 'plain';

        if( count($this->params['form']) > 0 ){
            $params = $this->params['form'];
            $_SESSION['recovery_search_params'] = json_encode($params);
        }else if( array_key_exists('recovery_search_params',$_SESSION) && !empty ($_SESSION['recovery_search_params']) ){
            $params = json_decode($_SESSION['recovery_search_params'],TRUE);
        }

        $user_id = $params['user_id'];
        $service_id = $params['service_id'];
        $status = $params['status'];
        $from_date = $params['from_date'];
        $to_date = $params['to_date'];
        $cause = $params['cause'];
        $subcause = $params['subcause'];
        $page = isset($params['download']) ? $params['download'] : "";

        $products = Configure::read('products');
        $mpos_plans = Configure::read('plans');
        $status_list = array('1'=>'Temporary Default','2'=>'Permanent Default','3'=>'Recovered','4'=>'Refund');

        $status_cond = '';
        $month_year_cond = '';
        $select_cond = '';
        $tbl = '';
        $rec_cond = '';
        $user_id_cond = '';
        $rec_query = '';
        $rc_user_ids = array();

        $recovery_causes = $this->Slaves->query('SELECT * FROM taggings_new WHERE module_id = 72');

        $excluded_users = array(5711708,11470265,31918718,47007315,47007593,47007845,47199184,77769009,37255496);

        $first_3_month_free = array("09811109116123015224","09813309116121610025","09813309116121610027",
            "09811109116123015159","09813309116121610025","09811109116123014933","09811109116123015160",
            "09811109116123014934","09811109116123014937","09813309116121610027","09811109116123014935",
            "09811109116123014936","09811109116123015224","09813309116123004545","09813309116123004539",
            "09813309116123004542","09811109116123015183","09813309116123004529","09813309116123004554",
            "09813309116123004555","09813309116123004562","09813309116123004552",
            "09813309116123004573","09813309116123004579");

        $causes = array();
        $subcauses = array();
        /*GET RECOVERY CAUSES AND SUBCAUSES*/
        foreach($recovery_causes as $rec)
        {
            if($rec['taggings_new']['parent_id'] == 0)
            {
                $rec_causes[$rec['taggings_new']['id']]['label'] = $rec['taggings_new']['name'];
                $causes[$rec['taggings_new']['id']] = $rec['taggings_new']['name'];
            }
            else
            {
                $rec_causes[$rec['taggings_new']['parent_id']]['subcauses'][$rec['taggings_new']['id']] = $rec['taggings_new']['name'];
            }
        }
        /**/

        //GET SERVICE PLANS
        $service_plans = $this->Serviceintegration->getServicePlans();
        $service_plans = json_decode($service_plans,true);
        $services = $this->Serviceintegration->getAllServices();
        $services = json_decode($services,true);

        /*GET RECOVERED TXNS*/
        if(!empty($from_date) && !empty($to_date))
        {
            if(in_array($status, array(3,4)) || empty($status)) //RECOVERED AND REVERSED TXNS
            {
                $service_id_cond = !empty($service_id)?'AND rc.service_id = '.$service_id.' ':'';
                $user_id_cond = !empty($user_id)?'AND rc.user_id = '.$user_id.' ':'';
                $status_cond = $status == 3?'AND type = 1 ':($status == 4?'AND type = 2 ':'');
                $rec_cause = !empty($cause)?'AND c.tag_id = '.$cause.' ':'';
                $rec_subcause = !empty($subcause)?'AND c.subtag_id = '.$subcause.' ':'';

                $rec_query = 'SELECT rc.*,c.* '
                            . 'FROM recovery_info rc '
                            . 'JOIN (SELECT * FROM comments_new ORDER BY id DESC) AS c ON (rc.id = c.ref_id) '
                            . 'WHERE 1 '
                            . 'AND recovery_date >= "'.$from_date.'" '
                            . 'AND recovery_date <= "'.$to_date.'" '
                            . ''.$service_id_cond.' '.$user_id_cond.' '.$status_cond.' '
                            . ''.$rec_cause.' '.$rec_subcause.' '
                            . 'GROUP BY rc.id';

                $rec_data['recovered_data'] = $this->Slaves->query($rec_query);

                if(!empty($rec_data['recovered_data'])){
                    $rc_user_ids = array_unique(array_map(function($element){
                            return $element['rc']['user_id'];
                        },$rec_data['recovered_data']));
                }

                $query = 'SELECT user_id,service_id,device_id,SUBSTRING(params,INSTR(params,"plan")+7,6) as Plan '
                . 'FROM users_services us '
                . 'WHERE 1 '
                . 'AND user_id IN ('.implode(',',$rc_user_ids).') ';

                $rec_data['plans'] = $this->Slaves->query($query);

                $plans = array();
                foreach ($rec_data['plans'] as $data)
                {
                    $plans[$data['us']['user_id']][$data['us']['service_id']] = $service_plans[$data['us']['service_id']][$data[0]['Plan']]['rental_amt'];
                }
            }
        }
        /**/

        $user_id_cond = !empty($user_id)?'AND user_id = '.$user_id.' ':'';
        $service_id_cond = !empty($service_id)?'AND service_id = '.$service_id.' ':'';
        $interval = 'if(device_id IN ("'.implode('","',$first_3_month_free).'"),3,1) ';
        $status_cond = '';
        $date_cond = '';
        if(!empty($status))
        {
            if($status == 1) //TEMPORARY DEFAULT
            {
//                $status_cond = 'AND service_flag NOT IN (0,2) AND ((next_rental_debit_date IS NULL AND TIMESTAMPDIFF(DAY, DATE_ADD(rental_activation_date,INTERVAL +'.$interval.' MONTH), CURDATE()) >= 2) OR (TIMESTAMPDIFF(DAY, next_rental_debit_date, CURDATE()) >= 2)) ';
                $status_cond = 'AND service_flag NOT IN (0,2) ';
                $date_cond = 'AND ((next_rental_debit_date IS NULL AND DATE_ADD(DATE_ADD(rental_activation_date,INTERVAL +'.$interval.' MONTH), INTERVAL 2 DAY) >= "'.$from_date.'" AND DATE_ADD(DATE_ADD(rental_activation_date,INTERVAL +'.$interval.' MONTH), INTERVAL 2 DAY) <= "'.$to_date.'" ) OR (DATE_ADD(next_rental_debit_date,INTERVAL 2 DAY) >= "'.$from_date.'" AND DATE_ADD(next_rental_debit_date,INTERVAL 2 DAY) <= "'.$to_date.'")) ';
            }
            else if($status == 2) //PERMANENT DEFAULT
            {
                $status_cond = 'AND service_flag = 2 ';
                $date_cond = 'AND ((next_rental_debit_date IS NULL AND DATE_ADD(DATE_ADD(rental_activation_date,INTERVAL +'.$interval.' MONTH), INTERVAL 7 DAY) >= "'.$from_date.'" AND DATE_ADD(DATE_ADD(rental_activation_date,INTERVAL +'.$interval.' MONTH), INTERVAL 7 DAY) <= "'.$to_date.'" ) OR (DATE_ADD(next_rental_debit_date,INTERVAL 7 DAY) >= "'.$from_date.'" AND DATE_ADD(next_rental_debit_date,INTERVAL 7 DAY) <= "'.$to_date.'"))';
            }
        }
        else
        {
            $status_cond = 'AND (service_flag NOT IN (0,2) AND ((next_rental_debit_date IS NULL AND DATE_ADD(DATE_ADD(rental_activation_date,INTERVAL +'.$interval.' MONTH), INTERVAL 2 DAY) >= "'.$from_date.'" AND DATE_ADD(DATE_ADD(rental_activation_date,INTERVAL +'.$interval.' MONTH), INTERVAL 2 DAY) <= "'.$to_date.'" ) OR (DATE_ADD(next_rental_debit_date,INTERVAL 2 DAY) >= "'.$from_date.'" AND DATE_ADD(next_rental_debit_date,INTERVAL 2 DAY) <= "'.$to_date.'"))'
                    . 'OR (service_flag = 2 AND ((next_rental_debit_date IS NULL AND DATE_ADD(DATE_ADD(rental_activation_date,INTERVAL +'.$interval.' MONTH), INTERVAL 7 DAY) >= "'.$from_date.'" AND DATE_ADD(DATE_ADD(rental_activation_date,INTERVAL +'.$interval.' MONTH), INTERVAL 7 DAY) <= "'.$to_date.'" ) OR (DATE_ADD(next_rental_debit_date,INTERVAL 7 DAY) >= "'.$from_date.'" AND DATE_ADD(next_rental_debit_date,INTERVAL 7 DAY) <= "'.$to_date.'")))) ';
        }

        if(!in_array($status,array(3,4)))
        {
            $query = 'SELECT user_id,service_id,device_id,service_flag,SUBSTRING(params,INSTR(params,"plan")+7,6) as Plan,DATE_FORMAT(created_on,"%Y-%m-%d") as created_at,'
                . 'CASE WHEN next_rental_debit_date IS NULL THEN DATE_ADD(rental_activation_date,INTERVAL +'.$interval.' MONTH) ELSE next_rental_debit_date END as rental_due_from,'
                . 'CASE WHEN next_rental_debit_date IS NULL THEN TIMESTAMPDIFF(MONTH, rental_activation_date, CURDATE()) '
                . 'ELSE TIMESTAMPDIFF(MONTH, next_rental_debit_date, CURDATE()) +1 END AS rental_due_count '
                . 'FROM users_services us '
                . 'WHERE 1 '
                . 'AND user_id NOT IN ('.implode(',',$excluded_users).') '
                . ''.$status_cond.' '.$date_cond.' '.$service_id_cond.' '.$user_id_cond.' ';

            $rec_data['user_services'] = $this->Slaves->query($query);
        }

        $userid_cond = !empty($user_id)?'AND ul.user_id = '.$user_id.' ':'';
        $serviceid_cond = !empty($service_id)?'AND ul.service_id = '.$service_id.' ':'';

        $last_rental_paid_date = $this->Slaves->query('SELECT ul.user_id,ul.service_id,MAX(ul.date) AS rental_paid_date FROM users_nontxn_logs ul JOIN retailers r ON (ul.user_id = r.user_id) WHERE ul.type = 20 '.$userid_cond.' '.$serviceid_cond.' GROUP BY ul.user_id,ul.service_id');

        $last_payment_details = array();

        foreach($last_rental_paid_date as $data)
        {
            $last_payment_details[$data['ul']['user_id']][$data['ul']['service_id']]['rental_paid_date'] = $data[0]['rental_paid_date'];
        }

        $user_cond = !empty($user_id)?'AND wt.user_id = '.$user_id.' ':'';
        $service_cond = !empty($service_id)?'AND p.service_id = '.$service_id.' ':'';

        if(($service_id == 11) || empty($service_id)) //MICROFINANCE
        {
            $user_id_cond = !empty($user_id)?'AND br.user_id = '.$user_id.' ':'';

            if($status != 3){
                $query = 'SELECT br.user_id,l.loan_number,l.emi_amount,l.payment_due,l.total_amount_payable,l.total_amount,l.due_date,sum(e.actual_amount) as emi_due,TIMESTAMPDIFF(MONTH, l.due_date, CURDATE()) AS due_count '
                        . 'FROM loan l '
                        . 'JOIN emi e ON (l.id = e.loan_id) '
                        . 'JOIN borrower br ON (l.borrower_id = br.id) '
                        . 'WHERE 1 '
                        . ' '.$user_id_cond.' '
                        . 'AND e.is_defaulter = 1 '
                        . 'GROUP BY l.id ';

                $rec_data['mf'] = $this->Microfinance->query($query);

                $last_emi_paid_date_qry = 'SELECT br.user_id,e.loan_id,max(e.emi_date) as emi_date '
                        . 'FROM emi e '
                        . 'JOIN loan l ON (l.id = e.loan_id) '
                        . 'JOIN borrower br ON (l.borrower_id = br.id) '
                        . 'WHERE 1 '
                        . ' '.$user_id_cond.' '
                        . 'AND e.is_defaulter = 0 '
                        . 'GROUP BY l.id ';

                $last_emi_paid_date = $this->Microfinance->query($last_emi_paid_date_qry);

                foreach($last_emi_paid_date as $data)
                {
                    $emi_paid_date[$data['br']['user_id']] = $data[0]['emi_date'];
                }
            }
        }

        $userids = $mf_user_ids = array();

        if(!empty($rec_data['user_services'])){
            $userids = array_map(function($element){
                    return $element['us']['user_id'];
                },$rec_data['user_services']);
        }

        if(!empty($rec_data['mf'])){
            $mf_user_ids = array_unique(array_map(function($element){
                    return $element['br']['user_id'];
                },$rec_data['mf']));
        }

        $user_ids = array_merge($userids,$mf_user_ids,$rc_user_ids);

        if(!in_array($status,array(3,4)))
        {
            $rec_cause = !empty($cause)?'AND c.tag_id = '.$cause.' ':'';
            $rec_subcause = !empty($subcause)?'AND c.subtag_id = '.$subcause.' ':'';
            $rec_query = 'SELECT rc.*,c.* '
                        . 'FROM recovery_info rc '
                        . 'JOIN (SELECT * FROM comments_new ORDER BY id DESC) AS c ON (rc.id = c.ref_id) '
                        . 'WHERE 1 '
                        . 'AND rc.recovery_date IS NULL '
                        . 'AND rc.user_id IN ('.implode(',',$user_ids).') '
                        . ''.$rec_cause.' '.$rec_subcause.' '
                        . 'GROUP BY rc.id';

            $rec_data['defaulter_data'] = $this->Slaves->query($rec_query);

            foreach($rec_data['defaulter_data'] as  $data)
            {
                $defaulter_data[$data['rc']['user_id']][$data['rc']['service_id']] = $data;
            }
        }

        $last_settlement_mode = $this->Slaves->query('SELECT * FROM('
                                                . 'SELECT wt.user_id,p.service_id,wt.settlement_mode '
                                                . 'FROM wallets_transactions wt '
                                                . 'JOIN products p ON (wt.product_id = p.id) '
                                                . 'WHERE 1 '
                                                . ''.$user_cond.' '.$service_cond.' AND wt.user_id IN ('.implode(',',$user_ids).') '
                                                . 'ORDER BY wt.date DESC '
                                                . ') AS txns '
                                                . 'GROUP BY user_id,service_id');

        foreach ($last_settlement_mode as $data)
        {
            $last_payment_details[$data['txns']['user_id']][$data['txns']['service_id']]['settlement_flag'] = ($data['txns']['settlement_mode'] == 0)?'Wallet':(($data['txns']['settlement_mode'] == 1)?'Bank':'');
        }
        /*GET USER INFO AND LAST TXN DATA*/
        $user_info = $this->Slaves->query('SELECT d.id as dist_id,d.user_id as dist_user_id,u.id as user_id,u.balance,r.shopname as ret_name,r.mobile as ret_mob,d.company as dist_name,d.mobile as dist_mob,rm.name as rm_name,rm.mobile as rm_mob '
                                        . 'FROM users u '
                                        . 'LEFT JOIN retailers r ON (u.id = r.user_id) '
                                        . 'LEFT JOIN distributors d ON (r.parent_id = d.id OR u.id = d.user_id) '
                                        . 'LEFT JOIN rm ON (d.rm_id = rm.id OR u.id = rm.user_id) '
                                        . 'WHERE u.id IN ('.implode(',',$user_ids).') ');

        $dist_user_ids = array_map(function($element){
            return $element['d']['dist_user_id'];
        },$user_info);

        $imp_data= $this->Shop->getUserLabelData(array_merge($user_ids,$dist_user_ids),2,0);

        $user_data = array();
        $response = array();
        if(!empty($user_info))
        {
            foreach($user_info as $info)
            {
                $user_data[$info['u']['user_id']] = $info;
                if(array_key_exists($info['u']['user_id'],$imp_data)){
                    $user_data[$info['u']['user_id']]['r']['ret_name'] = $imp_data[$info['u']['user_id']]['imp']['shop_est_name'];
                }
                if(array_key_exists($info['d']['dist_user_id'],$imp_data)){
                    $user_data[$info['u']['user_id']]['d']['dist_name'] = $imp_data[$info['d']['dist_user_id']]['imp']['shop_est_name'];
                }
            }
        }

//        $ret_last_txn_data = $this->Slaves->query('SELECT r.user_id,MAX(rl.date) AS last_active_date '
//                . 'FROM retailers_logs rl '
//                . 'JOIN retailers r ON (rl.retailer_id = r.id) '
//                . 'WHERE r.user_id IN ('.implode(',',$user_ids).') '
//                . 'GROUP BY r.user_id');
        $ret_last_txn_data = $this->Slaves->query('SELECT r.user_id,MAX(rl.date) AS last_active_date '
                . 'FROM retailer_earning_logs rl '
                . 'JOIN retailers r ON (rl.ret_user_id = r.user_id) '
                . 'WHERE r.user_id IN ('.implode(',',$user_ids).')'
//                . 'AND rl.service_id IN (1,2,4,5,6,7) '
                . 'GROUP BY r.user_id');

        if(!empty($ret_last_txn_data))
        {
            foreach($ret_last_txn_data as $data)
            {
                $user_data[$data['r']['user_id']]['last_active_date'] = $data[0]['last_active_date'];
            }
        }

        $dist_last_txn_data = $this->Slaves->query('SELECT d.user_id,MAX(d.date) AS last_active_date '
                . 'FROM users_logs d '
                . 'WHERE d.user_id IN ('.implode(',',$user_ids).') '
                . 'AND d.date >= "' . date('Y-m-d',strtotime('-90 days')) . '" '
                . 'GROUP BY d.user_id');

        if(!empty($dist_last_txn_data))
        {
            foreach($dist_last_txn_data as $data)
            {
                $user_data[$data['d']['user_id']]['last_active_date'] = $data[0]['last_active_date'];
            }
        }
        /**/

        /*GET COMMENT COUNT BY REFID AND MODULE ID*/
        $rec_data['ref_ids'] = $this->Slaves->query('SELECT id FROM recovery_info rc');
        $ref_ids = array_map(function($element){
                    return $element['rc']['id'];
                },$rec_data['ref_ids']);
        $ref_ids = implode(',',$ref_ids);

        $count = $this->Comments->getCommentCount($ref_ids,72);

        foreach($count as $data)
        {
            $comment_count[$data['c']['ref_id']] = $data[0]['msg_count'];
        }
        /**/
        foreach($rec_data['user_services'] as $data)
        {
            $rental_amt = isset($service_plans[$data['us']['service_id']][$data[0]['Plan']])?$service_plans[$data['us']['service_id']][$data[0]['Plan']]['rental_amt']:0;
            if($rental_amt > 0)
            {
                $status = ($data['us']['service_flag'] == 2)?2:1;
                $rec_date = '0000-00-00 00:00:00';
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['user_id'] = $data['us']['user_id'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['service_id'] = $data['us']['service_id'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['ret_name'] = $user_data[$data['us']['user_id']]['r']['ret_name'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['ret_mob'] = $user_data[$data['us']['user_id']]['r']['ret_mob'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['dist_id'] = $user_data[$data['us']['user_id']]['d']['dist_id'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['dist_name'] = $user_data[$data['us']['user_id']]['d']['dist_name'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['dist_mob'] = $user_data[$data['us']['user_id']]['d']['dist_mob'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['rm_name'] = $user_data[$data['us']['user_id']]['rm']['rm_name'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['rm_mob'] = $user_data[$data['us']['user_id']]['rm']['rm_mob'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['balance'] = $user_data[$data['us']['user_id']]['u']['balance'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['last_active_date'] = $user_data[$data['us']['user_id']]['last_active_date'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['param1'] = $data['us']['device_id'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['due_count'] = $data[0]['rental_due_count'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['due_date'] = $data[0]['rental_due_from'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['default_date'] = $status == 1?date('Y-m-d',strtotime($data[0]['rental_due_from'].'+2 days')):date('Y-m-d',strtotime($data[0]['rental_due_from'].'+7 days'));
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['status'] = $status;
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['ref_id'] = isset($defaulter_data[$data['us']['user_id']][$data['us']['service_id']])?$defaulter_data[$data['us']['user_id']][$data['us']['service_id']]['rc']['id']:'';
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['cause'] = isset($defaulter_data[$data['us']['user_id']][$data['us']['service_id']])?$defaulter_data[$data['us']['user_id']][$data['us']['service_id']]['c']['tag_id']:'';
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['subcause'] = isset($defaulter_data[$data['us']['user_id']][$data['us']['service_id']])?$defaulter_data[$data['us']['user_id']][$data['us']['service_id']]['c']['subtag_id']:'';
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['comment'] = isset($defaulter_data[$data['us']['user_id']][$data['us']['service_id']])?$defaulter_data[$data['us']['user_id']][$data['us']['service_id']]['c']['comment']:'';
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['amount'] = isset($service_plans[$data['us']['service_id']][$data[0]['Plan']])?$service_plans[$data['us']['service_id']][$data[0]['Plan']]['rental_amt']:0;
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['due_amt'] = isset($service_plans[$data['us']['service_id']][$data[0]['Plan']])?$service_plans[$data['us']['service_id']][$data[0]['Plan']]['rental_amt'] * $data[0]['rental_due_count']:0;
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['setup_cost'] = $service_plans[$data['us']['service_id']][$data[0]['Plan']]['setup_amt'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['avlrefund_amt'] = $service_plans[$data['us']['service_id']][$data[0]['Plan']]['setup_amt'] > 3499 ? $service_plans[$data['us']['service_id']][$data[0]['Plan']]['setup_amt'] * 0.5 : $service_plans[$data['us']['service_id']][$data[0]['Plan']]['setup_amt'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['refund_amt'] = 0;
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['last_paid_date'] = $last_payment_details[$data['us']['user_id']][$data['us']['service_id']]['rental_paid_date'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['settlement_flag'] = $last_payment_details[$data['us']['user_id']][$data['us']['service_id']]['settlement_flag'];
                $response[$data['us']['user_id']][$data['us']['service_id']][$status][$rec_date]['msg_count'] = $comment_count[$defaulter_data[$data['us']['user_id']][$data['us']['service_id']]['rc']['id']];
            }
        }

        foreach($rec_data['mf'] as $data)
        {
            $status = 0;
            $service_id = 11;
            $rec_date = '0000-00-00 00:00:00';
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['user_id'] = $data['br']['user_id'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['service_id'] = 11;
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['ret_name'] = $user_data[$data['br']['user_id']]['r']['ret_name'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['ret_mob'] = $user_data[$data['br']['user_id']]['r']['ret_mob'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['dist_id'] = $user_data[$data['br']['user_id']]['d']['dist_id'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['dist_name'] = $user_data[$data['br']['user_id']]['d']['dist_name'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['dist_mob'] = $user_data[$data['br']['user_id']]['d']['dist_mob'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['rm_name'] = $user_data[$data['br']['user_id']]['rm']['rm_name'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['rm_mob'] = $user_data[$data['br']['user_id']]['rm']['rm_mob'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['balance'] = $user_data[$data['br']['user_id']]['u']['balance'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['last_active_date'] = $user_data[$data['br']['user_id']]['last_active_date'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['param1'] = $data['l']['loan_number'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['due_date'] = $data['l']['due_date'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['due_count'] = $data[0]['due_count'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['status'] = $status;
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['ref_id'] = isset($defaulter_data[$data['br']['user_id']][$service_id])?$defaulter_data[$data['br']['user_id']][$service_id]['rc']['id']:'';
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['cause'] = isset($defaulter_data[$data['br']['user_id']][$service_id])?$defaulter_data[$data['br']['user_id']][$service_id]['c']['tag_id']:'';
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['subcause'] = isset($defaulter_data[$data['br']['user_id']][$service_id])?$defaulter_data[$data['br']['user_id']][$service_id]['c']['subtag_id']:'';
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['comment'] = isset($defaulter_data[$data['br']['user_id']][$service_id])?$defaulter_data[$data['br']['user_id']][$service_id]['c']['comment']:'';
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['amount'] = $data['l']['emi_amount'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['due_amt'] = $data[0]['emi_due'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['refund_amt'] = $data['l']['total_amount_payable'] - $data['l']['total_amount'];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['last_paid_date'] = $emi_paid_date[$data['br']['user_id']];
            $response[$data['br']['user_id']][$service_id][$status][$rec_date]['msg_count'] = $comment_count[$defaulter_data[$data['br']['user_id']][$service_id]['rc']['id']];
        }

        if(!empty($cause) || !empty($subcause))
        {
            foreach ($response as $user_id=>$data)
            {
                if(!isset($defaulter_data[$user_id]))
                {
                    unset($response[$user_id]);
                }
            }
        }

        foreach($rec_data['recovered_data'] as $data)
        {
            $status = $data['rc']['type'] == 1?3:4;
            $rec_date = $data['rc']['recovered_at'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['ref_id'] = $data['rc']['id'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['user_id'] = $data['rc']['user_id'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['service_id'] = $data['rc']['service_id'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['ret_name'] = $user_data[$data['rc']['user_id']]['r']['ret_name'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['ret_mob'] = $user_data[$data['rc']['user_id']]['r']['ret_mob'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['dist_id'] = $user_data[$data['rc']['user_id']]['d']['dist_id'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['dist_name'] = $user_data[$data['rc']['user_id']]['d']['dist_name'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['dist_mob'] = $user_data[$data['rc']['user_id']]['d']['dist_mob'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['rm_name'] = $user_data[$data['rc']['user_id']]['rm']['rm_name'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['rm_mob'] = $user_data[$data['rc']['user_id']]['rm']['rm_mob'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['balance'] = $user_data[$data['rc']['user_id']]['u']['balance'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['last_active_date'] = $user_data[$data['rc']['user_id']]['last_active_date'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['param1'] = $data['rc']['param1'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['status'] = $status;
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['cause'] = $data['c']['tag_id'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['subcause'] = $data['c']['subtag_id'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['comment'] = $data['c']['comment'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['recovered_at'] = $data['rc']['recovered_at'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['amount'] = $plans[$data['rc']['user_id']][$data['rc']['service_id']];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['due_amt'] = $data['rc']['recovered_amt'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['refund_amt'] = $data['rc']['type'] == 2?$data['rc']['recovered_amt']:0;
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['recovered_amt'] = $data['rc']['type'] == 1?$data['rc']['recovered_amt']:0;
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['last_paid_date'] = $data['rc']['recovered_at'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['settlement_flag'] = $last_payment_details[$data['rc']['user_id']][$data['rc']['service_id']]['settlement_flag'];
            $response[$data['rc']['user_id']][$data['rc']['service_id']][$status][$rec_date]['msg_count'] = $comment_count[$data['rc']['id']];
        }

        if($page=='download')
        {
            $this->downloadRecoveryReport($response,$services,$status_list,$rec_causes);
        }

        $this->set('params',$params);
        $this->set('page',$page);
        $this->set('services',$services);
        $this->set('status_list',$status_list);
        $this->set('recovery_causes',$rec_causes);
        $this->set('causes',$causes);
        $this->set('recovery_data',$response);
    }

    function recoverAmount()
    {
        $this->autoRender = false;
        $params = $this->params['form'];
        $user_id = $params['rec_data']['user_id'];
        $amount = $params['recovered_amt'];
        $service_id = $params['rec_data']['service_id'];
        $module_id = 72;

        $dataSource = $this->User->getDataSource();

        $dataSource->begin();

        $rec_data = $dataSource->query('SELECT * FROM recovery_info rc WHERE user_id = '.$user_id.' AND service_id = '.$service_id.' ORDER BY id desc LIMIT 1 ');

        if($params['action'] == 1 || $params['rec_data']['service_id'] == 11)
        {
            if(empty($rec_data) || (!empty($rec_data) && $rec_data[0]['rc']['recovery_date'] != null))
            {
                $add_rec_data = $dataSource->query('INSERT INTO recovery_info(user_id,service_id,param1,recovered_amt)'
                            . 'VALUES('.$user_id.','.$service_id.',"'.$params['rec_data']['param1'].'",0)');

                $ref_id = $dataSource->lastInsertId();
            }
            else
            {
                $ref_id = $rec_data[0]['rc']['id'];
            }

            if($ref_id)
            {
                $add_comment = $this->Comments->addComment($user_id,$ref_id,$module_id,$params['cause'],$params['subcause'],addslashes($params['comment']),$_SESSION['Auth']['User']['id'],$dataSource);

                if($add_comment)
                {
                    $dataSource->commit();
                    $response = array('status'=>'success','description'=>'Data saved successfully');
                }
                else
                {
                    $dataSource->rollback();
                    $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
                }
            }
            else
            {
                $dataSource->rollback();
                $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
            }
        }

        echo json_encode($response);
    }

    function getComments()
    {
       $this->autoRender = false;
       $ref_id = $this->params['form']['ref_id'];
       $module_id = 72;
       $comments = array();

       if(!empty($ref_id))
       {
           $comments = $this->Comments->getComments($ref_id,$module_id);
       }
       $response = array();

       if(!empty($comments))
       {
           $response = array('status'=>'success','data'=>$comments);
       }

       echo json_encode($response);
   }

   function downloadRecoveryReport($recovery_data,$products,$status_list,$recovery_causes)
   {
       $this->autoRender = false;
       App::import('Helper','csv');
       $this->layout = null;
       $this->autoLayout = false;
       $csv = new CsvHelper();

       if(!empty($recovery_data))
       {
           $line = array('#','Product Title','User Id','Retailer Name/Mobile','Dist Id/Dist Name/Mobile','Rm Name/Mobile','Device/Loan Number','Rental/EMI Amt','Last Active Date','Last Settlement Mode','Current Balance','Last Paid Date','Due Date','Due Count','Rental Due/EMI Due','Setup Cost','Refund/Interest Amt','Recovered Amt','Status','Recovery Date','Cause','Sub-Cause','Comment');
           $csv->addRow($line);
           $i = 1;
           foreach ($recovery_data as $user_id => $service_data) {
               foreach ($service_data as $service_id => $status_data) {
                   foreach($status_data as $status_id => $rec_data ) {
                       foreach($rec_data as $date=>$data){
                           $line = array($i,$products[$data['service_id']],$user_id,$data['ret_name'].'/'.$data['ret_mob'],$data['dist_id'].'/'.$data['dist_name'].'/'.$data['dist_mob'],$data['rm_name'].'/'.$data['rm_mob'],$data['param1'],$data['amount'],$data['last_active_date'],$data['settlement_flag'],$data['balance'],$data['last_paid_date'],$data['due_date'],isset($data['due_count'])?$data['due_count']:'',$data['due_amt'],$data['setup_cost'],$data['refund_amt'],$data['recovered_amt'],$status_list[$data['status']],$data['recovered_at'],$recovery_causes[$data['cause']]['label'],$recovery_causes[$data['cause']]['subcauses'][$data['subcause']],$data['comment']);
                           $csv->addRow($line);
                           $i++;
                       }
                   }
               }
           }
           echo $csv->render('recovery_data_'.date('Ymd').'.csv');
       }
       exit;
   }

   function reactivateService()
   {
        $this->autoRender = false;
        $params = $this->params['form'];
        $user_id = $params['user_id'];
        $service_id = $params['service_id'];
        $amount = $params['recovered_amt'];
        $module_id = 72;
        $service_plans = $this->Serviceintegration->getServicePlans();
        $service_plans = json_decode($service_plans,true);
        $first_3_month_free = array("09811109116123015224","09813309116121610025","09813309116121610027",
            "09811109116123015159","09813309116121610025","09811109116123014933","09811109116123015160",
            "09811109116123014934","09811109116123014937","09813309116121610027","09811109116123014935",
            "09811109116123014936","09811109116123015224","09813309116123004545","09813309116123004539",
            "09813309116123004542","09811109116123015183","09813309116123004529","09813309116123004554",
            "09813309116123004555","09813309116123004562","09813309116123004552",
            "09813309116123004573","09813309116123004579");

        $interval = 'if(device_id IN ("'.implode('","',$first_3_month_free).'"),3,1) ';

        $query = 'SELECT user_id,service_id,device_id,service_flag,SUBSTRING(params,INSTR(params,"plan")+7,6) as Plan,DATE_FORMAT(created_on,"%Y-%m-%d") as created_at,'
                . 'CASE WHEN next_rental_debit_date IS NULL THEN DATE_ADD(rental_activation_date,INTERVAL '.$interval.' MONTH) ELSE next_rental_debit_date END as rental_due_from,'
                . 'CASE WHEN next_rental_debit_date IS NULL THEN TIMESTAMPDIFF(MONTH, rental_activation_date, CURDATE()) '
                . 'ELSE TIMESTAMPDIFF(MONTH, next_rental_debit_date, CURDATE()) +1 END AS rental_due_count '
                . 'FROM users_services us '
                . 'WHERE 1 '
                . 'AND user_id = '.$user_id.' '
                . 'AND service_id = '.$service_id.' ';

        $dataSource = $this->User->getDataSource();
        $dataSource->begin();

        $rental_info = $dataSource->query($query);

        $device_id = $rental_info[0]['us']['device_id'];
        $due_count = $rental_info[0][0]['rental_due_count'];
        $rental_amt = $service_plans[$rental_info[0]['us']['service_id']][$rental_info[0][0]['Plan']]['rental_amt'] * $due_count;

        if($amount > $rental_amt)
        {
            $response = array('status'=>'failure','description'=>'Amount should be less than or equal to rental amount.');
        }
        else
        {
            $user_data = $this->Bridge->getUserData(array('user_id'=>$user_id),$dataSource);
            $mobile = !empty($user_data['data']['mobile'])?$user_data['data']['mobile']:'';
            $deducted_month = $this->Shop->getMonthListFromDate($rental_info[0][0]['rental_due_from'],$due_count);
            $data = array('service_id' => $service_id,'user_id' => $user_id,'amount' => $amount,'type' => 'db','txn_type' => 'rental','product_id' => '','service_charge' => 0,'commission' => 0,'tax' => 0,'deducted_month'=> implode(',',$deducted_month));

            $response = $this->Shop->deductRental($data,$dataSource);

            if($response['status'] == 'success')
            {
                $data['service_flag'] = 1;
                $reactivate = $this->Servicemanagement->updateServiceFlag($user_id,$service_id,$data,$dataSource);

                if($reactivate)
                {
                    $day = date('d', strtotime($rental_info[0][0]['rental_due_from']));
                    $next_rental_debit_date = date('Y-m-'.$day.'', strtotime($rental_info[0][0]['rental_due_from'].'+'.$due_count.' month'));
                    $set_rental_date = $this->Shop->setNextRentalDebitDate($user_id,$service_id,$next_rental_debit_date,$dataSource);

                    if($set_rental_date['status'] == 'success')
                    {
                        $update_rec_data = $this->Shop->updateRecoveryInfo($user_id,$service_id,$device_id,$amount,1,$dataSource);

                        if($update_rec_data['status'] == 'success')
                        {
                           $ref_id = $update_rec_data['ref_id'];
                           $add_comment = $this->Comments->addComment($user_id,$ref_id,$module_id,$params['cause'],$params['subcause'],addslashes($params['comment']),$_SESSION['Auth']['User']['id'],$dataSource);

                           if($add_comment)
                           {    //call smartpay api to update recovery status of a user
                                $update_status = $this->Shop->reactivateService($user_id,$service_id,$data['service_flag'],$next_rental_debit_date);

                                if($update_status)
                                {
                                    $dataSource->commit();
                                    $this->Shop->sendNotification($user_id,$service_id,$mobile,$amount,$next_rental_debit_date,$deducted_month,'reactivate');
                                    $response = array('status'=>'success','description'=>'Service reactivated successfully');
                                }
                                else
                                {
                                    $dataSource->rollback();
                                    $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
                                }
                            }
                            else
                            {
                                $dataSource->rollback();
                                $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
                            }
                        }
                        else
                        {
                            $dataSource->rollback();
                            $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
                        }
                    }
                }
            }
            else
            {
                $dataSource->rollback();
//                $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
            }
        }

        return json_encode($response);
   }

   function refundAmount()
   {
        $this->autoRender = false;
        $params = $this->params['form'];
        $user_id = $params['user_id'];
        $service_id = $params['service_id'];
        $amount = $params['refund_amt'];
        $referer = ( array_key_exists('referer',$params) ) ? $params['referer'] : 'recovery_panel';
        $module_id = 72;
        $service_plans = $this->Serviceintegration->getServicePlans();
        $service_plans = json_decode($service_plans,true);

        $dataSource = $this->User->getDataSource();
        $dataSource->begin();

        $get_refund_amt = $dataSource->query('SELECT * FROM users_services us WHERE user_id = '.$user_id.' AND service_id = '.$service_id.' ');
        $param = json_decode($get_refund_amt[0]['us']['params'],true);

        if( count($param) > 0 && array_key_exists('plan',$param) && !empty($param['plan']) )
        {
            $plan = $param['plan'];
        }
        $setup_cost = $service_plans[$get_refund_amt[0]['us']['service_id']][$plan]['setup_amt'];

        if($amount < 0 || $amount > $setup_cost){
            $response = array('status'=>'failure','description'=>'Refund amount should be less than or equal to setup cost.');
        }
        else {
//            $query = 'SELECT * FROM shop_transactions st WHERE source_id = '.$user_id.' AND user_id = '.$service_id.' AND type = '.KITCHARGE.' ORDER BY date DESC LIMIT 1 ';
//            $kit_activation_data = $dataSource->query($query);
//
//            if($kit_activation_data)
//            {
                $closing_bal = $this->Shop->shopBalanceUpdate($amount,'add',$user_id,null,$dataSource,1,0);
                if($closing_bal === false){
                     $response = array('status'=>'failure','description'=>'Insufficient wallet balance');
                }
                else
                {
//                    $shop_txn_id = $kit_activation_data[0]['st']['id'];
                    $description = "Kit refunded for user id ".$user_id." by ".$_SESSION['Auth']['User']['name'];
                    $res = $this->Shop->shopTransactionUpdate(TXN_REVERSE,$amount,$user_id,1,$service_id,null,KITCHARGE,$description,$closing_bal-$amount,$closing_bal,null,null,$dataSource);

                    if($res)
                    {
                        $data['service_flag'] = 0;
                        $data['kit_flag'] = 2;
                        // $data['params'] = '[]';
                        // $data['service_plan_id'] = 0;
                        $deactivate = $this->Servicemanagement->updateUserServiceFlags($user_id,$service_id,$data,$dataSource);

                        if($deactivate)
                        {
                            if($referer == 'recovery_panel'){
                                $update_rec_data = $this->Shop->updateRecoveryInfo($user_id,$service_id,$device_id,$amount,2,$dataSource);
                            } else {
                                $update_rec_data = array(
                                    'status' => 'success'
                                );
                            }
                            if($update_rec_data['status'] == 'success')
                            {
                                if($referer == 'recovery_panel'){
                                    $ref_id = $update_rec_data['ref_id'];
                                    $add_comment = $this->Comments->addComment($user_id,$ref_id,$module_id,$params['cause'],$params['subcause'],addslashes($params['comment']),$_SESSION['Auth']['User']['id'],$dataSource);
                                } else {
                                    $add_comment = true;
                                }
                                if($add_comment)
                                {
                                    $status = $this->Shop->updateServiceStatus($user_id,$service_id,$data);
                                    if($status)
                                    {
                                        $data['params'] = array();
                                        $log_response = $this->Servicemanagement->addServiceLog($user_id,$service_id,$data,'kit_refunded',$dataSource);
                                        if($log_response){
                                            $dataSource->commit();
                                            $response = array('status'=>'success','description'=>'Amount refunded successfully');
                                        } else {
                                            $dataSource->rollback();
                                            $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
                                        }

                                    }
                                    else
                                    {
                                        $dataSource->rollback();
                                        $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
                                    }
                                }
                                else
                                {
                                    $dataSource->rollback();
                                    $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
                                }
                            }
                            else
                            {
                                $dataSource->rollback();
                                $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
                            }
                        }
                        else
                        {
                            $dataSource->rollback();
                            $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
                        }
                    }
                    else
                    {
                        $dataSource->rollback();
                        $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
                    }
                }
//            }
//            else
//            {
//                $response = array('status'=>'failure','description'=>'Something went wrong. Please try again.');
//            }
        }
        return json_encode($response);
   }

   function getUserProfile()
   {
        $this->autoRender = false;
        $user_id = $this->params['form']['user_id'];
        $user_details = $this->UserProfile->getUserProfile($user_id);

        Configure::load('platform');
        $labels = $this->Documentmanagement->getImpLabels();

        foreach($user_details['imp_doc_data'] as $data)
        {
            $user_details['docs'][] = ($data['lsh']['pay1_status'] == 1 && $labels[$data['lsh']['label_id']]['type']==1)?$labels[$data['lsh']['label_id']]['label']:'';
        }

        $user_details['docs'] = implode(',', array_filter($user_details['docs']));
        unset($user_details['imp_doc_data']);

        $user_details['topup'] = !empty($user_details['avg_topup'][$user_id]['avg_topup'])?$user_details['avg_topup'][$user_id]['avg_topup']:0;
        unset($user_details['avg_topup']);

        $user_details['txn_details'] = array();
        foreach ($user_details['last_txn_details'][$user_id] as $data)
        {
            $user_details['txn_details'][$data['rl']['service_id']]['last_txn_date'] = $data[0]['txn_date'];
            $user_details['txn_details'][$data['rl']['service_id']]['avg_sale'] = $data['avg_sale'];
            $user_details['txn_details'][$data['rl']['service_id']]['rental_paid_date'] = '';
            $user_details['txn_details'][$data['rl']['service_id']]['rental_amt'] = '';
        }

        foreach ($user_details['last_rental_paid_data'][$user_id] as $data)
        {
            $user_details['txn_details'][$data['service_id']]['rental_paid_date'] = $data['rental_paid_date'];
            $user_details['txn_details'][$data['service_id']]['rental_amt'] = $data['amount'];
        }
        unset($user_details['last_txn_details']);
        unset($user_details['last_rental_paid_data']);

        if(!empty($user_details))
        {
            return json_encode(array('status'=>'success','data'=>$user_details));
        }
        else
        {
            return json_encode(array('status'=>'failure'));
        }
   }

}
