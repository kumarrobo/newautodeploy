<?php
class RetailersController extends AppController {
	
	var $name = 'Retailers';
	var $helpers = array('Html','Ajax','Javascript','Minify');
	var $components = array('RequestHandler','Shop');
	var $uses = array('Retailer','Product','MasterDistributor','ShopTransaction','VendorsActivation','Slaves');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
		//$this->Auth->allowedActions = array('switchProduct','addUsersToCycle','playwinManual','smartshop','contentPartnerSubscription','vendor','updateLocation','locateUser','reports','getCouponInfo','allRetailers','pushResult','pushPlaywinResult','sendPlywinResults','unsubscribeProduct','unsubscribeTrialProduct','userCyclicPackageUpdate','initializeCronCyclicPackages','cronCyclicPackages','mobileScriptCheck','checkIfWorking','getLastComments','cyclicData','product','salesman','addSalesmanPayment','msgAfterSubscription','locator','all','locate','retailProducts','becomeRetailer','getRetailersByArea','getAreasByCity','getCitiesByState','become','superDistributor','transferSuperDistributorBalance','assignSuperDistributorCoupons','disRetailBenefits');
	}
	
	
	function getCitiesByState(){
		$id = $_REQUEST['state_id'];
		$type = $_REQUEST['type'];
		
		$this->Retailer->recursive = -1;
		if($type == 'r'){
			$frmType = 'Retailer';
			$fn = "getAreas(this.options[this.selectedIndex].value,'r')";
			$areas = $this->Slaves->query("SELECT id,name FROM locator_city WHERE state_id = $id ORDER BY name asc");
		}else if ($type == 'd'){
			$frmType = 'Distributor';
			$fn = "getAreas(this.options[this.selectedIndex].value,'d')";
			$areas = $this->Slaves->query("SELECT id,name FROM locator_city WHERE state_id = $id ORDER BY name asc");	
		}else if ($type == 'u'){
			$frmType = 'Retailer';
			$fn = "getAreas(this.options[this.selectedIndex].value,'u')";
			$areas = $this->Slaves->query("SELECT id,name FROM locator_city WHERE state_id = $id and toShow = 1 ORDER BY name asc");
		}

		$html = '<select tabindex="8" id="city" name="data['.$frmType.'][city]" onchange="'.$fn.'">';
		$html .= '<option value="0">Select City</option>';
		foreach($areas as $area) {
			$html .= '<option value="'.$area['locator_city']['id'].'">'.$area['locator_city']['name'].'</option>';			
		}
		$html .= '</select>';
		echo $html;
		$this->autoRender = false;
	}
	
	function getAreasByCity(){
		$id = $_REQUEST['city_id'];		
		$type = $_REQUEST['type'];
		
		$this->Retailer->recursive = -1;
		if($type == 'r'){
			$frmType = 'Retailer';
			$areas = $this->Slaves->query("SELECT id,name FROM locator_area WHERE city_id = $id ORDER BY name asc");
		}else if ($type == 'd'){
			$frmType = 'Distributor';
			$areas = $this->Slaves->query("SELECT id,name FROM locator_area WHERE city_id = $id ORDER BY name asc");	
		}else if ($type == 'u'){
			$frmType = 'Retailer';
			$areas = $this->Slaves->query("SELECT id,name FROM locator_area WHERE city_id = $id and toShow = 1 ORDER BY name asc");	
		}

		
		$html = '<select tabindex="9" id="area" name="data['.$frmType.'][area_id]">';
		$html .= '<option value="0">Select Area</option>';
		foreach($areas as $area) {
			$html .= '<option value="'.$area['locator_area']['id'].'">'.$area['locator_area']['name'].'</option>';			
		}
		$html .= '</select>';
		echo $html;
		$this->autoRender = false;
	}
	
	/*function getRetailersByArea(){
		$id = $_REQUEST['area_id'];
		$page = 1;
		$limit = 10;
		
		if(isset($_REQUEST['page'])){
			$page = $_REQUEST['page'];
		}
		
		$this->Retailer->recursive = -1;
		
		$str = ($page-1)*$limit . "," . $limit;
		
		$retailers = $this->Retailer->find('all',array('fields' => array('Retailer.*','locator_area.name'),'conditions' => array('Retailer.area_id' => $id,'Retailer.toshow' => 1), 'order' => 'pin',
		'joins' => array(
					array(
						'table' => 'locator_area',
					 	'type' => 'inner',
					    'conditions' => array('Retailer.area_id = locator_area.id')
					)
				),
		'limit' => $str		
		));
		
		$count = $this->Retailer->find('count',array('conditions' => array('Retailer.area_id' => $id,'toshow' => 1)));
		
		$this->set('retailers',$retailers);
		$this->set('page',$page);
		$this->set('limit',$limit);
		$this->set('count',$count);
		$this->render('retailer_list');
	}*/
}