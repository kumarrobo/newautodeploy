<?php
class GroupsController extends AppController {
	
	var $name = 'Groups';
	var $helpers = array('Html','Ajax','Javascript','Minify');
	var $uses = array('Group','User');
	var $components = array('RequestHandler','Email');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
		exit;
	}
	
	function rechargedemo($mobile){
//		$msg = 'Welcome to Signal7 demo!
//Download links for the sample applications:
//
//Android: http://bit.ly/yiClx0
//
//Symbian: http://bit.ly/wgzEbQ';
                
                $MsgTemplate = $this->General->LoadApiBalance(); 
		$msg =  $MsgTemplate['Recharge_Demo_MSG'];
		
		$this->General->sendMessage('',$mobile,$msg,'priority',3);
		$this->autoRender = false;	
	}
	

	function find_file ($dirname, $fname, &$file_path) {
		
		  $dir = opendir($dirname);
		
		  while ($file = readdir($dir)) {
		    if (empty($file_path) && $file != '.' && $file != '..') {
		      if (is_dir($dirname.'/'.$file)) {
		        $this->find_file($dirname.'/'.$file, $fname, $file_path);
		      }
		      else {
		        if (file_exists($dirname.'/'.$fname)) {
		          $file_path = $dirname.'/'.$fname;
		          return;
		        }
		      }
		    }
		  }
		
	} // find_file
	
	function createShortUrl(){
		$url_id = $_POST['url_id'];
		if(trim($url_id) != ''){
			$this->pkg_data_url->query("delete from pkg_data_urls where id in( select ref_id from pkg_data_short_urls where pkg_data_short_urls.id=".$url_id.")");		
			$this->pkg_data_url->query("delete from pkg_data_short_urls where id =".$url_id);
		}
				
		$urlArr = $_POST['urls'];
		$typeArr = $_POST['type'];
		$titleArr = $_POST['title'];
		/// do all the insert here
		$insIdArr = array();
		$urlCnt = count($urlArr);
		for($k=0;$k<$urlCnt;$k++){
			if(trim($urlArr[$k]) != ''){
				$this->pkg_data_url->create();
				$this->pkg_data_url->data['title'] = trim($titleArr[$k]);
				$this->pkg_data_url->data['url'] = trim($urlArr[$k]);
				$this->pkg_data_url->data['type'] = trim($typeArr[$k]);
				if ($this->pkg_data_url->save($this->pkg_data_url->data)){
					array_push($insIdArr,$this->pkg_data_url->id);
				}
			}
		}
		
		$this->pkg_data_short_url->create();
		$this->pkg_data_short_url->data['pkg_id'] = $_POST['pkg_id'];
		$this->pkg_data_short_url->data['ref_id'] = implode(",",$insIdArr);
		$this->pkg_data_short_url->data['created'] = date('Y-m-d H:i:s');
				
		$this->pkg_data_short_url->save($this->pkg_data_short_url->data);
		$insert_id = $this->pkg_data_short_url->id;
		//ends
		
		$log_id = urlencode($this->objMd5->Encrypt($insert_id,encKey));
		$url = $this->General->getBitlyUrl('/packages/moreInfo/'.$log_id);
		echo $insert_id."^^^".$url;
		$this->autoRender = false;
	}
		
	function index() {
		//$this->Group->recursive = 0;
		//$this->set('groups', $this->paginate());
		$this->autoRender = false;
	}
	
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('group', $this->Group->read(null, $id));
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Group->create();
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The group has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
	}
	
	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The group has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Group->read(null, $id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for group', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Group->delete($id)) {
			$this->Session->setFlash(__('Group deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Group was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function asynchronousCall(){
		$random_id = $_REQUEST['random'];
		$data = $this->Group->query("SELECT * from asynchronous_calls where random_id = ".$random_id);
		echo $this->requestAction('/'.$data['0']['asynchronous_calls']['controller'].'s/after_'.$data['0']['asynchronous_calls']['action'],array('pass' => array($data['0']['asynchronous_calls']['params'])));
		$this->Group->query("DELETE FROM asynchronous_calls where random_id = ".$random_id);
		$this->autoRender = false;
	}
	
	
	function sendEmail(){
		$email = $this->data['email'];
		$emails = explode(",",$email);
		$body = $this->data['body'];
		$subject = $this->data['subject'];
		if(!empty($email)){
			$this->Email->html_body = $body;
			$this->Email->to = $emails;
            $this->Email->subject = $subject; 
            $result = $this->Email->send(); 
		}	
		$this->render('email_panel','ajax');
	}
	
	
	function excelToPhp(){
      	set_time_limit(0);
		ini_set("memory_limit","-1");
		error_reporting(E_ALL);
 		ini_set("display_errors", 1);
		
 		App::import('Vendor', 'excel_reader2');
		$data = new Spreadsheet_Excel_Reader('smartshop.xls', true);
		$i = 0;
		$vendorActObj = ClassRegistry::init('VendorsActivation');
		$vendorActObj->recursive = -1;
		
		foreach($data->sheets[0]['cells'] as $user){
			if(empty($user)) continue;
			
			$date = DateTime::createFromFormat('m d Y', trim($user[4]));
			$time = $date->format('Y-m-d H:i:s');
			$ref_code = substr(trim($user[2]),0,-1);
			
			$data1 = $this->Group->query("SELECT products_users.id,users.id FROM products_users,products,users WHERE users.mobile='".trim($user[3])."' AND products.price='".trim($user[5])."' AND products.id = products_users.product_id AND users.id = products_users.user_id AND Date(products_users.start) = '".$date->format('Y-m-d')."'");
			$this->printArray($data1);
			$retailer_code = trim($user[1]);
			if(empty($data1)){
				echo "Not found id";
			}
			else {
				$this->data['VendorsActivation']['vendor_id'] = 5;
				$this->data['VendorsActivation']['productuser_id'] =  $data1[0]['products_users']['id'];
				$this->data['VendorsActivation']['vendor_retail_code'] = $retailer_code;
				$this->data['VendorsActivation']['timestamp'] =  $time;
				$this->data['VendorsActivation']['txn_id'] =  $ref_code;
				$this->printArray($this->data);
				/*$vendorActObj->create();
				$vendorActObj->save($this->data);
				$prod_price = trim($user[5]);
				$this->Group->query("INSERT INTO vendors_retailers (vendor_id,retailer_code) VALUES (5,'$retailer_code')");
				$this->Group->query("UPDATE vendors_retailers SET totalSale= totalSale+$prod_price, weeklySale= weeklySale+$prod_price WHERE vendor_id = 3 AND retailer_code = '$retailer_code'");
				*/
			}
			$i++;
		}
		$this->autoRender = false;
	}
	
	function excelToPhp1(){
      	set_time_limit(0);
		ini_set("memory_limit","-1");
		error_reporting(E_ALL);
 		ini_set("display_errors", 1);
		
 		App::import('Vendor', 'excel_reader2');
		$data = new Spreadsheet_Excel_Reader('agam.xls', true);
		$i = 0;
		
		foreach($data->sheets[0]['cells'] as $user){
			if(isset($user[2])){
				$mobile = trim($user[2]);
				$mobile = substr($mobile, -10);
				if(!empty($mobile) && strlen($mobile) == 10){
					$this->Group->query("INSERT INTO contact_anu (mobile,type) VALUES ('$mobile','agam')");
				}
			}
				//$this->printArray($user);
			
		}
		$this->autoRender = false;
	}
	
	
	function build_acl() {
		set_time_limit(0);
		ini_set("memory_limit","-1");
		
		if (!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();
		
		$aco =& $this->Acl->Aco;
		$root = $aco->node('controllers');
		if (!$root) {
			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id; 
			$log[] = 'Created Aco node for controllers';
		} else {
			$root = $root[0];
		}   
		
		App::import('Core', 'File');
		$Controllers = Configure::listObjects('controller');
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'buildAcl';
		
		$Plugins = $this->_getPluginControllerNames();
		$Controllers = array_merge($Controllers, $Plugins);
		
		// look at each controller in app/controllers
		foreach ($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));
			
			// Do all Plugins First
			if ($this->_isPlugin($ctrlName)){
				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
				if (!$pluginNode) {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/'.$ctrlName);
			if (!$controllerNode) {
				if ($this->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				} else {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			} else {
				$controllerNode = $controllerNode[0];
			}
			
			//clean the methods. to remove those in Controller and private actions.
			foreach ($methods as $k => $method) {
				if (strpos($method, '_', 0) === 0) {
					unset($methods[$k]);
					continue;
				}
				if (in_array($method, $baseMethods)) {
					unset($methods[$k]);
					continue;
				}
				$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
				if (!$methodNode) {
					$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
					$methodNode = $aco->save();
					$log[] = 'Created Aco node for '. $method;
				}
			}
		}
		if(count($log)>0) {
			debug($log);
		}
		$this->autoRender = false;
	}
	
	function _getClassMethods($ctrlName = null) {
		App::import('Controller', $ctrlName);
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num+1);
		}
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);
		
		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if (array_key_exists('scaffold',$properties)) {
			if($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			} else {
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}
		}
		return $methods;
	}
	
	function _isPlugin($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) > 1) {
			return true;
		} else {
			return false;
		}
	}
	
	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}
	
	function _getPluginName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}
	
	function _getPluginControllerName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}
	
	/**
	 * Get the names of the plugin controllers ...
	 * 
	 * This function will get an array of the plugin controller names, and
	 * also makes sure the controllers are available for us to get the 
	 * method names by doing an App::import for each plugin controller.
	 *
	 * @return array of plugin names.
	 *
	 */
	function _getPluginControllerNames() {
		App::import('Core', 'File', 'Folder');
		$paths = Configure::getInstance();
		$folder =& new Folder();
		$folder->cd(APP . 'plugins');
		
		// Get the list of plugins
		$Plugins = $folder->read();
		$Plugins = $Plugins[0];
		$arr = array();
		
		// Loop through the plugins
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder->findRecursive('.*_controller\.php');
			
			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);
				
				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		return $arr;
	}
	
	function test(){
		echo "1"; exit;
		$this->autoRender = false;
	}
}
?>