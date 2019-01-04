<?php
class PromotionsController extends AppController {

	var $name = 'Promotions';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
	var $components = array('RequestHandler','Shop','busvendors','General');
	var $uses = array('User','Retailer');
	
	function beforeFilter() {parent::beforeFilter();$this->Auth->allow('*');}

	function test(){
		echo "1"; exit;
		$this->autoRender = false;
	}
	
	function campaign1(){
		$data = $this->Retailer->query("select distinct vendors_activations.mobile from vendors_activations USE INDEX(idx_date),retailers where retailer_id = retailers.id AND retailers.parent_id = 100 ANd date > '2014-04-01' AND date <= '2014-04-23' group by vendors_activations.mobile having (sum(amount) >= 120 AND sum(amount) <= 230)");
		$mobiles = array();
		$dbData = array();
		
//		$message = "Now cut your mobile expenses by 30% just give a miss call 02267242267\nConvert to Idea Post Paid & Enjoy: Plan 199, 600mins/400SMS Free, 30p Local, 50p STD";
		//this function is used for MsgTemplate
                $MsgTemplate = $this->General->LoadApiBalance(); 
		$message = $MsgTemplate['Promotions_Campaign_MSG'];
                
		$query="INSERT INTO promotions (mobile,msg,type,created,date) VALUES ";
		foreach($data as $dt){
			$mobiles[] = $dt['vendors_activations']['mobile'];
			$dbData[] = "('".$dt['vendors_activations']['mobile']."','".addslashes($message)."','prepaid to postpaid','".date('Y-m-d H:i:s')."','".date('Y-m-d')."')"; 
		}
		$query .= implode(",",$dbData);
		$this->Retailer->query($query);
		$this->printArray($mobiles);
		
		//$this->General->sendMessage($mobiles,$message,'promo');
		//$this->General->sendMails("SMS Promotional Campaign to Pay1 Users","Message: $message<br/>No of users: ". count($mobiles),array('tadka@pay1.in'),'mail');
		$this->autoRender = false;
	}
	
	function campaign2(){
		$data = $this->Retailer->query("select id from retailers where parent_id = 100");
		$retailers = array();
                //this function is used for MsgTemplate
                $MsgTemplate = $this->General->LoadApiBalance();
		foreach($data as $dt){
			$retailers[] = $dt['retailers']['id'];
		}
		
		$data = $this->Retailer->query("select distinct mobile from promotions");
		$oldData = array();
		foreach($data as $dt){
			$oldData[] = $dt['promotions']['mobile'];
		}
		
		$data = $this->Retailer->query("select distinct vendors_activations.mobile from vendors_activations where retailer_id in (".implode(",",$retailers).") ANd date > '2014-03-01' AND date <= '2014-03-31' group by vendors_activations.mobile having (sum(amount) >= 100 AND sum(amount) <= 300)");
		$mobiles = array();
		$dbData = array();
		$totMsg = 3000;
		
//		$message = "Now cut your mobile expenses by 30% just give a miss call 02267242267\nConvert to Idea Post Paid & Enjoy: Plan 199, 600mins/400SMS Free, 30p Local, 50p STD";
		$message = $MsgTemplate['Promotions_Campaign_MSG'];
		$i = 0;
		$query="INSERT INTO promotions (mobile,msg,type,created,date) VALUES ";
		foreach($data as $dt){
			if(!in_array($dt['vendors_activations']['mobile'],$oldData)){
				$mobiles[] = $dt['vendors_activations']['mobile'];
				$dbData[] = "('".$dt['vendors_activations']['mobile']."','".addslashes($message)."','prepaid to postpaid','".date('Y-m-d H:i:s')."','".date('Y-m-d')."')"; 
				if($i > $totMsg*1.5) break;
				$i++;
			}
		}
		$query .= implode(",",$dbData);
		$this->Retailer->query($query);
		$this->printArray($mobiles);
		
		$this->General->sendMessage($mobiles,$message,'notify');
		$this->General->sendMails("SMS Promotional Campaign to Pay1 Users","Message: $message<br/>No of users: ". count($mobiles),array('tadka@pay1.in'),'mail');
		$this->autoRender = false;
	}
	
	/*function oldRetailers(){
		$data = $this->Retailer->query("select mobile from retailers where substr(mobile,1,1) in ('9','8','7') AND id not in (select distinct retailer_id from retailers_logs where date >= '2014-04-01')");
		$mobiles = array();
                //this function is used for MsgTemplate
                $MsgTemplate = $this->General->LoadApiBalance();
		
		foreach($data as $dt){
			$mobiles[] = $dt['retailers']['mobile'];
		}
		
//		$message = "Hi,
//More fast n friendly, the new pay1 mobile app. Accessible via SMS n GPRS. Activate ur a/c today to enjoy Pay1 benefits. Call today 022-67242288
//-Pay1";
            	$message = $MsgTemplate['Old_Retailers_MSG'];
                
		$this->General->sendMessage($mobiles,$message,'notify');
		$this->autoRender = false;
	}*/
	
	function sendmail($excelfile='email.xls', $filename='pay1_partners_0207.html') {
		$docroot = $_SERVER['DOCUMENT_ROOT'] . "/" . $filename;
		$getcontent = file_get_contents($docroot);
		App::import('Vendor', 'excel_reader2');
		$excel = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT'] . "/" . $excelfile, true);

		$headers = "From: Pay1 <info@pay1.in>\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$data = $excel->sheets[0]['cells'];
		foreach ($data as $key => $val) {
			foreach ($val as $k => $v) {
				$trimmedV = trim($v);
				if ($v != 'email' && !empty($trimmedV)) {
					mail($v,"Be a part of India's #1 Cash to Digital Network",$getcontent,$headers);
				}
			}
		}
		$this->autoRender = false;
	}
                
        /**
         * This function will be used to display banner on Android apps and web
         * @param type $appId : (0:default, 1: merchantApp, 2: distApp)
         * @param type $groupId : (0:default, 1: retailer, 2:distributor, 3:rm)
         */
        function fillAdSpace($appId=0, $groupId=0){
            $this->autoRender = false;
            //$this->redirect("https://shopscdn.s3.amazonaws.com/smartbuy-promotion/smartbuy-promo.html");
            $this->layout = null;
            $this->render('ad_space_page');
        }
}
