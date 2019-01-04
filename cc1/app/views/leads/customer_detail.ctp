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
<style>
    .text-on-pannel {
        background: #fff none repeat scroll 0 0;
        height: auto;
        margin-left: 20px;
        padding: 3px 5px;
        position: absolute;
        margin-top: -47px;
        border: 1px solid #337ab7;
        border-radius: 8px;
    }

    .panel {
        /* for text on pannel */
        margin-top: 27px !important;
    }
    .panel-body {
        padding-top: 30px !important;
    }
    
</style>
<div class="container">
    <div class="row">
        <div id="successvalidation"> </div>
        <div id="errorvalidation"> </div>
        <div class="alert alert-success" id="successmsg" style="display:none;">
            <p>Details Updated successfully</p>
        </div>
        <div class="alert alert-danger" id="dangermsg" style="display:none;">
            <p>Details unable to update</p>
        </div>
        <div class="col-md-6">
            <form class="form-inline" id="reportform" name="reportform" method="POST" action="/leads/index">
                <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $id; ?>">
                <table class="table">
                    <thead>
                        <tr>
                            <th><h3>Customer Details</h3></th>
                            <th>
                                <button type="button" class="btn btn-primary btn-md" id="edit_detail">Edit</button>
                                <button type="button" class="btn btn-primary btn-md" id="save_detail">Save</button>
                                <button type="button" class="btn btn-primary btn-md" id="cancel_detail">Cancel</button>
                                <span id="editresponse"></span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><label>Lead Date</label></td>
                            <td><div class="form-group">
                                    <input type='text' class="form-control" style="width : 235px;" disabled="true" id="leaddate" name="leaddate" value="<?php echo $leaddetail['creation_date']; ?>" />

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Assign Date</label></td>
                            <td><div class="form-group">
                                   <input type='text' class="form-control" style="width : 235px;" disabled="true" name="assigndate" id="assigndate" value="<?php echo $leaddetail['assigned_datetime']; ?>"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Name</label></td>
                            <td><div class="form-group">
                                    <input type="hidden" name="id" id="id" style="width: 235px;" value="<?php echo $leaddetail['id']; ?>">
                                    <input type="text" name="name" id="name" required="true" disabled="true" style="width: 235px;" class="form-control input-sm" placeholder="Name" value="<?php echo $leaddetail['name']; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Mobile</label></td>
                            <td><div class="form-group">
                                    <input type="text" name="phone" id="phone" disabled="true" style="width: 235px;" class="form-control input-sm" placeholder="Mobile Number" value="<?php echo $leaddetail['phone']; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Alternate Mobile No</label></td>
                            <td><div class="form-group">
                                    <input type="text" name="alt_mob_no" id="alt_mob_no" min="10" disabled="true" style="width: 235px;" class="form-control input-sm" placeholder="Alternate Mobile Number" value="<?php echo $leaddetail['alternate_no']; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Email</label></td>
                            <td><div class="form-group">
                                    <input type="email" name="email" id="email" disabled="true" style="width: 235px;" class="form-control input-sm" placeholder="Email" value="<?php echo $leaddetail['email']; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Shopname</label></td>
                            <td><div class="form-group">
                                    <input type="text" name="shop_name" id="shop_name" disabled="true" style="width: 235px;" class="form-control input-sm" required="true" placeholder="Shop Name" value="<?php echo $leaddetail['shop_name']; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Current Business</label></td>
                            <td><div class="form-group">
                                    <input type="text" name="current_business" id="current_business" disabled="true" style="width: 235px;" class="form-control input-sm" placeholder="Current Business" value="<?php echo $leaddetail['current_business']; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Daily Sale</label></td>
                            <td><div class="form-group">
                                    <input type="text" name="daily_sale" id="daily_sale" disabled="true" style="width: 235px;" class="form-control input-sm" placeholder="Daily Sales" value="<?php echo $daily_sale; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Number Of Retailer</label></td>
                            <td><div class="form-group">
                                    <input type="text" name="retailersno" id="retailersno" disabled="true" style="width: 235px;" class="form-control input-sm" placeholder="Number Of Retailer" value="<?php echo $retailer_count; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>City</label></td>
                            <td><div class="form-group">
                                    <input type="text" name="city" id="city" disabled="true" style="width: 235px;" class="form-control input-sm" placeholder="City" value="<?php echo $city; ?>">
                                </div>
                            </td>
                        </tr>
