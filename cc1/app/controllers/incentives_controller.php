<?php
class IncentivesController extends AppController{
    var $name = 'Incentives';
    var $components = array('RequestHandler', 'Shop', 'General','Incentive');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator');
    var $uses = array('User', 'Slaves');

    function beforeFilter(){
        parent::beforeFilter();
        set_time_limit(0);
        ini_set("memory_limit", "512M");
        $this->Auth->allow('*');
        $whitelist_ips = explode(",", CRON_WHITELIST_IP);
        
        /*if( ! in_array($_SERVER['REMOTE_ADDR'], $whitelist_ips)){
            return;
        }*/
    }

    function schemePanel($scheme_id,$notify=0,$notify_type=0){
        $this->layout = 'plain';
        $return = array();
        $scheme_info = $this->Slaves->query("SELECT * FROM retailer_schemes WHERE id = '$scheme_id' AND is_active = '1'");
        if(!empty($scheme_info)) {
            $data = $this->Slaves->query("SELECT retailer_schemes_data.*,retailers.user_id,retailers.mobile FROM retailer_schemes_data left join retailers ON (retailers.id = retailer_schemes_data.retailer_id) WHERE scheme_id = '$scheme_id'");
            $ids = array();
            $final_rets = array();
            $cols = array();
            foreach($data as $dt){
                $ids[] = $dt['retailers']['user_id'];
                $ret_data = json_decode($dt['retailer_schemes_data']['data'],true);
                $final_rets[$dt['retailers']['user_id']] = $ret_data;
                $final_rets[$dt['retailers']['user_id']]['mobile'] = $dt['retailers']['mobile'];
                $final_rets[$dt['retailers']['user_id']]['incentive_given'] = $dt['retailer_schemes_data']['incentive'];
                $cols = array_keys($ret_data);
            }
            
            $new_cols = array('datewise_sale');
            $scheme_dates = array('from_date'=>$scheme_info[0]['retailer_schemes']['start_date'], 'to_date'=>$scheme_info[0]['retailer_schemes']['end_date']);
            
            $new_data = $this->Incentive->getData($ids, $new_cols, $scheme_dates, $scheme_info[0]['retailer_schemes']['service_ids']);
            $return['new_data'] = $new_data;
            $return['old_data'] = $final_rets;
            $return['scheme_dates'] = $scheme_dates;
            $return['scheme_info'] = array('name'=>$scheme_info[0]['retailer_schemes']['name'],'incentive'=>$scheme_info[0]['retailer_schemes']['incentive'],'service_ids'=>$scheme_info[0]['retailer_schemes']['service_ids']);
            $return['new_data_cols'] = $new_cols;
            $return['old_data_cols'] = $cols;
        }
        
        if($notify > 0){
            $this->Incentive->notify($return,$notify,$notify_type);
        }
        
        if($scheme_dates['to_date'] > date('Y-m-d')){
            $scheme_dates['to_date'] = date('Y-m-d');
        }
        if($scheme_dates['from_date'] > date('Y-m-d')){
            $scheme_dates['from_date'] = date('Y-m-d');
        }
        $return['scheme_dates'] = $scheme_dates;
        
        $this->set('data', $return);
        $this->set('scheme_id', $scheme_id);
        $this->set('notify', $notify);
        $this->set('notify_type', $notify_type);
    }
    
        
    function getRetailerSchemes()
    {
        $this->layout = 'plain';
        Configure::load('product_config');
        $services = Configure::read('services');
        $query = "SELECT rs.*,count(rsd.id) as ret_count "
                . "FROM retailer_schemes rs "
                . "LEFT JOIN retailer_schemes_data rsd "
                . "ON (rs.id = rsd.scheme_id) "
                . "WHERE is_active = '1' "
                . "GROUP BY rs.id";
        
        $ret_schemes = $this->User->query($query);
        
        $this->set('services',$services);
        $this->set('ret_schemes',$ret_schemes);
    }
    
    function uploadRetailerSchemesDetails()
    {
        $this->autoRender = false;
        $params = $this->params['form'];
        $scheme_name = $params['scheme_name'];
        $scheme_tag = $params['scheme_tag'];
        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        $incentive = $params['incentive'];
        $service_ids = implode(',', $params['service_ids']);
              
//        if($this->RequestHandler->isPost()){
        if($this->RequestHandler->isAjax())
        {           
            $validation_res = $this->Incentive->schemePanelValidation($params);
            
            if($validation_res['status'] == "success")
            {                   
                if($_FILES['retfile']['size'] > 0)
                {
                    $allowedExtension = array("csv");
                    $getfileInfo = pathinfo($_FILES['retfile']['name'], PATHINFO_EXTENSION);
                    if (in_array($getfileInfo, $allowedExtension)) 
                    {    
                        $document_path = $_FILES['retfile']['tmp_name'];
                        if (($handle = fopen($document_path, "r")) !== FALSE) 
                        {     
                            $headers = fgetcsv($handle);
                            $headers[0] = 'retailer_id';
                            $headers[1] = 'ret_target';
                            while (($data = fgetcsv($handle, 0, ",")) !== FALSE){
                                $temp[] = array_combine($headers, $data);

                            }

                            fclose($handle);
                        }
                        
                        $scheme_id = $this->Incentive->addRetailerSchemes($scheme_name,$scheme_tag,$start_date,$end_date,$incentive,$service_ids);
                        
                        if($scheme_id)
                        {
                            foreach($temp as $key => $val)
                            {
                                $retailer_id = $val['retailer_id'];
                                $target = floatval(str_replace(",","",$val['ret_target']));
                                $uploaded_at = date('Y-m-d H:i:s');
                                $param = addslashes(json_encode($val));
                                $insert_scheme_data = $this->User->query("INSERT INTO retailer_schemes_data(scheme_id,retailer_id,target,data,incentive,uploaded_at) VALUES('$scheme_id','$retailer_id','$target','$param','0','$uploaded_at') ");
                            }
                            $response = array('status'=>'success','description'=>'File uploaded successfully!','type'=>'0');
                        }
                    }
                    else 
                    {
                        $response = array('status'=>'failure','description'=>'Invalid File Format!','type'=>'2');
                    }
                }
                else
                { 
                    $response = array('status'=>'failure','description'=>'Kindly Upload your file','type'=>'2');
                } 
            }
            else
            {   
                $description = is_array($validation_res['description'])?implode(',',array_values($validation_res['description'])):$validation_res['description'];
                $response = array('status'=>'failure','description'=>$description,'type'=>'2');    
            }
            echo json_encode($response);
            exit;
        }
    }
    
    function deleteScheme()
    {
        $this->autoRender = false;
        $scheme_id = $this->params['form']['scheme_id'];
        
        $query = "UPDATE retailer_schemes "
                . "SET is_active = '0' "
                . "WHERE id = '$scheme_id'";
        
        $this->User->query($query);
        
        $response = array('status'=>'success','description'=>'Scheme deleted successfully!','type'=>'0');
        echo json_encode($response);
        exit;
    }
}
