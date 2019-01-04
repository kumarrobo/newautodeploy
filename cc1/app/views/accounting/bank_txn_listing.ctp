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
        <li><button class="tablinks active" onclick="window.location='/accounting/bankTxnListing'">Txn Listing</button></li>
        <li><button class="tablinks dropdown-toggle" onclick="window.location='/accounting/closingBalanceReport'">Closing Balance Report</button></li>
        <!--<li><button class="tablinks" onclick="window.location='/accounting/limitReconsilationReport'">Limit Reconsilation Report</button></li>-->
        <li><button class="tablinks" onclick="window.location='/accounting/bankStatements'">Bank Statements</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/ledger'">Ledger</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/debitSystem'">Debit System</button></li>
    </ul>
</div>
<br/><br/>

<?php
    $url = explode('/', $_SERVER['REQUEST_URI']);
    $date = $url[3] != '' ? $url[3] : date('Y-m-d');
?>
<div style="float:left;"><span style="font-weight:bold;">From Date : </span></div>
<div style="float:left;"><input type="text" class="form-control" style="width: 100px; margin-top: -5px; margin-left: 15px;" id="from_date" value="<?php echo $sel_from_date; ?>"></div>
<div style="float:left;"><span style="font-weight:bold; padding-left: 30px;">To Date : </span></div>
<div style="float:left;"><input type="text" class="form-control" style="width: 100px; margin-top: -5px; margin-left: 15px;" id="to_date" value="<?php echo $sel_to_date; ?>"></div>

<div style="float:left;"><span style="font-weight: bold; padding-left: 30px;">Bank : </span></div>
<div style="float:left;">
    <select class="form-control" id="bank" style='width: 125px; margin-top: -5px; margin-left: 15px;'>
        <option value='0' selected disabled>Select From Below</option>
        <?php foreach ($banks as $bank) { ?>
        <option value='<?php echo $bank['id']; ?>' <?php if ($sel_bank == $bank['id']) { echo "selected"; } ?>><?php echo $bank['name']; ?></option>
        <?php } ?>
    </select>
</div>
<!--<div style="float:left;"><span style="font-weight:bold; padding-left: 30px;">Txn ID / Reference ID : </span></div>
<div style="float:left;"><input type="text" class="form-control" style="width: 200px; margin-top: -5px; margin-left: 15px;" id="txn_id" value="<?php echo $txn_id != 0 ? $txn_id : ''; ?>" placeholder="Filter By Txn ID"></div>-->
<div style="float:left;"><span style="font-weight:bold; padding-left: 30px;">Category : </span></div>
<div style="float:left;">
    <select class="form-control" id="category" style='width: 125px; margin-top: -5px; margin-left: 15px;'>
        <option value='0' selected disabled>Select From Below</option>
        <?php foreach ($categories as $category) { ?>
        <option value="<?php echo $category['ac']['id']; ?>" <?php if ($category['ac']['id'] == $sel_category) { echo "selected"; } ?>><?php echo $category['ac']['txn_type'] ." - ". $category['ac']['category'] . ($category['ac']['subcategory'] != "" ? " - ". $category['ac']['subcategory'] : ""); ?></option>
        <?php } ?>
    </select>
