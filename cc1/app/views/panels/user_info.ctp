<script type="text/javascript">
/*
function changeDateFormat(dateString){
	var dateArray = dateString.split('-').reverse();
	return dateArray.join('-');
}

function dateObject(dateString){
	var dateArray = dateString.split('-');
	var dateObject = new Date(dateArray[2], dateArray[1], dateArray[0]);
	return dateObject;
}

function validateFromAndToDates(fromDate, toDate,daysRange){
	var datePattern = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;

	if(fromDate != '' && !fromDate.match(datePattern)) {
		 alert("Invalid date format in From field: " + fromDate);
		 return false;
	}
	if(toDate != '' && !toDate.match(datePattern)) {
		 alert("Invalid date format in To field: " + toDate);
		 return false;
	}

	fromDate = dateObject(fromDate);
	toDate = dateObject(toDate);

	daysDifference = Math.ceil((toDate.getTime() - fromDate.getTime())/(1000 * 3600 * 24));

	if(daysDifference > daysRange){
		alert("The date range should not exceed " + daysRange + " days");
		return false;
	}
	return true;
}

function setAction(){
	var mobno = $('mobno').value;
	var subid = $('subid').value;
	var fromDate = $('fromDate').value;
	var toDate = $('toDate').value;

	if(mobno != ''){
		if(mobileValidate(mobno)){
			if(validateFromAndToDates(fromDate, toDate, 1)){
				fromDate = changeDateFormat(fromDate);
				toDate = changeDateFormat(toDate);
				document.userInfo.action="/panels/userInfo/" + mobno + "/mobno/" + fromDate + "/" + toDate;
				document.userInfo.submit();
			}
		}
	}
	else if(subid != ''){
		if(validateFromAndToDates(fromDate, toDate, 31)){
			fromDate = changeDateFormat(fromDate);
			toDate = changeDateFormat(toDate);
			document.userInfo.action="/panels/userInfo/" + subid + "/subid/" + fromDate + "/" + toDate;
			document.userInfo.submit();
		}
	}
	else {
		alert("Please enter at least one field: Mobile Number or Subscriber ID");
		return false;
	}
}



function addComment(userId,userMobile)
{

    var reason=$('commentAreaForUser').value;
    // alert(reason);
	var index=reason.indexOf('#');
	// alert(index);
	var temp = reason.substring(1);
	if(temp=="")
	{
	alert("Tag name cannot be blank.");
	return;
	}

	if(index==0)
		{
		//alert("in loop");
			var url1='/panels/tagTransaction/0';
			var pars1="tagName="+encodeURIComponent(reason)+"&tagFor="+userId;

			var myAjax= new Ajax.Request(url1,{method: 'post',parameters:pars1,
			onSuccess:function(transport)
			   {
			   var html=transport.responseText;
	//			$("tags1").innerHTML += temp;
				$('commentAreaForTransaction').value= "";
			   }
			 });
		}

else
{

	var url = '/panels/addComment';
	var pars   = "userMobile="+userMobile+"&text="+encodeURIComponent($('commentAreaForUser').value);
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{
					//var html = transport.responseText;
					//var text1=html.split("==") + "<br />";
					//Element.insert('asdf',{top:text1});
					$('commentAreaForUser').value = "";
					window.location.reload( true );

				}
			});
}
}
*/
</script>
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-latest.js"></script>
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script>
var jQ = jQuery.noConflict();

function modalShow(){
jQ('#myModal').modal('show');

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

/*function addComment(userId,userMobile)
{

    var reason=$('commentAreaForUser').value;
    // alert(reason);
	var index=reason.indexOf('#');
	// alert(index);
	var temp = reason.substring(1);
	if(temp=="")
	{
	alert("Tag name cannot be blank.");
	return;
	}

	if(index==0)
		{
		//alert("in loop");
			var url1='/panels/tagTransaction/0';
			var pars1="tagName="+encodeURIComponent(reason)+"&tagFor="+userId;

			var myAjax= new Ajax.Request(url1,{method: 'post',parameters:pars1,
			onSuccess:function(transport)
			   {
			   var html=transport.responseText;
	//			$("tags1").innerHTML += temp;
				$('commentAreaForTransaction').value= "";
			   }
			 });
		}

else
{

	var url = '/panels/addComment';
	var pars   = "userMobile="+userMobile+"&text="+encodeURIComponent($('commentAreaForUser').value);
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{
					//var html = transport.responseText;
					//var text1=html.split("==") + "<br />";
					//Element.insert('asdf',{top:text1});
					$('commentAreaForUser').value = "";
					window.location.reload( true );

				}
			});
}
}*/

</script>

<script>

function takeComplaintReversal(mobile, id){
	var turnaround_time = $('turnaround_time').value;
// 	var turnaround_time = $('tat_hr') + $('tat_min');
	var url = '/panels/regReversal';
	var pars   = "id="+id+"&mobile="+mobile+"&turnaroundTime="+turnaround_time;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport){console.log(transport);
			jsonResponse = JSON.parse(transport.responseText);

			if(jsonResponse["status"] == 'success')
				$('response_complaint').addClassName('response_success');
			else{
				$('response_complaint').addClassName('response_failure');
				$('response_complaint').insert(jsonResponse["status"]);
				$('response_comments').insert("&nbsp;&nbsp;<span class='response_failure'>" + "code " + jsonResponse["code"] + ": " + jsonResponse["description"] + "</span>");
			}
		}
	});
}

