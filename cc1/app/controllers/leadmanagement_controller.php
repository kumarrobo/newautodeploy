<?php
 
class LeadmanagementController extends AppController{
	
	public $helpers = array();
	public $components = array('Shop', 'Auth', 'General');
	public $uses = array('User', 'Retailer', 'Slaves');	
	
	private $CREDS = array(
		'LeadSquared' => array(
			"api_url"			=>		"https://api.leadsquared.com/v2/LeadManagement.svc/",
			"access_key"			=>		'u$r51cd2432141bb991dcdedb7fd47aae52',
			"secret_key"			=>		"f2a728ebde12da26d7248e2002bd1b8f7f9eb26b"	
		)
	);
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('*');
	}
	
	function log($label, $data){
		$filename = "lead_management_".date('Ymd').".txt";
	
		if(is_array($data)){
			$data = json_encode($data);
		}
		$this->General->logData('/mnt/logs/'.$filename, $label."::".$data);
	}
	
	function request($url, $params, $headers, $label){
		$method = 'POST';
		if($method == 'GET'){
			if(!empty($params)){
				$params_string = array();
				foreach($params as $k => $p){
					$params_string[] = $k."=".$p;
				}
				$query_string = implode("&", $params_string);
				$url .= "?".$query_string;
				$params = array();
			}
		}
		$this->log("Curl request::".$label."::".$url."::".$method, $params);
		$response = $this->curl_post($url, $params, $headers);
		$this->log("Curl response::".$label."::".$url."::".$method, $response);
		
		return $response;
	}
	
	function curl_post($url, $params, $headers){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if(isset($headers))
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		if(isset($params))
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}
	
	function lsRequest($action, $json_payload, $headers){
		$url = $this->CREDS['LeadSquared']['api_url'].$action.
				"?accessKey=".$this->CREDS['LeadSquared']['access_key'].
				"&secretKey=".$this->CREDS['LeadSquared']['secret_key'];
		
		$this->log("lsRequest::", array($url, $json_payload, $headers));
		
		$response = $this->request($url, $json_payload, $headers);
		
		$this->log("lsRequest::", $response);
		
		$ls_response = array();
		if($response['success']){
			$response_object = json_decode($response['output']);
			$ls_response['status'] = "success";
			$ls_response['description'] = $response['output'];
			
			return $response_object;
		}
		else{
			$ls_response['status'] = "failure";
			$ls_response['description'] = $response['output'];
			return $ls_response;
		}
	}
	
	function getLeadsMetaData(){
		$this->autoRender = false;
		
		$action = "LeadsMetaData.Get";
		
		$response = $this->lsRequest($action);
		var_dump($response);
	}
	
	function createLead($columns){
		$this->log("createLead::", $columns);
		
		$this->autoRender = false;
		
		$action = "Lead.Create";
		
// 		$columns = array();
// 		$columns['mx_Shop_Name'] = 'Shop example';
// 		$columns['mx_Retailer_Name'] = 'Example';
// 		$columns['EmailAddress'] = 'example1@pay1.in';
// 		$columns['Mobile'] = '7101000000';
// 		$columns['mx_State']
// 		$columns['mx_City']
// 		$columns['mx_Area']
// 		$columns['mx_Pin_Code']
// 		$columns['mx_Messages']
// 		$columns['mx_Comment']
// 		$columns['mx_Date']
// 		$columns['mx_Timestamp']
// 		$columns['Source']
// 		$columns['mx_Interest']
// 		$columns['mx_Remark']
// 		$columns['mx_Status']
// 		$columns['mx_Agent_Name']
// 		$columns['mx_Updated_By']
// 		$columns['mx_Follow_Up_Date']
// 		$columns['mx_Lead_ID']
		
		$column_format_data = array();
		foreach($columns as $field => $column){
			$column_format_data[] = array(
				"Attribute" => 	$field,
				"Value"		=> 	$column
			);
		}
		$json_payload = json_encode($column_format_data);
		
		$this->log("createLead::", $json_payload);
		
		$headers = array('Content-Type: application/json');
		$response = $this->lsRequest($action, $json_payload, $headers);
	}
}	