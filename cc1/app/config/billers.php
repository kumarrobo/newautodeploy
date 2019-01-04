<?php
$config['categories'] = array('1'=>array('name'=>'Electricity','logo'=>'https://s3.amazonaws.com/billericons/utility_electricity.png'),'2'=>array('name'=>'Landline','logo'=>'https://s3.amazonaws.com/billericons/utility_landline.png'),'3'=>array('name'=>'Gas','logo'=>'https://s3.amazonaws.com/billericons/utility_gas.png'),'4'=>array('name'=>'Water','logo'=>'https://s3.amazonaws.com/billericons/utility_water.png'));
$config['billers'] = array(
        '45'=>array('Name'=>'Adani Electricity Mumbai Limited', 'product_id'=>'45', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/45.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer number', 'param'=>'accountNumber', 'sample'=>'156021112', 'regex'=>'^[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Cycle Number', 'param'=>'param', 'sample'=>'05', 'regex'=>'^[0-9]{2}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '46'=>array('Name'=>'BSES Rajdhani Power Limited', 'product_id'=>'46', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/46.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'CA Number', 'param'=>'accountNumber', 'sample'=>'151390169', 'regex'=>'^[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '47'=>array('Name'=>'BSES Yamuna Power Limited', 'product_id'=>'47', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/47.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'CA Number', 'param'=>'accountNumber', 'sample'=>'100202968', 'regex'=>'^[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '48'=>array('Name'=>'North Delhi Power Limited', 'product_id'=>'48', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/48.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'60020657932', 'regex'=>'^[0-9]{11,12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '89'=>array('Name'=>'BEST', 'product_id'=>'89', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/89.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'4240750538', 'regex'=>'^[0-9a-zA-Z]{9,10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '90'=>array('Name'=>'MSEDC Limited', 'product_id'=>'90', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/90.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer No', 'param'=>'accountNumber', 'sample'=>'000090621084', 'regex'=>'^[0-9]{12}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'BU', 'param'=>'param', 'sample'=>'4641', 'regex'=>'^[0-9]{4}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Processing Cycle', 'param'=>'param1', 'sample'=>'07', 'regex'=>'^[0-9]{2}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '91'=>array('Name'=>'Rajasthan Vidyut Vitran Nigam Limited', 'product_id'=>'91', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/91.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'K Number', 'param'=>'accountNumber', 'sample'=>'210434016055', 'regex'=>'^[2-3]{1}[0-9]{11}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '92'=>array('Name'=>'Torrent Power', 'product_id'=>'92', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/92.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Service Number', 'param'=>'accountNumber', 'sample'=>'500287574', 'regex'=>'^[0-9]{1,15}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'City', 'param'=>'param', 'sample'=>'Surat/Agra/Ahmedabad/Bhiwandi', 'regex'=>'^[0-9a-zA-Z]{1,30}$','bill_fetch'=>true,'input'=>'list'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '93'=>array('Name'=>'Bangalore Electricity Supply Company', 'product_id'=>'93', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/93.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Customer ID / Account ID', 'param'=>'accountNumber', 'sample'=>'9285555000', 'regex'=>'^[0-9]{1,10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '94'=>array('Name'=>'MP Madhya Kshetra Vidyut Vitaran - URBAN', 'product_id'=>'94', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/94.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'IVRS', 'param'=>'accountNumber', 'sample'=>'4144342241', 'regex'=>'^[0-9]{7,15}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '95'=>array('Name'=>'Noida Power Company Limited', 'product_id'=>'95', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/95.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'2000072163', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '96'=>array('Name'=>'MP Paschim Kshetra Vidyut Vitaran', 'product_id'=>'96', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/96.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Customer Number', 'param'=>'accountNumber', 'sample'=>'3962734000', 'regex'=>'^[0-9a-zA-Z]{2,30}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '97'=>array('Name'=>'Calcutta Electricity Supply Corporation', 'product_id'=>'97', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/97.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Customer ID (Not Consumer No)', 'param'=>'accountNumber', 'sample'=>'04000435972', 'regex'=>'^[0-9]{11}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '98'=>array('Name'=>'Chhattisgarh State Power Distribution Company Limited in Electricity', 'product_id'=>'98', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/98.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Business Partner Number', 'param'=>'accountNumber', 'sample'=>'1000029086', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '99'=>array('Name'=>'India Power Corporation Limited', 'product_id'=>'99', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/99.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'100530940004', 'regex'=>'^[0-9]{10,12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'State', 'param'=>'param', 'sample'=>'Bihar/WestBengal/Other', 'regex'=>'^[a-zA-Z]{1,15}$','bill_fetch'=>true,'input'=>'list'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '100'=>array('Name'=>'Jamshedpur Utilities and Services Company Limited', 'product_id'=>'100', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/100.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Business Partner Number', 'param'=>'accountNumber', 'sample'=>'0010027747', 'regex'=>'^[0-9a-zA-Z]{6,10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '101'=>array('Name'=>'Tripura Electricity Corporation Ltd', 'product_id'=>'101', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/101.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer ID', 'param'=>'accountNumber', 'sample'=>'110122458', 'regex'=>'^[0-9a-zA-Z]{1,12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '102'=>array('Name'=>'Assam Power Distribution Company Ltd Urban', 'product_id'=>'102', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/102.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer ID', 'param'=>'accountNumber', 'sample'=>'53000003468', 'regex'=>'^[0-9]{11,12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '103'=>array('Name'=>'Jaipur Vidyut Vitran Nigam', 'product_id'=>'103', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/103.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'K Number', 'param'=>'accountNumber', 'sample'=>'530000039468', 'regex'=>'^[0-9]{12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '104'=>array('Name'=>'Jodhpur Vidyut Vitran Nigam', 'product_id'=>'104', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/104.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'K Number', 'param'=>'accountNumber', 'sample'=>'530000034698', 'regex'=>'^[0-9]{12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '105'=>array('Name'=>'Ajmer Vidyut Vitran Nigam', 'product_id'=>'105', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/105.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'K Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '115'=>array('Name'=>'Bharatpur Electricity Services Ltd', 'product_id'=>'115', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/115.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'K Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^2[0-9a-zA-Z]{11}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '116'=>array('Name'=>'Bikaner Electricity Supply Limited', 'product_id'=>'116', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/116.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'K Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^3[0-9a-zA-Z]{11}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '117'=>array('Name'=>'Daman and Diu Electricity', 'product_id'=>'117', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/117.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Account Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{1,6}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '118'=>array('Name'=>'Eastern Power Distribution Co Ltd', 'product_id'=>'118', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/118.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Service Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9a-zA-Z]{8,20}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '119'=>array('Name'=>'Kota Electricity Distribution Limited', 'product_id'=>'119', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/119.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'K Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^2[0-9a-zA-Z]{11}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '120'=>array('Name'=>'Meghalaya Power Dist Corp Ltd', 'product_id'=>'120', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/120.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer ID', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{1,12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '121'=>array('Name'=>'Muzaffarpur Vidyut Vitran Limited', 'product_id'=>'121', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/121.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer No', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{10,12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '122'=>array('Name'=>'North Bihar Power Distribution Company Ltd', 'product_id'=>'122', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/122.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'CA Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{9,11}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '123'=>array('Name'=>'NESCO - Odisha', 'product_id'=>'123', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/123.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{1,15}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '124'=>array('Name'=>'South Bihar Power Distribution Company Ltd', 'product_id'=>'124', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/124.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'CA Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{9,11}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '125'=>array('Name'=>'SNDL Nagpur', 'product_id'=>'125', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/125.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer No', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{1,15}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '126'=>array('Name'=>'SOUTHCO- Odisha', 'product_id'=>'126', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/126.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{1,15}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '127'=>array('Name'=>'Southern Power Distribution Co Ltd (APSPDCL)', 'product_id'=>'127', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/127.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Service Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{9,13}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '128'=>array('Name'=>'TP Ajmer Distribution Ltd (TPADL)', 'product_id'=>'128', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/128.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'K Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{1,15}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '129'=>array('Name'=>'Uttarakhand Power Corporation Limited', 'product_id'=>'129', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/129.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Service Connection Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9a-zA-Z]{13}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '130'=>array('Name'=>'Uttar Pradesh Power Corp Ltd (UPPCL) - Urban', 'product_id'=>'130', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/130.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'5300000349', 'regex'=>'^[0-9]{10,12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '131'=>array('Name'=>'Tata Power - Delhi', 'product_id'=>'131', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/131.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'CA Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{1,15}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '132'=>array('Name'=>'Tata Power - Mumbai', 'product_id'=>'132', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/132.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'530000034968', 'regex'=>'^[0-9]{12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        
        '49'=>array('Name'=>'Airtel', 'product_id'=>'49', 'category'=>'2','logo_url'=>'https://s3.amazonaws.com/billericons/49.png', 'bill_fetch'=>false, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>false, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Landline Number', 'param'=>'accountNumber', 'sample'=>'02240039296', 'regex'=>'^[0-9]{11}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>true,'input'=>'text'))),
        '50'=>array('Name'=>'MTNL - Delhi', 'product_id'=>'50', 'category'=>'2','logo_url'=>'https://s3.amazonaws.com/billericons/50.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Account Number', 'param'=>'accountNumber', 'sample'=>'22356805', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Telephone Number', 'param'=>'param', 'sample'=>'2073152969', 'regex'=>'^[0-9]{8}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '110'=>array('Name'=>'MTNL - Mumbai', 'product_id'=>'110', 'category'=>'2','logo_url'=>'https://s3.amazonaws.com/billericons/110.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Account Number', 'param'=>'accountNumber', 'sample'=>'22356805', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Telephone Number', 'param'=>'param', 'sample'=>'2073152969', 'regex'=>'^[0-9]{1,15}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        
        '88'=>array('Name'=>'BSNL', 'product_id'=>'88', 'category'=>'2','logo_url'=>'https://s3.amazonaws.com/billericons/88.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Number with STD Code (without 0)', 'param'=>'accountNumber', 'sample'=>'8026795507', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Account Number', 'param'=>'param', 'sample'=>'9034244324', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Service type', 'param'=>'param1', 'sample'=>'LLI/LLC', 'regex'=>'^[A-Z]{3}$','bill_fetch'=>true,'input'=>'list'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '51'=>array('Name'=>'Mahanagar Gas Limited', 'product_id'=>'51', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/51.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'CA Number', 'param'=>'accountNumber', 'sample'=>'210000421146', 'regex'=>'^21[0-9]{10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Bill Group Number', 'param'=>'param', 'sample'=>'123456', 'regex'=>'^[0-9a-zA-Z]{1,15}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '85'=>array('Name'=>'Indraprasth Gas', 'product_id'=>'85', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/85.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'BP Number', 'param'=>'accountNumber', 'sample'=>'4000375990', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '86'=>array('Name'=>'Gujarat Gas', 'product_id'=>'86', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/86.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Customer ID', 'param'=>'accountNumber', 'sample'=>'500000437311', 'regex'=>'^[0-9]{1,15}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '87'=>array('Name'=>'Adani Gas', 'product_id'=>'87', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/87.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Customer ID', 'param'=>'accountNumber', 'sample'=>'1000043948', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'State', 'param'=>'param', 'sample'=>'Gujarat/Haryana/Other', 'regex'=>'^[a-zA-Z]{1,10}$','bill_fetch'=>true,'input'=>'list'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '107'=>array('Name'=>'Sabarmati Gas Limited', 'product_id'=>'107', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/107.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Customer ID', 'param'=>'accountNumber', 'sample'=>'1000043948', 'regex'=>'^[0-9]{12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '108'=>array('Name'=>'Siti Energy', 'product_id'=>'108', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/108.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'ARN Number', 'param'=>'accountNumber', 'sample'=>'1000043948', 'regex'=>'^[0-9]{7,9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '109'=>array('Name'=>'Tripura Natural Gas', 'product_id'=>'109', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/109.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'1000043948', 'regex'=>'^[0-9]{1,20}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '111'=>array('Name'=>'Delhi Jal Board', 'product_id'=>'111', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/111.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'K No', 'param'=>'accountNumber', 'sample'=>'1000043948', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '112'=>array('Name'=>'Municipal Corporation of Gurugram', 'product_id'=>'112', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/112.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'K No', 'param'=>'accountNumber', 'sample'=>'1000043948', 'regex'=>'^[0-9a-zA-Z]{7,20}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '113'=>array('Name'=>'Urban Improvement Trust (UIT) - Bhiwadi', 'product_id'=>'113', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/113.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Customer ID', 'param'=>'accountNumber', 'sample'=>'1000043948', 'regex'=>'^[0-9]{3,20}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '114'=>array('Name'=>'Uttarakhand Jal Sansthan', 'product_id'=>'114', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/114.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer Number (Last 7 Digits)', 'param'=>'accountNumber', 'sample'=>'1000043948', 'regex'=>'^[0-9]{7,22}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '133'=>array('Name'=>'Haryana City Gas', 'product_id'=>'133', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/133.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'CRN Number', 'param'=>'accountNumber', 'sample'=>'1000043948', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),       
        '134'=>array('Name'=>'WESCO Utility', 'product_id'=>'134', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/134.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>false, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'1000043948', 'regex'=>'^[0-9]{12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '135'=>array('Name'=>'BSNL', 'product_id'=>'135', 'category'=>'2','logo_url'=>'https://s3.amazonaws.com/billericons/135.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Landline Number', 'param'=>'accountNumber', 'sample'=>'2012345678', 'regex'=>'^[1-9]{1}[0-9]{1,9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Service type', 'param'=>'param', 'sample'=>'LLI/LLC', 'regex'=>'^LLI | LLC$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Account Number', 'param'=>'param', 'sample'=>'2073152969', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '136'=>array('Name'=>'Dakshin Gujarat Vij Company Limited', 'product_id'=>'136', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/136.png', 'bill_fetch'=>false, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'156021112', 'regex'=>'^[0-9]{5,11}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '137'=>array('Name'=>'DNH Power Distribution Company Limited', 'product_id'=>'137', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/137.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Service Connection Number', 'param'=>'accountNumber', 'sample'=>'156021112', 'regex'=>'^[\/0-9a-zA-Z]{1,20}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '138'=>array('Name'=>'Madhya Gujarat Vij Company Limited', 'product_id'=>'138', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/138.png', 'bill_fetch'=>false, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'156021112', 'regex'=>'^[0-9]{5,11}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '139'=>array('Name'=>'Paschim Gujarat Vij Company Limited', 'product_id'=>'139', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/139.png', 'bill_fetch'=>false, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'156021112', 'regex'=>'^[0-9]{5,11}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '140'=>array('Name'=>'Uttar Gujarat Vij Company Limited', 'product_id'=>'140', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/140.png', 'bill_fetch'=>false, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'156021112', 'regex'=>'^[0-9]{5,11}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '141'=>array('Name'=>'Connect Broadband', 'product_id'=>'141', 'category'=>'2','logo_url'=>'https://s3.amazonaws.com/billericons/141.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Directory Number', 'param'=>'accountNumber', 'sample'=>'156021112', 'regex'=>'^[0-9a-zA-Z]{4,11}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '142'=>array('Name'=>'Madhya Pradesh Poorv Kshetra Vidyut Vitaran Urban', 'product_id'=>'142', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/142.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Customer Number', 'param'=>'accountNumber', 'sample'=>'3962734000', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '143'=>array('Name'=>'Tamil Nadu Electricity Board (TNEB)', 'product_id'=>'143', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/143.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'3962734000', 'regex'=>'^[0-9a-zA-Z]{9,12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '144'=>array('Name'=>'Uttar Pradesh Power Corp Ltd (UPPCL) - RURAL', 'product_id'=>'144', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/144.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'396273422000', 'regex'=>'^[0-9]{12}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '145'=>array('Name'=>'Vadodara Gas Limited', 'product_id'=>'145', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/145.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'1000043', 'regex'=>'^[0-9]{7}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),                       
        '146'=>array('Name'=>'Unique Central Piped Gases Pvt Ltd', 'product_id'=>'146', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/146.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true,
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Customer No', 'param'=>'accountNumber', 'sample'=>'10000432', 'regex'=>'^[0-9a-zA-Z]{8}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '147'=>array('Name'=>'Uttar Haryana Bijli Vitran Nigam (UHBVN)', 'product_id'=>'147', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/147.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Account Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9a-zA-Z]{10,12}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '148'=>array('Name'=>'Dakshin Haryana Bijli Vitran Nigam (DHBVN)', 'product_id'=>'148', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/148.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Account Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9a-zA-Z]{9,12}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '149'=>array('Name'=>'Punjab State Power Corporation Ltd (PSPCL)', 'product_id'=>'149', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/149.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>true, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Account Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9a-zA-Z]{10,12}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '150'=>array('Name'=>'Jharkhand Bijli Vitran Nigam Limited (JBVNL)', 'product_id'=>'150', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/150.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9a-zA-Z]{3,15}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Subdivision Code', 'param'=>'param', 'sample'=>'Tati silwai/Ormanjhi/Bundu/Lalpur/Kokar/RMCH/Upper bazar/kanke/Main Road/Harmu/Ashok Nagar/Doranda/HEC/Tupudana/Ratu Chatti/Mander/Ratu Road/Khunti/Torpa/Gumla/Simdega/Kolebira/Lohardagga/Kuru/Daltonganj (U)/Chatarpur/Japla/Latehar/Barwahdih/Garwah- I/Garwah- II (Ranka)/NagarUtari/JSR(Karandih)/Mango/Jugsalai/Chota Govindpur/Adityapur-I/Adityapur-II/Ghatshila/Dhalbhugarh/Chakulia-I/Jadugora/Chaibasa (U)/Chaibasa (R)/Nowamundi/Sarikela/Rajkharshawan/Chandil/Chakradhapur/Manoharpur/Hazaribag(U)/Hazaribag(R)/Barhi/Chouparan/Chatra(N)/Chatra(S)/Ramgarh/Gola/Kujju/Bhurkunda/Giridih(U)/Giridih(R)/Dumri/Tisri/Jamua/Rajdhanwar/Koderma/Jumritilliya/Domchanch/Jamtara/Mihijam/Basukinath/Dumka(U)/Dumka(R)/Sahebganj/Barharwa/Rajmahal/Pakur/Amrapara/Deoghar/sarath/Madupur/Jassidih/Godda/Mahagama/Hirapur/Nayabazar/Karkend/Nirsa-1/Nirsa-2/Chirkunda/Gobindpur/Barwada/Tundi/Jharia/Mukunda/Diguadih/Sindri/Loyabad (Ganeshpur)/Kartras/Chas(U)/Chas(R)/Chandankiyari/Gomo/Gomia(Kathara)/Bermo(Fusro)/Jainamore/Kamdara/Ghaghra/Karandih/Topchanchi/Barhet/Daltonganj(R)/Kathara', 'regex'=>'^[0-9a-zA-Z ]{1,20}$','bill_fetch'=>true,'input'=>'list'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '151'=>array('Name'=>'Assam Power Distribution Company Ltd (NON-RAPDR) Rural', 'product_id'=>'151', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/151.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer ID', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{12}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '152'=>array('Name'=>'Chamundeshwari Electricity Supply Corp Ltd (CESCOM)', 'product_id'=>'152', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/152.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Account ID (RAPDRP) OR Consumer Number / Connection ID (Non-RAPDRP)', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9a-zA-Z]{10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '153'=>array('Name'=>'Hubli Electricity Supply Company Ltd (HESCOM)', 'product_id'=>'153', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/153.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Account ID (RAPDRP) OR Consumer Number / Connection ID (Non-RAPDRP)', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{5,10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '193'=>array('Name'=>'Hyderabad Metropolitan Water Supply and Sewerage Board', 'product_id'=>'193', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/193.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'CAN Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9a-zA-Z]{2,25}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '194'=>array('Name'=>'Himachal Pradesh State Electricity Board', 'product_id'=>'194', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/194.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'K Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{10,12}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '195'=>array('Name'=>'Charotar Gas Sahakari Mandali Ltd', 'product_id'=>'195', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/195.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Customer Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{1,5}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '196'=>array('Name'=>'Aavantika Gas Ltd.', 'product_id'=>'196', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/196.png', 'bill_fetch'=>true, 'amount_change'=>true, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Customer No', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9a-zA-Z]{10,15}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Address', 'param'=>'param', 'sample'=>'Mumbai 97', 'regex'=>'^[0-9a-zA-Z]$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '197'=>array('Name'=>'Bhopal Municipal Corporation - Water', 'product_id'=>'197', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/197.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Connection ID', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9a-zA-Z]{8,10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '198'=>array('Name'=>'Gwalior Municipal Corporation - Water', 'product_id'=>'198', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/198.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Connection ID', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{1,8}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '199'=>array('Name'=>'Indore Municipal Corporation - Water', 'product_id'=>'199', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/199.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Service Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{6,15}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '200'=>array('Name'=>'Indian Oil-Adani Gas Private Limited', 'product_id'=>'200', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/200.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Customer ID', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '201'=>array('Name'=>'Jabalpur Municipal Corporation - Water', 'product_id'=>'201', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/201.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Service Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{6,15}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '202'=>array('Name'=>'Municipal Corporation Jalandhar', 'product_id'=>'202', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/202.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Account No', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{1,9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '203'=>array('Name'=>'Municipal Corporation Ludhiana - Water', 'product_id'=>'203', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/203.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{1,10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '204'=>array('Name'=>'Maharashtra Natural Gas Limited (MNGL)', 'product_id'=>'204', 'category'=>'3','logo_url'=>'https://s3.amazonaws.com/billericons/204.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'BP No', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{7,10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '205'=>array('Name'=>'M.P. Madhya Kshetra Vidyut Vitaran - RURAL', 'product_id'=>'205', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/205.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'IVRS', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{7,15}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '206'=>array('Name'=>'M.P. Poorv Kshetra Vidyut Vitaran - RURAL', 'product_id'=>'206', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/206.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number/IVRS', 'param'=>'accountNumber', 'sample'=>'310163016533', 'regex'=>'^[0-9]{7,13}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '207'=>array('Name'=>'Sikkim Power - RURAL', 'product_id'=>'207', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/207.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Contract Acc Number', 'param'=>'accountNumber', 'sample'=>'310163016', 'regex'=>'^[0-9]{1,9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '208'=>array('Name'=>'Surat Municipal Corporation - Water', 'product_id'=>'208', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/208.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Connection Number', 'param'=>'accountNumber', 'sample'=>'310163016', 'regex'=>'^[0-9a-zA-Z]{1,20}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '209'=>array('Name'=>'Tata Docomo CDMA Landline', 'product_id'=>'209', 'category'=>'2','logo_url'=>'https://s3.amazonaws.com/billericons/209.png', 'bill_fetch'=>false, 'amount_change'=>true, 'after_due_date'=>true, 'bbps_flag'=>false, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Landline Number with STD Code (without 0)', 'param'=>'accountNumber', 'sample'=>'02240039296', 'regex'=>'^[0-9]{10}$','bill_fetch'=>true,'input'=>'text'),
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>true,'input'=>'text'))),
        '210'=>array('Name'=>'West Bengal State Electricity Distribution Co. Ltd (WBSEDCL)', 'product_id'=>'210', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/210.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer ID', 'param'=>'accountNumber', 'sample'=>'310163016', 'regex'=>'^[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '211'=>array('Name'=>'New Delhi Municipal Council (NDMC) - Water', 'product_id'=>'211', 'category'=>'4','logo_url'=>'https://s3.amazonaws.com/billericons/211.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'310163016', 'regex'=>'^[0-9]{7,10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
        '212'=>array('Name'=>'New Delhi Municipal Council (NDMC) - Electricity', 'product_id'=>'212', 'category'=>'1','logo_url'=>'https://s3.amazonaws.com/billericons/212.png', 'bill_fetch'=>true, 'amount_change'=>false, 'after_due_date'=>false, 'bbps_flag'=>true, 
                'fields'=>array(
                        array('label'=>'Mobile Number', 'param'=>'mobileNumber', 'sample'=>'9819829102', 'regex'=>'^[7-9]{1}[0-9]{9}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Consumer Number', 'param'=>'accountNumber', 'sample'=>'310163016', 'regex'=>'^[0-9]{7,10}$','bill_fetch'=>true,'input'=>'text'), 
                        array('label'=>'Amount', 'param'=>'amount', 'sample'=>'408', 'regex'=>'^[0-9]+(\.[0-9]{1,2})?$','bill_fetch'=>false,'input'=>'text'))),
);

$config['jharkhand_subdiv_codes'] = array('Tati silwai'=>'1','Ormanjhi'=>'2','Bundu'=>'3','Lalpur'=>'4','Kokar'=>'5','RMCH'=>'6','Upper bazar'=>'7','kanke'=>'8','Main Road'=>'9','Harmu'=>'10','Ashok Nagar'=>'11','Doranda'=>'12','HEC'=>'13','Tupudana'=>'14','Ratu Chatti'=>'15','Mander'=>'16','Ratu Road'=>'17','Khunti'=>'18','Torpa'=>'19','Gumla'=>'20','Simdega'=>'21','Kolebira'=>'22','Lohardagga'=>'23','Kuru'=>'24','Daltonganj (U)'=>'25','Chatarpur'=>'26','Japla'=>'27','Latehar'=>'28','Barwahdih'=>'29','Garwah- I'=>'30','Garwah- II (Ranka)'=>'31','NagarUtari'=>'32','JSR(Karandih)'=>'33','Mango'=>'34','Jugsalai'=>'35','Chota Govindpur'=>'36','Adityapur-I'=>'37','Adityapur-II'=>'38','Ghatshila'=>'39','Dhalbhugarh'=>'40','Chakulia-I'=>'41','Jadugora'=>'42','Chaibasa (U)'=>'43','Chaibasa (R)'=>'44','Nowamundi'=>'45','Sarikela'=>'46','Rajkharshawan'=>'47','Chandil'=>'48','Chakradhapur'=>'49','Manoharpur'=>'50','Hazaribag(U)'=>'51','Hazaribag(R)'=>'52','Barhi'=>'53','Chouparan'=>'54','Chatra(N)'=>'55','Chatra(S)'=>'56','Ramgarh'=>'57','Gola'=>'58','Kujju'=>'59','Bhurkunda'=>'60','Giridih(U)'=>'61','Giridih(R)'=>'62','Dumri'=>'63','Tisri'=>'64','Jamua'=>'65','Rajdhanwar'=>'66','Koderma'=>'67','Jumritilliya'=>'68','Domchanch'=>'69','Jamtara'=>'70','Mihijam'=>'71','Basukinath'=>'72','Dumka(U)'=>'73','Dumka(R)'=>'74','Sahebganj'=>'75','Barharwa'=>'76','Rajmahal'=>'77','Pakur'=>'78','Amrapara'=>'79','Deoghar'=>'80','sarath'=>'81','Madupur'=>'82','Jassidih'=>'83','Godda'=>'84','Mahagama'=>'85','Hirapur'=>'86','Nayabazar'=>'87','Karkend'=>'88','Nirsa-1'=>'89','Nirsa-2'=>'90','Chirkunda'=>'91','Gobindpur'=>'92','Barwada'=>'93','Tundi'=>'94','Jharia'=>'95','Mukunda'=>'96','Diguadih'=>'97','Sindri'=>'98','Loyabad (Ganeshpur)'=>'99','Kartras'=>'100','Chas(U)'=>'101','Chas(R)'=>'102','Chandankiyari'=>'103','Gomo'=>'104','Gomia(Kathara)'=>'105','Bermo(Fusro)'=>'106','Jainamore'=>'107','Kamdara'=>'108','Ghaghra'=>'109','Karandih'=>'110','Topchanchi'=>'111','Barhet'=>'112','Daltonganj(R)'=>'113','Kathara'=>'114');

?>