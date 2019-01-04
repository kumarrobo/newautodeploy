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
        <li><button class="tablinks" onclick="window.location='/accounting/txnUpload'">File Upload</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/autoUpload'">Txn Entry</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/bankTxnListing'">Txn Listing</button></li>
        <li><button class="tablinks dropdown-toggle" onclick="window.location='/accounting/closingBalanceReport'">Closing Balance Report</button></li>
        <!--<li><button class="tablinks" onclick="window.location='/accounting/limitReconsilationReport'">Limit Reconsilation Report</button></li>-->
        <li><button class="tablinks active" onclick="window.location='/accounting/bankStatements'">Bank Statements</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/ledger'">Ledger</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/debitSystem'">Debit System</button></li>
    </ul>

</div><br/>
<span style="color: red; text-align: center;"><?php echo $this->Session->flash(); ?></span>
<br/>
<div style="float:left;"><span style="font-weight:bold;">Date : </span></div>
<div style="float:left;"><input type="text" class="form-control" style="width: 100px; margin-top: -5px; margin-left: 15px;" id="date" value="<?php echo $date; ?>"></div>
<div style="float:left;"><span style="font-weight: bold; padding-left: 30px;">Bank : </span></div>
<div style="float:left;">
    <select class="form-control" id="bank" style='width: 200px; margin-top: -5px; margin-left: 15px;'>
        <option value='' selected disabled>Select From Below</option>
        <?php foreach ($banks as $key=>$bank) { ?>
        <option value='<?php echo $key; ?>' <?php if ($sel_bank == $key) { echo "selected"; } ?>><?php echo $bank; ?></option>
        <?php } ?>
    </select>
</div>
<div style="float:left; margin-left: 30px; margin-top: -5px;"><input class="btn btn-primary" type="button" value="Submit" style="padding: 5px 10px;" onclick="window.location = '/accounting/bankStatements/' + ($('#bank').val() != null ? $('#bank').val() : 0) + '/' + ($('#date').val() != '' ? $('#date').val() : 0)"></div>
<img id="export_csv" type="button" class="export_csv" src="/img/csv1.jpg" style="margin-left: 10px; height:25px" onclick="window.location = '/accounting/bankStatements/' + ($('#bank').val() != null ? $('#bank').val() : 0) + '/' + ($('#date').val() != '' ? $('#date').val() : 0) + '/1';">
<br/><br/><br/>
<table class="table table-bordered table-hover table-striped table-responsive" style="width:900px;">            
    <thead>             
        <tr>   
            <th>ID</th>
            <th>Txn Id</th>
            <th>Bank</th>
            <th>Bank Txn Id</th>
            <th>Branch</th>
            <th>Txn Date</th>
            <th>Operation Date</th>
            <th>Txn Type</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Closing Balance</th>
            <th>Status</th>
        </tr>
    </thead>
            
    <tbody style="color: #4e4e4e;">
        <?php $i = 1; if($data) { ?>
            <?php foreach($data as $res) { ?>
                <tr>
                    <td><center><?php echo $i; ?></center></td>
                    <td><?php echo $res['pay1_txn_id']; ?></td>
                    <td><?php echo $banks[$res['bank_id']]; ?></td>
                    <td><?php echo !empty($res['bank_txn_id']) ? $res['bank_txn_id'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['branch_code'] ? $res['branch_code'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['txn_date']; ?></td>
                    <td><?php echo $res['operation_date']; ?></td>
                    <td><?php echo ucfirst($res['txn_status']); ?></td>
                    <td><?php echo $res['description']; ?></td>
                    <td><?php echo $res['amount']; ?></td>
                    <td><?php echo $res['balance'];?></td>
                    <td><center><img src="/img/<?php echo $res['is_submitted'] == 1 ? 'success.png' : 'info.png'; ?>"/></center></td>
                </tr>
        <?php $i++; } } else { ?>
        <!--<tr><td colspan="11"><center>No Records Found</center></td></tr>-->
        <?php } ?>
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
        autoclose: true,
        todayHighlight: true
    });
    
    $('.table').dataTable({
//        "order": [[0, "desc" ]],
        "pageLength":100,
        "lengthMenu": [100, 200, 500],
    });
    
</script>
