<?php
ini_set('memory_limit', '-1');

date_default_timezone_set('Asia/Kolkata');
error_reporting(E_ALL);
ini_set('display_errors', '1');
$connection = mysql_connect("db-optimization.coyipz0wacld.us-east-1.rds.amazonaws.com","root",'vibhas_pay1');
if (!$connection) {
die("Database connection failed : " . mysql_error());
}

$connection1 = mysql_connect("db-optimization.coyipz0wacld.us-east-1.rds.amazonaws.com","root",'vibhas_pay1',true);
if (!$connection1) {
die("Database connection failed : " . mysql_error());
}
 
// 2. Select a database to use
mysql_select_db("shops",$connection);
if (!$connection) {
die("Database selection failed: " . mysql_error());
}

 mysql_select_db("dboptimization",$connection1);
if (!$connection1) {
die("Database selection failed: " . mysql_error());
}


$dump_date='2016-09-01';

if($argv[1]=='va'){
$vaquery = mysql_query("SELECT va.*,retailers.parent_id from vendors_activations as va left join retailers ON (retailers.id = va.retailer_id) where va.date = '{$dump_date}'",$connection);
$i=0;
while($obj = mysql_fetch_array($vaquery)) {
    
    $vtquery = mysql_query("SELECT sim_num,processing_time from vendors_transactions where ref_id ='{$obj['txn_id']}' order by id desc limit 0,1",$connection);
    
    $getVtData = mysql_fetch_array($vtquery);
    
    $vmquery = mysql_query("SELECT * from vendors_messages where va_tran_id ='{$obj['txn_id']}' order by id desc limit 0,1",$connection);
                            
    $getVmData = mysql_fetch_array($vmquery);
    
    $getStData  = mysql_query("Select discount_comission from shop_transactions where id = '{$obj['shop_transaction_id']}'",$connection);
    
    $fetchstData = mysql_fetch_array($getStData);
    
    if(empty($fetchstData['discount_comission'])){
        $retailer_marging = 0;
    } else {
        $retailer_marging = $fetchstData['discount_comission'];
    }
    
    if($argv[2]=='insert') {
    
     $insertVa = "INSERT INTO `shops`.`vendors_activations` 
                   (`id`, `vendor_id`, `product_id`, `mobile`, `param`, `amount`, `discount_commission`, `txn_id`, `vendor_refid`, `operator_id`, `shop_transaction_id`, `retailer_id`, `distributer_id`, `retailer_margin`, `invoice_id`, `status`, `prevStatus`, `api_flag`, `cause`, `code`, `timestamp`, `hr`, `date`, `reversal_date`, `cc_userid`, `tran_processtime`, `sim_num`, `updated_timestamp`) 
                   VALUES ('".$obj['id']."', '".$obj['vendor_id']."', '".$obj['product_id']."', '".$obj['mobile']."','".$obj['param']."','".$obj['amount']."', '".$obj['discount_commission']."', '".$obj['ref_code']."', '".$obj['vendor_refid']."', '".$obj['operator_id']."', '".$obj['shop_transaction_id']."', '".$obj['retailer_id']."', '".$obj['parent_id']."',{$retailer_marging},'".$obj['invoice_id']."','".$obj['status']."','".$obj['prevStatus']."','".$obj['api_flag']."','".$obj['cause']."','".$obj['code']."' ,'".$obj['timestamp']."','".date('H',strtotime($obj['timestamp']))."','".$obj['date']."', '',{$obj['complaintNo']}, '".$getVtData['processing_time']."', '".$getVtData['sim_num']."', '".$getVmData['timestamp']."')";
               
     if(mysql_query($insertVa,$connection1)){
         
         echo $i++."Record inserted"."\n";
         
     }
    } else {
        $updateVa = "update vendors_activations set discount_commission ='".$obj['discount_commission']."',retailer_margin='".$retailer_marging."',hr='".date('H',strtotime($obj['timestamp']))."',tran_processtime='".$getVtData['processing_time']."',sim_num ='".$getVtData['sim_num']."',updated_timestamp='".$getVmData['timestamp']."' where txn_id = '".$obj['txn_id']."'";
                                                  
        mysql_query($updateVa,$connection);
        
        echo $i++."Record updated"."\n";
    }
                           
    
   
}
}


