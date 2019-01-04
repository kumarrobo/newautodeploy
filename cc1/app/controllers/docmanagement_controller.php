<?php
class DocmanagementController extends AppController {
    var $name = 'Docmanagement';
    var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
    var $uses = array('User','Slaves','DocManagement');
    var $components = array('RequestHandler','Shop','Documentmanagement','Email','Platform','Serviceintegration');

    function beforeFilter()
    {
        set_time_limit(0);
        ini_set("memory_limit", "-1");
        parent::beforeFilter();
    }

    function verifyUserDocs()
    {
        Configure::load('bridge');
        $notification_url = Configure::read('notification_url');
        $user_id = $this->params['form']['user_id'];
        $label_type = $this->params['form']['label_type'];
        $uploaded_by = $verifier_id = $_SESSION['Auth']['User']['id'];
        $pay1_status = $this->params['form']['pay1_status'];
        $pay1_comment = isset($this->params['form']['pay1_comment'])?$this->params['form']['pay1_comment']:'';
        $ip_address = $this->RequestHandler->getClientIP();      
        $label_info = $this->params['form']['label_info'];   
        $labels = $this->Documentmanagement->getImpLabels();
        
        $redis = $this->Shop->redis_connect();

        $redis->del("impdata_0_".$user_id);
        $redis->del("impdata_1_".$user_id);
        $redis->del("impdata_2_".$user_id);
        $redis->del("impdata_3_".$user_id);
        $redis->del("impdata_4_".$user_id);
        
        $response = array();
        $flag = 0;
        $data = array();
        if($label_type == 1){
            $label_id = $this->params['form']['label_id'];
            $ref_code = $label_id . $user_id . date("YmdHis");
            $update_sql = $this->User->query('UPDATE imp_label_status_history '
                                            . 'SET pay1_status = "'.$pay1_status.'",pay1_comment = "'.$pay1_comment.'",verifier_id = "'.$verifier_id.'",updated_date = "'.date("Y-m-d").'",updated_at = "'.date("Y-m-d H:i:s").'" '
                                            . 'WHERE user_id = "'.$user_id.'" AND label_id = "'.$label_id.'" AND is_latest = "1" ');
            
            if($update_sql){
                $flag = 1;
                $doc_response[] = array(
                        'user_id'=>$user_id,
                        'label_id'=>$label_id,
                        'pay1_status'=>$pay1_status,
                        'pay1_comment'=>$pay1_comment
                         );                    
            }
        }else{
            $invalid_labels = array();
            
            foreach ($label_info as $label_id=>$label_data) {
                $ref_code = $label_id . $user_id . date("YmdHis");
                $label_data['user_id'] = $user_id;
                $label_data['label_type'] = $label_type;
                $curr_desc = $label_data['curr_desc'];
                $prev_desc = $label_data['prev_desc'];
                
                if($pay1_status == 1){
                $validation_res = $this->Documentmanagement->uploadDocsPanelValidation($label_data);
               
                if($validation_res['status']=="success"){            
                    $labelcheck = $this->Documentmanagement->checkIfLabelExists($user_id,$label_id);

                    if($labelcheck['status'] == 'success'){
        //                $id = $labelcheck['data'][0]['lsh']['id'];
                        if(count($labelcheck['data']) > 1){
                            if($pay1_status == 1){
                                $update_sql1 = $this->User->query('UPDATE imp_label_status_history '
                                                . 'SET pay1_status = "2",verifier_id = "'.$verifier_id.'",updated_date = "'.date("Y-m-d").'",updated_at = "'.date("Y-m-d H:i:s").'" '
            //                                            . 'WHERE id="'.$id.'" ');
                                                . 'WHERE user_id = "'.$user_id.'" AND label_id = "'.$label_id.'" AND pay1_status = "1" ');

                                $update_sql2 = $this->User->query('UPDATE imp_label_status_history '
                                                    . 'JOIN imp_label_upload_history ON (imp_label_status_history.ref_code = imp_label_upload_history.ref_code) '
                                                    . 'SET imp_label_status_history.pay1_status = "'.$pay1_status.'",imp_label_status_history.pay1_comment = "'.$pay1_comment.'",imp_label_status_history.verifier_id = "'.$verifier_id.'",imp_label_status_history.updated_date = "'.date("Y-m-d").'",imp_label_status_history.updated_at = "'.date("Y-m-d H:i:s").'",imp_label_upload_history.description = "'.$curr_desc.'" '
                //                                            . 'WHERE id="'.$id.'" ');
                                                    . 'WHERE imp_label_status_history.user_id = "'.$user_id.'" AND imp_label_status_history.label_id = "'.$label_id.'" AND imp_label_status_history.pay1_status = "0" ');

                                if($update_sql1 && $update_sql2){
                                    $flag = 1;
                                    $data[$label_id] = array('curr_desc'=>$curr_desc,'prev_desc'=>'');
                                    $data[$label_id]['input_type'] = $labels[$label_id]['input_type'];
                                }else{
                                    $invalid_labels[] = $labels[$label_id]['label'];
                                }

                            }elseif ($pay1_status == 2) {
                                $update_sql1 = $this->User->query('UPDATE imp_label_status_history '
                                                    . 'JOIN imp_label_upload_history ON (imp_label_status_history.ref_code = imp_label_upload_history.ref_code) '
                                                    . 'SET imp_label_status_history.pay1_status = "'.$pay1_status.'",imp_label_status_history.pay1_comment = "'.$pay1_comment.'",imp_label_status_history.verifier_id = "'.$verifier_id.'",imp_label_status_history.updated_date = "'.date("Y-m-d").'",imp_label_status_history.updated_at = "'.date("Y-m-d H:i:s").'",imp_label_upload_history.description = "'.$curr_desc.'" '
                //                                            . 'WHERE id="'.$id.'" ');
                                                    . 'WHERE imp_label_status_history.user_id = "'.$user_id.'" AND imp_label_status_history.label_id = "'.$label_id.'" AND imp_label_status_history.pay1_status = "0" ');
                                if($update_sql1){
                                    $flag = 1;
                                    $data[$label_id] = array('curr_desc'=>$prev_desc,'prev_desc'=>'');
                                    $data[$label_id]['input_type'] = $labels[$label_id]['input_type'];
                                }else{
                                    $invalid_labels[] = $labels[$label_id]['label'];
                                }
                            }                        
                        }elseif(count($labelcheck['data']) == 1 && $labelcheck['data'][0]['lsh']['pay1_status'] == 0){
                            $update_sql = $this->User->query('UPDATE imp_label_status_history '
                                                . 'JOIN imp_label_upload_history ON (imp_label_status_history.ref_code = imp_label_upload_history.ref_code) '
                                                . 'SET imp_label_status_history.pay1_status = "'.$pay1_status.'",imp_label_status_history.pay1_comment = "'.$pay1_comment.'",imp_label_status_history.verifier_id = "'.$verifier_id.'",imp_label_status_history.updated_date = "'.date("Y-m-d").'",imp_label_status_history.updated_at = "'.date("Y-m-d H:i:s").'",imp_label_upload_history.description = "'.$curr_desc.'" '
        //                                            . 'WHERE id="'.$id.'" ');
                                                . 'WHERE imp_label_status_history.user_id = "'.$user_id.'" AND imp_label_status_history.label_id = "'.$label_id.'" AND imp_label_status_history.pay1_status = "0" ');
                            if($update_sql){
                                $flag = 1;
                                $data[$label_id] = array('curr_desc'=>$curr_desc,'prev_desc'=>'');
                                $data[$label_id]['input_type'] = $labels[$label_id]['input_type'];
                            }else{
                                $invalid_labels[] = $labels[$label_id]['label'];
                            }
                        }elseif(count($labelcheck['data']) == 1 && $labelcheck['data'][0]['lsh']['pay1_status'] == 1){
                            $update_sql = $this->User->query('UPDATE imp_label_status_history '
                                            . 'SET pay1_status = "2",verifier_id = "'.$verifier_id.'",updated_date = "'.date("Y-m-d").'",updated_at = "'.date("Y-m-d H:i:s").'" '
        //                                            . 'WHERE id="'.$id.'" ');
                                            . 'WHERE user_id = "'.$user_id.'" AND label_id = "'.$label_id.'" AND pay1_status = "1" ');

                            $insert_sql1 = $this->User->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                        . "VALUES('$label_id','$user_id','$service_id','$ref_code','$curr_desc','$uploaded_by','$ip_address','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "') ");

                            $insert_sql2 = $this->User->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,verifier_id,created_date,created_at,updated_date,updated_at)"
                                                            . "VALUES('$label_id','$user_id','$ref_code','1','0','$uploaded_by','$verifier_id','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "')");
                            if($update_sql && $insert_sql1 && $insert_sql2){
                                $flag = 1;
                                $data[$label_id] = array('curr_desc'=>$curr_desc,'prev_desc'=>'');
                                $data[$label_id]['input_type'] = $labels[$label_id]['input_type'];
                            }else{
                                $invalid_labels[] = $labels[$label_id]['label'];
                            }
                        }
                    }else{                
                            $insert_sql1 = $this->User->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                            . "VALUES('$label_id','$user_id','$service_id','$ref_code','$curr_desc','$uploaded_by','$ip_address','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "') ");

                            $insert_sql2 = $this->User->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,verifier_id,created_date,created_at,updated_date,updated_at)"
                                                                . "VALUES('$label_id','$user_id','$ref_code','1','0','$uploaded_by','$verifier_id','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "')");            
                            if($insert_sql1 && $insert_sql2){
                                $flag = 1;
                                $data[$label_id] = array('curr_desc'=>$curr_desc,'prev_desc'=>'');
                                $data[$label_id]['input_type'] = $labels[$label_id]['input_type'];
                            }else{
                                $invalid_labels[] = $labels[$label_id]['label'];
                            }
                        }
                    }else{
                            $res = $validation_res;
                            $flag = 2;
                            $invalid_labels[] = $labels[$label_id]['label'];
                        }
                    }
                    elseif($pay1_status == 2){
                        $update_sql1 = $this->User->query('UPDATE imp_label_status_history '
                                                    . 'JOIN imp_label_upload_history ON (imp_label_status_history.ref_code = imp_label_upload_history.ref_code) '
                                                    . 'SET imp_label_status_history.pay1_status = "'.$pay1_status.'",imp_label_status_history.pay1_comment = "'.$pay1_comment.'",imp_label_status_history.verifier_id = "'.$verifier_id.'",imp_label_status_history.updated_date = "'.date("Y-m-d").'",imp_label_status_history.updated_at = "'.date("Y-m-d H:i:s").'",imp_label_upload_history.description = "'.$curr_desc.'" '
                //                                            . 'WHERE id="'.$id.'" ');
                                                    . 'WHERE imp_label_status_history.user_id = "'.$user_id.'" AND imp_label_status_history.label_id = "'.$label_id.'" AND imp_label_status_history.pay1_status = "0" ');

                        if($update_sql1){
                            $flag = 1;
                            $data[$label_id] = array('curr_desc'=>$prev_desc,'prev_desc'=>'');
                            $data[$label_id]['input_type'] = $labels[$label_id]['input_type'];
                        }else{
                            $invalid_labels[] = $labels[$label_id]['label'];
                        }
                    }
                    if($flag == 1){
                        $doc_response[] = array(
                                'user_id'=>$user_id,
                                'label_id'=>$label_id,
                                'pay1_status'=>$pay1_status,
                                'pay1_comment'=>$pay1_comment
                                 );
                    }
                }
        }
        
        if(count($invalid_labels) > 0){
            if(count($invalid_labels) == count($label_info)){
                $response = array('status'=>'failure','invalid_labels'=> implode(',', $invalid_labels));
                echo json_encode($response);
                exit;
            }
        }
        
        if(!empty($doc_response)){
            $services=$this->Documentmanagement->getUserServices($user_id);
            foreach ($services as $service) {
                if(array_key_exists($service,$notification_url) && !empty($notification_url[$service]))
                {
                    $this->General->curl_post_async($notification_url[$service], $doc_response, 'POST');
                }
            }
            $response = array('status'=>'success','msg'=> 'Document status updated successfully.','data'=>$data,'invalid_labels'=> implode(',', $invalid_labels));
        }
        echo json_encode($response);
        $this->autoRender = false;
    }

    function getUserDocuments()
    {
        $this->layout='';
        $params = $this->params['form']; // get data from post
        $user_id=$this->params['form']['user_id'];
        $dist_id=$this->params['form']['dist_id'];
        $mobile=$this->params['form']['mobile'];
        $pay1_status=$this->params['form']['pay1_status'];
        $bank_status=$this->params['form']['bank_status'];
        $this->DocManagement->set($params);
        Configure::load('platform');
        $labels=$this->Documentmanagement->getImpLabels();
        $services = Configure::read('services');
        $result=array();
        $doc_details=array();
        $distributor_list=$this->Documentmanagement->getDistributorList();

//        $documents=$this->Documentmanagement->getUserDocs($params);
        if(!empty($mobile))
        {
            $user_id=$this->Documentmanagement->getUserDocs($params);
            if(!empty($user_id))
            {
                $this->redirect(array('controller' => 'docmanagement', 'action' => 'userProfile/'.$user_id));
            }
            else
            {
                $this->Session->setFlash("<b>Error</b> :  Mobile number does not exist.");
            }
        }
//        $user_info=$this->Documentmanagement->getUserInfo($user_id);
//
//        $user_docs=$this->Documentmanagement->userStatusCheck($user_id);

        $this->set('distributors',$distributor_list);
        $this->set('user_docs',$documents);
        $this->set('services',$services);

    }

    function downloadImage()
    {
        Configure::load('platform');
        $user_id=$this->params['form']['user_id'];
//        $doc_id=$this->params['form']['doc_id'];
        $doc_id=118;
        $bucket=docbucket;

        App::import('vendor', 'S3', array('file' => 'S3.php'));
        $s3 = new S3(awsAccessKey, awsSecretKey);
        if(!empty($doc_id))
        {
            $userdata=$this->Slaves->query("SELECT url "
                                                                                . "FROM documents_logs "
                                                                                . "WHERE id = '$doc_id' ");
            if(count($userdata))
            {
                $document_path = $userdata[0]['documents_logs']['url'];
                $watermark = "/var/www/html/cc.pay1.com/app/webroot/img/pay1_logo.png";
                $imgUrl = 'http://' . $bucket . '.s3.amazonaws.com/' . $document_path;
                $newCopy="/tmp/".$document_path;
//                $this->Documentmanagement->getWatermarkImage($imgUrl,$watermark,$newCopy,$document_path);
                $this->Documentmanagement->getWatermarkImage($imgUrl,$watermark,$document_path);
//                echo $imgUrl;
//                $filepath="/tmp/".$document_path;
//                $img=$s3->getObject($bucket, $document_path,$filepath);
//                if($img)
//                {
//                        $zip->addFile($filepath, $path);
//                }
             }
        }

        $this->autoRender = false;
    }

    function downloadDocs()
    {
        Configure::load('platform');
        $user_id=$this->params['form']['user_id'];
//        $document_path=json_decode($this->params['form']['document_urls']);
        $document_path= explode(",", $this->params['form']['document_urls']);
        $bucket=docbucket;
        App::import('vendor', 'S3', array('file' => 'S3.php'));
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $zip = new ZipArchive;
        $filename = '/tmp/' .$user_id . '.zip';
//        $filename = $user_id . '.zip';
//        echo $filename;
//        die;
        $zip->open('/tmp/' . $user_id . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
//        $zip->open( $user_id . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($document_path as $path) {
            $filepath = "/tmp/" . $path;
            $newCopy = $filepath;
            $img = $s3->getObject($bucket, $path, $filepath);
//            $watermark = "/var/www/html/cc.pay1.com/app/webroot/img/pay1_logo.png";
            if ($img) {
                $zip->addFile($filepath);
//                        $watermark_img = $this->Documentmanagement->getWatermarkImage($filepath, $watermark, $newCopy, $path);
//                        $zip->addFile($watermark_img, $path);
            }
        }
        $zip->close();
//        $filename = $user_id . '.zip';
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$filename);
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        foreach ($document_path as $path)
        {
            unlink("/tmp/" . $path);
        }
        unlink('/tmp/' . $user_id . '.zip');

        $this->autoRender = false;
    }

    /*function userProfile($user_id = '')
    {
        $this->layout='';
        if(!$user_id || !is_numeric($user_id)){
           $this->redirect(array('controller' => 'docmanagement', 'action' => 'getUserDocuments'));
        }

        $params['user_id'] = $user_id;
        $profile_data = array();
//        $user_data = $this->Documentmanagement->getUserDocs($params);
        $user_data = $this->Documentmanagement->getUserInfo($params['user_id']);

        if(count($user_data)==0)
        {
            $this->redirect(array('controller' => 'docmanagement', 'action' => 'getUserDocuments'));
        }

        Configure::load('platform');
        $services = Configure::read('services');
        $pay1_status = Configure::read('pay1_status');

        if( count($user_data) > 0 ){

            // get area, city, state and pincode
            $location_info_query = 'SELECT area_id,latitude,longitude FROM retailers_location WHERE area_id > 0 and user_id = '.$params['user_id'];
            $location_info = $this->Slaves->query($location_info_query);

            if( count($location_info) > 0 ){


                $area_id = $location_info[0]['retailers_location']['area_id'];
                $lat = $location_info[0]['retailers_location']['latitude'];
                $long = $location_info[0]['retailers_location']['longitude'];


                $area_query = 'SELECT id,name,city_id,pincode FROM locator_area WHERE toShow =1  and id = '.$area_id;
                $areas_temp = $this->Slaves->query($area_query);

                $city_id = $areas_temp[0]['locator_area']['city_id'];
                $area = $areas_temp[0]['locator_area']['name'];
                $pincode = $areas_temp[0]['locator_area']['pincode'];

                $city_query = 'SELECT id,name,state_id FROM locator_city WHERE toShow =1 and id = '.$city_id;
                $cities_temp = $this->Slaves->query($city_query);

                $city = $cities_temp[0]['locator_city']['name'];
                $state_id = $cities_temp[0]['locator_city']['state_id'];

                $state_query = 'SELECT id,name FROM locator_state WHERE toShow =1 and id = '.$state_id;
                $states_temp = $this->Slaves->query($state_query);
                $state = $states_temp[0]['locator_state']['name'];


                $this->set('lat',$lat);
                $this->set('long',$long);
                $this->set('area',$area);
                $this->set('city',$city);
                $this->set('state',$state);
                $this->set('pincode',$pincode);

            }
            $service_ids = $this->Documentmanagement->getUserServices($params['user_id']);
            if(isset($user_data[0]['r']))
            {
                $profile_data['ret_info']['ret_name'] = $user_data[0]['r']['name'];
                $profile_data['ret_info']['ret_shop_name'] = $user_data[0]['r']['shopname'];
                $profile_data['ret_info']['ret_mobile'] = $user_data[0]['r']['mobile'];
                $profile_data['ret_info']['dist_name'] = $user_data[0]['d']['dname'];
                $profile_data['ret_info']['dist_company'] = $user_data[0]['d']['company'];
                $profile_data['ret_info']['ret_user_id'] = $user_data[0]['r']['ret_user_id'];
            }
            if(isset($user_data[0]['dist_info']))
            {
                $profile_data['dist_info']['dist_user_id'] = $user_data[0]['dist_info']['user_id'];
                $profile_data['dist_info']['dist_name'] = $user_data[0]['dist_info']['name'];
                $profile_data['dist_info']['dist_company'] = $user_data[0]['dist_info']['company'];
            }
            $user_services=array();
            foreach ($service_ids as $id)
            {
                $user_services[]=$services[$id];
            }
            $profile_data['user_id']=$user_id;
            $profile_data['services']= implode(",", $user_services);
        }
        $labels=Configure::read('doc_type');
        $documents=$this->Documentmanagement->userStatusCheck($params['user_id'],null,1);

        $response=array();
        $response[-1]= array();
        $response[-1]['label'] = 'General';
        foreach ($labels as $id=>$label)
        {
            if($label['type']==1)
            {
                $response[$id]=array();
                $response[$id]['label'] = $label['label'];
                foreach ($label['has_many'] as $textual_label_id)
                {
                    $response[$id]['textual_info'][$textual_label_id] = '';
                }
            }
            else if( ($label['type'] == 2) && ( empty($label['belongs_to']) ) ) {
                $response[-1]['textual_info'][$id] = '';
            }
        }
        if (count($documents) > 0) {

            foreach ($documents as $data)
            {
                if($labels[$data['lsh']['label_id']]['type']==2)
                {
                    $parent_label = $labels[$data['lsh']['label_id']]['belongs_to'];
                    $textual_label_id = $data['lsh']['label_id'];
                    $textual_label_value = $data[0]['description'];
                    if($parent_label){
                        $response[$parent_label]['textual_info'][$textual_label_id]=$textual_label_value;
                    } else {
                        $response[-1]['textual_info'][$textual_label_id]=$textual_label_value;
                    }
                }
                else
                {
                    $response[$data['lsh']['label_id']]['label']=$labels[$data['lsh']['label_id']]['label'];
                    $response[$data['lsh']['label_id']]['pay1_status']=$data['lsh']['pay1_status'];
                    $response[$data['lsh']['label_id']]['bank_status']=$data['lsh']['bank_status'];
                    $response[$data['lsh']['label_id']]['urls']= array_map(function($value){
                                                                            return 'http://'.docbucket.'.'.DOCUMENT_URL.$value;},
                                                                            explode(',',$data[0]['description'])
                                                                        );
                }

            }
        }
        $shop_types = $this->Shop->business_natureTypes();
        $location_types = $this->Shop->location_typeTypes();
        $turnover_types = $this->Shop->annual_turnoverTypes();
        $shoparea_types = $this->Shop->shop_area_typeTypes();
        $shop_ownership_types = $this->Shop->shop_ownershipTypes();

        $this->set('shop_types', $shop_types);
        $this->set('location_types', $location_types);
        $this->set('turnover_types', $turnover_types);
        $this->set('shoparea_types', $shoparea_types);
        $this->set('shop_ownership_types', $shop_ownership_types);

        $this->set('profileData',$profile_data);
        $this->set('pay1_status',$pay1_status);
        $this->set('documents',$response);
        $this->set('labels',$labels);
    }
*/
    function uploadDocs()
    {
        $this->autoRender = false;
        $params=$this->params['form'];
        $location_data = array();

        if( isset($params['location_data']) && !empty($params['location_data']) ){
            $long = (isset($params['location_data']['long']) && !empty($params['location_data']['long'])) ? $params['location_data']['long'] : null;
            $lat = (isset($params['location_data']['lat']) && !empty($params['location_data']['lat'])) ? $params['location_data']['lat'] : null;
            unset($params['location_data']);
            if( empty($lat) || empty($long) ){
                // $response = array('status' => 'failure','description' => 'Invalid Lat Long Information');
                // echo json_encode($response);
                // exit;
            }
        }

        $validation_res=$this->Documentmanagement->uploadDocsPanelValidation($params);

        $user_id = $params['user_id'];
        $label_ids = $params['label_id'];
        $documents = $params['document'];
        $uploaded_by = $_SESSION['Auth']['User']['id'];
        $ip_address = $this->RequestHandler->getClientIP();
        
        if($validation_res['status']=="success")
        {
            if(!is_array($label_ids))
            {
                if($documents && $documents['size'][0]!=0)
                {
                    $response=$this->Documentmanagement->updateDocumentInfo($user_id,$service_id=NULL,$label_ids,$documents,$uploaded_by);
                }
                else
                {
                    $response = array('status'=>'failure','description'=>'Kindly upload documents');
                }
            }
            else
            {
                foreach ($label_ids as $label_id=>$description)
                {
                    if($label_id == 12)
                    {
                       $date = date_create($description);
                       $description = date_format($date, "Y-m-d");
                    }
                    $response=$this->Documentmanagement->updateTextualInfo($user_id,$label_id,$service_id='',$description,$uploaded_by);
                }
            }

            if($lat && $long){
                $area_id = $this->General->getAreaIDByLatLong($long,$lat);
                if($area_id > 0 ){
                    $location_check_query = 'SELECT id FROM retailers_location WHERE user_id = '.$user_id;
                    $location_check = $this->Slaves->query($location_check_query);

                    if( count($location_check) > 0 ){
                        $location_update_query = 'UPDATE retailers_location SET area_id = '.$area_id.',latitude = '.$lat.',longitude = '.$long.' WHERE verified = 0 AND user_id = '.$user_id;
                        $location_update = $this->User->query($location_update_query);
                    } else {

                        $imp_data = $this->Shop->getUserLabelData($user_id,2,0);
                        $ret_id = '';
                        if( array_key_exists('ret',$imp_data[$user_id]) ){
                            $ret_id = $imp_data[$user_id]['ret']['id'];
                        } else if( array_key_exists('dist',$imp_data[$user_id]) ){
                            $ret_id = 0;
                        }
                        if( $ret_id !== '' ){
                            $location_insert_query = 'INSERT INTO retailers_location(retailer_id,area_id,latitude,longitude,updated,user_id,verified) VALUES ('.$ret_id.','.$area_id.','.$lat.','.$long.',"'.date('Y-m-d').'",'.$user_id.',0);';
                            $location_insert = $this->User->query($location_insert_query);
                        }
                    }
                }
            }

        }
        else
        {
            $response=$validation_res;
        }
        echo json_encode($response);
    }

    function getActiveUsers(){

        $active_users = array();
        $active_users = $this->Documentmanagement->getActiveUsers();
        $this->set('active_users',$active_users);
        $this->layout = 'plain';
        $this->render('/docmanagement/userlist');
    }
    
    function getUserInformation($mobile = null){
        $this->layout = '';
        $params = $this->params['url']; 
        $mobile = $this->params['url']['mobile'];
        
        $profile_data = array();
        if(!empty($mobile))
        {
            $user_id = $this->Documentmanagement->getUserDocs($params);
            
            if(!empty($user_id))
            {
                $user_data = $this->Documentmanagement->getUserInfo($user_id);
        
                if(count($user_data)==0)
                {
                    $this->Session->setFlash("<b>Error</b> :  User info does not exist.");
//                    $this->redirect(array('controller' => 'docmanagement', 'action' => 'getUserDocuments'));
                }

                Configure::load('platform');
                $services = Configure::read('services');
                $pay1_status_mapping = Configure::read('pay1_status');

                if( count($user_data) > 0 ){

                    // get area, city, state and pincode
                    $location_info_query = 'SELECT area_id,latitude,longitude FROM retailers_location WHERE area_id > 0 and user_id = '.$user_id;
                    $location_info = $this->Slaves->query($location_info_query);

                    if( count($location_info) > 0 ){


                        $area_id = $location_info[0]['retailers_location']['area_id'];
                        $lat = $location_info[0]['retailers_location']['latitude'];
                        $long = $location_info[0]['retailers_location']['longitude'];


                        $area_query = 'SELECT id,name,city_id,pincode FROM locator_area WHERE id = '.$area_id;
                        $areas_temp = $this->Slaves->query($area_query);

                        $city_id = $areas_temp[0]['locator_area']['city_id'];
                        $area = $areas_temp[0]['locator_area']['name'];
                        $pincode = $areas_temp[0]['locator_area']['pincode'];

                        $city_query = 'SELECT id,name,state_id FROM locator_city WHERE id = '.$city_id;
                        $cities_temp = $this->Slaves->query($city_query);

                        $city = $cities_temp[0]['locator_city']['name'];
                        $state_id = $cities_temp[0]['locator_city']['state_id'];

                        $state_query = 'SELECT id,name FROM locator_state WHERE id = '.$state_id;
                        $states_temp = $this->Slaves->query($state_query);
                        $state = $states_temp[0]['locator_state']['name'];


                        $this->set('lat',$lat);
                        $this->set('long',$long);
                        $this->set('area',$area);
                        $this->set('city',$city);
                        $this->set('state',$state);
                        $this->set('pincode',$pincode);

                    }
                    $service_ids = $this->Documentmanagement->getUserServices($user_id);
                    if(isset($user_data[0]['r']))
                    {
                        $profile_data['ret_info']['ret_name'] = $user_data[0]['r']['name'];
                        $profile_data['ret_info']['ret_shop_name'] = $user_data[0]['r']['shopname'];
                        $profile_data['ret_info']['ret_mobile'] = $user_data[0]['r']['mobile'];
                        $profile_data['ret_info']['dist_name'] = $user_data[0]['d']['dname'];
                        $profile_data['ret_info']['dist_company'] = $user_data[0]['d']['company'];
                        $profile_data['ret_info']['ret_user_id'] = $user_data[0]['r']['ret_user_id'];
                    }
                    if(isset($user_data[0]['dist_info']))
                    {
                        $profile_data['dist_info']['dist_user_id'] = $user_data[0]['dist_info']['user_id'];
                        $profile_data['dist_info']['dist_name'] = $user_data[0]['dist_info']['name'];
                        $profile_data['dist_info']['dist_company'] = $user_data[0]['dist_info']['company'];
                    }
                    $user_services=array();
                    foreach ($service_ids as $id)
                    {
                        $user_services[]=$services[$id];
                    }
                    $profile_data['user_id']=$user_id;
                    $profile_data['services']= implode(",", $user_services);
                }

                $labels = $this->Documentmanagement->getImpLabels();
                $sections = $this->Documentmanagement->getImpSections();
                $documents = $this->Documentmanagement->userStatusCheck($user_id,null,2);

                $response=array();
                foreach ($labels as $id => $label)
                {
                    if(!empty($label['section_ids'])){
                        $imp_sections = explode(',',$label['section_ids']);
                        foreach($imp_sections as $imp_sec){
                            $label_type = $label['type'] == 1?'documents':'textual';

                            $response[$imp_sec][$label_type][$id]['key'] = $label['key'];
                            $response[$imp_sec][$label_type][$id]['label'] = $label['label'];
                            $response[$imp_sec][$label_type][$id]['type'] = $label['type'];
                            $response[$imp_sec][$label_type][$id]['input_type'] = $label['input_type'];    
                            if( $label['input_type'] == 'dropdown'){
                                $response[$imp_sec][$label_type][$id]['default_values'] = $this->Documentmanagement->getDefaultValues($label['key']);
                            }
                        }  
                    }
                }
                
                if (count($documents) > 0) {

                    foreach ($documents[$user_id] as $label_id=>$data)
                    {
                        $imp_sections = explode(',',$labels[$label_id]['section_ids']);
                        $type = $data['type'];
                        unset($data['type']);
                        foreach($imp_sections as $imp_sec){
                            if($type == 1){
                                if(isset($response[$imp_sec]) && !empty($response[$imp_sec])){
                                    $response[$imp_sec]['documents'][$label_id]['label'] = $data['label'];
                                    $response[$imp_sec]['documents'][$label_id]['type'] = $type;
                                    $response[$imp_sec]['documents'][$label_id]['pay1_status'] = $data['pay1_status'];
                                    $response[$imp_sec]['documents'][$label_id]['bank_status'] = $data['bank_status'];                    
                                    $response[$imp_sec]['documents'][$label_id]['description'] = array_map(function($value){
                                                                                        return 'http://'.docbucket.'.'.DOCUMENT_URL.$value;},
                                                                                        explode(',',$data['description'])
                                                                                    );
                                }
                            }else{                       
                                $curr_desc = isset($data[0]['description'])?$data[0]['description']:$data[1]['description'];
                                $prev_desc = count($data) > 1?$data[1]['description']:'';
                                $pay1_status = isset($data[0]['pay1_status'])?$data[0]['pay1_status']:(isset($data[1]['pay1_status'])?$data[1]['pay1_status']:'');
                                $bank_status = isset($data[0]['bank_status'])?$data[0]['bank_status']:(isset($data[1]['bank_status'])?$data[1]['bank_status']:'');

        //                        foreach($data as $dt){
        //                            $response[$imp_sec]['textual'][$label_id]['label'] = $dt['label'];
                                if(isset($response[$imp_sec]) && !empty($response[$imp_sec])){
                                    $response[$imp_sec]['textual'][$label_id]['type'] = $type;
                                    $response[$imp_sec]['textual'][$label_id]['pay1_status'] = $pay1_status;
                                    $response[$imp_sec]['textual'][$label_id]['bank_status'] = $bank_status;
                                    $response[$imp_sec]['textual'][$label_id]['curr_desc'] = $curr_desc;
                                    $response[$imp_sec]['textual'][$label_id]['prev_desc'] = $prev_desc;
        //                            $response[$imp_sec]['textual'][$label_id][$dt['pay1_status']]['description'] = $dt['description'];
                                }
                            }
                        }
                    } 
                }
                
                $this->set('profileData',$profile_data);
                $this->set('pay1_status',$pay1_status_mapping);
                $this->set('documents',$response);
                $this->set('labels',$labels);
                $this->set('mobile',$mobile);
                $this->set('sections',$sections);
            }
            else
            {
                $this->Session->setFlash("<b>Error</b> :  Mobile number does not exist.");
            }
        }
    }
    
    function getPanStatus(){
        $this->autoRender = false;
        $params = $this->params['form'];
        $response = $this->Platform->getPanStatus(explode(",", $params['pan_no']));
//        $response = '{"status":"success","data":{"ACOPW8381Q":{"pan_number":"ACOPW8381Q","status_code":"E","status":"Existing and Valid PAN","last_name":"RATHOD","first_name":"VIKAS","middle_name":"JEETENDRA","pan_title":"Shri","last_update_date":"16\/08\/2017"}}}';
        
        echo json_encode($response);
    }
    
    function getUserSectionReport(){
        $this->layout = 'plain';
        $params = $this->params['form'];
        $pay1_status = array(0=>'Pending',1=>'Approved',2=>'Rejected');
        $page = isset($params['download']) ? $params['download'] : "";        
        
        Configure::load('platform');
        $status_list = Configure::read('pay1_status');
        $user_types = Configure::read('info_management_user_types'); 
        $handled_by_cond = '';
        $user_id_cond = '';
        $date_cond = '';
        $service_id_cond = '';
        if(!empty($params['user_id'])){
            $handled_by_cond = 'AND (luh.uploaded_by = '.$params['user_id'].' OR lsh.verifier_id = '.$params['user_id'].') ';
        }
        
        if(!empty($params['from_date']) && !empty($params['to_date'])){
            $date_cond = 'AND ((lsh.created_date >= "'.$params['from_date'].'" AND lsh.created_date <= "'.$params['to_date'].'") OR (lsh.updated_date >= "'.$params['from_date'].'" AND lsh.updated_date <= "'.$params['to_date'].'")) ';
        }
        
        if(!empty($params['mobile'])){
            $get_user_id = $this->Documentmanagement->getUserDocs($params);
            $user_id_cond = 'AND lsh.user_id = '.$get_user_id.' ';
        }        
        
        $groups = $this->Slaves->query('SELECT u.id as user_id,if(u.name IS NULL,u.mobile,u.name) as user_name,ug.group_id FROM users u JOIN user_groups ug ON (u.id = ug.user_id) WHERE u.active_flag = 1 AND ug.group_id IN (8,19,35,45)');
        foreach($groups as $user){
            $user_list[$user['ug']['group_id']][$user['u']['user_id']] = $user[0]['user_name'];            
        }
        $join_cond = '';
        if(!empty($params['user_type'])){
//            $user_ids = array_keys($user_list[$params['user_type']]);
//            $user_type_cond = 'AND (luh.uploaded_by IN ('.implode(',',$user_ids).') OR lsh.verifier_id IN ('.implode(',',$user_ids).')) ';
            $user_type_cond = 'AND ug.group_id = '.$params['user_type'].' ';
            $join_cond = 'JOIN user_groups ug ON (lsh.uploaded_by = ug.user_id OR lsh.verifier_id = ug.user_id) ';
        }        
        
        if(!empty($params['from_date']) && !empty($params['to_date'])){
//            $services = $this->Serviceintegration->getAllServices();
//            $services = json_decode($services,true);
            $labels = $this->Documentmanagement->getImpLabels();
            $sections = $this->Documentmanagement->getImpSections();
            $section_data = $this->Documentmanagement->getSectionLabelMapping();
//            $service_ids = !empty($params['service_id'])?array_keys($services):$params['service_id'];
//            $service_sections = $this->Documentmanagement->getServiceSections($service_ids);
            
            $ret_data = $this->Slaves->query('SELECT r.id as retailer_id,r.parent_id as distributor_id,r.user_id,r.mobile FROM retailers r');
            foreach ($ret_data as $ret){
                $retailers[$ret['r']['user_id']] = $ret['r'];
            }

            $user_group_data = $this->Slaves->query('SELECT ug.user_id,if(u.name IS NULL,u.mobile,u.name) as user_name,GROUP_CONCAT(ug.group_id) as group_id,GROUP_CONCAT(g.name) as group_name FROM users u JOIN user_groups ug ON (u.id = ug.user_id) JOIN groups g ON (ug.group_id = g.id) WHERE ug.group_id IN ("'.implode('","',array_values($user_types)).'") GROUP BY ug.user_id');

            foreach($user_group_data as $data){            
                $user_groups[$data['ug']['user_id']]['group_name'] = $data[0]['group_name'];
                $user_groups[$data['ug']['user_id']]['user_name'] = $data[0]['user_name'];
            }

            $get_documents = 'SELECT lsh.user_id,lsh.label_id,lsh.pay1_status,lsh.bank_status,lsh.ref_code,GROUP_CONCAT(luh.description) as description,lsh.created_date,lsh.created_at,lsh.updated_date,lsh.updated_at,lsh.uploaded_by,lsh.verifier_id,l.type,l.key,l.label '
                        . 'FROM imp_label_status_history lsh '
                        . 'JOIN imp_label_upload_history luh ON (lsh.ref_code = luh.ref_code) '
                        . 'JOIN imp_labels l ON (lsh.label_id = l.id) '
//                        . 'JOIN users u ON (lsh.uploaded_by = u.id OR lsh.verifier_id = u.id) '
                        . ''.$join_cond.' '
                        . 'WHERE 1 '
                        . 'AND lsh.is_latest = "1" AND l.type = 1 '
                        . ''.$handled_by_cond.' '.$user_id_cond.' '.$date_cond.' '.$service_id_cond.' '.$user_type_cond.' '
                        . 'GROUP BY lsh.user_id,lsh.label_id';

            $documents = $this->Slaves->query($get_documents);

            $uploaded_at = '';
            $uploaded_by = '';
            foreach ($documents as $index => $doc){
                $imp_sections = explode(',',$labels[$doc['lsh']['label_id']]['section_ids']);
                
                foreach($imp_sections as $imp_sec){
                    $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['status'] = $doc['lsh']['pay1_status'];
                    $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['uploaded_by'] = $doc['lsh']['uploaded_by'];
                    $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['verifier_id'] = $doc['lsh']['verifier_id'];
                    $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['uploaded_at'] = $doc['lsh']['created_at'];
                    $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['updated_at'] = $doc['lsh']['updated_at'];
                    $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['type'] = $doc['l']['type'];
                    $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['is_mandatory'] = $section_data[$sections[$imp_sec]['name']]['labels'][$doc['lsh']['label_id']]['is_mandatory'];
                }
            }
            
            $get_textual_labels = 'SELECT * FROM '
                    . '(SELECT luh.id, lsh.user_id,lsh.label_id,lsh.pay1_status,lsh.bank_status,lsh.pay1_comment,lsh.ref_code,luh.description,lsh.created_date,lsh.created_at,lsh.updated_date,lsh.updated_at,lsh.uploaded_by,lsh.verifier_id,l.type,l.key,l.label '
                    . 'FROM imp_label_upload_history luh '
                    . 'JOIN imp_label_status_history lsh ON (lsh.ref_code = luh.ref_code) '
                    . 'JOIN imp_labels l ON (luh.label_id = l.id) '
//                    . 'JOIN users u ON (lsh.uploaded_by = u.id OR lsh.verifier_id = u.id) '
                    . ''.$join_cond.' '
                    . 'WHERE 1 '
                    . 'AND l.type = 2 '
                    . ''.$handled_by_cond.' '.$user_id_cond.' '.$date_cond.' '.$service_id_cond.' '.$user_type_cond.' '
                    . 'ORDER BY luh.id DESC) as imp '
                    . 'GROUP BY user_id,label_id';

            $textual_labels = $this->Slaves->query($get_textual_labels);

            foreach ($textual_labels as $textual) {
                $imp_sections = explode(',',$labels[$textual['imp']['label_id']]['section_ids']);
                foreach($imp_sections as $imp_sec){
                    $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['status'] = $textual['imp']['pay1_status'];
                    $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['uploaded_by'] = $textual['imp']['uploaded_by'];
                    $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['verifier_id'] = $textual['imp']['verifier_id'];
                    $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['uploaded_at'] = $textual['imp']['created_at'];
                    $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['updated_at'] = $textual['imp']['updated_at'];            
                    $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['type'] = $textual['imp']['type'];            
                    $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['is_mandatory'] = $section_data[$sections[$imp_sec]['name']]['labels'][$textual['imp']['label_id']]['is_mandatory'];
                }
            }            
            
            $final = array();
            foreach ($response as $user_id => $sectionwise_data) {
                $first_label = current($sectionwise_data);

                $uploaded_at = $first_label['uploaded_at'];
                $uploaded_by = $first_label['uploaded_by'];

                $verified_at = $first_label['updated_at'];
                $verified_by = $first_label['verifier_id'];

                foreach ($sectionwise_data as $section_name => $labels) {
                    $section_label_count = count($section_data[$section_name]['labels']);                    
                    $final[$user_id]['sections'][$section_name]['status'] = ''; 
                    $final[$user_id]['sections'][$section_name]['approved_labels'] = array();
                    $label_count = count($labels);
                    $i = 0;
                    
                    $doc_labels = array_filter($labels,function($elm){
                        return ($elm['type'] == 1);
                    });
                    $tex_labels = array_filter($labels,function($elm){
                        return ($elm['type'] == 2);
                    });
                    
                    foreach($doc_labels as $label_id => $data){   // loop on documents
                        if( $data['status'] == 1 && ($final[$user_id]['sections'][$section_name]['status'] == 'Approved' || $final[$user_id]['sections'][$section_name]['status'] == '') ){
                            $final[$user_id]['sections'][$section_name]['status'] = 'Approved';
                            $final[$user_id]['sections'][$section_name]['approved_labels'][$label_id] = $label_id;
                        } else if($data['status'] == 0 && $data['is_mandatory'] == 1){
                            $final[$user_id]['sections'][$section_name]['status'] = 'Pending';
                        }
                        if($data['status'] == 2){
                            $i++;
                        }

                        if( strtotime($data['uploaded_at']) > strtotime($uploaded_at) ){
                            $uploaded_at = $data['uploaded_at'];
                            $uploaded_by = $data['uploaded_by'];
                        }

                        if( strtotime($data['updated_at']) > strtotime($verified_at) ){
                            $verified_at = $data['updated_at'];
                            $verified_by = $data['verifier_id'];
                        }
                    }
                    
                    $final[$user_id]['sections'][$section_name]['status'] = $final[$user_id]['sections'][$section_name]['status'] == '' ? 'Pending' : $final[$user_id]['sections'][$section_name]['status'];
                                            
                    foreach($tex_labels as $label_id => $data){  // loop on textual labels
                        if($data['status'] != 1){
                            $final[$user_id]['sections'][$section_name]['status'] = 'Pending';
                        }
                        if($data['status'] == 2){
                            $i++;
                        }
                        if($data['status'] == 1){
                            $final[$user_id]['sections'][$section_name]['approved_labels'][$label_id] = $label_id;
                        }

                        if( strtotime($data['uploaded_at']) > strtotime($uploaded_at) ){
                            $uploaded_at = $data['uploaded_at'];
                            $uploaded_by = $data['uploaded_by'];
                        }

                        if( strtotime($data['updated_at']) > strtotime($verified_at) ){
                            $verified_at = $data['updated_at'];
                            $verified_by = $data['verifier_id'];
                        }
                    }
                    if($label_count == $i){
                        $final[$user_id]['sections'][$section_name]['status'] = 'Rejected';
                    }
                    
                    foreach ($section_data[$section_name]['labels'] as $label_id => $label) {
                        if( $label['is_mandatory'] == 1 && (isset($final[$user_id]['sections'][$section_name]['approved_labels']) && !array_key_exists($label_id,$final[$user_id]['sections'][$section_name]['approved_labels']) )){
                           $final[$user_id]['sections'][$section_name]['status'] = 'Pending';
                        }
                    }
                }
                $final[$user_id]['uploaded_date'] = date('Y-m-d',strtotime($uploaded_at));
                $final[$user_id]['uploaded_at'] = $uploaded_at;
                $final[$user_id]['uploaded_by'] = $user_groups[$uploaded_by]['user_name'];
                $final[$user_id]['verified_at'] = $verified_at;
                $final[$user_id]['verified_by'] = $user_groups[$verified_by]['user_name'];
                $final[$user_id]['retailer_id'] = $retailers[$user_id]['retailer_id'];
                $final[$user_id]['distributor_id'] = $retailers[$user_id]['distributor_id'];
                $final[$user_id]['mobile'] = $retailers[$user_id]['mobile'];
                $final[$user_id]['group_name'] = $user_groups[$uploaded_by]['group_name']; 
            }
        }
        
        if($page=='download')
        {
            $this->downloadServiceActivationReport($final,$pay1_status,$params['status']);
        }
        
        $this->set('params',$params);
        $this->set('pay1_status',$pay1_status);
        $this->set('page',$page);
//        $this->set('services',$services);
        $this->set('status_list',$status_list);
        $this->set('user_types',$user_types);
        $this->set('user_list',$user_list);
//        $this->set('service_sections',$service_sections);
        $this->set('service_activation_details',$final);
    }
    
    function downloadServiceActivationReport($service_activation_details,$pay1_status,$input_status)
    {
        $this->autoRender = false;
        App::import('Helper','csv');
        $this->layout = null;
        $this->autoLayout = false;
        $csv = new CsvHelper();
        
        if(!empty($service_activation_details))
        {
            $line = array('#','Date','Retailer Mobile','Retailer ID','Dist Id','User Type','Documents','Uploaded by','Uploaded Date','Approve/Rejected by','Approve/Rejected Date');
            $csv->addRow($line);
            $i = 1;
            
            foreach($service_activation_details as $user_id => $section_details){
                $sec_status = '';
                foreach($section_details['sections'] as $section_name => $status){ 
//                        $section_status = isset($section_details['sections'][$section_name])?$section_details['sections'][$section_name]:'Pending';
                        if($input_status == ''){
                            $sec_status .= $section_name.' => ';
                            $sec_status.= $status.'; ';
                        }else{
                            if(strtolower($pay1_status[$input_status]) == strtolower($status)){
                                $sec_status.= $section_name.' => ';
                                $sec_status.= $status.'; ';
                            }
                        }
                }
                $line = array($i,$section_details['uploaded_date'],$section_details['mobile'],$section_details['retailer_id'],$section_details['distributor_id'],$section_details['group_name'],$sec_status,$section_details['uploaded_by'],$section_details['uploaded_at'],$section_details['verified_by'],$section_details['verified_at']);
                $csv->addRow($line);
                $i++;
            }
            echo $csv->render('service_activation_report'.date('Ymd').'.csv');
        }
        exit;
    }
    
    function sectionStatusSummaryReport(){
        $this->layout = 'plain';
        $params = $this->params['form'];
        $labels = $this->Documentmanagement->getImpLabels();
        $sections = $this->Documentmanagement->getImpSections();
        $section_data = $this->Documentmanagement->getSectionLabelMapping();

        Configure::load('platform');
        $status_list = Configure::read('pay1_status');
        $user_list =  Configure::read('info_management_user_types'); 
        $handled_by_cond = '';
        $user_id_cond = '';
        $date_cond = '';
        $service_id_cond = '';
        if(!empty($params['user_id'])){
            $handled_by_cond = 'AND (luh.uploaded_by = '.$params['user_id'].' OR lsh.verifier_id = '.$params['user_id'].') ';
        }
        
        if(!empty($params['from_date']) && !empty($params['to_date'])){
            $date_cond = 'AND ((lsh.created_date >= "'.$params['from_date'].'" AND lsh.created_date <= "'.$params['to_date'].'") OR (lsh.updated_date >= "'.$params['from_date'].'" AND lsh.updated_date <= "'.$params['to_date'].'")) ';
        }
        
        $groups = $this->Slaves->query('SELECT u.id as user_id,if(u.name IS NULL,u.mobile,u.name) as user_name,ug.group_id FROM users u JOIN user_groups ug ON (u.id = ug.user_id) WHERE u.active_flag = 1 ');
        foreach($groups as $user){
            $user_list[$user['ug']['group_id']][$user['u']['user_id']] = $user[0]['user_name'];
        }
       
        if(!empty($params['from_date']) && !empty($params['to_date'])){           
            
            $get_documents = 'SELECT lsh.user_id,lsh.label_id,lsh.pay1_status,lsh.bank_status,lsh.ref_code,GROUP_CONCAT(luh.description) as description,lsh.created_date,lsh.created_at,lsh.updated_date,lsh.updated_at,lsh.uploaded_by,lsh.verifier_id,l.type,l.key,l.label '
                        . 'FROM imp_label_status_history lsh '
                        . 'JOIN imp_label_upload_history luh ON (lsh.ref_code = luh.ref_code) '
                        . 'JOIN imp_labels l ON (lsh.label_id = l.id) '
                        . 'WHERE 1 '
                        . 'AND lsh.is_latest = "1" AND l.type = 1 '
                        . ''.$handled_by_cond.' '.$user_id_cond.' '.$date_cond.' '.$service_id_cond.' '.$user_type_cond.' '
                        . 'GROUP BY lsh.user_id,lsh.label_id';

            $documents = $this->Slaves->query($get_documents);

            $uploaded_at = '';
            $uploaded_by = '';
            foreach ($documents as $index => $doc){
                if(!empty($labels[$doc['lsh']['label_id']]['section_ids'])){
                    $imp_sections = explode(',',$labels[$doc['lsh']['label_id']]['section_ids']);

                    foreach($imp_sections as $imp_sec){
                        $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['status'] = $doc['lsh']['pay1_status'];
                        $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['uploaded_by'] = $doc['lsh']['uploaded_by'];
                        $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['verifier_id'] = $doc['lsh']['verifier_id'];
                        $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['uploaded_at'] = $doc['lsh']['created_at'];
                        $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['updated_at'] = $doc['lsh']['updated_at'];
                        $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['type'] = $doc['l']['type'];
                        $response[$doc['lsh']['user_id']][$sections[$imp_sec]['name']][$doc['lsh']['label_id']]['is_mandatory'] = $section_data[$sections[$imp_sec]['name']]['labels'][$doc['lsh']['label_id']]['is_mandatory'];
                    }
                }
            }
            
            $get_textual_labels = 'SELECT * FROM '
                    . '(SELECT luh.id, lsh.user_id,lsh.label_id,lsh.pay1_status,lsh.bank_status,lsh.pay1_comment,lsh.ref_code,luh.description,lsh.created_date,lsh.created_at,lsh.updated_date,lsh.updated_at,lsh.uploaded_by,lsh.verifier_id,l.type,l.key,l.label '
                    . 'FROM imp_label_upload_history luh '
                    . 'JOIN imp_label_status_history lsh ON (lsh.ref_code = luh.ref_code) '
                    . 'JOIN imp_labels l ON (luh.label_id = l.id) '
                    . 'WHERE 1 '
                    . 'AND l.type = 2 '
                    . ''.$handled_by_cond.' '.$user_id_cond.' '.$date_cond.' '.$service_id_cond.' '.$user_type_cond.' '
                    . 'ORDER BY luh.id DESC) as imp '
                    . 'GROUP BY user_id,label_id';

            $textual_labels = $this->Slaves->query($get_textual_labels);

            foreach ($textual_labels as $textual) {
                if(!empty($labels[$textual['imp']['label_id']]['section_ids'])){
                    $imp_sections = explode(',',$labels[$textual['imp']['label_id']]['section_ids']);
                    foreach($imp_sections as $imp_sec){
                        $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['status'] = $textual['imp']['pay1_status'];
                        $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['uploaded_by'] = $textual['imp']['uploaded_by'];
                        $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['verifier_id'] = $textual['imp']['verifier_id'];
                        $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['uploaded_at'] = $textual['imp']['created_at'];
                        $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['updated_at'] = $textual['imp']['updated_at'];            
                        $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['type'] = $textual['imp']['type'];            
                        $response[$textual['imp']['user_id']][$sections[$imp_sec]['name']][$textual['imp']['label_id']]['is_mandatory'] = $section_data[$sections[$imp_sec]['name']]['labels'][$textual['imp']['label_id']]['is_mandatory'];
                    }
                }
            }
            $section_status = array();
            
            foreach($sections as $section_id => $data ){
                $section_status[$data['name']]['name'] = $data['name'];
                $section_status[$data['name']]['pending_count'] = 0;
                $section_status[$data['name']]['approved_count'] = 0;
                $section_status[$data['name']]['rejected_count'] = 0;
            }
            
            $final = array();
            foreach ($response as $user_id => $sectionwise_data) {            
                $approved_count = 0;
                $pending_count = 0;
                $rejected_count = 0;
                foreach ($sectionwise_data as $section_name => $labels) {
                    $section_label_count = count($section_data[$section_name]['labels']);                    
                    $final[$user_id][$section_name]['status'] = ''; 
                    $final[$user_id][$section_name]['approved_labels'] = array();
                    $label_count = count($labels);
                    $i = 0;
                    
                    $doc_labels = array_filter($labels,function($elm){
                        return ($elm['type'] == 1);
                    });
                    $tex_labels = array_filter($labels,function($elm){
                        return ($elm['type'] == 2);
                    });
                    
                    foreach($doc_labels as $label_id => $data){   // loop on documents
                        if( $data['status'] == 1 && ($final[$user_id][$section_name]['status'] == 'Approved' || $final[$user_id][$section_name]['status'] == '') ){
                            $final[$user_id][$section_name]['status'] = 'Approved';
                            $final[$user_id][$section_name]['approved_labels'][$label_id] = $label_id;
                        } else if($data['status'] == 0 && $data['is_mandatory'] == 1){
                            $final[$user_id][$section_name]['status'] = 'Pending';
                        }
                        if($data['status'] == 2){
                            $i++;
                        }
                    }
                    
                    $final[$user_id][$section_name]['status'] = $final[$user_id][$section_name]['status'] == '' ? 'Pending' : $final[$user_id][$section_name]['status'];
                                            
                    foreach($tex_labels as $label_id => $data){  // loop on textual labels
                        if($data['status'] != 1){
                            $final[$user_id][$section_name]['status'] = 'Pending';
                        }
                        if($data['status'] == 1){
                            $final[$user_id][$section_name]['approved_labels'][$label_id] = $label_id;
                        }
                        if($data['status'] == 2){
                            $i++;
                        }
                    }
                    if($label_count == $i){
                        $final[$user_id][$section_name]['status'] = 'Rejected';
                    }
                    
                    foreach ($section_data[$section_name]['labels'] as $label_id => $label) {
                        if( $label['is_mandatory'] == 1 && (isset($final[$user_id][$section_name]['approved_labels']) && !array_key_exists($label_id,$final[$user_id][$section_name]['approved_labels']) )){
                           $final[$user_id][$section_name]['status'] = 'Pending';
                        }
                    }
                    
                    if($final[$user_id][$section_name]['status'] == 'Pending'){
                        $section_status[$section_name]['pending_count'] += $pending_count+1;
                    }elseif ($final[$user_id][$section_name]['status'] == 'Approved') {
                        $section_status[$section_name]['approved_count'] += $approved_count+1;
                    }elseif ($final[$user_id][$section_name]['status'] == 'Rejected') {
                        $section_status[$section_name]['rejected_count'] += $rejected_count+1;
                    }
                }
            }
        }   
       
        $this->set('params',$params);
        $this->set('pay1_status',$pay1_status);
        $this->set('page',$page);
        $this->set('status_list',$status_list);
        $this->set('user_list',$user_list);
//        $this->set('service_sections',$service_sections);
        $this->set('section_status',$section_status);
        $this->set('service_activation_details',$final);
    }
}