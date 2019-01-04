<?php $num = 5; $soldToday = array(); ?>
<table border="0" cellpadding="0" cellspacing="0" summary="List Retailers" width="100%" align="center">

	<tr>
		<td valign="top" width="50%">
			<table>
				<tr>
					<td>
					<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
						<caption class="header">
						SMSTadka Own Channel
						<?php $total_today = 0; $total = 0;for($i = 0; $i < ceil(count($smstadka)/$num); $i++) {?>
						<tr>
							<td width="20%">
								<table border="1" width="100%">
									<tr><td>Product Name</td></tr>
									<tr><td>Sold Today</td></tr>
									<tr><td>Total Sold</td></tr>
								</table>
							</td>
							<?php for($j = 0; $j < $num; $j++) { $index = $j + $i*$num; $total_today += $smstadka[$index]['0']['soldToday']; $total += $smstadka[$index]['0']['soldTotal'];
							$soldToday[$smstadka[$index]['Product']['id']] += $smstadka[$index]['0']['soldToday'];
							?>
							<td>
								<table border="1" width="100%">
									<tr style="height:40px"><td><b><?php echo $smstadka[$index]['Product']['name']; ?></b></td></tr>
									<tr><td><?php echo $smstadka[$index]['0']['soldToday']; ?></td></tr>
									<tr><td><?php echo $smstadka[$index]['0']['soldTotal']; ?></td></tr>
								</table>
							</td>
							<?php } ?>
						</tr>
						<?php }?>
					</table>
					<table width="100%" align="left">
						<tr><td width="30%">Total Sold Today:</td><td><?php echo $total_today;?></td></tr>
						<tr><td width="30%">Total Sold Till Now:</td><td><?php echo $total;?></td></tr>
					</table>
					</td>
				</tr>
				
				<tr>
					<td>
					<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
						<caption class="header">
						Playwin Cards
						</caption>
						<?php $total_today_cards = 0; $total_today_panel = 0; $total = 0; $i = 0; 
						foreach(explode(",",PLAYWIN_PKGS) as $product) {
							$total_today_cards += $playwin_data[$product]['cards_data']['0']['soldToday'];
							$total_today_panel += $playwin_data[$product]['online_data']['0']['soldToday'];
							$total += $playwin_data[$product]['cards_data']['0']['soldTotal'] + $playwin_data[$product]['online_data']['0']['soldTotal'];
						?>
						<?php if($i%$num == 0) {
								if($i != 0) echo "</tr>";
						?>
						<tr>
							<td width="20%">
								<table border="1" width="100%">
									<tr style="height:40px"><td>Product Name</td></tr>
									<tr style="height:40px"><td>Sold Today(Cards)</td></tr>
									<tr style="height:40px"><td>Sold Today(Panel)</td></tr>
									<tr style="height:40px"><td>Total Sold</td></tr>
								</table>
							</td>
						<?php } ?>	
						<?php 
							$soldToday[$product] += $playwin_data[$product]['cards_data']['0']['soldToday'] + $playwin_data[$product]['online_data']['0']['soldToday'];
						?>
						<td>
							<table border="1" width="100%">
								<tr style="height:40px"><td><b><?php echo $playwin_data[$product]['name']; ?></b></td></tr>
								<tr style="height:40px"><td><?php echo $playwin_data[$product]['cards_data']['0']['soldToday']; ?></td></tr>
								<tr style="height:40px"><td><?php echo $playwin_data[$product]['online_data']['0']['soldToday']; ?></td></tr>
								<tr style="height:40px"><td><?php echo ($playwin_data[$product]['cards_data']['0']['soldTotal'] + $playwin_data[$product]['online_data']['0']['soldTotal']); ?></td></tr>
							</table>
						</td>
						
						<?php $i++; } ?>
						</tr>
					</table>
					<table width="100%">
						<tr><td width="30%">Total Sold Today (Cards):</td><td><?php echo $total_today_cards;?></td></tr>
						<tr><td width="30%">Total Sold Today (Panel):</td><td><?php echo $total_today_panel;?></td></tr>
						<tr><td width="30%">Total Sold Till Now:</td><td><?php echo $total;?></td></tr>
					</table>
					</td>
				</tr>
				<?php foreach($s_dist_data as $s_dist => $data) { ?>
				<tr>
					<td>
					<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
						<caption class="header">
						Master Distributor (<?php echo $data['name']; ?>)
						</caption>
						<?php $total_today_cards = 0; $total_today_panel = 0; $total = 0; $i = 0; 
						foreach($products as $product) {
							if(!in_array($product['Product']['id'],explode(",",PLAYWIN_PKGS))){
							$total_today_cards += $data[$product['Product']['id']]['cards_data']['0']['soldToday'];
							$total_today_panel += $data[$product['Product']['id']]['online_data']['0']['soldToday'];
							$total += $data[$product['Product']['id']]['cards_data']['0']['soldTotal'] + $data[$product['Product']['id']]['online_data']['0']['soldTotal'];
						?>
						<?php if($i%$num == 0) {
								if($i != 0) echo "</tr>";
						?>
						<tr>
							<td width="20%">
								<table border="1" width="100%">
									<tr style="height:40px"><td>Product Name</td></tr>
									<tr style="height:40px"><td>Sold Today(Cards)</td></tr>
									<tr style="height:40px"><td>Sold Today(Panel)</td></tr>
									<tr style="height:40px"><td>Total Sold</td></tr>
								</table>
							</td>
						<?php } ?>	
						<?php 
							$soldToday[$product['Product']['id']] += $data[$product['Product']['id']]['cards_data']['0']['soldToday'] + $data[$product['Product']['id']]['online_data']['0']['soldToday'];
						?>
						<td>
							<table border="1" width="100%">
								<tr style="height:40px"><td><b><?php echo $product['Product']['name']; ?></b></td></tr>
								<tr style="height:40px"><td><?php echo $data[$product['Product']['id']]['cards_data']['0']['soldToday']; ?></td></tr>
								<tr style="height:40px"><td><?php echo $data[$product['Product']['id']]['online_data']['0']['soldToday']; ?></td></tr>
								<tr style="height:40px"><td><?php echo ($data[$product['Product']['id']]['cards_data']['0']['soldTotal'] + $data[$product['Product']['id']]['online_data']['0']['soldTotal']); ?></td></tr>
							</table>
						</td>
						
						<?php $i++; }} ?>
						</tr>
					</table>
					<table width="100%">
						<tr><td width="30%">Total Sold Today (Cards):</td><td><?php echo $total_today_cards;?></td></tr>
						<tr><td width="30%">Total Sold Today (Panel):</td><td><?php echo $total_today_panel;?></td></tr>
						<tr><td width="30%">Total Sold Till Now:</td><td><?php echo $total;?></td></tr>
					</table>
					</td>
				</tr>
				<?php } ?>
				<?php foreach($vendors_data as $vendor_id => $data) { ?>
				<tr>
					<td>
					<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
						<caption class="header">
						<?php echo $data['company']; ?>
						</caption>
						<?php $total_today = 0; $total = 0;for($i = 0; $i < ceil(count($data['data'])/$num); $i++) {?>
						<tr>
							<td width="20%">
								<table border="1" width="100%">
									<tr style="height:40px"><td>Product Name</td></tr>
									<tr style="height:40px"><td>Sold Today</td></tr>
									<tr style="height:40px"><td>Total Sold</td></tr>
								</table>
							</td>
							<?php for($j = 0; $j < $num; $j++) { $index = $j + $i*$num; $total_today += $data['data'][$index]['0']['soldToday']; $total += $data['data'][$index]['0']['soldTotal'];
							$soldToday[$data['data'][$index]['Product']['id']] += $data['data'][$index]['0']['soldToday'];
							?>
							<td>
								<table border="1" width="100%">
									<tr style="height:40px"><td><b><?php echo $data['data'][$index]['Product']['name']; ?></b></td></tr>
									<tr style="height:40px"><td><?php echo $data['data'][$index]['0']['soldToday']; ?></td></tr>
									<tr style="height:40px"><td><?php echo $data['data'][$index]['0']['soldTotal']; ?></td></tr>
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
				<?php } ?>
			</table>
		</td>
		<td valign="top" width="50%" style="padding-left:20px">
			<table border="0" cellpadding="0" cellspacing="0" summary="List Retailers" width="100%" align="center">
				<tr>
					<td>
					
						<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
							<caption class="header">
							Overall Sale
							</caption>
							<tr align="left">
					  			<th width="20%">Name</th>
					  			<th width="10%">Total Sold</th>
					  			<th width="10%">Cards Sold</th>
					  			<th width="10%">Panel Activation</th>
					  			<th width="10%">API Activation</th>
					  			<th width="10%">Total Trials</th>
					  			<th width="10%">Sold Today (<?php echo $date;?>)</th>
					  		</tr>
						<?php $c1=0; $c2=0; $c3=0; $c4 = 0; $c5 = 0; $c6 = 0; $c7 = 0;foreach($products as $product) { 
							$c1 = $c1 +  $product['Product']['sold'];
							$c2 = $c2 + $product['Product']['trials'];
							$c3 = $c3 + $soldToday[$product['Product']['id']];
							$c4 = $c4 + $product['Product']['trialsToday'];
							$cards = $product['Product']['sold'] - ($product['Product']['panelSold'] + $product['Product']['vendorSold']);
							$c5 = $c5 + $cards;
							$c6 = $c6 + $product['Product']['panelSold'];
							$c7 = $c7 + $product['Product']['vendorSold'];
						?>
							<tr align="left">
								<td><a href="/retailers/product/<?php echo $product['Product']['id'];?>"><?php echo $product['Product']['name']; ?></a></td>
								<td><?php echo $product['Product']['sold']; ?></td>
								<td><?php echo $cards; ?></td>
								<td><?php echo $product['Product']['panelSold']; ?></td>
								<td><?php echo $product['Product']['vendorSold']; ?></td>
								<td><?php echo $product['Product']['trials']; ?></td>
								<td><?php echo $soldToday[$product['Product']['id']]; ?></td>
							</tr>
						<?php }?>
							<tr align="left">
					  			<th width="20%">Total</th>
					  			<th width="10%"><?php echo $c1; ?></th>
					  			<th width="10%"><?php echo $c5; ?></th>
					  			<th width="10%"><?php echo $c6; ?></th>
					  			<th width="10%"><?php echo $c7; ?></th>
					  			<th width="10%"><?php echo $c2; ?></th>
					  			<th width="10%"><?php echo $c3; ?></th>
					  		</tr>
							
						</table>
					</td>
				</tr>
			</table>			
		</td>
	</tr>	
</table>