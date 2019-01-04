<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
session_name('CAKEPHP');
session_start();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
date_default_timezone_set('Asia/Kolkata');

$fp1 = fopen("/var/log/apps/limits_request_new_".date('Ymd').".log","a+");
fwrite($fp1,json_encode($_REQUEST)."\n");
fwrite($fp1,"=================\n");

ob_start();
/*$con = mysql_connect('ec2db.pay1intra.in','development','1602f9d7ae');
mysql_select_db('limits',$con);*/
$con = mysql_connect('prod-recharge.coyipz0wacld.us-east-1.rds.amazonaws.com','dev_pay1','DEV@PASSWD');
mysql_select_db('limits',$con);
//$con = mysql_connect('pay1-copydata.coyipz0wacld.us-east-1.rds.amazonaws.com','root','dboptimization');
//mysql_select_db('shops',$con);

$date = $_REQUEST['date1'];
if($date==''){
        $date =date('Ymd');
}
//$date = "20140227";
//echo "date=".$date;
function sendMsg($id,$msg) {
        echo "id: $id" . PHP_EOL;
        echo "data: $msg" . PHP_EOL;
        echo PHP_EOL;
        ob_flush();
        flush();
}

function maskNumber($number){
        return $number;
        //return preg_replace('/(1|2|3|4|5)/i',"x",$number);
}

//db insert start
if(isset($_REQUEST['sender'])){

        if ($_REQUEST['sender']== "PAY1")
 {
                $transid_arr = explode('_',$_REQUEST['transid']);
                $bank_name = $transid_arr[0];
                $account_no = $transid_arr[1];
                $trans_type = $transid_arr[2];
                $time = date('D:H:i:s');

                if($_REQUEST['type'] == "Super Distributor"){
                    $dist_id = "";
                    $super_dist_id = $_REQUEST['id'];
                }else{
                    $dist_id = $_REQUEST['id'];
                    $super_dist_id = "";
                }

                $query = "insert into limits(type,sender,bank_name,account_no,amount,transid,time,dist_type,dist_name,mobile,trans_type,created_on,showFlag,dist_id,super_dist_id,date,bank_details)
                values('PAY1','".strip_tags($_REQUEST['sender'])."','".$bank_name."','".$account_no."','".$_REQUEST['amount']."','".$_REQUEST['transid']."','".$time."','".$_REQUEST['type']."','".addslashes($_REQUEST['name'])."','".$_REQUEST['mobile']."','".$trans_type."','".date('YmdHis')."','Y','".$dist_id."','".$super_dist_id."','".date('Y-m-d')."','".$_REQUEST['bank_details']."')";
                fwrite($fp1,"PAY1-query=".$query."\n");
                $rs = mysql_query($query);
                //www.limits.local/server.php?sender=PAY1&amount=2000&time=Thu:13:41:15&transid=bom-4079_BKDNH13302648366_NEFT-RTGS&type=Retailer&name=SAI BABA BISKIT&mobile=9004777614

        }

else if (isset($_REQUEST['transid']) && $_REQUEST['sender']!= "TFR")
        {
                //$time = date('D:H:i:s');
                $time = date('D:H:i:s',strtotime($_REQUEST['time']));

                $transid_arr = explode('_',$_REQUEST['transid']);
                $bank = $transid_arr[0];
                $account_no = $transid_arr[1];
                $trans_type = $transid_arr[2];
                $query = "insert into limits(type,sender,bank_name,account_no,amount,transid,avail_bal,time,dist_type,dist_name,mobile,trans_type,created_on,showFlag,date)
                values('BANK','".strip_tags($_REQUEST['sender'])."','".$bank."','".$account_no."','".$_REQUEST['amount']."','".$_REQUEST['transid']."','".$_REQUEST['available']."','".$time."','".$_REQUEST['type']."','".addslashes($_REQUEST['name'])."','".$_REQUEST['mobile']."','".$trans_type."','".date('YmdHis')."','Y','".date('Y-m-d')."')";
                fwrite($fp1,"transid and not TFR-query=".$query."\n");
                $rs = mysql_query($query);
 //www.limits.local/server.php?sender=ICICI&available=5000&amount=2000&time=Thu:13:00:00&transid=bom-4079_BKDNH13302648366_NEFT-RTGS&type=Retailer&name=SAI BABA BISKIT&mobile=9004777614

        }
        else if ($_REQUEST['sender']== "TFR")
        {

             $query = "insert into limits(type,sender,amount,time,commission_perc,commission_amt,dist_type,dist_name,mobile,created_on,showFlag,date)
                values('TFR','".strip_tags($_REQUEST['sender'])."','".$_REQUEST['amount']."','".date('D:H:i:s')."','".round($_REQUEST['commission']*100/$_REQUEST['amount'],1) . "%','".$_REQUEST['commission']."','".$_REQUEST['type']."','".addslashes($_REQUEST['name'])."','".$_REQUEST['mobile']."','".date('YmdHis')."','Y','".date('Y-m-d')."')";
                fwrite($fp1,"TFR-query=".$query."\n");
                $rs = mysql_query($query);
                //www.limits.local/server.php?sender=TFR&amount=2000&time=Thu:13:00:00&transid=bom-4079_BKDNH13302648366_NEFT-RTGS&type=Retailer&commission=555.00&name=SAI BABA BISKIT&mobile=9004777614       
        }
else if(!isset($_REQUEST['time']))
        {
                $query = "insert into limits(type,sender,msg,created_on,showFlag,date)
                values('OFFICE','".strip_tags($_REQUEST['sender'])."','".$_REQUEST['msg']."','".date('YmdHis')."','Y','".date('Y-m-d')."')";
                fwrite($fp1,"Else time-query=".$query."\n");
                $rs = mysql_query($query);
                //www.limits.local/server.php?sender=Sunil&msg=Hari Om Kirana Store  done
        }
}
fclose($fp1);
//db insert end

