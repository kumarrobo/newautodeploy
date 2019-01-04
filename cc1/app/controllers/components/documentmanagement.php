<?php

class DocumentmanagementComponent extends Object{
    var $components = array('General', 'Shop','RequestHandler','Platform','Serviceintegration');
    var $Memcache = null;

    function getUserServices($user_id)
    {
        $Object = ClassRegistry::init('User');
        $service_array=array();
        $user_services=$Object->query("SELECT service_id "
                . "FROM users_services "
                . "WHERE user_id='$user_id' ");

        foreach ($user_services as $services)
        {
            $service_array[]=$services['users_services']['service_id'];
        }
        return $service_array;
    }

    function getDocLabelsByServiceId($service_id)
    {
       $labels = $this->getImpLabels();
       $Object = ClassRegistry::init('User');
       $service_id = is_array($service_id)?implode(',',$service_id):$service_id;
       $labels_array = array();
       
       $service_id_cond = '';       
       if(!empty($service_id)){
           $service_id_cond = 'AND ssm.service_id IN ('.$service_id.') ';
       }
//       $doc_labels = $Object->query("SELECT label_id,service_id "
//                . "FROM imp_label_service_acl "
//                . "WHERE service_id IN ($service_id) AND has_access='1' ");
       $doc_labels = $Object->query('SELECT ssm.service_id,slm.label_id,l.* '
               . 'FROM imp_section_service_mapping ssm '
               . 'JOIN imp_section_label_mapping slm ON FIND_IN_SET(slm.section_id,ssm.section_id) '
               . 'JOIN imp_labels l ON (slm.label_id = l.id) '
               . 'WHERE 1 '
               . ''.$service_id_cond.' '
               . 'GROUP BY ssm.service_id,slm.label_id '
               . 'ORDER BY ssm.service_id,slm.label_id');

        foreach ($doc_labels as $label)
        {
            if($label['l']['type'] == 1){
                $labels_array['document'][$label['ssm']['service_id']][$labels[$label['slm']['label_id']]['key']]['label'] = $label['l']['label'];
                $labels_array['document'][$label['ssm']['service_id']][$labels[$label['slm']['label_id']]['key']]['max_upload_count'] = $label['l']['max_upload_count'];
                $labels_array['document'][$label['ssm']['service_id']][$labels[$label['slm']['label_id']]['key']]['allowed_extensions'] = json_decode($label['l']['allowed_extensions'],true);
                $labels_array['document'][$label['ssm']['service_id']][$labels[$label['slm']['label_id']]['key']]['pay1_status'] = '';
                $labels_array['document'][$label['ssm']['service_id']][$labels[$label['slm']['label_id']]['key']]['pay1_comment'] = '';
                $labels_array['document'][$label['ssm']['service_id']][$labels[$label['slm']['label_id']]['key']]['urls'] = array();
            }else{
                $labels_array['textual'][$labels[$label['slm']['label_id']]['key']] = '';
            }
        }
        return $labels_array;
    }

    function getDocumentCount($params)
    {
        $Object = ClassRegistry::init('User');
        $count=0;

        $response=$Object->query("SELECT count(id) as doc_count "
                . "FROM imp_label_upload_history "
                . "WHERE user_id='{$params['user_id']}' and label_id='{$params['label_id']}' "
                . "group by user_id,label_id ");

        if(!empty($response))
        {
            $count=$response[0][0]['doc_count'];
        }
        return $count;
    }

    function getDistributorList()
    {
        $Object = ClassRegistry::init('User');
        $response = array();
        $dist_list = $Object->query("SELECT id,company "
                . "FROM distributors "
                . "WHERE active_flag=1 ");
        if(count($dist_list))
        {
            /** IMP DATA ADDED : START**/
            $dist_ids = array_map(function($element){
                return $element['distributors']['id'];
            },$dist_list);
            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
            /** IMP DATA ADDED : END**/

            foreach($dist_list as $list)
            {
                $list['distributors']['company'] = $imp_data[$list['distributors']['id']]['imp']['shop_est_name'];
                $response[]=$list['distributors'];
            }
        }

        return $response;
    }

    function userStatusCheck($user_id,$labels = array(),$mode = null,$dataSource = null){
        if(is_null($dataSource)){
        $Object = ClassRegistry::init('Slaves');
        }else{
            $Object = $dataSource;
        }
        $user_id = is_array($user_id)?implode(',', $user_id):$user_id;
        $label_cond = '';
        if( count($labels) > 0 ){
            $label_cond = " AND luh.label_id IN(".implode(',',$labels).")";
        }
        $response = array();
        $get_documents = "SELECT luh.service_id,lsh.user_id,lsh.label_id,lsh.pay1_status,lsh.bank_status,lsh.pay1_comment,lsh.ref_code,GROUP_CONCAT(luh.description) as description,l.type,l.key,l.label "
                    . "FROM imp_label_status_history lsh "
                    . "JOIN imp_label_upload_history luh ON (lsh.ref_code = luh.ref_code) "
                    . "JOIN imp_labels l ON (lsh.label_id = l.id) "
                    . "WHERE  lsh.is_latest = '1' AND lsh.user_id IN (".$user_id.") AND l.type = 1 "
                    . $label_cond." "
                    . "GROUP BY lsh.ref_code";
        $documents = $Object->query($get_documents);

        foreach ($documents as $data){
            $response[$data['lsh']['user_id']][$data['lsh']['label_id']]['type'] = $data['l']['type'];
            $response[$data['lsh']['user_id']][$data['lsh']['label_id']]['label_id'] = $data['lsh']['label_id'];
            $response[$data['lsh']['user_id']][$data['lsh']['label_id']]['pay1_status'] = $data['lsh']['pay1_status'];
            $response[$data['lsh']['user_id']][$data['lsh']['label_id']]['bank_status'] = $data['lsh']['bank_status'];
            $response[$data['lsh']['user_id']][$data['lsh']['label_id']]['pay1_comment'] = $data['lsh']['pay1_comment'];
            $response[$data['lsh']['user_id']][$data['lsh']['label_id']]['description'] = $data[0]['description'];
        }

        $get_textual_labels = "SELECT * FROM "
                . "(SELECT luh.id, luh.service_id,lsh.user_id,lsh.label_id,lsh.pay1_status,lsh.bank_status,lsh.pay1_comment,lsh.ref_code,luh.description,l.type,l.key,l.label "
                . "FROM imp_label_upload_history luh "
                . "JOIN imp_label_status_history lsh ON (lsh.ref_code = luh.ref_code) "
                . "JOIN imp_labels l ON (luh.label_id = l.id) "
                . "WHERE luh.user_id IN (".$user_id.") AND l.type = 2 AND lsh.pay1_status IN ('0','1') "
                . $label_cond." "
                . "ORDER BY luh.id) as imp ";

        $textual_labels = $Object->query($get_textual_labels);

        foreach ($textual_labels as $index=>$data){
            $response[$data['imp']['user_id']][$data['imp']['label_id']]['type'] = $data['imp']['type'];
            if($mode == 1){
//                $response[$data['imp']['label_id']]['key'] = $data['imp']['key'];
//                $response[$data['imp']['label_id']]['label'] = $data['imp']['label'];
//                $response[$data['imp']['label_id']]['type'] = $data['imp']['type'];
                $response[$data['imp']['user_id']][$data['imp']['label_id']]['pay1_status'] = $data['imp']['pay1_status'];
                $response[$data['imp']['user_id']][$data['imp']['label_id']]['bank_status'] = $data['imp']['bank_status'];
                $response[$data['imp']['user_id']][$data['imp']['label_id']]['pay1_comment'] = $data['imp']['pay1_comment'];
                $response[$data['imp']['user_id']][$data['imp']['label_id']]['description'] = $data['imp']['description'];
            } else {
//                $response[$data['imp']['label_id']][$data['imp']['pay1_status']]['key'] = $data['imp']['key'];
//                $response[$data['imp']['label_id']][$data['imp']['pay1_status']]['label'] = $data['imp']['label'];
//                $response[$data['imp']['label_id']][$data['imp']['pay1_status']]['type'] = $data['imp']['type'];
                $response[$data['imp']['user_id']][$data['imp']['label_id']][$data['imp']['pay1_status']]['pay1_status'] = $data['imp']['pay1_status'];
                $response[$data['imp']['user_id']][$data['imp']['label_id']][$data['imp']['pay1_status']]['bank_status'] = $data['imp']['bank_status'];
                $response[$data['imp']['user_id']][$data['imp']['label_id']][$data['imp']['pay1_status']]['pay1_comment'] = $data['imp']['pay1_comment'];
                $response[$data['imp']['user_id']][$data['imp']['label_id']][$data['imp']['pay1_status']]['description'] = $data['imp']['description'];
            }
        }

        return $response;
    }

