
<?php
class WPPN8 extends AppHelper{
 
    //put your code here
    // constructor
    /*function __construct() {
         
    }*/
 
    /**
     * Sending Push Notification
     */
    //private $url = 'https://android.googleapis.com/gcm/send';
    private $WPPN8_URL = '';
    /*private $DB_HOST = "localhost";
    private $DB_USER = "root";
    private $DB_PASSWORD = "root";
    private $DB_DATABASE = "gcm";*/ 
    /*
     * Google API Key
     */
    //private $GOOGLE_API_KEY = "AIzaSyDGxHufeW2uyiYyxz8BMOILVd5EWognhyg"; // Place your Google API Key

    public function send_notification($registatoin_url, $title , $message , $paramsArr=array(),$msgId,$created) {
       // Create the toast message
       
       //$paramStr = "/NotificationList.xaml?pull=true";
       $paramStr = "/NewNotification.xaml?pull=true";
        
       $toastMessage = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
                    "<wp:Notification xmlns:wp=\"WPNotification\">" .
                            "<wp:Toast>" .
                            "<wp:Text1>" .$title . "</wp:Text1>" .
                            "<wp:Text2>".$message."</wp:Text2>" .
                            "<wp:Text3>".$msgId."</wp:Text3>" .
                            "<wp:Text4>".$created."</wp:Text4>" .
                            "<wp:Param>".$paramStr."</wp:Param>" .//NotificationList.xaml
                            "</wp:Toast> " .
                    "</wp:Notification>";
             
        // Create request to send
        $r = curl_init();
        curl_setopt($r, CURLOPT_URL,$registatoin_url);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_POST, true);
        curl_setopt($r, CURLOPT_HEADER, true); 

        // add headers
        $httpHeaders=array('Content-type: text/xml; charset=utf-8', 'X-WindowsPhone-Target: toast',
                        'Accept: application/*', 'X-NotificationClass: 2','Content-Length:'.strlen($toastMessage));
        curl_setopt($r, CURLOPT_HTTPHEADER, $httpHeaders);

        // add message
        curl_setopt($r, CURLOPT_POSTFIELDS, $toastMessage);

        // execute request
        $output = curl_exec($r);
        if($output == FALSE){
            $output = "SUCCESS:".$output;           
        }else{
            $errno = curl_errno($r);
            $error =  curl_error($r);
            $output = $errno.":<".$error.">".$output;
        }
        curl_close($r);
        //$result = 'Curl failed: ' . curl_error($ch);
        // Close connection
        //curl_close($r);
        return $output;
    }
       
        }




   
  ?>  
