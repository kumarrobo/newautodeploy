
<html>

<head>
<style type="text/css">
.autocomplete-suggestions {
	border: 1px solid #999;
	background: #fff;
	cursor: default;
	overflow: auto;
}

.autocomplete-suggestion {
	padding: 10px 5px;
	font-size: 1.0em;
	white-space: nowrap;
	overflow: hidden;
}

.autocomplete-selected {
	background: #f0f0f0;
}

.autocomplete-suggestions strong {
	font-weight: normal;
	color: #3399ff;
}
</style>
</head>

<body>

	<!-- Tab panes -->
	<div class="tab-content">
		<!-- 	<div class="tab-pane active"> -->
		<!-- 		<div class="btn-group"> -->
		<form role="form" class="form-horizontal" action="/cashpayment/index/" method="POST">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group"
						style="text-align: center; margin-right: 5px; margin-left: 5px;">
						<label for="date">Enter Client Name</label> 
						<input type="text" class="form-control autocomplete" id="client" name="client_name" placeholder="Client Name">
						<input type="hidden" class="form-control autocomplete" id="client-id" name="client_id">
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group"
						style="text-align: center; margin-right: 5px; margin-left: 5px;">
						<label for="date">From Date</label>
						<input type="text" class="form-control" id="from-date" name="fromdate">
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px;">
						<label for="date">To Date</label>
						<input type="text" class="form-control" id="to-date" name="todate" value="">
					</div>
				</div>

				<div class="col-md-1">
					<div class="form-group"
						style="text-align: center; margin-left: 5px;">
						<label for="status">Status</label>
						<select class="btn btn-default" id="status" name="status">
							<option value="1">Collected</option>
							<option value="0">Pending</option>
							<option value="2">Settled</option>
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px; margin-top: 23px;">
						<button type="button" class="btn btn-primary" onclick="loadData()">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<br />
	<br>
	<div class="table-responsive">
		<table class="table table-hover table-bordered">
			<thead>
				<tr style="text-align: center;">
					<th class="field-label active" style = "width: 5%">
						<label>#</label>
					</th>
					<th class="field-label active" style = "width: 12%">
						<label>Transaction Id</label>
					</th>
					<th class="field-label active" style = "width: 14%">
						<label>Company Name</label>
					</th>
					<th class="field-label active" style = "width: 10%">
						<label>Amount</label>
					</th>
					<th class="field-label active" style = "width: 8%">
						<label>Status</label>
					</th>
					<th class="field-label active" style = "width: 10%">
						<label>In Time</label>
					</th>
					<th class="field-label active" style = "width: 12%">
						<label>Recieved Time</label>
					</th>					
					<th class="field-label active" style = "width: 10%">
						<label>Settled Time</label>
					</th>
					<th class="field-label active" style = "width: 10%">
						<label>Expiry Time</label>
					</th>
					<th class="field-label" style = "width: 10%"></th>
				</tr>
			</thead>

			<tbody id = "transaction-data">
				<!-- 				  table data comes here     called through ajax-->
					
    		</tbody>
		</table>
	</div>
	
	<!-- Modal data -->
	<div class="modal fade bs-example-modal-lg" id="settlement-data" tabindex="-1" role="dialog" aria-labelledby="settlement-data">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class = "modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title" id="settlement-data" align="center">Transaction Settlement Details</h3>
				</div>
				
<!-- 				<img src = "/img/ajax-loader-2.gif" id = "imageloader"></img> -->
<!-- 					<h1>hfbgvuwekjr</h1> -->
				<form role="form" class="form-horizontal" action="/cashpayment/insertSettlementData/" method="POST">	
					<div class = "modal-body">			
						<div class = "row">
							<div class="col-md-3" align = "center">
								<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px;" id = "transaction_id">
									<!-- data comes from ajax -->
								</div>
							</div>
							<div class="col-md-3" align = "center">
								<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px;" id = "transaction_amount">
									<!-- data comes from ajax -->
								</div>
							</div>
							<div class="col-md-3" align = "center">
								<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px;" id = "commission_amount">
									<!-- data comes from ajax -->
								</div>
							</div>
							<div class="col-md-3" align = "center">
								<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px;" id = "settlement_amount">
									<!-- data comes from ajax -->
								</div>
							</div>
						</div>
	<br><br>
						<div class = "row">
							<div class = "col-md-6" align = "center">
								<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px;" id = "settled_by">
							<!-- data comes from ajax -->
								</div>
							</div>
							<div class = "col-md-6" align = "center">
								<div class="form-group" style="text-align: center; margin-right: 5px; margin-left: 5px;" id = "comment">
								<!-- data comes from ajax -->
								</div>
							</div>						
						</div>
					</div>
															
					<div class="modal-footer">
					     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					     <button type="button" class="btn btn-success" onclick = "insertSettlementData()" data-dismiss="modal">Submit</button>
					</div> 
				</form>
			</div>
		</div>
	</div>
