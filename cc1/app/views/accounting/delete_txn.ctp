<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">

<style>
    .tab {
        overflow: hidden;
        border: 1px solid #428bca;
        background-color: #f1f1f1;
        height: 40px;
        border-radius: 10px;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 16px;
        line-height: 0.8em;
        color: gray;
    }

    .tab button:hover {
        background-color: #428bca;
        color: #fff;
    }

    .tab button.active {
        background-color: #fff;
        color: #428bca;
        font-weight: 600;
    }

    thead{
        background-color: #428bca;
        color: #fff;
    }
</style>
<div class="tab">

    <ul class="nav nav-tabs">
        <li><button class="tablinks" onclick="window.location = '/accounting/txnUpload'">File Upload</button></li>
        <li><button class="tablinks active" onclick="window.location = '/accounting/autoUpload'">Txn Entry</button></li>
        <li><button class="tablinks" onclick="window.location = '/accounting/bankTxnListing'">Txn Listing</button></li>
        <li><button class="tablinks dropdown-toggle" onclick="window.location = '/accounting/closingBalanceReport'">Closing Balance Report</button></li>
        <!--<li><button class="tablinks" onclick="window.location='/accounting/limitReconsilationReport'">Limit Reconsilation Report</button></li>-->
        <li><button class="tablinks" onclick="window.location = '/accounting/bankStatements'">Bank Statements</button></li>
        <li><button class="tablinks" onclick="window.location = '/accounting/ledger'">Ledger</button></li>
        <li><button class="tablinks" onclick="window.location = '/accounting/debitSystem'">Debit System</button></li>
    </ul>
</div>
<br/>

<span style="color: red; text-align: center;"><?php echo $this->Session->flash(); ?></span>

<br/>

<div style="float:left;"><span style="font-weight:bold;">Date : </span></div>
<div style="float:left;"><input type="text" class="form-control" style="width: 100px; margin-top: -5px; margin-left: 15px;" name= "date" id="date" value="<?php echo $dates; ?>"></div>

<div style="float:left;"><span style="font-weight: bold; padding-left: 30px;">Bank : </span></div>
<div style="float:left;">
    <select class="form-control" id="bank" style='width: 200px; margin-top: -5px; margin-left: 15px;'>
        <option value='' selected disabled>Select From Below</option>
        <option value=''>All</option>
        <?php foreach ($banks as $bank) { ?>
            <option value='<?php echo $bank['id']; ?>' <?php if ($sel_bank == $bank['id']) 
                { echo "selected"; }?>>
                <?php echo $bank['name']; ?></option>
        <?php } ?>
    </select>
</div>
<div style="float:left;"><span style="font-weight: bold; padding-left: 30px;">User : </span></div>
<div style="float:left;">
    <select class="form-control" id="user" style='width: 150px; margin-top: -5px; margin-left: 15px;'>
    <option value=''>All</option>
        <?php foreach ($users as $user) { ?>
            <option value='<?php echo $user['id']; ?>' <?php if($user['id'] == $sel_user) 
                {echo "selected"; }?>  > 
                    <?php echo $user['name']; ?> </option>
       <?php  } ?>
    </select>
</div>
<div style="float:left; margin-left: 30px; margin-top: -5px;"><a class="btn btn-primary" style="padding: 5px 10px;" id="filterdata">Submit</a></div>
<br><br/><br/><br/>
<div class="row"><a style="color:white;" class="btn btn-primary" id="deletedata">Delete</a>
<div class="alert alert-success" id="successmsg" style="width:350px;display:none;"><p>Transactions Deleted successfully</p></div>
<div class="alert alert-danger" id="dangermsg" style="width:350px;display:none;"><p>Transactions unable to delete</p></div></div>
<table class="table table-bordered table-hover table-striped table-responsive" >            
    <thead>             
        <tr>   
            <th>ID<input type="checkbox" id = "chckHead" /></th>
            <th>Txn Id</th>
            <th>Bank Name</th>
            <th>Description</th>
            <th>Txn Date</th>
            <th>Txn Type</th>
            <th>Amount</th>
        </tr>
    </thead>

    <tbody style="color: #4e4e4e;">
    <?php $i = 1; foreach ($txn_data as $res) { ?>
        <tr>
            <td><center><?php echo $i; ?><br><input type="checkbox" class = "chcktbl" name="txn_id" value="<?php echo $res['atd']['id']; ?>"></center></td>
            <td><?php echo $res['atd']['pay1_txn_id']; ?></td>
            <td><?php echo $res['bd']['bank_name']; ?></td>
            <td><?php echo $res['atd']['description']; ?></td>
            <td><?php echo $res['atd']['txn_date']; ?></td>
            <td><?php echo $res['atd']['txn_status']; ?></td>
            <td><?php echo $res['atd']['amount']; ?></td>
        </tr>
        <?php $i++;
    } ?>
</tbody>
</table>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

<script>
     $('#date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "1d",
        multidate: false,
//        autoclose: true,
        todayHighlight: true
    });
        jQuery(document).ready(function($) {
            $('#successmsg,#dangermsg').hide();
            $('#filterdata').click(function(){ 
                $('#filterdata').attr('href','/accounting/deleteTxn/0/'+($('#date').val() != '' ? $('#date').val() : 0) + '/'+ '/' + $('#bank').val() + '/0/' + $('#user').val());
            });
            
            $('.table').dataTable({
                "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
                "pageLength": 100,
                "lengthMenu": [100, 200, 500],
            });

            $('#chckHead').click(function() { 
                if (this.checked == false) {
                    $('.chcktbl:checked').prop('checked', false);
                } else {
                    $('.chcktbl:not(:checked)').prop('checked', true);
                }
            });
            
            $('#deletedata').click(function(){
                if ($('#chckHead').prop("checked") == false && $('.chcktbl').prop("checked") == false) {
                    alert('Please select checkbox to delete trasactions !!!');return false;
                }
                if (confirm("Press a button!")) {
                    var val = [];
                    $(':checkbox:checked').each(function(i){
                      val[i] = $(this).val();
                    });
                    $.ajax({
                        type: 'POST',
                        url: '/accounting/deleteTxn',
                        dataType: "json",
                        data: {delete: '1', ids: val},
                        success: function(data) {
                            if (data == '1') {
                                $('#successmsg').show();
                            } else {
                                $('#dangermsg').show();
                            }
                            location.reload(true);
                        }
                    });
                } 
            });
        });
</script>
<script>jQuery.noConflict();</script>
