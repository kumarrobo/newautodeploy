<?php
class AppController extends Controller {
		
	var $components = array('Acl', 'Auth', 'Session','General','Shop','RequestHandler');
	var $uses = array();
	
	var $categoryMapping = array();
	var $packageCategories = array();
	var $popularPacks = array();
	
	 /**
     * Overwrite redirect function for 403 header bug from auth component.
     * 
     * (non-PHPdoc)
     * @see cake/libs/controller/Controller#redirect($url, $status, $exit)
     */
	
	
	function redirect($url, $status = null, $exit = true) {
 
        if ($status == 403 && $this->RequestHandler->isAjax()) {
 			$this->throwAjaxError(403, __("$url", true));
            $this->_stop();
            return;
        }
        else {
            parent::redirect($url, $status = null, $exit = true);
        }
    }
    
	/**
	 * Return header codes for AJAX errors.
	 * 
	 * @param $errorCode
	 * @param $message
	 * @return unknown_type
	 */
	protected function throwAjaxError ($errorCode = 400, $message = null) {
 
		if ($this->RequestHandler->isAjax() || (isset($this->isAjax) && $this->isAjax == true)) {
			switch ($errorCode) {
				case 400 :
				case 403 :
				    $defaultMessage = 'The request could not be processed because access is forbidden.';
                    header("'HTTP/1.0 403 Forbidden", true, 403);
                    echo ($message == null)?$defaultMessage:$message;
                    break;
				case 408 :
				case 409 :
					$defaultMessage = 'The request could not be processed because of conflict in the request.';
					header("HTTP/1.0 409 Conflict", true, 409);
					echo ($message == null)?$defaultMessage:$message;
					break;
				case 500 : 
					break;
			}
			$this->autoRender = false;
			$this->layout = 'ajax'; 
			Configure::write('debug', 0);
		}
		else {
			throw new Exception('Ajax Error should only be thrown for ajax requests.');
		}
	}
	
	
	function beforeFilter() {
		//Configure AuthComponent
		$this->Auth->fields = array(
			'username' => 'mobile', 
			'password' => 'password'
		);
		$this->Auth->actionPath = 'controllers/';
		App::import('vendor', 'md5Crypt', array('file' => 'md5Crypt.php'));
		$this->objMd5 = new Md5Crypt;
		
		
		$this->set('objGeneral',$this->General);
		$this->set('objShop',$this->Shop);
		$this->set('objMd5',$this->objMd5);
		
		
		error_reporting(0);
		$this->checkUserAccess(); 
		
	}
	function printArray($txt){
		echo  '<pre>';
		print_r($txt);
		echo '</pre>';		
	}
	
        function checkUserAccess() {
		
		$groupId = $this->Session->read('Auth.User.group_id');
		$controllername = $this->params['controller'];
		$actionname = $this->params['action'];
	
		$mem_data = $this->Shop->getMemcache("controller_$controllername" . "_" . "action_$actionname" . "_" . "group_$groupId");
		
		//$bypassUrl = $this->Shop->getMemcache("controller_$controllername" . "_" . "action_$actionname");
		
	
		Configure::load('acl');
             
                $bypassArray= Configure::read('acl.bypass');
		
		if (array_key_exists($controllername, $bypassArray) && (in_array($actionname, $bypassArray[$controllername]) || ($bypassArray[$controllername][0]=='*'))) {
			
			 
		} else if ($mem_data == 1) {
					
			///$this->redirect(array('controller' => 'shops', 'action' => 'view'));
		} else {
                   $this->redirect(array('controller' => 'panels', 'action' => 'errorMsg'));
		}
	}

	function paginate_query($query, $results_per_page = 100, $options = array(), $instance = 'Slaves'){
            
            $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
            
		$offset = ($page - 1) * $results_per_page;
		$limit_query = " limit $offset, $results_per_page ";

                
		$results = $this->$instance->query($query.$limit_query);
		
		if(!empty($options) && $options['memcache_key']){
			$total_count = $this->Shop->getMemcache($options['memcache_key']);
			if(empty($total_count)){
				$options['memcache_duration'] = !empty($options['memcache_duration']) ? $options['memcache_duration'] : 3600;
				$total_count = count($this->$instance->query($query));
				$this->Shop->setMemcache($options['memcache_key'], $total_count, $options['memcache_duration']);
			}	
		}
		else 
			$total_count = count($this->$instance->query($query));
		
		$this->set('page', $page);
		$this->set('total_count', $total_count);
		$this->set('offset', $offset);
		$this->set('total_pages', ceil($total_count / $results_per_page));
	
		return $results;
	}
}
?>
