<?php
class B2cextenderController extends AppController {

	var $name = 'B2cextender';
	var $components = array('RequestHandler','Shop','General','B2cextender');
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
	var $uses = array('Retailer');
	
	function beforeFilter() {
		parent::beforeFilter ();
		$this->Auth->allow('*');		
	}
	
	function test(){
		echo "1";
		$this->autoRender = false;
	}
	
    /*
     * This function will be call when user gives a missed call and forward the last successful transaction detail of
     * that user to b2c server.
     * 
     */
    function registerMissedCallusertob2c($mobile,$misscallnum){
        /* trimming the last 10 digit as  sender mobile number */
        $number = substr($mobile, -10);
        
        //$dummy_stub = $this->B2cextender->missed_call_dummy($number);
        /* fetching the last successful transaction of the user giving missed call */
        $prev_date = date('Y-m-d', strtotime("-1 days"));
        $qry = "select * from vendors_activations where mobile='".$number."' and status=1 and date >='".$prev_date."' ORDER BY 1 desc LIMIT 1";
        $user_last_txn_detail = $this->Retailer->query($qry);
        
        //$user_last_txn_detail = array($dummy_stub);
        
        /* setting default value of variable */        
        $user_txn_detail = array('mobile_number'=>$mobile,'miss_call_num'=>$misscallnum);
        
        /* checking if query returned any data */
        if(count($user_last_txn_detail) > 0){
            $response_arr = $user_last_txn_detail[0]['vendors_activations'];
            /* setting array to send to b2c server */
            $user_txn_detail = array('trans_id'=>$response_arr['shop_transaction_id'],'amount'=>$response_arr['amount'],
                    'mobile_number'=>$response_arr['mobile'],'miss_call_num'=>$misscallnum,'trans_date'=>$response_arr['date']);
        }
        
        //print_r($user_last_txn_detail);exit();
        /* forwarding request to b2c server */
        $b2c_missed_call_url = B2C_URL ."api/true/actiontype/Create_MissCall_user/?";
        $output_response = $this->General->curl_post($b2c_missed_call_url, $user_txn_detail);
        //echo "----".$output_response['output']."---";
        //print_r($output_response);
        //print_r($user_last_txn_detail);exit();
        $this->autoRender = false;
    }	
}