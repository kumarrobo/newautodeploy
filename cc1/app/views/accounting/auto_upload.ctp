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
        <li><button class="tablinks active" onclick="window.location='/accounting/autoUpload'">Txn Entry</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/bankTxnListing'">Txn Listing</button></li>
        <li><button class="tablinks dropdown-toggle" onclick="window.location='/accounting/closingBalanceReport'">Closing Balance Report</button></li>
        <!--<li><button class="tablinks" onclick="window.location='/accounting/limitReconsilationReport'">Limit Reconsilation Report</button></li>-->
        <li><button class="tablinks" onclick="window.location='/accounting/bankStatements'">Bank Statements</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/ledger'">Ledger</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/debitSystem'">Debit System</button></li>
    </ul>
</div>
<br/>

<span style="color: red; text-align: center;"><?php echo $this->Session->flash(); ?></span>
<br/>
<div style="float:left;"><span style="font-weight:bold;">From Date : </span></div>
<div style="float:left;"><input type="text" class="form-control" style="width: 100px; margin-top: -5px; margin-left: 15px;" id="from_date" value="<?php echo $sel_from_date; ?>"></div>
<div style="float:left;"><span style="font-weight:bold;padding-left: 30px;">To Date : </span></div>
<div style="float:left;"><input type="text" class="form-control" style="width: 100px; margin-top: -5px; margin-left: 15px;" id="to_date" value="<?php echo $sel_to_date; ?>"></div>

<div style="float:left;"><span style="font-weight: bold; padding-left: 30px;">Bank : </span></div>
<div style="float:left;">
    <select class="form-control" id="bank" style='width: 200px; margin-top: -5px; margin-left: 15px;'>
        <option value='' selected disabled>Select From Below</option>
        <option value='0'>All</option>
        <?php foreach ($banks as $bank) { ?>
        <option value='<?php echo $bank['id']; ?>' <?php if ($sel_bank == $bank['id']) { echo "selected"; } ?>><?php echo $bank['name']; ?></option>
        <?php } ?>
    </select>
</div>
<div style="float:left;"><span style="font-weight: bold; padding-left: 30px;">Txn Type : </span></div>
<div style="float:left;">
    <select class="form-control" id="cd_type" style='width: 75px; margin-top: -5px; margin-left: 15px;'>
        <option <?php if ($sel_txn_type == 'Cr') { echo "selected"; } ?>>Cr</option>
        <option <?php if ($sel_txn_type == 'Dr') { echo "selected"; } ?>>Dr</option>
    </select>
