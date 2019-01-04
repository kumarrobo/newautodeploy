<?php

class IrctcController extends AppController{
    var $name = 'Irctc';
    var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
    var $components = array('RequestHandler','Shop','General','Bridge'); 
    var $uses = array('User','Slaves');

    
    function index(){
        $this->layout = 'plain';
        ini_set('memory_limit','2048M');           
    if($this->RequestHandler->isAjax()){        
     if($_FILES['irctcexcel']){            
      if(!empty($_FILES['irctcexcel']['name']) && !empty($_FILES['irctcexcel']['type'])){
          if(is_uploaded_file($_FILES['irctcexcel']['tmp_name'])){            
            //open uploaded csv file with read only mode
            $csvFile = fopen($_FILES['irctcexcel']['tmp_name'], 'r');                            
            //skip first line
            fgetcsv($csvFile);            
            $data = array();
            //parse data from csv file line by line
              while(($line = fgetcsv($csvFile)) !== FALSE){      
                if(is_numeric($line[1])){
                $txn_id                               = $line[1];
                $data[$txn_id]['method']              = 'walletApi';
                $data[$txn_id]['txn_id']              = $txn_id;
                $data[$txn_id]['type']                = 'db';
                $data[$txn_id]['amount']              = $line[4];             
                $data[$txn_id]['unique_id']           = $line[8];             
                $data[$txn_id]['service_id']          = IRCTC_SERVICEID;             
                $data[$txn_id]['product_id']          = IRCTC_PRODUCTID;           
                $data[$txn_id]['settle_flag']         = 1;                
                $data[$txn_id]['commission']          = 0;
                $data[$txn_id]['tax']                 = 0;
                $data[$txn_id]['description']         = 'PNR: '.$line[2].'|CLASS:'.$line[6].'|TXN_DATE:'.$line[7];
                $data[$txn_id]['server']              = 'travel_irctc';
                $data[$txn_id]['allow_negative']      = 1;  
                $data[$txn_id]['vendor_refid']        = $txn_id;               
              }}
            if(count($data) > 0 ){
                
                $userObj = ClassRegistry::init('User');
                $dataSource = $userObj->getDataSource();
                
                $unique_ids = array_map(function($elm){
                    return $elm['unique_id'];
                },$data);               
                if(count($unique_ids) > 0 && (count($unique_ids) == count($data)) ){                
                    $user_ids = $this->getUserids($unique_ids);                    
                    if(count($user_ids) > 0 ){                        
                        $failed_txns = array();                                        
                        Configure::load('bridge');
                        $configs = Configure::read('secrets');
                        $secret = $configs['travel_irctc']['secret'];
                        
                        foreach($data as $txn_id => $txn_data) {
                            $dataSource->begin();
                            
                            $txn_data['user_id'] = (array_key_exists($txn_data['unique_id'],$user_ids) && !empty($user_ids[$txn_data['unique_id']]) ) ? $user_ids[$txn_data['unique_id']] : null;
                             
                             
                            if($txn_data['user_id']){
                                $uniqueval  =  $txn_data['unique_id'];
                                unset($txn_data['unique_id']);
                                
                                $txn_data['token'] = $this->General->tokenGeneration($txn_data,$secret);
//                                $out = $this->General->curl_post($_SERVER['SERVER_NAME'].'/bridgeapis/apis',$txn_data);
                                $out = $this->General->curl_post($_SERVER['SERVER_NAME'].'/bridgeapis/apis',$txn_data);
                                $irctcDet = json_decode($out['output'],true);
                                
                                //$irctcDet = $this->Bridge->walletApi($txn_data,$dataSource);
                                if($irctcDet['status'] == 'success'){                                     
                                     $dataSource->commit();
                                } else {    
                                                                        
                                    $dataSource->rollback();
                                    $failed_txns[$txn_id]['user_id']    = $txn_data['user_id'];
                                    $failed_txns[$txn_id]['unique_id']  = $uniqueval;
                                    $failed_txns[$txn_id]['reason']     = $txn_id.'-'.$irctcDet['description'];
                                    $failed_txns[$txn_id]['txn_id']     = $txn_data['txn_id'];
                                    $failed_txns[$txn_id]['amount']     = $txn_data['amount'];
                                }
                            } else {
                                $failed_txns[$txn_id]['user_id'] = '';
                                $failed_txns[$txn_id]['unique_id']  = '';
                                $failed_txns[$txn_id]['txn_id']     = $txn_data['txn_id'];
                                $failed_txns[$txn_id]['amount']     = $txn_data['amount'];
                                $failed_txns[$txn_id]['reason'] = $txn_id.' - Unique Id Not found';
                            }
                        }
                        if(count($failed_txns) == 0 ){
                            $response= array(
                                'status' => 'success',
                                'msg' => 'All txns uploaded succesfully'
                            );
                        } else {                            
                            $failed_txns = $this->getUsersDetails($failed_txns);
                            $response= array(
                                'status' => 'failure',
                                'description' => 'Following txns failed to upload',
                                'failed_txns' => $failed_txns
                            );
                        }
                    } else {
                        $response= array(
                            'status' => 'failure',
                            'description' => 'No User Found'
                        );
                    }
                }else{
                    $response= array(
                        'status' => 'failure',
                        'description' => 'Unique Ids missing in file'
                    );  
                }
            }else {
                $response= array(
                    'status' => 'failure',
                    'description' => 'Empty File'
                );
            }
            //close opened csv file
            fclose($csvFile);
        }else{
            $response= array(
                'status' => 'failure',
                'description' => 'Failed to upload file.Please try again'
            );
        }
    }else{
        $response= array(
            'status' => 'failure',
            'description' => 'Invalid File'
        );
    }
    
    
    } else{
        $response= array(
            'status' => 'failure',
            'description' => 'Please upload file'
        );
    }
    echo json_encode($response);exit;
    }

}
    
