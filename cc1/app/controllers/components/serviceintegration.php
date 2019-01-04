<?php
class ServiceintegrationComponent extends Object{

    var $components = array('General', 'Shop', 'RequestHandler','B2cextender', 'Recharge', 'Documentmanagement');


    function getServicePlans()
    {
        $Object = ClassRegistry::init('Slaves');
        // $service_plans = $this->Shop->getMemcache("service_plans");
        $service_plans = '';
        if(empty($service_plans))
        {
            $plans = $Object->query('SELECT * FROM service_plans WHERE is_visible=1');

            if(!empty($plans))
            {
                foreach($plans as $plan)
                {
                    $service_plans[$plan['service_plans']['service_id']][$plan['service_plans']['plan_key']] = $plan['service_plans'];
                }

                $service_plans = json_encode($service_plans);
                $this->Shop->setMemcache("service_plans", $service_plans , 24*60*60);

            }
        }

        return $service_plans;
    }
    function getServiceProductPlans()
    {
        $Object = ClassRegistry::init('Slaves');
        // $service_product_plans = $this->Shop->getMemcache("service_product_plans");
        $service_product_plans = '';
        if(empty($service_product_plans))
        {
            $plans = $Object->query('SELECT * FROM service_product_plans');

            if(!empty($plans))
            {
                foreach($plans as $plan)
                {
                    $service_product_plans[$plan['service_product_plans']['service_plan_id']][$plan['service_product_plans']['product_id']] = $plan['service_product_plans'];
                }

                $service_product_plans = json_encode($service_product_plans);
                $this->Shop->setMemcache("service_product_plans", $service_product_plans , 24*60*60);

            }
        }

        return $service_product_plans;
    }

