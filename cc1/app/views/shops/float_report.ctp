<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'float'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
					<div>
					<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($from)) echo $from;?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($to)) echo $to;?>">
					
					<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="floatSearch();"></span>
					</div>
					<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
					<div class="appTitle" style="margin-top:20px;">Float Report</div>
					<table style="margin-top:10px" width="100%" border="1" summary="Company Rolling">
						 	<thead>
					          <tr class="noAltRow altRow">
					            <th scope="col">Date</th>
					            <th scope="col">Opening</th>
							    <th scope="col">Closing</th>
							    <th scope="col">Money Tfrd</th>
							    <th scope="col">Sale (Last day)</th>
							    <th scope="col">Old reversals </th>
							    <th scope="col">B2C Sale</th>
							    <th scope="col">Dayend B2C Adjustment</th>
							    <th scope="col">Refunds</th>
							    <th scope="col">Rental</th>
							    <th scope="col">Discount Tfred</th>
							    <th scope="col">Actual Outgoing</th>
					          </tr>
					        </thead>
				        	<tbody>
				        	<?php 
				        	$tot_tfr = 0;
				        	$tot_sale = 0;
				        	$tot_rev = 0;
				        	$tot_refund = 0;
				        	$tot_rental = 0;
				        	$tot_comm = 0;
				        	$tot_outgoing = 0;
				        	$tot_topup = 0;
				        	$tot_adjusted = 0;
				        	foreach($data as $date => $dt){
				        		if(!isset($dt['opening']) || !isset($dt['closing']))continue;
				        		$tot_refund += $dt['refund'];
				        		$tot_rental += $dt['rental'];
				        		$tot_comm += $dt['commission'];
				        		$tot_tfr += $dt['transferred'];
					        	$tot_sale += $dt['sale'];
					        	$tot_rev += $dt['reversals'];
					        	$tot_topup += $dt['b2c_topup'];
					        	$tot_adjusted += $dt['adjusted'];
				        	
				        		$actual = $dt['closing'] + $dt['rental'] + $dt['sale'] - ($dt['refund']+$dt['reversals']+$dt['transferred']+$dt['opening']);
				        		$actual += $dt['b2c_topup'] - $dt['adjusted'];
				        		
				        		$tot_outgoing += $actual;
				        		
				        		if($dt['commission'] > $actual) {
				        			$color = 'green';
				        			$amt = $dt['commission'] - $actual;
				        		}
				        		else {
				        			$color = 'red';
				        			$amt = $actual - $dt['commission'];
				        		}
				        	?>
				        		<tr>
				        			<td><b><?php echo $date;?></b></td>
				        			<td class="number"><?php echo $dt['opening'];?></td>
				        			<td class="number"><?php echo $dt['closing'];?></td>
				        			<td class="number"><?php echo $dt['transferred'];?></td>
				        			<td class="number"><?php echo $dt['sale'];?></td>
				        			<td class="number"><?php echo $dt['reversals'];?></td>
				        			<td class="number"><?php echo $dt['b2c_topup'];?></td>
				        			<td class="number"><?php echo $dt['adjusted'];?></td>
				        			<td class="number"><?php echo $dt['refund'];?></td>
				        			<td class="number"><?php echo $dt['rental'];?></td>
				        			<td class="number"><?php echo $dt['commission'];?></td>
				        			<td class="number"><?php if($amt > 0) echo "<span style='color:".$color.";font-size:9px'>($amt) </span>".$actual; else echo $actual;?></td>
				        			
				        		</tr>
				        	<?php } ?>
				        	</tbody>
				        	<tfoot>
				        		<tr>
				        			<td><b>Total</b></td>
				        			<td></td>
				        			<td></td>
				        			<td class="number"><b><?php echo $tot_tfr;?></b></td>
				        			<td class="number"><b><?php echo $tot_sale;?></b></td>
				        			<td class="number"><b><?php echo $tot_rev;?></b></td>
				        			<td class="number"><b><?php echo $tot_topup;?></b></td>
				        			<td class="number"><b><?php echo $tot_adjusted;?></b></td>
				        			<td class="number"><b><?php echo $tot_refund;?></b></td>
				        			<td class="number"><b><?php echo $tot_rental;?></b></td>
				        			<td class="number"><b><?php echo $tot_comm;?></b></td>
				        			<td class="number"><b><?php echo $tot_outgoing;?></b></td>
				        		</tr>
				        	</tfoot>
						 	</table>
										        
				</fieldset>
   			</div>
   			<br class="clearLeft" />
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>

<script>
function floatSearch(){
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
	
	var from_d = date_from.split('-');
	from_d = from_d[2] + " " + from_d[1] + from_d[0];
	var to_d = date_to.split('-');
	to_d = to_d[2] + " " + to_d[1] + to_d[0];
	if(from_d > to_d){
		$('date_err').innerHTML = "Error: Please select proper dates";
		$('date_err').show();
	}
	else {
		document.location.href="/shops/floatReport/"+$('fromDate').value+"/"+$('toDate').value;
	}
}
</script>