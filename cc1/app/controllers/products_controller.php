<?php
//error_reporting(0);
//ini_set("log_errors", 0);

class ProductsController extends AppController
{
        var $name = 'Products';
        var $components = array('RequestHandler', 'Shop', 'Busvendors', 'General');
        var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator','Sims');
        var $uses = array('User','Slaves');
       
        
        function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('*');            
    }
        
        function index()
        {
            $this->layout = 'products';

            $product_id = $this->params['form']['product_id'];
            $products = $this->User->query("select * from products");
                      
            $this->set("product_id",$product_id);
            $this->set("products", $products);
        }
        
        function edit()
        {
            $this->layout = 'products';

            $product_id = $this->params['url']['product_id'];
            $products = $this->User->query("select * from products where id='$product_id'");
            $circles = $this->User->query("select area_code as id, area_name from mobile_numbering_area");

            $this->set("product_id",$product_id);
            $this->set("products", $products);
            $this->set("circles", $circles);
        }
        
        function editFormEntry($id)
        {
           $this->autoRender = false;

         //  $id = $this->params['form']['id'];
           $to_show = $this->params['form']['to_show'];
           $invalid=$this->params['form']['invalid'];
           $circle_yes=$this->params['form']['cy'];
           $circle_no=$this->params['form']['cn'];
                      
           $query="update products "
                   . " set to_show='$to_show', "
                   . " invalid='$invalid',"
                   . "circle_yes='$circle_yes',"
                   . "circle_no='$circle_no'"
                   . " WHERE id = '$id'";
           //send sms
           
           $products = $this->User->query("select name from products where id='$id'");
           $this->General->logData("/mnt/logs/alert.txt",date('Y-m-d H:i:s')." :: ".json_encode($products));
           if(!empty($products) && !empty($id)){
               $sms = "";
               $product = "Product : ".$products[0]['products']['name'];
//               if(!empty($invalid)){
                    $sms .=" \n amount stop $invalid Rs ";
//                }
//                if(!empty($circle_yes)){
                   $sms .=" \nCircle Activated : ".$circle_yes;
//                }
//                if(!empty($circle_no)){
                    $sms .="\n Circle In-Activated : ".$circle_no;
//                }
                if(!empty($sms)){
                    $final_sms = $product.$sms;
                    $this->General->logData("/mnt/logs/alert.txt",date('Y-m-d H:i:s')." :: ".$final_sms);
                    $data = array('user'=>$this->Session->read('Auth.User.id'),'type'=>'operator changed', 'sms'=>$final_sms);
                    $this->General->logData("/mnt/logs/alert.txt",date('Y-m-d H:i:s')." :: ".json_encode($data));
                    $this->General->curl_post("http://inv.pay1.in/alertsystem/alertreport/addInAlert",$data,'POST');
                }
                
           }
            $updatequery=$this->User->query($query);
           echo json_encode(array('status'=>'done'));
         }

        function local_vendor_mapping($vendor_id = NULL, $operator_id=NULL) {
            
                $this->layout = 'products';
            
                $edit_whr = "";
                if(isset($vendor_id)) {
                    $data = $this->User->query("SELECT * FROM local_vendor_mapping WHERE vendor_id = $vendor_id and operator_id='$operator_id'");
                    $edit_whr = "WHERE vendor_id != $vendor_id";
                    $this->set('vendor_data', $data);
                }
                
                $vendors = $this->User->query("SELECT id, company FROM vendors WHERE active_flag = 1");
                $temp_vendors = array();
                foreach($vendors as $vendor) {
                    $temp_vendors[$vendor['vendors']['id']] = $vendor['vendors'];
                }
                $vendors = $temp_vendors;
                $this->set('vendors', $vendors);

                $operators = $this->User->query('SELECT id, name FROM products WHERE service_id IN (1,2,4,6) AND to_show = 1 AND active = 1');
                $this->set('operators', $operators);
        }
        
        function l_v_m_entry() {
            
                $this->autoRender = FALSE;
                
                $action = $this->params['form']['action'];
                $vendor = $this->params['form']['vendor'];
                $operator = $this->params['form']['operator'];
                $distributed = $this->params['form']['distributed'];
                $activation = $this->params['form']['activation'];
                
                if($action == 'a') {
                    $this->User->query("INSERT INTO local_vendor_mapping VALUES ('$vendor', '$operator', '$distributed', '$activation')");
                    $this->Session->setFlash('Mapping is successfull !!!');
                } else {
                    $this->User->query("UPDATE local_vendor_mapping SET operator_id = '$operator', distributor_id = '$distributed', is_deleted = '$activation' WHERE vendor_id = '$vendor' and operator_id='$operator'");
                    $this->Session->setFlash('Mapping is updated successfully !!!');
                    $this->Shop->delMemcache('local_Vendors_map');
                }
                
                $this->redirect('listing_lvm');
        }
        
        function listing_lvm() {
            
                $this->layout = 'products';
            
                $list_lvm = $this->User->query("SELECT local_vendor_mapping.vendor_id, vendors.company, products.name,
                    local_vendor_mapping.distributor_id,local_vendor_mapping.operator_id, local_vendor_mapping.is_deleted
                    FROM
                    local_vendor_mapping
                    LEFT JOIN
                    vendors ON local_vendor_mapping.vendor_id = vendors.id
                    LEFT JOIN
                    products ON local_vendor_mapping.operator_id = products.id ORDER BY 1 DESC");
                
                $this->set('list_lvm', $list_lvm);
        }

        function a_p_mapping($id = NULL) {
            
                $this->layout = 'products';

                if(isset($id)) {
                        
                        $data = $this->User->query("SELECT * FROM amount_priority_mapping WHERE id = $id");
                        $this->set('data', $data);
                }
                $operators = $this->User->query('SELECT id, name FROM products WHERE service_id IN (1,2,4,6) AND to_show=1 AND active=1');
                $this->set('operators', $operators);

                $vendors = $this->User->query("SELECT id, company FROM vendors WHERE active_flag = 1");
                $this->set('vendors', $vendors);
        }
        
        function a_p_m_entry() {
        
                $this->autoRender = FALSE;
                
                $id             = $this->params['form']['id'];
                $operator       = $this->params['form']['operator'];
                $vendor         = $this->params['form']['vendor'];
                $min_amount     = $this->params['form']['min_amount'];
                $max_amount     = $this->params['form']['max_amount'];
                $list_amount    = $this->params['form']['list_amount'];
                $activation     = $this->params['form']['activation'];
                
                if($id > 0) {
                        $this->User->query("UPDATE amount_priority_mapping SET product_id = '$operator',"
                                . " vendor_id = '$vendor', min_amount = '$min_amount', max_amount = '$max_amount',"
                                . " list_amount = '$list_amount', is_deleted = '$activation' WHERE id = '$id'");
                        $this->Shop->delMemcache('amount_priority_map');
                } else {
                        $this->User->query("INSERT INTO amount_priority_mapping"
                                . " (product_id, vendor_id, min_amount, max_amount, list_amount, is_deleted)"
                                . " VALUES ('$operator', '$vendor', '$min_amount', '$max_amount', '$list_amount', '$activation')");
                }
                
                $this->redirect("listing_apm");
        }
        
        function listing_apm() {
            
                $this->layout = 'products';
                
                $list_apm = $this->User->query("SELECT amount_priority_mapping.*, products.name, vendors.company FROM amount_priority_mapping"
                        . " LEFT JOIN products ON products.id = amount_priority_mapping.product_id"
                        . " LEFT JOIN vendors ON vendors.id = amount_priority_mapping.vendor_id ORDER BY 1 DESC");
                $this->set('list_apm', $list_apm);
        }
        

        function uploadivr(){      
            if(isset($this->params['form']) && !empty($this->params['form'])){
                
                if(isset($this->params['form']['ivrfile']['name']) && !empty($this->params['form']['ivrfile']['name']) && 
                        isset($this->params['form']['ivrfile2']['name']) && !empty($this->params['form']['ivrfile2']['name'])
                       && isset($this->params['form']['ivrnumber']) && !empty($this->params['form']['ivrnumber'])
                       && isset($this->params['form']['ivretype']) && !empty($this->params['form']['ivretype'])
//                    && isset($this->params['form']['ivrconf']['name']) && !empty($this->params['form']['ivrconf']['name']))
                   ) {
                   //Fetching data from form
                    $activate_ivr = $this->params['form']['activate_ivr'];                                           
                    $extnumber = $this->params['form']['ivrnumber'];
                    $exttype   = $this->params['form']['ivretype'];
                //Connecting to the  ftp server and locating the storing path                   
                    $ftp_server = IVR_FTP_SERVER;
                    $ftp_user_name = IVR_FTP_USERNAME;
                    $ftp_user_pass = IVR_FTP_PASS;
                    $remote_file1 = IVR_FILE_URL.$extnumber."/silence.mp3";                    
                    $remote_file2 = IVR_FILE_URL.$extnumber."/silence1.mp3";        
                    $conn_id = ftp_connect($ftp_server);
                    // login with username and password
                    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
                    ftp_pasv($conn_id, true);
                    //for mp3 (silence) file
                    $size = $this->params['form']['ivrfile']['size'];
                    $temp_file = $this->params['form']['ivrfile']['tmp_name'];
                    $silence = $this->params['form']['ivrfile']['name'];
                    //for mp3 (silence1) file
                    $size2 = $this->params['form']['ivrfile2']['size'];
                    $temp_file2 = $this->params['form']['ivrfile2']['tmp_name'];
                    $silence1 = $this->params['form']['ivrfile2']['name'];

                    $ext = pathinfo($silence, PATHINFO_EXTENSION);
                    $ext2 = pathinfo($silence1, PATHINFO_EXTENSION);

                    if($ext=='mp3' && $ext2=='mp3'){
                        $file1="silence.mp3";
                        $file2="silence1.mp3";
                        $target_file = $target_dir . basename($file1);
                        $target_file2 = $target_dir . basename($file2);
                        //Pushing file through ftp
                        if(ftp_put($conn_id, $remote_file1, $temp_file, FTP_BINARY) && ftp_put($conn_id, $remote_file2, $temp_file2, FTP_BINARY) ) {
                             $conffile = "extensions_additional_swapnil.conf";
                           if($activate_ivr == 1) {      
                             $url    = IVR_FTP_SERVER."/active.php?";
                             $params = array("inbound" => $extnumber,"group" => $exttype);
                             $out    = $this->General->curl_post($url,$params);
                           if($out['output'] == 'sleep 10'){                               
                                $this->Session->setFlash("<b>The file ". basename($silence). ", ".basename($silence1)." and ".$conffile." has been Activated for extension $extnumber </b>", 'default', array(), 'form1');
                             }                                
                        }else if($activate_ivr == 2){                    
                            $url     = IVR_FTP_SERVER . "/deactive.php?";
                            $params  = array("inbound" => $extnumber, "group" => $exttype);
                            $out     = $this->General->curl_post($url, $params);
                          if($out['output'] == 'sleep 10'){    
                            $this->Session->setFlash("<b>The file ". basename($silence). ", ".basename($silence1)." and ".$conffile." has been Deactivated for extension $extnumber </b>", 'default', array(), 'form1');                            
                              }  
                            }                         
                          }                     
                     else {
                        $this->Session->setFlash("<b>Problem while uploading the file.</b>", 'default', array(), 'form1');
                       }
                    }else{
                       $this->Session->setFlash("<b>File is not with proper extension.</b>", 'default', array(), 'form1');
                   }
                
                }else{
                    $this->Session->setFlash("<b>Please upload IVR mp3 and config files.</b>", 'default', array(), 'form1');
                }
                
            }
            $this->layout = 'products';
            $this->set('uploadivr',$val);

        }
        
    function allTransaction($recs = 100){
        $this->layout = 'plain';
        ini_set("memory_limit", "-1");
        $allTxnArray = array();
        $frmdates           = $this->params['form']['prodfrmdate'];
        $todates            = $this->params['form']['prodtodate'];
        $prodtype           = $this->params['form']['prodProducts'];
        $retMob             = $this->params['form']['prodRetMob'];
        $shopTxnId          = $this->params['form']['prodShoptxnid'];
        $vendorTxnId        = $this->params['form']['prodVendortxnid'];
        $status_value       = $this->params['form']['prodStatus'];
        $export             = $this->params['form']['prod_fld'];
        $userId             = $this->params['form']['prodUserid'];
        $retId              = $this->params['form']['prodRetid'];
        $statusarr          = array('0' => 'Partial/Refunded', '1' => 'Success', '2' => 'Failure','3' => 'All');        
        $productionval      = implode("','", $prodtype);
        
        $nodays = (strtotime($todates) - strtotime($frmdates)) / (60 * 60 * 24);
        $nodays += 1;        
        if($status_value == ''){
            $status_value = '3';
        }
        if(empty($frmdates))
            $frmdate = date('Y-m-d');
        else
            $frmdate = $frmdates;
        if(empty($todates))
            $todate = date('Y-m-d');
        else
            $todate = $todates;        
        if(isset($productionval) && $productionval != '') {
            $production = " AND  pt.service_id IN (' $productionval ')";
        }
        if(isset($retId) && $retId != '') {
            $retailer_Id = 'AND  r.id = "' . $retId . '"';
        }
        if(isset($shopTxnId) && $shopTxnId != '') {
            $shop_id = 'AND  wt.shop_transaction_id =  "' . $shopTxnId . '"';
        }
        
        if(isset($vendorTxnId) && $vendorTxnId != '') {
            $vendor_id = 'AND  wt.txn_id =  "' . $vendorTxnId . '"';
        }		
        if(isset($userId) && $userId != '') {
            $user_id = ' AND wt. user_id = "' . $userId .'"';
        }
        
        
        if(isset($status_value) && $status_value != '' && $status_value != '3' && $status_value != '0'  ) {
            $partial = '';
            $join    = '';
            $statusval = " AND  wt.status = ' $status_value '";
            
        }else {
            $statusval = '';
            $partial = '';
            $join    = '';
        }

        //For taking the retailer mobile no and returning his/her user_id

        $retailer_userId = $this->Shop->getUserLabelData($retMob, 2, 1);
        $ret_mob = isset($retailer_userId[$retMob]['ret']['user_id']) ? $retailer_userId[$retMob]['ret']['user_id'] : $retailer_userId[$retMob]['dist']['user_id'];

        if (isset($ret_mob) && $ret_mob != '') {
            $retuser_mob = 'AND  wt.user_id = "' . $ret_mob . '"';
        }

        $serviceprod = $this->Slaves->query("select id,name from services where id > '7' AND toShow = '1' ");

        if($nodays < 8) {            
        if($status_value == '0') {                
                if(isset($shopTxnId) && $shopTxnId != '') {
                    $shop_id = 'AND  st.id =  "' . $shopTxnId . '"';
                }

            $allProdtrans = $this->paginate_query('SELECT wt.txn_id,r.id,r.parent_id,wt.user_id,wt.shop_transaction_id,wt.service_charge,wt.commission,st.id,st.amount,st.note,wt.date,wt.created,pt.service_id,st.timestamp,us.param1                                              
                                              FROM `wallets_transactions` as wt 
                                              LEFT JOIN products as pt 
                                              ON (wt.product_id = pt.id)
                                              LEFT JOIN retailers as r
                                              ON (wt.user_id = r.user_id)
                                              LEFT JOIN users_services as us
                                              ON (wt.user_id = us.user_id AND pt.service_id = us.service_id) 
                                              JOIN shop_transactions as st 
                                              ON (wt.shop_transaction_id =  st.target_id)                                                                                                                        
                                              WHERE st.type IN (39,40) AND st.date >=  "' . $frmdate . '" AND st.date <= "' . $todate . '"  
                                             '. $production . ' ' . $vendor_id . ' ' . $statusval . '' . $retuser_mob . ' '. $shop_id .'' . $user_id .' '. $retailer_Id .'
                                              order by wt.txn_id desc');                    
            foreach ($allProdtrans as $trans) {
                $allTxnArray[$i]['shopid']          = $trans['st']['id'];
                $allTxnArray[$i]['retid']           = $trans['r']['id'];
                $allTxnArray[$i]['userid']          = $trans['wt']['user_id'];
                $allTxnArray[$i]['product_activation_id'] = $trans['us']['param1'];
                $allTxnArray[$i]['description']     = $trans['st']['note'];
                $allTxnArray[$i]['status']          =  'partial/refunded';
                $allTxnArray[$i]['txn_id']          = $trans['wt']['txn_id'];
                $allTxnArray[$i]['amount']          = $trans['st']['amount'];
                $allTxnArray[$i]['datetime']        = $trans['st']['timestamp'];                
                $allTxnArray[$i]['cc']              = $trans['st']['amountss'];//((isset($trans['wt']['service_charge'])) && (!empty($trans['wt']['service_charge'])))?$trans['wt']['service_charge']:$trans['wt']['commission'];                

                $i++;
            }
        }else {                        
        $allProdtrans = $this->paginate_query('SELECT wt.txn_id,wt.status,r.id,r.parent_id,wt.user_id,wt.shop_transaction_id,wt.amount,wt.amount_settled,wt.service_charge,wt.commission,wt.description,wt.date,wt.created,pt.service_id,us.param1 
                                              FROM `wallets_transactions` as wt 
                                              LEFT JOIN products as pt 
                                              ON (wt.product_id = pt.id)
                                              LEFT JOIN retailers as r
                                              ON (wt.user_id = r.user_id)
                                              LEFT JOIN users_services as us
                                              ON (wt.user_id = us.user_id AND pt.service_id = us.service_id)                                              
                                              WHERE wt.date >=  "' . $frmdate . '" AND wt.date <= "' . $todate . '"  '.$join.'
                                             '. $production . ' ' . $vendor_id . ' ' . $statusval . '' . $retuser_mob . ' '. $shop_id .'' . $user_id .' '. $retailer_Id .'
                                              order by wt.txn_id desc'); 

        //ON (wt.user_id = us.user_id  && pt.parent_id = us.service_id)
        
            foreach ($allProdtrans as $trans) {
                $allTxnArray[$i]['shopid']          = $trans['wt']['shop_transaction_id'];
                $allTxnArray[$i]['retid']           = $trans['r']['id'];
                $allTxnArray[$i]['userid']          = $trans['wt']['user_id'];
                $allTxnArray[$i]['product_activation_id'] = $trans['us']['param1'];                
                $allTxnArray[$i]['description']     = $trans['wt']['description'];
                $allTxnArray[$i]['status']          = $trans['wt']['status'];
                $allTxnArray[$i]['txn_id']          = $trans['wt']['txn_id'];
                $allTxnArray[$i]['amount']          = $trans['wt']['amount'];
                $allTxnArray[$i]['datetime']        = $trans['wt']['created'];                
                $allTxnArray[$i]['cc']              = ((isset($trans['wt']['service_charge'])) && (!empty($trans['wt']['service_charge'])))?$trans['wt']['service_charge']:$trans['wt']['commission'];                
                $i++;
         
                }                                
        }}        
        
        if($export == '') {
        $this->set('retId',$retId);
        $this->set('frmdate', $frmdate);
        $this->set('todate', $todate);
        $this->set('allProdtrans', $allProdtrans);
        $this->set('allTxnArray',$allTxnArray);
        $this->set('serviceprod', $serviceprod);
        $this->set('retMob', $retMob);
        $this->set('prodtype', $prodtype);        
        $this->set('vendorTxnId', $vendorTxnId);
        $this->set('status_value', $status_value);
        $this->set('statusarr', $statusarr);
        $this->set('retailer_det',$retailer_det);
        $this->set('shopTxnId',$shopTxnId);
        $this->set('recs', $recs);
        $this->set('days',$nodays);
        $this->set('userId',$userId);
        }else{
            
        $this->autoRender = false;        
        App::import('Helper', 'csv');
        $this->layout = null;
        $csv = new CsvHelper();        
        $txndetails = array();        
        $txndetail = array('0'=>'Product','1'=>'Distributor Id','2'=>'Retailer Id','3'=>'Current Balance','4'=>'Product Activation Id','5'=>'Vendor txn Id','6'=>'Shop txn Id','7'=>'Description','8'=>'Amount','9'=>'Comm/Charges','10'=>'Status','11'=>'Date Time');
        $i = 12;
        
        if($status_value == '0') {                
         
           if(isset($shopTxnId) && $shopTxnId != '') {
                    $shop_id = 'AND  st.id =  "' . $shopTxnId . '"';
           }            
        $allProdtrans = $this->Slaves->query('SELECT wt.txn_id,r.id,r.parent_id,wt.user_id,wt.shop_transaction_id,wt.service_charge,wt.commission,st.id,st.amount,st.note,wt.date,wt.created,pt.service_id,pt.name,st.timestamp,us.param1,uss.balance                                              
                                              FROM `wallets_transactions` as wt 
                                              LEFT JOIN products as pt 
                                              ON (wt.product_id = pt.id)
                                              LEFT JOIN retailers as r
                                              ON (wt.user_id = r.user_id)
                                              LEFT JOIN users_services as us
                                              ON (wt.user_id = us.user_id AND pt.service_id = us.service_id) 
                                              LEFT JOIN users as uss
                                              ON (wt.user_id = uss.id)
                                              JOIN shop_transactions as st 
                                              ON (wt.shop_transaction_id =  st.target_id)                                                                          
                                              WHERE st.type IN (39,40) AND st.date >=  "' . $frmdate . '" AND st.date <= "' . $todate . '"  
                                             '. $production . ' ' . $vendor_id . ' ' . $statusval . '' . $retuser_mob . ' '. $shop_id .'' . $user_id .' '. $retailer_Id .'
                                              order by wt.txn_id desc');
        
        $csv->addRow($txndetail);
        foreach($allProdtrans as $trans):
            $txndetails[0] = $trans['pt']['name'];
            $txndetails[1] = $trans['r']['parent_id'];
            $txndetails[2] = $trans['r']['id'];
//          $txndetails[2] = $trans['wt']['user_id'];
            $txndetails[3] = $trans['uss']['balance'];
            $txndetails[4] = $trans['us']['param1'];            
            $txndetails[5] = $trans['wt']['txn_id'];            
            $txndetails[6] = $trans['st']['id'];            
            $txndetails[7] = $trans['st']['note'];
            $txndetails[8] = $trans['st']['amount'];
            $txndetails[9] = $trans['wt']['amountss'];
//            $txndetails[9] = ((isset($trans['wt']['service_charge'])) && (!empty($trans['wt']['service_charge'])))?$trans['wt']['service_charge']:$trans['wt']['commission'];                
            $txndetails[10] = 'partial/refunded';
            $txndetails[11] = $trans['st']['timestamp'];
            $csv->addRow($txndetails);
            $i ++;
        endforeach; }
        else {
        $allProdtrans = $this->Slaves->query('SELECT wt.txn_id,wt.status,r.id,r.parent_id,wt.user_id,wt.shop_transaction_id,wt.amount,wt.amount_settled,wt.service_charge,wt.commission,wt.description,wt.date,wt.created,pt.service_id,pt.name,us.param1,uss.balance
                                              FROM `wallets_transactions` as wt 
                                              LEFT JOIN products as pt 
                                              ON (wt.product_id = pt.id)
                                              LEFT JOIN users_services as us
                                              ON (wt.user_id = us.user_id AND pt.service_id = us.service_id)
                                              LEFT JOIN users as uss
                                              ON (wt.user_id = uss.id) 
                                              LEFT JOIN retailers as r
                                              ON (wt.user_id = r.user_id)             
                                              WHERE wt.date >=  "' . $frmdate . '" AND wt.date <= "' . $todate . '"  '.$join.'
                                             '. $production . ' ' . $vendor_id . ' ' . $statusval . '' . $retuser_mob . ' '. $shop_id .'' . $user_id .' '. $retailer_Id .'
                                              order by wt.txn_id desc');
        
        $csv->addRow($txndetail);
        foreach($allProdtrans as $trans):
            $txndetails[0] = $trans['pt']['name'];
            $txndetails[1] = $trans['r']['parent_id'];
            $txndetails[2] = $trans['r']['id'];
//            $txndetails[2] = $trans['wt']['user_id'];
            $txndetails[3] = $trans['uss']['balance'];
            $txndetails[4] = $trans['us']['param1'];
            $txndetails[5] = $trans['wt']['txn_id'];            
            $txndetails[6] = $trans['wt']['shop_transaction_id'];            
            $txndetails[7] = $trans['wt']['description'];
            $txndetails[8] = $trans['wt']['amount'];
            $txndetails[9] = ((isset($trans['wt']['service_charge'])) && (!empty($trans['wt']['service_charge'])))?$trans['wt']['service_charge']:$trans['wt']['commission'];                
            $txndetails[10] = $statusarr[$trans['wt']['status']];
            $txndetails[11] = $trans['wt']['created'];
            $csv->addRow($txndetails);
            $i ++;
        endforeach;                                   
        }
              echo $csv->render('AllTxn_' . $frmdate . '_' . $todate . '.csv');
              
        }                
    }
        
}
?>
