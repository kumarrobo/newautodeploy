<?php
class SchemeComponent extends Object{
    var $components = array('General', 'Shop', 'RequestHandler');

    function getScheme($dist_id = null, $month = null, $year = null,$new_system=0,$scheme_id=null){
        $month = (empty($month)) ? date('m') : $month;
        $year = (empty($year)) ? date('Y') : $year;
        
        $last_month = ($month > 1) ? ($month - 1) : 12;
        $last_month_year = ($month > 1) ? $year : ($year - 1);
        
        Configure::load('scheme');
        $scheme = Configure::read('scheme_' . $month.'_'.$year);
        $datasource = ClassRegistry::init('User');
        
        $query = "1";
        if( ! empty($dist_id)){
            $query = "distributors.id IN ($dist_id)";
        }
        $data = $datasource->query("SELECT id,created,user_id FROM distributors WHERE $query AND active_flag = 1");
        $dist_userids = array_map(function ($element){
            return $element['distributors']['user_id'];
        }, $data);
        
        $days_last_month = date('t', strtotime($last_month_year . "-" . $last_month . "-1"));
        $days_curr_month = date('t', strtotime($year . "-" . $month . "-1"));
        
        if("$year-$month" <= "2018-04" && $new_system == 0){
            $last_month_data = $this->getDistSaleData("$last_month_year-$last_month-01", "$last_month_year-$last_month-$days_last_month", $dist_userids, $datasource);
            $curr_month_data = $this->getDistSaleData("$last_month_year-$last_month-01", "$last_month_year-$last_month-$days_curr_month", $dist_userids, $datasource);
        }
        $ids = array();
        
        /**
         * IMP DATA ADDED : START*
         */
        $dist_ids = array_map(function ($element){
            return $element['distributors']['id'];
        }, $data);
        $imp_data = $this->Shop->getUserLabelData($dist_ids, 2, 3);
        /**
         * IMP DATA ADDED : END*
         */
        
        $final_data = array();
        foreach($data as $dt){   
            $dist = $dt['distributors']['id'];
            $dist_user = $dt['distributors']['user_id'];
            $ids[] = $dist;
            
            if("$year-$month" <= "2018-04" && $new_system == 0){
                $sale_last_month = isset($last_month_data[$dist_user]['achieved'][0]['sale']) ? $last_month_data[$dist_user]['achieved'][0]['sale'] : 0;
                
                $target = $this->getTargetValues($scheme, $sale_last_month / $days_last_month, $days_curr_month);
                $final_data[$dist]['target'] = $target;
                $final_data[$dist]['achieved'] = $curr_month_data[$dist_user]['achieved'];
                $final_data[$dist]['company'] = $imp_data[$dt['distributors']['id']]['imp']['shop_est_name'];
                $final_data[$dist]['created'] = $dt['distributors']['created'];
                $final_data[$dist]['scheme'] = 'Dist monthly scheme - '.date('M',strtotime("$year-$month-01")) . ' ' . $year;
                $final_data[$dist]['services'] = 'Mobile Recharges, DTH Recharges';
                $final_data[$dist]['scheme_type'] = 'Monthly';
                $final_data[$dist]['incentive_given'] = 'NA';
                $final_data[$dist]['scheme_start'] = "01 ".date('M',strtotime("$year-$month-01"))." $year";
                $final_data[$dist]['scheme_end'] = "$days_curr_month ".date('M',strtotime("$year-$month-01"))." $year";
                
                $final_data[$dist]['company'] = $imp_data[$dt['distributors']['id']]['imp']['shop_est_name'];
                $final_data[$dist]['created'] = $dt['distributors']['created'];
                $final_data[$dist]['scheme_completed'] = (date('Y-m-d',strtotime("$year-$month-$days_curr_month")) >= date("Y-m-d")) ? 0 : 1;
                
                //$final_data = array('0'=>$final_data);
            }
            else {
                $final_data = $this->getCurrSchemes($month,$year,$days_last_month,$days_curr_month,$dist_user,$imp_data[$dt['distributors']['id']]['imp']['shop_est_name'],$dt['distributors']['created'],$final_data,$scheme_id);        
            }
        }
        
        if("$year-$month" <= "2018-04" && $new_system == 0){
            if(!empty($final_data))$final_data = array('0'=>$final_data);
        }
        
        return $final_data;
    }
    
