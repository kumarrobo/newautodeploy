<?php
//error_reporting(0);
//ini_set("log_errors", 0);

class SimsController extends AppController
{
	var $name = 'Sims';
	var $components = array('RequestHandler','Recharge','Shop');
	var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator','Sims');
	var $uses = array('User','Slaves');
	var $simInfo=array();
	var $totalOperators=array();
	var $UniqueSupplierList=array();
	var $activeModems=array();
	var $listofallModems=array();
	var $serverDiff=array();
	var $requests=array();
	var $apisale=array();
	var $isDistributer;


	 
	 
	public function beforeFilter()
	{
		parent::beforeFilter ();
		$this->Auth->allow('*');
	}


	public function getOperatorsViewJSON()
	{
		$this->autoRender=false;

		$this->index(true);
	}

	/*
	 * Handles sims & vendor logic in same function
	 * Start
	 */

	public function index($ajax=false)
	{

		ini_set("memory_limit", "512M");
		set_time_limit(0);

		$this->layout = 'sims';
                
                $modem_id = !empty($this->params['url']['modem_id'])?strpos($this->params['url']['modem_id'],",")?explode(",",$this->params['url']['modem_id']):array($this->params['url']['modem_id']): ($ajax || $this->Session->read('Auth.User.group_id') == "9" ? 0 : -1);

		$date= !empty($this->params['url']['searchbydate'])?$this->params['url']['searchbydate']:'';


                
                		/*
		 * Get total avaliable modems from shops DB
		 */
		$TotalModems=  $this->Slaves->query("SELECT * FROM vendors WHERE show_flag = 1 order by company");



		$this->listofallModems=$TotalModems;

		 

		//$this->set('last',$this->__getLastModemWorkingtime($TotalModems));

		/*
		 * Get total Products
		 */
		$this->totalOperators = $this->Shop->getProducts();


		/*
		 * Get all Inactive modems from shops DB
		 */
		$InactiveModems =  $this->Shop->getInactiveVendors();
		 
		/*
		 * Extract only active modems
		 */
		$modemDropdownList=$this->__getActiveModems($TotalModems,$InactiveModems);

		$ActiveModems= !empty($modem_id)?$this->__extractSelectModem($modemDropdownList,$modem_id):$modemDropdownList;
		 
		/*
		 * Checking Access
		 * Start
		 */
		if($this->Session->read('Auth.User.group_id')=="9"):$ActiveModems=$this->__checkAccess($ActiveModems);$modemDropdownList=$this->__checkAccess($modemDropdownList);endif;
		if($this->Session->read('Auth.User.group_id')=="9"): $isDistributer="1"; else:  $isDistributer="0"; endif;
		setcookie('isDistributer',$isDistributer,strtotime("tomorrow"));
		$this->isDistributer=$isDistributer;
		setcookie('isRoot',($this->Session->read('Auth.User.group_id')=="25")?1:0);
		/*
		 * End
		 */
		 
		/*
		 * Set Todays date if not selected
		 */

		if(empty($date)): $date = date('Y-m-d'); endif;

		/*
		 * Start Processing given modem to fetch required report
		 */
		$this->requests=  $this->getRequestPerMinute();

		// Setting api vendor sale
		//   $this->apisale=$this->setApiVendorSales();

		foreach ($ActiveModems as $key=>$modem):

		$operators=$this->__getDistinctOperatorwiseReportByModemId($modem,$date);

		$ActiveModems[$key]['vendors']['portsInfo']=$operators['portsInfo'];

		$ActiveModems[$key]['operators']= $operators['tobeReturned'];

		endforeach;

                /* For getting the disabled Modem
                 * 
                 **/
                                                   
                $disabled_time = date("Y-m-d H:i:s",strtotime("-10 minutes"));
                $disbModem = array();
                    
                foreach($ActiveModems as $actModem){
                    $selModem = $actModem['vendors']['portsInfo']['lasttime'];
                    if(!empty($selModem) && strtotime($selModem) < strtotime($disabled_time)){
                        $disbModem[] = $actModem['vendors']['company'];
                    }
                }
                $this->set('disabledModem', $disbModem);
                    
		$this->activeModems=$ActiveModems;
                
                
		//                echo "<pre>";
		//                print_r($this->activeModems);
		//                echo "</pre>";
		//                die;



		/*
		 * Get SimOverview Panel based on simPanel array shifted below
		 */
		//$ActiveOperators=$this->networks();
                                                

		if($ajax):
		$ActiveOperators=$this->networks();

                $api = array();
                foreach($ActiveOperators['operators'] as $active) {
                        foreach($active['modems'] as $key=>$modem) {
                                if(!isset($modem['id'])) {
                                        if(!in_array($active['info']['id'], $product_id)) {
                                                $product_id[] = $active['info']['id'];
                                        }
                                        if(!in_array($key, $vendor_id)) {
                                                $vendor_id[]  = $key;
                                        }
                                }
                        }
                }
                
                $api_data = $this->Slaves->query("SELECT vendors_commissions.product_id, vendors.shortForm, vendors_commissions.cap_per_min FROM vendors_commissions LEFT JOIN vendors ON (vendors.id = vendors_commissions.vendor_id) WHERE vendors_commissions.product_id IN (".implode(',',$product_id).") AND vendors_commissions.vendor_id IN (".implode(',',$vendor_id).")");
                
                foreach($api_data as $a_d) {
                        $api[$a_d['vendors_commissions']['product_id']][$a_d['vendors']['shortForm']] = $a_d['vendors_commissions']['cap_per_min'];
                }
                
		$this->autoRender=false;
		echo json_encode(array('networks'=>$ActiveOperators,'requests'=>$this->requests,'api'=>$api));
		exit();
		endif;

		$this->set('modemDropdownList',$modemDropdownList);
		$this->set('modems',$ActiveModems);
		$this->set('operators',$this->totalOperators);

		//                echo "<pre>";
		//                print_r($this->requests);
		//                echo "-----------";
		//                print_r($ActiveOperators['operators']);
		//                echo "</pre>";
		//                die;

		/*
		 * Get Api Vendor Balances
		 * Start
		 */
                                                        
		if($isDistributer=="0"):
		$this->set('apiVendors',  $this->__getApiVendorBalances());
		endif;
		/*
		 * End
		 */


		/*
		 * Set Requests
		 * Start
		 */
		$this->set('requests',$this->requests);
		$this->set('isDistributer',$isDistributer);
		/*
		 * End
		 */

		/*
		 /*
		 * Get Modem Request log
		 */
		//$modemRequestslogs = $this->User->query("SELECT * FROM modem_request_log order by created desc limit 0 , 100");
		//$this->set('modemRequestlogs',$modemRequestslogs);

		/*
		 * Fetch data to create success / Failure tiles per modem
		 */
		//$OperatorWiseSuccessFailure=$this->__getOperatorWiseSuccessFailureReports();

		//$this->set('OperatorWiseSuccessFailure',$OperatorWiseSuccessFailure['prods']);

		/*
		 * Get last working timestamp of each modem
		 */
		// $this->set('last',$OperatorWiseSuccessFailure['last_array']);
                $simbalrange=$this->getSimBalRange($modem_id,$date);

                $this->set('simbalrange',$simbalrange);

                //to get sims comment count
                $commentcount=$this->getSimCommentsCount($modem_id,$date);

                $this->set('commentcountarr',$commentcount);	 
	}
	 
	 
	/*
	 * Extract only the modem from totalactivemodems  that matched the id passed in URL
	 */
	public function __extractSelectModem($modems,$ids)
	{
		$tobeReturned=array();
		 
		foreach($modems as $key=>$modem):

		if(in_array($modem['vendors']['id'],$ids)):
		$tobeReturned[]=$modem;
		endif;
		 
		endforeach;
		 
		return $tobeReturned;
		 
	}
	 
	 
	function modemBalance($date=null,$vendor=4){
		if(empty($date))$date = date('Y-m-d');
		$adm = "query=balance&date=$date";
		$info = $this->Shop->getVendorInfo($vendor);

		$Rec_Data = false;
		if($date == date('Y-m-d'))$Rec_Data = $this->Shop->getMemcache("balance_$vendor");
			
		if($Rec_Data === false && $info['active_flag'] == 1){
			$Rec_Data = $this->Shop->modemRequest($adm,$vendor,$info);
			$Rec_Data = $Rec_Data['data'];
		}

		if(!empty($Rec_Data)){
			$Rec_Data = json_decode($Rec_Data,true);
			$time = $this->Shop->getMemcache("balance_timestamp_$vendor"."_last");
			$ports = $this->Shop->getMemcache("balance_ports_$vendor");
			if($time !== false){
				$Rec_Data['lasttime'] = $time;
			}
			if($ports !== false){
				$Rec_Data['ports'] = $ports;
			}
			return $Rec_Data;
		}
	}

	function getModemsimsDetails($vendor) {
			
        $res = array();
        $arr = array();

        if(isset($_FILES['simentry'])) {
            if(trim($_FILES['simentry']['type']) == 'text/csv' || trim($_FILES['simentry']['type']) == 'application/vnd.ms-excel') {
            $file = fopen($_FILES['simentry']["tmp_name"], "r");
            while(!feof($file)) {
                $data = fgetcsv($file, 1024);
                // For Getting Product id
                $productId = $this->Slaves->query("SELECT  `id` FROM products WHERE name = '{$data[1]}'");
                $supplierId = $this->Slaves->query("SELECT id FROM `inv_suppliers` WHERE `name` = '{$data[10]}'");
        if(is_numeric($data[0])) {

        $data1['sim_id'] = $data[0];
        $data1['operator'] = $productId[0]['products']['id'];
        $data1['mobile'] = $data[2];
        $data1['circle'] = $data[3];
        $data1['type'] = $data[4];
        $data1['pin'] = $data[5];
        $data1['commission'] = $data[6];
        $data1['limit'] = $data[7];
        $data1['roam_limit'] = $data[8];
        $data1['child'] = $data[9];
        $data1['parent'] = $data[10];
        $data1['inv_supplierId'] = $supplierId[0]['inv_suppliers']['id'];

        $res[$data1['sim_id']] = $data1;


        }}
        fclose($file);
        }else {

        $res = "Invalid File Format"; }
        $this->set('res', $res); }

               
		$param = "query=sims&vendor_id=$vendor";
		$simData = $this->Shop->modemRequest($param, $vendor);
                if ($simData['status'] == 'failure') {
			echo 'Recharge modem not responding';
		} else {
		$simData = $simData['data'];
			$simData = json_decode($simData, true);
			$this->set('simData', $simData);
		}

                
        foreach ($simData['New Sims'] as $sim){

          if(isset($res[$sim['ssid']])) {

                $arr['id']       = $sim['id'];
                $arr['simid']    = $res[$sim['ssid']]['sim_id'];
                $arr['operator'] = $res[$sim['ssid']]['operator'];
                $arr['mobile']   = $res[$sim['ssid']]['mobile'];
                $arr['circle']   = $res[$sim['ssid']]['circle'];
                $arr['type']     = $res[$sim['ssid']]['type'];
                $arr['pin']      = $res[$sim['ssid']]['pin'];
                $arr['comm']     = $res[$sim['ssid']]['commission'];
                $arr['limit']    = $res[$sim['ssid']]['limit'];
                $arr['roaming']  = $res[$sim['ssid']]['roam_limit'];
                $arr['Vendorid'] = $vendor;
                $arr['vendor']   = $res[$sim['ssid']]['child'];
                $arr['inv_supplier_id'] = $res[$sim['ssid']]['inv_supplierId'];
                $arr['machineid']   = $sim['machine_id'];
                $arr['balance']     = $sim['balance'];
                $arr['blocktag_id'] = $sim['block'];
                $arr['showflag']    = $sim['id'];
                $arr['parbal']      = $sim['id'];
                $arr['merge']       = $sim['id'];
                $arr['insert']      = "insert";
                $arr['vendor_tag']  = $res[$sim['ssid']]['parent'];
                $this->updateSimData($arr);
            } else{
                echo "NOT FOUND";
            }
        }
		//        echo "<pre>";
		//        print_r($simData);
		//$query = "query=sims";
		//$url = "http://start.loc/start.php";
			
		//        $query .= "&vendor_id=$vendor";
		//        $Rec_Data = $this->General->curl_post($url,$query,'POST');
		//        if($Rec_Data['success']=='1'){
		//            $Rec_Data = json_decode($Rec_Data['output'],true);
		//        }
		$oprData = $this->Slaves->query("SELECT  `id` ,`name` FROM products WHERE service_id IN ('1','2')");
		$circleData = $this->Slaves->query("SELECT `id`,`area_code`,`area_name` from `mobile_numbering_area` where `area_code`!='ZZ'");
		//$this->set('simData', $Rec_Data);
		$this->set('oprData', $oprData);
		$this->set('circleData',$circleData);
		$supplierList=  $this->Slaves->query("Select suppliers.id,suppliers.name from inv_suppliers suppliers JOIN inv_supplier_vendor_mapping sv ON suppliers.id=sv.supplier_id and sv.vendor_id={$vendor}");
		$this->set('vendors', $supplierList);
		$this->set('VendorId',$vendor);
		$vendordata = $this->Slaves->query('Select * from vendors where update_flag="1" and show_flag = "1"');
		$this->set('vendorsdata',$vendordata);
                                                $blocktag=$this->Slaves->query("select id,name as block_tag from inv_block_tags");
                                                $this->set('blocktags',$blocktag);
	}