    function getUserids($unique_ids = array()){
        $user_ids = array();
        if( count($unique_ids) > 0 ){
            $unique_id = implode("','",$unique_ids);
            $query = "SELECT param1,user_id FROM users_services WHERE param1 IN('$unique_id') AND service_id= '".IRCTC_SERVICEID."'";
            $temp = $this->Slaves->query($query);
            if( count($temp) > 0 ){
                foreach ($temp as $value) {
                    $user_ids[$value['users_services']['param1']] = $value['users_services']['user_id'];
                }
            }
        }
        return $user_ids;
    }
    
    
  function refundtxn(){
     $this->autoRender = False;        
       if($_FILES['irctcref_upload']){            
      if(!empty($_FILES['irctcref_upload']['name']) && !empty($_FILES['irctcref_upload']['type'])){
          if(is_uploaded_file($_FILES['irctcref_upload']['tmp_name'])){            
            //open uploaded csv file with read only mode
            $csvFile = fopen($_FILES['irctcref_upload']['tmp_name'], 'r');                            
            //skip first line
            fgetcsv($csvFile);
            
             $data = array();
            //parse data from csv file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){

                if(is_numeric($line[2])){
                    $txn_id                               = $line[2];
                    $data[$txn_id]['method']              = 'cancellationRefundApi';
                    $data[$txn_id]['txn_id']              = $txn_id;
                    $data[$txn_id]['server']              = 'travel_irctc';
                    $data[$txn_id]['refund_amount']       = $line[5];             
                    $data[$txn_id]['service_id']          = 19;                             
              
            } }           
            //Removing the first extra row from refund panel
            $coldata = array(0=>'Sl.No.','1'=>'RDS REFUND DATE',2=>'RESERVATION ID','3'=>'CANCELLATION ID','4'=>'BANK REFERENCE NUM','5'=>'REFUND AMOUNT','6'=>'REFUND TYPE','7'=>'CAN TXN DATE');                    
            unset($data[$coldata[2]]);            
            
           if (count($data) > 0) {               
                        $userObj = ClassRegistry::init('User');
                        $dataSource = $userObj->getDataSource();

                        $failed_txns = array();
                        Configure::load('bridge');
                        $configs = Configure::read('secrets');
                        $secret = $configs['travel_irctc']['secret'];
                        foreach ($data as $txn_id => $txn_data) {
                                 
                        if(!empty($txn_data['refund_amount'])){
                                        //$irctcDet = $this->Bridge->cancellationRefundApi($txn_data, $dataSource);
                    $txn_data['token'] = $this->General->tokenGeneration($txn_data,$secret);
                                $out = $this->General->curl_post($_SERVER['SERVER_NAME'].'/bridgeapis/apis',$txn_data);
                                $irctcDet = json_decode($out['output'],true);                                
                                if($irctcDet['status'] == 'success'){                                     
                                } else {                                     
                                            $failed_txns[$txn_id] = $txn_id . '-' . $irctcDet['description'];
                                        }
                                } else{                                
                                           $failed_txns[$txn_id] = $txn_id . '- transaction amount is empty' ;

                                 }
                                }
                        
                        if(count($failed_txns) == 0 ){
                            $response= array(
                                'status' => 'success',
                                'msg' => 'All txns Refunded succesfully'
                            );
                        } else {
                            $response= array(
                                'status' => 'failure',
                                'description' => 'Following txns failed to upload',
                                'failed_txns' => implode('<br>',$failed_txns)
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 'failure',
                            'description' => 'Empty File'
                        );
                    }
                
            fclose($csvFile);
        }else{
            $response= array(
                'status' => 'failure',
                'description' => 'Failed to upload file.Please try again'
            );
        }
    } else {
                $response = array(
                    'status' => 'failure',
                    'description' => 'Invalid File'
                );
            }
        } else {
            $response = array(
                'status' => 'failure',
                'description' => 'Please upload file'
            );
        }
        echo json_encode($response);
        exit;
    }