    function getCurrSchemes($month,$year,$days_last_month,$days_curr_month,$dist_ids=null,$company,$created,$final_data,$scheme_id = null){
        $datasource = ClassRegistry::init('Slaves');
        $query = " ";
        if( ! empty($dist_ids)){
            $query .= "AND ds.user_id IN ($dist_ids) ";
        }
        
        if(!empty($scheme_id)){
            $query .= "AND sc.id =$scheme_id ";
        }
        //else{
            $query .= "AND ((month(ds.validfrom) = '$month' AND year(ds.validfrom) = '$year') OR (month(ds.validto) = '$month' AND year(ds.validto) = '$year')) ";
        //}
        //$final_data = array();
        
        $data = $datasource->query("SELECT users.mobile,ds.distributor_id,ds.validfrom,ds.validto,ds.achieved,sc.id,sc.name,sc.scheme,sc.settlement,sum(st.amount) as amount,st.settled_flag,st.id FROM distributor_schemes as ds left join schemes as sc ON (sc.id = ds.scheme_id) left join scheme_target as st ON (st.scheme_id=ds.scheme_id AND st.distributor_id=ds.user_id and st.fromdate >= ds.validfrom and st.todate <= ds.validto) left join users ON (users.id=ds.user_id) WHERE ds.isactive=1 AND sc.isactive=1 $query group by ds.distributor_id,ds.scheme_id order by ds.id");
        foreach($data as $dt){
            $dist = $dt['ds']['distributor_id'];
            $scheme_id = $dt['sc']['id'];
            $service_names = $this->getServicesByScheme($scheme_id);
            
            $achieved_data = json_decode($dt['ds']['achieved'],true);
            $targets = $this->getTargets(json_decode($dt['sc']['scheme'],true),$achieved_data['last_month_sale'],$days_last_month,$days_curr_month);
            if($targets['target1']['sale'] == 0 && $targets['target1']['incentive'] == 0){
                if(isset($_SESSION['Auth']) && $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR)continue;
            }
            
            $final_data[$scheme_id][$dist]['achieved'] = (isset($achieved_data['scheme_total_sale'])) ? $achieved_data['scheme_total_sale'] : $achieved_data['sale'];
            $final_data[$scheme_id][$dist]['target'] = $targets;
            $final_data[$scheme_id][$dist]['company'] = $company;
            $final_data[$scheme_id][$dist]['created'] = $created;
            
            $final_data[$scheme_id][$dist]['scheme'] = $dt['sc']['name'];
            $final_data[$scheme_id][$dist]['scheme_id'] = $dt['st']['id'];
            $final_data[$scheme_id][$dist]['mobile'] = $dt['users']['mobile'];
            $final_data[$scheme_id][$dist]['scheme_data'] = json_decode($dt['sc']['scheme'],true);
            $final_data[$scheme_id][$dist]['services'] = $service_names;
            $final_data[$scheme_id][$dist]['settled_flag'] = $dt['st']['settled_flag'];
            $final_data[$scheme_id][$dist]['settlement_flag'] = $dt['sc']['settlement'];
            $final_data[$scheme_id][$dist]['scheme_type'] = ($dt['sc']['settlement'] == 1) ? 'Scheme End' :'Daily';
            $final_data[$scheme_id][$dist]['incentive_given'] = $dt['0']['amount'];
            $final_data[$scheme_id][$dist]['scheme_start'] = date('dS F Y',strtotime($dt['ds']['validfrom']));
            $final_data[$scheme_id][$dist]['scheme_end'] =  date('dS F Y',strtotime($dt['ds']['validto']));
            $final_data[$scheme_id][$dist]['scheme_completed'] = ($dt['ds']['validto'] >= date("Y-m-d")) ? 0 : 1;
        }
        
        return $final_data;
    }
    
