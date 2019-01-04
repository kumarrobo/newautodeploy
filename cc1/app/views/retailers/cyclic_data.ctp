<table>
	<tr>
		<td align="center" colspan="2"><h3>Cyclic Data (<?php echo $name; ?>) Range: <?php echo $range; ?><h3></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td><b>Content</b></td>
	</tr>
<?php
	foreach($data as $msg)
	{
		if($range){			
			if($msg['table1']['length'] >= $min && $msg['table1']['length'] <= $max){			
		
?>
		<tr><td>Message id: <?php echo $msg['table1']['id']; ?>   Length: <?php echo $msg['table1']['length']; ?> Cycle: <?php echo $msg['cyclic_refined']['cycle']; ?></td></tr>
		<tr>
			<td valign="top" align="left"><?php echo $msg['table1']['cyclicData']; ?></td>
		</tr>
		<tr><td colspan="2"><hr /></td></tr>
		
<?php
		}
	}else{
?>
	<tr><td>Message id: <?php echo $msg['table1']['id']; ?>   Length: <?php echo $msg['table1']['length']; ?> Cycle: <?php echo $msg['cyclic_refined']['cycle']; ?></td></tr>
		<tr>
			<td valign="top" align="left"><?php echo $msg['table1']['cyclicData']; ?></td>
		</tr>
		<tr><td colspan="2"><hr /></td></tr>
<?php			
	}
}	
?>
</table>