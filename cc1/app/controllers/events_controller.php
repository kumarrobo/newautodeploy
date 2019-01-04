<?php

class EventsController extends AppController {

        var $name       = 'Events';
	var $helpers    = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv');
	var $components = array('RequestHandler','Shop');
	var $uses       = array('User','Slaves');
        
	function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('*');
	}
	
        function index() {
            
                $this->layout = 'plain';
                
                $action_type = Configure::read('action_type');
                
                $events = $this->Slaves->query('SELECT * FROM events_action');
                
                $this->set('actions', $action_type);
                $this->set('events', $events);
        }
        
        function callEvent() {
                
                $action         = $_REQUEST['action'];
                $type           = $_REQUEST['type'];
                $button_text    = $_REQUEST['button_text'];
                $event          = $_REQUEST['event'];
                $expiry_date    = $_REQUEST['expiry_date'];
                $action_type    = Configure::read('action_type');
            
                if ($_FILES['image']['size'] > 5000000) {   // 5 MB
                        $this->Session->setFlash('Exceeded filesize limit !!!');
                        
                        $this->redirect('/events');
                }
                
                $image_name = 'image';
                $bucket     = s3MarketingBucket;
                $imgUrl     = $this->uploadImage($image_name, $bucket);
            
                $form_data  = array(
                                'image_url'     => $imgUrl,
                                'action_id'     => $action,
                                'action'        => $action_type[$action],
                                'type'          => $type,
                                'button_text'   => $button_text,
                                'event'         => $event,
                                'expiry_date'   => $expiry_date
                            );
                
                if(isset($_REQUEST['button_url'])) {
                    $form_data['action_redirection_url'] = $_REQUEST['button_url'];
                }
                
                $payload    = array(
                                "type"  => "MarketingFeed",
                                "title" => "New Feed",
                                "msg"   => $form_data,
                            );
                
                $wrapper    = array(
                                "data"          => $payload,
                                "time_to_live"  => "86400"
                            );
                
//                $testing = " AND u.mobile IN ('8108681401','7101000450','9819042543')";
                $batches = $this->Slaves->query("Select mobile,gcm_reg_id,appversion From (Select u.id as user_id,u.mobile,up.gcm_reg_id,SUBSTRING_INDEX( up.version, '_', -1 ) AS appversion 
                                    from users u JOIN user_profile up ON u.id=up.user_id $testing
                                    Where  device_type = 'android' AND up.app_type = 'recharge_app' AND up.date >= '".date('Y-m-d',strtotime('-7 days'))."' ORDER BY updated DESC) a group by user_id HAVING appversion >= '5.8'");
                $batches = array_chunk($batches, 999);
                
                App::import('Helper', 'gcm');
                $gcm = new GCM();
                
                foreach($batches as $batch) {

                    $batch_temp = array();
                    foreach($batch as $ba) {
                        $batch_temp[] = $ba['a']['gcm_reg_id'];
                    }
                    
                    $res = $gcm->send_notification($batch_temp, $wrapper);
                    
                    $values = array();
                    foreach($batch as $ba) {
                        $values[] = "('".$res['description']['results'][0]['message_id']."','".$ba['a']['mobile']."','".$ba['a']['gcm_reg_id']."','".json_encode($payload)."','notification','android','".$res['status']."','".date('Y-m-d H:i:s')."','".date('Y-m-d')."')";
                    }
                    
                    if($res['status'] == 'success') {
                        $this->User->query("INSERT INTO `notificationlog` 
                                    (`msg_id`, `mobile`, `user_key`, `msg`, `notify_type`, `user_type`, `response`, `created`, `date`) 
                                VALUES " . implode(',', $values));
                    }
                    
                }
                
                $this->User->query("INSERT INTO marketing_notifications (image_url, action, type, button_text, event, expiry_date, created_by, created_at) "
                        . "VALUES ('$imgUrl', '$action', '$type', '$button_text', '$event', '$expiry_date', '".$this->Session->read('Auth.User.id')."', '".date('Y-m-d H:i:s')."')");
                
                $this->Session->setFlash('Hit Successfull !!!');
                        
                $this->redirect('/events');
        }
        
        function uploadImage($image_name, $bucket) {
        
                $rand               = rand(1000,9999);
                $file_name          = explode('.', $_FILES[$image_name]['name']);
                $actual_image_name  = str_replace(" ", "_", $file_name[0]) . "_" . $rand . "." . $file_name[1];
                
                App::import('vendor', 'S3', array('file' => 'S3.php'));
                $s3 = new S3(awsAccessKey, awsSecretKey);
                $s3->putObjectFile($_FILES[$image_name]['tmp_name'], $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                
                return 'http://' . $bucket . '.s3.amazonaws.com/' . $actual_image_name;
        }
        
        function addEvent() {
                
                $this->autoRender = FALSE;
                
                $this->User->query("INSERT INTO events_action (event_name, created_by, created_at) VALUES "
                        . "('".$_REQUEST['new_event']."', '".$this->Session->read('Auth.User.id')."', '".date('Y-m-d H:i:s')."')");
        }
        
        function serviceAlert() {
            
                $this->layout = 'plain';
        }
        
        function generateServiceAlert() {
            
                $title          = $_REQUEST['title'];
                $description    = $_REQUEST['description'];
                $alert_type     = $_REQUEST['alert_type'];
                $type           = $_REQUEST['type'];
                
                $form_data  = array(
                                'title'         => $title,
                                'description'   => $description,
                                'alert_type'    => $alert_type,
                                'type'          => $type,
                            );
                
                $payload    = array(
                                "type"  => "ServiceFeed",
                                "title" => "New Feed",
                                "msg"   => $form_data,
                            );
                
                $wrapper    = array(
                                "data"          => $payload,
                                "time_to_live"  => "86400"
                            );
                
//                $testing = " AND u.mobile IN ('8108681401','7101000450')";
                $batches = $this->Slaves->query("Select mobile,gcm_reg_id,appversion From (Select u.id as user_id,u.mobile,up.gcm_reg_id,SUBSTRING_INDEX( up.version, '_', -1 ) AS appversion 
                                    from users u JOIN user_profile up ON u.id=up.user_id $testing
                                    Where  device_type = 'android' AND up.app_type='recharge_app' AND up.date >= '".date('Y-m-d',strtotime('-7 days'))."' ORDER BY updated DESC) a group by user_id HAVING appversion >= '5.8'");
                
                $batches = array_chunk($batches, 999);
                
                App::import('Helper', 'gcm');
                $gcm = new GCM();
                
                foreach($batches as $batch) {

                    $batch_temp = array();
                    foreach($batch as $ba) {
                        $batch_temp[] = $ba['a']['gcm_reg_id'];
                    }
                    
                    $res = $gcm->send_notification($batch_temp, $wrapper);
                    
                    $values = array();
                    foreach($batch as $ba) {
                        $values[] = "('".$res['description']['results'][0]['message_id']."','".$ba['a']['mobile']."','".$ba['a']['gcm_reg_id']."','".json_encode($payload)."','notification','android','".$res['status']."','".date('Y-m-d H:i:s')."','".date('Y-m-d')."')";
                    }
                    
                    if($res['status'] == 'success') {
                        $this->User->query("INSERT INTO `notificationlog` 
                                    (`msg_id`, `mobile`, `user_key`, `msg`, `notify_type`, `user_type`, `response`, `created`, `date`) 
                                VALUES " . implode(',', $values));
                    }
                }
                
                
                $this->User->query("INSERT INTO marketing_service_alert (title, description, alert_type, type, created_by, created_at) VALUES "
                        . "('$title', '$description', '$alert_type', '$type', '".$this->Session->read('Auth.User.id')."', '".date('Y-m-d H:i:s')."')");
                
                $this->Session->setFlash('Hit Successful !!!');
                        
                $this->redirect('/events/serviceAlert');
        }
}