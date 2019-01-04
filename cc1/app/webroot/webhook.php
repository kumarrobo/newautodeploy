<?php

        $challenge = $_REQUEST['hub_challenge'];
        $verify_token = $_REQUEST['hub_verify_token'];

        if($verify_token === "abc123") {
                echo $challenge;
        }
        
        $raw_data = json_decode(file_get_contents('php://input'), true);
        $leadgen_id = $raw_data['entry'][0]['changes'][0]['value']['leadgen_id'];
//        $form_id = $raw_data['entry'][0]['changes'][0]['value']['form_id'];
//        $page_id = $raw_data['entry'][0]['changes'][0]['value']['page_id'];
        
        $lead_data = json_decode(file_get_contents("https://graph.facebook.com/v2.10/".$leadgen_id."?access_token=EAAGeVP1W8PYBANrl7Cj9WJheHKjf3p0OyRnDVEDInMCWule6l9aWUkZBbqiuW6CMnuRW8QlU91DYlPZChydZBErmdNTMYSBlmVqHkVxWHuDIb5YgkJC44AMxQEzIefsZCQZAiSbfZByUSwBlnz2tZC9ZBseENTnrIzeQgMajYoqGOAZDZD&debug=all&format=json&method=get&pretty=0&suppress_http_code=1"), 1);
//        $form_data = file_get_contents("https://graph.facebook.com/v2.10/".$form_id."?access_token=EAAGeVP1W8PYBANrl7Cj9WJheHKjf3p0OyRnDVEDInMCWule6l9aWUkZBbqiuW6CMnuRW8QlU91DYlPZChydZBErmdNTMYSBlmVqHkVxWHuDIb5YgkJC44AMxQEzIefsZCQZAiSbfZByUSwBlnz2tZC9ZBseENTnrIzeQgMajYoqGOAZDZD&debug=all&format=json&method=get&pretty=0&suppress_http_code=1");
//        $page_data = file_get_contents("https://graph.facebook.com/v2.10/".$page_id."?access_token=EAAGeVP1W8PYBANrl7Cj9WJheHKjf3p0OyRnDVEDInMCWule6l9aWUkZBbqiuW6CMnuRW8QlU91DYlPZChydZBErmdNTMYSBlmVqHkVxWHuDIb5YgkJC44AMxQEzIefsZCQZAiSbfZByUSwBlnz2tZC9ZBseENTnrIzeQgMajYoqGOAZDZD&debug=all&format=json&method=get&pretty=0&suppress_http_code=1");

        $lead = array();
        foreach($lead_data['field_data'] as $l_d) {
                $lead[$l_d['name']] = $l_d['values'][0];
        }
        
        $con = mysql_connect('pay1.coyipz0wacld.us-east-1.rds.amazonaws.com', 'cc_user', 'SjeywU2jzPKJBzsw');
        mysql_select_db('shops', $con);
        $token = md5($lead['phone_number']);
//        $query = "INSERT INTO leads (pin_code, city, email, name, current_business, phone, messages, date, timestamp, req_by) "
//                . "VALUES ('{$lead['post_code']}', '{$lead['city']}', '{$lead['email']}', '{$lead['full_name']}', '{$lead['current_business']}', '{$lead['phone_number']}', '{$lead['time_to_call']}', '". date('Y-m-d', strtotime($lead_data['created_time'])) ."', '". date('Y-m-d H:i:s', strtotime($lead_data['created_time'])) ."', 'Facebook')";
                
        $query = "INSERT INTO leads_new (pin_code, city, email, name, current_business, phone, messages, creation_date, lead_timestamp, lead_source, token, otp_flag) "
                . "VALUES ('{$lead['post_code']}', '{$lead['city']}', '{$lead['email']}', '{$lead['full_name']}', '{$lead['current_business']}', '{$lead['phone_number']}', '{$lead['time_to_call']}', '". date('Y-m-d', strtotime($lead_data['created_time'])) ."', '". date('Y-m-d H:i:s', strtotime($lead_data['created_time'])) ."', '12','$token','0')";
        $res = mysql_query($query);
        
//        $file = "/mnt/logs/lead.log";
//        $fh = fopen($file,'a+');
//        fwrite($fh,date('Y-m-d H:i:s')."::".json_encode($lead_data)."\n");
//        fclose($fh);

//        https://graph.facebook.com/v2.10/461351207574152?access_token=&debug=all&format=json&method=get&pretty=0&suppress_http_code=1
        
//        Page ID : 276626485782867
        
//        Short-Term : EAAGeVP1W8PYBANpvtgDZAzHJs989RZAbBV9jWGddONTrLTg1RXkMHyb2LDa9TXMuyh5euEe1RX813MUY5MZABqgJ1l7eD1kLqC4JDORCFq0Xvu3eOhLmfvsHNPaR13eKRYBaKhrcTDhwxj9Odbg2AL1yVkX6Bkqr09nMcXdTzy6NqZCmiaYNLxyR2rICvhv0XZAaGTkN4qwZDZD
        
//        https://graph.facebook.com/v2.8/oauth/access_token?grant_type=fb_exchange_token&client_id=455562841485558&client_secret=d2e13a8dc30d59dc08e1cfd884de0b27&fb_exchange_token=EAAGeVP1W8PYBANpvtgDZAzHJs989RZAbBV9jWGddONTrLTg1RXkMHyb2LDa9TXMuyh5euEe1RX813MUY5MZABqgJ1l7eD1kLqC4JDORCFq0Xvu3eOhLmfvsHNPaR13eKRYBaKhrcTDhwxj9Odbg2AL1yVkX6Bkqr09nMcXdTzy6NqZCmiaYNLxyR2rICvhv0XZAaGTkN4qwZDZD

//        Long-Term : EAAGeVP1W8PYBAMBFBljRmsdXCRS2IZCVmGRupZCWWZBYf0ARZAptaMrK1d3AWNMTIR3caDnnt0qXi4xpf1Ia4wZCANgNxcmweeJGFZBYD0Ws06huV31lNPxp2KeZBQH2JGZCiALa78tu9zkYt5MPWFLQEHv847SpSznZAfBelZBJ30jQZDZD
        
//        https://graph.facebook.com/276626485782867?fields=access_token&access_token=EAAGeVP1W8PYBAMBFBljRmsdXCRS2IZCVmGRupZCWWZBYf0ARZAptaMrK1d3AWNMTIR3caDnnt0qXi4xpf1Ia4wZCANgNxcmweeJGFZBYD0Ws06huV31lNPxp2KeZBQH2JGZCiALa78tu9zkYt5MPWFLQEHv847SpSznZAfBelZBJ30jQZDZD
                
?>