function toggleElement(thing, element){
	if(thing.checked){
		$(element).show();
		if(thing.id == 'action_decline'){
			$('action_reverse').checked = false;
			$('send_sms').checked = true;
			//changeTagType();
		}
	}
	else
		$(element).hide();
}

function uncheckAction(thing, element){
	if(thing.checked){
		if($(element)){
			$(element).checked = false;
			if(element == 'action_decline'){
				$('action_send_sms').checked = false;
				$('action_send_sms').hide();
			}
			if(element == 'complaintToggle'){
				toggleTaT();
			}
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

function reversalRequest(flag, refCode, userMobile, retMobile, retId){

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

	if(flag == 1)
		newUrl = '/panels/reverseTransaction/'+refCode;
	else
		newUrl = '/panels/reversalDeclined/'+refCode+'/'+sendSMS;

	var myAjax = new Ajax.Request(newUrl, {method: 'post', parameters: pars,
		onSuccess:function(transport){
			var html1 = transport.responseText;
			$('response_' + cfm).addClassName('response_success');
			$('response_' + cfm).insert(html1);
			if(sendSMS == 1)
				$('response_send_sms').addClassName('response_success');
			$('commentAreaForTransaction').value = "";
			var pars1 = "tId="+refCode+"&reason="+encodeURIComponent(reason)+"&userMobile="+userMobile+"&flag="+flag+"&retId="+retId+"&retMobile="+retMobile+"&callTypeId="+call_type+"&tagId="+tag;

			var url1 = '/panels/updateCommentsForReversalNew';

			var myAjax = new Ajax.Request(url1, {method: 'post', parameters: pars1,
				onSuccess:function(transport){
// 					var html = transport.responseText;
// 					$('response_comments').insert(transport.responseText);
					jQ('#past_comments_' + refCode).prepend(transport.responseText);
					$('commentAreaForTransaction').value = "";
					$('ajax_loader').hide();
					$('submit_comment').show();
					$('response_submit').addClassName('response_success');
				}
			});
		}
	});
}

/*function tagReversal(userMobile, transactionId, retMobile){
		var tag = $('tag').value;

		if(tag == 'none')
			return false;

		var url = '/panels/tagTransactionNew/3';
		var pars="tagId="+tag+"&tagFor="+transactionId+"&retMobile="+retMobile;

		var myAjax= new Ajax.Request(url,{method: 'post',parameters:pars,
			onSuccess:function(transport){
				$('response_comments').insert(transport.responseText);
			}
		});
}*/

function takeComment(){
// 	selTrans = $$('input:checked[type="radio"][name="transactionInfo"]')[0];
	selTrans = jQ("a[data-refCode=" + jQ('#commentRefCode').val() + "]");
	var tId = selTrans.data('tid');
	var userMobile = selTrans.data('usermobile');
	var retMobile = selTrans.data('retmobile');
	var retId = selTrans.data('retid');
	var refCode = selTrans.data('refcode');
	var comment = $('commentAreaForTransaction').value;
	var call_type = $('call_type').value;
	var tag = $('tag').value;
	var shopTId = selTrans.data('shoptid');

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
		takeComplaintReversal(retMobile, tId);
	}

	if($('action_reverse')){
		if($('action_reverse').checked){
			reversalRequest(1, refCode, userMobile, retMobile, retId);
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
		openTransactionNew(tId, shopTId);
	}

	if($('action_pull_back') && $('action_pull_back').checked){
		pullbackNew(tId);
	}

	var url = '/panels/addComment';
	var pars = "transId="+refCode+"&text="+encodeURIComponent($('commentAreaForTransaction').value)+"&userMobile="+userMobile+"&retId="+retId+"&callTypeId="+call_type+"&tagId="+tag;

	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport){
			//var html = transport.responseText;
			//var text1 = html.split("==") + "<br />";
			//Element.insert('all_comments',{top:text1});
			jQ('#past_comments_' + refCode).prepend(transport.responseText);
			$('commentAreaForTransaction').value = "";
			$('ajax_loader').hide();
			$('submit_comment').show();
 			$('response_submit').addClassName('response_success');
 			$('response_comments').insert(transport.responseText);
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
	var url = '/panels/openTransaction';
	var pars = "id="+id+"&shopid="+shopid;
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

function selectActions(refCode){
// 	selTrans = $$('input:checked[type="radio"][name="transactionInfo"]')[0];

	selTrans = jQ('a[data-refCode=' + refCode + ']');

	$$('input[type="radio"][name="action"]').each(function(e){ e.checked = 0 });
	$$('input[type="checkbox"]').each(function(e){ e.checked = 0 });
	$('turnaround_time_text').hide();
	$('turnaround_time_options').hide();
	$('action_send_sms').hide();
	$('action_1').hide();
	$('action_2').hide();
	$('action_3').hide();
	$('action_4').hide();
	$('action_5').hide();
 	$('commentAreaForTransaction').value = '';

	$('response_submit').update('').removeClassName('response_success').removeClassName('response_failure');
	$('response_reverse').update('').removeClassName('response_success').removeClassName('response_failure');
	$('response_decline').update('').removeClassName('response_success').removeClassName('response_failure');
	$('response_comments').update('').removeClassName('response_success').removeClassName('response_failure');
	$('response_open_transaction').update('').removeClassName('response_success').removeClassName('response_failure');
	$('response_pull_back').update('').removeClassName('response_success').removeClassName('response_failure');
	$('response_complaint').update('').removeClassName('response_success').removeClassName('response_failure');
	$('response_send_sms').update('').removeClassName('response_success').removeClassName('response_failure');

	if(selTrans.data('complaint') == true)
		$('action_1').show();
	if(selTrans.data('actionreverse') == true)
		$('action_2').show();
	if(selTrans.data('actiondecline') == true)
		$('action_3').show();
	if(selTrans.data('actionopentransaction') == true)
		$('action_4').show();
	if(selTrans.data('actionpullback') == true)
		$('action_5').show();

	jQ('#commentRefCode').val(refCode);
	jQ('#call_type').val('');
	jQ('#tag').val('');
	jQ('#call_type').each(function () {
	    if (this.defaultSelected) {
	        this.selected = true;
	        return false;
	    }
	});
	jQ('#tag_resolution').each(function () {
	    if (this.defaultSelected) {
	        this.selected = true;
	        return false;
	    }
	});
	jQ('#tag_customer').each(function () {
	    if (this.defaultSelected) {
	        this.selected = true;
	        return false;
	    }
	});
// 	jQ('#call_type_selected').html('None');
// 	jQ('#tag_selected').html('None');
	loadComments(refCode);
	$('tag_resolution').hide();
	jQ('tag_customer').css("display", "");
}

function loadComments(refCode){
	jQ('#commentRefCode + section').html(loadingContainer('load_' + refCode));
	var url = '/panels/showComments';
	var pars = "refCode="+refCode;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport){
			jQ('#commentRefCode + section').html(transport.responseText);
		}
	});
}

// function check_complaint(tId){
// 	selTrans = $$('input:checked[type="radio"][name="transactionInfo"]')[0];
// 	if($('complaintToggle') && $('action_1').visible() && tId == selTrans.getAttribute('data-tId')){
// 		$('complaintToggle').checked = true;
// 		toggleTaT();
// 	}
// 	else
// 		alert('Select the corresponding transaction');
// }

var page = 0;

function more_transactions(){
	$('more_trans').hide();
	$('more_loader').show();
	page++;
	pars = "page=" + page;
	url = document.URL;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport){
			$('more_loader').hide();
			if(transport.responseText == ''){
				$('more_trans').update('No more transactions');
				$('more_trans').setAttribute('href', '');
			}
			else{
				jQ('#userTrans').append(transport.responseText);
				manageTagTypes();
			}
			$('more_trans').show();
		}
	});
}

