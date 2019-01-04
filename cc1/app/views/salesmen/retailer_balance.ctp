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


function searchRetailerBalance(){
	var method='payment';
	var mobile=trim($('mobile').value);
	if(mobileValidate(mobile) == '0') return false;
	document.retailerBalance.action="/salesmen/retailerSales/"+mobile; 	
}
</script>

<table width="100%"><tr><td><div style="width:100%;text-align:center;background:#ff0000;font-size:1.5em"><strong><span style="color:#ffffff">Pay</span><span style="color:#000000">1</span></strong></div></td><td width="90px"><a href="/salesmen/mainMenu">Main Menu</a></td></tr></table>
<form name="retailerBalance" method="POST" onSubmit="return searchRetailerBalance()" >
<h2>Retailer Details</h2>
<table>	
	<tr><td>Retailer Mob</td><td><input type="text" id="mobile" value="" /></td></tr>
	<tr><td colspan="2"></br><input type="submit" value="Get Details" /></td></tr>
</table>
</form>
<script>
$('mobile').focus();	
</script> 