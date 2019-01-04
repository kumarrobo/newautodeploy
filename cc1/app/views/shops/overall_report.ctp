<?php if(!isset($pageType) || $pageType != 'csv'){



?>
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'overall'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  				<div>
	    				<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
	    				<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="report();"></span>
                        <span><a href="?res_type=csv" ><img id="export_csv" type="button" alt="xp" class="export_csv" src="/img/csv1.jpg" style="height:25px" /></a></span>
    			
    					<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
					</div>
	  				<div class="appTitle" style="margin-top:20px;">Overall Report</div>
	  				<table style="margin-top:20px;" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
                    
                    <tr align="center">
                    	<table width="100%" cellspacing="0" cellpadding="0" border="1" class="ListTable" summary="Transactions">
                    		<tr>
                                <td><b>City</b></td>
								 <td><b>State</b></td>
                                <td><b>Distributor</b></td>
								 <td><b>Id</b></td>
								  <td><b>Reg Date</b></td>
								 <td><b>Margin Slab</b></td>
                                <!--<td><b>Mobile No.</b></td>-->
								<td><b><?php if($_SESSION['Auth']['show_sd'] == 1){ echo 'Lead Master Distributor'; }else { echo 'Lead RM'; } ?> </b></td>
                                <td><b><?php if($_SESSION['Auth']['show_sd'] == 1){ echo 'Current Master Distributor'; }else { echo 'Current RM'; } ?></b></td>
                                <td><b>Primary Value</b></td>
                                <td><b>Primary Txn</b></td>
                                <td><b>Primary Avg</b></td>
								<td><b>Previous Primary Avg</b></td>
                                <td><b>Secondry Value</b></td>
                    			<td><b>Secondry Unique Retailer</b></td>
                                 <td><b>Secondry Avg</b></td>
								 <td><b>Previous Secondry Avg</b></td>
                                 <td><b>Tertiary Value</b></td>
                    			<td><b>Tertiary Txn</b></td>
                                 <td><b>Tertiary Avg</b></td>
								 <td><b>Previous Tertiary Avg</b></td>
								 <td><b> Half Yearly Benchmark - Avg Tertiary Value INR</b></td>
                    			<td><b>New Retailer</b></td>
								<td><b>Total Retailer Base</b></td>
								<td><b>Total Transacting Retailer</b></td>
                    			<td><b>Half Yearly Benchmark - Avg Transacting Retailer </b></td>
								<td><b>Discount Received</b></td>
								<td><b>Incentive Received</b></td>
                    		</tr>
                    		<?php  $primary=0; $secondary = 0; $tertiary= 0;$newr=0;
                    		foreach($datas as $id => $data) {
								
                                    if(isset($data['id'])) {
	                    		$primary += $data['primary'];
	                    		$secondary += $data['secondary'];
	                    		$tertiary += $data['tertiary'];
                                        $newr += $data['newr'];
                                        $primaryavg  = intval($data['primary']/$diffdays);
                                        $primaryavgtotal += $primaryavg;
                                        $secondryavg  = intval($data['secondary']/$diffdays);
                                        $secondryavgtotal += $secondryavg; 
                                        $tertiaryavg  = intval($data['tertiary']/$diffdays);
                                        $tertiaryavgtotal += $tertiaryavg;
                                        $prevprimaryavg = intval($data['prev_primary']/$diffdays);
                                        $prevprimaryavgtotal += $prevprimaryavg;
                                        $prevsecondryavg = intval($data['prev_secondary']/$diffdays);
                                        $prevsecondryavgtotal += $prevsecondryavg;
                                        $prevtertiaryavg = intval($data['prev_tertiary']/$diffdays);
                                        $prevtertiaryavgtotal += $prevtertiaryavg;
                                        $primarytxn += $data['primary_txn'];
                                        $secondarytxn += $data['secondry_txn'];
                                        $tertiarytxn += $data['tertiary_txn'];
                                        $baseretailers += $data['base_retailers'];
                                        $benchmarktertiary += $data['benchmark_tertiary'];
                                        $uniqueret += $data['unique_ret'];
                                        $transactingretailer += $data['transacting_retailer'];
                                        $discount = intval($data['discount']);
                                        $discounttotal += $discount;
                                        $refund = intval($data['refund']);
                                        $refundtotal += $refund;
                                        
                    		?>
                    		<tr>
                                <td><?php echo $data['city'];?></td>
								 <td><?php echo $data['state'];?></td>
                    			<td><?php echo $data['company'];?></td>
								<td><?php echo $data['id'];?></td>
								<td><?php echo date('Y-m-d',  strtotime($data['created_date']));?></td>
								<td><?php echo $data['margin'];?></td>
                    			<!--<td><?php echo $data['mobile'];?></td>-->
								<td><?php echo isset($data['created_rm']) ? $data['created_rm'] : '' ;?></td>
                    			<td><?php echo isset($data['rmname']) ? $data['rmname'] : '' ;?></td>
                    			<td><?php echo $data['primary'];?></td>
                    			<td><?php echo $data['primary_txn'];?></td>
                                <td><?php echo $primaryavg;?></td>
								<td><?php echo $prevprimaryavg;?></td>
                                <td><?php echo $data['secondary'];?></td>
                    			<td><?php echo $data['secondry_txn'];?></td>
                                <td><?php echo $secondryavg;?></td>
								<td><?php echo $prevsecondryavg;?></td>
                                <td><?php echo $data['tertiary'];?></td>
                    			<td><?php echo $data['tertiary_txn'];?></td>
                                <td><?php echo $tertiaryavg; ?></td>
								<td><?php echo $prevtertiaryavg;?></td>
								<td><?php echo $data['benchmark_tertiary'];?></td>
                                <td><?php echo $data['new_retailers'];?></td>
								<td><?php echo $data['base_retailers'];?></td>
								<td><?php echo $data['unique_ret'];?></td>
                                <td><?php echo $data['transacting_retailer'];?></td>
								<td><?php echo intval($data['discount']);?></td>
                                <td><?php echo intval($data['refund']);?></td>
                    		</tr>
                                <?php } } ?>
                    		<tr>
                                    <td colspan="8"><b>Total</b></td>
                                        
                                    <td><b><?php echo $primary;?></b></td>
                                        <td><b><?php echo $primarytxn;?></b></td>
                                        <td><b><?php echo $primaryavgtotal;?></b></td>
                                        <td><b><?php echo $prevprimaryavgtotal;?></b></td>
                                    <td><b><?php echo $secondary;?></b></td>
                                        <td class="abc"><b><?php echo $secondarytxn;?></b></td>
                                        <td><b><?php echo $secondryavgtotal;?></b></td>
                                        <td><b><?php echo $prevsecondryavgtotal;?></b></td>
                                    <td><b><?php echo $tertiary;?></b></td>
                                        <td><b><?php echo $tertiarytxn;?></b></td>
                                        <td><b><?php echo $tertiaryavgtotal;?></b></td>
                                        <td><b><?php echo $prevtertiaryavgtotal;?></b></td>
                                        <td><b><?php echo $benchmarktertiary;?></b></td>
                                    <td><b><?php echo $newr;?></b></td>
                                    <td><b><?php echo $baseretailers;?></b></td>
                                    <td><b><?php echo $uniqueret;?></b></td>
                                    <td><b><?php echo $transactingretailer;?></b></td>
                                    <td><b><?php echo $discounttotal;?></b></td>
                                    <td><b><?php echo $refundtotal;?></b></td>

                    		</tr>
                    	</table>
                    </tr>      
			   	</table>
			   	
			</fieldset>
   		</div>
   		<br class="clearLeft" />
    </div>
 </div>
<br class="clearRight" />
</div>
<script>
function report(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
	
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
        
        var dt_from = new Date(date_from.split("-").reverse().join("-")).getTime();
        var dt_to = new Date(date_to.split("-").reverse().join("-")).getTime();
	
        if(dt_from > dt_to){
		$('date_err').innerHTML = "Error: Please select proper dates";
		$('date_err').show();
		$('submit').innerHTML = html;
	} else {
		$('date_err').hide();
		date_from = date_from.replace(/-/g,"");
		date_to = date_to.replace(/-/g,"");
		document.location.href="/shops/overallReport/"+date_from+"-"+date_to;
	}
}
</script>
<?php } ?>
