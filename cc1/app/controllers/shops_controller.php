<?php
use Predis\Cluster\Distribution\DistributionStrategyInterface;

class ShopsController extends AppController {
	var $name = 'Shops';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv','NumberToWord');
	var $components = array('RequestHandler','Shop','Invoice','Documentmanagement','Scheme','General','Serviceintegration');
	var $uses = array('SalesmanTransaction','Salesman','Retailer','Distributor','MasterDistributor','SuperDistributor','ModemRequestLog','User','ShopTransaction','Rm','Slaves');

	function beforeFilter() {
		set_time_limit(0);
		ini_set("memory_limit", "512M");
		parent::beforeFilter();
        error_reporting(0);

		$this->Auth->allow('*');

        //$this->Auth->allowedActions = array('retFilter','allDistributor','addInvestedAmount','investmentReport','earningReport','kitsTransfer','transferKits','backKitsTransfer','graphRetailer','pullback','addSalesmanCollection','salesmanTran','backSetup','addSetUpFee','formSetUpFee','backSalesman','createSalesman','formSalesman','saleReport','approve','lastTransactions','createCommissionTemplate','createRetailerApp','cronUpdateBalanceLFRS','printCreditDebitNote','backCreditDebit','createNote','getCreditDebitNotes','createCreditDebitNotes','retailerProdActivation','products','index','initializeOpeningBalance','generateInvoice','logout','test','setSignature','saveSignature','topupReceipts','printRequest','script','issue','backReceipt','issueReceipt','printReceipt','printInvoice','retailerListing','PNRListing');

        if($this->Session->read('Auth.User.passflag') == 0 && $this->Session->check('Auth.User') &&  $this->here != "/shops/changePassword" && $this->here != "/" && $this->here != "/shops/logout"){
           $this->redirect(array('controller' => 'shops', 'action' => 'changePassword'));
        }
		$states = $this->Slaves->query("SELECT id,name FROM locator_state WHERE toshow = 1 ORDER BY name asc");
		$cities = $this->Slaves->query("SELECT id,name FROM locator_city WHERE state_id = ". $states['0']['locator_state']['id']." AND toshow = 1 ORDER BY name asc");
        $bankDetails = $this->Shop->getBanks();
        $this->set('bankDetails',$bankDetails);

		$this->set('objShop',$this->Shop);
		if($this->Session->check('Auth.User')){
			$info = $this->Shop->getShopDataById($this->Session->read('Auth.id'),$this->Session->read('Auth.User.group_id'));
			//$info = $this->Shop->getShopData($this->Session->read('Auth.User.id'),$this->Session->read('Auth.User.group_id'));
			$this->info = $info;
			$this->set('info',$info);
		}
		if($this->Session->read('Auth.User.group_id') == ADMIN){
			//$distributors = $this->Distributor->find('all',array('conditions' => array('parent_id' => $this->info['id']), 'order' => 'name asc'));
			$this->MasterDistributor->recursive = -1;
			$s_dists = $this->MasterDistributor->find('all', array(
			'fields' => array('MasterDistributor.*', 'slabs.name','users.mobile','users.balance','users.opening_balance', 'sum(shop_transactions.amount) as xfer'),
			'joins' => array(
			array(
							'table' => 'slabs',
							'type' => 'inner',
							'conditions'=> array('MasterDistributor.slab_id = slabs.id')
			),
			array(
							'table' => 'users',
							'type' => 'inner',
							'conditions'=> array('MasterDistributor.user_id = users.id')
			),
			array(
							'table' => 'shop_transactions',
							'type' => 'left',
							'conditions'=> array('MasterDistributor.id = shop_transactions.target_id','shop_transactions.date = "'.date('Y-m-d').'"', 'shop_transactions.type = 0', 'shop_transactions.confirm_flag != 1')
			)

			),
			'order' => 'MasterDistributor.company asc',
			'group'	=> 'MasterDistributor.id'
			)
			);

                        $master_distributor = array();
                        foreach($s_dists as $sds) {
                                $sds['MasterDistributor']['balance'] = $sds['users']['balance'];
                                $sds['MasterDistributor']['opening_balance'] = $sds['users']['opening_balance'];
                                $master_distributor[] = $sds;
                        }

			$slabs = $this->Slaves->query("SELECT * FROM slabs as Slab WHERE active_flag = 1 AND group_id = " . RETAILER);

			$this->set('slabs',$slabs);
			$this->set('master_distributor',$master_distributor);
			$this->set('records',$master_distributor);
			$this->set('record_without_sd',$master_distributor);
			$this->set('modelName','MasterDistributor');			
			$this->sds = $master_distributor;
		}
		else if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR){
			//$distributors = $this->Distributor->find('all',array('conditions' => array('parent_id' => $this->info['id']), 'order' => 'name asc'));
			$this->Distributor->recursive = -1;
			$distributors = $this->Distributor->find('all', array(
					'fields' => array('Distributor.*', 'slabs.name', 'users.mobile', 'users.balance', 'users.opening_balance', 'rm.name','super_distributors.user_id'),
					'conditions' => array('Distributor.toshow' => 1),
					'joins' => array(
							array(
									'table' => 'slabs',
									'type' => 'inner',
									'conditions' => array('Distributor.slab_id = slabs.id')
							),
							array(
									'table' => 'users',
									'type' => 'inner',
									'conditions' => array('Distributor.user_id = users.id')
							),
							array(
									'table' => 'rm',
									'type' => 'left',
									'conditions' => array('Distributor.rm_id = rm.id')
							),
							array(
									'table' => 'super_distributors',
									'type' => 'left',
									'conditions' => array('Distributor.sd_id = super_distributors.id')
							)
					),
					'order' => 'Distributor.active_flag desc,Distributor.company asc',
					'group' => 'Distributor.id'
					)
			);
            /** IMP DATA ADDED : START**/

            $dist_ids = array_map(function($element){
                return $element['Distributor']['id'];
            },$distributors);


            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);

            $dist_imp_label_map = array(
                'pan_number' => 'pan_no',
                'company' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id'
            );
            foreach ($distributors as $key => $distributor) {
                foreach ($distributor['Distributor'] as $dist_label_key => $value) {
                    $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
                    if( array_key_exists($dist_label_key_mapped,$imp_data[$distributor['Distributor']['id']]['imp']) ){
                        $distributors[$key]['Distributor'][$dist_label_key] = $imp_data[$distributor['Distributor']['id']]['imp'][$dist_label_key_mapped];
                    }
                }
            }
            /** IMP DATA ADDED : END**/

			$distarray = array();
//			$query = $this->Slaves->query("SELECT  sum(`shop_transactions`.`amount`) as xfer,target_id as distId FROM `shop_transactions` where  `shop_transactions`.`date` = '".date('Y-m-d')."' AND `shop_transactions`.`type` = 1 AND `shop_transactions`.`confirm_flag` != 1 and shop_transactions.source_id = ".$this->info['id']."  group by distId");
			$query = $this->Slaves->query("SELECT sum(topup_buy) as xfer,distributors.id as distId FROM users_logs JOIN distributors ON (users_logs.user_id = distributors.user_id) JOIN master_distributors ON (users_logs.parent_user_id = master_distributors.user_id) WHERE users_logs.date = '".date('Y-m-d')."' AND master_distributors.id = ".$this->info['id']." GROUP BY distId");                                                        
			foreach ($query as $key){
				$distarray[$key['distributors']['distId']] = $key[0]['xfer'];
			}

			$record =  array();
			foreach ($distributors as $dis) {

				if(!empty($dis['Distributor']['sd_id'])){
					$sd_user_id = $dis['super_distributors']['user_id'];
					$sd_imp_data = $this->Shop->getUserLabelData($sd_user_id);
					 $dis['Distributor']['sd_company_name'] = $sd_imp_data[$sd_user_id]['imp']['shop_est_name'];
				}

                                $dis['Distributor']['balance'] = $dis['users']['balance'];
                                $dis['Distributor']['opening_balance'] = $dis['users']['opening_balance'];
				$record[$dis['Distributor']['id']] = $dis;
				if(!empty($distarray[$dis['Distributor']['id']])){
				$record[$dis['Distributor']['id']][]['xfer'] = $distarray[$dis['Distributor']['id']];
				} else {
					$record[$dis['Distributor']['id']][]['xfer'] = 0;
				}
			}
			$distRecords = $record;
			/*$record  = $this->General->array_sort_by_column($record,0);
			$distRecords =  array();
			foreach($record as $val){
				$distRecords[$val[0]['Distributor']['id']] = $val[0];
			}*/

			/**Distributor Without SuperDistributor***/

			$record_without_sd =  array();


				foreach ($distributors as $dis) {
					if($dis['Distributor']['sd_id'] == null){
						$dis['Distributor']['balance'] = $dis['users']['balance'];
		                $dis['Distributor']['opening_balance'] = $dis['users']['opening_balance'];
						$record_without_sd[$dis['Distributor']['id']] = $dis;
						if(!empty($distarray[$dis['Distributor']['id']])){
							$record_without_sd[$dis['Distributor']['id']][]['xfer'] = $distarray[$dis['Distributor']['id']];
						} else {
							$record_without_sd[$dis['Distributor']['id']][]['xfer'] = 0;
						}
					}
	                
				}

			/***End Distributor Without SuperDistributor**/
			

			if($this->info['id'] == 3){
				$slabs = $this->Slaves->query("SELECT * FROM slabs as Slab WHERE active_flag = 1 AND group_id = " . RETAILER);
			}
			else {
				$slabs = $this->Slaves->query("SELECT * FROM slabs as Slab WHERE Slab.id = " . $this->info['slab_id']);
			}
			$this->set('slabs',$slabs);
			$this->set('distributors',$distRecords);
			$this->set('record_without_sd',$record_without_sd);
			$this->set('records',$distRecords);
			$this->set('modelName','Distributor');
			$this->ds = $distRecords;

		}
		else if($this->Session->read('Auth.User.group_id') == SUPER_DISTRIBUTOR){

			

			$this->Distributor->recursive = -1;
			$distributors = $this->Distributor->find('all', array(
					'fields' => array('Distributor.*', 'slabs.name', 'users.mobile', 'users.balance', 'users.opening_balance', 'rm.name'),
					'conditions' => array('Distributor.toshow' => 1,'super_distributors.user_id' => $this->Session->read('Auth.User.id')),
					'joins' => array(
							array(
									'table' => 'super_distributors',
									'type' => 'inner',
									'conditions' => array('Distributor.sd_id = super_distributors.id')
							),
							array(
									'table' => 'slabs',
									'type' => 'inner',
									'conditions' => array('Distributor.slab_id = slabs.id')
							),
							array(
									'table' => 'users',
									'type' => 'inner',
									'conditions' => array('Distributor.user_id = users.id')
							),
							array(
									'table' => 'rm',
									'type' => 'left',
									'conditions' => array('Distributor.rm_id = rm.id')
							)
					),
					'order' => 'Distributor.active_flag desc,Distributor.company asc',
					'group' => 'Distributor.id'
					)
			);
            /** IMP DATA ADDED : START**/

            $dist_ids = array_map(function($element){
                return $element['Distributor']['id'];
            },$distributors);


            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);

            $dist_imp_label_map = array(
                'pan_number' => 'pan_no',
                'company' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id'
            );
            foreach ($distributors as $key => $distributor) {
                foreach ($distributor['Distributor'] as $dist_label_key => $value) {
                    $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
                    if( array_key_exists($dist_label_key_mapped,$imp_data[$distributor['Distributor']['id']]['imp']) ){
                        $distributors[$key]['Distributor'][$dist_label_key] = $imp_data[$distributor['Distributor']['id']]['imp'][$dist_label_key_mapped];
                    }
                }
            }
            /** IMP DATA ADDED : END**/

			$distarray = array();
			$query = $this->Slaves->query("SELECT sum(amount) as xfer,distributors.id as distId FROM shop_transactions as users_logs JOIN distributors ON (users_logs.target_id = distributors.id) JOIN super_distributors ON (users_logs.source_id = super_distributors.id) WHERE users_logs.confirm_flag = 0 AND users_logs.type = ".SDIST_DIST_BALANCE_TRANSFER." AND users_logs.date = '".date('Y-m-d')."' AND super_distributors.id = ".$this->info['id']." GROUP BY distId");                                                        
			foreach ($query as $key){
				$distarray[$key['distributors']['distId']] = $key[0]['xfer'];
			}
			$record =  array();
			foreach ($distributors as $dis) {
                                $dis['Distributor']['balance'] = $dis['users']['balance'];
                                $dis['Distributor']['opening_balance'] = $dis['users']['opening_balance'];
				$record[$dis['Distributor']['id']] = $dis;
				if(!empty($distarray[$dis['Distributor']['id']])){
					$record[$dis['Distributor']['id']][]['xfer'] = $distarray[$dis['Distributor']['id']];
				} else {
					$record[$dis['Distributor']['id']][]['xfer'] = 0;
				}
			}
			$distRecords = $record;
			

			if($this->info['id'] == 3){
				$slabs = $this->Slaves->query("SELECT * FROM slabs as Slab WHERE active_flag = 1 AND group_id = " . RETAILER);
			}
			else {
				$slabs = $this->Slaves->query("SELECT * FROM slabs as Slab WHERE Slab.id = " . $this->info['slab_id']);
			}

			$this->set('slabs',$slabs);
			$this->set('distributors',$distRecords);
			$this->set('records',$distRecords);
			$this->set('record_without_sd',$distRecords);
			$this->set('modelName','Distributor');
			$this->ds = $distRecords;


		}
		else if($this->Session->read('Auth.User.group_id') == DISTRIBUTOR){

			

			$retailers = $this->General->getRetailerList($this->info['id']);
                        if($this->info['id'] == 1){
				$slabs = $this->Slaves->query("SELECT * FROM slabs as Slab WHERE active_flag = 1 AND group_id = " . RETAILER);
			}
			else {
                $slabs = $this->Slaves->query("SELECT * FROM slabs as Slab WHERE Slab.id = " . $this->info['slab_id']);
			}


			$this->set('slabs',$slabs);
			$this->set('retailers',$retailers);
			$this->set('records',$retailers);
			$this->set('record_without_sd',$retailers);
			$this->set('modelName','Retailer');

			$areas = $this->Slaves->query("SELECT id,name FROM locator_area WHERE city_id = ". $cities['0']['locator_city']['id']." AND toshow = 1 ORDER BY name asc");
			$this->set('areas',$areas);
			$this->retailers = $retailers;
			//$incentive_scheme = $this->Scheme->getScheme($this->info['id']);
			//$incentive_scheme = $incentive_scheme[$this->info['id']];
			//$this->set('scheme',$incentive_scheme);
		}
                else if($this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER){
			$this->Distributor->recursive = -1;
			$distributors = $this->Distributor->find('all', array(
			'fields' => array('Distributor.*', 'slabs.name','users.mobile','users.balance','users.opening_balance','rm.name', 'sum(shop_transactions.amount) as xfer'),
			'conditions' => array('Distributor.rm_id' => $this->info['id'],'Distributor.toshow' => 1,'Distributor.active_flag'=>1),
			'joins' => array(
			array(
							'table' => 'slabs',
							'type' => 'inner',
							'conditions'=> array('Distributor.slab_id = slabs.id')
			),
			array(
							'table' => 'users',
							'type' => 'inner',
							'conditions'=> array('Distributor.user_id = users.id')
			),
			array(
								'table' => 'rm',
								'type' => 'left',
								'conditions'=> array('Distributor.rm_id = rm.id')
			),
			array(
							'table' => 'shop_transactions',
							'type' => 'left',
							'conditions'=> array('Distributor.id = shop_transactions.target_id','shop_transactions.date = "'.date('Y-m-d').'"', 'shop_transactions.type = 1', 'shop_transactions.confirm_flag != 1')
			)

			),
			'order' => 'Distributor.company asc',
			'group'	=> 'Distributor.id'
			)
        );


        /** IMP DATA ADDED : START**/

        $dist_ids = array_map(function($element){
            return $element['Distributor']['id'];
        },$distributors);

        $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);

        $dist_imp_label_map = array(
            'pan_number' => 'pan_no',
            'company' => 'shop_est_name',
            'alternate_number' => 'alternate_mobile_no',
            'email' => 'email_id'
        );
        foreach ($distributors as $key => $distributor) {
            foreach ($distributor['Distributor'] as $dist_label_key => $value) {
                $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
                if( array_key_exists($dist_label_key_mapped,$imp_data[$distributor['Distributor']['id']]['imp']) ){
                    $distributors[$key]['Distributor'][$dist_label_key] = $imp_data[$distributor['Distributor']['id']]['imp'][$dist_label_key_mapped];
                }
            }
        }
        /** IMP DATA ADDED : END**/



			/*if($this->info['id'] == 3){
				$slabs = $this->Distributor->query("SELECT * FROM slabs as Slab WHERE group_id = " . RETAILER);
				}
				else {

				$slabs = $this->Distributor->query("SELECT * FROM slabs as Slab WHERE Slab.id = " . $this->info['slab_id']);
				}*/
			//$this->set('slabs',$slabs);
			$this->set('distributors',$distributors);// this var is used in retailers_list view
			$this->set('records',$distributors);
			$this->set('modelName','Distributor');
			$this->ds = $distributors;// this var is used in sale reports view







		} else if($this->Session->read('Auth.User.group_id')){

			$this->Distributor->recursive = -1;
			$distributors = $this->Distributor->find('all', array(
					'fields' => array('Distributor.*', 'slabs.name', 'users.mobile', 'rm.name'),
					'conditions' => array('Distributor.parent_id' => '3', 'Distributor.toshow' => 1),
					'joins' => array(
							array(
									'table' => 'slabs',
									'type' => 'inner',
									'conditions' => array('Distributor.slab_id = slabs.id')
							),
							array(
									'table' => 'users',
									'type' => 'inner',
									'conditions' => array('Distributor.user_id = users.id')
							),
							array(
									'table' => 'rm',
									'type' => 'left',
									'conditions' => array('Distributor.rm_id = rm.id')
							)
					),
					'order' => 'Distributor.active_flag desc,Distributor.company asc',
					'group' => 'Distributor.id'
					)
                );

                /** IMP DATA ADDED : START**/
                $dist_ids = array_map(function($element){
                    return $element['Distributor']['id'];
                },$distributors);

                $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);

                $dist_imp_label_map = array(
                    'pan_number' => 'pan_no',
                    'company' => 'shop_est_name',
                    'alternate_number' => 'alternate_mobile_no',
                    'email' => 'email_id'
                );
                foreach ($distributors as $key => $distributor) {
                    foreach ($distributor['Distributor'] as $dist_label_key => $value) {
                        $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
                        if( array_key_exists($dist_label_key_mapped,$imp_data[$distributor['Distributor']['id']]['imp']) ){
                            $distributors[$key]['Distributor'][$dist_label_key] = $imp_data[$distributor['Distributor']['id']]['imp'][$dist_label_key_mapped];
                        }
                    }
                }
                /** IMP DATA ADDED : END**/

			$distarray = array();
			$query = $this->Slaves->query("SELECT  sum(`shop_transactions`.`amount`) as xfer,target_id as distId FROM `shop_transactions` where  `shop_transactions`.`date` = '".date('Y-m-d')."' AND `shop_transactions`.`type` = 1 AND `shop_transactions`.`confirm_flag` != 1 and shop_transactions.source_id = '3'  group by distId");
                        foreach ($query as $key){
				$distarray[$key['shop_transactions']['distId']] = $key[0]['xfer'];
			}
			$record =  array();
			foreach ($distributors as $dis) {
				$record[$dis['Distributor']['id']] = $dis;
				if(!empty($distarray[$dis['Distributor']['id']])){
				$record[$dis['Distributor']['id']][]['xfer'] = $distarray[$dis['Distributor']['id']];
				} else {
					$record[$dis['Distributor']['id']][]['xfer'] = 0;
				}
			}
			$distRecords = $record;
			/*$record  = $this->General->array_sort_by_column($record,0);
			$distRecords =  array();
			foreach($record as $val){
				$distRecords[$val[0]['Distributor']['id']] = $val[0];
			}*/

			//if($this->info['id'] == 3){
//				$slabs = $this->Slaves->query("SELECT * FROM slabs as Slab WHERE active_flag = 1 AND group_id = " . RETAILER);
			//}
			//else {
				$slabs = $this->Slaves->query("SELECT * FROM slabs as Slab WHERE Slab.id = '3'");
			//}
			$this->set('slabs',$slabs);
			$this->set('distributors',$distRecords);
			$this->set('records',$distRecords);
			$this->set('record_without_sd',$distRecords);
			$this->set('modelName','Distributor');
			$this->ds = $distRecords;
		}

        if($this->Session->read('Auth.proposition_agreement') == 0 && $this->Session->read('Auth.User.group_id') == DISTRIBUTOR && $this->here != "/shops/distAgreement"  && $this->here != "/shops/logout" &&  $this->here != "/shops/changePassword") {
             $this->redirect(array('controller' => 'shops', 'action' => 'distAgreement'));
        }
//contest flag
//        if($this->Session->read('Auth.contest_flag') == 0 && $this->Session->read('Auth.User.group_id') == DISTRIBUTOR && $this->here != "/shops/distContest"  && $this->here != "/shops/logout" && $this->here != "/shops/distAgreement" &&  $this->here != "/shops/changePassword") {
//             $this->redirect(array('controller' => 'shops', 'action' => 'distContest'));
//        }


		$this->set('states',$states);
		$this->set('cities',$cities);
		$this->Auth->loginAction = array('controller' => 'shops', 'action' => 'index');
		$this->Auth->logoutRedirect = array('controller' => 'shops', 'action' => 'index');
		$this->Auth->loginRedirect = array('controller' => 'shops', 'action' => 'view');
	}

	function index(){
		if($_SERVER['SERVER_NAME'] == "processor.pay1.in")$this->redirect(DISTPANEL_URL);
		if($this->Session->check('Auth.User')){
			$this->redirect(array('action' => 'view'));
			/*if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER || $_SESSION['Auth']['User']['group_id'] == ACCOUNTS || $_SESSION['Auth']['User']['group_id'] == '20'){
				$this->redirect(array('action' => 'view'));
			}
			else if($_SESSION['Auth']['User']['group_id'] == CUSTCARE){
				$this->redirect(array('controller' => 'panels','action' => 'index'));
			}*/
		}
		else if($_SERVER['SERVER_NAME'] == 'apis.signal7.in'){
			echo "Work going on";
			$this->autoRender = false;
		}
		else {
                        $group    = $this->Slaves->query("SELECT * FROM groups ORDER BY show_order ASC");
                        if (strpos($_SERVER['HTTP_HOST'],'cc') !== false) {
                                $host = 'cc';
                        } else if (strpos($_SERVER['HTTP_HOST'],'panel') !== false) {
                                $host = 'panel';
                        }
                        
                        $login_as = array();
                        foreach ($group as $login){
                                if($login['groups'][$host] == 1){
                                        $login_as[] = $login;
                                }
                        }
                        
                        empty($login_as) && $login_as = $group;
                        
                        $this->set('login_as', $login_as);
			$this->render('index');
		}
	}

	function view(){
		if(!isset($_SESSION['Auth']['User'])){
                        $this->redirect('/');
		} else if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR ||$_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) {
                        $this->render('dist_home_page');
                } else if($_SESSION['Auth']['User']['group_id'] == RETAILER){
			$this->redirect(RETPANEL_URL);

		}
		else if($_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){
			$this->redirect(array('controller' => 'shops','action' => 'allDistributor'));
		}
		else if($_SESSION['Auth']['User']['group_id'] == 9){
			$data = $this->Slaves->query("SELECT id FROM vendors WHERE user_id = ".$_SESSION['Auth']['User']['id']);
			$this->redirect('/sims/index');
		}
		else if($_SESSION['Auth']['User']['group_id'] == '18'){
			$this->redirect(array('controller' => 'shops','action' => 'limitTransfer'));
		}
		else{
			$this->redirect(array('controller' => 'panels','action' => 'view'));
		}
	}

	function autoCompleteSubarea(){
		$name = $this->data['Salesmen']['subarea'];

		$this->Shop->recursive = -1;
		$data = $this->Slaves->query("SELECT * from subarea where name like '".$name."%'");

		$this->set('subarea',$data);
		$this->render('auto_complete_subarea');
	}




	function formDistributor(){
		$services = $this->Slaves->query("SELECT id,name FROM services WHERE toShow = 1");
                $this->set('services', array_map('current', $services));
                $this->set('primary_services', Configure::read('primary_services'));
		$this->render('form_distributor');
	}

	function formSuperDistributor(){

		$allRM = $this->Slaves->query("
			SELECT r.id,r.name 
			FROM rm r
			JOIN users u ON u.id = r.user_id
		 	WHERE u.active_flag = 1");
         $this->set('allRM', $allRM);
	}

	function backDistributor(){
		$this->set('data',$this->data);
		$this->render('/elements/shop_form_distributor');
	}

	function backSuperDistributor(){
		$this->set('data',$this->data);
		$this->render('/elements/shop_form_super_distributor');
	}

	function backDistEdit($type){
		/*echo "<pre>";
		 print_r($this->data);
		 echo "</pre>";**/
		if($type == 'r'){
			if($_SESSION['Auth']['User']['group_id'] != DISTRIBUTOR)$this->redirect('/');

			$modName = 'Retailer';
			$cities = $this->Slaves->query("SELECT id,name FROM locator_city WHERE state_id = ". $this->data['Retailer']['state']." AND toshow = 1 ORDER BY name asc");
			$this->set('cities',$cities);
			$areas = $this->Slaves->query("SELECT id,name FROM locator_area WHERE city_id = ". $this->data['Retailer']['city']." AND toshow = 1 ORDER BY name asc");
			$this->set('areas',$areas);

			$stateName = $this->Slaves->query("SELECT name FROM locator_state WHERE id = ". $this->data['Retailer']['state']);
			$cityName = $this->Slaves->query("SELECT name FROM locator_city WHERE id = ". $this->data['Retailer']['city']);
			$areaName = $this->Slaves->query("SELECT name FROM locator_area WHERE id = ". $this->data['Retailer']['area_id']);

			$this->data['Retailer']['state'] = $stateName['0']['locator_state']['name'];
			$this->data['Retailer']['city'] = $cityName['0']['locator_city']['name'];
			$this->data['Retailer']['area'] = $areaName['0']['locator_area']['name'];

			$sMen = $this->Slaves->query("SELECT id,name,mobile FROM salesmen where dist_id = ".$this->info['id']."");
			$this->set('sMen',$sMen);

			//$fees = $this->Retailer->query("SELECT sum(shop_transactions.amount) as fee FROM shop_transactions join salesman_transactions on (salesman_transactions.shop_tran_id = shop_transactions.id) where salesman_transactions.payment_type = ".TYPE_SETUP." and shop_transactions.target_id = ".$this->data['Retailer']['id']." group by salesman_transactions.payment_type");
			//$this->set('fees',$fees);
		}
		if($type == 'd'){
			if($_SESSION['Auth']['User']['group_id'] != MASTER_DISTRIBUTOR)$this->redirect('/');

			$modName = 'Distributor';
//			$cities = $this->Slaves->query("SELECT id,name FROM locator_city WHERE state_id = ". $this->data['Distributor']['state']." AND toshow = 1 ORDER BY name asc");
//			$this->set('cities',$cities);
//			$stateName = $this->Slaves->query("SELECT name FROM locator_state WHERE id = ". $this->data['Distributor']['state']);
//			$cityName = $this->Slaves->query("SELECT name FROM locator_city WHERE id = ". $this->data['Distributor']['city']);
//
//			$this->data['Distributor']['state'] = $stateName['0']['locator_state']['name'];
//			$this->data['Distributor']['city'] = $cityName['0']['locator_city']['name'];;
		}
		$tmparr[0] = $this->data;
		$this->set('editData',$tmparr);
		$this->set('type',$type);
		$this->render('/elements/edit_form_ele_retailer');
	}

	function backSuperDistributorEdit(){
		$tmparr[0] = $this->data;
		$this->set('editData',$tmparr);
		$this->render('/elements/edit_form_super_distributor');
	}


	function createDistributor(){

		$msg = "";
		$empty = array();
		$empty_flag = false;
		$to_save = true;
		$confirm = 0;
                $MsgTemplate = $this->General->LoadApiBalance();
		if(isset($this->data['Distributor']['tds_flag']) && $this->data['Distributor']['tds_flag'] == 'on')
		$this->data['Distributor']['tds_flag'] = 1;
		else
		$this->data['Distributor']['tds_flag'] = 0;

		if(isset($this->data['confirm']))
		$confirm = $this->data['confirm'];

		$this->set('data',$this->data);
                if(empty($this->data['Distributor']['map_lat'])){
			$empty[] = 'Latitude';
			$empty_flag = true;
			$to_save = false;
		}
                if(empty($this->data['Distributor']['map_long'])){
			$empty[] = 'Longitute';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['name'])){
			$empty[] = 'Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['company'])){
			$empty[] = 'Company Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['mobile'])){
			$empty[] = 'Mobile';
			$empty_flag = true;
			$to_save = false;
		}
		else {
			$this->data['Distributor']['mobile'] = trim($this->data['Distributor']['mobile']);
			//preg_match('/^[6-9][0-9]{9}$/',$this->data['Distributor']['mobile'],$matches,0);
			if($this->General->mobileValidate($this->data['Distributor']['mobile']) == '1'){
				$msg = "Mobile Number is not valid";
				$to_save = false;
			}
		}
        if(empty($this->data['Distributor']['dob'])){
			$empty[] = 'DOB';
			$empty_flag = true;
			$to_save = false;
		}else{
                    $date = explode("-", $this->data['Distributor']['dob']);
                    $this->data['Distributor']['dob'] = $date[2] . "-" . $date[1] . "-" . $date[0];
                }
		if(empty($this->data['Distributor']['state'])){
			$empty[] = 'State';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['city'])){
                    	$empty[] = 'City';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['area_range'])){
                    	$empty[] = 'Area Range';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['address'])){
                    	$empty[] = 'Address';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['pan_number'])){
			$empty[] = 'PAN Number';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['gst_no'])){
			$empty[] = 'GST Number';
			$empty_flag = true;
			$to_save = false;
		}
                if(isset($this->data['Distributor']['commission_type']) && $this->data['Distributor']['commission_type'] == 0){
                        $primary_services = Configure::read('primary_services');
                        $result = array_diff(explode(',', $this->data['Distributor']['active_services']), $primary_services);
                        if(!empty($result)) {
                                $msg = "For this services, you have to select Tertiary Commission Type";
				$to_save = false;
                        }
		}
                if(isset($this->data['Distributor']['margin'])) {
                        if($this->data['Distributor']['margin'] == '') {
                                $empty[] = 'Margin';
                                $empty_flag = true;
                                $to_save = false;
                        }
                        if($this->data['Distributor']['margin'] > 0.5) {
                                $msg = "Margin can't be greater than 0.5 %";
                                $to_save = false;
                        }
                }

                if($this->Session->read('Auth.User.group_id') == SUPER_DISTRIBUTOR){

	                $chkDistributorCreatedCount = $this->Slaves->query("SELECT count(distributors.id) as distributor_count,super_distributors.no_distributors_limit FROM distributors JOIN super_distributors ON distributors.sd_id = super_distributors.id WHERE sd_id = ".$this->info['id'] );
	                if(!empty($chkDistributorCreatedCount)){
	                	$distributor_count = $chkDistributorCreatedCount[0][0]['distributor_count'];
	                	$no_distributors_limit = $chkDistributorCreatedCount[0]['super_distributors']['no_distributors_limit'];
	                	if($distributor_count>=$no_distributors_limit){
	                		$to_save = false;
							$msg = "You cannot create more than ".$no_distributors_limit." distributor";
	                	}
	                }
	            }

                if($this->data['Distributor']['active_services'] != '' && $this->data['confirm'] != 1) {
                        $services = $this->Slaves->query("SELECT id,name FROM services WHERE id IN (".$this->data['Distributor']['active_services'].")");
                        $this->set('active_services', array_map('current', $services));
                }

		if($to_save){
			$exists = $this->General->checkIfUserExists($this->data['Distributor']['mobile'], null, DISTRIBUTOR);
			if($exists){
				$user = $this->General->getUserDataFromMobile($this->data['Distributor']['mobile']);
				$user_groups = $user['groups'];
				if(in_array(SALESMAN,$user_groups) || in_array(DISTRIBUTOR,$user_groups) || in_array(RETAILER,$user_groups)){
					$to_save = false;
					$msg = "You cannot make this mobile as your distributor";
				}
			}
		}

                App::import('Controller', 'Apis');
                $obj = new ApisController;
                $obj->constructClasses();

                if(!isset($this->data['Distributor']['otp']) && !$empty_flag){

                    $sendOTPdata['mobile'] = $this->Session->read('Auth.User.mobile');
                    $sendOTPdata['interest'] = 'Distributor';
                    $sendOTPdata['create_dist_otp_flag'] = 1;

                    $otpData = $obj->sendOTPToRetDistLeads($sendOTPdata);

                }

		if(!$to_save){
                        $services = $this->Slaves->query("SELECT id,name FROM services WHERE toShow = 1");
                        $this->set('services', array_map('current', $services));
			if($empty_flag){
				$err_msg = '<div class="error_class">'.implode(", ",$empty).' cannot be set empty</div>';
				$this->Session->setFlash($err_msg, true);
				$this->render('/elements/shop_form_distributor','ajax');
			}
			else {
				$err_msg = '<div class="error_class">'.$msg.' </div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/shop_form_distributor','ajax');
			}
		}
		else if($confirm == 0 && $to_save){
                    $stateId = $this->General->stateInsert($this->data['Distributor']['state']);
                    $cityId = $this->General->cityInsert($this->data['Distributor']['city'] , $stateId);
			$slab = $this->Slaves->query("SELECT name FROM slabs WHERE id = " . $this->data['Distributor']['slab_id']);

			$this->set('slab',$slab);
                        $this->set('dob',$this->data['Distributor']['dob']);
			$this->set('city',$this->data['Distributor']['city']);
			$this->set('state',$this->data['Distributor']['state']);
			$this->render('confirm_distributor','ajax');
		}
		else {

                    //To verify OTP sent to Master Distributor Mobile Number
                    $verify_param['mobile'] = $this->Session->read('Auth.User.mobile');
                    $verify_param['otp'] =   $this->data['Distributor']['otp'];
                    $verify_param['interest'] =   'MasterDistributor';

                    $verifyData =  $obj->verifyOTP($verify_param);

                    if($verifyData['status'] =='failure'){

                        $err_msg = '<div class="error_class">Please enter correct OTP. </div>';
                        $this->Session->setFlash(__($err_msg, true));
                        $this->render('confirm_distributor','ajax');
                        return;
                    }



			$this->data['Distributor']['created'] = date('Y-m-d H:i:s');
			$this->data['Distributor']['modified'] = date('Y-m-d H:i:s');
			$this->data['Distributor']['balance'] = 0;

			if(!$exists){
//                                $check_exist = $this->Slaves->query("SELECT id,mobile,syspass FROM users WHERE mobile = '" . $this->data['Distributor']['mobile'] . "'");
//                                if (!$check_exist) {
                                        $user = $this->General->registerUser($this->data['Distributor']['mobile'],RETAILER_REG,DISTRIBUTOR);
                                        $user = $user['User'];
//                                } else {
//                                        $user = $check_exist[0]['users'];
//                                }
			}

			$this->data['Distributor']['user_id'] = $user['id'];
			$this->data['Distributor']['parent_id'] = $this->info['id'];
			$this->data['Distributor']['target_amount'] = 25000;

                        if(!isset($this->data['Distributor']['commission_type'])) {
                                $this->data['Distributor']['commission_type'] = $this->Session->read('Auth.commission_type');
                                $this->data['Distributor']['active_services'] = $this->Session->read('Auth.active_services');
                        }
                        
                        if($this->Session->read('Auth.User.group_id') == SUPER_DISTRIBUTOR){
                                $this->data['Distributor']['commission_type'] = 1;
                                $this->data['Distributor']['parent_id'] = 3;
                                $this->data['Distributor']['sd_id'] = $this->info['id'];
                                $this->data['Distributor']['rm_id'] = $this->info['rm_id'];
                                $this->data['Distributor']['slab_id'] = $this->info['slab_id'];
                        } 

			$this->Distributor->create();
			if ($this->Distributor->save($this->data)) {
                                /** IMP DATA ADDED : START**/
                                $imp_update_data = array(
                                    'name' => $this->data['Distributor']['name'],
                                    'company' => $this->data['Distributor']['company'],
                                    'dob' => date('Y-m-d',strtotime($this->data['Distributor']['dob'])),
                                    'shop_area' => $this->data['Distributor']['area_range'],
                                    'shop_city' => $this->data['Distributor']['city'],
                                    'shop_state' => $this->data['Distributor']['state'],
                                    'address' => $this->data['Distributor']['address'],
                                    'pan_no' => $this->data['Distributor']['pan_number'],                                    
                                    'gst_no' => $this->data['Distributor']['gst_no'],
                                    'email' => $this->data['Distributor']['email'],
                                );
                                $this->Shop->updateUserLabelData($user['id'],$imp_update_data,$this->Session->read('Auth.User.id'),0);
                                /** IMP DATA ADDED : END**/
			        $this->Shop->addUserGroup($user['id'],DISTRIBUTOR);
                                $area_id = $this->General->getAreaIDByLatLong($this->data['Distributor']['map_long'],$this->data['Distributor']['map_lat']);
                                $this->Retailer->query("INSERT INTO retailers_location (retailer_id,area_id,latitude,longitude,updated,user_id,verified) VALUES ('0','$area_id','".$this->data['Distributor']['map_lat']."','".$this->data['Distributor']['map_long']."','".date('Y-m-d')."','".$this->data['Distributor']['user_id']."','1')");
				$mail_subject = "New Distributor Created";
				$this->General->makeOptIn247SMS($user['mobile']);

				$mail_body = "MasterDistributor: " . $this->info['company'] . "<br/>";
				$mail_body .= "Distributor: " . $this->data['Distributor']['company'];
				$mail_body .= "Address: " . $this->data['Distributor']['address'];
				$this->General->sendMails($mail_subject, $mail_body,array('dharmesh.chauhan@pay1.in','sales@pay1.in',
						'tadka@pay1.in','irfan@pay1.in', 'accounts@pay1.in', 'limits@pay1.in'),'mail');
				$this->Shop->updateSlab($this->data['Distributor']['slab_id'],$this->Distributor->id,DISTRIBUTOR);

				$distributor = $this->data;

                                $paramdata['DISTRIBUTOR_MOBILE_NUMBER'] = $distributor['Distributor']['mobile'];
                                $paramdata['USER_SYSPASS'] = $user['syspass'];
                                $content =  $MsgTemplate['CreateDistributor_MSG'];
                                $sms = $this->General->ReplaceMultiWord($paramdata,$content);


				//$sms .= "\nFind below url to download distributor app: " . $this->General->createAppDownloadUrl(DISTRIBUTOR,1);
				//$sms .= "\n\nDefault Retailer credentials for you are below: \nUserName: 1".$distributor['Distributor']['mobile']."\nPassword: ".$ret_user['syspass'];
				//$sms .= "\nFind below url to download retailer app: " . $this->General->createAppDownloadUrl(RETAILER,1);

				$this->General->sendMessage($user['mobile'],$sms,'shops');
				//}

				//create default salesman with distributors phone number
				if(!empty($this->data['Distributor']['name'])){
					$sales = $distributor['Distributor']['name'];
				}
				else {
					$sales = "Default";
				}

				$salesmanData = $this->Slaves->query("SELECT * FROM salesmen WHERE mobile = '".$distributor['Distributor']['mobile']."'");
				if(empty($salesmanData)){
					$salesman = array(
							'dist_id' 	=> 	$this->Distributor->id,
							'name'		=>	addslashes($sales),
							'mobile'	=>	$distributor['Distributor']['mobile']
					);
					$this->insertSalesman($salesman);
                                        $salesmanData = $this->Slaves->query("SELECT * FROM salesmen WHERE mobile = '".$distributor['Distributor']['mobile']."'");
				}
				else {
					$this->Retailer->query("UPDATE salesmen SET dist_id = ".$this->Distributor->id ." WHERE mobile = '".$distributor['Distributor']['mobile']."'");
				}

                                if (!$exists) {
                                        $retailerData = $this->Slaves->query("SELECT * FROM retailers WHERE mobile = '".$distributor['Distributor']['mobile']."'");
                                        if (!$retailerData) {
                                                $this->Retailer->query("INSERT INTO retailers (user_id,parent_id,slab_id,mobile,email,name,shopname,salesman,maint_salesman,created,modified,trial_flag) VALUES "
                                                        . "('".$user['id']."','".$this->Distributor->id."','13','".$distributor['Distributor']['mobile']."','".$this->data['Distributor']['email']."',"
                                                        . "'".$this->data['Distributor']['name']."','".$this->data['Distributor']['company']."','".$salesmanData[0]['salesmen']['id']."',"
                                                        . "'".$salesmanData[0]['salesmen']['id']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','1')");
                                                $this->Shop->addUserGroup($user['id'],RETAILER);
                                                $retailerData = $this->Retailer->query("SELECT * FROM retailers WHERE mobile = '".$distributor['Distributor']['mobile']."'");
                                                $this->Retailer->query("INSERT INTO unverified_retailers (retailer_id,name,shopname,created,modified) VALUES "
                                                        . "('".$retailerData[0]['retailers']['id']."','".$retailerData[0]['retailers']['name']."','".$retailerData[0]['retailers']['shopname']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')");
                                                $this->Retailer->query("INSERT INTO user_profile (user_id,location_src,area_id,device_type,version,manufacturer,created,updated,date) VALUES "
                                                        . "('".$user['id']."','network','0','web','5','Chrome','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','".date('Y-m-d')."')");
                                        }
                                }

				$this->set('data',null);
				$this->render('/elements/shop_form_distributor','ajax');
			} else {


				$err_msg = '<div class="error_class">The Distributor could not be saved. Please, try again.</div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/shop_form_distributor','ajax');
			}
		}
	}

	function createSuperDistributor(){

		$msg = "";
		$empty = array();
		$empty_flag = false;
		$to_save = true;
		$confirm = 0;
                $MsgTemplate = $this->General->LoadApiBalance();

		if(isset($this->data['confirm']))
		$confirm = $this->data['confirm'];
		$this->set('data',$this->data);
        if(empty($this->data['SuperDistributor']['map_lat'])){
			$empty[] = 'Latitude';
			$empty_flag = true;
			$to_save = false;
		}
       	if(empty($this->data['SuperDistributor']['map_long'])){
			$empty[] = 'Longitute';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['name'])){
			$empty[] = 'Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['company'])){
			$empty[] = 'Company Name';
			$empty_flag = true;
			$to_save = false;
		} 
		if(empty($this->data['SuperDistributor']['mobile'])){
			$empty[] = 'Mobile';
			$empty_flag = true;
			$to_save = false;
		}
		else {
			$this->data['SuperDistributor']['mobile'] = trim($this->data['SuperDistributor']['mobile']);
			//preg_match('/^[6-9][0-9]{9}$/',$this->data['SuperDistributor']['mobile'],$matches,0);
			if($this->General->mobileValidate($this->data['SuperDistributor']['mobile']) == '1'){
				$msg = "Mobile Number is not valid";
				$to_save = false;
			}
		}
        if(empty($this->data['SuperDistributor']['dob'])){
			$empty[] = 'DOB';
			$empty_flag = true;
			$to_save = false;
		}else{
                    $date = explode("-", $this->data['SuperDistributor']['dob']);
                    $this->data['SuperDistributor']['dob'] = $date[2] . "-" . $date[1] . "-" . $date[0];
                }
		if(empty($this->data['SuperDistributor']['state'])){
			$empty[] = 'State';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['city'])){
                    	$empty[] = 'City';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['address'])){
                    	$empty[] = 'Address';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['pan_number'])){
			$empty[] = 'PAN Number';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['gst_no'])){
			$empty[] = 'GST Number';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['rm_id'])){
			$empty[] = 'RM';
			$empty_flag = true;
			$to_save = false;
		}
               

                

		if($to_save){
			$exists = $this->General->checkIfUserExists($this->data['SuperDistributor']['mobile'], null, SUPER_DISTRIBUTOR);
			if($exists){
				$user = $this->General->getUserDataFromMobile($this->data['SuperDistributor']['mobile']);
				$user_groups = $user['groups'];
				if(in_array(SALESMAN,$user_groups) || in_array(DISTRIBUTOR,$user_groups) || in_array(RETAILER,$user_groups) || in_array(SUPER_DISTRIBUTOR,$user_groups)){
					$to_save = false;
					$msg = "You cannot make this mobile as your super distributor";
				}
			}
		}

                App::import('Controller', 'Apis');
                $obj = new ApisController;
                $obj->constructClasses();

                if(!isset($this->data['SuperDistributor']['otp']) && !$empty_flag){

                    $sendOTPdata['mobile'] = $this->Session->read('Auth.User.mobile');
                    $sendOTPdata['interest'] = 'SuperDistributor';
                    $sendOTPdata['create_super_dist_otp_flag'] = 1;

                    $otpData = $obj->sendOTPToRetDistLeads($sendOTPdata);

                }

                 $allRM = $this->Slaves->query("
									SELECT r.id,r.name 
									FROM rm r
									JOIN users u ON u.id = r.user_id
								 	WHERE u.active_flag = 1");
						         $this->set('allRM', $allRM);

		if(!$to_save){
                       
			if($empty_flag){
				$err_msg = '<div class="error_class">'.implode(", ",$empty).' cannot be set empty</div>';
				$this->Session->setFlash($err_msg, true);
				$this->render('/elements/shop_form_super_distributor','ajax');
			}
			else {
				$err_msg = '<div class="error_class">'.$msg.' </div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/shop_form_super_distributor','ajax');
			}
		}
		else if($confirm == 0 && $to_save){
                    $stateId = $this->General->stateInsert($this->data['SuperDistributor']['state']);
                    $cityId = $this->General->cityInsert($this->data['SuperDistributor']['city'] , $stateId);
			$slab = $this->Slaves->query("SELECT name FROM slabs WHERE id = " . $this->data['SuperDistributor']['slab_id']);

			$this->set('slab',$slab);
            $this->set('dob',$this->data['SuperDistributor']['dob']);
			$this->set('city',$this->data['SuperDistributor']['city']);
			$this->set('state',$this->data['SuperDistributor']['state']);
			$this->render('confirm_super_distributor','ajax');
		}
		else {
                    //To verify OTP sent to Super Distributor Mobile Number
                    $verify_param['mobile'] = $this->Session->read('Auth.User.mobile');
                    $verify_param['otp'] =   $this->data['SuperDistributor']['otp'];
                    $verify_param['interest'] =   'SuperDistributor';
                    $verifyData =  $obj->verifyOTP($verify_param);
                    if($verifyData['status'] =='failure'){

                        $err_msg = '<div class="error_class">Please enter correct OTP. </div>';
                        $this->Session->setFlash(__($err_msg, true));
                        $this->render('confirm_super_distributor','ajax');
                        return;
                    }



			$this->data['SuperDistributor']['created'] = date('Y-m-d H:i:s');
			$this->data['SuperDistributor']['modified'] = date('Y-m-d H:i:s');
			$this->data['SuperDistributor']['balance'] = 0;

			if(!$exists){
//                                $check_exist = $this->Slaves->query("SELECT id,mobile,syspass FROM users WHERE mobile = '" . $this->data['SuperDistributor']['mobile'] . "'");
//                                if (!$check_exist) {
                                        $user = $this->General->registerUser($this->data['SuperDistributor']['mobile'],RETAILER_REG,SUPER_DISTRIBUTOR);
                                        $user = $user['User'];
//                                } else {
//                                        $user = $check_exist[0]['users'];
//                                }
			}

			$this->data['SuperDistributor']['user_id'] = $user['id'];
			
              
             $super_distributors_insert = $this->SuperDistributor->query("INSERT INTO super_distributors(user_id,rm_id,slab_id,created,modified) VALUES('".$user['id']."','".$this->data['SuperDistributor']['rm_id']."','".$this->data['SuperDistributor']['slab_id']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')");

                
			if ($super_distributors_insert) {
			        $this->Shop->addUserGroup($user['id'],SUPER_DISTRIBUTOR);
                                $area_id = $this->General->getAreaIDByLatLong($this->data['SuperDistributor']['map_long'],$this->data['SuperDistributor']['map_lat']);
                                $this->Retailer->query("INSERT INTO retailers_location (retailer_id,area_id,latitude,longitude,updated,user_id,verified) VALUES ('0','$area_id','".$this->data['SuperDistributor']['map_lat']."','".$this->data['SuperDistributor']['map_long']."','".date('Y-m-d')."','".$this->data['SuperDistributor']['user_id']."','1')");


                    /** IMP DATA ADDED : START**/
                    $imp_update_data = array(
                        'name' => $this->data['SuperDistributor']['name'],
                        'pan_number' => $this->data['SuperDistributor']['pan_number'],
                        'shop_est_name' => $this->data['SuperDistributor']['company'],
                        'email' => $this->data['SuperDistributor']['email'],
                        'address' => $this->data['SuperDistributor']['address'],
                        'gst_no' => $this->data['SuperDistributor']['gst_no'],
                        'dob' => $this->data['SuperDistributor']['dob'],
                        'mobile' => $this->data['SuperDistributor']['mobile']
                    );
                    $response = $this->Shop->updateUserLabelData($user['id'],$imp_update_data,$this->Session->read('Auth.User.id'));
                    /** IMP DATA ADDED : END**/




				$mail_subject = "New Super Distributor Created";
				$this->General->makeOptIn247SMS($user['mobile']);

				$mail_body = "Master Distributor: " . $this->info['company'] . "<br/>";
				$mail_body .= "Super Distributor: " . $this->data['SuperDistributor']['company'];
				$mail_body .= "Address: " . $this->data['SuperDistributor']['address'];
				$this->General->sendMails($mail_subject, $mail_body,array('dharmesh.chauhan@pay1.in','sales@pay1.in',
						'tadka@pay1.in','irfan@pay1.in', 'accounts@pay1.in', 'limits@pay1.in'),'mail');
				$this->Shop->updateSlab($this->data['SuperDistributor']['slab_id'],$this->SuperDistributor->id,SUPER_DISTRIBUTOR);

				$super_distributor = $this->data;

                                $paramdata['SUPER_DISTRIBUTOR_MOBILE_NUMBER'] = $super_distributor['SuperDistributor']['mobile'];
                                $paramdata['USER_SYSPASS'] = $user['syspass'];
                                $content =  $MsgTemplate['CreateSuperDistributor_MSG'];
                                $sms = $this->General->ReplaceMultiWord($paramdata,$content);



				$this->General->sendMessage($user['mobile'],$sms,'shops');


					//-------------- Send email to admin when rm added with distributor ----------
					// variable array contains the values which is to be parsed in email_body

					$SelectedRM = $this->Slaves->query("
									SELECT id,name,mobile
									FROM rm
								 	WHERE id = ".$this->data['SuperDistributor']['rm_id']);

					$varParseArr = array (
                                             'rm_name'             =>  $SelectedRM[0]['rm']['name'],
                                             'master_distributor_company'  =>  $this->info['name'],
											 'super_distributor_company'  => $this->data['SuperDistributor']['name'],
					);
					$this->General->sendTemplateEmailToAdmin("emailToAdminOnRmAddWithSuperDistributor",$varParseArr);
					//----------------------------------------------------------------------------

					//---- Send SMS ( welcome msg ) to distributor(who became RM) on RM Add with distributor ------
					// variable array contains the values which is to be parsed in sms_body
					$varParseArr = array (
                                             'rm_mobile'           =>  $$SelectedRM[0]['rm']['mobile'],
                                             'rm_name'             =>  $SelectedRM[0]['rm']['name'],
					);
					$this->General->sendTemplateSMSToMobile($this->data['SuperDistributor']['mobile'],"smsToSuperDistributorOnRmAdd",$varParseArr);
					//----------------------------------------------------------------------------

				//}

				$this->set('data',null);
				$this->render('/elements/shop_form_super_distributor','ajax');
			} else {


				$err_msg = '<div class="error_class">Super Distributor could not be saved. Please, try again.</div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/shop_form_super_distributor','ajax');
			}
		}
	}

	/*function allotCards(){
		$this->render('allotcards');
	}

	function backAllotment(){
		$this->set('data',$this->data);
		$this->render('/elements/shop_allot_cards');
	}*/

    function rmdashboard(){
        if( $this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER || $this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR ){

            $this->set('dist_count',count($this->ds));
            $this->set('retailer_count',array_sum($this->Shop->getRetailerCountByDistIds(implode(',',array_map('current',array_map('current',$this->ds))))));
            $this->set('current_sales',$this->Shop->getRmCurrentSaleEarningServiceWise($this->Session->read('Auth.id'),null,null,null,date('Y'),date('m')));

            $trans_retailer_count = 0;
            $temp = $this->Shop->getTransactingRetsByRmId($this->Session->read('Auth.id'),date('Y'),date('m'));
            foreach ($temp as $dist_id => $trans_ret) {
                $trans_retailer_count+= array_sum($trans_ret);
            }
            $this->set('trans_retailer_count',$trans_retailer_count);

            $schemes = $this->Scheme->getScheme(implode(',',array_map('current',array_map('current',$this->ds))),date('m'),date('Y'));
            $on_track_dists = array();
            $off_track_dists = array();
            $sales_target = 0;
            foreach( array_map('current',array_map('current',$this->ds)) as $dist_id ){
                $recharge_target = $schemes[$dist_id]['target']['target1']['recharge'];
                $sales_target += $recharge_target;
                $recharge_expected_achieved = (($recharge_target/30)*date("d"));
                $recharge_achieved = (isset($schemes[$dist_id]['achieved']['0']) && !empty($schemes[$dist_id]['achieved']['0']['sale'])) ? $scheme['achieved']['0']['sale']  : 0;

                if( $recharge_achieved >= $recharge_expected_achieved){
                    $on_track_dists[] = $dist_id;
                } else {
                    $off_track_dists[] = $dist_id;
                }
            }
            $this->set('sales_target',$sales_target);
            $this->set('dist_on_track',count($on_track_dists));
            $this->set('dist_off_track',count($off_track_dists));
            $this->render('rmdashboard');
        }
    }
    function rmNewLead(){
        if( $this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER || $this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR ){
            $this->render('rmnewlead');
        }
    }

    function getPercentSale($scheme_data){
        $achieved = $scheme_data['achieved'];
        $percent = 0;
        foreach($scheme_data as $sd){
            if($sd['sale'] > 0 && $achieved/$sd['sale'] > $percent){
                $percent = $achieved/$sd['sale'];
            }
            else if($sd['sale'] == 0){
                $percent = 1;
            }
        }
        return $percent*100;
    }

    function defineLabel($percent,$expect_percent=100){
        if($percent >= $expect_percent*1.25){
            $label =1;
        }
        else if($percent >= $expect_percent){
            $label =2;
        }
        else if($percent >= $expect_percent*0.9){
            $label =3;
        }
        else if($percent >= $expect_percent*0.75){
            $label = 4;
        }
        else {
            $label = 5;
        }

        return $label;
    }

    function rmTargetReport(){
        if( $this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER || $this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR ){

            $year = date('Y');
            $month = date('m');
            if( array_key_exists('year_month',$this->params['url']) ){
                $year_month = explode('-',trim($this->params['url']['year_month']));
                $year = $year_month[0];
                $month = $year_month[1];
            }

            $selected_label = array();
            if( array_key_exists('label',$this->params['url']) ){
                $selected_label = $this->params['url']['label'];
            }


            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/",$year.'-'.$month)) {
                $dist_ids = array_map(function($element){
                    return $element['Distributor']['id'];
                },$this->ds);

                $this->set('year_month',$year.'-'.$month);

                $retailer_count = $this->Shop->getRetailerCountByDistIds(implode(',',$dist_ids));
                $trans_retailer_count = $this->Shop->getTransactingRetsByRmId($this->Session->read('Auth.User.group_id'),$this->Session->read('Auth.id'),$year,$month);

                $dist_sales = $this->Shop->getRmCurrentSaleEarningServiceWise($this->Session->read('Auth.User.group_id'),$this->Session->read('Auth.id'),null,null,null,$year,$month);
                $kit_sales = $this->Shop->getKitSales($this->Session->read('Auth.User.group_id'),$this->Session->read('Auth.id'),$year,$month);



                    $schemes = $this->Scheme->getScheme(implode(",",$dist_ids),$month,$year);

                $achieved = array();

                foreach($schemes as $scheme_id=>$scheme){
                    foreach($scheme as $dist_id=>$scheme_data){
                        if($scheme_data['scheme_completed'] == 1){
                            $percent = $this->getPercentSale($scheme_data);
                            $schemes[$scheme_id][$dist_id]['percent'] = $percent;
                            $schemes[$scheme_id][$dist_id]['label'] = $this->defineLabel($percent);

                            $schemes[$scheme_id][$dist_id]['overall_expected_percent'] = 100;
                            $schemes[$scheme_id][$dist_id]['overall_achieved_percent'] = $percent;
                        }
                        else{
                            $date_diff = date_diff(date_create($scheme_data['scheme_start']), date_create($scheme_data['scheme_end']));
                            $schemeDays = $date_diff->format('%a') + 1;

                            $date_diff = date_diff(date_create($scheme_data['scheme_start']), date_create(date('Y-m-d')));
                            $days_passed = $date_diff->format('%a') + 1;
                            $expected_percent = $days_passed * 100 / $schemeDays;

                            $percent = $this->getPercentSale($scheme_data);
                            $schemes[$scheme_id][$dist_id]['percent'] = $percent*100/$expected_percent;
                            $schemes[$scheme_id][$dist_id]['overall_expected_percent'] = $expected_percent;
                            $schemes[$scheme_id][$dist_id]['overall_achieved_percent'] = $percent;
                            $schemes[$scheme_id][$dist_id]['label'] = $this->defineLabel($percent, $expected_percent);
                        }
                        if(!empty($selected_label) && !in_array($schemes[$scheme_id][$dist_id]['label'], $selected_label)){
                            unset($schemes[$scheme_id][$dist_id]);
                        }
                    }
                }
                 /*foreach( $dist_ids as $dist_id ){

                    if( array_key_exists($dist_id,$schemes) && !empty($schemes[$dist_id]['target']) ){
                        $recharge_target1 = $schemes[$dist_id]['target']['target1']['recharge'];
                        $recharge_target2 = $schemes[$dist_id]['target']['target2']['recharge'];


                        if( ($year == date('Y')) && ($month == date('m')) ){
                            $days_passed = date("d")-1;
                        } else {
                            $days_passed = cal_days_in_month(null,$month,$year);
                        }

                        $recharge_expected_achieved_target1 = (($recharge_target1/cal_days_in_month(null,$month,$year))*($days_passed));
                        $recharge_expected_achieved_target2 = (($recharge_target2/cal_days_in_month(null,$month,$year))*($days_passed));

                        // $recharge_achieved = (isset($schemes[$dist_id]['achieved']['0']) && !empty($schemes[$dist_id]['achieved']['0']['sale'])) ? $scheme['achieved']['0']['sale']  : null;
                        $recharge_achieved = (isset($dist_sales[$dist_id][1][$year][$month]['sale']) && !empty($dist_sales[$dist_id][1][$year][$month]['sale'])) ? $dist_sales[$dist_id][1][$year][$month]['sale']  : null;
                        $percent_achieved = round(($recharge_achieved/$recharge_expected_achieved_target1)*100);

                        $achieved[$dist_id]['overall_expected_percent'] = round(($days_passed/cal_days_in_month(null,$month,$year))*100);
                        $achieved[$dist_id]['overall_achieved_percent'] = round(($recharge_achieved/$recharge_target1)*100);
                        $achieved[$dist_id]['percent'] = $percent_achieved;
                        $achieved[$dist_id]['target1'] = $recharge_target1;
                        $achieved[$dist_id]['recharge_achieved'] = ($recharge_achieved-$recharge_expected_achieved_target1);

                        if( $recharge_achieved >= $recharge_expected_achieved_target2 ){
                            $achieved[$dist_id]['label'] = 1;

                        } else if( ($recharge_achieved < $recharge_expected_achieved_target2) && ($recharge_achieved >= $recharge_expected_achieved_target1) ){
                            $achieved[$dist_id]['label'] = 2;
                        } else if( $recharge_achieved < $recharge_expected_achieved_target1 ){

                            if( $percent_achieved >= 90 ){
                                $achieved[$dist_id]['label'] = 3;
                            } else if( ($percent_achieved >= 75) && ($percent_achieved < 90) ){
                                $achieved[$dist_id]['label'] = 4;
                            } else if($percent_achieved < 75){
                                $achieved[$dist_id]['label'] = 5;
                            }
                        }
                    }
                }
*/
                $sales_report = array();
                foreach ( $this->ds as $dist ) {
             //          if( array_key_exists($dist['Distributor']['id'],$achieved ) ){
                    $sales_report[$dist['Distributor']['id']]['dist_user_id'] =  $dist['Distributor']['user_id'];
                    $sales_report[$dist['Distributor']['id']]['slab'] =  $dist['slabs']['name'];
                    $sales_report[$dist['Distributor']['id']]['rm'] =  $dist['rm']['name'];
                    $sales_report[$dist['Distributor']['id']]['dist_name'] =  $dist['Distributor']['company'];
                    $sales_report[$dist['Distributor']['id']]['mobile'] =  $dist['Distributor']['mobile'];
                    $sales_report[$dist['Distributor']['id']]['retailer_count'] =  ( isset($retailer_count[$dist['Distributor']['id']]) && !empty($retailer_count[$dist['Distributor']['id']]) ) ? $retailer_count[$dist['Distributor']['id']] : 0;


                    /*$sales_report[$dist['Distributor']['id']]['recharge_sale'] =  ( isset($dist_sales[$dist['Distributor']['id']]) && !empty($dist_sales[$dist['Distributor']['id']]) ) ? array_sum(array_map(function($element){return array_sum(array_map(function ($elm){return $elm['sale'];},$element));},$dist_sales[$dist['Distributor']['id']][1])) : 0 ;
                    $sales_report[$dist['Distributor']['id']]['recharge_trans_retailer_count'] =  ( isset($trans_retailer_count[$dist['Distributor']['id']]) && !empty($trans_retailer_count[$dist['Distributor']['id']]) ) ? $trans_retailer_count[$dist['Distributor']['id']][1] : 0 ;

                    $sales_report[$dist['Distributor']['id']]['swipe_sale'] =  ( isset($dist_sales[$dist['Distributor']['id']]) && !empty($dist_sales[$dist['Distributor']['id']]) ) ? array_sum(array_map(function($element){return array_sum(array_map(function ($elm){return $elm['sale'];},$element));},$dist_sales[$dist['Distributor']['id']][8])) : 0 ;
                    $sales_report[$dist['Distributor']['id']]['swipe_trans_retailer_count'] =  ( isset($trans_retailer_count[$dist['Distributor']['id']]) && !empty($trans_retailer_count[$dist['Distributor']['id']]) ) ? $trans_retailer_count[$dist['Distributor']['id']][8] : 0 ;

                    $sales_report[$dist['Distributor']['id']]['mpos_kit_sale'] = ( isset($kit_sales[$dist['Distributor']['id']]) && !empty($kit_sales[$dist['Distributor']['id']]) ) ? $kit_sales[$dist['Distributor']['id']][$year][$month] : 0 ;
                    $sales_report[$dist['Distributor']['id']]['remit_sale'] =  ( isset($dist_sales[$dist['Distributor']['id']]) && !empty($dist_sales[$dist['Distributor']['id']]) ) ? array_sum(array_map(function($element){return array_sum(array_map(function ($elm){return $elm['sale'];},$element));},$dist_sales[$dist['Distributor']['id']][12]))  : 0 ;
                    $sales_report[$dist['Distributor']['id']]['remit_trans_retailer_count'] =  ( isset($trans_retailer_count[$dist['Distributor']['id']]) && !empty($trans_retailer_count[$dist['Distributor']['id']]) ) ? $trans_retailer_count[$dist['Distributor']['id']][12] : 0 ;

                    $sales_report[$dist['Distributor']['id']]['smartbuy_sale'] =  ( isset($dist_sales[$dist['Distributor']['id']]) && !empty($dist_sales[$dist['Distributor']['id']]) ) ? array_sum(array_map(function($element){return array_sum(array_map(function ($elm){return $elm['sale'];},$element));},$dist_sales[$dist['Distributor']['id']][13])) : 0 ;
                    $sales_report[$dist['Distributor']['id']]['smartbuy_trans_retailer_count'] =  ( isset($trans_retailer_count[$dist['Distributor']['id']]) && !empty($trans_retailer_count[$dist['Distributor']['id']]) ) ? $trans_retailer_count[$dist['Distributor']['id']][13] : 0 ;

                    $sales_report[$dist['Distributor']['id']]['achieved'] = ( array_key_exists($dist['Distributor']['id'],$achieved) && !empty($achieved[$dist['Distributor']['id']]) ) ? $achieved[$dist['Distributor']['id']] : null ;

                    if( ($selected_label) && ($sales_report[$dist['Distributor']['id']]['achieved']['label'] != $selected_label) ){
                        unset($sales_report[$dist['Distributor']['id']]);
                    }*/
//                }
                }

            } else {
                $this->set('validation_error','Invalid date range selected !!');
            }

            $labels = array(
                1 => array(
                    'label' => 'Excellent',
                    'style'  => 'color:darkgreen;font-weight:bold;'
                ),
                2 => array(
                    'label' => 'Great',
                    'style'  => 'color:green;'
                ),
                3 => array(
                    'label' => 'Close to Target',
                    'style' => 'color:blue;'
                ),
                4 => array(
                    'label' => 'Average',
                    'style' => 'color:orange;'
                ),
                5 => array(
                    'label' => 'Poor',
                    'style' => 'color:red;'
                )
            );
            $selected_label = implode(",", $selected_label);
            $this->set('selected_label',$selected_label);
            $this->set('labels',$labels);
            $this->set('sales_report',$sales_report);
            $this->set('schemes',$schemes);
            $this->render('rmtargetreport');
        }
    }
    /*
     * on the basis of date and service filter
     * rm can check previous primary secondory and tertiery value
     * can compare previous value upto current week (week 1 , week 2 ...)
     */
    function rmOverAll(){
        //if( $this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER ||  $this->Session->read('Auth.User.group_id') ==MASTER_DISTRIBUTOR ){


            //$servicesId=array('1'=>array(1,2,3,4,5,6,7),'8'=>array(8,9,10),'11'=>array(11),'12'=>array(12),'13'=>array(13));
            if(!empty($this->params['url']) && array_key_exists('from',$this->params['url']) && array_key_exists('to',$this->params['url']) &&  array_key_exists('label',$this->params['url'])){
                $rm_sd_cond = "distributors.rm_id = ".$this->Session->read('Auth.id');
                if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR){
                    $rm_sd_cond = ' distributors.parent_id IN ('.$this->Session->read('Auth.id').')    ';
                }

                $rm_sd_cond .=' AND distributors.active_flag='.$this->params['url']['active_inactive'];
                    $servicelist = $this->Shop->getServiceByParentId($this->params['url']['label']);
                    $service = $this->params['url']['label'];
                    $service = is_array($service) ? implode(',',$service) : $service;
//                    $service = implode(",",$servicesId[$this->params['url']['label']]);
                    //$service = implode(",",$servicelist);
                      $date_from = $this->params['url']['from'];
                      $date_to = $this->params['url']['to'];
                      $today = new DateTime();
                      if($date_to == $today->format('Y-m-d')){

                          $today = new DateTime();
                          $today->sub(new DateInterval('P1D'));
                          $date_to = $today->format('Y-m-d');

                      }

                          $curr_mon = new DateTime($date_from);
                          $curr_mon->sub(new DateInterval('P1M'));
                          $prev_mon = $curr_mon->format('Y-m-d');

                      $flag = true;
                      $date1 = strtotime($date_from);
                      $date2 = strtotime($date_to);
                      $num =round(($date2-$date1)/(24*60*60))+1;

                         $weekCount = floor(($num)/7);
                         $reminderCount = ($num)%7;

                         if(empty($date_from)){
                             $this->set('validation_error','Please select From date');
                         }
                         elseif(empty($date_to)){
                              $this->set('validation_error','Please select To date');
                         }
                       else if(strtotime($date_from)> strtotime($date_to)){
                           $this->set('validation_error','From date should be less than To date');
                            $flag = false;
                        }else if($num>31){
                            $this->set('validation_error','Please select date range within 1 month only');
                            $flag = false;
                        }
                        elseif(empty($this->params['url']['label'])){
                             $this->set('validation_error','Please select service from list');
                            $flag = false;
                        }
                        else
                            {
                                    $d = date_parse_from_format("Y-m-d", $date_from);
                                    $month = $d["month"];
                                    $year = $d["year"];
                                    $avg = cal_days_in_month(null,$month-1,$year);
                                    $prev = date_parse_from_format("Y-m-d",$prev_mon);
                                    $prvMonth = $prev["month"];
                                     $prvYear = $prev["year"];
                                     $prevavg = cal_days_in_month(null,$prvMonth,$year);

                                    $sqlPrev = "SELECT ROUND(SUM(topup_sold)/$prevavg) as second,ROUND(SUM(topup_buy)/$prevavg) as prim,"
                                          . "trim(distributors.id) as rid,trim(distributors.company) as comp,users.mobile,distributors.name as distname,distributors.state,rm.name as rmname,rm.mobile as rmmobile,distributors.created as created_date  "
                                          . "FROM distributors,users_logs as distributors_logs,users,rm WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND distributors.user_id=users.id AND rm.id = distributors.rm_id  AND MONTH(date) ='".($prvMonth)."' AND YEAR(date)='".$prvYear."'  group by distributors_logs.user_id";

                                    if($this->params['url']['reporttype']=='rm'){
                                            $rm_sd_cond .=' AND rm.active_flag='.$this->params['url']['active_inactive'];
                                            $sqlPrev = "SELECT ROUND(SUM(topup_sold)/$prevavg) as second,ROUND(SUM(topup_buy)/$prevavg) as prim,"
                                              . "trim(rm.id) as rid,rm.mobile,rm.name as rmname,rm.created as created_date,rm.mobile as rmmobile  "
                                              . "FROM distributors,users_logs as distributors_logs,users,rm WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND rm.user_id=users.id AND rm.id = distributors.rm_id  AND MONTH(date) ='".($prvMonth)."' AND YEAR(date)='".$prvYear."'  group by rm.id";

                                    }
                                    $prevdata = $this->Slaves->query($sqlPrev);

                                    $this->General->logData('rmOverAll',' Previous '.$sqlPrev);


                                    $prevTerSql = "SELECT ROUND(SUM(amount)/$prevavg) as sale,trim(distributors.id) as rid,trim(distributors.company) as comp "
                                            . "FROM `retailer_earning_logs`,distributors WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND MONTH(retailer_earning_logs.date)= '".($prvMonth)."' AND YEAR(date)='".$prvYear."'  AND retailer_earning_logs.service_id IN ($service) group by distributors.id";

                                    if($this->params['url']['reporttype']=='rm'){
                                            $prevTerSql = "SELECT ROUND(SUM(amount)/$prevavg) as sale,trim(rm.id) as rid"
                                                    . "   FROM `retailer_earning_logs`,distributors,rm WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond  AND rm.id = distributors.rm_id  AND distributors.id NOT IN (".DISTS.") AND MONTH(retailer_earning_logs.date)= '".($prvMonth)."' AND YEAR(date)='".$prvYear."'  AND retailer_earning_logs.service_id IN ($service) group by rm.id";
                                    }
                                    $prev_data_ter = $this->Slaves->query($prevTerSql);

                                    $this->General->logData('rmOverAll',' PreviousTertiery '.$prevTerSql);

                                    //week1
                                    $week []= 'week1';
                                     $date_to = $weekCount>=1 ? date('Y-m-d',strtotime($date_from.'+6 days')) : $date_to;
                                     $date_to = date('Y-m-d',strtotime($date_to));
                                     $weekAvg = $num<7 ? $num : 7;

                                     $sql = "SELECT ROUND(SUM(topup_sold)/$weekAvg) as second,ROUND(SUM(topup_buy)/$weekAvg) as prim,trim(distributors.id) as rid,"
                                        . "trim(distributors.company) as comp,users.mobile,distributors.name as distname,distributors.city,distributors.state,distributors.created as created_date,"
                                        . "distributors.margin as margin,rm.name as rmname,rm.mobile as rmmobile FROM distributors,users_logs as distributors_logs,users,rm "
                                        . "WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND distributors.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to'  group by distributors_logs.user_id";

                                     if($this->params['url']['reporttype']=='rm'){

                                         $sql = "SELECT ROUND(SUM(topup_sold)/$weekAvg) as second,ROUND(SUM(topup_buy)/$weekAvg) as prim,trim(rm.id) as rid,"
                                        . "rm.mobile as rmmobile,rm.name as rmname,rm.created as created_date "
                                        . "FROM distributors,users_logs as distributors_logs,users,rm "
                                        . "WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND rm.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to'  group by rm.id";

                                    }

                                     $data = $this->Slaves->query($sql);

                        $this->General->logData('rmOverAll',' week1 sql '.$sql);
                        $terSql = "SELECT ROUND(SUM(amount)/$weekAvg) as sale,trim(distributors.id) as rid,trim(distributors.company) as comp "
                                . "FROM `retailer_earning_logs`,distributors WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND retailer_earning_logs.date >= '$date_from' AND retailer_earning_logs.date <= '$date_to'  AND retailer_earning_logs.service_id IN ($service) group by distributors.id ";

                        if($this->params['url']['reporttype']=='rm'){

                                $terSql = "SELECT ROUND(SUM(amount)/$weekAvg) as sale,trim(rm.id) as rid "
                                        . "FROM `retailer_earning_logs`,distributors,rm WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND rm.id = distributors.rm_id AND distributors.id NOT IN (".DISTS.") AND retailer_earning_logs.date >= '$date_from' AND retailer_earning_logs.date <= '$date_to'  AND retailer_earning_logs.service_id IN ($service) group by rm.id ";
                        }
                        $data_ter = $this->Slaves->query($terSql);
                        $this->General->logData('rmOverAll',' week1 tertiery sql '.$terSql);

                        $list = array();

                        if($this->params['url']['reporttype'] != 'rm'){
                            /** IMP DATA ADDED : START**/
                            $dist_ids = array_map(function($element){
                                return $element['0']['rid'];
                            },$data);
                            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
                            /** IMP DATA ADDED : END**/
                        }

                        foreach($data as $dt){
                                if($this->params['url']['reporttype'] != 'rm'){
                                    $dt['0']['comp'] = $imp_data[$dt['0']['rid']]['imp']['shop_est_name'];
                                    $dt['distributors']['distname'] = $imp_data[$dt['0']['rid']]['imp']['name'];
                                }

                                $list[$dt['0']['rid']]['week1primary'] = $dt['0']['prim'];
                                $list[$dt['0']['rid']]['week1secondary'] = $dt['0']['second'];
                                $list[$dt['0']['rid']]['company'] = array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                                $list[$dt['0']['rid']]['week1tertiary'] = 0;
                                $list[$dt['0']['rid']]['id'] = empty($dt['0']['rid']) ? 0 : $dt['0']['rid'];
                                $list[$dt['0']['rid']]['distributor_name'] = array_key_exists('distname',$dt['distributors']) ?  $dt['distributors']['distname'] : "";
                                $list[$dt['0']['rid']]['rmname'] = $dt['rm']['rmname'];
                                $list[$dt['0']['rid']]['mobile'] =  $dt['users']['mobile'];
                                 $list[$dt['0']['rid']]['rmmobile'] =  $dt['rm']['rmmobile'];
                                $list[$dt['0']['rid']]['state'] = array_key_exists('state',$dt['distributors']) ?  $dt['distributors']['state'] : "";
                                $list[$dt['0']['rid']]['created_date'] = (array_key_exists('created_date',$dt['distributors'])) ?$dt['distributors']['created_date'] : $dt['rm']['created_date'];
                        }

                        if($this->params['url']['reporttype'] != 'rm'){
                            /** IMP DATA ADDED : START**/
                            $dist_ids = array_map(function($element){
                                return $element['0']['rid'];
                            },$data_ter);
                            $imp_data_ter = $this->Shop->getUserLabelData($dist_ids,2,3);
                            /** IMP DATA ADDED : END**/
                        }

                        foreach ($data_ter as $dt) {
                            if($this->params['url']['reporttype'] != 'rm'){
                                $dt['0']['comp'] = $imp_data_ter[$dt['0']['rid']]['imp']['shop_est_name'];
                            }
                            $list[$dt['0']['rid']]['week1tertiary'] = $dt['0']['sale'];
                            $list[$dt['0']['rid']]['company'] = array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                        }

                         if($weekCount>=2 || ($weekCount==1 && $reminderCount>=1)){
                              //week2

                                        $week[] = 'week2';
                                        $date_from = date('Y-m-d',strtotime($date_to.'+1 days')) ;
                                        $date_to = ($weekCount==1 && $reminderCount>=1 ) ? date('Y-m-d',strtotime($date_from.'+'.($reminderCount-1).' days'))  : date('Y-m-d',strtotime($date_from.'+6 days'));
                                        $weekAvg = ($weekCount==1 && $reminderCount>=1 ) ? $reminderCount : 7 ;

                                        $sql = "SELECT ROUND(SUM(topup_sold)/$weekAvg) as second,ROUND(SUM(topup_buy)/$weekAvg) as prim,trim(distributors.id) as rid,"
                                       . "trim(distributors.company) as comp,users.mobile,rm.mobile as rmmobile,distributors.name as distname,distributors.city,distributors.state,distributors.created as created_date,"
                                       . "distributors.margin as margin,rm.name as rmname FROM distributors,users_logs as distributors_logs,users,rm "
                                       . "WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND distributors.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to'  group by distributors_logs.user_id";

                                        if($this->params['url']['reporttype']=='rm'){

                                                 $sql = "SELECT ROUND(SUM(topup_sold)/$weekAvg) as second,ROUND(SUM(topup_buy)/$weekAvg) as prim,trim(rm.id) as rid,"
                                                            . "rm.mobile as rmmobile,rm.name as rmname,rm.mobile as rmmobile,rm.created as created_date "
                                                            . "FROM distributors,users_logs as distributors_logs,users,rm "
                                                            . "WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND rm.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to'  group by rm.id";
                                          }
                                        $data = $this->Slaves->query($sql);

                                        $this->General->logData('rmOverAll',' week1 sql '.$sql);
                                       $terSql = "SELECT ROUND(SUM(amount)/$weekAvg) as sale,trim(distributors.id) as rid,trim(distributors.company) as comp
                                                                                                       FROM `retailer_earning_logs`,distributors WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND retailer_earning_logs.date >= '$date_from' AND retailer_earning_logs.date <= '$date_to'  AND retailer_earning_logs.service_id IN ($service) group by distributors.id ";
                                       if($this->params['url']['reporttype']=='rm'){

                                            $terSql = "SELECT ROUND(SUM(amount)/$weekAvg) as sale,trim(rm.id) as rid "
                                                            . "FROM `retailer_earning_logs`,distributors,rm WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND rm.id = distributors.rm_id AND distributors.id NOT IN (".DISTS.") AND retailer_earning_logs.date >= '$date_from' AND retailer_earning_logs.date <= '$date_to'  AND retailer_earning_logs.service_id IN ($service) group by rm.id ";
                                        }
                                       $data_ter = $this->Slaves->query($terSql);
                                       $this->General->logData('rmOverAll',' week1 tertiery sql '.$terSql);

                                       if($this->params['url']['reporttype'] != 'rm'){
                                            /** IMP DATA ADDED : START**/
                                            $dist_ids = array_map(function($element){
                                                return $element['0']['rid'];
                                            },$data);
                                            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
                                            /** IMP DATA ADDED : END**/
                                        }

                                       foreach($data as $dt){
                                            if($this->params['url']['reporttype'] != 'rm'){
                                                $dt['0']['comp'] = $imp_data[$dt['0']['rid']]['imp']['shop_est_name'];
                                                $dt['distributors']['distname'] = $imp_data[$dt['0']['rid']]['imp']['name'];
                                            }
                                            $list[$dt['0']['rid']]['week2primary'] = $dt['0']['prim'];
                                            $list[$dt['0']['rid']]['week2secondary'] = $dt['0']['second'];
                                            $list[$dt['0']['rid']]['company'] =  array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                                            $list[$dt['0']['rid']]['week2tertiary'] = 0;
                                            $list[$dt['0']['rid']]['id'] = empty($dt['0']['rid']) ? 0 : $dt['0']['rid'];
                                            $list[$dt['0']['rid']]['mobile'] =  $dt['users']['mobile'];
                                            $list[$dt['0']['rid']]['rmmobile'] =  $dt['rm']['rmmobile'];
                                            $list[$dt['0']['rid']]['distributor_name'] = array_key_exists('distname',$dt['distributors']) ?  $dt['distributors']['distname'] : "";
                                            $list[$dt['0']['rid']]['rmname'] = $dt['rm']['rmname'];
                                            $list[$dt['0']['rid']]['state'] = array_key_exists('state',$dt['distributors']) ?  $dt['distributors']['state'] : "";
                                            $list[$dt['0']['rid']]['created_date'] = (array_key_exists('created_date',$dt['distributors'])) ?$dt['distributors']['created_date'] : $dt['rm']['created_date'];
                                    }

                                    if($this->params['url']['reporttype'] != 'rm'){
                                        /** IMP DATA ADDED : START**/
                                        $dist_ids = array_map(function($element){
                                            return $element['0']['rid'];
                                        },$data_ter);
                                        $imp_data_ter = $this->Shop->getUserLabelData($dist_ids,2,3);
                                        /** IMP DATA ADDED : END**/
                                    }

                                    foreach ($data_ter as $dt) {
                                        if($this->params['url']['reporttype'] != 'rm'){
                                            $dt['0']['comp'] = $imp_data_ter[$dt['0']['rid']]['imp']['shop_est_name'];
                                        }

                                        $list[$dt['0']['rid']]['week2tertiary'] = $dt['0']['sale'];
                                        $list[$dt['0']['rid']]['company'] = array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                                    }

                         }
                         if($weekCount>=3 || ($weekCount==2 && $reminderCount>=1)){
                             //week3
                                        $week[] = 'week3';
                                        $date_from = date('Y-m-d',strtotime($date_to.'+1 days')) ;
                                        $date_to = ($weekCount==2 && $reminderCount>=1 ) ? date('Y-m-d',strtotime($date_from.'+'.($reminderCount-1).' days'))  : date('Y-m-d',strtotime($date_from.'+6 days'));
                                       // $date_to = date('Y-m-d',strtotime($date_from.'+6 days')) ;
                                        $weekAvg = ($weekCount==2 && $reminderCount>=1 ) ? $reminderCount : 7 ;

                                       $sql = "SELECT ROUND(SUM(topup_sold)/$weekAvg) as second,ROUND(SUM(topup_buy)/$weekAvg) as prim,trim(distributors.id) as rid,"
                                       . "trim(distributors.company) as comp,users.mobile,distributors.name as distname,distributors.city,distributors.state,distributors.created as created_date,"
                                       . "distributors.margin as margin,rm.name as rmname,rm.mobile as rmmobile FROM distributors,users_logs as distributors_logs,users,rm "
                                       . "WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND distributors.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to'  group by distributors_logs.user_id";

                                       if($this->params['url']['reporttype']=='rm'){

                                             $sql = "SELECT ROUND(SUM(topup_sold)/$weekAvg) as second,ROUND(SUM(topup_buy)/$weekAvg) as prim,trim(rm.id) as rid,"
                                                            . "rm.mobile as rmmobile,rm.name as rmname,rm.created as created_date "
                                                            . "FROM distributors,users_logs as distributors_logs,users,rm "
                                                            . "WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND rm.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to'  group by rm.id";
                                        }
                                        $data = $this->Slaves->query($sql);

                                        $this->General->logData('rmOverAll',' week1 sql '.$sql);
                                       $terSql = "SELECT ROUND(SUM(amount)/$weekAvg) as sale,trim(distributors.id) as rid,trim(distributors.company) as comp
                                                                                                       FROM `retailer_earning_logs`,distributors WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND retailer_earning_logs.date >= '$date_from' AND retailer_earning_logs.date <= '$date_to'  AND retailer_earning_logs.service_id IN ($service) group by distributors.id ";
                                       if($this->params['url']['reporttype']=='rm'){

                                                    $terSql = "SELECT ROUND(SUM(amount)/$weekAvg) as sale,trim(rm.id) as rid "
                                                            . "FROM `retailer_earning_logs`,distributors,rm WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND rm.id = distributors.rm_id AND distributors.id NOT IN (".DISTS.") AND retailer_earning_logs.date >= '$date_from' AND retailer_earning_logs.date <= '$date_to'  AND retailer_earning_logs.service_id IN ($service) group by rm.id ";
                                        }
                                       $data_ter = $this->Slaves->query($terSql);
                                       $this->General->logData('rmOverAll',' week1 tertiery sql '.$terSql);

                                       if($this->params['url']['reporttype'] != 'rm'){
                                            /** IMP DATA ADDED : START**/
                                            $dist_ids = array_map(function($element){
                                                return $element['0']['rid'];
                                            },$data);
                                            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
                                            /** IMP DATA ADDED : END**/
                                        }

                                       foreach($data as $dt){
                                            if($this->params['url']['reporttype'] != 'rm'){
                                                $dt['0']['comp'] = $imp_data[$dt['0']['rid']]['imp']['shop_est_name'];
                                                $dt['distributors']['distname'] = $imp_data[$dt['0']['rid']]['imp']['name'];
                                            }
                                            $list[$dt['0']['rid']]['week3primary'] = $dt['0']['prim'];
                                            $list[$dt['0']['rid']]['week3secondary'] = $dt['0']['second'];
                                            $list[$dt['0']['rid']]['company'] = array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                                            $list[$dt['0']['rid']]['week3tertiary'] = 0;
                                            $list[$dt['0']['rid']]['id'] = empty($dt['0']['rid']) ? 0 : $dt['0']['rid'];
                                            $list[$dt['0']['rid']]['mobile'] =  $dt['users']['mobile'];
                                            $list[$dt['0']['rid']]['rmmobile'] =  $dt['rm']['rmmobile'];
                                            $list[$dt['0']['rid']]['distributor_name'] = array_key_exists('distname',$dt['distributors']) ?  $dt['distributors']['distname'] : "";
                                            $list[$dt['0']['rid']]['rmname'] = $dt['rm']['rmname'];
                                            $list[$dt['0']['rid']]['state'] =array_key_exists('state',$dt['distributors']) ?  $dt['distributors']['state'] : "";
                                            $list[$dt['0']['rid']]['created_date'] = (array_key_exists('created_date',$dt['distributors'])) ?$dt['distributors']['created_date'] : $dt['rm']['created_date'];
                                    }

                                    if($this->params['url']['reporttype'] != 'rm'){
                                        /** IMP DATA ADDED : START**/
                                        $dist_ids = array_map(function($element){
                                            return $element['0']['rid'];
                                        },$data_ter);
                                        $imp_data_ter = $this->Shop->getUserLabelData($dist_ids,2,3);
                                        /** IMP DATA ADDED : END**/
                                    }

                                    foreach ($data_ter as $dt) {
                                        if($this->params['url']['reporttype'] != 'rm'){
                                            $dt['0']['comp'] = $imp_data_ter[$dt['0']['rid']]['imp']['shop_est_name'];
                                        }
                                        $list[$dt['0']['rid']]['week3tertiary'] = $dt['0']['sale'];
                                        $list[$dt['0']['rid']]['company'] =array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                                    }
                         }
                        if($weekCount>=4 || ($weekCount==3 && $reminderCount>=1)){
                            //week4
                                        $week[] ='week4';
                                        $date_from = date('Y-m-d',strtotime($date_to.'+1 days')) ;
                                        $date_to = ($weekCount==3 && $reminderCount>=1 ) ? date('Y-m-d',strtotime($date_from.'+'.($reminderCount-1).' days'))  : date('Y-m-d',strtotime($date_from.'+6 days'));
                                        //$date_to = date('Y-m-d',strtotime($date_from.'+6 days')) ;
                                        $weekAvg = ($weekCount==3 && $reminderCount>=1 ) ? $reminderCount : 7 ;

                                        $sql = "SELECT ROUND(SUM(topup_sold)/$weekAvg) as second,ROUND(SUM(topup_buy)/$weekAvg) as prim,trim(distributors.id) as rid,"
                                       . "trim(distributors.company) as comp,users.mobile,distributors.name as distname,distributors.city,distributors.state,distributors.created as created_date,"
                                       . "distributors.margin as margin,rm.mobile as rmmobile,rm.name as rmname FROM distributors,users_logs as distributors_logs,users,rm "
                                       . "WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND distributors.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to'  group by distributors_logs.user_id";

                                        if($this->params['url']['reporttype']=='rm'){

                                             $sql = "SELECT ROUND(SUM(topup_sold)/$weekAvg) as second,ROUND(SUM(topup_buy)/$weekAvg) as prim,trim(rm.id) as rid,"
                                                            . "rm.mobile as rmmobile,rm.name as rmname,rm.created as created_date "
                                                            . "FROM distributors,users_logs as distributors_logs,users,rm "
                                                            . "WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND rm.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to'  group by rm.id";
                                        }
                                        $data = $this->Slaves->query($sql);

                                        $this->General->logData('rmOverAll',' week1 sql '.$sql);
                                       $terSql = "SELECT ROUND(SUM(amount)/$weekAvg) as sale,trim(distributors.id) as rid,trim(distributors.company) as comp
                                                                                                       FROM `retailer_earning_logs`,distributors WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND retailer_earning_logs.date >= '$date_from' AND retailer_earning_logs.date <= '$date_to'  AND retailer_earning_logs.service_id IN ($service) group by distributors.id ";
                                       if($this->params['url']['reporttype']=='rm'){

                                            $terSql = "SELECT ROUND(SUM(amount)/$weekAvg) as sale,trim(rm.id) as rid "
                                                            . "FROM `retailer_earning_logs`,distributors,rm WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND rm.id = distributors.rm_id AND distributors.id NOT IN (".DISTS.") AND retailer_earning_logs.date >= '$date_from' AND retailer_earning_logs.date <= '$date_to'  AND retailer_earning_logs.service_id IN ($service) group by rm.id ";
                                        }
                                       $data_ter = $this->Slaves->query($terSql);
                                       $this->General->logData('rmOverAll',' week1 tertiery sql '.$terSql);

                                        if($this->params['url']['reporttype'] != 'rm'){
                                            /** IMP DATA ADDED : START**/
                                            $dist_ids = array_map(function($element){
                                                return $element['0']['rid'];
                                            },$data);
                                            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
                                            /** IMP DATA ADDED : END**/
                                        }

                                       foreach($data as $dt){
                                            if($this->params['url']['reporttype'] != 'rm'){
                                                $dt['0']['comp'] = $imp_data[$dt['0']['rid']]['imp']['shop_est_name'];
                                                $dt['distributors']['distname'] = $imp_data[$dt['0']['rid']]['imp']['name'];
                                            }
                                            $list[$dt['0']['rid']]['week4primary'] = $dt['0']['prim'];
                                            $list[$dt['0']['rid']]['week4secondary'] = $dt['0']['second'];
                                            $list[$dt['0']['rid']]['company'] = array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                                            $list[$dt['0']['rid']]['week4tertiary'] = 0;
                                            $list[$dt['0']['rid']]['id'] = empty($dt['0']['rid']) ? 0 : $dt['0']['rid'];
                                            $list[$dt['0']['rid']]['mobile'] =  $dt['users']['mobile'];
                                            $list[$dt['0']['rid']]['rmmobile'] =  $dt['rm']['rmmobile'];
                                            $list[$dt['0']['rid']]['distributor_name'] = array_key_exists('distname',$dt['distributors']) ?  $dt['distributors']['distname'] : "";
                                            $list[$dt['0']['rid']]['rmname'] = $dt['rm']['rmname'];
                                            $list[$dt['0']['rid']]['state'] = array_key_exists('state',$dt['distributors']) ?  $dt['distributors']['state'] : "";
                                            $list[$dt['0']['rid']]['created_date'] =(array_key_exists('created_date',$dt['distributors'])) ?$dt['distributors']['created_date'] : $dt['rm']['created_date'];
                                    }
                                    if($this->params['url']['reporttype'] != 'rm'){
                                        /** IMP DATA ADDED : START**/
                                        $dist_ids = array_map(function($element){
                                            return $element['0']['rid'];
                                        },$data_ter);
                                        $imp_data_ter = $this->Shop->getUserLabelData($dist_ids,2,3);
                                        /** IMP DATA ADDED : END**/
                                    }

                                    foreach ($data_ter as $dt) {
                                        if($this->params['url']['reporttype'] != 'rm'){
                                            $dt['0']['comp'] = $imp_data_ter[$dt['0']['rid']]['imp']['shop_est_name'];
                                        }

                                        $list[$dt['0']['rid']]['week4tertiary'] = $dt['0']['sale'];
                                        $list[$dt['0']['rid']]['company'] = array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                                    }
                         }
                          if($weekCount>=5 || ($weekCount==4 && $reminderCount>=1)){
                           //week5
                                        $week[] = 'week5';
                                        $days = $reminderCount;
                                        $date_from = date('Y-m-d',strtotime($date_to.'+1 days')) ;
                                        $date_to = ($weekCount==4 && $reminderCount>=1 ) ? date('Y-m-d',strtotime($date_from.'+'.($reminderCount-1).' days'))  : date('Y-m-d',strtotime($date_from.'+6 days'));
                                        //$date_to = date('Y-m-d',strtotime($date_from."+$reminderCount days")) ;
                                        $weekAvg = ($weekCount==4 && $reminderCount>=1 ) ? $reminderCount : 7 ;
                                        $sql = "SELECT ROUND(SUM(topup_sold)/$weekAvg) as second,ROUND(SUM(topup_buy)/$weekAvg) as prim,trim(distributors.id) as rid,"
                                       . "trim(distributors.company) as comp,users.mobile,rm.mobile as rmmobile,distributors.name as distname,distributors.city,distributors.state,distributors.created as created_date,"
                                       . "distributors.margin as margin,rm.name as rmname FROM distributors,users_logs as distributors_logs,users,rm "
                                       . "WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND distributors.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to'  group by distributors_logs.user_id";

                                        if($this->params['url']['reporttype']=='rm'){

                                             $sql = "SELECT ROUND(SUM(topup_sold)/$weekAvg) as second,ROUND(SUM(topup_buy)/$weekAvg) as prim,trim(rm.id) as rid,"
                                                            . "rm.mobile as rmmobile,rm.name as rmname,rm.created as created_date "
                                                            . "FROM distributors,users_logs as distributors_logs,users,rm "
                                                            . "WHERE $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND rm.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to'  group by rm.id";
                                        }
                                        $data = $this->Slaves->query($sql);

                                        $this->General->logData('rmOverAll',' week1 sql '.$sql);
                                       $terSql = "SELECT ROUND(SUM(amount)/$weekAvg) as sale,trim(distributors.id) as rid,trim(distributors.company) as comp
                                                                                                       FROM `retailer_earning_logs`,distributors WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND distributors.id NOT IN (".DISTS.") AND retailer_earning_logs.date >= '$date_from' AND retailer_earning_logs.date <= '$date_to'  AND retailer_earning_logs.service_id IN ($service) group by distributors.id ";
                                       if($this->params['url']['reporttype']=='rm'){

                                            $terSql = "SELECT ROUND(SUM(amount)/$weekAvg) as sale,trim(rm.id) as rid "
                                                            . "FROM `retailer_earning_logs`,distributors,rm WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND $rm_sd_cond AND rm.id = distributors.rm_id AND distributors.id NOT IN (".DISTS.") AND retailer_earning_logs.date >= '$date_from' AND retailer_earning_logs.date <= '$date_to'  AND retailer_earning_logs.service_id IN ($service) group by rm.id ";
                                        }
                                       $data_ter = $this->Slaves->query($terSql);
                                       $this->General->logData('rmOverAll',' week1 tertiery sql '.$terSql);

                                        if($this->params['url']['reporttype'] != 'rm'){
                                            /** IMP DATA ADDED : START**/
                                            $dist_ids = array_map(function($element){
                                                return $element['0']['rid'];
                                            },$data);
                                            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
                                            /** IMP DATA ADDED : END**/
                                        }

                                       foreach($data as $dt){

                                            if($this->params['url']['reporttype'] != 'rm'){
                                                $dt['0']['comp'] = $imp_data[$dt['0']['rid']]['imp']['shop_est_name'];
                                                $dt['distributors']['distname'] = $imp_data[$dt['0']['rid']]['imp']['name'];
                                            }
                                            $list[$dt['0']['rid']]['week5primary'] = $dt['0']['prim'];
                                            $list[$dt['0']['rid']]['week5secondary'] = $dt['0']['second'];
                                            $list[$dt['0']['rid']]['company'] = array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                                            $list[$dt['0']['rid']]['week5tertiary'] = 0;
                                            $list[$dt['0']['rid']]['id'] = empty($dt['0']['rid']) ? 0 : $dt['0']['rid'];
                                            $list[$dt['0']['rid']]['mobile'] =  $dt['users']['mobile'];
                                            $list[$dt['0']['rid']]['rmmobile'] =  $dt['rm']['rmmobile'];
                                            $list[$dt['0']['rid']]['distributor_name'] = array_key_exists('distname',$dt['distributors']) ?  $dt['distributors']['distname'] : "";
                                            $list[$dt['0']['rid']]['state'] = array_key_exists('state',$dt['distributors']) ?  $dt['distributors']['state'] : "";
                                            $list[$dt['0']['rid']]['rmname'] = $dt['rm']['rmname'];
                                            $list[$dt['0']['rid']]['created_date'] = (array_key_exists('created_date',$dt['distributors'])) ?$dt['distributors']['created_date'] : $dt['rm']['created_date'];
                                    }

                                    if($this->params['url']['reporttype'] != 'rm'){
                                        /** IMP DATA ADDED : START**/
                                        $dist_ids = array_map(function($element){
                                            return $element['0']['rid'];
                                        },$data_ter);
                                        $imp_data_ter = $this->Shop->getUserLabelData($dist_ids,2,3);
                                        /** IMP DATA ADDED : END**/
                                    }

                                    foreach ($data_ter as $dt) {
                                        if($this->params['url']['reporttype'] != 'rm'){
                                            $dt['0']['comp'] = $imp_data_ter[$dt['0']['rid']]['imp']['shop_est_name'];
                                        }
                                        $list[$dt['0']['rid']]['week5tertiary'] = $dt['0']['sale'];
                                        $list[$dt['0']['rid']]['company'] =  array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                                    }
                         }

                    //$rmdetails = $this->Slaves->query("SELECT rm.name,distributors.id from rm left join distributors on rm.id = distributors.created_rm_id where rm.id = '".$this->info['id']."'");

                    if($this->params['url']['reporttype'] != 'rm'){
                        /** IMP DATA ADDED : START**/
                        $dist_ids = array_map(function($element){
                            return $element['0']['rid'];
                        },$prevdata);
                        $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
                        /** IMP DATA ADDED : END**/
                    }
                    foreach($prevdata as $dt){
                            if($this->params['url']['reporttype'] != 'rm'){
                                $dt['0']['comp'] = $imp_data[$dt['0']['rid']]['imp']['shop_est_name'];
                                $dt['distributors']['distname'] = $imp_data[$dt['0']['rid']]['imp']['name'];
                            }
                            $list[$dt['0']['rid']]['prev_primary'] = $dt['0']['prim'];
                            $list[$dt['0']['rid']]['prev_secondary'] = $dt['0']['second'];

                            $list[$dt['0']['rid']]['company'] =  array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                            $list[$dt['0']['rid']]['prev_tertiary'] = 0;
                            $list[$dt['0']['rid']]['distributor_name'] =  array_key_exists('distname',$dt['distributors']) ?  $dt['distributors']['distname'] : "";
                            $list[$dt['0']['rid']]['rmname'] = $dt['rm']['rmname'];
                            $list[$dt['0']['rid']]['state'] =  array_key_exists('state',$dt['distributors']) ?  $dt['distributors']['state'] : "";
                            $list[$dt['0']['rid']]['created_date'] =  (array_key_exists('created_date',$dt['distributors'])) ?$dt['distributors']['created_date'] : $dt['rm']['created_date'];
                    }
                    if($this->params['url']['reporttype'] != 'rm'){
                        /** IMP DATA ADDED : START**/
                        $dist_ids = array_map(function($element){
                            return $element['0']['rid'];
                        },$prev_data_ter);
                        $imp_data_prev_ter = $this->Shop->getUserLabelData($dist_ids,2,3);
                        /** IMP DATA ADDED : END**/
                    }
                    foreach ($prev_data_ter as $dt) {
                            if($this->params['url']['reporttype'] != 'rm'){
                                $dt['0']['comp'] = $imp_data_prev_ter[$dt['0']['rid']]['imp']['shop_est_name'];
                            }
                            $list[$dt['0']['rid']]['prev_tertiary'] = $dt['0']['sale'];
                            $list[$dt['0']['rid']]['company'] = array_key_exists('comp',$dt['0']) ?  $dt['0']['comp'] : "";
                    }

                     $pageType = empty($this->params['url']['res_type']) ? "" : ($this->params['url']['res_type']);

                    if($pageType == 'csv'){
                        App::import('Helper','csv');
                        $this->layout = null;
                        $this->autoLayout = false;
                        $csv = new CsvHelper();
                        $line = array("S.No.");
                        if($this->params['url']['reporttype']!='rm'){
                            array_push($line,"Distributor ","State");
                        }

                        array_push($line,"RM ","Prev Primary","Prev Secondary","Prev Tertiery","Week1 Primary","Week1 Secondary","Week1 Tertiery","Tertiery Difference");

                        if(in_array('week2', $week)){
                               array_push($line,'Week2 primary','Week2 secondary','Week2 tertiery',"Tertiery Difference");
                        }
                        if(in_array('week3', $week)){
                               array_push($line,'Week3 primary','Week3 secondary','Week3 tertiery',"Tertiery Difference");
                        }
                        if(in_array('week4', $week)){
                               array_push($line,'Week4 primary','Week4 secondary','Week4 tertiery',"Tertiery Difference");
                        }
                        if(in_array('week5', $week)){
                               array_push($line,'Week5 primary','Week5 secondary','Week5 tertiery',"Tertiery Difference");
                        }

                        $csv->addRow($line);
                        $i=1;
                        foreach($list as $id => $data) {
                            $temp = array();
                              if(isset($data['id'])) {

                                    $company = $data['company'];
                                    $statename = $data['state'];
                                    $rmname = $data['rmname'];
                                    $prevPrimary = $data['prev_primary'];
                                    $prevSecondary = $data['prev_secondary'];
                                    $prevTertiery = $data['prev_tertiary'];

                                    $week1primary = $data['week1primary'];
                                    $week1secondary = $data['week1secondary'];
                                    $week1tertiary = $data['week1tertiary'];
                                    $week1tertiarydiff = ($data['week1tertiary']-$data['prev_tertiary']);
                                    $temp= array($i);


                                    if($this->params['url']['reporttype']!=='rm'){
                                        array_push($temp,$company, $statename);
                                    }
                                    array_push($temp,$rmname, $prevPrimary, $prevSecondary, $prevTertiery, $week1primary, $week1secondary, $week1tertiary,$week1tertiarydiff);
                                    if(in_array('week2', $week)){

                                        $week2primary = $data['week2primary'];
                                        $week2secondary = $data['week2secondary'];
                                        $week2tertiary = $data['week2tertiary'];
                                        $week2tertiarydiff = ($data['week2tertiary']-$data['prev_tertiary']);
                                        array_push($temp,$week2primary, $week2secondary, $week2tertiary,$week2tertiarydiff);
                                    }
                                    if(in_array('week3', $week)){

                                        $week3primary = $data['week3primary'];
                                        $week3secondary = $data['week3secondary'];
                                        $week3tertiary = $data['week3tertiary'];
                                        $week3tertiarydiff = ($data['week3tertiary']-$data['prev_tertiary']);
                                        array_push($temp,$week3primary, $week3secondary, $week3tertiary,$week3tertiarydiff);
                                    }
                                    if(in_array('week4', $week)){

                                        $week4primary = $data['week4primary'];
                                        $week4secondary = $data['week4secondary'];
                                        $week4tertiary = $data['week4tertiary'];
                                        $week4tertiarydiff = ($data['week4tertiary']-$data['prev_tertiary']);
                                        array_push($temp,$week4primary, $week4secondary, $week4tertiary,$week4tertiarydiff);
                                    }
                                    if(in_array('week5', $week)){

                                        $week5primary = $data['week5primary'];
                                        $week5secondary = $data['week5secondary'];
                                        $week5tertiary = $data['week5tertiary'];
                                        $week5tertiarydiff = ($data['week5tertiary']-$data['prev_tertiary']);
                                        array_push($temp,$week5primary, $week5secondary, $week5tertiary,$week5tertiarydiff);
                                    }
                                  $csv->addRow($temp);
                                  $i++;

                              }
                        }

                        echo $csv->render('performance_report'.date('YmdHis').'.csv');die;
                    }

            }
            }

             $services = $this->Shop->getAllServices();

             $this->set('selected_label',$service);
             $this->set('labels',$services);
             $this->set('week',$week);
             $this->set('activated',$this->params['url']['active_inactive']);
             $this->set('type',$this->params['url']['reporttype']);
            $this->set('to',array_key_exists('to',$this->params['url']) ?  trim($this->params['url']['to']) : null);
            $this->set('from',array_key_exists('from',$this->params['url']) ?  trim($this->params['url']['from']) : null);
            $this->set('datas',$list);



           // $this->render('overall');
        //}
    }

    function rmProposition($dist_id){
        if( $this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER || $this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR ){
            $this->render('rmproposition');
        }
    }
    function rmSupport($dist_id){
        if( $this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER || $this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR ){
            $this->render('rmsupport');
        }
    }

    function distProfile(){
        if( $this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER || $this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR ){
            $dist_id = null;
            if( array_key_exists('dist',$this->params['url']) ){
                $dist_id = trim($this->params['url']['dist']);
            }

            if($dist_id){
                $all_dists = array();
                foreach( array_map('current',$this->ds) as $key => $value ){
                    $all_dists[$value['id']] = $value['company'];
                    if($value['id'] == $dist_id){
                        $data = $this->Shop->getUserLabelData($value['user_id'],2);
                        $this->set('dist_details',$data[$value['user_id']]);
                    }
                }
            } else {
                $this->redirect('/shops/rmSalesReport');
            }

            $this->set('dist_id',$dist_id);
            $this->set('all_dists',$all_dists);
            $this->render('distprofile');
        }
    }

    function updateDistProfile($dist_user_id = null,$dist_id = null){
        if( $this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER || $this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR ){
            if($dist_user_id && $dist_id && $this->params['form']){
                $response = $this->Shop->updateUserLabelData($dist_user_id,$this->params['form'],$this->Session->read('Auth.User.id'),0);
                $this->redirect('/shops/distProfile?dist='.$dist_id);
            }
        }
    }



    function distSales(){
        if( $this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER || $this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR ){

            $dist_id = null;
            if( array_key_exists('dist',$this->params['url']) ){
                $dist_id = trim($this->params['url']['dist']);
            }

            if($dist_id){
                $all_dists = array();
                foreach( array_map('current',$this->ds) as $key => $value ){
                    $all_dists[$value['id']] = $value['company'];
                    if($value['id'] == $dist_id){
                        $this->set('dist_name',$value['company']);
                        $this->set('dist_mobile',$value['mobile']);
                        $dist_user_id = $value['user_id'];
                    }
                }

                // echo '<pre>';
                // print_r($dist_user_id);
                // echo '<br>';
                // print_r($dist_id);
                // print_r($all_dists);
                // exit;

            if($dist_user_id){

                Configure::load('product_config');
                $services = array_map(function($element){
                    return $element['name'];
                },Configure::read('services'));
                unset($services[2],$services[4],$services[6],$services[7],$services[9],$services[10]);

                // $selected_service = null;
                // if( array_key_exists('service',$this->params['url']) &&  in_array(trim(strtolower($this->params['url']['service'])),array_map('strtolower',$services)) ){
                //     $selected_service = array_search(trim(strtolower($this->params['url']['service'])),array_map('strtolower',$services));
                // }

                // if($selected_service){

                    $sale_from = date('Y').'-04-01';
                    $sale_to = date('Y-m-d');
                    // $sale_to = '2017-02-02';

                    if( ((strtotime($sale_to) - strtotime($sale_from))/(60 * 60 * 24)) < 0 ){
                        $sale_from = date('Y',strtotime('-1 year')).'-04-01';
                    }

                    if( array_key_exists('sale_from',$this->params['url']) && !empty($this->params['url']['sale_from']) ){
                        if( array_key_exists('sale_to',$this->params['url']) && !empty($this->params['url']['sale_to']) ){
                            $sale_from = trim($this->params['url']['sale_from']).'-01';
                            $sale_to = trim($this->params['url']['sale_to']).'-'.date('t',strtotime(trim($this->params['url']['sale_to']).'-01'));
                        }
                    }

                    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$sale_from) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$sale_to) ) {

                        // $sales = $this->Shop->getRmCurrentSaleEarningServiceWise(null,$dist_id,$dist_user_id,$selected_service,$dist_user_id,null,null,$sale_from,$sale_to);
                        // $sales = $this->Shop->getRmCurrentSaleEarningServiceWise(null,$dist_id,$dist_user_id,null,$dist_user_id,null,null,$sale_from,$sale_to);

                    // echo '<pre>';
                    // print_r($sales);
                    // exit;

                        $start = new DateTime($sale_from);
                        $start->modify('first day of this month');
                        $end   = new DateTime($sale_to);
                        $end->modify('first day of next month');
                        $interval = DateInterval::createFromDateString('1 month');
                        $period = new DatePeriod($start, $interval, $end);
                        $sale_duration_short = array();
                        $sale_duration_full = array();

                        foreach( $period as $dt ){
                            $sale_duration_short[] = $dt->format("Y-m");
                            $sale_duration_full[$dt->format("Y-m")] = $dt->format("Y-M");
                        }

                        krsort($sale_duration_full);

                        // echo '<pre>';
                        // print_r($sale_duration_full);
                        // print_r($sales[$dist_id][$selected_service]);
                        // exit;

                        $service_target_map = array(
                            1  => 'recharge',
                            8  => 'mpos',
                            12 => 'dmt',
                            13 => 'smartbuy'
                        );

                        // if( $sales && count($sales) > 0){
                            foreach ($sale_duration_full as $key => $label) {
                                $month_year = explode('-',$key);
                                $schemes = $this->Scheme->getScheme($dist_id,$month_year[1],$month_year[0]);

                                if( count($schemes[$dist_id]['target']) > 0 ){
                                    foreach ( $schemes[$dist_id]['target'] as $key => $target) {
                                        // if(!empty($target[$service_target_map[$selected_service]])){
                                                $sales[$dist_id][1][$month_year[0]][$month_year[1]]['targets'][ucfirst($key)] = $target[$service_target_map[1]];
                                                $sales[$dist_id][1][$month_year[0]][$month_year[1]]['target_incentives'][ucfirst($key)]= $target['incentive_ex'];
                                                $sales[$dist_id][12][$month_year[0]][$month_year[1]]['targets'][ucfirst($key)] = $target[$service_target_map[12]];
                                                $sales[$dist_id][13][$month_year[0]][$month_year[1]]['targets'][ucfirst($key)] = $target[$service_target_map[13]];
                                                $sales[$dist_id][8][$month_year[0]][$month_year[1]]['targets'][ucfirst($key)] = $target[$service_target_map[8]];

                                        // }
                                    }
                                }
                            }
                        // }

                        // echo '<pre>';
                        // print_r($sales);
                        // exit;

                        $this->set('sale_duration_full',$sale_duration_full);
                        $this->set('sale_from',current($sale_duration_short));
                        $this->set('sale_to',end($sale_duration_short));
                        $this->set('selected_service',$selected_service);
                        $this->set('sales',$sales[$dist_id]);

                    } else {
                        $this->set('validation_error','Invalid date range selected !!');
                    }
                // } else {
                //     $this->set('validation_error','Invalid service selected !!');
                // }

            } else {
                $this->redirect('/shops/rmSalesReport');
            }
        } else {
            $this->redirect('/shops/rmSalesReport');
        }

            // $this->set('months',array(
            //     1 => "Jan", 2 => "Feb", 3 => "March", 4=> "April", 5 =>"May",
            //     6 => "June",7 => "July",8 => "Aug", 9 => "Sept",10 => "Oct",
            //     11 => "Nov",12 => "Dec")
            // );



            $this->set('all_dists',$all_dists);
            $this->set('dist_id',$dist_id);
            $this->set('dist_user_id',$dist_user_id);
            $this->set('services',$services);
            $this->render('distsales');
        }
    }


    function allDistributor(){

		if($this->Session->read('Auth.User.group_id') == ADMIN){
			$data=$this->Slaves->query("SELECT sum(st.amount) as amts,source_id from shop_transactions as st where st.type='".COMMISSION_MASTERDISTRIBUTOR."' AND st.date= '".date('Y-m-d')."' group by st.source_id");
                       //}else if($this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER ){
			//$distributors = $this->Distributor->find('all',array('conditions' => array('parent_id' => $this->info['id']), 'order' => 'name asc'));
			//      $data=$this->Retailer->query("SELECT sum(st.amount) as amts,source_id from shop_transactions as st where st.type='".COMMISSION_DISTRIBUTOR."' AND st.date= '".date('Y-m-d')."' group by st.source_id");
		}else {
//			$data=$this->Slaves->query("SELECT sum(st.amount) as amts,source_id from shop_transactions as st where st.type='".COMMISSION_DISTRIBUTOR."' AND st.date= '".date('Y-m-d')."' group by st.source_id");
			$data=$this->Slaves->query("SELECT sum(ul.amount-ul.txn_reverse_amt) as amts,st.id as source_id from users_nontxn_logs as ul join distributors st on (ul.user_id = st.user_id) where ul.type='".COMMISSION_DISTRIBUTOR."' AND ul.date= '".date('Y-m-d')."' group by ul.user_id");
               }

		$datas = array();
		foreach($data as $dt){
			$datas[$dt['st']['source_id']] = $dt['0']['amts'];
        }

        $this->set('datas',$datas);
		$this->render('alldistributor');
	}

	function allSuperDistributor(){

		/*********************************Super Distributor*******************************/

			$this->SuperDistributor->recursive = -1;
			$rm_condition = 1 ;
			if($this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER){
				$rm_condition = "SuperDistributor.rm_id = ".$this->info['id'];
			}


			$super_distributors = $this->SuperDistributor->find('all', array(
					'fields' => array('users.*', 'slabs.name', 'SuperDistributor.id','SuperDistributor.user_id', 'SuperDistributor.max_limit', 'SuperDistributor.no_distributors_limit', 'SuperDistributor.active_flag', 'SuperDistributor.created', 'rm.name', 'locator_city.name', 'locator_state.name'),
					'conditions' => array('SuperDistributor.active_flag' => 1,$rm_condition),
					'joins' => array(
							array(
									'table' => 'slabs',
									'type' => 'inner',
									'conditions' => array('SuperDistributor.slab_id = slabs.id')
							),
							array(
									'table' => 'users',
									'type' => 'inner',
									'conditions' => array('SuperDistributor.user_id = users.id')
							),
							array(
									'table' => 'rm',
									'type' => 'left',
									'conditions' => array('SuperDistributor.rm_id = rm.id')
							),
							array(
									'table' => 'retailers_location',
									'type' => 'left',
									'conditions' => array('SuperDistributor.user_id = retailers_location.user_id')
							),
							array(
									'table' => 'locator_area',
									'type' => 'left',
									'conditions' => array('retailers_location.area_id = locator_area.id')
							),
							array(
									'table' => 'locator_city',
									'type' => 'left',
									'conditions' => array('locator_city.id = locator_area.city_id')
							),
							array(
									'table' => 'locator_state',
									'type' => 'left',
									'conditions' => array('locator_state.id = locator_city.state_id')
							)
					),
					'order' => 'SuperDistributor.active_flag desc',
					'group' => 'SuperDistributor.id'
					)
			);
            /** IMP DATA ADDED : START**/


            $super_dist_ids = array_map(function($element){
                return $element['SuperDistributor']['user_id'];
            },$super_distributors);


            $imp_data = $this->Shop->getUserLabelData($super_dist_ids);

            foreach ($super_distributors as $key => $super_distributor) {
                        $super_distributors[$key]['SuperDistributor']['company'] = $imp_data[$super_distributor['SuperDistributor']['user_id']]['imp']['shop_est_name'];
                        $super_distributors[$key]['SuperDistributor']['shopname'] = $imp_data[$super_distributor['SuperDistributor']['user_id']]['imp']['shop_est_name'];
                        $super_distributors[$key]['SuperDistributor']['city'] = $super_distributor['locator_city']['name'];
            }

            /** IMP DATA ADDED : END**/

			$super_distarray = array();

			if($this->Session->read('Auth.User.group_id') == RELATIONSHIP_MANAGER){
				$md_id = 3;
			}else{
				$md_id = $this->info['id'];
			}
			$query = $this->Slaves->query("SELECT sum(amount) as xfer,super_distributors.id as SdId FROM shop_transactions as users_logs JOIN super_distributors ON (users_logs.target_id = super_distributors.id) JOIN master_distributors ON (users_logs.source_id = master_distributors.id) WHERE users_logs.confirm_flag = 0 AND users_logs.type = ".MDIST_SDIST_BALANCE_TRANSFER." AND users_logs.date = '".date('Y-m-d')."' AND master_distributors.id = ".$md_id." GROUP BY SdId");      



			foreach ($query as $key){
				$super_distarray[$key['super_distributors']['SdId']] = $key[0]['xfer'];
			}
			$record =  array();
			foreach ($super_distributors as $dis) {
                                $dis['SuperDistributor']['balance'] = $dis['users']['balance'];
                                $dis['SuperDistributor']['opening_balance'] = $dis['users']['opening_balance'];
				$record[$dis['SuperDistributor']['id']] = $dis;
				if(!empty($super_distarray[$dis['SuperDistributor']['id']])){
				$record[$dis['SuperDistributor']['id']][]['xfer'] = $super_distarray[$dis['SuperDistributor']['id']];
				} else {
					$record[$dis['SuperDistributor']['id']][]['xfer'] = 0;
				}
			}
			$SuperDistRecords = $record;


			$this->set('super_distributors',$SuperDistRecords);
			$this->set('SDrecords',$SuperDistRecords);
			$this->set('SDmodelName','SuperDistributor');
			$this->ds = $SuperDistRecords;

			/*********************************End Super Distributor*******************************/

		$this->render('allsuperdistributor');
	}

        function convertDistToNewSystem() {

                $dist_id = trim($_POST['dist_id']);
                $user_id = trim($_POST['user_id']);

                if (is_numeric($dist_id) && is_numeric($user_id)) {
                        $this->User->query("UPDATE salesmen SET tran_limit = '0', balance = '0' WHERE dist_id = '".$dist_id."'");

                        $res = $this->User->query("UPDATE distributors SET system_used = '1' WHERE id = '".$dist_id."'");

                        $this->General->logoutUser($user_id,DISTRIBUTOR);
                }

                echo $res;

                $this->autoRender = false;
        }



	function retFilter(){

		$filter = $_REQUEST['filter'];
		$id = $_REQUEST['id'];
		$query = "";
		if($this->Session->read('Auth.User.group_id') == DISTRIBUTOR){
			if($id == 0)$id = null;
			else if($id != null)$query = " AND retailers.maint_salesman = $id";
			$dist = $this->info['id'];
		}
		else {
			$dist = $id;
		}

		if($filter == 1){//top transacting last 7 days
//			$ids = $this->Slaves->query("SELECT retailers.id FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers_logs.date >= '".date('Y-m-d',strtotime('-8 days'))."' $query AND retailers.parent_id=$dist group by retailer_id having (avg(sale) > 1000)");
			$ids = $this->Slaves->query("SELECT retailers.id "
                                . "FROM retailer_earning_logs rel "
                                . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                                . "WHERE rel.date >= '".date('Y-m-d',strtotime('-8 days'))."' "
                                . "$query "
                                . "AND d.id = $dist "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "GROUP BY retailers.id "
                                . "HAVING (AVG(rel.amount) > 1000)");
		}
		else if($filter == 2){//avg transacting last 7 days
//			$ids = $this->Slaves->query("SELECT retailers.id FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers_logs.date >= '".date('Y-m-d',strtotime('-8 days'))."' $query AND retailers.parent_id=$dist group by retailer_id having (avg(sale) <= 1000 AND avg(sale) > 500)");
			$ids = $this->Slaves->query("SELECT retailers.id "
                                . "FROM retailer_earning_logs rel "
                                . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                                . "WHERE rel.date >= '".date('Y-m-d',strtotime('-8 days'))."' "
                                . "$query "
                                . "AND d.id = $dist "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "GROUP BY retailers.id "
                                . "HAVING (AVG(rel.amount) <= 1000 AND AVG(rel.amount) > 500)");
		}
		else if($filter == 3){//low transacting last 7 days
//			$ids = $this->Slaves->query("SELECT retailers.id FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers_logs.date >= '".date('Y-m-d',strtotime('-8 days'))."' $query AND retailers.parent_id=$dist group by retailer_id having (avg(sale) <= 500)");
			$ids = $this->Slaves->query("SELECT retailers.id "
                                . "FROM retailer_earning_logs rel "
                                . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                                . "WHERE rel.date >= '".date('Y-m-d',strtotime('-8 days'))."' "
                                . "$query "
                                . "AND d.id = $dist "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "GROUP BY retailers.id "
                                . "HAVING (AVG(rel.amount) <= 500)");
		}
		else if($filter == 4){//dropped in last 2 days
			//$ids = $this->Retailer->query("SELECT retailers.id FROM vendors_activations,retailers WHERE retailers.id = vendors_activations.retailer_id $query AND retailers.parent_id=$dist AND vendors_activations.date >= '".date('Y-m-d',strtotime('-2 days'))."' group by retailer_id having (max(vendors_activations.date) = '".date('Y-m-d',strtotime('-2 days'))."')");
//			$ids = $this->Slaves->query("SELECT retailers.id FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id $query AND retailers.parent_id=$dist AND retailers_logs.date >= '".date('Y-m-d',strtotime('-2 days'))."' group by retailer_id having (max(retailers_logs.date) = '".date('Y-m-d',strtotime('-2 days'))."')");
			$ids = $this->Slaves->query("SELECT retailers.id "
                                . "FROM retailer_earning_logs rel "
                                . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                                . "WHERE 1 "
                                . "$query "
                                . "AND d.id = $dist "
                                . "AND rel.date >= '".date('Y-m-d',strtotime('-2 days'))."' "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "GROUP BY retailers.id "
                                . "HAVING (MAX(rel.date) = '".date('Y-m-d',strtotime('-2 days'))."')");
		}
		else if($filter == 5){//dropped in last 7 days
			//$ids = $this->Retailer->query("SELECT retailers.id FROM vendors_activations,retailers WHERE retailers.id = vendors_activations.retailer_id $query AND retailers.parent_id=$dist AND vendors_activations.date >= '".date('Y-m-d',strtotime('-7 days'))."' group by retailer_id having (max(vendors_activations.date) >= '".date('Y-m-d',strtotime('-7 days'))."' AND max(vendors_activations.date) <= '".date('Y-m-d',strtotime('-2 days'))."')");
//			$ids = $this->Slaves->query("SELECT retailers.id FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id $query AND retailers.parent_id=$dist AND retailers_logs.date >= '".date('Y-m-d',strtotime('-7 days'))."' group by retailer_id having (max(retailers_logs.date) >= '".date('Y-m-d',strtotime('-7 days'))."' AND max(retailers_logs.date) < '".date('Y-m-d',strtotime('-2 days'))."')");
			$ids = $this->Slaves->query("SELECT retailers.id "
                                . "FROM retailer_earning_logs rel"
                                . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                                . "WHERE 1 "
                                . "$query "
                                . "AND d.id = $dist "
                                . "AND rel.date >= '".date('Y-m-d',strtotime('-7 days'))."' "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "GROUP BY retailers.id "
                                . "HAVING (MAX(rel.date) >= '".date('Y-m-d',strtotime('-7 days'))."' "
                                . "AND MAX(rel.date) < '".date('Y-m-d',strtotime('-2 days'))."')");
		}
		else if($filter == 6){//dropped between last 7-14 days
			//$ids = $this->Retailer->query("SELECT retailers.id FROM vendors_activations,retailers WHERE retailers.id = vendors_activations.retailer_id $query AND retailers.parent_id=$dist AND vendors_activations.date >= '".date('Y-m-d',strtotime('-14 days'))."' group by retailer_id having (max(vendors_activations.date) >= '".date('Y-m-d',strtotime('-14 days'))."' AND max(vendors_activations.date) < '".date('Y-m-d',strtotime('-7 days'))."')");
//			$ids = $this->Slaves->query("SELECT retailers.id FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id $query AND retailers.parent_id=$dist AND retailers_logs.date >= '".date('Y-m-d',strtotime('-14 days'))."' group by retailer_id having (max(retailers_logs.date) >= '".date('Y-m-d',strtotime('-14 days'))."' AND max(retailers_logs.date) < '".date('Y-m-d',strtotime('-7 days'))."')");
			$ids = $this->Slaves->query("SELECT retailers.id "
                                . "FROM retailer_earning_logs rel "
                                . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                                . "WHERE 1 "
                                . "$query "
                                . "AND d.id = $dist "
                                . "AND rel.date >= '".date('Y-m-d',strtotime('-14 days'))."' "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "GROUP BY retailers.id "
                                . "HAVING (MAX(rel.date) >= '".date('Y-m-d',strtotime('-14 days'))."' "
                                . "AND MAX(rel.date) < '".date('Y-m-d',strtotime('-7 days'))."')");
		}
		else if($filter == 7){//dropped between last 14-30 days
			//$ids = $this->Retailer->query("SELECT retailers.id FROM vendors_activations,retailers WHERE retailers.id = vendors_activations.retailer_id $query AND retailers.parent_id=$dist AND vendors_activations.date >= '".date('Y-m-d',strtotime('-30 days'))."' group by retailer_id having (max(vendors_activations.date) >= '".date('Y-m-d',strtotime('-30 days'))."' AND max(vendors_activations.date) < '".date('Y-m-d',strtotime('-14 days'))."')");
//			$ids = $this->Slaves->query("SELECT retailers.id FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id $query AND retailers.parent_id=$dist AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' group by retailer_id having (max(retailers_logs.date) >= '".date('Y-m-d',strtotime('-30 days'))."' AND max(retailers_logs.date) < '".date('Y-m-d',strtotime('-14 days'))."')");
			$ids = $this->Slaves->query("SELECT retailers.id "
                                . "FROM retailer_earning_logs rel "
                                . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                                . "WHERE 1 "
                                . "$query "
                                . "AND d.id = $dist "
                                . "AND rel.date >= '".date('Y-m-d',strtotime('-30 days'))."' "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "GROUP BY retailers.id "
                                . "HAVING (MAX(rel.date) >= '".date('Y-m-d',strtotime('-30 days'))."' "
                                . "AND MAX(rel.date) < '".date('Y-m-d',strtotime('-14 days'))."')");
		}
		else if($filter == 8){//dropped before 30 days
			//$ids = $this->Retailer->query("SELECT retailers.id FROM vendors_activations,retailers WHERE retailers.id = vendors_activations.retailer_id $query AND retailers.parent_id=$dist AND vendors_activations.date >= '".date('Y-m-d',strtotime('-2 days'))."' group by retailer_id having (max(vendors_activations.date) < '".date('Y-m-d',strtotime('-30 days'))."')");
//			$ids = $this->Slaves->query("SELECT retailers.id FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id $query AND retailers.parent_id=$dist AND retailers_logs.date >= '".date('Y-m-d',strtotime('-120 days'))."' group by retailer_id having (max(retailers_logs.date) < '".date('Y-m-d',strtotime('-30 days'))."')");
			$ids = $this->Slaves->query("SELECT retailers.id "
                                . "FROM retailer_earning_logs rel "
                                . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                                . "WHERE 1 "
                                . "$query "
                                . "AND d.id = $dist "
                                . "AND rel.date >= '".date('Y-m-d',strtotime('-120 days'))."' "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "GROUP BY retailers.id "
                                . "HAVING (MAX(rel.date) < '".date('Y-m-d',strtotime('-30 days'))."')");
		}

		$idrs = array();
		foreach($ids as $idr){
			$idrs[] = $idr['retailers']['id'];
		}

		echo implode(",",$idrs);
		$this->autoRender = false;
	}

	function allRetailer($id = null,$retId = null,$servId = null){
		$query = "";
		$this->set('retailer_type','non-deleted');
		if($this->Session->read('Auth.User.group_id') == DISTRIBUTOR){
			if($id == 0)$id = null;
			else if($id != null)$query = " AND retailers.maint_salesman = $id";
			if($retId == null) $retId = 0;
			else if($retId!=null) $query.= " AND retailers.id = $retId";
			$dist = $this->info['id'];
			$salesmen=$this->Slaves->query("select name,id,mobile from salesmen WHERE dist_id = $dist AND active_flag = 1 order by id");
			$this->set('salesmen',$salesmen);
			$this->set('sid',$id);
			$this->set('retId', $retId);
                        $this->set('servId', $servId);
			$sid = $id;
		} else {
			$this->set('dist',$id);
			if($id != null){
				//$retailers = $this->General->getRetailerList($id,null,true);
				$distributors = $this->viewVars['distributors'];// already fetched in before controller so used used thos record
				//$this->printArray($distributors);
				$this->set('modelName','Retailer');
				//$this->set('records',$retailers);
				$distributor=array();
				foreach ($distributors as $d){//check given id is in rm's distribtors list
					if($d['Distributor']['id'] == $id ){
						$distributor = $d;
					}
				}
				if(empty($distributor)){// if rm is not belongs to the distribtors( given id )
					$this->redirect('/shops/view');
				}
				$dist = $id;
			}
		}

		$amountCollected = array();
//		$averageResult=$this->Slaves->query("SELECT avg(sale) as avg_ret, retailer_id
//                                                                           from
//                                                                                retailers_logs,retailers
//                                                                           WHERE
//                                                                                retailers.id = retailers_logs.retailer_id AND
//                                                                                retailers.parent_id = ".( empty($dist)? 0 : $dist )." AND
//                                                                                retailers_logs.date > '".date('Y-m-d',strtotime('-30 days'))."'
//                                                                                group by retailer_id");
		$averageResult=$this->Slaves->query("SELECT AVG(rel.amount) AS avg_ret, retailers.id AS retailer_id "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                        . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                        . "WHERE d.id = ".( empty($dist)? 0 : $dist )." "
                        . "AND rel.date > '".date('Y-m-d',strtotime('-30 days'))."' "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY retailers.id");

		foreach($averageResult as $avg){
			$amountCollected[$avg['retailers']['retailer_id']]['average'] = $avg['0']['avg_ret'];
		}
		$successToday=$this->Slaves->query("SELECT sum(va.amount) as amts,retailers.id from vendors_activations as va join retailers on(va.retailer_id=retailers.id AND retailers.parent_id = ".( isset($dist)? $dist : 0 ).") where retailers.toshow = 1 and  va.status != 2 and va.status != 3 AND va.date= '".date('Y-m-d')."' $query group by retailers.id");
		//$successToday=$this->Retailer->query("SELECT sum(st.amount) as amts,retailers.id from shop_transactions as st join retailers on(st.source_id=retailers.id AND retailers.parent_id = ".( isset($dist)? $dist : 0 ).") where retailers.toshow = 1 and  st.type='".RETAILER_ACTIVATION."' and st.confirm_flag = 1 AND st.date= '".date('Y-m-d')."' $query group by retailers.id");
		foreach($successToday as $ac){
			$amountCollected[$ac['retailers']['id']]['sale'] = $ac['0']['amts'];
		}
        $serviceDet = $this->Slaves->query("Select * from services where toShow = '1' and id >= '8'");
        foreach($serviceDet as $serv){
            $servname[$serv['services']['id']] = $serv['services']['name'];
        }

        $slmn = $this->Slaves->query("SELECT id FROM salesmen WHERE mobile = ".$this->Session->read('Auth.User.mobile'));
	$retailers = $this->General->getRetailerList(isset($dist)? $dist : 0,isset($sid)? $sid : 0,true,isset($retId) ? $retId : 0,$slmn[0]['salesmen']['id'],isset($servId) ? $servId : 0);

		$transactions = array();
		/*$lastTrans=$this->Slaves->query("SELECT retailer_id FROM vendors_activations,retailers WHERE vendors_activations.date = '".date('Y-m-d')."' AND retailers.id = vendors_activations.retailer_id AND retailers.parent_id = ".( isset($dist)? $dist : 0 )." $query group by vendors_activations.retailer_id");
		foreach($lastTrans as $ac){
			$transactions[$ac['vendors_activations']['retailer_id']] = date('Y-m-d');
		}*/
//		$lastTrans=$this->Slaves->query("SELECT max(date) as maxDate,retailer_id FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers_logs.sale > 0 AND retailers.parent_id = ".( isset($dist)? $dist : 0 )." $query group by retailers_logs.retailer_id");
		$lastTrans=$this->Slaves->query("SELECT MAX(rel.date) AS maxDate,retailers.id AS retailer_id "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                        . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                        . "WHERE d.id = ".( isset($dist)? $dist : 0 )." "
                        //. "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "$query "
                        . "GROUP BY retailers.id "
                        . "HAVING SUM(rel.amount) > 0");
		foreach($lastTrans as $ac){
			if(!isset($transactions[$ac['retailers']['retailer_id']]))
			$transactions[$ac['retailers']['retailer_id']] = $ac['0']['maxDate'];
		}
//                $totalSum = $this->Slaves->query("SELECT retailers.id,sum(st.amount) amount FROM shop_transactions st JOIN retailers ON (st.target_id = retailers.id) WHERE retailers.parent_id = ".(isset($dist)? $dist : 0)." AND st.confirm_flag != 1 AND st.type = ".SLMN_RETL_BALANCE_TRANSFER." AND st.date = '".date('Y-m-d')."' GROUP BY retailers.id");
//                $topupToday = array();
//                foreach($totalSum as $t) {
//                        $topupToday[$t['retailers']['id']] = $t[0]['amount'];
//                }
//		$this->set('topupToday',$topupToday);
		$this->set('lastTrans',$transactions);
		$this->set('records',$retailers);
		$this->set('amounts',$amountCollected);
                $this->set('serviceDet',$serviceDet);
                $this->set('servname',$servname);

//		echo "<pre>";
//		print_r($retailers);
//		die;
		//print_r($amountCollected);
		$this->render('allretailer');
	}

	function deletedRetailer($id = null){

		$query = "";
		$this->set('retailer_type','deleted');
		if($this->Session->read('Auth.User.group_id') == DISTRIBUTOR){
			if($id == 0)$id = null;
			else if($id != null)$query = " AND retailers.maint_salesman = $id";
			$dist = $this->info['id'];
			$salesmen=$this->Slaves->query("select name,id,mobile from salesmen WHERE dist_id = $dist AND mobile != '".$_SESSION['Auth']['User']['mobile']."' AND active_flag = 1 order by id");
			$this->set('salesmen',$salesmen);
			$this->set('sid',$id);

		}else {
			$this->set('dist',$id);
			if($id != null){
				$retailers = $this->General->getRetailerList($id);
				$distributors = $this->viewVars['distributors'];// already fetched in before controller so used used thos record
				$this->set('modelName','Retailer');
				$this->set('records',$retailers);

				$distributor=array();
				foreach ($distributors as $d){//check given id is in rm's distribtors list
					if($d['Distributor']['id'] == $id ){
						$distributor = $d;
					}
				}
				if(empty($distributor)){// if rm is not belongs to the distribtors( given id )
					$this->redirect('/shops/view');
				}
				$dist = $id;
			}
		}

		$amountCollected = array();
//		$averageResult=$this->Slaves->query("SELECT avg(sale) as avg_ret, retailer_id
//                                                                           from
//                                                                                retailers_logs,retailers
//                                                                           WHERE
//                                                                                retailers.id = retailers_logs.retailer_id AND
//                                                                                retailers.parent_id = ".( empty($dist)? 0 : $dist )." AND
//
//                                                                                retailers.toShow = 0
//                                                                                group by retailer_id");//retailers_logs.date > '".date('Y-m-d',strtotime('-30 days'))."'
		$averageResult=$this->Slaves->query("SELECT AVG(rel.amount) AS avg_ret, retailers.id AS retailer_id "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                        . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                        . "WHERE d.id = ".( empty($dist)? 0 : $dist ).""
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "AND retailers.toShow = 0 "
                        . "GROUP BY retailers.id");//retailers_logs.date > '".date('Y-m-d',strtotime('-30 days'))."'

		foreach($averageResult as $avg){
			$amountCollected[$avg['retailers']['retailer_id']]['average'] = $avg['0']['avg_ret'];
		}

		$successToday=$this->Slaves->query("SELECT sum(va.amount) as amts,retailers.id from vendors_activations as va join retailers on(va.retailer_id=retailers.id AND retailers.parent_id = ".( isset($dist)? $dist : 0 ).") where retailers.toshow = 1 and  va.status != 2 and va.status != 3 AND va.date= '".date('Y-m-d')."' $query group by retailers.id");
		foreach($successToday as $ac){
			$amountCollected[$ac['retailers']['id']]['sale'] = $ac['0']['amts'];
		}

		$qry = 1;
		if($id != null){
			$qry = "Retailer.maint_salesman = $id";
		}
		$retailObj = ClassRegistry::init('Retailer');
		$retailers =  $retailObj->find('all', array(
                                                'fields' => array('Retailer.*','users.mobile', 'sum(users_logs.topup_buy) as xfer'),
                                                'conditions' => array('Retailer.parent_id' => $dist,'Retailer.toshow' => 0,$qry),
                                                'joins' => array(
		array(
                                                                                'table' => 'users',
                                                                                'type' => 'left',
                                                                                'conditions'=> array('Retailer.user_id = users.id')
		),
		array(
                                                                                'table' => 'users_logs',
                                                                                'type' => 'left',
                                                                                'conditions'=> array('Retailer.user_id = users_logs.user_id','users_logs.date = "'.date('Y-m-d').'"')
		)
		),
                                                'order' => 'xfer desc, Retailer.shopname asc',
                                                'group'	=> 'Retailer.id'
                                                )
                                            );

                                        /** IMP DATA ADDED : START**/
                                        $ret_ids = array_map(function($element){
                                            return $element['Retailer']['id'];
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
                                            foreach ($retailer['Retailer'] as $retailer_label_key => $value) {
                                                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                                                if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['Retailer']['id']]['imp']) ){
                                                    $retailers[$key]['Retailer'][$retailer_label_key] = $imp_data[$retailer['Retailer']['id']]['imp'][$retailer_label_key_mapped];
                                                }
                                            }
                                        }
                                        /** IMP DATA ADDED : END**/


                                                ////$this->General->getRetailerList(isset($dist)? $dist : 0 ,isset($sid)? $sid : 0);
                                                $transactions = array();
                                                $lastTrans=$this->Slaves->query("SELECT retailer_id FROM vendors_activations,retailers WHERE vendors_activations.date = '".date('Y-m-d')."' AND retailers.id = vendors_activations.retailer_id AND retailers.parent_id = ".( isset($dist)? $dist : 0 )." $query group by vendors_activations.retailer_id");
                                                foreach($lastTrans as $ac){
                                                	$transactions[$ac['vendors_activations']['retailer_id']] = date('Y-m-d');
                                                }
//                                                $lastTrans=$this->Slaves->query("SELECT max(date) as maxDate,retailer_id FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers_logs.sale > 0 AND retailers.parent_id = ".( isset($dist)? $dist : 0 )." $query group by retailers_logs.retailer_id");
                                                $lastTrans = $this->Slaves->query("SELECT MAX(rel.date) AS maxDate,retailers.id AS retailer_id "
                                                        . "FROM retailer_earning_logs rel "
                                                        . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                                        . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                                                        . "WHERE d.id = ".( isset($dist)? $dist : 0 )." "
                                                        . "$query "
                                                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                                                        . "GROUP BY retailers.id "
                                                        . "HAVING SUM(rel.amount) > 0");
                                                foreach($lastTrans as $ac){
                                                	if(!isset($transactions[$ac['retailers']['retailer_id']]))
                                                	$transactions[$ac['retailers']['retailer_id']] = $ac['0']['maxDate'];
                                                }
                                                $this->set('lastTrans',$transactions);
                                                $this->set('records',$retailers);
                                                $this->set('amounts',$amountCollected);
                                                $this->render('allretailer');
	}

	function salesmanListing($id = null)
	{

		$slmn=$this->Slaves->query("select sm.*,users.balance,group_concat(sub.name) as subs from salesmen sm left join salesmen_subarea ssa on(ssa.salesmen_id=sm.id) LEFT JOIN users ON (users.id = sm.user_id) left join subarea sub on(sub.id=ssa.subarea_id) WHERE sm.dist_id = ".$this->info['id']." AND sm.mobile != '".$_SESSION['Auth']['User']['mobile']."' AND sm.active_flag = 1 group by sm.id order by sm.id asc");
		$topups = $this->Slaves->query("SELECT salesman_transactions.salesman,sum(shop_transactions.amount) as amt FROM salesman_transactions inner join shop_transactions ON (shop_transactions.id = salesman_transactions.shop_tran_id) WHERE shop_transactions.source_id = ".$this->info['id']." AND collection_date = '".date('Y-m-d')."' AND salesman_transactions.payment_type = 2 AND shop_transactions.confirm_flag != 1  group by salesman");
		$colls = $this->Slaves->query("SELECT collection_amount,salesman FROM salesman_collections WHERE date = '".date('Y-m-d')."' AND payment_type = 2 AND distributor_id = ". $this->info['id']);

                $salesmanListResult = array();
		foreach($slmn as $slr){
                        $slr['sm']['balance'] = $slr['users']['balance'];
                        $salesmanListResult[] = $slr;
		}

		$tops = array();
		foreach($topups as $top){
			$tops[$top['salesman_transactions']['salesman']] = $top['0']['amt'];
		}

		foreach ($colls as $col) {
			if (isset($tops[$col['salesman_collections']['salesman']])) {
				$tops[$col['salesman_collections']['salesman']] = $tops[$col['salesman_collections']['salesman']] - $col['salesman_collections']['collection_amount'];
			}
		}
		$this->set('salesman',$salesmanListResult);
		$this->set('topups',$tops);
	}


	function deleteSubarea($subareaId)
	{
		$success=0;
		$smId=$_REQUEST['smId'];

		//exit;
		$this->User->query("delete from salesmen_subarea where salesmen_id=$smId and subarea_id=$subareaId");
		$success=1;
		echo $success;
		$this->autoRender=false;
	}


	function saveEditSm()
	{

		$smId=$_REQUEST['smId'];
		$limit = $_REQUEST['smLimit'];
		$newSmMobile=$_REQUEST['smMobile'];

		$smMobileResult=$this->User->query("select sm.mobile,sm.tran_limit,sm.user_id from salesmen sm where sm.id=$smId");
		$smMobile=$smMobileResult['0']['sm']['mobile'];
		$smUserId=$smMobileResult['0']['sm']['user_id'];
		$subareas=$_REQUEST['subAreaList'];
		$balance = isset($smMobileResult['0']['sm']['balance']) ? $smMobileResult['0']['sm']['balance'] : 0;

		$chkDuplicateSmMobile = $this->User->query("select sm.mobile from salesmen sm where sm.mobile=$newSmMobile and sm.id != $smId");
                $chkDuplicateUserMobile = $this->User->query("SELECT id FROM users WHERE mobile = '$newSmMobile' and id != '$smUserId'");
		if(empty($chkDuplicateSmMobile) && empty($chkDuplicateUserMobile))
		{
			$success=1;
		}
		else
		{
			$success=0;
		}

		$diff = $limit - $smMobileResult['0']['sm']['tran_limit'];
		$balance = $balance + $diff;
		/*if($diff > 0)
			$balance = $balance + $diff;
			else
			$balance = $balance - abs($diff);*/

                if($success == 1) {
                        if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) {
                                $update=$this->User->query("update salesmen set name='".$_REQUEST['smName']."', balance=".$balance.",tran_limit=$limit,mobile='$newSmMobile' where id=$smId");
                        } else {
                                $update=$this->User->query("update users set mobile='$newSmMobile' where id='".$smMobileResult['0']['sm']['user_id']."'");
                                $update=$this->User->query("update salesmen set name='".$_REQUEST['smName']."',mobile='$newSmMobile' where id=$smId");
                        }
                }
		$count=$this->mapSalesmanToSubarea($smId,$subareas);

		echo $success;

		$this->autoRender=false;
	}


	function editSalesman($mobile)
	{
		$salesmanResult=$this->Slaves->query("select sm.* from salesmen sm where sm.mobile='$mobile' AND sm.dist_id = " . $this->info['id']);
		if(empty($salesmanResult)){
			$this->redirect(array('action' => 'salesmanListing'));
		}
		$this->set('smR',$salesmanResult);

		$salesmanExistingSubareaResult=$this->Slaves->query("select sm.name,sm.id,sm.mobile,sm.balance,sub.name,sub.id from salesmen sm left join salesmen_subarea ssa on(ssa.salesmen_id=sm.id) left join subarea sub on(sub.id=ssa.subarea_id) where sm.mobile='$mobile'");
		$this->set('existingSA',$salesmanExistingSubareaResult);
		$this->set('smEditDeatils',$smDetails);
	}

	/*function retailerListing($id = null){
		if(!$this->Session->check('Auth.User.group_id'))$this->logout();

		$retailers = array();
		if($id == null)$this->set('empty',1);
		else {
		$shop = $this->Shop->getShopDataById($id,DISTRIBUTOR);
		$this->set('distributor',$shop['company']);
		$this->set('distributor_id',$id);
		if($shop['parent_id'] == $this->info['id']){
		$this->Retailer->recursive = -1;
		$retailers = $this->Retailer->find('all', array(
		'fields' => array('Retailer.*', 'users.mobile', 'sum(shop_transactions.amount) as xfer'),
		'conditions' => array('Retailer.parent_id' => $id),
		'joins' => array(
		array(
		'table' => 'users',
		'type' => 'left',
		'conditions'=> array('Retailer.user_id = users.id')
		),
		array(
		'table' => 'shop_transactions',
		'type' => 'left',
		'conditions'=> array('Retailer.id = shop_transactions.target_id','shop_transactions.date = "'.date('Y-m-d').'"', 'shop_transactions.type = 2')
		)
		),
		'order' => 'Retailer.shopname asc',
		'group'	=> 'Retailer.id'
		)
		);
		}
		else {
		$retailers = array();
		}
		}
		$this->set('retailers',$retailers);
		$this->render('retailer_listing');
		}*/

	function editRetailer($type,$id){
		if(!in_array($type, array('d','r')))
		$this->redirect(array('action' => 'allRetailer'));

		$cityName = "";
		$stateName = "";

		if($type == 'r'){
			$tableName = 'retailers';
			$modName = 'Retailer';
			$editData = $this->Retailer->find('all', array(
			'fields' => array('Retailer.*', 'slabs.name','users.mobile', 'ur.*'),
			'conditions' => array('Retailer.id' => $id),
			'joins' => array(
			array(
							'table' => 'slabs',
							'type' => 'inner',
							'conditions'=> array('Retailer.slab_id = slabs.id')
			),
			array(
							'table' => 'users',
							'type' => 'inner',
							'conditions'=> array('Retailer.user_id = users.id')
			),
			array(
					'table' => 'unverified_retailers as ur',
					'type' => 'left',
					'conditions'=> array('ur.retailer_id = Retailer.id')
			)
			))
        );

            /** IMP DATA ADDED : START**/
            $temp = $this->Shop->getUserLabelData($id,2,2);
            $imp_data = $temp[$id];

            $retailer_imp_label_map = array(
                'pan_number' => 'pan_no',
                'shopname' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id',
                'shop_structure' => 'shop_ownership',
                'shop_type' => 'business_nature'
            );

            foreach ($editData as $key => $retailer) {
                foreach ($retailer['Retailer'] as $retailer_label_key => $value) {
                    $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                    if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                        $editData[$key]['Retailer'][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
                    }
                }
                foreach ($retailer['ur'] as $retailer_label_key => $value) {
                    $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                    if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                        $editData[$key]['ur'][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
                    }
                }
            }
            /** IMP DATA ADDED : END**/



			foreach($editData[0]['ur'] as $key => $row){
				if($key != 'id')
					$editData[0]['Retailer'][$key] = $editData[0]['ur'][$key];
			}
			$subareaList=$this->Slaves->query("select id,name from subarea where area_id= " .  $editData['0']['Retailer']['area_id']);


			$this->set('subareaList',$subareaList);




			$city = $this->Slaves->query("SELECT locator_city.name,locator_city.id FROM locator_city,locator_area WHERE locator_area.id = " . $editData['0']['Retailer']['area_id'] . " AND locator_area.city_id = locator_city.id");
			$cityName = "";
			if(!empty($city)){
				$cityName = trim($city['0']['locator_city']['name']);
				$this->set('retCity',$city['0']['locator_city']['id']);
				$state = $this->Slaves->query("SELECT locator_state.name,locator_state.id FROM locator_state,locator_city WHERE locator_city.id = " . $city['0']['locator_city']['id'] . " AND locator_city.state_id = locator_state.id");
			}
			$stateName = "";
			if(!empty($state)){
				$stateName = trim($state['0']['locator_state']['name']);
				$this->set('retState',$state['0']['locator_state']['id']);
			}
		}

		if($type == 'd'){
			$tableName = 'distributors';
			$modName = 'Distributor';
			$editData = $this->Distributor->find('all', array(
			'fields' => array('Distributor.*', 'slabs.name','users.mobile'),
			'conditions' => array('Distributor.id' => $id),
			'joins' => array(
			array(
							'table' => 'slabs',
							'type' => 'inner',
							'conditions'=> array('Distributor.slab_id = slabs.id')
			),
			array(
							'table' => 'users',
							'type' => 'inner',
							'conditions'=> array('Distributor.user_id = users.id')
			)

			)));

            /** IMP DATA ADDED : START**/

            $temp = $this->Shop->getUserLabelData($id,2,3);
            $imp_data = $temp[$id];

            $dist_imp_label_map = array(
            	'pan_number' => 'pan_no',
                'company' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id'
            );
            foreach ($editData as $key => $distributor) {
                foreach ($distributor['Distributor'] as $dist_label_key => $value) {
                    $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
                    if( array_key_exists($dist_label_key_mapped,$imp_data['imp']) ){
                        $editData[$key]['Distributor'][$dist_label_key] = $imp_data['imp'][$dist_label_key_mapped];
                    }
                }
            }
            /** IMP DATA ADDED : END**/

			$stateName = trim($editData['0']['Distributor']['state']);
		}

		if($_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){
			$column_name = "sd_id";
		}else{
			$column_name = "parent_id";
		}

		if(!$this->Slaves->query("SELECT id FROM ".$tableName." WHERE ".$column_name." = ".$this->info['id']." and id=".$id))
		$this->redirect(array('action' => 'allRetailer'));

//                $city_data = $this->User->query("SELECT state_id,name FROM locator_city WHERE id = (SELECT city_id  FROM `locator_area` WHERE `name` LIKE '".$editData['0']['Distributor']['area_range']."') AND toshow = 1 ORDER BY name asc");
//
//                $editData['0']['Distributor']['state_id'] = $city_data['0']['locator_city']['state_id'];
//                $editData['0']['Distributor']['city'] = $city_data['0']['locator_city']['name'];
//
//                $state_data = $this->User->query("SELECT name FROM locator_state WHERE id = '".$editData['0']['Distributor']['state_id']."' AND toshow = 1 ORDER BY name asc");
//		$editData['0']['Distributor']['state'] = $state_data['0']['locator_state']['name'];

//                $this->set('areas',$areas);
//
//		$cities = $this->Slaves->query("SELECT id,name FROM locator_city WHERE state_id = (select id from locator_state where name='".$stateName."') AND toshow = 1 ORDER BY name asc");
//		$this->set('cities',$cities);
//
//		$areas = $this->Slaves->query("SELECT id,name FROM locator_area WHERE city_id = (select id from locator_city where name='".$cityName."') AND toshow = 1 ORDER BY name asc");
//		$this->set('areas',$areas);

		$sMen = $this->Slaves->query("SELECT id,name,mobile FROM salesmen where dist_id = ".$this->info['id']."");
		$this->set('sMen',$sMen);

		$rm_list = $this->Slaves->query("SELECT id,name FROM rm WHERE master_dist_id = ".$this->info['id']." ORDER BY name asc");
		$this->set('rm_list',$rm_list);
		/*$fees = $this->Retailer->query("SELECT sum(salesman_transactions.collection_amount) as fee FROM salesman_transactions inner join shop_transactions on (salesman_transactions.shop_tran_id = shop_transactions.id) where salesman_transactions.payment_type = ".TYPE_SETUP." and shop_transactions.target_id = ".$id);
		 $this->set('fees',$fees);*/

                $services = $this->Slaves->query("SELECT id,name FROM services WHERE toShow = 1");
		$this->set('services', array_map('current', $services));

		$this->set('type',$type);
		$this->set('id',$id);
		$this->set('editData',$editData);
		$this->set('modName',$modName);
                $this->set('primary_services', Configure::read('primary_services'));

		$this->render('edit_form_retailer');
		//$this->autoRender = false;
	}

	function editSuperDistributor($id){

			$modName = 'SuperDistributor';
			

			$editData = $this->SuperDistributor->find('all', array(
					'fields' => array('users.mobile','slabs.id', 'slabs.name', 'SuperDistributor.id', 'SuperDistributor.slab_id','SuperDistributor.user_id','SuperDistributor.no_distributors_limit', 'rm.name', 'rm.id','retailers_location.latitude','retailers_location.longitude','locator_city.name','locator_state.name'),
					'conditions' => array('SuperDistributor.id' => $id),
					'joins' => array(
							array(
									'table' => 'slabs',
									'type' => 'inner',
									'conditions' => array('SuperDistributor.slab_id = slabs.id')
							),
							array(
									'table' => 'users',
									'type' => 'inner',
									'conditions' => array('SuperDistributor.user_id = users.id')
							),
							array(
									'table' => 'rm',
									'type' => 'left',
									'conditions' => array('SuperDistributor.rm_id = rm.id')
							),
							array(
									'table' => 'retailers_location',
									'type' => 'left',
									'conditions' => array('SuperDistributor.user_id = retailers_location.user_id')
							),
							array(
									'table' => 'locator_area',
									'type' => 'left',
									'conditions' => array('retailers_location.area_id = locator_area.id')
							),
							array(
									'table' => 'locator_city',
									'type' => 'left',
									'conditions' => array('locator_city.id = locator_area.city_id')
							),
							array(
									'table' => 'locator_state',
									'type' => 'left',
									'conditions' => array('locator_state.id = locator_city.state_id')
							)
					)
				)
			);
            /** IMP DATA ADDED : START**/

            

            $imp_data = $this->Shop->getUserLabelData($editData[0]['SuperDistributor']['user_id']);
            
            $imp_label_map = array(
                'name' , 'address', 'dob', 'gst_no'
            );

                foreach ($imp_label_map as $imp_label) {


                   $editData[0]['SuperDistributor'][$imp_label] = $imp_data[$editData[0]['SuperDistributor']['user_id']]['imp'][$imp_label];
                }

                $editData[0]['SuperDistributor']['pan_number'] = $imp_data[$editData[0]['SuperDistributor']['user_id']]['imp']['pan_no'];
                $editData[0]['SuperDistributor']['company'] = $imp_data[$editData[0]['SuperDistributor']['user_id']]['imp']['shop_est_name'];
                $editData[0]['SuperDistributor']['email'] = $imp_data[$editData[0]['SuperDistributor']['user_id']]['imp']['email_id'];
                $editData[0]['SuperDistributor']['map_lat'] = $editData[0]['retailers_location']['latitude'];
                $editData[0]['SuperDistributor']['map_long'] = $editData[0]['retailers_location']['longitude'];
                $editData[0]['SuperDistributor']['mobile'] = $editData[0]['users']['mobile'];
                $editData[0]['SuperDistributor']['rm_id'] = $editData[0]['rm']['id'];
                $editData[0]['SuperDistributor']['rm_name'] = $editData[0]['rm']['name'];
                $editData[0]['SuperDistributor']['state'] = $editData[0]['locator_state']['name'];
                $editData[0]['SuperDistributor']['city'] = $editData[0]['locator_city']['name'];


            /** IMP DATA ADDED : END**/


		$allRM = $this->Slaves->query("
			SELECT r.id,r.name 
			FROM rm r
			JOIN users u ON u.id = r.user_id
		 	WHERE u.active_flag = 1");
         $this->set('allRM', $allRM);


         	

		$this->set('id',$id);
		$this->set('editData',$editData);
		$this->set('modName',$modName);

		$this->render('edit_form_super_distributor');/**/
	}

	function editSuperDistributorValidation(){
		$msg = "";
		$empty = array();
		$empty_flag = false;
		$to_save = true;
		$confirm = 0;
                $MsgTemplate = $this->General->LoadApiBalance();
		if(isset($this->data['confirm']))
		$confirm = $this->data['confirm'];
		$this->set('data',$this->data);
        if(empty($this->data['SuperDistributor']['map_lat'])){
			$empty[] = 'Latitude';
			$empty_flag = true;
			$to_save = false;
		}
       	if(empty($this->data['SuperDistributor']['map_long'])){
			$empty[] = 'Longitute';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['name'])){
			$empty[] = 'Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['company'])){
			$empty[] = 'Company Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['mobile'])){
			$empty[] = 'Mobile';
			$empty_flag = true;
			$to_save = false;
		}
		else {
			$this->data['SuperDistributor']['mobile'] = trim($this->data['SuperDistributor']['mobile']);
			//preg_match('/^[6-9][0-9]{9}$/',$this->data['SuperDistributor']['mobile'],$matches,0);
			if($this->General->mobileValidate($this->data['SuperDistributor']['mobile']) == '1'){
				$msg = "Mobile Number is not valid";
				$to_save = false;
			}

			if($this->data['SuperDistributor']['no_distributors_limit'] < 0){
				$msg = "No. of distributor shoud be greater than 0";
				$to_save = false;
			}
		}
        if(empty($this->data['SuperDistributor']['dob'])){
			$empty[] = 'DOB';
			$empty_flag = true;
			$to_save = false;
		}else{
            $date = explode("-", $this->data['SuperDistributor']['dob']);
            $this->data['SuperDistributor']['dob'] = $date[2] . "-" . $date[1] . "-" . $date[0];
        }
		if(empty($this->data['SuperDistributor']['state'])){
			$empty[] = 'State';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['city'])){
                    	$empty[] = 'City';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['address'])){
                    	$empty[] = 'Address';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['pan_number'])){
			$empty[] = 'PAN Number';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['gst_no'])){
			$empty[] = 'GST Number';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['SuperDistributor']['rm_id'])){
			$empty[] = 'RM';
			$empty_flag = true;
			$to_save = false;
		}
               

                

		if($to_save){
				$exists = $this->General->checkIfUserExists($this->data['SuperDistributor']['mobile'], null, SUPER_DISTRIBUTOR);
				if(!$exists) {
                            $err_msg = "User does not exist with this mobile number";
                            $to_save = false;
                       }
			
		}

		$allRM = $this->Slaves->query("
									SELECT r.id,r.name 
									FROM rm r
									JOIN users u ON u.id = r.user_id
								 	WHERE u.active_flag = 1");
						         $this->set('allRM', $allRM);
              

		if(!$to_save){

			$tArr[0] = $this->data;
			$this->set('editData',$tArr);
                        
			if($empty_flag){

				$err_msg = '<div class="error_class">'.implode(", ",$empty).' cannot be set empty</div>';
				$this->Session->setFlash($err_msg, true);
				$this->render('/elements/edit_form_super_distributor','ajax');
			}
			else {
				$err_msg = '<div class="error_class">'.$msg.' </div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/edit_form_super_distributor','ajax');
			}
		}
		else if($confirm == 0 && $to_save){
                    $stateId = $this->General->stateInsert($this->data['SuperDistributor']['state']);
                    $cityId = $this->General->cityInsert($this->data['SuperDistributor']['city'] , $stateId);
			$slab = $this->Slaves->query("SELECT name FROM slabs WHERE id = " . $this->data['SuperDistributor']['slab_id']);

			$tArr[0] = $this->data;

			$this->set('slab',$slab);
            $this->set('dob',$this->data['SuperDistributor']['dob']);
			$this->set('city',$this->data['SuperDistributor']['city']);
			$this->set('state',$this->data['SuperDistributor']['state']);
			$this->render('confirm_super_distributor_edit','ajax');
		}
		else {

			$id = $this->data['SuperDistributor']['id'];
              
			$this->data['SuperDistributor']['created'] = date('Y-m-d H:i:s');
			$this->data['SuperDistributor']['modified'] = date('Y-m-d H:i:s');

			if($exists){


				$user = $this->SuperDistributor->find('all', array(
						'fields' => array('users.*', 'slabs.name', 'SuperDistributor.id','SuperDistributor.user_id', 'SuperDistributor.max_limit', 'SuperDistributor.no_distributors_limit', 'SuperDistributor.active_flag', 'SuperDistributor.created', 'rm.name'),
						'conditions' => array('SuperDistributor.id' => $id),
						'joins' => array(
								array(
										'table' => 'slabs',
										'type' => 'inner',
										'conditions' => array('SuperDistributor.slab_id = slabs.id')
								),
								array(
										'table' => 'users',
										'type' => 'inner',
										'conditions' => array('SuperDistributor.user_id = users.id')
								),
								array(
										'table' => 'rm',
										'type' => 'left',
										'conditions' => array('SuperDistributor.rm_id = rm.id')
								)
						)
						)
				);
			}

			$this->data['SuperDistributor']['user_id'] = $user[0]['users']['id'];


                   
			$update_super_distributors = $this->SuperDistributor->query("UPDATE super_distributors SET rm_id = '".$this->data['SuperDistributor']['rm_id']."',slab_id = '".$this->data['SuperDistributor']['slab_id']."',no_distributors_limit = '".$this->data['SuperDistributor']['no_distributors_limit']."' WHERE id=".$id);
                
			if ($update_super_distributors) {
                                $area_id = $this->General->getAreaIDByLatLong($this->data['SuperDistributor']['map_long'],$this->data['SuperDistributor']['map_lat']);
                                $this->Retailer->query("
                                	UPDATE retailers_location 
                                	SET 
	                                	area_id = '$area_id',
	                                	latitude = '".$this->data['SuperDistributor']['map_lat']."',
	                                	longitude = '".$this->data['SuperDistributor']['map_long']."',
	                                	updated = '".date('Y-m-d')."' 
                                	WHERE user_id = '".$this->data['SuperDistributor']['user_id']."'");
				
				$this->Shop->updateSlab($this->data['SuperDistributor']['slab_id'],$this->SuperDistributor->id,SUPER_DISTRIBUTOR);

				/** IMP DATA ADDED : START**/
                    $imp_update_data = array(
                        'name' => $this->data['SuperDistributor']['name'],
                        'pan_number' => $this->data['SuperDistributor']['pan_number'],
                        'shop_est_name' => $this->data['SuperDistributor']['company'],
                        'email' => $this->data['SuperDistributor']['email'],
                        'address' => $this->data['SuperDistributor']['address'],
                        'gst_no' => $this->data['SuperDistributor']['gst_no'],
                        'dob' => $this->data['SuperDistributor']['dob']
                    );
                    $response = $this->Shop->updateUserLabelData($user[0]['users']['id'],$imp_update_data,$this->Session->read('Auth.User.id'));
                    /** IMP DATA ADDED : END**/					

				//$this->render('/elements/allSuperDistributors','ajax');
				/*echo "success";
				exit;*/
				$tArr[0] = $this->data;
				$this->set('editData',$tArr);
				//$this->render('/elements/edit_form_super_distributor','ajax');
				echo "success";
				exit;
			} else {

				$tArr[0] = $this->data;
				$this->set('editData',$tArr);
				$err_msg = '<div class="error_class">Super Distributor could not be saved. Please, try again.</div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/edit_form_super_distributor','ajax');
			}
		}
	}

	function deleteRetailer(){

		$type = $_REQUEST['type'] ;
		$id = $_REQUEST['id'] ;
		$toShow = $_REQUEST['toShow'] ;
		$block = $_REQUEST['block'] ;

		if(!in_array($type, array('d','r')))
		$this->redirect(array('action' => 'allRetailer'));



		if($type == 'r'){
			$subareaList=$this->Retailer->query("UPDATE retailers SET toshow = $toShow ,  block_flag = $block, modified = '".date('Y-m-d H:i:s')."' where id= " . $id);
		}

		if($type == 'd'){
			$subareaList=$this->Retailer->query("UPDATE retailers SET toshow = $toShow ,  block_flag = $block, modified = '".date('Y-m-d H:i:s')."' where id= " . $id);
		}

		$this->autoRender = false;
		$res = array('status'=>'success','msg'=>'record deleted .');
		return(json_encode($res));
	}
	/*
	 ({request:{options:{method:"post", asynchronous:true, contentType:"application/x-www-form-urlencoded", encoding:"UTF-8", parameters:{id:43, type:"r"}, evalJSON:true, evalJS:true, onSuccess:(function (transport)
	 {
	 alert(transport.toSource());
	 $("ret_"+rid).hide();
	 })}, transport:{}, url:"/shops/deleteRetailer", method:"post", parameters:{id:43, type:"r"}, body:"id=43&type=r", _complete:true}, transport:{}, readyState:4, status:200, statusText:"OK", responseText:"{\"status\":\"success\",\"msg\":\"record deleted .\"}", headerJSON:null, responseXML:null, responseJSON:null})
	 */
	function showDetails($type,$id,$dist=null){

		if(!in_array($type, array('d','r','sd')) || empty($id)){
			if($dist == null)
			$this->redirect(array('action' => 'allRetailer'));
			else
			$this->redirect(array('action' => 'retailerListing'));
		}

		if($type == 'r'){
			$tableName = 'retailers';
			$modName = 'Retailer';
			$this->Retailer->recursive = -1;
			$editData = $this->Retailer->find('all', array(
			'fields' => array('Retailer.*', 'slabs.name','users.mobile', 'ur.*'),
			'conditions' => array('Retailer.id' => $id),
			'joins' => array(
			array(
							'table' => 'slabs',
							'type' => 'inner',
							'conditions'=> array('Retailer.slab_id = slabs.id')
			),
			array(
							'table' => 'users',
							'type' => 'inner',
							'conditions'=> array('Retailer.user_id = users.id')
			),
			array(
					'table' => 'unverified_retailers as ur',
					'type' => 'left',
					'conditions'=> array('ur.retailer_id = Retailer.id')
			)
			))
			);


			if($editData[0]['Retailer']['verify_flag'] != 1){
				foreach($editData[0]['ur'] as $key => $row){
					if($key != 'id')
						$editData[0]['Retailer'][$key] = $editData[0]['ur'][$key];
				}

				$editData[0]['Retailer']['shopname'] = $editData[0]['Retailer']['shop_name'];
				$editData[0]['Retailer']['pin'] = $editData[0]['Retailer']['pin_code'];
            }

            /** IMP DATA ADDED : START**/
            $temp = $this->Shop->getUserLabelData($id,2,2);
            $imp_data = $temp[$id];

            $retailer_imp_label_map = array(
                'pan_number' => 'pan_no',
                'shopname' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id',
                'shop_structure' => 'shop_ownership',
                'shop_type' => 'business_nature'
            );

            foreach ($editData as $key => $retailer) {
                foreach ($retailer['Retailer'] as $retailer_label_key => $value) {
                    $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                    if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                        $editData[$key]['Retailer'][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
                    }
                }
            }
            /** IMP DATA ADDED : END**/


			if($dist != null){
				$parent = $editData['0']['Retailer']['parent_id'];
				$shop = $this->Shop->getShopDataById($parent,DISTRIBUTOR);
				if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR && ($this->$parent != $dist || $shop['parent_id'] != $this->info['id']))
				$this->redirect(array('action' => 'retailerListing'));
				else
				$this->redirect(array('action' => 'allRetailer'));
				$this->set('dist',$dist);
			}

			$state = isset ($editData['0']['Retailer']['state'])?$editData['0']['Retailer']['state']:"";
			$city = isset($editData['0']['Retailer']['city'])?$editData['0']['Retailer']['city']:"";
			$slab = $this->Slaves->query("SELECT name FROM slabs WHERE id = " . $editData['0']['Retailer']['slab_id']);
			$area = $this->Slaves->query("SELECT name FROM locator_area WHERE id = " . $editData['0']['Retailer']['area_id']);

			$this->set('slab',$slab['0']['slabs']['name']);
			$this->set('city',$city);
			$this->set('state',$state);
			$this->set('area',$area['0']['locator_area']['name']);

			
		}

		if($type == 'd'){
			$tableName = 'distributors';
			$modName = 'Distributor';
			$this->Distributor->recursive = -1;
			$editData = $this->Distributor->find('all', array(
				'fields' => array('Distributor.*', 'slabs.name','users.mobile'),
				'conditions' => array('Distributor.id' => $id),
				'joins' => array(
			array(
								'table' => 'slabs',
								'type' => 'inner',
								'conditions'=> array('Distributor.slab_id = slabs.id')
			),
			array(
								'table' => 'users',
								'type' => 'inner',
								'conditions'=> array('Distributor.user_id = users.id')
			)

			))
        ); 
                        
                        

        /** IMP DATA ADDED : START**/
        $temp = $this->Shop->getUserLabelData($id,2,3);
        $imp_data = $temp[$id];

        $dist_imp_label_map = array(
            'pan_number' => 'pan_no',
            'company' => 'shop_est_name',
            'alternate_number' => 'alternate_mobile_no',
            'email' => 'email_id'
        );
        foreach ($editData as $key => $distributor) {
            foreach ($distributor['Distributor'] as $dist_label_key => $value) {
                $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
                if( array_key_exists($dist_label_key_mapped,$imp_data['imp']) ){
                    $editData[$key]['Distributor'][$dist_label_key] = $imp_data['imp'][$dist_label_key_mapped];
                }
            }
        }
        /** IMP DATA ADDED : END**/


			$state = $editData['0']['Distributor']['state'];
			$city = $editData['0']['Distributor']['city'];
			$slab = $this->Slaves->query("SELECT name FROM slabs WHERE id = " . $editData['0']['Distributor']['slab_id']);

			$this->set('slab',$slab['0']['slabs']['name']);
			$this->set('city',$city);
			$this->set('state',$state);

		} if($type == "sd"){

			$modName = 'SuperDistributor';
			$this->SuperDistributor->recursive = -1;
			$editData = $this->SuperDistributor->find('all', array(
					'fields' => array('users.mobile','slabs.id', 'slabs.name', 'SuperDistributor.id','SuperDistributor.user_id', 'rm.name', 'rm.id','retailers_location.latitude','retailers_location.longitude','locator_city.name','locator_state.name'),
					'conditions' => array('SuperDistributor.id' => $id),
					'joins' => array(
							array(
									'table' => 'slabs',
									'type' => 'inner',
									'conditions' => array('SuperDistributor.slab_id = slabs.id')
							),
							array(
									'table' => 'users',
									'type' => 'inner',
									'conditions' => array('SuperDistributor.user_id = users.id')
							),
							array(
									'table' => 'rm',
									'type' => 'left',
									'conditions' => array('SuperDistributor.rm_id = rm.id')
							),
							array(
									'table' => 'retailers_location',
									'type' => 'left',
									'conditions' => array('SuperDistributor.user_id = retailers_location.user_id')
							),
							array(
									'table' => 'locator_area',
									'type' => 'left',
									'conditions' => array('retailers_location.area_id = locator_area.id')
							),
							array(
									'table' => 'locator_city',
									'type' => 'left',
									'conditions' => array('locator_city.id = locator_area.city_id')
							),
							array(
									'table' => 'locator_state',
									'type' => 'left',
									'conditions' => array('locator_state.id = locator_city.state_id')
							)
					)
				)
			);
            /** IMP DATA ADDED : START**/


            $imp_data = $this->Shop->getUserLabelData($editData[0]['SuperDistributor']['user_id']);
            
            $imp_label_map = array(
                'name' , 'address', 'dob', 'gst_no'
            );

                foreach ($imp_label_map as $imp_label) {


                   $editData[0]['SuperDistributor'][$imp_label] = $imp_data[$editData[0]['SuperDistributor']['user_id']]['imp'][$imp_label];
                }

                $editData[0]['SuperDistributor']['pan_number'] = $imp_data[$editData[0]['SuperDistributor']['user_id']]['imp']['pan_no'];
                $editData[0]['SuperDistributor']['company'] = $imp_data[$editData[0]['SuperDistributor']['user_id']]['imp']['shop_est_name'];
                $editData[0]['SuperDistributor']['email'] = $imp_data[$editData[0]['SuperDistributor']['user_id']]['imp']['email_id'];
                $editData[0]['SuperDistributor']['map_lat'] = $editData[0]['retailers_location']['latitude'];
                $editData[0]['SuperDistributor']['map_long'] = $editData[0]['retailers_location']['longitude'];
                $editData[0]['SuperDistributor']['mobile'] = $editData[0]['users']['mobile'];
                $editData[0]['SuperDistributor']['rm_id'] = $editData[0]['rm']['id'];
                $editData[0]['SuperDistributor']['rm_name'] = $editData[0]['rm']['name'];

			$this->set('slab',$editData['0']['slabs']['name']);
			$this->set('city',$editData[0]['locator_city']['name']);
			$this->set('state',$editData[0]['locator_state']['name']);
                

            /** IMP DATA ADDED : END**/
		}

			if(!in_array($type, array('d','r'))){
				if($_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){
					if($type == "d"){
						if(!$this->Slaves->query("SELECT id FROM ".$tableName." WHERE sd_id = ".$this->info['id']." and id=".$id) && $dist == null)
						$this->redirect(array('action' => 'allRetailer'));
					}
				}else{
					if(!$this->Slaves->query("SELECT id FROM ".$tableName." WHERE parent_id = ".$this->info['id']." and id=".$id) && $dist == null)
					$this->redirect(array('action' => 'allRetailer'));
				}

				
			}

			


		$this->set('type',$type);
		$this->set('id',$id);
		$this->set('editData',$editData);
		$this->set('modName',$modName);
		//$this->render('showDetails');
	}

	function editRetValidation(){
		$msg = "";
		$empty = array();
		$empty_flag = false;
		$to_save = true;
		$confirm = 0;
		if(isset($this->data['confirm']))
		$confirm = $this->data['confirm'];

		$this->set('data',$this->data);
		if(empty($this->data['Retailer']['name'])){
			$empty[] = 'Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Retailer']['pan_number'])){
			$empty[] = 'Pan Number';
			$empty_flag = true;
			$to_save = false;
		}

		if($this->data['Retailer']['state'] == 0){
			$empty[] = 'State';
			$empty_flag = true;
			$to_save = false;
		}
		if($this->data['Retailer']['city'] == 0){
			$empty[] = 'City';
			$empty_flag = true;
			$to_save = false;
		}
		if($this->data['Retailer']['area_id'] == 0){
			$empty[] = 'Area';
			$empty_flag = true;
			$to_save = false;
		}

		if(empty($this->data['Retailer']['pin'])){
			$empty[] = 'Pin Code';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Retailer']['shopname'])){
			$empty[] = 'Shop Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Retailer']['address'])){
			$empty[] = 'Address';
			$empty_flag = true;
			$to_save = false;
		}
		if($this->data['Retailer']['salesman'] == 0){
			$empty[] = 'Salesman';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Retailer']['mobile_info'])){
			$empty[] = 'Mobile Info';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Retailer']['app_type'])){
			$empty[] = 'App Type';
			$empty_flag = true;
			$to_save = false;
		}

		$sMen = $this->Slaves->query("SELECT id,name,mobile FROM salesmen where dist_id = ".$this->info['id']."");
		$this->set('sMen',$sMen);

		/*$fees = $this->Retailer->query("SELECT sum(shop_transactions.amount) as fee FROM shop_transactions join salesman_transactions on (salesman_transactions.shop_tran_id = shop_transactions.id) where salesman_transactions.payment_type = ".TYPE_SETUP." and shop_transactions.target_id = ".$this->data['Retailer']['id']." group by salesman_transactions.payment_type");
		 $this->set('fees',$fees);*/

		$this->set('appType',implode(",",$this->data['Retailer']['app_type']));

		if(!$to_save){
			$cities = $this->Slaves->query("SELECT id,name FROM locator_city WHERE state_id = ". $this->data['Retailer']['state']." AND toshow = 1 ORDER BY name asc");
			$this->set('cities',$cities);

			$areas = $this->Slaves->query("SELECT id,name FROM locator_area WHERE city_id = ". $this->data['Retailer']['city']." AND toshow = 1 ORDER BY name asc");
			$this->set('areas',$areas);
			$stateName = $this->Slaves->query("SELECT name FROM locator_state WHERE id = ". $this->data['Retailer']['state']);
			$cityName = $this->Slaves->query("SELECT name FROM locator_city WHERE id = ". $this->data['Retailer']['city']);
			$areaName = $this->Slaves->query("SELECT name FROM locator_area WHERE id = ". $this->data['Retailer']['area_id']);
			if($empty_flag){
				$this->data['Retailer']['state'] = $stateName['0']['locator_state']['name'];
				$this->data['Retailer']['city'] = $cityName['0']['locator_city']['name'];
				$this->data['Retailer']['area_id'] = $this->data['Retailer']['area_id'];
				$tArr[0] = $this->data;
				$this->set('editData',$tArr);
				$this->set('type','r');
				$err_msg = '<div class="error_class">'.implode(", ",$empty).' cannot be set empty</div>';
				$this->Session->setFlash($err_msg, true);
				$this->render('/elements/edit_form_ele_retailer','ajax');

			}
		}
		else if($confirm == 0 && $to_save){
			$state = $this->Slaves->query("SELECT name FROM locator_state WHERE id = " . $this->data['Retailer']['state']);
			$city = $this->Slaves->query("SELECT name FROM locator_city WHERE id = " . $this->data['Retailer']['city']);
			$slab = $this->Slaves->query("SELECT name FROM slabs WHERE id = " . $this->data['Retailer']['slab_id']);
			$area = $this->Slaves->query("SELECT name FROM locator_area WHERE id = " . $this->data['Retailer']['area_id']);
			$subarea=$this->Slaves->query("SELECT name FROM subarea WHERE id = " . $this->data['Retailer']['subarea']);
			$this->set('slab',$slab['0']['slabs']['name']);
			$this->set('city',$city['0']['locator_city']['name']);
			$this->set('state',$state['0']['locator_state']['name']);
			$this->set('area',$area['0']['locator_area']['name']);
			$this->set('subarea',$subarea['0']['subarea']['name']);

			$sman = $this->Slaves->query("SELECT name FROM salesmen WHERE id = " . $this->data['Retailer']['salesman']);
			$this->set('salesman',$sman['0']['salesmen']['name']);

			$this->render('confirm_ret_edit','ajax');
		}
		else {
			$id = $this->data['Retailer']['id'];
			$name = $this->data['Retailer']['name'];
			$panNumber = $this->data['Retailer']['pan_number'];

			$email = $this->data['Retailer']['email'];
			$subareaId=$this->data['Retailer']['subarea'];

			$areaId = $this->data['Retailer']['area_id'];
			$pin = $this->data['Retailer']['pin'];
			$shopName = $this->data['Retailer']['shopname'];
			$Address = $this->data['Retailer']['address'];
			$slab = $this->data['Retailer']['slab_id'];
			$salesman = $this->data['Retailer']['salesman'];
			$kyc = addslashes($this->data['Retailer']['kyc']);
			$mInfo = addslashes($this->data['Retailer']['mobile_info']);
			$appType = addslashes($this->data['Retailer']['app_type']);

			//old slab id
			$oldSlabQ = $this->Slaves->query("select slab_id from retailers where id=".$id);
			$oldSlabId = $oldSlabQ['0']['retailers']['slab_id'];
			//

			$modified = date('Y-m-d H:i:s');

			$go = 0;

			if($this->Slaves->query("SELECT id FROM retailers WHERE parent_id = ".$this->info['id']." and id=".$id)){
				if ($this->Distributor->query("update retailers
						set app_type= '".$appType."',
						mobile_info= '".$mInfo."',
						kyc= '".$kyc."',
						salesman= '".$salesman."',
						pan_number='".$panNumber."',
						email='".$email."',
						slab_id='".$slab."',
						modified='".$modified."',
						subarea_id='".$subareaId."',
						verify_flag='0'
						where id=".$id)) {

                    /** IMP DATA ADDED : START**/
                    $imp_update_data = array(
                        'pan_number' => $panNumber,
                        'email' => $email,
                        'name' => $name,
                        'shopname' => $shopName,
                        'address' => $Address
                    );
                    $response = $this->Shop->updateUserLabelData($id,$imp_update_data,$this->Session->read('Auth.User.id'),2);
                    /** IMP DATA ADDED : END**/

					$this->Distributor->query("update unverified_retailers
							set name='".$name."',
							area_id='".$areaId."',
							pin='".$pin."',
							shopname='".$shopName."',
							address='".$Address."',
							modified='".$modified."'
							where retailer_id=".$id);
					if($oldSlabId != $slab)
					$this->Shop->updateSlab($slab,$id,RETAILER);
					$go = 1;
				}else{
					$err = 'The Retailer could not be saved. Please, try again.';
				}
			}else{
				$err = "You don't have permission to edit this retailer.";
			}

			if ($go == 1) {
				$this->set('data',null);
				$retailer = $this->General->getRetailerList($this->info['id'],null,true);

				$this->set('records',$retailer);
				$this->render('/elements/allRetailers','ajax');
			} else {
				$tArr[0] = $this->data;
				$this->set('editData',$tArr);
				$this->set('type','r');
				$err_msg = '<div class="error_class">'.$err.'</div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/edit_form_ele_retailer','ajax');
			}
		}
	}

	function editDistValidation(){

		$msg = "";
		$empty = array();
		$empty_flag = false;
		$to_save = true;
		$confirm = 0;
		if(isset($this->data['confirm']))
		$confirm = $this->data['confirm'];

		$this->set('data',$this->data);

                if($this->data['Distributor']['active_flag']== 1 && empty($this->data['Distributor']['map_lat'])){
			$empty[] = 'Latitude';
			$empty_flag = true;
			$to_save = false;
		}
		if($this->data['Distributor']['active_flag']== 1 && empty($this->data['Distributor']['map_long'])){
			$empty[] = 'Longitute';
			$empty_flag = true;
			$to_save = false;
		}
                if(empty($this->data['Distributor']['name'])){
			$empty[] = 'Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['company'])){
			$empty[] = 'Company Name';
			$empty_flag = true;
			$to_save = false;
		}
                if(empty($this->data['Distributor']['dob'])){
			$empty[] = 'DOB';
			$empty_flag = true;
			$to_save = false;
		}else{
                    $date = explode("-", $this->data['Distributor']['dob']);
                    $this->data['Distributor']['dob'] = $date[2] . "-" . $date[1] . "-" . $date[0];
                }
		if(empty($this->data['Distributor']['state'])){
			$empty[] = 'State';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['city'])){
			$empty[] = 'City';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['area_range'])){
			$empty[] = 'Area Range';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['address'])){
			$empty[] = 'Address';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Distributor']['pan_number'])){
			$empty[] = 'PAN Number';
			$empty_flag = true;
			$to_save = false;
		}
		if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                        if(! is_numeric($this->data['Distributor']['margin']) ){
				$incorrect[] = 'Margin';
				$incorrect_flag = true;
				$to_save = false;
			}
		}
                if(! is_numeric($this->data['Distributor']['retailer_limit']) ){
			$incorrect[] = 'Retailer Limit';
			$incorrect_flag = true;
			$to_save = false;
		}
                if(isset($this->data['Distributor']['commission_type']) && $this->data['Distributor']['commission_type'] == 0){
                        $primary_services = Configure::read('primary_services');
                        $result = array_diff(explode(',', $this->data['Distributor']['active_services']), $primary_services);
                        if(!empty($result)) {
                                $m_msg = "For this services, you have to select Tertiary Commission Type";
				$to_save = false;
                        }
		}

                if($this->data['Distributor']['active_services'] != '' && $this->data['confirm'] != 1) {
                        $services = $this->Slaves->query("SELECT id,name FROM services WHERE id IN (".$this->data['Distributor']['active_services'].")");
                        $this->set('active_services', array_map('current', $services));
                }
                if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
	                if(isset($this->data['Distributor']['margin'])) {
	                        if($this->data['Distributor']['margin'] == '') {
	                                $empty[] = 'Margin';
	                                $empty_flag = true;
	                                $to_save = false;
	                        } else if($this->data['Distributor']['margin'] > 0.5) {
	                                $m_msg = "Margin can't be greater than 0.5 %";
	                                $to_save = false;
	                        } else {
	                                $margin = $this->data['Distributor']['margin'];
	                        }
	                } else {
	                        $margin = 0.5;
	                }
	            }else{
	            	$margin = 0;
	            }
//                if(empty($this->data['Distributor']['gst_no'])){
//			$empty[] = 'GST Number';
//			$empty_flag = true;
//			$to_save = false;
//		}

		if(!$to_save){
                    $this->set('cities',$this->data['Distributor']['city']);

                        $services = $this->Slaves->query("SELECT id,name FROM services WHERE toShow = 1");
                        $this->set('services', array_map('current', $services));
			if($empty_flag){

                                $tArr[0] = $this->data;
				$this->set('editData',$tArr);
				$this->set('type','d');
				$err_msg = '<div class="error_class">'.implode(", ",$empty).' cannot be set empty</div>';
				$this->Session->setFlash($err_msg, true);
				$this->render('/elements/edit_form_ele_retailer','ajax');
			}
            if($incorrect_flag){
				$tArr[0] = $this->data;
				$this->set('editData',$tArr);
				$this->set('type','d');
				$err_msg = '<div class="error_class"> Please provide a correct value of '.implode(", ",$incorrect).' .</div>';
				$this->Session->setFlash($err_msg, true);
				$this->render('/elements/edit_form_ele_retailer','ajax');
			}
                        if($m_msg) {
				$tArr[0] = $this->data;
				$this->set('editData',$tArr);
				$this->set('type','d');
				$err_msg = '<div class="error_class"> '. $m_msg .' </div>';
				$this->Session->setFlash($err_msg, true);
				$this->render('/elements/edit_form_ele_retailer','ajax');

                        }
		}
		else if($confirm == 0 && $to_save){

			$slab = $this->Slaves->query("SELECT name FROM slabs WHERE id = " . $this->data['Distributor']['slab_id']);
			$rm = $this->Slaves->query("SELECT name FROM rm WHERE id = " . $this->data['Distributor']['rm_id']);
			$this->set('slab',$slab['0']['slabs']['name']);
                        $this->set('state',$this->data['Distributor']['state']);
                        $this->set('city',$this->data['Distributor']['city']);

			if(empty($rm)){
				$this->set('rm_name',"");
			}else{
				$this->set('rm_name',$rm['0']['rm']['name']);
			}
                        $this->set('dob', $this->data['Distributor']['dob']);
			$this->set('rm_id',$this->data['Distributor']['rm_id']);
			$this->set('type','d');
			$this->render('confirm_dist_edit','ajax');
		}
		else {

			$id = $this->data['Distributor']['id'];
			$name = addslashes($this->data['Distributor']['name']);
			$panNumber = addslashes($this->data['Distributor']['pan_number']);
			$companyName = addslashes($this->data['Distributor']['company']);
			$email = addslashes($this->data['Distributor']['email']);
                        $state = addslashes($this->data['Distributor']['state']);
                        $city = addslashes($this->data['Distributor']['city']);
                        $map_lat = addslashes($this->data['Distributor']['map_lat']);
                        $map_long = addslashes($this->data['Distributor']['map_long']);
                        $dob = addslashes($this->data['Distributor']['dob']);
                        $commission_type = isset($this->data['Distributor']['commission_type']) ? addslashes($this->data['Distributor']['commission_type']) : $this->Session->read('Auth.commission_type');
                        $active_services = isset($this->data['Distributor']['active_services']) ? addslashes($this->data['Distributor']['active_services']) : $this->Session->read('Auth.active_services');
                        $gst_no = addslashes($this->data['Distributor']['gst_no']);
                        $dist_type = addslashes($this->data['Distributor']['dist_type']);
                        $lead_type = addslashes($this->data['Distributor']['lead_type']);

			$rmQ = $this->Slaves->query("SELECT name FROM rm WHERE id = " . $this->data['Distributor']['rm_id']);
			if(empty($rmQ)){
				$rm_name = "";
			}else{
				$rm_name = $rmQ['0']['rm']['name'];
			}

			$areaRange = $this->data['Distributor']['area_range'];
			$compAddress = addslashes($this->data['Distributor']['address']);
			$slab = $this->data['Distributor']['slab_id'];

			//old slab id
			$oldSlabQ = $this->Slaves->query("SELECT distributors.slab_id,distributors.rm_id,distributors.mobile,distributors.created_rm_id,distributors.user_id FROM distributors WHERE  distributors.id=".$id);
			$oldSlabId = $oldSlabQ['0']['distributors']['slab_id'];
			$oldRmId = $oldSlabQ['0']['distributors']['rm_id'];
			$oldcreatedRmId = $oldSlabQ['0']['distributors']['created_rm_id'];
			$distMobile = $oldSlabQ['0']['distributors']['mobile'];

                        $targetAmount = $this->data['Distributor']['target_amount'];
                        $rentalAmount = $this->data['Distributor']['rental_amount'];
                        $activeFlag = $this->data['Distributor']['active_flag'];
                        $retailerLimit = $this->data['Distributor']['retailer_limit'];
                        $sdAmt = $this->data['Distributor']['sd_amt'];
                        $alternate_mob = $this->data['Distributor']['alternate_mob'];

                        $sdArr = explode("-",$this->data['Distributor']['sd_date']);
                        $sdDate = $sdArr[2]."-".$sdArr[1]."-".$sdArr[0];

                        $sdWdArr = explode("-",$this->data['Distributor']['sd_withdraw_date']);
                        $sdWdDate = $sdWdArr[2]."-".$sdWdArr[1]."-".$sdWdArr[0];

			//
			$modified = date('Y-m-d H:i:s');
			$go = 0;

			if($_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){
				$column_name = "sd_id";
			}else{
				$column_name = "parent_id";
			}

			if($this->Slaves->query("SELECT distributors.id,distributors.company,distributors.mobile FROM distributors WHERE  distributors.".$column_name." = ".$this->info['id']." and distributors.id=".$id)){

                            $targetAmount = empty($targetAmount) ? 0 : $targetAmount;
                            $rentalAmount = empty($rentalAmount) ? 0 : $rentalAmount;
                            $activeFlag = empty($activeFlag) ? 0 : $activeFlag;
                            $sdAmt = empty($sdAmt) ? 0 : $sdAmt;
                            $retailerLimit = empty($retailerLimit) ? 0 : $retailerLimit;

                            if(empty($oldcreatedRmId))$oldcreatedRmId = $this->data['Distributor']['rm_id'];

                            if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
								$condition = ", slab_id='".$slab."'";
								$rm_condition = ", rm_id='".$this->data['Distributor']['rm_id']."'";
							}else{
								$activeFlag = 1;
							}

                                if ($this->Distributor->query("update distributors set name='".$name."',pan_number='".$panNumber."', company='".$companyName."', email='".$email."',  dob='".$dob."',map_lat='".$map_lat."', map_long='".$map_long."', state='".$state."', city='".$city."', area_range='".$areaRange."', address='".$compAddress."'$rm_condition,created_rm_id='$oldcreatedRmId', modified='".$modified."'
                                             , target_amount=$targetAmount ,rental_amount= $rentalAmount ,margin = $margin , active_flag = $activeFlag , retailer_limit = $retailerLimit , sd_amt = $sdAmt , sd_date = '$sdDate' ,sd_withdraw_date = '$sdWdDate',alternate_number = '$alternate_mob',commission_type = '$commission_type',active_services = '$active_services',gst_no = '$gst_no',dist_type = '$dist_type',lead_type = '$lead_type'$condition
                                            where id=".$id)) {
                                    
                                    $area_id = $this->General->getAreaIDByLatLong($map_long, $map_lat);
                                    $this->Retailer->query("INSERT IGNORE INTO retailers_location (retailer_id,area_id,latitude,longitude,updated,user_id,verified) VALUES ('0','$area_id','".$map_lat."','".$map_long."','".date('Y-m-d')."','".$oldSlabQ['0']['distributors']['user_id']."','1')");

                                /** IMP DATA ADDED : START**/
                                $imp_update_data = array(
                                    'name' => $name,
                                    'pan_number' => $panNumber,
                                    'company' => $companyName,
                                    'email' => $email,
                                    'address' => $compAddress,
                                    'alternate_number' => $alternate_mob,
                                    'gst_no' => $gst_no,
                                    'dob' => date('Y-m-d', strtotime($dob))
                                );
                                $response = $this->Shop->updateUserLabelData($id,$imp_update_data,$this->Session->read('Auth.User.id'),3);
                                /** IMP DATA ADDED : END**/


                                        $this->User->query("UPDATE users JOIN distributors On (users.id = distributors.user_id) SET users.active_flag = $activeFlag WHERE distributors.id = $id");
					if($oldSlabId != $slab){
						$this->Shop->updateSlab($slab,$id,DISTRIBUTOR);
						$retailers = $this->Retailer->query("SELECT id FROM retailers WHERE parent_id = $id");
						foreach($retailers as $ret){
							$this->Shop->updateSlab($slab,$ret['retailers']['id'],RETAILER);
						}
						$this->Retailer->query("UPDATE retailers SET slab_id = $slab, modified = '".date('Y-m-d H:i:s')."' WHERE parent_id = $id");
					}
					$go = 1;
					if(!empty($this->data['Distributor']['rm_id']) && $oldRmId != $this->data['Distributor']['rm_id'] ){// execute this block only if rm_id updated
						$rm_details = $this->Slaves->query("SELECT * FROM rm WHERE id=".$this->data['Distributor']['rm_id']);
						if(!empty($rm_details)){
							//-------------- Send email to admin when rm added with distributor ----------
							// variable array contains the values which is to be parsed in email_body
							$varParseArr = array (
                                                     'rm_name'             =>  $rm_details[0]['rm']['name'],
                                                     'master_distributor_company'  =>  $this->info['name'],
													 'distributor_company'  => $companyName,
							);
							$this->General->sendTemplateEmailToAdmin("emailToAdminOnRmAddWithDistributor",$varParseArr);
							//----------------------------------------------------------------------------

							//---- Send SMS ( welcome msg ) to distributor(who became RM) on RM Add with distributor ------
							// variable array contains the values which is to be parsed in sms_body
							$varParseArr = array (
                                                     'rm_mobile'           =>  $rm_details[0]['rm']['mobile'],
                                                     'rm_name'             =>  $rm_details[0]['rm']['name'],
							);
							$this->General->sendTemplateSMSToMobile($distMobile,"smsToDistributorOnRmAddWithDistributor",$varParseArr);
							//----------------------------------------------------------------------------
						}
					}

				}else{
					$err = 'The Distributor could not be saved. Please, try again.';
				}
			}else{
				$err = "You don't have permission to edit this distributor.";
			}

			if ($go == 1) {
				$this->set('data',null);

				if($_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){
					$sd_condition = 'super_distributors.user_id = '. $this->Session->read('Auth.User.id');
					$parent_id = 3;
				}else{
					$parent_id = $this->info['id'];
				}


				$distributors = $this->Distributor->find('all', array(
				'fields' => array('Distributor.*', 'slabs.name','users.mobile','rm.name', 'sum(shop_transactions.amount) as xfer'),
				'conditions' => array('Distributor.parent_id' => $parent_id,$sd_condition),
				'joins' => array(
				array(
								'table' => 'super_distributors',
								'type' => 'left',
								'conditions' => array('Distributor.sd_id = super_distributors.id')
				),
				array(
								'table' => 'slabs',
								'type' => 'inner',
								'conditions'=> array('Distributor.slab_id = slabs.id')
				),
				array(
								'table' => 'users',
								'type' => 'inner',
								'conditions'=> array('Distributor.user_id = users.id')
				),
				array(
								'table' => 'rm',
								'type' => 'left',
								'conditions'=> array('Distributor.rm_id = rm.id')
				),
				array(
								'table' => 'shop_transactions',
								'type' => 'left',
								'conditions'=> array('Distributor.id = shop_transactions.target_id','datediff(now(),shop_transactions.timestamp) < 1 ', 'shop_transactions.type = 1' , 'shop_transactions.confirm_flag != 1')
				)

				),
				'order' => 'Distributor.name asc',
				'group'	=> 'Distributor.id'
				)
            );


                /** IMP DATA ADDED : START**/
                $dist_ids = array_map(function($element){
                    return $element['Distributor']['id'];
                },$distributors);

                $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);

                $dist_imp_label_map = array(
                    'pan_number' => 'pan_no',
                    'company' => 'shop_est_name',
                    'alternate_number' => 'alternate_mobile_no',
                    'email' => 'email_id'
                );
                foreach ($distributors as $key => $distributor) {
                    foreach ($distributor['Distributor'] as $dist_label_key => $value) {
                        $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
                        if( array_key_exists($dist_label_key_mapped,$imp_data[$distributor['Distributor']['id']]['imp']) ){
                            $distributors[$key]['Distributor'][$dist_label_key] = $imp_data[$distributor['Distributor']['id']]['imp'][$dist_label_key_mapped];
                        }
                    }
                }
                /** IMP DATA ADDED : END**/


				$this->set('records',$distributors);
				if($this->data['trans_type'] == 'd'){
					$this->render('/elements/allDistributors','ajax');
				}else{
					$this->render('/elements/allRetailers','ajax');
				}
				//$this->render('/elements/allRetailers','ajax');

			} else {
				$tArr[0] = $this->data;
				$this->set('editData',$tArr);
				$this->set('type','d');
				$err_msg = '<div class="error_class">'.$err.'</div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/edit_form_ele_retailer','ajax');
			}
		}

	}

	function formRetailer(){
		$imp_data = $this->Shop->business_natureTypes();
		$this->set('imp_data',$imp_data);
		$location_data = $this->Shop->location_typeTypes();
		$this->set('location_data',$location_data);
		$turnover_data = $this->Shop->annual_turnoverTypes();
		$this->set('turnover_data',$turnover_data);
		$ownership_data = $this->Shop->shop_ownershipTypes();
		$this->set('ownership_data',$ownership_data);
		$this->render('form_retailer');
	}

	function formSalesman(){

		$states = $this->Slaves->query("SELECT id,name FROM locator_state WHERE toshow = 1 ORDER BY name asc");
		$this->render('form_salesman');
		$this->set('states',$states);
	}
	function formRm(){
		$states = $this->Slaves->query("SELECT id,name FROM locator_state WHERE toshow = 1 ORDER BY name asc");
		$this->render('form_rm');
		$this->set('states',$states);
	}

	/*function formSetUpFee(){
		$sMen = $this->Slaves->query("SELECT id,name,mobile FROM salesmen where dist_id = ".$this->info['id']."");

		$fee = $this->Slaves->query("SELECT sum(amount) as fee,target_id FROM shop_transactions where type = ".SETUP_FEE." group by target_id");
		$fees = array();
		foreach($fee as $f){
			$fees[$f['shop_transactions']['target_id']] = $f['0']['fee'];
		}

		$this->set('fees',$fees);
		$this->set('sMen',$sMen);
		$this->render('form_setupfee');
	}*/

	function backRetailer(){
		$this->set('data',$this->data);
		/*$cities = $this->Retailer->query("SELECT id,name FROM locator_city WHERE state_id = ". $this->data['Retailer']['state']." ORDER BY name asc");
		 $this->set('cities',$cities);
		 $areas = $this->Retailer->query("SELECT id,name FROM locator_area WHERE city_id = ". $this->data['Retailer']['city']." ORDER BY name asc");
		 $this->set('areas',$areas);*/
		$this->render('/elements/shop_form_retailer');
	}

	function backSalesman(){
		$this->set('data',$this->data);
		$this->render('/elements/shop_form_salesman');
	}

	function backSetup(){
		$sMen = $this->Slaves->query("SELECT id,name,mobile FROM salesmen where dist_id = ".$this->info['id']."");
		$this->set('sMen',$sMen);
		$this->set('data',$this->data);
		$this->render('/elements/shop_form_setup');
	}

	function addSetUpFee(){
		$msg = "";
		$empty = array();
		$empty_flag = false;
		$to_save = true;
		$confirm = 0;
		if(isset($this->data['confirm']))
		$confirm = $this->data['confirm'];

		$this->set('data',$this->data);
		if($this->data['SalesmanTransaction']['retailer'] == 0){
			$empty[] = 'Retailer';
			$empty_flag = true;
			$to_save = false;
		}
		if($this->data['SalesmanTransaction']['salesman'] == 0){
			$empty[] = 'Salesman';
			$empty_flag = true;
			$to_save = false;
		}


		if(empty($this->data['SalesmanTransaction']['amount'])){
			$empty[] = 'Amount';
			$empty_flag = true;
			$to_save = false;
		}
		else {
			$this->data['SalesmanTransaction']['amount'] = trim($this->data['SalesmanTransaction']['amount']);
			preg_match('/^[0-9]{1,}$/',$this->data['SalesmanTransaction']['amount'],$matches,0);
			if(empty($matches)){
				$msg = "Enter valid Amount";
				$to_save = false;
			}
		}

		if($this->data['SalesmanTransaction']['payment_mode'] == 0){
			$empty[] = 'Payment Mode';
			$empty_flag = true;
			$to_save = false;
		}

		if(empty($this->data['SalesmanTransaction']['collection_date'])){
			$empty[] = 'Collection Date';
			$empty_flag = true;
			$to_save = false;
		}

		if(!$to_save){
			$sMen = $this->Slaves->query("SELECT id,name,mobile FROM salesmen where dist_id = ".$this->info['id']."");
			$this->set('sMen',$sMen);
			if($empty_flag){
				$err_msg = '<div class="error_class">'.implode(", ",$empty).' cannot be set empty</div>';
				$this->Session->setFlash($err_msg, true);
				$this->render('/elements/shop_form_setup','ajax');
			}
			else {
				$err_msg = '<div class="error_class">'.$msg.'</div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/shop_form_setup','ajax');
			}
		}else if($confirm == 0 && $to_save){
			$ret = $this->Slaves->query("SELECT mobile,name FROM retailers WHERE id = " . $this->data['SalesmanTransaction']['retailer']);
			$sman = $this->Slaves->query("SELECT name FROM salesmen WHERE id = " . $this->data['SalesmanTransaction']['salesman']);

            /** IMP DATA ADDED : START**/
            $temp = $this->Shop->getUserLabelData($this->data['SalesmanTransaction']['retailer'],2,2);
            $imp_data = $temp[$this->data['SalesmanTransaction']['retailer']];
            /** IMP DATA ADDED : END**/


			// $this->set('retailer',$ret['0']['retailers']['name']." (".$ret['0']['retailers']['mobile'].")");
			$this->set('retailer',$imp_data['imp']['name']." (".$ret['0']['retailers']['mobile'].")");
			$this->set('salesman',$sman['0']['salesmen']['name']);
			$this->render('/shops/confirm_setup','ajax');
		}else {
			$shpTranId = $this->Shop->shopTransactionUpdate(SETUP_FEE,$this->data['SalesmanTransaction']['amount'],$this->info['id'],$this->data['SalesmanTransaction']['retailer']);
			$this->data['SalesmanTransaction']['shop_tran_id'] = $shpTranId;

			$colldate = explode("-",$this->data['SalesmanTransaction']['collection_date']);
			$this->data['SalesmanTransaction']['collection_date'] = $colldate['2']."-".$colldate['1']."-".$colldate['0'];
			$this->data['SalesmanTransaction']['created'] = date('Y-m-d H:i:s');
			$this->data['SalesmanTransaction']['payment_type'] = TYPE_SETUP;
			$this->data['SalesmanTransaction']['confirm_flag'] = 1;
			$this->SalesmanTransaction->create();
			if ($this->SalesmanTransaction->save($this->data)) {
				$this->set('data',null);
				$sMen = $this->Slaves->query("SELECT id,name,mobile FROM salesmen where dist_id = ".$this->info['id']."");
				$this->set('sMen',$sMen);
				$this->render('/elements/shop_form_setup','ajax');
			} else {
				$err_msg = '<div class="error_class">The transaction could not be saved. Please, try again.</div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/shop_form_setup','ajax');
			}
		}
	}


	function createSalesman(){
		$msg = "";
		$empty = array();
		$empty_flag = false;
		$to_save = true;
		$confirm = 0;
		if(isset($this->data['confirm']))
		$confirm = $this->data['confirm'];

		$subareas=$this->data['subArea1'];
		$this->set('data',$this->data);
		if(empty($this->data['Salesman']['name']) || !ctype_alnum(str_replace(' ', '', $this->data['Salesman']['name']))){
			$empty[] = 'Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(!empty($this->data['subArea1']) && !ctype_alnum(str_replace(' ', '', $this->data['subArea1']))){
		    $msg = "Area is not valid";
		    $to_save = false;
		}
		if(empty($this->data['Salesman']['mobile'])){
			$empty[] = 'Mobile';
			$empty_flag = true;
			$to_save = false;
		}
		else {
			$this->data['Salesman']['mobile'] = trim($this->data['Salesman']['mobile']);
			//preg_match('/^[6-9][0-9]{9}$/',$this->data['Salesman']['mobile'],$matches,0);
			if($this->General->mobileValidate($this->data['Salesman']['mobile']) == '1'){
				$msg = "Mobile Number is not valid";
				$to_save = false;
			}
		}

		if(isset($data['confirm']) && !is_numeric($data['confirm'])){
		    $msg = "Issue in some input params";
		    $to_save = false;
		}

                $this->data['Salesman']['tran_limit'] = 0;
                $this->data['Salesman']['balance'] = 0;
                /*
                 * Importing Apis Controller functions for OTP send and verify/
                 */

                App::import('Controller', 'Apis');
                $obj = new ApisController;
                $obj->constructClasses();


                //To send OTP to Salesman Mobile Number
                if(($confirm == 0) && !isset($this->data['Salesman']['otp']) && (!$empty_flag)){

                    $sendOTPdata['mobile'] = $this->Session->read('Auth.User.mobile');
                    $sendOTPdata['interest'] = 'Retailer';
                    $sendOTPdata['create_saleman_otp_flag'] = 1;

                    $otpData = $obj->sendOTPToRetDistLeads($sendOTPdata);

                }

		if($to_save){// salesmen creation limit check
			$salesmanLimitCheck = $this->Slaves->query("SELECT count(*) as cnt FROM salesmen WHERE dist_id =".$this->info['id']." and active_flag = 1 ");
			$distributor = $this->Slaves->query("SELECT company , salesman_limit  FROM distributors WHERE id =".$this->info['id']);
			if(!empty($salesmanLimitCheck) && $salesmanLimitCheck[0]['cnt'] >= $distributor['0']['distributors']['salesman_limit'] ){
				$msg = "Overlimit salemen creation .";
				$to_save = false;

				//-------------- email to admin about overlimit salesmen creation ----------------
				$varParseArr = array (
                                 'salesman_name'       =>  $this->data['Salesman']['name'],
                                 'salesman_mobile'     =>  $this->data['Salesman']['mobile'],
                                 'distributor_id'      =>  $this->info['id'],
                                 'distributor_company' =>  $this->info['company']
				);
				$this->General->sendTemplateEmailToAdmin("emailToAdminOnOverLimitSalesmenCreation",$varParseArr);
				//----------------------------------------------------------------------------
			}
		}
		if($to_save){
			$exists = $this->General->checkIfSalesmanExists($this->data['Salesman']['mobile']);

			$retCheck = $this->Slaves->query("SELECT * FROM retailers WHERE mobile='".$this->data['Salesman']['mobile']."'");

			if($exists){
				$msg = "Mobile number is already a salesman";
				$to_save = false;
			}
			else if(!empty($retCheck)){
				$msg = "You cannot make this mobile as your salesman";
				$to_save = false;
			}
		}

		if(!$to_save){
			if($empty_flag){
				$err_msg = '<div class="error_class">'.implode(", ",$empty).' cannot be set empty</div>';
				$this->Session->setFlash($err_msg, true);
				$this->render('/elements/shop_form_salesman','ajax');
			}
			else {
				$err_msg = '<div class="error_class">'.$msg.'</div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/shop_form_salesman','ajax');
			}
		}else if($confirm == 0 && $to_save){

			$this->render('/shops/confirm_salesman','ajax');
		}else {

                    //To verify OTP sent to Salesman Mobile Number
                        $verify_param['mobile'] = $this->Session->read('Auth.User.mobile');
                        $verify_param['otp'] =   $this->data['Salesman']['otp'];
                        $verify_param['interest'] =   'Retailer';

                        $verifyData =  $obj->verifyOTP($verify_param);

                        if($verifyData['status'] =='failure'){

                            $err_msg = '<div class="error_class">Please enter correct OTP. </div>';
                            $this->Session->setFlash(__($err_msg, true));
                            $this->render('/shops/confirm_salesman','ajax');
                            return;
                        }


			$this->data['Salesman']['created'] = date('Y-m-d H:i:s');
			$this->data['Salesman']['dist_id'] = $this->info['id'];
			//$this->data['Salesman']['balance'] = $this->data['Salesman']['tran_limit'];

			if($this->insertSalesman($this->data['Salesman'],$isCreateSalesman=true)){
				$count=$this->mapSalesmanToSubarea($this->data['Salesman']['id'],$subareas);
				$this->set('data',null);
				$this->render('/elements/shop_form_salesman','ajax');
			} else {
				$err_msg = '<div class="error_class">The Salesman could not be saved. Please, try again.</div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/shop_form_salesman','ajax');
			}
		}
	}

	function insertSalesman($salesman,$isCreateSalesman=false,$password=null){
		if(isset($salesman['dist_id']) && isset($salesman['name']) && isset($salesman['mobile'])){
			if(!isset($salesman['created']))
				$salesman['created'] = date('Y-m-d H:i:s');

				if($isCreateSalesman){
				    $exists = $this->General->checkIfUserExists($salesman['mobile']);
				}
				else $exists = true;

			if($exists){
			    $user = $this->General->getUserDataFromMobile($salesman['mobile']);
			    $user['User']['id'] = $user['id'];
			}
			else {
			    $user = $this->General->registerUser($salesman['mobile'],ONLINE_REG,SALESMAN);
			}
			$salesman['user_id'] = $user['User']['id'];
			$salesman['balance'] = 0;
			$salesman['tran_limit'] = 0;
			$this->Salesman->create();

			if($this->Salesman->save($salesman)){
                            //Send message only if CreateSalesman
                            $this->Shop->addUserGroup($salesman['user_id'],SALESMAN);

                            if($isCreateSalesman){
                                $paramdata['PASSWORD'] = $password;
                                $MsgTemplate = $this->General->LoadApiBalance();
                                $content =  $MsgTemplate['CreateSalesman_MSG'];
                                $message = $this->General->ReplaceMultiWord($paramdata,$content);
                            	$this->General->sendMessage($salesman['mobile'], $message, 'shops');
				return true;
                            }
			}
			else
				return false;
		}
		else
			return false;
	}

	function createRm(){

		$msg = "";
		$empty = array();
		$empty_flag = false;
		$to_save = true;
		$confirm = 0;
		if(isset($this->data['confirm']))
		$confirm = $this->data['confirm'];

		$this->set('data',$this->data);
		if(empty($this->data['Rm']['name'])){
			$empty[] = 'Name';
			$empty_flag = true;
			$to_save = false;
		}
                if(!is_numeric($this->data['Rm']['show_sd'])) {
                       $empty[] = 'Show As';
                       $empty_flag = true;
                       $to_save = false;
                }
		if(empty($this->data['Rm']['mobile'])){
			$empty[] = 'Mobile';
			$empty_flag = true;
			$to_save = false;
		}else {
			$this->data['Rm']['mobile'] = trim($this->data['Rm']['mobile']);
			//preg_match('/^[6-9][0-9]{9}$/',$this->data['Rm']['mobile'],$matches,0);
			if($this->General->mobileValidate($this->data['Rm']['mobile']) == '1'){
				$msg = "Mobile Number is not valid";
				$to_save = false;
			}
		}


		if($to_save){
			$exists = $this->General->checkIfUserExists($this->data['Rm']['mobile']);
			if($exists){
			        $to_save = false;
			        $msg = "You cannot make this mobile as your relationship manager ( RM ).";
				/*$user = $this->General->getUserDataFromMobile($this->data['Rm']['mobile']);
				if(($user['group_id'] != MEMBER )){//|| $user['group_id'] == RELATIONSHIP_MANAGER
					$to_save = false;
					$msg = "You cannot make this mobile as your relationship manager ( RM ).";
				}*/
			}
		}

		if(!$to_save){
			if($empty_flag){
				$err_msg = '<div class="error_class">'.implode(", ",$empty).' cannot be set empty</div>';
				$this->Session->setFlash($err_msg, true);
				$this->render('/elements/shop_form_rm','ajax');
			}
			else {
				$err_msg = '<div class="error_class">'.$msg.'</div>';
				$this->Session->setFlash(__($err_msg, true));
				$this->render('/elements/shop_form_rm','ajax');
			}
		}else if($confirm == 0 && $to_save){
			$this->render('/shops/confirm_rm','ajax');
		}else {

                        // check if user already exist exist
			// $exists = $this->General->checkIfUserExists($this->data['Rm']['mobile']);
			if($exists){
			         $to_save = false;
			         $msg = "You cannot make this mobile as your relationship manager ( RM ).";
				/*$user = $this->General->getUserDataFromMobile($this->data['Rm']['mobile']);
				if(($user['group_id'] != MEMBER )){//|| $user['group_id'] == RELATIONSHIP_MANAGER
					$to_save = false;
					$msg = "You cannot make this mobile as your relationship manager ( RM ).";
				} else {
					$userData['User']['id'] = $user['id'];
					$userData['User']['group_id'] = RELATIONSHIP_MANAGER;
					$this->User->save($userData);//make existing user to a RelationShip Manager

				}*/
			}else{
				$user = $this->General->registerUser($this->data['Rm']['mobile'],ONLINE_REG,RELATIONSHIP_MANAGER);

				$user = $user['User'];
			}

			//-------------- Send email to admin on new rm registration ----------------
			// variable array contains the values which is to be parsed in email_body
			$varParseArr = array (
                             'rm_mobile'           =>  $this->data['Rm']['mobile'],
                             'rm_name'             =>  $this->data['Rm']['name'],
                             'distributor_company' =>  "None"
                             );
                             $this->General->sendTemplateEmailToAdmin("emailToAdminOnRmRegistration",$varParseArr);
                             //----------------------------------------------------------------------------

                             $this->data['Rm']['created'] = date('Y-m-d H:i:s');
                             $this->data['Rm']['master_dist_id'] = $this->info['id'];
                             $this->data['Rm']['user_id'] = $user['id'];
                             $this->data['Rm']['show_sd'] = $this->data['Rm']['show_sd'];
                             $this->Rm->create();

                             if ($this->Rm->save($this->data)) {
                                $this->Shop->addUserGroup($user['id'],RELATIONSHIP_MANAGER);
                             	//--------- Send SMS ( welcome msg ) to rm on rm registration ----------------
                             	// variable array contains the values which is to be parsed in sms_body
                             	$varParseArr = array (
                                     'rm_mobile'           =>  $this->data['Rm']['mobile'],
                                     'rm_name'             =>  $this->data['Rm']['name'],
                                     'distributor_company' =>  "None"
                                     );
                                     $this->General->sendTemplateSMSToMobile($this->data['Rm']['mobile'],"smsToRMOnRmRegistration",$varParseArr);
                                     //----------------------------------------------------------------------------

                                     //----- Send SMS ( rm registered ) to super distributors on rm registration --
                                     // variable array contains the values which is to be parsed in sms_body
                                     $varParseArr = array (
                                     'rm_mobile'           =>  $this->data['Rm']['mobile'],
                                     'rm_name'             =>  $this->data['Rm']['name'],
                                     'distributor_company' =>  "None"
                                     );
                                     $this->General->sendTemplateSMSToMobile($_SESSION['Auth']['User']['mobile'],"smsToSuperDistOnRmRegistration",$varParseArr);
                                     //----------------------------------------------------------------------------

                                     //$count=$this->mapSalesmanToSubarea($this->data['Salesman']['id'],$subareas);
                                     $this->set('data',null);
                                     $this->render('/elements/shop_form_rm','ajax');
                             } else {
                             	$err_msg = '<div class="error_class">The rm could not be saved. Please, try again.</div>';
                             	$this->Session->setFlash(__($err_msg, true));
                             	$this->render('/elements/shop_form_rm','ajax');
                             }
		}
	}
	function mapSalesmanToSubarea($sId,$subArea)
	{
		/*$sIdResult=$this->User->query("select id from salesmen where mobile='$sMobile'");
		 $sId=$sIdResult['0']['salesmen']['id'];*/

		$subareaList =split(" ",$subArea);

		$count=0;
		foreach($subareaList as $s)
		{

			$subareaIdResult=$this->Slaves->query("select id from subarea where name='$subareaList[$count]'");
			$subAreaId=$subareaIdResult['0']['subarea']['id'];

			$chkIfExistResult=$this->Slaves->query("select id from salesmen_subarea where  salesmen_id=$sId and subarea_id=$subAreaId ");
			if(empty($chkIfExistResult))
			{
				$this->User->query("insert into salesmen_subarea (salesmen_id,subarea_id) values($sId,$subAreaId)");
				$count++;
			}
		}

		return $count;
	}



	function salesmanTran($frm=null,$to=null,$id=null){
		if(is_null($frm)){
			$frm = date('d-m-Y');
		}
		if(is_null($to)){
			$to = date('d-m-Y');
		}
		$query = "";
		if($id != null && $id != 0){
			$query = " AND salesman = $id";
		}

		$frmArr = date('Y-m-d',strtotime($frm));
		$toArr = date('Y-m-d',strtotime($to));

		if($id != 0){
			$salesman = $this->Slaves->query("SELECT * FROM salesmen where dist_id = ".$this->info['id']." AND id = $id");
			$this->set('sinfo',$salesman);
		}
		$salesmans = $this->Slaves->query("SELECT * FROM salesmen where dist_id = ".$this->info['id']." AND active_flag = 1 order by id");

		$data = array();
		$collections = $this->Slaves->query("SELECT salesman_collections.* FROM salesman_collections WHERE salesman_collections.distributor_id = ".$this->info['id']." AND salesman_collections.date >= '$frmArr' AND salesman_collections.date <= '$toArr' $query order by salesman_collections.date");
		$topups = $this->Slaves->query("SELECT salesman_transactions.salesman,salesman_transactions.collection_date,salesman_transactions.payment_type,if(salesman_transactions.payment_type = 1,sum(salesman_transactions.collection_amount),sum(shop_transactions.amount)) as amt FROM salesman_transactions inner join salesmen ON (salesman_transactions.salesman = salesmen.id) inner join shop_transactions ON (shop_transactions.id = salesman_transactions.shop_tran_id) WHERE shop_transactions.source_id = ".$this->info['id']." AND collection_date >= '$frmArr' AND collection_date <= '$toArr' $query AND shop_transactions.confirm_flag != 1 group by salesman,collection_date,payment_type");
		foreach($collections as $coll){
			if(empty($coll['salesman_collections']['collection_amount'])){
				$coll['salesman_collections']['collection_amount'] = 0;
			}
			$data[$coll['salesman_collections']['date']][$coll['salesman_collections']['salesman']]['collection'][$coll['salesman_collections']['payment_type']] = $coll['salesman_collections']['collection_amount'];
		}
		foreach($topups as $topup){
			if(empty($topup['0']['amt'])){
				$topup['0']['amt'] = 0;
			}
			$data[$topup['salesman_transactions']['collection_date']][$topup['salesman_transactions']['salesman']]['topup'][$topup['salesman_transactions']['payment_type']] = $topup['0']['amt'];
		}
		$this->set('data',$data);
		$this->set('salesmans',$salesmans);
		$this->set('from',$frm);
		$this->set('to',$to);
		$this->set('id',$id);
		$this->render('form_salesman_tran');
	}

	/*function addSalesmanCollection(){
		$id = trim($_REQUEST['id']);
		$date = trim($_REQUEST['date']);

		$topup = trim($_REQUEST['topup']);
		$setup = trim($_REQUEST['setup']);
		$cash = trim($_REQUEST['cash']);
		$cheque = trim($_REQUEST['cheque']);

		if(empty($topup))$topup = 0;
		if(empty($setup))$setup = 0;
		if(empty($cash))$cash = 0;
		if(empty($cheque))$cheque = 0;

		$data = $this->Slaves->query("SELECT * FROM salesman_collections WHERE date = '$date' AND salesman = $id order by payment_type");
		$salesman = $this->Slaves->query("SELECT salesmen.dist_id,salesmen.name,salesmen.balance FROM salesmen WHERE id = $id");
		$distMobile = $this->Slaves->query("Select mobile from users where id = '".$this->info['user_id']."'");
		$distName = $this->info['name'];
		$salesmanName = $salesman[0]['salesmen']['name'];
		$MsgTemplate = $this->General->LoadApiBalance();
		if($cash + $cheque == $topup + $setup){
			if(empty($data) && $salesman['0']['salesmen']['dist_id'] == $this->info['id']){
				$this->Retailer->query("INSERT INTO salesman_collections (salesman,distributor_id,date,payment_type,collection_amount,created) VALUES ($id,".$this->info['id'].",'$date',1,$setup,'".date('Y-m-d H:i:s')."')");
				$this->Retailer->query("INSERT INTO salesman_collections (salesman,distributor_id,date,payment_type,collection_amount,created) VALUES ($id,".$this->info['id'].",'$date',2,$topup,'".date('Y-m-d H:i:s')."')");
				$this->Retailer->query("INSERT INTO salesman_collections (salesman,distributor_id,date,payment_type,collection_amount,created) VALUES ($id,".$this->info['id'].",'$date',3,$cash,'".date('Y-m-d H:i:s')."')");
				$this->Retailer->query("INSERT INTO salesman_collections (salesman,distributor_id,date,payment_type,collection_amount,created) VALUES ($id,".$this->info['id'].",'$date',4,$cheque,'".date('Y-m-d H:i:s')."')");

				$diff_top = $topup;
				$diff_set = $setup;
				$remainingbal = $salesman[0]['salesmen']['balance']+$diff_top;
				$this->Retailer->query("UPDATE salesmen SET balance = balance + $diff_top, setup_pending = setup_pending - $diff_set WHERE id = $id");

                                $paramdata['DIFF_TOP'] = $diff_top;
                                $paramdata['SALESMAN_NAME'] = $salesmanName;
                                $paramdata['REMAINING_BALANCE'] = $remainingbal;
                                $content =  $MsgTemplate['Salesman_Collection_MSG'];
                                $msg = $this->General->ReplaceMultiWord($paramdata,$content);
                                $this->General->sendMessage($distMobile[0]['users']['mobile'],$msg,'notify');

//			        $this->General->sendMessage($distMobile[0]['users']['mobile'],"Dear Sir, Amount of Rs. $diff_top collected from $salesmanName and now salesman topup limit is Rs. $remainingbal",'notify');
				echo "done";
			}
			else if(!empty($data) && $data[0]['salesman_collections']['distributor_id'] == $this->info['id']){
				$diff_top = $topup - $data[1]['salesman_collections']['collection_amount'];
				$diff_set = $setup - $data[0]['salesman_collections']['collection_amount'];
				$remainingbal = $salesman[0]['salesmen']['balance']+$diff_top;
				$this->Retailer->query("UPDATE salesman_collections SET collection_amount=$topup WHERE id = " . $data[1]['salesman_collections']['id']);
				$this->Retailer->query("UPDATE salesman_collections SET collection_amount=$setup WHERE id = " . $data[0]['salesman_collections']['id']);
				$this->Retailer->query("UPDATE salesman_collections SET collection_amount=$cash WHERE id = " . $data[2]['salesman_collections']['id']);
				$this->Retailer->query("UPDATE salesman_collections SET collection_amount=$cheque WHERE id = " . $data[3]['salesman_collections']['id']);

				$this->Retailer->query("UPDATE salesmen SET balance = balance + $diff_top, setup_pending = setup_pending - $diff_set WHERE id = $id");

                                $paramdata['DIFF_TOP'] = $diff_top;
                                $paramdata['SALESMAN_NAME'] = $salesmanName;
                                $paramdata['REMAINING_BALANCE'] = $remainingbal;
                                $content =  $MsgTemplate['Salesman_Collection_MSG'];
                                $msg = $this->General->ReplaceMultiWord($paramdata,$content);
                                $this->General->sendMessage($distMobile[0]['users']['mobile'],$msg,'notify');

//                                $this->General->sendMessage($distMobile[0]['users']['mobile'],"Dear Sir, Amount of Rs. $diff_top collected from $salesmanName and now salesman topup limit is Rs. $remainingbal",'notify');
				echo "done";
			}
			else {
				echo "Sorry, you cannot edit this entry";
			}
		}
		else {
			echo "Sum of cash & cheque should match with your total topup & setup collections";
		}

		$this->autoRender = false;
	}*/

	function create_unverified_retailer($retailer_id, $retailer){
		if(isset($retailer) && isset($retailer_id)){
			$this->User->query("insert into unverified_retailers
					(retailer_id, name, address, shopname, shop_type, shop_type_value, location_type,
					created, modified)
					values ('".$retailer_id."', '".mysql_real_escape_string($retailer['name'])."',
					'".mysql_real_escape_string($retailer['address'])."', '".mysql_real_escape_string($retailer['shopname'])."',
					'".$retailer['shop_type']."', '".mysql_real_escape_string($retailer['shop_type_value'])."',
                    '".$retailer['location_type']."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
            /** IMP DATA ADDED : START**/
                $imp_update_data = array(
                'address' => mysql_real_escape_string($retailer['address']),
                'name' => mysql_real_escape_string($retailer['name']),
                'shopname' => mysql_real_escape_string($retailer['shopname']),
                'retailer_type'=> mysql_real_escape_string($retailer['retailer_type']),
                'location_type'=>mysql_real_escape_string($retailer['location_type']),
                'turnover_type'=>mysql_real_escape_string($retailer['turnover_type']),
                'ownership_type'=>mysql_real_escape_string($retailer['ownership_type'])

            );
            $this->Shop->updateUserLabelData($retailer_id,$imp_update_data,$retailer_id,2);
            /** IMP DATA ADDED : END**/
		}
	}

	function createRetailer($retailer = array()){
		$is_trial = false;
		if(!empty($retailer)){
			$this->data['confirm'] = 1;
			if($retailer['api_flow'] == "verify_lead"){
				//Auto sign up of retailers from Pay1 Merchant App and pay1.in/partners
				$this->data['dist'] = $retailer['distributor_user_id'];
				$this->data['Retailer']['name'] = $retailer['name'];
				$this->data['Retailer']['shopname'] = $retailer['shop_name'];
				$this->data['Retailer']['mobile'] = $retailer['phone'];
				$this->data['Retailer']['email'] = $retailer['email'];
				$this->data['Retailer']['rental_flag'] = 0;
				$this->data['Retailer']['retailer_type'] = 3;
				$this->data['Retailer']['trial_flag'] = 1;

				$this->data['Retailer']['retailer_type']  = $retailer['retailer_type'];
				$this->data['Retailer']['location_type']  = $retailer['location_type'];
				$this->data['Retailer']['turnover_type']  = $retailer['turnover_type'];
				$this->data['Retailer']['ownership_type'] = $retailer['ownership_type'];

				$this->data['address']['address'] = "";
				$this->data['address']['area'] = $retailer['area'];
				$this->data['address']['city'] = $retailer['city'];
				$this->data['address']['state'] = $retailer['state'];
				$this->data['address']['pincode'] = $retailer['pin_code'];

				$this->data['r_u_d'] = $retailer['r_u_d'];

				$this->data['password'] = $retailer['pin'];
			}
			else if($retailer['api_flow'] == "trial"){
				//Create retailer from Distributor App by Distributors and Salesmen
				$is_trial = true;
				$this->data['dist'] = $retailer['distributor_user_id'];
				$this->data['Retailer']['name'] = $retailer['name'];
				$this->data['Retailer']['shopname'] = $retailer['shop_name'];
				$this->data['Retailer']['mobile'] = $retailer['mobile'];

                                $this->data['Retailer']['retailer_type']  = $retailer['retailer_type'];
				$this->data['Retailer']['location_type']  = $retailer['location_type'];
				$this->data['Retailer']['turnover_type']  = $retailer['turnover_type'];
				$this->data['Retailer']['ownership_type'] = $retailer['ownership_type'];

				$this->data['Retailer']['rental_flag'] = 0;
				$this->data['Retailer']['retailer_type'] = 2;
				$this->data['Retailer']['trial_flag'] = 1;

                            $this->data['Retailer']['otp'] = empty($retailer['otp']) ? 0 : $retailer['otp'];
                            $app_verify_otp = empty($retailer['otp_verify_flag']) ?  0 : $retailer['otp_verify_flag'];

                            /*
                             * to stop reatiler creation on older app version
                            */
                            $this->data['Retailer']['stop_creation'] = empty($retailer['app_version_code']) ? 0 : $retailer['app_version_code'];
			}
			else {
				//Create retailer from Distributor App (Not used now)
				$this->data['dist'] = $retailer['d_uid'];
				$this->data['Retailer']['name'] = $retailer['r_n'];
				$this->data['Retailer']['mobile'] = $retailer['r_m'];
				$this->data['Retailer']['shopname'] = $retailer['s_n'];
				$this->data['Retailer']['address'] = $retailer['r_add'];
				if(isset($retailer['s_t'])){
					$shop_type_index = array_search($retailer['s_t'], $this->Shop->business_natureTypes());
					if(!$shop_type_index){
						$this->data['Retailer']['shop_type'] = 8;
						$this->data['Retailer']['shop_type_value'] = $retailer['s_t'];
					}
					else {
						$this->data['Retailer']['shop_type'] = $shop_type_index;
					}
				}
				if(isset($retailer['l_t'])){
					$location_type_index = array_search($retailer['l_t'], $this->Shop->location_typeTypes());
					if($location_type_index){
						$this->data['Retailer']['location_type'] = $location_type_index;
					}
				}
				$this->data['Retailer']['trial_flag'] = 1;
// 				$this->data['Retailer']['shop_structure'] = $retailer['s_s'];
				$this->data['Retailer']['rental_flag'] = 0;
				$this->data['Retailer']['retailer_type'] = 2;

				$this->data['address']['address'] = $retailer['r_add'];
				$this->data['address']['area'] = $retailer['r_a'];
				$this->data['address']['city'] = $retailer['r_c'];
				$this->data['address']['state'] = $retailer['r_s'];
				$this->data['address']['pincode'] = $retailer['r_pc'];
				if(is_float($retailer['r_la']) && is_float($retailer['r_lo'])){
					$this->data['address']['latitude'] = $retailer['r_la'];
					$this->data['address']['longitude'] = $retailer['r_lo'];
				}
			}
		}
        else{
        	//Create retailer through SMS and Distributor panel
        	$is_trial = true;

                if((isset($this->data['Retailer']['shopname']))){

                    App::import('Controller', 'Apis');
                    $obj = new ApisController;
                    $obj->constructClasses();

                    if(!isset($this->data['Retailer']['otp'])){
                        $sendOTPdata['mobile'] = $this->Session->read('Auth.User.mobile');
                        $sendOTPdata['interest'] = 'Retailer';
                        $sendOTPdata['create_ret_otp_flag'] = 1;

                        $otpData = $obj->sendOTPToRetDistLeads($sendOTPdata);
                    }
                    $otp_flag = true;
                }
        }

                $retailer_type_label = $this->Shop->business_natureTypes($this->data['Retailer']['retailer_type']);
		$this->set('retailer_type_label',$retailer_type_label);
				//print_r($retailer_type_label);
		$location_type_label = $this->Shop->location_typeTypes($this->data['Retailer']['location_type']);
		$this->set('location_type_label',$location_type_label);

		$turnover_type_label = $this->Shop->annual_turnoverTypes($this->data['Retailer']['turnover_type']);
		$this->set('turnover_type_label',$turnover_type_label);

		$ownership_type_label = $this->Shop->shop_ownershipTypes($this->data['Retailer']['ownership_type']);
		$this->set('ownership_type_label',$ownership_type_label);


        $authData = $this->Session->read('Auth');

         if(!empty($authData) && $authData['User']['group_id'] == SALESMAN){
            $salesman = $authData;
            $authData = $this->Shop->getShopDataById($salesman['dist_id'], DISTRIBUTOR);
            $authData['User']['group_id'] = SALESMAN;
            $authData['User']['mobile'] = $salesman['mobile'];
         } elseif(!empty($this->data['dist'])){

        		$authData = $this->Shop->getShopData($this->data['dist'], DISTRIBUTOR);
			//$getUserdata = $this->General->getUserDataFromId($this->data['dist']);
			$authData['User']['group_id'] = DISTRIBUTOR;
			$authData['User']['id'] = $authData['user_id'];
			$authData['User']['mobile'] = $authData['mobile'];
			$authData['User']['name'] = $authData['name'];
		}

		if(!in_array($authData['User']['group_id'], array(DISTRIBUTOR, SALESMAN))){
			$this->redirect('/');
		}

	    $this->info = $authData;

		$msg = "";
		$empty = array();
		$empty_flag = false;
		$to_save = true;
		$confirm = 0;
		if(isset($this->data['confirm']))
		$confirm = $this->data['confirm'];

		$this->set('data',$this->data);
		if(empty($this->data['Retailer']['name']) && !$is_trial){
			$empty[] = 'Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Retailer']['mobile'])){
			$empty[] = 'Mobile';
			$empty_flag = true;
			$to_save = false;
		}
		else {
			$this->data['Retailer']['mobile'] = trim($this->data['Retailer']['mobile']);
			//preg_match('/^[6-9][0-9]{9}$/',$this->data['Retailer']['mobile'],$matches,0);
			if($this->General->mobileValidate($this->data['Retailer']['mobile']) == 1){
				$msg = "Mobile Number is not valid";
				$to_save = false;
			}
		}
		/*if($this->data['Retailer']['state'] == 0){
			$empty[] = 'State';
			$empty_flag = true;
			$to_save = false;
			}
			if($this->data['Retailer']['city'] == 0){
			$empty[] = 'City';
			$empty_flag = true;
			$to_save = false;
			}
			if($this->data['Retailer']['area_id'] == 0){
			$empty[] = 'Area';
			$empty_flag = true;
			$to_save = false;
			}

			if(empty($this->data['Retailer']['pin'])){
			$empty[] = 'Pin Code';
			$empty_flag = true;
			$to_save = false;
			}*/
		if(empty($this->data['Retailer']['shopname']) && empty($retailer)){
			$empty[] = 'Shop Name';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Retailer']['address']) && empty($retailer) && !$is_trial){
			$empty[] = 'Address';
			$empty_flag = true;
			$to_save = false;
		}

		if(empty($this->data['Retailer']['retailer_type']) && empty($retailer))
		{
			$empty[] = 'Retailer Type';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Retailer']['location_type']) && empty($retailer))
		{
			$empty[] = 'Location Type';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Retailer']['turnover_type']) && empty($retailer))
		{
			$empty[] = 'Turnover Type';
			$empty_flag = true;
			$to_save = false;
		}
		if(empty($this->data['Retailer']['ownership_type']) && empty($retailer))
		{
			$empty[] = 'Ownership Type';
			$empty_flag = true;
			$to_save = false;
		}

                if($to_save){
			//if(!isset($this->data['Retailer']['rental_flag'])){
				$this->data['Retailer']['rental_flag'] = 0;
			///}

			$exists = $this->General->checkIfUserExists($this->data['Retailer']['mobile']);
                        if($exists){
                                $to_save = false;
                                $msg = "You cannot make this mobile as your retailer";
			}

                        /*
                         * To Stop Creation of Retailers only by using Distributor App
                         */
                        if(isset($this->data['Retailer']['stop_creation']) && (!$this->data['Retailer']['stop_creation'])){
                            $this->info['retailer_creation'] = 0;
                        }
                        /*
                         * End of Stop Reatailer Creation
                        */


                        /*
                         * To Stop Creation of Retailers only by using Distributor App
                         */
                        if(isset($this->data['Retailer']['stop_creation']) && (!$this->data['Retailer']['stop_creation'])){
                            $this->info['retailer_creation'] = 0;
                        }
                        /*
                         * End of Stop Reatailer Creation
                        */


			if($this->info['retailer_creation'] == 0){
				$msg = "You cannot create a retailer, contact pay1";

                            /*
                                * To Stop Creation of Retailers only by using Distributor App
                                */
                                if(isset($this->data['Retailer']['stop_creation']) && (!$this->data['Retailer']['stop_creation'])){
                                $msg = "Due to security reason, please create Retailer using SMS or ".DISTPANEL_URL." \n"
                                        . "This service will resume before the 11th of July 2016.";
                                }
                            /*
                              * End of Stop Reatailer Creation
                           */

				$to_save = false;
			}

			$count = count($this->retailers);
			if($this->info['retailer_limit'] > 0 && $count >= $this->info['retailer_limit']){
				$msg = "You have reached your retailer-creation limit. You cannot create retailer now";
				$to_save = false;
			}

//			if($this->data['Retailer']['rental_flag'] == 0 && $this->info['kits'] == 0){
//				$msg = "You have 0 kits left. Buy more retailer kits to enjoy this benefit";
//				$to_save = false;
//			}
		}

		if(!$to_save){
			/*$cities = $this->Retailer->query("SELECT id,name FROM locator_city WHERE state_id = ". $this->data['Retailer']['state']." ORDER BY name asc");
			 $this->set('cities',$cities);

			 $areas = $this->Retailer->query("SELECT id,name FROM locator_area WHERE city_id = ". $this->data['Retailer']['city']." ORDER BY name asc");
			 $this->set('areas',$areas);

			 $subareas=$this->Retailer->query("select id,name from subarea where area_id=".$this->data['Retailer']['area_id']);
			 $this->set('subareas',$subareas);*/

			if($empty_flag){
				if(empty($retailer)){
					$err_msg = '<div class="error_class">'.implode(", ",$empty).' cannot be set empty</div>';
					$this->Session->setFlash($err_msg, true);
					$this->render('/elements/shop_form_retailer','ajax');
				}
				else{
					return array("status" => "failure", "description" => $msg);
				}
			}
			else {
				if(empty($retailer)){
					$err_msg = '<div class="error_class">'.$msg.'</div>';
					$this->Session->setFlash(__($err_msg, true));
					$this->render('/elements/shop_form_retailer','ajax');
				}
				else{
					return array("status" => "failure", "description" => $msg);
				}
			}
		}
		else if($confirm == 0 && $to_save){
			/*$state = $this->Retailer->query("SELECT name FROM locator_state WHERE id = " . $this->data['Retailer']['state']);
			 $city = $this->Retailer->query("SELECT name FROM locator_city WHERE id = " . $this->data['Retailer']['city']);
			 $slab = $this->Retailer->query("SELECT name FROM slabs WHERE id = " . $this->data['Retailer']['slab_id']);
			 $area = $this->Retailer->query("SELECT name FROM locator_area WHERE id = " . $this->data['Retailer']['area_id']);


			 $this->set('slab',$slab['0']['slabs']['name']);
                        $this->set('city',$city['0']['locator_city']['name']);
			 $this->set('state',$state['0']['locator_state']['name']);
			 $this->set('area',$area['0']['locator_area']['name']);*/
			if(empty($retailer)){
				$this->render('/shops/confirm_retailer','ajax');
			}
			else{
				return array("status" => "failure", "description" => $msg);
			}
		}
		else {

                    App::import('Controller', 'Apis');
                    $obj = new ApisController;
                    $obj->constructClasses();

                    if($app_verify_otp == 0 && $this->data['Retailer']['stop_creation']){

                            $sendOTPdata['mobile'] = $this->Session->read('Auth.User.mobile');
                            $sendOTPdata['interest'] = 'Distributor';
                            $sendOTPdata['create_ret_otp_flag'] = 1;
                            $otpData = $obj->sendOTPToRetDistLeads($sendOTPdata);

                            return $otpData;

                    }

                    //only if otp sent i.e. otp flag set
                    if($otp_flag || $app_verify_otp){

                        $verify_param['mobile'] = $this->Session->read('Auth.User.mobile');
                        $verify_param['otp'] =   $this->data['Retailer']['otp'];
                        $verify_param['interest'] =   'Retailer';

                        $verifyData =  $obj->verifyOTP($verify_param);

                        if($verifyData['status'] =='failure'){


                            //Return Failure for Distributor & Salesman App
                            if($app_verify_otp){

                                return $verifyData;
                            }

                            $err_msg = '<div class="error_class">Please enter correct OTP. </div>';
                            $this->Session->setFlash(__($err_msg, true));
                            $this->render('/shops/confirm_retailer','ajax');
                            return;
                        }
                    }

			$this->data['Retailer']['created'] = date('Y-m-d H:i:s');
			$this->data['Retailer']['modified'] = date('Y-m-d H:i:s');
			$this->data['Retailer']['balance'] = 0;
			/*$state = $this->Retailer->query("SELECT name FROM locator_state WHERE id = " . $this->data['Retailer']['state']);
			 $this->data['Retailer']['state'] = $state['0']['locator_state']['name'];

			 $city = $this->Retailer->query("SELECT name FROM locator_city WHERE id = " . $this->data['Retailer']['city']);
			 $this->data['Retailer']['city'] = $city['0']['locator_city']['name'];*/

			$default = $this->Slaves->query("SELECT id FROM salesmen WHERE mobile = '" . $authData['User']['mobile']."'");

			if(!empty($default)){
				$this->data['Retailer']['salesman'] = $default['0']['salesmen']['id'];
				$this->data['Retailer']['maint_salesman'] = $default['0']['salesmen']['id'];
			}

			if(!$exists){
				$user = $this->General->registerUser($this->data['Retailer']['mobile'],RETAILER_REG,RETAILER, $this->data['password']);
				$user = $user['User'];
				$new_user = 1;
			}
			/*else if($user['group_id'] == MEMBER){
				$userData['User']['id'] = $user['id'];
				$userData['User']['group_id'] = RETAILER;
				$this->User->save($userData);//make already user to a retailer
			}*/
			$this->data['Retailer']['user_id'] = $user['id'];
			$this->data['Retailer']['parent_id'] = $this->info['id'];
			$this->data['Retailer']['slab_id'] = $this->info['slab_id'];

			$this->Retailer->create();
			if ($this->Retailer->save($this->data)) {
//                                $business_nature_types = $this->Shop->business_natureTypes();
//                                $location_types = $this->Shop->location_typeTypes();
//                                $annual_turnover_types = $this->Shop->annual_turnoverTypes();
//                                $shop_ownership_types = $this->Shop->shop_ownershipTypes();
                                /** IMP DATA ADDED : START**/
                                $imp_update_data = array(
                                    'name' => $this->data['Retailer']['name'],
                                    'shopname' => $this->data['Retailer']['shopname'],
                                    'shop_area' => $this->data['Retailer']['area'],
                                    'shop_city' => $this->data['Retailer']['city'],
                                    'shop_state' => $this->data['Retailer']['state'],
                                    'shop_pincode' => $this->data['Retailer']['pincode'],
                                    'address' => $this->data['Retailer']['address'],
                                    'business_nature' => $this->data['Retailer']['retailer_type'],
                                    'location_type' => $this->data['Retailer']['location_type'],
                                    'annual_turnover' => $this->data['Retailer']['turnover_type'],
                                    'shop_ownership' => $this->data['Retailer']['ownership_type']
                                );
                                $this->Shop->updateUserLabelData($user['id'],$imp_update_data,$this->Session->read('Auth.User.id'),2);
                                /** IMP DATA ADDED : END**/
				//$this->General->updateLocation($this->data['Retailer']['area_id']);
			         $this->Shop->addUserGroup($user['id'],RETAILER);
				$this->create_unverified_retailer($this->Retailer->id, $this->data['Retailer']);

				if(isset($this->data['address']))
					$this->General->updateRetailerAddress($this->Retailer->id, $this->data['Retailer']['user_id'], $this->data['address']);

				$this->General->makeOptIn247SMS($this->data['Retailer']['mobile']);


                                //$paramdata['RETAILER_MOBILE_NUMBER'] = $this->data['Retailer']['mobile'];
//                                $MsgTemplate = $this->General->LoadApiBalance();
//		                $sms = $MsgTemplate['CreateRetailer_App_MSG'];

				//$sms = $this->getRentalSMS($this->data['Retailer']['rental_flag'],$this->info['rental_amount'],$this->info['target_amount']);

//				$this->General->sendMessage($user['mobile'],$sms,'payone');
				if($this->data['Retailer']['rental_flag'] == 1){
					$mail_subject = "New Retailer Created On Rental";
 				}else {
					$mail_subject = "New Retailer Created Via Kit";
				}
				$mail_body = "Distributor: " . $this->info['company'] . "<br/>";
				$mail_body .= "Retailer: " . $this->data['Retailer']['shopname'] . "<br/>";
				$mail_body .= "Address: " . $this->data['Retailer']['address'];
// 				$this->General->sendMails($mail_subject, $mail_body,array('rm@pay1.in','tadka@pay1.in'));

				/*if(!empty($retailer)){
					$this->User->query("update users set passFlag = 1 where mobile = '".$this->data['Retailer']['mobile']."'");
				}*/
				if(!empty($retailer) && $this->data['r_u_d']){
					$mail_subject_rud = "New Retailer Registered: Assign Distributor";
					$mail_body_rud = "A new retailer was created just now. A distributor needs to be assigned.<br/>";
					$mail_body_rud .= "Name: ". $this->data['Retailer']['name'] . "<br/>";
					$mail_body_rud .= "Mobile: " . $this->data['Retailer']['mobile'] . "<br/>";
					$mail_body_rud .= "Area: " . $this->data['address']['area'] . "<br/>";
					$mail_body_rud .= "City: " . $this->data['address']['city'] . "<br/>";
					$mail_body_rud .= "State: " . $this->data['address']['state'] . "<br/>";
					$mail_body_rud .= "Pin code: " . $this->data['address']['pincode'] . "<br/>";
					$emails_rud = array('info@pay1.in');
					$this->General->sendMails($mail_subject_rud, $mail_body_rud, $emails_rud, "mail");
				}

				$this->Shop->updateSlab($this->data['Retailer']['slab_id'],$this->Retailer->id,RETAILER);

				if($this->data['Retailer']['rental_flag'] == 0){
					if($this->info['commission_kits_flag'] == 1){
						if($this->info['kits'] == -1){
							$this->Retailer->query("UPDATE distributors SET discounted_money=discounted_money+".$this->info['discount_kit']." WHERE id = ".$this->info['id']);
						}
						else {
							$this->Retailer->query("UPDATE distributors SET kits=kits-1,discounted_money=discounted_money+".$this->info['discount_kit']." WHERE id = ".$this->info['id']);
						}
					}
					else if($this->info['kits'] > 0){
						$this->Retailer->query("UPDATE distributors SET kits=kits-1 WHERE id = ".$this->info['id']);
					}
				}

				/*if(!empty($default)){
					$date=Date("Y-m-d");
					$date_created=Date("Y-m-d H:i:s");
					$st_id = $this->Shop->shopTransactionUpdate(SETUP_FEE,SETUP_FEE_AMT,$this->info['id'],$this->Retailer->id);
					//for trial put insert amount=0 in sst but in st put 1500
					$this->Retailer->query("INSERT INTO salesman_transactions(shop_tran_id,salesman,payment_mode,payment_type,collection_amount,collection_date,created) VALUES ($st_id,".$default['0']['salesmen']['id'].",1,1,0,'$date','$date_created')");
					}*/
				if(empty($retailer)){
					$this->set('data',null);
					$this->render('/elements/shop_form_retailer','ajax');
				}
				else{
					App::import('Controller', 'Distributors');
					$ini = new DistributorsController;
					$ini->constructClasses();
					$retailer_detail = $ini->getRetailer(array('r_id' => $this->Retailer->id));
					return array("status" => "success", "description" => array(
								'User' => $this->Retailer->read(),
								'retailer' => $retailer_detail['description']
							));
				}
			} else {
				if(empty($retailer)){
					$err_msg = '<div class="error_class">The Retailer could not be saved. Please, try again.</div>';
					$this->Session->setFlash(__($err_msg, true));
					$this->render('/elements/shop_form_retailer','ajax');
				}
				else {
					return array("status" => "failure", "description" => $msg);
				}
			}
		}
	}

	function getRentalSMS($rental_flag,$rental_amount,$target_amount){
		$msg = "";
		if($rental_flag == 1){
			if($rental_amount > 0 && $target_amount > 0){
				//$msg = "\nDo minimum sale of Rs $target_amount per month to avoid rental of Rs $rental_amount";
			}
			else if($rental_amount > 0 && $target_amount == -1){
				//$msg = "\nRs $rental_amount will be charged as monthly rental";
			}
		}
		else {
			//$msg = "\nKindly pay setup fee of Rs 500 to your distributor";
		}
		return $msg;
	}

	function createRetailerApp($params,$format){
		$to_save = true;

		$this->data['Retailer']['mobile'] = $params['mobile'];
		if(empty($_SESSION['Auth']))$this->redirect('/');
		//preg_match('/^[6-9][0-9]{9}$/',$this->data['Retailer']['mobile'],$matches,0);
		if($this->General->mobileValidate($this->data['Retailer']['mobile']) == '1'){
			$msg = "Invalid demo mobile number";
			return array('status' => 'failure','description' => $msg);
		}
		$exists = $this->General->checkIfUserExists($this->data['Retailer']['mobile']);
		if($exists){
		    $to_save = false;
		    $msg = "You cannot make this mobile as your retailer.";
			/*$user = $this->General->getUserDataFromMobile($this->data['Retailer']['mobile']);
			if($user['group_id'] != MEMBER){
				$to_save = false;
				$msg = "You cannot make this mobile as your retailer.";
			}
			else {
				$userData['User']['id'] = $user['id'];
				$userData['User']['group_id'] = RETAILER;
				$this->User->save($userData);//make already user to a retailer
			}*/
		}
		else{
			$user = $this->General->registerUser($this->data['Retailer']['mobile'],RETAILER_REG,RETAILER);
			$user = $user['User'];
		}

		if($to_save){

			$this->data['Retailer']['user_id'] = $user['id'];
			$this->data['Retailer']['parent_id'] = $_SESSION['Auth']['id'];
			$this->data['Retailer']['slab_id'] = $_SESSION['Auth']['slab_id'];
			$this->data['Retailer']['rental_flag'] = 0;
			if(isset($params['name'])){
				$this->data['Retailer']['name'] = $params['name'];
				$this->data['Retailer']['pin'] = $params['pincode'];
			}

			if(isset($params['shopname']))
			{
				$this->data['Retailer']['shopname'] = $params['shopname'];
			}

			if(isset($params['subArea']))
			{
				$this->data['Retailer']['subarea_id'] = $params['subArea'];
			}

			if(isset($params['type']))
			{
				$this->data['Retailer']['retailer_type'] = $params['type'];
			}


			if(isset($params['salesmanId'])){
				$this->data['Retailer']['salesman'] = $params['salesmanId'];
				$this->data['Retailer']['maint_salesman'] = $params['salesmanId'];
			}

			$this->Retailer->create();
			if ($this->Retailer->save($this->data)) {
			        $this->Shop->addUserGroup($user['id'],RETAILER);
				$this->create_unverified_retailer($this->Retailer->id, $this->data['Retailer']);

				$this->General->makeOptIn247SMS($this->data['Retailer']['mobile']);

//                                $MsgTemplate = $this->General->LoadApiBalance();
//		                $sms = $MsgTemplate['CreateRetailer_App_MSG'];

				//$sms .= $this->getRentalSMS($params['rental_flag'],$_SESSION['Auth']['rental_amount'],$_SESSION['Auth']['target_amount']);

//				$this->General->sendMessage($this->data['Retailer']['mobile'],$sms,'payone');

				$sName = '';
				$msg = 'Retailer created successfully.';

				if($params['salesmanId']){
					$sQ = $this->Slaves->query("SELECT name,mobile FROM salesmen where id=".$params['salesmanId']);
					$sName = $sQ['0']['salesmen']['name'];
				}
				if($params['rental_flag'] == 1){
					$mail_subject = "New Retailer Created On Rental";
				}
				else {
					$mail_subject = "New Retailer Created Via Kit";
				}
				$mail_body = "Salesman: ".$sName."<br/>";
				$mail_body .= "Distributor: " .$_SESSION['Auth']['company'] . "<br/>";
				if(isset($this->data['Retailer']['name'])){
					$mail_body .= "Retailer: " . $this->data['Retailer']['name'] . "<br/>";
				}
				$mail_body .= "Retailer Mobile: " . $this->data['Retailer']['mobile'];
				//$this->General->sendMails($mail_subject, $mail_body);

				$this->Shop->updateSlab($this->data['Retailer']['slab_id'],$this->Retailer->id,RETAILER);

				if($params['rental_flag'] == 0){
					if($_SESSION['Auth']['commission_kits_flag'] == 1){
						if($_SESSION['Auth']['kits'] == -1){
							$this->User->query("UPDATE distributors SET discounted_money=discounted_money+".$_SESSION['Auth']['discount_kit']." WHERE id = ".$_SESSION['Auth']['id']);
						}
						else {
							$this->User->query("UPDATE distributors SET kits=kits-1,discounted_money=discounted_money+".$_SESSION['Auth']['discount_kit']." WHERE id = ".$_SESSION['Auth']['id']);
						}
					}
					else if($_SESSION['Auth']['kits'] > 0){
						$this->Retailer->query("UPDATE distributors SET kits=kits-1 WHERE id = ".$_SESSION['Auth']['id']);
						if($params['salesmanId'] && $sQ['0']['salesmen']['mobile'] == $_SESSION['Auth']['User']['mobile']){
							$msg .= "\nKits left: " . $_SESSION['Auth']['kits'] - 1;
						}
					}
				}

				/*if(isset($params['salesmanId'])){
					$date=Date("Y-m-d");
					$date_created=Date("Y-m-d H:i:s");
					$st_id = $this->Shop->shopTransactionUpdate(SETUP_FEE,SETUP_FEE_AMT,$_SESSION['Auth']['id'],$this->Retailer->id);
					//for trial put insert amount=0 in sst but in st put 1500
					$this->User->query("insert into salesman_transactions(shop_tran_id,salesman,payment_mode,payment_type,collection_amount,collection_date,created) values($st_id,".$params['salesmanId'].",1,1,0,'$date','$date_created')");
					}*/
			}
			return array('status' => 'success','description' => $msg);
		}
		else {
			return array('status' => 'failure','description' => $msg);
		}
	}

	function changePassword(){
		if($this->Session->read('Auth.User.group_id') ==''){
			$this->redirect('/');
	       }

		$this->render('setting');
	}

	/*function commissions(){
		$data = $this->Shop->getAllCommissions($this->info['id'],$this->Session->read('Auth.User.group_id'),$this->info['slab_id']);
		//$this->printArray($data);
		$this->set('data',$data);
		$this->render('discount_table');
	}*/

	/*function setSignature(){
		if(!$this->Session->check('Auth.User.group_id'))$this->logout();
		if($this->info['signature_flag'] == 1){
			$data['signature'] = 'on';
		}
		$data['text'] = $this->info['signature'];
		$this->set('data',$data);
		$this->render('signature');
	}


	function saveSignature(){
		if(!$this->Session->check('Auth.User.group_id'))$this->logout();
		$signature = 0;
		if(isset($this->data['signature']) && $this->data['signature'] == 'on'){
			$signature = 1;
		}
		$this->set('data',$this->data);
		if($signature == 1 && empty($this->data['text'])){
			$err_msg = '<div class="error_class">Please enter your 60 character signature.</div>';
			$this->Session->setFlash(__($err_msg, true));
			$this->render('/elements/shop_signature','ajax');
		}
		else {
			$this->Retailer->updateAll(array('Retailer.signature_flag' => $signature, 'Retailer.signature' => '"'.$this->data['text'].'"'),array('Retailer.id' => $this->info['id']));
			if($signature == 0) $msg = 'Signature Removed Successfully';
			else $msg = 'Signature Saved Successfully';
			$err_msg = '<div class="success">'.$msg.'</div>';
			$this->Session->setFlash(__($err_msg, true));
			$this->render('/elements/shop_signature','ajax');
		}
	}*/

	function accountHistory($date=null,$page=null,$report=0){

		if($this->Session->read('Auth.User.group_id') != ADMIN && $this->Session->read('Auth.User.group_id') != MASTER_DISTRIBUTOR && $this->Session->read('Auth.User.group_id') != SUPER_DISTRIBUTOR && $this->Session->read('Auth.User.group_id') != DISTRIBUTOR && $this->Session->read('Auth.User.group_id') != RETAILER){
			$this->redirect('/');
		}
                if($this->Session->read('Auth.system_used') == 0) {
                        $report=0;
                }
		$api = false;
		$page_limit = 30;
		$pageWise = 1;
		if(is_array($date)){
			$api = true;
			$params = $date;
			$date = $params['date'];
			$page = $params['page'];
			$page_limit = $params['limit'];
                        $pageWise = isset($params['is_page_wise'])? $params['is_page_wise'] : 1;
		}
		$grp_id = $_SESSION['Auth']['User']['group_id'];
		if($page == null)$page = 1;

		if($date == 0 || $date == null){
			$date = date('dmY')."-".date('dmY');
		}
		if($date != null){
			//$limit = " limit " . ($page-1)*$page_limit.",".$page_limit;

			$dates = explode("-",$date);
			$date_from = $dates[0];
			$date_to = $dates[1];



			if(checkdate(substr($date_from,2,2), substr($date_from,0,2), substr($date_from,4)) && checkdate(substr($date_to,2,2), substr($date_to,0,2), substr($date_to,4))){
				$date_from =  substr($date_from,4) . "-" . substr($date_from,2,2) . "-" . substr($date_from,0,2);
				$date_to =  substr($date_to,4) . "-" . substr($date_to,2,2) . "-" . substr($date_to,0,2);

				$nodays=(strtotime($date_to) - strtotime($date_from))/ (60 * 60 * 24);
				$nodays += 1;

				$query2 = "
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Topup Transferred' as name,trim(master_distributors.company) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions INNER JOIN master_distributors ON (master_distributors.id = shop_transactions.target_id) WHERE type = " . ADMIN_TRANSFER . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Commission Transferred' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE type = " . COMMISSION_MASTERDISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Pullback' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE user_id = ".$_SESSION['Auth']['User']['id'] . " AND type = " . PULLBACK_MASTERDISTRIBUTOR . ")
					";

				$query3 = "
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Topup Received' as name,trim(master_distributors.company) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions INNER JOIN master_distributors ON (master_distributors.id = shop_transactions.source_id) WHERE target_id = ".$this->info['id']." AND type = " . MDIST_SDIST_BALANCE_TRANSFER . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Commision Received' as name,trim(shop_transactions.target_id) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']." AND type = " . COMMISSION_SUPERDISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Topup Received' as name,trim(distributors.company) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions INNER JOIN distributors ON (distributors.id = shop_transactions.target_id) WHERE source_id = ".$this->info['id']. " AND type = " . SDIST_DIST_BALANCE_TRANSFER . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Discount Received' as name,if(shop_transactions.target_id != 0,trim(shop_transactions.target_id),trim(shop_transactions.note)) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']." AND type = " . COMMISSION_DISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, trim(shop_transactions.note) as name,'' as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']." AND type = " . COMMISSION_DISTRIBUTOR_REVERSE. ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,shop_transactions.target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0  as debit, 'Pullback by me' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions LEFT JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE st.source_id = ".$this->info['id']. " AND shop_transactions.type = " . PULLBACK_DISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,shop_transactions.source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Pullback by Company' as name,trim(shop_transactions.target_id) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE shop_transactions.source_id = ".$this->info['id']. " AND shop_transactions.type = " . PULLBACK_SUPERDISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Incentive/Refund' as name,trim(shop_transactions.note) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$_SESSION['Auth']['User']['id']. " AND type = " . REFUND . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Service Charge' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']. " AND user_id = ".DISTRIBUTOR." AND type = " . SERVICE_CHARGE . ")
				";

				$query4 = "
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Topup Received' as name,trim(master_distributors.company) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions INNER JOIN master_distributors ON (master_distributors.id = shop_transactions.target_id) WHERE target_id = ".$this->info['id']." AND type = " . ADMIN_TRANSFER . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Commision Received' as name,trim(shop_transactions.target_id) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']." AND type = " . COMMISSION_MASTERDISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Topup Transferred' as name,trim(distributors.company) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions INNER JOIN distributors ON (distributors.id = shop_transactions.target_id) WHERE source_id = ".$this->info['id']." AND type = " . MDIST_DIST_BALANCE_TRANSFER . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Topup Transferred' as name,'Super Distributor' as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions INNER JOIN super_distributors ON (super_distributors.id = shop_transactions.target_id) WHERE source_id = ".$this->info['id']." AND type = " . MDIST_SDIST_BALANCE_TRANSFER . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Commission Transferred' as name,trim(shop_transactions.target_id) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions INNER JOIN distributors ON (distributors.id = shop_transactions.source_id) WHERE distributors.parent_id = ".$this->info['id']." AND target_id !=0 AND type = " . COMMISSION_DISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Commission Transferred' as name,trim(shop_transactions.target_id) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions INNER JOIN distributors ON (distributors.id = shop_transactions.source_id) WHERE distributors.parent_id = ".$this->info['id']." AND target_id !=0 AND type = " . COMMISSION_SUPERDISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Pullback by Company' as name,trim(shop_transactions.target_id) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']. " AND type = " . PULLBACK_MASTERDISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Pullback by me' as name,trim(shop_transactions.target_id) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE user_id = ".$_SESSION['Auth']['User']['id']. " AND type = " . PULLBACK_DISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Pullback by me' as name,trim(shop_transactions.target_id) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE user_id = ".$_SESSION['Auth']['User']['id']. " AND type = " . PULLBACK_SUPERDISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Incentive/Refund' as name,'' as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$_SESSION['Auth']['User']['id']. " AND type = " . REFUND . ")
				";

                                    if($report == 0) {
                                            $old_to_new = "(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Topup Transferred' as name,shop_transactions.target_id as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']." AND type = " . DIST_RETL_BALANCE_TRANSFER . " AND note is not null AND (confirm_flag != 1 OR type_flag != 5)) "
                                                    . "UNION "
                                                    . "(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,shop_transactions.target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Pullback by Me' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.user_id = ".$_SESSION['Auth']['User']['id']. " AND shop_transactions.type = " . PULLBACK_RETAILER . " AND st.type = " . DIST_RETL_BALANCE_TRANSFER . ")";
                                    } else if($report == 1) {
                                            $old_to_new = " (SELECT st.target_id,st.date,st.source_opening as opening,source_closing as closing,st.id,st.confirm_flag,0 as credit,st.amount as debit,'Topup Transferred' name,concat(salesmen.name,' (salesman)') refid,st.type,st.timestamp FROM shop_transactions st JOIN salesmen ON (st.target_id = salesmen.id AND salesmen.mobile != ".$_SESSION['Auth']['User']['mobile'].") WHERE st.source_id = ".$_SESSION['Auth']['id']." AND st.type = ".DIST_SLMN_BALANCE_TRANSFER." AND st.type_flag != 5) "
                                                    . "UNION "
                                                    . "(SELECT st.target_id,st.date,st.source_opening as opening,source_closing as closing,st.user_id id,st.confirm_flag,0 as credit,st.amount as debit,'Topup Transferred' name,st.target_id refid,st.type,st.timestamp FROM shop_transactions st JOIN salesmen ON (salesmen.id = st.source_id AND salesmen.dist_id = ".$this->info['id'].") WHERE st.type = ".SLMN_RETL_BALANCE_TRANSFER." AND st.user_id != 0 AND st.type_flag != 5) "
                                                    . "UNION "
                                                    . "(SELECT st.target_id,st.date,st.target_opening as opening,st.target_closing as closing,st.id,st.confirm_flag,st.amount as credit,0 as debit,'Pullback by Me' name,concat(st.target_id, if(salesmen.mobile = ".$_SESSION['Auth']['User']['mobile'].",' (retailer)',' (salesman)')) refid,st.type,st.timestamp FROM shop_transactions st JOIN shop_transactions tst ON (st.target_id = tst.id) JOIN salesmen ON (st.source_id = salesmen.id) WHERE salesmen.dist_id = ".$_SESSION['Auth']['id']." AND st.type = ".PULLBACK_SALESMAN." AND tst.user_id IS NOT NULL) "
                                                    . "UNION "
                                                    . "(SELECT st.target_id,st.date,0 as opening,0 as closing,st.id,st.confirm_flag,st.amount as credit,st.amount as debit,'Pullback by Me' name,'salesman-retailer' refid,st.type,st.timestamp FROM shop_transactions st JOIN shop_transactions tst ON (st.target_id = tst.id) JOIN retailers ON (st.source_id = retailers.id) WHERE retailers.parent_id = ".$_SESSION['Auth']['id']." AND st.type = ".PULLBACK_RETAILER." AND (tst.user_id IS NULL OR tst.user_id = 0))";
                                    }
				$query5 = "
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Topup Received' as name,trim(distributors.company) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions INNER JOIN distributors ON (distributors.id = shop_transactions.target_id) WHERE target_id = ".$this->info['id']. " AND type = " . MDIST_DIST_BALANCE_TRANSFER . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Topup Received' as name,trim(distributors.company) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions INNER JOIN distributors ON (distributors.id = shop_transactions.target_id) WHERE target_id = ".$this->info['id']. " AND type = " . SDIST_DIST_BALANCE_TRANSFER . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Discount Received' as name,if(shop_transactions.target_id != 0,trim(shop_transactions.target_id),trim(shop_transactions.note)) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']." AND type = " . COMMISSION_DISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, trim(shop_transactions.note) as name,'' as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']." AND type = " . COMMISSION_DISTRIBUTOR_REVERSE. ")
					UNION
					$old_to_new
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Pullback by SD' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']. " AND type = " . PULLBACK_DISTRIBUTOR . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Incentive/Refund' as name,trim(shop_transactions.note) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$_SESSION['Auth']['User']['id']. " AND type = " . REFUND . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Service Charge' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$this->info['id']. " AND user_id = ".DISTRIBUTOR." AND type = " . SERVICE_CHARGE . ")
				";

				if($grp_id == DISTRIBUTOR){
				    $retailer_id = $this->Slaves->query("SELECT id FROM retailers WHERE user_id = ". $_SESSION['Auth']['User']['id']);
				    $retailer_id = $retailer_id[0]['retailers']['id'];
				}

				else {
				    $retailer_id = $_SESSION['Auth']['id'];
				}
				$query6 = "
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.target_opening as opening,target_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Topup Received' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE target_id = ".$retailer_id." AND type = " . DIST_RETL_BALANCE_TRANSFER . " AND note is not null AND (confirm_flag != 1 OR type_flag != 5))
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,(shop_transactions.source_opening - shop_transactions.amount) as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Recharge' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$retailer_id." AND type = " . RETAILER_ACTIVATION . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,(shop_transactions.source_closing - shop_transactions.amount) as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Recharge Discount' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$retailer_id." AND type = " . COMMISSION_RETAILER . ")
                                            UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Pullback by Distributor' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$retailer_id." AND type = " . PULLBACK_RETAILER . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, 'Incentive/Refund' as name,'' as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$_SESSION['Auth']['User']['id']. " AND type = " . REFUND . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,(shop_transactions.source_closing + shop_transactions.amount) as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Service Charge' as name,trim(shop_transactions.target_id) as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$retailer_id. " AND type = " . SERVICE_CHARGE . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, 'Monthly Rental' as name,'' as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$_SESSION['Auth']['User']['id']. " AND type = " . RENTAL . ")
					UNION
					(SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,shop_transactions.source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, st.amount as credit, 0 as debit, 'Reversal' as name,trim(shop_transactions.target_id) as refid,shop_transactions.type, shop_transactions.timestamp FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE shop_transactions.source_id = ".$retailer_id. " AND shop_transactions.type = ".REVERSAL_RETAILER.")
					";

				$grp_ids = explode(",",$_SESSION['Auth']['User']['group_ids']);
				$query = "";
				foreach ($grp_ids as $gpid){
				    $q = isset(${"query$gpid"}) ? ${"query$gpid"} : "";
				    if(!empty($q)){
				        if(empty($query)) $query = $q;
				        else $query = $query . " UNION " . $q;
				    }
				}

                    //Common queries for all
				    $query = $query . " UNION
				        (SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, trim(shop_transactions.note) as name,'' as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE type_flag in (0,2) AND source_id = ".$_SESSION['Auth']['User']['id']." AND type in (" . DEBIT_NOTE . ",".CREDIT_NOTE.",".COMMISSION.",".SERVICECHARGES.",".SERVICE_TAX.",".TDS.",".VOID_TXN."))
				        UNION
				        (SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, (if(shop_transactions.source_id = ".$_SESSION['Auth']['User']['id'].",0,shop_transactions.amount)) as credit,(if(shop_transactions.source_id = ".$_SESSION['Auth']['User']['id'].",shop_transactions.amount,0)) as debit, trim(shop_transactions.note) as name,'' as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE type_flag = 0 AND (source_id = ".$_SESSION['Auth']['User']['id']." OR target_id = ".$_SESSION['Auth']['User']['id'].") AND type in (" . WALLET_TRANSFER . "))
				        UNION
				        (SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, (if(shop_transactions.source_id = ".$_SESSION['Auth']['User']['id'].",shop_transactions.amount,0)) as credit,(if(shop_transactions.source_id = ".$_SESSION['Auth']['User']['id'].",0,shop_transactions.amount)) as debit, trim(shop_transactions.note) as name,'' as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE type_flag = 0 AND (source_id = ".$_SESSION['Auth']['User']['id']." OR user_id = ".$_SESSION['Auth']['User']['id'].") AND type in (" . WALLET_TRANSFER_REVERSED . "))
				        UNION
				        (SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, trim(shop_transactions.note) as name,'' as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE type_flag = 0 AND source_id = ".$_SESSION['Auth']['User']['id']." AND type = " . KITCHARGE . ")
				        UNION
				        (SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, shop_transactions.amount as credit, 0 as debit, trim(shop_transactions.note) as name,'' as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE source_id = ".$_SESSION['Auth']['User']['id']." AND type in (" . TXN_REVERSE. ",".TXN_CANCEL_REFUND."))
				        UNION
				        (SELECT shop_transactions.target_id,shop_transactions.date,shop_transactions.source_opening as opening,source_closing as closing, shop_transactions.id, shop_transactions.confirm_flag, 0 as credit, shop_transactions.amount as debit, trim(shop_transactions.note) as name,'' as refid, shop_transactions.type, shop_transactions.timestamp FROM shop_transactions WHERE type_flag = 0 AND source_id = ".$_SESSION['Auth']['User']['id']." AND type in ( " . SECURITY_DEPOSIT . ",".ONE_TIME_CHARGE."))
                                ";

				if($nodays <= 7){
					$transactions = $this->Shop->getMemcache("txns_".$date."_".$_SESSION['Auth']['id']."_".$grp_id.$report);
					if(empty($transactions)){
						$transactions = $this->Slaves->query("SELECT transactions.* FROM ($query) as transactions  where transactions.date >= '$date_from' AND  transactions.date <= '$date_to'  AND  (transactions.credit > 0 OR transactions.debit > 0)  order by transactions.id desc,transactions.timestamp desc");
                                                $rets = array();
                                                $counter = 0;

                                                /** IMP DATA ADDED : START**/
                                                if($grp_id == MASTER_DISTRIBUTOR || $grp_id == SUPER_DISTRIBUTOR){
                                                    $dist_ids = array_map(function($element){
                                                        return $element['transactions']['target_id'];
                                                    },$transactions);
                                                    $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);

                                                   
                                                }
                                                /** IMP DATA ADDED : END**/


                                                foreach($transactions as $txn) {

                                                    if(in_array($txn['transactions']['type'],array(MDIST_DIST_BALANCE_TRANSFER,SDIST_DIST_BALANCE_TRANSFER))) {
                                                        $txn['transactions']['refid'] = $transactions[$counter]['transactions']['refid'] = $imp_data[$txn['transactions']['target_id']]['imp']['shop_est_name'];
                                                    }

                                                    if(in_array($txn['transactions']['type'],array(MDIST_SDIST_BALANCE_TRANSFER))) {

                                                    	
														$get_sd_user_id = $this->Slaves->query("SELECT user_id FROM super_distributors WHERE id = ".$txn['transactions']['target_id']);
                                                    	if(!empty($get_sd_user_id)){

                                                    		$sd_user_id = $get_sd_user_id[0]['super_distributors']['user_id'];

                                                    		 $sd_imp_data = $this->Shop->getUserLabelData($sd_user_id);
                                                    		 $company = $sd_imp_data[$sd_user_id]['imp']['shop_est_name'];
                                                    	}
														$txn['transactions']['refid'] = $transactions[$counter]['transactions']['refid'] = $company;
                                                    }

                                                    if(in_array($txn['transactions']['type'],array(DIST_RETL_BALANCE_TRANSFER,SLMN_RETL_BALANCE_TRANSFER))) {
                                                        if(is_numeric($txn['transactions']['refid'])) {
                                                            $rets[] = $txn['transactions']['refid'];
                                                        }
                                                    }

                                                    if(in_array($txn['transactions']['type'],array(DEBIT_NOTE,SERVICECHARGES,TDS))) {
                                                        $transactions[$counter]['transactions']['debit'] = $transactions[$counter]['transactions']['credit'];
                                                        $transactions[$counter]['transactions']['credit'] = 0;
                                                    }

                                                    if($txn['transactions']['type'] == VOID_TXN && $txn['opening_closing']['opening'] > $txn['opening_closing']['closing']){
                                                        $transactions[$counter]['transactions']['debit'] = $transactions[$counter]['transactions']['credit'];
                                                        $transactions[$counter]['transactions']['credit'] = 0;
                                                    }

                                                    $counter++;

                                                }
                                                $rets = array_unique($rets);
                                                $all_rets = $this->Slaves->query("SELECT r.id,if(ur.shopname = '' OR ur.shopname IS NULL,if(r.shopname = '' OR r.shopname IS NULL,r.mobile,r.shopname),ur.shopname) retailer FROM unverified_retailers ur "
                                                        . "JOIN retailers r ON (ur.retailer_id = r.id) "
                                                        . "WHERE ur.retailer_id IN (".implode(',',$rets).")");

                                                /** IMP DATA ADDED : START**/
                                                $imp_data = $this->Shop->getUserLabelData(implode(',',$rets),2,2);
                                                /** IMP DATA ADDED : END**/

                                                $final_rets = array();
                                                foreach($all_rets as $rets) {
                                                    if( isset($imp_data[$rets['r']['id']]['imp']['shop_est_name']) && !empty($imp_data[$rets['r']['id']]['imp']['shop_est_name']) ){
                                                        $rets[0]['retailer'] =  $imp_data[$rets['r']['id']]['imp']['shop_est_name'];
                                                    }
                                                    $final_rets[$rets['r']['id']] = $rets[0]['retailer'];
                                                }

                                                $temp_transactions = array();
                                                foreach($transactions as $tran) {
                                                    if(in_array($tran['transactions']['type'],array(DIST_RETL_BALANCE_TRANSFER,SLMN_RETL_BALANCE_TRANSFER))) {
                                                        $ret = $final_rets[$tran['transactions']['refid']];
                                                        if($tran['transactions']['debit'] > 0){
                                                            $ret .= " (Retailer)";
                                                        }
                                                        $tran['transactions']['refid'] = $ret;
                                                    }

                                                    $temp_transactions[] = $tran;
                                                }
                                                $transactions = $temp_transactions;

						if($date_to == date('Y-m-d')) $time = 2*60;
						else $time = 7*24*60*60;
						$this->Shop->setMemcache("txns_".$date."_".$_SESSION['Auth']['id']."_".$grp_id.$report,$transactions,$time);
					}

					$trans_count = count($transactions);
                                        if($pageWise == 1){
                                                $transactions = array_slice($transactions,($page-1)*$page_limit,$page_limit);
                                        }
				} else {
					$transactions = array();
					$trans_count = 0;
					$this->set('date_limit',0);
				}
				$this->set(compact('trans_count','date_from','date_to'));
				if($api){
					$result['trans_count'] = $trans_count;
					$result['date_from'] = $date_from;
					$result['date_to'] = $date_to;
				}
			}
			else {
				$transactions = array();
			}

		}
		else {
			$transactions = array();
		}

		$this->set('page',$page);
		$this->set('transactions',$transactions);
		$this->set('report',$report);
		if($api){
			if($date == null) $result['empty'] = 0;
			$result['page'] = $page+1;
			$result['transactions'] = $transactions;
			return $result;
		}
		else {
			if($date == null) $this->set('empty',0);
			$this->render('account_history');
		}
	}

	//RohitP(rohit3nov@gmail.com)
	function distEarningReport(){
		if($this->Session->read('Auth.User.group_id') != DISTRIBUTOR ){
			$this->redirect('/');
		}
		ini_set("memory_limit", "1024M");
		$dist_id = $this->Session->read('Auth.User.id');
		$product_types  = Configure::read('product_types');
		$product_types = $this->Shop->getServices();
		unset($product_types[11]); // unsetting microfinance

		$dist_salesmen  = $this->Shop->getDistSalesmen($this->Session->read('Auth.id'));
		$dist_retailers = $this->Shop->getDistRetailers($this->Session->read('Auth.id'));

		if( array_key_exists('service',$this->params['url']) ){
			$selected_product_type = null;
			$selected_salesman = null;
			$selected_retailer = null;
			$date_from = null;
			$date_to = null;

			$page_limit = 10;
			$page_wise = 1;
			$page = 1;
			if( array_key_exists('service',$this->params['url']) && trim(strtolower($this->params['url']['service'])) == 'additional-incentives'){
				$selected_product_type = 'additional-incentives';
			} else if( array_key_exists('service',$this->params['url']) &&  in_array(trim(strtolower($this->params['url']['service'])),array_map('strtolower',$product_types)) ){
				$selected_product_type = array_search(trim(strtolower($this->params['url']['service'])),array_map('strtolower',$product_types));
            }

			if( array_key_exists('salesman',$this->params['url']) &&  in_array(trim(strtolower($this->params['url']['salesman'])),array_map('strtolower',$dist_salesmen)) ){
                $selected_salesman = array_search(trim(strtolower($this->params['url']['salesman'])),array_map('strtolower',$dist_salesmen));
                if($selected_salesman){
                    $dist_retailers = $this->Shop->getDistRetailers($this->Session->read('Auth.id'),$selected_salesman);
                }
            }

			if( array_key_exists('retailer',$this->params['url']) &&  in_array(trim(strtolower($this->params['url']['retailer'])),array_map('strtolower',$dist_retailers)) ){
				$selected_retailer = array_search(trim(strtolower($this->params['url']['retailer'])),array_map('strtolower',$dist_retailers));
			}
			if( array_key_exists('from',$this->params['url']) && !empty($this->params['url']['from']) ){
				$date_from = trim($this->params['url']['from']);
			}
			if( array_key_exists('to',$this->params['url']) && !empty($this->params['url']['to']) ){
				$date_to = trim($this->params['url']['to']);
			}
			if( array_key_exists('page_limit',$this->params['url']) && !empty($this->params['url']['page_limit']) && is_numeric($this->params['url']['page_limit']) ){
				$page_limit = trim($this->params['url']['page_limit']);
			}
			// if( array_key_exists('page_wise',$this->params['url']) && !empty($this->params['url']['page_wise']) && is_numeric($this->params['url']['page_wise']) ){
			// 	$page_wise = trim($this->params['url']['page_wise']);
			// }
			if( array_key_exists('page',$this->params['url']) && !empty($this->params['url']['page']) && is_numeric($this->params['url']['page']) ){
				$page = trim($this->params['url']['page']);
			}

			Configure::load('product_config');
        	$earning_config = Configure::read('services');
			if( $selected_product_type ){
				if( $date_from && $date_to ){

					if( ((strtotime($date_to) - strtotime($date_from))/(60 * 60 * 24))  < 31 ){
						$transactions = $this->Shop->getDistTransactionsByProductType($this->Session->read('Auth.id'),$dist_id,$selected_product_type,$date_from,$date_to,$selected_retailer,$selected_salesman);

						if($selected_product_type == 'additional-incentives'){
							$total_incentive = 0;
							foreach($transactions as $index => $transaction){
								$total_incentive += $transaction['st']['amount'];
							}
							$trans_count = count($transactions);
							if($page_wise == 1){
								$transactions = array_slice($transactions,($page-1)*$page_limit,$page_limit,true);
							}

							$this->set('transactions',$transactions);
							$this->set('page',$page);
							$this->set('trans_count',$trans_count);
							$this->set('total_incentive',$total_incentive);

						} else {
							$total_sale = 0;
							$total_earning = 0;
							$total_refund = $transactions['refund'];
							if( count($transactions['sale']) > 0 ){
								foreach( $transactions['sale'] as $index => $transaction ){
									$transactions['sale'][$index]['st']['earning'] = 0;
									if( !array_key_exists('type',$transaction['st']) ){
										$transaction['st']['type'] = RETAILER_ACTIVATION;
										$transaction['st']['id'] = $transaction['st']['shop_transaction_id'];
									}
									
									if($selected_product_type >= 8 && in_array($transaction['st']['type'],array(RETAILER_ACTIVATION,CREDIT_NOTE,DEBIT_NOTE))){
									    $dist_margin =  json_decode($transaction[0]['dist_margins'],true);
									    $comm = $this->Shop->calculateCommission($transaction['st']['amount'],$dist_margin);
									    $transactions['sale'][$index]['st']['earning'] = $comm['comm'];
									}
									else if( $selected_product_type < 8 && in_array($transaction['st']['type'],array(RETAILER_ACTIVATION,CREDIT_NOTE,DEBIT_NOTE)) ){
									    //$gst_flag = (strlen($this->Session->read('Auth.gst_no')) < 15) ? false : true;
									    $comm = $this->Shop->getServiceMargin($selected_product_type, 0);
									    $transactions['sale'][$index]['st']['earning'] = (($comm/100)*$transaction['st']['amount']);
									}
									$total_sale +=  $transaction['st']['amount'];
									$total_earning +=  $transactions['sale'][$index]['st']['earning'];
								}
							}

							$trans_count = count($transactions['sale']);
							if($page_wise == 1){
								$transactions['sale'] = array_slice($transactions['sale'],($page-1)*$page_limit,$page_limit,true);
							}

							$this->set('transactions',$transactions['sale']);
							$this->set('page',$page);
							$this->set('trans_count',$trans_count);
							$this->set('total_sale',$total_sale);
							$this->set('total_earning',$total_earning);
							$this->set('total_refund',$total_refund);
						}
					} else {
						$this->set('validation_error','Date difference cannot be greater than 31 days !!');
					}
				} else {
					$this->set('validation_error','Date range is mandatory !!');
				}
			} else {
				$this->set('validation_error','Invalid Product type selected !!');
			}
		}
		$this->set('selected_product_type',$selected_product_type);
		$this->set('selected_salesman',$selected_salesman);
		$this->set('selected_retailer',$selected_retailer);
		$this->set('date_from',$date_from);
		$this->set('date_to',$date_to);
		$this->set('product_types',$product_types);
		$this->set('dist_salesmen',$dist_salesmen);
		$this->set('dist_retailers',$dist_retailers);

		$this->render('dist_earning_report');
	}


	function topup($date = null,$page=null){
                                                     $last90days=date("Y-m-d", strtotime('-90 days'));
		if(is_array($date)){
			$api = true;
			$params = $date;
			$date = $params['date'];
			$page = $params['page'];
		}
		$grp_id = $_SESSION['Auth']['User']['group_id'];
		if($page == null)$page = 1;
		if(empty($date)){
			$date = date('dmY')."-".date('dmY');
		}
		$limit = " limit " . ($page-1)*PAGE_LIMIT.",".PAGE_LIMIT;

		$dates = explode("-",$date);
		$date_from = $dates[0];
		$date_to = $dates[1];

		if(checkdate(substr($date_from,2,2), substr($date_from,0,2), substr($date_from,4)) && checkdate(substr($date_to,2,2), substr($date_to,0,2), substr($date_to,4))){
			$date_from =  substr($date_from,4) . "-" . substr($date_from,2,2) . "-" . substr($date_from,0,2);
			$date_to =  substr($date_to,4) . "-" . substr($date_to,2,2) . "-" . substr($date_to,0,2);
                                                                                $datediff= date_diff(date_create($date_from), date_create($date_to));

                                                                                if(intval($datediff->format('%a')) > 30):
                                                                                    exit("Date range can not exceed 30 days.");
                                                                                endif;

                                                                                $date_arr=$this->Shop->getDays($date_from,$date_to);

                                                                                $shop_date=array();
                                                                                $shop_log_date=array();
                                                                                foreach($date_arr as $date):
                                                                                    if($date >= $last90days):
                                                                                        $shop_date[]=$date;
                                                                                    else:
                                                                                        $shop_log_date[]=$date;
                                                                                    endif;
                                                                                endforeach;

                                                                                if(!empty($shop_date)){  $fromdate1=reset($shop_date); $todate1=end($shop_date); }
                                                                                if(!empty($shop_log_date)){ $fromdate2=reset($shop_log_date); $todate2=end($shop_log_date); }

			if($grp_id == DISTRIBUTOR){
                                                                                                                if(!empty($shop_date) && !empty($shop_log_date))
                                                                                                                {
                                                                                                                    $query = "SELECT * FROM ((SELECT shop_transactions.id,shop_transactions.source_id,shop_transactions.target_id,shop_transactions.amount,shop_transactions.type, shop_transactions.timestamp,shop_transactions.date , if(type IN ( " . MDIST_DIST_BALANCE_TRANSFER . ",".SDIST_DIST_BALANCE_TRANSFER."),shop_transactions.target_opening,shop_transactions.source_opening) AS opening, if(type IN ( " . MDIST_DIST_BALANCE_TRANSFER . ",".SDIST_DIST_BALANCE_TRANSFER."),shop_transactions.target_closing,shop_transactions.source_closing) AS closing FROM shop_transactions USE INDEX (type_date) WHERE shop_transactions.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type IN ( " . MDIST_DIST_BALANCE_TRANSFER . ",".SDIST_DIST_BALANCE_TRANSFER.")) OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_DISTRIBUTOR . ")) AND date >= '$fromdate1' AND date <= '$todate1') "
                                                                                                                            . "UNION "
                                                                                                                            . "(SELECT shop_transactions_logs.id,shop_transactions_logs.source_id,shop_transactions_logs.target_id,shop_transactions_logs.amount,shop_transactions_logs.type, shop_transactions_logs.timestamp,shop_transactions_logs.date ,  if(type = " . MDIST_DIST_BALANCE_TRANSFER . ",shop_transactions_logs.target_opening,shop_transactions_logs.source_opening) AS opening, if(type = " . MDIST_DIST_BALANCE_TRANSFER . ",shop_transactions_logs.target_closing,shop_transactions_logs.source_closing) AS closing FROM shop_transactions_logs USE INDEX (type_date) WHERE shop_transactions_logs.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type = " . MDIST_DIST_BALANCE_TRANSFER . ") OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_DISTRIBUTOR . ")) AND date >= '$fromdate2' AND date <= '$todate2' )) as shop_transactions order by id desc";
                                                                                                                }
                                                                                                                elseif(!empty($shop_date) && empty($shop_log_date))
                                                                                                                {
                                                                                                                    $query="SELECT shop_transactions.id,shop_transactions.source_id,shop_transactions.target_id,shop_transactions.amount,shop_transactions.type, shop_transactions.timestamp,shop_transactions.date ,  if(type IN ( " . MDIST_DIST_BALANCE_TRANSFER . ",".SDIST_DIST_BALANCE_TRANSFER."),shop_transactions.target_opening,shop_transactions.source_opening) AS opening, if(type IN ( " . MDIST_DIST_BALANCE_TRANSFER . ",".SDIST_DIST_BALANCE_TRANSFER."),shop_transactions.target_closing,shop_transactions.source_closing) AS closing FROM shop_transactions USE INDEX (type_date) WHERE shop_transactions.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type IN ( " . MDIST_DIST_BALANCE_TRANSFER . ",".SDIST_DIST_BALANCE_TRANSFER.")) OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_DISTRIBUTOR . " AND target_id != 0)) AND date >= '$fromdate1' AND date <= '$todate1' order by id desc ";
                                                                                                                }
                                                                                                                elseif(!empty($shop_log_date) && empty($shop_date))
                                                                                                                {
                                                                                                                    $query="SELECT shop_transactions.id,shop_transactions.source_id,shop_transactions.target_id,shop_transactions.amount,shop_transactions.type, shop_transactions.timestamp,shop_transactions.date ,  if(type IN ( " . MDIST_DIST_BALANCE_TRANSFER . ",".SDIST_DIST_BALANCE_TRANSFER."),shop_transactions.target_opening,shop_transactions.source_opening) AS opening, if(type IN ( " . MDIST_DIST_BALANCE_TRANSFER . ",".SDIST_DIST_BALANCE_TRANSFER."),shop_transactions.target_closing,shop_transactions.source_closing) AS closing FROM shop_transactions_logs shop_transactions USE INDEX (type_date) WHERE shop_transactions.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type IN ( " . MDIST_DIST_BALANCE_TRANSFER . ",".SDIST_DIST_BALANCE_TRANSFER.")) OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_DISTRIBUTOR . ")) AND date >= '$fromdate2' AND date <= '$todate2' order by id desc ";
                                                                                                                }
                                                                                                        }
			else if($grp_id == MASTER_DISTRIBUTOR){
                                                                                                           if(!empty($shop_date) && !empty($shop_log_date)){
				$query = "SELECT * FROM ((SELECT shop_transactions.id,shop_transactions.source_id,shop_transactions.target_id,shop_transactions.amount,shop_transactions.type, shop_transactions.timestamp,shop_transactions.date ,  if(type = " . ADMIN_TRANSFER . ",shop_transactions.target_opening,shop_transactions.source_opening) AS opening, if(type = " . ADMIN_TRANSFER . ",shop_transactions.target_closing,shop_transactions.source_closing) AS closing FROM shop_transactions USE INDEX (type_date) WHERE shop_transactions.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type = " . ADMIN_TRANSFER . ") OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_MASTERDISTRIBUTOR . ")) AND date >= '$fromdate1' AND date <= '$todate1') "
                                                                                                                            . "UNION "
                                                                                                                            . "(SELECT shop_transactions_logs.id,shop_transactions_logs.source_id,shop_transactions_logs.target_id,shop_transactions_logs.amount,shop_transactions_logs.type, shop_transactions_logs.timestamp,shop_transactions_logs.date, if(type = " . ADMIN_TRANSFER . ",shop_transactions_logs.target_opening,shop_transactions_logs.source_opening) AS opening, if(type = " . ADMIN_TRANSFER . ",shop_transactions_logs.target_closing,shop_transactions_logs.source_closing) AS closing FROM shop_transactions_logs USE INDEX (type_date)  WHERE shop_transactions_logs.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type = " . ADMIN_TRANSFER . ") OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_MASTERDISTRIBUTOR . ")) AND date >= '$fromdate2' AND date <= '$todate2')) as shop_transactions order by id desc";
                                                                                                           }
                                                                                                           elseif(!empty($shop_date) && empty($shop_log_date))
                                                                                                            {
                                                                                                                $query="SELECT shop_transactions.id,shop_transactions.source_id,shop_transactions.target_id,shop_transactions.amount,shop_transactions.type, shop_transactions.timestamp,shop_transactions.date ,  if(type = " . ADMIN_TRANSFER . ",shop_transactions.target_opening,shop_transactions.source_opening) AS opening, if(type = " . ADMIN_TRANSFER . ",shop_transactions.target_closing,shop_transactions.source_closing) AS closing FROM shop_transactions USE INDEX (type_date) WHERE shop_transactions.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type = " . ADMIN_TRANSFER . ") OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_MASTERDISTRIBUTOR . ")) AND date >= '$fromdate1' AND date <= '$todate1' order by id desc ";
                                                                                                            }
                                                                                                            elseif(!empty($shop_log_date) && empty($shop_date))
                                                                                                            {
                                                                                                                $query="SELECT shop_transactions.id,shop_transactions.source_id,shop_transactions.target_id,shop_transactions.amount,shop_transactions.type, shop_transactions.timestamp,shop_transactions.date ,  if(type = " . ADMIN_TRANSFER . ",shop_transactions.target_opening,shop_transactions.source_opening) AS opening, if(type = " . ADMIN_TRANSFER . ",shop_transactions.target_closing,shop_transactions.source_closing) AS closing FROM shop_transactions_logs shop_transactions USE INDEX (type_date) WHERE shop_transactions.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type = " . ADMIN_TRANSFER . ") OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_MASTERDISTRIBUTOR . ")) AND date >= '$fromdate2' AND date <= '$todate2'  order by id desc";
                                                                                                            }
                                                                                                }

            else if($grp_id == SUPER_DISTRIBUTOR){
			       if(!empty($shop_date) && !empty($shop_log_date)){
			            $query = "SELECT * FROM ((SELECT shop_transactions.id,shop_transactions.source_id,shop_transactions.target_id,shop_transactions.amount,shop_transactions.type, shop_transactions.timestamp,shop_transactions.date ,  if(type = " . MDIST_SDIST_BALANCE_TRANSFER . ",shop_transactions.target_opening,shop_transactions.source_opening) AS opening, if(type = " . MDIST_SDIST_BALANCE_TRANSFER . ",shop_transactions.target_closing,shop_transactions.source_closing) AS closing FROM shop_transactions USE INDEX (type_date) WHERE shop_transactions.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type = " . MDIST_SDIST_BALANCE_TRANSFER . ") OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_SUPERDISTRIBUTOR . ")) AND date >= '$fromdate1' AND date <= '$todate1') ".
			             "UNION ". 
			             	"(SELECT shop_transactions_logs.id,shop_transactions_logs.source_id,shop_transactions_logs.target_id,shop_transactions_logs.amount,shop_transactions_logs.type, shop_transactions_logs.timestamp,shop_transactions_logs.date, if(type = " . MDIST_SDIST_BALANCE_TRANSFER . ",shop_transactions_logs.target_opening,shop_transactions_logs.source_opening) AS opening, if(type = " . MDIST_SDIST_BALANCE_TRANSFER . ",shop_transactions_logs.target_closing,shop_transactions_logs.source_closing) AS closing FROM shop_transactions_logs USE INDEX (type_date)  WHERE shop_transactions_logs.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type = " . MDIST_SDIST_BALANCE_TRANSFER . ") OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_SUPERDISTRIBUTOR . ")) AND date >= '$fromdate2' AND date <= '$todate2')) as shop_transactions order by id desc";
			        }
			        elseif(!empty($shop_date) && empty($shop_log_date))
			         {
			             $query="SELECT shop_transactions.id,shop_transactions.source_id,shop_transactions.target_id,shop_transactions.amount,shop_transactions.type, shop_transactions.timestamp,shop_transactions.date ,  if(type = " . MDIST_SDIST_BALANCE_TRANSFER . ",shop_transactions.target_opening,shop_transactions.source_opening) AS opening, if(type = " . MDIST_SDIST_BALANCE_TRANSFER . ",shop_transactions.target_closing,shop_transactions.source_closing) AS closing FROM shop_transactions USE INDEX (type_date) WHERE shop_transactions.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type = " . MDIST_SDIST_BALANCE_TRANSFER . ") OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_SUPERDISTRIBUTOR . ")) AND date >= '$fromdate1' AND date <= '$todate1' order by id desc ";
			          }
			          elseif(!empty($shop_log_date) && empty($shop_date))
			          {
			             $query="SELECT shop_transactions.id,shop_transactions.source_id,shop_transactions.target_id,shop_transactions.amount,shop_transactions.type, shop_transactions.timestamp,shop_transactions.date ,  if(type = " . MDIST_SDIST_BALANCE_TRANSFER . ",shop_transactions.target_opening,shop_transactions.source_opening) AS opening, if(type = " . MDIST_SDIST_BALANCE_TRANSFER . ",shop_transactions.target_closing,shop_transactions.source_closing) AS closing FROM shop_transactions_logs shop_transactions USE INDEX (type_date) WHERE shop_transactions.confirm_flag != 1 AND ((target_id = ".$_SESSION['Auth']['id'] . " AND type = " . MDIST_SDIST_BALANCE_TRANSFER . ") OR (source_id = ".$_SESSION['Auth']['id'] . " AND type = " . COMMISSION_SUPERDISTRIBUTOR . ")) AND date >= '$fromdate2' AND date <= '$todate2'  order by id desc";
			          }
			 }


			$transactions = $this->Slaves->query($query . " $limit");
			$transArr = array();
			if(!empty($transactions))foreach ($transactions as $transaction){
				if($transaction['shop_transactions']['type'] == COMMISSION_DISTRIBUTOR || $transaction['shop_transactions']['type'] == COMMISSION_MASTERDISTRIBUTOR){
					$transArr[$transaction['shop_transactions']['target_id']]['id'] =  $transaction['shop_transactions']['id'];
					$transArr[$transaction['shop_transactions']['target_id']]['amount'] =  ( isset($transArr[$transaction['shop_transactions']['target_id']]['amount']) ? $transArr[$transaction['shop_transactions']['target_id']]['amount'] : 0 ) + $transaction['shop_transactions']['amount'];
					$transArr[$transaction['shop_transactions']['target_id']]['opening'] =  isset($transArr[$transaction['shop_transactions']['target_id']]['opening']) ? $transArr[$transaction['shop_transactions']['target_id']]['opening'] : 0  ;
					$transArr[$transaction['shop_transactions']['target_id']]['closing'] = isset($transArr[$transaction['shop_transactions']['target_id']]['closing']) ? $transArr[$transaction['shop_transactions']['target_id']]['closing'] : 0  ;
					$transArr[$transaction['shop_transactions']['target_id']]['timestamp'] = isset($transArr[$transaction['shop_transactions']['target_id']]['timestamp']) ? $transArr[$transaction['shop_transactions']['target_id']]['timestamp'] : 0  ;
					$transArr[$transaction['shop_transactions']['target_id']]['type'] = isset($transArr[$transaction['shop_transactions']['target_id']]['type']) ? $transArr[$transaction['shop_transactions']['target_id']]['type'] : $transaction['shop_transactions']['type']  ;
				}else{
					$transArr[$transaction['shop_transactions']['id']]['id'] = $transaction['shop_transactions']['id'];
					$transArr[$transaction['shop_transactions']['id']]['amount'] = ( isset($transArr[$transaction['shop_transactions']['id']]['amount']) ? $transArr[$transaction['shop_transactions']['id']]['amount'] : 0 ) +  $transaction['shop_transactions']['amount'];
					$transArr[$transaction['shop_transactions']['id']]['opening'] = $transaction[0]['opening'];
					$transArr[$transaction['shop_transactions']['id']]['closing'] = $transaction[0]['closing'];
					$transArr[$transaction['shop_transactions']['id']]['timestamp'] = $transaction['shop_transactions']['timestamp'];
					$transArr[$transaction['shop_transactions']['id']]['type'] = $transaction['shop_transactions']['type'];

				}
			}
			$transactions = $transArr ;
			$trans_count = $this->Slaves->query($query);
			$trans_count = count($trans_count);
			$this->set(compact('trans_count','date_from','date_to'));
			if(!empty ($api)){
				$result['trans_count'] = $trans_count;
				$result['date_from'] = $date_from;
				$result['date_to'] = $date_to;
			}
		}
		else {
			$transactions = array();
		}

		/*}
		 else {
			$transactions = array();
			}*/
		$this->set('page',$page);
		$this->set('transactions',$transactions);

		if($date == null) $this->set('empty',0);
		$this->render('buy_report');
	}

	function topupDist($date = null,$dist=null){
		$pageType = empty($_GET['res_type']) ? "" : $_GET['res_type'];
                $page_old = empty($_GET['old_data']) ? "" : $_GET['old_data'];

                $grp_id = $_SESSION['Auth']['User']['group_id'];
                $query_old = '';


		if(is_array($date)){
			$params = $date;
			$date = $params['date'];
			$page = $params['page'];
		}
		if($dist == null)$dist = 0;

		if($date == null){
			$date = date('dmY') . '-' . date('dmY');
		}

		$this->set('dist',$dist);

		if($date != null){
			$dates = explode("-",$date);
			$date_from = $dates[0];
			$date_to = $dates[1];

			if(checkdate(substr($date_from,2,2), substr($date_from,0,2), substr($date_from,4)) && checkdate(substr($date_to,2,2), substr($date_to,0,2), substr($date_to,4))){
				$date_from =  substr($date_from,4) . "-" . substr($date_from,2,2) . "-" . substr($date_from,0,2);
				$date_to =  substr($date_to,4) . "-" . substr($date_to,2,2) . "-" . substr($date_to,0,2);

				if($grp_id == ADMIN){
					if($dist == 0){

						$query = "SELECT shop_transactions.id,shop_transactions.amount,shop_transactions.type,shop_transactions.note,shop_transactions.type_flag,st.amount as commission,shop_transactions.timestamp,trim(master_distributors.company) as company ,shop_transactions.target_opening AS opening, shop_transactions.target_closing AS closing FROM shop_transactions inner join master_distributors  ON (master_distributors.id = shop_transactions.target_id) left join shop_transactions as st ON (st.target_id = shop_transactions.id AND st.type = " . COMMISSION_MASTERDISTRIBUTOR . ") WHERE shop_transactions.confirm_flag != 1 AND shop_transactions.type =" . ADMIN_TRANSFER . " AND shop_transactions.date >= '$date_from' AND shop_transactions.date <= '$date_to' order by shop_transactions.id desc";
                                            if($page_old == "old_csv"){
                                                $query_old = "SELECT shop_transactions_logs.id,shop_transactions_logs.amount,shop_transactions_logs.type,shop_transactions_logs.note,shop_transactions_logs.type_flag,st.amount as commission,shop_transactions_logs.timestamp,trim(master_distributors.company) as company ,shop_transactions_logs.target_opening AS opening, shop_transactions_logs.target_closing AS closing FROM shop_transactions_logs USE INDEX ( type_date ) inner join master_distributors  ON (master_distributors.id = shop_transactions_logs.target_id) left join shop_transactions_logs as st ON (st.target_id = shop_transactions_logs.id AND st.type = " . COMMISSION_MASTERDISTRIBUTOR . ")  WHERE shop_transactions_logs.confirm_flag != 1 AND shop_transactions_logs.type =" . ADMIN_TRANSFER . " AND shop_transactions_logs.date >= '$date_from' AND shop_transactions_logs.date <= '$date_to' order by shop_transactions_logs.id desc";

                                            }

                                        }
					else {

						$query = "SELECT shop_transactions.id,shop_transactions.amount,shop_transactions.type,shop_transactions.note,shop_transactions.type_flag,st.amount as commission,shop_transactions.timestamp,trim(master_distributors.company) as company , shop_transactions.target_opening AS opening, shop_transactions.target_closing AS closing FROM shop_transactions inner join master_distributors  ON (master_distributors.id = shop_transactions.target_id) left join shop_transactions as st ON (st.target_id = shop_transactions.id AND st.type = " . COMMISSION_MASTERDISTRIBUTOR . ") WHERE shop_transactions.confirm_flag != 1 AND shop_transactions.target_id = $dist AND shop_transactions.type =" . ADMIN_TRANSFER . " AND shop_transactions.date >= '$date_from' AND shop_transactions.date <= '$date_to' order by shop_transactions.id desc";
                                             if($page_old == "old_csv"){
                                                $query_old = "SELECT shop_transactions_logs.id,shop_transactions_logs.amount,shop_transactions_logs.type,shop_transactions_logs.note,shop_transactions_logs.type_flag,st.amount as commission,shop_transactions_logs.timestamp,trim(master_distributors.company) as company , shop_transactions_logs.target_opening AS opening, shop_transactions_logs.target_closing AS closing FROM shop_transactions_logs USE INDEX ( type_date ) inner join master_distributors  ON (master_distributors.id = shop_transactions_logs.target_id) left join shop_transactions_logs as st ON (st.target_id = shop_transactions_logs.id AND st.type = " . COMMISSION_MASTERDISTRIBUTOR . ")  WHERE shop_transactions_logs.confirm_flag != 1 AND shop_transactions_logs.target_id = $dist AND shop_transactions_logs.type =" . ADMIN_TRANSFER . " AND shop_transactions_logs.date >= '$date_from' AND shop_transactions_logs.date <= '$date_to' order by shop_transactions_logs.id desc";
                                             }
					}

				}else {

					$r1 = 0;
					if($grp_id == RELATIONSHIP_MANAGER){
						$r1 = $this->info['master_dist_id'];
						$extra = " AND distributors.rm_id = " . $this->info['id'];
						$type = MDIST_DIST_BALANCE_TRANSFER.",".SDIST_DIST_BALANCE_TRANSFER;
					} else {
						if($grp_id == MASTER_DISTRIBUTOR){
							$type = MDIST_DIST_BALANCE_TRANSFER;
						}else if($grp_id == SUPER_DISTRIBUTOR){
							$type = SDIST_DIST_BALANCE_TRANSFER;
						}
						$r1 = $this->info['id'];
						$extra = "";
					}

					if($dist == 0){
                                                $query = "SELECT shop_transactions.target_id,shop_transactions.id,shop_transactions.amount,shop_transactions.type,shop_transactions.type_flag,shop_transactions.note,st.amount as commission,shop_transactions.timestamp,trim(distributors.company) as company , shop_transactions.source_opening AS opening, shop_transactions.source_closing AS closing FROM shop_transactions left join distributors  ON (distributors.id = shop_transactions.target_id)
                                                	left join super_distributors  ON (super_distributors.id = shop_transactions.target_id) left join shop_transactions as st ON (st.target_id = shop_transactions.id AND st.type = " . COMMISSION_DISTRIBUTOR . ")  WHERE shop_transactions.confirm_flag != 1 AND IF(shop_transactions.type = '".MDIST_DIST_BALANCE_TRANSFER."',shop_transactions.source_id = $r1,TRUE) AND shop_transactions.type IN  (" . $type . ") AND shop_transactions.date >= '$date_from' AND shop_transactions.date <= '$date_to'  $extra order by shop_transactions.id desc";
                                                if($page_old == "old_csv"){
                                                    $query_old = "SELECT shop_transactions.target_id,shop_transactions_logs.id,shop_transactions_logs.amount,shop_transactions_logs.type,shop_transactions_logs.type_flag,shop_transactions_logs.note,st.amount as commission,shop_transactions_logs.timestamp,trim(distributors.company) as company , shop_transactions_logs.source_opening AS opening, shop_transactions_logs.source_closing AS closing FROM shop_transactions_logs USE INDEX ( type_date ) left join distributors  ON (distributors.id = shop_transactions_logs.target_id) left join super_distributors  ON (super_distributors.id = shop_transactions.target_id) left join shop_transactions_logs as st ON (st.target_id = shop_transactions_logs.id AND st.type = " . COMMISSION_DISTRIBUTOR . ")  WHERE shop_transactions_logs.confirm_flag != 1 AND IF(shop_transactions.type = '".MDIST_DIST_BALANCE_TRANSFER."',shop_transactions.source_id = $r1,TRUE) AND shop_transactions_logs.type IN (" . $type . ") AND shop_transactions_logs.date >= '$date_from' AND shop_transactions_logs.date <= '$date_to'  $extra order by shop_transactions_logs.id desc";
                                                }
					} else {

                                                    $query = "SELECT shop_transactions.target_id,shop_transactions.id,shop_transactions.amount,shop_transactions.type,shop_transactions.type_flag,shop_transactions.note,st.amount as commission,shop_transactions.timestamp,trim(distributors.company) as company , shop_transactions.source_opening AS opening, shop_transactions.source_closing AS closing FROM shop_transactions left join distributors  ON (distributors.id = shop_transactions.target_id) left join super_distributors  ON (super_distributors.id = shop_transactions.target_id) left join shop_transactions as st ON (st.target_id = shop_transactions.id AND st.type = " . COMMISSION_DISTRIBUTOR . ")  WHERE shop_transactions.confirm_flag != 1 AND IF(shop_transactions.type = '".MDIST_DIST_BALANCE_TRANSFER."',shop_transactions.source_id = $r1,TRUE) AND shop_transactions.target_id = $dist AND shop_transactions.type  IN (" . $type . ") AND shop_transactions.date >= '$date_from' AND shop_transactions.date <= '$date_to' $extra order by shop_transactions.id desc";
                                                if($page_old == "old_csv"){
                                                    $query_old = "SELECT shop_transactions.target_id,shop_transactions_logs.id,shop_transactions_logs.amount,shop_transactions_logs.type,shop_transactions_logs.type_flag,shop_transactions_logs.note,st.amount as commission,shop_transactions_logs.timestamp,trim(distributors.company) as company , shop_transactions_logs.source_opening AS opening, shop_transactions_logs.source_closing AS closing FROM shop_transactions_logs left join distributors  ON (distributors.id = shop_transactions_logs.target_id) left join super_distributors  ON (super_distributors.id = shop_transactions.target_id) left join shop_transactions_logs as st ON (st.target_id = shop_transactions_logs.id AND st.type = " . COMMISSION_DISTRIBUTOR . ")  WHERE shop_transactions_logs.confirm_flag != 1 AND IF(shop_transactions.type = '".MDIST_DIST_BALANCE_TRANSFER."',shop_transactions.source_id = $r1,TRUE) AND shop_transactions_logs.target_id = $dist AND shop_transactions_logs.type IN (" . $type . ") AND shop_transactions_logs.date >= '$date_from' AND shop_transactions_logs.date <= '$date_to' $extra order by shop_transactions_logs.id desc";
                                                }
                                        }
                                        //exit;
				}

                $transactions = $this->Slaves->query($query);

                /** IMP DATA ADDED : START**/
                if($grp_id != ADMIN){
                    $dist_ids = array_map(function($element){
                        return $element['shop_transactions']['target_id'];
                    },$transactions);
                    $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
                }
                /** IMP DATA ADDED : END**/


                if(!empty($query_old)){
                    $transactions_old = $this->Slaves->query($query_old);

                    /** IMP DATA ADDED : START**/
                    if($grp_id != ADMIN){
                        $dist_ids = array_map(function($element){
                            return $element['shop_transactions']['target_id'];
                        },$transactions_old);
                        $imp_data_old = $this->Shop->getUserLabelData($dist_ids,2,3);
                    }
                    /** IMP DATA ADDED : END**/

                }
				$this->set(compact('date_from','date_to'));
			}
			else {
				$transactions = array();
                $transactions_old = array();
			}
		}
		else {
			$transactions = array();
                        $transactions_old = array();
		}

		$dist_company_name_array = array(MDIST_DIST_BALANCE_TRANSFER,SDIST_DIST_BALANCE_TRANSFER);
        $sd_company_name_array = array(MDIST_SDIST_BALANCE_TRANSFER);

        if($pageType != "csv"){        	

            foreach($transactions as $key => $transaction){

            	if(in_array($transaction['shop_transactions']['type'],$dist_company_name_array)){
            		$transactions[$key]['0']['company'] = "Distributor : ".$imp_data[$transaction['shop_transactions']['target_id']]['imp']['shop_est_name'];
            	}
            	else if(in_array($transaction['shop_transactions']['type'],$sd_company_name_array)){

            		$get_sd_user_id = $this->Slaves->query("SELECT user_id FROM super_distributors WHERE id = ".$transaction['shop_transactions']['target_id']);
                    	if(!empty($get_sd_user_id)){

                    		$sd_user_id = $get_sd_user_id[0]['super_distributors']['user_id'];

                    		 $sd_imp_data = $this->Shop->getUserLabelData($sd_user_id);
                    		 $sd_company = $sd_imp_data[$sd_user_id]['imp']['shop_est_name'];
                    	}

            		$transactions[$key]['0']['company'] = "Super Distributor : ".$sd_company;
            	}
                
            }
			$this->set('transactions',$transactions);
                        if($date == null) $this->set('empty',0);
		}else{

			App::import('Helper','csv');
			$this->layout = null;
			$this->autoLayout = false;
			$csv = new CsvHelper();
			//"v.company,v.shortForm, r.name,r.shopname,r.id,r.mobile,p.name,va.mobile, va.ref_code, va.amount, va.status, va.timestamp"

			//---------------------------
			//$line = array('Row','TransId','VendorTransId','Retailer Mobile', 'Shop', 'Vendor',  'Cust Mob','Operator','Circle','Amt','Comm','Status','Date','TypeStatus','Cause');
			$line = array("Transaction ID","Date", "Particulars","Transfer Type","Credit","Discount","Opening","Closing");
                        $csv->addRow($line);

			$i=1;

			foreach($transactions as $key => $transaction){
                                $id = $transaction['shop_transactions']['id'];
                                $timestamp = date('d-m-Y H:i:s', strtotime($transaction['shop_transactions']['timestamp']));
                                // $company = $transaction['0']['company'];

                                if(in_array($transaction['shop_transactions']['type'],$dist_company_name_array)){
				            		$company = $transactions[$key]['0']['company'] = "Distributor : ".$imp_data[$transaction['shop_transactions']['target_id']]['imp']['shop_est_name'];
				            	}
				            	else if(in_array($transaction['shop_transactions']['type'],$sd_company_name_array)){

				            		$get_sd_user_id = $this->Slaves->query("SELECT user_id FROM super_distributors WHERE id = ".$transaction['shop_transactions']['target_id']);
				                    	if(!empty($get_sd_user_id)){

				                    		$sd_user_id = $get_sd_user_id[0]['super_distributors']['user_id'];

				                    		 $sd_imp_data = $this->Shop->getUserLabelData($sd_user_id);
				                    		 $sd_company = $sd_imp_data[$sd_user_id]['imp']['shop_est_name'];
				                    }

				            		$company = $transactions[$key]['0']['company'] = "Super Distributor : ".$sd_company;
				            	}

                                $trans_type = $this->General->getTransferTypeName($transaction['shop_transactions']['type']);

                                $note = "";
                                if($transaction['shop_transactions']['type_flag'] == 1)
                                    $note = 'Cash';
                                else if($transaction['shop_transactions']['type_flag'] == 2)
                                    $note = 'NEFT';
                                else if($transaction['shop_transactions']['type_flag'] == 3)
                                    $note = 'ATM Transfer';
                                else if($transaction['shop_transactions']['type_flag'] == 4)
                                    $note = 'Cheque';
                                else if($transaction['shop_transactions']['type_flag'] == 5)
                                    $note = 'Payment Gateway';

                                $note = $note . " - " . $transaction['shop_transactions']['note'];

                                $amount = empty($transaction['shop_transactions']['amount']) ? 0 : $transaction['shop_transactions']['amount'];
                                $commission = empty($transaction['st']['commission']) ? 0 : $transaction['st']['commission'];
                                $opening = empty($transaction['shop_transactions']['opening'])? 0 : $transaction['shop_transactions']['opening'] ;
                                $closing = empty($transaction['shop_transactions']['closing']) ? 0 : $transaction['shop_transactions']['closing'];


				$line = array($id ,$timestamp , $company , $note ,$trans_type ,$amount ,  $commission , $opening , $closing);

				$csv->addRow($line);
				$i++;
			}


                        foreach($transactions_old as $key => $transaction){
                                $id = $transaction['shop_transactions_logs']['id'];
                                $timestamp = date('d-m-Y H:i:s', strtotime($transaction['shop_transactions_logs']['timestamp']));
                                // $company = $transaction['0']['company'];
                                //$company = $transactions_old[$key]['0']['company'] = $imp_data_old[$transaction['shop_transactions']['target_id']]['imp']['shop_est_name'];


                                if(in_array($transaction['shop_transactions']['type'],$dist_company_name_array)){
				            		$company = $transactions_old[$key]['0']['company'] = "Distributor : ".$imp_data[$transaction['shop_transactions']['target_id']]['imp']['shop_est_name'];
				            	}
				            	else if(in_array($transaction['shop_transactions']['type'],$sd_company_name_array)){

				            		$get_sd_user_id = $this->Slaves->query("SELECT user_id FROM super_distributors WHERE id = ".$transaction['shop_transactions']['target_id']);
				                    	if(!empty($get_sd_user_id)){

				                    		$sd_user_id = $get_sd_user_id[0]['super_distributors']['user_id'];

				                    		 $sd_imp_data = $this->Shop->getUserLabelData($sd_user_id);
				                    		 $sd_company = $sd_imp_data[$sd_user_id]['imp']['shop_est_name'];
				                    }

				            		$company = $transactions_old[$key]['0']['company'] = "Super Distributor : ".$sd_company;
				            	}

                                $trans_type = $this->General->getTransferTypeName($transaction['shop_transactions_logs']['type']);

                                $note = "";
                                if($transaction['shop_transactions_logs']['type_flag'] == 1)
                                    $note = 'Cash';
                                else if($transaction['shop_transactions_logs']['type_flag'] == 2)
                                    $note = 'NEFT';
                                else if($transaction['shop_transactions_logs']['type_flag'] == 3)
                                    $note = 'ATM Transfer';
                                else if($transaction['shop_transactions_logs']['type_flag'] == 4)
                                    $note = 'Cheque';
                                else if($transaction['shop_transactions_logs']['type_flag'] == 5)
                                    $note = 'Payment Gateway';

                                $note = $note . " - " . $transaction['shop_transactions_logs']['note'];

                                $amount = empty($transaction['shop_transactions_logs']['amount']) ? 0 : $transaction['shop_transactions_logs']['amount'];
                                $commission = empty($transaction['st']['commission']) ? 0 : $transaction['st']['commission'];
                                $opening = empty($transaction['shop_transactions']['opening'])? 0 : $transaction['shop_transactions']['opening'] ;
                                $closing = empty($transaction['shop_transactions']['closing']) ? 0 : $transaction['shop_transactions']['closing'];


				$line = array($id ,$timestamp , $company , $note ,$trans_type ,$amount ,  $commission , $opening , $closing);

				$csv->addRow($line);
				$i++;
			}

                        $fileNamePre = "Transactions_";
                        /*if($page_old != "old_csv"){
                            $fileNamePre = "Archieve_Transactions_";
                        }else{
                            $fileNamePre = "Transactions_";
                        }*/
			echo $csv->render($fileNamePre.$date_from."_".$date_to.".csv");

		}

                $this->set('pageType',$pageType);
		$this->render('buy_dist_report');
	}

	function saleReport($date = null,$id=null){
		$api = false;
		$service_id = null;
		if(is_array($date)){
			$api = true;
			$params = $date;
			$date = $params['date'];
			if(isset($params['id']))$id = $params['id'];
			if(isset($params['service']))$service_id = $params['service'];
		}
		if(!isset($_SESSION['Auth']['User']))$this->redirect(array('action' => 'index'));
		$show = true;

		if($date != null){
			$dates = explode("-",$date);
			$date_from = $dates[0];
			$date_to = $dates[1];
			if(checkdate(substr($date_from,2,2), substr($date_from,0,2), substr($date_from,4)) && checkdate(substr($date_to,2,2), substr($date_to,0,2), substr($date_to,4))){
				$date_from =  substr($date_from,4) . "-" . substr($date_from,2,2) . "-" . substr($date_from,0,2);
				$date_to =  substr($date_to,4) . "-" . substr($date_to,2,2) . "-" . substr($date_to,0,2);
			}
		}
		else {
			$date_from = date('Y-m-d');
			$date_to = date('Y-m-d');
		}
		$this->set('date_from',$date_from);
		$this->set('date_to',$date_to);

		$cond_id = '';
		if($id != null){
			$cond_id = " AND st3.source_id = $id";
			$this->set('id',$id);
		}
		if($show){
			$cond = 'AND st1.date >= "'.$date_from.'" AND st1.date <= "' .$date_to . '"';

			/*if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR){
				$query = "SELECT products.name,products.id,count(st1.id) as counts,sum(st1.amount) as amount, sum(st2.amount-st3.amount) as income FROM shop_transactions as st1 INNER JOIN shop_transactions as st2 ON (st2.target_id = st1.id AND st2.type = ".COMMISSION_MASTERDISTRIBUTOR." AND st2.source_id = ".$_SESSION['Auth']['id'].") INNER JOIN shop_transactions as st3 ON (st3.target_id = st1.id $cond_id AND st3.type = ".COMMISSION_DISTRIBUTOR.") INNER JOIN products ON (products.id = st1.target_id) WHERE st1.confirm_flag = 1 AND st1.type = ".RETAILER_ACTIVATION;
			}
			else if($this->Session->read('Auth.User.group_id') == DISTRIBUTOR){
				$query = "SELECT products.name,products.id,count(st1.id) as counts,sum(st1.amount) as amount FROM shop_transactions as st1 INNER JOIN products ON (products.id = st1.target_id) INNER JOIN retailers ON (retailers.id = st1.source_id AND retailers.parent_id = ".$this->info['id'].") WHERE st1.confirm_flag = 1 AND st1.type = ".RETAILER_ACTIVATION;
			}
			else */ if($this->Session->read('Auth.User.group_id') == RETAILER){
				//$query = "SELECT products.name,products.id,count(vd.id) as counts,sum(vd.amount) as amount, sum(st1.amount) as income FROM vendors_activations as vd INNER JOIN shop_transactions as st1 ON (st1.target_id = vd.shop_transaction_id AND st1.type = ".COMMISSION_RETAILER.") INNER JOIN products ON (products.id = vd.product_id) WHERE  vd.retailer_id = ".$_SESSION['Auth']['id']." AND vd.status != 2 AND vd.status != 3";
				$query = "SELECT products.name,products.id,count(va.id) as counts,sum(va.amount) as amount, sum(va.retailer_margin) as income FROM vendors_activations as va INNER JOIN products ON (products.id = va.product_id) WHERE va.retailer_id = ".$_SESSION['Auth']['id']." AND va.status != 2 AND va.status != 3";
				$cond = 'AND va.date >= "'.$date_from.'" AND va.date <= "' .$date_to . '"';
			}
			$products = array();
			if($service_id != null || !empty($service_id)){
				$services = $this->Slaves->query("SELECT services.id,services.name FROM services WHERE services.id = $service_id");
			}
			else {
				$services = $this->Slaves->query("SELECT services.id,services.name FROM services order by id");
			}

			foreach($services as $service){
				$service_id = $service['services']['id'];
				$query1 = $query . " AND service_id = $service_id $cond group by products.id";
				$data = $this->Slaves->query($query1);
				if(!empty($data)){
					$products[$service_id]['data'] = $data;
					$products[$service_id]['name'] = $service['services']['name'];
				}
			}
		}
		else {
			$products = array();
		}
		if(!$api)
		$this->set('products',$products);
		else return $products;
	}

	function investmentReport($from=null,$to=null,$vendorId=null,$summary=0){
	    //summary = 1 means date wise, summary = 2 means vendor wise
		if(!isset($from))
		$from=date('d-m-Y',strtotime('-7 days'));
		if(!isset($to))
		$to=date('d-m-Y');

		if(empty($vendorId))$vendorId = 0;

		$fdarr = explode("-",$from);
		$tdarr = explode("-",$to);

		$fd = $fdarr[2]."-".$fdarr[1]."-".$fdarr[0];
		$ft = $tdarr[2]."-".$tdarr[1]."-".$tdarr[0];

		$this->set('from',$from);
		$this->set('to',$to);

		//for vendors dropdown
		$vendorResult=$this->Shop->getVendors();
		$this->set('vendors',$vendorResult);
		$this->set('id',$vendorId);

		if($vendorId==0){
		    $result=$this->Slaves->query("SELECT earnings_logs.*,vendors.company,vendors.update_flag,sum(devices_data.inc) as inc FROM earnings_logs inner join  vendors ON (vendors.id = earnings_logs.vendor_id) left join devices_data ON (earnings_logs.date = devices_data.sync_date  and devices_data.vendor_id = earnings_logs.vendor_id ) where  date >= '".$fd."' and date <= '".$ft."' AND vendors.id not in (".SAAS_VENDORS.") group by vendors.id,date order by date desc,vendor_id");

		}else{
			$result=$this->Slaves->query("SELECT earnings_logs.*,vendors.company,vendors.update_flag,sum(devices_data.inc) as inc FROM earnings_logs inner join  vendors ON (vendors.id = earnings_logs.vendor_id) left join devices_data ON (earnings_logs.date = devices_data.sync_date  and devices_data.vendor_id = earnings_logs.vendor_id ) where  date >= '".$fd."' and date <= '".$ft."' and   earnings_logs.vendor_id = $vendorId AND vendors.id not in (".SAAS_VENDORS.") group by vendors.id,date order by date desc,vendor_id");
		}

		$data = array();


		foreach($result as $res){
		    $vendor_id = $res['earnings_logs']['vendor_id'];
		    $date = $res['earnings_logs']['date'];
		    if($summary == 0){
		        $data[$date][] = $res;
		    } else if($summary == 1){//date wise
		        if(!isset($data[$date])){
		            //if($date == $fd){
		              $data[$date]['opening'] = $res['earnings_logs']['opening'];
		            //}
		            //if($date == $ft){
		              $data[$date]['closing'] = $res['earnings_logs']['closing'];
		            //}
		            $data[$date]['invested'] = $res['earnings_logs']['invested'];
		            $data[$date]['exp_earn'] = $this->Shop->calculateExpectedEarning($res);
		            $data[$date]['sale'] = $res['earnings_logs']['sale'];
		            $data[$date]['earning'] = $res['earnings_logs']['sale'] - ($res['earnings_logs']['opening'] + $res['earnings_logs']['invested'] - $res['earnings_logs']['closing']);
		            $data[$date]['inc'] = $res['0']['inc'];
		        }
		        else {
		            //if($date == $fd){
		              $data[$date]['opening'] += $res['earnings_logs']['opening'];
		            //}
		            //if($date == $ft){
		              $data[$date]['closing'] += $res['earnings_logs']['closing'];
		            //}
		            $data[$date]['invested'] += $res['earnings_logs']['invested'];
		            $data[$date]['exp_earn'] += $this->Shop->calculateExpectedEarning($res);
		            $data[$date]['sale'] += $res['earnings_logs']['sale'];
		            $data[$date]['earning'] += $res['earnings_logs']['sale'] - ($res['earnings_logs']['opening'] + $res['earnings_logs']['invested'] - $res['earnings_logs']['closing']);
		            $data[$date]['inc'] += $res['0']['inc'];
		        }
		    } else if($summary == 2){//vendor wise
		        if(!isset($data[$vendor_id])){
		            if($date == $fd){
		              $data[$vendor_id]['opening'] = $res['earnings_logs']['opening'];
		            }
		            if($date == $ft){
		              $data[$vendor_id]['closing'] = $res['earnings_logs']['closing'];
		            }
		            $data[$vendor_id]['invested'] = $res['earnings_logs']['invested'];
		            $data[$vendor_id]['exp_earn'] = $this->Shop->calculateExpectedEarning($res);
		            $data[$vendor_id]['sale'] = $res['earnings_logs']['sale'];
		            $data[$vendor_id]['earning'] = $res['earnings_logs']['sale'] - ($res['earnings_logs']['opening'] + $res['earnings_logs']['invested'] - $res['earnings_logs']['closing']);
		            $data[$vendor_id]['inc'] = $res['0']['inc'];
		            $data[$vendor_id]['company'] = $res['vendors']['company'];
		        }
		        else {
		            if($date == $fd){
		              $data[$vendor_id]['opening'] += $res['earnings_logs']['opening'];
		            }
		            if($date == $ft){
		              $data[$vendor_id]['closing'] += $res['earnings_logs']['closing'];
		            }
		            $data[$vendor_id]['invested'] += $res['earnings_logs']['invested'];
		            $data[$vendor_id]['exp_earn'] += $this->Shop->calculateExpectedEarning($res);
		            $data[$vendor_id]['sale'] += $res['earnings_logs']['sale'];
		            $data[$vendor_id]['earning'] += $res['earnings_logs']['sale'] - ($res['earnings_logs']['opening'] + $res['earnings_logs']['invested'] - $res['earnings_logs']['closing']);
		            $data[$vendor_id]['inc'] += $res['0']['inc'];
		        }
		    }

		}

		$this->set('summary',$summary);
		$this->set('data',$data);
	}

	function addInvestmentEntry(){
		$vendor_id = $_POST['vendor_id'];
		$date = $_POST['date'];
		$saas_flag = 0;
		if(in_array($vendor_id,explode(",",SAAS_VENDORS))){
		    $saas_flag = 1;
		}
		if($vendor_id && $date){
			$fdarr = explode("-", $date);
			$fd = $fdarr[2]."-".$fdarr[1]."-".$fdarr[0];
			$earnings_logs = $this->User->query("select * from earnings_logs
					where vendor_id = '$vendor_id' and date = '$fd'");
			if(empty($earnings_logs)){
				if($this->User->query("insert into earnings_logs (vendor_id, date,saas_flag)
						values ('$vendor_id', '$fd','$saas_flag')"))
					echo "done";
					exit;
			}
		}
		$this->autoRender = false;
	}

	function floatReport($from=null,$to=null){

		if(!isset($from))
		$from=date('d-m-Y',strtotime('-30 days'));
//		$from=date('d-m-Y',strtotime('-1 days'));
		if(!isset($to))
		$to=date('d-m-Y');

		if(empty($vendorId))$vendorId = 0;

		$fdarr = explode("-",$from);
		$tdarr = explode("-",$to);

		$fd = $fdarr[2]."-".$fdarr[1]."-".$fdarr[0];
		$td = $tdarr[2]."-".$tdarr[1]."-".$tdarr[0];

		$fd_old = date('Y-m-d',strtotime($fd . ' -1 days'));

		$this->set('from',$from);
		$this->set('to',$to);

		$result1 = $this->Slaves->query("SELECT float_logs.* FROM float_logs WHERE date >= '".$fd_old."' AND date <= '".$td."' AND hour =24 order by date desc");
		$result2 = $this->Slaves->query("SELECT SUM(amount-txn_reverse_amt) as amt,date FROM users_nontxn_logs as refunds WHERE date >= '".$fd."' AND date <= '".$td."' AND type = ".REFUND." GROUP BY date ORDER BY date DESC");
                $result3 = $this->Slaves->query("SELECT SUM(amount) as amt,date FROM users_nontxn_logs as shop_transactions WHERE date >= '".$fd."' AND date <= '".$td."' AND type = ".RENTAL." GROUP BY date ORDER BY date DESC");
                $result4=$this->Slaves->query("SELECT sum(amount) as amt,date FROM `shop_transactions` where type = 2 AND source_id = 0 AND date >= '".$fd."' AND date <= '".$td."' group by date");
                $result5=$this->Slaves->query("SELECT SUM(if(confirm_flag = 1 AND type = 4,amount,0)) as amt, SUM(if(type_flag = 1 AND type = 11 ,amount,0)) as reversal,date FROM shop_transactions USE INDEX ( ref1_type ) WHERE date >= '".$fd."' AND date <= '".$td."' AND source_id = 13 AND type in (4,11) group by date");

		$data = array();
		foreach($result1 as $res){
			$data[$res['float_logs']['date']]['closing'] = $res['float_logs']['float'];
			$data[date('Y-m-d',strtotime($res['float_logs']['date'].' + 1 days'))]['opening'] = $res['float_logs']['float'];
			$data[$res['float_logs']['date']]['sale'] = $res['float_logs']['sale'];
			$data[$res['float_logs']['date']]['transferred'] = $res['float_logs']['transferred'];
			$data[$res['float_logs']['date']]['commission'] = $res['float_logs']['commissions'];
			$data[$res['float_logs']['date']]['reversals'] = $res['float_logs']['old_reversals'];
		}

		//unset($data[$fd_old]);
                foreach($result2 as $res){
			$data[$res['refunds']['date']]['refund'] = $res['0']['amt'];
		}

		foreach($result3 as $res){
			$data[$res['shop_transactions']['date']]['rental'] = $res['0']['amt'];
		}

		foreach($result4 as $res){
			$data[$res['shop_transactions']['date']]['adjusted'] = $res['0']['amt'];
		}

		foreach($result5 as $res){
			$data[$res['shop_transactions']['date']]['b2c_topup'] = $res['0']['amt'] - $res['0']['reversal'];
		}

		$this->set('data',$data);
	}

	function addInvestedAmount(){
		$id = trim($_REQUEST['id']);
		$amount = empty($_REQUEST['amount'])? 0 : $_REQUEST['amount'];
		$opening = empty($_REQUEST['opening'])? 0 : $_REQUEST['opening'];
		$closing = empty($_REQUEST['closing'])? 0 : $_REQUEST['closing'];
		$comment = empty($_REQUEST['comment'])? "" : $_REQUEST['comment'];
		$max_date = date('Y-m-d',strtotime('- 180 days'));

		$this->Retailer->query("UPDATE earnings_logs
				SET invested='$amount',opening='$opening',closing='$closing', comment='$comment'
				WHERE id = $id AND date >= '$max_date'");

		echo 'done';
		$this->autoRender = false;
	}

	function earningReport($from=null,$to=null){
		if(!isset($from))
		$from=date('d-m-Y',strtotime('-7 days'));
		if(!isset($to))
		$to=date('d-m-Y');

		$fdarr = explode("-",$from);
		$tdarr = explode("-",$to);

		$fd = $fdarr[2]."-".$fdarr[1]."-".$fdarr[0];
		$ft = $tdarr[2]."-".$tdarr[1]."-".$tdarr[0];

		$this->set('from',$from);
		$this->set('to',$to);

		$result=$this->Slaves->query("SELECT sum(sale) as sale,sum(invested) as invested,sum(expected_earning) as expected_earning,sum(if(vendors.id != 5,sale+closing-opening-invested,0)) as earning,sum(if(vendors.update_flag=0,old_reversal,0)) as reversal,date,vendor_id FROM earnings_logs left join vendors ON (vendors.id = vendor_id) WHERE closing is not null AND date >= '".$fd."' AND date <= '".$ft."' AND earnings_logs.saas_flag =0 group by date order by date desc");

		$data = array();
		foreach($result as $res){
		    $data[$res['earnings_logs']['date']]['sale'] = $res['0']['sale'];
		    $data[$res['earnings_logs']['date']]['invested'] = $res['0']['invested'];
		    $data[$res['earnings_logs']['date']]['earning'] = $res['0']['earning'];
		    $data[$res['earnings_logs']['date']]['expected_earning'] = $res['0']['expected_earning'];
		    $data[$res['earnings_logs']['date']]['reversal'] = $res['0']['reversal'];
		}
                
//		$extra_ret=$this->Slaves->query("SELECT sum(earning) as earning,date FROM retailers_logs left join retailers on (retailers.id = retailer_id) WHERE date >= '".$fd."' AND date <= '".$ft."' AND retailers.parent_id not in (".SAAS_DISTS.") group by date");
		$extra_ret=$this->Slaves->query("SELECT SUM(rel.commission) AS earning,rel.date "
                        . "FROM retailer_earning_logs rel "
                        . "LEFT JOIN retailers ON (retailers.user_id = rel.ret_user_id) "
                        . "JOIN distributors d ON (rel.dist_user_id = d.user_id)  "
                        . "WHERE rel.date >= '".$fd."' "
                        . "AND rel.date <= '".$ft."' "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "AND d.id not in (".SAAS_DISTS.") "
                        . "GROUP BY rel.date");
		$extra_ret_service_charge=$this->Slaves->query("SELECT SUM(rel.service_charge) AS earning,rel.date "
		        . "FROM retailer_earning_logs rel "
		        . "LEFT JOIN retailers ON (retailers.user_id = rel.ret_user_id) "
		        . "JOIN distributors d ON (rel.dist_user_id = d.user_id)  "
		        . "WHERE rel.date >= '".$fd."' "
		        . "AND rel.date <= '".$ft."' "
		        . "AND rel.service_id IN (1,2,4,5,6,7) "
		        . "AND d.id not in (".SAAS_DISTS.") "
		        . "GROUP BY rel.date");
        
		$extra_dist=$this->Slaves->query("SELECT sum(users_nontxn_logs.amount) as earning,users_nontxn_logs.date FROM users_nontxn_logs,distributors WHERE distributors.user_id = users_nontxn_logs.user_id AND distributors.parent_id = 3 AND users_nontxn_logs.date >= '".$fd."' AND users_nontxn_logs.date <= '".$ft."' AND users_nontxn_logs.service_id <= 7 AND users_nontxn_logs.type=".COMMISSION_DISTRIBUTOR." AND distributors.id not in (".SAAS_DISTS.") GROUP BY users_nontxn_logs.date");
		$extra_masterdist=$this->Slaves->query("SELECT sum(shop_transactions.amount) as earning,shop_transactions.date FROM shop_transactions WHERE shop_transactions.type = ".COMMISSION_MASTERDISTRIBUTOR." AND confirm_flag != 1 AND shop_transactions.date >= '".$fd."' AND shop_transactions.date <= '".$ft."' group by shop_transactions.date");
		
		$refund=$this->Slaves->query("SELECT sum(amount-txn_reverse_amt) as amt,date FROM users_nontxn_logs as refunds WHERE date >= '".$fd."' AND date <= '".$ft."' AND type = ".REFUND." AND service_id <= 7 GROUP BY date ORDER BY date DESC");
		
		foreach($extra_ret as $ext){
			if(isset($data[$ext['rel']['date']]))
			$data[$ext['rel']['date']]['retailer_earning'] = $ext['0']['earning'];
		}
		foreach($extra_ret_service_charge as $ext){
		    if(isset($data[$ext['rel']['date']]))
		        $data[$ext['rel']['date']]['retailer_service_charge'] = $ext['0']['earning'];
		}
		foreach($extra_dist as $ext){
			if(isset($data[$ext['users_nontxn_logs']['date']]))
			$data[$ext['users_nontxn_logs']['date']]['distributor_earning'] = $ext['0']['earning'];
		}
		foreach($extra_masterdist as $ext){
			if(isset($data[$ext['shop_transactions']['date']]))
			$data[$ext['shop_transactions']['date']]['sdistributor_earning'] = $ext['0']['earning'];
		}
		foreach($refund as $ext){
			if(isset($data[$ext['refunds']['date']]))
			$data[$ext['refunds']['date']]['refunds'] = $ext['0']['amt'];
		}

		$this->set('data',$data);
	}

	function salesmanReport($from=null,$to=null,$salesmanId=null,$retailerId=null,$type=null,$reports=0)
	{
                ini_set("memory_limit", "4096M");
                
                if($this->Session->read('Auth.system_used') == 0) {
                        $reports = 0;
                }

                if(!empty($from) && !$this->General->dateValidate($from)){
                    $this->redirect(array('action' => 'index'));
                }
                else if(!empty($to) && !$this->General->dateValidate($to)){
                    $this->redirect(array('action' => 'index'));
                }
                else if(!empty($salesmanId) && !is_numeric($salesmanId)){
                    $this->redirect(array('action' => 'index'));
                }
                else if(!empty($retailerId) && !is_numeric($retailerId)){
                    $this->redirect(array('action' => 'index'));
                }
                $pageType = empty($_GET['res_type']) ? "" : $_GET['res_type'];
                $page_old = empty($_GET['old_data']) ? "" : $_GET['old_data'];
                $this->set('pageType',$pageType);
                if(!in_array($this->Session->read('Auth.User.group_id'), array(DISTRIBUTOR, SALESMAN))){
			$this->redirect(array('action' => 'index'));
		}
		//echo "Start SSM".$salesmanMobile;
		if(!isset($from))
		$from=date('d-m-Y');
		if(!isset($to))
		$to=date('d-m-Y');

		$fdarr = explode("-",$from);
		$tdarr = explode("-",$to);

		$fd = $fdarr[2]."-".$fdarr[1]."-".$fdarr[0];
		$ft = $tdarr[2]."-".$tdarr[1]."-".$tdarr[0];

		$this->set('from',$from);
		$this->set('to',$to);

                //for salesman dropdown
		$salesmanResult=$this->Slaves->query("select * from salesmen where mobile != ".$this->Session->read('Auth.User.mobile')." AND dist_id = ".$this->info['id']." AND active_flag = 1");
		$this->set('salesmans',$salesmanResult);

		//for retailers dropDown
		//$retailerResult=$this->User->query("select * from retailers where parent_id = ".$this->info['id']);
		//echo "select * from retailers where parent_id = ".$this->info['id'];
		$this->set('retailers',$this->retailers);

		//for table in salesman Reports
		//	echo "Sales Mobile= ".$salesmanMobile;
		$this->set('id', $salesmanId);
		$this->set('rid', $retailerId);

                $salesMenCond = "";
		if(!empty($salesmanId)){
			$salesMenCond = "salesmen.id = $salesmanId";
			$salesMenCondNewSal = "st.target_id = $salesmanId";
			$salesMenCondNewRet = "st.source_id = $salesmanId";
			$salesMenCondSal = "st.source_id = $salesmanId";
                        $appSalCond = "r.maint_salesman = $salesmanId";
                        $appSalCondNew = "retailers.maint_salesman = $salesmanId";
		}else{
			$salesMenCond = $salesMenCondNewSal = $salesMenCondNewRet = $salesMenCondSal = $appSalCond = $appSalCondNew = 1;
		}

		if(!empty($retailerId)){
			$retailerCond = "r.id = $retailerId";
			$retailerCondNew = "st.target_id = $retailerId";
			$retailerCondSal = "st.target_id = $retailerId";
			$appRetCond = "r.id = $retailerId";
			$appRetCondNew = "retailers.id = $retailerId";
                        $retailerCondDist = "salesmen.id = $retailerId";
		}else{
			$retailerCond = $retailerCondNew = $retailerCondSal = $appRetCond = $appRetCondNew = $retailerCondDist = 1;
		}

		if($_POST['request_from'] == "distributorApp"){
			if($this->Session->read('Auth.User.group_id') == DISTRIBUTOR){
				$distributor_id = $this->Session->read('Auth.id');
                                $reports_new = 'distributor';
			}
			else {
				$distributor_id = $this->Session->read('Auth.dist_id');
                                $reports_new = 'salesman';
			}
if($reports == 0) {
			$salesResult = $this->Slaves->query("
                select r.id,r.name,r.mobile,ur.shopname,st.amount,sst.id,st.id,st.note,st.type_flag,sst.created,salesmen.name,
				st.source_opening,st.source_closing,salesmen.id,sst.closing,(sst.closing + st.amount) as s_opening
                from shop_transactions st
                left join salesman_transactions sst on (st.id=sst.shop_tran_id)
                left join salesmen ON (salesmen.id = sst.salesman)
                left join   retailers r on(r.id=st.target_id)
				left join unverified_retailers ur on ur.retailer_id = r.id
                where
                        $appSalCond AND
                       $appRetCond AND
                st.source_id = ".$distributor_id." AND
                st.confirm_flag != 1 AND
                st.type = ".DIST_RETL_BALANCE_TRANSFER." AND
                st.date between '".$fd."' and '".$ft."'
                order by st.timestamp desc");

            $report = array();

            /** IMP DATA ADDED : START**/
            $ret_ids = array_map(function($element){
                return $element['r']['id'];
            },$salesResult);

            $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
            /** IMP DATA ADDED : END**/

			foreach($salesResult as $sr){

                $sr['ur']['shopname'] = $imp_data[$sr['r']['id']]['imp']['shop_est_name'];
				$note = "";
                if($transaction['st']['type_flag'] == 1)
                	$note = 'Cash';
                else if($transaction['st']['type_flag'] == 2)
                    $note = 'NEFT';
                else if($transaction['st']['type_flag'] == 3)
                    $note = 'ATM Transfer';
                else if($transaction['st']['type_flag'] == 4)
                    $note = 'Cheque';
                else if($transaction['st']['type_flag'] == 5)
                    $note = 'Payment Gateway';
                $note .= " - " . $sr['st']['note'];
                if($this->Session->read('Auth.User.group_id') == DISTRIBUTOR){
                	$opening = $sr['st']['source_opening'];
                	$closing = $sr['st']['source_closing'];
                }
                else {
                	$opening = $sr['0']['s_opening'];
                	$closing = $sr['sst']['closing'];
                }

				$report[] = array(
					"s_t_id" 	=> $sr['st']['id'],
					"a"			=> $sr['st']['amount'],
					"o"			=> $opening,
					"c"			=> $closing,
					"t"			=> $sr['sst']['created'],
					"n"			=> $note,
					"r_id"		=> $sr['r']['id'],
					"r_m"		=> $sr['r']['mobile'],
					"r_sn"		=> $sr['ur']['shopname'],
					"s_id"		=> $sr['salemen']['id'],
					"sm_t_id"	=> $sr['sst']['id']
				);
			}
                        } else {
                                $type_flag = array(1=>'Cash', 2=>'NEFT', 3=>'ATM Transfer', 4=>'Cheque', 5=>'Payment Gateway');

                                if($reports_new == 'distributor' && $type == 0) {
                                        $salesResult = $this->Slaves->query("SELECT * FROM ((SELECT st.id,st.amount,st.timestamp,st.type_flag,st.note,st.source_opening as opening,st.source_closing as closing,
                                                salesmen.id shop_id,concat(salesmen.name,' (Salesman)') shopname,salesmen.mobile
                                                FROM shop_transactions st
                                                JOIN salesmen ON (st.target_id = salesmen.id AND salesmen.mobile != '".$this->Session->read('Auth.User.mobile')."')
                                                WHERE $salesMenCond AND $retailerCondDist AND st.source_id = '".$this->Session->read('Auth.id')."' AND st.date = '".date('Y-m-d', strtotime($from))."' AND st.type = ".DIST_SLMN_BALANCE_TRANSFER." AND st.confirm_flag != 1)
                                                UNION
                                                (SELECT st.id,st.amount,st.timestamp,st.type_flag,st.note,st.source_opening as opening,st.source_closing as closing,retailers.id shop_id,
                                                concat(unverified_retailers.shopname,' (Retailer)') shopname,retailers.mobile
                                                FROM shop_transactions st
                                                JOIN salesmen ON (st.source_id = salesmen.id AND salesmen.mobile = '".$this->Session->read('Auth.User.mobile')."')
                                                JOIN retailers ON (st.target_id = retailers.id)
                                                JOIN unverified_retailers ON (retailers.id = unverified_retailers.retailer_id)
                                                WHERE $appSalCondNew AND $appRetCondNew AND st.date = '".date('Y-m-d', strtotime($from))."' AND st.type = ".SLMN_RETL_BALANCE_TRANSFER." AND st.user_id IS NOT NULL AND st.confirm_flag != 1)) t ORDER BY id DESC");

                                        $report = array();

                                        /** IMP DATA ADDED : START**/
                                        $ret_ids = array_map(function($element){
                                            return $element['t']['shop_id'];
                                        },$salesResult);

                                        $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
                                        /** IMP DATA ADDED : END**/

                                        foreach($salesResult as $sr){
                                                $sr['t']['shopname'] = $imp_data[$sr['t']['shop_id']]['imp']['shop_est_name'];
                                                $report[] = array(
                                                        "s_t_id" 	=> $sr['t']['id'],
                                                        "a"		=> $sr['t']['amount'],
                                                        "o"		=> $sr['t']['opening'],
                                                        "c"		=> $sr['t']['closing'],
                                                        "t"		=> $sr['t']['timestamp'],
                                                        "n"		=> $type_flag[$sr['t']['type_flag']].' - '.$sr['t']['note'],
                                                        "r_id"		=> $sr['t']['shop_id'],
                                                        "r_m"		=> $sr['t']['mobile'],
                                                        "r_sn"		=> $sr['t']['shopname'],
                                                        "s_id"		=> $sr['t']['shop_id'],
                                                        "sm_t_id"	=> $sr['t']['id']
                                                );
                                        }
                                } else if($reports_new == 'salesman' || $type == 1) {
                                        if($reports_new == 'salesman') {
                                                $cond = "st.source_id = '".$this->Session->read('Auth.id')."'";
                                        } else if($type == 1) {
                                                $cond = "retailers.parent_id = '".$this->Session->read('Auth.id')."'";
                                        }
                                        $salesResult = $this->Slaves->query("SELECT st.id,st.amount,st.timestamp,st.type_flag,st.note,st.source_opening as opening,st.source_closing as closing,retailers.id shop_id,retailers.shopname,ur.shopname,retailers.mobile
                                                FROM shop_transactions st
                                                JOIN retailers ON (st.target_id = retailers.id)
												JOIN unverified_retailers ur on (ur.retailer_id = retailers.id)
												WHERE $appSalCondNew AND $appRetCondNew AND st.date = '".date('Y-m-d', strtotime($from))."' AND $cond AND st.type = ".SLMN_RETL_BALANCE_TRANSFER." AND (st.user_id IS NULL OR st.user_id = 0) AND st.confirm_flag != 1 ORDER BY st.id DESC");

                                        /** IMP DATA ADDED : START**/
                                        $ret_ids = array_map(function($element){
                                            return $element['retailers']['shop_id'];
                                        },$salesResult);

                                        $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
                                        /** IMP DATA ADDED : END**/

                                        $report = array();
                                        foreach($salesResult as $sr){
                                                $sr['ur']['shopname'] = $sr['retailers']['shopname'] = $imp_data[$sr['retailers']['shop_id']]['imp']['shop_est_name'];
                                                $report[] = array(
                                                        "s_t_id" 	=> $sr['st']['id'],
                                                        "a"		=> $sr['st']['amount'],
                                                        "o"		=> $sr['st']['opening'],
                                                        "c"		=> $sr['st']['closing'],
                                                        "t"		=> $sr['st']['timestamp'],
                                                        "n"		=> $type_flag[$sr['st']['type_flag']].' - '.$sr['st']['note'],
                                                        "r_id"		=> $sr['retailers']['shop_id'],
                                                        "r_m"		=> $sr['retailers']['mobile'],
                                                        "r_sn"		=> ($sr['ur']['shopname'] != '' ? $sr['ur']['shopname'] : $sr['retailers']['shopname']),
                                                        "s_id"		=> $sr['salemen']['id'],
                                                        "sm_t_id"	=> $sr['st']['id']
                                                );
                                        }
                                }
                        }
			return array("status" => "success", "description" => $report);
		} else {
                        if($reports == 0) {
                                $salesResult = $this->Slaves->query("
                                        select r.name,r.mobile,r.shopname,ur.shopname,st.amount,sst.id,st.id,st.note,st.type_flag,sst.created,salesmen.name,st.source_opening,st.source_closing
                                        from shop_transactions st
                                        left join salesman_transactions sst on (st.id=sst.shop_tran_id)
                                        left join salesmen ON (salesmen.id = sst.salesman)
                                        left join retailers r on(r.id=st.target_id)
										left join unverified_retailers ur on (ur.retailer_id = r.id)
										where $salesMenCond AND $retailerCond AND
                                        st.source_id = ".$_SESSION['Auth']['id']." AND
                                        st.confirm_flag != 1 AND
                                        st.type = ".DIST_RETL_BALANCE_TRANSFER." AND
                                        st.date between '".$fd."' and '".$ft."'
                                        order by st.timestamp desc");

                                        /** IMP DATA ADDED : START**/
                                        $ret_mobiles = array_map(function($element){
                                            return $element['r']['mobile'];
                                        },$salesResult);

                                        $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);

                                        $retailer_imp_label_map = array(
                                            'pan_number' => 'pan_no',
                                            'shopname' => 'shop_est_name',
                                            'alternate_number' => 'alternate_mobile_no',
                                            'email' => 'email_id',
                                            'shop_structure' => 'shop_ownership',
                                            'shop_type' => 'business_nature'
                                        );
                                        foreach ($salesResult as $key => $retailer) {
                                            foreach ($retailer['r'] as $retailer_label_key => $value) {
                                                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                                                if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['mobile']]['imp']) ){
                                                    $salesResult[$key]['r'][$retailer_label_key] = $imp_data[$retailer['r']['mobile']]['imp'][$retailer_label_key_mapped];
                                                }
                                            }
                                            foreach ($retailer['ur'] as $retailer_label_key => $value) {
                                                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                                                if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['mobile']]['imp']) ){
                                                    $salesResult[$key]['ur'][$retailer_label_key] = $imp_data[$retailer['r']['mobile']]['imp'][$retailer_label_key_mapped];
                                                }
                                            }
                                        }
                                        /** IMP DATA ADDED : END**/
                        } else if($reports == 1) {
                                $salesResult = $this->Slaves->query("SELECT * FROM ((SELECT st.id,st.user_id,st.source_id,st.target_id,st.amount,st.type_flag,st.note,st.timestamp,
                                        salesmen.name salesmen_name,salesmen.mobile salesmen_mobile,salesmen.created salesmen_created,st.target_opening salesman_opening,st.target_closing salesman_closing,'' retailer_shopname,'' ur_shopname,'' retailer_mobile,'' retailer_opening,'' retailer_closing
                                        FROM shop_transactions st
                                        JOIN salesmen ON (st.target_id = salesmen.id AND salesmen.mobile != '".$_SESSION['Auth']['User']['mobile']."')
                                        WHERE $salesMenCondNewSal AND st.source_id = ".$_SESSION['Auth']['id']." AND st.confirm_flag != 1 AND
                                        st.type = ".DIST_SLMN_BALANCE_TRANSFER." AND st.date between '".$fd."' and '".$ft."')
                                        UNION
                                        (SELECT st.id,st.user_id,st.source_id,st.target_id,st.amount,st.type_flag,st.note,st.timestamp,salesmen.name salesmen_name,salesmen.mobile salesmen_mobile,salesmen.created salesmen_created,st.source_opening salesman_opening,st.source_closing salesman_closing,
                                        retailers.shopname retailer_shopname,ur.shopname ur_shopname,retailers.mobile retailer_mobile,st.target_opening retailer_opening,st.target_closing retailer_closing
                                        FROM shop_transactions st
                                        JOIN salesmen ON (st.source_id = salesmen.id AND salesmen.mobile = '".$_SESSION['Auth']['User']['mobile']."')
                                        JOIN retailers ON (st.target_id = retailers.id)
										JOIN unverified_retailers ur on (ur.retailer_id = retailers.id)
										WHERE $salesMenCondNewRet AND $retailerCondNew AND st.confirm_flag != 1 AND st.type = ".SLMN_RETL_BALANCE_TRANSFER." AND
                                        st.user_id IS NOT NULL AND st.date between '".$fd."' and '".$ft."')) txn ORDER BY id DESC");


                            /** IMP DATA ADDED : START**/
                            $ret_mobiles = array_map(function($element){
                                return $element['txn']['retailer_mobile'];
                            },$salesResult);

                            $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);

                            $retailer_imp_label_map = array(
                                'pan_number' => 'pan_no',
                                'retailer_shopname' => 'shop_est_name',
                                'ur_shopname' => 'shop_est_name',
                                'alternate_number' => 'alternate_mobile_no',
                                'email' => 'email_id',
                                'shop_structure' => 'shop_ownership',
                                'shop_type' => 'business_nature'
                            );
                            foreach ($salesResult as $key => $retailer) {
                                foreach ($retailer['txn'] as $retailer_label_key => $value) {
                                    $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                                    if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['txn']['retailer_mobile']]['imp']) ){
                                        $salesResult[$key]['txn'][$retailer_label_key] = $imp_data[$retailer['txn']['retailer_mobile']]['imp'][$retailer_label_key_mapped];
                                    }
                                }
                            }
                            /** IMP DATA ADDED : END**/

                        } else if($reports == 2) {
                                $salesResult = $this->Slaves->query("SELECT st.id,st.amount,salesmen.name,salesmen.mobile,st.source_opening,st.source_closing,retailers.shopname,ur.shopname,retailers.mobile,st.target_opening,st.target_closing,st.type_flag,st.note,st.timestamp
                                        FROM shop_transactions st
                                        LEFT JOIN salesmen ON (st.source_id = salesmen.id)
                                        LEFT JOIN retailers ON (st.target_id = retailers.id)
										LEFT JOIN unverified_retailers ur on (ur.retailer_id = retailers.id)
                                        WHERE $salesMenCondSal AND $retailerCondSal AND
                                        salesmen.dist_id = ".$this->Session->read('Auth.id')." AND (st.user_id IS NULL OR st.user_id = 0) AND
                                        st.confirm_flag = 0 AND st.type = ".SLMN_RETL_BALANCE_TRANSFER."
                                        AND st.date BETWEEN '".$fd."' and '".$ft."'
                                        ORDER BY st.timestamp DESC");

                                        /** IMP DATA ADDED : START**/
                                        $ret_mobiles = array_map(function($element){
                                            return $element['retailers']['mobile'];
                                        },$salesResult);

                                        $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);

                                        $retailer_imp_label_map = array(
                                            'pan_number' => 'pan_no',
                                            'shopname' => 'shop_est_name',
                                            'alternate_number' => 'alternate_mobile_no',
                                            'email' => 'email_id',
                                            'shop_structure' => 'shop_ownership',
                                            'shop_type' => 'business_nature'
                                        );
                                        foreach ($salesResult as $key => $retailer) {
                                            foreach ($retailer['retailers'] as $retailer_label_key => $value) {
                                                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                                                if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['mobile']]['imp']) ){
                                                    $salesResult[$key]['retailers'][$retailer_label_key] = $imp_data[$retailer['r']['mobile']]['imp'][$retailer_label_key_mapped];
                                                }
                                            }
                                            foreach ($retailer['ur'] as $retailer_label_key => $value) {
                                                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                                                if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['mobile']]['imp']) ){
                                                    $salesResult[$key]['ur'][$retailer_label_key] = $imp_data[$retailer['r']['mobile']]['imp'][$retailer_label_key_mapped];
                                                }
                                            }
                                        }
                                        /** IMP DATA ADDED : END**/
                        }
		}
                if($page_old == "old_csv"){
                        $query_old = "
                            select r.name,r.mobile,r.shopname,ur.shopname,st.amount,sst.id,st.id,st.note,st.type_flag,sst.created,salesmen.name,st.source_opening,st.source_closing
                            from shop_transactions_logs st
                            left join salesman_transactions sst on (st.id=sst.shop_tran_id)
                            left join salesmen ON (salesmen.id = sst.salesman)
                            left join retailers r on(r.id=st.target_id)
							left join unverified_retailers ur on (ur.retailer_id = r.id)
							where
                                    $salesMenCond AND
                                    $retailerCond AND
                            st.source_id = ".$this->info['id']." AND
                            st.confirm_flag != 1 AND
                            st.type = ".DIST_RETL_BALANCE_TRANSFER." AND
                            st.date between '".$fd."' and '".$ft."'
                            order by st.timestamp desc
                        ";
                }

        if($pageType != "csv"){
            $this->set('salesResult',$salesResult);
            $this->set('reports',$reports);
        }else{
            App::import('Helper','csv');
			$this->layout = null;
			$this->autoLayout = false;
			$csv = new CsvHelper();

            if($reports == 0) {
                    $line = array("Transaction ID","Salesman","Retailer" , "Retailer Mobile","Note","Amount","Opening","Closing","Time");
            } else if(in_array($reports,array(1,2))) {
                    $line = array("Transfer Type","Txn Id","Amount","Salesman","Salesman Mobile","Salesman Opening","Salesman Closing","Retailer","Retailer Mobile","Retailer Opening","Retailer Closing","Note","Time");
            }
            $csv->addRow($line);
            $i=1;

            $type_flag = array(1=>'Cash',2=>'NEFT',3=>'ATM Transfer',4=>'Cheque/DD',5=>'Payment Gateway');
            if($reports == 0) {
                    foreach($salesResult as $transaction){
                            $TransactionID = $transaction['st']['id'];
                            $Salesman = $transaction['salesmen']['name'];
                            $Retailer = ($transaction['ur']['shopname'] != '' ? $transaction['ur']['shopname'] : $transaction['r']['shopname']);
                            $RetailerMobile = $transaction['r']['mobile'];
                            $Note = "";
                            if($transaction['st']['type_flag'] == 1)
                            $Note = 'Cash';
                            else if($transaction['st']['type_flag'] == 2)
                            $Note = 'NEFT';
                            else if($transaction['st']['type_flag'] == 3)
                            $Note = 'ATM Transfer';
                            else if($transaction['st']['type_flag'] == 4)
                            $Note = 'Cheque';
                            else if($transaction['st']['type_flag'] == 5)
                            $Note = 'Payment Gateway';

                            $Note .= " - " . $transaction['st']['note'];

                            $Amount = $transaction['st']['amount'];
                            $Opening = $transaction['st']['opening'];
                            $Closing = $transaction['st']['closing'];
                            $Time = $transaction['sst']['created'];

                            $line = array($TransactionID,$Salesman,$Retailer,$RetailerMobile,$Note,$Amount,$Opening,$Closing,$Time);
                            $csv->addRow($line);
                            $i++;
                    }
                    if(!empty($query_old)){
                        $salesResult_old = $this->Slaves->query($query_old);

                        /** IMP DATA ADDED : START**/
                        $ret_mobiles = array_map(function($element){
                            return $element['r']['mobile'];
                        },$salesResult_old);

                        $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);

                        $retailer_imp_label_map = array(
                            'pan_number' => 'pan_no',
                            'shopname' => 'shop_est_name',
                            'alternate_number' => 'alternate_mobile_no',
                            'email' => 'email_id',
                            'shop_structure' => 'shop_ownership',
                            'shop_type' => 'business_nature'
                        );
                        foreach ($salesResult_old as $key => $retailer) {
                            foreach ($retailer['r'] as $retailer_label_key => $value) {
                                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                                if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['mobile']]['imp']) ){
                                    $salesResult_old[$key]['r'][$retailer_label_key] = $imp_data[$retailer['r']['mobile']]['imp'][$retailer_label_key_mapped];
                                }
                            }
                            foreach ($retailer['ur'] as $retailer_label_key => $value) {
                                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                                if( array_key_exists($retailer_label_key_mapped,$imp_data[$retailer['r']['mobile']]['imp']) ){
                                    $salesResult_old[$key]['ur'][$retailer_label_key] = $imp_data[$retailer['r']['mobile']]['imp'][$retailer_label_key_mapped];
                                }
                            }
                        }
                        /** IMP DATA ADDED : END**/

                        foreach($salesResult_old as $transaction){
                             $TransactionID = $transaction['st']['id'];
                             $Salesman = $transaction['salesmen']['name'];
                             $Retailer = ($transaction['ur']['shopname'] != '' ? $transaction['ur']['shopname'] : $transaction['r']['shopname']);
                             $RetailerMobile = $transaction['r']['mobile'];
                             $Note = "";
                             if($transaction['st']['type_flag'] == 1)
                                 $Note = 'Cash';
                             else if($transaction['st']['type_flag'] == 2)
                                 $Note = 'NEFT';
                             else if($transaction['st']['type_flag'] == 3)
                                 $Note = 'ATM Transfer';
                             else if($transaction['st']['type_flag'] == 4)
                                 $Note = 'Cheque';
                             else if($transaction['st']['type_flag'] == 5)
                                 $Note = 'Payment Gateway';

                                 $Note .= " - " . $transaction['st']['note'];

                             $Amount = $transaction['st']['amount'];
                             $Opening = $transaction['st']['opening'];
                             $Closing = $transaction['st']['closing'];
                             $Time = $transaction['sst']['created'];

                            $line = array($TransactionID,$Salesman,$Retailer,$RetailerMobile,$Note,$Amount,$Opening,$Closing,$Time);
                            $csv->addRow($line);
                            $i++;
                        }
                    }
            } else if($reports == 1) {
                    foreach($salesResult as $transaction){
                            $tran_type        = ($transaction['txn']['retailer_shopname'] != '') ? 'Retailer' : 'Salesman';
                            $tran_id          = $transaction['txn']['id'].(($transaction['txn']['retailer_shopname'] != '') ? " / " . $transaction['txn']['user_id'] : '');
                            $amt              = round($transaction['txn']['amount']);
                            $salesman         = $transaction['txn']['salesmen_name'];
                            $salesman_mobile  = $transaction['txn']['salesmen_mobile'];
                            $salesman_opening = round($transaction['txn']['salesman_opening'],2);
                            $salesman_closing = round($transaction['txn']['salesman_closing'],2);
                            $retailer         = ($transaction['txn']['ur_shopname'] != '' ? $transaction['txn']['ur_shopname'] : $transaction['txn']['retailer_shopname']);
                            $retailer_mobile  = $transaction['txn']['retailer_mobile'];
                            $retailer_opening = $transaction['txn']['retailer_opening'] != '' ? round($transaction['txn']['retailer_opening'],2) : '';
                            $retailer_closing = $transaction['txn']['retailer_closing'] != '' ? round($transaction['txn']['retailer_closing'],2) : '';
                            $flag             = $type_flag[$transaction['txn']['type_flag']] . (($transaction['txn']['note'] != '') ? " - " . $transaction['txn']['note'] : "");
                            $timestamp        = date('d-M-Y h:i:s A', strtotime($transaction['txn']['timestamp']));

                            $line = array($tran_type,$tran_id,$amt,$salesman,$salesman_mobile,$salesman_opening,$salesman_closing,$retailer,$retailer_mobile,$retailer_opening,$retailer_closing,$flag,$timestamp);
                            $csv->addRow($line);
                            $i++;
                    }
            } else if($reports == 2) {
                    foreach($salesResult as $transaction){
                            $tran_type        = 'Salesman-Retailer';
                            $tran_id          = $transaction['st']['id'];
                            $amt              = round($transaction['st']['amount']);
                            $salesman         = $transaction['salesmen']['name'];
                            $salesman_mobile  = $transaction['salesmen']['mobile'];
                            $salesman_opening = $transaction['st']['source_opening'];
                            $salesman_closing = $transaction['st']['source_closing'];
                            $retailer         = ($transaction['ur']['shopname'] != '' ? $transaction['ur']['shopname'] : $transaction['retailers']['shopname']);
                            $retailer_mobile  = $transaction['retailers']['mobile'];
                            $retailer_opening = $transaction['st']['target_opening'];
                            $retailer_closing = $transaction['st']['target_closing'];
                            $flag             = $type_flag[$transaction['st']['type_flag']] . " - " . $transaction['st']['note'];
                            $timestamp        = date('d-M-Y h:i:s A', strtotime($transaction['st']['timestamp']));

                            $line = array($tran_type,$tran_id,$amt,$salesman,$salesman_mobile,$salesman_opening,$salesman_closing,$retailer,$retailer_mobile,$retailer_opening,$retailer_closing,$flag,$timestamp);
                            $csv->addRow($line);
                            $i++;
                    }
            }
            $fileNamePre = "Transactions_";
            echo $csv->render($fileNamePre.$fd."_".$ft.".csv");
        }

        if(in_array($reports, array(1,2))) {
                $this->autoRender = FALSE;
                $this->render('salesman_report_new');
        }

	}

	function pullbackNew($params){

		if(!in_array($this->Session->read('Auth.User.group_id'), array(DISTRIBUTOR, SALESMAN))){
			$this->redirect(array('action' => 'index'));
		} else {
                        $this->info = $this->Session->read('Auth');

		$MsgTemplate = $this->General->LoadApiBalance();

			$shop_transid = isset($params['shop_transid']) ? $params['shop_transid'] : $_REQUEST['shop_transid'];

                        $shop_transaction = $this->User->query("SELECT * FROM shop_transactions WHERE id = " . $shop_transid);
                        if(!isset($params['transfer_type'])) {
                                if($shop_transaction[0]['shop_transactions']['type'] == DIST_SLMN_BALANCE_TRANSFER) {
                                        $transfer_type = SALESMAN;
                                } else if(empty($shop_transaction[0]['shop_transactions']['user_id'])) {
                                        $transfer_type = SLMN_RETL_BALANCE_TRANSFER;
                                } else {
                                        $transfer_type = RETAILER;
                                }
                        } else {
                                $transfer_type = isset($params['transfer_type']) ? $params['transfer_type'] : $_REQUEST['transfer_type'];
                        }

			$data = $this->Shop->getMemcache("pullback$shop_transid");
			if($data == null)
                                $this->Shop->setMemcache("pullback$shop_transid",1,2*60);
			else {
                                echo "Cannot be pulled back right now. Try again after some time"; exit;
                        }

                        if($transfer_type == SALESMAN) {

                                $success = false;
                                $msg = "";
                                if(!empty($shop_transaction)){
                                        $salesmanid     = $shop_transaction['0']['shop_transactions']['target_id'];
                                        $shopid         = $shop_transaction['0']['shop_transactions']['id'];
                                        $trans_date     = $shop_transaction['0']['shop_transactions']['date'];
                                        $confirm_flag   = $shop_transaction['0']['shop_transactions']['confirm_flag'];
                                        $amt            = $shop_transaction['0']['shop_transactions']['amount'];

                                        if($confirm_flag == 1){
                                                echo "Already pulled back your amount"; exit;
                                        }

                                        $shopResult = $this->Slaves->query("SELECT id,amount FROM shop_transactions WHERE source_id = ".$this->info['id']." AND target_id = $salesmanid AND type = ".DIST_SLMN_BALANCE_TRANSFER." ORDER BY id desc limit 1");

                                        if($shopResult['0']['shop_transactions']['id'] == $shopid || $confirm_flag > 1) {
                                                $salesResult=$this->Slaves->query("SELECT user_id, salesmen.mobile,users.balance, salesmen.name FROM salesmen inner join users ON (users.id = salesmen.user_id)"
                                                        . "WHERE salesmen.id = $salesmanid AND dist_id = " . $this->info['id']);

                                                if(!empty($salesResult)){

                                                            // $getSalesmenInfo = $this->Slaves->query("Select * from users where id  = '".$salesResult[0]['salesmen']['user_id']."'");

                                                               if($salesResult['0']['users']['balance'] >= $amt || $confirm_flag > 2) {
                                                                if($confirm_flag == 4 && $salesResult['0']['users']['balance'] < $amt) {
                                                                        $amt = $salesResult['0']['users']['balance'];
                                                                }
                                                                $success = true;

                                                                $bal_sal = $this->Shop->shopBalanceUpdate($amt,'subtract',$salesResult['0']['salesmen']['user_id'],SALESMAN);
                                                                $bal_dis = $this->Shop->shopBalanceUpdate($amt,'add',$this->info['user_id'],DISTRIBUTOR);



                                                                $trans_id = $this->Shop->shopTransactionUpdate(PULLBACK_SALESMAN,$amt,$salesmanid,$shopid,$this->info['user_id'],null,null,null,$bal_sal+$amt,$bal_sal,$bal_dis-$amt,$bal_dis);

                                                                //$this->Shop->addOpeningClosing($salesmanid,SALESMAN,$trans_id,$bal_sal+$amt,$bal_sal);
                                                                //$this->Shop->addOpeningClosing($this->info['id'],DISTRIBUTOR,$trans_id,$bal_dis-$amt,$bal_dis);

                                                                $this->User->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE id = $shopid");
//                                                                $this->User->query("UPDATE distributors_logs SET topup_sold = topup_sold - $amt WHERE distributor_id = " . $this->info['id']. " AND date = '$trans_date'");

                                                                $salesData = $this->Slaves->query("SELECT mobile, balance FROM users WHERE id = ".$salesResult['0']['users']['id']);

                                                                $paramdata['AMOUNT']    = $amt;
                                                                $paramdata['BALANCE']   = $salesResult['0']['users']['balance'] - $amt;

                                                                $content = $MsgTemplate['Pullback_FromDistributor_OfSalesman_MSG'];
                                                                $sal_msg = $this->General->ReplaceMultiWord($paramdata,$content);
                                                                $this->General->sendMessage($salesData['0']['users']['mobile'],$sal_msg,'notify');

                                                                if($bal_sal < 0) {
                                                                        $this->General->sendMails("Salesman balance is negative after pullback","Salesman: ".$salesData['0']['users']['mobile']."<br/>Balance: $bal_sal",array('tadka@pay1.in','limits@pay1.in'),'mail');
                                                                }
                                                                if(isset($params) && $params['request_from'] == "distributorApp") {
                                                                        if($authData['User']['group_id'] == SALESMAN){
                                                                                $balance = $salesData['0']['users']['balance'];
                                                                        }
                                                                        else
                                                                                $balance = $bal_dis;
                                                                        return array("status" => "success", "description" => "Done", "balance" => $balance);
                                                                }
                                                                else
                                                                        echo "success";
                                                        } else {
                                                                if(isset($params) && $params['request_from'] == "distributorApp"){
                                                                        return array("status" => "failure", "description" => "Retailer balance is less than $amt");
                                                                }
                                                                else{
                                                                        $msg = "Salesman balance is less than $amt";
                                                                        echo $msg;
                                                                }
                                                        }
                                                }
                                                else {
                                                        if(isset($params) && $params['request_from'] == "distributorApp"){
                                                                return array("status" => "failure", "description" => "Retailer does not exist");
                                                        }
                                                        else{
                                                                $msg = "Salesman does not exists";
                                                                echo $msg;
                                                        }
                                                }
                                        }
                                        else {
                                                if(isset($params) && $params['request_from'] == "distributorApp"){
                                                        return array("status" => "failure", "description" => "Only last topup can be pulled back");
                                                }
                                                else{
                                                        $msg = "Only last topup can be pulled back";
                                                        echo $msg;
                                                }
                                        }
                                } else {
                                        if(isset($params) && $params['request_from'] == "distributorApp"){
                                                return array("status" => "failure", "description" => "Only last topup can be pulled back");
                                        }
                                        else{
                                                $msg = "Transaction does not exist";
                                                echo $msg;
                                        }
                                }
                        } else if($transfer_type == RETAILER) {

                                $success = false;
                                $msg = "";
                                if(!empty($shop_transaction)){
                                        $retid          = $shop_transaction['0']['shop_transactions']['target_id'];
                                        $salesmanid     = $shop_transaction['0']['shop_transactions']['source_id'];
                                        $shopid         = $shop_transaction['0']['shop_transactions']['id'];
                                        $trans_date     = $shop_transaction['0']['shop_transactions']['date'];
                                        $confirm_flag   = $shop_transaction['0']['shop_transactions']['confirm_flag'];
                                        $amt            = $shop_transaction['0']['shop_transactions']['amount'];
                                        $usr_id         = $shop_transaction['0']['shop_transactions']['user_id'];

                                        if($confirm_flag == 1){
                                                echo "Already pulled back your amount"; exit;
                                        }

                                        $shopResult = $this->User->query("SELECT id,amount FROM shop_transactions WHERE source_id = ".$salesmanid." AND target_id = $retid AND type = ".SLMN_RETL_BALANCE_TRANSFER." ORDER BY id desc limit 1");

                                        if($shopResult['0']['shop_transactions']['id'] == $shopid || $confirm_flag > 1) {



                                                $retResult = $this->Slaves->query("SELECT users.id,users.balance,users.mobile FROM users Inner join retailers  ON (retailers.user_id = users.id)
                                                        WHERE retailers.id = $retid AND retailers.parent_id = ".$this->info['id']
                                                                                  );

                                                if(!empty($retResult)){
                                                        if($retResult['0']['users']['balance'] >= $amt || $confirm_flag > 2){
                                                                if($confirm_flag == 4 && $retResult['0']['users']['balance'] < $amt) {
                                                                        $amt = $retResult['0']['users']['balance'];
                                                                }
                                                                $success = true;

                                                                $bal_ret    = $this->Shop->shopBalanceUpdate($amt,'subtract',$retResult['0']['users']['id'],RETAILER);
                                                                $sal_info   = $this->Slaves->query("SELECT users.id,users.balance,users.mobile FROM users Inner join salesmen ON (users.id =salesmen.user_id) "
                                                                                . "WHERE salesmen.id = $salesmanid AND dist_id = " . $this->info['id']);
//                                                                if(!empty($sal_info)){
//                                                                    $getSalesmenInfo = $this->Slaves->query("Select * from users where id  = '".$sale_info[0]['salesmen']['user_id']."'");
//                                                                }
                                                                $bal_sal    = $sal_info[0]['users']['balance'];
                                                                $bal_dis    = $this->Shop->shopBalanceUpdate($amt,'add',$this->info['user_id'],DISTRIBUTOR);

                                                                $trans_id = $this->Shop->shopTransactionUpdate(PULLBACK_RETAILER,$amt,$retid,$shopid,$this->info['user_id'],null,null,null,$bal_ret+$amt,$bal_ret,$bal_sal,$bal_sal+$amt);
                                                                $trans_id1 = $this->Shop->shopTransactionUpdate(PULLBACK_SALESMAN,$amt,$salesmanid,$usr_id,$this->info['user_id'],null,null,null,$bal_sal+$amt,$bal_sal,$bal_dis-$amt,$bal_dis);

                                                               // $this->Shop->addOpeningClosing($retid,RETAILER,$trans_id,$bal_ret+$amt,$bal_ret);
                                                               // $this->Shop->addOpeningClosing($salesmanid,SALESMAN,$trans_id,$bal_sal,$bal_sal+$amt);
                                                             //   $this->Shop->addOpeningClosing($salesmanid,SALESMAN,$trans_id1,$bal_sal+$amt,$bal_sal);
                                                               // $this->Shop->addOpeningClosing($this->info['id'],DISTRIBUTOR,$trans_id1,$bal_dis-$amt,$bal_dis);

                                                                $this->User->query("UPDATE shop_transactions SET confirm_flag=1 WHERE id IN ('".$shopid."','".$usr_id."')");
                                                                $this->User->query("UPDATE users_logs SET topup_sold = topup_sold - $amt,topup_unique=topup_unique-1 WHERE user_id = " . $this->info['user_id']. " AND date = '$trans_date'");
                                                                $this->User->query("UPDATE users_logs SET topup_buy = topup_buy - $amt,primary_txn=primary_txn-1 WHERE user_id = ".$retResult['0']['users']['id']." AND date = '$trans_date'");

                                                                $paramdata['AMOUNT']    = $amt;
                                                                $paramdata['BALANCE']   = $retResult['0']['users']['balance'] - $amt;

                                                                $content =  $MsgTemplate['Pullback_Retailer_MSG'];
                                                                $ret_msg = $this->General->ReplaceMultiWord($paramdata,$content);
                                                                $this->General->sendMessage($retResult['0']['users']['mobile'],$ret_msg,'notify');

//                                                                $paramdata['AMOUNT']    = $amt;
//                                                                $paramdata['BALANCE']   = $retResult['0']['retailers']['balance'] - $amt;
//
//                                                                $content =  $MsgTemplate['Pullback_FromSalesman_OfRetailer_MSG'];
//                                                                $ret_msg = $this->General->ReplaceMultiWord($paramdata,$content);
//                                                                $this->General->sendMessage($retResult['0']['retailers']['mobile'],$ret_msg,'notify');
//
//                                                                $paramdata['AMOUNT']        = $amt;
//                                                                $paramdata['SHOP_NAME']     = $retResult['0']['retailers']['shopname'];
//                                                                $paramdata['MOBILE_NUMBER'] = $retResult['0']['retailers']['mobile'];
//
//                                                                $content =  $MsgTemplate['Pullback_Salesmen_MSG'];
//                                                                $saleman_msg = $this->General->ReplaceMultiWord($paramdata,$content);
//                                                                $this->General->sendMessage($sal_info['0']['salesmen']['mobile'],$saleman_msg,'notify', ($sal_info[0]['salesmen']['balance']),SALESMAN);
//
//                                                                $paramdata['AMOUNT']    = $amt;
//                                                                $paramdata['BALANCE']   = $sal_info['0']['salesmen']['balance'];
//
//                                                                $content =  $MsgTemplate['Pullback_FromDistributor_OfSalesman_MSG'];
//                                                                $sal_msg = $this->General->ReplaceMultiWord($paramdata,$content);
//                                                                $this->General->sendMessage($sal_info['0']['salesmen']['mobile'],$sal_msg,'notify');


                                                                if($bal_ret < 0){
                                                                        $this->General->sendMails("Retailer balance is negative after pullback", "Retailer: ".$retResult['0']['retailers']['mobile']."<br/>Balance: $bal_ret",array('tadka@pay1.in','limits@pay1.in'),'mail');
                                                                }
                                                                if(isset($params) && $params['request_from'] == "distributorApp"){
                                                                        if($authData['User']['group_id'] == SALESMAN){
                                                                                $balance = $sal_info['0']['users']['balance'];
                                                                        }
                                                                        else
                                                                                $balance = $bal_dis;
                                                                        return array("status" => "success", "description" => "Done", "balance" => $balance);
                                                                }
                                                                else
                                                                        echo "success";
                                                        }
                                                        else{
                                                                if(isset($params) && $params['request_from'] == "distributorApp"){
                                                                        return array("status" => "failure", "description" => "Retailer balance is less than $amt");
                                                                }
                                                                else{
                                                                        $msg = "Retailer balance is less than $amt";
                                                                        echo $msg;
                                                                }
                                                        }
                                                }
                                                else {
                                                        if(isset($params) && $params['request_from'] == "distributorApp"){
                                                                return array("status" => "failure", "description" => "Retailer does not exist");
                                                        }
                                                        else{
                                                                $msg = "Retailer does not exists";
                                                                echo $msg;
                                                        }
                                                }
                                        }
                                        else {
                                                if(isset($params) && $params['request_from'] == "distributorApp"){
                                                        return array("status" => "failure", "description" => "Only last topup can be pulled back");
                                                }
                                                else{
                                                        $msg = "Only last topup can be pulled back";
                                                        echo $msg;
                                                }
                                        }
                                }
                                else {
                                        if(isset($params) && $params['request_from'] == "distributorApp"){
                                                return array("status" => "failure", "description" => "Only last topup can be pulled back");
                                        }
                                        else {
                                                $msg = "Transaction does not exist";
                                                echo $msg;
                                        }
                                }
                        } else if($transfer_type == SLMN_RETL_BALANCE_TRANSFER) {

                                $success = false;
                                $msg = "";
                                if(!empty($shop_transaction)){
                                        $salesmanid     = $shop_transaction['0']['shop_transactions']['source_id'];
                                        $retailerid     = $shop_transaction['0']['shop_transactions']['target_id'];
                                        $shopid         = $shop_transaction['0']['shop_transactions']['id'];
                                        $trans_date     = $shop_transaction['0']['shop_transactions']['date'];
                                        $confirm_flag   = $shop_transaction['0']['shop_transactions']['confirm_flag'];
                                        $amt            = $shop_transaction['0']['shop_transactions']['amount'];

                                        if($confirm_flag == 1){
                                                echo "Already pulled back your amount"; exit;
                                        }

                                        $shopResult = $this->User->query("SELECT id,amount FROM shop_transactions WHERE source_id = $salesmanid AND target_id = $retailerid AND type = ".SLMN_RETL_BALANCE_TRANSFER." AND (user_id IS NULL OR user_id = 0) ORDER BY id DESC LIMIT 1");

                                        if($shopResult['0']['shop_transactions']['id'] == $shopid || $confirm_flag > 1) {
                                                $retailResult=$this->User->query("SELECT users.id,users.mobile,users.balance,shopname,parent_id FROM users inner join retailers ON (retailers.user_id = users.id) "
                                                        . "WHERE retailers.id = $retailerid AND retailers.maint_salesman = $salesmanid AND parent_id = " . $this->info['id']);

                                                if(!empty($retailResult)){
                                                        if($retailResult['0']['users']['balance'] >= $amt || $confirm_flag > 2) {
                                                                if($confirm_flag == 4 && $retailResult['0']['users']['balance'] < $amt) {
                                                                        $amt = $retailResult['0']['users']['balance'];
                                                                }
                                                                $success = true;

                                                                $sal_info   = $this->Slaves->query("SELECT users.id,users.balance,users.mobile FROM users inner join salesmen ON (salesmen.user_id=users.id) "
                                                                                . "WHERE salesmen.id = ".$salesmanid);
                                                                $bal_ret = $this->Shop->shopBalanceUpdate($amt,'subtract',$retailResult[0]['users']['id'],RETAILER);
                                                                $bal_sal = $this->Shop->shopBalanceUpdate($amt,'add',$sal_info[0]['users']['id'],SALESMAN);
                                                                $trans_id = $this->Shop->shopTransactionUpdate(PULLBACK_RETAILER,$amt,$retailerid,$shopid,$this->info['user_id'],null,null,null,$bal_ret+$amt,$bal_ret,$bal_sal-$amt,$bal_sal);

                                                                //$this->Shop->addOpeningClosing($retailerid,RETAILER,$trans_id,$bal_ret+$amt,$bal_ret);
                                                                //$this->Shop->addOpeningClosing($salesmanid,SALESMAN,$trans_id,$bal_sal-$amt,$bal_sal);

                                                                $this->User->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE id = $shopid");
                                                                $this->User->query("UPDATE users_logs SET topup_sold = topup_sold - $amt,topup_unique=topup_unique-1 WHERE user_id = " . $sal_info[0]['users']['id']. " AND date = '$trans_date'");
                                                                $this->User->query("UPDATE users_logs SET topup_buy = topup_buy - $amt,primary_txn=primary_txn-1 WHERE user_id = ".$retailResult[0]['users']['id']." AND date = '$trans_date'");

                                                                $retData = $this->Slaves->query("SELECT mobile, balance FROM users WHERE id = ".$retailResult[0]['users']['id']);

                                                                $paramdata['AMOUNT']    = $amt;
                                                                $paramdata['BALANCE']   = $retailResult['0']['users']['balance'] - $amt;

                                                                $content = $MsgTemplate['Pullback_FromSalesman_OfRetailer_MSG'];
                                                                $sal_msg = $this->General->ReplaceMultiWord($paramdata,$content);
                                                                $this->General->sendMessage($retailResult['0']['users']['mobile'],$sal_msg,'notify');

                                                                if($bal_sal < 0) {
                                                                        $this->General->sendMails("Retailer balance is negative after pullback","Retailer: ".$retailResult['0']['users']['mobile']."<br/>Balance: $bal_ret",array('tadka@pay1.in','limits@pay1.in'),'mail');
                                                                }
                                                                if(isset($params) && $params['request_from'] == "distributorApp") {
                                                                        if($authData['User']['group_id'] == SALESMAN){
                                                                                $balance = $retData['0']['users']['balance'];
                                                                        } else {
                                                                                $getDistInfo = $this->Slaves->query("Select users.id,users.balance,users.mobile from users Inner Join distributors ON (users.id = distributors.user_id) where distributors.id = '".$retailResult['0']['retailers']['parent_id']."'");

                                                                                $balance = $getDistInfo['0']['users']['balance'];
                                                                        }
                                                                        return array("status" => "success", "description" => "Done", "balance" => $balance);
                                                                }
                                                                else
                                                                        echo "success";
                                                        } else {
                                                                if(isset($params) && $params['request_from'] == "distributorApp"){
                                                                        return array("status" => "failure", "description" => "Retailer balance is less than $amt");
                                                                }
                                                                else{
                                                                        $msg = "Retailer balance is less than $amt";
                                                                        echo $msg;
                                                                }
                                                        }
                                                }
                                                else {
                                                        if(isset($params) && $params['request_from'] == "distributorApp"){
                                                                return array("status" => "failure", "description" => "Retailer does not exist");
                                                        }
                                                        else{
                                                                $msg = "Salesman does not exists";
                                                                echo $msg;
                                                        }
                                                }
                                        }
                                        else {
                                                if(isset($params) && $params['request_from'] == "distributorApp"){
                                                        return array("status" => "failure", "description" => "Only last topup can be pulled back");
                                                }
                                                else{
                                                        $msg = "Only last topup can be pulled back";
                                                        echo $msg;
                                                }
                                        }
                                } else {
                                        if(isset($params) && $params['request_from'] == "distributorApp"){
                                                return array("status" => "failure", "description" => "Only last topup can be pulled back");
                                        }
                                        else{
                                                $msg = "Transaction does not exist";
                                                echo $msg;
                                        }
                                }
                        }
		}

		if(!$success && empty($msg)){
			if(isset($params) && $params['request_from'] == "distributorApp"){
				return array("status" => "success", "description" => "Cannot be pulled back");
			}
			else
				echo "Cannot be pulled back";
		}
		$this->autoRender = false;
	}

	function pullback($params){
		if(!in_array($this->Session->read('Auth.User.group_id'), array(ADMIN, MASTER_DISTRIBUTOR, SUPER_DISTRIBUTOR, DISTRIBUTOR, SALESMAN)) && !in_array($params['request_from'], array('accounts_masterdistributor', 'accounts_distributor'))) {
			$this->redirect(array('action' => 'index'));
		}
		$MsgTemplate = $this->General->LoadApiBalance();

                if (in_array($params['request_from'], array('accounts_masterdistributor','accounts_distributor'))) {
                        $this->info = array();
                        $this->info['id']      = $params['user']['id'];
                        $this->info['user_id'] = $params['user']['user_id'];
                        $_REQUEST = $params;
                }

		if(in_array($this->Session->read('Auth.User.group_id'), array(DISTRIBUTOR, SALESMAN)) || $params['request_from'] == 'accounts_distributor') {
			if(isset($params) && $params['request_from'] == "distributorApp"){
				$authData = $this->Session->read('Auth');
				if($authData['User']['group_id'] == SALESMAN){
					$salesman = $authData;
					$authData = $this->Shop->getShopDataById($salesman['dist_id'], DISTRIBUTOR);
					$authData['User']['group_id'] = SALESMAN;
					$authData['User']['mobile'] = $salesman['mobile'];

					$this->info = $authData;
				}
				else
					$this->info = $this->Session->read('Auth');
                        }
			$salesman_trans_id = isset($params['salesman_transid']) ? $params['salesman_transid'] : $_REQUEST['salesman_transid'];
			$shop_transid = isset($params['shop_transid']) ? $params['shop_transid'] : $_REQUEST['shop_transid'];

			$data = $this->Shop->getMemcache("pullback$salesman_trans_id");
			if($data == null)$this->Shop->setMemcache("pullback$salesman_trans_id",1,2*60);
			else {
				if(isset($params) && in_array($params['request_from'], array('distributorApp','accounts_distributor'))){
					return array("status" => "failure", "description" => "Cannot be pulled back right now. Try again after some time");
				}
				else
					echo "What??Cannot be pulled back right now";exit;
			}

			$salesmanResult=$this->User->query("SELECT shop_transactions.date,shop_transactions.timestamp,shop_transactions.target_id,
					shop_transactions.confirm_flag,shop_transactions.amount,salesman_transactions.id,
					salesman_transactions.shop_tran_id,salesman_transactions.created,salesman_transactions.salesman,salesmen.mobile,salesmen.user_id
					FROM salesman_transactions,shop_transactions,salesmen
					WHERE salesmen.dist_id = ".$this->info['id']."
					AND salesman_transactions.salesman = salesmen.id
					AND payment_type = 2
					AND salesman_transactions.id = $salesman_trans_id
					AND salesman_transactions.shop_tran_id = shop_transactions.id
					AND shop_transactions.id = " . $shop_transid);

			$success = false;
			$msg = "";
			if(!empty($salesmanResult)){
				$retid = $salesmanResult['0']['shop_transactions']['target_id'];
				$salesmanid = $salesmanResult['0']['salesman_transactions']['salesman'];
				$shopid = $salesmanResult['0']['salesman_transactions']['shop_tran_id'];
				$trans_date = $salesmanResult['0']['shop_transactions']['date'];

				$confirm_flag = $salesmanResult['0']['shop_transactions']['confirm_flag'];
				$amt = $salesmanResult['0']['shop_transactions']['amount'];

				if($confirm_flag == 1){
					if(isset($params) && in_array($params['request_from'], array('distributorApp','accounts_distributor'))){
						return array("status" => "failure", "description" => "Already pulled back your amount");
					}
					else
						echo "Already pulled back your amount";exit;
				}
				$shopResult=$this->User->query("SELECT id,amount FROM shop_transactions WHERE source_id = ".$this->info['id']." AND target_id = $retid AND type = ".DIST_RETL_BALANCE_TRANSFER." ORDER BY id desc limit 1");

				if ($shopResult['0']['shop_transactions']['id'] == $shopid || $confirm_flag > 1){
					//$amt = $shopResult['0']['shop_transactions']['amount'];

					$retResult=$this->User->query("SELECT users.balance, retailers.mobile,retailers.user_id, retailers.shopname, ur.shopname
							FROM retailers
							left join unverified_retailers ur on ur.retailer_id = retailers.id
                                                        left join users ON (users.id  =retailers.user_id)
							WHERE retailers.id = $retid
							AND retailers.parent_id = " . $this->info['id']);



					if(!empty($retResult)){
						if($retResult['0']['users']['balance'] >= $amt || $confirm_flag > 2){
							if($confirm_flag == 4 && $retResult['0']['users']['balance'] < $amt){
								$amt = $retResult['0']['users']['balance'];
							}
							$success = true;

							$bal_ret = $this->Shop->shopBalanceUpdate($amt,'subtract',$retResult['0']['retailers']['user_id'],RETAILER);
							$bal_dis = $this->Shop->shopBalanceUpdate($amt,'add',$this->info['user_id'],DISTRIBUTOR);

                                                        if($salesmanResult[0]['salesmen']['mobile'] != $this->Session->read('Auth.User.mobile') && $params['request_from'] != 'accounts_distributor') {
                                                            $this->Shop->shopBalanceUpdate($amt,'add',$salesmanResult[0]['salesmen']['user_id'],SALESMAN);
                                                        }

                                                        /*  Added as changes for DB optimization  */
							$trans_id = $this->Shop->shopTransactionUpdate(PULLBACK_RETAILER,$amt,$retid,$shopid,$this->info['user_id'],null,null,null,$bal_ret+$amt,$bal_ret,$bal_dis-$amt,$bal_dis);
//							$this->Shop->addOpeningClosing($retid,RETAILER,$trans_id,$bal_ret+$amt,$bal_ret);
//							$this->Shop->addOpeningClosing($this->info['id'],DISTRIBUTOR,$trans_id,$bal_dis-$amt,$bal_dis);

							$this->User->query("UPDATE shop_transactions SET confirm_flag=1 WHERE id = $shopid");
							$this->User->query("UPDATE users_logs SET topup_sold = topup_sold - $amt,topup_unique=topup_unique-1 WHERE user_id = " . $this->info['user_id']. " AND date = '$trans_date'");
							$this->User->query("UPDATE users_logs SET topup_buy = topup_buy - $amt,primary_txn=primary_txn-1 WHERE user_id = ".$retResult['0']['retailers']['user_id']." AND date = '$trans_date'");

							//$this->User->query("DELETE FROM salesman_transactions WHERE id = $salesman_trans_id");

							//$this->User->query("INSERT INTO pullbacks (salesman_id,retailer_id,distributor_id,amount,topup_time,pullback_time) VALUES ($salesmanid,$retid,".$this->info['id'].",$amt,'".$salesmanResult['0']['shop_transactions']['timestamp']."','".date('Y-m-d H:i:s')."')");
							$salesData = $this->Slaves->query("SELECT mobile, balance FROM users WHERE id = $salesmanResult[0]['salesmen']['user_id']");

                                                        $paramdata['AMOUNT'] = $amt;
                                                        $paramdata['BALANCE'] = $retResult['0']['users']['balance'] - $amt;


                                                        $content =  $MsgTemplate['Pullback_Retailer_MSG'];
                                                        $ret_msg = $this->General->ReplaceMultiWord($paramdata,$content);
                                                        $this->General->sendMessage($retResult['0']['retailers']['mobile'],$ret_msg,'notify');

//							$this->General->sendMessage($retResult['0']['retailers']['mobile'],"Dear Retailer, Rs $amt is pulled back from your account by your distributor. Your balance is now Rs " . ($retResult['0']['retailers']['balance'] - $amt),'notify');

                                                        $paramdata['AMOUNT'] = $amt;


                                                        /** IMP DATA ADDED : START**/
                                                        $temp = $this->Shop->getUserLabelData($retResult['0']['retailers']['user_id'],2,0);
                                                        $imp_data = $temp[$retResult['0']['retailers']['user_id']];
                                                        /** IMP DATA ADDED : END**/

                                                        // $paramdata['SHOP_NAME'] = $retResult['0']['retailers']['shopname'];
                                                        $paramdata['SHOP_NAME'] = $imp_data['imp']['shop_est_name'];
                                                        $paramdata['MOBILE_NUMBER'] = $retResult['0']['retailers']['mobile'];

                                                        $content =  $MsgTemplate['Pullback_Salesmen_MSG'];
                                                        $saleman_msg = $this->General->ReplaceMultiWord($paramdata,$content);
                                                        $this->General->sendMessage($salesData['0']['users']['mobile'],$saleman_msg,'notify', ($salesData[0]['users']['balance']),SALESMAN);

//                                                      $this->General->sendMessage($salesData['0']['salesmen']['mobile'],"Dear Salesman, Rs $amt is pulled back from retailer ".$retResult['0']['retailers']['shopname']." (".$retResult['0']['retailers']['mobile'].")",'notify', ($salesData[0]['salesmen']['balance']),SALESMAN);


                                                        if($bal_ret < 0){
								$this->General->sendMails("Retailer balance is negative after pullback", "Retailer: ".$retResult['0']['retailers']['mobile']."<br/>Balance: $bal_ret",array('tadka@pay1.in','limits@pay1.in'),'mail');

							}
							if(isset($params) && in_array($params['request_from'], array('distributorApp','accounts_distributor'))) {
								if($authData['User']['group_id'] == SALESMAN){
									$balance = $salesData['0']['users']['balance'];
								}
								else
									$balance = $bal_dis;
                                                                return array("status" => "success", "description" => "Done", "balance" => $balance);
							}
							else
								echo "success";
						}
						else{
							if(isset($params) && in_array($params['request_from'], array('distributorApp','accounts_distributor'))){
								return array("status" => "failure", "description" => "Retailer balance is less than $amt");
							}
							else{
								$msg = "Retailer balance is less than $amt";
								echo $msg;
							}
						}
					}
					else {
						if(isset($params) && in_array($params['request_from'], array('distributorApp','accounts_distributor'))){
							return array("status" => "failure", "description" => "Retailer does not exist");
						}
						else{
							$msg = "Retailer does not exists";
							echo $msg;
						}
					}
				}
				else {
					if(isset($params) && in_array($params['request_from'], array('distributorApp','accounts_distributor'))){
						return array("status" => "failure", "description" => "Only last topup can be pulled back");
					}
					else{
						$msg = "Only last topup can be pulled back";
						echo $msg;
					}
				}
			}
			else {
				if(isset($params) && in_array($params['request_from'], array('distributorApp','accounts_distributor'))){
					return array("status" => "failure", "description" => "Only last topup can be pulled back");
				}
				else{
					$msg = "Only last topup can be pulled back";
					echo $msg;
				}
			}
		}
		else if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR || $params['request_from'] == 'accounts_masterdistributor' || $this->Session->read('Auth.User.group_id') == SUPER_DISTRIBUTOR) {
			$shop_trans_id = $_REQUEST['shop_transid'];
			$data = $this->Shop->getMemcache("pullback$shop_trans_id");
			if($data == null)$this->Shop->setMemcache("pullback$shop_trans_id",1,2*60);
			else {
                                if ($params['request_from'] == 'accounts_masterdistributor') {
                                        return array("status" => "success", "description" => "Cannot be pulled back right now");
                                } else {
                                        echo "Cannot be pulled back right now"; exit;
                                }
			}

			$shopResult = $this->User->query("SELECT shop_transactions.* FROM shop_transactions WHERE id = $shop_trans_id AND type IN ('".MDIST_DIST_BALANCE_TRANSFER."','".MDIST_SDIST_BALANCE_TRANSFER."','".SDIST_DIST_BALANCE_TRANSFER."')");

			if(!empty($shopResult)){
				$amt = $shopResult['0']['shop_transactions']['amount'];
				$targetid = $shopResult['0']['shop_transactions']['target_id'];
				$confirm_flag = $shopResult['0']['shop_transactions']['confirm_flag'];
				$trans_date = $shopResult['0']['shop_transactions']['date'];
                                $type = $shopResult['0']['shop_transactions']['type'] == MDIST_SDIST_BALANCE_TRANSFER ? 'superdistributor' : 'distributor';

				if($confirm_flag == 1){
                                        if ($params['request_from'] == 'accounts_masterdistributor') {
                                                return array("status" => "success", "description" => "Cannot be pulled back right now");
                                        } else {
                                                echo "Already pulled back your amount";exit;
                                        }
				}

				$shopResult1=$this->User->query("SELECT id,amount FROM shop_transactions WHERE source_id = ".$this->info['id']." AND target_id = $targetid AND type = ".$shopResult['0']['shop_transactions']['type']." ORDER BY id desc limit 1");
				if ($shopResult1['0']['shop_transactions']['id'] == $shop_trans_id || $confirm_flag > 1){
                                        $commission_type = ($type == 'distributor' ? COMMISSION_DISTRIBUTOR : COMMISSION_SUPERDISTRIBUTOR);
					$comm = $this->User->query("SELECT id,amount FROM shop_transactions WHERE target_id = $shop_trans_id AND  type = $commission_type");
					if(!empty($comm)){
						$amt += $comm['0']['shop_transactions']['amount'];
					}
                                        if ($type == 'distributor') {

                                        		if($shopResult['0']['shop_transactions']['type'] == SDIST_DIST_BALANCE_TRANSFER){
                                        			$column = "sd_id";
                                        		}else{
                                        			$column = "parent_id";
                                        		}

                                                $result=$this->User->query("SELECT users.id,users.balance,users.mobile,distributors.company,distributors.user_id FROM distributors,users WHERE distributors.user_id = users.id AND distributors.id = $targetid AND $column = " . $this->info['id']);
                                        } else {
                                                $result=$this->User->query("SELECT users.id,users.balance,users.mobile,super_distributors.id,super_distributors.user_id FROM super_distributors,users WHERE super_distributors.user_id = users.id AND super_distributors.id = $targetid");
                                        }

					if(!empty($result)){
						if($result['0']['users']['balance'] >= $amt || $confirm_flag > 2){
							if($confirm_flag == 4 && $result['0']['users']['balance'] < $amt){
								$amt = $result['0']['users']['balance'];
							}
							$success = true;
							$bal_dis = $this->Shop->shopBalanceUpdate($amt,'subtract',$result['0']['users']['id']);
							$bal_sdis = $this->Shop->shopBalanceUpdate($amt,'add',$this->info['user_id']);

                                                        $pullback_type = ($type == 'distributor' ? PULLBACK_DISTRIBUTOR : PULLBACK_SUPERDISTRIBUTOR);
							$trans_id = $this->Shop->shopTransactionUpdate($pullback_type,$amt,$targetid,$shop_trans_id,$this->info['user_id'],null,null,null,$bal_dis+$amt,$bal_dis,$bal_sdis-$amt,$bal_sdis);

							$this->User->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE id = $shop_trans_id");
							$this->User->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE target_id = $shop_trans_id AND type = $commission_type");

                                                        if ($type == 'distributor') {
                                                                $this->User->query("UPDATE users_logs SET topup_buy = topup_buy - $amt,primary_txn=primary_txn-1 WHERE user_id = ".$result['0']['distributors']['user_id']." AND date = '$trans_date'");
                                                        }
                                                        
                                                        $paramdata['USER'] = ucfirst($type);
                                                        $paramdata['AMOUNT'] = $amt;
                                                        $paramdata['BALANCE'] = $result['0']['users']['balance'] - $amt;
                                                        $content =  $MsgTemplate['Pullback_Distributor_MSG'];
                                                        $dist_msg = $this->General->ReplaceMultiWord($paramdata,$content);
                                                        $this->General->sendMessage($result['0']['users']['mobile'],$dist_msg,'notify',null,DISTRIBUTOR);

							if($bal_dis < 0 && $type == 'distributor'){
								$this->General->sendMails("Distributor balance is negative after pullback", "Distributor: ".$result['0']['distributors']['company']."<br/>Balance: $bal_dis",array('tadka@pay1.in','limits@pay1.in'),'mail');
							}
							if ($params['request_from'] == 'accounts_masterdistributor') {
                                                                return array("status" => "success", "description" => "success");
                                                        } else {
                                                                echo "success";
                                                        }
						}
						else{
							$msg = ucfirst($type)." balance is less than $amt";
							if ($params['request_from'] == 'accounts_masterdistributor') {
                                                                return array("status" => "success", "description" => $msg);
                                                        } else {
                                                                echo $msg;
                                                        }
						}
					}
				}
				else {
					$msg = "Only last topup can be pulled back";
                                        if ($params['request_from'] == 'accounts_masterdistributor') {
                                                return array("status" => "success", "description" => $msg);
                                        } else {
                                                echo $msg;
                                        }
				}
			}
		}
		else if($this->Session->read('Auth.User.group_id') == ADMIN){

			$shop_trans_id = $_REQUEST['shop_transid'];
			$data = $this->Shop->getMemcache("pullback$shop_trans_id");
			if($data == null)$this->Shop->setMemcache("pullback$shop_trans_id",1,2*60);
			else {
				echo "Cannot be pulled back right now";exit;
			}


			$shopResult=$this->User->query("SELECT shop_transactions.* FROM shop_transactions WHERE id = $shop_trans_id AND type = " .ADMIN_TRANSFER);

			if(!empty($shopResult)){
				$amt = $shopResult['0']['shop_transactions']['amount'];
				$sdistid = $shopResult['0']['shop_transactions']['target_id'];
				$confirm_flag = $shopResult['0']['shop_transactions']['confirm_flag'];

				if($confirm_flag == 1){
					echo "Already pulled back your amount";exit;
				}

				$shopResult1=$this->User->query("SELECT id,amount FROM shop_transactions WHERE target_id = $sdistid AND type = ".ADMIN_TRANSFER." ORDER BY id desc limit 1");
				if ($shopResult1['0']['shop_transactions']['id'] == $shop_trans_id || $confirm_flag > 1){
					$comm=$this->User->query("SELECT id,amount FROM shop_transactions WHERE target_id = $shop_trans_id AND type = ".COMMISSION_MASTERDISTRIBUTOR);
					if(!empty($comm)){
						$amt += $comm['0']['shop_transactions']['amount'];
					}
					$distResult=$this->User->query("SELECT users.id,users.balance,users.mobile,master_distributors.company FROM master_distributors,users WHERE master_distributors.user_id = users.id AND master_distributors.id = $sdistid");

					if(!empty($distResult)){
						if($distResult['0']['users']['balance'] >= $amt || $confirm_flag > 2){
							if($confirm_flag == 4 && $distResult['0']['users']['balance'] < $amt){
								$amt = $distResult['0']['users']['balance'];
							}
							$success = true;
							$bal_sdis = $this->Shop->shopBalanceUpdate($amt,'subtract',$distResult[0]['users']['id'],MASTER_DISTRIBUTOR);
                                                        /*  Added as changes for DB optimization  */
							$trans_id = $this->Shop->shopTransactionUpdate(PULLBACK_MASTERDISTRIBUTOR,$amt,$sdistid,$shop_trans_id,$this->Session->read('Auth.User.id'),null,null,null,$bal_sdis+$amt,$bal_sdis);
//							$this->Shop->addOpeningClosing($sdistid,MASTER_DISTRIBUTOR,$trans_id,$bal_sdis+$amt,$bal_sdis);

							$this->User->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE id = $shop_trans_id");
							$this->User->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE target_id = $shop_trans_id AND type = ".COMMISSION_MASTERDISTRIBUTOR);

                                                        $paramdata['USER'] = 'Sir';
                                                        $paramdata['AMOUNT'] = $amt;
                                                        $paramdata['BALANCE'] = $distResult['0']['users']['balance'] - $amt;
                                                        $content =  $MsgTemplate['Pullback_Distributor_MSG'];
                                                        $sup_dist_msg = $this->General->ReplaceMultiWord($paramdata,$content);
                                                        $this->General->sendMessage($distResult['0']['users']['mobile'],$sup_dist_msg,'shops');

//							$this->General->sendMessage($distResult['0']['users']['mobile'],"Dear Sir, Rs $amt is pulled back from your account. Your balance is now Rs " . ($distResult['0']['master_distributor']['balance'] - $amt),'shops');
							if($bal_sdis < 0){
								$this->General->sendMails("Master Distributor balance is negative after pullback", "Master Distributor: ".$distResult['0']['master_distributors']['company']."<br/>Balance: $bal_sdis",array('tadka@pay1.in','limits@pay1.in'),'mail');
							}
							echo "success";
						}
						else{
							$msg = "Master Distributor balance is less than $amt";
							echo $msg;
						}
					}
				}
				else {
					$msg = "Only last topup can be pulled back";
					echo $msg;
				}
			}
		}

		if(!$success && empty($msg)){
			if(isset($params) && in_array($params['request_from'], array('distributorApp','accounts_masterdistributor','accounts_distributor'))) {
				return array("status" => "success", "description" => "Cannot be pulled back");
			}
			else
				echo "Cannot be pulled back";
		}
		$this->autoRender = false;
	}

	function topupRequests(){//for distributor
		$data = $this->Slaves->query("SELECT topup_request.*,retailers.name,retailers.id FROM topup_request,retailers where retailers.user_id =  topup_request.user_id AND retailers.parent_id = " . $_SESSION['Auth']['id']);
		$this->set('data',$data);
	}

	/*function approve($id){
		$data = $this->Slaves->query("SELECT topup_request.*,retailers.name,retailers.mobile,retailers.id,retailers.shopname,retailers.kyc_flag FROM topup_request,retailers where retailers.user_id =  topup_request.user_id AND retailers.parent_id = " . $_SESSION['Auth']['id'] . " AND topup_request.id=$id");
		$bal = $this->Shop->getBalance($_SESSION['Auth']['id']);
		$to_save = true;
		if($data['0']['topup_request']['amount'] > $bal){
			$message = "Your amount cannot be greater than your account balance";
			$to_save = false;
		}
		else {
			if($data['0']['retailers']['kyc_flag'] == 0 && ($data['0']['topup_request']['amount'] + $bal) > KYC_AMOUNT){
				$message = "Please collect KYC of the retailer. Retailer balance cannot be greater than Rs." . KYC_AMOUNT;
				$to_save = false;
			}
		}
		if($to_save){
			$this->Shop->shopTransactionUpdate(DIST_RETL_BALANCE_TRANSFER,$data['0']['topup_request']['amount'],$_SESSION['Auth']['id'],$data['0']['retailers']['id']);
			$bal = $this->Shop->shopBalanceUpdate($data['0']['topup_request']['amount'],'subtract',$_SESSION['Auth']['id'],DISTRIBUTOR);
			$bal1 = $this->Shop->shopBalanceUpdate($data['0']['topup_request']['amount'],'add',$data['0']['retailers']['id'],RETAILER);
			$mail_subject = "Retailer Top-Up request approved";
			$mail_body = "Distributor: " . $_SESSION['Auth']['company']. " approved top-up of Rs. " . $data['0']['topup_request']['amount'] . " of retailer: " . $data['0']['retailers']['shopname'];

//			$msg = "Dear Retailer,\nYour account is successfully credited with Rs." . $data['0']['topup_request']['amount']. "\nYour current balance is Rs.$bal1";

                        $paramdata['TOPUP_AMOUNT'] = $data['0']['topup_request']['amount'];
                        $paramdata['BALANCE'] = $bal1;
                        $MsgTemplate = $this->General->LoadApiBalance();
                        $content =  $MsgTemplate['Reatiler_Approve_MSG'];
                        $msg = $this->General->ReplaceMultiWord($paramdata,$content);

                        $this->General->sendMessage($data['0']['retailers']['mobile'],$msg,'notify');
			//$this->General->sendMails($mail_subject, $mail_body);

			$this->Retailer->query("UPDATE topup_request SET approveStatus = 1 WHERE id = $id");
			echo "<script> reloadShopBalance(".$bal.");  </script>";
			echo "Approved";
		}
		else {
			echo $message;
		}
		$this->autoRender = false;
	}*/

	function transfer(){

                if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) {
                    $salesman    = $this->Slaves->query("SELECT id,name,mobile FROM salesmen WHERE dist_id = ".$_SESSION['Auth']['id']." AND mobile != ".$_SESSION['Auth']['User']['mobile']." ORDER BY name ASC");
                    $retailers   = $this->Slaves->query("SELECT retailers.id,retailers.shopname,retailers.mobile,ur.shopname FROM retailers LEFT JOIN unverified_retailers ur ON (retailers.id = ur.retailer_id) WHERE retailers.parent_id = ".$_SESSION['Auth']['id']." AND retailers.toshow = 1 ORDER BY ur.shopname ASC");

                    /** IMP DATA ADDED : START**/

                    $ret_ids = array_map(function($element){
                        return $element['retailers']['id'];
                    },$retailers);

                    $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
                    foreach ($retailers as $key => $retailer) {
                        $retailers[$key]['retailers']['shopname'] = $imp_data[$retailer['retailers']['id']]['imp']['shop_est_name'];
                        $retailers[$key]['ur']['shopname'] = $imp_data[$retailer['retailers']['id']]['imp']['shop_est_name'];
                    }
                    /** IMP DATA ADDED : END**/

                    $dist_detail = $this->Slaves->query("SELECT system_used FROM distributors WHERE id = ".$_SESSION['Auth']['id']);
                    $this->set('salesman', $salesman);
                    $this->set('retailers', $retailers);
                    $_SESSION['Auth']['system_used'] = $dist_detail[0]['distributors']['system_used'];
                }
		$this->render('transfer');
	}

	function amountTransfer($params=null,$authData=null){

		if(empty($authData)){
                    $authData = $this->Session->read('Auth');
                }
                $MsgTemplate = $this->General->LoadApiBalance();

		if(!in_array($authData['User']['group_id'],array(DISTRIBUTOR,SUPER_DISTRIBUTOR,MASTER_DISTRIBUTOR,ADMIN, SALESMAN))) {
			$this->redirect(array('action' => 'index'));
		}

                if(in_array($authData['User']['group_id'],array(DISTRIBUTOR,SALESMAN))) {
                        $par_id = isset($authData['dist_id']) ? $authData['dist_id'] : $authData['id'];
                        $su = $this->Slaves->query("SELECT system_used FROM distributors WHERE id = '".$par_id."'");
                        $sys_used = $su['0']['distributors']['system_used'];
                }

                if($sys_used == 1) {
                        if(isset($params['app_flag'])) {
                                return array("status" => "failure", "description" => "Invalid Transfer. Kindly login again");
                        } else {
                                echo 'Invalid transfer. Kindly login again';
                        }
			exit;
                }

                $app_flag = 0;      // 0 for panel, 1 for sms transfer & 2 for auto limit transfer, 3 for app api
		$name = "";
		$confirm = 0;
		if(isset($this->data['confirm']))
                        $confirm = $this->data['confirm'];

		if($confirm == 1){

			$this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","request when confirm flag is 1*: data=> ".json_encode($this->data)."<br/>".date('Y-m-d H:i:s'));
		}
                
		if($params != null){
			$this->data['confirm'] = 1;
			$confirm = 1;
			$this->data['amount'] = $params['amount'];
			$this->data['shop'] = $params['retailer'];
			$this->info = $authData;
			if(isset($params['description'])){
				$this->data['description'] = $params['description'];
				$this->data['typeRadio'] = 1;
			}
                        if(isset($params['app_flag']) && ($params['app_flag'] == 2)){
                                $app_flag = 2;
                                $this->data['bank_name'] = $params['bankName'];
                                $this->data['typeRadio'] = isset($params['typeRadio']) ? $params['typeRadio'] : 1;
                                $this->data['description'] = $params['txnId'];
                                if(isset($params['type_id'])){
                                        $this->data['type_id'] = $params['type_id'];
                                }
                                if(isset($params['margin'])){
                                        $this->data['commission'] = $params['margin'];
                                }
                        } else if(isset($params['app_flag']) && ($params['app_flag'] == 3)){
                                $app_flag = 3;
                                if($authData['User']['group_id'] == SALESMAN){
                                        $salesman = $authData;
                                        $authData = $this->Shop->getShopDataById($salesman['dist_id'], DISTRIBUTOR);
                                        $authData['User']['group_id'] = SALESMAN;
                                        $authData['User']['mobile'] = $salesman['mobile'];
                                        $this->info = $authData;
                                }
                        } else {
                                $app_flag = 1;
                                $this->data['typeRadio'] = 1;
                        }
		} else {
			if($confirm == 0 && $authData['User']['group_id'] != trim($this->data['group'])) {
				echo 'Invalid transfer. Kindly login again';
                                exit;
			}

			if($app_flag == 0) {
				if($confirm == 1 && $authData['User']['id'] == 1) {
                                        $getlimitPassword = $this->General->findVar('limit_password');
                                        $password = isset($_REQUEST['password']) ? md5($_REQUEST['password']) : "" ;
                                        //$pass=hash('sha256',$_REQUEST['password']);

                                        if($getlimitPassword!=$password) {
                                                echo "Please enter valid password or contact your system admin"; exit;
                                        }

					if(isset($this->data['bank_name']) && empty($this->data['bank_name'])){
                                                echo "Please Select Bank Name"; exit;
                                        }
					if(isset($this->data['description']) && empty($this->data['description'])){
                                                echo "Please Enter Bank Txn Id"; exit;
                                        }
                                } else if($confirm == 1 && $authData['User']['group_id'] == ADMIN) {
                                        $getlimitPassword = $this->General->findVar('limit_adminpassword');
                                        $password = isset($_REQUEST['password']) ? md5( $_REQUEST['password']) : "" ;
                                        if($getlimitPassword != $password) {
                                                echo "Please enter valid password or contact your system admin"; exit;
                                        }

                                        if(isset($this->data['bank_name']) && empty($this->data['bank_name'])){
                                                echo "Please Select Bank Name"; exit;
                                        }
                                        if(isset($this->data['description']) && empty($this->data['description'])){
                                                echo "Please Enter Bank Txn Id"; exit;
                                        }
                                } else if($confirm == 1 && $authData['User']['group_id'] == DISTRIBUTOR && in_array($authData['id'],explode(",",DISTS))) {
                                        if(isset($this->data['bank_name']) && empty($this->data['bank_name'])){
                                                echo "Please Select Bank Name"; exit;
                                        }
                                        if(isset($this->data['description']) && empty($this->data['description'])){
                                                echo "Please Enter Bank Txn Id"; exit;
                                        }
                                }
                        }
		}

                if ($app_flag == 2 && isset($params['passwd'])) {
                        $getlimitPassword = $this->General->findVar('limit_password');
                        $password = md5($params['passwd']);
                        if($getlimitPassword != $password) {
                                return array('status'=>'failure', 'description'=>'Please enter valid password or contact your system admin');
                        }
                }

		$to_save = true;
		$this->data['amount'] = trim($this->data['amount']);
                if($authData['User']['group_id'] == MASTER_DISTRIBUTOR && $this->data['type_id'] == DISTRIBUTOR) {
                    $shop_temp = explode('_',$this->data['shop']);
                    $total_transferred = $this->Slaves->query("SELECT sum(amount) ta FROM shop_transactions WHERE type = ".MDIST_DIST_BALANCE_TRANSFER." AND target_id = '".$shop_temp[0]."' AND type_flag = 1 AND confirm_flag != 1 AND date = '".date('Y-m-d')."'");
                    $limit = $this->Slaves->query("SELECT max_limit from distributors WHERE id = '".$shop_temp[0]."'");
                }

		if($this->data['shop'] == 0) {
			if($authData['User']['group_id'] == ADMIN)
                                $msg = "Please select Master distributor";
			else if($authData['User']['group_id'] == MASTER_DISTRIBUTOR)
                                if ($this->data['type_id'] == DISTRIBUTOR) {
                                        $msg = "Please Select Distributor";
                                } else {
                                        $msg = "Please Select Superdistributor";
                                }
			else if($authData['User']['group_id'] == DISTRIBUTOR) {
				if($app_flag == 1) {
					$msg = "Invalid SMS format.\nCorrect format: PAY1 TB mobile amount";
				} else if($app_flag == 0) {
					$msg = "Please select retailer";
				}
			}

			$to_save = false;
		} else if(empty($this->data['amount'])) {
			if($app_flag == 1) {
				$msg = "Invalid SMS format.\nCorrect format: PAY1 TB mobile amount";
			} else if($app_flag == 0)
                                $msg = "Please enter some amount";
			$to_save = false;
		} else if($this->data['amount'] > 500000 && !($authData['User']['group_id'] == ADMIN && $this->data['shop'] == 3) && $app_flag!=2) // removed restriction of 500000 limit for admin and auto limit transfer
		{
			$msg = "Amount cannot be greater than 500000";
			$to_save = false;
		}
//		else if(!($authData['User']['group_id'] == MASTER_DISTRIBUTOR && in_array($this->data['shop'], array(1513,487,1714,840,1569,512))) &&  $this->data['amount'] > 1000000 && !($authData['User']['group_id'] == ADMIN && $this->data['shop'] == 3) && $app_flag==2) // removed restriction of 1000000 limit for admin and increased the transfer limit for autop transfer
		else if($authData['User']['group_id'] != MASTER_DISTRIBUTOR &&  $this->data['amount'] > 1000000 && !($authData['User']['group_id'] == ADMIN && $this->data['shop'] == 3) && $app_flag==2) // removed restriction of 1000000 limit for admin and increased the transfer limit for autop transfer
		{
			$msg = "Amount cannot be greater than 1000000";
			$to_save = false;
		}
//		else if($authData['User']['group_id'] == MASTER_DISTRIBUTOR && in_array($this->data['shop'], array(1513,487,1714,840,1569,512)) &&  $this->data['amount'] > 2000000 && $app_flag == 2) 
		else if($authData['User']['group_id'] == MASTER_DISTRIBUTOR && $this->data['type_id'] == DISTRIBUTOR && $this->data['amount'] > $limit[0]['distributors']['max_limit'] && $app_flag == 2) 
		{
			$msg = "Amount cannot be greater than " . $limit[0]['distributors']['max_limit'];
			$to_save = false;
		} else if($this->data['amount'] <= 0 || !preg_match('/^\d+$/',$this->data['amount'])){
			if($app_flag == 1){
				$msg = "Invalid SMS format.\nCorrect format: PAY1 TB mobile amount";
			}
			else if($app_flag == 0)
                                $msg = "Amount entered is not valid";
			$to_save = false;
		} else if(($this->data['commission'] != 1 || !is_numeric($this->data['commission_per'])) && $this->data['confirm'] != 1 && $authData['User']['group_id'] != DISTRIBUTOR && $authData['User']['group_id'] != SUPER_DISTRIBUTOR) {
			$msg = "Commission entered is not valid";
			$to_save = false;
                } else if(!isset($this->data['typeRadio'])) {
			$msg = "Select Transfer Type";
			$to_save = false;
		} else if($authData['User']['group_id'] == MASTER_DISTRIBUTOR && $this->data['type_id'] == DISTRIBUTOR && $this->data['typeRadio'] != 1 && ($total_transferred[0][0]['ta'] + $this->data['amount']) > $limit[0]['distributors']['max_limit']) {
			$msg = "You cannot transfer more than ".$limit[0]['distributors']['max_limit']." in Cash in a day";
			$to_save = false;
		} else {

                        if(isset($this->data['commission_per'])){
                            $this->data['commission'] = ($this->data['amount'] * $this->data['commission_per']/100);
                        }

			if($authData['User']['group_id'] != ADMIN){
				$bal = $this->Shop->getBalance($authData['User']['id']);
			}
			$amt_bal = $this->data['amount'];

			if($authData['User']['group_id'] == ADMIN || $authData['User']['group_id'] == MASTER_DISTRIBUTOR)
                                $amt_bal = $amt_bal + $this->data['commission'];

			if($authData['User']['group_id'] != ADMIN  && $amt_bal > $bal) {
				if($app_flag == 1) {
					$msg = "Contact your distributor, he doesn't have enough balance";
				} else if($app_flag == 0)
                                        $msg = "Your amount cannot be greater than your account balance";
				else if($app_flag == 3)
                                        return array("status" => "failure", "description" => "Cannot transfer due to insufficient balance");
				else
                                        $msg = "Cannot transfer due to insufficient balance";

				$to_save = false;
			} else {
				if($authData['User']['group_id'] != ADMIN) {
					if($app_flag == 3 && $authData['User']['group_id'] == SALESMAN) {
						$shop = $this->Shop->getShopDataById($this->data['shop'], 6);
						if($shop['parent_id'] != $this->info['id']) {
							return array("status" => "failure", "description" => "Invalid transfer");
						}
					} else if ($authData['User']['group_id'] == SUPER_DISTRIBUTOR && !isset($this->data['type_id'])) {
						$shop = $this->Shop->getShopDataById($this->data['shop'], DISTRIBUTOR);
						if($shop['sd_id'] != $this->info['id']) {
							echo "Invalid transfer";
                                                        exit;
                                                }
                                        } else if (!isset($this->data['type_id']) || $this->data['type_id'] == DISTRIBUTOR) {
						$shop = $this->Shop->getShopDataById($this->data['shop'],$authData['User']['group_id'] + 1);
						if($shop['parent_id'] != $this->info['id']) {
							echo "Invalid transfer";
                                                        exit;
                                                }
                                        } else {
						$shop = $this->Shop->getShopDataById($this->data['shop'], SUPER_DISTRIBUTOR);
                                        }
				} else {
					$shop = $this->Shop->getShopDataById($this->data['shop'],MASTER_DISTRIBUTOR);
					if(isset($shop['parent_id'])) {
						echo "Invalid transfer";
                                                exit;
					}
				}

                                $getUserdata = $this->General->getUserDataFromId($shop['user_id']);
                                if(in_array($authData['User']['group_id'],array(DISTRIBUTOR,SALESMAN)) && $shop['kyc_flag'] == 0 && ($this->data['amount'] + $getUserdata['balance']) > KYC_AMOUNT){
                                    $msg = "Please collect KYC of the retailer. Retailer balance cannot be greater than Rs." . KYC_AMOUNT;
                                    $to_save = false;

                                }
                                else if($shop['user_id'] == $authData['User']['id']){
                                    $msg = "You cannot transfer balance to yourself";
                                    $to_save = false;

                                }
                                else if(in_array($authData['User']['group_id'],array(DISTRIBUTOR,SALESMAN)) && $shop['kyc_flag'] == 1 && ($this->data['amount'] + $getUserdata['balance']) > KYC_AMOUNT_MAX){
                                    $msg = "Retailer cannot maintain more than ".KYC_AMOUNT_MAX;
                                    $to_save = false;

                                }
				else if(isset($shop['active_flag']) && $shop['active_flag'] == 0) {
					$msg = "You can not transfer to a closed distributor.";
					$to_save = false;
				} else {
					$this->data['shopData'] = $shop;
					if($confirm == 0) {
						if($authData['User']['group_id'] != ADMIN)
                                                        $this->set('balance',$bal - $amt_bal);
						$this->set('data',$this->data);
						$this->render('confirm_transfer','ajax');
					} else {
						if($app_flag!=2){
						$check_wait_time = $this->Shop->addMemcache("requested_distributor_".$authData['id'],$authData['id'] , 1);

                                                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","add in memcache*: data=> ".json_encode($this->data)."<br/>".date('Y-m-d H:i:s'));

                                                if(!$check_wait_time){

//                                                    return array('status'=>'failure','code'=>'37','description'=>$this->errors(37));
                                                }

                                               }


						$mail_subject = "Retail Panel: Amount Transferred";
						$bal1 = 0;
						if(isset($this->data['bank_name']) && !empty($this->data['bank_name'])) {
                                                        $ret = $this->Shop->lockBankTransaction($this->data['bank_name'],$this->data['description']);
                                                        if($ret == false) {
                                                                $msg = "Transfer for ".$this->data['description']." is already done.";
                                                                $to_save = false;
                                                        }
						}

                                                $this->General->logData('axis_bank_integration.txt', json_encode($params));
						if ($this->Session->read('Auth.User.group_id') != LIMIT && $params['axis_exception'] != 1) {
                                                        $det = $this->Shop->getMemcache("txn_" . $getUserdata['mobile']);
                                                        if ($det === false) {
                                                                $this->Shop->setMemcache("txn_" . $getUserdata['mobile'],1,15);
                                                        } else {
                                                                $msg = "You cannot transfer limit to same person within 15 seconds !!!";
                                                                $to_save = false;
                                                        }
                                                }

                                                $dataSource = $this->User->getDataSource();

                                                try {
                                                        $dataSource->begin($this->User);

                                                        if($to_save && $authData['User']['group_id'] == ADMIN){
                                                                /*  Added as changes for DB optimization  */
                                                                $bal1 = $this->Shop->shopBalanceUpdate($this->data['amount']+$this->data['commission'],'add',$shop['user_id'],MASTER_DISTRIBUTOR,$dataSource);
                                                                $trans_id = $this->Shop->shopTransactionUpdate(ADMIN_TRANSFER,$this->data['amount'],0,$shop['id'],$authData['User']['id'],null,null,null,null,null,$bal1-($this->data['amount']+$this->data['commission']),$bal1,$dataSource);
        //							$this->Shop->addOpeningClosing($shop['id'],MASTER_DISTRIBUTOR,$trans_id,$bal1-($this->data['amount']+$this->data['commission']),$bal1);

                                                                if(!empty($this->data['commission'])){
                                                                        $this->Shop->shopTransactionUpdate(COMMISSION_MASTERDISTRIBUTOR,$this->data['commission'],$shop['id'],$trans_id, $authData['User']['id'],null,null,null,0,0,0,0,$dataSource);
                                                                }

                                                                if(isset($this->data['typeRadio'])){
                                                                        $dataSource->query("UPDATE shop_transactions SET type_flag = ".$this->data['typeRadio'].",note = '".addslashes($this->data['bank_name'].":".$this->data['description'])."' where id = $trans_id");
                                                                }

                                                                //$this->Shop->addOpeningClosing($shop['id'],MASTER_DISTRIBUTOR,$trans_id,$bal1-($this->data['amount']+$this->data['commission']),$bal1);

                                                                $mail_body = "Admin: " . $this->info['company'] . " transferred Rs. " . ($this->data['amount'] + $this->data['commission']) . " to MasterDistributor: " . $shop['company'];
                                                                $name = "MasterDistributor";
                                                                $dist_data = $this->General->getUserDataFromId($shop['user_id']);
                                                                $shop['mobile'] = $dist_data['mobile'];

                                                                /*$query = "SELECT AVG(earning) as earning from distributors_logs where distributor_id = ".$shop['id']." AND date >='".date('Y-m-d',strtotime('-7 days'))."' and date<='".date('Y-m-d')."'";

                                                                $avgDistSale = $this->Slaves->query($query);*/

                                                                $sms = "Dear $name,\nYour account is successfully credited with Rs." . ($this->data['amount'] + $this->data['commission']) . "\nYour current balance is Rs.$bal1";
                                                                if($shop['margin'] == '0.0'){
                                                                    $sms .= "\nYour expected earning for today is Rs. ".intval($avgDistSale[0][0]['earning']);
                                                                }

                                                                if(!empty($this->data['description'])){
                                                                        $mail_body .= "<br/>".$this->data['description'];
                                                                }

                                                                if(!in_array($shop['id'],explode(",",MDISTS))){
                                                                        $this->General->sendMails($mail_subject, $mail_body,array('limits@pay1.in'));
                                                                        $data1 = array();
                                                                        $data1['sender'] =  "TFR";
                                                                        $data1['process'] =  "limits";
                                                                        $data1['type'] = "SD";
                                                                        $data1['name'] = $shop['company'];
                                                                        $data1['mobile'] = $shop['mobile'];
                                                                        $data1['amount'] = $this->data['amount'];
                                                                        $data1['commission'] = $this->data['commission'];
                                                                        $data1['commission_per'] = $this->data['commission_per'];
                                                                        $data1['transid'] = $trans_id;
                                                                        $this->General->curl_post($this->General->findVar('limit_url'),$data1);
                                                                }
                                                                $msg = "Transaction is Completed Successfully And Transaction Id is $trans_id";
                                                                $shopId = $trans_id;
                                                        } else if($to_save && $authData['User']['group_id'] == MASTER_DISTRIBUTOR && ($this->data['type_id'] == DISTRIBUTOR || !isset($this->data['type_id']))) {
                                                                /*  Added as changes for DB optimization  */ 
                                                                $bal = $this->Shop->shopBalanceUpdate($this->data['amount']+$this->data['commission'],'subtract',$this->info['user_id'],MASTER_DISTRIBUTOR,$dataSource);
                                                                $bal1 = $this->Shop->shopBalanceUpdate($this->data['amount']+$this->data['commission'],'add',$shop['user_id'],DISTRIBUTOR,$dataSource);
                                                                $trans_id = $this->Shop->shopTransactionUpdate(MDIST_DIST_BALANCE_TRANSFER,$this->data['amount'],$this->info['id'],$shop['id'],$authData['User']['id'],null,null,null,$bal+$this->data['amount']+$this->data['commission'],$bal,$bal1-($this->data['amount']+$this->data['commission']),$bal1,$dataSource);
        //							$this->Shop->addOpeningClosing($this->info['id'],MASTER_DISTRIBUTOR,$trans_id,$bal+$this->data['amount']+$this->data['commission'],$bal);
        //							$this->Shop->addOpeningClosing($shop['id'],DISTRIBUTOR,$trans_id,$bal1-($this->data['amount']+$this->data['commission']),$bal1);

                                                                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step 1*: transId=> $trans_id<br/>".date('Y-m-d H:i:s'));
                                                                if($this->data['shopData']['margin'] != 0 && $this->data['shopData']['commission_type'] == 0) {

        //								if($this->data['bank_name'] == 'ICICI6714' &&  ($this->data['typeRadio'] == 1 || $this->data['typeRadio'] == 3)){
        //									$this->data['commission'] = round($this->data['commission']-$this->data['amount']*0.0005,2);
        //								}

                                                                        $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR,$this->data['commission'],$shop['id'],$trans_id,null,null,null,null,0,0,0,0,$dataSource);
                                                                        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step 2 update commision*: transId=> $trans_id<br/>".date('Y-m-d H:i:s'));
                                                                }

                                                                if($app_flag == 2){
                                                                        $this->data['typeRadio'] = 1;
                                                                }
                                                                if(isset($this->data['typeRadio'])){
                                                                        $dataSource->query("UPDATE shop_transactions SET type_flag = ".$this->data['typeRadio'].",note = '".addslashes($this->data['bank_name'].":".$this->data['description'])."' where id = $trans_id");
                                                                        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step 3 update shop_transactions*: transId=> $trans_id<br/>".date('Y-m-d H:i:s'));
                                                                }
                                                                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step 4 update and insert*: transId=> $trans_id<br/>".date('Y-m-d H:i:s'));

                                                                $mail_body = "MasterDistributor: " . $this->info['company'] . " transferred Rs. " . ($this->data['amount'] + $this->data['commission']) . " to Distributor: " . $shop['company'];
                                                                $name = "Distributor";
                                                                $dist_data = $this->General->getUserDataFromId($shop['user_id']);
                                                                $shop['mobile'] = $dist_data['mobile'];
                                                                $sms = "Dear $name,\nYour account is successfully credited with Rs." . ($this->data['amount'] + $this->data['commission']) . "\nYour current balance is Rs.$bal1";

                                                                if(!empty($this->data['description'])){
                                                                        $mail_body .= "<br/>".$this->data['description'].$this->data['bank_name'];
                                                                }

                                                                if(in_array($this->info['id'],explode(",",MDISTS)) && !in_array($shop['id'],explode(",",DISTS))){

                                                                        $slab_det = $this->Slaves->query("select commission_dist , name from slabs where id = ".$shop['slab_id']."\n");
                                                                        if ( $this->data['commission'] * 100 / $this->data['amount']  > $slab_det[0]['slabs']['commission_dist'] ){

                                                                                if($shop['margin'] == round($this->data['commission'] * 100/$this->data['amount'],2) && $shop['margin_approved'] == 1){

                                                                                } else {
                                                                                        $this->General->sendMails("Wrong Commission Transfered to distributor","
                                                                                        Company : ".$shop['company']."</br>
                                                                                        Mobile : ".$shop['mobile']."</br>
                                                                                        Amount : ".$this->data['amount']."</br>
                                                                                        Commission : ".$this->data['commission']."(".round($this->data['commission'] * 100 / $this->data['amount'],2)."%)"."</br>
                                                                                        Slab Name : ".$slab_det[0]['slabs']['name']."</br>
                                                                                        " ,array('finance@pay1.in','limits@pay1.in'), 'mail');
                                                                                }

                                                                        }
                                                                        if($bal1 >= 500000){// if current bal after transfer then raise a alarm

                                                                                $mail_subject = "Current balance of Distributor is greater than 500000";
                                                                                $mail_body = "Current balance of ".$shop['company']." is $bal1 </br>
                                                                                        Mobile : ".$shop['mobile']."</br>
                                                                                        Amount Transferred : ".$this->data['amount']."</br>
                                                                                        ";
                                                                                $this->General->sendMails($mail_subject, $mail_body , array("tadka@pay1.in",'limits@pay1.in'),'mail');
                                                                        }


                                                                        $this->General->sendMails($mail_subject, $mail_body,array('limits@pay1.in'));

                                                                        $data1 = array();
                                                                        $data1['sender'] =  "TFR";
                                                                        $data1['process'] =  "limits";
                                                                        $data1['type'] = "D";
                                                                        $data1['name'] = $shop['company'];
                                                                        $data1['mobile'] = $shop['mobile'];
                                                                        $data1['amount'] = $this->data['amount'];
                                                                        $data1['commission'] = $this->data['commission'];
                                                                        $data1['commission_per'] = $this->data['commission_per'];
                                                                        $data1['transid'] = $trans_id;
                                                                        $this->General->curl_post($this->General->findVar('limit_url'),$data1);
                                                                        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step 5 after curl operation: transId=> $trans_id<br/>".date('Y-m-d H:i:s'));

                                                                }
        //							$msg = "Transaction is Completed Successfully And Transaction Id is $trans_id";
        //							$succmsg = "Transfer to ".$shop['company']."Completed Successfully!!!";

                                                                $paramdata['RECID'] = $trans_id;
                                                                $content=  $MsgTemplate['AmountTransfer_TransactionComplete_MSG'];
                                                                $msg = $this->General->ReplaceMultiWord($paramdata,$content);

                                                                $paramdata['SHOP_NAME'] = $shop['company'];
                                                                $content=  $MsgTemplate['AmountTransfer_TransferComplete_MSG'];
                                                                $succmsg = $this->General->ReplaceMultiWord($paramdata,$content);


                                                                $shopId = $trans_id;
                                                        } else if($to_save && $authData['User']['group_id'] == SUPER_DISTRIBUTOR) {

                                                                $bal = $this->Shop->shopBalanceUpdate($this->data['amount']+$this->data['commission'],'subtract',$this->info['user_id'],SUPER_DISTRIBUTOR,$dataSource);
                                                                $bal1 = $this->Shop->shopBalanceUpdate($this->data['amount']+$this->data['commission'],'add',$shop['user_id'],DISTRIBUTOR,$dataSource);
                                                                $trans_id = $this->Shop->shopTransactionUpdate(SDIST_DIST_BALANCE_TRANSFER,$this->data['amount'],$this->info['id'],$shop['id'],$authData['User']['id'],null,null,null,$bal+$this->data['amount']+$this->data['commission'],$bal,$bal1-($this->data['amount']+$this->data['commission']),$bal1,$dataSource);

                                                                if($this->data['shopData']['margin'] != 0 && $this->data['shopData']['commission_type'] == 0) {
                                                                        $this->Shop->shopTransactionUpdate(COMMISSION_DISTRIBUTOR,$this->data['commission'],$shop['id'],$trans_id,null,null,null,null,0,0,0,0,$dataSource);
                                                                }

                                                                if($app_flag == 2){
                                                                        $this->data['typeRadio'] = 1;
                                                                }
                                                                if(isset($this->data['typeRadio'])){
                                                                        $dataSource->query("UPDATE shop_transactions SET type_flag = ".$this->data['typeRadio'].",note = '".addslashes($this->data['bank_name'].":".$this->data['description'])."' where id = $trans_id");
                                                                }

                                                                $mail_body = "SuperDistributor : " . $this->info['name'] . " transferred Rs. " . ($this->data['amount'] + $this->data['commission']) . " to Distributor: " . $shop['company'];
                                                                $sms = "Dear Distributor,\nYour account is successfully credited with Rs." . ($this->data['amount'] + $this->data['commission']) . "\nYour current balance is Rs.$bal1";

                                                                if(!empty($this->data['description'])){
                                                                        $mail_body .= "<br/>".$this->data['description'].$this->data['bank_name'];
                                                                }

                                                                if(!in_array($shop['id'],explode(",",DISTS))){

                                                                        $slab_det = $this->Slaves->query("select commission_dist, name from slabs where id = ".$shop['slab_id']."\n");
                                                                        if ( $this->data['commission'] * 100 / $this->data['amount']  > $slab_det[0]['slabs']['commission_dist'] ){

                                                                                if(!($shop['margin'] == round($this->data['commission'] * 100/$this->data['amount'],2) && $shop['margin_approved'] == 1)){
                                                                                        $this->General->sendMails("Wrong Commission Transfered to distributor","
                                                                                        Company : ".$shop['company']."</br>
                                                                                        Mobile : ".$shop['mobile']."</br>
                                                                                        Amount : ".$this->data['amount']."</br>
                                                                                        Commission : ".$this->data['commission']."(".round($this->data['commission'] * 100 / $this->data['amount'],2)."%)"."</br>
                                                                                        Slab Name : ".$slab_det[0]['slabs']['name']."</br>
                                                                                        " ,array('finance@pay1.in','limits@pay1.in'), 'mail');
                                                                                }

                                                                        }
                                                                        if($bal1 >= 500000){// if current bal after transfer then raise a alarm

                                                                                $mail_subject = "Current balance of Distributor is greater than 500000";
                                                                                $mail_body = "Current balance of ".$shop['company']." is $bal1 </br>
                                                                                        Mobile : ".$shop['mobile']."</br>
                                                                                        Amount Transferred : ".$this->data['amount']."</br>
                                                                                        ";
                                                                                $this->General->sendMails($mail_subject, $mail_body , array("tadka@pay1.in",'limits@pay1.in'),'mail');
                                                                        }


                                                                        $this->General->sendMails($mail_subject, $mail_body,array('limits@pay1.in'));

                                                                        $data1 = array();
                                                                        $data1['sender'] =  "TFR";
                                                                        $data1['process'] =  "limits";
                                                                        $data1['type'] = "D";
                                                                        $data1['name'] = $shop['company'];
                                                                        $data1['mobile'] = $shop['mobile'];
                                                                        $data1['amount'] = $this->data['amount'];
                                                                        $data1['commission'] = $this->data['commission'];
                                                                        $data1['commission_per'] = $this->data['commission_per'];
                                                                        $data1['transid'] = $trans_id;
                                                                        $this->General->curl_post($this->General->findVar('limit_url'),$data1);
                                                                }

                                                                $paramdata['RECID'] = $trans_id;
                                                                $content=  $MsgTemplate['AmountTransfer_TransactionComplete_MSG'];
                                                                $msg = $this->General->ReplaceMultiWord($paramdata,$content);

                                                                $paramdata['SHOP_NAME'] = $shop['company'];
                                                                $content=  $MsgTemplate['AmountTransfer_TransferComplete_MSG'];
                                                                $succmsg = $this->General->ReplaceMultiWord($paramdata,$content);

                                                                $shopId = $trans_id;

                                                        } else if($to_save && $authData['User']['group_id'] == MASTER_DISTRIBUTOR && $this->data['type_id'] == SUPER_DISTRIBUTOR) {
                                                            
                                                                $bal = $this->Shop->shopBalanceUpdate($this->data['amount']+$this->data['commission'],'subtract',$this->info['user_id'],MASTER_DISTRIBUTOR,$dataSource);
                                                                $bal1 = $this->Shop->shopBalanceUpdate($this->data['amount']+$this->data['commission'],'add',$shop['user_id'],SUPER_DISTRIBUTOR,$dataSource);
                                                                $trans_id = $this->Shop->shopTransactionUpdate(MDIST_SDIST_BALANCE_TRANSFER,$this->data['amount'],$this->info['id'],$shop['id'],$authData['User']['id'],null,null,null,$bal+$this->data['amount']+$this->data['commission'],$bal,$bal1-($this->data['amount']+$this->data['commission']),$bal1,$dataSource);

                                                                if($app_flag == 2){
                                                                        $this->data['typeRadio'] = 1;
                                                                }
                                                                
                                                                if(isset($this->data['typeRadio'])){
                                                                        $dataSource->query("UPDATE shop_transactions SET type_flag = ".$this->data['typeRadio'].",note = '".addslashes($this->data['bank_name'].":".$this->data['description'])."' where id = $trans_id");
                                                                        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step 3 update shop_transactions*: transId=> $trans_id<br/>".date('Y-m-d H:i:s'));
                                                                }

                                                                $mail_subject = "SuperDistributor Transfer Balance";
                                                                $mail_body = "MasterDistributor : " . $this->info['company'] . " transferred Rs. " . ($this->data['amount'] + $this->data['commission']) . " to SuperDistributor : " . $shop['name'];
                                                                $sms = "Dear SuperDistributor,\nYour account is successfully credited with Rs." . ($this->data['amount'] + $this->data['commission']) . "\nYour current balance is Rs.$bal1";

                                                                if(!empty($this->data['description'])){
                                                                        $mail_body .= "<br/>".$this->data['description'].$this->data['bank_name'];
                                                                }

                                                                $this->General->sendMails($mail_subject, $mail_body, array('limits@pay1.in'));

                                                                $data1 = array();
                                                                $data1['sender'] = "TFR";
                                                                $data1['process'] = "limits";
                                                                $data1['type'] = "D";
                                                                $data1['name'] = $shop['name'];
                                                                $data1['mobile'] = $shop['mobile'];
                                                                $data1['amount'] = $this->data['amount'];
                                                                $data1['transid'] = $trans_id;
                                                                $this->General->curl_post($this->General->findVar('limit_url'),$data1);

                                                                $paramdata['RECID'] = $trans_id;
                                                                $content=  $MsgTemplate['AmountTransfer_TransactionComplete_MSG'];
                                                                $msg = $this->General->ReplaceMultiWord($paramdata,$content);

                                                                $paramdata['SHOP_NAME'] = $shop['name'];
                                                                $content=  $MsgTemplate['AmountTransfer_TransferComplete_MSG'];
                                                                $succmsg = $this->General->ReplaceMultiWord($paramdata,$content);

                                                                $shopId = $trans_id;
                                                                
                                                        } else if($to_save && in_array($authData['User']['group_id'], array(DISTRIBUTOR, SALESMAN))){
                                                                /*  Added as changes for DB optimization  */
                                                                $bal = $this->Shop->shopBalanceUpdate($this->data['amount'],'subtract',$this->info['user_id'],DISTRIBUTOR,$dataSource);
                                                                $bal1 = $this->Shop->shopBalanceUpdate($this->data['amount'],'add',$shop['user_id'],RETAILER,$dataSource);
                                                                $recId = $this->Shop->shopTransactionUpdate(DIST_RETL_BALANCE_TRANSFER,$this->data['amount'],$this->info['id'],$shop['id'],$authData['id'],null,null,null,$bal+$this->data['amount'],$bal,$bal1-$this->data['amount'],$bal1,$dataSource);
        //							$this->Shop->addOpeningClosing($shop['id'],RETAILER,$recId,$bal1-$this->data['amount'],$bal1);
        //							$this->Shop->addOpeningClosing($this->info['id'],DISTRIBUTOR,$recId,$bal+$this->data['amount'],$bal);

                                                                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step 1*: transId=> $recId<br/>".date('Y-m-d H:i:s'));
                                                                if(!isset($params['salesmanId']) || $params['salesmanId'] == ''){
                                                                        $dstMob = $this->Slaves->query("SELECT mobile from users where id = '".$authData['User']['id']."'");
                                                                        $sm = $this->Slaves->query("SELECT id,name from salesmen where mobile = '".$dstMob['0']['users']['mobile']."'");
                                                                        $params['salesmanId'] = $sm['0']['salesmen']['id'];
                                                                        $params['salesmanName'] = $sm['0']['salesmen']['name'];
                                                                        $params['distId'] = 0;
                                                                }
                                                                if(!isset($salesman)){
                                                                        $salesmen = $this->Slaves->query("select * from salesmen where id = '".$params['salesmanId']."'");
                                                                        $salesman = $salesmen['0']['salesmen'];
                                                                }
                                                                if(isset($this->data['typeRadio'])){
                                                                        $dataSource->query("UPDATE shop_transactions SET type_flag = ".$this->data['typeRadio'].",note = '".addslashes($this->data['bank_name'].":".$this->data['description'])."' where id = $recId");
                                                                }

                                                                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step2 => update and insert transaction  : transId=> $recId<br/>".date('Y-m-d H:i:s'));

                                                                $dataSource->query("INSERT INTO salesman_transactions
                                                                                (shop_tran_id,salesman,payment_mode,payment_type,details,collection_date,created, closing)
                                                                                VALUES ('".$recId."','".$params['salesmanId']."','".MODE_CASH."','".TYPE_TOPUP."','','".date('Y-m-d')."','".date('Y-m-d H:i:s')."', '".($salesman['balance'] - $this->data['amount'])."')");

                                                                if(!empty($shop['shopname'])){
                                                                        $shop_name = substr($shop['shopname'],0,15);
                                                                        if($shop_name != $shop['shopname'])$shop_name = $shop_name . "..";
                                                                } else
                                                                        $shop_name = $shop['mobile'];

                                                                if($authData['User']['group_id'] == SALESMAN){
                                                                        $distributors = $this->Slaves->query("select u.mobile
                                                                                from users u
                                                                                left join distributors d on d.user_id = u.id
                                                                                where d.id = ".$this->info['id']);
                                                                        if($distributors){
                //								$message_distributor = "Salesman: ".$params['salesmanName']." ) transferred Rs. " . $this->data['amount'] . " to Retailer: " . $shop_name;
                                                                                $paramdata['SALESMAN_NAME'] = "(".$params['salesmanName'].")";
                                                                                $paramdata['AMOUNT'] = $this->data['amount'];
                                                                                $paramdata['SHOP_NAME'] = $shop_name;
                                                                                $content =  $MsgTemplate['AmountTransfer_SalesmanToRetailer_MSG'];
                                                                                $message_distributor = $this->General->ReplaceMultiWord($paramdata,$content);


                                                                                $this->General->sendMessage($distributors['0']['u']['mobile'], $message_distributor, "notify", $bal, DISTRIBUTOR);
                                                                        }
                                                                }

                                                                $mail_body = "Distributor: " . $this->info['company'] . " (Salesman: ".$params['salesmanName']." ) transferred Rs. " . $this->data['amount'] . " to Retailer: " . $shop_name;
                                                                $name = "Retailer";
        //							$sms = "Dear $name,\nYour account is successfully credited with Rs." . $this->data['amount']. "\nYour current balance is Rs.$bal1";

                                                                $paramdata['NAME'] = $name;
                                                                $paramdata['AMOUNT'] = $this->data['amount'];
                                                                $paramdata['BALANCE'] = $bal1;
                                                                $content=  $MsgTemplate['AmountTransfer_AccountCreated_MSG'];
                                                                $sms = $this->General->ReplaceMultiWord($paramdata,$content);


                                                                if(!empty($this->data['description'])){
                                                                        $mail_body .= "<br/>".$this->data['description'].$this->data['bank_name'];
                                                                }

                                                                if(in_array($this->info['id'],explode(",",DISTS))){
                                                                        $this->General->sendMails($mail_subject, $mail_body,array('limits@pay1.in'));

                                                                        $data1 = array();
                                                                        $data1['sender'] =  "TFR";
                                                                        $data1['process'] =  "limits";
                                                                        $data1['type'] = "R";
                                                                        $data1['name'] = $shop['shopname'];
                                                                        $data1['mobile'] = $shop['mobile'];
                                                                        $data1['amount'] = $this->data['amount'];
                                                                        $data1['transid'] = $recId;
                                                                        $this->General->curl_post($this->General->findVar('limit_url'),$data1);
                                                                        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step3 => after curl operation  : transId=> $recId<br/>".date('Y-m-d H:i:s'));
                                                                }
                                                                if($bal1 >= KYC_AMOUNT){// if current bal after transfer then raise a alarm

                                                                    $mail_subject = "Current balance of Retailer is greater than ". KYC_AMOUNT;
                                                                        $mail_body = "Current balance of ".$shop['shopname']." is $bal1 </br>
                                                                                    Mobile : ".$shop['mobile']."</br>
                                                                                    Amount Transferred : ".$this->data['amount']."</br>
                                                                                    ";
                                                                        $this->General->sendMails($mail_subject, $mail_body , array("tadka@pay1.in","limits@pay1.in"),'mail');
                                                                }


                                                                $msg = "Transaction is Completed Successfully And Transaction Id is $recId";
                                                               /* $paramdata['RECID'] = $recId;
                                                                $content=  $MsgTemplate['AmountTransfer_TransactionComplete_MSG'];
                                                                $msg = $this->General->ReplaceMultiWord($paramdata,$content);*/

                                                                $succmsg = "Transfer to ".$shop['shopname']."Completed Successfully!!!";

                                                                                                                        /*$paramdata['SHOP_NAME'] = $shop['shopname'];
                                                                $this->General->logData('/mnt/logs/salesman_limit_transfer.txt', "after sms21");
                                                                $content=  $MsgTemplate['AmountTransfer_TransferComplete_MSG'];
                                                                $this->General->logData('/mnt/logs/salesman_limit_transfer.txt', "after sms22");
                                                                $succmsg = $this->General->ReplaceMultiWord($paramdata,$content);
                                                                */

                                                                $shopId = $recId;
                                                        }

                                                        $dataSource->commit($this->User);

                                                } catch (Exception $ex) {
                                                        $dataSource->rollback();
                                                }
						//$this->General->sendMessage($shop['mobile'],$sms,$name=="Retailer"?'notify':'shops');
						$this->General->sendMessage($shop['mobile'],$sms,'shops');

						if($to_save && isset($params['distId'])) {

							$sm = $this->Slaves->query("SELECT users.mobile,users.balance,users.id,tran_limit from salesmen inner join users ON (users.id  = salesmen.user_id) where salesmen.id = ".$params['salesmanId']);

							$data = $this->Slaves->query("SELECT sum(shop_transactions.amount) as topups FROM salesman_transactions inner join shop_transactions ON (shop_transactions.id=salesman_transactions.shop_tran_id) WHERE salesman_transactions.salesman=".$params['salesmanId']." AND shop_transactions.target_id is not null AND salesman_transactions.payment_type=2 AND collection_date='".date('Y-m-d')."'");

                                                        $paramdata['AMOUNT'] = $this->data['amount'];
                                                        $paramdata['BALANCE'] = $bal;
                                                        $paramdata['SHOP_NAME'] = $shop_name;
                                                        $paramdata['TOPUPS'] = $data['0']['0']['topups'];
                                                        $content =  $MsgTemplate['AmountTransfer_ToRetailer_MSG'];
                                                        $message = $this->General->ReplaceMultiWord($paramdata,$content);

							if($app_flag != 1){
// 								$this->General->sendMessage($sm['0']['salesmen']['mobile'],$message,'ussd');
							} else {
								$sms_send = $message;
							}
						} else if($to_save && isset($params['salesmanId'])){

							$sm = $this->Slaves->query("SELECT users.mobile,users.balance,users.id,tran_limit from salesmen inner join users ON (users.id  = salesmen.user_id) where salesmen.id = ".$params['salesmanId']);
							$this->Shop->shopBalanceUpdate($this->data['amount'],'subtract',$sm['0']['users']['id'],SALESMAN);
							$data = $this->Slaves->query("SELECT sum(shop_transactions.amount) as topups FROM salesman_transactions inner join shop_transactions ON (shop_transactions.id=salesman_transactions.shop_tran_id) WHERE salesman_transactions.salesman=".$params['salesmanId']." AND salesman_transactions.payment_type=2 AND collection_date='".date('Y-m-d')."'");


                                                        $paramdata['AMOUNT'] = $this->data['amount'];
                                                        $paramdata['BALANCE'] = $sm['0']['users']['balance'] - $this->data['amount'] . " (" . $sm['0']['salesmen']['tran_limit'] . ")";
                                                        $paramdata['SHOP_NAME'] = $shop_name;
                                                        $paramdata['TOPUPS'] = $data['0']['0']['topups'];
                                                        $content =  $MsgTemplate['AmountTransfer_ToRetailer_MSG'];
                                                        $message = $this->General->ReplaceMultiWord($paramdata,$content);

                                                        if($app_flag != 1 && $app_flag !=3) {
								$this->General->sendMessage($sm['0']['users']['mobile'],$message,'notify',null,DISTRIBUTOR);
							} else {
								$sms_send = $message;
							}
						}
						if($to_save){
                                                        if($app_flag == 0){
                                                                //echo "<script> reloadShopBalance(".$bal.");  </script>";
                                                                //$this->render('/elements/shop_transfer','ajax');
                                                        } else {
                                                                return array('status' => 'success','balance' => $bal1,'description' => $sms_send,'shopId' => $shopId ,'msg' => $succmsg);
                                                        }
                                                }
					}
				}
			}

                }

		if(!$to_save){
			if($app_flag == 0){
				$this->set('data',$this->data);
				$msg = "<div class='error_class'>".$msg."</div>"
                                        . "<script>typeChange();</script>";
				$this->Session->setFlash(__($msg, true));
				$this->render('/elements/shop_transfer','ajax');
			} else {
				return array('status' => 'failure','description' => $msg);
			}
		} else if($confirm == 1) {
			$this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step 6 before rendering : transId=> $shopId<br/>".date('Y-m-d H:i:s'));
			echo "<script> reloadShopBalance(".$bal.");  </script>";
			$msg = "<div class='error_class'>".$msg."</div>"
                                . "<script>typeChange();</script>";
			$this->Session->setFlash(__($msg, true));
			$this->render('/elements/shop_transfer','ajax');
			$this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/amount_transfer.txt","inside balance transfer step 7 after rendering : transId=> $shopId<br/>".date('Y-m-d H:i:s'));
		}
        }

        function amountTransferNew($params=null,$authData=null) {

                if(empty($authData)) {
                        $authData = $this->info = $this->Session->read('Auth');
                } else {
                        $this->info = $authData;
                }


                $par_id = isset($authData['dist_id']) ? $authData['dist_id'] : $authData['id'];
                $su = $this->Slaves->query("SELECT system_used, active_flag FROM distributors WHERE id = '".$par_id."'");
                $sys_used = $su['0']['distributors']['system_used'];

                if(!in_array($authData['User']['group_id'],array(DISTRIBUTOR,SALESMAN)) && !isset($authData['dist_id'])){
			$this->redirect(array('action' => 'index'));
		}

//                $su = $this->Slaves->query("SELECT system_used FROM distributors WHERE id = '".$authData['id']."'");
//                $sys_used = $su['0']['distributors']['system_used'];

                $app_flag = 0;      // 0 for panel, 1 for sms transfer, 2 for auto limit transfer, 3 for app api

                $MsgTemplate = $this->General->LoadApiBalance();

                if($params != null){
			$this->data['confirm'] = 1;
                        $confirm = 1;
			$this->data['amount']  = $params['amount'];
			$this->data['shop']    = $params['retailer'];
			if(isset($params['description'])) {
				$this->data['description'] = $params['description'];
			}
			if(isset($params['typeRadio'])) {
				$this->data['typeRadio'] = $params['typeRadio'];
                        } else {
				$this->data['typeRadio'] = 1;
                        }
                        if(isset($params['app_flag']) && ($params['app_flag'] == 3)) {
                                $app_flag = 3;
                                if(isset($params['salesmanId'])) {
                                        $authData['User']['group_id'] = SALESMAN;
                                        $this->data['type'] = RETAILER;
                                        $this->data['salesmanId'] = $params['salesmanId'];
                                        $this->data['salesmanName'] = $params['salesmanName'];
                                }
                                if(isset($params['salesman'])) {
                                        $authData['User']['group_id'] = DISTRIBUTOR;
                                        $this->data['type'] = SALESMAN;
                                        $this->data['shop'] = $params['salesman'];
                                }
                        } else {
                                $app_flag = 1;
                                if($params['type'] == 'Salesman') {
                                        $this->data['type'] = SALESMAN;
                                } else {
                                        $this->data['type'] = RETAILER;
                                }
                                if(isset($params['salesmanId'])) {
                                        $this->data['salesmanId'] = $params['salesmanId'];
                                        $this->data['salesmanName'] = $params['salesmanName'];
                                }
                        }
		} else {
                        $type = $this->data['type'];

                        $this->data['type'] = $this->data['type'] == 'Retailer' ? RETAILER : SALESMAN;

                        if($authData['User']['group_id'] != trim($this->data['group'])) {
                                echo "Invalid Transfer. Kindly Login Again";
                                    exit;
                        }

                        if($app_flag == 0) {
				if($authData['User']['id'] == 1) {
                                        $getlimitPassword = $this->General->findVar('limit_password');
                                        $password = isset($_REQUEST['password']) ? md5($_REQUEST['password']): "" ;
                                       // $pass1=hash('sha256',$_REQUEST['password']);
                                        if($getlimitPassword != $password) {
                                                echo "Please enter valid password or contact your system admin"; exit;
                                        }
					if(isset($this->data['bank_name']) && empty($this->data['bank_name'])){
                                                echo "Please Select Bank Name"; exit;
                                        }
					if(isset($this->data['description']) && empty($this->data['description'])){
                                                echo "Please Enter Bank Txn Id"; exit;
                                        }
                                } else if($authData['User']['group_id'] == DISTRIBUTOR && in_array($authData['id'],explode(",",DISTS))) {
                                        if(isset($this->data['bank_name']) && empty($this->data['bank_name'])){
                                                echo "Please Select Bank Name"; exit;
                                        }
                                        if(isset($this->data['description']) && empty($this->data['description'])){
                                                echo "Please Enter Bank Txn Id"; exit;
                                        }
                                }


                                if (isset($this->data['confirm_flag']) && !is_numeric($this->data['confirm_flag'])) {
                                        echo "Some internal issue occured"; exit;
                                }

                        }
                }

                $to_save = true;
		$this->data['amount'] = trim($this->data['amount']);

                if($su['0']['distributors']['active_flag'] == 0) {
                        if($app_flag == 3) {
                                return array("status" => "failure", "description" => "Cannot transfer balance. Distributor is inactive");
                        } else {
                                echo "Cannot transfer balance. Distributor is inactive";
                                exit;
                        }
                }

                if(empty($this->data['amount'])) {
                        if($app_flag == 1) {
				$msg = "Invalid SMS format.\nCorrect format: PAY1 TB mobile amount";
			} else if($app_flag == 0) {
                                $msg = "Please enter some amount";
                        }
                        $to_save    = false;
                } else if($this->data['amount'] <= 0 || !preg_match('/^\d+$/',$this->data['amount']) || !is_numeric($this->data['amount'])) {
                        if($app_flag == 1){
				$msg = "Invalid SMS format.\nCorrect format: PAY1 TB mobile amount";
			} else if($app_flag == 0) {
                                $msg = "Amount entered is not valid";
                        }
                        $to_save    = false;
                } else if($this->data['type'] == RETAILER && $this->data['amount'] > 50000 && $app_flag != 2) {
                        $msg        = "Amount cannot be greater than 50000";
                        $to_save    = false;
                } else if($this->data['type'] == RETAILER && $this->data['amount'] > 100000 && $app_flag == 2) {
                        $msg        = "Amount cannot be greater than 100000";
                        $to_save    = false;
                } else if(empty($this->data['type']) || !in_array($this->data['type'], array(RETAILER, SALESMAN))) {
                        $msg        = "Please select a type to transfer the amount";
                        $to_save    = false;
                } else if($this->data['shop'] == 0 || empty($this->data['shop']) || !is_numeric($this->data['shop'])) {
                        if($app_flag == 1) {
                                $msg = "Invalid SMS format.\nCorrect format: PAY1 TB mobile amount";
                        } else if($app_flag == 0) {
                                if($this->data['type'] == SALESMAN) {
                                        $msg = "Please select salesman";
                                } else {
                                        $msg = "Please select retailer";
                                }
                        }
			$to_save = false;
                } else if(isset($this->data['group']) && (empty($this->data['group']) || !in_array($this->data['group'], array(DISTRIBUTOR, SALESMAN)))) {
                        $msg        = "Please select group";
                        $to_save    = false;
                } else if(empty($this->data['typeRadio']) || !is_numeric($this->data['typeRadio'])) {
                        $msg        = "Please select Payment Type";
                        $to_save    = false;
                } else if($this->data['typeRadio'] != 1 && isset($this->data['description']) && empty($this->data['description'])) {
                        $msg        = "Please Enter Bank Txn Id";
                        $to_save    = false;
                } else if($this->data['description'] != '' && !ctype_alnum(str_replace("-","",$this->data['description']))) {
                        $msg        = "Please Enter Proper Bank Txn Id";
                        $to_save    = false;
                } else {
                        if(isset($this->data['bank_name'])) {
                                $this->data['description'] = $this->data['bank_name']." (".$this->data['description'].")";
                        }

                        //$table = ($authData['User']['group_id'] == DISTRIBUTOR) ? 'distributors' : 'salesmen';
                        $bal_data = $this->Shop->getBalance($authData['User']['id']);

                        $bal        = $bal_data;
                        $amt_bal    = $this->data['amount'];

                        if($amt_bal > $bal) {
                                if($app_flag != 3) {
                                        $msg = "Cannot transfer due to insufficient balance";
                                } else {
                                        return array("status" => "failure", "description" => "Cannot transfer due to insufficient balance");
                                }
                                $to_save    = false;
                        } else {
                                $shop = $this->Shop->getShopDataById($this->data['shop'], $this->data['type']);
                                if($authData['User']['group_id'] == DISTRIBUTOR && $this->data['type'] == SALESMAN) {
                                        if($this->info['id'] != $shop['dist_id']) {
                                                if($app_flag == 3) {
                                                        return array("status" => "failure", "description" => "Invalid transfer");
                                                } else {
                                                        echo "Invalid transfer";
                                                        exit;
                                                }
                                        }
                                } else if($authData['User']['group_id'] == DISTRIBUTOR && $this->data['type'] == RETAILER) {
                                        if($this->info['id'] != $shop['parent_id']) {
                                                if($app_flag == 3) {
                                                        return array("status" => "failure", "description" => "Invalid transfer");
                                                } else {
                                                        echo "Invalid transfer";
                                                        exit;
                                                }
                                        }
                                } else if($authData['User']['group_id'] == SALESMAN && $this->data['type'] == RETAILER) {
                                        if($this->info['id'] != $shop['maint_salesman']) {
                                                if($app_flag == 3) {
                                                        return array("status" => "failure", "description" => "Invalid transfer");
                                                } else {
                                                        echo "Invalid transfer";
                                                        exit;
                                                }
                                        }
                                }


                                if(in_array($authData['User']['group_id'], array(DISTRIBUTOR, SALESMAN)) && $this->data['type'] != SALESMAN && $shop['kyc_flag'] == 0 && ($this->data['amount'] + $shop['balance']) > KYC_AMOUNT) {
                                        $msg        = "Please collect KYC of the retailer. Retailer balance cannot be greater than Rs." . KYC_AMOUNT;
                                        $to_save    = false;
                                }
                                else if(in_array($authData['User']['group_id'], array(DISTRIBUTOR, SALESMAN)) && $this->data['type'] != SALESMAN && $shop['kyc_flag'] == 1 && ($this->data['amount'] + $shop['balance']) > KYC_AMOUNT_MAX) {
                                    $msg = "Retailer cannot maintain more than ".KYC_AMOUNT_MAX;
                                    $to_save = false;
                                }
                                else if($shop['user_id'] == $authData['User']['id']){
                                    $msg = "You cannot transfer balance to yourself";
                                    $to_save = false;

                                }
                                else {
                                        if($this->data['confirm_flag'] == 0 && $to_save == true && $app_flag == 0) {
                                                $this->data['confirm_flag'] = 1;
                                                $this->data['shopData'] = $shop;
                                                $this->set('balance', $bal-$amt_bal);
						$this->set('data', $this->data);
						$this->render('/elements/shop_transfer_new','ajax');
                                                $this->data['confirm_flag'] = 0;
                                        } else {
                                                $det = $this->Shop->getMemcache("txn_" . $shop['mobile']);

                                                if($det == false) {
                                                    $this->Shop->setMemcache("txn_" . $shop['mobile'],1, 2*60);
                                                } else {
                                                        if($app_flag == 3) {
                                                                return array("status" => "failure", "description" => "Invalid transfer");
                                                        } else {
                                                                $msg = "You cannot transfer balance to same person within 2 minutes !!!";
                                                        }
                                                        $to_save    = false;
                                                }
                                        }

                                        if(($this->data['confirm_flag'] == 1 && $to_save) || $app_flag != 0) {
                                                $this->data['shopData'] = $shop;

                                                $mail_subject   = "Retail Panel: Amount Transferred";
                                                $describ        = isset($this->data['description']) ? addslashes($this->data['description']) : '';

                                                $dataSource = $this->User->getDataSource();

                                                try {
                                                        $dataSource->begin($this->User);

                                                        if($authData['User']['group_id'] == DISTRIBUTOR) {
                                                                if($this->data['type'] == SALESMAN) {

                                                                        $bal        = $this->Shop->shopBalanceUpdate($this->data['amount'],'subtract',$this->info['user_id'],DISTRIBUTOR,$dataSource);
                                                                        $bal1       = $this->Shop->shopBalanceUpdate($this->data['amount'],'add',$shop['user_id'],SALESMAN,$dataSource);

                                                                        $recId      = $this->Shop->shopTransactionUpdate(DIST_SLMN_BALANCE_TRANSFER, $this->data['amount'], $this->info['id'], $shop['id'], $authData['User']['id'], null, $this->data['typeRadio'], $describ,$bal+$this->data['amount'],$bal,$bal1-$this->data['amount'],$bal1,$dataSource);

                                                                       // $this->Shop->addOpeningClosing($this->info['id'],DISTRIBUTOR,$recId,$bal+$this->data['amount'],$bal);
                                                                       // $this->Shop->addOpeningClosing($shop['id'],SALESMAN,$recId,$bal1-$this->data['amount'],$bal1);

                                                                        $paramdata['DISTRIBUTOR_NAME']  = $authData['company'];
                                                                        $paramdata['AMOUNT']            = $this->data['amount'];
                                                                        $paramdata['SALESMAN_NAME']     = $shop['name'];
                                                                        $content = $MsgTemplate['AmountTransfer_DistributorToSalesman_MSG'];
                                                                        $sms = $this->General->ReplaceMultiWord($paramdata,$content);

                                                                        $mail_body = "Distributor: " . $this->info['company'] . " transferred Rs. " . $this->data['amount'] . " to Salesman: " . $shop['name'] . "<br/>" . $this->data['description'];
                                                                        if(in_array($this->info['id'], explode(",",DISTS))) {
                                                                                $this->General->sendMails($mail_subject, $mail_body, array('limits@pay1.in'));

                                                                                $data1              = array();
                                                                                $data1['sender']    = "TFR";
                                                                                $data1['process']   = "limits";
                                                                                $data1['type']      = "S";
                                                                                $data1['name']      = $shop['name'];
                                                                                $data1['mobile']    = $shop['mobile'];
                                                                                $data1['amount']    = $this->data['amount'];
                                                                                $data1['transid']   = $recId;
                                                                                $this->General->curl_post($this->General->findVar('limit_url'),$data1);
                                                                        }

                                                                        if($bal1 >= KYC_AMOUNT){    // if current bal after transfer then raise a alarm
        //
                                                                            $mail_subject   = "Current balance of Retailer is greater than ".KYC_AMOUNT;
                                                                                $mail_body      = "Current balance of ".$shop['name']." is $bal1 </br>
        //                                                                                    Mobile : ".$shop['mobile']."</br>
        //                                                                                    Amount Transferred : ".$this->data['amount']."</br>";
                                                                                $this->General->sendMails($mail_subject, $mail_body , array("tadka@pay1.in","limits@pay1.in"),'mail');
                                                                        }

                                                                        $msg = "Transaction is Completed Successfully And Transaction Id is $recId";

                                                                        $paramdata['SALESMAN_NAME'] = $shop['name'];
                                                                        $sending_msg = 'AmountTransfer_ToSalesman_MSG';

                                                                } else {

                                                                        $sm = $this->Slaves->query("SELECT salesmen.* from salesmen JOIN users ON salesmen.user_id = users.id WHERE users.id = '".$authData['User']['id']."'");
                                                                        $params['salesmanId']   = $sm['0']['salesmen']['id'];
                                                                        $params['salesmanName'] = $sm['0']['salesmen']['name'];
                                                                        $params['distId']       = 0;
                                                                        $salesman = $sm['0']['salesmen'];
        //                                                                $note = ($shop['shopname'] == '' ? $shop['mobile'] : $shop['shopname']);

                                                                        $bal    = $this->Shop->shopBalanceUpdate($this->data['amount'],'subtract',$this->info['user_id'],DISTRIBUTOR,$dataSource);
                                                                        $bal1   = $this->Shop->shopBalanceUpdate($this->data['amount'],'add',$shop['user_id'],RETAILER,$dataSource);
                                                                        $bal2   = $bal;

                                                                        $recId  = $this->Shop->shopTransactionUpdate(DIST_SLMN_BALANCE_TRANSFER, $this->data['amount'], $this->info['id'], $salesman['id'], $authData['User']['id'], null, $this->data['typeRadio'], $describ,$bal+$this->data['amount'],$bal,$bal2,$bal2+$this->data['amount'],$dataSource);
                                                                        $recId1 = $this->Shop->shopTransactionUpdate(SLMN_RETL_BALANCE_TRANSFER, $this->data['amount'], $salesman['id'], $shop['id'], $recId, $authData['User']['id'], $this->data['typeRadio'], $describ,$bal2+$this->data['amount'],$bal2,$bal1-$this->data['amount'],$bal1,$dataSource);

                                                                       // $this->Shop->addOpeningClosing($this->info['id'],DISTRIBUTOR,$recId,$bal+$this->data['amount'],$bal);
                                                                        //$this->Shop->addOpeningClosing($salesman['id'],SALESMAN,$recId,$bal2,$bal2+$this->data['amount']);
                                                                        //$this->Shop->addOpeningClosing($salesman['id'],SALESMAN,$recId1,$bal2+$this->data['amount'],$bal2);
                                                                        //$this->Shop->addOpeningClosing($shop['id'],RETAILER,$recId1,$bal1-$this->data['amount'],$bal1);

                                                                        if(!empty($shop['shopname'])){
                                                                                $shop_name = substr($shop['shopname'],0,15);
                                                                                if($shop_name != $shop['shopname']) $shop_name = $shop_name . "..";
                                                                        } else
                                                                                $shop_name = $shop['mobile'];

                                                                                $paramdata['NAME'] = 'Retailer';
                                                                                $paramdata['AMOUNT'] = $this->data['amount'];
                                                                                $paramdata['BALANCE'] = $bal1;
                                                                                $content=  $MsgTemplate['AmountTransfer_AccountCreated_MSG'];
                                                                        /*$paramdata['DISTRIBUTOR_NAME']  = $authData['company'];
                                                                        $paramdata['AMOUNT']            = $this->data['amount'];
                                                                        $paramdata['RETAILER_NAME']     = $shop['name'] . " (".$shop['shopname'].")";
                                                                        $content = $MsgTemplate['AmountTransfer_DistributorToRetailer_MSG'];*/
                                                                        $sms = $this->General->ReplaceMultiWord($paramdata,$content);

                                                                        $mail_body = "Distributor: " . $this->info['company'] . " transferred Rs. " . $this->data['amount'] . " to Retailer: " . $shop_name . "<br/>". $this->data['description'];
                                                                        if(in_array($this->info['id'], explode(",",DISTS))) {
                                                                                $this->General->sendMails($mail_subject, $mail_body, array('limits@pay1.in'));

                                                                                $data1              = array();
                                                                                $data1['sender']    = "TFR";
                                                                                $data1['process']   = "limits";
                                                                                $data1['type']      = "R";
                                                                                $data1['name']      = $shop['shopname'];
                                                                                $data1['mobile']    = $shop['mobile'];
                                                                                $data1['amount']    = $this->data['amount'];
                                                                                $data1['transid']   = $recId1;
                                                                                $this->General->curl_post($this->General->findVar('limit_url'),$data1);
                                                                        }

                                                                        if($bal1 >= KYC_AMOUNT){    // if current bal after transfer then raise a alarm

                                                                            $mail_subject   = "Current balance of Retailer is greater than ".KYC_AMOUNT;
                                                                                $mail_body      = "Current balance of ".$shop['shopname']." is $bal1 </br>
                                                                                            Mobile : ".$shop['mobile']."</br>
                                                                                            Amount Transferred : ".$this->data['amount']."</br>";
                                                                                $this->General->sendMails($mail_subject, $mail_body , array("tadka@pay1.in","limits@pay1.in"),'mail');
                                                                        }

                                                                        $msg = "Transaction is Completed Successfully And Transaction Id is $recId";

                                                                        $paramdata['SHOP_NAME'] = $shop['shopname'];
                                                                        $sending_msg = 'AmountTransfer_ToRetailer_MSG';
                                                                }
                                                                $top_ups = $dataSource->query("SELECT sum(amount) as topups FROM shop_transactions WHERE source_id = '".$_SESSION['Auth']['id']."' AND confirm_flag != '1' AND date = '".date('Y-m-d')."' AND type = ".DIST_SLMN_BALANCE_TRANSFER);


                                                        } else {

                                                                if($this->info['block_flag'] == 1) {
                                                                        return array("status" => "failure", "description" => "You are blocked by your distributor. Contact your distributor");
                                                                }

                                                                $authData['User']['group_id'] = SALESMAN;
                                                                $note = ($shop['shopname'] == '' ? $shop['mobile'] : $shop['shopname']);

                                                                $bal    = $this->Shop->shopBalanceUpdate($this->data['amount'],'subtract',$this->info['user_id'],SALESMAN,$dataSource);
                                                                $bal1   = $this->Shop->shopBalanceUpdate($this->data['amount'],'add',$shop['user_id'],RETAILER,$dataSource);

                                                                $recId  = $this->Shop->shopTransactionUpdate(SLMN_RETL_BALANCE_TRANSFER,$this->data['amount'],$this->info['id'],$shop['id'], NULL, NULL, $this->data['typeRadio'], $describ,$bal+$this->data['amount'],$bal,$bal1-$this->data['amount'],$bal1,$dataSource);
                                                               // $this->Shop->addOpeningClosing($this->info['id'],SALESMAN,$recId,$bal+$this->data['amount'],$bal);
                                                               // $this->Shop->addOpeningClosing($shop['id'],RETAILER,$recId,$bal1-$this->data['amount'],$bal1);

                                                                if(!empty($shop['shopname'])){
                                                                        $shop_name = substr($shop['shopname'],0,15);
                                                                        if($shop_name != $shop['shopname'])$shop_name = $shop_name . "..";
                                                                } else
                                                                        $shop_name = $shop['mobile'];


                                                                        $paramdata['NAME'] = 'Retailer';
                                                                        $paramdata['AMOUNT'] = $this->data['amount'];
                                                                        $paramdata['BALANCE'] = $bal1;
                                                                        $content=  $MsgTemplate['AmountTransfer_AccountCreated_MSG'];

                                                                /*$paramdata['SALESMAN_NAME'] = ucwords($this->info['name']);
                                                                $paramdata['AMOUNT']        = $this->data['amount'];
                                                                $paramdata['SHOP_NAME']     = $shop['shopname'];
                                                                $content = $MsgTemplate['AmountTransfer_SalesmanToRetailer_MSG'];*/
                                                                $sms = $this->General->ReplaceMultiWord($paramdata,$content);

                                                                $mail_body = "Salesman: ".ucwords($this->info['name'])." transferred Rs. " . $this->data['amount'] . " to Retailer: " . $shop['shopname'] . "<br/>". $this->data['description'];
                                                                $this->General->sendMails($mail_subject, $mail_body, array('limits@pay1.in'));

                                                                $data1              = array();
                                                                $data1['sender']    = "TFR";
                                                                $data1['process']   = "limits";
                                                                $data1['type']      = "R";
                                                                $data1['name']      = $shop['shopname'];
                                                                $data1['mobile']    = $shop['mobile'];
                                                                $data1['amount']    = $this->data['amount'];
                                                                $data1['transid']   = $recId;
                                                                $this->General->curl_post($this->General->findVar('limit_url'),$data1);

                                                                if($bal1 >= KYC_AMOUNT){    // if current bal after transfer then raise a alarm

                                                                    $mail_subject = "Current balance of Retailer is greater than ".KYC_AMOUNT;
                                                                        $mail_body = "Current balance of ".$shop['shopname']." is $bal1 </br>
                                                                                    Mobile : ".$shop['mobile']."</br>
                                                                                    Amount Transferred : ".$this->data['amount']."</br>";
                                                                        $this->General->sendMails($mail_subject, $mail_body , array("tadka@pay1.in","limits@pay1.in"),'mail');
                                                                }

                                                                $msg = "Transaction is Completed Successfully And Transaction Id is $recId";

                                                                $paramdata['SHOP_NAME'] = $shop['shopname'];
                                                                $sending_msg = 'AmountTransfer_ToRetailer_MSG';

                                                                $top_ups = $dataSource->query("SELECT sum(amount) as topups FROM shop_transactions WHERE source_id = '".$this->info['id']."' AND confirm_flag != '1' AND date = '".date('Y-m-d')."' AND type = ".SLMN_RETL_BALANCE_TRANSFER);
                                                        }

                                                        $dataSource->commit($this->User);

                                                } catch (Exception $ex) {
                                                        $dataSource->rollback();
                                                }

                                                $this->General->sendMessage($shop['mobile'],$sms,'shops');

                                               // $top_ups = $this->Slaves->query("SELECT sum(shop_transactions.amount) as topups FROM shop_transactions ON (shop_transactions.id=opening_closing.shop_transaction_id) WHERE opening_closing.shop_id=".$authData['id']." AND opening_closing.group_id=".$authData['User']['group_id']." AND Date(opening_closing.timestamp)='".date('Y-m-d')."'");

                                                $paramdata['AMOUNT']    = $this->data['amount'];
                                                $paramdata['BALANCE']   = $bal;
                                                $paramdata['TOPUPS']    = $top_ups['0']['0']['topups'];
                                                $content = $MsgTemplate[$sending_msg];
                                                $message = $this->General->ReplaceMultiWord($paramdata,$content);

//                                                if($app_flag != 1){}
                                                $this->General->sendMessage($authData['User']['mobile'],$message,'shops');
                                                if($app_flag != 0){
                                                    return array('status'=>'success','balance'=>$bal,'description'=>$message,'shopId'=>$shop['id'],'msg'=>$msg);
                                                }

                                        }
                                }
                        }
                }

                if($this->data['confirm_flag'] == 1 || $to_save == false) {
                        if($app_flag == 0) {
                                $msg = "<div class='error_class'>".$msg."</div>"
                                        . "<script>typeChange();</script>";
                                if($authData['User']['group_id'] == DISTRIBUTOR) {
                                        $msg .= "<script>reloadBal($bal);</script>";
                                }
                                if($this->data['confirm_flag'] == 1) {
                                        $this->data['confirm_flag'] = 0;
                                } else {
                                        $this->set('data', $this->data);
                                }
                                $this->Session->setFlash(__($msg, true));
                                $this->render('/elements/shop_transfer_new','ajax');
                        } else {
                                return array('status'=>'failure', 'description'=>$msg);
                        }
                }
        }

        function calculateCommission(){
		$id = trim($_REQUEST['id']);
		$amount = trim($_REQUEST['amount']);
		$comm = 0;
		$margin = 0;

		if($amount > 0){
			$margin = $this->Shop->getMemcache("margin_".$this->Session->read('Auth.User.group_id')."_".$id);

			if($margin === false){
				if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR){
					$data = $this->Slaves->query("SELECT margin FROM distributors as shop WHERE id = $id");
				}
				else {
					$data = $this->Slaves->query("SELECT margin FROM master_distributors as shop WHERE id = $id");
				}

				if(!empty($data)){
					$margin = $data['0']['shop']['margin'];
					$this->Shop->setMemcache("margin_".$this->Session->read('Auth.User.group_id')."_".$id,$data['0']['shop']['margin'],6*60*60);
				}

			}
			$comm = round($margin * $amount /100,0);
		}

		echo $comm;
		$this->autoRender = false;
	}


	function backTransfer(){

		$this->set('data',$this->data);
		$this->render('/elements/shop_transfer');
	}

	function kitsTransfer(){

                $services = $this->Slaves->query("SELECT id, name FROM services WHERE toShow = 1");
                $this->set('services', $services);
	}

        function debitSystem() {

                $this->autoRender = false;

                $to_save = true;
                if ($this->data['shop'] == 0) {
                        $msg = "Please select Operation Type";
                        $to_save = false;
                } else if ($this->data['distributor'] == 0) {
                        $msg = "Please select Distributor";
                        $to_save = false;
                } else if ($this->data['service'] == 0) {
                        $msg = "Please select Service";
                        $to_save = false;
                } else if ($this->data['shop'] > 1  && (empty($this->data['amount']) || $this->data['amount'] <= 0)) {
                        $msg = "Please enter proper amount";
                        $to_save = false;
                } else if ($this->data['shop'] == 1 && (empty($this->data['kits']) || $this->data['kits'] <= 0)) {
                        $msg = "Please enter proper no of Kits";
                        $to_save = false;
                } else if ($this->data['shop'] == 1 && (empty($this->data['per_kit']) || $this->data['per_kit'] <= 0)) {
                        $msg = "Please enter proper Per kit charge";
                        $to_save = false;
                }

                if ($this->data['distributor'] > 0) {
                        $distributor  = $this->data['distributor'];
                        $service      = $this->data['service'];
                        $amount       = $this->data['shop'] == 1 ? $this->data['kits'] * ($this->data['per_kit'] - $this->data['discount_per_kit']) : $this->data['amount'];

                        $dist_details = $this->Slaves->query("SELECT d.id, d.user_id, dk.id, users.balance FROM distributors d LEFT JOIN distributors_kits dk ON (d.id = dk.distributor_id AND dk.service_id = '$service') JOIN users ON (d.user_id = users.id) "
                                . "WHERE d.id = '$distributor'");
                         //print_r($dist_details) ;die;
                        if ($amount > $dist_details[0]['users']['balance']) {
                                $msg = "Distributor does not have enough balance";
                                $to_save = false;
                        }
                }

                if ($this->data['confirm_flag'] == 0 && $to_save != false) {
                        $confirm_flag = $this->data['confirm_flag'];
                        $to_save = false;
                        //$pass2=hash('sha256',$this->data['password']);
                } else if ($this->data['confirm_flag'] == 1 && $this->General->findVar('limit_password') != md5($this->data['password'])) {
                        $msg = "Incorrect Password";
                        $to_save = false;
                }

                if ($to_save) {
                        $this->General->logData('kit_issues.txt',json_encode($this->data));
                        $kits        = $this->data['kits'];
                        $discount    = $this->data['shop'] == 1 ? $this->data['kits'] * $this->data['discount_per_kit'] : 0;
                        $note        = $this->data['note'];
                        $type        = ($this->data['shop'] == 1) ? KITCHARGE : (($this->data['shop'] == 2) ? SECURITY_DEPOSIT : ONE_TIME_CHARGE);

                        $this->General->logData('kit_issues.txt',json_encode($dist_details));

                        if($this->data['shop'] == 1 && $kits > 200){
                            $msg = 'Failure: Kits cannot be greater than 200';
                        }
                        else {
                            $balance = $this->Shop->shopBalanceUpdate($amount, 'subtract', $dist_details[0]['d']['user_id']);

                            $this->Shop->shopTransactionUpdate($type, $amount, $dist_details[0]['d']['user_id'], $kits, $service, $discount, NULL, $note, $balance + $amount, $balance);

                            if ($this->data['shop'] == 1) {
                                if ($dist_details[0]['dk']['id'] == '') {
                                    $this->User->query("INSERT INTO distributors_kits (distributor_id, service_id, kits, updated) VALUES "
                                            . "('$distributor', '$service', '$kits', '".date('Y-m-d H:i:s')."')");
                                } else {
                                    $this->User->query("UPDATE distributors_kits SET kits = kits + $kits, updated = '".date('Y-m-d H:i:s')."' WHERE id = '{$dist_details[0]['dk']['id']}'");
                                }
                            }

                            $msg = 'Record Inserted Successfully';
                        }

                } else {
                    if ($this->data['shop'] == 2) {
                        $this->User->query("UPDATE distributors SET sd_amt = sd_amt + $amount WHERE id = '$distributor'");
                    }
                    else if($this->data['shop'] == 3){
                        $this->User->query("UPDATE distributors SET one_time = one_time + $amount WHERE id = '$distributor'");
                    }
                        $this->set('data', $this->data);

                        if (isset($confirm_flag)) {
                                $this->set('confirm_flag', 1);
                        }
                }

                $services = $this->Slaves->query("SELECT id, name FROM services WHERE toShow = 1");
                $this->set('services', $services);
                if(isset($msg)) {
                        $msg = "<div class='error_class'>".$msg."</div>";
                        $this->Session->setFlash(__($msg, true));
                }
                $this->render('/elements/shop_kits_transfer','ajax');
        }

	function transferKits(){
		//echo "======".isset($this->data['commission_flag'])?"Y":"N"."======";
		$this->data['commission_flag'] = isset($this->data['commission_flag'])?$this->data['commission_flag']:"";
		$to_save = true;
		$confirm = 0;
		if(isset($this->data['confirm']))
		$confirm = $this->data['confirm'];
		preg_match('/^[0-9]/',$this->data['amount'],$matches,0);
		preg_match('/^[0-9]/',$this->data['kit_commission'],$matches1,0);
		if($this->data['shop'] == 0)
		{
			$msg = "Please select distributor";
			$to_save = false;
		}
		else if(empty($this->data['amount']))
		{
			$msg = "Please enter amount";
			$to_save = false;
		}
		else if($this->data['amount'] <= 0 || empty($matches)){
			$msg = "Amount entered is not valid";
			$to_save = false;
		}
		else if($this->data['kit'] > 30){
			$msg = "Cannot transfer more than 30 kits at a time";
			$to_save = false;
		}
		else if($this->data['commission_flag'] == 'on' && (empty($matches1) || empty($this->data['kit_commission']))){
			$msg = "Commission amount entered is not valid";
			$to_save = false;
		}
		else {
			$shop = $this->Shop->getShopDataById($this->data['shop'],DISTRIBUTOR);
			$this->data['shopData'] = $shop;

			if($confirm == 0){
				$this->set('data',$this->data);
				$this->render('confirm_kits_transfer','ajax');
			} else {

				$mail_subject = "Retail Panel: Kits Transferred";
				$kits = $shop['kits'];
				if($kits == -1)$kits = 0;
				if(empty($this->data['kit']))
				$kits = -1;
				else
				$kits = $kits + $this->data['kit'];

				if($this->data['commission_flag'] == 'on'){
					$this->Retailer->query("UPDATE distributors SET kits=$kits,commission_kits_flag=1,retailer_creation=1,discount_kit=".$this->data['kit_commission']." WHERE id = " . $shop['id']);
				}
				else {
					$this->Retailer->query("UPDATE distributors SET kits=$kits,commission_kits_flag=0,retailer_creation=1,discount_kit=null WHERE id = " . $shop['id']);
				}

				if(!empty($this->data['kit'])){
					$this->Retailer->query("INSERT INTO distributors_kits (distributor_id,kits,amount,note,timestamp) VALUES (".$shop['id'].",".$this->data['kit'].",".$this->data['amount'].",'".addslashes($this->data['note'])."','".date('Y-m-d H:i:s')."')");
				}
				else {
					$this->Retailer->query("INSERT INTO distributors_kits (distributor_id,amount,note,timestamp) VALUES (".$shop['id'].",".$this->data['amount'].",'".addslashes($this->data['note'])."','".date('Y-m-d H:i:s')."')");
				}

				$mail_body = "MasterDistributor: " . $this->info['company'] . " transferred kits " . $this->data['kit'] . " to Distributor: " . $shop['company'] . " in Rs. " . $this->data['amount'];
				if(!empty($this->data['note'])){
					$mail_body .= "<br/>Note: ".$this->data['note'];
				}

				$dist_data = $this->General->getUserDataFromId($shop['user_id']);
				$shop['mobile'] = $dist_data['mobile'];
//				$msg = "Dear Distributor,\nYour account is successfully credited with " . $this->data['kit']. "kits";

                                $paramdata['KIT_DATA'] = $this->data['kit'];
                                $MsgTemplate = $this->General->LoadApiBalance();
                                $content=  $MsgTemplate['TransferKits_MSG'];

				if($kits != -1){
//					$msg .= "\nYou have total $kits now";
                                        $paramdata['TOTAL_KITS'] = $kits;
                                        $content =  $MsgTemplate['Transfer_TotalKits_MSG'];
				}
                                $msg = $this->General->ReplaceMultiWord($paramdata,$content);
				$this->General->sendMessage($shop['mobile'],$msg,'shops');
				$this->General->sendMails($mail_subject, $mail_body,array('distributor.care@pay1.in','limits@pay1.in'),'mail');

				$this->render('/elements/shop_kits_transfer','ajax');
			}
		}

		if(!$to_save){
			$this->set('data',$this->data);
			$msg = '<div class="error_class">'.$msg.'</div>';
			$this->Session->setFlash(__($msg, true));
			$this->render('/elements/shop_kits_transfer','ajax');
		}
	}


	function backKitTransfer(){
		$this->set('data',$this->data);
		$this->render('/elements/shop_kits_transfer');
	}


	function logout(){

		$this->Auth->logout();
		session_destroy();
		$this->redirect('/shops');
	}

	/*function updateTransactionPP(){
		$TransactionId = $_REQUEST['TransactionId'];
		$TransactionType = $_REQUEST['TransactionType'];
		$MobileNo = $_REQUEST['MobileNo'];
		$CustomerNo = $_REQUEST['CustomerNo'];
		$Amount = $_REQUEST['Amount'];
	}*/

	/*function lastTransactions($page = null){
		if($page == null)$page = 1;
		$ret = $this->Shop->getLastTransactions(null,$page);
		//$this->printArray($ret);
	}*/

//	function initializeOpeningBalance(){//initialize balance once everyday at 11:30PM
//		$this->SuperDistributor->updateAll(array('SuperDistributor.opening_balance' => 'SuperDistributor.balance'));
//		$this->Distributor->updateAll(array('Distributor.opening_balance' => 'Distributor.balance'));
//		$this->Retailer->updateAll(array('Retailer.opening_balance' => 'Retailer.balance'));
//		$this->autoRender = false;
//	}



        /*function products(){
		$this->render('products','xml/default');
	}

	function createCommissionTemplate(){
		$slab_id = 3;
		$percents = array(3,1.9,3.5,2,3.5,4.5,3,3,2.5,2.5,3.5,4,3.5,3.5,2,2,3.3,2.7,3.5,3.3,4.2);
		$products = $this->Retailer->query("SELECT id from products where active = 1");
		$i = 0;
		foreach($products as $prod){

			$this->Retailer->query("INSERT INTO slabs_products (slab_id,product_id,percent) VALUES (".$slab_id.",".$prod['products']['id'].",".$percents[$i].")");
			$i++;
		}
		$this->autoRender = false;
	}*/


	/*function genaric_match($template,$string,$varStart="{{",$varEnd="}}"){


		$template = str_replace($varStart,"|~|`",$template);
		$template = str_replace($varEnd,"`|~|",$template);

		$t=explode("|~|",$template);

		$temp="";
		$i=0;
		foreach ($t as $n=>$v){
			$i++;
			if (($i==count($t)||($i==(count($t)-1)&&$t[$n+1]==""))&&substr($v,0,1)=="`"&&substr($v,-1)=="`"){
				//Last Item
				$temp.="(?P<".substr($v,1,-1).">.++)";

			}elseif(substr($v,0,1)=="`"&&substr($v,-1)=="`"){
				//Search Item
				$temp.="(?P<".substr($v,1,-1).">[^".$t[$n+1]."]++)";

			}else{
				$temp.=$v;
			}

		}
		$temp="~^".$temp."$~";
		echo $temp . "<br/>";
		echo $string . "<br/>";

		preg_match($temp, $string, $matches);

		return $matches;

	}

	function test(){
		$array = array(1,2,3,4,5,6,7,8,9,10);
		$array = array_slice($array,3*3,3);
		print_r($array);
		$text = '<?xml version="1.0" encoding="UTF-8"?>
<Response>
<Status>0</Status>
<StatusText>SUCCESS</StatusText>
<Ticket>
<Status>0</Status>
<StatusText>SUCCESS</StatusText>
<DrawID>7</DrawID>
<TSN>6844-3DA1-E679-9CBF</TSN>
<DrawDate>22/07/2013 21:15:00</DrawDate>
<SLSN>200107556</SLSN>
<BetDate>07/16/2013 12:19</BetDate>
<GameName>Keno,Monday,.,,</GameName>
<Cost>20.00</Cost>
<Mrp>10.00</Mrp>
<Panel>
<LP>0</LP>
<BetLine>A: 01 02 03 02</BetLine>
</Panel>
<Promotion/>
</Ticket>
<CardBalance>14730.00</CardBalance>
</Response>';

		$arr = $this->General->xml2array($text);
		$this->printArray($arr);exit;
		echo "1";
		$this->autoRender = false;
		exit;

		echo "1";exit;
		$this->General->sendMessageViaInfobip('',array('9819032643','9833032643'),'hello');exit;
		$this->General->mailToUsers('test','test',array('ashish@pay1.in'));
		exit;
		$this->General->sendMessage('','9892609560,9819852204,9004387418','hello');
		exit;
	}

	function script(){
		$this->Invoice->recursive = -1;
		$invoices = $this->Invoice->find('all');
		foreach($invoices as $invoice){
			if($invoice['Invoice']['group_id'] == MASTER_DISTRIBUTOR){
				$parent = null;
			}
			else if($invoice['Invoice']['group_id'] == DISTRIBUTOR || $invoice['Invoice']['group_id'] == RETAILER){
				$shop = $this->Shop->getShopDataById($invoice['Invoice']['ref_id'],$invoice['Invoice']['group_id']);
				$parent = $shop['parent_id'];
			}
			$this->Invoice->updateAll(array('Invoice.from_id' => $parent),array('Invoice.id' => $invoice['Invoice']['id']));
		}
		$this->autoRender = false;
	}

	function issue(){
		$id = $_REQUEST['id'];
		$type = $_REQUEST['type'];
		$child = $_REQUEST['child'];

		if($type == RECEIPT_TOPUP){
			$number = $this->Shop->getTopUpReceiptNumber($id);
		}
		else if($type == RECEIPT_INVOICE){
			$invoice = $this->Invoice->find('first',array('fields' => array('Invoice.timestamp','Invoice.invoice_number'), 'conditions' => array('Invoice.id' => $id)));
			$number = $invoice['Invoice']['invoice_number'];
		}
		$this->set('number',$number);
		$this->set('type',$type);
		$this->set('id',$id);
		$this->set('child',$child);
		$this->render('receipt_form','ajax');
	}*/

	function graphRetailer(){
		$type = $_REQUEST['type'];
		$id = $_REQUEST['id'];
		if(empty($_REQUEST['from'])){
			$from = date('Y-m-d',strtotime('-30 days'));
			$to = date('Y-m-d');
		}
		else {
			$from = $_REQUEST['from'];
			$to = $_REQUEST['to'];
			if(checkdate(substr($from,2,2), substr($from,0,2), substr($from,4)) && checkdate(substr($to,2,2), substr($to,0,2), substr($to,4))){
				$from =  substr($from,4) . "-" . substr($from,2,2) . "-" . substr($from,0,2);
				$to =  substr($to,4) . "-" . substr($to,2,2) . "-" . substr($to,0,2);
			}
			else {
				$from = date('Y-m-d',strtotime('-30 days'));
				$to = date('Y-m-d');
			}
		}

		if($this->Session->read('Auth.User.group_id') != ADMIN && $this->Session->read('Auth.User.group_id') != MASTER_DISTRIBUTOR && $this->Session->read('Auth.User.group_id') != DISTRIBUTOR && $this->Session->read('Auth.User.group_id') != RELATIONSHIP_MANAGER && $this->Session->read('Auth.User.group_id') != CUSTCARE)$this->redirect('/shops/view');

		if($type == 'r'){
			if(!empty($id)){
				if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
					$data = $this->Shop->getShopDataById($id,RETAILER);
					if($data['parent_id'] != $this->info['id'])exit;
				}
				else if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){
					$data = $this->Shop->getShopDataById($id,RETAILER);
					$data1 = $this->Shop->getShopDataById($data['parent_id'],DISTRIBUTOR);
					if($data1['parent_id'] != $this->info['id']){
						exit;
					}
				}
			}
			else exit;

			$topup = $this->Slaves->query("SELECT date,topup_buy FROM users_logs as retailers_logs WHERE user_id  = ".$this->Session->read('Auth.User.id')." AND date >= '$from' AND date <= '$to'");
			$sale = $this->Slaves->query("SELECT SUM(rel.amount) as sale,rel.date "
                                . "FROM retailer_earning_logs rel "
                                . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                                . "WHERE r.id = $id AND rel.date >= '$from' AND rel.date <= '$to'"
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "GROUP BY rel.date");

			$begin = new DateTime($from);
			$end = new DateTime($to);

			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);

			$data = array();
			$totSale = 0;
			$i = 0;

			$saleData = array();

			foreach($topup as $tp){
				$saleData[$tp['retailers_logs']['date']]['topup'] = $tp['retailers_logs']['topup'];
			}
                        
			foreach($sale as $sl){
				$saleData[$sl['rel']['date']]['sale'] = $sl[0]['sale'];
			}

			foreach ($period as $dt){
				$data1 = array();
				$date = $dt->format("Y-m-d");
				if(isset($saleData[$date])){
					if(isset($saleData[$date]['sale'])){
						$totSale += $saleData[$date]['sale'];
						$sale = intval($saleData[$date]['sale']);
					}
					else {
						$sale = 0;
					}

					if(isset($saleData[$date]['topup'])){
						$topup = intval($saleData[$date]['topup']);
					}
					else {
						$topup = 0;
					}
					$data[] = array($date,$sale,$topup);
				}
				else {
					$data[] = array($date,0,0);
				}

				$i++;
			}

			$avg = intval($totSale/$i);
			$i=0;
			foreach($data as $dt){
				$data[$i][3]=$avg;
				$i++;
			}
			$graphData = array(
			   'labels' => array(
			array('string' => 'Sample'),
			array('number' => 'Daily Sale'),
			array('number' => 'Daily Topup'),
			array('number' => 'Average Sale')
			),
			   'data' => $data,
			   'title' => 'Retailer Sale Report',
			   'type' => 'line',
			   'width' => '1200',
			   'height'=>'500'
			   );

		}
		else if($type == 'd'){
			if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
				$id = $this->info['id'];
			}

			if(!empty($id)){
				if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){
					$data = $this->Shop->getShopDataById($id,DISTRIBUTOR);
					if($data['parent_id'] != $this->info['id']){
						exit;
					}
				}

//				$sale = $this->Slaves->query("SELECT sum(sale) as amts,date FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id  = $id AND date >= '$from' AND date <= '$to' GROUP by date order by date");
				$sale = $this->Slaves->query("SELECT SUM(rel.amount) as amts,rel.date "
                                        . "FROM retailer_earning_logs rel "
                                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id)"
                                        . "JOIN distributors d ON (rel.dist_user_id = d.user_id)  "
                                        . "WHERE d.id  = $id "
                                        . "AND rel.date >= '$from' "
                                        . "AND rel.date <= '$to' "
                                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY rel.date "
                                        . "ORDER BY rel.date");
				$dist_data = $this->Slaves->query("SELECT sum(topup_buy) as topup_buy,sum(topup_sold) as topup_sold,sum(topup_unique) as topup_unique,sum(retailers) as retailers,sum(transacting) as transacting,date FROM users_logs as distributors_logs join distributors ON (distributors.user_id = distributors_logs.user_id) WHERE distributors.id = $id AND date >= '$from' AND date <= '$to' group by date order by date");
//				$averageResult=$this->Slaves->query("SELECT retailers_logs.sale,retailers_logs.date,retailers_logs.retailer_id from retailers_logs,retailers WHERE retailers.parent_id = $id AND retailers.id = retailers_logs.retailer_id AND retailers_logs.date >= '$from' AND retailers_logs.date <= '$to'");
				$averageResult = $this->Slaves->query("SELECT SUM(rel.amount) as sale,rel.date,r.id as retailer_id "
                                        . "FROM retailer_earning_logs rel "
                                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id)"
                                        . "JOIN distributors d ON (rel.dist_user_id = d.user_id)  "
                                        . "WHERE d.id = $id "
                                        . "AND rel.date >= '$from' "
                                        . "AND rel.date <= '$to' "
                                        . "AND rel.service_id IN (1,2,4,5,6,7)");
			}
			else if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){
//				$sale = $this->Slaves->query("SELECT sum(sale) as amts,date FROM retailers_logs,retailers WHERE retailers.id = retailers_logs.retailer_id AND date >= '$from' AND date <= '$to' GROUP by date order by date");
				$sale = $this->Slaves->query("SELECT SUM(rel.amount) as amts,rel.date "
                                        . "FROM retailer_earning_logs rel "
                                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                                        . "WHERE rel.date >= '$from' "
                                        . "AND rel.date <= '$to' "
                                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY rel.date "
                                        . "ORDER BY rel.date");
				$dist_data = $this->Slaves->query("SELECT sum(topup_buy) as topup_buy,sum(topup_sold) as topup_sold,sum(topup_unique) as topup_unique,sum(retailers) as retailers,sum(transacting) as transacting,date FROM users_logs as distributors_logs FORCE INDEX (date) join distributors ON (distributors.user_id = distributors_logs.user_id) WHERE date >= '$from' AND date <= '$to' group by date order by date");
//				$averageResult=$this->Slaves->query("SELECT retailers_logs.sale,retailers_logs.date,retailers_logs.retailer_id from retailers_logs WHERE retailers_logs.date >= '$from' AND retailers_logs.date <= '$to'");
				$averageResult=$this->Slaves->query("SELECT SUM(rel.amount) AS sale,rel.date,r.id AS retailer_id "
                                        . "FROM retailer_earning_logs rel "
                                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                                        . "WHERE rel.date >= '$from' "
                                        . "AND rel.date <= '$to' "
                                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY r.id,rel.date");
			}

			$begin = new DateTime($from);
			$end = new DateTime($to);

			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);

			$data = array();
			$data0 = array();
			$data1 = array();
			$data2 = array();

			$totSale = 0;
			$totSale_sec = 0;
			$i = 0;
			$avgSale = 0;
			$avgSale_sec = 0;

			$saleData = array();

			foreach($sale as $sl){
				$saleData[$sl['rel']['date']]['sale'] = $sl['0']['amts'];
				$totSale += $sl['0']['amts'];
				$i++;
			}

			if($i>0)$avgSale = intval($totSale/$i);
			$i = 0;
			$retData = array();

			foreach($dist_data as $tp){
				$saleData[$tp['distributors_logs']['date']]['topup'] = $tp['0']['topup_buy'];
				$saleData[$tp['distributors_logs']['date']]['topup_sec'] = $tp['0']['topup_sold'];
				$totSale_sec += $tp['0']['topup_sold'];
				$i++;

				$retData[$tp['distributors_logs']['date']]['retailers'] = $tp['0']['retailers'];
				$retData[$tp['distributors_logs']['date']]['transacting'] = $tp['0']['transacting'];
				$retData[$tp['distributors_logs']['date']]['topups'] = $tp['0']['topup_unique'];
			}

			if($i>0)$avgSale_sec = intval($totSale_sec/$i);


			foreach($averageResult as $sl){
				if($sl[0]['sale'] >= 1000){
					if(isset($retData[$sl['rel']['date']]['plus1000'])){
						$retData[$sl['rel']['date']]['plus1000'] += 1;
					}
					else {
						$retData[$sl['rel']['date']]['plus1000'] = 1;
					}
				}
				else if($sl[0]['sale'] >= 500 && $sl[0]['sale'] < 1000){
					if(isset($retData[$sl['rel']['date']]['plus500'])){
						$retData[$sl['rel']['date']]['plus500'] += 1;
					}
					else {
						$retData[$sl['rel']['date']]['plus500'] = 1;
					}
				}
				else {
					if(isset($retData[$sl['rel']['date']]['less500'])){
						$retData[$sl['rel']['date']]['less500'] += 1;
					}
					else {
						$retData[$sl['rel']['date']]['less500'] = 1;
					}
				}
			}

			foreach ($period as $dt){
				$date = $dt->format("Y-m-d");
				if(isset($saleData[$date])){
					if(isset($saleData[$date]['sale'])){
						$sale = intval($saleData[$date]['sale']);
					}
					else {
						$sale = 0;
					}

					if(isset($saleData[$date]['topup'])){
						$topup = intval($saleData[$date]['topup']);
					}
					else {
						$topup = 0;
					}

					if(isset($saleData[$date]['topup_sec'])){
						$topup_s = intval($saleData[$date]['topup_sec']);
					}
					else {
						$topup_s = 0;
					}
					$data[] = array($date,$topup,$topup_s,$avgSale_sec);
					$data0[] = array($date,$sale,$avgSale);
				}
				else {
					$data[] = array($date,0,0,0);
					$data0[] = array($date,0,0);
				}

				$retBefore = 0;
				if(isset($retData[$date])){
					if(isset($retData[$date]['retailers'])){
						$retBefore = $retData[$date]['retailers'];
					}
					$ret = $retBefore;

					if(isset($retData[$date]['transacting'])){
						$trans = $retData[$date]['transacting'];
					}
					else {
						$trans = 0;
					}

					if(isset($retData[$date]['topups'])){
						$topups = $retData[$date]['topups'];
					}
					else {
						$topups = 0;
					}

					if(isset($retData[$date]['plus1000'])){
						$plus1000 = $retData[$date]['plus1000'];
					}
					else {
						$plus1000 = 0;
					}

					if(isset($retData[$date]['plus500'])){
						$plus500 = $retData[$date]['plus500'];
					}
					else {
						$plus500 = 0;
					}

					if(isset($retData[$date]['less500'])){
						$less500 = $retData[$date]['less500'];
					}
					else {
						$less500 = 0;
					}

					$data1[] = array($date,$ret,$trans,$topups);
					$data2[] = array($date,$plus1000,$plus500,$less500);
				}
				else {
					$data1[] = array($date,$retBefore,0,0);
					$data2[] = array($date,0,0,0);
				}
				$i++;
			}

			$graphData = array(
			   'labels' => array(
			array('string' => 'Sample'),
			array('number' => 'Daily Topup (Primary)'),
			array('number' => 'Daily Sale (Secondary)'),
			array('number' => 'Average Sale (Secondary)')
			),
			   'data' => $data,
			   'title' => 'Distributor Sale Report',
			   'type' => 'line',
			   'width' => '1200',
			   'height'=>'500'
			   );

			   $graphData0 = array(
			   'labels' => array(
			   array('string' => 'Sample'),
			   array('number' => 'Daily Sale (Tertiary)'),
			   array('number' => 'Average Sale (Tertiary)')
			   ),
			   'data' => $data0,
			   'title' => 'Retailers Sale Report',
			   'type' => 'line',
			   'width' => '1200',
			   'height'=>'500'
			   );

			   $graphData1 = array(
			   'labels' => array(
			   array('string' => 'Sample'),
			   array('number' => 'Total Retailers'),
			   array('number' => 'Transacting Retailers'),
			   array('number' => 'Daily Unique Retailer topups')
			   ),
			   'data' => $data1,
			   'title' => 'Distributor Retailers Report',
			   'type' => 'line',
			   'width' => '1200',
			   'height'=>'500'
			   );

			   $graphData2 = array(
			   'labels' => array(
			   array('string' => 'Sample'),
			   array('number' => 'Retailers (Sale >= 1000)'),
			   array('number' => 'Retailers (500 <= Sale < 1000)'),
			   array('number' => 'Retailers (Sale < 500)')
			   ),
			   'data' => $data2,
			   'title' => 'Retailer Performance Report',
			   'type' => 'line',
			   'width' => '1200',
			   'height'=>'500'
			   );

			   $this->set('data0',$graphData0);
			   $this->set('data1',$graphData1);
			   $this->set('data2',$graphData2);
		}
		//echo json_encode($graphData);
		$this->set('data',$graphData);
		$this->set('type',$type);
		$this->set('id',$id);
		$this->set('from',$from);
		$this->set('to',$to);
	}

        function kitReport()
        {
                $data=$this->Slaves->query("select service_plans.plan_name as plan,distributors_kits.kits,services.name from distributors_kits
                                    join services on distributors_kits.service_id = services.id
                                    LEFT JOIN service_plans ON(distributors_kits.service_plans_id = service_plans.id)
                                    where distributors_kits.distributor_id = '".$this->Session->read('Auth.id')."' ");
                $this->set('data',$data) ;
        }

	function mainReport($id = null)
	{
		$show = false;
		if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
			$distid = $this->info['id'];
			$show = true;
			$this->set('name',$this->info['company']);
		}
		else if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){
			$sdistid = $this->info['id'];
			if(!empty($id)){
				$shop = $this->Shop->getShopDataById($id,DISTRIBUTOR);
				if($shop['parent_id'] == $this->info['id'] || $shop['parent_id'] == $this->info['master_dist_id']){
					$show = true;
					$this->set('name',$shop['company']);
					$distid = $id;
					$this->set('dist',$distid);
				}
			}
			else {
				$this->set('name','All Distributors');
				$show = true;
			}
		}else if($_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){
			$sdistid = $this->info['id'];
			if(!empty($id)){
				$shop = $this->Shop->getShopDataById($id,DISTRIBUTOR);
				if($shop['parent_id'] == $this->info['id'] || $shop['parent_id'] == $this->info['master_dist_id']){
					$show = true;
					$this->set('name',$shop['company']);
					$distid = $id;
					$this->set('dist',$distid);
				}
			}
			else {
				$this->set('name','All Distributors');
				$show = true;
			}
		}
		else if($_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER){
			$rmid = $this->info['id'];
			$sdistid = $this->info['master_dist_id'];
			if(!empty($id)){
				$shop = $this->Shop->getShopDataById($id,DISTRIBUTOR);
				if($shop['rm_id'] == $this->info['id'] && $shop['parent_id'] == $this->info['master_dist_id']){
					$show = true;
					$this->set('name',$shop['company']);
					$distid = $id;
					$this->set('dist',$distid);
				}
			}
			else {
                                $this->set('name','All Distributors');
				$show = true;
			}
		}
		else if($_SESSION['Auth']['User']['group_id'] == ADMIN){
			if(!empty($id)){
				$shop = $this->Shop->getShopDataById($id,MASTER_DISTRIBUTOR);
				if(!empty($shop)){
					$sdistid = $id;
					$this->set('name',$shop['company']);
					$show = true;
					$this->set('dist',$sdistid);
				}
			}
			else {
				$this->set('name','All MasterDistributors');
				$show = true;
			}
		}

		if($show){
			$today = date('Y-m-d');
			$datas = array();

			if(!isset($sdistid) && !isset($distid)){//Admin with all SD's
				$data_buy = $this->Slaves->query("SELECT sum(shop_transactions.amount) as amts FROM shop_transactions WHERE shop_transactions.confirm_flag != 1 AND ((shop_transactions.type = ".MDIST_DIST_BALANCE_TRANSFER.") OR (shop_transactions.type = ".COMMISSION_DISTRIBUTOR.")) AND shop_transactions.date = '$today'");
//				$data_buy1 = $this->Slaves->query("SELECT sum(topup_buy) as amts FROM users_logs JOIN distributors ON (users_logs.user_id = distributors.user_id) WHERE users_logs.date = '$today'");
//				$data_buy2 = $this->Slaves->query("SELECT sum(amount-txn_reverse_amt) as amts FROM users_nontxn_logs WHERE type = ".COMMISSION_DISTRIBUTOR." AND date = '$today'");
//                                $data_buy[0][0]['amts'] = $data_buy1[0][0]['amts']+$data_buy2[0][0]['amts'];
                                
				$data_sold = $this->Slaves->query("SELECT sum(amount) as amts, count(distinct target_id) as cts FROM shop_transactions WHERE confirm_flag != 1 AND type IN ('".DIST_RETL_BALANCE_TRANSFER."','".SLMN_RETL_BALANCE_TRANSFER."') AND date = '$today'");
//				$data_sold = $this->Slaves->query("SELECT sum(topup_buy) as amts, count(users_logs.user_id) as cts FROM users_logs JOIN retailers ON (users_logs.user_id = retailers.user_id) WHERE date = '$today'");
                               
				$data_ret = $this->Slaves->query("SELECT count(retailers.id) as cts FROM retailers");
				$data_trans = $this->Slaves->query("SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale FROM vendors_activations as va WHERE va.retailer_id != 13 AND va.status != 2 AND va.status !=3 AND va.date = '$today'");
                                
				//SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale FROM vendors_activations as va,retailers WHERE va.status != 2 AND va.status !=3 AND retailers.id = va.retailer_id AND va.date = '2013-06-10' AND retailers.parent_id=64
				$data_bef = $this->Slaves->query("SELECT sum(topup_buy) as topup_buy,sum(topup_sold) as topup_sold,sum(topup_unique) as topup_unique,sum(retailers) as retailers,sum(transacting) as transacting,date FROM users_logs as distributors_logs FORCE INDEX (date) WHERE date >= '".date('Y-m-d',strtotime('-30 days'))."' AND date < '".date('Y-m-d')."' group by date order by date");
                                
//				$data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs FORCE INDEX (idx_date) WHERE retailers_logs.retailer_id not in (13) AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' group by retailers_logs.date order by retailers_logs.date");
				$data_bef_ret = $this->Slaves->query("SELECT SUM(retailers_logs.amount) AS sale,COUNT(DISTINCT retailers_logs.ret_user_id) AS transacting,retailers_logs.date "
                                        . "FROM retailer_earning_logs retailers_logs "
                                        . "JOIN retailers r ON (retailers_logs.ret_user_id = r.user_id) "
                                        . "WHERE r.id NOT IN (13) "
                                        . "AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."'"
                                        . "AND retailers_logs.date < '".date('Y-m-d')."' "
                                        . "AND retailers_logs.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY retailers_logs.date "
                                        . "ORDER BY retailers_logs.date");
			}
			else if(isset($sdistid) && !isset($distid)){//SD with all distributors or RM with all distributors or Admin with a SD
				if(isset($rmid))$extra = " AND distributors.rm_id = $rmid AND distributors.active_flag = '1'";
				else $extra = " AND distributors.parent_id = $sdistid AND distributors.active_flag = '1'";

                                if(isset($rmid)) {
                                        $data_buy = $this->Slaves->query("SELECT sum(st.amount) amts FROM shop_transactions st JOIN distributors ON (st.target_id = distributors.id) WHERE st.type = ".MDIST_DIST_BALANCE_TRANSFER." AND st.confirm_flag != 1 AND distributors.rm_id = '$rmid' AND st.date = '$today'");
//                                        $data_buy = $this->Slaves->query("SELECT sum(st.topup_buy) amts FROM users_logs st JOIN distributors ON (st.user_id = distributors.user_id) WHERE distributors.rm_id = '$rmid' AND st.date = '$today'");
                                } else {
				$data_buy = $this->Slaves->query("SELECT sum(shop_transactions.amount) as amts FROM shop_transactions WHERE shop_transactions.confirm_flag != 1 AND (shop_transactions.type = ".MDIST_DIST_BALANCE_TRANSFER.")  AND source_id = '".$this->info['id']."' and  shop_transactions.date = '$today'");
//				$data_buy = $this->Slaves->query("SELECT sum(shop_transactions.topup_sold) as amts FROM users_logs shop_transactions JOIN master_distributors ON (shop_transactions.user_id = master_distributors.user_id) WHERE master_distributors.id = '".$this->info['id']."' and  shop_transactions.date = '$today'");                                
                                }
				$data_sold_1 = $this->Slaves->query("SELECT sum(shop_transactions.amount) as amts,count(distinct shop_transactions.target_id) as cts FROM shop_transactions FORCE INDEX(type_date) JOIN distributors ON (shop_transactions.source_id = distributors.id) WHERE shop_transactions.confirm_flag != 1 AND shop_transactions.type = ".DIST_RETL_BALANCE_TRANSFER." AND shop_transactions.date = '$today' $extra");
//				$data_sold = $this->Slaves->query("SELECT sum(users_logs.topup_buy) as amts,count(distinct users_logs.user_id) as cts FROM users_logs JOIN retailers ON (users_logs.user_id = retailers.user_id) JOIN distributors ON (users_logs.parent_user_id = distributors.user_id) WHERE users_logs.date = '$today' $extra");
				$data_sold_2 = $this->Slaves->query("SELECT sum(shop_transactions.amount) as amts,count(distinct shop_transactions.target_id) as cts FROM shop_transactions FORCE INDEX(type_date) JOIN salesmen ON (shop_transactions.source_id = salesmen.id) JOIN distributors ON (salesmen.dist_id = distributors.id) WHERE shop_transactions.confirm_flag != 1 AND shop_transactions.type = ".SLMN_RETL_BALANCE_TRANSFER." AND shop_transactions.date = '$today' $extra");
                                $data_sold[0][0] = array('amts'=> $data_sold_1[0][0]['amts']+$data_sold_2[0][0]['amts'],'cts'=>$data_sold_1[0][0]['cts']+$data_sold_2[0][0]['cts']);
				$data_ret = $this->Slaves->query("SELECT count(retailers.id) as cts FROM retailers,distributors WHERE retailers.parent_id = distributors.id $extra");
				$data_trans = $this->Slaves->query("SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale "
                                        . "FROM vendors_activations as va "
                                        . "LEFT JOIN retailers ON (retailers.id = va.retailer_id) "
                                        . "LEFT JOIN distributors ON (distributors.id = retailers.parent_id) "
                                        . "WHERE va.status NOT IN (2,3) AND va.retailer_id != 13 AND va.date = '$today' $extra");
				//SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale FROM vendors_activations as va,retailers,distributors WHERE va.status != 2 AND va.status !=3 AND retailers.id = va.retailer_id AND retailers.parent_id = distributors.id AND va.date = '$today' $extra
				$data_bef = $this->Slaves->query("SELECT sum(distributors_logs.topup_buy) as topup_buy,sum(distributors_logs.topup_sold) as topup_sold,sum(distributors_logs.topup_unique) as topup_unique,sum(distributors_logs.retailers) as retailers,sum(distributors_logs.transacting) as transacting,distributors_logs.date FROM users_logs as distributors_logs,distributors WHERE distributors_logs.user_id  = distributors.user_id $extra AND distributors_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' AND distributors_logs.date < '".date('Y-m-d')."' group by distributors_logs.date order by distributors_logs.date");
//				$data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs,retailers,distributors WHERE retailers.id not in (13) AND retailers.id = retailers_logs.retailer_id AND retailers.parent_id=distributors.id $extra AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' group by retailers_logs.date order by retailers_logs.date");
				$data_bef_ret = $this->Slaves->query("SELECT SUM(retailers_logs.amount) AS sale,COUNT(DISTINCT retailers_logs.ret_user_id) AS transacting,retailers_logs.date "
                                        . "FROM retailer_earning_logs retailers_logs "
                                        . "JOIN retailers ON (retailers_logs.ret_user_id = retailers.user_id) "
                                        . "JOIN distributors ON (retailers_logs.dist_user_id = distributors.user_id) "
                                        . "WHERE retailers.id not in (13) "
                                        . "$extra "
                                        . "AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' "
                                        . "AND retailers_logs.date < '".date('Y-m-d')."' "
                                        . "AND retailers_logs.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY retailers_logs.date "
                                        . "ORDER BY retailers_logs.date");
			}
			else if(isset($sdistid) && isset($distid)){ //SD with a distributor OR RM with a distributor
				$extra = "";
				if(isset($rmid))$extra = " AND distributors.rm_id = $rmid";
				$data_buy = $this->Slaves->query("SELECT sum(shop_transactions.amount) as amts FROM shop_transactions inner join distributors ON (distributors.id = shop_transactions.target_id)  WHERE shop_transactions.confirm_flag != 1 AND shop_transactions.type = ".MDIST_DIST_BALANCE_TRANSFER." AND shop_transactions.source_id = $sdistid AND shop_transactions.target_id = $distid AND distributors.parent_id = shop_transactions.source_id $extra  AND shop_transactions.date = '$today'");
//				$data_buy = $this->Slaves->query("SELECT sum(topup_buy) as amts FROM users_logs inner join distributors ON (distributors.user_id = users_logs.user_id) inner join master_distributors on (users_logs.parent_user_id = master_distributors.user_id) WHERE master_distributors.id = $sdistid AND distributors.id = $distid $extra  AND users_logs.date = '$today'");
				$data_sold_1 = $this->Slaves->query("SELECT sum(shop_transactions.amount) as amts,count(distinct shop_transactions.target_id) as cts FROM shop_transactions  WHERE shop_transactions.confirm_flag != 1 AND shop_transactions.type = ".DIST_RETL_BALANCE_TRANSFER." AND shop_transactions.date = '$today' AND shop_transactions.source_id = $distid");
//				$data_sold = $this->Slaves->query("SELECT sum(users_logs.topup_buy) as amts,count(distinct users_logs.user_id) as cts FROM users_logs JOIN retailers ON (users_logs.user_id = retailers.user_id) JOIN distributors ON (users_logs.parent_user_id = distributors.user_id) WHERE users_logs.date = '$today' AND distributors.id = $distid");
				$data_sold_2 = $this->Slaves->query("SELECT sum(shop_transactions.amount) as amts,count(distinct shop_transactions.target_id) as cts FROM shop_transactions JOIN salesmen ON (shop_transactions.source_id = salesmen.id) WHERE shop_transactions.confirm_flag != 1 AND shop_transactions.type = '".SLMN_RETL_BALANCE_TRANSFER."' AND shop_transactions.date = '$today' AND salesmen.dist_id = '$distid'");
                                $data_sold[0][0] = array('amts'=> $data_sold_1[0][0]['amts']+$data_sold_2[0][0]['amts'],'cts'=>$data_sold_1[0][0]['cts']+$data_sold_2[0][0]['cts']);
				$data_ret = $this->Slaves->query("SELECT count(retailers.id) as cts FROM retailers WHERE retailers.parent_id = $distid");
				$data_trans = $this->Slaves->query("SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale FROM vendors_activations as va,retailers WHERE va.retailer_id != 13 AND va.status != 2 AND va.status !=3 AND retailers.id = va.retailer_id AND va.date = '$today' AND retailers.parent_id=$distid");
				//SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale FROM vendors_activations as va,retailers WHERE va.status != 2 AND va.status !=3 AND retailers.id = va.retailer_id AND va.date = '2013-06-10' AND retailers.parent_id=$distid

				$data_bef = $this->Slaves->query("SELECT sum(distributors_logs.topup_buy) as topup_buy,sum(distributors_logs.topup_sold) as topup_sold,sum(distributors_logs.topup_unique) as topup_unique,sum(distributors_logs.retailers) as retailers,sum(distributors_logs.transacting) as transacting,distributors_logs.date FROM users_logs as distributors_logs join distributors ON (distributors.user_id=distributors_logs.user_id) WHERE distributors.id = $distid AND distributors_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' AND distributors_logs.date < '".date('Y-m-d')."'  group by distributors_logs.date order by distributors_logs.date");
//				$data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs,retailers WHERE retailers.id not in (13) AND retailers.id = retailers_logs.retailer_id AND retailers.parent_id= $distid AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' group by retailers_logs.date order by retailers_logs.date");
				$data_bef_ret = $this->Slaves->query("SELECT SUM(retailers_logs.amount) AS sale,COUNT(DISTINCT retailers_logs.ret_user_id) AS transacting,retailers_logs.date "
                                        . "FROM retailer_earning_logs retailers_logs "
                                        . "JOIN retailers r ON (retailers_logs.ret_user_id = r.user_id)"
                                        . "JOIN distributors d ON (retailers_logs.dist_user_id = d.user_id)  "
                                        . "WHERE r.id not in (13) "
                                        . "AND d.id = $distid "
                                        . "AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' "
                                        . "AND retailers_logs.date < '".date('Y-m-d')."' "
                                        . "AND retailers_logs.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY retailers_logs.date "
                                        . "ORDER BY retailers_logs.date");
			}
			else if(!isset($sdistid) && isset($distid)){//Distributor
				$data_buy = $this->Slaves->query("SELECT sum(shop_transactions.amount) as amts FROM shop_transactions WHERE shop_transactions.confirm_flag != 1 AND ((shop_transactions.type = ".MDIST_DIST_BALANCE_TRANSFER." AND shop_transactions.target_id = $distid) OR (shop_transactions.type = ".COMMISSION_DISTRIBUTOR." AND shop_transactions.source_id = $distid)) AND shop_transactions.date = '$today'");
//                                $data_buy1 = $this->Slaves->query("SELECT sum(topup_buy) as amts FROM users_logs JOIN distributors ON (users_logs.user_id = distributors.user_id) WHERE distributors.id = $distid AND users_logs.date = '$today'");
//				$data_buy2 = $this->Slaves->query("SELECT sum(amount-txn_reverse_amt) as amts FROM users_nontxn_logs WHERE distributors.id = $distid AND type = ".COMMISSION_DISTRIBUTOR." AND date = '$today'");
//                                $data_buy[0][0]['amts'] = $data_buy1[0][0]['amts']+$data_buy2[0][0]['amts'];
                                $data_sold_1 = $this->Slaves->query("SELECT sum(shop_transactions.amount) as amts,count(distinct shop_transactions.target_id) as cts FROM shop_transactions  WHERE shop_transactions.confirm_flag != 1 AND shop_transactions.type = ".DIST_RETL_BALANCE_TRANSFER." AND shop_transactions.date = '$today' AND shop_transactions.source_id = $distid");
                                $data_sold_2 = $this->Slaves->query("SELECT sum(shop_transactions.amount) as amts,count(distinct shop_transactions.target_id) as cts FROM shop_transactions JOIN salesmen ON (shop_transactions.source_id = salesmen.id) WHERE shop_transactions.confirm_flag != 1 AND shop_transactions.type = ".SLMN_RETL_BALANCE_TRANSFER." AND shop_transactions.date = '$today' AND salesmen.dist_id = $distid");
//                                $data_sold = $this->Slaves->query("SELECT sum(users_logs.topup_buy) as amts,count(distinct users_logs.user_id) as cts FROM users_logs JOIN retailers ON (users_logs.user_id = retailers.user_id) JOIN distributors ON (users_logs.parent_user_id = distributors.user_id) WHERE users_logs.date = '$today' AND distributors.id = $distid");
                                $data_sold[0][0] = array('amts'=> $data_sold_1[0][0]['amts']+$data_sold_2[0][0]['amts'],'cts'=>$data_sold_1[0][0]['cts']+$data_sold_2[0][0]['cts']);
				$data_ret = $this->Slaves->query("SELECT count(retailers.id) as cts FROM retailers WHERE retailers.parent_id = $distid");
				$data_trans = $this->Slaves->query("SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale FROM vendors_activations as va,retailers WHERE va.retailer_id != 13 AND va.status != 2 AND va.status !=3 AND retailers.id = va.retailer_id AND va.date = '$today' AND retailers.parent_id=$distid");
				//SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale FROM vendors_activations as va,retailers WHERE va.status != 2 AND va.status !=3 AND retailers.id = va.retailer_id AND va.date = '$today' AND retailers.parent_id=$distid

				$data_bef = $this->Slaves->query("SELECT sum(distributors_logs.topup_buy) as topup_buy,sum(distributors_logs.topup_sold) as topup_sold,sum(distributors_logs.topup_unique) as topup_unique,sum(distributors_logs.retailers) as retailers,sum(distributors_logs.transacting) as transacting,distributors_logs.date FROM users_logs as distributors_logs join distributors ON (distributors.user_id=distributors_logs.user_id) WHERE distributors.id = $distid AND distributors_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' AND distributors_logs.date < '".date('Y-m-d')."' group by distributors_logs.date order by distributors_logs.date");
//				$data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs,retailers WHERE retailers.id not in (13) AND retailers.id = retailers_logs.retailer_id AND retailers.parent_id= $distid AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' group by retailers_logs.date order by retailers_logs.date");
				$data_bef_ret = $this->Slaves->query("SELECT SUM(retailers_logs.amount) AS sale,COUNT(DISTINCT retailers_logs.ret_user_id) AS transacting,retailers_logs.date "
                                        . "FROM retailer_earning_logs retailers_logs "
                                        . "JOIN retailers r ON (retailers_logs.ret_user_id = r.user_id)"
                                        . "JOIN distributors d ON (retailers_logs.dist_user_id = d.user_id)  "
                                        . "WHERE r.id not in (13) "
                                        . "AND d.id = $distid "
                                        . "AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."'"
                                        . "AND retailers_logs.date < '".date('Y-m-d')."'  "
                                        . "AND retailers_logs.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY retailers_logs.date "
                                        . "ORDER BY retailers_logs.date");
                                        }

			foreach($data_buy as $dt){
				$datas['buy'] = $dt['0']['amts'];
			}

			foreach($data_sold as $dt){
				$datas['sold'] = $dt['0']['amts'];
				$datas['unique'] = $dt['0']['cts'];
			}

			foreach($data_ret as $dt){
				$datas['retailers'] = $dt['0']['cts'];
			}

			foreach($data_trans as $dt){
				$datas['transacting'] = $dt['0']['cts'];
				$datas['sale'] = $dt['0']['sale'];
				$datas['percent_trans'] = $datas['retailers']==0 ? 0 : intval($datas['transacting']*100/$datas['retailers']);
				$datas['sale_avg_ret'] = empty($datas['transacting'])? 0 : intval($datas['sale']/$datas['transacting']);
				$datas['sale_avg'] = intval($datas['sale']);
			}



			foreach($data_bef_ret as $dt){
				$tertiarysale[$dt['retailers_logs']['date']] = array("sale" => $dt[0]['sale'],"transacting"=>$dt[0]['transacting']);
			}


			$yest_date = date('Y-m-d',strtotime('-1 days'));
			$week_date = date('Y-m-d',strtotime('-7 days'));
			$month_date = date('Y-m-d',strtotime('-30 days'));

			$datas_before = array();
			$ret_before=0;
			$ret_tot = 0;
			$datas_before['week']['new'] = 0;
			$datas_before['month']['new'] = 0;
			$i=0;
			$j=0;

			$datatopupsold = array();
			$datatransRetailer = array();
			$datanewOutlets = array();

		   foreach($data_bef as $dt){
				$datatopupsold[] = array($dt['distributors_logs']['date'],(isset($dt[0]['topup_sold'])?$dt[0]['topup_sold']:0),(isset($dt[0]['topup_buy'])?$dt[0]['topup_buy']:0),(isset($tertiarysale[$dt['distributors_logs']['date']]['sale']) ?$tertiarysale[$dt['distributors_logs']['date']]['sale'] : 0 ));
				$datatransRetailer[] = array($dt['distributors_logs']['date'],intval($dt['0']['transacting']));
				if($dt['distributors_logs']['date']!=$month_date){
				$datanewOutlets[] = array($dt['distributors_logs']['date'],$dt['0']['retailers'] - $ret_before);
				}
				$dataUniqueTopups[] = array($dt['distributors_logs']['date'],$dt['0']['topup_unique']);



				if($dt['distributors_logs']['date'] == $yest_date){
					$datas_before['yesterday']['buy'] = $dt['0']['topup_buy'];
					$datas_before['yesterday']['sold'] = $dt['0']['topup_sold'];
					$datas_before['yesterday']['unique'] = $dt['0']['topup_unique'];
					$datas_before['yesterday']['retailers'] = $dt['0']['retailers'];
					$datas_before['yesterday']['transacting'] = $dt['0']['transacting'];
					$datas_before['yesterday']['new'] = $dt['0']['retailers'] - $ret_before;
					$datas_before['yesterday']['percent_trans'] = intval($dt['0']['transacting']*100/$dt['0']['retailers']);
				}

				if($dt['distributors_logs']['date'] >= $week_date){
					$datas_before['week']['percent_trans'] += $dt['0']['transacting']/$dt['0']['retailers'];
					$datas_before['week']['new'] = $datas_before['week']['new'] + $dt['0']['retailers'] - $ret_before;
					$datas_before['week']['buy'] +=  $dt['0']['topup_buy'];
					$datas_before['week']['sold'] +=  $dt['0']['topup_sold'];
					$datas_before['week']['unique'] +=  $dt['0']['topup_unique'];
					$i++;
				}
				$datas_before['month']['percent_trans'] += $dt['0']['transacting']/$dt['0']['retailers'];
				if($ret_before > 0)$datas_before['month']['new'] = $datas_before['month']['new'] + $dt['0']['retailers'] - $ret_before;
				$datas_before['month']['buy'] +=  $dt['0']['topup_buy'];
				$datas_before['month']['sold'] +=  $dt['0']['topup_sold'];
				$datas_before['month']['unique'] +=  $dt['0']['topup_unique'];
				$ret_before = $dt['0']['retailers'];
				$j++;
			}


			$datatopupsold[] = array(date('Y-m-d'),(isset($datas['sold'])?$datas['sold']:0),(isset($datas['buy'])?$datas['buy']:0),(isset($datas['sale']) ? $datas['sale'] : 0));

			$datatransRetailer[] = array(date('Y-m-d'),intval($datas['transacting']));
			//$datanewOutlets[] = array(date('Y-m-d'),$datas['retailers'] - $ret_before);
			$dataUniqueTopups[] = array(date('Y-m-d'),$datas['unique']);



			$datas_before['week']['percent_trans'] = empty($datas_before['week']['percent_trans'])? 0 : intval($datas_before['week']['percent_trans']*100/$i);
			$datas_before['week']['buy'] = empty($datas_before['week']['buy']) ? 0 : intval($datas_before['week']['buy']/$i);
			$datas_before['week']['sold']= empty($datas_before['week']['sold'])? 0 : intval($datas_before['week']['sold']/$i);
			$datas_before['week']['unique'] = empty($datas_before['week']['unique']) ? 0 : intval($datas_before['week']['unique']/$i);

			$datas_before['month']['percent_trans'] = empty($datas_before['month']['percent_trans'])? 0 : intval($datas_before['month']['percent_trans']*100/$j);
			$datas_before['month']['buy'] = empty($datas_before['month']['buy']) ? 0 : intval($datas_before['month']['buy']/$j);
			$datas_before['month']['sold'] = empty($datas_before['month']['sold']) ? 0 : intval($datas_before['month']['sold']/$j);
			$datas_before['month']['unique'] = empty($datas_before['month']['unique']) ? 0 :intval($datas_before['month']['unique']/$j);

			if(isset($datas_before['yesterday']['retailers'])){
				$datas['new'] = $datas['retailers'] - $datas_before['yesterday']['retailers'];
			}

			$datanewOutlets[] = array(date('Y-m-d'),$datas['new']);
			$i = 0;
			$j = 0;
			$retialerSale = array();

			foreach($data_bef_ret as $dt){
				$retialerSale[] = array($dt['retailers_logs']['date'],$dt[0]['sale']);
				$retailerAvgSale[] = array($dt['retailers_logs']['date'],intval($dt[0]['sale']/$dt[0]['transacting']));
				if($dt['retailers_logs']['date'] == $yest_date){
					$datas_before['yesterday']['sale'] = $dt['0']['sale'];
					$datas_before['yesterday']['sale_avg_ret'] = intval($datas_before['yesterday']['sale']/$datas_before['yesterday']['transacting']);
				}
				if($dt['retailers_logs']['date'] >= $week_date){
					$datas_before['week']['sale'] += isset($dt['0']['sale']) ? $dt['0']['sale'] : 0;
					$datas_before['week']['sale_avg_ret'] += isset($dt['0']['sale']) ? ($dt['0']['sale']/$dt['0']['transacting']) : 0;
					$i++;
				}
				$datas_before['month']['sale'] += $dt['0']['sale'];
				$datas_before['month']['sale_avg_ret'] += $dt['0']['sale']/$dt['0']['transacting'];
				$j++;
			}

			$retialerSale[] =array(date('Y-m-d'),$datas['sale']);
			$retailerAvgSale[] = array(date('Y-m-d'),intval($datas['sale']/$datas['transacting']));

			$datas_before['yesterday']['sale_avg'] = isset($datas_before['yesterday']['sale']) ? $datas_before['yesterday']['sale'] : 0;
			$datas_before['week']['sale_avg'] = isset($datas_before['week']['sale']) ? intval($datas_before['week']['sale']/$i) : 0;
			$datas_before['month']['sale_avg'] = isset($datas_before['month']['sale'])? intval($datas_before['month']['sale']/$j) : 0;
			$datas_before['week']['sale_avg_ret'] = (isset($datas_before['week']['sale_avg_ret']) && $i != 0) ? intval($datas_before['week']['sale_avg_ret']/$i) : 0;
			$datas_before['month']['sale_avg_ret'] = (isset($datas_before['month']['sale_avg_ret']) && $j != 0) ? intval($datas_before['month']['sale_avg_ret']/$j) : 0;


			$graphData = array(
					'labels' => array(
							array('string' => 'Sample'),
							array('number' => 'Topup sold today'),
							array('number' => 'Topup buy today'),
							array('number' => 'Tertiary Sale')
					),
					'data' => $datatopupsold,
					'title' => 'Topup sold/day',
					'type' => 'line',
					'width' => '900',
					'height' => '400'
			);
			$graphData1 = array(
					'labels' => array(
							array('string' => 'Sample'),
							array('number' => 'transacting Retailers')
					),
					'data' => $datatransRetailer,
					'title' => 'transacting Retailers',
					'type' => 'line',
					'width' => '900',
					'height' => '400'
			);
			$graphData2 = array(
					'labels' => array(
							array('string' => 'Sample'),
							array('number' => 'New outlets opened')
					),
					'data' => $datanewOutlets,
					'title' => 'New outlets opened',
					'type' => 'line',
					'width' => '900',
					'height' => '400'
			);
			$graphData3 = array(
					'labels' => array(
							array('string' => 'Sample'),
							array('number' => 'Unique topups/day')
					),
					'data' => $dataUniqueTopups,
					'title' => 'Unique topups/day',
					'type' => 'line',
					'width' => '900',
					'height' => '400'
			);

			$graphData5 = array(
					'labels' => array(
							array('string' => 'Sample'),
							array('number' => 'Avg Sale/Retailer')
					),
					'data' => $retailerAvgSale,
					'title' => 'Avg Sale/Retailer',
					'type' => 'line',
					'width' => '900',
					'height' => '400'
			);


			$this->set('data_today',$datas);
			$this->set('data_before',$datas_before);
			$this->set('data1',$graphData);
			$this->set('data2',$graphData1);
			$this->set('data3',$graphData2);
			$this->set('data4',$graphData3);
			//$this->set('data5',$graphData4);
			$this->set('data6',$graphData5);
		}

		if(!empty($id))$this->set('id',$id);

		if($this->Session->read('Auth.User.group_id') == DISTRIBUTOR ){
			$response = $this->Shop->getDistEarnings();
			$total_today_earning = array_sum(array_map(function($earning){return $earning['today'];},$response['earnings']));
			$total_yesterday_earning = array_sum(array_map(function($earning){return $earning['yesterday'];},$response['earnings']));
			$total_last_7_days_earning = array_sum(array_map(function($earning){return $earning['last_7_days'];},$response['earnings']));
			$total_last_30_days_earning = array_sum(array_map(function($earning){return $earning['last_30_days'];},$response['earnings']));
			$retailer_count = 0;
			if( isset($datas['retailers']) && $datas['retailers'] > 0 ){
				$retailer_count = $datas['retailers'];
			}

			$this->set('earnings',$response['earnings']);
			$this->set('services',$response['services']);
			$this->set('total_today_earning',$total_today_earning);
			$this->set('total_yesterday_earning',$total_yesterday_earning);
			$this->set('total_last_7_days_earning',$total_last_7_days_earning);
			$this->set('total_last_30_days_earning',$total_last_30_days_earning);
			$this->set('retailer_count',$retailer_count);
		}
	}

	function targetReport($monthval=null,$yearval=null){
	    if(empty($monthval)){
	        $monthval= date('m');
	    }
	    if(empty($yearval)){
	        $yearval= date('Y');
	    }
	    if($_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER){
	        $dists = $this->Slaves->query("SELECT id FROM distributors WHERE active_flag = 1 AND rm_id = ".$this->info['id']);
	    }
	    else if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){
	        $dists = $this->Slaves->query("SELECT id FROM distributors WHERE active_flag = 1 AND parent_id = ".$this->info['id']);
	    }

	    $distributors = array();
	    foreach($dists as $dist){
	        $distributors[] = $dist['distributors']['id'];
	    }
	    $distributors = implode(",",$distributors);
	    $data = $this->Scheme->getScheme($distributors,$monthval,$yearval);
	    $month = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
	    $year = array('2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020');

	    $this->set('monthval',$monthval);
	    $this->set('yearval',$yearval);
	    $this->set('month',$month);
	    $this->set('year',$year);
	    $this->set('target_data',$data);
	}


	function overallReport($date=null,$salesman=0){

		if($date == null){
			$date = date('dmY',strtotime('-30 days')) . '-' . date('dmY',strtotime('-1 days'));
		}

		$dates = explode("-",$date);
		$date_from = $dates[0];
		$date_to = $dates[1];

		if(checkdate(substr($date_from,2,2), substr($date_from,0,2), substr($date_from,4)) && checkdate(substr($date_to,2,2), substr($date_to,0,2), substr($date_to,4))){
			$date_from =  substr($date_from,4) . "-" . substr($date_from,2,2) . "-" . substr($date_from,0,2);
			$date_to =  substr($date_to,4) . "-" . substr($date_to,2,2) . "-" . substr($date_to,0,2);

			if($date_to == date('Y-m-d')){
				$date_to = date('Y-m-d',strtotransactingtime('-1 days'));
			}

			if($_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER){
                $data = $this->Slaves->query("SELECT sum(topup_sold) as second,sum(topup_buy) as prim,max(retailers)-min(retailers) as newr,trim(distributors.id) as rid,trim(distributors.company) as comp,users.mobile,distributors.name as distname,distributors.city,distributors.created as created_date,distributors.margin as margin,sum(topup_unique) as secondrytxn,sum(primary_txn) as primarytxn,benchmark_value as benchmark_tervalue,transacting_retailer as trans_retailer,avg(transacting)as transacting,rm.name as rmname FROM users_logs as distributors_logs,distributors,users,rm WHERE distributors.rm_id = ".$this->info['id']." AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND distributors.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '$date_from' AND date <= '$date_to' AND distributors.active_flag= '1' group by distributors_logs.user_id");
//				$data_ter = $this->Slaves->query("SELECT sum(sale) as sale,trim(distributors.id) as rid,trim(distributors.company) as comp,sum(retailers_logs.transactions)as transactions,count(distinct retailer_id) as uniqueret FROM `retailers_logs`,retailers,distributors WHERE retailers_logs.retailer_id = retailers.id AND retailers.parent_id = distributors.id AND distributors.rm_id = ".$this->info['id']." AND distributors.id NOT IN (".DISTS.") AND retailers_logs.date >= '$date_from' AND retailers_logs.date <= '$date_to' AND distributors.active_flag= '1' group by distributors.id");
				$data_ter = $this->Slaves->query("SELECT SUM(rel.amount) AS sale,TRIM(distributors.id) AS rid,TRIM(distributors.company) AS comp,SUM(rel.txn_count) AS transactions,COUNT(DISTINCT rel.ret_user_id) AS uniqueret "
                                        . "FROM retailer_earning_logs rel "
                                        . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                        . "JOIN distributors ON (rel.dist_user_id = distributors.user_id) "
                                        . "WHERE distributors.rm_id = ".$this->info['id']." "
                                        . "AND distributors.id NOT IN (".DISTS.") "
                                        . "AND rel.date >= '$date_from' "
                                        . "AND rel.date <= '$date_to' "
                                        . "AND distributors.active_flag = '1' "
                                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY distributors.id");
                $prevdata = $this->Slaves->query("SELECT sum(topup_sold) as second,sum(topup_buy) as prim,max(retailers)-min(retailers) as newr,trim(distributors.id) as rid,trim(distributors.company) as comp,users.mobile,distributors.name as distname,distributors.city,sum(topup_unique) as secondrytxn,sum(primary_txn) as primarytxn,benchmark_value as benchmark_tervalue,transacting_retailer as trans_retailer,rm.name as rmname FROM users_logs as distributors_logs,distributors,users,rm WHERE distributors.rm_id = ".$this->info['id']." AND distributors.id NOT IN (".DISTS.") AND distributors.user_id = distributors_logs.user_id AND distributors.user_id=users.id AND rm.id = distributors.rm_id  AND date >= '".date('Y-m-d',strtotime($date_from.'-1 months'))."' AND date <= '".date('Y-m-d',strtotime($date_to.'-1 months'))."' AND distributors.active_flag= '1' group by distributors_logs.user_id");
//				$prev_data_ter = $this->Slaves->query("SELECT sum(sale) as sale,trim(distributors.id) as rid,trim(distributors.company) as comp,sum(retailers_logs.transactions)as transactions FROM `retailers_logs`,retailers,distributors WHERE retailers_logs.retailer_id = retailers.id AND retailers.parent_id = distributors.id AND distributors.rm_id = ".$this->info['id']." AND distributors.id NOT IN (".DISTS.") AND retailers_logs.date >= '".date('Y-m-d',strtotime($date_from.'-1 months'))."' AND retailers_logs.date <= '".date('Y-m-d',strtotime($date_to.'-1 months'))."' AND distributors.active_flag= '1' group by distributors.id");
				$prev_data_ter = $this->Slaves->query("SELECT SUM(rel.amount) AS sale,TRIM(distributors.id) AS rid,TRIM(distributors.company) AS comp,SUM(rel.txn_count) AS transactions "
                                        . "FROM retailer_earning_logs rel "
                                        . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                        . "JOIN distributors ON (rel.dist_user_id = distributors.user_id)  "
                                        . "WHERE distributors.rm_id = ".$this->info['id']." "
                                        . "AND distributors.id NOT IN (".DISTS.") "
                                        . "AND rel.date >= '".date('Y-m-d',strtotime($date_from.'-1 months'))."' "
                                        . "AND rel.date <= '".date('Y-m-d',strtotime($date_to.'-1 months'))."' "
                                        . "AND distributors.active_flag = '1' "
                                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY distributors.id");

//				$incentiveRefund = $this->Slaves->query("SELECT source_id,sum(shop_transactions.amount) as incentive,'discount' as name  FROM `shop_transactions` inner join distributors on distributors.id =  shop_transactions.source_id  WHERE  shop_transactions.type = '".COMMISSION_DISTRIBUTOR."'   and   date>='$date_from' and date<='$date_to' and distributors.rm_id = ".$this->info['id']." group by source_id
//                                                           UNION
//                                                          SELECT source_id,sum(shop_transactions.amount)  as refund,'refund' as name FROM `shop_transactions` inner join distributors on shop_transactions.source_id =  distributors.user_id WHERE shop_transactions.type = '".REFUND."' and shop_transactions.date>='$date_from' and  shop_transactions.date<='$date_to' AND shop_transactions.confirm_flag = 0 and distributors.rm_id  = '".$this->info['id']."'group by source_id");
				$incentiveRefund = $this->Slaves->query("SELECT distributors.id as source_id,sum(shop_transactions.amount) as incentive,'discount' as name  FROM users_nontxn_logs as shop_transactions inner join distributors on distributors.user_id =  shop_transactions.user_id  WHERE  shop_transactions.type = '".COMMISSION_DISTRIBUTOR."'   and   date>='$date_from' and date<='$date_to' and distributors.rm_id = ".$this->info['id']." group by shop_transactions.user_id
                                                           UNION
                                                          SELECT distributors.id as source_id,sum(shop_transactions.amount-shop_transactions.txn_reverse_amt)  as refund,'refund' as name FROM users_nontxn_logs as shop_transactions inner join distributors on shop_transactions.user_id =  distributors.user_id WHERE shop_transactions.type = '".REFUND."' and shop_transactions.date>='$date_from' and  shop_transactions.date<='$date_to' and distributors.rm_id  = '".$this->info['id']."' group by shop_transactions.user_id");
				$rmdetails = $this->Slaves->query("SELECT rm.name,distributors.id from rm left join distributors on rm.id = distributors.created_rm_id where rm.id = '".$this->info['id']."'");
			    $getNewretailer = $this->Slaves->query("select retailers.parent_id,count(retailers.id) as new_retailers from retailers inner join distributors on distributors.id = retailers.parent_id left join rm on distributors.rm_id = rm.id where date(retailers.created)>='$date_from' and date(retailers.created)<='$date_to' and distributors.rm_id = '".$this->info['id']."' group by distributors.id");
				$totalBaseRet = $this->Slaves->query("select distributors.id as distId,count(*) as base_ret from retailers inner join distributors on retailers.parent_id = distributors.id left join rm on rm.id = distributors.rm_id  where date(retailers.created)<='$date_to' and distributors.rm_id ='".$this->info['id']."' group by retailers.parent_id");

                 /** IMP DATA ADDED : START**/
                $dist_mobiles = array_map(function($element){
                    return $element['0']['rid'];
                },$data_ter);
                $imp_data = $this->Shop->getUserLabelData($dist_mobiles,2,3);
                foreach ($data_ter as $key => $dist) {
                    $data_ter[$key]['0']['comp'] = $imp_data[$dist['0']['rid']]['imp']['shop_est_name'];
                }
                /** IMP DATA ADDED : END**/

                /** IMP DATA ADDED : START**/
                $dist_mobiles = array_map(function($element){
                    return $element['0']['rid'];
                },$prevdata);
                $dist_mobiles = $this->Shop->getUserLabelData($dist_mobiles,2,3);
                foreach ($prevdata as $key => $dist) {
                    $prevdata[$key]['0']['comp'] = $dist_mobiles[$dist['0']['rid']]['imp']['shop_est_name'];
                    $prevdata[$key]['distributors']['distname'] = $dist_mobiles[$dist['0']['rid']]['imp']['name'];
                }
                /** IMP DATA ADDED : END**/

                /** IMP DATA ADDED : START**/
                $dist_mobiles = array_map(function($element){
                    return $element['0']['rid'];
                },$prev_data_ter);
                $imp_data = $this->Shop->getUserLabelData($dist_mobiles,2,3);
                foreach ($prev_data_ter as $key => $dist) {
                    $prev_data_ter[$key]['0']['comp'] = $imp_data[$dist['0']['rid']]['imp']['shop_est_name'];
                }
                /** IMP DATA ADDED : END**/

			}
			else if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){
                                $dists = str_replace('1,','',DISTS);
				$data = $this->Slaves->query("SELECT sum(topup_sold) as second,sum(topup_buy) as prim,max(retailers)-min(retailers) as newr,trim(distributors.id) as rid,trim(distributors.company) as comp,distributors.state,users.mobile,distributors.name as distname,distributors.city,distributors.created as created_date,distributors.margin as margin,sum(topup_unique) as secondrytxn,sum(primary_txn) as primarytxn,benchmark_value as benchmark_tervalue,transacting_retailer as trans_retailer,avg(transacting)as transacting,rm.name as rmname FROM users_logs as distributors_logs INNER JOIN distributors ON (distributors.user_id = distributors_logs.user_id) INNER JOIN users ON (distributors.user_id=users.id)  left join rm ON (distributors.rm_id = rm.id)  WHERE distributors.parent_id = ".$this->info['id']." AND distributors.id NOT IN (".$dists.") AND date >= '$date_from' AND date <= '$date_to' AND distributors.active_flag= '1' group by distributors_logs.user_id");
//				$data_ter = $this->Slaves->query("SELECT sum(sale) as sale,trim(distributors.id) as rid,trim(distributors.company) as comp,sum(retailers_logs.transactions) as transactions,count(distinct retailer_id) as uniqueret FROM `retailers_logs`,retailers,distributors WHERE retailers_logs.retailer_id = retailers.id AND retailers.parent_id = distributors.id AND distributors.parent_id = ".$this->info['id']." AND distributors.id NOT IN (".$dists.") AND retailers_logs.date >= '$date_from' AND retailers_logs.date <= '$date_to' and distributors.active_flag= '1' group by distributors.id");
				$data_ter = $this->Slaves->query("SELECT SUM(rel.amount) AS sale,TRIM(distributors.id) AS rid,TRIM(distributors.company) AS comp,SUM(rel.txn_count) AS transactions,COUNT(DISTINCT rel.ret_user_id) AS uniqueret "
                                        . "FROM retailer_earning_logs rel "
                                        . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                        . "JOIN distributors ON (rel.dist_user_id = distributors.user_id) "
                                        . "WHERE distributors.parent_id = ".$this->info['id']." "
                                        . "AND distributors.id NOT IN (".$dists.") "
                                        . "AND rel.date >= '$date_from' "
                                        . "AND rel.date <= '$date_to' "
                                        . "AND distributors.active_flag= '1' "
                                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY distributors.id");
				$prevdata = $this->Slaves->query("SELECT sum(topup_sold) as second,sum(topup_buy) as prim,max(retailers)-min(retailers) as newr,trim(distributors.id) as rid,trim(distributors.company) as comp,distributors.state,users.mobile,distributors.name as distname,distributors.city,distributors.created as discreated,distributors.margin as distmargin,sum(topup_unique) as secondrytxn,sum(primary_txn) as primarytxn,benchmark_value as benchmark_tervalue,transacting_retailer as trans_retailer,rm.name as rmname FROM users_logs as distributors_logs INNER JOIN distributors ON (distributors.user_id = distributors_logs.user_id) INNER JOIN users ON (distributors.user_id=users.id)  left join rm ON (distributors.rm_id = rm.id)  WHERE distributors.parent_id = ".$this->info['id']." AND distributors.id NOT IN (".$dists.") AND date >= '".date('Y-m-d',strtotime($date_from.'-1 months'))."' AND date <= '".date('Y-m-d',strtotime($date_to.'-1 months'))."' AND distributors.active_flag= '1' group by distributors_logs.user_id");
//				$prev_data_ter = $this->Slaves->query("SELECT sum(sale) as sale,trim(distributors.id) as rid,trim(distributors.company) as comp,sum(retailers_logs.transactions) as transactions FROM `retailers_logs`,retailers,distributors WHERE retailers_logs.retailer_id = retailers.id AND retailers.parent_id = distributors.id AND distributors.parent_id = ".$this->info['id']." AND distributors.id NOT IN (".$dists.") AND retailers_logs.date >= '".date('Y-m-d',strtotime($date_from.'-1 months'))."' AND retailers_logs.date <= '".date('Y-m-d',strtotime($date_to.'-1 months'))."' AND distributors.active_flag= '1' group by distributors.id");
				$prev_data_ter = $this->Slaves->query("SELECT SUM(rel.amount) AS sale,TRIM(distributors.id) AS rid,TRIM(distributors.company) AS comp,SUM(rel.txn_count) AS transactions "
                                        . "FROM retailer_earning_logs rel "
                                        . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                        . "JOIN distributors ON (rel.dist_user_id = distributors.user_id) "
                                        . "WHERE distributors.parent_id = ".$this->info['id']." "
                                        . "AND distributors.id NOT IN (".$dists.") "
                                        . "AND rel.date >= '".date('Y-m-d',strtotime($date_from.'-1 months'))."' "
                                        . "AND rel.date <= '".date('Y-m-d',strtotime($date_to.'-1 months'))."' "
                                        . "AND distributors.active_flag= '1' "
                                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY distributors.id");
//				$incentiveRefund = $this->Slaves->query("SELECT source_id,sum(shop_transactions.amount) as incentive,'discount' as name  FROM `shop_transactions` WHERE  shop_transactions.type = '".COMMISSION_DISTRIBUTOR."'   and   date>='$date_from' and date<='$date_to' group by source_id
//                                                           UNION
//                                                           SELECT distributors.id source_id,sum(shop_transactions.amount) as refund,'refund' as name FROM `shop_transactions` inner join distributors on shop_transactions.source_id =  distributors.user_id   WHERE  shop_transactions.type = '".REFUND."' and shop_transactions.date>='$date_from' and shop_transactions.confirm_flag=0 AND shop_transactions.date<='$date_to' and distributors.parent_id  = '".$this->info['id']."' group by source_id");
				$incentiveRefund = $this->Slaves->query("SELECT distributors.id source_id,SUM(dl.amount) as incentive,'discount' as name FROM users_nontxn_logs dl JOIN distributors ON (dl.user_id = distributors.user_id) WHERE dl.type = '".COMMISSION_DISTRIBUTOR."' AND date >= '$date_from' AND date<='$date_to' GROUP BY distributors.id
                                                           UNION
                                                           SELECT distributors.id source_id,SUM(dl.amount-dl.txn_reverse_amt) as refund,'refund' as name FROM users_nontxn_logs dl INNER JOIN distributors ON (dl.user_id = distributors.user_id) WHERE dl.type = '".REFUND."' AND dl.date >= '$date_from' AND dl.date <= '$date_to' AND distributors.parent_id  = '".$this->info['id']."' GROUP BY distributors.id");
				$rmdetails = $this->Slaves->query("SELECT rm.name,distributors.id from rm left join distributors on rm.id = distributors.created_rm_id where distributors.parent_id = '".$this->info['id']."' group by distributors.id");
			    $getNewretailer = $this->Slaves->query("SELECT retailers.parent_id,count(retailers.id) as new_retailers from retailers inner join distributors on distributors.id = retailers.parent_id where date(retailers.created)>='$date_from' and date(retailers.created)<='$date_to' and distributors.parent_id = '".$this->info['id']."' group by distributors.id");
				$totalBaseRet = $this->Slaves->query("select distributors.id as distId,count(*) as base_ret from retailers inner join distributors on retailers.parent_id = distributors.id where date(retailers.created)<='$date_to' and distributors.parent_id = '".$this->info['id']."' group by retailers.parent_id");

                /** IMP DATA ADDED : START**/
                $dist_mobiles = array_map(function($element){
                    return $element['0']['rid'];
                },$data_ter);
                $imp_data = $this->Shop->getUserLabelData($dist_mobiles,2,3);
                foreach ($data_ter as $key => $dist) {
                    $data_ter[$key]['0']['comp'] = $imp_data[$dist['0']['rid']]['imp']['shop_est_name'];
                }
                /** IMP DATA ADDED : END**/

                /** IMP DATA ADDED : START**/
                $dist_mobiles = array_map(function($element){
                    return $element['0']['rid'];
                },$prevdata);
                $dist_mobiles = $this->Shop->getUserLabelData($dist_mobiles,2,3);
                foreach ($prevdata as $key => $dist) {
                    $prevdata[$key]['0']['comp'] = $dist_mobiles[$dist['0']['rid']]['imp']['shop_est_name'];
                    $prevdata[$key]['distributors']['distname'] = $dist_mobiles[$dist['0']['rid']]['imp']['name'];
                }
                /** IMP DATA ADDED : END**/

                /** IMP DATA ADDED : START**/
                $dist_mobiles = array_map(function($element){
                    return $element['0']['rid'];
                },$prev_data_ter);
                $imp_data = $this->Shop->getUserLabelData($dist_mobiles,2,3);
                foreach ($prev_data_ter as $key => $dist) {
                    $prev_data_ter[$key]['0']['comp'] = $imp_data[$dist['0']['rid']]['imp']['shop_est_name'];
                }
                /** IMP DATA ADDED : END**/


			}
			else if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
			    $sals_cond = $salesman == 0 ? "1=1" : "retailers.maint_salesman = ".$salesman;
			    $data = array();//$this->Retailer->query("SELECT sum(topup_sold) as second,sum(topup_buy) as prim,max(retailers)-min(retailers) as newr,trim(distributors.id) as rid,trim(distributors.company) as comp FROM `distributors_logs`,distributors WHERE distributors.parent_id = ".$this->info['id']." AND distributors.id NOT IN (".DISTS.") AND distributors.id = distributor_id AND date >= '$date_from' AND date <= '$date_to' group by distributor_id");
			    $data_ter1 = $this->Slaves->query("SELECT retailers.id as rid , unverified_retailers.shopname as rname , retailers.mobile as rmobile , sum(topup_buy) as sum_topup  FROM users_logs,retailers,unverified_retailers WHERE users_logs.user_id = retailers.user_id AND retailers.id = unverified_retailers.retailer_id AND $sals_cond AND retailers.parent_id = ".$this->info['id']." AND date >= '$date_from' AND date <= '$date_to'  group by retailers.id");
                                                        
			    $data_ter2 = $this->Slaves->query("SELECT *,(SUM(sum_sale)-sum_ussd_sale-sum_sms_sale) as sum_app_sale FROM "
                                    . "(SELECT retailers.id as rid , unverified_retailers.shopname as rname , retailers.mobile as rmobile , "
                                    . "SUM(rel.amount) as sum_sale , if(rel.api_flag = 2,sum(rel.amount),0) as sum_ussd_sale , if(rel.api_flag = 0,sum(rel.amount),0) as sum_sms_sale,rel.api_flag  "
                                    . "FROM retailer_earning_logs rel "
                                    . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                    . "JOIN unverified_retailers ON (retailers.id = unverified_retailers.retailer_id) "
                                    . "JOIN distributors ON (rel.dist_user_id = distributors.user_id) "
                                    . "WHERE $sals_cond "
                                    . "AND distributors.id = ".$this->info['id']." "
                                    . "AND date >= '$date_from' "
                                    . "AND date <= '$date_to' "
                                    . "GROUP BY retailers.id,rel.api_flag "
                                    . "ORDER BY sum_sale DESC) as data_ter "
                                    . "GROUP BY rid ");

			    $salesmans = $this->Slaves->query("select * from salesmen where mobile != ".$this->Session->read('Auth.User.mobile')." AND dist_id = ".$this->info['id']." AND active_flag = 1");

                /** IMP DATA ADDED : START**/
                $ret_mobiles1 = array_map(function($element){
                    return $element['retailers']['rmobile'];
                },$data_ter1);
                $ret_mobiles2 = array_map(function($element){
                    return $element['data_ter']['rmobile'];
                },$data_ter2);
                $ret_mobiles = array_unique(array_merge($ret_mobiles1,$ret_mobiles2));
                
                $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);
               
                foreach($data_ter1 as $data){
                    $data_ter[$data['retailers']['rid']][0]['sum_topup'] = $data[0]['sum_topup'];
                    $data_ter[$data['retailers']['rid']]['unverified_retailers']['rname'] = $imp_data[$data['retailers']['rmobile']]['imp']['shop_est_name'];
                    $data_ter[$data['retailers']['rid']]['retailers']['rmobile'] = $data['retailers']['rmobile'];
                    $data_ter[$data['retailers']['rid']][0]['sum_sale'] = 0;
                    $data_ter[$data['retailers']['rid']][0]['sum_app_sale'] = 0;
                    $data_ter[$data['retailers']['rid']][0]['sum_ussd_sale'] = 0;
                    $data_ter[$data['retailers']['rid']][0]['sum_sms_sale'] = 0;
                }
                            
                foreach ($data_ter2 as $data) {
                    $data_ter[$data['data_ter']['rid']]['unverified_retailers']['rname'] = $imp_data[$data['data_ter']['rmobile']]['imp']['shop_est_name'];
                    $data_ter[$data['data_ter']['rid']]['retailers']['rid'] = $data['data_ter']['rid'];
                    $data_ter[$data['data_ter']['rid']]['retailers']['rmobile'] = $data['data_ter']['rmobile'];
                    $data_ter[$data['data_ter']['rid']][0]['sum_sale'] = $data['data_ter']['sum_sale'];
                    $data_ter[$data['data_ter']['rid']][0]['sum_app_sale'] = $data[0]['sum_app_sale'];
                    $data_ter[$data['data_ter']['rid']][0]['sum_ussd_sale'] = $data['data_ter']['sum_ussd_sale'];
                    $data_ter[$data['data_ter']['rid']][0]['sum_sms_sale'] = $data['data_ter']['sum_sms_sale'];
                }
                /** IMP DATA ADDED : END**/


                $this->set('datas',$data_ter);
			    $this->set('salesmans',$salesmans);
			    $this->set('salesm',$salesman);
			    $this->render('retailer_overall_report');
			}
			else {
				$data = $this->Slaves->query("SELECT sum(topup_sold) as second,sum(topup_buy) as prim,max(retailers)-min(retailers) as newr,trim(master_distributors.id) as rid,trim(master_distributors.company) as comp FROM users_logs as distributors_logs,distributors,master_distributors WHERE distributors.parent_id = master_distributors.id AND distributors.user_id = distributors_logs.user_id AND date >= '$date_from' AND date <= '$date_to' group by master_distributors.id");
//				$data_ter = $this->Slaves->query("SELECT sum(sale) as sale,trim(master_distributors.id) as rid,trim(master_distributors.company) as comp FROM `retailers_logs`,retailers,distributors,master_distributors WHERE retailers_logs.retailer_id = retailers.id AND retailers.parent_id = distributors.id AND distributors.parent_id = master_distributors.id AND retailers_logs.date >= '$date_from' AND retailers_logs.date <= '$date_to' group by master_distributors.id");
				$data_ter = $this->Slaves->query("SELECT SUM(rel.amount) AS sale,TRIM(master_distributors.id) AS rid,TRIM(master_distributors.company) AS comp "
                                        . "FROM retailer_earning_logs rel "
                                        . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                        . "JOIN distributors ON (rel.dist_user_id = distributors.user_id) "
                                        . "JOIN master_distributors ON (distributors.parent_id = master_distributors.id) "
                                        . "WHERE rel.date >= '$date_from' "
                                        . "AND rel.date <= '$date_to' "
                                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY master_distributors.id");
			}

			$list = array();
			foreach($data as $dt){
				$list[$dt['0']['rid']]['primary'] = $dt['0']['prim'];
				$list[$dt['0']['rid']]['secondary'] = $dt['0']['second'];
				$list[$dt['0']['rid']]['newr'] = $dt['0']['newr'];
				$list[$dt['0']['rid']]['company'] = $dt['0']['comp'];
				$list[$dt['0']['rid']]['tertiary'] = 0;
				$list[$dt['0']['rid']]['id'] = $dt['0']['rid'];
                $list[$dt['0']['rid']]['mobile'] = $dt['users']['mobile'];
                $list[$dt['0']['rid']]['city'] = $dt['distributors']['city'];
				$list[$dt['0']['rid']]['state'] = $dt['distributors']['state'];
                $list[$dt['0']['rid']]['distributor_name'] = $dt['distributors']['distname'];
                $list[$dt['0']['rid']]['primary_txn'] = $dt[0]['primarytxn'];
                $list[$dt['0']['rid']]['secondry_txn'] = $dt[0]['secondrytxn'];
                $list[$dt['0']['rid']]['benchmark_tertiary'] = $dt['distributors']['benchmark_tervalue'];
                $list[$dt['0']['rid']]['transacting_retailer'] = $dt['distributors']['trans_retailer'];
				$list[$dt['0']['rid']]['transacting'] = $dt[0]['transacting'];
                $list[$dt['0']['rid']]['rmname'] = $dt['rm']['rmname'];
				$list[$dt['0']['rid']]['created_date'] = $dt['distributors']['created_date'];
				$list[$dt['0']['rid']]['margin'] = $dt['distributors']['margin'];
			}

			foreach($prevdata as $dt){
				$list[$dt['0']['rid']]['prev_primary'] = $dt['0']['prim'];
				$list[$dt['0']['rid']]['prev_secondary'] = $dt['0']['second'];
				$list[$dt['0']['rid']]['newr'] = $dt['0']['newr'];
				$list[$dt['0']['rid']]['company'] = $dt['0']['comp'];
				$list[$dt['0']['rid']]['prev_tertiary'] = 0;
                                $list[$dt['0']['rid']]['prev_primary_txn'] = $dt[0]['primarytxn'];
                                $list[$dt['0']['rid']]['prev_secondry_txn'] = $dt[0]['secondrytxn'];
                                $list[$dt['0']['rid']]['prev_benchmark_tertiary'] = $dt['distributors']['benchmark_tervalue'];
                                $list[$dt['0']['rid']]['prev_ransacting_retailer'] = $dt['distributors']['trans_retailer'];
                                $list[$dt['0']['rid']]['rmname'] = $dt['rm']['rmname'];
			}

			foreach ($data_ter as $dt) {
				if ($_SESSION['Auth']['User']['group_id'] != DISTRIBUTOR) {
					$list[$dt['0']['rid']]['tertiary'] = $dt['0']['sale'];
					$list[$dt['0']['rid']]['company'] = $dt['0']['comp'];
					$list[$dt['0']['rid']]['tertiary_txn'] = $dt['0']['transactions'];
					$list[$dt['0']['rid']]['unique_ret'] = $dt['0']['uniqueret'];
				}
			}

			foreach ($prev_data_ter as $dt) {
				if ($_SESSION['Auth']['User']['group_id'] != DISTRIBUTOR) {
					$list[$dt['0']['rid']]['prev_tertiary'] = $dt['0']['sale'];
					$list[$dt['0']['rid']]['company'] = $dt['0']['comp'];
					$list[$dt['0']['rid']]['prev_tertiary_txn'] = $dt['0']['transactions'];
				}
			}

			foreach ($incentiveRefund as $val) {
				if (isset($list[$val[0]['source_id']])):
					$list[$val[0]['source_id']][$val[0]['name']] = $val[0]['incentive'];
				endif;
			}

			foreach ($rmdetails as $val) {
				if (isset($list[$val['distributors']['id']])) {
					$list[$val['distributors']['id']]['created_rm'] = $val['rm']['name'];
				}
			}

			foreach ($getNewretailer as $val) {
				if (isset($list[$val['retailers']['parent_id']])) {
					$list[$val['retailers']['parent_id']]['new_retailers'] = $val[0]['new_retailers'];
				}
			}

			foreach($totalBaseRet as $val){
				if (isset($list[$val['distributors']['distId']])) {

					$list[$val['distributors']['distId']]['base_retailers'] = $val[0]['base_ret'];
				}

			}

			$date1 = new DateTime($date_from);

                        $date2 = date("Y-m-d", strtotime("+1 day", strtotime($date_to)));

			$date2 = new DateTime($date2);

			$diffdays = $date2->diff($date1)->format("%a");

			$pageType = empty($_GET['res_type']) ? "" : $_GET['res_type'];
                        $list = Set::sort($list, '{[0-9]}.tertiary', 'desc');
			$this->set('datas',$list);
			$this->set('date_from',$date_from);
			$this->set('date_to',$date_to);
			$this->set('diffdays',$diffdays);
                        $this->set('pageType',$pageType);

                        if($pageType == 'csv'){
                                App::import('Helper','csv');
                                $this->layout = null;
                                $this->autoLayout = false;
                                $csv = new CsvHelper();
                                //$line = array("Txn Date","Txn Id","Signal7 T_ID","Number","Operator","Amount","Opening","Closing","Earning","Reversal Date","Description","Status");
                                $line = array("S.No.","City","State","Distributor","id","Reg Date","Margin Slab","LEAD RM","CURRENT RM","Primary Value","Primary Txn","Primary Avg","Previous Primary Avg","SecondaryValue","Secondary Unique Retailer","Secondry Avg","Previous Secondry-Avg","Tertiary Value","Tertiary Txn","Tertiary Avg","Previous Tertiary-Avg","Half Yearly Benchmark-Avg Tertiary Value INR","New Retailer","Total Retailer Base","Total Transacting-Retailer","Half Yearly Benchmark - Avg Transacting Retailer","Discount Recevied","Incentive Recevied");
                                $csv->addRow($line);
                                $i=1;

                                foreach ($list as $data) {
                                    if(isset($data['id'])) {
                                        $primaryavg  = intval($data['primary']/$diffdays);
                                        $secondryavg  = intval($data['secondary']/$diffdays);
                                        $tertiaryavg  = intval($data['tertiary']/$diffdays);
                                        $prevprimaryavg = intval($data['prev_primary']/$diffdays);
                                        $prevsecondryavg = intval($data['prev_secondary']/$diffdays);
                                        $prevtertiaryavg = intval($data['prev_tertiary']/$diffdays);
                                        $temp = array($i, $data['city'],$data['state'], $data['company'],$data['id'],date('Y-m-d',  strtotime($data['created_date'])),$data['margin'],$data['created_rm'],$data['rmname'],$data['primary'],$data['primary_txn'],$primaryavg,$prevprimaryavg,$data['secondary'],$data['secondry_txn'],$secondryavg,$prevsecondryavg,$data['tertiary'],$data['tertiary_txn'],$tertiaryavg,$prevtertiaryavg,$data['benchmark_tertiary'],$data['new_retailers'],$data['base_retailers'],$data['unique_ret'],$data['transacting_retailer'],$data['discount'],$data['refund']);
                                        $csv->addRow($temp);
                                        $i++;
                                    }
                                }

                                echo $csv->render('overall_report'.date('YmdHis').'.csv');

                        }
		}
	}


	function sReport($date=null,$state=0,$rm_id=0)
	{

		//--------------
		$qryPart = "";
                $dates = explode("-",$date);
                $date_from = $dates[0];
                $date_to = $dates[1];
		if( !(empty($date_from) || empty($date_to)) ){


			if(checkdate(substr($date_from,2,2), substr($date_from,0,2), substr($date_from,4)) && checkdate(substr($date_to,2,2), substr($date_to,0,2), substr($date_to,4))){
				$date_from =  substr($date_from,4) . "-" . substr($date_from,2,2) . "-" . substr($date_from,0,2);
				$date_to =  substr($date_to,4) . "-" . substr($date_to,2,2) . "-" . substr($date_to,0,2);
				if($date_to == date('Y-m-d')){
					$date_to = date('Y-m-d');//,strtotime('-1 days')
				}
			}
			$this->set('date_from',$date_from);
			$this->set('date_to',$date_to);
			$qryPart = " AND Date(distributors.created) >= '$date_from' AND Date(distributors.created) <= '$date_to'";

		}
                if(!empty($state)){
                    $qryPart = $qryPart." AND distributors.state like '$state' AND distributors.active_flag='1'";
                }
                if(!empty($rm_id)){
                    $qryPart = $qryPart." AND distributors.rm_id = '$rm_id' AND distributors.active_flag='1'";
                }
		//---------------
                $today = date('Y-m-d');
		$yest_date = date('Y-m-d',strtotime('-1 days'));
		$week_date = date('Y-m-d',strtotime('-7 days'));
		$month_date = date('Y-m-d',strtotime('-30 days'));

                $rmList = $this->Slaves->query("SELECT id , name FROM `rm` Where  active_flag = 1 ORDER BY name"); //WHERE toShow = 0

                $this->set('rmList',$rmList);
                $this->set('state',$state);
                $this->set('rm_id',$rm_id);

                $datas = array();

		if($_SESSION['Auth']['User']['group_id'] == ADMIN){

			foreach($this->sds as $sdist){
				/*if($date != null){
					if(!($sdist['SuperDistributor']['created'] >= $date_from && $sdist['SuperDistributor']['created'] <= $date_to))  continue;
					}*/
				$sdistid = $sdist['MasterDistributor']['id'];
                $data_trans = $this->Slaves->query("SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale FROM vendors_activations as va USE INDEX(idx_date),retailers , distributors  WHERE va.status != 2 AND va.status !=3 AND retailers.id = va.retailer_id AND va.date = '$today' AND retailers.parent_id = distributors.id AND distributors.parent_id=$sdistid AND distributors.active_flag='1'");
				//echo "SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale FROM vendors_activations as va,retailers , distributors  WHERE va.status != 2 AND va.status !=3 AND retailers.id = va.retailer_id AND va.date = '$today' AND retailers.parent_id = distributors.id AND distributors.parent_id=$sdistid  $qryPart";
				//SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale FROM vendors_activations as va,retailers WHERE va.status != 2 AND va.status !=3 AND retailers.id = va.retailer_id AND va.date = '$today' AND retailers.parent_id = distributors.id AND distributors.parent_id=$sdistid
//				$data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs,retailers,distributors WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id=distributors.id AND distributors.parent_id = $sdistid AND distributors.active_flag='1' AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' group by retailers_logs.date order by retailers_logs.date");
				$data_bef_ret = $this->Slaves->query("SELECT SUM(rel.amount) AS sale,COUNT(DISTINCT rel.ret_user_id) AS transacting,rel.date "
                                        . "FROM retailer_earning_logs rel "
                                        . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                        . "JOIN distributors ON (rel.dist_user_id = distributors.user_id) "
                                        . "WHERE distributors.parent_id = $sdistid "
                                        . "AND distributors.active_flag = '1' "
                                        . "AND rel.date >= '".date('Y-m-d',strtotime('-30 days'))."' "
                                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                                        . "GROUP BY rel.date "
                                        . "ORDER BY rel.date");
				$i = 0;
				$j = 0;
				$datas_before = array();
				$datas_before['yesterday'] = 0;
				$datas_before['name'] = $sdist['MasterDistributor']['company'];
				$datas_before['today'] = $data_trans['0']['0']['sale'];
				foreach($data_bef_ret as $dt){
					if($dt['rel']['date'] == $yest_date){
						$datas_before['yesterday'] = $dt['0']['sale'];
					}
					if($dt['rel']['date'] >= $week_date){
						$datas_before['week'] += $dt['0']['sale'];
						$i++;
					}
					$datas_before['month'] += $dt['0']['sale'];
					$j++;
				}

				$datas_before['week'] = isset($datas_before['week'])&& !empty($i) ? intval($datas_before['week']/$i):0;
				$datas_before['month'] = isset($datas_before['month']) && !empty($j) ? intval($datas_before['month']/$j) : 0;

				$datas[] = $datas_before;
			}
		}
		else {
                        $data_trans = $this->Slaves->query("SELECT count(distinct va.retailer_id) as cts,sum(va.amount) as sale,distributors.id,distributors.company FROM vendors_activations as va USE INDEX (idx_date),retailers , distributors WHERE va.status != 2 AND va.status !=3 AND retailers.id = va.retailer_id AND retailers.parent_id = distributors.id AND va.date = '$today' $qryPart GROUP BY distributors.id");
//                        $data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale) as sale,count(retailers_logs.id) as transacting,retailers_logs.date,distributors.id,distributors.company FROM retailers_logs,retailers ,distributors WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id = distributors.id AND retailers_logs.date >= '".date('Y-m-d',strtotime('-30 days'))."' $qryPart  group by distributors.id,retailers_logs.date order by distributors.id,retailers_logs.date");
                        $data_bef_ret = $this->Slaves->query("SELECT SUM(rel.amount) AS sale,COUNT(DISTINCT rel.ret_user_id) AS transacting,rel.date,distributors.id,distributors.company "
                                . "FROM retailer_earning_logs rel "
                                . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                . "JOIN distributors ON (rel.dist_user_id = distributors.user_id) "
                                . "WHERE rel.date >= '".date('Y-m-d',strtotime('-30 days'))."' "
                                . "AND rel.service_id IN (1,2,4,5,6,7) "
                                . "$qryPart "
                                . "GROUP BY distributors.id,rel.date "
                                . "ORDER BY distributors.id,rel.date");

			foreach($data_bef_ret as $dist){
				$distid = $dist['distributors']['id'];
				$datas[$distid]['name'] = $dist['distributors']['company'];

				if(!isset($datas[$distid]['yesterday']))$datas[$distid]['yesterday'] = 0;
				if(!isset($datas[$distid]['week'])){
					$datas[$distid]['week'] = 0;
					$datas[$distid]['week_count'] = 0;
				}
				if(!isset($datas[$distid]['month'])){
					$datas[$distid]['month'] = 0;
					$datas[$distid]['month_count'] = 0;
				}

				if($dist['rel']['date'] == $yest_date){
					$datas[$distid]['yesterday'] = $dist['0']['sale'];
				}
				if($dist['rel']['date'] >= $week_date){
					$datas[$distid]['week'] += $dist['0']['sale'];
					$datas[$distid]['week_count'] ++;
				}
				$datas[$distid]['month'] += $dist['0']['sale'];
				$datas[$distid]['month_count'] ++;
			}

			foreach($data_trans as $dist){
				$distid = $dist['distributors']['id'];
				$datas[$distid]['name'] = $dist['distributors']['company'];
				$datas[$distid]['today'] = $dist['0']['sale'];
			}

			$datas1 = array(); 
			foreach($this->ds as $dist){
				$distid = $dist['Distributor']['id'];
				if(isset($datas[$distid])){
					if(isset($datas[$distid]['week_count']) && $datas[$distid]['week_count'] > 0){
						$datas[$distid]['week'] = intval($datas[$distid]['week']/$datas[$distid]['week_count']);
					}
					else $datas[$distid]['week'] = 0;
					if(isset($datas[$distid]['month_count']) && $datas[$distid]['month_count'] > 0){
						$datas[$distid]['month'] = intval($datas[$distid]['month']/$datas[$distid]['month_count']);
					}
					else $datas[$distid]['month'] = 0;
					if(!isset($datas[$distid]['yesterday']))$datas[$distid]['yesterday'] = 0;
					if(!isset($datas[$distid]['today']))$datas[$distid]['today'] = 0;
					$datas1[$distid] = $datas[$distid];
				}
				else {
					if($date != null){
						if(!(date('Y-m-d',strtotime($dist['Distributor']['created'])) >= $date_from && date('Y-m-d',strtotime($dist['Distributor']['created'])) <= $date_to))  continue;
					}

					$datas1[$distid]['name'] = $dist['Distributor']['company'];
					$datas1[$distid]['week'] = 0;
					$datas1[$distid]['month'] = 0;
					$datas1[$distid]['yesterday'] = 0;
					$datas1[$distid]['today'] = 0;
				}

			}

			$datas = $datas1;
		}

		$this->set('datas',$datas);
	}

        function retailerIncentives(){
            if(isset($_FILES['incentive']) && $_POST['service'] > 0) {

                        if(trim($_FILES['incentive']['type']) == 'text/csv' || trim($_FILES['incentive']['type']) == 'application/vnd.ms-excel') {

                                $file = fopen($_FILES['incentive']["tmp_name"],"r");
                                while(!feof($file)) {
                                        $data = fgetcsv($file, 1024);
                                        if(is_numeric($data[0]) && is_numeric($data[1])) {
                                                $result = $this->refundRetailer($data[0], $data[1], $data[2], 'panel', $_POST['service']);
                                                $data1 = array();
                                                $data1['id']        = $data[0];
                                                $data1['amount']    = $data[1];
                                                $data1['narration'] = $data[2];
                                                if($result['fail']) {
                                                        $data1['status'] = "<span style='color: red; font-weight: bold;'>Fail</span>";
                                                        $data1['remark'] = $result['fail'];
                                                } else {
                                                        $data1['company'] = $result['company'];
                                                        $data1['name'] = $result['name'];
                                                        $data1['mobile'] = $result['mobile'];
                                                        $data1['status'] = "<span style='color: green; font-weight: bold;'>Success</span>";
                                                        $data1['remark'] = $result['success'];
                                                }
                                                $res[] = $data1;
                                        }
                                }
                                fclose($file);
                        } else {

                                $res = "Invalid File Format";
                        }
                        $this->set('res', $res);
                } else {
                        $services = array_map('current', $this->Slaves->query("SELECT parent_id, parent_name FROM services WHERE parent_name IS NOT NULL GROUP BY parent_id"));
                        $this->set('services', $services);
                }
        }
        
	function refundRetailer($ret_id, $amt, $narration=NULL, $source=NULL, $service=1){
//		if($_SESSION['Auth']['User']['group_id'] != ADMIN && $_SESSION['Auth']['User']['group_id'] != ACCOUNTS){
//			$this->redirect('/');
//		}

                if(empty($ret_id) || empty($amt)){
                    if($source == 'panel') {
			$result['fail'] = "Please hit with id & amount as well";
			return $result;
                    } else {
		        echo "Please hit with id & amount as well";
		        exit;
                    }
                } 

		else if($amt > 1000){
                     if($source == 'panel') {
			$result['fail'] = "You are not allowed to transfer more than Rs1000 at one time";
			return $result;
                    } else {
			echo "You are not allowed to transfer more than Rs1000 at one time";
			exit;
		}
                }
		$shop = $this->Shop->getShopDataById($ret_id,RETAILER);

                if(empty($shop['user_id'])){
		    $msg1 = "Retailer id is not right";
		    if($source == 'panel') {
		        $result['fail'] = $msg1;
		        return $result;
		    } else {
		        echo $msg1;
		        exit;
		    }
		}

                $userId = $shop['user_id'];
                
                if($source == 'panel') {
                    $exist =$this->Slaves->query("SELECT count(*) count FROM shop_transactions WHERE type = '".REFUND."' AND date = '".date('Y-m-d')."' AND user_id = '$service' AND amount = '$amt' AND source_id = '$userId'");

                    if($exist[0][0]['count'] != 0) {
                                    $result['fail'] = "Same transaction is repeated for the day";
                                return $result;
                        }
                }

                if(!empty($shop['user_id'])){

                        $result['mobile']  = $shop['mobile'];
                        $result['company'] = $shop['shopname'];
                        $result['name']    = $shop['name'];
                        /*  Added as changes for DB optimization  */
		        $dataSource = $this->Retailer->getDataSource();
                        $userId  = $shop['user_id'];

                        try {
                                $dataSource->begin($this->Retailer);
                                $amt_settle = $amt;
                                $tds=0;
                                $user_data = $this->General->getUserDataFromId($userId);
                                if(in_array(DISTRIBUTOR,$user_data['groups'])){
                                    $tds = $amt*TDS_PERCENT/100;
//                                print_r($tds);die;
                                    $amt_settle = $amt - $tds;
                                }
                                
                                $bal = $this->Shop->shopBalanceUpdate($amt_settle,'add',$userId,RETAILER,$dataSource);
                                $trans_id = $this->Shop->shopTransactionUpdate(REFUND,$amt,$userId,RETAILER,$service,null,null,null,$bal-$amt_settle+$tds,$bal+$tds,null,null,$dataSource);
				if($tds > 0)
                                $this->Shop->shopTransactionUpdate(TDS,$tds,$userId,$trans_id,null,null,null,"TDS deducted against txn: $trans_id",$bal+$tds,$bal,null,null,$dataSource);
                                
                                if($trans_id === false || empty($trans_id)) {
                                        throw new Exception("Entry can't be done");
                                } else {

                //        			$sms = "Dear Retailer,\nYou have got refund of Rs $amt from Pay1 company";
                //        			$sms .= "\nYour current balance is now: Rs. " .$bal;

                                        $paramdata['AMOUNT'] = $amt;
                                        $paramdata['BALANCE'] = $bal;
                                        $MsgTemplate = $this->General->LoadApiBalance();
                                        $content =  $MsgTemplate['Retailer_Refund_MSG'];
                                        $sms = $this->General->ReplaceMultiWord($paramdata,$content);

                                        //$this->Retailer->query("INSERT INTO refunds (group_id,user_id,shoptrans_id,amount,type,note,date,timestamp) VALUES (".RETAILER.",$userId,$trans_id,$amt,2,'$sms','".date('Y-m-d')."','".date('Y-m-d H:i:s')."')");
                                        $this->General->sendMessage($shop['mobile'],$sms,'notify');
                                        $mail_body = "Incentive of Rs $amt given to retailer ".$shop['mobile'] . " (" . $shop['shopname']. ")";
                                        $mail_body .= "<br/>Retailer's balance after this transaction is $bal";
                                        $this->General->sendMails("Incentive to Retailer", $mail_body,array('dharmesh.chauhan@pay1.in','finance@pay1.in','naziya.khan@pay1.in'),'mail');
//                                        echo $mail_body;
                                }
                                $dataSource->commit($this->Retailer);
                        } catch (Exception $e) {
                                $dataSource->rollback();
                        }
	//			$this->Shop->addOpeningClosing($ret_id,RETAILER,$trans_id,$bal-$amt,$bal);
                        if($source == 'panel') {
                                $result['success'] = $mail_body;
                                return $result;
                        } else {
                                echo $mail_body;
                        }
		} else {
			echo "Retailer id is not right";
		}

		$this->autoRender = false;
	}

        function distributorIncentives() {

                if(isset($_FILES['incentive']) && $_POST['service'] > 0) {

                        if(trim($_FILES['incentive']['type']) == 'text/csv' || trim($_FILES['incentive']['type']) == 'application/vnd.ms-excel') {

                                $file = fopen($_FILES['incentive']["tmp_name"],"r");
                                while(!feof($file)) {
                                        $data = fgetcsv($file, 1024);
                                        if(is_numeric($data[0]) && is_numeric($data[1])) {
                                                $result = $this->incentiveDistributor($data[0], $data[1], $data[2], 'panel', $_POST['service']);

                                                $data1['id']        = $data[0];
                                                $data1['amount']    = $data[1];
                                                $data1['narration'] = $data[2];
                                                if($result['fail']) {
                                                        $data1['status'] = "<span style='color: red; font-weight: bold;'>Fail</span>";
                                                        $data1['remark'] = $result['fail'];
                                                } else {
                                                        $data1['company'] = $result['company'];
                                                        $data1['name'] = $result['name'];
                                                        $data1['mobile'] = $result['mobile'];
                                                        $data1['status'] = "<span style='color: green; font-weight: bold;'>Success</span>";
                                                        $data1['remark'] = $result['success'];
                                                }
                                                $res[] = $data1;
                                        }
                                }
                                fclose($file);
                        } else {

                                $res = "Invalid File Format";
                        }

                        $this->set('res', $res);
                } else {
                        $services = array_map('current', $this->Slaves->query("SELECT parent_id, parent_name FROM services WHERE parent_name IS NOT NULL GROUP BY parent_id"));
                        $this->set('services', $services);
                }
        }

	function incentiveDistributor($id, $amt, $narration=NULL, $source=NULL, $service=1){

		if(empty($id) || empty($amt)){
                    if($source == 'panel') {
			$result['fail'] = "Please hit with id & amount as well";
			return $result;
                    } else {
		        echo "Please hit with id & amount as well";
		        exit;
                    }
                } else if($amt > 20000) {
                    if($source == 'panel') {
                        $result['fail'] = "You are not allowed to transfer more than Rs.20000 at one time";
                        return $result;
                    } else {
		        echo "You are not allowed to transfer more than Rs.20000 at one time";
		        exit;
                    }
		}

		$shop = $this->Slaves->query("SELECT distributors.mobile,distributors.company,distributors.name,distributors.user_id,distributors.gst_no FROM distributors WHERE distributors.id = $id");

                if(empty($shop)){
		    $msg1 = "Distributor id is not right";
		    if($source == 'panel') {
		        $result['fail'] = $msg1;
		        return $result;
		    } else {
		        echo $msg1;
		        exit;
		    }
		}

		$userId = $shop[0]['distributors']['user_id'];
                if($source == 'panel') {
                    $exist = $this->Slaves->query("SELECT count(*) count FROM shop_transactions WHERE shop_transactions.source_id = '$userId' AND shop_transactions.user_id = '$service' AND shop_transactions.amount = $amt AND shop_transactions.date = '".date('Y-m-d')."'");
                    if($exist[0][0]['count'] != 0) {
                                $result['fail'] = "Same transaction is repeated for the day";
                                return $result;
                        }
                }

		if(!empty($shop)){
                        $result['mobile']  = $shop[0]['distributors']['mobile'];
                        $result['company'] = $shop[0]['distributors']['company'];
                        $result['name']    = $shop[0]['distributors']['name'];

                        $dataSource = $this->Retailer->getDataSource($this->Retailer);
		        $dataSource->begin();

                        try {
                                $gst_flag = (strlen($shop[0]['distributors']['gst_no']) < 15) ? false : true;
                                if($gst_flag){
                                    $denom = "1.".SERVICE_TAX_PERCENT;
                                    $tds = ($amt/$denom)*TDS_PERCENT/100;
                                }
                                else $tds = $amt*TDS_PERCENT/100;
                                $amt_settle = $amt - $tds;
                                $bal = $this->Shop->shopBalanceUpdate($amt_settle,'add',$userId,DISTRIBUTOR,$dataSource);

                                $trans_id = $this->Shop->shopTransactionUpdate(REFUND,$amt,$userId,DISTRIBUTOR,$service,null,null,null,$bal-$amt_settle+$tds,$bal+$tds,null,null,$dataSource);
                                $this->Shop->shopTransactionUpdate(TDS,$tds,$userId,$trans_id,$service,null,null,"TDS deducted against txn: $trans_id",$bal+$tds,$bal,null,null,$dataSource);

				if($trans_id === false || empty($trans_id)) {
                                        throw new Exception("Entry can't be added");
                                } else {

                    //			$sms = "Dear Distributor,\nYou have got incentive of Rs $amt from Pay1 company";
                    //			$sms .= "\nYour current balance is now: Rs. " .$bal;

                                        $paramdata['AMOUNT'] = $amt;
                                        $paramdata['BALANCE'] = $bal;
                                        $MsgTemplate = $this->General->LoadApiBalance();
                                        $content =  $MsgTemplate['Distributor_Incentive_MSG'];
                                        $sms = $this->General->ReplaceMultiWord($paramdata,$content);

                                        $txn_id = $this->Retailer->query("SELECT id FROM shop_transactions WHERE type = '".REFUND."' AND source_id = '$userId' AND amount = '$amt' AND date = '".date('Y-m-d')."' AND confirm_flag = 0");
                                        /*if(!empty($txn_id)) {
                                            $this->Retailer->query("INSERT INTO refunds (group_id,user_id,shoptrans_id,amount,type,note,narration,date,timestamp) VALUES (".DISTRIBUTOR.",$userId,".$txn_id[0]['shop_transactions']['id'].",$amt,1,'$sms','$narration','".date('Y-m-d')."','".date('Y-m-d H:i:s')."')");
                                        }*/
                                        $this->General->sendMessage($shop['0']['users']['mobile'],$sms,'notify',$bal,DISTRIBUTOR);

                                        $mail_body = "Incentive of Rs $amt given to distributor ".$shop['0']['distributors']['company'];
                                        $mail_body .= "<br/>Distributor's balance after this transaction is $bal";
                                        $this->General->sendMails("Incentive to Distributor", $mail_body,array('rm@pay1.in','tadka@pay1.in','finance@pay1.in'),'mail');
                                }
                                $dataSource->commit($this->Retailer);
                        } catch (Exception $e) {
                                $dataSource->rollback();
                        }
                        if($source == 'panel') {
                                $result['success'] = $mail_body;
                                return $result;
                        } else {
                                echo $mail_body;
                        }
		}
		$this->autoRender = false;
	}

	function changeDistributorMobileNo($oldNo,$newNo){

            App::import('Controller', 'Apis');
            $obj = new ApisController;
            $obj->constructClasses();

            $verify_otp = 0;
            $verify_otp_fail = 0;


            if($this->RequestHandler->isPost()) {

                $data = $this->params['form'];


                if($data['otp']){

                    $verify_param['mobile'] = $data['master_dist_mob'];
                    $verify_param['otp'] =    $data['otp'];
                    $verify_param['interest'] =   'Distributor';

                    $verifyData =  $obj->verifyOTP($verify_param);

                    //Return Failure for if number not changes

                    if($verifyData['status'] =='failure'){

                        $verify_otp_fail = 1;

                        $verify_response = array(
                            'status' => FALSE ,
                            'errors' => array('code'=>'E000','msg'=>'Please enter correct OTP.')
                        ) ;

                    }else{

                    $verify_otp = 1;

                    }
                }

                $oldNo = isset($data['old_mob_num']) ? $data['old_mob_num'] : 0 ;
                $newNo = isset($data['new_mob_num']) ? $data['new_mob_num'] : 0 ;


                if((empty ($oldNo) || empty ($newNo)) && !$verify_otp){
			$response = array(
                                    'status' => FALSE ,
                                   'errors' => array('code'=>'E001','msg'=>'Please enter old and new Distributor Mobile Number.')
			) ;
                }else{

                    $oldData = $this->User->query("SELECT users.id,user_groups.group_id,users.mobile,distributors.id,distributors.user_id,distributors.company, distributors.parent_id FROM users inner join user_groups ON (user_groups.user_id = users.id) inner JOIN distributors ON (distributors.user_id = users.id) WHERE users.mobile = '".$oldNo."' and user_groups.group_id = ".DISTRIBUTOR);

                    $masterDistData = $this->User->query("SELECT users.mobile FROM users inner join master_distributors ON (master_distributors.user_id = users.id) WHERE master_distributors.id = '".$oldData[0]['distributors']['parent_id']."' ");

                    //Master Distributor Mobile Number of Old Distributor Mobile Number
                    $data['master_dist_mob']  =  $masterDistData[0]['users']['mobile'];

                    if(empty ($oldData)  && !$verify_otp){
				//error user-distributor does'nt exist
				$response = array(
                                            'status' => FALSE ,
                                        'errors' => array('code'=>'E002','msg'=>'Distributor (User) not exist for Given mobile number .')
				) ;

                    }else if(empty ($masterDistData)  && !$verify_otp){

                        //error user-distributor does'nt exist
                            $response = array(
                                        'status' => FALSE ,
                                        'errors' => array('code'=>'E002','msg'=>'Master Distributor (User) not exist for Given Distributor mobile number .')
                            ) ;

                    }else{

				//$oldDistData = $this->User->query("SELECT id,user_id,company FROM distributors WHERE user_id = '".$oldData[0]['users']['id']."'");
				$newData = $this->User->query("SELECT * FROM users WHERE mobile = '".$newNo."'");
				$newSalesmanData = $this->User->query("SELECT * FROM salesmen WHERE mobile = '".$newNo."'");

				if(empty($newData) && empty($newSalesmanData)){//check if new_no not already exist )
					//update the previous number

                                if($verify_otp){

					$this->User->query("update users set mobile = '".$newNo."' where id = ".$oldData[0]['users']['id']);
					$this->User->query("update distributors set mobile = '".$newNo."' where user_id = ".$oldData[0]['users']['id']);
					$this->User->query("update salesmen set mobile = '".$newNo."' where user_id = ".$oldData[0]['users']['id']);
					$this->User->query("update retailers set mobile = '".$newNo."' where user_id = ".$oldData[0]['users']['id']);

                                        $response = array(
                                            'status' => true ,
                                        'success' => array('code'=>'S001','msg'=>'Distributor Number changed successfully .')
					) ;
					$data['OLD_NUMBER'] = $oldNo;
					$data['NEW_NUMBER'] = $newNo;
					$MsgTemplate = $this->General->LoadApiBalance();
					$content =  $MsgTemplate['Change_Distributor_Number'];
                                        $sms = $this->General->ReplaceMultiWord($data,$content);

					$this->General->sendMessage($oldNo.",".$newNo,$sms,'shops');

                                }else if(!$verify_otp_fail){

                                    $sendOTPdata['mobile'] = $data['master_dist_mob'];
                                    $sendOTPdata['interest'] = 'Distributor';
                                    $sendOTPdata['change_dist_mob_otp_flag'] = 1;

                                    $obj->sendOTPToRetDistLeads($sendOTPdata);
                                }
					//sms code

				}else{//( if  existing number )
					$response = array(
                                            'status' => false ,
                                            'errors' => array('code'=>'E004','msg'=>'Given New Mobile No is already exist ')
						) ;
				}

			}
		}

                if($verify_otp)
                   $this->redirect('/panels');

                $this->set("data",$data);
                $this->set("response",$response);
                $this->set("verify_response",$verify_response);
                $this->set("verify_otp",$verify_otp);

            }
	}

	function floatGraph(){

		if(empty($_SESSION['Auth']))$this->redirect('/');
		$dt_to = isset($_REQUEST['from']) ? $_REQUEST['to'] : "";
		$dt_from = isset($_REQUEST['to']) ? $_REQUEST['from'] : "";
		$type = empty($_REQUEST['type']) ? "hourly" : $_REQUEST['type'];
		$qp = "";
		$to = "";
		$from = "";

		$qp = " where 1";
		if (!empty($dt_to) && !empty($dt_from)) {
			$qp = $qp . " and ( date >= '" . substr($dt_from, 4, 4) . "-" . substr($dt_from, 2, 2) . "-" . substr($dt_from, 0, 2) . "' and date <= '" . substr($dt_to, 4, 4) . "-" . substr($dt_to, 2, 2) . "-" . substr($dt_to, 0, 2) . "' ) or `date` = '".date("Y-m-d")."'";

			$to = substr($dt_to, 4, 4) . "-" . substr($dt_to, 2, 2) . "-" . substr($dt_to, 0, 2);
			$from = substr($dt_from, 4, 4) . "-" . substr($dt_from, 2, 2) . "-" . substr($dt_from, 0, 2);
			$interval = date_diff(date_create($from), date_create($to));
			if (intval($interval->format('%a')) > 15) {// if request is for more than 15 days then it will show only 15 days before data of  to_date
				//$to = date("Y-m-d");
				$date = new DateTime($to);
				$date->modify('-15 day');
				$from = $date->format('Y-m-d');
			}
		} else {
			//date ( $format, strtotime ( '-7 day' . $date ) )
			$to = date("Y-m-d");
			$date = new DateTime($to);
			$date->modify('-7 day');
			$from = $date->format('Y-m-d');
			$qp = $qp . " and date >= '" . $from . "' and date <= '" . $to . "'";
		}


		$optionSales = array(
                'title' => 'Hourly Sale Report',
                'width' => 1200,
                'height' => 600,
                'vAxis' => array('title' => "Amount"),
                'hAxis' => array('title' => "Hours"),
                'seriesType' => "bars",
                'series' => array(
		1 => array("type" => "line", 'curveType'=> "function",'pointSize' => 2 , 'color'=>'red'),
		2 => array("type" => "line",'curveType'=> "function", 'pointSize' => 3,'color'=>'green'),
		3 => array("type" => "line",'curveType'=> "function", 'pointSize' => 3,'color'=>'#5EFB6E'))//1 => array("type" => "line",'curveType'=> "function", 'pointSize' => 3),
		);



		$datas = $this->Slaves->query("SELECT id , sale , float_logs.float , `date` , hour FROM float_logs $qp order by `date` , `hour` "); //and hour = 24

		$dateWiseFloatData = array();

		$datewiseSaleGraphData = array();
		$hourlyData = array();
		$hourlyTodayData = array(); // , 1 =>634.5 ,2 =>172 ,  1 =>634.5 , 2 =>172
		$hourlyYesterdayData = array(); //
		$hourlyTillTodayData = array();
		$hourlyTillYesterdayData = array();
		$prevHrSale = 0;
		$prevDate = "";
		$tillHourData = array();
		$totalTillHourData = array();
		$avgTillHourData = 0;
		$countDayEndHourData = 0;
		$sumDayEndHourData = 0;
		foreach ($datas as $data) {
			$d = $data['float_logs'];
			$hrSale = 0;

			if ($d['hour'] == 1) {
				$hrSale = $d['sale'];
			} else {
				$hrSale = $d['sale'] - $prevHrSale;
			}
			$prevHrSale = $d['sale'];
			$prevDate = $d['date'];
			if (!isset($hourlyData[$d['hour']])) {
				$hourlyData[$d['hour']] = array();
			}
			if ($d['date'] != date('Y-m-d')) {
				array_push($hourlyData[$d['hour']], $hrSale);
			}

			if (!isset($tillHourData[$d['hour']])) {
				$tillHourData[$d['hour']] = array();
			}
			if ($d['date'] != date('Y-m-d')) {
				array_push($tillHourData[$d['hour']], $d['sale']);
			}
			if ($d['date'] == date('Y-m-d')) {
				$hourlyTodayData[$d['hour']] = $hrSale;
				$hourlyTillTodayData[$d['hour']] = $d['sale'];
			}
			if ($d['date'] == date("Y-m-d", strtotime( '-1 days' ) )) {
				$hourlyYesterdayData[$d['hour']] = $hrSale;
				$hourlyTillYesterdayData[$d['hour']] = $d['sale'];
				//$hourlyTillTodayData[$d['hour']] = $d['sale'];
			}


			$dtStr = date("d-M-Y", strtotime($data['float_logs']['date']));
			if ($data['float_logs']['hour'] == 24) {
				array_push($totalTillHourData , $data['float_logs']['sale']);

				$sumDayEndHourData = $sumDayEndHourData + $data['float_logs']['sale'];
				$countDayEndHourData++;
				array_push($datewiseSaleGraphData, array($dtStr, intval($data['float_logs']['sale'])));

				//$dateWiseFloatData[$dtStr]['day_end'] = $data['float_logs']['float'];
			}
			$dateWiseFloatData[$dtStr]['day_end'] = $data['float_logs']['float'];
			//$dateWiseFloatData[$dtStr]['min'] =
			if (isset($dateWiseFloatData[$dtStr]['min'])) {
				if ($dateWiseFloatData[$dtStr]['min'] >= $d['float']) {
					$dateWiseFloatData[$dtStr]['min'] = $d['float'];
					$dateWiseFloatData[$dtStr]['min_hour'] = $d['hour'];
				}

				if ($dateWiseFloatData[$dtStr]['max'] <= $d['float']) {
					$dateWiseFloatData[$dtStr]['max'] = $d['float'];
					$dateWiseFloatData[$dtStr]['max_hour'] = $d['hour'];
				}
			} else {
				$dateWiseFloatData[$dtStr]['min'] = $d['float'];
				$dateWiseFloatData[$dtStr]['min_hour'] = $d['hour'];

				$dateWiseFloatData[$dtStr]['max'] = $d['float'];
				$dateWiseFloatData[$dtStr]['max_hour'] = $d['hour'];
			}
			//array_push($graphData3['data'],array($dtStr,$data['float_logs']['float']));
		}

		$hourlyAvgData = array();
		foreach ($hourlyData as $key => $data) {
			$hourlyAvgData[$key] = array_sum($data) / count($data);
		}

		$datewiseSaleGraphDataWithAvg = array();
		$avgDayEndHourData = $countDayEndHourData==0 ? 0 : $sumDayEndHourData / $countDayEndHourData ;
		foreach ($datewiseSaleGraphData as $key => $data) {
			array_push( $datewiseSaleGraphDataWithAvg , array($data[0], $data[1] ,$avgDayEndHourData) );
		}

		array_unshift($datewiseSaleGraphDataWithAvg, array('Date', 'Sale' ,'Average'));
		$this->set('datewisesaledata', $datewiseSaleGraphDataWithAvg);


		$tillHourAvgData = array();
		foreach ($tillHourData as $key => $data) {
			$tillHourAvgData[$key] = array_sum($data) / count($data);
		}
		$avgTillHourData = count($totalTillHourData) == 0 ? 0 : array_sum($totalTillHourData) / count($totalTillHourData);



		$saleData = array();
		$sumAvg = 0;
		$sumCurr = 0;
		$perDiff = 0;
		$hdAvg = 0;
		$hdCurr = 0;
		$prevAvg = 0;
		$prevCurr = 0;
		$sumDip = 0;
		$countDip = 0;
		$finalSale = 0;
		$calPerNumSum = 0;
		$calPerDenSum = 0;
		$calPer = 0;
		// main logic part
		foreach ($hourlyAvgData as $h => $data) {
			$yhd = intval(empty($hourlyYesterdayData[$h]) ? 0 : $hourlyYesterdayData[$h]);
			if (isset($hourlyTodayData[$h])) {

				$diffTill = $hourlyTillTodayData[$h] - $tillHourAvgData[$h];
				$perDiffTill = $tillHourAvgData[$h] * 100 / $tillHourAvgData[24];

				$calPerNum = ( $diffTill / $tillHourAvgData[$h] ) * $perDiffTill; // numerator single part
				$calPerDen = $perDiffTill;
				$calPerNumSum = $calPerNumSum*0.5 + $calPerNum;//numerator part
				$calPerDenSum = $calPerDenSum*0.5 + $calPerDen;//denominator part
				$calPer = $calPerNumSum / $calPerDenSum * 100;// final weighted mean (nuemerator part / denominator part)

				$temp = array($h . "", intval($data),intval($tillHourAvgData[$h]),intval($hourlyTillTodayData[$h]),intval($hourlyTillYesterdayData[$h]));//, intval($hourlyTodayData[$h])

				//$finalSale = $finalSale + $prevCurr;
			} else { // here we will calculate exacted data

				//echo $calPer . "<br/>";
				$expDiffTillSale = $tillHourAvgData[$h] * $calPer / 100;
				$expTillSale = $tillHourAvgData[$h] + $expDiffTillSale;

				$temp = array($h . "", intval($data),intval($tillHourAvgData[$h]),intval($expTillSale),intval($hourlyTillYesterdayData[$h]));//, intval($expSale)

				//$finalSale = $finalSale + $prevCurr;
			}
			array_push($saleData, $temp);
		}
		array_unshift($saleData, array('Hours', 'Average',"Avg Sale Trend","Today's Sale Trend","Yesterday's Sale"));//,"Today's Sale Trend" //, 'Today' . " = " . round($finalSale, 0) . " \n  [ " . round($perDiff, 2) . " % ]"
		//echo json_encode($saleData);
		$this->set('saledata', $saleData);
		$this->set('type', $type);
		$this->set('to', $to);
		$this->set('from', $from);

		$this->set('optionSales', $optionSales);

		$floatdata = array(
		// array('Date', 'Min','Max','Day End'),
		// array('01-May-2013',  165, 551.5 ,938      ),
		// array('29-May-2013', 135,  627.5 ,  1120   )
		);
		foreach ($dateWiseFloatData as $key => $data) {
			array_push($floatdata, array($key, "", intval($data['min']), intval($data['max']), intval(isset($data['day_end']) ? $data['day_end'] : 0)));
		}


		$optionFloat = array(
                'title' => 'Daily Float Report',
                'width' => 1200,
                'height' => 600,
                'vAxis' => array('title' => "Amount"),
                'hAxis' => array('title' => "Date"),
                'seriesType' => "bars",
                'tooltip' => array('isHtml' => true),
                'series' => array(0 => array('type' => 'line', 'pointSize' => 5), 1 => array('type' => 'line', 'pointSize' => 5), 2 => array('type' => 'line', 'pointSize' => 5)),
                'focusTarget' => 'category',
		);
		$this->set('floatdata', $floatdata);
		$this->set('optionFloat', $optionFloat);


		$optionDateWiseSale = array(
                'title' => 'Daily Sale Report',
                'width' => 1200,
                'height' => 600,
                'vAxis' => array('title' => "Amount"),
                'hAxis' => array('title' => "Date"),
                'seriesType' => "bars",
                'series' => array(0 => array('type' => 'line', 'pointSize' => 5),1 => array('type' => 'line', 'pointSize' => 5))//
		);

		$this->set('optionDateWiseSale', $optionDateWiseSale);
		$this->render('float_graph');
	}


	function distTopUpRequest(){
		$dist_user = $this->General->getUserDataFromId($_SESSION['Auth']['user_id']);
			$this->set('mobile',$dist_user['mobile']);//home

			$banks = $this->Shop->getBanks();
			$this->set('banks',$banks);

			if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
				$top_tab = 'activity';
			}else{
				$top_tab = 'home';
			}
			$this->set('top_tab',$top_tab);
			$this->set('side_tab','topup_request');
			$this->render('dist_topup_request');
	}

	function allRetailerTrans($ret_id = 0,$productId=0,$date=null,$page=1,$itemsPerPage=20){

		if(empty($date)){
			$date = date('dmY')."-".date('dmY');
		}

		//$limit = " limit " . ($page-1)*PAGE_LIMIT.",".PAGE_LIMIT;

		$dates = explode("-",$date);
		$date_from = $dates[0];
		$date_to = $dates[1];

		$transactions = array();
		if(checkdate(substr($date_from,2,2), substr($date_from,0,2), substr($date_from,4)) && checkdate(substr($date_to,2,2), substr($date_to,0,2), substr($date_to,4))){
			$date_from =  substr($date_from,4) . "-" . substr($date_from,2,2) . "-" . substr($date_from,0,2);
			$date_to =  substr($date_to,4) . "-" . substr($date_to,2,2) . "-" . substr($date_to,0,2);
		}else{
			$date_from =  date('Y') . "-" . date('m') . "-" . date('d');
			$date_to =  date('Y') . "-" . date('m') . "-" . date('d');
		}
		$this->set('date_from',$date_from);
		$this->set('date_to',$date_to);
		$this->set('page',$page);
		$this->set('ret_id',$ret_id);

		//($date,$page=1,$service = null,$date2=null,$itemsPerPage=0,$retailerId=0,$operatorId=0)
		$records = $this->Shop->getLastTransactions($date_from,$page,null,$date_to,$itemsPerPage,$ret_id,$productId);

		$products = $this->Slaves->query("SELECT id , name  FROM `products` WHERE to_show = 1 ORDER BY name asc");
		$this->set('productId',$productId);
		$this->set('products',$products);


		$this->set('transactions',$records['ret']);
		$this->set('total_count',$records['total_count']);
		$this->set('side_tab','allRetailerTrans');
		$this->render('dist_retailer_trans');//$this->autoRender = false;
	}
	function testXml(){
		/*$xml = $string = <<<XML
		 <?xml version='1.0'?>
		 <document>
		 <title>Forty What?</title>
		 <from>Joe</from>
		 <to>Jane</to>
		 <body>
		 I know that's the answer -- but what's the question?
		 </body>
		 </document>
		 XML;
		 $array = json_decode(json_encode((array)simplexml_load_string($xml)),1);
		 print_r($array);*/
		$dom = new DOMDocument;
		$dom->loadXML("<document>
 <title>Forty What?</title>
 <from>Joe</from>
 <to>Jane</to>
 <body>
  I know that's the answer -- but what's the question?
 </body>
</document>");
		if (!$dom) {
			echo 'Error while parsing the document';
			exit;
		}

		$s = simplexml_import_dom($dom);
		$array = json_decode(json_encode($s),1);
		print_r($array);//->document[0]->title;

	}


	function lastTransferred(){
		$shop_id = $_REQUEST['id'];

                if(isset($_REQUEST['type']))
                    $transfer_type = $_REQUEST['type'];

		$source_id = $this->info['id'];
                $comm_type = '';
                $type = '';

		if($this->Session->read('Auth.User.group_id') == ADMIN){
			$type = 0;
			$comm_type = 5;
			$source_id = 0;
		}
		else if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR){
			$type = 1;
			$comm_type = 6;
		}
		else if($this->Session->read('Auth.User.group_id') == DISTRIBUTOR){
			$type = 2;
			$comm_type = 7;
		}

		if($source_id == 0){
			$records = $this->Slaves->query("SELECT st1.id,st1.amount,st1.note,st2.amount,st1.timestamp FROM shop_transactions as st1 left join shop_transactions as st2 ON (st2.target_id = st1.id AND st2.type = $comm_type)  WHERE st1.target_id = $shop_id AND st1.type = $type AND st1.date >='".date('Y-m-d',strtotime('-7 days'))."' order by st1.id desc limit 5");
		}

else {
                        if(isset($transfer_type) && $transfer_type == 'Salesman') {
                                $records_temp = $this->Slaves->query("SELECT id, amount, note, timestamp FROM shop_transactions WHERE source_id = $source_id AND target_id = $shop_id AND type = ".DIST_SLMN_BALANCE_TRANSFER." AND confirm_flag != 1 AND date >= ".date('Y-m-d',strtotime('-7 days'))." ORDER BY 1 DESC LIMIT 5");
                                $i = 0;
                                foreach($records_temp as $rec) {
                                        $records[$i]['st1'] = array('id' => $rec['shop_transactions']['id'], 'amount'=> $rec['shop_transactions']['amount'], 'note' => $rec['shop_transactions']['note'], 'timestamp' => $rec['shop_transactions']['timestamp']);
                                        $records[$i]['st2'] = array('amount'=> 0);
                                        $i++;
                                }
                        } else if(isset($transfer_type) && $transfer_type == 'Retailer') {
                                $records_temp = $this->Slaves->query("SELECT st.id, st.amount, st.note, st.timestamp FROM shop_transactions st JOIN salesmen ON (st.source_id = salesmen.id) WHERE salesmen.dist_id = $source_id AND confirm_flag != 1 AND st.user_id IS NOT NULL AND st.target_id = $shop_id AND st.type = ".SLMN_RETL_BALANCE_TRANSFER." AND st.date >= ".date('Y-m-d',strtotime('-7 days'))." ORDER BY 1 DESC LIMIT 5");
                                $i = 0;
                                foreach($records_temp as $rec) {
                                        $records[$i]['st1'] = array('id' => $rec['st']['id'], 'amount'=> $rec['st']['amount'], 'note' => $rec['st']['note'], 'timestamp' => $rec['st']['timestamp']);
                                        $records[$i]['st2'] = array('amount'=> 0);
                                        $i++;
                                }
                        } else if($this->Session->read('Auth.User.group_id') == SUPER_DISTRIBUTOR) {
                                $records_temp = $this->Slaves->query("SELECT id, amount, note, timestamp FROM shop_transactions WHERE source_id = '".$this->Session->read('Auth.id')."' AND target_id = $shop_id AND type = '".SDIST_DIST_BALANCE_TRANSFER."' AND confirm_flag != 1 AND date >= ".date('Y-m-d',strtotime('-7 days'))." ORDER BY 1 DESC LIMIT 5");
                                $i = 0;
                                foreach($records_temp as $rec) {
                                        $records[$i]['st1'] = array('id' => $rec['shop_transactions']['id'], 'amount'=> $rec['shop_transactions']['amount'], 'note' => $rec['shop_transactions']['note'], 'timestamp' => $rec['shop_transactions']['timestamp']);
                                        $records[$i]['st2'] = array('amount'=> 0);
                                        $i++;
                                }
                        } else {
                                $records = $this->Slaves->query("SELECT st1.id,st1.amount,st1.note,st2.amount,st1.timestamp FROM shop_transactions as st1 left join shop_transactions as st2 ON (st2.target_id = st1.id AND st2.type = $comm_type)  WHERE st1.source_id = $source_id AND st1.target_id = $shop_id AND st1.type = $type AND st1.date >='".date('Y-m-d',strtotime('-7 days'))."' order by st1.id desc limit 5");
                                if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR) {
                                        $dist = $this->Slaves->query("SELECT margin, commission_type FROM distributors WHERE id = '$shop_id'");
                                        $records = array('rec'=>$records, 'dist'=>array('comm'=>$dist[0]['distributors']['commission_type'],'margin'=>$dist[0]['distributors']['margin']));
                                }
                        }
		}

		echo json_encode($records);
		$this->autoRender = false;
	}


        function pullBackApproval(){
           // echo "1"; exit;
            if ($this->RequestHandler->isPost() || $this->RequestHandler->isPut()){
                $trnsID = $_REQUEST['trans_id'];
                if(empty($trnsID)){
                    $this->Session->setFlash("<div class='error'>Transaction ID can not be empty !</div>", true);
                    $this->render("/elements/shop_pulllback_approval");

                }else{
                    $statusID = $_REQUEST['status_id'];
                    $check = $this->Retailer->query("SELECT confirm_flag FROM shop_transactions WHERE id = '$trnsID' AND confirm_flag != 1 AND type_flag != 5");
                    if(!empty($check)){
                    	$this->Retailer->query("UPDATE shop_transactions SET confirm_flag = '$statusID'  WHERE id = '$trnsID' ");
                    	$this->Session->setFlash("<div class='success'>Transaction approved.</div>", true);
                    }
                    else {
                    	$this->Session->setFlash("<div class='error'>Transaction ID already pulled back or cannot be pulled back!</div>", true);
                    }
                    $this->render("/elements/shop_pulllback_approval");

                }

            }else{
                $this->render("pulllback_approval");
        }
        }



        function uploadImages($upFileName,$fileN){
        	$filename = "uploadKYCDocuments".date('Ymd').".txt";
        	$this->General->logData('/mnt/logs/'.$filename,"inside uploadImages ::files::".json_encode($_FILES));
            $response = array();
            for($i=0;$i<count($_FILES[$upFileName]["name"]);$i++){
                try {

        
                        if (!isset($_FILES[$upFileName]['error'][$i]) || is_array($_FILES[$upFileName]['error'][$i])){
                            throw new RuntimeException('Invalid parameters.');
                        }

                        // Check $_FILES['upfile']['error'] value.
                        switch ($_FILES[$upFileName]['error'][$i]) {
                            case UPLOAD_ERR_OK:
                                break;
                            case UPLOAD_ERR_NO_FILE:
                                throw new RuntimeException('No file sent.');
                            case UPLOAD_ERR_INI_SIZE:
                            case UPLOAD_ERR_FORM_SIZE:
                                throw new RuntimeException('Exceeded filesize limit.');
                            default:
                                throw new RuntimeException('Unknown errors.');
                        }

                        // You should also check filesize here.
                        if ($_FILES[$upFileName]['size'][$i] > 5000000) {//5 MB
                            throw new RuntimeException('Exceeded filesize limit.');
                        }

                        // DO NOT TRUST $_FILES[$upFileName]['mime'] VALUE !!
                        // Check MIME Type by yourself.
                        try{
                            $finfo = new finfo(FILEINFO_MIME_TYPE);
                        }catch(Exception $e){
                            echo $e ;
                        }

                        $var = $finfo->file($_FILES[$upFileName]['tmp_name'][$i]);

                        if (false === $ext = array_search(
                            $finfo->file(
                                $_FILES[$upFileName]['tmp_name'][$i]),
                                array(
                                    'jpg' => 'image/jpeg',
                                    'png' => 'image/png',
                                    'gif' => 'image/gif',
                                ),
                                true
                        )) {

                            throw new RuntimeException('Invalid file format.');
                        }
                        // You should name it uniquely.
                        // DO NOT USE $_FILES[$upFileName]['name'] WITHOUT ANY VALIDATION !!
                        // On this example, obtain safe unique name from its binary data.
                        $rand = rand(1000,9999);
                        //echo $_FILES[$upFileName]['tmp_name'][$i];
                        //die;

						App::import('vendor', 'S3', array('file' => 'S3.php'));
						$bucket = s3kycBucket;
						$s3 = new S3(awsAccessKey, awsSecretKey);
						$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
						$retInfo = explode('_', $fileN);
						$actual_image_name = $fileN . "_" . $rand . "." . $ext;
						if ($s3->putObjectFile($_FILES[$upFileName]['tmp_name'][$i], $bucket, $actual_image_name, S3::ACL_PUBLIC_READ)) {
						$imgUrl = 'http://' . $bucket . '.s3.amazonaws.com/' . $actual_image_name;
						if($_POST['verify_flag'] == 1){
							$this->Retailer->query("insert into retailers_docs
									(retailer_id, type, src, uploader_user_id)
								values('" . $retInfo[2] . "', '" . $upFileName . "', '" . $imgUrl . "', '".$_SESSION['Auth']['user_id']."')");
						}
						else {
							$this->Retailer->query("insert into retailers_details
									(retailer_id, type, image_name, uploader_user_id)
								values('" . $retInfo[2] . "', '" . $upFileName . "', '" . $imgUrl . "', '".$_SESSION['Auth']['user_id']."')");
						}

						//echo "<img src='$imgUrl' style='max-width:400px'/><br/>";
						array_push($response, array(
							'status' => 'success',
							'filename' => $_FILES[$upFileName]['name'][$i],
							'msg' => 'File is uploaded successfully.'
					));
				   } else {
					array_push($response, array(
							'status' => 'faliure',
							'filename' => $_FILES[$upFileName]['name'][$i],
							'msg' => 'File is uploaded successfully.'
					));
				  }
				} catch (RuntimeException $e) {
					array_push($response, array(
							'status' => 'failure',
							'filename' => $_FILES[$upFileName]['name'][$i],
							'msg' => $e->getMessage()
					));
				}
			}


            return $response;
        }

        
        function recheckTrans() {

        	if ($this->RequestHandler->isAjax()) {
        		$id = trim($_POST["shop_transid"]);
        		if (isset($id) && !empty($id)) {
        			$checktrans = $this->User->query("Select `status` from pg_payuIndia where shop_transaction_id  = $id");
        			if (!empty($checktrans) && $checktrans[0]['pg_payuIndia']['status'] == 'failure') {
        				$this->User->query("UPDATE shop_transactions SET confirm_flag = '0' WHERE id = $id");
        				$this->User->query("UPDATE pg_payuIndia SET status = 'Pending' WHERE shop_transaction_id = $id");
        				echo "Data updated successfully";
        				exit();
        			}
        			else {
        				echo "Transaction not found";
        				exit();
        			}
        		}
        	}
    	}

    	function limitTransfer() {

    		if($this->RequestHandler->isPost()) {
    			$file = $_FILES['upload_file']['name'];
		        if (!empty($file)) {
                                $allowedExtension = array("xls");
                                $getfileInfo = pathinfo($file, PATHINFO_EXTENSION);
                                if (in_array($getfileInfo, $allowedExtension)) {
                                        if (!move_uploaded_file($_FILES['upload_file']['tmp_name'], "/tmp/" . $file)) {
                                                echo $msg = "Failed to move uploaded file.";
                                                die;
                                        }
                                        chmod("/tmp/". $file, 777);
                                } else {
                                        echo $msg = "Invalid File Format!!!!!";
                                        die;
                                }
                                $arrayRecord = array();
    				$amountArray = array();
    				$filepath = "/tmp/" . $file;
    				App::import('Vendor', 'excel_reader2');
    				$excel = new Spreadsheet_Excel_Reader($filepath, true);
    				$data = $excel->sheets[0]['cells'];
    				foreach ($data as $key => $value) {
    					if (isset($value) && count($value)>5) {
//                                              $txnId          = substr($file,0,3) == 'sbi' ? substr($file, 4, 10) . '_' . $value[7] : trim($value[2]);
                                                $txnId          = substr($file,0,3) == 'sbi' ? date('d-m-Y', strtotime(str_ireplace('/','-', $value[2]))) . '_' . $value[7] : trim($value[2]);
//                                                $valuedate      = substr($file,0,3) == 'sbi' ? substr($file, 4, 10) : trim($value[3]);
                                                $valuedate      = substr($file,0,3) == 'sbi' ? date('d-m-Y', strtotime(str_ireplace('/','-', $value[2]))) : trim($value[3]);
//                                                $txnPostedDate  = substr($file,0,3) == 'sbi' ? date('d-m-Y h:i:s A', strtotime(substr($file, 4, 10))) : trim($value[4]);
                                                $txnPostedDate  = substr($file,0,3) == 'sbi' ? date('d-m-Y h:i:s A', strtotime(str_ireplace('/','-', $value[2]))) : trim($value[4]);
                                                $description    = substr($file,0,3) == 'sbi' ? $value[3] : $value[6];
                                                $transType      = substr($file,0,3) == 'sbi' ? ($value[5] > 0 ? 'DR' : 'CR') : trim($value['7']);
                                                $transAmt       = substr($file,0,3) == 'sbi' ? ($transType == 'DR' ? intval(str_replace(",","",trim($value[5]))) : intval(str_replace(",","",trim($value[6])))) : intval(str_replace(",","",trim($value['8'])));

                                                if(substr($file,0,3) == 'sbi') {
                                                    $val[1] = $value[1];
                                                    $val[2] = $txnId;
                                                    $val[3] = $valuedate;
                                                    $val[4] = $txnPostedDate;
                                                    $val[6] = $description;
                                                    $val[7] = $transType;
                                                    $val[8] = $transAmt;
                                                    $val[9] = $value[7];
                                                    $value = $val;
                                                }

						if(!empty($txnId)){
                                                        $param['bankName'] = substr($file,0,3) == 'sbi' ? 'SBI' : 'ICICI6714';
    							$checkduplicateTrans = $this->Retailer->query("Select id FROM bank_transactions where bank_name ='" . $param['bankName'] . "' AND bank_txnid = '".$txnId."'");
    							if(count($checkduplicateTrans)){
    								$arrayRecord['alreadydone'][] = $value;
    							}
    						}
    						if($transType == 'DR'){
    							$arrayRecord['failed'][] = $value;
    							if($description == 'CASH PAID:'){
    								$amountArray[] = $transAmt;
                                                        }
    						}
    						if ((strpos($description, 'BY CASH ') !== false || strpos($description, 'CSH DEP (CDM)-CARDLESS DEPOSITBY ') !== false) && $transType == 'CR' && !in_array($transAmt, $amountArray) && empty($checkduplicateTrans)) {
    							$description = str_replace("  ", " ", $description);
    							$getdesription = explode(" ", $description);
    							if (count($getdesription) > 2) {
                                                                $mobileNo = ($param['bankName'] == 'SBI') ? (strlen($getdesription[4]) < 10 ? str_replace('-','',$getdesription[5]) : $getdesription[4]) : substr($getdesription[2],-10);
    								$checkRecords = $this->Slaves->query("SELECT users.id,name,group_id FROM users JOIN user_groups ON (users.id = user_groups.user_id AND user_groups.group_id IN (".DISTRIBUTOR.",".RETAILER.")) WHERE mobile = '" . $mobileNo . "'");
    								if (count($checkRecords) > 0) {
    									if (!empty($checkRecords[0]['user_groups']['group_id'])) {
    										if ($checkRecords[0]['user_groups']['group_id'] == RETAILER) {
    											$getRetailerInfo = $this->Slaves->query("SELECT retailers.id,retailers.user_id,retailers.balance,retailers.parent_id,distributors.id,distributors.user_id FROM retailers INNER JOIN  distributors ON retailers.parent_id = distributors.id where retailers.user_id='" . $checkRecords[0]['users']['id'] . "' AND distributors.id in (".DISTS.")");
    											if (count($getRetailerInfo) > 0) {
    												$disData = $this->Slaves->query("SELECT * from users JOIN user_groups ON (users.id = user_groups.user_id AND user_groups.group_id = ".DISTRIBUTOR.") where users.id ='" . $getRetailerInfo[0]['distributors']['user_id'] . "'");
    												if (count($disData) > 0) {
    													$info = $this->Shop->getShopData($disData[0]['users']['id'], $disData[0]['user_groups']['group_id']);
    													$info['User']['group_id'] = $disData[0]['user_groups']['group_id'];
    													$info['User']['id'] = $disData[0]['users']['id'];
    													$info['User']['mobile'] = $disData[0]['users']['mobile'];
    													$param['amount'] = intval($transAmt);
    													$param['retailer'] = $getRetailerInfo[0]['retailers']['id'];
    													$param['app_flag'] = 2;
    													$param['txnId'] = $txnId;
    													$result =$this->amountTransfer($param,$info);
    													if($result['status']=='success'){
															$value['txnid'] = $result['shopId'];
															$value['msg'] = $result['msg'];
    														$arrayRecord['transfer'][] = $value;
    													} else {
															$value['msg'] = $result['description'];
    														$arrayRecord['failed'][] = $value;
    													}
    												}
    												else {
														$value['msg'] = "Distributors does not Exists";
    													$arrayRecord['failed'][] = $value;
    												}
    											}
    											else {
													$value['msg'] = "Retailer does not Exists";
    												$arrayRecord['failed'][] = $value;
    											}
    										} else if ($checkRecords[0]['user_groups']['group_id'] == DISTRIBUTOR) {

    											$getDistributorInfo = $this->Slaves->query("SELECT distributors.id,distributors.user_id,distributors.margin,distributors.parent_id,master_distributors.id,master_distributors.user_id FROM distributors INNER JOIN master_distributors ON distributors.parent_id = master_distributors.id where distributors.user_id='" . $checkRecords[0]['users']['id'] . "' AND master_distributors.id in (".MDISTS.")");
    											if(count($getDistributorInfo)>0){
    												$masterDisdata = $this->Slaves->query("SELECT * from users JOIN user_groups ON (users.id = user_groups.user_id AND user_groups.group_id = ".MASTER_DISTRIBUTOR.") WHERE users.id ='".$getDistributorInfo[0]['master_distributors']['user_id']."'");
    												if(count($masterDisdata)>0){
    													$info = $this->Shop->getShopData($masterDisdata[0]['users']['id'], $masterDisdata[0]['user_groups']['group_id']);
    													$info['User']['group_id'] = MASTER_DISTRIBUTOR;
    													$info['User']['id'] = $masterDisdata[0]['users']['id'];
    													$info['User']['mobile'] = $masterDisdata[0]['users']['mobile'];
    													$param['amount'] = intval($transAmt);
                                                                                                        //$param['margin'] = round($transAmt*($getDistributorInfo[0]['distributors']['margin']-0.05)/100,2);

    													$param['margin'] = round($transAmt*$getDistributorInfo[0]['distributors']['margin']/100,2);
    													$param['retailer'] = $getDistributorInfo[0]['distributors']['id'];
    													$param['app_flag'] = 2;
    													$param['txnId'] = $txnId;

    													$result = $this->amountTransfer($param,$info);
    													if($result['status']=='success'){
    														$value['txnid'] = $result['shopId'];
															$value['msg'] = $result['msg'];
    														$arrayRecord['transfer'][] = $value;
    													} else {
															$value['msg'] = $result['description'];
    														$arrayRecord['failed'][] = $value;

    													}

    												}
    												else {
														$value['msg'] = "Master Distributors does not Exists";
    													$arrayRecord['failed'][] = $value;
    												}
    											}
    											else {
													$value['msg'] = "Distributors does not Exists";
    												$arrayRecord['failed'][] = $value;
    											}
    										}
    										else {
												$value['msg'] = "Invalid group Id";
    											$arrayRecord['failed'][] = $value;
    										}
    									}
    								} else {
										$value['msg'] = "Number does not exist!!!";
    									$arrayRecord['failed'][] = $value;

    								}
    							}
    						} else {
    							if((strpos($description, 'BY CASH ') == false || strpos($description, 'CSH DEP (CDM)-CARDLESS DEPOSITBY ') == false) && $transType=='CR' && empty($checkduplicateTrans)){
									//$value['msg'] = "";
    								$arrayRecord['failed'][] = $value;
    							}

    						}
    					}
    				}
    				$this->set('transferRecord',$arrayRecord);
    				unlink($filepath);
    			}
    		}
        }

function pullbackRefund(){
        if($this->RequestHandler->isAjax()){
            $dataS = $this->User;
            $dataSource = $dataS->getDataSource();
            
            try{
                $shopId = $_POST['shop_id'];
                if( ! empty($shopId)){
                    $dataSource->begin($dataS);
                    $shopdata = $dataSource->query("SELECT * FROM shop_transactions WHERE id = '" . $shopId . "' AND type = '" . REFUND . "' AND confirm_flag = 0");
                    
                    if( ! empty($shopdata)){
                        $groupId = $shopdata[0]['shop_transactions']['target_id'];
                        $amount = $shopdata[0]['shop_transactions']['amount'];
                        $userId = $shopdata[0]['shop_transactions']['source_id']; // ret_userid,dist_userid,sup_userid
                        $serviceId = $shopdata[0]['shop_transactions']['user_id'];
                        $date = $shopdata[0]['shop_transactions']['date'];
                        
                        $tdsdata = $dataSource->query("SELECT * FROM shop_transactions WHERE target_id = '" . $shopId . "' AND type = '" . TDS . "' AND date='" . $shopdata[0]['shop_transactions']['date'] . "'");
                        if( ! empty($tdsdata)){
                            $tds = $tdsdata[0]['shop_transactions']['amount'];
                            $amount = $amount - $tds;
                        }
                        
                        // $getUserdata = $this->Shop->getShopDataById($id,$groupId);
                        $getUserdata = $dataSource->query("select mobile from users where id = '" . $userId . "'");
                        
                        $bal = $this->Shop->shopBalanceUpdate($amount, 'subtract', $userId, $groupId,$dataSource);
                        //$dataSource->query("DELETE FROM refunds WHERE shoptrans_id = '" . $shopId . "'");
                        $dataSource->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE id ='" . $shopId . "'");
                        $dataSource->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE id = ".$tdsdata[0]['shop_transactions']['id']);
                        //$dataSource->query("UPDATE users_nontxn_logs SET amount=amount-".$shopdata[0]['shop_transactions']['amount'].",tds=tds-$tds WHERE type=".REFUND." AND user_id=$userId AND date='$date' AND service_id=$serviceId");
                        //$dataSource->query("UPDATE users_nontxn_logs SET amount=amount-".$shopdata[0]['shop_transactions']['amount'].",tds=tds-$tds WHERE type=".REFUND." AND parent_user_id=$userId AND date='$date' AND service_id=$serviceId");
                        
                        $description = "Incentive pulled back - $shopId";
                        $this->Shop->shopTransactionUpdate(TXN_REVERSE,$amount,$userId,$shopId,$serviceId,null,REFUND,$description,$bal+$amount,$bal,0,0,$dataSource);
                        
                        $paramdata['AMOUNT'] = $amount;
                        $paramdata['BALANCE'] = $bal;
                        $MsgTemplate = $this->General->LoadApiBalance();
                        $content = $MsgTemplate['Pullback_Refund_MSG'];
                        $sms = $this->General->ReplaceMultiWord($paramdata, $content);
                        
                        $this->General->sendMessage($getUserdata[0]['users']['mobile'], $sms, 'notify', $bal, $groupId);
                        echo "success";
                        $dataSource->commit($dataS);
                    }
                    else{
                        $dataSource->rollback($dataS);
                        echo "faliure";
                    }
                }
            }
            catch(Exception $e){
                $dataSource->rollback($dataS);
            }
        }
        $this->autoRender = false;
    }

	function incentivePullback($date = null) {


		if ($date == null) {
			$date = date('dmY' . '-' . date('dmY'));
		}

		$dates = explode("-", $date);
		$date_from = $dates[0];
		$date_to = $dates[1];

		if (checkdate(substr($date_from, 2, 2), substr($date_from, 0, 2), substr($date_from, 4)) && checkdate(substr($date_to, 2, 2), substr($date_to, 0, 2), substr($date_to, 4))) {
			$date_from = substr($date_from, 4) . "-" . substr($date_from, 2, 2) . "-" . substr($date_from, 0, 2);
			$date_to = substr($date_to, 4) . "-" . substr($date_to, 2, 2) . "-" . substr($date_to, 0, 2);
		}
		//$query = "Select refunds.*, if(shop_transactions.target_id=".RETAILER.",retailers.shopname,distributors.company) company,retailers.mobile,shop_transactions.date from refunds  inner join shop_transactions on (refunds.shoptrans_id = shop_transactions.id) left join distributors ON (shop_transactions.source_id = distributors.user_id) left join retailers ON (shop_transactions.source_id =retailers.user_id) WHERE refunds.date >= '" . $date_from . "' AND refunds.date<='" . $date_to . "' order by refunds.timestamp desc";
		
		$query = "Select shop_transactions.id,shop_transactions.note,shop_transactions.amount,shop_transactions.target_id,shop_transactions.source_id,users.mobile,shop_transactions.date,shop_transactions.confirm_flag,shop_transactions.timestamp from shop_transactions join users on (users.id = shop_transactions.source_id) WHERE shop_transactions.type = ".REFUND." AND shop_transactions.date >= '" . $date_from . "' AND shop_transactions.date<='" . $date_to . "' order by shop_transactions.timestamp desc";
		
		$transdata = $this->Slaves->query($query);
		
		/** IMP DATA ADDED : START**/
		$user_ids = array_map(function($element){
		    return $element['shop_transactions']['user_id'];
		},$transdata);
		$imp_data = $this->Shop->getUserLabelData($user_ids,2,0);
		
		foreach ($transdata as $key=>$tdata){
		    $user_id = $tdata['shop_transactions']['source_id'];
		    $transdata[$key]['0']['company'] = $imp_data[$user_id]['imp']['shop_est_name'];
		}
		
		$this->set('date_from', $date_from);
		$this->set('date_to', $date_to);
		$this->set('transaction', $transdata);
	}

	function graphMainReport($id = null,$date=null,$range = null){


		$show = false;
		if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
			$distid = $this->info['id'];
			$show = true;
			$this->set('name',$this->info['company']);
		}
		else if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){
			$sdistid = $this->info['id'];
			if(!empty($id)){
				$shop = $this->Shop->getShopDataById($id,DISTRIBUTOR);
				if($shop['parent_id'] == $this->info['id'] || $shop['parent_id'] == $this->info['master_dist_id']){
					$show = true;
					$this->set('name',$shop['company']);
					$distid = $id;
					$this->set('dist',$distid);
				}
			}
			else {
				$this->set('name','All Distributors');
				$show = true;
			}
		}
		else if($_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER){
			$rmid = $this->info['id'];
			$sdistid = $this->info['master_dist_id'];
			if(!empty($id)){
				$shop = $this->Shop->getShopDataById($id,DISTRIBUTOR);
				if($shop['rm_id'] == $this->info['id'] && $shop['parent_id'] == $this->info['master_dist_id']){
					$show = true;
					$this->set('name',$shop['company']);
					$distid = $id;
					$this->set('dist',$distid);
				}
			}
			else {
                $this->set('name','All Distributors');
				$show = true;
			}
		}
		else if($_SESSION['Auth']['User']['group_id'] == ADMIN){
			if(!empty($id)){
				$shop = $this->Shop->getShopDataById($id,MASTER_DISTRIBUTOR);
				if(!empty($shop)){
					$sdistid = $id;
					$this->set('name',$shop['company']);
					$show = true;
					$this->set('dist',$sdistid);
				}
			}
			else {
				$this->set('name','All MasterDistributors');
				$show = true;
			}
		}
			if($show){
			if ($date == null) {
				$date = date('dmY' . '-' . date('dmY'));
			}
			$dates = explode("-", $date);
			$date_from = $dates[0];
			$date_to = $dates[1];
			if (checkdate(substr($date_from, 2, 2), substr($date_from, 0, 2), substr($date_from, 4)) && checkdate(substr($date_to, 2, 2), substr($date_to, 0, 2), substr($date_to, 4))) {
				$date_from = substr($date_from, 4) . "-" . substr($date_from, 2, 2) . "-" . substr($date_from, 0, 2);
				$date_to = substr($date_to, 4) . "-" . substr($date_to, 2, 2) . "-" . substr($date_to, 0, 2);
			}


			$datas = array();

			if(!isset($sdistid) && !isset($distid)){//Admin with all SD's
				if ($range == null) {
					$groupbydate = 'group by date';
					//$groupbyretailerdate = 'group by retailers_logs.date';
					$data_bef = $this->Slaves->query("SELECT sum(topup_buy) as topup_buy,sum(topup_sold) as topup_sold,sum(topup_unique) as topup_unique,sum(retailers) as retailers,sum(transacting) as transacting,date FROM users_logs as distributors_logs FORCE INDEX (date) join distributors ON (distributors.user_id = distributors_logs.user_id) WHERE date >= '".$date_from."' AND date<='".$date_to."' $groupbydate order by date");
//				    $data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs FORCE INDEX (idx_date) WHERE retailers_logs.date >= '".$date_from."' AND date<='".$date_to."'  $groupbyretailerdate order by retailers_logs.date");
				    $data_bef_ret = $this->Slaves->query("SELECT SUM(amount) as sale,COUNT(DISTINCT ret_user_id) AS transacting,date "
                                            . "FROM retailer_earning_logs "
                                            . "WHERE date >= '".$date_from."' "
                                            . "AND date <= '".$date_to."' "
                                            . "AND service_id IN (1,2,4,5,6,7) "
                                            . "GROUP BY date "
                                            . "ORDER BY date");
				}else {
					$groupbydate = 'group by week(date)';
					//$groupbyretailerdate = 'group by week(retailers_logs.date)';
					$data_bef = $this->Slaves->query("SELECT sum(topup_buy/7) as topup_buy,sum(topup_sold/7) as topup_sold,sum(topup_unique/7) as topup_unique,sum(retailers/7) as retailers,sum(transacting/7) as transacting,date FROM users_logs as distributors_logs join distributors ON (distributors.user_id = distributors_logs.user_id) WHERE date >= '".$date_from."' AND date<='".$date_to."' $groupbydate order by date");
//				    $data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs FORCE INDEX (idx_date) WHERE retailers_logs.date >= '".$date_from."' AND date<='".$date_to."'  $groupbyretailerdate order by retailers_logs.date");
				    $data_bef_ret = $this->Slaves->query("SELECT SUM(amount) as sale,COUNT(DISTINCT ret_user_id) AS transacting,date "
                                            . "FROM retailer_earning_logs "
                                            . "WHERE date >= '".$date_from."' "
                                            . "AND date <= '".$date_to."' "
                                            . "AND service_id IN (1,2,4,5,6,7) "
                                            . "GROUP BY WEEK(date) "
                                            . "ORDER BY date");

				}

			}
               else if (isset($sdistid) && !isset($distid)) {//SD with all distributors or RM with all distributors or Admin with a SD
				if (isset($rmid))
					$extra = " AND distributors.rm_id = $rmid AND distributors.active_flag = '1'";
				else
					$extra = " AND distributors.parent_id = $sdistid AND distributors.active_flag = '1'";

				if ($range == null) {
					$groupbydate = 'group by distributors_logs.date';
					//$groupbyretailerdate = 'group by retailers_logs.date';
					$data_bef = $this->Slaves->query("SELECT sum(distributors_logs.topup_buy) as topup_buy,sum(distributors_logs.topup_sold) as topup_sold,sum(distributors_logs.topup_unique) as topup_unique,sum(distributors_logs.retailers) as retailers,sum(distributors_logs.transacting) as transacting,distributors_logs.date FROM users_logs as distributors_logs,distributors WHERE distributors_logs.user_id  = distributors.user_id $extra AND distributors_logs.date >= '" . $date_from . "' and distributors_logs.date<='" . $date_to . "'  $groupbydate order by distributors_logs.date");
//					$data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale/7) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs FORCE INDEX (idx_date),retailers,distributors WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id=distributors.id $extra AND retailers_logs.date >= '" . $date_from . "' AND retailers_logs.date<='" . $date_to . "' $groupbyretailerdate order by retailers_logs.date");
					$data_bef_ret = $this->Slaves->query("SELECT SUM(amount/7) as sale,COUNT(DISTINCT ret_user_id) AS transacting,retailer_earning_logs.date "
                                                . "FROM retailer_earning_logs "
                                                . "JOIN retailers ON (retailer_earning_logs.ret_user_id = retailers.user_id) "
                                                . "JOIN distributors ON (retailer_earning_logs.dist_user_id = distributors.user_id) "
                                                . "WHERE 1 $extra "
                                                . "AND retailer_earning_logs.date >= '" . $date_from . "' "
                                                . "AND retailer_earning_logs.date <= '" . $date_to . "' "
                                                . "AND retailer_earning_logs.service_id IN (1,2,4,5,6,7) "
                                                . "GROUP BY retailer_earning_logs.date "
                                                . "ORDER BY retailer_earning_logs.date");
				} else {
					$groupbydate = 'group by week(distributors_logs.date)';
					//$groupbyretailerdate = 'group by week(retailers_logs.date)';
					$data_bef = $this->Slaves->query("SELECT sum(distributors_logs.topup_buy/7) as topup_buy,sum(distributors_logs.topup_sold/7) as topup_sold,sum(distributors_logs.topup_unique/7) as topup_unique,sum(distributors_logs.retailers/7) as retailers,sum(distributors_logs.transacting/7) as transacting,distributors_logs.date FROM users_logs as distributors_logs,distributors WHERE distributors_logs.user_id  = distributors.user_id $extra AND distributors_logs.date >= '" . $date_from . "' and distributors_logs.date<='" . $date_to . "'  $groupbydate order by distributors_logs.date");
//					$data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale/7) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs FORCE INDEX (idx_date),retailers,distributors WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id=distributors.id $extra AND retailers_logs.date >= '" . $date_from . "' AND retailers_logs.date<='" . $date_to . "' $groupbyretailerdate order by retailers_logs.date");
					$data_bef_ret = $this->Slaves->query("SELECT SUM(amount/7) as sale,COUNT(DISTINCT ret_user_id) AS transacting,retailer_earning_logs.date "
                                                . "FROM retailer_earning_logs "
                                                . "JOIN retailers ON (retailer_earning_logs.ret_user_id = retailers.user_id) "
                                                . "JOIN distributors ON (retailer_earning_logs.dist_user_id = distributors.user_id) "
                                                . "WHERE 1 $extra "
                                                . "AND retailer_earning_logs.date >= '" . $date_from . "' "
                                                . "AND retailer_earning_logs.date <= '" . $date_to . "' "
                                                . "AND retailer_earning_logs.service_id IN (1,2,4,5,6,7) "
                                                . "GROUP BY WEEK(retailer_earning_logs.date) "
                                                . "ORDER BY retailer_earning_logs.date");
				}
			} else if(isset($sdistid) && isset($distid)){//SD with a distributor OR RM with a distributor
				$extra = "";
				if(isset($rmid))$extra = " AND distributors.rm_id = $rmid";
				if ($range == null) {
					$groupbydate = 'group by distributors_logs.date';
					//$groupbyretailerdate = 'retailers_logs.date';
					$data_bef = $this->Slaves->query("SELECT sum(distributors_logs.topup_buy) as topup_buy,sum(distributors_logs.topup_sold) as topup_sold,sum(distributors_logs.topup_unique) as topup_unique,sum(distributors_logs.retailers) as retailers,sum(distributors_logs.transacting) as transacting,distributors_logs.date FROM users_logs as distributors_logs INNER JOIN distributors ON (distributors_logs.user_id  = distributors.user_id) WHERE distributors.id = $distid AND distributors_logs.date >= '".$date_from."' AND distributors_logs.date<='".$date_to."' $groupbydate order by distributors_logs.date");
//				    $data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale/7) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs FORCE INDEX (idx_date),retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id= $distid AND retailers_logs.date >= '".$date_from."' AND retailers_logs.date<='".$date_to."' $groupbyretailerdate order by retailers_logs.date");
				    $data_bef_ret = $this->Slaves->query("SELECT SUM(amount/7) as sale,COUNT(DISTINCT ret_user_id) AS transacting,retailer_earning_logs.date "
                                            . "FROM retailer_earning_logs "
                                            . "JOIN retailers ON (retailer_earning_logs.ret_user_id = retailers.user_id) "
                                            . "JOIN distributors ON (retailer_earning_logs.dist_user_id = distributors.user_id) "
                                            . "WHERE distributors.id = $distid "
                                            . "AND retailer_earning_logs.date >= '".$date_from."' "
                                            . "AND retailer_earning_logs.date <= '".$date_to."'"
                                            . "AND retailer_earning_logs.service_id IN (1,2,4,5,6,7) "
                                            . "GROUP BY retailer_earning_logs.date "
                                            . "ORDER BY retailer_earning_logs.date");

				}else {
					$groupbydate = 'group by week(distributors_logs.date)';
					//$groupbyretailerdate = 'group by week(retailers_logs.date)';
					$data_bef = $this->Slaves->query("SELECT sum(distributors_logs.topup_buy/7) as topup_buy,sum(distributors_logs.topup_sold/7) as topup_sold,sum(distributors_logs.topup_unique/7) as topup_unique,sum(distributors_logs.retailers/7) as retailers,sum(distributors_logs.transacting/7) as transacting,distributors_logs.date FROM users_logs as distributors_logs INNER JOIN distributors ON (distributors_logs.user_id  = distributors.user_id) WHERE distributors.id = $distid AND distributors_logs.date >= '".$date_from."' AND distributors_logs.date<='".$date_to."' $groupbydate order by distributors_logs.date");
//				    $data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale/7) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs FORCE INDEX (idx_date),retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id= $distid AND retailers_logs.date >= '".$date_from."' AND retailers_logs.date<='".$date_to."' $groupbyretailerdate order by retailers_logs.date");
				    $data_bef_ret = $this->Slaves->query("SELECT SUM(amount/7) as sale,COUNT(DISTINCT ret_user_id) AS transacting,retailer_earning_logs.date "
                                            . "FROM retailer_earning_logs "
                                            . "JOIN retailers ON (retailer_earning_logs.ret_user_id = retailers.user_id) "
                                            . "JOIN distributors ON (retailer_earning_logs.dist_user_id = distributors.user_id) "
                                            . "WHERE distributors.id = $distid "
                                            . "AND retailer_earning_logs.date >= '".$date_from."' "
                                            . "AND retailer_earning_logs.date <= '".$date_to."' "
                                            . "AND retailer_earning_logs.service_id IN (1,2,4,5,6,7) "
                                            . "GROUP BY WEEK(retailer_earning_logs.date) "
                                            . "ORDER BY retailer_earning_logs.date");
				}

					}
			else if(!isset($sdistid) && isset($distid)){//Distributor

				if ($range == null) {
					$groupbydate = 'group by distributors_logs.date';
					//$groupbyretailerdate = 'group by retailers_logs.date';
					$data_bef = $this->Slaves->query("SELECT sum(distributors_logs.topup_buy) as topup_buy,sum(distributors_logs.topup_sold) as topup_sold,sum(distributors_logs.topup_unique) as topup_unique,sum(distributors_logs.retailers) as retailers,sum(distributors_logs.transacting) as transacting,distributors_logs.date FROM users_logs as distributors_logs INNER JOIN distributors ON (distributors_logs.user_id  = distributors.user_id) WHERE distributors.id = $distid AND distributors_logs.date >= '".$date_from."' AND distributors_logs.date<='".$date_to."' $groupbydate order by distributors_logs.date");
//				    $data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale/7) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs FORCE INDEX (idx_date),retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id= $distid AND retailers_logs.date >= '".$date_from."' AND retailers_logs.date<='".$date_to."' $groupbyretailerdate order by retailers_logs.date");
				    $data_bef_ret = $this->Slaves->query("SELECT SUM(amount/7) as sale,COUNT(DISTINCT ret_user_id) AS transacting,retailer_earning_logs.date "
                                            . "FROM retailer_earning_logs "
                                            . "JOIN retailers ON (retailer_earning_logs.ret_user_id = retailers.user_id) "
                                            . "JOIN distributors ON (retailer_earning_logs.dist_user_id = distributors.user_id) "
                                            . "WHERE distributors.id = $distid "
                                            . "AND retailer_earning_logs.date >= '".$date_from."' "
                                            . "AND retailer_earning_logs.date <= '".$date_to."' "
                                            . "AND retailer_earning_logs.service_id IN (1,2,4,5,6,7) "
                                            . "GROUP BY retailer_earning_logs.date "
                                            . "ORDER BY retailer_earning_logs.date");
				}else {
					$groupbydate = 'group by week(distributors_logs.date)';
					//$groupbyretailerdate = 'group by week(retailers_logs.date)';
					$data_bef = $this->Slaves->query("SELECT sum(distributors_logs.topup_buy/7) as topup_buy,sum(distributors_logs.topup_sold/7) as topup_sold,sum(distributors_logs.topup_unique/7) as topup_unique,sum(distributors_logs.retailers/7) as retailers,sum(distributors_logs.transacting/7) as transacting,distributors_logs.date FROM users_logs as distributors_logs INNER JOIN distributors ON (distributors_logs.user_id  = distributors.user_id) WHERE distributors.id = $distid AND distributors_logs.date >= '".$date_from."' AND distributors_logs.date<='".$date_to."' $groupbydate order by distributors_logs.date");
//				    $data_bef_ret = $this->Slaves->query("SELECT sum(retailers_logs.sale/7) as sale,count(retailers_logs.id) as transacting,retailers_logs.date FROM retailers_logs FORCE INDEX (idx_date),retailers WHERE retailers.id = retailers_logs.retailer_id AND retailers.parent_id= $distid AND retailers_logs.date >= '".$date_from."' AND retailers_logs.date<='".$date_to."' $groupbyretailerdate order by retailers_logs.date");
				    $data_bef_ret = $this->Slaves->query("SELECT SUM(amount/7) as sale,COUNT(DISTINCT ret_user_id) AS transacting,retailer_earning_logs.date "
                                            . "FROM retailer_earning_logs "
                                            . "JOIN retailers ON (retailer_earning_logs.ret_user_id = retailers.user_id) "
                                            . "JOIN distributors ON (retailer_earning_logs.dist_user_id = distributors.user_id) "
                                            . "WHERE distributors.id = $distid "
                                            . "AND retailer_earning_logs.date >= '".$date_from."' "
                                            . "AND retailer_earning_logs.date <= '".$date_to."' "
                                            . "AND retailer_earning_logs.service_id IN (1,2,4,5,6,7) "
                                            . "GROUP BY WEEK(retailer_earning_logs.date) "
                                            . "ORDER BY retailer_earning_logs.date");
				}

			}


            $datas_before = array();
			$ret_before=0;
			$ret_tot = 0;
			$datas_before['week']['new'] = 0;
			$datas_before['month']['new'] = 0;

			$datatopupsold = array();
			$datatransRetailer = array();
			$datanewOutlets = array();

			foreach($data_bef_ret as $dt){
				$tertiarysale[$dt['retailer_earning_logs']['date']] = array("sale" => $dt[0]['sale'],"transacting"=>$dt[0]['transacting']);
				if($range != null){
				$retailerAvgSale[] = array($dt['retailer_earning_logs']['date'],intval(($dt[0]['sale']*7)/$dt[0]['transacting']));
				} else {
					$retailerAvgSale[] = array($dt['retailer_earning_logs']['date'],intval($dt[0]['sale']/$dt[0]['transacting']));
				}
			}

			foreach ($data_bef as $dt) {
				$datatopupsold[] = array($dt['distributors_logs']['date'], intval($dt[0]['topup_sold']),intval($dt[0]['topup_buy']), intval($tertiarysale[$dt['distributors_logs']['date']]['sale']));
				$datatransRetailer[] = array($dt['distributors_logs']['date'], intval($dt['0']['transacting']));
				if ($dt['distributors_logs']['date'] != $date_from) {
					$datanewOutlets[] = array($dt['distributors_logs']['date'], intval($dt['0']['retailers'] - $ret_before));
				}

				$dataUniqueTopups[] = array($dt['distributors_logs']['date'], intval($dt['0']['topup_unique']));
				$ret_before = $dt['0']['retailers'];
			}


		$retialerSale = array();
			foreach($data_bef_ret as $dt){
				$retailerAvgSale[] = array($dt['retailer_earning_logs']['date'],intval($dt[0]['sale']/$dt[0]['transacting']));
			}
             $graphData = array(
					'labels' => array(
							array('string' => 'Sample'),
							array('number' => 'Topup sold today'),
							array('number' => 'Topup buy today'),
							array('number' => 'Tertiary Sale')
					),
					'data' => $datatopupsold,
					'title' => 'Topup sold/day',
					'type' => 'line',
					'width' => '900',
					'height' => '400'
			);
			$graphData1 = array(
					'labels' => array(
							array('string' => 'Sample'),
							array('number' => 'transacting Retailers')
					),
					'data' => $datatransRetailer,
					'title' => 'transacting Retailers',
					'type' => 'line',
					'width' => '900',
					'height' => '400'
			);
			$graphData2 = array(
					'labels' => array(
							array('string' => 'Sample'),
							array('number' => 'New outlets opened')
					),
					'data' => $datanewOutlets,
					'title' => 'New outlets opened',
					'type' => 'line',
					'width' => '900',
					'height' => '400'
			);
			$graphData3 = array(
					'labels' => array(
							array('string' => 'Sample'),
							array('number' => 'Unique topups/day')
					),
					'data' => $dataUniqueTopups,
					'title' => 'Unique topups/day',
					'type' => 'line',
					'width' => '900',
					'height' => '400'
			);


			$graphData5 = array(
					'labels' => array(
							array('string' => 'Sample'),
							array('number' => 'Avg Sale/Retailer')
					),
					'data' => $retailerAvgSale,
					'title' => 'Avg Sale/Retailer',
					'type' => 'line',
					'width' => '900',
					'height' => '400'
			);


			$this->set('data1',$graphData);
			$this->set('data2',$graphData1);
			$this->set('data3',$graphData2);
			$this->set('data4',$graphData3);
			//$this->set('data5',$graphData4);
			$this->set('data6',$graphData5);
		}
		$this->set('date_from', $date_from);
		$this->set('date_to', $date_to);

		if(!empty($id))$this->set('id',$id);

		if(!empty($range)) $this->set('type',$range);
		//$this->autoRender = false;

	}

function distributorsMonthReport($month=null,$service=null){

		if($month ==null){
			$month = date('m');
		}

		if($service!=null){
			$service_condition = " AND rel.service_id IN (".$service.")";
		}


		$d= cal_days_in_month(CAL_GREGORIAN,$month,date('Y'));
		$fromdate = date('Y-m-d',strtotime(date("Y-$month-01")));
		$todate = date("Y-m-d", strtotime("+$d day", strtotime($fromdate)));

		$distRecords = array();
		$datearray = array();
		$distId = array();

//		$distSale = $this->Slaves->query("SELECT SUM(sale) as retsale,retailers_logs.date,retailers.parent_id,distributors.company,distributors.city,distributors.state,distributors.id,date(distributors.created) as created_date,distributors.margin,distributors.mobile "
//				                           ."FROM "
//				                            ."retailers_logs  USE index(idx_date) "
//				                            ."LEFT JOIN retailers on (retailers_logs.retailer_id = retailers.id) "
//											."LEFT JOIN distributors on (distributors.id = retailers.parent_id) "
//				                            ."WHERE retailers_logs.date>= '".$fromdate."' AND retailers_logs.date<='".$todate."' "
//				                            ."AND distributors.parent_id = '".$this->info['id']."'"
//				                            ."GROUP BY retailers.parent_id,retailers_logs.date");



		$distSale = $this->Slaves->query("SELECT SUM(rel.amount) AS retsale, COUNT(DISTINCT rel.ret_user_id) AS no_of_transacting_retailer,rel.date,rel.dist_user_id as parent_id, distributors.company,distributors.city,distributors.state,distributors.id,DATE(distributors.created) AS created_date,distributors.margin,distributors.mobile "
                                                . "FROM retailer_earning_logs rel "
                                                . "JOIN retailers ON (rel.ret_user_id = retailers.user_id) "
                                                . "JOIN distributors ON (rel.dist_user_id = distributors.user_id) "
                                                . "WHERE rel.date >= '".$fromdate."' "
                                                . "AND rel.date <= '".$todate."' "
                                                . "AND distributors.parent_id = '".$this->info['id']."' "
                                                . $service_condition 
                                                . "GROUP BY distributors.id,rel.date");

            /** IMP DATA ADDED : START**/
            $dist_ids = array_map(function($element){
                return $element['distributors']['id'];
            },$distSale);

            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);

            $dist_imp_label_map = array(
                'pan_number' => 'pan_no',
                'company' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id'
            );
            foreach ($distSale as $key => $distributor) {
                foreach ($distributor['distributors'] as $dist_label_key => $value) {
                    $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
                    if( array_key_exists($dist_label_key_mapped,$imp_data[$distributor['distributors']['id']]['imp']) ){
                        $distSale[$key]['distributors'][$dist_label_key] = $imp_data[$distributor['distributors']['id']]['imp'][$dist_label_key_mapped];
                    }
                }
                $distSale[$key]['distributors']['no_of_transacting_retailer'] = $distributor[0]['no_of_transacting_retailer'];
            }
            /** IMP DATA ADDED : END**/


		foreach ($distSale as $val){
			$datearray[$val['rel']['date']] = $val['rel']['date'];
			$dataRecords[$val['rel']['parent_id']][$val['rel']['date']] = array("sale" => $val[0]['retsale']);
			$distId[$val['rel']['parent_id']] = $val;
		}

		foreach ($datearray as $dateval){
			foreach($distId as $diskey => $disval){
				if(!isset($distRecords[$diskey][$dateval])){
					$distRecords[$diskey][$dateval] = array();
				}
				$distRecords[$diskey][$dateval] = isset($dataRecords[$diskey][$dateval]) ? $dataRecords[$diskey][$dateval] : array("sale"=>"");
			}
		}

		$services = $this->Shop->getAllServices();

			$fromdate = date("Y-$month-01");
			$this->set('fromdate',$fromdate);
			$this->set('services',$services);
			$this->set('todate',$todate);
			$this->set('distRecords',$distRecords);
			$this->set('serviceval',$service);
			$this->set('monthval',$month);
			$this->set('distId',$distId);
		    $pageType = empty($_GET['res_type']) ? "" : $_GET['res_type'];
			$this->set('pageType',$pageType);
            if($pageType == 'csv'){
			App::import('Helper','csv');
			$this->layout = null;
			$this->autoLayout = false;
			$csv = new CsvHelper();
			$line = array();
			$line[0] = "Distributors Name";
			$line[1] = "City";
			$line[2] = "State";
			$line[3] = "Id";
			$line[4] = "Reg Date";
			$line[5] = "Margin Slab";
			//$line[6] = "Mobile No";
			$i=7;
			foreach ($datearray as $dateval){
				$line[$i] = $dateval;
				$i++;
			}
			$csv->addRow($line);

			foreach($distRecords as $key => $val){
				$temp[0] = $distId[$key]['distributors']['company'];
				$temp[1] = $distId[$key]['distributors']['city'];
				$temp[2] = $distId[$key]['distributors']['state'];
				$temp[3] = $distId[$key]['distributors']['id'];
				$temp[4] = $distId[$key][0]['created_date'];
				$temp[5] = $distId[$key]['distributors']['margin'];
				//$temp[6] = $distId[$key]['users']['mobile'];
				$i=7;
				foreach ($val as $k => $v){
					$temp[$i] = isset($v['sale']) ? $v['sale'] : "";
					$i++;

				}
				$csv->addRow($temp);
			}
			 echo $csv->render('distributors_month_report'.date('YmdHis').'.csv');
                } else {
                        $this->render('distributors_month_report');
                }

        }

        function bankDetails()
        {
            $bankdetails = $this->Shop->getMemcache("bankinfo");

            if(empty($bankdetails))
            {

                $bankdetails = $this->Slaves->query("select * from bank_details where visible_to_retailer_flag = 1");

                $this->Shop->setMemcache("bankinfo",$bankdetails,1*24*60*60);

            }

            $this->set('bankdetails',$bankdetails);
            $this->render('bank_detail');
        }

        /*function distIncentive(){
            $this->render('dist_incentive');
        }*/

        function distTermsCondition()
        {

            /*$d_id = $this->Session->read('Auth.id');
             $dist_info = $this->Slaves->query("Select name,company,sd_amt,created from distributors where id = ' $d_id' ");
             echo "Select name,company,sd_amt,created from distributors where id = '$d_id' ";
            echo '<pre>';
            print_r($dist_info);
            echo '</pre>';
            $this->set('dist_info',$dist_info);*/
             $this->render('dist_terms_condition');
        }

        function distAgreement()
        {
        $this->render('dist_agreement');
        }

        function distProposition()
        {
            $this->render('dist_proposition');
        }

        function distributorsHelpDesk()
        {
            $this->render('distributor_helpdesk');
        }

        function distAgreementPdf(){
                /*    $d_id = $this->Session->read('Auth.id');
                    $dist_info = $this->Slaves->query("Select name,company,sd_amt,created from distributors where id = ' $d_id' ");*/
		    require_once APP . vendors . DS . tcpdf . DS . 'tcpdf.php';
		    $pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		    $pdf->AddPage();
		    $pdf->setPrintHeader(false);
		    $pdf->setFormDefaultProp(array('lineWidth' => 1, 'borderStyle' => 'solid', 'fillColor' => array(255, 255, 200), 'strokeColor' => array(255, 128, 128)));
		    $pdf->SetFont('helvetica', 'B', 16);
		    $pdf->Cell(190, 4, 'Online Distributor Service Agreement', array('TB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))), 0, 'C');
		    $pdf->SetFont('helvetica', '', 10);

		    $det = '<div id="pageContent" style="min-height:500px;position:relative;">
			<div class="loginCont">
				<div id="innerDiv" class="leftFloat">
		    <style>
		        .tnc{margin-top: 10px;width: 80%;margin:5 auto;}
		       .tnc span{display: block;padding: 10px 0;}
		       .tnc ul{margin: 15px 0;}
		       .tnc li{margin-left: 40px;padding: 5px 0;}
		    </style>
		    <div class="tnc">
		    <p>
		    <span><h2></h2></span>
		    <span>THIS AGREEMENT executed as specified herein by the person(s)/Company as described in the Appointment Letter(hereinafter collectively and severally referred to as "The Executants”)

		      <b> MindsArray Technologies Pvt Ltd (herein after referred to as Pay1),</b>  a platform for use of prepaid mobile/DTH recharge purchasing services and or to purchase prepaid mobile/DTH recharge popularly known as Pay1,   having its registered office at 726, Raheja s Metroplex (IJIMIMA), Link Road, Malad West, Mumbai – 400064.</span>
		    <span> <b><center> AND </center></b> </span>

		    <span> ­Person/ Sole Proprietorship Firm/ Partnership Firm/ Private Limited/ Limited Liability Partnership as mentioned in the appointment letter issued by Pay1 (hereinafter referred as <b> “Distributor” or The Company </b>)    </span>

		    <span> WHEREAS </span>

		    <span> Pay1 is involved in the business of providing a platform services to its network of Distributors and Retailers for following services:<br/> </span>
		    <ul>
		        <li> a) Mobile and DTH Recharges,</li>
		        <li> b) Domestic Money Transfer (DMT),</li>
		        <li> c) MPOS: Complete payment collection solution comprising of card acceptance solutions and cash@POS,</li>
		        <li> d) Capital: Micro finance loan to its network of retailers and distributors on its platform through NBFC or Micro-finance lenders or similar organization</li>
		    </ul>
		    Pay1 provides these services through its network of distributors and Retailers.
		    Distributor & Retailers shall have to maintain a closed loop wallet account with Pay1 for availing the services offered.

		    Pay1 is desirous to expand the business within the territorial jurisdiction of India & in order to same wish to open the outlets in various areas to control/manage the operations of its official business through Distributors which will be located in that area and to collect the payments from its retailers located in that area.

		    In reference to the appointment letter issued by Pay1, this is to formally appoint <b> the Company</b> as a Distributor of Pay1 in geographical region as allotted by Pay1 from time to time. We would like to thank you for choosing to work with us and look forward to a fruitful relationship with you. Please find listed the key terms of this engagement

		    <ul>
		        1. DEFINITIONS
		        <li>1.1.  In this Letter of Appointment the below terms and phrases shall have the following meaning:</li>
		        <li>1.1.1.  “Applicable Law(s)” means any statute, law, regulation, ordinance, rule, judgment, notification, rule of common law, order, decree, bye-law, government approval, directive, guideline, requirement or other governmental restriction, or any similar form of decision of, or determination by, or any interpretation or policy by, any authority having jurisdiction over the matter in question, whether in effect as of the date of this Appointment or thereafter.</li>
		    <li>1.1.2. “Claims” mean any losses, liabilities, claims, damages, costs and expenses, including legal fees and disbursements in relation thereto; </li>
		    <li>1.1.3. “Confidential Information” shall have the meaning as prescribed to it in clause 18 hereof.</li>
		    <li>1.1.4. “Effective Date” means the date of acceptance of this Appointment by Distributor *</li>
		    <li>1.1.5. “IP Rights” or “Intellectual Property Rights” shall mean all rights in and in relation to all intellectual property rights subsisting in the Pay1 business model, the Products and Services, the marketing materials and any other intellectual property rights of Pay1 including all patents, patent applications, trademarks, trade names, service marks, service names, brand names, internet domain names and sub- domains, IT Applications, inventions, processes, formulae, copyrights, business and product names, logos, slogans, trade secrets, industrial models, processes, designs, methodologies, computer programs (including all source codes), technical information, manufacturing, engineering and technical drawings, know-how and all pending applications for and registrations of patents, entity models, trademarks, service marks, copyrights and internet domain names and sub-domains.</li>
		    <li>1.1.6. “IT Application” means the software as prescribed by Pay1 and supplied to Distributor in relation to for the operation of the Services and provision of Products. Pay1 may from time to time specify and provide upgrades and new Products to Distributor which shall form part of the IT Application.</li>
		    <li>1.1.7. “Person(s)” means any individual, sole proprietorship, unincorporated association, unincorporated organization, body corporate, corporation, company, partnership, limited liability company, joint venture, government authority or trust or any other entity or organization.</li>
		    <li>1.1.8. “Products and Services” means the Recharges, DMT and MPOS etc as agreed of this Appointment and any additions/deletions to the same mutually carried out by the Parties.</li>
		    <li>1.1.9. “Right of Operation” means the rights obtained by Distributor based on this Appointment, and as detailed in clause 4 of this Appointment.</li>
		    <li>1.1.10. “Territory” means geographical area covering region as allotted by Pay1 to distributor on distributors from time to time.</li>
		    </ul>
		    <ul>
		        2. APPOINTMENT
		    <li>2.1. Pay1 hereby appoints the Distributor as its non-exclusive Distributors upon the terms and conditions as set forth herein, in the Territory for use of Right of Operation as detailed in clause 4 of this Appointment. The appointment shall be effective from the date of execution of the Appointment. </li>
		    </ul>
		    <ul>
		    3. RELATIONSHIP BETWEEN THE PARTIES
		    <li>3.1. It is specifically agreed that Distributors shall act as an independent Person and shall not be deemed to be an employee/representatives/Affiliates of Pay1. None of the employees/representatives/agents/Affiliates of Distributors shall be entitled to claim permanent absorption or any other claim or benefit against Pay1.</li>
		    </ul>
		    <ul>4. DISTRIBUTOR’S RIGHT OF OPERATION
		    <li>4.1. Within the framework of this Appointment, Pay1 hereby grants Distributor, in terms of the Obligations of Distributor as detailed in clause 5 of this Appointment, a right and a duty (Right of Operation) to;</li>
		    <li>4.1.1. Expand the reach of Pay1 Products and Services etc.</li>
		    <li>4.1.2. Roll out, supervise and manage the affairs on behalf of Pay1 in the Network and ensure that, to provide the Services and Products etc. in the Network, all activities are carried out in accordance with all Applicable Laws.</li>
		    </ul>
		    <ul>
		    5. OBLIGATIONS OF DISTRIBUTOR
		    <li>Distributor understands and agrees it is paramount for Distributors to perform the following obligations as per and only as per processes and turn-around-times specified by Pay1 from time-to-time in writing or over email.</li>
		    <li>5.1. Distribution and Sales:</li>
		    <li>5.1.1.  Network Rollout: Distributor shall set up the network of retailers in both rural and urban areas as per rollout plans agreed mutually from time to time between Pay1 and Distributor. Setting up the network involves performing the following duties:</li>
		    <li>5.1.1.1. Short listing suitable Customer Service Point (Retailers) prospects from Distributor’s existing distribution network and from the market.</li>
		    <li>5.1.1.2. Collecting applications forms, documents, application fees, setup fees and security deposits from the shortlisted prospects.</li>
		    <li>5.1.1.3. Carrying out due diligence of the prospects.</li>
		    <li>5.1.1.4. Providing  applications and documents  to Pay1 in the required format, as provided by Pay1</li>
		    <li>5.1.2. Cash Collection Service: Distributor shall collect cash from the network of Retailers and deposit the same into the Pay1 account directly or thru netbanking from Distributor own account </li>
		    <li>5.1.3. Taking over management of independently appointed by Pay1: Pay1 may independently appoint Retailers in the Territory. Pay1 may choose to hand over management of such Retailers to Distributor and Distributor may choose to take over the management.  If Pay1 and Distributor choose to do so, the taken over by Distributor from Pay1 will form a part of the Network.</li>
		    <li>5.1.4. Other Competitor recharge Aggregators: Subject to clause 16 hereof, if Distributor partners with another aggregator of recharge or becomes an aggregator itself, Distributor shall not appoint from the Pay1 Network for its business with the other competitor recharge aggregators’ or for its own business as an aggregator business. </li>
		    <li>5.1.5. Other BCs: Subject to clause 16 hereof, if Distributor partners with another BC or becomes a BC itself, Distributor shall not appoint from the Pay1 Network for its business with the other BC or for its own business as a BC.</li>
		    <li>5.2. Pre-funding and Liquidity: Distributor shall ensure each Retailer in his Network pre-funds their Pay1 wallet by required amounts (“e-balance”) and have required cash-in-hand (“cash-in-hand”) at every time.</li>
		    <li>5.3. Human Resource Management: Both Parties shall appoint Single Points of Contact (“SPOC”s) for top-level business coordination and for each aspect of the business.</li>
		    <li>5.4. Marketing Operations: Distributor shall be responsible for:</li>
		    <li>5.4.1. Ensuring adherence to Pay1’s  specifications for installation  of marketing material  at locations, Partners’ branch locations and other market locations</li>
		    <li>5.4.2. Execution of local above-the-line (ATL) and below-the-line (BTL) marketing campaigns and/or schemes designed and extended by Pay1 if any</li>
		    <li>5.4.3. Identification of need to design local marketing campaigns and/or schemes</li>
		    <li>5.4.4. It shall be imperative for Distributor to execute the customer-facing schemes (e.g. discounts, advertisement etc.) extended by Pay1 or only with the prior approval of Pay1.</li>
		    <li>5.4.5. Distributor Service Reports: Distributor will provide Pay1 with reports about the services provided by Distributor and Retailers in its Network</li>
		    <li>5.5. Customer Service and Fraud Management:  Distributor shall;</li>
		    <li>5.5.1. Address and resolve customers’ queries, complaints and issues to the point of their satisfaction in the manner as defined by Pay1</li>
		    <li>5.5.2. Facilitate refunds to customers by a in case of a wrong transaction due to a mistake only through Pay1</li>
		    <li>5.5.3. Facilitate refunds to customers only by the Partner or Pay1 in case of a wrong transaction due to technology issues.</li>
		    <li>5.5.4. Identify suspicious activity done by Retailer or Retailer’s staff members who may be committing a fraud, and carry out further necessary investigation to confirm the event of fraud.</li>
		    <li>5.6. Process compliance: Distributor shall ensure that the Network complies with the processes as may be communicated by Pay1 from time to time.</li>
		    </ul>
		    <ul>
		        6. OBLIGATIONS OF Pay1
		    <li>Technology Support: As part of Pay1 Appointment with Partners, Pay1 provides technology platform as a service to the Partners. Pay1 will ensure robust and quality technology support services to the Network.</li>
		    </ul>
		    <ul>
		        7. REVENUE SHARE
		    <li>7.1. For undertaking and fulfilling its responsibilities in terms of this Appointment, Distributor will be entitled to get a percentage of Pay1 revenues generated from the Network as commission subject to deductions wherever applicable as per the Applicable Laws (the “Commission”) as agreed.</li>
		    <li>7.2. Commission will be broken into base commissions (“Base Commissions”) and performance-based incentives (“Incentives”) as agreed.</li>
		    <li>7.3. Pay1 is authorized to change the commission anytime.</li>
		    <li>7.4. Pay1 will specify performance levels required for paying the Incentives from time to time.</li>
		    </ul>
		    <ul>
		        8. PAYMENT TO Distributor AND TO THE NETWORK
		    <li>8.1. Pay1 reserves the right to pay Distributor for its services in the form of e-balance into Distributor’s e-balance account created in the IT Application.</li>
		    <li>8.2. E-balance can be used by Distributor for Pay1 business or for withdrawal of money by applying to Pay1.</li>
		    <li>8.3. It shall be solely the duty of Distributor to make payments to the Network as outlined by Pay1 from time to time.</li>
		    </ul>
		    <ul>
		        9. TERM
		    <li>9.1.  This Appointment shall be valid for a period of 1(One) year from the Effective Date, unless terminated earlier in terms of this Appointment. This Appointment may be renewed automatically every 1 (One) year on the terms and conditions mutually agreed by the Parties. </li>
		    </ul>
		    <ul>
		        10. TERMINATION
		    <li>10.1. This Appointment shall be co-terminus with the Distributor and Retailer Appointments entered into by Pay1. In the event of termination of any Distributor and Retailer Appointment, Pay1 shall have the right to terminate the Appointment immediately.</li>
		    <li>10.2. Pay1 shall have the right to terminate this Appointment immediately on the happening of the following events: In the case the parties want to discontinue the service then they have to serve 30 days’ notice.</li>
		    </ul>
		    <ul>
		        11. INDEMNITY
		    <li>11.1. Notwithstanding anything to the contrary stated in this Appointment, Distributor hereby indemnifies and agree to keep fully indemnified Pay1 and its employees, representatives, Affiliates from and against all actions, suits, judgment, forfeitures, proceedings, claims, demands, losses, obligations, deficiencies, judgments, actions, suits, arbitrations, assessments, costs and expenses, including without limitation, expenses of investigation and enforcement of this indemnity and reasonable attorneys’ fees and disbursements, imposed on, asserted or claimed against or incurred, suffered or paid or other damages which may arise or occur in respect of and for reason of breach and/or non- compliance of any of the terms and conditions of this Appointment or of the representations, warranties, statements.</li>
		    </ul>
		    <ul>12. INTELLECTUAL PROPERTY  RIGHTS
		    <li>12.1. Distributor agrees that it shall not use the logo, trademark, copy rights of other IP Rights of Pay1 and/or it’s Partner(s) in any advertisement or publicity materials or any other written communication with any other party, without the prior written consent of Pay1.</li>
		    <li>12.2. All trademarks, trade names, copyrights, patent, designs technical know-how in relation to the Products and Services including all IT Applications, brochures, signs, advertisements, exhibition equipment, logos, slogans, standard operating procedures, and other sales and marketing materials and any related literature supplied by Pay1 shall remain property of Pay1.</li>
		    </ul>
		    <ul>
		        13. NON SOLICITATION AND NON COMPETITION
		    <li>13.1. Distributor agrees and undertakes that whether on its own account, or for any Person, for the Term of this Appointment and twelve months thereafter,  it shall not solicit or entice, or endeavor to solicit or entice, from the Network any retailer/distributor/Master Distributor/ officers or employees of or any retailer/distributor/Master Distributor with whom Distributor have had dealings during his tenure of this Appointment, whether or not that person would commit a breach of any contract by reason of ceasing to service or provide services within the Network.</li>
		    <li>13.2. Distributor agrees that some restrictions on its activities during and after the tenure of this Appointment are necessary to protect the goodwill and other legitimate interests of Pay1 and/or the Partner(s).  Distributor agrees not to undertake or provide any Services or Products for any outside business competitive with Pay1 within the Territory. During the tenure of this Appointment and for a period of one year thereafter Distributor (the “Restriction Period”), Distributor undertakes not to compete, directly or indirectly, with Pay1 in the Territory, whether as a BC or an agent/employee/partner of any other BC or recharge aggregator. Specifically, but without limiting the foregoing, Distributor agrees not to engage in any manner in any activity that is directly or indirectly competitive or potentially competitive with the business of Pay1. For purposes of this provision, the business of Pay1 shall include all Products and Services offered by Pay1 in any manner.</li>
		    </ul>
		    <ul>
		        14. BRAND REPUTATION
		    <li>14.1. Distributor acknowledges that the protection and development of the Pay1 brand and reputation is of key importance to Distributor and Pay1. Therefore Distributor agrees to (1) promptly inform Pay1 about any written complaints received about Retailers business activities from any interested party whatsoever, (2) provide, without request, Pay1 with copies of the written complaints, articles, legal papers, etc. and (3) keep Pay1 informed about the progress of any such complaints.</li>
		    <li>14.2. Distributor will represent brands of Retailers and Pay1. Therefore, Distributor will take ownership for its actions related to Retailers or and Pay1’s brands. In case of any defamation or brand-loss caused by negligence or improper action of Retailers or Retailer staff or Pay1 Network, Pay1 may, without prejudice to other legal remedies, choose to unilaterally terminate the relationship with Distributor and charge a penalty to Distributor as under; there will be a Penalty for fraud related loss including full amount of material loss + Penalty for defamation or brand loss</li>
		    </ul>
		    <ul>
		        15. CONFIDENTIALITY
		    <li>15.1. This Appointment and all information exchanged between the Parties under this Appointment or during the negotiations preceding this Appointment is confidential to them and may not be disclosed to any third party. Each Party shall hold in strictest confidence, shall not use or disclose to any third party, and shall take all necessary precautions to secure any Confidential Information of the other Party.</li>
		    <li>15.2. Disclosure of such information shall be restricted solely to employees, agents, consultants and representatives who have been advised of their obligation with respect to Confidential Information. Disclosures of Confidential Information to its outside professional advisors or for enforcement of this Appointment including any rights and obligations of a Party hereto shall not be deemed to be a breach of this confidential obligation by a party hereto. </li>
		    </ul>
		    <ul>
		    <li>16. Business Provided: The current nature of MOU is not purely pertaining to services & hence any default shall not be counted as deficing of services. Pay1 shall not be civil or original liable unless & until is an intention proven.</li>
		    </ul>
		    <ul>
		    <li>17. Applicable Law: This Agreement and the interpretation of its terms shall be governed by and construed in accordance with the laws of the State of Maharashtra and subject to the exclusive jurisdiction of the state courts located in Mumbai, India.</li>
		    </ul>
		    <span><b>MindsArray Technologies Pvt Ltd</b> <br></br></span>
		    <span><br> Authorized Signatory </br> </span>
			</p>
			</div>
			</div>
				        <br class="clearLeft" />
			</div>
			</div>';

		    $pdf->writeHTML($det, false, false, false, false, '');
		    $pdf->SetFont('', 'B', 9);
		    $pdf->SetFont('', '', 9);
		    $pdf->lastPage();
		    ob_clean();
		    $pdf->Output('/Pay1/Agreement.pdf', 'I');
		}

        function distContest(){

            $this->render('dist_contest');
        }

        function distContestDet(){

            $this->render('dist_contest_det');
        }

        function limitDepartmentDetails()
        {
            $this->render('limit_department');
        }

        function customerCare()
        {
            $this->render('customer_care');
        }

        function getInvoiceData($invoiceid,$ymid)
        {
            if(!empty($invoiceid) && !empty($ymid)):
            $query="SELECT inv.*,d.company as dname,DATE_FORMAT(inv.invoice_date,'%b') as month,DATE_FORMAT(inv.invoice_date,'%y') as year "
                    . "FROM invoices_data inv "
                    . "join distributors d "
                    . "on (d.id=inv.distributor_id) "
                    . "where inv.invoice_id='$invoiceid' and inv.yearmonth_id='$ymid' ";

            $data=$this->User->query($query);

            $this->set('invoice',$data);

            $this->render('/elements/taxInvoice');

            endif;
        }

        function getInvoiceHistory()
        {
            $fromdate=$this->params['form']['fromDate'];
            $todate=$this->params['form']['toDate'];
            $dist_id=$this->info['id'];
            $dist_name=$this->info['company'];
            $page = isset($this->params['form']['download']) ? $this->params['form']['download'] : "";

            if(!empty($fromdate) && !empty($todate)):
            $query="select  inv.*,month(inv.invoice_date) as month,DATE_FORMAT(inv.invoice_date,'%y') as year "
                    . "from invoices_data as inv "
                    . "where invoice_date >='$fromdate' and invoice_date <= '$todate' and distributor_id='$dist_id' "
                    . "order by invoice_date,invoice_id";

            $invoicedata=$this->User->query($query);
            endif;

            if($page == 'download'):
                        $this->set('page',$page);
                        App::import('Helper','csv');
                        $this->layout = null;
                        $this->autoLayout = false;
                        $csv = new CsvHelper();
                        $csv->addRow(array('BILL TO :',$dist_name));
                        $csv->addRow(array(''));
                        $line=array('Row','Date','Invoice No','Gross Amount','Discount','Net Amount');
                        $csv->addRow($line);
                        $i=1;
                        $topup=0;$discount=0;$gross_sum=0;

                        foreach($invoicedata as $invoice):
                            $fiscalyear=$invoice[0]['month']<4?($invoice[0]['year']-1)."-".$invoice[0]['year']:($invoice[0]['year'])."-".($invoice[0]['year']+1);
                            $invoice_id="PAY1/".$fiscalyear."/".$invoice['inv']['invoice_id'];
                            $totalsale=$invoice['inv']['invoice_date']>='2017-04-01'?$invoice['inv']['topup_buy']:$invoice['inv']['topup_buy']+$invoice['inv']['earning'];
                            $earning=$invoice['inv']['earning'];
                            $net_amt=$invoice['inv']['invoice_date']>='2017-04-01'?ceil($totalsale-$earning):ceil($invoice['inv']['topup_buy']);
                            $gross_sum+=$totalsale;
                            $discount+=$earning;
                            $gross_amt+=$net_amt;
                            $line=array($i,date('d-m-Y',strtotime($invoice['inv']['invoice_date'])),$invoice_id,$totalsale,$earning,$net_amt);
                            $csv->addRow($line);
                            $i++;
                        endforeach;

                        $csv->addRow(array('Total','','',$gross_sum,$discount,$gross_amt));
                        ob_clean();
                        echo $csv->render("invoice_".$fromdate."_".$todate.".csv");
            endif;

            $this->set('fromdate',$fromdate);
            $this->set('todate',$todate);
            $this->set('page',$page);
            $this->set('dist_name',$dist_name);
            $this->set('invoicedata',$invoicedata);
        }

        function getInvoiceHistoryNew()
        {
            $params = $this->params['form'];
            $user_id = $_SESSION['Auth']['User']['id'];
            $inv_month = isset($params['inv_month'])?$params['inv_month']:date('m');
            $inv_year  = isset($params['inv_year'])?$params['inv_year']:date('Y');
            $inv_type  = $params['inv_type'];
            $page = isset($params['download']) ? $params['download'] : "";

            $invoice_list =  $this->Invoice->getInvoiceList($user_id,$inv_month,$inv_year,$inv_type);
            $invoice_ids = array_map(function ($list) {return $list['invoice_id'];}, $invoice_list);

            if($page=='download')
            {
                $this->downloadZip($user_id,$invoice_list);
            }

            $this->set('user_id',$user_id);
            $this->set("year",$inv_year);
            $this->set("month",$inv_month);
            $this->set("type",$inv_type);
//            $this->set('invoice_ids',json_encode($invoice_ids));
            $this->set('invoice_ids',$invoice_ids);
            $this->set('invoicedata',$invoice_list);
        }

        function getAllInvoices()
        {
            $params = $this->params['form'];
            $inv_month = isset($params['inv_month'])?$params['inv_month']:date('m');
            $inv_year  = isset($params['inv_year'])?$params['inv_year']:date('Y');
            $inv_type  = $params['inv_type'];
            $group_id  = $params['group_id'];
            $page = isset($params['download']) ? $params['download'] : "";

            $invoice_list =  $this->Invoice->getAllInvoices($inv_month,$inv_year,$inv_type,$group_id);
            $invoice_ids = array_map(function ($list) {return $list['invoice_id'];}, $invoice_list);

            if($page=='download')
            {
                $this->downloadZip($user_id,$invoice_list);
            }

            $this->set("year",$inv_year);
            $this->set("month",$inv_month);
            $this->set("type",$inv_type);
            $this->set('invoice_ids',$invoice_ids);
            $this->set('invoicedata',$invoice_list);
        }

        function getNewInvoice($user_id,$invoice_id=NULL,$month=NULL,$year=NULL,$type=NULL,$email_id=NULL)
        {
            $this->autoRender = FALSE;

            if ($this->RequestHandler->isAjax())
            {
                $params = $this->params['form'];
                $user_id = $params['user_id'];
                $invoice_id = is_array($params['invoice_id'])?$params['invoice_id']:(array)$params['invoice_id'];
                $month = $params['month'];
                $year = $params['year'];
                $email_id = $params['email_id'];
                $type = $params['type'];

                if(empty($email_id))
                {
                    $checkmail_json = $this->checkIfEmailIdExists($user_id);
                    $checkmail = json_decode($checkmail_json,TRUE);

                    if($checkmail['status']=='success' && !empty($checkmail['data']))
                    {
                        $email_id = $checkmail['data'];
                        if($this->Invoice->sendMail($user_id,$invoice_id,$month,$year,$email_id))
                        {
                            echo json_encode(array('status'=>'success','type'=>0,'msg'=>'Invoice emailed successfully'));exit();
                        }
                    }
                    else
                    {
                        echo $checkmail_json;
                    }
                }
                else
                {
                    $this->Invoice->sendMail($user_id,$invoice_id,$month,$year,$email_id);
//                    $this->sendMail($user_id,$invoice_id,$month,$year,$email_id);
//                    if($this->Invoice->sendMail($user_id,$invoice_id,$month,$year,$email_id))
//                    {
//                        echo json_encode(array('status'=>'success','type'=>true,'msg'=>'Invoice emailed successfully'));exit();
//                    }
//                    else
//                    {
//                        echo json_encode(array('status'=>'failure','type'=>false,'data'=>'','msg'=>"Something went wrong. Please try again."));exit();
//                    }
                }
            }
            else
            {
                $invoice_data = $this->Invoice->getInvoiceData($user_id,$invoice_id,$month,$year,$email_id,$type);
                $response = $this->Invoice->generatePdf($invoice_data,$type);
            }
        }

        function checkIfEmailIdExists($user_id)
        {
            $this->autoRender = FALSE;
                $label_id = 24;
                $email_id = $this->Documentmanagement->getLabelDescription($user_id,$label_id);

                if(empty($email_id))
                {
                $email = $this->Slaves->query("Select email from distributors where user_id = '$user_id' ");
                    $email_id =  $email[0]['distributors']['email'];
                    $this->Documentmanagement->updateTextualInfo($user_id,$label_id,0,$email_id,$user_id);
                }

            if(filter_var($email_id, FILTER_VALIDATE_EMAIL))
            {
                return json_encode(array('status'=>'success','type'=>0,'data'=>$email_id));exit();
            }
            else
            {
                return json_encode(array('status'=>'failure','type'=>1,'data'=>'','msg'=>"Error"));exit();
            }
        }

        function downloadZip($user_id,$invoice_list)
        {
            $this->autoRender = FALSE;
            $zip = new ZipArchive;
            $filename = '/tmp/' .$user_id . '.zip';
            $zip->open('/tmp/' . $user_id . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach ($invoice_list as $inv)
            {
                $invoice_data = $this->Invoice->getInvoiceData($user_id,$inv['invoice_id'],$inv['month'],$inv['year'],3);
                $response = $this->Invoice->generatePdf($invoice_data,3);
                if($response)
                {
                    $filepath = "/tmp/taxInvoice".$inv['invoice_id'].".pdf";
                    $zip->addFile($filepath);
                }
            }

            $zip->close();
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename='.$filename);
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
        }

        function getOverallGstReport()
        {
            ini_set("memory_limit", "1024M");
            Configure::load('product_config');
            $gst_state_code_mapping=Configure::read('gst_state_code_mapping');

            $month = !empty($this->params['form']['inv_month'])?$this->params['form']['inv_month']:date('m', strtotime('-1 month'));
            $year = !empty($this->params['form']['inv_year'])?$this->params['form']['inv_year']:((date('m')=='01')?date('Y', strtotime('-1 year')):date('Y'));
            $type = $this->params['form']['type'];
            $page = $this->params['form']['download_gst'] ? $this->params['form']['download_gst'] : "";
            $from_date = date("$year-$month-01");
            $to_date = date("$year-$month-31");
            $fiscalyear=$month < 4?((substr($year,2,2)-1)."-".(substr($year,2,2))):((substr($year,2,2))."-".(substr($year,2,2)+1));
            $response = array();

            if($type == 0)
            {
                $get_supplierwise_purchase_data = "SELECT o.order_date,supplier_operator_id,s.name as supplier_name,p.name as operator_name,sb.account_holder_name,sb.account_no,s.gst_no,"
                        . "if(s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra'),'Maharashtra',if(s.location IN ('f','mh','ss','sss','Test','Testing') or s.location is null or s.location = ' ','Other','Non Maharashtra')) as location,"
                        . "sum(amount) as order_amt,sum(to_pay) as to_pay "
                        . "FROM inv_orders o "
                        . "JOIN inv_supplier_operator so ON (so.id = o.supplier_operator_id) "
                        . "JOIN inv_suppliers s ON (s.id = so.supplier_id) "
                        . "JOIN products p ON (p.id = so.operator_id) "
                        . "LEFT JOIN inv_supplier_banks sb ON (sb.id = o.supplier_bank_id) "
                        . "WHERE o.order_date >= '$from_date' "
                        . "AND o.order_date <= '$to_date' "
                        . "AND o.is_payment_done = '1' "
                        . "GROUP BY supplier_operator_id,order_date "
                        . "ORDER BY order_date ";

                $supplierwise_purchase_data = $this->Slaves->query($get_supplierwise_purchase_data);

                foreach ($supplierwise_purchase_data as $purchase_data)
                {
                    $response['purchase_data'][] = $purchase_data;
                }

                $get_modem_vendors_purchase_data = "SELECT o.order_date,so.id as soid,so.commission_type_formula,s.id as supplier_id,s.name as supplier_name,sb.account_holder_name,p.id as product_id,p.name as operator_name,sum(o.amount) as net_amt,sum(o.to_pay) as to_pay,p.type as product_type,s.gst_no, "
                            . "if(s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra'),'Maharashtra',if(s.location IN ('f','mh','ss','sss','Test','Testing') or s.location is null or s.location = ' ','Other',s.location)) as state, "
                            . "if(p.type=0,(sum(to_pay)/1.18),(((sum(to_pay) * so.commission_type_formula)/100)/1.18)) as taxable_amt,"
                            . "if(p.type=0,((sum(to_pay)/1.18)*18)/100,((((sum(to_pay)*so.commission_type_formula)/100)/1.18)*18)/100) as input_gst,"
                            . "if((s.location IN "
                            . "('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra')"
                            . " AND p.type = 0),((sum(to_pay)/1.18)*9)/100,"
                            . "if((s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra') AND p.type = 1),((((sum(to_pay) * so.commission_type_formula)/100)/1.18)*9)/100,0)) as cgst,"
                            . "if((s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra') AND p.type = 0),((sum(to_pay)/1.18)*9)/100,"
                            . "if((s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra') AND p.type = 1),((((sum(to_pay) * so.commission_type_formula)/100)/1.18)*9)/100,0)) as sgst,"
                            . "if((s.location NOT IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra') AND p.type = 0),((sum(to_pay)/1.18)*1.18)/100,"
                            . "if((s.location NOT IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra') AND p.type = 1),((((sum(to_pay) * so.commission_type_formula)/100)/1.18)*18)/100,0)) as igst "
                            . "FROM inv_orders o "
                            . "JOIN inv_supplier_operator so ON (so.id = o.supplier_operator_id) "
                            . "JOIN inv_suppliers s ON (s.id = so.supplier_id) "
                            . "JOIN products p ON (p.id = so.operator_id) "
                            . "LEFT JOIN inv_supplier_banks sb ON (sb.supplier_id = s.id AND sb.default_bank = '1') "
                            . "WHERE order_date >= '$from_date' "
                            . "AND order_date <= '$to_date' "
                            . "AND o.is_payment_done = '1' "
                            . "AND s.is_api = '0' "
                            . "GROUP BY so.id,p.type,o.order_date "
                            . "ORDER BY so.id,p.type,o.order_date ";

                $modem_vendors_purchase_data = $this->Slaves->query($get_modem_vendors_purchase_data);

                $report = array();

                foreach ($modem_vendors_purchase_data as $purchase_data)
                {
                    if($purchase_data['o']['order_date'] > '2018-01-15')
                    {
                        $product_type = ($purchase_data['p']['product_type'] == 0)?"P2P":"P2A";
                    }
                    else
                    {
                        $product_type = ($purchase_data['p']['product_type'] == 0 && $purchase_data['p']['product_id'] != 18)?"P2P":"P2A";
                    }

                    $state_code = !empty($purchase_data['s']['gst_no']) ? $gst_state_code_mapping[substr($purchase_data['s']['gst_no'],0,2)] : $purchase_data[0]['state'];
                    $state = ($state_code == 'Maharashtra')?'Maharashtra':'Outside';

                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['soid'] = $purchase_data['so']['soid'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['product_type'] = $product_type;
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['supplier_name'] = $purchase_data['s']['supplier_name'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['account_holder_name'] = $purchase_data['sb']['account_holder_name'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['operator_name'] = $purchase_data['p']['operator_name'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['gst_no'] = $purchase_data['s']['gst_no'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['state'] = !empty($purchase_data['s']['gst_no']) ? $gst_state_code_mapping[substr($purchase_data['s']['gst_no'],0,2)] : $purchase_data[0]['state'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['to_pay'] += $purchase_data[0]['to_pay'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['taxable_amt'] += $purchase_data[0]['taxable_amt'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['commission'] += !empty($purchase_data['so']['commission_type_formula'])?($purchase_data[0]['to_pay'] * $purchase_data['so']['commission_type_formula'])/100:0;
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['input_gst'] += $purchase_data[0]['input_gst'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['cgst'] += ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['sgst'] += ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['igst'] += ($state!='Maharashtra')?($purchase_data[0]['taxable_amt']*18)/100:0;

                    $response["Overall_".$state."_purchase"]['to_pay'] += $purchase_data[0]['to_pay'];
                    $response["Overall_".$state."_purchase"]['taxable_amt'] += $purchase_data[0]['taxable_amt'];
                    $response["Overall_".$state."_purchase"]['input_gst'] += $purchase_data[0]['input_gst'];
                    $response["Overall_".$state."_purchase"]['cgst'] += ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $response["Overall_".$state."_purchase"]['sgst'] += ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $response["Overall_".$state."_purchase"]['igst'] += ($state!='Maharashtra')?($purchase_data[0]['taxable_amt']*18)/100:0;
//                    $response[$product_type."_".$state."_purchase"]['net_amt'] += $purchase_data[0]['net_amt'];
                    $response[$product_type."_".$state."_purchase"]['to_pay'] += $purchase_data[0]['to_pay'];
                    $response[$product_type."_".$state."_purchase"]['taxable_amt'] += $purchase_data[0]['taxable_amt'];
                    $response[$product_type."_".$state."_purchase"]['input_gst'] += $purchase_data[0]['input_gst'];
                    $response[$product_type."_".$state."_purchase"]['cgst'] += ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $response[$product_type."_".$state."_purchase"]['sgst'] += ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $response[$product_type."_".$state."_purchase"]['igst'] += ($state!='Maharashtra')?($purchase_data[0]['taxable_amt']*18)/100:0;
                }

                $get_api_vendors_purchase_data = "SELECT so.id as soid,so.commission_type_formula,s.id as supplier_id,p.id as product_id,avs.product_type,sum(avs.sale) as to_pay,s.gst_no,s.name as supplier_name ,sb.account_holder_name, p.name as operator_name,"
                                                . "if(s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra'),'Maharashtra',if(s.location IN ('f','mh','ss','sss','Test','Testing') or s.location is null or s.location = ' ','Other',s.location)) as state, "
                                                . "if(avs.product_type=0,(sum(avs.sale)/1.18),(((sum(avs.sale) * so.commission_type_formula)/100)/1.18)) as taxable_amt,"
                                                . "if(avs.product_type=0,((sum(avs.sale)/1.18)*18)/100,((((sum(avs.sale)*so.commission_type_formula)/100)/1.18)*18)/100) as input_gst,"
                                                . "if((s.location IN "
                                                . "('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra')"
                                                . " AND avs.product_type = 0),((sum(avs.sale)/1.18)*9)/100,"
                                                . "if((s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra') AND avs.product_type = 1),((((sum(avs.sale) * so.commission_type_formula)/100)/1.18)*9)/100,0)) as cgst,"
                                                . "if((s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra') AND avs.product_type = 0),((sum(avs.sale)/1.18)*9)/100,"
                                                . "if((s.location IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra') AND avs.product_type = 1),((((sum(avs.sale) * so.commission_type_formula)/100)/1.18)*9)/100,0)) as sgst,"
                                                . "if((s.location NOT IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra') AND avs.product_type = 0),((sum(avs.sale)/1.18)*1.18)/100,"
                                                . "if((s.location NOT IN ('Mumbai','Maharashtra','BHIVANDI','Bhiwandi','bhiwandi dist than','Boisar','Borivali','dahisar','Jogeshwari','Jogeshwari E Mumbai','Kalkeri','kurla west-mumbai','Maharashta','Maharshtra','makwana,andhri (e) mumbai_400059','Malad (E)','Malad (East)','Nagpur','Nashik','Nasik','Navi Mumbai','Palghar Maharashtra','PUNE','Satara','Thane','Vasai East,Maharashtra','Virar, Maharashtra') AND avs.product_type = 1),((((sum(avs.sale) * so.commission_type_formula)/100)/1.18)*18)/100,0)) as igst "
                                                . "FROM api_vendors_sale_data avs "
//                                                . "JOIN inv_supplier_operator so ON (avs.supplier_id = so.supplier_id AND avs.product_id = so.operator_id) "
                                                . "JOIN inv_suppliers s ON (s.id = avs.supplier_id) "
//                                                . "JOIN inv_supplier_banks sb ON (sb.supplier_id = s.id AND sb.default_bank = '1') "
                                                . "JOIN products p ON (p.id = avs.product_id) "
                                                . "LEFT JOIN inv_supplier_operator so ON (avs.supplier_id = so.supplier_id AND avs.product_id = so.operator_id) "
                                                . "LEFT JOIN inv_supplier_banks sb ON (sb.supplier_id = s.id AND sb.default_bank = '1') "
                                                . "WHERE avs.date >= '$from_date' "
                                                . "AND avs.date <= '$to_date' "
                                                . "GROUP BY s.id,p.id,avs.product_type ";

                $api_vendors_purchase_data = $this->Slaves->query($get_api_vendors_purchase_data);

                foreach ($api_vendors_purchase_data as $purchase_data)
                {
                    $product_type = ($purchase_data['avs']['product_type'] == 0)?"P2P":"P2A";
                    $state_code = !empty($purchase_data['s']['gst_no']) ? $gst_state_code_mapping[substr($purchase_data['s']['gst_no'],0,2)] : $purchase_data[0]['state'];
                    $state = ($state_code == 'Maharashtra')?'Maharashtra':'Outside';

                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['product_type'] = $product_type;
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['supplier_name'] = $purchase_data['s']['supplier_name'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['account_holder_name'] = $purchase_data['sb']['account_holder_name'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['operator_name'] = $purchase_data['p']['operator_name'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['gst_no'] = $purchase_data['s']['gst_no'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['state'] = !empty($purchase_data['s']['gst_no']) ? $gst_state_code_mapping[substr($purchase_data['s']['gst_no'],0,2)] : $purchase_data[0]['state'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['to_pay'] = $purchase_data[0]['to_pay'];
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['taxable_amt'] = !empty($purchase_data[0]['taxable_amt'])?$purchase_data[0]['taxable_amt']:'0.00';
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['commission'] = !empty($purchase_data['so']['commission_type_formula'])?($purchase_data[0]['to_pay'] * $purchase_data['so']['commission_type_formula'])/100:0;
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['input_gst'] = !empty($purchase_data[0]['input_gst'])?$purchase_data[0]['input_gst']:'0.00';
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['cgst'] = ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['sgst'] = ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $report[$purchase_data['s']['supplier_id']."_".$purchase_data['p']['product_id']][$product_type]['igst'] = ($state!='Maharashtra')?($purchase_data[0]['taxable_amt']*18)/100:0;

                    $response["Overall_".$state."_purchase"]['to_pay'] += $purchase_data[0]['to_pay'];
                    $response["Overall_".$state."_purchase"]['taxable_amt'] += $purchase_data[0]['taxable_amt'];
                    $response["Overall_".$state."_purchase"]['input_gst'] += $purchase_data[0]['input_gst'];
                    $response["Overall_".$state."_purchase"]['cgst'] += ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $response["Overall_".$state."_purchase"]['sgst'] += ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $response["Overall_".$state."_purchase"]['igst'] += ($state!='Maharashtra')?($purchase_data[0]['taxable_amt']*18)/100:0;

                    $response[$product_type."_".$state."_purchase"]['to_pay'] += $purchase_data[0]['to_pay'];
                    $response[$product_type."_".$state."_purchase"]['taxable_amt'] += $purchase_data[0]['taxable_amt'];
                    $response[$product_type."_".$state."_purchase"]['input_gst'] += $purchase_data[0]['input_gst'];
                    $response[$product_type."_".$state."_purchase"]['cgst'] += ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $response[$product_type."_".$state."_purchase"]['sgst'] += ($state=='Maharashtra')?($purchase_data[0]['taxable_amt']*9)/100:0;
                    $response[$product_type."_".$state."_purchase"]['igst'] += ($state!='Maharashtra')?($purchase_data[0]['taxable_amt']*18)/100:0;
                }
                /*echo "<pre>";
                print_r($response);
                echo "</pre>";
                exit;*/
                
                if($page == 'download')
                {
                    $this->formatPurchaseReport($response,$month,$year);
                }

                if($page == 'monthlyreport')
                {
                    $this->formatMonthlyPurchaseReport($report,$month,$year);
                }
            }
            elseif($type == 1)
            {
                $get_p2p_sales_data = "SELECT if(t.target_state='Maharashtra','Maharashtra','Outside') as state,tl.description,sum(tl.total_amt) as gross_amt,"
                        . "if(tl.description='MPOS Service Charges',sum(tl.payable_amt)*1.18,sum(tl.payable_amt)) as net_amt,"
                        . "if(tl.description='MPOS Service Charges',sum(tl.payable_amt),sum(tl.payable_amt)/1.18) as taxable_amt,"
                        . "if(tl.description='MPOS Service Charges',(sum(tl.payable_amt)*18)/100,((sum(tl.payable_amt)/1.18)*18)/100) as input_gst,"
                        . "if(tl.description='MPOS Service Charges',(sum(tl.payable_amt)*tl.cgst)/100,((sum(tl.payable_amt)/1.18)*tl.cgst)/100) as cgst,"
                        . "if(tl.description='MPOS Service Charges',(sum(tl.payable_amt)*tl.sgst)/100,((sum(tl.payable_amt)/1.18)*tl.sgst)/100) as sgst,"
                        . "if(tl.description='MPOS Service Charges',(sum(tl.payable_amt)*tl.igst)/100,((sum(tl.payable_amt)/1.18)*tl.igst)/100) as igst "
                        . "FROM tax_invoices t "
                        . "JOIN tax_invoices_logs tl "
                        . "ON (t.id = tl.invoice_id and t.month = tl.month and t.year=tl.year) "
                        . "JOIN retailers r "
                        . "ON (t.target_id = r.user_id) "
                        . "WHERE 1 "
                        . "AND t.month = '$month' "
                        . "AND t.year = '$year' "
                        . "AND t.target_group_id = '6' "
                        . "AND t.source_group_id = '0' "
                        . "GROUP BY state,tl.description";

                $p2p_sales_data = $this->Slaves->query($get_p2p_sales_data);

                foreach ($p2p_sales_data as $sales_data)
                {
                    $response["Overall_" . $sales_data[0]['state'] . "_sale"]['state'] = $sales_data[0]['state'];
                    $response["Overall_" . $sales_data[0]['state'] . "_sale"]['gross_amt'] += $sales_data[0]['gross_amt'];
                    $response["Overall_" . $sales_data[0]['state'] . "_sale"]['net_amt'] += $sales_data[0]['net_amt'];
                    $response["Overall_" . $sales_data[0]['state'] . "_sale"]['discount'] += $sales_data[0]['gross_amt'] != 0?$sales_data[0]['gross_amt'] - $sales_data[0]['net_amt']:0;
                    $response["Overall_" . $sales_data[0]['state'] . "_sale"]['taxable_amt'] += $sales_data[0]['taxable_amt'];
                    $response["Overall_" . $sales_data[0]['state'] . "_sale"]['input_gst'] += $sales_data[0]['input_gst'];
                    $response["Overall_" . $sales_data[0]['state'] . "_sale"]['cgst'] += $sales_data[0]['cgst'];
                    $response["Overall_" . $sales_data[0]['state'] . "_sale"]['sgst'] += $sales_data[0]['sgst'];
                    $response["Overall_" . $sales_data[0]['state'] . "_sale"]['igst'] += $sales_data[0]['igst'];
                }

                foreach ($p2p_sales_data as $p2p_data)
                {
                    $response["P2P_".$p2p_data[0]['state']."_sale"][] = $p2p_data;
                }

                $get_p2a_sales_data = "SELECT if(t.source_state='Maharashtra','Maharashtra','Outside') as state,tl.description,sum(tl.total_amt) as gross_amt,sum(tl.payable_amt) as net_amt,"
                        . "(sum(tl.payable_amt)/1.18) as taxable_amt,"
                        . "(((sum(tl.payable_amt)/1.18)*18)/100) as input_gst,"
                        . "(((sum(tl.payable_amt)/1.18)*tl.cgst)/100) as cgst,"
                        . "(((sum(tl.payable_amt)/1.18)*tl.sgst)/100) as sgst,"
                        . "(((sum(tl.payable_amt)/1.18)*tl.igst)/100) as igst "
                        . "FROM tax_invoices t "
                        . "JOIN tax_invoices_logs tl "
                        . "ON (t.id = tl.invoice_id and t.month = tl.month and t.year=tl.year) "
                        . "JOIN retailers r "
                        . "ON (t.source_id = r.user_id) "
                        . "WHERE 1 "
                        . "AND t.month = '$month' "
                        . "AND t.year = '$year' "
                        . "AND t.source_group_id = '6' "
                        . "AND t.target_group_id = '0' "
                        . "GROUP BY state,tl.description";

                $p2a_sales_data = $this->Slaves->query($get_p2a_sales_data);

                foreach ($p2a_sales_data as $p2a_data)
                {
                    $response["P2A_".$p2a_data[0]['state']."_sale"][] = $p2a_data;
                }

                $get_ret_p2p_data = "SELECT concat('$fiscalyear/$month/',t.id) as invoice_id,r.shopname as retailer_name,r.mobile as retailer_mobile,t.target_gst_no as retailer_gst_no,t.target_state as state,tl.description,"
                        . "sum(tl.total_amt) as gross_amt,if(tl.description='MPOS Service Charges',sum(tl.payable_amt)*1.18,sum(tl.payable_amt)) as net_amt,"
                        . "if(tl.description='MPOS Service Charges',sum(tl.payable_amt),sum(tl.payable_amt)/1.18) as taxable_amt,"
                        . "if(tl.description='MPOS Service Charges',(sum(tl.payable_amt)*18)/100,((sum(tl.payable_amt)/1.18)*18)/100) as input_gst,"
                        . "if(tl.description='MPOS Service Charges',(sum(tl.payable_amt)*tl.cgst)/100,((sum(tl.payable_amt)/1.18)*tl.cgst)/100) as cgst,"
                        . "if(tl.description='MPOS Service Charges',(sum(tl.payable_amt)*tl.sgst)/100,((sum(tl.payable_amt)/1.18)*tl.sgst)/100) as sgst,"
                        . "if(tl.description='MPOS Service Charges',(sum(tl.payable_amt)*tl.igst)/100,((sum(tl.payable_amt)/1.18)*tl.igst)/100) as igst "
                        . "FROM tax_invoices t "
                        . "JOIN tax_invoices_logs tl "
                        . "ON (t.id=tl.invoice_id and t.invoice_date=tl.invoice_date) "
                        . "JOIN retailers r "
                        . "ON (t.target_id=r.user_id) "
                        . "WHERE t.month = '$month' "
                        . "AND t.year = '$year' "
                        . "AND t.target_group_id = '6' "
                        . "AND t.source_group_id = '0' "
                        . "GROUP BY t.target_id,tl.description ";

                $ret_p2p_data = $this->Slaves->query($get_ret_p2p_data);

                /** IMP DATA ADDED : START**/
                $ret_mobiles = array_map(function($element){
                    return $element['r']['retailer_mobile'];
                },$ret_p2p_data);

                $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);
                /** IMP DATA ADDED : END**/

                foreach ($ret_p2p_data as $invoice_data)
                {
                    $invoice_data['r']['retailer_name'] = (isset($imp_data[$invoice_data['r']['retailer_mobile']])) ? $imp_data[$invoice_data['r']['retailer_mobile']]['imp']['shop_est_name'] : $invoice_data['r']['retailer_name'];
                    $response['p2p_invoice_data'][] = $invoice_data;
                }

                $get_ret_p2a_data = "SELECT concat('$fiscalyear/$month/',t.id) as invoice_id,r.shopname as retailer_name,r.mobile as retailer_mobile,t.source_gst_no as retailer_gst_no,t.source_state as state,tl.description,"
                        . "sum(tl.total_amt) as gross_amt,sum(tl.payable_amt) as net_amt,"
                        . "(sum(tl.payable_amt)/1.18) as taxable_amt,"
                        . "(((sum(tl.payable_amt)/1.18)*18)/100) as input_gst,"
                        . "(((sum(tl.payable_amt)/1.18)*tl.cgst)/100) as cgst,"
                        . "(((sum(tl.payable_amt)/1.18)*tl.sgst)/100) as sgst,"
                        . "(((sum(tl.payable_amt)/1.18)*tl.igst)/100) as igst "
                        . "FROM tax_invoices t "
                        . "JOIN tax_invoices_logs tl "
                        . "ON (t.id=tl.invoice_id and t.invoice_date=tl.invoice_date) "
                        . "JOIN retailers r "
                        . "ON (t.source_id=r.user_id) "
                        . "WHERE t.month = '$month' "
                        . "AND t.year = '$year' "
                        . "AND t.target_group_id = '0' "
                        . "AND t.source_group_id = '6' "
                        . "GROUP BY t.source_id,tl.description ";

                $ret_p2a_data = $this->Slaves->query($get_ret_p2a_data);

                /** IMP DATA ADDED : START**/
                $ret_mobiles = array_map(function($element){
                    return $element['r']['retailer_mobile'];
                },$ret_p2a_data);

                $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);
                /** IMP DATA ADDED : END**/
                foreach ($ret_p2a_data as $invoice_data)
                {
                    $invoice_data['r']['retailer_name'] = (isset($imp_data[$invoice_data['r']['retailer_mobile']])) ? $imp_data[$invoice_data['r']['retailer_mobile']]['imp']['shop_est_name'] : $invoice_data['r']['retailer_name'];
                    $response['p2a_invoice_data'][] = $invoice_data;
                }

                if($page == 'download')
                {
                    $this->formatSaleReport($response,$month,$year);
                }
            }

            $this->set('month',$month);
            $this->set('year',$year);
            $this->set('page',$page);
            $this->set('gst_data',$response);
        }

        function formatPurchaseReport($gst_data,$month,$year)
        {
            $this->autoRender = false;
            App::import('Helper','csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();

//            if(!empty($data))
//            {
//                $line = array('Circle','Net Purchase','To pay','Taxable','Input @18%','CGST','SGST','IGST');
//                $csv->addRow($line);
//
//                if(isset($data['Overall_Maharashtra_purchase']))
//                {
//                    $line = array('Maharashtra',round($data['Overall_Maharashtra_purchase'][0]['net_amt'],2),round($data['Overall_Maharashtra_purchase'][0]['to_pay'],2),round($data['Overall_Maharashtra_purchase'][0]['taxable_amt'],2),round($data['Overall_Maharashtra_purchase'][0]['input_gst'],2),round($data['Overall_Maharashtra_purchase'][0]['cgst'],2),round($data['Overall_Maharashtra_purchase'][0]['sgst'],2),round($data['Overall_Maharashtra_purchase'][0]['igst'],2));
//                    $csv->addRow($line);
//                }
//
//                if(isset($data['Overall_Outside_purchase']))
//                {
//                    $line = array('Non Maharashtra',round($data['Overall_Outside_purchase'][0]['net_amt'],2),round($data['Overall_Outside_purchase'][0]['to_pay'],2),round($data['Overall_Outside_purchase'][0]['taxable_amt'],2),round($data['Overall_Outside_purchase'][0]['input_gst'],2),round($data['Overall_Outside_purchase'][0]['cgst'],2),round($data['Overall_Outside_purchase'][0]['sgst'],2),round($data['Overall_Outside_purchase'][0]['igst'],2));
//                    $csv->addRow($line);
//                }
//
//                $line = array('Total',round(($data['Overall_Maharashtra_purchase'][0]['net_amt']+$data['Overall_Outside_purchase'][0]['net_amt']),2),round(($data['Overall_Maharashtra_purchase'][0]['to_pay']+$data['Overall_Outside_purchase'][0]['to_pay']),2),round(($data['Overall_Maharashtra_purchase'][0]['taxable_amt']+$data['Overall_Outside_purchase'][0]['taxable_amt']),2),round(($data['Overall_Maharashtra_purchase'][0]['input_gst']+$data['Overall_Outside_purchase'][0]['input_gst']),2),round(($data['Overall_Maharashtra_purchase'][0]['cgst']+$data['Overall_Outside_purchase'][0]['cgst']),2),round(($data['Overall_Maharashtra_purchase'][0]['sgst']+$data['Overall_Outside_purchase'][0]['sgst']),2),round(($data['Overall_Maharashtra_purchase'][0]['igst']+$data['Overall_Outside_purchase'][0]['igst']),2));
//                $csv->addRow($line);
//                $csv->addRow(array(''));
//
//                if(isset($data['P2P_Maharashtra_purchase']))
//                {
//                    $line = array('P2P Maharashtra',round($data['P2P_Maharashtra_purchase'][0]['net_amt'],2),round($data['P2P_Maharashtra_purchase'][0]['to_pay'],2),round($data['P2P_Maharashtra_purchase'][0]['taxable_amt'],2),round($data['P2P_Maharashtra_purchase'][0]['input_gst'],2),round($data['P2P_Maharashtra_purchase'][0]['cgst'],2),round($data['P2P_Maharashtra_purchase'][0]['sgst'],2),round($data['P2P_Maharashtra_purchase'][0]['igst'],2));
//                    $csv->addRow($line);
//                }
//
//                if(isset($data['P2P_Outside_purchase']))
//                {
//                    $line = array('P2P Non Maharashtra',round($data['P2P_Outside_purchase'][0]['net_amt'],2),round($data['P2P_Outside_purchase'][0]['to_pay'],2),round($data['P2P_Outside_purchase'][0]['taxable_amt'],2),round($data['P2P_Outside_purchase'][0]['input_gst'],2),round($data['P2P_Outside_purchase'][0]['cgst'],2),round($data['P2P_Outside_purchase'][0]['sgst'],2),round($data['P2P_Outside_purchase'][0]['igst'],2));
//                    $csv->addRow($line);
//                }
//
//                $line = array('Total',round(($data['P2P_Maharashtra_purchase'][0]['net_amt']+$data['P2P_Outside_purchase'][0]['net_amt']),2),round(($data['P2P_Maharashtra_purchase'][0]['to_pay']+$data['P2P_Outside_purchase'][0]['to_pay']),2),round(($data['P2P_Maharashtra_purchase'][0]['taxable_amt']+$data['P2P_Outside_purchase'][0]['taxable_amt']),2),round(($data['P2P_Maharashtra_purchase'][0]['input_gst']+$data['P2P_Outside_purchase'][0]['input_gst']),2),round(($data['P2P_Maharashtra_purchase'][0]['cgst']+$data['P2P_Outside_purchase'][0]['cgst']),2),round(($data['P2P_Maharashtra_purchase'][0]['sgst']+$data['P2P_Outside_purchase'][0]['sgst']),2),round(($data['P2P_Maharashtra_purchase'][0]['igst']+$data['P2P_Outside_purchase'][0]['igst']),2));
//                $csv->addRow($line);
//                $csv->addRow(array(''));
//
//                if(isset($data['P2A_Maharashtra_purchase']))
//                {
//                    $line = array('P2A Maharashtra',$data['P2A_Maharashtra_purchase'][0]['net_amt'],round($data['P2A_Maharashtra_purchase'][0]['to_pay'],2),round($data['P2A_Maharashtra_purchase'][0]['taxable_amt'],2),round($data['P2A_Maharashtra_purchase'][0]['input_gst'],2),round($data['P2A_Maharashtra_purchase'][0]['cgst'],2),round($data['P2A_Maharashtra_purchase'][0]['sgst'],2),round($data['P2A_Maharashtra_purchase'][0]['igst'],2));
//                    $csv->addRow($line);
//                }
//
//                if(isset($data['P2A_Outside_purchase']))
//                {
//                    $line = array('P2A Non Maharashtra',round($data['P2A_Outside_purchase'][0]['net_amt'],2),round($data['P2A_Outside_purchase'][0]['to_pay'],2),round($data['P2A_Outside_purchase'][0]['taxable_amt'],2),round($data['P2A_Outside_purchase'][0]['input_gst'],2),round($data['P2A_Outside_purchase'][0]['cgst'],2),round($data['P2A_Outside_purchase'][0]['sgst'],2),round($data['P2A_Outside_purchase'][0]['igst'],2));
//                    $csv->addRow($line);
//                }
//
//                $line = array('Total',round(($data['P2A_Maharashtra_purchase'][0]['net_amt']+$data['P2A_Outside_purchase'][0]['net_amt']),2),round(($data['P2A_Maharashtra_purchase'][0]['to_pay']+$data['P2A_Outside_purchase'][0]['to_pay']),2),round(($data['P2A_Maharashtra_purchase'][0]['taxable_amt']+$data['P2A_Outside_purchase'][0]['taxable_amt']),2),round(($data['P2A_Maharashtra_purchase'][0]['input_gst']+$data['P2A_Outside_purchase'][0]['input_gst']),2),round(($data['P2A_Maharashtra_purchase'][0]['cgst']+$data['P2A_Outside_purchase'][0]['cgst']),2),round(($data['P2A_Maharashtra_purchase'][0]['sgst']+$data['P2A_Outside_purchase'][0]['sgst']),2),round(($data['P2A_Maharashtra_purchase'][0]['igst']+$data['P2A_Outside_purchase'][0]['igst']),2));
//                $csv->addRow($line);
//
//                echo $csv->render("purchase_report_".$year.$month.".csv");
//            }
            if(isset($gst_data['purchase_data']))
            {
                $line = array('Order Date','SOId ','Supplier Name','Operator Name','Account holder name ','Account No ','GST No','Location','Order Amount','To Pay');
                $csv->addRow($line);

                foreach ($gst_data['purchase_data'] as $data)
                {
                    $line = array($data['o']['order_date'],$data['o']['supplier_operator_id'],$data['s']['supplier_name'],$data['p']['operator_name'],$data['sb']['account_holder_name'],$data['sb']['account_no'],$data['s']['gst_no'],$data[0]['location'],$data[0]['order_amt'],$data[0]['to_pay']);
                    $csv->addRow($line);
                }

                echo $csv->render("purchase_report_".$year.$month.".csv");
            }

            exit;
        }

        function formatMonthlyPurchaseReport($purchase_data,$month,$year)
        {
            $this->autoRender = false;
            App::import('Helper','csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();

            if(!empty($purchase_data))
            {
                $line = array('Product Type','Supplier Name','Account holder name','Operator Name','GST No','Location','To Pay','Taxable amount','Commission','Input GST','CGST','SGST','IGST');
                $csv->addRow($line);

                foreach ($purchase_data as $soid => $p_data)
                {
                    foreach($p_data as $p_type => $data)
                    {
                        $line = array($data['product_type'],$data['supplier_name'],$data['account_holder_name'],$data['operator_name'],$data['gst_no'],$data['state'],$data['to_pay'],$data['taxable_amt'],$data['commission'],$data['input_gst'],$data['cgst'],$data['sgst'],$data['igst']);
                        $csv->addRow($line);
                    }
                }

                echo $csv->render("monthly_purchase_report_".$year.$month.".csv");
            }

            exit;
        }

        function formatSaleReport($gst_data,$month,$year)
        {
            $this->autoRender = false;
            App::import('Helper','csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();
//            $line = array('For Sales Consolidated Summary');
//            $csv->addRow($line);
//            $line = array('Circle','Sale','Discount-Dist','Net Purchase','Taxable','Input @18%','CGST','SGST','IGST');
//            $csv->addRow($line);
//            $line = array($gst_data['Overall_Maharashtra_sale'][0]['state'],number_format($gst_data['Overall_Maharashtra_sale'][0]['gross_amt'],2),($gst_data['Overall_Maharashtra_sale'][0]['gross_amt']!=0)?number_format(($gst_data['Overall_Maharashtra_sale'][0]['gross_amt']-$gst_data['Overall_Maharashtra_sale'][0]['net_amt']),2):"-",number_format($gst_data['Overall_Maharashtra_sale'][0]['net_amt'],2),number_format($gst_data['Overall_Maharashtra_sale'][0]['taxable_amt'],2),number_format($gst_data['Overall_Maharashtra_sale'][0]['input_gst'],2),number_format($gst_data['Overall_Maharashtra_sale'][0]['cgst'],2),number_format($gst_data['Overall_Maharashtra_sale'][0]['sgst'],2),number_format($gst_data['Overall_Maharashtra_sale'][0]['igst'],2));
//            $csv->addRow($line);
//            $line = array('Non Maharashtra',number_format($gst_data['Overall_Outside_sale'][0]['gross_amt'],2),($gst_data['Overall_Outside_sale'][0]['gross_amt']!=0)?number_format(($gst_data['Overall_Outside_sale'][0]['gross_amt']-$gst_data['Overall_Outside_sale'][0]['net_amt']),2):"-",number_format($gst_data['Overall_Outside_sale'][0]['net_amt'],2),number_format($gst_data['Overall_Outside_sale'][0]['taxable_amt'],2),number_format($gst_data['Overall_Outside_sale'][0]['input_gst'],2),number_format($gst_data['Overall_Outside_sale'][0]['cgst'],2),number_format($gst_data['Overall_Outside_sale'][0]['sgst'],2),number_format($gst_data['Overall_Outside_sale'][0]['igst'],2));
//            $csv->addRow($line);
//            $csv->addRow(array(''));
//
//            $line = array('P2P Maharashtra');
//            $csv->addRow($line);
//            $line = array('','Sale','Discount-Dist','Net Purchase','Taxable','Input @18%','CGST','SGST','IGST');
//            $csv->addRow($line);
//            foreach ($gst_data['P2P_Maharashtra_sale'] as $mh_p2p_data)
//            {
//                $line = array($mh_p2p_data['tl']['description'],number_format($mh_p2p_data[0]['gross_amt'],2),($mh_p2p_data[0]['gross_amt']!=0)?number_format(($mh_p2p_data[0]['gross_amt']-$mh_p2p_data[0]['net_amt']),2):"-",number_format($mh_p2p_data[0]['net_amt'],2),number_format($mh_p2p_data[0]['taxable_amt'],2),number_format($mh_p2p_data[0]['input_gst'],2),number_format($mh_p2p_data[0]['cgst'],2),number_format($mh_p2p_data[0]['sgst'],2),number_format($mh_p2p_data[0]['igst'],2));
//                $csv->addRow($line);
//            }
//            $csv->addRow(array(''));
//
//            $line = array('P2P Non Maharashtra');
//            $csv->addRow($line);
//            $line = array('','Sale','Discount-Dist','Net Purchase','Taxable','Input @18%','CGST','SGST','IGST');
//            $csv->addRow($line);
//            foreach ($gst_data['P2P_Outside_sale'] as $nm_p2p_data)
//            {
//                $line = array($nm_p2p_data['tl']['description'],number_format($nm_p2p_data[0]['gross_amt'],2),($nm_p2p_data[0]['gross_amt']!=0)?number_format(($nm_p2p_data[0]['gross_amt']-$nm_p2p_data[0]['net_amt']),2):"-",number_format($nm_p2p_data[0]['net_amt'],2),number_format($nm_p2p_data[0]['taxable_amt'],2),number_format($nm_p2p_data[0]['input_gst'],2),number_format($nm_p2p_data[0]['cgst'],2),number_format($nm_p2p_data[0]['sgst'],2),number_format($nm_p2p_data[0]['igst'],2));
//                $csv->addRow($line);
//            }
//            $csv->addRow(array(''));
//
//            $line = array('P2A Maharashtra');
//            $csv->addRow($line);
//            $line = array('','Sale','Discount-Dist','Net Purchase','Taxable','Input @18%','CGST','SGST','IGST');
//            $csv->addRow($line);
//            foreach ($gst_data['P2A_Maharashtra_sale'] as $mh_p2a_data)
//            {
//                $line = array($mh_p2a_data['tl']['description'],number_format($mh_p2a_data[0]['gross_amt'],2),($mh_p2a_data[0]['gross_amt']!=0)?number_format(($mh_p2a_data[0]['gross_amt']-$mh_p2a_data[0]['net_amt']),2):"-",number_format($mh_p2a_data[0]['net_amt'],2),number_format($mh_p2a_data[0]['taxable_amt'],2),number_format($mh_p2a_data[0]['input_gst'],2),number_format($mh_p2a_data[0]['cgst'],2),number_format($mh_p2a_data[0]['sgst'],2),number_format($mh_p2a_data[0]['igst'],2));
//                $csv->addRow($line);
//            }
//            $csv->addRow(array(''));
//
//            $line = array('P2A Non Maharashtra');
//            $csv->addRow($line);
//            $line = array('','Sale','Discount-Dist','Net Purchase','Taxable','Input @18%','CGST','SGST','IGST');
//            $csv->addRow($line);
//            foreach ($gst_data['P2A_Outside_sale'] as $nm_p2a_data)
//            {
//                $line = array($nm_p2a_data['tl']['description'],number_format($nm_p2a_data[0]['gross_amt'],2),($nm_p2a_data[0]['gross_amt']!=0)?number_format(($nm_p2a_data[0]['gross_amt']-$nm_p2a_data[0]['net_amt']),2):"-",number_format($nm_p2a_data[0]['net_amt'],2),number_format($nm_p2a_data[0]['taxable_amt'],2),number_format($nm_p2a_data[0]['input_gst'],2),number_format($nm_p2a_data[0]['cgst'],2),number_format($nm_p2a_data[0]['sgst'],2),number_format($nm_p2a_data[0]['igst'],2));
//                $csv->addRow($line);
//            }
//
//            echo $csv->render("sales_report_".$year.$month.".csv");
//            exit;

            if(isset($gst_data['p2p_invoice_data']))
            {
                $csv->addRow(array('P2P Sale Report'));
                $csv->addRow(array(''));
                $line = array('Invoice No','Retailer Name','Mobile No','State','Description','Retailer GST No','Gross','Discount','Net Amount','Input @18%','CGST','SGST','IGST');
                $csv->addRow($line);

                foreach ($gst_data['p2p_invoice_data'] as $data)
                {
                    $line = array($data[0]['invoice_id'],$data['r']['retailer_name'],$data['r']['retailer_mobile'],$data['t']['state'],$data['tl']['description'],$data['t']['retailer_gst_no'],round($data[0]['gross_amt'],2),($data[0]['gross_amt']!=0)?round(($data[0]['gross_amt']-$data[0]['net_amt']),2):'-',round($data[0]['net_amt'],2),round($data[0]['input_gst'],2),round($data[0]['cgst'],2),round($data[0]['sgst'],2),round($data[0]['igst'],2));
                    $csv->addRow($line);
                }
                $csv->addRow(array(''));
//                echo $csv->render("retailers_sales_report_".$year.$month.".csv");
            }

            if(isset($gst_data['p2a_invoice_data']))
            {
                $csv->addRow(array(''));
                $csv->addRow(array('P2A Sale Report'));
                $csv->addRow(array(''));
                $line = array('Invoice No','Retailer Name','Mobile No','State','Description','Retailer GST No','Gross','Discount','Net Amount','Input @18%','CGST','SGST','IGST');
                $csv->addRow($line);

                foreach ($gst_data['p2a_invoice_data'] as $data)
                {
                    $line = array($data[0]['invoice_id'],$data['r']['retailer_name'],$data['r']['retailer_mobile'],$data['t']['state'],$data['tl']['description'],$data['t']['retailer_gst_no'],round($data[0]['gross_amt'],2),($data[0]['gross_amt']!=0)?round(($data[0]['gross_amt']-$data[0]['net_amt']),2):'-',round($data[0]['net_amt'],2),round($data[0]['input_gst'],2),round($data[0]['cgst'],2),round($data[0]['sgst'],2),round($data[0]['igst'],2));
                    $csv->addRow($line);
                }

                echo $csv->render("retailers_sales_report_".$year.$month.".csv");
            }
            exit;
        }

        function getTDSReport()
        {
            $month = !empty($this->params['form']['inv_month'])?$this->params['form']['inv_month']:date('m', strtotime('-1 month'));
            $year = !empty($this->params['form']['inv_year'])?$this->params['form']['inv_year']:((date('m')=='01')?date('Y', strtotime('-1 year')):date('Y'));
            $page = $this->params['form']['download_tds'] ? $this->params['form']['download_tds'] : "";
            $from_date = date("$year-$month-01");
            $to_date = date("$year-$month-31");

            $distributor_gst_nos = $this->Documentmanagement->getLabelDescription(NULL,20);
            $distributor_pan_nos = $this->Documentmanagement->getLabelDescription(NULL,9);

            $get_dist_commission = "SELECT d.id as dist_id,d.company as dist_name,t.source_id,t.source_gst_no as dist_gst_no,SUM(tl.payable_amt) as commission,d.pan_number "
                    . "FROM tax_invoices t "
                    . "JOIN tax_invoices_logs tl "
                    . "ON (t.id = tl.invoice_id and t.month = tl.month and t.year = tl.year) "
                    . "JOIN distributors d "
                    . "ON (t.source_id = d.user_id) "
                    . "WHERE t.source_group_id = '5' "
                    . "AND t.target_group_id = '0' "
                    . "AND t.month = '$month' "
                    . "AND t.year = '$year' "
                    . "GROUP BY d.id "
                    . "ORDER BY d.id";

            $commission = $this->Slaves->query($get_dist_commission);

            foreach($commission as $data)
            {
                $response[$data['t']['source_id']]['dist_id'] = $data['d']['dist_id'];
                $response[$data['t']['source_id']]['dist_name'] = $data['d']['dist_name'];
                $response[$data['t']['source_id']]['commission'] = $data[0]['commission'];
                $response[$data['t']['source_id']]['dist_gst_no'] = (array_key_exists($data['t']['source_id'], $distributor_gst_nos))?$distributor_gst_nos[$data['t']['source_id']]:$data['t']['dist_gst_no'];
                $response[$data['t']['source_id']]['pan_number'] = (array_key_exists($data['t']['source_id'], $distributor_pan_nos))?$distributor_pan_nos[$data['t']['source_id']]:$data['d']['pan_number'];
            }

            $get_incentives = "SELECT dl.user_id,SUM(dl.amount-dl.txn_reverse_amt) as incentive,d.id as dist_id,d.company as dist_name,d.pan_number,d.gst_no as dist_gst_no " //source_id is userid
                        . "FROM users_nontxn_logs dl "
                        . "JOIN distributors d ON (dl.user_id = d.user_id) "
                        . "WHERE dl.type=".REFUND." "      
                        . "AND d.id NOT IN (".SAAS_DISTS.") "
                        . "AND dl.date >= '$from_date' "
                        . "AND dl.date <= '$to_date' "
                        . "GROUP BY dl.user_id";

            $incentives = $this->Slaves->query($get_incentives);

            foreach ($incentives as $data)
            {
                $response[$data['dl']['user_id']]['dist_id'] = $data['d']['dist_id'];
                $response[$data['dl']['user_id']]['dist_name'] = $data['d']['dist_name'];
                $response[$data['dl']['user_id']]['incentive'] = $data[0]['incentive'];
                $response[$data['dl']['user_id']]['dist_gst_no'] = (array_key_exists($data['dl']['user_id'], $distributor_gst_nos))?$distributor_gst_nos[$data['dl']['user_id']]:$data['d']['dist_gst_no'];
                $response[$data['dl']['user_id']]['pan_number'] = (array_key_exists($data['dl']['user_id'], $distributor_pan_nos))?$distributor_pan_nos[$data['dl']['user_id']]:$data['d']['pan_number'];
            }

            $get_tds_data = "SELECT d.id as dist_id,dl.user_id,d.company as dist_name,d.pan_number,d.gst_no as dist_gst_no,SUM(dl.tds) as tds_amt "
                    . "FROM users_nontxn_logs dl "
                    . "JOIN distributors d ON (dl.user_id = d.user_id) "
                    . "WHERE dl.date >= '$from_date' "
                    . "AND dl.date <= '$to_date' "
                    . "GROUP BY d.id "
                    . "ORDER BY d.id";

            $tds = $this->Slaves->query($get_tds_data);

            foreach ($tds as $data) {
                $response[$data['dl']['user_id']]['dist_id'] = $data['d']['dist_id'];
                $response[$data['dl']['user_id']]['dist_name'] = $data['d']['dist_name'];
                $response[$data['dl']['user_id']]['tds_amt'] = $data[0]['tds_amt'];
                $response[$data['dl']['user_id']]['dist_gst_no'] = (array_key_exists($data['dl']['user_id'], $distributor_gst_nos))?$distributor_gst_nos[$data['dl']['user_id']]:$data['d']['dist_gst_no'];
                $response[$data['dl']['user_id']]['pan_number'] = (array_key_exists($data['dl']['user_id'], $distributor_pan_nos))?$distributor_pan_nos[$data['dl']['user_id']]:$data['d']['pan_number'];
            }

            if($page == 'download')
            {
                $this->formatTDSReport($response,$month,$year);
            }

            $this->set('month',$month);
            $this->set('year',$year);
            $this->set('page',$page);
            $this->set('tds_data',$response);
            $this->render('get_tds_report');
        }

        function formatTDSReport($tds_data,$month,$year)
        {
            $this->autoRender = FALSE;
            App::import('Helper','csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();

            if(!empty($tds_data))
            {
                $i = 1;
                $line = array('Sr No.','Distributor Id','Distributor Name','Pan No','GST No','Commission','Incentive','TDS');
                $csv->addRow($line);
                $total_commission = 0;
                $total_tds = 0;
                foreach ($tds_data as $dist_id=>$data)
                {
                    $incentive = (isset($data['incentive']) && !empty($data['incentive']))?$data['incentive']:0;
                    $commission = (isset($data['commission']) && !empty($data['commission']))?($data['commission'] - $incentive):0;
                    $tds_amt = (isset($data['tds_amt']) && !empty($data['tds_amt']))?$data['tds_amt']:0;
                    $total_incentive += $incentive;
                    $total_commission += $commission;
                    $total_tds += $tds_amt;

                    $line = array($i,$data['dist_id'],$data['dist_name'],!empty($data['pan_number'])?$data['pan_number']:"-",!empty($data['dist_gst_no'])?$data['dist_gst_no']:"-",round($commission,2),round($incentive,2),round($tds_amt,2));
                    $csv->addRow($line);
                    $i++;
                }
                $line = array('Total','','','','',round($total_commission,2),round($total_incentive,2),round($total_tds,2));
                $csv->addRow($line);

                echo $csv->render("tds_report_".$year.$month.".csv");
            }
            exit;
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

             if($_SESSION['Auth']['User']['group_id']==MASTER_DISTRIBUTOR){
                 $agent_name = $_REQUEST['rm_list'];

             }
             else
             {
                    $sql = "SELECT name FROM rm where id= ".$this->Session->read('Auth.id');
                    $rm = $this->Slaves->query($sql);
                    $agent_name = $rm[0]['rm']['name'];
             }

            if(!empty($agent_name)){
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
                        $this->Shop->addLeadsIntoZoho($create_lead);

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
            }
            else
            {
                $notif = "Please select RM";
                $error = true;
            }



            $this->set('notif', $notif);
            $this->set('error', $error);
            $this->set('lead_source_mapping', $lead_source_mapping);
        }
        $rmList = array();
            if($_SESSION['Auth']['User']['group_id']==MASTER_DISTRIBUTOR)
            {
                $rmList = $this->Shop->getRmList($this->Session->read('Auth.id'));
            }
            $this->set('rmlist', $rmList);
    }


        function distHomePage(){
            $this->render('dist_home_page');
        }


        /**
         * action that finds the nearby retailers for a logged in distributor
         * @return json data of retailers
         */
        function getNearbyRetailers() {
        	$userId = $this->Session->read('Auth.User.id');
        	$latLongQuery = "select longitude, latitude from user_profile where user_id='$userId' and latitude != 0 and longitude !=0 order by updated desc limit 1";
            $latLongQueryResponse = $this->Slaves->query($latLongQuery);
            $latitude = $latLongQueryResponse[0]['user_profile']['latitude'];
            $longitude = $latLongQueryResponse[0]['user_profile']['longitude'];
            $lat = deg2rad ( floatval ($latitude) );
            $lng = deg2rad ( floatval ( $longitude) );
            $updatedDate = date('Y-m-d', strtotime('-30 days'));
            $retailerSelectQuery = "select * from ( "
						."Select retailers.shopname, retailers.name, retailers.mobile, rl.latitude, rl.longitude , retailers.user_id,retailers.address,retailers.pin,locator_area.name as area_name,locator_city.name as city_name,locator_state.name as state_name, "
                        ."acos(sin($lat)*sin(radians(rl.latitude)) + cos($lat)*cos(radians(rl.latitude))*cos(radians(rl.longitude)- $lng)) * 6371 As D "
                    	."From "
                        ."retailers_location as rl "
                        ."LEFT JOIN retailers ON ( retailers.id = rl.retailer_id ) "
                        ."LEFT JOIN locator_area ON ( locator_area.id = retailers.area_id ) "
                        ."LEFT JOIN locator_city ON ( locator_area.city_id = locator_city.id ) "
                        ."LEFT JOIN locator_state ON ( locator_city.state_id = locator_state.id ) "
                    	."Where "
                        ."rl.latitude != '' AND rl.longitude != '' AND rl.longitude != 0 AND rl.longitude != 0 AND retailers.parent_id=1 AND rl.updated >= '".$updatedDate."'"
						." ) as v WHERE v.D < 5 group by v.user_id";
            $retailerList = $this->Slaves->query($retailerSelectQuery);

            /** IMP DATA ADDED : START**/
            $ret_mobiles = array_map(function($element){
                return $element['v']['mobile'];
            },$retailerList);

            $imp_data = $this->Shop->getUserLabelData($ret_mobiles,2,1);
            /** IMP DATA ADDED : END**/

            $nearbyRetailerArray = array();
            foreach($retailerList as $retailer) {
            	// array_push($nearbyRetailerArray, array('shop_name' => $retailer[v]['shopname'],'retailer_name' => $retailer[v]['name'], 'retailer_mobile' => $retailer[v]['mobile'], 'retailer_address' => $retailer[v]['address']));
            	array_push($nearbyRetailerArray, array('shop_name' => $imp_data[$retailer[v]['mobile']]['imp']['shop_est_name'],'retailer_name' => $imp_data[$retailer[v]['mobile']]['imp']['name'], 'retailer_mobile' => $retailer[v]['mobile'], 'retailer_address' => $imp_data[$retailer[v]['mobile']]['imp']['address']));
            }
//             $res = array('status'=>'success','data'=> $nearbyRetailerArray);
//             return(json_encode($res));
            $this->set('nearbyRetailers', $nearbyRetailerArray);
            $this->render('nearby_retailers');
        }


       /**
        * action that finds nearby distributors for a logged in retailer
        * @return json data of retailers
        */
        function getNearbyDistributors() {
        	$userId = $this->Session->read('Auth.User.id');
        	$latLongQuery = "select longitude, latitude from user_profile where user_id='$userId' and latitude != 0 and longitude !=0 order by updated desc limit 1";
        	$latLongQueryResponse = $this->Slaves->query($latLongQuery);
        	$latitude = $latLongQueryResponse[0]['user_profile']['latitude'];
        	$longitude = $latLongQueryResponse[0]['user_profile']['longitude'];
        	$lat = deg2rad(floatval($latitude));
        	$lng = deg2rad(floatval($longitude));
        	$distributorSelectQuery = "select * from ( "
        			."Select name, mobile, address, map_lat, map_long, user_id, "
        			."acos(sin($lat)*sin(radians(dis.map_lat)) + cos($lat)*cos(radians(dis.map_lat))*cos(radians(dis.map_long)- $lng)) * 6371 As D "
        			."From "
        		    ."distributors as dis "
        			."Where "
        			."dis.map_lat != '' AND dis.map_long != '' AND dis.map_lat != 0 AND dis.map_long != 0 AND dis.map_lat IS NOT NULL AND dis.map_long IS NOT NULL "
        		    ." ) as v WHERE v.D < 5 group by v.user_id";
           $distributorList = $this->Slaves->query($distributorSelectQuery);
           $nearbyDistributorArray = array();
            foreach($distributorList as $distributor) {
        		array_push($nearbyDistributorArray, array('distributor_name' => $distributor[v]['name'], 'distributor_mobile' => $distributor[v]['mobile'], 'distributor_address' => $distributor[v]['address']));
             }
             $res = array('status'=>'success','data'=> $nearbyDistributorArray);
             echo(json_encode($res));
             $this->autoRender = false;

        }


        function rmGraph(){

            $rm_sd_cond = "distributors.rm_id = ".$this->Session->read('Auth.id');
            if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR){
                $rm_sd_cond = ' distributors.parent_id IN ('.$this->Session->read('Auth.id').')    ';
                $rms = $this->Shop->getRmList();
                $this->set('rms',$rms);
            }

            $distributors = $this->Shop->getAllDistributors('user_id',array('user_id','company'),'company',$rm_sd_cond);

            if(!empty($this->params['url']) && array_key_exists('from',$this->params['url']) && array_key_exists('to',$this->params['url']) &&  array_key_exists('label',$this->params['url']) &&  array_key_exists('distributor',$this->params['url'])){
                    $date_from = $this->params['url']['from'];
                    $date_to = $this->params['url']['to'];
                    $date1 = strtotime($date_from);
                    $date2 = strtotime($date_to);
                    $num =round(($date2-$date1)/(24*60*60))+1;


                    if(empty($date_from))
                    {
                                        $this->set('validation_error','Please select From date');
                    }
                    elseif(empty($date_to))
                    {
                         $this->set('validation_error','Please select To date');
                    }
                   else if(strtotime($date_from)> strtotime($date_to))
                   {
                       $this->set('validation_error','From date should be less than To date');
                        $flag = false;
                    }
                    else if($num>31)
                    {
                        $this->set('validation_error','Please select date range within 1 month only');
                        $flag = false;
                    }
                    elseif(empty($this->params['url']['label']))
                    {
                         $this->set('validation_error','Please select service from list');
                        $flag = false;
                    }
                    else
                    {
                        $label = $this->params['url']['label'];
                        $servicelist = $this->Shop->getServiceByParentId($label);
                        $service = implode(",",$servicelist);
                        $serviceCheck = '';
                        if(!empty($this->params['url']['label'])){
                            $serviceCheck = ' AND service_id IN ( '.$service.' ) ';
                        }
                        $distributorCheck = '';
                        if(!empty($this->params['url']['distributor'])){
                            $distributorCheck = ' AND dist_user_id = '.$this->params['url']['distributor'];
                        }
                        if(!empty($this->params['url']['rm'])){
                            $distributorCheck = ' AND distributors.rm_id = '.$this->params['url']['rm'];
                        }
                        $rm_sd_cond .=$serviceCheck;
                        $rm_sd_cond .=$distributorCheck;
                       $data =  $this->Shop->getRmGraphData($rm_sd_cond,$date_from,$date_to);
//                        $sql="SELECT ROUND(SUM(amount)) as sale,trim(distributors.id) as rid,date,"
//                                . "count(distinct retailer_earning_logs.ret_user_id) as transaction_retailer "
//                                . "FROM `retailer_earning_logs`,distributors,users "
//                                . "WHERE retailer_earning_logs.dist_user_id=distributors.user_id AND "
//                                . "distributors.user_id=users.id AND $rm_sd_cond group by distributors.id,date";
                    }
            }
            $graphData= array();
            $graphRetailerData= array();
            $graphAvgSaleData= array();
            $graphNoofTxnData= array();

            if(!empty($data['sale'])){
            $graphData = array(
                                'labels' => array(
                                                array('string' => 'Sample'),
                                                array('number' => 'Sale')
                                ),
                                'data' => $data['sale'],
                                'title' => 'Sale',
                                'type' => 'line',
                                'width' => '900',
                                'height' => '400'
                );
            }

            if(!empty($data['trans_retailers'])){
            $graphRetailerData = array(
                                'labels' => array(
                                                array('string' => 'Sample'),
                                                array('number' => 'Transacting Retailer'),

                                ),
                                'data' => $data['trans_retailers'],
                                'title' => 'Transaction Retailer',
                                'type' => 'line',
                                'width' => '900',
                                'height' => '400'
                );
            }
            if(!empty($data['avg_sale'])){
            $graphAvgSaleData = array(
                                'labels' => array(
                                                array('string' => 'Sample'),
                                                array('number' => 'Avg Sale per Retailer'),

                                ),
                                'data' => $data['avg_sale'],
                                'title' => 'Avg Sale',
                                'type' => 'line',
                                'width' => '900',
                                'height' => '400'
                );
            }
            if(!empty($data['no_of_transaction'])){
            $graphNoofTxnData = array(
                                'labels' => array(
                                                array('string' => 'Sample'),
                                                array('number' => 'No. of transaction'),

                                ),
                                'data' => $data['no_of_transaction'],
                                'title' => 'No. of transaction',
                                'type' => 'line',
                                'width' => '900',
                                'height' => '400'
                );
            }
            $norecord = 1;
            if(empty($graphAvgSaleData) && empty($graphRetailerData) && empty($graphData) && empty($graphNoofTxnData)){
                $norecord=0;
            }
            $services = $this->Shop->getAllServices();
            $this->set('selected_label',$service );
            $this->set('selected_rm',$this->params['url']['rm']);
            $this->set('selected_distributor',array_key_exists('distributor',$this->params['url']) ? trim($this->params['url']['distributor']): null );
            $this->set('labels',$services);
            $this->set('datas',$graphData);
            $this->set('norecord',$norecord);
            $this->set('trans_retailer',$graphRetailerData);
            $this->set('avg_sale',$graphAvgSaleData);
            $this->set('no_of_transaction',$graphNoofTxnData);
            $this->set('from',array_key_exists('from',$this->params['url']) && !empty($this->params['url']['from'])  ?  trim($this->params['url']['from']) : null);
            $this->set('to',array_key_exists('to',$this->params['url']) && !empty($this->params['url']['to']) ?  trim($this->params['url']['to']) : null);
            $this->set('distributors',$distributors);

        }




       function rmComapreTool(){

           $result = array(); $overviewData = array(); $cityArr = array();  $rmArr = array(); $stateArr = array();$distributorArr = array();

           $dist_sql = "SELECT rm_id,id,user_id,active_flag,company FROM distributors";
           $dist_data = $this->Slaves->query($dist_sql);

            /** IMP DATA ADDED : START**/
            $dist_ids = array_map(function($element){
                return $element['distributors']['id'];
            },$dist_data);
            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
            /** IMP DATA ADDED : END**/

           $dist_array = array();
           $dist_uid_array = array();
           $dist_data_rm = array();
           foreach($dist_data as $dist_d){
               $dist_d['distributors']['company'] = $imp_data[$dist_d['distributors']['id']]['imp']['shop_est_name'];
               $dist_id = $dist_d['distributors']['id'];
               $rm_id = $dist_d['distributors']['rm_id'];
               $user_id = $dist_d['distributors']['user_id'];
               $dist_array[$dist_id] = $dist_d['distributors'];
               $dist_uid_array[$user_id] = $dist_d['distributors'];
               $dist_data_rm[$rm_id][] = $user_id;
           }

           $rm_sql = "SELECT name,id FROM rm";
           $rm_data = $this->Slaves->query($rm_sql);
           $rm_array = array();
           foreach($rm_data as $rm_d){
               $rmid = $rm_d['rm']['id'];
               $rm_array[$rmid] = $rm_d['rm'];
           }

           $condition = '1 ';
           if($this->Session->read('Auth.User.group_id') != MASTER_DISTRIBUTOR || isset($this->params['url']['RM'])){
               $rm_id = (isset($this->params['url']['RM'])) ? $this->params['url']['RM'] : $this->Session->read('Auth.id');
               $dists = $dist_data_rm[$rm_id];
               $condition = ' retailer_earning_logs.dist_user_id IN ('.implode($dists,",").')    ';
           }
           //$condition = "distributors.rm_id = ".$this->Session->read('Auth.id');
            /*if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR){
                $condition = ' distributors.parent_id IN ('.$this->Session->read('Auth.id').')    ';
            }*/
             if(!empty($this->params['url']) && (array_key_exists('from',$this->params['url']) || array_key_exists('from_month',$this->params['url']) ) && (array_key_exists('to',$this->params['url']) || array_key_exists('to_month',$this->params['url']) )&&  array_key_exists('label',$this->params['url']) ){
               $from_dateCond = '';
                 if($this->params['url']['compare']=='month' && array_key_exists('from_month',$this->params['url']) && !empty($this->params['url']['from_month'])){
                    $from = explode('-',$this->params['url']['from_month']);

                    $to =  explode('-',$this->params['url']['to_month']);
                    $from_dateCond = " AND MONTH(date)= '{$from[0] }' AND YEAR(date)='{$from[1] }'";
                    $to_dateCond = " AND MONTH(date)= '{$to[0] }' AND YEAR(date)='{$to[1] }'";

                    $this->params['url']['from']='';
                    $this->params['url']['to']='';
                    $this->params['url']['from_week']='';
                    $this->params['url']['to_week']='';

               }
               elseif($this->params['url']['compare']=='week')
               {
                       $from_date  = new DateTime($this->params['url']['from_week']);
                        $month = $from_date->format('m');
                        $year = $from_date->format('Y');

                      $week1 = $this->params['url']['week1'];
                      $week2 = $this->params['url']['week2'];
                      $week1day1 = (($week1*7)-6);
                      $week1day2 = $week1*7> cal_days_in_month(null,$month,$year) ? cal_days_in_month(null,$month,$year) : $week1*7;
                      $week1StartDate = new DateTime($year.'-'.$month.'-'.$week1day1);
                      $week1EndDate = new DateTime($year.'-'.$month.'-'.$week1day2);

                       $to_date  = new DateTime($this->params['url']['to_week']);
                        $month = $to_date->format('m');
                        $year = $to_date->format('Y');

                      $week2day1 = (($week2*7)-6);
                      $week2day2 = $week2*7> cal_days_in_month(null,$month,$year) ? cal_days_in_month(null,$month,$year) : $week2*7;

                      $week2StartDate = new DateTime($year.'-'.$month.'-'.$week2day1);
                      $week2EndDate = new DateTime($year.'-'.$month.'-'.$week2day2);

                        $from_dateCond = " AND date>= '{$week1StartDate->format('Y-m-d') }' ";
                        $from_dateCond .= " AND date<= '{$week1EndDate->format('Y-m-d')}' ";

                        $to_dateCond = " AND date>= '{$week2StartDate->format('Y-m-d')}' ";
                        $to_dateCond .= " AND date<= '{$week2EndDate->format('Y-m-d')}' ";

                        $this->params['url']['from_month']='';
                        $this->params['url']['to_month']='';
                        $this->params['url']['from']='';
                        $this->params['url']['to']='';


               }
               else
               {
                    $from = $this->params['url']['from'];
                    $to = $this->params['url']['to'];
                    $from_dateCond = " AND date= '{$from }' ";
                    $to_dateCond = " AND date= '{$to }' ";
                    $this->params['url']['from_month']='';
                    $this->params['url']['to_month']='';
                    $this->params['url']['from_week']='';
                    $this->params['url']['to_week']='';


               }

                 $servicelist = $this->Shop->getServiceByParentId($this->params['url']['label']);

                    $service = implode(",",$servicelist);

                $sql1 = "SELECT round(SUM(amount)) as sell,count(distinct retailer_earning_logs.ret_user_id) as transaction_retailer  "
                                       . "FROM `retailer_earning_logs` "
                                       . "WHERE $condition AND retailer_earning_logs.service_id IN ($service) $from_dateCond";

                $sql11 = "SELECT round(SUM(amount)) as sell,count(distinct retailer_earning_logs.ret_user_id) as transaction_retailer  "
                                               . "FROM `retailer_earning_logs` "
                                                       . "WHERE $condition AND retailer_earning_logs.service_id IN ($service) $from_dateCond  group by date";

                $overview1 = $this->Slaves->query($sql1);
                $overview11 = $this->Slaves->query($sql11);
                $overviewData[] = $overview1[0][0];
                $overviewData[0]['avg'] = 0;
                $i = 0;
                foreach ($overview11 as $val){
                    $i++;
                    $overviewData[0]['avg'] += round($val[0]['sell'] / $val[0]['transaction_retailer']);
                }
                $overviewData[0]['avg'] = round($overviewData[0]['avg']/$i);
                $overviewData[0]['sell'] = round($overviewData[0]['sell']/$i);
                 $sql2 = "SELECT round(SUM(amount)) as sell,count(distinct retailer_earning_logs.ret_user_id) as transaction_retailer  "

                                    . "FROM `retailer_earning_logs` "
                                    . "WHERE $condition AND retailer_earning_logs.service_id IN ($service) $to_dateCond";

                 $sql22 = "SELECT round(SUM(amount)) as sell,count(distinct retailer_earning_logs.ret_user_id) as transaction_retailer  "
                                            . "FROM `retailer_earning_logs` "
                                                    . "WHERE $condition AND retailer_earning_logs.service_id IN ($service) $to_dateCond group by date";

                 $overview2 = $this->Slaves->query($sql2);
                 $overview22 = $this->Slaves->query($sql22);
                 $overviewData[] = $overview2[0][0];
                 $overviewData[1]['avg'] = 0;
                 $j = 0;
                 foreach ($overview22 as $val){
                     $j++;
                     $overviewData[1]['avg'] += round($val[0]['sell'] / $val[0]['transaction_retailer']);
                 }
                 $overviewData[1]['avg'] = round($overviewData[1]['avg']/$j);
                 $overviewData[1]['sell'] = round($overviewData[1]['sell']/$j);
                 $basicComp = $this->params['url']['basic_compare'];
                 if(array_key_exists('state', $this->params['url'])){

                     //state wise
                     $condition .=' AND locator_state.id="'.$this->params['url']['state'].'"';

                 }
                 if(array_key_exists('city', $this->params['url'])){
                     $condition .=' AND locator_city.id="'.$this->params['url']['city'].'"';
                 }
                 /*if(array_key_exists('RM', $this->params['url'])){
                     $condition .=' AND distributors.rm_id='.$this->params['url']['RM'];
                 }*/
                 if(array_key_exists('distributor', $this->params['url'])){
                     $dist_id = $this->params['url']['distributor'];
                     $dist_user_id = $dist_array[$dist_id]['user_id'];
                     $condition .=' AND retailer_earning_logs.dist_user_id ='.$dist_user_id;
                 }

                   $sql1 = "SELECT retailer_earning_logs.dist_user_id,locator_city.id as cityId,locator_city.name as city,locator_state.id as state_id,locator_state.name as state,round(SUM(amount)/$i) as sale,count(distinct retailer_earning_logs.ret_user_id) as transaction_retailer  "
                                       . "FROM `retailer_earning_logs` left join retailers_location on (retailer_earning_logs.ret_user_id=retailers_location.user_id) inner join locator_area on (retailers_location.area_id=locator_area.id) inner join locator_city on (locator_area.city_id= locator_city.id) inner join locator_state on (locator_state.id=locator_city.state_id) "
                                               . "WHERE $condition AND retailer_earning_logs.dist_user_id NOT IN (".DISTS_UID.") AND retailer_earning_logs.service_id IN ($service) $from_dateCond "
                                       . "group by retailer_earning_logs.ret_user_id having sum(amount)>0 ";


                                       $sql2 = "SELECT retailer_earning_logs.dist_user_id,locator_city.id as cityId,locator_city.name as city,locator_state.id as state_id,locator_state.name as state,round(SUM(amount)/$j) as sale,count(distinct retailer_earning_logs.ret_user_id) as transaction_retailer  "
                                       . "FROM `retailer_earning_logs` left join retailers_location on (retailer_earning_logs.ret_user_id=retailers_location.user_id) inner join locator_area on (retailers_location.area_id=locator_area.id) inner join locator_city on (locator_area.city_id= locator_city.id) inner join locator_state on (locator_state.id=locator_city.state_id) "
                                               . "WHERE $condition AND retailer_earning_logs.dist_user_id NOT IN (".DISTS_UID.") AND retailer_earning_logs.service_id IN ($service) $to_dateCond "
                                               . "group by retailer_earning_logs.ret_user_id having sum(amount)>0 ";


                $compar1 = $this->Slaves->query($sql1);

                $compar2 = $this->Slaves->query($sql2);
                $result = array(); $state = array();  $rm = array(); $distributor = array();  $city = array();

                foreach($compar1 as $val)
                {
                    if(!empty($val['locator_state']['state'])){
                        if(!isset($state[$val['locator_state']['state']]['comp1']))$state[$val['locator_state']['state']]['comp1'] = 0;
                      $state[$val['locator_state']['state']]['comp1']  += $val['0']['sale'];
                      //$state[$val['locator_state']['state']]['id']  = $val['distributors']['id'];
                      $state[$val['locator_state']['state']]['state_id']  = $val['locator_state']['state_id'];
                    }
                }

                foreach($compar2 as $val)
                {
                    if(!empty($val['locator_state']['state_id'])){
                        if(!isset($state[$val['locator_state']['state']]['comp2']))$state[$val['locator_state']['state']]['comp2'] = 0;
                        $state[$val['locator_state']['state']]['comp2']  += $val['0']['sale'];
                        $state[$val['locator_state']['state']]['state_id']  = $val['locator_state']['state_id'];
                    }

                }
                $stateArr = array();
               foreach($state as $key=>$val){
                   $comp1 = (isset($val['comp1'])) ? round($val['comp1']) : 0;
                   $comp2 = (isset($val['comp2'])) ? round($val['comp2']) : 0;

                    $stateArr[$key]['comp2'] = $comp2;
                    $stateArr[$key]['comp1'] =$comp1;
                    $stateArr[$key]['state_id'] = (isset($val['state'])) ? $val['state'] : '';
                    $stateArr[$key]['difference'] = $comp2  - $comp1;
               }

               foreach($compar1 as $val)
                {
                    $dist_user_id = $val['retailer_earning_logs']['dist_user_id'];
                    $rmid = $dist_uid_array[$dist_user_id]['rm_id'];
                    $rm_name = (isset($rm_array[$rmid])) ? $rm_array[$rmid]['name'] : '';

                    if(!empty($rm_name)){
                        if(!isset($rm[$rm_name]['comp1']))$rm[$rm_name]['comp1']=0;
                        $rm[$rm_name]['comp1']  += $val['0']['sale'];
                        $rm[$rm_name]['id']  = $rmid;
                    }
                }

                foreach($compar2 as $val)
                {
                    $dist_user_id = $val['retailer_earning_logs']['dist_user_id'];
                    $rmid = $dist_uid_array[$dist_user_id]['rm_id'];
                    $rm_name = (isset($rm_array[$rmid])) ? $rm_array[$rmid]['name'] : '';

                    if(!empty($rm_name)){
                        if(!isset($rm[$rm_name]['comp2']))$rm[$rm_name]['comp2']=0;
                        $rm[$rm_name]['comp2']  += $val['0']['sale'];
                        $rm[$rm_name]['id']  = $rmid;
                    }
                }

                $rmArr = array();
               foreach($rm as $key=>$val){
                   $comp1 = (isset($val['comp1'])) ? round($val['comp1']) : 0;
                   $comp2 = (isset($val['comp2'])) ? round($val['comp2']) : 0;

                   $rmArr[$key]['comp2'] = $comp2;
                   $rmArr[$key]['comp1'] = $comp1;
                   $rmArr[$key]['id'] = $val['id'];
                   $rmArr[$key]['difference'] = $comp2  -  $comp1;
               }


               foreach($compar1 as $val)
                {
                    $dist_user_id = $val['retailer_earning_logs']['dist_user_id'];
                    $dist_id = $dist_uid_array[$dist_user_id]['id'];
                    $dist_comp = $dist_uid_array[$dist_user_id]['company'];
                    $rmid = $dist_uid_array[$dist_user_id]['rm_id'];
                    $rm_name = (isset($rm_array[$rmid])) ? $rm_array[$rmid]['name'] : '';

                    if(!empty($dist_id)){
                        if(!isset($distributor[$dist_comp]['comp1']))$distributor[$dist_comp]['comp1'] = 0;
                        $distributor[$dist_comp]['comp1']  += $val['0']['sale'];
                        $distributor[$dist_comp]['rm']  = $rm_name;
                        $distributor[$dist_comp]['id']  = $dist_id;
                        $distributor[$dist_comp]['state']  = $val['locator_state']['state'];
                    }
                }

                foreach($compar2 as $val)
                {
                    $dist_user_id = $val['retailer_earning_logs']['dist_user_id'];
                    $dist_id = $dist_uid_array[$dist_user_id]['id'];
                    $dist_comp = $dist_uid_array[$dist_user_id]['company'];
                    $rmid = $dist_uid_array[$dist_user_id]['rm_id'];
                    $rm_name = (isset($rm_array[$rmid])) ? $rm_array[$rmid]['name'] : '';

                    if(!empty($dist_id)){
                        if(!isset($distributor[$dist_comp]['comp2']))$distributor[$dist_comp]['comp2'] = 0;

                        $distributor[$dist_comp]['comp2']  += $val['0']['sale'];
                        $distributor[$dist_comp]['rm']  = $rm_name;
                        $distributor[$dist_comp]['id']  = $dist_id;
                        $distributor[$dist_comp]['state']  = $val['locator_state']['state'];
                    }
                }

                $distributorArr = array();

               foreach($distributor as $key=>$val){
                   $comp1 = (isset($val['comp1'])) ? round($val['comp1']) : 0;
                   $comp2 = (isset($val['comp2'])) ? round($val['comp2']) : 0;

                   $distributorArr[$key]['comp1'] = $comp1;
                   $distributorArr[$key]['comp2'] = $comp2;
                   $distributorArr[$key]['id'] = $val['id'];
                   $distributorArr[$key]['rm'] = (isset($val['rm'])) ? $val['rm'] : '';
                   $distributorArr[$key]['state'] = (isset($val['state'])) ? $val['state'] : '';
                   $distributorArr[$key]['difference'] = $comp2  -  $comp1;
               }


               foreach($compar1 as $val)
                {
                    if(!empty($val['locator_city']['city'])){
                      if(!isset($city[$val['locator_city']['city']]['comp1']))$city[$val['locator_city']['city']]['comp1'] = 0;
                      $city[$val['locator_city']['city']]['comp1']  += $val['0']['sale'];
                      $city[$val['locator_city']['city']]['state']  = $val['locator_state']['state'];
                      $city[$val['locator_city']['city']]['id']  = $val['locator_city']['cityId'];
                    }
                }

                foreach($compar2 as $val)
                {
                    if(!empty($val['locator_city']['cityId'])){
                        if(!isset($city[$val['locator_city']['city']]['comp2']))$city[$val['locator_city']['city']]['comp2'] = 0;

                        $city[$val['locator_city']['city']]['comp2']  += $val['0']['sale'];
                        $city[$val['locator_city']['city']]['id']  = $val['locator_city']['cityId'];
                    }
                }
                $cityArr = array();
                $cityKey = array();
               foreach($city as $key=>$val){
                   $comp1 = (isset($val['comp1'])) ? round($val['comp1']) : 0;
                   $comp2 = (isset($val['comp2'])) ? round($val['comp2']) : 0;

                   $cityArr[$key]['id'] = $val['id'];
                   $cityArr[$key]['state'] = (isset($val['state'])) ? $val['state'] : '';
                   $cityArr[$key]['comp1'] = $comp1;
                   $cityArr[$key]['comp2'] = $comp2;
                   $cityArr[$key]['difference'] = $comp2  -  $comp1;

               }

             }

            // echo "<pre>"; print_r($distributorArr); die;
            $services = $this->Shop->getAllServices();
            $this->set('selected_label',array_key_exists('label',$this->params['url']) ? trim($this->params['url']['label']): null );
            $date_from = $this->params['url']['from'];
            $this->set('labels',$services);
            $this->set('basicLabel',$basicComp);
            $this->set('overviewdata',$overviewData);
            $this->set('data',(!empty($stateArr) && !empty($rmArr) && !empty($cityArr) && !empty($distributorArr) ? 1 :0 ) );
            $this->set('state',$stateArr);
            $this->set('rm',$rmArr);
            $this->set('city',$cityArr);

            $this->set('distributor',$distributorArr);

            $this->set('compareBy',$this->params['url']['compare']);
            $this->set('to',array_key_exists('to',$this->params['url']) && !empty($this->params['url']['to']) ?  trim($this->params['url']['to']) : null);
            $this->set('from',array_key_exists('from',$this->params['url']) && !empty($this->params['url']['from'])  ?  trim($this->params['url']['from']) : null);
            $this->set('to',array_key_exists('to',$this->params['url']) && !empty($this->params['url']['to']) ?  trim($this->params['url']['to']) : null);
            $this->set('from_month',array_key_exists('from_month',$this->params['url']) && !empty($this->params['url']['from_month'])  ?  trim($this->params['url']['from_month']) : null);
            $this->set('to_month',array_key_exists('to_month',$this->params['url']) && !empty($this->params['url']['to_month']) ?  trim($this->params['url']['to_month']) : null);

            $this->set('from_week',array_key_exists('from_week',$this->params['url']) && !empty($this->params['url']['from_week'])  ?  trim($this->params['url']['from_week']) : null);
            $this->set('to_week',array_key_exists('to_week',$this->params['url']) && !empty($this->params['url']['to_week']) ?  trim($this->params['url']['to_week']) : null);
            $this->set('week1',array_key_exists('week1',$this->params['url']) && !empty($this->params['url']['week1'])  ?  trim($this->params['url']['week1']) : null);
            $this->set('week2',array_key_exists('week2',$this->params['url']) && !empty($this->params['url']['week2']) ?  trim($this->params['url']['week2']) : null);

            $this->set('selected_label',array_key_exists('label',$this->params['url']) ? trim($this->params['url']['label']): null );
            $this->render('comapretool');
        }

        function debitCreditReport()
        {
            ini_set("memory_limit","2048M");
            
            $params = $this->params['form'];
            $from_date = $params['from_date'];
            $to_date = $params['to_date'];
            $page = !empty($params['download_txns']) ? $params['download_txns'] : "";
            $txn_types = array('COMMISSION DISTRIBUTOR'=>'6','INCENTIVE'=>'19','RENTAL'=>'20','KITCHARGE'=>'35','SECURITY DEPOSIT'=>'36','ONE TIME CHARGE'=>'37');
            $type = !empty($params['txn_type'])?$params['txn_type']: implode(',',array_values($txn_types));

            Configure::load('product_config');
            // $services = Configure::read('services');
            $service_temp = $this->Serviceintegration->getServiceDetails();
            $services_temp = json_decode($service_temp,true);

            $services = array();
            foreach ($services_temp as $service_id => $service) {
                $services[$service_id] = $service['name'];
            }

            $service_cond = '';
            if( isset($params['selected_service']) && !empty($params['selected_service']) && array_key_exists($params['selected_service'],$services) ){
                $service_cond = " AND st.user_id IN (".$params['selected_service'].")";
            }

            $date_diff = strtotime($to_date) - strtotime($from_date);
            $diff = floor($date_diff / (60 * 60 * 24));

            if($diff <= 31)
            {
                $get_txn_data_query = "SELECT * FROM "
                                    . "(SELECT st.*,if(st.source_id = d.user_id or st.source_id = d.id,d.company,if(st.source_id = r.user_id,r.shopname,'')) as user_name,if(st.source_id = d.user_id,d.id,if(st.source_id = r.user_id,r.id,'')) as uid "
                                    . "FROM shop_transactions st "
                                    . "LEFT JOIN distributors d ON (d.user_id = st.source_id OR d.id = st.source_id) "
                                    . "LEFT JOIN retailers r ON (r.user_id = st.source_id) "
                                    . "WHERE st.date >= '$from_date' "
                                    . "AND st.date <= '$to_date' "
                                    . "AND st.type IN ($type) "
                                    . " $service_cond "
                                    . "ORDER BY st.id) as txn_data "
                                    . "UNION "
                                    . "SELECT * FROM "
                                    . "(SELECT st.*,if(st.source_id = d.user_id or st.source_id = d.id,d.company,if(st.source_id = r.user_id,r.shopname,'')) as user_name,if(st.source_id = d.user_id,d.id,if(st.source_id = r.user_id,r.id,'')) as uid "
                                    . "FROM shop_transactions_logs st "
                                    . "LEFT JOIN distributors d ON (d.user_id = st.source_id OR d.id = st.source_id) "
                                    . "LEFT JOIN retailers r ON (r.user_id = st.source_id) "
                                    . "WHERE st.date >= '$from_date' "
                                    . "AND st.date <= '$to_date' "
                                    . "AND st.type IN ($type) "
                                    . " $service_cond "
                                    . "ORDER BY st.id) as txn_data ";

                $txn_data = $this->Slaves->query($get_txn_data_query);

                if($page == 'download')
                {
                    $this->formatDebitCreditReport($txn_data,$services,$txn_types,$from_date,$to_date);
                }
            }
            else
            {
                $this->Session->setFlash("<b>Error</b> :  Date range exceeding 31 days.");
            }
            $this->set('transactions',$txn_data);
            $this->set('params',$params);
            $this->set('txn_types',$txn_types);
            $this->set('services',$services);
            $this->set('page',$page);
            $this->set('selected_service',$params['selected_service']);
        }

        function formatDebitCreditReport($txn_data,$services,$txn_types,$from_date,$to_date)
        {
            $this->autoRender = false;
            App::import('Helper','csv');
            $this->layout = null;
            $this->autoLayout = false;
            $csv = new CsvHelper();

            if(!empty($txn_data))
            {
                $i = 1;
                $headers = array('Sr. No.','Id','User ID','User Name','Service','Type','Dr','Cr','Description','Date','Updated Time');
                $csv->addRow($headers);

                foreach($txn_data as $data)
                {
                    $dr_amt = ($data[0]['source_opening'] < $data[0]['source_closing'])?$data[0]['amount']:'0.00';
                    $cr_amt = ($data[0]['source_opening'] > $data[0]['source_closing'])?$data[0]['amount']:'0.00';
                    $line = array($i,$data[0]['uid'],$data[0]['source_id'],$data[0]['user_name'],$services[$data[0]['user_id']],array_search($data[0]['type'],$txn_types),$dr_amt,$cr_amt,$data[0]['note'],$data[0]['date'],$data[0]['timestamp']);
                    $csv->addRow($line);
                    $i++;
                }

//                echo $csv->render("debit_credit_report.csv");
                echo $csv->render("Transactions_".$from_date."_".$to_date.".csv");
            }
            exit;
        }

        function uploadTdsCertificate()
        {
            $params = $this->params['form'];
            $quarter = $params['quarter'];
            $year = $params['year'];
            $doc_ext = strtolower(pathinfo($_FILES['tds_file']['name'], PATHINFO_EXTENSION));

            if($this->RequestHandler->isPost())
            {
                if($_FILES['tds_file']['size'] > 0)
                {
                    if($doc_ext == 'zip')
                    {
                        $zip = new ZipArchive;
                        $res = $zip->open($params['tds_file']['tmp_name']);
                        if ($res === TRUE)
                        {
                            App::import('vendor', 'S3', array('file' => 'S3.php'));
                            $bucket = tdsbucket;
                            $path = $_FILES['tds_file']['tmp_name'];
                            $s3 = new S3(awsAccessKey, awsSecretKey);
                            $flag = 1;
                            $invalid_files = array();

                            for ($i = 0; $i < $zip->numFiles; $i++)
                            {
                                $doc_name = $zip->getNameIndex($i);
                                $ext = strtolower(pathinfo($doc_name, PATHINFO_EXTENSION));
                                if($ext == 'pdf')
                                {
                                    $fileinfo = pathinfo($doc_name);
                                    copy("zip://".$path."#".$doc_name, "/tmp/".$fileinfo['basename']);
                                    $filename = strtolower(pathinfo($doc_name, PATHINFO_FILENAME))."_".$quarter."_".$year.".".$ext;

                                    $s3_response = $s3->putObjectFile("/tmp/".$fileinfo['basename'], $bucket, $filename, S3::ACL_PUBLIC_READ);

                                    if($s3_response)
                                    {
                                        unlink("/tmp/".$fileinfo['basename']);
                                        $flag = 1;
                                    }
                                    else
                                    {
                                        $flag = 0;
                                    }
                                }
                                else
                                {
                                    $flag = 0;
                                    $invalid_files[] = $doc_name;
                                }
                            }
                            $zip->close();
                            if($flag == 1)
                            {
                                $this->Session->setFlash("<b>Success</b> :  File uploaded successfully!");
                            }
                            elseif($flag == 0 && !empty($invalid_files))
                            {
                              $this->Session->setFlash("<b>Error</b> :  Invaild files : ". implode(',', $invalid_files));
                            }
                            else
                            {
                                $this->Session->setFlash("<b>Error</b> :  Something went wrong. Please try again.");
                            }
                        }
                        else
                        {
                          $this->Session->setFlash("<b>Error</b> :  Something went wrong. Please try again.");
                        }
                    }
                    else
                    {
                        $this->Session->setFlash("<b>Error</b> :  The file you are trying to upload is not a .zip file. Please try again.");
                    }
                }
                else
                {
                    $this->Session->setFlash("<b>Error</b> :  Empty file.");
                }
            }
        }

        function downloadTdsCertificate()
        {
            $params = $this->params['form'];
            $quarter = $params['quarter'];
            $year = $params['year'];
            $id = $this->Session->read('Auth.id');

            if($this->RequestHandler->isPost())
            {
                App::import('vendor', 'S3', array('file' => 'S3.php'));
                $bucket = tdsbucket;
                $filename = $id."_".$quarter."_".$year.".pdf";
                $s3 = new S3(awsAccessKey, awsSecretKey);
                $checkIfFileExists = $s3->getObjectInfo($bucket, $filename ,true);

                if(!empty($checkIfFileExists))
                {
                    $presigned_url = $s3->aws_s3_link(awsAccessKey,awsSecretKey,$bucket,'/'.$filename,time() - strtotime(date('Y-m-d'))+50);
                    header('Location: ' . $presigned_url);
                }
                else
                {
                    $this->Session->setFlash("<b>Error</b> :  Certificate not found.");
                }
            }
            $this->set('params',$params);
        }
        function newsletter($mode)
        {
        	$this->layout = null;
        	if ($mode=='hindi')
        	{
        	$this->render('hindi');
        	}
        	else {
        	$this->render('english');
        	}
        }


        function distTargetReport()
        {
            $year = date('Y');
            $month = date('m');
            if( array_key_exists('year_month',$this->params['url']) ){
                $year_month = explode('-',trim($this->params['url']['year_month']));
                $year = $year_month[0];
                $month = $year_month[1];
            }
            $dist_id = $this->Session->read('Auth.id');

            $schemes = $this->Scheme->getScheme($dist_id,$month,$year,1);

            $target_report = array();

           /* if(!empty($schemes))
            {
                $target_report['target1'] = $recharge_target1 = $schemes[$dist_id]['target']['target1']['recharge'];
                $target_report['target2'] = $recharge_target2 = $schemes[$dist_id]['target']['target2']['recharge'];
                $target_report['achieved'] = $recharge_achieved = $schemes[$dist_id]['achieved']['0']['sale'];
            }*/

            $this->set('year_month',$year.'-'.$month);
            $this->set('target_report',$schemes);
        }

        function buyKits(){
            $distributors = $this->Slaves->query("SELECT distributors.id FROM distributors INNER JOIN users ON distributors.user_id = users.id WHERE  users.id = ".$_SESSION['Auth']['User']['id']);
            $dist_id = $distributors[0]['distributors']['id'];
            $this->set('distributor', $dist_id);

            $to_save = true;
            if ($this->data) {
                        if ($this->data['service'] == 0) {
                                $this->Session->setFlash("<b>Errors</b> : Please select Service");
                                $to_save = false;
                        } else if ((empty($this->data['amount']) || (!preg_match("/^[0-9]*$/", $this->data['amount'])) || ($this->data['amount'] <= 0))) {
                                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/debitsystem.txt","Debit System => " . json_encode($this->data));
                                $this->Session->setFlash("<b>Errors</b> : Please enter proper amount");
                                $to_save = false;
                        } else if (empty($this->data['kits']) || $this->data['kits'] <= 0 || !preg_match("/^[0-9]*$/", $this->data['kits'])){
                                $this->Session->setFlash("<b>Errors</b> : Please enter proper no of Kits");
                                $to_save = false;
                        } else if(!empty($this->data['discount_per_kit']) && (!preg_match("/^[0-9].*$/", $this->data['discount_per_kit']))){
                                $this->Session->setFlash("<b>Errors</b> : Enter valid discount per kit");
                                $to_save = false;
                        } else if($this->data['is_visible'] == '0'){
                                $this->Session->setFlash("<b>Errors</b> : Invalid plan");
                                $to_save = false;
                        }
            }
            $amt = $this->data['setup_amt'];
            $service_plan_id = $this->data['plan'];
            $user_id = $_SESSION['Auth']['User']['id'];
            $service = $this->data['service'];
            $amount   = (($this->data['kits'] * $this->data['setup_amt']) - ($this->data['kits'] * $this->data['discount_per_kit']));
            $dist_details = $this->Slaves->query("SELECT d.id, d.user_id, dk.id, users.balance FROM distributors d LEFT JOIN distributors_kits dk ON ( d.id = dk.distributor_id )
                                                                    JOIN users ON ( d.user_id = users.id )  WHERE users.id =  '$user_id' AND dk.service_id =  '$service' AND dk.service_plans_id =  '$service_plan_id'");

            if (!empty($dist_details) && ($amount > $dist_details[0]['users']['balance'])) {
                $this->Session->setFlash("<b>Errors</b> : Insufficient Balance");
                $to_save = false;
            }
            if ($this->data['confirm_flag'] == 0 && $to_save != false) {
                $confirm_flag = $this->data['confirm_flag'];
                $to_save = false;
            }

            if ($to_save) {
                $kits = $this->data['kits'];
                $note = $this->data['note'];
                $type = KITCHARGE ;
                $discount = $this->data['kits'] * $this->data['discount_per_kit'];

                if ($kits > 200) {
                    $this->Session->setFlash("<b>Errors</b> : Failure: Kits cannot be greater than 200");
                } else {
                    $balance = $this->Shop->shopBalanceUpdate($amount, 'subtract', $user_id);
                    $this->Shop->shopTransactionUpdate($type, $amount, $dist_details[0]['d']['user_id'], $kits, $service, $discount, NULL, $note, $balance + $amount, $balance);
                        if (empty($dist_details)) {
                            $result = $this->User->query("INSERT INTO distributors_kits (distributor_id, service_id, kits,service_plans_id, updated) VALUES "
                                    . "('$dist_id', '$service', '$kits','" . $service_plan_id . "' ,'" . date('Y-m-d H:i:s') . "')");
                        } else {
                            $result = $this->User->query("UPDATE distributors_kits SET kits = kits + $kits, service_plans_id =  $service_plan_id, updated = '" . date('Y-m-d H:i:s') . "' WHERE id = '{$dist_details[0]['dk']['id']}'");
                        }
                        if ($result) {
                            $this->User->query("INSERT INTO distributors_kits_log (distributor_id, service_id, kits,service_plans_id, amount , created_by, created_at, action) VALUES "
                                    . "('$dist_id', '$service', '$kits','" . $service_plan_id . "' , '$amount', '" . $_SESSION['Auth']['User']['id'] . "' ,'" . date('Y-m-d H:i:s') . "','debit')");
                        }
                        if ($this->data['confirm_flag']) {
                            $this->Session->setFlash("<b>Success</b> : Kits buy Successfully");
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
            $plans = $this->Serviceintegration->getServicePlans();
            $this->set('serviceplans', $plans);
        }

        function salesmenAccountHistory()
        {
            if ($this->RequestHandler->isPost()){
                $params = $this->params['form'];
                $search_id = $params['search_id'];
                $search    = $params['search'];
                $from_date = date('Y-m-d', strtotime($params['from_date']));
                $to_date   = date('Y-m-d', strtotime($params['to_date']));
                $date_diff = strtotime($to_date) - strtotime($from_date);
                $diff = floor($date_diff / (60 * 60 * 24));
                if($params['from_date'] != '' && $params['to_date'] != ''){
                    if($diff <= 7 ){
                        $query_where = "
                                (SELECT shop_transactions.id,shop_transactions.note,shop_transactions.date,shop_transactions.timestamp,shop_transactions.source_opening,shop_transactions.source_closing,shop_transactions.type,0 as credit, shop_transactions.amount as debit FROM shop_transactions WHERE  shop_transactions.source_id = '" . $search_id . "' AND shop_transactions.type =".SLMN_RETL_BALANCE_TRANSFER.")
                                UNION
                                (SELECT shop_transactions.id,shop_transactions.note,shop_transactions.date,shop_transactions.timestamp,shop_transactions.target_opening,shop_transactions.target_closing,shop_transactions.type, shop_transactions.amount as credit, 0 as debit FROM shop_transactions JOIN shop_transactions st ON (shop_transactions.target_id = st.id) WHERE st.source_id = '" . $search_id . "' AND shop_transactions.type =".PULLBACK_RETAILER.")
                                UNION
                                (SELECT shop_transactions.id,shop_transactions.note,shop_transactions.date,shop_transactions.timestamp,shop_transactions.target_opening,shop_transactions.target_closing,shop_transactions.type,shop_transactions.amount as credit, 0 as debit FROM shop_transactions WHERE  shop_transactions.type =".DIST_SLMN_BALANCE_TRANSFER." AND target_id = '" . $search_id . "')
                                UNION
                                (SELECT shop_transactions.id,shop_transactions.note,shop_transactions.date,shop_transactions.timestamp,shop_transactions.source_opening,shop_transactions.source_closing,shop_transactions.type,0 as credit, shop_transactions.amount as debit FROM shop_transactions WHERE shop_transactions.source_id = '" . $search_id . "' AND shop_transactions.type =".PULLBACK_SALESMAN.")
                                ";

                        $transactions = $this->Slaves->query("SELECT shop_transactions.* FROM ($query_where) as shop_transactions  where shop_transactions.date >= '$from_date' AND  shop_transactions.date <= '$to_date'  AND  (shop_transactions.credit > 0 OR shop_transactions.debit > 0)  order by shop_transactions.id desc");
                        $this->set('transactions',$transactions);
                        $this->set('txn_data',$txn_data);
                        $this->set('from_date',$from_date);
                        $this->set('to_date',$to_date);
                        }
                        else{
                        $this->Session->setFlash("<b>Error</b> :  Date range exceeding 7 days.");
                        }
                        $this->set('search_id',$search_id);
                        $this->set('search',$search);
                }
                else{
                    $this->Session->setFlash("<b>Error</b> :  Enter Valid Date");
                }
            }
        }

        function salesmenList()
        {
            $this->autoRender = FALSE;

            $salesmen_name = $this->Slaves->query("SELECT salesmen.name,salesmen.id from salesmen JOIN distributors ON ( distributors.id = salesmen.dist_id AND distributors.mobile != salesmen.mobile ) WHERE dist_id = '".$this->Session->read('Auth.id')."' AND salesmen.name LIKE '%".$_POST['str']."%' ");
            echo json_encode($salesmen_name); die;
        }

        function distributorLimit(){
                        
                if ($this->RequestHandler->isPost()) {
                        
                        $this->autoRender = FALSE;
                        
                        $to_save=true;
                        $limit=$this->params['form']['limit'];
                        $id=$this->params['form']['id'];
                        if($this->General->priceValidate($limit) == ''){//amount validation
                                $msg = "Invalid amount";
                                $to_save=false;
                        }

                       if($to_save){
                                $dist = $this->Slaves->query("SELECT mobile, company, max_limit FROM distributors d WHERE id = '$id'");
                                $data = $this->User->query("UPDATE distributors SET max_limit='$limit' where id='$id'");
                                if($data){
                                        $msg = "Limit updated";
                                        $to_save=true;
                                        $this->General->sendMails("Distributor Limit Changed !!!", "Distributor Name : ". $dist[0]['d']['company'] ."<br/>Distributor ID : " . $id . "<br/>Distributor Mobile : " . $dist[0]['d']['mobile'] . "<br/>Limit Changed : From -> " . $dist[0]['d']['max_limit'] . " To -> ".$limit , array("ashok.y@pay1.in","ashish@pay1.in","abhinav.m@pay1.in"),'mail');
                                } else {
                                        $msg = "Unable to update limit";
                                        $to_save=false;
                                }
                        }
                        if(isset($msg)) {
                               $array = array("msg"=>"<div class='alert alert-".(($to_save==true)?"success":"danger")."'>".$msg."</div>","to_save"=>$to_save);
                               echo json_encode($array);exit;
                        }
                }
                
                $result=$this->Slaves->query("select id,user_id,mobile,max_limit from distributors");
                $dist_ids = array_map(function($element){
                        return $element['distributors']['user_id'];
                },$result);
                $temp = $this->Shop->getUserLabelData($dist_ids,2,0);

                $this->set('result',$result);
                $this->set('result_names',$temp);
        }
}

?>
