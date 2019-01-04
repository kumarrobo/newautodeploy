<?php
class MicrofinanceController extends AppController {
        var $name = 'Microfinance';
        var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv');
        var $components = array('RequestHandler','Shop','Documentmanagement');
        var $uses = array('User','Slaves','Microfinance');

        function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('*');
        }


        function getLoanDetails($type = null) {
        $this->layout = 'plain';

                $curl_url = PRAGATICAP_URL . '/getLoanLeadList';
                $curl_params = array();
                $curl_out = $this->General->curl_post($curl_url, $curl_params);
                $json_decoded_response = json_decode($curl_out['output'], true);
                if($json_decoded_response['status'] == "success") {
                        $loanDetails = $json_decoded_response['description'];


                        foreach($loanDetails as $key => $loan) {
                                $loanDetails[$key]['group_name'] =  $this->getGroupNames($loan['user_id']);
                                $loanDetails[$key]['mobile'] =  $this->getUserMobile($loan['user_id']);
                                $loanDisbursalUrl = PRAGATICAP_URL ."/disburseLoan?code=ash1)h*d" . "&applicationNumber=". $loan['application_number'];
                                $loanDetails[$key]['disbursal_url'] = $loanDisbursalUrl;
                                $loanDetails[$key]['created_timestamp'] = date( "Y-m-d H:i:s", strtotime($loan['created_timestamp']));
                                if($loanDetails[$key]['loan_duration_str'] == "m"){
                                	$loanDetails[$key]['loan_duration'] = $loanDetails[$key]['loan_duration'] . " Months";
                                }
                                else{
                                	$loanDetails[$key]['loan_duration'] = $loanDetails[$key]['loan_duration'] . " Days";
                                	
                                }
                        }
                        $this->set('loanDetails',$loanDetails);
                }

$curl_url = PRAGATICAP_URL . '/getVendorList';
                $curl_params = array();
                $curl_out = $this->General->curl_post($curl_url, $curl_params);
                $json_decoded_response = json_decode($curl_out['output'], true);
                if($json_decoded_response['status'] == "success") {
                	$vendorList = $json_decoded_response['description']['vendor_list'];
                }
                else{
                	$vendorList = array();
                }
                
                $this->set('vendorList',$vendorList);
                        $this->set('loantype',$type);

    }

   function verifyDocs() {
        $this->autoRender=false;
            $curl_url = PRAGATICAP_URL . '/verifyLeadDocs';
            //$curl_params = array('user_id' => $_REQUEST['user_id']);
            $curl_params = $_REQUEST;
            $curl_out = $this->General->curl_post($curl_url, $curl_params);
            echo $curl_out['output'];
   }


   function rejectDocs() {
        $this->autoRender=false;
        $curl_url = PRAGATICAP_URL . '/rejectLeadDocs';
        $curl_params = $_REQUEST;
        $curl_out = $this->General->curl_post($curl_url, $curl_params);
        echo $curl_out['output'];
   }

   function submitToNBFC() {
        $this->autoRender=false;
        $curl_url = PRAGATICAP_URL . '/submitLeadToNBFC';
        $curl_params = $_REQUEST;
        $curl_out = $this->General->curl_post($curl_url, $curl_params);
        echo $curl_out['output'];
   }

   function rejectLead() {
	   	$this->autoRender=false;
	   	$logger = $this->General->dumpLog('microfinance', 'microfinance');
	   	$logger->info("inside reject lead");

	   	$curl_url = PRAGATICAP_URL . '/updateApplicationStatus';
	   	$curl_params = $_REQUEST;
	   	$curl_out = $this->General->curl_post($curl_url, $curl_params);
	   	echo $curl_out['output'];
   }

   function approveLead() {
	   	$this->autoRender=false;
	   	$curl_url = PRAGATICAP_URL . '/updateApplicationStatus';
	   	$curl_params = $_REQUEST;
	   	$curl_out = $this->General->curl_post($curl_url, $curl_params);
	   	echo $curl_out['output'];
   }
   
   function  disburseLoan(){
	   	$this->autoRender=false;
	   	$curl_url = PRAGATICAP_URL . '/disburseVer2';
	   	$curl_params = $_REQUEST;
	   	$curl_out = $this->General->curl_post($curl_url, $curl_params);
	   	echo $curl_out['output'];
   }
    
   
   function getGroupNames($userId) {
        $usersResult=$this->Slaves->query("select users.*,groups.name from users join user_groups on (users.id = user_groups.user_id) join groups on (user_groups.group_id = groups.id)  where users.id='".$userId."'");
        $all_roles = array();
        foreach($usersResult as $uR) {
                $all_roles[] = $uR['groups']['name'];
        }
        $groupNames = implode(', ',$all_roles);
        return  $groupNames;
   }


   function rejectApplication($applicationNumber){
        $this->autoRender = false;
        $curl_url = PRAGATICAP_URL . '/rejectLoanLead';
        $curl_params = array('application_number' => $_REQUEST['application_number']);
        $curl_out = $this->General->curl_post($curl_url, $curl_params);
        echo $curl_out['output'];
   }

   function getUserMobile($userId) {
        $userRes = $this->Slaves->query("select mobile from users where id='".$userId."'");
        $mobile = $userRes[0]['users']['mobile'];
        return $mobile;

   }
   
   function setLoanDetails($type = null){
       $this->layout = 'plain';
       $default_count = array();
       $emi_date      = array();
       $loanDet       = array();
       $totdefault_count = array();

       if(!isset($type)){
           $type = '0'  ;
       }

       if($type == '0'){
           $loanDet = $this->Microfinance->query('select l.id, l.loan_number,l.total_amount,l.emi_date,b.user_id,l.payment_due,l.emi_count,l.due_date,l.start_date,l.loan_type from loan as l inner join borrower as b on b.id=l.borrower_id where l.payment_status = 2 order by l.id');
           $loanDefCount = $this->Microfinance->query('select COUNT(defaulter_count) as defaulter_count,loan_id from emi where defaulter_count > 0 group by loan_id ');

       }
       else {
         $loanDet = $this->Microfinance->query('select l.id,l.loan_number,l.total_amount,b.user_id,l.payment_due,l.emi_count,l.due_date,l.start_date,l.loan_type from loan as l inner join borrower as b on b.id=l.borrower_id where l.payment_status = 1 order by l.id');

         $lastemiPaid = $this->Microfinance->query('select MAX(l.emi_date) as emi_date,l.id,e.is_defaulter from loan as l INNER JOIN emi as e on (l.id = e.loan_id) where e.is_defaulter = 0  group by l.id');
         $loanDefCount = $this->Microfinance->query('select COUNT(defaulter_count) as defaulter_count,loan_id from emi where is_defaulter = 1 group by loan_id');
         $totLoanDefCount = $this->Microfinance->query('select COUNT(defaulter_count) as defaulter_count,loan_id from emi where defaulter_count > 0 group by loan_id ');
       }
       //For getting the defaulted count date for both partial and running loan
       foreach($loanDefCount as  $defCount){
           $default_count[$defCount['emi']['loan_id']] = $defCount[0]['defaulter_count'];
       }
       //for getting last paid emi date
       foreach ($lastemiPaid as $emiDate) {
            $emi_date[$emiDate['l']['id']] = $emiDate[0]['emi_date'];
       }
       //For getting the total defaulter count 
       foreach($totLoanDefCount as  $defCount){
           $totdefault_count[$defCount['emi']['loan_id']] = $defCount[0]['defaulter_count'];
       }
       //For Fetching the borrower name
       if (count($loanDet) > 0) {
         /** IMP DATA ADDED : START**/
          $userid = array_map(function($element){
             return $element['b']['user_id'];
         },$loanDet);


         $borrower_name = $this->Shop->getUserLabelData($userid,2,0);


      }
       $this->set('LoanDetails',$loanDet);
       $this->set('loantype',$type);
       $this->set('def_count',$default_count);
       $this->set('emi_date',$emi_date);
       $this->set('borrower_name',$borrower_name);
       $this->set('totdefault_count',$totdefault_count);
   }
   
   function setEmiDetails($id = null){
       $this->layout = 'plain';

       $emiDet = $this->Microfinance->query("select e.installment_amount, e.actual_amount,e.emi_date,e.defaulter_count,e.is_defaulter,e.loan_id from emi as e  where e.loan_id = '$id' group by e.id");
       $this->set('EmiDetails',$emiDet);

   }

}
