
<?php
class WEBPN extends AppHelper{
 
    //put your code here
    // constructor
    /*function __construct() {
         
    }*/
 
    /**
     * Sending Push Notification
     */
    //private $url = 'https://android.googleapis.com/gcm/send';
    //private $WEBPN_URL = 'http://rx.pay1.in:5001/webnotify';
    private $WEBPN_URL = 'http://www.smstadka.com:5001/webnotify';
    //private $WEBPN_URL = 'http://192.168.0.38:9009/webnotify';
    /*private $DB_HOST = "localhost";
    private $DB_USER = "root";
    private $DB_PASSWORD = "root";
    private $DB_DATABASE = "gcm";*/ 
    /*
     * Google API Key
     */
    //private $GOOGLE_API_KEY = "AIzaSyDGxHufeW2uyiYyxz8BMOILVd5EWognhyg"; // Place your Google API Key

    public function send_notification($users,  $message) {
        // Create the toast message
        // Create request to send
        
        $message = urlencode($message);
        $r = curl_init();
        $url = $this->WEBPN_URL."/$users/$message";
        curl_setopt($r, CURLOPT_URL,$url);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_POST, false);
        //curl_setopt($ch, CURLOPT_HEADER, true); 

        // add headers
        //$httpHeaders=array('Content-type: text/xml; charset=utf-8', 'X-WindowsPhone-Target: toast',
        //                'Accept: application/*', 'X-NotificationClass: 2','Content-Length:'.strlen($toastMessage));
        //curl_setopt($r, CURLOPT_HTTPHEADER, $httpHeaders);
        
        // add message
        //curl_setopt($r, CURLOPT_POSTFIELDS, $toastMessage);
        
        // execute request
        $output = curl_exec($r);
        if($output == FALSE){
            $output = "SUCCESS".":".$output;            
        }else{
            $errno = curl_errno($r);
            $error =  curl_error($r);
            $output = $errno.":<".$error.">".$output;
        }
        curl_close($r);
        //$result = 'Curl failed: ' . curl_error($ch);
        // Close connection
        //curl_close($r);
        //echo $output;
        return $output;
    }
 
}




   
  ?>  
