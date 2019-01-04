<?php echo $this->Form->create('Group', array('type' => 'file'));?>

<table width="60%" align="center">
<tr>
	<td colspan="2"><?php echo $error; ?></td>
</tr>
<tr>
	<td widyh="50%">Description</td>
	<td><textarea value="" name="data[groups][desc]" rows="4" style="width:200px;"><?php echo $desc; ?></textarea></td>
</tr>
<tr>
	<td>3gp File</td>
	<td><input type="file" value="" name="data[groups][video]"></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><br><input type="submit" value="Upload"></td>
</tr>

</table>
</form>