<table>
	<tr> <td valign='top'>
	<table>
	<tr><td align="center" colspan="2"><h3>Fun Data</h3></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td><b>Package Name</b></td>
		<td><b>No. of data</b></td>
	</tr>
<?php
	foreach($fun_data_details as $data)
	{
?>
		<tr>
			<td align="left" valign="top" style="width:150px;"><?php echo $data['packages']['name']; ?></td>
			<td align="left" valign="top"><?php echo $data['0']['cnt']; ?></td>
		</tr>
<?php		
	}
?>
	</table>
	</td>
	
	
	<td valign='top' style="padding-left:100px;">
	<table>
	<tr><td align="center" colspan="2"><h3>Tips Data </h3></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td><b>Package Name</b></td>
		<td><b>No. of data</b></td>
	</tr>
<?php
	foreach($tips_data_details as $data)
	{
?>
		<tr>
			<td align="left" valign="top" style="width:150px;"><?php echo $data['packages']['name']; ?></td>
			<td align="left" valign="top"><?php echo $data['0']['cnt']; ?></td>
		</tr>
<?php		
	}
?>
	</table>
	</td>
	
	<td valign='top' style="padding-left:100px;">
	<table>
	<tr><td align="center" colspan="2"><h3>Cricket Data </h3></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td><b>Package Name</b></td>
		<td><b>No. of data</b></td>
	</tr>
<?php
	foreach($cricket_data_details as $data)
	{ if($data['packages']['id'] == 146){ 
?>
		<tr>
			<td align="left" valign="top" style="width:150px;"><?php echo $data['packages']['name']; ?></td>
			<td align="left" valign="top"><?php echo $data['0']['cnt']; ?></td>
		</tr>
<?php		
	} }
?>
	
	</table>
	</td>
	<td valign='top' style="padding-left:100px;">
	<table>
	<tr><td align="center" colspan="2"><h3>Cool Video Data </h3></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td><b>Package Name</b></td>
		<td><b>No. of data</b></td>
	</tr>

	<tr>
		<td align="left" valign="top" style="width:150px;">Cool Videos</td>
		<td align="left" valign="top"><?php echo $video_data_details['0']['0']['cnt']; ?></td>
	</tr>
	</table>
	</td>
	
	</tr>
</table>