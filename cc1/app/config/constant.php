	<?php

	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	putenv('TZ=Asia/Calcutta');
	define('CONFIG_PATH',__DIR__);

	define('SERVER_IP',trim(file_get_contents(CONFIG_PATH.'/server_ip.conf')));
	define('MEMCACHE_IP',trim(file_get_contents(CONFIG_PATH.'/memcache_host.conf')));
	define('MEMCACHE_MASTER',trim(file_get_contents(CONFIG_PATH.'/memcache_master.conf')));
	define('DB_HOST',trim(file_get_contents(CONFIG_PATH.'/db_host.conf')));
	define('TPS_REDIS',trim(file_get_contents(CONFIG_PATH.'/tps_redis_host.conf')));
	define('REDIS_HOST',trim(file_get_contents(CONFIG_PATH.'/sms_n_mail_redis_host.conf')));


	define('SMS_FLAG','1');
	define('MAIL_FLAG','1');
	define('TAX_MODEL','1');//1 for discount model & 0 for service tax model

	//SERVICE down flag
	define('SMS_SERVICE',1);
	define('USSD_SERVICE',1);
	define('APP_WEB_SERVICE',1);
	define('API_SERVICE',1);

	define('KYC_AMOUNT',50000);
	define('KYC_AMOUNT_MAX',200000);
	define('PAGE_LIMIT',10);

	/* Version Numbers */
	define('STYLE_CSS_VERSION','919');
	define('STYLE_CSS_IE_VERSION','998');
	define('M_STYLE_CSS_VERSION','981');
	define('RETAIL_STYLE_CSS_VERSION','9321');
	define('SCRIPT_APP_JS_VERSION','1181117'); //script & app
	define('MERGE_JS_VERSION','980'); //prototype, effects, carousel, dpEncodeRequest
	define('MERGE_1_JS_VERSION','980'); //scriptaculous, calendar, controls

	//---- s3 credentials
	define('awsAccessKey', 'AKIAIP36TMKRJCTDEYVQ');
	define('awsSecretKey', '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');

