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
            <a href="/leads/leadList" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Lead List</span></a>
            <a href="/leads/format" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Lead Upload</span></a>
            <a href="/leads/employeeDetails" class="list-group-item"><i class="fa fa-list-alt"></i> <span>Employee Leads </span></a>
            <a href="/leads/report" class="list-group-item active"><i class="fa fa-credit-card"></i> <span>Report</span></a>
            </div>
        </div>
        <div class='col-sm-10'>
            <div class="row">
                <form class="form-inline" id="reportform" name="reportform" method="POST" action="/leads/report">
                    <div class="row">
                        <div class="col-md-1">
                            <label>From</label>
                        </div>
                        <div class="col-md-4">
                            <span class='input-group date' id='datetimepicker1' >
                                <input type='text' class="form-control" id="fromdate"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </span>
                        </div>
                        <div class="col-md-1">
                            <label>To</label>
                        </div>
                        <div class="col-md-4">
                            <span class='input-group date' id='datetimepicker2'>
                                <input type='text' class="form-control" id="todate"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </span>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn  btn-primary btn-md" id="filterdata">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <br>
            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Lead Sources wise</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped leadtable">
                            <thead>
                                <tr class="filters">
                                    <th>Lead Sources</th>
                                    <th>Hot</th>
                                    <th>Cold</th>
                                    <th>Converted</th>
                                    <th>Not Interested</th>
                                    <th>Pending Leads</th>
                                    <th>Total Lead</th>
                                </tr>
                            </thead>
                            <tbody id="leadtabledata">
                                <?php 
//                                    foreach($lead_source as $key => $value){
//                                        echo '<tr>';
//                                        echo '<td>'.$key.'</td>';
//                                        $hot += $value['1'];     echo '<td>'.$value['1'].'</td>';
//                                        $cold += $value['3'];    echo '<td>'.$value['3'].'</td>';
//                                        $con += $value['48'];    echo '<td>'.$value['48'].'</td>';
//                                        $notcon += $value['49']; echo '<td>'.$value['49'].'</td>';
//                                        $pend += $value['0'];    echo '<td>'.$value['0'].'</td>';
//                                        $totl += $value['sum'];  echo '<td style="font-weight: bold">'.$value['sum'].'</td>';
//                                        echo '</tr>';
//                                    }
//                                    $totl += $hot+$cold+$con+$notcon;
//                                    echo '<tr style="font-weight: bold">';
//                                    echo '<td>Total</td>';
//                                    echo '<td>'.$hot.'</td>';
//                                    echo '<td>'.$cold.'</td>';
//                                    echo '<td>'.$con.'</td>';
//                                    echo '<td>'.$notcon.'</td>';
//                                    echo '<td>'.$pend.'</td>';
//                                    echo '<td>'.$totl.'</td>';
//                                    echo '</tr>';
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Executive wise</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped leadtable">
                            <thead>
                                <tr class="filters">
                                    <th>Executive Name</th>
                                    <th>Hot</th>
                                    <th>Cold</th>
                                    <th>Converted</th>
                                    <th>Not Interested</th>
                                    <th>Total Lead</th>
                                </tr>
                            </thead>
                            <tbody id="usertabledata">
                                <?php 
