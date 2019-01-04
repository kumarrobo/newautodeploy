<style>
.top-buffer {
	margin-top: 20px;
}
.checkbox-margin {
	margin-top: -13px;
}
.table td, .table th {
   text-align: center;   
}
.btn-b2c, .bg-b2c {
	background-color: rgba(176, 252, 35, 0.28);
}
.btn-complaint, .bg-complaint {
	background-color: rgba(128, 128, 128, 0.15);
}
.btn-novendor, .bg-novendor {
	background-color: rgba(255, 0, 0, 0.2);
}
.btn-disabledmodem, .bg-disabledmodem {
	background-color: rgba(249, 204, 9, 0.47);;
}
.new-retailer{
	background-color: rgba(0,255, 0, 0.2);
}
</style>	

<script>
var loader = "<img src='/img/ajax-loader-1.gif' />";

jQuery.fn.addHiddenInput = function (name, value) {
    return this.each(function () {
        var input = $("<input>").attr("type", "hidden").attr("name", name).val(value);
        $(this).append($(input));
    });
};

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
    $("select#vendor_id").multipleSelect({ selectAll:true, width: 290, multipleWidth: 120, multiple: true});
    $("select#product_id").multipleSelect({ selectAll: true, width: 290, multipleWidth: 120, multiple: true});
});

function getTransactions(){
	var vendorIds = $("select#vendor_id").multipleSelect("getSelects");
	var productIds = $("select#product_id").multipleSelect("getSelects");
	
	var fromDate = $('#fromDate').val();
	var toDate = $('#toDate').val();

	var modem_flag = 0, api_flag = 0;
	if($('#modem_flag').is(':checked')){
		modem_flag = 1;
	}	
	if($('#api_flag').is(':checked')){
		api_flag = 1;
	}
	
	$("#form").addHiddenInput('vendorIds', vendorIds)
			.addHiddenInput('productIds', productIds)
			.addHiddenInput('modem_flag', modem_flag)
			.addHiddenInput('api_flag', api_flag)
			.submit();
}

function statusUpdate(id, vendor_id, vendor, date, ref_id){
	$('#su_' + id).html(loader);
	var url = '/recharges/isAfterTransaction';
	var params = {'id' : id,'vendor' : vendor, 'date' : date, 'ref_id' : ref_id};
	$.post(url, params, function(response){
		if(response.trim() == 'true')
			final_response = "<i class='glyphicon glyphicon-ok' style='color:#39B3D7;font-size:large;'></i>";
		else
			final_response = "<i class='glyphicon glyphicon-remove' style='color:rgba(255, 0, 0, 0.2);font-size:large;'></i>";
		$('#su_' + id).html(final_response);
	});
}

function simNo(id, vendor, date, ref_id){
	$('#sn_' + id).html(loader);
	var url = '/recharges/simNo';
	var params = {'id' : id,'vendor' : vendor, 'date' : date, 'ref_id' : ref_id};

	$.post(url, params, function(response){
		$('#sn_' + id).html(response);
	});
}

function manualSuccess(id){
	var r = confirm("Confirm?");
	if(r){
		$('#ms_' + id).html(loader);
		var url = '/panels/manualSuccess';
		var params = {'id' : id};

		$('#ms_' + id).load(url, params);
	}	
}

function manualFailure(id){
	var r = confirm("Confirm?");
	if(r){
		$('#mf_' + id).html(loader);
		var url = '/panels/manualFailure';
		var params = {'id' : id};

		$('#mf_' + id).load(url, params);
	}	
}

function selectModem(vendor_id){
	$("#form").addHiddenInput('vendorIds', [vendor_id])
	.addHiddenInput('productIds', '')
	.addHiddenInput('modem_flag', 1)
	.addHiddenInput('api_flag', 1)
	.attr('target', '_blank')
	.submit();
}

