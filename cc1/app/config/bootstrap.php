<?php

define('SITE_NAME','http://panel.pay1.in');
define('DISTPANEL_URL','https://panel.pay1.in/');
define('RETPANEL_URL','https://shop.pay1.in/');
define('PROCESSOR_HOSTNAME','panel.pay1.in');//--------------------------------------------------
define('DOMAIN_TYPE','http');

define('SMARTPAY_URL','https://smartpay.pay1.in/api');
define('B2C_URL','https://b2c.pay1.in/index.php/api_new/action/');

if(class_exists('Configure')){
Configure::write('login_domains',array('cc.pay1.in','cc.pay1.me'));
Configure::write('login_as',array('5'=>'Distributor','8'=>'RM'));
}

define('SMS_SERVER_IP','34.204.211.60');
define('CRON_WHITELIST_IP','34.204.211.60,23.21.220.6,107.22.174.199,127.0.0.1');
define('PROXY_IP_PRIVATE','10.79.177.17');
define('PROXY_PORT','3100');
define('encKey','PuTyOuRK3yHeReeswrwerwr');

define('GOOGLE_API_KEY','AIzaSyDLjOsyEYa-A2n_M_BMviNoh5ozspe1le4');

//chat credentials
define('CHAT_SOAP_URL',"http://221.135.137.133/VoiceServices/UserDetails.asmx");

//modem backup server
define('MODEM_BACKUP_SERVER_URL','http://mysqlpay1.ddns.net:6081/start.php');

define('MEMCACHE_PORT',11211);
define('TPS_REDIS_PORT',6300);
define('REDIS_PORT','6300');

define('MODEM_REDIS_HOST','107.22.176.158');
define('MODEM_REDIS_PASSWORD','$avail$p@y!');
define('MODEM_REDIS_PORT','6300');

define('s3kycBucket','pay1bucket');
define('s3limitBucket','pay1limits');
define('s3MarketingBucket','pay1marketing');
define('dailytxnsbucket','pay1-daily-txns');
define('tdsbucket','pay1tds');

define('docbucket','livepay1dms');
define('GOOGLE_URL_SHORTNER_KEY','AIzaSyDsfOltyxiZ4pndUegkLV6MvmLtHiI2xas');
define('LEAD_BASE_URL', 'pay1.in');
define('apireconbucket','liveapirecon');
//WHITELIST IPS
define('OFFICE_IPS','127.0.0.1,122.170.110.229,103.43.162.133,49.248.130.100,103.226.85.3,182.74.214.234,182.74.214.236,49.248.17.244,49.248.17.245,49.248.17.243,49.248.17.242,182.74.214.237,182.74.214.235,220.227.219.197,182.75.158.74,103.226.85.154,14.192.27.171,14.192.27.172,14.192.27.173,14.192.27.174,49.248.75.250');


require_once 'live_config.php';
require_once 'constant.php';

define('USE_PROXY',0);
define('SMARTPAY_WEB_URL','https://smartpay.pay1.in');
define('DMT_CHECKSTATUS','https://remitapisv2.pay1.in/');
define('DMT_RESTRICT_START_DATE','2018-04-10');
define('PRAGATICAP_URL','http://loan.pragaticapital.in');

define('MANUAL_DIST_INCENTIVE_LIMIT',95);
define('NEWEKO_CHECKSTATUS','https://remitapisv3.pay1.in/');