//                                    foreach($user_wise as $key =>$user){
//                                        echo '<tr>';
//                                        echo '<td>'.$key.'</td>';
//                                        $hot += $user['1'];echo '<td>'.$user['1'].'</td>';
//                                        $cold += $user['3'];echo '<td>'.$user['3'].'</td>';
//                                        $con += $user['48'];echo '<td>'.$user['48'].'</td>';
//                                        $notcon += $user['49'];echo '<td>'.$user['49'].'</td>';
//                                        $totl += $user['0'];echo '<td style="font-weight: bold">'.$user['0'].'</td>';
//                                        echo '</tr>';
//                                    }
//                                    echo '<tr style="font-weight: bold">';
//                                    echo '<td>Total</td>';
//                                    echo '<td>'.$hot.'</td>';
//                                    echo '<td>'.$cold.'</td>';
//                                    echo '<td>'.$con.'</td>';
//                                    echo '<td>'.$notcon.'</td>';
//                                    echo '<td>'.$totl.'</td>';
//                                    echo '</tr>';
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Tag List</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped leadtable">
                                <thead>
                                    <tr class="filters">
                                        <th>Tags</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody id="tagtabledata">
                                    <?php 
                                        foreach($tag_data as $tag){
                                            echo '<tr>';
                                            echo '<td>'.$tag['tag_data']['Tag'].'</td>';
                                            echo '<td>'.$tag[0]['Count'].'</td>';
                                            $total += $tag[0]['Count'];
                                            echo '</tr>';
                                        }
                                        echo '<tr style="font-weight: bold">';
                                        echo '<td>Total</td>';
                                        echo '<td>'.$total.'</td>';
                                        echo '</tr>';
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    jQuery(document).ready(function($) {
        
        $('.list-group-item').click(function(e) {
            $('.list-group-item').removeClass('active');
            $(this).addClass('active');
         });
        
        $('#filterdata').click(function(){
            var data = {
                'fromdate': $('#fromdate').val(),
                'todate': $('#todate').val()
            };
            $.ajax({
                type : 'POST',
                url  : '/leads/report',
                dataType : "json",
                data : { filter : '1', data : data},
                success : function(response){
                    if(response.status == 'success'){ 
                    var leaddata = ''; var cold= 0;var hot = 0; var con=0;var notcon =0;var pend= 0;var totl =0;
                    $.each(response.lead_source, function(key,value){
                        leaddata +='<tr>';
                        leaddata +='<td>'+key+'</td>';
                        leaddata +='<td>'+value['1']+'</td>';
                        leaddata +='<td>'+value['3']+'</td>';
                        leaddata +='<td>'+value['48']+'</td>';
                        leaddata +='<td>'+value['49']+'</td>';
                        leaddata +='<td>'+value['0']+'</td>';
                        leaddata +='<td style="font-weight: bold">'+value['sum']+'</td>';
                        leaddata += '</tr>';
                        
                        hot += parseInt(value['1']);   
                        cold += parseInt(value['3']);    
                        con += parseInt(value['48']);    
                        notcon+= parseInt(value['49']); 
                        pend += parseInt(value['0']);    
                        totl += parseInt(value['sum']);  
                    });
//                    totl += hot+cold+con+notcon;
                    leaddata += '<tr style="font-weight: bold">';
                    leaddata += '<td>Total</td>';
                    leaddata += '<td>'+hot+'</td>';
                    leaddata += '<td>'+cold+'</td>';
                    leaddata += '<td>'+con+'</td>';
                    leaddata += '<td>'+notcon+'</td>';
                    leaddata += '<td>'+pend+'</td>';
                    leaddata += '<td>'+totl+'</td>';
                    leaddata += '</tr>';
                    $('#leadtabledata').html(leaddata);
                    
                    var user_wisedata = ''; var user_wisecold= 0;var user_wisehot = 0; var user_wisecon=0;var user_wisenotcon =0;var user_wisetotl =0;
                    $.each(response.user_wise, function(key,user){
                        user_wisedata += '<tr>';
                        user_wisedata += '<td>'+key+'</td>';
                        user_wisedata += '<td>'+user['1']+'</td>';
                        user_wisedata += '<td>'+user['3']+'</td>';
                        user_wisedata += '<td>'+user['48']+'</td>';
                        user_wisedata += '<td>'+user['49']+'</td>';
                        user_wisedata += '<td style="font-weight: bold">'+user['0']+'</td>';
                        user_wisedata += '</tr>';
                        
                        user_wisehot += parseInt(user['1']);
                        user_wisecold += parseInt(user['3']);
                        user_wisecon += parseInt(user['48']);
                        user_wisenotcon += parseInt(user['49']);
                        user_wisetotl += parseInt(user['0']);
                    });
                    user_wisedata += '<tr style="font-weight: bold">';
                    user_wisedata += '<td>Total</td>';
                    user_wisedata += '<td>'+user_wisehot+'</td>';
                    user_wisedata += '<td>'+user_wisecold+'</td>';
                    user_wisedata += '<td>'+user_wisecon+'</td>';
                    user_wisedata += '<td>'+user_wisenotcon+'</td>';
                    user_wisedata += '<td>'+user_wisetotl+'</td>';
                    user_wisedata += '</tr>';
                    $('#usertabledata').html(user_wisedata);
                    
                    var tag_datadata = '';var total =0;
                    for (var x in response.tag_data) {
                        if(!isNaN(x)){
                            tag_datadata += '<tr>';
                            tag_datadata += '<td>'+response.tag_data[x].tag_data.Tag+'</td>';
                            tag_datadata += '<td>'+response.tag_data[x][0].Count+'</td>';
                            tag_datadata += '</tr>';
                            total += parseInt(response.tag_data[x]['0']['Count']);
                        }
                    }
                    tag_datadata += '<tr style="font-weight: bold">';
                    tag_datadata += '<td>Total</td>';
                    tag_datadata += '<td>'+total+'</td>';
                    tag_datadata += '</tr>';
                     $('#tagtabledata').html(tag_datadata);
                 }
                 else{
                    alert(response.description) ; 
                 }
                }
            });
        });
        $('#datetimepicker1, #datetimepicker2').datetimepicker({
            format: "YYYY-MM-DD",
            toolbarPlacement: 'top',
            defaultDate: moment(),
            showClear: true,
            showClose: true,
            sideBySide: true
        });
        $('#filterdata').trigger('click');
    });
</script>
<script>jQuery.noConflict();</script>
