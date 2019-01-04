<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'overall'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  				<div>
	    				<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
	    				<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="report();"></span><br/><br/>
                                        
                                        <span style="font-weight:bold;margin-right:10px;">Select Salesman: </span>
                                        <select id="salesman">
                                                <option value="0">ALL</option>
                                                <?php foreach($salesmans as $salesman) {?>
                                                        <option value="<?php echo $salesman['salesmen']['id'];?>" <?php if(isset($salesm) && $salesm == $salesman['salesmen']['id']) echo "selected";?>><?php echo $salesman['salesmen']['name'] . " - " . $salesman['salesmen']['mobile'] ; ?></option>
                                                <?php } ?>
                                        </select>
    					<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
					</div>
	  				<div class="appTitle" style="margin-top:20px;">Retailer Sale Report</div>
	  				<table style="margin-top:20px;" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
                    
                    <tr align="center">
                    	<table width="100%" cellspacing="0" cellpadding="0" border="1" class="ListTable" summary="Transactions">
                    		<tr>    <td><b>Retailer</b></td>
                    			<td align="center"><b>TopUp</b></td>
                    			<td align="center"><b>Sale</b></td>
                    			<td align="center"><b>App Sale</b></td>
                    			<td align="center"><b>SMS Sale</b></td>
                    			<td align="center"><b>Misscall Sale</b></td>                    			
                    		</tr>
                    		<?php $sum_sale=0; $sum_app_sale = 0; $sum_topup= 0;$sum_sms_sale = 0; $sum_ussd_sale = 0; 
                    		foreach($datas as $id => $data) { 
	                    		$sum_sale += $data[0]['sum_sale'];
	                    		$sum_app_sale += $data[0]['sum_app_sale'];
	                    		$sum_sms_sale += $data[0]['sum_sms_sale'];
	                    		$sum_ussd_sale += $data[0]['sum_ussd_sale'];
	                    		$sum_topup += $data[0]['sum_topup'];
                    		?>
                    		<tr>
                                        <td><?php echo $data['unverified_retailers']['rname']." ( ".$data['retailers']['rmobile']." )";?></td>
                    			<td align="center"><?php echo $data[0]['sum_topup'];?></td>
                    			<td align="center"><?php echo $data[0]['sum_sale'];?></td>
                    			<td align="center"><?php echo $data[0]['sum_app_sale'];?></td>
                    			<td align="center"><?php echo $data[0]['sum_sms_sale'];?></td>
                    			<td align="center"><?php echo $data[0]['sum_ussd_sale'];?></td>
                    			
                    		</tr>
                    		<?php } ?>
                    		<tr>
                    			<td><b>Total</b></td>
                    			<td align="center"><b><?php echo $sum_topup;?></b></td>
                    			<td align="center"><b><?php echo $sum_sale;?></b></td>
                    			<td align="center"><b><?php echo $sum_app_sale;?></b></td>
                    			<td align="center"><b><?php echo $sum_sms_sale;?></b></td>
                    			<td align="center"><b><?php echo $sum_ussd_sale;?></b></td>
                    			
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
	var salesman = $('salesman').value;
        
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
		document.location.href="/shops/overallReport/"+date_from+"-"+date_to+"/"+salesman;
	}
}
</script>