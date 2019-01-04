<style>
#tags tr:nth-child(even) {
	background-color: #eee;
}

#tags tr:nth-child(odd) {
	background-color: #fff;
}

#tags td {
	border: 1px solid white;
}
</style>
<?php if(count($calls) > 0): ?>
<h1>Tags Report - <?php echo $tag[0]['taggings']['name'] ?> (<?php echo count($calls) ?>)</h1><h3> From <?php echo $from_date ?> to <?php echo $to_date ?> <?php if(isset($from_time)) echo $from_time.":00 to ".$to_time.":00 " ?></h3><br>
<table id="tags" style="text-align:center; padding:7px;margin:10px;">
	<tr>
		<th>No.</th>
		<th>Transaction no.</th>
		<th>Customer Mobile no.</th>
		<th>Operator</th>
		<th>Vendor</th>
		<th>Amount</th>
		<th>Status</th>
		<th>Comment Time</th>
		<th>Recharge Time</th>
		<th>Executive</th>
		<th>Retailer</th>
		<th>Medium</th>
		<th>Device Type</th>
	</tr>
	<?php $i = 0; ?>
	<?php foreach($calls as $call): ?>
	<?php $i++ ?>
	<tr>
		<td><?php echo $i ?></td>
		<td><a target="_blank" href='/panels/transaction/<?php echo $call['c']['ref_code'] ?>'><?php if(isset($call['c']['ref_code'])) echo $call['c']['ref_code']; else echo "Not Applicable" ?></a></td>
		<td><?php echo $call['va']['mobile'] ?></td>
		<td><?php echo $call['p']['name'] ?></td>
		<td><?php echo $call['v']['company'] ?></td>
		<td><?php echo $call['va']['amount'] ?></td>
		<td><?php echo $call['va']['status'] ?></td>
		<td><?php echo $call['c']['created'] ?></td>
		<td><?php echo $call['va']['timestamp'] ?></td>
		<td><?php echo $call['u2']['name'] ?></td>
		<td><a target="_blank" href='/panels/retInfo/<?php echo $call['r']['mobile'] ?>'><?php echo $call['r']['shopname']." (".$call['r']['mobile'].")" ?></a></td>
		<td><?php if(isset($medium_map[$call['cc']['medium']])) echo $medium_map[$call['cc']['medium']]; else echo "None"; ?> </td>
                <td><?php echo ($device_type[$call['r']['user_id']] == '') ? 'NULL' : ucwords($device_type[$call['r']['user_id']]); ?></td>
	</tr>
	<?php endforeach ?>
</table>
<?php endif ?>