    function getTargets($scheme,$lastmonthsale,$days_last_month,$days_curr_month){
        $schemetarget = array();
        $lastmonthsale = empty($lastmonthsale) ? 0 : $lastmonthsale;
        $key = 0;
        foreach($scheme as $key=>$val){
            $range = explode("-", $key);
            if($range[0] == 0 && $range[1] == 0){
                $schemetarget = $val;
                break;
            }
            else if($lastmonthsale >= $range[0] && $lastmonthsale <= $range[1]){
                $schemetarget = $val;
                break;
            }
            //$key1++;
        }
        
        $return = array();
        
        if(!empty($schemetarget)){
            $targets = $schemetarget['target'];
            $i =1;
            foreach($targets as $key1=>$target){
                if(strpos($target, '%') !== false){
                    $percent_pos = strpos($target, '%');
                    $percent = substr($target, 0, $percent_pos);
                    $target_sale = $days_curr_month* ($lastmonthsale/$days_last_month)* ($percent + 100) / 100;
                    $return['target'.$i]['sale'] = (ceil($target_sale / 10000)) * 10000;
                }
                else {
                    $return['target'.$i]['sale'] = $target;
                }
                
                $incentive =$scheme[$key]['incentive'][$key1];
                $return['target'.$i]['incentive'] = $incentive;
                
                if(strpos($incentive, '%') !== false){
                    $percent_pos = strpos($incentive, '%');
                    $percent = substr($incentive, 0, $percent_pos);
                    $incentive_ex = round($return['target'.$i]['sale']*$percent/100,2);
                    $return['target'.$i]['incentive_ex'] = $incentive_ex;
                }
                else {
                    $return['target'.$i]['incentive_ex'] = $incentive;
                }
                $i++;
            }
        }
        return $return;
    }
    
    function updateSchemeSale($date=null,$scheme_id=null){
        $datasource = ClassRegistry::init('User');
        $date = (empty($date)) ? date('Y-m-d',strtotime('-1 day')) : date('Y-m-d',strtotime($date.' -1 day'));
        $qry = (empty($scheme_id)) ? "1" : "ds.scheme_id=$scheme_id";
        $data = $datasource->query("SELECT ds.id,ds.user_id,ds.validfrom,ds.validto,sc.settlement,group_concat(services.id) as services FROM distributor_schemes as ds left join schemes as sc ON (sc.id = ds.scheme_id) left join scheme_services as ss on (ss.scheme_id = ds.scheme_id  and ss.isactive=1) left join services ON (services.id = ss.service_id) WHERE $qry AND ds.isactive=1 AND sc.isactive=1 AND ds.validfrom <= '$date' AND ds.validto >= '$date' group by ds.distributor_id,ds.scheme_id");
        foreach($data as $dt){
            if($dt['sc']['settlement']==1){
                $row = array('fromdate'=>$dt['ds']['validfrom'],'todate'=>$dt['ds']['validto'],'distributor_id'=>$dt['ds']['user_id'],'services'=>$dt['0']['services']);
            }
            else{
                $row = array('fromdate'=>$date,'todate'=>$date,'distributor_id'=>$dt['ds']['user_id'],'services'=>$dt['0']['services']);
            }
            
            $curr_sale = $this->getdistributorSale($row,$datasource);
            $lastmonthsale = $this->getDistLastmonthsale($row,$datasource);
            $scheme_total_sale = $curr_sale;
            
            if($dt['sc']['settlement']==0){
                $row = array('fromdate'=>$dt['ds']['validfrom'],'todate'=>$dt['ds']['validto'],'distributor_id'=>$dt['ds']['user_id'],'services'=>$dt['0']['services']);   
                $scheme_total_sale = $this->getdistributorSale($row,$datasource);
            }
            
            $achieved = json_encode(array('sale'=>$curr_sale,'last_month_sale'=>$lastmonthsale,'scheme_total_sale'=>$scheme_total_sale));
            $datasource->query("UPDATE distributor_schemes SET achieved='{$achieved}' WHERE id=".$dt['ds']['id']);
        }
        
    }