function loadingContainer(id){
	return '<div id="' + id + '" class="loading-container">'
				+	'<div class="loading"></div>'
				+	'<div class="content">'
				+		'<img src="/img/ajax-loader-2.gif" alt="">'
				+		'<span class="text"></span>'
				+	'</div>'
				+'</div>';
}

function modal_factory(method, id, title){
	method = method.charAt(0).toUpperCase() + method.slice(1);
	url = '/panels/show' + method;
	pars = 'id=' + id;
	jQ('#modal_body').html(loadingContainer(method + '_' + id));
	jQ('#modal_container').modal('show');
	jQ('#modal_title').html(title);
	new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport){
			jQ('#modal_body').html(transport.responseText);
		}
	});
}

function showTransactionHistory(ref_code){
	url = '/panels/detailedTransaction';
	pars = 'trans=' + ref_code;
	jQ('#transactionHistoryBody').html(loadingContainer('th_' + ref_code));
	jQ('#transactionHistory').modal('show');
	new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport){
			jQ('#transactionHistoryBody').html(transport.responseText);
		}
	});
}

function showActionModal(){
	jQ('#action_modal').modal('show');
}

function manageTagTypes(){
	jQ("input[name='action']").change(function(e){
		if(jQ('#action_decline').is(':checked')){
	  		jQ('#tag_customer').hide();
	  		jQ('#tag_resolution').css("display", "");
	  	}
	  	else{
	  		jQ('#tag_resolution').hide();
	  		jQ('#tag_customer').css("display", "");
	  	}
	});
}

