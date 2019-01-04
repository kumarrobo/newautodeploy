<script>
function trim(str) { return str.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); }
function mobileValidate(y){
	var y = trim(y);
	if(y == ""){ alert("Enter mobile number"); return 0;
	}else if(isNaN(y)||y.indexOf(" ")!= -1){ alert("Mobile number should contain numeric values"); return 0;
	}else if(y.length != 10){ alert("Mobile number should be a 10 digit number"); return 0;
	}else if(y.charAt(0)!="9" && y.charAt(0)!="8" && y.charAt(0)!="7"){ alert("Mobile number should start with 9, 8 or 7"); return false;}
	return 1;
}


function createRetailer1(){
	var rShopName=trim($('shopname').value);
	var rMobile=trim($('mobile').value);
	
		
	if(rShopName==''){
		alert("Enter retailer shop name.");
		return false;
	}
	
	if(mobileValidate(rMobile) == '0') return false;
	
	var sel = document.getElementsByName('subArea');
	var subArea = '';
	for (var i=0; i <sel.length; i++){
		if (sel[i].checked === true){ 
			subArea = sel[i].value;
			break; 
		}
	 }

	var sel1=document.getElementsByName('type');
	var type='';	
	for(var j=0;j<sel1.length;j++){
		if(sel1[j].checked === true){
			type=sel1[j].value;
			break;
		}
	}
	
	var confirmRetailer=confirm("Shop: "+rShopName+" \nMobile: "+rMobile+"\nPress 'Ok' to create retailer");
	
	if(confirmRetailer === true){						
	}else{
		return false;
	}
  }
</script>

<?php
	if(isset($ret['status']) && $ret['status'] != ''){
		if($ret['status'] == 'failure'){
?>
			<script>alert("<?php echo $ret['description']; ?>");</script>
<?php	
		}else if($ret['status'] == 'success'){
		
			if($ret['type'] == '0'){
?>
				<script>
					alert("<?php echo $ret['description']; ?>");
					window.location.href = '/salesmen/mainMenu/';
				</script>
<?php							
			}else{
			$tmp  = '/salesmen/payment/1/'.$ret['mobile'].'/'.$ret['type'];
?>				
				<script>
					alert("<?php echo $ret['description']; ?>");
					window.location.href = '<?php echo $tmp ?>';
				</script>
<?php					
			}
		}
	}
?>
<table width="100%"><tr><td><div style="width:100%;text-align:center;background:#ff0000;font-size:1.5em"><strong><span style="color:#ffffff">Pay</span><span style="color:#000000">1</span></strong></div></td><td width="90px"><a href="/salesmen/mainMenu">Main Menu</a></td></tr></table>
<h2>Retailer Registration</h2>
<form action="/salesmen/createRetailer/" name="createRetailer" method="POST" onSubmit="return createRetailer1()">
<table>
	<tr><td>Shop Name</td><td><input type="text" name="shopname" id="shopname" value=""/></td></tr>
	<tr><td>Retailer Mobile</td><td><input type="number" name="mobile" id="mobile" value="" /></td></tr>
	<tr><td colaspn="2">&nbsp;</td></tr>
	<tr><td>Area</td><td>	
	<?php
		$count=1;
			foreach($salesmanArea as $s)
			{
				$chked = '';
				if($count == 1) $chked = 'checked';
				  		
				echo "<input type='radio' ".$chked." name='subArea' id='subArea".$count."' value='".$s['sa']['id']."'/>".$s['sa']['name'];				
				$count++;
				echo "</br>";
			}
	
	?>			
	</td></tr>
	<tr><td colaspn="2">&nbsp;</td></tr>	
	<tr>
		<td>Type</td>
		<td>
			<label for="type2"><input type='radio' name='type' id='type2' checked value='1'/>Paid</label></br>
			<label for="type1"><input type='radio' name='type' id='type1' value='0'/>Trial</label></br>	
		</td>
	</tr>

	<tr align="left"><td colspan="2"><input type="submit" value="Register Retailer" />&nbsp;<input type="button" value="Reset" onClick="this.form.reset()" /></td></tr>
</table>
</form>
<script>
$('shopname').focus();

setRadio('subArea');
setRadio('type');

function setRadio(name) {
	var radioButtons = document.getElementsByName(name);
	for (var x = 0; x < radioButtons.length; x++) {
		if (x == 0) {
			radioButtons[x].checked = true;
		}else{
			radioButtons[x].checked = false;
		}
	}
}
</script>