<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SchemeController extends AppController {

    var $name = 'Scheme';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator', 'GChart', 'Csv', 'NumberToWord');
    var $components = array('RequestHandler','Scheme');
    var $uses = array('Slaves', 'User');

    function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'module';
    }
  
    /*
     *Add scheme with name,service frequency of settlement and target params
     */
    function addscheme()
    {
        $this->autoRender = false;
        //get all services
        $services = $this->Scheme->getAllServices();
        //get all distributors
        $distributors = $this->Shop->getDsitributors();
        
        if(isset($_POST) && array_key_exists('scheme', $_POST) && array_key_exists('service', $_POST)   && array_key_exists('target', $_POST)  && array_key_exists('frequency', $_POST)  ){
            
            extract($_POST);
            $data = array();
            $scheme_name = $this->Scheme->getschemeByName($scheme);
            if(!empty($scheme_name)){
                return json_encode(array('error'=>1,'msg'=>'Scheme with given name already exist'));
            }
            $i=0;
            foreach($sellrange as $index=>$value){
                if($i>0){
                    $target = ('target'.$i);
                    $incentive = ('incentive'.$i);
                    $target= $$target;
                    $incentive = $$incentive;
                }
                
                foreach($target as $key=>$val)
                {
                    $data[$sellrange[$index]]['target'][] = $val;
                }
                foreach($incentive as $key=>$val)
                {
                    $data[$sellrange[$index]]['incentive'][] = $val;
                    
                }
                $i++;
            }
            
            $data = json_encode($data);
            $schemeData = array('name'=>$scheme,);
            $currDate = date('Y-m-d');
	    $priority=1;
            $query = "INSERT INTO schemes (name,scheme,type,createdat,settlement,updatedat,created_userid) "
                    . "VALUES ('$scheme','$data',$priority,'$currDate',$frequency,'".date('Y-m-d H:i:s')."','".$_SESSION['Auth']['User']['id']."')";
                    $result = $this->User->query($query);
                    
                    if($result)
                    {
                        $query = "SELECT LAST_INSERT_ID() as 'insert_id'";
                        $result = $this->User->query($query);
                        
                        $serviceQ = "INSERT INTO scheme_services (service_id,scheme_id) ";
                        $i=0;
                        foreach($service as $id){
                            if($i>0)
                                $serviceQ.=" , ($id,{$result[0][0]['insert_id']}) ";
                                else
                                    $serviceQ  .= " VALUES ($id,{$result[0][0]['insert_id']}) ";
                                    $i++;
                        }
                        
                        $this->User->query($serviceQ);
                        return json_encode(array('error'=>0,'msg'=>'Scheme added successfully'));
                    }
                    else
                    {
                        return json_encode(array('error'=>1,'msg'=>'Scheme Not inserted properly'));
                    }
                    
        }
        return json_encode(array('error'=>1,'msg'=>'Please send data properly'));
    }
    
    function adddistToScheme(){
        
        $this->autoRender = false;
        //get all distributors
        $distributors = $this->Shop->getDsitributors();
        
        if(isset($_POST) && array_key_exists('schemeId', $_POST)   && array_key_exists('from', $_POST)   && array_key_exists('to', $_POST)    ){
            extract($_POST);
            
            $i=0;
            $serviceQuery= "SELECT group_concat(service_id) as service FROM scheme_services where scheme_id= $schemeId AND isactive=1";
            $services = $this->User->query($serviceQuery);
            if(empty($services)){
                return json_encode(array('error'=>1,'msg'=>'Please add service in this scheme and then add distributor'));
            }
            
            if($from > $to){
                return json_encode(array('error'=>1,'msg'=>'Please enter correct date range'));
            }
            
            if($all_dist ==1){
                $distributor = array_keys($distributors);
            }
            
            $service = $services[0][0]['service'];
            $query = "SELECT distinct distributor_schemes.distributor_id as dist_list "
                    . "FROM schemes INNER JOIN scheme_services "
                            . "ON (schemes.id=scheme_services.scheme_id) "
                                    . "INNER JOIN distributor_schemes "
                                            . "ON (schemes.id=distributor_schemes.scheme_id)  "
                                                    . "where scheme_services.service_id IN ($service) AND scheme_services.isactive=1 AND "
                                                    . "schemes.isactive=1 AND distributor_schemes.isactive =1 AND "
                                                            . "distributor_schemes.distributor_id IN  (".implode(",",($distributor)).")  AND  (('$from'  BETWEEN distributor_schemes.validfrom AND distributor_schemes.validto) OR ('$to'  BETWEEN distributor_schemes.validfrom AND distributor_schemes.validto))";
                                                            $dist_data_list = $this->User->query($query);
                                                            
                                                            foreach($dist_data_list as $id){
                                                                $dist_list[] = $id['distributor_schemes']['dist_list'];
                                                            }
                                                            
                                                            if(!empty($dist_list)){
                                                                if(!(array_diff($dist_list,$distributor) || array_diff($distributor,$dist_list))){
                                                                    return json_encode(array('error'=>1,'msg'=>"Can't add Distributors since already assigned with same services"));
                                                                }else{
                                                                    $dist_array = array();
                                                                    
                                                                    foreach($distributor as $val){
                                                                        if(!in_array($val,$dist_list)){
                                                                            $dist_array[]= $val;
                                                                        }
                                                                    }
                                                                    $distributor =$dist_array;
                                                                    
                                                                }
                                                                
                                                            }
                                                            foreach($distributors as $key=>$val){
                                                                if(in_array($key,$distributor)){
                                                                    $string.= ($i>0) ? "," : "";
                                                                    $distId = $key;
                                                                    $userId = $val['user_id'];
                                                                    
                                                                    $string .= " ($distId,$userId,$schemeId,'$from','$to','".date('Y-m-d H:i:s')."','".$_SESSION['Auth']['User']['id']."')";
                                                                    $i++;
                                                                }
                                                            }
                                                            $query = "INSERT INTO distributor_schemes (distributor_id,user_id,scheme_id,validfrom,validto,createdat,created_userid) "
                                                                    . "VALUES $string";
                                                                    
                                                                    $result = $this->User->query($query);
                                                                    if($result)
                                                                    {
                                                                        $this->Scheme->updateSchemeSale(date('Y-m-d'),$schemeId);
                                                                        return json_encode(array('error'=>0,'msg'=>"Distributors added successfully"));
                                                                    }
                                                                    else
                                                                    {
                                                                        return json_encode(array('error'=>1,'msg'=>'Distributor Not inserted properly'));
                                                                    }
        }
        return json_encode(array('error'=>1,'msg'=>'Please send data properly'));
        
    }
    
    function updatescheme(){
        $this->autoRender = false;
        //get all services
        $services = $this->Scheme->getAllServices();
        
        //get all distributors
        $distributors = $this->Shop->getDsitributors();
        
        if(isset($_POST) && array_key_exists('scheme', $_POST) && array_key_exists('service', $_POST)  && array_key_exists('target', $_POST)  && array_key_exists('incentive', $_POST)   && array_key_exists('frequency', $_POST) ){
            $dataSource = $this->User->getDataSource();
            $dataSource->begin();
            try{
                extract($_POST);
                $data = array();
                // $service = implode(',',$service);
                $i=0;
                foreach($sellrange as $index=>$value){
                    if($i>0){
                        $target = ('target'.$i);
                        $incentive = ('incentive'.$i);
                        $target= $$target;
                        $incentive = $$incentive;
                    }
                    
                    foreach($target as $key=>$val)
                    {
                        $data[$sellrange[$index]]['target'][] = $val;
                    }
                    foreach($incentive as $key=>$val)
                    {
                        $data[$sellrange[$index]]['incentive'][] = $val;
                        
                    }
                    $i++;
                }
                $data = json_encode($data);
                
                $result = $this->Scheme->getSchemeById($schemeId);
                
                if(!empty($result)){
                    
                    $this->Scheme->updateSchemeService($schemeId,$service);
                    
                    $cond = array();
                    if($scheme!= $result['name']){
                        $cond[] = "name='$scheme'";
                    }
			$modal_priority = 1;
                    if($modal_priority != $result['type']){
                        
                        $cond[] =  "type='$modal_priority'";
                    }
                    if($schemeactivation != $result['isactive']){
                        $cond[] =  "isactive='$schemeactivation'";
                    }
                    if($frequency!=$result['settlement']){
                        $cond[] = " settlement='$frequency'";
                    }
                    $cond[] = "scheme='{$data}'";
                    $updatecondition  =   implode(",",$cond);
                    $type = empty($distributor) ? 0 : 1;
                    $service = empty($service ) ? 0 : $service;
                    $sql = "UPDATE schemes SET  $updatecondition,updatedat='".date('Y-m-d H:i:s')."',updated_userid='".$_SESSION['Auth']['User']['id']."' where id=$schemeId";
                    
                    $dataSource->query($sql);
                    
                    if( !empty($distributor)){
                        
                        $dist_result = $this->User->query("SELECT * FROM distributor_schemes where scheme_id=$schemeId");
                        $dist_cond = array();
                        
                        if(!empty($dist_result) && count($dist_result)==1){
                            if($distributor!=$dist_result[0]['distributor_schemes']['distributor_id']){
                                $dist_cond[] = " distributor_id=$distributor ";
                                $dist_cond[] = "user_id = {$distributors[$distributor]['user_id']}";
                            }
                            
                            if($from!=$dist_result[0]['distributor_schemes']['validfrom']){
                                $dist_cond[] = " validfrom=$validfrom ";
                            }
                            if($to!=$dist_result[0]['distributor_schemes']['validto']){
                                $dist_cond[] = " validto=$to ";
                            }
                            if($schemeactivation!=$dist_result[0]['distributor_schemes']['isactive']){
                                $dist_cond[] = " isactive=$schemeactivation ";
                            }
                            if(!empty($dist_cond)){
                                $condition  =   implode(",",$dist_cond);
                            }
                            $sql = "UPDATE distributor_schemes SET $condition,updatedat='".date('Y-m-d H:i:s')."',updated_userid='".$_SESSION['Auth']['User']['id']."' where scheme_id=$schemeId";
                            $dataSource->query($sql);
                        }
                        elseif(!empty($dist_result) && count($dist_result)>1){
                            $string = "VALUES ";
                            $currDateTime = date('Y-m-d H:i:s');
                            
                            if($type==0){
                                
                                $i=0;
                                foreach($distributors as $key=>$val){
                                    
                                    $string.= ($i>0) ? "," : "";
                                    $distId = $key;
                                    $userId = $val['user_id'];
                                    
                                    $string .= " ($distId,$userId,$schemeId,'$from','$to','$currDateTime',$type)";
                                    $i++;
                                }
                                
                            }else{
                                $user_id = $distributors[$distributor]['user_id'];
                                $string .= " ($distributor,$user_id,$schemeId,'$from','$to','$currDateTime',$type,'".$_SESSION['Auth']['User']['id']."')";
                            }
                            $query = "INSERT INTO distributor_schemes (distributor_id,user_id,scheme_id,validfrom,validto,createdat,type,created_userid) "
                                    . $string;
                                    
                                    $dataSource->query($query);
                                    
                        }
                        
                    }
                    $dataSource->commit();
                    return json_encode(array('error'=>0,'msg'=>'Scheme updated successfully'));
                }
                
                return json_encode(array('error'=>1,'msg'=>'Scheme not found'));
            }
            catch(Exception $e){
                $dataSource->rollback();
                $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/scheme.txt","exception occured while updating scheme: data=> ".json_encode($e)."<br/>".date('Y-m-d H:i:s'));
                return json_encode(array('error'=>1,'msg'=>'Scheme not updated'));
            }
        }else{
            return json_encode(array('error'=>1,'msg'=>'Please use valid params'));
        }
        
    }
    
    function deleteDistributorScheme(){
        $this->autoRender = false;
        if(isset($_GET) && isset($_GET['distId'])){
            $distId = $_GET['distId'];
            $schemeId = $_GET['schemeId'];
            $query = "UPDATE distributor_schemes SET isactive=0,updated_userid='".$_SESSION['Auth']['User']['id']."',updatedat='".date('Y-m-d H:i:s')."' WHERE distributor_id=$distId AND scheme_id=$schemeId";
            $result = $this->User->query($query);
            if($result){
                return json_encode(array('error'=>0,'msg'=>'Distributor deleted successfully'));
            }
            else
            {
                return json_encode(array('error'=>1,'msg'=>'Error occured while deleting distributor successfully'));
            }
        }
        return json_encode(array('error'=>1,'msg'=>'Please provide valid data'));
    }
    
    function giveDistributorScheme(){
        $this->autoRender = false;
        $st_id = $_GET['schemetarget_id'];
        
        $query = "SELECT st.*,ds.achieved FROM scheme_target as st left join distributor_schemes as ds on (ds.scheme_id=st.scheme_id AND ds.user_id=st.distributor_id AND st.fromdate=ds.validfrom AND st.todate=ds.validto) WHERE st.id = $st_id";
        $result = $this->User->query($query);
        
	
        if(empty($result)){
            return json_encode(array('error'=>1,'msg'=>'Please provide valid data'));
        }
        else if(!empty($result['0']['st']['shop_transaction_id'])){
            return json_encode(array('error'=>1,'msg'=>'Incentive already given'));
        }
        else {
            $incentive = $this->Scheme->calculateIncentive($result[0],MANUAL_DIST_INCENTIVE_LIMIT/100);
            if($incentive > 0){
                $query = "UPDATE scheme_target SET amount=$incentive, achieved=1,crondate='".date('Y-m-d')."',settled_flag=0 where id=$st_id";
                $this->User->query($query);
                
                $this->Scheme->setIncentive();
                return json_encode(array('error'=>0,'msg'=>'Rs. '.$incentive. ' incentive given successfully'));
            }
            else {
                return json_encode(array('error'=>1,'msg'=>'Incentive not given'));
            }
            
        }
        
    }
    
    function sendSchemeNotification(){
        $this->autoRender = false;
        $scheme_id = $_POST['scheme_id'];
        $year_month = $_POST['year_month'];
        $title = $_POST['notif_title'];
        
        if(!empty($year_month)){
            $exp = explode('-',$year_month);
            $year = $exp[0];
            $month = $exp[1];
        }
        else {
            $year = date('Y');
            $month = date('m');
        }
        $last_month = ($month > 1) ? ($month - 1) : 12;
        $last_month_year = ($month > 1) ? $year : ($year - 1);
        
        $days_last_month = date('t', strtotime($last_month_year . "-" . $last_month . "-1"));
        $days_curr_month = date('t', strtotime($year . "-" . $month . "-1"));
        
        $schemes = $this->Scheme->getCurrSchemes($month,$year,$days_last_month,$days_curr_month,null,null,null,null,$scheme_id);
        $schemes = $schemes[$scheme_id];
        
        if(empty($schemes)){
            return json_encode(array('error'=>1,'msg'=>'Please provide valid data'));
        }
        else {
            $MsgTemplate = $this->General->LoadApiBalance();
            
            foreach($schemes as $dist=>$res){
	        $sms_target='';
                if($res['settled_flag'] == 0){
                    $target = $res['target'];
                    
                    foreach($target as $key=>$t){
                        $sms_target .= ucwords($key). ": Rs ".$t['sale'].", Incentive: ".$t['incentive']."\n";
                    }
                    
                    $scheme_period = $res['scheme_start'] . " to " . $res['scheme_end'];
                    if(date('Y-m-d') > date('Y-m-d',strtotime($res['scheme_start']))){//new scheme
                        $content = $MsgTemplate['Distributor_Scheme_New'];
                        $sms = $this->General->ReplaceMultiWord(array('TITLE'=>$title,'SERVICES'=>$res['services'], 'TARGETS'=>$sms_target, 'SCHEME_PERIOD'=>$scheme_period), $content);
                    }
                    else {//ongoing scheme
                        $content = $MsgTemplate['Distributor_Scheme_Ongoing'];
                        $sms = $this->General->ReplaceMultiWord(array('TITLE'=>$title,'SERVICES'=>$res['services'], 'TARGETS'=>$sms_target, 'SCHEME_PERIOD'=>$scheme_period,'ACHIEVED'=>$res['achieved']), $content);
                    }
                    $this->General->sendMessage($res['mobile'], $sms, 'shops');
                    
                }
            }
            return json_encode(array('error'=>0,'msg'=>'Notification sent successfully'));
            
            
        }
        
    }
    
    function getscheme(){
        $this->autoRender = false;
        extract($_GET);
        $schemeId = $_GET['schemeId'];
        $result = $this->Scheme->getSchemeById($schemeId);
        
        if(!empty($result)){
            $query = "SELECT group_concat(service_id) as services FROM scheme_services where scheme_id=$schemeId AND isactive=1";
            $service_list = $this->User->query($query);
            if(empty($service_list))
                $result['service']  = '';
                else
                    $result['service']  = $service_list[0][0]['services'];
                    return json_encode($result);
        }
        return json_encode(array());
    }
    
    function viewscheme(){
        $services = $this->Scheme->getAllServices();
        //get all distributors
        $distributors = $this->Shop->getDsitributors();
        $schemes = $this->Scheme->getAllSchemes();
        
        $this->set('scheme_list',$schemes);
        $this->set('services',$services);
        $this->set('distributors',$distributors);
        $this->render('scheme');
        
    }
    
    function schemeReport($scheme_id=null,$dist_id=null){
        //$this->autoRender = false;
        $year = date('Y');
        $month = date('m');
        
        if( array_key_exists('year_month',$this->params['url']) ){
            $year_month = explode('-',trim($this->params['url']['year_month']));
            $year = $year_month[0];
            $month = $year_month[1];
        }
        
        $schemes = $this->Scheme->getScheme($dist_id,$month,$year,1,$scheme_id);
        $this->set('target_report',$schemes);
        $this->set('year_month',$year.'-'.$month);
        $this->set('schemeId',$scheme_id);
        $this->set('dist_id',$dist_id);
        
        $this->render('scheme_report');
    }
    
    //get list of active distributors withing given schemeId
    //if data is from post request inactivate distributor
    function getSchemeDistributor($id=null)
    {
        $result="";
        if(empty($_POST)){
            $schemeId = $id;
        }else{
            extract($_POST);
            $schemeId = $id;
            $result = $this->Scheme->deleteDistributor($scheme_id,$distributor_schemes);
            $this->set('message',$result);
            
        }
        $schemeDist = $this->Scheme->getDistributorsByScheme($schemeId);
        $distributors = $this->Shop->getDsitributors();
        $services = $this->Scheme->getServicesByScheme($schemeId);
        
        $this->set('schemeDist',$schemeDist);
        $this->set('schemeId',$schemeId);
        $this->set('distributors',$distributors);
        $this->set('services',$services);
        $this->set('message',$result);
        $this->render('scheme_distributor');
    }
    
    function editSchemeDistributor()
    {
        extract($_POST);
        $this->autoRender = false;
        
        if(!empty($dist_scheme_id) && !empty($from) && !empty($to)){
            $dist_result = $this->User->query("SELECT * FROM distributor_schemes where id=$dist_scheme_id");
            if(!empty($dist_result)){
                if(!(($dist_result[0]['distributor_schemes']['validfrom'] != $from && $dist_result[0]['distributor_schemes']['validfrom'] > date('Y-m-d') && $from > date('Y-m-d')) ||  $dist_result[0]['distributor_schemes']['validfrom'] == $from)){
                    return json_encode(array('error'=>1,'msg'=>'Not a valid from date'));
                }
                if(!(($dist_result[0]['distributor_schemes']['validto'] != $to && $dist_result[0]['distributor_schemes']['validto'] > date('Y-m-d') && $to > date('Y-m-d')) || $dist_result[0]['distributor_schemes']['validto'] == $to)){
                    return json_encode(array('error'=>1,'msg'=>'Not a valid to date'));
                }
                
                $sql = "UPDATE distributor_schemes SET validfrom='$from',validto='$to',updatedat='".date('Y-m-d H:i:s')."',updated_userid='".$_SESSION['Auth']['User']['id']."' where id=$dist_scheme_id";
                $this->User->query($sql);
                
                return json_encode(array('error'=>0,'msg'=>'Scheme for a distributor edited successfully'));
            }
            else {
                return json_encode(array('error'=>1,'msg'=>'Distributor scheme not found'));
            }
            
        }
        else{
            return json_encode(array('error'=>1,'msg'=>'Please use valid params'));
        }
    }
    
     
    
}
