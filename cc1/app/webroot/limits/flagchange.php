<?php
$con = mysql_connect('prod-recharge.coyipz0wacld.us-east-1.rds.amazonaws.com','dev_pay1','DEV@PASSWD');
mysql_select_db('limits',$con);

$id = $_REQUEST['id'];
$showFlag = $_REQUEST['setFlag'];

$sqlUpdate = "update limits set showFlag = '".$showFlag."', modified_on='".date('YmdHis')."' where id=".$id;
$rsupdate = mysql_query($sqlUpdate);
//if($rsupdate) echo "Y"; else "N";
?>