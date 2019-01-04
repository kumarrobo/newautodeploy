<?php

class DocManagementApiController extends AppController {

    var $helpers = array('Html', 'Ajax', 'Javascript', 'Minify', 'Paginator');
    var $uses = array('User', 'Slaves', 'Doc_management');
    var $components = array('RequestHandler', 'Shop', 'Documentmanagement', 'Email');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }

    function checkForAccess() {
        if (!empty($_SESSION['Auth']) && !empty($_SESSION['Auth']['User']) && !empty($_SESSION['Auth']['User']['group_id'])) {
            return TRUE;
        } else {
            $response = array('status' => 'failure', 'errCode' => 403, 'description' => $this->Documentmanagement->errorCodes(403));
            echo json_encode($response);
            exit();
        }
    }
    
    function encrypt_string($string = '', $salt = '89AD3E56780ACF57686949FDE12182891D456ECD342112E') {
        $this->autoRender = false;
        $key = pack('H*', $salt);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_CBC, $iv);
        
        return base64_encode($iv . $ciphertext);
    }


    function addUserDocs() {
        
//        $this->checkForAccess();
        $req_data_in = $this->params['form']; // get data from post
//        $data = array(‘method’=>’uploadDocs’,’app_name’=>’smartpay’,’date_from’=>’2017-06-02’,’date_to’=>’2017-06-02’,’token’=>’3234asdsdf45t’,’user_id’=>12);
        $req = $this->encrypt_string(json_encode($req_data_in),Configure::read('requestKey'));
        
        return $req;
//        $this->Doc_management->set($req_data_in);
//        $acl_check=$this->Documentmanagement->checkServiceLabelMapping($label_id,$service_id);
//        if($acl_check)
//        {
//        if ($this->Doc_management->validates(array('fieldList' => array('user_id','service_id','label_id')))) {
            $response_status_flag = '';
            $user_id = $this->params['form']['user_id'];
            $service_id = $this->params['form']['service_id'];
            $label_id = $this->params['form']['label_id'];
            $ref_code = $label_id . $user_id . date("YmdHis");
            $label_description = $this->params['form']['label_description'];
            $documents = $this->params['form']['document'];
            Configure::load('platform');
            Configure::load('bridge');
            $labels = Configure::read('doc_type');
            $notification_url = Configure::read('notification_url');
            
            if (array_key_exists($label_id, $labels)) {                
                if (array_key_exists($service_id, $notification_url)) {
                    if($labels[$label_id]['type']==1)
                    {
                    $check_doc = $this->Documentmanagement->checkIfDocExists($req_data_in);

                    if ($check_doc['status']) {
                        $images = array();
                        $max_size = 3145728;

                        $img_err = array();
                        $doc_details = array();

                        if ( ($documents) && count($documents['name']) > 0) {
                            if (count($documents['name']) <= $labels[$label_id]['max_upload_count']) {
                                $is_img = TRUE;
                                foreach ($documents['tmp_name'] as $index => $document) {
                                    $fileType = mime_content_type($document);
                                    if (!in_array($fileType, $labels[$label_id]['allowed_extensions'])) {
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
                                        $doc_count = $this->Documentmanagement->getDocumentCount($req_data_in);
                                        $uploaded_paths = array();

                                        // Transaction start
                                        $dataSource = $this->User->getDataSource();
                                        $dataSource->begin();
                                        foreach ($documents['name'] as $index => $document) {
                                            $img_ext = strtolower(pathinfo($document, PATHINFO_EXTENSION));
                                            $uploaded_paths[] = $filename = $user_id . '_' . $label_id . '_' . ($doc_count + $index + 1) . '.' . $img_ext;
                                            $document_path = $_FILES['document']['tmp_name'][$index];
                                            App::import('vendor', 'S3', array('file' => 'S3.php'));
                                            $bucket = docbucket;
                                            $s3 = new S3(awsAccessKey, awsSecretKey);
                                            //                       $s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
                                            $response = $s3->putObjectFile($document_path, $bucket, $filename, S3::ACL_PUBLIC_READ);

                                            if ($response) {
                                                $description = $filename;

                                                $label_upload_response = $this->User->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,created_date,created_at)"
                                                        . "VALUES('$label_id','$user_id','$service_id','$ref_code','$description','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "' )");
                                             
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
                                                $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->Documentmanagement->errorCodes(102));
                                                echo json_encode($response);
                                                exit;
                                            }
                                        }

                                        if ($response_status_flag == 1) {
                                            if (count($ids) > 0) {                                            
                                                
                                                $ids = implode(",", $ids);

                                                $pay1_status_update = '';
                                                if ($check_doc['data']['pay1_status'] == 0 && $check_doc['data']['bank_status'] == 0) {
                                                    $pay1_status_update .= "pay1_status='3',";
                                                }
                                                if(!empty($check_doc['data']))
                                                {
                                                    $update_label_status_response = $this->User->query("UPDATE imp_label_status_history "
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
                                                        $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->Documentmanagement->errorCodes(102));
                                                        echo json_encode($response);
                                                        exit;
                                                    }
                                                }
                                                $currentTime = date('Y-m-d H:i:s');
                                                $insert_label_status_response = $this->User->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,is_latest,pay1_status,bank_status,created_date,created_at)"
                                                        . "VALUES('$label_id','$user_id','$ref_code','1','0','0','" . date('Y-m-d') . "','$currentTime' )");

                                                if (!$insert_label_status_response) {
                                                    if (count($uploaded_paths) > 0) {
                                                        foreach ($uploaded_paths as $uri) {
                                                            $s3->deleteObject($bucket, $uri);
                                                        }
                                                    }
                                                    // Transaction rollback
                                                    $dataSource->rollback();
                                                    $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->Documentmanagement->errorCodes(102));
                                                    echo json_encode($response);
                                                    exit;
                                                }
                                                /** SENDING NOTIFICATION TO PRODUCT SERVERS : START  * */
                                                $services = $this->Documentmanagement->getUserServices($user_id);
                                                if( count($services) == 0  || !in_array($service_id, $services)){
                                                    $insert_user_services_response = $this->User->query("INSERT IGNORE INTO users_services(user_id,service_id)VALUES('$user_id','$service_id')");
                                                }
                                                
                                                $doc_response = $this->Documentmanagement->sendNotification($ids);
                                                foreach ($services as $service) {
                                                    $this->General->curl_post($notification_url[$service], $doc_response, 'POST');
                                                }

                                                $dataSource->commit();
                                            }

                                            $response = array('status' => 'success',
                                                'msg' => 'Document uploaded successfully','data'=>array('expiry_time'=>date("Y-m-d H:i:s", strtotime("$currentTime +3 hours")))
                                            );

                                            echo json_encode($response);
                                            exit;
                                        }
                                    } else {
                                        $response = array('status' => 'failure', 'errCode' => 103, 'description' => $this->Documentmanagement->errorCodes(103));
                                        echo json_encode($response);
                                        exit;
                                    }
                                } else {
                                    $response = array('status' => 'failure', 'errCode' => 104, 'description' => $this->Documentmanagement->errorCodes(104));
                                    echo json_encode($response);
                                    exit;
                                }
                            } else {
                                $response = array('status' => 'failure', 'errCode' => 105, 'description' => $this->Documentmanagement->errorCodes(105));
                                echo json_encode($response);
                                exit;
                            }
                        } else {
                            $response = array('status' => 'failure', 'errCode' => 106, 'description' => $this->Documentmanagement->errorCodes(106));
                            echo json_encode($response);
                            exit;
                        }
                    } else {
                        $response = array('status' => 'failure', 'errCode' => 107, 'description' => $this->Documentmanagement->errorCodes(107));
                        echo json_encode($response);
                        exit;
                    }
                }
                else 
                {
                    if ($this->Doc_management->validates(array('fieldList' => array('label_description')))) {
                    $description=$label_description;
                    $labelcheck=$this->Documentmanagement->checkIfLabelExists($req_data_in);
                    $dataSource = $this->User->getDataSource();
                    if($labelcheck)
                    {
                        // Transaction start                        
                        $dataSource->begin();
                        $update_label_description = $this->User->query("UPDATE imp_label_upload_history "
                                . "SET description='$description',service_id='$service_id' "
                                . "WHERE user_id='$user_id' AND label_id='$label_id'");
                        if (!$update_label_description) 
                        {
                            // Transaction rollback
                            $dataSource->rollback();
                            $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->Documentmanagement->errorCodes(102));
                            echo json_encode($response);
                            exit;
                        }
                    }
                    else
                    {
                        // Transaction start                        
                        $dataSource->begin();
                        $label_upload_response = $this->User->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,created_date,created_at)"
                                                            . "VALUES('$label_id','$user_id','$service_id','$ref_code','$description','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "') ");

                        if (!$label_upload_response) 
                        {
                            // Transaction rollback
                            $dataSource->rollback();
                            $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->Documentmanagement->errorCodes(102));
                            echo json_encode($response);
                            exit;
                        }
                        
                        $insert_label_status_response = $this->User->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,created_date,created_at)"
                                                            . "VALUES('$label_id','$user_id','$ref_code','0','0','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "')");
                        if (!$insert_label_status_response) 
                        {
                            // Transaction rollback
                            $dataSource->rollback();
                            $response = array('status' => 'failure', 'errCode' => 102, 'description' => $this->Documentmanagement->errorCodes(102));
                            echo json_encode($response);
                            exit;
                        }
                    }
                    //Transaction commit
                    $dataSource->commit();
                    $response = array('status' => 'success','msg' => 'Data updated successfully');

                    echo json_encode($response);
                    exit;
                    
                }
                else
                {
                    $response = array('status' => 'failure','description' => $this->Doc_management->validationErrors);
                    echo json_encode($response);
                    exit;
                }
                }
                }else {
                    $response = array('status' => 'failure', 'errCode' => 109, 'description' => $this->Documentmanagement->errorCodes(109));
                    echo json_encode($response);
                    exit;
                }
            }
            
 
        else {
                $response = array('status' => 'failure', 'errCode' => 110, 'description' => $this->Documentmanagement->errorCodes(110));
                echo json_encode($response);
                exit;
            }
