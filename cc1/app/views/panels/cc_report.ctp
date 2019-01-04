<style>
#cc_report table, #cc_report th, #cc_report td {
    border: 1px solid black;
    border-collapse: collapse;
    margin:10px;
}
#cc_report th, #cc_report td {
    padding: 5px;
    text-align: left;
}
#cc_report table tr:nth-child(even) {
    background-color: #eee;
}
#cc_report table tr:nth-child(odd) {
   background-color:#fff;
}
#cc_report table th	{
    background-color: black;
    color: white;
}
#cc_report div {
    margin-top:5px;
}
.pull-left {
	float:left;
}
.pull-right {
	float:right;
}
.wrapper {
	margin:10px;
	margin-top:20px;
	padding:5px;
	width:100%;
}
.column25 {
	width:25%;
}
.column33 {
	width:33%;
}
.column66 {
	width:66%;
}
.column50 {
	width:50%;
}
.column75 {
	width:75%;
}
.column100 {
	width:100%;
}
#user_call_types, #vendor_products, #call_types, .tall200 {
	overflow:auto;
	height:200px;
}
#tags {
	overflow:auto;
	height:620px;
	margin-left:10px;
}
#cc_report th a {
	color:white;
}
#cc_report td a {
	color:black;
}
#cc_report a:hover {
	color: blue;
}
</style>

<script>
function changeDateFormat(dateString){
	var dateArray = dateString.split('-').reverse();
	return dateArray.join('-');
}

