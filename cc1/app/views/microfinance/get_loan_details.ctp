<link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function(){
    $('.table').dataTable({
    "order": [[3, "desc" ]],
    "pageLength":10,
    "lengthMenu": [10,100, 200, 500],
    });
    
    
$('#reject_lead_submit').on('click',function(){
        console.log("inside reject loan  lead")
        var pay1_comment = $.trim($('#pay1comment').val());  
        if(pay1_comment=="")
        {
            alert("Enter reason");
            return false;
        }
        var application_id = $('#application_id').val();
        console.log("application id"+ application_id);   

        var url =  '/microfinance/rejectLead';
        var params = {'application_id':application_id, status:'0',rof:pay1_comment};
        $.post(url, params, function(response){
           jsonResponse = JSON.parse(response);
           console.log(jsonResponse);
           if(jsonResponse['errCode'] == 0){
             console.log("inside success");
             location.reload();
            }
        }); 
    });


$('#approve_lead_submit').on('click',function(){
        var application_id = $('#application_id').val();
        var amount = $('#amount').val();
        var interest = $('#interest').val();
        var processingFee = $('#processing_fee').val();
        var vendorId = $('#vendorSelect').val();
        
        if(application_id =="" || amount == "" || interest == "" || processingFee == "" || vendorId == "")
        {
            alert("Fill all the  fields");
            return false;
        }
 
        $.ajax({
          url : "/microfinance/approveLead",
          type: "POST",
          dataType:'json',
          data : {application_id:application_id,status:'1', vendor_id: vendorId, amount: amount, interest_rate : interest, processing_fee : processingFee},
          success:function(res){
             console.log(res);
              if(res.status=='success'){
                  $('#approve_lead_modal').modal('hide');
                  location.reload();
              }
              else
              {
                  alert("Could not approve");
              }
              
            }
        });
    });
});


function verifyDocs(application_id) {
         console.log("hello");
        console.log(application_id);
        var url =  '/microfinance/verifyDocs';
        var params = {'application_id':application_id};
        $.post(url, params, function(response){
           jsonResponse = JSON.parse(response);
           console.log(jsonResponse);
           if(jsonResponse['errCode'] == 0){
             console.log("inside success");
             location.reload();
            }
        });
}

function rejectDocs(application_id) {
        var url =  '/microfinance/rejectDocs';
        var params = {'application_id':application_id};
        $.post(url, params, function(response){
           jsonResponse = JSON.parse(response);
           if(jsonResponse['errCode'] == 0){
             console.log("inside success");
             location.reload();
            }
        });
}

function submitToNBFC(application_id) {
        var url =  '/microfinance/submitToNBFC';
        var params = { 'application_id':application_id};
        $.post(url, params, function(response){
           jsonResponse = JSON.parse(response);
           if(jsonResponse['errCode'] == 0){
             console.log("inside success");
             location.reload();
            }
        });
  
}



function rejectLead(application_id)
{
    $('#application_id').val(application_id);
    $('#amount').val('');
    $('#processing_fee').val('');
    $('#interest').val('');
    
    $('#reject_lead_modal').modal('show');
    
}

function approveLead(application_id)
{
    $('#application_id').val(application_id);
    $('#pay1comment').val('');
    $('#approve_lead_modal').modal('show');
    
}

function disburseLoan(application_id){
        var url =  '/microfinance/disburseLoan';
        var params = { 'application_id':application_id};
        $.post(url, params, function(response){
           jsonResponse = JSON.parse(response);
           if(jsonResponse['errCode'] == 0){
             console.log("inside success");
             alert("Successfully disbursed");
             location.reload();
            }
        });
}



function showDocDetails(docInfo){
  console.log(docInfo);
  $('.doc_detail_modal_body').empty();
  $('.doc_detail_modal_body').append(docInfo);
  $('#doc_detail_modal').modal('show');
}
</script>


<div class="container-fluid">
    <div>
        <div class="row">
        <div class="col-lg-12 text-center">
            <h3>Loan List</h3>
        </div>
        </div>
    </div>
<nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <ul class="nav navbar-nav">
                        <li class="<?php
                        if ($loantype == "0") {
                            echo "active";
                        }
                        ?>" ><a href = "/microfinance/setLoanDetails/0/" >Paid</a></li>
                        <li class="<?php
                        if ($loantype == "1") {
                            echo "active";
                        }
                        ?>"><a href = "/microfinance/setLoanDetails/1/">Running</a></li>
                         <li class="<?php
                        if ($loantype == "2") {
                            echo "active";
                        }
                        ?>"> <a href = "/microfinance/getLoanDetails/2/">Loan List</a></li>
                    </ul>
                </div>
            </div>
        </nav> <br><br>
    <div>
    <table class="table table-bordered table-hover">
    <thead>
     <tr>
     <th>User Id</th>
     <th>Phone Number</th>
     <th>User Roles</th>
     <th>Application Number</th>
     <th>Loan Amount</th>
     <th>Loan Duration</th>
     <th>Document Status</th>
     <th>Application Status</th>
     <th>User Application Status</th>
     <th>Offer Details</th>
     <th>Vendor</th>
     
     <th>Disbursal Status</th>
     <th>Created Timestamp</th>
     <th>Action Buttons</th>
     
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($loanDetails as $loan) { ?>
      <tr>
      <td><a href= <?php echo "/panels/retInfo/".$loan['mobile'] ?>><?php echo $loan['user_id'] ?></a></td>
          <td><?php echo $loan['mobile'] ?></td>
                    <td><?php echo $loan['group_name'] ?></td>
      <td><?php echo $loan['application_number'] ?></td>
                <td><?php echo $loan['loan_amount'] ?></td>
 
           <td><?php echo $loan['loan_duration'] ?></td>

      <td><?php echo $loan['document_status'] ?>
      <a href='javascript:void(0)' style="margin:5px"  onclick="showDocDetails('<?php echo $loan['doc_details'] ?>')"
                                class="btn btn-sm btn-info">View Docs</a>
      </td>

      <td><?php echo $loan['application_status'] ?></td>
       <td><?php echo $loan['user_application_status'] ?></td>
       <td><?php echo $loan['offer_details'] ?></td>
       <td><?php echo $loan['vendor'] ?></td>
       
       <td><?php echo $loan['disbursal_status'] ?></td>
        <td><?php echo $loan['created_timestamp'] ?></td>
      <td>
      
      <?php if($loan['stage_status'] == '1'){ ?>
           <?php if($loan['doc_approval_mode'] == 0) {?>
	           <a class="btn btn-sm btn-info" style="margin:5px" href=<?php echo '/docmanagement/getUserInformation?mobile=' . $loan['mobile']?> >Info Management</a>
	           <a href='javascript:void(0)' style="margin:5px"  onclick="verifyDocs('<?php echo $loan['application_id'] ?>')"
	                                class="btn btn-sm btn-success">Verify Docs</a>
	           <a href='javascript:void(0)' style="margin:5px"  onclick="rejectDocs('<?php echo $loan['application_id'] ?>')"
	                                class="btn btn-sm btn-danger">Reject Docs</a> 
                                
           <?php } ?>
                                
           <?php  if($loan['application_approval_mode'] == 0){ ?>                    
           <a href='javascript:void(0)' style="margin:5px"  onclick="rejectLead('<?php echo $loan['application_id'] ?>')"
                                class="btn btn-sm btn-danger">Reject Application</a>
                                
            <?php } ?>
                             
      <?php } elseif(($loan['stage_status'] == '2')   && ($loan['application_approval_mode'] == 0) ) {?>
       <a href='javascript:void(0)' style="margin:5px"  onclick="approveLead('<?php echo $loan['application_id'] ?>')"
                                class="btn btn-sm btn-success">Loan Offer</a>
                                
      <a href='javascript:void(0)' style="margin:5px"  onclick="rejectLead('<?php echo $loan['application_id'] ?>')"
                                class="btn btn-sm btn-danger">Reject Application</a>
      <?php } 

      elseif($loan['stage_status'] == '3') {?>
      	<p>TnC Acceptance Pending</p>
	 <?php }
     elseif(($loan['stage_status'] == '4')  && ($loan['disbursal_mode'] == 0)) {?>
            <a target="_blank" style="margin:5px" class="btn btn-sm btn-success" onclick="disburseLoan('<?php echo $loan['application_id'] ?>')">Disburse Loan</a>
      <?php } ?>
         
      </td>
      </tr>
 
    <?php
    } ?>

    </tbody>
    </table>
    </div>
    
    
    <div id="reject_lead_modal" class="modal fade" >
        <div class="modal-dialog" style="width:500px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Reject Application</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5"><label class="">Reason of Rejection</label></div>
                    <div class="col-lg-7"><textarea id="pay1comment" name="pay1comment"></textarea></div>
                </div>

                <input type="hidden" name="application_id" id="application_id" value="">
            </div>
            <div class="modal-footer">
                <div class="save_comments col-lg-2 col-lg-offset-4" id="save_comments">
                    <button type="button" class="btn btn-primary" id="reject_lead_submit">Submit</button>
                </div>
                <button type="button" class="btn btn-default col-lg-2" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
       </div>
       
       
    <div id="approve_lead_modal" class="modal fade" >
        <div class="modal-dialog" style="width:500px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Approve Application</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5"><label class="">Amount</label></div>
                    <div class="col-lg-7"><input id="amount" name="pay1comment"></textarea></div>
                </div>
                
                <div class="row">
                    <div class="col-lg-5"><label class="">Yearly Interest Rate</label></div>
                    <div class="col-lg-7"><input id="interest" name="pay1comment"></input></div>
                </div>
                
                <div class="row">
                    <div class="col-lg-5"><label class="">Processing Fee</label></div>
                    <div class="col-lg-7"><input id="processing_fee" name="processing_fee"></input></div>
                </div>
                
                   <div class="row">
                    <div class="col-lg-5"><label class="">Vendor Id</label></div>
                    <div class="col-lg-7">

                 <select id="vendorSelect" name="vendorSelect" >
	                <?php
	                foreach($vendorList as $key => $vendorData) {
	                    $selected = '';
	                    echo '<option value="'.$vendorData['id'].'" '.$selected.'>'.$vendorData['name'].'</option>';                                        
	                }
	                ?>
	        </select>


                </div>
                </div>
                <input type="hidden" name="application_id" id="application_id" value="">
            </div>
            <div class="modal-footer">
                <div class="save_comments col-lg-2 col-lg-offset-4" id="save_comments">
                    <button type="button" class="btn btn-primary" id="approve_lead_submit">Submit</button>
                </div>
                <button type="button" class="btn btn-default col-lg-2" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
       </div>
       
       
      <div id="doc_detail_modal" class="modal fade" >
        <div class="modal-dialog" style="width:500px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Document Details</h4>
            </div>
            <div class="doc_detail_modal_body"> 
           
            </div>
        </div>
        </div>
       </div>
</div>