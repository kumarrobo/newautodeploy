<script>

function trim(str) { return str.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); }
function mobileValidate(y){
	var y = trim(y);
	if(y == ""){ alert("Enter mobile number"); return 0;
	}else if(isNaN(y)||y.indexOf(" ")!= -1){ alert("Mobile number should contain numeric values"); return 0;
	}else if(y.length != 10){ alert("Mobile number should be a 10 digit number"); return 0;
	}
	return 1;
}


function collectAmount(){
	var method='payment';
	var mobile=trim($('mobile').value);
	var sel1=document.getElementsByName('type');
	var type='';
	
	if(mobileValidate(mobile) == '0') return false;
		
	for(var j=0;j<sel1.length;j++){
		if(sel1[j].checked === true){
			type=sel1[j].value;
		}
	}
	document.collection.action="/salesmen/payment/3/"+mobile+"/"+type;
	//document.location.href="/salesmen/payment/3/"+mobile+"/"+type;	
}

</script>
<table width="100%"><tr><td><div style="width:100%;text-align:center;background:#ff0000;font-size:1.5em"><strong><span style="color:#ffffff">Pay</span><span style="color:#000000">1</span></strong></div></td><td width="90px"><a href="/salesmen/mainMenu">Main Menu</a></td></tr></table>
<form name="collection" method="POST" onSubmit="return collectAmount()">
<h2>Payment Collection</h2>
<table>	
	<tr>
		<td>Retailer Mobile</td>
		<td><input type="number" name="mobile" id="mobile" value="" /></td>
	</tr>
	<tr>
		<td>Type</td>
		<td>
			<label><input type='radio' checked name='type' id='type1'  value='1'/>Top Up</label></br>
			<label><input type='radio' name='type' id='type2' value='0'/>Set Up</label>
		</td>
	</tr>	
	<tr><td>&nbsp;</td><td><input type="submit" value="Collect" /></td></tr>
</table>
</form>
<script>
$('mobile').focus();	
</script> 