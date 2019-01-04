<?php
class BusvendorsComponent extends Object {
        var $components = array('General');
        var $key = "2lGEjyPg1xsYRXbEdeymbfMn3guLpF"; 
	var $secret = "uq1a6kJUAKs4Ij1UYe0wAkMnIOEcA4";
	var $base_url = "http://api.seatseller.travel/";
	//var $base_url = "http://46.137.192.242/";
	//var $base_url = "http://beta.seatseller.travel:8080/";
        
        function errors($code){
		$err = array(
			'0'=>'Authentication Failed.',
			'1'=>'Account disabled.',
			'2'=>'Invalid Method.',
			'3'=>'Invalid Action.',
			'4'=>'Blank Parameters.',
			'5'=>'No Trips Available.',
			'6'=>'Date of journey is not valid.',
			'7'=>'Invalid Recharge Type.',
			'8'=>'Invalid Operator Code.',
	
			);
			return 	$err[$code];
	}
        
        function __construct(){
            App::import('Vendor', 'redbus/',array('file'=>'Oauthstore.php'));
            App::import('Vendor', 'redbus/',array('file'=>'Oauthrequester.php'));
            
        }
        
	function beforeFilter() { 
		parent::beforeFilter();
		$this->Auth->allow('*');
                $this->autoLayout = $this->autoRender = FALSE;
	}
        
        function seatLayout(){
            echo "Welcome to Pay1 Bus reservation system.";
        }
        
        public function invokeGetRequest($requestUrl)
	{
		//global $key, $secret, $base_url,$source,$destination,$doj,$tripId,$boarding;
                $logger = $this->General->dumpLog('invokeGetRequest', 'rbGetRequests');
                $logger->info("Parameters |".json_encode($requestUrl));
		$url = $this->base_url.$requestUrl;
		$curl_options = array(CURLOPT_HTTPHEADER => array('Content-Type: application/json'), CURLOPT_TIMEOUT => 0, 				CURLOPT_CONNECTTIMEOUT => 0);
		$options = array('consumer_key' => $this->key, 'consumer_secret' => $this->secret);
		OAuthStore::instance("2Leg", $options);
		$method = "GET";
		$params = null;
		try
		{
			$request = new OAuthRequester($url, $method, $params);
                        $result = $request->doRequest();
			$response = $result['body'];
			return $response;
		}
		catch(OAuthException2 $e)
		{
			echo $e;
		}
		catch(Exception $e1)
		{
			echo $e1;
		}
	}
        
        function invokePostRequest($requestUrl, $requestUrl)
	{
                $logger = $this->General->dumpLog('Booking Request', 'rbPostRequests');
                $logger->info("Parameters |".json_encode($requestUrl));
		//global $key, $secret, $base_url;
		$url = $this->base_url.$requestUrl;
		$curl_options = array(CURLOPT_HTTPHEADER => array('Content-Type: application/json'), CURLOPT_TIMEOUT => 0, 				CURLOPT_CONNECTTIMEOUT => 0);
		$options = array('consumer_key' => $this->key, 'consumer_secret' => $this->secret);
		OAuthStore::instance("2Leg", $options);
		$method = "POST";
		$params = null;
		try
		{
			$request = new OAuthRequester($url, $method, $params, $blockRequest);
			echo "Timeout is: ".$curl_options[CURLOPT_TIMEOUT]."<hr></br>";
			echo "Connection timeout is: ".$curl_options[CURLOPT_CONNECTTIMEOUT ]."<hr></br>";
			$result = $request->doRequest(0,$curl_options);
			$response = $result['body'];
			return $response;
		}
		catch(OAuthException2 $e)
		{       
                        $logger->info("OAuthException2 |".$e);
			echo "Exception happened".$e."<hr></br>";
		}
		catch(Exception $e1)
		{
                        $logger->info("generic exception |".$e1);
			echo "generic exception".$e1."<hr></br>";
		}
	}

	function getAllSources()
	{
		return $this->invokeGetRequest("sources");
	}
	
	function getAllDestinations($sourceId)
	{
		return $this->invokeGetRequest("destinations?source=".$sourceId);
	}

	function getAvailableTrips($sourceId,$destinationId,$date)
	{
		return $this->invokeGetRequest("availabletrips?source=".$sourceId. "&destination=" . $destinationId . "&doj=" . $date); 		
	}
	
	function getBoardingPoint($boarding)
	{
		return $this->invokeGetRequest("boardingPoint?id=".$boarding);
	}

	function getTripDetails($tripId)
	{
		return $this->invokeGetRequest("tripdetails?id=".$tripId); 	
	}
	
	function blockTicket($blockRequest)
	{	
		/*foreach($blockRequest->inventoryItems as $inventory)
		{
			echo "</hr></br>Seat Name:".$inventory->name;
			echo "</hr></br>Fare:".$inventory->fare;
			echo "</hr></br>Gender:".$inventory->ladiesSeat."</hr></br>";
		}
		*/	return $this->invokePostRequest("blockTicket",$blockRequest); 
	}

	function confirmTicket($blockKey)
	{
			return $this->invokePostRequest("bookticket?blockKey=".$blockKey,"");
	} 
	function getTicket($ticketId)
	{
		
		return $this->invokeGetRequest("ticket?tin=".$ticketId);
	}

	function getCancellationData($cancellationId)
	{
		
		return $this->invokeGetRequest("cancellationdata?tin=".$cancellationId);
		echo " <hr>The ticket details are:".$cancellationId."<hr/>";
	}

        
	function cancelTicket($cancelRequest)
	{
		return $this->invokePostRequest("cancelticket",$cancelRequest);
	}
       
        
}

?>