    function getUserDocs($params)
    {
        $Object = ClassRegistry::init('User');
        $response=array();
        $ids=array();
        $ret_cond='';

        $labels = $this->getImpLabels();
        $doc_label_ids = array_keys(array_filter($labels,function($label){
            return $label['type'] == 1;
        }));

//        if(!empty($params['dist_id']))
//        {
//            $ret_cond="AND r.parent_id IN ('{$params['dist_id']}') ";
//        }
        $user_id = '';
        if(!empty($params['mobile']))
        {
//            $ret_cond.="AND (r.mobile IN ('{$params['mobile']}') OR d.mobile IN ('{$params['mobile']}')) ";
//        }
//        if(!empty($params['user_id']))
//        {
//            $ret_cond.="AND luh.user_id IN ('{$params['user_id']}') ";
//        }

//        $get_documents="SELECT r.name,r.user_id as ret_user_id,r.shopname,r.mobile,d.user_id as dist_user_id,d.name as dname,luh.user_id,GROUP_CONCAT(DISTINCT(luh.description)) as description,GROUP_CONCAT(DISTINCT(us.service_id)) as service_id "
//                    . "FROM imp_label_upload_history  luh "
//                    . "JOIN retailers r "
//                    . "ON (r.user_id=luh.user_id) "
//                    . "JOIN distributors d "
//                    . "ON (d.id=r.parent_id) "
//                    . "JOIN users_services us "
//                    . "ON (us.user_id=luh.user_id) "
//                    . "WHERE  luh.is_latest='1' "
//                    . "AND luh.label_id IN (".implode(',',$doc_label_ids).") "
//                    . "$ret_cond "
////                    . "GROUP BY lsh.user_id,lsh.label_id";
//                    . "GROUP BY luh.user_id";

//        $get_documents="SELECT r.user_id "
//                    . "FROM retailers r "
//                    . "WHERE r.mobile in ('{$params['mobile']}') "
//                    . "UNION "
//                    . "SELECT d.user_id "
//                    . "FROM distributors d "
//                    . "WHERE d.mobile IN ('{$params['mobile']}') ";
        $get_documents="SELECT id "
                    . "FROM users "
                    . "WHERE mobile in ('{$params['mobile']}') ";

        $documents=$Object->query($get_documents);

        $user_id = $documents[0]['users']['id'];
        }

        return $user_id;
    }

    function getUserInfo($user_id='')
    {
        $Object = ClassRegistry::init('User');
        $user_roles_query = 'SELECT group_id '
                            . 'FROM user_groups '
                            . 'WHERE user_id="'.$user_id.'" '
                            . 'AND group_id IN (5,6)';
        $user_roles = $Object->query($user_roles_query);
        $user_info = array();
        if(count($user_roles))
        {
            $user_roles = array_map('current',array_map('current',$user_roles));
            if(in_array(6, $user_roles))
            {
                $ret_info_query='SELECT r.user_id as ret_user_id,r.name,r.shopname,r.mobile,d.user_id as dist_user_id,d.name as dname,d.company '
                    . 'FROM retailers r '
                    . 'JOIN distributors d '
                    . 'ON (d.id=r.parent_id) '
                    . 'WHERE r.user_id="'.$user_id.'" ';
    //                . 'OR d.user_id="'.$user_id.'"';


                $user_info=$Object->query($ret_info_query);

                /** IMP DATA ADDED : START**/
                $imp_data = $this->Shop->getUserLabelData(array($user_id,$user_info[0]['d']['dist_user_id']),2,0);
                /** IMP DATA ADDED : END**/

                $user_info[0]['r']['name'] = $imp_data[$user_id]['imp']['name'];
                $user_info[0]['r']['shopname'] = $imp_data[$user_id]['imp']['shop_est_name'];
                $user_info[0]['r']['mobile'] = $imp_data[$user_id]['ret']['mobile'];
                $user_info[0]['d']['dname'] = $imp_data[$user_info[0]['d']['dist_user_id']]['imp']['name'];
                $user_info[0]['d']['company'] = $imp_data[$user_info[0]['d']['dist_user_id']]['imp']['shop_est_name'];
            }
            if(in_array(5, $user_roles))
            {
                $dist_info_query='SELECT user_id,name,company '
                        . 'FROM distributors d '
                        . 'WHERE user_id="'.$user_id.'"';

                $dist_info=$Object->query($dist_info_query);
                $imp_data = $this->Shop->getUserLabelData($user_id,2,0);
                $dist_info[0]['d']['name'] = $imp_data[$user_id]['imp']['name'];
                $dist_info[0]['d']['company'] = $imp_data[$user_id]['imp']['shop_est_name'];
            }
            $user_info[0]['dist_info']=$dist_info[0]['d'];
        }
        return $user_info;
    }

