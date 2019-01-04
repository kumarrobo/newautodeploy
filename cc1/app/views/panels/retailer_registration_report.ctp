

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
    });
});

function selectDate(){
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

	window.location.href = "/panels/retailerRegistrationReport/" + fromDate + "/" + toDate;
}
</script>
<title>
	Retailers' Registration
</title>
<h1>Retailers' Registration Report</h1>
<div class="panel panel-default">
  <div class="panel-heading"><?php echo $count ?> retailers registered between <?php echo $fromDate ?> and <?php echo $toDate ?> through Android App</div>
  <div class="panel-body">
<div class="row">  
  <div class="col-lg-3">
 
  </div>	
	<div class="input-daterange" id="datepicker">
	  <div class="col-lg-6">
	    <div class="input-group input-group-sm">
	      <span class="input-group-btn">
	        <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i> From</button>
	      </span>
	      <input type="text" readonly style="cursor:pointer" class="form-control" id="fromDate" placeholder="DD-MM-YYYY" value="<?php echo $fromDate ?>">
	      <span class="input-group-btn">
	        <button class="btn btn-primary" type="button" onclick="selectDate();">Go</button>
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
<table class="table table-striped table-hover table-condensed" style="border-collapse:collapse;">
	<tr>
		<th>#</th>
		<th>Name</th>
		<th>Email</th>
		<th>Mobile</th>
		<th>Area</th>
		<th>Balance</th>
		<th>Last Sale Date</th>
		<th>Sale</th>
		<th>Created</th>
		<th></th>
	</tr>
	<?php foreach($retailers as $r): ?>
	<tr>
		<td><?php echo $r['r']['id'] ?></td>
		<td><?php echo $r['r']['name'] ?></td>
		<td><?php echo $r['r']['email'] ?></td>
		<td><a target="_blank" href="/panels/retInfo/<?php echo $r['r']['mobile'] ?>"><?php echo $r['r']['mobile'] ?></a></td>
		<td><?php echo $r['r']['area'] ?></td>
		<td><?php echo $r['users']['balance'] ?></td>
		<td><?php echo $r['0']['last_sale_date'] ?></td>
		<td><?php echo $r[0]['sale'] ?></td>
		<td><?php echo $r['r']['created'] ?></td>
		<td><a target="_blank" href="/shops/graphRetailer?type=r&id=<?php echo $r['r']['id'] ?>">Analyze</a></td>
	</tr>
	<?php endforeach ?>
</table>
</div>