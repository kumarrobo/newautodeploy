<link href="/boot/css/font-awesome.min.css" rel="stylesheet">
<script src="/boot/js/jquery-2.0.3.min.js"></script>
<script src="/boot/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.css">
<script src = "/boot/js/jquery-ui.js"></script>
<link href = "/boot/css/jquery-ui.css" rel = "stylesheet">
<script src="/boot/js/moment.min.js"></script>
<script src="/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>
<form class="form-inline" id="reportform" name="reportform" method="POST" action="/accounting/txnRecon" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-1">
                        <label>Services</label>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <select id="service" name="service" class="form-control">
                                <option value="0">---------- All ----------</option>
                                <?php
                                    foreach($services as $service){
                                            echo "<option value='".$service['services']['id']."'>".$service['services']['name']." </option>";
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
                                <option value="0">----------- All -----------</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label>Upload File</label>
                    </div>
                    <div class="col-md-3">
                        <input type="file" name="upload_file" id="upload_file" class="form-control">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info btn-md">Submit</button><span id="response"></span>
                    </div>
                </div>
    <br><br><div id="dvTable"></div>        
</form>

<script>
    jQuery(document).ready(function() {
        jQuery('#service').change(function(){
            var serviceid = jQuery(this).val();
            jQuery.ajax({
                url: '/accounting/txnRecon',
                dataType: 'json',
                data: {service: '1', serviceid: serviceid},
                type: 'POST',
                success: function(response) {
                    jQuery('#vendor').html('');
                    jQuery.each(response, function (i, item) {
                        jQuery('#vendor').append("<option value=" + item.product_vendors.id + ">" + item.product_vendors.name + "</option>");
                    });
                }
            });
        });
        
        jQuery("button").click(function() {
            jQuery('#response').html("<img src='/img/ajax-loader-2.gif' />");
            var file_data = jQuery('#upload_file').prop('files')[0];
            var service = jQuery('#service').val();
            var vendor = jQuery('#vendor').val();
            if(service == '0' || vendor == '0'){
                alert('Please select service and vendor');
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
                        var table = "<div class='panel panel-info filterable'>";
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
                            table += "<th>Settled Amount</th>";
                            table += "<th>Status</th>";
                            table += "<th>Date</th>";
                            table += "<th>Description</th>";
                        table += "</tr>";
                        table += "</thead>";
                        table += "<tbody id='myTable'>";
                        for (var x in data.success) {
                            if (!isNaN(x)) {
                                table += "<tr>";
                                table += "<td>" + data.success[x].txn_id + "</td>";
                                table += "<td>" + data.success[x].vendor_txn_id + "</td>";
                                table += "<td>" + data.success[x].amount + "</td>";
                                table += "<td>" + data.success[x].settled_amount + "</td>";
                                table += "<td>" + data.success[x].status + "</td>";
                                table += "<td>" + data.success[x].date + "</td>";
                                table += "<td style='color :green;'><b>Success</b> </td>";
//                                table += "<td>" + data.success[x].description + "</td>";
                                table += "</tr>";
                            }
                        }
                        for (var y in data.fail) {
                            if (!isNaN(y)) {
                                table += "<tr>";
                                table += "<td>" + data.fail[y].txn_id + "</td>";
                                table += "<td>" + data.fail[y].vendor_txn_id + "</td>";
                                table += "<td>" + data.fail[y].amount + "</td>";
                                table += "<td>" + data.fail[y].settled_amount + "</td>";
                                table += "<td>" + data.fail[y].status + "</td>";
                                table += "<td>" + data.fail[y].date + "</td>";
                                table += "<td style='color :red;'> <b>Failed</b></td>";
//                                table += "<td>" + data.fail[y].description + "</td>";
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
                            "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0 ] }],
                            "pageLength":10,
                            "lengthMenu": [10, 20, 25],
                        });
                    } else {
                        alert(data.message);
                    }
                }
            });
        });
    });

    jQuery("#upload_file").change(function() {
        var fileExtension = ['xls'];
        if (jQuery.inArray(jQuery(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("Only formats are allowed : " + fileExtension.join(', '));
            location.reload();
        }
    });
</script>
<script>jQuery.noConflict();</script>