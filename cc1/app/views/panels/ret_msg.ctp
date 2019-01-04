<script>

function setAction()
{
	
	
	var radioValue = '';
	for (index=0; index < document.retMsg.user.length; index++) {
		if (document.retMsg.user[index].checked) {
			radioValue = document.retMsg.user[index].value;
			break;
		}
	}

	if(radioValue == '') alert('Alert select whom to send message');
	
	if(radioValue == 'test'){
		
		
	}else if(radioValue == 'multi'){
		var tst = ''; 
		for (x=0;x<retMsg.multiRet.length;x++)
         {
            if (retMsg.multiRet[x].selected)
            {
            	tst = tst + retMsg.multiRet[x].value;
            }                     
         }
         
         if(tst == ''){
         	alert('Select at least one retailer'); return false;
         }         	
	}
	
	if($('message1').value.strip() == ''){
		$('message1').value = '';
		$('message1').focus();
		alert('Enter message'); return false;
	}
	//alert($('user').value);
	//document.retMsg.action="/panels/retMsg/1";
	document.retMsg.submit();
}

function fillMsg(){
	$('message1').value = $('template').value;
	resCharacters('message1',310,'charCount');
}

function resCharacters(id,limit,out){
		var str = $(id).value.length;
		$(out).innerHTML=str + '/' + limit + " chars";
		if(str > limit){
			alert('Message limit: '+limit+' chars only');
			$(id).value = $(id).value.substring(0,limit);
			str = $(id).value.length;
			$(out).innerHTML=str + '/' + limit + " chars";
			return false;
		}
	}
</script>

<form name="retMsg" method="POST" action="/panels/retMsg/" onSubmit="return setAction();">
<table border='0' cellpadding="2" cellspacing="5" >

<?php if(!empty($Error) && $Error != ''){ ?>
<tr>
	<td colspan="2"><span style="background-color: yellow;"><?php echo $Error; ?></span></td>
</tr>
<?php } ?> 
<tr>
	<td><input type="radio" name="user" value="test" id="user"  checked/></td><td>Testing Number</br><input type="text" name="testMobile" id="testMobile"></td>
</tr>

<!--<tr>
	<td><input type="radio" name="user"  id="user" value="multi"/></td>
	 <td>Select Multiple Retailers </br>
		<select multiple="multiple" name="multiRet[]" id="multiRet">
		
		<?php /* foreach($retailer as $ret){ 
		  //echo '<option value="'.$ret['retailers']['mobile'].'">'.$ret['retailers']['name'].' - '.$ret['retailers']['shopname'].' - '.$ret['retailers']['mobile'].'</option>';
		 } */?> 
		</select>
	</td> 
</tr>-->    
<tr>
	<td><input type="radio" name="user"  id="user" value="app_rotation"/></td>
	<td>APP retailers
	</td>
</tr>
<tr>
	<td><input type="radio" name="user"  id="user" value="ussd_rotation"/></td>
	<td>USSD retailers
	</td>
</tr>
<tr>
	<td><input type="radio" name="user"  id="user" value="sms_rotation"/></td>
	<td>SMS retailers
	</td>
</tr>
<tr>
	<td><input type="radio" name="user"  id="user" value="rotation"/></td>
	<td>All retailers
	</td>
</tr>

<tr>
	<td><input type="radio" name="user"  id="user" value="distributors"/></td>
	<td>All Distributor
	</td>
</tr>
<tr>
	<td><input type="radio" name="user"  id="user" value="pay1_salesmen"/><input type="hidden" name="salesmen_no"  id="salesmen_no" value="8879647661,8879647662,8879647663,8879647667,8879647673,9713055742"/></td>
        <td>Pay1 Salesmen
	</td>
</tr>

<tr>
	<td>Template</td>
	<td>
		<select id="template" onchange="fillMsg()">
		<option value=''>None</option>
		<?php foreach($templates as $tmp){ 
		  echo '<option value="'.$tmp['msg_templates']['msg'].'">'.$tmp['msg_templates']['name'].'</option>';
		 } ?> 
		 
		</select>
	</td>
</tr>

<tr>
	<td></td><td><textarea name="message1" id="message1" rows="10" cols="60" onKeyUp="resCharacters('message1',310,'charCount');"/></textarea>
	<span id='charCount' class="hints">Upto 310 chars</span>
	</td>
</tr>

<tr>
	<td></td><td><input type="button" value="Submit" onclick="setAction()"/></td>
</tr>
</table>
</form>