    function getDistSaleData($from_date, $to_date, $dist_id = null, $datasource,$services=null){
        $query = "1 AND ";
        if(is_array($dist_id))$dist_id = implode(",",$dist_id);
        if( ! empty($dist_id)){
            $query .= "distributors.user_id IN ($dist_id) AND ";
        }
        
        if( ! empty($services)){
            $query .= "services.id IN ($services) AND ";
        }
        
        // $data = $datasource->query("SELECT sum(retailer_total_sale) as tot_sale,distributors.id,distributors.company,distributors.created FROM distributors_logs INNER JOIN distributors ON (distributors.id = distributor_id) WHERE $query AND month(date) = '$month' AND year(date) = '$year' group by distributor_id");
        //$kit_data = $datasource->query("select sum(target_id) as kits,sum(amount),shop_transactions.user_id,services.name,distributors.user_id FROM shop_transactions INNER JOIN distributors ON (distributors.user_id = source_id) LEFT JOIN services ON (services.id = shop_transactions.user_id) WHERE $query type = " . KITCHARGE . " and date >= '$from_date' AND date <= '$to_date' group by source_id,shop_transactions.user_id");
        $sale_data = $datasource->query("select sum(amount) as tot_sale,count(ret_user_id) as num,count(distinct ret_user_id) as cts,services.id,services.name,distributors.user_id FROM retailer_earning_logs as rl INNER JOIN distributors ON (distributors.user_id = rl.dist_user_id) LEFT JOIN services ON (services.id = service_id) WHERE $query date >= '$from_date' AND date <= '$to_date' group by dist_user_id,service_id");
        
        $ret = array();
        
        /*foreach($kit_data as $dt){
            $dist_id = $dt['distributors']['user_id'];
            $service_id = $dt['shop_transactions']['user_id'];
            $ret[$dist_id]['achieved'][$service_id]['kits'] = $dt['0']['kits'];
            $ret[$dist_id]['achieved'][$service_id]['name'] = $dt['services']['name'];
        }*/
        
        foreach($sale_data as $dt){
            $dist_id = $dt['distributors']['user_id'];
            $service_id = $dt['services']['id'];
            $ret[$dist_id]['achieved'][$service_id]['sale'] = $dt['0']['tot_sale'];
            $ret[$dist_id]['achieved'][$service_id]['count'] = $dt['0']['num'];
            $ret[$dist_id]['achieved'][$service_id]['rets'] = $dt['0']['cts'];
            $ret[$dist_id]['achieved'][$service_id]['name'] = $dt['services']['name'];
        }
        
        return $ret;
    }

    function targetCalculation($target, $sale_last_month, $days){
        if(strpos($target['recharge'], '%') !== false){
            $percent_pos = strpos($target['recharge'], '%');
            $percent = substr($target['recharge'], 0, $percent_pos);
            $target_sale = $days * $sale_last_month * ($percent + 100) / 100;
            $target['sale'] = (ceil($target_sale / 10000)) * 10000;
        }
        else {
            $target['sale'] = $target['recharge'];
        }
        
        if(strpos($target['incentive'], '%') !== false){
            $inc_pos = strpos($target['incentive'], '%');
            $percent = substr($target['incentive'], 0, $inc_pos);
            $target['incentive_ex'] = round($target['sale'] * $percent / 100, 2);
        }
        else{
            $target['incentive_ex'] = $target['incentive'];
        }
        
        return $target;
    }

