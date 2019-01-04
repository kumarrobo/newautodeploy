<?php  //echo "<pre>"; print_r($user_data); echo "</pre>";?>

<table border="1" cellpadding="0" cellspacing="0" summary="List Package" width="50%" align="center">
	<caption class="header">
	Package Info
	</caption>

	<tr align="left">
		<td>Name</td>
		<td><?php echo $pack_data['Package']['name']; ?></td>
	</tr>


	<tr align="left">
		<td>Price</td>
		<td><?php echo $pack_data['Package']['price']; ?></td>
	</tr>
	<tr align="left">
		<td>Validity</td>
		<td><?php echo $pack_data['Package']['validity'] . " days"; ?> </td>
	</tr>

	<tr align="left">
		<td>Frequency</td>
		<td><?php echo $objGeneral->getFrequency($pack_data['Package']['frequency']);?></td>
	</tr>
	<tr align="left">
		<td>Mobile Code</td>
		<td><?php echo $pack_data['Package']['code']; ?></td>
	</tr>
	<tr align="left">
		<td>Unique messages sent</td>
		<td><?php echo $unique; ?></td>
	</tr>
	<tr align="left">
		<td>Total Messages sent</td>
		<td><?php echo $total; ?></td>
	</tr>
	
</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="padding-top:50px;">
	<tr>
		<td valign="top" width="50%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Current Users (<?php echo count($active_users); ?>)
			</caption>
			<tr align="left">
	  			<th width="15%">Mobile</th>
	  			<th width="10%">No. of times</th>
	  			<th width="12%">Start</th>
	  			<th width="13%">End</th>
	  		</tr>
	  	       
			<?php foreach($active_users as $user){ ?>
			<tr align="left">
			<td> <a href="/groups/getUserInfo/<?php echo $user['users']['mobile'];?>"><?php echo $user['users']['mobile'];?></a></td>
			<td> <?php echo $user['0']['cactive'];?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['0']['start']));?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['0']['end']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="50%" style="padding-left:50px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Inactive Users (<?php echo count($inactive_users); ?>)
			</caption>
			<tr align="left">
	  			<th width="10%">Mobile</th>
	  			<th width="10%">Balance</th>
	  			<th width="10%">No. of times</th>
	  			<th width="10%">Start</th>
	  			<th width="10%">End</th>
	  		</tr>
	  	       
			<?php foreach($inactive_users as $user){ ?>
			<tr align="left">
			<td> <a href="/groups/getUserInfo/<?php echo $user['users']['mobile'];?>"><?php echo $user['users']['mobile'];?></a></td>
			<td> <?php echo $user['users']['balance'];?></td>
			<td> <?php echo $user['0']['cactive'];?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['0']['start']));?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['0']['end']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>

</table>	