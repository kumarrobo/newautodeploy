<link href="/boot/css/font-awesome.min.css" rel="stylesheet">
<script src="/boot/js/jquery-2.0.3.min.js"></script>
<script src="/boot/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

<script src = "/boot/js/jquery-ui.js"></script>
<link href = "/boot/css/jquery-ui.css" rel = "stylesheet">
<script src="/boot/js/moment.min.js"></script>
<script src="/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">

<style>
    .table-responsive {height:180px;}
    .centered-form{
        margin-top: 60px;
    }
    .centered-form .panel{
        background: rgba(255, 255, 255, 0.8);
        box-shadow: rgba(0, 0, 0, 0.3) 20px 20px 20px;
    }
    .filterable {
        margin-top: 15px;
    }
    .filterable .panel-heading .pull-right {
        margin-top: -20px;
    }

    .list-group {
        margin:auto;
        float:left;
        padding-top:20px;
    }
    
</style>
<div class="container">
    <div class="row">
        <div class='col-sm-2'>
            <div class="list-group" style="width:100%;">
            <a href="/leads/assignLead" class="list-group-item "><i class="fa fa-tasks"></i> <span>Assign Lead</span></a>
            <a href="/leads/leadList" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Lead List</span></a>
            <a href="/leads/format" class="list-group-item active"><i class="fa fa-list-alt"></i> <span>Lead Upload</span></a>
            <a href="/leads/employeeDetails" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Employee Leads</span></a>
            <a href="/leads/report" class="list-group-item "><i class="fa fa-credit-card"></i> <span>Report</span></a>
        </div>
        </div>
        
        <div class='col-sm-10' >
            <form class="form-inline" id="reportform" name="reportform" method="POST" action="/leads/format" enctype="multipart/form-data">
                <table cellspacing ="0" cellpadding="0">
                    <tr>
                        <td><label style="font-size:15px; padding-right :30px;">Upload File</label></td>
                        <td><input type="file" name="upload_file" id="upload_file" class="form-control">
                        <button type="button" class="btn btn-primary btn-md">Submit</button>
                        <span id="response"></span>
                        </td>
                    </tr>
                </table>

                <div id="dvTable">
                </div>
            </form>
        </div>
    </div>
    <script>
        jQuery(document).ready(function () {
            jQuery('.list-group-item').click(function(e) {
//            e.preventDefault();
            jQuery('.list-group-item').removeClass('active');
            jQuery(this).addClass('active');
         });
            
            jQuery("button").click(function () {
                jQuery('#response').html("<img src='/img/ajax-loader-2.gif' />");
                var file_data = jQuery('#upload_file').prop('files')[0];
                var form_data = new FormData();
                form_data.append('file', file_data);
                
        jQuery.ajax({
                    url: '/leads/displayData',
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'POST',
                    success: function (data) {
                        jQuery('#response').html('');
                        if(data.status == 1){
                        var table = "<div class='panel panel-primary filterable'>";
                        table += "<div class='panel-heading'>";
                        table += "<h3 class='panel-title'>Lead Format</h3>";
                        table += "</div>";
                        table += "<div class=''>";
                        table += "<table class='table table-responsive'>";
                        table += "<thead>";
                        table += "<tr class='filters'>";
                        
                        table += "<th>Lead Id</th>";
                        table += "<th>Lead date</th>";
                        table += "<th>Name</th>";
                        table += "<th>State</th>";
                        table += "<th>Pincode</th>";
                        table += "<th>Leads Source</th>";
                        table += "<th>Mobile No</th>";
                        
                        table += "<th>Alt Mobile No</th>";
                        table += "<th>Result</th>";
                        table += "<th>Description</th>"
                        table += "</tr>";
                        table += "</thead>";
                        table += "<tbody id='myTable'>";

                        for (var x in data.success) {
                            table += "<tr>";
                            if (!isNaN(x)) {
                                table += "<td>" + data.success[x].lead_id + "</td>";
                                table += "<td>" + data.success[x].creation_date + "</td>";
                                table += "<td>" + data.success[x].name + "</td>";
                                table += "<td>" + data.success[x].state + "</td>";
                                table += "<td>" + data.success[x].pin_code + "</td>";
                                table += "<td>" + data.success[x].lead_source + "</td>";
                                table += "<td>" + data.success[x].phone + "</td>";
                                table += "<td>"+ data.success[x].alternate_no +"</td>";
                                table += "<td style='color :green;'>Success </td>";
                                table += "<td>" + data.success[x].description + "</td>";

                            }
                            table += "</tr>";
                        }
                        for (var y in data.fail) {
                            table += "<tr>";
                            if (!isNaN(y)) {
                                table += "<td>" + data.fail[y].lead_id + "</td>";
                                table += "<td>" + data.fail[y].creation_date + "</td>";
                                table += "<td>" + data.fail[y].name + "</td>";
                                table += "<td>" + data.fail[y].state + "</td>";
                                table += "<td>" + data.fail[y].pin_code + "</td>";
                                table += "<td>" + data.fail[y].lead_source + "</td>";
                                table += "<td>" + data.fail[y].phone + "</td>";
                                table += "<td>" + data.fail[y].alternate_no +"</td>";
                                table += "<td style='color :red;'> Failed</td>";
                                table += "<td>" + data.fail[y].description + "</td>";
                            }
                        table += "</tr>";
                        }
                        table += "</tbody>";
                        table += "</table>";
                        table += "</div>";
                        table += "</div>";
                        jQuery("#dvTable").html(table);
                    }
                    else{
                    alert(data.message);
                    }
                    }

                });
            });
        });
        function goToPage(page = 1) {
            jQuery('#reportform').attr('action', '/leads/format/?page=' + page);
            jQuery('#reportform').submit();

        }
        
         jQuery("#upload_file").change(function () {
        var fileExtension = ['xls'];
        if (jQuery.inArray(jQuery(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("Only formats are allowed : "+fileExtension.join(', '));
            location.reload();
        }
    });
    </script>
    <script>jQuery.noConflict();</script>
