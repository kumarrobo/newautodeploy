<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<style>

.new-retailer{
	background-color: rgba(255, 0, 0, 0.2);
}

.complaintButton {
        padding: 5px 10px !important;
        border: 1px solid #000 !important;
        border-radius: 3px;
        color: #fff;
        font-weight: 600;
        background-color: #3883c3;
}

.complaintButton:hover {
        padding: 5px 10px !important;
        border: 1px solid #000 !important;
        border-radius: 3px;
        color: #3883c3;
        font-weight: 600;
        background-color: #fff;
}

</style>

<script>
/*
function setAction()
{
	document.tranReport.action="/panels/transaction/"+$('tran').value;
	document.tranReport.submit();
}

function setAction1()
{
	document.tranReport1.action="/panels/transaction/"+$('tran1').value+"/1";
	document.tranReport1.submit();
}
function setActionP()
{
	document.tranReportP.action="/panels/transaction/"+$('ptran').value+"/2";
	document.tranReportP.submit();
}


/*function addComment(userMobile,transactionId,retMobile,retId)
{
    tagReversals(userMobile,transactionId,retMobile,0);

	var reason=$('commentAreaForTransaction').value;
	var len=reason.length;
	//var index=reason.indexOf('#');
	index = -1;
		var temp = reason.substring(1);

		if(index!=-1)
			{
				if(temp=="")
				{
					alert("Tag name cannot be blank.");
					return;
				}

				var url1='/panels/tagTransaction/1';
				var pars1="tagName="+encodeURIComponent(reason)+"&tagFor="+transactionId+"&retMobile="+retMobile;

				var myAjax= new Ajax.Request(url1,{method: 'post',parameters:pars1,
				onSuccess:function(transport)
			   		{
			   			var html=transport.responseText;
						//	$("tags1").innerHTML += temp;
						$('commentAreaForTransaction').value= "";
			   		}
			 	});
			}

		else
			{
				var url = '/panels/addComment';
				var pars   = "transId="+transactionId+"&text="+encodeURIComponent($('commentAreaForTransaction').value)+"&userMobile="+userMobile+"&retId="+retId;

				var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
					{
						var html = transport.responseText;
						var text1=html.split("==") + "<br />";
						Element.insert('asdf',{top:text1});
						$('commentAreaForTransaction').value = "";

					}
				});
			}
}

function tagReversals(userMobile,transactionId,retMobile,callFrom)
{
		var sel='';
		if(callFrom==0) //callFrom function addComment()
			var tagName = $('tagDDD').value;
		else //call from requestReversal()
			var tagName = $('tagDDD1').options[sel.selectedIndex].value;;

		if(tagName=='none')
			return;

		var url='/panels/tagTransaction/3';
			var pars="tagName="+tagName+"&tagFor="+transactionId+"&retMobile="+retMobile;

			var myAjax= new Ajax.Request(url,{method: 'post',parameters:pars,
			onSuccess:function(transport)
			   {
			   var html=transport.responseText;
			//	$("tags1").innerHTML += temp;
			//	$('commentAreaForTransaction').value= "";
			   }
			 });
}



function takeReversal(mobile, id){
	var r=confirm("Click 'Ok' to take reversal request");
	if (r==true)
	{
		var url = '/panels/regReversal';
		var pars   = "id="+id+"&mobile="+mobile;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{
						if(transport.responseText== 'success')
						window.location.reload();
					}
				});
	}else return;
}

function openTransaction(id,shopid){
	var r=confirm("Click 'Ok' to confirm");
	if (r==true)
	{
		var url = '/panels/openTransaction';
		var pars   = "id="+id+"&shopid="+shopid;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{
						if(transport.responseText== 'success')
						window.location.reload();
						else alert('Sorry transaction cannot be opened');
					}
				});
	}else return;
}

function pullback(id){
	var r=confirm("Click 'Ok' to confirm");
	if (r==true)
	{
		var url = '/panels/pullback';
		var pars   = "id="+id;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{
						if(transport.responseText== 'success')
						window.location.reload();
						else alert(transport.responseText);
					}
				});
	}else return;
}

function requestReversal(flag,tId,userMobile,retMobile, retId)
{
var sendsms = 0;
if($('sendSMS') && $('sendSMS').checked === true)
sendsms = 1;

//tagReversals(userMobile,tId,retMobile,1);
var cfm='';
if(flag==1)
	cfm='reverse';
else
	cfm='decline';
var r=confirm("Are you sure you want to "+cfm+" the transaction?");

if (r==true)
{
    var reason=$('reason').value;


	var newUrl = '';
	if(flag==1){
		newUrl='/panels/reverseTransaction/'+tId;
	}else{
		newUrl='/panels/reversalDeclined/'+tId+'/'+sendsms;
	}

	var pars='';

	var myAjax = new Ajax.Request(newUrl, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{

					var html1 = transport.responseText;
					//alert(html1);
					$('reason').value = "";
					var pars1   = "tId="+tId+"&reason="+encodeURIComponent(reason)+"&userMobile="+userMobile+"&flag="+flag+"&retId="+retId+"&retMobile="+retMobile;
					var url1='/panels/updateCommentsForReversal';


					var myAjax = new Ajax.Request(url1, {method: 'post', parameters: pars1,
						onSuccess:function(transport)
						{
							var html = transport.responseText;
							$('a123').value=html;
							Element.insert('asdf',{top:html});
						    window.location.reload( true );
						}
					});
				}
			});

}else return;
}*/