    function getTargetValues($scheme, $sale_last_month, $days){
        foreach($scheme as $key=>$sc){
            if($key > $sale_last_month){
                $sc_dist = $sc;
                break;
            }
        }
        
        if( ! empty($sc_dist)){
            foreach($sc_dist as $key=>$sc){
                $sc_dist[$key] = $this->targetCalculation($sc, $sale_last_month, $days);
            }
            
            return $sc_dist;
        }
    }

    function getAllSchemes(){
        $datasource = ClassRegistry::init('User');
        // get list of scheme from scheme and distributor_schemes table
        $sql = "SELECT GROUP_CONCAT(dist.distributor_id) as dist_ids,GROUP_CONCAT(distinct scheme_services.service_id) as service, " . "schemes.* " . "FROM schemes LEFT JOIN distributor_schemes as dist" . " ON (dist.scheme_id=schemes.id AND dist.isactive=1) LEFT JOIN " . "scheme_services ON (scheme_services.scheme_id=schemes.id) " . " where scheme_services.isactive=1 AND schemes.isactive=1 group by schemes.id";
        
        return $datasource->query($sql);
    }

    function getSchemeById($id){
        $datasource = ClassRegistry::init('User');
        $sql = "SELECT * " . "FROM schemes where id=$id ";
        
        $result = $datasource->query($sql);
        return $result[0]['schemes'];
    }

    function getAllServices(){
        $datasource = ClassRegistry::init('User');
        $sql = "SELECT id,name FROM services where toShow=1 ";
        $data = $datasource->query($sql);
        $services = array();
        foreach($data as $row){
            $services[$row['services']['id']] = $row['services']['name'];
        }
        return $services;
    }

    function getServicesByScheme($schemeId){
        $datasource = ClassRegistry::init('User');
        $query = "SELECT group_concat(service_id) as service FROM scheme_services where scheme_id=$schemeId  AND isactive=1";
        $servicelist = $datasource->query($query);
        
        $services = $servicelist[0][0]['service'];
        $sql = "SELECT group_concat(services.name) as services FROM services where  id IN ($services) ";
        $result = $datasource->query($sql);
        if( ! empty($result)){
            return $result[0][0]['services'];
        }
    }

    function updateSchemeService($schemeId, $service){
        $datasource = ClassRegistry::init('User');
        
        $query = "SELECT service_id FROM scheme_services where scheme_id=$schemeId AND isactive=1";
        $result = $datasource->query($query);
        
        $scheme_service = array();
        foreach($result as $val){
            $scheme_service[] = $val['scheme_services']['service_id'];
        }
        if(array_diff($scheme_service, $service) || array_diff($service, $scheme_service)){
            
            $update_q = "UPDATE scheme_services SET isactive=0 where  scheme_id=$schemeId  AND isactive=1";
            $datasource->query($update_q);
            $serviceQ = "INSERT INTO scheme_services (service_id,scheme_id) ";
            
            $i = 0;
            foreach($service as $id){
                if($i > 0) $serviceQ .= " , ($id,$schemeId) ";
                else $serviceQ .= " VALUES ($id,$schemeId) ";
                $i ++ ;
            }
            
            $datasource->query($serviceQ);
        }
    }

    function getDistributorsByScheme($id){
        $datasource = ClassRegistry::init('User');
        
        $sql = "SELECT schemes.name,distributor_schemes.id,distributor_schemes.distributor_id, distributor_schemes.validfrom,distributor_schemes.validto,distributor_schemes.scheme_id " . "FROM schemes,distributor_schemes where schemes.id=distributor_schemes.scheme_id AND distributor_schemes.scheme_id=$id AND distributor_schemes.isactive=1 ";
        
        $result = $datasource->query($sql);
        
        return $result;
    }

    function getschemeByName($scheme){
        $datasource = ClassRegistry::init('User');
        $query = "SELECT id from schemes where name='{$scheme}'";
        $result = $datasource->query($query);
        return $result;
    }

