<?php
 
$statusarray = array('All','success','failure','pending');

?>

<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
 <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
   <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
   <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });

    var loader = "<img src='/img/ajax-loader-1.gif' />";
    
    function statusUpdate(id, vendor_id, vendor, date, ref_id){
	$('#su_' + id).html(loader);
	var url = '/panels/check_current_api_txn_status';
	var params = {'id' : id, 'vendor_id' : vendor_id, 'vendor' : vendor, 'date' : date, 'ref_id' : ref_id};
	$.post(url, params, function(response){
                final_response = response.trim();
		if(final_response !== '')
                    $('#su_' + id).html(final_response);
                    $('#su_' + id + "_org").html(final_response);
	});
    }
    
</script>
	<style type="text/css">
		.checkbox {
			font-size: 12px;
			line-height: 23px;
		}
		.reddiv{
			background-color:#FF0000;
			/*border: 1px solid;*/
			box-shadow: 0 0 2px grey;
		}
		.greendiv{
			background-color: #00FF00;
			/*border: 1px solid;*/
			box-shadow: 0 0 2px grey;
		}
	</style>
	
	<div class="container">
  <h2>Api Transaction Details</h2>
  <div class="panel panel-default">
    <div class="panel-body">Transaction History</div>
  </div>
  <div class="row" style="padding: 40px 10px 10px;">
	  
			<div class="col-lg-12" style="text-align: center;">
				<div class="col-lg-3" style="text-align: center;border:1px;">
				<input type="text" class="form-control" style='width:270px;margin-bottom: 20px;'  id="datepicker"   value="<?php echo $date; ?>">
				</div>
				<div class="col-lg-6" style="text-align:left;">
				
				<select name ="vendor" id="vendor" class="">
					<option value="">---Select Vendors----</option>
					<?php foreach ($apiVendors as $val){ ?>
					<option value="<?php echo $val['vendors']['id']; ?>" <?php if($val['vendors']['id'] == $vendorId){ echo 'selected'; } ?>><?php echo $val['vendors']['company']; ?></option>
					<?php } ?>

				</select>
					
					<select name ="status" id="status" class="">
<!--					<option value="">---Select Status----</option>-->
					<?php foreach ($statusarray as $statval): ?>
						<option value='<?php echo $statval ?>' <?php if($statval == $status){ echo 'selected'; } ?>><?php echo $statval ?></option>
					<?php endforeach;?>
					
					

				</select>
			
			 <input type="button" value="submit" onclick="submit();" class="btn btn-primary">
			 
			
			</div>
				
			
		</div>
  
  <table class="table table-condensed table-hover">
    <thead>
      <tr>
		  <th>Tran Id</th>
		   <th>Vendor</th>
			<th>Amount</th>
			<th>Vendor Status</th>
			<th>check vendor status</th>
			<th>Server status</th>
                        <th>Current status</th>
			<th>Timestamp</th>
			<th>Action</th>
      </tr>
    </thead>
    <tbody>
		
		<?php 
                
                foreach ($apiResult as $apival): 
                    $vendor_name = $apival['vendors']['company'];
                    $vendor_id = $apival['vendors']['id'];
                    $vendor_shortname = $apival['vendors']['shortForm'];
                    $transId = $apival['api_transactions']['txn_id'];
                    $transAmount = $apival['api_transactions']['amount'];
                    $vendorStatus = $apival['api_transactions']['vendor_status'];
                    $currentstatus = $apival['vendors_activations']['status'];
                    $vendor_refId = $apival['vendors_activations']['vendor_refid'];
                    $serverStatus = $apival['api_transactions']['server_status'];
                    $txn_updatetime = $apival['api_transactions']['updated_time'];
                ?>
		
		<tr>
                    <td><a target="_blank" href="/panels/transaction/<?php echo $transId;?>">
                              <?php echo $transId; ?>
                    </td>
                    <td><?php echo $vendor_name; ?></td>
                    <td><?php echo $transAmount; ?></td>
                    <td id="su_<?php echo $transId;?>_org"><?php echo $vendorStatus; ?></td>
                    <td id="su_<?php echo $transId;?>"><a onclick="statusUpdate('<?php echo $transId; ?>', '<?php echo $vendor_id ;?>', '<?php echo $vendor_shortname ?>', '<?php echo $date;?>', '<?php echo $vendor_refId;?>')" class="btn btn-sm btn-default" href="javascript:viod(0)">api status</a></td>
                    <td><?php echo $serverStatus; ?></td>                        
                    <td><?php echo isset($status_map[$currentstatus]) ? $status_map[$currentstatus] : ""; ?></td>
                    <td><?php echo $txn_updatetime; ?></td>
                    <?php if($apival['api_transactions']['flag'] == 1){ ?>
                    <td><img src="/img/ajax-loader-1.gif" id="ajax_loader_<?php echo $apival['api_transactions']['id'];?>" style="display: none;" /><button id='refund_button_<?php echo $apival['api_transactions']['id'];?>' type="button" class="btn btn-success btn-sm" onclick="refund('<?php echo $apival['api_transactions']['txn_id']; ?>',<?php echo $apival['api_transactions']['id'];?>);">Refund</button><button id='success_button_<?php echo $apival['api_transactions']['id'];?>' type="button" class="btn btn-success btn-sm" onclick="success(<?php echo $apival['api_transactions']['id'];?>);">Success</button><span id='message_<?php echo $apival['api_transactions']['id'];?>'></span></td>
                    <?php } else if($apival['api_transactions']['flag'] == 2){ ?>
                    <td>Refunded</td>
                    <?php } ?>
                </tr>
		
		<?php endforeach; ?>
	</tbody>
		
  </table>

			

		
 
</div>
 
	



<script>
	

            // When the document is ready
            $(document).ready(function () {
                $('#datepicker').datepicker({
                    format: "yyyy-mm-dd",
					//startDate: "-365d",
						endDate: "1d",
						multidate: false,
						autoclose: true,
						todayHighlight: true
                });  
            
            });
       

	function submit(){
		var frm = $("#frm_time").val();
		var vendorId = $("#vendor").val();
		var status = $("#status").val();
		var url = '/panels/apiRecon/?date='+$("#datepicker").val()+"&vendor="+vendorId+"&status="+status;
		window.location = url;
	}
	
	function refund(ref_code,id){
		var r=confirm("Are you sure you want to reverse the transaction?");
		if(r==true){
			var url = '/panels/reverseTransaction/'+ref_code;
			var pars   = "";
			$('#ajax_loader_'+id).show();
			$('#refund_button_'+id).remove();
			$('#success_button_'+id).remove();
			$.post(url, pars, function(response){
				$('#ajax_loader_'+id).hide();
				$('#message_'+id).html('Refunded');
			});
		}
		
	}
	
	function success(id){
		var r=confirm("Are you sure you want to success this transaction?");
		if(r==true){
			var url = '/panels/apiReconSuccessTxn/'+id;
			var pars   = "";
			$('#ajax_loader_'+id).show();
			$('#refund_button_'+id).remove();
			$('#success_button_'+id).remove();
			$.post(url, pars, function(response){
				$('#ajax_loader_'+id).hide();
				$('#message_'+id).html('Success Txn');
			});
		}
		
	}
	

</script>