function updateCallComplain(complaint_id) {
    var val = $('hidden').value;

    var myAjax = new Ajax.Request('/panels/updateCallComplain', {method: 'post', parameters: "takenby="+val+"&complaint_id="+complaint_id,
                        onSuccess: function(e)
                        {
                                $('hidden').value = e.responseText;
                        }
                });
}

</script>
<?php echo $this->element('cc_search'); ?>
<!--
<br />
<table>
	<tr>
		<td>
			<form name="tranReport" method="POST" onSubmit="setAction()">
				Pay1 Transaction No: <input type="text" name="tran" id="tran"
					value="<?php if(isset($tran))echo $tran;?>" /> <input type="button"
					value="Submit" onclick="setAction()" />
			</form>
		</td>
		<td>
			<form name="tranReport1" method="POST" onSubmit="setAction1()">
				Vendor Transaction No : <input type="text" name="tran1" id="tran1"
					value="<?php if(isset($tran1))echo $tran1;?>" /> <input
					type="button" value="Submit" onclick="setAction1()" />
			</form>
		</td>
		<td>
			<form name="tranReportP" method="POST" onSubmit="setActionP()">
				Partner Transaction No: <input type="text" name="ptran" id="ptran"
					value="<?php  if(isset($ptran))echo $ptran;?>" /> <input
					type="button" value="Submit" onclick="setActionP()" />
			</form>
		</td>
	</tr>
</table>
</br>
-->

<div id="a123"></div>

<div id="tags1" style="color: #00FF00"></div>
<div id="tags" style="color: #00FF00">
	<table>
		<tr>
<?php
// if (! empty ( $tags ))
// 	foreach ( $tags as $t ) {
// 		echo "<td bgcolor='#A4D3EE'><a href='/panels/tags/" . $t ['t'] ['name'] . "'>" . $t ['t'] ['name'] . "</td>";

// 		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
// 	}
?>

</tr>
	</table>
</div>
</br>
<!-- tagging Nisha end -->
</div>


