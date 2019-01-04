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
        <li class="active"><a data-toggle="tab" href="#home" style="font-weight:bold">Vendor Recon Report</a></li>
        <li><a data-toggle="tab" href="#menu2" style="font-weight:bold">Set Opening</a></li>
        <li><a data-toggle="tab" href="#menu3" style="font-weight:bold">Set Loss</a></li>
        <!--<li><a data-toggle="tab" href="#menu4" style="font-weight:bold">Security Deposit</a></li>-->
        <li><a data-toggle="tab" href="#menu4" style="font-weight:bold">Commission Adjustment</a></li>
        <li><a data-toggle="tab" href="#menu5" style="font-weight:bold">Service Charge Adjustment</a></li>
    </ul>
    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <form class="form-inline"  name="reportform">
                <div style="padding-top: 30px;">
                    <div class="row">
                        <div class="col-md-1">
                            <label>Services</label>
                        </div>
                        <div class="col-md-3">
                            <select id="service" name="service" class="form-control">
                                    <option value="0">Select Service</option>
                                    <?php
                                    foreach ($services as $service) {
                                        echo "<option value='" . $service['services']['id'] . "'>" . $service['services']['name'] . " </option>";
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="btn-label"  style='padding-left :30px; padding-top:5px;'>Vendor</label>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <select  class="form-control" name="vendorid" id="vendorid" style="width:200px;">
                                    <option value="0">Select Vendor</option>
                                </select>
                            </div>
                        </div>
                    </div>
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
                            <label class="btn-label" style="margin-left: 35px;padding-top: 5px;">To  </label>
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
            <div class="row" id="vendortxnTable"></div>
        </div>
        <div id="menu2" class="tab-pane fade">
            <form class="form-inline"  name="reportform">
                <div style="padding-top: 45px;">
                    <div class="row">
                        <div class="col-md-1">
                            <label>Service</label>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <select id="serviceid" name="serviceid" class="form-control">
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
                        </div>
                    <div class="row">
                        <div class="col-md-1">
                            <label>Date</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" style="padding-left :30px;" id="opening_date" name="opening_date" >
                        </div>
                        <div class="col-md-1">
                            <label>Opening</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" style="padding-left :30px;" id="opening" name="opening" >
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary btn-md" id="update_opening"  >Update</button><span id="opening_response"></span>
                        </div>
                    </div>
                    <br><br><div id="dvTable"></div>    
                </div>
            </form>
        </div>
        
        
        <div id="menu3" class="tab-pane fade">
            <form class="form-inline"  name="reportform" >
                <div style="padding-top: 45px;">
                    <div class="row">
                        <div class="col-md-1">
                            <label>Services</label>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <select id="services" name="services" class="form-control">
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
                        <div class="col-md-3">
                            <div class="input-group">
                                <select id="product_vendor" name="product_vendor" class="form-control" style="width:180px;" >
                                    <option value="0">Select Vendor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <label>Date</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" style="padding-left :30px;" id="loss_date" name="loss_date" >
                        </div>
                        <div class="col-md-1">
                            <label>Loss</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" style="padding-left :30px;" id="loss" name="loss" >
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary btn-md" id="update_loss" >Update</button><span id="loss_response"></span>
                        </div>
                    </div>
                    <br><br><div id="dvTable"></div>    
                </div>
            </form>
        </div>
<!--        <div id="menu4" class="tab-pane fade">
            <form class="form-inline"  name="reportform" >
                <div style="padding-top: 45px;">
                    <div class="row">
                        <div class="col-md-1">
                            <label>Services</label>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <select id="service_id" name="service_id" class="form-control">
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
                        <div class="col-md-3">
                            <div class="input-group">
                                <select id="vendor_data" name="vendor_data" class="form-control" style="width:180px;" >
                                    <option value="0">Select Vendor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <label>Date</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" style="padding-left :30px;" id="service_date" name="service_date" >
                        </div>
                        <div class="col-md-1">
                            <label>Security Deposit</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" style="padding-left :30px;" id="security_deposit" name="security_deposit" >
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary btn-md" id="update_security" >Update</button><span id="service_response"></span>
                        </div>
                    </div>
                    <br><br><div id="dvTable"></div>    
                </div>
            </form>
        </div>-->
        
<div id="menu4" class="tab-pane fade">
            <form class="form-inline"  name="reportform" >
                <div style="padding-top: 45px;">
                    <div class="row">
                        <div class="col-md-1">
                            <label>Services</label>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <select id="service_list" name="service_list" class="form-control">
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
                        <div class="col-md-3">
                            <div class="input-group">
                                <select id="vendor_list" name="vendor_list" class="form-control" style="width:180px;" >
                                    <option value="0">Select Vendor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <label>Date</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" style="padding-left :30px;" id="commission_date" name="commission_date" >
                        </div>
                        <div class="col-md-1">
                            <label>Commission Adjustment</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" style="padding-left :30px;" id="commission" name="commission" >
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary btn-md" id="update_commission" >Update</button><span id="commission_response"></span>
                        </div>
                    </div>
                    <br><br><div id="dvTable"></div>    
                </div>
            </form>
        </div>

        <div id="menu5" class="tab-pane fade">
            <form class="form-inline"  name="reportform" >
                <div style="padding-top: 45px;">
                    <div class="row">
                        <div class="col-md-1">
                            <label>Services</label>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <select id="services_list" name="services_list" class="form-control">
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
                        <div class="col-md-3">
                            <div class="input-group">
                                <select id="vendors_list" name="vendors_list" class="form-control" style="width:180px;" >
                                    <option value="0">Select Vendor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <label>Date</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" style="padding-left :30px;" id="service_charge_date" name="service_charge_date" >
                        </div>
                        <div class="col-md-1">
                            <label>Service Charge Adjustment</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" style="padding-left :30px;" id="service_charge" name="service_charge" >
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary btn-md" id="update_service_charge" >Update</button><span id="service_charge_response"></span>
                        </div>
                    </div>
                    <br><br><div id="dvTable"></div>    
                </div>
            </form>
        </div>

    </div>
</div>

<script>

    var dt= new Date();
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
    jQuery('#opening_date').datetimepicker({
        defaultDate: dt,
        format: 'YYYY-MM-DD',
        maxDate: dt
    });
    jQuery('#loss_date').datetimepicker({
        defaultDate: dt,
        format: 'YYYY-MM-DD',
        maxDate: dt
    });
//    jQuery('#service_date').datetimepicker({
//        defaultDate: dt,
//        format: 'YYYY-MM-DD',
//        maxDate: dt
//    });
     jQuery('#commission_date').datetimepicker({
        defaultDate: dt,
        format: 'YYYY-MM-DD',
        maxDate: dt
    });

     jQuery('#service_charge_date').datetimepicker({
        defaultDate: dt,
        format: 'YYYY-MM-DD',
        maxDate: dt
    });

    jQuery(document).ready(function($) {
        
        $('#service').change(function() {
            var serviceid = $(this).val();
//            var vendorid = $(this).val();
            $.ajax({
                type: 'POST',
                url: '/accounting/vendorSearch',
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
        
        $('#services').change(function() {
            var serviceid = $(this).val();
//            var vendorid = $(this).val();
            $.ajax({
                type: 'POST',
                url: '/accounting/vendorSearch',
                dataType: 'json',
                data: {serviceflag: '1', servicedata: serviceid},
                success: function(data) {
                    $('#product_vendor').html('');
                    $.each(data, function(i, item) {
                        $('#product_vendor').append("<option value=" + item.product_vendors.id + ">" + item.product_vendors.name + "</option>");
                    });
                }
            });
        });
        
         $('#service_list').change(function() {
            var serviceid = $(this).val();
//            var vendorid = $(this).val();
            $.ajax({
                type: 'POST',
                url: '/accounting/vendorSearch',
                dataType: 'json',
                data: {serviceflag: '1', servicedata: serviceid},
                success: function(data) {
                    $('#vendor_list').html('');
                    $.each(data, function(i, item) {
                        $('#vendor_list').append("<option value=" + item.product_vendors.id + ">" + item.product_vendors.name + "</option>");
                    });
                }
            });
        });
        
            $('#services_list').change(function() {
            var serviceid = $(this).val();
//            var vendorid = $(this).val();
            $.ajax({
                type: 'POST',
                url: '/accounting/vendorSearch',
                dataType: 'json',
                data: {serviceflag: '1', servicedata: serviceid},
                success: function(data) {
                    $('#vendors_list').html('');
                    $.each(data, function(i, item) {
                        $('#vendors_list').append("<option value=" + item.product_vendors.id + ">" + item.product_vendors.name + "</option>");
                    });
                }
            });
        });
        
//        $('#service_id').change(function() {
//            var serviceid = $(this).val();
////            var vendorid = $(this).val();
//            $.ajax({
//                type: 'POST',
//                url: '/accounting/vendorSearch',
//                dataType: 'json',
//                data: {serviceflag: '1', servicedata: serviceid},
//                success: function(data) {
//                    $('#vendor_data').html('');
//                    $.each(data, function(i, item) {
//                        $('#vendor_data').append("<option value=" + item.product_vendors.id + ">" + item.product_vendors.name + "</option>");
//                    });
//                }
//            });
//        });
        
        $('#serviceid').change(function() {
            var serviceid = $(this).val();
//            var vendorid = $(this).val();
            $.ajax({
                type: 'POST',
                url: '/accounting/vendorSearch',
                dataType: 'json',
                data: {serviceflag: '1', servicedata: serviceid},
                success: function(data) {
                    $('#vendor').html('');
                    $.each(data, function(i, item) {
                        $('#vendor').append("<option value=" + item.product_vendors.id + ">" + item.product_vendors.name + "</option>");
                    });
                }
            });
        });

        $('#update_opening').click(function() {
            jQuery('#opening_response').html("<img src='/img/ajax-loader-2.gif' />");

            var data = {
                'update_opening': 1,
                'opening_date': $('#opening_date').val(),
                'opening' : $('#opening').val(),
                'vendor': $('#vendor').val()
            };
            $.ajax({
                type: 'POST',
                url: '/accounting/vendorDashboard',
                dataType: 'json',
                data: data,
                success: function(response) {
                    jQuery('#opening_response').html("");
                    if(response.status == '1'){
                        alert("Opening Updated Successfully!");
                    } else {
                        alert("Unable to update!!!");
                    }
                    $('#opening_date').val('<?php echo date('Y-m-d'); ?>');
                    $('#opening').val('');
                    $('#vendor').val('0');
                }
            });
        });
        
        $('#update_loss').click(function() {
            jQuery('#loss_response').html("<img src='/img/ajax-loader-2.gif' />");

            var data = {
                'update_loss': 1,
                'loss_date': $('#loss_date').val(),
                'loss' :  $('#loss').val(),
                'product_vendor': $('#product_vendor').val()
            };
            $.ajax({
                type: 'POST',
                url: '/accounting/vendorDashboard',
                dataType: 'json',
                data: data,
                success: function(response) {
                    jQuery('#loss_response').html("");
                    if(response.status == '1'){
                        alert("Loss Updated Successfully!");
                    } else {
                        alert("Unable to update!!!");
                    }
                    $('#loss_date').val('<?php echo date('Y-m-d'); ?>');
                    $('#loss').val('');
                    $('#product_vendor').val('0');
                }
            });
        });
        
        $('#update_commission').click(function() {
            jQuery('#commission_response').html("<img src='/img/ajax-loader-2.gif' />");
            
             var data = {
                'commission_adjustment': 1,
                'commission_date': $('#commission_date').val(),
                'commission' :  $('#commission').val(),
                'vendor_list': $('#vendor_list').val()
            };
            $.ajax({
                type: 'POST',
                url: '/accounting/vendorDashboard',
                dataType: 'json',
                data: data,
                success: function(response) {
                    jQuery('#commission_response').html("");
                     if(response.status == '1'){
                        alert("Commission Updated Successfully!");
                    } else {
                        alert("Unable to update commission!!!");
                    }
                    $('#commission_date').val('<?php echo date('Y-m-d'); ?>');
                    $('#commission').val('');
                    $('#vendor_list').val('0');
                }
            });
        });
        
        $('#update_service_charge').click(function() {
            jQuery('#service_charge_response').html("<img src='/img/ajax-loader-2.gif' />");
            
             var data = {
                'service_charge_adjustment': 1,
                'service_charge_date': $('#service_charge_date').val(),
                'service_charge' :  $('#service_charge').val(),
                'vendors_list': $('#vendors_list').val()
            };
            $.ajax({
                type: 'POST',
                url: '/accounting/vendorDashboard',
                dataType: 'json',
                data: data,
                success: function(response) {
                    jQuery('#service_charge_response').html("");
                     if(response.status == '1'){
                        alert("Service Charge Updated Successfully!");
                    } else {
                        alert("Unable to update service charge!!!");
                    }
                    $('#service_charge_date').val('<?php echo date('Y-m-d'); ?>');
                    $('#service_charge').val('');
                    $('#vendors_list').val('0');
                }
            });
        });
        
        
//         $('#update_security').click(function() {
//            jQuery('#service_response').html("<img src='/img/ajax-loader-2.gif' />");
//
//            var data = {
//                'update_security': 1,
//                'service_date': $('#service_date').val(),
//                'security_deposit' :  $('#security_deposit').val(),
//                'vendor_data': $('#vendor_data').val()
//            };
//            $.ajax({
//                type: 'POST',
//                url: '/accounting/vendorDashboard',
//                dataType: 'json',
//                data: data,
//                success: function(response) {
//                    jQuery('#service_response').html("");
//                    if(response.status == '1'){
//                        alert("Service Charge Updated Successfully!");
//                    } else {
//                        alert("Unable to update!!!");
//                    }
//                    $('#service_date').val('<?php echo date('Y-m-d'); ?>');
//                    $('#security_deposit').val('');
//                    $('#vendor_data').val('0');
//                }
//            });
//        });
        
        $('#filterdata').click(function() {
            jQuery('#filter_response').html("<img src='/img/ajax-loader-2.gif' />");

            var data = {
                'filter': 1,
                'fromdate': $('#from_date').val(),
                'todate': $('#to_date').val(),
                'vendor': $('#vendorid').val()
            };
            $.ajax({
                type: 'POST',
                url: '/accounting/vendorDashboard',
                dataType: 'json',
                data: data,
                success: function(response) {
                    if (response.status == 'failed') {
                        alert(response.msg);
                        return false;
                    }
                    if (response.length == 0) {
                        jQuery('#vendortxnTable').html("");
                    }
                    jQuery('#filter_response').html("");
                    if (response.length > 0) {
                        var vendortxn = "<div class='panel panel-primary filterable'>";
                        vendortxn += "<div class='panel-heading'>";
                        vendortxn += "<h3 class='panel-title'>Vendor Transaction</h3>";
                        vendortxn += "</div>";
                        vendortxn += "<div class=''>";
                        vendortxn += "<table class='table table-responsive' id='vendortxn'>";
                        vendortxn += "<thead>";
                        vendortxn += "<tr class='filters'>";
                        vendortxn += "<th>Date</th>";
                        vendortxn += "<th>Vendor</th>";
                        vendortxn += "<th>Opening</th>";
                        vendortxn += "<th>Closing</th>";
                        vendortxn += "<th>Topup</th>";
                        vendortxn += "<th>Sale</th>";
                        vendortxn += "<th>Loss</th>";
                        vendortxn += "<th>Commission</th>";
                        vendortxn += "<th>Commission TDS</th>";
                        vendortxn += "<th>Service Charge</th>";
                        vendortxn += "<th>Vendor Commission</th>";
                        vendortxn += "<th>Vendor Service Charge</th>";
                        vendortxn += "<th>Diff</th>";
                        vendortxn += "</tr>";
                        vendortxn += "</thead>";
                        vendortxn += "<tbody>";
                        
                        var diff_total = 0;
                        for (var x in response) {
                            if (!isNaN(x)) {
                                    var commission_tds = ((parseInt(response[x].vendor_recon.commission) - parseInt(response[x].vendor_recon.commission_adjustment))/1.18) * 0.05;
                                    var sale = response[x].vendor_recon.sale == null ? '' : (jQuery.inArray(parseInt(response[x].vendor_recon.vendor_id), [1,2,3]) == -1 ? (parseInt(response[x].vendor_recon.sale) - parseInt(response[x].vendor_recon.refund)) : (parseInt(response[x].vendor_recon.sale) - parseInt(response[x].vendor_recon.refund)) * -1);
                                    var diff = (parseInt(response[x].vendor_recon.closing) + (sale) - parseInt(response[x].vendor_recon.opening) - parseInt(response[x].vendor_recon.topup) + parseInt(response[x].vendor_recon.loss) - (parseInt(response[x].vendor_recon.commission) - parseInt(response[x].vendor_recon.commission_adjustment) - parseInt(commission_tds)) + (parseInt(response[x].vendor_recon.service_charge) - parseInt(response[x].vendor_recon.service_charge_adjustment)));
                                    diff_total += diff;
                                    vendortxn += "<tr>";
                                    vendortxn += "<td>" + (response[x].vendor_recon.date == null ? '' : response[x].vendor_recon.date) + "</td>";
                                    vendortxn += "<td>" + (response[x].product_vendors.name == null ? '' : response[x].product_vendors.name) + "</td>";
                                    vendortxn += "<td>" + (response[x].vendor_recon.opening == null ? '' : response[x].vendor_recon.opening) + "</td>";
                                    vendortxn += "<td>" + (response[x].vendor_recon.closing == null ? '' : response[x].vendor_recon.closing) + "</td>";
                                    vendortxn += "<td>" + (response[x].vendor_recon.topup == null ? '' : response[x].vendor_recon.topup) + "</td>";
                                    vendortxn += "<td>" + (sale) + " (" + parseInt(response[x].vendor_recon.refund) + ")</td>";
                                    vendortxn += "<td>" + (response[x].vendor_recon.loss == null ? '' : response[x].vendor_recon.loss) + "</td>";
                                    vendortxn += "<td>" + (response[x].vendor_recon.commission == null ? '' : (parseInt(response[x].vendor_recon.commission) - parseInt(response[x].vendor_recon.commission_adjustment))) + " (" + parseInt(response[x].vendor_recon.commission_adjustment) + ")</td>";
                                    vendortxn += "<td>" + parseInt(commission_tds) + "</td>";
                                    vendortxn += "<td>" + (response[x].vendor_recon.service_charge == null ? '' : (parseInt(response[x].vendor_recon.service_charge) - parseInt(response[x].vendor_recon.service_charge_adjustment))) + " (" + parseInt(response[x].vendor_recon.service_charge_adjustment) + ")</td>";
                                    vendortxn += "<td>" + (response[x].vendor_recon.vendor_comm == null ? '' : response[x].vendor_recon.vendor_comm) + "</td>";
                                    vendortxn += "<td>" + (response[x].vendor_recon.vendor_sc == null ? '' : response[x].vendor_recon.vendor_sc) + "</td>";
                                    vendortxn += "<td>" + diff.toFixed(2) + "</td>";
                                    vendortxn += "</tr>";
                            }
                        }
                        vendortxn += "</tbody>";
                        vendortxn += "<tfoot>";
                        vendortxn += "<tr><td colspan='10'></td><td><b>" + diff_total.toFixed(2) + "</b></td></tr>";
                        vendortxn += "</tfoot>";
                        vendortxn += "</table>";
                        vendortxn += "</div>";
                        vendortxn += "</div>";
                        jQuery("#vendortxnTable").html(vendortxn);
                        jQuery('#vendortxn').dataTable({
//                            "scrollY": "200px",
//                            "scrollCollapse": true,
                            "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
                            "pageLength": 10,
                            "lengthMenu": [10, 20, 50],
                        });
                    }
                }
            });
        });
    });
</script>
<script>jQuery.noConflict();</script>