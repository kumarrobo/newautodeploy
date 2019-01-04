<style>
.table td, .table th {
   text-align: center;   
}
</style>

<script>
var loader = "<img src='/img/ajax-loader-1.gif' />";

function settle(transaction_id){
	var url = '/mpos/settle';
	var data = {'transaction_id' : transaction_id};

	$('#settlement_' + transaction_id).html(loader);
	$.post(url, data, function(response){
		$('#settlement_' + transaction_id).html(response);
	});
}

$(document).ready(function() {
    $(".input-daterange input").each(function (){
        $(this).datepicker({
    	    format: "dd-mm-yyyy",
    	    autoclose: true,
    	    todayHighlight: true
    	});
    });//date.setDate(date.getDate() + 7);
    $("#date").datepicker().on("changeDate", function(e){
    	var date = new Date(e.date.getTime() + 7*1000*86400);
        $("#date").datepicker("setStartDate", e.date);
        $("#date").datepicker("setEndDate", toDate);
    });
});

function settle_on_date(){
	$('form').submit();
}

function format_date(date, day_add){
	date = date.split('-');
	var d = new Date(date[2], date[1], date[0]);
	d.setDate(d.getDate() + day_add);
	
	return ("0" + d.getDate()).slice(-2) + "-" + ("0" + d.getMonth()).slice(-2) + "-" + d.getFullYear();
}

function prev_date(){
	var date = $('#date').val();
	var prev_date = format_date(date, -1);
	$('#date').val(prev_date);
	settle_on_date();
}

function next_date(){
	var date = $('#date').val();
	var next_date = format_date(date, 1);
	$('#date').val(next_date);
	settle_on_date();
}
</script>

<link rel="SHORTCUT ICON" href="/img/pay1_favic.png">
<title>
	mPOS Settlement
</title>
<br/><br/><br/>
<h2>mPOS Settlement</h2>
<div class="panel panel-default">
  <div class="panel-heading">
  	<div class="row">
  		<div class="col-lg-12">
  		
		</div>	
	</div>	
   </div>
  <div class="panel-body">
  <form method="post">
	<div class="input-daterange row" id="datepicker">
	  <div class="col-lg-12">
	    <div class="input-group input-group-sm">
	      <span class="input-group-btn">
	        <button class="btn btn-default" type="button" onclick="prev_date();"><i class="glyphicon glyphicon-chevron-left"></i></button>
	      </span>
	      <input type="text" readonly style="cursor:pointer" onchange="settle_on_date();" class="form-control" id="date" name="date" placeholder="DD-MM-YYYY" value="<?php echo $date ?>">
	      <span class="input-group-btn">
	        <button class="btn btn-default" type="button" onclick="next_date();"><i class="glyphicon glyphicon-chevron-right"></i></button>
	      </span>
	    </div><!-- /input-group -->
	  </div><!-- /.col-lg-6 -->
	</div> 
	</form>
  </div>
  
  <table class="table table-striped table-hover table-condensed table-bordered" style="border-collapse:collapse;">
	<tr>
		<th>Mobile</th>
		<th>Shop Name</th>
		<th>Distributor</th>
		<th>Mode</th>
		<th>Amount</th>
		<th>Incentive</th>
		<th>Total</th>
		<th>Time</th>
	</tr>
	<?php foreach($transactions as $t): ?>
	<tr>
		<td><a href="/panels/retInfo/<?php echo $t['r']['mobile'] ?>" target="_blank"><?php echo $t['r']['mobile'] ?></a></td>
		<td><?php echo $t['ur']['shopname'] ?></td>
		<td><?php echo $t['d']['company'] ?></td>
		<td><?php echo $t['mt']['txnType'] ?>
		<td><?php echo $t['st']['amount'] ?></td>
		<td><?php echo $t['sst']['amount'] ?></td>
		<td><?php echo ($t['st']['amount'] + $t['sst']['amount']) ?></td>
		<td><?php echo $t['st']['timestamp'] ?></td>
	</tr>
	<?php endforeach ?>
</table>

</div>