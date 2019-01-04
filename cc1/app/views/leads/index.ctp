<link href="/boot/css/font-awesome.min.css" rel="stylesheet">
<script src="/boot/js/jquery-2.0.3.min.js"></script>
<script src="/boot/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="/css/bootstrap.min.css">

<script src = "/boot/js/jquery-ui.js"></script>
<link href = "/boot/css/jquery-ui.css" rel = "stylesheet">
<script src="/boot/js/moment.min.js"></script>
<script src="/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
<!--<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/css/bootstrap-combined.min.css" rel="stylesheet" >-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
<style>
    .table-responsive {height:180px;}
    .centered-form{
        margin-top: 60px;
    }
   .centered-form .panel{
        background: rgba(255, 255, 255, 0.8);
        box-shadow: rgba(0, 0, 0, 0.3) 20px 20px 20px;
    }/*
*/    .filterable {
        margin-top: 15px;
    }
   .filterable .panel-heading .pull-right {
        margin-top: -20px;
    }

</style>
<div class="container">
    <div class="row">
        <div class='col-sm-2'>
            <div class="sidebar-nav">
                <div class="well" style="width:100%; padding: 8px 0;">
                    <ul class="nav nav-list"> 
                        <li class="nav-header"><center><font face="sans-serif"> Admin Menu</font></center></li>        
                        <li><a href="index"><i class="icon-home"></i> Dashboard</a></li>
                        <li><a href="#"><i class="icon-envelope"></i> Messages <span class="badge badge-info">4</span></a></li>
                        <li><a href="#"><i class="icon-comment"></i> Comments <span class="badge badge-info">10</span></a></li>
                        <li class="active"><a href="#"><i class="icon-user"></i> Members</a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="icon-comment"></i> Settings</a></li>
                        <li><a href="#"><i class="icon-share"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class='col-sm-10'>
            <form class="form-inline" id="reportform" name="reportform" method="POST" action="/leads/index">
                <div>
                    <label>From</label>
                    <span class='input-group date' id='datetimepicker1' >
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </span>
                    <label>To</label>
                    <span class='input-group date' id='datetimepicker2'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </span>
                    <label>State</label><input type="text" id="state" class="form-control" ><input type="hidden" id="state-id">
                    <button type="button" class="btn btn-primary btn-md" >Submit</button>
                </div>
            </form>

            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                        </div>
                        <div class="modal-body">
                            <a class="custom-close"> My Custom Close Link </a>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="first_name" id="first_name" class="form-control input-sm" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="last_name" id="last_name" class="form-control input-sm" placeholder="Last Name">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email Address">
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-sm" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Register" class="btn btn-info btn-block">

                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>






            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Users
                    </h3>
                    <div class="pull-right">
                        <button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-plus"></span> Add User</button>
                    </div>
                </div>
                <div class="">
                    <table class="table">
                        <thead>
                            <tr class="filters">
                                <th><input type="checkbox" id = "chckHead" /></th>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            foreach ($leads as $lead) {
                                echo '<tr>';
                                echo '<td><input type="checkbox" class = "chcktbl" /></td>';
                                echo '<td>' . $lead['leads_new']['id'] . '</td>';
                                echo '<td>' . $lead['leads_new']['name'] . '</td>';
                                echo '<td>' . $lead['leads_new']['email'] . '</td>';
                                echo '<td>' . $lead['leads_new']['phone'] . '</td>';
                                echo '<td>' . $lead['leads_new']['lead_state'] = $lead['leads_new']['lead_state'] == '2' ? '<span class="label label-danger">Hot</span>' : '<span class="label label-primary">Cold</span>' . '</td>';
                                echo '<td class="text-center"><a class="btn btn-info btn-xs edit" href="#"><span class="glyphicon glyphicon-edit"></span> Edit</a> <a href="#" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a></td>';
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
</div>
<script>
    function goToPage(page = 1) {
        jQuery('#reportform').attr('action', '/leads/index/?page=' + page);
        jQuery('#reportform').submit();
    }

    jQuery(document).ready(function($) {
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
        $('#getModal').click(function() {
            $('#myModal').modal('show');
            $('#myModal').modal({backdrop: 'static', keyboard: true});
        });
        $('.edit').click(function() {
            $('#myModal').modal('show');
            $('#myModal').modal({backdrop: 'static', keyboard: true});
        });
        $('#chckHead').click(function() {
            if (this.checked == false) {
                $('.chcktbl:checked').prop('checked', false);
            } else {
                $('.chcktbl:not(:checked)').prop('checked', true);
            }
        });
        $('#datetimepicker1').datetimepicker();
        $('#datetimepicker2').datetimepicker();
    });
</script>
<script>jQuery.noConflict();</script>