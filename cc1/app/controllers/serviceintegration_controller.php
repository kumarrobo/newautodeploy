<?php
class ServiceintegrationController extends AppController {
    
    var $name = 'Serviceintegration';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator', 'GChart','Csv');
    var $components = array('RequestHandler', 'Shop', 'Bridge','Serviceintegration','General');           
    var $uses = array('User','Slaves');   
    
    function servicesForm(){
        $this->layout = 'plain';        
        $servcDetails       =  $this->Slaves->query("Select * from services");
        $servcs             =  $this->Slaves->query("Select id,name from services");
        $servcPartnerList   =  $this->Slaves->query("Select id,name from service_partners");
        foreach($servcPartnerList as $data){
            $partener_list[$data['service_partners']['id']] = $data['service_partners']['name'];
        }
        $getKYCname = $this->Serviceintegration->getKYCname();
        
        if($this->RequestHandler->isAjax()) {
            $upservcIncs = array();
            $upservID       = $this->params['form']['upservID'];
            $upservcStatus  = $this->params['form']['servStatus'];
            $upservcType    = $this->params['form']['servType'];
            $upservcRegist  = $this->params['form']['servRegist'];
            $upservcPartner = $this->params['form']['servcPartner'];
            $upservcInc     = $this->params['form']['servcInc'];
            $upservcIncservc= $this->params['form']['servcIncservice'];
            $upservcGST     = $this->params['form']['servcGst'];
            array_unshift($upservcIncservc, $upservID);
            
                $updtservcDetails = $this->User->query( "Update services set toShow = $upservcStatus ,service_type = '$upservcType' ,"
                . " registration_type = '$upservcRegist', partner_id = '$upservcPartner' , inc_type_flag = $upservcInc,"
                    . " inc_adj_services = " . json_encode(implode(',',$upservcIncservc)) .", gst = $upservcGST where id = $upservID ");
            if($updtservcDetails){
            echo json_encode(array(
                        'status' => 'success',
                        'msg' => 'Service Update Successfully'
                    ));
            exit;
            }else{
            echo json_encode(array(
                        'status' => 'failure',
                        'description' => 'Service Updation Failure'
                    ));
            exit;
            }
        }
        $this->set('getKYCname',$getKYCname);
        $this->set('servcDetails',$servcDetails);
        $this->set('servcDetail',$servcs);
//        $this->set('servcPartner',$servcPartnerList);
        $this->set('servcPartner',$partener_list);        
        
    }
    function servicesInsert(){
        $this->autoRender = FALSE;      
        $servcName = $this->params['form']['servcName'];
        $servcType      = $this->params['form']['servcType'];
        $servcRegist    = $this->params['form']['servcRegist'];
        $servcPartner   = $this->params['form']['servcPartner'];
        $servcIncservice= $this->params['form']['servcIncservice'];
        $servcInc       = $this->params['form']['servcInc'];
        $servcGST       = $this->params['form']['servcGST'];
        $toShow         = 1;                
        $dataSource = $this->User->getDataSource();
        $dataSource->begin();
        if(isset($servcName)) {
           $insertServices = $dataSource->query("Insert into services(name,service_type,registration_type,toShow,partner_id,inc_type_flag,inc_adj_services,gst) "
                                . "values('$servcName',$servcType,$servcRegist,$toShow,$servcPartner,$servcInc, ".json_encode(implode(',',$servcIncservice)) .",$servcGST)");
            $partnerid = $dataSource->lastInsertId();
            if($partnerid) {
                $partnerId = $dataSource->query("Update  services set `parent_id` = $partnerid  where id = $partnerid" );

                if($insertServices) {
                    $dataSource->commit();                
                    echo json_encode($insertServices);
                } else {
                    $dataSource->rollback();
                }
            } else {
                $dataSource->rollback();
            }
        }
            
    }
       function serviceFields(){                        
        $this->autoRender = FALSE;                           
        if($_POST) {
            if (is_array($_POST)) {
                $servcId = $_POST['fields'][0]['fieldval'];
                $sql = "INSERT INTO service_fields(`key`, `label`, `type`, `regex`, `validation`, `default_values`, `service_id`) values";
                $valuesArr = array();
                foreach ($_POST['fields'] as $field) {
                    $valuesArr[] = "('" . $field['fieldkey'] . "','" . $field['fieldlab'] . "','" . $field['fieldtype'] . "','" . $field['fieldregex'] . "','" . $field['fieldvalid'] . "','" . $field['fielddef'] . "'," . $servcId . ")";
                }
                $sql .= implode(',', $valuesArr);
                $InsFields = $this->User->query($sql);
            }
            //$servicefields = $this->Serviceintegration->setServicefields($fieldval);           
        return json_encode($InsFields);
    }
        
      
    }
    function updserviceFields(){
        $this->autoRender = FALSE;             
        
        //Updating fields
        $upd_field_id   = $this->params['form']['upfid'];
        $upd_fieldKey   = $this->params['form']['upfield_key'];
        $upd_fieldLab   = $this->params['form']['upfield_lab'];
        $upd_fieldType  = $this->params['form']['upfield_type'];
        $upd_fieldRegex = $this->params['form']['upfield_regex'];
        $upd_fieldValid = $this->params['form']['upfield_valid'];
        $upd_fieldDef   = $this->params['form']['upfield_def'];                            
             $data = array(
            'upid'          => $upd_field_id,
            'upkey'         => $upd_fieldKey,
            'uplabel'       => $upd_fieldLab,
            'uptype'        => $upd_fieldType,
            'upregex'       => $upd_fieldRegex,
            'upvalidation'  => $upd_fieldValid,
            'updefault'     => $upd_fieldDef                 
            );
        $upserviceFields = $this->Serviceintegration->updServicefields($data);
        return json_encode($upserviceFields);
        }
    
