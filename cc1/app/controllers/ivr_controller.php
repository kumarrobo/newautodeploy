<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ivrController extends AppController{
	var $name = 'Shops';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv');
	var $components = array('RequestHandler','Shop');
	var $uses = array('Slaves','Retailer');


	 function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }

	function retailerBalance($mobile){
		$this->autoRender = false;
		if (!empty($mobile)) {

		    return $this->Shop->getBalanceViaMobile($mobile);
		}
	}


		function lastTopUpAmount($mobile){

	      $this->autoRender = false;

		  $response = '';

		  if(!empty($mobile)) :

			  $lastTopupquery = "Select shop_transactions.amount,shop_transactions.date,shop_transactions.timestamp,retailers.mobile, "
				                . " distributors.company  "
								. " from shop_transactions "
								. "inner join retailers on (retailers.id = shop_transactions.target_id) "
				                . "inner join distributors ON (retailers.parent_id =  distributors.id)"
								. " where retailers.mobile = '".$mobile."' and shop_transactions.type = '".DIST_RETL_BALANCE_TRANSFER."'"
							    . " order by shop_transactions.id desc";


		     $result = $this->Slaves->query($lastTopupquery);


			 if(!empty($result)):

//				if(count($result)>1) {
//
//					$response = "Your last three top up is";
//
//					 foreach($result as $val):
//
//					 $response.= " {$val['shop_transactions']['amount']} Rupees on ".date('d',strtotime($val['shop_transactions']['date']))." ".date('M',strtotime($val['shop_transactions']['date']))." ".date('Y',strtotime($result[0]['shop_transactions']['date']))."  at ".date('h:i A',strtotime($val['shop_transactions']['timestamp']))." from {$val['distributors']['company']} ";
//
//				     endforeach;
//
//				  } else {

					 //$response = "Your last top up amount is {$result[0]['shop_transactions']['amount']} on ".date('d',strtotime($result[0]['shop_transactions']['date']))." ".date('M',strtotime($result[0]['shop_transactions']['date']))." ".date('Y',strtotime($result[0]['shop_transactions']['date']))."  at ".date('h:i A',strtotime($result[0]['shop_transactions']['timestamp']))." from {$result[0]['distributors']['company']} ";

					  $response = $result[0]['shop_transactions']['amount'];
			//	 }

			 endif;

		  endif;

		  return $response;

		}

		function lastTopUpdate($mobile){

	      $this->autoRender = false;

		  $response = '';

		  if(!empty($mobile)) :

			  $lastTopupquery = "Select shop_transactions.amount,shop_transactions.date,shop_transactions.timestamp,retailers.mobile, "
				              . " distributors.company  "
					      . " from shop_transactions "
					      . "inner join retailers on (retailers.id = shop_transactions.target_id) "
				              . "inner join distributors ON (retailers.parent_id =  distributors.id)"
					      . " where retailers.mobile = '".$mobile."' and shop_transactions.type in ('".DIST_RETL_BALANCE_TRANSFER."','".SLMN_RETL_BALANCE_TRANSFER."')"
					      . " order by shop_transactions.id desc";

		     $result = $this->Slaves->query($lastTopupquery);

			 if(!empty($result)):

			 $response = date('d',strtotime($result[0]['shop_transactions']['date']))." ".date('M',strtotime($result[0]['shop_transactions']['date']))." ".date('Y',strtotime($result[0]['shop_transactions']['date']));

			 endif;

		  endif;

		  return $response;

		}

		function lastTopTime($mobile){

	      $this->autoRender = false;

		  $response = '';

		  if(!empty($mobile)) :

			  $lastTopupquery = "Select shop_transactions.amount,shop_transactions.date,shop_transactions.timestamp,retailers.mobile, "
				                . " distributors.company  "
								. " from shop_transactions "
								. "inner join retailers on (retailers.id = shop_transactions.target_id) "
				                . "inner join distributors ON (retailers.parent_id =  distributors.id)"
								. " where retailers.mobile = '".$mobile."' and shop_transactions.type in ('".DIST_RETL_BALANCE_TRANSFER."','".SLMN_RETL_BALANCE_TRANSFER."')"
							    . " order by shop_transactions.id desc";


		     $result = $this->Slaves->query($lastTopupquery);


			 if(!empty($result)):

			 $response = date('h:i A',strtotime($result[0]['shop_transactions']['timestamp']));

			 endif;

		  endif;

		  return $response;

		}

		function lastTopCompany($mobile){

	      $this->autoRender = false;

		  $response = '';

		  if(!empty($mobile)) :

			  $lastTopupquery = "Select shop_transactions.amount,shop_transactions.date,shop_transactions.timestamp,retailers.mobile, "
				                . " distributors.id,distributors.company  "
								. " from shop_transactions "
								. "inner join retailers on (retailers.id = shop_transactions.target_id) "
				                . "inner join distributors ON (retailers.parent_id =  distributors.id)"
								. " where retailers.mobile = '".$mobile."' and shop_transactions.type in ('".DIST_RETL_BALANCE_TRANSFER."','".SLMN_RETL_BALANCE_TRANSFER."')"
							    . " order by shop_transactions.id desc";


		     $result = $this->Slaves->query($lastTopupquery);


			 if(!empty($result)):
                 /** IMP DATA ADDED : START**/
                $temp = $this->Shop->getUserLabelData($result[0]['distributors']['id'],2,3);
                $imp_data = $temp[$result[0]['distributors']['id']];
                /** IMP DATA ADDED : END**/

			    // $response = $result[0]['distributors']['company'];
			    $response = $imp_data['imp']['shop_est_name'];

			 endif;

		  endif;

		  return $response;

		}


		function earning($mobile){

			$this->autoRender = false;

			//$date = date('Y-m-d');

			$date = '2014-01-13';
			$response = "";

			$earningdata = $this->Slaves->query("SELECT sum(amount) as amts,"
					. "sum(amount*discount_comission/100) as earning "
					. "FROM shop_transactions inner join retailers ON (retailers.id = shop_transactions.source_id) "
					. "WHERE type = ".RETAILER_ACTIVATION." AND confirm_flag = 1 AND date = '$date' AND retailers.mobile = '".$mobile."'"
					);

			if(!empty($earningdata[0][0]['amts']) && !empty($earningdata[0][0]['earning'])){

				$response = intval($earningdata[0][0]['earning']);

//				$response = "Dear Retailer your total sale for ".date('d',strtotime($date))." ".date('M',strtotime($date))." is {$earningdata[0][0]['amts']} Rupess and earning is {$earningdata[0][0]['earning']} Rupees ";
			} else {

				$response = "No records found";
			}

			return $response;

		}

		function createComplain($mobile,$tag=NULL){

		$this->autoRender = false;

		if(!empty($mobile)){

	    App::import('Controller', 'Apis');

		$obj = new ApisController;

		$obj->constructClasses();

		$transdata  = $this->Slaves->query("SELECT id FROM vendors_activations"
											. " WHERE "
											. "(mobile = '".$mobile."' or param = '".$mobile."')"
											. " ORDER BY id DESC LIMIT 0,1");

		if(!empty($transdata)):

		$ret = $obj->reversal(array('id'=>$transdata[0]['vendors_activations']['id'], 'tag' => $tag,'method' => 'reversal','type' => 'ivr_complain'));

	    endif;

		if(!empty($ret)):

			return $ret['description'];
		endif;
		} else {

			return "Transaction does not exist";
		}


		}

		function lastTxnStatus($mobile,$limit=NULL){

		   $this->autoRender = false;

			if(!empty($mobile)){

			$lasttxnstatus = $this->Slaves->query("SELECT retailers.mobile,vendors_activations.mobile,vendors_activations.amount,vendors_activations.status,vendors_activations.param "
					                              .  "FROM `retailers` "
												  . " left join vendors_activations "
												  . " on (vendors_activations.retailer_id = retailers.id) "
												  . " where retailers.mobile = '$mobile'   "
												  . " order by vendors_activations.id desc "
												  . " limit $limit,1");

				if (!empty($lasttxnstatus)) {

				if ($lasttxnstatus[0]['vendors_activations']['status'] == '0') {
					$ps = 'In Process';
				} else if ($lasttxnstatus[0]['vendors_activations']['status'] == '1') {
					$ps = 'Successful';
				} else if ($lasttxnstatus[0]['vendors_activations']['status'] == '2') {
					$ps = 'Failed';
				} else if ($lasttxnstatus[0]['vendors_activations']['status'] == '3') {
					$ps = 'Reversed';
				} else if ($lasttxnstatus[0]['vendors_activations']['status'] == '4') {
					$ps = 'Reversal In Process';
				} else if ($lasttxnstatus[0]['vendors_activations']['status'] == '5') {
					$ps = 'Reversal declined';
				}

				if($lasttxnstatus[0]['vendors_activations']['param']==NULL){
					$mobile = $lasttxnstatus[0]['vendors_activations']['mobile'];
				} else {
					$mobile = $lasttxnstatus[0]['vendors_activations']['param'];
				}

				$response = $ps;
				 //$response = "status : $ps <br/> number : $mobile <br/>  Rupees : {$lasttxnstatus[0]['vendors_activations']['amount']}";
			} else {


		        $response = "No transaction found for number $mobile";
			}

		   return $response;

			}

		}

		function lastTxnNumber($mobile,$limit=NULL){

		   $usrQuery = '';

		   $this->autoRender = false;

			if(!empty($mobile)){

			$lasttxnstatus = $this->Slaves->query("SELECT retailers.mobile,vendors_activations.mobile,vendors_activations.amount,vendors_activations.status,vendors_activations.param "
					                              .  "FROM `retailers` "
												  . " left join vendors_activations "
												  . " on (vendors_activations.retailer_id = retailers.id) "
												  . " where retailers.mobile = '$mobile' "
												  . " order by vendors_activations.id desc "
												  . " limit $limit,1");

				if (!empty($lasttxnstatus)) {


				if($lasttxnstatus[0]['vendors_activations']['param']==NULL){
					$mobile = $lasttxnstatus[0]['vendors_activations']['mobile'];
				} else {
					$mobile = $lasttxnstatus[0]['vendors_activations']['param'];
				}

				$response = $mobile;
				 //$response = "status : $ps <br/> number : $mobile <br/>  Rupees : {$lasttxnstatus[0]['vendors_activations']['amount']}";
			} else {


		        $response = "No transaction found for number $mobile";
			}

		   return $response;

			}

		}

		function lastTxnamount($mobile,$limit=NULL){

		$usrQuery = '';


		   $this->autoRender = false;

			if(!empty($mobile)){

			$lasttxnstatus = $this->Slaves->query("SELECT retailers.mobile,vendors_activations.mobile,vendors_activations.amount,vendors_activations.status,vendors_activations.param "
					                              .  "FROM `retailers` "
												  . " left join vendors_activations "
												  . " on (vendors_activations.retailer_id = retailers.id) "
												  . " where retailers.mobile = '$mobile' "
												  . " order by vendors_activations.id desc "
												  . " limit $limit,1");

			   if (!empty($lasttxnstatus)) {

				$response = $lasttxnstatus[0]['vendors_activations']['amount'];
				 //$response = "status : $ps <br/> number : $mobile <br/>  Rupees : {$lasttxnstatus[0]['vendors_activations']['amount']}";
			  } else {


		        $response = "No transaction found for number $mobile";
			}

		   return $response;

			}

		}

		function otherTransactionOperator($mobile){

		   $this->autoRender = false;

			if(!empty($mobile)){

			$sql = $this->Slaves->query("SELECT vendors_activations.id,products.name from vendors_activations INNER join products"
										. " ON (products.id = vendors_activations.product_id) "
										. " WHERE vendors_activations.mobile ='" .$mobile. "'  OR vendors_activations.param ='".$mobile."' order by id desc limit 0,1 ");

			if(!empty($sql)){
				$return = $sql[0]['products']['name'];
			}
			}

			return $return;
		}
		function otherTransactionamount($mobile){

			$this->autoRender = false;

			if(!empty($mobile)){
			$sql = $this->Slaves->query("SELECT vendors_activations.amount from vendors_activations "
										. " WHERE vendors_activations.mobile =" .$mobile. "  OR vendors_activations.param =".$mobile." order by id desc limit 0,1 ");

			if(!empty($sql)){

				$return = $sql[0]['vendors_activations']['amount'];
			}
			}

			return $return;
		}

		function otherTransactionstatus($mobile){

			$this->autoRender = false;

			if(!empty($mobile)){
			$sql = $this->Slaves->query("SELECT vendors_activations.status from vendors_activations "
										. "WHERE vendors_activations.mobile =" .$mobile. "  OR vendors_activations.param =".$mobile." order by id desc limit 0,1 ");

			if(!empty($sql)){

				if ($sql[0]['vendors_activations']['status'] == '0') {
					$ps = 'In Process';
				} else if ($sql[0]['vendors_activations']['status'] == '1') {
					$ps = 'Successful';
				} else if ($sql[0]['vendors_activations']['status'] == '2') {
					$ps = 'Failed';
				} else if ($sql[0]['vendors_activations']['status'] == '3') {
					$ps = 'Reversed';
				} else if ($sql[0]['vendors_activations']['status'] == '4') {
					$ps = 'Reversal In Process';
				} else if ($sql[0]['vendors_activations']['status'] == '5') {
					$ps = 'Reversal declined';
				}
			}
			}

			return $ps;
		}

		function lastComplaintNumber($mobile){
			$this->autoRender = false;

			$lastComplaintQuery = $this->Slaves->query( ' SELECT'
														. ' vendors_activations.mobile '
														. ' FROM vendors_activations'
					                                    . ' INNER JOIN complaints ON (complaints.vendor_activation_id = vendors_activations.id)'
														. ' INNER JOIN retailers ON (retailers.id = vendors_activations.retailer_id)'
														. ' WHERE retailers.mobile ="'.$mobile.'"'
														. ' ORDER BY vendors_activations.id desc '
														. ' LIMIT 0,1');

			if(!empty($lastComplaintQuery)){

				$complaintNumber = $lastComplaintQuery[0]['vendors_activations']['mobile'];
			}

			return $complaintNumber;


		}

		function lastComplaintstatus($mobile){
			$this->autoRender = false;

			$lastComplainStatus = $this->Slaves->query( ' SELECT'
														. ' vendors_activations.mobile,complaints.resolve_flag '
														. ' FROM vendors_activations'
					                                    . ' INNER JOIN complaints ON (complaints.vendor_activation_id = vendors_activations.id)'
														. ' INNER JOIN retailers ON (retailers.id = vendors_activations.retailer_id)'
														. ' WHERE retailers.mobile ="'.$mobile.'"'
														. ' ORDER BY vendors_activations.id desc '
														. ' LIMIT 0,1');

			if(!empty($lastComplainStatus)){

				if($lastComplainStatus[0]['complaints']['resolve_flag'] ='0'){
					$status = 'In Process';
				} else {
					$status = 'Successful';
				}

			}

			return $status;


		}

		function lastComplaintsTat($mobile){
			$this->autoRender = false;

			$lastComplaintat = $this->Slaves->query( ' SELECT'
														. ' vendors_activations.mobile,complaints.turnaround_time '
														. ' FROM vendors_activations'
					                                    . ' INNER JOIN complaints ON (complaints.vendor_activation_id = vendors_activations.id)'
														. ' INNER JOIN retailers ON (retailers.id = vendors_activations.retailer_id)'
														. ' WHERE retailers.mobile ="'.$mobile.'"'
														. ' ORDER BY vendors_activations.id desc '
														. ' LIMIT 0,1');



			$datetime1 = new DateTime(date('Y-m-d H:i:s'));

			$datetime2 = new DateTime($lastComplaintat[0]['complaints']['turnaround_time']);

			$datediff = $datetime2->diff($datetime1);


			$time = $datediff->h.' Hours'  . ' '.$datediff->i.'Minutes';

			return $time;


		}

}

