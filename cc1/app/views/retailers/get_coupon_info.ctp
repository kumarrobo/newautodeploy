<style type="text/css">
.taggLinkBG1 {background-color: #FF8800 !important;}
.success {
background: #FFCC00;
padding: 2px 5px;
}
.error{
font-size: 1em;
background: red;
padding: 2px 5px;
color: white;
}
</style>

<div id="innerDiv" align="center" style="margin-top:10px;">
	<fieldset style="padding:0px;border:0px;margin:0px;">
		<div>
			<span style="font-weight:bold;">Coupon Code: </span><input id="inp1" type="text" style="margin-left:10px; width: 100px;" maxlength="10" value="<?php if(isset($code)) echo $code;?>"> <span style="margin-left:10px;margin-right:10px;">|</span> <span style="font-weight:bold">Serial Number</span><input type="text" id="inp2" style="margin-left:10px; width: 100px;" maxlength="10" value="<?php if(isset($serial)) echo $serial;?>">
			<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findInfo();"></span>
		</div>
		<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please enter code or a serial number</span></div>
		<div class="appTitle" style="margin-top:20px;">Coupon Details</div>
		<?php if(!empty($data)) { $data = $data[0];?>
		<table width="50%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
		      <tr>
		      	<td><b>Code</b></td>
		      	<td><?php echo $data['coupons']['code'];?></td>
		      </tr>
		      <tr>
		      	<td><b>Serial Number</b></td>
		      	<td><?php echo $data['coupons']['serialNumber'];?></td>
		      </tr>
		      <tr>
		      	<td><b>Product</b></td>
		      	<td><?php echo "<a href='/retailers/product/" . $data['coupons']['product_id'] . "'>" . $data['products']['name'] . "</a>";?></td>
		      </tr>
		      <tr>
		      	<td><b>Status</b></td>
		      	<td><?php if(empty($data['users']['mobile']))echo "Not Activated"; else echo "Activated on " . $data['retailers_coupons']['modified']; ?></td>
		      </tr>
		      <tr>
		      	<td><b>Dry Status</b></td>
		      	<td><?php if($data['coupons']['dry_flag'] == 3) echo "Card Reversed - Cannot be activated"; else if($data['coupons']['dry_flag'] == 2 || ($data['coupons']['dry_flag'] == 1 && empty($data['retailers']['shopname'])))echo "Dry Stock"; else echo "Not Dry Card"; ?></td>
		      </tr>
		      <?php if(!empty($data['users']['mobile'])) { ?>
		      <tr>
		      	<td><b>User Mobile</b></td>
		      	<td><a href="/groups/getUserInfo/<?php echo $data['users']['mobile'];?>"><?php echo $data['users']['mobile']; ?></a></td>
		      </tr>
		      <?php } ?>
		      <tr>
		      	<td><b>Allotted To Retailer</b></td>
		      	<td><?php if(!empty($data['retailers']['shopname'])) echo "<a href='/retailers/index/".$data['retailers']['id']."'>" . $data['retailers']['name'] . "<br/>" . $data['retailers']['shopname']. "</a>"; else "Unknown";?></td>
		      </tr>
		      <tr>
		      	<td><b>Allotted To Distributor</b></td>
		      	<td><?php if(!empty($data['distributors']['company'])) echo $data['distributors']['company'] . " (".$data['retailers_coupons']['allot_time'].")"; else "-";?></td>
		      </tr>
		      <tr>
		      	<td><b>Allotted To Master Distributor</b></td>
		      	<td><?php if(!empty($data['master_distributor']['company'])) echo "<a href='/retailers/masterDistributor/".$data['retailers_coupons']['masterdistributor_id']."'>" . $data['master_distributor']['company']. " (".$data['retailers_coupons']['created'].")</a>"; else "-";?></td>
		      </tr>
   		</table>
   		<?php } ?>
	</fieldset>
</div>
   			
<script>
function findInfo(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
	if($('inp1').value == "" && $('inp2').value == ""){
		$('date_err').show();
		$('submit').innerHTML = html;
	}
	else {
		$('date_err').hide();
		if($('inp1').value == "")code = -1;
		else code = $('inp1').value;
		
		if($('inp2').value == "")loc = "/" + code;
		else loc = "/" + code + "/" + $('inp2').value;
		window.location = "http://" + siteName + "/retailers/getCouponInfo" + loc;
	}
}
function showLoader3(id){
	$(id).innerHTML = '<span id="loader2" class="loader2" style="display:inline-block; width:50px">&nbsp;</span>';
}
</script>   			