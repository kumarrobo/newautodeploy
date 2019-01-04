<table>
	<tr>
		<td align="center" colspan="2"><h3>All Recent Messages<h3></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td><b>Date</b></td>
		<td><b>Content</b></td>
	</tr>
<?php
	foreach($allRecentMsgs as $msgs)
	{
?>
		<tr>
			<td valign="top" align="left"><?php echo $msgs['Log']['timestamp']; ?></td>
			<td valign="top" align="left"><?php echo $msgs['Log']['content']; ?></td>
		</tr>
		<tr><td colspan="2"><hr /></td></tr>
		
<?php		
	}
?>
</table>