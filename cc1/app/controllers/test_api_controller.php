<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class TestApiController extends AppController{

    function beforeFilter(){
        //parent::beforeFilter();

        $data = array('method'=>'authenticate','app_name'=>'rm_app','mobile'=>'8898481581','password'=>'1010','uuid'=>'3234asdsdf45t','login_datetime'=>'2018-06-06 18:00:00','latitude'=>'19.0000','longitude'=>'75.998','version_code'=>'1'); 
        /*$data = array('method'=>'verifyOTPAuthenticate','app_name'=>'rm_app','mobile'=>'8898481581','rm_user_id'=>'79719253','uuid'=>'3234asdsdf45t','gcm_reg_id'=>'dfjgdgfdgfgfdjgbkjdkf','device_type'=>'Android','otp'=>'1234','device_name'=>'Samsung','login_datetime'=>'2018-06-06 18:00:00','latitude'=>'19.0000','longitude'=>'75.998');
        $data = array('method'=>'resendOTPAuthenticate','app_name'=>'rm_app','mobile'=>'8898481581','rm_user_id'=>'79719253');
        $data = array('method'=>'showCheckinOrCheckout','app_name'=>'rm_app','rm_user_id'=>'79719253','token'=>'ef1e6vltli7t3h5d9alt7oe2b6');
        $data = array('method'=>'markCheckIn','app_name'=>'rm_app','rm_user_id'=>'79719253','uuid'=>'3234asdsdf45t','latitude'=>'19.0000','longitude'=>'75.998','token'=>'ef1e6vltli7t3h5d9alt7oe2b6','reason_type'=>'','reason'=>'Going out');*/
       /* $data = array('method'=>'markCheckOut','app_name'=>'rm_app','attendance_id'=>'1','rm_user_id'=>'79719253','reason_type'=>'','reason'=>'Going out','uuid'=>'3234asdsdf45t','latitude'=>'19.0000','longitude'=>'75.998','token'=>'ef1e6vltli7t3h5d9alt7oe2b6');
        $data = array('method'=>'logout','app_name'=>'rm_app','tracking_data'=>'[{"time" : "13:00:00","latitude" : "19.3234","longitude" : "87.3634",},{"time" : "13:02:00","latitude" : 19.3234","longitude" : "87.3634",}]','rm_user_id'=>'79719253','uuid'=>'3234asdsdf45t','token'=>'ef1e6vltli7t3h5d9alt7oe2b6','login_id'=>'1');
        $data = array('method'=>'insertTrackingLog','app_name'=>'rm_app','tracking_data'=>'[{"time" : "13:00:00","latitude" : "19.3234","longitude" : "87.3634"},{"time" : "13:02:00","latitude" : "19.3234","longitude" : "87.3634"}]','rm_user_id'=>'79719253','uuid'=>'3234asdsdf45t');*/
       //$data = array('method'=>'masterDistributor','app_name'=>'rm_app','rm_user_id'=>'79747580','token'=>'a11pvsva1b3vsqrqgerv110cu0');
      /*$data = array('method'=>'masterRetailer','app_name'=>'rm_app','dist_id'=>'2','rm_user_id'=>'79747580','token'=>'isq29e607f28bhlhr2bjbt1030');
      $data = array('method'=>'masterStatus','app_name'=>'rm_app','rm_user_id'=>'79747580','token'=>'isq29e607f28bhlhr2bjbt1030');*/
       /*$data = array('method'=>'myAllLead','app_name'=>'rm_app','rm_user_id'=>'79719253','token'=>'a11pvsva1b3vsqrqgerv110cu0');*/

       $this->encrypt(json_encode($data));
       
    }

    function encrypt($str) { 
        $hex_iv = '00000000000000000000000000000000';
        $key="ARMjU1M674OUZ4Qz";
        $key = hash("sha256", $key, true);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $key, $this->hexToStr($hex_iv));
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        $encrypted = mcrypt_generic($td, $str);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);        
        echo base64_encode($encrypted);
        exit;
    }



    function hexToStr($hex)
    {
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2)
        {
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
    
}
