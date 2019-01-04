
<?php

class CommunityController extends AppController{
    var $name = 'Community';
    var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
    var $components = array('RequestHandler','Shop','General');   
    
    
    
    function uploadPanel(){
        $this->layout = "plain";
         //Feed Type
        $feedAbout = $this->General->curl_post('http://community.pay1.in/getFeedType', null, 'GET');         
        $feedAboutval = json_decode($feedAbout['output']);
        //representation type
        $feedType = $this->General->curl_post('http://community.pay1.in/getResourceRepresentationType', null, 'GET');         
        $feedTypeval = json_decode($feedType['output']);
                
        
        $this->set('feedAboutval',$feedAboutval);
        $this->set('feedTypeval',$feedTypeval);
 
        if($this->RequestHandler->isAjax()){     
        $feed           = $this->params['form']['selectfeed'];
        $feedtitle      = $this->params['form']['feed_title'];
        $feedrepType    = $this->params['form']['feed_type'];
        $short_images   = $this->params['form']['feed_val'];        
        $feedtextUrl    = $this->params['form']['feed_blog'];
        $feedvideoUrls  = $this->params['form']['feed_video'];
        $feedimageUrls  = $this->params['form']['feed_imageval'];
        $feedimg1       = $this->params['form']['feed_image1val'];
        $feedimg2       = $this->params['form']['feed_image2val'];
        $feedimg3       = $this->params['form']['feed_image3val'];
        $feedimg4       = $this->params['form']['feed_image4val'];        
        //trimming the blank spaces in url's
        $slider1       = trim($feedimg1);
        $slider2       = trim($feedimg2);
//        $slider3       = trim($feedimg3);
//        $slider4       = trim($feedimg4);
        $sliders = array(
            trim($feedimg1),
            trim($feedimg2),
            trim($feedimg3),
            trim($feedimg4)
        );
        $feedvideoUrl  = trim($feedvideoUrls);
        $feedimageUrl  = trim($feedimageUrls); 
        $short_image   = trim($short_images);
       
        $user_id = $this->Session->read('Auth.User.id');
                      

     if(isset($short_images) && !empty($short_images)) {
        if($feedrepType == 4) {            
            $params = array('resource_id' => $feedtextUrl, 'resource_representation_type' => $feedrepType,
                'small_icon' => $short_image, 'title' => $feedtitle, 'feed_type' => $feed, 'visibility' => '1', 'uploaded_by' => $user_id);
                if(isset($feedtextUrl) && !empty($feedtextUrl)) {
                    $addFeed = $this->General->curl_post('http://community.pay1.in/addFeed', $params, 'POST'); 
                    echo json_encode(array(
                    'status' => 'success',
                    'msg' => 'Blog added Successfully'
                ));
                exit;             
             }else {
                 echo json_encode(array(
                    'status' => 'failure',
                    'description' => 'Test can\'t be empty'
                ));
               exit;         
             }
        } else if ($feedrepType == 3) {
        //resource for slider
//            $feedsliderUrl = $slider1 . "," . $slider2 . "," . $slider3  . "," . $slider4;            
            $feedsliderUrl = implode(',',array_filter($sliders));            
            $params = array('resource_id' => $feedsliderUrl, 'resource_representation_type' => $feedrepType,
                'small_icon' => $short_image, 'title' => $feedtitle, 'feed_type' => $feed, 'visibility' => '1', 'uploaded_by' => $user_id);     
              if((isset($slider1) && !empty($slider1)) && (isset($slider2) && !empty($slider2)) ) {
                $addFeed = $this->General->curl_post('http://community.pay1.in/addFeed', $params, 'POST');             
                    echo json_encode(array(
                    'status' => 'success',
                    'msg' => 'Slider added Successfully'
                ));
                exit;             
             }else {
                 echo json_encode(array(
                    'status' => 'failure',
                    'description' => 'First two image are not uploaded properly'
                ));
               exit;         
             }
            
        } else if ($feedrepType == 2) {
            $params = array('resource_id' => $feedimageUrl, 'resource_representation_type' => $feedrepType,
                'small_icon' => $short_image, 'title' => $feedtitle, 'feed_type' => $feed, 'visibility' => '1', 'uploaded_by' => $user_id);
           if(isset($feedimageUrl) && !empty($feedimageUrl)) {                        
                $addFeed = $this->General->curl_post('http://community.pay1.in/addFeed', $params, 'POST');             
                    echo json_encode(array(
                            'status' => 'success',
                            'msg' => 'Image added Successfully'
                        ));
                        exit;
                    } else {
                        echo json_encode(array(
                            'status' => 'failure',
                            'description' => 'Image is not uploaded properly'
                        ));
                        exit;
                    }
                } else if ($feedrepType == 1) {
            $params = array('resource_id' => $feedvideoUrl, 'resource_representation_type' => $feedrepType,
                'small_icon' => $short_image, 'title' => $feedtitle, 'feed_type' => $feed, 'visibility' => '1', 'uploaded_by' => $user_id);
           if(isset($feedvideoUrl) && !empty($feedvideoUrl)) {                 
            $addFeed = $this->General->curl_post('http://community.pay1.in/addFeed', $params, 'POST');             
                echo json_encode(array(
                    'status' => 'success',
                    'msg' => 'Video URL added Successfully'
                ));
                exit;             
             }else {
                 echo json_encode(array(
                    'status' => 'failure',
                    'description' => 'Video URL should not be empty'
                ));
               exit;         
             }
            
        }   
     }else{
            echo json_encode(array(
                    'status' => 'failure',
                    'description' => 'Small icon is not uploaded properly'
                ));
             exit;         
            }
}  
    }
   