$contents_bank = "SMS from Bank";
$contents_pay1 = "SMS from Distributors";
$contentstfr = "Transfered Commission";
$contents = "Balance transfer comments";

$process_query = mysql_query("select sender,time,msg from limits where type='OFFICE' and date = '".$date."' order by  id desc");

while($process_arr = mysql_fetch_array($process_query)){
    $contents .= "<div class='msgun'><b><span style='color:#c73525;margin-right:10px;'>".$process_arr['sender']."</span></b><span style='color:#8c65e3'>".$process_arr['time']."</span><br/> ".$process_arr['msg']."</div>";

}

// Bank column start
$contents_bank_visible = '<div id="div_bank_sms">';
$process_bank_query = mysql_query("select id,type,sender,amount,transid,avail_bal,time,dist_type,dist_name,mobile,showFlag,created_on,modified_on from limits where type='BANK' and showFlag='Y' and date = '".$date."' order by created_on desc");

while($process_bank_arr = mysql_fetch_array($process_bank_query)){
        $param = "'bank',".$process_bank_arr['id'].",'N'";
        if($process_bank_arr['modified_on']!=""){
            $color = "#FF0000;";
        }else{
            $color = "#8c65e3;";
        }
        if (in_array($_SESSION['Auth']['User']['group_id'], array('18','38','29'))) {
                $cond = "onclick=smspay1hide(".$param.")";
                $close = "Close";
        }
        $contents_bank_visible.= "<div name='smsbank' id='bank_sms_".$process_bank_arr['id']."' class='msgln'><div style='float:left;'><b><span style='color:".$color."margin-right:10px;'>".$process_bank_arr['sender']."</span></b><span style='color:#8c65e3'>".$process_bank_arr['time']."</span><span style='font-size:11px;'>&nbsp;&nbsp;id=".substr($process_bank_arr['id'],-3)."</span></div><div style='float:right;font-size:12px;cursor:pointer;' $cond  >$close</div><div style='float:right;'></div><div style='float:left;width:100%'><b>Amount:</b> ".$process_bank_arr['amount']." <span style='font-size:11px;' id='amtwrds_".$process_bank_arr['id']."'></span></div><br/><b>TxnID:</b> ".$process_bank_arr['transid']."<br/><b>Available Bal:</b> ".$process_bank_arr['avail_bal']."</div>";
        $cond  = '';
        $close = '';

}
$contents_bank_visible .= '</div>';
$contents_bank_invisible = '<br/><br/><div id="hidden_bank_sms" style="margin-top:100px;">';
$process_bank_query = mysql_query("select id,type,sender,amount,transid,avail_bal,time,dist_type,dist_name,mobile,showFlag,created_on,modified_on from limits where type='BANK' and showFlag='N' and date = '".$date."' order by modified_on desc");

