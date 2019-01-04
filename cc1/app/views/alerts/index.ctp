
<!-- <h1>Hello</h1> -->

<head>
<style type="text/css">
td {
	text-align: center;
}

/* for placeholder aligning in center */
::-webkit-input-placeholder {
   text-align: center;
}

:-moz-placeholder { /* Firefox 18- */
   text-align: center;  
}

::-moz-placeholder {  /* Firefox 19+ */
   text-align: center;  
}

:-ms-input-placeholder {  
   text-align: center; 
}
</style>
</head>

<body>

	<!-- Tab panes -->
	<div class="tab-content">
		<!-- 	<div class="tab-pane active"> -->
		<!-- 		<div class="btn-group"> -->
		<form role="form" class="form-horizontal" action="/alerts/index/" method="POST">
			<div class="row">
				<div class="col-md-2 col-md-offset-1">
					<div class="form-group" style="text-align: center; width: 180px; margin-left: 5px; margin-right: 5px">
						<label for="drop-type">Drop Type</label>
						<select class="btn btn-default form-control" id="drop-type" name="droptype">
<!-- 							<option value="0">All</option> -->
							<option value="1" <?php if($dropId == 1) { echo "selected" ;}?>>Gradually Dropping</option>
							<option value="2" <?php if($dropId == 2) { echo "selected" ;}?>>Dropped Out</option>
						</select>
					</div>
				</div>		
								
				<div class="col-md-2 col-md-offset-1">
					<div class="form-group" style="text-align: center; width: 180px; margin-right: 5px; margin-left: 5px;">
						<label for="drop-date-from">Drop Date - from  </label>
						<input type="text" id="drop-date-from" class = "form-control" name="dropdate_from" placeholder = "Drop Date" value = "<?php echo $dropDate_from; ?>">
					</div>
				</div>

				<div class="col-md-2 col-md-offset-1">
					<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px; width: 180px">
						<label for="drop-date-to">Drop Date - to </label>
						<input type="text" id="drop-date-to" class = "form-control" name="dropdate_to" placeholder = "Drop Date" value = "<?php echo $dropDate_to; ?>">
					</div>
				</div>
				<div class="col-md-1">
					<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px; margin-top:20px;">
						<button type="submit" class="btn btn-primary">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</button>
					</div>
				</div>	
				<div class="col-md-1 col-md-offset">
					<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px; margin-top:20px;">
						<a href = "/alerts/downloadDumpData/<?php echo $dropId;?>/<?php echo $dropDate; ?>/<?php echo $callDate; ?>" target = "_blank" class="btn btn-success">
							<span class="glyphicon glyphicon-download" aria-hidden="true"> Excel Data</span>
						</a>
					</div>
				</div>			
			</div>
		</form>
	</div>
	
	<br><br>
	
	<div class="table-responsive">
		<table class="table table-hover table-bordered table-condensed">
			<thead>
				<tr style="text-align: center;">
					<th class="field-label active">
						<label>#</label>
					</th>
<!-- 					<th class="field-label active"> -->
<!-- 						<label>Name</label> -->
<!-- 					</th> -->
					<th class="field-label active" style = "width: 2%; text-align: center;">
						<label>Shop Name</label>
					</th>
					<th class="field-label active" style = "width: 7%; text-align: center;">
						<label>Mobile</label>
					</th>
					<th class="field-label active" style = "width: 7%; text-align: center;">
						<label>Created On</label>
					</th>
					<th class="field-label active" style = "width: 7%; text-align: center;">
						<label>Distributor Details</label>
					</th>
                                        <th class="field-label active" style = "width: 7%; text-align: center;">
						<label>State</label>
					</th>
                                        <th class="field-label active" style = "width: 7%; text-align: center;">
						<label>Distributor Active</label>
					</th>
                                        <th class="field-label active" style = "width: 7%; text-align: center;">
						<label>Last Trans</label>
					</th>
                                        <th class="field-label active" style = "width: 7%; text-align: center;">
						<label>Retailer Age</label>
					</th>
                                        <th class="field-label active" style = "width: 7%; text-align: center;">
						<label>Recharge Mode</label>
					</th>
                                        
