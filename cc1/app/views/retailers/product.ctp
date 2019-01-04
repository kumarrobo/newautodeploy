<?php  //echo "<pre>"; print_r($user_data); echo "</pre>";?>

<table border="1" cellpadding="0" cellspacing="0" summary="List Product" width="50%" align="center">
	<caption class="header">
	Product Info
	</caption>

	<tr align="left">
		<td>Name</td>
		<td><?php echo $product['Product']['name']; ?></td>
	</tr>


	<tr align="left">
		<td>Price</td>
		<td><?php echo $product['Product']['price']; ?></td>
	</tr>
	<tr align="left">
		<td>Validity</td>
		<td><?php echo $product['Product']['validity'] . " days"; ?> </td>
	</tr>

	<tr align="left">
		<td>Product Code</td>
		<td><?php echo $product['Product']['code']; ?></td>
	</tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="padding-top:50px;">
	<tr>
		<td valign="top" width="50%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Active Product Users (<?php echo count($active); ?>)
			</caption>
			<tr align="left">
	  			<th width="15%">Mobile</th>
	  			<th width="10%">No. of times</th>
	  			<th width="10%">Trial</th>
	  			<th width="12%">Start</th>
	  			<th width="13%">End</th>
	  		</tr>
	  	       
			<?php foreach($active as $user){ ?>
			<tr align="left">
			<td> <a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a></td>
			<td> <?php echo ($user['0']['counts'] - $user['0']['trial']);?></td>
			<td> <?php echo $user['0']['trial'];?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['0']['minDate']));?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['0']['maxDate']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="50%" style="padding-left:50px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			InActive Product Users (<?php echo count($inactive); ?>)
			</caption>
			<tr align="left">
	  			<th width="15%">Mobile</th>
	  			<th width="10%">No. of times</th>
	  			<th width="10%">Trial</th>
	  			<th width="12%">Start</th>
	  			<th width="13%">End</th>
	  		</tr>
	  	       
			<?php foreach($inactive as $user){ ?>
			<tr align="left">
			<td> <a href="/groups/getUserInfo/<?php echo $user['User']['mobile'];?>"><?php echo $user['User']['mobile'];?></a></td>
			<td> <?php echo ($user['0']['counts'] - $user['0']['trial']);?></td>
			<td> <?php echo $user['0']['trial'];?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['0']['minDate']));?></td>
			<td> <?php echo date('Y-m-d', strtotime($user['0']['maxDate']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>

</table>