    function deleteDistributor($id, $distributor_schemes){
        $datasource = ClassRegistry::init('User');
        
        $sql = "Update distributor_schemes SET isactive=0,updatedat='" . date('Y-m-d H:i:s') . "',updated_userid='" . $_SESSION['Auth']['User']['id'] . "' where id IN (" . implode(',', $distributor_schemes) . ")";
        $result = $datasource->query($sql);
        
        if($result){
            return array('error'=>0, 'msg'=>'Distributor deleted successfully');
        }
        else{
            return array('error'=>1, 'msg'=>'Error occured while deleting distributor successfully');
        }
    }

    /*
     * get unique distributors with service and scheme details and insert in schemetarget table
     */
    function schemeinsertCron($manual_date=null){
        // get distributor id , schemetype and serviceid when current date between valid from and valid to
        $date = (empty($manual_date)) ? date('Y-m-d',strtotime('-1 day')) : date('Y-m-d',strtotime($manual_date.' -1 day '));
        $date_today = (empty($manual_date)) ? date('Y-m-d') : date('Y-m-d',strtotime($manual_date));
        
        $datasource = ClassRegistry::init('User');
        $sql = "SELECT ds.`user_id`,ds.validfrom,ds.validto,sh.id," . "GROUP_CONCAT(sch_service.service_id) as services,sh.settlement,sh.scheme " . "FROM `distributor_schemes` as ds INNER JOIN schemes as sh ON (sh.id=ds.`scheme_id`) " . "INNER JOIN scheme_services as sch_service " . "ON (sh.id=sch_service.scheme_id) WHERE sch_service.isactive=1 AND sh.isactive=1 " . "AND ds.isactive=1 AND '$date' BETWEEN ds.validfrom and ds.validto " . "GROUP BY sh.id,ds.user_id,ds.validfrom,ds.validto";
        $result = $datasource->query($sql);
        $distributors = array();
        $query = "INSERT INTO scheme_target (scheme_id,services,distributor_id,data,crondate,fromdate,todate) VALUES ";
        $queryString = array();
        $i = 0;
        foreach($result as $row){
            // daily scheme or scheme end
            if($row['sh']['settlement'] == 0){
                $queryString[] = "({$row['sh']['id']},'{$row[0]['services']}',{$row['ds']['user_id']},'{$row['sh']['scheme']}','{$date_today}','$date','$date' ) ";
            }
            elseif($row['sh']['settlement'] = 1 && $row['ds']['validto'] == $date){
                $queryString[] = "({$row['sh']['id']},'{$row[0]['services']}',{$row['ds']['user_id']},'{$row['sh']['scheme']}','{$date_today}','{$row['ds']['validfrom']}','{$row['ds']['validto']}' ) ";
            }
            
        }
       	 
        if( ! empty($queryString)){
            $queryString = $query . implode(",",$queryString);
            $datasource->query($queryString);
        }
    }

    /*
     * get distributors from schemetarget table and calculate incentive and update schemetarget
     */
    function schemeupdateCron($manual_date=null){
        $datasource = ClassRegistry::init('User');
        $date = (empty($manual_date)) ? date('Y-m-d',strtotime('-1 day')) : date('Y-m-d',strtotime($manual_date.' -1 day '));
        $date_today = (empty($manual_date)) ? date('Y-m-d') : date('Y-m-d',strtotime($manual_date));
        
        $sql = "SELECT st.services,st.distributor_id,st.data,st.fromdate,st.todate,st.id,ds.achieved FROM scheme_target as st left join distributor_schemes as ds on (ds.scheme_id=st.scheme_id AND ds.user_id=st.distributor_id) where st.achieved=0 AND st.crondate='$date_today' AND st.fromdate >= ds.validfrom AND st.todate <= ds.validto group by ds.scheme_id,ds.user_id";
        $result = $datasource->query($sql);
        
        $distributor = array();
        foreach($result as $row){
            $scheme_target_id = $row['st']['id'];
            $distributor[$row['st']['id']] = $this->calculateIncentive($row);
        }
        
        if( ! empty($distributor)){
            foreach($distributor as $key=>$val){
                if($val >= 0){
                    $query = "UPDATE scheme_target SET amount=$val, achieved=1 where id=$key";
                    $datasource->query($query);
                }
            }
        }
    }

