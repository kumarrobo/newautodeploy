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
$sms_Q = SMSTADKA_SMSQ;

if($_GET['type'] == 'mail'){
    $sms_Q = SMSTADKA_MAILQ;    
}


function redis_connect(){
    try {			
        Predis\Autoloader::register();
        $openredis = new Predis\Client(array(
               'host' => "staging-redis.oq14zy.0001.use1.cache.amazonaws.com",
               'port' => REDIS_PORT 
        ));
    }
    catch (Exception $e) {
        echo "Couldn't connected to Redis";
        echo $e->getMessage();
        $openredis = false;
    }
    return $openredis;
}



$redisObj = redis_connect();
$request_ids = $redisObj->lrange($sms_Q,0,4);
echo "<pre>";
print_r($request_ids);
echo "</pre>";
$redisObj->quit();
