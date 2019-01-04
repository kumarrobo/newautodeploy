<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class AclController extends AppController {

	var $name = 'Acl';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart');
	var $components = array('RequestHandler','Shop');
	var $uses = array('Retailer','Distributor','MasterDistributor','User','ModemRequestLog','Slaves');
	var $turnaround_time = array(0.5, 1, 2, 24, 48);
	var $api_medium_map = array("SMS", "API", "USSD", "Android", "", "Java", "", "Windows 7", "Windows 8", "Web");	

	function beforeFilter() {
		
		    set_time_limit(0);
		    ini_set("memory_limit","512M");
        //ini_set("display_errors", "off");
        //error_reporting(0);
		    parent::beforeFilter();
			
			 $this->Auth->allow('*');
		
		    $this->layout = 'module';
			
			$moduleListing = $this->Slaves->query("SELECT modules.module_name,module_group_mapping.module_id,module_full_name,module_group_mapping.group_id from modules Inner join module_group_mapping ON (modules.id=module_group_mapping.module_id) WHERE group_id = '".$this->Session->read('Auth.User.group_id')."' and show_flag = '1' order by modules.id ASC");

		Configure::load('acl');
		
		$mappedModule= Configure::read('acl.modules');

			 $bypassmodule= Configure::read('acl.bypass');
			 
			 if(!empty($moduleListing)) {
			
			foreach ($moduleListing as $moduleval):
				
				if(array_key_exists($moduleval['modules']['module_name'],$mappedModule)):
					
				if(!empty($moduleval['modules']['module_full_name'])):
				
					
				$this->modulearray[$moduleval['modules']['module_full_name']] = array("action" => $mappedModule[$moduleval['modules']['module_name']]['url'][0]);
				endif;
				endif;
		    endforeach;	
			 }
			
			
			$this->set('modulelist',$this->modulearray);
			
		}
		


		public function setUserAccess() {
				
		// get the module and group mapping from database and set the data in memcache
		Configure::load('acl');
		
        $moduleData= Configure::read('acl.modules');
		
		$data = array();
		
		foreach ($moduleData as $key => $val) {

			$getModuleDetails = $this->Retailer->query("Select * from modules where module_name = '" . $key . "'");

			if (count($getModuleDetails) > 0) {

				$moduleId = $getModuleDetails[0]['modules']['id'];

				if (!empty($moduleId)) {

					$getModuleMappingDetails = $this->Retailer->query("Select * from module_group_mapping where module_id = '" . $moduleId . "'");

					if (count($getModuleMappingDetails) > 0) {
                                                
						foreach ($getModuleMappingDetails as $moduleval) {

							$getModulelist = $moduleData[$key]['list'];

							$groupId = $moduleval['module_group_mapping']['group_id'];
							$access_type = $moduleval['module_group_mapping']['access_type'];
//                                                      
							foreach ($getModulelist as $listval) {

								$controllerName = $listval['controller'];
                                                                
								if ($access_type == 1) {  // full access
									$getmoduleAction = $listval['action'];
								} else {  // partial access
									$getmoduleAction = array($listval['action'][0]);
								}
								
								
								foreach ($getmoduleAction as $actionlist) {

									foreach ($actionlist as $actionkey => $accessval) {
										
										//$this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/acl.txt",date('Y-m-d H:i:s')."controller_$controllerName" . "_" . "action_$accessval" . "_" . "group_$groupId");

										$this->Shop->setMemcache("controller_$controllerName" . "_" . "action_$accessval" . "_" . "group_$groupId", 1, 15*60);
									}
								}
							}
						}
					}
				}
			}
                        }

		$this->autoRender = false;
	}
	
	    function insertModule(){
		
		 $moduleData= Configure::read('acl.modules');
		
		  foreach ($moduleData as $key => $val):
			$checkModuleExist = $this->Retailer->query("SELECT id from modules WHERE module_name = '".$key."' ");
			if(empty($checkModuleExist)):
			$sql = "INSERT  INTO modules VALUES ('','".$key."', '',1,'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
			$this->Retailer->query($sql);
			endif;
		endforeach;
		$this->autoRender = false;
	}
	
	
	function module() {
		
		Configure::load('acl');
             
        $mappedModule= Configure::read('acl.modules');
		
		$groupDetails = $this->Retailer->query("Select * from groups where flag = '1' and id NOT IN (".VENDOR.")");
		
		$moduleDetails = $this->Retailer->query("Select * from modules where show_flag = '1'");

		$this->set('group', $groupDetails);
		$this->set('module', $moduleDetails);
		foreach ($moduleDetails as $modDetails) {
			$modValue[$modDetails['modules']['id']] = $modDetails['modules']['module_name'];
		}

		$assignedModule = array();
		$moduleArray = array();
		$returnArray = array();
		if ($this->RequestHandler->isAjax()) {
			$groupId = $_REQUEST['group'];
			if (isset($_REQUEST['moduleid']) && !empty($_REQUEST['moduleid'])) {
				$moduleId = $_REQUEST['moduleid'];
				$deleteModule = $this->Retailer->query("Delete from module_group_mapping where group_id ='" . $groupId . "' AND module_id IN($moduleId)");

                                $this->deleteExistingAclMapping($moduleId, $groupId ,$modValue);
                                
			} else if (isset($_REQUEST['insertid']) && !empty($_REQUEST['insertid'])) {
                                $insertId = explode(',', $_REQUEST['insertid']);
				$accessType = explode(',', $_REQUEST['access_id']);
                                
				$this->deleteExistingAclMapping($_REQUEST['insertid'], $groupId ,$modValue);
                                
				foreach ($insertId as $key => $val) {
					$modulename = $modValue[$val];
					$getModulelist = $mappedModule[$modulename]['list'];
					
					foreach ($getModulelist as $listval) {
						$controllerName = $listval['controller'];
						
						if (in_array("full_".$val, $accessType)) {  // full access
							$accessvalue = "1";
							$getmoduleAction = $listval['action'];
							
						} else {  // partial access
							$accessvalue = "0";
							$getmoduleAction = array($listval['action'][0]);
						}
						
						foreach ($getmoduleAction as $actionlist) {
							foreach ($actionlist as $actionkey => $accessval) {
								$this->Shop->setMemcache("controller_$controllerName" . "_" . "action_$accessval" . "_" . "group_$groupId", 1,24*60*60);
							}
						}
					}
					
					$insertModule = $this->User->query("INSERT INTO module_group_mapping VALUES('','" . $val . "','" . $groupId . "','" . $accessvalue . "')");
				}
			}

			$getModulebyId = $this->Retailer->query("Select * from module_group_mapping where group_id = '" . $groupId . "'");
			$getAllModule = $this->Retailer->query("Select * from modules where show_flag = '1' ");
			foreach ($getAllModule as $modval) {
				$moduleArray[$modval['modules']['id']] = $modval['modules']['module_full_name'];
			}
			$accessModules = array();
			foreach ($getModulebyId as $val) {
				$text = ($val['module_group_mapping']['access_type'] == 1) ? "(Full)" : "(Only View)";
				$assignedModule[$val['module_group_mapping']['module_id']] = $moduleArray[$val['module_group_mapping']['module_id']];
				$accessModules[$val['module_group_mapping']['module_id']] = $text;
			}
			$getUnAssignedModule = array_diff($moduleArray, $assignedModule);
			$getAssignedModule = array_intersect($assignedModule, $moduleArray);
			
			foreach ($getAssignedModule as $key => $val){
				if(isset($accessModules[$key])){
					$getAssignedModule[$key] = $val . " " . $accessModules[$key];
				}
			}

			$this->set('assignedModule', $getAssignedModule);
			$this->set('UnassignedModule', $getUnAssignedModule);

			$returnArray['Assignedmodule'] = $getAssignedModule;
			$returnArray['Unassignedmodule'] = $getUnAssignedModule;

			$result = array("status" => "success", "response" => $returnArray);
			echo json_encode($result);
			die;
		}
	}
        
        function deleteExistingAclMapping($moduleId,$groupId,$modValue)
        {
            Configure::load('acl');             
            $mappedModule= Configure::read('acl.modules');
            
            $delete_module_ids = explode(',', $moduleId);
            
            foreach ($delete_module_ids as $key => $val) {
                $modulename = $modValue[$val];
                $getModulelist = $mappedModule[$modulename]['list'];

                foreach ($getModulelist as $listval) {
                    $controllerName = $listval['controller'];

                    $getmoduleAction = $listval['action'];

                    foreach ($getmoduleAction as $actionlist) {
                        foreach ($actionlist as $actionkey => $accessval) {
                            $memcache_set_res = $this->Shop->getMemcache("controller_$controllerName" . "_" . "action_$accessval" . "_" . "group_$groupId");
                            if($memcache_set_res == 1)
                            {
                                $this->Shop->delMemcache("controller_$controllerName" . "_" . "action_$accessval" . "_" . "group_$groupId");                                        
                            }
                        }
                    }
                }
            }
        }
	
		function listUser(){
                    
			
                        $this->layout='plain';
                                    
             		$userdata = $this->User->query('Select users.id,GROUP_CONCAT(groups.name) as groups,users.active_flag,users.mobile,users.name AS username,users.outside_access,groups.outside_access
                                                                                                        from user_groups iu 
                                                                                                        inner join groups  
                                                                                                        ON (iu.group_id = groups.id)
                                                                                                        inner join users 
                                                                                                        on (users.id = iu.user_id) 
                                                                                                        where groups.flag = 1
                                                                                                        group by users.id
                                                                                                        order by groups.name asc');
                   
                        $this->set('userData',$userdata);

			
		}
                
		function addUser($userId=null){
			
			$group = $this->User->query('Select * from groups where flag = "1"');
			
			$this->set('group',$group);
			
				if ($this->RequestHandler->isPost()) {
					
					$userId = isset($_POST['user_id']) ? $_POST['user_id'] : "";
					$username = isset($_POST['username']) ? $_POST['username'] : "";
					$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : "";
					$groupId = isset($_POST['group']) ? $_POST['group']: ""; 
					$password = isset($_POST['pwd']) ? $_POST['pwd'] : "";
					$pwd  = $this->Auth->password($password);
					
					if(empty($userId)){
						
						$userData['User']['name'] = $username;
                                                $userData['User']['group_id'] = $groupId;
						$userData['User']['password'] = $pwd;
						$userData['User']['mobile'] = $mobile;
						$userData['User']['created'] = date('Y-m-d H:i:s');
						$userData['User']['modified'] = date('Y-m-d H:i:s');
						
						$checkUserExist = $this->Retailer->query("select * from users where mobile = '".$mobile."' and group_id NOT IN ('2','3','4','5','6','8','9','11')");
						if(!empty($checkUserExist)){
							if ($checkUserExist[0]['users']['group_id'] == MEMBER) {
								$password = $this->Auth->password('0000');
							} else {
								$password = $checkUserExist[0]['users']['password'];
							}

					        $checkinternaluser = $this->Retailer->query("select * from internal_users where user_id = '".$checkUserExist[0]['users']['id']."'");
							
							if(empty($checkinternaluser)){
								$this->Retailer->query("INSERT INTO internal_users (username,password,created_at,modified_at,group_id,user_id) VALUES ('".$checkUserExist[0]['users']['name']."','".$password."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','".$checkUserExist[0]['users']['group_id']."','".$checkUserExist[0]['users']['id']."')");
								$this->Retailer->query("Update users set password = '".$password."' where id = '".$checkUserExist[0]['users']['id']."'");
							} else {
								echo "User already exist with same number!!!!";
								die;
							}
						}else if($this->User->save($userData)){
                                                    							
                                 $userId= $this->User->id;

                                 $this->Retailer->query("INSERT INTO internal_users (username,created_at,modified_at,group_id,user_id) VALUES ('$username','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."',".$groupId.",'$userId')");
						}
						
					} else {
						
						$checkUserById = $this->Retailer->query("select * from users inner join internal_users ON (users.id = internal_users.user_id ) where internal_users.user_id = '".$userId."'");
						if(!empty($checkUserById)) {
							
						if($checkUserById[0]['users']['mobile']!=$mobile){
							
						    $checkUserExist = $this->Retailer->query("select * from users where mobile = '".$mobile."'");
							
						    if(!empty($checkUserExist)){
							echo "User already exist with same number!!!!";
							die;
							
						   }
						   } else {
							
							if($this->Retailer->query("update users set mobile = '".$mobile."',group_id = '".$groupId."',name='".$username."' where id ='".$userId."' ")){
						
							 $this->Retailer->query("update internal_users set username = '".$username."',group_id = '".$groupId."' where user_id ='".$userId."'");
								
							
						     }
						}
						
						}
					}
					$this->redirect('/acl/listUser/');
					
				} else {

					   $userdata = $this->Slaves->query("Select 
					                          internal_users.*,users.mobile,users.id,users.group_id,users.name 
					                          from 
					                          internal_users 
					                          inner join users on (users.id = internal_users.user_id)
							                  where internal_users.user_id = '".$userId."'");
					   
					   $inventoryUser = $this->Slaves->query("Select users.id, users.name, users.mobile,groups.name,users.group_id
					                              from users 
					                                 inner join vendors ON ( users.id = vendors.user_id )
					                                 INNER JOIN groups ON ( groups.id = users.group_id )
                                                     where users.id = '".$userId."'");
					   
					 
					   
					   if(empty($userdata)) {
						    $this->set('userData',$inventoryUser);
					   } else {
						    $this->set('userData',$userdata);
					   }
			
			
			          
					   $this->set('user_id',$userId);
					
				}
			
		}
		
		function listGroup(){
			
                                                        $this->layout='plain';
			$group = $this->User->query('Select * from groups where flag = "1" ORDER BY name ');
			
			$this->set('group',$group);
			
			
		}
		
		function addGroup($groupId=null){
			
			if ($this->RequestHandler->isPost()) {
				
				
				
				$groupId = isset($_POST['group_id']) ? $_POST['group_id'] : "";
				
				$groupName = isset($_POST['groupname']) ? $_POST['groupname'] : "";
					
			    $flag = isset($_POST['flag']) ? $_POST['flag'] : "";
			  
				
				if(empty($groupId)){
					
					$this->Retailer->query("INSERT INTO groups (name,flag,created,modified) VALUES ('$groupName','".$flag."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')");
				} else {
					
					$this->Retailer->query("update groups set name = '".$groupName."',flag = '".$flag."' where id  ='".$groupId."'");
				}
				$this->redirect('/acl/listGroup/');
				
			} else {
				
				$group = $this->Slaves->query("Select * from groups where id = '".$groupId."'");
			}
			
			
			
			$this->set('group',$group);
			$this->set('group_id',$groupId);
			
			
		}
		
		/*function insertExistingUser(){
			
			$query = $this->Retailer->query(" SELECT * FROM `users` where mobile IN ('9967054833',
																					'9773605396',
																					'9619635016',
																					'9819990261',
																					'8652740726',
																					'9004010430',
																					'9930078836'
																					)");
			
			if(!empty($query)){
				
				foreach ($query as $val){
					
					$userId = $val['users']['id'];
					
					$checkUser = $this->Retailer->query("Select * from internal_users where user_id = '".$userId."'");
					
					if(empty($checkUser)){
						
						$this->Retailer->query("INSERT INTO internal_users (username,password,created_at,modified_at,group_id,user_id) VALUES ('".$val['users']['name']."','".$val['users']['password']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','".$val['users']['group_id']."','".$val['users']['id']."')");
					}
				}
				
			}
			
			$this->autoRender = false;
		}*/
                
                /*
                 * New Add user logic where a single user can belong to one or many groups
                 * Start
                 */
                
                    public function add()
                    {
                        $this->layout='plain';
                        $group = $this->User->query('Select * from groups where flag = "1" ORDER BY name ASC ');
                       
                         if ($this->RequestHandler->isPost()):
                            
                             $errors=$this->validateForm($this->params['form']);
                            
                                    if(!empty($errors)):
                                              $errorString=count($errors)>1?implode(',',$errors):$errors[0];
                                              $this->Session->setFlash("<b>Errors</b> : ".$errorString);
                                              $this->redirect('add');
                                   else:
                                            
                                             // Add an entry to users table
                                            $userData['User']['name'] = $this->params['form']['username'];
                                            $userData['User']['group_id'] = $this->params['form']['selectItemgroups_cc'][0];
                                            $userData['User']['password'] = $this->Auth->password($this->params['form']['password']);
                                            $userData['User']['mobile'] = $this->params['form']['mobile'];
                                            $userData['User']['created'] = date('Y-m-d H:i:s');
                                            $userData['User']['modified'] = date('Y-m-d H:i:s');
                                            
                                            $this->User->save($userData);
                                            $insert_id=$this->User->id;
                                            
                                            if($insert_id):
                                                
                                                    // 1:m in user_groups table
                                                    if(!empty($this->params['form']['selectItemgroups_cc'])):
                                                        foreach($this->params['form']['selectItemgroups_cc'] as $group_id):
                                                        $this->Shop->addUserGroup($insert_id,$group_id);
                                                        endforeach;
                                                    endif;

                                                    $this->Session->setFlash("<b>Success</b> : User created successfully");
                                                    $this->redirect('add');
                                            
                                            else:
                                                
                                                    $this->Session->setFlash("<b>Error</b> : Db error");
                                                    $this->redirect('add');
                                                    
                                            endif;
                                             
                                   endif;
                       endif;
                       
                        $this->set('groups',$group);
                    }
                    
                    public function validateForm($params,$mode="add")
                    {
                       $error=array();
                       
                        if(empty($params['selectItemgroups_cc'])):
                                    $error[]="No group Selected";
                        endif;
                        
                        if(empty($params['mobile']) && $mode=="add"):
                                     $error[]="No Mobile Selected";
                        endif;
                        
                        if($mode=="add"):
                            if($params['password']!=$params['confirmpassword']):
                                        $error[]="Passwords dont match";
                            endif;
                        endif;
                        
                        if(!empty($params['mobile']) && $mode=="add"):
                            $sql="Select * from users where mobile='{$params['mobile']}' ";
                            $result=  $this->User->query($sql);
                            if(!empty($result)):
                                $error[]="User already exists";
                            endif;
                        endif;
                        
                        return $error;
                        
                    }
                    
                    public function edit($id)
                    {
                        if(empty($id)): exit('Error'); endif;
                        
                        $this->layout='plain';
                        $group = $this->User->query('Select * from groups where flag = "1" ORDER BY name ASC ');
                        
                        if ($this->RequestHandler->isPost()):
                           
                            $errors=$this->validateForm($this->params['form'],'edit');
                            
                                    if(!empty($errors)):
                                              $errorString=count($errors)>1?implode(',',$errors):$errors[0];
                                              $this->Session->setFlash("<b>Errors</b> : ".$errorString);
                                              $this->redirect('edit/'.$id);
                                   else:
                                   
                                     if(!empty($this->params['form']['selectItemgroups_cc'])):
                                            $data = $this->User->query("DELETE FROM user_groups where user_id = '$id'");
                                            foreach ($this->params['form']['selectItemgroups_cc'] as $group_id):
                                            //$this->Shop->addUserGroup($id, $group_id);
                                            $sql = "INSERT INTO `user_groups` VALUES (NULL,'{$id}','{$group_id}')";
                                            $this->User->query($sql);
                                            endforeach;
                                            $sql = "Update users set  name ='{$this->params['form']['username']}' where id='{$id}'  ";
                                            $this->User->query($sql);
                                      endif;

                                     $this->Session->setFlash("<b>Success</b> : User updated successfully");
                                     $this->redirect('edit/'.$id);
                            
                                    endif;

                        endif;
                          
                        
                        $user=  $this->User->query("Select iu.*,u.mobile,u.name from user_groups iu JOIN users u  ON iu.user_id=u.id where user_id='{$id}' ");
                      
                        $user=  $this->format($user); 
                        $this->set('groups',$group);
                        $this->set('userData',$user);
                    }
                    
                    public function format($data)
                    {
                        $temp=array();
                        
                        foreach($data as $value):
                                $temp['user']=array('id'=>$value['iu']['id'],'mobile'=>$value['u']['mobile'],'name'=>$value['u']['name']);
                                $temp['groups'][]=$value['iu']['group_id'];;
                        endforeach;
                        
                      return $temp;
                     
                   }
                    
                   public function outsideAccess() {
                        $this->layout='plain';

                        $data = $this->User->query("SELECT * FROM  `groups` WHERE flag =1 LIMIT 0 , 30");
                        $this->set('groupList', $data);
                   }
		
                   public function giveaccess($id) {
                       $this->autoRender = false;
                       
                       $success = $this->User->query("UPDATE `groups` SET `outside_access`= '1' WHERE id = $id");
                       
                        if ($success) {
                            $this->Session->setFlash("<b>Success</b> : Access granted successfully");
                        } else {
                            $this->Session->setFlash("<b>Errors</b> : Unable to grant access");
                        }
                        $this->redirect('outsideAccess');
                   }
	
                   public function removeaccess($id){
                        $this->autoRender = false;

                        $success = $this->User->query("UPDATE `groups` SET `outside_access`= '0' WHERE id = $id");

                        if ($success) {
                            $this->Session->setFlash("<b>Success</b> : Access removed successfully");
                        } else {
                            $this->Session->setFlash("<b>Errors</b> : Unable to remove access");
                        }
                        $this->redirect('outsideAccess');
                   }
                   
                   public function externalaccess($id,$val){
                       $this->autoRender = false;
                       $data=$this->User->query("update users SET outside_access='$val' where id='$id'");
                       $this->redirect('listUser');
                   }
                   
                   public function delete($id,$flag){
                       $this->autoRender = false;
                       if($flag == 1){
                           $data=$this->User->query("update users SET active_flag=0 where id='$id' AND active_flag = 1");
                       }
                       else {
                           $data=$this->User->query("update users SET active_flag=1 where id='$id' AND active_flag = 0");
                       }
                       $this->redirect('listUser');
                   }
                   
                   public function groupUsers(){
                       $this->layout='plain';
                       $all_groups=$this->Slaves->query("SELECT id,name from groups");
                       $this->set('all_groups',$all_groups);
                       if ($this->RequestHandler->isPost())
                       {   
                            $to_save= true;
                            if ($this->params['form']['group']== "") {
                            $msg = "Please Select Group Name";
                            $to_save = false;
                            }
                           if($to_save){
                               $group = $this->params['form']['group'];
                               $search = $this->params['form']['search'];
                                $query_where = "SELECT users.id,name ,mobile from user_groups INNER JOIN users ON users.id=user_groups.user_id where group_id= $group";
                                 if($search != ''){
                                    $query_where .= " and (users.id = '" . $search . "'  or mobile = '" . $search . "')";
                                }
                               $user = $this->paginate_query($query_where);
                               $this->set('user',$user);          
                            }
                           if(isset($msg)) {
                             $msg = "<div class='alert alert-".(($to_save==true)?"success":"danger")."'>".$msg."</div>";
                             $this->Session->setFlash($msg);
                            }
                       }
                   }
                   
                   function moduleAccess(){
                       $this->layout = 'plain';
                       
                       $modulegroup = $this->Slaves->query("SELECT id, module_name FROM `modules`");
                           
                       $mod = array();
                       foreach ($modulegroup as $arr){
                                $mod [] =  array("value" => $arr ['modules']['module_name'],"data"=> $arr ['modules']['id']);
                       }
                       
                       $this->set('moduleList', json_encode($mod));
                   }
                   
                   function moduleGroup(){
                       $this->autoRender = FALSE;
                       $modulegroup = $this->Slaves->query("SELECT groups.id, groups.name FROM `module_group_mapping` 
                                                                INNER JOIN `modules` ON  modules.id = module_group_mapping.`module_id`
                                                                INNER JOIN `groups` ON groups.id = module_group_mapping.`group_id`
                                                                WHERE modules.id = '".$_POST['module']."'");
                       echo json_encode($modulegroup);
                   }
}