    function getFieldData(){
        $this->autoRender = FALSE;
        $servId = $this->params['form']['id'];            
        $getservicefields = $this->Serviceintegration->getServiceFields($servId);
        
        return json_encode($getservicefields);

    }
    
    function setKYCData(){
     $this->autoRender = FALSE;
        $delservId = $this->params['form']['kycdservid'];            
        $delkycval = $this->params['form']['kycddata']; 
        $delKYCdata = $this->Serviceintegration->delKYCdetail($delservId,$delkycval);
       return json_encode($delKYCdata);
       
    }
    
    function insKYCData(){
        $this->autoRender = FALSE;
        $servId = $this->params['form']['kycid'];            
        $kycval = $this->params['form']['kycdata'];         
        
        $setKYCdata = $this->Serviceintegration->setKYCdetail($servId,$kycval);
        return json_encode($setKYCdata);
    }
            
    function getKYCData(){
        $this->autoRender = FALSE;
        $servId = $this->params['form']['kycid'];
        
        $getKYCdata = $this->Serviceintegration->getKYCdetail($servId);
        return json_encode($getKYCdata);       
    }
    
    function setProductData() {
        $this->autoRender = FALSE;
        $servId = $this->params['form']['kycid'];
        $product_name = $this->params['form']['productname'];
        $product_creationtime = date('Y-m-d H:i:s');        
        $setProductdata = $this->Serviceintegration->setProductdetail($servId,$product_name,$product_creationtime);
        return json_encode($setProductdata);
    }

    function servicesPlans($plantype,$service_id)
    {
        
        $this->layout = 'plain';                
        
            $servicePlans = $this->Slaves->query("Select * from service_plans");
            $services_name = $this->Serviceintegration->getServiceNames();
            $servicename = array();
            foreach ($services_name as $serv_name) {
            $servicename[$serv_name['services']['id']] = $serv_name['services']['name'];
            }
                                    
         if(isset($service_id)){                
            $servicePlans = $this->Slaves->query("Select * from service_plans  where service_id = '$service_id'");            
         }   
         
         $prodDet = $this->Slaves->query("Select id,name from  products where service_id =  $service_id ");
             
        
        $this->set('prodDet',$prodDet); 
        $this->set('servicePlans',$servicePlans);
        $this->set('service_id',$service_id);
        $this->set('prodtype',$plantype);
        $this->set('servicename',$servicename);
        $this->set('services_name',$services_name);
                
    }
    