function dateObject(dateString){
	var dateArray = dateString.split('-');
	var dateObject = new Date(dateArray[2], dateArray[1], dateArray[0]);
	return dateObject;
}
function validateFromAndToDates(fromDate, toDate, daysRange){
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
function getDateParamString(){
	var fromDate = $('fromDate').value;
	var toDate = $('toDate').value;

	if(validateFromAndToDates(fromDate, toDate, 31)){
		fromDate = changeDateFormat(fromDate);
		toDate = changeDateFormat(toDate);

		dateParams = "fromDate=" + fromDate + "&toDate=" + toDate;
		return dateParams;
	}
	return false;
}
function linkWithParam(param){
	if(typeof param != 'undefined')	
		if(param.split("=")[1] == "" || param.split("=")[1] == -1)
			return;
	
	dateParams = getDateParamString();
	var from_time = "";
	var to_time = "";
	if($('from_time'))
		from_time = $('from_time').value;
	if($('to_time'))
		to_time = $('to_time').value;
	if(dateParams){
		$('date_submit').hide();
		$('ajax_loader').show();
		var url = document.URL.split('?')[0];
		url += "?" + dateParams;
		if(typeof param != 'undefined')	
			url += "&" + param;
		if(from_time && to_time){
			url += "&from_time=" + from_time + "&to_time=" + to_time;
		}	
		window.location.href = url; 
	}	
}
// function userStats(element){
// 	param = "mobile=" + element.value;
// 	linkWithParam(param);
// }

function getReport(){
	mobile = "";
	mobile = $('sel_exec').value;
	dateParams = getDateParamString();
	var from_time = "";
	var to_time = "";
	if($('from_time'))
		from_time = $('from_time').value;
	if($('to_time'))
		to_time = $('to_time').value;
	if(dateParams){
		$('date_submit').hide();
		$('ajax_loader').show();
		$('sel_exec').disable();
		var url = document.URL.split('?')[0];
		url += "?" + dateParams + "&mobile=" + mobile;
		if(from_time && to_time){
			url += "&from_time=" + from_time + "&to_time=" + to_time;
		}	
		window.location.href = url; 
	}	
}

function add_tag(){
	var tag = $('tag_name').value;
	var tag_type = $('tag_type').value;

	if(tag == ""){
		alert("Enter a tag name");
		return false;
	}	
	var url= "/panels/createTag";
	var data = "tagName=" + encodeURIComponent(trim(tag)) + "&tagType=" + tag_type;
	$('add_tag_button').hide();
	$('tag_ajax_loader').show();
	var myAjax = new Ajax.Request(url, {method: 'post', parameters:data,
	onSuccess:function(transport){
   			var response = (transport.responseText).trim();
   			$('tag_ajax_loader').hide();
   			$('add_tag_button').show();
   			$('tag_name').value = "";
			if(response == "success"){
				alert("Tag added");
			}
			else {
				alert("Cannot add this tag.");
			}		
   		}
 	});  
}   

function remove_tag(){
	var tag_type = $('tag_type_remove').value;
	var tag_id = $('tag_' + tag_type.toLowerCase()).value;

	var url= "/panels/removeTag";
	var data = "tagId=" + tag_id;
	$('remove_tag_button').hide();
	$('remove_tag_ajax_loader').show();
	var myAjax = new Ajax.Request(url, {method: 'post', parameters:data,
	onSuccess:function(transport){
   			var response = (transport.responseText).trim();
   			$('remove_tag_ajax_loader').hide();
   			$('remove_tag_button').show();
			if(response == "success"){
				alert("Tag removed");
			}
			else {
				alert("Could not remove tag");
			}		
   		}
 	});  
} 

function selectTags(){
	var tag_type = $('tag_type_remove').value;

	$('tag_customer').hide();
	$('tag_retailer').hide();
	$('tag_resolution').hide();

	$('tag_' + tag_type.toLowerCase()).show();
}
</script>
<table style="width:100%">
<caption><h1>Customer Care Report</h1><h2><?php if($subject) echo $subject ?></h2></caption>
<tr><td>
<div class="wrapper" id="cc_report">
	<div class="pull-left column50">
		<div class="pull-left">
			<div class="pull-left column25" id="call_types">
				<table>
					<tr><th>Call Type</th><th>Count</th></tr>
					<?php foreach($callTypes as $ktc => $tc):?>
						<tr><td><a href="javascript:linkWithParam('callTypeId=<?php echo $ktc ?>');"><?php echo $tc['name'] ?></a></td><td><?php echo $tc['count'] ?></td></tr>
					<?php endforeach ?>
				</table>
			</div>
			<div class="pull-right column50" id="control">
				<div>
					From: <input type="text" name="fromDate" id="fromDate" value="<?php if(isset($fromDate))echo $fromDate;?>" style="width:25%;cursor:pointer;" placeholder="dd-mm-yyyy" onmouseover="fnInitCalendar(this, 'fromDate','close=true, restrict=true, instance=single')"/>
					To: <input type="text" name="toDate" id="toDate" value="<?php if(isset($toDate))echo $toDate;?>" style="width:25%;cursor:pointer;" placeholder="dd-mm-yyyy" onmouseover="fnInitCalendar(this, 'toDate','close=true, restrict=true, instance=single')"/>
					<input type="submit" value="Submit" onclick="getReport();" id="date_submit"><img
							src="/img/ajax-loader-1.gif" id="ajax_loader"
							style="display: none;" />
				</div>
				<?php if($fromDate == $toDate): ?>
				<div>
					Time From:
					<select id="from_time">
						<option></option>
						<?php for($i = 8; $i < 24; $i++): ?>
						<option value="<?php echo sprintf("%02d", $i); ?>" <?php if($from_time == $i) echo "selected" ?>><?php echo $i ?></option>
						<?php endfor ?>
					</select>
					To:
					<select id="to_time">
						<option></option>
						<?php for($i = 8; $i < 24; $i++): ?>
						<option value="<?php echo sprintf("%02d", $i); ?>" <?php if($to_time == $i) echo "selected" ?>><?php echo $i ?></option>
						<?php endfor ?>
					</select>
				</div>
				<?php endif ?>
				<div>
					Select Executive: 
					<select onchange="getReport();" id="sel_exec">
					<option value="" <?php if(!isset($user_mobile)) echo "selected"?>>All Executives</option>
					<?php foreach($usersList as $au):?>
						<option value="<?php echo $au['c']['mobile'] ?>" <?php if(isset($user_mobile) && $au['c']['mobile'] == $user_mobile) echo "selected"; ?>><?php if($au['u']['user']) echo $au['u']['user']; else echo $au['c']['mobile']; ?></option>
					<?php endforeach ?>
					</select>
				</div>
				<div class="">
					<table>
						<tr>
							<td>
								<input type="text" id="tag_name" name="tag_name" placeholder="Type tag here.." />
							</td>
							<td>
								<select id="tag_type">
									<option value="Customer">Customer</option>
									<option value="Resolution">Resolution</option>
									<option value="Retailer">Retailer</option>
								</select>
							</td>
							<td>
								<input type="button" onclick="add_tag();" value="Add" id="add_tag_button"/>
								<img src="/img/ajax-loader-1.gif" id="tag_ajax_loader" style="display: none;" />
							</td>
						</tr>
						<tr>
							<td>
								<select id="tag_customer">
									<?php foreach($tags as $tc): ?>
									<?php if($tc['t']['type'] == "Customer"): ?>
									<option value="<?php echo $tc['t']['tag_id']?>"><?php echo $tc['t']['tag']?></option>
									<?php endif ?>
									<?php endforeach ?>
								</select>
								<select id="tag_retailer" style="display:none;">
									<?php foreach($tags as $tret): ?>
									<?php if($tret['t']['type'] == "Retailer"): ?>
									<option value="<?php echo $tret['t']['tag_id']?>"><?php echo $tret['t']['tag']?></option>
									<?php endif ?>
									<?php endforeach ?>
								</select>
								<select id="tag_resolution" style="display:none;">
									<?php foreach($tags as $tres): ?>
									<?php if($tres['t']['type'] == "Resolution"): ?>
									<option value="<?php echo $tres['t']['tag_id']?>"><?php echo $tres['t']['tag']?></option>
									<?php endif ?>
									<?php endforeach ?>
								</select>
							</td>
							<td>
								<select id="tag_type_remove" onchange="selectTags();">
									<option value="Customer">Customer</option>
									<option value="Resolution">Resolution</option>
									<option value="Retailer">Retailer</option>
								</select>
							</td>
							<td>
								<input type="button" onclick="remove_tag();" value="Remove" id="remove_tag_button"/>
								<img src="/img/ajax-loader-1.gif" id="remove_tag_ajax_loader" style="display: none;" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<?php if(isset($viaCounts)): ?>
			<div class="pull-right column25">
				<table>
					<tr><th>Via</th><th>Count</th></tr>
					<?php foreach($viaCounts as $vc):?>
						<tr><td><a href="javascript:linkWithParam('via=<?php if(isset($vc['cc']['via'])) echo $vc['cc']['via']; else if(isset($vc['va']['via'])) echo $vc['va']['via'] ?>');"><?php echo $vc['cc']['name'] ?></a></td><td><?php echo $vc[0]['count'] ?></td></tr>
					<?php endforeach ?>
				</table>
			</div>
			<?php endif ?>
		</div>	
		<div id="user_call_types" class="pull-left column100">
			<table>
				<tr>
					<th>Execs / Call Types</th>
					<?php foreach($callTypes as $ktc => $tc):?>
						<th><a href="javascript:linkWithParam('callTypeId=<?php echo $ktc ?>');"><?php echo $tc['name'] ?></a></th>
					<?php endforeach ?>	
				</tr>
				<?php foreach($users as $u): ?>
				<tr>
					<th>
						<a href="javascript:linkWithParam('mobile=<?php echo $u ?>');"><?php echo $usersCallTypesCounts[$u][-1]['name'] ?></a>
					</th>
					<?php foreach($call_types as $ct): ?>
					<td>
						<?php echo $usersCallTypesCounts[$u][$ct]['count'] ?>
					</td>
					<?php endforeach ?>
				</tr>	
				<?php endforeach ?>
			</table>
		</div>	
		<div id="vendor_products" class="pull-left column100">
			<table>
				<tr>
					<th>Vendor / Modem</th>
					<?php foreach($vendors as $v): ?>
					<?php foreach($products as $p): ?>
					<?php if($v == -1): ?>
						<th><a href="javascript:linkWithParam('productId=<?php echo $p ?>')" > <?php echo $vendorsProducts[-1][$p]['name'] ?> </a></th>
					<?php endif ?>	
					<?php endforeach ?>
					<?php endforeach ?>
				</tr>	
				<?php foreach($vendors as $v): ?>
				<tr>
					<th>
						<a href="javascript:linkWithParam('vendorId=<?php echo $v ?>')" ><?php echo $vendorsProducts[$v][-1]['name'] ?></a>
					</th>
				<?php foreach($products as $p): ?>
					<td>
						<?php echo $vendorsProducts[$v][$p]['count'] ?>
					</td>
				<?php endforeach ?>
				</tr>
				<?php endforeach ?>
			</table>
		</div>	
	</div>
	<div class="pull-left column25" id="tags">
		<div class="tall200">
			<table>
				<tr><th>Customer Tags</th><th>Count</th></tr>
				<?php foreach($tags as $at):?>
				<?php if($at['t']['type'] == "Customer"): ?>
					<tr><td><a href="/panels/tagReport/<?php echo $at['t']['tag_id'] ?>/<?php echo $fromDate ?>/<?php echo $toDate ?>/<?php if(isset($user_mobile)) echo $user_mobile; else echo 0 ?>/<?php if($from_time AND $to_time) { echo $from_time."_".$to_time; } else { echo 0; } ?>/<?php echo $callTypeId; ?>" target="_blank"><?php echo $at['t']['tag'] ?></a></td><td><?php echo $at['0']['count'] ?></td></tr>
				<?php endif ?>
				<?php endforeach ?>
				<tr><td>Total</td><td><?php if(isset($totalCTagsCount)) echo $totalCTagsCount ?></td></tr>
			</table>
		</div>
		<div class="tall200">
			<table>
				<tr><th>Resolution Tags</th><th>Count</th></tr>
				<?php foreach($tags as $at):?>
				<?php if($at['t']['type'] == "Resolution"): ?>
					<tr><td><a href="/panels/tagReport/<?php echo $at['t']['tag_id'] ?>/<?php echo $fromDate ?>/<?php echo $toDate ?>/<?php if(isset($user_mobile)) echo $user_mobile; else echo 0  ?>/<?php if($from_time AND $to_time) { echo $from_time."_".$to_time; } else { echo 0; } ?>/<?php echo $callTypeId; ?>" target="_blank"><?php echo $at['t']['tag'] ?></a></td><td><?php echo $at['0']['count'] ?></td></tr>
				<?php endif ?>
				<?php endforeach ?>
				<tr><td>Total</td><td><?php if(isset($totalResTagsCount)) echo $totalResTagsCount ?></td></tr>
			</table>
		</div>
		<div class="tall200">
			<table>
				<tr><th>Retailer Tags</th><th>Count</th></tr>
				<?php foreach($tags as $at):?>
				<?php if($at['t']['type'] == "Retailer"): ?>
					<tr><td><a href="/panels/tagReport/<?php echo $at['t']['tag_id'] ?>/<?php echo $fromDate ?>/<?php echo $toDate ?>/<?php if(isset($user_mobile)) echo $user_mobile; else echo 0  ?>/<?php if($from_time AND $to_time) { echo $from_time."_".$to_time; } else { echo 0; } ?>/<?php echo $callTypeId; ?>" target="_blank"><?php echo $at['t']['tag'] ?></a></td><td><?php echo $at['0']['count'] ?></td></tr>
				<?php endif ?>
				<?php endforeach ?>
				<tr><td>Total</td><td><?php if(isset($totalRetTagsCount)) echo $totalRetTagsCount ?></td></tr>
			</table>
		</div>
		<div class="tall200">
			<table>
				<tr><th>Online Complaint Tags</th><th>Count</th></tr>
				<?php foreach($tags as $at):?>
				<?php if($at['t']['type'] == "Online Complaint"): ?>
					<tr><td><a href="/panels/tagReport/<?php echo $at['t']['tag_id'] ?>/<?php echo $fromDate ?>/<?php echo $toDate ?>/<?php if(isset($user_mobile)) echo $user_mobile; else echo 0  ?>/<?php if($from_time AND $to_time) { echo $from_time."_".$to_time; } else { echo 0; } ?>/<?php echo $callTypeId; ?>" target="_blank"><?php echo $at['t']['tag'] ?></a></td><td><?php echo $at['0']['count'] ?></td></tr>
				<?php endif ?>
				<?php endforeach ?>
				<tr><td>Total</td><td><?php if(isset($totalOCTagsCount)) echo $totalOCTagsCount ?></td></tr>
			</table>
		</div>
	</div>	
</div>
</td></tr></table>