<?php if(isset($tran)){ ?>
<font size="5" style="color: #ff0000"><strong><?php echo $individualTransaction[0]['services']['name']; ?></strong></font>
</br>
</br>
<table id="userTransactions" border="1" cellpadding="2" cellspacing="0"
	width="100%">
	<tr valign="top">
		<th width="100px">Ret Name / Mobile / Shop Name</th>

		<!-- <th>Product</th> -->
		<th>Provider / ref_id</th>
		<th>Operator Id</th>
		<th>Customer </br>Number
		</th>
		<th>Operator</th>
		<th>Subscriber Id</th>
		<th>Amt</th>
		<th>Cause</th>

		<!--	<th>Internal Status</th>
  			<th>Provider Response</th> -->
		<th>Previous Status</th>
		<th>Current Status</th>
		<th>Complaint Turnaround Time / Tag</th>
		<th>Time</th>
<!-- 		<th width="200px">Action</th> -->
		<!--	<th>Reason for reversal</th> -->


	</tr>
<?php


	foreach ( $individualTransaction as $d ) {

		if (in_array($d['va']['retailer_id'], $retailerData)) {

			$classname = 'new-retailer';
		} else {
			$classname = '';
		}
		if (strcmp ( $d ['r'] ['name'], '' ) != 0) {
			$retailerLink = $d ['r'] ['name'];
		} else {
			$retailerLink = $d ['r'] ['mobile'];
		}

		if ($d ['services'] ['id'] == 2 || $d ['services'] ['id'] == 6) {
			$subId = $d ['va'] ['param'];
		} else {
			$subId = 'N.A.';
		}

		$retMobile = $d ['r'] ['mobile'];
		echo "<tr class ='".$classname."'>";
		echo "<td><a href='/panels/retInfo/" . $d ['r'] ['mobile'] . "'>" . $d ['r'] ['mobile'] . " </a> / </br>" . $d ['r'] ['shopname'] . "</td>";

		echo "<td> <a target='_blank' href='/recharges/tranStatus/" . $tran . "/" . $d ['vendors'] ['shortForm'] . "/" . $d ['va'] ['date'] . "/" . $d ['va'] ['vendor_refid'] . "'>Status</a> <br />" . $d ['vendors'] ['company'] . "<br/>/" . $d ['va'] ['vendor_refid'] . "</td>";
		echo "<td>" . $d ['va'] ['operator_id'] . "</td>";
		echo "<td> &nbsp; <a href='/panels/userInfo/" . $d ['va'] ['mobile'] . "'>" . $d ['va'] ['mobile'] . "</a> &nbsp; </td>";
		echo "<td>" . $d ['p'] ['name'] . "</td>";
		if ($subId != 'N.A') {
			echo "<td>&nbsp; <a href='/panels/userInfo/" . $subId . "/subid'>" . $subId . "</a> &nbsp;</td>";
		} else {
			echo "<td>" . $subId . "</td>";
		}
		echo "<td>" . $d ['va'] ['amount'] . "</td>";
		echo "<td>" . $d ['va'] ['cause'] . "</td>";
		$pss = '';
		if ($d ['va'] ['prevStatus'] == '0') {
			$pss = 'In Process';
		} else if ($d ['va'] ['prevStatus'] == '1') {
			$pss = 'Successful';
		} else if ($d ['va'] ['prevStatus'] == '2') {
			$pss = 'Failed';
		} else if ($d ['va'] ['prevStatus'] == '3') {
			$pss = 'Reversed';
		} else if ($d ['va'] ['prevStatus'] == '4') {
			$pss = 'Reversal In Process';
		} else if ($d ['va'] ['prevStatus'] == '5') {
			$pss = 'Reversal declined';
		}
		echo "<td>" . $pss . "</td>";

		// echo "<td>".$objShop->error($d['vm']['internal_error_code'])."</td>";
		// echo "<td>".$d['vm']['response']."</br>".$d['va']['status']."</td>";

		$ps = '';
		if ($d ['va'] ['status'] == '0') {
			$ps = 'In Process';
		} else if ($d ['va'] ['status'] == '1') {
			$ps = 'Successful';
		} else if ($d ['va'] ['status'] == '2') {
			$ps = 'Failed';
		} else if ($d ['va'] ['status'] == '3') {
			$ps = 'Reversed';
		} else if ($d ['va'] ['status'] == '4') {
			$ps = 'Reversal In Process';
		} else if ($d ['va'] ['status'] == '5') {
			$ps = 'Reversal declined';
		}

		if (empty ( $d ['users'] ['id'] )) {
			$ps = '<a alt="System" title="System">' . $ps . '</a>';
		} else if (empty ( $d ['users'] ['name'] )) {
			$ps = '<a alt="User: ' . $d ['users'] ['id'] . '" title="User: ' . $d ['users'] ['id'] . '">' . $ps . '</a>';
		} else {
			$ps = '<a alt="' . $d ['users'] ['name'] . '" title="' . $d ['users'] ['name'] . '">' . $ps . '</a>';
		}

		$revReq = '';
		if ($d [0] ['resolve_flag'] != '0') {
			// $revReq = '<a href="javascript:void(0)" onclick="takeReversal(\''.$d['r']['mobile'].'\',\''.$d['va']['id'].'\')">Take complaint</a>';
		}

		$status = '';
		if($d['va']['status'] == '0'){
			$status = 'In Process';
		}else if($d['va']['status'] == '1'){
			$status = 'Successful';
		}else if($d['va']['status'] == '2'){
			$status = 'Failed';
		}else if($d['va']['status'] == '3'){
			$status = 'Reversed';
		}else if($d['va']['status'] == '4'){
			$status = 'Reversal In Process';
		}else if($d['va']['status'] == '5'){
			$status = 'Reversal declined';
		}

		$resolve_factor = $d ['0'] ['count_resolve_flag'] ? floor($d ['0'] ['resolve_flag'] / $d ['0'] ['count_resolve_flag']) : $d ['0'] ['resolve_flag'];

     	$status_icon = 'icon_caution.png';
     	$icon_complaint = 'resend.png';
     	if(in_array($d ['va']['status'], array(0)))
     		$status_icon = "hourglass.png";
     	if(in_array($d['va']['status'], array(1, 4, 5)))
			$status_icon = "green-tick.png";

     	$complaint_status = '';
     	if (strlen($resolve_factor) > 0 && $resolve_factor == 0){
     		$icon_complaint = "hourglass.png";
     		$complaint_status = 'Complaint pending';
     	}
     	else if(strlen($resolve_factor) > 0 && $resolve_factor == 1){
     		$icon_complaint = "doubletick.png";
     		$complaint_status = 'Complaint resolved';
     	}

		$reversalStats = "<img title='".$status."' style='max-height:15px;' src='/img/".$status_icon."' />&nbsp;&nbsp;&nbsp;";
		if($icon_complaint == 'resend.png'){
// 			$reversalStats .= "<img id='icon_complaint' src='/img/".$icon_complaint."' style='max-height:15px;cursor:pointer' onclick='check_complaint(".$data['va']['id'].");' />";
		}
		else{
			$reversalStats .= "<img title='".$complaint_status."' id='icon_complaint' src='/img/".$icon_complaint."' style='max-height:15px;' />";
		}

		echo "<td style='text-align:center'>" . $reversalStats . "</td>";
		echo "<td>" . $d ['0'] ['tat'] ."<br/>".$commentsResult[0]['t']['name'] ."</td>";
		echo "<td>" . $d ['va'] ['timestamp'] . "</td>";

// 		if ($d ['va'] ['status'] == '0' || $d ['va'] ['status'] == '1' || $d ['va'] ['status'] == '4') {
// 			echo "<td height='100' id='box'> <textarea class='input textarea' id='reason' style='height: 60px; width: 150px; line-height: 1.5em;
// 						font-family: Arial,Helvetica,sans-serif; font-size: 14px; direction: ltr;' autocomplete='off'></textarea></br>";
// 			echo '<select name="tagDDD1" id="tagDDD1">';
// 			foreach ( $reversalDDD as $k => $tr ) {
// 				echo "<option value='" . $tr ['taggings'] ['id'] . "'>" . $tr ['taggings'] ['name'] . "</option>";
// 			}
// 			echo "</select>";
// 			echo "</br>";

// 			echo "<input type='button' value='Reverse' onClick='requestReversal(\"1\",\"" . $d ['va'] ['ref_code'] . "\",\"" . $d ['va'] ['mobile'] . "\",\"" . $d ['r'] ['mobile'] . "\"," . $d ['r'] ['id'] . ")' />";
// 			if ($d [0] ['resolve_flag'] == '0') {
// 				echo " OR <br /> <label><input type='checkbox' name='sendSMS' id='sendSMS' value='1' checked > Send SMS </label><input type='button' value='Decline' onClick='requestReversal(\"0\",\"" . $d ['va'] ['ref_code'] . "\",\"" . $d ['va'] ['mobile'] . "\",\"" . $d ['r'] ['mobile'] . "\")' />";
// 			}
// 			echo "</td>";
// 		} else if ($d ['va'] ['status'] == '5' && $confirm_flag == 1) {
// 			echo "<td><input type='button' value='Open Transaction' onClick='openTransaction(" . $d ['va'] ['id'] . "," . $d ['va'] ['shop_transaction_id'] . ")' /> </td>";
// 		} else {
// 			echo "<td><input type='button' value='Pull Back' onClick='pullback(" . $d ['va'] ['id'] . ")' /> </td>";
// 		}
	}
	?>

</table>

</br>
</br>

<table border="0" width="100%" class="<?php echo $classname;?>">
	<tr>
		<td valign="top">
			<table border="1" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<th>Ref Code</th>
					<th>Vendor</th>
					<th>Vendor Id</th>
					<th>Internal Response</th>
					<th>Provider Response</th>
					<th>Status</th>
					<th>Timestamp</th>
					<th>Processing Time</th>
				</tr>

			<?php
	foreach ( $detailedTransaction as $d ) {
		$vendor = strtoupper ( $d ['vendors'] ['shortForm'] );

		echo "<tr>";
		echo "<td>" . $d ['vm'] ['va_tran_id'] . "</td>";
		echo "<td>" . $vendor . "</td>";
		echo "<td>" . $d ['vm'] ['vendor_refid'] . "</td>";
		echo "<td>" . $objShop->errors ( $d ['vm'] ['internal_error_code'] ) . "</td>";
		echo "<td>" . $d ['vm'] ['response'] . "</td>";
		echo "<td>" . $d ['vm'] ['status'] . "</td>";
		echo "<td>" . $d ['vm'] ['timestamp'] . "</td>";
		echo "<td>" . $d ['vm'] ['processing_time'] . "</td>";
		echo "</tr>";
	}
	?>
			</table>
        <?php if((!in_array($individualTransaction[0]['va']['retailer_id'], $retailerData)) && (empty($individualTransaction[0]['c']['takenby']))) { ?>
        <br /><br /><br /><div style="border: 2px solid grey; padding: 30px 0">* Mark it as Call Complain ? &nbsp;<input type="checkbox" onclick="updateCallComplain(<?php echo $individualTransaction[0]['c']['id']; ?>);" id="callComplain">Yes</div>
        <input type="hidden" id="hidden" value="<?php echo $individualTransaction[0]['c']['takenby'] == 0 ? $_SESSION['Auth']['User']['id'] : $individualTransaction[0]['c']['takenby']; ?>">
        <?php } ?>
        <?php if ($individualTransaction[0]['va']['vendor_id'] == BBPS_VENDOR) { ?>
        <br/><div style="border: 2px solid grey; padding: 30px 0">
            <div style="font-weight: bolder">BBPS Complain :-</div><br/>
            <div id='complaints'>
                <?php if (empty($individualTransaction[0]['bc']['id'])) { ?>
                    <div>
                        Reason : <select id='disposition'>
                            <option value='' selected disabled>Select From Below</option>
                            <option>Transaction Successful, account not updated</option>
                            <option>Amount deducted, biller account credited but transaction ID not received</option>
                            <option>Amount deducted, biller account not credited transaction ID not received</option>
                            <option>Amount deducted multiple times</option>
                            <option>Double payment updated</option>
                            <option>Erroneously paid in wrong account</option>
                            <option>Others, provide details in description</option>
                        </select>
                    </div><br/>
                    <div>
                        Description : <textarea id='description' cols='55' ></textarea>
                    </div><br/>
                    <input type='button' onclick="raiseComplaint();" value='Submit' class='complaintButton'>
                <?php } else { ?>
                    <div>Complaint Already Registered !!!</div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
		</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td width="50%">
			<div style="overflow:auto; max-height:300px;">
			<table id = "past_comments" width="100%" >
			<?php
		foreach ( $commentsResult as $comment ) {
			if($comment['u']['name']){
				echo "<tr bgcolor='#CEF6F5' style='border: 2px solid white'>";
				echo "<td><span style='font-size:12px;'>By ".$comment['u']['name'].
						" @ ".$comment['c']['created']." on ".$comment['c']['ref_code']." (".$comment['t']['name'].")</span></br>
						".$comment['c']['comments']."</td>";
				echo "</tr>";
			}
			?>

			 <?php } ?>
			 </table>
			</div>
			<style>
.form-font tr td {
	font-size: 14px;
}

ul.ws_drop_down {
	display: block;
	float: left;
	background-repeat: repeat;
	background-position: top;
}

ul.ws_drop_down li img {
	border: 0px;
	vertical-align: middle;
	padding: 1px
}

ul.ws_drop_down li {
	display: block;
	margin: 0px 0px 0px 0px;
	float: left;
}

ul.ws_drop_down a:hover ul, ul.ws_drop_down a:hover a:hover ul, ul.ws_drop_down a:hover a:hover a:hover ul
	{
	display: block;
}

ul.ws_drop_down li a {
	display: block;
	vertical-align: middle;
	text-decoration: none;
	text-align: left;
	font-size: 14px;
	line-height: 20px;
	padding: 2px 0px 2px 10px;
	margin: 0px;
	color: #666666;
	background-repeat: no-repeat;
	background-position: 75px center;
	width: 120px;
	outline: none;
}

ul.ws_drop_downm li a:hover, ul.ws_drop_downm li a {
	color: #000;
}

ul.ws_drop_down ul {
	position: absolute;
	left: -1px;
	top: 98%;
	background-color: #fff;
	margin: -2px 0px 0px 0px;
	border-bottom: 1px solid #7e9dba;
	border-left: 1px solid #7e9dba;
}

ul.ws_drop_down, ul.ws_drop_down ul {
	margin: 0px;
	list-style: none;
	padding: 0px;
}

ul.ws_drop_down a:active, ul.ws_drop_down a:focus {
	outline-style: none;
}

ul.ws_drop_down ul li {
	float: left;
	margin: 0px 0px 0px -1px;
}

ul.ws_drop_down ul a {
	white-space: nowrap;
	text-align: left; /*border-right: 1px solid #CCCCCC;*/
	border-left: 1px solid #7e9dba;
	border-top: 0px solid #7e9dba;
	width: 100px;
	background-image: none;
	padding: 3px 0px 3px 10px;
}

ul.ws_drop_down li:hover {
	position: relative;
}

ul.ws_drop_down li:hover>a {
	background-color: #fff;
	color: #a14209;
	text-decoration: none;
}

ul.ws_drop_down li a:hover {
	position: relative;
	background-color: #fff;
	color: #a14209;
	text-decoration: none;
}

ul.ws_drop_downm li a:hover {
	background-color: #f5db89;
}

ul.ws_drop_down ul, ul.ws_drop_down a:hover ul ul {
	display: none;
	z-index: 99999;
}

ul.ws_drop_down li:hover>ul {
	display: block
}
/* CSS for TABLE Tags for IE 6 and Lower START */
ul.ws_drop_down li a table, ul.ws_drop_down li a:hover table {
	border-collapse: collapse;
	margin: 0px 0px 0px 0px;
	border: 0px;
	padding: 0px;
}

ul.ws_drop_down li a table tr td, ul.ws_drop_down li a:hover table tr td
	{
	padding: 0px;
	border: 0px;
}

ul.ws_drop_down li a table ul, ul.ws_drop_down li a:hover table ul {
	border-collapse: collapse;
	padding: 0px;
	margin: 0px 0px 0px -1px;
}

ul.ws_drop_down table ul {
	left: 0px;
}

span.response_success:before {
	content: url('/img/green_circle_check14x14.png');
}

span.response_success {
	color: green;
	font-size: 12px;
	font-family: "Lucida Console", Monaco, monospace;
}

span.response_failure {
	color: #987107;
	font-size: 12px;
	font-family: "Lucida Console", Monaco, monospace;
}
</style>

<script>

var jQ = jQuery.noConflict();

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

function check_complaint(){
	if($('complaintToggle')){
		$('complaintToggle').checked = true;
		toggleTaT();
	}
}

function takeComplaintReversal(mobile, id){
	var turnaround_time = $('turnaround_time').value;
// 	var turnaround_time = $('tat_hr') + $('tat_min');
	var url = '/panels/regReversal';
	var pars   = "id="+id+"&mobile="+mobile+"&turnaroundTime="+turnaround_time;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport){
			jsonResponse = JSON.parse(transport.responseText);

			if(jsonResponse["status"] == 'success')
				$('response_complaint').addClassName('response_success');
			else{
				$('response_complaint').addClassName('response_failure');
				$('response_complaint').insert(jsonResponse["status"]);
				$('response_comments').insert("<br/>" + "code " + jsonResponse["code"] + ": " + jsonResponse["description"]);
			}
		}
	});
}