    function calculateIncentive($row,$value_percent=1){
        //$currentsale = $this->getdistributorSale($row,$datasource);
        //$lastmonthsale = $this->getDistLastmonthsale($row,$datasource);
        
        $achieved = json_decode($row['ds']['achieved'], true);
        $currentsale = $achieved['sale'];
        $lastmonthsale = $achieved['last_month_sale'];
        
        $row = $row['st'];
        $scheme = json_decode($row['data'], true);
        
        foreach($scheme as $key=>$val){
            $range = explode("-", $key);
            if($range[0] == 0 && $range[1] == 0){
                $schemetarget = $val;
                break;
            }
            else if($lastmonthsale >= $range[0] && $lastmonthsale <= $range[1]){
                $schemetarget = $val;
                break;
            }
        }
        $key = -1;
        $incentive = 0;
        
        $month = date('m', strtotime($row['fromdate']));
        $year = date('Y', strtotime($row['fromdate']));
        
        $last_month = ($month > 1) ? ($month - 1) : 12;
        $last_month_year = ($month > 1) ? $year : ($year - 1);
        $days_last_month = date('t', strtotime($last_month_year . "-" . $last_month . "-1"));
        
        $date_diff = date_diff(date_create($row['fromdate']),date_create($row['todate']));
        $schemeDays = $date_diff->format('%a') + 1;
        
        if( ! empty($schemetarget)){
            foreach($schemetarget['target'] as $key2=>$value){
                if(strpos($value, '%') === false && $value*$value_percent <= $currentsale){
                    $key = $key2;
                }
                elseif(strpos($value, '%') !== false && $lastmonthsale > 0 && ((($lastmonthsale * $schemeDays / $days_last_month) * (100 + (int)$value))*$value_percent / 100 <= $currentsale)){
                    $key = $key2;
                }
            }
            if($key >= 0){
                if(strpos($schemetarget['incentive'][$key], '%') !== false){
                    $percent_pos = strpos($schemetarget['incentive'][$key], '%');
                    $percent = substr($schemetarget['incentive'][$key], 0, $percent_pos);
                    $incentive = (int)(($currentsale * $percent) / 100);
                }
                else{
                    $incentive = $schemetarget['incentive'][$key];
                }
            }
        }
        return $incentive;
    }

    function getdistributorSale($row,$datasource){
        $data = $this->getDistSaleData($row['fromdate'], $row['todate'], $row['distributor_id'],$datasource,$row['services']);
        $total = 0;
        $dist_id = $row['distributor_id'];
        foreach($data[$dist_id]['achieved'] as $service=>$dt){
            $total += $dt['sale'];
        }
        
        return $total;
    }

    function getDistLastmonthsale($row,$datasource){
        $curr_mon = new DateTime($row['fromdate']);
        $curr_mon->sub(new DateInterval('P1M'));
        $prev_mon = $curr_mon->format('Y-m-d');
        $prev = date_parse_from_format("Y-m-d", $prev_mon);
        $prvMonth = $prev["month"];
        $prvMonthYear = $prev["year"];
        
        $days_last_month = date('t', strtotime($prvMonthYear. "-" . $prvMonth. "-1"));
        
        $data = $this->getDistSaleData("$prvMonthYear-$prvMonth-01", "$prvMonthYear-$prvMonth-$days_last_month", $row['distributor_id'],$datasource,$row['services']);
        
        $total = 0;
        $dist_id = $row['distributor_id'];
        foreach($data[$dist_id]['achieved'] as $service=>$dt){
            $total += $dt['sale'];
        }
        
        return $total;
    }