    function updPlanDetails(){
        $this->autoRender = FALSE;
        
        $spid       = $this->params['form']['id'];
        $serviceid  = $this->params['form']['serviceid'];
        $plankey    = $this->params['form']['plankey'];
        $plan       = $this->params['form']['plan'];
        $planstatus = $this->params['form']['planstatus'];
        $settleamt  = $this->params['form']['settleamt'];
        $rentamt    = $this->params['form']['rentamt'];
        $distcomm   = $this->params['form']['distcomm'];
        
        $data = array(
            'id'   => $spid,
           'serviceid'   => $serviceid,
            'plankey'    => $plankey,
            'plan'       => $plan,
            'planstatus' => $planstatus,
            'settleamt'  => $settleamt,
            'rentamt'    => $rentamt,
             'distcomm'  => $distcomm 
        );
        
        
        
        $updPlanData =  $this->Serviceintegration->updPlanData($data);
        return json_encode($updPlanData);
    }
    
    
    function servicesProducts($prodtype,$service_id){       
        $this->layout = 'plain';                
        
        $services_name = $this->Serviceintegration->getServiceNames();
        
        $servicename = array();
        foreach ($services_name as $serv_name){
            $servicename[$serv_name['services']['id']] = $serv_name['services']['name'];
        }
        
        $serviceProductDet = $this->Serviceintegration->getProductDet($service_id);
        $product_id = $this->Slaves->query('SELECT id FROM products ORDER BY ID DESC LIMIT 1');        
        $vendors_name = $this->Slaves->query('Select id,name from product_vendors');
        $servicesPlanDet  =  $this->Slaves->query('SELECT id,plan_name service_plans FROM  service_plans where service_id =  '. $service_id .'');        
        
        $this->set('product_id',$product_id[0]['products']['id']);
        $this->set('vendors_name',$vendors_name);
        $this->set('serviceProductDet',$serviceProductDet);
        $this->set('service_id',$service_id);
        $this->set('servicesPlanDet',$servicesPlanDet);
        $this->set('servicename',$servicename);
        $this->set('prodtype',$prodtype);
    }
    
    function updProductsDetails(){
        $this->autoRender = FALSE;        
        $prodid       = $this->params['form']['id'];
        $prodname     = $this->params['form']['upprodname'];
        $prodmin      = $this->params['form']['upprodmin'];
        $prodmax      = $this->params['form']['upprodmax'];
        $prodstatus   = $this->params['form']['upprodstatus'];
        $prodearnflag = $this->params['form']['upprodearningf'];
        $prodearn     = $this->params['form']['upprodearn'];
        $prodtds      = $this->params['form']['upprodtds'];
        $prodmargin   = $this->params['form']['upprodmargin'];
        $prodtype     = $this->params['form']['upprodtype'];
        $delid        =  $this->params['form']['delprodid'];     
        
        
        if(!empty($delid)){
        $delProductsData =  $this->Serviceintegration->delProductsData($delid);
        return json_encode($delProductsData);       
        }
        $data = array(
           'pid'          => $prodid,
           'pname'        => $prodname,
           'pmin'         => $prodmin,
           'pmax'         => $prodmax,
           'pprodstatus'  => $prodstatus,           
           'pearning'     => $prodearn,
           'ptype'        => $prodtype,
           'pearningf'    => $prodearnflag,
           'ptds'         => $prodtds,
           'pmargin'      => $prodmargin,
        );
        
        $updProductsData =  $this->Serviceintegration->updProductsData($data);
        return json_encode($updProductsData);       
    }
    
    function InsProductDetails(){
        $this->autoRender = FALSE;        
        $valuesarr[] = array();
            
      if(is_array($_POST))   {
        $sql = "Insert into products(service_id,parent,name,min,max,earning_type,type,earning_type_flag,tds,expected_earning_margin) values";
        $valuesArr = array();
        foreach($_POST['products'] as $prod){            
         $valuesArr[] = "(".$prod['serviceid'].",".$prod['parentid'].",'".$prod['prodname'] ."',".$prod['prodmin'].",".$prod['prodmax'].",".$prod['prodearningtype'].",".$prod['prodtype'].",".$prod['prodearningflag'].",".$prod['prodtds'].",".$prod['emargin'].")";
         
        }        
        $sql .= implode(',', $valuesArr);        
        
        $InsProduct = $this->User->query($sql);  
        return json_encode($InsProduct);       
        
        } 
    }
 