//	define('s3kycBucket','stagingpay1bucket');
//	define('s3limitBucket','stagingpay1limits');
//	define('s3MarketingBucket','stagingpay1marketing');
	define('invoicebucket','pay1invoice');
        define('s3communityBucket','pay1-pcampaign');



	define('DISTRIBUTOR_APP_FILE_1','pay1.jar');
	define('RETAILER_APP_FILE_1','pay1.apk');
	define('RETAILER_APP_FILE_2','pay1.jad');
	define('PLAY_STORE_APP_URL','https://play.google.com/store/apps/details?id=com.mindsarray.pay1');
	define('PAY1_APP_URL','https://panel.pay1.in/apis/downloadApp/');

	define('MEMBER','1');
	define('ADMIN','2');
        define('SUPER_DISTRIBUTOR','3');
	define('MASTER_DISTRIBUTOR','4');
	define('DISTRIBUTOR','5');
	define('RETAILER','6');
	define('CUSTCARE','7');
	define('RELATIONSHIP_MANAGER','8');
	define('VENDOR','9');
	define('ACCOUNTS','10');
	define('SALESMAN','11');
	define('INVENTORY_ADMIN', '12');
	define('INVENTORY_MEMBER', '13');
	define('INVENTORY_EDITOR', '14');
	define('CHANNEL_SALES', '15');
	define('BACKEND_MODEM_MANAGEMENT', '16');
	define('LIMITS', '18');
	define('CUSTOMER_CARE_SUPPORT', '19');
	define('CHANNEL_OPERTAION', '20');
	define('BACKEND_OPERATION_EXPERT', '21');
	define('RETENTION', '22');
	define('MARKETING', '23');
	define('BACKEND_ADMIN', '24');
	define('SUPER_ADMIN', '25');
	define('HR', '26');
	define('QUALITY_ANALYST', '27');
	define('TECHNOLOGY', '29');
	define('SYSTEM_ADMIN', '30');
	define('STRATEGIC_ALLIANCE', '31');
	define('LENDER', '33');
	define('BORROWER', '34');
	define('LIMIT', '38');
	define('LEAD_OPERATIONS', '45');


	/* User Registration Types */
	define('ONLINE_REG',1);
	define('MISSCALL_REG',2);
	define('RETAILER_REG',3);

	//Our SDs & Ds
	define('MDISTS','3');
	define('DISTS','1,3,18,29,87');
	define('DISTS_UID','8,43,349448,395710,633650');
	define('WHITELIST_USERS','1,8,55');
	define('SAAS_DISTS','1883,2120');
	define('SAAS_VENDORS','116,141,148,159');

	define('TIME_DURATION',720);

	//Retailer Transaction Types
	define('ADMIN_TRANSFER','0');//ref1 is admin_id & ref2 is master distributor id
	define('MDIST_DIST_BALANCE_TRANSFER','1');//ref1 is masterdistributor id and ref2 is distributor id
	define('DIST_RETL_BALANCE_TRANSFER','2');//ref1 is distributor id and ref2 is retailer id
	define('DISTRIBUTOR_ACTIVATION','3');//ref1 is distributor id and ref2 is retailersCouponids seperated by commas
	define('RETAILER_ACTIVATION','4');//ref1 is retailer id and ref2 is product id, confirm_flag = 1 is success
	define('COMMISSION_MASTERDISTRIBUTOR','5');//ref1 is masterdistributor id and ref2 is parent id
	define('COMMISSION_DISTRIBUTOR','6');//ref1 is distributor id and ref2 is parent id
	define('COMMISSION_RETAILER','7');//ref1 is retailer id and ref2 is parent id
	define('TDS_MASTERDISTRIBUTOR','8');//ref1 is masterdistributor id and ref2 is parent id
	define('TDS_DISTRIBUTOR','9');//ref1 is distributor id and ref2 is parent id
	define('TDS_RETAILER','10');//ref1 is retailer id and ref2 is parent id
	define('REVERSAL_RETAILER','11');//ref1 is retailer id and ref2 is parent id
	define('REVERSAL_DISTRIBUTOR','12');//ref1 is master / superdistributor id and ref2 is parent id
	define('REVERSAL_MASTERDISTRIBUTOR','13');//ref1 is distributor id and ref2 is parent id

	define('DEBIT_NOTE','16');//ref1 is user_id and ref2 is product_id, user_id is service_id confirm_flag = 0 means success if 1 means reversed, type_flag = 0 means full settlement, 1 means no settlement, 2 means partial settlement
	define('CREDIT_NOTE','17');//ref1 is user_id and ref2 is product_id, user_id is service_id confirm_flag = 0 means success if 1 means reversed, type_flag = 0 means settled in wallet, 1 means no settlement, 2 means partial settlement
	define('SETUP_FEE','18');//ref1 is dist id and ref2 is ret id
	define('REFUND','19');//ref1 is userid and ref2 is groupid
	define('RENTAL','20');//ref1 is userid
	define('PULLBACK_RETAILER','21');//ref1 is retid and ref2 is parent_id and user_id is user_id of distributor
	define('PULLBACK_DISTRIBUTOR','22');//ref1 is distributor id and ref2 is parent_id and user_id is user_id of super distributor
	define('PULLBACK_MASTERDISTRIBUTOR','23');//ref1 is masterdistributor id and ref2 is parent_id and user_id is user_id of user/admin
	define('SERVICE_CHARGE','24');//ref1 is retid/distid/superdistid and ref2 is groupid and user_id is parent_id

	define('DIST_SLMN_BALANCE_TRANSFER','25');//ref1 is distributor id and ref2 is salesman id
	define('SLMN_RETL_BALANCE_TRANSFER','26');//ref1 is salesman id and ref2 is retailer id
	define('PULLBACK_SALESMAN','27');//ref1 is salesmenid and ref2 is shop_transaction_id

	define('COMMISSION','28');//ref1 is user_id and ref2 is parent id
	define('SERVICECHARGES','29');//ref1 is user_id and ref2 is parent_id
	define('SERVICE_TAX','30');//ref1 is user_id and ref2 is parent_id
	define('TDS','31');//ref1 is user_id and ref2 is parent_id
	define('VOID_TXN','32');//ref1 is user_id and ref2 is parent_id
	define('WALLET_TRANSFER','33');//ref1 is from user_id and ref2 is to user_id
	define('WALLET_TRANSFER_REVERSED','34');//ref1 is to user_id and ref2 is parent_id & user_id is from user_id
	define('KITCHARGE','35');//ref1 is for user_id and user_id is for service_id
        define('SECURITY_DEPOSIT','36');//source_id is for user_id and user_id is for service_id
        define('ONE_TIME_CHARGE','37');//source_id is for user_id
        define('COMMISSION_DISTRIBUTOR_REVERSE','38');//source_id is distributor id
        define('TXN_REVERSE','39');//source_id is user_id, target_id is ref shop_txn_id, type_flag = type of that txn, user_id is service_id
        define('TXN_CANCEL_REFUND','40');//source_id is user_id, target_id is ref shop_txn_id, user_id is service_id // this is for partial refunds

        define('MDIST_SDIST_BALANCE_TRANSFER','41');//source_id is masterdistributor id, target_id is superdistributor id
        define('SDIST_DIST_BALANCE_TRANSFER','42');//source_id is superdistributor id, target_id is distributor id
        define('COMMISSION_SUPERDISTRIBUTOR','43');//source_id is superdistributor id, target_id is parent id
        define('PULLBACK_SUPERDISTRIBUTOR','44');//source_id is superdistributor id, target_id is parent id

        define('DISH_TV_POINTS',0);
	//Calculation Constants
	define('TDS_PERCENT','5');
	define('SERVICE_TAX_PERCENT','18');

	//Transaction status
	define('PENDING',0);
	define('TRANS_SUCCESS',1);
	define('TRANS_FAIL',2);
	define('TRANS_REVERSE',3);
	define('TRANS_REVERSE_PENDING',4);
	define('TRANS_REVERSE_DECLINE',5);


	//payment modes
	define('MODE_CASH',1);
	define('MODE_CHEQUE',2);
	define('MODE_NEFT',3);
	define('MODE_DD',4);
	define('MODE_PG',5);

	//payment types
	define('TYPE_SETUP',1);
	define('TYPE_TOPUP',2);


	/* App types*/
	define('APP_JAVA',1);
	define('APP_ANDROID',2);
	define('APP_SMS',3);
	define('APP_USSD',4);


	/* B2C Parameters */
	define('B2C_RETAILER','13'); //retailer id of b2c
	define('WALLET_ID','44');
	define('WALLET_VENDOR','22');

	/*Services*/
	define('RECHARGE','1');
	define('UTILITY','4');
	define('DMT','12');


	if(class_exists('Configure')){
	    Configure::write('recharge_method',array(0 => 'Default', 1 => 'App', 2 => 'SMS', 3 => 'Ussd',4 => 'Web', 5 => 'NA'));
	    Configure::write('transaction_status', array(0 => 'Success', 1 => 'Success', 2 => 'Reversed', 3 => 'Reversed', 4 => 'Complaint taken', 5 => 'Success'));
	    Configure::write('action_type', array('Open browser with link', 'Go to wholesale section', 'Go to Contact Details', 'Go to C2D payments', 'Go to Wallet Section', 'Go to Change Password', 'Open Video in You tube', 'Close','Wholesaler Notification'));
	    Configure::write('blocksmsrecepients',array('8082011232','8652066106','7710976244','8652066534','7710961460','7039204715','9833762887'));

	    Configure::write('service_type',array(
            8  =>array('MPOS Withdrawal : Non VISA'=>57,'MPOS Withdrawal : VISA' => 159,'Sale - CC'=>56,'Sale - CC : EMI' => 161,'Sale - DC'=>72),
            9  =>array('UPI'=>77),
            10 =>array('AEPS Withdrawal'=>73,'AEPS Deposit' => 74,'Balance Enquiry' => 78)
        ));
	    Configure::write('device_type',array(
            8   => array(1=>'Payswiff - GPRS',2=>'Payswiff - Basic'),
            10  => array(3=>'Mantra',4=>'Morpho',5 => 'Startek',6 => 'Secugen')
        ));
	    Configure::write('vendor',array(
            // 8   => array(1 => 'Ezetap',2 => 'Paynear'),
            10  => array(1 => 'RBL',2 => 'FingPay')
        ));

	    Configure::write('services', array(8 => 'MPOS',9 => 'UPI',  10 => 'AEPS',11=>'SMARTCAPITAL',12=>'DMT',13=>'SHOP1'));
	    Configure::write('primary_services', array(1,2,3,4,5,6,7,8,9,10));
	    Configure::write('plans',array(
            8 =>array(
                'plan_a' => 'Basic - Rs. 999',
                'plan_b' => 'Standard - Rs. 1999',
                'plan_c' => 'Supreme - Rs. 3499',
                'plan_e' => 'Supreme - Rs. 3499(1)',
                'plan_d' => 'Prime - Rs. 5999',
                'plan_f' => 'Prime - Rs. 5999(1)',

                'plan_g' => 'Standard - Rs. 2360',
                'plan_h' => 'Supreme - Rs. 4130',
                'plan_i' => 'Prime - Rs. 7080',
                'plan_j' => 'New Plan - Rs. 999(2.0)',
                'plan_k' => 'New Plan - Rs. 1500',
                'plan_l' => 'New Plan - Rs. 3540',
                'plan_m' => 'New Plan - Rs. 999(3.0)',
                'plan_n' => 'New Plan - Rs. 3500',
                'plan_o' => 'New Plan - Rs. 1500(2.0)'
            ),
            10 =>array(
                'plan_a' => 'Basic - Rs. 299',
                'plan_b' => 'Basic - Rs. 499',
                // 'plan_b' => 'Standard - Rs. 2999',
                'plan_c' => 'Supreme - Rs. 3299'
                // 'plan_d' => 'Special 1 - Rs. 600',
                // 'plan_e' => 'Special 2 - Rs. 1000'
            )

        ));

	    Configure::write('distPlanCharges',array(


            8 => array(
                'plan_a' => 0,
                'plan_b' => 1000,
                'plan_c' => 2500,
                'plan_d' => 5000,
                'plan_e' => 2500,
                'plan_f' => 5000,

                'plan_g' => 1361,
                'plan_h' => 3131,
                'plan_i' => 6081,
                'plan_j' => 0,
                'plan_k' => 501,
                'plan_l' => 2541,
                'plan_m' => 0,
                'plan_n' => 2501,
                'plan_o' => 501
            ),
            10 => array(
                'plan_a' => 199,
                'plan_b' => 399,
                'plan_c' => 2949
                // 'plan_d' => 0,
                // 'plan_e' => 0
            )

        ));


	    Configure::write('retPlanCharges',array(


            8 => array(
                'plan_a' => 999,
                'plan_b' => 1999,
                'plan_c' => 3499,
                'plan_d' => 5999,
                'plan_e' => 3499,
                'plan_f' => 5999,

                'plan_g' => 2360,
                'plan_h' => 4130,
                'plan_i' => 7080,
                'plan_j' => 999,
                'plan_k' => 1500,
                'plan_l' => 3540,
                'plan_m' => 999,
                'plan_n' => 3500,
                'plan_o' => 1500
            ),
            10 => array(
                'plan_a' => 299,
                'plan_b' => 499,
                'plan_c' => 3299
                // 'plan_d' => 600,
                // 'plan_e' => 1000
            )
        ));

        Configure::write('distDeactivatePlanCharges',array(
            8  => 750,
            10 => 1649
        ));
        Configure::write('distDeactivatePlanDiscountCharges',array(
            8  => 0,
            10 => 0
        ));
	    Configure::write('service_fields',array(
	       8 => array(
		   'kit_flag' => array(
		       'label' => 'Kit Flag',
		       'type' => 'checkbox'
		   ),
		   'service_flag' => array(
		       'label' => 'Service Flag',
		       'type' => 'checkbox'
		   ),
		   'device_id' => array(
		       'label' => 'Device Id',
		       'type' => 'text',
		    //    'validation' => 'unique|require'
		       'validation' => 'unique'
		   ),
		   'tid' => array(
		       'label' => 'TID',
		       'type' => 'text',
		       'validation' => 'unique'
		   ),
		   'plan' => array(
		       'label' => 'Plan',
		       'type' => 'dropdown',
		       'default_values' => array(
                'plan_a' => 'Basic - Rs. 999',
                'plan_b' => 'Standard - Rs. 1999',
                'plan_c' => 'Supreme - Rs. 3499',
                'plan_e' => 'Supreme - Rs. 3499(1)',
                'plan_d' => 'Prime - Rs. 5999',
                'plan_f' => 'Prime - Rs. 5999(1)',

                'plan_g' => 'Standard - Rs. 2360',
                'plan_h' => 'Supreme - Rs. 4130',
                'plan_i' => 'Prime - Rs. 7080',
                'plan_j' => 'New Plan - Rs. 999(2.0)',
                'plan_k' => 'New Plan - Rs. 1500',
                'plan_l' => 'New Plan - Rs. 3540',
                'plan_m' => 'New Plan - Rs. 999(3.0)',
                'plan_n' => 'New Plan - Rs. 3500',
                'plan_o' => 'New Plan - Rs. 1500(2.0)'
		       ),
		       'validation' => 'require'
            ),
            'payment_mode' => array(
		       'label' => 'Payment Mode',
		       'type' => 'dropdown',
		       'default_values' => array(
                    // 1 => 'Distributor Wallet',
                    2 => 'Retailer Wallet',
                    3 => 'Payment done from Insta Mojo',
                    4 => 'Distributor Kit'
                ),
		       'validation' => 'require'
            ),
            'settlement_options' => array(
		       'label' => 'Settlement',
		       'type' => 'dropdown',
		       'default_values' => array(
                    2 => 'Default',
                    0 => 'Wallet',
                    1 => 'Bank'
                ),
		       'validation' => ''
            )
	       ),
	       9 => array(
		   'kit_flag' => array(
		       'label' => 'Kit Flag',
		       'type' => 'checkbox'
		   ),
		   'service_flag' => array(
		       'label' => 'Service Flag',
		       'type' => 'checkbox'
		   )
	       ),
	       10 => array(
		   'kit_flag' => array(
		       'label' => 'Kit Flag',
		       'type' => 'checkbox'
		   ),
		   'service_flag' => array(
		       'label' => 'Service Flag',
		       'type' => 'checkbox'
            ),
            'imei_no' => array(
		       'label' => 'IMEI No',
		       'type' => 'text',
		       'validation' => 'unique'
		   ),
		   'device_id' => array(
		       'label' => 'Device Id',
		       'type' => 'text',
		       'validation' => 'unique'
		   ),
           'device_type' => array(
		       'label' => 'Device Type',
		       'type' => 'dropdown',
                'default_values' => Configure::read('device_type.10')
            ),
            'vendor' => array(
		       'label' => 'Vendor',
		       'type' => 'dropdown',
                'default_values' => Configure::read('vendor.10')
            ),
		   'rbl_device_id' => array(
		       'label' => 'RBL Device Id',
		       'type' => 'text',
		       'validation' => 'unique'
		   ),
		   'csp_id' => array(
		       'label' => 'Min Id',
		       'type' => 'text',
		       'validation' => ''
		   ),
		//    'csp_pass' => array(
		//        'label' => 'CSR Password',
		//        'type' => 'text',
		//        'validation' => ''
		//    ),
		   'plan' => array(
		       'label' => 'Plan',
		       'type' => 'dropdown',
                'default_values' => array(
                    'plan_a' => 'Basic - Rs. 299',
                    'plan_b' => 'Basic - Rs. 499',
                    'plan_c' => 'Supreme - Rs. 3299',
                    // 'plan_d' => 'Special 1 - Rs. 600',
                    // 'plan_e' => 'Special 2 - Rs. 1000'
                ),
		       'validation' => 'require'
            ),
            'payment_mode' => array(
                'label' => 'Payment Mode',
                'type' => 'dropdown',
                'default_values' => array(
                        // 1 => 'Distributor Wallet',
                        2 => 'Retailer Wallet',
                        3 => 'Payment done from Insta Mojo',
                        4 => 'Distributor Kit'
                    ),
                'validation' => 'require'
            ),
            'settlement_options' => array(
		       'label' => 'Settlement',
		       'type' => 'dropdown',
		       'default_values' => array(
                    2 => 'Default',
                    0 => 'Wallet',
                    1 => 'Bank'
                ),
		       'validation' => ''
            )
	       ),
	       11 => array(
		   'kit_flag' => array(
		       'label' => 'Kit Flag',
		       'type' => 'checkbox'
		   ),
		   'service_flag' => array(
		       'label' => 'Service Flag',
		       'type' => 'checkbox'
		   )
	       ),
	       12 => array(
		   'kit_flag' => array(
		       'label' => 'Kit Flag',
		       'type' => 'checkbox'
		   ),
		   'service_flag' => array(
		       'label' => 'Service Flag',
		       'type' => 'checkbox'
		   ),
		   'bc_agent' => array(
		       'label' => 'BC Agent',
		       'type' => 'text',
		       'validation' => 'unique'
		   ),
                   'ret_margin' => array(
		       'label' => 'Retailer Margin',
		       'type' => 'text',
                       'validation' => 'require|numeric|min:0.4|max:0.7'
		   )
	       ),
               13 => array(
                   'kit_flag' => array(
		       'label' => 'Kit Flag',
		       'type' => 'checkbox'
		   ),
		   'service_flag' => array(
		       'label' => 'Service Flag',
		       'type' => 'checkbox'
		   ),
		   'limit' => array(
		       'label' => 'Credit Bal',
		       'type' => 'text',
                       'validation' => 'require|numeric'
		   ),
                   'used' => array(
		       'label' => 'Bal Used',
		       'type' => 'label'
		   )
	       )
	    ));

	    Configure::write('AutoDeclinedVendorIds',array('58','8','87','149','68','162','57','165','176','180','150','167','152'));
	    Configure::write('AutoDeclinedProductIds',array('3','34','30','31','7','8'));
	    Configure::write('invoiceDescriptions',array(1=>'Mobile Recharge Services',2=>'DTH Recharge Services',3=>'Entertainment',4=>'Postpaid Services',5=>'Pay1 Wallet',6=>'Utility Bill Payment Services',7=>'Cash PG',8=>'MPOS',9=>'UPI',10=>'AEPS',11=>'Microfinance',12=>'DMT Service Charges',13=>'Commission for Cash Collection',14=>'Incentives',15=>array(8=>'MPOS Setup Cost',10=>'AEPS Setup Cost')));
            Configure::write('gst_state_code_mapping',array(
                                                        '35' => 'Andaman and Nicobar Islands',
                                                        '28' => 'Andhra Pradesh',
                                                        '37' => 'Andhra Pradesh (New)',
                                                        '12' => 'Arunachal Pradesh',
                                                        '18' => 'Assam',
                                                        '10' => 'Bihar',
                                                        '04' => 'Chandigarh',
                                                        '22' => 'Chattisgarh',
                                                        '26' => 'Dadra and Nagar Haveli',
                                                        '25' => 'Daman and Diu',
                                                        '07' => 'Delhi',
                                                        '30' => 'Goa',
                                                        '24' => 'Gujarat',
                                                        '06' => 'Haryana',
                                                        '02' => 'Himachal Pradesh',
                                                        '01' => 'Jammu and Kashmir',
                                                        '20' => 'Jharkhand',
                                                        '29' => 'Karnataka',
                                                        '32' => 'Kerala',
                                                        '31' => 'Lakshadweep Islands',
                                                        '23' => 'Madhya Pradesh',
                                                        '27' => 'Maharashtra',
                                                        '14' => 'Manipur',
                                                        '17' => 'Meghalaya',
                                                        '15' => 'Mizoram',
                                                        '13' => 'Nagaland',
                                                        '21' => 'Odisha',
                                                        '34' => 'Pondicherry',
                                                        '03' => 'Punjab',
                                                        '08' => 'Rajasthan',
                                                        '11' => 'Sikkim',
                                                        '33' => 'Tamil Nadu',
                                                        '36' => 'Telangana',
                                                        '16' => 'Tripura',
                                                        '09' => 'Uttar Pradesh',
                                                        '05' => 'Uttarakhand',
                                                        '19' => 'West Bengal'
                                                    ));

                Configure::write('Remit_bank_status',array('rbl' => array(-1 => 'Pending', 0 => 'Failed', 1 => 'Success'),'eko' => array(0 => 'Success', 1 => 'Failed',2 => 'Response Awaited',3 => 'Refund Pending',4 => 'Refunded')));
                Configure::write('Remit_pay1_status',array(-1 => 'Pending', 0 => 'Failed', 1 => 'Success', 2 => 'Cart', 3 => 'Eligible for refund', 4 => 'Refund was done'));
                Configure::write('Pay1_products',array(1=>'Pay1 Merchant',2=>'Pay1 Swipe',3=>'Pay1 Remit',4=>'Pay1 Smartbuy'));
                Configure::write('products',array('8'=>'MPOS','10'=>'AEPS','11'=>'LOAN'));
                Configure::write('Travel_pay1_status',array(0 => 'Pending', 1 => 'Success', 2 => 'Confirmed',3 => 'Failed' ,5 => 'Refunded', 6 => 'Part Refunded'));
                Configure::write('rentalCharges',array(
                                    8 => array(
                                                'plan_a' => 299,
                                                'plan_b' => 199,
                                                'plan_c' => 99,
                                                'plan_d' => 0,
                                                'plan_e' => 99,
                                                'plan_f' => 0,

                                                'plan_g' => 352,
                                                'plan_h' => 117,
                                                'plan_i' => 0,
                                                'plan_j' => 352,
                                                'plan_k' => 400,
                                                'plan_l' => 117,
                                                'plan_m' => 400,
                                                'plan_n' => 360,
                                                'plan_o' => 400
                                            ),
                                    10 => array(
                                            'plan_a' => 299,
                                            'plan_b' => 199,
                                            'plan_c' => 99,
                                            'plan_d' => 0,
                                            'plan_e' => 99
                                        )
                                    )
                        );
                        Configure::write('SCHEME_DISTRIBUTOR_IDS',array(47203469));

                        Configure::write('kit_status', array(
                            '0' => 'Manual Deactivated',
                            '1' => 'Kit Purchased/Assigned',
                            '2' => 'Kit Refunded'
                        ));

                        Configure::write('service_status', array(
                            '0' => 'Manual Deactivated',
                            '1' => 'Service Active',
                            '2' => 'Deactivated (Rental)',
                            '3' => 'Kit Purchased',
                            '4' => 'Service Activation Request/Pending',
                            '5' => 'Request Rejected',
                            '6' => 'Vendor Verification Pending',
                            '7' => 'Vendor Verified'
                        ));
                        Configure::write('info_management_user_types',array('Retailer'=>6,'Distributor'=>5,'RM'=>8,'DMT'=>35,'Leads'=>45,'CC'=>19));
                        Configure::write('activation_team_user_list',array());
                        
                        //Dmt Credential

                        Configure::write('DMT_URLS',array('stag'=>'https://stagingremit.pay1.in/',
                                                          'uat'=>'https://remitapisuat.pay1.in/',
                                                        'live'=>'https://remitapisv3.pay1.in/'));  
}


