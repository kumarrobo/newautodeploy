<style>
.noMore td, .noMore td a { color:#ddd !important; }
.visit { color:#00ffff; }
.uVisit { color:#ff00ff; }
</style>
<table border="0" cellpadding="0" cellspacing="0" summary="List Retailers" width="100%" align="center">

	<tr>
		<td valign="top" width="60%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
				<caption class="header">
				Retailers
				</caption>
				<tr align="left">
		  			<th width="10%">Name</th>
		  			<th width="10%">Total</th>
		  			<th width="10%">Sold</th>
		  			<th width="5%">Sold Today (<?php echo date('Y-m-d');?>)</th>
		  			<th width="10%">Salesman</th>
		  			<th width="10%">City</th>
		  			<th width="15%">Address</th>
		  		</tr>
			<?php foreach($retailers as $retailer) { ?>
				<tr align="left" <?php if($retailer['Retailer']['toshow'] == 0) echo "class=noMore"; ?>>
				<?php 
					if($retailer['Retailer']['lastVisited'])
					{					 
					 $diff = floor(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($retailer['Retailer']['lastVisited'])) / (60*60*24));
					 
					 }  
				?>
					<td ><a href="retailers/index/<?php echo $retailer['Retailer']['id']; ?>" <?php if($retailer['Retailer']['lastVisited']) { if($diff > 14) echo " style='color:#f00'"; else if($diff > 10) echo " style='color:#0f0'"; } ?>><?php echo $retailer['Retailer']['name'] . " - " . $diff . " Days" ; ?></a></br></td>
					<td><?php echo $retailer['Coupon']['total']; ?></td>
					<td><?php echo $retailer['Coupon']['sold']; ?></td>
					<td><?php echo $retailer['Coupon']['soldToday']; ?></td>
					<td><a href="/retailers/salesman/<?php echo $retailer['Salesman']['id']; ?>"><?php echo $retailer['Salesman']['name'] . " - " .$retailer['Salesman']['area']; ?></a>
					<td><?php echo $retailer['Retailer']['city']; ?></td>
					<td><?php echo $retailer['Retailer']['shopname'] . "<br/>" . $retailer['Retailer']['address']; ?></td>
				</tr>
			<?php }?>
				
			</table>
		</td>
		<td valign="top" width="40%" style="padding-left:20px">
			<table border="0" cellpadding="0" cellspacing="0" summary="List Retailers" width="100%" align="center">
				<tr>
					<td>
					
						<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left" style="margin-top:20px;">
							<caption class="header">
							Salesmans
							</caption>
							<tr align="left">
					  			<th width="20%">Name</th>
					  			<th width="10%">Mobile</th>
					  			<th width="10%">Total Retailers</th>
					  			<th width="10%">Total Collection</th>
					  			<th width="10%">Cards Sold</th>
					  		</tr>
						<?php foreach($salesmans as $salesman) { ?>
							<tr align="left">
								<td><a href="/retailers/salesman/<?php echo $salesman['Salesman']['id'];?>"><?php echo $salesman['Salesman']['name'] . " - " . $salesman['Salesman']['area']; ?></a></td>
								<td><?php echo $salesman['Salesman']['mobile']; ?></td>
								<td><?php echo $salesman['0']['num']; ?></td>
								<td><?php echo $salesman['0']['amount']; ?></td>
								<td><?php echo $salesman['0']['sold']; ?></td>
								
							</tr>
						<?php }?>
							
						</table>
					</td>
				</tr>
				
				<tr>
					<td>
					
						<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left" style="margin-top:20px;">
							<caption class="header">
							Master Distributors
							</caption>
							<tr align="left">
					  			<th width="20%">Name</th>
					  			<th width="20%">Mobile</th>
					  			<th width="20%">Balance</th>
					  		</tr>
						<?php foreach($master_distributor as $master) { ?>
							<tr align="left">
								<td><a href="/retailers/masterDistributor/<?php echo $master['master_distributor']['id'];?>"><?php echo $master['master_distributor']['name']; ?></a></td>
								<td><?php echo $master['users']['mobile']; ?></td>
								<td><?php echo $master['master_distributor']['balance']; ?></td>
							</tr>
						<?php }?>
							
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="100%" align="left" style="margin-top:20px;">
						<caption class="header">
						Add New Retailer
						</caption>
						<tr><td>
							<div id="retailBox" >
								<?php echo $this->element('retailer_form');?>
							</div>
							
						</td></tr>
					</table>
					</td>
				</tr>
			</table>			
		</td>
	</tr>	
</table>