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

function collectPayment(rMobile,flag,type,pending){
	var url='';
	var chequeNo='';
	var amount=trim($('amount').value);
	var billBookNo=$('billBookNo').value;
	var sel1=document.getElementsByName('mode');
	var payMode='';
	
	if(mobileValidate(rMobile) == '0') return false;
	
	if(amount == ''){
		alert("Enter amount"); return false;
	}else if(isNaN(amount)||amount.indexOf(" ")!= -1){
		alert("Enter valid amount"); return false;
	}else if(amount > pending){
		alert("Collection amount cannot be greater than pending amount"); return false;
	}
	
	for(var j=0;j<sel1.length;j++){
		if(sel1[j].checked === true){
			payMode=sel1[j].value;
		}
	}
	
	if(payMode=='2'){
		chequeNo=$('chequeNumber').value;
		if(chequeNo == ''){
			alert("Enter cheque number"); return false;
		}else if(isNaN(chequeNo)||chequeNo.indexOf(" ")!=-1){
	      	alert("Enter valid cheque number"); return false;
	   	}
	}
		
	
	
	var re = /[^0-9]/g;
	if(billBookNo != ''){
		if (re.test(billBookNo)){
			alert("Enter a valid bill book number"); return false;
		}
	}
	
	if(confirm("Are you sure you want to collect Rs."+amount+" from Retailer "+$('shopName').value) === true){ 
		document.payment.action="/salesmen/collectPayment/";						
	}else{
		return false;
	}
		
}

function showCheque(){
	var sel = document.getElementsByName('mode');
	var str = '';	
	for (var i=0; i<sel.length; i++){
		if (sel[i].checked == true){ 
			str = sel[i].value; 
		}
	}
	
	if(str == '2')
		$('chequeRadio').show(); 
	else
		$('chequeRadio').hide(); 	
}

</script>


<table width="100%"><tr><td><div style="width:100%;text-align:center;background:#ff0000;font-size:1.5em"><strong><span style="color:#ffffff">Pay</span><span style="color:#000000">1</span></strong></div></td><td width="90px"><a href="/salesmen/mainMenu">Main Menu</a></td></tr></table>
<form name="payment" method="POST" onSubmit="return collectPayment('<?php echo $RMobile; ?>','<?php echo $Flag; ?>','<?php echo $typeMD; ?>',parseInt('<?php echo $pending; ?>'));">
<h3><?php echo $RShop." - ".$typeMD; ?></h3>
<input type="hidden" id="shopName" value="<?php echo $RShop; ?>" />
<input type="hidden" name="rMobile" id="rMobile" value="<?php echo $RMobile; ?>" />
<input type="hidden" name="type" id="type" value="<?php echo $typeMD; ?>" />
<input type="hidden" name="flag" id="flag" value="<?php echo $Flag; ?>" />

<?php 
if(isset($STU)){
	if($STU==0)
		echo "Pending set-up amount is <b>".$pending . "</b>";
	else
		echo "Pending top-up amount is <b>".$pending . "</b>";
}
?>

<table style="margin-top:10px"> 
<?php if(isset($Flag))
	{
		$varMobile=$RMobile;
			
		if($Flag==2)
		{
			$varAmount=$Amount;
		}
		else 
		{
			$varAmount='';
		}
	}
	
	if(isset($topUpAmount))
		$varAmount=$topUpAmount;
		
echo '<tr><td>Mobile</td><td>'.$varMobile.'</td></tr>';
echo '<tr><td>Amount</td><td><input type="number" name="amount" id="amount" value="'.$varAmount.'"/></td></tr>';
?>

<tr>
	<td>Mode</td>
	<td>
		<label><input type='radio' name='mode' id='mode1' checked value='1' onChange="showCheque()"/>Cash </label></br>
		<label><input type='radio' name='mode' id='mode2'  value='2' onChange="showCheque()"/>Cheque</label></br>	
	</td>
</tr>
<tr id="chequeRadio" style="display:none;">
	<td>Cheque No</td>
	<td><input type="number" name="chequeNumber" id="chequeNumber" value=""/></td>
</tr>
<tr>
	<td>Bill Number</td>
	<td><input type="number" name="billBookNo" id="billBookNo" value=""/></td>
</tr>	
	
<tr><td>&nbsp;</td>
	<td>
		<input type="submit" value="Collect" />
	</td>
</tr>
</table>
</form>
<script>
$('amount').focus();
setRadio('mode');
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