    function checkServiceLabelMapping($label_id,$service_id)
    {
        $Object = ClassRegistry::init('User');
        $response=$Object->query('SELECT ssm.service_id,slm.label_id,l.* '
               . 'FROM imp_section_service_mapping ssm '
               . 'JOIN imp_section_label_mapping slm ON (ssm.section_id = slm.section_id) '
               . 'JOIN imp_labels l ON (slm.label_id = l.id) '
               . 'WHERE 1 '
               . 'AND ssm.service_id = '.$service_id.' '
               . 'AND slm.label_id = '.$label_id.' ');

        if(count($response))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    //check if user is allowed to upload documents
    function checkIfDocExists($user_id,$label_id)
    {
        $Object = ClassRegistry::init('User');
        $response=$Object->query("SELECT lsh.* "
                . "FROM imp_label_status_history lsh "
                . "JOIN imp_label_upload_history luh ON (lsh.ref_code = luh.ref_code) "
                . "WHERE lsh.user_id = '$user_id' AND lsh.label_id = '$label_id' AND lsh.is_latest = '1' "
                . "GROUP BY lsh.ref_code"
//                . "ORDER BY updated_at desc "
//                . "LIMIT 1"
                        );

        $labels = $this->getImpLabels();

        if(!empty($response) && $labels[$label_id]['dynamic_flag'] == 0)
        {
//            if(($response[0]['imp_label_status_history']['pay1_status']==1 && $response[0]['imp_label_status_history']['bank_status']==1)) // latest doc is verified
            if($response[0]['lsh']['pay1_status']==1) // latest doc is verified
            {
                return array('status' => FALSE,'data' => $response[0]['lsh'],'errCode'=>112,'description'=>$this->errorCodes(112));
            }
            else
            {
                if($response[0]['lsh']['created_at'] < date("Y-m-d H:i:s", strtotime('-3 hours')))     // this will check if user is uploading doc again within 3 hrs time span
                {
//                    if($response[0]['lsh']['pay1_status']==0 && $response[0]['lsh']['bank_status']==0)    // if latest is in process, then dont allow ; if rejected then allow
                    if($response[0]['lsh']['pay1_status']==0)    // if latest is in process, then dont allow ; if rejected then allow
                    {
                        return array('status' => FALSE,'data' => $response[0]['lsh'],'errCode'=>107,'description'=>$this->errorCodes(107));
                    }
                    else
                    {
                        return array('status' => TRUE,'data' => $response[0]['lsh']);   //reject
                    }
                }
                else
                {
                     return array('status' => TRUE,'data' => $response[0]['lsh']);
                }
            }
        }
        else
        {
            $data = !empty($response) ? $response[0]['lsh'] : array();
            return array('status' => TRUE,'data' => $data);
        }
    }

    function checkIfLabelExists($user_id,$label_id,$dataSource = null)
    {
        if(is_null($dataSource)){
        $Object = ClassRegistry::init('User');
        }else{
            $Object = $dataSource;
        }
//        $response=$Object->query("SELECT * "
//                . "FROM imp_label_upload_history "
//                . "WHERE user_id='$user_id' AND label_id='$label_id' "
//                . "ORDER BY ID DESC "
//                . "LIMIT 1");
        $response = $Object->query("SELECT luh.id,lsh.id,lsh.user_id,lsh.label_id,lsh.pay1_status,lsh.bank_status,lsh.pay1_comment,lsh.ref_code,luh.description "
                . "FROM imp_label_upload_history luh "
                . "JOIN imp_label_status_history lsh ON (lsh.ref_code = luh.ref_code) "
                . "WHERE luh.user_id = ".$user_id." AND luh.label_id = ".$label_id." AND lsh.pay1_status IN ('0','1') "
                . "ORDER BY luh.id ");

        if(count($response) > 0)
        {
            return array('status'=>'success','data'=>$response);
        }
        else
        {
            return array('status'=>'failure');
        }
    }

    function checkUserServiceMapping($user_id,$service_id)
    {
        $Object = ClassRegistry::init('User');
        $service_array=array();
        $user_services=$Object->query("SELECT service_id "
                . "FROM users_services "
                . "WHERE user_id='$user_id' and service_id='$service_id' ");

        if(count($user_services))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function checkDocs($user_id=null,$service_id=null)
    {
        Configure::load('platform');
        $labels = $this->getImpLabels();
        $pay1_status = Configure::read('pay1_status');
        $response = $this->getDocLabelsByServiceId($service_id);

        $documents = $this->userStatusCheck($user_id,null,1);

        $Object = ClassRegistry::init('Slaves');

        App::import('vendor', 'S3', array('file' => 'S3.php'));

        $s3 = new S3(awsAccessKey, awsSecretKey);


        $response['textual']['shop_area'] = '';
        $response['textual']['shop_city'] = '';
        $response['textual']['shop_pincode'] = '';
        $response['textual']['shop_state'] = '';

        if (count($documents) > 0)
        {
            foreach ($documents[$user_id] as $label_id=>$data)
            {
                $type = $data['type'];
                unset($data['type']);
                if($type == 2)
                {
                    if(array_key_exists($labels[$label_id]['key'], $response['textual'])){

                        if(in_array($label_id, array(17,19,25,31,32)))
                        {
                            $types = $this->getImpLabelTypes($label_id);
                            $response['textual'][$labels[$label_id]['key']] = !empty($types)?$types[$data['description']]:'';
                        }
                        else
                        {
                            $response['textual'][$labels[$label_id]['key']] = $data['description'];
                        }
                    }
                }
                else
                {
                    foreach ($response['document'] as $service_id => $expected_labels) {
                        if(array_key_exists($labels[$label_id]['key'], $expected_labels))
                        {
                            $response['document'][$service_id][$labels[$label_id]['key']]['pay1_status'] = $pay1_status[$data['pay1_status']];
                            $response['document'][$service_id][$labels[$label_id]['key']]['pay1_comment'] = $data['pay1_comment'];

                            $response['document'][$service_id][$labels[$label_id]['key']]['urls'] = array_map(function($value) use($s3){
                                return $s3->aws_s3_link(awsAccessKey,awsSecretKey,docbucket,'/'.$value,time() - strtotime(date('Y-m-d'))+86400);},
                                explode(',',$data['description'])
                                );
                        }
                    }
                }
            }
            // return $response;
        }

        /** Add Location Details : START **/
//        if( empty($response['textual']['shop_area']) || empty($response['textual']['shop_city']) || empty($response['textual']['shop_pincode']) || empty($response['textual']['shop_state']) ){
            $location_info_query = 'SELECT area_id,latitude,longitude FROM retailers_location WHERE area_id > 0 and user_id = '.$user_id;
            $location_info = $Object->query($location_info_query);


            if( count($location_info) > 0 ){

                $area_id = $location_info[0]['retailers_location']['area_id'];
                $area_query = 'SELECT id,name,city_id,pincode FROM locator_area WHERE id = '.$area_id;
                $areas_temp = $Object->query($area_query);

                $city_id = $areas_temp[0]['locator_area']['city_id'];
                if( !empty($areas_temp[0]['locator_area']['name']) ){
                    $response['textual']['shop_area'] = $area = $areas_temp[0]['locator_area']['name'];
                }

                if( !empty($areas_temp[0]['locator_area']['pincode']) ){
                    $response['textual']['shop_pincode'] = $pincode = $areas_temp[0]['locator_area']['pincode'];
                }
                $city_query = 'SELECT id,name,state_id FROM locator_city WHERE id = '.$city_id;
                $cities_temp = $Object->query($city_query);
                if( !empty($cities_temp[0]['locator_city']['name']) ){
                    $response['textual']['shop_city'] = $city = $cities_temp[0]['locator_city']['name'];
                }
                $state_id = $cities_temp[0]['locator_city']['state_id'];

                $state_query = 'SELECT id,name FROM locator_state WHERE id = '.$state_id;
                $states_temp = $Object->query($state_query);
                if( !empty($states_temp[0]['locator_state']['name']) ){
                    $response['textual']['shop_state'] = $state = $states_temp[0]['locator_state']['name'];
                }
            } else {
                $imp_data = $this->Shop->getUserLabelData($user_id,2,0);
                if( array_key_exists('dist',$imp_data[$user_id]) ){

                    $location_details = $this->Shop->getLocationDetails($imp_data[$user_id]['dist']['map_long'],$imp_data[$user_id]['dist']['map_lat']);
                    if( empty($response['textual']['shop_area']) ){
                        $response['textual']['shop_area'] = $location_details['area'];
                    }
                    if( empty($response['textual']['shop_city']) ){
                        $response['textual']['shop_city'] = $location_details['city'];
                    }
                    if( empty($response['textual']['shop_pincode']) ){
                        $response['textual']['shop_pincode'] = $location_details['pincode'];
                    }
                    if( empty($response['textual']['shop_state']) ){
                        $response['textual']['shop_state'] = $location_details['state'];
                    }
                }
            }
//        }
        /** Add Location Details : END **/

        return $response;
    }

//    function sendNotification($ids)
//    {
//        $Object = ClassRegistry::init('User');
//        $userdata=$Object->query("SELECT * "
//                . "FROM imp_label_status_history "
//                . "WHERE id in ($ids)");
//        $result=array();
//        $doc_details=array();
//
//        foreach ($userdata as $data)
//        {
//            $result['user_id']=$data['imp_label_status_history']['user_id'];
//            $result['doc_id']=$data['imp_label_status_history']['id'];
//            $result['label_id']=$data['imp_label_status_history']['label_id'];
//            $result['ref_code']=$data['imp_label_status_history']['ref_code'];
//            $result['pay1_status']=$data['imp_label_status_history']['pay1_status'];
//            $result['bank_status']=$data['imp_label_status_history']['bank_status'];
//            $doc_details[]=$result;
//        }
//        $response=array('status'=>'success',
//                                             'service_type'=>'document',
//                                             'data'=> json_encode($doc_details)
//                                            );
//        return $response;
//    }

    function errorCodes($code)
    {
        $err=array('101'=>'Docs not found',
            '102'=>'Something went wrong. Please try again.',
            '103'=>'Document size allowed : 3 MB.',
            '104'=>'Invalid document.',
            '105'=>'Maximum image count exceeded.',
            '106'=>'Document missing.',
            '107'=>'Document verification is in process. Can\'t upload new documents.',
            '108'=>'Unauthorized Access.',
            '109'=>'Invalid Service.',
            '110'=>'Invalid Label.',
            '111'=>'Reference code required',
            '112'=>'Documents already approved. Can\'t upload new documents.',
            '403'=>'Session does not exist.',
            '404'=>'Access denied.');

        return $err[$code];
    }

    function getWatermarkedImage($img, $watermark, $newCopy,$img_name)
//    function getWatermarkImage($img, $watermark,$img_name)
    {
//        header('content-type: image/jpeg');
//        header("Content-Type: application/force-download");
        $watermark=imagecreatefrompng($watermark);
        $img_ext= strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
//        $image = imagecreatetruecolor($sx, $sy);
        if($img_ext=='png')
        {
            $image = imagecreatefrompng($img);
        }
        elseif($img_ext=='jpeg' || $img_ext=='jpg')
        {
            $image = imagecreatefromjpeg($img);
        }
        $img_w = imagesx($image);
        $img_h = imagesy($image);
        $sx = imagesx($watermark);
        $sy = imagesy($watermark);
        $dest_x = ($img_w / 2) - ($sx / 2);
        $dest_y = ($img_h / 2) - ($sy / 2);
        imagecopymerge($image, $watermark, $dest_x, $dest_y, 0, 0, $sx, $sy,50);
        // Output and free memory
        if($img_ext=='png')
        {
            imagepng($image,$newCopy,100);
        }
        elseif($img_ext=='jpeg' || $img_ext=='jpg')
        {
            imagejpeg($image,$newCopy,100);
//            imagejpeg($image);
        }
//        imagejpeg($image);
        imagedestroy($image);
        imagedestroy($watermark);
//        header("Content-Disposition: attachment; filename=\"".basename($image)."\";" );
        return $newCopy;
    }

    function updateTextualInfo($user_id,$label_id,$service_id="",$description,$uploaded_by)
    {
        $redis = $this->Shop->redis_connect();

        $redis->del("impdata_0_".$user_id);
        $redis->del("impdata_1_".$user_id);
        $redis->del("impdata_2_".$user_id);
        $redis->del("impdata_3_".$user_id);
        $redis->del("impdata_4_".$user_id);
        $Object = ClassRegistry::init('User');
        $ip_address = $this->RequestHandler->getClientIP();
        $data = array('user_id'=>$user_id,'label_id'=>$label_id,'description'=>$description);
        $validation_res = $this->uploadDocsPanelValidation($data);

        if($validation_res['status'] == "success"){
            $labelcheck = $this->checkIfLabelExists($user_id,$label_id);
            $ref_code = $label_id . $user_id . date("YmdHis");
            $dataSource = $Object->getDataSource();
            if($labelcheck['status'] == 'success')
            {
                // Transaction start
                $dataSource->begin();
                if(count($labelcheck['data']) > 1 || (count($labelcheck['data']) == 1 && $labelcheck['data'][0]['lsh']['pay1_status'] == 0)){
                    $id = isset($labelcheck['data'][1]['lsh']['pay1_status']) && $labelcheck['data'][1]['lsh']['pay1_status'] == 0?$labelcheck['data'][1]['luh']['id']:$labelcheck['data'][0]['luh']['id'];
                    $label_val = isset($labelcheck['data'][1]['lsh']['pay1_status']) && $labelcheck['data'][1]['lsh']['pay1_status'] == 0?$labelcheck['data'][1]['luh']['description']:$labelcheck['data'][0]['luh']['description'];
                    if($label_val != $description){
                        $update_label_description = $Object->query("UPDATE imp_label_upload_history "
                                . "SET description = '$description',service_id = '$service_id',uploaded_by = '$uploaded_by' "
                                . "WHERE id = '$id'");
                        if (!$update_label_description)
                        {
                            // Transaction rollback
                            $dataSource->rollback();
                            $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->errorCodes(102));
                            return $response;
                        }
                    }
                }else{
                    $label_upload_response = $Object->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                    . "VALUES('$label_id','$user_id','$service_id','$ref_code','$description','$uploaded_by','$ip_address','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "') ");

                    if (!$label_upload_response)
                    {
                        // Transaction rollback
                        $dataSource->rollback();
                        $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->errorCodes(102));
                        return $response;
                    }

                    $insert_label_status_response = $Object->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                                        . "VALUES('$label_id','$user_id','$ref_code','0','0','$uploaded_by','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "')");
                    if (!$insert_label_status_response)
                    {
                        // Transaction rollback
                        $dataSource->rollback();
                        $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->errorCodes(102));
                        return $response;
                    }
                }
            }
            else
            {
                // Transaction start
                $dataSource->begin();
                $label_upload_response = $Object->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                    . "VALUES('$label_id','$user_id','$service_id','$ref_code','$description','$uploaded_by','$ip_address','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "') ");

                if (!$label_upload_response)
                {
                    // Transaction rollback
                    $dataSource->rollback();
                    $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->errorCodes(102));
                    return $response;
                }

                $insert_label_status_response = $Object->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                                    . "VALUES('$label_id','$user_id','$ref_code','0','0','$uploaded_by','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "')");
                if (!$insert_label_status_response)
                {
                    // Transaction rollback
                    $dataSource->rollback();
                    $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->errorCodes(102));
                    return $response;
                }
            }
        }else{
            $response = $validation_res;
            return $response;
        }
        //Transaction commit
        $dataSource->commit();
        $response = array('status' => 'success','msg' => 'Document Info updated successfully');
        return $response;

    }

    function updateDocumentInfo($user_id,$service_id,$label_id,$documents,$uploaded_by)
    {
        $check_doc = $this->checkIfDocExists($user_id,$label_id);
        Configure::load('platform');
        if ($check_doc['status']) {
        $Object = ClassRegistry::init('User');
        $ip_address = $this->RequestHandler->getClientIP();
        $labels = $this->getImpLabels();
        Configure::load('bridge');
        $notification_url = Configure::read('notification_url');
        $ref_code = $label_id . $user_id . date("YmdHis");
        $images = array();
        $max_size = 5242880;

        $img_err = array();
        $doc_details = array();

        if ( ($documents) && ($documents['size'][0] > 0) ) {
            if (count($documents['name']) <= $labels[$label_id]['max_upload_count']) {
                $is_img = TRUE;
                foreach ($documents['tmp_name'] as $index => $document) {
                    $fileType = mime_content_type($document);
                    if (!in_array($fileType, json_decode($labels[$label_id]['allowed_extensions'],true))) {
                        $images[] = $document;
                        $is_img = False;
                    }
                }

                $size_flag = TRUE;
                foreach ($documents['size'] as $index => $size) {
                    if ($size > $max_size) {
                        $size_flag = FALSE;
                    }
                }
                $images = count($images) > 1 ? implode(",", $images) : (!empty($images) ? $images[0] : $images);
                $ids = array();
                if ($is_img) {
                    if ($size_flag) {
                        $uploaded_paths = array();

                        // Transaction start
                        $dataSource = $Object->getDataSource();
                        $dataSource->begin();
                        foreach ($documents['name'] as $index => $document) {
                            $img_ext = strtolower(pathinfo($document, PATHINFO_EXTENSION));
                            $uploaded_paths[] = $filename = $user_id . '_' . $label_id . '_' .($index+1).'_' .date("YmdHis") . '.' . $img_ext;
                            $document_path = $_FILES['document']['tmp_name'][$index];
                            App::import('vendor', 'S3', array('file' => 'S3.php'));
                            $bucket = docbucket;
                            $s3 = new S3(awsAccessKey, awsSecretKey);
                            //                       $s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
                            $response = $s3->putObjectFile($document_path, $bucket, $filename, S3::ACL_PUBLIC_READ);

                            if ($response) {
                                $label_upload_response = $Object->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                        . "VALUES('$label_id','$user_id','$service_id','$ref_code','$filename','$uploaded_by','$ip_address','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "' )");

                                if ($label_upload_response) {
                                    $ids[] = mysql_insert_id();
                                    $response_status_flag = 1; // success
                                } else {
                                    $response_status_flag = 0; // failure
                                }
                            } else {
                                $response_status_flag = 0; // failure
                            }

                            if ($response_status_flag == 0) {
                                    if (count($uploaded_paths) > 0) {
                                        foreach ($uploaded_paths as $uri) {
                                            $s3->deleteObject($bucket, $uri);
                                        }
                                    }
                                    // Transaction rollback
                                    $dataSource->rollback();
                                    $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->errorCodes(102));
                                    return $response;
                                }
                        }
                        if ($response_status_flag == 1) {
                            if (count($ids) > 0) {

                                $ids = implode(",", $ids);

                                $pay1_status_update = '';
//                                if ($check_doc['data']['pay1_status'] == 0 && $check_doc['data']['bank_status'] == 0) {
                                if ($check_doc['data']['pay1_status'] == 0) {
                                    $pay1_status_update .= "pay1_status='3',";
                                }
                                if(!empty($check_doc['data']))
                                {
                                    $update_label_status_response = $Object->query("UPDATE imp_label_status_history "
                                        . "SET " . $pay1_status_update . "is_latest='0',updated_at='" . date("Y-m-d H:i:s") . "' "
                                        . "WHERE  id = " . $check_doc['data']['id']);

                                    if (!$update_label_status_response) {
                                        if (count($uploaded_paths) > 0) {
                                            foreach ($uploaded_paths as $uri) {
                                                $s3->deleteObject($bucket, $uri);
                                            }
                                        }
                                        // Transaction rollback
                                        $dataSource->rollback();
                                        $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->errorCodes(102));
                                        return $response;
                                    }
                                }
                                $currentTime = date('Y-m-d H:i:s');
                                $insert_label_status_response = $Object->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,is_latest,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                        . "VALUES('$label_id','$user_id','$ref_code','1','0','0','$uploaded_by','" . date('Y-m-d') . "','$currentTime' )");

                                if (!$insert_label_status_response) {
                                    if (count($uploaded_paths) > 0) {
                                        foreach ($uploaded_paths as $uri) {
                                            $s3->deleteObject($bucket, $uri);
                                        }
                                    }
                                    // Transaction rollback
                                    $dataSource->rollback();
                                    $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->errorCodes(102));
                                    return $response;
                                }

                                $dataSource->commit();

                                /** SENDING NOTIFICATION TO PRODUCT SERVERS : START  * */
                                                $services = $this->getUserServices($user_id);
//                                                if( count($services) == 0  || !in_array($service_id, $services)){
//                                                    if($service_id==12)
//                                                    {
//                                                        $insert_user_services_response = $this->User->query("INSERT IGNORE INTO users_services(user_id,service_id)VALUES('$user_id','$service_id')");
//                                                    }
//                                                }
//
//                                                $doc_response = $this->sendNotification($ids);
                                                $doc_response = array(
                                                                    'user_id'=>$user_id,
                                                                    'label_id'=>$label_id,
                                                                    'urls'=>array_map(function($path){return 'http://'.docbucket.'.'.DOCUMENT_URL.$path;},$uploaded_paths));

                                                foreach ($services as $service) {
                                                    if(array_key_exists($service,$notification_url) && !empty($notification_url[$service]))
                                                    {
                                                        $this->General->curl_post_async($notification_url[$service], $doc_response, 'POST');
                                                    }
                                                }
                            }

                            $response = array('status' => 'success',
                                'msg' => 'Document uploaded successfully','data'=>array('expiry_time'=>date("Y-m-d H:i:s", strtotime("$currentTime +3 hours")),'urls'=>array_map(function($path){return 'http://'.docbucket.'.'.DOCUMENT_URL.$path;},$uploaded_paths))
                            );

                            return $response;
                        }
                    } else {
                        $response = array('status' => 'failure', 'errCode' => 103, 'description' => $this->errorCodes(103));
                        return $response;
                    }
                } else {
                    $response = array('status' => 'failure', 'errCode' => 104, 'description' => $this->errorCodes(104));
                    return $response;
                }
            } else {
                $response = array('status' => 'failure', 'errCode' => 105, 'description' => $this->errorCodes(105));
                return $response;
            }
        } else {
            $response = array('status' => 'failure', 'errCode' => 106, 'description' => $this->errorCodes(106));
            return $response;
        }
       }
       else {
            $response = array('status' => 'failure', 'errCode' => $check_doc['errCode'], 'description' => $check_doc['description']);
            return $response;
        }
    }

    function uploadDocsPanelValidation($params)
    {
        $Object = ClassRegistry::init('DocManagement');
        $Object->set($params);
        Configure::load('platform');
        Configure::load('bridge');
        $labels = $this->getImpLabels();
        $notification_url = Configure::read('notification_url');

        if (!$Object->validates(array('fieldList' => array('user_id')))) {
            $response = array('status' => 'failure','description' => $Object->validationErrors);
            return $response;
        }

        if($params['label_id'] == ''){
            $response = array('status' => 'failure','description' => array('Label id required'));
            return $response;
        }
//        if(!is_array($params['label_id'])){  // doc label - single
            if (!array_key_exists($params['label_id'], $labels)) {
                $response = array('status' => 'failure', 'errCode' => 110, 'description' => $this->errorCodes(110));
                return $response;
            }
//        }
        if($params['label_type'] == 2) {                            // textual labels - multiple
            $allow = TRUE;
//            foreach ($params['label_id'] as $label_id => $value) {
//                if (!array_key_exists($label_id, $labels)) {
//                    $response = array('status' => 'failure', 'errCode' => 110, 'description' => $this->errorCodes(110));
//                    return $response;
//                }

                if($params['curr_desc'] == ''){
                    $allow = FALSE;
//                    break;
                }

                $regex = ( isset($labels[$params['label_id']]['regex']) && !empty($labels[$params['label_id']]['regex']) ) ? $labels[$params['label_id']]['regex'] :null;
                if( $regex && !preg_match($regex,$params['curr_desc']) ){
                    return array('status'=>'failure','description'=> 'Invalid '.$labels[$params['label_id']]['label'],'label'=>$labels[$params['label_id']]['label']);
                }
                
//                if($params['label_id'] == 9){
//                    $pan_validation_res = $this->Platform->getPanStatus(array($params['curr_desc']));
//                    
//                    if($pan_validation_res['data'][$params['curr_desc']]['status_code'] != 'E'){
//                        return array('status'=>'failure','description'=> 'Invalid '.$labels[$params['label_id']]['label'],'label'=>$labels[$params['label_id']]['label']);
//                    }
//                }
            if(!$allow){
                $response = array('status' => 'failure','description' => 'Fields missing');
                return $response;
            }
        }

        return array('status'=>'success');
    }

    function getLabelConfig($key,$value){
        Configure::load('platform');
        $labels = $this->getImpLabels();
        $keys = array_map(function($element)use ($key){return $element[$key];},$labels);

        return array_combine(
            array_map(function($element) use ($key) {return $element[$key];},$labels),
            array_map(function($element) use ($value){return $element[$value];},$labels)
        );
    }
    function getActiveUsers(){
        Configure::load('platform');
        $labels = $this->getImpLabels();
        $pay1_status = Configure::read('pay1_status');

        $Object = ClassRegistry::init('Slaves');
        $query = 'SELECT luh.service_id,lsh.user_id,lsh.label_id,lsh.pay1_status,lsh.bank_status,lsh.pay1_comment,lsh.ref_code,GROUP_CONCAT(luh.description) as description,user.mobile,user.name,user.id
            FROM imp_label_upload_history  luh
            LEFT JOIN imp_label_status_history lsh
            ON (lsh.ref_code=luh.ref_code)
            LEFT JOIN users user
            ON (luh.user_id = user.id)
            WHERE  luh.is_latest="1"
            GROUP BY luh.user_id,luh.ref_code';

        $documents = $Object->query($query);

        $response = array();
        if (count($documents) > 0)
        {
            foreach ($documents as $index => $data)
            {
                if($labels[$data['lsh']['label_id']]['type']==2)
                {
                    $response[$data['lsh']['user_id']]['textual'][$labels[$data['lsh']['label_id']]['key']]=$data[0]['description'];
                }
                else
                {
                    // foreach ($response['document'] as $service_id => $expected_labels) {
                        // if(array_key_exists($labels[$data['luh']['label_id']]['key'], $expected_labels))
                        // {
                            $response[$data['lsh']['user_id']]['document'][$labels[$data['lsh']['label_id']]['key']]['pay1_status']=$pay1_status[$data['lsh']['pay1_status']];
                            $response[$data['lsh']['user_id']]['document'][$labels[$data['lsh']['label_id']]['key']]['pay1_comment']=$data['lsh']['pay1_comment'];
                            $response[$data['lsh']['user_id']]['document'][$labels[$data['lsh']['label_id']]['key']]['urls']= array_map(function($value){
                                return 'http://'.docbucket.'.'.DOCUMENT_URL.$value;},
                                explode(',',$data[0]['description'])
                                );
                        // }
                    // }
                }
                $response[$data['lsh']['user_id']]['info'] = $data['user'];
            }
        }
        return $response;
    }