    /*
     * calculate tds for distributor and amount
     * make 2 entry in shop_transaction table 1.for tds-amount 2. for tds
     * update distributor balance
     */
    function setIncentive($manual_date=null){
        $userObj = ClassRegistry::init('User');
        $dataSource = $userObj->getDataSource();
        
        try{
            $dataSource->begin();
            $date = (empty($manual_date)) ? date('Y-m-d') : date('Y-m-d',strtotime($manual_date));
            $sql = "SELECT st.distributor_id,st.id,st.amount as incentive,s.name,st.services FROM `scheme_target` as st INNER JOIN schemes as s ON (s.id=st.scheme_id) where st.achieved=1 and st.crondate='$date' AND st.settled_flag = 0";
            $result = $dataSource->query($sql);
            if( ! empty($result)){
                
                foreach($result as $row){
                    $data = array();
                    $data['incentive'] = $row['st']['incentive'];
                    $data['scheme'] = $row['s']['name'];
                    $data['services'] = $row['st']['services'];

                    if($data['incentive'] > 0){
                        $dists = $dataSource->query("SELECT distributors.id,distributors.user_id,distributors.mobile,distributors.commission_type,distributors.margin,distributors.incentive,distributors.gst_no FROM distributors WHERE active_flag = 1 AND user_id={$row['st']['distributor_id']}");
                        $data['mobile'] = $dists[0]['distributors']['mobile'];
                        $data['gst_flag'] = (strlen($dist['distributors']['gst_no']) < 15) ? false : true;
                        $data['user_id'] = $dists[0]['distributors']['user_id'];
                        
                        $tds = $this->getTds($data);
                        $data['tds'] = $tds;
                        $trans_id = $this->setDistributorIncentive($data, $dataSource);
                        $dataSource->query("UPDATE scheme_target SET settled_flag = 1,shop_transaction_id='$trans_id' WHERE id=".$row['st']['id']);
                    }
                    else{
                        $dataSource->query("UPDATE scheme_target SET settled_flag = 1 WHERE id=".$row['st']['id']);
                    }
                }
            }
            $dataSource->commit();
        }
        catch(Exception $e){
            $dataSource->rollback();
        }
    }

    function getTds($data){
        $incentive = $data['incentive'];
        $gst_flag = $data['gst_flag'];
        $denom = "1." . SERVICE_TAX_PERCENT;
        
        if($gst_flag){
            $tds_comm = round(($incentive / $denom) * TDS_PERCENT / 100, 3);
        }
        else{
            $tds_comm = round($incentive * TDS_PERCENT / 100, 3);
        }
        return $tds_comm;
    }

    function setDistributorIncentive($result, $datasource){
        $tds = $result['tds'];
        $incentive = $result['incentive'];
        $mobile = $result['mobile'];
        $user_id = $result['user_id'];
        $services = explode(",",$result['services']);
        $service_id = $services[0];
        
        $balance = $this->Shop->getBalance($user_id,$datasource);
        $closing_bal = $this->Shop->shopBalanceUpdate($incentive - $tds, 'add', $user_id, DISTRIBUTOR, $datasource);
        
        $description = "Incentive against scheme: ".$result['scheme'];
        $trans_id = $this->Shop->shopTransactionUpdate(REFUND, $incentive, $user_id, 0, $service_id, null, null, $description, $balance, $balance + $incentive, 0, 0, $datasource);
        $description = "TDS deducted on last incentive: $trans_id";
        $this->Shop->shopTransactionUpdate(TDS, $tds, $user_id, $trans_id, $service_id, null, null, $description, $balance + $incentive, $closing_bal, 0, 0, $datasource);
        
        $MsgTemplate = $this->General->LoadApiBalance();
        $content = $MsgTemplate['Distributor_Incentive'];
        $sms = $this->General->ReplaceMultiWord(array('CLOSING'=>$closing_bal, 'SCHEME'=>$result['scheme'], 'INCENTIVE'=>$incentive), $content);
        $this->General->sendMessage($mobile, $sms, 'shops');
        
        return $trans_id;
    }

    
}