<!-- 				<th class="field-label active" style = "width: 8%; text-align: center;"> -->
<!-- 						<label>Distributor Mobile</label> -->
<!-- 					</th>					 -->
<!-- 					<th class="field-label active" style = "width: 8%; text-align: center;">  -->
<!-- 						<label>Down Type</label> -->
<!-- 					</th> -->
					<th class="field-label active" style = "width: 8%; text-align: center;">
						<label>Sale 4th last Week From Down Date</label>
					</th>
					<th class="field-label active" style = "width: 8%; text-align: center;">
						<label>Sale 3rd last Week From Down Date</label>
					</th>
					<th class="field-label active" style = "width: 8%; text-align: center;">
						<label>Sale 2nd last Week From Down Date</label>
					</th>
					<th class="field-label active" style = "width: 8%; text-align: center;">
						<label>Sale last Week From Down Date</label>
					</th>
					<th class="field-label active" style = "width: 8%; text-align: center;">
						<label>Down Date</label>	
					</th>
					<th class="field-label active" style = "width: 5%; text-align: center;">
						<label>Call status and Comment Details</label>					
					</th>
	<!-- 				<th class="field-label active" style = "width: 5%; text-align: center;"> -->
<!-- 						<label>Comment Link</label> -->
<!-- 					</th> -->
					<th class="field-label active" style = "width: 8%; text-align: center;">
						<label>Sale 1st Week From call date</label>
					</th>
					<th class="field-label active" style = "width: 8%; text-align: center;">
						<label>Sale 2nd Week from call date</label>
					</th>
					<th class="field-label active" style = "width: 8%; text-align: center;">
						<label>Sale 3rd Week from call date</label>
					</th>
					<th class="field-label active" style = "width: 8%; text-align: center;">
						<label>Sale 4th week from call date</label>
					</th>
				</tr>
			</thead>
						
			<tbody id = "table-body">			
            	<?php	
            		$count = 1;
					foreach ( $details as $id => $fields ) {
						echo "<tr>";
						echo "<td>" . ($count++) . "</td>";
						echo "<td>" .  $fields ['shopname'] . "<br>(" . $fields ['tag'] . ")</td>";
						echo "<td><a target='_blank' href='/panels/retInfo/".$fields ['mobile']."'>" .  $fields ['mobile'] . "</a><br/><a href='/shops/graphRetailer/?type=r&id=".$id."' target='_blank'>Graph</a></td>";
						echo "<td>" .  $fields ['created'] . "</td>";
						echo "<td>" .  $fields ['distributor_company'] . "(" .  $fields ['distributor_mobile'] . ")</td>";
                                                echo "<td>" .  $fields ['state'] . "</td>";
                                                echo "<td>" .  $fields ['dist_active'] . "</td>";
                                                echo "<td>" .  $fields ['last_txn'] . "</td>";
                                                echo "<td>" .  $fields ['retailer_age'] . "</td>";
                                                echo "<td>" .  $fields ['recharge_mode'] . "</td>";
                                                
// 						$downId = $fields ['down_type_id'];
// 						if($downId == 1)
// 							echo "<td> Gradually Dropping</td>";
// 						else if ($downId == 2)
// 							echo "<td> Dropped Out</td>";
						echo "<td>" .  $fields ['sale_4th_last_week'] . "</td>";
						echo "<td>" .  $fields ['sale_3rd_last_week'] . "</td>";
						echo "<td>" .  $fields ['sale_2nd_last_week'] . "</td>";
						echo "<td>" .  $fields ['sale_last_week'] . "</td>";
						echo "<td>" .  $fields ['down_date'] . "</td>";
				?>
			
						<td>
<!-- 							<button type = "button" class = "btn btn-link"> -->
							<?php 
								  $downId = $fields ['down_type_id'];
								  $retailerDropId = $fields ['unique_id'];
								  if( $fields ['call_flag'] == 0){
									echo "<button class = 'btn btn-link' onclick = 'setRetailerId($id,$retailerDropId)'>";
									echo "<span class = 'glyphicon glyphicon-phone-alt' style = 'color: rgb(255,0,0);'></span>";
									echo "</button>";
								  }
								  elseif ($fields ['call_flag'] == 1){
								  	echo "<button class = 'btn btn-link' onclick = 'setRetailerId($id,$retailerDropId)'>";
								  	echo "<span class = 'glyphicon glyphicon-phone-alt' style = 'color: rgb(0,240,0);'></span>";
								  	echo "</button>";
								  }
							?>								
<!-- 							</button> -->
							<button class = "btn btn-link btn-sm" onclick = "showDetails(<?php echo $downId;?>,<?php echo $id; ?>)">
								Comments
							</button>
							<?php $url = "/shops/graphRetailer/?type=r&id=".$id;?>
							<a href="<?php echo $url;?>" target="_blank" class = "btn btn-link btn-sm">
								Graph
							</a>
						</td>		
				<?php
						echo "<td>" .  $fields ['sale_1st_week_post_call'] . "</td>";
						echo "<td>" .  $fields ['sale_2nd_week_post_call'] . "</td>";
						echo "<td>" .  $fields ['sale_3rd_week_post_call'] . "</td>";
						echo "<td>" .  $fields ['sale_4th_week_post_call'] . "</td>";
						echo "</tr>";									
					}
				?> 
			</tbody>
		</table>
	</div>
	
	<!-- MODAL DATA FOR CALL DOING-->
	<div class="modal fade" id="comment-entry" tabindex="-1" role="dialog" aria-labelledby="comment-entry">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class = "modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title" align="center">Retailer Down Reason</h3>
				</div>
				
				<form role="form" class="form-horizontal" method="POST">	
					<div class = "modal-body">			
						<div class = "row">
							<div class="col-md-4 col-md-offset-1" align = "center">
								<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px; width: 200px;" id = "tagging">
								<!-- html comes from java script code here -->
								</div>
							</div>
							<div class="col-md-5 col-md-offset-1" align = "center">
								<div class="form-group" style="text-align: center; margin-right: 10px;">
									<label for="comments"> Comments </label>
							 		<textarea class="form-control" id="comment" rows="4" cols="50"></textarea>						
								</div>
							</div>	
							<input type = "hidden">			
						</div>	
					</div>
															
					<div class="modal-footer">
					     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					     <button type="button" class="btn btn-success" onclick="insertReason()" data-dismiss="modal">Submit</button>
					</div> 
				</form>
			</div>
		</div>
	</div>

