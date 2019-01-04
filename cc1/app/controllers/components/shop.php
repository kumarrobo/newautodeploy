<?php
class ShopComponent extends Object{
    var $components = array('General', 'B2cextender', 'Recharge','Documentmanagement','Bridge','Serviceintegration');
    var $Memcache = null;

    function __construct(){
        if(class_exists("Memcache")){
            $memcache = new Memcache();
            $this->Memcache = null;
            try{
                if($memcache->pconnect(MEMCACHE_IP, MEMCACHE_PORT)){
                    $this->Memcache = $memcache;
                }
            }
            catch(Exception $e){
                $this->General->logData("memcache_log.txt", "Exception in memcache connection::" . $e->getMessage());
            }
        }
    }

    function memcacheConnection($memcache_host){
        $memcache = new Memcache();
        if($memcache->pconnect($memcache_host, MEMCACHE_PORT)){
            return $memcache;
        }
        return false;

    }

    function addMemcache($key, $value, $duration){
        try{
            if( ! empty($this->Memcache)){
                return $this->Memcache->add($key, $value, false, $duration);
            }
            else
                return false;
        }
        catch(Exception $e){
            return false;
        }
    }

    function setMemcache($key, $value, $duration = null){
        try{
            if( ! empty($this->Memcache)){
                if(empty($duration)) $duration = 0;
                return $this->Memcache->set($key, $value, false, $duration);
            }
            else
                return false;
        }
        catch(Exception $e){
            return false;
        }
    }

    function incrementMemcache($key, $counter = 1){
        try{
            if( ! empty($this->Memcache)){
                return $this->Memcache->increment($key, $counter);
            }
            else
                return false;
        }
        catch(Exception $e){
            return false;
        }
    }

    function decrementMemcache($key, $counter = 1){
        try{
            if( ! empty($this->Memcache)){
                return $this->Memcache->decrement($key, $counter);
            }
            else
                return false;
        }
        catch(Exception $e){
            return false;
        }
    }

    function getMemcache($key){
        try{
            if( ! empty($this->Memcache)){
                return $this->Memcache->get($key);
            }
            else
                return false;
        }
        catch(Exception $e){
            return false;
        }
    }

    function getMemcacheStats(){
        try{
            if( ! empty($this->Memcache)){
                return $this->Memcache->getStats();
            }
            else
                return false;
        }
        catch(Exception $e){
            return false;
        }
    }

    function getMemcacheKeys($prefix = null){
        if( ! empty($this->Memcache)){
            $list = array();
            $allSlabs = $this->Memcache->getExtendedStats('slabs');
            $items = $this->Memcache->getExtendedStats('items');
            $i = 0;
            foreach($allSlabs as $server=>$slabs){
                foreach($slabs as $slabId=>$slabMeta){
                    $cdump = $this->Memcache->getExtendedStats('cachedump', (int)$slabId);
                    foreach($cdump as $keys=>$arrVal){
                        if( ! is_array($arrVal)) continue;
                        foreach($arrVal as $k=>$v){
                            if(empty($prefix) || (strpos($k, $prefix) !== false && strpos($k, $prefix) == 0)){
                                $list[$k] = $this->getMemcache($k);
                            }
                        }
                    }
                }
            }
            return $list;
        }
        else
            return false;
    }

    function delMemcache($key){
        try{
            if( ! empty($this->Memcache)){
                return $this->Memcache->delete($key);
            }
            else
                return false;
        }
        catch(Exception $e){
            return false;
        }
    }

    function redis_connect(){
        try{
            App::import('Vendor', 'Predis', array('file'=>'Autoloader.php'));
            Predis\Autoloader::register();
            $this->redis = new Predis\Client(array('host'=>REDIS_HOST, 'port'=>REDIS_PORT, 'persistent'=>false));

            // 'password' => REDIS_PASSWORD,
        }
        catch(Exception $e){
            echo "Couldn't connected to Redis";
            echo $e->getMessage();
            $this->General->logData('/mnt/logs/redis_connector' . date('Y-m-d') . '.log', "issue in sms_redis : " . $e->getMessage(), FILE_APPEND | LOCK_EX);
            $this->redis = false;
        }
        return $this->redis;
    }

    /**
     * Only for request TPS LINE
     *
     * @return type
     */
    function redis_connector(){
        try{
            App::import('Vendor', 'Predis', array('file'=>'Autoloader.php'));
            Predis\Autoloader::register();
            $this->tpsredis = new Predis\Client(array('host'=>REDIS_HOST, 'port'=>REDIS_PORT, 'persistent'=>true));
        }
        catch(Exception $e){
            echo "Couldn't connected to Redis";
            echo $e->getMessage();
            $this->General->logData('/mnt/logs/redis_connector' . date('Y-m-d') . '.log', "issue in tps_redis : " . $e->getMessage(), FILE_APPEND | LOCK_EX);
            $this->tpsredis = false;
        }
        return $this->tpsredis;
    }

    function errors($code){
        $err = array('0'=>'Authentication Failed.', '1'=>'Account disabled.', '2'=>'Invalid Method.', '3'=>'Request cannot be processed. Try again.', '4'=>'Invalid Number of Parameters.', '5'=>'Wrong operator code/Wrong Mobile Number.', '6'=>'Invalid Amount.', '7'=>'Invalid Recharge Type.',
                '8'=>'Invalid Operator Code.', '9'=>'Product does not exist.', '10'=>'Recharge PPI failed.', '11'=>'Getting recharges from OSS failed.', '12'=>'Recharge OSS failed.', '13'=>'Transaction Successful.', '14'=>'Transaction Failed. Try Again.', '15'=>'Transaction Pending.',
                '16'=>'DTH Recharge PPI failed.', '17'=>'Getting dth recharges from OSS failed.', '18'=>'DTH Recharge OSS failed.', '19'=>'Custom PPI Message.', '20'=>'Custom oss Message.', '21'=>'Mobile flexi recharge code not supported from OSS.',
                '22'=>'Mobile recharge code not available from OSS.', '23'=>'DTH recharge code not available from OSS.', '24'=>'DTH flexi recharge code not supported from OSS.', '25'=>'No active vendor for mobile recharge.', '26'=>'Insufficient balance. Please recharge.',
                '27'=>'No active vendor for DTH recharge.', '28'=>'Login failed. Try Again.', '29'=>'Recharge not available.', '30'=>'Technical Problem. Please try again.', '31'=>'Transaction Processed.', '403'=>'Session does not exist.', '404'=>'Access denied.', '32'=>'Invalid Existing Pin.',
                '33'=>'Minimum recharge error.', '34'=>'Maximum recharge error.', '35'=>'Telecom Circle not found. Please try after some time.', '36'=>'Vendor Server not responding', '37'=>'Try after some time.', '38'=>'Recharge for this mobile number/subscriber id is already under process.',
                '39'=>'Wrong subscriber id', '40'=>'No active sim/no balance', '41'=>'Dropped due to lot of pending requests', '42'=>'Payment Authorization failed', '43'=>'Operator is currently down. Try after some time.', '44'=>'Please call customer care on 022-67242288 to activate this service.',  // Transfer to kit to use this service',
                '45'=>'Complaint already taken', '46'=>'Wrong account/phone number', '47'=>'Operator General Error', '48'=>'You are using an older version of Pay1. Please update the app to recharge.', '55'=>'Kindly create a strong password', '60'=>'Exception Occured', "61" => "Invalid User");
        return $err[$code];
    }

    function apiErrors($code){
        $err = array('0'=>'Authentication Failed.', '1'=>'Account disabled.', '2'=>'Invalid Method.', '3'=>'Request cannot be processed. Try again.', '4'=>'Invalid Number of Parameters.', '5'=>'Wrong operator code/Wrong Mobile Number.', '6'=>'Invalid Amount.', '7'=>'Invalid Recharge Type.',
                '8'=>'Invalid Operator Code.', '9'=>'Product does not exist.', '10'=>'Recharge PPI failed.', '11'=>'Getting recharges from OSS failed.', '12'=>'Recharge OSS failed.', '13'=>'Transaction Successful.', '14'=>'Transaction Failed. Try Again.', '15'=>'Transaction Pending.',
                '16'=>'DTH Recharge PPI failed.', '17'=>'Getting dth recharges from OSS failed.', '18'=>'DTH Recharge OSS failed.', '19'=>'Custom PPI Message.', '20'=>'Custom oss Message.', '21'=>'Mobile flexi recharge code not supported from OSS.',
                '22'=>'Mobile recharge code not available from OSS.', '23'=>'DTH recharge code not available from OSS.', '24'=>'DTH flexi recharge code not supported from OSS.', '25'=>'No active vendor for mobile recharge.', '26'=>'Insufficient balance. Please recharge.',
                '27'=>'No active vendor for DTH recharge.', '28'=>'Login failed. Try Again.', '29'=>'Recharge not available.', '30'=>'Technical Problem. Please try again.', '31'=>'Transaction Processed.', '403'=>'Session does not exist.', '404'=>'Access denied.', '32'=>'Invalid Existing Pin.',
                '33'=>'Minimum recharge error.', '34'=>'Maximum recharge error.', '35'=>'Telecom Circle not found. Please try after some time.', '36'=>'Vendor Server not responding', '37'=>'Please try this transaction after 15 minutes.',
                '38'=>'Recharge for this mobile number/subscriber id is already under process.', '39'=>'Wrong subscriber id', '40'=>'No active sim/no balance', '41'=>'Dropped due to lot of pending requests', '42'=>"Are you sure you want to repeat this recharge?",
                '43'=>"Are you sure you want to continue?", '44'=>"No mobile or password found", '45'=>"The new number cannot be registered as a retailer.", '46'=>"Invalid mobile or password", '47'=>"OTP did not match. Mobile number did not change.", '48'=>"OTP expired. Try again.",
                '49'=>"Your mobile did not match with our records.", '50'=>"Incorrect MOBILE NUMBER or PIN", '51'=>"Leads is already created", '52'=>"Retailer already exists with this mobile number", '53'=>"Distributor already exists with this mobile number",
                '54'=>"OTP did not match or expired. Try again.", '55'=>"Lead generated successfully.", '56'=>"Lead not generated.", '57'=>"Incorrect MOBILE NUMBER or OTP", '58'=>"Incorrect MOBILE NUMBER or INTEREST", '59'=>"OTP has been sent to your mobile number",
                '60'=>"Your number is already registered with Pay1", '61'=>"Please wait! You will get OTP on call shortly on your registered number.. ", '62'=>"Please wait! Your request is under process.. ", '63'=>'Incorrect Mobile Number or Token.', '64'=>'Form submitted successfully.',
                '65'=>'Your application is under review. We have sent detailed email / sms on the proposal. We will get back to you within 24 hrs or you can call us on 02242932202.','66'=>'OTP verified successfully.','67'=>'Invalid Mobile Number',
                '68'=>'Your application is under review. We will get back to you within 24 hrs or you can call us on 02242932202.','69'=>'We have already received an application for distributorship through your mobile number. To become a retailer, please apply using a different mobile number.',
                '70'=>'Your are already a distributor. You cannot change your number from here','71'=>'Invalid input parameters',

                // ------API Error Codes-------
                'E000'=>'Some error occured.', 'E001'=>'Invalid Operation Type.', 'E002'=>'Account disabled.', 'E003'=>'Invalid inputs.', // Empty of insufficient input
'E004'=>'Invalid partner ID.', 'E005'=>'Authentication Error ( Invalid Access Key ).', 'E006'=>'Authentication Error ( Invalid access location ).', 'E007'=>'Not enough balance', 'E008'=>"Wrong operator code/Wrong Mobile Number.", 'E009'=>"Wrong operator code/Wrong Subscriber id.",
                'E010'=>"Invalid amount.", 'E011'=>"Request already in process", 'E012'=>"Try after some time", 'E013'=>"Operator General Error", 'E014'=>"Duplicate request", 'E015'=>"Invalid Transaction Id", 'E016'=>"Invalid operator access",
                'E017'=>"No of transactions should be less or equal to 10.", 'E018'=>"Complaint already taken.", 'E019'=>"Cannot create retailer on this mobile number", 'E020'=>"Retailer creation limit reached. Cannot create more retailers.", 'E021'=>"You cannot create retailer, contact Pay1",
                'E022'=>"You have 0 kits left. Buy more retailer kits to enjoy this benefit.", 'E023'=>"The Retailer could not be saved. Please, try again.", 'E024'=>"field left empty", 'E025'=>"Retailer not found. Cannot send OTP", 'E026'=>"Incorrect OTP or PIN",
                'E027'=>"Authentication failure. OTP did not match.",

                // KYC error codes
                "E101"=>"Update KYC to enjoy Toll Free Calling.", "E102"=>"Your information is rejected.", "E103"=>"Your information is under review. Wait for 48 hours.", "E104"=>"Sorry! We are available between 8am to 11pm.", "E105"=>"Incomplete data. Please update to enjoy Toll Free Calling.");
        return $err[$code];
    }

    // function retailerTypes($type=null){
    // $arr = array('1' => 'Telecom',
    // '2' => 'Kirana / General Stores',
    // '3' => 'Medical Store',
    // '4' => 'Cyber Cafes',
    // '5' => 'Travel Agency',
    // '6' => 'STD / PCO',
    // '7' => 'Footwear shops',
    // '8' => 'Fast Food Joints / Eateries',
    // '9' => 'Freelancer (without shop)',
    // '10' => 'Electronics',
    // '11' => 'Electrical and Hardware',
    // '12' => 'Computer Stationery',
    // '13' => 'Xerox and Stationery',
    // '14' => 'Estate Agent',
    // '15' => 'Clothing',
    // '16' => 'Bags and Other Accessories dealers',
    // '17' => 'Pan Bidi shop',
    // '18' => 'Saloon',
    // '19' => 'Tailors',
    // '20' => 'Others',
    // '21' => 'Mobile Store',
    // '22' => 'Stationery Shop',
    // '23' => 'Grocery Store',
    // '24' => 'Photocopy Store',
    // '25' => 'Hardware Shop'
    // );

    // if(empty($type)) return $arr;
    // else return $arr[$type];
    // }

    // function locationTypes($type=null){
    // $arr = array('1' => 'Near Station',
    // '2' => 'Posh Area',
    // '3' => 'Slum Area',
    // '4' => 'Residential Area',
    // '5' => 'Market',
    // '6' => 'Industrial Area',
    // '7' => 'Commercial Area',
    // );

    // if(empty($type)) return $arr;
    // else return $arr[$type];
    // }
    function business_natureTypes($type = null){
        $arr = array('1'=>'Mobile Store', '2'=>'Stationery Shop', '3'=>'Medical Store', '4'=>'Grocery Store', '5'=>'Photocopy Store', '6'=>'Travel Agency', '7'=>'Hardware Shop', '8'=>'Others', '9'=>'General Store', '10'=>'Tours and Travels','11'=>'Paan Shop');

        if(empty($type)) return $arr;
        else return $arr[$type];
    }

    function location_typeTypes($type = null){
        $arr = array('1'=>'Residential Area', '2'=>'Commercial Area', '3'=>'Industrial Area');

        if(empty($type)) return $arr;
        else return $arr[$type];
    }

    function annual_turnoverTypes($type = null){
        $arr = array('1'=>'0-20 lakhs', '2'=>'20-75 lakhs', '3'=>'Greater than 75 lakhs');

        if(empty($type)) return $arr;
        else return $arr[$type];
    }

    function shop_area_typeTypes($type = null){
        $arr = array('1'=>'Residential', '2'=>'School/College Area', '3'=>'Market Area', '4'=>'Commercial/Office Area', '5'=>'Outstation Bus Stand/Station Area', '6'=>'Others');

        if(empty($type)) return $arr;
        else return $arr[$type];
    }

    function shop_ownershipTypes($type = null){
        $arr = array('1'=>'Owned', '2'=>'Rented', '3'=>'Co-Rented');

        if(empty($type)) return $arr;
        else return $arr[$type];
    }

    function genderTypes($type = null){
        $arr = array('1'=>'Male', '2'=>'Female');

        if(empty($type)) return $arr;
        else return $arr[$type];
    }
    
    function alt_genderTypes($type = null){
        $arr = array('1'=>'Male', '2'=>'Female');

        if(empty($type)) return $arr;
        else return $arr[$type];
    }

    function structureTypes($type = null){
        $arr = array('1'=>'Permanent', '2'=>'Temporary');

        if(empty($type)) return $arr;
        else return $arr[$type];
    }

    function kycSectionMap($section_id = null){
        $map = array('1'=>array('fields'=>array('name'), 'documents'=>array('PAN_CARD')), '2'=>array('fields'=>array('shopname', 'area_id', 'address', 'pin', 'latitude', 'longitude'), 'documents'=>array('ADDRESS_PROOF')),
                '3'=>array('fields'=>array('shop_type', 'shop_type_value', 'location_type'), 'documents'=>array('SHOP_PHOTO')));

        if(empty($section_id)) return $map;
        else return $map[$section_id];
    }

    function mapApiErrs($code){
        $err = array('1'=>'E002', '2'=>'E001', '3'=>'E000', '4'=>'E003', '5'=>'E008', '6'=>'E010', '7'=>'E003', '8'=>'E008', '9'=>'E003', '10'=>'E013', '11'=>'E013', '12'=>'E013', '14'=>'E013', '16'=>'E013', '17'=>'E013', '18'=>'E013', '19'=>'E013', '20'=>'E013', '21'=>'E013', '22'=>'E013',
                '23'=>'E013', '24'=>'E013', '25'=>'E013', '26'=>'E007', '27'=>'E013', '29'=>'E013', '30'=>'E000', '33'=>'E010', '34'=>'E010', '35'=>'E013', '36'=>'E013', '37'=>'E012', '38'=>'E011', '39'=>'E009', '40'=>'E013', '41'=>'E013', '42'=>'E013', '43'=>'E016', '45'=>'E018');
        return $err[$code];
    }

    function ussdErrors($code){
        $err = array("1"=>"Unknown subscriber", "105"=>"SDP:SIM Card data does not exist.", "11"=>"Teleservice not provisioned", "110"=>"Map Dialog P Abort Indication : Source is MAP", "111"=>"Map Dialog P Abort Indication : Source is TCAP",
                "112"=>"Map Dialog P Abort Indication : Source is Network", "13"=>"CallBarred", "130"=>"Map Dialogue rejected refuse reason :invalid dest reference", "132"=>"Map Dialog Rejected Refuse Reason: Application Context not supported",
                "137"=>"Map Dialog Rejected Provider Reason: Resource Limitation", "138"=>"Map Dialogue rejected provider reason : Maintenance Activity", "150"=>"Map Dialog User Abort User Reason: User specific reason", "151"=>"MAP_DLGUA_UsrRsn_UsrResourceLimitation",
                "153"=>"Map Dialog User Abort User Reason: App procedure cancelled", "176"=>"Invalid service code", "177"=>"Access Denied Blacklist", "178"=>"CDREC_IMSIBlackListed", "183"=>"VLR black listed", "192"=>"Unexpected session release server/3rd party APP",
                "193"=>"No reseponse with configurable time from servers/3rd party application", "197"=>"Application Specific Error from 3rd partyClient", "209"=>"Timer expired for SRISM Response", "210"=>"Timer expired for N/W initaited USSRN", "211"=>"Timer expired for Mobile initaited USSRN",
                "229"=>"Received Invalid MAP message", "231"=>"IMSI unavailable in SRISM Response", "232"=>"VLR unavailable in SRISM Response", "235"=>"Exit Option Selected By Subscriber", "236"=>"Menu is Subscriber based/but subscriber type could not be resolved at the point in transaction.",
                "242"=>"Service not configured/Menu Incomplete", "243"=>"Invalid user input", "244"=>"Access Denied Blacklisted", "245"=>"Client not connected", "27"=>"Absent Subscriber", "31"=>"subscriber busy for Mt sms", "34"=>"System failure", "35"=>"dataMissing", "36"=>"unexpectedDataValue",
                "9"=>"Illegal subscriber", "72"=>"Ussd Busy");
        return $err[$code];
    }

    function smsProdCodes($code){
        $prod = array('1'=>array('operator'=>'Aircel', 'params'=>array('method'=>'mobRecharge', 'operator'=>'1', 'type'=>'flexi')), '2'=>array('operator'=>'Airtel', 'params'=>array('method'=>'mobRecharge', 'operator'=>'2', 'type'=>'flexi')),
                '3'=>array('operator'=>'BSNL', 'params'=>array('method'=>'mobRecharge', 'operator'=>'3', 'type'=>'flexi')), '4'=>array('operator'=>'Idea', 'params'=>array('method'=>'mobRecharge', 'operator'=>'4', 'type'=>'flexi')),
                '5'=>array('operator'=>'Loop/BPL', 'params'=>array('method'=>'mobRecharge', 'operator'=>'5', 'type'=>'flexi')), '6'=>array('operator'=>'MTS', 'params'=>array('method'=>'mobRecharge', 'operator'=>'6', 'type'=>'flexi')),
                '7'=>array('operator'=>'Reliance CDMA', 'params'=>array('method'=>'mobRecharge', 'operator'=>'7', 'type'=>'flexi')), '8'=>array('operator'=>'Reliance GSM', 'params'=>array('method'=>'mobRecharge', 'operator'=>'8', 'type'=>'flexi')),
                '9'=>array('operator'=>'Tata Docomo', 'params'=>array('method'=>'mobRecharge', 'operator'=>'9', 'type'=>'flexi')), '10'=>array('operator'=>'Tata Indicom', 'params'=>array('method'=>'mobRecharge', 'operator'=>'10', 'type'=>'flexi')),
                '11'=>array('operator'=>'Uninor', 'params'=>array('method'=>'mobRecharge', 'operator'=>'11', 'type'=>'flexi')), '12'=>array('operator'=>'Videocon', 'params'=>array('method'=>'mobRecharge', 'operator'=>'12', 'type'=>'flexi')),
                '13'=>array('operator'=>'Virgin CDMA', 'params'=>array('method'=>'mobRecharge', 'operator'=>'13', 'type'=>'flexi')), '14'=>array('operator'=>'Virgin GSM', 'params'=>array('method'=>'mobRecharge', 'operator'=>'14', 'type'=>'flexi')),
                '15'=>array('operator'=>'Vodafone', 'params'=>array('method'=>'mobRecharge', 'operator'=>'15', 'type'=>'flexi')), '27'=>array('operator'=>'Tata SV', 'params'=>array('method'=>'mobRecharge', 'operator'=>'9', 'type'=>'flexi', 'special'=>1)),
                '28'=>array('operator'=>'Videocon SV', 'params'=>array('method'=>'mobRecharge', 'operator'=>'12', 'type'=>'flexi', 'special'=>1)), '29'=>array('operator'=>'Uninor SV', 'params'=>array('method'=>'mobRecharge', 'operator'=>'11', 'type'=>'flexi', 'special'=>1)),
                '30'=>array('operator'=>'MTNL', 'params'=>array('method'=>'mobRecharge', 'operator'=>'30', 'type'=>'flexi')), '31'=>array('operator'=>'MTNL SV', 'params'=>array('method'=>'mobRecharge', 'operator'=>'30', 'type'=>'flexi', 'special'=>1)),
                '34'=>array('operator'=>'BSNL SV', 'params'=>array('method'=>'mobRecharge', 'operator'=>'3', 'type'=>'flexi', 'special'=>1)), '16'=>array('operator'=>'Airtel DTH', 'params'=>array('method'=>'dthRecharge', 'operator'=>'1', 'type'=>'flexi')),
                '17'=>array('operator'=>'Big TV DTH', 'params'=>array('method'=>'dthRecharge', 'operator'=>'2', 'type'=>'flexi')), '18'=>array('operator'=>'Dish TV DTH', 'params'=>array('method'=>'dthRecharge', 'operator'=>'3', 'type'=>'flexi')),
                '19'=>array('operator'=>'Sun TV DTH', 'params'=>array('method'=>'dthRecharge', 'operator'=>'4', 'type'=>'flexi')), '20'=>array('operator'=>'Tata Sky DTH', 'params'=>array('method'=>'dthRecharge', 'operator'=>'5', 'type'=>'flexi')),
                '21'=>array('operator'=>'Videocon DTH', 'params'=>array('method'=>'dthRecharge', 'operator'=>'6', 'type'=>'flexi')), '22'=>array('operator'=>'Dil Vil Pyar Vyar', 'params'=>array('method'=>'vasRecharge', 'operator'=>'22', 'type'=>'fix')),
                '23'=>array('operator'=>'Naughty Jokes', 'params'=>array('method'=>'vasRecharge', 'operator'=>'23', 'type'=>'fix')), '24'=>array('operator'=>'PNR Alert', 'params'=>array('method'=>'vasRecharge', 'operator'=>'24', 'type'=>'fix')),
                '25'=>array('operator'=>'Instant Cricket', 'params'=>array('method'=>'vasRecharge', 'operator'=>'25', 'type'=>'fix')), '32'=>array('operator'=>'Chatpati Baate Mini Pack', 'params'=>array('method'=>'vasRecharge', 'operator'=>'32', 'type'=>'fix')),
                '33'=>array('operator'=>'Chatpati Baate Mega Pack', 'params'=>array('method'=>'vasRecharge', 'operator'=>'33', 'type'=>'fix')), '35'=>array('operator'=>'Ditto TV', 'params'=>array('method'=>'vasRecharge', 'operator'=>'35', 'type'=>'flexi')),
                                /*  36 - Docomo Postpaid
                                    37 - Loop Mobile PostPaid
                                    38 - Cellone PostPaid
                                    39 - IDEA Postpaid
                                    40 - Tata TeleServices PostPaid
                                    41 - Vodafone Postpaid*/
                                '36'=>array('operator'=>'Docomo Postpaid', 'params'=>array('method'=>'mobBillPayment', 'operator'=>'1', 'type'=>'flexi')), '37'=>array('operator'=>'Loop Mobile PostPaid', 'params'=>array('method'=>'mobBillPayment', 'operator'=>'2', 'type'=>'flexi')),
                '38'=>array('operator'=>'Cellone PostPaid', 'params'=>array('method'=>'mobBillPayment', 'operator'=>'3', 'type'=>'flexi')), '39'=>array('operator'=>'IDEA Postpaid', 'params'=>array('method'=>'mobBillPayment', 'operator'=>'4', 'type'=>'flexi')),
                '40'=>array('operator'=>'Tata TeleServices PostPaid', 'params'=>array('method'=>'mobBillPayment', 'operator'=>'5', 'type'=>'flexi')), '41'=>array('operator'=>'Vodafone Postpaid', 'params'=>array('method'=>'mobBillPayment', 'operator'=>'6', 'type'=>'flexi')),

                '42'=>array('operator'=>'Airtel Postpaid', 'params'=>array('method'=>'mobBillPayment', 'operator'=>'7', 'type'=>'flexi')),
                '43'=>array('operator'=>'Reliance Postpaid', 'params'=>array('method'=>'mobBillPayment', 'operator'=>'8', 'type'=>'flexi')),
                '44'=>array('operator'=>'Pay1 Wallet', 'params'=>array('method'=>'pay1Wallet', 'operator'=>'1', 'type'=>'flexi')), '45'=>array('operator'=>'Reliance Energy (Mumbai)', 'params'=>array('method'=>'utilityBillPayment', 'operator'=>'1', 'type'=>'flexi')),
                '46'=>array('operator'=>'BSES Rajdhani', 'params'=>array('method'=>'utilityBillPayment', 'operator'=>'2', 'type'=>'flexi')), '47'=>array('operator'=>'BSES Yamuna', 'params'=>array('method'=>'utilityBillPayment', 'operator'=>'3', 'type'=>'flexi')),
                '48'=>array('operator'=>'North Delhi Power Limited', 'params'=>array('method'=>'utilityBillPayment', 'operator'=>'4', 'type'=>'flexi')), '49'=>array('operator'=>'Pay1 Wallet', 'params'=>array('method'=>'utilityBillPayment', 'operator'=>'5', 'type'=>'flexi')),
                '50'=>array('operator'=>'Pay1 Wallet', 'params'=>array('method'=>'utilityBillPayment', 'operator'=>'6', 'type'=>'flexi')), '51'=>array('operator'=>'Pay1 Wallet', 'params'=>array('method'=>'utilityBillPayment', 'operator'=>'7', 'type'=>'flexi')),
                '83'=>array('operator'=>'Reliance Jio', 'params'=>array('method'=>'mobRecharge', 'operator'=>'83', 'type'=>'flexi')));
        return isset($prod[$code]['params']) ? $prod[$code]['params'] : array();
    }

    function getRetailerTrnsDetails($mobile){
        $data = $this->getMemcache($mobile . "_retTxn");
        return $data;
    }

    function setRetailerTrnsDetails($mobile, $data){
        $this->setMemcache($mobile . "_retTxn", $data, 3 * 24 * 60 * 60);
        return;
    }

    function getSalesmanDeviceData($mobile){
        $data = $this->getMemcache($mobile . "_device_data");
        return $data;
    }

    function setSalesmanDeviceData($mobile, $data){
        $data['trans_type'] = $data['trans_type'] . "_distributor";
        $this->setMemcache($mobile . "_device_data", $data, 3 * 24 * 60 * 60);
        return;
    }


    function shopTransactionUpdate($type, $amount, $ref1_id, $ref2_id, $user_id = null, $discount = null, $type_flag = null, $note = null, $source_opening = 0, $source_closing = 0, $target_opening = 0, $target_closing = 0, $dataSource = null){
        if(is_null($dataSource)):

            $this->data = null;
            $transObj = ClassRegistry::init('ShopTransaction');

            $this->data['ShopTransaction']['source_id'] = (empty($ref1_id) ? 0 : $ref1_id);
            $this->data['ShopTransaction']['target_id'] = (empty($ref2_id) ? 0 : $ref2_id);
            $this->data['ShopTransaction']['amount'] = $amount;
            $this->data['ShopTransaction']['type'] = $type;
            $this->data['ShopTransaction']['timestamp'] = date('Y-m-d H:i:s');
            $this->data['ShopTransaction']['date'] = date('Y-m-d');
            $this->data['ShopTransaction']['note'] = $note;
            $this->data['ShopTransaction']['source_opening'] = $source_opening;
            $this->data['ShopTransaction']['source_closing'] = $source_closing;
            $this->data['ShopTransaction']['target_opening'] = $target_opening;
            $this->data['ShopTransaction']['target_closing'] = $target_closing;
            if($type == RETAILER_ACTIVATION){
                $this->data['ShopTransaction']['confirm_flag'] = 1;
            }

            if($user_id != null){
                $this->data['ShopTransaction']['user_id'] = $user_id;
            }
            if($discount != null){
                $this->data['ShopTransaction']['discount_comission'] = $discount;
            }
            if($type_flag != null){
                $this->data['ShopTransaction']['type_flag'] = $type_flag;
            }
            $transObj->create();
            if($transObj->save($this->data)){
                return $transObj->id;
            }
            else
                return false;

        else:
            // $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', 'Creating ST ', FILE_APPEND | LOCK_EX);
            $confirm_flag = ($type == RETAILER_ACTIVATION) ? 1 : 0;
            $user_id =  ! is_null($user_id) ? $user_id : null;
            $discount_comission =  ! is_null($discount) ? $discount : null;
            $type_flag =  ! is_null($type_flag) ? $type_flag : null;
            $date = date('Y-m-d');
            $timestamp = date('Y-m-d H:i:s');
            $sql = " Insert into shop_transactions(source_id,target_id,amount,type,timestamp,date,note,confirm_flag,user_id,discount_comission,type_flag,source_opening,source_closing,target_opening,target_closing) values ('{$ref1_id}','{$ref2_id}','{$amount}','{$type}','{$timestamp}','{$date}','{$note}','{$confirm_flag}','{$user_id}','{$discount_comission}','{$type_flag}','{$source_opening}','{$source_closing}','{$target_opening}','{$target_closing}' ) ";

            if($dataSource->query($sql)):
                if(in_array($type,array(DIST_RETL_BALANCE_TRANSFER,SLMN_RETL_BALANCE_TRANSFER)))
                {
                    $ret_topup_data = $dataSource->query("SELECT user_id,topup_flag FROM retailers WHERE id = '$ref2_id' ");

                    if(!empty($ret_topup_data) && ($ret_topup_data[0]['retailers']['topup_flag'] == '0'))
                    {
                        $dataSource->query("UPDATE retailers SET topup_flag = '1' WHERE id = '$ref2_id' ");

                        //call an api of gamification whenever a user takes balance for the first time
                        $url = REFERRAL_URL."?user_id=".$ret_topup_data[0]['retailers']['user_id']."&amount=".$amount."&param1=&param2=";
                        $data = $this->General->curl_post($url,null,'GET',30,30);
                        $this->General->logData('/mnt/logs/referral.txt', "Input : ".$url." Output : ".json_encode($data));
                    }
                }
                $lastInserIdQuery = $dataSource->query("SELECT id FROM shop_transactions WHERE source_id = '$ref1_id' AND type = '$type' AND date = '$date' AND timestamp = '$timestamp' ORDER BY id DESC LIMIT 1");
                // return $dataSource->lastInsertId();
                $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', "ST id :  {$lastInserIdQuery[0][0]['id']}", FILE_APPEND | LOCK_EX);
                return $lastInserIdQuery[0]['shop_transactions']['id'];

            else:
                $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', "ST Failed :  {$lastInserIdQuery[0][0]['id']}", FILE_APPEND | LOCK_EX);
                return false;
            endif;

        endif;
    }

    function addAppRequest($method, $mobile, $amount, $opr, $type, $ret_id){
        $ret = $this->addMemcache("request_" . $mobile . "_" . intval($amount) . "_" . $opr . "_" . $ret_id, 1, 5 * 60);
        if($ret !== false) return 1;
        else return 0;
        /*
         * $reqObj = ClassRegistry::init('Request');
         *
         * //$reqObj->query("DELETE FROM requests WHERE timestamp < now() - 300");
         *
         * $this->data['Request']['method'] = $method;
         * $this->data['Request']['mobile'] = $mobile;
         * $this->data['Request']['amount'] = $amount;
         * $this->data['Request']['operator'] = $opr;
         * $this->data['Request']['type'] = $type;
         * $this->data['Request']['timestamp'] = NULL;
         * $reqObj->create();
         * $insertQry = "INSERT INTO requests (method,mobile,amount,operator,type,timestamp) VALUES ('".$method."','".$mobile."','".$amount."','".$opr."','".$type."',NULL)";
         * $lastId = "SELECT LAST_INSERT_ID() as 'insertId'";
         *
         * if(in_array(strtolower($method),array('cashpgpayment'))){
         * if($reqObj->query($insertQry)){
         * $lastId_arr = $reqObj->query($lastId);
         * return $lastId_arr[0][0]['insertId'];
         * }
         * }elseif($reqObj->save($this->data)){
         * return $reqObj->id;
         * }
         *
         * $reqObj->query("INSERT INTO requests_dropped (retailer_id,method,mobile,amount,operator,timestamp) VALUES (".$_SESSION['Auth']['id'].",'$method','$mobile',$amount,$opr,'".date('Y-m-d H:i:s')."')");
         * return null;
         */
    }

    function deleteAppRequest($mobile, $amount, $opr, $ret_id){
        $this->delMemcache("request_" . $mobile . "_" . intval($amount) . "_" . $opr . "_" . $ret_id);
        /*
         * $reqObj = ClassRegistry::init('Request');
         *
         * $reqObj->query("DELETE FROM requests WHERE mobile='$mobile' AND amount=$amount");
         */
    }

    function addComment($userId, $retId, $transId, $test, $loggedInUser, $name = null, $tag_id, $call_type_id){
        $userObj = ClassRegistry::init('User');

        // if(!empty($test)){
        // $medium = -1;
        // if(!empty($transId)){
        // $medium = $userObj->query("select api_flag from vendors_activations where ref_code = '$transId'");
        // $medium = $medium[0]['vendors_activations']['api_flag'];
        // }

        $userObj->query("insert into comments(users_id,retailers_id,mobile,ref_code,comments,created,tag_id,call_type_id,date) values('$userId','$retId','$loggedInUser','$transId','" . addslashes($test) . "','" . date('Y-m-d H:i:s') . "','" . $tag_id . "','" . $call_type_id . "','" . date('Y-m-d') . "')");
        if( ! empty($transId)){
            $slaveObj = ClassRegistry::init('Slaves');
            $comments_count = $slaveObj->query("select ref_code, count from comments_count where ref_code = '$transId' and date = '" . date('Y-m-d') . "'");
            $va = $slaveObj->query("select vendor_id, product_id, api_flag from vendors_activations where txn_id = '$transId'");
            $userObj->query("UPDATE vendors_activations SET cc_userid = '$userId' WHERE txn_id = '$transId'");

            if($comments_count) $userObj->query("update comments_count set count = count + 1 where ref_code = '$transId' and date = '" . date('Y-m-d') . "'");
            else $userObj->query("insert into comments_count(ref_code, count, vendor_id, product_id, retailer_id, medium, date) values('$transId', 1, " . $va[0]['vendors_activations']['vendor_id'] . ", " . $va[0]['vendors_activations']['product_id'] . ", '$retId', " . $va[0]['vendors_activations']['api_flag'] . ", '" . date('Y-m-d') . "')");
        }
        /*
         * $user = $this->General->getUserDataFromMobile($loggedInUser);
         * $data['user'] = $user['name'];
         * if(!empty($transId)){
         * $txid = $transId;
         * $data['txtype'] = 1;
         *
         * $extra = $userObj->query("SELECT vendors.shortForm,vendors_activations.amount,products.name FROM vendors_activations,vendors,products WHERE products.id = product_id AND vendors.id = vendor_id AND ref_code = '$transId'");
         * $data['extra'] = $extra['0']['vendors']['shortForm'] . " | " . $extra['0']['products']['name'] . " | " . $extra['0']['vendors_activations']['amount'];
         * }
         * else if(!empty($retId)){
         * $retData = $userObj->query("SELECT mobile FROM retailers WHERE id = $retId");
         *
         * $txid = $retData['0']['retailers']['mobile'];
         * $data['txtype'] = 2;
         * }
         * else {
         * $user = $this->General->getUserDataFromId($userId);
         * $txid = $user['mobile'];
         * $data['txtype'] = 3;
         * }
         * $data['txid'] = $txid;
         * $data['process'] = 'cc';
         * $data['msg'] = $test;
         * $this->General->curl_post_async("http://pay1.in/cclive/server.php",$data);
         */

        // }
    }

    function shopCreditDebitUpdate($type, $amount, $from, $to, $to_groupId, $desc, $numbering, $api_flag = null){
        $transObj = ClassRegistry::init('ShopTransaction');
        if($api_flag == null) $api_flag = 0;
        if( ! empty($from)) $transObj->query("INSERT INTO shop_creditdebit (from_id,to_id,to_groupid,amount,type,api_flag,description,numbering,timestamp) VALUES ($from,$to,$to_groupId,$amount,$type,$api_flag,'" . addslashes($desc) . "','$numbering','" . date('Y-m-d H:i:s') . "')");
        else $transObj->query("INSERT INTO shop_creditdebit (to_id,to_groupid,amount,type,api_flag,description,numbering,timestamp) VALUES ($to,$to_groupId,$amount,$type,$api_flag,'" . addslashes($desc) . "','$numbering','" . date('Y-m-d H:i:s') . "')");
    }

    function transactionsOnRetailerActivation($retailer_id, $product, $user_id = null, $amount, $ip = NULL, $apiflag = NULL, $dataSource = null){
        $prodObj = ClassRegistry::init('Product');
        $prodObj->recursive =  - 1;
        $ret_shop = $this->getShopDataById($retailer_id, RETAILER);

        // transaction entries
        $perData = $this->getCommissionPercent($ret_shop['slab_id'], $product,$amount);
        $percent = (isset($perData['percent'])) ? $perData['percent'] : 0;
        $commission_retailer = $percent * $amount / 100;
        // $commission_retailer = $this->calculateCommission($retailer_id,RETAILER,$product,$amount,$ret_shop['slab_id']);

        $distributor_id = $ret_shop['parent_id'];
        $dist_shop = $this->getShopDataById($distributor_id, DISTRIBUTOR);
        $tds_retailer = 0;
        if($commission_retailer > 0){
            if($dist_shop['tds_flag'] == 1 && TAX_MODEL == 0){
                $tds_retailer = $this->calculateTDS($commission_retailer);
                $commission_retailer -= $tds_retailer;
            }
        }

        $service_charge = (isset($perData['service_charge']) &&  ! empty($perData['service_charge'])) ? $perData['service_charge'] : 0;

        if($service_charge > 0){
            if($perData['service_tax'] == 1){
                $service_tax = SERVICE_TAX_PERCENT;
                $service_charge = $service_charge + ($service_charge * $service_tax/ 100);
            }
        }

        if($commission_retailer > 0 && $commission_retailer < $service_charge){
            $service_charge = 0;
        }

        $service_charge = round($service_charge, 2);
        // balance update
        // $amount_distributor = $commission_distributor - $commission_retailer;
        // $amount_superdistributor = $commission_superdistributor - $commission_distributor;
        $amount_retailer = $amount - $commission_retailer + $service_charge;
        $bal = $this->shopBalanceUpdate($amount_retailer, 'subtract', $ret_shop['user_id'], RETAILER, $dataSource);
        if($bal < 0){
            $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', "Negative bal encountered Rolling Back  : {$bal}", FILE_APPEND | LOCK_EX);
            return false;
        }

        if($user_id == null){
            $user_id = $ret_shop['user_id'];
        }

        $trans_id = $this->shopTransactionUpdate(RETAILER_ACTIVATION, $amount, $retailer_id, $product, $user_id, $percent, null, $ip, $bal + $amount_retailer, $bal, null, null, $dataSource);
        if($trans_id === false) return false;

        if($service_charge > 0){
            $ret = $this->shopTransactionUpdate(SERVICE_CHARGE, $service_charge, $retailer_id, $trans_id, $user_id, null, null, null, $bal + $amount_retailer, $bal, null, null, $dataSource);
            if($ret === false) return false;
        }

        if($commission_retailer > 0){
            $ret = $this->shopTransactionUpdate(COMMISSION_RETAILER, $commission_retailer, $retailer_id, $trans_id, $user_id, $percent, null, null, $bal + $amount_retailer, $bal, null, null, $dataSource);
            if($ret === false) return false;
        }

        if($tds_retailer > 0){
            $ret = $this->shopTransactionUpdate(TDS, $tds_retailer, $ret_shop['user_id'], $trans_id, $user_id, null, null, "TDS deducted on recharge (5%) - $trans_id", $bal + $amount_retailer, $bal, null, null, $dataSource);
            if($ret === false) return false;
        }
        // $this->addOpeningClosing($retailer_id, RETAILER, $trans_id, $bal + $amount_retailer, $bal, $dataSource);
        return array('balance'=>$bal, 'shop_transaction_id'=>$trans_id, 'service_charge'=>$service_charge, 'retailer_margin'=>($amount - $amount_retailer));
    }

    /*
     * function addOpeningClosing($shop_id, $group_id, $shoptrans_id, $opening, $closing, $dataSource = null){
     * $prodObj = is_null($dataSource) ? ClassRegistry::init('Product') : $dataSource;
     * $prodObj->query("INSERT INTO opening_closing (shop_id,group_id,shop_transaction_id,opening,closing,timestamp) VALUES ($shop_id,$group_id,$shoptrans_id,$opening,$closing,'" . date('Y-m-d H:i:s') . "')");
     * $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', "Adding Opening Closing entry : ", FILE_APPEND | LOCK_EX);
     * }
     */
    function createVendorActivation($data = array(), $dataSource = null){
        $vendor_id = $data['vendor_id'];
        $product_id = $data['product_id'];
        $mobile = $data['mobile'];
        $amount = $data['amount'];
        $shop_transaction_id = $data['shop_transaction_id'];
        $retailer_margin = $data['retailer_margin'];
        $distributor_id = $data['distributor_id'];
        $retailer_id = $data['retailer_id'];
        $timestamp = date('Y-m-d H:i:s');
        $updated_timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $hr = date('H');
        $api_flag = $data['api_flag'];
        $txn_id = uniqid(rand(0, 1000));
        $param = (isset($data['param'])) ? $data['param'] : '';
        //$this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', 'Creating VA entry statt ' . $sql, FILE_APPEND | LOCK_EX);

        $discount_commission_data = $this->getVendorCommissionData($data['vendor_id'], $data['product_id']);
        $discount_commission = $discount_commission_data['discount_commission'];

        $fixed_commission = $discount_commission_data['commission_fixed'];

        if($fixed_commission > 0) $discount_commission = $fixed_commission*100/$amount;

        $sql = "Insert into vendors_activations(vendor_id,product_id,mobile,amount,shop_transaction_id,retailer_id,timestamp,hr,date,api_flag,txn_id,param,discount_commission,retailer_margin,distributor_id,updated_timestamp)" . "  values('{$vendor_id}','{$product_id}','{$mobile}','{$amount}','{$shop_transaction_id}','{$retailer_id}','{$timestamp}','{$hr}','{$date}','{$api_flag}','{$txn_id}','{$param}','{$discount_commission}','{$retailer_margin}','{$distributor_id}','{$updated_timestamp}') ";

        // $dataSource->query($sql);
        $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', 'Creating VA entry ' . $sql, FILE_APPEND | LOCK_EX);
        if($dataSource->query($sql)){
            $lastInserIdQuery = $dataSource->query("SELECT LAST_INSERT_ID() as id FROM vendors_activations limit 1 ");
            $id = $lastInserIdQuery[0][0]['id'];
            $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', " VA id : {$id} ", FILE_APPEND | LOCK_EX);

            $ref_code = 3022 * 100000000 + intval($id);
            if($dataSource->query("UPDATE vendors_activations SET txn_id = '$ref_code' WHERE id=$id")){
                $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', " Updating ref code in VA refcode: {$ref_code} ", FILE_APPEND | LOCK_EX);
//                return $ref_code;
                return array('ref_code'=>$ref_code,'timestamp'=>$timestamp);
            }
            else{
                $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', " Updating ref code in VA failed ", FILE_APPEND | LOCK_EX);
                return false;
            }
        }
        else{
            $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', " VA query Failed  ", FILE_APPEND | LOCK_EX);
            return false;
        }
    }

    function calculateExpectedEarning($data){
        if($data['vendors']['update_flag'] == 1){
            $exp_earn = $data['earnings_logs']['incoming'] - $data['earnings_logs']['invested'];
        }
        else if($data['earnings_logs']['vendor_id'] == 36){
            $exp_earn = intval($data['earnings_logs']['invested'] * 3.7 / 100);
        }
        else if($data['earnings_logs']['vendor_id'] == 62){
            $exp_earn = intval($data['earnings_logs']['invested'] * 3.5 / 100);
        }



        else if($data['earnings_logs']['vendor_id'] == 65 && $data['earnings_logs']['date'] < date('Y-m-d', strtotime('2018-08-01'))){
                $exp_earn = intval($data['earnings_logs']['invested'] * 3.0 / 100);



        }
        else
            $exp_earn = $data['earnings_logs']['expected_earning'];

        return $exp_earn;
    }

    function createTransaction($prodId, $venId, $api_flag, $mobNo, $amt, $custId = null, $ip = null){
        // $shopData = $this->getShopDataById($_SESSION['Auth']['id'],$_SESSION['Auth']['User']['group_id']);
        $this->General->logData('/mnt/logs/createTransaction.log', "In create Transaction: $mobNo $amt $venId");

        if($api_flag != 4){ // api partner
            $check_wait_time = $this->addMemcache("requested_user_" . $_SESSION['Auth']['id'], $_SESSION['Auth']['id'], 5);

            if( ! $check_wait_time){
                return array('status'=>'failure', 'code'=>'37', 'description'=>$this->errors(37));
            }
        }

        try{
            $this->General->logData('/mnt/logs/createTransaction.log', "Getting retailer balance : $mobNo $amt $venId");

            $balance = $this->getBalance($_SESSION['Auth']['User']['id']);

            if($balance < $amt){
                $this->delMemcache("requested_user_" . $_SESSION['Auth']['id']);
                return array('status'=>'failure', 'code'=>'26', 'description'=>$this->errors(26));
            }
            else{
                /*
                 * Create Transaction
                 * Start
                 */

                $this->General->logData('/mnt/logs/createTransaction.log', "Starting begin transaction : $mobNo $amt $venId");

                $userObj = ClassRegistry::init('User');
                $dataSource = $userObj->getDataSource();
                $dataSource->begin();
                $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', 'Created DataSource Object ', FILE_APPEND | LOCK_EX);

                $ret = $this->transactionsOnRetailerActivation($_SESSION['Auth']['id'], $prodId, null, $amt, $ip, $api_flag, $dataSource);
                if($ret === false){
                    $dataSource->rollback();
                    $this->delMemcache("requested_user_" . $_SESSION['Auth']['id']);
                    return array('status'=>'failure', 'code'=>'30', 'description'=>$this->errors(26));
                }
                $ref_id = $this->createVendorActivation(array('vendor_id'=>$venId, 'product_id'=>$prodId, 'api_flag'=>$api_flag, 'mobile'=>$mobNo, 'param'=>$custId, 'amount'=>$amt, 'shop_transaction_id'=>$ret['shop_transaction_id'], 'retailer_margin'=>$ret['retailer_margin'],
                        'retailer_id'=>$_SESSION['Auth']['id'], 'distributor_id'=>$_SESSION['Auth']['parent_id']), $dataSource);
                // $ref_id = $this->createVendorActivation ( $venId, $prodId, $api_flag, $mobNo, $amt, $ret [1], $_SESSION ['Auth'] ['id'], $custId, $dataSource );
                if($ref_id === false || empty($ref_id)){
                    $req_content = $venId . "|" . $prodId . "|" . $api_flag . "|" . $mobNo . "|" . $amt . "|" . $ret['shop_transaction_id'] . "|" . $_SESSION['Auth']['id'] . "|" . $custId;
                    file_put_contents('/mnt/logs/vendor_activation_failure.log', "vendor_act param : " . $req_content . " : result_ref_id - " . $ref_id . "  var_dump : " . json_encode($ref_id) . " \n", FILE_APPEND | LOCK_EX);
                    $shop_transid = $ret['shop_transaction_id'];
                    $dataSource->rollback();
                    $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', " VA failed", FILE_APPEND | LOCK_EX);
                    /*
                     * $vendorActObj = ClassRegistry::init('VendorsActivation');
                     * $vendorActObj->query("DELETE FROM shop_transactions WHERE id = '$shop_transid'");
                     * $vendorActObj->query("DELETE FROM opening_closing WHERE shop_transaction_id = '$shop_transid'");
                     * $vendorActObj->query("UPDATE retailers SET balance = balance + $amt, modified = '".date('Y-m-d H:i:s')."' WHERE id= '".$_SESSION['Auth']['id']."'");
                     */
                    $this->delMemcache("requested_user_" . $_SESSION['Auth']['id']);
                    return array('status'=>'failure', 'code'=>'30', 'description'=>$this->errors(30));
                }
                else{
                    $dataSource->commit();
                    $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', " DONE ! ! !  ", FILE_APPEND | LOCK_EX);
                    //$this->delMemcache("requested_user_" . $_SESSION['Auth']['id']);
                    return array('status'=>'success', 'tranId'=>$ref_id['ref_code'], 'balance'=>$ret['balance'], 'service_charge'=>$ret['service_charge'],'timestamp'=>$ref_id['timestamp']);
                }
            }
        }
        catch(Exception $e){

            $dataSource->rollback();
            $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', 'in Catch Exception occured ', FILE_APPEND | LOCK_EX);
            $this->General->logData('/mnt/log/TranQuery' . date('Y-m-d') . '.log', 'Exception Encountered : ' . json_encode($e), FILE_APPEND | LOCK_EX);
            return array('status'=>'failure', 'code'=>'60', 'description'=>$this->errors(60));
        }
    }

    function setProdVendorHealth($vendor_id, $prod_id, $status){
        $info = $this->getVendorInfo($vendor_id);

        $map_arr = array('9'=>array(9, 10, 27), '10'=>array(9, 10, 27), '27'=>array(9, 10, 27), '11'=>array(11, 29), '29'=>array(11, 29), '7'=>array(7, 8), '8'=>array(7, 8), '7'=>array(7, 8), '12'=>array(12, 28), '28'=>array(12, 28), '3'=>array(3, 34), '34'=>array(3, 34), '30'=>array(30, 31),
                '31'=>array(30, 31));
        $prod_arr = array('7'=>'8', '10'=>'9', '27'=>'9', '28'=>'12', '29'=>'11', '31'=>'30', '34'=>'3');

        $prod_id = isset($prod_arr[$prod_id]) ? $prod_arr[$prod_id] : $prod_id;
        $key = "health_$vendor_id" . "_" . $prod_id . "_" . date('Ymd');

        $cap_inprocess_limit = $this->getMemcache("cap_inprocess_$prod_id" . "_$vendor_id");
        $max_cap = ( ! empty($cap_inprocess_limit)) ? ($cap_inprocess_limit * 4) : 50;

        $get = $this->getMemcache($key);
        $prods = isset($map_arr[$prod_id]) ? $map_arr[$prod_id] : array($prod_id);

        if($info['update_flag'] == 0){
            if($status == 1){
                if($get === false){
                    $this->setMemcache($key, 0, 2 * 60 * 60);
                }
                else if($get > 0){
                    $health_percent = 45;
                    $val = $this->decrementMemcache($key);
                    if($val < ($max_cap * $health_percent) / 100 && $this->getMemcache("disabled_$vendor_id" . "_" . $prod_id)){
                        $vendorActObj = ClassRegistry::init('VendorsActivation');

                        $prod_name = array();
                        foreach($prods as $p){
                            $ex_data = $vendorActObj->query("SELECT oprDown FROM vendors_commissions WHERE vendor_id = $vendor_id AND product_id = $p");
                            if($ex_data[0]['vendors_commissions']['oprDown'] == 1){
                                $vendorActObj->query("UPDATE vendors_commissions SET oprDown = 0 WHERE vendor_id = $vendor_id AND product_id = $p AND oprDown = 1");
                                $prodInfo = $this->setProdInfo($p);
                                $prod_name[] = $prodInfo['name'];
                            }
                        }
                        if( ! empty($prod_name)){
                            $this->General->sendMails("(SOS)Enabling " . implode(",", $prod_name) . " of vendor " . $info['company'], "Transactions cleared so activated it automatically", array('backend@pay1.in'), 'mail');
                            $this->delMemcache("disabled_$vendor_id" . "_" . $prod_id);
                            $this->General->logData('/mnt/logs/vendor_status.txt', "Enabling : $vendor_id" . "_" . $prod_id."::health value::$val");
                        }
                        // $this->setMemcache($key,0,30*60);
                    }

                    $this->General->logData('/mnt/logs/vendor_status.txt', "decrement:: $key: $val");
                }
            }
            else if($status == 0){
                if($get === false){
                    $this->setMemcache($key, 1, 2 * 60 * 60);
                }
                else{
                    $val = $this->incrementMemcache($key);
                    if($val > $max_cap){
                        $vendorActObj = ClassRegistry::init('VendorsActivation');
                        $prod_name = array();
                        foreach($prods as $p){
                            $ex_data = $vendorActObj->query("SELECT oprDown FROM vendors_commissions WHERE vendor_id = $vendor_id AND product_id = $p");
                            if($ex_data[0]['vendors_commissions']['oprDown'] == 0){
                                $vendorActObj->query("UPDATE vendors_commissions SET oprDown = 1 WHERE vendor_id = $vendor_id AND product_id = $p AND oprDown = 0");
                                $prodInfo = $this->setProdInfo($p);
                                $prod_name[] = $prodInfo['name'];
                            }
                        }
                        if( ! empty($prod_name)){
                            $this->General->sendMails("(SOS)Disabling " . implode(",", $prod_name) . " of vendor " . $info['company'], "Lot of transactions are going in process. Kindly check and activate it manually", array('backend@pay1.in'), 'mail');
                            // $this->setMemcache($key,0);
                            $this->setMemcache("disabled_$vendor_id" . "_" . $prod_id, 1);
                            $this->General->logData('/mnt/logs/vendor_status.txt', "Disabling : $vendor_id" . "_" . $prod_id."::health value::$val");
                        }
                    }
                    $this->General->logData('/mnt/logs/vendor_status.txt', "increment:: $key: $val");
                }
            }
        }
    }

    /*
     * function updateTransaction($tranId,$venTranId,$status,$code,$desc,$extra=null,$opr_id=null,$vendor_id=null,$prod_id=null,$discount_comm=null){
     * if($status == 'success') {
     * $status = TRANS_SUCCESS;
     * }
     * else if($status == 'failure') {
     * $status = TRANS_REVERSE;
     * }
     * else if($status == 'pending') {
     * $status = 0;
     * }
     * if(!empty($vendor_id) && !empty($prod_id))$this->setProdVendorHealth($vendor_id,$prod_id,$status);
     * if(is_null($extra))
     * $extra = '';
     *
     * $this->updateVendorActivation($tranId,$venTranId,$status,$code,$desc,$extra,$opr_id,$vendor_id,$discount_comm);
     * $this->unlockTransaction($tranId);
     *
     * if($status == TRANS_REVERSE){
     * //$err = ($code == 5) ? 4 : (($code == 6) ? 3 : null);
     * $this->reverseTransaction($tranId,1,$code);
     * }
     * }
     */
    function checkStatus($transId, $vendor_id){
        $vendors = $this->addMemcache("transCheck$transId@$vendor_id", 1, 5 * 60);
        // $this->General->logData("/tmp/status.txt",date('Y-m-d H:i:s')."::$transId: ".json_encode($vendors));

        if($vendors === false){
            return false;
        }
        else{
            $this->delMemcache("transCheck$transId@$vendor_id");
            return true;
        }
        /*
         * if(!in_array($vendor_id,$vendors)){
         * return true;
         * }
         */
    }

    function autopullbackTransaction($transId, $vendorId, $dbObj){
        $this->General->logData("pullback.txt","SELECT va.id,va.retailer_id,va.shop_transaction_id,va.retailer_margin,va.amount,va.vendor_refid,retailers.mobile,va.status,products.service_id FROM vendors_activations as va inner join products ON (products.id = product_id) inner join retailers ON (retailers.id = retailer_id) WHERE txn_id = '$transId' AND status in (2,3)");

        $result = $dbObj->query("SELECT va.id,va.retailer_id,va.shop_transaction_id,va.retailer_margin,va.amount,va.vendor_refid,retailers.mobile,va.status,products.service_id FROM vendors_activations as va inner join products ON (products.id = product_id) inner join retailers ON (retailers.id = retailer_id) WHERE txn_id = '$transId' AND status in (2,3)");
        if(empty($result)) return;
        $dbObj = (empty($dbObj)) ? ClassRegistry::init('User') : $dbObj;
        $shop_id = $result['0']['va']['shop_transaction_id'];

        $this->General->logData("pullback.txt","INside autobullback: $transId $vendorId");
        if($this->lockReverseTransaction($shop_id, $dbObj)){
            $this->unlockReverseTransaction($shop_id, $dbObj);
            return;
        }
        $this->General->logData("pullback.txt","INside autobullback 1: $transId $vendorId");

        $this->Recharge->update_in_vendors_activations(array('vendor_id'=>$vendorId, 'prevStatus'=>$result['0']['va']['status'], 'status'=>1, 'cc_userid'=>'', 'reversal_date'=>''), array('txn_id'=>$transId), $dbObj);

        $dbObj->query("UPDATE shop_transactions SET confirm_flag=1 WHERE id=$shop_id");
        $this->Recharge->log_in_vendor_message(array('va_tran_id'=>$transId, 'vendor_refid'=>$result['0']['va']['vendor_refid'], 'service_id'=>$result['0']['products']['service_id'], 'service_vendor_id'=>$vendorId, 'internal_error_code'=>'13', 'response'=>'Auto Pulled back by System',
                'status'=>'success', 'timestamp'=>date('Y-m-d H:i:s'), 'vm_date'=>date('Y-m-d')), $dbObj);

        $this->General->logData("pullback.txt","INside autobullback 2: $transId $vendorId");

        $amt = $result['0']['va']['amount'] - $result['0']['va']['retailer_margin'];
        $ret_id = $result['0']['va']['retailer_id'];

        $ret_shop = $this->getShopDataById($ret_id, RETAILER);

        $bal = $this->shopBalanceUpdate($amt, 'subtract', $ret_shop['user_id'], RETAILER, $dbObj);
        $this->General->logData("pullback.txt","INside autobullback 3: $transId $vendorId");

        // $this->addOpeningClosing($ret_id, RETAILER, $shop_id, $bal + $amt, $bal, $dbObj);

        $this->unlockReverseTransaction($shop_id, $dbObj);
        $dbObj->query("DELETE FROM shop_transactions WHERE target_id = $shop_id AND type = " . REVERSAL_RETAILER);

        $dbObj->query("INSERT INTO trans_pullback (id,vendors_activations_id,vendor_id,status,timestamp,pullback_by,pullback_time,reported_by,date) values('','" . $result['0']['va']['id'] . "','" . $vendorId . "','1','" . date('Y-m-d H:i:s') . "','','" . date('Y-m-d H:i:s') . "','Auto-pullback','" . date('Y-m-d') . "')");
        $this->General->logData("pullback.txt","INside autobullback 4: $transId $vendorId");

        $paramdata = array();
        if(empty($result['0']['va']['param'])){
            $paramdata['PULLBACKTO'] = "Mobile: " . $result['0']['va']['mobile'] . "\n";
        }
        else{
            $paramdata['PULLBACKTO'] = "Subscriber Id: " . $result['0']['va']['param'] . "\n";
        }

        $paramdata['PULLED_AMOUNT'] = $amt;
        $paramdata['TRANSID'] = substr($transId,  - 5);
        $paramdata['AMOUNT'] = $result['0']['va']['amount'];
        $paramdata['BALANCE'] = $bal;

        $MsgTemplate = $this->General->LoadApiBalance();
        $content = $MsgTemplate['Panels_Pullback_MSG'];
        $msg = $this->General->ReplaceMultiWord($paramdata, $content);

        $this->General->sendMessage($result['0']['retailers']['mobile'], $msg, 'notify');
    }

    function addStatus($transId, $vendor_id){
        $vendors = $this->setMemcache("transCheck$transId@$vendor_id", 1, 10 * 60);
        $this->General->logData("/mnt/logs/status.txt", date('Y-m-d H:i:s') . "::$transId: 1" . json_encode($vendors));
    }

    function deleteStatus($transId, $vendor_id){
        $vendors = $this->delMemcache("transCheck$transId@$vendor_id");
        $this->General->logData("/mnt/logs/status.txt", date('Y-m-d H:i:s') . "delete status::$transId: 1" . json_encode($vendors));
    }

    function lockTransactionDuplicates($prod, $number, $amount, $api_flag){
        $prod = $this->getOtherProds($prod);
        $prod = str_replace(",", "_", $prod);
        $ret = false;
        $ret1 = $this->addMemcache("dup_$number" . "_" . $prod . "_$amount", $number, TIME_DURATION * 60);
        if($ret1 !== false) $ret = true;

        /*
         * $vendorActObj = ClassRegistry::init('VendorsActivation');
         * if($vendorActObj->query("INSERT INTO temp_repeattxn VALUES (NULL,'$number','".addslashes($prod)."','$amount','$api_flag','".date('Y-m-d H:i:s',strtotime('+ '.TIME_DURATION.' minutes'))."')")){
         * $ret = true;
         * }
         */

        return $ret;
    }

    /*
     * function lockTransactionVendor($transId,$vendor_name,$vendor_id){
     * //$ret = $this->addMemcache("txn$transId"."_$vendor_id",1,10*60);
     *
     * $ret1 = false;
     * $vendorActObj = ClassRegistry::init('VendorsActivation');
     * if($vendorActObj->query("INSERT INTO temp_transaction VALUES (NULL,'$transId','$vendor_name','$vendor_id','".date('Y-m-d')."','".date('Y-m-d H:i:s')."')")){
     * $ret1 = true;
     * }
     * else $ret1 = false;
     *
     * return $ret1;
     * }
     */
    function lockTransaction($transId){
        $ret1 = false;
        $ret = $this->addMemcache("temp$transId", 1, 10 * 60);
        if($ret !== false){
            $ret1 = true;
        }

        return $ret1;
    }

    function lockReverseTransaction($transId, $dataSource){
        if($dataSource->query("INSERT INTO temp_reversed VALUES (NULL,'$transId','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "')")){
            return true;
        }
        return false;
    }

    function lockBankTransaction($bank, $transId){
        $vendorActObj = ClassRegistry::init('VendorsActivation');
        $ret = false;
        if($vendorActObj->query("INSERT INTO bank_transactions VALUES (NULL,'$bank','" . addslashes($transId) . "')")){
            $ret = true;
        }

        return $ret;
    }

    function unlockReverseTransaction($transId, $dbObj = null){
        $vendorActObj = (empty($dbObj)) ? ClassRegistry::init('VendorsActivation') : $dbObj;
        $vendorActObj->query("DELETE FROM temp_reversed WHERE shoptrans_id = '$transId'");
        return true;
    }

    function unlockTransactionDuplicates($prod, $number, $amount){
        $prod = $this->getOtherProds($prod);
        $prod = str_replace(",", "_", $prod);
        $this->delMemcache("dup_$number" . "_" . $prod . "_$amount");
        /*
         * $amount = intval($amount);
         * $vendorActObj = ClassRegistry::init('VendorsActivation');
         * $vendorActObj->query("DELETE FROM temp_repeattxn WHERE (number='$number' AND amount='$amount' AND prods='$prod') OR (timestamp < '".date('Y-m-d H:i:s')."')");
         */
        return true;
    }

    function unlockTransaction($transId){
        $ret = $this->delMemcache("temp$transId");
        /*
         * $vendorActObj = ClassRegistry::init('VendorsActivation');
         * $vendorActObj->query("DELETE FROM temp_txn WHERE ref_code='$transId'");
         */

        return true;
    }

    function reversalDeclined($ref_id, $send){
        $vendorActObj = ClassRegistry::init('VendorsActivation');
        $data = $vendorActObj->query("SELECT vendors_activations.id,vendors_activations.status,vendors_activations.mobile,vendors_activations.product_id,vendors_activations.retailer_id,resolve_flag FROM vendors_activations left join complaints ON (vendors_activations.id = vendor_activation_id AND resolve_flag = 0) where txn_id='" . $ref_id . "'");

        if($data['0']['vendors_activations']['status'] == TRANS_REVERSE_PENDING || $data['0']['complaints']['resolve_flag'] == '0'){
            $this->Recharge->update_in_vendors_activations(array('status'=>TRANS_REVERSE_DECLINE, 'cc_userid'=>$_SESSION['Auth']['User']['id']), array('txn_id'=>$ref_id));
            $ret = $vendorActObj->query("SELECT retailers.mobile,vendors_activations.*,products.service_id,products.name FROM vendors_activations join retailers on ( vendors_activations.retailer_id = retailers.id) join products on (vendors_activations.product_id = products.id) where vendors_activations.txn_id='" . $ref_id . "'");
            $vendorActObj->query("UPDATE complaints SET resolve_flag=1,closedby='" . $_SESSION['Auth']['User']['id'] . "',resolve_date='" . date('Y-m-d') . "',resolve_time='" . date('H:i:s') . "' WHERE vendor_activation_id = " . $data['0']['vendors_activations']['id']);

            $msg = 'Complaint for Trans Id ' . substr($ret['0']['vendors_activations']['txn_id'],  - 5) . ' is resolved';
            $msg .= "\nTransaction is successful";
            $msg .= "\nDate: " . date('j M, g:i A', strtotime($ret['0']['vendors_activations']['timestamp']));
            if($ret['0']['products']['service_id'] == 2){
                $msg .= "\nSub Id: " . $ret['0']['vendors_activations']['param'];
                $msg .= "\nOprId: " . $ret['0']['vendors_activations']['operator_id'];
            }
            $msg .= "\nMob: " . $ret['0']['vendors_activations']['mobile'];
            $msg .= "\nOpr: " . $ret['0']['products']['name'];
            $msg .= "\nAmt: " . $ret['0']['vendors_activations']['amount'];

            if($send == 1) $this->General->sendMessage($ret['0']['retailers']['mobile'], $msg, 'notify');

            if($data['0']['vendors_activations']['retailer_id'] == B2C_RETAILER){
                $partnerLogs = $vendorActObj->query("SELECT partners_log.partner_req_id FROM partners_log left join vendors_activations ON (partners_log.vendor_actv_id = vendors_activations.txn_id) WHERE vendors_activations.txn_id = '$ref_id'");

                if(empty($partnerLogs)) return;
                $url = B2C_URL.'actiontype/resolve_complain/api/true';
                $data = array('transaction_id'=>$partnerLogs['0']['partners_log']['partner_req_id']);
                $this->General->curl_post_async($url, $data);
            }
        }
        return;
    }

    function reverseTransaction($tranId, $update = null, $error = null, $group_id = null, $user_id = null, $setUsr = 0){
        try{
            if($group_id != SUPER_ADMIN && empty($update)){
                if( ! $this->Recharge->lockTransaction($tranId)){
                    throw new Exception("Transaction is locked");
                }
            }

            $datasource = ClassRegistry::init('User');
            $vendorActObj = $datasource->getDataSource();
            $vendorActObj->begin();




            $data = $vendorActObj->query("SELECT vendors_activations.*,products.service_id,products.name,products.type,vendors_commissions.type,vendors.update_flag,vendors.ip,vendors.port,products.earning_type,products.earning_type_flag,products.expected_earning_margin "


                    . "FROM vendors_activations,products,vendors,vendors_commissions "
                    . "WHERE vendors.id = vendors_activations.vendor_id "
                    . "AND products.reverse_flag = 1 "
                    . "AND products.id = vendors_activations.product_id "
                    . "AND vendors_commissions.vendor_id=vendors.id AND vendors_commissions.product_id=products.id "
                    . "AND vendors_activations.txn_id = '$tranId'");

            if(empty($data)){
                throw new Exception("Either data is not present or its a cash pg transactions");
            }
            $shop_trans_id = $data['0']['vendors_activations']['shop_transaction_id'];

            $this->lockReverseTransaction($shop_trans_id, $vendorActObj);

            $date = $data['0']['vendors_activations']['date'];

            if($data['0']['vendors_activations']['status'] == TRANS_SUCCESS && $data['0']['vendors']['update_flag'] == 1 &&  ! empty($data['0']['vendors_activations']['operator_id'])){
                throw new Exception("Reversal of Successful txn of modems cannot be done");
            }

            if($data['0']['vendors_activations']['status'] == TRANS_SUCCESS &&  empty($group_id)){
                throw new Exception("Reversal of Successful txn cannot be done automatically");
            }

            if($date < date('Y-m-d', strtotime('-30 days'))){
                throw new Exception("txn is more than 30 days from backend admin");
            }
            else if($date < date('Y-m-d', strtotime('-7 days')) && $date >= date('Y-m-d', strtotime('-30 days')) && $group_id != BACKEND_ADMIN){ // backend operation expert
                throw new Exception("txn is more than 7 days from backend operation expert");
            }
            else if($date < date('Y-m-d', strtotime('-3 days')) && $date >= date('Y-m-d', strtotime('-7 days')) &&  ! in_array($group_id, array(BACKEND_OPERATION_EXPERT, BACKEND_ADMIN))){ // backend operation expert
                throw new Exception("txn is more than 7 days from backend operation expert");
            }
            else{ // other group and cc after 30 mins.
                $currtime = date('Y-m-d H:i:s', strtotime('-30 minutes'));
                $timestamp = $data['0']['vendors_activations']['timestamp'];

                if($group_id == CUSTCARE && $timestamp > $currtime){
                    throw new Exception("txn is less than 30 minutes");
                }
            }

            $ret_id = $data['0']['vendors_activations']['retailer_id'];
            $v_id = $data['0']['vendors_activations']['vendor_id'];
            $p_id = $data['0']['vendors_activations']['product_id'];
            $amount = $data['0']['vendors_activations']['amount'];
            $retailer_margin = $data['0']['vendors_activations']['retailer_margin'];
            $service_charge = $data['0']['vendors_activations']['retailer_margin'] < 0?abs($data['0']['vendors_activations']['retailer_margin']):0;
            $commission = $data['0']['vendors_activations']['retailer_margin'] > 0?$data['0']['vendors_activations']['retailer_margin']:0;
            $api_flag = $data['0']['vendors_activations']['api_flag'];
            $service_id = $data['0']['products']['service_id'];
            $product_type = ($data['0']['products']['type'] == 0 && $data['0']['vendors_commissions']['type'] == 0) ? 0 : 1;

            if($data['0']['products']['service_id'] == 4 && $data['0']['vendors_activations']['api_flag'] != 4){
                $msg_user = "Dear User\nYour request of bill payment of Rs $amount declined from your operator. Please take your money back if already paid to your retailer\nYour pay1 txnid: $tranId";
                $this->General->sendMessage(array($data['0']['vendors_activations']['mobile']), $msg_user, 'shops');
            }


            $ret_amount = $amount - $retailer_margin;

            if($date != date('Y-m-d')) $type_flag = 1;
            else $type_flag = 0;

            if($data['0']['vendors_activations']['status'] == TRANS_SUCCESS && $data['0']['vendors']['update_flag'] == 1){
                $this->General->sendMails('Modem: Txn failed after success', $tranId, array('chirutha@pay1.in', 'backend@pay1.in'), 'mail');
            }
            else if($data['0']['vendors_activations']['status'] == TRANS_SUCCESS && $data['0']['vendors']['update_flag'] == 0){
                $this->General->sendMails('API: Txn failed after success', $tranId, array('chirutha@pay1.in', 'backend@pay1.in','naziya.khan@pay1.in'), 'mail');
            }


            $shop_data = $this->getShopDataById($ret_id, RETAILER);

            if($user_id == null){
                $user_id = $shop_data['user_id'];
            }

            $bal = $this->shopBalanceUpdate($ret_amount, 'add', $shop_data['user_id'], RETAILER, $vendorActObj);

            $this->shopTransactionUpdate(REVERSAL_RETAILER, $ret_amount, $ret_id, $shop_trans_id, $user_id, null, $type_flag, null, $bal - $ret_amount, $bal, null, null, $vendorActObj);


            $vendorActObj->query("UPDATE shop_transactions SET confirm_flag = 0 WHERE id = $shop_trans_id");

            if($type_flag == 1){
                //$vendorActObj->query("UPDATE retailers_logs SET sale = sale - $amount,earning=earning-$retailer_margin WHERE retailer_id = '$ret_id' AND date = '$date'");

//                $vendorActObj->query("UPDATE retailer_earning_logs rel "
//                                    . "JOIN retailers r "
//                                    . "ON (rel.ret_user_id=r.user_id) "
//                                    . "SET rel.amount = rel.amount - $amount,rel.earning=rel.earning-$retailer_margin "
//                                    . "WHERE r.id = '$ret_id' "
//                                    . "AND rel.date = '$date' "
//                                    . "AND rel.service_id='$service_id' "
//                                    . "AND rel.product_type='$product_type' AND rel.api_flag=$api_flag AND rel.type in (4,16)");

                $vendor_comm = $data['0']['vendors_activations']['discount_commission']*$amount/100;
                $vendorActObj->query("UPDATE api_vendors_sale_data SET sale = sale - $amount,commission = commission - $vendor_comm WHERE vendor_id = $v_id AND product_id = $p_id AND date = '$date'");
                $vendorActObj->query("UPDATE earnings_logs SET sale = sale - $amount,expected_earning=expected_earning-$vendor_comm WHERE vendor_id = $v_id AND date = '$date'");
                $vendorActObj->query("UPDATE retailer_earning_logs rel "
                        . "JOIN retailers r "
                        . "ON (rel.ret_user_id=r.user_id) "
                        . "SET txn_count=txn_count-1,rel.amount = rel.amount - $amount,rel.earning=rel.earning-$retailer_margin,rel.expected_earning=rel.expected_earning-$expected_earning,rel.commission=rel.commission-$commission,rel.service_charge=rel.service_charge-$service_charge "
                        . "WHERE r.id = '$ret_id' "
                        . "AND rel.date = '$date' "
                        . "AND rel.service_id='$service_id' "
                        . "AND rel.txn_type='$earning_type' AND rel.txn_type_flag='$earning_type_flag' AND rel.api_flag=$api_flag AND rel.type = ".DEBIT_NOTE." ");

            }
            else {
                $vendorActObj->query("UPDATE retailer_earning_logs rel "
                        . "JOIN retailers r "
                        . "ON (rel.ret_user_id=r.user_id) "
                        . "SET txn_count=txn_count-1,rel.amount = rel.amount - $amount,rel.earning=rel.earning-$retailer_margin,rel.expected_earning=rel.expected_earning-$expected_earning,closing_amt=closing_amt-$amt,closing_txn_count=closing_txn_count-1,rel.commission=rel.commission-$commission,rel.service_charge=rel.service_charge-$service_charge "
                        . "WHERE r.id = '$ret_id' "
                        . "AND rel.date = '$date' "
                        . "AND rel.service_id='$service_id' "
                        . "AND rel.txn_type='$earning_type' AND rel.txn_type_flag='$earning_type_flag' AND rel.api_flag=$api_flag AND rel.type = ".DEBIT_NOTE." ");

                $vendorActObj->query("UPDATE api_vendors_sale_data SET sale = sale - $amount,commission = commission - $retailer_margin WHERE vendor_id = $v_id AND product_id = $p_id AND date = '$date'");
                $vendorActObj->query("UPDATE earnings_logs SET sale = sale - $amount WHERE vendor_id = $v_id AND date = '$date'");

            }
            // $this->addOpeningClosing($ret_id, RETAILER, $shop_trans_id, $bal - $ret_amount, $bal, $vendorActObj);
            $this->General->logData("/var/log/pay1_reverse.log", date('Y-m-d H:i:s') . " : $tranId: reversed successfully");

            if($date <= date('Y-m-d', strtotime('-1 days')) && $data['0']['vendors']['update_flag'] == 1){
                $this->updateDeviceData($tranId, $data['0']['vendors_activations']['vendor_id'], $data['0']['vendors_activations']['product_id'], $data['0']['vendors_activations']['amount'], true, $vendorActObj);
            }

            $bal = round($bal, 2);
            // inform retailer by sms

            if(empty($user_id)) $user_id = 0;

            $vendorActObj->query("UPDATE complaints SET resolve_flag=1,closedby='" . (empty($_SESSION['Auth']['User']['id']) ? $user_id : $_SESSION['Auth']['User']['id']) . "',resolve_date='" . date('Y-m-d') . "',resolve_time='" . date('H:i:s') . "' WHERE vendor_activation_id = " . $data['0']['vendors_activations']['id']);

            $this->General->logData("/var/log/pay1_reverse.log", date('Y-m-d H:i:s') . " : $tranId: updating complaints table::UPDATE complaints SET resolve_flag=1,closedby='" . (empty($_SESSION['Auth']['User']['id']) ? $user_id : $_SESSION['Auth']['User']['id']) . "',resolve_date='" . date('Y-m-d') . "',resolve_time='" . date('H:i:s') . "' WHERE vendor_activation_id = " . $data['0']['vendors_activations']['id']);

            $vendorActObj->query("UPDATE api_transactions SET flag=2 WHERE txn_id = '$tranId' AND vendor_id = " . $data['0']['vendors_activations']['vendor_id']);

            $err_code = "E013";

            $error = empty($error) ? 47 : $error;

            if($error == 37){
                $err_code = 'E012';
            }
            else if($error == 5){
                if($data['0']['products']['service_id'] == 1) $err_code = 'E008';
                else if($data['0']['products']['service_id'] == 2) $err_code = 'E009';
            }
            else if($error == 6){
                $err_code = 'E010';
            }
            else{
                $err_code = 'E013';
            }

            $cc_userid = $setUsr == 1 ? (empty($_SESSION['Auth']['User']['id']) ? $user_id : $_SESSION['Auth']['User']['id']) : 0;
            $this->Recharge->update_in_vendors_activations(array('prevStatus'=>$data['0']['vendors_activations']['status'], 'status'=>TRANS_REVERSE, 'code'=>$error, 'cause'=>addslashes($this->errors($error)), 'reversal_date'=>date('Y-m-d'),
                    'cc_userid'=>$cc_userid), array('txn_id'=>$tranId), $vendorActObj);
            // if($_SESSION['Auth']['User']['id'])
            // $this->Recharge->log_in_vendor_message(array('va_tran_id'=>$tranId,'vendor_refid'=>$data['0']['vendors_activations']['vendor_refid'],'service_id'=>$data['0']['products']['service_id'],'service_vendor_id'=>$data['0']['vendors_activations']['vendor_id'],'internal_error_code'=>14,'response'=>addslashes('Manual reversal by '.$usrName),'status'=>'failure','timestamp'=>date("Y-m-d H:i:s"),'vm_date'=>date('Y-m-d')),$vendorActObj);

            if($data['0']['vendors_activations']['api_flag'] == 4){
                // update partners log entry
                $vendorActObj->query("UPDATE partners_log SET err_code= '$err_code',description = '" . $this->apiErrors($err_code) . "' WHERE vendor_actv_id= '" . $tranId . "'");

                // call status_update url of api vendors ( pay1 api client )
                // $partnerRegObj = ClassRegistry::init('Partner');
                $partnerLog = $vendorActObj->query("SELECT partners_log.id,partners_log.partner_req_id,partners.status_update_url,partners.acc_id,partners.password FROM partners,partners_log WHERE partners.id = partners_log.partner_id AND partners_log.vendor_actv_id = '$tranId'");
                $status_update_url = $partnerLog[0]['partners']['status_update_url'];
                if( ! empty($status_update_url)){

                    $partnerId = $partnerLog['0']['partners']['acc_id'];
                    $pay1_trans_id = "2082" . sprintf('%06d', $partnerLog['0']['partners_log']['id']);
                    $client_trans_id = $partnerLog['0']['partners_log']['partner_req_id'];
                    $open_bal = $bal - $ret_amount;
                    $close_bal = $bal;

                    $hash = sha1($partnerId . $client_trans_id . $partnerLog['0']['partners']['password']); // order -> acc_id + transId + refCode + password

                    $sdata = array('open_bal'=>$open_bal, 'close_bal'=>$close_bal, 'trans_id'=>$pay1_trans_id, 'client_req_id'=>$client_trans_id, 'status'=>'failure', 'err_code'=>$err_code, 'description'=>$this->apiErrors($err_code), 'hash_code'=>$hash, 'vendor_code'=>'Signal7');
                    $this->General->curl_post($status_update_url, $sdata,'POST',30,10,true,true,false,true);
                }
            }
            else{
                $tranId = substr($tranId,  - 5);
                $msg = "";
                if( ! empty($error)){
                    $msg .= "Reason:" . $this->apiErrors($err_code) . "\n";
                }
                $msg .= "Reversal of Rs." . $ret_amount . " is done.\n";

                $msg .= "Trans Id: $tranId\n";
                $msg .= "Operator: " . $data['0']['products']['name'] . "\n";
                if(in_array($data['0']['products']['service_id'], array(1, 3, 4, 5))){
                    $msg .= "Mobile: " . $data['0']['vendors_activations']['mobile'] . "\n";
                    $this->deleteAppRequest($data['0']['vendors_activations']['mobile'], $data['0']['vendors_activations']['amount'], $data['0']['vendors_activations']['product_id'], $data['0']['vendors_activations']['retailer_id']);
                }
                else if($data['0']['products']['service_id'] == 2 || $data['0']['products']['service_id'] == 6){
                    $msg .= "Subscriber Id: " . $data['0']['vendors_activations']['param'] . "\n";
                    $this->deleteAppRequest($data['0']['vendors_activations']['param'], $data['0']['vendors_activations']['amount'], $data['0']['vendors_activations']['product_id'], $data['0']['vendors_activations']['retailer_id']);

                    // $this->General->logData("deleteappreq.txt","mobile =>".$data['0']['vendors_activations']['param']."<br/>"."amount=>".$data['0']['vendors_activations']['amount']."</br>"."operator=>".$data['0']['vendors_activations']['product_id']."<br/>"."retailerid=>".$data['0']['vendors_activations']['retailer_id']."msg=>request deleted successfully!!!!");
                }
                $msg .= "Amount: " . $data['0']['vendors_activations']['amount'] . "\nYour current balance is Rs." . $bal;
                $this->General->sendMessage($shop_data['mobile'], $msg, 'notify');
                /* ------------------- */
                // if($data['0']['vendors_activations']['mobile'] == '9819032643'){
                $req_reverse_data = array('mobile'=>$data['0']['vendors_activations']['mobile'], 'trans_id'=>$data['0']['vendors_activations']['txn_id'], 'amount'=>$data['0']['vendors_activations']['amount']);
                $this->B2cextender->manage_request_from_b2c_user($req_reverse_data, 'failure');
                // }
                /* ------------------- */
            }

            $this->General->logData("/var/log/pay1_reverse.log", date('Y-m-d H:i:s') . " : just before memcache for repeat recharge delete");

            $this->delMemcache("recharge_" . $data['0']['vendors_activations']['retailer_id'] . "_" . $data['0']['vendors_activations']['product_id'] . "_" . $data['0']['vendors_activations']['mobile'] . "_" . $data['0']['vendors_activations']['amount']);
            $this->unlockTransactionDuplicates($data['0']['vendors_activations']['product_id'],  ! empty($data['0']['vendors_activations']['param']) ? $data['0']['vendors_activations']['param'] : $data['0']['vendors_activations']['mobile'], $data['0']['vendors_activations']['amount']);
            $vendorActObj->commit();
        }
        catch(Exception $e){
            $this->General->logData("/var/log/pay1_reverse.log", date('Y-m-d H:i:s') . " : $tranId: " . $e->getMessage());
            if($group_id != SUPER_ADMIN && empty($update)){
                $this->Recharge->unlockTransaction($tranId);
            }
            $vendorActObj->rollback();

            return;
        }
    }

    function updateDeviceData($transId, $vendor_id, $product_id, $amount, $reverse_flag = true, $vendorActObj = null){
        $vendorActObj = (empty($vendorActObj)) ? ClassRegistry::init('VendorsActivation') : $vendorActObj;
        $data = $vendorActObj->query("SELECT sim_num,vm_date FROM `vendors_messages` WHERE `va_tran_id` = '$transId' AND service_vendor_id = '$vendor_id' AND sim_num is not null order by id desc limit 1");
        if( ! empty($data)){
            $sim_num = $data[0]['vendors_messages']['sim_num'];
            $date = $data[0]['vendors_messages']['vm_date'];
            $prod_id = $this->getOtherProds($product_id);
            $str = ($reverse_flag) ? "+" : "-";
            $vendorActObj->query("UPDATE devices_data SET server_diff = server_diff $str $amount WHERE opr_id in ($prod_id) AND mobile='$sim_num' AND vendor_id = $vendor_id AND sync_date='$date'");
        }
    }

    function openservice_redis(){
        try{
            App::import('Vendor', 'Predis', array('file'=>'Autoloader.php'));
            Predis\Autoloader::register();
            $this->openredis = new Predis\Client(array('host'=>MODEM_REDIS_HOST, 'password'=>MODEM_REDIS_PASSWORD, 'port'=>MODEM_REDIS_PORT, 'persistent'=>true));
        }
        catch(Exception $e){
            echo "Couldn't connected to Redis";
            echo $e->getMessage();
            $this->General->logData('/mnt/logs/redis_connector' . date('Y-m-d') . '.log', "issue in openservice_redis : " . $e->getMessage(), FILE_APPEND | LOCK_EX);
            $this->openredis = false;
        }
        return $this->openredis;
    }

    function modemRequest($query, $vendor = 4, $data = null, $timeout = 45){
        if(empty($data)) $data = $this->getVendorInfo($vendor);
        $uuid = $vendor . "_" . time() . rand(1, 99999);
        $query .= "&vendor_id=$vendor&uuid=$uuid&reqtime=" . time();
        parse_str($query, $params);
        $arr_map = array('7'=>'8', '10'=>'9', '27'=>'9', '28'=>'12', '29'=>'11', '31'=>'30', '34'=>'3');

        if(isset($params['oprId'])){
            $opr_new = isset($arr_map[$params['oprId']]) ? $arr_map[$params['oprId']] : $params['oprId'];
            $log_file = '/mnt/logs/modemRequest_via_redis_' . $vendor . '_' . $opr_new . '_' . date('Y-m-d') . '.log';
        }
        else{
            $log_file = '/mnt/logs/modemRequest_via_redis_' . $vendor . '_' . date('Y-m-d') . '.log';
        }
        // $logger = $this->General->dumpLog('modemRequest_by_redis', 'modemRequest_via_redis_'.$vendor.'_'.$opr_new);
        // $logger->info("Received param : data after getting from memcach : ".$query." | ".$vendor." | ".json_encode($data)." | ". $timeout );

        file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . " : $vendor machine Id after getting from memcach  :" . json_encode($data), FILE_APPEND | LOCK_EX);

        if(empty($data['machine_id'])){
            $data = $this->setVendorInfo($vendor);
            // $logger->info("$vendor machine Id after setting in memcach : ".json_encode($data));
            file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . " : $vendor machine Id after setting in memcach  :" . json_encode($data), FILE_APPEND | LOCK_EX);
            if(empty($data['machine_id'])){
                $vendorActObj = ClassRegistry::init('Slaves');
                $data = $vendorActObj->query("SELECT * FROM vendors WHERE id = $vendor");
                $data = $data[0]['vendors'];
                // $logger->info("$vendor machine Id after setting in memcach again : ".json_encode($data));
                file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . " : $vendor machine Id after setting in memcach again  :" . json_encode($data), FILE_APPEND | LOCK_EX);
            }
        }

        $errno = '';
        $errstr = '';

        $vendor_q = $vendor . "_" . $data['machine_id'];
        $response = "";
        $max_limit = 30;
        $open_redis = $this->openservice_redis();

        if($open_redis == FALSE){
            file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . " error in redis connection ", FILE_APPEND | LOCK_EX);
            // $logger->error("error in redis connection");
            return array('status'=>'failure', 'errno'=>515, 'error'=>'error in redis connection');
        }
        // $logger->error("error in redis connection");
        file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . " checking queue length ", FILE_APPEND | LOCK_EX);

        $vendor_q_lenght = $open_redis->llen($vendor_q);
        // $logger->info("comparing vendor Q len with max : ".$vender_q_lnght." | ".$max_limit);

        if($vendor_q_lenght > $max_limit){
            // $this->unHealthyVendor($vendor,$max_limit);
            return array('status'=>'failure', 'errno'=>515, 'error'=>'max thresh limit reached');
        }

        if($params['query'] == 'recharge'){
            $key_vendor_opr = "queue_" . $vendor . "_" . $opr_new;
            $vendor_q_opr = "Trans_" . $vendor . "_" . $opr_new;

            // $logger->info("::queue test::".$params['transId']."::$key_vendor_opr::$vendor_q_opr::".var_dump($open_redis->exists($key_vendor_opr))."::queue size:".$open_redis->hlen($vendor_q_opr)."::max length:".$open_redis->get($key_vendor_opr));
            file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . "::queue test::" . $params['transId'] . "::$key_vendor_opr::$vendor_q_opr::" . var_dump($open_redis->exists($key_vendor_opr)) . "::queue size:" . $open_redis->hlen($vendor_q_opr) . "::max length:" . $open_redis->get($key_vendor_opr), FILE_APPEND | LOCK_EX);

            if( ! $open_redis->exists($key_vendor_opr)){
                $this->getProdInfo($opr_new);
            }

            if($open_redis->exists($key_vendor_opr)){
                if($open_redis->hlen($vendor_q_opr) >= $open_redis->get($key_vendor_opr)){
                    return array('status'=>'failure', 'errno'=>515, 'error'=>'Dropped due to lot of pending requests - Server');
                }
                else{
                    if( ! $open_redis->exists("activestatus_" . $vendor)){
                        file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . " dropping request due to network connectivity issues : " . $params['transId'] . " | data : " . $query, FILE_APPEND | LOCK_EX);

                        // $this->General->sendMails("Internet fluctuations at : $vendor",$params['transId'],array('ashish@pay1.in'),'mail');
                        return array('status'=>'failure', 'errno'=>515, 'error'=>'Dropped due to network connectivity issues at modem - Server');
                    }
                    $open_redis->hset($vendor_q_opr, $params['transId'], time() + 120);
                }
            }
            else{
                return array('status'=>'failure', 'errno'=>515, 'error'=>'No active sim/no balance in sims - Server');
            }
        }
        /**
         * *
         */

        // $logger->info(" inserting data in Queue : $vendor_q | data : ". $query);
        file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . " inserting data in Queue : $vendor_q | data : " . $query, FILE_APPEND | LOCK_EX);

        $open_redis->set($uuid, "1");
        $open_redis->expire($uuid, 20);
        $open_redis->lpush($vendor_q, $query);

        // $logger->info(" waiting for response from hash : dynamic_service : uuid ". $uuid);
        file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . " waiting for response ", FILE_APPEND | LOCK_EX);
        $response_status = $open_redis->hexists('dynamic_service', $uuid);
        $counter = 1;
        while( ! $response_status){
            if($counter > $timeout * 5){
                $this->unHealthyVendor($vendor);
                break;
            }
            usleep(200000);
            $response_status = $open_redis->hexists('dynamic_service', $uuid);
            $counter ++ ;
        }
        if($response_status){
            $response = $open_redis->hget('dynamic_service', $uuid);

            // $logger->info("removing key from hash | key : ". $uuid);
            file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . " | removing key from hash | key : " . $uuid, FILE_APPEND | LOCK_EX);
            $open_redis->hdel('dynamic_service', $uuid);
        }
        // $logger->info(" Response : ". $response);
        file_put_contents($log_file, "\n" . Date("Y-m-d H:i:s -- ") . " | Response : " . $response, FILE_APPEND | LOCK_EX);
        return array('status'=>'success', 'data'=>$response);
    }

    /*
     * function modemRequest($query,$vendor=4,$data=null,$timeout=45){
     * //if(in_array($vendor,array(4,10,31)))
     * return $this->modemRequest_by_redis($query,$vendor,$data,$timeout);
     *
     * if(empty($data)) $data = $this->getVendorInfo($vendor);
     *
     * $ip = $data['ip'];
     * $port = $data['port'];
     * if($data['bridge_flag'] == 1 && !empty($data['bridge_ip'])){
     * $ip = $data['bridge_ip'];
     * }
     *
     * $url = "http://$ip:$port/start.php";
     * $errno = '';
     * $errstr = '';
     * $query .= "&vendor_id=$vendor";
     * $Rec_Data = $this->General->curl_post($url,$query,'POST',$timeout);
     *
     * if(!$Rec_Data['success']){
     * $this->unHealthyVendor($vendor);
     * if($Rec_Data['timeout']){
     * return array('status'=>'failure','errno'=>$errno,'error'=>$errstr);
     * }
     * }
     *
     * return array('status'=>'success','data'=>$Rec_Data['output']);
     * }
     */
    function healthyVendor($vendor, $ip = null){
        $data1 = $this->getVendorInfo($vendor);
        $name = $data1['company'];

        /* Don't declare a vendor healthy if the electricity is down */
        $elec_check = $this->getMemcache("electricity_" . $vendor);
        if($elec_check !== false && $elec_check == 0){
            return;
        }

        $data = array();

        $set = false;
        $ip_flag = false;
        if( ! empty($ip) && $ip != $data1['ip']){
            $data['ip'] = $ip;
            $data['update_time'] = date('Y-m-d H:i:s');
            $set = true;
            $ip_flag = true;
        }

        if($data1['active_flag'] == 0){
            $data['active_flag'] = 1;
            $data['health_factor'] = 0;
            $set = true;
            $this->General->logData("/mnt/logs/log.txt", date('Y-m-d H:i:s') . ": Enabling $name Vendor : $vendor");
            $this->General->sendMails("(SOS)Enabling $name Vendor : $vendor", "Got the connectivity. So enabling the vendor", array('backend@pay1.in', 'chetan.yadav@pay1.in'), 'mail');
        }

        if($data1['health_factor'] > 5){
            $data['health_factor'] = 0;
            $set = true;
        }

        if($set){
            $this->setVendorInfo($vendor, $data);
            if($ip_flag) $this->setVendors();
            $this->setInactiveVendors();
        }
    }

    function unHealthyVendor($vendor, $health_factor = null){
        if(empty($vendor)) return;

        $data = $this->getVendorInfo($vendor);
        $name = $data['company'];

        $set = false;
        $data1 = array();
        if($data['active_flag'] == 1){
            if(is_null($health_factor)) $data1['health_factor'] = $data['health_factor'] + 1;
            else $data1['health_factor'] = $health_factor;

            if($data1['health_factor'] >= 20){
                $data1['active_flag'] = 0;
                $this->General->sendMails("(SOS)Disabling $name Vendor : $vendor", "Connectivity issues. It will be activated once we get the connectivity", array('backend@pay1.in', 'chetan.yadav@pay1.in'), 'mail');

                $this->General->logData("/mnt/logs/log.txt", date('Y-m-d H:i:s') . ": Disabling $name Vendor : $vendor");
                $set = true;
            }
            $this->setVendorInfo($vendor, $data1);
            if($set) $this->setInactiveVendors();
        }
    }

    function earnings($params){
        $pageNo = empty($params['page_no']) ? 0 : $params['page_no'];
        $itemsPerPage = empty($params['items_per_page']) ? 0 : $params['items_per_page'];

        if(empty($params['service_id'])){
            $services = '';
        }
        if( ! isset($params['date']) || empty($params['date'])){
            $num = date("w");
            $date_from = date('Y-m-d', strtotime('- ' . $num . ' days'));
            $date_to = date('Y-m-d');
            $next_week = "";
            $prev_week = date('dmY', strtotime('- ' . ($num + 7) . ' days')) . '-' . date('dmY', strtotime('- ' . ($num + 1) . ' days'));
        }
        else{
            $date = $params['date'];
            $dates = explode("-", $date);
            $date_from = $dates[0];
            $date_to = $dates[1];
            $date_from = substr($date_from, 4) . "-" . substr($date_from, 2, 2) . "-" . substr($date_from, 0, 2);
            $date_to = substr($date_to, 4) . "-" . substr($date_to, 2, 2) . "-" . substr($date_to, 0, 2);
            $prev_week = date('dmY', strtotime($date_from . ' - 7 days')) . '-' . date('dmY', strtotime($date_to . ' - 7 days'));
            if(date('Y-m-d', strtotime($date_to)) < date('Y-m-d')){
                $next_week = date('dmY', strtotime($date_to . ' + 1 days')) . '-' . date('dmY', strtotime($date_to . ' + 7 days'));
            }
            else
                $next_week = "";
        }

        if($itemsPerPage <= 0 || $pageNo <= 0){
            // $query = "SELECT sum(st1.amount) as amount, sum(st2.amount) as income,date(st1.timestamp) as date FROM shop_transactions as st1 INNER JOIN shop_transactions as st2 ON (st2.ref2_id = st1.id AND st2.type = ".COMMISSION_RETAILER.") WHERE st1.confirm_flag = 1 AND st1.type = ".RETAILER_ACTIVATION." AND st1.ref1_id = ".$_SESSION['Auth']['id'] . " AND st1.date >= '$date_from' AND st1.date <= '$date_to' group by st1.date";
            // $query = "SELECT sum(va.amount) as amount, sum(va.amount+oc.closing-oc.opening) as income,trim(va.date) as date FROM vendors_activations as va INNER JOIN opening_closing as oc ON (oc.shop_transaction_id=va.shop_transaction_id AND oc.shop_id=va.retailer_id AND oc.group_id = ".RETAILER.") WHERE va.retailer_id = ".$_SESSION['Auth']['id'] . " AND va.status != 2 AND va.status != 3 AND va.date >= '$date_from' AND va.date <= '$date_to' group by va.date";
            $query = "SELECT SUM(rel.amount) AS amount, TRUNCATE(SUM(rel.earning),3) AS income,trim(rel.date) as date "
                    . "FROM retailer_earning_logs rel "
                    . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                    . "WHERE r.id = ".$_SESSION['Auth']['id']." "
                    . "AND rel.date >= '$date_from' "
                    . "AND rel.date <= '$date_to' "
                    . "AND rel.service_id IN (1,2,4,5,6,7) "
                    . "GROUP BY date";
        }
        else{
            $ll = $itemsPerPage * ($pageNo - 1); // + 1; // lower limit
            $ul = $itemsPerPage * $pageNo; // upper limit
                                           // $query = "SELECT sum(st1.amount) as amount, sum(st2.amount) as income,date(st1.timestamp) as date FROM shop_transactions as st1 INNER JOIN shop_transactions as st2 ON (st2.ref2_id = st1.id AND st2.type = ".COMMISSION_RETAILER.") WHERE st1.confirm_flag = 1 AND st1.type = ".RETAILER_ACTIVATION." AND st1.ref1_id = ".$_SESSION['Auth']['id'] . " AND st1.date >= '$date_from' AND st1.date <= '$date_to' group by st1.date limit $ll , $ul ";
                                           // $query = "SELECT sum(va.amount) as amount, sum(va.amount+oc.closing-oc.opening) as income,trim(va.date) as date FROM vendors_activations as va INNER JOIN opening_closing as oc ON (oc.shop_transaction_id=va.shop_transaction_id AND oc.shop_id=va.retailer_id AND oc.group_id = ".RETAILER.") WHERE va.retailer_id = ".$_SESSION['Auth']['id'] . " AND va.status != 2 AND va.status != 3 AND va.date >= '$date_from' AND va.date <= '$date_to' group by va.date limit $ll , $ul";
            $query = "SELECT SUM(rel.amount) AS amount, TRUNCATE(SUM(rel.earning),3) AS income,trim(rel.date) as date "
                    . "FROM retailer_earning_logs rel "
                    . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                    . "WHERE r.id = ".$_SESSION['Auth']['id']." "
                    . "AND rel.date >= '$date_from' "
                    . "AND rel.date <= '$date_to' "
                    . "AND rel.service_id IN (1,2,4,5,6,7)"
                    . "GROUP BY date "
                    . "LIMIT $ll , $ul";
        }
        $vendorActObj = ClassRegistry::init('Slaves');
        // $vendorActObj->recursive = -1;

        $data = $vendorActObj->query($query);
        $query = "SELECT sum(va.amount) as amount, sum(va.retailer_margin) as income,trim(va.date) as date FROM vendors_activations as va WHERE va.retailer_id = " . $_SESSION['Auth']['id'] . " AND va.status != 2 AND va.status != 3 AND va.date = '" . date('Y-m-d') . "'";
        $today = $vendorActObj->query($query);
        // $today = $vendorActObj->query("SELECT sum(st1.amount) as amount, sum(st2.amount) as income,date(st1.timestamp) as date FROM shop_transactions as st1 INNER JOIN shop_transactions as st2 ON (st2.ref2_id = st1.id AND st2.type = ".COMMISSION_RETAILER.") WHERE st1.confirm_flag = 1 AND st1.type = ".RETAILER_ACTIVATION." AND st1.ref1_id = ".$_SESSION['Auth']['id'] . " AND st1.date = '".date('Y-m-d')."'");
        // print_r($data);
        return array($data, $today['0'], $prev_week, $next_week, $date_from . ' to ' . $date_to);
    }

    function topups($params){
        if( ! isset($params['date']) || empty($params['date'])){
            $num = date("w");
            $date_from = date('Y-m-d', strtotime('- ' . $num . ' days'));
            $date_to = date('Y-m-d');
            $next_week = "";
            $prev_week = date('dmY', strtotime('- ' . ($num + 7) . ' days')) . '-' . date('dmY', strtotime('- ' . ($num + 1) . ' days'));
        }
        else{
            $date = $params['date'];
            $dates = explode("-", $date);
            $date_from = $dates[0];
            $date_to = $dates[1];
            $date_from = substr($date_from, 4) . "-" . substr($date_from, 2, 2) . "-" . substr($date_from, 0, 2);
            $date_to = substr($date_to, 4) . "-" . substr($date_to, 2, 2) . "-" . substr($date_to, 0, 2);
            $prev_week = date('dmY', strtotime($date_from . ' - 7 days')) . '-' . date('dmY', strtotime($date_to . ' - 7 days'));
            if($date_to < date('Y-m-d')){
                $next_week = date('dmY', strtotime($date_to . ' + 1 days')) . '-' . date('dmY', strtotime($date_to . ' + 7 days'));
            }
            else
                $next_week = "";
        }

        $vendorActObj = ClassRegistry::init('Slaves');
        $vendorActObj->recursive =  - 1;
        $data1 = array();
        $data2 = array();

        if($date_from < date('Y-m-d')){
            $to_date = $date_to < date('Y-m-d')?$date_to:date('Y-m-d', strtotime('-1 day'));
            $query1 = "SELECT SUM(st1.topup_buy) as amount, date(st1.date) as day FROM users_logs as st1 WHERE st1.user_id = " . $_SESSION['Auth']['user_id'] . " AND st1.date >= '$date_from' AND st1.date <= '$to_date' GROUP BY st1.date";
            $data1 = $vendorActObj->query($query1);
        }
        if($date_from == date('Y-m-d') || $date_to == date('Y-m-d')){
            $query2 = "SELECT sum(st1.amount) as amount, date(st1.timestamp) as day FROM shop_transactions as st1 WHERE st1.confirm_flag != 1 AND st1.type IN ('" . DIST_RETL_BALANCE_TRANSFER . "','" . SLMN_RETL_BALANCE_TRANSFER . "') AND st1.target_id = " . $_SESSION['Auth']['id'] . " AND st1.date = '".date('Y-m-d')."' group by st1.date";
            $data2 = $vendorActObj->query($query2);
        }
        $data = array_merge($data1,$data2);

        return array($data, $prev_week, $next_week, $date_from . ' to ' . $date_to);
    }

    function verifyParams($params, $mapping){
        $ret = true;
        $msg = "";
        foreach($mapping['allParams']['param'] as $param){
            $field = trim($param['field']);
            if( ! isset($params[$field])){
                $ret = false;
                $msg = $field . " not entered";
                break;
            }
            else if(strlen($params[$field]) > $param['length'] || empty($params[$field])){
                $msg = $field . ": Enter valid value";
                $ret = false;
                break;
            }
            else if($field == 'Mobile' || $field == 'PNR' || $field == 'Amount'){
                if(strlen($params[$field]) != $param['length']){
                    $msg = $field . ": Enter valid value";
                    $ret = false;
                    break;
                }
            }
        }
        if($ret){
            return array('status'=>'success');
        }
        else{
            return array('status'=>'failure', 'description'=>$msg);
        }
    }

    function mailToMasterDistributor($retailer_id, $subject, $mail_body){
        $ret_shop = $this->getShopDataById($retailer_id, RETAILER);
        $dist_shop = $this->getShopDataById($ret_shop['parent_id'], DISTRIBUTOR);
        $masterdist_shop = $this->getShopDataById($dist_shop['parent_id'], MASTER_DISTRIBUTOR);
        $this->General->sendMails($subject, $mail_body, array($masterdist_shop['email']));
    }

    function mailToDistributor($retailer_id, $subject, $mail_body){
        $ret_shop = $this->getShopDataById($retailer_id, RETAILER);
        $dist_shop = $this->getShopDataById($ret_shop['parent_id'], DISTRIBUTOR);
        $this->General->sendMails($subject, $mail_body, array($dist_shop['email']));
    }

    /*
     * function calculateCommission($id,$group_id,$product,$amount,$slab){
     * //$shop = $this->getShopDataById($id,$group_id);
     * //$slab = $shop['slab_id'];
     * $commission = $this->getMemcache("commission_".$product."_".$slab);
     * if($commission === false){
     * $slabObj = ClassRegistry::init('SlabsUser');
     * $prodData = $slabObj->query("SELECT percent FROM slabs_products INNER JOIN products ON (product_id = products.id) WHERE slab_id = $slab AND product_id = $product");
     * if(!empty($prodData))
     * $commission = $amount*$prodData['0']['slabs_products']['percent']/100;
     * else $commission = 0;
     * $this->setMemcache("commission_".$product."_".$slab,$commission,24*60*60);
     * }
     *
     * return $commission;
     * }
     */
    function getCommissionPercent($slab_id, $product,$amount=null){
        $commission = $this->getMemcache("commission_" . $product . "_" . $slab_id);
        if($commission === false){
            $slabObj = ClassRegistry::init('Slaves');
            Configure::load('product_config');
            $prodData = $slabObj->query("SELECT slabs_products.percent,slabs_products.service_charge,slabs_products.service_tax,products.service_id FROM products left join slabs_products ON (products.id = product_id AND slab_id = $slab_id) WHERE product_id = $product");
            if(isset($prodData) &&  ! empty($prodData)){
                $commission = $prodData['0']['slabs_products'];
                $service_id = $prodData['0']['products']['service_id'];
                if(Configure::read('retailer_commission_'.$service_id)){
                    $data = Configure::read('retailer_commission_'.$service_id);
                    foreach($data as $key => $dt){
                        $from = explode("-",$key);
                        if($amount >= $from[0] && $amount <= $from[1]){
                            if($dt['variable'] > 0){
                                $service_charge = $amount*$dt['variable']/100;
                            }
                            else {
                                $service_charge = $dt['fixed'];
                            }

                            if($dt['service_tax'] > 0){
                                $service_tax = $dt['service_tax'];
                            }
                            break;
                        }
                    }
                    $commission = array('service_charge'=>$service_charge,'service_tax'=>$service_tax);
                }
                else if($commission['service_charge'] > 0){
                    $service_charge = $amount*$commission['service_charge']/100;
                    if($service_id == 4 && $service_charge < 5){
                        $service_charge = 5;
                    }
                    $commission['service_charge'] = $service_charge;
                    if($service_id != 4)$this->setMemcache("commission_" . $product . "_" . $slab_id, $commission, 3 * 60 * 60);
                }
                else {
                    $this->setMemcache("commission_" . $product . "_" . $slab_id, $commission, 3 * 60 * 60);
                }
            }
            else
                $commission = array();



        }

        return $commission;
    }

    function getAllCommissions_new($user_id, $group_id, $slab, $service = null){
        $slabObj = ClassRegistry::init('SlabsUser');
        $table = array();

        if($service <= 5 || $service == 7){
            $qryStr = '';
            if( ! is_null($service)) $qryStr = ' and products.service_id = ' . $service . ' ';

            if($group_id == RETAILER){
                $prodData = $slabObj->query("SELECT products.id,products.name, products.type,slabs_products.percent, slabs_products.service_charge, slabs_products.service_tax FROM slabs_products,products WHERE products.id = product_id AND slab_id = $slab AND products.to_show = 1 AND products.active = 1 " . $qryStr . " ORDER BY products.id");

                foreach($prodData as $pd){
                    $info = array();
                    $info['key'] = $pd['products']['name'];
                    if($pd['slabs_products']['service_charge'] > 0){
                        $info['value'] = $pd['slabs_products']['service_charge'] . "%";
                    }
                    else {
                        $info['value'] = $pd['slabs_products']['percent'] . "%";
                    }

                    if($service == 4){
                        $info['value'] .= " (Min Rs 5)";
                    }
                    if($pd['slabs_products']['service_tax'] == 1){
                        $info['value'] .= " + GST";
                    }

                    if($pd['slabs_products']['service_charge'] > 0){
                        $info['value'] .= " (Service Charge)";
                    }
                    else {
                        $info['value'] .= " (Discount Commission)";
                    }

                    if($pd['products']['type'] == 0){// 0 means P2P & 1 means P2A
                        $info['value'] .= " - P2P";
                    }
                    else {
                        $info['value'] .= " - P2A";
                    }

                    $table[] = $info;
                }
            }
        } else if($service == 6) // utility bill payments
        {
            Configure::load('product_config');

            $data = Configure::read('retailer_commission_' . $service);
            //$i = 0;
            foreach($data as $key=>$dt){
                $info = array();
                $info['key'] = $key;

                if($dt['variable'] > 0){
                    $value = $dt['variable'] . " %";
                }
                else {
                    $value = "Rs " . $dt['fixed'];
                }
                if($dt['service_tax'] > 0){
                    $value .= " + GST";
                }
                $value .= " (Service Charge)";

                $info['value'] = $value;
                $table[] = $info;
            }
        }
        else if($service >= 12){
            $plan = $slabObj->query("SELECT spp.ret_params,products.earning_type,products.name from users_services left join service_plans on (service_plans.id = users_services.service_plan_id) left join service_product_plans as spp on (spp.service_plan_id = service_plans.id) left join products on (products.id = spp.product_id) WHERE user_id = '$user_id' AND service_plans.service_id = '$service'");
            $plan_params = json_decode($plan[0]['spp']['ret_params'],true);
            if(!empty($plan_params)){
                $data['ret_margin']['margin'] = $plan_params['0-0']['margin'];
                $data['min'] = $plan_params['0-0']['min'];
                $data['max'] = $plan_params['0-0']['max'];
                $data['ret_margin']['service_tax'] = 0;

                $prod_type = $plan[0]['products']['earning_type'];
                if($prod_type == 2){
                    $data['ret_margin']['service_charge'] = 1;
                }
                else {
                    $data['ret_margin']['service_charge'] = 0;
                }

                foreach($data as $key=>$dt){
                    $info = array();
                    $info['key'] = $key;
                    $value = $dt;
                    if($key == 'ret_margin'){
                        $value = $dt['margin'];
                        if($dt['service_tax'] > 0){
                            $value .= " + GST";
                        }

                        if($dt['service_charge'] > 0){
                            $value .= " (Service Charge)";
                        }
                    }

                    $info['value'] = $value;
                    $table[] = $info;
                }
            }

        }

        return $table;
    }

    function getAllCommissions($id, $group_id, $slab, $service = null){
        $qryStr = '';
        if( ! is_null($service)) $qryStr = ' and products.service_id = ' . $service . ' ';
        $slabObj = ClassRegistry::init('SlabsUser');
        $table = array();
        /*
         * if($group_id == MASTER_DISTRIBUTOR){
         * $slab = SDIST_SLAB;
         * $prodData = $slabObj->query("SELECT trim(products.name) as prodName, trim(slabs_products.percent) as prodPercent FROM slabs_products,products WHERE products.id = product_id AND slab_id = $slab AND products.to_show = 1 AND products.active = 1 ".$qryStr." ORDER BY products.id");
         * $table['SD'] = $prodData;
         * }
         *
         * if($group_id <= DISTRIBUTOR){
         * $slab = DIST_SLAB;
         * $prodData = $slabObj->query("SELECT trim(products.name) as prodName, trim(slabs_products.percent) as prodPercent FROM slabs_products,products WHERE products.id = product_id AND slab_id = $slab AND products.to_show = 1 AND products.active = 1 ".$qryStr." ORDER BY products.id");
         * $table['D'] = $prodData;
         * }
         */

        if($group_id <= RETAILER){
            // $slab = RET_SLAB;
            $prodData = $slabObj->query("SELECT trim(products.name) as prodName, trim(slabs_products.percent) as prodPercent FROM slabs_products,products WHERE products.id = product_id AND slab_id = $slab AND products.to_show = 1 AND products.active = 1 " . $qryStr . " ORDER BY products.id");
            $table['R'] = $prodData;
        }

        return $table;
    }

    function calculateTDS($commission){
        return round($commission * TDS_PERCENT / 100,2);
    }

    function calculateServiceTax($service_charge){
        $multiplier = "1." . SERVICE_TAX_PERCENT;
        return round($service_charge * $multiplier,2);
    }

    function shopBalanceUpdate($price, $type, $id, $group_id = null, $dataSource = null, $user_id_flag = 0, $allow_negative = 1){
        $userObj = is_null($dataSource) ? ClassRegistry::init('User') : $dataSource;

        // if($group_id == MASTER_DISTRIBUTOR){
        // $table = 'master_distributors';
        // }
        // else if($group_id == DISTRIBUTOR){
        // $table = 'distributors';
        // }
        // else if($group_id == SALESMAN){
        // $table = 'salesmen';
        // }
        // else if($group_id == RETAILER){
        // $table = 'retailers';
        // }

        if($type == 'subtract'){
            if($allow_negative == 1){
                $userObj->query("UPDATE users SET balance = balance - $price WHERE id = $id");
            }
            else {
                $userObj->query("UPDATE users SET balance = balance - $price WHERE id = $id AND balance >= $price");
                $affected_rows = $userObj->query("SELECT ROW_COUNT() as rows");
                $rows_affected = $affected_rows[0][0]['rows'];
                if($rows_affected <= 0 && $price > 0) return false;
            }
        }
        else if($type == 'add'){
            $userObj->query("UPDATE users SET balance = balance + $price WHERE id = $id");
        }

        // if($group_id != SALESMAN){
        // $qry = "SELECT " . $table . ".balance,users.mobile FROM $table left join users ON (users.id = user_id) WHERE $table" . ".id = $id";
        // $bal = $userObj->query($qry);
        // }
        // else{
        // $qry = "SELECT balance,mobile FROM $table WHERE id = $id";
        // $bal_1 = $userObj->query($qry);
        // $bal[0][$table] = array('balance'=>$bal_1[0][$table]['balance']);
        // $bal[0]['users'] = array('mobile'=>$bal_1[0][$table]['mobile']);
        // }

        $qry = "SELECT users.balance,users.mobile FROM users  WHERE users.id = $id";
        $bal = $userObj->query($qry);

        $final_bal = sprintf('%.2f', $bal['0']['users']['balance']);
        $this->General->logData('/mnt/logs/TranQuery' . date('Y-m-d') . '.log', "Retailer Final Bal :  {$final_bal}", FILE_APPEND | LOCK_EX);
        if($final_bal < -50){
            $this->General->logData('/mnt/logs/negative_bal.txt', "User ID : $id | Final balance : $final_bal | Mobile number: " . $bal['0']['users']['mobile']);

            if(is_null($dataSource)): // Send message only if this function is called from somewhere else other than during transaction.
                $this->General->sendMessage('9819032643,9820595052,9833258509,7208207549', "Negative balance Alert, User ID : $id | Final balance : $final_bal | Mobile number: " . $bal['0']['users']['mobile'], 'shops');
                $this->General->sendMails("Alert : Negative Balance", "User ID : $id <br/> Final balance : $final_bal <br/> Mobile number: " . $bal['0']['users']['mobile'], array('ashish@pay1.in', 'chirutha@pay1.in','finance@pay1.in'), 'mail');

                                                        endif;

        }
        return sprintf('%.2f', $bal['0']['users']['balance']);
    }

    function getNewInvoice($shop_id, $from_id, $group_id, $invoice_type){
        $invoiceObj = ClassRegistry::init('Invoice');
        $this->data['Invoice']['ref_id'] = $shop_id;
        $this->data['Invoice']['from_id'] = $from_id;
        $this->data['Invoice']['group_id'] = $group_id;
        $this->data['Invoice']['invoice_type'] = $invoice_type;
        $this->data['Invoice']['timestamp'] = date('Y-m-d H:i:s');

        $invoiceObj->create();
        $invoiceObj->save($this->data);

        return $invoiceObj->id;
    }

    function addToInvoice($invoice_id, $trans_id){
        $invoiceObj = ClassRegistry::init('Invoice');
        $invoiceObj->query("INSERT INTO invoices_transactions (invoice_id,shoptransaction_id,timestamp) VALUES ($invoice_id,$trans_id,'" . date('Y-m-d H:i:s') . "')");
    }


    function getShopDataById1($id, $group_id){
        $info = $this->getMemcache("shop" . $id . "_" . $group_id);
        if($info === false){
            if($group_id == MASTER_DISTRIBUTOR){
                $userObj = ClassRegistry::init('MasterDistributor');
                $userObj->recursive =  - 1;
                $bal = $userObj->find('first', array('conditions'=>array('id'=>$id)));
                $info = $bal['MasterDistributor'];
            }
            else if($group_id == DISTRIBUTOR){
                $userObj = ClassRegistry::init('Distributor');
                $userObj->recursive =  - 1;
                $bal = $userObj->find('first', array('conditions'=>array('id'=>$id)));
                $info = $bal['Distributor'];
            }
            else if($group_id == RETAILER){
                $userObj = ClassRegistry::init('Retailer');
                $userObj->recursive =  - 1;
                $bal = $userObj->query("select * from retailers
						left join unverified_retailers ur on ur.retailer_id = retailers.id
						where retailers.id = $id");

                foreach($bal[0]['ur'] as $key=>$row){
                    if( ! in_array($key, array('id'))) $bal[0]['retailers'][$key] = $bal[0]['ur'][$key];
                }

                $info = $bal['0']['retailers'];

                /** IMP DATA ADDED : START**/
                $temp = $this->getUserLabelData($id,2,2);
                $imp_data = $temp[$id];
                $retailer_imp_label_map = array(
                    'pan_number' => 'pan_no',
                    'shopname' => 'shop_est_name',
                    'alternate_number' => 'alternate_mobile_no',
                    'email' => 'email_id',
                    'shop_structure' => 'shop_ownership',
                    'shop_type' => 'business_nature'
                );
                foreach ($info as $retailer_label_key => $value) {
                    $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                    if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                        $info[$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
                    }
                }
                /** IMP DATA ADDED : END**/
            }
            else if($group_id == RELATIONSHIP_MANAGER){
                $userObj = ClassRegistry::init('Rm');
                $userObj->recursive =  - 1;
                $bal = $userObj->find('first', array('conditions'=>array('id'=>$id)));
                $info = $bal['Rm'];
            }

            $this->setMemcache("shop" . $id . "_" . $group_id, $info, 60 * 60 * 3);
        }
        return $info;
    }

    function getBalance($user_id,$dataSource=null){
        $userObj = is_null($dataSource) ? ClassRegistry::init('User') : $dataSource;
        $bal = $userObj->query("select balance from users where id=$user_id");

        if( ! empty($bal)) return $bal['0']['users']['balance'];
        else return 0;
    }

    function getBalanceViaMobile($mobile){
        $userObj = ClassRegistry::init('User');
        $userObj->recursive =  - 1;
        $bal = $userObj->query("select balance from users where mobile='$mobile'");
        if( ! empty($bal)) return $bal['0']['users']['balance'];
        else return 0;
    }

    function addUserGroup($user_id, $group_id){
        $userObj = ClassRegistry::init('User');
        $userObj->query("INSERT INTO user_groups (user_id,group_id) VALUES ($user_id,$group_id)");
    }

    function getShopDataById($id, $group_id){
        if($group_id == MASTER_DISTRIBUTOR){
            $userObj = ClassRegistry::init('MasterDistributor');
            $userObj->recursive =  - 1;
            $bal = $userObj->find('first', array('conditions'=>array('id'=>$id)));
            $keyword = 'MasterDistributor';
        }
        else if($group_id == SUPER_DISTRIBUTOR){
            $userObj = ClassRegistry::init('SuperDistributor');
            $userObj->recursive =  - 1;
            $bal = $userObj->find('first', array('conditions'=>array('id'=>$id)));
            $keyword = 'SuperDistributor';

            $name = $userObj->query("SELECT description FROM imp_label_upload_history imp WHERE label_id = 15 AND user_id = '" . $bal[$keyword]['user_id'] . "'");
            $bal[$keyword]['name'] = $name[0]['imp']['description'];
        }
        else if($group_id == DISTRIBUTOR){
            $userObj = ClassRegistry::init('Distributor');
            $userObj->recursive =  - 1;
            $bal = $userObj->find('first', array('conditions'=>array('id'=>$id)));
            $keyword = 'Distributor';

            /** IMP DATA ADDED : START**/
            $temp = $this->getUserLabelData($id,2,3);
            $imp_data = $temp[$id];
            $dist_imp_label_map = array(
                'pan_number' => 'pan_no',
                'company' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id'
            );
            foreach ($bal[$keyword] as $dist_label_key => $value) {
                $dist_label_key_mapped = ( array_key_exists($dist_label_key,$dist_imp_label_map) ) ? $dist_imp_label_map[$dist_label_key] : $dist_label_key;
                if( array_key_exists($dist_label_key_mapped,$imp_data['imp']) ){
                    $bal[$keyword][$dist_label_key] = $imp_data['imp'][$dist_label_key_mapped];
                }
            }
            /** IMP DATA ADDED : END**/

        }
        else if($group_id == RETAILER){
            $userObj = ClassRegistry::init('Retailer');
            $userObj->recursive =  - 1;

            $bal1 = $userObj->query("select * from retailers
					left join unverified_retailers ur on ur.retailer_id = retailers.id
					where retailers.id = $id");

            foreach($bal1[0]['ur'] as $key=>$row){
                if( ! in_array($key, array('id'))) $bal1[0]['retailers'][$key] = $bal1[0]['ur'][$key];
            }

            $bal = $bal1[0];
            $keyword = 'retailers';
            // $bal = $userObj->find('first',array('conditions' => array('id' => $id)));
            // return $bal['Retailer'];

            /** IMP DATA ADDED : START**/
            $temp = $this->getUserLabelData($id,2,2);
            $imp_data = $temp[$id];
            $retailer_imp_label_map = array(
                'pan_number' => 'pan_no',
                'shopname' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id',
                'shop_structure' => 'shop_ownership',
                'shop_type' => 'business_nature'
            );
            foreach ($bal[$keyword] as $retailer_label_key => $value) {
                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                    $bal[$keyword][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
                }
            }
            /** IMP DATA ADDED : END**/
        }
        else if($group_id == RELATIONSHIP_MANAGER){
            $userObj = ClassRegistry::init('Rm');
            $userObj->recursive =  - 1;
            $bal = $userObj->find('first', array('conditions'=>array('id'=>$id)));
            $keyword = 'Rm';
        }
        else if($group_id == SALESMAN){
            $userObj = ClassRegistry::init('Salesman');
            $userObj->recursive =  - 1;
            $shop_data = $userObj->query("SELECT * FROM salesmen WHERE id = $id");
            $bal = $shop_data[0];
            $keyword = 'salesmen';
        }
        if(in_array($group_id, array(MASTER_DISTRIBUTOR, SUPER_DISTRIBUTOR, DISTRIBUTOR, RETAILER, RELATIONSHIP_MANAGER, SALESMAN))){
            $res = $userObj->query("SELECT mobile, balance FROM users WHERE id = '" . $bal[$keyword]['user_id'] . "'");
            $bal[$keyword]['balance'] = $res[0]['users']['balance'];
            $bal[$keyword]['mobile'] = $res[0]['users']['mobile'];
            return $bal[$keyword];
        }
    }

    /*
     * function disabledApps($uid){
     * $userObj = ClassRegistry::init('Retailer');
     * $userObj->recursive = -1;
     * $dis = $userObj->query('select service_id from services_disabled where user_id = '.$uid);
     * $str = '';
     * foreach($dis as $d)
     * $str .= $d['services_disabled']['service_id'].",";
     *
     * if($str == '')return $str;
     * else return substr($str,0,-1);
     * }
     */
    function shortSerials($serials){
        $serials = explode(",", $serials);
        sort($serials);
        $serial_array = array();

        $min = $serials[0];
        $last = $serials[0];
        $serial_array[$min] = 1;
        foreach($serials as $serial){
            if($serial == $last + 1){
                $serial_array[$min] = $serial_array[$min] + 1;
                $last = $serial;
            }
            else if($serial != $min){
                $min = $serial;
                $last = $serial;
                $serial_array[$min] = 1;
            }
        }
        $strings = array();
        foreach($serial_array as $key=>$value){
            $string = $key;
            if($value > 1){
                $string .= "-" . ($key + $value - 1);
            }
            $strings[] = $string;
        }
        return implode(", ", $strings);
    }

    /*
     * function getInvoiceNumber($invoice_id,$date){
     * $invoiceObj = ClassRegistry::init('Invoice');
     * $data = $invoiceObj->findById($invoice_id);
     * if($data['Invoice']['group_id'] == MASTER_DISTRIBUTOR){
     * $shop = $this->getShopDataById($data['Invoice']['ref_id'],$data['Invoice']['group_id']);
     * $comp = explode(" ",$shop['company']);
     * $ret = $invoiceObj->query("SELECT count(*) as counts FROM invoices WHERE group_id = ".MASTER_DISTRIBUTOR." and id < $invoice_id");
     * $ret_to = $invoiceObj->query("SELECT count(*) as counts FROM invoices WHERE group_id = ".MASTER_DISTRIBUTOR." and ref_id = ". $data['Invoice']['ref_id']." and id < $invoice_id");
     * }
     * else if($data['Invoice']['group_id'] == DISTRIBUTOR){
     * $shop = $this->getShopDataById($data['Invoice']['ref_id'],$data['Invoice']['group_id']);
     * $comp = explode(" ",$shop['company']);
     * $parent_shop = $this->getShopDataById($shop['parent_id'],MASTER_DISTRIBUTOR);
     * $parent_comp = explode(" ",$parent_shop['company']);
     * $ret = $invoiceObj->query("SELECT count(*) as counts FROM invoices WHERE group_id = ".DISTRIBUTOR." and from_id = ".$data['Invoice']['from_id']." and id < $invoice_id");
     * $ret_to = $invoiceObj->query("SELECT count(*) as counts FROM invoices WHERE group_id = ".DISTRIBUTOR." and from_id = ".$data['Invoice']['from_id']." and ref_id = ". $data['Invoice']['ref_id']." and id < $invoice_id");
     * }
     * else if($data['Invoice']['group_id'] == RETAILER){
     * $shop = $this->getShopDataById($data['Invoice']['ref_id'],$data['Invoice']['group_id']);
     * $comp = explode(" ",$shop['shopname']);
     * $parent_shop = $this->getShopDataById($shop['parent_id'],DISTRIBUTOR);
     * $parent_comp = explode(" ",$parent_shop['company']);
     * $ret = $invoiceObj->query("SELECT count(*) as counts FROM invoices WHERE group_id = ".RETAILER." and from_id = ".$data['Invoice']['from_id']." and id < $invoice_id");
     * $ret_to = $invoiceObj->query("SELECT count(*) as counts FROM invoices WHERE group_id = ".RETAILER." and from_id = ".$data['Invoice']['from_id']." and ref_id = ". $data['Invoice']['ref_id']." and id < $invoice_id");
     * }
     * $suffix1 = "ST";
     * $suffix2 = "";
     * foreach($comp as $str){
     * $suffix2 .= substr($str,0,1);
     * }
     * $suffix2 = strtoupper($suffix2);
     * if(isset($parent_comp)){
     * $suffix1 = "";
     * foreach($parent_comp as $str){
     * $suffix1 .= substr($str,0,1);
     * }
     * $suffix1 = strtoupper($suffix1);
     * }
     * $number = $ret['0']['0']['counts'] + 1;
     * $number1 = $ret_to['0']['0']['counts'] + 1;
     * return "INV-" . $suffix1 . "/" . $suffix2 . "/" . date('Y',strtotime($date)). "/" . sprintf('%04d', $number) . "/" . sprintf('%03d', $number1);
     * }
     *
     * function getTopUpReceiptNumber($transaction_id){
     * $transObj = ClassRegistry::init('ShopTransaction');
     * $data = $transObj->findById($transaction_id);
     * if($data['ShopTransaction']['type'] == ADMIN_TRANSFER){
     * $suffix1 = "ST";
     * $shop = $this->getShopDataById($data['ShopTransaction']['ref2_id'],MASTER_DISTRIBUTOR);
     * $comp = explode(" ",$shop['company']);
     * }
     * else if($data['ShopTransaction']['type'] == MDIST_DIST_BALANCE_TRANSFER){
     * $shop = $this->getShopDataById($data['ShopTransaction']['ref2_id'],DISTRIBUTOR);
     * $comp = explode(" ",$shop['company']);
     * $parent_shop = $this->getShopDataById($shop['parent_id'],MASTER_DISTRIBUTOR);
     * $parent_comp = explode(" ",$parent_shop['company']);
     * }
     * else if($data['ShopTransaction']['type'] == DIST_RETL_BALANCE_TRANSFER){
     * $shop = $this->getShopDataById($data['ShopTransaction']['ref2_id'],RETAILER);
     * $comp = explode(" ",$shop['shopname']);
     * $parent_shop = $this->getShopDataById($shop['parent_id'],DISTRIBUTOR);
     * $parent_comp = explode(" ",$parent_shop['company']);
     * }
     * $ret = $transObj->query("SELECT count(*) as counts FROM shop_transactions WHERE type = ".$data['ShopTransaction']['type']." and ref1_id = ".$data['ShopTransaction']['ref1_id']." and id < $transaction_id");
     * $suffix2 = "";
     * foreach($comp as $str){
     * $suffix2 .= substr($str,0,1);
     * }
     * $suffix2 = strtoupper($suffix2);
     * if(isset($parent_comp)){
     * $suffix1 = "";
     * foreach($parent_comp as $str){
     * $suffix1 .= substr($str,0,1);
     * }
     * $suffix1 = strtoupper($suffix1);
     * }
     * $number = $ret['0']['0']['counts'] + 1;
     * return "TP-". $suffix1 . "/". $suffix2 . "/" . sprintf('%06d', $number);
     * }
     *
     * function getCreditDebitNumber($to_id,$from_id,$to_groupid,$type,$id=null){
     * $invoiceObj = ClassRegistry::init('Invoice');
     * $query = "";
     * if($id != null){
     * $query = " AND id < $id";
     * }
     * if(empty($from_id)) $from = " is null";
     * else $from = " = $from_id";
     *
     * $ret = $invoiceObj->query("SELECT count(*) as counts FROM shop_creditdebit WHERE to_groupid = $to_groupid AND from_id $from AND to_id = $to_id AND type=$type $query");
     * $retAll = $invoiceObj->query("SELECT count(*) as counts FROM shop_creditdebit WHERE from_id $from AND type=$type $query");
     *
     * $shop = $this->getShopDataById($to_id,$to_groupid);
     * if(!empty($from_id)){
     * $parent_shop = $this->getShopDataById($from_id,$to_groupid-1);
     * $parent_comp = explode(" ",$parent_shop['company']);
     * }
     *
     * if(isset($shop['company']))
     * $comp = explode(" ",$shop['company']);
     * else
     * $comp = explode(" ",$shop['shopname']);
     *
     * $suffix1 = "ST";
     * $suffix2 = "";
     * foreach($comp as $str){
     * $suffix2 .= substr($str,0,1);
     * }
     * $suffix2 = strtoupper($suffix2);
     * if(isset($parent_comp)){
     * $suffix1 = "";
     * foreach($parent_comp as $str){
     * $suffix1 .= substr($str,0,1);
     * }
     * $suffix1 = strtoupper($suffix1);
     * }
     * $number = $retAll['0']['0']['counts'] + 1;
     * $number1 = $ret['0']['0']['counts'] + 1;
     * if($type == 0) $suffix0 = "CN";
     * else if($type == 1) $suffix0 = "DN";
     * return "$suffix0-" . $suffix1 . "/" . $suffix2 . "/" . sprintf('%04d', $number) . "/" . sprintf('%03d', $number1);
     * }
     */
    function getOtherProds($prodId){
        if($prodId == 3 || $prodId == 34) $prodId1 = "3,34";
        else if($prodId == 7 || $prodId == 8) $prodId1 = "7,8";
        else if($prodId == 9 || $prodId == 27 || $prodId == 10) $prodId1 = "9,10,27";
        else if($prodId == 11 || $prodId == 29) $prodId1 = "11,29";
        else if($prodId == 12 || $prodId == 28) $prodId1 = "12,28";
        else if($prodId == 30 || $prodId == 31) $prodId1 = "30,31";
        else $prodId1 = $prodId;

        return $prodId1;
    }

    function getParentProd($prodId){
        $arr_map = array('7'=>'8', '10'=>'9', '27'=>'9', '28'=>'12', '29'=>'11', '31'=>'30', '34'=>'3');
        if(isset($arr_map[$prodId])) return $arr_map[$prodId];
        else return $prodId;
    }

    function getProdInfo($prodId){
        $info = $this->getMemcache("prod$prodId");
        if($info === false){
            $info = $this->setProdInfo($prodId);
        }

        return $info;
    }

    function getVendorCommissionData($vendorId, $productId){
        $comm = $this->getMemcache("vendorcomm_" . $vendorId . "_" . $productId);
        if($comm === false){
            $comm = $this->setVendorCommissionData($vendorId, $productId);
        }
        return $comm;
    }

    function setVendorCommissionData($vendorId, $productId, $newData = array()){
        $userObj = ClassRegistry::init('User');
        $data = $userObj->query("SELECT * FROM vendors_commissions WHERE vendor_id=$vendorId AND product_id=$productId");
        $comm = $data['0']['vendors_commissions'];

        if( ! empty($newData)){
            $arr = array();
            foreach($newData as $k=>$v){
                $arr[] = "$k='$v'";
                $comm[$k] = $v;
            }
            $userObj->query("UPDATE vendors_commissions SET " . implode(",", $arr) . " WHERE vendor_id=$vendorId AND product_id=$productId");
        }
        $this->setMemcache("vendorcomm_" . $vendorId . "_" . $productId, $comm, 12 * 60 * 60);
        return $comm;
    }

    function getVendorId($vendor){
        $info = $this->getMemcache("vendorName_" . $vendor);
        if($info === false){
            $invoiceObj = ClassRegistry::init('Invoice');

            $vData = $invoiceObj->query("SELECT id FROM vendors WHERE shortForm = '$vendor'");
            $info = $vData[0]['vendors']['id'];
            $this->setMemcache("vendorName_" . $vendor, $info, 24 * 60 * 60);
        }

        return $info;
    }

    function getVendorInfo($vendorId){
        $info = $this->getMemcache("vendor$vendorId");
        if($info === false){
            $info = $this->setVendorInfo($vendorId);
        }

        return $info;
    }

    function setVendorInfo($vendorId, $info = null){
        $invoiceObj = ClassRegistry::init('Invoice');

        $info1 = $invoiceObj->query("SELECT * FROM vendors WHERE id = $vendorId");
        $info1 = $info1['0']['vendors'];
        if(empty($info1)){
            return FALSE;
        }
        if( ! empty($info)){
            $arr = array();
            foreach($info as $k=>$v){
                if($k == 'machine_id') continue;
                $arr[] = "$k='$v'";
                $info1[$k] = $v;
            }
            $invoiceObj->query("UPDATE vendors SET " . implode(",", $arr) . " WHERE id = $vendorId");
        }
        $this->setMemcache("vendor$vendorId", $info1, 30 * 60);
        return $info1;
    }

    function getVMNList($src = null){
        // $ret = array();
        if($src == 'fromLogin'){ // to send vmn list with loginResponse
            $ret = $this->General->findVar('VMNList');
            $ret = json_decode($ret, true);
            $res = array();
            if(count($ret) > 0){
                foreach($ret as $key=>$value){
                    array_push($res, $value['no']);
                }
                $ret = $res;
            }
        }
        else{
            $ret = $this->General->findVar('VMNList');
            $ret = json_decode($ret, true);
        }
        return $ret;
    }

    function setProdInfo1($prodId){
        $invoiceObj = ClassRegistry::init('Slaves');

        $this->info = $this->info_vendor_ids = $this->info_vendors_n = array();
        $this->info['vendors'] = array();

        $this->manage_prod_info_data($invoiceObj, $prodId);
        // print_r($this->info);
        // return;
        if($this->info['automate'] == 1){
            $this->generate_auto_priority_list($invoiceObj, $prodId);
        }


        $this->setMemcache("prod$prodId", $this->info, 60);
        return $this->info;
    }

    function setProdInfo($prodId){
        $invoiceObj = ClassRegistry::init('Slaves');
        $info = array();
        $info['vendors'] = array();
        $vendor_ids = array();
        $vendors_n = array();

        $product_vend_commission_data = $invoiceObj->query("SELECT services.id,services.name,vendors_commissions.*, products.per_sim_capacity,products.circle_yes, products.circle_no, products.automate_flag, vendors.shortForm, vendors.update_flag, vendors.modem_grade, products.price, products.oprDown, products.down_note , products.name,products.min,products.max,products.invalid,products.blocked_slabs,inv_supplier_operator.commission_type_formula FROM products left join vendors_commissions ON (vendors_commissions.product_id= products.id AND vendors_commissions.oprDown = 0) left join vendors on (vendors_commissions.vendor_id=vendors.id) join services ON (products.service_id = services.id) left join inv_supplier_vendor_mapping on (inv_supplier_vendor_mapping.vendor_id = vendors.id and vendors.update_flag = 0) left join inv_supplier_operator on (inv_supplier_operator.supplier_id = inv_supplier_vendor_mapping.supplier_id AND inv_supplier_operator.operator_id = products.id) WHERE products.id=$prodId AND vendors.show_flag = 1 order by vendors_commissions.active desc,discount_commission desc,commission_fixed desc");
        // echo "<hr>1";
        // var_dump($product_vend_commission_data);
        foreach($product_vend_commission_data as $res){
            $info['service_id'] = $res['services']['id'];
            $info['service_name'] = $res['services']['name'];
            $info['min'] = $res['products']['min'];
            $info['max'] = $res['products']['max'];
            $info['automate'] = $res['products']['automate_flag'];
            $info['price'] = $res['products']['price'];
            $info['invalid'] = $res['products']['invalid'];
            $info['name'] = $res['products']['name'];
            $info['oprDown'] = $res['products']['oprDown'];
            $info['down_note'] = $res['products']['down_note'];
            $info['circles_yes'] = strtoupper(trim($res['products']['circle_yes']));
            $info['circles_no'] = strtoupper(trim($res['products']['circle_no']));
            $info['per_sim_capacity'] = trim(($res['products']['per_sim_capacity'] > 0) ? $res['products']['per_sim_capacity'] : 1);
            $info['blocked_slabs'] = explode(",", trim($res['products']['blocked_slabs']));

            if( ! empty($res['vendors_commissions']['vendor_id'])){

                $res['vendors_commissions']['discount_commission'] = (!empty($res['inv_supplier_operator']['commission_type_formula']) && $res['services']['id'] != 6) ? $res['inv_supplier_operator']['commission_type_formula'] : floatval($res['vendors_commissions']['discount_commission']);

                if(!empty($res['vendors_commissions']['discount_commission'])){

                    $res['vendors_commissions']['discount_commission'] .= '%';
                }
                else {
                    $res['vendors_commissions']['discount_commission'] = $res['vendors_commissions']['commission_fixed'];
                }

                $data = array('vendor_id'=>$res['vendors_commissions']['vendor_id'], 'shortForm'=>$res['vendors']['shortForm'], 'discount_commission'=>$res['vendors_commissions']['discount_commission'],'circles_yes'=>explode(",", $res['vendors_commissions']['circles_yes']),
                        'circles_no'=>explode(",", $res['vendors_commissions']['circles_no']), 'circle'=>trim($res['vendors_commissions']['circle']), 'update_flag'=>$res['vendors']['update_flag'], 'denom_yes'=>explode(",", trim($res['vendors_commissions']['denom_yes'])),
                        'denom_no'=>explode(",", trim($res['vendors_commissions']['denom_no'])), 'from_STD'=>trim($res['vendors_commissions']['from_STD']), 'to_STD'=>trim($res['vendors_commissions']['to_STD']), 'denom_primary'=>trim($res['vendors_commissions']['denom_primary']),
                        'denom_circle'=>trim($res['vendors_commissions']['denom_circle']), 'modem_grade'=>trim($res['vendors']['modem_grade']), 'is_disabled'=>$res['vendors_commissions']['is_disabled'], 'retailers'=>($res['vendors_commissions']['is_disabled'] == 0) ? $res['vendors_commissions']['retailer_ids']: '', 'distributors'=>($res['vendors_commissions']['is_disabled'] == 0) ? $res['vendors_commissions']['distributor_ids'] : '');

                $info['vendors'][] = $data;
                $vendor_ids[] = $res['vendors_commissions']['vendor_id'];

                $vendors_n[$res['vendors_commissions']['vendor_id']] = $data;
            }
        }


        if($info['automate'] == 1){
            $opr_ids = $this->getOtherProds($prodId);
            $non_primary = array();
            $cap_non_primary = ($prodId == 15 ) ? 75 : 100; // 75% for vodafone and others 100%
            //$vendors_data = $invoiceObj->query("select vendor_id,sum(planned_sale) as planned_sale,sum(base_amount) as base_amount,sum(totalsale) as totalsale,sum(sims) as sims from (SELECT devices_data.vendor_id,inv_planning_sheet.planned_sale,inv_planning_sheet.base_amount,sum(devices_data.sale) as totalsale,sum(if(devices_data.device_id = devices_data.par_bal AND devices_data.block = '0' AND devices_data.stop_flag=0 AND devices_data.balance > 10 AND devices_data.recharge_flag = 1 AND devices_data.active_flag = 1, 1, 0)) as sims FROM inv_planning_sheet INNER JOIN devices_data ON (devices_data.supplier_operator_id = inv_planning_sheet.supplier_operator_id) WHERE devices_data.sync_date = '".date('Y-m-d')."' AND devices_data.opr_id in ($opr_ids) group by devices_data.vendor_id,devices_data.supplier_operator_id having (sims > 0)) as t group by t.vendor_id");

            $vendors_data = $invoiceObj->query("SELECT devices_data.vendor_id,devices_data.supplier_operator_id,devices_data.inv_supplier_id,inv_planning_sheet.base_amount,inv_planning_sheet.max_sale_capacity,sum(devices_data.sale) as totalsale,sum(if(devices_data.device_id = devices_data.par_bal AND devices_data.block = '0' AND devices_data.stop_flag=0 AND devices_data.balance >= 10 AND devices_data.recharge_flag = 1 AND devices_data.active_flag = 1, 1, 0)) as sims, inv_modem_planning_sheet.target
FROM inv_planning_sheet
INNER JOIN devices_data ON (devices_data.supplier_operator_id = inv_planning_sheet.supplier_operator_id)
INNER JOIN inv_modem_planning_sheet ON (inv_modem_planning_sheet.supplier_operator_id = devices_data.supplier_operator_id AND devices_data.vendor_id =inv_modem_planning_sheet.vendor_id)
WHERE devices_data.sync_date = '".date('Y-m-d')."' AND devices_data.opr_id in ($opr_ids) group by devices_data.vendor_id,devices_data.supplier_operator_id having (sims > 0)");


            $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." ::$prodId:: modem_vendor_data:: ".  json_encode($vendors_data));

            $target_sale_supplier = array();
            $target_sale_vendor = array();
            foreach ($vendors_data as $vendor){
                if(!isset($target_sale_supplier[$vendor['devices_data']['supplier_operator_id']])) {
                    $target_sale_supplier[$vendor['devices_data']['supplier_operator_id']]['target'] = 0;
                    $target_sale_supplier[$vendor['devices_data']['supplier_operator_id']]['base'] = 0;
                }
                $target_sale_supplier[$vendor['devices_data']['supplier_operator_id']]['target'] += $vendor['inv_modem_planning_sheet']['target'];

                $target_sale_supplier[$vendor['devices_data']['supplier_operator_id']]['base'] = $vendor['inv_planning_sheet']['max_sale_capacity'];
                $target_sale_supplier[$vendor['devices_data']['supplier_operator_id']]['supplier_id'] = $vendor['devices_data']['inv_supplier_id'];


                if(!isset($target_sale_vendor[$vendor['devices_data']['vendor_id']])){
                    $target_sale_vendor[$vendor['devices_data']['vendor_id']]['target'] = 0;
                    $target_sale_vendor[$vendor['devices_data']['vendor_id']]['sims'] = 0;
                    $target_sale_vendor[$vendor['devices_data']['vendor_id']]['sale'] = 0;
                    $target_sale_vendor[$vendor['devices_data']['vendor_id']]['base'] = 0;
                }

                $target_sale_vendor[$vendor['devices_data']['vendor_id']]['target'] += $vendor['inv_modem_planning_sheet']['target'];
                $target_sale_vendor[$vendor['devices_data']['vendor_id']]['sims'] += $vendor['0']['sims'];
                $target_sale_vendor[$vendor['devices_data']['vendor_id']]['sale'] += $vendor['0']['totalsale'];
                $target_sale_vendor[$vendor['devices_data']['vendor_id']]['base'] += $vendor['inv_planning_sheet']['max_sale_capacity'];
                $target_sale_vendor[$vendor['devices_data']['vendor_id']]['data'][] = $vendor;
            }

            $priority_1 = array();
            $priority_2 = array();
            $cut_off = array();
            $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." ::$prodId:: target_sale_vendor:: ".  json_encode($target_sale_vendor));


            foreach ($target_sale_vendor as $vendor_id=>$vendor){
                if(!in_array($vendor_id,$vendor_ids))continue;

                $r2_sale = $vendor['base'] - $vendor['sale'];
                $r1_sale = $vendor['target'] - $vendor['sale'];

                if($vendor['sale']*100/$vendor['target'] > $cap_non_primary){
                    $non_primary[] = $vendor_id;
                }
                if($vendor['sims'] > 0 && $r1_sale > 0){
                    $weight = $r1_sale/$vendor['sims'];
                    $priority_1[$weight] = $vendor_id;
                }
                else if($vendor['sims'] > 0 && $r2_sale > 0){
                    $weight = $r2_sale/$vendor['sims'];
                    $priority_2[$weight] = $vendor_id;
                }

                $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." :: $prodId:: $vendor_id ::r1_sale $r1_sale :: r2_sale $r2_sale");


                //$modem_cutoff_arr = array();
                foreach($vendor['data'] as $dt){
                    $supplier = $dt['devices_data']['supplier_operator_id'];
                    $target_supp = $target_sale_supplier[$supplier]['target'];
                    $base_supp = $target_sale_supplier[$supplier]['base'];

                    $ratio = ($base_supp < $target_supp) ? 1 :  ($base_supp/$target_supp);
                    $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." :: $prodId:: $vendor_id :: ".$target_sale_supplier[$supplier]['supplier_id']." :: base $base_supp:: target_supp ::$target_supp:: vendor_supp_target:: ".$dt['inv_modem_planning_sheet']['target']."::vendor_supp_sale::".$dt['0']['totalsale']." :: ratio $ratio");

                    if($dt['0']['totalsale'] > $ratio*$dt['inv_modem_planning_sheet']['target']){
                        $query = "query=salestop&supplier_id=".$target_sale_supplier[$supplier]['supplier_id']."&product=$opr_ids&flag=1";
                        $this->async_request_via_redis($query,$vendor_id);
                        $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." :: $prodId:: $vendor_id ::cutoff query :: $query");
                    }
                }


                if($r1_sale < 0 && $r2_sale < 0){
                    $cut_off[] = $vendor_id;
                }

                $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." :: $prodId:: $vendor_id ::vendors cutoffs  :: ".json_encode($cut_off));
            }


            //get other vendors as well
            $vendors_data = $invoiceObj->query("SELECT vendors.id,base_amount,max_sale_capacity,imps.target FROM `inv_planning_sheet` as ips inner join inv_supplier_operator as iso ON (ips.supplier_operator_id = iso.id) inner join inv_modem_planning_sheet as imps ON (imps.supplier_operator_id = iso.id) inner join inv_supplier_vendor_mapping as isvm ON (isvm.supplier_id = iso.supplier_id) inner join vendors ON (vendors.id = isvm.vendor_id) WHERE vendors.update_flag = 0 AND operator_id in ($opr_ids)");

            foreach($vendors_data as $vd){
                if(!in_array($vd['vendors']['id'],$vendor_ids))continue;

                $prod = $this->getParentProd($prodId);

                $cap_per_min = $this->getMemcache("cap_api_".$prod."_".$vd['vendors']['id']);

                $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." :: $prod:: ".$vd['vendors']['id']." ::cap_per_min $cap_per_min :: "."cap_api_".$prod."_".$vd['vendors']['id']);

                if($cap_per_min !== false && $cap_per_min > 0){
                    //$sale = $this->getMemcache("sale_".$prodId."_".$vd['vendors']['id']);
                    $sale = $this->getMemcache("sale_".$prodId."_".$vd['vendors']['id']);
                    $r2_sale = $vd['ips']['max_sale_capacity'] - $sale;
                    $r1_sale = $vd['imps']['target'] - $sale;

                    if($sale*100/$vd['imps']['target'] > $cap_non_primary){
                        $non_primary[] = $vd['vendors']['id'];
                    }

                    $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." :: $prodId:: ".$vd['vendors']['id']." ::r1_sale $r1_sale :: r2_sale $r2_sale :: $cap_per_min");


                    if($r1_sale > 0){
                        $weight = $r1_sale/$cap_per_min;
                        $priority_1[$weight] = $vd['vendors']['id'];
                    }
                    else if($r2_sale > 0){
                        $weight = $r2_sale/$cap_per_min;
                        $priority_2[$weight] = $vd['vendors']['id'];
                    }

                    if($r1_sale < 0 && $r2_sale < 0){
                        $cut_off[] =$vd['vendors']['id'];
                    }
                }

            }

            $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." :: $prodId:: priority1 :: ".  json_encode($priority_1)." :: priority2 :: ". json_encode($priority_2));

            krsort($priority_1);
            krsort($priority_2);

            $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." :: after sort :: $prodId:: priority1 :: ".  json_encode($priority_1)." :: priority2 :: ". json_encode($priority_2));

            $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." :: cutoff vendors :: $prodId:: ".  json_encode($cut_off));

            $info['vendors'] = array();
            foreach($priority_1 as $k => $v){
                $info['vendors'][] = $vendors_n[$v];
                $final[] = $v;
            }
            foreach($priority_2 as $k => $v){
                $info['vendors'][] = $vendors_n[$v];
                $final[] = $v;
            }

            foreach($vendors_n as $k => $v){
                if(in_array($k,$final))continue;
                if(in_array($k,$cut_off))continue;
                $info['vendors'][] = $v;
            }

            /**
             * adhock for all transaction between 23:45 - 24:00 Hrs priority vendors will be in reverse order, so that if api vendor
             * unable to clear txn modem should able to clear them.
             */
            if(date('H') == "23" && date("i") >= 45){
                $info['vendors'] = array_reverse($info['vendors'],true);
            }

            $info['non_primary'] = $non_primary;
            $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." :: final :: $prodId :: ". json_encode($info['vendors']) . "::non-primary vendors::".json_encode($info['non_primary']));
        }
        $this->setMemcache("prod$prodId",$info,5*60);
        return $info;
    }

    function generate_auto_priority_list($invoiceObj, $prodId){
        $opr_ids = $this->getOtherProds($prodId);
        $current_date = date('Y-m-d');
        $this->info_non_primary = array();
        $this->info_priority_suppliers = array();
        $this->info_cap_non_primary = ($prodId == 15) ? 75 : 100; // 75% for vodafone and others 100%
        $this->info_total_reqired_sims_count = ($this->info['per_sim_capacity'] > 0) ? $this->predict_required_sims($invoiceObj, $opr_ids, $current_date) :  - 1;

        $this->info_target_sale_supplier = $this->info_target_sale_vendor = array();
        $this->info_total_available_sims_count = $this->info_total_usable_sims_count = 0;
        $this->generate_data_with_target_sale($invoiceObj, $opr_ids, $prodId, $current_date);

        $this->info_priority_1 = $this->info_priority_2 = $this->info_cut_off = array();

        $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " ::$prodId:: target_sale_vendor:: " . json_encode($this->info_target_sale_vendor));

        $this->create_modem_vendor_priority($prodId);

        $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " :: $prodId:: $vendor_id ::vendors cutoffs  :: " . json_encode($this->info_cut_off));

        $this->create_api_vendor_priority($invoiceObj, $prodId, $opr_ids);

        $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " :: $prodId:: priority1 :: " . json_encode($this->info_priority_1) . " :: priority2 :: " . json_encode($this->info_priority_2));

        krsort($this->info_priority_1);
        krsort($this->info_priority_2);

        $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " :: after sort :: $prodId:: priority1 :: " . json_encode($this->info_priority_1) . " :: priority2 :: " . json_encode($this->info_priority_2));
        $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " :: cutoff vendors :: $prodId:: " . json_encode($this->info_cut_off));

        $this->finalize_priority_list();

        $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " :: final :: $prodId :: " . json_encode($info['vendors']) . "::non-primary vendors::" . json_encode($info['non_primary']));
    }

    function finalize_priority_list(){
        $this->info['vendors'] = array();
        foreach($this->info_priority_1 as $k=>$v){
            $this->info['vendors'][] = $this->info_vendors_n[$v];
            $final[] = $v;
        }
        foreach($this->info_priority_2 as $k=>$v){
            $this->info['vendors'][] = $this->info_vendors_n[$v];
            $final[] = $v;
        }

        foreach($this->info_vendors_n as $k=>$v){
            if(in_array($k, $final)) continue;
            if(in_array($k, $this->info_cut_off)) continue;
            $this->info['vendors'][] = $v;
        }

        /**
         * adhock for all transaction between 23:45 - 24:00 Hrs priority vendors will be in reverse order, so that if api vendor
         * unable to clear txn modem should able to clear them.
         */
        if(date('H') == "23" && date("i") >= 45){
            $info['vendors'] = array_reverse($info['vendors'], true);
        }

        $info['non_primary'] = $this->info_non_primary;
    }

    function priority_basedon_requirement(){
        $done = false;
        $vendors_sims = array();
        $total_sims = 0;
        krsort($this->info_vendor_supplier_priority_1);
        krsort($this->info_vendor_supplier_priority_2);

        foreach($this->info_vendor_supplier_priority_1 as $vends){

            if( ! isset($vendors_sims[$vends['vendor_id']])){
                $vendors_sims[$vends['vendor_id']]['sims'] = 0;
                $vendors_sims[$vends['vendor_id']]['sale_left'] = 0;
            }
            $vendors_sims[$vends['vendor_id']]['sims'] += $vends['sims'];
            $vendors_sims[$vends['vendor_id']]['sale_left'] += $vends['sale_left'];

            $total_sims += $vends['sims'];
            if($total_sims >= $this->info_total_reqired_sims_count){
                $done = true;
                break;
            }
        }

        if( ! $done){
            $vendors_sims = array();
            $total_sims = 0;
            foreach($this->info_vendor_supplier_priority_2 as $vends){

                if( ! isset($vendors_sims[$vends['vendor_id']])){
                    $vendors_sims[$vends['vendor_id']]['sims'] = 0;
                    $vendors_sims[$vends['vendor_id']]['sale_left'] = 0;
                }
                $vendors_sims[$vends['vendor_id']]['sims'] += $vends['sims'];
                $vendors_sims[$vends['vendor_id']]['sale_left'] += $vends['sale_left'];

                $total_sims += $vends['sims'];
                if($total_sims >= $this->info_total_reqired_sims_count){
                    $done = true;
                    break;
                }
            }
        }

        return $vendors_sims;
    }

    function setRedisQueueLength($vendor_id, $prodId, $sims){
        $prod = $this->getParentProd($prodId);
        $redisObj = $this->openservice_redis();
        $key = "queue_new_" . $vendor_id . "_" . $prod;
        $redisObj->setex($key, 120, $sims);
    }

    function create_modem_vendor_priority($prodId){
        $vendors_sims = ($this->info_total_reqired_sims_count > 0) ? $this->priority_basedon_requirement() : array();

        foreach($this->info_target_sale_vendor as $vendor_id=>$vendor){

            $r2_sale = $vendor['base'] - $vendor['sale'];
            $r1_sale = $vendor['target'] - $vendor['sale'];

            if($vendor['sale'] * 100 / $vendor['target'] > $this->info_cap_non_primary){
                $this->info_non_primary[] = $vendor_id;
            }

            if(isset($vendors_sims[$vendor_id])){
                $weight = $vendors_sims[$vendor_id]['sale_left'] / $vendors_sims[$vendor_id]['sims'];
                while(isset($this->info_priority_1[$weight]))
                    $weight += 1;
                $this->info_priority_1[$weight] = $vendor_id;
                $sims = $vendors_sims[$vendor_id]['sims'];
            }
            else if($vendor['sims'] > 0 && $r1_sale > 0){
                $weight = $r1_sale / $vendor['sims'];
                while(isset($this->info_priority_1[$weight]))
                    $weight += 1;
                $this->info_priority_1[$weight] = $vendor_id;
                $sims = $vendor['sims'];
            }
            else if($vendor['sims'] > 0 && $r2_sale > 0){
                $weight = $r2_sale / $vendor['sims'];
                while(isset($this->info_priority_2[$weight]))
                    $weight += 1;
                $this->info_priority_2[$weight] = $vendor_id;
                $sims = $vendor['sims'];
            }

            $this->setRedisQueueLength($vendor_id, $prodId, $sims);

            $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " :: $prodId:: $vendor_id ::r1_sale $r1_sale :: r2_sale $r2_sale");

            $this->manage_cuttoff_modem_vendor($vendor, $prodId, $vendor_id);

            if($r1_sale < 0 && $r2_sale < 0){
                $this->info_cut_off[] = $vendor_id;
            }
        }
    }

    function create_api_vendor_priority($invoiceObj, $prodId, $opr_ids){
        // get other vendors as well
        $vendors_data = $invoiceObj->query("SELECT vendors.id,base_amount,max_sale_capacity,imps.target FROM `inv_planning_sheet` as ips inner join inv_supplier_operator as iso ON (ips.supplier_operator_id = iso.id) inner join inv_modem_planning_sheet as imps ON (imps.supplier_operator_id = iso.id) inner join inv_supplier_vendor_mapping as isvm ON (isvm.supplier_id = iso.supplier_id) inner join vendors ON (vendors.id = isvm.vendor_id) WHERE vendors.update_flag = 0 AND operator_id in ($opr_ids)");

        foreach($vendors_data as $vd){
            if( ! in_array($vd['vendors']['id'], $vendor_ids)) continue;

            $prod = $this->getParentProd($prodId);

            $cap_per_min = $this->getMemcache("cap_api_" . $prod . "_" . $vd['vendors']['id']);

            $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " :: $prod:: " . $vd['vendors']['id'] . " ::cap_per_min $cap_per_min :: " . "cap_api_" . $prod . "_" . $vd['vendors']['id']);

            if($cap_per_min !== false && $cap_per_min > 0){
                $sale = $this->getMemcache("sale_" . $prodId . "_" . $vd['vendors']['id']);
                $r2_sale = $vd['ips']['max_sale_capacity'] - $sale;
                $r1_sale = $vd['imps']['target'] - $sale;

                if($sale * 100 / $vd['imps']['target'] > $this->info_cap_this->info_non_primary){
                    $this->info_non_primary[] = $vd['vendors']['id'];
                }

                $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " :: $prodId:: " . $vd['vendors']['id'] . " ::r1_sale $r1_sale :: r2_sale $r2_sale :: $cap_per_min");

                if($r1_sale > 0){
                    $weight = $r1_sale / $cap_per_min;
                    while(isset($this->info_priority_1[$weight]))
                        $weight += 1;
                    $this->info_priority_1[$weight] = $vd['vendors']['id'];
                }
                else if($r2_sale > 0){
                    $weight = $r2_sale / $cap_per_min;
                    while(isset($this->info_priority_2[$weight]))
                        $weight += 1;
                    $this->info_priority_2[$weight] = $vd['vendors']['id'];
                }

                if($r1_sale < 0 && $r2_sale < 0){
                    $this->info_cut_off[] = $vd['vendors']['id'];
                }
            }
        }
    }

    function manage_cuttoff_modem_vendor($vendor, $prodId, $vendor_id){
        // $modem_cutoff_arr = array();
        foreach($vendor['data'] as $dt){
            $supplier = $dt['devices_data']['supplier_operator_id'];
            $target_supp = $this->info_target_sale_supplier[$supplier]['target'];
            $base_supp = $this->info_target_sale_supplier[$supplier]['base'];

            $ratio = ($base_supp < $target_supp) ? 1 : ($base_supp / $target_supp);
            $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " :: $prodId:: $vendor_id :: " . $target_sale_supplier[$supplier]['supplier_id'] . " :: base $base_supp:: target_supp ::$target_supp:: vendor_supp_target:: " . $dt['inv_modem_planning_sheet']['target'] . "::vendor_supp_sale::" . $dt['0']['totalsale'] . " :: ratio $ratio");

            if($dt['0']['totalsale'] > $ratio * $dt['inv_modem_planning_sheet']['target']){
                $query = "query=salestop&supplier_id=" . $this->info_target_sale_supplier[$supplier]['supplier_id'] . "&product=$opr_ids&flag=1";
                $this->async_request_via_redis($query, $vendor_id);
                $this->General->logData("/mnt/logs/prioritylog.txt", date("Y-m-d H:i:s") . " :: $prodId:: $vendor_id ::cutoff query :: $query");
            }
        }
    }

    function generate_data_with_target_sale($invoiceObj, $opr_ids, $prodId, $current_date){
        $vendors_data = $invoiceObj->query("SELECT devices_data.vendor_id,inv_supplier_operator.priority_flag,devices_data.supplier_operator_id,devices_data.inv_supplier_id,inv_planning_sheet.base_amount,inv_planning_sheet.max_sale_capacity,sum(devices_data.sale) as totalsale,sum(if(devices_data.device_id = devices_data.par_bal AND devices_data.block = '0' AND devices_data.stop_flag=0 AND devices_data.balance >= 10 AND devices_data.recharge_flag = 1 AND devices_data.bal_range < devices_data.balance AND devices_data.active_flag = 1, 1, 0)) as sims, inv_modem_planning_sheet.target
			FROM inv_planning_sheet
			INNER JOIN devices_data ON (devices_data.supplier_operator_id = inv_planning_sheet.supplier_operator_id)
			INNER JOIN inv_modem_planning_sheet ON (inv_modem_planning_sheet.supplier_operator_id = devices_data.supplier_operator_id AND devices_data.vendor_id =inv_modem_planning_sheet.vendor_id)
                        INNER JOIN inv_supplier_operator ON (inv_modem_planning_sheet.supplier_operator_id = inv_supplier_operator.id)
				WHERE devices_data.sync_date = '$current_date' AND devices_data.opr_id in ($opr_ids)
				GROUP BY devices_data.vendor_id,devices_data.supplier_operator_id having (sims > 0)");

        // $this->General->logData("/mnt/logs/prioritylog.txt",date("Y-m-d H:i:s")." ::$prodId:: modem_vendor_data:: ". json_encode($vendors_data));

        foreach($vendors_data as $vendor){
            if( ! isset($this->info_target_sale_supplier[$vendor['devices_data']['supplier_operator_id']])){
                $this->info_target_sale_supplier[$vendor['devices_data']['supplier_operator_id']]['target'] = 0;
                $this->info_target_sale_supplier[$vendor['devices_data']['supplier_operator_id']]['base'] = 0;
            }

            $this->info_target_sale_supplier[$vendor['devices_data']['supplier_operator_id']]['target'] += $vendor['inv_modem_planning_sheet']['target'];

            $this->info_target_sale_supplier[$vendor['devices_data']['supplier_operator_id']]['base'] = $vendor['inv_planning_sheet']['max_sale_capacity'];
            $this->info_target_sale_supplier[$vendor['devices_data']['supplier_operator_id']]['supplier_id'] = $vendor['devices_data']['inv_supplier_id'];

            if( ! isset($this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']])){
                $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['target'] = 0;
                $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['sims'] = 0;
                $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['usable_sims_count'] = 0;
                $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['sale'] = 0;
                $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['base'] = 0;
            }

            $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['target'] += $vendor['inv_modem_planning_sheet']['target'];
            $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['sims'] += $vendor['0']['sims'];
            $usable_sims = ($vendor['0']['totalsale'] < $vendor['inv_modem_planning_sheet']['target']) ? $vendor['0']['sims'] : 0;
            $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['usable_sims_count'] += $usable_sims;
            $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['sale'] += $vendor['0']['totalsale'];
            $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['base'] += $vendor['inv_planning_sheet']['max_sale_capacity'];

            $this->info_target_sale_vendor_supp[$vendor['devices_data']['vendor_id']][$vendor['devices_data']['supplier_operator_id']]['max_base'] = $vendor['inv_planning_sheet']['max_sale_capacity'];
            $this->info_target_sale_vendor_supp[$vendor['devices_data']['vendor_id']][$vendor['devices_data']['supplier_operator_id']]['target_sale'] = $vendor['inv_modem_planning_sheet']['target'];
            $this->info_target_sale_vendor_supp[$vendor['devices_data']['vendor_id']][$vendor['devices_data']['supplier_operator_id']]['avail_sims'] = $vendor['0']['sims'];
            $this->info_target_sale_vendor_supp[$vendor['devices_data']['vendor_id']][$vendor['devices_data']['supplier_operator_id']]['usable_sims'] = $usable_sims;
            $this->info_target_sale_vendor_supp[$vendor['devices_data']['vendor_id']][$vendor['devices_data']['supplier_operator_id']]['actual_sale'] = $vendor['0']['totalsale'];
            // $this->info_target_sale_vendor_supp[$vendor['devices_data']['vendor_id']]['target_based_capability'][$vendor['devices_data']['supplier_operator_id']] = ( ( $vendor['inv_modem_planning_sheet']['target'] - $vendor['0']['totalsale'] ) / $usable_sims) ;
            // $this->info_target_sale_vendor_supp[$vendor['devices_data']['vendor_id']]['maxcap_based_capability'][$vendor['devices_data']['supplier_operator_id']] = ( ( $vendor['inv_planning_sheet']['max_sale_capacity'] - $vendor['0']['totalsale'] ) / $vendor['0']['sims'] ) ;

            $this->info_target_sale_vendor[$vendor['devices_data']['vendor_id']]['data'][] = $vendor;
            $this->info_total_available_sims_count += $vendor['0']['sims'];
            $this->info_total_usable_sims_count += $usable_sims;

            if($usable_sims > 0){
                $target_weight = (($vendor['inv_modem_planning_sheet']['target'] - $vendor['0']['totalsale']) / $usable_sims);
                $target_weight = ($vendor['inv_supplier_operator']['priority_flag'] == 1) ? 1000000000000 : $target_weight;
                while(isset($this->info_vendor_supplier_priority_1[$target_weight]))
                    $target_weight += 1;
                $this->info_vendor_supplier_priority_1[$target_weight] = array('vendor_id'=>$vendor['devices_data']['vendor_id'], 'sale_left'=>($vendor['inv_modem_planning_sheet']['target'] - $vendor['0']['totalsale']), 'sims'=>$usable_sims);
            }
            if($vendor['0']['sims'] > 0){
                $max_weight = (($vendor['inv_planning_sheet']['max_sale_capacity'] - $vendor['0']['totalsale']) / $vendor['0']['sims']);
                $max_weight= ($vendor['inv_supplier_operator']['priority_flag'] == 1) ? 1000000000000 : $max_weight;

                while(isset($this->info_vendor_supplier_priority_2[$max_weight]))
                    $max_weight += 1;
                $this->info_vendor_supplier_priority_2[$max_weight] = array('vendor_id'=>$vendor['devices_data']['vendor_id'], 'sale_left'=>($vendor['inv_planning_sheet']['max_sale_capacity'] - $vendor['0']['totalsale']), 'sims'=>$vendor['0']['sims']);
            }
        }
    }

    function predict_required_sims($invoiceObj, $opr_ids, $current_date){
        $current_datetime = date('Y-m-d H:i:s');
        $start_time = strtotime($current_datetime) - (60 * 5);
        $start_datetime = date('Y-m-d H:i:s', $start_datetime);

        $request_volume_data = $invoiceObj->query("SELECT minute(timestamp) as minute, count(1)  as cnt FROM vendors_activations where date='$current_date' and timestamp >= '$start_datetime' and timestamp <= '$current_datetime' and product_id in ($opr_ids) group by minute(timestamp) order by cnt desc limit 1");

        $max_request_per_min = isset($request_volume_data[0]['vendors_activations']['cnt']) ? $request_volume_data[0]['vendors_activations']['cnt'] : 0;

        return (($max_request_per_min / $this->info['per_sim_capacity']) * (1.2));
    }

    function manage_prod_info_data($invoiceObj, $prodId){
        $product_vend_commission_data = $invoiceObj->query("SELECT services.id,services.name,vendors_commissions.*, products.per_sim_capacity,products.circle_yes, products.circle_no, products.automate_flag, vendors.shortForm, vendors.update_flag, vendors.modem_grade, products.price, products.oprDown, products.down_note , products.name,products.min,products.max,products.invalid,products.blocked_slabs,inv_supplier_operator.commission_type_formula FROM products left join vendors_commissions ON (vendors_commissions.product_id= products.id AND vendors_commissions.oprDown = 0) left join vendors on (vendors_commissions.vendor_id=vendors.id) join services ON (products.service_id = services.id) left join inv_supplier_vendor_mapping on (inv_supplier_vendor_mapping.vendor_id = vendors.id and vendors.update_flag = 0) left join inv_supplier_operator on (inv_supplier_operator.supplier_id = inv_supplier_vendor_mapping.supplier_id AND inv_supplier_operator.operator_id = products.id) WHERE products.id=$prodId AND vendors.show_flag = 1 order by vendors_commissions.active desc,discount_commission desc,commission_fixed desc");
        // echo "<hr>1";
        // var_dump($product_vend_commission_data);
        foreach($product_vend_commission_data as $res){
            $this->info['service_id'] = $res['services']['id'];
            $this->info['service_name'] = $res['services']['name'];
            $this->info['min'] = $res['products']['min'];
            $this->info['max'] = $res['products']['max'];
            $this->info['automate'] = $res['products']['automate_flag'];
            $this->info['price'] = $res['products']['price'];
            $this->info['invalid'] = $res['products']['invalid'];
            $this->info['name'] = $res['products']['name'];
            $this->info['oprDown'] = $res['products']['oprDown'];
            $this->info['down_note'] = $res['products']['down_note'];
            $this->info['circles_yes'] = strtoupper(trim($res['products']['circle_yes']));
            $this->info['circles_no'] = strtoupper(trim($res['products']['circle_no']));
            $this->info['per_sim_capacity'] = trim(($res['products']['per_sim_capacity'] > 0) ? $res['products']['per_sim_capacity'] : 1);
            $this->info['blocked_slabs'] = explode(",", trim($res['products']['blocked_slabs']));

            if( ! empty($res['vendors_commissions']['vendor_id'])){

                $res['vendors_commissions']['discount_commission'] = (!empty($res['inv_supplier_operator']['commission_type_formula'])) ? $res['inv_supplier_operator']['commission_type_formula'] : $res['vendors_commissions']['discount_commission'];
                if(!empty($res['vendors_commissions']['discount_commission'])){
                    $res['vendors_commissions']['discount_commission'] .= '%';
                }
                else {
                    $res['vendors_commissions']['discount_commission'] = $res['vendors_commissions']['commission_fixed'];
                }
                $data = array('vendor_id'=>$res['vendors_commissions']['vendor_id'], 'shortForm'=>$res['vendors']['shortForm'], 'discount_commission'=>$res['vendors_commissions']['discount_commission'], 'circles_yes'=>explode(",", $res['vendors_commissions']['circles_yes']),
                        'circles_no'=>explode(",", $res['vendors_commissions']['circles_no']), 'circle'=>trim($res['vendors_commissions']['circle']), 'update_flag'=>$res['vendors']['update_flag'], 'denom_yes'=>explode(",", trim($res['vendors_commissions']['denom_yes'])),
                        'denom_no'=>explode(",", trim($res['vendors_commissions']['denom_no'])), 'from_STD'=>trim($res['vendors_commissions']['from_STD']), 'to_STD'=>trim($res['vendors_commissions']['to_STD']), 'denom_primary'=>trim($res['vendors_commissions']['denom_primary']),
                        'denom_circle'=>trim($res['vendors_commissions']['denom_circle']), 'modem_grade'=>trim($res['vendors']['modem_grade']), 'is_disabled'=>$res['vendors_commissions']['is_disabled'], 'retailers'=>($res['vendors_commissions']['is_disabled'] == 0) ? $res['vendors_commissions']['retailer_ids']: '', 'distributors'=>($res['vendors_commissions']['is_disabled'] == 0) ? $res['vendors_commissions']['distributor_ids'] : '');

                $this->info['vendors'][] = $data;
                $this->info_vendor_ids[] = $res['vendors_commissions']['vendor_id'];

                $this->info_vendors_n[$res['vendors_commissions']['vendor_id']] = $data;
            }
        }
        // echo "<hr>";
        // print_r($this->info);
    }

    function calculateSims(){
        $vendors_sims = array();
        $total_sims = 0;
        $vendor_ids = array();
        $done = false;

        foreach($this->info_vendor_supplier_priority_1 as $vends){
            if( ! in_array($vends['vendor_id'], $vendor_ids)) $vendor_ids[] = $vends['vendor_id'];
            if( ! isset($vendors_sims[$vends['vendor_id']])) $vendors_sims[$vends['vendor_id']] = 0;

            if( ! $done){
                $vendors_sims[$vends['vendor_id']] += $vends['sims'];
            }

            $total_sims += $vends['sims'];
            if($total_sims > $this->info_total_reqired_sims_count){
                $done = true;
            }
        }

        if($this->info_total_reqired_sims_count >= $this->info_total_available_sims_count || $this->info_total_reqired_sims_count ==  - 1){
            $set_avail = true;
        }
        else{
            if($this->info_total_usable_sims_count >= $this->info_total_reqired_sims_count){}
            else{}
            // $this->manage_access_sims_calculation();
        }
        // --- update redis value
        // return $total_usable_sims_count;
    }

    function manage_access_sims_calculation($total_reqired_sims_count, $total_available_sims_count, $total_usable_sims_count, $vendor_sale_data){
        if($total_usable_sims_count >= $total_reqired_sims_count){
            return $total_usable_sims_count = $total_usable_sims_count;
        }
        else{
            return $this->manage_sims_cal_with_alpha($total_reqired_sims_count, $total_available_sims_count, $total_usable_sims_count);
        }
    }

    function manage_sims_cal_with_alpha($total_reqired_sims_count, $total_available_sims_count, $total_usable_sims_count, $alpha_var = 1){
        $alpha = $this->calculate_alpha($total_reqired_sims_count, $total_available_sims_count, $total_usable_sims_count, $alpha_var);
        if(($alpha * $total_usable_sims_count) > $total_available_sims_count){
            return $total_usable_sims_count = $total_available_sims_count;
        }
        else{
            $alpha_var = $alpha_var + 0.1;
            return $this->manage_sims_cal_with_alpha($total_reqired_sims_count, $total_available_sims_count, $total_usable_sims_count, $alpha_var);
        }
    }

    function calculate_alpha($total_reqired_sims_count, $total_available_sims_count, $total_usable_sims_count, $alpha_var = 1){
        return ((($total_reqired_sims_count - $total_usable_sims_count) / ($total_available_sims_count - $total_usable_sims_count)) + $alpha_var);
    }

    function getVendors(){
        $vendors = $this->getMemcache('vendors');
        if(empty($vendors)){
            $vendors = $this->setVendors();
        }

        return $vendors;
    }

    function setVendors(){
        $invoiceObj = ClassRegistry::init('Slaves');
        $vendors = $invoiceObj->query("SELECT id,shortForm,company,balance,user_id,update_flag,ip,port FROM vendors WHERE show_flag = 1 order by company");
        $this->setMemcache('vendors', $vendors, 60 * 60 * 24);

        return $vendors;
    }

    function getInactiveVendors(){
        $vendors = $this->getMemcache('inactiveVendors');
        if($vendors === false){
            $vendors = $this->setInactiveVendors();
        }

        return $vendors;
    }

    function setInactiveVendors(){
        $invoiceObj = ClassRegistry::init('Slaves');
        $vendors = $invoiceObj->query("SELECT group_concat(id) as ids FROM vendors WHERE active_flag != 1");
        $vendors = explode(",", $vendors['0']['0']['ids']);
        $this->setMemcache('inactiveVendors', $vendors, 2 * 60);

        return $vendors;
    }

    function getProducts(){
        $products = $this->getMemcache('products');
        if(empty($products)){
            $invoiceObj = ClassRegistry::init('Slaves');
            $products = $invoiceObj->query("SELECT * FROM products");
            $this->setMemcache('products', $products, 60 * 60);
        }

        return $products;
    }

    function findOptimalVendor($vendors, $prodId, $automate_flag, $existing = array(), $transId = null){
        $arr_map = array('7'=>'8', '10'=>'9', '27'=>'9', '28'=>'12', '29'=>'11', '31'=>'30', '34'=>'3', '181'=>'18');

        foreach($vendors as $vend){
            $vendor_id = $vend['vendor_id'];
            if(in_array($vendor_id, $existing)) continue;

            if($vend['update_flag'] == 0){
                $prod = (isset($arr_map[$prodId])) ? $arr_map[$prodId] : $prodId;
                $data = $this->getMemcache("status_$prod" . "_$vendor_id");

                $this->General->logData("/mnt/logs/abc_$vendor_id.txt", "before decrement::$transId::$prod::$vendor_id::$data");

                if($data !== false && $data > 0){
                    $data = $this->decrementMemcache("status_$prod" . "_$vendor_id");
                    if($data >= 0){
                        $this->General->logData("/mnt/logs/abc_$vendor_id.txt", "after decrement::$transId::$prod::$vendor_id::$data");
                        $vend['key_vendor'] = "status_$prod" . "_$vendor_id";
                        return $vend;
                    }
                }

                if($data === false){
                    return $vend;
                }
            }
            else
                return $vend;
        }
    }

    function getActiveVendor($prodId, $mobile = null, $apiPartner = null, $primary_flag = true, $additional_param = array()){
        $success = true;

        $info = $this->getProdInfo($prodId);
        if(empty($info['vendors'])){
            $info = $this->setProdInfo($prodId);
        }

        $non_primary = (isset($info['non_primary'])) ? $info['non_primary'] : array();
        $api_flag = false;
        if($mobile != null){
            $prod_d = $this->General->getMobileDetailsNew($mobile); // need to open for mobile numbering changes
                                                                    // $prod_d = $this->General->getMobileDetails($mobile);

            if( ! $api_flag &&  ! empty($info['circles_yes']) &&  ! in_array($prod_d['area'], explode(",", $info['circles_yes']))){
                return array('status'=>'failure', 'code'=>'', 'description'=>'Cannot recharge on ' . $prod_d['area'] . ' circle', 'name'=>$info['name']);
            }

            if( ! $api_flag &&  ! empty($info['circles_no']) && in_array($prod_d['area'], explode(",", $info['circles_no']))){
                return array('status'=>'failure', 'code'=>'', 'description'=>'Cannot recharge on ' . $prod_d['area'] . ' circle', 'name'=>$info['name']);
            }
        }

        if($apiPartner == 6){ // b2c api partner
            $api_flag = true;
        }
        // distributor id & vendor id mapping, key will be distid_prodid & value will be vendorid
        // 75 - cotton_green 71-kandivali 10-GSM modem2

        $dist_vendor_mapping = $this->generate_local_vendor_map();
        // removing mira-rd
        // mira-rd : '758_2'=>'84','776_2'=>'84','866_2'=>'84','920_2'=>'84','14477_2'=>'84','28199_2'=>'84','41433_2'=>'84','77467_2'=>'84','12051_2'=>'84','36831_2'=>'84','103479_2'=>'84','107208_2'=>'84',
        // santacruz, andheri, jogeshwari
        // jogeshwari : '467_2'=>'99','478_2'=>'99','529_2'=>'99','535_2'=>'99','4905_2'=>'99','7335_2'=>'99','8272_2'=>'99','8285_2'=>'99','8315_2'=>'99','8320_2'=>'99','8324_2'=>'99','8428_2'=>'99','8461_2'=>'99','8552_2'=>'99','8824_2'=>'99','8854_2'=>'99','9109_2'=>'99','9115_2'=>'99','9117_2'=>'99','9121_2'=>'99','9171_2'=>'99','9420_2'=>'99','10364_2'=>'99','10442_2'=>'99','10505_2'=>'99','10557_2'=>'99','10815_2'=>'99','10821_2'=>'99','11393_2'=>'99','11602_2'=>'99','11845_2'=>'99','11872_2'=>'99','12356_2'=>'99','12412_2'=>'99','12965_2'=>'99','13116_2'=>'99','13385_2'=>'99','14069_2'=>'99','14116_2'=>'99','15662_2'=>'99','17260_2'=>'99','17309_2'=>'99','17361_2'=>'99','17481_2'=>'99','19647_2'=>'99','19727_2'=>'99','20165_2'=>'99','20680_2'=>'99','21749_2'=>'99','22035_2'=>'99','22263_2'=>'99','22268_2'=>'99','22650_2'=>'99','22831_2'=>'99','23179_2'=>'99','23439_2'=>'99','23874_2'=>'99','25691_2'=>'99','25694_2'=>'99','25785_2'=>'99','29116_2'=>'99','29307_2'=>'99','32099_2'=>'99','32821_2'=>'99','32829_2'=>'99','32836_2'=>'99','32842_2'=>'99','33247_2'=>'99','33810_2'=>'99','35427_2'=>'99','36598_2'=>'99','37910_2'=>'99','37938_2'=>'99','41726_2'=>'99','42481_2'=>'99','44507_2'=>'99','47040_2'=>'99','48184_2'=>'99','48945_2'=>'99','53435_2'=>'99','53908_2'=>'99','55067_2'=>'99','55224_2'=>'99','56693_2'=>'99','57604_2'=>'99','60347_2'=>'99','60959_2'=>'99','61723_2'=>'99','62791_2'=>'99','68860_2'=>'99','69308_2'=>'99','70125_2'=>'99','70879_2'=>'99','75657_2'=>'99','75658_2'=>'99','81390_2'=>'99','81550_2'=>'99','86768_2'=>'99','89449_2'=>'99','93546_2'=>'99','104088_2'=>'99','111577_2'=>'99','112777_2'=>'99','112945_2'=>'99','114790_2'=>'99','121013_2'=>'99','121757_2'=>'99','122192_2'=>'99','122748_2'=>'99','125172_2'=>'99'
        $ret_vendor_mapping = array('282_2'=>'96', '311_2'=>'96', '339_2'=>'96', '346_2'=>'96', '364_2'=>'96', '365_2'=>'96', '400_2'=>'96', '416_2'=>'96', '444_2'=>'96', '453_2'=>'96', '454_2'=>'96', '462_2'=>'96', '475_2'=>'96', '481_2'=>'96', '486_2'=>'96', '487_2'=>'96', '491_2'=>'96',
                '508_2'=>'96', '511_2'=>'96', '515_2'=>'96', '519_2'=>'96', '542_2'=>'96', '547_2'=>'96', '564_2'=>'96', '566_2'=>'96', '576_2'=>'96', '585_2'=>'96', '615_2'=>'96', '623_2'=>'96', '651_2'=>'96', '655_2'=>'96', '656_2'=>'96', '711_2'=>'96', '723_2'=>'96', '741_2'=>'96', '810_2'=>'96',
                '831_2'=>'96', '1008_2'=>'96', '1025_2'=>'96', '1059_2'=>'96', '1165_2'=>'96', '2181_2'=>'96', '2186_2'=>'96', '2190_2'=>'96', '2207_2'=>'96', '2209_2'=>'96', '2212_2'=>'96', '2223_2'=>'96', '2224_2'=>'96', '2227_2'=>'96', '2229_2'=>'96', '2235_2'=>'96', '2238_2'=>'96',
                '2247_2'=>'96', '2248_2'=>'96', '2251_2'=>'96', '2254_2'=>'96', '2257_2'=>'96', '2259_2'=>'96', '2260_2'=>'96', '2261_2'=>'96', '2262_2'=>'96', '2263_2'=>'96', '2270_2'=>'96', '2273_2'=>'96', '2277_2'=>'96', '2279_2'=>'96', '2282_2'=>'96', '2285_2'=>'96', '2287_2'=>'96',
                '2289_2'=>'96', '2291_2'=>'96', '2293_2'=>'96', '2308_2'=>'96', '2316_2'=>'96', '2325_2'=>'96', '2331_2'=>'96', '2349_2'=>'96', '2354_2'=>'96', '2357_2'=>'96', '2362_2'=>'96', '2363_2'=>'96', '2364_2'=>'96', '2367_2'=>'96', '2375_2'=>'96', '2377_2'=>'96', '2378_2'=>'96',
                '2387_2'=>'96', '2398_2'=>'96', '2410_2'=>'96', '2419_2'=>'96', '2434_2'=>'96', '2461_2'=>'96', '2468_2'=>'96', '2486_2'=>'96', '2489_2'=>'96', '2491_2'=>'96', '2521_2'=>'96', '2522_2'=>'96', '2523_2'=>'96', '2546_2'=>'96', '2608_2'=>'96', '2610_2'=>'96', '2621_2'=>'96',
                '2639_2'=>'96', '2649_2'=>'96', '2650_2'=>'96', '2660_2'=>'96', '2703_2'=>'96', '2708_2'=>'96', '2755_2'=>'96', '2763_2'=>'96', '2805_2'=>'96', '2828_2'=>'96', '2847_2'=>'96', '2887_2'=>'96', '2914_2'=>'96', '2916_2'=>'96', '2979_2'=>'96', '3006_2'=>'96', '3008_2'=>'96',
                '3009_2'=>'96', '3024_2'=>'96', '3105_2'=>'96', '3112_2'=>'96', '3398_2'=>'96', '3399_2'=>'96', '3579_2'=>'96', '3666_2'=>'96', '3694_2'=>'96', '3696_2'=>'96', '3796_2'=>'96', '3801_2'=>'96', '3818_2'=>'96', '3826_2'=>'96', '3827_2'=>'96', '3969_2'=>'96', '4020_2'=>'96',
                '4089_2'=>'96', '4341_2'=>'96', '4346_2'=>'96', '4385_2'=>'96', '4386_2'=>'96', '4401_2'=>'96', '4416_2'=>'96', '4421_2'=>'96', '4609_2'=>'96', '4640_2'=>'96', '4653_2'=>'96', '4655_2'=>'96', '4700_2'=>'96', '4730_2'=>'96', '4764_2'=>'96', '4775_2'=>'96', '4785_2'=>'96',
                '4818_2'=>'96', '4838_2'=>'96', '4854_2'=>'96', '4938_2'=>'96', '4980_2'=>'96', '4981_2'=>'96', '5016_2'=>'96', '5022_2'=>'96', '5133_2'=>'96', '5229_2'=>'96', '5231_2'=>'96', '5391_2'=>'96', '5430_2'=>'96', '5523_2'=>'96', '5531_2'=>'96', '5729_2'=>'96', '5809_2'=>'96',
                '5954_2'=>'96', '5984_2'=>'96', '5989_2'=>'96', '6100_2'=>'96', '6224_2'=>'96', '6247_2'=>'96', '6434_2'=>'96', '6453_2'=>'96', '6508_2'=>'96', '6519_2'=>'96', '6594_2'=>'96', '6683_2'=>'96', '6685_2'=>'96', '6742_2'=>'96', '7323_2'=>'96', '7521_2'=>'96', '7653_2'=>'96',
                '7796_2'=>'96', '7800_2'=>'96', '7849_2'=>'96', '7901_2'=>'96', '7990_2'=>'96', '8065_2'=>'96', '8107_2'=>'96', '8295_2'=>'96', '8338_2'=>'96', '8404_2'=>'96', '8894_2'=>'96', '9027_2'=>'96', '9100_2'=>'96', '9114_2'=>'96', '9137_2'=>'96', '9175_2'=>'96', '9225_2'=>'96',
                '9326_2'=>'96', '9387_2'=>'96', '9397_2'=>'96', '9413_2'=>'96', '9414_2'=>'96', '9459_2'=>'96', '9479_2'=>'96', '9587_2'=>'96', '9678_2'=>'96', '9796_2'=>'96', '9810_2'=>'96', '9923_2'=>'96', '9977_2'=>'96', '10171_2'=>'96', '10512_2'=>'96', '10520_2'=>'96', '10799_2'=>'96',
                '10836_2'=>'96', '11207_2'=>'96', '11255_2'=>'96', '11278_2'=>'96', '11628_2'=>'96', '11964_2'=>'96', '12053_2'=>'96', '12127_2'=>'96', '12142_2'=>'96', '12167_2'=>'96', '12241_2'=>'96', '12273_2'=>'96', '12284_2'=>'96', '12304_2'=>'96', '12479_2'=>'96', '12510_2'=>'96',
                '12535_2'=>'96', '12551_2'=>'96', '12716_2'=>'96', '12740_2'=>'96', '12803_2'=>'96', '12805_2'=>'96', '12835_2'=>'96', '12843_2'=>'96', '13004_2'=>'96', '13054_2'=>'96', '13065_2'=>'96', '13135_2'=>'96', '13139_2'=>'96', '13239_2'=>'96', '13273_2'=>'96', '13462_2'=>'96',
                '13523_2'=>'96', '13594_2'=>'96', '13677_2'=>'96', '13720_2'=>'96', '13996_2'=>'96', '14008_2'=>'96', '14010_2'=>'96', '14012_2'=>'96', '14043_2'=>'96', '14099_2'=>'96', '14137_2'=>'96', '14156_2'=>'96', '14302_2'=>'96', '14316_2'=>'96', '14526_2'=>'96', '14742_2'=>'96',
                '14828_2'=>'96', '14839_2'=>'96', '14909_2'=>'96', '14989_2'=>'96', '15042_2'=>'96', '15165_2'=>'96', '15534_2'=>'96', '15607_2'=>'96', '15888_2'=>'96', '15968_2'=>'96', '15969_2'=>'96', '16004_2'=>'96', '16188_2'=>'96', '16232_2'=>'96', '16287_2'=>'96', '16324_2'=>'96',
                '16334_2'=>'96', '16581_2'=>'96', '16631_2'=>'96', '16642_2'=>'96', '16792_2'=>'96', '17123_2'=>'96', '17336_2'=>'96', '17743_2'=>'96', '18004_2'=>'96', '18185_2'=>'96', '18246_2'=>'96', '18336_2'=>'96', '18534_2'=>'96', '18674_2'=>'96', '18706_2'=>'96', '18724_2'=>'96',
                '18737_2'=>'96', '19011_2'=>'96', '19459_2'=>'96', '19486_2'=>'96', '19516_2'=>'96', '19526_2'=>'96', '19528_2'=>'96', '19558_2'=>'96', '19561_2'=>'96', '20060_2'=>'96', '20115_2'=>'96', '20224_2'=>'96', '20355_2'=>'96', '20499_2'=>'96', '20500_2'=>'96', '20622_2'=>'96',
                '21176_2'=>'96', '21254_2'=>'96', '21817_2'=>'96', '21849_2'=>'96', '22177_2'=>'96', '22518_2'=>'96', '22566_2'=>'96', '22616_2'=>'96', '22692_2'=>'96', '23040_2'=>'96', '23062_2'=>'96', '23221_2'=>'96', '23646_2'=>'96', '24326_2'=>'96', '24434_2'=>'96', '24481_2'=>'96',
                '24531_2'=>'96', '24614_2'=>'96', '25215_2'=>'96', '25400_2'=>'96', '25413_2'=>'96', '25546_2'=>'96', '25844_2'=>'96', '25856_2'=>'96', '26022_2'=>'96', '26227_2'=>'96', '26235_2'=>'96', '26260_2'=>'96', '26343_2'=>'96', '26388_2'=>'96', '26958_2'=>'96', '27422_2'=>'96',
                '27677_2'=>'96', '27756_2'=>'96', '27823_2'=>'96', '27959_2'=>'96', '28392_2'=>'96', '28403_2'=>'96', '28472_2'=>'96', '28673_2'=>'96', '28693_2'=>'96', '28758_2'=>'96', '28802_2'=>'96', '28809_2'=>'96', '28947_2'=>'96', '29004_2'=>'96', '29164_2'=>'96', '29285_2'=>'96',
                '29386_2'=>'96', '29557_2'=>'96', '29775_2'=>'96', '30070_2'=>'96', '30294_2'=>'96', '30347_2'=>'96', '30426_2'=>'96', '31010_2'=>'96', '31410_2'=>'96', '31617_2'=>'96', '31623_2'=>'96', '31792_2'=>'96', '31797_2'=>'96', '32214_2'=>'96', '32242_2'=>'96', '32281_2'=>'96',
                '32455_2'=>'96', '33047_2'=>'96', '33198_2'=>'96', '33581_2'=>'96', '33750_2'=>'96', '33760_2'=>'96', '33816_2'=>'96', '34532_2'=>'96', '34664_2'=>'96', '34779_2'=>'96', '34852_2'=>'96', '34940_2'=>'96', '35282_2'=>'96', '35283_2'=>'96', '35298_2'=>'96', '35329_2'=>'96',
                '35396_2'=>'96', '35512_2'=>'96', '35715_2'=>'96', '35838_2'=>'96', '36541_2'=>'96', '36977_2'=>'96', '37549_2'=>'96', '38097_2'=>'96', '38320_2'=>'96', '38427_2'=>'96', '38437_2'=>'96', '38721_2'=>'96', '38742_2'=>'96', '38750_2'=>'96', '38894_2'=>'96', '39491_2'=>'96',
                '40097_2'=>'96', '40342_2'=>'96', '40920_2'=>'96', '41014_2'=>'96', '41471_2'=>'96', '41542_2'=>'96', '41548_2'=>'96', '41587_2'=>'96', '41858_2'=>'96', '42385_2'=>'96', '42500_2'=>'96', '42665_2'=>'96', '43833_2'=>'96', '43907_2'=>'96', '44176_2'=>'96', '44380_2'=>'96',
                '44385_2'=>'96', '44545_2'=>'96', '44558_2'=>'96', '45316_2'=>'96', '45396_2'=>'96', '45555_2'=>'96', '46181_2'=>'96', '46464_2'=>'96', '46572_2'=>'96', '47335_2'=>'96', '47337_2'=>'96', '48095_2'=>'96', '48099_2'=>'96', '48586_2'=>'96', '48594_2'=>'96', '49739_2'=>'96',
                '49840_2'=>'96', '49931_2'=>'96', '51179_2'=>'96', '52540_2'=>'96', '52741_2'=>'96', '53173_2'=>'96', '53186_2'=>'96', '53270_2'=>'96', '53333_2'=>'96', '53936_2'=>'96', '54377_2'=>'96', '54523_2'=>'96', '55140_2'=>'96', '55236_2'=>'96', '55262_2'=>'96', '55556_2'=>'96',
                '55869_2'=>'96', '56725_2'=>'96', '56846_2'=>'96', '57006_2'=>'96', '57195_2'=>'96', '57399_2'=>'96', '57966_2'=>'96', '58024_2'=>'96', '58914_2'=>'96', '58943_2'=>'96', '59333_2'=>'96', '59645_2'=>'96', '60038_2'=>'96', '60644_2'=>'96', '60687_2'=>'96', '60888_2'=>'96',
                '61329_2'=>'96', '61489_2'=>'96', '62364_2'=>'96', '63138_2'=>'96', '63505_2'=>'96', '63854_2'=>'96', '65711_2'=>'96', '66122_2'=>'96', '66412_2'=>'96', '66498_2'=>'96', '66729_2'=>'96', '66975_2'=>'96', '67909_2'=>'96', '68880_2'=>'96', '70464_2'=>'96', '70931_2'=>'96',
                '71039_2'=>'96', '71298_2'=>'96', '71323_2'=>'96', '71362_2'=>'96', '71962_2'=>'96', '73320_2'=>'96', '73529_2'=>'96', '73620_2'=>'96', '73995_2'=>'96', '74024_2'=>'96', '74295_2'=>'96', '74666_2'=>'96', '75268_2'=>'96', '75879_2'=>'96', '76041_2'=>'96', '76783_2'=>'96',
                '77533_2'=>'96', '79096_2'=>'96', '79265_2'=>'96', '79928_2'=>'96', '80410_2'=>'96', '80951_2'=>'96', '82234_2'=>'96', '83568_2'=>'96', '84707_2'=>'96', '85005_2'=>'96', '85232_2'=>'96', '85283_2'=>'96', '85812_2'=>'96', '87441_2'=>'96', '88362_2'=>'96', '90051_2'=>'96',
                '90296_2'=>'96', '91004_2'=>'96', '91275_2'=>'96', '92158_2'=>'96', '93200_2'=>'96', '93340_2'=>'96', '94487_2'=>'96', '94852_2'=>'96', '95516_2'=>'96', '97116_2'=>'96', '97198_2'=>'96', '99058_2'=>'96', '99751_2'=>'96', '100092_2'=>'96', '100343_2'=>'96', '101068_2'=>'96',
                '102080_2'=>'96', '102258_2'=>'96', '105545_2'=>'96', '105759_2'=>'96', '106608_2'=>'96', '107033_2'=>'96', '107676_2'=>'96', '107912_2'=>'96', '108281_2'=>'96', '111766_2'=>'96', '112334_2'=>'96', '113219_2'=>'96', '113541_2'=>'96', '115526_2'=>'96', '116749_2'=>'96',
                '118155_2'=>'96', '119967_2'=>'96', '120934_2'=>'96', '121031_2'=>'96', '121615_2'=>'96', '122051_2'=>'96', '16183_2'=>'96', '16247_2'=>'96', '16647_2'=>'96', '16692_2'=>'96', '16885_2'=>'96', '17073_2'=>'96', '17598_2'=>'96', '28161_2'=>'96', '40474_2'=>'96', '48316_2'=>'96',
                '48360_2'=>'96', '48398_2'=>'96', '48418_2'=>'96', '48512_2'=>'96', '48539_2'=>'96', '48547_2'=>'96', '48641_2'=>'96', '48680_2'=>'96', '48823_2'=>'96', '48930_2'=>'96', '48960_2'=>'96', '48997_2'=>'96', '49032_2'=>'96', '49162_2'=>'96', '49253_2'=>'96', '49432_2'=>'96',
                '49456_2'=>'96', '49704_2'=>'96', '49953_2'=>'96', '50184_2'=>'96', '50375_2'=>'96', '50599_2'=>'96', '50602_2'=>'96', '50611_2'=>'96', '50800_2'=>'96', '50834_2'=>'96', '50898_2'=>'96', '50911_2'=>'96', '50967_2'=>'96', '50968_2'=>'96', '51121_2'=>'96', '51383_2'=>'96',
                '51517_2'=>'96', '51799_2'=>'96', '52000_2'=>'96', '52289_2'=>'96', '52311_2'=>'96', '53051_2'=>'96', '53847_2'=>'96', '54042_2'=>'96', '54067_2'=>'96', '54621_2'=>'96', '55147_2'=>'96', '55269_2'=>'96', '56200_2'=>'96', '57617_2'=>'96', '57743_2'=>'96', '58397_2'=>'96',
                '58595_2'=>'96', '60642_2'=>'96', '60851_2'=>'96', '61846_2'=>'96', '61890_2'=>'96', '62622_2'=>'96', '62684_2'=>'96', '62800_2'=>'96', '63618_2'=>'96', '63622_2'=>'96', '64379_2'=>'96', '65167_2'=>'96', '65562_2'=>'96', '65717_2'=>'96', '66460_2'=>'96', '66625_2'=>'96',
                '66799_2'=>'96', '68231_2'=>'96', '69496_2'=>'96', '70386_2'=>'96', '70976_2'=>'96', '75374_2'=>'96', '76203_2'=>'96', '76377_2'=>'96', '76818_2'=>'96', '77839_2'=>'96', '78776_2'=>'96', '78863_2'=>'96', '79054_2'=>'96', '80332_2'=>'96', '82415_2'=>'96', '82440_2'=>'96',
                '82463_2'=>'96', '82507_2'=>'96', '82584_2'=>'96', '82615_2'=>'96', '82680_2'=>'96', '82738_2'=>'96', '82775_2'=>'96', '82784_2'=>'96', '82808_2'=>'96', '82820_2'=>'96', '82948_2'=>'96', '82962_2'=>'96', '82964_2'=>'96', '83237_2'=>'96', '83286_2'=>'96', '83331_2'=>'96',
                '83342_2'=>'96', '83635_2'=>'96', '84019_2'=>'96', '84036_2'=>'96', '84437_2'=>'96', '84561_2'=>'96', '84614_2'=>'96', '84638_2'=>'96', '84721_2'=>'96', '84756_2'=>'96', '84846_2'=>'96', '84895_2'=>'96', '84958_2'=>'96', '84960_2'=>'96', '85004_2'=>'96', '85109_2'=>'96',
                '85118_2'=>'96', '85147_2'=>'96', '85178_2'=>'96', '85195_2'=>'96', '85247_2'=>'96', '85362_2'=>'96', '85366_2'=>'96', '85450_2'=>'96', '85597_2'=>'96', '86014_2'=>'96', '86121_2'=>'96', '86129_2'=>'96', '86155_2'=>'96', '86238_2'=>'96', '86281_2'=>'96', '86449_2'=>'96',
                '86498_2'=>'96', '86537_2'=>'96', '86915_2'=>'96', '86962_2'=>'96', '87178_2'=>'96', '87220_2'=>'96', '87280_2'=>'96', '87311_2'=>'96', '87733_2'=>'96', '87766_2'=>'96', '87782_2'=>'96', '87807_2'=>'96', '87811_2'=>'96', '87879_2'=>'96', '87893_2'=>'96', '87920_2'=>'96',
                '87927_2'=>'96', '87931_2'=>'96', '87941_2'=>'96', '87998_2'=>'96', '88264_2'=>'96', '88313_2'=>'96', '88335_2'=>'96', '88374_2'=>'96', '88378_2'=>'96', '88397_2'=>'96', '88423_2'=>'96', '88524_2'=>'96', '88639_2'=>'96', '88684_2'=>'96', '88771_2'=>'96', '88811_2'=>'96',
                '88886_2'=>'96', '89020_2'=>'96', '89072_2'=>'96', '89157_2'=>'96', '89183_2'=>'96', '89804_2'=>'96', '89817_2'=>'96', '90021_2'=>'96', '90055_2'=>'96', '90057_2'=>'96', '90094_2'=>'96', '90234_2'=>'96', '90280_2'=>'96', '90493_2'=>'96', '90570_2'=>'96', '90600_2'=>'96',
                '90872_2'=>'96', '91380_2'=>'96', '91504_2'=>'96', '91661_2'=>'96', '91855_2'=>'96', '91944_2'=>'96', '91954_2'=>'96', '92049_2'=>'96', '92121_2'=>'96', '92698_2'=>'96', '92917_2'=>'96', '93195_2'=>'96', '93437_2'=>'96', '94058_2'=>'96', '94334_2'=>'96', '94397_2'=>'96',
                '96394_2'=>'96', '97635_2'=>'96', '97790_2'=>'96', '98425_2'=>'96', '102357_2'=>'96', '102361_2'=>'96', '102862_2'=>'96', '103026_2'=>'96', '103073_2'=>'96', '103337_2'=>'96', '103685_2'=>'96', '103920_2'=>'96', '104199_2'=>'96', '104201_2'=>'96', '104223_2'=>'96', '105240_2'=>'96',
                '105494_2'=>'96', '105649_2'=>'96', '106139_2'=>'96', '106214_2'=>'96', '107243_2'=>'96', '107858_2'=>'96', '108228_2'=>'96', '109442_2'=>'96', '111713_2'=>'96', '112070_2'=>'96', '112668_2'=>'96', '113116_2'=>'96', '113117_2'=>'96', '115837_2'=>'96', '116017_2'=>'96',
                '117971_2'=>'96', '118962_2'=>'96', '119997_2'=>'96', '120138_2'=>'96', '120994_2'=>'96', '121667_2'=>'96', '121738_2'=>'96', '121783_2'=>'96', '122039_2'=>'96', '122303_2'=>'96', '122788_2'=>'96', '123173_2'=>'96', '124139_2'=>'96', '125006_2'=>'96', '185_2'=>'95', '187_2'=>'95',
                '188_2'=>'95', '194_2'=>'95', '195_2'=>'95', '198_2'=>'95', '202_2'=>'95', '204_2'=>'95', '206_2'=>'95', '210_2'=>'95', '217_2'=>'95', '219_2'=>'95', '220_2'=>'95', '223_2'=>'95', '225_2'=>'95', '228_2'=>'95', '229_2'=>'95', '230_2'=>'95', '231_2'=>'95', '235_2'=>'95', '238_2'=>'95',
                '239_2'=>'95', '244_2'=>'95', '248_2'=>'95', '250_2'=>'95', '251_2'=>'95', '252_2'=>'95', '254_2'=>'95', '256_2'=>'95', '258_2'=>'95', '260_2'=>'95', '261_2'=>'95', '263_2'=>'95', '265_2'=>'95', '268_2'=>'95', '274_2'=>'95', '275_2'=>'95', '276_2'=>'95', '279_2'=>'95', '280_2'=>'95',
                '283_2'=>'95', '287_2'=>'95', '291_2'=>'95', '292_2'=>'95', '294_2'=>'95', '296_2'=>'95', '305_2'=>'95', '307_2'=>'95', '308_2'=>'95', '309_2'=>'95', '315_2'=>'95', '316_2'=>'95', '322_2'=>'95', '323_2'=>'95', '326_2'=>'95', '327_2'=>'95', '336_2'=>'95', '342_2'=>'95', '358_2'=>'95',
                '361_2'=>'95', '369_2'=>'95', '370_2'=>'95', '373_2'=>'95', '383_2'=>'95', '385_2'=>'95', '388_2'=>'95', '398_2'=>'95', '399_2'=>'95', '402_2'=>'95', '403_2'=>'95', '406_2'=>'95', '417_2'=>'95', '427_2'=>'95', '436_2'=>'95', '438_2'=>'95', '442_2'=>'95', '449_2'=>'95', '450_2'=>'95',
                '456_2'=>'95', '459_2'=>'95', '473_2'=>'95', '480_2'=>'95', '484_2'=>'95', '488_2'=>'95', '490_2'=>'95', '493_2'=>'95', '498_2'=>'95', '499_2'=>'95', '512_2'=>'95', '513_2'=>'95', '514_2'=>'95', '516_2'=>'95', '520_2'=>'95', '521_2'=>'95', '525_2'=>'95', '526_2'=>'95', '531_2'=>'95',
                '540_2'=>'95', '541_2'=>'95', '550_2'=>'95', '556_2'=>'95', '560_2'=>'95', '562_2'=>'95', '565_2'=>'95', '570_2'=>'95', '572_2'=>'95', '574_2'=>'95', '581_2'=>'95', '582_2'=>'95', '583_2'=>'95', '584_2'=>'95', '587_2'=>'95', '588_2'=>'95', '590_2'=>'95', '597_2'=>'95', '598_2'=>'95',
                '599_2'=>'95', '601_2'=>'95', '602_2'=>'95', '606_2'=>'95', '607_2'=>'95', '608_2'=>'95', '614_2'=>'95', '616_2'=>'95', '620_2'=>'95', '622_2'=>'95', '628_2'=>'95', '629_2'=>'95', '639_2'=>'95', '640_2'=>'95', '643_2'=>'95', '644_2'=>'95', '647_2'=>'95', '658_2'=>'95', '660_2'=>'95',
                '669_2'=>'95', '673_2'=>'95', '674_2'=>'95', '694_2'=>'95', '698_2'=>'95', '710_2'=>'95', '718_2'=>'95', '724_2'=>'95', '733_2'=>'95', '736_2'=>'95', '737_2'=>'95', '746_2'=>'95', '766_2'=>'95', '775_2'=>'95', '778_2'=>'95', '783_2'=>'95', '785_2'=>'95', '788_2'=>'95', '789_2'=>'95',
                '790_2'=>'95', '793_2'=>'95', '794_2'=>'95', '796_2'=>'95', '801_2'=>'95', '802_2'=>'95', '803_2'=>'95', '805_2'=>'95', '808_2'=>'95', '809_2'=>'95', '811_2'=>'95', '812_2'=>'95', '820_2'=>'95', '824_2'=>'95', '825_2'=>'95', '826_2'=>'95', '827_2'=>'95', '829_2'=>'95', '830_2'=>'95',
                '835_2'=>'95', '836_2'=>'95', '842_2'=>'95', '843_2'=>'95', '844_2'=>'95', '846_2'=>'95', '847_2'=>'95', '848_2'=>'95', '849_2'=>'95', '850_2'=>'95', '853_2'=>'95', '864_2'=>'95', '875_2'=>'95', '894_2'=>'95', '895_2'=>'95', '900_2'=>'95', '902_2'=>'95', '941_2'=>'95', '962_2'=>'95',
                '1000_2'=>'95', '1024_2'=>'95', '1038_2'=>'95', '1054_2'=>'95', '1055_2'=>'95', '1058_2'=>'95', '1069_2'=>'95', '1084_2'=>'95', '1106_2'=>'95', '1107_2'=>'95', '1145_2'=>'95', '2267_2'=>'95', '2372_2'=>'95', '2407_2'=>'95', '2431_2'=>'95', '2442_2'=>'95', '2444_2'=>'95',
                '2689_2'=>'95', '2751_2'=>'95', '2922_2'=>'95', '2997_2'=>'95', '3022_2'=>'95', '3232_2'=>'95', '3397_2'=>'95', '3547_2'=>'95', '3551_2'=>'95', '3553_2'=>'95', '3565_2'=>'95', '3589_2'=>'95', '3647_2'=>'95', '3695_2'=>'95', '3710_2'=>'95', '3716_2'=>'95', '3807_2'=>'95',
                '3815_2'=>'95', '3862_2'=>'95', '3998_2'=>'95', '4005_2'=>'95', '4075_2'=>'95', '4086_2'=>'95', '4106_2'=>'95', '4117_2'=>'95', '4139_2'=>'95', '4148_2'=>'95', '4151_2'=>'95', '4169_2'=>'95', '4175_2'=>'95', '4186_2'=>'95', '4195_2'=>'95', '4198_2'=>'95', '4205_2'=>'95',
                '4290_2'=>'95', '4326_2'=>'95', '4331_2'=>'95', '4342_2'=>'95', '4361_2'=>'95', '4379_2'=>'95', '4417_2'=>'95', '4517_2'=>'95', '4539_2'=>'95', '4543_2'=>'95', '4559_2'=>'95', '4566_2'=>'95', '4618_2'=>'95', '4621_2'=>'95', '4690_2'=>'95', '4699_2'=>'95', '4710_2'=>'95',
                '4757_2'=>'95', '4782_2'=>'95', '4822_2'=>'95', '4840_2'=>'95', '4849_2'=>'95', '4869_2'=>'95', '4883_2'=>'95', '4892_2'=>'95', '4901_2'=>'95', '4918_2'=>'95', '5013_2'=>'95', '5038_2'=>'95', '5041_2'=>'95', '5052_2'=>'95', '5236_2'=>'95', '5388_2'=>'95', '5398_2'=>'95',
                '5405_2'=>'95', '5472_2'=>'95', '5544_2'=>'95', '5682_2'=>'95', '5780_2'=>'95', '5885_2'=>'95', '5946_2'=>'95', '5949_2'=>'95', '5983_2'=>'95', '6068_2'=>'95', '6119_2'=>'95', '6226_2'=>'95', '6384_2'=>'95', '6416_2'=>'95', '6419_2'=>'95', '6442_2'=>'95', '6520_2'=>'95',
                '6532_2'=>'95', '6586_2'=>'95', '6598_2'=>'95', '6651_2'=>'95', '6672_2'=>'95', '6749_2'=>'95', '6821_2'=>'95', '6843_2'=>'95', '7064_2'=>'95', '7162_2'=>'95', '7163_2'=>'95', '7692_2'=>'95', '7847_2'=>'95', '7863_2'=>'95', '7864_2'=>'95', '7884_2'=>'95', '7897_2'=>'95',
                '7991_2'=>'95', '7995_2'=>'95', '8095_2'=>'95', '8105_2'=>'95', '8141_2'=>'95', '8154_2'=>'95', '8246_2'=>'95', '8337_2'=>'95', '8351_2'=>'95', '8382_2'=>'95', '8417_2'=>'95', '8452_2'=>'95', '8454_2'=>'95', '9072_2'=>'95', '9135_2'=>'95', '9270_2'=>'95', '9286_2'=>'95',
                '9296_2'=>'95', '9324_2'=>'95', '9370_2'=>'95', '9389_2'=>'95', '9624_2'=>'95', '9777_2'=>'95', '9920_2'=>'95', '9983_2'=>'95', '10462_2'=>'95', '10618_2'=>'95', '10621_2'=>'95', '10754_2'=>'95', '10789_2'=>'95', '10818_2'=>'95', '10830_2'=>'95', '10850_2'=>'95', '10975_2'=>'95',
                '11001_2'=>'95', '11055_2'=>'95', '11086_2'=>'95', '11684_2'=>'95', '11731_2'=>'95', '11852_2'=>'95', '11873_2'=>'95', '11898_2'=>'95', '11936_2'=>'95', '11940_2'=>'95', '11991_2'=>'95', '11998_2'=>'95', '12011_2'=>'95', '12104_2'=>'95', '12105_2'=>'95', '12511_2'=>'95',
                '12627_2'=>'95', '12709_2'=>'95', '12856_2'=>'95', '12999_2'=>'95', '13412_2'=>'95', '13496_2'=>'95', '13518_2'=>'95', '13564_2'=>'95', '13574_2'=>'95', '13648_2'=>'95', '13659_2'=>'95', '13678_2'=>'95', '13682_2'=>'95', '13691_2'=>'95', '13700_2'=>'95', '13715_2'=>'95',
                '13728_2'=>'95', '13729_2'=>'95', '13732_2'=>'95', '13794_2'=>'95', '13930_2'=>'95', '14013_2'=>'95', '14640_2'=>'95', '14805_2'=>'95', '14812_2'=>'95', '15027_2'=>'95', '15174_2'=>'95', '15293_2'=>'95', '15344_2'=>'95', '15393_2'=>'95', '15429_2'=>'95', '15437_2'=>'95',
                '15582_2'=>'95', '15696_2'=>'95', '15794_2'=>'95', '16355_2'=>'95', '16409_2'=>'95', '16495_2'=>'95', '16785_2'=>'95', '16812_2'=>'95', '16931_2'=>'95', '16986_2'=>'95', '16987_2'=>'95', '17045_2'=>'95', '17056_2'=>'95', '17135_2'=>'95', '17203_2'=>'95', '17244_2'=>'95',
                '17333_2'=>'95', '17408_2'=>'95', '17584_2'=>'95', '17853_2'=>'95', '17956_2'=>'95', '18019_2'=>'95', '18192_2'=>'95', '18358_2'=>'95', '18360_2'=>'95', '18526_2'=>'95', '18537_2'=>'95', '18565_2'=>'95', '18604_2'=>'95', '18663_2'=>'95', '18778_2'=>'95', '18785_2'=>'95',
                '18903_2'=>'95', '19188_2'=>'95', '19211_2'=>'95', '19531_2'=>'95', '19580_2'=>'95', '19586_2'=>'95', '19822_2'=>'95', '20052_2'=>'95', '20232_2'=>'95', '20444_2'=>'95', '20717_2'=>'95', '21011_2'=>'95', '21145_2'=>'95', '21191_2'=>'95', '21625_2'=>'95', '21630_2'=>'95',
                '21721_2'=>'95', '21757_2'=>'95', '22311_2'=>'95', '22346_2'=>'95', '22354_2'=>'95', '23142_2'=>'95', '23151_2'=>'95', '23538_2'=>'95', '23842_2'=>'95', '24000_2'=>'95', '24132_2'=>'95', '24182_2'=>'95', '24200_2'=>'95', '24265_2'=>'95', '24267_2'=>'95', '24281_2'=>'95',
                '24296_2'=>'95', '24297_2'=>'95', '24381_2'=>'95', '24480_2'=>'95', '24701_2'=>'95', '24745_2'=>'95', '24770_2'=>'95', '24934_2'=>'95', '24935_2'=>'95', '24942_2'=>'95', '25126_2'=>'95', '25271_2'=>'95', '25280_2'=>'95', '25382_2'=>'95', '25383_2'=>'95', '25438_2'=>'95',
                '25457_2'=>'95', '25649_2'=>'95', '25650_2'=>'95', '26081_2'=>'95', '26082_2'=>'95', '26084_2'=>'95', '26095_2'=>'95', '26127_2'=>'95', '26171_2'=>'95', '26182_2'=>'95', '26710_2'=>'95', '26711_2'=>'95', '26724_2'=>'95', '26757_2'=>'95', '26809_2'=>'95', '26872_2'=>'95',
                '26994_2'=>'95', '26995_2'=>'95', '27058_2'=>'95', '27059_2'=>'95', '27060_2'=>'95', '27121_2'=>'95', '27247_2'=>'95', '27352_2'=>'95', '27587_2'=>'95', '27592_2'=>'95', '27798_2'=>'95', '28062_2'=>'95', '28308_2'=>'95', '28421_2'=>'95', '28469_2'=>'95', '28520_2'=>'95',
                '28585_2'=>'95', '28587_2'=>'95', '28755_2'=>'95', '28853_2'=>'95', '28861_2'=>'95', '28864_2'=>'95', '28865_2'=>'95', '28938_2'=>'95', '28939_2'=>'95', '28940_2'=>'95', '28949_2'=>'95', '28993_2'=>'95', '29033_2'=>'95', '29195_2'=>'95', '29380_2'=>'95', '29444_2'=>'95',
                '29446_2'=>'95', '29461_2'=>'95', '29464_2'=>'95', '29644_2'=>'95', '29814_2'=>'95', '29816_2'=>'95', '30496_2'=>'95', '31218_2'=>'95', '31456_2'=>'95', '31657_2'=>'95', '31910_2'=>'95', '32348_2'=>'95', '32349_2'=>'95', '32382_2'=>'95', '32720_2'=>'95', '33026_2'=>'95',
                '33071_2'=>'95', '33072_2'=>'95', '33393_2'=>'95', '33859_2'=>'95', '34060_2'=>'95', '34068_2'=>'95', '34077_2'=>'95', '34083_2'=>'95', '34084_2'=>'95', '34142_2'=>'95', '34161_2'=>'95', '34483_2'=>'95', '34816_2'=>'95', '36402_2'=>'95', '36424_2'=>'95', '36487_2'=>'95',
                '37010_2'=>'95', '37066_2'=>'95', '37067_2'=>'95', '37329_2'=>'95', '37486_2'=>'95', '37501_2'=>'95', '37507_2'=>'95', '37623_2'=>'95', '37624_2'=>'95', '37994_2'=>'95', '38049_2'=>'95', '38160_2'=>'95', '38161_2'=>'95', '38286_2'=>'95', '38431_2'=>'95', '38601_2'=>'95',
                '38811_2'=>'95', '42095_2'=>'95', '42373_2'=>'95', '42504_2'=>'95', '42769_2'=>'95', '42858_2'=>'95', '43781_2'=>'95', '44642_2'=>'95', '44892_2'=>'95', '44946_2'=>'95', '45962_2'=>'95', '48643_2'=>'95', '49984_2'=>'95', '50316_2'=>'95', '50328_2'=>'95', '50670_2'=>'95',
                '50679_2'=>'95', '50680_2'=>'95', '50682_2'=>'95', '50683_2'=>'95', '50684_2'=>'95', '25_2'=>'95', '26_2'=>'95', '28_2'=>'95', '29_2'=>'95', '36_2'=>'95', '37_2'=>'95', '38_2'=>'95', '39_2'=>'95', '45_2'=>'95', '46_2'=>'95', '48_2'=>'95', '49_2'=>'95', '52_2'=>'95', '53_2'=>'95',
                '54_2'=>'95', '55_2'=>'95', '59_2'=>'95', '60_2'=>'95', '61_2'=>'95', '64_2'=>'95', '65_2'=>'95', '66_2'=>'95', '67_2'=>'95', '81_2'=>'95', '82_2'=>'95', '109_2'=>'95', '113_2'=>'95', '116_2'=>'95', '120_2'=>'95', '125_2'=>'95', '129_2'=>'95', '137_2'=>'95', '138_2'=>'95',
                '140_2'=>'95', '142_2'=>'95', '144_2'=>'95', '145_2'=>'95', '152_2'=>'95', '154_2'=>'95', '157_2'=>'95', '158_2'=>'95', '159_2'=>'95', '167_2'=>'95', '183_2'=>'95', '211_2'=>'95', '224_2'=>'95', '234_2'=>'95', '241_2'=>'95', '243_2'=>'95', '245_2'=>'95', '318_2'=>'95', '319_2'=>'95',
                '328_2'=>'95', '343_2'=>'95', '350_2'=>'95', '357_2'=>'95', '384_2'=>'95', '421_2'=>'95', '428_2'=>'95', '429_2'=>'95', '430_2'=>'95', '445_2'=>'95', '452_2'=>'95', '471_2'=>'95', '496_2'=>'95', '497_2'=>'95', '502_2'=>'95', '509_2'=>'95', '518_2'=>'95', '544_2'=>'95', '545_2'=>'95',
                '546_2'=>'95', '555_2'=>'95', '573_2'=>'95', '577_2'=>'95', '579_2'=>'95', '589_2'=>'95', '595_2'=>'95', '596_2'=>'95', '600_2'=>'95', '612_2'=>'95', '617_2'=>'95', '630_2'=>'95', '631_2'=>'95', '636_2'=>'95', '650_2'=>'95', '653_2'=>'95', '654_2'=>'95', '661_2'=>'95', '670_2'=>'95',
                '682_2'=>'95', '691_2'=>'95', '695_2'=>'95', '696_2'=>'95', '697_2'=>'95', '703_2'=>'95', '706_2'=>'95', '709_2'=>'95', '712_2'=>'95', '722_2'=>'95', '725_2'=>'95', '729_2'=>'95', '731_2'=>'95', '734_2'=>'95', '735_2'=>'95', '738_2'=>'95', '739_2'=>'95', '743_2'=>'95', '744_2'=>'95',
                '748_2'=>'95', '761_2'=>'95', '769_2'=>'95', '770_2'=>'95', '779_2'=>'95', '782_2'=>'95', '817_2'=>'95', '893_2'=>'95', '961_2'=>'95', '1056_2'=>'95', '1074_2'=>'95', '1085_2'=>'95', '1100_2'=>'95', '1147_2'=>'95', '1187_2'=>'95', '1630_2'=>'95', '2204_2'=>'95', '2300_2'=>'95',
                '2360_2'=>'95', '2365_2'=>'95', '2472_2'=>'95', '2575_2'=>'95', '2601_2'=>'95', '2641_2'=>'95', '2661_2'=>'95', '2813_2'=>'95', '2839_2'=>'95', '2851_2'=>'95', '2870_2'=>'95', '2931_2'=>'95', '2944_2'=>'95', '2978_2'=>'95', '2991_2'=>'95', '3059_2'=>'95', '3120_2'=>'95',
                '3355_2'=>'95', '3476_2'=>'95', '3563_2'=>'95', '3564_2'=>'95', '3650_2'=>'95', '3884_2'=>'95', '3975_2'=>'95', '4053_2'=>'95', '4065_2'=>'95', '4066_2'=>'95', '4134_2'=>'95', '4135_2'=>'95', '4140_2'=>'95', '4259_2'=>'95', '4551_2'=>'95', '4578_2'=>'95', '4709_2'=>'95',
                '4900_2'=>'95', '4904_2'=>'95', '4912_2'=>'95', '4924_2'=>'95', '4931_2'=>'95', '4933_2'=>'95', '5078_2'=>'95', '5097_2'=>'95', '5099_2'=>'95', '5139_2'=>'95', '5297_2'=>'95', '5720_2'=>'95', '5986_2'=>'95', '5999_2'=>'95', '6474_2'=>'95', '6624_2'=>'95', '6631_2'=>'95',
                '6660_2'=>'95', '6844_2'=>'95', '7529_2'=>'95', '7643_2'=>'95', '8881_2'=>'95', '9133_2'=>'95', '10271_2'=>'95', '10446_2'=>'95', '10787_2'=>'95', '11545_2'=>'95', '11738_2'=>'95', '11783_2'=>'95', '12099_2'=>'95', '12717_2'=>'95', '12922_2'=>'95', '13738_2'=>'95', '14423_2'=>'95',
                '15388_2'=>'95', '15460_2'=>'95', '15536_2'=>'95', '21687_2'=>'95', '21874_2'=>'95', '23190_2'=>'95', '23238_2'=>'95', '23469_2'=>'95', '23545_2'=>'95', '23930_2'=>'95', '24856_2'=>'95', '24879_2'=>'95', '25672_2'=>'95', '25910_2'=>'95', '25962_2'=>'95', '26113_2'=>'95',
                '26167_2'=>'95', '26250_2'=>'95', '26494_2'=>'95', '26899_2'=>'95', '28192_2'=>'95', '28895_2'=>'95', '29022_2'=>'95', '29023_2'=>'95', '29821_2'=>'95', '29990_2'=>'95', '31057_2'=>'95', '32055_2'=>'95', '32056_2'=>'95', '32093_2'=>'95', '32383_2'=>'95', '33018_2'=>'95',
                '33824_2'=>'95', '33832_2'=>'95', '34497_2'=>'95', '34822_2'=>'95', '34910_2'=>'95', '35395_2'=>'95', '35516_2'=>'95', '36092_2'=>'95', '36293_2'=>'95', '36313_2'=>'95', '36323_2'=>'95', '36330_2'=>'95', '36340_2'=>'95', '37118_2'=>'95', '37685_2'=>'95', '38638_2'=>'95',
                '38959_2'=>'95', '40982_2'=>'95', '40984_2'=>'95', '41367_2'=>'95', '42024_2'=>'95', '42226_2'=>'95', '43831_2'=>'95', '45331_2'=>'95', '45663_2'=>'95', '46179_2'=>'95', '46408_2'=>'95', '46490_2'=>'95', '47812_2'=>'95', '48419_2'=>'95', '48695_2'=>'95', '50189_2'=>'95',
                '50515_2'=>'95', '51683_2'=>'95', '52668_2'=>'95', '52701_2'=>'95', '53883_2'=>'95', '53960_2'=>'95', '54421_2'=>'95', '55627_2'=>'95', '58538_2'=>'95', '59507_2'=>'95', '59559_2'=>'95', '59844_2'=>'95', '61114_2'=>'95', '63279_2'=>'95', '63506_2'=>'95', '65027_2'=>'95',
                '66042_2'=>'95', '72817_2'=>'95', '85138_2'=>'95', '87770_2'=>'95', '97099_2'=>'95', '100268_2'=>'95', '105111_2'=>'95', '108684_2'=>'95', '109878_2'=>'95', '110241_2'=>'95', '113912_2'=>'95', '114894_2'=>'95', '115751_2'=>'95', '118081_2'=>'95', '118301_2'=>'95', '122125_2'=>'95',
                '123365_2'=>'95', '124326_2'=>'95', '36898_2'=>'95', '36903_2'=>'95', '37093_2'=>'95', '37193_2'=>'95', '37215_2'=>'95', '37253_2'=>'95', '37335_2'=>'95', '37337_2'=>'95', '37353_2'=>'95', '37408_2'=>'95', '37568_2'=>'95', '37579_2'=>'95', '37861_2'=>'95', '37941_2'=>'95',
                '37946_2'=>'95', '37951_2'=>'95', '38095_2'=>'95', '38113_2'=>'95', '38171_2'=>'95', '38175_2'=>'95', '38533_2'=>'95', '38621_2'=>'95', '38624_2'=>'95', '39152_2'=>'95', '39230_2'=>'95', '39595_2'=>'95', '40140_2'=>'95', '40204_2'=>'95', '40303_2'=>'95', '40386_2'=>'95',
                '40424_2'=>'95', '40689_2'=>'95', '41718_2'=>'95', '41808_2'=>'95', '42035_2'=>'95', '42214_2'=>'95', '43017_2'=>'95', '43582_2'=>'95', '44210_2'=>'95', '47144_2'=>'95', '49297_2'=>'95', '52602_2'=>'95', '56675_2'=>'95', '58304_2'=>'95', '58883_2'=>'95', '58884_2'=>'95',
                '59098_2'=>'95', '59152_2'=>'95', '61220_2'=>'95', '61718_2'=>'95', '62459_2'=>'95', '62660_2'=>'95', '62709_2'=>'95', '63671_2'=>'95', '63772_2'=>'95', '65062_2'=>'95', '65063_2'=>'95', '65111_2'=>'95', '67131_2'=>'95', '67142_2'=>'95', '67302_2'=>'95', '67371_2'=>'95',
                '69495_2'=>'95', '78727_2'=>'95', '78731_2'=>'95', '80685_2'=>'95', '80767_2'=>'95', '86130_2'=>'95', '90052_2'=>'95', '91462_2'=>'95', '91463_2'=>'95', '94968_2'=>'95', '108248_2'=>'95', '110092_2'=>'95', '110320_2'=>'95', '114251_2'=>'95', '117492_2'=>'95', '117842_2'=>'95',
                '118214_2'=>'95', '122323_2'=>'95', '122535_2'=>'95', '123195_2'=>'95', '124671_2'=>'95', '125553_2'=>'95', '677_2'=>'95', '1682_2'=>'95', '34153_2'=>'95', '94504_2'=>'95', '94824_2'=>'95', '94883_2'=>'95', '94917_2'=>'95', '96790_2'=>'95', '96891_2'=>'95', '106615_2'=>'95',
                '107273_2'=>'95', '107466_2'=>'95', '109496_2'=>'95', '112714_2'=>'95', '117668_2'=>'95', '123005_2'=>'95', '123016_2'=>'95', '123901_2'=>'95', '467_2'=>'99', '478_2'=>'99', '529_2'=>'99', '535_2'=>'99', '4905_2'=>'99', '7335_2'=>'99', '8272_2'=>'99', '8285_2'=>'99', '8315_2'=>'99',
                '8320_2'=>'99', '8324_2'=>'99', '8428_2'=>'99', '8461_2'=>'99', '8552_2'=>'99', '8824_2'=>'99', '8854_2'=>'99', '9109_2'=>'99', '9115_2'=>'99', '9117_2'=>'99', '9121_2'=>'99', '9171_2'=>'99', '9420_2'=>'99', '10364_2'=>'99', '10442_2'=>'99', '10505_2'=>'99', '10557_2'=>'99',
                '10815_2'=>'99', '10821_2'=>'99', '11393_2'=>'99', '11602_2'=>'99', '11845_2'=>'99', '11872_2'=>'99', '12356_2'=>'99', '12412_2'=>'99', '12965_2'=>'99', '13116_2'=>'99', '13385_2'=>'99', '14069_2'=>'99', '14116_2'=>'99', '15662_2'=>'99', '17260_2'=>'99', '17309_2'=>'99',
                '17361_2'=>'99', '17481_2'=>'99', '19647_2'=>'99', '19727_2'=>'99', '20165_2'=>'99', '20680_2'=>'99', '21749_2'=>'99', '22035_2'=>'99', '22263_2'=>'99', '22268_2'=>'99', '22650_2'=>'99', '22831_2'=>'99', '23179_2'=>'99', '23439_2'=>'99', '23874_2'=>'99', '25691_2'=>'99',
                '25694_2'=>'99', '25785_2'=>'99', '29116_2'=>'99', '29307_2'=>'99', '32099_2'=>'99', '32821_2'=>'99', '32829_2'=>'99', '32836_2'=>'99', '32842_2'=>'99', '33247_2'=>'99', '33810_2'=>'99', '35427_2'=>'99', '36598_2'=>'99', '37910_2'=>'99', '37938_2'=>'99', '41726_2'=>'99',
                '42481_2'=>'99', '44507_2'=>'99', '47040_2'=>'99', '48184_2'=>'99', '48945_2'=>'99', '53435_2'=>'99', '53908_2'=>'99', '55067_2'=>'99', '55224_2'=>'99', '56693_2'=>'99', '57604_2'=>'99', '60347_2'=>'99', '60959_2'=>'99', '61723_2'=>'99', '62791_2'=>'99', '68860_2'=>'99',
                '69308_2'=>'99', '70125_2'=>'99', '70879_2'=>'99', '75657_2'=>'99', '75658_2'=>'99', '81390_2'=>'99', '81550_2'=>'99', '86768_2'=>'99', '89449_2'=>'99', '93546_2'=>'99', '104088_2'=>'99', '111577_2'=>'99', '112777_2'=>'99', '112945_2'=>'99', '114790_2'=>'99', '121013_2'=>'99',
                '121757_2'=>'99', '122192_2'=>'99', '122748_2'=>'99', '125172_2'=>'99', '8540_2'=>'111', '9529_2'=>'111', '15184_2'=>'111', '15194_2'=>'111', '15198_2'=>'111', '15450_2'=>'111', '15695_2'=>'111', '15705_2'=>'111', '8540_2'=>'111', '9529_2'=>'111', '15184_2'=>'111', '15194_2'=>'111',
                '15198_2'=>'111', '15441_2'=>'111', '15450_2'=>'111', '15695_2'=>'111', '15705_2'=>'111', '15886_2'=>'111', '16135_2'=>'111', '16521_2'=>'111', '16623_2'=>'111', '16689_2'=>'111', '22249_2'=>'111', '23248_2'=>'111', '24691_2'=>'111', '27758_2'=>'111', '29820_2'=>'111',
                '29911_2'=>'111', '32355_2'=>'111', '33303_2'=>'111', '53289_2'=>'111', '63730_2'=>'111', '78105_2'=>'111', '79842_2'=>'111', '107190_2'=>'111', '107221_2'=>'111', '4735_2'=>'111', '5181_2'=>'111', '33897_2'=>'111', '34101_2'=>'111', '42266_2'=>'111', '50731_2'=>'111',
                '51939_2'=>'111', '85254_2'=>'111', '87340_2'=>'111', '92814_2'=>'111', '94354_2'=>'111', '95676_2'=>'111', '96376_2'=>'111', '96611_2'=>'111', '96648_2'=>'111', '96711_2'=>'111', '96806_2'=>'111', '96949_2'=>'111', '97067_2'=>'111', '97088_2'=>'111', '97246_2'=>'111',
                '97265_2'=>'111', '98379_2'=>'111', '98387_2'=>'111', '99155_2'=>'111', '99227_2'=>'111', '99396_2'=>'111', '99442_2'=>'111', '99498_2'=>'111', '99710_2'=>'111', '99763_2'=>'111', '99787_2'=>'111', '99886_2'=>'111', '99887_2'=>'111', '100006_2'=>'111', '100102_2'=>'111',
                '100362_2'=>'111', '100444_2'=>'111', '100563_2'=>'111', '100615_2'=>'111', '103139_2'=>'111', '103426_2'=>'111', '103570_2'=>'111', '104081_2'=>'111', '104423_2'=>'111', '105524_2'=>'111', '105530_2'=>'111', '105551_2'=>'111', '105563_2'=>'111', '106018_2'=>'111', '106095_2'=>'111',
                '106545_2'=>'111', '106849_2'=>'111', '106962_2'=>'111', '107419_2'=>'111', '107479_2'=>'111', '108342_2'=>'111', '108712_2'=>'111', '111068_2'=>'111', '112065_2'=>'111', '113144_2'=>'111', '119335_2'=>'111', '119336_2'=>'111', '122362_2'=>'111', '123598_2'=>'111', '126482_2'=>'111',
                '4452_2'=>'111', '4455_2'=>'111', '4536_2'=>'111', '4712_2'=>'111', '4859_2'=>'111', '4860_2'=>'111', '5379_2'=>'111', '5624_2'=>'111', '5761_2'=>'111', '8386_2'=>'111', '11162_2'=>'111', '11779_2'=>'111', '11780_2'=>'111', '12737_2'=>'111', '12909_2'=>'111', '13074_2'=>'111',
                '14510_2'=>'111', '14627_2'=>'111', '15360_2'=>'111', '15467_2'=>'111', '15617_2'=>'111', '15930_2'=>'111', '15994_2'=>'111', '15995_2'=>'111', '17888_2'=>'111', '17924_2'=>'111', '17947_2'=>'111', '17948_2'=>'111', '18112_2'=>'111', '18119_2'=>'111', '18168_2'=>'111',
                '18169_2'=>'111', '18180_2'=>'111', '18221_2'=>'111', '18232_2'=>'111', '18241_2'=>'111', '18250_2'=>'111', '18476_2'=>'111', '18609_2'=>'111', '18980_2'=>'111', '19172_2'=>'111', '19504_2'=>'111', '19524_2'=>'111', '19530_2'=>'111', '19533_2'=>'111', '19572_2'=>'111',
                '19610_2'=>'111', '19612_2'=>'111', '19778_2'=>'111', '19890_2'=>'111', '20088_2'=>'111', '20089_2'=>'111', '20178_2'=>'111', '20314_2'=>'111', '20605_2'=>'111', '20889_2'=>'111', '20959_2'=>'111', '21499_2'=>'111', '21513_2'=>'111', '22279_2'=>'111', '24910_2'=>'111',
                '27216_2'=>'111', '27456_2'=>'111', '27854_2'=>'111', '27865_2'=>'111', '28089_2'=>'111', '28153_2'=>'111', '30786_2'=>'111', '30789_2'=>'111', '30790_2'=>'111', '30795_2'=>'111', '30849_2'=>'111', '30972_2'=>'111', '31129_2'=>'111', '31180_2'=>'111', '31225_2'=>'111',
                '32346_2'=>'111', '32359_2'=>'111', '32366_2'=>'111', '32405_2'=>'111', '32420_2'=>'111', '32450_2'=>'111', '32785_2'=>'111', '33116_2'=>'111', '33223_2'=>'111', '33224_2'=>'111', '33225_2'=>'111', '33817_2'=>'111', '33825_2'=>'111', '33830_2'=>'111', '33839_2'=>'111',
                '33891_2'=>'111', '33956_2'=>'111', '33963_2'=>'111', '33965_2'=>'111', '34642_2'=>'111', '37102_2'=>'111', '37109_2'=>'111', '37112_2'=>'111', '37651_2'=>'111', '37660_2'=>'111', '37665_2'=>'111', '38153_2'=>'111', '42407_2'=>'111', '42485_2'=>'111', '43496_2'=>'111',
                '44053_2'=>'111', '44498_2'=>'111', '44987_2'=>'111', '45127_2'=>'111', '45380_2'=>'111', '48843_2'=>'111', '48925_2'=>'111', '51545_2'=>'111', '52103_2'=>'111', '52308_2'=>'111', '54728_2'=>'111', '54734_2'=>'111', '56353_2'=>'111', '56741_2'=>'111', '59419_2'=>'111',
                '60916_2'=>'111', '61722_2'=>'111', '63102_2'=>'111', '66351_2'=>'111', '72797_2'=>'111', '76527_2'=>'111', '79520_2'=>'111', '86694_2'=>'111', '93128_2'=>'111', '93130_2'=>'111', '97077_2'=>'111', '110009_2'=>'111', '112357_2'=>'111', '12063_2'=>'111', '12107_2'=>'111',
                '15327_2'=>'111', '15940_2'=>'111', '23554_2'=>'111', '29723_2'=>'111', '34790_2'=>'111', '34792_2'=>'111', '34841_2'=>'111', '40740_2'=>'111', '41084_2'=>'111', '52698_2'=>'111', '58395_2'=>'111', '75849_2'=>'111', '76077_2'=>'111', '77114_2'=>'111', '82822_2'=>'111',
                '90270_2'=>'111', '102071_2'=>'111', '102217_2'=>'111', '102245_2'=>'111', '102268_2'=>'111', '102276_2'=>'111', '102281_2'=>'111', '102293_2'=>'111', '102314_2'=>'111', '102630_2'=>'111', '102727_2'=>'111', '102739_2'=>'111', '102741_2'=>'111', '103574_2'=>'111', '104841_2'=>'111',
                '104878_2'=>'111', '104937_2'=>'111', '105189_2'=>'111', '106630_2'=>'111', '106644_2'=>'111', '106658_2'=>'111', '106673_2'=>'111', '106694_2'=>'111', '107518_2'=>'111', '113182_2'=>'111', '121471_2'=>'111', '121584_2'=>'111', '123978_2'=>'111', '124490_2'=>'111', '4495_2'=>'111',
                '89192_2'=>'111', '89194_2'=>'111', '89206_2'=>'111', '89211_2'=>'111', '89214_2'=>'111', '89217_2'=>'111', '89230_2'=>'111', '89237_2'=>'111', '89240_2'=>'111', '89244_2'=>'111', '89255_2'=>'111', '89257_2'=>'111', '89263_2'=>'111', '89270_2'=>'111', '89276_2'=>'111',
                '89277_2'=>'111', '89446_2'=>'111', '89453_2'=>'111', '89458_2'=>'111', '89465_2'=>'111', '89468_2'=>'111', '89473_2'=>'111', '89475_2'=>'111', '89476_2'=>'111', '89480_2'=>'111', '89489_2'=>'111', '89499_2'=>'111', '89509_2'=>'111', '89510_2'=>'111', '89513_2'=>'111',
                '89515_2'=>'111', '89604_2'=>'111', '89649_2'=>'111', '89676_2'=>'111', '89680_2'=>'111', '89698_2'=>'111', '89700_2'=>'111', '89705_2'=>'111', '89713_2'=>'111', '89717_2'=>'111', '89732_2'=>'111', '89737_2'=>'111', '89744_2'=>'111', '89749_2'=>'111', '89756_2'=>'111',
                '89758_2'=>'111', '89762_2'=>'111', '89766_2'=>'111', '89919_2'=>'111', '89922_2'=>'111', '89935_2'=>'111', '89950_2'=>'111', '90173_2'=>'111', '90203_2'=>'111', '90214_2'=>'111', '90218_2'=>'111', '90403_2'=>'111', '90413_2'=>'111', '90424_2'=>'111', '90433_2'=>'111',
                '90873_2'=>'111', '90877_2'=>'111', '90904_2'=>'111', '90905_2'=>'111', '90926_2'=>'111', '90928_2'=>'111', '90940_2'=>'111', '90943_2'=>'111', '91115_2'=>'111', '91121_2'=>'111', '91133_2'=>'111', '91376_2'=>'111', '91381_2'=>'111', '91391_2'=>'111', '91401_2'=>'111',
                '91416_2'=>'111', '91428_2'=>'111', '91430_2'=>'111', '91642_2'=>'111', '91647_2'=>'111', '91651_2'=>'111', '91655_2'=>'111', '91667_2'=>'111', '91848_2'=>'111', '91861_2'=>'111', '92063_2'=>'111', '92083_2'=>'111', '92120_2'=>'111', '92132_2'=>'111', '92688_2'=>'111',
                '92695_2'=>'111', '92891_2'=>'111', '92947_2'=>'111', '93151_2'=>'111', '93314_2'=>'111', '93579_2'=>'111', '93594_2'=>'111', '93878_2'=>'111', '93896_2'=>'111', '94513_2'=>'111', '94716_2'=>'111', '94739_2'=>'111', '95896_2'=>'111', '96299_2'=>'111', '96339_2'=>'111',
                '97553_2'=>'111', '97877_2'=>'111', '97891_2'=>'111', '98885_2'=>'111', '100041_2'=>'111', '100337_2'=>'111', '100350_2'=>'111', '101070_2'=>'111', '101087_2'=>'111', '101770_2'=>'111', '102365_2'=>'111', '102378_2'=>'111', '102717_2'=>'111', '103231_2'=>'111', '103887_2'=>'111',
                '105527_2'=>'111', '106064_2'=>'111', '106667_2'=>'111', '107566_2'=>'111', '108065_2'=>'111', '108791_2'=>'111', '111062_2'=>'111', '96306_2'=>'111', '115621_2'=>'111', '115624_2'=>'111', '115641_2'=>'111', '115646_2'=>'111', '115657_2'=>'111', '115663_2'=>'111', '115668_2'=>'111',
                '115679_2'=>'111', '115702_2'=>'111', '115711_2'=>'111', '115845_2'=>'111', '115882_2'=>'111', '115894_2'=>'111', '115900_2'=>'111', '115904_2'=>'111', '115913_2'=>'111', '115919_2'=>'111', '115936_2'=>'111', '115943_2'=>'111', '115949_2'=>'111', '115978_2'=>'111', '115990_2'=>'111',
                '115994_2'=>'111', '116103_2'=>'111', '116160_2'=>'111', '116209_2'=>'111', '116364_2'=>'111', '116450_2'=>'111', '116776_2'=>'111', '116823_2'=>'111', '117549_2'=>'111', '118295_2'=>'111', '118586_2'=>'111', '118911_2'=>'111', '119589_2'=>'111', '119642_2'=>'111', '119884_2'=>'111',
                '121416_2'=>'111', '123034_2'=>'111', '123047_2'=>'111', '124270_2'=>'111', '125177_2'=>'111', '3127_2'=>'111', '4289_2'=>'111', '4316_2'=>'111', '4323_2'=>'111', '4383_2'=>'111', '4419_2'=>'111', '4481_2'=>'111', '7100_2'=>'111', '9090_2'=>'111', '10234_2'=>'111', '11228_2'=>'111',
                '11784_2'=>'111', '11792_2'=>'111', '11839_2'=>'111', '11868_2'=>'111', '11885_2'=>'111', '11897_2'=>'111', '11904_2'=>'111', '11945_2'=>'111', '11989_2'=>'111', '11996_2'=>'111', '11997_2'=>'111', '12052_2'=>'111', '12125_2'=>'111', '12173_2'=>'111', '12213_2'=>'111',
                '12220_2'=>'111', '12334_2'=>'111', '12341_2'=>'111', '12351_2'=>'111', '12383_2'=>'111', '12384_2'=>'111', '12424_2'=>'111', '12430_2'=>'111', '12448_2'=>'111', '12497_2'=>'111', '12567_2'=>'111', '12578_2'=>'111', '12882_2'=>'111', '12883_2'=>'111', '12893_2'=>'111',
                '12973_2'=>'111', '12975_2'=>'111', '12977_2'=>'111', '13002_2'=>'111', '13005_2'=>'111', '13094_2'=>'111', '13098_2'=>'111', '13100_2'=>'111', '13102_2'=>'111', '13189_2'=>'111', '13223_2'=>'111', '13374_2'=>'111', '13388_2'=>'111', '13507_2'=>'111', '13749_2'=>'111',
                '13906_2'=>'111', '13961_2'=>'111', '14225_2'=>'111', '14426_2'=>'111', '14435_2'=>'111', '14466_2'=>'111', '14553_2'=>'111', '14623_2'=>'111', '14692_2'=>'111', '14703_2'=>'111', '14864_2'=>'111', '14887_2'=>'111', '14942_2'=>'111', '14965_2'=>'111', '15050_2'=>'111',
                '15127_2'=>'111', '15211_2'=>'111', '15232_2'=>'111', '15249_2'=>'111', '15271_2'=>'111', '15328_2'=>'111', '15854_2'=>'111', '15905_2'=>'111', '15929_2'=>'111', '16096_2'=>'111', '16149_2'=>'111', '16244_2'=>'111', '16275_2'=>'111', '16349_2'=>'111', '16493_2'=>'111',
                '16497_2'=>'111', '16503_2'=>'111', '16593_2'=>'111', '16719_2'=>'111', '16779_2'=>'111', '16838_2'=>'111', '16865_2'=>'111', '16872_2'=>'111', '16896_2'=>'111', '16904_2'=>'111', '17159_2'=>'111', '17177_2'=>'111', '17596_2'=>'111', '17613_2'=>'111', '17746_2'=>'111',
                '17786_2'=>'111', '17889_2'=>'111', '17944_2'=>'111', '18039_2'=>'111', '18129_2'=>'111', '18211_2'=>'111', '18214_2'=>'111', '18224_2'=>'111', '18227_2'=>'111', '18255_2'=>'111', '18267_2'=>'111', '18274_2'=>'111', '18397_2'=>'111', '18404_2'=>'111', '18491_2'=>'111',
                '18493_2'=>'111', '18531_2'=>'111', '18600_2'=>'111', '18621_2'=>'111', '18684_2'=>'111', '18879_2'=>'111', '18880_2'=>'111', '19003_2'=>'111', '19018_2'=>'111', '19054_2'=>'111', '19454_2'=>'111', '19698_2'=>'111', '19762_2'=>'111', '19776_2'=>'111', '19933_2'=>'111',
                '20064_2'=>'111', '20170_2'=>'111', '20309_2'=>'111', '20406_2'=>'111', '20645_2'=>'111', '20871_2'=>'111', '21295_2'=>'111', '21949_2'=>'111', '22599_2'=>'111', '22731_2'=>'111', '26126_2'=>'111', '29312_2'=>'111', '31677_2'=>'111', '31680_2'=>'111', '31713_2'=>'111',
                '31743_2'=>'111', '31745_2'=>'111', '31766_2'=>'111', '32361_2'=>'111', '32488_2'=>'111', '34169_2'=>'111', '34172_2'=>'111', '34341_2'=>'111', '42751_2'=>'111', '42752_2'=>'111', '43865_2'=>'111', '43908_2'=>'111', '44128_2'=>'111', '44165_2'=>'111', '44166_2'=>'111',
                '44167_2'=>'111', '46829_2'=>'111', '47806_2'=>'111', '47807_2'=>'111', '47808_2'=>'111', '47809_2'=>'111', '47810_2'=>'111', '48385_2'=>'111', '52033_2'=>'111', '56598_2'=>'111', '57003_2'=>'111', '64014_2'=>'111', '72251_2'=>'111', '72518_2'=>'111', '72924_2'=>'111',
                '73932_2'=>'111', '91519_2'=>'111', '94172_2'=>'111', '104082_2'=>'111', '115715_2'=>'111', '17568_2'=>'111', '18091_2'=>'111', '18167_2'=>'111', '29122_2'=>'111', '53527_2'=>'111', '71499_2'=>'111', '73865_2'=>'111', '73870_2'=>'111', '73874_2'=>'111', '73889_2'=>'111',
                '73893_2'=>'111', '73895_2'=>'111', '73897_2'=>'111', '73907_2'=>'111', '74185_2'=>'111', '74214_2'=>'111', '74645_2'=>'111', '74663_2'=>'111', '74671_2'=>'111', '74677_2'=>'111', '74688_2'=>'111', '74708_2'=>'111', '75070_2'=>'111', '75142_2'=>'111', '76787_2'=>'111',
                '77112_2'=>'111', '77980_2'=>'111', '78044_2'=>'111', '78837_2'=>'111', '78841_2'=>'111', '78846_2'=>'111', '78847_2'=>'111', '78848_2'=>'111', '78849_2'=>'111', '78957_2'=>'111', '78958_2'=>'111', '78959_2'=>'111', '78963_2'=>'111', '79263_2'=>'111', '79275_2'=>'111',
                '79317_2'=>'111', '79865_2'=>'111', '80820_2'=>'111', '81158_2'=>'111', '82136_2'=>'111', '82377_2'=>'111', '82428_2'=>'111', '84365_2'=>'111', '84367_2'=>'111', '85467_2'=>'111', '85862_2'=>'111', '90275_2'=>'111', '90476_2'=>'111', '90963_2'=>'111', '91451_2'=>'111',
                '92133_2'=>'111', '92481_2'=>'111', '92967_2'=>'111', '93856_2'=>'111', '94505_2'=>'111', '95417_2'=>'111', '95853_2'=>'111', '96606_2'=>'111', '99216_2'=>'111', '101413_2'=>'111', '105506_2'=>'111', '105986_2'=>'111', '105987_2'=>'111', '109185_2'=>'111', '111079_2'=>'111',
                '112258_2'=>'111', '112260_2'=>'111', '125465_2'=>'111', '2495_2'=>'111', '2507_2'=>'111', '13086_2'=>'111', '55932_2'=>'111', '55935_2'=>'111', '56076_2'=>'111', '56141_2'=>'111', '56143_2'=>'111', '56161_2'=>'111', '56174_2'=>'111', '56182_2'=>'111', '56187_2'=>'111',
                '56192_2'=>'111', '56202_2'=>'111', '56341_2'=>'111', '56343_2'=>'111', '57352_2'=>'111', '57635_2'=>'111', '59221_2'=>'111', '62092_2'=>'111', '63656_2'=>'111', '64703_2'=>'111', '65085_2'=>'111', '65288_2'=>'111', '66758_2'=>'111', '68155_2'=>'111', '70212_2'=>'111',
                '72650_2'=>'111', '73034_2'=>'111', '73969_2'=>'111', '78992_2'=>'111', '80323_2'=>'111', '83541_2'=>'111', '85416_2'=>'111', '89461_2'=>'111', '94057_2'=>'111', '95254_2'=>'111', '105132_2'=>'111', '112728_2'=>'111', '112862_2'=>'111', '114880_2'=>'111', '115043_2'=>'111',
                '125536_2'=>'111', '30_2'=>'126', '33_2'=>'126', '34_2'=>'126', '42_2'=>'126', '63_2'=>'126', '93_2'=>'126', '94_2'=>'126', '101_2'=>'126', '104_2'=>'126', '110_2'=>'126', '117_2'=>'126', '118_2'=>'126', '119_2'=>'126', '131_2'=>'126', '133_2'=>'126', '134_2'=>'126', '161_2'=>'126',
                '200_2'=>'126', '236_2'=>'126', '255_2'=>'126', '266_2'=>'126', '267_2'=>'126', '273_2'=>'126', '278_2'=>'126', '288_2'=>'126', '314_2'=>'126', '360_2'=>'126', '376_2'=>'126', '610_2'=>'126', '692_2'=>'126', '759_2'=>'126', '926_2'=>'126', '1494_2'=>'126', '1753_2'=>'126',
                '2169_2'=>'126', '2205_2'=>'126', '2206_2'=>'126', '2829_2'=>'126', '2908_2'=>'126', '3458_2'=>'126', '3831_2'=>'126', '4236_2'=>'126', '4614_2'=>'126', '5010_2'=>'126', '5060_2'=>'126', '6437_2'=>'126', '6478_2'=>'126', '7447_2'=>'126', '7491_2'=>'126', '7543_2'=>'126',
                '7589_2'=>'126', '7722_2'=>'126', '7787_2'=>'126', '7973_2'=>'126', '8223_2'=>'126', '8224_2'=>'126', '8449_2'=>'126', '8893_2'=>'126', '9022_2'=>'126', '9299_2'=>'126', '9691_2'=>'126', '9710_2'=>'126', '11642_2'=>'126', '13046_2'=>'126', '13707_2'=>'126', '14276_2'=>'126',
                '14859_2'=>'126', '15686_2'=>'126', '16988_2'=>'126', '17434_2'=>'126', '17465_2'=>'126', '17649_2'=>'126', '17904_2'=>'126', '18492_2'=>'126', '19537_2'=>'126', '19653_2'=>'126', '19932_2'=>'126', '19967_2'=>'126', '19970_2'=>'126', '20961_2'=>'126', '21308_2'=>'126',
                '22170_2'=>'126', '23612_2'=>'126', '23981_2'=>'126', '26476_2'=>'126', '26633_2'=>'126', '26656_2'=>'126', '27139_2'=>'126', '28278_2'=>'126', '28523_2'=>'126', '29894_2'=>'126', '30219_2'=>'126', '32342_2'=>'126', '32695_2'=>'126', '33674_2'=>'126', '34451_2'=>'126',
                '38584_2'=>'126', '38585_2'=>'126', '38897_2'=>'126', '38899_2'=>'126', '40968_2'=>'126', '41750_2'=>'126', '43830_2'=>'126', '44455_2'=>'126', '44495_2'=>'126', '45319_2'=>'126', '45321_2'=>'126', '45505_2'=>'126', '47442_2'=>'126', '47875_2'=>'126', '51062_2'=>'126',
                '51063_2'=>'126', '52619_2'=>'126', '53644_2'=>'126', '53797_2'=>'126', '53842_2'=>'126', '54178_2'=>'126', '54682_2'=>'126', '54735_2'=>'126', '55056_2'=>'126', '55229_2'=>'126', '55664_2'=>'126', '55870_2'=>'126', '56645_2'=>'126', '60133_2'=>'126', '62926_2'=>'126',
                '63057_2'=>'126', '64037_2'=>'126', '75176_2'=>'126', '75214_2'=>'126', '75549_2'=>'126', '84406_2'=>'126', '89593_2'=>'126', '93476_2'=>'126', '94453_2'=>'126', '96317_2'=>'126', '96958_2'=>'126', '102273_2'=>'126', '111390_2'=>'126', '112112_2'=>'126', '115850_2'=>'126',
                '119835_2'=>'126', '121636_2'=>'126', '122514_2'=>'126', '123063_2'=>'126', '128512_2'=>'126', '47_2'=>'126', '69_2'=>'126', '85_2'=>'126', '105_2'=>'126', '111_2'=>'126', '114_2'=>'126', '181_2'=>'126', '197_2'=>'126', '289_2'=>'126', '773_2'=>'126', '2848_2'=>'126', '3372_2'=>'126',
                '3403_2'=>'126', '3675_2'=>'126', '3700_2'=>'126', '3713_2'=>'126', '3800_2'=>'126', '3907_2'=>'126', '4012_2'=>'126', '4295_2'=>'126', '4404_2'=>'126', '5234_2'=>'126', '5235_2'=>'126', '5575_2'=>'126', '7016_2'=>'126', '7281_2'=>'126', '7557_2'=>'126', '7919_2'=>'126',
                '9060_2'=>'126', '9186_2'=>'126', '9577_2'=>'126', '12623_2'=>'126', '12624_2'=>'126', '12641_2'=>'126', '12655_2'=>'126', '12659_2'=>'126', '12667_2'=>'126', '12671_2'=>'126', '12674_2'=>'126', '12699_2'=>'126', '12704_2'=>'126', '12712_2'=>'126', '12718_2'=>'126', '12739_2'=>'126',
                '12749_2'=>'126', '12767_2'=>'126', '12804_2'=>'126', '12808_2'=>'126', '12809_2'=>'126', '12833_2'=>'126', '12936_2'=>'126', '12943_2'=>'126', '12951_2'=>'126', '12968_2'=>'126', '12993_2'=>'126', '13103_2'=>'126', '13140_2'=>'126', '13153_2'=>'126', '13154_2'=>'126',
                '13172_2'=>'126', '13436_2'=>'126', '13503_2'=>'126', '13582_2'=>'126', '13862_2'=>'126', '13959_2'=>'126', '14209_2'=>'126', '14437_2'=>'126', '14449_2'=>'126', '14450_2'=>'126', '14854_2'=>'126', '14915_2'=>'126', '15003_2'=>'126', '15101_2'=>'126', '15235_2'=>'126',
                '15296_2'=>'126', '15358_2'=>'126', '15579_2'=>'126', '15664_2'=>'126', '15893_2'=>'126', '16810_2'=>'126', '17104_2'=>'126', '17105_2'=>'126', '17145_2'=>'126', '17210_2'=>'126', '17563_2'=>'126', '17783_2'=>'126', '17923_2'=>'126', '18065_2'=>'126', '18818_2'=>'126',
                '19329_2'=>'126', '19443_2'=>'126', '19451_2'=>'126', '19506_2'=>'126', '19799_2'=>'126', '19984_2'=>'126', '20007_2'=>'126', '20078_2'=>'126', '20665_2'=>'126', '21128_2'=>'126', '21129_2'=>'126', '21410_2'=>'126', '21446_2'=>'126', '21672_2'=>'126', '21840_2'=>'126',
                '21845_2'=>'126', '22564_2'=>'126', '22707_2'=>'126', '23195_2'=>'126', '23197_2'=>'126', '23537_2'=>'126', '24322_2'=>'126', '25550_2'=>'126', '25763_2'=>'126', '25885_2'=>'126', '26359_2'=>'126', '26911_2'=>'126', '27759_2'=>'126', '27775_2'=>'126', '27794_2'=>'126',
                '27839_2'=>'126', '28414_2'=>'126', '28518_2'=>'126', '28679_2'=>'126', '28710_2'=>'126', '28854_2'=>'126', '29142_2'=>'126', '29157_2'=>'126', '29773_2'=>'126', '29951_2'=>'126', '30346_2'=>'126', '30443_2'=>'126', '30447_2'=>'126', '31166_2'=>'126', '31172_2'=>'126',
                '31175_2'=>'126', '31179_2'=>'126', '31189_2'=>'126', '31201_2'=>'126', '31202_2'=>'126', '31203_2'=>'126', '31204_2'=>'126', '31209_2'=>'126', '31210_2'=>'126', '31452_2'=>'126', '32404_2'=>'126', '32740_2'=>'126', '32741_2'=>'126', '32755_2'=>'126', '32773_2'=>'126',
                '33017_2'=>'126', '33319_2'=>'126', '33727_2'=>'126', '34633_2'=>'126', '34839_2'=>'126', '36301_2'=>'126', '36750_2'=>'126', '36813_2'=>'126', '37403_2'=>'126', '37519_2'=>'126', '37581_2'=>'126', '37839_2'=>'126', '37844_2'=>'126', '38367_2'=>'126', '39114_2'=>'126',
                '39558_2'=>'126', '40199_2'=>'126', '40200_2'=>'126', '40602_2'=>'126', '40765_2'=>'126', '41437_2'=>'126', '42329_2'=>'126', '43848_2'=>'126', '43906_2'=>'126', '43914_2'=>'126', '48626_2'=>'126', '48628_2'=>'126', '49290_2'=>'126', '49517_2'=>'126', '53419_2'=>'126',
                '53938_2'=>'126', '54776_2'=>'126', '66725_2'=>'126', '68895_2'=>'126', '69915_2'=>'126', '69936_2'=>'126', '70206_2'=>'126', '73714_2'=>'126', '74027_2'=>'126', '77079_2'=>'126', '77686_2'=>'126', '77870_2'=>'126', '80290_2'=>'126', '85827_2'=>'126', '85935_2'=>'126',
                '87354_2'=>'126', '94274_2'=>'126', '95494_2'=>'126', '107593_2'=>'126', '116887_2'=>'126', '124130_2'=>'126', '130848_2'=>'126', '5638_2'=>'126', '21787_2'=>'126', '27884_2'=>'126', '33311_2'=>'126', '51193_2'=>'126', '51195_2'=>'126', '51197_2'=>'126', '51200_2'=>'126',
                '51201_2'=>'126', '51203_2'=>'126', '51204_2'=>'126', '51210_2'=>'126', '51212_2'=>'126', '51214_2'=>'126', '51330_2'=>'126', '51338_2'=>'126', '51339_2'=>'126', '51345_2'=>'126', '51354_2'=>'126', '51363_2'=>'126', '51366_2'=>'126', '51376_2'=>'126', '51387_2'=>'126',
                '51400_2'=>'126', '51402_2'=>'126', '51404_2'=>'126', '51411_2'=>'126', '51546_2'=>'126', '51629_2'=>'126', '51651_2'=>'126', '51658_2'=>'126', '51659_2'=>'126', '51743_2'=>'126', '52218_2'=>'126', '52357_2'=>'126', '52776_2'=>'126', '53239_2'=>'126', '53480_2'=>'126',
                '53727_2'=>'126', '54013_2'=>'126', '55186_2'=>'126', '58154_2'=>'126', '60112_2'=>'126', '60597_2'=>'126', '65416_2'=>'126', '68292_2'=>'126', '71032_2'=>'126', '71623_2'=>'126', '73055_2'=>'126', '76656_2'=>'126', '78283_2'=>'126', '79197_2'=>'126', '79565_2'=>'126',
                '80228_2'=>'126', '81311_2'=>'126', '82365_2'=>'126', '82836_2'=>'126', '82928_2'=>'126', '85843_2'=>'126', '86025_2'=>'126', '86387_2'=>'126', '90241_2'=>'126', '90500_2'=>'126', '93547_2'=>'126', '93935_2'=>'126', '93971_2'=>'126', '95216_2'=>'126', '95536_2'=>'126',
                '96364_2'=>'126', '96818_2'=>'126', '97441_2'=>'126', '99263_2'=>'126', '100653_2'=>'126', '101672_2'=>'126', '105962_2'=>'126', '117364_2'=>'126', '121465_2'=>'126', '126170_2'=>'126', '128257_2'=>'126', '130052_2'=>'126', '130084_2'=>'126', '67132_2'=>'84', '56731_2'=>'84',
                '62278_2'=>'84', '63088_2'=>'84', '63111_2'=>'84', '62554_2'=>'84', '62120_2'=>'84', '56627_2'=>'84', '63509_2'=>'84', '56767_2'=>'84', '64714_2'=>'84', '62276_2'=>'84', '55594_2'=>'84', '58682_2'=>'84', '41155_2'=>'84', '67335_2'=>'84', '55657_2'=>'84', '55974_2'=>'84',
                '56808_2'=>'84', '55660_2'=>'84', '55584_2'=>'84', '55571_2'=>'84', '55576_2'=>'84', '55562_2'=>'84', '70021_2'=>'84', '56108_2'=>'84', '73338_2'=>'84', '73371_2'=>'84', '80033_2'=>'84', '81243_2'=>'84', '89770_2'=>'84', '95842_2'=>'84', '95991_2'=>'84', '103243_2'=>'84',
                '106557_2'=>'84', '115841_2'=>'84', '121808_2'=>'84', '123574_2'=>'84', '126948_2'=>'84', '55109_2'=>'84', '40052_2'=>'84', '41299_2'=>'84', '31363_2'=>'84', '13886_2'=>'84', '5595_2'=>'84', '3046_2'=>'84', '25339_2'=>'84', '1049_2'=>'84', '31161_2'=>'84', '76198_2'=>'84',
                '54209_2'=>'84', '2817_2'=>'84', '41255_2'=>'84', '45295_2'=>'84', '40210_2'=>'84', '31329_2'=>'84', '71221_2'=>'84', '2382_2'=>'84', '42445_2'=>'84', '19148_2'=>'84', '33238_2'=>'84', '7505_2'=>'84', '1126_2'=>'84', '72950_2'=>'84', '79416_2'=>'84', '84473_2'=>'84', '86419_2'=>'84',
                '86939_2'=>'84', '94710_2'=>'84', '99682_2'=>'84', '109739_2'=>'84', '112045_2'=>'84', '112870_2'=>'84', '114474_2'=>'84', '114503_2'=>'84', '114975_2'=>'84', '121946_2'=>'84', '121948_2'=>'84', '122848_2'=>'84', '129364_2'=>'84', '135148_2'=>'84', '15276_2'=>'84', '37993_2'=>'84',
                '40572_2'=>'84', '21853_2'=>'84', '20322_2'=>'84', '40601_2'=>'84', '23921_2'=>'84', '21249_2'=>'84', '42172_2'=>'84', '27483_2'=>'84', '21638_2'=>'84', '32092_2'=>'84', '5273_2'=>'84', '21571_2'=>'84', '37037_2'=>'84', '32893_2'=>'84', '6496_2'=>'84', '20350_2'=>'84',
                '69257_2'=>'84', '21399_2'=>'84', '20561_2'=>'84', '36327_2'=>'84', '20474_2'=>'84', '60045_2'=>'84', '34001_2'=>'84', '69737_2'=>'84', '11089_2'=>'84', '69054_2'=>'84', '28373_2'=>'84', '27438_2'=>'84', '57726_2'=>'84', '49054_2'=>'84', '46043_2'=>'84', '43050_2'=>'84',
                '15554_2'=>'84', '20012_2'=>'84', '27072_2'=>'84', '25579_2'=>'84', '15631_2'=>'84', '36144_2'=>'84', '52282_2'=>'84', '15486_2'=>'84', '29408_2'=>'84', '23467_2'=>'84', '32462_2'=>'84', '21758_2'=>'84', '57537_2'=>'84', '20903_2'=>'84', '30370_2'=>'84', '18220_2'=>'84',
                '37740_2'=>'84', '69264_2'=>'84', '19543_2'=>'84', '55878_2'=>'84', '15616_2'=>'84', '21239_2'=>'84', '32184_2'=>'84', '16036_2'=>'84', '21236_2'=>'84', '17921_2'=>'84', '35469_2'=>'84', '32434_2'=>'84', '21875_2'=>'84', '25855_2'=>'84', '22181_2'=>'84', '15192_2'=>'84',
                '21938_2'=>'84', '15546_2'=>'84', '55154_2'=>'84', '36994_2'=>'84', '15574_2'=>'84', '39419_2'=>'84', '15749_2'=>'84', '15565_2'=>'84', '21559_2'=>'84', '24892_2'=>'84', '52486_2'=>'84', '55683_2'=>'84', '55572_2'=>'84', '37351_2'=>'84', '50203_2'=>'84', '20745_2'=>'84',
                '25505_2'=>'84', '39132_2'=>'84', '27603_2'=>'84', '37088_2'=>'84', '20856_2'=>'84', '22706_2'=>'84', '15186_2'=>'84', '32427_2'=>'84', '29012_2'=>'84', '37026_2'=>'84', '36133_2'=>'84', '51037_2'=>'84', '35175_2'=>'84', '41641_2'=>'84', '69029_2'=>'84', '37117_2'=>'84',
                '15542_2'=>'84', '30913_2'=>'84', '51268_2'=>'84', '28687_2'=>'84', '71260_2'=>'84', '72709_2'=>'84', '72734_2'=>'84', '73444_2'=>'84', '75633_2'=>'84', '76804_2'=>'84', '76912_2'=>'84', '79724_2'=>'84', '80555_2'=>'84', '81316_2'=>'84', '82406_2'=>'84', '92386_2'=>'84',
                '93647_2'=>'84', '97481_2'=>'84', '103721_2'=>'84', '103991_2'=>'84', '114149_2'=>'84', '115515_2'=>'84', '136298_2'=>'84', '51269_2'=>'128', '40864_2'=>'128', '40585_2'=>'128', '56599_2'=>'128', '63488_2'=>'128', '50875_2'=>'128', '45747_2'=>'128', '55851_2'=>'128', '42037_2'=>'128',
                '45595_2'=>'128', '58498_2'=>'128', '49398_2'=>'128', '40613_2'=>'128', '39570_2'=>'128', '39603_2'=>'128', '46591_2'=>'128', '62870_2'=>'128', '43994_2'=>'128', '39571_2'=>'128', '48408_2'=>'128', '54853_2'=>'128', '3594_2'=>'128', '39644_2'=>'128', '49954_2'=>'128',
                '42663_2'=>'128', '60902_2'=>'128', '39575_2'=>'128', '41329_2'=>'128', '40038_2'=>'128', '52895_2'=>'128', '53091_2'=>'128', '52182_2'=>'128', '4307_2'=>'128', '39601_2'=>'128', '40153_2'=>'128', '50124_2'=>'128', '52119_2'=>'128', '40759_2'=>'128', '41147_2'=>'128',
                '69773_2'=>'128', '10475_2'=>'128', '41373_2'=>'128', '19253_2'=>'128', '52903_2'=>'128', '40139_2'=>'128', '39770_2'=>'128', '39815_2'=>'128', '52294_2'=>'128', '40269_2'=>'128', '44155_2'=>'128', '39686_2'=>'128', '48873_2'=>'128', '39599_2'=>'128', '39602_2'=>'128',
                '7974_2'=>'128', '16737_2'=>'128', '70324_2'=>'128', '72248_2'=>'128', '73318_2'=>'128', '74760_2'=>'128', '78113_2'=>'128', '86242_2'=>'128', '89523_2'=>'128', '89532_2'=>'128', '91048_2'=>'128', '100882_2'=>'128', '100922_2'=>'128', '107619_2'=>'128', '108031_2'=>'128',
                '109622_2'=>'128', '113470_2'=>'128', '115098_2'=>'128', '116474_2'=>'128', '119118_2'=>'128', '120349_2'=>'128', '121904_2'=>'128', '122515_2'=>'128', '127501_2'=>'128', '127648_2'=>'128', '130489_2'=>'128', '132767_2'=>'128', '754_2'=>'71', '828_2'=>'71', '957_2'=>'71',
                '972_2'=>'71', '978_2'=>'71', '3530_2'=>'71', '3882_2'=>'71', '4373_2'=>'71', '6904_2'=>'71', '6937_2'=>'71', '6940_2'=>'71', '7090_2'=>'71', '7094_2'=>'71', '7180_2'=>'71', '7361_2'=>'71', '7362_2'=>'71', '7388_2'=>'71', '7421_2'=>'71', '7432_2'=>'71', '7535_2'=>'71', '7546_2'=>'71',
                '7689_2'=>'71', '8079_2'=>'71', '8182_2'=>'71', '8241_2'=>'71', '8250_2'=>'71', '8294_2'=>'71', '8392_2'=>'71', '8668_2'=>'71', '10071_2'=>'71', '10112_2'=>'71', '10491_2'=>'71', '11656_2'=>'71', '11750_2'=>'71', '12222_2'=>'71', '12513_2'=>'71', '13218_2'=>'71', '13969_2'=>'71',
                '14600_2'=>'71', '15145_2'=>'71', '15950_2'=>'71', '16118_2'=>'71', '16509_2'=>'71', '16595_2'=>'71', '16950_2'=>'71', '16999_2'=>'71', '17308_2'=>'71', '17698_2'=>'71', '17883_2'=>'71', '18116_2'=>'71', '18127_2'=>'71', '18175_2'=>'71', '18470_2'=>'71', '18915_2'=>'71',
                '19152_2'=>'71', '19277_2'=>'71', '19656_2'=>'71', '19757_2'=>'71', '19791_2'=>'71', '19811_2'=>'71', '19813_2'=>'71', '19833_2'=>'71', '20080_2'=>'71', '20086_2'=>'71', '20302_2'=>'71', '20395_2'=>'71', '20429_2'=>'71', '20578_2'=>'71', '20799_2'=>'71', '22075_2'=>'71',
                '22864_2'=>'71', '24772_2'=>'71', '24946_2'=>'71', '25065_2'=>'71', '25498_2'=>'71', '25556_2'=>'71', '25734_2'=>'71', '26096_2'=>'71', '26119_2'=>'71', '26429_2'=>'71', '26437_2'=>'71', '26905_2'=>'71', '27410_2'=>'71', '28178_2'=>'71', '28187_2'=>'71', '29032_2'=>'71',
                '29589_2'=>'71', '29757_2'=>'71', '29953_2'=>'71', '30596_2'=>'71', '30772_2'=>'71', '30970_2'=>'71', '31008_2'=>'71', '31184_2'=>'71', '31782_2'=>'71', '31876_2'=>'71', '32299_2'=>'71', '32300_2'=>'71', '35729_2'=>'71', '36210_2'=>'71', '36259_2'=>'71', '37924_2'=>'71',
                '38149_2'=>'71', '39221_2'=>'71', '40627_2'=>'71', '41308_2'=>'71', '44757_2'=>'71', '46391_2'=>'71', '46549_2'=>'71', '49187_2'=>'71', '51228_2'=>'71', '52235_2'=>'71', '52314_2'=>'71', '54495_2'=>'71', '55202_2'=>'71', '55542_2'=>'71', '55713_2'=>'71', '67284_2'=>'71',
                '69804_2'=>'71', '70288_2'=>'71', '71774_2'=>'71', '72043_2'=>'71', '72395_2'=>'71', '73351_2'=>'71', '74115_2'=>'71', '77384_2'=>'71', '77647_2'=>'71', '87793_2'=>'71', '87799_2'=>'71', '88089_2'=>'71', '919_2'=>'71', '6066_2'=>'71', '14513_2'=>'71', '14607_2'=>'71', '15515_2'=>'71',
                '15744_2'=>'71', '15859_2'=>'71', '16037_2'=>'71', '16432_2'=>'71', '16484_2'=>'71', '17435_2'=>'71', '19267_2'=>'71', '19270_2'=>'71', '19278_2'=>'71', '19330_2'=>'71', '19732_2'=>'71', '19744_2'=>'71', '20409_2'=>'71', '28247_2'=>'71', '30926_2'=>'71', '31124_2'=>'71',
                '35607_2'=>'71', '36212_2'=>'71', '37625_2'=>'71', '43542_2'=>'71', '45889_2'=>'71', '46800_2'=>'71', '52034_2'=>'71', '52331_2'=>'71', '52413_2'=>'71', '52439_2'=>'71', '52629_2'=>'71', '53103_2'=>'71', '53612_2'=>'71', '57013_2'=>'71', '57296_2'=>'71', '57432_2'=>'71',
                '66195_2'=>'71', '66500_2'=>'71', '72511_2'=>'71', '74452_2'=>'71', '83076_2'=>'71', '88845_2'=>'71', '91274_2'=>'71', '91989_2'=>'71', '104692_2'=>'71', '111922_2'=>'71', '114178_2'=>'71', '116436_2'=>'71', '117054_2'=>'71', '119272_2'=>'71', '128659_2'=>'71', '130526_2'=>'71',
                '133117_2'=>'71', '133297_2'=>'71', '135020_2'=>'71', '135102_2'=>'71', '135832_2'=>'71', '136218_2'=>'71', '138680_2'=>'71', '139152_2'=>'71', '140085_2'=>'71', '141799_2'=>'71', '44089_2'=>'71', '44598_2'=>'71', '57519_2'=>'71', '59756_2'=>'71', '60385_2'=>'71', '62066_2'=>'71',
                '62085_2'=>'71', '63365_2'=>'71', '63427_2'=>'71', '63877_2'=>'71', '64117_2'=>'71', '68169_2'=>'71', '68352_2'=>'71', '68912_2'=>'71', '72971_2'=>'71', '77682_2'=>'71', '77986_2'=>'71', '87066_2'=>'71', '95603_2'=>'71', '107929_2'=>'71', '108776_2'=>'71', '109111_2'=>'71',
                '111606_2'=>'71', '122497_2'=>'71', '123731_2'=>'71', '124246_2'=>'71', '126975_2'=>'71', '127785_2'=>'71', '128038_2'=>'71', '128792_2'=>'71', '129370_2'=>'71', '131545_2'=>'71', '133532_2'=>'71', '134913_2'=>'71', '134930_2'=>'71', '134936_2'=>'71', '134947_2'=>'71',
                '134955_2'=>'71', '134967_2'=>'71', '134972_2'=>'71', '135104_2'=>'71', '135223_2'=>'71', '135252_2'=>'71', '135271_2'=>'71', '135399_2'=>'71', '135408_2'=>'71', '135419_2'=>'71', '135480_2'=>'71', '135509_2'=>'71', '135522_2'=>'71', '135788_2'=>'71', '135791_2'=>'71',
                '135965_2'=>'71', '136002_2'=>'71', '136121_2'=>'71', '136186_2'=>'71', '137206_2'=>'71', '137516_2'=>'71', '137551_2'=>'71', '138644_2'=>'71', '139361_2'=>'71', '140117_2'=>'71', '140385_2'=>'71', '140399_2'=>'71', '141168_2'=>'71', '141239_2'=>'71', '142533_2'=>'71',
                '144492_2'=>'71', '8275_2'=>'71', '15714_2'=>'71', '23584_2'=>'71', '23588_2'=>'71', '23590_2'=>'71', '23591_2'=>'71', '23594_2'=>'71', '23598_2'=>'71', '23600_2'=>'71', '23601_2'=>'71', '23603_2'=>'71', '23604_2'=>'71', '23635_2'=>'71', '23690_2'=>'71', '23692_2'=>'71',
                '23697_2'=>'71', '23703_2'=>'71', '23704_2'=>'71', '23708_2'=>'71', '23743_2'=>'71', '23759_2'=>'71', '23852_2'=>'71', '23865_2'=>'71', '23965_2'=>'71', '24218_2'=>'71', '24219_2'=>'71', '24260_2'=>'71', '24325_2'=>'71', '24464_2'=>'71', '24640_2'=>'71', '25340_2'=>'71',
                '25436_2'=>'71', '25836_2'=>'71', '26236_2'=>'71', '26962_2'=>'71', '27244_2'=>'71', '27420_2'=>'71', '27497_2'=>'71', '27565_2'=>'71', '28107_2'=>'71', '28399_2'=>'71', '28400_2'=>'71', '28512_2'=>'71', '28581_2'=>'71', '29332_2'=>'71', '30038_2'=>'71', '30364_2'=>'71',
                '30423_2'=>'71', '30522_2'=>'71', '30779_2'=>'71', '31637_2'=>'71', '31721_2'=>'71', '32313_2'=>'71', '33573_2'=>'71', '33673_2'=>'71', '33812_2'=>'71', '34264_2'=>'71', '34735_2'=>'71', '35375_2'=>'71', '35875_2'=>'71', '35939_2'=>'71', '36384_2'=>'71', '36554_2'=>'71',
                '36559_2'=>'71', '37079_2'=>'71', '37119_2'=>'71', '37428_2'=>'71', '37497_2'=>'71', '39056_2'=>'71', '39113_2'=>'71', '39185_2'=>'71', '40251_2'=>'71', '42661_2'=>'71', '42809_2'=>'71', '43066_2'=>'71', '44630_2'=>'71', '46371_2'=>'71', '47461_2'=>'71', '47733_2'=>'71',
                '48828_2'=>'71', '49390_2'=>'71', '51216_2'=>'71', '51344_2'=>'71', '51571_2'=>'71', '51758_2'=>'71', '53658_2'=>'71', '55205_2'=>'71', '56097_2'=>'71', '56357_2'=>'71', '56854_2'=>'71', '58027_2'=>'71', '59043_2'=>'71', '59446_2'=>'71', '60153_2'=>'71', '60155_2'=>'71',
                '61055_2'=>'71', '61739_2'=>'71', '62424_2'=>'71', '62729_2'=>'71', '63551_2'=>'71', '64621_2'=>'71', '64961_2'=>'71', '65108_2'=>'71', '66881_2'=>'71', '68622_2'=>'71', '70084_2'=>'71', '70588_2'=>'71', '73037_2'=>'71', '73208_2'=>'71', '73878_2'=>'71', '75208_2'=>'71',
                '75302_2'=>'71', '75846_2'=>'71', '78321_2'=>'71', '78935_2'=>'71', '81063_2'=>'71', '81609_2'=>'71', '82353_2'=>'71', '82619_2'=>'71', '84274_2'=>'71', '85720_2'=>'71', '85952_2'=>'71', '88006_2'=>'71', '90980_2'=>'71', '92199_2'=>'71', '92747_2'=>'71', '92821_2'=>'71',
                '93053_2'=>'71', '93435_2'=>'71', '94119_2'=>'71', '94132_2'=>'71', '94228_2'=>'71', '97486_2'=>'71', '98265_2'=>'71', '98913_2'=>'71', '98916_2'=>'71', '100357_2'=>'71', '105539_2'=>'71', '105765_2'=>'71', '108832_2'=>'71', '109439_2'=>'71', '109851_2'=>'71', '111908_2'=>'71',
                '112068_2'=>'71', '112108_2'=>'71', '112190_2'=>'71', '113114_2'=>'71', '113272_2'=>'71', '115739_2'=>'71', '117327_2'=>'71', '121065_2'=>'71', '122499_2'=>'71', '125806_2'=>'71', '126906_2'=>'71', '127146_2'=>'71', '128058_2'=>'71', '128848_2'=>'71', '129347_2'=>'71',
                '135274_2'=>'71', '135338_2'=>'71', '136918_2'=>'71', '137815_2'=>'71', '138679_2'=>'71', '139537_2'=>'71', '139560_2'=>'71', '139724_2'=>'71', '141403_2'=>'71', '142888_2'=>'71', '143223_2'=>'71', '19299_2'=>'71', '46179_2'=>'71', '51684_2'=>'71', '51952_2'=>'71', '61140_2'=>'71',
                '64402_2'=>'71', '67372_2'=>'71', '84388_2'=>'71', '116223_2'=>'71', '129372_2'=>'71', '129984_2'=>'71', '129998_2'=>'71', '130020_2'=>'71', '131175_2'=>'71', '131693_2'=>'71', '133519_2'=>'71', '133959_2'=>'71', '140350_2'=>'71', '141990_2'=>'71', '141993_2'=>'71', '143934_2'=>'71',
                '143954_2'=>'71', '143955_2'=>'71', '144390_2'=>'71', '18839_2'=>'136', '19108_2'=>'136', '22147_2'=>'136', '27153_2'=>'136', '27697_2'=>'136', '27700_2'=>'136', '28206_2'=>'136', '28207_2'=>'136', '28208_2'=>'136', '28209_2'=>'136', '28257_2'=>'136', '28307_2'=>'136',
                '28371_2'=>'136', '28429_2'=>'136', '28699_2'=>'136', '29067_2'=>'136', '29068_2'=>'136', '29365_2'=>'136', '29366_2'=>'136', '29451_2'=>'136', '29452_2'=>'136', '29453_2'=>'136', '29454_2'=>'136', '29455_2'=>'136', '29472_2'=>'136', '29514_2'=>'136', '29533_2'=>'136',
                '29585_2'=>'136', '29704_2'=>'136', '29744_2'=>'136', '29791_2'=>'136', '29964_2'=>'136', '30024_2'=>'136', '30039_2'=>'136', '30041_2'=>'136', '30046_2'=>'136', '30139_2'=>'136', '30152_2'=>'136', '30200_2'=>'136', '30225_2'=>'136', '30345_2'=>'136', '30620_2'=>'136',
                '30621_2'=>'136', '30622_2'=>'136', '30858_2'=>'136', '30988_2'=>'136', '31087_2'=>'136', '31089_2'=>'136', '31092_2'=>'136', '31119_2'=>'136', '31164_2'=>'136', '31165_2'=>'136', '31168_2'=>'136', '31188_2'=>'136', '31257_2'=>'136', '31258_2'=>'136', '31259_2'=>'136',
                '31273_2'=>'136', '31288_2'=>'136', '31289_2'=>'136', '31290_2'=>'136', '31293_2'=>'136', '31295_2'=>'136', '31300_2'=>'136', '31315_2'=>'136', '31380_2'=>'136', '31405_2'=>'136', '31406_2'=>'136', '31434_2'=>'136', '31435_2'=>'136', '31436_2'=>'136', '31451_2'=>'136',
                '31467_2'=>'136', '31473_2'=>'136', '31475_2'=>'136', '31618_2'=>'136', '31619_2'=>'136', '31621_2'=>'136', '31622_2'=>'136', '31779_2'=>'136', '31795_2'=>'136', '31839_2'=>'136', '31872_2'=>'136', '31892_2'=>'136', '31929_2'=>'136', '31988_2'=>'136', '32110_2'=>'136',
                '32131_2'=>'136', '32219_2'=>'136', '32297_2'=>'136', '32357_2'=>'136', '32358_2'=>'136', '32416_2'=>'136', '32807_2'=>'136', '32890_2'=>'136', '33020_2'=>'136', '33300_2'=>'136', '33318_2'=>'136', '33670_2'=>'136', '33834_2'=>'136', '34239_2'=>'136', '34316_2'=>'136',
                '34830_2'=>'136', '34831_2'=>'136', '35216_2'=>'136', '35354_2'=>'136', '36198_2'=>'136', '36310_2'=>'136', '36672_2'=>'136', '36840_2'=>'136', '37197_2'=>'136', '37566_2'=>'136', '37778_2'=>'136', '38605_2'=>'136', '39646_2'=>'136', '39752_2'=>'136', '40137_2'=>'136',
                '40194_2'=>'136', '40283_2'=>'136', '40567_2'=>'136', '44108_2'=>'136', '44353_2'=>'136', '45144_2'=>'136', '46668_2'=>'136', '46681_2'=>'136', '47559_2'=>'136', '47803_2'=>'136', '48334_2'=>'136', '48958_2'=>'136', '50424_2'=>'136', '51039_2'=>'136', '51192_2'=>'136',
                '51373_2'=>'136', '51374_2'=>'136', '52111_2'=>'136', '52427_2'=>'136', '52900_2'=>'136', '52905_2'=>'136', '53023_2'=>'136', '53206_2'=>'136', '54359_2'=>'136', '54989_2'=>'136', '57016_2'=>'136', '57165_2'=>'136', '57967_2'=>'136', '58712_2'=>'136', '60166_2'=>'136',
                '60769_2'=>'136', '60893_2'=>'136', '61392_2'=>'136', '61746_2'=>'136', '62827_2'=>'136', '62888_2'=>'136', '63108_2'=>'136', '63332_2'=>'136', '63358_2'=>'136', '63564_2'=>'136', '63681_2'=>'136', '65604_2'=>'136', '65847_2'=>'136', '66050_2'=>'136', '66568_2'=>'136',
                '68833_2'=>'136', '69302_2'=>'136', '69415_2'=>'136', '73049_2'=>'136', '73652_2'=>'136', '73655_2'=>'136', '75547_2'=>'136', '77043_2'=>'136', '77794_2'=>'136', '78489_2'=>'136', '78965_2'=>'136', '80628_2'=>'136', '81149_2'=>'136', '82469_2'=>'136', '83305_2'=>'136',
                '84669_2'=>'136', '84700_2'=>'136', '84948_2'=>'136', '85809_2'=>'136', '85880_2'=>'136', '86188_2'=>'136', '92157_2'=>'136', '92705_2'=>'136', '97039_2'=>'136', '97041_2'=>'136', '97060_2'=>'136', '97061_2'=>'136', '97333_2'=>'136', '99411_2'=>'136', '100878_2'=>'136',
                '103235_2'=>'136', '104913_2'=>'136', '105450_2'=>'136', '105451_2'=>'136', '107230_2'=>'136', '109133_2'=>'136', '110010_2'=>'136', '110495_2'=>'136', '110939_2'=>'136', '112516_2'=>'136', '116206_2'=>'136', '116215_2'=>'136', '116491_2'=>'136', '118292_2'=>'136', '118716_2'=>'136',
                '119469_2'=>'136', '120952_2'=>'136', '123436_2'=>'136', '125272_2'=>'136', '125526_2'=>'136', '130507_2'=>'136', '133471_2'=>'136', '133472_2'=>'136', '134551_2'=>'136', '135547_2'=>'136', '140838_2'=>'136', '141749_2'=>'136', '144888_2'=>'136', '1156_2'=>'136', '1164_2'=>'136',
                '1352_2'=>'136', '2475_2'=>'136', '2509_2'=>'136', '2656_2'=>'136', '2710_2'=>'136', '2784_2'=>'136', '2806_2'=>'136', '2822_2'=>'136', '2832_2'=>'136', '2876_2'=>'136', '2911_2'=>'136', '2934_2'=>'136', '2999_2'=>'136', '3071_2'=>'136', '3177_2'=>'136', '3333_2'=>'136',
                '3391_2'=>'136', '3462_2'=>'136', '3680_2'=>'136', '4150_2'=>'136', '4313_2'=>'136', '4338_2'=>'136', '4394_2'=>'136', '4441_2'=>'136', '4642_2'=>'136', '5702_2'=>'136', '7949_2'=>'136', '12421_2'=>'136', '17337_2'=>'136', '19170_2'=>'136', '19409_2'=>'136', '20659_2'=>'136',
                '21318_2'=>'136', '21494_2'=>'136', '26154_2'=>'136', '26775_2'=>'136', '28437_2'=>'136', '30033_2'=>'136', '30733_2'=>'136', '32323_2'=>'136', '38849_2'=>'136', '40434_2'=>'136', '47751_2'=>'136', '50288_2'=>'136', '54136_2'=>'136', '58143_2'=>'136', '58795_2'=>'136',
                '60267_2'=>'136', '60513_2'=>'136', '62845_2'=>'136', '67086_2'=>'136', '67669_2'=>'136', '74625_2'=>'136', '75031_2'=>'136', '76797_2'=>'136', '77480_2'=>'136', '77975_2'=>'136', '80572_2'=>'136', '82374_2'=>'136', '92971_2'=>'136', '94569_2'=>'136', '95434_2'=>'136',
                '110292_2'=>'136', '111959_2'=>'136', '112121_2'=>'136', '114034_2'=>'136', '116465_2'=>'136', '118743_2'=>'136', '119491_2'=>'136', '119723_2'=>'136', '119893_2'=>'136', '122286_2'=>'136', '124091_2'=>'136', '124550_2'=>'136', '125322_2'=>'136', '125837_2'=>'136', '131926_2'=>'136',
                '132057_2'=>'136', '133160_2'=>'136', '133774_2'=>'136', '133954_2'=>'136', '136594_2'=>'136', '137079_2'=>'136', '137338_2'=>'136', '137761_2'=>'136', '138277_2'=>'136', '138918_2'=>'136', '140251_2'=>'136', '145284_2'=>'136', '145766_2'=>'136', '145852_2'=>'136', '53390_2'=>'136',
                '53437_2'=>'136', '53487_2'=>'136', '53490_2'=>'136', '53494_2'=>'136', '53497_2'=>'136', '53504_2'=>'136', '53571_2'=>'136', '53831_2'=>'136', '53857_2'=>'136', '54308_2'=>'136', '54551_2'=>'136', '54891_2'=>'136', '55014_2'=>'136', '55543_2'=>'136', '55967_2'=>'136',
                '58824_2'=>'136', '59998_2'=>'136', '64602_2'=>'136', '75251_2'=>'136', '77053_2'=>'136', '78055_2'=>'136', '78632_2'=>'136', '79055_2'=>'136', '83590_2'=>'136', '85185_2'=>'136', '87994_2'=>'136', '90732_2'=>'136', '91720_2'=>'136', '94474_2'=>'136', '101737_2'=>'136',
                '103655_2'=>'136', '105599_2'=>'136', '106563_2'=>'136', '107197_2'=>'136', '107207_2'=>'136', '111742_2'=>'136', '112674_2'=>'136', '119456_2'=>'136', '121781_2'=>'136', '128419_2'=>'136', '131671_2'=>'136', '131673_2'=>'136', '135780_2'=>'136', '138518_2'=>'136', '138673_2'=>'136',
                '141065_2'=>'136', '143395_2'=>'136', '145109_2'=>'136', '19240_2'=>'136', '121081_2'=>'136', '121103_2'=>'136', '121104_2'=>'136', '121117_2'=>'136', '121207_2'=>'136', '121216_2'=>'136', '121231_2'=>'136', '121290_2'=>'136', '121340_2'=>'136', '121386_2'=>'136', '121669_2'=>'136',
                '121695_2'=>'136', '121733_2'=>'136', '122222_2'=>'136', '122578_2'=>'136', '122615_2'=>'136', '122761_2'=>'136', '122813_2'=>'136', '122938_2'=>'136', '122949_2'=>'136', '123000_2'=>'136', '123014_2'=>'136', '123049_2'=>'136', '123120_2'=>'136', '123180_2'=>'136', '123286_2'=>'136',
                '123530_2'=>'136', '123552_2'=>'136', '123705_2'=>'136', '123784_2'=>'136', '123845_2'=>'136', '124314_2'=>'136', '124437_2'=>'136', '124570_2'=>'136', '124689_2'=>'136', '124873_2'=>'136', '125307_2'=>'136', '125523_2'=>'136', '125947_2'=>'136', '126095_2'=>'136', '126106_2'=>'136',
                '126293_2'=>'136', '126343_2'=>'136', '126356_2'=>'136', '126497_2'=>'136', '126565_2'=>'136', '126691_2'=>'136', '126744_2'=>'136', '127080_2'=>'136', '127722_2'=>'136', '128276_2'=>'136', '130378_2'=>'136', '131438_2'=>'136', '132134_2'=>'136', '132407_2'=>'136', '132419_2'=>'136',
                '132482_2'=>'136', '132867_2'=>'136', '133052_2'=>'136', '133061_2'=>'136', '133109_2'=>'136', '133884_2'=>'136', '134223_2'=>'136', '134797_2'=>'136', '135283_2'=>'136', '135518_2'=>'136', '135675_2'=>'136', '135745_2'=>'136', '135822_2'=>'136', '136902_2'=>'136', '137352_2'=>'136',
                '137830_2'=>'136', '137841_2'=>'136', '138508_2'=>'136', '138540_2'=>'136', '138627_2'=>'136', '138830_2'=>'136', '139019_2'=>'136', '139181_2'=>'136', '139876_2'=>'136', '140104_2'=>'136', '140106_2'=>'136', '140115_2'=>'136', '140181_2'=>'136', '142974_2'=>'136', '143254_2'=>'136',
                '143628_2'=>'136', '144243_2'=>'136', '144455_2'=>'136', '144887_2'=>'136', '145503_2'=>'136', '146192_2'=>'136', '146395_2'=>'136', '146810_2'=>'136', '146822_2'=>'136', '147299_2'=>'136', '34635_2'=>'136', '113451_2'=>'136', '107545_2'=>'136', '107938_2'=>'136', '108236_2'=>'136',
                '108551_2'=>'136', '108702_2'=>'136', '108705_2'=>'136', '108709_2'=>'136', '108822_2'=>'136', '109075_2'=>'136', '109846_2'=>'136', '109863_2'=>'136', '109884_2'=>'136', '109891_2'=>'136', '109921_2'=>'136', '109933_2'=>'136', '111609_2'=>'136', '111805_2'=>'136', '111828_2'=>'136',
                '112458_2'=>'136', '112459_2'=>'136', '112628_2'=>'136', '112798_2'=>'136', '113001_2'=>'136', '113041_2'=>'136', '114262_2'=>'136', '114264_2'=>'136', '114297_2'=>'136', '114491_2'=>'136', '114545_2'=>'136', '114964_2'=>'136', '115853_2'=>'136', '116004_2'=>'136', '120181_2'=>'136',
                '120667_2'=>'136', '122071_2'=>'136', '122166_2'=>'136', '124441_2'=>'136', '124456_2'=>'136', '127653_2'=>'136', '127851_2'=>'136', '127852_2'=>'136', '128317_2'=>'136', '129972_2'=>'136', '133873_2'=>'136', '134674_2'=>'136', '134935_2'=>'136', '136208_2'=>'136', '137137_2'=>'136',
                '139600_2'=>'136', '140318_2'=>'136', '142453_2'=>'136', '143814_2'=>'136', '144144_2'=>'136', '144152_2'=>'136', '144813_2'=>'136', '145950_2'=>'136');
        /*
         * $dist_vendor_mapping = array(
         * '624_2'=>'75','398_2'=>'75','453_2'=>'75','212_2'=>'75','860_2'=>'75','816_2'=>'75','777_2'=>'75','512_2'=>'75','241_2'=>'75',
         * '286_2'=>'75','855_2'=>'75','505_2'=>'75',
         * '461_2'=>'78','785_2'=>'78','844_2'=>'78','312_2'=>'78',
         * '744_2'=>'77','470_2'=>'77','584_2'=>'77','555_2'=>'77','700_2'=>'77','522_2'=>'77'
         * );
         *
         */
        $this->General->logData('/mnt/logs/active_vendor.txt', "product:$prodId, mobile:$mobile, additional params:" . json_encode($additional_param));

        $inactive = $this->getInactiveVendors();
        $vends = array();
        $prim_vend = null;
        $primary = null;

        if($api_flag){
            $invoiceObj = ClassRegistry::init('Slaves');
            $prodData = $invoiceObj->query("SELECT primary_vendor FROM partner_operator_status WHERE partner_acc_no = 'P000002' AND operator_id = $prodId");

            if( ! empty($prodData) &&  ! empty($prodData['0']['partner_operator_status']['primary_vendor']) &&  ! in_array($prodData['0']['partner_operator_status']['primary_vendor'], $inactive)){
                $prim_vend = $prodData['0']['partner_operator_status']['primary_vendor'];
            }
        }

        $exceptional_vendors = array_merge(array('29'), array_values(array_unique($dist_vendor_mapping)), array_values(array_unique($ret_vendor_mapping))); // b2c modem & local set ups

        // modular_distrib_id is a adhock patch for pay1 bihar
        $modular_distrib_id = isset($additional_param['dist_id']) ? $additional_param['dist_id'] : 0;

        if( ! ($api_flag) && isset($additional_param['retailer_created']) && $modular_distrib_id != '1883'){
            $diff = strtotime(date('Y-m-d')) - strtotime($additional_param['retailer_created']);

            $prim_vend_newRetailers = array('8'=>'29', '9'=>'29', '15'=>'29', '16'=>'29', '17'=>'29', '18'=>'29');
            if($diff / (60 * 60 * 24) <= 30){
                $prim_vend = (isset($prim_vend_newRetailers[$prodId]) &&  ! empty($prim_vend_newRetailers[$prodId])) ? $prim_vend_newRetailers[$prodId] : 29; // b2c vendor
            }
        }

        $exception = null;
        // print_r($inactive);
        //
        $primary_vendors = array();

        foreach($info['vendors'] as $vend){
            $circle_yes = $vend['circles_yes'];
            $circle_no = $vend['circles_no'];

            $imp_yes = implode(",", $circle_yes);
            $imp_no = implode(",", $circle_no);

            // exception wrt airtel infogem
            /*
             * if($vend['vendor_id'] == 27 && $prodId == 2 && !in_array($vend['vendor_id'],$inactive)){
             * $exception = $vend;
             * }
             */
            // if($vend['vendor_id'] == 68 && $prodId == 2 && $additional_param['amount'] <= 50){
            // continue;
            // }
            if( ! empty($imp_yes) &&  ! in_array($prod_d['area'], $circle_yes)){
                continue;
            }
            if( ! empty($imp_no) && in_array($prod_d['area'], $circle_no)){
                continue;
            }
            if( ! in_array($vend['vendor_id'], $inactive)){
                if($api_flag ||  ! in_array($vend['vendor_id'], $exceptional_vendors)){ // removing exceptional/priority modems from normal b2b transactions
                    $vends[] = $vend;
                }

                if( ! empty($prim_vend) && $prim_vend == $vend['vendor_id']){ // primary wrt api partner/ new retailers
                    $primary = $vend;
                    $this->General->logData('/mnt/logs/active_vendor.txt', "product:$prodId, mobile:$mobile, additional params:" . json_encode($additional_param) . " ::api vendor case ::primary: " . $vend['vendor_id']);
                }
                else if(empty($prim_vend)){
                    $val = isset($additional_param['dist_id']) ? $additional_param['dist_id'] : 0;
                    $retailer_id_val = isset($additional_param['retailer_id']) ? $additional_param['retailer_id'] : 0;

                    if( ! empty($retailer_id_val) && isset($ret_vendor_mapping[$retailer_id_val . "_" . $prodId]) && $ret_vendor_mapping[$retailer_id_val . "_" . $prodId] == $vend['vendor_id'] &&  ! in_array($vend['vendor_id'], $non_primary)){
                        $this->General->logData('/mnt/logs/active_vendor.txt', "product:$prodId, mobile:$mobile, additional params:" . json_encode($additional_param) . " ::retid_area case ::primary: " . $vend['vendor_id']);
                        $primary_vendors['local_vendor'] = $vend;
                    }

                    if( ! empty($val) && isset($dist_vendor_mapping[$val . "_" . $prodId]) && $dist_vendor_mapping[$val . "_" . $prodId] == $vend['vendor_id'] &&  ! in_array($vend['vendor_id'], $non_primary)){
                        $this->General->logData('/mnt/logs/active_vendor.txt', "product:$prodId, mobile:$mobile, additional params:" . json_encode($additional_param) . " ::distid_area case ::primary: " . $vend['vendor_id']);
                        $primary_vendors['local_vendor'] = $vend;
                    }
                    if( ! isset($primary_vendors['circle']) &&  ! empty($vend['circle']) && $prod_d['area'] == $vend['circle'] &&  ! in_array($vend['vendor_id'], $non_primary)){ // circle wise primary vendor logic
                        $this->General->logData('/mnt/logs/active_vendor.txt', "product:$prodId, mobile:$mobile, additional params:" . json_encode($additional_param) . " ::circle_area primary case ::primary: " . $vend['vendor_id']);
                        $primary_vendors['circle'] = $vend;
                    }
                    if( ! empty($additional_param) && isset($additional_param['amount'])){
                        if($this->check_amount_priority(intval($prodId), intval($vend['vendor_id']), intval($additional_param['amount']))){
                            $this->General->logData('/mnt/logs/active_vendor.txt', "product:$prodId, mobile:$mobile, additional params:" . json_encode($additional_param) . " ::cp airtel lower amount case ::primary: " . $vend['vendor_id']);
                            $primary_vendors['cp_airtel'] = $vend;
                        }
                        if(in_array($additional_param['amount'], array(10, 20, 30)) && $prodId == 2 && strtoupper($prod_d['area']) == 'MU' && $vend['vendor_id'] == '8'){
                            $primary_vendors['cp_airtel_denom'] = $vend;
                        }
                    }
                }
            }
        }

        if(empty($primary)){
            if(isset($primary_vendors['cp_airtel_denom'])) $primary = $primary_vendors['cp_airtel_denom'];
            else if(isset($primary_vendors['local_vendor'])) $primary = $primary_vendors['local_vendor'];
            else if(isset($primary_vendors['cp_airtel'])) $primary = $primary_vendors['cp_airtel'];
            else if(isset($primary_vendors['circle'])) $primary = $primary_vendors['circle'];
        }

        $this->General->logData('/mnt/logs/active_vendor.txt', "product:$prodId, mobile:$mobile, additional params:" . json_encode($additional_param) . " ::final primary: " . $primary['vendor_id']);

        if( ! empty($primary)){
            array_unshift($vends, $primary);
        }

        if( ! empty($exception)){
            $vends[] = $exception;
        }

        $info['vendors'] = $vends;

        if(empty($primary) && $primary_flag){
            $primary = $this->findOptimalVendor($vends, $prodId, $info['automate']);
        }
        else if($primary_flag){
            $this->findOptimalVendor(array($primary), $prodId, $info['automate']);
        }

        if(empty($info['vendors'])){
            return array('status'=>'failure', 'code'=>'', 'description'=>'', 'name'=>$info['name'], 'info'=>$info);
        }
        else{
            return array('status'=>'success', 'code'=>'', 'info'=>$info, 'primary'=>$primary);
        }
    }

    /**
     * Generate and arrange the vendors mapping in specified structured based on input
     *
     * @return type array
     */
    function generate_local_vendor_map(){
        $mapArr = $this->getLocalVendorsMap();
        $returnArr = array();
        foreach($mapArr as $vendorId=>$opr_dist_Arr){
            foreach($opr_dist_Arr as $oprId=>$dist_Arr){
                foreach($dist_Arr as $dist_Id){
                    $returnArr[$dist_Id . "_" . $oprId] = $vendorId;
                }
            }
        }
        return $returnArr;
    }

    /**
     * Get local vendors mapping from memcache
     *
     * @return type array
     */
    function getLocalVendorsMap(){
        $vendors = $this->getMemcache('local_Vendors_map');
        if($vendors === false){
            $vendors = $this->setLocalVendorsMap();
        }
        return $vendors;
    }

    /**
     * SET local vendors mapping in memcache from query result
     *
     * @return type array
     */
    function setLocalVendorsMap(){
        // $slaveObj = ClassRegistry::init('Slaves');
        $slaveObj = ClassRegistry::init('User');
        $vendors = $slaveObj->query("SELECT * FROM local_vendor_mapping where is_deleted = 1 ");
        $vendordata = array();
        foreach($vendors as $localvendor_key=>$localvendor_val){
            $vendor_id = isset($localvendor_val['local_vendor_mapping']['vendor_id']) ? $localvendor_val['local_vendor_mapping']['vendor_id'] : "";
            $operator_id = isset($localvendor_val['local_vendor_mapping']['operator_id']) ? $localvendor_val['local_vendor_mapping']['operator_id'] : "";
            $distributor_id = isset($localvendor_val['local_vendor_mapping']['distributor_id']) ? $localvendor_val['local_vendor_mapping']['distributor_id'] : "";
            $vendordata[$vendor_id][$operator_id] = explode(",", $distributor_id);
        }
        $this->setMemcache('local_Vendors_map', $vendordata);
        return $vendordata;
    }

    /**
     *
     * Check if there is any amount based priority exist for particular operator on specific vendor
     *
     * @param type $productId
     * @param type $vendorId
     * @param type $Amount
     * @return type boolean
     */
    function check_amount_priority($productId, $vendorId, $Amount){
        $amt_priority = $this->getAmountPriorityMap();

        foreach($amt_priority as $pro_arr){

            if($pro_arr['product_id'] == $productId && $pro_arr['vendor_id'] == $vendorId){

                if( ! empty($pro_arr['list_amount'])){

                    return in_array($Amount, explode(",", $pro_arr['list_amount']));
                }
                elseif( ! empty($pro_arr['min_amount']) &&  ! empty($pro_arr['min_amount']) && $pro_arr['min_amount'] <= $Amount && $pro_arr['max_amount'] >= $Amount){

                    return TRUE;
                }
                else{

                    return FALSE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Get amount priority map from memcache
     *
     * @return type array
     */
    function getAmountPriorityMap(){
        $amtPriorityMap = $this->getMemcache('amount_priority_map');
        if($amtPriorityMap === false){
            $amtPriorityMap = $this->setAmountPriorityMap();
        }
        return $amtPriorityMap;
    }

    /**
     * set amount priority map to memcache from query result
     *
     * @return type array
     */
    function setAmountPriorityMap(){
        // $slaveObj = ClassRegistry::init('Slaves');
        $slaveObj = ClassRegistry::init('User');
        $amtPriorityMap = $slaveObj->query("SELECT * FROM amount_priority_mapping where is_deleted = 1 ");
        $amtPriorityMapping = array();
        foreach($amtPriorityMap as $amtPriority_key=>$amtPriority_val){
            $amtPriorityMapping[] = $amtPriority_val['amount_priority_mapping'];
        }
        $this->setMemcache('amount_priority_map', $amtPriorityMapping, 24 * 60 * 60);
        return $amtPriorityMapping;
    }

    function checkPossibility($prodId, $mobileNo, $amount, $power, $param = null, $special = null, $api_flag = null){
        $sms = null;
        if( ! empty($power)) return $sms;
        $invoiceObj = ClassRegistry::init('Invoice');

        if($amount == ''){
            $prodData = $invoiceObj->query("SELECT price FROM products_info WHERE product_id = $prodId");
            if(empty($prodData) && count($prodData) > 0){
                $amount = $prodData['0']['products_info']['price'];
            }
            else{
                $amount = '';
            }
        }
        if(empty($param)) $number = $mobileNo;
        else $number = $param;

        $mins = TIME_DURATION;

        if(empty($prodId) || empty($number) || empty($amount)) return 'Invalid recharge format';

        if( ! $this->lockTransactionDuplicates($prodId, $number, $amount, $api_flag)){
            if($param == null){
                $msg = "*$prodId*$mobileNo*$amount";
                $sms = "hold: Repeat on $mobileNo of Rs$amount within " . ($mins / 60) . " hours";
            }
            else{
                $msg = "*$prodId*$param*$mobileNo*$amount";
                $sms = "hold: Repeat on $param of Rs$amount within " . ($mins / 60) . " hours";
            }

            if( ! empty($special) && $special == 1) $msg = $msg . "#";

            $sender = $_SESSION['Auth']['mobile'];
            $this->addRepeatTransaction($msg, $sender, 2);
            $mail_body = "Request is on hold, Retailer $sender has done duplicate transaction within $mins mins";
            $mail_body .= "<br/>Customer Mobile: $mobileNo/$param, Amount: $amount";
            $this->General->sendMails("Pay1: Same Transaction within $mins mins", $mail_body, array('notifications@pay1.in'));

            $added = $invoiceObj->query("SELECT id FROM repeated_transactions WHERE msg = '$msg' AND sender='$sender' AND type=2 ORDER BY id desc LIMIT 1");
            if( ! empty($added)){
                $id = $added['0']['repeated_transactions']['id'];
                $id = "1" . sprintf('%04d', $id);
                $sms .= "\nTo continue send: #$id via misscall service or SMS to 09004-350-350";
                $sms .= "\nIgnore if don't want to continue";
            }
        }

        return $sms;
    }

    function addRepeatTransaction($msg, $sender, $type, $added_by = null){
        $invoiceObj = ClassRegistry::init('Invoice');

        if($added_by == null){
            $invoiceObj->query("INSERT INTO repeated_transactions (sender,msg,type,timestamp) VALUES ('$sender','" . addslashes($msg) . "',$type,'" . date('Y-m-d H:i:s') . "')");
        }
        else{
            $invoiceObj->query("INSERT INTO repeated_transactions (sender,msg,send_flag,type,added_by,processed_by,timestamp) VALUES ('$sender','" . addslashes($msg) . "',1,$type,$added_by,$added_by,'" . date('Y-m-d H:i:s') . "')");
        }
    }

    /*
     * function getReceiptNumber($id){
     * $recObj = ClassRegistry::init('Receipt');
     * $data = $recObj->findById($id);
     * if($data['Receipt']['receipt_type'] == RECEIPT_INVOICE){
     * $suffix1 = "INV";
     * }
     * else if($data['Receipt']['receipt_type'] == RECEIPT_TOPUP){
     * $suffix1 = "TP";
     * }
     *
     * if($data['Receipt']['group_id'] == MASTER_DISTRIBUTOR){
     * $suffix2 = "ST";
     * }
     * else if($data['Receipt']['group_id'] == DISTRIBUTOR){
     * $parent_shop = $this->getShopDataById($data['Receipt']['shop_from_id'],MASTER_DISTRIBUTOR);
     * $parent_comp = explode(" ",$parent_shop['company']);
     * }
     * else if($data['Receipt']['group_id'] == RETAILER){
     * $parent_shop = $this->getShopDataById($data['Receipt']['shop_from_id'],DISTRIBUTOR);
     * $parent_comp = explode(" ",$parent_shop['company']);
     * }
     *
     * $ret = $recObj->query("SELECT count(*) as counts FROM receipts WHERE shop_from_id = ".$data['Receipt']['shop_from_id']." and group_id = " . $data['Receipt']['group_id'] . " and id < $id");
     * if(isset($parent_comp)){
     * $suffix2 = "";
     * foreach($parent_comp as $str){
     * $suffix2 .= substr($str,0,1);
     * }
     * $suffix2 = strtoupper($suffix2);
     * }
     * $number = $ret['0']['0']['counts'] + 1;
     * return "R/". $suffix1 . "/". $suffix2 . "/" . sprintf('%03d', $number);
     * }
     */
    function getShopData($user_id, $group_id){
        if($group_id == MASTER_DISTRIBUTOR){
            $userObj = ClassRegistry::init('MasterDistributor');
            $bal = $userObj->find('first', array('conditions'=>array('user_id'=>$user_id)));
            return $bal['MasterDistributor'];
        }
        else if($group_id == SUPER_DISTRIBUTOR){
            $userObj = ClassRegistry::init('SuperDistributor');
            $bal = $userObj->find('first', array('conditions'=>array('user_id'=>$user_id)));

            $imp_data = $this->getUserLabelData($user_id);
            foreach ($bal as $key => $super_distributor) {
                $bal[$key]['company'] = $imp_data[$super_distributor['SuperDistributor']['user_id']]['imp']['shop_est_name'];
                $bal[$key]['shopname'] = $imp_data[$super_distributor['SuperDistributor']['user_id']]['imp']['shop_est_name'];
            }

            return $bal['SuperDistributor'];
        }
        else if($group_id == DISTRIBUTOR){
            $userObj = ClassRegistry::init('Distributor');
            $bal = $userObj->find('first', array('conditions'=>array('user_id'=>$user_id)));
            return $bal['Distributor'];
        }
        else if($group_id == RETAILER){
            $userObj = ClassRegistry::init('Slaves');
            $retailers = $userObj->query("select *
					from retailers as r
					left join unverified_retailers ur on ur.retailer_id = r.id
					where r.user_id = " . $user_id);
            foreach($retailers[0]['ur'] as $key=>$row){
                if( ! in_array($key, array('id'))) $retailers[0]['r'][$key] = $retailers[0]['ur'][$key];
            }

            /** IMP DATA ADDED : START - Rohit **/
            $temp = $this->getUserLabelData($user_id,2,0);
            $imp_data = $temp[$user_id];
            $retailer_imp_label_map = array(
                'pan_number' => 'pan_no',
                'shopname' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id',
                'shop_structure' => 'shop_ownership',
                'shop_type' => 'business_nature'
            );
            foreach ($retailers[0]['r'] as $retailer_label_key => $value) {
                $retailer_label_key_mapped = ( array_key_exists($retailer_label_key,$retailer_imp_label_map) ) ? $retailer_imp_label_map[$retailer_label_key] : $retailer_label_key;
                if( array_key_exists($retailer_label_key_mapped,$imp_data['imp']) ){
                    $retailers[0]['r'][$retailer_label_key] = $imp_data['imp'][$retailer_label_key_mapped];
                }
            }
            /** IMP DATA ADDED : END**/

            return $retailers[0]['r'];
        }
        else if($group_id == RELATIONSHIP_MANAGER){
            $userObj = ClassRegistry::init('Rm');
            $bal = $userObj->find('first', array('conditions'=>array('Rm.user_id'=>$user_id)));
            return $bal['Rm'];
        }
    }

    function payment_gateway($amount, $via = 'web'){
        $userObj = ClassRegistry::init('Retailer');
        $data = $userObj->query("SELECT * FROM pg_checks WHERE distributor_id = '" . $_SESSION['Auth']['parent_id'] . "'");

        $topup = $amount - $data['0']['pg_checks']['service_charge'];

        $balance = $this->getBalance($_SESSION['Auth']['User']['id']);

        if(empty($data) || $data['0']['pg_checks']['active_flag'] == 0){
            return array('status'=>'failure', 'code'=>'43', 'description'=>$this->errors(43));
        }
        else if($balance == 0 && $topup < $data['0']['pg_checks']['min_amount']){
            return array('status'=>'failure', 'code'=>'33', 'description'=>'Minimum amount allowed for first time topup is Rs. ' . $data['0']['pg_checks']['min_amount']);
        }
        else if($balance > 0 && $topup < $data['0']['pg_checks']['second_top_up']){
            return array('status'=>'failure', 'code'=>'33', 'description'=>'Minimum amount allowed for topup is Rs. ' . $data['0']['pg_checks']['second_top_up']);
        }
        else if($topup > $data['0']['pg_checks']['max_amount']){
            return array('status'=>'failure', 'code'=>'34', 'description'=>'Maximum amount allowed for topup is Rs. ' . $data['0']['pg_checks']['max_amount']);
        }


        /*
         * $retailer = $userObj->query("SELECT rental_flag from retailers where id = '".$_SESSION['Auth']['id']."'");
         * $rental_flag = $retailer['0']['retailers']['rental_flag'];
         * if($rental_flag == 2){
         * $amount += $this->General->findVar("OTA_Fee");
         * }
         */
        // make entry in pg_india table

        $distributor = $userObj->query("SELECT system_used from distributors where id = '1'");
        if($distributor[0]['distributors']['system_used'] == 1){
            $recId = $this->shopTransactionUpdate(DIST_SLMN_BALANCE_TRANSFER, $topup, 1, 5, 8, null, 5);
            $recId1 = $this->shopTransactionUpdate(SLMN_RETL_BALANCE_TRANSFER, $topup, 5, $_SESSION['Auth']['id'], 8, null, 5);
        }
        else if($distributor[0]['distributors']['system_used'] == 0){
            $recId = $this->shopTransactionUpdate(DIST_RETL_BALANCE_TRANSFER, $topup, 1, $_SESSION['Auth']['id'], 8, null, 5);
        }

        $this->setMemcache("pg_$recId", $via, 30 * 60);

        $sm = $userObj->query("SELECT salesmen.id,salesmen.name from salesmen inner join distributors ON (distributors.id = salesmen.dist_id) inner join users ON (users.id = distributors.user_id) where distributors.id = '" . $_SESSION['Auth']['parent_id'] . "' AND users.mobile = salesmen.mobile");
        if($distributor[0]['distributors']['system_used'] == 0){
            $userObj->query("INSERT INTO salesman_transactions (shop_tran_id,salesman,payment_mode,payment_type,details,collection_date,created) VALUES ('" . $recId . "','" . $sm['0']['salesmen']['id'] . "','" . MODE_PG . "','" . TYPE_TOPUP . "','','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "')");
        }

        $client_ip = $this->General->getClientIP();

        $userObj->query("INSERT INTO pg_payuIndia (status,shop_transaction_id,amount,addedon,server_ip) VALUES ('pending','$recId','$amount','" . date('Y-m-d H:i:s') . "','" . $client_ip. "')");

        // get shop_transid & pass it to pg_payment function .. this will return payment form.. Which will be shown to user

        $shopname = (empty($_SESSION['Auth']['shopname'])) ? $_SESSION['Auth']['mobile'] : preg_replace("/[^a-zA-Z0-9\s]/", "", $_SESSION['Auth']['shopname']);
        $data = array('transaction_id'=>$recId, 'amount'=>$amount, 'category'=>'TOPUP_RETAILER', 'name'=>$shopname, 'email'=>$_SESSION['Auth']['email']);

        $pg_form_data = $this->generatePayment_form_retailer($data);
        return array('status'=>'success', 'code'=>'0', 'description'=>$pg_form_data);
    }

    function generatePayment_form_retailer($data){
        $data2process = $this->payu_setdata2process($data);
        $fdata = array('data2process'=>$data2process, 'salt'=>PAYU_SALT);
        $hash = $this->generate_payu_hash($fdata);

        $data2process['phone'] = $_SESSION['Auth']['mobile'];
        $data2process['surl'] = PAYU_SUCCESS_URL;
        $data2process['furl'] = PAYU_FAILURE_URL;
        $data2process['curl'] = PAYU_FAILURE_URL;
        $data2process['touturl'] = PAYU_FAILURE_URL;
        $data2process['hash'] = $hash;
        $data2process['user_credentials'] = PAYU_KEY . ":" . $_SESSION['Auth']['user_id'];

        $pay_page_content = $this->generate_pg_payu_form_content($data2process);
        return $pay_page_content;
    }

    function payu_setdata2process($data){
        $data2process['key'] = PAYU_KEY;
        // transaction related detail
        $data2process['txnid'] = $data['transaction_id'];
        $data2process['amount'] = $data['amount'];
        $data2process['productinfo'] = $data['category'];
        // customer detail

        $data2process['firstname'] = $data['name'];
        $data2process['email'] = (empty($data['email'])) ? "" : $data['email'];
        return $data2process;
    }

    function generate_payu_hash($data){
        $str = implode("|", array_values($data['data2process'])) . "|||||||||||" . $data['salt'];
        // echo $str;exit();
        return hash("sha512", $str);
    }

    function generate_pg_payu_form_content($data){
        $mem_data = $this->getMemcache("pg_" . $data['txnid']);
        if($mem_data == 'web'){
            $postdata = http_build_query($data);
            $options = array('http'=>array('method'=>'POST', 'header'=>'Content-type: application/x-www-form-urlencoded', 'content'=>$postdata));

            $context = stream_context_create($options);

            $result = file_get_contents(PANEL_PAYU_SEAMLESS_URL, false, $context);

            return $result;
        }
        else{
            $content = "<html>";
            $content .= '<script> function submitform(){document.getElementById("pg_payu_form").submit();}</script>';
            $content .= "<body onload=submitform()>";
            $content .= "<form id='pg_payu_form' action='" . PAYU_URL . "' id='pg_payu_form' method='post'>";
            foreach($data as $key=>$value){
                $content .= "<input type='hidden' name='$key' value='$value'>";
            }
            $content .= "</form>";
            $content .= "</body></html>";
            return $content;
        }
    }

    function update_pg_payu($data, $online = true){
        if($online &&  ! $this->check_payu_hash($data)){
            $t_data = $data;
            $t_data['ip'] = $this->General->getClientIP();

            $this->General->sendMails("b2b: wrong hash for this transaction please check it", json_encode($t_data), array('ashish@pay1.in'), 'mail');
            return array('status'=>'failure', 'code'=>'', 'description'=>"Hash not matched");
        }

        $data['cardnum'] = isset($data['card_no']) ? $data['card_no'] : (isset($data['cardnum']) ? $data['cardnum'] : "");
        $data['error'] = isset($data['error_code']) ? $data['error_code'] : (isset($data['error']) ? $data['error'] : "");
        $data['shop_transaction_id'] = $data['txnid'];

        unset($data['card_no']);
        unset($data['error_code']);
        unset($data['txnid']);

        $userObj = ClassRegistry::init('Retailer');
        $colqry = "SELECT COLUMN_NAME  FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='shops' AND `TABLE_NAME`='pg_payuIndia'";
        $col_result = $userObj->query($colqry);
        $col_data = array();

        foreach($col_result as $k=>$v){
            $col_data[$v['COLUMNS']['COLUMN_NAME']] = isset($data[$v['COLUMNS']['COLUMN_NAME']]) ? addslashes($data[$v['COLUMNS']['COLUMN_NAME']]) : "";
        }

        $payuQry = "select pg_payuIndia.status,shop_transactions.* from pg_payuIndia left join shop_transactions ON (shop_transactions.id = pg_payuIndia.shop_transaction_id) WHERE shop_transaction_id='" . $data['shop_transaction_id'] . "' AND status = 'pending'";
        $payu_result = $userObj->query($payuQry);

        if(empty($payu_result)){
            $this->General->sendMails("B2B:: Payu data already exists", json_encode($data), array('ashish@pay1.in'), 'mail');
        }
        else{
            unset($col_data['id']);
            unset($col_data['server_ip']);
            $this->update_payu_Transaction($col_data['shop_transaction_id'], $col_data);

            if($col_data['status'] === "success"){
                if($payu_result['0']['shop_transactions']['type'] == DIST_RETL_BALANCE_TRANSFER){
                    $result = $this->process_retailer_transfer_after_pg($data);
                }
                return $result;
            }
            else if($col_data['status'] === "failure"){
                if($payu_result['0']['shop_transactions']['type'] == DIST_RETL_BALANCE_TRANSFER){
                    $userObj->query("UPDATE shop_transactions SET confirm_flag = 1 WHERE id = " . $col_data['shop_transaction_id']);
                }
                return array('status'=>'failure', 'code'=>'', 'description'=>json_encode($col_data));
            }
        }
    }

    function process_retailer_transfer_after_pg($data){
        try{
            $userObj = ClassRegistry::init('Retailer');
            $txnid = $data['shop_transaction_id'];
            $amount = $data['amount'];

            $txndata = $userObj->query("SELECT shop_transactions.amount,shop_transactions.source_id,shop_transactions.target_id,retailers.shopname,retailers.mobile FROM shop_transactions left join retailers ON (retailers.id = shop_transactions.target_id) WHERE shop_transactions.id = $txnid AND shop_transactions.confirm_flag != 1");

            if( ! empty($txndata)){




                $userObj->query("UPDATE shop_transactions SET note = '" . $data['mihpayid'] . "' WHERE id = $txnid");

                $txn_amount = $txndata['0']['shop_transactions']['amount'];
                $dist_id = $txndata['0']['shop_transactions']['source_id'];
                $ret_id = $txndata['0']['shop_transactions']['target_id'];
                $ret_mobile = $txndata['0']['retailers']['mobile'];

                $distributorInfo = $this->getShopDataById($dist_id, DISTRIBUTOR);
                $retailerInfo = $this->getShopDataById($ret_id, RETAILER);

                $bal = $this->shopBalanceUpdate($txn_amount, 'subtract', $distributorInfo['user_id'], DISTRIBUTOR);
                $bal1 = $this->shopBalanceUpdate($txn_amount, 'add', $retailerInfo['user_id'], RETAILER);

                /* Added as changes for DB optimization */
                $userObj->query("UPDATE shop_transactions SET note = '" . $data['mihpayid'] . "',source_opening='" . ($bal + $txn_amount) . "',source_closing='$bal',target_opening='" . ($bal1 - $txn_amount) . "',target_closing='$bal1' WHERE id = $txnid");
                // $this->addOpeningClosing($dist_id,DISTRIBUTOR,$txnid,$bal+$txn_amount,$bal);
                // $this->addOpeningClosing($ret_id,RETAILER,$txnid,$bal1-$txn_amount,$bal1);

                if( ! empty($txndata['0']['retailers']['shopname'])){

                    /** IMP DATA ADDED : START**/
                    $temp = $this->getUserLabelData($ret_mobile,2,1);
                    $imp_data = $temp[$ret_mobile];
                    $txndata['0']['retailers']['shopname'] = $imp_data['imp']['shop_est_name'];
                    /** IMP DATA ADDED : END**/

                    $shop_name = substr($txndata['0']['retailers']['shopname'], 0, 15);
                }
                else
                    $shop_name = $txndata['0']['retailers']['mobile'];

                /*
                 * $distributors = $userObj->query("select u.mobile
                 * from users u
                 * left join distributors d on d.user_id = u.id
                 * where d.id = ".$dist_id);
                 * if($distributors){
                 * $message_distributor = "Your account is debited with Rs. ".$txn_amount." to retailer: ".$shop_name;
                 * $this->General->sendMessage($distributors['0']['u']['mobile'], $message_distributor, "notify", null, DISTRIBUTOR);
                 * }
                 */

                $mail_subject = "Amount transferred to retailer via Payment Gateway";
                $mail_body = "Transferred Rs. $txn_amount to Retailer: " . $shop_name;

                $mail_body .= "<br/>Payment done by Retailer: " . $amount;
                $mail_body .= "<br/>PayuID: " . $data['mihpayid'];

                $msg = "Dear $shop_name,\nYour account is successfully credited with Rs." . $txn_amount . " Via Credit Card/Debit Card/Net Banking. Your reference id is $txnid\nYour current balance is Rs.$bal1";
                $this->General->sendMessage($ret_mobile, $msg, 'shops');
                $this->General->sendMails($mail_subject, $mail_body, array("tadka@pay1.in", "limits@pay1.in"), 'mail');
                return array('status'=>'success', 'code'=>'', 'description'=>$msg);
            }
        }
        catch(Exception $e){
            return array('status'=>'failure', 'code'=>$e->getCode(), 'description'=>$e->getMessage());
        }
    }

    function update_payu_Transaction($trans_id, $data = array()){
        $tablename = "pg_payuIndia";
        $userObj = ClassRegistry::init('Retailer');
        if(count($data) > 0){
            $update_data = "";
            foreach($data as $col=>$val){
                if(empty($val)) continue;
                $update_data .= (strlen($update_data) > 0) ? ", `$col`='$val'" : "`$col`='$val'";
            }
            $userObj->query("UPDATE $tablename SET $update_data WHERE shop_transaction_id='$trans_id'");
        }
    }

    function check_payu_hash($data){
        $key = PAYU_KEY;
        $salt = PAYU_SALT;
        $status = $this->General->set_defaultBlank($data, 'status');
        $email = $this->General->set_defaultBlank($data, 'email');
        $firstname = $this->General->set_defaultBlank($data, 'firstname');
        $productinfo = $this->General->set_defaultBlank($data, 'productinfo');
        $amount = $this->General->set_defaultBlank($data, 'amount');
        $txnid = $this->General->set_defaultBlank($data, 'txnid');
        $dstr = $salt . "|" . $status . "|||||||||||" . $email . "|" . $firstname . "|" . $productinfo . "|" . $amount . "|" . $txnid . "|" . $key;
        // $dstr = $salt."|".$data['status']."|||||||||||".$data['email']."|".$data['firstname']."|".$data['productinfo']."|".$data['amount']."|".$data['txnid']."|".$key;
        $internal_hash = hash("sha512", $dstr);
        if($internal_hash == $data['hash']){
            return true;
        }
        return false;
    }

    function getLastTransactions($date, $page = 1, $service = null, $date2 = null, $itemsPerPage = 0, $retailerId = null, $operatorId = 0, $is_page_wise = 1){
        if(empty($date)){
            $date = date('Y-m-d');
        }
        else
            $date = date('Y-m-d', strtotime($date));

        if(empty($date2)){
            $date2 = $date;
        }
        else
            $date2 = date('Y-m-d', strtotime($date2));

        if($date2 > date('Y-m-d', strtotime($date . ' + 30 days'))){
            $date2 = date('Y-m-d', strtotime($date . ' + 30 days'));
        }
        $next_day = date('Y-m-d', strtotime($date . ' + 1 day'));
        if($next_day > date('Y-m-d')){
            $next_day = '';
        }
        $prev_day = date('Y-m-d', strtotime($date . ' - 1 day'));

        if($itemsPerPage == 0){
            $itemsPerPage = PAGE_LIMIT;
        }

        $limit = ($page - 1) * $itemsPerPage > 0 ? ($page - 1) * $itemsPerPage : 0;

        $retailObj = ClassRegistry::init('Slaves');
        if(is_null($retailerId)){
            $retailer = $_SESSION['Auth']['id'];
        }
        else{
            $retailer = $retailerId;
        }
        if(empty($operatorId)){
            $operatorIdQry = "";
        }
        else{
            $operatorIdQry = "AND vendors_activations.product_id = " . $operatorId;
        }
        $qryLimitPart = "";
        if($service == null){
            if($is_page_wise != 0){
                $qryLimitPart = " LIMIT $limit," . $itemsPerPage;
            }

            $ret = $retailObj->query("SELECT products.name,services.name,vendors_activations.txn_id as ref_code,vendors_activations.id,vendors_activations.vendor_id,vendors_activations.param,
                                        vendors_activations.mobile,vendors_activations.amount,vendors_activations.status, trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id,sum(complaints.resolve_flag) as resolve_flag,
                                        bbps_txnid_mapping.id as bbps_txnid,bbps_txnid_mapping.bill_data
					FROM vendors_activations
                                        inner join products on (products.id = vendors_activations.product_id)
					inner join services on (products.service_id = services.id)
					left join complaints on (complaints.vendor_activation_id = vendors_activations.id)
                                        left join bbps_txnid_mapping on (bbps_txnid_mapping.payment_txnid = vendors_activations.txn_id)
                                        where vendors_activations.retailer_id = $retailer AND vendors_activations.date >= '$date' AND vendors_activations.date <= '$date2' $operatorIdQry
					group by vendors_activations.id
					order by vendors_activations.id desc " . $qryLimitPart);
            $ret_cnt_res = $retailObj->query("SELECT count(*) as cnt FROM products,services,vendors_activations where products.service_id = services.id AND products.id = vendors_activations.product_id AND vendors_activations.retailer_id = $retailer AND vendors_activations.date>='$date' AND vendors_activations.date<='$date2' $operatorIdQry ");
            $ret_count = $ret_cnt_res[0][0]['cnt'];
        }
        else{
            $bbps_data = ($service == 6)?',bbps_txnid_mapping.id as bbps_txnid,bbps_txnid_mapping.bill_data ':'';
            $join = ($service == 6)?'left join bbps_txnid_mapping on (bbps_txnid_mapping.payment_txnid = vendors_activations.txn_id) ':'';
            $ret = $retailObj->query("SELECT shop_transactions.source_opening,shop_transactions.source_closing,products.name,services.name,vendors_activations.txn_id as ref_code,
                                        vendors_activations.id,vendors_activations.vendor_id,vendors_activations.param,vendors_activations.mobile,vendors_activations.amount,
					vendors_activations.status,vendors_activations.cause,vendors_activations.code, trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id,sum(complaints.resolve_flag) as resolve_flag
                                        $bbps_data
					FROM products
					inner join services on (products.service_id = services.id)
					inner join vendors_activations on (products.id = vendors_activations.product_id)
					inner join shop_transactions on (vendors_activations.shop_transaction_id = shop_transactions.id)
					left join complaints on (complaints.vendor_activation_id = vendors_activations.id)
                                        $join
					where vendors_activations.retailer_id = $retailer AND products.service_id = $service AND vendors_activations.date>='$date' AND vendors_activations.date<='$date2'  $operatorIdQry
					group by vendors_activations.id
					order by vendors_activations.id desc " . $qryLimitPart);

            Configure::load('billers');
            $billers = Configure::read('billers');

            $i = 0;
            foreach($ret as $r){
                $ret[$i]['vendors_activations']['logo_url'] = $billers[$r['vendors_activations']['product_id']]['logo_url'];
                $ret[$i]['opening_closing']['opening'] = $r['shop_transactions']['source_opening'];
                $ret[$i]['opening_closing']['closing'] = $r['shop_transactions']['source_closing'];
                $ret[$i][0]['bbps_flag'] = (isset($r['bbps_txnid_mapping']['bbps_txnid']) && !empty($r['bbps_txnid_mapping']['bbps_txnid']))?1:0;
                $i ++ ;
            }
            $ret_cnt_res = $retailObj->query("SELECT count(*) as cnt FROM products,services,vendors_activations where vendors_activations.retailer_id = $retailer AND products.service_id = services.id AND products.service_id = $service AND products.id = vendors_activations.product_id AND vendors_activations.date>='$date' AND vendors_activations.date<='$date2' $operatorIdQry");
            $ret_count = $ret_cnt_res[0][0]['cnt'];
        }
        $more = 0;
        if(count($ret) == $itemsPerPage){
            $more = 1;
        }

        return array('ret'=>$ret, 'today'=>$date, 'prev'=>$prev_day, 'next'=>$next_day, 'more'=>$more, 'total_count'=>$ret_count);
    }

    function lastten($service){
        $retailObj = ClassRegistry::init('Slaves');
        $retailer = $_SESSION['Auth']['id'];
        $join = ($service == 6)?'left join bbps_txnid_mapping on (bbps_txnid_mapping.payment_txnid = vendors_activations.txn_id) ':'';
        $bbps_data = ($service == 6)?',bbps_txnid_mapping.id as bbps_txnid,bbps_txnid_mapping.bill_data ':'';

        $ret_arr = $retailObj->query("SELECT products.name,services.name,vendors_activations.txn_id as ref_code,vendors_activations.id,vendors_activations.param,
				vendors_activations.mobile,vendors_activations.amount,vendors_activations.status, trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id,sum(complaints.resolve_flag) as resolve_flag
				$bbps_data
                                FROM products
				inner join services on products.service_id = services.id
				inner join vendors_activations on products.id = vendors_activations.product_id
				left join complaints on complaints.vendor_activation_id = vendors_activations.id where products.service_id = $service AND vendors_activations.retailer_id =  $retailer AND vendors_activations.date >= '" . date('Y-m-d', strtotime('-7 days')) . "'
				$join
                                group by vendors_activations.id
				order by vendors_activations.id desc LIMIT 0,10");

        Configure::load('billers');
        $billers = Configure::read('billers');
        $ret = array();
        foreach($ret_arr as $ra) {
            if(isset($ra['bbps_txnid_mapping']['bbps_txnid']) && !empty($ra['bbps_txnid_mapping']['bbps_txnid'])){
                $ra['0']['bbps_flag']=1;
            }
            $ra['vendors_activations']['logo_url'] = isset($billers[$ra['vendors_activations']['product_id']]['logo_url'])?$billers[$ra['vendors_activations']['product_id']]['logo_url']:'';
            $ret[] = $ra;
        }

        return array('ret'=>$ret);
    }

    function lastFiveTransactions(){
        $retailObj = ClassRegistry::init('Slaves');
        $retailer = $_SESSION['Auth']['id'];
        $transactions = $retailObj->query("SELECT products.name,services.name,vendors_activations.txn_id as ref_code,vendors_activations.id,
				vendors_activations.param,vendors_activations.mobile,vendors_activations.amount,vendors_activations.status,
				trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id,
				sum(complaints.resolve_flag) as resolve_flag
				FROM products
				inner join services on products.service_id = services.id
				inner join vendors_activations on products.id = vendors_activations.product_id
				left join complaints on complaints.vendor_activation_id = vendors_activations.id
				where vendors_activations.retailer_id = $retailer
				AND vendors_activations.date >= '" . date('Y-m-d', strtotime('-7 days')) . "'
				group by vendors_activations.id
				order by vendors_activations.id desc LIMIT 0,5");
        return $transactions;
    }

    function mobileTransactions($mobile, $service = null){
        if( ! in_array($service, array('2', '6'))) $mobile = substr($mobile,  - 10);
        $retailObj = ClassRegistry::init('Slaves');
        $retailer = isset($_SESSION['Auth']['id']) ? $_SESSION['Auth']['id'] : "";
        $limit = 0;
        $join = 'left join bbps_txnid_mapping on (bbps_txnid_mapping.payment_txnid = vendors_activations.txn_id) ';
        $bbps_data = ',bbps_txnid_mapping.id as bbps_txnid,bbps_txnid_mapping.bill_data ';

        if(empty($service)){
            $ret_arr = $retailObj->query("SELECT products.name,services.name,vendors_activations.txn_id as ref_code,vendors_activations.param,vendors_activations.vendor_id,
					vendors_activations.mobile,vendors_activations.amount,vendors_activations.status, trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id,sum(complaints.resolve_flag) as resolve_flag
					$bbps_data
                                        FROM products
					inner join services on products.service_id = services.id
					inner join vendors_activations on products.id = vendors_activations.product_id
					left join complaints on complaints.vendor_activation_id = vendors_activations.id
					$join
                                        where vendors_activations.retailer_id = $retailer AND vendors_activations.mobile = '$mobile' AND vendors_activations.date >= '" . date('Y-m-d', strtotime('-7 days')) . "'
					group by vendors_activations.id
					order by vendors_activations.id desc LIMIT $limit," . PAGE_LIMIT);
        }
        else{
            $q = 'vendors_activations.mobile';
            if($service == '2' || $service == '6') $q = 'vendors_activations.param';

            $ret_arr = $retailObj->query("SELECT products.name,services.name,vendors_activations.txn_id as ref_code,vendors_activations.id,vendors_activations.param,vendors_activations.vendor_id,
					vendors_activations.mobile,vendors_activations.amount,vendors_activations.status, trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id,sum(complaints.resolve_flag) as resolve_flag
					$bbps_data
                                        FROM products
					inner join services on products.service_id = services.id
					inner join vendors_activations use INDEX (idx_ret_date) on products.id = vendors_activations.product_id
					left join complaints on complaints.vendor_activation_id = vendors_activations.id
					$join
                                        where products.service_id = $service AND vendors_activations.retailer_id = $retailer AND (vendors_activations.mobile = '$mobile' OR " . $q . " like '%$mobile%') AND vendors_activations.date >= '" . date('Y-m-d', strtotime('-7 days')) . "'
					group by vendors_activations.id
					order by vendors_activations.id desc LIMIT $limit," . PAGE_LIMIT);
        }

        Configure::load('billers');
        $billers = Configure::read('billers');
        $ret = array();
        foreach($ret_arr as $ra) {
            if(isset($ra['bbps_txnid_mapping']['bbps_txnid']) && !empty($ra['bbps_txnid_mapping']['bbps_txnid'])){
                $ra['0']['bbps'] = 1;
            }
            $ra['vendors_activations']['logo_url'] = $billers[$ra['vendors_activations']['product_id']]['logo_url'];
            $ret[] = $ra;
        }

        return array('ret'=>$ret);
    }

    function lastThreeTopups($mobile = null){
        if(empty($mobile)) return null;
        $Retailer = ClassRegistry::init('Slaves');

        $transactions = $Retailer->query("select *
						from shop_transactions st
						left join retailers r on r.id = st.target_id
						where st.source_id = r.parent_id
						and st.target_id = r.id
						and st.type = 2
						and r.mobile = '$mobile'
						order by st.timestamp desc
						limit 3");

        return $transactions;
    }

    function searchTransactionsHistory($mob_subid, $retailer_id){
        $retailObj = ClassRegistry::init('Slaves');
        $ret = $retailObj->query("SELECT products.name,services.name,vendors_activations.txn_id as ref_code,vendors_activations.id,
					vendors_activations.param,vendors_activations.mobile,vendors_activations.amount,vendors_activations.status,
					trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id,
					sum(complaints.resolve_flag) as resolve_flag
				FROM products
				inner join services on products.service_id = services.id
				inner join vendors_activations on products.id = vendors_activations.product_id
				left join complaints on complaints.vendor_activation_id = vendors_activations.id
				where vendors_activations.retailer_id = $retailer_id
				AND (vendors_activations.mobile = '$mob_subid' OR vendors_activations.param = '$mob_subid')
				AND vendors_activations.date >= '" . date('Y-m-d', strtotime('-7 days')) . "'
				order by vendors_activations.id desc");

        return $ret;
    }

    function searchTransactions($mob_subid, $retailer_id){
        $retailObj = ClassRegistry::init('Slaves');
        $retailer = $_SESSION['Auth']['id'];
        $ret = $retailObj->query("SELECT products.name,services.id,vendors_activations.txn_id as ref_code,vendors_activations.param,
				vendors_activations.mobile,vendors_activations.amount,vendors_activations.status, vendors_activations.timestamp,
				vendors_activations.product_id
				FROM products,services,vendors_activations
				where products.service_id = services.id
				AND products.id = vendors_activations.product_id
				AND vendors_activations.retailer_id = $retailer_id
				AND (vendors_activations.mobile = '$mob_subid' OR vendors_activations.param = '$mob_subid')
				AND vendors_activations.date >= '" . date('Y-m-d', strtotime('-7 days')) . "'
				order by vendors_activations.id desc
				LIMIT 2");

        return $ret;
    }

    function complaintStats($retailer_id, $date){
        if( ! $date){
            $date = date('Y-m-d');
        }
        $prev_day = date('Y-m-d', strtotime($date . ' - 1 day'));
        $retailObj = ClassRegistry::init('Slaves');
        $complaints = $retailObj->query("SELECT count(complaints.resolve_flag) as count, complaints.resolve_flag
							from complaints
                         	inner join vendors_activations USE INDEX (PRIMARY) ON (complaints.vendor_activation_id = vendors_activations.id)
                         	inner join retailers  on(vendors_activations.retailer_id=retailers.id)
                            where complaints.in_date >= '$prev_day'
                            AND vendors_activations.retailer_id = $retailer_id
                            group by complaints.resolve_flag");
        return $complaints;
    }

    function reversalTransactions($date, $service = null){
        $page = 1;
        if(empty($date)){
            $date = date('Y-m-d');
        }

        $next_day = date('Y-m-d', strtotime($date . ' + 1 day'));
        if($next_day > date('Y-m-d')){
            $next_day = '';
        }
        $prev_day = date('Y-m-d', strtotime($date . ' - 1 day'));

        // return array('ret' => array(),'today' => $date,'prev' => $prev_day,'next' => $next_day);

        $limit = ($page - 1) * PAGE_LIMIT;

        $retailer = $_SESSION['Auth']['id'];
        // $type_sql = " AND (vendors_activations.status = ".TRANS_REVERSE." OR vendors_activations.status = ".TRANS_REVERSE_PENDING." OR vendors_activations.status = ".TRANS_REVERSE_DECLINE.")";

        if($service == null || empty($service)){
            /*
             * "SELECT
             * products.name,services.name,vendors_activations.ref_code,vendors_activations.param,vendors_activations.mobile,vendors_activations.amount,vendors_activations.status, trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id
             * FROM
             * products,services,vendors_activations
             * where
             * products.service_id = services.id
             * AND products.id = vendors_activations.product_id
             * AND vendors_activations.retailer_id = $retailer
             * AND vendors_activations.date='$date' $type_sql
             * order by
             * vendors_activations.id desc
             * "
             */
            $panelQ = "
			SELECT
                                products.name,services.name,vendors_activations.txn_id as ref_code,vendors_activations.param,
                                vendors_activations.mobile,vendors_activations.amount,vendors_activations.status, trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id,sum(complaints.resolve_flag) as resolve_flag
                         from
                                        complaints
                         inner  join    vendors_activations USE INDEX (PRIMARY) ON (complaints.vendor_activation_id = vendors_activations.id)
                         inner  join    retailers  on(vendors_activations.retailer_id=retailers.id)
                         inner  join    products   on(products.id=vendors_activations.product_id)
                         inner join    services     on(products.service_id=services.id)

				where

                                complaints.in_date ='$date'
                                AND vendors_activations.retailer_id =  $retailer
                                group by vendors_activations.id
                                order by complaints.id desc";

            // AND complaints.resolve_flag = 0
        }
        else{
            // $ret = $retailObj->query("SELECT products.name,services.name,vendors_activations.ref_code,vendors_activations.id,vendors_activations.param,vendors_activations.mobile,vendors_activations.amount,vendors_activations.status, trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id FROM products,services,vendors_activations where products.service_id = services.id AND products.service_id = $service AND products.id = vendors_activations.product_id AND vendors_activations.retailer_id = $retailer AND vendors_activations.date='$date' $type_sql order by vendors_activations.id desc");
            $panelQ = "
			SELECT
                                products.name,services.name,vendors_activations.txn_id as ref_code,vendors_activations.param,vendors_activations.mobile,vendors_activations.amount,vendors_activations.status, trim(vendors_activations.timestamp) as timestamp,vendors_activations.product_id,sum(complaints.resolve_flag) as resolve_flag
                         from
                                        complaints
                         inner  join    vendors_activations USE INDEX (PRIMARY) ON (complaints.vendor_activation_id = vendors_activations.id)
                         inner  join    retailers  on(vendors_activations.retailer_id=retailers.id)
                         inner  join    products   on(products.id=vendors_activations.product_id)
                         inner  join    services     on(products.service_id=services.id)

				where

                                complaints.in_date ='$date'
                                AND services.id = $service
                                AND vendors_activations.retailer_id =  $retailer
                                group by vendors_activations.id
                                order by complaints.id desc";
        }
        $retailObj = ClassRegistry::init('Slaves');
        $ret_arr = $retailObj->query($panelQ);

        Configure::load('billers');
        $billers = Configure::read('billers');
        $ret = array();
        foreach($ret_arr as $ra) {
            $ra['vendors_activations']['logo_url'] = $billers[$ra['vendors_activations']['product_id']]['logo_url'];
            $ret[] = $ra;
        }

        return array('ret'=>$ret, 'today'=>$date, 'prev'=>$prev_day, 'next'=>$next_day);
    }

    function updateSlab($slab_id, $shop_id, $group_id){
        $slabObj = ClassRegistry::init('SlabsUser');
        $slabObj->create();
        $slabData['SlabsUser']['slab_id'] = $slab_id;
        $slabData['SlabsUser']['shop_id'] = $shop_id;
        $slabData['SlabsUser']['group_id'] = $group_id;
        $slabData['SlabsUser']['timestamp'] = date('Y-m-d H:i:s');
        $slabObj->save($slabData);
    }

    function getBanks(){
        $data = $this->getMemcache('banks');

        if($data === false){
            $retailObj = ClassRegistry::init('Retailer');
            $data = $retailObj->query("Select `bank_name` from bank_details where status = '1'");
            $this->setMemcache('banks', $data, 2 * 24 * 60 * 60);
        }

        return $data;
    }

    function addNewVendorRetailer($vendor_id, $retailer_code){
        $retailObj = ClassRegistry::init('Retailer');
        $retailObj->query("INSERT INTO vendors_retailers (vendor_id,retailer_code) VALUES ($vendor_id,'$retailer_code')");
    }

    function apiAccessHashCheck($params, $partnerModel){
        $pass = $partnerModel['Partner']['password'];
        $hashGen = sha1($params['partner_id'] . $params['trans_id'] . $pass); // .$params['info_json']
        if( ! empty($params['hash_code']) && strtolower($params['hash_code']) == strtolower($hashGen)){
            return true;
        }
        else{
            return false;
        }
    }

    function appRechargeAccessHashCheck($uuid, $mobDthNo, $amt, $timestamp, $hash){
        $hashGen = sha1($uuid . $mobDthNo . $amt . $timestamp); // .$params['info_json']
        if( ! empty($hash) && strtolower($hash) == strtolower($hashGen)){
            return true;
        }
        else{
            return false;
        }
    }

    function apiAccessIPCheck($partner){
        $result = array();
        $ipAddrsStr = $partner['Partner']['ip_addrs']; // get partener's valid ip list
        $ipArr = explode(",", $ipAddrsStr);
        $ip = $this->General->getClientIP();

        if(in_array($ip, $ipArr)){ // check client ip is in partner's ips list
            $result = array('access'=>true);
        }
        else{
            $result = array('access'=>false);
        }
        return $result;
    }

    function apiAccessPartnerOperatorCheck($partnerAccno, $operatorId){ // check operator is open for a partner (only for recharge api)
        $result = array();
        $retailObj = ClassRegistry::init('Slaves');
        $result = $retailObj->query("select * from  partner_operator_status where partner_acc_no = '" . $partnerAccno . "' and operator_id = '$operatorId'");

        if(empty($result) || $result[0]['partner_operator_status']['status'] == 0){ // check client ip is in partner's ips list
            $result = array('access'=>false);
        }
        else{
            $result = array('access'=>true);
        }
        return $result;
    }

    function checkToken($tokenkey = null){
        $redis = $this->redis_connect();
        $value = false;
        if($tokenkey != null){
            $value = $redis->hexists("token", $tokenkey);
        }
        // $redis->quit();
        return $value;
    }

    function setToken($tokenkey = null){
        $redis = $this->redis_connect();
        if($tokenkey != null){
            $redis->hset("token", $tokenkey, 1);
        }
        // $redis->quit();
    }

    function delToken($tokenvalue){
        $redis = $this->redis_connect();
        $deltoken = $redis->hdel("token", $tokenvalue);
        // $redis->quit();
        return $deltoken;
    }

    /*
     * function partnerSessionInit( $type , $model ){ //$id ,
     * // type -> user , partner , retailer , distributor , rm
     * // $model -> Model Object of ( user , partner , retailer , distributor , rm )
     * $data = array();
     * if($type == "user"){
     * $data = $this->User->query("SELECT id FROM users WHERE mobile = '".$params['mobile']."' AND password='$password'");
     * }else if($type == "partner"){
     * $partnerModel = $model;
     * $retailerRegObj = ClassRegistry::init('Retailer');
     * $retailerModel = $retailerRegObj->findById($partnerModel['Partner']['retailer_id']);
     * if(!empty($retailerModel)){
     * $data['user_id'] = $retailerModel['Retailer']['user_id'];
     * $data['group_id'] = RETAILER;
     * }
     * }
     *
     * if(empty($data)){// Invalid Partner ( can't find retailer account for partner ).
     * return false;
     * }else{
     * $info = $this->getShopData($data['user_id'],$data['group_id']);
     * $info['User']['group_id'] = $data['group_id'];
     * $info['User']['id'] = $data['user_id'];
     * //$this->SessionComponent->write('Auth',$info);
     * $_SESSION['Auth'] = $info;
     * if(!empty($_SESSION['Auth']['id'])){
     * return true;
     * }else{
     * return false;
     * }
     * }
     * }
     */

    /**
     *
     * @param type $query
     *            : string params
     * @param type $vendor_id
     *            : modem ID
     */
    function async_request_via_redis($query, $vendor_id){
        $redis = $this->openservice_redis();
        $queuename = "updateIncoming_{$vendor_id}";
        $queuevalue = $query;
        $redis->lpush($queuename, $query);
    }

    function deleteDocument($src){
        $Retailer = ClassRegistry::init('Retailer');
        $objectKey = explode("/", $src);
        App::import('vendor', 'S3', array('file'=>'S3.php'));
        $bucket = s3kycBucket;
        $s3 = new S3(awsAccessKey, awsSecretKey);
        if($s3->deleteObject($bucket, $objectKey[3])){
            if($Retailer->query("DELETE FROM  retailers_details where image_name = '" . $src . "'")){
                return "Image deleted";
            }
            else
                return $src . " not deleted from db";
        }
        else{
            return $src . " not deleted from aws";
        }
    }

    function deleteImageFromAWS($src){
        $objectKey = explode("/", $src);
        App::import('vendor', 'S3', array('file'=>'S3.php'));
        $bucket = s3kycBucket;
        $s3 = new S3(awsAccessKey, awsSecretKey);
        if($s3->deleteObject($bucket, $objectKey[3])) return true;
        else return false;
    }

    function removeDocument($retailer_id, $section_id, $verify_flag){
        $map = $this->kycSectionMap($section_id);
        $Retailer = ClassRegistry::init('Retailer');
        if($verify_flag == 1){
            $verified_documents = $Retailer->query("select * from retailers_docs
    				where retailer_id = " . $retailer_id . "
    				and type = '" . $map['documents'][0] . "'");
            if(($section_id == 3 && count($verified_documents) > 3) || in_array($section_id, array(1, 2))){
                $documents = $Retailer->query("select * from retailers_details
    					where image_name = '" . $verified_documents[0]['retailers_docs']['src'] . "'");
                $Retailer->query("delete from retailers_docs
    					where id = " . $verified_documents[0]['retailers_docs']['id']);
                if(count($documents) == 0){
                    $this->deleteImageFromAWS($verified_documents[0]['retailers_docs']['src']);
                }
            }
        }
        else{
            $documents = $Retailer->query("select * from retailers_details
    				where retailer_id = " . $retailer_id . "
    				and type = '" . $map['documents'][0] . "'");
            if(($section_id == 3 && count($documents) > 3) || in_array($section_id, array(1, 2))){
                $verified_documents = $Retailer->query("select * from retailers_docs
    					where src = '" . $documents[0]['retailers_details']['image_name'] . "'");
                $Retailer->query("delete from retailers_details
    					where id = " . $documents[0]['retailers_details']['id']);
                if(count($verified_documents) == 0){
                    $this->deleteImageFromAWS($documents[0]['retailers_details']['src']);
                }
            }
        }
    }

    /**
     * KYC States
     * 0 => Submitted
     * 1 => Rejected
     * 2 => Approved
     * 3 => Unverified
     */
    function setKYCState($retailer_id, $section_id, $state, $reason = null){
        $Retailer = ClassRegistry::init('Retailer');
        switch($state){
            case 0 :
                $retailers_kyc_states = $Retailer->query("select *
								from retailers_kyc_states
								where retailer_id = " . $retailer_id . "
								and section_id = " . $section_id);
                if(empty($retailers_kyc_states)){
                    $map = $this->kycSectionMap($section_id);
                    $types = implode(",", $map['documents']);
                    $documents = $Retailer->query("select *
    						from retailers_details rd
    						where rd.type in ('" . $types . "')
    						and rd.retailer_id = " . $retailer_id);
                    if( ! empty($documents)){
                        $Retailer->query("insert into retailers_kyc_states
									(retailer_id, section_id, verified_state, document_state, document_timestamp, document_date)
									values(" . $retailer_id . ", " . $section_id . ", '0', '0', '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d') . "')");
                    }
                }
                else{
                    $Retailer->query("update retailers_kyc_states
									set document_state = '0',
									document_timestamp = '" . date('Y-m-d H:i:s') . "',
									document_date = '" . date('Y-m-d') . "',
    								comment = ''
									where retailer_id = " . $retailer_id . "
									and section_id = " . $section_id);
                }
                break;
            case 1 :
                $retailers_kyc_states = $Retailer->query("select *
								from retailers_kyc_states
								where retailer_id = " . $retailer_id . "
								and section_id = " . $section_id);
                if( ! empty($retailers_kyc_states)){
                    $Retailer->query("update retailers_kyc_states
									set document_state = '1',
									document_timestamp = '" . date('Y-m-d H:i:s') . "',
									document_date = '" . date('Y-m-d') . "',
    								comment = '$reason'
									where retailer_id = " . $retailer_id . "
									and section_id = " . $section_id);
                }
                break;
            case 2 :
                $retailers_kyc_states = $Retailer->query("select *
								from retailers_kyc_states
								where retailer_id = " . $retailer_id . "
								and section_id = " . $section_id);
                if( ! empty($retailers_kyc_states)){
                    $Retailer->query("update retailers_kyc_states
									set document_state = '2',
    								verified_state = '1',
									document_timestamp = '" . date('Y-m-d H:i:s') . "',
									document_date = '" . date('Y-m-d') . "',
    								verified_timestamp = '" . date('Y-m-d H:i:s') . "',
									verified_date = '" . date('Y-m-d') . "',
    								comment = ''
									where retailer_id = " . $retailer_id . "
									and section_id = " . $section_id);
                    $map = $this->kycSectionMap($section_id);
                    $unverified_retailers = $Retailer->query("select *
    						from unverified_retailers ur
    						where ur.retailer_id = " . $retailer_id);

                    $update_query = "update retailers set ";
                    foreach($map['fields'] as $field){
                        if( ! in_array($field, array('latitude', 'longitude'))){
                            $update_query .= " $field = '" . $unverified_retailers[0]['ur'][$field] . "', ";
                            $imp_update_data[$field] = $unverified_retailers[0]['ur'][$field];
                        }
                    }
                    $update_query .= " modified = '" . date('Y-m-d H:i:s') . "'
    						where id = " . $retailer_id;
                    $Retailer->query($update_query);

                    /** IMP DATA ADDED : START**/
                    $response = $this->updateUserLabelData($retailer_id,$imp_update_data,$this->Session->read('Auth.User.id'),2);
                    /** IMP DATA ADDED : END**/

                    if(in_array('latitude', $map['fields'])){
                        $user_profile = $Retailer->query("select r.*, up.*
    							from user_profile up
    							join retailers r on r.user_id = up.user_id
    							where r.id = " . $retailer_id . "
    							and up.device_type = 'online'
    							order by updated desc
    							limit 1");
                        if( ! empty($user_profile)){
                            $Retailer->query("update user_profile
								set latitude = '" . $unverified_retailers[0]['ur']['latitude'] . "',
								longitude = '" . $unverified_retailers[0]['ur']['longitude'] . "',
								updated = '" . date("Y-m-d H:i:s") . "',
                                date = '".date('Y-m-d')."'
								where user_id = " . $user_profile[0]['up']['user_id'] . "
								and device_type = 'online'");
                        }
                        else{
                            $retailers = $Retailer->query("select *
    								from retailers r
    								where r.id = " . $retailer_id);
                            $Retailer->query("insert into user_profile
								(user_id, gcm_reg_id, uuid, longitude, latitude, location_src, device_type,
								version , manufacturer, created, updated,date)
								VALUES (" . $retailers[0]['r']['user_id'] . ", '" . $retailers[0]['r']['mobile'] . "',
								'" . $retailers[0]['r']['mobile'] . "', '" . $unverified_retailers[0]['ur']['longitude'] . "',
								'" . $unverified_retailers[0]['ur']['latitude'] . "', '' ,'online' ,'' ,'' ,
								'" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "','".date('Y-m-d')."')");
                        }
                    }

                    $types = implode(",", $map['documents']);
                    $retailers_details = $Retailer->query("select *
    						from retailers_details rd
    						where retailer_id = " . $retailer_id . "
    						and type in ('" . $types . "')");
                    $Retailer->query("delete from retailers_docs
    						where retailer_id = " . $retailer_id . "
    						and type in ('" . $types . "')");
                    foreach($retailers_details as $rd){
                        $Retailer->query("insert into retailers_docs
    							(retailer_id, type, src, uploader_user_id)
    							values (" . $rd['rd']['retailer_id'] . ", '" . $rd['rd']['type'] . "', '" . $rd['rd']['image_name'] . "',
    							" . $rd['rd']['uploader_user_id'] . ")");
                    }
                }
                break;
            case 3 :

                break;
        }
        $this->setKYCScore($retailer_id);
    }

    function setKYCScore($retailer_id){
        $Retailer = ClassRegistry::init('Retailer');

        $retailers_kyc_states = $Retailer->query("select *
    			from retailers_kyc_states rks
    			where rks.retailer_id = " . $retailer_id);
        $kyc_score = 0;
        foreach($retailers_kyc_states as $rks){
            switch($rks['rks']['section_id']){
                case "1" :
                    $rks['rks']['verified_state'] and $kyc_score += 35;
                    break;
                case "2" :
                    $rks['rks']['verified_state'] and $kyc_score += 40;
                    break;
                case "3" :
                    $rks['rks']['verified_state'] and $kyc_score += 25;
                    break;
            }
        }

        $Retailer->query("update retailers
    			set kyc_score = " . $kyc_score . ",
    			modified = '" . date('Y-m-d H:i:s') . "'
    			where id = " . $retailer_id);
    }

    function errorCodeMapping($vendorId, $errorCode){
        $errorcode = array(
                "8"=>array("0"=>"13", "1"=>"30", "2"=>"30", "3"=>"30", "4"=>"8", "5"=>"403", "6"=>"30", "7"=>"6", "8"=>"5", "9"=>"46", "10"=>"4", "11"=>"403", "12"=>"30", "21"=>"26", "23"=>"5", "24"=>"30", "25"=>"30", "30"=>"14", "32"=>"30", "33"=>"30", "37"=>"30", "45"=>"9", "50"=>"30", "52"=>"30",
                        "53"=>"30", "54"=>"43", "81"=>"34", "82"=>"30", "88"=>"30)", "224"=>"43", "134"=>"30", "137"=>"30"),
                "58"=>array("TXN"=>"13", "TUP"=>"31", "RPI"=>"4", "UAD"=>"404", "IAC"=>"30", "IAT"=>"30", "AAB"=>"30", "IAB"=>"26", "ISP"=>"8", "DID"=>"30", "DTX"=>"30", "IAN"=>"46", "IRA"=>"6", "DTB"=>"30", "RBT"=>"43", "SPE"=>"43", "SPD"=>"43", "UED"=>"30", "IEC"=>"30", "IRT"=>"30", "ITI"=>"30",
                        "TSU"=>"30", "IPE"=>"30", "ISE"=>"30", "TRP"=>"30", "OUI"=>"0", "ODI"=>"30"),

                "27"=>array("100"=>"13", "99"=>"14", "101"=>"30", "102"=>"26", "103"=>"6", "104"=>"30", "105"=>"30", "106"=>"30", "107"=>"5", "110"=>"6", "111"=>"30", "121"=>"1", "123"=>"30", "165"=>"15", "170"=>"30", "171"=>"30", "173"=>"43", "172"=>"2", "174"=>"30"),
                "24"=>array("1200"=>"15", "1201"=>"0", "1202"=>"5", "1203"=>"6", "1204"=>"30", "1205"=>"9", "1206"=>"30", "1207"=>"26", "1208"=>"26", "1209"=>"30", "1210"=>"30", "1211"=>"30", "1212"=>"0"),
                "18"=>array("0"=>"0", "1"=>"31", "501"=>"30", "502"=>"4", "503"=>"14", "504"=>"0", "505"=>"0", "506"=>"26", "507"=>"30", "508"=>"30", "509"=>"30", "510"=>"2"));

        return $errorcode[$vendorId][$errorCode];
    }

    function isStrongPassword($password,$group_id=6){
        $group_id = (isset($_SESSION['User']['group_id'])) ? $_SESSION['User']['group_id'] : $group_id;
        if( ! empty($password)):
            // if(strlen($password)<5 || !preg_match( '~[A-Z]~', $password) || !preg_match( '~[a-z]~', $password) || !preg_match( '~[0-9]~', $password)):
            if($group_id==6 && strlen($password)<4 || strlen($password) > 12 || in_array($password, array('1010', '1111', '9999', '1234', '0000', '4321', '2222', '4444', '5555', '6666', '7777', '8888', '3333')) ):
                return false;
            elseif(strlen($password)<4 || strlen($password) > 12 || in_array($password, array('1010', '1111', '9999', '1234', '0000', '4321', '2222', '4444', '5555', '6666', '7777', '8888', '3333'))):
                return false;
            else:
                return true;
            endif;

                        endif;

        return false;
    }



    function checkAuthenticateDeviceType($device_type){
        if( ! empty($device_type) && $device_type == "android"){
            $verify = 2;
        }
        else if( ! empty($device_type) && $device_type == "windows7"){
            $verify = 7;
        }
        else if( ! empty($device_type) && $device_type == "windows8"){
            $verify = 8;
        }
        else if( ! empty($device_type) && $device_type == "java"){
            $verify = 3;
        }
        else if( ! empty($device_type) && $device_type == "web"){
            $verify = 9;
        }
        else{
            $verify = 0;
        }
        return $verify;
    }

    function checkAppVerssion($groupId, $appVersionCode,$app_name){
        if($groupId == 6):
            $updateVersionCode = $this->General->findVar("pay1_merchant_update_version");

        elseif($groupId == 5):
            $updateVersionCode = $this->General->findVar("pay1_distributor_update_version");
        endif;

        if( ! empty($appVersionCode)):
            if($appVersionCode < $updateVersionCode):
                $returnData = array("status"=>"failure", "code"=>"48", "forced_upgrade_flag"=>"1", "description"=>$this->errors(48));
                 endif;
            endif;


        return $returnData;
    }

    function getUserInfo($mobile, $password = null, $params = null){
	$insObj = ClassRegistry::init('Slaves');
	$params['app_type'] = (!isset($params['app_type'])) ? 'recharge_app' : $params['app_type'];
        if($password == null):
            $sqlQuery = "SELECT users.id, users.passflag, users.active_flag, user_profile.id, gcm_reg_id,uuid,longitude,latitude,location_src,device_type FROM users LEFT JOIN user_profile on (user_profile.user_id = users.id and user_profile.uuid = '" . $params['d_id'] . "' And user_profile.app_type ='" . $params['app_type'] . "')" . " WHERE mobile = '" . $mobile . "'";
        else:
            $sqlQuery = "SELECT users.id, users.passflag, users.active_flag, user_profile.id, gcm_reg_id,uuid,longitude,latitude,location_src,device_type FROM users left join user_profile on (user_profile.user_id = users.id and user_profile.uuid = '" . $params['d_id'] . "' And user_profile.app_type ='" . $params['app_type'] . "') " . " WHERE mobile = '" . $mobile . "' AND password='$password'";
        endif;

        $data = $insObj->query($sqlQuery);

        return $data;
    }

    function setDeviceData($mobile, $data){
        if($data['group_id'] == RETAILER){
            $this->setRetailerTrnsDetails($mobile, $data);
        }
        else if($data['group_id'] == SALESMAN || $data['group_id'] == DISTRIBUTOR){
            $this->setSalesmanDeviceData($mobile, $data);
        }
        return;
    }

    function getDays($sStartDate, $sEndDate){
        // Firstly, format the provided dates.
        $sStartDate = date("Y-m-d", strtotime($sStartDate));
        $sEndDate = date("Y-m-d", strtotime($sEndDate));

        // Start the variable off with the start date
        $aDays[] = $sStartDate;

        // Set a 'temp' variable, sCurrentDate, with
        // the start date - before beginning the loop
        $sCurrentDate = $sStartDate;

        // While the current date is less than the end date
        while($sCurrentDate < $sEndDate){
            // Add a day to the current date
            $sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

            // Add this new day to the aDays array
            $aDays[] = $sCurrentDate;
        }

        // Once the loop has finished, return the
        // array of days.
        return $aDays;
    }
    function getServices(){
        $services = array();
        $userObj = ClassRegistry::init('Slaves');
        $temp = $userObj->query('SELECT id,name FROM services WHERE toShow = 1');
        if( count($temp) > 0 ){
            foreach(array_map('current',$temp) as $index => $service){
                $services[$service['id']] = trim($service['name']);
            }
        }
        return $services;
    }
    function getDistSalesmen($dist_id = null){
        $dist_salesmen = array();
        if($dist_id){
            $userObj = ClassRegistry::init('Slaves');
            $temp = $userObj->query('SELECT id,name FROM salesmen WHERE dist_id = "'.$dist_id.'"');
            if( count($temp) > 0 ){
                foreach(array_map('current',$temp) as $index => $salesman){
                    $dist_salesmen[$salesman['id']] = trim($salesman['name']);
                }
            }
        }
        return array_unique(array_filter($dist_salesmen));
    }
    function getDistRetailers($dist_id = null,$selected_salesman = null){
        $dist_retailers = array();

        $salesman_cond = '';
        if( $selected_salesman ){
            $salesman_cond = ' AND maint_salesman = '.$selected_salesman;
        }

        if($dist_id){
            $userObj = ClassRegistry::init('Slaves');
            $temp = $userObj->query('SELECT id,shopname as name FROM retailers WHERE parent_id = "'.$dist_id.'"'.$salesman_cond);
            if( count($temp) > 0 ){
                foreach(array_map('current',$temp) as $index => $retailer){
                    $dist_retailers[$retailer['id']] = $retailer['name'];
                }
                /** IMP DATA ADDED : START - Rohit **/
                $imp_data = $this->getUserLabelData(array_keys($dist_retailers),2,2);
                foreach ($dist_retailers as $retailer_id => $retailer_name) {
                    if(array_key_exists($retailer_id,$imp_data)){
                        $dist_retailers[$retailer_id] = $imp_data[$retailer_id]['imp']['shop_est_name'];
                    }
                }
                /** IMP DATA ADDED : END - Rohit **/
            }
        }
        return array_unique(array_filter($dist_retailers));
    }
    function getDistTransactionsByProductType($dist_id = null,$dist_user_id = null,$product_type = null,$date_from = null,$date_to = null,$retailer = null,$salesman = null,$mode = null){
        $transactions = array();
        $refund_transactions = array();
        $kitcharge_transactions = array();
        if( $dist_id && $product_type && $dist_user_id){
            $userObj = ClassRegistry::init('Slaves');

            $date_cond = '';
            if( $date_from && $date_to ){
                $date_cond = ' AND st.date >= "'.date('Y-m-d',strtotime($date_from)).'" AND st.date <= "'.date('Y-m-d',strtotime($date_to)).'"';
            }
            $retailer_cond = '';
            if( (!$mode) && $retailer ){
                $retailer_cond = ' AND ret.id = '.$retailer;
            }
            $salesman_cond = '';
            if( (!$mode) && $salesman ){
                $salesman_cond = ' AND sal.id = '.$salesman;
            }

            switch ( $product_type ) {


                case $product_type >= 8:  // swipe,AEPS,remit




                    $main_select_cond = ' SELECT  st.*,
                            ret.mobile as retailer_mobile,
                            ret.id as retailer_id,
                            ret.shopname as retailer_name,
                            sal.user_id as salesman_user_id,
                            sal.mobile as salesman_mobile,
                            sal.name as salesman_name, trim(spp.dist_params) as dist_margins
                    FROM shop_transactions st
                    JOIN retailers ret
                    ON( st.source_id = ret.user_id )
                    LEFT JOIN products ON (products.id = st.target_id)
                    left join users_services on (products.service_id = users_services.service_id AND st.source_id = users_services.user_id)
                    left join service_plans on (service_plans.id = users_services.service_plan_id)
                    left join service_product_plans as spp on (spp.service_plan_id = service_plans.id AND spp.product_id = products.id)
                    LEFT JOIN salesmen sal
                    ON( ret.maint_salesman = sal.id ) ';

                    // $kitcharge_select_cond = 'SELECT st.* FROM shop_transactions st ';
                    $kitcharge_select_cond = ' SELECT  SUM(st.discount_comission) as amount FROM shop_transactions st ';
                    $refund_select_cond = ' SELECT  SUM(st.amount) as amount FROM shop_transactions st ';

                    /*if($mode && $mode == 'earning'){
                        $main_select_cond = '   SELECT  SUM(st.amount) as amount FROM shop_transactions st
                                                LEFT JOIN retailers ret
                                                ON( st.source_id = ret.user_id )';
                        // $refund_select_cond = $kitcharge_select_cond = ' SELECT  SUM(st.amount) as amount FROM shop_transactions st ';
                    }*/

                    $query = $main_select_cond.'
                                WHERE st.type IN('.CREDIT_NOTE.','.DEBIT_NOTE.')
                                AND st.confirm_flag = 0
                                AND st.type_flag in (0,1)
                                AND ret.parent_id = '.$dist_id.$date_cond.$retailer_cond.$salesman_cond;

                    $kitcharge_query = $kitcharge_select_cond.'
                                    WHERE st.type IN('.KITCHARGE.')
                                    AND st.confirm_flag = 0
                                    AND st.source_id = '.$dist_user_id.$date_cond;

                    $refund_query = $refund_select_cond.'
                                WHERE st.type IN('.REFUND.')
                                AND st.confirm_flag = 0
                                AND st.source_id = '.$dist_user_id.$date_cond;

                    $query = $query.' AND st.user_id IN('.$product_type.')';
                    $kitcharge_query = $kitcharge_query.' AND st.user_id IN('.$product_type.')';
                    $refund_query = $refund_query.' AND st.user_id IN('.$product_type.')';


                    $transactions = $userObj->query($query);

                    if(!$mode){
                        /** IMP DATA ADDED : START**/
                         $ret_mobiles = array_map(function($element){
                            return $element['ret']['retailer_mobile'];
                        },$transactions);


                        $imp_data = $this->getUserLabelData($ret_mobiles,2,1);

                        foreach ($transactions as $key => $transaction) {
                            $transactions[$key]['ret']['retailer_name'] = $imp_data[$transaction['ret']['retailer_mobile']]['imp']['shop_est_name'];
                        }
                        /** IMP DATA ADDED : END**/
                    }
                    $refund_transactions = $userObj->query($refund_query);
                    $kitcharge_transactions = $userObj->query($kitcharge_query);
                    break;
                case in_array( $product_type,array(1,2,4,6,7) ):

                    $main_select_cond = '  SELECT  st.*,
                            ret.mobile as retailer_mobile,
                            ret.user_id as retailer_user_id,
                            ret.shopname as retailer_name,
                            sal.user_id as salesman_user_id,
                            sal.mobile as salesman_mobile,
                            sal.name as salesman_name,
                            st.mobile as customer_mobile
                    FROM vendors_activations st
                    JOIN retailers ret
                    ON( st.retailer_id = ret.id )
                    LEFT JOIN salesmen sal
                    ON( ret.maint_salesman = sal.id )';
//
                    // $refund_select_cond = 'SELECT st.* FROM shop_transactions st ';
                    $refund_select_cond = ' SELECT  SUM(st.amount) as amount FROM shop_transactions st ';

                    /*if($mode && $mode == 'earning'){
                        $main_select_cond = ' SELECT  SUM(st.amount) as amount FROM vendors_activations st ';
                        // $refund_select_cond = ' SELECT  SUM(st.amount) as amount FROM shop_transactions st ';
                    }*/

                    $query = $main_select_cond.'
                                LEFT JOIN products prod
                                ON( st.product_id = prod.id )
                                WHERE prod.service_id IN('.$product_type.')
                                AND st.status NOT IN(2,3)
                                AND st.distributor_id = '.$dist_id.$date_cond.$retailer_cond.$salesman_cond;

                    $refund_query = $refund_select_cond.'
                                WHERE st.type IN('.REFUND.')
                                AND st.confirm_flag = 0
                                AND st.source_id = '.$dist_user_id.$date_cond;



                    $transactions = $userObj->query($query);

                    if(!$mode){
                        /** IMP DATA ADDED : START**/
                        $ret_mobiles = array_map(function($element){
                            return $element['ret']['retailer_mobile'];
                        },$transactions);


                        $imp_data = $this->getUserLabelData($ret_mobiles,2,1);

                        foreach ($transactions as $key => $transaction) {
                            $transactions[$key]['ret']['retailer_name'] = $imp_data[$transaction['ret']['retailer_mobile']]['imp']['shop_est_name'];
                        }
                        /** IMP DATA ADDED : END**/
                    }

//                    if($product_type == 1){
                        // $refund_query = $refund_query.' AND st.user_id NOT IN(8,9,10,11,12)';
                        $refund_query = $refund_query.' AND st.user_id IN ('.$product_type.')';
                        $refund_transactions = $userObj->query($refund_query);
//                    }

                    // echo '<pre>';
                    // print_r($query);
                    // echo '<br>';
                    // echo '<br>';
                    // print_r($refund_query);

                break;
                case in_array( $product_type,array('additional-incentives') ):
                    $refund_select_cond = ' SELECT  st.id,st.amount,st.note,st.date,st.type ';
                    if($mode && $mode == 'earning'){
                        $refund_select_cond = ' SELECT  SUM(st.amount) as amount ';
                    }
                    $refund_query = $refund_select_cond.' FROM shop_transactions st
                                        WHERE st.type IN('.REFUND.')
                                        AND st.confirm_flag = 0
                                        AND (st.user_id is null or st.user_id = 0)
                                        AND st.source_id = '.$dist_user_id.$date_cond;
                    if($mode && $mode == 'earning'){
                        return array_sum(array_map('current',array_map('current',$userObj->query($refund_query))));
                    }
                    return $userObj->query($refund_query);
                break;
                default:
                break;
            }
        }

        if($mode && $mode == 'earning'){
            //$response['sale'] = array_sum(array_map('current',array_map('current',$transactions)));
            $response['sale'] = $transactions;
            $response['refund'] = array_sum(array_map('current',array_map('current',$refund_transactions)));
            $response['kitcharge'] = array_sum(array_map('current',array_map('current',$kitcharge_transactions)));
            return $response;
        } else {
            $response['sale'] = $transactions;
            $response['refund'] = array_sum(array_map('current',array_map('current',$refund_transactions)));
            $response['kitcharge'] = array_sum(array_map('current',array_map('current',$kitcharge_transactions)));
            return $response;
            // return array_merge($transactions,$refund_transactions,$kitcharge_transactions);
        }
    }

    function getRetailerEarningLogServiceWise($dist_user_id = null,$service_id = null,$date_from = null,$date_to = null){
        $userObj = ClassRegistry::init('Slaves');
        $date_cond = '';
        if( $date_from && $date_to ){
            $date_cond = ' AND rel.date >= "'.date('Y-m-d',strtotime($date_from)).'" AND rel.date <= "'.date('Y-m-d',strtotime($date_to)).'" ';
        }

        $service_condition = '';
        if($service_id){
            $service_condition = ' AND rel.service_id IN ('.$service_id.') ';
        }

        $dist_user_id_cond = '';
        if($dist_user_id){
            $dist_user_id_cond = ' AND rel.dist_user_id IN('.$dist_user_id.') ';
        }

        $retailer_earning_log_query = ' SELECT SUM(rel.amount) as total_sale
                                        FROM retailer_earning_logs rel
                                        WHERE rel.type IN ('.CREDIT_NOTE.','.DEBIT_NOTE.','.RETAILER_ACTIVATION.') '.
                                        $dist_user_id_cond.$service_condition.$date_cond.'
                                        GROUP BY rel.service_id';
        $total_retailer_sale = $userObj->query($retailer_earning_log_query);
        return array_sum(array_map('current',array_map('current',$total_retailer_sale)));

    }
    function getDistributorIncentive($dist_user_id = null,$service_id = null,$date_from = null,$date_to = null){
        $userObj = ClassRegistry::init('Slaves');
        $date_cond = '';
        if( $date_from && $date_to ){
            $date_cond = ' AND st.date >= "'.date('Y-m-d',strtotime($date_from)).'" AND st.date <= "'.date('Y-m-d',strtotime($date_to)).'" ';
        }

        $service_condition = '';
        if($service_id){
            $service_condition = ' AND st.service_id IN ('.$service_id.') ';
        }

        $dist_user_id_cond = '';
        if($dist_user_id){
            $dist_user_id_cond = ' AND st.user_id IN('.$dist_user_id.') ';
        }

        if( $service_id == 'additional-incentives' ){   // additional incentive
            $refund_query = '   SELECT  SUM(st.amount) as amount  FROM shop_transactions st
                                WHERE st.type IN('.REFUND.')
                                AND st.confirm_flag = 0
                                AND st.user_id NOT IN(1,2,3,4,5,6,7,8,9,10,11,12) '.
                                $dist_user_id_cond.$date_cond;
            return array_sum(array_map('current',array_map('current',$userObj->query($refund_query))));


        } else { // service wise incentive

            $kitcharge_amount = 0;
            $refund_amount = 0;
            /*if( $service_id && in_array($service_id,array(8,12)) ){ // kitcharge only for mpos and dmt
                $kitcharge_query = 'SELECT  SUM(st.discount_comission) as amount FROM shop_transactions st
                                    WHERE st.type IN('.KITCHARGE.')
                                    AND st.confirm_flag = 0 ' .
                                    $dist_user_id_cond.$date_cond.$service_condition;
                $kitcharge_res = $userObj->query($kitcharge_query);
                $kitcharge_amount = array_sum(array_map('current',array_map('current',$kitcharge_res)));

            }*/

            $comm_query = '   SELECT  SUM(st.amount-st.txn_reverse_amt) as amount FROM users_nontxn_logs st
                                WHERE st.type IN('.COMMISSION_DISTRIBUTOR.') '.
                                $dist_user_id_cond.$date_cond.$service_condition;
            $comm_res = $userObj->query($comm_query);
            $comm_amount = array_sum(array_map('current',array_map('current',$comm_res)));

            $refund_query = '   SELECT  SUM(st.amount-st.txn_reverse_amt) as amount FROM users_nontxn_logs st
                                WHERE st.type IN('.REFUND.') '.
                                $dist_user_id_cond.$date_cond.$service_condition;
            $refund_res = $userObj->query($refund_query);
            $refund_amount = array_sum(array_map('current',array_map('current',$refund_res)));

            return $refund_amount+$kitcharge_amount+$comm_amount;
        }
    }

    //RohitP(rohit3nov@gmail.com)
	function getDistEarnings(){
        $earnings = array();
        $response = array();
        if( $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR ){

            $services = $this->getServices();
			//unset($services[11]); // unsetting microfinance
			$services['additional-incentives'] = 'Additional Incentives';

			$dist_user_id = $_SESSION['Auth']['User']['id'];
			$dist_id = $_SESSION['Auth']['id'];
			$gst_flag = (strlen($_SESSION['Auth']['gst_no']) < 15) ? false : true;

			Configure::load('product_config');
			$earning_config = Configure::read('services');


			foreach($services as $id => $name){
                                $comm = $this->getServiceMargin($id, 0);
				// TODAY
				$today = $this->getDistTransactionsByProductType($dist_id,$dist_user_id,$id,date('Y-m-d'),date('Y-m-d'),null,null,'earning');
                if( $id == 'additional-incentives' ){
					$earnings[$id]['today'] = $today;
				} else {
					$earnings[$id]['today'] = $today['refund'];

					foreach($today['sale'] as $index => $transaction){
					    if($id >= 8 && in_array($transaction['st']['type'],array(RETAILER_ACTIVATION,CREDIT_NOTE,DEBIT_NOTE))){
					        $dist_margin =  json_decode($transaction[0]['dist_margins'],true);
					        $comm = $this->calculateCommission($transaction['st']['amount'],$dist_margin);
					        $earnings[$id]['today'] += $comm['comm'];
					        $today['sale_tot'] +=  $transaction['st']['amount'];
					    }
					    else if( $id< 8){
					        $earnings[$id]['today'] = $earnings[$id]['today'] + ( ($comm/100)*$transaction['st']['amount']);
					        $today['sale_tot'] +=  $transaction['st']['amount'];
					    }
					}

                }



                // YESTERDAY
                $incentive = $this->getDistributorIncentive($dist_user_id,$id,date('Y-m-d', strtotime('-1 day')),date('Y-m-d', strtotime('-1 day')));
                $earnings[$id]['yesterday'] = $incentive;
				/*if($id != 'additional-incentives'){
                    $yesterday_sale = $this->getRetailerEarningLogServiceWise($dist_user_id,$id,date('Y-m-d', strtotime('-1 day')),date('Y-m-d', strtotime('-1 day')));
                    if( $yesterday_sale > 0 ){
                        $earnings[$id]['yesterday'] = $earnings[$id]['yesterday'] + ( ($comm/100)*$yesterday_sale );
					}
				}*/


                // LAST 7 DAYS
                $incentive = $this->getDistributorIncentive($dist_user_id,$id,date('Y-m-d', strtotime('-6 days')),date('Y-m-d'));
                $earnings[$id]['last_7_days'] = $incentive + $earnings[$id]['today'];
				/*if($id != 'additional-incentives'){
                    $last_6_days_sale = $this->getRetailerEarningLogServiceWise($dist_user_id,$id,date('Y-m-d', strtotime('-6 days')),date('Y-m-d', strtotime('-1 day')));
                    if( ($last_6_days_sale+$today['sale_tot']) > 0 ){
                        $earnings[$id]['last_7_days'] = $earnings[$id]['last_7_days'] + ( ($comm/100)*($last_6_days_sale+$today['sale']) );
					}
                }*/

                // LAST 30 DAYS
                $incentive = $this->getDistributorIncentive($dist_user_id,$id,date('Y-m-d', strtotime('-29 days')),date('Y-m-d'));
                $earnings[$id]['last_30_days'] = $incentive + $earnings[$id]['today'];
				/*if($id != 'additional-incentives'){
                    $last_29_days_sale = $this->getRetailerEarningLogServiceWise($dist_user_id,$id,date('Y-m-d', strtotime('-29 days')),date('Y-m-d', strtotime('-1 day')));
                    if( ($last_29_days_sale+$today['sale_tot']) > 0 ){
                        $earnings[$id]['last_30_days'] = $earnings[$id]['last_30_days'] + ( ($comm/100)*($last_29_days_sale+$today['sale']) );
					}
                }*/

			}
        }

        $response['earnings'] = $earnings;
        if($services){
            $response['services'] = $services;
        }
        return $response;
			// $this->set('services',$services);
	}


    function shortenurl($url=null)
    {
        if(is_null($url)){
            return ;
        }

        $key=GOOGLE_URL_SHORTNER_KEY;
        $apiURL = 'https://www.googleapis.com/urlshortener/v1/url'.'?key='.$key;

        $ch = curl_init();
        // If we're shortening a URL...

        curl_setopt($ch,CURLOPT_URL,$apiURL);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$url)));
        curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        // Execute the post
        $result = curl_exec($ch);
        // Close the connection
        curl_close($ch);
        // Return the result
        return json_decode($result,true);
    }

    function addLeadsIntoZoho($params)
    {
        Configure::load('platform');
        $lead_state_mapping = Configure::read('lead_state');
        $lead_source_mapping = Configure::read('lead_source');
        $lead_state = $lead_state_mapping[$params['lead_state']];
        $lead_source = array_search($params['lead_source'],$lead_source_mapping);
        $url = 'https://crm.zoho.com/crm/private/xml/Leads/insertRecords';
        $post_string = 'newFormat=1&authtoken=8e0e86c663b8d6bf039da0c9fe5bfc56&scope=crmapi&xmlData=<Leads><row no="1"><FL val="Lead Source">'.$lead_source.'</FL><FL val="Interested In">'.$params['interest'].'</FL><FL val="Lead Status">'.$lead_state.'</FL><FL val="Company">'.$params['shop_name'].'</FL><FL val="First Name">'.$params['name'].'</FL><FL val="Last Name">'.$params['name'].'</FL><FL val="Email">'.$params['email'].'</FL><FL val="Phone">'.$params['mobile'].'</FL><FL val="Other Phone">'.$params['mobile'].'</FL><FL val="Mobile">'.$params['mobile'].'</FL><FL val="Zip Code">'.$params['pin_code'].'</FL><FL val="Industry">'.$params['current_business'].'</FL></row></Leads>';
        $zoho_url = $url."?".$post_string;

        $params='newFormat=1&authtoken=8e0e86c663b8d6bf039da0c9fe5bfc56&scope=crmapi&xmlData='.urlencode('<Leads><row no="1"><FL val="Lead Source">'.$lead_source.'</FL><FL val="Interested In">'.$params['interest'].'</FL><FL val="Lead Status">'.$lead_state.'</FL><FL val="Company">'.$params['shop_name'].'</FL><FL val="First Name">'.$params['name'].'</FL><FL val="Last Name">'.$params['name'].'</FL><FL val="Email">'.$params['email'].'</FL><FL val="Phone">'.$params['mobile'].'</FL><FL val="Other Phone">'.$params['mobile'].'</FL><FL val="Mobile">'.$params['mobile'].'</FL><FL val="Zip Code">'.$params['pin_code'].'</FL><FL val="Industry">'.$params['current_business'].'</FL></row></Leads>');

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url.'?'.$params,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          ));

        $response = curl_exec($curl);
        $out = $this->General->xml2array($response,1,'');

        if(is_array($out) && array_key_exists('response', $out)){
            $response = $out['response']['result']['recorddetail']['FL'];

            foreach($response as $res)
            {
                if($res['attr']['val'] == 'Id')
                {
                    $zoho_lead_id = $res['value'];
                }
            }
        }

        curl_close($curl);

        $this->General->logData("/mnt/logs/zoho.txt", date('Y-m-d H:i:s') ."URL : ".$zoho_url ."Response : ".$response ."Zoho Id : ".$id);
        return $zoho_lead_id;
    }

    function getRetailerCountByDistIds($dist_ids = null){
        if($dist_ids){
            $userObj = ClassRegistry::init('Slaves');
            $res = $userObj->query('SELECT parent_id,count(id) as retailer_count FROM retailers WHERE parent_id IN('.$dist_ids.') GROUP BY parent_id');
            // echo 'SELECT parent_id,count(id) as retailer_count FROM retailers WHERE parent_id IN('.$dist_ids.') GROUP BY parent_id';
            // echo '<br>';
            if($res){
                $response = array();
                foreach($res as $data){
                    $response[$data['retailers']['parent_id']]  = $data['0']['retailer_count'];
                }
                return $response;
            }
        }
        return false;
    }

    function updateZohoLeads($params)
    {
        $pay1_products = Configure::read('Pay1_products');
        $url = 'https://crm.zoho.com/crm/private/xml/Leads/updateRecords';
        $gst_flag = ($params['gst_flag'] == 1)? 'Yes' : 'No';
        $has_curr_account = ($params['has_curr_account'] == 1)? 'Yes' : 'No';
        $products = $params['interested_products'];
        $prod_list = array();

        foreach($products as $prod)
        {
            $prod_list[] = isset($pay1_products[$prod])?$pay1_products[$prod]:'';
        }

        $post_string = 'newFormat=1&authtoken=8e0e86c663b8d6bf039da0c9fe5bfc56&scope=crmapi&id='.$params['zoho_id'].'&xmlData=<Leads><row no="1"><FL val="No of Years in Current Business">'.$params['no_of_curr_business_years'].'</FL><FL val="No of Employees">'.$params['no_of_salesmen'].'</FL><FL val="No of Retailers">'.$params['no_of_retailers'].'</FL><FL val="Annual Revenue">'.$params['curr_turnover_per_month'].'</FL><FL val="GST Registered">'.$gst_flag.'</FL><FL val="Current Account">'.$has_curr_account.'</FL><FL val="Pay1 Products">'. implode(';', $prod_list).'</FL><FL val="Lead Status">Warm</FL></row></Leads>';
        $zoho_url = $url."?".$post_string;

        $params = 'newFormat=1&authtoken=8e0e86c663b8d6bf039da0c9fe5bfc56&scope=crmapi&id='.$params['zoho_id'].'&xmlData='.urlencode('<Leads><row no="1"><FL val="No of Years in Current Business">'.$params['no_of_curr_business_years'].'</FL><FL val="No of Employees">'.$params['no_of_salesmen'].'</FL><FL val="No of Retailers">'.$params['no_of_retailers'].'</FL><FL val="Annual Revenue">'.$params['curr_turnover_per_month'].'</FL><FL val="GST Registered">'.$gst_flag.'</FL><FL val="Current Account">'.$has_curr_account.'</FL><FL val="Pay1 Products">'.implode(';', $prod_list).'</FL><FL val="Lead Status">Warm</FL></row></Leads>');

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url.'?'.$params,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          ));

        $response = curl_exec($curl);

        curl_close($curl);
        $this->General->logData("/mnt/logs/zoho.txt", date('Y-m-d H:i:s') ."Update URL : ".$zoho_url ."Response : ".$response);
    }

    function getRmCurrentSaleEarningServiceWise($group = null,$logged_in_id = null,$dist_id = null,$dist_user_id = null,$service_id = null,$year = null, $month=null,$sale_from = null,$sale_to = null){
        if($logged_in_id || $dist_id){

            $rm_sd_cond = '';
            if($logged_in_id){
                $rm_sd_cond = 'AND dist.rm_id IN ('.$logged_in_id.') ';
                if($group == MASTER_DISTRIBUTOR){
                    $rm_sd_cond = 'AND dist.parent_id IN ('.$logged_in_id.') ';
                }
            }

            $dist_cond = '';
            if($dist_id){
                $dist_cond = ' AND dist.id  IN('.$dist_id.') ';
            }

            $service_cond = '';
            if($service_id){
                if($service_id == 1){
                    $service_id = '1,2,4,6,7';
                }
                $service_cond = ' AND rel.service_id  IN('.$service_id.') ';
            }

            $year_month_cond = '';
            if( $year && $month ){
                $year_month_cond = ' AND YEAR(rel.date) = "'.$year.'" AND MONTH(rel.date) = "'.$month.'" ';
            } else if( $sale_from && $sale_to ){
                $year_month_cond = ' AND rel.date >= "'.date('Y-m-d',strtotime($sale_from)).'" AND rel.date <= "'.date('Y-m-d',strtotime($sale_to)).'" ';
            }



            $userObj = ClassRegistry::init('Slaves');
            $res = $userObj->query('SELECT  YEAR(rel.date) as year,MONTH(rel.date) as month ,dist.id,rel.service_id,sum(rel.amount) as sale,dist.gst_no
                                    FROM retailer_earning_logs rel
                                    JOIN distributors dist ON (rel.dist_user_id = dist.user_id)
                                    WHERE dist.active_flag = 1
                                    AND rel.amount !=0
                                    AND rel.type IN('.CREDIT_NOTE.','.DEBIT_NOTE.','.RETAILER_ACTIVATION.')
                                    '.$rm_sd_cond.$dist_cond.$year_month_cond.$service_cond.'
                                    GROUP BY MONTH(rel.date),YEAR(rel.date),rel.service_id,dist.id ORDER BY YEAR(rel.date) desc,MONTH(rel.date) desc');


            if($res){

                if($dist_user_id && $dist_id){
                    $dist_user_id_cond = '';
                    if($dist_user_id){
                        $dist_user_id_cond = ' AND st.source_id IN('.$dist_user_id.') ';
                    }

                    $date_cond = '';
                    if( $sale_from && $sale_to ){
                        $date_cond = ' AND st.date >= "'.date('Y-m-d',strtotime($sale_from)).'" AND st.date <= "'.date('Y-m-d',strtotime($sale_to)).'" ';
                    }

                    $service_condition = '';
                    if($service_id){
                        $service_condition = ' AND st.user_id IN ('.$service_id.') ';
                    }



                    $kitcharges = array();
                    $refunds = array();


                    $earnings = array();

                    $earning_query = 'SELECT YEAR(st.date) as year,MONTH(st.date) as month,SUM(st.amount-st.txn_reverse_amt) as amount
                                            FROM users_nontxn_logs st
                                            WHERE st.type IN('.COMMISSION_DISTRIBUTOR.')
                                            ' .$dist_user_id_cond.$date_cond.$service_condition.'

                                            GROUP BY MONTH(st.date),YEAR(st.date) ORDER BY YEAR(st.date) desc,MONTH(st.date) desc';


                    $earning_res = $userObj->query($earning_query);

                    if( count($earning_res) > 0 ){
                        foreach (array_map('current',$earning_res) as $key => $value) {
                            $earnings[$value['year']][($value['month'] < 10) ? '0'.$value['month'] : $value['month']] = $value['amount'];
                        }
                    }
                    $kitcharge_query = 'SELECT YEAR(st.date) as year,MONTH(st.date) as month,SUM(st.amount-st.txn_reverse_amt) as amount
                                            FROM users_nontxn_logs st


                                            WHERE st.type IN('.KITCHARGE.')
                                            AND st.confirm_flag = 0 ' .
                                            $dist_user_id_cond.$date_cond.$service_condition.'
                                            GROUP BY MONTH(st.date),YEAR(st.date) ORDER BY YEAR(st.date) desc,MONTH(st.date) desc';


                        $kitcharge_res = $userObj->query($kitcharge_query);

                        if( count($kitcharge_res) > 0 ){
                            foreach (array_map('current',$kitcharge_res) as $key => $value) {
                                $kitcharges[$value['year']][($value['month'] < 10) ? '0'.$value['month'] : $value['month']] = $value['amount'];
                            }
                        }

                    $refund_query = 'SELECT YEAR(st.date) as year,MONTH(st.date) as month,SUM(st.amount-st.txn_reverse_amt) as amount FROM users_nontxn_logs st



                                        WHERE st.type IN('.REFUND.')
                                        AND st.confirm_flag = 0 '.
                                        $dist_user_id_cond.$date_cond.$service_condition.'
                                        GROUP BY MONTH(st.date),YEAR(st.date) ORDER BY YEAR(st.date) desc,MONTH(st.date) desc';


                    $refund_res = $userObj->query($refund_query);
                    if( count($refund_res) > 0 ){
                        foreach (array_map('current',$refund_res) as $key => $value) {
                            $refunds[$value['year']][($value['month'] < 10) ? '0'.$value['month'] : $value['month']] = $value['amount'];
                        }
                    }
                }

                $response = array();
                    // echo '<pre>';
                    // print_r($res);
                    // exit;
                    if($dist_user_id && $dist_id){
                        Configure::load('product_config');
                        $earning_config = Configure::read('services');
                    }

                foreach ($res as $key => $value) {
                    /*if($dist_user_id && $dist_id){
                        $earning = 0;
                        if( ($value[0]['sale'] > 0) && !in_array($id,array(8,9,10,11)) ){
                            $gst_flag = (strlen($value['dist']['gst_no']) < 15) ? false : true;
                            $comm_per = $this->getServiceMargin($value['rel']['service_id'], $gst_flag);


                            $earning = ($comm_per/100) * $value[0]['sale'];
                        }
                    }*/

                    if( $value['rel']['service_id'] <= 7 ){
                        $response[$value['dist']['id']][1][$value[0]['year']][($value[0]['month'] < 10) ? '0'.$value[0]['month'] : $value[0]['month']]['sale'] += $value[0]['sale'];
                        if($dist_user_id && $dist_id){
                           // $response[$value['dist']['id']][1][$value[0]['year']][($value[0]['month'] < 10) ? '0'.$value[0]['month'] : $value[0]['month']]['earning'] += $earning;
                            $response[$value['dist']['id']][1][$value[0]['year']][($value[0]['month'] < 10) ? '0'.$value[0]['month'] : $value[0]['month']]['earning'] = $earnings[$value[0]['year']][($value[0]['month'] < 10) ? '0'.$value[0]['month'] : $value[0]['month']];
                            $response[$value['dist']['id']][1][$value[0]['year']][($value[0]['month'] < 10) ? '0'.$value[0]['month'] : $value[0]['month']]['incentive'] = $refunds[$value[0]['year']][($value[0]['month'] < 10) ? '0'.$value[0]['month'] : $value[0]['month']];
                        }
                    } else {
                        $mth = ($value[0]['month'] < 10) ? '0'.$value[0]['month'] : $value[0]['month'];
                        if(!isset($response[$value['dist']['id']][$value['rel']['service_id']][$value[0]['year']][$mth]['sale'])){
                            $response[$value['dist']['id']][$value['rel']['service_id']][$value[0]['year']][$mth]['sale'] = 0;
                        }
                        $response[$value['dist']['id']][$value['rel']['service_id']][$value[0]['year']][$mth]['sale'] += $value[0]['sale'];
                        if($dist_user_id && $dist_id){
                            //$response[$value['dist']['id']][$value['rel']['service_id']][$value[0]['year']][$mth]['earning'] += $earning;
                            $response[$value['dist']['id']][$value['rel']['service_id']][$value[0]['year']][$mth]['earning'] = $earnings[$value[0]['year']][$mth] + $kitcharges[$value[0]['year']][$mth];
                            $response[$value['dist']['id']][$value['rel']['service_id']][$value[0]['year']][$mth]['incentive'] = $refunds[$value[0]['year']][$mth] + $kitcharges[$value[0]['year']][$mth];
                        }
                    }
                }

                if($dist_user_id && $dist_id){

                    if($service_id == '1,2,4,6,7'){
                        $service_id = 1;
                    }
                    foreach ($response[$dist_id][$service_id] as $year => $monthly) {
                        foreach ($monthly as $month => $sale) {

                            $prev_month = $month-1;
                            $prev_month = ($prev_month < 10) ? '0'.$prev_month : $prev_month;
                            $inc_dec = 0;
                            if( array_key_exists( $prev_month ,$response[$dist_id][$service_id][$year]) ){
                                $diff = $sale['sale'] - $response[$dist_id][$service_id][$year][$prev_month]['sale'];
                                $inc_dec = round(($diff/$response[$dist_id][$service_id][$year][$prev_month]['sale'])*100);
                                $inc_dec = ( $inc_dec > 0 ) ? '<span style="color:green;">+'.$inc_dec.' %</span>' : '<span style="color:red;">'.$inc_dec.' %</span>';

                            }
                            $sale['inc_dec'] = $inc_dec;
                            $response[$dist_id][$service_id][$year][$month]['inc_dec'] = $inc_dec;
                        }
                    }
                }

                return $response;
            }
        }
        return false;
    }

    function getServiceMargin($service_id,$gst_flag){
        Configure::load('product_config');
        $earning_config = Configure::read('services');
        $earning_config = $earning_config[$service_id];
        $comm_per = $earning_config['variable'];
        $denom = "1.".SERVICE_TAX_PERCENT;

        if($earning_config['commission'] && $earning_config['variable'] > 0){
            if(!$gst_flag){
                if(isset($earning_config['gst_incentive']) && $earning_config['gst_incentive']){
                    $comm_per = round($comm_per/$denom,3);
                }
            }
        }

        return $comm_per;
    }

    function getTransactingRetsByRmId($group = null,$logged_in_id = null,$year = null,$month = null){
        if($group && $logged_in_id){

            $month_cond = ' AND MONTH(rel.date) = "'.date('m').'" AND YEAR(rel.date) = "'.date('Y').'" ';
            if($year && $month){
                $month_cond = $month_cond = ' AND MONTH(rel.date) = "'.$month.'" AND YEAR(rel.date) = "'.$year.'" ';
            }
            $rm_sd_cond = ' AND dist.rm_id = "'.$logged_in_id.'" ';
            if($group == MASTER_DISTRIBUTOR){
                $rm_sd_cond = ' AND dist.parent_id = "'.$logged_in_id.'" ';
            }

            $userObj = ClassRegistry::init('Slaves');
            $res = $userObj->query('SELECT ser.parent_id,dist.id,count(DISTINCT(rel.ret_user_id)) as trans_retailer_count
                                    FROM retailer_earning_logs rel
                                    JOIN distributors dist ON (rel.dist_user_id = dist.user_id)
                                    JOIN services ser ON(rel.service_id = ser.id)
                                    WHERE dist.active_flag = 1
                                    AND rel.type IN('.CREDIT_NOTE.','.DEBIT_NOTE.','.RETAILER_ACTIVATION.')
                                    '.$rm_sd_cond.'
                                    AND rel.ret_user_id != 44 '.$month_cond.' GROUP BY dist.id,ser.parent_id');

            if($res){

                $response = array();
                foreach($res as $data){
                    // if( $data['rel']['service_id'] <= 7 ){
                        // $response[$data['dist']['id']][1]  += $data['0']['trans_retailer_count'];
                    // } else {
                    if(!isset($response[$data['dist']['id']][$data['ser']['parent_id']]))$response[$data['dist']['id']][$data['ser']['parent_id']] = 0;
                        $response[$data['dist']['id']][$data['ser']['parent_id']]  += $data['0']['trans_retailer_count'];
                    // }
                }
                return $response;
            }
        }
        return false;
    }

    function getAllServices(){
           $userObj = ClassRegistry::init('Slaves');
            $result = $userObj->query('SELECT distinct parent_id,parent_name FROM services where parent_name!="" AND toShow = 1');

            $services = array();
            foreach($result as  $row){
                $services[$row['services']['parent_id']]=$row['services']['parent_name'];
            }
           //$services=array('1'=>'Recharges','8'=>'Mpos','12'=>'DMT','13'=>'SmartBuy');
           return $services;
    }

    function getServiceByParentId($id){

                   $userObj = ClassRegistry::init('Slaves');

                   $id = is_array($id) ? implode(',', $id) : $id;

                   $result = $userObj->query('SELECT  id FROM services  where toShow=1 AND parent_id IN ('.$id.')');

                   $services = array();
                   foreach($result as $val){
                       $services[]=$val['services']['id'];
                   }
//                    $services = array();
//                   $services=array('1'=>'Recharges','8'=>'Mpos','11'=>'Microfinance','12'=>'DMT','13'=>'SmartBuy');
                   return $services;
    }


    function getKitSales($group = null,$logged_in_id = null,$year = null,$month = null){

        $rm_sd_cond = ' AND dist.rm_id = "'.$logged_in_id.'" ';
        if($group == MASTER_DISTRIBUTOR){
            $rm_sd_cond = ' AND dist.parent_id = "'.$logged_in_id.'" ';
        }

        $kits = array();
        $userObj = ClassRegistry::init('Slaves');
        $kits_query = ' SELECT dist.id,YEAR(st.date) as year,MONTH(st.date) as month,SUM(target_id) as kits
                        FROM shop_transactions st
                        JOIN distributors dist
                        ON (dist.user_id = st.source_id)
                        WHERE type = '.KITCHARGE.'
                        AND MONTH(st.date) = "'.$month.'"
                        AND YEAR(st.date) = "'.$year.'"
                        '.$rm_sd_cond.'
                        GROUP BY MONTH(st.date),YEAR(st.date),dist.id';


        $kitcharge_res = $userObj->query($kits_query);
        foreach($kitcharge_res as $key => $value) {
            $kits[$value['dist']['id']][$value['0']['year']][$value['0']['month']] = $value['0']['kits'];
        }
        return $kits;

    }

    /**
     * This function will fetch user Profile information from imps tables and if not found then from retailer or distributor table as per user group
     * @param type $user_ids
     * @param type $label_type
     * @param type $input_type 0 -> input is user_id,1 -> input is mobile,2 -> input is retailer id,3 -> input is distributor id, 4 -> input is distributor id as parent_id
     * @return type
     */
   function getUserLabelData($input = null,$label_type = 2,$input_type = 0){

        $response = array();
        $input = (is_array($input)) ? addslashes(implode(',',$input)) : addslashes($input);

        $userObj = ClassRegistry::init('Slaves');
        $user_id_input_map = array();
        $user_ids = null;
        switch ($input_type) {
            case 0: // 0 -> input is user_id
                $user_ids = $input;
                break;
            case 1: // 1 -> input is mobile
                $get_user_id_query = 'SELECT id,mobile FROM users WHERE mobile IN ('.$input.')';
                $user_id_res = $userObj->query($get_user_id_query);
                if( count($user_id_res) > 0 ){
                    foreach ($user_id_res as $key => $value) {
                        $user_id_input_map[$value['users']['id']] = $value['users']['mobile'];
                    }
                    $user_ids = implode(',',array_keys($user_id_input_map));
                }

                break;
            case 2: // 2 -> input is retailer id
                $get_user_id_query = 'SELECT user_id,id FROM retailers WHERE id IN ('.$input.')';
                $user_id_res = $userObj->query($get_user_id_query);

                if( count($user_id_res) > 0 ){
                    foreach ($user_id_res as $key => $value) {
                        $user_id_input_map[$value['retailers']['user_id']] = $value['retailers']['id'];
                    }
                    $user_ids = implode(',',array_keys($user_id_input_map));
                }
                break;
            case 3: // 3 -> input is distributor id
                $get_user_id_query = 'SELECT user_id,id FROM distributors WHERE id IN ('.$input.')';
                $user_id_res = $userObj->query($get_user_id_query);
                if( count($user_id_res) > 0 ){
                    foreach ($user_id_res as $key => $value) {
                        $user_id_input_map[$value['distributors']['user_id']] = $value['distributors']['id'];
                    }
                    $user_ids = implode(',',array_keys($user_id_input_map));
                }
                break;
            case 4: // 4 -> input is distributor id as parent_id
                $get_user_id_query = 'SELECT user_id,id FROM retailers WHERE parent_id IN ('.$input.')';
                $user_id_res = $userObj->query($get_user_id_query);

                if( count($user_id_res) > 0 ){
                    foreach ($user_id_res as $key => $value) {
                        $user_id_input_map[$value['retailers']['user_id']] = $value['retailers']['id'];
                    }
                    $user_ids = implode(',',array_keys($user_id_input_map));
                }
                break;

            default:
                break;
        }

        $redis_keys = array();
        foreach(explode(",",$user_ids) as  $user_id){
            $redis_keys[] = "impdata_".$input_type."_".$user_id;
        }

        $redis = $this->redis_connect();
        $ret = $redis->mGet($redis_keys);
        $response = array();

        $i = 0;
        foreach(explode(",",$user_ids) as  $user_id){
            $data = json_decode($ret[$i],true);

            if( ($data === false) || empty($data) ){
                $data = $this->getUserLabel($label_type,$input_type,$user_id,$user_id_input_map,$userObj);
            }

            $keys = array_keys($data);
            $key = $keys[0];
            $response[$key] = $data[$key];
            $i++;
        }

        return $response;
    }


    /**
     * This function will fetch user Profile information from imps tables and if not found then from retailer or distributor table as per user group
     * @param type $user_ids
     * @param type $label_type
     * @param type $input_type 0 -> input is user_id,1 -> input is mobile,2 -> input is retailer id,3 -> input is distributor id, 4 -> input is distributor id as parent_id
     * @return type
     */
    function getUserLabel($label_type = 2,$input_type = 0,$user_ids,$user_id_input_map,$userObj){

        $response = array();

        if($user_ids){


            Configure::load('platform');
            $labels = $this->Documentmanagement->getImpLabels();

            $label_ids = null;
            if($label_type){
                $label_ids = array_keys(array_filter($labels,function($label) use ($label_type){
                    return $label['type'] == $label_type;
                }));
            }
            foreach (explode(',',$user_ids) as $user_id) {


                $u_key = (($input_type != 0) && array_key_exists($user_id,$user_id_input_map)) ? $user_id_input_map[$user_id] : $user_id;
                foreach ($label_ids as $label_id) {
                    $response[$u_key]['imp'][$labels[$label_id]['key']] = null;
                }


            }

            $label_info = $this->Documentmanagement->userStatusCheck(explode(',',$user_ids),$label_ids,1);

            if (count($label_info) > 0){
                foreach ($label_info as $user_id=>$label_data){
                    foreach ($label_data as $label_id=>$data){
                        $response[(($input_type != 0) && array_key_exists($user_id,$user_id_input_map)) ? $user_id_input_map[$user_id] : $user_id]['imp'][$labels[$label_id]['key']]= $data['description'];
                    }
                }
            }


            $user_groups_query = ' SELECT user_id,group_id
                            FROM user_groups
                            WHERE user_id IN('.$user_ids.')
                            AND group_id IN('.DISTRIBUTOR.','.RETAILER.')';

            $groups = $userObj->query($user_groups_query);
            $user_groups = array();
            foreach (array_map('current',$groups) as $key => $value) {
                $user_groups[$value['group_id']][] = $value['user_id'];
            }

            $dist_data = array();
            if( array_key_exists(DISTRIBUTOR,$user_groups) ){
                $dist_query = ' SELECT *
                            FROM distributors
                            WHERE user_id IN('.implode(',',$user_groups[DISTRIBUTOR]).')';

                $distributor = $userObj->query($dist_query);
                foreach ($distributor as $key => $value) {
                    $dist_data[(($input_type != 0) && array_key_exists($value['distributors']['user_id'],$user_id_input_map)) ? $user_id_input_map[$value['distributors']['user_id']] : $value['distributors']['user_id']] = $value['distributors'];
                }
            }

            $retailer_data = array();
            if( array_key_exists(RETAILER,$user_groups) ){
                $ret_query = ' SELECT *
                            FROM retailers
                            WHERE user_id IN('.implode(',',$user_groups[RETAILER]).')';

                $retailer = $userObj->query($ret_query);
                foreach ($retailer as $key => $value) {
                    $retailer_data[(($input_type != 0) && array_key_exists($value['retailers']['user_id'],$user_id_input_map)) ? $user_id_input_map[$value['retailers']['user_id']] : $value['retailers']['user_id']] = $value['retailers'];
                }
            }

            $imp_dist_label_map = array(
                'pan_no' => 'pan_number',
                'shop_est_name' => 'company',
                'alternate_mobile_no' => 'alternate_number',
                'email_id' => 'email'
            );
            $imp_retailer_label_map = array(
                'pan_no' => 'pan_number',
                'shop_est_name' => 'shopname',
                'alternate_mobile_no' => 'alternate_number',
                'email_id' => 'email',
                'shop_ownership' => 'shop_structure',
                'business_nature' => 'shop_type'
            );

            foreach($response as $user_id => $data){

                if( array_key_exists($user_id,$dist_data) ){
                    foreach ($data['imp'] as $imp_label_key => $imp_label_value) {
                        $imp_label_key_mapped = ( array_key_exists($imp_label_key,$imp_dist_label_map) ) ? $imp_dist_label_map[$imp_label_key] : $imp_label_key;
                        if( array_key_exists($imp_label_key_mapped,$dist_data[$user_id]) ){
                            if( !empty($imp_label_value) ){
                                unset($dist_data[$user_id][$imp_label_key_mapped]);
                            } else {
                                $response[$user_id]['imp'][$imp_label_key] = $dist_data[$user_id][$imp_label_key_mapped];
                                unset($dist_data[$user_id][$imp_label_key_mapped]);
                            }
                        }
                    }
                    $response[$user_id]['dist'] = $dist_data[$user_id];
                }
                if( array_key_exists($user_id,$retailer_data) ){
                    foreach ($data['imp'] as $imp_label_key => $imp_label_value) {
                        $imp_label_key_mapped = ( array_key_exists($imp_label_key,$imp_retailer_label_map) ) ? $imp_retailer_label_map[$imp_label_key] : $imp_label_key;
                        if( array_key_exists($imp_label_key_mapped,$retailer_data[$user_id]) ){
                            if( !empty($imp_label_value) ){
                                unset($retailer_data[$user_id][$imp_label_key_mapped]);
                            } else {
                                $response[$user_id]['imp'][$imp_label_key] = $retailer_data[$user_id][$imp_label_key_mapped];
                                unset($retailer_data[$user_id][$imp_label_key_mapped]);
                            }
                        }
                    }
                    $response[$user_id]['ret'] = $retailer_data[$user_id];
                }

            }
            $this->General->logData("user_data.txt",json_encode($response));

            $redis = $this->redis_connect();
            $redis->set("impdata_".$input_type."_".$user_ids,json_encode($response));

        }

        return $response;
    }


    /**
     * This function will update user profile information in IMP tables
     * @param type $dist_user_id
     * @param type $data
     * @param type $updated_by
     * @return boolean
     */
    function updateUserLabelData($input = null,$data = array(),$updated_by = null,$input_type = 0){

        $input = (is_array($input)) ? addslashes(implode(',',$input)) : addslashes($input);
        $userObj = ClassRegistry::init('Slaves');
        $user_id_input_map = array();
        $user_ids = null;
        switch ($input_type) {
            case 0: // 0 -> input is user_id
                $user_ids = $input;
            break;
            case 1: // 1 -> input is mobile
                $get_user_id_query = 'SELECT id,mobile FROM users WHERE mobile IN ('.$input.')';
                $user_id_res = $userObj->query($get_user_id_query);
                if( count($user_id_res) > 0 ){
                    foreach ($user_id_res as $key => $value) {
                        $user_id_input_map[$value['users']['id']] = $value['users']['mobile'];
                    }
                    $user_ids = implode(',',array_keys($user_id_input_map));
                }

            break;
            case 2: // 2 -> input is retailer id
                $get_user_id_query = 'SELECT user_id,id FROM retailers WHERE id IN ('.$input.')';
                $user_id_res = $userObj->query($get_user_id_query);

                if( count($user_id_res) > 0 ){
                    foreach ($user_id_res as $key => $value) {
                        $user_id_input_map[$value['retailers']['user_id']] = $value['retailers']['id'];
                    }
                    $user_ids = implode(',',array_keys($user_id_input_map));
                }
            break;
            case 3: // 3 -> input is distributor id
                $get_user_id_query = 'SELECT user_id,id FROM distributors WHERE id IN ('.$input.')';
                $user_id_res = $userObj->query($get_user_id_query);
                if( count($user_id_res) > 0 ){
                    foreach ($user_id_res as $key => $value) {
                        $user_id_input_map[$value['distributors']['user_id']] = $value['distributors']['id'];
                    }
                    $user_ids = implode(',',array_keys($user_id_input_map));
                }
            break;
            case 4: // 4 -> input is distributor id as parent_id
                $get_user_id_query = 'SELECT user_id,id FROM retailers WHERE parent_id IN ('.$input.')';
                $user_id_res = $userObj->query($get_user_id_query);

                if( count($user_id_res) > 0 ){
                    foreach ($user_id_res as $key => $value) {
                        $user_id_input_map[$value['retailers']['user_id']] = $value['retailers']['id'];
                    }
                    $user_ids = implode(',',array_keys($user_id_input_map));
                }
            break;

            default:
            break;
        }

        if($user_ids && (count($data) > 0) ){
            Configure::load('platform');
            $labels = $this->Documentmanagement->getImpLabels();

            $imp_label_map = array(
                'pan_number' => 'pan_no',
                'shopname' => 'shop_est_name',
                'company' => 'shop_est_name',
                'alternate_number' => 'alternate_mobile_no',
                'email' => 'email_id',
                'shop_structure' => 'shop_ownership',
                'retailer_type' => 'business_nature',
                'location_type'=>'location_type',
                'turnover_type'=>'annual_turnover',
                'ownership_type'=>'shop_ownership'
            );

            foreach ($data as $label_key => $label_value) {
                $imp_label_key_mapped = ( array_key_exists($label_key,$imp_label_map) ) ? $imp_label_map[$label_key] : $label_key;
                $label_id = array_search($imp_label_key_mapped,array_map(function($elm){return $elm['key'];},$labels));
                if($label_id){
                    $response = $this->Documentmanagement->updateTextualInfo($user_ids,$label_id,0,$label_value,$updated_by);
                }
            }

            return true;
        }
        return false;
    }


    function getAllDistributors($primKey,$columns,$displayColumn,$condition =  ' 1=1 '){
         $userObj = ClassRegistry::init('Slaves');
         $columns = implode(',', $columns);
        $sql = "SELECT  $columns FROM distributors where active_flag=1 AND $condition";
        $result = $userObj->query($sql);
        $distributors = array();
        foreach($result  as $row){
            $distributors[$row['distributors'][$primKey]] = $row['distributors'][$displayColumn];
        }
        return $distributors;
    }


    function getDsitributors(){
        $userObj = ClassRegistry::init('Slaves');
        $sql = "SELECT  id,user_id,name,company FROM distributors where active_flag=1";
        $result = $userObj->query($sql);
        $distributors = array();
        foreach($result as $row){
            $distributors[$row['distributors']['id']] = array('company'=>$row['distributors']['company'],'user_id'=>$row['distributors']['user_id']);
        }
        return $distributors;

    }


    function getRmGraphData($condition,$from,$to){
        $userObj = ClassRegistry::init('Slaves');


        $sql = "SELECT round(sum(amount)) as sale,count(distinct retailer_earning_logs.ret_user_id) as transaction_retailer,date,sum(txn_count) as no_of_transaction  "



                                    . "FROM `retailer_earning_logs`,distributors,users "
                                    . "WHERE $condition AND  retailer_earning_logs.dist_user_id=distributors.user_id AND distributors.user_id=users.id AND `date` >= '{$from}'  AND `date` <= '{$to}'  group by date";
         $result = $userObj->query($sql);

        $distributors = array();
        $transactionRetailers = array();
        foreach($result  as $row){
            $distributors['sale'][] = array($row['retailer_earning_logs']['date'],$row[0]['sale'],$row[0]['transaction_retailer'],round($row[0]['sale']/$row[0]['transaction_retailer']));
            $distributors['trans_retailers'][]   =array($row['retailer_earning_logs']['date'],$row[0]['transaction_retailer']);
            $distributors['avg_sale'][]   =array($row['retailer_earning_logs']['date'],round($row[0]['sale']/$row[0]['transaction_retailer']));
            $distributors['no_of_transaction'][]   =array($row['retailer_earning_logs']['date'],$row[0]['no_of_transaction']);
        }

        return $distributors;
    }
    /*
     * get dist id and show list of rm under that dist
     */
    function getRmList($distId=null){
        $sql = "SELECT rm.id,rm.name,rm.mobile FROM rm left join users ON (users.id = rm.user_id) where rm.active_flag = 1 and users.active_flag = 1";
        $userObj = ClassRegistry::init('Slaves');
        $result = $userObj->query($sql);

        $rmList = array();
        foreach($result as $row){
            $rmList[$row['rm']['id']] = $row['rm'];
        }
        return $rmList;
    }

    function reactivateService($user_id,$service_id,$service_flag,$next_rental_debit_date)
    {
        if(in_array($service_id, array(8,10)))
        {
            $obj = ClassRegistry::init('Smartpay');
            $result = $obj->query('UPDATE users_services SET service_flag = '.$service_flag.',next_rental_debit_date = '.$next_rental_debit_date.',updated_at = "'.date('Y-m-d H:i:s').'" WHERE user_id = '.$user_id.' AND service_id = '.$service_id.' AND service_flag = 2 ');
        }

        if($obj->getAffectedRows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function commissionCalculation($user_id,$service_id,$product_id,$amount,$vendor_amount,$vendor_service_charge=0,$vendor_commission=0){
        $obj = ClassRegistry::init('Slaves');
        $plan = $obj->query("SELECT spp.ret_params,products.earning_type from users_services left join service_plans on (service_plans.id = users_services.service_plan_id) left join service_product_plans as spp on (spp.service_plan_id = service_plans.id) left join products on (products.id = spp.product_id) WHERE user_id = '$user_id' AND service_plans.service_id = '$service_id' AND spp.product_id = '$product_id'");
        $plan_params = json_decode($plan[0]['spp']['ret_params'],true);

        if(empty($plan_params)) return;

        $comm = $this->calculateCommission($amount, $plan_params);
        $comm = $comm['comm'];

        if($service_id == 12 && in_array($product_id,array(84,215))){
            $comm = $this->calculateCommissionDMT($amount, $plan_params);
            $service_charge = $comm['service_charge'];
            $commission = $comm['commission'];
            $tax = $this->calculateTDS($commission);
        }
        else if(empty($vendor_amount)){
            $commission = 0;
            if($plan[0]['products']['earning_type']==2){//service charge
                $service_charge = $comm;
            }
            else if($plan[0]['products']['earning_type']==1){//commission
                $commission = $comm;
                $tax = $this->calculateTDS($commission);
            }
            else if($plan[0]['products']['earning_type']==0){//discount .. no tds
                $commission = $comm;
            }
        }
        else{

            if($vendor_service_charge > 0){//service charge scenario
                $service_charge = $vendor_service_charge + $comm;
                $commission = $vendor_commission;

                $vendor_service_charge = $this->calculateServiceTax($vendor_service_charge);
            }
            else if ($vendor_commission > 0){
                if($vendor_commission >= $comm){
                    $commission=$vendor_commission-$comm;
                    $service_charge = 0;
                }
                else{
                    $service_charge = $comm - $vendor_commission;
                    $commission = 0;
                }
            }
            else {
                $service_charge = $comm;
                $commission = 0;
            }

            $vendor_tds = $this->calculateTDS($vendor_commission);
            $vendor_amount = $vendor_amount + $vendor_tds;

            if($commission > 0){
                $tax = $this->calculateTDS($commission);
            }

            if($service_charge > 0){
                $service_charge = $this->calculateServiceTax($service_charge);
            }
        }

        return array('commission'=>$commission,'service_charge'=>$service_charge,'tax'=>$tax,'amount'=>$amount,'vendor_amount'=>$vendor_amount,'vendor_commission'=>$vendor_commission,'vendor_service_charge'=>$vendor_service_charge);
    }

    function calculateCommissionDMT($sale,$config,$vendor_id=null){
        $service_charge_per=DMT_SERVICE_CHARGE_PER;
        if(empty($config)) return array('service_charge'=>0,'commission'=>0);
        else {
            $margin = array();
            foreach($config as $key=>$val){
                $range = explode("-", $key);
                if($range[0] != ''){
                    if($range[0] == 0 && $range[1] == 0){
                        $margin = $val;
                        break;
                    }
                    else if((int)($sale) >= $range[0] && (int)$sale <= $range[1]){
                        $margin = $val;
                        break;
                    }
                }
            }

            if(!empty($margin)){
                $var = $margin['margin'];
                $max = $margin['max'];
                $min = $margin['min'];
                $type = 0;//0 for sale, 1 for count

                if(strpos($var, '%') === false){
                    $value = $var;
                    $type = 1;
                }
                elseif(strpos($var, '%') !== false){
                    $var = rtrim($var,'%');
                    $value = $var * $sale/100;
                }

                $service_charge = max($sale * $service_charge_per/100,DMT_SERVICE_CHARGE_MIN);
                $commission = $value;

                if($min > 0 && empty($vendor_id)){
                    if($service_charge <= $min){
                        $service_charge = $min;
                        $commission = 0;
                    }
                    else {
                        $value = $service_charge - $commission;

                        if($value < $min){
                            $commission = $service_charge - $min;
                        }
                    }
                }
                else {
                    $denom = "1." . SERVICE_TAX_PERCENT;
                    $commission = $service_charge/$denom - $commission;
                    
                    if(!empty($vendor_id) && $vendor_id == 4 && $sale > 3000){//eko
                        $commission = $commission - max($sale*0.067/100,0.54);
                    }
                }
                $commission = round($commission,2);
                $service_charge = round($service_charge,2);

                return array('commission'=>$commission,'service_charge'=>$service_charge,'type'=>$type);
            }
        }

        return array('service_charge'=>0,'commission'=>0);
    }

    function calculateCommission($sale,$config){
        if(empty($config)) return array('comm'=>0);
        else {
            $margin = array();
            foreach($config as $key=>$val){
                $range = explode("-", $key);
                if($range[0] != ''){
                    if($range[0] == 0 && $range[1] == 0){
                        $margin = $val;
                        break;
                    }
                    else if((int)($sale) >= $range[0] && (int)$sale <= $range[1]){
                        $margin = $val;
                        break;
                    }
                }
            }

            if(!empty($margin)){
                $var = $margin['margin'];
                $max = $margin['max'];
                $min = $margin['min'];
                $type = 0;//0 for sale, 1 for count

                if(strpos($var, '%') === false){
                    $value = $var;
                    $type = 1;
                }
                elseif(strpos($var, '%') !== false){
                    $var = rtrim($var,'%');
                    $value = $var * $sale/100;
                }

                if($min > 0 && $value < $min){
                    $value = $min;
                }

                if($max > 0 && $value > $max){
                    $value = $max;
                }

                return array('comm'=>$value,'type'=>$type);
            }
        }

        return array('comm'=>0);
    }

    function calculateTdsWithGst($amount,$gst_flag){
        if($gst_flag){
            $denom = "1." . SERVICE_TAX_PERCENT;
            $tds = ($amount / $denom) * TDS_PERCENT / 100;
        }
        else{
            $tds = $amount * TDS_PERCENT / 100;
        }

        return $tds;
    }

    function updateServiceStatus($user_id,$service_id,$data)
    {
        if(in_array($service_id, array(8,10)))
        {
            $obj = ClassRegistry::init('Smartpay');
            $result = $obj->query('UPDATE users_services SET kit_flag = '.$data['kit_flag'].',service_flag = '.$data['service_flag'].',updated_at = "'.date('Y-m-d H:i:s').'" WHERE user_id = '.$user_id.' AND service_id = '.$service_id.' ');
        }

        if($obj->getAffectedRows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function getSmartpayBankTxnDetails($smartpay_txn_ids = array()){
        $txn_details = array();
        if( count($smartpay_txn_ids) > 0 ){
            $query = "  SELECT wt.txn_id,wt.server,wt.user_id,wt.product_id,wt.cr_db,wt.amt_remaining_settlement,st.user_id as service_id
                        FROM wallets_transactions wt
                        LEFT JOIN shop_transactions st
                        ON(wt.shop_transaction_id = st.id)
                        WHERE wt.server = 'smartpay'
                        AND wt.txn_id IN('".implode("','",$smartpay_txn_ids)."')";
            $userObj = ClassRegistry::init('Slaves');
            $result = $userObj->query($query);

            if( count($result) > 0 ){
                foreach ($result as $txn) {
                    $txn_details[$txn['wt']['txn_id']]['txn_id'] = $txn['wt']['txn_id'];
                    $txn_details[$txn['wt']['txn_id']]['server'] = $txn['wt']['server'];
                    $txn_details[$txn['wt']['txn_id']]['user_id'] = $txn['wt']['user_id'];
                    $txn_details[$txn['wt']['txn_id']]['product_id'] = $txn['wt']['product_id'];
                    $txn_details[$txn['wt']['txn_id']]['type'] = $txn['wt']['cr_db'];
                    // $txn_details[$txn['wt']['txn_id']]['amt_remaining_settlement'] = $txn['wt']['amt_remaining_settlement'];
                    $txn_details[$txn['wt']['txn_id']]['service_id'] = $txn['st']['service_id'];
                }
            }
        }
        return $txn_details;
    }
    function settleBankTxn($txn_details = array(),$dataSource = null){
        if( count($txn_details) > 0 ){
            return $this->Bridge->walletApi($txn_details,$dataSource);
        }
        return array('status' => 'failure');
    }

    function deductRental($params,$dataSource,$balance_check = true)
    {
        Configure::load('product_config');
        $services = Configure::read('services');

//        if($balance_check){
//            $closing_bal = $this->shopBalanceUpdate($params['amount'],'subtract',$params['user_id'],null,$dataSource,1,0);
//
//            if($closing_bal === false){
//                 return array('status'=>'failure','errCode'=>'105','description'=>'Insufficient wallet balance');
//            }
//        } else {
            $balance = $dataSource->query('SELECT balance FROM users WHERE id = '.$params['user_id'].' ');
            if($balance[0]['users']['balance'] >= $params['amount']){
                $response = $dataSource->query('UPDATE users SET balance = balance - '.$params['amount'].' WHERE id = '.$params['user_id']);
                if(!$response){
                    return array('status'=>'failure','description'=>'Something went wrong. Please try again');
                }
            }else{
                return array('status'=>'failure','errCode'=>'105','description'=>'Insufficient wallet balance');
            }
//        }

        $description = $params['deducted_month'];
        $trans_id = $this->shopTransactionUpdate(RENTAL,$params['amount'],$params['user_id'],null,$params['service_id'],null,0,$description,$closing_bal+$params['amount'],$closing_bal,null,null,$dataSource);

        if($trans_id === false) return array('status'=>'failure','errCode'=>'106','description'=>'Transaction entry is not created');

        $dataSource->query("INSERT INTO rentals VALUES (NULL," . $params['user_id'] . ",".$trans_id.",".$params['amount'].",".$params['amount'].",".$params['amount'].",'','','" . date('Y-m-d') . "')");

        return array('status'=>'success','closing'=>$closing_bal, 'shop_transaction_id'=>$trans_id, 'amt_settled'=>$params['amount'], 'type'=>$params['type']);
    }

    function setNextRentalDebitDate($user_id,$service_id,$next_rental_debit_date,$dataSource)
    {
        //set next rental date
        $response = $dataSource->query('UPDATE users_services SET next_rental_debit_date = "'.$next_rental_debit_date.'" WHERE user_id = '.$user_id.' AND service_id = '.$service_id.' ');
        if($response)
        {
            return array('status'=>'success');
        }
        else
        {
            return array('status'=>'failure');
        }
    }

    function sendNotification($user_id,$service_id,$mobile,$amount,$next_rental_debit_date,$deducted_month,$mode)
    {
        $filename = "rental_details_".date('Ymd').".txt";
        $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": next rental due on :: ".$next_rental_debit_date);
        $services = $this->Serviceintegration->getAllServices();
        $services = json_decode($services,true);
//        $mobile = $user_data['data']['mobile'];
        $MsgTemplate = $this->General->LoadApiBalance();
        $paramdata['AMOUNT'] = $amount;
        $paramdata['MONTH'] = $deducted_month;
        $paramdata['SERVICE_NAME'] = $services[$service_id];
        $paramdata['REACTIVATE'] = '';
        if( !empty($mode) && $mode == 'reactivate' )
        {
            $paramdata['REACTIVATE'] = ".Your ".$paramdata['SERVICE_NAME']." service has been reactivated as well.";
        }
        $content = $MsgTemplate['RENTAL_DEDUCT_TEMPLATE'];
        $message = $this->General->ReplaceMultiWord($paramdata, $content);
        $this->General->sendMessage($mobile, $message, 'notify');
        $this->General->logData("/mnt/logs/".$filename, date('Y-m-d H:i:s') . ": sms sent to user :: ".$message);
    }

    function deactivateService($user_id,$service_id,$dataSource)
    {
        $response = $dataSource->query('UPDATE users_services SET service_flag = 2 WHERE user_id = '.$user_id.' AND service_id = '.$service_id.' ');
        if($response)
        {
            return array('status'=>'success');
        }
        else
        {
            return array('status'=>'failure');
        }
    }

    function getBankTxnDetails($user_id,$service_id,$amount)
    {
        $userObj = ClassRegistry::init('Slaves');
        $current_date = date('Y-m-d');

        $txn_details = array();
        $query = 'SELECT wt.txn_id,wt.user_id,wt.product_id,wt.cr_db AS type,wt.server,wt.amt_remaining_settlement,wt.date,p.service_id '
                . 'FROM wallets_transactions wt '
                . 'JOIN products p ON (wt.product_id = p.id) '
                . 'WHERE wt.user_id = '.$user_id.' '
                . 'AND DATE_ADD(wt.date,INTERVAL +1 day) >= "'.$current_date.'" '
                . 'AND wt.amt_remaining_settlement >= '.$amount.' '
                . 'AND p.service_id = '.$service_id.' '
                . 'ORDER BY wt.date DESC '
                . 'LIMIT 1';

        $result = $userObj->query($query);
        if( !empty($result) ){
            $txn_details = $result[0]['wt'];
        }

        return $txn_details;
    }

    function updateRecoveryInfo($user_id,$service_id,$param1,$amount,$type,$dataSource)
    {
        $rec_data = $dataSource->query('SELECT * FROM recovery_info rc WHERE user_id = '.$user_id.' AND service_id = '.$service_id.' ORDER BY id desc LIMIT 1 ');

        if(empty($rec_data) || (!empty($rec_data) && $rec_data[0]['rc']['recovery_date'] != null))
        {
            $update_rec_data = $dataSource->query('INSERT INTO recovery_info(user_id,service_id,param1,recovered_amt,type,recovery_date,recovered_at)'
                    . 'VALUES('.$user_id.','.$service_id.',"'.$param1.'",'.$amount.','.$type.',"'.date("Y-m-d").'","'.date("Y-m-d H:i:s").'")');

            if($update_rec_data){
                $ref_id = $dataSource->lastInsertId();
            }
            else {
                $ref_id = false;
            }

            if($ref_id){
                $response = array('status'=>'success','ref_id'=>$ref_id);
            }
            else {
                $response = array('status'=>'failure');
            }
        }
        else
        {
            $ref_id = $rec_data[0]['rc']['id'];
            $update_rec_data = $dataSource->query('UPDATE recovery_info '
                    . 'SET recovered_amt = '.$amount.',type = '.$type.',recovery_date = "'.date("Y-m-d").'",recovered_at = "'.date("Y-m-d H:i:s").'" '
                    . 'WHERE id = '.$ref_id.' ');

            if($update_rec_data) {
                $response = array('status'=>'success','ref_id'=>$ref_id);
            }
            else {
                $response = array('status'=>'failure');
            }
        }
        return $response;
    }

    function getMonthListFromDate($date,$count)
    {
        $ret = array();

        for($i=0; $i < $count; $i++){
            $next = date('F-y',strtotime($date.'+'.$i.' month'));
            $ret[] = $next;
        }

        return $ret;
    }

    function getLocationDetails($long = null,$lat = null){
        $userObj = ClassRegistry::init('Slaves');
        $response = array('area'=>'','city'=>'','pincode'=>'','state'=>'');

        if($lat && $long){
            $area_id = $this->General->getAreaIDByLatLong($long,$lat);
            if($area_id > 0){
                $area_query = 'SELECT id,name,city_id,pincode FROM locator_area WHERE id = '.$area_id;
                $areas_temp = $userObj->query($area_query);

                $city_id = $areas_temp[0]['locator_area']['city_id'];
                $area = $areas_temp[0]['locator_area']['name'];
                $pincode = $areas_temp[0]['locator_area']['pincode'];

                $city_query = 'SELECT id,name,state_id FROM locator_city WHERE id = '.$city_id;
                $cities_temp = $userObj->query($city_query);

                $city = $cities_temp[0]['locator_city']['name'];
                $state_id = $cities_temp[0]['locator_city']['state_id'];

                $state_query = 'SELECT id,name FROM locator_state WHERE id = '.$state_id;
                $states_temp = $userObj->query($state_query);
                $state = $states_temp[0]['locator_state']['name'];
                $response = array('area'=>$area,'city'=>$city,'pincode'=>$pincode,'state'=>$state);
            }
        }

        return $response;
    }

    function getParentIds($user_ids){
        $userObj = ClassRegistry::init('Slaves');
        $response = array();
        $ret_parent_id = $userObj->query('SELECT r.user_id,d.user_id AS parent_id '
                . 'FROM retailers r '
                . 'JOIN distributors d ON (r.parent_id = d.id) '
                . 'WHERE r.user_id IN ('.implode(',', $user_ids).')');

        if(!empty($ret_parent_id)){
            foreach($ret_parent_id as $data){
                $response[$data['r']['user_id']] = $data['d']['parent_id'];
            }
        }

        $slmn_parent_id = $userObj->query('SELECT s.user_id,d.user_id AS parent_id '
                . 'FROM salesmen s '
                . 'JOIN distributors d ON (s.dist_id = d.id) '
                . 'WHERE s.user_id IN ('.implode(',', $user_ids).')');

        if(!empty($slmn_parent_id)){
            foreach($slmn_parent_id as $data){
                $response[$data['s']['user_id']] = $data['d']['parent_id'];
            }
        }

        $dist_parent_id = $userObj->query('SELECT d.user_id,sd.user_id AS parent_id '
                . 'FROM distributors d '
                . 'JOIN master_distributors sd ON (d.parent_id = sd.id) '
                . 'WHERE d.user_id IN ('.implode(',', $user_ids).')');

        if(!empty($dist_parent_id)){
            foreach($dist_parent_id as $data){
                $response[$data['d']['user_id']] = $data['sd']['parent_id'];
            }
        }

        $sd_parent_id = $userObj->query('SELECT sd.user_id,md.user_id AS parent_id '
                . 'FROM super_distributors sd '
                . 'JOIN master_distributors md ON (sd.parent_id = md.id) '
                . 'WHERE sd.user_id IN ('.implode(',', $user_ids).')');

        if(!empty($sd_parent_id)){
            foreach($sd_parent_id as $data){
                $response[$data['sd']['user_id']] = $data['md']['parent_id'];
            }
        }
        return $response;
    }
}
?>
