<?php if($page!='download') { ?>
<link type='text/css' rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    thead{
        background-color: #428bca;
        color: #fff;
    }

    </style>
<div>

    <div class="incentive-report-container">
        <div><h3>Recovery Panel</h3></div>
        <div class="panel panel-default">
            <div class="panel-heading">Filter</div>
            <div class="panel-body">

                <form method="post" action="/panels/getRecoveryData" id="recoveryform">
        <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="txn_date">Date</label>
                            <div class="" id="sandbox-container">
                              <div class="input-daterange input-group" id="datepicker">
                                  <input type="text" class="form-control" name="from_date"  id="from_date" value="<?php echo $params['from_date']?$params['from_date']:date('Y-m-d'); ?>" />
                                  <span class="input-group-addon">to</span>
                                  <input type="text" class="form-control" name="to_date"  id="to_date"  value="<?php echo $params['to_date']?$params['to_date']:date('Y-m-d'); ?>"  />
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="service_id">Services</label>
                            <div class="">
                                <select class="form-control" id="service_id" name="service_id">
                                    <option value="">All</option>
                                    <?php foreach ($services as $id=>$name){ ?>
                                    <option value="<?php echo $id?>" <?php if($params['service_id'] == $id){ echo "selected"; }?>><?php echo $name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="status">Status</label>
                            <div class="">
                                <select class="form-control" id="status" name="status">
                                    <option value="">All</option>
                                    <?php foreach ($status_list as $id => $status) { ?>
                                    <option value="<?php echo $id?>" <?php if($params['status'] == $id){ echo "selected"; }?>><?php echo $status;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="user_id">User Id</label>
                            <div class="">
                                <input type="text" class="form-control" name="user_id" id="user_id" value="<?php echo $params['user_id'];?>"/>
                            </div>
                        </div>
                    </div>
            </div>
                    <div class="row">
                        <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="cause">Causes</label>
                            <div class="">
                                <select class="form-control" id="cause" name="cause" onchange="getSubcauses(this.value)">
                                    <option value="">All</option>
                                    <?php foreach ($causes as $id=>$name){ ?>
                                    <option value="<?php echo $id?>" <?php if($params['cause'] == $id){ echo "selected"; }?>><?php echo $name;?></option>
                                    <?php  }?>
                                </select>
                            </div>
                        </div>
                    </div>
                        <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="subcause">Subcauses</label>
                            <div id="subcause">
                                <select class="form-control" id="subcause" name="subcause">
                                    <option value="">All</option>
                                    <?php if(!empty($params['cause'])){?>
                                    <?php foreach ($recovery_causes[$params['cause']]['subcauses'] as $id=>$name){ ?>
                                    <option value="<?php echo $id?>" <?php if($params['subcause'] == $id){ echo "selected"; }?>><?php echo $name;?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-xs btn-primary" style="margin-top:0.75cm" onclick="searchTxn()">Search</button>
                        <button type="button" class="btn btn-xs btn-success" style="margin-top:0.75cm" onclick="" id="btndownload">Download</button>
                    </div>
                </div>
                    </div>
            <input type="hidden" name='download' id ='download' value="">

            </form>
        </div>
    </div>

        <?php if( count($recovery_data) > 0 ){ ?>
            <table class="table table-striped table-responsive table-bordered table-fixed" id="tblrecoverydata">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Title</th>
                        <th>User Id</th>
                        <th>Retailer Name/Mobile</th>
                        <th>Dist Id/Dist Name/Mobile</th>
                        <th>Rm Name/Mobile</th>
                        <th>Device/Loan Number</th>
                        <th>Rental/EMI Amt</th>
                        <th>Last Active Date</th>
                        <th>Last Settlement Mode</th>
                        <th>Current Balance</th>
                        <th>Last Paid Date</th>
                        <th>Due Date</th>
                        <th>Default Date</th>
                        <th>Due Count</th>
                        <th>Rental Due/EMI Due</th>
                        <th>Setup Cost</th>
                        <th>Refund/Interest Amt</th>
                        <th>Recovered Amt</th>
                        <th>Status</th>
                        <th>Recovery Date</th>
                        <th>Cause</th>
                        <th>Sub-Cause</th>
                        <th>Comment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                foreach ($recovery_data as $user_id => $service_data) {
                    foreach ($service_data as $service_id => $status_data) {
                        foreach($status_data as $status_id => $rec_data ) {
                            foreach($rec_data as $date=>$data){
                $i++;
                ?>
                    <tr id="<?php echo $i;?>">
                        <td><?php echo $i; ?></td>
                        <td><?php echo $services[$data['service_id']];?></td>
                        <td><?php echo "<a href='javascript:void(0)' onclick='getUserProfile(".$user_id.",".json_encode($services).")'>".$user_id."</a>";?></td>
                        <td><?php echo $data['ret_name'].'/'.$data['ret_mob'];?></td>
                        <td><?php echo $data['dist_id'].'/'.$data['dist_name'].'/'.$data['dist_mob'];?></td>
                        <td><?php echo $data['rm_name'].'/'.$data['rm_mob'];?></td>
                        <td><?php echo $data['param1'];?></td>
                        <td><?php echo $data['amount'];?></td>
                        <td><?php echo $data['last_active_date'];?></td>
                        <td><?php echo $data['settlement_flag'];?></td>
                        <td><?php echo $data['balance'];?></td>
                        <td><?php echo $data['last_paid_date'];?></td>
                        <td><?php echo $data['due_date'];?></td>
                        <td><?php echo $data['default_date'];?></td>
                        <td><?php echo isset($data['due_count'])?$data['due_count']:'';?></td>
                        <td><?php echo $data['due_amt'];?></td>
                        <td><?php echo $data['setup_cost'];?></td>
                        <td><?php echo $data['refund_amt'];?></td>
                        <td><?php echo $data['recovered_amt'];?></td>
                        <td><?php echo $status_list[$data['status']];?></td>
                        <td><?php echo $data['recovered_at'];?></td>
                        <td>
                            <?php if(in_array($data['status'],array(3,4))) { echo $recovery_causes[$data['cause']]['label'];}
                                    else {?>
                            <select name="cause_<?php echo $i;?>" id="cause_<?php echo $i;?>" onchange="listSubcause(<?php echo $i;?>,this.value)">
                                    <option>Select Cause</option>
                                    <?php foreach ($recovery_causes as $id => $cause){   ?>

                                        <option value="<?php echo $id;?>" <?php if($data['cause'] == $id){ echo "selected"; }?>><?php echo $cause['label'];?></option>

                                    <?php }?>
                            </select>
                            <?php }?>
                        </td>
                        <td id="sub_cause_<?php echo $i;?>">
                            <?php if(in_array($data['status'],array(3,4))) { echo $recovery_causes[$data['cause']]['subcauses'][$data['subcause']];}
                             else {?>
                            <select name="subcause_<?php echo $i;?>" id="subcause_<?php echo $i;?>">
                                    <?php foreach ($recovery_causes[$data['cause']]['subcauses'] as $id => $subcause){   ?>

                                        <option value="<?php echo $id;?>" <?php if($data['subcause'] == $id){ echo "selected"; }?>><?php echo $subcause;?></option>

                                    <?php }?>
                            </select>
                            <?php }?>
                        </td>
                        <td><?php if(!in_array($data['status'],array(3,4))){ ?><textarea id="comment_<?php echo $i;?>" name="comment_<?php echo $i;?>"></textarea><?php } ?>

                            <?php if(!empty($data['msg_count'])) { ?>
                            <a class="fa fa-comments-o" style="font-size:20px;cursor:pointer" onclick="showComments(<?php echo $data['ref_id']?>)" data-refid="<?php echo $data['ref_id']?>"></a>&nbsp;(<?php echo !empty($data['msg_count'])?$data['msg_count']:0; ?>)<?php } ?>
                        </td>
                        <td><?php if(!in_array($data['status'],array(3,4)) || $data['service_id'] == 11){?><div id='save_button_<?php echo $i; ?>' ><button id='savecomment' class='btn btn-xs btn-primary' onclick='recoverAmount(<?php echo $i; ?>,<?php echo json_encode($data,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);?>,1)'>Save</button></div><br/><?php }
                        if($data['status'] == 2){?>
                            <div id='reactivate_button_<?php echo $i; ?>'>
                                <button id='deductamt' class='btn btn-xs btn-success' onclick='reactivate(<?php echo $i; ?>,<?php echo json_encode($data,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);?>,2)'>Reactivate</button>
                            </div><br>
                            <div id='refund_button_<?php echo $i; ?>'>
                                <button id='refundamt' class='btn btn-xs btn-danger' onclick='refundAmount(<?php echo $i; ?>,<?php echo json_encode($data,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);?>,3)'>Refund</button>
                            </div>
                        </div>  <?php } ?></td>
                    </tr>
                <?php }}}} ?>
                </tbody>
            </table>

        <?php } else {
            echo '<h3>No records Found</h3>';
        }
    ?>
        <div class="modal fade" id="showcomments">
            <div class="modal-dialog" style="">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #428bca;">
                    <h4 class="modal-title">Comments</h4>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12" id="loadcomments">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Ok</button>
                    </div>
            </div>
            </div>
        </div>

        <div class="modal fade" id="reactivationmodal">
            <div class="modal-dialog" style="width:300px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Partial Recovery And Service Reactivation</h4>
                </div>
                <div class="modal-body">
                    <p><b>Due Amount : </b><span id="due_amt"></span></p>
                    <div class="row">
                        <div class="col-lg-12">
                        <label>Amount to be recovered : </label>
                        <input type="text" name="recovered_amt" id="recovered_amt" class="form-control" autocomplete="off"/>
                        </div>
                    </div>
                    <!--<input type="hidden" name="rec_data" id="rec_data" value="">-->
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <input type="hidden" name="service_id" id="service_id" value="">
                    <input type="hidden" name="cause" id="cause" value="">
                    <input type="hidden" name="subcause" id="subcause" value="">
                    <input type="hidden" name="comment" id="comment" value="">
                    <input type="hidden" name="action" id="action" value="">
                </div>
                <div class="modal-footer">
                    <div id="recoveramt_div" style="display: none;float: left"><img src='/img/ajax-loader-2.gif' style='width:10px;height:5px;'></img> <b>loading..</b></div>
                    <button class="btn btn-sm btn-default btn-primary" id="btnrecoveramt">Submit</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        </div>

        <div class="modal fade" id="refundmodal">
            <div class="modal-dialog" style="width:300px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Refund</h4>
                </div>
                <div class="modal-body">
                    <p><b>Amount available to refund : </b><span id="avlrefund_amt"></span></p>
                    <div class="row">
                        <div class="col-lg-12">
                        <label>Enter Amount : </label>
                        <input type="text" name="refund_amt" id="refund_amt" class="form-control" autocomplete="off"/>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <input type="hidden" name="service_id" id="service_id" value="">
                    <input type="hidden" name="device_id" id="device_id" value="">
                    <input type="hidden" name="cause" id="cause" value="">
                    <input type="hidden" name="subcause" id="subcause" value="">
                    <input type="hidden" name="comment" id="comment" value="">
                    <input type="hidden" name="action" id="action" value="">
                    <input type="hidden" name="setup_cost" id="setup_cost" value="">
                </div>
                <div class="modal-footer">
                    <div id="refundamt_div" style="display: none;float: left"><img src='/img/ajax-loader-2.gif' style='width:10px;height:5px;'></img> <b>loading..</b></div>
                    <button class="btn btn-sm btn-default btn-primary" id="btnrefundamt">Submit</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>

        <div class="modal fade" id="userprofile">
            <div class="modal-dialog" style="">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #428bca;">
                    <h4 class="modal-title">User Profile</h4>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12" id="loadcomments">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Ok</button>
                    </div>
            </div>
            </div>
        </div>
    </div>
<br class="clearRight" />
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<!--<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>-->

<script>
    $(document).ready(function()
    {
        $('#sandbox-container .input-daterange').datepicker({
        format: "yyyy-mm-dd",
        startDate: "-365d",
        endDate: "1d",
        todayHighlight: true,
        orientation: "top right"
       });

        $('#tblrecoverydata').dataTable({
//            "order": [[ 2, "desc" ]],
            "bSort" : false,
            "pageLength":1000,
            "lengthMenu": [ 10, 100, 1000 ]
        });

        $('button#btndownload').on('click',function(){
            $("#download").val('download');
            $('#recoveryform').submit();
        });

        $('button#btnrecoveramt').on('click',function(){
            var user_id = $('#user_id').val();
            var service_id = $('#service_id').val();
            var device_id = $('#device_id').val();
            var cause = $('#cause').val();
            var subcause = $('#subcause').val();
            var comment = $('#comment').val();
            var action = $('#action').val();
            var amount = $('#recovered_amt').val().replace(/[^\d\.]/g, '');
            var due_amt = parseInt($('#due_amt').text());

            if(!amount) {
                alert('Enter valid amount');
                return false;
            }

            if(amount > due_amt) {
                alert('Amount should be less than or equal to rental amount');
                return false;
            }

            $.ajax({
                url : "/panels/reactivateService",
                type: "POST",
                data : {user_id:user_id,service_id:service_id,cause:cause,subcause:subcause,comment:comment,action:action,recovered_amt:amount},
                dataType:'json',
                beforeSend: function () {
                            $('#recoveramt_div').show();
                            $("#btnrecoveramt").attr("disabled", true);
                        },
                success:function(res){
                    $('#recoveramt_div').hide();
                    $("#btnrecoveramt").attr("disabled", false);
                    alert(res.description);
                    $('#reactivationmodal').modal('hide');
                    if(res.status == 'success')
                    {
                        window.location.href = "/panels/getRecoveryData";
                    }
                }
            });
        });

        $('button#btnrefundamt').on('click',function(){

            alert('Refund can be done from service activation panel');
            return false;

            var user_id = $('#user_id').val();
            var service_id = $('#service_id').val();
            var device_id = $('#device_id').val();
            var cause = $('#cause').val();
            var subcause = $('#subcause').val();
            var comment = $('#comment').val();
            var action = $('#action').val();
            var amount = $('#refund_amt').val().replace(/[^\d\.]/g, '');
            var avlrefund_amt = parseInt($('#avlrefund_amt').text());
            var setup_cost = parseInt($('#setup_cost').text());

            if(!amount)
            {
                alert('Enter valid amount');
                return false;
            }
            if(amount < 0 || amount > setup_cost)
            {
                alert('Refund amount should be less than or equal to setup cost');
                return false;
            }

            $.ajax({
                url : "/panels/refundAmount",
                type: "POST",
                data : {user_id:user_id,service_id:service_id,device_id:device_id,cause:cause,subcause:subcause,comment:comment,action:action,refund_amt:amount},
                dataType:'json',
                beforeSend: function () {

                              $('#refundamt_div').show();
                              $("#btnrefundamt").attr("disabled", true);
                        },
                success:function(res){
                    $('#refundamt_div').hide();
                    $("#btnrefundamt").attr("disabled", false);
                    alert(res.description);
                    $('#refundmodal').modal('hide');
                    if(res.status == 'success')
                    {
                        window.location.href = "/panels/getRecoveryData";
                    }
                }
            });
        });
    });

    function searchTxn()
    {
        $("#download").val('');
        $('#recoveryform').submit();
    }

    function listSubcause(id,c_id)
    {
        var recovery_causes = <?php echo json_encode($recovery_causes); ?>;
        var subcauses_list = "";
        $('#sub_cause_'+id).html('');
        subcauses_list = "<select id='subcause_"+id+"' name='subcause_"+id+"'>";
        $.each(recovery_causes[c_id]['subcauses'],function(id,label){
            subcauses_list += "<option value='"+id+"'>"+label+"</option>";
        });
        subcauses_list += "</select>";
        $('#sub_cause_'+id).append(subcauses_list);
    }

    function getSubcauses(c_id)
    {
        var recovery_causes = <?php echo json_encode($recovery_causes); ?>;
        var subcauses_list = "";
        $('#subcause').html('');
        if(c_id.length !== 0)
        {
            subcauses_list = "<select id='subcause' name='subcause'>";
            $.each(recovery_causes[c_id]['subcauses'],function(id,label){
                subcauses_list += "<option value='"+id+"'>"+label+"</option>";
            });
            subcauses_list += "</select>";
        }
        else
        {
            subcauses_list = "<select id='subcause' name='subcause'><option value=''>All</option></select>";
        }
        $('#subcause').append(subcauses_list);
    }

    function recoverAmount(id,rec_data,action)//action 1->save comments,2->deduct amt
    {
        console.log(rec_data);
        var cause = $('select#cause_'+id).val();
        var subcause = $('select#subcause_'+id).val();
        var comment = $('#comment_'+id).val().trim();
        var btn = (action == 1)?$('#save_button_'+id):$('#deductamt_button_'+id);
        var disable_btn = (action == 2)?$('#save_button_'+id+' > #savecomment'):$('#deductamt_button_'+id+' > #deductamt');
        var btn_html = (action == 1)?$('#save_button_'+id).html():$('#deductamt_button_'+id).html();
        var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";

        if(typeof subcause === 'undefined')
        {
            alert('Please select subcause');
            return false;
        }

        if(comment.length == 0)
        {
            alert('Please enter comment');
            return false;
        }

        $.ajax({
                url : "/panels/recoverAmount",
                type: "POST",
                data : {rec_data:rec_data,cause:cause,subcause:subcause,comment:comment,action:action},
                dataType:'json',
                beforeSend: function () {
                            disable_btn.prop('disabled', true);
                            btn.html(loading_gif);
                        },
                success:function(res){
                    disable_btn.prop('disabled', false);
                    btn.html(btn_html);
                    alert(res.description);
                    if(res.status == 'success')
                    {
                        window.location.href = "/panels/getRecoveryData";
                    }
                }
            });
    }

    function reactivate(id,rec_data,action)//action 1->save comments,2->deduct amt
    {
        var cause = $('select#cause_'+id).val();
        var subcause = $('select#subcause_'+id).val();
        var comment = $('#comment_'+id).val().trim();

        if(typeof subcause === 'undefined')
        {
            alert('Please select subcause');
            return false;
        }

        if(comment.length == 0)
        {
            alert('Please enter comment');
            return false;
        }

        $('#cause').val(cause);
        $('#subcause').val(subcause);
        $('#comment').val(comment);
        $('#action').val(action);
        $('#due_amt').text(rec_data.due_amt);
        $('#user_id').val(rec_data.user_id);
        $('#service_id').val(rec_data.service_id);
        $('#reactivationmodal').modal('show');
    }

    function refundAmount(id,rec_data,action)
    {
        var cause = $('select#cause_'+id).val();
        var subcause = $('select#subcause_'+id).val();
        var comment = $('#comment_'+id).val().trim();

        if(typeof subcause === 'undefined')
        {
            alert('Please select subcause');
            return false;
        }

        if(comment.length == 0)
        {
            alert('Please enter comment');
            return false;
        }

        $('#cause').val(cause);
        $('#subcause').val(subcause);
        $('#comment').val(comment);
        $('#action').val(action);
        $('#avlrefund_amt').text(rec_data.avlrefund_amt);
        $('#user_id').val(rec_data.user_id);
        $('#service_id').val(rec_data.service_id);
        $('#device_id').val(rec_data.param1);
        $('#setup_cost').text(rec_data.setup_cost);
        $('#refundmodal').modal('show');
    }

    function showComments(ref_id)
    {
        var recovery_causes = <?php echo json_encode($recovery_causes);  ?>;
        $('#showcomments').modal("toggle");

        $.ajax({
            url : "/panels/getComments",
            type: "POST",
            data : {ref_id:ref_id},
            dataType:'json',
            beforeSend: function () {
                $('#showcomments').find('.modal-body').html('<center><img src="/img/ajax-loader-2.gif"></img> Please wait...</center>');
                    },
            success:function(res){
                $('#showcomments #loadcomments').html('');
                if(res.status == 'success')
                {
                    var HTML="";
                        HTML="<div class='row' style='margin-left:5px;margin-right:5px;'>";
                        HTML+="<table class='table table-striped table-responsive table-bordered'>";
                        HTML+="<tbody>";
                        HTML+="<tr>";
                        HTML+="<th>Cause</th>";
                        HTML+="<th>Subcause</th>";
                        HTML+="<th>Comment</th>";
                        HTML+="<th>Username</th>";
                        HTML+="<th>Date</th>";
                        HTML+="</tr>";
                        $.each(res.data,function(k,v){
                                   HTML+="<tr>";
                                   HTML+="<td>"+recovery_causes[v.c.tag_id]['label']+"</td>";
                                   HTML+="<td>"+recovery_causes[v.c.tag_id]['subcauses'][v.c.subtag_id]+"</td>";
                                   HTML+="<td>"+v.c.comment+"</td>";
                                   HTML+="<td>"+v.u.username+"</td>";
                                   HTML+="<td>"+v.c.created_at+"</td>";
                                   HTML+="</tr>";

                        })
                        HTML+="</tbody>";
                        HTML+="</table>";
                        HTML+="</div>";
                        $('#showcomments').find('.modal-body').html(HTML);
                }
                else
                {
                    $('#loadcomments').html("No Comments Yet");
                    $('#showcomments').modal("toggle");
                }
            }
        });
    }

    function getUserProfile(user_id,services)
    {
        $('#userprofile').modal("toggle");
        $.ajax({
            url : "/panels/getUserProfile",
            type: "POST",
            data : {user_id:user_id},
            dataType:'json',
            beforeSend: function () {
                $('#userprofile').find('.modal-body').html('<center><img src="/img/ajax-loader-2.gif"></img> Please wait...</center>');
                    },
            success:function(res){
                $('#userprofile').find('.modal-body').html('');

                if(res.status == 'success')
                {
                    var textual_data = res.data.imp_textual_data[user_id];

                    var HTML="";
                        HTML="<div class='row' style='margin-left:5px;margin-right:5px;'>";
                        HTML+="<table class='table table-striped table-responsive table-bordered'>";
                        HTML+="<tbody>";
                        HTML+="<tr>";
                        HTML+="<th>Name</th>";
                        HTML+="<th>Shopname</th>";
                        HTML+="<th>Mobile</th>";
                        HTML+="<th>Activation Date</th>";
                        HTML+="<th>Avg Topup</th>";
                        HTML+="<th>Verified Docs</th>";
                        HTML+="</tr>";
                        HTML+="<tr>";
                        HTML+="<td>"+textual_data.imp.name+"</td>";
                        HTML+="<td>"+textual_data.imp.shop_est_name+"</td>";
                        HTML+="<td>"+textual_data.ret.mobile+"</td>";
                        HTML+="<td>"+textual_data.ret.created+"</td>";
                        HTML+="<td>"+res.data.topup+"</td>";
                        HTML+="<td>"+res.data.docs+"</td>";
                        HTML+="</tr>";
                        HTML+="</tbody>";
                        HTML+="</table>";
                        HTML+="<br/>";
                        HTML+="<table class='table table-striped table-responsive table-bordered'>";
                        HTML+="<tbody>";
                        HTML+="<tr>";
                        HTML+="<th>Service</th>";
                        HTML+="<th>Last Txn Date</th>";
                        HTML+="<th>Avg Sale</th>";
                        HTML+="<th>Last Rental Paid Date</th>";
                        HTML+="<th>Rental Amt</th>";
                        HTML+="</tr>";
                        $.each(res.data.txn_details,function(k,v){
                                    HTML+="<tr>";
                                    HTML+="<td>"+services[k]+"</td>";
                                    HTML+="<td>"+v['last_txn_date']+"</td>";
                                    HTML+="<td>"+v['avg_sale']+"</td>";
                                    HTML+="<td>"+v['rental_paid_date']+"</td>";
                                    HTML+="<td>"+v['rental_amt']+"</td>";
                                    HTML+="</tr>";

                        })
                        HTML+="</tbody>";
                        HTML+="</table>";
                        HTML+="</div>";
                        $('#userprofile').find('.modal-body').html(HTML);
                }
                else
                {
                    $('#loadcomments').html("No data found");
                    $('#userprofile').modal("toggle");
                }
            }
        });
    }
</script>
<?php } ?>