	function updateSimData($arr=null) {
		        
         if(isset($_POST["operator"])){
               $in = $_POST;
           } else {
               $in = $arr;
        }
        // Check is SOID exists
        $checkifexists = $this->Slaves->query("Select id from inv_supplier_operator where operator_id='{$in['operator']}'  AND supplier_id='{$in['inv_supplier_id']}' ");
        if (empty($checkifexists)) {
            if ($this->RequestHandler->isAjax()) {
                echo json_encode(array('data' => 'Error : Supplier-Operator Mapping Does not exists'));
                exit();
            } else {
                return;
            }
        }

        $oprData = $this->Slaves->query("SELECT  `id` ,`name` FROM products WHERE service_id IN ('1','2')");
        $oprArray = array();
        foreach ($oprData as $key => $val) {
            $oprArray[$val['products']['id']] = $val['products']['name'];
        }

        $vendorID   = isset($in['Vendorid']) ? $in['Vendorid'] : "";
        $balance    = isset($in['balance']) ? $in['balance'] : "";
        $circle     = isset($in['circle']) ? $in['circle'] : "";
        $comm       = isset($in['comm']) ? $in['comm'] : "";
        $limit      = isset($in['limit']) ? $in['limit'] : "";
        $mobile     = isset($in['mobile']) ? $in['mobile'] : "";
        $operator   = isset($in['operator']) ? $in['operator'] : "";
        $pin        = isset($in['pin']) ? $in['pin'] : "";
        $roaming    = isset($in['roaming']) ? $in['roaming'] : "";
        $showFlag   = isset($in['showflag']) ? $in['showflag'] : "";
        $type       = isset($in['type']) ? $in['type'] : "";
        $vendorTag = isset($_POST['vendortag']) ? $in['vendortag'] : "";
        $vendorName = isset($in['vendor']) ? $in['vendor'] : "";
        $parbal     = isset($in['parbal']) ? $in['parbal'] : "";
        $machineId  = isset($in['machineid']) ? $in['machineid'] : "";
        $simId      = isset($in['simid']) ? $in['simid'] : "";
        $oprId      = $oprArray[$operator];
        $id         = isset($in['id']) ? $in['id'] : "";
        $insert     = isset($in['insert']) ? $in['insert'] : "";
        if ($in['block'] == "true"): $block = 1;
        else: $block = 0;
        endif;
        if (trim($in['vendor_tag']) != ""): $vendor_tag = $in['vendor_tag'];
        else:$vendor_tag = "";
        endif;
        $vendor_id = isset($in['inv_supplier_id']) ? $in['inv_supplier_id'] : "";
        if ($in['checkmultiple'] == "true"): $multiple = 1;
        else : $multiple = 0;
        endif;
        $merge = isset($in['merge']) ? $in['merge'] : "";
        $blocktag_id = isset($in['blocktag_id']) ? $in['blocktag_id'] : "";
        $query = "query=updateSimdata&operator=$oprId&mobile=$mobile&circle=$circle&type=$type&pin=$pin&balance=$balance&comm=$comm&limit=$limit&roaming=$roaming&showflag=$showFlag&id=$id&oprId=$operator&vendorname=$vendorName&parbal=$parbal&machineid=$machineId&simid=$simId&insert=$insert&block=$block&multiple=$multiple&vendor_tag=$vendor_tag&supplier_id=$vendor_id&merge=$merge&blocktag_id=$blocktag_id";
        //$query = "query=updateSimdata&operator=$oprId&mobile=$mobile&circle=$circle&type=$type&pin=$pin&balance=$balance&comm=$comm&limit=$limit&roaming=$roaming&showflag=$showFlag&id=$id&oprId=$operator&vendorname=$vendorName&parbal=$parbal&machineid=$machineId&simid=$simId&insert=$insert&block=$block&multiple=$multiple&vendor_tag=$vendor_tag&supplier_id=$vendor_id&merge=$merge";
        $Rec_Data = $this->Shop->modemRequest($query, $vendorID);

        if (isset($Rec_Data['status'])) {
            if ($this->RequestHandler->isAjax()) {
                echo json_encode($Rec_Data);
                die;
            }   else {
                return;
            }
        }
        $this->autoRender = false;
    }

	function checkPassword() {

		if ($this->RequestHandler->isAjax()) {
			$userName = $_SESSION['Auth']['User']['mobile'];
			$password = $this->Auth->password($_POST['password']);
			$checkData = $this->User->query("SELECT  `id`  FROM users WHERE mobile = '$userName' AND Password = '$password'");
			if (count($checkData)) {
				echo json_encode(array("result" => "success"));
			} else {
				echo json_encode(array("result" => "failure"));
			}
			die;
		}
		$this->autoRender = false;
	}

	function shiftSims(){

		$this->autoRender = false;

		if ($this->RequestHandler->isAjax()) {

			$supplierId = $_REQUEST['supplier_id'];
			$shifted_modem_id = $_REQUEST['shifted_modem_id'];
			$vendor =   $_REQUEST['modemId'];
			$parbal  = $_REQUEST['parbal'];

			// Check is SOID exists
			$checkifexists=$this->Slaves->query("Select id from inv_supplier_operator where operator_id='{$_POST['oprId']}'  AND supplier_id='{$supplierId}' ");

			if(empty($checkifexists)):
			echo json_encode(array('data'=>'Error : Supplier-Operator Mapping Doesnot exists'));
			exit();
			endif;

			$checkVendorMapping = $this->Slaves->query("SELECT * FROM  `inv_supplier_vendor_mapping` where supplier_id = '{$supplierId}' AND vendor_id = '{$shifted_modem_id}' ");

			if(empty($checkVendorMapping)):
			echo json_encode(array('data'=>'Error : Vendor-Supplier Mapping Doesnot exists'));
			exit();
			///get data from source vendor
			else:
			$param = "query=shiftsims&source_vendor_id=$vendor&target_vendor_id=$shifted_modem_id&parbal=$parbal";

			$simData = $this->Shop->modemRequest($param, $vendor);

			if($simData['status'] =='success'){
				$data = json_decode($simData['data'],TRUE);
					
				$querydata = json_encode($data['data']);
					
				$insertparam = "query=shiftsims&target_vendor_id={$data['target_vendor_id']}&source_vendor_id={$data['source_vendor_id']}&insertdata=".urlencode($querydata)."&parbalance=".$data['parbal'];
				//insert data in targeted vendor_id
				$insertdeviceData = $this->Shop->modemRequest($insertparam,$data['target_vendor_id']);
					
				if($insertdeviceData['status'] == 'success'){

					//update balance of source vendor_id
					$updateData = json_decode($insertdeviceData['data'],TRUE);

					$updateparam = "query=shiftsims&target_vendor_id={$updateData['target_vendor_id']}&source_vendor_id={$updateData['source_vendor_id']}&parbalance=".$updateData['parbal']."&reqtype=shiftsim";

					$updatedeviceData = $this->Shop->modemRequest($updateparam,$updateData['source_vendor_id']);

					if($updatedeviceData['status'] == 'success'){
							
						echo json_encode($updatedeviceData);
						die;
					}
				}
					
			}


			endif;

		}
	}

	public function __getDistinctOperatorwiseReportByModemId($modem,$date)
	{

		if(strtotime($date)==strtotime(date('Y-m-d')) && ($this->params['url']['mem'] != 0) ):

		$id=$modem['vendors']['id'];

		$memcachedata=  $this->Shop->getMemcache("DistinctOperatorwiseReportByModemId_$id");

		$memcachedata=  json_decode($memcachedata,true);

		if(!empty($memcachedata)):

                return array('tobeReturned'=>$memcachedata['operators'],'portsInfo'=>array('lasttime'=>$memcachedata['last'],'ports'=>$memcachedata['ports'],'modem_ip'=>$this->Shop->getMemcache("vendorip_$id")));

		endif;

		endif;


		//$response = $this->modemBalance($date,$modem['vendors']['id']);

		$response=$this->__getSimdataFromDb($date,$modem['vendors']['id']);
		 
		if(empty($response)):  return; endif;
		 
		//$this->serverDiff=$this->__getServerDiffByModemId($modem,$date); No need since its already stored in DB
		 
		$this->simInfo=$response;
		 
		$tobeReturned=array();

		$operators=  $this->totalOperators;
		 
		/*
		 * Add server diff key to each sim response for further calculation
		 * No need since its already stored in DB
		 */
		// $this->__setServerDiffKey();
		 
		/*
		 * Calculate Total Unique  Operator figures  by iterating through $operators array & $response
		 */

		foreach($operators as $key=>$operator):

		 
		foreach ($this->simInfo as $value):

		$value=(object)$value;
                                                     
		if($value->opr_id==$operator['products']['id']):

		$tobeReturned[$operator['products']['id']]['products']['name']=$operator['products']['name'];
		$tobeReturned[$operator['products']['id']]['products']['id']=$operator['products']['id'];

		$this->__fillUniqueSupplierList($value);

		$tobeReturned[$operator['products']['id']]['products']['totalSims']+=1;
		 
                                                     $bal_check=($value->bal_range != 0)?(($value->balance+10) > $value->bal_range):($value->balance >= 10);
                                                     
		$tobeReturned[$operator['products']['id']]['products']['totalActiveSims']+=(($value->active_flag!=0) && ($value->block!=1) && ($value->stop_flag==0) && $bal_check)?1:0;
		 

		$tobeReturned[$operator['products']['id']]['products']['totalBalance']+=$value->balance;
		 

		$tobeReturned[$operator['products']['id']]['products']['totalBlockedSims']+=$value->block?1:0;
		 

		$tobeReturned[$operator['products']['id']]['products']['totalStoppedSims']+=($value->state==2)?1:0;
		 

		$tobeReturned[$operator['products']['id']]['products']['totalBlockedBalance']+=($value->block)?$value->balance:0;
		 

		$tobeReturned[$operator['products']['id']]['products']['totalSale']+=$value->sale;
		 
		 
		$tobeReturned[$operator['products']['id']]['products']['totalOpening']+=$value->opening;


		$tobeReturned[$operator['products']['id']]['products']['totalIncoming']+=$value->tfr;
		 
		 
		$tobeReturned[$operator['products']['id']]['products']['totalIncomingClo']+=$value->inc?$value->inc:0;
		 
		 
		$tobeReturned[$operator['products']['id']]['products']['totalClosing']+=$value->closing?$value->closing:0;

		 
		$tobeReturned[$operator['products']['id']]['products']['totalHomeSale']+=($value->opr_id=='4' && $value->sale>0)?($value->sale-$value->roaming_today):0;
		 
		 
		$tobeReturned[$operator['products']['id']]['products']['totalRoamingSale']+=$value->roaming_today?$value->roaming_today:0;


		//$tobeReturned[$operator['products']['id']]['products']['totalServerDiffnew']+=($value->serverDiffnew>0)?$value->serverDiffnew:0;
		$tobeReturned[$operator['products']['id']]['products']['totalServerDiffnew']+=($value->server_diff>0)?$value->server_diff:0;
                


		 
		endif;

		endforeach;

		endforeach;


		 

		/*
		 * Get Supplier wise report for each operator
		 */

		foreach($tobeReturned as $key=>$value):

		$tobeReturned[$key]['products']['suppliers']=  $this->__getDistinctOperatorwiseReportBySupplierName($value['products']['id']);

		endforeach;


		// Set lasttime & ports if search mode is on and date is current since it searches frm DB
		if(strtotime($date)==strtotime(date('Y-m-d'))):
		$response['lasttime']=  $this->Shop->getMemcache("balance_timestamp_{$modem['vendors']['id']}"."_last");
		$response['ports']=  $this->Shop->getMemcache("balance_ports_{$modem['vendors']['id']}");
		else:   $response['lasttime']=array(); $response['ports']=array(); endif;

		return array('tobeReturned'=>$tobeReturned,'portsInfo'=>array('lasttime'=>$response['lasttime'],'ports'=>$response['ports'],'modem_ip'=>$modem['vendors']['ip']));


	}
	 
	/*
	 * Returns Supplier wise report based in operatorid
	 */
	 
	public function __getDistinctOperatorwiseReportBySupplierName($operator_id)
	{

		$supplierList=  $this->UniqueSupplierList;

		foreach($supplierList as $key=>$supplier):

		$totalSims=0;
		$totalActiveSims=0;
		$totalBalance=0;
		$totalBlockedSims=0;
		$totalStoppedSims=0;
		$totalBlockedBalance=0;
		$totalSale=0;
		$totalOpening=0;
		$totalIncoming=0;
		$totalClosing=0;
		$totalIncomingClo=0;
		$totalHomeSale=0;
		$totalRoamingSale=0;
		$totalServerDiffnew=0;

		$sims=array();

		foreach ($this->simInfo as $value):


		$value=(object)$value;
                
		if((strtolower(trim($value->vendor_tag))==strtolower($supplier)) && ($value->opr_id==$operator_id) && !empty($value->vendor_tag)):

		$totalSims++;
		$totalActiveSims+=(($value->active_flag!=0) && ($value->block!=1) && ($value->state!=2) && ( ($value->balance+10) >$value->bal_range))?1:0;
		$totalBalance+=$value->balance;
		$totalBlockedSims+=$value->block?1:0;
		$totalStoppedSims+=($value->state==2)?1:0;
		$totalBlockedBalance+=($value->block)?$value->balance:0;


		$totalSale+=$value->sale;
		$totalOpening+=$value->opening;
		$totalIncoming+=$value->tfr;
		$totalIncomingClo+=$value->inc;
		$totalClosing+=$value->closing;
		 
		$totalHomeSale+=($value->opr_id=='4' && $value->sale>0)?($value->sale-$value->roaming_today):0;
		$totalRoamingSale+=$value->roaming_today;
		 
		//$totalServerDiffnew+=$value->serverDiffnew;
		$totalServerDiffnew+=$value->server_diff;
		 
		// $value->serverDiff=$this->__setServerDiff($value);
		$sims[]=$value;
		 

		endif;

		endforeach;

		if($totalSims):

		$tobeReturned[$supplier]=array(
                                                                                                                            'totalSims'=>$totalSims,
                                                                                                                            'totalActiveSims'=>$totalActiveSims,
                                                                                                                            'totalBalance'=>$totalBalance,
                                                                                                                            'totalBlockedSims'=>$totalBlockedSims,
                                                                                                                            'totalStoppedSims'=>$totalStoppedSims,
                                                                                                                            'totalBlockedBalance'=>$totalBlockedBalance,
                                                                                                                            'totalSale'=>$totalSale,
                                                                                                                            'totalOpening'=>$totalOpening,
                                                                                                                            'totalIncoming'=>$totalIncoming,
                                                                                                                            'totalClosing'=>$totalClosing,
                                                                                                                            'totalIncomingClo'=>$totalIncomingClo,
                                                                                                                             'totalHomeSale'=>$totalHomeSale,
                                                                                                                             'totalRoamingSale'=>$totalRoamingSale,
                                                                                                                             'totalServerDiffnew'=>$totalServerDiffnew
		);

		$tobeReturned[$supplier]['sims']=$sims;

		endif;


		 
		 
		endforeach;
		 

		 

		return $tobeReturned;
		 
		 
	}
	 
	 
	/*
	 * Extracts only the active modems from total modems & check if update flag is set
	 */
	public function __getActiveModems($TotalModems,$InactiveModems)
	{
		$tobeReturned=array();
		 
		foreach($TotalModems as $key=>$modem):
		 
		//                            if(!in_array($modem['vendors']['id'], $InactiveModems) && $modem['vendors']['update_flag']==1):
		//
		//                                        $tobeReturned[]=$modem;
		//
		//                            endif;

		if($modem['vendors']['update_flag']==1):

		if(in_array($modem['vendors']['id'], $InactiveModems)):

		$modem['vendors']['inactive']=1;
		$tobeReturned[]=$modem;

		else:

		$modem['vendors']['inactive']=0;
		$tobeReturned[]=$modem;

		endif;
		 
		endif;

		endforeach;
		 
		return $tobeReturned;
	}

