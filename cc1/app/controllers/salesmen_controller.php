<?php
class SalesmenController extends AppController {

	var $name = 'Salesmen';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
	var $components = array('RequestHandler','Shop');
	var $uses = array('Retailer','Slaves');

	function beforeFilter() {
		parent::beforeFilter();
		$this->layout = 'mobile';
		$this->Auth->allow('*');
	}

	/*function checkSession(){
		if(!isset($_SESSION['salesman']['mobile'])){
			$this->redirect('login');
			exit;
		}
	}

	function checkForAccess($method){
		$ret = true;
		//allowed to access functions
		$auth_salesman = array('createRetailer','addRetailer','topupAmount','amountTransfer','collectPayment');

		if(in_array($method,$auth_salesman))
		{
			if(isset($_SESSION['Auth'])){
				$ret = 404;

				$group_id = $_SESSION['Auth']['User']['group_id'];
				if($group_id == SALESMAN && in_array($method,$auth_salesman)){
					$ret = true;
				}
			}else{
				$ret = 403;
			}
		}
		return $ret;
	}

	function index(){
		$this->checkSession();
		$this->redirect('mainMenu');
	}

	function receiveWeb($format='json')
	{
		//	if(!in_array($format,$this->validFormats))$format = 'json';
		$method = $_REQUEST['method'];


		$this->User->query("INSERT INTO app_req_log (method,params,ret_id,timesatmp) VALUES ('$method','".json_encode($_REQUEST)."',".$_SESSION['Auth']['User']['id'].",'".date('Y-m-d H:i:s')."')");

		if(!method_exists($this, $method)){
			//$this->displayWeb(array('status'=>'failure','code'=>'2','description'=>$this->Shop->errors(2)), $format); exit;
		}

		try{
			$acl = $this->checkForAccess($method);
			if($acl !== true)
			{
				//$this->displayWeb(array('status'=>'failure','code'=>$acl,'description'=>$this->Shop->errors($acl)), $format);exit;
			}

			if(in_array($method,array('createRetailer','addRetailer')))
			{
				if($method == 'createRetailer' || $method == 'addRetailer')
				{
					$id = $this->Shop->addAppRequest($method,$_REQUEST['mobileNumber'],$_REQUEST['Shopname'],$_REQUEST['subArea'],$_REQUEST['type']);
				}
					
				if($method == 'topupAmount' )
				{
					$id = $this->Shop->addAppRequest($method,$_REQUEST['mobileNumber'],$_REQUEST['amount'],$_REQUEST['checkFlag']);
				}
				//else {
				//	$id = $this->Shop->addAppRequest($method,$_REQUEST['Mobile'],$_REQUEST['Amount'],$_REQUEST['product']);
				//	}

				if(empty($id)){
					//$this->displayWeb(array('status'=>'failure','code'=>'38','description'=>$this->Shop->errors(38)), $format); exit;
				}
			}


			$ret = $this->$method($_REQUEST,$format);
			//$this->displayWeb($ret, $format);
		}catch(Exception $e){
			//$this->displayWeb(array('status'=>'failure','code'=>'30','description'=>$this->Shop->errors(30)), $format); exit;
		}

		$this->autoRender = false;
	}*/



