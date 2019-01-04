<?php
$config['group_app_mapping'] = array(
                                'smartpay' => array(RETAILER),
                                'microfinance' => array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                                'dmt' => array(RETAILER),
                                'recharge_app' => array(RETAILER),
                                'recharge_web' => array(RETAILER),
                                'distributor_web' => array(DISTRIBUTOR,RETAILER),
                                'loan_app' => array(RETAILER,DISTRIBUTOR),
                                'travel_web' => array(RETAILER),
                                'travel_app' => array(RETAILER)
                            );

$config['acl'] = array('logout'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'walletHistory'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'uploadDocs'=>array(RETAILER,DISTRIBUTOR,BORROWER),
                       'profileApi'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'balance'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'deviceInfoUpdate'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'bankAccounts'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'listInvoices'=>array(RETAILER),
                       'getInvoice'=>array(RETAILER),
                       'updatePin'=>array(RETAILER,DISTRIBUTOR),
                       'updateTextualInfo'=>array(RETAILER,DISTRIBUTOR,BORROWER),
                       'getPanStatus'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'getPlans'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'serviceInfoApi'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'purchaseKit'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'upgradePlan'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'requestService'=>array(RETAILER,DISTRIBUTOR,BORROWER,LENDER),
                       'getCommissions'=>array(RETAILER,DISTRIBUTOR),
                       'commissionCalculation'=>array(RETAILER,DISTRIBUTOR)
);


$config['app_names'] = array('smartpay','microfinance','dmt','recharge_app','recharge_web','loan_app','travel_web','distributor_web','travel_app');
$config['app_names_services'] = array('smartpay'=>array(8,9,10),'microfinance'=>array(11),'dmt'=>array(12),'recharge_app'=>array(1,2,4,5,6,7,12,20),'recharge_web'=>array(1,2,4,5,6,7,12,20),'loan_app'=>array(11,14,15,24),'travel-web'=>array(23),'travel_app'=>array(23));
$config['app_versions_force_upgrade'] = array('recharge_app'=>'112','dmt'=>'17','smartpay' => '23','loan_app'=>'159');

$config['whitelist_apis']= array('authenticate','verifyOTPAuthenticate','resendOTPAuthenticate','changePin','verifyOTPChangePIN','createRetDistNewLeads','verifyRetDistNewLeads','sendOTPToRetDistLeads');

$config['api_param_counts']= array('changePin'=>3,'verifyOTPChangePIN'=>5,'authenticate'=>6,'verifyOTPAuthenticate'=>6,'resendOTPAuthenticate'=>5,'profileApi'=>4,'logout'=>4);

$config['doc_labels'] = array('pan' => 'PAN Card','aadhar' => 'Aadhar Card',  'kyc' => 'CSP onboarding form' ,'shop_est'=>'Shop establishment/Certificate of business ','declaration'=>'Declaration form','photo'=>'Recent passport size photograph',
                            'name'=>'Name','pan_no'=>'PAN No','aadhar_no'=>'Aadhar No','address'=>'Address','dob'=>'Date of Birth','gender'=>'Gender','registered_no'=>'Registered No','shop_est_name'=>'Name of Establishment','employer_name'=>'Name of Employer',
                            'business_nature'=>'Nature of Business','shop_est_address'=>'Postal Address of Establishment','shop_ownership'=>'Shop Ownership','gst_no'=>'GST No','alternate_mobile_no'=>'Alternate Mobile Number','shop_photo'=>'Shop Photo','cancelled_cheque'=>'Cancelled Cheque',
                            'consent_letter'=>'Consent Letter','loan_conf_letter'=>'Loan Confirmation Letter','payment_receipt'=>'Payment Confirmation Receipt','gst_certificate'=>'GST Certificate','gst_filing'=>'GST Filing','bank_statement'=>'Bank Statement'
                            );