function toggleElement(thing, element){
	if(thing.checked){
		$(element).show();
		if(thing.id == 'action_decline')
			$('action_reverse').checked = false;
	}
	else
		$(element).hide();
}

function uncheckAction(thing, element){
	if(thing.checked){
		if($(element)){
			$(element).checked = false;
			if(element == 'action_decline')
				$('action_send_sms').hide();
		}
	}
}

function dropdownToggleNone(){
	if($('call_type').value != 'none'){
		$('default_tag').hide();
		$('other_tags').show();
	}
	else{
		$('other_tags').hide();
		$('default_tag').show();
	}
}

function reversalRequest(flag, tId, userMobile, retMobile, retId){
	var sendSMS = 0;
	var cfm = '';

	if($('send_sms') && $('send_sms').checked)
		sendSMS = 1;

	if(flag == 1)
		cfm='reverse';
	else
		cfm='decline';

    var reason = $('commentAreaForTransaction').value;
	var newUrl = '';
	var pars = '';
	var call_type = $('call_type').value;
	var tag = $('tag').value;
	console.log(tId);
	if(flag == 1)
		newUrl = '/panels/reverseTransaction/'+tId;
	else
		newUrl = '/panels/reversalDeclined/'+tId+'/'+sendSMS;

	var myAjax = new Ajax.Request(newUrl, {method: 'post', parameters: pars,
		onSuccess:function(transport){
			var html1 = transport.responseText;
			$('response_' + cfm).addClassName('response_success');
			$('response_' + cfm).insert(html1);
			if(sendSMS == 1)
				$('response_send_sms').addClassName('response_success');

		}
	});

	$('commentAreaForTransaction').value = "";
	var pars1 = "tId="+tId+"&reason="+encodeURIComponent(reason)+"&userMobile="+userMobile+"&flag="+flag+"&retId="+retId+"&retMobile="+retMobile+"&callTypeId="+call_type+"&tagId="+tag;
	var url1 = '/panels/updateCommentsForReversalNew';

	var myAjax = new Ajax.Request(url1, {method: 'post', parameters: pars1,
		onSuccess:function(transport){
			var html = transport.responseText;
			$('ajax_loader').hide();
			$('past_comments').insert({top:transport.responseText});
			$('response_submit').addClassName('response_success');
			$('response_submit').insert('<a href="javascript:window.location.href=document.URL"> Reload</a>');
		}
	});
}

