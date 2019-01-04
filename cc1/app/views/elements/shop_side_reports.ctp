    <div class="leftFloat dashboardPack">
      <div class="catList">
        <ul id='innerul'>
        	<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER || $_SESSION['Auth']['User']['group_id'] == ACCOUNTS || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR) {

        	 if($_SESSION['Auth']['User']['group_id'] == ADMIN){
        	 	$report = "AD";
        	 	$report_child = "MasterDistributor";
        	 }
        	 else if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){
        	 	$report = "MD";
        	 	$report_child = "Distributor";
        	 }else if($_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){
                $report = "Super Distributor";
                $report_child = "Distributor";
             }
        	 else if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
        	 	$report = "Distributor";
        	 	$report_child = "Retailer";
        	 }else if($_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER){
        	 	$report = "RM";
        	 	$report_child = "Distributor";
        	 }

        	 ?>
        	 <li>
        	 <a class="hList" href="javascript:void(0);"><p>
        	 	<?php echo 'Accounts';?>
        	 	</p>
        	 </a>

        	<div class="sublist">
        		<ul>
                                <?php
                                        if(isset($_SESSION['Auth']['system_used']) && $_SESSION['Auth']['system_used'] == 1) {
                                                $report = 1;
                                        } else {
                                                $report = 0;
                                        }
                                        if($_SESSION['Auth']['User']['group_id']!=ACCOUNTS && $_SESSION['Auth']['User']['group_id']!=SUPER_DISTRIBUTOR) {
                                ?>
        			<li name='innerli'>
        				<a href="/shops/mainReport" class="<?php if($side_tab == 'main') echo 'sel';?>">Main Report</a>
        			</li>
                                <?php } ?>
        			<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){ ?>
        			<li name='innerli'>
        				<a href="/shops/accountHistory/<?php echo "0/1/".$report ?>" class="<?php if($side_tab == 'history') echo 'sel';?>">Account History</a>
        			</li>
        			<?php } ?>
        			<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER){ ?>
        			<li name='innerli'>
        				<a href="/shops/sReport" class="<?php if($side_tab == 'sale') echo 'sel';?>">Sale Report</a>
        			</li>
        			<li name='innerli'>
        				<a href="/shops/overallReport" class="<?php if($side_tab == 'overall') echo 'sel';?>">Overall Report</a>
        			</li>
        			<!--<li name='innerli'>
        				<a href="/shops/targetReport" class="<?php if($side_tab == 'scheme') echo 'sel';?>">Target Report</a>
        			</li>-->
        			<?php } ?>
        			<?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR) { ?>
        			<li name='innerli'>
        				<a href="/shops/topup" class="<?php if($side_tab == 'topup') echo 'sel';?>"><?php echo "Buy Report"; ?></a>
        			</li>
					<li name='innerli'>
								<a href="/shops/distributorsMonthReport" class="<?php if ($side_tab == 'distributors_month_report') echo 'sel'; ?>"><?php echo "Distributor Sale"; ?></a>
					</li>
        			<?php } ?>
        			<?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) { ?>
        			<li name='innerli'>
        				<a href="/shops/topup" class="<?php if($side_tab == 'topup') echo 'sel';?>"><?php echo "Buy Report"; ?></a>
        			</li>
                                <li name='innerli'>
        				<a href="/shops/overallReport" class="<?php if($side_tab == 'overall') echo 'sel';?>">Retailers Sale Report</a>
        			</li>
                                <li name='innerli'>
        				<a href="/shops/getInvoiceHistory" class="<?php if($side_tab == 'invoice') echo 'sel';?>">Invoice History</a>
        			</li>

                                <li name='innerli'>
        				<a href="/shops/getInvoiceHistoryNew" class="<?php if($side_tab == 'invoicenew') echo 'sel';?>">Invoice History New</a>
        			</li>
                                <li name='innerli'>
        				<a href="/shops/downloadTdsCertificate" class="<?php if($side_tab == 'download_tds') echo 'sel';?>">Download TDS Certificate</a>
        			</li>
        			<?php } else {
						 if($_SESSION['Auth']['User']['group_id']!=ACCOUNTS){?>
        			<li name='innerli'>
        				<a href="/shops/topupDist" class="<?php if($side_tab == 'topup') echo 'sel';?>"><?php echo "Balance Transfer Report"; ?></a>
        			</li>
						 <?php } } ?>
        			<!--<li name='innerli'>
        				<a href="/shops/saleReport" class="<?php //if($side_tab == 'sale') echo 'sel';?>">Sale Report</a>
        			</li>-->
        			<?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) { ?>
        			<li name='innerli'>
                                    <a href="/shops/salesmanReport/<?php echo date('d-m-Y')."/".date('d-m-Y') ?>/0/0/0/<?php echo $report ?>" class="<?php if($side_tab == 'salesman') echo 'sel';?>">Salesman Report</a>
        			</li>
                                <li name='innerli'>
        				<a href="/shops/allRetailerTrans" class="<?php if($side_tab == 'allRetailerTrans') echo 'sel';?>">Retailer Transactions</a>
        			</li>
        			<?php } ?>
                                <?php  if( $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR ){ ?>
                                        <li name='innerli'>
                                                <a href="/shops/distEarningReport" class="<?php if($side_tab == 'dist_earning_report') echo 'sel';?>">Earning Report</a>
                                        </li>
                                        <li name='innerli'>
                                                <a href="/shops/kitReport" class="<?php if($side_tab == 'kitreport') echo 'sel';?>">Kits Report</a>
                                        </li>
        			<?php }  ?>
                                <?php if($_SESSION['Auth']['User']['group_id'] == SUPER_ADMIN) { ?>
                                <li name='innerli'>
        				<a href="/shops/getOverallGstReport" class="<?php if($side_tab == 'gstreport') echo 'sel';?>">Overall Gst Report</a>
        			</li>
                                <li name='innerli'>
        				<a href="/shops/getTDSReport" class="<?php if($side_tab == 'tdsreport') echo 'sel';?>">TDS Report</a>
        			</li>
        			<?php } ?>
        			<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == ACCOUNTS ){?>
<!--        			<li name='innerli'>
        				<a href="/shops/earningReport" class="<?php if($side_tab == 'earning') echo 'sel';?>">Earning Report</a>
        			</li>-->
        			<li name='innerli'>
        				<a href="/shops/floatReport" class="<?php if($side_tab == 'float') echo 'sel';?>">Float Report</a>
        			</li>
                                <li name='innerli'>
        				<a href="/shops/floatGraph" class="<?php if($side_tab == 'float_graph') echo 'sel';?>">Float Graph</a>
        			</li>
                                 <li name='innerli'>
        				<a href="/shops/getOverallGstReport" class="<?php if($side_tab == 'gstreport') echo 'sel';?>">Overall Gst Report</a>
        			</li>
                                <li name='innerli'>
        				<a href="/shops/getTDSReport" class="<?php if($side_tab == 'tdsreport') echo 'sel';?>">TDS Report</a>
        			</li>
                                <li name='innerli'>
        				<a href="/shops/getAllInvoices" class="<?php if($side_tab == 'invoicehistory') echo 'sel';?>">Invoice History</a>
        			</li>
                        <?php
                        /*        <li name='innerli'>
        				<a href="/shops/debitCreditReport" class="<?php if($side_tab == 'debitcreditreport') echo 'sel';?>">Debit Credit Report</a>
        			</li> */
                    ?>
                                <li name='innerli'>
        				<a href="/shops/uploadTdsCertificate" class="<?php if($side_tab == 'upload_tds') echo 'sel';?>">Upload TDS Certificate</a>
        			</li>
					<?php } if($_SESSION['Auth']['User']['group_id'] == ADMIN){ ?>
					<li name='innerli'>
        				<a href="/shops/incentivePullback" class="<?php if($side_tab == 'incentive_pullback') echo 'sel';?>">Incentive Pullback</a>
        			</li>
					<li name='innerli'>
        				<a href="/shops/recheckTrans" class="<?php if($side_tab == 'recheckTrans') echo 'sel';?>">Recheck Transactions</a>
        			</li>
					<?php } ?>

        		</ul>
        	</div>
        	</li>
        	<?php } else if($_SESSION['Auth']['User']['group_id'] == RETAILER) { ?>
        		<li>
        	 <a class="hList" href="javascript:void(0);"><p>
        	 	<?php echo 'Retailer';?>
        	 	</p>
        	 </a>

        	<div class="sublist">
        		<ul>
        			<li name='innerli'>
        				<a href="/shops/accountHistory" class="<?php if($side_tab == 'transfer') echo 'sel';?>">Ledger Balance</a>
        			</li>
        			<li name='innerli'>
        				<a href="/shops/saleReport" class="<?php if($side_tab == 'sale') echo 'sel';?>">Sale Report</a>
        			</li>
        			<li name='innerli'>
        				<a href="/shops/lastTransactions" class="<?php if($side_tab == 'trans') echo 'sel';?>">Last Transactions</a>
        			</li>
        			<!--<li name='innerli'>
        				<a href="/shops/invoices" class="<?php if($side_tab == 'invoice') echo 'sel';?>">Retailer Reports</a>
        			</li>-->
        			<li name='innerli'>
        				<a href="/shops/getCreditDebitNotes" class="<?php if($side_tab == 'credit') echo 'sel';?>">Credit / Debit Note</a>
        			</li>
        		</ul>
        	</div>
        	</li>
        	<?php } ?>

		</ul>
    </div>
  </div>