	/*	function displayWeb($msg,$format){
		if($format == 'json'){
		//header('Content-Type: application/json');
		echo  $_GET['root'] .'(['.json_encode($msg).']);';
		//echo $_GET['root'] . '([{"id":"'.$_REQUEST['name'].'","option":"'.$_REQUEST['id'].'"},{"id":"0","option":"Select provider"},{"id":"1","option":"Aircel"},{"id":"2","option":"Airtel"},{"id":"3","option":"BSNL"},{"id":"4","option":"Idea"},{"id":"5","option":"Loop/BPL"},{"id":"6","option":"MTS"},{"id":"7","option":"Reliance CDMA"},{"id":"8","option":"Reliance GSM"},{"id":"9","option":"Tata Docomo"},{"id":"10","option":"Tata Indicom"},{"id":"11","option":"Uninor"},{"id":"12","option":"Videocon"},{"id":"13","option":"Virgin CDMA"},{"id":"14","option":"Virgin GSM"},{"id":"15","option":"Vodafone"}]);';exit;
		//			echo 'root ([{"id":"0","option":"Select provider"},{"id":"1","option":"Aircel"},{"id":"2","option":"Airtel"},{"id":"3","option":"BSNL"},{"id":"4","option":"Idea"},{"id":"5","option":"Loop/BPL"},{"id":"6","option":"MTS"},{"id":"7","option":"Reliance CDMA"},{"id":"8","option":"Reliance GSM"},{"id":"9","option":"Tata Docomo"},{"id":"10","option":"Tata Indicom"},{"id":"11","option":"Uninor"},{"id":"12","option":"Videocon"},{"id":"13","option":"Virgin CDMA"},{"id":"14","option":"Virgin GSM"},{"id":"15","option":"Vodafone"}]);';
		exit;
		}else if("xml"){
		header('Content-Type: application/xml');
		$xml = new XmlWriter();
		$xml->openMemory();
		$xml->startDocument('1.0', 'UTF-8');
		$xml->startElement('root');

		function write(XMLWriter $xml, $msg){
		foreach($msg as $key => $value){
		if(is_array($value)){
		$xml->startElement($key);
		write($xml, $value);
		$xml->endElement();
		continue;
		}
		$xml->writeElement($key, $value);
		}
		}
		write($xml, $msg);
		$xml->endElement();
		echo $xml->outputMemory(true);
		}
		$this->autoRender = false;
		}
		*/