<!--                        <tr>
                            <td><label>Existing Retailer</label></td>
                            <td><div class="form-group">
                                    <input type="text" name="lead_state" id="lead_state" disabled="true" style="width: 235px;" class="form-control input-sm" placeholder="Existing Retailer" value="<?php // echo $retailer_count == '' ? 'NO' : 'YES'; ?>">
                                </div>
                            </td>
                        </tr>-->
                    </tbody>
                </table>
            </form>
        </div>

        <div class="col-md-6">

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Commented List</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="commenttable">
                        <thead>
                            <tr class="filters">
                                <th>Commented By</th>
                                <th>Created Date</th>
                                <th>SubTag</th>
                            </tr>
                        </thead>
                        <tbody id="comments">
                            <?php
                            foreach ($comments as $comment) {
                                echo '<tr>';
                                echo '<td>' . $comment['users']['commented_user'] . '</td>';
                                echo '<td>' . $comment['comments_new']['created_at'] . '</td>';
                                echo '<td>' . $comment['taggings_new']['tag'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-body">
                    <h3 class="text-on-pannel text-primary"><strong class="text-lcase"> Comment </strong></h3>
                    <div id="errormsg" class="row"> </div>
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2"><label>Main Tag</label></div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <select name="maintag" id="maintag" class="form-control input-sm">
                                    <option value="0">---Select Maintags---</option>
                                    <?php
                                    foreach ($tagging as $tag) {
                                        echo "<option value='" . $tag['taggings_new']['id'] . "'>" . $tag['taggings_new']['name'] . " </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2"><label>Sub Tag</label></div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <select name="subtag" id="subtag" class="form-control input-sm">
                                    <option value="0">---Select Subtags---</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2"><label>Status Change</label></div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <select name="statuschange" id="statuschange" class="form-control input-sm">
                                    <option value="0">---Select Status---</option>
                                    <?php
                                    foreach ($status as $statuschange) {
                                        echo '<option value="' . $statuschange['lead_attributes_values']['id'] . '">' . $statuschange['lead_attributes_values']['lead_values'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2"><label>Followup Date</label></div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <span class='input-group date' id="datetimepicker3">
                                <input type='text' class="form-control" name="followupdate" id="followupdate"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </span>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2"><label>Comment</label></div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <textarea class="form-control input-sm" rows="5" id="comment" id="comment"></textarea>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3"><button class="btn btn-primary" id="updatecomment" name="updatecomment">Submit</button><span id="commentresponse"></span></div>
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Distributor List</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr class="filters">
                            <th>Distributor info</th>
                            <th>Distributor Name</th>
                            <th>Avg Sale of 30 days</th>
                            <th>No of Retailers</th>
                            <th>Pincode Sale</th>
                            <th>Pincode No of retailers</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($distributor as $dist) {
                            echo '<tr>';
                            echo '<td>' . $dist['dist_details']['id'] . '</td>';
                            echo '<td>' . $dist['dist_details']['company'] . '</td>';
                            echo '<td>' . $dist[0]['average'] . '</td>';
                            echo '<td>' . $dist['dist_details']['retailers'] . '</td>';
                            echo '<td>' . $dist_details[$dist['dist_details']['id']]['pincode_sale'] . '</td>';
                            echo '<td>' . $dist_details[$dist['dist_details']['id']]['retailers'] . '</td>';
                            echo '<td>' . ($dist['dist_details']['active_flag'] == 1 ? 'Active' : 'Inactive') . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-7"></div>
                <div class="col-md-5 text-right">
                    <?php echo $this->element('pagination'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {
        $('#successmsg,#dangermsg').hide();
        $('#edit_detail').show();
        $('#save_detail').hide();
        $('#cancel_detail').hide();
        
        $('#alt_mob_no').focusout(function(){
            var val = $('#alt_mob_no').val();
            if(val != ''){
                if (!(/^\d{10}$/.test(val))) {
                    alert("Invalid number; must be ten digits");
                }
                return false;
            }
        });
        $('#alt_mob_no').focus();
        $('#edit_detail').click(function () {
            $('#name,#alt_mob_no,#email,#shop_name,#current_business').prop('disabled', false);
            $('#edit_detail').hide();
            $('#save_detail').show();
            $('#cancel_detail').show();
        });
        $('#save_detail').click(function () {
            $('#editresponse').html("<img src='/img/ajax-loader-2.gif' />");
            var data = {
                'id': $('#id').val(),
                'name': $('#name').val(),
                'phone': $('#phone').val(),
                'alt_mob_no': $('#alt_mob_no').val(),
                'email': $('#email').val(),
                'shop_name': $('#shop_name').val(),
                'current_business': $('#current_business').val(),
                'city': $('#city').val()
            };

            $.ajax({
                type: 'POST',
                url: '/leads/leadDetail',
                dataType: "json",
                data: {edit: '1', data: data},
                success: function (response) {
                    if (response["status"] == 'success') {
                        $('#editresponse').html('');
                        $('#successvalidation').html('').fadeIn().delay(5000).fadeOut();
                        $('#successvalidation').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" >&times;</a><p>'+response['description']+'</p></div>').fadeOut(5500);
                        $('#name,#alt_mob_no,#email,#shop_name,#current_business').prop('disabled', true);
                        $('#edit_detail').show();
                        $('#save_detail').hide();
                        $('#cancel_detail').hide();
                    }
                    if(response['status'] == 'failed'){
                        $('#editresponse').html('');
                        $('#errorvalidation').html('').fadeIn().delay(5000).fadeOut();
                        $('#errorvalidation').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" >&times;</a><p>'+response['description']+'</p></div>').fadeOut(5500);
                        return false;
                    }
                }
            });
        });

        $('#cancel_detail').click(function () {
            $('#edit_detail').show();
            $('#save_detail').hide();
            $('#cancel_detail').hide();
            $('#name,#alt_mob_no,#email,#shop_name,#current_business,#city').prop('disabled', true);
        });

        $('#updatecomment').click(function () {
            $('#commentresponse').html("<img src='/img/ajax-loader-2.gif' />");
            var commentdata = {
                'leadid': $('#lead_id').val(),
                'maintag': $('#maintag').val(),
                'subtag': $('#subtag').val(),
                'statuschange': $('#statuschange').val(),
                'comment': $('#comment').val(),
                'followupdate': $('#followupdate').val(),
            };
            
            $.ajax({
                type: 'POST',
                url: '/leads/leadDetail',
                dataType: 'json',
                data: {updatecomment: '1', commentdata: commentdata},
                success: function (response) {
                    $('#errormsg').html('').fadeIn().delay(3000).fadeOut();
                    if(response['status'] == 'failure' ){ 
                        $('#commentresponse').html("");
                        $('#errormsg').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" >&times;</a><p>'+response['description']+'</p></div>').fadeOut(5500);
                    }
                    if(response['status'] == 'success'){
                            $('#commentresponse').html("");
                            var table = '';
                            $.each(response.description, function (k,v) {
                                table += '<tr>';
                                table += '<td>' + (v.users.commented_user == null ? '' : v.users.commented_user) + '</td>';
                                table += '<td>' + v.comments_new.created_at + '</td>';
                                table += '<td>' + v.taggings_new.tag + '</td>';
                                table += '</tr>';
                            });
                            $('#comments').show();
                            $('#comments').html(table);
                            $('#successmsg').show().fadeOut(1500);
                            $('#maintag').val('0');
                            $('#subtag').val('0');
                            $('#statuschange').val('0');
                            $('#comment').val('');
                            $('#followupdate').val('<?php echo date('Y-m-d H:i:s'); ?>');
                        }
                    }   
            });
        });

        $('#maintag').change(function () {
            var maintag = $(this).val();
            $.ajax({
                type: 'POST',
                url: '/leads/leadDetail',
                dataType: 'json',
                data: {maintagflag: '1', maintagdata: maintag},
                success: function (data) {
                    $.each(data, function (i, item) {
                        $('#subtag').append("<option value=" + item.id + ">" + item.name + "</option>");
                    });
                }
            });
        });

        $('#datetimepicker1,#datetimepicker2').datetimepicker({
            format: "YYYY-MM-DD HH:mm:ss",
            toolbarPlacement: 'top',
            showClear: true,
            showClose: true,
            sideBySide: true
        });
        $('#datetimepicker3').datetimepicker({
            defaultDate: new Date(),
            format: 'YYYY-MM-DD HH:mm:ss',
            minDate: new Date()
        });
        $('#commenttable').dataTable({
//        "order": [[0, "desc" ]],
            "searching": false,
            "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0 ] }],
            "pageLength":10,
            "lengthMenu": [10, 20, 25],
        });
    });
</script>
<script>jQuery.noConflict();</script>