define('SOAP_NAMESPACE_URL','http://tempuri.org/');
define('SMSTADKA_SMSQ','smstadka');
define('SMSTADKA_MAILQ','smstadka-mail');
define('SMSTADKA_MAIL_QUEUE','test_archana_mail');
define('TINY_URL','http://tinyurl.com/api-create.php');

define('GOOGLE_TRANSLATE_API','https://www.googleapis.com/language/translate/v2?key='.GOOGLE_API_KEY);
define('GOOGLE_MAP_API','https://maps.googleapis.com/maps/api/geocode/json');
define('PAY1_GOOGLE_MAP_API','https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_API_KEY);

//define('B2C_PULLBACK_URL','http://b2c.pay1.in/index.php/api_new/action/actiontype/pullback/api/true');
define('DND_CHECK_URL','http://nccptrai.gov.in/nccpregistry/saveSearchSub.misc');

//define('PLAY_STORE_APP_URL','https://play.google.com/store/apps/details?id=com.mindsarray.pay1');
//define('PAY1_APP_URL','https://panel.pay1.in/apis/downloadApp/');

//define('PANEL_PAYU_SEAMLESS_URL','https://panel.pay1.in/apis/pgPayUSeamless');
define('DOCUMENT_URL','s3.amazonaws.com/');

define('BBPS_VENDOR','161');
define('PAY1_GST_NO','27AAFCM5069H2Z7');
define('RET_LEAD_WEB_URL', 'https://shop.pay1.in');
define('RET_LEAD_APP_URL', 'https://goo.gl/GTaMAu');
define('TOP_TRENDING_PRODUCTS','90,46,45,48,47,93');
define('IVR_FILE_URL','/var/lib/asterisk/sounds/en/');
define('Rental_Delay_Limit','7');
define('IRCTC_SERVICEID',19);
define('IRCTC_PRODUCTID',162);
define('MANUAL_DIST_INCENTIVE_LIMIT','95');

//Techmate solution Api
define('TECHMATE_PWD','0805');
define('TECHMATE_UN','7710975362');
define('TECHMATE_URL','http://techmatesolutions.info/API/APIService.aspx');

define('DMT_SERVICE_CHARGE_PER','1');
define('DMT_SERVICE_CHARGE_MIN','10');



//define('DMT_NOTIFICATION_INSERTURL','https://remitapisuat.pay1.in/api/insertMessage');
//define('DMT_NOTIFICATION_UPDATEURL','https://remitapisuat.pay1.in/api/updateMessage');
//define('DMT_SERVICE_TOGGLEURL','https://remitapisuat.pay1.in/api/toggleVendorService');
//define('DMT_CTMORefundURL','https://remitapisuat.pay1.in/api/getRefundStatus');
//define('DMT_CTMURefundURL','https://remitapisuat.pay1.in/api/updateRefundStatus');
//Travel credential
define('Travel_AgencyBal','https://apis.pay1travel.in/api/get-tbo-account-balance/1');
define('Travel_ticket_key','111$MNM#ert12c11');
define('Travel_ticket_url','https://flight.pay1travel.in/flight/t/');