function selectTag(element){
	jQ("#tag").val(element.value);
}

jQ(document).ready(function(){
	manageTagTypes();
	jQ(".lead_form input").css("height", '25px');
});

</script>

<style>
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

#userTrans tr:nth-child(even) {
	background-color: #eee;
}

#userTrans tr:nth-child(odd) {
	background-color: #fff;
}

#user td, #retailers td {
	border: 1px solid white;
}

#all_comments td {
	background-color: #eee;
	margin: 2px;
}

.loading {
	width: 100%;
	height: 100%;
	z-index: 999;
	position: absolute;
	top: 0px;
	left: 0px;
	opacity: 0.3;
	background: none repeat scroll 0% 0% #939393;
}

.loading-container .content {
	background: none repeat scroll 0% 0% #FFF;
	border: 5px solid #AAA;
	left: 40%;
	top: 50px;
	position: absolute;
	text-align: center;
	vertical-align: middle;
	z-index: 999;
	padding: 10px 50px;
}

.loading-container .content img {
	vertical-align: middle;
}

.loading-container .content {
	text-align: center;
}

th, caption, #userTrans td {
	text-align: center;
}

caption {
	font-weight: bold;
}
.new-retailer{
	background-color: rgba(255, 0, 0, 0.2);
}
</style>

