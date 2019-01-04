<style>
.table td, .table th {
   text-align: center;   
}
</style>

<script>
var loader = "<img src='/img/ajax-loader-1.gif' />";

function set_verify_flag(retailer_id, verify_flag){
	if(verify_flag != "undefined" && retailer_id){
		var url = "/panels/setVerifyFlag";
		var data = "verify_flag=" + verify_flag + "&retailer_id=" + retailer_id;
		$('#verify_button_' + retailer_id).hide();
		$('#ajax_loader_' + retailer_id).show();
		$.post(url, data, function(response){
			if(response == "true"){
				var verification 
				$('#verify_flag_' + retailer_id).html(verification_status(verify_flag));
			}	
			else
				alert("Could not set verification status for retailer id " + retailer_id + ". Verify all documents first.");
			$('#verify_button_' + retailer_id).show();
			$('#ajax_loader_' + retailer_id).hide();
		});
	}	
}

function verification_status(verify_flag){
	switch(verify_flag){
		case 0:
			return "Unverified";
		case 1:
			return "Verified";
		case 2:
			return "Documents Submitted";		 
	}
}

function toggle_trained(retailer_id){
	if(retailer_id){
		var url = "/panels/toggleTrained";
		var data = "retailer_id=" + retailer_id;
		$('#trained_check_' + retailer_id).hide();
		$('#loader_' + retailer_id).show();
		$.post(url, data, function(response){
			$('#loader_' + retailer_id).hide();
			$('#trained_check_' + retailer_id).show();
			if(response == "false"){
				var checkbox = $('#trained_check_' + retailer_id);
				checkbox.attr("checked", !checkbox.attr("checked"));
				alert("Could not check!");
			}	
		});
	}	
}

function submit(){
	$('#distributor_id').val($('#d_id').val());
	$('#verify_flag').val($('#v_f').val());
	$('#search_term').val($('#s_t').val());
	$('#trained').val($('#t').val());
	$('#fromDateV').val($('#from_date_v').val());
	$('#toDateV').val($('#to_date_v').val());
	$('#fromDateD').val($('#from_date_d').val());
	$('#toDateD').val($('#to_date_d').val());
	$('#verified_state').val($('#v_state').val());
	$('#document_state').val($('#d_state').val());
	$('#page').val(1);

	retailers();
}

function goToPage($page){
	$('#page').val($page);

	retailers();
}

function retailers(){
	$('#form').submit();
}

