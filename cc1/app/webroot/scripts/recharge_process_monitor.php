<?php 

## ---- getting count of process running for execution
$EXP_PROC_CNT=20;
$CUR_PROC_CNT = shell_exec("ps -ef | grep recharge_processes_sender | wc -l");
$CUR_PROC_CNT = $CUR_PROC_CNT - 2;

$DIR_PATH = __DIR__;
chdir($DIR_PATH);
chdir('../../');
require_once('vendors/Predis/Autoloader.php');
require_once('config/bootstrap.php');

$handler_Q = "TXN_REQUEST_QUEUE";

function openservice_redis(){
    try {			
        Predis\Autoloader::register();
        $openredis = new Predis\Client(array(
               'host' => TPS_REDIS,
               'port' => TPS_REDIS_PORT
        ));
    }
    catch (Exception $e) {
        echo "Couldn't connected to Redis";
        echo $e->getMessage();
        $openredis = false;
    }
    return $openredis;
}

function curl_post_async($url, $params=null)
{
    foreach ($params as $key => &$val) {
      if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts=parse_url($url);

    $fp = fsockopen($parts['host'],
        isset($parts['port'])?$parts['port']:80,
        $errno, $errstr, 30);

    $out = "POST ".$parts['path']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string)) $out.= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}

while ($CUR_PROC_CNT < $EXP_PROC_CNT){
	$START_PROC="nohup php $DIR_PATH/recharge_processes_sender.php > /dev/null 2> /dev/null & echo $!";
    shell_exec($START_PROC);
	$CUR_PROC_CNT=$CUR_PROC_CNT + 1;
    echo "$lastmin :Increased current process to : $CUR_PROC_CNT";
}

$redisObj = openservice_redis();
$total = $redisObj->llen($handler_Q);
if($total > $EXP_PROC_CNT*2){
    $MOBILETO="9819032643,9221770571,7738832731";
    $sms = "Recharges queue length is $total on server & we have total " . ($EXP_PROC_CNT + 2) . " processes running";
    $SMS_URL=SMS_MAIL_SERVER."redis/insertInQsms";
    curl_post_async($SMS_URL,array('root'=>'payone','sender'=>'','mobile'=>$MOBILETO,'sms'=>$sms));
}

?>