//        } else {
//            $response = array('status' => 'failure','description' => $this->Doc_management->validationErrors);
//            echo json_encode($response);
//            exit;
//        }
//     }
        $this->autoRender = false;
    }

    function getUserDocList() {
        $this->checkForAccess();
        $req_data_in = $this->params['form']; // get data from post
        $user_id = $this->params['form']['user_id'];
        $service_id = $this->params['form']['service_id'];
        Configure::load('platform');
        $labels = Configure::read('doc_type');
        $this->Doc_management->set($req_data_in);
        
        if ($this->Doc_management->validates(array('fieldList' => array('user_id','service_id')))) {
            $response = $this->Documentmanagement->getDocLabelsByServiceId($service_id);
            $documents = $this->Documentmanagement->userStatusCheck($user_id);
            
            if (count($documents) > 0) {

                    foreach ($documents as $data) 
                    {
                        if($labels[$data['lsh']['label_id']]['type']==2)
                        {   
                            $response[$labels[$data['lsh']['label_id']]['label']]=$data[0]['description'];                            
                        }
                        else
                        {
                            if(array_key_exists($data['lsh']['label_id'], $response))
                            {
                            $response[$data['lsh']['label_id']]['pay1_status']=$data['lsh']['pay1_status'];
                            $response[$data['lsh']['label_id']]['bank_status']=$data['lsh']['bank_status'];
                            $response[$data['lsh']['label_id']]['urls']= array_map(function($value){
                                                                                    return DOCUMENT_URL.$value;},
                                                                                    explode(',',$data[0]['description'])
                                                                                );
                            }
                        }
                    }
                    
                    $response = array('status' => 'success', 'data' => $response);
            } else {
                $response = array('status' => 'failure', 'errCode' => 101, 'description' => $this->Documentmanagement->errorCodes(101));
            }
        } else {
            $response = array('status' => 'failure',
                'description' => $this->Doc_management->validationErrors
            );
        }
        echo json_encode($response);
        $this->autoRender = false;
    }

    function sendDocumentDetailsEmail() {
        $user_id = $this->params['form']['user_id'];
        $email_id = $this->params['form']['email_id'];
        $doc_id = $this->params['form']['doc_id'];
        $ref_code = $this->params['form']['ref_code'];
        $ids = array();
        $document_path = array();
        $bucket = docbucket;
        if(count($ref_code) > 0)
        {
            $ids = implode(",", $ref_code);            
        }

        App::import('vendor', 'S3', array('file' => 'S3.php'));
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $zip = new ZipArchive;

        if (!empty($ids)) {
            $id="'" . str_replace(",", "','", $ids) . "'";
            $userdata = $this->Slaves->query("SELECT description "
                    . "FROM imp_label_upload_history "
                    . "WHERE ref_code IN ($id) AND user_id='$user_id'");
            
            if (count($userdata)) {
                foreach ($userdata as $data) {
                    $document_path[] = $data['imp_label_upload_history']['description'];
                }
                $zip->open('/tmp/' . $user_id . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

                foreach ($document_path as $path) {
                    $filepath = "/tmp/" . $path;
                    $newCopy = $filepath;
                    $img = $s3->getObject($bucket, $path, $filepath);
                    $watermark = "/var/www/html/cc.pay1.com/app/webroot/img/pay1_logo.png";
                    if ($img) {
//                        $zip->addFile($filepath, $path);     
                        $watermark_img = $this->Documentmanagement->getWatermarkImage($filepath, $watermark, $newCopy, $path);                            
                        $zip->addFile($watermark_img, $path);
                    }
                }
                $zip->close();
                $this->Email->template = 'Document details';
                $this->Email->to = 'Dipali<dipali.warekar@pay1.in>';
                $this->Email->subject = 'Documents';
                $this->Email->from = 'Dipali<dipali.warekar@pay1.in>';
                $this->Email->sendAs = 'both';
                $this->Email->attachments = array('/tmp/' . $user_id . '.zip', 'doc.zip');
                $this->Email->smtpOptions = array(
                    'port' => '465',
                    'timeout' => '100',
                    'host' => 'ssl://smtp.gmail.com',
                    'username' => 'dipali.warekar@pay1.in',
                    'password' => '1603198627'
                );

                /* Set delivery method */

                $this->Email->delivery = 'smtp';

//                if($this->General->sendMails ('Documents','hello' , array($email_id), 'mail' ))
                if ($this->Email->send()) {
                    foreach ($document_path as $path) {
                        unlink("/tmp/" . $path);
                    }
                    unlink('/tmp/' . $user_id . '.zip');
                    $response = array('status' => 'success', 'msg' => 'Mail has been sent successfully.');
                }
            } else {
                $response = array('status' => 'failure', 'errCode' => 101, 'description' => $this->Documentmanagement->errorCodes(101));
            }
        }
        echo json_encode($response);
        $this->autoRender = false;
    }

}