	/*function retailerSales($rMobile)
	{
		$distributorId=$_SESSION['Auth']['id'];
		$date=date('d-m-Y');
		$fdarr = explode("-",$date);
		$fd = $fdarr[2]."-".$fdarr[1]."-".$fdarr[0];
		
		$retailerDetailsResult=$this->Slaves->query("select id,shopname,balance from retailers where mobile='$rMobile'");
		if(empty($retailerDetailsResult))
		{
			echo "Retailer does not exist.";
			exit;
		}
		
		else
		{
			$retailerShopname=$retailerDetailsResult['0']['retailers']['shopname'];
			$retailerBalance=$retailerDetailsResult['0']['retailers']['balance'];
			$retailerId=$retailerDetailsResult['0']['retailers']['id'];
			
			
			$successToday=$this->Slaves->query("SELECT va.amount,r.name,r.shopname,r.id,r.mobile, va.txn_id,  va.status, va.timestamp from vendors_activations va  join retailers r on(va.retailer_id=r.id) where (va.status<>2 and va.status<>3) and va.retailer_id=$retailerId and va.date= '$fd' order by va.id desc");
			$averageResult=$this->Slaves->query("select avg(amts) as total from (select sum(amount) as amts,Date(timestamp) from shop_transactions  where source_id = $retailerId AND type='".RETAILER_ACTIVATION."' group by date order by timestamp desc limit 15) as table1;");
			
			$retailerSetUpPendResult=$this->Slaves->query("select id,confirm_flag from shop_transactions where source_id=$distributorId and target_id=$retailerId and type='".SETUP_FEE."' ");
			$confirm_flag=$retailerSetUpPendResult['0']['shop_transactions']['confirm_flag'];
			$st_id=$retailerSetUpPendResult['0']['shop_transactions']['id'];
			
			if($confirm_flag==1)
				$pendSetUp=0;
			else
			{
				$sstResult=$this->Slaves->query("select sum(collection_amount) as total from salesman_transactions where shop_tran_id=$st_id");
				$sstAmount=$sstResult['0']['0']['total'];
			//	echo "SST amount".$sstAmount;
				$pendSetUp=SETUP_FEE_AMT-$sstAmount;
				
			}
	
			$retailerTopUpPendResult=$this->Slaves->query("select id,amount,confirm_flag from shop_transactions where source_id=$distributorId and target_id=$retailerId and type='".DIST_RETL_BALANCE_TRANSFER."' ");
			$confirm_flag=$retailerTopUpPendResult['0']['shop_transactions']['confirm_flag'];
			$st_id=$retailerTopUpPendResult['0']['shop_transactions']['id'];
			$stAmountForTopUp=$retailerTopUpPendResult['0']['shop_transactions']['amount'];
			
			if(empty($st_id)) //if no top up done so far.	
			{
				//echo "Empty";
				$pendTopUp=0;
			}
			else
			{
				$pendTopUp=0;
					foreach($retailerTopUpPendResult as $rs)
					{
						if($rs['shop_transactions']['confirm_flag']!=1)
						{
							//echo "confirm flag is ! 1";
							$st_id=$rs['shop_transactions']['id'];
							$amount=$rs['shop_transactions']['amount'];
							$sstResultTopUp=$this->Slaves->query("select sum(collection_amount) as total from salesman_transactions where shop_tran_id=$st_id ");
							$sstAmountTopUp=$sstResultTopUp['0']['0']['total'];
							$pendTopUp+=$amount-$sstAmountTopUp;
						}
						else
						{
							$pendTopUp+=0;
						}
					}
			}
			$average=$averageResult['0']['0']['total'];
		//	echo "Average is ".$average;
			$this->set('pendSetUp',$pendSetUp);
			$this->set('pendTopUp',$pendTopUp);
			$this->set('average',$average);
			$this->set('success',$successToday);
			
			$this->set('rMobile',$rMobile);
			$this->set('rShopname',$retailerShopname);
			$this->set('rBalance',$retailerBalance);
		
		}
		
		
	}*/
	
	
/*	function addRetailer(){
		//print_r($_REQUEST); exit;
		$ret = '';
		$this->checkSession();
		//$salesmanMobile=$_SESSION['salesman']['mobile'];//$_SESSION['Auth']['User']['mobile'];
		//$salesmanIdResult=$this->User->query("select id from salesmen where mobile=$salesmanMobile");
		//$salesmanId=$_SESSION['salesman']['id'];

		//create an APIS Controller
		App::import('Controller', 'Shops');
		$ini = new ShopsController;
		$ini->constructClasses();
		
		//$subArea=$_REQUEST['subArea'];
		//$subAreaIdResult=$this->User->query("select id from subarea where name ='$subArea' ");
		//$subAreaId=$_REQUEST['subArea'];
		
		
		$params['mobile'] = $_REQUEST['mobileNumber'];
		$params['salesmanId'] = $_SESSION['salesman']['id'];
		$params['shopname'] = $_REQUEST['Shopname'];
		$params['subArea'] = $_REQUEST['subArea'];
		$params['type']=$_REQUEST['type'];

		$ret= $ini->createRetailerApp($params,'json');

		$this->set('ret',$ret);
		//$this->autoRender = false;
	}


	function login(){
		$err = '';
		if($_SESSION['salesman']['mobile'] != ''){
			$this->redirect(array('controller'=>'salesmen','action' => 'mainMenu'));
		}
		
		if(isset($_REQUEST['mobileNumber']) && !empty($_REQUEST['mobileNumber'])){			
			$mobile=$_REQUEST['mobileNumber'];
			$pwd=$_REQUEST['pwd'];
			$loginResults=$this->User->query("select password,id,dist_id,name from salesmen where mobile='$mobile'");
			$loginResultsPwd = $loginResults['0']['salesmen']['password'];
			 
			if($pwd == $loginResultsPwd && $pwd != ''){
				$_SESSION['salesman']['name'] = $loginResults['0']['salesmen']['name'];
				$_SESSION['salesman']['mobile'] = $mobile;
				$_SESSION['salesman']['id'] = $loginResults['0']['salesmen']['id'];
					
				$info = $this->Shop->getShopDataById($loginResults['0']['salesmen']['dist_id'],DISTRIBUTOR);
				$info['User']['group_id'] = DISTRIBUTOR;
				$info['User']['id'] = $info['user_id'];
				$this->Session->write('Auth',$info);
				$this->redirect(array('controller'=>'salesmen','action' => 'mainMenu'));
			}else{
				$err = "Login Failed. Enter correct login details.";
			}
			//$this->autoRender =false;
		}
		
		$this->set('err',$err);
	}

	function checkLogin()
	{
		$mobile=$_REQUEST['salesmanMobile'];
		$pwd=$_REQUEST['pwd'];

		$loginResults=$this->User->query("select password,id,dist_id from salesmen where mobile=$mobile");
		$loginResultsPwd=$loginResults['0']['salesmen']['password'];

		if($pwd == $loginResultsPwd)
		{
			$_SESSION['salesman']['mobile'] = $mobile;
			$_SESSION['salesman']['id'] = $loginResults['0']['salesmen']['id'];
				
			$info = $this->Shop->getShopDataById($loginResults['0']['salesmen']['dist_id'],DISTRIBUTOR);
			$info['User']['group_id'] = DISTRIBUTOR;
			$info['User']['id'] = $info['user_id'];
			$this->Session->write('Auth',$info);
			echo 'Success';
		}
		else
		{
			echo "Login Failed.Enter correct login details";

		}
		$this->autoRender =false;
		//print_r($_SESSION);
	}

	function createRetailer(){
		$this->checkSession();
		if(isset($_REQUEST['shopname']) && trim($_REQUEST['shopname']) != ''){
			App::import('Controller', 'Shops');
			$ini = new ShopsController;
			$ini->constructClasses();
			
			$params['mobile'] = trim($_REQUEST['mobile']);
			$params['salesmanId'] = trim($_SESSION['salesman']['id']);
			$params['shopname'] = trim($_REQUEST['shopname']);
			$params['subArea'] = trim($_REQUEST['subArea']);
			$params['type']= trim($_REQUEST['type']);
	
			$ret= $ini->createRetailerApp($params,'json');
	
			if(strtolower($ret['status']) == 'success'){
				if($_REQUEST['type'] == '0'){
					$ret = array('status' => 'success','description' => 'Retailer created successfully.','type'=>$_REQUEST['type']);				
				}else{
					$ret = array('status' => 'success','description' => 'Retailer created succcessfully!! Now collect set-up fees.','type'=>$_REQUEST['type'],'mobile'=>$params['mobile']);
				}
			}else{
				$salesmanSubArea=$this->User->query("SELECT sa.name,sa.id from subarea sa where sa.id in (select ss.id from salesmen_subarea ss where ss.salesmen_id=".$_SESSION['salesman']['id'].")");
				$this->set('salesmanArea',$salesmanSubArea);
			}
			
			$this->set('ret',$ret);
		}else{
			$salesmanSubArea=$this->User->query("SELECT sa.name,sa.id from subarea sa where sa.id in (select ss.id from salesmen_subarea ss where ss.salesmen_id=".$_SESSION['salesman']['id'].")");
			$this->set('salesmanArea',$salesmanSubArea);
		}
	}

	function payment($flag,$retMob=null,$type=null,$amount=null){
		$this->checkSession();
		$salesmanId=$_SESSION['salesman']['id'];
		$salesmanMobile=$_SESSION['salesman']['mobile'];

		$salesmanUIdRes = $this->User->query("select id from users where mobile='$salesmanMobile'");
		$salesmanUserId = $salesmanUIdRes['0']['users']['id'];

		$date = Date("Y-m-d H:i:s");
		$STAmount = SETUP_FEE_AMT;
		
		$retDetRes=$this->User->query("select id,shopname from retailers where mobile='$retMob'");
		if(count($retDetRes) < 1) {
			echo "<script>alert('Retailer does not exists'); document.location.href='/salesmen/mainMenu';</script>";
			exit;	
		}else{
			$retShopName=$retDetRes['0']['retailers']['shopname'];
			$retId=$retDetRes['0']['retailers']['id'];
			
			$distributorId=$_SESSION['Auth']['id'];
			
			if(trim($retShopName) == ''){
				$retShopName='empty';		
			}
	
			$this->set('RShop',$retShopName);
			$this->set('RMobile',$retMob);

	
			if(($flag==1 && $type==1)||($flag==3 && $type==0)) //call from createRetailer->'Paid'(1,1);call from collection->'SetUp'(3,0)
			{
				$STU=0; //0->setUp
				$trans_type = SETUP_FEE;
			}
			else if(($flag==2 && $type==1) || ($flag==3 && $type==1)) //call from collection:'TopUp'->(3,1);Call from Top up Screen()-> (2,1)
			{
				$STU=1;//1->TopUp
				$trans_type = DIST_RETL_BALANCE_TRANSFER;
			}
			
			$this->set('STU',$STU);
			
			$stAmountResult=$this->Slaves->query("select id,amount from shop_transactions where source_id=$distributorId and target_id=$retId and type=$trans_type and confirm_flag != 1 order by id");
			$stId=$stAmountResult['0']['shop_transactions']['id'];
			$stAmount=$stAmountResult['0']['shop_transactions']['amount'];
			
			if($flag==1)//from createRetailer() : onli set up fees record picked up
			{
				if($type==1)//Paid -CreateRetailer
				{
					$typeMD='Set Up';
					$sstTotalSetUpAmountResult=$this->Slaves->query("select sum(collection_amount) as total,collection_date,created from salesman_transactions where shop_tran_id=$stId and payment_type=1 ");
					$chkSetUpAmountInSST=$sstTotalSetUpAmountResult['0']['0']['total'];
	
					$pendingSetUp=$stAmount-$chkSetUpAmountInSST;					
					$this->set('pending',$pendingSetUp);
				}
			}//end of create retailer
	
	
			if($flag==3 && $type==0) //collection of set-up 
			{					
				$typeMD='Set Up';
				if(empty($stAmountResult))
				{
					$sstObj = ClassRegistry::init('ShopTransaction');
					$this->data['ShopTransaction']['source_id'] = $distributorId;
					$this->data['ShopTransaction']['target_id'] = $retId;
					$this->data['ShopTransaction']['user_id'] = $salesmanUserId;
					$this->data['ShopTransaction']['amount'] = $amount;
					$this->data['ShopTransaction']['confirm_flag'] = 0;
					$this->data['ShopTransaction']['type'] = SETUP_FEE;
					$this->data['ShopTransaction']['timestamp'] = $date;
					
					$sstObj->create();
					$sstObj->save($this->data);
					$stId = $sstObj->id;				
					//$this->User->query("insert into shop_transactions(ref1_id,ref2_id,user_id,amount,confirm_flag,type,timestamp) values($distributorId,$retId,$salesmanUserId,$amount,0,'".SETUP_FEE."','$date')");
					//$stId=mysql_insert_id();
					$stAmount=$amount;
				}

				$sstTotalSetUpAmountResult=$this->Slaves->query("select sum(collection_amount) as total,collection_date,created from salesman_transactions where shop_tran_id=$stId and payment_type=1 ");
				$chkSetUpAmountInSST=$sstTotalSetUpAmountResult['0']['0']['total'];

				$pendingSetUp=$stAmount-$chkSetUpAmountInSST;					
				$this->set('pending',$pendingSetUp);
			}
	
			
	
			if(($flag==2 && $type==1) || ($flag==3 && $type==1))//TOP UP 3,1=> collection screen, top-up selected, 2,1=>top-up screen, collect selected
			{
				if(empty($stId)) //if no entry in st table for set up fee,make a new entry
				{
					$this->set('pending',0);
					
				}//st empty end
					
				else
				{
					$pend=0;
					foreach($stAmountResult as $pST)
					{
						$sstRsId=$pST['shop_transactions']['id'];
						$pendST=$pST['shop_transactions']['amount'];
						$sstTotalTopUpAmountResult=$this->Slaves->query("select sum(collection_amount) as total from salesman_transactions where shop_tran_id=$sstRsId and payment_type=".TYPE_TOPUP);
						$pendSST=$sstTotalTopUpAmountResult['0']['0']['total'];	
						$pend=$pend + ($pendST-$pendSST);
					}
					$typeMD = 'Top up';
					$this->set('pending',$pend);
					$this->set('topUpAmount',$amount);
				}
				
			}

			$this->set('typeMD',$typeMD);
			$this->set('Flag',$flag);
			$this->set('type',$type);
		}
	}


	function crr()
	{
		$rMobile=$_REQUEST['Mobile'];
		$rShopName=$_REQUEST['Shopname'];
		$rSubArea=$_REQUEST['subArea'];
		$rType=$_REQUEST['type'];

	}

	function amountTransfer($params,$format){
		$this->checkSession();
		App::import('Controller', 'Shops');
		$ini = new ShopsController;
		$ini->constructClasses();
		$data = $ini->amountTransfer($params,$this->Session->read('Auth'));
		return $data;
	}*/