</div>
<div style="float:left; margin-left: 30px; margin-top: -5px;"><input class="btn btn-primary" type="button" value="Submit" style="padding: 5px 10px;" onclick="window.location = '/accounting/bankTxnListing/' + $('#from_date').val() + '/'+ $('#to_date').val() + '/' + ($('#bank').val() == null ? 0 : $('#bank').val()) + '/' + ($('#txn_id').val() == '' ? 0 : $('#txn_id').val()) + '/' + ($('#category').val() == null ? 0 : $('#category').val());"></div>
<img id="export_csv" type="button" class="export_csv" src="/img/csv1.jpg" style="margin-left: 10px; height:25px" onclick="window.location = '/accounting/bankTxnListing/' + $('#from_date').val() + '/'+ $('#to_date').val() + '/' + ($('#bank').val() == null ? 0 : $('#bank').val()) + '/' + ($('#txn_id').val() == '' ? 0 : $('#txn_id').val()) + '/' + ($('#category').val() == null ? 0 : $('#category').val()) + '/1';">
<br/><br/><br/>
<table class="table table-bordered table-hover" style="width: 900px; margin-top: 50px;">            
    <thead>             
        <tr>   
            <th>Txn ID</th>
            <th>Bank</th>
            <th>Branch Code</th>
            <th>Bank Txn ID</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Balance</th>
            <th>Limit Processed + Refund</th>            
            <th>Txn Date</th>            
            <th>Upload Date</th>            
            <th>Category</th>
            <th>Sub-Category</th>
            <th>Shop Tran ID</th>
            <th>Specific</th>
            <th>Reference ID</th>
            <th>Narration</th>
            <th>Action Performed</th>
        </tr>
    </thead>
            
    <tbody style="color: #4e4e4e;">
        <?php if($result) { ?>
            <?php foreach($result as $res) { ?>
                <tr>
                    <td <?php if($txn_id == $res['atd']['pay1_txn_id']) { ?>style="font-weight: bold;"<?php } ?>><?php echo $res['atd']['pay1_txn_id']; if($res['atd']['account_category_id'] == 1) { ?><br><br><center><input class="btn btn-primary" type="button" value="Pullback" style="padding: 5px 10px;" onclick="pullback(<?php echo $res['atd']['shop_tran_id']; ?>,'<?php echo $res['atd']['type'] == 'distributor' || $res['atd']['type'] == 'superdistributor' ? 'accounts_masterdistributor' : 'accounts_distributor' ; ?>');"></center><?php } ?></td>
                    <td <?php if($sel_bank != 0) { ?>style="font-weight: bold;"<?php } ?>><?php echo $res[0]['bank']; ?></td>
                    <td><?php echo $res['atd']['branch_code'] != '' ? $res['atd']['branch_code'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['atd']['bank_txn_id'] != '' ? $res['atd']['bank_txn_id'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['atd']['txn_status']; ?></td>
                    <td><?php echo $res['atd']['amount']; ?></td>
                    <td><?php echo $res['atd']['balance']; ?></td>
                    <td><?php echo ($res['atd']['account_category_id'] == 1 ? ($res['atd']['amount'] - $res['atd']['refund']) . ($res['atd']['refund'] ? ' + ' . $res['atd']['refund'] : '') : '<center>-</center>') ; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($res['atd']['txn_date'])); ?></td>
                    <td><?php echo $res['atd']['operation_date']; ?></td>
                    <td <?php if($sel_category == $res['atd']['account_category_id']) { ?>style="font-weight: bold;"<?php } ?>><?php echo $res['ac']['category'] == '' ? '<center>-</center>' : $res['ac']['category']; ?></td>
                    <td <?php if($sel_category == $res['atd']['account_category_id']) { ?>style="font-weight: bold;"<?php } ?>><?php echo $res['ac']['subcategory'] == '' ? '<center>-</center>' : $res['ac']['subcategory']; ?></td>
                    <td><?php echo $res['atd']['shop_tran_id'] == 0 ? '<center>-</center>' : $res['atd']['shop_tran_id'] . "<br>( " . $res['st']['timestamp'] . " )"; ?></td>
                    <?php
                            if ($res['atd']['type'] == 'supplier') {
                                    $order_ids  = explode(',', $res['atd']['type_id']);
                                    $supp = array();
                                    foreach ($order_ids as $oi) {
                                            $supp[] = $suppliers[$oi];
                                    }
                                    $supplier = implode(', ', $supp);
                            }
                            $type_id = array('distributor'=>'Distributor - '.$res[0]['distributor'],'retailer'=>'Retailer - '.$res[0]['retailer'],'supplier'=>'Supplier - '.$supplier,'bank_account'=>$res[0]['receiver_bank'],'superdistributor'=>'Super Distributor - '.$res[0]['superdistributor']);
                    ?>
                    <td><?php echo $res['atd']['type'] != '' ? $type_id[$res['atd']['type']] : '<center>-</center>'; ?></td>
                    <td <?php if($txn_id == $res['atd']['refund'] && $txn_id != 0) { ?>style="font-weight: bold;"<?php } ?>><?php echo in_array($res['atd']['account_category_id'], array(55,56)) ? $res['atd']['refund'] : '<center>-</center>'; ?></td>
                    <td><?php echo $res['atd']['narration'] == '' ? '<center>-</center>' : $res['atd']['narration']; ?></td>
                    <td><?php echo $res['users']['name']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
        <!--<tr><td colspan="17"><center>No Records Found</center></td></tr>-->
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

<script>
    
    $('#from_date, #to_date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });
    
    $('.table').dataTable({
//        "order": [0, "desc"],
        "pageLength": 50,
        "lengthMenu": [50, 100, 150, 200],
    });
    
    function pullback (shop_tran_id, request_from) {
        
        var r = confirm("Are You sure, you want to pull back this amount ?");
        
	if(r == true) {
            $.post('/accounting/pullback', {'shop_transid': shop_tran_id, 'request_from': request_from}, function(e) {
                if(e == 1){
                    alert('Done !!!');
                    location.reload();
                } else {
                    alert('Failed !!!');
                }
            });
	}
    }
    
</script>
