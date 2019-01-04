<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'earning'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
					<div>
					<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($from)) echo $from;?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($to)) echo $to;?>">
					
					<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="earningSearch();"></span>
					</div>
					<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
					<div class="appTitle" style="margin-top:20px;">Earning Report</div>
						<table style="margin-top:10px" width="100%" border="1" summary="Company Rolling">
						 	<thead>
					          <tr>
							    <th rowspan="2" scope="col">Date</th>
							    <th class="number" rowspan="2" scope="col">Sale</th>
							    <th class="number" rowspan="2" scope="col">Investment</th>
							    <th class="number" colspan="2" scope="col">Gross Earning</th>
							    <th class="number" colspan="3" scope="col">Cost</th>
							    <th class="number" rowspan="2" scope="col">Net Earning</th>
							    <th class="number" colspan="3" scope="col">%</th>
							    
							  </tr>
							  <tr>
							    <th scope="col">Expected </th>
							    <th scope="col">Actual</th>
							    <th scope="col">Commission Tfrd</th>
							    <th scope="col">Service Charge</th>
							    <th scope="col">Incentives</th>
							    <th scope="col">Expected</th>
							    <th scope="col">Tfrd</th>
							    <th scope="col">Net Earning</th>
							  </tr>
					        </thead>
				        	<tbody>
				        	<?php 
				        	$tot_earn = 0;
				        	$tot_sale = 0;
				        	$tot_expect = 0;
				        	//$tot_ret = 0;
				        	//$tot_dist = 0;
				        	//$tot_sd = 0;
				        	$tot_com = 0;
				        	$tot_ref = 0;
				        	$tot_invested = 0;
				        	foreach($data as $date => $dt){
				        		if(!isset($dt['retailer_earning']))$dt['retailer_earning'] = 0;
				        		if(!isset($dt['distributor_earning']))$dt['distributor_earning'] = 0;
				        		if(!isset($dt['sdistributor_earning']))$dt['sdistributor_earning'] = 0;
				        		if(!isset($dt['earning']))$dt['earning'] = 0;
				        		if(!isset($dt['sale']))$dt['sale'] = 0;
				        		if(!isset($dt['expected_earning']))$dt['expected_earning'] = 0;
				        		$commission = $dt['retailer_earning'] + $dt['distributor_earning'] + $dt['sdistributor_earning'];
				        		$service_charge = $dt['retailer_service_charge'];
				        		$earning = 0;
				        		if($dt['invested'] > 0)
				        		$earning = $dt['earning'] - $commission - $dt['refunds'] + $service_charge;
				        		
				        		$tot_earn += $earning;
				        		$tot_sale += $dt['sale'];
				        		$tot_expect += $dt['expected_earning'];
				        		//$tot_ret += $dt['retailer_earning'];
				        		//$tot_dist += $dt['distributor_earning'];
				        		//$tot_sd += $dt['sdistributor_earning'];
				        		$tot_com += $commission;
				        		$tot_sc += $service_charge;
				        		$tot_ref += $dt['refunds'];
				        		$tot_invested += $dt['invested'];
				        	?>
				        		<tr>
				        			<td><b><?php echo $date;?></b></td>
				        			<td class="number"><?php echo $dt['sale'];?></td>
				        			<td class="number"><?php echo $dt['invested'];?></td>
				        			<td class="number"><?php echo intval($dt['expected_earning']);?></td>
				        			<td class="number"><?php echo intval($dt['earning']);?></td>
				        			<td class="number"><?php echo $commission;?></td>
				        			<td class="number"><?php echo $service_charge;?></td>
				        			<td class="number"><?php echo $dt['refunds'];?></td>
				        			<td class="number"><?php echo intval($earning);?></td>
				        			<td class="number"><?php echo round($dt['expected_earning']*100/$dt['sale'],2); ?></td>
				        			<td class="number"><?php echo round($commission*100/$dt['sale'],2); ?></td>
				        			<td class="number"><?php echo round($earning*100/$dt['sale'],2); ?></td>
				        		</tr>
				        	<?php } ?>
				        	</tbody>
				        	<tfoot>
				        		<tr>
				        			<td><b>Total</b></td>
				        			<td class="number"><b><?php echo $tot_sale;?></b></td>
				        			<td class="number"><b><?php echo $tot_invested;?></b></td>
				        			<td class="number" colspan="2"><b></b></td>
				        			<td class="number"><b><?php echo $tot_com;?></b></td>
				        			<td class="number"><b><?php echo $tot_sc;?></b></td>
				        			<td class="number"><b><?php echo $tot_ref;?></b></td>
				        			
				        			<td class="number"><b><?php echo intval($tot_earn); ?></b></td>
				        			
				        			
                                                                <?php if($tot_invested == 0){ ?>
                                                                    <td class="number"><b>0</b></td>
                                                                    <td class="number"><b>0</b></td>
                                                                    <td class="number"><b>0</b></td>
                                                                
                                                                <?php }else{ ?>
				        			<td class="number"><b><?php echo round($tot_expect*100/$tot_sale,2); ?></b></td>
				        			<td class="number"><b><?php echo round($tot_com*100/$tot_sale,2); ?></b></td>
				        			<td class="number"><b><?php echo round($tot_earn*100/$tot_sale,2); ?></b></td>
                                                                <?php  } ?>
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
function earningSearch(){
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
		document.location.href="/shops/earningReport/"+$('fromDate').value+"/"+$('toDate').value;
	}
}
</script>