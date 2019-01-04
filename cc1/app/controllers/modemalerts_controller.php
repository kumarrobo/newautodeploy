<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModemalertsController extends AppController {

    var $name = 'Modemalerts';
    var $components = array('RequestHandler', 'Shop', 'General');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator');
    var $uses = array('Retailer','Slaves');

    function beforeFilter() {
    	parent::beforeFilter ();
        $this->Auth->allow('*');
//         header('Content-Type: application/json');
    }
    
    /*
     * This function will receive alert from modems if there is a power failure or power is back
     */
    function alertpowercut(){
    	$this->autoRender = false;
    	$vendor = $_REQUEST['vendor'];
    	$flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : 1; //flag 0 means power is cut, 1 means power is back
    	$data = array();
    	
    	if(empty($vendor))return;
    	
    	if($flag == 1){
    		$body = "Electricity is up. Server is starting the transactions now";
    		$this->Shop->setMemcache("electricity_".$vendor,1,60*60);
    		$this->Shop->healthyVendor($vendor);
    	}
    	else {
    		$body = "Electricity is down. Server is stopping the transactions now. System will be shutdown in few minutes";
    		$this->Shop->setMemcache("electricity_".$vendor,0,60*60);
    		$this->Shop->unHealthyVendor($vendor,20);
    	}
    	
    	$data = $this->Shop->getVendorInfo($vendor);
    	$name = $data['company'];
    	
    	$this->General->sendMails("(SOS)Power failure problem $name Vendor : $vendor",$body,array('backend@pay1.in','chetan.yadav@pay1.in','lalit.kumar@pay1.in'),'mail');
    }
    
    
    function ipupdate(){
    	$vendor = $_REQUEST['vendor'];
    	$ip = $this->General->getClientIP();
    	$this->General->logData('ipupdate.txt',"$vendor::$ip");
    	$this->Shop->setVendorInfo($vendor,array('ip'=>$ip));
    	$this->Shop->setMemcache("vendorip_$vendor",$ip,120);
    	$this->autoRender = false;
    }
    
    function checkForCodeUpdate() {
    	$vendor = $_REQUEST['vendor_id'];
    	$data = $this->Slaves->query("SELECT svn_flag FROM vendors WHERE id = $vendor");
    
    	if (!empty($data) && $data[0]['vendors']['svn_flag'] > 0) {
    
    		$this->Retailer->query("UPDATE vendors SET svn_flag=svn_flag-1 WHERE id = $vendor");
    
    		echo json_encode(array('status' => 1));
    	} else
    		echo json_encode(array('status' => 0));
    
    		$this->autoRender = false;
    }
    
   
    function CheckOperatorFlag(){
    
    
    	$checkAutoUpdateOperator = $this->Slaves->query("Select id,auto_check,name from products where modified  >= '".$_REQUEST['prevtimestamp']."'");
    	if(count($checkAutoUpdateOperator)>0){
    		foreach ($checkAutoUpdateOperator as $val){
    			$data[$val['products']['id']]['check'] = $val['products']['auto_check'];
    			$data[$val['products']['id']]['name'] = $val['products']['name'];
    		}
    		echo json_encode($data);
    	}
    
    	$this->autoRender = false;
    }
    
    function getSMSTemplates() {
    	$timestamp = isset($_REQUEST['timestamp']) ? urldecode($_REQUEST['timestamp']) : '0000-00-00 00:00:00';
    	 
    	$data = $this->Slaves->query("SELECT * FROM sms_templates Where datetime >= '$timestamp'");
    
    	$prods = $this->Slaves->query("SELECT auto_check,id FROM products WHERE to_show=1");
    	$data['prods'] = $prods;
    	echo json_encode($data);
    	$this->autoRender = false;
    }
    
}




