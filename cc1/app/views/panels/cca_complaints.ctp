<link rel="stylesheet" href="/boot/css/bootstrap.min.css">

<div class="container">

<div class="panel panel-default">
	  <div class="panel-body"><h4>CC Avenue Complaints</h4></div>
  </div>
    
    		<div class="row">
			<div class="col-lg-3" style="text-align: center;border:1px;">
                                <span style="float:left">From : &nbsp;</span>
                                <span style="float:left; margin-top: -5px;"><input type="text" class="form-control" style='width:100px;margin-bottom: 20px;' id="frm_date" value="<?php echo $fromDate; ?>"></span>
                                
                        </div>
			<div class="col-lg-4" style="text-align: center;border:1px;">
                                <span style="float:left; margin-left: 100px;">To : &nbsp;</span>
				<span style="float:left; margin-top: -5px;"><input type="text" class="form-control" style='width:100px;margin-bottom: 20px;' id="to_date" value="<?php echo $toDate; ?>"></span>
                                
                        </div>

			<div class="col-lg-2" style="text-align:left;border:1px;">
				<input type="button" value="Submit" onclick="submit();" class="btn btn-primary">
			</div>

		</div>
      <div class="tab-content" style="padding: 20px;">
    <div id="overall" class="tab-pane fade in active">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Txn ID</th>
                <th scope="col">Biller</th>
                <th scope="col">CC Avenue ID</th>
                <th scope="col">Amount</th>
                <th scope="col">Transaction Time</th>
                <th scope="col">Status</th>
                <th scope="col">Complaint Takenby</th>
                <th scope="col">Complaint Type</th>
                <th scope="col">Complaint Time</th>
                <th scope="col">Resolve Time</th>
                <th scope="col">Assigned To</th>
                <th scope="col">Complaint Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($complaints) {
                foreach ($complaints as $complaint) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo $complaint['va']['id']; ?></th>
                        <td><a href='/panels/transaction/<?php echo $complaint['va']['txn_id']; ?>'><?php echo $complaint['va']['txn_id']; ?></a></td>
                        <td><?php echo $complaint['p']['name']; ?></td>
                        <td><?php echo $complaint['bc']['bbps_complaint_id']; ?></td>
                        <td><?php echo $complaint['va']['amount']; ?></td>
                        <td><?php echo $complaint['va']['timestamp']; ?></td>
                        <td><?php echo $complaint['bc']['bc_status']; ?></td>
                        <td><?php echo $complaint[0]['user_name']; ?> </td>
                        <td><?php echo $complaint['bc']['complaint_type']; ?></td>
                        <td><?php echo $complaint['bc']['timestamp']; ?></td>
                        <td><?php echo (!empty($complaint['complaints']['resolve_date']) && !empty($complaint['complaints']['resolve_time']))?$complaint['complaints']['resolve_date'].' '.$complaint['complaints']['resolve_time']:'-'; ?></td>
                        <td><?php echo $complaint['bc']['assigned_to']; ?></td>
                        <td><?php echo $complaint['bc']['complaint_reason']; ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr><td colspan="9"><center><?php echo "No Records Found"; ?></center></td></tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
    </div>
</div>

<script>

	function submit(){
		window.location.href = "/panels/ccaComplaints/"+$("frm_date").value + "/" + $("to_date").value;
	}

</script>