while($process_bank_arr = mysql_fetch_array($process_bank_query)){
        $param = "'bank',".$process_bank_arr['id'].",'Y'";
        $unixtime = strtotime($process_bank_arr['modified_on']);
        $modified_on = date('D:H:i:s',$unixtime);
        $contents_bank_invisible.= "<div name='smsbank' id='hidden_bank_sms_".$process_bank_arr['id']."' class='msgln_hidden'><div style='float:left;'><b><span style='color:#c73525;margin-right:10px;'>".$process_bank_arr['sender']."</span></b><span style='color:#8c65e3;font-size:11px;'>".$process_bank_arr['time']."&nbsp;Modified On:".$modified_on."</span><span style='font-size:11px;'>&nbsp;&nbsp;id=".substr($process_bank_arr['id'],-3)."</span></div><div style='float:right;font-size:12px;cursor:pointer;' onclick=smspay1hide(".$param.") >Open</div><br/><br/><div style='float:left;width:100%'><b>Amount:</b> ".$process_bank_arr['amount']." <span style='font-size:11px;' id='amtwrds_".trim($process_bank_arr['id'])."'></span></div><br/><b>TxnID:</b> ".$process_bank_arr['transid']."<br/><b>Available Bal:</b> ".$process_bank_arr['avail_bal']."</div>";

}
$contents_bank_invisible .= '</div>';
$contents_bank .= $contents_bank_visible.$contents_bank_invisible;
// Bank column end

// Pay1 column start
$contents_pay1_visible = '<div id="div_pay1_sms">';
$process_pay1_query = mysql_query("select dist_id,super_dist_id,id,type,sender,amount,transid,time,dist_type,dist_name,mobile,modified_on,showFlag,bank_details from limits where type='PAY1' and showFlag = 'Y' and date = '".$date."' order by created_on desc");

while($process_pay1_arr = mysql_fetch_array($process_pay1_query)){
    $param = "'pay1',".$process_pay1_arr['id'].",'N'";
    if($process_pay1_arr['modified_on']!=""){
        $color = "#FF0000;";
    }else{
        $color = "#8c65e3;";
    }

    if($process_pay1_arr['dist_type'] == "Super Distributor"){
        $Id = $process_pay1_arr['super_dist_id'];
    }else{
       $Id = $process_pay1_arr['dist_id'];
    }


    $bank_details = json_decode($process_pay1_arr['bank_details'],1);
    if (in_array($_SESSION['Auth']['User']['group_id'], array('18','38','29'))) {
        $cond = "onclick=smspay1hide(".$param.")";
        $close = "Close";
    }
    $contents_pay1_visible .= "<div class='msgrn_new' id='pay1_sms_".$process_pay1_arr['id']."'><b><span style='color:".$color."margin-right:10px;'>".$process_pay1_arr['sender']."</span></b><span style='color:#8c65e3;'>".$process_pay1_arr['time']."</span><span style='font-size:11px;'>&nbsp;&nbsp;id=".substr($process_pay1_arr['id'],-3)."</span><div style='float:right;font-size:12px;cursor:pointer;' $cond  >$close</div><br/>".$process_pay1_arr['dist_type'].": ".json_encode($process_pay1_arr['dist_name'])." Id:".$Id." (".maskNumber($process_pay1_arr['mobile'])." )<br/><b>Amount:</b> ".$process_pay1_arr['amount']." <span style='font-size:11px;' id='amtwrds_".trim($process_pay1_arr['id'])."'></span><br/><b>TxnID:</b> ".json_encode($process_pay1_arr['transid'])."<br/><b>Branch: </b>".$bank_details['branch_name']." - ".$bank_details['branch_code'];
    $cond  = '';
    $close = '';
    if($bank_details['bank_slip'] != '') {
        $contents_pay1_visible .= " <a href='".$bank_details['bank_slip']."' target='_blank'><span style='color:red'>View</span></a>";
    }
    $contents_pay1_visible .= "&nbsp;&nbsp;</div>";

}
$contents_pay1_visible .= '</div>';

