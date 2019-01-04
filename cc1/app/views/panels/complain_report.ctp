<?php 

$fromTime = explode(":",$fromTime);
$toTime = explode(":",$toTime);

?>

  <link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
   <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
   <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
	</script>

	
	<div class="container">
		
  <div class="panel panel-default">
	  <div class="panel-body"><h4>Complaint Report Details</h4></div>
  </div>
		
		<div class="row">
			<div class="col-lg-3" style="text-align: center;border:1px;">
                                <span style="float:left">From : &nbsp;</span>
                                <span style="float:left; margin-top: -5px;"><input type="text" class="form-control" style='width:100px;margin-bottom: 20px;' id="frm_date" value="<?php echo $fromDate; ?>"></span>
                                <span style="float:left">&nbsp;
                                        <select name="frm_time_hrs" id="frm_time_hrs">
                                                <?php for($i=0;$i<24;$i++) { ?>
                                                <option <?php if($i == $fromTime[0]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
                                                <?php } ?>
                                        </select> :
                                        <select name="frm_time_mins" id="frm_time_mins">
                                                <?php for($i=0;$i<60;$i++) { ?>
                                                <option <?php if($i == $fromTime[1]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
                                                <?php } ?>
                                        </select>
                                </span>
                        </div>
			<div class="col-lg-4" style="text-align: center;border:1px;">
                                <span style="float:left; margin-left: 100px;">To : &nbsp;</span>
				<span style="float:left; margin-top: -5px;"><input type="text" class="form-control" style='width:100px;margin-bottom: 20px;' id="to_date" value="<?php echo $toDate; ?>"></span>
                                <span style="float:left">&nbsp;
                                        <select name="to_time_hrs" id="to_time_hrs">
                                            <?php for($i=0;$i<24;$i++) { ?>
                                            <option <?php if($i == $toTime[0]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
                                            <?php } ?>
                                        </select> :
                                        <select name="to_time_mins" id="to_time_mins">
                                                <?php for($i=0;$i<60;$i++) { ?>
                                                <option <?php if($i == $toTime[1]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
                                                <?php } ?>
                                        </select>
                                </span>
                        </div>
<!--			<div class="col-lg-3" style="text-align: center;border:1px;">
				<input type="text" class="form-control" style='width:270px;'  id="frm_date"   value="<?php echo $fromDate; ?>">
			</div>-->
			<div class="col-lg-2" style="text-align:left;border:1px;">
				<input type="button" value="Submit" onclick="submit();" class="btn btn-primary">
			</div>

		</div>
		
  <div class="row" style="">
	  

  <ul class="nav nav-pills" style="padding: 20px;">
	  <li class="active"><a data-toggle="pill" href="#overall"><b>Overall Complaints</b></a></li>
	  <li ><a data-toggle="pill" href="#user"><b>Userwise Closed Complaints</b></a></li>
	  <li><a data-toggle="pill" href="#vendor"><b>Vendor Wise Complaint Status</b></a></li>
	  <li><a data-toggle="pill" href="#operator"><b>Operator Wise Complaint status</b></a></li>
	  <li><a data-toggle="pill" href="#hour"><b>Hour Wise Closed Complaints</b></a></li>
	  <li><a data-toggle="pill" href="#open"><b>Open/Closed</b></a></li>
	  <li><a data-toggle="pill" href="#percent"><b>Percentage Wise Complaints</b></a></li>
	 
  </ul>
  
  <div class="tab-content" style="padding: 20px;">
    <div id="overall" class="tab-pane fade in active">
		
		<table class="table  table-bordered" style="width:100%;">
			<tr>
				<td>
					<table class="table table-hover table-bordered" style="width:100%;">
						
						<thead>
      <tr>
		<th class="col-lg-1">Total</th>
		<th class="col-lg-2">Open</th>
		<th class="col-lg-1">Closed</th>
		<th class="col-lg-1">Exceed</th>
      </tr>
    </thead>
	<tbody>
		<tr>
			<td><?php  echo $totalComplaint;?></td>
			<td><a href='#' onclick="showtable('opened')"><?php  echo $totalOpen;?></a></td>
			<td><a href='#' onclick="showtable('closed')"><?php  echo $totalClosed;?></a></td>
			<td><a href='#' onclick="showtable('exceed')"><?php  echo $outoftat;?></a></td>
		</tr>
	</tbody>
						
					</table>
					
				</td>
				<td id='opened' style="display: none;">
					<table class="table table-hover table-bordered" style="width:30%;">
			<thead>
      <tr>
		<th class="col-lg-1">Vendor Name</th>
		<th class="col-lg-2">Open</th>
      </tr>
    </thead>
	<tbody>
		<?php foreach ($dataset['modem'] as $val){ ?>
		<tr>
			<td><?php  echo $val[0]['name'];?></td>
			<td><?php  echo  $val[0]['open'];?></td>
		</tr>
		<?php } ?>
		<?php foreach ($dataset['api'] as $val){ ?>
		<tr>
			<td><?php  echo $val[0]['name'];?></td>
			<td><?php  echo  $val[0]['open'];?></td>
		</tr>
		<?php } ?>
	</tbody>
	
		</table>
					
				</td>
				<td id='closed' style="display: none;">
					<table class="table table-hover table-bordered" style="width:30%;">
			<thead>
      <tr>
		<th class="col-lg-1">Vendor Name</th>
		<th class="col-lg-2">Closed</th>
      </tr>
    </thead>
	<tbody>
		<?php foreach ($dataset['modem'] as $val){ ?>
		<tr>
			<td><?php  echo $val[0]['name'];?></td>
			<td><?php  echo  $val[0]['closed'];?></td>
		</tr>
		<?php } ?>
		<?php foreach ($dataset['api'] as $val){ ?>
		<tr>
			<td><?php  echo $val[0]['name'];?></td>
			<td><?php  echo  $val[0]['closed'];?></td>
		</tr>
		<?php } ?>
	</tbody>
	
		</table>
					
				</td>
				<td id='exceed' style="display: none;">
					<table class="table table-bordered" style="width:30%;">
			<thead>
      <tr>
		<th class="col-lg-1">Vendor Name</th>
		<th class="col-lg-2">Exceed</th>
      </tr>
    </thead>
	<tbody>
		<?php foreach ($dataset['modem'] as $key => $val){ ?>
		<tr>
			<td><?php  echo $val[0]['name'];?></td>
                        <td><a target="_blank" href="/panels/exceedComplainDetails/<?php echo $key ?>/<?php echo $fromDate; ?>/<?php echo $toDate; ?>/<?php echo implode('.',$fromTime); ?>/<?php echo implode('.',$toTime); ?>"><?php echo $val[0]['outoftat'];?></a></td>
		</tr>
		<?php } ?>
		<?php foreach ($dataset['api'] as $key =>$val){ ?>
		<tr>
			<td><?php  echo $val[0]['name'];?></td>
			<td><a target="_blank" href="/panels/exceedComplainDetails/<?php echo $key ?>/<?php echo $fromDate; ?>/<?php echo $toDate; ?>/<?php echo implode('.',$fromTime); ?>/<?php echo implode('.',$toTime); ?>"><?php echo $val[0]['outoftat'];?></a></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Total</td>
			<td><a target="_blank" href="/panels/exceedComplainDetails/total/<?php echo $fromDate; ?>/<?php echo $toDate; ?>/<?php echo implode('.',$fromTime); ?>/<?php echo implode('.',$toTime); ?>"><?php echo $outoftat;?></a></td>
		</tr>
                
	</tbody>
	
		</table>
					
				</td>
			</tr>
			
	
		</table>
		
		
      
    </div>
    <div id="user" class="tab-pane fade">
		<?php  if(count($dataset['user']) > 0) { ?>
		
	<table class="table table-hover table-bordered" style="width:40%;">
	<thead>
      <tr>
		<th class="col-lg-1">Agent Name</th>
		<th class="col-lg-2">Closed Complaints</th>
		
      </tr>
    </thead>
	<tbody>
		<?php foreach ($dataset['user'] as $userkey => $userval) { ?>
		<tr>
			<td><?php  echo $userval[0]['name'];?></td>
			<td><?php  echo $userval[0]['closed'];?></td>
		</tr>
		<?php } ?>
	</tbody>
	</table>
	<?php } ?>
     
    </div>
    <div id="vendor" class="tab-pane fade">
		<?php  if(count($dataset['modem'])>0){?>
		<table class="table  table-bordered" style="width:50%;">
			
			<tr>
				 
				<td style="width:25%">
					<table class="table table-hover table-bordered" style="width:100%;">
						<thead>
							<tr>
			<th colspan="5">
				Modem Wise Complaints Count 
			</th>
		</tr>
		<tr>
			<th class="col-lg-2">VendorName</th>
			<th class="col-lg-1"><b>Open</b></th>
			<th class="col-lg-1"><b>Closed</b></th>
			<th class="col-lg-3"><b>Re-Open</b></th>
			<th class="col-lg-3"><b>Total</b></th>
		</tr>
		
			</thead>
			<tbody>
				<?php foreach ($dataset['modem'] as $key => $val) {?>
				<tr>
				<td class="col-lg-2"><?php echo $val[0]['name'] ; ?></td>
				<td class="col-lg-1"><?php echo $val[0]['open'] ; ?></td>
				<td class="col-lg-1"><?php echo $val[0]['closed'] ; ?></td>
				<td class="col-lg-3"><a target="_blank" href="/panels/reOpenDetails/vendor/<?php echo $key ?>/<?php echo $fromDate; ?>/<?php echo $toDate; ?>/<?php echo implode('.',$fromTime); ?>/<?php echo implode('.',$toTime); ?>"><?php echo $val[0]['reopen'] ; ?></a></td>
				<td class="col-lg-2"><?php echo ($val[0]['closed'])+($val[0]['open'])+($val[0]['reopen']) ; ?></td>
			   </tr>
				
				<?php } ?>
			</tbody>
						
					</table>
				</td>
				<td>
				<table class="table table-hover table-bordered" style="width:100%;">
				<thead>
							<tr>
			<th colspan="5">
				Api Wise Complaints Count 
			</th>
		</tr>
		<tr>
			<th class="col-lg-2">VendorName</th>
			<th class="col-lg-1"><b>Open</b></th>
			<th class="col-lg-1"><b>Closed</b></th>
			<th class="col-lg-3"><b>Re-Open</b></th>
			<th class="col-lg-3"><b>Total</b></th>
		</tr>
		
			</thead>
			<tbody>
				<?php foreach ($dataset['api'] as $key => $val) {?>
				<tr>
				<td class="col-lg-2"><?php echo $val[0]['name'] ; ?></td>
				<td class="col-lg-1"><?php echo $val[0]['open'] ; ?></td>
				<td class="col-lg-1"><?php echo $val[0]['closed'] ; ?></td>
				<td class="col-lg-3"><a target="_blank" href="/panels/reOpenDetails/vendor/<?php echo $key ?>/<?php echo $fromDate; ?>/<?php echo $toDate; ?>/<?php echo implode('.',$fromTime); ?>/<?php echo implode('.',$toTime); ?>"><?php echo $val[0]['reopen'] ; ?></a></td>
				<td class="col-lg-2"><?php echo ($val[0]['closed'])+($val[0]['open'])+($val[0]['reopen']) ; ?></td>
			   </tr>
				
				<?php } ?>
			</tbody>
						
					</table>
					
				</td>
				
			</tr>
			
		</table>
		<?php } ?>
      
    </div>
    <div id="operator" class="tab-pane fade">
      <?php if(count($dataset['product'])>0){ ?>
		<table class="table table-hover table-bordered" style="width:40%;">
			<thead>
		<tr>
			<th class="col-lg-2">Operator Name</th>
			<th class="col-lg-1"><b>Open</b></th>
			<th class="col-lg-1"><b>Closed</b></th>
			<th class="col-lg-3"><b>Re-Open</b></th>
			<th class="col-lg-3">Total</th>
		</tr>
			</thead>
			
	  <?php } ?>
			<tbody>
				<?php  foreach ($dataset['product'] as $prodkey => $prodval) { ?>
				<tr>
				<td class="col-lg-2"><?php echo $prodval[0]['name'] ; ?></td>
				<td class="col-lg-1"><?php echo $prodval[0]['open'] ; ?></td>
				<td class="col-lg-1"><?php echo $prodval[0]['closed'] ; ?></td>
				<td class="col-lg-3"><a target="_blank" href="/panels/reOpenDetails/opr/<?php echo $prodkey ?>/<?php echo $fromDate; ?>/<?php echo $toDate; ?>/<?php echo implode('.',$fromTime); ?>/<?php echo implode('.',$toTime); ?>"><?php echo $prodval[0]['reopen'] ; ?></a></td>
				<td class="col-lg-1"><?php echo ($prodval[0]['closed'])+($prodval[0]['open'])+($prodval[0]['reopen']) ; ?></td>
			   </tr>
				<?php } ?>
			</tbody>
		</table>
    </div>
	  <div id="hour" class="tab-pane fade">
		  <?php  if (count($dataset['hour']) > 0) { ?>
		  
		  <table class="table table-hover table-bordered" style="width:40%;">
			  <thead>
				  
			  </thead>
			<tbody>
		<tr>
			<th class="col-lg-4">Between 1 hour</th>
			<td><?php echo $dataset['hour'][0]; ?></td>
		</tr>
		<tr>
			<th class="col-lg-4">Between 2 hour</th>
			<td><?php echo $dataset['hour'][1]; ?></td>
		</tr>
		<tr>
			<th class="col-lg-4">Between 3 hour</th>
			<td><?php echo $dataset['hour'][2]; ?></td>
		</tr>
		<tr>
			<th class="col-lg-4">Between 4 hour</th>
			<td><?php echo $dataset['hour'][3]; ?></td>
		</tr>
		<tr>
			<th class="col-lg-4">Between 5 hour</th>
			<td><?php echo $dataset['hour'][4]; ?></td>
		</tr>
		<tr>
			<th class="col-lg-4">Between 6 hour</th>
			<td><?php echo $dataset['hour'][5]; ?></td>
		</tr>
		<tr>
			<th class="col-lg-4">Between 7 hour</th>
			<td><?php echo $dataset['hour'][6]; ?></td>
		</tr>
		<tr>
			<th class="col-lg-4">Between 8 hour</th>
			<td><?php echo $dataset['hour'][7]; ?></td>
		</tr>
		<tr>
			<th class="col-lg-4">Between 9 hour</th>
			<td><?php echo $dataset['hour'][8]; ?></td>
		</tr>
			</tbody>
		  </table>
		  
		  <?php } ?>
      
    </div>
	  <div id="open" class="tab-pane fade">
		  
		  <?php  if (count($dataset['days']) > 0) { ?>
		  
		  <table class="table table-hover table-bordered" style="width:50%;">
			  <thead>
				  
			  </thead>
			<tbody>
				<tr>
					<th></th>
					<th>Open</th>
					<th>Closed</th>
				</tr>
		
		<tr>
			<th class="col-lg-7">Todays  Transactions</th>
			
			<td class="col-lg-1"><?php echo isset($dataset['opencount'][0]) ? $dataset['opencount'][0] : 0; ?></td>
			<td class="col-lg-1"><?php echo isset($dataset['days'][0]) ? $dataset['days'][0] : 0; ?></td>
		</tr>
		<tr>
			<th class="col-lg-7">1 Days Old Transaction</th>
			<td class="col-lg-1"><?php echo isset($dataset['opencount'][1]) ? $dataset['opencount'][1] : 0; ?></td>
			<td class="col-lg-1"><?php echo isset($dataset['days'][1]) ? $dataset['days'][1] : 0; ?></td>
		</tr>
		<tr>
			<th class="col-lg-7">2 Days Old  Transaction</th>
			<td class="col-lg-1"><?php echo isset($dataset['opencount'][2]) ? $dataset['opencount'][2] : 0; ?></td>
			<td class="col-lg-1"><?php echo isset($dataset['days'][2]) ? $dataset['days'][2] : 0; ?></td>
		</tr>
		<tr>
			<th class="col-lg-7">3 Days Old Transaction</th>
			
			<td class="col-lg-1"><?php echo isset($dataset['opencount'][3]) ? $dataset['opencount'][3] : 0; ?></td>
			<td class="col-lg-1"><?php echo isset($dataset['days'][3]) ? $dataset['days'][3] : 0; ?></td>
		</tr>
		<tr>
			<th class="col-lg-7">4 Days Old Transaction</th>
			<td class="col-lg-1"><?php echo isset($dataset['opencount'][4]) ? $dataset['opencount'][4] : 0; ?></td>
			<td class="col-lg-1"><?php echo isset($dataset['days'][4]) ? $dataset['days'][4] : 0; ?></td>
		</tr>
		
		<tr>
			<th class="col-lg-7">5 Days Old Transaction</th>
			<td class="col-lg-1"><?php echo isset($dataset['opencount'][5]) ? $dataset['opencount'][5] : 0; ?></td>
			<td class="col-lg-1"><?php echo isset($dataset['days'][5]) ? $dataset['days'][5] : 0; ?></td>

		</tr>
		<tr>
			<th class="col-lg-7">6 Days Old Transaction</th>
			<td class="col-lg-1"><?php echo isset($dataset['opencount'][6]) ? $dataset['opencount'][6] : 0; ?></td>
			<td class="col-lg-1"><?php echo isset($dataset['days'][6]) ? $dataset['days'][6] : 0; ?></td>
		</tr>
		
			</tbody>
		  </table>
		  
		 <?php } ?>
		 
      
    </div>
	   <div id="percent" class="tab-pane fade">
		<?php  if(count($dataset['modem'])>0){?>
		<table class="table  table-bordered" style="width:50%;">
			
			<tr>
				 
				<td style="width:25%">
					<table class="table table-hover table-bordered" style="width:100%;">
						<thead>
							<tr>
			<th colspan="5">
				Modem Wise Complaints Count 
			</th>
		</tr>
		<tr>
			<th class="col-lg-2">VendorName</th>
			<th class="col-lg-1"><b>Complaints Count</b></th>
			<th class="col-lg-1"><b>Percentage</b></th>
			
		</tr>
		
			</thead>
			<tbody>
				<?php foreach ($dataset['modem'] as $key => $val) {
					$totalCount = ($val[0]['closed'])+($val[0]['open'])+($val[0]['reopen']);
					?>
				<tr>
				<td class="col-lg-2"><?php echo $val[0]['name'] ; ?></td>
				<td class="col-lg-1"><?php echo $totalCount ; ?></td>
				<td class="col-lg-1"><?php echo round(($totalCount/$totalComplaint)*100,2) ; ?></td>
				
				
			   </tr>
				
				<?php } ?>
			</tbody>
						
					</table>
				</td>
				<td>
				<table class="table table-hover table-bordered" style="width:100%;">
				<thead>
							<tr>
			<th colspan="5">
				Api Wise Complaints Count 
			</th>
		</tr>
		<tr>
			<th class="col-lg-2">VendorName</th>
			<th class="col-lg-1"><b>Complaints Count</b></th>
			<th class="col-lg-1"><b>Percentage</b></th>
			
		</tr>
		
			</thead>
			<tbody>
				<?php foreach ($dataset['api'] as $key => $val) {
					$totalCount = ($val[0]['closed'])+($val[0]['open'])+($val[0]['reopen']);
					?>
				<tr>
				<td class="col-lg-2"><?php echo $val[0]['name'] ; ?></td>
				<td class="col-lg-1"><?php echo $totalCount ; ?></td>
				
				<td class="col-lg-2"><?php echo round(($totalCount/$totalComplaint)*100,2) ;?></td>
			   </tr>
				
				<?php } ?>
			</tbody>
						
					</table>
					
				</td>
				<td>
				<table class="table table-hover table-bordered" style="width:100%;">
				<thead>
							<tr>
			<th colspan="5">
				OperatorWise Complaints Count 
			</th>
		</tr>
		<tr>
			<th class="col-lg-2">Operator</th>
			<th class="col-lg-1"><b>Complaints Count</b></th>
			<th class="col-lg-1"><b>Percentage</b></th>
			
		</tr>
		
			</thead>
			<tbody>
				<?php foreach ($dataset['product'] as $key => $val) {
					$totalCount = ($val[0]['closed'])+($val[0]['open'])+($val[0]['reopen']);
					?>
				<tr>
				<td class="col-lg-2"><?php echo $val[0]['name'] ; ?></td>
				<td class="col-lg-1"><?php echo $totalCount ; ?></td>
				
				<td class="col-lg-2"><?php echo round(($totalCount/$totalComplaint)*100,2) ;?></td>
			   </tr>
				
				<?php } ?>
			</tbody>
						
					</table>
					
				</td>
				
			</tr>
			
		</table>
		<?php } ?>
      
    </div>
	  
  </div>

		
 
</div>
	</div>
	<script>
	

            // When the document is ready
            $(document).ready(function () {
                $('#frm_date, #to_date').datepicker({
                    format: "yyyy-mm-dd",
					//startDate: "-365d",
						endDate: "1d",
						multidate: false,
						autoclose: true,
						todayHighlight: true
                });  
            });
       

	function submit(){
		//var frm = $("#frm_time").val();
		//var to = $("#to_time").val();
		//var url = '/panels/getProcessTime/'+$("#datepicker").val()+"/"+frm+"/"+to;
		//window.location = url;
		window.location.href = "/panels/complainReport/"+$("#frm_date").val() + "/" + $("#to_date").val()+"/"+$("#frm_time_hrs").val()+"."+$("#frm_time_mins").val()+"/"+$("#to_time_hrs").val()+"."+$("#to_time_mins").val();
	}
	
	function showtable(type){
		
	
		$("#"+type).show();
	}
	

</script>