function selectOperator(product_id){
	$("#form").addHiddenInput('vendorIds', '')
	.addHiddenInput('productIds', [product_id])
	.addHiddenInput('modem_flag', 1)
	.addHiddenInput('api_flag', 1)
	.attr('target', '_blank')
	.submit();
}

function filter(transaction_class){
	$('.table > tbody > tr').each(function(){
		$(this).show();
		if($(this).hasClass(transaction_class)){
			$(this).show();
		}	
		else
			$(this).hide();
	});
	$('.table-head').show();
}
</script>
<link rel="SHORTCUT ICON" href="/img/pay1_favic.png">	
<title>In Process Transactions</title>

<div class="panel panel-default">
  <div class="panel-heading">
  	<h1>In Process Transactions <?php echo "(".count($process).")"?></h1>
  	Generated at: <span style="color:#428bca"><?php echo date("h : i A"); ?></span>
  </div>
  <div class="panel-body">
    <form method="post" id="form" role="form">
    <div class="row">
    	<div class="col-lg-3">
    	
    	</div>
	    <div class="input-daterange col-lg-6" id="datepicker">
		    <div class="input-group input-group-sm">
		      <span class="input-group-btn">
		        <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i> From</button>
		      </span>
		      <input type="text" readonly style="cursor:pointer" class="form-control" id="fromDate" name="fromDate" placeholder="DD-MM-YYYY" value="<?php echo $fromDate ?>">
		      <span class="input-group-btn">
		        
		      </span>
		      <input type="text" readonly style="cursor:pointer" class="form-control" id="toDate" name="toDate" placeholder="DD-MM-YYYY" value="<?php echo $toDate ?>">
		      <span class="input-group-btn">
		        <button class="btn btn-default" type="button">To <i class="glyphicon glyphicon-calendar"></i></button>
		      </span>
		    </div>
		</div>
	</div>
	<div class="row top-buffer">	
		<div class="col-lg-1">
    	
    	</div>
		<div class="form-group col-lg-5">
    	<label for="vendor_id">Setup: </label>
            <select id="vendor_id" name="vendor_id">
                    <?php foreach($vendors as $v): ?>
                            <option value="<?php echo $v['vendors']['id'] ?>" <?php if(in_array($v['vendors']['id'], $vendorIds)) echo "selected" ?> >
                                    <?php echo $v['vendors']['company'] ?>
                            </option>
                    <?php endforeach ?>
            </select>
         </div> 
         <div class="form-group col-lg-5">
    	 <label for="product_id">Operator: </label>   
            <select id="product_id" name="product_id">
                    <?php foreach($products as $p): ?>
                            <option value="<?php echo $p['products']['id'] ?>" <?php if(in_array($p['products']['id'], $productIds)) echo "selected" ?> >
                                    <?php echo $p['products']['name'] ?>
                            </option>
                    <?php endforeach ?>
            </select>
        </div>
	</div>	
	<div class="row">
		<div class="col-lg-3">
    	
    	</div>
		<div class="col-lg-3">
        	<div class="row">
        		<div class="col-lg-2">
        			<label >Show:</label>
        		</div>
        		<div class="col-lg-3 checkbox-margin">
        			<div class="checkbox">
					  <label><input id="modem_flag" type="checkbox" value="" <?php if($modem_flag) echo "checked" ?>>Modem</label>
					</div>
        		</div>
        		<div class="col-lg-3 checkbox-margin">
        			<div class="checkbox">
					  <label><input id="api_flag" type="checkbox" value="" <?php if($api_flag) echo "checked" ?>>API</label>
					</div>
        		</div>
        	</div>
        </div>
        <div class="col-lg-3">
        	<button class="btn btn-sm btn-primary" type="button" onclick="getTransactions();">Submit</button>
        </div>
	</div>
    </form>
    <marquee behavior="alternate" scrollamount="1" class="row">
    	<div class="alert alert-danger" role="alert">
    	<label style="font-size:large;">Top modems:</label>
		<?php 
		foreach($top_vendors as $tv){
			echo "<a style='font-size:large;' href='javascript:selectModem(".$tv.");' class='alert-link'>".$in_process_vendors[$tv]."(".$in_process_vendors_count[$tv].")</a> ";
		}
		?>
		<br/>
		<label style="font-size:large;">Top operators:</label>
		<?php 
		foreach($top_products as $tp){
			echo "<a style='font-size:large;' href='javascript:selectOperator(".$tp.");' class='alert-link'>".$in_process_products[$tp]."(".$in_process_products_count[$tp].")</a> ";
		}
		?>
		</div>
    </marquee>
    <div class="row">
    	<div class="col-lg-4">
    		<a href="javascript:filter('bg-b2c');" class="btn btn-md btn-b2c"></a>  B2C Transaction (<?php echo $b2c_count ?>)
    	</div>
    	<div class="col-lg-4">
    		<a href="javascript:filter('bg-complaint');" class="btn btn-md btn-complaint"></a>  Complaint Transaction (<?php echo $complaint_count ?>)
    	</div>
    	<div class="col-lg-4">
    		<a href="javascript:filter('bg-novendor');" class="btn btn-md btn-novendor"></a>  Vendor ID Not Generated (<?php echo $novendor_count ?>)
    	</div>
    </div>
    <br/>
    <div class="row">	
    	<div class="col-lg-4">
    		<a href="javascript:filter('bg-disabledmodem');" class="btn btn-md btn-disabledmodem"></a>  Modem Disabled (<?php echo $disabled_modem_count ?>)
    	</div>
    	<div class="col-lg-4">
    		<a href="javascript:filter('normal');" class="btn btn-md btn-default"></a>  Normal Transaction (<?php echo $normal_count ?>)
    	</div>
		<div class="col-lg-4">
    		<a href="javascript:filter('new-retailer');" class="btn btn-md btn-default new-retailer"></a> New Retailer (<?php echo $new_retailer ?>)
    	</div>
    </div>
    <div class="row top-buffer">
        <table class="table table-hover table-condensed table-bordered" style="border-collapse:collapse;">
			<tr class="table-head">
						<th>Modem</th>
						<th>Operator</th>                      
						<th>Amt</th> 
						<th>Circle</th>
                        <th>Cust. Mobile</th>
						<th>SIM No.</th>
	  					<th>Tran Id.</th>
	  					<th>Info.</th>
	  		    		<th>Status</th>
	  		    		<th>Action</th>
	  		    		<th>Trans Time</th>
	  		    		<th>Time left</th>
	  		    		<th>Failure</th>
			</tr>
        <?php foreach($process as $d): ?>
        	<tr class="<?php 
			   if(in_array($d['va']['retailer_id'], $retailerData)){
				   echo "new-retailer";
			   }
	        	else if($d['va']['retailer_id'] == 13) 
	        		echo "bg-b2c";
	        	else if($d[0]['complaint_flag'])
	        		echo "bg-complaint";
	        	else if(empty($d['va']['vendor_refid']))
	        		echo "bg-novendor";
	        	else if($d['v']['active_flag'] == 0)
	        		echo "bg-disabledmodem";
	        	else echo "normal";
        	?>">
        		<td><?php echo $d['v']['company'] ?></td>
        		<td><?php echo $d['p']['name'] ?></td>
        		<td><?php echo $d['va']['amount'] ?></td>
  				<td><?php echo $circle[substr($d['r']['mobile'],0,5)]; ?></td>
        		<td><a href="/panels/userInfo/<?php echo $d['va']['mobile'] ?>"><?php echo $d['va']['mobile'] ?></a></td>
        		<td id="sn_<?php echo $d['va']['txn_id'] ?>">
        			<?php if(!empty($d['va']['sim_num'])): ?>
        			<a target="_blank" href="/sims/lastModemTransactions/<?php echo $d['v']['id'] ?>/<?php echo $d['dd']['device_id'] ?>/1"
        			><?php echo $d['va']['sim_num'] ?></a>
        			<?php else: ?>
        			NA
        			<?php endif ?>
        		</td>
        		<td><a target="_blank" href="/panels/transaction/<?php echo $d['va']['txn_id'] ?>"><?php echo $d['va']['txn_id'] ?></a></td>
        		<td>
        			<a href="/recharges/tranStatus/<?php echo $d['va']['txn_id']."/".$d['v']['shortForm']."/".$d['va']['date']."/".$d['va']['vendor_refid'] ?>"
        			target="_blank" class="btn btn-sm btn-info">More Info</a>
        		</td>
        		<td id="su_<?php echo $d['va']['txn_id'] ?>">
					<?php if($d['v']['update_flag'] == 1): ?>
					<a href="javascript:void(0)" onclick="statusUpdate('<?php echo $d['va']['txn_id'] ?>', <?php echo $d['v']['id'] ?>, '<?php echo $d['v']['shortForm'] ?>', '<?php echo $d['va']['date'] ?>', '<?php echo $d['va']['vendor_refid'] ?>')"
					class="btn btn-sm btn-default">Status</a>
					<?php elseif($d['v']['active_flag'] == 0): ?>
					DISABLED
					<?php else: ?>
                                            <a href="javascript:void(0)" onclick="statusUpdate('<?php echo $d['va']['txn_id'] ?>', <?php echo $d['v']['id'] ?>, '<?php echo $d['v']['shortForm'] ?>', '<?php echo $d['va']['date'] ?>', '<?php echo $d['va']['vendor_refid'] ?>')"
					class="btn btn-sm btn-default">Status</a>
					<?php endif ?>
				</td>
        		<td id="ms_<?php echo $d['va']['txn_id'] ?>">
        			<a href='javascript:void(0)' onclick="manualSuccess('<?php echo $d['va']['txn_id'] ?>')"
        			class="btn btn-sm btn-success">Success</a>
				</td>
        		<td><?php echo substr($d['va']['timestamp'], 11) ?></td>
        		<?php 
        		$buffer_time = 15 * 60;
        		$effective_time = strtotime($d['va']['timestamp']) + $buffer_time; 
        		$secs = $effective_time - time(); 
	  			if($secs < 0){
	  				$secs = - $secs;
	  				$mins = floor($secs / 60) % 60;
	  				$hours = floor($secs / 3600);
	  				
	  				if($hours > 0)
	  					echo "<td style='color:red'>".$hours." Hrs ".$mins." mins delayed </td>";
	  				else if($mins > 0)
	  					echo "<td style='color:#ff7500;'>".$mins." mins delayed </td>";
	  				else 
	  					echo "<td style='color:#ffab00;'>".$secs." secs delayed </td>";
	  			}
	  			else {
	  				$mins = floor($secs / 60) % 60;
	  				
	  				if($mins > 0)
	  					echo "<td>".$mins." mins left </td>";
	  				else
	  					echo "<td style='color:rgb(255, 135, 0);'>".$secs." secs left </td>";
	  			}
?>
        		<td id="mf_<?php echo $d['va']['txn_id'] ?>">
        			<?php if($d['v']['update_flag'] == 1): ?>
        			<a href="javascript:manualFailure('<?php echo $d['va']['txn_id'] ?>');" class="btn btn-sm btn-default">
        				<span class="glyphicon glyphicon-thumbs-down" style="color:red"></span>
        			</a>
        			<?php elseif($d['v']['update_flag'] == 0): ?>
                                    <a href="javascript:manualFailure('<?php echo $d['va']['txn_id'] ?>');" class="btn btn-sm btn-default">
                                        <span class="glyphicon glyphicon-thumbs-down" style="color:red"></span>
                                    </a>
        			<?php endif ?>
        		</td>
        	</tr>
        <?php endforeach ?>
        </table>
    </div>
  </div>
</div>
