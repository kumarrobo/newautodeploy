<?php
$con = mysql_connect('prod-recharge.coyipz0wacld.us-east-1.rds.amazonaws.com','dev_pay1','DEV@PASSWD');
mysql_select_db('limits',$con);

$date = $_REQUEST['date1'];
$sql = "select id,amount from limits where date = '".$date."'";
$rs = mysql_query($sql);
$str = '';
while($arr = mysql_fetch_array($rs)){
    $str .= $arr['id'].'|'.$arr['amount'].'|<>|'; 
}
$str = substr($str, 0, -4);
echo $str;
?>