function takeComment(userMobile, refCode, retMobile, retId){
	console.log(userMobile + ' ' + refCode + ' ' + retMobile + ' ' + retId);
	var comment = $('commentAreaForTransaction').value;
	var call_type = $('call_type').value;
	var tag = $('tag').value;

	if(comment == ''){
		alert('Give a valid comment');
		return false;
	}
	if(call_type == ""){
		alert("Select a call type");
		return false;
	}
	if(tag == ""){
		alert("Tag this call");
		return false;
	}
	$('submit_comment').hide();
	$('ajax_loader').show();
	if($('complaintToggle') && $('complaintToggle').checked){
		takeComplaintReversal('<?php echo $individualTransaction[0]['r']['mobile'] ?>', <?php echo $individualTransaction[0]['va']['id'] ?>);
	}
	if($('action_reverse')){
		if($('action_reverse').checked){
			reversalRequest(1, refCode, <?php echo $individualTransaction[0]['va']['mobile'] ?>, '<?php echo $individualTransaction[0]['r']['mobile'] ?>', <?php echo $individualTransaction[0]['r']['id'] ?>);
			return;
		}
		else if($('action_decline')){
			if($('action_decline').checked){
				reversalRequest(0, refCode, userMobile, retMobile);
				return;
			}
		}
	}

	if($('action_open_transaction') && $('action_open_transaction').checked){
		openTransactionNew(<?php echo $individualTransaction[0]['va']['id'] ?>, <?php echo $individualTransaction[0]['va']['shop_transaction_id'] ?>);
	}

	if($('action_pull_back') && $('action_pull_back').checked){
		pullbackNew(<?php echo $individualTransaction[0]['va']['id'] ?>);
	}

	var url = '/panels/addComment';
	var pars = "transId="+refCode+"&text="+encodeURIComponent($('commentAreaForTransaction').value)+"&userMobile="+userMobile+"&retId="+retId+"&callTypeId="+call_type+"&tagId="+tag;

	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport){
// 			var html = transport.responseText;
// 			var text1 = html.split("==") + "<br />";
			$('past_comments').insert({top:transport.responseText});
// 			Element.insert('past_comments',{top:text1});
			$('commentAreaForTransaction').value = "";
			$('ajax_loader').hide();
 			$('response_submit').addClassName('response_success');
			$('response_submit').insert('<a href="javascript:window.location.href=document.URL"> Reload</a>');
		}
	});

}