<?php echo $this->element('cc_search'); ?>
<br />
<br />
<?php if(isset($userTrans)){ ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%"
	align="center">
	<tr>
		<td valign="top" width="75%" colspan="2"
			style="background-color: #ECB49D;">
			<table id="user" cellpadding="4" cellspacing="0" width="100%"
				style="text-align: center;">
				<caption>User Information</caption>
				<tr>
					<?php if(isset($uData)): ?>
					<th style="font-weight: bold;">User Id</th>
					<th style="font-weight: bold;">Group</th>
					<?php endif ?>
					<th style="font-weight: bold;">Operator</th>
					<th style="font-weight: bold;">Circle</th>
				</tr>
				<tr>
					<?php if(isset($uData)): ?>
					<td><?php echo $uData[0]['users']['id']; ?></td>
					<td><?php echo $uData[0]['groups']['name']; ?></td>
					<?php endif ?>
					<td><?php echo $mobileDetails['opr_name']; ?></td>
					<td><?php echo $mobileDetails['area_name']; ?></td>
				</tr>
			</table> <!-- 			<tr>
					<?php if(isset($uData)): ?>
					<th style="font-weight: bold;">User Id</th><td><?php echo $uData[0]['users']['id']; ?></td>
					<th style="font-weight: bold;">Group</th><td><?php echo $uData[0]['groups']['name']; ?></td>
					<?php endif ?>
					<th style="font-weight: bold;">Operator</th><td><?php echo $mobileDetails['opr_name']; ?></td>
				</tr>
				<tr>
					<?php if(isset($uData)): ?>
					<th style="font-weight: bold;">Retailer Mobile</th><td><?php echo $uData[0]['users']['mobile']; ?></td>
					<th style="font-weight: bold;">Shop Name</th><td><?php if($uData['0']['groups']['id'] == 6) echo $userTrans['0']['r']['shopname']; ?></td>
					<?php endif ?>
					<th style="font-weight: bold;">Circle</th><td><?php echo $mobileDetails['area_name']; ?></td>
				</tr>

			</table>
 --> <!-- 		<table>
				<tr>
					<td style="width:20%;">
						<a href="javascript:window.location.href = document.URL + '?more=1'">More Transactions</a>
					</td>
					<td style="width:5%;">&nbsp;</td>
					<td style="width:10%;">
						From:
					</td>
					<td style="width:20%;">
						<input type="text" name="fromDate" id="fromDate" value="<?php if(isset($fromDate))echo $fromDate;?>"
						style="cursor:pointer;" placeholder="dd-mm-yyyy" onmouseover="fnInitCalendar(this, 'fromDate','close=true, restrict=true, instance=single')"/>
					</td>
					<td style="width:10%;">
						To:
					</td>
					<td style="width:15%;">
						<input type="text" name="toDate" id="toDate" value="<?php if(isset($toDate))echo $toDate;?>"
						style="cursor:pointer;" placeholder="dd-mm-yyyy" onmouseover="fnInitCalendar(this, 'toDate','close=true, restrict=true, instance=single')"/>
					</td>
					<td style="width:20%;">
						<input type="submit" value="Submit" onclick="setAction();">
					</td>
				</tr>
			</table>check_complaint();
 --> <!--
			   <tr><td colspan="2">User Information</td></tr>
				<?php //if(count($uData)>0){ ?>

				<tr align="left">
					<td>User Id</td>
					<td><?php //echo $uData[0]['users']['id']; ?></td>
				</tr>


				<tr align="left">
					<td>User Mobile</td>
					<td><?php //echo $uData[0]['users']['mobile']; ?></td>
				</tr>

			<tr align="left">
					<td>User GroupId</td>
					<td><?php //echo $uData[0]['groups']['name']; ?></td>
				</tr>

					<?php
// 					if($uData['0']['groups']['id']==6)
// 					{
// 					  echo "<td>Shop Name</td>";
// 					  echo "<td>".$userTrans['0']['r']['shopname']."</td>";
// 				    } }
					?>



				<tr align="left">
					<td>State</td>
					<td><?php //echo $mobileDetails['area_name']; ?></td>
				</tr>

				<tr>
					<td>Telecom Operator</td>
					<td><?php //echo $mobileDetails['opr_name']; ?></td>
				</tr>


				</table>

				<table border="0" cellpadding="0" cellspacing="0" width="100%" >
				<tr><td></br></br></td></tr>
				</table>


				<table border="1" cellpadding="0" cellspacing="0" width="100%" >
				<tr><td colspan="4"><strong>User link to Retailer</strong></td></tr>
				<tr>
				<th>Retailer Name</th>
				<th>Retailer Shop Name</th>
				<th>Retailer Mobile</th>
			2	<th>Retailer Address</th>
				</tr>

				<?php //$rets = array(); foreach($userTrans as $ur ) {

// 				if(in_array($ur['r']['mobile'],$rets)) continue;
// 				$rets[] = $ur['r']['mobile'];
// 				if(strcmp($ur['r']['name'],'')!=0){
// 				$retailerLink=$ur['r']['name'];
// 				}else{
// 				$retailerLink=$ur['r']['mobile'];
// 				}

// 				echo "<tr>";
// 				echo "<td><a href='/phttp://shops.pay1/panels/userInfo/9920641967anels/retInfo/".$ur['r']['mobile']."' >".$retailerLink."</a></td>";
// 				echo "<td><a href='/panels/retInfo/".$ur['r']['mobile']."'>".$ur['r']['shopname']."</a></td>";
// 				echo "<td>".$ur['r']['mobile']."</td>";
// 				echo "<td>".$ur['r']['address']."</td>";

// 				echo "</tr>";
// 				 } ?>

				</table>
-->

		</td>
		<td width="25%" style="background-color: #ECEB9D;">
			<table id="retailers" cellpadding="4" cellspacing="0" width="100%"
				style="text-align: center;">
				<caption>Retailer Information</caption>
				<tr>
					<th style="font-weight: bold;">Mobile</th>
					<th style="font-weight: bold;">Shop</th>
				</tr>
				<?php if(isset($retailers)): ?>
				<?php foreach($retailers as $r): ?>
				<tr>
					<td><?php echo $r['mobile'] ?></td>
					<td><?php echo $r['shop'] ?></td>
				</tr>
				<?php endforeach ?>
				<?php endif ?>
			</table>

		</td>
	</tr>
	<tr>
		<td><br />
		<br /></td>
	</tr>
	<tr>
		<td colspan="3" width="75%;">
			<?php if(count($userTrans) > 0): ?>
			<table id="userTrans" cellpadding="4" cellspacing="0" width="100%"
				align="left">
				<caption>User Transactions</caption>
				<tr>
					<th width="8%">Transaction Id(ref_code)</th>
					<th width="8%">Ret Mobile</th>
					<th width="10%">Vendor/RefId</th>
					<th width="8%">Sub Id</th>
					<!--<th width="10%">Mobile Number</th>  -->
					<th width="4%">Op Id</th>
					<th width="8%">Operator</th>
					<th width="8%">Amount</th>
					<!--	<th width="10%">Internal Error Code</th>
		  			<th width="10%">Response</th> -->
					<th width="3%">Status</th>
					<th width="8%">Timestamp</th>
					<th width="5%">History</th>
					<th width="4%">Comment</th>
				</tr>
				<?php

				foreach($userTrans as $key => $data)
				{


					if (in_array($data['r']['id'], $retailerData)) {

						$class = "background-color: rgba(255, 0, 0, 0.2)";
					} else {
						$class = '';
					}

			echo "<tr style ='".$class."'>";
				echo "<td> <a target='_blank' href='/panels/transaction/".$data['va']['txn_id']."'>".$data['va']['txn_id']."</a></td>";
				echo "<td><a target='_blank' href='/panels/retInfo/".$data['r']['mobile']."'>".$data['r']['mobile']."</a></td>";
				echo "<td><a target='_blank' href='/recharges/tranStatus/" . $data['va']['txn_id'] . "/" . $data ['vendors'] ['shortForm'] . "/" . $data ['va'] ['date'] . "/" . $data ['va'] ['vendor_refid'] . "'>".$data['vendors']['shortForm']."</a>";
				echo "&nbsp;/".$data['va']['vendor_refid']."&nbsp;</td>";
				echo "<td>";
				if(isset($data['va']['param']))
					echo $data['va']['param'];
				else
					echo "NA";
				echo "</td>";
				echo "<td>".$data['va']['operator_id']."&nbsp;</td>";
			    echo "<td>".$data['p']['name']."&nbsp;</td>";
				echo "<td>".$data['va']['amount']."&nbsp;</td>";
			//	echo "<td>".$objShop->errors($data['vm']['internal_error_code'])."&nbsp;</td>";
			//  echo "<td>".$data['vm']['response']."&nbsp;</td>";
			//	echo "<td>".$data['vm']['status']."&nbsp;</td>";

				$status = '';
	  		   if($data['va']['status'] == '0'){
				$status = 'In Process';
	     		}else if($data['va']['status'] == '1'){
				$status = 'Successful';
		    	}else if($data['va']['status'] == '2'){
				$status = 'Failed';
			   }else if($data['va']['status'] == '3'){
				$status = 'Reversed';
			   }else if($data['va']['status'] == '4'){
				$status = 'Reversal In Process';
	     		}else if($data['va']['status'] == '5'){
				$status = 'Reversal declined';
		     	}

		     	$resolve_factor = $data ['0'] ['count_resolve_flag'] ? floor($data ['0'] ['resolve_flag'] / $data ['0'] ['count_resolve_flag']) : $data ['0'] ['resolve_flag'];

		     	$status_icon = 'icon_caution.png';
		     	$icon_complaint = 'resend.png';
		     	if(in_array($data ['va']['status'], array(0)))
		     		$status_icon = "hourglass.png";
		     	if(in_array($data['va']['status'], array(1, 4, 5)))
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
// 					$reversalStats .= "<img id='icon_complaint' src='/img/".$icon_complaint."' style='max-height:15px;cursor:pointer' onclick='check_complaint(".$data['va']['id'].");' />";
				}
				else{
					$reversalStats .= "<img title='".$complaint_status."' id='icon_complaint' src='/img/".$icon_complaint."' style='max-height:15px;' />";
				}

				echo "<td>".$reversalStats."&nbsp;</td>";
				echo "<td>".$data['va']['timestamp']."&nbsp;</td>";
				//echo "<td><input type=button value=\"Request Reversal\" ></td>";
				echo "<td><a href=javascript:modal_factory('transaction','".$data['va']['txn_id']."','Transaction-Trace');>Transaction</a></td>";
				echo "<td><a name='transactionInfo' data-refCode='".$data['va']['txn_id'].
				"' data-tId='".$data['va']['id']."' data-userMobile='".$mobno."' data-retMobile='".$data['r']['mobile'].
				"' data-retId='".$data['r']['id']."' data-shopTId='".$data['va']['shop_transaction_id']."' data-clicked='false' ";

				if ($data ['va'] ['status'] == '0' || $data ['va'] ['status'] == '1' || $data ['va'] ['status'] == '4') {
					echo " data-actionReverse=true ";
					if (strlen($resolve_factor) > 0 && $resolve_factor == 0)
						echo " data-actionDecline=true ";
				} else if ($data ['va'] ['status'] == '5')
					echo " data-actionOpenTransaction=true ";
				else
					echo " data-actionPullBack=true ";
				if(!(strlen($resolve_factor) > 0 && $resolve_factor == 0))
					echo "data-complaint=true";
				echo " href=javascript:showActionModal();selectActions('".$data['va']['txn_id']."');>Comment</a> ";
				//echo "<td>".$data ['0'] ['resolve_flag']."&".$data ['0'] ['count_resolve_flag']."&".$resolve_factor.var_dump($resolve_factor)."</td>";
				echo "</tr>";
				}
				?>

			</table>
			<table width="100%;">
				<tr>
					<td colspan="6"></td>
					<td><img src="/img/ajax-loader-1.gif" id="more_loader"
						style="display: none;" /> <a
						href='javascript:more_transactions();' id="more_trans">More
							Transactions</a></td>
				</tr>
			</table>
			<?php else: ?>
			No transactions recorded for this mobile.
			<?php endif ?>
		</td>
		<!--		<td width="25%;" style="vertical-align:top;text-align: center;">
 		Past Comments
			<div style="height:284px;overflow:auto">
			<?php if(isset($comment)): ?>
				<table id="all_comments"  width="100%" cellpadding="4" style="text-align:left;">
				<?php
						foreach($comment as $cm){ ?>
						<tr>
							<td><span style="font-size:11px;">By <?php echo $cm['u1']['name']; echo ' @ ';  echo $cm['c']['created']; ?> on <?php if(!empty($cm['c']['ref_code'])) echo $cm['c']['ref_code']; ?></span></br><?php echo $cm['c']['comments']; ?></td>
						</tr>
				<?php
				} ?>
			<?php endif ?>

				</table>
			</div>
		</td>-->
	</tr>
