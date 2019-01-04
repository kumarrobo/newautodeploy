<table style="margin-top:10px" width="100%" border="1" cellpadding="3" cellspacing="0">
	<h4> Requests per minute </h4>
	<thead>
		<tr>
			<th rowspan="2">Operator</th>
			<th rowspan="2">Usable Sims</th>
			<th colspan="2"><?php echo date('Y-m-d H:i');?></th>
			<th colspan="2"><?php echo date('Y-m-d H:i',strtotime('-1 minutes'));?></th>
			<th colspan="2"><?php echo date('Y-m-d H:i',strtotime('-2 minutes'));?></th>
			<th colspan="2"><?php echo date('Y-m-d H:i',strtotime('-3 minutes'));?></th>
			<th colspan="2"><?php echo date('Y-m-d H:i',strtotime('-4 minutes'));?></th>
			<th colspan="2"><?php echo date('Y-m-d H:i',strtotime('-5 minutes'));?></th>
		</tr>
		<tr>
			<th>API</th>
			<th>Modems</th>
			<th>API</th>
			<th>Modems</th>
			<th>API</th>
			<th>Modems</th>
			<th>API</th>
			<th>Modems</th>
			<th>API</th>
			<th>Modems</th>
			<th>API</th>
			<th>Modems</th>
		</tr>
	<?php foreach($requests as $opr => $req) { ?>
	<tr>
		<td><?php echo $req['name']; ?></td>
		<td><?php echo $req['devices']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i')]['api_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i')]['modem_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i',strtotime('-1 minutes'))]['api_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i',strtotime('-1 minutes'))]['modem_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i',strtotime('-2 minutes'))]['api_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i',strtotime('-2 minutes'))]['modem_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i',strtotime('-3 minutes'))]['api_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i',strtotime('-3 minutes'))]['modem_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i',strtotime('-4 minutes'))]['api_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i',strtotime('-4 minutes'))]['modem_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i',strtotime('-5 minutes'))]['api_txns']; ?></td>
		<td><?php echo $req[date('Y-m-d H:i',strtotime('-5 minutes'))]['modem_txns']; ?></td>
		
		
	</tr>
	
	<?php } ?>
</table>	


