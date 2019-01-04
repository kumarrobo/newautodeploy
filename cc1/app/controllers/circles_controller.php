
<?php

/**
 * 
 * @author modem
 *
 */
class CirclesController extends AppController {
	var $name = 'Circles';
	var $helpers = array (
			'Html',
			'Ajax',
			'Javascript',
			'Minify' 
	);
	var $components = array (
			'RequestHandler',
			'Shop' 
	);
	var $uses = array (
			'Circle' 
	);
	function beforeFilter() {
		set_time_limit ( 0 );
		//ini_set ( "memory_limit", "-1" );
		parent::beforeFilter ();
		$this->layout = 'circles';
	}
	
	/**
	 *
	 * @param string $circle        	
	 * @param string $operator        	
	 * @param string $plan        	
	 */
	function index($circle = "null", $operator = "null", $plan = "null", $duplicate = 0, $plan_amt="null") {
		$flag_circle = 0;
		$flag_operator = 0;
		$flag_plan = 0;
		
		$c = "";
		$o = "";
		$p = "";
		$a = "";
		if ($circle != "null") {
			$c = " c_id = $circle AND";
			$flag_circle = 1;
		}
		
		if ($operator != "null") {
// 			if ($flag_circle == 0)
// 				$o = " prod_code_pay1 = $operator AND ";
// 			else
			$o = " prod_code_pay1 = $operator AND";
			$flag_operator = 1;
		}
		
		if ($plan != "null" ) {
			if ($plan == 'Data_2G')
				$p .= " plan_type = 'Data/2G' AND ";
			else
				$p .= " plan_type = '$plan' AND ";
			$flag_plan = 1;
		}
                if ($plan_amt!="null")
                {
                    $a.=" plan_amt = $plan_amt AND ";
                }
		
		$query = "";
		if($flag_operator !=0){		
			$query = "SELECT id, c_name,opr_name,plan_type,plan_amt, plan_validity, plan_desc, updated FROM circle_plans WHERE $c $o $p $a show_flag = 1 order by plan_amt";
 			//echo $query;
			$data = $this->Circle->query ( $query );
			$this->set ( 'posts', $data );
			$this->set ( 'prod_code_pay1', $operator );
			$this->set ( 'c_id', $circle );
			$this->set ( 'duplicateFlag', $duplicate );
			$this->set ( 'planType', $plan);
                        $this->set ( 'plan_amt', $plan_amt);

                     
		}
		$this->render ( '/circles/index' );
	}
	
	/**
	 *
	 * @param unknown $id        	
	 */
	function deletePlan($id) {
		// $this->autoRender = false;
		$updatedOn = date ( 'Y-m-d H:i:s' );
		$query = "UPDATE circle_plans SET show_flag = 0,updated = '$updatedOn' WHERE id = $id";
// 		echo "<br>$query<br>";
		// echo $query."<br>";
		$this->Circle->query ( $query );
		
		$query = "SELECT c_id, prod_code_pay1, plan_type FROM circle_plans WHERE id = $id";
		$data = $this->Circle->query($query);
		$circle = $data[0]['circle_plans']['c_id'];
		$operator = $data[0]['circle_plans']['prod_code_pay1'];
		$plan = $data[0]['circle_plans']['plan_type'];
		if($plan == "Data/2G")
			$plan = "Data_2G";
		$this->redirect("/circles/index/".$circle."/".$operator."/". $plan);
		
	}
        /**
         * this function soft deletes multiple plans
         */
        function deletePlans() {
            $plan_ids = $this->params['form']['plans'];
            if( is_array($plan_ids) && count($plan_ids) > 0 ){
                $query = 'UPDATE circle_plans '
                        . 'SET show_flag = 0,updated = "'.date('Y-m-d H:i:s').'" '
                        . 'WHERE id IN ('.implode(',',$plan_ids).')';
		$response = $this->Circle->query( $query );
                if($response){
                    echo 'success';
                }
            }
            $this->autoRender = false;
        }
	