function pullbackNew(id){
	var url = '/panels/pullback';
	var pars = "id="+id;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport){
			if(transport.responseText == 'success')
				$('response_pull_back').addClassName('response_success');
			else{
				$('response_pull_back').addClassName('response_failure');
				$('response_pull_back').insert(transport.responseText);
			}
		}
	});
}

function openTransactionNew(id, shopid){
        var turnaround_time = $('turnaround_time').value;
	var url = '/panels/openTransaction';
	var pars = "id="+id+"&shopid="+shopid+"&turnaround_time="+turnaround_time;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport){
			if(transport.responseText== 'success')
				$('response_open_transaction').addClassName('response_success');
			else{
				$('response_open_transaction').addClassName('response_failure');
				$('response_open_transaction').insert(transport.responseText);
			}
		}
	});
}

function selectElement(element, name, id){
	$(element + '_selected').update(name);
	$(element).value = id;
	if(element == 'call_type')
		dropdownToggleNone();
}

function toggleTaT(){
	if($('complaintToggle').checked){
		$('turnaround_time_options').show();
		$('turnaround_time_text').show();
	}
	else{
		$('turnaround_time_options').hide();
		$('turnaround_time_text').hide();
	}
}

function createTag(){
	var tagName = prompt("Create a new tag", "New Tag");

	if (tagName != null) {
		$('add_tag_load').show();
		$('add_tag_plus').hide();
		var url = '/panels/createTag';
		var pars = "tagName="+tagName;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
			onSuccess:function(transport){
				$('add_tag_load').hide();
				$('add_tag_plus').show();
				if(transport.responseText == 'success')
					alert('Tag "' + tagName + '" created. Reload page to see it in the list');
				else
					alert('Tag "' + tagName + '" already exists');
			}
		});
	}
}

function selectTag(element){
	$("tag").value = element.value;
}