if($argv[1]=='vm'){
$i=0;
 
$vasql = mysql_query("Select * from vendors_activations where date = '{$dump_date}'",$connection);

while($fetchsql = mysql_fetch_array($vasql)){
    
    $vtquery = "SELECT sim_num,processing_time,ref_id from vendors_transactions where ref_id ='".$fetchsql['txn_id']."' order by id desc limit 0,1";
     
     
     $res = mysql_query($vtquery,$connection);
     
     $count = mysql_num_rows($res);
     
     if($count) {
     $fetchvt = mysql_fetch_array($res);
     $vt[$fetchvt['ref_id']]['sim_num']= $fetchvt['sim_num'];
     $vt[$fetchvt['ref_id']]['processing_time']= $fetchvt['processing_time'];
     }
    
    $sql = mysql_query("Select * from vendors_messages where va_tran_id = '".$fetchsql['txn_id']."'",$connection);
    while ($fetch = mysql_fetch_array($sql)){
     $i++;
     
     $data[$fetch['va_tran_id']]= $fetch;
     $data[$fetch['va_tran_id']]['sim_num']= '';
     $data[$fetch['va_tran_id']]['processing_time']= '';

     $insertVm = "INSERT INTO `shops`.`vendors_messages` (`id`,`va_tran_id`,`vendor_refid`,`service_id`,`service_vendor_id`,`internal_error_code`,`response`,`status`,`timestamp`,`sim_num`,`processing_time`,`vm_date`)
                                            VALUES('{$data[$fetch['va_tran_id']]['id']}','{$data[$fetch['va_tran_id']]['va_tran_id']}','{$data[$fetch['va_tran_id']]['vendor_refid']}','{$data[$fetch['va_tran_id']]['service_id']}','{$data[$fetch['va_tran_id']]['service_vendor_id']}','{$data[$fetch['va_tran_id']]['internal_error_code']}','{$data[$fetch['va_tran_id']]['response']}','{$data[$fetch['shop_tran_id']]['status']}','{$data[$fetch['va_tran_id']]['timestamp']}','{$data[$fetch['shop_tran_id']]['sim_num']}','{$data[$fetch['va_tran_id']]['processing_time']}','".date('Y-m-d',strtotime($data[$fetch['va_tran_id']]['timestamp']))."')";
         
     mysql_query($insertVm,$connection1);
     
   echo $i."\n"; 
     
}
$vmquery = mysql_query("select id from vendors_messages where va_tran_id = '".$fetchsql['txn_id']."' order by id desc limit 0,1",$connection);
$vmres = mysql_fetch_array($vmquery);
if($vmres){
     $sql = "update vendors_messages set sim_num = '{$vt[$fetchsql['txn_id']]['sim_num']}',processing_time = '{$vt[$fetchsql['txn_id']]['processing_time']}' where id = '{$vmres['id']}' ";
     mysql_query($sql,$connection1);
}
}


}

