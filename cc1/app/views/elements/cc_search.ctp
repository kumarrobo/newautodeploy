<style>
:-moz-placeholder {
    color: blue;
    opacity: 0.4;
}
 
::-webkit-input-placeholder {
    color: blue;
    opacity: 0.4;
}
*:focus {
	outline: none;
}
form {
	font: 14px/21px "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
}
.lead_form h2, .lead_form label {
	font-family:Georgia, Times, "Times New Roman", serif;
}
.form_hint, .required_notification {
	font-size: 11px;
}
.lead_form ul {
    width:250px;
    list-style-type:none;
    list-style-position:outside;
    margin:0px;
    padding:0px;
}
.lead_form li{
    padding:12px; 
   /* border-bottom:1px solid #eee;*/
    position:relative;
}
.lead_form li:first-child, .lead_form li:last-child {
    /*border-bottom:1px solid #777;*/
}
.contact_form h2 {
    margin:0;
    display: inline;
}
.required_notification {
    color:#d45252; 
    margin:10px 0 0 0; 
    display:inline;
    float:right;
}
.lead_form label {
    width:160px;
    margin-top: 3px;
    display:inline-block;
    float:left;
    padding:3px;
}
.lead_form input {
    height:13px; 
    width:215px; 
    padding:5px 8px;
}
.lead_form textarea {padding:8px; width:300px;}
.lead_form button {margin-left:50px;}
.lead_form input, .lead_form textarea { 
    border:1px solid #aaa;
    box-shadow: 0px 0px 3px #ccc, 0 10px 15px #eee inset;
    border-radius:2px;
    -moz-transition: padding .25s; 
    -webkit-transition: padding .25s; 
    -o-transition: padding .25s;
    transition: padding .25s;
    padding-right:30px;
}
.lead_form input:focus, .lead_form textarea:focus {
    background: #fff; 
    border:1px solid #555; 
    box-shadow: 0 0 3px #aaa; 
    padding-right:70px;
}
button.submit {
    background-color: #68b12f;
    background: -webkit-gradient(linear, left top, left bottom, from(#68b12f), to(#50911e));
    background: -webkit-linear-gradient(top, #68b12f, #50911e);
    background: -moz-linear-gradient(top, #68b12f, #50911e);
    background: -ms-linear-gradient(top, #68b12f, #50911e);
    background: -o-linear-gradient(top, #68b12f, #50911e);
    background: linear-gradient(top, #68b12f, #50911e);
    border: 1px solid #509111;
    border-bottom: 1px solid #5b992b;
    border-radius: 3px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    -ms-border-radius: 3px;
    -o-border-radius: 3px;
    box-shadow: inset 0 1px 0 0 #9fd574;
    -webkit-box-shadow: 0 1px 0 0 #9fd574 inset ;
    -moz-box-shadow: 0 1px 0 0 #9fd574 inset;
    -ms-box-shadow: 0 1px 0 0 #9fd574 inset;
    -o-box-shadow: 0 1px 0 0 #9fd574 inset;
    color: white;
    font-weight: bold;
    padding: 2px 20px;
    text-align: center;
    text-shadow: 0 -1px 0 #396715;
}
button.submit:hover {
    opacity:.85;
    cursor: pointer; 
}
button.submit:active {
    border: 1px solid #20911e;
    box-shadow: 0 0 10px 5px #356b0b inset; 
    -webkit-box-shadow:0 0 10px 5px #356b0b inset ;
    -moz-box-shadow: 0 0 10px 5px #356b0b inset;
    -ms-box-shadow: 0 0 10px 5px #356b0b inset;
    -o-box-shadow: 0 0 10px 5px #356b0b inset;
     
}
input:required, textarea:required {
    background: #fff url(/img/required_asterisk.png) no-repeat 98% center;
}
::-webkit-validation-bubble-message {
    padding: 1em;
}
.lead_form input:focus:invalid, .lead_form textarea:focus:invalid { 
    background: #fff url(/img/img_required.png) no-repeat 98% center;
    box-shadow: 0 0 5px #d45252;
    border-color: #b03535
}
.lead_form input:required:valid, .lead_form textarea:required:valid { 
    background: #fff url(/img/green_circle_check14x14.png) no-repeat 98% center;
    box-shadow: 0 0 5px #5cd053;
    border-color: #28921f;
}
.form_hint {
    background: #d45252;
    border-radius: 3px 3px 3px 3px;
    color: white;
    margin-left:8px;
    padding: 1px 6px;
    z-index: 999; 
    position: absolute; 
    display: none;
}
.form_hint::before {
    content: "\25C0"; /* left point triangle in escaped unicode */
    color:#d45252;
    position: absolute;
    top:1px;
    left:-6px;
}
.lead_form input:focus + .form_hint {display: inline;}
.lead_form input:required:valid + .form_hint {background: #28921f;}
.lead_form input:required:valid + .form_hint::before {color:#28921f;}

.symbol {
    font-size: 0.9em;
    font-family: Times New Roman;
    border-radius: 1em;
    padding: .1em .6em .1em .6em;
    font-weight: bolder;
    color: white;
    background-color: #4E5A56;
}
.icon-tick { background: #13c823; }
.icon-tick:before { content: '\002713'; }
.notify {
    background-color:#e3f7fc; 
    color:#555; 
    border:.1em solid;
    border-color: #8ed9f6;
    border-radius:10px;
    font-family:Tahoma,Geneva,Arial,sans-serif;
    font-size:1.1em;
    padding:10px 10px 10px 10px;
    margin:10px;
    cursor: default;
    width:700px;
}
.notify-green { background: #e9ffd9; border-color: #D1FAB6; }
</style>
<script>
function today(){
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();

	if(dd<10) {
	    dd='0'+dd
	} 

	if(mm<10) {
	    mm='0'+mm
	} 

	today = dd+'-'+mm+'-'+yyyy;	
	return today;
}

function cc_search(){
	var retMobile = $('rMobNo').value.strip();
	var uMobile = $('mobno').value.strip();
	var uSubId = $('subid').value.strip();
	var transactionId = $('pay1Tran').value.strip();
	var pay1TransId = $('vendTran').value.strip();
	var rShop = $('rShop').value.strip();
	var ptran = $('ptran').value.strip();
                        var retailerId = $('retailerId').value.strip();
	var params1 = '';
	var params2 = '';
	var url = '';
	from = to = today();
	if($('from'))
		from = $('from').value;
	if($('to'))
		to = $('to').value;
	
	switch(false){
		case (retMobile == ''):
			params1=retMobile;
			url="/panels/retInfo/"+params1+"/-1/"+from+"/"+to;
			break;
		case (rShop == ''):	
			params1=rShop;
			url="/panels/search/"+from+"/"+to+"/"+rShop;
			break;
		case (uMobile == ''):
			params1=uMobile;
			url="/panels/userInfo/"+params1;	
			break;
		case (uSubId == ''):	
			params1=uSubId;
			url="/panels/userInfo/"+params1+"/subid";
			break;
                                                case (retailerId == ''):
                                                                        url = "/panels/retInfo/"+"temp/"+retailerId+"/"+from+"/"+to;
			break;
		case (transactionId == ''):	
			params1=transactionId;
			url="/panels/transaction/"+params1;
			break;
		case (pay1TransId == ''):	
			params1=pay1TransId;
			url="/panels/transaction/"+params1+"/1";
			break;
		case (ptran == ''):
			url = "/panels/transaction/"+ ptran +"/2";	
			break;
                                                default:
			return;	
	}

	document.searchInfo.action=url;
	document.searchInfo.submit();
}
</script>

<div style="background-color: #E1EFBB;">
<form class="lead_form" onSubmit="cc_search();" method="post" name="searchInfo">
<ul style="display:inline-block;">
    <li>
        <label for="rMobNo">Retailer Mobile No:</label>
        <input data-type="search" type="text" name="rMobNo" id="rMobNo"  value="<?php if(isset($mob))echo $mob;?><?php if(isset($rMobNo))echo $rMobNo;?>">
    </li>
    <li>
        <label for="rShop">Retailer Shop Name:</label>
       	<input data-type="search" type="text" name="rShop" id="rShop"  value="<?php if(isset($rShop)) echo $rShop; ?>">
    </li>
</ul>
<ul style="display:inline-block;">
    <li>
        <label for="mobno">Customer Mobile No:</label>
       	<input data-type="search" type="text" name="mobno" id="mobno" value="<?php if(isset($mobno))echo $mobno;?>"/>
    </li>
    <li>
        <label for="pay1Tran">Customer Subscriber Id:</label>
        <input data-type="search" type="text" name="subid" id="subid" value="<?php if(isset($subid))echo $subid;?>" />
    </li>
</ul>
<ul style="display:inline-block;">
    <li>
        <label for="pay1Tran">Pay1 Transaction No:</label>
       	<input data-type="search" type="text" name="pay1Tran" id="pay1Tran">
    </li>
    <li>
        <label for="vendTran">Vendor Transaction No:</label>
        <input data-type="search" type="text" name="vendTran" id="vendTran">
    </li>
</ul>
<ul style="display:inline-block;">
    <li>
        <label for="retailerId">Retailer ID:</label>
       	<input data-type="search" type="text" name="retailerId" id="retailerId" value="<?php echo isset($retId)?$retId:"";?>" />
    </li>
    <li>
        <label for="ptran">Partner Transaction No:</label>
        <input data-type="search" type="text" name="ptran" id="ptran" value="<?php  if(isset($ptran))echo $ptran;?>" />
    </li>
</ul>
<ul style="display:inline-block;">
	<li>
     	<label></label>
     	<button class="submit" type="submit" onclick="cc_search();">Submit</button>
    </li>
</ul>
</form> 
</div>