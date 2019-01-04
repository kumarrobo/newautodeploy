<table>
	<tr>
		<td align="center" colspan="2"><h3>All Messages (<?php echo $name;?>)<h3></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
<?php
	foreach($allRecentMsgs as $msgs)
	{
?>
		<tr>
			<td valign="top" align="left">Message Id: <?php echo $msgs['messages']['id']; ?></td>
		</tr>
		<tr>
			<td valign="top" align="left"><?php echo $msgs['messages']['content']; ?></td>
		</tr>
		<tr><td colspan="1"><hr /></td></tr>
		
<?php		
	}
?>
</table>