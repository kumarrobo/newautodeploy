<link href="/boot/css/font-awesome.min.css" rel="stylesheet">
<script src="/boot/js/jquery-2.0.3.min.js"></script>
<script src="/boot/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
<script src = "/boot/js/jquery-ui.js"></script>
<link href = "/boot/css/jquery-ui.css" rel = "stylesheet">
<script src="/boot/js/moment.min.js"></script>
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>

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
            <a href="/leads/assignLead" class="list-group-item active"><i class="fa fa-tasks"></i> <span>Assign Lead</span></a>
            <a href="/leads/leadList" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Lead List</span></a>
            <a href="/leads/format" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Lead Upload</span></a>
            <a href="/leads/employeeDetails" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Employee Leads</span></a>
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
            <form class="form-inline" id="reportform" name="reportform" method="POST" action="/leads/assignLead">
                <div>

                    <div style="float:left;"><span style="font-weight:bold;">From Date : </span></div>
                    <div style="float:left;"><input type="text" class="form-control" style="width: 200px; margin-top: -5px; margin-left: 15px; display: block;" id="from_date" name="from_date" value="<?php echo isset($from_date) ? $from_date : date('Y-m-d'); ?>"></div>
                    <div style="float:left;"><span style="font-weight:bold; padding-left: 30px;">To Date : </span></div>
                    <div style="float:left;"><input type="text" class="form-control" style="width: 200px; margin-top: -5px; margin-left: 15px;" id="to_date" name="to_date" value="<?php echo isset($to_date) ? $to_date : date('Y-m-d'); ?>"></div>

                </div>
                <div style="padding-top: 40px;">
                    <label class="btn-label" >State :</label>
                    <select  class="form-control" name="stateid" id="stateid" style="width:200px;">
                        <option value="">All</option>
                        <?php foreach ($state as $states) { ?>
                            <option value='<?php echo $states['ls']['id']; ?>'<?php
                            if ($stateid == $states['ls']['id']) {
                                echo "selected";
                            }
                            ?>><?php echo $states['ls']['name']; ?></option>
                                <?php } ?>
                    </select>

                    <label class="btn-label"  style='padding-left :30px;'>Lead Source :</label>
                    <select  class="form-control" name="sourceid" id="sourceid" style="width:200px;">
                        <option value="">All</option>
                        <?php foreach ($source as $sources) { ?>
                            <option value='<?php echo $sources['lead_attributes_values']['id']; ?>'<?php
                            if ($sourceid == $sources['lead_attributes_values']['id']) {
                                echo "selected";
                            }
                            ?>><?php echo $sources['lead_attributes_values']['lead_values']; ?></option>
                                <?php } ?>
                    </select>

                    <label class="btn-label"  style='padding-left :30px;'>Market:</label>
                    <select  class="form-control" name="marketid" id="marketid" style="width:200px;">
                        <option value="0" <?php
                        if ($marketid == 0) {
                            echo "selected";
                        }
                        ?>>All</option>
                        <option value="1" <?php
                        if ($marketid == 1) {
                            echo "selected";
                        }
                        ?>>Available</option>
                        <option value="2" <?php
                        if ($marketid == 2) {
                            echo "selected";
                        }
                        ?>>Not Available</option>
                                <?php ?>
                    </select>

                    <button class="btn btn-primary btn-md" type="submit" id="filterdata">Submit</button>
                    <span id="response"></span>
                </div>
                <div id="dvTable">
                </div>
            </form>


            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">Lead Assign</h3>
                </div>
                <div class="">
                    <table class="table" id="listGroup">
                        <thead>
                            <tr class="filters" >
                                <th><input type="checkbox" id = "chckHead"  /></th>
                                <th>ID</th>
                                <th>Lead date</th>
                                <th>Name</th>
                                <th>State</th>
                                <th>Pincode</th>
                                <th>Leads Source</th>
                                <th>Area Details</th>
                                <th>Assign To</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            $i = 0;
                            foreach ($leads as $lead) {

                                echo '<tr id="tablerow' . $lead['ln']['id'] . '">';
                                echo '<td><input type="checkbox" class = "chcktbl"  value="' . $lead['ln']['id'] . '"/></td>';
                                echo '<td>' . $lead['ln']['id'] . '</td>';
                                echo '<td>' . $lead['ln']['creation_date'] . '</td>';
                                echo '<td id = "name_' . $lead['ln']['id'] . '">' . $lead['ln']['shop_name'] . '</td>';
                                echo '<td>' . $lead['ls']['name'] . '</td>';
                                if($lead['ln']['pin_code'] == 0){
                                    $pincode = '';
                                }else{
                                    $pincode = $lead['ln']['pin_code'];
                                }
                                echo '<td>' . $pincode . '</td>';
                                
                                echo '<td>' . $lead['lav']['lead_values'] . '</td>';
                                if($pincode_dist_count[$lead['ln']['pin_code']]==''){
                                    $dist=0;
                                }else{
                                    $dist=$pincode_dist_count[$lead['ln']['pin_code']];
                                }
                                if($pincode_ret_count[$lead['ln']['pin_code']]==''){
                                    $ret=0;
                                }else{
                                    $ret=$pincode_ret_count[$lead['ln']['pin_code']];
                                }
                                echo '<td>' . $dist. ' / '. $ret.  '</td>';
                                ?>
                            <td> <select  class="form-control" name="assign_to" id="assign_to<?php echo $lead['ln']['id']; ?>" style="width:150px;">
                                    <option value="">SELECT USER</option>
                                    <?php foreach ($assign as $assign_new) { ?>
                                        <option value='<?php echo $assign_new['users']['id']; ?>'><?php echo $assign_new['users']['name']; ?></option>
                                    <?php } ?>   

                                </select></td>
                            <?php
                            echo '<input type="hidden"  name="lead_id" value="' . $lead['ln']['id'] . '"/>';
                            echo '<td><button class="btn btn-primary submit_lead"  type="button" onclick="submit_lead (' . $lead['ln']['id'] . ')">Assign</button></td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                    
                </div>
                <div class="row">
                    <div class="col-md-7"style="padding-top: 45px;" >
                        <div style="float:left;"><input type="hidden" class="form-control" style="width: 200px; margin-top: -5px; margin-left: 15px;" id="lead_id" name="lead_id"></div>
                        <label style="margin-left: 80px;"> Assign To</label>
                        <select  class="form-control" name="assign_all" id="assign_all" style="width:200px; margin-left: 150px;margin-top: -29px;">
                            <option value="">SELECT USER</option>
                            <?php foreach ($assign as $assign_new) { ?>
                                <option value='<?php echo $assign_new['users']['id']; ?>'><?php echo $assign_new['users']['name']; ?></option>
                            <?php } ?>   
                        </select>
                        <button class="btn btn-primary btn-md" id="assign_confirm" type="button" style="width:100px; margin-left: 380px;margin-top: -35px;" >Submit</button>
                    </div>
                    <div class="col-md-5 text-right" >
                        <?php echo $this->element('pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    jQuery('#from_date, #to_date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "1d",
        multidate: false
    });



    function goToPage(page = 1) {
        jQuery('#reportform').attr('action', '/leads/assignLead/?page=' + page);
        jQuery('#reportform').submit();
    }

    function submit_lead(lead_id) {


        var assign_to = encodeURI(document.getElementById('assign_to' + lead_id).value);

        if (assign_to == '') {
            alert('Please Select user');
            return false;
        }
        jQuery.ajax({
            type: 'POST',
            url: '/leads/assignLeadData/',
            dataType: 'json',
            data: {"lead_id": lead_id, "assign_to": assign_to},
            success: function (data) {
                    jQuery('#tablerow' + lead_id).html('');
            }

        });
    }

    function submit_data() {
        var lead_id = jQuery('#modal_lead_id').val();
        var assign_id = jQuery('#modal_assign_id').val();
        jQuery.ajax({
            type: 'POST',
            url: '/leads/assignLeadData/',
            dataType: 'json',
            data: {"lead_id": lead_id, "assign_to": assign_id},
            success: function (data) {
                jQuery('#myModal').modal('hide');
                var lead_data = lead_id.split(',');
                for (var x in lead_data) {
                    jQuery('#tablerow' + lead_data[x]).html('');
                }
            }
        });
    }
    
    jQuery(document).ready(function ($) {
//        jQuery('.list-group-item').click(function(e) {
////            e.preventDefault();
//            jQuery('.list-group-item').removeClass('active');
//            jQuery(this).addClass('active');
//         });
        
        $("#assign_confirm").click(function () {
           
            
            var assign = jQuery('#assign_all').val();
            if(assign == ''){
                alert('Please Select User');
                return false;
            }
            
             var selected_ids = [];
            for(var i = 0; i < $('.chcktbl').length; i++){
                if (jQuery(jQuery('.chcktbl')[i]).prop('checked') == true) {
                    selected_ids.push(jQuery(jQuery('.chcktbl')[i]).val());
                }
            }
           
           if(selected_ids == ''){
                alert('Select atleast one checkbox');
                return false;
            }
            
            
            
            var table = "<div class='panel panel-primary filterable'>";
            table += "<div class='panel-heading'>";
            table += "<h3 class='panel-title'>Lead Format</h3>";
            table += "</div>";
            table += "<div class=''>";
            table += "<input type='hidden' id='modal_lead_id' name='modal_lead_id' value='"+selected_ids.join(',')+"'>";
            table += "<input type='hidden' id='modal_assign_id' name='modal_assign_id' value='"+assign+"' >";
            table += "<table class='table table-responsive'>";
            table += "<thead>";
            table += "<tr class='filters'>";
            table += "<th>ID</th>";
            table += "<th>Name</th>";
            table += "</tr>";
            table += "</thead>";
            table += "<tbody id='myTable'>";
                for (var x in selected_ids) {
                    table += "<tr>";
                    if (!isNaN(x)) {
                        table += "<td>" + selected_ids[x] + "</td>";
                        table += "<td>" + jQuery('#name_'+selected_ids[x]).html() + "</td>";
                    }
                    table += "</tr>";
                }
            table += "</table>";
            table += "</div>";
            table += "</div>";
            $("#modal_table").html(table);
            $('#myModal').modal('show');
        });
        
        $('#chckHead').click(function () {
            if (this.checked == false) {
                $('.chcktbl:checked').prop('checked', false);
            } else {
                $('.chcktbl:not(:checked)').prop('checked', true);
            }
        });
    });
</script>
<script>jQuery.noConflict();</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirm Lead</h4>
            </div>
            <div class="modal-body">

                <div id="modal_table">
                </div>
                <button class="btn btn-primary btn-md " type="button" style="width:100px; margin-left:100px;margin-top: -10px;" onclick="submit_data()">Submit</button>
                <button class="btn btn-primary btn-md" type="button" data-dismiss="modal" aria-hidden="true"  style="width:100px; margin-left: 380px;margin-top: -35px;" >Cancel</button>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