</body>

</html>


<script>

$(function(){
var data = <?php echo $clientDetails;?>;
// console.log(data);
$('.autocomplete').autocomplete({
	  lookup: data,
	    onSelect: function (suggestion) {
		    $('#client-id').val(suggestion.data);
// 		    console.log(suggestion);
	}
 })
});


$('#from-date').datepicker({
    format: "yyyy-mm-dd",
	multidate: false,
	autoclose: true,
	todayHighlight: true
});  

$('#to-date').datepicker({
    format: "yyyy-mm-dd",
	multidate: false,
	autoclose: true,
	todayHighlight: true
}); 

function loadData(){
  	 var client_id = $('#client-id').val();     
  	 var from_date = $('#from-date').val();
  	 var to_date = $('#to-date').val();	
  	 var status = $('#status').val();
  	 var html = '';
     var url = '/cashpayment/loadTransactionData/';

     $("#transaction-data").html('');
     $.ajax({
          url: url,
          type: "POST",
          data: {"client_id": client_id, "from_date": from_date, "to_date": to_date, "status": status},
          dataType: "json",
			  success: function(data) {
// 			  alert(data);
// 				console.log(data);comment
				if(data.success_status == "success"){
	 				$.each(data.response,function(index,arr){
// 						console.log(index);
// 	 			 		console.log( arr['expiry_time']);
						html += "<tr style='text-align: center;'>";
							html +=	"<td style = 'width: 5%'>" + (index+1) + "</td>";
							html += "<td style = 'width: 12%'>" + arr['id'] + "</td>";
							html += "<td style = 'width: 14%'>" + arr['company_name'] + "</td>";
							html += "<td style = 'width: 10%'>" + arr['amount'] + "</td>";
							html += "<td style = 'width: 8%'>" + arr['status'] + "</td>";
							html += "<td style = 'width: 10%'>" + arr['intime'] + "</td>";
							html += "<td style = 'width: 12%'>" + arr['updated_time'] + "</td>";
							html += "<td style = 'width: 10%'>" + arr['settled_date'] + "</td>";
							html += "<td style = 'width: 10%'>" + arr['expiry_time'] + "</td>";
							html += "<td style = 'width: 10%'>";
								html += "<button type='button' class='btn btn-link' onclick = 'loadSettlementData(" +arr['id'] + ")' data-toggle='modal' data-target='#settlement-data'>";
								html += "<span class='glyphicon glyphicon-check'></span> Make Settlement";
								html += "</button>";
							html += "</td>";
						html += "</tr>";
					});
	 			}
//                  console.log(html);
				 $("#transaction-data").append(html);
			 }
      });
}

function loadSettlementData(transaction_id){
	var url = '/cashpayment/loadSettlementData/';
	var txn_id = transaction_id;
	var html = '';


	$('#transaction_id').html('');
	$('#transaction_amount').html('');
	$('#commission_amount').html('');
	$('#settlement_amount').html('');
	$("#settled_by").html('');
	$("#comment").html('');
	$.ajax({
        url: url,
        type: "POST",
        data: {"txn_id": txn_id},
        dataType: "json",
			  success: function(data) {
// 				console.log(data);
				if(data.status == "success"){
	 				$.each(data.response,function(key,val){
		 				html = '';
		 				html += "<label>" + key + "</label>";
		 				html += "<input type = 'text' class = 'form-control' id= '"+key+"_val' value = "+val+">";
						$("#"+key).append(html);
// 						console.log(html);
						html = '';
					});
	 			}
//              
				html = "<label> settled by </label>" 			
				html += "<input type = 'text' class = 'form-control' id = 'settled_by_val'>";
// 				console.log(html);
				$("#settled_by").append(html);
				
				html = '';
				html = "<label>comment</label>";
				html += "<textarea class='form-control' id ='comment_val' rows='3' cols='30'></textarea>";
// 				console.log(html);
				$("#comment").append(html);
// 				 $("#transaction-data").append(html);
			 }
    });
}

function insertSettlementData(){
	var url = "/cashpayment/insertSettlementData/";
	var txn_id = $('#transaction_id_val').val();
	var txn_amt = $('#transaction_amount_val').val();
	var commission_amt = $('#commission_amount_val').val();
	var settlement_amt = $('#settlement_amount_val').val();
	var settled = $("#settled_by_val").val();
	var comment = $("#comment_val").val();

	$.ajax({
		url: url,
		type: "POST",
		data: {"txn_id":txn_id, "txn_amt":txn_amt , "commission_amt": commission_amt, "settlement_amt":settlement_amt, "settled": settled, "comment":comment},
		dataType: "json",
		 success: function(data){
			 if(data.status == "success"){
				 alert("Transaction settled successfully");
					// refresh data
				 loadData();
			 }			 	
			 else
				 alert("Transaction not settled");
		 }

	});
}
</script>