	/*
	 * End
	 */
	 
	 
	public function __fillUniqueSupplierList($sim)
	{
		 
		if(!in_array(strtolower(trim($sim->vendor_tag)),$this->UniqueSupplierList) && !empty($sim->vendor_tag)):

		$this->UniqueSupplierList[]=strtolower(trim($sim->vendor_tag));
		 
		endif;
		 

		 
	}
	 
	 
	public function networks()
	{
            $networks=array();
		 
		foreach($this->activeModems as $key=>$modem):



		foreach($modem['operators'] as $key2=>$operator):


		if(array_key_exists($operator['products']['name'],$networks['operators'])):
		$totalSims=$networks['operators'][$operator['products']['name']]['info']['totalSims']+$operator['products']['totalSims'];
		$totalActiveSims=$networks['operators'][$operator['products']['name']]['info']['totalActiveSims']+$operator['products']['totalActiveSims'];
		$totalBalance=$networks['operators'][$operator['products']['name']]['info']['totalBalance']+$operator['products']['totalBalance'];
		$totalBlockedSims=$networks['operators'][$operator['products']['name']]['info']['totalBlockedSims']+$operator['products']['totalBlockedSims'];
		$totalStoppedSims=$networks['operators'][$operator['products']['name']]['info']['totalStoppedSims']+$operator['products']['totalStoppedSims'];
		$totalBlockedBalance=$networks['operators'][$operator['products']['name']]['info']['totalBlockedBalance']+$operator['products']['totalBlockedBalance'];
		$totalSale=$networks['operators'][$operator['products']['name']]['info']['totalSale']+$operator['products']['totalSale'];
		$totalOpening=$networks['operators'][$operator['products']['name']]['info']['totalOpening']+$operator['products']['totalOpening'];
		$totalIncoming=$networks['operators'][$operator['products']['name']]['info']['totalIncoming']+$operator['products']['totalIncoming'];
		$totalIncomingClo=$networks['operators'][$operator['products']['name']]['info']['totalIncomingClo']+$operator['products']['totalIncomingClo'];
		$totalClosing=$networks['operators'][$operator['products']['name']]['info']['totalClosing']+$operator['products']['totalClosing'];

		else:
		$totalSims=$operator['products']['totalSims'];
		$totalActiveSims=$operator['products']['totalActiveSims'];
		$totalBalance=$operator['products']['totalBalance'];
		$totalBlockedSims=$operator['products']['totalBlockedSims'];
		$totalStoppedSims=$operator['products']['totalStoppedSims'];
		$totalBlockedBalance=$operator['products']['totalBlockedBalance'];
		$totalSale=$operator['products']['totalSale'];
		$totalOpening=$operator['products']['totalOpening'];
		$totalIncoming=$operator['products']['totalIncoming'];
		$totalIncomingClo=$operator['products']['totalIncomingClo'];
		$ttotalClosing=$operator['products']['totalClosing'];
		endif;



		$networks['operators'][$operator['products']['name']]['info']=array(
                                                                                                                                                                             'id'=>$operator['products']['id'], 
                                                                                                                                                                             'totalSims'=>$totalSims,
                                                                                                                                                                             'totalActiveSims'=>$totalActiveSims,
                                                                                                                                                                             'totalBalance'=>$totalBalance,
                                                                                                                                                                             'totalBlockedSims'=>$totalBlockedSims,
                                                                                                                                                                             'totalStoppedSims'=>$totalStoppedSims,
                                                                                                                                                                             'totalBlockedBalance'=>$totalBlockedBalance,
                                                                                                                                                                             'totalSale'=>$totalSale,
                                                                                                                                                                             'totalOpening'=>$totalOpening,
                                                                                                                                                                             'totalIncoming'=>$totalIncoming,
                                                                                                                                                                             'totalIncomingClo'=>$totalIncomingClo,
                                                                                                                                                                             'totalClosing'=>$totalClosing,
                                                                                                                                                                             'totalApisale'=>0.00
		);


		// Push API modems into operator array
		//Start
		if(!$this->isDistributer):
		if(isset($this->requests['apirequests'][$operator['products']['id']])):
		 
		$totalapisale=0;
		foreach($this->requests['apirequests'][$operator['products']['id']] as $apikey=>$api):

		$networks['operators'][$operator['products']['name']]['modems'][$apikey]=array('company'=>$api['company'],
                                                                                                                                                                             'totalrequests'=>$api['totalrequests'],
                                                                                                                                                                             'successrequests'=>$api['successrequests'],
                                                                                                                                                                             'totalSale'=>  $api['sale']
		);

		$totalapisale+=$api['sale'];
		endforeach;
		 
		$networks['operators'][$operator['products']['name']]['info']['totalApisale']=$totalapisale;
		 
		endif;
		endif;
		//End

		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]=$modem['vendors'];



                foreach($operator['products']['suppliers'] as $sim):
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['sims'][]=$sim['sims'];
                                                    endforeach;



		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalSims']=$operator['products']['totalSims'];
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalActiveSims']=$operator['products']['totalActiveSims'];
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalBalance']=$operator['products']['totalBalance'];
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalBlockedSims']=$operator['products']['totalBlockedSims'];
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalStoppedSims']=$operator['products']['totalStoppedSims'];
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalBlockedBalance']=$operator['products']['totalBlockedBalance'];
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalSale']=$operator['products']['totalSale'];
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalOpening']=$operator['products']['totalOpening'];
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalIncoming']=$operator['products']['totalIncoming'];
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalIncomingClo']=$operator['products']['totalIncomingClo'];
		$networks['operators'][$operator['products']['name']]['modems'][$modem['vendors']['id']]['totalClosing']=$operator['products']['totalClosing'];



		endforeach;




		$totalSims=0;
		$totalActiveSims=0;
		$totalBalance=0;
		$totalBlockedSims=0;
		$totalStoppedSims=0;
		$totalBlockedBalance=0;
		$totalSale=0;
		$totalOpening=0;
		$totalIncoming=0;
		$totalIncomingClo=0;
		$ttotalClosing=0;


		endforeach;


		//                        echo '<pre>';
		//                        print_r($networks);
		//                        echo '</pre>';
		//                        die;