    function getPlanRetMarginDetails($prod_id,$service_id,$user_id){
          $Object = ClassRegistry::init('Slaves');

        $service_plans_Margin = '';
        if(empty($service_plans_Margin))
        {
            $margins = $Object->query('select us.user_id,us.service_plan_id,spp.ret_params,spp.dist_params from users_services as us
                                    LEFT JOIN service_product_plans as spp ON (us.service_plan_id = spp.service_plan_id)
                                    where us.service_id = "'.$service_id.'" and spp.product_id = "'.$prod_id.'" and us.user_id IN ("'.$user_id.'")');


            if(!empty($margins)){
                foreach($margins as $margin){
                    $plan_ret_margin[$margin['us']['user_id']]         = $margin['spp']['ret_params'];
                }
            }
        }
        return  $plan_ret_margin;
    }
            function getProductVendors()
    {
        $Object = ClassRegistry::init('Slaves');
        // $product_vendors = $this->Shop->getMemcache("product_vendors");
        $product_vendors = '';
        if(empty($product_vendors))
        {
            $vendors = $Object->query('SELECT * FROM product_vendors');

            if(!empty($vendors))
            {
                foreach($vendors as $vendor)
                {
                    $product_vendors[$vendor['product_vendors']['service_id']][$vendor['product_vendors']['id']] = $vendor['product_vendors'];
                }

                $product_vendors = json_encode($product_vendors);
                $this->Shop->setMemcache("product_vendors", $product_vendors , 24*60*60);

            }
        }

        return $product_vendors;
    }

function InsertServices($servcName,$servcType,$servcRegist,$toShow){
    $Object = ClassRegistry::init('User');
    $insertServices = $Object->query("Insert into services(name,service_type,registration_type,toShow) "
    . "values('$servcName',$servcType,$servcRegist,$toShow)");
        return $insertServices;

}

    function setServicefields($params)
    {
        echo '<pre>';
        print_r($params);
        echo '</pre>';
//            $id = $data['siid'];
//        $key = $data['sikey'];
//        $label = $data['silabel'];
//        $type = $data['sitype'];
//        $regex = $data['siregex'];
//        $valid = $data['sivalidation'];
//        $default = $data['sidefault'];
//        if ($id != 'null') {
//            $Object = ClassRegistry::init('User');
//            $serviceFieldParamsIns = $Object->query("INSERT INTO `service_fields`(`key`, `label`, `type`, `regex`, `validation`, `default_values`) "
//                    . "VALUES ('$key','$label','$type','$regex','$valid','$default')");
//
//            $Object2 = ClassRegistry::init('Slaves');
//            $fldid = $Object2->query('select MAX(id) as fldid  from service_fields');
//            $fieldid = $fldid[0][0]['fldid'];
//            $serviceFieldIns = $Object->query('INSERT INTO `service_fields`(`service_id`, `field_id`) VALUES (' . $id . ',' . $fieldid . ')');
//        }
        return $serviceFieldIns;
    }


    function updServicefields($data){
        $id = $data['upid'];
        $key = $data['upkey'];
        $label = $data['uplabel'];
        $type = $data['uptype'];
        $regex = $data['upregex'];
        $valid = $data['upvalidation'];
        $default = $data['updefault'];
        if(!empty($key) || !empty($label) || !empty($type) || !empty($regex) || !empty($valid) || !empty($default) ){
         $Object = ClassRegistry::init('User');
         $serviceFieldsUpd = $Object->query('UPDATE service_fields SET `key` = "'. $key .'",label = "'. $label .'",type = "'. $type .'",regex = "'. $regex .'",validation = "'. $valid .'",default_values = '. json_encode($default) .' WHERE id = "'. $id .'" ');
        }
        return $serviceFieldsUpd;
    }
    function getServiceFields($servId){
        $Object = ClassRegistry::init('Slaves');

        $servicefield = $Object->query('SELECT * from service_fields as sf
                                        where sf.service_id = '.$servId.' ');

        return $servicefield;
    }

    function getAllServices()
    {
        $Object = ClassRegistry::init('Slaves');
        // $services = $this->Shop->getMemcache("services");
        $services = '';
        if(empty($services))
        {
            $sql = "SELECT id,name FROM services where toShow = 1";
            $data = $Object->query($sql);
            foreach($data as $row){
                $services[$row['services']['id']] = $row['services']['name'];
            }
            $services = json_encode($services);
            $this->Shop->setMemcache("services", $services, 24*60*60);
        }
        return $services;
    }
    function getServiceDetails()
    {
        $Object = ClassRegistry::init('Slaves');
        // $services = $this->Shop->getMemcache("services_details");
        $services = '';

        if(empty($services))
        {
            $sql = "SELECT * FROM services where toShow = 1";
            $data = $Object->query($sql);
            foreach($data as $row){
                $services[$row['services']['id']] = $row['services'];
            }
            $services = json_encode($services);
            $this->Shop->setMemcache("services_details", $services, 24*60*60);
        }
        return $services;
    }

    /*for returning services */
    function getServices()
    {
        $Object = ClassRegistry::init('Slaves');
        // $services = $this->Shop->getMemcache("services");
        $services = '';
        if(empty($services))
        {
            $sql = "SELECT id,name FROM services where toShow = 1";
            $data = $Object->query($sql);
            foreach($data as $row){
                $services[$row['services']['id']] = $row['services']['name'];
            }
        }
        return $services;
    }


    function getKYCname(){
             $Object = ClassRegistry::init('Slaves');
                 $data = $Object->query('SELECT * FROM imp_labels WHERE type = 1');
                 return $data;

    }

    function setKYCdetail($id,$val){

        $Object = ClassRegistry::init('User');
        if (is_array($val)) {

            $sql = "INSERT INTO imp_label_service_acl (label_id, service_id, has_access) values ";

            $valuesArr = array();
            foreach ($val as $row) {

                $label_id = (int) $row;
                $service_id = $id;
                $access = 1;

                $valuesArr[] = "('$label_id', '$service_id', '$access')";
            }
            $sql .= implode(',', $valuesArr);
            $data = $Object->query($sql);

        }

        return $data;
    }

    function getKYCdetail($id){
     $Object = ClassRegistry::init('Slaves');

     $sql = "Select il.label,services.name,ilsa.* from  `imp_label_service_acl` as ilsa JOIN  imp_labels as il on (ilsa.label_id = il.id)"
             . "LEFT JOIN services ON (ilsa.service_id = services.id ) "
             . "where ilsa.service_id = '$id'" ;

     $data = $Object->query($sql);
            return $data;
    }

    function delKYCdetail($delservId,$delkycval){
        $Object = ClassRegistry::init('User');
        $sql = "Delete  from imp_label_service_acl where label_id = '$delservId' and service_id = '$delkycval' ";

        $data = $Object->query($sql);
        return $data;
    }

    function setProductdetail($servId, $product_name,$product_creationtime){
        $Object = ClassRegistry::init('User');

        $sql = "Insert into products (service_id,parent,name,created)  values ($servId,'0',$product_name,$product_creationtime)";
        $data = $Object->query($sql);
        return $data;

    }

    function getServiceNames(){
        $Object = ClassRegistry::init('Slaves');
            $sql = "Select * from services";
            $data = $Object->query($sql);
        return $data;
    }



    function updPlanData($data){
        $id         = $data['id'];
        $servid     = $data['serviceid'];
        $key        = $data['plankey'];
        $plan       = $data['plan'];
        $planstatus = $data['planstatus'];
        $settleamt  = $data['settleamt'];
        $rentamt    = $data['rentamt'];
        $distcomm   = $data["distcomm"];

        $Object = ClassRegistry::init('User');
        $sql = "UPDATE service_plans SET service_id = '$servid' ,plan_key = '$key',plan_name = '$plan',is_visible = '$planstatus',"
                . " setup_amt = '$settleamt',rental_amt= '$rentamt',dist_commission = '$distcomm'  WHERE id = '$id' ";
        $datas = $Object->query($sql);
        return $datas;
    }

    function getProductDet($service_id){
        $Object = ClassRegistry::init('Slaves');
        $sql = "Select * from products where service_id	 = '$service_id'";
        $data = $Object->query($sql);
        return $data;
    }

    function updProductsData($data){
        $id         = $data['pid'];
        $name       = $data['pname'];
        $min        = $data['pmin'];
        $max        = $data['pmax'];
        $prodstatus = $data['pprodstatus'];
        $gst        = $data['pgst'];
        $earning    = $data['pearning'];
        $earningf   = $data['pearningf'];
        $type       = $data['ptype'];
        $tds        = $data['ptds'];
        $margin     = $data['pmargin'];

        $Object = ClassRegistry::init('User');
        $sql =  "UPDATE products SET name = '$name' ,min = '$min',max = '$max',active = '$prodstatus',"
                . " earning_type_flag = '$earningf',earning_type = '$earning',type = '$type',tds = '$tds',expected_earning_margin = '$margin'  WHERE id = '$id' ";
        $datas = $Object->query($sql);
        return $datas;
    }

    function delProductsData($id){
        $Object = ClassRegistry::init('User');
        $sql = "Delete from products where id = '$id'" ;
        $data = $Object->query($sql);
        return $data;
    }

    function setServicePartner($data){
        $key        = $data['key'];
        $name       = $data['name'];
        $salt       = $data['salt'];
        $callback   = $data['callback'];
        $redirect   = $data['redirect'];
        $params     = $data['params'];
        $seckey     = $data['seckey'];


        $Object = ClassRegistry::init('User');
        $sql = 'INSERT INTO `service_partners`( `key`, `name`, `salt`, `callback`, `redirect`, `params`,`secret_key`) VALUES ("' . $key . '","' . $name . '","' . $salt .'","' . $callback .'","' . $redirect .'","' . $params .'","'. $seckey .'")';

        $datas = $Object->query($sql);
        return $datas;
    }

    function updservcPartner($data){

        $id           = $data['partnerid'];
        $key          = $data['key'];
        $name         = $data['name'];
        $callback     = $data['callback'];
        $redirect     = $data['redirect'];
        $params       = $data['params'];
        $salt         = $data['salt'];
        $seckey       = $data['seckey'];

        $Object = ClassRegistry::init('User');
        $sql =  "UPDATE `service_partners` SET `key`= '". $key."',`name`='". $name ."',`salt`= '". $salt ."',"
                . "`callback`= '". $callback ."',`redirect`= '". $redirect ."',`params`= '". $params ."',`secret_key`= '". $seckey ."' WHERE id = '$id' ";

        $datas = $Object->query($sql);
        return $datas;
    }


        function updVendor($data){

        $id           = $data['vendorid'];
        $name         = $data['name'];
        $partnerid     = $data['partnerid'];


        $Object = ClassRegistry::init('User');
        $sql =  "UPDATE `product_vendors` SET `name`='" . $name. "',`service_partner_id`= $partnerid "
                . " WHERE id = '$id' ";

        $datas = $Object->query($sql);
        return $datas;
    }
    function getKitStatus($kit_flag_value = ''){
        if($kit_flag_value != ''){
            $kit_statuses =  Configure::read('kit_status');
            $label = $kit_statuses[$kit_flag_value];
            return $label;
        }
        return $kit_flag_value;
    }

    function getServiceStatus($service_flag_value = ''){
        if($service_flag_value != ''){
            $service_statuses =  Configure::read('service_status');
            $label = $service_statuses[$service_flag_value];
            return $label;
        }
        return $service_flag_value;
    }


}




