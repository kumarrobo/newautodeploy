<?php
class SmartpayController extends AppController {
	var $name = 'Smartpay';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv');
	var $components = array('RequestHandler','Shop','Smartpaycomp');
	var $uses = array('User','Slaves');

	function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('*');
	}

        function getUserServices($mobile)
        {
            $this->layout = 'plain';

            if(empty($mobile)):
                $mobile=$this->params['form']['mobile'];
            endif;

            $services = Configure::read('services');
            $response = array();
            $kit_enabled_services=array();

             if(isset($mobile) && is_numeric($mobile)):
                $id=$this->User->query("select r.user_id,d.commission_type from retailers r join distributors d on (r.parent_id=d.id) where r.mobile='$mobile'");
                $user_id=$id[0]['r']['user_id'];
                $commission_type=$id[0]['d']['commission_type'];

//                if(isset($user_id) && $commission_type==1):
                if( isset($user_id) ):
                $data=array('user_id'=>$user_id,'token'=>$_COOKIE['CAKEPHP']);
                $json=$this->General->curl_post(SMARTPAY_URL.'/getUserServices',$data,'POST');
                $kit_enabled_services= json_decode($json['output'],true);
                    foreach($services as $service_id => $service_name):
                        if(count($kit_enabled_services) >0):
                            $response[$service_id] = array('kit_flag' => 0,'service_flag' => 0);
                            foreach($kit_enabled_services as $index => $service_details):
                               if($service_id == $service_details['service_id']):
                                   $response[$service_id]['kit_flag'] = $service_details['kit_flag'];
                                   $response[$service_id]['service_flag'] = $service_details['service_flag'];
                                   $response[$service_id]['registration_flag'] = $service_details['registration_flag'];
                                   $response[$service_id]['device_id'] = $service_details['device_id'];
                                   $response[$service_id]['csp_id'] = $service_details['csp_id'];
                                   $response[$service_id]['csp_pass'] = $service_details['csp_pass'];
                                   $response[$service_id]['tid'] = $service_details['tid'];
                                   break;
                               endif;
                            endforeach;
                         endif;
                    endforeach;

                    $doc_details=$this->checkUserDocs($user_id);

//                elseif(isset($user_id) && $commission_type!=1):
//                    $this->Session->setFlash("<b>Error</b> :  This retailer's distributor is set to primary commission type");
                else:
                    $this->Session->setFlash("<b>Error</b> :  Mobile number does not exist.");
                endif;
            endif;

            $this->set('mobile',$mobile);
            $this->set('user_id',$user_id);
            $this->set('services',$services);
            $this->set('servicedetails',$response);
            $this->set('doc_details',$doc_details);
        }

        function checkUserDocs($user_id)

        {
            $data=array('user_id'=>$user_id,'token'=>$_COOKIE['CAKEPHP']);
            $json=$this->General->curl_post(SMARTPAY_URL.'/checkUserDocs',$data,'POST');

            $doc_labels = Configure::read('doc_labels');
            $docs_array= json_decode($json['output'],true);

            $panel_flag = TRUE;

             if($docs_array['status']=='success'):
                foreach ($docs_array['docs'] as $key => $val):
                        if( array_key_exists($key, $doc_labels) ):
                            if( !$val ):
                                $panel_flag = FALSE;
                            endif;
                        endif;
                endforeach;
             else:
                    $docs_array['docs']=$doc_labels;
                    $panel_flag = FALSE;
             endif;

            $this->set('doc_labels',$doc_labels);
            $this->set('panel_flag',$panel_flag);

            return $docs_array['docs'];
            $this->autoRender=false;
        }

        function updateUserServices()
        {
            $user_id=$this->params['form']['user_id'];
            $mobile=$this->params['form']['mobile'];
            $services=$this->params['form']['services'];
            $data=array('user_id'=>$user_id,'token'=>$_COOKIE['CAKEPHP'],'services'=>json_encode($services));

            $response=$this->General->curl_post(SMARTPAY_URL.'/updateUserServices',$data,'POST');
            $output=json_decode($response['output'],true);

            if($output['status']=='success'):
                $this->Session->setFlash("<b>Success</b> : ".$output['msg']);
            else:
                $this->Session->setFlash("<b>Error</b> : ".$output['description']);
            endif;

            $this->redirect(array('controller' => 'smartpay', 'action' => 'getUserServices/'.$mobile));
            $this->autoRender=false;
        }

        function updateUserDocs()
        {
            $user_id=$this->params['form']['user_id'];
            $mobile=$this->params['form']['mobile'] ;
            unset($this->params['form']['user_id']);
            unset($this->params['form']['mobile']);
            $docs=$this->params['form'];
            $data=array('user_id'=>$user_id,'token'=>$_COOKIE['CAKEPHP'],'docs'=>json_encode($docs));

            $response=$this->General->curl_post(SMARTPAY_URL.'/updateUserDocs',$data,'POST');
            $output=json_decode($response['output'],true);

            if($output['status']=='success'):
                $this->Session->setFlash("<b>Success</b> : ".$output['msg']);
            else:
                $this->Session->setFlash("<b>Error</b> : ".$output['description']);
            endif;

             $this->redirect(array('controller' => 'smartpay', 'action' => 'getUserServices/'.$mobile));

            $this->autoRender=false;
        }

        function saveDeviceComments()
        {
            $user_id=$this->params['form']['user_id'];
            $service_id=$this->params['form']['service_id'];
            $device_id=$this->params['form']['device_id'];
            $data=array('user_id'=>$user_id,'token'=>$_COOKIE['CAKEPHP'],'service_id'=>$service_id,'device_id'=>$device_id);
            $response=$this->General->curl_post(SMARTPAY_URL.'/mapDevice',$data,'POST');
            echo $response['output'];
            $this->autoRender=false;
        }

        function saveCspComments()
        {
            $user_id=$this->params['form']['user_id'];
            $service_id=$this->params['form']['service_id'];
            $csp_id=$this->params['form']['csp_id'];
            $csp_pass=$this->params['form']['csp_pass'];
            $data=array('user_id'=>$user_id,'token'=>$_COOKIE['CAKEPHP'],'service_id'=>$service_id,'csp_id'=>$csp_id,'csp_pass'=>$csp_pass);
            $response=$this->General->curl_post(SMARTPAY_URL.'/mapCSP',$data,'POST');
            echo $response['output'];
            $this->autoRender=false;
        }

        function saveTIDComments()
        {
            $user_id=$this->params['form']['user_id'];
            $service_id=$this->params['form']['service_id'];
            $tid=$this->params['form']['tid'];
            $data=array('user_id'=>$user_id,'token'=>$_COOKIE['CAKEPHP'],'service_id'=>$service_id,'tid'=>$tid);
            $response=$this->General->curl_post(SMARTPAY_URL.'/mapTID',$data,'POST');
            echo $response['output'];
            $this->autoRender=false;
        }

        function getRetailerList()
        {
            $this->layout = 'plain';

            $mobile=$this->params['form']['retailer_no'];
            $pay1_status=$this->params['form']['pay1_status'];
            $bank_status=$this->params['form']['bank_status'];
            $id=$this->User->query("select user_id from retailers where mobile='$mobile' ");

            $uid=!empty($id[0]['retailers']['user_id'])?$id[0]['retailers']['user_id']:"";
            $data=array('token'=>$_COOKIE['CAKEPHP'],'pay1_status'=> $pay1_status,'bank_status'=> $bank_status,'user_id'=>$uid);

            $response=$this->General->curl_post(SMARTPAY_URL.'/getuserWithUploadedKYC',$data,'POST');
            $kycdata=json_decode($response['output'],true);
            $userids=array();

            foreach($kycdata as $data):
                $userids[]=$data['user_id'];
            endforeach;

            $userids=count($userids)>1?implode(",", $userids):(!empty($userids)?$userids[0]:$userids);
            $mobilenos=$this->User->query("select user_id,mobile,shopname from retailers where user_id in ($userids)");
            $mobilenos = $this->Shop->getUserLabelData($userids,2,0);


            foreach ($kycdata as $data):
                foreach($mobilenos as $val):
                    if($val['retailers']['user_id']==$data['user_id']):
                        $data['mobile']=$val['retailers']['mobile'];
                        // $data['shopname'] = $val['retailers']['shopname'];
                        $data['shopname'] = $imp_data[$val['retailers']['user_id']]['imp']['shop_est_name'];
                    endif;
                endforeach;
                $retailerdata[]=$data;
            endforeach;


            $this->set('mobile',$mobile);
            $this->set('pay1_status',$pay1_status);
            $this->set('bank_status',$bank_status);
            $this->set('retailerdata',$retailerdata);
        }

        function getSettlementDetails()
        {
            $this->layout = 'plain';
            ini_set('memory_limit','1024M');
            $datetime=date("Y-m-d H:i:s");
            $services = Configure::read('services');
            $service_type = Configure::read('service_type');
            $device_type = Configure::read('device_type');
            $vendors = Configure::read('vendor');
            $settlementDetails=array();


            $params=(isset($_SESSION['form_data']))?$_SESSION['form_data'] : $this->params['form'];
            unset($_SESSION['form_data']);



//            $date=!empty($params['txn_date'])?$params['txn_date']:date('Y-m-d');
            $from_date=!empty($params['from_date'])?$params['from_date']:date('Y-m-d');
            $to_date=!empty($params['to_date'])?$params['to_date']:date('Y-m-d');
            $page = isset($params['download']) ? $params['download'] : "";
            $txn_ids=($page=='txndownload')?$params['txn_ids']:$params['txn_id'];
            $from_bank=$params['from_bank'];
            if(!empty($params['mobile_no'])):
            $id=$this->User->query("select user_id from retailers where mobile='{$params['mobile_no']}'");
            $user_id=$id[0]['retailers']['user_id'];
                if(empty($user_id)):
                    $this->Session->setFlash("<b>Error</b> : Invalid Mobile Number. Please try again.");
                endif;
            endif;





             if($page=='txndownload')
             {
                //   $data=array('token'=>$_COOKIE['CAKEPHP'],'txn_id'=>$txn_ids);

                  $data=array('token'=>$_COOKIE['CAKEPHP'],
                    'txn_id'=>$txn_ids,
                    'from_txn_date'=>$from_date,
                    'to_txn_date'=>$to_date,
                    'user_id'=>$user_id,
                    'txn_status'=>$params['txn_status'],
                    'service_type'=>implode(',',$params['service_type']),
                    'settlement_mode'=>$params['settlement_mode'],
                    'txn_type'=>$params['txn_type'],
                    'device_type'=>implode('',$params['device_type']),
                    'vendor_id'=>implode(',',$params['vendor_id']),
                    'error_code'=>$params['error_code']
                    );
              }
             else
             {
                $data=array('token'=>$_COOKIE['CAKEPHP'],
                    'txn_id'=>$txn_ids,
                    'from_txn_date'=>$from_date,
                    'to_txn_date'=>$to_date,
                    'user_id'=>$user_id,
                    'txn_status'=>$params['txn_status'],
                    'service_type'=>implode(',',$params['service_type']),
                    'settlement_mode'=>$params['settlement_mode'],
                    'txn_type'=>$params['txn_type'],
                    'device_type'=>implode(',',$params['device_type']),
                    'vendor_id'=>implode(',',$params['vendor_id']),
                    'error_code'=>$params['error_code']
                    );
              }

            //$response=$this->General->curl_post(SMARTPAY_URL.'/fetchCustReport',$data,'POST');


//            echo '<pre>' . __FILE__ . '-' . __LINE__ . '<br>';
//            print_r($data);
//            exit;

            if($page=='dispute'){
                $dispute_res = $this->Smartpaycomp->fetchDisputeData($data,$page);
                $dispute_data = json_decode($dispute_res,true);

                if($dispute_data['status']=='success'){
                    $this->downloadDisputeDetails($dispute_data['data']);
                } else{
                    $this->Session->setFlash("<b>Error</b> : ".$dispute_data['description']);
                    $this->redirect( '/smartpay/getSettlementDetails');
                }
            }


            $response = $this->Smartpaycomp->fetchCustomerReport($data,$page);

            $userData=json_decode($response,true);
            if($userData['status']=='success'):
            foreach($userData['transactions'] as $data):
                $userids[]=$data['user_id'];
            endforeach;

            $userids=count($userids)>1?implode(",", $userids):(!empty($userids)?$userids[0]:$userids);

            $mobilenos=$this->User->query("select user_id,mobile,shopname from retailers where user_id in ($userids)");
            foreach ($userData['transactions'] as $data):
                foreach($mobilenos as $val):
                    if($val['retailers']['user_id']==$data['user_id']):
                        $data['mobile']=$val['retailers']['mobile'];
                    endif;
                endforeach;
                $settlementDetails[]=$data;
            endforeach;

            else:
            $this->Session->setFlash("<b>Error</b> : ".$userData['description']);

            endif;

            if($page=='download') {
                if($userData['status']=='success'){
                    $this->downloadSettlementDetails($settlementDetails);
                } else {
                    $this->redirect( '/smartpay/getSettlementDetails');
                }
            }
            elseif ($page=='txndownload')
            {
                if(!empty($from_bank))
                {
                    if($from_bank=='icici')
                    {
                        $txn_details=$this->formatExcelDataForIcici($settlementDetails);
                        App::import('Helper','csv');
                        $this->layout = null;
                        $this->autoLayout = false;
                        $csv = new CsvHelper();
                        echo $csv->array_to_csv($txn_details,"icici_".$datetime.".csv");
                    }
                    elseif($from_bank=='axis')
                    {
                        $txn_details=$this->formatExcelDataForAxisIMPS($settlementDetails);
                        App::import('Helper','csv');
                        $this->layout = null;
                        $this->autoLayout = false;
                        $csv = new CsvHelper();
                        echo $csv->array_to_csv($txn_details,"axis_".$datetime.".csv");
                    }
                }
            }
            $this->set('page',$page);
            $this->set('params',$params);
            $this->set('services',$services);
            $this->set('service_type',$service_type);
            $this->set('device_type',$device_type);
            $this->set('vendors',$vendors);
            $this->set('settlementDetails',$settlementDetails);
        }

        function saveSettlementComments()
        {
            $this->autoRender=false;
            $params=$this->params['form'];
            $data=array('token'=>$_COOKIE['CAKEPHP'],'txn_id'=>$params['txn_id'],'utr_id'=>$params['utr_id'],'utr_date'=>$params['utr_date'],'utr_comments'=>$params['utr_comments']);

            $txns = $this->Shop->getSmartpayBankTxnDetails(array($params['txn_id']));
            if( count($txns) > 0 ){
                $dataSource = $this->User->getDataSource();
                $dataSource->begin($this->User);

                foreach ($txns as $txn) {
                    $txn['method'] = 'walletApi';
                    $txn['ref_id'] = $params['utr_id'];
                    $txn['settle_flag'] = 3;
                    $sett_res = $this->Shop->settleBankTxn($txn,$dataSource);
                    if( $sett_res['status'] == 'failure' ){
                        $dataSource->rollback();
                        echo json_encode(array('status' => 'failure','description' => 'Couldn\'t settle any transaction(s).Please try again.'));
                        exit;
                    }
                }

                $response=$this->General->curl_post(SMARTPAY_URL.'/settleTxn',$data,'POST');
                $response=json_decode($response['output'],true);
                if( $response['status'] == 'success' ){
                    // commit
                    $dataSource->commit($this->User);
                    echo json_encode($response);
                    exit;
                } else {
                    // rollback
                    $dataSource->rollback();
                    echo json_encode(array('status' => 'failure','description' => 'Couldn\'t settle any transaction(s).Please try again.'));
                    exit;
                }
            } else {
                echo json_encode(array('status' => 'failure','description' => 'Transaction(s) not found'));
                exit;
            }
        }

        function downloadSettlementDetails($settlementDetails)
        {
            $service_type = Configure::read('service_type');
            App::import('Helper','csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();
            $line=array('Row','Vendor','Device Type','TXD ID','RRN','Card no','Card brand','TID','Retailer Mobile','Customer Mobile','Retailer Id','Distributor id','Auth Code','Txn Type','Settlement Mode','Bank Name','Account No','IFSC Code','Amount','Txn Status','Settlement Status','Time & Date','Settlement Date Time','Charges','TDS','Commision','Plan','Settlement Amount','Partially Settled in Wallet','Incentive','Receipt URL','UTR Id','UTR Date','UTR Comments');

            $csv->addRow($line);
            $i=1;
            foreach ($settlementDetails as $trans):
                $txn_type="";
                if($trans['service_id']==8)
                {
                    if( $trans['product_id']==$service_type[8]['MPOS Withdrawal : Non VISA'] )
                    {
                        $txn_type="CW - DD : Non VISA";
                    } else if( $trans['product_id']==$service_type[8]['MPOS Withdrawal : VISA'] ){
                        $txn_type="CW - DD : VISA";
                    }
                    elseif( $trans['product_id']==$service_type[8]['Sale - CC : EMI'] || $trans['product_id']==$service_type[8]['Sale - CC'] || $trans['product_id']==$service_type[8]['Sale - DC'])
                    {
                        $cardtype = '--';
                        if( strtolower($trans['payment_card_type']) == "debit" ){
                            $cardtype = "DC";
                        } else if( strtolower($trans['payment_card_type']) == "credit" ){
                            $cardtype = "CC";
                            if( $trans['product_id']==$service_type[8]['Sale - CC : EMI'] ){
                                $cardtype = "CC : EMI";
                            }

                        }
                        $txn_type="Sale - ".$cardtype;
                    }
                }
                elseif($trans['service_id']==9)
                {
                    $txn_type="UPI - ".$trans['vpa'];
                }
                elseif($trans['service_id']==10)
                {
                    $txn_type="AEPS";
                    if(in_array($trans['product_id'],$service_type[$trans['service_id']])){
                        $txn_type = array_search($trans['product_id'],$service_type[$trans['service_id']]);
                    }
                }
                $txn_status=$trans['txn_status']=="P"?"Pending":(($trans['txn_status']=="S")?"Success":"Failed - ".$trans['status_description']);
                $settlement_flag=$trans['settlement_flag']==0?"W - ":"B - ";
                $status=$trans['settlement_status']=="P"?"Pending":(($trans['settlement_status']=="S")?"Settled":"Failed");
                $settlement_status=$settlement_flag.$status;


                $commission = '';
                $tds = '';
                $charges = '';
                if(($trans['settlement_flag']==0) && ($trans['settlement_status']=="S")){
                    if($trans['wallet_details']['amt_settled'] > $trans['txn_amount']){
                        $commission = $trans['wallet_details']['amt_settled'] - $trans['txn_amount'];
                    } else if( $trans['wallet_details']['amt_settled'] < $trans['txn_amount'] ){
                        if( $trans['product_id']==$service_type[8]['Sale - CC : EMI'] || $trans['product_id']==$service_type[8]['Sale - CC'] || $trans['product_id']==$service_type[8]['Sale - DC'] ){
                            $charges = $trans['txn_amount'] - $trans['wallet_details']['amt_settled']-$trans['settled_amount'];
                        }
                    }
                }
                if( array_key_exists('commission',$trans['wallet_details']) ){
                    $commission = $trans['wallet_details']['commission'];
                    if( array_key_exists('tax',$trans['wallet_details']) && $trans['wallet_details']['tax'] > 0 ){
                        $commission = $commission - $trans['wallet_details']['tax'];
                    }
                }

                $line=array($i,$trans['vendor_id'],$trans['device_type'],$trans['txn_id'],$trans['rrn'],$trans['card_no'],$trans['paymentCardBrand'],$trans['tid'],$trans['mobile'],$trans['mobile_no'],$trans['user_details']['retailer_id'],$trans['user_details']['distributor_id'],$trans['auth_code'],$txn_type,($trans['settlement_flag']==0)?"Wallet":"Bank",$trans['bank_details']['bank_name'],$trans['bank_details']['acc_no'],$trans['bank_details']['ifsc_code'],$trans['txn_amount'],$txn_status,$settlement_status,$trans['txn_time'],$trans['settled_at'],$charges,$tds,$commission,$trans['plan'],$trans['wallet_details']['amt_settled'],$trans['settled_amount'],$trans['incentive_details']['amt_settled'],$trans['receipt_url'],$trans['utr_id'],$trans['utr_date'],$trans['utr_comments']);

                $csv->addRow($line);
                $i++;
            endforeach;
            ob_clean();
            echo $csv->render("settlement.csv");
            exit;
            }

        function downloadDisputeDetails($dispute_data)
        {
            App::import('Helper','csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();
            $line=array('Participant ID','Trxn Type','Device ID','RRN','PAN Number','System Trace Audit Number','Trxn Date','Transaction Amount','Timing','Stan','NPCI RESP CODE');

            $csv->addRow($line);
            // $i=1;
            foreach ($dispute_data as $trans){
                $line=array($trans['participant_id'],$trans['trxn_type'],$trans['device_id'],$trans['rrn'],$trans['pan_number'],$trans['system_trace_audit_number'],$trans['trxn_date'],$trans['trxn_amount'],$trans['timing'],$trans['stan'],$trans['npci_response_code']);
                $csv->addRow($line);
                // $i++;
            }
            ob_clean();
            echo $csv->render("npci_dispute_txn_details.csv");
            exit;
        }


            function formatExcelDataForIcici($txndata)
            {
                $txndetails=array();
                $icicitxndetails=array();

                $txndetails[0]['Payment Indicator']='Payment Indicator';
                $txndetails[0]['Beneficiary Code']='Beneficiary Code';
                $txndetails[0]['Beneficiary Name']='Beneficiary Name';
                $txndetails[0]['Amount']='Amount';
                $txndetails[0]['Payment Date']='Payment Date';
                $txndetails[0]['Debit Account Number']='Debit Account Number';
                $txndetails[0]['Credit Account Number']='Credit Account Number';
                $txndetails[0]['IFSCCode']='IFSCCode';
                $txndetails[0]['Beneficiary Address']='Beneficiary Address';
                $txndetails[0]['Print Location']='Print Location';
                $txndetails[0]['Payable Location']='Payable Location';
                $txndetails[0]['Payment Remarks']='Payment Remarks';

                foreach ($txndata as $data)
                {
                    if($data['settlement_flag']==1)
                    {
                        $txndetails[$data['mobile']]['Payment Identifier']= (strtolower($data['bank_details']['bank_name'])=="icici")?"I":"N";
                        $txndetails[$data['mobile']]['Beneficiary Code']="";
                        $txndetails[$data['mobile']]['Beneficiary Name']=$data['bank_details']['acc_name'];
                        $txndetails[$data['mobile']]['Amount']+=$data['wallet_details']['amt_settled'];
                        $txndetails[$data['mobile']]['Payment Date']='="'.date('d/m/Y').'"';
                        $txndetails[$data['mobile']]['Debit Account Number']='="'.icici_debit_account_no.'"';
                        $txndetails[$data['mobile']]['Credit Account Number']=!$this->haszeroinfront($data['bank_details']['acc_no'])?$data['bank_details']['acc_no']:'="'.$data['bank_details']['acc_no'].'"';
                        $txndetails[$data['mobile']]['IFSCCode']=$data['bank_details']['ifsc_code'];
                        $txndetails[$data['mobile']]['Beneficiary Address']="";
                        $txndetails[$data['mobile']]['Print Location']="";
                        $txndetails[$data['mobile']]['Payable Location']="";
                        $txndetails[$data['mobile']]['txn_id'][]=$data['txn_id'];
                        $txndetails[$data['mobile']]['Payment Remarks']= implode('#', $txndetails[$data['mobile']]['txn_id']);
                    }
                }

                foreach ($txndetails as $txn)
                {
                    unset($txn['txn_id']);
                    $icicitxndetails[]=$txn;
                }
                return $icicitxndetails;
            }

            function formatExcelDataForAxisIMPS($txndata)
            {
                $txndetails=array();
                $axistxndetails=array();

                $txndetails[0]['PaymentIdentifier']='PaymentIdentifier';
                $txndetails[0]['PaymentAmount']='PaymentAmount';
                $txndetails[0]['Beneficiary Name']='Beneficiary Name';
                $txndetails[0]['Bene Address 1']='Bene Address 1';
                $txndetails[0]['Bene Address 2']='Bene Address 2';
                $txndetails[0]['Bene Address 3']='Bene Address 3';
                $txndetails[0]['Bene City']='Bene City';
                $txndetails[0]['Beneficiary State']='Beneficiary State';
                $txndetails[0]['Pin Code']='Pin Code';
                $txndetails[0]['Beneficiary Account No']='Beneficiary Account No';
                $txndetails[0]['Beneficiary Email ID']='Beneficiary Email ID';
                $txndetails[0]['Email Body']='Email Body';
                $txndetails[0]['Debit Account No']='Debit Account No';
                $txndetails[0]['CRN No']='CRN No';
                $txndetails[0]['Receiver IFSC Code']='Receiver IFSC Code';
                $txndetails[0]['Receiver Account Type']='Receiver Account Type';
                $txndetails[0]['Print Branch']='Print Branch';
                $txndetails[0]['Payable Location']='Payable Location';
                $txndetails[0]['Instrument Date']='Instrument Date';
                $txndetails[0]['Additional Info 1']='Additional Info 1';
                $txndetails[0]['Additional Info 2']='Additional Info 2';
                $txndetails[0]['Swift Code']='Swift Code';

                foreach ($txndata as $data)
                {
                    if($data['settlement_flag']==1)
                    {
                        $txndetails[$data['mobile']]['PaymentIdentifier']="IMPS";
                        $txndetails[$data['mobile']]['PaymentAmount']+=$data['wallet_details']['amt_settled'];
                        $txndetails[$data['mobile']]['Beneficiary Name']=$data['bank_details']['acc_name'];
                        $txndetails[$data['mobile']]['Bene Address 1']="";
                        $txndetails[$data['mobile']]['Bene Address 2']="";
                        $txndetails[$data['mobile']]['Bene Address 3']="";
                        $txndetails[$data['mobile']]['Bene City']="";
                        $txndetails[$data['mobile']]['Beneficiary State']="";
                        $txndetails[$data['mobile']]['Pin Code']="";
                        $txndetails[$data['mobile']]['Beneficiary Account No']=!$this->haszeroinfront($data['bank_details']['acc_no'])?$data['bank_details']['acc_no']:"'".$data['bank_details']['acc_no'];
                        $txndetails[$data['mobile']]['Beneficiary Email ID']='accounts@pay1.in';
                        $txndetails[$data['mobile']]['Email Body']="";
                        $txndetails[$data['mobile']]['Debit Account Number']=axis_debit_account_no;
                        $txndetails[$data['mobile']]['txn_id'][]=$data['txn_id'];
                        $txndetails[$data['mobile']]['CRN No']= implode('#', $txndetails[$data['mobile']]['txn_id']);
                        $txndetails[$data['mobile']]['Receiver IFSC Code']=$data['bank_details']['ifsc_code'];
                        $txndetails[$data['mobile']]['Receiver Account Type']=11;
                        $txndetails[$data['mobile']]['Print Branch']="";
                        $txndetails[$data['mobile']]['Payable Location']="";
                        $txndetails[$data['mobile']]['Instrument Date']="";
                        $txndetails[$data['mobile']]['Additional Info 1']="";
                        $txndetails[$data['mobile']]['Additional Info 2']="";
                        $txndetails[$data['mobile']]['Swift Code']=$data['bank_details']['ifsc_code'];
                    }
                }

                foreach ($txndetails as $txn)
                {
                    unset($txn['txn_id']);
                    $axistxndetails[]=$txn;
                }
                return $axistxndetails;
            }

            function uploadExcel()
            {
                $file = $_FILES['utrfile']['name'];

                    if($file){
                    $allowedExtension = array("csv");
                    $getfileInfo = pathinfo($file, PATHINFO_EXTENSION);
                    if (in_array($getfileInfo, $allowedExtension)) {
                            if (!move_uploaded_file($_FILES['utrfile']['tmp_name'], "/tmp/" . $file)) {
                                    echo $msg = "Failed to move uploaded file.";
                                    die;
                            }
                            chmod("/tmp/". $file, 777);
                    } else {
                            echo $msg = "Invalid File Format!!!!!";
                            die;
                    }
                    $filepath = "/tmp/" . $file;
                if (($handle = fopen($filepath, "r")) !== FALSE) {
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

                $response=$this->updateUtrFromExcel($temp);

                $txnData=array();
                if(!empty($response))
                {
                    $failed_ids = array();
                    $error_msg = array();
                    foreach ($response as  $res)
                    {
                       $data=array('token'=>$_COOKIE['CAKEPHP'],'txn_id'=>$res['txn_id'],'utr_id'=>$res['utr_id'],'utr_date'=>$res['utr_date'],'utr_comments'=>$res['utr_comments']);


                        $txns = $this->Shop->getSmartpayBankTxnDetails(explode('#',$res['txn_id']));
                        if( count($txns) > 0 ){
                            $shop_settle_flag = true;
                            $dataSource = $this->User->getDataSource();
                            $dataSource->begin($this->User);

                            foreach ($txns as $txn) {
                                $txn['method'] = 'walletApi';
                                $txn['ref_id'] = $res['utr_id'];
                                $txn['settle_flag'] = 3;
                                $sett_res = $this->Shop->settleBankTxn($txn,$dataSource);
                                if( $sett_res['status'] == 'failure' ){
                                    $shop_settle_flag = false;
                                }
                            }

                            if($shop_settle_flag){
                                $txnResponse=$this->General->curl_post(SMARTPAY_URL.'/settleTxn',$data,'POST');
                                $txnData=json_decode($txnResponse['output'],true);

                                if($txnData['status']=="failure")
                                {
                                    $dataSource->rollback();
                                    $failed_ids[] = str_replace("#", " , ",$res['txn_id']);
                                    foreach($txnData['description'] as $msg){
                                        $error_msg[$msg] = $msg;
                                    }
                                } else {
                                    $dataSource->commit($this->User);

                            	    $utr_check = $this->Slaves->query("SELECT id FROM account_txn_details atd WHERE DATE(txn_date) >= '".date('Y-m-d', strtotime('-2 days'))."' AND description LIKE '%".$res['utr_id']."%' AND is_submitted = '0'");

                                    if ($utr_check) {
                                        $this->User->query("UPDATE account_txn_details SET account_category_id = '49', is_submitted = '1' WHERE id = '{$utr_check[0]['atd']['id']}'");
                                    }
                                }
                            } else {
                                $dataSource->rollback();
                                $failed_ids[] = str_replace("#", " , ",$res['txn_id']);
                            }
                        } else {
                            $failed_ids[] = str_replace("#", " , ",$res['txn_id']);
                        }
                    }

                    if( count($failed_ids)> 0 )
                    {
                        if( count($error_msg) > 0 ){
                            $this->Session->setFlash("<b>Error</b> : Failed Transactions - ". implode(' , ',$failed_ids)."  <br>"." <b>Reason </b>: ". implode(" <br> ", $error_msg));
                        } else {
                            $this->Session->setFlash("<b>Error</b> : Failed Transactions - ". implode(' , ',$failed_ids));
                        }

                    }
                    else
                    {
                        $this->Session->setFlash("<b>Success</b> : All  transactions settled successfully");
                    }
                }
                else
                {
                    $this->Session->setFlash("<b>Error</b> : Please enter proper UTR details");

                }

                } else {
                    $this->Session->setFlash("<b>Error</b> : Kindly Upload your UTR excel");
                }
                $this->Session->write('form_data', json_decode($this->params['form']['form_data'],TRUE));
                $this->redirect( '/smartpay/getSettlementDetails');
                $this->autoRender=false;
            }

            function updateUtrFromExcel($data)
            {
                $response=array();

                foreach($data as $row):
                    //for axis imps
                    if(isset($row['13']) && !empty($row['13']) && isset($row['22']) && !empty($row['22']) && isset($row['23']) && !empty($row['23']) && isset($row['24']) && !empty($row['24'])):

                        $txn_ids=  $row['13'];
                        $utr_id=$row['22'];
                        $utr_date=$row['23'];
                        $utr_comments=$row['24'];

                        $response[]=array('txn_id' => $txn_ids, 'utr_id' => $utr_id,'utr_date'=>$utr_date,'utr_comments'=>$utr_comments);

                    //for icici
                    elseif(isset($row['11']) && !empty($row['11']) && isset($row['12']) && !empty($row['12']) && isset($row['13']) && !empty($row['13']) && isset($row['14']) && !empty($row['14'])):

                        $txn_ids= $row['11'];
                       $utr_id=$row['12'];
                       $utr_date=$row['13'];
                       $utr_comments=$row['14'];

                       $response[]=array('txn_id' => $txn_ids, 'utr_id' => $utr_id,'utr_date'=>$utr_date,'utr_comments'=>$utr_comments);
                    endif;

                endforeach;

                return $response;
            }

            function haszeroinfront($amount)
            {
                if($amount):
                    if(substr($amount,0,2)=="00"):
                        return true;
                    elseif(substr($amount,0,1)=="0"):
                        return true;
                    endif;
                 endif;

                 return false;
            }
}