$contents_pay1_invisible = '<br/><br/><div id="hidden_pay1_sms" style="margin-top:100px;">';
$process_pay1_query = mysql_query("select dist_id,super_dist_id,id,type,sender,amount,transid,time,dist_type,dist_name,mobile,modified_on,showFlag from limits where type='PAY1' and showFlag='N' and date = '".$date."' order by modified_on desc");
$i=0;
while($process_pay1_arr = mysql_fetch_array($process_pay1_query)){

    if($process_pay1_arr['dist_type'] == "Super Distributor"){
        $Id = $process_pay1_arr['super_dist_id'];
    }else{
       $Id = $process_pay1_arr['dist_id'];
    }


    $param = "'pay1',".$process_pay1_arr['id'].",'Y'";
    $unixtime = strtotime($process_pay1_arr['modified_on']);
    $modified_on = date('D:H:i:s',$unixtime);
    $contents_pay1_invisible .= "<div style='border-style:solid;border-width:1px;' class='msgrn_new_hidden' id='hidden_pay1_sms_".$process_pay1_arr['id']."'><b><span style='color:#c73525;margin-right:10px;'>".$process_pay1_arr['sender']."</span></b><span style='color:#8c65e3;font-size:11px;'>".$process_pay1_arr['time']."&nbsp;Modified On:".$modified_on."</span><span style='font-size:11px;'>&nbsp;&nbsp;id=".substr($process_pay1_arr['id'],-3)."</span><div style='float:right;font-size:12px;cursor:pointer;' onclick=smspay1hide(".$param.") >Open<br/></span></div><br/>".$process_pay1_arr['dist_type'].": ".json_encode($process_pay1_arr['dist_name'])." Id:".$Id." (".maskNumber($process_pay1_arr['mobile'])." )<br/><b>Amount:</b> ".$process_pay1_arr['amount']." <span style='font-size:11px;' id='amtwrds_".trim($process_pay1_arr['id'])."'></span><br/><b>TxnID:</b> ".json_encode($process_pay1_arr['transid'])." </div>";

}
$contents_pay1_invisible .= '</div>';
$contents_pay1 .= $contents_pay1_visible.$contents_pay1_invisible;
// Pay1 column end

$process_tfr_query = mysql_query("select type,sender,amount,time,commission_perc,commission_amt,dist_type,dist_name,mobile,showFlag,date from limits where type='TFR' and date = '".$date."' order by  id desc");

while($process_tfr_arr = mysql_fetch_array($process_tfr_query)){
    $contentstfr .= "<div class='msgrn'><b><span style='color:#c73525;margin-right:10px;'>".$process_tfr_arr['sender']."</span></b><span style='color:#8c65e3'>".$process_tfr_arr['time']."</span><br/>".$process_tfr_arr['dist_type'].": ".$process_tfr_arr['dist_name']." (".maskNumber($process_tfr_arr['mobile'])." )<br/><b>Amount:</b> ".$process_tfr_arr['amount']."<br/><b>Commission:</b> ".$process_tfr_arr['commission_amt']."(".$process_tfr_arr['commission_perc'].")</div>";

}
$finalcontent = "<div class='msglx'>".$contents_bank."</div><div class='msglx_new'>".$contents_pay1."</div><div class='msglx'>".$contentstfr."</div><div class='msglx' id='uchat'>".$contents."</div>";

sendMsg(time(),$finalcontent);
