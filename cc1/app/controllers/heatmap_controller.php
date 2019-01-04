<?php

class HeatmapController extends AppController {

	var $name = 'Heatmap';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv');
	var $components = array('RequestHandler','Shop');
	var $uses = array('Retailer','Slaves');

	function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('*');
	    set_time_limit(0);
	    ini_set("memory_limit", "512M");
	}

        public function index($state = 'All', $city = 'All', $area = 'All') {

            $this->autoLayout = FALSE;

            $states = $this->Slaves->query('SELECT * FROM locator_state where toshow = 1');
            $this->set('states', $states);

            if($state == 'All') {

                $res = $this->Slaves->query("SELECT user_profile.user_id, user_profile.latitude, user_profile.longitude "
                        . "FROM user_profile "
                        . "JOIN "
                            . "(SELECT user_profile.user_id, max(user_profile.updated) updated "
                            . "FROM user_profile JOIN retailers ON (user_profile.user_id = retailers.user_id) "
                            . "WHERE user_profile.latitude > 0 AND user_profile.longitude > 0 AND user_profile.area_id > 0 AND user_profile.date > '" . date('Y-m-d', strtotime('-7 days')) . "' "
                            . "GROUP BY user_profile.user_id) a "
                        . "ON (user_profile.user_id = a.user_id AND user_profile.updated = a.updated)");

                $zoom = 5;
            } else {

                $where_clause = " locator_state.id = $state ";
                $table = 'locator_state';
                $zoom = 7;

                if($city != 'All') {
                    $where_clause .= " AND locator_city.id = $city ";
                    $table = 'locator_city';
                    $zoom = 9;
                }
                if($area != 'All') {
                    $where_clause .= " AND locator_area.id = $area ";
                    $table = 'locator_area';
                    $zoom = 13;
                }
//                $res = $this->Slaves->query("SELECT $table.name, $table.lat, $table.long, tbl.latitude, tbl.longitude "
//                        . "FROM (SELECT user_profile.user_id, user_profile.latitude, user_profile.longitude, user_profile.area_id FROM user_profile JOIN retailers ON (user_profile.user_id = retailers.user_id) WHERE user_profile.latitude > 0 AND user_profile.longitude > 0 AND user_profile.area_id > 0 AND Date(user_profile.updated) > '" . date('Y-m-d', strtotime('-7 days')) . "' ORDER BY user_profile.id DESC) tbl "
//                        . "LEFT JOIN locator_area ON locator_area.id = tbl.area_id "
//                        . "LEFT JOIN locator_city ON locator_city.id = locator_area.city_id "
//                        . "LEFT JOIN locator_state ON locator_state.id = locator_city.state_id "
//                        . "WHERE $where_clause GROUP BY tbl.user_id");
                $res = $this->Slaves->query("SELECT $table.name, $table.lat, $table.long, user_profile.user_id, user_profile.latitude, user_profile.longitude "
                        . "FROM user_profile "
                        . "JOIN "
                            . "(SELECT user_profile.user_id, max(user_profile.updated) updated "
                            . "FROM user_profile JOIN retailers ON (user_profile.user_id = retailers.user_id) "
                            . "WHERE user_profile.latitude > 0 AND user_profile.longitude > 0 AND user_profile.area_id > 0 AND user_profile.updated > '" . date('Y-m-d', strtotime('-7 days')) . "' "
                            . "GROUP BY user_profile.user_id) a "
                        . "ON (user_profile.user_id = a.user_id AND user_profile.updated = a.updated) "
                        . "JOIN locator_area ON (locator_area.id = user_profile.area_id) "
                        . "LEFT JOIN locator_city ON (locator_city.id = locator_area.city_id) "
                        . "LEFT JOIN locator_state ON (locator_state.id = locator_city.state_id) "
                        . "WHERE $where_clause");
            }

            $data = array();
            foreach($res as $temp) {
                $data[] = array('lat' => $temp['user_profile']['latitude'], 'lng' => $temp['user_profile']['longitude']);
            }

            $this->set('data', json_encode($data));
            $this->set('center', array(($res[0][$table]['lat'] == '') ? 23.3302095 : $res[0][$table]['lat'] , ($res[0][$table]['long'] == '') ? 78.0576766 : $res[0][$table]['long'], $zoom, $state, $city, $area));
        }

        public function filterCityArea() {

            $this->autoRender = FALSE;

            $search     = $this->params['form']['search'];
            $location   = $this->params['form']['location'];
            //echo $search;
            //echo $location;
            if($search == 'City') {
                $data = $this->Slaves->query("SELECT id, name FROM locator_city WHERE state_id = $location");
            } else  {
                $data = $this->Slaves->query("SELECT id, name FROM locator_area WHERE city_id = $location");
            }

            echo json_encode($data);
        }


        public function distRetMap($state = '',$city = '',$area = '',$pincode = "",$dist_id=''){
            $pincode = trim(urldecode($pincode));
            $state = trim(urldecode($state));
            $city = trim(urldecode($city));
            $area = trim(urldecode($area));
            $dist_id = trim(urldecode($dist_id));
           $this->autoLayout = FALSE;
           $states = $this->Slaves->query("Select * from locator_state where toshow = 1");
           $this->set('states',$states);
           if(!empty($state)){
               $cities = $this->Slaves->query("Select * from locator_city where state_id = '$state' AND toshow = 1");
               $this->set('cities',$cities);
               if(!empty($city)){
                   $areas = $this->Slaves->query("Select * from locator_area where city_id = '$city' AND toshow = 1");
                   $this->set('areas',$areas);
               }
           }

           $this->set('pinvalue',$pincode);
           $this->set('dist_id',$dist_id);

           $days = date('Y-m-d', strtotime("-1 month"));



            $where_clause = "1=1";

            if(!empty($dist_id) || !empty($pincode) || !empty($area)) {
                if(!empty($dist_id)){
                    $where_clause = " d.id = '$dist_id' ";
                }
                else if(!empty($pincode)){
                    $where_clause = " la.pincode = '$pincode' ";
                }
                else if(!empty($area)){
                    $where_clause = " ls.id = '$state' AND lc.id = '$city' AND la.id = '$area' ";
                }
                $table = 'la';
                $zoom = 13;

                $result = $this->Slaves->query("SELECT $table.name, $table.lat, $table.long,r.id,r.shopname,r.address,r.mobile,
                        d.id,d.company,rl.latitude,rl.longitude,la.NAME area,lc.NAME city,ls.NAME state,la.pincode
                        FROM retailers r
                        JOIN retailers_location rl ON (rl.retailer_id = r.id)
                        JOIN distributors d ON (d.id = r.parent_id)
                        JOIN locator_area la ON (la.id = rl.area_id)
                        LEFT JOIN locator_city lc ON (lc.id = la.city_id)
                        LEFT JOIN locator_state ls ON ( ls.id = lc.state_id )
                        WHERE $where_clause AND !(rl.latitude = '20' and rl.longitude='77') AND rl.area_id > 0 AND rl.updated > '$days'");


                foreach ($result as $res) {
                    $data = array();
                    $shop_name = !empty($res['r']['shopname']) ? $res['r']['shopname'] : $res['r']['mobile'];
                    $data['title'] = $shop_name . " (Distributor: ".$res['d']['company'].", Area: ".$res['la']['area'].")";
                    $data['lat'] = $res['rl']['latitude'];
                    $data['long'] = $res['rl']['longitude'];
                    $data['desc'] = $data['title'];
                    $data['type'] = 'top_b';
                    $ret_details[] = $data;

                    if(!empty($dist_id)){
                        if(!empty($res['la']['pincode'])){
                            if(!isset($dist_details[$res['la']['pincode']])){
                                $dist_details[$res['la']['pincode']]['rets'] = 1;
                                $dist_details[$res['la']['pincode']]['name'] = $res['la']['pincode'];
                                $dist_details[$res['la']['pincode']]['city'] = $res['lc']['city'];
                                $dist_details[$res['la']['pincode']]['state'] = $res['ls']['state'];
                                $dist_details[$res['la']['pincode']]['lat'] = $res['la']['lat'];
                                $dist_details[$res['la']['pincode']]['long'] = $res['la']['long'];
                            } else {
                                $dist_details[$res['la']['pincode']]['rets'] += 1;
                            }
                        }
                        $this->set('dist_name',$res['d']['company']);
                    }
                    else {
                        if(!isset($dist_details[$res['d']['id']])){
                            $dist_details[$res['d']['id']]['rets'] = 1;
                            $dist_details[$res['d']['id']]['name'] = $res['d']['company'];
                        } else {
                            $dist_details[$res['d']['id']]['rets'] += 1;
                        }

                    }

                }
                uasort($dist_details, function($a, $b) {
                    return strcmp($b['rets'], $a['rets']);
                });
                if(!empty($dist_id)){
                    $lat_long = array_values($dist_details);
                    $lat_long = $lat_long[0];
                }
                $this->set('type',"retailer");


            }
            else if (empty($state) || (!empty($state) && empty($city))) {
                    if(empty($state)){
                        $where_clause = "1";
                        $zoom = 5;
                        $min = 50;
                        $top_a = 500;
                        $top_b = 100;
                    }
                    else {
                        $where_clause = " ls.id = $state";
                        $table = 'ls';
                        $min = 5;
                        $zoom = 7;

                        $top_a = 200;
                        $top_b = 50;
                    }

                    $result = $this->Slaves->query("SELECT count(distinct d.id) as dists,count(distinct r.id) as rets,
                                        lc.name city, lc.id,ls.id, ls.name state, lc.long, lc.lat,ls.lat,ls.long
                                        FROM retailers r
                                        JOIN retailers_location rl ON (rl.retailer_id = r.id)
                                        JOIN distributors d ON (d.id = r.parent_id)
                                        JOIN locator_area la ON (la.id = rl.area_id)
                                        LEFT JOIN locator_city lc ON (lc.id = la.city_id)
                                        LEFT JOIN locator_state ls ON (ls.id = lc.state_id) where $where_clause AND ls.name = d.state AND rl.area_id > 0 AND rl.updated > '$days' group by lc.id having rets >= 1 order by rets desc");
                    foreach ($result as $res) {
                        if($res['0']['rets'] >= $min){
                            $data = array();
                            $data['title'] = $res['lc']['city'] ." - ".$res['ls']['state']." (Distributors:  " .$res['0']['dists']. ", Retailer: ".$res['0']['rets'].")";
                            $data['lat'] = $res['lc']['lat'];
                            $data['long'] = $res['lc']['long'];
                            $data['desc'] = $res['lc']['city'] ." - ".$res['ls']['state']." (Distributors:  " .$res['0']['dists']. ", Retailer: ".$res['0']['rets'].")";
                            $data['type'] = 'cities';
                            if($res[0]['rets'] >= $top_a){
                                $data['type'] = 'top_a';
                            }
                            else if($res[0]['rets'] >= $top_b){
                                $data['type'] = 'top_b';
                            }
                            $ret_details[] = $data;
                        }
                        $dist_details[$res['lc']['id']]['state_id'] = $res['ls']['id'];
                        $dist_details[$res['lc']['id']]['city_id'] = $res['lc']['id'];
                        $dist_details[$res['lc']['id']]['state'] = $res['ls']['state'];
                        $dist_details[$res['lc']['id']]['city'] = $res['lc']['city'];
                        $dist_details[$res['lc']['id']]['dists'] = $res['0']['dists'];
                        $dist_details[$res['lc']['id']]['rets'] = $res['0']['rets'];

                    }
                    $this->set('type',"overall");
            } else if(!empty($city)) {
                    $where_clause = " ls.id = $state AND lc.id = $city";
                    $table = 'lc';
                    $min = 5;
                    $zoom = 10;
                    $top_a = 50;
                    $top_b = 10;

                    $result = $this->Slaves->query("SELECT count(distinct r.id) as rets,count(distinct d.id) as dists,
                            la.name area, la.id,  la.long, la.lat, la.pincode,lc.lat,lc.long
                            FROM retailers r
                            JOIN retailers_location rl ON (rl.retailer_id = r.id)
                            JOIN distributors d ON (d.id = r.parent_id)
                            JOIN locator_area la ON (la.id = rl.area_id)
                            LEFT JOIN locator_city lc ON (lc.id = la.city_id)
                            LEFT JOIN locator_state ls ON (ls.id = lc.state_id) where ls.name = d.state AND $where_clause AND rl.area_id > 0 AND rl.updated > '$days' group by la.pincode having rets >= 1 order by rets desc");


                    $result1 = $this->Slaves->query("SELECT count(r.id) as rets,d.company,d.id,
                            la.long, la.lat, la.pincode
                            FROM retailers r
                            JOIN retailers_location rl ON (rl.retailer_id = r.id)
                            JOIN distributors d ON (d.id = r.parent_id)
                            JOIN locator_area la ON (la.id = rl.area_id)
                            LEFT JOIN locator_city lc ON (lc.id = la.city_id)
                            LEFT JOIN locator_state ls ON (ls.id = lc.state_id) where ls.name = d.state AND $where_clause AND rl.area_id > 0 AND rl.updated > '$days' group by d.id,la.pincode order by rets desc");

                    foreach ($result as $res) {
                        if($res['0']['rets'] >= $min){
                            $data = array();
                            $data['title'] = "Pincode: ".$res['la']['pincode']." (Retailers: ".$res['0']['rets'].", Distributors: ".$res['0']['dists'].")";
                            $data['lat'] = $res['la']['lat'];
                            $data['long'] = $res['la']['long'];
                            $data['desc'] = "Pincode: ".$res['la']['pincode']." (Retailers: ".$res['0']['rets'].", Distributors: ".$res['0']['dists'].")";
                            $data['type'] = 'cities';
                            if($res[0]['rets'] >= $top_a){
                                $data['type'] = 'top_a';
                            }
                            else if($res[0]['rets'] >= $top_b){
                                $data['type'] = 'top_b';
                            }
                            $ret_details[] = $data;
                        }
                        if(!empty($res['la']['pincode'])){
                            $area_details[$res['la']['pincode']]['pincode'] = $res['la']['pincode'];
                            $area_details[$res['la']['pincode']]['dists'] = $res['0']['dists'];
                            $area_details[$res['la']['pincode']]['rets'] = $res['0']['rets'];
                        }

                    }

                    foreach($result1 as $res){
                        $dist_id = $res['d']['id'];
                        $pincode = $res['la']['pincode'];
                        if(!empty($res['la']['pincode'])){
                            $dist_details[$dist_id][$pincode]['rets'] = $res['0']['rets'];
                            $dist_details[$dist_id][$pincode]['name'] = $res['d']['company'];
                        }
                    }

                    $this->set('area_details',$area_details);
                    $this->set('type',"citywise");

            }

            $dist_details =  array();


            /** IMP DATA ADDED : START**/
            $ret_ids = array_map(function($element){
                return $element['r']['id'];
            },$result);
            $dist_ids = array_map(function($element){
                return $element['d']['id'];
            },$result);
            $imp_ret_data = $this->Shop->getUserLabelData($ret_ids,2,2);
            $imp_dist_data = $this->Shop->getUserLabelData($dist_ids,2,3);
            /** IMP DATA ADDED : END**/

            foreach ($result as $res) {
                    $res['r']['shopname'] = $imp_ret_data[$res['r']['id']]['imp']['shop_est_name'];
                    $res['r']['address'] = $imp_ret_data[$res['r']['id']]['imp']['address'];
                    $ret_details[] = $res;

                    if(!isset($dist_details[$res['d']['id']])){
                            $res['d']['ret_count'] = 1;
                            $res['d']['company'] = $imp_dist_data[$res['d']['id']]['imp']['shop_est_name'];
                            $dist_details[$res['d']['id']] = $res['d'];
                    } else {
                            $dist_details[$res['d']['id']]['ret_count'] += 1;
                    }
            }

            $this->set('dist_det',$dist_details);
            $this->set('ret_det',$ret_details);

            $lat_c = $lat_long['lat'] ? $lat_long['lat'] : (($result[0][$table]['lat'] == '') ? 23.3302095 : $result[0][$table]['lat']);
            $long_c = $lat_long['long'] ? $lat_long['long'] : (($result[0][$table]['long'] == '') ? 78.0576766 : $result[0][$table]['long']);
            $this->set('center', array($lat_c, $long_c, $zoom, $state, $city, $area));
    }
}