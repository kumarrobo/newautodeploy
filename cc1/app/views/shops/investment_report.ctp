<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_activities',array('side_tab' => 'investment'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
					<div>
					<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($from)) echo $from;?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($to)) echo $to;?>">
					
					<span style="font-weight:bold;margin-left:10px;">Summary Type: </span> <select id="summ_type">
					   		<option value="0" <?php if(isset($summary) && $summary == 0) echo "selected";?>>Normal</option>
							<option value="1" <?php if(isset($summary) && $summary == 1) echo "selected";?>>Date Wise</option>
							<option value="2" <?php if(isset($summary) && $summary == 2) echo "selected";?>>Vendor Wise</option>
						</select>
					
					<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="vendorEarningSearch();"></span>
					
					<div style="margin-top:10px;"><span style="font-weight:bold;margin-right:10px;">Select Vendor: </span>
						<select id="vendor">
					   		<option value="0">ALL</option>
							<?php foreach($vendors as $vendor) {?>
								<option value="<?php echo $vendor['vendors']['id'];?>" <?php if(isset($id) && $id == $vendor['vendors']['id']) echo "selected";?>><?php echo $vendor['vendors']['company']; ?></option>
							<?php } ?>
						</select>
					</div>
				    <div style="padding:3px;margin-top:7px;margin-bottom:7px;">
					<span style="font-weight:bold;">Select Date:<input id="add_date" type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($from)) echo $from;?>"></span>
					<span style="font-weight:bold;">Select Vendor:
						<select id="add_vendor">
							<?php foreach($vendors as $vendor) {?>
								<option value="<?php echo $vendor['vendors']['id'];?>" <?php if(isset($id) && $id == $vendor['vendors']['id']) echo "selected";?>><?php echo $vendor['vendors']['company']; ?></option>
							<?php } ?>
						</select>	
					</span>
					<span><input type="button" value="Add" class="retailBut enabledBut" style="padding: 0 5px 3px" onclick="add();"></span>
					</div>				
					</div>
					<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
					<div class="appTitle" style="margin-top:20px;">Investment Report</div>
						 <?php 
						 $otot_invested = 0;
				        	$otot_sale = 0;
				        	$otot_earn = 0;
				        	$otot_exp = 0;
				        	$otot_reversal = 0;
				        	$otot_cf = 0;
				        	
				        	if($summary != 0) { ?>
				        	<table style="margin-top:10px; text-align:center" width="1000" border="1" summary="Company Rolling">
						 	<thead>
					          <tr class="noAltRow altRow">
					            <th scope="col">Date</th>
					            <th scope="col">Vendor</th>
							    <th scope="col">Opening</th>
							    <th scope="col">Closing</th>
							    <th scope="col">Invested</th>
							    <th scope="col">Sale </th>
							    <th scope="col">Expected Earning </th>
							    <!--<th scope="col">Reversals </th>-->
							    <th scope="col">Earn</th>
							    <th scope="col">Diff</th>
							    <th scope="col">Earn(%)</th>
								<th scope="col">Incentive</th>
							    <th scope="col">Comment</th>
							    <th scope="col">Edit</th>
					          </tr>
					        </thead>
					        <tbody>
				        	<?php }
						 foreach($data as $date => $val){ if($summary == 0) {?>
							<table style="margin-top:10px; text-align:center" width="1000" border="1" summary="Company Rolling">
						 	<thead>
					          <tr class="noAltRow altRow">
					            <th scope="col">Date</th>
					            <th scope="col">Vendor</th>
							    <th scope="col">Opening</th>
							    <th scope="col">Closing</th>
							    <th scope="col">Invested</th>
							    <th scope="col">Sale </th>
							    <th scope="col">Expected Earning </th>
							    <!--<th scope="col">Reversals </th>-->
							    <th scope="col">Earn</th>
							    <th scope="col">Diff</th>
							    <th scope="col">Earn(%)</th>
								<th scope="col">Incentive</th>
							    <th scope="col">Comment</th>
							    <th scope="col">Edit</th>
					          </tr>
					        </thead>
					        <tbody>
				        	
					        <?php } ?>
				        	<?php 
				        	$tot_invested = 0;
				        	$tot_sale = 0;
				        	$tot_earn = 0;
				        	$tot_exp = 0;
				        	$tot_open = 0;
				        	$tot_close = 0;
				        	$tot_reversal = 0;
				        	$tot_cf=0;
							$tot_inc=0;
				        	
				        	if($summary == 0) {
				        	foreach($val as $dt){
				        		$earning = $dt['earnings_logs']['sale'] - ($dt['earnings_logs']['opening'] + $dt['earnings_logs']['invested'] - $dt['earnings_logs']['closing']);
				        		$exp_earn = $objShop->calculateExpectedEarning($dt);
				        		
				        		$tot_invested += $dt['earnings_logs']['invested'];
				        		$tot_sale += $dt['earnings_logs']['sale'];
				        		$tot_earn += $earning;
				        		$tot_exp += $exp_earn;
				        		$tot_open += $dt['earnings_logs']['opening'];
				        		$tot_close += $dt['earnings_logs']['closing'];
				        		$tot_reversal += $dt['earnings_logs']['old_reversal'];
				        		$tot_cf += $earning - $exp_earn;
								$tot_inc += $dt[0]['inc'];
				        	?>
				        		<tr style="<?php if($dt['earnings_logs']['comment']) echo "background:orange;" ?>">
				        			<td><b><?php echo $date;?></b></td>
				        			<td><?php echo $dt['vendors']['company'];?></td>
				        			<td><span id="opening_<?php echo $dt['earnings_logs']['id']; ?>"><?php echo round($dt['earnings_logs']['opening'],2);?></span></td>
				        			<td><span id="closing_<?php echo $dt['earnings_logs']['id']; ?>"><?php echo round($dt['earnings_logs']['closing'],2);?></span></td>
				        			<td><span id="invested_<?php echo $dt['earnings_logs']['id']; ?>"><?php echo round($dt['earnings_logs']['invested'],2);?></span></td>
				        			<td><?php echo round($dt['earnings_logs']['sale'],2);?></td>
				        			<td><?php echo round($exp_earn,2);?></td>
				        			<!--<td><?php //echo $dt['earnings_logs']['old_reversal']; ?></td>-->
				        			<td><?php echo round($earning,2); ?></td>
				        			<td><?php echo round($earning - $exp_earn,2); ?></td>
				        			<td><?php echo round($earning*100/$dt['earnings_logs']['sale'],2). " %"; ?></td>
									<td><?php echo round($dt[0]['inc'],2); ?></td>
                                                                                                                                                                                           <td><textarea id="comment_<?php echo $dt['earnings_logs']['id'] ?>" style="width:200px;height: 70px;resize: none;" disabled ><?php echo $dt['earnings_logs']['comment'] ?></textarea></td>
				        			<?php if($date >= date('Y-m-d',strtotime('-30 days'))){ ?>
				        			<td><span id="edit_<?php echo $dt['earnings_logs']['id']; ?>"><a href="javascript:void(0);" onclick="edit(<?php echo $dt['earnings_logs']['id']; ?>,'',<?php echo $dt['vendors']['update_flag'];?>)">Edit</a></span></td>
				        			<?php } else {?>
				        			<td><span id="edit_<?php echo $dt['earnings_logs']['id']; ?>"></span></td>
				        			<?php } ?>
				        			
				        		</tr>
				        		<tr id="comment_tr_<?php echo $dt['earnings_logs']['id'] ?>" style="display:none">
				        			<td colspan="12" >
				        				<textarea id="comment_<?php echo $dt['earnings_logs']['id'] ?>" style="width:99%" disabled placeholder="<no comment>" ><?php 
				        					echo $dt['earnings_logs']['comment'] 
				        				?></textarea>
				        			</td>
				        		</tr>
				        	<?php } } else if ($summary == 1){
				        		$earning = $val['earning'];
				        		$exp_earn = $val['exp_earn'];
				        		
				        		$tot_invested = $val['invested'];
				        		$tot_sale = $val['sale'];
				        		$tot_open = $val['opening'];
				        		$tot_close = $val['closing'];
				        		$tot_cf = $earning - $exp_earn;
								$tot_inc = $val['inc'];
								$tot_earn = $earning;
								$tot_exp = $exp_earn;
							?>
							
							<tr style="">
				        			<td><b><a href="shops/investmentReport/<?php echo $date;?>/<?php echo $date;?>/0/0" target='_blank'><?php echo $date;?></a></b></td>
				        			<td></td>
				        			<td><?php echo round($tot_open,2);?></td>
				        			<td><?php echo round($tot_close,2);?></td>
				        			<td><?php echo round($tot_invested,2);?></td>
				        			<td><?php echo round($tot_sale,2);?></td>
				        			<td><?php echo round($exp_earn,2);?></td>
				        			<td><?php echo round($earning,2); ?></td>
				        			<td><?php echo round($earning - $exp_earn,2); ?></td>
				        			<td><?php echo round($earning*100/$tot_sale,2). " %"; ?></td>
									<td><?php echo round($tot_inc,2); ?></td>
                                                                                                                                                                                           <td><textarea id="comment_<?php echo $dt['earnings_logs']['id'] ?>" style="width:200px;height: 70px;resize: none;" disabled ><?php echo $dt['earnings_logs']['comment'] ?></textarea></td>
				        			<td></td>
				        			
				        		</tr>
				        		<tr style="display:none">
				        			
				        		</tr>
				        		<?php 
				        	} else if ($summary == 2){
				        		$earning = $val['earning'];
				        		$exp_earn = $val['exp_earn'];
				        		
				        		$tot_invested = $val['invested'];
				        		$tot_sale = $val['sale'];
				        		$tot_open = $val['opening'];
				        		$tot_close = $val['closing'];
				        		$tot_cf = $earning - $exp_earn;
								$tot_inc = $val['inc'];
								$tot_earn = $earning;
								$tot_exp = $exp_earn;
							?>
							
							<tr style="">
				        			<td></td>
				        			<td><b><a href="shops/investmentReport/<?php echo $from;?>/<?php echo $to;?>/<?php echo $date;?>/0" target='_blank'><?php echo $val['company'];?></a></b></td>
				        			<td><?php echo round($tot_open,2);?></td>
				        			<td><?php echo round($tot_close,2);?></td>
				        			<td><?php echo round($tot_invested,2);?></td>
				        			<td><?php echo round($tot_sale,2);?></td>
				        			<td><?php echo round($exp_earn,2);?></td>
				        			<td><?php echo round($earning,2); ?></td>
				        			<td><?php echo round($earning - $exp_earn,2); ?></td>
				        			<td><?php echo round($earning*100/$tot_sale,2). " %"; ?></td>
									<td><?php echo round($tot_inc,2); ?></td>
                                                                                                                                                                                           <td><textarea id="comment_<?php echo $dt['earnings_logs']['id'] ?>" style="width:200px;height: 70px;resize: none;" disabled ><?php echo $dt['earnings_logs']['comment'] ?></textarea></td>
				        			<td></td>
				        			
				        		</tr>
				        		<tr style="display:none">
				        			
				        		</tr>
				        		<?php 
				        	}
				        	$otot_invested += $tot_invested;
				        	$otot_sale += $tot_sale;
				        	$otot_earn += $tot_earn;
				        	$otot_exp += $tot_exp;
				        	$otot_reversal += $tot_reversal; 
				        	$otot_cf += $tot_cf; 
				        	
				        	?>
				        	<?php if ($summary == 0) { ?>
				        	</tbody>
				        	
				        	<tfoot>
				        		<tr>
				        			<td><b>Total</b></td>
				        			<td></td>
				        			<td><b><?php echo round($tot_open,2); ?></b></td>
				        			<td><b><?php echo round($tot_close,2); ?></b></td>
				        			<td><b><?php echo round($tot_invested,2); ?></b></td>
				        			<td><b><?php echo round($tot_sale,2); ?></b></td>
				        			<td><b><?php echo round($tot_exp,2); ?></b></td>
				        			<td><b><?php echo round($tot_earn,2); ?></b></td>
				        			<td><b><?php echo round($tot_cf,2); ?></b></td>
				        			<td><b><?php echo round($tot_earn*100/$tot_sale,2). " %"; ?></b></td>
				        			<td><b><?php echo $tot_inc; ?></td>
				        			<td></td>
				        		</tr>
				        	</tfoot>
				        	</table>
				        	<?php } ?>
						 	
			        	<?php } if ($summary != 0) {?>
			        	</tbody>
			        	</table>
			        	<?php } ?>
			        	<table style="margin-top:10px" width="1000" border="1" summary="Company Rolling">
			        		<thead>
					          <tr class="noAltRow altRow">
					            <th scope="col">Date</th>
					            <th scope="col">Vendor</th>
							    <th scope="col">Opening</th>
							    <th scope="col">Closing</th>
							    <th scope="col">Invested</th>
							    <th scope="col">Sale </th>
							    <th scope="col">Expected Earning </th>
							    <!--<th scope="col">Reversals</th>-->
							    <th scope="col">Earn</th>
							    <th scope="col">Diff</th>
							    <th scope="col">Earn(%)</th>
							    <th scope="col">Edit</th>
					          </tr>
					        </thead>
			        		<tbody>
			        		<tr>
			        			<td><b>Overall</b></td>
			        			<td></td>
			        			<td></td>
			        			<td></td>
			        			<td><b><?php echo round($otot_invested,2); ?></b></td>
			        			<td><b><?php echo round($otot_sale,2); ?></b></td>
			        			<td><b><?php echo round($otot_exp,2); ?></b></td>
			        			<!--<td><b><?php //echo round($otot_reversal,2); ?></b></td>-->
			        			<td><b><?php echo round($otot_earn,2); ?></b></td>
			        			<td><b><?php echo round($otot_cf,2); ?></b></td>
			        			<td><b><?php
                                                        $sl = ($otot_sale==0) ? 0 : round($otot_earn*100/$otot_sale,2);
                                                        echo $sl." %"; ?></b></td>
			        			<td></td>
			        		</tr>
			        		</tbody>
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
function vendorEarningSearch(){
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
	var summ_type = $('summ_type').options[$('summ_type').selectedIndex].value;
    var vendor = $('vendor').options[$('vendor').selectedIndex].value;
	document.location.href="/shops/investmentReport/"+$('fromDate').value+"/"+$('toDate').value+"/"+vendor+"/"+summ_type;
	
}

