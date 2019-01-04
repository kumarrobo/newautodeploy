<script>
function setAction()
{

	var retMobile=$('rMobNo').value.strip();
	var uMobile=$('uMobNo').value.strip();
	var uSubId=$('uSubId').value.strip();
	var transactionId=$('tran').value.strip();
	var pay1TransId=$('tran1').value.strip();
	var rShop=$('rShop').value.strip();
	var params1= '';
	var params2='';
	var url='';
	
	if(retMobile != '' || rShop != '')
	{
		if(retMobile != '' && rShop != '')
		{
		alert("Please enter either Retailer Mobile OR Retailer Shop Name. Not both.");
		return;
		}
	
		if(retMobile != '')
		{
			params1=retMobile;
			url="/panels/retInfo/"+params1+"/"+params2+"/"+$('from').value+"/"+$('to').value;
		
		}
		else 
		{
			//alert(rShop);
			params1=rShop;
			url="/panels/search/"+$('from').value+"/"+$('to').value+"/"+rShop;
			
		}	
	}
    else
     if(uMobile != '' || uSubId != '' )
	{
		//alert("In user info");
		if(uMobile != '')
		{
		
			params1=uMobile;
			url="/panels/userInfo/"+params1;
		}
		else
		{
			params1=uSubId;
			url="/panels/userInfo/"+params1+"/subid";
		}
	}
	else
	 if(transactionId != '' || pay1TransId != '')
	{
		if(transactionId != '' )
		{
		//alert(transactionId);
			params1=transactionId;
			url="/panels/transaction/"+params1;
		}
		else
		{
			params1=pay1TransId;
			url="/panels/transaction/"+params1+"/1";
		}
	}

	document.searchInfo.action=url;
	document.searchInfo.submit();
	
}

function getInformation(){
	var retMobile = $('rMobNo').value.strip();
	var uMobile = $('mobno').value.strip();
	var uSubId = $('subid').value.strip();
	var transactionId = $('pay1Tran').value.strip();
	var pay1TransId = $('vendTran').value.strip();
	var rShop = $('rShop').value.strip();
	var params1= '';
	var params2='';
	var url='';

	if(retMobile != '' || rShop != '')
	{
		if(retMobile != '' && rShop != '')
		{
		alert("Please enter either Retailer Mobile OR Retailer Shop Name. Not both.");
		return;
		}
	
		if(retMobile != '')
		{
			params1=retMobile;
			url="/panels/retInfo/"+params1+"/"+params2+"/"+$('from').value+"/"+$('to').value;
		
		}
		else 
		{
			//alert(rShop);
			params1=rShop;
			url="/panels/search/"+$('from').value+"/"+$('to').value+"/"+rShop;
			
		}	
	}
    else
     if(uMobile != '' || uSubId != '' )
	{
		//alert("In user info");
		if(uMobile != '')
		{
		
			params1=uMobile;
			url="/panels/userInfo/"+params1;
		}
		else
		{
			params1=uSubId;
			url="/panels/userInfo/"+params1+"/subid";
		}
	}
	else
	 if(transactionId != '' || pay1TransId != '')
	{
		if(transactionId != '' )
		{
		//alert(transactionId);
			params1=transactionId;
			url="/panels/transaction/"+params1;
		}
		else
		{
			params1=pay1TransId;
			url="/panels/transaction/"+params1+"/1";
		}
	}

	document.searchInfo.action=url;
	document.searchInfo.submit();
}
</script>

<!--  
<form name="searchInfo" method="POST" onSubmit="setAction()">
<table cellspacing="30" cellpadding="0">
		<tr>
			<td valign="top">
				Retailer Mobile No : <input type="text" name="rMobNo" id="rMobNo"  value="<?php if(isset($rMobNo))echo $rMobNo;?>" />
				OR</br>
				Retailer Shop Name  : <input type="text" name="rShop" id="rShop"  value="" />
			</td>
			<td valign="top">			
				<input type="hidden" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from','close=true')" value="<?php if(!empty($from))echo $from;?>" /><br />
				<br />   <input type="hidden" name="to"   id="to" onmouseover="fnInitCalendar(this, 'to','close=true')"   value="<?php if(!empty($to))echo $to;?>" />
			</td>
		
			<td valign="top">
				Customer Mobile No: <input type="text" name="uMobNo" id="uMobNo" value="" />		
				OR</br>
				Customer Subscriber Id: <input type="text" name="uSubId" id="uSubId" value="" />
			</td>
			<td valign="top">
				Pay1 Transaction No: <input type="text" name="tran" id="tran" value="" />
				OR</br>
				Vendor Transaction No : <input type="text" name="tran1" id="tran1" value="" />			
			</td>
			<td>
				<input type="submit" value="Submit" onclick="setAction()"/>
			</td>
	</tr>	
	</table>
</form>

	
<form name="searchInfo" method="POST" onSubmit="getInformation();">
	<table cellpadding="4" style="width:100%;">
		<tr>
			<td style="text-align:right;">
				Retailer Mobile No: 
			</td>
			<td style="text-align:left;">
				<input type="text" name="rMobNo" id="rMobNo"  value="<?php if(isset($rMobNo))echo $rMobNo;?>">
			</td>
			<td style="text-align:right;">
				Customer Mobile No: 
			</td>
			<td style="text-align:left;">
				<input type="text" name="mobno" id="mobno" value="<?php if(isset($mobno))echo $mobno;?>"/>
			</td>
			<td style="text-align:right;">
				Pay1 Transaction No: 
			</td>
			<td style="text-align:left;">
				<input type="text" name="pay1Tran" id="pay1Tran">
			</td>
		</tr>	
		<tr>
			<td style="text-align:right;">
				Retailer Shop Name: 
			</td>
			<td style="text-align:left;">
				<input type="text" name="rShop" id="rShop"  value="">
			</td>
			<td style="text-align:right;">
				Customer Subscriber Id: 
			</td>
			<td style="text-align:left;">
				<input type="text" name="subid" id="subid" value="<?php if(isset($subid))echo $subid;?>" />
			</td>
			<td style="text-align:right;">
				Vendor Transaction No: 
			</td>
			<td style="text-align:left;">
				<input type="text" name="vendTran" id="vendTran">
			</td>
		</tr>
		<tr>
			<td></td><td></td>
			<td style="text-align:right;">
				<input type="submit" value="Submit" onclick="getInformation();">
			</td>
			<td></td><td></td><td></td>
		</tr>
	</table>
</form>-->
<?php echo $this->element('cc_search'); ?>
<div>
	<?php
	if(isset($retailerShopResults))
	{
	echo "<table cellspacing='0' cellpadding='0' border='1'>";
	echo "<tr><th>Retailer Shop Name</th>";
	echo "<th>Retailer Mobile</th></tr>";
	
	foreach($retailerShopResults as $rs)
		{
			echo "<tr>";		
			echo "<td><a href='/panels/retInfo/".$rs['retailers']['mobile']."'>".$rs['retailers']['shopname']."</a></td>";
			echo "<td>".$rs['retailers']['mobile']."</td>";	
			echo "</tr>";
		}	
	echo "</table>";
	}

	?>	
</div>