</table>

<div class="modal fade" id="action_modal">
	<div class="modal-dialog" style="width: 850px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Comment / Action</h4>
			</div>
			<div class="modal-body">

				<table>
					<tr>
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
						<td></td>
						<td><input type="hidden" name="tag" id="tag" value="" />
							<div>

							<select id="tag_resolution" style="display:none;" onchange="selectTag(this);">
								<option value="" selected disabled>Tag this call</option>
								<?php foreach($taggings as $k => $tag): ?>
		    					<?php if($tag['taggings']['type'] == 'Resolution'): ?>
		    					<option value="<?php echo $tag['taggings']['id'] ?>"><?php echo $tag['taggings']['name'] ?></option>
		    					<?php endif ?>
		    					<?php endforeach; ?>
							</select>

							<select id="tag_customer" onchange="selectTag(this);">
								<option value="" selected disabled>Tag this call</option>
								<?php foreach($taggings as $k => $tag): ?>
		    					<?php if($tag['taggings']['type'] == 'Customer'): ?>
		    					<option value="<?php echo $tag['taggings']['id'] ?>"><?php echo $tag['taggings']['name'] ?></option>
		    					<?php endif ?>
		    					<?php endforeach; ?>
							</select>
							</div>
						</td>
						<td><span id="action_1" style="display: none;"><input
								name="action" type="radio" onchange="toggleTaT();"
								id="complaintToggle" /> Complaint <span id="response_complaint"></span></span>
							<span id="action_3" style="display: none;"><input name="action"
								type="radio" id="action_decline" style="cursor: pointer;"
								onchange="toggleElement(this, 'action_send_sms');"> Decline <span
								id="response_decline"></span></span></td>
						<td style="text-align: right; width: 15%;"><span
							id="turnaround_time_text" style="display: none;">Turnaround Time:</span>
							<span id="action_send_sms" style="display: none;"><input
								type="checkbox" id="send_sms" /> Send SMS <span
								id="response_send_sms"></span></span></td>
						<td id="turnaround_time_options" style="display: none;">
							<div>
