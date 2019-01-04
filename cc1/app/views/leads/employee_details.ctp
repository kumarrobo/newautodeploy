<link href="/boot/css/font-awesome.min.css" rel="stylesheet">
<script src="/boot/js/jquery-2.0.3.min.js"></script>
<script src="/boot/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="/css/bootstrap.min.css">

<script src = "/boot/js/jquery-ui.js"></script>
<link href = "/boot/css/jquery-ui.css" rel = "stylesheet">
<script src="/boot/js/moment.min.js"></script>
<script src="/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
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
                <a href="/leads/assignLead" class="list-group-item"><i class="fa fa-tasks"></i> <span>Assign Lead</span></a>
                <a href="/leads/leadList" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Lead List</span></a>
                <a href="/leads/format" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Lead Upload</span></a>
                <a href="/leads/employeeDetails" class="list-group-item active"><i class="fa fa-list-alt"></i> <span>Employee Leads</span></a>
                <a href="/leads/report" class="list-group-item "><i class="fa fa-credit-card"></i> <span>Report</span></a>
            </div>
        </div>
        <div class='col-sm-10' >
            <?php $messages = $this->Session->flash(); ?>
            <?php if (!empty($messages) && preg_match('/Error/', $messages)): ?>
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p><?php echo $messages; ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($messages) && preg_match('/Success/', $messages)): ?>
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p><?php echo $messages; ?></p>
                </div>
            <?php endif; ?>
            <form class="form-inline" id="reportform" name="reportform" method="POST" action="/leads/employeeDetails">
                <input type="hidden" name='download' id ='download' value="">
                <div class="form-group">
                    <label > From </label>
                    <span class='input-group datetime date' style="width:200px;" >
                        <input type='text' class="form-control" id="fromdate" name="fromdate" value="<?php echo isset($from_date) ? $from_date : ''; ?>"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </span>
                </div>
                <div class="form-group">
                    <label  style="padding-left:10px;">To</label>
                    <span class='input-group datetime date' style="width:200px;" >
                        <input type='text' class="form-control" id="todate" name="todate" value="<?php echo isset($to_date) ? $to_date : ''; ?>"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </span>
                </div>
                <?php if (!empty($access)) { ?>
                <div class="form-group">
                    <label class="btn-label" style="padding-left:10px;" >Employee Name </label>
                    <select  class="form-control" name="emp_id" id="emp_id" style="width:200px;">
                        <option value="">ALL </option>
                        <?php foreach ($emp_name as $employee_name) { ?>
                            <option value='<?php echo $employee_name['users']['id']; ?>'
                            <?php
                            if ($name == $employee_name['users']['id']) {
                                echo "selected";
                            }
                            ?>>
                                <?php echo $employee_name['users']['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php } ?>
                <div class="form-group">
                    <span  style="padding-left:10px;"> <button onclick="exportdata('submit');" class="btn btn-primary btn-md">Submit</button> </span>
                    <button value="" onclick="exportdata('download');" ><i class="fa fa-file-excel-o fa-2x" style="color:green"></i></button>
                </div>
            </form>

            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">Employee Details</h3>
                </div>
                <div class="">

                    <table class="table" id="listGroup">
                        <thead>
                            <tr class="filters">

                                <th>Id</th>
                                <th>Lead date</th>
                                <th>Assigned Date</th>
                                <th>Last Comment</th>
                                <th>Name</th>
                                <th>State</th>
                                <th>Mobile No</th>
                                <th>Lead Source</th>
                                <th>Lead Status</th>
                                <th>Assign</th>
                                <th>Tagging</th>
                                <th>Sub Tagging</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            foreach ($lead as $leads) {
                                echo '<form class="form-inline"  method="POST" action="/leads/assign_lead_data">';
                                echo '<tr>';
                                echo '<td>' . $leads['lead_data']['id'] . '</td>';
                                echo '<td>' . $leads['lead_data']['creation_date'] . '</td>';
                                echo '<td>' . $leads['lead_data']['assigned_datetime'] . '</td>';
                                echo '<td>' . $leads['lead_data']['comment'] . '</td>';
                                echo '<td>' . $leads['lead_data']['shop_name'] . '</td>';
                                echo '<td>' . $leads['lead_data']['state_name'] . '</td>';
                                echo '<td>' . $leads['lead_data']['mobile'] . '</td>';
                                echo '<td>' . $leads['lead_data']['lead_source'] . '</td>';
                                echo '<td>' . $leads['lead_data']['lead_state'] . '</td>';
                                echo '<td>' . $leads['lead_data']['assign_name'] . '</td>';
                                echo '<td>' . $leads['lead_data']['tag'] . ' </td>';
                                echo '<td>' . $leads['lead_data']['sub_tag'] . ' </td>';

                                echo '</tr>';
                                echo '</form>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="row ">
                    <div class="col-md-5 text-right" style="margin-left: 460px;">
                        <?php echo $this->element('pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function goToPage(page = 1) {
        jQuery("#download").val('');
        jQuery('#reportform').attr('action', '/leads/employeeDetails/?page=' + page);
        jQuery('#reportform').submit();
    }

    jQuery(document).ready(function ($) {
        $('.list-group-item').click(function (e) {
//            e.preventDefault();
            $('.list-group-item').removeClass('active');
            $(this).addClass('active');
        });

        $('.datetime').datetimepicker({
            defaultDate: new Date(),
            format: 'YYYY-MM-DD HH:mm',
            maxDate: new Date()
        });

    });
    function exportdata(value) {
        jQuery("#download").val(value);
        jQuery("#reportform").submit();
    }
</script>
<script>jQuery.noConflict();</script>
