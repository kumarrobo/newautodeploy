<div style="max-height:500px; overflow-y:scroll">
<table class="table table-condensed table-bordered table-striped table-hover">
<tr>
	<th width="25%">From JID </th>
	<th width="50%">Chat</th>
	<th width="25%">Time</th>
</tr>
<?php foreach($chats as $chat): ?>
<tr>
	<td><?php $splitJID = explode("@", $chat['m']['fromJID']);
			if(is_numeric($splitJID[0])){ 
				echo "Retailer"; 
			}	
			else { 
				echo "Support";
			}	 ?></td>
	<td><?php echo $chat['m']['body'] ?></td>
	<td><?php echo date('Y-m-d H:i:s', $chat['m']['sentDate']/1000) ?></td>
</tr>
<?php endforeach ?>
</table>
</div>