    function uploadImages($upFileName,$fileN){
        $filename = "uploadCommunity" . date('Ymd') . ".txt";
        $this->General->logData('/mnt/logs/' . $filename, "inside uploadImages ::files::" . json_encode($_FILES));
        $response = array();        
        
        for ($i = 0; $i < count($_FILES[$upFileName]["name"]); $i++) {
            try {

                if (!isset($_FILES[$upFileName]['error']) || is_array($_FILES[$upFileName]['error'])) {
                    throw new RuntimeException('Invalid parameters.');
                }

// Check $_FILES['upfile']['error'] value.
                switch ($_FILES[$upFileName]['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new RuntimeException('No file sent.');
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new RuntimeException('Exceeded filesize limit.');
                    default:
                        throw new RuntimeException('Unknown errors.');
                }

// DO NOT TRUST $_FILES[$upFileName]['mime'] VALUE !!
// Check MIME Type by yourself.
                try {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                } catch (Exception $e) {
                    echo $e;
                }
                $var = $finfo->file($_FILES[$upFileName]['tmp_name']);
                if (false === $ext = array_search(
                        $finfo->file(
                                $_FILES[$upFileName]['tmp_name']), 
                     array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                        ), true
                        )) {
                    throw new RuntimeException('Invalid file format.');
                }

// You should also check filesize here.
                if ($_FILES[$upFileName]['size'] > 3000000) {//3 MB
                    array_push($response, array(
                        'status' => 'failure',                        
                        'description' => 'File should not be greater than 3 MB.'
                    ));
                }else {                
//
//                }
//                // You should name it uniquely.
// DO NOT USE $_FILES[$upFileName]['name'] WITHOUT ANY VALIDATION !!
// On this example, obtain safe unique name from its binary data.
                $rand = rand(1000, 9999);
//echo $_FILES[$upFileName]['tmp_name'][$i];
//die;

                App::import('vendor', 'S3', array('file' => 'S3.php'));
                $bucket = s3communityBucket; // s3kycBucket;
                $s3 = new S3(awsAccessKey, awsSecretKey);
                $s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
                $retInfo = explode('_', $fileN);
                $actual_image_name = $fileN . "_" . $rand . ".jpeg";
                if ($s3->putObjectFile($_FILES[$upFileName]['tmp_name'], $bucket, $actual_image_name, S3::ACL_PUBLIC_READ)) {
                    $imgUrl = 'http://' . $bucket . '.s3.amazonaws.com/' . $actual_image_name;
                    array_push($response, array(
                        'status' => 'success',
                        'url' => $imgUrl,
                        'msg' => 'File is uploaded successfully.'
                    ));
                } else {
                    array_push($response, array(
                        'status' => 'failure',
                        'filename' => $_FILES[$upFileName]['name'],
                        'description' => 'File  not uploaded successfully.'
                    ));
                }
            }} catch (RuntimeException $e) {
                array_push($response, array(
                    'status' => 'failure',
                    'filename' => $_FILES[$upFileName]['name'],
                    'description' => $e->getMessage()
                ));
            }
        }

        return $response;
    }

            
    function sendFeed($params){    
        $this->autoRender = FALSE;
        $Feed = $this->General->curl_post('http://community.pay1.in/addFeed', $params, 'POST');             
        return $Feed;
    }

    function shortImages(){
       $this->autoRender = FALSE;           
        if($_FILES['feed_icon']){
        $short_image = $this->uploadImages("feed_icon","feed_icon_");                       
        echo json_encode($short_image);        
    }}