function edit(id,val,flag){
	var invested = $('invested_'+id).innerHTML;
	var opening = $('opening_'+id).innerHTML;
	var closing = $('closing_'+id).innerHTML;
	
	var text = ''; 
	if(flag == 1) var text = "readonly='readonly'";
	
	$('comment_' + id).enable();
	if(val == ''){
		$('invested_'+id).innerHTML="<input type='text' size='6'"+text+" id='input_invested_"+id+"' value='"+invested+"'>";
		$('opening_'+id).innerHTML="<input type='text' size='6' id='input_opening_"+id+"' value='"+opening+"'>";
		$('closing_'+id).innerHTML="<input type='text' size='6' id='input_closing_"+id+"' value='"+closing+"'>";
	}
	else {
		$('invested_'+id).innerHTML="<input type='text' readonly='readonly' size='6' id='input_invested_"+id+"' value='"+invested+"'>";
		$('opening_'+id).innerHTML="<input type='text' readonly='readonly' size='6' id='input_opening_"+id+"' value='"+opening+"'>";
		$('closing_'+id).innerHTML="<input type='text' readonly='readonly' size='6' id='input_closing_"+id+"' value='"+closing+"'>";
	}
	$('edit_'+id).innerHTML="<a href='javascript:void(0)' onclick='editDetails("+id+")'>Submit</a>";
}