<!--						<select id="tat_hr">
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
					<tr>
						<td colspan="4"></td>
						<td style="width: 15%;"><span id="action_2" style="display: none;"><input
								name="action" type="radio" id="action_reverse"
								style="cursor: pointer;"
								onchange="uncheckAction(this, 'action_decline');uncheckAction(this, 'complaintToggle');">
								Reverse <span id="response_reverse"></span></span> <span
							id="action_4" style="display: none;"><input name="action"
								type="radio" id="action_open_transaction"
								onchange="uncheckAction(this, 'complaintToggle')" /> Open
								Transaction <span id="response_open_transaction"></span></span>
							<span id="action_5" style="display: none;"><input name="action"
								type="radio" id="action_pull_back"
								onchange="uncheckAction(this, 'complaintToggle')" /> Pull Back <span
								id="response_pull_back"></span></span></td>
						<td colspan="2"></td>
					</tr>
				</table>

				<table style="width: 100%;">
					<tr>
						<td>Comments</td>
					</tr>
					<tr>
						<td style="width: 60%;"><textarea class="input textarea"
								id="commentAreaForTransaction"
								style="height: 100px; width: 100%; line-height: 1.5em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; direction: ltr;"
								autocomplete="off"></textarea></td>
					</tr>
					<tr>
						<td id="response_comments" style="width: 100%;"></td>
					</tr>
				<tr>
						<td style="text-align: right; width: 60%;"><img
							src="/img/ajax-loader-1.gif" id="ajax_loader"
							style="display: none;" /> <input id="submit_comment"
							type="button" class="btn btn-primary" value="Submit"
							onClick="takeComment();"> <span id="response_submit"></span></td>
					</tr>
			</table>
				<input type="hidden" value="" id="commentRefCode">
				<section></section>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal_container">
	<div class="modal-dialog" style='height: 300px;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modal_title"></h4>
			</div>
			<div class="modal-body" id="modal_body"
				style='height: 300px; overflow: auto;'></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<?php } ?>
