<?php

$config['services'] = array(
        '1' => array('name'=>'Mobile Recharge','fixed'=>0,'variable'=>0.5,'tertiary_flag'=>1,'allow_negative'=>false,'incentive_flag'=>true,'commission'=>true),
        '2' => array('name'=>'DTH Recharge','fixed'=>0,'variable'=>0.5,'tertiary_flag'=>1,'allow_negative'=>false,'incentive_flag'=>true,'commission'=>true),
        '4' => array('name'=>'Postpaid Bills','fixed'=>0,'variable'=>0.5,'tertiary_flag'=>1,'allow_negative'=>true,'incentive_flag'=>false,'commission'=>true),
        '6' => array('name'=>'Utility Bills','fixed'=>0,'variable'=>0.1,'tertiary_flag'=>1,'allow_negative'=>true,'incentive_flag'=>false,'commission'=>true),
        '7' => array('name'=>'C2D Recharge','fixed'=>0,'variable'=>0,'tertiary_flag'=>1,'allow_negative'=>false,'incentive_flag'=>false,'commission'=>true),
        /*'8' => array('name'=>'MPOS','fixed'=>0,'variable'=>0.2,'tertiary_flag'=>0,'allow_negative'=>false,'incentive_flag'=>false,'commission'=>false),
        '9' => array('name'=>'UPI','fixed'=>0,'variable'=>0.2,'tertiary_flag'=>0,'allow_negative'=>false,'incentive_flag'=>false,'commission'=>false),
        '10' => array('name'=>'AEPS','fixed'=>0,'variable'=>0.2,'tertiary_flag'=>0,'allow_negative'=>false,'incentive_flag'=>false,'commission'=>false),
        '12' => array('name'=>'DMT','fixed'=>0,'variable'=>0.2,'tertiary_flag'=>1,'allow_negative'=>true,'incentive_flag'=>false,'commission'=>true,'gst_incentive'=>true),
        '13' => array('name'=>'SmartBuy','fixed'=>0,'variable'=>0.5,'tertiary_flag'=>1,'allow_negative'=>false,'incentive_flag'=>false,'commission'=>true),
        '14' => array('name'=>'Insurance - Personal Accident','fixed'=>0,'variable'=>5,'tertiary_flag'=>1,'allow_negative'=>false,'incentive_flag'=>false,'commission'=>true),
        '18' => array('name'=>'Pay1 Travel','fixed'=>0,'variable'=>0.2,'tertiary_flag'=>1,'allow_negative'=>false,'incentive_flag'=>false,'commission'=>true)*/
);

/*$config['rental_incentives'] = array(
        '8' => array('fixed'=>20,'variable'=>0),
);*/

/*$config['products'] = array(
 '74' => array('fixed'=>5,'variable'=>0,'service_tax'=>0,'tds'=>5),
 );*/
$config['retailer_commission_12_default'] = 0.6;
$config['retailer_commission_6'] = array(
        '0-1000' => array('fixed'=>0,'variable'=>0,'service_tax'=>1,'tds'=>0),
        '1001-2000' => array('fixed'=>5,'variable'=>0,'service_tax'=>1,'tds'=>0),
        '2001-500000' => array('fixed'=>10,'variable'=>0,'service_tax'=>1,'tds'=>0)
);

$config['retailer_commission_12'] = array(
        'ret_margin' => array('margin'=>$config['retailer_commission_12_default'],'service_charge'=>1,'service_tax'=>0),
        'min' => 10,
        'max' => 500
);


/*$config['retailer_commission_12'] = array(
        '0-1000' => array('fixed'=>0,'variable'=>0,'service_tax'=>1,'tds'=>0),
        '1001-2000' => array('fixed'=>5,'variable'=>0,'service_tax'=>1,'tds'=>0),
        '2001-500000' => array('fixed'=>10,'variable'=>0,'service_tax'=>1,'tds'=>0)
);*/