	/*function topupAmount(){
		$this->checkSession();
		$mobile=$_REQUEST['mobile'];
		$amount=$_REQUEST['amount'];
		
		if(isset($_REQUEST['collectAmount']) && $_REQUEST['collectAmount'] != '')
			$toCollect=1;
		else
			$toCollect=0;

		$salesman = $this->User->query("SELECT id,balance,tran_limit,mobile,name FROM salesmen where id = ".$_SESSION['salesman']['id']);
		$data = $this->User->query("SELECT * FROM retailers WHERE mobile = '".$mobile."'");
		
		$flag = 0;
		if(empty($data)){
			echo "Retailer does not exist.";
			$flag = 0;
		}

		if(!empty($data)){
			if($salesman['0']['salesmen']['balance'] >= $amount){
				$params['amount'] = $amount;
				$params['retailer'] = $data['0']['retailers']['id'];
				$params['salesmanId'] = $salesman['0']['salesmen']['id'];
				$params['salesmanName'] = $salesman['0']['salesmen']['name'];
				$retailerShopName=$data['0']['retailers']['shopname'];

			 	$ret=$this->amountTransfer($params,'json');

				if($ret['status'] == 'success'){
					$flag = 1;
					$message = "Amount of Rs. $amount transferred to retailer $mobile successfully. Retailer Shop Name ". $retailerShopName;
					
					//$this->User->query("UPDATE salesmen SET balance = balance - $amount WHERE id=".$salesman['0']['salesmen']['id']);
					//$message = "Amount of Rs. $amount transferred to retailer $mobile successfully. Retailer Shop Name ". $retailerShopName;
					//$sms = "Amount Rs $amount transferred to retailer $mobile successfully. Retailer Shop Name $retailerShopName. Retailer balance is now Rs." . $data['0']['retailers']['balance'];
					//$this->General->sendMessage($salesman['0']['salesmen']['mobile'],$sms,'shops');
				}else{
					$flag = 0;
					$message="Not successful";
					$body = "Salesman: $loggedUserId<br/>";
					$body .= "Retailer: $mobile (".$data['0']['retailers']['name'].")<br/>";
					$body .= "Retailer Shop Name: ".$retailerShopName;
					$body .= "Amount trying: $amount<br/>";

					//$this->General->sendMails('Pay1: Salesman Cannot Transfer Balance',$body,array('chirutha@pay1.in','vinit@pay1.in'));
				}
			}else{
				$message="Your balance transfer limit of Rs.".$salesman['0']['salesmen']['tran_limit']." is exceeded. Kindly contact your distributor.";
				$flag = 0;
			}
		}
		
		$this->set('msg',$message);
		$this->set('flag',$flag);		
		$this->set('amount',$amount);
		$this->set('mobile',$mobile);
		$this->set('toCollect',$toCollect);		
	}*/