function selectTagType(type){
	if(type == "Retailer"){
		$("tag_retailer").enable();
		$("tag_customer").disable();
	}
	else{
		$("tag_retailer").disable();
		$("tag_customer").enable();
	}
	var options = $$('select#tag_customer option');
	var len = options.length;
	for (var i = 0; i < len; i++) {
		options[0].selected = true;
	}
	var options = $$('select#tag_retailer option');
	var len = options.length;
	for (var i = 0; i < len; i++) {
		options[0].selected = true;
	}
	$("tag").value = ""
}
</script>
			<div>
				<span id="response_comments" class="response_failure"></span>

				<table style="width: 600px;" class="form-font">
					<col style="width: 13%">
					<col style="width: 30%">
					<col style="width: 27%">
					<col style="width: 30%">
					<tr>
						<td colspan="2">
							<h4>Comments</h4>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<textarea class="input textarea" id="commentAreaForTransaction"
					style="height: 70px; width: 600px; line-height: 1.5em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; direction: ltr;"
					autocomplete="off"></textarea>
						</td>
					</tr>
					<tr style="height: 30px;">
						<td></td>
						<td>
							<div>
							<select name="call_type" id="call_type">
								<option value="" selected disabled>Select Call Type</option>
								<?php foreach($call_types as $key => $call_type): ?>
								<option value="<?php echo $call_type['cc_call_types']['id'] ?>"><?php echo $call_type['cc_call_types']['name'] ?></option>
								<?php endforeach; ?>
							</select>
							</div>
						</td>
							<td style="text-align:right;">
								<input type="radio" name="tag_radio" onchange="selectTagType('Retailer')" checked> <br/>
								<input type="radio" name="tag_radio" onchange="selectTagType('Customer')" >
							</td>
							<input type="hidden" id="tag" name="tag" value="">
		<!-- 	 <td style="text-align:right;"><span title="Create a new tag" style="font-size:large;cursor:pointer;" onclick="createTag();"><span id="add_tag_plus">+</span><img src="/img/ajax-loader-bounce.gif" id="add_tag_load" style="display:none;"/></span> Tag:</td> -->
						<td style="text-align:left;">
							<div>

							<select id="tag_retailer" onchange="selectTag(this);" >
								<option value="" selected disabled>Resolution Tags</option>
								<?php foreach($taggings as $k => $tag): ?>
		    					<?php if($tag['taggings']['type'] == 'Resolution'): ?>
		    					<option value="<?php echo $tag['taggings']['id'] ?>"><?php echo $tag['taggings']['name'] ?></option>
		    					<?php endif ?>
		    					<?php endforeach; ?>
							</select>

							<select id="tag_customer" onchange="selectTag(this);" disabled>
								<option value="" selected disabled>CC Tags</option>
		    					<?php foreach($taggings as $k => $tag): ?>
		    					<?php if($tag['taggings']['type'] == 'Customer'): ?>
		    					<option value="<?php echo $tag['taggings']['id'] ?>"><?php echo $tag['taggings']['name'] ?></option>
		    					<?php endif ?>
		    					<?php endforeach; ?>
							</select>
							</div>
						</td>
					</tr>
<?php if(!(strlen($resolve_factor) > 0 && $resolve_factor == 0)): ?>
<tr style="height: 30px;">
						<td></td>
						<td><input type="checkbox" onchange="toggleTaT();"
							id="complaintToggle" /> Complaint <span id="response_complaint"></span></td>
						<td style="text-align: right;"><span id="turnaround_time_text"
							style="display: none;">Turnaround Time:</span></td>
						<td id="turnaround_time_options" style="display: none;">
							<div>
<!--							<select id="tat_hr">
								<?php for($i = 0; $i < 25; $i++): ?>
								<option value="<?php echo $i ?>"><?php if($i < 10){ echo "0".$i; }else{ echo $i; } ?></option>
								<?php endfor ?>
								<option value="48">48</option>
							</select> Hr :
							<select id="tat_min">
								<option value="0">00</option>
								<option value="0.5">30</option>
							</select> Min  -->
							<select id="turnaround_time">
								<?php foreach($turnaround_time as $tat): ?>
								<option value="<?php echo $tat ?>">Up to <?php echo $tat ?> Hrs</option>
								<?php endforeach ?>
							</select>
							</div></td>
					</tr>

<?php endif; ?>

<tr style="height:30px;">
<td style="text-align:right;">Action:</td>
<?php if($individualTransaction[0]['va']['status']=='0' || $individualTransaction[0]['va']['status']=='1' || $individualTransaction[0]['va']['status']== '4'): ?>
<td><input type="checkbox" id="action_reverse" onchange="uncheckAction(this, 'action_decline')"/> Reverse <span id="response_reverse"></span></td>
<?php if(strlen($resolve_factor) > 0 && $resolve_factor == 0): ?>
<td><input type="checkbox" id="action_decline" onchange="toggleElement(this, 'action_send_sms');"/> Decline <span id="response_decline"></span></td>
<td><span id="action_send_sms" style="display:none;"><input type="checkbox" id="send_sms" checked /> Send SMS <span id="response_send_sms"></span></span></td>
<?php endif; ?>
<?php elseif($individualTransaction[0]['va']['status']=='5' && $confirm_flag == 1): ?>
<td><input type="checkbox" id="action_open_transaction"/> Open Transaction <span id="response_open_transaction"></span></td>
<?php else: ?>
<td><input type="checkbox" id="action_pull_back"/> Pull Back <span id="response_pull_back"></span></td>
<?php endif; ?>
</tr>
					<tr>
						<td style="text-align: right;"></td>
					</tr>

					<tr>
						<td colspan="4">
							<img src="/img/ajax-loader-1.gif"
							id="ajax_loader" style="display: none;" /> <input
							id="submit_comment" type="button" value="Submit"
							onClick="takeComment('<?php echo $individualTransaction['0']['va']['mobile'] ;?>','<?php echo $individualTransaction['0']['va']['txn_id'];?>','<?php echo $retMobile; ?>',<?php echo $individualTransaction['0']['r']['id']; ?>);">
							<span id="response_submit"></span>
						</td>
					</tr>
				</table>



			</div>
		</td>
	</tr>