    function typeImages(){
       $this->autoRender = FALSE;   
        if($_FILES['feed_image']){
        $type_image = $this->uploadImages("feed_image","feed_image_");                        
        echo json_encode($type_image);        
        }}

    function sliderImages(){
        $this->autoRender = FALSE;
            if ($_FILES['feed_image1']) {
                $slider_image = $this->uploadImages("feed_image1", "feed_image1_");
                echo json_encode($slider_image);
            } else if($_FILES['feed_image2']) {
                $slider_image = $this->uploadImages("feed_image2", "feed_image2_");
                echo json_encode($slider_image);
            } else if($_FILES['feed_image3']) {
                $slider_image = $this->uploadImages("feed_image3", "feed_image3_");
                echo json_encode($slider_image);
            } else{
                $slider_image = $this->uploadImages("feed_image4", "feed_image4_");
                echo json_encode($slider_image);
            }
    }
    
    function getFeedReport(){
        $this->layout = "plain";
        $getfeedval  =  array();
        $getfeedtype = array("1" => "Company Update","2" =>"Training Material", "3" => "Blog");
        $getreprType = array("1" => "Video","2" =>"Image", "3" => "Slide","4" => "Text");
        $getFeed       = $this->General->curl_post('http://community.pay1.in/getFeed', null, 'GET');         
        $getfeed     =  json_decode($getFeed['output'],true);
        
        $this->set('feedval',$getfeed);
        $this->set('getfeedtype',$getfeedtype);
        $this->set('getreprType',$getreprType);
                          
    }
    
    function updateFeed(){
        $this->autoRender = False;
        
        $id = $this->params['form']['id'];
        $value = $this->params['form']['value'];
        
        if($value == '1'){
            $visibility = '0';
        }else{
            $visibility = '1';
        }
        
        $params = array("feed_id" => $id ,"visibility" => $visibility);
                
        $updFeed = $this->General->curl_post('http://community.pay1.in/updateFeedVisibility', $params, 'POST');
        echo json_encode($updFeed);
    }
    
    function setFeed($id,$smallicon){
        $this->layout = "plain";
        echo $id;
        print_r($smallicon);
        
    }
    
    function bloghtml(){
        $this->autoRender = False;
        $response = array();
        if($_POST['feed_blog']){
            $data = $_POST['feed_blog'];
        
        if(((strpos($data,"<html")) !== False)){
            //if(((strpos($data,"<html>")) !== False) || (strpos($data,"<html lang=\"en\">" !== False)) || (strpos($data,"<html lang=\"en-us\">") !== False)|| (strpos($data,"<html lang=\"en-uk\">"))  !== False){                                        
            $fname = "blog.html"; //generates random name
            $fa = fopen("/tmp/" . $fname, 'w'); //creates new file
            fwrite($fa, $data);            
            fclose($fa);                                    
                        
                $filename = "uploadBlogCommunity" . date('Ymd') . ".txt";
                $this->General->logData('/mnt/logs/' . $fname, "inside uploadImages ::files::" . json_encode($fa));
                       
                $rand = rand(1000, 9999);

                $fileN = "Blog";
                App::import('vendor', 'S3', array('file' => 'S3.php'));
                $bucket = s3communityBucket; // s3kycBucket;                
                $s3 = new S3(awsAccessKey, awsSecretKey);
                $s3->putBucket($bucket, S3::ACL_PUBLIC_READ);                
                $retInfo = explode('_', $fileN);
                $actual_blog_name = $fileN . "_" . $rand . ".html";                
                $fil = '/tmp/blog.html';
                if ($s3->putObjectFile($fil, $bucket, $actual_blog_name, S3::ACL_PUBLIC_READ)) {
                    $blogUrl = 'http://' . $bucket . '.s3.amazonaws.com/' . $actual_blog_name;
                    array_push($response, array(
                        'status' => 'success',
                        'url' => $blogUrl,
                        'msg' => 'File is Saved successfully.'
                    ));
            } else {
                    array_push($response, array(
                        'status' => 'failure',
                        'filename' => $fname,
                        'description' => 'File  is not Saved successfully.'
                    ));
            }                   
            } else {
                    array_push($response, array(
                        'status' => 'failure',
                        'filename' => $fname,
                        'description' => 'Please Enter code with Proper Tags.'
                    ));                               
            }
            } 
         else {
                    array_push($response, array(
                        'status' => 'failure',                        
                        'description' => 'Please Insert HTML Content.'
                    ));                               
            }   
            echo json_encode($response);       
    
            }   
    }