$config['doc_type'] = array( '1'=>array('id'=>'1','label'=>'PAN Card','key'=>'pan','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11,12,18),'has_many'=>array(8,9,12),'dynamic_flag'=>'0') ,
        '2'=>array('id'=>'2','label'=>'Aadhar Card','key'=>'aadhar','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11,12,18),'has_many'=>array(10,11,13,24,25,35,36,37),'dynamic_flag'=>'0') ,
        '3'=>array('id'=>'3','label'=>'CSP onboarding form','key'=>'kyc','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(),'dynamic_flag'=>'0'),
        '4'=>array('id'=>'4','label'=>'Shop establishment/Certificate of business','key'=>'shop_est','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(14,15,16,17,18,19,32,44,45,46),'dynamic_flag'=>'0'),
        '5'=>array('id'=>'5','label'=>'Declaration form','key'=>'declaration','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(),'dynamic_flag'=>'0'),
        '6'=>array('id'=>'6','label'=>'Recent passport size photograph','key'=>'photo','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(),'dynamic_flag'=>'0'),
        '7'=>array('id'=>'7','label'=>'Profile','key'=>'profile','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(),'dynamic_flag'=>'0'),
        '8'=>array('id'=>'8','label'=>'Name','key'=>'name','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>1,'regex'=>"/[a-zA-Z\s'&-]+$/"),
        '9'=>array('id'=>'9','label'=>'PAN No','key'=>'pan_no','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>1,'regex'=>'/[A-Za-z]{5}\d{4}[A-Za-z]{1}$/'),
        '10'=>array('id'=>'10','label'=>'Aadhar No','key'=>'aadhar_no','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>2,'regex'=>'/[0-9]{12}$/'),
        '11'=>array('id'=>'11','label'=>'Address','key'=>'address','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>2),
        '12'=>array('id'=>'12','label'=>'Date of Birth','key'=>'dob','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>2),
        '13'=>array('id'=>'13','label'=>'Gender','key'=>'gender','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>2,'input_type' => 'dropdown'),
        '14'=>array('id'=>'14','label'=>'Registered No','key'=>'registered_no','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>4),
        '15'=>array('id'=>'15','label'=>'Name of Establishment','key'=>'shop_est_name','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>4,'regex'=>"/[a-zA-Z\s'&-]+$/"),
        '16'=>array('id'=>'16','label'=>'Name of Employer','key'=>'employer_name','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>4,'regex'=>"/[a-zA-Z\s'&-]+$/"),
        '17'=>array('id'=>'17','label'=>'Shop Type','key'=>'business_nature','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>4,'input_type' => 'dropdown'),
        '18'=>array('id'=>'18','label'=>'Postal Address of Establishment','key'=>'shop_est_address','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>4),
        '19'=>array('id'=>'19','label'=>'Shop Ownership','key'=>'shop_ownership','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>4,'input_type' => 'dropdown'),
        '20'=>array('id'=>'20','label'=>'GST No','key'=>'gst_no','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'29','regex'=>'/d{2}[A-Z]{5}\d{4}[A-Z]{1}\d[Z]{1}[A-Z\d]{1}$/'),
        '21'=>array('id'=>'21','label'=>'Shop Photo','key'=>'shop_photo','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8),'has_many'=>array(),'dynamic_flag'=>'0'),
        '22'=>array('id'=>'22','label'=>'Cancelled Cheque','key'=>'cancelled_cheque','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(),'dynamic_flag'=>'0'),
        '23'=>array('id'=>'23','label'=>'Alternate Mobile Number','key'=>'alternate_mobile_no','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'','regex'=>'/[1-9]{1}[0-9]{9}$/'),
        '24'=>array('id'=>'24','label'=>'Email Id','key'=>'email_id','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'','regex'=>'/([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/'),
        '25'=>array('id'=>'25','label'=>'Location Type','key'=>'location_type','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>2,'input_type' => 'dropdown'),
        '26'=>array('id'=>'26','label'=>'Consent Letter','key'=>'consent_letter,','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(11),'has_many'=>array(),'dynamic_flag'=>'0'),
        '27'=>array('id'=>'27','label'=>'Loan Confirmation Letter','key'=>'loan_conf_letter','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(11),'has_many'=>array(),'dynamic_flag'=>0),
        '28'=>array('id'=>'28','label'=>'Payment Confirmation Receipt','key'=>'payment_receipt','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(11),'has_many'=>array(),'dynamic_flag'=>0),
        '29'=>array('id'=>'29','label'=>'GST Certificate','key'=>'gst_certificate','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(20),'dynamic_flag'=>0),
        '30'=>array('id'=>'30','label'=>'GST Filing','key'=>'gst_filing','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(),'dynamic_flag'=>0),
        '31'=>array('id'=>'31','label'=>'Annual Turnover','key'=>'annual_turnover','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'','input_type' => 'dropdown'),
        '32'=>array('id'=>'32','label'=>'Shop Area Type','key'=>'shop_area_type','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'','input_type' => 'dropdown'),
        '33'=>array('id'=>'33','label'=>'Residential Address','key'=>'residential_address','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>''),
        '34'=>array('id'=>'34','label'=>'Residential Area','key'=>'residential_area','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>''),
        '35'=>array('id'=>'35','label'=>'Residential City','key'=>'residential_city','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>''),
        '36'=>array('id'=>'36','label'=>'Residential State','key'=>'residential_state','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>''),
        '37'=>array('id'=>'37','label'=>'Residential Pincode','key'=>'residential_pincode','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>''),
        '38'=>array('id'=>'38','label'=>'Account Holder Name','key'=>'acc_holder_name','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'','regex'=>"/[a-zA-Z\s'&-]+$/"),
        '39'=>array('id'=>'39','label'=>'Account Number','key'=>'account_no','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>''),
        '40'=>array('id'=>'40','label'=>'IFSC Code','key'=>'ifsc_code','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'','regex'=>'/[A-Za-z]{4}[a-zA-Z0-9]{7}$/'),
        '41'=>array('id'=>'41','label'=>'Bank Name','key'=>'bank_name','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'','regex'=>"/[a-zA-Z\s'&-]+$/"),
        '42'=>array('id'=>'42','label'=>'Bank Statement','key'=>'bank_statement','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png','application/pdf'),'services'=>array(11),'has_many'=>array(38,39,40,41),'dynamic_flag'=>'1'),
        '43'=>array('id'=>'43','label'=>'Shop Area','key'=>'shop_area','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'','regex'=>'/[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$/'),
        '44'=>array('id'=>'44','label'=>'Shop City','key'=>'shop_city','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'','regex'=>'/[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$/'),
        '45'=>array('id'=>'45','label'=>'Shop Pincode','key'=>'shop_pincode','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>'','regex'=>'/[1-9][0-9]{5}$/'),
        '46'=>array('id'=>'46','label'=>'Shop State','key'=>'shop_state','max_upload_count'=>NULL,'type'=>2,'allowed_extensions'=>NULL,'services'=>NULL,'belongs_to'=>''),
        '47'=>array('id'=>'47','label'=>'Aadhar Front','key'=>'aadhar_front','max_upload_count'=>1,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(10,11,12,13,25),'dynamic_flag'=>0),
        '48'=>array('id'=>'48','label'=>'Aadhar Back','key'=>'aadhar_back','max_upload_count'=>1,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(),'dynamic_flag'=>0),
        '49'=>array('id'=>'49','label'=>'ITR','key'=>'itr','max_upload_count'=>2,'type'=>1,'allowed_extensions'=>array('image/jpeg', 'image/png'),'services'=>array(8,9,10,11),'has_many'=>array(),'dynamic_flag'=>0)
    );
$config['textual_labels'] = array('name'=>'Name','pan_no'=>'PAN No','aadhar_no'=>'Aadhar No','address'=>'Address','dob'=>'Date of Birth','gender'=>'Gender','registered_no'=>'Registered No','shop_est_name'=>'Name of Establishment','employer_name'=>'Name of Employer','business_nature'=>'Nature of Business','location_type' => 'Location Type','shop_est_address'=>'Postal Address of Establishment','shop_ownership'=>'Shop Ownership','gst_no'=>'GST No','alternate_mobile_no'=>'Alternate Mobile Number',
    'residential_address'=>'Residential Address','residential_area'=>'Residential Area','residential_city'=>'Residential City','residential_state'=>'Residential State','residential_pincode'=>'Residential Pincode','acc_holder_name'=>'Account Holder Name','account_no'=>'Account Number','ifsc_code'=>'IFSC Code','bank_name'=>'Bank Name',
    'shop_area' => 'Shop Area','shop_city' => 'Shop City','shop_pincode' => 'Shop Pincode','shop_state' => 'Shop State');
$config['pay1_status'] = array(0 => 'INPROCESS',1 => 'APPROVED',  2=> 'REJECTED',3=>'CANCELLED');

$config['lead_state_mapping'] = array('cc'=>'2','pay1.in'=>'2','shop.pay1.in'=>'2','recharge_app'=>'2','distributor_app'=>'2','swipe_app'=>'2','smartpay'=>'2','loan_app'=>'2','remit_app'=>'2','facebook'=>'3','orm'=>'3','app_store'=>'3','rmpanel'=>'3','swipe'=>'2','remit'=>'2','channeloperations'=>'3');
$config['lead_source'] = array('cc'=>'4','pay1.in'=>'5','shop.pay1.in'=>'6','merchant_app'=>'7','distributor_app'=>'8','swipe_app'=>'9','capital_app'=>'10','remit_app'=>'11','facebook'=>'12','orm'=>'13','app_store'=>'14','rmpanel'=>'15','swipe'=>'45','remit'=>'46','channeloperations'=>'47','rm_app'=>'50');
$config['lead_state'] = array('1'=>'Hot','2'=>'Warm','3'=>'Cold');
$config['imp_label_types'] = array('17'=>array('1'=>'Mobile Store', '2'=>'Stationery Shop', '3'=>'Medical Store', '4'=>'Grocery Store', '5'=>'Photocopy Store', '6'=>'Travel Agency', '7'=>'Hardware Shop', '8'=>'Others', '9'=>'General Store', '10'=>'Tours and Travels','11'=>'Paan Shop')
                                    ,'19'=>array('1'=>'Owned', '2'=>'Rented', '3'=>'Co-Rented')
                                    ,'25'=>array('1'=>'Residential Area', '2'=>'Commercial Area', '3'=>'Industrial Area')
                                    ,'31'=>array('1'=>'0-20 lakhs', '2'=>'20-75 lakhs', '3'=>'Greater than 75 lakhs')
                                    ,'32'=>array('1'=>'Residential', '2'=>'School/College Area', '3'=>'Market Area', '4'=>'Commercial/Office Area', '5'=>'Outstation Bus Stand/Station Area', '6'=>'Others'));
$config['product_base_url'] = array(
        8 => 'https://smartpay.pay1.in/api',
        9 => 'https://smartpay.pay1.in/api',
        10 => 'https://smartpay.pay1.in/api',
        11=>'https://microfinance.pay1.in:8000',
        12=>'https://remitapis.pay1.in/api'
);

$config['pan_errors'] = array(
    2 => 'System Error',
    3 => 'Authentication Failure',
    4 => 'User not authorized',
    5 => 'No PANs Entered',
    6 => 'User validity has expired',
    7 => 'Number of PANs exceeds the limit (5)',
    8 => 'Not enough balance',
    9 => 'Not an HTTPs request',
    10 => 'POST method not used'
);
$config['pan_statuses'] = array(
    'E' => 'Existing and Valid PAN',
    'F' => 'Fake PAN',
    'N' => 'Record (PAN) Not Found in ITD Database/Invalid PAN'
);
$config['pan_config'] = array(
    "NSDL_USER_ID"  => "V0132801",
    "PAN_CODE_PATH" =>  $_SERVER["DOCUMENT_ROOT"]."/../vendors/pvc",
    "SIGNATURE_FOLDER" =>  "signatures",
    "PFX_PSWD" => "123",
    "DATA_LIMIT" =>  10
);