  function refundtxnTwo(){
     $this->autoRender = False;        
      if(!empty($_FILES['irctcref2_upload']['name']) && !empty($_FILES['irctcref2_upload']['type'])){
          if(is_uploaded_file($_FILES['irctcref2_upload']['tmp_name'])){            
            //open uploaded csv file with read only mode
            $csvFile = fopen($_FILES['irctcref2_upload']['tmp_name'], 'r');                            
            //skip first line
            fgetcsv($csvFile);                       
             $data = array();
            //parse data from csv file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                if(is_numeric($line[1])){
                    $txn_id                               = $line[1];
                    $data[$txn_id]['method']              = 'cancellationRefundApi';
                    $data[$txn_id]['txn_id']              = $txn_id;
                    $data[$txn_id]['server']              = 'travel_irctc';
                    $data[$txn_id]['refund_amount']       = $line[6];             
                    $data[$txn_id]['service_id']          = 19;             
                
                }
            }              

       if($_FILES['irctcref2_upload']){            
           if (count($data) > 0) {               
                        $userObj = ClassRegistry::init('User');
                        $dataSource = $userObj->getDataSource();

                        $failed_txns = array();
                        Configure::load('bridge');
                        $configs = Configure::read('secrets');
                        $secret = $configs['travel_irctc']['secret'];
                        foreach ($data as $txn_id => $txn_data) {
                                 
                   if(!empty($txn_data['refund_amount'])){
                                        //$irctcDet = $this->Bridge->cancellationRefundApi($txn_data, $dataSource);
                    $txn_data['token'] = $this->General->tokenGeneration($txn_data,$secret);
                                $out = $this->General->curl_post($_SERVER['SERVER_NAME'].'/bridgeapis/apis',$txn_data);
                                $irctcDet = json_decode($out['output'],true);                                
                                if($irctcDet['status'] == 'success'){                                     
                                } else {                                     
                                            $failed_txns[$txn_id] = $txn_id . '-' . $irctcDet['description'];
                                        }
                                } else{                                
                                           $failed_txns[$txn_id] = $txn_id . '- transaction amount is empty' ;
;
                                 }
                                }
                        
                        if(count($failed_txns) == 0 ){
                            $response= array(
                                'status' => 'success',
                                'msg' => 'All txns Refunded succesfully'
                            );
                        } else {
                            $response= array(
                                'status' => 'failure',
                                'description' => 'Following txns failed to upload',
                                'failed_txns' => implode('<br>',$failed_txns)
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 'failure',
                            'description' => 'Empty File'
                        );
                    }
                
            fclose($csvFile);
        }else{
            $response= array(
                'status' => 'failure',
                'description' => 'Failed to upload file.Please try again'
            );
        }
    } else {
                $response = array(
                    'status' => 'failure',
                    'description' => 'Invalid File'
                );
            }
        } else {
            $response = array(
                'status' => 'failure',
                'description' => 'Please upload file'
            );
        }
        echo json_encode($response);
        exit;
    }
    
function getUsersDetails($failed_txns){
    $this->autoRender = "False";
    $user_ids = array_filter(array_map(function($elm){ return $elm['user_id']; } ,$failed_txns));
    
    if(count($user_ids > 0)){   
        $user_id = implode("','", $user_ids);
        $res = $this->Slaves->query("Select u.id,u.balance,r.parent_id,r.id,r.user_id From retailers as r JOIN  users as u ON (r.user_id = u.id) where u.id IN ('". $user_id ."')"); 
        if(count($res) > 0){

            foreach ($res as $details){
                foreach ($failed_txns as $index => $txn){
                    if( $details['u']['id'] == $txn['user_id'] ){
                        $failed_txns[$index]['user_details'] = $details;
                    }
                }
            }
        }
    }
    return $failed_txns;
}
    
}
    ?>