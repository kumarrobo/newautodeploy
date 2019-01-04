<?php

class WsregisterController extends AppController {
	var $name = 'Wsregister';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv');
	var $components = array('RequestHandler','Shop');
	var $uses = array('User','C2d');
	
	function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('*');
	}

	function index($id=NULL) {
            
                $ivr_nos = array('9167787891','9970648711','9819042543','9890328611','7040157755');
                
                $ivr_nos_used = $this->C2d->query("SELECT ivr_no FROM cash_payment_client WHERE ivr_no != ''");
                $ivr_temp = array();
                foreach($ivr_nos_used as $i_n_u) {
                    $ivr_temp[] = $i_n_u['cash_payment_client']['ivr_no'];
                }
                $ivr_nos = array_diff($ivr_nos, $ivr_temp);
                
                
                if(isset($id)) {
                        
                        $id_data = $this->C2d->query('SELECT * FROM cash_payment_client WHERE id = ' . $id);
                        $ivr_nos[] = $id_data[0]['cash_payment_client']['ivr_no'];
                        
                        $slab_data = $this->User->query('SELECT percent FROM slabs_products WHERE product_id = ' . $id_data[0]['cash_payment_client']['product_id'] . ' LIMIT 1');
                        $id_data[0]['cash_payment_client']['slab_commision'] = $slab_data[0]['slabs_products']['percent'];
                                
                        $this->set('id_data', $id_data[0]['cash_payment_client']);
                }
                
                $this->set('ivr_nos', $ivr_nos);
                
                $this->layout = 'wsregister';
	}
	
	function registerWholesaler() {

                $action         = $this->params['form']['action'];
                $id             = $this->params['form']['id'];
                $name           = $this->params['form']['name'];
                $username       = $this->params['form']['username'];
                $contact_person = $this->params['form']['contact_person_name'];
                $contact_no     = $this->params['form']['contact_no'];
                $ivr_no         = $this->params['form']['ivr_no'];
                $pay1discount   = $this->params['form']['pay1discount'];
                $slab_discount  = $this->params['form']['slab_commision'];
                $bankname       = $this->params['form']['bankname'];
                $branchname     = $this->params['form']['branchname'];
                $ifsccode       = $this->params['form']['ifsccode'];
                $address        = str_replace("'","~",$this->params['form']['address']);
                $logo_url       = $this->params['form']['logo_url'];
                $cover_photo    = $this->params['form']['cover_photo'];
                $description    = str_replace("'","~",$this->params['form']['description']);
                $date           = date('Y-m-d H:i:s');

                $slabs          = $this->User->query('SELECT slabs.id,slabs.name FROM slabs_products'
                        . ' LEFT JOIN slabs ON slabs_products.slab_id = slabs.id where slabs_products.product_id = 55');
                
                if($action == 'a') {
                        $password   = $this->params['form']['password'];
                        $this->User->query("INSERT INTO products "
                                . "(service_id,name,to_show,active,auto_check,oprDown,min,max,automate_flag,monitor,created,reverse_flag)"
                                . " VALUES "
                                . "(7,'" . $name . "',0,1,1,0,10,10000,0,0,'" . $date . "',0)");

                        $res = $this->User->query("SELECT LAST_INSERT_ID() as insert_id from products limit 1");
                        $product_id = $res[0][0]['insert_id'];

                        $this->User->query("UPDATE products SET parent=" . $product_id . " WHERE id=" . $product_id);

                        $this->User->query("INSERT INTO vendors_commissions"
                                . " (vendor_id,product_id,discount_commission,tat_time,active,oprDown,cap_per_min,timestamp,is_deleted,updated_by)"
                                . " VALUES "
                                . "(56," . $product_id . ",'" . $pay1discount . "','1.0',1,0,-1,'" . $date . "',0,1)");

                        $temp_values = array();
                        foreach($slabs as $slab) {
                            $temp_values[] = "(" . $slab['slabs']['id'] . "," . $product_id . ",'" . $slab_discount . "','0.00',0)";
                        }

                        $this->User->query("INSERT INTO slabs_products"
                                . " (slab_id,product_id,percent,service_charge,service_tax)"
                                . " VALUES "
                                . implode(', ', $temp_values));

                        $this->C2d->query("INSERT INTO cash_payment_client"
                                . " (company_name,username,contact_person_name,contact_no,ivr_no,password,status,created_date,commission,product_id,bank_name,bank_branch_name,IFSC_code,bank_address,last_login,logo_url,cover_photo,description)"
                                . " VALUES "
                                . "('" . $name . "','" . $username . "','" . $contact_person . "','" . $contact_no . "','" . $ivr_no . "','" . md5($password) . "',1,'" . $date . "','" . $pay1discount . "'," . $product_id . ",'" . $bankname . "','" . $branchname . "','" . $ifsccode . "','" . $address . "','0000-00-00 00:00:00','" . $logo_url . "','" . $cover_photo . "','" . $description . "')");

                        $this->Session->setFlash('Wholesaler has been added successfully !!!');
                } else {
                    
                        $product_id = $this->params['form']['product_id'];
                        $this->User->query("UPDATE products"
                                . " SET name='" . $name
                                . "' WHERE id=" . $product_id);
                        
                        $this->User->query("UPDATE vendors_commissions"
                                . " SET discount_commission='" . $pay1discount
                                . "' WHERE product_id=" . $product_id);
                        
                        $update_batch = "UPDATE slabs_products SET percent = (CASE slab_id";
                        foreach($slabs as $slab) {
                                $update_batch .= " WHEN '" . $slab['slabs']['id'] . "' THEN '" . $slab_discount . "'";
                        }
                        $update_batch .= " END)"
                                . " WHERE product_id=" . $product_id;
                        $this->User->query($update_batch);
                        
                        $this->C2d->query("UPDATE cash_payment_client"
                                . " SET company_name='" . $name . "',username='" . $username . "',contact_person_name='" . $contact_person . "',contact_no='" . $contact_no . "',ivr_no='" . $ivr_no . "',commission='" . $pay1discount . "',bank_name='" . $bankname . "',bank_branch_name='" . $branchname . "',IFSC_code='" . $ifsccode . "',bank_address='" . $address . "',logo_url='" . $logo_url . "',cover_photo='" . $cover_photo . "',description='" . $description
                                . "' WHERE product_id=" . $product_id);
                        
                        $this->Session->setFlash('Wholesaler details has been updated successfully !!!');
                }

                $this->redirect('listws');
	}
        
        function checkUnique() {
            
                $this->autoRender = false;
                
                $id = $this->params['form']['id'];
                $username = strtolower($this->params['form']['username']);
                
                $res = $this->C2d->query("SELECT * FROM cash_payment_client WHERE username = '" . $username . "' AND status=1 AND id !=" . $id);
                
                return json_encode(count($res));
        }
        
        function listws() {
            
                $listWS = $this->C2d->query('SELECT * FROM cash_payment_client ORDER BY 1 DESC');
                
                $this->set('listWS', $listWS);
                $this->layout = 'wsregister';
        }
}

?>