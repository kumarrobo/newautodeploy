<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'main'));?>
    		<div id="innerDiv">

	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR  || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER){?>
<!--	  			<div>
    			<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">

    			<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="refunddata();"></span>
    			</div>-->
					<div>
	  				<span style="font-weight:bold;margin-right:10px;">Select <?php echo $modelName;?>: </span>
					<select id="shop">
               		<option value="0">Select</option>
					<?php
					 foreach($records as $distributor) {?>
						<option value="<?php echo $distributor[$modelName]['id'];?>" <?php if(isset($id) && $id == $distributor[$modelName]['id']) echo "selected";?>><?php echo $distributor[$modelName]['company']; ?></option>
					<?php } ?>
					</select>
					<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findDistributor();"></span>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select <?php echo $modelName;?></span></div>
				<?php } ?>

					<table style="margin-top:20px;" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">

                    <?php if(empty($data_today)) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Results Found !!</span></td>
                    </tr>

                    <?php } else { ?>
                    <tr align="center"><td>Welcome <?php echo $name;?> !!</td></tr>
					<?php if( ($earnings) && ( count($earnings) > 0 ) ){?>
						<tr>
							<td>
								<center>
									<table style="border-collapse: collapse;border: 1px solid black;">
										<thead>
											<th style="border: 1px solid black;color:#0167A9;">Product wise earnings</th>
											<th style="border: 1px solid black;color:#0167A9;">Today</th>
											<th style="border: 1px solid black;color:#0167A9;">Yesterday</th>
											<th style="border: 1px solid black;color:#0167A9;">Last 7 days</th>
											<th style="border: 1px solid black;color:#0167A9;">Last 30 days</th>
										</thead>
										<tbody>
											<?php foreach( $earnings as $service_id => $earnings ){

												echo '<tr>
														<td style="border: 1px solid black;"><b>'. $services[$service_id].'</b></td>';
												if($service_id == 'additional-incentives'){
													$services[$service_id] = $service_id;
												}
												echo   '<td style="border: 1px solid black;"><a target="_blank" href="distEarningReport?service='.$services[$service_id].'&from='.date('d-m-Y').'&to='.date('d-m-Y').'">'.$earnings['today'].'</a></td>
														<td style="border: 1px solid black;"><a target="_blank" href="distEarningReport?service='.$services[$service_id].'&from='.date('d-m-Y', strtotime('-1 day')).'&to='.date('d-m-Y', strtotime('-1 day')).'">'.$earnings['yesterday'].'</a></td>
														<td style="border: 1px solid black;"><a target="_blank" href="distEarningReport?service='.$services[$service_id].'&from='.date('d-m-Y', strtotime('-6 days')).'&to='.date('d-m-Y').'">'.$earnings['last_7_days'].'</a></td>';
												echo	'<td style="border: 1px solid black;">'.$earnings['last_30_days'].'</td>
													</tr>';
											} ?>
										</tbody>
									</table>
								</center>
							</td>
						</tr>
					<?php } ?>
                    <tr align="center">
                    	<table width="100%" cellspacing="0" cellpadding="0" border="1" class="ListTable" summary="Transactions">
                    		<tr>
                    			<td></td>
                    			<td><b>Today</b></td>
                    			<td><b>Yesterday</b></td>
                    			<td><b>Last 7 days</b></td>
                    			<td><b>Last 30 days</b></td>
                    			<?php if($_SESSION['Auth']['User']['group_id'] != ADMIN){ ?>
                    			<td></td>
                    			<?php } ?>
                    		</tr>
							<tr>
                    			<td><b>Topup buy/day</b></td>
                    			<td><?php echo isset($data_today['buy'])?$data_today['buy']:0;?></td>
                    			<td><?php echo isset($data_before['yesterday']['buy']) ? $data_before['yesterday']['buy'] : 0 ;?></td>
                    			<td><?php echo isset($data_before['week']['buy']) ? $data_before['week']['buy'] : 0; ?></td>
                    			<td><?php echo $data_before['month']['buy']; ?></td>

								<td>&nbsp;</td>

                    		</tr>
                    		<tr>
                    			<td><b>Topup sold/day</b></td>
                    			<td><?php echo isset($data_today['sold'])?$data_today['sold']:0;?></td>
                    			<td><?php echo isset($data_before['yesterday']['sold']) ? $data_before['yesterday']['sold'] : 0 ;?></td>
                    			<td><?php echo isset($data_before['week']['sold']) ? $data_before['week']['sold'] : 0; ?></td>
                    			<td><?php echo $data_before['month']['sold']; ?></td>
                    			<?php if($_SESSION['Auth']['User']['group_id'] != ADMIN){ ?>
                    			<td><a target="Graphs" href="<?php echo isset($dist) ? '/shops/graphRetailer/?type=d&id='.$dist : "/shops/graphRetailer/?type=d";?>">View graph</a></td>
                    			<?php } ?>
                    		</tr>
                    		<tr>
                    			<td><b>% of transacting Retailers</b></td>
                    			<td><?php echo $data_today['percent_trans'] ."% (".$data_today['transacting']."/".$data_today['retailers'] . ")";?></td>
                    			<td><?php echo isset( $data_before['yesterday']['percent_trans'] ) ? $data_before['yesterday']['percent_trans'] ."% (".$data_before['yesterday']['transacting']."/".$data_before['yesterday']['retailers'] . ")" : 0;?></td>
                    			<td><?php echo $data_before['week']['percent_trans']."%"; ?></td>
                    			<td><?php echo $data_before['month']['percent_trans']."%"; ?></td>
                    			<?php if($_SESSION['Auth']['User']['group_id'] != ADMIN){ ?>
                    			<td><a target="Graphs" href="<?php echo isset($dist) ? "/shops/graphRetailer/?type=d&id=".$dist : "#";?>">View graph</a></td>
                    			<?php } ?>
                    		</tr>
                    		<tr>
                    			<td><b>New outlets opened</b></td>
                    			<td><?php echo isset($data_today['new']) ? $data_today['new'] :0;?></td>
                    			<td><?php echo isset($data_before['yesterday']['new']) ? $data_before['yesterday']['new'] : 0;?></td>
                    			<td><?php echo $data_before['week']['new']; ?></td>
                    			<td><?php echo $data_before['month']['new']; ?></td>
                    			<?php if($_SESSION['Auth']['User']['group_id'] != ADMIN){ ?>
                    			<td><a target="Graphs" href="<?php echo isset($dist) ? "/shops/graphRetailer/?type=d&id=".$dist : "#";?>">View graph</a></td>
                    			<?php } ?>
                    		</tr>
                    		<tr>
                    			<td><b>Unique topups/day</b></td>
                    			<td><?php echo $data_today['unique'];?></td>
                    			<td><?php echo isset( $data_before['yesterday']['unique'] ) ? $data_before['yesterday']['unique'] :0;?></td>
                    			<td><?php echo $data_before['week']['unique']; ?></td>
                    			<td><?php echo $data_before['month']['unique']; ?></td>
                    			<?php if($_SESSION['Auth']['User']['group_id'] != ADMIN){ ?>
                    			<td><a target="Graphs" href="<?php echo isset($dist) ?"/shops/graphRetailer/?type=d&id=".$dist : "#";?>">View graph</a></td>
                    			<?php } ?>
                    		</tr>
                    		<tr>
                    			<td><b>Retailer Sale/day</b></td>
                    			<td><?php echo $data_today['sale_avg'];?></td>
                    			<td><?php echo $data_before['yesterday']['sale_avg'];?></td>
                    			<td><?php echo $data_before['week']['sale_avg']; ?></td>
                    			<td><?php echo $data_before['month']['sale_avg']; ?></td>
                    			<?php if($_SESSION['Auth']['User']['group_id'] != ADMIN){ ?>
                    			<td><a target="Graphs" href="<?php echo isset($dist) ?"/shops/graphRetailer/?type=d&id=".$dist : "#";?>">View graph</a></td>
                    			<?php } ?>
                    		</tr>
                    		<tr>
                    			<td><b>Avg Sale/Retailer</b></td>
                    			<td><?php echo $data_today['sale_avg_ret'];?></td>
                    			<td><?php echo isset( $data_before['yesterday']['sale_avg_ret']) ? $data_before['yesterday']['sale_avg_ret'] : 0;?></td>
                    			<td><?php echo $data_before['week']['sale_avg_ret']; ?></td>
                    			<td><?php echo $data_before['month']['sale_avg_ret']; ?></td>
                    			<?php if($_SESSION['Auth']['User']['group_id'] != ADMIN){ ?>
                    			<td><a target="Graphs" href="<?php echo isset($dist) ?"/shops/graphRetailer/?type=d&id=".$dist : "#";?>">View graph</a></td>
                    			<?php } ?>
                    		</tr>
							<?php if( ($earnings) && ( count($earnings) > 0 ) ){?>
								<tr>
									<td><b>Earnings/day</b></td>
									<td><?php echo round($total_today_earning/30,2);?></td>
									<td><?php echo round($total_yesterday_earning/30,2);?></td>
									<td><?php echo round($total_last_7_days_earning/30,2); ?></td>
									<td><?php echo round($total_last_30_days_earning/30,2); ?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td><b>Earnings/Retailer</b></td>
									<td><?php echo ( $retailer_count > 0 ) ? round($total_today_earning/$retailer_count,2) : $total_today_earning;?></td>
									<td><?php echo ( $retailer_count > 0 ) ? round($total_yesterday_earning/$retailer_count,2) : $total_yesterday_earning;?></td>
									<td><?php echo ( $retailer_count > 0 ) ? round($total_last_7_days_earning/$retailer_count,2) : $total_last_7_days_earning; ?></td>
									<td><?php echo ( $retailer_count > 0 ) ? round($total_last_30_days_earning/$retailer_count,2) : $total_last_30_days_earning; ?></td>
									<td>&nbsp;</td>
								</tr>
							<?php } ?>
                    	</table>
                    </tr>
                    <?php } ?>
			   	</table>
				          <?php
							echo $this->GChart->start('test1');
							echo $this->GChart->visualize('test1', $data1);
							?>
				  <span style="align:center;padding: 100px;"><a href="/shops/graphMainReport/<?php echo isset($id) ? $id : 0; ?>/<?php echo date('dmY',strtotime('-30 days')). '-' .date('dmY'); ?>">Click here to See Previous date records</a></span>
				          <?php
							echo $this->GChart->start('test2');
							echo $this->GChart->visualize('test2', $data2);
							?>
				  <span style="align:center;padding: 100px;"><a href="/shops/graphMainReport/<?php echo isset($id) ? $id : 0; ?>/<?php echo date('dmY',strtotime('-30 days')). '-' .date('dmY'); ?>">Click here to See Previous date records</a></span>
                            <?php
							echo $this->GChart->start('test3');
							echo $this->GChart->visualize('test3', $data3);
							?>
				  <span style="align:center;padding: 100px;"><a href="/shops/graphMainReport/<?php echo isset($id) ? $id : 0; ?>/<?php echo date('dmY',strtotime('-30 days')). '-' .date('dmY'); ?>">Click here to See Previous date records</a></span>
                          <?php
							echo $this->GChart->start('test4');
							echo $this->GChart->visualize('test4', $data4);
							?>
				  <span style="align:center;padding: 100px;"><a href="/shops/graphMainReport/<?php echo isset($id) ? $id : 0; ?>/<?php echo date('dmY',strtotime('-30 days')). '-' .date('dmY'); ?>">Click here to See Previous date records</a></span>


						 <?php
							echo $this->GChart->start('test5');
							echo $this->GChart->visualize('test5', $data5);
							?>
				  <span style="align:center;padding: 100px;"><a href="/shops/graphMainReport/<?php echo isset($id) ? $id : 0; ?>/<?php echo date('dmY',strtotime('-30 days')). '-' .date('dmY'); ?>">Click here to See Previous date records</a></span>
						 <?php
							echo $this->GChart->start('test6');
							echo $this->GChart->visualize('test6', $data6);
							?>
				  <span style="align:center;padding: 100px;"><a href="/shops/graphMainReport/<?php echo isset($id) ? $id : 0; ?>/<?php echo date('dmY',strtotime('-30 days')). '-' .date('dmY'); ?>">Click here to See Previous date records</a></span>

			</fieldset>
   			</div>
   			<br class="clearLeft" />
 		</div>

    </div>
 </div>
<br class="clearRight" />
</div>
<script>
function findDistributor(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
	var salesman = $('shop').options[$('shop').selectedIndex].value;

	if(salesman == 0){
		window.location.href = "/shops/mainReport/0";
	}
	else {
		$('date_err').hide();
		window.location.href = "/shops/mainReport/"+salesman;
	}
}
</script>