</div>
<div style="float:left; margin-left: 30px; margin-top: -5px;"><input class="btn btn-primary" type="button" value="Submit" style="padding: 5px 10px;" onclick="window.location = '/accounting/autoUpload/0/'+ ($('#from_date').val() != '' ? $('#from_date').val() : 0) + '/'+ ($('#to_date').val() != '' ? $('#to_date').val() : 0) + '/' + ($('#bank').val() != null ? $('#bank').val() : 0) + '/0/' + $('#cd_type').val()"></div>
<br/><br/><br/>
<table class="table table-bordered table-hover table-striped table-responsive" style="width:900px;">            
    <thead>             
        <tr>   
            <th>ID<?php if ($sel_txn_type == 'Dr') { ?><input type="checkbox" id="checkAll"><?php } ?></th>
            <th>Txn Id</th>
            <th>Bank Txn Id</th>
            <th>Bank Name</th>
            <th>Branch</th>
            <th>Txn Date</th>
            <th>Operation Date</th>
            <th>Txn Type</th>
            <th>Description</th>
            <th>Amount</th>
            <th>No of Requests</th>
            <th>Registered</th>
        </tr>
    </thead>
            
    <tbody style="color: #4e4e4e;">
        <?php $i = 1; if($array_record['priority']) { ?>
            <?php foreach($array_record['priority'] as $res) { ?>
                <tr>
                    <td><center><?php echo $i; ?><br><input type="checkbox" name="txn_id" value="<?php echo $res['txn_id']; ?>"></center></td>
                    <td><?php echo $res['txn_id']; ?></td>
                    <td><?php echo !empty($res['bank_txn_id']) ? $res['bank_txn_id'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['bank_name']; ?></td>
                    <td><?php echo $res['branch_code'] ? $res['branch_code'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['txn_date']; ?></td>
                    <td><?php echo $res['operation_date']; ?></td>
                    <td><?php echo ucfirst($res['txn_type']); ?></td>
                    <td><?php echo $res['description']; ?></td>
                    <td><?php echo ucfirst($res['txn_type']) == 'Dr' ? $res['amount'] : round($res['amount']); ?></td>
                    <td><center><?php echo $limit_count[$res['bank_id']][round($res['amount'])] ? $limit_count[$res['bank_id']][round($res['amount'])] : '-'; ?></center></td>
                    <td><a href='javascript:void(0)' onclick="makeEntry(<?php echo "'".$res['txn_id'] . "','" . ucfirst($res['txn_type'])."'"; ?>);">Make Entry</a></td>
                </tr>
        <?php $i++; } } ?>
        <?php if($array_record['success'] || $array_record['fail']) { ?>
            <?php foreach($array_record['success'] as $res) { ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $res['txn_id']; ?></td>
                    <td><?php echo $res['bank_name']; ?></td>
                    <td><?php echo !empty($res['bank_txn_id']) ? $res['bank_txn_id'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['branch_code'] ? $res['branch_code'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['txn_date']; ?></td>
                    <td><?php echo $res['operation_date']; ?></td>
                    <td><?php echo ucfirst($res['txn_type']); ?></td>
                    <td><?php echo $res['description']; ?></td>
                    <td><?php echo $res['amount']; ?></td>
                    <td><center><?php echo $limit_count[$res['bank_id']][$res['amount']] ? $limit_count[$res['bank_id']][$res['amount']] : '-'; ?></center></td>
                    <td><img src="/img/success.png"></td>
                </tr>
            <?php $i++; } ?>
            <?php foreach($array_record['fail'] as $res) { ?>
                <tr>
                    <td><center><?php echo $i; ?><br><input type="checkbox" class="txn_mark" name="txn_id" value="<?php echo $res['txn_id']; ?>"></center></td>
                    <td><?php echo $res['txn_id']; ?></td>
                    <td><?php echo !empty($res['bank_txn_id']) ? $res['bank_txn_id'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['bank_name']; ?></td>
                    <td><?php echo $res['branch_code'] ? $res['branch_code'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['txn_date']; ?></td>
                    <td><?php echo $res['operation_date']; ?></td>
                    <td><?php echo ucfirst($res['txn_type']); ?></td>
                    <td><?php echo $res['description']; ?></td>
                    <td><?php echo ucfirst($res['txn_type']) == 'Dr' ? $res['amount'] : round($res['amount']); ?></td>
                    <td><center><?php echo $limit_count[$res['bank_id']][round($res['amount'])] ? $limit_count[$res['bank_id']][round($res['amount'])] : '-'; ?></center></td>
                    <td><a href='javascript:void(0)' onclick="makeEntry(<?php echo "'".$res['txn_id'] . "','" . ucfirst($res['txn_type'])."'"; ?>);">Make Entry</a></td>
                </tr>
            <?php $i++; } ?>
        <?php } else { ?>
        <!--<tr><td colspan="12"><center>No Records Found</center></td></tr>-->
        <?php } ?>
    </tbody>
</table>
<?php if (in_array($_SESSION['Auth']['User']['mobile'], array('90299160044','7208207549','9833258509'))) { ?>
<a href="/accounting/deleteTxn">Delete Transaction</a>
<?php } ?>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

<script>
    
    $('#from_date,#to_date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });
    
    $('.table').dataTable({
//        "order": [[0, "desc" ]],
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0 ] }],
        "pageLength":100,
        "lengthMenu": [100, 200, 500],
    });
    
    function makeEntry (txn_id, txn_type) {
        var arr = [];
        $("input:checkbox[name=txn_id]:checked").each(function(){
            arr.push($(this).val());
        });
        
        if (arr.length > 10 && txn_type == 'Cr') {
            alert("You can select maximum 10 txns at a time !!!"); return false;
        }
        
        txn_id = (arr.length > 0) ? arr.join(',') : txn_id;
        location.href = "/accounting/accountSpecificTxn/"+txn_id+"/"+txn_type;
    }
    
    $('#checkAll').click(function() {
        if ($('#checkAll:checkbox:checked').length > 0) {
            $('.txn_mark').prop('checked', true);
        } else {
            $('.txn_mark').prop('checked', false);
        }
    });
    
</script>
