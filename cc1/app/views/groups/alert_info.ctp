<table border="1" cellpadding="0" cellspacing="0" summary="Alerts" width="50%" align="center">
	<caption class="header">
	<b><?php echo $app_info['SMSApp']['name']; ?></b>
	</caption>
</table>

<?php if($app_info['SMSApp']['controller_name'] == 'pnr') {?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
	<tr>
		<td valign="top" width="40%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Active PNR Alerts (<?php echo count($active_data); ?>)
			</caption>
			<tr align="left">
	  			<th width="10%">Mobile</th>
	  			<th width="10%">PNR Number</th>
	  			<th width="10%">Journey Date</th>
	  			<th width="10%">Start</th>
	  		</tr>
	  	       
			<?php foreach($active_data as $user){ ?>
			<tr align="left">
			<td> <a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a></td>
			<td> <?php echo $user['Pnr']['pnr_number'];?></td>
			<td> <?php echo $user['Pnr']['journey_date'];?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['Pnr']['start']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="60%" style="padding-left:50px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Inactive PNR Alerts (<?php echo count($inactive_data); ?>)
			</caption>
			<tr align="left">
	  			<th width="10%">Mobile</th>
	  			<th width="10%">PNR Number</th>
	  			<th width="10%">Journey Date</th>
	  			<th width="10%">Start</th>
	  			<th width="10%">End</th>
	  			<th width="10%">Status</th>
	  		</tr>
	  	       
			<?php foreach($inactive_data as $user){ ?>
			<tr align="left">
			<td> <a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a></td>
			<td> <?php echo $user['Pnr']['pnr_number'];?></td>
			<td> <?php echo $user['Pnr']['journey_date'];?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['Pnr']['start']));?></td>
			<td> <?php if(!empty($user['Pnr']['end']))echo date('Y-m-d', strtotime($user['Pnr']['end']));?></td>
			<td> <?php echo ucwords(strtolower($user['Pnr']['chart_status']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>

</table>

<?php } ?>

<?php if($app_info['SMSApp']['controller_name'] == 'stock') {?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
	<tr>
		<td valign="top" width="45%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Active Stock Alerts (<?php echo count($active_data); ?>)
			</caption>
			<tr align="left">
	  			<th width="10%">Mobile</th>
	  			<th width="15%">Company</th>
	  			<th width="5%">NSE/BSE</th>
	  			<th width="5%">News/Price</th>
	  			<th width="10%">Start</th>
	  		</tr>
	  	       
			<?php foreach($active_data as $user){ ?>
			<tr align="left">
			<td> <a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a></td>
			<td> <?php echo $user['StockCompany']['company'];?></td>
			<td> <?php if($user['Stock']['nse_flag'] == '1') echo 'NSE'; else echo 'BSE'; ?></td>
			<td> <?php if($user['Stock']['news_flag'] == '1') echo 'News'; else echo 'Price'; ?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['Stock']['start']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="55%" style="padding-left:50px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Inactive Stock Alerts (<?php echo count($inactive_data); ?>)
			</caption>
			<tr align="left">
	  			<th width="14%">Mobile</th>
	  			<th width="15%">Company</th>
	  			<th width="5%">NSE/BSE</th>
	  			<th width="5%">News/Price</th>
	  			<th width="8%">Start</th>
	  			<th width="8%">End</th>
	  		</tr>
	  	       
			<?php foreach($inactive_data as $user){ ?>
			<tr align="left">
			<td> <a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a> (<?php echo $user['User']['balance'];?>)</td>
			<td> <?php echo $user['StockCompany']['company'];?></td>
			<td> <?php if($user['Stock']['nse_flag'] == '1') echo 'NSE'; else echo 'BSE'; ?></td>
			<td> <?php if($user['Stock']['news_flag'] == '1') echo 'News'; else echo 'Price'; ?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['Stock']['start']));?></td>
			<td> <?php if(!empty($user['Stock']['end']))echo date('Y-m-d', strtotime($user['Stock']['end']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>

</table>

<?php } ?>

<?php if($app_info['SMSApp']['controller_name'] == 'reminder') {?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
	<tr>
		<td valign="top" width="50%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Active Reminders (<?php echo count($active_data); ?>)
			</caption>
			<tr align="left">
	  			<th width="10%">Mobile</th>
	  			<th width="10%">Type</th>
	  			<th width="10%">For</th>
	  			<th width="10%">Date (Time)</th>
	  			<th width="10%">Created On</th>
	  		</tr>
	  	       
			<?php foreach($active_data as $user){ ?>
			<tr align="left">
			<td> <a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a></td>
			<td> <?php if(!empty($user['Reminder']['day']))echo "Daily"; else if(!empty($user['Reminder']['week']))echo "Weekly"; else if(!empty($user['Reminder']['month']))echo "Monthly"; else if(!empty($user['Reminder']['year']))echo "Yearly"; else echo "One Time";?></td>
			<td><?php if($user['User']['mobile'] == $user['Reminder']['reminder_for'])echo "Me"; else {echo "Others (" . count(explode(",",$user['Reminder']['reminder_for'])) . ")" ;} ?></td>
			<td> <?php echo $user['Reminder']['date'] . " (" . $user['Reminder']['time'] .  ")";  ?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['Reminder']['created']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="50%" style="padding-left:50px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Inactive Reminders (<?php echo count($inactive_data); ?>)
			</caption>
			<tr align="left">
	  			<th width="12%">Mobile</th>
	  			<th width="8%">Type</th>
	  			<th width="10%">For</th>
	  			<th width="10%">Date (Time)</th>
	  			<th width="10%">Created On</th>
	  		</tr>
	  	       
			<?php foreach($inactive_data as $user){ ?>
			<tr align="left">
			<td> <a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a> (<?php echo $user['User']['balance'];?>)</td>
			<td> <?php if(!empty($user['Reminder']['day']))echo "Daily"; else if(!empty($user['Reminder']['week']))echo "Weekly"; else if(!empty($user['Reminder']['month']))echo "Monthly"; else if(!empty($user['Reminder']['year']))echo "Yearly"; else echo "One Time";?></td>
			<td><?php if($user['User']['mobile'] == $user['Reminder']['reminder_for'])echo "Own"; else {echo "Others (" . count(explode(",",$user['Reminder']['reminder_for'])) . ")" ;} ?></td>
			<td> <?php echo $user['Reminder']['date'] . " (" . $user['Reminder']['time'] .  ")";  ?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['Reminder']['created']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>

</table>

<?php } ?>


<?php //echo "<pre>"; print_r($data); print_r($app_info); echo "</pre>";?>