//    function getLabelDescription($user_id,$label_id)
//    {
//        $Object = ClassRegistry::init('User');
//        $get_label_description = $Object->query("SELECT description "
//                                                    . "FROM imp_label_upload_history "
//                                                    . "WHERE user_id = '$user_id' "
//                                                    . "AND label_id = '$label_id' ");
//
//        $label_description = !empty($get_label_description)?$get_label_description[0]['imp_label_upload_history']['description']:'';
//
//        return $label_description;
//    }

    function getLabelDescription($user_id = null,$label_id = null)
    {
        $Object = ClassRegistry::init('User');
        $user_id_cond = '';
        if($user_id){
            $user_id_cond = " AND user_id IN ('$user_id') ";
        }
        $label_id_cond = '';
        if($label_id){
            $label_id_cond = " AND label_id IN ('$label_id') ";
        }

        $get_label_description = $Object->query("SELECT user_id,description "
                                                    . "FROM imp_label_upload_history "
                                                    . "WHERE  is_latest = '1' ".$user_id_cond.$label_id_cond);

        if( count($get_label_description) == 1 ){
            $label_description = !empty($get_label_description)?$get_label_description[0]['imp_label_upload_history']['description']:'';
        } else {
            foreach( array_map('current',$get_label_description) as $label ){
                $label_description[$label['user_id']] = $label['description'];
            }
        }

        return $label_description;
    }

    function getImpLabelTypes($label_id)
    {
        Configure::load('platform');
        $label_types = Configure::read('imp_label_types');

        $imp_label_types = array_key_exists($label_id, $label_types)?$label_types[$label_id]:'';

        return $imp_label_types;
    }

    function updateLabelStatus($user_id,$label_id,$label_val,$dataSource = null){
        if(is_null($dataSource)){
            $Object = ClassRegistry::init('User');
        }else{
            $Object = $dataSource;
}

        $response = $Object->query('UPDATE imp_label_status_history '
                        . 'SET pay1_status = '.$label_val.',updated_at = "'.date('Y-m-d H:i:s').'" '
                        . 'WHERE user_id = '.$user_id.' AND label_id = '.$label_id.' AND is_latest = "1" ');

        if($response){
            return array('status'=>'success');
        }else{
            return array('status'=>'failure');
        }
    }

    function updateAadhar($user_id){
        $Object = ClassRegistry::init('User');
        $aadhar_old = $Object->query('SELECT * FROM imp_label_status_history lsh join imp_label_upload_history luh on (luh.ref_code = lsh.ref_code and luh.label_id = lsh.label_id and luh.user_id = lsh.user_id) WHERE lsh.label_id = 2 and lsh.is_latest = "1" and lsh.user_id = '.$user_id.' ');
        $count = count($aadhar_old);

        foreach($aadhar_old as $data){
            $label_id1 = '47';
            $label_id2 = '48';
            $date = date('Y-m-d');
            $datetime = date("YmdHis");
            $ref_code1 = $label_id1.$user_id.$datetime;
            $ref_code2 = $label_id2.$user_id.$datetime;
            $description = $data['luh']['description'];
            if($count == 1){
                $Object->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                . "VALUES('$label_id1','".$data['luh']['user_id']."','".$data['luh']['service_id']."','$ref_code1','".$data['luh']['description']."','".$data['luh']['uploaded_by']."','".$data['luh']['ip_address']."','" . $date . "','" . $datetime . "') ");
                $check_doc = $this->checkIfDocExists($user_id,$label_id1);
                if ($check_doc['data']['pay1_status'] == 0) {
                    $pay1_status_update .= "pay1_status='3',";
                }
                if(!empty($check_doc['data']))
                {
                    $Object->query("UPDATE imp_label_status_history "
                        . "SET " . $pay1_status_update . "is_latest='0',updated_at='" . date("Y-m-d H:i:s") . "' "
                        . "WHERE  id = " . $check_doc['data']['id']);
                }
                $Object->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                                . "VALUES('$label_id1','".$data['lsh']['user_id']."','$ref_code1','".$data['lsh']['pay1_status']."','".$data['lsh']['bank_status']."','".$data['lsh']['uploaded_by']."','" . $date . "','" . $datetime . "')");

                $Object->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                . "VALUES('$label_id2','".$data['luh']['user_id']."','".$data['luh']['service_id']."','$ref_code2','".$data['luh']['description']."','".$data['luh']['uploaded_by']."','".$data['luh']['ip_address']."','" . $date . "','" . $datetime . "') ");

                $check_doc = $this->checkIfDocExists($user_id,$label_id2);
                if ($check_doc['data']['pay1_status'] == 0) {
                    $pay1_status_update .= "pay1_status='3',";
                }
                if(!empty($check_doc['data']))
                {
                    $Object->query("UPDATE imp_label_status_history "
                        . "SET " . $pay1_status_update . "is_latest='0',updated_at='" . date("Y-m-d H:i:s") . "' "
                        . "WHERE  id = " . $check_doc['data']['id']);
                }
                $Object->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                        . "VALUES('$label_id2','".$data['lsh']['user_id']."','$ref_code2','".$data['lsh']['pay1_status']."','".$data['lsh']['bank_status']."','".$data['lsh']['uploaded_by']."','" . $date . "','" . $datetime . "')");
            }elseif($count > 1){
                $str = '_2_2_';
                if(strpos($description,$str) !== false){
                    $Object->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                . "VALUES('$label_id2','".$data['luh']['user_id']."','".$data['luh']['service_id']."','$ref_code2','".$data['luh']['description']."','".$data['luh']['uploaded_by']."','".$data['luh']['ip_address']."','" . $date . "','" . $datetime . "') ");
                    $check_doc = $this->checkIfDocExists($user_id,$label_id2);
                    if ($check_doc['data']['pay1_status'] == 0) {
                        $pay1_status_update .= "pay1_status='3',";
                    }
                    if(!empty($check_doc['data']))
                    {
                        $Object->query("UPDATE imp_label_status_history "
                            . "SET " . $pay1_status_update . "is_latest='0',updated_at='" . date("Y-m-d H:i:s") . "' "
                            . "WHERE  id = " . $check_doc['data']['id']);
                    }
                    $Object->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                                . "VALUES('$label_id2','".$data['lsh']['user_id']."','$ref_code2','".$data['lsh']['pay1_status']."','".$data['lsh']['bank_status']."','".$data['lsh']['uploaded_by']."','" . $date . "','" . $datetime . "')");

                }else{
                    $Object->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                . "VALUES('$label_id1','".$data['luh']['user_id']."','".$data['luh']['service_id']."','$ref_code1','".$data['luh']['description']."','".$data['luh']['uploaded_by']."','".$data['luh']['ip_address']."','" . $date . "','" . $datetime . "') ");
                    $check_doc = $this->checkIfDocExists($user_id,$label_id1);
                    if ($check_doc['data']['pay1_status'] == 0) {
                        $pay1_status_update .= "pay1_status='3',";
                    }
                    if(!empty($check_doc['data']))
                    {
                        $Object->query("UPDATE imp_label_status_history "
                            . "SET " . $pay1_status_update . "is_latest='0',updated_at='" . date("Y-m-d H:i:s") . "' "
                            . "WHERE  id = " . $check_doc['data']['id']);
                    }
                    $Object->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                                . "VALUES('$label_id1','".$data['lsh']['user_id']."','$ref_code1','".$data['lsh']['pay1_status']."','".$data['lsh']['bank_status']."','".$data['lsh']['uploaded_by']."','" . $date . "','" . $datetime . "')");
                }
            }
        }
    }

    function getSectionLabelMapping(){
        $Object = ClassRegistry::init('Slaves');
        $section_data = $Object->query('SELECT l.*,s.name as section_name,sl.* '
                . 'FROM imp_section_label_mapping sl '
                . 'JOIN imp_labels l ON (l.id = sl.label_id) '
                . 'JOIN imp_sections s ON (s.id = sl.section_id)');

        $response = array();

        foreach($section_data as $data){
            $response[$data['s']['section_name']]['id'] = $data['sl']['section_id'];
            $response[$data['s']['section_name']]['section_name'] = $data['s']['section_name'];
            if($data['l']['type'] == 1){
                $response[$data['s']['section_name']]['doc_labels'][] = $data['l'];
            }elseif($data['l']['type'] == 2){
                $response[$data['s']['section_name']]['textual_labels'][] = $data['l'];
            }
            $response[$data['s']['section_name']]['labels'][$data['l']['id']] = $data['l'];
            $response[$data['s']['section_name']]['labels'][$data['l']['id']]['is_mandatory'] = $data['sl']['is_mandatory'];
        }
        return $response;
    }

    function getImpLabels(){
        $Object = ClassRegistry::init('Slaves');
        $section_data = $Object->query('SELECT l.*,s.name as section_name,sl.label_id,sl.section_id,GROUP_CONCAT(sl.section_id) as section_id '
                . 'FROM imp_labels l '
                . 'LEFT JOIN imp_section_label_mapping sl ON (l.id = sl.label_id) '
                . 'LEFT JOIN imp_sections s ON (s.id = sl.section_id) '
                . 'GROUP BY l.id');

        $response = array();

        foreach($section_data as $data){
            $response[$data['l']['id']] = $data['l'];
            $response[$data['l']['id']]['section_ids'] = $data[0]['section_id'];
        }
        return $response;
    }

    function getImpSections(){
        $Object = ClassRegistry::init('Slaves');
        $sections = $Object->query('SELECT * FROM imp_sections s');

        $response = array();

        foreach($sections as $data){
            $response[$data['s']['id']]['name'] = $data['s']['name'];
        }
        return $response;
    }

    function updateRetShopLocation($user_id,$longitude,$latitude){
        $Object = ClassRegistry::init('User');
        $location_info_query = 'SELECT area_id,latitude,longitude FROM retailers_location WHERE area_id > 0 and user_id = '.$user_id;
        $location_info = $Object->query($location_info_query);

        if(empty($location_info)){
            $imp_data = $this->Shop->getUserLabelData($user_id,2,0);
            if( array_key_exists('ret',$imp_data[$user_id]) ){
                $ret_id = $imp_data[$user_id]['ret']['id'];
            } else if( array_key_exists('dist',$imp_data[$user_id]) ){
                $ret_id = 0;
            }
            $location_details = $this->Shop->getLocationDetails($longitude,$latitude);
            if(!empty($location_details)){
                if(!empty($location_details['area'])){
                    $area_id = $location_details['area'];
                    $location_insert_query = 'REPLACE INTO retailers_location(retailer_id,area_id,latitude,longitude,updated,user_id,verified) VALUES ('.$ret_id.',"'.$area_id.'","'.$latitude.'","'.$longitude.'","'.date('Y-m-d').'",'.$user_id.',0);';
                    $location_insert = $Object->query($location_insert_query);
                }
                $imp_shop_data = array(43=>$location_details['area'],44=>$location_details['city'],45=>$location_details['pincode'],46=>$location_details['state']);
                foreach ($imp_shop_data as $label_id => $val){
                    if(!empty($val)){
                        $this->updateTextualInfo($user_id,$label_id,0,$val,$user_id);
                    }
                }
            }
        }
    }

    function getDefaultValues($key){
        $fn = $key.'Types';
        $types = $this->Shop->$fn();

        return $types;
    }

    function getSectionData($user_id,$service_id){
        $doc_info = $this->checkDocs($user_id,$service_id);
        $section_data = $this->getSectionLabelMapping();
        $service_sections = $this->getServiceSections($service_id);
        $service_sections = $service_sections[$service_id];

        $label_details = array();
        foreach ( $doc_info['document'][$service_id] as $label_key => $label ) {
        $label_details[$label_key] = $label;
        }
        // foreach ( $doc_info['textual'] as $label_key => $value ) {
        // $label_details[$label_key] = $value;
        // }
        $section_data = array_intersect_key($section_data,$service_sections);
        foreach ( $section_data as $section_name => $section_details ) {


        $doc_labels = array_map(function($elm){
        return $elm['key'];
        },$section_details['doc_labels']);

        $section_data[$section_name]['status'] = 'Approved';
        foreach ( $doc_labels as $label_key ) {
        if( array_key_exists($label_key,$label_details) ){
        $section_data[$section_name]['doc_label_details'][$label_key] = $label_details[$label_key];
        if( strtolower($label_details[$label_key]['pay1_status']) != 'approved' ){
        $section_data[$section_name]['status'] = 'Pending';
        }
        } else {
        $section_data[$section_name]['status'] = 'Pending';
        }
        }


        // $textual_labels = array_map(function($elm){
        // return $elm['key'];
        // },$section_details['textual_labels']);
        }
        return $section_data;
    }
    
    function getServiceSections($service_id){
        $Object = ClassRegistry::init('User');
        $service_id = is_array($service_id)?implode(',',$service_id):$service_id;
        $sections = array();

        $service_id_cond = '';
        if(!empty($service_id)){
        $service_id_cond = 'AND ssm.service_id IN ('.$service_id.') ';
        }

        $temp = $Object->query('SELECT ssm.service_id,ssm.section_id,s.* '
        . 'FROM imp_section_service_mapping ssm '
        . 'JOIN imp_sections s ON (s.id = ssm.section_id) '
        . 'WHERE 1 '
        . $service_id_cond);

        if( count($temp) > 0 ){
        foreach ($temp as $section_details) {
        $sections[$section_details['ssm']['service_id']][$section_details['s']['name']] = $section_details['s'];
        $sections[$section_details['ssm']['service_id']][$section_details['s']['name']]['status'] = 'Approved';
        }
        }
        return $sections;
    }

    function getUserSectionReport($user_id = '' ,$service_id = '' ){

        if( empty($user_id) || empty($service_id) ){
            return false;
        }
        $params['user_id'] = $user_id;
        $params['service_id'] = $service_id;

        $pay1_status = array(0=>'Pending',1=>'Approved',2=>'Rejected');
        $services = $this->Serviceintegration->getAllServices();
        $services = json_decode($services,true);

        Configure::load('platform');
        $status_list = Configure::read('pay1_status');
        $user_types = Configure::read('info_management_user_types');

        if(!empty($params['service_id'])){
            $service_id_cond = ' AND ssm.service_id = '.$params['service_id'].' ';
        }

        if(!empty($params['user_id'])){
            $user_id_cond = ' AND lsh.user_id = '.$params['user_id'].' ';
        }
        $service_ids = !empty($params['service_id'])?array_keys($services):$params['service_id'];
        $service_sections = $this->getServiceSections($service_ids);

        $Object = ClassRegistry::init('Slaves');
        $get_documents = 'SELECT lsh.user_id,lsh.label_id,lsh.pay1_status,lsh.bank_status,lsh.ref_code,GROUP_CONCAT(luh.description) as description,lsh.created_date,lsh.created_at,lsh.updated_date,lsh.updated_at,lsh.uploaded_by,lsh.verifier_id,l.type,l.key,l.label,ssm.section_id,ssm.service_id,s.name as section_name '
                    . 'FROM imp_label_status_history lsh '
                    . 'JOIN imp_label_upload_history luh ON (lsh.ref_code = luh.ref_code) '
                    . 'JOIN imp_labels l ON (lsh.label_id = l.id)'
                    . 'JOIN imp_section_label_mapping sl ON (lsh.label_id = sl.label_id) '
                    . 'JOIN imp_section_service_mapping ssm ON (ssm.section_id = sl.section_id) '
                    . 'JOIN imp_sections s ON (s.id = ssm.section_id) '
                    . 'WHERE 1 '
                    . 'AND lsh.is_latest = "1" AND l.type = 1 '
                    . ' '.$user_id_cond.$service_id_cond.' '
                    . 'GROUP BY lsh.user_id,ssm.service_id,ssm.section_id,lsh.label_id';

        $documents = $Object->query($get_documents);

        foreach ($documents as $index => $doc){
            $response[$doc['lsh']['user_id']][$doc['ssm']['service_id']][$doc['s']['section_name']][$doc['lsh']['label_id']]['status'] = $doc['lsh']['pay1_status'];
            $response[$doc['lsh']['user_id']][$doc['ssm']['service_id']][$doc['s']['section_name']][$doc['lsh']['label_id']]['uploaded_by'] = $doc['lsh']['uploaded_by'];
            $response[$doc['lsh']['user_id']][$doc['ssm']['service_id']][$doc['s']['section_name']][$doc['lsh']['label_id']]['verifier_id'] = $doc['lsh']['verifier_id'];
            $response[$doc['lsh']['user_id']][$doc['ssm']['service_id']][$doc['s']['section_name']][$doc['lsh']['label_id']]['uploaded_at'] = $doc['lsh']['created_at'];
            $response[$doc['lsh']['user_id']][$doc['ssm']['service_id']][$doc['s']['section_name']][$doc['lsh']['label_id']]['updated_at'] = $doc['lsh']['updated_at'];
        }

        $get_textual_labels = 'SELECT * FROM '
                . '(SELECT luh.id, lsh.user_id,lsh.label_id,lsh.pay1_status,lsh.bank_status,lsh.pay1_comment,lsh.ref_code,luh.description,lsh.created_date,lsh.created_at,lsh.updated_date,lsh.updated_at,lsh.uploaded_by,lsh.verifier_id,l.type,l.key,l.label,ssm.section_id,ssm.service_id,s.name as section_name '
                . 'FROM imp_label_upload_history luh '
                . 'JOIN imp_label_status_history lsh ON (lsh.ref_code = luh.ref_code) '
                . 'JOIN imp_labels l ON (luh.label_id = l.id) '
                . 'JOIN imp_section_label_mapping sl ON (lsh.label_id = sl.label_id) '
                . 'JOIN imp_section_service_mapping ssm ON (ssm.section_id = sl.section_id)'
                . 'JOIN imp_sections s ON (s.id = ssm.section_id) '
                . 'WHERE 1 '
                . 'AND l.type = 2 '
                . ' '.$user_id_cond.$service_id_cond.' '
                . 'ORDER BY luh.id DESC) as imp '
                . 'GROUP BY user_id,service_id,section_id,label_id';

        $textual_labels = $Object->query($get_textual_labels);

        foreach ($textual_labels as $textual) {
            $response[$textual['imp']['user_id']][$textual['imp']['service_id']][$textual['imp']['section_name']][$textual['imp']['label_id']]['status'] = $textual['imp']['pay1_status'];
            $response[$textual['imp']['user_id']][$textual['imp']['service_id']][$textual['imp']['section_name']][$textual['imp']['label_id']]['uploaded_by'] = $textual['imp']['uploaded_by'];
            $response[$textual['imp']['user_id']][$textual['imp']['service_id']][$textual['imp']['section_name']][$textual['imp']['label_id']]['verifier_id'] = $textual['imp']['verifier_id'];
            $response[$textual['imp']['user_id']][$textual['imp']['service_id']][$textual['imp']['section_name']][$textual['imp']['label_id']]['uploaded_at'] = $textual['imp']['created_at'];
            $response[$textual['imp']['user_id']][$textual['imp']['service_id']][$textual['imp']['section_name']][$textual['imp']['label_id']]['updated_at'] = $textual['imp']['updated_at'];
        }

        $final = array();
        foreach ($response as $user_id => $servicewise_data) {
            foreach ($servicewise_data as $service_id => $sectionwise_data){

                foreach ($sectionwise_data as $section_name => $labels) {
                    $final[$user_id][$service_id]['sections'][$section_name] = 'Approved';
                    $label_count = count($labels);
                    $i = 0;
                    foreach($labels as $label_id => $data){

                        if($data['status'] != 1){
                            $final[$user_id][$service_id]['sections'][$section_name] = 'Pending';
                        }
                        if($data['status'] == 2){
                            $i++;
                        }

                    }
                    if($label_count == $i){
                        $final[$user_id][$service_id]['sections'][$section_name] = 'Rejected';
                    }
                }
                $final[$user_id][$service_id]['service_name'] = $services[$service_id];
            }
        }
        return $final;
    }
}