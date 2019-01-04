<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class BridgeapisController extends AppController{
    var $name = 'Bridgeapis';
    var $components = array('RequestHandler', 'Shop', 'General','Bridge');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator');
    var $uses = array('User', 'Slaves');

    function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('*');
        error_reporting(1);
        $this->logId = rand() . time();
    }

    function apis(){

        $this->autoRender = false;
        $params = $_POST;

        $this->log("Request : " . json_encode($params) . " :: Server Info : " . json_encode($_SERVER));
        $response = $this->Bridge->requestValidation($params);
        if($response['status'] == 'success'){
            $method = $params['method'];
            if( ! method_exists($this, $method)){
                $response = array('status'=>'failure', 'errCode'=>'104', 'description'=>$this->Bridge->errorDescription('104'));
            }
            else {
                if( strtolower($params['server']) == 'travel_irctc' && $params['method'] == 'cancellationRefundApi' ){
                    $params['groups'] = $user_data['groups'];
                    $response = $this->$method($params);
                } else {
                    if(!is_numeric($params['user_id'])) {
                    $response = array('status'=>'failure', 'errCode'=>'111', 'description'=>$this->Bridge->errorDescription('111'));
                }
                $user_data = $this->General->getUserDataFromId($params['user_id']);
                if(empty($user_data) && $params['server'] != 'woocommerce'){
                    $response = array('status'=>'failure', 'errCode'=>'111', 'description'=>$this->Bridge->errorDescription('111'));
                }
                else {
                    $params['groups'] = $user_data['groups'];
                    $response = $this->$method($params);
                }
            }
            }
        }

        $this->log("Response: " . json_encode($response));
        echo json_encode($response);
    }

    private function authenticate($params){
            $dataSource = $this->User->getDataSource();
            try{
                $dataSource->begin($this->User);

                $return= $this->Bridge->checkUserExist($params,$dataSource);
                $groups = explode(',', $return[0]['groupids']);
                $group  = (count($groups) == 2) ? DISTRIBUTOR : $groups[0];

                $uuid = $dataSource->query("SELECT p.user_id,p.uuid,p.updated "
                                        . "FROM user_profile p "
                                        . "JOIN "
                                        . "(SELECT user_id,max(updated) AS updated FROM user_profile GROUP BY user_id) AS a "
                                        . "ON (p.user_id = a.user_id AND p.updated = a.updated) "
                                        . "WHERE p.user_id = '".$return['users']['id']."' "
                                        . "AND p.app_type = 'recharge_app' "
                                        . "AND p.device_type = 'web' ");

                if($return['status'] == 'failure'){
                    throw new Exception(json_encode($return));
                }

                $dataSource->commit($this->User);

                if($group == RETAILER){
                    $shop_data = $this->Shop->getShopData($return['users']['id'],$group);
                    $dist_id = $shop_data['parent_id'];
                }
                else {
                    $dist_id = 0;
                }
                $return = array('status'=>'success', 'user_id'=>$return['users']['id'], 'uuid'=>$uuid[0]['p']['uuid'], 'dist_id'=>$dist_id, 'group'=>$group,'balance'=>$return['users']['balance']);

            }
            catch (Exception $e){
                $dataSource->rollback();
                return json_decode($e->getMessage());
            }

            return $return;
    }

    private function activationApi($params){
        $dataSource = $this->User->getDataSource();
        Configure::load('bridge');

        $configs = Configure::read('secrets');

        $user_id = $params['user_id'];
        try{
            $dataSource->begin($this->User);
            $return = $this->Bridge->activate($params,$dataSource);

            $service_ids = $configs[$params['server']]['service_ids'];
            $services = $dataSource->query("SELECT service_id,kit_flag,service_flag,params FROM users_services WHERE user_id = '$user_id' AND service_id in ($service_ids)");

            $data_services = array();
            foreach($services as $service){
                $data_services[] = $service['users_services'];
            }

            $return['services'] = $data_services;
            $dataSource->commit($this->User);
        }
        catch (Exception $e){
            $dataSource->rollback();
            $return = array('status'=>'failure','errCode'=>'106','description'=>$this->Bridge->errorDescription('106'));
        }
        return $return;
    }

    private function userCreditApi($params){
        $dataSource = $this->User->getDataSource();
        $dataSource->begin($this->User);
        try{
            $return = $this->Bridge->userCreditApi($params,$dataSource);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            } else if($return['status'] == 'success'){
                $dataSource->commit($this->User);
            }
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }
        return $return;
    }

    private function utilizeUserCreditApi($params){
        $dataSource = $this->User->getDataSource();
        $dataSource->begin($this->User);
        try{
            $return = $this->Bridge->utilizeUserCreditApi($params,$dataSource);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            } else if($return['status'] == 'success'){
                $dataSource->commit($this->User);
            }
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }
        return $return;
    }

    private function addUserCreditApi($params){
        $dataSource = $this->User->getDataSource();
        $dataSource->begin($this->User);
        try{
            $return = $this->Bridge->addUserCreditApi($params,$dataSource);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            } else if($return['status'] == 'success'){
                $dataSource->commit($this->User);
            }
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }
        return $return;
    }
    
    private function commissionCalculation($params){
        $user_id     = $params['user_id'];
        $service_id  = $params['service_id'];
        $product_id  = $params['product_id'];
        
        $amount = floatval($params['amount']);
        $vendor_amount = isset($params['vendor_amount']) ? $params['vendor_amount']: 0;
        $vendor_service_charge = isset($params['vendor_service_charge']) ? $params['vendor_service_charge']: 0;
        $vendor_commission = isset($params['vendor_commission']) ? $params['vendor_commission']: 0;
        
        $comm_data = $this->Shop->commissionCalculation($user_id,$service_id,$product_id,$amount,$vendor_amount,$vendor_service_charge,$vendor_commission);
        if(empty($comm_data)){
            return array('status' => 'failure','errCode'=>'113','description' => $this->Bridge->errorDescription('113'));
        }
        else {
            return array('status' => 'success','errCode'=>'0','description' => $comm_data);
        }
    }

    private function walletApi($params){
        $dataSource = $this->User->getDataSource();
        $dataSource->begin($this->User);
        try{
            $return = $this->Bridge->walletApi($params,$dataSource);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            } else if($return['status'] == 'success'){
                $dataSource->commit($this->User);
            }
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }
        return $return;
    }

    private function voidApi($params){
        $dataSource = $this->User->getDataSource();
        try{
            $dataSource->begin($this->User);
            $return = $this->Bridge->checkWalletTxn($params['txn_id'],$params['server'],$dataSource);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            }

            $return = $this->Bridge->reverseWalletEntries($params,$return['data'],$dataSource);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            }

            $dataSource->commit($this->User);
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }

        return $return;
    }

    private function updateVendorRefId($params){
        $dataSource = $this->User->getDataSource();
        try{
            $dataSource->begin($this->User);
            $return = $this->Bridge->checkWalletTxn($params['txn_id'],$params['server'],$dataSource);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            }

            $return = $this->Bridge->updateVendorRefId($params,$dataSource);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            } else if($return['status'] == 'success'){
                $dataSource->commit($this->User);
            }
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }
        return $return;
    }

    private function voidCreditApi($params){
        $dataSource = $this->User->getDataSource();
        try{
            $dataSource->begin($this->User);
            $return = $this->Bridge->checkWalletTxn($params['txn_id'],$params['server'],$dataSource);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            }
            $return = $this->Bridge->refundCredit($params,$dataSource,$return['data']);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            }

            $dataSource->commit($this->User);
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }

        return $return;
    }

    private function userValidationApi($params){
        $dataSource = $this->User->getDataSource();
        try{
            $dataSource->begin($this->User);
            $return = $this->Bridge->validateUser($params,$dataSource);
            $dataSource->commit($this->User);
        }
        catch (Exception $e){
            $dataSource->rollback($this->User);
            $return = array('status'=>'failure','errCode'=>'106','description'=>$this->Bridge->errorDescription('106'));
        }

        return $return;
    }

    private function statusCheckApi($params){
        $dataSource = $this->User->getDataSource();
        try{
            $dataSource->begin($this->User);
            $return = $this->Bridge->checkWalletTxn($params['txn_id'],$params['server'],$dataSource);
            if($return['status'] == 'failure'){
                throw new Exception(json_encode($return));
            }

            $data = $return['data'];
            if(empty($data['amount_settled'])){
                $return = array('status'=>'failure','errCode'=>'112','description'=>$this->Bridge->errorDescription('112'));
            }
            else {
                $return = array('status'=>'success', 'shop_transaction_id'=>$data['shop_transaction_id'], 'amt_settled'=>$data['amount_settled']);
            }

            $dataSource->commit($this->User);
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }

        return $return;
    }

    private function userDataApi($params){
        $dataSource = $this->User->getDataSource();
        try{
            $dataSource->begin($this->User);
            $return = $this->Bridge->getUserData($params,$dataSource);
            $dataSource->commit($this->User);
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }

        return $return;
    }

    private function userCommissionApi($params){
        $dataSource = $this->User->getDataSource();
        try{
            $dataSource->begin($this->User);
            $return = $this->Bridge->getUserCommission($params,$dataSource);
            $dataSource->commit($this->User);
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }

        return $return;
    }

    private function profileApi($params) {
        $dataSource = $this->User->getDataSource();
        $return = $this->Bridge->profileApi($params,$dataSource);
        return $return;
    }

    function log($data){
        $filename = "bridgeapis.txt";
        $data = "LogId: " . $this->logId . ":::" . $data;
        $this->General->logData($filename, $data);
    }

    function setMasterDataInMemcache(){
        $this->autoRender = false;
        $whitelist_ips = explode(",",SMS_SERVER_IP);
        $client_ip = $this->General->getClientIP();

        if(!in_array($client_ip,$whitelist_ips)){
            return;
        }
        $this->Bridge->setMasterDataInMemcache();

    }

    function test(){
        echo "1";
        $this->autoRender = false;
    }

    function cancellationRefundApi($params){
        $dataSource = $this->User->getDataSource();
        try{
            $dataSource->begin($this->User);

            $return = $this->Bridge->cancellationRefundApi($params,$dataSource);
            $dataSource->commit($this->User);
        }
        catch (Exception $e){
            $dataSource->rollback();
            return json_decode($e->getMessage());
        }
        return $return;
    }

    function getLabelInfo($params){
        $dataSource = $this->User->getDataSource();
        $response = $this->Bridge->getLabelInfo($params,$dataSource);

        return $response;
    }

    function updateLabelStatus($params){
        $dataSource = $this->User->getDataSource();
        $response = $this->Bridge->updateLabelStatus($params,$dataSource);

        return $response;
    }
}