	/*function collectPayment($params=null){
		if($params != null)$_REQUEST = $params;
		//print_r($_REQUEST); exit;
		$this->checkSession();
		$date=Date("Y-m-d H:i:s");
		$distributorId=$_SESSION['Auth']['id'];
		$rMobile=$_REQUEST['rMobile'];
		$type=$_REQUEST['type'];
		$flag=$_REQUEST['flag'];		
		$amount=$_REQUEST['amount'];
		$payMode=$_REQUEST['mode'];		
		$chequeNo=$_REQUEST['chequeNumber'];
		$billBookNumber=$_REQUEST['billBookNo'];
		
		$payModeInt = $payMode; 
		if(strtolower(trim($type))=='top up'){
			$typeST=2;
			$trans_type = DIST_RETL_BALANCE_TRANSFER;	
		}else{
			$typeST=1;
			$trans_type = SETUP_FEE;
		}
		
		$retailerIdResult = $this->Slaves->query("select id,shopname from retailers where mobile='$rMobile'");
		$retailerId = $retailerIdResult['0']['retailers']['id'];
		
		$sMM=$_SESSION['salesman']['mobile'];
		$smId=$_SESSION['salesman']['id'];

		$salesmanUserIdResult=$this->Slaves->query("select id from users where mobile='$sMM'");
		$salesmanUserId=$salesmanUserIdResult['0']['users']['id'];		
		$chkAmtShopTransResult=$this->Slaves->query("select shop_transactions.id,shop_transactions.amount,sum(salesman_transactions.collection_amount) as col_amount from shop_transactions left join salesman_transactions ON (salesman_transactions.shop_tran_id = shop_transactions.id) where shop_transactions.source_id=$distributorId and shop_transactions.target_id=$retailerId and shop_transactions.type=$trans_type and shop_transactions.confirm_flag =0 group by salesman_transactions.shop_tran_id");
		$chkAmtShopTransId=$chkAmtShopTransResult['0']['shop_transactions']['id'];
		
		
		$chkAmtShopTransAmount=$chkAmtShopTransResult['0']['shop_transactions']['amount'];
                $MsgTemplate = $this->General->LoadApiBalance();

		if(($flag==1 && $typeST==1)||($flag==3 && $typeST==1)) //FOR SET UP FROM createRetailer
		{
			$this->User->query("insert into salesman_transactions(shop_tran_id,salesman,payment_mode,payment_type,collection_amount,billbook_number,cheque_number,collection_date,created) values($chkAmtShopTransId,$smId,$payModeInt,$typeST,$amount,'$billBookNumber','$chequeNo','$date','$date')");
			$sstTotalSetUpAmountResult=$this->User->query("select sum(collection_amount) as total,collection_date,created from salesman_transactions where shop_tran_id=$chkAmtShopTransId and payment_type=1 ");
			$chkSetUpAmountInSST=$sstTotalSetUpAmountResult['0']['0']['total'];
			$totalPending=SETUP_FEE_AMT - $chkSetUpAmountInSST;
			if($totalPending < 0) $totalPending = 0;
                        
//			$msg = "Dear Retailer, We have successfully collected your setup fee of Rs $amount.";
                        $paramdata['AMOUNT'] = $amount;
                        $content =  $MsgTemplate['Retailer_CollectPayment_MSG'];
                        $msg = $this->General->ReplaceMultiWord($paramdata,$content);
                        
			if($chkSetUpAmountInSST==SETUP_FEE_AMT)	//check if total amt in SST=amt in st, then confirm flag=1 in shop_transaction
			{
				$this->User->query("update shop_transactions set confirm_flag=1 where id=$chkAmtShopTransId");
				$this->User->query("update retailers set retailer_type=2, modified = '".date('Y-m-d H:i:s')."' where id=$retailerId");
			}else {
				$this->User->query("update retailers set retailer_type=1, modified = '".date('Y-m-d H:i:s')."' where id=$retailerId");
//				$msg = "Dear Retailer, We have successfully collected your setup fee of Rs $amount.";
//                                $msg .= "\nPlease pay remaining Rs $totalPending asap to get our best services";
                                $paramdata['TOTALPANDING'] = $totalPending;
                                $content =  $MsgTemplate['Retailer_CollectPayment_Pending_MSG'];
                                $msg = $this->General->ReplaceMultiWord($paramdata,$content);
			}
			$this->User->query("update salesmen set setup_pending=setup_pending+$amount where id=$smId");
			$this->General->sendMails("Set Up Fee Collected","Retailer: $rMobile(".$retailerIdResult['0']['retailers']['shopname']."), Amount Paid: $amount, Pending amount: ".$totalPending."<br/>Salesman: ".$_SESSION['salesman']['name']);
			
			$this->General->sendMessage($rMobile,$msg,'notify');
		}
			
		if(($flag==2 && $typeST==2)||($flag==3 && $typeST==2)) //flag=2 & top up
		{
			$remainingAmt=0;			
			$remainingAmount=$amount;
			foreach($chkAmtShopTransResult as $st){
				
				$collected_amt = $st['0']['col_amount'];
				$thisAmt=$st['shop_transactions']['amount'] - $collected_amt;
				$thisStId=$st['shop_transactions']['id'];
								
				if($thisAmt<=$remainingAmount){
					$this->User->query("insert into salesman_transactions(shop_tran_id,salesman,payment_mode,payment_type,collection_amount,billbook_number,cheque_number,collection_date,created) values($thisStId,$smId,$payModeInt,$typeST,$thisAmt,'$billBookNumber','$chequeNo','$date','$date')");
					$this->User->query("update shop_transactions set confirm_flag=1 where id=$thisStId");

					if($thisAmt == $remainingAmount)
					break;
				}else{
					$this->User->query("insert into salesman_transactions(shop_tran_id,salesman,payment_mode,payment_type,collection_amount,billbook_number,cheque_number,collection_date,created)  values($thisStId,$smId,$payModeInt,$typeST,$remainingAmount,'$billBookNumber','$chequeNo','$date','$date')");					
				}
					
				$remainingAmount=$remainingAmount-$thisAmt;
				//echo "Remaining amount ".$remainingAmount;
				if($remainingAmount<=0)
				break;
					
			}
		}//end of if flag loop

		//echo "success";
		$this->set('amount',$_REQUEST['amount']);		
	}


	function mainMenu()
	{
		$this->checkSession();
	}

	function topup($rMobile=null)
	{
		$this->checkSession();
		if(isset($rMobile))
		{
			$retailerMobile=$_REQUEST['$rMobile'];
			$this->set('RMobile',$retailerMobile);
			
		}
		
	}

	function collection()
	{
		$this->checkSession();
	}

	function help()
	{
		$this->checkSession();
	}
	
	function retailerBalance()
	{
		$this->checkSession();
	}*/

		
	