if($argv[1]=='shops'){
   
    $stData = "Select * from shop_transactions limit 0,10";
    $res = mysql_query($stData,$connection1);
    while($fetch = mysql_fetch_array($res)){
    $target_opening = 0;
    $target_closing = 0;
    $source_closing = 0;
    $source_opening = 0;
    $qry = '';
            
    if($fetch['type']=='11'){
        $shop_tran_id = $fetch['id'];
        $fetch['id'] = $fetch['target_id'];
        $qry = 'order by id desc limit 0,1';
    }
        
        $getOpeningClosing = "Select * from opening_closing where shop_transaction_id = '{$fetch['id']}' $qry";
        $result = mysql_query($getOpeningClosing,$connection);
        $numrows = mysql_num_rows($result);
        if ($numrows) {
            while ($data = mysql_fetch_array($result)) {
                
               if($fetch['type']=='0'){
                   $target_opening = $data['opening'];
                   $target_closing = $data['closing'];
               } else if($fetch['type']=='1'){
                       if($data['group_id']==4){
                           $source_opening = $data['opening'];
                           $source_closing = $data['closing'];
                       } else {
                           $target_opening = $data['opening'];
                           $target_closing = $data['closing'];
                       }
               }else if(in_array($fetch['type'],array('2','25'))){
                       if($data['group_id']==5){
                           $source_opening = $data['opening'];
                           $source_closing = $data['closing'];
                       } else {
                           $target_opening = $data['opening'];
                           $target_closing = $data['closing'];
                       }
               } else if(in_array($fetch['type'],array('3','4','5','6','7','11','19'))){
                          $source_opening = $data['opening'];
                          $source_closing = $data['closing'];
                          $fetch['id'] = $shop_tran_id;
                  } else if($fetch['type']=='21' || $fetch['type']=='27'){
                      if(in_array($data['group_id'],array(6,11))){
                          $source_opening = $data['opening'];
                          $source_closing = $data['closing'];
                      } else {
                          $target_opening = $data['opening'];
                          $target_closing = $data['closing'];
                      }
                  }
                  else if($fetch['type']=='22'){
                       if($data['group_id']==4){
                          $source_opening = $data['opening'];
                          $source_closing = $data['closing'];
                      } else {
                          $target_opening = $data['opening'];
                          $target_closing = $data['closing'];
                      }
                  } else if ($fetch['type']=='26'){
                      if($data['group_id']=='11'){
                          $source_opening = $data['opening'];
                          $source_closing = $data['closing'];
                      } else {
                          $target_opening = $data['opening'];
                          $target_closing = $data['closing'];
                      }
                  } else {
                      
                  }
                
                  $updateShop = "update shop_transactions set source_id = '".$fetch['ref1_id']."',target_id = '".$fetch['ref2_id']."',source_opening = '".$source_opening."',source_closing = '".$source_closing."',target_opening = '".$target_opening."',target_closing = '".$target_closing."' where id = '".$fetch['id']."'";
               
            }
            // $sql = "INSERT INTO `shops`.`shop_transactions` (`id`, `source_id`, `target_id`, `user_id`, `amount`, `discount_comission`, `type`, `timestamp`, `date`, `confirm_flag`, `type_flag`, `note`, `source_opening`, `source_closing`, `target_opening`, `target_closing`) "
                               // . " VALUES ('{$fetch['id']}', '{$fetch['source_id']}', '{$fetch['target_id']}', '{$fetch['user_id']}', '{$fetch['amount']}', '{$fetch['discount_comission']}', '{$fetch['type']}', '{$fetch['timestamp']}', '{$fetch['date']}', '{$fetch['confirm_flag']}', '{$fetch['type_flag']}','{$fetch['note']}', '{$source_opening}', '{$source_closing}', '{$target_opening}', '{$target_closing}')";
        } else {
             //$sql = "INSERT INTO `shops`.`shop_transactions` (`id`, `source_id`, `target_id`, `user_id`, `amount`, `discount_comission`, `type`, `timestamp`, `date`, `confirm_flag`, `type_flag`, `note`, `source_opening`, `source_closing`, `target_opening`, `target_closing`) "
                   
            //  . " VALUES ('{$fetch['id']}', '{$fetch['source_id']}', '{$fetch['target_id']}', '{$fetch['user_id']}', '{$fetch['amount']}', '{$fetch['discount_comission']}', '{$fetch['type']}', '{$fetch['timestamp']}', '{$fetch['date']}', '{$fetch['confirm_flag']}', '{$fetch['type_flag']}','{$fetch['note']}', '{$source_opening}', '{$source_closing}', '{$target_opening}', '{$target_closing}')";
        
            $updateShop = "update shop_transactions set source_id = '".$fetch['source_id']."',target_id = '".$fetch['target_id']."',source_opening = '".$source_opening."',source_closing = '".$source_closing."',target_opening = '".$target_opening."',target_closing = '".$target_closing."' where id = '".$fetch['id']."'";
            
        }
        
        mysql_query($updateShop,$connection);
        
    }
}


?>