function toggleCommentBox(id)
{
    $('comment_' + id).enable();
    $('edit_'+id).innerHTML="<a href='javascript:void(0)' onclick='editDetails("+id+")'>Submit</a>";
}

function editDetails(id){
	var invested = $("input_invested_"+id).value;
	var opening = $("input_opening_"+id).value;
	var closing = $("input_closing_"+id).value;
	var edit_html = "<a href='javascript:void(0)' onclick='toggleCommentBox("+id+")'>Edit</a>";
	var r=confirm("Confirm?");
	var comment = $("comment_" + id).value;
	if(r==true){
                                                     $('comment_' + id).disable();
		$('edit_'+id).innerHTML='Submitting';
	
		var url = '/shops/addInvestedAmount';
			var params = {'id' : id,'amount':invested,'opening':opening,'closing':closing, 'comment':comment};
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
			onSuccess:function(transport)
					{		
						var html = transport.responseText;
						if(html == 'done'){
							$('invested_'+id).innerHTML = invested;
							$('opening_'+id).innerHTML = opening;
							$('closing_'+id).innerHTML = closing;
							$('edit_'+id).innerHTML='done';
						}
						else {
							$('edit_'+id).innerHTML=edit_html;
							alert(html);
						}
					}
			});
	}
}

function showCommentBox(earning_log_id){
	$('comment_tr_' + earning_log_id).toggle();
}

function add(){
	var url = '/shops/addInvestmentEntry';
	var vendor_id = $('add_vendor').value;
	var date = $('add_date').value;
	var params = "vendor_id=" + vendor_id + "&date=" + date;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport){
				var html = transport.responseText.trim();
				if(html == 'done'){
					 location.reload(); 
				}
				else {
					alert("Cannot add entry");
				}
			}
		});
}
</script>