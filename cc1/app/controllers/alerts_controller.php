
<?php

/**
 * GENERATES ALERTS FOR UNCONSISITENCY IN SALES
 * @AUTHOR RISHABH GUPTA
 */
class AlertsController extends AppController {
	var $name = 'Alerts';
	var $helpers = array (
			'Html',
			'Ajax',
			'Javascript',
			'Minify',
			'Paginator'
	);
	var $components = array (
			'RequestHandler',
			'Shop'
	);
	var $uses = array (
			'Retailer',
			'Slaves'
	);

	function beforeFilter() {
		set_time_limit ( 0 );
		ini_set ( "memory_limit", "512M" );
		parent::beforeFilter ();
		$this->Auth->allow ( '*' );
	}

	/**
	 * REPORTS THE SALES DOWN IF THE SALES OF
	 * DISTRIBUTOR OR RETAILES ARE GOING DOWN BY CERTAIN RANGE
	 */
	function salesDownDistributor() {
		$this->autoRender = false;

		// BASE COMPARISON SALES FOR EACH USER
		$dateMinus4 = date ( 'Y-m-d', strtotime ( '-4 days' ) );
		$dateMinus33 = date ( 'Y-m-d', strtotime ( '-33 days' ) );
		// $dataBaseSales = $this->Retailer->query("
		// SELECT
		// distributor_id, SUM(topup_sold) as base_sale, SUM(transacting) as base_transacting
		// FROM
		// distributors_logs
		// WHERE
		// date between '$dateMinus33' and '$dateMinus4'
		// GROUP BY
		// distributor_id"
		// );

		// benchmark_value gives BASE SALES RETAILERS
		// transacting_retailer gives BASE TRANSACTING RETAILERS
		$dataBaseSales = $this->Slaves->query ( "
								SELECT
									id, benchmark_value, transacting_retailer
								FROM
									distributors" );
		$baseSales = array ();
		$baseTransacting = array ();

		// echo count($dataBaseSales)."<br>";

		// foreach ($dataBaseSales as $index => $values){
		// $baseSales[$values['distributors_logs']['distributor_id']] = $values[0]['base_sale']/30;
		// $baseTransacting[$values['distributors_logs']['distributor_id']] = $values[0]['base_transacting']/30;
		// }

		foreach ( $dataBaseSales as $index => $arr ) {
			$baseSales [$arr ['distributors'] ['id']] = $arr ['distributors'] ['benchmark_value'];
			$baseTransacting [$arr ['distributors'] ['id']] = $arr ['distributors'] ['transacting_retailer'];
		}

		// echo "<pre>";
		// print_r($baseSales);
		// print_r($baseTransacting);

		// DATES RANGES CAN BE CHANGED AS PER THE REQUIREMENT
		// DOWN SALES OF DISTRIBUTOR BY %AGE SALES BY 15% OR RETAILER TRANSACTING COUNT

		$dateMinus3 = date ( 'Y-m-d', strtotime ( '-3 days' ) );
		$dateMinus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );
		$sales = $this->Slaves->query ( "
     				SELECT
     					distributors.id, topup_sold, transacting, date
     				FROM
     					users_logs as distributors_logs
                                JOIN distributors ON (distributors.user_id = distributors_logs.user_id)
     				WHERE
     					date between '$dateMinus3' and '$dateMinus1'
   					ORDER BY
   						distributors.id" );

		// echo "The sales are : <br>";
		// print_r($sales);
		$dataTopup = array ();
		$dataRetailers = array ();
		$idsDownSales15 = array ();
		$idDownSalesCount15 = 0;
		$idsDownSales30 = array ();
		$idDownSalesCount30 = 0;
		$idsDownRetailers = array ();
		$idDownRetailersCount = 0;

		foreach ( $sales as $id => $values ) {
			$dataTopup [$values ['distributors'] ['id']] [$values ['distributors_logs'] ['date']] = $values ['distributors_logs'] ['topup_sold'];
			$dataRetailers [$values ['distributors'] ['id']] [$values ['distributors_logs'] ['date']] = $values ['distributors_logs'] ['transacting'];
		}

		// ITERATING OVER ALL THE TOPUP_SALES FOR EACH DISTRIBUTOR
		foreach ( $dataTopup as $id => $dates ) {
			// echo "<br>$id <br>";
			// FOR COMPARISON OF DOWN SALES
			$baseSale = isset ( $baseSales [$id] ) ? $baseSales [$id] : 0;

			// BASE SALES MUST BE ATLEAST RS. 10,000
			if ($baseSale < 10000) {
				continue;
			}
			$saleCountDay15 = count ( $dataTopup [$id] );
			$saleCountDay30 = 0;
			$baseSale15Percent = 0.15 * $baseSale; // 15 percent of base sale
			$baseSale30Percent = 0.30 * $baseSale;
			$downSaleCount15 = 0; // must be equal to $saleCount
			$downSaleCount30 = 0;
			$saleDiff = 0;

			/**
			 * CONDITION CHECK: 3 DAYS CONSECUTIVE SALES DOWN 15 PERCENT
			 */
			foreach ( $dates as $date => $value ) {
				// echo" $date => $value <br> ";
				$saleDiff = $baseSale - $value;
				if ($saleDiff > $baseSale15Percent) {
					$downSaleCount15 ++;
				}
			}
			// saleCountDay =0 : No sales has been done in the past 3 days
			if ($downSaleCount15 == $saleCountDay15) {
				$idsDownSales15 [$idDownSalesCount15 ++] = $id;
			}

			/**
			 * CONDITION CHECK: 2 DAYS CONSECUTIVE SALES DOWN 30 PERCENT
			 * @VAR UNKNOWN
			 */
			$currentDate = date ( 'Y-m-d', strtotime ( '-1 days' ) );
			$currentDateMinus3 = date ( 'Y-m-d', strtotime ( '-3 days' ) ); // change it to current date from php date function in YYYY-MM-DD format
			if (array_key_exists ( $currentDateMinus3, $dates )) {
				$saleCountDay30 = $saleCountDay15 - 1; // count($dataTopup[$id])
			} else {
				$saleCountDay30 = $saleCountDay15;
			}
			foreach ( $dates as $date => $value ) {
				if ($date == $currentDateMinus3) {
					continue;
				}
				$saleDiff = $baseSale - $value;
				if ($saleDiff > $baseSale30Percent) {
					$downSaleCount30 ++;
				}
			}

			if ($downSaleCount30 == $saleCountDay30) {
				$idsDownSales30 [$idDownSalesCount30 ++] = $id;
			}
		}

		// echo "<br> The data top up is </br><pre>";
		// print_r($dataTopup);
		// echo "<br><br>The defaulters for down sales for 3 consecutive days are : <br> <pre>";
		// print_r($idsDownSales15);
		$stringIdsDownSales15 = implode ( ",", $idsDownSales15 );
		// echo "<br>stringIdsDownSales15 is : $stringIdsDownSales15 <br>";

		// $dataDefaulterDownSales15 = $this->Retailer->query("
		// SELECT
		// D.id, D.name, D.company, D.state, U.mobile
		// FROM
		// distributors AS D
		// LEFT JOIN
		// users AS U
		// ON
		// D.user_id = U.id
		// WHERE
		// D.id in ($stringIdsDownSales15)"
		// );

		$query = "  SELECT D.id, D.name, D.company, D.state,D.mobile, rm.name as Relationship_Manager
					FROM distributors AS D, rm
					WHERE  D.rm_id = rm.id
					AND D.id in ($stringIdsDownSales15)";



		$dataDefaulterDownSales15 = $this->Slaves->query ( $query );
		// echo "<pre>";
		// print_r($dataDefaulterDownSales15);

		$mail_subject = "Distributor Secondary Down 15% - 3 days";
		$mail_body = "<br/><br/><b><h2>Distributor Secondary Down 15% - 3 days</h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Company</th>
							<th align = 'center'>State</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Relationship Manager</th>
							<th align = 'center'>Benchmark of Sales</th>
							<th align = 'center'> Sale on " . (date ( 'Y-m-d', strtotime ( '-1 days' ) )) . "</th>
							<th align = 'center'> Sale on " . (date ( 'Y-m-d', strtotime ( '-2 days' ) )) . "</th>
							<th align = 'center'> Sale on " . (date ( 'Y-m-d', strtotime ( '-3 days' ) )) . "</th>
						</tr>";

        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($stringIdsDownSales15,2,3);
        /** IMP DATA ADDED : END**/

		foreach ( $dataDefaulterDownSales15 as $index => $arr ) {

            $arr['D']['name'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['name'] : $arr['D']['name'];
            $arr['D']['company'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['shop_est_name'] : $arr['D']['company'];

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";
			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// GETTING SALES RECORDS
			$baseSale = isset ( $baseSales [$arr ['D'] ['id']] ) ? $baseSales [$arr ['D'] ['id']] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $baseSale, 2 ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-1 days' ) ));
			$dateSale = isset ( $dataTopup [$arr ['D'] ['id']] [$date] ) ? $dataTopup [$arr ['D'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateSale, 2 ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-2 days' ) ));
			$dateSale = isset ( $dataTopup [$arr ['D'] ['id']] [$date] ) ? $dataTopup [$arr ['D'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateSale, 2 ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-3 days' ) ));
			$dateSale = isset ( $dataTopup [$arr ['D'] ['id']] [$date] ) ? $dataTopup [$arr ['D'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateSale, 2 ) . "</td>";

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table >";
		// $mail_body .= "<br/>";
		// echo " The mail body is as follows: <br> $mail_body";
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in'
		), 'mail' );

		// echo "<br><br> The defaulters for down sales for 2 consecutive days are : <br> <pre>";
		$stringIdsDownSales30 = implode ( ",", $idsDownSales30 );
		// echo "<br>stringIdsDownSales30 is : $stringIdsDownSales30 <br>";

		// $dataDefaulterDownSales30 = $this->Retailer->query("
		// SELECT
		// D.id, D.name, D.company, D.state, U.mobile
		// FROM
		// distributors AS D
		// LEFT JOIN
		// users AS U
		// ON
		// D.user_id = U.id
		// WHERE
		// D.id in ($stringIdsDownSales30)"
		// );
		$query = "  SELECT D.id, D.name, D.company, D.state,D.mobile, rm.name as Relationship_Manager
					FROM distributors AS D,  rm
					WHERE  D.rm_id = rm.id
					AND D.id in ($stringIdsDownSales30)";

		$dataDefaulterDownSales30 = $this->Slaves->query ( $query );

		// print_r($dataDefaulterDownSales30);

		$mail_subject = "Distributor Secondary Down 30% - 2 days ";
		$mail_body = "<br/><br/><b><h2>Distributor Secondary Down 30% - 2 days </h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Company</th>
							<th align = 'center'>State</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Relationship Manager</th>
							<th align = 'center'>Benchmark of Sales</th>
							<th align = 'center'> Sale on " . (date ( 'Y-m-d', strtotime ( '-1 days' ) )) . "</th>
							<th align = 'center'> Sale on " . (date ( 'Y-m-d', strtotime ( '-2 days' ) )) . "</th>
						</tr>";

        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($stringIdsDownSales30,2,3);
        /** IMP DATA ADDED : END**/
		foreach ( $dataDefaulterDownSales30 as $index => $arr ) {

            $arr['D']['name'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['name'] : $arr['D']['name'];
            $arr['D']['company'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['shop_est_name'] : $arr['D']['company'];

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";
			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// GETTING SALES RECORDS
			$baseSale = isset ( $baseSales [$arr ['D'] ['id']] ) ? $baseSales [$arr ['D'] ['id']] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $baseSale, 2 ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-1 days' ) ));
			$dateSale = isset ( $dataTopup [$arr ['D'] ['id']] [$date] ) ? $dataTopup [$arr ['D'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateSale, 2 ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-2 days' ) ));
			$dateSale = isset ( $dataTopup [$arr ['D'] ['id']] [$date] ) ? $dataTopup [$arr ['D'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateSale, 2 ) . "</td>";

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table >";
		// $mail_body .= "<br/>";
		// echo " The mail body is as follows: <br> $mail_body";
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in'
		), 'mail' );

		// ITERATING OVER TRANSACTING RETAILERS OF EACH DISTRIBUTOR
		foreach ( $dataRetailers as $id => $dates ) {
			// echo "<br>$id <br>";

			// FOR COMPARISON OF DOWN SALES (GET NUMBER OF ROWS)
			$saleCountDay = count ( $dataRetailers [$id] );
			$baseTranscatingRetailer = isset ( $baseTransacting [$id] ) ? $baseTransacting [$id] : 0;

			if ($baseTranscatingRetailer < 15) {
				continue;
			}

			$downRetailerCount = 0; // must be equal to $saleCount

			foreach ( $dates as $date => $value ) {
				if ($baseTranscatingRetailer - $value > 10) {
					$downRetailerCount ++;
				}
			}

			// saleCountDay = 0: No retailers have transacted in the past 3 days
			if ($downRetailerCount == $saleCountDay) {
				$idsDownRetailers [$idDownRetailersCount ++] = $id;
			}
		}
		// echo "<br><br>The defaulters for retailers transacting for 3 days are: <br> <pre>";
		// print_r($idsDownRetailers);
		$stringIdsDownRetailers = implode ( ",", $idsDownRetailers );
		// echo "<br>stringIdsDownRetailers is : $stringIdsDownRetailers <br>";

		// $dataDefaulterDownRetailers = $this->Retailer->query("
		// SELECT
		// D.id, D.name, D.company, D.state, U.mobile
		// FROM
		// distributors AS D
		// LEFT JOIN
		// users AS U
		// ON
		// D.user_id = U.id
		// WHERE
		// D.id in ($stringIdsDownRetailers)"
		// );

		$query = "  SELECT D.id, D.name, D.company, D.state,D.mobile, rm.name as Relationship_Manager
					FROM distributors AS D, rm
					WHERE D.rm_id = rm.id
					AND D.id in ($stringIdsDownRetailers)";

		// echo "<pre> <h2>Distributor 10 R Down - 3 days</h2> <br>";
		$dataDefaulterDownRetailer = $this->Slaves->query ( $query );
		// print_r($dataDefaulterDownRetailer);

		// print_r($dataDefaulterDownRetailers);
		$mail_subject = "Distributor 10 R Down - 3 days";
		$mail_body = "<br/><br/><b><h2>Distributor 10 R Down - 3 days</h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Company</th>
							<th align = 'center'>State</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Relationship Manager</th>
							<th align = 'center'>Benchmark of Retailers Transacting</th>
							<th align = 'center'> Retailers Transacting on " . (date ( 'Y-m-d', strtotime ( '-1 days' ) )) . "</th>
							<th align = 'center'> Retailers Transacting on " . (date ( 'Y-m-d', strtotime ( '-2 days' ) )) . "</th>
							<th align = 'center'> Retailers Transacting on " . (date ( 'Y-m-d', strtotime ( '-3 days' ) )) . "</th>
						</tr>";
        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($stringIdsDownRetailers,2,3);
        /** IMP DATA ADDED : END**/

		foreach ( $dataDefaulterDownRetailer as $index => $arr ) {

            $arr['D']['name'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['name'] : $arr['D']['name'];
            $arr['D']['company'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['shop_est_name'] : $arr['D']['company'];

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";
			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// GETTING SALES RECORDS
			$base = isset ( $baseTransacting [$arr ['D'] ['id']] ) ? $baseTransacting [$arr ['D'] ['id']] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $base ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-1 days' ) ));
			$dateTransacting = isset ( $dataRetailers [$arr ['D'] ['id']] [$date] ) ? $dataRetailers [$arr ['D'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateTransacting ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-2 days' ) ));
			$dateTransacting = isset ( $dataRetailers [$arr ['D'] ['id']] [$date] ) ? $dataRetailers [$arr ['D'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateTransacting ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-3 days' ) ));
			$dateTransacting = isset ( $dataRetailers [$arr ['D'] ['id']] [$date] ) ? $dataRetailers [$arr ['D'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateTransacting ) . "</td>";

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table >";
		// $mail_body .= "<br/>";
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in'
		), 'mail' );
		// echo " The mail body is as follows: <br> $mail_body";

		/**
		 * Weekly 10 retailers down under distributor
		 */
		// $dateMinus37 = date('Y-m-d', strtotime('-37 days')) ;
		// $dateMinus8 = date('Y-m-d', strtotime('-8 days'));
		// $dataBaseTransactingWeekly = $this->Retailer->query("
		// SELECT
		// distributor_id, SUM(transacting) as base_transacting
		// FROM
		// distributors_logs
		// WHERE
		// date between '$dateMinus37' and '$dateMinus8'
		// GROUP BY
		// distributor_id"
		// );

		// $baseTransactingWeekly = array();

		// BASE FOR TRANSACTING WEEKLY = BENCHMARK VALUE IN TABLE
		$baseTransactingWeekly = $baseTransacting;
		// echo "The base transacting weeky are : ?<br><pre>".count($dataBaseTransactingWeekly)."<br>";
		// print_r($dataBaseTransactingWeekly);

		// foreach ($dataBaseTransactingWeekly as $id => $values){
		// $baseTransactingWeekly[$values['distributors_logs']['distributor_id']] = $values[0]['base_transacting']/30;
		// }

		// print_r($baseTransactingWeekly);

		// WEEKLY SALES: SET DATES BY PHP FUNCTIONS
		$dateMinus7 = date ( 'Y-m-d', strtotime ( '-7 days' ) );
		$dateMinus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );

		$dataTransactingWeekly = $this->Slaves->query ( "
		     				SELECT
		     					distributors.id, SUM(transacting) as week_transacting
		     				FROM
		     					users_logs as distributors_logs
                                                JOIN distributors ON (distributors.user_id = distributors_logs.user_id)
		     				WHERE
		     					date between '$dateMinus7' and '$dateMinus1'
		   					GROUP BY
		   						distributor_id" );

		// print_r($dataTransactingWeekly);

		$transactingWeekly = array ();
		$idsDownTransactingWeekly = array ();
		$idDownTransactingWeeklyCount = 0;

		foreach ( $dataTransactingWeekly as $id => $values ) {
			$transactingWeekly [$values ['distributors'] ['id']] = intval ( $values [0] ['week_transacting'] / 7 );
		}

		foreach ( $transactingWeekly as $id => $averageTransacting ) {
			$baseTranscatingRetailerWeekly = isset ( $baseTransactingWeekly [$id] ) ? intval ( $baseTransactingWeekly [$id] ) : 0;

			if ($baseTranscatingRetailerWeekly < 15) {
				continue;
			}

			// $downRetailerCount = 0; // must be equal to $saleCount

			// saleCountDay = 0: No retailers have transacted in the past 3 days
			if ($baseTranscatingRetailerWeekly - $averageTransacting > 10) {
				$idsDownTransactingWeekly [$idDownTransactingWeeklyCount ++] = $id;
			}
		}

		$stringIdsDownTransactingWeekly = implode ( ",", $idsDownTransactingWeekly );
		// echo "<br>stringIdsDownTransacting Weekly is : $stringIdsDownTransactingWeekly <br>";

		// $dataDefaulterTransactingWeekly = $this->Retailer->query("
		// SELECT
		// D.id, D.name, D.company,D.state, U.mobile
		// FROM
		// distributors AS D
		// LEFT JOIN
		// users AS U
		// ON
		// D.user_id = U.id
		// WHERE
		// D.id in ($stringIdsDownTransactingWeekly)"
		// );

		$query = "  SELECT D.id, D.name, D.company, D.state,D.mobile, rm.name as Relationship_Manager
					FROM distributors AS D, rm
					WHERE D.rm_id = rm.id
					AND D.id in ($stringIdsDownTransactingWeekly)";

		$dataDefaulterTransactingWeekly = $this->Slaves->query ( $query );
		// echo "defaulters transacting weekly <br><pre>";
		// print_r($dataDefaulterTransactingWeekly);

		$mail_subject = "Distributor 10 R Down - week";
		$mail_body = "<br/><br/><b><h2>Distributor 10 R Down - week</h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Company</th>
							<th align = 'center'>State</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Relationship Manager</th>
							<th align = 'center'>Benchmark of Retailers Transacting</th>
							<th align = 'center'> Average Retailers Transacting Between " . (date ( 'Y-m-d', strtotime ( '-1 days' ) )) . " and " . (date ( 'Y-m-d', strtotime ( '-7 days' ) )) . "</th>
						</tr>";
        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($stringIdsDownTransactingWeekly,2,3);
        /** IMP DATA ADDED : END**/

		foreach ( $dataDefaulterTransactingWeekly as $index => $arr ) {

            $arr['D']['name'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['name'] : $arr['D']['name'];
            $arr['D']['company'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['shop_est_name'] : $arr['D']['company'];

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";
			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// getting sales records
			$base = isset ( $baseTransacting [$arr ['D'] ['id']] ) ? $baseTransacting [$arr ['D'] ['id']] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $base ) . "</td>";

			$dateStart = (date ( 'Y-m-d', strtotime ( '-1 days' ) ));
			$dateEnd = (date ( 'Y-m-d', strtotime ( '-7 days' ) ));
			$dateTransacting = isset ( $transactingWeekly [$arr ['D'] ['id']] ) ? $transactingWeekly [$arr ['D'] ['id']] : 0;
			$mail_body .= "<td align = 'center'>" . $dateTransacting . "</td>";

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table >";
		// $mail_body .= "<br/>";
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in'
		), 'mail' );

		// echo " The mail body is as follows: <br> $mail_body";
	}

	/**
	 * GENERATE ALERTS FOR DOWNGRADING OF RETAILERS UNDER DISTRIBUTORS
	 * SHIFTING OF SUFFICIENT NUMBER OF RETAILERS TO YELLOW OR RED ZONE
	 * SHIFTING OF RETAILERS FROM 20%
	 * AND
	 * LIST OF RETAILERS WHOSE SALES ARE GOING DOWN BY 50%
	 */
	function salesDownRetailer() {
		$this->autoRender = false;

		/**
		 * RETAILERS HAVING SALES DOWN BY 50%
		 */

		// $dateMinus33 = date('Y-m-d', strtotime('-33 days'));
		// $dateMinus4 = date('Y-m-d', strtotime('-4 days'));
		$dateMinus3 = date ( 'Y-m-d', strtotime ( '-3 days' ) );
		$dateMinus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );

		$dataBaseSalesRetailers = $this->Slaves->query ( "
									 SELECT
										id, ret_benchmark_value
									 FROM
										retailers" );
		// echo "<pre>";
		// print_r($dataBaseSalesRetailers);
		$baseSalesRetailers = array ();

		foreach ( $dataBaseSalesRetailers as $index => $arr ) {
			$baseSalesRetailers [$arr ['retailers'] ['id']] = $arr ['retailers'] ['ret_benchmark_value'];
		}

		// GETTING LAST 3 DAYS SALE
		$dataRecentSalesRetailers = $this->Slaves->query ( "SELECT r.id AS retailer_id, SUM(rel.amount) AS sale, rel.date "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE rel.date BETWEEN '$dateMinus3' AND '$dateMinus1' "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id,rel.date" );

		$recentSalesRetailers = array ();

		foreach ( $dataRecentSalesRetailers as $index => $arr ) {
			$recentSalesRetailers [$arr ['r'] ['retailer_id']] [$arr ['rel'] ['date']] = $arr [0] ['sale'];
		}

		$idsDownSalesRetailers = array ();
		$idsDownSalesRetailersCount = 0;

		foreach ( $recentSalesRetailers as $id => $dates ) {
			// FOR COMPARISON OF DOWN SALES
			$baseSale = isset ( $baseSalesRetailers [$id] ) ? $baseSalesRetailers [$id] : 0;

			// BASE SALES MUST BE ATLEAST RS. 100
			if ($baseSale < 100) {
				continue;
			}
			$saleCountDay = count ( $recentSalesRetailers [$id] );
			$baseSale50Percent = 0.50 * $baseSale; // 50 % of base sale
			$downSaleCount = 0; // must be equal to $saleCount
			$saleDiff = 0;

			/**
			 * CONDITION CHECK: 3 DAYS CONSECUTIVE SALES DOWN 50 PERCENT
			 */
			foreach ( $dates as $date => $value ) {
				// echo" $date => $value <br> ";
				$saleDiff = $baseSale - $value;
				if ($saleDiff > $baseSale50Percent) {
					$downSaleCount ++;
				}
			}
			// saleCountDay =0 : No sales has been done in the past 3 days
			if ($downSaleCount == $saleCountDay) {
				$idsDownSalesRetailers [$idsDownSalesRetailersCount ++] = $id;
			}
		}

		$stringIdsDownSalesRetailers = implode ( ",", $idsDownSalesRetailers );
		// echo "<pre>";
		// print_r($idsDownSalesRetailers);
		// print_r($stringIdsDownSalesRetailers);
		// echo $stringIdsDownSalesRetailers;

		$dataDefaulterDownSales50 = $this->Slaves->query ( "
										SELECT
											R.id, R.name, R.shopname, R.mobile, D.company AS distributor_company, D.mobile AS distributor_mobile
										FROM
											retailers AS R, distributors AS D
										WHERE
											R.parent_id = D.id

										AND
											R.id in ($stringIdsDownSalesRetailers)" );
		// echo "<pre>";
		// print_r($dataDefaulterDownSales50);

		$mail_subject = "Retailer Sales Down 50% - 3 days";
		$mail_body = "<br/><br/><b><h2>Retailer Sales Down 50% - 3 days</h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Shop Name</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Distributor Company Name</th>
							<th align = 'center'>Distributor Mobile Number</th>
							<th align = 'center'> Benchmark Sales</th>
							<th align = 'center'> Sale on " . (date ( 'Y-m-d', strtotime ( '-1 days' ) )) . "</th>
							<th align = 'center'> Sale on " . (date ( 'Y-m-d', strtotime ( '-2 days' ) )) . "</th>
							<th align = 'center'> Sale on " . (date ( 'Y-m-d', strtotime ( '-3 days' ) )) . "</th>
						</tr>";

		foreach ( $dataDefaulterDownSales50 as $index => $arr ) {

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";
			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// GETTING SALES RECORDS
			$baseSale = isset ( $baseSalesRetailers [$arr ['R'] ['id']] ) ? $baseSalesRetailers [$arr ['R'] ['id']] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $baseSale, 2 ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-1 days' ) ));
			$dateSale = isset ( $recentSalesRetailers [$arr ['R'] ['id']] [$date] ) ? $recentSalesRetailers [$arr ['R'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateSale, 2 ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-2 days' ) ));
			$dateSale = isset ( $recentSalesRetailers [$arr ['R'] ['id']] [$date] ) ? $recentSalesRetailers [$arr ['R'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateSale, 2 ) . "</td>";

			$date = (date ( 'Y-m-d', strtotime ( '-3 days' ) ));
			$dateSale = isset ( $recentSalesRetailers [$arr ['R'] ['id']] [$date] ) ? $recentSalesRetailers [$arr ['R'] ['id']] [$date] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $dateSale, 2 ) . "</td>";

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table >";
		// $mail_body .= "<br/>";
		// echo " The mail body is as follows: <br> $mail_body";
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in'
		), 'mail' );

		/**
		 * DISTRIBUTOR HAVING RETAILER SHIFTED FROM BLUE TO YELLOW AND YELLOW TO RED ZONE BY 20%
		 *
		 * @var unknown
		 */
		// $dataBaseSalesRetailers = $this->Retailer->query("
// 		SELECT
// 		r.parent_id,rl.retailer_id, SUM(rl.sale) AS sum_sale
// 		FROM
// 		retailers_logs AS rl, retailers AS r
// 		WHERE
// 		rl.retailer_id = r.id
// 		AND
// 		date between '$dateMinus33' and '$dateMinus4'
// 		GROUP BY
// 		rl.retailer_id,r.parent_id
// 		ORDER BY
// 		r.parent_id"

		// );
		// echo "<pre>";

		$dataBaseSalesRetailers = $this->Slaves->query ( "
										SELECT
											R.id as retailer_id, D.id as distributor_id, R.ret_benchmark_value
										FROM
											retailers AS R, distributors AS D
										WHERE
											R.parent_id = D.id" );

		$baseSalesRetailersBlue = array ();
		$baseSalesRetailersYellow = array ();
		$baseSalesRetailersRed = array ();
		// $baseSalesRetailers = array();

		// echo "<pre>";
		// print_r($dataBaseSalesRetailers);

		foreach ( $dataBaseSalesRetailers as $index => $values ) {

			$baseSale = $values ['R'] ['ret_benchmark_value'];

			if ($baseSale > 1000) {
				$baseSalesRetailersBlue [$values ['D'] ['distributor_id']] [$values ['R'] ['retailer_id']] = $baseSale;
			} elseif ($baseSale >= 500) {
				$baseSalesRetailersYellow [$values ['D'] ['distributor_id']] [$values ['R'] ['retailer_id']] = $baseSale;
			} else {
				$baseSalesRetailersRed [$values ['D'] ['distributor_id']] [$values ['R'] ['retailer_id']] = $baseSale;
			}
		}

		$countBaseSalesRetailersBlue = array ();
		$countBaseSalesRetailersYellow = array ();
		$countBaseSalesRetailersRed = array ();

		foreach ( $baseSalesRetailersBlue as $id => $retailers ) {
			$countBaseSalesRetailersBlue [$id] = count ( $retailers );
		}

		foreach ( $baseSalesRetailersYellow as $id => $retailers ) {
			$countBaseSalesRetailersYellow [$id] = count ( $retailers );
		}
		// echo "<br> The base sales count in the yellow zone is : <br><pre>";
		// print_r($countBaseSalesRetailersYellow);

		// foreach ($baseSalesRetailersRed as $id => $retailers){
		// $countBaseSalesRetailersRed[$id] = count($retailers);
		// }

		// GETTING SALES COUNT IN THE LAST 3 DAYS
		$dataRecentSalesRetailers = $this->Slaves->query ("SELECT d.id,r.id AS retailer_id, SUM(rel.amount) AS sum_sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "JOIN distributors d ON (rel.dist_user_id = d.user_id) "
                        . "WHERE rel.date BETWEEN '$dateMinus3' AND '$dateMinus1' "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id,d.id "
                        . "ORDER BY d.id" );

		$recentSalesRetailersBlue = array ();
		$recentSalesRetailersYellow = array ();
		$recentSalesRetailersRed = array ();

		foreach ( $dataRecentSalesRetailers as $index => $values ) {
			$recentSale = $values [0] ['sum_sale'] / 3;
			if ($recentSale > 1000) {
				$recentSalesRetailersBlue [$values ['d'] ['id']] [$values ['r'] ['retailer_id']] = $recentSale;
			} elseif ($recentSale >= 500) {
				$recentSalesRetailersYellow [$values ['d'] ['id']] [$values ['r'] ['retailer_id']] = $recentSale;
			} else {
				$recentSalesRetailersRed [$values ['d'] ['id']] [$values ['r'] ['retailer_id']] = $recentSale;
			}
		}

		$countRecentSalesRetailersBlue = array ();
		$countRecentSalesRetailersYellow = array ();
		$countRecentSalesRetailersRed = array ();

		// echo "<pre>";

		// echo "<br> The base and Recent sales count in the blue zone is : <br><pre>";
		foreach ( $recentSalesRetailersBlue as $index => $retailers ) {
			$countRecentSalesRetailersBlue [$index] = count ( $retailers );
			if (! isset ( $countBaseSalesRetailersBlue [$index] ))
				$countBaseSalesRetailersBlue [$index] = 0;
			// echo "$index => $countBaseSalesRetailersBlue[$index] : $countRecentSalesRetailersBlue[$index] <br>";
		}

		// echo "<br> The base and Recent sales count in the yellow zone is : <br><pre>";
		foreach ( $recentSalesRetailersYellow as $index => $retailers ) {
			$countRecentSalesRetailersYellow [$index] = count ( $retailers );
			if (! isset ( $countBaseSalesRetailersYellow [$index] ))
				$countBaseSalesRetailersYellow [$index] = 0;
			// echo "$index => $countBaseSalesRetailersYellow[$index] : $countRecentSalesRetailersYellow[$index] <br>";
		}

		// // echo "<br> The base and Recent sales count in the red zone is : <br><pre>";
		// foreach ($recentSalesRetailersRed as $index => $retailers){
		// $countRecentSalesRetailersRed[$index] = count($retailers);
		// if (!isset($countBaseSalesRetailersRed[$index]))
		// $countBaseSalesRetailersRed[$index] = 0;
		// // echo "$index => $countBaseSalesRetailersRed[$index] : $countRecentSalesRetailersRed[$index] <br>";
		// }

		// getting the defaulters distributor id under whom retailers are shifting to the lower sales zone
		$idsDefaultersBlueToYellow = array ();
		$idsDefaultersBlueToYellowCount = 0;
		foreach ( $countRecentSalesRetailersBlue as $id => $value ) {
			if ($countBaseSalesRetailersBlue [$id] < 5) {
				continue;
			}
			$diff = $countBaseSalesRetailersBlue [$id] - $value;
			$baseSales20Percent = 0.2 * $countBaseSalesRetailersBlue [$id];

			if ($diff >= $baseSales20Percent) {
				$idsDefaultersBlueToYellow [$idsDefaultersBlueToYellowCount ++] = $id;
			}
		}

		$stringIdsDefaultersBlueToYellow = implode ( ",", $idsDefaultersBlueToYellow );
		// echo "<br><pre>";
		// print_r($stringIdsDefaultersBlueToYellow);

		// $dataDefaulterBlueToYellow = $this->Slaves->query("
		// SELECT
		// D.id, D.name, D.company, D.state, U.mobile
		// FROM
		// distributors AS D
		// LEFT JOIN
		// users AS U
		// ON
		// D.user_id = U.id
		// WHERE
		// D.id in (".$stringIdsDefaultersBlueToYellow.")
		// ");

		$query = "  SELECT D.id, D.name, D.company, D.state,D.mobile,rm.name as Relationship_Manager
					FROM distributors AS D,  rm
					WHERE D.rm_id = rm.id
					AND D.id in ($stringIdsDefaultersBlueToYellow)";

		$dataDefaulterBlueToYellow = $this->Slaves->query ( $query );

		$mail_subject = "20% R Shift B - Y";
		$mail_body = "<br/><br/><b><h2>20% R Shift B - Y </h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Company</th>
							<th align = 'center'>State</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Relationship Manager</th>
							<th align = 'center'>Average Retailers Transacting in the blue zone</th>
							<th align = 'center'> Average Retailers Transacting Between " . (date ( 'Y-m-d', strtotime ( '-1 days' ) )) . " and " . (date ( 'Y-m-d', strtotime ( '-3 days' ) )) . " In Blue Zone</th>
					  </tr>";

        $mail_body .= "<tr>";

        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($stringIdsDefaultersBlueToYellow,2,3);
        /** IMP DATA ADDED : END**/

		foreach ( $dataDefaulterBlueToYellow as $index => $arr ) {

            $arr['D']['name'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['name'] : $arr['D']['name'];
            $arr['D']['company'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['shop_est_name'] : $arr['D']['company'];

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";
			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// getting sales records
			$baseSale = isset ( $countBaseSalesRetailersBlue [$arr ['D'] ['id']] ) ? $countBaseSalesRetailersBlue [$arr ['D'] ['id']] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $baseSale ) . "</td>";

			$recentSale = isset ( $countRecentSalesRetailersBlue [$arr ['D'] ['id']] ) ? $countRecentSalesRetailersBlue [$arr ['D'] ['id']] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $recentSale ) . "</td>";

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table>";
		// $mail_body .= "<br/>";
		// echo "The mail body is : ".$mail_body;
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in'
		), 'mail' );

		$idsDefaultersYellowToRed = array ();
		$idsDefaultersYellowToRedCount = 0;
		foreach ( $countRecentSalesRetailersYellow as $id => $value ) {
			if ($countBaseSalesRetailersYellow [$id] < 5) {
				continue;
			}
			$diff = $countBaseSalesRetailersYellow [$id] - $value;
			$baseSales20Percent = 0.2 * $countBaseSalesRetailersYellow [$id];

			if ($diff >= $baseSales20Percent) {
				$idsDefaultersYellowToRed [$idsDefaultersYellowToRedCount ++] = $id;
			}
		}

		$stringIdsDefaultersYellowToRed = implode ( ",", $idsDefaultersYellowToRed );
		// echo "<br><pre>";
		// print_r($stringIdsDefaultersYellowToRed);

		// $dataDefaulterYellowToRed = $this->Slaves->query("
		// SELECT
		// D.id, D.name, D.company, D.state, U.mobile
		// FROM
		// distributors AS D
		// LEFT JOIN
		// users AS U
		// ON
		// D.user_id = U.id
		// WHERE
		// D.id in (".$stringIdsDefaultersYellowToRed.")
		// ");

		$query = "  SELECT D.id, D.name, D.company, D.state ,D.mobile,rm.name as Relationship_Manager
					FROM distributors AS D,  rm
					WHERE D.rm_id = rm.id
					AND D.id in ($stringIdsDefaultersYellowToRed)";

		$dataDefaulterYellowToRed = $this->Slaves->query ( $query );

		// print_r($dataDefaulterYellowToRed);
		$mail_subject = "20% R Shift Y- R";
		$mail_body = "<br/><br/><b><h2>20% R Shift Y- R </h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Company</th>
							<th align = 'center'>State</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Relationship Manager</th>
							<th align = 'center'>Average Retailers Transacting in the Yellow zone</th>
							<th align = 'center'> Average Retailers Transacting Between " . (date ( 'Y-m-d', strtotime ( '-1 days' ) )) . " and " . (date ( 'Y-m-d', strtotime ( '-3 days' ) )) . " In Yellow Zone</th>
					  </tr>";

        $mail_body .= "<tr>";

        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($stringIdsDefaultersYellowToRed,2,3);
        /** IMP DATA ADDED : END**/

		foreach ( $dataDefaulterYellowToRed as $index => $arr ) {

            $arr['D']['name'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['name'] : $arr['D']['name'];
            $arr['D']['company'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['shop_est_name'] : $arr['D']['company'];

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";
			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// getting sales records
			$baseSale = isset ( $countBaseSalesRetailersYellow [$arr ['D'] ['id']] ) ? $countBaseSalesRetailersYellow [$arr ['D'] ['id']] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $baseSale ) . "</td>";

			$recentSale = isset ( $countRecentSalesRetailersYellow [$arr ['D'] ['id']] ) ? $countRecentSalesRetailersYellow [$arr ['D'] ['id']] : 0;
			$mail_body .= "<td align = 'center'>" . round ( $recentSale ) . "</td>";

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table>";
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in'
		), 'mail' );
		// echo "The mail body is : ".$mail_body;
	}

	/**
	 * REPORTS THE DOWN SALES OF STATE BY Rs. 2,00,000
	 * FOR CONSECUTIVE 3 DAYS
	 */
	function salesDownState() {
		$this->autoRender = false;

		// $dateMinus33 = date('Y-m-d', strtotime('-33 days'));
		// $dateMinus4 = date('Y-m-d', strtotime('-4 days'));
		// $dataStateBaseSales = $this->Slaves->query("
		// SELECT
		// SUM(DBL.topup_sold) AS base_sale_state,DB.state
		// FROM
		// distributors_logs AS DBL, distributors AS DB
		// WHERE
		// DBL.distributor_id = DB.id
		// AND
		// date between '$dateMinus33' and '$dateMinus4'
		// GROUP BY
		// DB.state"
		// );

		$dataStateBaseSales = $this->Slaves->query ( "
									SELECT
										SUM(benchmark_value) as base_sale_state, state
									FROM
										distributors
									GROUP BY
										state" );
		// echo "State Base Sales <br><pre>";

		$statesBaseSales = array ();

		// foreach($dataStateBaseSales as $index => $values){
		// $statesBaseSales[$values['DB']['state']] = $values[0]['base_sale_state']/30;
		// }
		foreach ( $dataStateBaseSales as $index => $values ) {
			$statesBaseSales [$values ['distributors'] ['state']] = $values [0] ['base_sale_state'];
		}
		// echo "<pre>";
		// print_r($statesBaseSales);

		$dateMinus3 = date ( 'Y-m-d', strtotime ( '-3 days' ) );
		$dateMinus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );

		$dataStates = $this->Slaves->query ( "
						 SELECT
							sum(DBL.topup_sold) as sale, DB.state, DBL.date
						 FROM
							users_logs as DBL, distributors AS DB
						 WHERE
							DBL.user_id = DB.user_id
						 AND
							date between '$dateMinus3' and '$dateMinus1'
						 GROUP BY
							DB.state, DBL.date" );

		$states = array ();
		foreach ( $dataStates as $index => $values ) {
			$states [$values ['DB'] ['state']] [$values ['DBL'] ['date']] = $values [0] ['sale'];
		}
		// echo "States <br><pre>";
		// print_r($states);

		foreach ( $states as $state => $dates ) {

			$baseSale = round ($statesBaseSales [$state]);
// 			$baseSale10Percent = 0.1 * $baseSale;
			$saleDiffLimit = 200000;
			$saleCountDay = count ( $states [$state] );
			$saleDownCount = 0;
			$saleDiff = 0;
			$statesDefaulter = array ();
			$statesDefaulterCount = 0;
			foreach ( $dates as $date => $sale ) {
				$saleDiff = round ($baseSale - $sale);
				// echo "<br>$baseSale $sale $saleDiff ". 0.1 * $baseSale."<br>";
				if ($saleDiff >= $saleDiffLimit) {
					$saleDownCount ++;
				}
			}
			// echo "<br><br>";
			if ($saleDownCount == $saleCountDay) {
				$statesDefaulter [$statesDefaulterCount ++] = $state;
			}
		}

		// echo "<br><br> The state Defaulters are : <pre>";
		// print_r($statesDefaulter);

		// MAIL FORMAT
		$mail_subject = "State Secondary Down Rs. 2,00,000 - 3days";
		$mail_body = "<br/><br/><b><h2>State Secondary Down Rs. 2,00,000 - 3days</h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'> State </th>
							<th align = 'center'> Benchmark of Sales</th>
							<th align = 'center'> Sales on " . (date ( 'Y-m-d', strtotime ( '-1 days' ) )) . " </th>
							<th align = 'center'> Sales on " . (date ( 'Y-m-d', strtotime ( '-2 days' ) )) . " </th>
							<th align = 'center'> Sales on " . (date ( 'Y-m-d', strtotime ( '-3 days' ) )) . " </th>
					   </tr>";
		$mail_body .= "<tr>";
		foreach ( $statesDefaulter as $index => $state ) {

			$mail .= "<td align = 'center'>" . ($index + 1) . "</td>";
			$mail .= "<td align = 'center'>" . $state . "</td>";
			$mail .= "<td align = 'center'>" . $statesBaseSales [$state] . "</td>";

			$date = date ( 'Y-m-d', strtotime ( '-1 days' ) );
			$mail .= "<td align = 'center'>" . $states [$state] [$date] . "</td>";

			$date = date ( 'Y-m-d', strtotime ( '-2 days' ) );
			$mail .= "<td align = 'center'>" . $states [$state] [$date] . "</td>";

			$date = date ( 'Y-m-d', strtotime ( '-3 days' ) );
			$mail .= "<td align = 'center'>" . $states [$state] [$date] . "</td>";
		}

		$mail_body .= "</table >";
		// $mail_body .= "<br/>";

// 		echo " The mail body is as follows: <br> $mail_body";
		// SEND MAIL
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in'
		), 'mail' );
	}

	/**
	 * AUTHOR: RISHABH GUPTA
	 * CHECKS AND COMPARES THA WHETHER THE PRESENT DAY SALES ARE GOING THE RIGHT WAY
	 * AT 14:00 AND 20:00.
	 * TAKES THE AVERAGE OF 15 DAYS FOR BASE COMPARISON
	 * $TIME INDICATES AT WHICH TIME DURATION THE QUERY NEEDS TO BE EXECUTED
	 * @PARAM STRING $TIME DECIDES FORECAST FOR WHICH TIME NEEDS TO BE SEND
	 */
	function salesForecast() {
		$this->autoRender = false;

		// AVERAGE SECONDARY TILL 12:00, 18:00
		// AVERAGE TERTIARY TILL 12:00, 18:00
		$dateMinus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );
		$dateMinus15 = date ( 'Y-m-d', strtotime ( '-15 days' ) );
		$presentHour = date ( 'H' );

		$dataAverage = $this->Slaves->query ( "
								SELECT
									DLQ.distributor_id,
									(SUM(DLQ.secondary_00to06 + DLQ.secondary_06to12)) AS sum_secondary12,
									(SUM(DLQ.secondary_00to06 + DLQ.secondary_06to12 + DLQ.secondary_12to18)) AS sum_secondary18,
									(SUM(DLQ.tertiary_00to06 + DLQ.tertiary_06to12)) AS sum_tertiary12,
									(SUM(DLQ.tertiary_00to06 + DLQ.tertiary_06to12 + DLQ.tertiary_12to18)) AS sum_tertiary18
								FROM
									distributor_logs_quarter AS DLQ, distributors AS D
								WHERE
									DLQ.distributor_id = D.id
								AND
									DLQ.date BETWEEN '$dateMinus15' AND '$dateMinus1'
								GROUP BY
									DLQ.distributor_id" );
		$average = array ();

		foreach ( $dataAverage as $index => $arr ) {
			$average [$arr ['DLQ'] ['distributor_id']] ['secondary_till_12'] = $arr [0] ['sum_secondary12'] / 15;
			$average [$arr ['DLQ'] ['distributor_id']] ['secondary_till_18'] = $arr [0] ['sum_secondary18'] / 15;
			$average [$arr ['DLQ'] ['distributor_id']] ['tertiary_till_12'] = $arr [0] ['sum_tertiary12'] / 15;
			$average [$arr ['DLQ'] ['distributor_id']] ['tertiary_till_18'] = $arr [0] ['sum_tertiary18'] / 15;
		}

		// echo "<pre>";
		// print_r($average);

		// CURRENT DAY DATA OF SECONDARY
		$time = date ( 'H' ); // CONDITION FOR CHECKING TO SHOOT THE QUERY ACCORDING TO THE TIME
		$currentDate = date ( 'Y-m-d' );
		$timestamp = '';
		if ($time >= 12 && $time < 18)
			$timestamp = " AND HOUR(timestamp) >=00 AND HOUR(timestamp) < 12 ";
		if ($time >= 18)
			$timestamp = " AND HOUR(timestamp) >=00 AND HOUR(timestamp) < 18 ";

			// CONFIRM FLAG = 0 (FOR PRIMARY ALSO CONFIRM FLAG = 0)
		$dataSecondaryCurrentDay = $this->Slaves->query ( "
										SELECT
											source_id AS distributor_id, SUM(amount) AS secondary
										FROM
											shop_transactions
										WHERE
											date = '$currentDate'
										AND
											type = 2
										$timestamp
										AND
											confirm_flag = 0
										GROUP BY
											source_id" );

		// echo "<pre>";
		// print_r($dataSecondaryCurrentDay);

		// CONFIRM FLAG = 1
		$dataTertiaryCurrentDay = $this->Slaves->query ( "
										SELECT
											R.parent_id AS distributor_id, SUM(ST.amount) AS tertiary
										FROM
											shop_transactions  AS ST, retailers AS R
										WHERE
											R.id = ST.source_id
										AND
											ST.date = '$currentDate'
										AND
											ST.type = 4
										$timestamp
										AND
											confirm_flag = 1
										GROUP BY
											R.parent_id" );

		// echo"<pre>";
		// print_r($dataTertiaryCurrentDay);

		$secondaryCurrentDay = array ();
		$tertiaryCurrentDay = array ();

		foreach ( $dataSecondaryCurrentDay as $arr ) {
			$secondaryCurrentDay [$arr ['shop_transactions'] ['distributor_id']] = $arr [0] ['secondary'];
		}
		foreach ( $dataTertiaryCurrentDay as $arr ) {
			$tertiaryCurrentDay [$arr ['R'] ['distributor_id']] = $arr [0] ['tertiary'];
		}

		// print_r($secondaryCurrentDay);
		// print_r($tertiaryCurrentDay);

		$idsDefaulter = array ();
		$idsDefaulterCount = 0;

		// GETTING THE DIFFERENCE OF TERTIARY, SECONDARY FOR 12:00 P.M.
		if ($time >= 12 && $time < 18) {
			foreach ( $tertiaryCurrentDay as $id => $amt ) {
				if (! isset ( $average [$id] ['tertiary_till_12'] ))
					$average [$id] ['tertiary_till_12'] = 0;
				$avgTertiary = $average [$id] ['tertiary_till_12'];
				$avgTertiary25 = 0.25 * $avgTertiary;
				$saleDiffTertiary = $avgTertiary - $amt;

				// TERTIARY SALES ARE DOWN BY 25%, THEN CHECK SECONDARY
				if ($saleDiffTertiary > $avgTertiary25) {
					if (! isset ( $secondaryCurrentDay [$id] ))
						$secondaryCurrentDay [$id] = 0;
					if (! isset ( $average [$id] ['secondary_till_12'] ))
						$average [$id] ['secondary_till_12'] = 0;
					$avgSecondary = $average [$id] ['secondary_till_12'];
					$avgSecondary25 = 0.25 * $average [$id] ['secondary_till_12'];
					$saleDiffSecondary = $avgSecondary - $secondaryCurrentDay [$id];

					// SECONDARY SALES DOWN BY 25%
					if ($saleDiffSecondary != 0 && $saleDiffSecondary > $avgSecondary25) {
						$idsDefaulter [$idsDefaulterCount ++] = $id;
					}
				}
			}
		}

		if ($time >= 18) {
			foreach ( $tertiaryCurrentDay as $id => $amt ) {
				if (! isset ( $average [$id] ['tertiary_till_18'] ))
					$average [$id] ['tertiary_till_18'] = 0;
				$avgTertiary = $average [$id] ['tertiary_till_18'];
				$avgTertiary25 = 0.25 * $avgTertiary;
				$saleDiffTertiary = $avgTertiary - $amt;

				// TERTIARY SALES ARE DOWN BY 25%, THEN CHECK SECONDARY
				if ($saleDiffTertiary > $avgTertiary25) {
					if (! isset ( $secondaryCurrentDay [$id] ))
						$secondaryCurrentDay [$id] = 0;
					if (! isset ( $average [$id] ['secondary_till_18'] ))
						$average [$id] ['secondary_till_18'] = 0;
					$avgSecondary = $average [$id] ['secondary_till_18'];
					$avgSecondary25 = 0.25 * $average [$id] ['secondary_till_18'];
					$saleDiffSecondary = $avgSecondary - $secondaryCurrentDay [$id];

					// SECONDARY SALES DOWN BY 25%
					if ($saleDiffSecondary != 0 && $saleDiffSecondary > $avgSecondary25) {
						$idsDefaulter [$idsDefaulterCount ++] = $id;
					}
				}
			}
		}

		$stringIdsDefaulter = implode ( ",", $idsDefaulter );

		// $dataDefaulter = $this->Slaves->query("
		// SELECT
		// D.id, D.name, D.company, D.state, U.mobile
		// FROM
		// distributors AS D
		// LEFT JOIN
		// users AS U
		// ON
		// D.user_id = U.id
		// WHERE
		// D.id in ($stringIdsDefaulter)"
		// );

		$query = "  SELECT D.id, D.name, D.company, D.state, D.mobile , rm.name as Relationship_Manager
					FROM distributors AS D,rm
					WHERE  D.rm_id = rm.id
					AND D.id in ($stringIdsDefaulter)";

		$dataDefaulter = $this->Slaves->query ( $query );
		// print_r($dataDefaulterYellowToRed);

		$t = 0;
		if ($time >= 12 && $time < 18)
			$t = 12;
		if ($time >= 18)
			$t = 18;
			// echo "<br>t = $t and time = $time <br>";

		$mail_subject = "Currently Minus 25% by $t:00 P.M.";
		$mail_body = "<br/><br/><b><h2>Currently Minus 25% by $t:00 P.M.</h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Company</th>
							<th align = 'center'>State</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Relationship Manager</th>
							<th align = 'center'>Average Tertiary Expected by $t:00 P.M.</th>
							<th align = 'center'>Current Tertiary by $t:00 P.M.</th>
							<th align = 'center'>Average Secondary Expected by $t:00 P.M.</th>
							<th align = 'center'>Current Secondary by $t:00 P.M.</th>
					  </tr>";

        $mail_body .= "<tr>";

        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($stringIdsDefaulter,2,3);
        /** IMP DATA ADDED : END**/

		foreach ( $dataDefaulter as $index => $arr ) {

            $arr['D']['name'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['name'] : $arr['D']['name'];
            $arr['D']['company'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['shop_est_name'] : $arr['D']['company'];

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";
			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// getting secondary and tertiary values till 12:00
			if ($t == 12) {
				$mail_body .= "<td align = 'center'>" . round ( $average [$arr ['D'] ['id']] ['tertiary_till_12'] ) . "</td>";
				$mail_body .= "<td align = 'center'>" . round ( $tertiaryCurrentDay [$arr ['D'] ['id']] ) . "</td>";
				$mail_body .= "<td align = 'center'>" . round ( $average [$arr ['D'] ['id']] ['secondary_till_12'] ) . "</td>";
				$mail_body .= "<td align = 'center'>" . round ( $secondaryCurrentDay [$arr ['D'] ['id']] ) . "</td>";
			}

			// getting secondary and tertiary values till 18:00
			if ($t == 18) {
				$mail_body .= "<td align = 'center'>" . round ( $average [$arr ['D'] ['id']] ['tertiary_till_18'] ) . "</td>";
				$mail_body .= "<td align = 'center'>" . round ( $tertiaryCurrentDay [$arr ['D'] ['id']] ) . "</td>";
				$mail_body .= "<td align = 'center'>" . round ( $average [$arr ['D'] ['id']] ['secondary_till_18'] ) . "</td>";
				$mail_body .= "<td align = 'center'>" . round ( $secondaryCurrentDay [$arr ['D'] ['id']] ) . "</td>";
			}

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table>";

		// SEND MAIL
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in'
		), 'mail' );
		// $mail_body .= "<br/>";
		// echo "The mail body is : ".$mail_body;
	}

	/**
	 * Finds the retailers gradually dropping down (with last 4 weeks sales average (individually of each week))
	 * Conditions:
	 * 1: retailer must be atleast 4 weeks old
	 * 2: retailer's average sale of last week > 500
	 * 3: retailer's average sale of atleast 2 weeks must be less than 75% of 4th_last week
	 */
	function retailersGraduallyDropped() {
		$this->autoRender = false;

		$dateminus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );
		$dateminus7 = date ( 'Y-m-d', strtotime ( '-7 days' ) );
		$dateminus8 = date ( 'Y-m-d', strtotime ( '-8 days' ) );
		$dateminus14 = date ( 'Y-m-d', strtotime ( '-14 days' ) );
		$dateminus15 = date ( 'Y-m-d', strtotime ( '-15 days' ) );
		$dateminus21 = date ( 'Y-m-d', strtotime ( '-21 days' ) );
		$dateminus22 = date ( 'Y-m-d', strtotime ( '-22 days' ) );
		$dateminus28 = date ( 'Y-m-d', strtotime ( '-28 days' ) );
                
		$query = "(SELECT r.id as retailer_id, 'last_week' AS week, SUM(rel.amount) AS sale FROM retailer_earning_logs rel JOIN retailers r ON (rel.ret_user_id = r.user_id) WHERE r.id NOT IN "
                        . "(SELECT id FROM retailers WHERE DATE(created) >= '$dateminus1' - INTERVAL 27 DAY) AND date BETWEEN '$dateminus1' - INTERVAL 6 DAY AND '$dateminus1' AND rel.service_id IN (1,2,4,5,6,7) GROUP BY r.id) "
                        . "UNION (SELECT r.id as retailer_id, '2nd_last_week' AS week, SUM(rel.amount) AS sale FROM retailer_earning_logs rel JOIN retailers r ON (rel.ret_user_id = r.user_id) WHERE r.id NOT IN "
                        . "(SELECT id FROM retailers WHERE DATE(created) >= '$dateminus1' - INTERVAL 27 DAY) AND date BETWEEN '$dateminus1' - INTERVAL 13 DAY AND '$dateminus1' - INTERVAL 7 DAY AND rel.service_id IN (1,2,4,5,6,7) GROUP BY r.id) "
                        . "UNION (SELECT r.id as retailer_id, '3rd_last_week' AS week, SUM(rel.amount) AS sale FROM retailer_earning_logs rel JOIN retailers r ON (rel.ret_user_id = r.user_id) WHERE r.id NOT IN "
                        . "(SELECT id FROM retailers WHERE DATE(created) >= '$dateminus1' - INTERVAL 27 DAY) AND date BETWEEN '$dateminus1' - INTERVAL 20 DAY AND '$dateminus1' - INTERVAL 14 DAY AND rel.service_id IN (1,2,4,5,6,7) GROUP BY r.id) "
                        . "UNION (SELECT r.id as retailer_id, '4th_last_week' AS week, SUM(rel.amount) AS sale FROM retailer_earning_logs rel JOIN retailers r ON (rel.ret_user_id = r.user_id) WHERE r.id NOT IN "
                        . "(SELECT id FROM retailers WHERE DATE(created) >= '$dateminus1' - INTERVAL 27 DAY) AND date BETWEEN '$dateminus1' - INTERVAL 27 DAY AND '$dateminus1' - INTERVAL 21 DAY AND rel.service_id IN (1,2,4,5,6,7) GROUP BY r.id)";

		$dataRetailersAverageSales = $this->Slaves->query ( $query );
                
		$retailersAverageSales = array ();
		foreach ( $dataRetailersAverageSales as $arr ) {
			$retailersAverageSales [$arr [0] ['retailer_id']] [$arr [0] ['week']] = round ( ($arr [0] ['sale']) / 7 );
		}

		// sales down from 4th week to 1st week down by 50%
		$retailerIdsGraduallyDropped = array ();
		$retailerIdsGraduallyDroppedCount = 0;

		$salesThreshold = 500;
		// Keys for week: last_week, 2nd_last_week, 3rd_last_week, 4th_last_week
		foreach ( $retailersAverageSales as $retailerID => $weeklySalesArr ) {
			if ($weeklySalesArr ['last_week'] < $salesThreshold)
				continue;

				// sales of the last week must be less than 50% of 4th last week for reporting them
			$sales4thLastWeek50Percent = $weeklySalesArr ['4th_last_week'] * 0.5;
			if ($weeklySalesArr ['last_week'] > $sales4thLastWeek50Percent)
				continue;

				// used to calculate deviation of sales
			$sales4thLastWeek75Percent = $weeklySalesArr ['4th_last_week'] * 0.75;

			$retailerSalesDownCount = 0;
			foreach ( $weeklySalesArr as $week => $sales ) {
				if ($sales < $sales4thLastWeek75Percent)
					$retailerSalesDownCount ++;
			}

			if ($retailerSalesDownCount >= 2)
				$retailerIdsGraduallyDropped [$retailerIdsGraduallyDroppedCount ++] = $retailerID;
		}

		$stringRetailerIdsGraduallyDropped = implode ( ",", $retailerIdsGraduallyDropped );

		$query = "
				SELECT
				R.id, R.name, R.shopname, R.mobile, D.company AS distributor_company, D.mobile AS distributor_mobile
				FROM
				retailers AS R, distributors AS D
				WHERE
				R.parent_id = D.id

				AND
				R.id in ($stringRetailerIdsGraduallyDropped)";

		$dataRetailerGraduallyDropped = $this->Slaves->query ( $query );

		$mail_subject = "Retailers Gradually Dropping Down";
		$mail_body = "<br/><br/><b><h2>Retailer Sales Down</h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Shop Name</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Distributor Company Name</th>
							<th align = 'center'>Distributor Mobile Number</th>
							<th align = 'center'>Average Sale in 4th last week ($dateminus22 to $dateminus28) </th>
							<th align = 'center'>Average Sale in 3rd last week ($dateminus15 to $dateminus21)</th>
							<th align = 'center'>Average Sale in 2nd last week ($dateminus8 to $dateminus14)</th>
							<th align = 'center'>Average Sale in last week ($dateminus1 to $dateminus7)</th>
						</tr>";

		foreach ( $dataRetailerGraduallyDropped as $index => $arr ) {

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";

			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// GETTING SALES RECORDS
			$sale = $retailersAverageSales [$arr ['R'] ['id']] ['4th_last_week'];
			$mail_body .= "<td align = 'center'>" . $sale . "</td>";

			$sale = $retailersAverageSales [$arr ['R'] ['id']] ['3rd_last_week'];
			$mail_body .= "<td align = 'center'>" . $sale . "</td>";

			$sale = $retailersAverageSales [$arr ['R'] ['id']] ['2nd_last_week'];
			$mail_body .= "<td align = 'center'>" . $sale . "</td>";

			$sale = $retailersAverageSales [$arr ['R'] ['id']] ['last_week'];
			$mail_body .= "<td align = 'center'>" . $sale . "</td>";

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table >";
		// $mail_body .= "<br/>";
		// echo " The mail body is as follows: <br> $mail_body";
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in',
				'ashish@pay1.in'				
		), 'mail' );
	}

	/**
	 * Finds the dropped out retailers from the system on the basis of the following conditions:
	 * 1.
	 * retailer must be atleast 37 days old
	 * 2. retailer must have average sales of Rs. 500 in the past 30 days (from the last transacted day)
	 * 3. last transaction retailer did 7 days before
	 */
	function retailersDroppedOut() {
		$this->autoRender = false;
		// $date = "2015-08-23 07:38:42";
		// $date1 = date ( 'Y-m-d', strtotime ( $date ) );

		$dateMinus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );
		$dateMinus8 = date ( 'Y-m-d', strtotime ( '-8 days' ) );
		$dateMinus37 = date ( 'Y-m-d', strtotime ( '-37 days' ) );
		$dateMinus7 = date ( 'Y-m-d', strtotime ( '-7 days' ) );

		// valid retailer id's for comparisons of dropped out condition
		$query = "SELECT r.id AS retailer_id, MAX(rel.date), SUM(rel.amount) AS 'sale' "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id NOT IN "
                        . "(SELECT id "
                        . "FROM retailers "
                        . "WHERE DATE(created) > '$dateMinus1' - INTERVAL 37 DAY) "
                        . "AND rel.date >= '$dateMinus37' "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id "
                        . "HAVING (MAX(rel.date) = '$dateMinus1' - INTERVAL 6 DAY "
                        . "AND SUM(rel.amount) > 15000)";

		$dataValidIds = $this->Slaves->query ( $query );
		// echo "<pre>";

		$retailerAverageSales = array ();

		// find average sales of the retailer
		foreach ( $dataValidIds as $arr ) {
			$retailerAverageSales [$arr ['r'] ['retailer_id']] = round ( $arr [0] ['sale'] / 30 );
		}

		// print_r($retailerAverageSales);

		$idRetailersDroppedOut = array ();
		$idRetailersDroppedOutCount = 0;

		foreach ( $retailerAverageSales as $id => $averageSales ) {
			$idRetailersDroppedOut [$idRetailersDroppedOutCount ++] = $id;
		}

		// print_r($idRetailersDroppedOut);

		$stringidRetailersDroppedOut = implode ( ",", $idRetailersDroppedOut );

		$query = "SELECT
					R.id, R.name, R.shopname, R.mobile, R.created, D.company AS distributor_company, D.mobile AS distributor_mobile
  				  FROM
					retailers AS R, distributors AS D
				  WHERE
					R.parent_id = D.id

					 AND R.id in ($stringidRetailersDroppedOut)";

		$dataRetailerDropped = $this->Slaves->query ( $query );

		// mail body

		$mail_subject = "Retailers Dropped Out (Last transacted on $dateMinus7)";
		$mail_body = "<br/><br/><b><h2>Retailers Dropped Out (Last transacted on $dateMinus7)</h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Shop Name</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Created on </th>
							<th align = 'center'>Distributor Company Name</th>
							<th align = 'center'>Distributor Mobile Number</th>
							<th align = 'center'>Average Sale Between ($dateMinus37 to $dateMinus8) </th>
						</tr>";

		foreach ( $dataRetailerDropped as $index => $arr ) {

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";

			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// GETTING SALES RECORDS
			$sale = $retailerAverageSales [$arr ['R'] ['id']];
			$mail_body .= "<td align = 'center'>" . $sale . "</td>";

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table >";
		// echo " The mail body is as follows: <br> $mail_body";
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in',
				'ashish@pay1.in'
		), 'mail' );
	}

	/**
	 * For each distributor, it gives the number of retailers having transacting in the following zones:
	 * 1.
	 * less than Rs. 1000
	 * 2. Between Rs. 1000 - Rs. 2000
	 * 3. Greater than Rs. 2000
	 */
	function distributorRetailersCount() {
		$dateFrom = "2015-07-01";
		$dateTo = "2015-07-31";
		$daysCount = 31;

		$query = "SELECT R.parent_id, R.id,D.id as dist_id SUM(RL.amount) AS sum_sale "
                        . "FROM retailer_earning_logs AS RL "
                        . "JOIN retailers AS R ON (RL.ret_user_id = R.user_id)"
                        . "JOIN distributors D ON (RL.dist_user_id = D.user_id) "
                        . "WHERE RL.date BETWEEN '$dateFrom' AND '$dateTo' "
                        . "AND DATE(R.created) <= '$dateFrom' "
                        . "AND RL.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY R.id,D.id "
                        . "ORDER BY D.id"; 

		$dataSales = $this->Slaves->query ( $query );
		// echo "<pre>";

		$salesRetailers = array ();

		// echo "<pre>";
		// print_r($dataBaseSalesRetailers);
		foreach ( $dataSales as $index => $values ) {

			$sale = round ( $values [0] ['sum_sale'] / $daysCount );

			if ($sale <= 1000) {
				$salesRetailers [$values ['D'] ['dist_id']] ['1000'] [$values ['R'] ['id']] = $sale;
			} elseif ($sale >= 2001) {
				$salesRetailers [$values ['D'] ['dist_id']] ['2000'] [$values ['R'] ['id']] = $sale;
			} else {
				$salesRetailers [$values ['D'] ['dist_id']] ['1000-2000'] [$values ['R'] ['id']] = $sale;
			}
		}
		// print_r($salesRetailers);

		$countSalesRetailers = array ();

		foreach ( $salesRetailers as $id => $retailers ) {
			$countSalesRetailers [$id] ['1000'] = count ( $retailers ['1000'] );
			$countSalesRetailers [$id] ['1000-2000'] = count ( $retailers ['1000-2000'] );
			$countSalesRetailers [$id] ['2000'] = count ( $retailers ['2000'] );
		}

		$validIds = array ();
		$validIdsCount = 0;
		foreach ( $countSalesRetailers as $id => $arr ) {
			$validIds [$validIdsCount ++] = $id;
		}
		$stringValidIds = implode ( ",", $validIds );
		// echo $stringValidIds;
		// print_r($countSalesRetailers);

		$query = "  SELECT D.id, D.name, D.company, D.state,D.mobile,rm.name as Relationship_Manager
					FROM distributors AS D, rm
					WHERE  D.rm_id = rm.id
					AND D.id in ($stringValidIds)";

		$dataDistributors = $this->Slaves->query ( $query );
		// print_r($dataDistributors);

		$mail_subject = "Retailers count for each distributor (slab wise in month of July)";
		$mail_body = "<br/><br/><b><h2>Retailers count for each distributor (slab wise in month of July)</h2></b>";

		$mail_body .= "<br/>";
		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
		$mail_body .= "<tr>
							<th align = 'center'> S.No. </th>
							<th align = 'center'>Name</th>
							<th align = 'center'>Company</th>
							<th align = 'center'>State</th>
							<th align = 'center'>Mobile</th>
							<th align = 'center'>Relationship Manager</th>
							<th align = 'center'>Retailers (Rs. 0 to Rs. 1000)</th>
							<th align = 'center'>Retailers (Rs. 1001 to Rs. 2000)</th>
							<th align = 'center'>Retailers (Rs. 2001 and above)</th>
						</tr>";

        $mail_body .= "<tr>";

        /** IMP DATA ADDED : START**/
        $imp_data = $this->Shop->getUserLabelData($stringValidIds,2,3);
        /** IMP DATA ADDED : END**/

		foreach ( $dataDistributors as $index => $arr ) {

            $arr['D']['name'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['name'] : $arr['D']['name'];
            $arr['D']['company'] =  (isset($imp_data[$arr['D']['id']])) ? $imp_data[$arr['D']['id']]['imp']['shop_est_name'] : $arr['D']['company'];

			$mail_body .= "<tr>";
			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";
			foreach ( $arr as $table => $detailsArr ) {
				foreach ( $detailsArr as $details => $value ) {
					if ($details == "id") {
						continue;
					}
					$mail_body .= "<td align = 'center'>" . $value . "</td>";
				}
			}

			// getting sales records
			$saleCount = $countSalesRetailers [$arr ['D'] ['id']] ['1000'];
			$mail_body .= "<td align = 'center'>" . round ( $saleCount ) . "</td>";

			$saleCount = $countSalesRetailers [$arr ['D'] ['id']] ['1000-2000'];
			$mail_body .= "<td align = 'center'>" . round ( $saleCount ) . "</td>";

			$saleCount = $countSalesRetailers [$arr ['D'] ['id']] ['2000'];
			$mail_body .= "<td align = 'center'>" . round ( $saleCount ) . "</td>";

			$mail_body .= "</tr>";
		}
		$mail_body .= "</table>";
		$this->General->sendMails ( $mail_subject, $mail_body, array (
				'channel@pay1.in',
				'ashish@pay1.in'
		), 'mail' );
// 		echo "The mail body is : " . $mail_body;
	}

	/**
	 * INDEPENDENCE DAY OFFER FOR RETAILERS
	 *
	 * 1. Scheme Name: PAY1 Independence Offer
	 * 2. Scheme Period: 16 to 31 Aug2015
	 * 3. Target Group: Existing Retailer those are doing daily sales between Rs 1,000 - 2,000/-.\
	 * 4. Targeted New Sale: 50% growth in daily average
	 * 5. Incentive Amount: 1% on new sales growth
	 */
	/*function moneyBackRetailersSpecial() { // 1st day of month

		// $from = '2015-08-16';
	                                       // $to = '2015-08-31';
		$query = "SELECT
					R.id,R.mobile, AVG(sale) as sale
				FROM
					retailers_logs AS RL, retailers AS R
				WHERE
					RL.retailer_id = R.id
				AND
					date between '2015-08-01' and '2015-08-31'
				GROUP BY
					R.id
				HAVING
					AVG(sale) >=500";

		$dataSalesAverage = $this->Slaves->query ( $query );

// 		echo "sales average: <br><pre>";
// 		print_r($dataSalesAverage);

		$salesAverage = array ();
		foreach ( $dataSalesAverage as $index => $arr ) {
			$salesAverage [$arr ['R'] ['id']] ['sales'] = round ( $arr [0] ['sale']);
			$salesAverage [$arr ['R'] ['id']] ['mobile'] = $arr ['R'] ['mobile'];
		}


		$dateMinus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );
// 		$currentDate = "2015-08-31";
		// 		echo $currentDate;
		$query = "SELECT
					R.id,R.mobile, sum(sale) as sale
				FROM
					retailers_logs AS RL, retailers AS R
				WHERE
					RL.retailer_id = R.id
						AND date between '2015-09-01' and '$dateMinus1'
				GROUP BY
					R.id";

		$dataSalesDone = $this->Slaves->query ( $query );

		$salesDone = array ();
		foreach ( $dataSalesDone as $index => $arr ) {
			$salesDone [$arr ['R'] ['id']] = round ( $arr [0] ['sale'] );
		}

		$idsTargetAchieved = array();
		$idsTargetAchievedCount = 0;

		$count = 0;
		$countTotal = 0;
		$sum = 0;
		foreach ( $salesAverage as $id => $arr ) {

			$targetSale = round ( $salesAverage [$id] ['sales'] * 30 * 1.3 );
// 			$newSale = round ( $salesAverage [$id] ['sales'] * 16 * 0.5 );
// 			$incentiveAmount = round ( $newSale * 0.02 );
			$achievedAmount = $salesDone [$id];
			$countTotal++;

// 			if ($targetSale <= $achievedAmount) {
// 				$idsTargetAchieved[$idsTargetAchievedCount++] = $id;
// 				$sms2 = "";
// 				$sms2 .= "PAY1 Independance Day offer: Targeted Sale is Rs. ";
// 				$sms2 .= $targetSale;
// 				$sms2 .= " and you have achieved Rs. ";
// 				$sms2 .= $achievedAmount;
// 				$sms2 .= ". Achieve target to get incentives of Rs. ";
// 				$sms2 .= $incentiveAmount;
// 				$sms2 .= " by 31stAug.\nPAY1";
// 				$count++;
// 				$sum += $incentiveAmount;
// 				$sms = "";
// 				$sms .= "PAY1 Independence Day offer: Get Bonus of Rs. ";
// 				$sms .= $incentiveAmount;
// 				$sms .= " on doing total sale of Rs. ";
// 				$sms .= $targetSale;
// 				$sms .= " in next 16 days (16th Aug to 31st Aug).\nPAY1";

//			$sms = "PAY1 Ganesh Utsav offer, Your total targeted Sale Rs $targetSale and you have achieved Rs $achievedAmount. Achieve your target  Till 30th Sept and win exciting prizes";

                        $paramdata['TARGETSALE'] = $targetSale;
                        $paramdata['ACHIEVEDAMOUNT'] = $achievedAmount;
                        $MsgTemplate = $this->General->LoadApiBalance();
                        $content =  $MsgTemplate['GaneshUtsav_Offer_MSG'];
                        $sms = $this->General->ReplaceMultiWord($paramdata,$content);

// 			echo "<br>" . $salesAverage [$id] ['mobile'] . ": $sms <br>";

			$this->General->sendMessage ( $salesAverage [$id] ['mobile'], $sms, 'notify' );
// 			}

		}
// 		$stringIdsTargetAchieved =  implode(",", $idsTargetAchieved);

// 		$query = "SELECT
// 					R.id, R.name, R.shopname, R.mobile, R.created, D.company AS distributor_company, U.mobile AS distributor_mobile
// 					FROM
// 					retailers AS R, distributors AS D, users AS U
// 					WHERE
// 					R.parent_id = D.id
// 					AND
// 					D.user_id = U.id
// 					AND
// 					R.id in ($stringIdsTargetAchieved)";

// 		$dataRetailersTargetAchieved = $this->Slaves->query($query);

// 		$mail_subject = "Beneficiaries of Independence Day offer ";
// 		$mail_body = "<br/><br/><b><h2>Beneficiaries of Independence Day offer </h2></b>";

// 		$mail_body .= "<br/>";
// 		$mail_body .= "<table width='100%' border='3' style='border-collapse:collapse;font-size: 14px;'>";
// 		$mail_body .= "<tr>
// 						<th align = 'center'> S.No. </th>
// 						<th align = 'center'>Name</th>
// 						<th align = 'center'>Shop Name</th>
// 						<th align = 'center'>Mobile</th>
// 						<th align = 'center'>Created on </th>
// 						<th align = 'center'>Distributor Company Name</th>
// 						<th align = 'center'>Distributor Mobile Number</th>
// 						<th align = 'center'>Sales Target (16-08-2015 to 31-08-2015) </th>
// 						<th align = 'center'>Sales Done (16-08-2015 to 31-08-2015) </th>
// 						<th align = 'center'>Incentive to be given (Rs. ) </th>
// 						</tr>";

// 		foreach ( $dataRetailersTargetAchieved as $index => $arr ) {

// 			$mail_body .= "<tr>";
// 			$mail_body .= "<td align = 'center'>" . ($index + 1) . "</td>";

// 			foreach ( $arr as $table => $detailsArr ) {
// 				foreach ( $detailsArr as $details => $value ) {
// 					if ($details == "id") {
// 						continue;
// 					}
// 					$mail_body .= "<td align = 'center'>" . $value . "</td>";
// 				}
// 			}

// 			// GETTING SALES RECORDS
// 			$sale = round (($salesAverage [$arr ['R'] ['id']] ['sales']) * 1.5 *16);
// 			$mail_body .= "<td align = 'center'>" . $sale . "</td>";

// 			$sale = $salesDone[$arr['R']['id']];
// 			$mail_body .= "<td align = 'center'>" . $sale . "</td>";

// 			$incentive = round (($salesAverage [$arr ['R'] ['id']] ['sales']) * 0.5 * 16 * 0.02);
// 			$mail_body .= "<td align = 'center'>" . $incentive . "</td>";
// // 			$newSale = round ( $salesAverage [$id] ['sales'] * 16 * 0.5 );
// // 			$incentiveAmount = round ( $newSale * 0.02 );

// 			$mail_body .= "</tr>";
// 		}
// 		$mail_body .= "</table >";
// 		echo " The mail body is as follows: <br> $mail_body";
// 		echo "<br><br>$countTotal => $count<br><br>$sum";

// 		$this->autoRender = false;
	}

	function moneyBackRetailersSpecialEndMessage(){

// 		$count = 0;
		$query = "SELECT
					R.id,R.mobile, sum(sale) as sale
				FROM
					retailers_logs AS RL, retailers AS R
				WHERE
					RL.retailer_id = R.id
				AND
					date between '2015-08-01' and '2015-08-14'
				GROUP BY
					R.id
				HAVING
					sale >=14000 AND sale <=28000";
		$MsgTemplate = $this->General->LoadApiBalance();
		$dataSalesAverage = $this->Slaves->query ( $query );
		$salesAverage = array ();
		foreach ( $dataSalesAverage as $index => $arr ) {
			$salesAverage [$arr ['R'] ['id']]  = $arr ['R'] ['mobile'];
		}

		foreach ( $salesAverage as $id => $mobile ) {
// 			$count++;
//
//			$sms = "Have you claimed your Independence Day Contest prize? Like our Facebook page and check the list of winners Today. www.facebook.com/pay1Store  \nPAY1";

                        $sms =  $MsgTemplate['IndependenceDay_Contest_MSG'];

// 			echo "<br>" . $mobile. ": $sms <br>";
			$this->General->sendMessage ( $mobile, $sms, 'notify' );
		}

// 		echo "<br>$count<br>";
		$this->autoRender = false;
	}


	/**
	 * The data will be inserted in 'retailers_drop' table
	 * for the retailers gradually dropping
	 * The 'down_type_id' = 1 for gradual drop for the column(down_type_id)
	 * @param string $date: for external insertion of data in the database
	 */
	function insertRetailersGradualDropData($date = NULL) {
		$this->autoRender = false;

		if($date != NULL)
			$dateminus1 = $date;
		else
			$dateminus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );

		$dateminus7 = date('Y-m-d', strtotime('-6 days', strtotime($dateminus1)));
// 		$dateminus7 = date ( 'Y-m-d', strtotime ( '-7 days' ) );
		$dateminus8 = date ( 'Y-m-d', strtotime ( '-8 days' ) );
		$dateminus14 = date ( 'Y-m-d', strtotime ( '-14 days' ) );
		$dateminus15 = date ( 'Y-m-d', strtotime ( '-15 days' ) );
		$dateminus21 = date ( 'Y-m-d', strtotime ( '-21 days' ) );
		$dateminus22 = date ( 'Y-m-d', strtotime ( '-22 days' ) );
		$dateminus28 = date ( 'Y-m-d', strtotime ( '-28 days' ) );

		$query = "(SELECT r.id AS retailer_id, 'last_week' AS week, SUM(rel.amount) AS sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id NOT IN (SELECT id FROM retailers WHERE DATE(created) >= '$dateminus1' - INTERVAL 27 DAY) "
                        . "AND date BETWEEN '$dateminus1' - INTERVAL 6 DAY AND '$dateminus1' "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id) "
                        . "UNION (SELECT r.id AS retailer_id, '2nd_last_week' AS week, SUM(rel.amount) AS sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id NOT IN (SELECT id FROM retailers WHERE DATE(created) >= '$dateminus1' - INTERVAL 27 DAY) "
                        . "AND date BETWEEN '$dateminus1' - INTERVAL 13 DAY AND '$dateminus1' - INTERVAL 7 DAY "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id) "
                        . "UNION (SELECT r.id AS retailer_id, '3rd_last_week' AS week, SUM(rel.amount) AS sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id NOT IN (SELECT id FROM retailers WHERE DATE(created) >= '$dateminus1' - INTERVAL 27 DAY) "
                        . "AND date BETWEEN '$dateminus1' - INTERVAL 20 DAY AND '$dateminus1' - INTERVAL 14 DAY"
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id) "
                        . "UNION (SELECT r.id AS retailer_id, '4th_last_week' AS week, SUM(rel.amount) AS sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id NOT IN (SELECT id FROM retailers WHERE DATE(created) >= '$dateminus1' - INTERVAL 27 DAY) "
                        . "AND date BETWEEN '$dateminus1' - INTERVAL 27 DAY AND '$dateminus1' - INTERVAL 21 DAY "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id)";

		$dataRetailersAverageSales = $this->Slaves->query ( $query );
		$retailersAverageSales = array ();
		foreach ( $dataRetailersAverageSales as $arr ) {
			$retailersAverageSales [$arr [0] ['retailer_id']] [$arr [0] ['week']] = round ( ($arr [0] ['sale']) / 7 );
		}
// 		echo "<pre>";
// 		print_r($retailersAverageSales);

		// sales down from 4th week to 1st week down by 50%
		$retailerIdsGraduallyDropped = array ();
		$retailerIdsGraduallyDroppedCount = 0;

		$salesThreshold = 500;
		// Keys for week: last_week, 2nd_last_week, 3rd_last_week, 4th_last_week
		foreach ( $retailersAverageSales as $retailerID => $weeklySalesArr ) {
			if ($weeklySalesArr ['last_week'] < $salesThreshold)
				continue;

			// sales of the last week must be less than 50% of 4th last week for reporting them
			$sales4thLastWeek50Percent = $weeklySalesArr ['4th_last_week'] * 0.5;
			if ($weeklySalesArr ['last_week'] > $sales4thLastWeek50Percent)
				continue;

			// used to calculate deviation of sales
			$sales4thLastWeek75Percent = $weeklySalesArr ['4th_last_week'] * 0.75;

			$retailerSalesDownCount = 0;
			foreach ( $weeklySalesArr as $week => $sales ) {
				if ($sales < $sales4thLastWeek75Percent)
					$retailerSalesDownCount ++;
			}

			if ($retailerSalesDownCount >= 2)
				$retailerIdsGraduallyDropped [$retailerIdsGraduallyDroppedCount ++] = $retailerID;
		}
// 		print_r($retailerIdsGraduallyDropped);

		$stringRetailerIdsGraduallyDropped = implode ( ",", $retailerIdsGraduallyDropped );

		//Getting the list of id's already entered in the table
		$query = "SELECT
					distinct retailer_id, down_date
				  FROM
					retailers_drop
				  WHERE
					down_date >= $dateminus28
						AND retailer_id IN ($stringRetailerIdsGraduallyDropped)";

		$dataDuplicateIds  = $this->Slaves->query($query);
// 		$dataDuplicateIds  = $this->Retailer->query($query);
// 		echo "<pre>";
// 		print_r($dataDuplicateIds);

		$duplicateIDs = array();
		$duplicateIDscount = 0;
		foreach ($dataDuplicateIds as $arr){
			$duplicateIDs [$duplicateIDscount++] = $arr['retailers_drop']['retailer_id'];
		}
// 		print_r($duplicateIDs);
		$retailerIdsGraduallyDropped = array_diff($retailerIdsGraduallyDropped,$duplicateIDs);
// 		print_r($retailerIdsGraduallyDropped);
// 		echo count($retailerIdsGraduallyDropped);
// 		die;

		$downTypeId = 1; // for retailers gradually dropping the id is 1
		$query = "";
		$query = "INSERT INTO retailers_drop
					(retailer_id,
					 down_type_id,
					 sale_4th_last_week,
					 sale_3rd_last_week,
					 sale_2nd_last_week,
					 sale_last_week,
					 down_date)
				  VALUES " ;

		foreach ($retailerIdsGraduallyDropped as $id){
			$sale4thLastWeek = $retailersAverageSales [$id]['4th_last_week'];
			if (!isset($sale4thLastWeek))
				$sale4thLastWeek = 0;
			$sale3rdLastWeek = $retailersAverageSales [$id]['3rd_last_week'];
			if (!isset($sale3rdLastWeek))
				$sale3rdLastWeek = 0;
			$sale2ndLastWeek = $retailersAverageSales [$id]['2nd_last_week'];
			if (!isset($sale2ndLastWeek))
				$sale2ndLastWeek = 0;
			$saleLastWeek = $retailersAverageSales [$id]['last_week'];
			if (!isset($saleLastWeek))
				$saleLastWeek = 0;
			$dateDown = $dateminus1;

			$query .= "($id, $downTypeId, $sale4thLastWeek, $sale3rdLastWeek, $sale2ndLastWeek, $saleLastWeek, '$dateminus1'), ";
		}
		$query = rtrim(trim($query),",");
// 		echo $query;

		//INSERT DATA IN THE DATABASE
		$this->Retailer->query($query);
// 		$this->Slaves->query($query);

	}

	/**
	 * The data will be inserted in 'retailers_drop' table
	 * for the retailers dropped out
	 * The 'down_type_id' = 2 for dropped out for the column(down_type_id)
	 * $dateMinus1 - INTERVAL 6 DAY for last transacted
	 * $dateMinus1 - INTERVAL 5 DAY for dropped out
	 * @param string $date: for external insertion of data in the database
	 */
	function insertRetailersDroppedOutData($date = NULL) {
		$this->autoRender = false;

		if($date != NULL)
			$dateMinus1 = $date;
		else
			$dateMinus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );

		$dateMinus8 = date ( 'Y-m-d', strtotime ( '-8 days' ) );
		$dateMinus37 = date ( 'Y-m-d', strtotime ( '-37 days' ) );
		$dateMinus7 = date ( 'Y-m-d', strtotime ( '-7 days' ) ); //  last transacted date
		$dateMinus6 = date ( 'Y-m-d', strtotime ( '-6 days' ) ); // day from which transaction dropped

// 		echo $dateMinus37;
// 		die;
		$query = "SELECT r.id AS retailer_id, MAX(date), SUM(rel.amount) AS 'sale' "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id NOT IN (SELECT id FROM retailers WHERE DATE(created) > '$dateMinus1' - INTERVAL 37 DAY) "
                        . "AND date > '$dateMinus1' - INTERVAL 37 DAY "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id "
                        . "HAVING (MAX(date) = '$dateMinus1' - INTERVAL 6 DAY "
                        . "AND AVG(rel.amount) > 500 "
                        . "AND COUNT(rel.id) > 10)";

		$dataValidIds = $this->Slaves->query ( $query );
		// echo "<pre>";

		$retailerAverageSales = array ();

		// AVERAGE SALES OF THE RETAILER
		foreach ( $dataValidIds as $arr ) {
			$retailerAverageSales [$arr ['r'] ['retailer_id']] = round ( $arr [0] ['sale'] / 30 );
		}

		// print_r($retailerAverageSales);

		$idRetailersDroppedOut = array ();
		$idRetailersDroppedOutCount = 0;

		foreach ( $retailerAverageSales as $id => $averageSales ) {
			$idRetailersDroppedOut [$idRetailersDroppedOutCount ++] = $id;
		}

// 		echo "<pre>";
// 		print_r($idRetailersDroppedOut);
		$stringidRetailersDroppedOut = implode ( ",", $idRetailersDroppedOut );
// 		echo $stringidRetailersDroppedOut;

		//Getting the list of id's already entered in the table
		$query = "SELECT
					distinct retailer_id, down_date
				  FROM
					retailers_drop
				  WHERE
					retailer_id IN ($stringidRetailersDroppedOut)";

// 		$dataDuplicateIds  = $this->Retailer->query($query);
		$dataDuplicateIds  = $this->Slaves->query($query);
		// 		echo "<pre>";
		// 		print_r($dataDuplicateIds);

		$duplicateIDs = array();
		$duplicateIDscount = 0;
		foreach ($dataDuplicateIds as $arr){
			$duplicateIDs [$duplicateIDscount++] = $arr['retailers_drop']['retailer_id'];
		}
		// 		print_r($duplicateIDs);
		$idRetailersDroppedOut = array_diff($idRetailersDroppedOut,$duplicateIDs);

		$stringidRetailersDroppedOut = implode ( ",", $idRetailersDroppedOut );

// 		echo "<br>$idRetailersDroppedOut";
// 		die;

		//SALES OF THE DEFAULTER RETAILERS

		$query = "(SELECT r.id AS retailer_id, 'last_week' AS week, SUM(rel.amount) AS sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id IN ($stringidRetailersDroppedOut) "
                        . "AND date BETWEEN '$dateMinus7' - INTERVAL 6 DAY AND '$dateMinus7' "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id) "
                        . "UNION (SELECT r.id AS retailer_id, '2nd_last_week' AS week, SUM(rel.amount) AS sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id IN ($stringidRetailersDroppedOut) "
                        . "AND date BETWEEN '$dateMinus7' - INTERVAL 13 DAY AND '$dateMinus7' - INTERVAL 7 DAY "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id) "
                        . "UNION (SELECT r.id AS retailer_id, '3rd_last_week' AS week, SUM(rel.amount) AS sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id IN ($stringidRetailersDroppedOut) "
                        . "AND date BETWEEN '$dateMinus7' - INTERVAL 20 DAY AND '$dateMinus7' - INTERVAL 14 DAY "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id) "
                        . "UNION (SELECT r.id AS retailer_id, '4th_last_week' AS week, SUM(rel.amount) AS sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id IN ($stringidRetailersDroppedOut) "
                        . "AND date BETWEEN '$dateMinus7' - INTERVAL 27 DAY AND '$dateMinus7' - INTERVAL 21 DAY "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id)"; 

		$dataRetailersAverageSales = $this->Slaves->query ( $query );
		$retailersAverageSales = array ();
		foreach ( $dataRetailersAverageSales as $arr ) {
			$retailersAverageSales [$arr [0] ['retailer_id']] [$arr [0] ['week']] = round ( ($arr [0] ['sale']) / 7 );
		}

		print_r($retailersAverageSales);

		$downTypeId = 2; // for retailers dropped out the id is 1
		$query = "";
		$query = "INSERT INTO retailers_drop
					(retailer_id,
					 down_type_id,
					 sale_4th_last_week,
					 sale_3rd_last_week,
					 sale_2nd_last_week,
					 sale_last_week,
					 down_date)
				  VALUES " ;

		foreach ($idRetailersDroppedOut as $id){
			$sale4thLastWeek = $retailersAverageSales [$id]['4th_last_week'];
			if (!isset($sale4thLastWeek))
				$sale4thLastWeek = 0;
			$sale3rdLastWeek = $retailersAverageSales [$id]['3rd_last_week'];
			if (!isset($sale3rdLastWeek))
				$sale3rdLastWeek = 0;
			$sale2ndLastWeek = $retailersAverageSales [$id]['2nd_last_week'];
			if (!isset($sale2ndLastWeek))
				$sale2ndLastWeek = 0;
			$saleLastWeek = $retailersAverageSales [$id]['last_week'];
			if (!isset($saleLastWeek))
				$saleLastWeek = 0;
			$dateDown = $dateMinus6;

			$query .= "($id, $downTypeId, $sale4thLastWeek, $sale3rdLastWeek, $sale2ndLastWeek, $saleLastWeek, '$dateDown'), ";
		}
		$query = rtrim(trim($query),",");
// 		echo $query;

// 		INSERT DATA IN THE DATABASE
// 		$this->Slaves->query($query);
		$this->Retailer->query($query);
	}

	/**
	 * Passes data to the index view to be rendered for dropped retailers
	 */
	function index() {
		//login filter

		$this->layout = 'alerts';
		$stringDropDate = "";
		$stringCallDate = "";
		$dropId = 1; // default selection of gradual drop;

		$dropDate_from = empty($_POST ['dropdate_from']) ? date('Y-m-d',strtotime('-15 days')) : $_POST ['dropdate_from'];
		$dropDate_to = empty($_POST ['dropdate_to']) ? date('Y-m-d') : $_POST ['dropdate_to'];
		//$callDate = $_POST ['calldate'];

		//$dropDate = empty($dropDate) ? date('Y-m-d') : $dropDate;
		//$callDate = empty($callDate) ? date('Y-m-d') : $callDate;

		if ($dropDate_from != NULL && $dropDate_to != NULL) {
			$stringDropDate = " AND RD.down_date >= '$dropDate_from' AND RD.down_date <= '$dropDate_to'";
		}
		/*if ($callDate != NULL) {
			$stringCallDate = " AND RD.call_date = '$callDate' ";
		}*/
		if ($_POST ['droptype'] != NULL) {
			$dropId = $_POST ['droptype'];
		}

		// Data retailers

//		$query = "SELECT
//                                    R.id,
//                                    R.shopname,
//                                    R.mobile,
//                                    R.created,
//                                    D.state,
//                                    D.active_flag,
//                                    D.company AS distributor_company,
//                                    max(RL.date) AS last_txn,
//
//                                    GREATEST( sum(android_sale), sum(web_sale), sum(sms_sale),sum(ussd_sale),sum(java_sale)) recharge_mode_amt, case GREATEST(android_sale, web_sale,sms_sale)
//                                        when android_sale then 'android'
//                                        when web_sale then 'web'
//                                        when sms_sale then 'sms'
//                                        when ussd_sale then 'ussd'
//                                        when java_sale then 'java'
//                                        end recharge_mode,
//
//                                    DATEDIFF(max(RL.date),date(R.created)) as retailer_age,
//                                    D.mobile AS distributor_mobile,
//                                        RD.down_type_id,
//                                        RD.sale_4th_last_week,
//                                        RD.sale_3rd_last_week,
//                                        RD.sale_2nd_last_week,
//                                        RD.sale_last_week,
//                                        RD.down_date,
//                                        RD.call_flag,
//                                        RD.id,
//                                        RD.sale_1st_week_post_call,
//                                        RD.sale_2nd_week_post_call,
//                                        RD.sale_3rd_week_post_call,
//                                        RD.sale_4th_week_post_call
//                                FROM
//                                    retailers AS R,
//                                        distributors AS D,
//
//                                        retailers_drop AS RD,
//                                        retailers_logs AS RL
//                                WHERE
//                                        RD.retailer_id = R.id
//                                         AND R.parent_id = D.id
//                                         AND RL.retailer_id = R.id
//
//                                         AND RD.down_type_id = $dropId
//                                         $stringCallDate $stringDropDate
//                                         group by RL.retailer_id
//                                order by D.company ";// default selection: Gradually dropping data
		$query = "SELECT *,"
                        . "GREATEST(android_sale,web_sale,sms_sale,ussd_sale,java_sale) recharge_mode_amt, "
                        . "CASE GREATEST(android_sale, web_sale,sms_sale,ussd_sale,java_sale) WHEN android_sale THEN 'android' WHEN web_sale THEN 'web' WHEN sms_sale THEN 'sms' WHEN ussd_sale THEN 'ussd' WHEN java_sale THEN 'java' END recharge_mode "
                        . "FROM "
                        . "(SELECT R.id as retailer_id,R.shopname,R.mobile,R.created,D.state,D.active_flag,D.company AS distributor_company,max(RL.date) AS last_txn,RL.api_flag,if(api_flag = 3,SUM(RL.amount),0) as android_sale,if(api_flag = 9,SUM(RL.amount),0) as web_sale,if(api_flag = 0,SUM(RL.amount),0) as sms_sale,if(api_flag = 2,SUM(RL.amount),0) as ussd_sale,if(api_flag = 5,SUM(RL.amount),0) as java_sale,"
                        . "DATEDIFF(max(RL.date),date(R.created)) as retailer_age, D.mobile AS distributor_mobile,RD.down_type_id,RD.sale_4th_last_week,RD.sale_3rd_last_week,RD.sale_2nd_last_week,RD.sale_last_week,RD.down_date,RD.call_flag,RD.id,RD.sale_1st_week_post_call,RD.sale_2nd_week_post_call,RD.sale_3rd_week_post_call,RD.sale_4th_week_post_call "
                        . "FROM retailers R "                        
                        . "JOIN retailers_drop RD ON (RD.retailer_id = R.id) "
                        . "JOIN retailer_earning_logs RL ON (RL.ret_user_id = R.user_id) "
                        . "JOIN distributors D ON (RL.dist_user_id = D.user_id) "
                        . "WHERE RD.down_type_id = $dropId $stringCallDate $stringDropDate "
                        . "GROUP BY R.id,RL.api_flag "
                        . "ORDER BY D.company) as drop_data ";// default selection: Gradually dropping data 

// 		$dataDropDetails = $this->Retailer->query ( $query );
		$dataDropDetails = $this->Slaves->query($query);
		$dropDetails = array ();

		foreach ( $dataDropDetails as $arr ) {
			$tag_query = $this->Slaves->query("	select t.name
											from comments c
											left join taggings t on t.id = c.tag_id
											where t.type = 'retailers_drop'
											and retailers_id = '".$arr ['R'] ['id']."'
											order by c.id desc
											limit 1");

			$tag = $tag_query['0']['t']['name'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['unique_id'] = $arr ['drop_data'] ['retailer_id'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['shopname'] = $arr ['drop_data'] ['shopname'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['tag'] = $tag;
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['mobile'] = $arr ['drop_data'] ['mobile'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['created'] = $arr ['drop_data'] ['created'];
                        $dropDetails [$arr ['drop_data'] ['retailer_id']] ['state'] = $arr ['drop_data'] ['state'];
                        $dropDetails [$arr ['drop_data'] ['retailer_id']] ['dist_active'] = $arr ['drop_data'] ['active_flag'];
                        $dropDetails [$arr ['drop_data'] ['retailer_id']] ['last_txn'] = $arr ['drop_data'] ['last_txn'];
                        $dropDetails [$arr ['drop_data'] ['retailer_id']] ['retailer_age'] = $arr ['drop_data'] ['retailer_age'];
                        $dropDetails [$arr ['drop_data'] ['retailer_id']] ['recharge_mode'] = $arr ['0'] ['recharge_mode'].' ( Rs. '.$arr ['0'] ['recharge_mode_amt'].') ';
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['distributor_company'] = $arr ['drop_data'] ['distributor_company'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['distributor_mobile'] = $arr ['drop_data'] ['distributor_mobile'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['down_type_id'] = $arr ['drop_data'] ['down_type_id'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['sale_4th_last_week'] = $arr ['drop_data'] ['sale_4th_last_week'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['sale_3rd_last_week'] = $arr ['drop_data'] ['sale_3rd_last_week'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['sale_2nd_last_week'] = $arr ['drop_data'] ['sale_2nd_last_week'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['sale_last_week'] = $arr ['drop_data'] ['sale_last_week'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['down_date'] = $arr ['drop_data'] ['down_date'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['call_flag'] = $arr ['drop_data'] ['call_flag'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['sale_1st_week_post_call'] = $arr ['drop_data'] ['sale_1st_week_post_call'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['sale_2nd_week_post_call'] = $arr ['drop_data'] ['sale_2nd_week_post_call'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['sale_3rd_week_post_call'] = $arr ['drop_data'] ['sale_3rd_week_post_call'];
			$dropDetails [$arr ['drop_data'] ['retailer_id']] ['sale_4th_week_post_call'] = $arr ['drop_data'] ['sale_4th_week_post_call'];
		}

		$query = "SELECT distinct id, name FROM taggings WHERE type = 'retailers_drop' ORDER BY  name";
// 		$dataTaggings = $this->Retailer->query ( $query );
		$dataTaggings = $this->Slaves->query($query);
		$taggings = array ();

		foreach ( $dataTaggings as $index => $arr ) {
			$taggings [$arr ['taggings'] ['id']] = $arr ['taggings'] ['name'];
		}

		// login details of a user
		$userDetails = $this->Session->read ( 'Auth.User' );
		$userMobile = $userDetails ['mobile'];

		$this->set ( 'tags', $taggings );
		$this->set ( 'details', $dropDetails );
		$this->set ( 'userMobile', $userMobile );
		$this->set ( 'dropId', $dropId );
		$this->set ( 'dropDate_from', $dropDate_from );
		$this->set ( 'dropDate_to', $dropDate_to );
	}


	/**
	 * Generates the excel dump of the data created for whose call is done
	 */
// 	function downloadDumpData (){
	function downloadDumpData ($dropId = NULL, $dropDate = NULL, $callDate = NULL){
			//Data for download options
                ini_set ( "memory_limit", "-1" );

		$this->autoRender = false;
		$stringDropDate = "";
		$stringCallDate = "";
// 		$dropId = 1; // default selection of gradual drop;
// echo "<pre>";
// print_r($_REQUEST);
// echo "<br>". $_REQUEST['dropdate'] ."<br>" . $_REQUEST['calldate'] . "<br>" . $_REQUEST['dropid'];;

		$dropDate = isset($dropDate) ? $dropDate : "";
		$callDate = isset($callDate) ? $callDate : "";
		$dropId = isset($dropId) ? $dropId : 1;

		if($dropDate != ""){
			$stringDropDate = " AND RD.down_date = '". $dropDate . "' ";
		}
		if($callDate != ""){
			$stringCallDate = " AND RD.call_date = '". $callDate ."' ";
		}

// 		echo "<br> $dropDate <br> $callDate <br> $dropId";
// 		echo "<br> $stringDropDate <br> $stringCallDate <br> $dropId";

// 		echo "<pre> $stringCallDate <br> $stringDropDate <br> $dropId <br>";
// 		if ($_POST ['droptype'] != NULL) {
// 			$dropId = $_POST ['droptype'];
// 		}

// 		$dropId = 1;
// 		$$dropDateString = "";
// 		$callDateString = "";
// 		$dumpQuery = "SELECT
// 						R.id,
// 						R.shopname,
// 						R.mobile,
// 						R.created,
// 						D.company AS distributor_company,
// 						U.mobile AS distributor_mobile,
// 						RD.down_type_id,
// 						RD.sale_4th_last_week,
// 						RD.sale_3rd_last_week,
// 						RD.sale_2nd_last_week,
// 						RD.sale_last_week,
// 						RD.down_date,
// 						RD.call_flag,
// 						RD.call_time,
// 						RD.id,
// 						RD.sale_1st_week_post_call,
// 						RD.sale_2nd_week_post_call,
// 						RD.sale_3rd_week_post_call,
// 						RD.sale_4th_week_post_call,
// 						RD.comment_id,
// 						C.comments,
// 						C.tag_id,
// 						T.name
// 					FROM
// 						retailers AS R,
// 						distributors AS D,
// 						users AS U,
// 						retailers_drop AS RD,
// 						taggings AS T,
// 						comments AS C
// 					WHERE
// 						RD.retailer_id = R.id
// 						 AND R.parent_id = D.id
// 						 AND U.id = D.user_id
// 						 AND RD.down_type_id = $dropId
// 						 AND RD.comment_id = C.id
// 						 AND C.tag_id = T.id
// 						 $stringDropDate  $stringCallDate";

//		$dumpQuery = "SELECT
//						R.id,
//						R.shopname,
//						R.mobile,
//						R.created,
//                                                D.state,
//                                                D.active_flag,
//                                                max(RL.date) AS last_txn,
//
//                                    GREATEST( sum(android_sale), sum(web_sale), sum(sms_sale),sum(ussd_sale),sum(java_sale)) recharge_mode_amt, case GREATEST(android_sale, web_sale,sms_sale)
//                                        when android_sale then 'android'
//                                        when web_sale then 'web'
//                                        when sms_sale then 'sms'
//                                        when ussd_sale then 'ussd'
//                                        when java_sale then 'java'
//                                        end recharge_mode,
//
//                                    DATEDIFF(max(RL.date),date(R.created)) as retailer_age,
//
//                                                D.company AS distributor_company,
//						D.mobile AS distributor_mobile,
//						RD.down_type_id,
//						RD.sale_4th_last_week,
//						RD.sale_3rd_last_week,
//						RD.sale_2nd_last_week,
//						RD.sale_last_week,
//						RD.down_date,
//						RD.call_flag,
//						RD.call_time,
//						RD.id,
//						RD.sale_1st_week_post_call,
//						RD.sale_2nd_week_post_call,
//						RD.sale_3rd_week_post_call,
//						RD.sale_4th_week_post_call,
//						RD.comment_id,
//						C.mobile AS calling_number,
//						U2.name AS call_made_by,
//						C.comments,
//						C.tag_id,
//						T.name
//					FROM
//						retailers AS R,
//						distributors AS D,
//
//						users AS U2,
//						retailers_drop AS RD,
//                                                retailers_logs AS RL,
//                                                taggings AS T,
//						comments AS C
//					WHERE
//						RD.retailer_id = R.id
//						 AND R.parent_id = D.id
//                                                 AND RL.retailer_id = R.id
//
//						 AND RD.down_type_id = $dropId
//						 AND RD.comment_id = C.id
//						 AND C.tag_id = T.id
//						 AND C.mobile = U2.mobile
//						 $stringDropDate  $stringCallDate
//                                                 group by RL.retailer_id
//                                        ORDER BY D.company ";
		$dumpQuery = "SELECT *,"
                        . "GREATEST(android_sale,web_sale,sms_sale,ussd_sale,java_sale) recharge_mode_amt, "
                        . "CASE GREATEST(android_sale, web_sale,sms_sale,ussd_sale,java_sale) WHEN android_sale THEN 'android' WHEN web_sale THEN 'web' WHEN sms_sale THEN 'sms' WHEN ussd_sale THEN 'ussd' WHEN java_sale THEN 'java' END recharge_mode "
                        . "FROM (SELECT R.id as retailer_id,R.shopname,R.mobile,R.created,D.state,D.active_flag,max(RL.date) AS last_txn,if(api_flag = 3,SUM(RL.amount),0) as android_sale,if(api_flag = 9,SUM(RL.amount),0) as web_sale,if(api_flag = 0,SUM(RL.amount),0) as sms_sale,if(api_flag = 2,SUM(RL.amount),0) as ussd_sale,if(api_flag = 5,SUM(RL.amount),0) as java_sale,"
                        . "DATEDIFF(max(RL.date),date(R.created)) as retailer_age,D.company AS distributor_company,D.mobile AS distributor_mobile,RD.down_type_id,RD.sale_4th_last_week,RD.sale_3rd_last_week,RD.sale_2nd_last_week,RD.sale_last_week,RD.down_date,RD.call_flag,RD.call_time,RD.id,RD.sale_1st_week_post_call,RD.sale_2nd_week_post_call,RD.sale_3rd_week_post_call,RD.sale_4th_week_post_call,RD.comment_id,C.mobile AS calling_number,U2.name AS call_made_by,C.comments,C.tag_id,T.name "
                        . "FROM retailers R "                        
                        . "JOIN retailer_earning_logs AS RL ON (RL.ret_user_id = R.user_id) "
                        . "JOIN distributors D ON (RL.dist_user_id = D.user_id) "
                        . "LEFT JOIN retailers_drop RD ON (RD.retailer_id = R.id) "                        
                        . "LEFT JOIN comments AS C ON (RD.comment_id = C.id) "
                        . "LEFT JOIN taggings AS T ON (C.tag_id = T.id) "
                        . "LEFT JOIN users AS U2 ON (C.mobile = U2.mobile) "
                        . "WHERE RD.down_type_id = $dropId $stringDropDate  $stringCallDate "
                        . "GROUP BY R.id,RL.api_flag "
                        . "ORDER BY D.company) AS ret_logs ";

// 		echo $dumpQuery;die;

		$dataDump = $this->Slaves->query($dumpQuery);
                
		$dump = array ();
// 		echo "<pre>";
// 		print_r($dataDump);

		foreach ( $dataDump as $arr ) {
			$dump [$arr ['ret_logs'] ['retailer_id']] ['unique_id'] = $arr ['ret_logs'] ['retailer_id'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['shopname'] = $arr ['ret_logs'] ['shopname'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['mobile'] = $arr ['ret_logs'] ['mobile'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['created'] = $arr ['ret_logs'] ['created'];
                        $dump [$arr ['ret_logs'] ['retailer_id']] ['state'] = $arr ['ret_logs'] ['state'];
                        $dump [$arr ['ret_logs'] ['retailer_id']] ['dist_active'] = $arr ['ret_logs'] ['active_flag'];
                        $dump [$arr ['ret_logs'] ['retailer_id']] ['last_txn'] = $arr ['ret_logs'] ['last_txn'];
                        $dump [$arr ['ret_logs'] ['retailer_id']] ['retailer_age'] = $arr ['ret_logs'] ['retailer_age'];
                        $dump [$arr ['ret_logs'] ['retailer_id']] ['recharge_mode'] = $arr ['0'] ['recharge_mode'].' ( Rs. '.$arr ['0'] ['recharge_mode_amt'].') ';
			$dump [$arr ['ret_logs'] ['retailer_id']] ['distributor_company'] = $arr ['ret_logs'] ['distributor_company'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['distributor_mobile'] = $arr ['ret_logs'] ['distributor_mobile'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['down_type_id'] = $arr ['ret_logs'] ['down_type_id'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['sale_4th_last_week'] = $arr ['ret_logs'] ['sale_4th_last_week'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['sale_3rd_last_week'] = $arr ['ret_logs'] ['sale_3rd_last_week'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['sale_2nd_last_week'] = $arr ['ret_logs'] ['sale_2nd_last_week'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['sale_last_week'] = $arr ['ret_logs'] ['sale_last_week'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['down_date'] = $arr ['ret_logs'] ['down_date'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['call_time'] = $arr ['ret_logs'] ['call_time'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['tag'] = $arr ['ret_logs'] ['name'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['comment'] = $arr ['ret_logs'] ['comments'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['call_flag'] = $arr ['ret_logs'] ['call_flag'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['calling_number'] = $arr ['ret_logs'] ['calling_number'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['call_made_by'] = $arr ['ret_logs'] ['call_made_by'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['sale_1st_week_post_call'] = $arr ['ret_logs'] ['sale_1st_week_post_call'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['sale_2nd_week_post_call'] = $arr ['ret_logs'] ['sale_2nd_week_post_call'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['sale_3rd_week_post_call'] = $arr ['ret_logs'] ['sale_3rd_week_post_call'];
			$dump [$arr ['ret_logs'] ['retailer_id']] ['sale_4th_week_post_call'] = $arr ['ret_logs'] ['sale_4th_week_post_call'];
		}

// 		print_r($dump);
// 		die;
		App::import('Helper','csv');
		$this->layout = null;
		$this->autoLayout = false;
		$csv = new CsvHelper();
		$line = array("S.No.","Shop Name","Mobile","Created On","State","Active","Last Trans","Retailer Age","Recharge Mode","Distributor Name","Distributor Mobile","Sale 4th last week from down date",
				"Sale 3rd last week from down date","Sale 2nd last week from down date","Sale last week from down date","Down Date","Problem Tag",
				"Comment text","Call Time", "Calling Number" , "Called By", "sale_1st_week_post_call" ,"sale_2nd_week_post_call", "sale_3rd_week_post_call", "sale_4th_week_post_call");

		$csv->addRow($line);
		$i=1;
		foreach ($dump as $data) {
			$downType ="";
			$temp = array($i, $data['shopname'],$data['mobile'], $data['created'],$data['state'],$data['dist_active'],$data['last_txn'],$data['retailer_age'],$data['recharge_mode'],$data['distributor_company'],$data['distributor_mobile'],
						$data['sale_4th_last_week'],$data['sale_3rd_last_week'],$data['sale_2nd_last_week'],$data['sale_last_week'],
						$data['down_date'],$data['tag'],$data['comment'], $data['call_time'], $data['calling_number'],
						$data['call_made_by'], $data['sale_1st_week_post_call'], $data['sale_2nd_week_post_call'],
					    $data['sale_3rd_week_post_call'],$data['sale_4th_week_post_call']);
			$csv->addRow($temp);
			$i++;
		}
// 		return $csv;
		echo $csv->render('retailer_report'.date('YmdHis').'.csv');
// 		echo  $csv;

	}

	/**
	 * keys: tag_id, comment, userMobile, retailer_id, retailerDropId
	 */
	function insertCommentData() {
		$this->autoRender = false;
		$date = date ( 'Y-m-d' );
		$dateTime = date ( 'Y-m-d H:i:s' );
		$details = $_POST;

		$query = "INSERT INTO comments (retailers_id,comments, mobile, tag_id, created, date)
					VALUES (
					" . $details ['retailer_id'] . ",
					'" . $details ['comment'] . "',
					'" . $details ['userMobile'] . "',
					" . $details ['tag_id'] . ",
					'" . $dateTime . "',
					'" . $date . "')";

// 		echo "comment insertion:  $query <br>";

		// INSERTING DATA IN comments TABLE
		$insertComment = $this->Retailer->query ( $query );
// 		$insertComment = $this->Slaves->query($query);
// 		echo "insertComment : $insertComment <br>";

		$data = array ();
		if ($insertComment == 1) {
			$query = "SELECT LAST_INSERT_ID() as 'insert_id'";
			$dataLastInsert = $this->Retailer->query ( $query );
// 			$dataLastInsert = $this->Slaves->query($query);
// 			print_r($dataLastInsert);
			$lastInsert = $dataLastInsert [0] [0] ['insert_id'];
// 			echo "last insert: $lastInsert <br>";

// UPDATING DATA IN retailers_drop TABLE
			$query = "UPDATE retailers_drop
						SET comment_id = $lastInsert ,
						call_time = '$dateTime' ,
						call_date = '$date' ,
						call_flag = 1
						WHERE
						id = " . $_POST ['retailerDropId'];
// 			echo $query;
			$this->Retailer->query ( $query );
// 			$this->Slaves->query($query);
			$data ["status"] = "success";
		} else {
			$data ["status"] = "failure";
		}

		echo json_encode ( $data );
	}

	/**
	 * Used for retrieving comments entered for a retailer corresponding to its drop
	 */
	function retrieveCommentDetails() {
		$this->autoRender = false;
		$downId = $_POST ['downId'];
		$retailerId = $_POST ['retailerId'];
                
                if (is_numeric($downId) && is_numeric($retailerId)) {
                        $query = "SELECT
                                                    RD.retailer_id, U.name, C.id, C.comments, T.name as tag, T.type as type, RD.down_date, RD.call_time
                                                FROM
                                                    retailers_drop AS RD,
                                                    taggings AS T,
                                                    comments AS C,
                                                        users AS U
                                                WHERE
                                                    RD.retailer_id = $retailerId
                                                        AND C.tag_id = T.id
                                                                AND C.mobile = U.mobile
                                                                AND C.retailers_id = $retailerId
                                                                AND T.type = 'retailers_drop'
                                                                AND RD.down_type_id = $downId";

                        $dataCommentDetails = $this->Slaves->query ( $query );
                        // print_r($dataCommentDetails);
                        $commentDetails = array ();

                        // Map arrays with comments Id
                        foreach ( $dataCommentDetails as $index => $arr ) {
                                $commentId = $arr ['C'] ['id'];

                                // echo $commentId."<br>";
                                foreach ( $arr as $table => $keys ) {
                                        foreach ( $keys as $key => $val ) {
                                                if ($key == "id" || $key == "retailer_id")
                                                        continue;
                                                $commentDetails [$commentId] [$key] = $val;
                                        }
                                }
                        }
                        $data = array ();
                        if (! empty ( $commentDetails )) {
                                $data ["status"] = "success";
                                $data ["response"] = $commentDetails;
                        } else {
                                $data ["status"] = "failure";
                        }
                }
		// print_r($data);
		echo json_encode ( $data );
	}

	/**
	 * This function inserts the data in the 'retailers_drop' table post call date.
	 * It will run on daily basis in backend from "cron-tab"
	 *
	 * @param unknown $param
	 */
	function insertDataPostCallDate() {
		$this->autoRender = false;

		// RETAILERS COMPLETING 1 WEEK SALE
		$dateDiff = 6;
		$saleWeek = "sale_1st_week_post_call";
		$this->helperInsertDataPostCallDate ( $dateDiff, $saleWeek );

		// RETAILERS COMPLETING 2 WEEK SALE
		$dateDiff = 13;
		$saleWeek = "sale_2nd_week_post_call";
		$this->helperInsertDataPostCallDate ( $dateDiff, $saleWeek );

		// RETAILERS COMPLETING 3 WEEK SALE
		$dateDiff = 20;
		$saleWeek = "sale_3rd_week_post_call";
		$this->helperInsertDataPostCallDate ( $dateDiff, $saleWeek );

		// RETAILERS COMPLETING 4 WEEK SALE
		$dateDiff = 27;
		$saleWeek = "sale_4th_week_post_call";
		$this->helperInsertDataPostCallDate ( $dateDiff, $saleWeek );
	}

	/**
	 * Helper function for inserting data for post call date sales in 'retailers_drop' table
	 *
	 * @param unknown $dateDiff:
	 *        	to get the 4 weeks sale
	 * @param unknown $updateColumn:
	 *        	to update the sale_post call date columns
	 */
	function helperInsertDataPostCallDate($dateDiff, $updateColumn) {
		$this->autoRender =  false;

// 		echo "Entered";
		$dateminus1 = date ( 'Y-m-d', strtotime ( '-1 days' ) );

		$retailerIdQuery = "SELECT
								id, retailer_id, call_date
							FROM
								retailers_drop
							WHERE
								DATEDIFF('$dateminus1', call_date) = $dateDiff";

// 		echo "retailer ID query = $retailerIdQuery <br>" ;
// 		die;


		$dataRetailerId = $this->Slaves->query ( $retailerIdQuery );
		$retailerIds = array ();

		// MAP ID (KEY) WITH RETAILER_ID (VALUE) IN 'RETAILERS_DROP' TABLE
		foreach ( $dataRetailerId as $index => $arr ) {
			$retailerIds [$arr ['retailers_drop'] ['id']] = $arr ['retailers_drop'] ['retailer_id'];
// 			$retailerIds [$arr ['retailers_drop'] ['id']] = $arr ['retailers_drop'] ['call_date'];
		}
// 		print_r($retailerIds);
		$stringRetailerId = implode ( ",", $retailerIds );
//		$salesQuery = "SELECT
//							retailer_id, SUM(sale) AS sale
//						FROM
//							retailers_logs
//						WHERE
//							retailer_id IN ($stringRetailerId)
//								AND date BETWEEN '$dateminus1' - INTERVAL $dateDiff DAY AND '$dateminus1'
//						GROUP BY retailer_id";
		$salesQuery = "SELECT r.id AS retailer_id, SUM(rel.amount) AS sale "
                        . "FROM retailer_earning_logs rel "
                        . "JOIN retailers r ON (rel.ret_user_id = r.user_id) "
                        . "WHERE r.id IN ($stringRetailerId) "
                        . "AND date BETWEEN '$dateminus1' - INTERVAL $dateDiff DAY AND '$dateminus1' "
                        . "AND rel.service_id IN (1,2,4,5,6,7) "
                        . "GROUP BY r.id";

// 		echo "sales query = $salesQuery <br>";
		$dataSales = $this->Slaves->query ( $salesQuery );

// 		echo "<pre>";
// 		print_r($dataSales);

		$sales = array ();
		foreach ( $dataSales as $arr ) {
			$sales [$arr ['r'] ['retailer_id']] = round ( ($arr ['0'] ['sale']) / 7 );
		}
// 		print_r($sales);


		// Inserting data in the 'retailers_drop' table
		// $id = id of the column entry in 'retailers_drop' table
		foreach ( $retailerIds as $id => $retailerId ) {
			$query = "UPDATE retailers_drop
						SET $updateColumn = " . $sales [$retailerId] . "
						 WHERE retailer_id = $retailerId AND id = $id";
// 			echo "<br>$query<br>";
			$this->Retailer->query ( $query );
		}
	}

/*	function retailerSales(){
		$this->autoRender = FALSE;



		$prevMonthSale = $this->Slaves->query("Select retailers.parent_id,retailers.id,retailers.name,retailers.mobile,locator_city.name,retailers.shopname,distributors.company,avg(retailers_logs.sale) as sale  from retailers inner join retailers_logs USE INDEX (idx_date) ON (retailers.id = retailers_logs.retailer_id) left join locator_area ON (locator_area.id = retailers.area_id) left join locator_city ON (locator_city.id = locator_area.city_id) left join distributors ON (distributors.id = retailers.parent_id) Where Month(retailers_logs.date) = '09' and YEAR (retailers_logs.date) = '2015' and ussd_sale=0 and `sms_sale` =0 GROUP BY retailers.id");

		$currMonthSale = $this->Slaves->query("Select retailers.id,retailers.name,retailers.mobile,retailers.shopname,avg(retailers_logs.sale) as sale  from retailers inner join retailers_logs USE INDEX (idx_date) ON (retailers.id = retailers_logs.retailer_id) Where Month(retailers_logs.date) = '10' and YEAR (retailers_logs.date) = '2015' and ussd_sale=0 and `sms_sale` =0 GROUP BY retailers.id");

        $distributor_ids = array();
		foreach($prevMonthSale as $val):

            $prevSale[$val['retailers']['id']] = $val;
            $distributor_ids[] = $val['retailers']['parent_id'];

		endforeach;


		foreach($currMonthSale as $val):

			$CurrSale[$val['retailers']['id']] = $val;

		endforeach;

        // IMP DATA ADDED : START
        $imp_data = $this->Shop->getUserLabelData(array_keys($prevSale),2,2);
        $imp_data_dist = $this->Shop->getUserLabelData($distributor_ids,2,3);
        // IMP DATA ADDED : END

		foreach ($prevSale as $retval):

			if(isset($CurrSale[$retval['retailers']['id']])):

				$perdiff = round(($CurrSale[$retval['retailers']['id']][0]['sale']-$retval[0]['sale'])/$retval[0]['sale']*100,2);


				if($perdiff>=30):

			    // $data[$retval['retailers']['id']] = array("Retailer name" =>$retval['retailers']['name'],"shopname" =>$retval['retailers']['shopname'],"mobile" =>$retval['retailers']['mobile'],"city" => $retval['locator_city']['name'], "Distributor company" => $retval['distributors']['company'],"Sep sale" => $retval[0]['sale'],"Oct sale" =>$CurrSale[$retval['retailers']['id']][0]['sale']);
			    $data[$retval['retailers']['id']] = array(
                                "Retailer name" =>$imp_data[$retval['retailers']['id']]['imp']['name'],
                                "shopname" =>$imp_data[$retval['retailers']['id']]['imp']['shop_est_name'],
                                "mobile" =>$imp_data[$retval['retailers']['id']]['ret']['mobile'],
                                "city" => $retval['locator_city']['name'],
                                "Distributor company" => $imp_data_dist[$retval['retailers']['parent_id']]['imp']['shop_est_name'],
                                "Sep sale" => $retval[0]['sale'],
                                "Oct sale" =>$CurrSale[$retval['retailers']['id']][0]['sale']
                            );

			   endif;


			  endif;

		 endforeach;

		echo json_encode($data);
		die;

	}*/

	function systemAlerts(){


		$date = date('Y-m-d');

		$transrequest = $this->Slaves->query("SELECT api_flag,max(timestamp) as timestamp "
												. "FROM `vendors_activations` "
												. "where date  = '$date'  and api_flag NOT IN (1,7,8)"
												. "group by api_flag"
											 );

		$vmnrequest = $this->Slaves->query("SELECT virtual_num,max(timestamp) as timestamp"
											. " FROM `virtual_number`"
											. " WHERE date = '$date' and  (virtual_num NOT IN (SELECT virtual_num FROM vmn_number where status != 1))"
											. " group by virtual_num "
					                       );



		  foreach ($transrequest as $transval):

			  if(time()-strtotime($transval[0]['timestamp'])>300 && (date('H',strtotime($transval[0]['timestamp'])))>='8' && date('H',strtotime($transval[0]['timestamp']))<'23'){

				  $deviceType = isset($transval['vendors_activations']['api_flag']) ? $transval['vendors_activations']['api_flag'] : "";

				switch($deviceType){
				case 0:
					$type = "SMS";
					break;
				case 1:
					$type = "API";
					break;
				case 2:
					$type = "USSD";
					break;
				case 3:
					$type = "Android";
					break;
				case 4:
					$type = "Partner";
					break;
				case 5:
					$type = "Java";
					break;
				case 6:
					$type = "";
					break;
				case 7:
					$type = "Windows 7";
					break;
				case 8:
					$type = "Windows 8";
					break;
				case 9:
					$type = "Web";
					break;
				default:
					$type = "None";
					break;
			}

			/*  $msg= "There in no transaction from $type from more than 5 Minutes last transaction time is {$transval[0]['timestamp']}";


			   $this->General->sendMails ("System alerts", $msg, array (
				'ashish@pay1.in',
				'dharmesh.chauhan@pay1.in',
				'chirutha@pay1.in',
				'nandan.rana@pay1.in',
				'vinit@pay1.in',
				'customer.care@pay1.in',
		            ), 'mail' );*/
			  }
		  endforeach;

		  foreach ($vmnrequest as $val):

			   if(time()-strtotime($val[0]['timestamp'])>300 && (date('H',strtotime($val[0]['timestamp'])))>='8' && date('H',strtotime($val[0]['timestamp']))<'23'){

				$vmnmsg= "There in no hit  from VMN Number  {$val['virtual_number']['virtual_num']} from more than 5 Minutes last transaction time is {$val[0]['timestamp']}";

					 $this->General->sendMails ("VMN alerts", $vmnmsg,array(
				'chirutha@pay1.in',
                                'dipali.warekar@pay1.in',
				'customer.care@pay1.in',
		            ),'mail' );
					}


		  endforeach;

		  $this->autoRender = false;

	}
}
?>