	function logout(){
		session_destroy();
		$_SESSION = null;
		$this->checkSession();
	}
	
	
	function mapSalesman(){
		$sid = $_REQUEST['sid'];
		$rid = $_REQUEST['rid'];
		if($sid != 0){
			$this->Retailer->query("UPDATE retailers SET maint_salesman = $sid, modified = '".date('Y-m-d H:i:s')."' WHERE id = $rid");
		}
		$this->autoRender = false;
	}
	
	function blockRetailer(){
		$rid = $_REQUEST['rid'];
		$flag = $_REQUEST['flag'];
		
		$this->Retailer->query("UPDATE retailers SET block_flag = $flag, modified = '".date('Y-m-d H:i:s')."' WHERE id = $rid AND block_flag != $flag");
		
		$mobile = $this->Retailer->query("SELECT mobile,shopname FROM retailers WHERE id = $rid");
		$MsgTemplate = $this->General->LoadApiBalance(); 		
		if($flag == 1){
			//$this->General->sendMails("Pay1 Retailer partially blocked","Retailer: ".$mobile['0']['retailers']['shopname'] . "(" . $mobile['0']['retailers']['mobile'] . ")",array('ashish@pay1.in','vinit@pay1.in'));	
//			$message = "Dear Retailer,
//
//Thank you for trying Pay1 services.
//Aapka Pay1 trial khatm ho gaya hai.";
                        
                        $message = $MsgTemplate['Retailer_Block_MSG'];
                	$this->General->sendMessage($mobile['0']['retailers']['mobile'],$message,'notify');
		}
		else if($flag == 2){
			//$this->General->sendMails("Pay1 Retailer fully blocked","Retailer: ".$mobile['0']['retailers']['shopname'] . "(" . $mobile['0']['retailers']['mobile'] . ")",array('ashish@pay1.in','vinit@pay1.in'));
		}
		else if($flag == 0){
			//$this->General->sendMails("Pay1 Retailer Unblocked","Retailer: ".$mobile['0']['retailers']['shopname'] . "(" . $mobile['0']['retailers']['mobile'] . ")",array('ashish@pay1.in','vinit@pay1.in'));
//			$message = "Dear Retailer,
//
//Thank you for choosing Pay1 services.
//You can now do transactions with us";
                        
                        $message = $MsgTemplate['Retailer_UnBlock_MSG'];
                	$this->General->sendMessage($mobile['0']['retailers']['mobile'],$message,'notify');
		}
		echo "success";
		$this->autoRender = false;
	}
	
