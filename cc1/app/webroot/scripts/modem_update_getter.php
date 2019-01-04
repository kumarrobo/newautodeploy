<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$DIR_PATH = __DIR__;
chdir($DIR_PATH);
chdir('../../');
require_once('vendors/Predis/Autoloader.php');
require_once('config/bootstrap.php');

$handler_Q = "UPDATE_HANDLER_Q";

function openservice_redis(){
    try {			
        Predis\Autoloader::register();
        $openredis = new Predis\Client(array(
               'host' => MODEM_REDIS_HOST,
               'password' => MODEM_REDIS_PASSWORD,
               'port' => MODEM_REDIS_PORT 
        ));
    }
    catch (Exception $e) {
        echo "Couldn't connected to Redis";
        echo $e->getMessage();
        $openredis = false;
    }
    return $openredis;
}

function is_json($str){
    return $result = is_array($str)?false:json_decode($str,false) != null;
}

function format_json($param) {
    return is_json($param) ? $param : json_encode($param);
}

$redisObj = openservice_redis();

while (TRUE){
    if($redisObj == false){
        $redisObj = openservice_redis();
        sleep(2);
        continue;
        //break;
    }    
    $request_id = $redisObj->rpop($handler_Q);
    if($request_id === NULL){
        sleep(2);
	    continue;
    }
    file_put_contents('/tmp/updaterchanges_process.txt', date('Y-m-d H:i:s')." | ".$request_id." \n", FILE_APPEND | LOCK_EX);
    shell_exec("sh " . $DIR_PATH."/modem_update_setter.sh $request_id");
}


