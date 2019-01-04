<link href="/boot/css/font-awesome.min.css" rel="stylesheet">
<script src="/boot/js/jquery-2.0.3.min.js"></script>
<script src="/boot/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
<script src = "/boot/js/jquery-ui.js"></script>
<link href = "/boot/css/jquery-ui.css" rel = "stylesheet">
<script src="/boot/js/moment.min.js"></script>
<!--<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>-->
<script src="/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>
<div class="container">

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home" style="font-weight:bold">Transaction Recon Report</a></li>
        <li><a data-toggle="tab" href="#menu2" style="font-weight:bold">Upload</a></li>

    </ul>
    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <form class="form-inline"  name="reportform" method="POST" action="/accounting/txnSearch">
                <div style="padding-top: 30px;">
                    <div class="row">
                        <div class="col-md-1">
                            <label class="btn-label" style="margin-left: 15px;padding-top:5px;">Service</label>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <select id="serviceid" name="serviceid" class="form-control" style="width:215px;">
                                    <option value="">Select Service</option>
                                    <?php foreach ($services as $service) { ?>
                                        <option value='<?php echo $service['services']['id']; ?>'<?php
                                        if ($service == $service['services']['id']) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $service['services']['name']; ?></option>
                                            <?php } ?>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="btn-label"  style='padding-left :30px; padding-top:5px;'>Vendor</label>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <select  class="form-control" name="vendorid" id="vendorid" style="width:200px;">
                                    <option value="">Select Vendor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-1">
                            <label class="btn-label " style="margin-left: 15px;padding-top:5px;">From </label>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control" style="padding-left :30px;" id="from_date" name="from_date" >
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="btn-label" style="margin-left: 35px;padding-top: 5px;">To </label>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control" style="padding-left :30px;width:200px;" id="to_date" name="to_date" >
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-md" id="filterdata">Submit</button><span id="filter_response"></span>
                    </div>

                </div>
            </form>   
            <br>
            <div class="row">
                <div class="col-md-6" id="profitlossTable"></div>
                <div class="col-md-6" id="txnmatchTable"></div>
            </div>
            <div class="row" id="mismatch">
            </div>

            <div class="row" id="vendormargin">
            </div>

            <div class="row" id="settlewallet">
            </div>

            <div class="row" id="losstxns">
            </div>
        </div>
        <div id="menu2" class="tab-pane fade">
            <form class="form-inline"  name="reportform" method="POST" action="/accounting/txnRecon" enctype="multipart/form-data">
                <div style="padding-top: 45px;">
                    <div class="row">
                        <div class="col-md-1">
                            <label>Services</label>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <select id="service" name="service" class="form-control">
                                    <option value="0">Select Service</option>
                                    <?php
                                    foreach ($services as $service) {
                                        echo "<option value='" . $service['services']['id'] . "'>" . $service['services']['name'] . " </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label>Vendors</label>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <select id="vendor" name="vendor" class="form-control" style="width:180px;" >
                                    <option value="0">Select Vendor</option>
                                </select>
                            </div>
                        </div>
                        <div id="upload_container" style="display: none;">
                            <div class="col-md-1">
                                <label>Upload File</label>
                            </div>
                            <div class="col-md-3">
                                <input type="file" name="upload_file" id="upload_file" class="form-control" >
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary btn-md" id="upload" >Submit</button><span id="response"></span>
                            </div>
                        </div>
                    </div>
                    <br><br><div id="dvTable"></div>    
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Product Total</h4>
            </div>
            <div class="modal-body">
                <div id="modal_table">
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>

    var dt = new Date();
    jQuery('#from_date').datetimepicker({
        defaultDate: dt,
        format: 'YYYY-MM-DD',
        maxDate: dt
    });
    jQuery('#to_date').datetimepicker({
        defaultDate: dt,
        format: 'YYYY-MM-DD',
        maxDate: dt
    });

    jQuery(document).ready(function($) {

        $('#serviceid').change(function() {
            var serviceid = $(this).val();
//            var vendorid = $(this).val();
            $.ajax({
                type: 'POST',
                url: '/accounting/txnSearch',
                dataType: 'json',
                data: {serviceflag: '1', servicedata: serviceid},
                success: function(data) {
                    $('#vendorid').html('');
                    $.each(data, function(i, item) {
                        $('#vendorid').append("<option value=" + item.product_vendors.id + ">" + item.product_vendors.name + "</option>");
                    });
                }
            });
        });

        $('#filterdata').click(function() {
            jQuery('#profitlossTable, #txnmatchTable, #mismatch, #vendormargin, #settlewallet, #losstxns').html("");
            jQuery('#filter_response').html("<img src='/img/ajax-loader-2.gif' />");
            var fromdate = $('#from_date').val();
            var service = $('#serviceid').val();
            var vendor = $('#vendorid').val();


            var data = {
                'filter': 1,
                'fromdate': $('#from_date').val(),
                'todate': $('#to_date').val(),
                'service': $('#serviceid').val(),
                'vendor': $('#vendorid').val()
            };
            $.ajax({
                type: 'POST',
                url: '/accounting/dashboard',
                dataType: 'json',
                data: data,
                success: function(response) {
                    if (response.status == 'failed') {
                        alert(response.msg);
                        return false;
                    }
                    jQuery('#filter_response').html("");
                    if (response.profit_loss != null) {
                        var overview = " <div class='panel panel-primary filterable'>";
                        overview += "<div class='panel-heading'>";
                        overview += "<h3 class='panel-title'>Profit / Loss Overall <span style='float:right'> Total Profit/Loss : " + response.total_profit_loss + " </span> </h3>";
                        overview += "</div>";
                        overview += "<table class='table table-responsive' id='overview'>";
                        overview += "<thead>";
                        overview += "<tr class='filters'>";
                        overview += "<th>Product</th>";
                        overview += "<th>Total</th>";
                        overview += "</tr>";
                        overview += "</thead>";

                        overview += "<tbody>";

                        for (var x in response.profit_loss) {
                            if (!isNaN(x)) {
                                overview += '<tr>';
                                overview += "<td>" + (response.profit_loss[x].products.name == null ? '' : response.profit_loss[x].products.name) + "</td>";
                                overview += '<td class="product_total" data-id = "'+response.profit_loss[x].products.id+'" data-name = "'+response.profit_loss[x].products.name+'">' + (response.profit_loss[x][0].amount == null ? '' : response.profit_loss[x][0].amount) + "</td>";
                                overview += "</tr>";
                                
                            }
                        }

                        overview += "</tbody>";
                        overview += "</table>";
                        overview += "</div>";

                        jQuery("#profitlossTable").html(overview);

                        jQuery('.product_total').click(function() {
                            var productid = jQuery(this).data('id');
                            showModal(response.profit[productid]);
                        });
                    } else {
                        jQuery("#profitlossTable").html('');
                    }
                    if (response.mismatchtxn.length > 0) {
                        var mismatch_cnt = 0;
                        var mismatchmargin_cnt = 0;

                        var txnmismatch = "<div class='panel panel-primary filterable'>";
                        txnmismatch += "<div class='panel-heading'>";
                        txnmismatch += "<h3 class='panel-title'>Transaction Mismatch</h3>";
                        txnmismatch += "</div>";
                        txnmismatch += "<div class=''>";
                        txnmismatch += "<table class='table table-responsive' id='txnmismatch'>";
                        txnmismatch += "<thead>";
                        txnmismatch += "<tr class='filters'>";

                        txnmismatch += "<th>Date</th>";
                        txnmismatch += "<th style='color :red;'>txn_id</th>";
                        txnmismatch += "<th style='color :red;'>vendor_txn_id</th>";
                        txnmismatch += "<th style='color :orange;'>Amount</th>";
                        txnmismatch += "<th style='color :orange;'>Vendor Amount</th>";
                        txnmismatch += "<th style='color :green;'>Status</th>";
                        txnmismatch += "<th style='color :green;'>Vendor Status</th>";

                        txnmismatch += "</tr>";
                        txnmismatch += "</thead>";
                        txnmismatch += "<tbody>";
                        for (var x in response.mismatchtxn) {
                            if (!isNaN(x)) {
                                var resp = response.mismatchtxn[x][0];
                                var color = "";
                                if ((resp.recon_txn == null || resp.txn_id == null) && !(resp.recon_txn == null && resp.txn_id == null)) {
                                    color = "red";
                                } else if ((resp.vendor_txn_id == null || resp.vendor_txn == null) && !(resp.vendor_txn_id == null && resp.vendor_txn == null)) {
                                    color = "red";
                                } else if (resp.amount != resp.recon_amt) {
                                    color = "orange";
                                } else if (resp.status != resp.recon_status) {
                                    color = "green";
                                }

                                if (color != "" && resp.amount != 0) {
                                    txnmismatch += "<tr>";
                                    txnmismatch += "<td>" + (resp.date == null ? '' : resp.date) + "</td>";
                                    txnmismatch += (color == "red" ? "<td style='color :red;'>" + (resp.txn_id == null ? resp.recon_txn : resp.txn_id) + "</td>" : "<td>" + (resp.txn_id == null ? resp.recon_txn : resp.txn_id) + "</td>");
                                    txnmismatch += (color == "red" ? "<td style='color :red;'>" + (resp.vendor_txn_id == null ? resp.vendor_txn : resp.vendor_txn_id) + "</td>" : "<td>" + (resp.vendor_txn_id == null ? resp.vendor_txn : resp.vendor_txn_id) + "</td>");
                                    txnmismatch += (color == "orange" ? "<td style='color :orange;'>" + (resp.amount == null ? resp.recon_amt : resp.amount) + "</td>" : "<td>" + (resp.amount == null ? resp.recon_amt : resp.amount) + "</td>");
                                    txnmismatch += (color == "orange" ? "<td style='color :orange;'>" + (resp.recon_amt == null ? '' : resp.recon_amt) + "</td>" : "<td>" + (resp.recon_amt == null ? '' : resp.recon_amt) + "</td>");
                                    txnmismatch += (color == "green" ? "<td style='color :green;'>" + ((resp.status == 1) ? 'Success' : ((resp.status == 0) ? 'Pending' : ((resp.status == '' || resp.status == null) ? '' : 'Failed'))) + "</td>" : "<td>" + ((resp.status == 1) ? 'Success' : ((resp.status == 0) ? 'Pending' : ((resp.status == '' || resp.status == null) ? '' : 'Failed'))) + "</td>");
                                    txnmismatch += (color == "green" ? "<td style='color :green;'>" + ((resp.recon_status == 1) ? 'Success' : ((resp.recon_status == 0) ? 'Pending' : ((resp.recon_status == '' || resp.recon_status == null) ? '' : 'Failed'))) + "</td>" : "<td>" + ((resp.recon_status == 1) ? 'Success' : ((resp.recon_status == 0) ? 'Pending' : ((resp.recon_status == '' || resp.recon_status == null) ? '' : 'Failed'))) + "</td>");
                                    txnmismatch += "</tr>";

                                    mismatch_cnt += 1;
                                }
                            }
                        }

                        txnmismatch += "</tbody>";
                        txnmismatch += "</table>";
                        txnmismatch += "</div>";
                        txnmismatch += "</div>";
                        jQuery("#mismatch").html(txnmismatch);
                        jQuery('#txnmismatch').dataTable({
                            "scrollY": "200px",
                            "scrollCollapse": true,
                            "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
                            "pageLength": 10,
                            "lengthMenu": [10, 20, 50],
                        });
                    }

                    if (response.vendormargin.length > 0) {
                        var vendormargin = "<div class='panel panel-primary filterable'>";
                        vendormargin += "<div class='panel-heading'>";
                        vendormargin += "<h3 class='panel-title'>Vendor Margins Mistmatch <span style='float: right'>Total Expected Vendor Margin : " + response.total_vendorMargin.toFixed(2) + "</span></h3>";
                        vendormargin += "</div>";
                        vendormargin += "<div class=''>";
                        vendormargin += "<table class='table table-responsive' id='vendormarginTable'>";
                        vendormargin += "<thead>";
                        vendormargin += "<tr class='filters'>";

                        vendormargin += "<th>Date</th>";
                        vendormargin += "<th>txn_id</th>";
                        vendormargin += "<th>vendor_txn_id</th>";
                        vendormargin += "<th>Amount</th>";
                        vendormargin += "<th style='color :purple;'>Expected Vendor Margin</th>";
                        vendormargin += "<th style='color :purple;'>Actual Vendor Margin</th>";
                        vendormargin += "<th>Status</th>";

                        vendormargin += "</tr>";
                        vendormargin += "</thead>";
                        vendormargin += "<tbody>";
                        for (var x in response.vendormargin) {
                            if (!isNaN(x)) {
                                var resp = response.vendormargin[x][0];
                                var exp_margin = resp.vendor_settled_amount != null ? parseFloat(resp.vendor_settled_amount) : parseFloat(0.00);
                                var act_margin = resp.vendor_margin != null ? parseFloat(resp.vendor_margin) : parseFloat(0.00);
                                if ((exp_margin - act_margin) > (resp.vendor_mrgin * 0.01)) {
                                    vendormargin += "<tr>";
                                    vendormargin += "<td>" + (resp.date == null ? '' : resp.date) + "</td>";
                                    vendormargin += "<td>" + resp.txn_id + "</td>";
                                    vendormargin += "<td>" + resp.vendor_txn_id + "</td>";
                                    vendormargin += "<td>" + resp.amount + "</td>";
                                    vendormargin += "<td>" + exp_margin.toFixed(2) + "</td>";
                                    vendormargin += "<td>" + act_margin.toFixed(2) + "</td>";
                                    vendormargin += ((resp.status == 1 || resp.status == 0) ? "<td><b>Success</b> </td>" : "<td> <b>Failed</b></td>");
                                    vendormargin += "</tr>";
                                    
                                    mismatchmargin_cnt += 1;
                                }
                            }
                        }

                        vendormargin += "</tbody>";
                        vendormargin += "</table>";
                        vendormargin += "</div>";
                        vendormargin += "</div>";
                        jQuery("#vendormargin").html(vendormargin);
                        jQuery('#vendormarginTable').dataTable({
                            "scrollY": "200px",
                            "scrollCollapse": true,
                            "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
                            "pageLength": 10,
                            "lengthMenu": [10, 20, 50],
                        });
                    }

                    var settlewallet = " <div class='panel panel-primary filterable'>";
                    settlewallet += "<div class='panel-heading'>";
                    settlewallet += "<h3 class='panel-title'>Other Reports Count</h3>";
                    settlewallet += "</div>";
                    settlewallet += "<div class=''>";
                    settlewallet += "<table class='table table-responsive' id='txnsearch'>";
                    settlewallet += "<tbody>";

                    settlewallet += "<tr><td class='col-xs-8'> Total Txns Matched </td><td class='col-xs-2'>" + (response.count.txnmatch_count == null ? '0' : response.count.txnmatch_count) + "</td></tr>";
                    settlewallet += "<tr><td class='col-xs-8'> Total Txns Mismatched </td><td class='col-xs-2'>" + (mismatch_cnt == null ? '0' : mismatch_cnt) + "</td></tr>";
                    settlewallet += "<tr><td class='col-xs-8'> Vendor Margins Mismatched </td><td class='col-xs-2'>" + (mismatchmargin_cnt == null ? '0' : mismatchmargin_cnt) + "</td></tr>";
                    settlewallet += "<tr><td class='col-xs-8'> Loss Transactions </td><td class='col-xs-2'>" + (response.loss_txns.length > 0 ? response.loss_txns.length : '0') + "</td></tr>";
                    settlewallet += "<tr><td class='col-xs-8'> Bank Settlement Pending </td><td class='col-xs-2'>" + (response.bank_settlement_pending.length > 0 ? response.bank_settlement_pending.length : '0') + "</td></tr>";
                    settlewallet += "<tr><td class='col-xs-8'> Txns Settled in Bank (SUM / COUNT)</td><td class='col-xs-2'>" + response.count.settlebank + ' / ' + response.count.settlebank1 + "</td></tr>";
                    settlewallet += "<tr><td class='col-xs-8'> Txns Settled in Wallet (SUM / COUNT)</td><td class='col-xs-2'>" +  response.count.settleWallet + ' / ' + response.count.settleWallet1 + "</td></tr>";
                    settlewallet += "<tr><td class='col-xs-8'> Total Sale </td><td class='col-xs-2'>" + (response.count.totalSum != null ? response.count.totalSum : '0') + "</td></tr>";

                    settlewallet += "</tbody>";
                    settlewallet += "</table>";
                    settlewallet += "</div>";
                    settlewallet += "</div>";
                    settlewallet += "</div>";

                    jQuery("#txnmatchTable").html(settlewallet);

                    if (response.bank_settlement_pending.length > 0) {
                        var settlewallet = "<div class='panel panel-primary filterable'>";
                        settlewallet += "<div class='panel-heading'>";
                        settlewallet += "<h3 class='panel-title'>Bank Settlement Pending</h3>";
                        settlewallet += "</div>";
                        settlewallet += "<div class=''>";
                        settlewallet += "<table class='table table-responsive' id='settlewalletTable'>";
                        settlewallet += "<thead>";
                        settlewallet += "<tr class='filters'>";

                        settlewallet += "<th>Date</th>";
                        settlewallet += "<th>txn_id</th>";
                        settlewallet += "<th>vendor_txn_id</th>";
                        settlewallet += "<th>Amount</th>";
                        settlewallet += "<th>Status</th>";

                        settlewallet += "</tr>";
                        settlewallet += "</thead>";
                        settlewallet += "<tbody>";
                        for (var x in response.bank_settlement_pending) {
                            if (!isNaN(x)) {
                                settlewallet += "<tr>";
                                settlewallet += "<td>" + (response.bank_settlement_pending[x].wallets_transactions.date == null ? '' : response.bank_settlement_pending[x].wallets_transactions.date) + "</td>";
                                settlewallet += "<td>" + (response.bank_settlement_pending[x].wallets_transactions.txn_id == null ? '' : response.bank_settlement_pending[x].wallets_transactions.txn_id) + "</td>";
                                settlewallet += "<td>" + (response.bank_settlement_pending[x].wallets_transactions.vendor_refid == null ? '' : response.bank_settlement_pending[x].wallets_transactions.vendor_refid) + "</td>";
                                settlewallet += "<td>" + (response.bank_settlement_pending[x].wallets_transactions.amount == null ? '' : response.bank_settlement_pending[x].wallets_transactions.amount) + "</td>";
                                settlewallet += (response.bank_settlement_pending[x].wallets_transactions.status == '1' ? "<td style='color :green;'><b>Success</b> </td>" : "<td style='color :red;'> <b>Failed</b></td>");
                                settlewallet += "</tr>";
                            }
                        }
                        settlewallet += "</tbody>";
                        settlewallet += "</table>";
                        settlewallet += "</div>";
                        settlewallet += "</div>";
                        jQuery("#settlewallet").html(settlewallet);
                        jQuery('#settlewalletTable').dataTable({
                            "scrollY": "200px",
                            "scrollCollapse": true,
                            "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
                            "pageLength": 10,
                            "lengthMenu": [10, 20, 50],
                        });
                    }

                    if (response.loss_txns.length > 0) {
                        var losstxns = "<div class='panel panel-primary filterable'>";
                        losstxns += "<div class='panel-heading'>";
                        losstxns += "<h3 class='panel-title'>Loss Transactions</h3>";
                        losstxns += "</div>";
                        losstxns += "<div class=''>";
                        losstxns += "<table class='table table-responsive' id='losstxnsTable'>";
                        losstxns += "<thead>";
                        losstxns += "<tr class='filters'>";

                        losstxns += "<th>Date</th>";
                        losstxns += "<th>Txn Id</th>";
                        losstxns += "<th>Vendor Txn Id</th>";
                        losstxns += "<th>Product</th>";
                        losstxns += "<th>Vendor Margin</th>";
                        losstxns += "<th>Retailer Margin</th>";
                        losstxns += "<th>Profit</th>";
                        losstxns += "<th>Amount</th>";
                        losstxns += "<th>Status</th>";

                        losstxns += "</tr>";
                        losstxns += "</thead>";
                        losstxns += "<tbody>";
                        for (var x in response.loss_txns) {
                            if (!isNaN(x)) {
                                if (response.loss_txns[x].wallets_transactions.cr_db = 'cr') {
                                    var cr_profit = response.loss_txns[x].wallets_transactions.vendor_settled_amount - response.loss_txns[x].wallets_transactions.amount_settled;
                                    if (cr_profit < 0) {
                                        losstxns += "<tr>";
                                        losstxns += "<td>" + (response.loss_txns[x].wallets_transactions.date == null ? '' : response.loss_txns[x].wallets_transactions.date) + "</td>";
                                        losstxns += "<td>" + (response.loss_txns[x].wallets_transactions.txn_id == null ? '' : response.loss_txns[x].wallets_transactions.txn_id) + "</td>";
                                        losstxns += "<td>" + (response.loss_txns[x].wallets_transactions.vendor_refid == null ? '' : response.loss_txns[x].wallets_transactions.vendor_refid) + "</td>";
                                        losstxns += "<td>" + (response.loss_txns[x].products.name == null ? '' : response.loss_txns[x].products.name) + "</td>";
                                        var vendor_margin = response.loss_txns[x].wallets_transactions.vendor_settled_amount - response.loss_txns[x].wallets_transactions.amount;
                                        var retailer_margin = response.loss_txns[x].wallets_transactions.amount_settled - response.loss_txns[x].wallets_transactions.amount;
                                        losstxns += "<td>" + vendor_margin.toFixed(2) + "</td>";
                                        losstxns += "<td>" + retailer_margin.toFixed(2) + "</td>";
                                        losstxns += "<td>" + cr_profit.toFixed(2) + "</td>";
                                        losstxns += "<td>" + (response.loss_txns[x].wallets_transactions.amount == null ? '' : response.loss_txns[x].wallets_transactions.amount) + "</td>";
                                        losstxns += (response.loss_txns[x].wallets_transactions.status == '1' || response.loss_txns[x].wallets_transactions.status == '0' ? "<td style='color :green;'><b>Success</b> </td>" : "<td style='color :red;'> <b>Failed</b></td>");
                                        losstxns += "</tr>";


                                    }
                                }
                                if (response.loss_txns[x].wallets_transactions.cr_db = 'db') {
                                    var db_profit = response.loss_txns[x].wallets_transactions.amount_settled - response.loss_txns[x].wallets_transactions.vendor_settled_amount;
                                    if (db_profit < 0) {
                                        losstxns += "<tr>";
                                        losstxns += "<td>" + (response.loss_txns[x].wallets_transactions.date == null ? '' : response.loss_txns[x].wallets_transactions.date) + "</td>";
                                        losstxns += "<td>" + (response.loss_txns[x].wallets_transactions.txn_id == null ? '' : response.loss_txns[x].wallets_transactions.txn_id) + "</td>";
                                        losstxns += "<td>" + (response.loss_txns[x].wallets_transactions.vendor_refid == null ? '' : response.loss_txns[x].wallets_transactions.vendor_refid) + "</td>";
                                        losstxns += "<td>" + (response.loss_txns[x].products.name == null ? '' : response.loss_txns[x].products.name) + "</td>";
                                        var vendor_margin = response.loss_txns[x].wallets_transactions.amount - response.loss_txns[x].wallets_transactions.vendor_settled_amount;
                                        var retailer_margin = response.loss_txns[x].wallets_transactions.amount - response.loss_txns[x].wallets_transactions.amount_settled;
                                        losstxns += "<td>" + vendor_margin.toFixed(2) + "</td>";
                                        losstxns += "<td>" + retailer_margin.toFixed(2) + "</td>";
                                        losstxns += "<td>" + db_profit.toFixed(2) + "</td>";
                                        losstxns += "<td>" + (response.loss_txns[x].wallets_transactions.amount == null ? '' : response.loss_txns[x].wallets_transactions.amount) + "</td>";
                                        losstxns += (response.loss_txns[x].wallets_transactions.status == '1' || response.loss_txns[x].wallets_transactions.status == '0' ? "<td style='color :green;'><b>Success</b> </td>" : "<td style='color :red;'> <b>Failed</b></td>");
                                        losstxns += "</tr>";
                                    }
                                }
                            }
                        }

                        losstxns += "</tbody>";
                        losstxns += "</table>";
                        losstxns += "</div>";
                        losstxns += "</div>";
                        jQuery("#losstxns").html(losstxns);
                        jQuery('#losstxnsTable').dataTable({
                            "scrollY": "200px",
                            "scrollCollapse": true,
                            "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
                            "pageLength": 10,
                            "lengthMenu": [10, 20, 50],
                        });
                    }
                }
            });
        });


        jQuery('#service').change(function() {
            var serviceid = jQuery(this).val();
            jQuery.ajax({
                url: '/accounting/txnRecon',
                dataType: 'json',
                data: {service: '1', serviceid: serviceid},
                type: 'POST',
                success: function(response) {
                    jQuery('#vendor').html('');
                    if (response.length > 0) {
                        jQuery('#upload_container').show();
                        jQuery.each(response, function(i, item) {
                            jQuery('#vendor').append("<option value=" + item.product_vendors.id + ">" + item.product_vendors.name + "</option>");
                        });
                    } else {
                        jQuery('#upload_container').hide();
                        alert("No vendor found for this service");
                        return false;
                    }


                }
            });
        });

        jQuery("#upload").click(function() {
            jQuery("#dvTable").html('');
            jQuery('#response').html("<img src='/img/ajax-loader-2.gif' />");
            var file_data = jQuery('#upload_file').prop('files')[0];
            var service = jQuery('#service').val();
            var vendor = jQuery('#vendor').val();
            if (service == '0' || vendor == '0') {
                alert('Please select service and vendor');
                jQuery('#response').html('');
                return false;
            }
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('service', service);
            form_data.append('vendor', vendor);
            jQuery.ajax({
                url: '/accounting/displayTxnRecon',
                dataType: 'json',
                contentType: false,
                processData: false,
                data: form_data,
                type: 'POST',
                success: function(data) {
                    jQuery('#response').html('');
                    if (data.status == '1') {
                        var table = "<div class='panel panel-primary filterable'>";
                        table += "<div class='panel-heading'>";
                        table += "<h3 class='panel-title'>Transaction Recon System</h3>";
                        table += "</div>";
                        table += "<div class=''>";
                        table += "<table class='table table-responsive' id='txntable'>";
                        table += "<thead>";
                        table += "<tr class='filters'>";
                        table += "<th>Txn id</th>";
                        table += "<th>Vendor txn Id</th>";
                        table += "<th>Amount</th>";
                        table += "<th>Status</th>";
                        table += "<th>Date</th>";
                        table += "</tr>";
                        table += "</thead>";
                        table += "<tbody id='myTable'>";
                        for (var x in data.success) {
                            if (!isNaN(x)) {
                                table += "<tr>";
                                table += "<td>" + (data.success[x].txn_id == null ? '' : data.success[x].txn_id) + "</td>";
                                table += "<td>" + (data.success[x].vendor_txn_id == null ? '' : data.success[x].vendor_txn_id) + "</td>";
                                table += "<td>" + (data.success[x].amount == null ? '' : data.success[x].amount) + "</td>";
                                table += ((data.success[x].status == '1') ? "<td style='color :green;'><b>Success</b> </td>" : "<td style='color :red;'><b>Failed</b> </td>");
                                table += "<td>" + (data.success[x].date == null ? '' : data.success[x].date) + "</td>";
                                table += "</tr>";
                            }
                        }
                        for (var y in data.fail) {
                            if (!isNaN(y)) {
                                table += "<tr>";
                                table += "<td>" + (data.success[x].txn_id == null ? '' : data.success[x].txn_id) + "</td>";
                                table += "<td>" + (data.success[x].vendor_txn_id == null ? '' : data.success[x].vendor_txn_id) + "</td>";
                                table += "<td>" + (data.success[x].amount == null ? '' : data.success[x].amount) + "</td>";
                                table += "<td style='color :red;'><b>Failed</b> </td>";
                                table += "<td>" + (data.success[x].date == null ? '' : data.success[x].date) + "</td>";
                                table += "</tr>";
                            }
                        }
                        table += "</tbody>";
                        table += "</table>";
                        table += "</div>";
                        table += "</div>";
                        jQuery("#dvTable").html(table);
                        jQuery('#txntable').dataTable({
                            //        "order": [[0, "desc" ]],
                            "searching": false,
                            "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
                            "pageLength": 10,
                            "lengthMenu": [10, 50, 100],
                        });
                    } else {
                        alert(data.message);
                    }
                }
            });
        });
        
        function showModal(response){
                var flag = true;
                var modal = "<table class='table table-responsive' id='overview'>";
                if(flag == true){
                    flag = false;
                    modal += '<thead>';
                    modal += '<tr>';
                    if (response.total_sale != null) {
                        modal += ((parseFloat(response.total_sale) != 0.00) ? "<th>Total Sale</th>" : '');
                    }
                    if (response.total_txn_count != null) {
                        modal += ((parseFloat(response.total_txn_count) != 0.00) ? "<th>Total Txn Count</th>" : '');
                    }
                    if (response.retailer_service_charge != null) {
                        modal += ((parseFloat(response.retailer_service_charge) != 0.00) ? "<th>Retailer Service Charge</th>" : '');
                    }
                    if (response.vendor_service_charge != null) {
                        modal += ((parseFloat(response.vendor_service_charge) != 0.00) ? "<th>Vendor Service Charge</th>" : '');
                    }
                    if (response.retailer_commission != null) {
                        modal += ((parseFloat(response.retailer_commission) != 0.00) ? "<th>Retailer Commission</th>" : '');
                    }
                    if (response.vendor_commission != null) {
                        modal += ((parseFloat(response.vendor_commission) != 0.00) ? "<th>Vendor Commission</th>" : '');
                    }
                    modal += "</tr>";
                    modal += "</thead>";
                }

                modal += '<tbody>';
                modal += '<tr>';
                if (response.total_sale != null) {
                    modal += ((parseFloat(response.total_sale) != 0.00) ? "<td>" + response.total_sale + "</td>" : '');
                }
                if (response.total_txn_count != null) {
                    modal += ((parseFloat(response.total_txn_count) != 0.00) ? "<td>" + response.total_txn_count + "</td>" : '');
                }
                if (response.retailer_service_charge != null) {
                    modal += ((parseFloat(response.retailer_service_charge) != 0.00) ? "<td>" + response.retailer_service_charge + "</td>" : '');
                }
                if (response.vendor_service_charge != null) {
                    modal += ((parseFloat(response.vendor_service_charge) != 0.00) ? "<td>" + response.vendor_service_charge + "</td>" : '');
                }
                if (response.retailer_commission != null) {
                    modal += ((parseFloat(response.retailer_commission) != 0.00) ? "<td>" + response.retailer_commission + "</td>" : '');
                }
                if (response.vendor_commission != null) {
                    modal += ((parseFloat(response.vendor_commission) != 0.00) ? "<td>" + response.vendor_commission + "</td>" : '');
                }
                modal += "</tr>";
                modal += "</tbody>";
                modal += "</tbody>";
                modal += "</table>";
                modal += "</div>";
                jQuery('#modal_table').html(modal);
                jQuery('#myModal').modal('show');
        }
    });

    jQuery("#upload_file").change(function() {
        var fileExtension = ['xls', 'csv'];
        if (jQuery.inArray(jQuery(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("Only formats are allowed : " + fileExtension.join(', '));
            return false;
        }
    });
</script>
<script>jQuery.noConflict();</script>
