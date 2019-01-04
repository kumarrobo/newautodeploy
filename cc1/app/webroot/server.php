<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
date_default_timezone_set('Asia/Kolkata');
function sendMsg($id, $msg) {
	echo "id: $id" . PHP_EOL;
	echo "data: $msg" . PHP_EOL;
	echo PHP_EOL;
	ob_flush();
	flush();
}
//if($_REQUEST['msg'] == null)$_REQUEST['msg'] = 1;
if(isset($_REQUEST['sender'])){
	 
	$top = "<b><span style='color:#c73525;margin-right:10px;'>".strip_tags($_REQUEST['sender'])."</span></b><span style='color:#8c65e3'>".date('D:H:i:s')."</span>";
	if ($_REQUEST['sender']== "PAY1")
	{	$html = "<div class='msgrn'>$top";
		$html .= "<br/>".$_REQUEST['type'].": ".$_REQUEST['name'] . " (" .$_REQUEST['mobile'] ." )";
		$html .= "<br/><b>Amount:</b> ".$_REQUEST['amount'];
		if(isset($_REQUEST['transid']))$html .= "<br/><b>TxnID:</b> ".$_REQUEST['transid'];
		$html .= "</div>";
		$fp = fopen($_REQUEST['process']."pay1.txt", 'a');
		fwrite($fp, "$html\n");
	}
	else if (isset($_REQUEST['transid']) && $_REQUEST['sender']!= "TFR")
	{
		$html = "<div class='msgln'>$top";
		$html .= "<br/><b>Amount:</b> ".$_REQUEST['amount'];
		if(isset($_REQUEST['transid']))$html .= "<br/><b>TxnID:</b> ".$_REQUEST['transid'];
		if(isset($_REQUEST['available']))$html .= "<br/><b>Available Bal:</b> ".$_REQUEST['available'];
		
		$html .= "</div>";
		$fp = fopen($_REQUEST['process']."icici.txt", 'a');
		fwrite($fp, "$html\n");
	}
	else if ($_REQUEST['sender']== "TFR")
	{
		$html = "<div class='msgrn'>$top";
		$html .= "<br/>".$_REQUEST['type'].": ".$_REQUEST['name'] . " (" .$_REQUEST['mobile'] ." )";
		$html .= "<br/><b>Amount:</b> ".$_REQUEST['amount'];
		if(isset($_REQUEST['commission']))$html .= "<br/><b>Commission:</b> ".$_REQUEST['commission'] . "(" . round($_REQUEST['commission']*100/$_REQUEST['amount'],1) . "%)";
		$html .= "</div>";
		
		$fp = fopen($_REQUEST['process']."tfr.txt", 'a');
		fwrite($fp, "$html\n");	
	}
	else if(!isset($_REQUEST['time']))
	{
		$fp = fopen($_REQUEST['process'].".txt", 'a');
		fwrite($fp, "<div class='msgun'>$top<br/>".strip_tags(($_REQUEST['msg']))."</div>"."\n");
	}
	fclose($fp);
}
/*
 //if(file_exists($_REQUEST['process'].".txt") && filesize($_REQUEST['process'].".txt") >= 0){
 $handle = fopen($_REQUEST['process'].".txt", "r");
 $contents = fread($handle, filesize($_REQUEST['process'].".txt"));
 $handle = fopen($_REQUEST['process']."icici.txt", "r");
 $contentsicici = fread($handle, filesize($_REQUEST['process']."icici.txt"));
 $handle = fopen($_REQUEST['process']."pay1.txt", "r");
 $contentspay1 = fread($handle, filesize($_REQUEST['process']."pay1.txt"));
 */
$contents = file($_REQUEST['process'].".txt");
$contents = str_replace("\n",'',implode(array_reverse($contents)));
$contentsicici = file($_REQUEST['process']."icici.txt");
$contentsicici = str_replace("\n",'',implode(array_reverse($contentsicici)));
$contentspay1 = file($_REQUEST['process']."pay1.txt");
$contentspay1 = str_replace("\n",'',implode(array_reverse($contentspay1)));
$contentstfr = file($_REQUEST['process']."tfr.txt");
$contentstfr = str_replace("\n",'',implode(array_reverse($contentstfr)));

//fclose($handle);
//      fclose($handle1);
//      fclose($handle2);

//deleting file when it get bigger
//      if(filesize($_REQUEST['process'].".txt")>11100){
//              @unlink($_REQUEST['process'].".txt");
//      }
//}

$contents = str_replace("\r\n",'',$contents);
//$contents = str_replace("\n",'',$contents);
$contentsicici = str_replace("\r\n",'',$contentsicici);
//$contentsicici = trim(str_replace("\n",'',$contentsicici));
$contentspay1 = str_replace("\r\n",'',$contentspay1);
//$contentspay1 = str_replace("\n",'',$contentspay1);
$contentstfr = str_replace("\r\n",'',$contentstfr);

$finalcontent = "<div class='msglx'>".$contentsicici."</div><div class='msglx'>".$contentspay1."</div><div class='msglx'>".$contentstfr."</div><div class='msglx' id='uchat'>".$contents."</div>";
sendMsg(time(),$finalcontent);
?>