    function InsPlanDetails(){
        $this->autoRender = FALSE;  
        
        if(is_array($_POST)) {
           $dataSource = $this->User->getDataSource();
           $dataSource->begin();
           
            $InsPlan = $dataSource->query("INSERT INTO `service_plans`(`service_id`, `plan_key`, `plan_name`, `setup_amt`, `rental_amt`,`dist_commission`,`dist_rental_commission`) "
                    . "VALUES (".$_POST['plans'][0]['servc_name'].",'".$_POST['plans'][0]['plan_key']."','".$_POST['plans'][0]['plan_name'] ."',".$_POST['plans'][0]['settlement_amt'].",".$_POST['plans'][0]['rental_amt'].",".$_POST['plans'][0]['dist_comm'].",".$_POST['plans'][0]['rent_comm'].")");         
            $planid = $dataSource->lastInsertId();
            if($planid) {            
                $username = $this->Session->read('Auth.User.id');
                    $sql = "INSERT INTO `service_product_plans`(`product_id`, `service_plan_id`, `ret_params`, `dist_params`, `created_by`,`created_date`,`created_at`) VALUES";                    
                    
                    $valuesArr = array();
                    $prod_ids=array();
                   foreach($_POST['prod'] as $row) {
                    $prod_ids[]=$row['prod_id'];
                    $ret_array = array();
                    $dist_array = array();
                    foreach($row['slabs'] as $slab_index => $slab) {                        
                        $slab_key = $slab['slabs'];                   
                        unset($slab['slabs']);                    
                        $ret_array[$slab_key] = $slab;
                   }
                                       
                    foreach($row['dslabs'] as $slab) {
                        $slab_key = $slab['slabs'];                    
                        unset($slab['slabs']);         
                        $dist_array[$slab_key] = $slab;
                    }    
                   
                    $valuesArr[] = "(" . $row['prod_id'] . "," . $planid . ",'" . json_encode($ret_array) . "','" . json_encode($dist_array) . "'," . $username . ",'" . date("Y-m-d") . "','" . date("Y-m-d H:i:s") . "')";
                   }
                   if(count($valuesArr) > 0 ){
                        if( count($prod_ids) != count(array_unique($prod_ids)) ){
                            echo json_encode(array(
                                'status' => 'failure',
                                'description' => 'You cannot select same product multiple times'
                            ));exit;
                        }
                    $sql .= implode(',', $valuesArr);                                          
                    $planMargin = $dataSource->query($sql);                
                if($planMargin) {
                    $dataSource->commit();                
                    echo json_encode(array(
                        'status' => 'success',
                        'msg' => 'Plan created successfully'
                    ));exit;
                } else {
                    $dataSource->rollback();
                    echo json_encode(array(
                        'status' => 'failure',
                        'description' => 'Something went wrong.Please try again'
                    ));exit;
                }
            } else {
                echo json_encode(array(
                    'status' => 'failure',
                    'description' => 'Please map atleast one product with the plan'
                ));exit;
            }
            } else {
                $dataSource->rollback();
                echo json_encode(array(
                    'status' => 'failure',
                    'description' => 'Something went wrong.Please try again'
                ));exit;
            }
        }
    } 
    