<!-- MODAL DATA FOR SEEING DETAILS -->
	<div class="modal fade" id="view-details" tabindex="-1" role="dialog" aria-labelledby="view-details">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class = "modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title" align="center">Comment Details</h3>
				</div>
				
				<div class = "modal-body table-responsive">
					<table class="table table-hover table-bordered table-condensed">
						<thead>
							<tr style="text-align: center;">							
								<th class="field-label active" style = "text-align: center; width: 20%;"> Comment Made By	</th>
								<th class="field-label active" style = "text-align: center; width: 30%;"> Comment Text	</th>
								<th class="field-label active" style = "text-align: center; width: 20%;"> Problem Tag	</th>
								<th class="field-label active" style = "text-align: center; width: 15%;"> Down Date	</th>
								<th class="field-label active" style = "text-align: center; width: 15%;"> Call Time	</th>
							</tr>													
						</thead>
						<tbody id = "comment-table">
								<!-- data comes from ajax calling from showDetails() -->
						</tbody>
					</table>			
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

</body>

<script>
$('#drop-date').datepicker({
    format: "yyyy-mm-dd",
	multidate: false,
	autoclose: true,
	todayHighlight: true
});  

$('#call-date').datepicker({
    format: "yyyy-mm-dd",
	multidate: false,
	autoclose: true,
	todayHighlight: true
}); 

