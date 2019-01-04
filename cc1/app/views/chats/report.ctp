

<style>
table .collapse.in {
    display: table-cell !important;
}
</style>


<script>
// var jQ = jQuery.noConflict();

$(document).ready(function() {
    $(".input-daterange input").each(function (){
        $(this).datepicker({
    	    format: "dd-mm-yyyy",
    	    startDate: "11-07-2015",
    	    endDate: "1d",
    	    autoclose: true,
    	    todayHighlight: true
    	});
    });//date.setDate(date.getDate() + 7);
    $("#fromDate").datepicker().on("changeDate", function(e){
    	var toDate = new Date(e.date.getTime() + 7*1000*86400);
        $("#toDate").datepicker("setStartDate", e.date);
        $("#toDate").datepicker("setEndDate", toDate);
    });
});

function getChats(cid){
	if($('#accordion' + cid).data('collapsed')){
		$('#icon' + cid).removeClass('glyphicon glyphicon-play');
		$('#icon' + cid).addClass('glyphicon glyphicon-chevron-down');
		$('#margin' + cid).show();
		if(!$('#accordion' + cid).data('loaded')){
			$.get("/chats/conversation/" + cid, function(data, status){
		        $('#accordion' + cid).html(data);
		        $('#accordion' + cid).data('loaded', true);
		    });
		} 
		$('#accordion' + cid).data('collapsed', false);
	}	  
	else {
		$('#accordion' + cid).data('collapsed', true);
		$('#icon' + cid).removeClass('glyphicon glyphicon-chevron-down');
		$('#icon' + cid).addClass('glyphicon glyphicon-play');
		$('#margin' + cid).hide();
	}	
}

function selectJID(jid){
	var fromDate = $('#fromDate').val();
	from_date_array = fromDate.split("-");
	var toDate = $('#toDate').val();
	to_date_array = toDate.split("-");
	from_date = new Date(from_date_array[2] + "-" + from_date_array[1] + "-" + from_date_array[0]);
	to_date = new Date(to_date_array[2] + "-" + to_date_array[1] + "-" + to_date_array[0]);
	
	if(to_date.getTime() - from_date.getTime() < 0){
		alert("Select a proper date range");
		return false;
	}	

	window.location.href = "/chats/report/" + fromDate + "/" + toDate + "/" + jid;
}
</script>
<title>
	Chat Report
</title>
<h1>Chat Report</h1>
<div class="panel panel-default">
  <div class="panel-heading">Chat Report<?php echo $subject ?></div>
  <div class="panel-body">
<div class="row">  
  <div class="col-lg-3">
    <div class="dropdown">
	  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
	    Support User
	    <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
	  <?php foreach($supportJIDs as $s): ?>
	  	<li><a href="javascript:selectJID('<?php echo $s['c']['to_jid'] ?>');"><?php echo $s['c']['to_jid'] ?></a></li>
	  <?php endforeach ?>
	  </ul>
	</div>
  </div>	
	<div class="input-daterange" id="datepicker">
	  <div class="col-lg-6">
	    <div class="input-group input-group-sm">
	      <span class="input-group-btn">
	        <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i> From</button>
	      </span>
	      <input type="text" readonly style="cursor:pointer" class="form-control" id="fromDate" placeholder="DD-MM-YYYY" value="<?php echo $fromDate ?>">
	      <span class="input-group-btn">
	        <button class="btn btn-primary" type="button" onclick="selectJID('');">Go</button>
	      </span>
	      <input type="text" readonly style="cursor:pointer" class="form-control" id="toDate" placeholder="DD-MM-YYYY" value="<?php echo $toDate ?>">
	      <span class="input-group-btn">
	        <button class="btn btn-default" type="button">To <i class="glyphicon glyphicon-calendar"></i></button>
	      </span>
	    </div><!-- /input-group -->
	  </div><!-- /.col-lg-6 -->
	</div>  
	</div>
  </div>
<table class="table table-striped table-hover" style="border-collapse:collapse;">
	<tr>
		<th></th>
		<th>#</th>
		<th>Retailer</th>
		<th>Support User</th>
		<th>Date</th>
		<th>Start Time</th>
		<th>End Time</th>
	</tr>
	<?php foreach($chats as $chat): ?>
	<tr <?php if($chat['c']['start_time'] == $chat['c']['end_time']) echo "class='danger'"; ?>>
		<td data-toggle="collapse" data-target="#accordion<?php echo $chat['c']['conversation_id'] ?>" class="clickable"><i id="icon<?php echo $chat['c']['conversation_id'] ?>" class="glyphicon glyphicon-play" style="cursor:pointer;" onclick="getChats('<?php echo $chat['c']['conversation_id'] ?>');"></i></td>
		<td><?php echo $chat['c']['conversation_id'] ?></td>
		<td><a href="javascript:selectJID('<?php echo $chat['c']['from_jid'] ?>');" ><?php echo $chat['c']['from_jid'] ?></a></td>
		<td><?php echo $chat['c']['to_jid'] ?></td>
		<td><?php echo $chat['c']['date'] ?></td>
		<td><?php echo date("Y-m-d H:i:s", $chat['c']['start_time']/1000) ?></td>
		<td><?php echo date("Y-m-d H:i:s", $chat['c']['end_time']/1000) ?></td>
	</tr>
	<tr>
		<td id="margin<?php echo $chat['c']['conversation_id'] ?>" style="display:none;"></td>
		<td colspan="7" id="accordion<?php echo $chat['c']['conversation_id'] ?>" class="collapse" style="font-size:12px;" data-collapsed="true" data-loaded="false">
			<img src="/img/ajax-loader-1.gif" />
		</td>
    </tr>
	<?php endforeach ?>
</table>
</div>