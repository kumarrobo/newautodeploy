<?php $total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0;?>
<table border="0" cellpadding="0" cellspacing="0" summary="List Retailers" width="1100px" align="center">

	<tr>
		<td valign="top" width="75%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
				<caption class="header">
				<?php echo strtoupper($name); ?> Retailers
				</caption>
				<tr align="center">
					<th width="5%">Sr. No.</th>
  					<th width="12%">Retailer Code</th>
  					<th width="10%">Name</th>
  					<th width="10%">Possible No</th>
  					<th width="10%">Mobile</th>
  					<th width="8%">Sale Till Date</th>
  					<th width="8%">Sale in Scheme</th>
  					<th width="8%">Sale this week</th>
  					<th width="8%">Sale today</th>
  					<th width="11%">Follow up date</th>
  				</tr>
		  				
			<?php $i = 0;foreach($retailers as $retailer) { $i++;
			$total1 = $total1 + $retailer['0']['totalSaleTill'];
			$total2 = $total2 + (int)$retailer['vendors_retailers']['totalSale'];
			$total3 = $total3 + (int)$retailer['vendors_retailers']['weeklySale'];
			$total4 = $total4 + $retailer['0']['saleToday'];
			?>
				<tr align="center"> 
					<td><?php echo $i; ?></td>
					<td><?php echo $retailer['vendors_retailers']['retailer_code']; ?></td>
					<td><?php if(!empty($retailer['vendors_retailers']['name']))echo $retailer['vendors_retailers']['name']; ?></td>
					<td><?php if(empty($retailer['vendors_retailers']['user_id'])) { ?><a href="/groups/userInfo/<?php echo $retailer['vendors_retailers']['possNo']; ?>/<?php echo $retailer['vendors_retailers']['retailer_code']; ?>"><?php echo $retailer['vendors_retailers']['possNo']; ?></a><?php } ?></td>
					<td><a href="/groups/userInfo/<?php echo $retailer['vendors_retailers']['mobile']; ?>/<?php echo $retailer['vendors_retailers']['retailer_code']; ?>"><?php echo $retailer['vendors_retailers']['mobile'];?></a></td>
					<td><?php echo $retailer['0']['totalSaleTill']; ?></td>
					<td><?php echo (int)$retailer['vendors_retailers']['totalSale']; ?></td>
					<td><?php echo (int)$retailer['vendors_retailers']['weeklySale']; ?></td>
					<td><?php echo $retailer['0']['saleToday']; ?></td>
					<td><?php if($retailer['vendors_retailers']['followup'])echo date('j M, y',strtotime($retailer['vendors_retailers']['followup'])); ?></td>
				</tr>
			<?php }?>
			<tr align="center"> 
				<td><b>Total</b></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><b><?php echo $total1; ?></b></td>
				<td><b><?php echo $total2; ?></b></td>
				<td><b><?php echo $total3; ?></b></td>
				<td><b><?php echo $total4; ?></b></td>
			</tr>
			</table>
		</td>
		<td valign="top" width="35%" style="padding-left:10px">
			<table border="0" cellpadding="0" cellspacing="0" summary="List Retailers" width="100%" align="center">
				<tr>
					<td>
						<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
						<caption class="header">
						Overall Sale
						</caption>
						<?php $num = 5; $total_today = 0; $total = 0;for($i = 0; $i < ceil(count($vendors_data)/$num); $i++) {?>
						<tr>
							<td width="20%">
								<table border="1" width="100%">
									<tr style="height:40px"><td>Product Name</td></tr>
									<tr style="height:40px"><td>Sold Today</td></tr>
									<tr style="height:40px"><td>Total Sold</td></tr>
								</table>
							</td>
							<?php for($j = 0; $j < $num; $j++) { $index = $j + $i*$num; $total_today += $vendors_data[$index]['0']['soldToday']; $total += $vendors_data[$index]['0']['soldTotal'];
							?>
							<td>
								<table border="1" width="100%">
									<tr style="height:40px"><td><b><?php echo $vendors_data[$index]['Product']['name']; ?></b></td></tr>
									<tr style="height:40px"><td><?php echo $vendors_data[$index]['0']['soldToday']; ?></td></tr>
									<tr style="height:40px"><td><?php echo $vendors_data[$index]['0']['soldTotal']; ?></td></tr>
								</table>
							</td>
							<?php } ?>
						</tr>
						<?php }?>
					</table>
					<table width="100%">
						<tr><td width="30%">Total Sold Today:</td><td><?php echo $total_today;?></td></tr>
						<tr><td width="30%">Total Sold Till Now:</td><td><?php echo $total;?></td></tr>
					</table>
					</td>
				</tr>
				
				<tr>
					<td>
					
						<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left" style="margin-top:20px;">
							<caption class="header">
							Retail Winners
							</caption>
							<tr align="left">
								<th width="20%">Retailer Code</th>
					  			<th width="20%">Name</th>
					  			<th width="20%">Type</th>
					  			<th width="20%">Date</th>
					  		</tr>
						<?php foreach($winners as $winner) { ?>
							<tr align="left">
								<td><?php echo $winner['retailers_winners']['retailer_code']; ?></td>
								<td><?php if(!empty($winner['vendors_retailers']['name']))echo $winner['vendors_retailers']['name']; ?></td>
								<td><?php echo strtoupper($winner['retailers_winners']['type']); ?></td>
								<td><?php echo date('Y-m-d', strtotime($winner['retailers_winners']['timestamp'])); ?></td>
							</tr>
						<?php }?>
							
						</table>
					</td>
				</tr>
				</table>			
		</td>
	</tr>	
</table>