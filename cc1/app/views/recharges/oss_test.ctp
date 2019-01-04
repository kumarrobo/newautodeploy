<script>

function setAction(){
	document.userInfo.action="/recharges/ossTest/"+$('type').value+"/"+$('opr').value+"/"+$('amt').value;
	document.userInfo.submit();
}

</script>
<form name="userInfo" method="POST" onSubmit="setAction()">
<table>
	<tr>
		<td>Type: 
			<select name="type" id="type">
				<option value="mr" <?php if($type == 'mr') echo 'selected'; ?>>Mobile</option> 
				<option value="dt" <?php if($type == 'dt') echo 'selected'; ?>>DTH</option>	
			</select>		
		</td>
		<td>Operator: 
			<select name="opr" id="opr">
			<?php foreach($opr['mobRecharge'] as $o){
				$sel = '';
				if($selOpr == $o['flexi']['oss'])
					$sel = 'selected';
					
				echo '<option '.$sel.' value="'.$o['flexi']['oss'].'">'.$o['operator'].'</option>';
			}
			
			foreach($opr['dthRecharge'] as $o){
				$sel = '';
				if($selOpr == $o['flexi']['oss'])
					$sel = 'selected';
					 
				echo '<option '.$sel.' value="'.$o['flexi']['oss'].'">'.$o['operator'].'</option>';
			}
			?>				
		</td>
		<td>Amt: <input type="text" name="amt" id="amt" value="<?php echo $amt; ?>">&nbsp;&nbsp;&nbsp;<input type="button" value="Submit" onclick="setAction()"></td>		
	</tr>
</table>
</form>
<b>Available recharges</b> </br>

<?php
echo "<pre>";
print_r($rec);
echo "</pre>";
?>

<b>Recharge code selected:</b></br>

<?php
echo $recCode;
?>
