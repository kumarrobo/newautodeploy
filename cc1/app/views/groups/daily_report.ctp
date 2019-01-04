<div style="min-height:700px;">

	<div><span>Total Users Registered on Site: <b><?php echo $total_users_registered;?></b> </span></div> 
	<hr>
	<div><span>Total Users Registered on <?php echo $date; ?> : <b><?php echo (count($users_registered_Loggedin) + count($users_registered_notLoggedin)) ;?></b> </span></div> 
	<hr>

	<div><span>Users registered and logged in : <b><?php echo count($users_registered_Loggedin);?></b></span> <br>
		<?php foreach($users_registered_Loggedin as $user) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a>(<?php echo $user['User']['balance'];?>) </span>
		<?php } ?>
	</div>
	<hr>
	<div><span>Users registered but not logged in : <b><?php echo count($users_registered_notLoggedin);?></b></span> <br>
		<?php foreach($users_registered_notLoggedin as $user) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a> </span>
		<?php } ?>
	</div>
	<hr>
	<div><span>Users registered via Miss Call : <b><?php echo count($users_registered_viaMisscall);?></b></span> <br>
		<?php foreach($users_registered_viaMisscall as $user) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a> </span>
		<?php } ?>
	</div>
	<hr>
	

	<div><span>Total Users visited : <b><?php echo $users_visited;?> </b></span></div> 
	<div><span>Total returning users : <b><?php echo $returning_users;?> </b></span></div>
	<hr>
	<?php if(!empty($pnrs_subs) || !empty($pnrs_discon)) { ?>
	<div><span><a href="/groups/getAlertsInfo/pnr">PNR Alerts</a><span><br/>
		<?php if(!empty($pnrs_subs)) { ?>
		Created:
		<?php foreach($pnrs_subs as $pnr) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $pnr['users']['mobile'];?>"><?php echo $pnr['users']['mobile'];?></a>(<?php echo $pnr['0']['counts'];?>) </span>
		<?php } ?><br/>
		<?php } ?>
		<?php if(!empty($pnrs_discon)) { ?>
		Ended:
		<?php foreach($pnrs_discon as $pnr) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $pnr['users']['mobile'];?>"><?php echo $pnr['users']['mobile'];?></a>(<?php echo $pnr['0']['counts'];?>) </span>
		<?php } }?>
	</div>
	<hr>
	<?php  } ?>
	
	<?php if(!empty($stock_subs) || !empty($stock_discon)) { ?>
	<div><span><a href="/groups/getAlertsInfo/stock">Stock Alerts</a><span><br/> 
		<?php if(!empty($stock_subs)) { ?>
		Created:
		<?php foreach($stock_subs as $stock) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $stock['users']['mobile'];?>"><?php echo $stock['users']['mobile'];?></a>(<?php echo $stock['0']['counts'];?>) </span>
		<?php } ?><br/>
		<?php } ?>
		<?php if(!empty($stock_discon)) { ?>
		Ended:
		<?php foreach($stock_discon as $stock) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $stock['users']['mobile'];?>"><?php echo $stock['users']['mobile'];?></a>(<?php echo $stock['0']['counts'];?>) </span>
		<?php } } ?>
	</div>
	<hr>
	<?php  } ?>
	
	<?php if(!empty($reminder_subs)) { ?>
	<div><span><a href="/groups/getAlertsInfo/reminder">Reminders</a><span><br/> 
		<?php foreach($reminder_subs as $reminder) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $reminder['users']['mobile'];?>"><?php echo $reminder['users']['mobile'];?></a>(<?php echo $reminder['0']['counts'];?>) </span>
		<?php } ?><br/>
		
	</div>
	<hr>
	<?php  } ?>
	
	<div><span>Packages Subscribed : <b><?php echo count($packages_subscribed);?></b></span> <br>
		<?php foreach($packages_subscribed as $package) {?>
			<span style="margin-right:10px;"><a href="/groups/getPackInfo/<?php echo $package['packages_users']['package_id'];?>"><?php echo $package['packages']['name']; ?></a>(<?php echo $package['0']['packCount'];?>)</span>
		<?php } ?>
	</div>
	<hr>
	<div><span>Users Who Subscribed Packages : <b><?php echo count($users_packages_subscribed);?></b></span> <br>
		<?php foreach($users_packages_subscribed as $user) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $user['users']['mobile'];?>"><?php echo $user['users']['mobile']; ?></a>(<?php echo $user['0']['userCount'];?>)</span>
		<?php } ?>
	</div>
	<hr>
	
	<?php if(!empty($packages_continued)) { ?>
	<div><span>Packages Continued: <b><?php echo count($packages_continued);?></b></span> <br>
		<?php foreach($packages_continued as $package) {?>
			<span style="margin-right:10px;"><a href="/groups/getPackInfo/<?php echo $package['packages_users']['package_id'];?>"><?php echo $package['packages']['name']; ?></a>(<a href="/groups/getUserInfo/<?php echo $package['users']['mobile'];?>"><?php echo $package['users']['mobile']; ?></a>)</span>
		<?php } ?>
	</div>
	<hr>
	<?php } ?>
	
	<?php if(!empty($packages_discontinued)) { ?>
	<div><span>Packages Discontinued: <b><?php echo count($packages_discontinued);?></b></span> <br>
		<?php foreach($packages_discontinued as $package) {?>
			<span style="margin-right:10px;"><a href="/groups/getPackInfo/<?php echo $package['packages_users']['package_id'];?>"><?php echo $package['packages']['name']; ?></a>(<a href="/groups/getUserInfo/<?php echo $package['users']['mobile'];?>"><?php echo $package['users']['mobile']; ?></a>)</span>
		<?php } ?>
	</div>
	<hr>
	<?php } ?>
	
	<?php if(!empty($products_discon)) { ?>
	<div><span>Products Discontinued: <b><?php echo count($products_discon);?></b></span> <br>
		<?php foreach($products_discon as $product) {?>
			<span style="margin-right:10px;"><a href="/retailers/product/<?php echo $product['Product']['id'];?>"><?php echo $product['Product']['name']; ?></a> <?php if($product['ProductsUser']['count'] - $product['ProductsUser']['trial'] == 0) echo "-Trial";?>(<a href="/groups/getUserInfo/<?php echo $product['User']['mobile'];?>"><?php echo $product['User']['mobile']; ?></a>)</span>
		<?php } ?>
	</div>
	<hr>
	<?php } ?>

	<div><span>Total Messages Sent For The Day : <b><?php echo $total_messages_sent;?> </b></span></div> 
	<div><span>Total Free Messages Sent: : <b><?php echo $free_messages_sent;?> </b></span></div>
	<div><span>Total Forwaded Messages Sent : <b><?php echo $forward_messages_sent;?> </b></span></div> 
	<div><span>Total Messages Sent For Packages: : <b><?php echo $package_messages_sent;?> </b></span></div>
	<hr>
	
	<?php if(!empty($payment_success)) { ?>
	<div><span>Successful Payments: <b><?php echo count($payment_success);?></b></span> <br>
		<?php foreach($payment_success as $user) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $user['users']['mobile'];?>"><?php echo $user['users']['mobile'];?></a>(<?php echo $user['payment']['amount'];?>)</span>
		<?php } ?>
	</div>
	<hr>
	<?php } ?>
	
	<?php if(!empty($payment_failure)) { ?>
	<div><span>Failure Payments: <b><?php echo count($payment_failure);?></b></span> <br>
		<?php foreach($payment_failure as $user) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $user['users']['mobile'];?>"><?php echo $user['users']['mobile'];?></a>(<?php echo $user['payment']['amount'];?>)</span>
		<?php } ?>
	</div>
	<hr>
	<?php } ?>
	
	<?php if(!empty($payment_trials)) { ?>
	<div><span>Payment Trials: <b><?php echo count($payment_trials);?></b></span> <br>
		<?php foreach($payment_trials as $user) {?>
			<span style="margin-right:10px;"><a href="/groups/getUserInfo/<?php echo $user['users']['mobile'];?>"><?php echo $user['users']['mobile'];?></a>(<?php echo $user['0']['num_trials'];?>)</span>
		<?php } ?>
	</div>
	<hr>
	<?php } ?>
	

</div>