</table>

<?php }
    if(isset($partnerLog) && $partnerLog){
        echo $this->element('partner_trans_status',array('partnerLog',$partnerLog));
    }
?>
<?php if(count($userData) > 0){ ?>
                        <table class="table table-bordered table-hover" style="margin-top:1.5em">
			<tr><td colspan="13" align="center" class="appTitle">SmartPay Transaction Information</td></tr>
                        <tr class="bg-info">
			<th>Pay1 Txn ID </th>
                                                                                <th>Vendor/RefId</th>
                                                                                <th>Customer Mobile</th>
                                                                                <th>Auth Code</th>
                                                                                <th>Service Type</th>
                                                                                <th>Mode</th>
                                                                                <!--<th>Card Number</th>-->
                                                                                <th>Amount</th>
                                                                                <th>Txn Status</th>
			<th>Settlement Status</th>
                                                                                <th>Time/Date</th>
			<th>Settlement Timestamp</th>
			<th>Receipt URL</th>
			</tr>
			<?php
                                                                                $services = Configure::read('services');
                                                                                $service_type = Configure::read('service_type');
			foreach($userData as $user){
                                                                                                           if($user['service_id']==8)
                                                                                                           {
                                                                                                               if($user['product_id']==$service_type[8]['MPOS Withdrawal : Non VISA'])
                                                                                                               {
                                                                                                                   $txn_type="CW - DD : Non VISA";
                                                                                                               } else if ( $user['product_id']==$service_type[8]['MPOS Withdrawal : VISA'] ){
                                                                                                                   $txn_type="CW - DD : VISA";
                                                                                                               }
                                                                                                               elseif( ($user['product_id']==$service_type[8]['Sale - CC : EMI']) || ($user['product_id']==$service_type[8]['Sale - CC']) || ($user['product_id']==$service_type[8]['Sale - DC']))
                                                                                                               {
                                                                                                                    $cardtype = '--';
                                                                                                                    if( strtolower($user['payment_card_type']) == "debit" ){
                                                                                                                        $cardtype = "DC";
                                                                                                                    } else if( strtolower($user['payment_card_type']) == "credit" ){
                                                                                                                        $cardtype = "CC";
                                                                                                                        if($user['product_id']==$service_type[8]['Sale - CC : EMI']){
                                                                                                                            $cardtype = "CC : EMI";
                                                                                                                        }
                                                                                                                    }
                                                                                                                    $txn_type="Sale - ".$cardtype;
                                                                                                               }
                                                                                                           }
                                                                                                           elseif($report['service_id']==9)
                                                                                                           {
                                                                                                               $txn_type="UPI - ".$user['vpa'];
                                                                                                           }
                                                                                                           elseif($user['service_id']==10)
                                                                                                           {
                                                                                                                $txn_type="AEPS";
                                                                                                                if(in_array($user['product_id'],$service_type[$user['service_id']])){
                                                                                                                    $txn_type = array_search($user['product_id'],$service_type[$user['service_id']]);
                                                                                                                }
                                                                                                           }
                                                                                                           $txn_status=$user['txn_status']=="P"?"Pending":(($user['txn_status']=="S")?"Success":"Failed - ".$user['status_description']);
                                                                                                           $settlement_flag=$user['settlement_flag']==0?"W - ":"B - ";
                                                                                                           $status=$user['settlement_status']=="P"?"Pending":(($user['settlement_status']=="S")?"Settled":"Failed");
                                                                                                           $settlement_status=$settlement_flag.$status;

				echo "<tr><td>".$user['txn_id']."</td>";
				echo "<td>".$user['card_no']."</td>";
                                                                                                           echo "<td><a target='_blank' href='/panels/userInfo/".$user['mobile_no']."'>".$user['mobile_no']."</td>";
				echo "<td>".$user['auth_code']."</td>";
				echo "<td>".$txn_type."</td>";
				echo "<td>".$services[$user['service_id']]."</td>";
				echo "<td>".$user['txn_amount']."</td>";
				echo "<td>".$txn_status."</td>";
				echo "<td>".$settlement_status."</td>";
				echo "<td>".$user['txn_time']."</td>";
				echo "<td>".$user['settled_at']."</td>";
				echo "<td><a target='_blank' href='".$user['receipt_url']."'>".$user['receipt_url']."</a></td>";
				echo "</tr>";
			}
			?>
			</table>
                <?php }?>

<script>

    function raiseComplaint() {
        var txn_id = '<?php echo $individualTransaction[0]['va']['txn_id'] ?>';
        var disposition = $('disposition').value;
        var description = $('description').value;

        if (disposition == '' || description == '') {
            alert("Some Fields Are Empty !!!");
        } else {
            var myAjax = new Ajax.Request('/panels/bbpsComplainRegistration', {method: 'post', parameters: 'txn_id='+txn_id+'&disposition='+disposition+'&description='+description,
                onSuccess: function(res){
                    if(trim(res.responseText) != "null") {
                        var obj = JSON.parse(trim(res.responseText));
                        if(obj.status == 'success') {
                            $('complaints').innerHTML = 'Complain Registered Successfully !!!';
                        } else {
                            alert('Some Problem Occured !!!');
                        }
                    } else {
                        alert('Some Problem Occured !!!');
                    }
                }
            });
        }
    }

</script>