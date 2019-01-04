<?php
$config['secrets'] = array(
        'smartpay' => array('secret'=>'br878idnddgenpay1','service_ids'=>'8,9,10'),
        'microfinance' => array('secret'=>'83873djd$%h26#3','service_ids'=>'11,17'),
	'dmt' => array('secret'=>'6sh73djdsd56h26#3','service_ids'=>'12','product_id'=>'84'),
	'woocommerce' => array('secret'=>'78hjsa89erkoo9F$#','service_ids'=>'13'),
        'insurance' => array('secret'=>'S7j$J4#elirU%Gp832','service_ids'=>'14,15'),
        'pan_service' => array('secret'=>'A0$wnb85I7','service_ids'=>'20'),
        'pay1gold' => array('secret' => 'Y6qcTLGIbU5G2cm','service_ids' => '24'),
        'travel' => array('secret' => 'qx3fnygmLQofS2Y','service_ids' => '23')

);
$config['requestKey'] = 'A778M5U1MGHUZ4Qz';

$config['notification_url'] = array(
	8 => 'https://smartpay.pay1.in/api/updateService',
	9 => 'https://smartpay.pay1.in/api/updateService', 
	10 => 'https://smartpay.pay1.in/api/updateService',
	11=>'https://microfinance.pay1.in:8000/updateDocumentStatus',
	12=>'https://remitapisv3.pay1.in/api/activate'
);