		return $networks;

	}
	 
	 
	public function getOperatorWiseSuccessFailureReports($viewname=null)
	{
		$this->autoRender=false;
		// if($viewname=="operatorview")
		//  return '{"successfailurereports":{"1":{"max":"13","total":410,"count":"119","vendor":"delhi","failure":14,"active":"0","modem_flag":"1","name":"Aircel"},"2":{"max":"54","total":947,"count":"346","vendor":"UPE_SP","failure":17,"active":"0","modem_flag":"1","name":"Airtel"},"5":{"vendor":0,"count":0,"total":1,"name":"Loop","failure":0},"9":{"max":"12","total":167,"count":"100","vendor":"kota","failure":14,"active":"1","modem_flag":"1","name":"Tata Docomo"},"11":{"max":"25","total":101,"count":"46","vendor":"up","failure":1,"active":"1","modem_flag":"1","name":"Uninor"},"12":{"max":"8","total":"15","count":"15","vendor":"cp","failure":"0","active":"1","modem_flag":"0","name":"Videocon"},"15":{"max":"48","total":1238,"count":"413","vendor":"joinrec","failure":17,"active":"1","modem_flag":"0","name":"Vodafone"},"16":{"max":"12","total":81,"count":"27","vendor":"kota","failure":4,"active":"0","modem_flag":"1","name":"Airtel DTH"},"21":{"max":"5","total":96,"count":"94","vendor":"payt","failure":1,"active":"1","modem_flag":"0","name":"Videocon DTH"}},"modemwisesuccessfailure":[{"shortForm":"bagalkot","timestamp":"2015-07-08 13:07:02"},{"shortForm":"Banglore","timestamp":"2015-07-08 14:03:02"},{"shortForm":"bhuv","timestamp":"2015-07-08 14:21:03"},{"shortForm":"calicutt","timestamp":"2015-07-08 14:21:38"},{"shortForm":"cp","timestamp":"2015-07-08 14:24:03"},{"shortForm":"darbanga","timestamp":"2015-07-08 14:05:06"},{"shortForm":"delhi","timestamp":"2015-07-08 14:24:40"},{"shortForm":"gaya","timestamp":"2015-07-08 14:24:13"},{"shortForm":"gitech","timestamp":"2015-07-08 14:17:08"},{"shortForm":"gorakh","timestamp":"2015-07-08 12:37:02"},{"shortForm":"modem","timestamp":"2015-07-08 14:24:02"},{"shortForm":"modem2","timestamp":"2015-07-08 14:24:24"},{"shortForm":"modem3","timestamp":"2015-07-08 14:24:21"},{"shortForm":"modem4","timestamp":"2015-07-08 14:24:03"},{"shortForm":"vasai","timestamp":"2015-07-08 14:24:14"},{"shortForm":"jshed","timestamp":"2015-07-08 13:53:01"},{"shortForm":"jbp","timestamp":"2015-07-08 14:24:02"},{"shortForm":"joinrec","timestamp":"2015-07-08 14:24:59"},{"shortForm":"kntka","timestamp":"2015-07-08 14:15:02"},{"shortForm":"kota","timestamp":"2015-07-08 14:24:15"},{"shortForm":"magicm2","timestamp":"2015-07-08 14:24:30"},{"shortForm":"mirzapur","timestamp":"2015-07-08 14:23:01"},{"shortForm":"bihar","timestamp":"2015-07-08 14:24:10"},{"shortForm":"p1","timestamp":"2015-07-08 14:19:04"},{"shortForm":"payt","timestamp":"2015-07-08 14:24:42"},{"shortForm":"raichur","timestamp":"2015-07-08 14:13:44"},{"shortForm":"rio","timestamp":"2015-07-08 14:21:34"},{"shortForm":"upe","timestamp":"2015-07-08 14:15:03"},{"shortForm":"up","timestamp":"2015-07-08 14:23:03"},{"shortForm":"UPE_SP","timestamp":"2015-07-08 14:24:20"}]}';
		//  return json_decode($testJson,true);
		 
		$data=array();
		$prods = array();
		$tobeReturned=array();
		 
		//. " AND vendors_activations.date = '2014-01-01' "
		//. " AND vendors_activations.timestamp >= '2014-01-01' "

		$query="SELECT count(vendors_activations.id) as ids, vendors_activations.vendor_id,vendors_activations.product_id,products.name,vendors.shortForm,sum(if(vendors_activations.status !=2 AND vendors_activations.status !=3,1,0)) as success,sum(if(vendors_activations.status =2 OR vendors_activations.status =3,1,0)) as failure,vendors_commissions.active,vendors.update_flag "
		. " FROM `vendors_activations`,products,vendors,vendors_commissions  "
		. " WHERE vendors_commissions.vendor_id= vendors.id "
		. " AND vendors_commissions.product_id = products.id "
		. " AND products.id = vendors_activations.product_id "
		. " AND vendors.id = vendors_activations.vendor_id "
		. " AND vendors_activations.date = '".date('Y-m-d')."' "
		. " AND vendors_activations.timestamp >= '".date('Y-m-d H:i:s',strtotime('-30 minutes'))."' "
		. " Group by vendors_activations.product_id,vendors_activations.vendor_id "
		. " Order by vendors_activations.product_id";
		 
		 
		$data1 = $this->Shop->getVendors();
		$last_array = array();
		
		foreach($data1 as $dt)
		{
		    $time = $this->Shop->getMemcache("vendor".$dt['vendors']['id']."_last");
		    
		    if(!empty($time))
		    {
		        $last_array[] = array('shortForm'=>$dt['vendors']['shortForm'],'timestamp'=>$time);
		        //$last_array[] = array('balance'=>$this->getApiVendorBalance($dt['vendors']['id'],$dt['vendors']['shortForm'],$dt['vendors']['update_flag']),'is_api_vendor'=>$dt['vendors']['update_flag'],'shortForm'=>$dt['vendors']['shortForm'],'timestamp'=>$time);
		    }
		}
		
		//$tobeReturned['last_array']=$last_array;
		if($viewname=="modemview"):
		echo json_encode(array('modemwisesuccessfailure'=>$last_array));exit();
		endif;
		
		$data = $this->Slaves->query($query);
		
		foreach($data as $dt){
			if(empty($prods[$dt['vendors_activations']['product_id']]))
			{
				$prods[$dt['vendors_activations']['product_id']]['max'] = $dt['vendors_activations']['vendor_id'];
				$prods[$dt['vendors_activations']['product_id']]['total'] = $dt['0']['ids'];
				$prods[$dt['vendors_activations']['product_id']]['count'] = $dt['0']['success'];
				$prods[$dt['vendors_activations']['product_id']]['vendor'] = $dt['vendors']['shortForm'];
				$prods[$dt['vendors_activations']['product_id']]['failure'] = $dt['0']['failure'];
				$prods[$dt['vendors_activations']['product_id']]['active'] = $dt['vendors_commissions']['active'];
				$prods[$dt['vendors_activations']['product_id']]['modem_flag'] = $dt['vendors']['update_flag'];
			}
			else
			{
				if($prods[$dt['vendors_activations']['product_id']]['count'] < $dt['0']['ids'])
				{
					$prods[$dt['vendors_activations']['product_id']]['max'] = $dt['vendors_activations']['vendor_id'];
					$prods[$dt['vendors_activations']['product_id']]['count'] = $dt['0']['success'];
					$prods[$dt['vendors_activations']['product_id']]['vendor'] = $dt['vendors']['shortForm'];
					$prods[$dt['vendors_activations']['product_id']]['modem_flag'] = $dt['vendors']['update_flag'];
					$prods[$dt['vendors_activations']['product_id']]['active'] = $dt['vendors_commissions']['active'];
					//$prods[$dt['vendors_activations']['product_id']]['failure'] = $dt['0']['failure'];
				}

				$prods[$dt['vendors_activations']['product_id']]['total'] = $prods[$dt['vendors_activations']['product_id']]['total'] + $dt['0']['ids'];
				$prods[$dt['vendors_activations']['product_id']]['failure'] = $prods[$dt['vendors_activations']['product_id']]['failure'] + $dt['0']['failure'];
			}

			$prods[$dt['vendors_activations']['product_id']]['name'] = $dt['products']['name'];

			/* Commented Section gives last success timestamp of each modem
			 * Since its not required in new simpanel its commented cross check with RC controller for actual code
			 * also look at last array key*/
			//$data1 = $this->listofallModems;

						//$data1 = $this->User->query("SELECT max(timestamp) as timestamp,vendors.shortForm FROM vendors left join `vendors_activations` ON (vendors_activations.vendor_id= vendors.id AND vendors_activations.date = '".date('Y-m-d')."') WHERE vendors.active_flag = 1 AND vendors_activations.status = 1 group by vendors.id");


			 
			 
		}
		

		$array = array();

		$data = $this->Shop->getProducts();

		foreach($data as $prod)
		{
			if($prod['products']['monitor'] == 1)
			{
				if(!isset($prods[$prod['products']['id']]))
				{
					$array[$prod['products']['id']]['vendor'] = 0;
					$array[$prod['products']['id']]['count'] = 0;
					$array[$prod['products']['id']]['total'] = 1;
					$array[$prod['products']['id']]['name'] = $prod['products']['name'];
					$array[$prod['products']['id']]['failure'] = 0;
				}
				else if($prods[$prod['products']['id']]['modem_flag'] != 1 || $prods[$prod['products']['id']]['count']*100/$prods[$prod['products']['id']]['total'] < 60 || $prods[$prod['products']['id']]['failure']*100/$prods[$prod['products']['id']]['total'] > 20){
					$array[$prod['products']['id']] = $prods[$prod['products']['id']];
				}
			}
		}



		// $tobeReturned['prods']=$prods;
		if($viewname=="operatorview"):
		echo json_encode(array('successfailurereports'=>$array,''));
		exit();
		endif;

		 
		 
		 
	}
	 
	 
	function __getApiVendorBalances()
	{
		$tobeReturned=array();
		 
		$apiVendorsList=  $this->getApiVendorsDetails();



		foreach($apiVendorsList as $key=>$value):

		$tobeReturned[]=array('id'=>$value['vendors']['id'],'name'=>$value['vendors']['company'],'shortform'=>$value['vendors']['shortForm'],'balance'=>  $this->getApiVendorBalance($value['vendors']['id'],$value['vendors']['shortForm'],0));
		 
		endforeach;
		 
		 
		return $tobeReturned;
		 
	}
	 
	/*
	 * Get Api vendor Details
	 * Start
	 */
	function getApiVendorsDetails()
	{
		$vendors = $this->Slaves->query("SELECT * FROM `vendors` where update_flag = 0 AND active_flag = 1 and machine_id=0");
		 
		return $vendors;
	}
	/*
	 *
	 */
	 
	/*
	 * Get Last Modem working time
	 * Start
	 */
	function __getLastModemWorkingtime($vendors)
	{
		$last_array=array();

		foreach($vendors as $value):

		$time = $this->Shop->getMemcache("vendor".$value['vendors']['id']."_last");

		if(!empty($time)):
		//$last_array[] = array('shortForm'=>$value['vendors']['shortForm'],'timestamp'=>$time);
		$last_array[] = array('is_api_vendor'=>$value['vendors']['update_flag'],'shortForm'=>$value['vendors']['shortForm'],'timestamp'=>$time,'balance'=>$this->getApiVendorBalance($value['vendors']['id'],$value['vendors']['shortForm'],$value['vendors']['update_flag']));
		endif;
		 
		 

		endforeach;

		return $last_array;

	}


	/*
	 * End
	 */



	/*
	 * Get API Vendor Balances
	 */
	function getApiVendorBalance($vendorId,$shortForm,$update_flag)
	{
		if($update_flag==0)
		{
			
		    $output = $this->Recharge->apiBalance($vendorId,$shortForm);
		    
			if(isset($output['balance'])):

			return $output['balance'];

			
			endif;
			 
		}

		return 0;
	}

	/*
	 * Author : Vibhas Bhingarde
	 * Handles ajax call to update sim incoming value from simPanel
	 */

	function updateIncomingManually()
	{
		$this->autoRender=false;

		if ($this->RequestHandler->isAjax()):
		 
		//                 if(strtotime($this->params['form']['date'])<strtotime('today -2 days') && $this->Session->read('Auth.User.group_id')!=2):
		//                            echo json_encode(array('status'=>'success','data'=>'Error : Not permitted'));
		//                            return;
		//                            exit();
		//                 endif;
		 
		if(strtotime($this->params['form']['date'])<strtotime('today')):
		$this->CheckIncomingAccess($this->params['form']['date']);
		endif;
		 
		$this->checkValidIncoming($this->params['form']);
		 
		$device_id=$this->params['form']['device_id'];
		$bal=$this->params['form']['bal'];
		$oldbal=$this->params['form']['oldbal'];
		$userid= $this->Auth->user('id');
		$date=  date('Y-m-d',strtotime($this->params['form']['date']));
		$mobile=$this->params['form']['mobile'];
		$operator_id=$this->params['form']['operator_id'];

		$vendor_id=$this->params['form']['vendor_id'];
		 
		$query="query=UpdateIncoming&device_id=$device_id&bal=$bal&user_id=$userid&date=$date";
		 
		if($this->isUpfromlast5min($this->params['form']['vendor_id'])):


		$response=$this->Shop->modemRequest($query,$vendor_id);
		 

		// SendMail if Incoming exceeded
		if($this->params['form']['sendmail']=="1"):
		$body = "Vendor ID:  {$vendor_id} <br/>SIM No:  {$mobile} <br/>Operator: {$operator_id} <br/>Diff amount: {$this->params['form']['diffamount']} <br/>Incoming changed to: {$bal} <br/>Incoming changed by: {$this->Session->read('Auth.User.name')} <br/>";
		$this->General->sendMails('(SOS)Diff of a sim exceeded by less than -100',$body,array('accounts@pay1.in','backend@pay1.in','orders@pay1.in','vinit@pay1.in'),'mail');
		endif;

		echo json_encode($response);
		 
		/*
		 * Update Inventory Simdata table if edited date is less than current date
		 * Start
		 */

		//if($response['data']=="Incoming Update Success"):
               if(preg_match('/Incoming Update Success/',$response['data'])):

		if(strtotime($date)<strtotime('today')):

		// Update pendings table only if one is editing incoming 1 days later  because editing incoming 1 day later is automatically synced by pending script
		// if((in_array($operator_id,array('9','15','17','16','11','30','8','3','20','18','2','1','4','19'))) ):
		$params=array('operator_id'=>$operator_id,'mobile'=>$mobile,'vendor_id'=>$vendor_id,'newincoming'=>$bal,'date'=>$date,'oldincoming'=>$oldbal);
		$this->adjustPendings($params);
		//   endif;


		//                                   $sql="Update devices_data  set tfr='{$bal}' Where mobile='{$mobile}' AND opr_id='{$operator_id}' AND vendor_id='{$vendor_id}' AND sync_date='{$date}'";
		//                                   $this->User->query($sql);
		 
		// Send Mail every time if incoming edited is =  2 day
		if(strtotime($date)<=strtotime('today -2 days')):
		$body = "Vendor ID:  {$vendor_id} <br/>SIM No:  {$mobile} <br/>Operator: {$operator_id} <br/>Diff amount: {$this->params['form']['diffamount']} <br/>Incoming changed to: {$bal} <br/>Incoming changed by: {$this->Session->read('Auth.User.name')} <br/>";
		$this->General->sendMails('Incoming Updated 2 days later ',$body,array('accounts@pay1.in','backend@pay1.in','ashish@pay1.in','orders@pay1.in','vinit@pay1.in'),'mail');
		endif;

		endif;

		endif;

		else:

		// Update pendings table only if one is editing incoming 1 days later  because editing incoming 1 day later is automatically synced by pending script
		if( (strtotime($date)<=strtotime('today -1 days')) ):
		$params=array('operator_id'=>$operator_id,'mobile'=>$mobile,'vendor_id'=>$vendor_id,'newincoming'=>$bal,'date'=>$date,'oldincoming'=>$oldbal);
		$this->adjustPendings($params);
		endif;


		//                            $sql="Update devices_data  set tfr='{$bal}' Where mobile='{$mobile}' AND opr_id='{$operator_id}' AND vendor_id='{$vendor_id}' AND sync_date='{$date}'";
		//                            $this->User->query($sql);

		$query.="&mobile=$mobile";
		$this->storeIncomingRequestinRedis($this->params['form'],$query,$this->params['form']['vendor_id']);
		echo json_encode(array('status'=>'success','data'=>'Incoming Update Success'));

		// Send Mail every time if incoming edited is =  2 day
		if(strtotime($date)<=strtotime('today -2 days')):
		$body = "Vendor ID:  {$vendor_id} <br/>SIM No:  {$mobile} <br/>Operator: {$operator_id} <br/>Diff amount: {$this->params['form']['diffamount']} <br/>Incoming changed to: {$bal} <br/>Incoming changed by: {$this->Session->read('Auth.User.name')} <br/>";
		$this->General->sendMails('Incoming Updated 2 day later ',$body,array('accounts@pay1.in','backend@pay1.in','ashish@pay1.in','orders@pay1.in','vinit@pay1.in'),'mail');
		endif;

		endif;

		/*
		 * End
		 */
		endif;
		 
		exit();

	}

	public function checkValidIncoming($params)
	{
		$flag=false;
		 
		// If root no validation at all
		if($this->Session->read('Auth.User.group_id')=="25"):
		$flag=true;
		else:

		if($params['expectedIncoming']>=0 && $params['bal']>=0 && $params['bal']%1==0):
		$flag=true;
		else:
		$flag=false;
		endif;

		endif;

		if(!$flag):
		echo json_encode(array('status' => 'Error', 'data' => 'Error : Enter valid incoming'));
		exit();
		endif;
	}

	function  __getServerDiffByModemId($modem,$date)
	{

		App::import('Controller','Panels');

		$Obj=new PanelsController();

		$Obj->constructClasses();
		 
		return $Obj->get_server_diff_by_vendor($modem['vendors']['id'],$date);

	}

	/*
	 * Set ServerDiff key by comparing  operator,mobile,vendor of sim Info with serverDiff array fetched from  __getServerDiffByModemId
	 */
	function __setServerDiff($sim)
	{
		$serverdiff=0;


		foreach($this->serverDiff as $k=>$v):


		if(($sim->opr_id==$v->operator_id) && ($sim->mobile==$v->sim_num) && (strtolower(trim($sim->vendor))==strtolower(trim($v->vendor)))):

		$serverdiff=$v->server_diff;

		break;

		endif;

		endforeach;


		return $serverdiff;

	}

	function __setServerDiffKey()
	{
		$serverdiff=0;

		foreach($this->simInfo as $key=>$value):

		$this->simInfo[$key]['serverDiffnew']=$serverdiff;

		$value=(object)$value;

		foreach($this->serverDiff as $k=>$v):

		if(($value->opr_id==$v['operator_id']) && ($value->mobile==$v['sim_num']) && (strtolower(trim($value->vendor))==strtolower(trim($v['vendor'])))):

		$serverdiff=$v['server_diff'];

		$this->simInfo[$key]['serverDiffnew']=$serverdiff;

		$serverdiff=0;

		break;

		endif;

		endforeach;

		endforeach;
	}

	function getRequestPerMinute()
	{

		$date=date('Y-m-d');
		$start=date('Y-m-d H:i:s',strtotime('-10 minutes'));
		$end=date('Y-m-d H:i:s',strtotime('-2 minutes'));

		$sql="SELECT count(vendors_activations.id) as ids, vendors_activations.vendor_id,vendors_activations.product_id,products.name,vendors.shortForm,"
		. "sum(if(vendors_activations.status !=2 AND vendors_activations.status !=3 AND (vendors_activations.timestamp >= '{$start}' AND vendors_activations.timestamp <= '{$end}')  ,1,0)) as success,"
		. "sum(if(vendors_activations.status =2 OR vendors_activations.status =3 AND (vendors_activations.timestamp >= '{$start}' AND vendors_activations.timestamp <= '{$end}') ,1,0)) as failure,"
		. "sum(if(vendors_activations.status !=2 AND vendors_activations.status !=3,amount,0)) as sale,vendors_commissions.active,vendors.update_flag"
		. "  FROM `vendors_activations` use index (idx_date) left join products ON (products.id = vendors_activations.product_id) left join vendors ON (vendors.id = vendors_activations.vendor_id) left join vendors_commissions ON (vendors_commissions.vendor_id= vendors.id AND vendors_commissions.product_id = products.id)"
		. " WHERE vendors_activations.date = '".date('Y-m-d')."' "
		. " group by vendors_activations.product_id,vendors_activations.vendor_id "
		. " order by vendors_activations.product_id";

                $currmin = date('YmdHi');
                $memcachedata = $this->Shop->getMemcache("simreqpermin_$currmin");
                $memcachedata = json_decode($memcachedata, true);
                if (!empty($memcachedata)):
                    $result = $memcachedata;
                else :
                    $result=$this->Slaves->query($sql);
                    $this->Shop->setMemcache("simreqpermin_$currmin", json_encode($result), 60);
                endif;
                
		$tobeReturned=array();

		// Creating array to combine request of many child product to one parent like eg : tata indicom/sv/.. To  tata docomo
		// Changed productid  of same product_type to one single product id like 27,10 to 9
		$result=  $this->combineRequests($result);

		// Creating array of format array[vendor_id][product_id^]=values

		foreach($result as $key=>$value):

		// Set Modem View

		if(!isset($tobeReturned['modemview'][$value['vendors_activations']['vendor_id']][$value['vendors_activations']['product_id']])):

		$tobeReturned['modemview'][$value['vendors_activations']['vendor_id']][$value['vendors_activations']['product_id']]=array('totalrequests'=>ceil(($value[0]['success'] + $value[0]['failure'])/8),
                                                                                                                                                                                        'successrequests'=>ceil($value[0]['success']/8));
		else:

		$totalrequests=0;
		$totalrequests=ceil(($value[0]['success'] + $value[0]['failure'])/8) + ($tobeReturned['modemview'][$value['vendors_activations']['vendor_id']][$value['vendors_activations']['product_id']]['totalrequests']);

		$totalsuccess=0;
		$totalsuccess=ceil($value[0]['success']/8)+($tobeReturned['modemview'][$value['vendors_activations']['vendor_id']][$value['vendors_activations']['product_id']]['successrequests']);


		$tobeReturned['modemview'][$value['vendors_activations']['vendor_id']][$value['vendors_activations']['product_id']]=array('totalrequests'=>$totalrequests,
                                                                                                                                                                                                'successrequests'=>$totalsuccess);

		endif;

		// Set Operator View

		if(!isset($tobeReturned['operatorview'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']])):

		$tobeReturned['operatorview'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']]=array('totalrequests'=>ceil(($value[0]['success'] + $value[0]['failure'])/8),
                                                                                                                                                                                        'successrequests'=>ceil($value[0]['success']/8));
		else:

		$totalrequests=0;
		$totalrequests=ceil(($value[0]['success'] + $value[0]['failure'])/8) + ($tobeReturned['operatorview'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']]['totalrequests']);

		$totalsuccess=0;
		$totalsuccess=ceil($value[0]['success']/8)+($tobeReturned['operatorview'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']]['successrequests']);


		$tobeReturned['operatorview'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']]=array('totalrequests'=>$totalrequests,
                                                                                                                                                                                                'successrequests'=>$totalsuccess);

		endif;


		// Set Api requests view

		if($value['vendors']['update_flag']==0):

		if(!isset($tobeReturned['apirequests'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']])):
		$tobeReturned['apirequests'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']]=array('totalrequests'=>ceil(($value[0]['success'] + $value[0]['failure'])/8),
                                                                                                                                                                                                        'successrequests'=>ceil($value[0]['success']/8),
                                                                                                                                                                                                        'company'=>$value['vendors']['shortForm'],
                                                                                                                                                                                                        'modem_id'=>$value['vendors_activations']['vendor_id'],
                                                                                                                                                                                                         'sale'=>$value[0]['sale']);   
		else:

		$totalsale=0;
		$totalsale=$value[0]['sale']+($tobeReturned['apirequests'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']]['sale']);

		$totalrequests=0;
		$totalrequests=ceil(($value[0]['success'] + $value[0]['failure'])/8) + ($tobeReturned['apirequests'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']]['totalrequests']);

		$totalsuccess=0;
		$totalsuccess=ceil($value[0]['success']/8)+($tobeReturned['apirequests'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']]['successrequests']);

		$tobeReturned['apirequests'][$value['vendors_activations']['product_id']][$value['vendors_activations']['vendor_id']]=array('totalrequests'=>$totalrequests,
                                                                                                                                                                                                 'successrequests'=>$totalsuccess,
                                                                                                                                                                                                 'company'=>$value['vendors']['shortForm'],
                                                                                                                                                                                                 'modem_id'=>$value['vendors_activations']['vendor_id'],
                                                                                                                                                                                                  'sale'=>$totalsale);   
		endif;
		 
		endif;
		 
		endforeach;

		 
		return $tobeReturned;

	}

	 
	public function combineRequests($data)
	{
		$arr_map = array('7'=>'8','10'=>'9','27'=>'9','28'=>'10','29'=>'11','31'=>'30','34'=>'3');
		 
		foreach($data as $key=>$value):

		if(isset($arr_map[$value['vendors_activations']['product_id']])):

		$data[$key]['vendors_activations']['product_id']=$arr_map[$value['vendors_activations']['product_id']];

		endif;

		endforeach;

		return $data;

	}
	 
	 
	public function __checkAccess($modems)
	{
		// Create temp accessible array
		$accessiblemodems=array();


		// Insert Accessible modems in array
		foreach($modems as $modem):
		if($modem['vendors']['user_id']==$this->Session->read('Auth.User.id')):
		$accessiblemodems[]=$modem;
		endif;
		endforeach;
		 

		// Check Access
		if((empty($accessiblemodems) && $this->Session->read('Auth.User.group_id')=="9")):
		echo "Invalid access"; exit;
		endif;
		 
		if(!empty($accessiblemodems)):
		return $accessiblemodems;
		endif;
		 
		return $modems;

	}
	 
	public function __getSimdataFromDb($date,$modem_id)
	{
		$temp=array();
	 
		$params=$this->params['url'];

                $sql="SELECT * FROM  devices_data "
		. " WHERE vendor_id='{$modem_id}' AND sync_date='{$date}' ";
		 
		if(!empty($params['operators'])):
		$sql.=" AND opr_id='{$params['operators']}' ";
		endif;

		if(!empty($params['saleamtFrom']) && !empty($params['saleamtTo'])):
		$sql.=" AND sale>='{$params['saleamtFrom']}' AND sale<='{$params['saleamtTo']}' ";
		endif;

		if(!empty($params['suppliername'])):
		$sql.=" AND ( vendor like '%{$params['suppliername']}%' OR vendor_tag like '%{$params['suppliername']}%' )  ";
		endif;

		if(!empty($params['searchbymobile'])):
		$sql.=" AND mobile like '%{$params['searchbymobile']}%' ";
		endif;

                if(($params['diffFrom']!="") && ($params['diffTo']!="") && is_numeric($params['diffFrom']) && is_numeric($params['diffTo'])):
		if($date==date('Y-m-d')):
                                                $sql.=" AND ((sale-opening+balance-inc-tfr)>='{$params['diffFrom']}' AND (sale-opening+balance-inc-tfr)<='{$params['diffTo']}')";
                                                else:
                                                $sql.=" AND ((sale-opening+closing-inc-tfr)>='{$params['diffFrom']}' AND (sale-opening+closing-inc-tfr)<='{$params['diffTo']}')";
                                                endif;
                endif;

                if(!empty($params['serverdiffcheckbox'])):
		if($date==date('Y-m-d')):
		$sql.=" AND ((server_diff-(sale-opening+balance-inc-tfr))<-100 OR  (server_diff-(sale-opening+balance-inc-tfr))>100) ";
		else:
		$sql.=" AND ((server_diff-(sale-opening+closing-inc-tfr))<-100 OR  (server_diff-(sale-opening+closing-inc-tfr))>100) ";
		endif;
		endif;

		if(!empty($params['color'])):
		$currenttime=date('Y-m-d H:i:s');
		switch (urldecode($params['color'])):
		case "#c73525":
			$sql.=" AND active_flag='0' AND balance>3000 ";
			break;
		case "#8c65e3":
			$sql.=" AND active_flag='1' AND balance<3000 ";
			break;
		case "#99ff99":
			$sql.=" AND active_flag='1'  ";
			break;
		case "#f6ff00":
			$sql.=" AND  active_flag='1' AND balance>3000 AND last<=DATE_SUB('{$currenttime}',INTERVAL 45 MINUTE) ";
			break;
		case "#c0c0c0":
			$sql.=" AND ( roaming_limit>0 AND roaming_today >=0 AND (roaming_today < roaming_limit-100) AND active_flag<>0  AND balance > 100  )";
			break;
		case "#ffa500":
			$sql.=" AND ( roaming_limit>0 AND roaming_today >=0 AND (roaming_today < roaming_limit-100) AND active_flag<>1  AND balance > 100  )";
			break;
		case "#19ffd1":
			$sql.=" AND last<=DATE_SUB('{$currenttime}',INTERVAL 36 HOUR) AND balance>100  ";
			break;
		case "#99ffcc":
			$sql.=" AND block='1' ";
			break;
		default:
			$sql.="";
			endswitch;
			endif;
			 

			//  echo "<br/>";

			$result=$this->Slaves->query($sql);

                        $temp=array();
			 
			if(!empty($result)):
			foreach ($result as $r):
			$r['devices_data']['id']=$r['devices_data']['device_id'];
			$temp[]=$r['devices_data'];
			endforeach;
			else:
			// If no data exists in devices table
			$checkrecordsexists=$this->Slaves->query("Select id from devices_data where sync_date='{$date}'");
			if(empty($checkrecordsexists)):
			return $this->modemBalance($date,$modem_id);
			else:
			return array();
			endif;
			endif;
			 
			return $temp;
			 
	}
	 
	// Code to yellow color sims with stop_flag=2
	public function getyellowsimsbymodemid()
	{
                                $this->autoRender=false;
                               
                                if($this->RequestHandler->isAjax()):
                                $modemid=$this->params['url']['modemid'];
                               $date=date('Y-m-d'); $currenttime=date('Y-m-d H:i:s');
                                $memcachedata=$this->Shop->getMemcache("yellowsims_$modemid");
                                $memcachedata=json_decode($memcachedata,true);
                                
                                if(!empty($memcachedata)):
                                        $result=$memcachedata;
                                else:
                                        $result = $this->Slaves->query("SELECT vendor_id,device_id,mobile FROM devices_data WHERE vendor_id IN ($modemid) AND sync_date='{$date}' AND active_flag='1' AND balance>3000 AND last<=DATE_SUB('{$currenttime}',INTERVAL 15 MINUTE ) AND stop_flag='2' ");
                                        if(!empty($result)):
                                         $this->Shop->setMemcache("yellowsims_$modemid", json_encode($result), 1800);
                                        endif;
                                endif;
                                //$result=  $this->Slaves->query("SELECT vendor_id,device_id,mobile FROM devices_data WHERE vendor_id='{$modemid}' AND sync_date='{$date}' AND active_flag='1' AND balance>3000 AND last<=DATE_SUB('{$currenttime}',INTERVAL 15 MINUTE ) AND stop_flag='2' ");
                                
                                if($result):
		$temp=array();
		foreach($result as $row):
                                                $temp[$row['devices_data']['vendor_id']][]=$row['devices_data'];
		endforeach;
                                                echo json_encode(array('status'=>'success','data'=>$temp));exit();
                                endif;
                                
                                echo json_encode(array('status'=>'success','data'=>''));
                                endif;
	}
	 
	public function testmemcache($id)
	{
	    ///echo "<pre>";
		// print_r($this->Shop->getMemcache("DistinctOperatorwiseReportByModemId_$id"));
		print_r($this->Shop->getMemcache("balance_$id"."_last"));
		print_r($this->Shop->getMemcache("balance_timestamp_$id"."_last"));
		print_r($this->Shop->getMemcache("balance_ports_$id"));
		// echo "</pre>";
		 
	}

	public function adjustPendings($params)
	{
		 
		$this->autoRender=false;
		 
		$emailbody="";
		 
		// $params=array('operator_id'=>17,'vendor_id'=>'4','mobile'=>'8657124712','date'=>'2015-09-05','newincoming'=>'20000','oldincoming'=>'15000');
		 
		// Get soid from devices tables based on passed parameters
		$get="Select devices_data.supplier_operator_id,devices_data.tfr,so.commission_type, so.commission_type_formula "
		. " from devices_data left join inv_supplier_operator so ON ( so.id = devices_data.supplier_operator_id )    "
		. " where opr_id='{$params['operator_id']}' AND vendor_id='{$params['vendor_id']}' AND mobile='{$params['mobile']}' AND sync_date='{$params['date']}'  ";
		 
		$getresult=  $this->User->query($get);

		$supplier_operator_id=$getresult[0]['devices_data']['supplier_operator_id'];
		$oldincoming=$getresult[0]['devices_data']['tfr'];

		$emailbody.="Siminfo : ". "Vendor ID:  {$params['vendor_id']} <br/>SIM No:  {$params['mobile']} <br/>Operator: {$params['operator_id']} <br/>Old Incoming: {$oldincoming} <br/>New Incoming: {$params['newincoming']} <br/>Incoming changed by: {$this->Session->read('Auth.User.name')} <br/>Incoming changed userid: {$this->Session->read('Auth.User.id')}"."<br/>";


		//Update devices data table with new incoming
		$updatedevice="Update devices_data  set tfr='{$params['newincoming']}' Where mobile='{$params['mobile']}' AND opr_id='{$params['operator_id']}' AND vendor_id='{$params['vendor_id']}' AND sync_date='{$params['date']}'";
		$this->User->query($updatedevice);

		$emailbody.=$updatedevice;

		if($supplier_operator_id>0):

		$emailbody.="<br/>Soid : ".$supplier_operator_id."<br/><br/>";

		// Get all pendings greater than that date
		$sql="Select id,pending,incoming,pending_date from inv_pendings p  where pending_date>='{$params['date']}'   AND p.supplier_operator_id='{$supplier_operator_id}'  order by pending_date asc ";
                                                     $result=  $this->User->query($sql);
		 
		$emailbody.="<b>Before data : </b> <br/><br/>".json_encode($result)."<br/><br/>";

		// Pending amt to adjust
		$pendingtoadjust=$params['newincoming']-$oldincoming;
		$invtoadjust=($getresult[0]['so']['commission_type'] == '1') ? ($pendingtoadjust * ((100 - $getresult[0]['so']['commission_type_formula'])/100)) : ($pendingtoadjust * (100 / (100 + $getresult[0]['so']['commission_type_formula'])));
		$earntoadjust=$pendingtoadjust-$invtoadjust;
		 
		$emailbody.="Pending to adjust : ".$pendingtoadjust."<br/><br/>";

		if(count($result)>0):

		// Create update array
		if($params['newincoming'] != $oldincoming):

		foreach ($result as $value):
		$updatearray[]=array('id'=>$value['p']['id'],'pending'=>$value['p']['pending']+$pendingtoadjust);
		endforeach;

		$emailbody.="<b>After data : </b> <br/><br/>".json_encode($updatearray)."<br/>";

		//Update Incoming column of that date with new value
		$this->User->query("Update inv_pendings set incoming=incoming+{$pendingtoadjust} where id='{$result[0]['p']['id']}'");
		$this->removeFromHighlights($params);
		//adjust earnings table wrt investment report
		$datetime=date('Y-m-d H:i:s');
		$this->User->query("Update earnings_logs SET update_time='{$datetime}',incoming=incoming+{$pendingtoadjust},invested=invested+{$invtoadjust},expected_earning=expected_earning+{$earntoadjust} where vendor_id = '".$params['vendor_id']."' AND date = '".$params['date']."'");
		 
		 
		foreach($updatearray as $update):
		$q = "";
		$q = "Update inv_pendings set pending='{$update['pending']}' where id='{$update['id']}' ";
		$this->User->query($q);
		endforeach;

		// Send Mail
		if(strtotime($params['date'])<=strtotime('today -1 days')):
		$this->General->sendMails('Pending Adjusted',$emailbody,array('accounts@pay1.in','backend@pay1.in','ashish@pay1.in','orders@pay1.in','vinit@pay1.in'),'mail');
		endif;

		// Create Log
		$emailbody.="<br/><br/>---------------------------------------------------------------------------------------------------------------------------------------------<br/><br/>";
		$this->General->logData("/mnt/logs/pendingadjusted.txt",date('Y-m-d H:i:s')." :: ".$emailbody);

		endif;

		endif;

		endif;
	}
	 

	public function storeIncomingRequestinRedis($params=array(),$querystr="",$vendor_id=0)
	{
		//                $params=array('testing params');
		//                $querystr="query=UpdateIncoming&device_id=30&bal=100&user_id=1&date=2015-08-14";
		//                $vendor_id=4;

		$this->autoRender=false;
		 
		if($vendor_id>0 and $querystr!="")

		$redis = $this->Shop->openservice_redis();
		$queuename="updateIncoming_{$vendor_id}";
		$items=$redis->lrange($queuename,0,-1);
		$exists=false;
		 
		foreach($items as $item):
		if($item==$querystr):
		$exists=true;
		break;
		endif;
		endforeach;
		 
		 
		if(!$exists):
		$queuevalue=$querystr;
		$redis->lpush($queuename,$queuevalue);
		$this->General->logData('/mnt/logs/updateIncomingdata_'.date('Y-m-d').'.txt',"\n".date("Y-m-d H:i:s")." -- Success : {$querystr} \nPARAMS : ".  json_encode($params)." \n ",FILE_APPEND | LOCK_EX);
		else:
		$this->General->logData('/mnt/logs/updateIncomingdata_'.date('Y-m-d').'.txt',"\n".date("Y-m-d H:i:s -- ")."Item already exists : {$querystr} \nPARAMS : ".  json_encode($params)." \n ",FILE_APPEND | LOCK_EX);
		endif;
		 

	}


	function isUpfromlast5min($vendor)
	{

		$lasttimestamp=$this->Shop->getMemcache("balance_timestamp_$vendor"."_last");

		if(strtotime($lasttimestamp)>strtotime('-5 minutes')):
		return true;
		endif;

		return false;

	}

	function del()
	{
		$this->autoRender=false;
		$redis = $this->Shop->openservice_redis();
		echo "<pre>";
		print_r($redis->lrange("updateIncoming_41",0,-1));
		echo "</pre>";
		$redis->del("updateIncoming_41");
		echo "<pre>";
		print_r($redis->lrange("updateIncoming_41",0,-1));
		echo "</pre>";
		exit();


	}

	function CheckIncomingAccess($date)
	{
		$group_id=$this->Session->read('Auth.User.group_id');

		// Root access
		if($group_id==25):
		return;
		endif;

		// Restrict Other member to edit incoming less than day
		if($group_id!=10):
		echo json_encode(array('status' => 'Error', 'data' => 'Error : Only Accounts team can update incoming less than 1 day'));
		exit();
		endif;

		// Restrict Accounts to edit incoming less than 2 days
		if($group_id==10 && strtotime($date)<strtotime('today -7 days')):
		echo json_encode(array('status' => 'Error', 'data' => 'Error : Only Admin team can update incoming less than 2 days'));
		exit();
		endif;

	}

	function CheckClosingAccess($date)
	{
		$group_id=$this->Session->read('Auth.User.group_id');

		// Root access
		if($group_id==25):
		return;
		endif;

		// Restrict Other user from editing closing
		if($group_id!=10):
		echo json_encode(array('status' => 'Error', 'data' => 'Error : Only accounts team can update Closing'));
		exit();
		endif;

		// Restrict Accounts section from editing closing less than 2 days
		if($group_id==10 && strtotime($date)<strtotime('today -7 days')):
		echo json_encode(array('status' => 'Error', 'data' => 'Error : Only Admin can update closing less than 2 days'));
		exit();
		endif;
		 

	}

	function updateClosing()
	{
		$this->autoRender=false;

		if ($this->RequestHandler->isAjax()):

		$params=$this->params['form'];
		 
		$this->CheckClosingAccess($params['date']);
		 
		$nextDay=date('Y-m-d',strtotime($params['date'].' +1 day'));

		$query="query=UpdateClosing&device_id={$params['device_id']}&closing={$params['closing']}&date={$params['date']}";
		 
		$lastClosing = "SELECT closing FROM devices_data where vendor_id='{$params['vendor_id']}' AND sync_date='{$params['date']}' AND device_id='{$params['device_id']}' and mobile='{$params['mobile']}' ";
		$lastCl = $this->User->query($lastClosing);
		$lastCl = $lastCl['0']['devices_data']['closing'];
		// Update Devices_Data table
		$updateClosing="Update devices_data set closing='{$params['closing']}' where vendor_id='{$params['vendor_id']}' AND sync_date='{$params['date']}' AND device_id='{$params['device_id']}' and mobile='{$params['mobile']}' ";
		$this->User->query($updateClosing);

		$updateOpening="Update devices_data set opening='{$params['closing']}' where vendor_id='{$params['vendor_id']}' AND sync_date='{$nextDay}' AND device_id='{$params['device_id']}' and mobile='{$params['mobile']}' ";
		$this->User->query($updateOpening);

		$adjustClosing = $params['closing'] - $lastCl;
		$updateClosing="UPDATE earnings_logs SET closing=closing+'{$adjustClosing}' where vendor_id='{$params['vendor_id']}' AND date='{$params['date']}'";
		$this->User->query($updateClosing);
		$updateOpening="Update earnings_logs set opening=opening+'{$adjustClosing}' where vendor_id='{$params['vendor_id']}' AND date='{$nextDay}'";
		$this->User->query($updateOpening);
		// Update Modem level
		$mode="modemrequest";

		if($this->isUpfromlast5min($params['vendor_id'])):
		 
		$response=$this->Shop->modemRequest($query,$params['vendor_id']);
		 
		echo json_encode(array('status'=>'success','data'=>$response['data']));
		 
		else:

		$mode="Redis";

		$this->storeClosingRequestinRedis($params,$query,$params['vendor_id']);

		echo json_encode(array('status'=>'success','data'=>"Closing Updated Successfully"));
		 
		endif;

		$body="Old Closing : {$params['old_closing']}  <br/> New Closing : {$params['closing']} <br/> Date :  {$params['date']} <br/> Querystring : {$query} <br/> Mode : {$mode} ";
		$this->General->sendMails('(SOS) Closing Updated',$body,array('accounts@pay1.in','backend@pay1.in','orders@pay1.in','vinit@pay1.in','ashish@pay1.in'),'mail');

		endif;
		exit();
	}

	public function updateBalance()
	{
		$this->autoRender=false;

		if ($this->RequestHandler->isAjax()):

		$params=$this->params['form'];

		if($this->Session->read('Auth.User.group_id')!=2):
		echo json_encode(array('status' => 'Error', 'data' => 'Error : Only root user can update balance'));
		exit();
		endif;

		$query="query=UpdateBalance&device_id={$params['parbal']}&bal={$params['newbalance']}&user_id={$this->Session->read('Auth.User.id')}";

		$updateSuccess=false;
		 
		if($this->isUpfromlast5min($params['vendor_id'])):
		 
		$mode="Modemreq";
		 
		$response=$this->Shop->modemRequest($query,$params['vendor_id']);
		 
		echo json_encode(array('status'=>'success','data'=>$response['data']));

		$updateSuccess=($response['data']=="Balance Update Success")?true:false;
		 
		else:
		 
		$mode="Redis";

		$this->storeBalanceRequestinRedis($params,$query,$params['vendor_id']);

		echo json_encode(array('status'=>'success','data'=>"Balance Update Success"));
		 
		$updateSuccess=true;
		 
		endif;

		if($updateSuccess):
		$body="Details : <br/> Old Balance :  {$params['oldbalance']} <br/> New Balance : {$params['newbalance']} <br/> Parbal : {$params['parbal']}  <br/> Mobile : {$params['mobile']}  <br/> Userid : {$this->Session->read('Auth.User.id')}  <br/> Query : {$query}  <br/> Mode : {$mode}";
		$this->General->sendMails("(SOS) Balance Updated",$body,array('accounts@pay1.in','backend@pay1.in','orders@pay1.in','vinit@pay1.in','ashish@pay1.in'),'mail');
		$this->General->logData('/tmp/updateBalancedatasuccess_'.date('Y-m-d').'.log',"\n".date("Y-m-d H:i:s")." -- Success : {$query} \nPARAMS : ".  json_encode($params)."  Mode : {$mode} \n \n ",FILE_APPEND | LOCK_EX);
		endif;

		endif;
	}


	public function storeBalanceRequestinRedis($params=array(),$query="",$vendor_id=0)
	{
		$this->autoRender=false;

		if($query!="" && $vendor_id>0):
		 
		$redisObj = $this->Shop->openservice_redis();
		$queuename="updateIncoming_{$vendor_id}";
		$items=$redisObj->lrange($queuename,0,-1);
		$exists=false;

		foreach($items as $item):
		if($item==$query):
		$exists=true;
		break;
		endif;
		endforeach;

		if(!$exists){
			$redisObj->lpush($queuename,$query);
			$this->General->logData('/tmp/updateBalancedataredis_'.date('Y-m-d').'.log',"\n".date("Y-m-d H:i:s")." -- Success : {$query} \nPARAMS : ".  json_encode($params)." \n ",FILE_APPEND | LOCK_EX);
		}else{
			$this->General->logData('/tmp/updateBalancedataredis_'.date('Y-m-d').'.log',"\n".date("Y-m-d H:i:s -- ")."Item already exists : {$query} \nPARAMS : ".  json_encode($params)." \n ",FILE_APPEND | LOCK_EX);
		}

		endif;
	}

	public function storeClosingRequestinRedis($params=array(),$query="",$vendor_id=0)
	{
		$this->autoRender=false;
		 
		if($query!="" && $vendor_id>0):
		 
		$redisObj = $this->Shop->openservice_redis();
		$queuename="updateIncoming_{$vendor_id}";
		$items=$redisObj->lrange($queuename,0,-1);
		$exists=false;
		 
		foreach($items as $item):
		if($item==$query):
		$exists=true;
		break;
		endif;
		endforeach;

		if(!$exists){
			$redisObj->lpush($queuename,$query);
			$this->General->logData('/mnt/logs/updateClosingdata_'.date('Y-m-d').'.txt',"\n".date("Y-m-d H:i:s")." -- Success : {$query} \nPARAMS : ".  json_encode($params)." \n ",FILE_APPEND | LOCK_EX);
		}else{
			$this->General->logData('/mnt/logs/updateClosingdata_'.date('Y-m-d').'.txt',"\n".date("Y-m-d H:i:s -- ")."Item already exists : {$query} \nPARAMS : ".  json_encode($params)." \n ",FILE_APPEND | LOCK_EX);
		}
		 
		endif;
	}

	public function getHighlights()
	{
		$this->autoRender=false;
		$where="";

		if($this->Session->read('Auth.User.group_id')=="9"):

		$queryresult=  $this->Slaves->query("Select id from vendors where user_id='{$this->Session->read('Auth.User.id')}' ");

		$ids=array();

		if(!empty($queryresult)):
		foreach($queryresult as $value):
		$ids[]=$value['vendors']['id'];
		endforeach;
		endif;

		$ids=empty($ids)?"":(count($ids)>1?implode(',',$ids):$ids[0]);

		$where=!empty($ids)?" AND vendor_id IN ({$ids}) ":"  ";

		endif;

		$date=date('Y-m-d',strtotime('today - 3 days'));
		$sql="Select * from devices_highlights  where 1 {$where} and sync_date>='{$date}' ";

		$result=  $this->Slaves->query($sql);

		if($result):
		$temp=array();
		foreach($result as $row):
		$temp[$row['devices_highlights']['sync_date']][]=$row;
		endforeach;
		echo json_encode(array('status'=>true,'type'=>true,'data'=>$temp));exit();
		endif;

		echo json_encode(array('status'=>true,'type'=>false,'msg'=>"No data avaliable"));
			
	}


	function lastModemTransactions($vendor,$device,$page,$limit=null,$date=null){

		$pageType = empty($_GET['res_type']) ? "" : $_GET['res_type'];
                 
                                                 if(!($this->Session->read('Auth.User.group_id')=="9")):
                                                $adm = "query=last&device=$device&page=$page&limit=$limit&date=$date&vendor=$vendor";
                                                else:
                                                $adm = "query=last&device=$device&page=1&limit=10&date=$date&vendor=$vendor";   
                                                endif;
              
		if($this->isUpfromlast5min($vendor,$date)):
		$Rec_Data = $this->Shop->modemRequest($adm,$vendor,null,120);
		$Rec_Data = $Rec_Data['data'];
        $this->set('vendor',$vendor);
        $this->set('dates',$date);                
		$this->set('device',$device);
		$this->set('page',$page);
		$data = json_decode($Rec_Data,true);
			
		$this->set('pageType',$pageType);
		if($pageType == 'csv'){
			App::import('Helper','csv');
			$this->layout = null;
			$this->autoLayout = false;
			$csv = new CsvHelper();
			$line = array("Sr. No", 	"Mobile/Sub Id", 	"Amount", 	"Ref Id", 	"Status", 	"Incentive", 	"Trials", 	"Cause", 	"SIM Balance", 	"SMS Received", 	"Added at", 	"Processed at", 	"Status updated at");
			$csv->addRow($line);
			$i=1;
			foreach($data as $md){

				$status = "";
				if($md['status'] == 0){
					$status= "In Process";
				}
				else if($md['status'] == 1){
					$status= "Successful";
				}
				else {
					$status= "Failed";
				}
				$temp = array($i,$md['mobile']."/".$md['param'],$md['amount'],$md['vendor_refid'],$status,$md['incentive'],$md['trials'],$md['cause'],$md['sim_balance'],$md['message'],$md['timestamp'],$md['processing_time'],$md['updated']);
				$csv->addRow($temp);
				$i++;
			}

			echo $csv->render("lastTransactions_device=".$device."_page=".$page."_limit=".$limit."_date=$date".".csv");

		}else{
			$this->set('data',$data);
		}

		$this->render('last_transactions');
		$this->autoRender = false;

		else:
		
                $adm.="&vendor_id=$vendor";
                                    
                if($vendor <= 60){
                    $port = "221";
                } else if($vendor > 60 && $vendor <= 120){
                    $port = "222";
                } else if($vendor > 120){
                    $port = "223";
                }
                
        $data=file_get_contents("http://mysqlpay1.ddns.net:$port/start.php?$adm");
		$data = json_decode($data,true);
        $this->set('vendor',$vendor);
        $this->set('dates',$date);
		$this->set('device',$device);
		$this->set('page',$page);
		$this->set('data',$data);
		$this->set('pageType',$pageType);
		$this->render('last_transactions');
		$this->autoRender = false;
		endif;
	}

	function lastModemSMSes($vendor=null,$device=null,$page,$limit=null,$date=null,$all=null){
               //Passing value from Controller to view
                $paravendor = $this->params['pass'][0];
                $this->set('paramvendor',$paravendor);
                
                $paradev = $this->params['pass'][1];
                $this->set('paramdev',$paradev);
                
                $paradate   = $this->params['pass'][4];
                $this->set('paramdate',$paradate);
                
                //Removing Default layout

                $this->layout='plain';
		// Check if last sync timestamp of modem
		if($this->isUpfromlast5min($vendor,$date)):
		$adm = "query=sms&device=$device&page=$page&limit=$limit&date=$date";
                    if(!empty($all) && $all == 'all'):
                           $adm = "query=sms&device=$device&page=$page&limit=$limit&date=$date&all=$all";   
                    endif;
		$Rec_Data = $this->Shop->modemRequest($adm,$vendor);
		$Rec_Data = $Rec_Data['data'];
		$res = json_decode($Rec_Data,true);
		else:
		$adm = "query=sms&device=$device&page=$page&limit=$limit&date=$date&vendor_id=$vendor";
 
                if($vendor <= 60){
                    $port = "221";
                } else if($vendor > 60 && $vendor <= 120){
                    $port = "222";
                } else if($vendor > 120){
                    $port = "223";
                }
                
                $data=file_get_contents("http://mysqlpay1.ddns.net:$port/start.php?$adm");
                $res = json_decode($data,true);
		endif;
                $this->set('modem_sms', $res);
                $this->render('lastmodemsmses');
	}

	function resetModemDevice(){
		$device_id = $_REQUEST['device'];
		$vendor = $_REQUEST['vendor'];
		$adm = "query=.reset&device=$device_id";
		$Rec_Data = $this->Shop->modemRequest($adm,$vendor);
		$this->autoRender = false;
	}

	function stopModemDevice(){
		$device_id = $_REQUEST['device'];
		$stop = $_REQUEST['stop'];
		$vendor = $_REQUEST['vendor'];

		$adm = "query=stop&device=$device_id&stop=$stop";
		$Rec_Data = $this->Shop->modemRequest($adm,$vendor);
		$Rec_Data = $Rec_Data['data'];
		echo $Rec_Data;
		$this->autoRender = false;
	}

	public function removeFromHighlights($params)
	{
		$this->autoRender=false;
		//$params=array('operator_id'=>'2','vendor_id'=>'38','date'=>'2015-09-22','mobile'=>'7541012068','newincoming'=>'1200');
		$sql="Select * from devices_highlights where sync_date='{$params['date']}' ";
		 
		$result=  $this->Slaves->query($sql);

		if(!empty($result)):
		foreach($result as $row):
		if($row['devices_highlights']['opr_id']==$params['operator_id'] && $row['devices_highlights']['vendor_id']==$params['vendor_id'] && $row['devices_highlights']['mobile']==$params['mobile']  && $row['devices_highlights']['sync_date']==$params['date']):
		$newdiff=$row['devices_highlights']['diff']+$params['newincoming'];
		if($newdiff==0 || $newdiff>=-500):
		$this->User->query("Delete from devices_highlights where id='{$row['devices_highlights']['id']}' ");
		$this->General->logData('/mnt/logs/diffhighlights_'.date('Y-m-d').'.txt',"\n".date("Y-m-d H:i:s -- ")."Diff deleted :  newdiff={$newdiff}  \nPARAMS : ".  json_encode($params). " \n Row : ".  json_encode($row)."  \n ",FILE_APPEND | LOCK_EX);
		else:
		$this->User->query("Update devices_highlights set diff='{$newdiff}' where id='{$row['devices_highlights']['id']}' ");
		$this->General->logData('/mnt/logs/diffhighlights_'.date('Y-m-d').'.txt',"\n".date("Y-m-d H:i:s -- ")."Diff updated  : {$query}  newdiff={$newdiff} \nPARAMS : ".  json_encode($params)." \n Row : ".  json_encode($row)." \n ",FILE_APPEND | LOCK_EX);
		endif;
		endif;
		endforeach;
		endif;
		 
	}
	 
	//check sim status
	function checkSimStatus()
           {
            $this->autoRender = false;
            
            $device_id = $this->params['form']['device'];
            $vendor = $this->params['form']['vendor'];
            $adm = "query=simstatus&device=$device_id";
            $Rec_Data = $this->Shop->modemRequest($adm,$vendor);
            $Rec_Data = $Rec_Data['data'];
            echo $Rec_Data;
           }

	//negative difference
        function checkNegDiff()
            {
            $this->autoRender = false;
            
            $device_id = $this->params['form']['device'];
            $vendor = $this->params['form']['vendor'];
            $adm = "query=negdiff&device=$device_id";
            $Rec_Data = $this->Shop->modemRequest($adm,$vendor);
            $Rec_Data = $Rec_Data['data'];
            //echo json_encode($Rec_Data);
            echo $Rec_Data;
            }

	//remove sim
        function removeSim()
            {
            $this->autoRender = false;
            
            $device_id = $this->params['form']['device'];
            $vendor = $this->params['form']['vendor'];
            $adm = "query=simremove&device=$device_id";
            $Rec_Data = $this->Shop->modemRequest($adm,$vendor);
            $Rec_Data = $Rec_Data['status'];
//            echo json_encode($Rec_Data);
            echo $Rec_Data;
            }

	//recharge type
        function rechargeType()
            {
            $this->autoRender = false;
            
            $type = array('1'=>'app','2'=>'sms','3'=>'ussd','4'=>'web');
            
            $recharge_type = $this->params['form']['recharge_type'];
            $operator_id = $this->params['form']['operatorid'];
            $device_id = $this->params['form']['device'];
            $vendor = $this->params['form']['vendor'];
            
            $adm = "query=setrechargetype&device=$device_id&opr_id=$operator_id&recharge_type=$type[$recharge_type]&type=$recharge_type";
            $Rec_Data = $this->Shop->modemRequest($adm,$vendor);
            $Rec_Data = $Rec_Data['status'];
//            echo json_encode($Rec_Data);
            echo $Rec_Data;
            }

	//send block sms to suppliers and inventory team
	function sendBlockSms()
	{
		$this->autoRender = false;
		//$url="http://www.smstadka.com/redis/insertInQsms";
		$inv_supplier_id=$this->params['form']['inv_supplier_id'];
		$mobile=$this->params['form']['mobile'];
		$vendor=$this->params['form']['vendor'];
		$operator=$this->params['form']['operator'];
		$balance=$this->params['form']['balance'];

		$query="select contact from inv_supplier_contacts where supplier_id=$inv_supplier_id and to_send=1";
		$sendblocksms=$this->Slaves->query($query);
		$blocknos=array();

		foreach ($sendblocksms as $value):
		$blocknos[]=$value['inv_supplier_contacts']['contact'];
		endforeach;

		$blocknumarr=array_merge($blocknos,Configure::read('blocksmsrecepients'));

		foreach($blocknumarr as $nos):
		//$curl=$this->General->curl_post_async($url,array('mobile'=>$nos,'root'=>'tata','sms'=>"Kindly do not put balance in Sim $mobile till our next intimation. Vendor name:$vendor Operator:$operator Block Amount:$balance"),'GET');
                                               $this->General->sendMessage($nos,"Kindly do not put balance in Sim $mobile till our next intimation. Vendor name:$vendor Operator:$operator Block Amount:$balance","shops");    
		endforeach;

		echo json_encode(array('status'=>'success'));
		exit();

	}

	function allBalance($modem_id = 0,$date=null,$last=0){

		$office_ips = explode(",",OFFICE_IPS);
		$office_ips[] = '127.0.0.1';
		
		$client_ip = $this->General->getClientIP();
		

		if(in_array($client_ip,$office_ips) || $_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['id'] == 1 || $_SESSION['Auth']['User']['group_id'] == 9){

		}
		else $this->redirect('/shops/view');

		$data = $this->Shop->getVendors();
		foreach($data as $dt){
			if($dt['vendors']['user_id'] == $_SESSION['Auth']['User']['id'])
			$check = $dt;
		}
		//$check = $this->User->query("SELECT * FROM `vendors` WHERE user_id = ".$_SESSION['Auth']['User']['id']);
		if($modem_id != '12323'){
			if((empty($check) && $_SESSION['Auth']['User']['group_id'] == 9) || (!empty($check) && $check['vendors']['id'] != $modem_id)){
				echo "Invalid access"; exit;
			}
		}

		if($modem_id == '12323' || empty($date))$date = date('Y-m-d');
		$this->set('date',$date);

		$ips = array();
		$modems = array();
		$map = array();
		
		foreach($data as $dt){
			$inactive = $this->Shop->getInactiveVendors();

			if($dt['vendors']['update_flag'] == 1){

				if($modem_id != 0 && $modem_id != '12323' && $dt['vendors']['id'] != $modem_id) continue;

				$modem_bal = $this->Recharge->modemBalance($date,$dt['vendors']['id']);
				if(empty($modem_bal)) continue;

				$modem_bal['inactive'] = 0;
				if(in_array($dt['vendors']['id'],$inactive)) $modem_bal['inactive'] = 1;

				$modems[$dt['vendors']['id']] = $modem_bal;
				$ips[$dt['vendors']['id']] = $dt['vendors']['ip'].":".$dt['vendors']['port'];
				$map[$dt['vendors']['id']] = $dt['vendors']['company'];
			}
		}
			
		$vendors = $this->Slaves->query("SELECT * FROM `vendors` where update_flag = 0 AND show_flag = 1");
		$balances = array();
		$total = 0;
		$modem_bals = array();
		if(empty($vendors))$vendors = array();
		foreach($vendors as $vend){
			//if($vend['vendors']['update_flag'] == 0 && $vend['vendors']['active_flag'] == 1){
			$id = $vend['vendors']['id'];
			if($modem_id != 0 && $modem_id != '12323' && $id != $modem_id) continue;

			//$id = $vend['vendors']['id'];
			$name = $vend['vendors']['shortForm'];
			$method = $name . "Balance";

			if(method_exists($this, $method)){
				$balances[$name] = $this->$method(1);
				$total += $balances[$name]['balance'];
				$modem_bals[$id] = $balances[$name]['balance'];
			}
			//}
		}



		if($_SESSION['Auth']['User']['group_id'] != 9){
			$data = $this->Slaves->query("SELECT count(vendors_activations.id) as ids, vendors_activations.vendor_id,vendors_activations.product_id,products.name,vendors.shortForm,sum(if(vendors_activations.status !=2 AND vendors_activations.status !=3,1,0)) as success,sum(if(vendors_activations.status =2 OR vendors_activations.status =3,1,0)) as failure,vendors_commissions.active,vendors.update_flag FROM `vendors_activations`,products,vendors,vendors_commissions WHERE vendors_commissions.vendor_id= vendors.id AND vendors_commissions.product_id = products.id AND products.id = vendors_activations.product_id AND vendors.id = vendors_activations.vendor_id AND vendors_activations.date = '".date('Y-m-d')."' AND vendors_activations.timestamp >= '".date('Y-m-d H:i:s',strtotime('-30 minutes'))."' group by vendors_activations.product_id,vendors_activations.vendor_id order by vendors_activations.product_id");
			if(empty($data))$data = array();

			$prods = array();
			foreach($data as $dt){
				if(empty($prods[$dt['vendors_activations']['product_id']])){
					$prods[$dt['vendors_activations']['product_id']]['max'] = $dt['vendors_activations']['vendor_id'];
					$prods[$dt['vendors_activations']['product_id']]['total'] = $dt['0']['ids'];
					$prods[$dt['vendors_activations']['product_id']]['count'] = $dt['0']['success'];
					$prods[$dt['vendors_activations']['product_id']]['vendor'] = $dt['vendors']['shortForm'];
					$prods[$dt['vendors_activations']['product_id']]['failure'] = $dt['0']['failure'];
					$prods[$dt['vendors_activations']['product_id']]['active'] = $dt['vendors_commissions']['active'];
					$prods[$dt['vendors_activations']['product_id']]['modem_flag'] = $dt['vendors']['update_flag'];
				}
				else {
					if($prods[$dt['vendors_activations']['product_id']]['count'] < $dt['0']['ids']){
						$prods[$dt['vendors_activations']['product_id']]['max'] = $dt['vendors_activations']['vendor_id'];
						$prods[$dt['vendors_activations']['product_id']]['count'] = $dt['0']['success'];
						$prods[$dt['vendors_activations']['product_id']]['vendor'] = $dt['vendors']['shortForm'];
						$prods[$dt['vendors_activations']['product_id']]['modem_flag'] = $dt['vendors']['update_flag'];
						$prods[$dt['vendors_activations']['product_id']]['active'] = $dt['vendors_commissions']['active'];
						//$prods[$dt['vendors_activations']['product_id']]['failure'] = $dt['0']['failure'];
					}
					$prods[$dt['vendors_activations']['product_id']]['total'] = $prods[$dt['vendors_activations']['product_id']]['total'] + $dt['0']['ids'];
					$prods[$dt['vendors_activations']['product_id']]['failure'] = $prods[$dt['vendors_activations']['product_id']]['failure'] + $dt['0']['failure'];
				}
				$prods[$dt['vendors_activations']['product_id']]['name'] = $dt['products']['name'];

				$data1 = $this->Shop->getVendors();
				$last_array = array();
				foreach($data1 as $dt){
					$time = $this->Shop->getMemcache("vendor".$dt['vendors']['id']."_last");
					if(!empty($time)){
						$last_array[] = array('shortForm'=>$dt['vendors']['shortForm'],'timestamp'=>$time);
					}
				}
				//$data1 = $this->User->query("SELECT max(timestamp) as timestamp,vendors.shortForm FROM vendors left join `vendors_activations` ON (vendors_activations.vendor_id= vendors.id AND vendors_activations.date = '".date('Y-m-d')."') WHERE vendors.active_flag = 1 AND vendors_activations.status = 1 group by vendors.id");
				$this->set('last',$last_array);
			}

			$array = array();
			$data = $this->Shop->getProducts();

			foreach($data as $prod){
				if($prod['products']['monitor'] == 1){
					if(!isset($prods[$prod['products']['id']])){
						$array[$prod['products']['id']]['vendor'] = 0;
						$array[$prod['products']['id']]['count'] = 0;
						$array[$prod['products']['id']]['total'] = 1;
						$array[$prod['products']['id']]['name'] = $prod['products']['name'];
						$array[$prod['products']['id']]['failure'] = 0;
					}
					else if($prods[$prod['products']['id']]['modem_flag'] != 1 || $prods[$prod['products']['id']]['count']*100/$prods[$prod['products']['id']]['total'] < 60 || $prods[$prod['products']['id']]['failure']*100/$prods[$prod['products']['id']]['total'] > 20){
						$array[$prod['products']['id']] = $prods[$prod['products']['id']];
					}
				}
			}
			$this->set('prods',$array);

		}

		$this->set('balances',$balances);
		$this->set('modems',$modems);
		$this->set('map',$map);
		$this->set('ips',$ips);

		$modemRequests = $this->Slaves->query("SELECT * FROM modem_request_log order by created desc limit 0 , 100");
		$this->set('modemRequests',$modemRequests);

	}
        
        //Check balance
         function checkBalance()
            {
            $this->autoRender = false;
           
            $device_id = $this->params['form']['device'];
            $vendor = $this->params['form']['vendor'];
            $opr_id = $this->params['form']['opr_id'];
            $adm = "query=simbalance&device=$device_id&opr_id=$opr_id";
            $Rec_Data = $this->Shop->modemRequest($adm,$vendor);
            $Rec_Data = $Rec_Data['data'];
            //echo json_encode($Rec_Data);
            echo $Rec_Data;
            }
            
            function addBlockSimsData()
            {
                $date=date('Y-m-d');
                
                $vendorID = $this->params['form']['Vendorid'];
                $inv_supplier_id = $this->params['form']['inv_supplier_id'];
                $operator = $this->params['form']['operator'];
                $balance = $this->params['form']['balance'];
                $mobile = $this->params['form']['mobile'];
                $simId = $this->params['form']['simid'];
                $block = $this->params['form']['block'];
                $blocktag_id = $this->params['form']['blocktag_id'];
                $userid= $this->Auth->user('id');
                
                $checkifSimAlreadyExists=$this->User->query("Select * from inv_blocksims_history where mobile='{$mobile}' and vendor_id='{$vendorID}' and opr_id='{$operator}' order by id desc limit 1");
                
                if(!empty($checkifSimAlreadyExists)):
                    $id=$checkifSimAlreadyExists[0]['inv_blocksims_history']['id'];
                    $block_date=$checkifSimAlreadyExists[0]['inv_blocksims_history']['block_date'];
                    $resolved_date=$checkifSimAlreadyExists[0]['inv_blocksims_history']['resolved_date'];
                    $status=$checkifSimAlreadyExists[0]['inv_blocksims_history']['block'];
                    $resdate=($block==1)?'0000-00-00':date('Y-m-d');
                    
                    //if previously block sim is not resolved yet or sim is blocked today or previously block sim is resolved today then update that record
                    if(($status==1 && $block_date !== $date) || ($block_date == $date) || ($block_date !== $date && $resolved_date == $date)):
                        $updatequery="Update inv_blocksims_history "
                                . "set block='{$block}',resolved_date='{$resdate}',blocktag_id='{$blocktag_id}' "
                                . "where id='{$id}' ";

                                if($this->User->query($updatequery)):
                                echo json_encode(array("status" => "success","msg"=>"Prev record of this sim found. Block sim details are updated succefully"));
                                endif;
                    
                    else:
                        $this->User->query("insert into inv_blocksims_history(vendor_id,inv_supplier_id,opr_id,scid,mobile,balance,user_id,blocktag_id,block,block_date,block_time)values('".$vendorID."','".$inv_supplier_id."','".$operator."','".$simId."','".$mobile."','".$balance."','".$userid."','".$blocktag_id."','".$block."','".date('Y-m-d')."','".date("Y-m-d H:i:s")."')");
                        echo json_encode(array("status" => "success","msg"=>"Block sims details are added succefully"));
                    endif;
                else:
                        $this->User->query("insert into inv_blocksims_history(vendor_id,inv_supplier_id,opr_id,scid,mobile,balance,user_id,blocktag_id,block,block_date,block_time)values('".$vendorID."','".$inv_supplier_id."','".$operator."','".$simId."','".$mobile."','".$balance."','".$userid."','".$blocktag_id."','".$block."','".date('Y-m-d')."','".date("Y-m-d H:i:s")."')");
                        echo json_encode(array("status" => "success","msg"=>"Block sims details are added succefully"));
                endif;
                
                $this->autoRender = false;
            }
            
            //check if any previous record of block sim exists
            public function checkBlocksimStatus()
            { 
                $mobile = $this->params['form']['mobile'];
                $vendorID = $this->params['form']['Vendorid'];
                $operator = $this->params['form']['operator'];
                $today=date("Y-m-d");
                $date=date('Y-m-d',  strtotime('-2 days'));
                
                $data=$this->User->query("Select * from inv_blocksims_history where mobile='{$mobile}' and vendor_id='{$vendorID}' and opr_id='{$operator}' order by id desc limit 1");
                $block=$data[0]['inv_blocksims_history']['block'];
                $resolved_date=$data[0]['inv_blocksims_history']['resolved_date'];
                
                if(!empty($data) && ($block==0) && (($resolved_date >= $date) && ($resolved_date < $today)) ):
                    echo json_encode(array("status"=>"success"));
                else:
                    echo json_encode(array("status"=>"failure"));
                endif;
                
                $this->autoRender = false;
            }
            
            //reset previous blocksim record i.e set resolved date to 0000-00-00
            public function resetSimStatus() 
            {
                $mobile = $this->params['form']['mobile'];
                $vendorID = $this->params['form']['Vendorid'];
                $operator = $this->params['form']['operator'];
                
                $selectquery=$this->User->query("select id from inv_blocksims_history where mobile='{$mobile}' and vendor_id='{$vendorID}' and opr_id='{$operator}' order by id desc limit 1");
                
                $id=$selectquery[0]['inv_blocksims_history']['id'];
                $updatequery="Update inv_blocksims_history "
                            . "set block='1',resolved_date='0000-00-00' "
                            . "where id='{$id}'";
                            
                if($this->User->query($updatequery)):
                    echo json_encode(array("data" => "success","msg"=>"Data updated successflly"));
                endif;
                
                $this->autoRender = false;
            }
            
            //insert new record of block sim if user dont want to reset previous history
            public function addNewBlockSimsData()
            {
                $date=date('Y-m-d');
                $vendorID = $this->params['form']['Vendorid'];
                $inv_supplier_id = $this->params['form']['inv_supplier_id'];
                $operator = $this->params['form']['operator'];
                $balance = $this->params['form']['balance'];
                $mobile = $this->params['form']['mobile'];
                $simId = $this->params['form']['simid'];
                $block = $this->params['form']['block'];
                $blocktag_id = $this->params['form']['blocktag_id'];
                $userid= $this->Auth->user('id');

                if($this->User->query("insert into inv_blocksims_history(vendor_id,inv_supplier_id,opr_id,scid,mobile,balance,user_id,blocktag_id,block,block_date,block_time)values('".$vendorID."','".$inv_supplier_id."','".$operator."','".$simId."','".$mobile."','".$balance."','".$userid."','".$blocktag_id."','".$block."','".date('Y-m-d')."','".date("Y-m-d H:i:s")."')")):
                    echo json_encode(array("data" => "success","msg"=>"New record is added successfully."));exit();
                endif;
                
                $this->autoRender = false;
            }

            public function getSimBalRange($ids,$date)
            {
                $ids=empty($ids)?"":(count($ids)>1?implode(',',$ids):$ids[0]);
                
                $simbalarr=array();
                
                if(!empty($ids)):
                    
                        $query="SELECT so.id as so_id,so.supplier_id,so.operator_id,so.sim_bal_range, vendor_id "
                                . "FROM inv_supplier_operator so "
                                . "JOIN inv_supplier_vendor_mapping svm "
                                . "ON so.supplier_id = svm.supplier_id "
                                . "WHERE svm.vendor_id IN ($ids)";
                
                        $simbalrange= $this->Slaves->query($query);

                        foreach($simbalrange as $simbal):
                            $simbalarr[$simbal['so']['supplier_id']."_".$simbal['so']['operator_id']]=$simbal['so']['sim_bal_range'];
                        endforeach;
                
               endif;
               
                return $simbalarr;
                
            }
            
            public function getSimComments()
            {
                $oprid=$this->params['url']['opr_id'];
                $vendorid=$this->params['url']['vendor_id'];
                $scid=$this->params['url']['scid'];
                $commentdate=$this->params['url']['comment_date'];

                $query="select comment,comment_date,comment_timestamp,u.name from devices_comments dc join users u on (dc.user_id=u.id) where vendor_id='{$vendorid}' and opr_id='{$oprid}' and scid='{$scid}' and comment_date='{$commentdate}'";

                $result=$this->User->query($query);
                $comments=array();
                foreach($result as $res):
                    $comments[]=array('comment'=>$res['dc']['comment'],'name'=>$res['u']['name'],'comment_date'=>$res['dc']['comment_date'],'comment_timestamp'=>$res['dc']['comment_timestamp']);
                endforeach;

                if(!empty($comments)):
                    echo json_encode(array('status'=>true,'type'=>true,'comments'=>$comments));exit();
                endif;
                
                echo json_encode(array('status'=>true,'type'=>false,'msg'=>"Error"));exit();
            }
            
            public function saveComments()
            {
                $oprid=$this->params['form']['opr_id'];
                $vendorid=$this->params['form']['vendor_id'];
                $scid=$this->params['form']['scid'];
                $commentdate=$this->params['form']['comment_date'];
                $comment=$this->params['form']['comment'];
                $userid= $this->Auth->user('id');
                
                if($this->User->query("insert into devices_comments(vendor_id,opr_id,scid,comment,user_id,comment_date,comment_timestamp)values('".$vendorid."','".$oprid."','".$scid."','".$comment."','".$userid."','".$commentdate."','".date("Y-m-d H:i:s")."')")):
                    echo json_encode(array('status'=>true,'type'=>true,'msg'=>"Commented Successfully"));exit();
                endif;
                
                echo json_encode(array('status'=>true,'type'=>false,'msg'=>"Error"));exit();
            }
            
            public function getSimCommentsCount($ids,$date)
            {
                $modemids=count($ids)>1?implode(',',$ids):(!empty($ids)?$ids[0]:$ids);

                $commentcount=array();
                
                if(!empty($modemids)):
                    
                    $result=$this->User->query("select *,count(id) as messagecount from devices_comments where vendor_id in ($modemids) and comment_date='{$date}' group by vendor_id,opr_id,scid,comment_date");
                    
                    foreach ($result as $res):
                        $commentcount[$res['devices_comments']['vendor_id']."_".$res['devices_comments']['opr_id']."_".$res['devices_comments']['scid']."_".$res['devices_comments']['comment_date']]=$res[0]['messagecount'];
                    endforeach;
                
                endif;
                
                return $commentcount;
            }
}
