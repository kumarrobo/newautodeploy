<?php
 
class GCM extends AppHelper{
	
	
    private $CREDS = array(
    	'merchant' 			=> array(	
	    	'gcm_url' 			=> 'https://android.googleapis.com/gcm/send', 
	    	'google_api_key' 	=> "AIzaSyDGxHufeW2uyiYyxz8BMOILVd5EWognhyg"
    	),
            'merchant_new' 			=> array(
                    'gcm_url' 			=> 'https://fcm.googleapis.com/fcm/send',
                    'google_api_key' 	=> "AIzaSyCZLHpXPOc_PqKl8AMfn0iEF7IdPIziZjE"
            ),
    	'channel_partner' 	=> array(
    		//'gcm_url' 			=> 'https://gcm-http.googleapis.com/gcm/send',
    		//'google_api_key' 	=> "AIzaSyBk7XDeCYesfqrN57YCfAEaSmDMkOvcWBI"
                                     'gcm_url' 			=> 'https://fcm.googleapis.com/fcm/send',
    		'google_api_key' 	=> "AIzaSyCS7v_CmccFmcEkzk-yZT-dFSDs7uDOLjc"
    	)	
    ); 
    
	private $GCM_URL;
    private $GOOGLE_API_KEY;
    
    function __construct($ANDROID_APP = 'merchant'){
    	$this->CREDS[$ANDROID_APP] && $this->GCM_URL = $this->CREDS[$ANDROID_APP]['gcm_url'];
    	$this->CREDS[$ANDROID_APP] && $this->GOOGLE_API_KEY = $this->CREDS[$ANDROID_APP]['google_api_key'];
    }
    
    public function send_notification($registatoin_ids, $message) {
              
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );
 
        $headers = array(
            'Authorization: key=' . $this->GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->GCM_URL);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3');
		
        // Execute post
        $result = curl_exec($ch);
        $response = array();
        if ($result === FALSE || empty($result)) {
            $response['status'] = "failure";
            $response['error_code'] = curl_errno($ch);
            $response['description'] = curl_error($ch);
            curl_close($ch);
            return $response;//null;//           
        }
        else {
        	curl_close($ch);
        	
	        $result = json_decode($result,true);
	        if($result['success'] == '0'){
	        	$response['status'] = "failure";
	        }
	        else {
	        	$response['status'] = "success";
	        }
	        $response['error_code'] = "";
	        $response['description'] = $result;
	        return $response;
        }
       
    }
 
}
?>