/**
 * retailerId : id of the retailer in 'retailers'table
 * retailerDropId : id of the the entry of the retailer dropped details in 'retailers_drop' table
 */
function setRetailerId(retailerId, retailerDropId){
	$('#comment-entry').modal('toggle');
	var html = '';
	$("#tagging").html('');
	html += "<label for='drop-reason'>Drop Reason</label>";
	html += "<select class='btn btn-default form-control' id='drop-reason' name='drop_reason'>";
	html += "<?php 	
				foreach ($tags as $id => $tag){
					echo "<option value = '" . $id . "'>" . $tag . "</option>";
				}									
			?>";
	html += "</select>";
	html += "<input type = 'hidden' id = 'retailer-id' name = 'retailer_id' value = '" + retailerId + "'>";
	html += "<input type = 'hidden' id = 'retailer-drop-id' name = 'retailer_id' value = '" + retailerDropId + "'>";
	$("#tagging").append(html);
}

function insertReason(){
// 	alert("Hii");
	var tag_id = $('#drop-reason').val();
	var comment = $('#comment').val();
	var userMobile = "<?php echo $userMobile;?>";
	var retailer_id = $('#retailer-id').val();
	var retailerDropId = $('#retailer-drop-id').val();
// 	alert(tag_id + "\n" + comment + "\n" + userMobile + "\n" + retailer_id);

	var url = '/alerts/insertCommentData/';
    $.ajax({
         url: url,
         type: "POST",
         data: {"tag_id": tag_id, "comment": comment, "userMobile": userMobile, "retailer_id": retailer_id, "retailerDropId": retailerDropId},
         dataType: "json",
		  success: function(data) {
			if(data.status == "success"){
				alert("Comment Inserted Successfully");		
 			}				 
		 }
   });
	
   
}

/**
 * downId: 1=> retailers gradually dropping; 2=> retailers dropped out 
 * retailerId => id of the retailer in the retailere table
 */
function showDetails(downId, retailerId){
// 	alert(id);comment-table
	var url= '/alerts/retrieveCommentDetails/';
	var html = '';
	$("#comment-table").html('');
    $.ajax({
        url: url,
        type: "POST",
        data: {"downId": downId, "retailerId": retailerId},
        dataType: "json",
		  success: function(data) {
			  console.log(data);
			if(data.status == "success"){
				$.each(data.response,function(key,arr){
					if(arr['type'] == 'retailers_drop'){
						html += "<tr>";
						html += "<td style = 'width: 20%;'>" + arr['name'] + arr['type'] +"</td>";
						html += "<td style = 'width: 30%;'>" + arr['comments'] + "</td>";
						html += "<td style = 'width: 20%;'>" + arr['tag'] + "</td>";
						html += "<td style = 'width: 15%;'>" + arr['down_date'] + "</td>";
						html += "<td style = 'width: 15%;'>" + arr['call_time'] + "</td>";
						html += "</tr>";
					}	
				});
				$("#comment-table").append(html);	
				$('#view-details').modal('toggle');	
			}
			else
				alert('No comments inserted');					 
		 }	
  });
	
}

function downloadData(dropId, dateDrop, dateCall){
	var droptype = dropId;
	var dropdate = dateDrop;
	var calldate = dateCall;

	var url = '/alerts/downloadDumpData/';
    $.ajax({
         url: url,
         type: "POST",
         data: {"droptype": droptype, "dropdate": dropdate, "calldate": calldate},
         dataType: "json",
		  success: function(data) {
			if(data.status == "success"){
				alert("Comment Inserted Successfully");		
 			}				 
		 }
   });

}

</script>