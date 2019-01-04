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

function collectAmount1(){
	var method='topUp';
	var mobile=trim($('mobile').value);
	var amount=trim($('amount').value);
	
	if(mobileValidate(mobile) == '0') return false;
	
	if(amount == ''){
		alert("Enter amount"); return false;
	}else if(isNaN(amount)||amount.indexOf(" ")!= -1){
		alert("Enter valid amount"); return false;
	}
	
	var checkFlag='';
	if($('collectAmount').checked === true)
		checkFlag=1;
	else
		checkFlag=0;
		
	if(confirm("Are you sure you want to transfer Rs " + amount + " to " + mobile)){
		document.topUp.action="/salesmen/topupAmount/";
	}else{
		return false;
	}

}

function cngButVal(){
	if($('collectAmount').checked === true)
		$('collect').value = 'Top-up & collect';
	else
		$('collect').value = 'Top-up';	
}

</script>

<table width="100%"><tr><td><div style="width:100%;text-align:center;background:#ff0000;font-size:1.5em"><strong><span style="color:#ffffff">Pay</span><span style="color:#000000">1</span></strong></div></td><td width="90px"><a href="/salesmen/mainMenu">Main Menu</a></td></tr></table>
<form name="topUp" method="POST" onSubmit="return collectAmount1();">
<h3>Retailer Top-Up</h3>
<table>	
	<tr><td>Mobile No.</td><td> <input type="number" name="mobile" id="mobile" value="" /><td></tr>
	<tr><td>Amount</td><td> <input type="number" name="amount" id="amount" value=""/></td></tr>
	<tr><td>&nbsp;</td><td style="padding:10px 0"><label><input type="checkbox" name="collectAmount" id="collectAmount" value="1" onclick="cngButVal();" />Collect</label></td></tr>
	<tr><td>&nbsp;</td><td><input type="submit" name="collect" id="collect" value="Top-up"/></td></tr>
</table>
</form>
<script>
$('mobile').focus();	
</script> 