    function  setPlansDetails($plainid = null){        
        $this->layout = 'plain';
        
        $planDetails = $this->Slaves->query("SELECT sp.*,sm.* from `service_plans` as sp  
                                              LEFT JOIN service_product_plans as sm ON (sp.id = sm.service_plan_id)
                                            WHERE sp.id = $plainid");        
        $service_id = $planDetails[0]['sp']['service_id'];
        
        
       $prodDet = $this->Slaves->query("Select id,name from  products where service_id = $service_id ");
       $prodid = $planDetails[0]['sm']['product_id'];       
                

        $this->set('prodid',$prodid);
        $this->set('planDetails',$planDetails);
        $this->set('prodDet',$prodDet);
      
    }
           
    function updPlansDetails(){
        $this->autoRender = FALSE;
        if(is_array($_POST)) {
          //  exit;
            $dataSource = $this->User->getDataSource();
            $dataSource->begin();
                $UpdPlan = $dataSource->query("Update `service_plans` set `plan_key` = '".$_POST['up']['plan_key']."', `plan_name` = '".$_POST['up']['plan_name']."'"
                        . ", `setup_amt`= ".$_POST['up']['settlement_amt'].", `rental_amt`= ".$_POST['up']['rental_amt'].""
                        . ",`dist_commission`= ".$_POST['up']['dist_comm']." ,`dist_rental_commission`= ".$_POST['up']['rent_comm']." WHERE  id = ".$_POST['up']['plan_id']." ");                   
                
                
                if($UpdPlan){                                            
                $username = $this->Session->read('Auth.User.id');                
                    $sql = "INSERT INTO `service_product_plans`(`product_id`, `service_plan_id`, `ret_params`, `dist_params`, `created_by`,`created_date`,`created_at`) VALUES";                                        
                    $valuesArr = array();
                    $prod_ids=array();
                   foreach($_POST['uprod'] as $row) {
                       if(!empty($row['prod_id'])){
                    $prod_ids[]=$row['prod_id'];
                    $ret_array = array();
                    $dist_array = array();
                    
                    
                    foreach($row['slabs'] as $slab) {                        
                        $slab_key = $slab['slabs'];                   
                        unset($slab['slabs']);                    
                        $ret_array[$slab_key] = $slab;
                   }
                                       
                    foreach($row['dslabs'] as $slab) {
                        $slab_key = $slab['slabs'];                    
                        unset($slab['slabs']);         
                        $dist_array[$slab_key] = $slab;
                    }    
                   
                    $valuesArr[] = "(" . $row['prod_id'] . "," . $_POST['up']['plan_id'] .",'". json_encode($ret_array) . "','" . json_encode($dist_array) . "'," . $username . ",'" . date("Y-m-d") . "','" . date("Y-m-d H:i:s") . "')";
                   }
                   }
                   
                   if(count($valuesArr) > 0 ){
                        if( count($prod_ids) != count(array_unique($prod_ids)) ){                            
                            echo json_encode(array(
                                'status' => 'failure',
                                'description' => 'You cannot select same product multiple times'
                            ));exit;
                        }
                    $sql .= implode(',', $valuesArr);               
//                    echo $sql;exit;
                    $delete = $dataSource->query('Delete from service_product_plans where service_plan_id = '.$_POST['up']['plan_id']);
//                    if($delete){
                    $planMargin = $dataSource->query($sql);                
                    
                    if($planMargin) {
                        $dataSource->commit();                
                        echo json_encode(array(
                            'status' => 'success',
                            'msg' => 'Plan Updated successfully'
                        ));exit;
                    } else {
                        $dataSource->rollback();
                        echo json_encode(array(
                            'status' => 'failure',
                            'description' => 'Something went wrong.Please try again'
                        ));exit;
                    }
//                   }else{
//                       $dataSource->rollback();
//                        echo json_encode(array(
//                            'status' => 'failure',
//                            'description' => 'Something went wrong.Please try again'
//                        ));exit;
//                   }
                } else{
                    $dataSource->commit();                
                    echo json_encode(array(
                        'status' => 'success',
                        'msg' => 'Plan Updated successfully'
                    ));exit;
                }
            }
            
             else {
                $dataSource->rollback();
                echo json_encode(array(
                    'status' => 'failure',
                    'description' => 'Something went wrong.Please try again'
                ));exit;
            }
    }   
    }
            
    function InsServicePartner(){
        $this->layout = "plain";
        
        if($this->RequestHandler->isAjax()){

            $data = array(
            'key'       => $_POST['servcPartKey'],
            'seckey'    => $_POST['servcPartSecKey'],
            'name'      => $_POST['servcPartname'],
            'salt'      => $_POST['servcPartsalt'],
           'callback'   => $_POST['servcPartcallback'],
           'redirect'   => $_POST['servcPartredirect'],
            'params'    => $_POST['servcPartParams']
            );
            
        $InsServicePartner = $this->Serviceintegration->setServicePartner($data);           
            echo json_encode($InsServicePartner);
            die;
        }
        
    }
    
    function ListServicePartner(){
        $this->layout = "plain";       
        $servicePartner = $this->Slaves->query('select * from service_partners');                
        $this->set('servicePartner',$servicePartner);
    }
    
        
    function updServicePartner(){
        $this->autoRender = FALSE;                
        $data = array(
           'partnerid'  => $this->params['form']['id'],
           'key'        => $this->params['form']['key'],
           'name'       => $this->params['form']['name'],
           'salt'       => $this->params['form']['salt'],
           'callback'   => $this->params['form']['callback'],
           'redirect'   => $this->params['form']['redirect'],
           'params'     => $this->params['form']['params'],
           'seckey'     => $this->params['form']['seckey']
           
        );
        
        $updservicePartner =  $this->Serviceintegration->updservcPartner($data);
        return json_encode($updservicePartner);       
    }
    
    
    function serviceVendor(){
        $this->layout = "plain";
        
        $servicePartner = $this->Slaves->query('select id,name from service_partners');
        
        $vendors = $this->Slaves->query('select * from product_vendors ');
         $partnername = array();
         
         foreach($vendors as $partner){
             $partnerid[] = $partner['product_vendors']['service_partner_id'];
         }         
        $partnerno = implode(',',$partnerid);         
        $this->set('vendors',$vendors);
        $this->set('partnerno',$partnerno);
        $this->set('servicePartner',$servicePartner);
    }
    
    function InsVendor(){
        $this->autoRender = FALSE;  
         $name      = $this->params['form']['vendorName'];
         $partnerid = $this->params['form']['servicePartner'];                        
         $productid = $this->params['form']['vendor'][0]['servicevendor'];
         $type      = $this->params['form']['upvendor'][0]['optradio'];
         $prodid    = $this->User->query('Select service_id from products where id = '. $productid .'');
         $service   = ($prodid[0]['products']['service_id']);
        $dataSource = $this->User->getDataSource();
        $dataSource->begin();
        
        if(isset($name)) {
           $InsVendor = $dataSource->query("INSERT INTO `product_vendors`(`name`, `service_partner_id`,`service_id`,`type_flag`)"
                                            . " VALUES ('" . $name. "',$partnerid,$service,$type )");
            $vendorid = $dataSource->lastInsertId();            
            if($vendorid){               
                $valuearr[] = array();                
                     $sql = "INSERT INTO `product_vendor_margins`(`product_id`, `vendor_id`, `margin`) "
                        . "VALUES ";                     
                $valuesArr = array();
                $prod_id = array();
                foreach($_POST['vendor'] as $row) {                       
                $prod_id[] = $row['servicevendor'];
                $ven_array = array();                
                foreach($row['slabs'] as $slab_index => $slab) {
                $slab_key = $slab['slabs'];
                unset($slab['slabs']);
                $ven_array[$slab_key] = $slab;
                }
                $valuesArr[] = "(" . $row['servicevendor'] . ",'" . $vendorid . "','" . json_encode($ven_array) . "')";
                } 
                
                   if(count($valuesArr) > 0 ){                       
                        if( count($prod_id) != count(array_unique($prod_id)) ){
                            echo json_encode(array(
                                'status' => 'failure',
                                'description' => 'You cannot select same product multiple times'
                            ));exit;
                        }
                    $sql .= implode(',', $valuesArr);                                          
                    $vendorMargin = $dataSource->query($sql);                
                    if($vendorMargin) {
                        $dataSource->commit();                
                        echo json_encode(array(
                            'status' => 'success',
                            'msg' => 'Vendor Created successfully'
                        ));exit;
                    } else {
                        $dataSource->rollback();
                        echo json_encode(array(
                            'status' => 'failure',
                            'description' => 'Something went wrong.Please try again'
                        ));exit;
                    }
                   
//                   else{
//                       $dataSource->rollback();
//                        echo json_encode(array(
//                            'status' => 'failure',
//                            'description' => 'Something went wrong.Please try again'
//                        ));exit;
//                   }
                } else{
                    $dataSource->commit();                
                    echo json_encode(array(
                        'status' => 'success',
                        'msg' => 'Vendor Created successfully'
                    ));exit;
                }
            }
            
             else {
                $dataSource->rollback();
                echo json_encode(array(
                    'status' => 'failure',
                    'description' => 'Something went wrong.Please try again'
                ));exit;
            }
    }   
          
    }
    
    function updVendors(){
        $this->autoRender = FALSE;

        if(is_array($_POST)) {            
        $dataSource = $this->User->getDataSource();
        $dataSource->begin();
        
            $productid  = $_POST['upvendor'][0]['servicevendor'];
            $type       = $_POST['upvendor'][0]['optradio'];
            $prodid     = $this->User->query('Select service_id from products where id = ' . $productid . '');
            $service_Id = $prodid[0]['products']['service_id'];


            $UpdVendor = $dataSource->query("UPDATE `product_vendors`  SET  `name` = '" . $_POST['upvendorName'] . "', `service_partner_id`= " . $_POST['servicePartner'] . " ,"
                    . "service_id = " . $service_Id . ",`type_flag` = ".$type." where id = ".$_POST['vendor_id']."");

            if($UpdVendor) {
              
                     $sql = "INSERT INTO `product_vendor_margins`(`product_id`, `vendor_id`, `margin`) "
                        . "VALUES ";                     
                $valuesArr = array();                
                $prod_id = array();
                foreach($_POST['upvendor'] as $row) {                       
                $prod_id[] = $row['servicevendor'];
                $ven_array = array();
                foreach($row['slabs'] as $slab_index => $slab) {
                $slab_key = $slab['slabs'];
                unset($slab['slabs']);
                $ven_array[$slab_key] = $slab;
                }
                $valuesArr[] = "(" . $row['servicevendor'] . ",'" . $_POST['vendor_id'] . "','" . json_encode($ven_array) . "')";
                }
                   if(count($valuesArr) > 0 ){
                        if( count($prod_id) != count(array_unique($prod_id)) ){                            
                            echo json_encode(array(
                                'status' => 'failure',
                                'description' => 'You cannot select same product multiple times'
                            ));exit;
                        }
                    $sql .= implode(',', $valuesArr);                                   
                    
                    $delete = $dataSource->query('Delete from product_vendor_margins where vendor_id = '.$_POST['vendor_id']);
                    if($delete){
                    $vendorMargin = $dataSource->query($sql);                                    
                    
                    if($vendorMargin) {
                        $dataSource->commit();                
                        echo json_encode(array(
                            'status' => 'success',
                            'msg' => 'Vendor Updated successfully'
                        ));exit;
                    } else {
                        $dataSource->rollback();
                        echo json_encode(array(
                            'status' => 'failure',
                            'description' => 'Something went wrong.Please try again'
                        ));exit;
                    }
                   }
//                   else{
//                       $dataSource->rollback();
//                        echo json_encode(array(
//                            'status' => 'failure',
//                            'description' => 'Something went wrong.Please try again'
//                        ));exit;
//                   }
                } else{
                    $dataSource->commit();                
                    echo json_encode(array(
                        'status' => 'success',
                        'msg' => 'Vendor Updated successfully'
                    ));exit;
                }
            }
            
             else {
                $dataSource->rollback();
                echo json_encode(array(
                    'status' => 'failure',
                    'description' => 'Something went wrong.Please try again'
                ));exit;
            }
    }   
          
    }
    
    function setVendorDetails($vendorId = null){
        $this->layout = 'plain';

        $servicePartner = $this->Slaves->query('select id,name from service_partners');
        $vendors        = $this->Slaves->query("select * from product_vendors ");
        $partnername   = array();
         foreach($vendors as $partner){
             $partnerid[] = $partner['product_vendors']['service_partner_id'];
         }
        $vendorDet =  $this->Slaves->query("SELECT pv.*,pvm.* from `product_vendors` as pv  
                                             LEFT JOIN product_vendor_margins as pvm ON (pv.id = pvm.vendor_id)
                                            WHERE pv.id = $vendorId");
        $partnerId = $vendorDet[0]['pv']['service_partner_id'];
        
        $prodDet = $this->Slaves->query("SELECT s.id,s.name,p.id,p.name FROM `services` as s
                                 JOIN products as p ON (s.id = p.service_id)
                                 WHERE s.partner_id = " . $partnerId . "  ");

        $partnerno = implode(',',$partnerid);         
        $this->set('vendors',$vendors);
        $this->set('vendorDet',$vendorDet);
        $this->set('prodDet',$prodDet);
        $this->set('partnerno',$partnerno);
        $this->set('servicePartner',$servicePartner);        
        
    }
    
    function prodListing(){
        $this->autoRender = False;        
        
        $partnerId = $this->params['form']['id'];
        
        $proddet = $this->Slaves->query("SELECT s.id,s.name,p.* FROM `services` as s
                                         JOIN products as p ON (s.id = p.service_id)
                                         WHERE s.partner_id = ". $partnerId ."  ");
        
        echo json_encode($proddet);
    }
            
    
    function getProductPlanDetails(){
        $this->autoRender = false;

        $productId = $_POST['productId'];
        
       $productPlanDet  = $this->Slaves->query('Select * from service_product_plans  where product_id = '.$productId.'');
        
        return json_encode($productPlanDet);
    }
    
}

 