	function rentalRetailer(){
		$this->autoRender = false;
		$rid  = $_REQUEST['rid'];
		$flag = $_REQUEST['flag'];
		$mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : "";
		 
		if($flag == 0){
			$to_str = "Kit";
			$from_str = "Rental";
			$retailerInfo = $this->Retailer->query("SELECT * FROM `retailers`,`distributors`
                                                 WHERE `retailers`.id = $rid AND `distributors`.id = `retailers`.`parent_id`");

			if($retailerInfo[0]['distributors']['kits'] == 0){
				echo "Distributor of this retailer have 0 kits left.";
				exit;
			}
			$this->Retailer->query("UPDATE retailers SET rental_flag = $flag, modified = '".date('Y-m-d H:i:s')."' WHERE id = $rid");
			$this->Retailer->query("UPDATE `shops`.`distributors` SET `kits` = `kits` - 1 WHERE `distributors`.`id` =".$retailerInfo[0]['distributors']['id']);
		}else{
			$this->Retailer->query("UPDATE retailers SET rental_flag = $flag, modified = '".date('Y-m-d H:i:s')."' WHERE id = $rid");
			$to_str = "Rental";
			$from_str = "Kit";
		}
		$mail_subject = "Retailer shifted to $to_str";
		$mail_body = "Retailer ID : $rid </br> Retailer Mobile : $mobile </br> Shifted from $from_str to $to_str .";
		$this->General->sendMails($mail_subject, $mail_body,array('info@pay1.in'),'mail');
		echo "success";
	}
	
	
	function blockSalesman(){
		$rid = $_REQUEST['rid'];
		$flag = $_REQUEST['flag'];
		
		$this->Retailer->query("UPDATE salesmen SET block_flag = $flag WHERE id = $rid AND block_flag != $flag");
		
		echo "success";
		$this->autoRender = false;
	}
	
	function test(){
		echo "1";
		$this->autoRender = false;
	}
}