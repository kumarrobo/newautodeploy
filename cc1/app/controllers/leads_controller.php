<?php

class LeadsController extends AppController {

    var $name = 'Leads';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator', 'GChart', 'Csv', 'NumberToWord');
    var $components = array('RequestHandler', 'Shop', 'Invoice', 'Documentmanagement', 'Scheme', 'General', 'Serviceintegration');
    var $uses = array('User', 'Slaves');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
        
        $parent_methods = array('assignLead','assignLeadData','format','displayData','report');
        $method = explode('/', $_SERVER['REQUEST_URI']);
        
        if (in_array($method[2], $parent_methods)) {
            if (!empty($_SESSION['Auth'])) {
                $access = $this->Slaves->query("SELECT * FROM leads_acl WHERE parent_user_id = '{$_SESSION['Auth']['User']['id']}'");
                $this->set('access', $access);
            }
            if (empty($access)) { die('Parent User Access Required'); }
        }
    }

    function index() {
        $leads = $this->Slaves->query("SELECT l.id,l.name,l.email,l.phone,l.lead_state FROM `leads_new` AS l WHERE `status` = 16");
        $this->set('leads', $leads);
    }

    function salesList() {
        $term = $_GET['term'];
        $salesmen_name = $this->Slaves->query("SELECT salesmen.name AS label,salesmen.id AS value from salesmen JOIN distributors ON ( distributors.id = salesmen.dist_id AND distributors.mobile != salesmen.mobile ) WHERE salesmen.name LIKE '%" . $term . "%' ");
        $salesmen = array();
        for ($i = 0; $i < count($salesmen_name); $i++) {
            $salesmen[] = $salesmen_name[$i]['salesmen'];
        }
        echo json_encode($salesmen);
        die;
    }

    function leadList() {
            
            if ($this->params['form']['filter']) {
                
                    $this->autoRender = FALSE;

                    $fromdate = $this->params['data']['fromdate'] ? date('Y-m-d H:i:s', strtotime($this->params['data']['fromdate'])) : date('Y-m-d', strtotime("-7 days"));
                    $todate   = $this->params['data']['todate']   ? date('Y-m-d H:i:s', strtotime($this->params['data']['todate']))   : date('Y-m-d');

                    is_numeric($this->params['data']['leadtag']) && $tag    = 'AND comments_new.tag_id = "' . $this->params['data']['leadtag'] . '"';
                    $this->params['data']['mobile'] != ''        && $mobile = 'AND leads_new.phone     = "' . $this->params['data']['mobile'] . '"';
                    
                    if (is_numeric($this->params['data']['user']) && !empty($access)) {
                            $assign = 'AND assign_to = "' . $this->params['data']['user'] . '"';
                    } else if (empty($access)) {
                            $assign = 'AND assign_to = "' . $_SESSION['Auth']['User']['id'] . '"';
                    }
                    
                    date('Y-m-d H:i:s', strtotime($fromdate)) > date('Y-m-d H:i:s', strtotime($todate)) && $msg = "* From date cannot be greater than To date !!!";
                    round((strtotime($todate) - strtotime($fromdate)) / (60 * 60 * 24)) > 30            && $msg = "* Date range cannot b greater than 30 days !!!";
                    
                    if(!$this->General->dateValidate($fromdate) && empty($fromdate)){
                        $msg= "Invalid From Date";
                    }
                     if(!$this->General->dateValidate($todate) && empty($todate) ){
                        $msg= "Invalid To Date";
                    }
                    if(!$this->General->mobileValidate($mobile) == '1' && !empty($mobile)){
                       $msg="Invalid Mobile number";
                    }
                    
                    
                    if (!$msg) {
                            $result = $this->Slaves->query("SELECT * FROM (SELECT leads_new.id, creation_date, assigned_datetime, shop_name, locator_state.name state_name,phone,alternate_no, lav.lead_values lead_source,lav1.lead_values lead_status,users.name user_name,comments_new.created_at,comments_new.comment, taggings_new.name tag, pin_code, followup_date FROM leads_new
                                                LEFT JOIN locator_area ON (leads_new.pin_code = locator_area.pincode AND locator_area.toShow = 1)
                                                LEFT JOIN locator_city ON (locator_city.id = locator_area.city_id AND locator_city.toShow = 1)
                                                LEFT JOIN locator_state ON (locator_state.id = locator_city.state_id AND locator_state.toShow = 1)
                                                LEFT JOIN lead_attributes_values lav ON (lav.id = leads_new.lead_source)
                                                LEFT JOIN lead_attributes_values lav1 ON (lav1.id = leads_new.lead_state)
                                                JOIN users ON (users.id = leads_new.assign_to)
                                                LEFT JOIN comments_new ON (comments_new.ref_id = leads_new.id)
                                                LEFT JOIN taggings_new ON (taggings_new.id = comments_new.tag_id)
                                                WHERE assign_to > '0' $assign AND leads_new.assigned_datetime >= '" . $fromdate . "' AND leads_new.assigned_datetime <= '" . $todate . "' $mobile $tag 
                                                ORDER BY comments_new.id DESC , leads_new.id DESC, leads_new.pin_code ASC) txns GROUP BY txns.id ORDER BY txns.assigned_datetime DESC");
                            
                            $data['priority'] = array();
                            $data['result'] = array();
                            foreach($result as $value){

                                $follow_up_date = date("Y-m-d H:i:s", strtotime($value['txns']['followup_date']));
                                $two_hr_before_time = date('Y-m-d H:i:s', strtotime($follow_up_date . " -2 hours"));
                                $current_time = date('Y-m-d H:i:s');

                                if ($follow_up_date != "0000-00-00 00:00:00") {
                                    if (strtotime($current_time) <= strtotime($follow_up_date) && strtotime($current_time) >= strtotime($two_hr_before_time)) {

                                        $data['priority'][] = $value;
                                    } else {
                                        $data['result'][] = $value;
                                    }
                                } else {
                                    $data['result'][] = $value;
                                }
                            }

                            $noRetailers = $this->Slaves->query("SELECT leads_new.pin_code, COUNT(DISTINCT retailers.id) no_of_retailers FROM retailers
                                                JOIN retailers_location ON (retailers.user_id = retailers_location.user_id AND retailers_location.area_id != 0 AND retailers.toshow = 1)
                                                JOIN locator_area ON (retailers_location.area_id = locator_area.id AND locator_area.toShow = 1) 
                                                JOIN leads_new ON (locator_area.pincode = leads_new.pin_code AND leads_new.assign_to != 0 $assign AND leads_new.pin_code != 0 $mobile) 
                                                LEFT JOIN comments_new ON (comments_new.ref_id = leads_new.id $tag)   
                                                WHERE leads_new.assigned_datetime >= '$fromdate' AND leads_new.assigned_datetime <= '$todate' 
                                                GROUP BY leads_new.pin_code ORDER BY leads_new.pin_code ASC");

                            foreach ($noRetailers as $nr) {
                                    $no_of_retailers[$nr['leads_new']['pin_code']] = $nr[0]['no_of_retailers'];
                            }

                            $noDistributor = $this->Slaves->query("SELECT leads_new.pin_code, COUNT(DISTINCT distributors.id) no_of_distributors FROM distributors
                                                JOIN retailers_location ON (distributors.user_id = retailers_location.user_id AND retailers_location.area_id != 0 AND distributors.toshow = 1)
                                                JOIN locator_area ON (retailers_location.area_id = locator_area.id AND locator_area.toShow = 1)
                                                JOIN leads_new ON (locator_area.pincode = leads_new.pin_code AND leads_new.assign_to != 0 $assign AND leads_new.pin_code != 0 $mobile)
                                                LEFT JOIN comments_new ON (comments_new.ref_id = leads_new.id $tag) 
                                                WHERE leads_new.assigned_datetime >= '$fromdate' AND leads_new.assigned_datetime <= '$todate' 
                                                GROUP BY leads_new.pin_code ORDER BY leads_new.pin_code ASC");

                            foreach ($noDistributor as $nd) {
                                    $no_of_distributors[$nd['leads_new']['pin_code']] = $nd[0]['no_of_distributors'];
                            }
                    }
                    if (!$msg) {
                            $res_array = array('status' => 'success', 'description' => array('lead_list' => $data, 'no_of_distributors' => $no_of_distributors, 'no_of_retailers' => $no_of_retailers));
                    } else {
                            $res_array = array('status' => 'failure', 'description' => $msg);
                    }
                    echo json_encode($res_array); 
            }
            $assignlist = $this->Slaves->query("SELECT users.id, users.name FROM users "
                            . "JOIN user_groups ON (user_groups.user_id = users.id) "
                            . "WHERE user_groups.group_id = '". LEAD_OPERATIONS ."' AND users.name != ''");
            
            $tagging = $this->Slaves->query("SELECT * FROM taggings_new WHERE parent_id = '0'");
            $this->set('tagging', $tagging);
            $this->set('users', $assignlist);
    }

    function customerDetail($id) {
        
            empty($access) && $filter_access = " AND leads_new.assign_to = '{$_SESSION['Auth']['User']['id']}' ";
            
            $leads = $this->Slaves->query("SELECT *,locator_city.name AS cityname FROM `leads_new` 
                                        LEFT JOIN locator_area ON (locator_area.pincode = leads_new.pin_code )
                                        LEFT JOIN locator_city ON locator_city.id = locator_area.city_id WHERE leads_new.id = $id $filter_access GROUP BY leads_new.id");

            $retailer_count = $this->Slaves->query("SELECT COUNT(DISTINCT retailers.id) count FROM retailers
                                                    JOIN retailers_location ON ( retailers_location.user_id = retailers.user_id AND retailers_location.area_id !=0 AND retailers.toshow = 1) 
                                                    JOIN locator_area ON ( locator_area.id = retailers_location.area_id AND locator_area.toShow = 1 AND locator_area.pincode != 0)  
                                                    WHERE locator_area.pincode =  '" . $leads[0]['leads_new']['pin_code'] . "'");

            $dist = $this->Slaves->query("SELECT id, company, AVG(topup_sold) average, retailers, active_flag FROM (
                                            SELECT d.id, d.company, users_logs.topup_sold, users_logs.retailers, d.active_flag FROM distributors d 
                                            JOIN retailers_location ON ( retailers_location.user_id = d.user_id AND retailers_location.area_id != 0 ) 
                                            JOIN locator_area ON (locator_area.id = retailers_location.area_id AND locator_area.toShow = 1) 
                                            JOIN users_logs ON (users_logs.user_id = d.user_id) 
                                            WHERE locator_area.pincode =  '" . $leads[0]['leads_new']['pin_code'] . "'
                                            AND users_logs.date >=  '" . date('Y-m-d', strtotime("-1 months")) . "'
                                            AND users_logs.date <=  '" . date('Y-m-d') . "'
                                            ORDER BY users_logs.user_id DESC) dist_details GROUP BY id");

            $distributors = $this->Slaves->query("SELECT distributors.id, COUNT(retailers.id) count, SUM(rel.amount) pincode_sale FROM distributors
                                                JOIN retailers ON (retailers.parent_id = distributors.id)
                                                JOIN retailers_location ON (retailers_location.user_id = retailers.user_id AND retailers_location.area_id != 0)
                                                JOIN locator_area ON (locator_area.id = retailers_location.area_id AND locator_area.toShow = 1)
                                                JOIN (SELECT ret_user_id, SUM(amount) amount FROM retailer_earning_logs rel
                                                            WHERE rel.date >= '" . date('Y-m-d', strtotime("-1 months")) . "' AND rel.date <= '" . date('Y-m-d') . "' AND rel.type IN (4,16,17)
                                                            GROUP BY ret_user_id) rel ON (rel.ret_user_id = retailers.user_id)
                                                WHERE locator_area.pincode = '" . $leads[0]['leads_new']['pin_code'] . "' GROUP BY distributors.id;");
//            $dist_details = array('100' => array('retailers' => 1,'pincode_sale' => 946)); $pincodesale = 0;
            $dist_details = array();        
            foreach ($distributors as $distributor) {
                    $dist_details[$distributor['distributors']['id']]['retailers']    = $distributor[0]['count'];
                    $dist_details[$distributor['distributors']['id']]['pincode_sale'] = $distributor[0]['pincode_sale'];
                    $pincodesale += $dist_details[$distributor['distributors']['id']]['pincode_sale'];
            }
            $pincodesale = empty($pincodesale) ? '0' : $pincodesale;
            
            $dailysale = $this->Slaves->query("SELECT AVG( topup_sold ) daily_sale FROM  `users_logs` ul JOIN distributors d ON d.user_id = ul.user_id
                                               JOIN retailers_location rl ON d.user_id = rl.user_id JOIN locator_area la ON rl.area_id = la.id 
                                               WHERE la.pincode = '". $leads[0]['leads_new']['pin_code'] ."' AND
                                               ul.date >= '".date('Y-m-d', strtotime("-1 months"))."' AND  ul.date  <= '".date('Y-m-d')."'");

            $tagging  = $this->Slaves->query("SELECT * FROM  `taggings_new` WHERE parent_id = '0'");
            $status   = $this->Slaves->query("SELECT * FROM  `lead_attributes_values` JOIN lead_attributes_type ON lead_attributes_type.id = lead_attributes_values.type_id WHERE lead_attributes_values.type_id = 1");
            $comments = $this->Slaves->query("SELECT comments_new.id, users.name AS commented_user, comments_new.created_at, taggings_new.name AS tag FROM  `comments_new` 
                                                JOIN leads_new ON leads_new.id = comments_new.ref_id
                                                JOIN users ON users.id = comments_new.cc_id
                                                JOIN taggings_new ON taggings_new.id = comments_new.tag_id
                                                WHERE  `ref_id` =  '$id' AND comments_new.module_id =  '".LEAD_OPERATIONS."'
                                                AND  `cc_id` =  '" . $_SESSION['Auth']['User']['id'] . "' ORDER BY created_at ASC ");

            $this->set('id', $id);
            $this->set('distributor', $dist);
            $this->set('dist_details', $dist_details);
            $this->set('tagging', $tagging);
            $this->set('status', $status);
            $this->set('comments', $comments);
            $this->set('leaddetail', $leads[0]['leads_new']);
            $this->set('city', $leads[0]['locator_city']['cityname']);
//            $this->set('daily_sale', $dailysale[0][0]['daily_sale']);
            $this->set('daily_sale', $pincodesale);
            $this->set('retailer_count', $retailer_count[0][0]['count']);
    }

    function leadDetail() {
        $this->autoRender = false;

        if ($this->params['form']['maintagflag']) {
            $tagging = array_map(function($value) {
                return array('id' => $value['taggings_new']['id'], 'name' => $value['taggings_new']['name']);
            }, $this->Slaves->query("SELECT * FROM  `taggings_new` WHERE parent_id = '" . $this->params['form']['maintagdata'] . "'"));
            echo json_encode($tagging);
        }
        if ($this->params['form']['updatecomment']) {
            
            if($this->params['form']['commentdata']['leadid'] == '' || $this->params['form']['commentdata']['maintag'] == '0' || $this->params['form']['commentdata']['subtag'] == '0' || $this->params['form']['commentdata']['statuschange'] == '0' || $this->params['form']['commentdata']['comment'] == ''){
                 $msg = "All are required fields !!!";
            }else{
                $this->User->query("INSERT INTO `comments_new`( `ref_id`, `module_id`, `tag_id`, `subtag_id`, `comment`, `cc_id`, `created_date`, `created_at`) VALUES ('{$this->params['form']['commentdata']['leadid']}','".LEAD_OPERATIONS."','{$this->params['form']['commentdata']['maintag']}','{$this->params['form']['commentdata']['subtag']}','{$this->params['form']['commentdata']['comment']}','{$_SESSION['Auth']['User']['id']}','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "')");
                $this->User->query("UPDATE `leads_new` SET `lead_state`='{$this->params['form']['commentdata']['statuschange']}' , `followup_date`='" . date('Y-m-d H:i:s', strtotime($this->params['form']['commentdata']['followupdate'])) . "' WHERE id= '{$this->params['form']['commentdata']['leadid']}'");
                $result = $this->Slaves->query("SELECT comments_new.id, users.name AS commented_user, comments_new.created_at, taggings_new.name AS tag FROM  `comments_new` 
                                                JOIN leads_new ON leads_new.id = comments_new.ref_id JOIN users ON users.id = comments_new.cc_id
                                                JOIN taggings_new ON taggings_new.id = comments_new.tag_id  WHERE  `ref_id` =  '{$this->params['form']['commentdata']['leadid']}'
                                                AND comments_new.module_id =  '".LEAD_OPERATIONS."'
                                                AND  `cc_id` =  '" . $_SESSION['Auth']['User']['id'] . "'  ORDER BY created_at ASC ");
                
            }
            if (!$msg) {
                    $res_array = array('status' => 'success', 'description' => $result);
            } else {
                    $res_array = array('status' => 'failure', 'description' => $msg);
            }
            echo json_encode($res_array);
        }
        if ($this->params['form']['edit']) {
            if (!empty($this->params['data']['name']) && !preg_match('/^[a-zA-Z0-9 .]+$/', $this->params['data']['name'])) {
                $result['status'] = 'failed';
                $result['description'] = 'Enter Valid Name !!!';
            } else if (!empty($this->params['data']['alt_mob_no']) && !is_numeric($this->params['data']['alt_mob_no'])) {
                $result['status'] = 'failed';
                $result['description'] = 'Mobile Number Is Not Valid !!!';
            } else if (!empty($this->params['data']['email']) && !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $this->params['data']['email'])) {
                $result['status'] = 'failed';
                $result['description'] = 'Email Address Is Not Valid !!!';
            } else if (empty($this->params['data']['shop_name']) || !preg_match('/^[a-zA-Z0-9 .]+$/', $this->params['data']['shop_name'])) {
                $result['status'] = 'failed';
                $result['description'] = 'Enter valid Shop name !!!';
            } else if (!empty($this->params['data']['current_business']) && !preg_match('/^[a-zA-Z0-9 .]+$/', $this->params['data']['current_business'])) {
                $result['status'] = 'failed';
                $result['description'] = 'Enter valid Current Business !!!';
            }
            if($result['status'] != 'failed'){ 
                    $this->User->query("UPDATE `leads_new` SET  `name`= '{$this->params['data']['name']}',`shop_name`= '{$this->params['data']['shop_name']}',`email`= '{$this->params['data']['email']}',`phone`= '{$this->params['data']['phone']}',`current_business`= '{$this->params['data']['current_business']}',`city`= '{$this->params['data']['city']}', alternate_no = '{$this->params['data']['alt_mob_no']}',`updated_at`='" . date('Y-m-d H:i:s') . "' WHERE `id` = '{$this->params['data']['id']}'");
                    $result['status'] = 'success';
                    $result['description'] = 'Saved Successfully';
            }
            echo json_encode($result); 
        }
    }

    function report() {
            
        
            if ($this->params['form']['filter']) {

            $from_date = date('Y-m-d', strtotime($this->params['form']['filter'] ? $this->params['data']['fromdate'] : '-7 days'));
            $to_date   = $this->params['form']['filter'] ? date('Y-m-d', strtotime($this->params['data']['todate'])) : date('Y-m-d');
            
            if(!$this->General->dateValidate($from_date)){
                $error = array('status' => 'failure', 'description' => 'Invalid From Date!!!');
                echo json_encode($error);die;
            }
            if(!$this->General->dateValidate($to_date)){
            $error = array('status' => 'failure', 'description' => 'Invalid To Date!!!');
                echo json_encode($error);die;
            }

            $ids = $this->Slaves->query("SELECT id FROM lead_attributes_values WHERE type_id = '1'");

                $lead_source = array();
                foreach ($ids as $id) {
                        $lead_source[] = $this->Slaves->query("SELECT lav.lead_values lead_source, COUNT(leads_new.id) count, lead_state AS status "
                                . "FROM leads_new "
                                . "JOIN lead_attributes_values lav ON (leads_new.lead_source = lav.id) "
                                . "WHERE creation_date >= '" . $from_date . "' AND creation_date <='" . $to_date . "' AND assign_to > 0 AND lead_state = ' {$id['lead_attributes_values']['id']} ' "
                                . "GROUP BY lead_source");
                }

                $pending = $this->Slaves->query("SELECT lav.lead_values lead_source, COUNT(leads_new.id) count FROM leads_new 
                            JOIN lead_attributes_values lav ON (leads_new.lead_source = lav.id)
                            WHERE creation_date >= '" . $from_date . "' AND  creation_date <= '" . $to_date . "' AND assign_to = 0
                            GROUP BY lead_source");

                $pending_lead = array();
                foreach ($pending as $lead) {
                        !isset($pending_lead[$lead['lav']['lead_source']]) && $pending_lead[$lead['lav']['lead_source']] = 0;
                        $pending_lead[$lead['lav']['lead_source']] = $lead[0]['count'];
                }

                $tag = $this->Slaves->query("SELECT lead_id,name Tag, COUNT( id ) Count,created_at FROM "
                        . "( SELECT leads_new.id as lead_id,taggings_new.name, taggings_new.id,comments_new.created_at FROM leads_new "
                        . "JOIN comments_new ON ( leads_new.id = comments_new.ref_id ) "
                        . "JOIN taggings_new ON ( comments_new.tag_id = taggings_new.id ) "
                        . "GROUP BY leads_new.id ORDER BY comments_new.id ASC , leads_new.id DESC)tag_data GROUP BY id");

                $user = array();
                foreach ($ids as $id) {
                        $user[] = $this->Slaves->query("SELECT users.name, COUNT(leads_new.id) count, leads_new.lead_state AS status FROM leads_new JOIN users ON (users.id = leads_new.assign_to) WHERE creation_date BETWEEN '" . date('Y-m-d', strtotime($this->params['data']['fromdate'])) . "' AND '" . date('Y-m-d', strtotime($this->params['data']['todate'])) . "' AND assign_to > 0 AND lead_state = '{$id['lead_attributes_values']['id']}' GROUP BY assign_to;");
                }

                $user_wise = array();
                foreach ($user as $usr) {
                        foreach ($usr as $users) {
                                !isset($user_wise[$users['users']['name']]) && $user_wise[$users['users']['name']] = array('1' => 0, '3' => 0, '48' => 0, '49' => 0, '0' => 0);
                                if ($users['leads_new']['status'] != '2') {
                                        $user_wise[$users['users']['name']][$users['leads_new']['status']] = $users[0]['count'];
                                        $user_wise[$users['users']['name']]['0'] += $users[0]['count'];
                                }
                        }
                }

                $data = array();
                foreach ($lead_source as $source) {
                        foreach ($source as $lead) {
                                !isset($data[$lead['lav']['lead_source']]) && $data[$lead['lav']['lead_source']] = array('1' => 0, '3' => 0, '48' => 0, '49' => 0, '0' => 0);
                                if ($lead['leads_new']['status'] != '2') {
                                        $data[$lead['lav']['lead_source']][$lead['leads_new']['status']] = $lead[0]['count'];
                                }
                        }
                }

                foreach (array_keys($data) as $dt) {
                        foreach (array_keys($pending_lead) as $pnd) {
                                if ($dt == $pnd) {
                                        $data[$dt][0] = $pending_lead[$pnd];
                                }
                        }
                        $data[$dt]['sum'] = array_sum($data[$dt]);
                }

                echo json_encode(array('status'=>'success','user_wise' => $user_wise, 'tag_data' => $tag, 'lead_source' => $data), 1);

                $this->autoRender = false;
            }
    }

    function assignLead() {
        
            $params = $this->params['form'];
        
            $from_date = date('Y-m-d', strtotime($params['from_date'] == '' ? '-7 days' : $params['from_date']));
            $to_date   = $params['to_date'] == '' ? date('Y-m-d') : date('Y-m-d', strtotime($params['to_date']));
            
            $date_diff = floor((strtotime($to_date) - strtotime($from_date)) / (60 * 60 * 24));
            if(!$this->General->dateValidate($from_date)){
                $this->Session->setFlash("<b>Error</b> : Invalid From date");
            }
            if(!$this->General->dateValidate($to_date)){
                $this->Session->setFlash("<b>Error</b> : Invalid To date");
            }
           
            if($from_date <= $to_date){
            if ($date_diff <= 7) {
                
                    $lead_id  = $params['lead_id'];
                    $stateid  = $params['stateid'];
                    $sourceid = $params['sourceid'];
                    $marketid = $params['marketid'];

                    $stateid  != '' && $state  = " AND ls.id = '" . $stateid . "' ";
                    $sourceid != '' && $source = " AND lav.id = '" . $sourceid . "' ";
                    if(!is_numeric($stateid) && !empty($stateid) ){
                      $this->Session->setFlash("<b>Error</b> : Invalid State");
                    }
                    if(!is_numeric($sourceid) && !empty($sourceid)){
                      $this->Session->setFlash("<b>Error</b> : Invalid Source");
                    }
                    if(!is_numeric($marketid) && !empty($marketid)){
                      $this->Session->setFlash("<b>Error</b> : Invalid Market");
                    }
                    
                    if ($marketid == 0) {
                        
                            $query_where = "SELECT ln.id, ln.creation_date, ln.shop_name, ls.name, ln.pin_code, lav.lead_values FROM leads_new ln 
                                    LEFT JOIN locator_area la ON (ln.pin_code = la.pincode AND la.toShow = 1 AND ln.pin_code != 0)
                                    LEFT JOIN lead_attributes_values lav ON (ln.lead_source = lav.id)
                                    LEFT JOIN locator_city lc ON (la.city_id=lc.id AND lc.toShow = 1)
                                    LEFT JOIN locator_state ls ON (ls.id = lc.state_id AND ls.toShow = 1)  
                                    WHERE ln.assign_to = 0 AND ln.creation_date >= '" . $from_date . "' AND ln.creation_date <= '" . $to_date . "' $source $state
                                    GROUP BY ln.id DESC";
                    } else {

                            if ($marketid == 2) {
                                    $not_available = "LEFT JOIN locator_area la1 ON (ln.pin_code = la1.pincode AND la1.toShow = 1)";
                                    $dist = "AND d.id IS NULL";
                            }
                            
                            $query_where = "SELECT DISTINCT ln.id, ln.creation_date, ln.shop_name, ls.name, ln.pin_code, lav.lead_values 
                            FROM distributors d
                            JOIN retailers_location rl ON (d.user_id = rl.user_id)
                            JOIN locator_area la ON ( rl.area_id = la.id AND la.toShow = 1)
                            ".($marketid == 2 ? "RIGHT" : "")." JOIN leads_new ln ON (ln.pin_code = la.pincode)
                            $not_available 
                            LEFT JOIN locator_city lc ON (".($marketid == 2 ? "la1" : "la").".city_id=lc.id AND lc.toShow = 1) 
                            LEFT JOIN locator_state ls ON ( ls.id = lc.state_id AND ls.toShow = 1)
                            LEFT JOIN lead_attributes_values lav ON (ln.lead_source = lav.id)
                            WHERE ln.assign_to = 0 AND ln.pin_code!=0 $dist $state $source   AND ln.creation_date >= '" . $from_date . "' AND ln.creation_date <= '" . $to_date . "'
                            GROUP BY ln.id DESC";
                    }
                    
                    $dist_count = $this->Slaves->query("SELECT leads_new.pin_code, COUNT(DISTINCT distributors.id) no_of_distributors FROM distributors "
                            . "JOIN retailers_location ON ( distributors.user_id = retailers_location.user_id AND retailers_location.area_id != 0 AND distributors.toshow = 1) "
                            . "JOIN locator_area ON ( retailers_location.area_id = locator_area.id AND locator_area.toShow = 1) "
                            . "JOIN leads_new ON ( locator_area.pincode = leads_new.pin_code AND leads_new.assign_to = 0 AND leads_new.pin_code != 0 ) "
                            . "WHERE leads_new.creation_date >=  '" . $from_date . "' AND leads_new.creation_date <=  '" . $to_date . "' "
                            . "GROUP BY leads_new.pin_code");
                    $pincode_dist_count = array();
                    foreach ($dist_count as $no_of_dist) {
                            $pincode_dist_count[$no_of_dist['leads_new']['pin_code']] = $no_of_dist[0]['no_of_distributors'];
                    }

                    $ret_count = $this->Slaves->query("SELECT leads_new.pin_code, COUNT(DISTINCT retailers.id ) no_of_retailers FROM retailers "
                            . "JOIN retailers_location ON (retailers.user_id = retailers_location.user_id AND retailers_location.area_id != 0 AND AND retailers.toshow = 1) "
                            . "JOIN locator_area ON (retailers_location.area_id = locator_area.id AND locator_area.toShow = 1) "
                            . "JOIN leads_new ON (locator_area.pincode = leads_new.pin_code AND leads_new.assign_to = 0 AND leads_new.pin_code != 0) "
                            . "WHERE leads_new.creation_date >=  '" . $from_date . "' AND leads_new.creation_date <=  '" . $to_date . "' "
                            . "GROUP BY leads_new.pin_code");
                    $pincode_ret_count = array();
                    foreach ($ret_count as $no_of_ret) {
                            $pincode_ret_count[$no_of_ret['leads_new']['pin_code']] = $no_of_ret[0]['no_of_retailers'];
                    }

                    $assign = $this->Slaves->query("SELECT users.id, users.name FROM users "
                            . "JOIN user_groups ON (user_groups.user_id = users.id) "
                            . "WHERE user_groups.group_id = '". LEAD_OPERATIONS ."' AND users.name != ''");
                    
                    $leads = $this->paginate_query($query_where, 30);

                    $this->set('leads', $leads);
                    $this->set('id', $lead_id);
                    $this->set('assign', $assign);
                    $this->set('pincode_dist_count', $pincode_dist_count);
                    $this->set('pincode_ret_count', $pincode_ret_count);
            } else {
                    $this->Session->setFlash("<b>Error</b> :  Date range exceeding 7 days.");
            }
            }else{
                $this->Session->setFlash("<b>Error</b> :  From date should be less than To date.");
            }

            $state_name = $this->Slaves->query("SELECT DISTINCT ls.id, ls.name FROM leads_new ln
                    LEFT JOIN locator_area la ON (ln.pin_code = la.pincode AND la.toShow = 1 AND ln.pin_code != '')
                    JOIN locator_city lc ON (la.city_id = lc.id AND lc.toShow = 1)
                    JOIN locator_state ls ON (ls.id = lc.state_id AND ls.toShow = 1)
                    WHERE ln.assign_to = 0 AND ln.creation_date >= '$from_date' AND ln.creation_date <= '$to_date'");
            
            $source_name = $this->Slaves->query("SELECT id, lead_values FROM lead_attributes_values WHERE type_id = 2");
            
            $this->set('marketid', $marketid);
            $this->set('from_date', $from_date);
            $this->set('to_date', $to_date);
            $this->set('stateid', $stateid);
            $this->set('sourceid', $sourceid);
            $this->set('state', $state_name);
            $this->set('source', $source_name);
    }

    function assignLeadData() {
        $this->autoRender = FALSE;
        if ($this->RequestHandler->isPost()) {
            $lead_id = $this->params['form']['lead_id'];
            $assign_to = $this->params['form']['assign_to'];

            $res = $this->User->query("UPDATE leads_new SET  assign_to='" . $assign_to . "',assigned_datetime ='" . date('Y-m-d H:i:s') . "'  WHERE id IN ($lead_id) ");
            echo json_encode($res);
        }
    }

    function format() {
        //ajax call on displayData()
    }

    function displayData() {
        
            $this->autoRender = FALSE;

            if ($this->RequestHandler->isPost()) {

                    $array_record['status']  = 0;
                    $array_record['message'] = "Some Internal Error";
                    
                    $file = $_FILES['file']['name'];
                    
                    if (!empty($file)) {
                        
                            $allowedExtension = array("xls");
                            $getfileInfo      = pathinfo($file, PATHINFO_EXTENSION);

                            if (in_array($getfileInfo, $allowedExtension)) {
                                
                                    if ($getfileInfo == "xls") {
                                        
                                            if (!move_uploaded_file($_FILES['file']['tmp_name'], "/tmp/" . $file)) {
                                                    echo json_encode(array('message' => "Failed to move uploaded file", 'status' => 0));
                                                    die;
                                            }
                                            chmod("/tmp/" . $file, 777);

                                            $filepath = "/tmp/" . $file;
                                            App::import('Vendor', 'excel_reader2');
                                            $excel = new Spreadsheet_Excel_Reader($filepath, true);
                                            $data  = $excel->sheets[0]['cells'];
                                    } else {
                                        
                                            $file = fopen($_FILES['file']["tmp_name"], "r");
                                            while (!feof($file)) {
                                                    $data[] = fgetcsv($file, 1024);
                                            }
                                            fclose($file);
                                    }
                            } else {

                                    echo json_encode(array('message' => "Invalid File Format (only xls file allowed)", 'status' => 0));
                                    die;
                            }
                            $i=0;
                            $mobile_array = array();
                            foreach ($data as $key => $value) {
                                
                                    $lead_date   = date('Y-m-d', strtotime(trim($value[1])));
                                    $name        = trim($value[2]);
                                    $state       = trim($value[3]);
                                    $pincode     = trim($value[4]);
                                    $lead_source = trim($value[5]);
                                    $mobile_no   = trim($value[6]);
                                    $alt_mobile  = trim($value[7]);
                                    
                                    $result                = array();
                                    $result['status']      = 'success';
                                    $result['description'] = '';
                                    $lead_id               = '';
                                    
                                    $source = $this->User->query("SELECT id from lead_attributes_values where lead_values= '$lead_source' and type_id = 2");

                                    if ($lead_date != '1970-01-01') {
                                            if (!$this->General->dateValidate(str_replace(array('.', '/'), '-', $lead_date)) ) {
                                                    $result['status']      = 'failed';
                                                    $result['description'] = 'Enter Valid Date';
                                            } else if(strtotime ($lead_date) > strtotime(date('Y-m-d'))){
                                                    $result['status']      = 'failed';
                                                    $result['description'] = 'Invalid Date';
                                            } else if (!ctype_alnum(str_replace(array('_', '.'), '', $lead_source))) {
                                                    $result['status']      = 'failed';
                                                    $result['description'] = 'Enter Valid Lead source';
                                            } else if ($this->General->mobileValidate($mobile_no) == '1') {
                                                    $result['status']      = 'failed';
                                                    $result['description'] = 'Mobile Number Is Not Valid';
                                            } else if (empty($source)) {
                                                    $result['status']      = 'failed';
                                                    $result['description'] = 'Lead source is not valid';
                                            } else if (!empty($pincode) && strlen($pincode)!=6){
                                                    $result['status']      = 'failed';
                                                    $result['description'] = 'Invalid pincode';
                                            } else if(!empty($alt_mobile) && !is_numeric($alt_mobile)){
                                                    $result['status']      = 'failed';
                                                    $result['description'] = 'Invalid Alternate Mobile Number';
                                            } else if(!empty ($state) && !ctype_alpha($state)){
                                                    $result['status']      = 'failed';
                                                    $result['description'] = 'Invalid State';
                                            }else if(in_array ($mobile_no,$mobile_array)){
                                                    $result['status']      = 'failed';
                                                    $result['description'] = 'Duplicate Mobile Number';
                                            }
                
                                            $lead_id[0]['leads_new']['id']="";
                                            if ($result['status'] == 'success') {

                                                    $chk_mobile = $this->User->query("SELECT phone from leads_new where phone= '$mobile_no' and creation_date = '" . $lead_date . "'");

                                                    if (!empty($chk_mobile)) {
                                                            $result['status']      = 'failed';
                                                            $result['description'] = 'Mobile no. Exist';
                                                    } else if (!$this->User->query("INSERT INTO leads_new (creation_date,shop_name,state,pin_code,lead_source,phone,alternate_no) VALUES ('$lead_date','$name','$state','$pincode','{$source[0]['lead_attributes_values']['id']}','$mobile_no','$alt_mobile')")) {
                                                            $result['status']      = 'failed';
                                                            $result['description'] = 'Some Problem Occured';
                                                    }

                                                    $lead_id = $this->User->query("SELECT id from leads_new where phone = '$mobile_no' and creation_date = '" . $lead_date . "'");
                                            }

                                            $result_array = array('description' => $result['description'], 'lead_id' => $lead_id[0]['leads_new']['id'], 'creation_date' => $lead_date, 'name' => $name, 'state' => $state, 'pin_code' => $pincode, 'lead_source' => $lead_source, 'phone' => $mobile_no, 'alternate_no' => $alt_mobile);

                                            if ($result['status'] == 'success') {
                                                    $array_record['success'][] = $result_array;
                                            } else {
                                                    $array_record['fail'][]    = $result_array;
                                            }

                                            $array_record['status'] = 1;
                                    }
                                    $mobile_array[$i]      = $mobile_no;
                                    $i++;
                                    
                                            }
                    }
            
                    echo json_encode($array_record);
            }
    }

    function employeeDetails() {
        
            $params = $this->params['form'];
        
            $from_date = date('Y-m-d H:i:s', strtotime($params['fromdate'] == '' ? '-7 days' : $params['fromdate']));
            $to_date   = $params['todate'] == '' ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime($params['todate']));
            
            $diff = floor((strtotime($to_date) - strtotime($from_date)) / (60 * 60 * 24));
            if(!$this->General->dateValidate($from_date) && empty($from_date) ){
                $this->Session->setFlash("<b>Error</b> : Invalid From date");
            }
            if(!$this->General->dateValidate($to_date) && empty($to_date)){
                $this->Session->setFlash("<b>Error</b> : Invalid To date");
            }
            if ($diff <= 7) {
                
                    $emp_id = $params['emp_id'];
                    $page   = isset($this->params['form']['download']) ? $this->params['form']['download'] : "";

                    if (is_numeric($emp_id) && !empty($access)) {
                            $assign = " AND assign_to = '" . $emp_id . "'";
                    } else if (empty($access)) {
                            $assign = " AND assign_to = '" . $_SESSION['Auth']['User']['id'] . "'";
                    }
                            
                    $lead = $this->paginate_query("SELECT * FROM (SELECT leads_new.id, leads_new.creation_date, leads_new.assigned_datetime,"
                            . "comments_new.comment, leads_new.shop_name, locator_state.name state_name, leads_new.phone mobile,"
                            . "lav.lead_values lead_source, lav1.lead_values lead_state, leads_new.assign_to assign_to,users.name assign_name, tn.name tag,"
                            . "tn1.name sub_tag FROM leads_new LEFT JOIN comments_new ON (comments_new.ref_id = leads_new.id) "
                            . "LEFT JOIN locator_area ON (locator_area.pincode = leads_new.pin_code AND locator_area.toShow = 1) "
                            . "LEFT JOIN locator_city ON (locator_city.id = locator_area.city_id AND locator_city.toShow = 1) "
                            . "LEFT JOIN locator_state ON (locator_state.id = locator_city.state_id AND locator_state.toShow = 1) "
                            . "LEFT JOIN lead_attributes_values lav ON (lav.id = leads_new.lead_source) "
                            . "LEFT JOIN lead_attributes_values lav1 ON (lav1.id = leads_new.lead_state) "
                            . "JOIN users ON (users.id = leads_new.assign_to) "
                            . "LEFT JOIN taggings_new tn ON (tn.id = comments_new.tag_id) "
                            . "LEFT JOIN taggings_new tn1 ON (tn1.id = comments_new.subtag_id) "
                            . "WHERE leads_new.assign_to > 0 AND leads_new.assigned_datetime >= '" . $from_date . "' AND "
                            . "leads_new.assigned_datetime <= '" . $to_date . "' $assign "
                            . "ORDER BY leads_new.id DESC , comments_new.id DESC) lead_data GROUP BY id ORDER BY assigned_datetime DESC");
                    
                    if ($page == 'download') {
                            $this->set('page', $page);
                            App::import('Helper', 'csv');
                            $this->layout = null;
                            $this->autoLayout = false;
                            $csv = new CsvHelper();
                            $line = array('SR NO', 'Lead Date', 'Assigned Date n Time', 'Last Comment Date n  Time', 'Name', 'State', 'Mobile No', 'Lead Source', 'Lead Status', 'Assign', 'Tagging', 'Sub Tagging');
                            $csv->addRow($line);
                            $i = 1;
                            foreach ($lead as $data) {
                                    $line = array($i, $data['lead_data']['creation_date'], $data['lead_data']['assigned_datetime'], $data['lead_data']['comment'], $data['lead_data']['shop_name'], $data['lead_data']['state_name'], $data['lead_data']['mobile'], $data['lead_data']['lead_source'], $data['lead_data']['lead_state'], $data['lead_data']['assign_to'], $data['lead_data']['tag'], $data['lead_data']['sub_tag']);
                                    $csv->addRow($line);
                                    $i++;
                            }
                            ob_clean();
                            echo $csv->render("invoice_" . $from_date . "_" . $to_date . ".csv");
                            exit;
                    }
            } else {
                    $this->Session->setFlash("<b>Error</b> : Date range exceeding 7 days.");
            }
            
            $emp_name = $this->Slaves->query("SELECT users.id, users.name FROM users "
                            . "JOIN user_groups ON (user_groups.user_id = users.id) "
                            . "WHERE user_groups.group_id = '". LEAD_OPERATIONS ."' AND users.name != ''");
            
            $this->set('emp_name', $emp_name);
            $this->set('name', $emp_id);
            $this->set('from_date', date('Y-m-d H:i', strtotime($from_date)));
            $this->set('to_date', date('Y-m-d H:i', strtotime($to_date)));
            $this->set('lead', $lead);
    }

}