	/**
	 */
	function newPlanEntry() {
		$this->autoRender = false;
		
		$sqlData = $this->setQueryParameters ( $_POST );

		$planType = "";
		if($sqlData['plan_type'] == 'Data_2G')
			$planType = "Data/2G";
		else 
			$planType = $sqlData ['plan_type'];
		
		$planExists = $this->Circle->query("select * 
											from circle_plans
											where c_id = ". $sqlData ['c_id'] ." 
											AND prod_code_pay1 = ".$sqlData ['prod_code_pay_1'] ." 
											AND plan_type = '".$planType."' 
											AND plan_amt = ".$sqlData ['plan_amt']);
		if(empty($planExists)){
			$query = "INSERT INTO circle_plans 
					(opr_name, c_name, c_code_pay1, c_type, c_id, prod_code_pay1, plan_type, plan_amt,
				plan_validity, plan_desc, show_flag, updated )
				VALUES ('" . $sqlData ['opr_name'] . "',' " . $sqlData ['c_name'] . "', '" . $sqlData ['c_code_pay1'] . "', 
						'" . $sqlData ['c_type'] . "', " . $sqlData ['c_id'] . ", " . $sqlData ['prod_code_pay_1'] . ", 
						'" . $planType . "', " . $sqlData ['plan_amt'] . ", '" . $sqlData ['plan_validity'] . "', 
						'" . $sqlData ['plan_desc'] . "', " . $sqlData ['show_flag'] . ", '" . $sqlData ['updated'] . "')";
                        $this->Circle->query ( $query );
		}
		else {
			$query = "update circle_plans
					set plan_validity = '" . $sqlData ['plan_validity'] . "',
					plan_desc = '" . $sqlData ['plan_desc'] . "',
					show_flag = ".$sqlData ['show_flag'].",
					updated = '" . $sqlData ['updated'] . "'
					where id = ".$planExists[0]['circle_plans']['id'];
			$this->Circle->query ( $query );
		}
		
		$this->redirect("/circles/index/".$sqlData ['c_id']."/". $sqlData ['prod_code_pay_1']."/". $sqlData ['plan_type']);
	}
	
	/**
	 *
	 * @param unknown $id        	
	 */
	function editPlanForm($id) {
		// $this->autoRender = false;
// 		$this->layout = 'sims';
		// echo $id;
		$query = "SELECT id, c_id, prod_code_pay1, plan_type, plan_amt, plan_validity, plan_desc FROM circle_plans WHERE id = $id ";
		$data = $this->Circle->query ( $query );
		
		// echo "<pre>";
		// print_r($data);
		$this->set ( 'posts', $data );
		$this->render ( '/circles/editPlanForm/' );
	}
	
	/**
	 */
	function editPlanEntry() {
		// $this->autoRender = false;
		// $this->layout = false;
		$sqlData = $this->setQueryParameters ( $_POST );
		$sqlData ['id'] = $_POST ['id'];
		$query = "UPDATE circle_plans SET 
					opr_name = '" . $sqlData ['opr_name'] . "', 
					c_name = '" . $sqlData ['c_name'] . "',
					c_code_pay1 = '" . $sqlData ['c_code_pay1'] . "',
					c_type = '" . $sqlData ['c_type'] . "',
					c_id = " . $sqlData ['c_id'] . ",
					prod_code_pay1 = " . $sqlData ['prod_code_pay_1'] . ",
					plan_type = '" . $sqlData ['plan_type'] . "',
					plan_amt = " . $sqlData ['plan_amt'] . ",
					plan_validity = '" . $sqlData ['plan_validity'] . "',
					plan_desc = '" . $sqlData ['plan_desc'] . "',		
					show_flag = 1,
					updated = '" . $sqlData ['updated'] . "'   
					WHERE id = " . $sqlData ['id'];
		
		// echo "<pre>";
		$this->Circle->query ( $query );
// 		$this->index ();
// 		$this->set('circle',$sqlData ['c_name']);
// 		$this->set('operator', $sqlData ['opr_name']);
// 		$this->set('plantype', $sqlData ['plan_type']);
		$this->redirect("/circles/index/".$sqlData ['c_id']."/". $sqlData ['prod_code_pay_1']."/". $sqlData ['plan_type']);
		
		// print_r($query);
	}
	
	/**
	 *
	 * @param unknown $data        	
	 */
	function setQueryParameters($data) {
		$date = date ( 'Y-m-d H:i:s' );
		
		$circle = array ();
		$circle ['0'] ['c_name'] = "";
		$circle ['0'] ['c_code_pay1'] = "all";
		
		$circle ['1'] ['c_name'] = "AndhraPradesh";
		$circle ['1'] ['c_code_pay1'] = "AP";
		
		$circle ['2'] ['c_name'] = "Assam";
		$circle ['2'] ['c_code_pay1'] = "AS";
		
		$circle ['4'] ['c_name'] = "Chennai";
		$circle ['4'] ['c_code_pay1'] = "CH";
		
		$circle ['5'] ['c_name'] = "Delhi NCR";
		$circle ['5'] ['c_code_pay1'] = "DL";
		
		$circle ['6'] ['c_name'] = "Gujarat";
		$circle ['6'] ['c_code_pay1'] = "GJ";
		
		$circle ['7'] ['c_name'] = "Haryana,NE";
		$circle ['7'] ['c_code_pay1'] = "NE";
		
		$circle ['8'] ['c_name'] = "Himachal Pradesh";
		$circle ['8'] ['c_code_pay1'] = "HP";
		
		$circle ['9'] ['c_name'] = "Jammu & Kashmir";
		$circle ['9'] ['c_code_pay1'] = "JK";
		
		$circle ['3'] ['c_name'] = "Jharkand";
		$circle ['3'] ['c_code_pay1'] = "BR";
		
		$circle ['10'] ['c_name'] = "Karnataka";
		$circle ['10'] ['c_code_pay1'] = "KA";
		
		$circle ['11'] ['c_name'] = "Kerala";
		$circle ['11'] ['c_code_pay1'] = "KL";
		
		$circle ['12'] ['c_name'] = "Kolkata";
		$circle ['12'] ['c_code_pay1'] = "KO";
		
		$circle ['14'] ['c_name'] = "Madhya Pradesh";
		$circle ['14'] ['c_code_pay1'] = "MP";
		
		$circle ['13'] ['c_name'] = "Maharashtra";
		$circle ['13'] ['c_code_pay1'] = "MH";
		
		$circle ['15'] ['c_name'] = "Mumbai";
		$circle ['15'] ['c_code_pay1'] = "MU";
		
		$circle ['17'] ['c_name'] = "Orissa";
		$circle ['17'] ['c_code_pay1'] = "OR";
		
		$circle ['18'] ['c_name'] = "Punjab";
		$circle ['18'] ['c_code_pay1'] = "PB";
		
		$circle ['19'] ['c_name'] = "Rajasthan";
		$circle ['19'] ['c_code_pay1'] = "RJ";
		
		$circle ['20'] ['c_name'] = "Tamil Nadu";
		$circle ['20'] ['c_code_pay1'] = "TN";
		
		$circle ['16'] ['c_name'] = "Tripura";
		$circle ['16'] ['c_code_pay1'] = "NE";
		
		$circle ['21'] ['c_name'] = "Uttar Pradesh (East)";
		$circle ['21'] ['c_code_pay1'] = "UE";
		
		$circle ['22'] ['c_name'] = "Uttarakhand";
		$circle ['22'] ['c_code_pay1'] = "UW";
		
		$circle ['23'] ['c_name'] = "West Bengal";
		$circle ['23'] ['c_code_pay1'] = "WB";
		
		// Metros : CH-4, DL-5, KO-12, MU-15 :: rest States
		
		$operator = array ();
		$operator ['1'] = "Aircel";
		$operator ['2'] = "Airtel";
		$operator ['16'] = "Airtel DTH";
		$operator ['3'] = "BSNL";
		$operator ['18'] = "Dish TV";
		$operator ['4'] = "Idea";
		$operator ['30'] = "MTNL";
		$operator ['6'] = "MTS";
		$operator ['7'] = "Reliance CDMA";
		$operator ['17'] = "Reliance DTH";
		$operator ['8'] = "Reliance GSM";
		$operator ['9'] = "Tata Docomo";
		$operator ['10'] = "Tata Indicom";
		$operator ['19'] = "Sun TV DTH";
		$operator ['20'] = "Tata Sky DTH";
		$operator ['11'] = "Uninor";
		$operator ['12'] = "Videocon";
		$operator ['21'] = "Videocon Dth";
		$operator ['15'] = "Vodafone";
                $operator ['83'] = "Reliance Jio";
		
		// fields for data entry;
		$sqlData = array ();
		$sqlData ['opr_name'] = $operator [$data ['operator']];
		$sqlData ['c_name'] = $circle [$data ['circle']] ['c_name'];
		$sqlData ['c_code_pay1'] = $circle [$data ['circle']] ['c_code_pay1'];
		
		if ($data ['circle'] == 4 || $data ['circle'] == 5 || $data ['circle'] == 12 || $data ['circle'] == 15)
			$sqlData ['c_type'] = "Metros";
		else
			$sqlData ['c_type'] = "States";
		
		$sqlData ['c_id'] = $data ['circle'];
		$sqlData ['prod_code_pay_1'] = $data ['operator'];
		$sqlData ['plan_type'] = $data ['planType'];
		$sqlData ['plan_amt'] = $data ['planAmount'];
		$sqlData ['plan_validity'] = $data ['planValidity'];
		$sqlData ['plan_desc'] = $data ['planDescription'];
		$sqlData ['show_flag'] = 1;
		$sqlData ['updated'] = $date;
		
		$this->autoRender = false;
		return $sqlData;
	}
	

	function searchCircles() {
		$this->autoRender = false;
		$prod_code_pay1 = $_POST['operator_id'];

		$plans = array();
		$query = "SELECT distinct c_id, c_name from circle_plans WHERE prod_code_pay1 = $prod_code_pay1 ";
		$dataPlans = $this->Circle->query($query);

                foreach ($dataPlans as $index => $arr){
			if($arr['circle_plans']['c_name'] == '')
				continue;
			$plans[$arr['circle_plans']['c_id']] = $arr['circle_plans']['c_name'];
		}
	
		$data = array();
		if (!empty($plans)){
			$data['status'] = "success";
			$data['response'] = $plans;
		}
		else 
			$data['status'] = "failure";
		echo json_encode($data);
		die;
	}
	
	function searchPlans() {
		$this->autoRender = false;
		$c_id = $_POST['circle_id'];
		$prod_code_pay1 = $_POST['operator_id'];
		$plans = array();
// 		echo "circle id = $c_id";
// 		echo "operator_id = $prod_code_pay1";
// 		echo "<br>".empty($c_id)."<br>";

// 		if($c_id == "null" || empty($c_id)){
// 			$query = "SELECT distinct plan_type from circle_plans WHERE prod_code_pay1 = $prod_code_pay1 ";			
// 		}
// 		else{
		if($c_id == null)
			$query = "SELECT distinct plan_type from circle_plans WHERE prod_code_pay1 = $prod_code_pay1 ";
		else 
			$query = "SELECT distinct plan_type from circle_plans WHERE c_id = $c_id AND prod_code_pay1 = $prod_code_pay1 ";
// 		}

		$dataPlans = $this->Circle->query($query);

		foreach ($dataPlans as $index => $arr){
			if($arr['circle_plans']['plan_type']!="")
                            {
				$plans[$index] = $arr['circle_plans']['plan_type'];
			}			
		}
	
		if (!empty($plans)){
			$data['status'] = "success";
			$data['response'] = $plans;
		}
		echo json_encode($data);
		die;

	}
}
        
       