<?php if(count($userData) > 0){?>
                        <table class="table table-bordered table-hover" style="margin-top:1.5em">
			<tr><td colspan="13" align="center" class="appTitle">SmartPay Transaction Information</td></tr>
                        <tr class="bg-info">
			<th>Pay1 Txn ID </th>
                                                                                <th>Retailer Mobile</th>
                                                                                <th>Vendor/RefId</th>
                                                                                <th>Auth Code</th>
                                                                                <th>Txn Type</th>
                                                                                <th>Number/VPA</th>
                                                                                <th>Amount</th>
                                                                                <th>Txn Status</th>
			<th>Settlement Status</th>
                                                                                <th>Time/Date</th>
			<th>Settlement Timestamp</th>
			<th>Receipt URL</th>
			<th>Comment</th>
			</tr>
			<?php
                                                                                $service_type = Configure::read('service_type');
			foreach($userData as $user){
                                                                                                           if($user['service_id']==8)
                                                                                                           {
                                                                                                               if( $user['product_id']==$service_type[8]['MPOS Withdrawal : Non VISA'] )
                                                                                                               {
                                                                                                                   $txn_type="CW - DD : Non VISA";
                                                                                                               } else if ( $user['product_id']==$service_type[8]['MPOS Withdrawal : Non VISA'] ){
                                                                                                                   $txn_type="CW - DD : VISA";
                                                                                                               }
                                                                                                               elseif(($user['product_id']==$service_type[8]['Sale - CC']) || ($user['product_id']==$service_type[8]['Sale - DC']))
                                                                                                               {
                                                                                                                    $cardtype = '--';
                                                                                                                    if( strtolower($user['payment_card_type']) == "debit" ){
                                                                                                                        $cardtype = "DC";
                                                                                                                    } else if( strtolower($user['payment_card_type']) == "credit" ){
                                                                                                                        $cardtype = "CC";
                                                                                                                        if($user['product_id']==$service_type[8]['Sale - CC']){
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

				echo "<tr><td><a target='_blank' href='/panels/transaction/".$user['txn_id']."'>".$user['txn_id']."</td>";
				echo "<td><a target='_blank' href='/panels/retInfo/".$user['mobile']."'>".$user['mobile']."</td>";
				echo "<td>".$user['card_no']."</td>";
				echo "<td>".$user['auth_code']."</td>";
				echo "<td>".$txn_type."</td>";
				echo "<td>".$user['vpa']."</td>";
				echo "<td>".$user['txn_amount']."</td>";
				echo "<td>".$txn_status."</td>";
				echo "<td>".$settlement_status."</td>";
				echo "<td>".$user['txn_time']."</td>";
				echo "<td>".$user['settled_at']."</td>";
				echo "<td><a target='_blank' href='".$user['receipt_url']."'>".$user['receipt_url']."</a></td>";
                                                                                                           echo "<td><a name='transactionInfo' data-refCode='".$user['txn_id'].
				"' data-userMobile='".$user['mobile_no']."' data-retMobile='".$user['mobile'].
				"' data-retId='".$user['retailer_id']."' data-shopTId='".$user['shop_txn_id']."' data-clicked='false' ";
                                                                                                          echo "href=javascript:showActionModal();selectActions('".$user['txn_id']."');>Comment</a></td>";
				echo "</tr>";
			}
			?>
			</table>
                <?php }?>