$(document).ready(function() {
    $(".input-daterange input").each(function (){
        $(this).datepicker({
    	    format: "dd-mm-yyyy",
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

function mposActivation(retailer_id, activate_flag){
	var retailer = $('#retailer_' + retailer_id);
	var name = retailer.data('name');
	var shopname = retailer.data('shopname');
	var dsn = retailer.data('dsn');
	var mobile = retailer.data('mobile');

	$('#retailer_id').val(retailer_id);
	$('#r_mobile').html(mobile);
	$('#r_shopname').html(shopname);
	$('#dsn').val(dsn);

	$('#a_mpos').hide();
	$('#d_mpos').hide();
	
	if(activate_flag == 1){
		$('#a_mpos').show();
	}
	else {
		$('#d_mpos').show();
	}		
	
	//$('#mpos_activation').modal('show');
}

function updateDSN(){
	var retailer_id = $('#retailer_id').val();
	var dsn = $('#dsn').val().trim();
	if(dsn == ""){
		alert("DSN cannot be empty");
		return false;
	}	
	
	$('#updateDSN').html(loader);
	$.post("/panels/updateDSN", {"dsn" : dsn, "retailer_id" : retailer_id}, function(response){
		$('#updateDSN').html('<a href="javascript:updateDSN();" class="btn btn-success btn-sm" >Update</a>');
		if(response.trim() == "true"){
			alert("Device serial no updated");
		}
		else {
			alert("Could not update DSN. Try again.");
		}
	});
}

function activateMPOS(activate_flag){
	var retailer_id = $('#retailer_id').val();

	$('#activate_loader').show();
	$('#activateMPOS').hide();
	$.post("/panels/activateMPOS", {"retailer_id" : retailer_id, "activate_flag" : activate_flag}, function(response){
		$('#activate_loader').hide();
		$('#activateMPOS').show();
		if(response.trim() == "mPOS service activated."){
			$('#a_mpos').hide();
			$('#d_mpos').show();
			$('#mpos_' + retailer_id).html("ACTIVATED");
		}
		else {	
			$('#d_mpos').hide();
			$('#a_mpos').show();
			$('#mpos_' + retailer_id).html("DEACTIVATED");
		}
		
		alert(response);	
	});
}
</script>
<link rel="SHORTCUT ICON" href="/img/pay1_favic.png">
<title>
	Retailers
</title>
<h2>Retailers (<?php echo $total_count ?>)</h2>

<div id="mpos_activation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="max-height: 240px;">
      <div class="modal-body" style="text-align: center;">
      	<input type="hidden" id="retailer_id" >
      	<div class="row form-horizontal" role="form">
		  <div class="form-group">
		    <label class="control-label col-sm-5">Id:</label>
		    <label class="control-label col-sm-7" id="r_mobile" style="text-align:left"></label>
		  </div>
		   <div class="form-group">
		    <label class="control-label col-sm-5">Shop Name:</label>
		    <label class="control-label col-sm-7" id="r_shopname" style="text-align:left"></label>
		  </div>
		 </div> 	
      	<div class="row">
  			<div class="col-lg-8">
		  		<div class="input-group input-group-sm">
				  <span class="input-group-addon" id="dsn_input">DSN</span>
				  <input id="dsn" type="text" class="form-control" placeholder="mPOS Device Serial No" aria-describedby="dsn_input">
				</div>
			</div>
			<div class="col-lg-4" id="updateDSN">
				<a href="javascript:updateDSN();" class="btn btn-success btn-sm" >Update</a>
			</div>
  		</div>
  		<div class="row" style="margin-top:15px;">
  			<div class="col-lg-12" id="activateMPOS">
				<a id="d_mpos" style="display:none;" href="javascript:activateMPOS('0');" class="btn btn-warning btn-sm" >Deactivate mPOS Service</a>
				
				<a id="a_mpos" style="display:none;" href="javascript:activateMPOS('1');" class="btn btn-info btn-sm" >Activate mPOS Service</a>
				
			</div>
			<img src='/img/ajax-loader-1.gif' style="display:none;" id="activate_loader"/>
  		</div>
  		<div class="row" style="margin-top:15px;">
  			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  		</div>
      </div>
    </div>

  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
  	<div class="row">
  		<div class="col-lg-12">
	  		<div class="input-group input-group-sm">
			  <span class="input-group-addon" id="sizing-addon3">Search</span>
			  <input id="s_t" type="text" class="form-control" placeholder="Search by retailer name, mobile or shop name" 
			  	value="<?php if($search_term) echo $search_term ?>" aria-describedby="sizing-addon3">
			</div>
		</div>	
	</div>	
	<br/>
	<div class="row">
		<div class="col-lg-6">
			<div class="row">
				<div class="col-lg-5">
				
				</div>
				<div class="col-lg-3">
					<label>Verified Date:</label>
				</div>
			</div>
			<div class="input-daterange row" id="datepicker">
			  <div class="col-lg-12">
			    <div class="input-group input-group-sm">
			      <span class="input-group-btn">
			        <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i> From</button>
			      </span>
			      <input type="text" readonly style="cursor:pointer" class="form-control" id="from_date_v" placeholder="DD-MM-YYYY" value="<?php echo $fromDateV ?>">
			      <span class="input-group-btn">
			        
			      </span>
			      <input type="text" readonly style="cursor:pointer" class="form-control" id="to_date_v" placeholder="DD-MM-YYYY" value="<?php echo $toDateV ?>">
			      <span class="input-group-btn">
			        <button class="btn btn-default" type="button">To <i class="glyphicon glyphicon-calendar"></i></button>
			      </span>
			    </div><!-- /input-group -->
			  </div><!-- /.col-lg-6 -->
			</div> 
		</div>
		<div class="col-lg-6">
			<div class="row">
				<div class="col-lg-5">
				
				</div>
				<div class="col-lg-3">
					<label>Document Date:</label>
				</div>
			</div>
			<div class="input-daterange row" id="datepicker">
			  <div class="col-lg-12">
			    <div class="input-group input-group-sm">
			      <span class="input-group-btn">
			        <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i> From</button>
			      </span>
			      <input type="text" readonly style="cursor:pointer" class="form-control" id="from_date_d" placeholder="DD-MM-YYYY" value="<?php echo $fromDateD ?>">
			      <span class="input-group-btn">
			        
			      </span>
			      <input type="text" readonly style="cursor:pointer" class="form-control" id="to_date_d" placeholder="DD-MM-YYYY" value="<?php echo $toDateD ?>">
			      <span class="input-group-btn">
			        <button class="btn btn-default" type="button">To <i class="glyphicon glyphicon-calendar"></i></button>
			      </span>
			    </div><!-- /input-group -->
			  </div><!-- /.col-lg-6 -->
			</div> 
		</div>
	</div>
   </div>
  <div class="panel-body">
  	<div class="row form-inline" style="margin:20px">
  		<div class="form-group col-lg-5">
  			<label for="d_id">Distributor:</label>
		  	<select id="d_id" style="width:75%">
		  		<option value="">All</option>
		  		<?php foreach($distributors as $d): ?>
		  			<option value="<?php echo $d['distributors']['id'] ?>" <?php if($distributor_id == $d['distributors']['id']) echo "selected" ?> >
		  				<?php echo $d['distributors']['company']." : ".$d['distributors']['mobile'] ?>
		  			</option>
		  		<?php endforeach ?>
		  	</select>
	  	</div>
		<div class="col-lg-3">
		
		</div>
		<div class="form-group col-lg-3">
  			<label for="t">Training:</label>	
		  	<select id="t">
		  		<option value="">All</option>
		  		<option value="0" <?php if($trained === "0") echo "selected" ?> >Not trained</option> 
		  		<option value="1" <?php if($trained == 1) echo "selected" ?> >Trained</option> 
		  	</select>
		</div>  	
	</div>	
	<div class="row form-inline" style="margin:20px">
  		<div class="form-group col-lg-5">
  			<label for="v_state">Verified State:</label>
		  	<select id="v_state" style="width:75%">
		  		<option value="">All</option>
		  		<option value="0" <?php if($verified_state === "0") echo "selected" ?> >Unverified</option>
		  		<option value="1" <?php if($verified_state == 1) echo "selected" ?> >Partially Verified</option>
		  		<option value="2" <?php if($verified_state == 2) echo "selected" ?> >Verified</option>
		  	</select>
	  	</div>
	  	<div class="form-group col-lg-5">
  			<label for="d_state">Document State:</label>
		  	<select id="d_state" style="width:75%">
		  		<option value="">All</option>
		  		<option value="0" <?php if($document_state === "0") echo "selected" ?>>Submitted</option>
		  		<option value="1" <?php if($document_state == 1) echo "selected" ?>>Rejected</option>
		  	</select>
	  	</div>  	
	  	<div class="col-lg-1">
	  		<button type="button" class="btn btn-primary btn-sm" onclick="submit();">Submit</button>
	  	</div>	
	</div>	
  </div>
  
  <table class="table table-striped table-hover table-condensed table-bordered" style="border-collapse:collapse;">
	<tr>
		<th>#</th>
		<th>Retailer ID</th>
		<th>Shop Name</th>
		<!--<th>Mobile</th>-->
		<th>Document Date</th>
		<th>Verified Date</th>
		<th>Trained</th>
		<th>KYC</th>
		<th>mPOS</th>
	</tr>
	<?php $i = $offset + 1 ?>
	<?php foreach($retailers as $r): ?>
	<tr>
		<td><?php echo $i ?></td>
		<td 
			id="retailer_<?php echo $r['r']['id'] ?>"
			data-name="<?php echo $r['r']['name'] ?>"
			data-shopname="<?php echo $r['r']['shopname'] ?>"
			data-mobile="<?php echo $r['r']['mobile'] ?>"
			data-DSN="<?php echo $r['r']['device_serial_no'] ?>"
			><?php echo $r['r']['id'] ?></td>
		<!--<td><?php echo $r['ur']['shopname'] ?></td>-->
		<td><a target="_blank" href="/panels/retInfo/<?php echo $r['r']['mobile'] ?>"><?php echo $r['ur']['shopname'] ?></a></td>
		<td>
			<?php echo $r[0]['d_date'] ?>
		</td>
		<td>
			<?php echo $r[0]['v_date'] ?>
		</td>
		<td>
			<img src='/img/ajax-loader-1.gif' id="loader_<?php echo $r['r']['id'] ?>" style="display:none;"/>
			<input type="checkbox" <?php if($r['r']['trained']) echo "checked" ?> id="trained_check_<?php echo $r['r']['id'] ?>" onclick="toggle_trained('<?php echo $r['r']['id'] ?>');">
		</td>
		<td><a href="/panels/retailerVerification/<?php echo $r['r']['id'] ?>" target="_blank" 
		 class="btn btn-sm 
		 <?php if(strpos($r[0]['gds'], "1") !== false): ?>
		 btn-danger
		 <?php elseif(strpos($r[0]['gds'], "0") !== false): ?>
		 btn-warning
		 <?php elseif($r[0]['gds'] == "2,2,2"): ?>
		 btn-success
		 <?php else: ?>
		 btn-default
		 <?php endif ?>
		 " >KYC</a></td>
		 <td id="mpos_<?php echo $r['r']['id'] ?>">
		 	<?php //if($r['r']['kyc_score'] == "100"): ?>
			 	<?php if(strpos($r[0]['service_ids'], "8") !== false): ?>
			 		<a href="javascript:mposActivation('<?php echo $r['r']['id'] ?>', '0');" class="btn btn-warning btn-sm" >DEACTIVATE</a>
			 	<?php else: ?>
			 		<a href="javascript:mposActivation('<?php echo $r['r']['id'] ?>', '1');" class="btn btn-info btn-sm" >ACTIVATE</a>
			 	<?php endif ?>
		 	<?php //else: ?>
		 		<?php //echo $r['r']['kyc_score'] ?>
		 	<?php //endif ?>
		 </td>
	</tr>
	<?php $i++ ?>
	<?php endforeach ?>
</table>

</div>
<?php echo $this->element('pagination');?>

<form id="form" method="post">  
<input type="hidden" id="distributor_id" name="distributor_id" value="<?php if(isset($distributor_id)) echo $distributor_id ?>" >
<input type="hidden" id="trained" name="trained" value="<?php if(isset($trained)) echo $trained ?>" >
<input type="hidden" id="page" name="page" value="<?php if(isset($page)) echo $page ?>" >
<input type="hidden" id="search_term" name="search_term" value="<?php if(isset($search_term)) echo $search_term ?>" >
<input type="hidden" id="fromDateV" name="fromDateV" value="<?php echo $fromDateV ?>" />
<input type="hidden" id="toDateV" name="toDateV" value="<?php echo $toDateV ?>" />
<input type="hidden" id="fromDateD" name="fromDateD" value="<?php echo $fromDateD ?>" />
<input type="hidden" id="toDateD" name="toDateD" value="<?php echo $toDateD ?>" />
<input type="hidden" id="document_state" name="document_state" value="<?php echo $document_state ?>" />
<input type="hidden" id="verified_state" name="verified_state" value="<?php echo $verified_state ?>" />
</form>  