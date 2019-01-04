<link href="/boot/css/font-awesome.min.css" rel="stylesheet">
<script src="/boot/js/jquery-2.0.3.min.js"></script>
<script src="/boot/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

<script src = "/boot/js/jquery-ui.js"></script>
<link href = "/boot/css/jquery-ui.css" rel = "stylesheet">
<script src="/boot/js/moment.min.js"></script>
<script src="/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
<!--<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/css/bootstrap-combined.min.css" rel="stylesheet" >-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<style>
    .list-group {
        margin:auto;
        float:left;
        padding-top:20px;
    }
    
    .lead {
        margin:auto;
        left:0;
        right:0;
        padding-top:10%;
    }
</style>
<div class="container">
    <div class="row">
        <div class='col-sm-2'>
            <div class="list-group" style="width:100%;">
            <a href="/leads/assignLead" class="list-group-item"><i class="fa fa-tasks"></i> <span>Assign Lead</span></a>
            <a href="/leads/leadList" class="list-group-item active"><i class="fa fa-list-alt"></i> <span>Lead List</span></a>
            <a href="/leads/format" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Lead Upload</span></a>
            <a href="/leads/employeeDetails" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Employee Leads</span></a>
            <a href="/leads/report" class="list-group-item"><i class="fa fa-credit-card"></i> <span>Report</span></a>
            </div>
        </div>
        <div class='col-sm-10'>
            <div id='errormsg'></div>
                
            <form class="form-inline" id="reportform" name="reportform" method="POST" action="/leads/index">
                <div class="row">
                    <div class="col-md-1">
                        <label>From</label>
                    </div>
                    <div class="col-md-3">
                        <span class='input-group datetimepicker1 date' id='datetimepicker1' >
                            <input type='text' class="form-control" name="fromdate" id="fromdate"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </span>
                    </div>
                    <div class="col-md-1">
                        <label>To</label>
                    </div>
                    <div class="col-md-3">
                        <span class='input-group datetimepicker2 date' id='datetimepicker2'>
                            <input type='text' class="form-control" name="todate" id="todate"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </span>
                    </div>
                    <?php if (!empty($access)) { ?>
                    <div class="col-md-1">
                        <label>Employee Name</label>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <select id="user" name="user" class="form-control" style="width:215px;">
                                <option>----------- All -----------</option>
                                <?php
                                    foreach($users as $user){
                                            echo "<option value='".$user['users']['id']."'>".$user['users']['name']." </option>";
                                    }
                                 ?>
                            </select>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <label>Mobile No.</label>
                    </div>
                    <div class="col-md-3">
                        <span class='input-group' >
                            <input type='text' class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile No."/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-phone-alt"></span>
                            </span>
                        </span>
                    </div>
                    <div class="col-md-1">
                        <label>Lead Tag</label>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <select id="leadtag" name="leadtag" class="form-control" style="width:215px;">
                                <option>---Select Main tags---</option>
                                <?php
                                    foreach($tagging as $tag){
                                            echo "<option value='".$tag['taggings_new']['id']."'>".$tag['taggings_new']['name']." </option>";
                                    }
                                 ?>
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary btn-md" id="filterdata">Submit</button>
                    <span id="response"></span>
                </div>
            </form>
            <br>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Lead List</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped leadtable">
                        <thead>
                            <tr class="filters">
                                <th>Lead ID</th>
                                <th>Lead Date</th>
                                <th>Assigned Date n Time</th>
                                <th>Name</th>
                                <th>State</th>
                                <th>Pincode</th>
                                <th>Mobile No</th>
                                <th>Alt Mobile No</th>
                                <th>Lead Source</th>
                                <th>Lead Status</th>
                                <th colspan="3">Dist Count /  Retailer Count</th>
                                <th>Assign</th>
                                <th>Last Call Date & Time</th>
                                <th>Lead Tag</th>
                                <th>Followup Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="leadtabledata">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function goToPage(page = 1) {
        $('#reportform').attr('action', '/leads/index/?page=' + page);
        $('#reportform').submit();
    }

    jQuery(document).ready(function($) {
        
        $('.list-group-item').click(function(e) {
//            e.preventDefault();
            $('.list-group-item').removeClass('active');
            $(this).addClass('active');
         });
        
        $('#leadtable').DataTable();
        $(function() {
            $("#distributor").autocomplete({
                source: "/leads/salesList",
                minLength: 2,
                select: function(event, ui) {
                    event.preventDefault();
                    $("#distributor").val(ui.item.label);
                    $("#distributor-id").val(ui.item.value);
                },
                focus: function(event, ui) {
                    event.preventDefault();
                    $("#distributor").val(ui.item.label);
                }
            });
        });
        $('#filterdata').click(function(){
            $('#response').html("<img src='/img/ajax-loader-2.gif' />");
            var data = {
                'fromdate': $('#fromdate').val(),
                'todate': $('#todate').val(),
                'mobile': $('#mobile').val(),
                <?php if (!empty($access)) { ?>
                'user' : $('#user').val(),
                <?php } ?>
                'leadtag': $('#leadtag').val()
            };
            $.ajax({
                type : 'POST',
                url  : '/leads/leadList',
                dataType : "json",
                data : { filter : '1', data : data},
                cache: false,
                success : function(response){
                    $('#response').html('');
                    $('#errormsg').html('').fadeIn().delay(3000).fadeOut();
                    if(response['status'] == 'failure' ){
                        $('#errormsg').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" >&times;</a><p>'+response['description']+'</p></div>').fadeOut(5500);
                    }
                    var tabledata = '';var x = 0;
                     if(response['status'] == 'success' && (response.description.lead_list.priority.length > 0 || response.description.lead_list.result.length > 0)){
                        if(typeof response.description.lead_list.priority != "undefined"){     
                            $.each(response.description.lead_list.priority, function(k, v){
                                tabledata += '<tr style= "background-color: yellow;">';
                                tabledata += '<td>'+response.description.lead_list.priority[x]['txns'].id+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].creation_date == '0000-00-00' ? '' : response.description.lead_list.priority[x]['txns'].creation_date )+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].assigned_datetime == null ? '' : response.description.lead_list.priority[x]['txns'].assigned_datetime)+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].shop_name == null ? '' : response.description.lead_list.priority[x]['txns'].shop_name)+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].state_name == null ? '' : response.description.lead_list.priority[x]['txns'].state_name)+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].pin_code == null ? '' : response.description.lead_list.priority[x]['txns'].pin_code)+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].phone == null ? '' : response.description.lead_list.priority[x]['txns'].phone)+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].alternate_no == null ? '' : response.description.lead_list.priority[x]['txns'].alternate_no)+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].lead_source == null ? '' : response.description.lead_list.priority[x]['txns'].lead_source)+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].lead_status == null ? '' : response.description.lead_list.priority[x]['txns'].lead_status)+'</td>';
                                tabledata += '<td>'+(( response.description.no_of_distributors != null && typeof (response.description.no_of_distributors[response.description.lead_list.priority[x]['txns'].pin_code]) != "undefined") ? response.description.no_of_distributors[response.description.lead_list.priority[x]['txns'].pin_code] : '0' )+'</td>';
                                tabledata += '<td> / </td>';
                                tabledata += '<td>'+(( response.description.no_of_retailers !=  null && typeof (response.description.no_of_retailers[response.description.lead_list.priority[x]['txns'].pin_code]) != "undefined") ? response.description.no_of_retailers[response.description.lead_list.priority[x]['txns'].pin_code] : '0' )+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].user_name == null ? '' : response.description.lead_list.priority[x]['txns'].user_name)+'</td>';
                                tabledata += '<td>'+(response.description.lead_list.priority[x]['txns'].created_at == null ? '' : response.description.lead_list.priority[x]['txns'].created_at)+'</td>';
                                tabledata += '<td>'+ (response.description.lead_list.priority[x]['txns'].tag == null ? '' : response.description.lead_list.priority[x]['txns'].tag) +'</td>';
                                tabledata += '<td>'+ (response.description.lead_list.priority[x]['txns'].followup_date == null ? '' : response.description.lead_list.priority[x]['txns'].followup_date)  +'</td>';
                                tabledata += '<td class="text-center"><a class="btn btn-info btn-xs edit" href="/leads/customerDetail/' + response.description.lead_list.priority[x]['txns'].id + '" target="_blank"><span class="glyphicon glyphicon-edit"></span> Edit</a></td>';
                                tabledata += '<tr>';
                                x++;
                            });
                        }
                    
                        var y = 0;
                    if(typeof response.description.lead_list.result != "undefined"){
                        $.each(response.description.lead_list.result, function(k, v){
                            tabledata += '<tr>';
                            tabledata += '<td>'+response.description.lead_list.result[y]['txns'].id+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].creation_date == '0000-00-00' ? '' : response.description.lead_list.result[y]['txns'].creation_date )+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].assigned_datetime == null ? '' : response.description.lead_list.result[y]['txns'].assigned_datetime)+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].shop_name == null ? '' : response.description.lead_list.result[y]['txns'].shop_name)+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].state_name == null ? '' : response.description.lead_list.result[y]['txns'].state_name)+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].pin_code == null ? '' : response.description.lead_list.result[y]['txns'].pin_code)+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].phone == null ? '' : response.description.lead_list.result[y]['txns'].phone)+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].alternate_no == null ? '' : response.description.lead_list.result[y]['txns'].alternate_no)+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].lead_source == null ? '' : response.description.lead_list.result[y]['txns'].lead_source)+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].lead_status == null ? '' : response.description.lead_list.result[y]['txns'].lead_status)+'</td>';
                            tabledata += '<td>'+(( response.description.no_of_distributors != null && typeof (response.description.no_of_distributors[response.description.lead_list.result[y]['txns'].pin_code]) != "undefined") ? response.description.no_of_distributors[response.description.lead_list.result[y]['txns'].pin_code] : '0' )+'</td>';
                            tabledata += '<td> / </td>';
                            tabledata += '<td>'+( ( response.description.no_of_retailers !=  null && typeof (response.description.no_of_retailers[response.description.lead_list.result[y]['txns'].pin_code]) != "undefined") ? response.description.no_of_retailers[response.description.lead_list.result[y]['txns'].pin_code] : '0' )+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].user_name == null ? '' : response.description.lead_list.result[y]['txns'].user_name)+'</td>';
                            tabledata += '<td>'+(response.description.lead_list.result[y]['txns'].created_at == null ? '' : response.description.lead_list.result[y]['txns'].created_at)+'</td>';
                            tabledata += '<td>'+ (response.description.lead_list.result[y]['txns'].tag == null ? '' : response.description.lead_list.result[y]['txns'].tag) +'</td>';
                            tabledata += '<td>'+ (response.description.lead_list.result[y]['txns'].followup_date == null ? '' : response.description.lead_list.result[y]['txns'].followup_date)  +'</td>';
                            tabledata += '<td class="text-center"><a class="btn btn-info btn-xs edit" href="/leads/customerDetail/' + response.description.lead_list.result[y]['txns'].id + '" target="_blank"><span class="glyphicon glyphicon-edit"></span> Edit</a></td>';
                            tabledata += '<tr>';
                            y++;
                        });

                }
                }
                $('#leadtabledata').html(tabledata);
            }
            });
        }); 
        var d = new Date();
        $('.datetimepicker1').datetimepicker({
            defaultDate: d.setDate(d.getDate()-1),
            format: 'YYYY-MM-DD HH:mm:ss',
            maxDate: new Date()
        });
        $('.datetimepicker2').datetimepicker({
            defaultDate: new Date(),
            format: 'YYYY-MM-DD HH:mm:ss',
            maxDate: new Date()
        });
        $('#filterdata').trigger("click");
    });
</script>
<script>jQuery.noConflict();</script>
