<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="/boot/css/fixedHeader.dataTables.min.css">

<style>
    .tab {
        overflow: hidden;
        border: 1px solid #428bca;
        background-color: #f1f1f1;
        height: 40px;
        border-radius: 10px;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 16px;
        line-height: 0.8em;
        color: gray;
    }

    .tab button:hover {
        background-color: #428bca;
        color: #fff;
    }

    .tab button.active {
        background-color: #fff;
        color: #428bca;
        font-weight: 600;
    }
    
    thead{
       background-color: #428bca;
       color: #fff;
    }
    tbody tr td,th{
       text-align:center;
    }
</style>
<div class="tab">

    <ul class="nav nav-tabs">
        <li><button class="tablinks active" onclick="window.location='/rm/rmAttendance'">Attendance</button></li>
        <li><button class="tablinks" onclick="window.location='/rm/rmTask'">Task</button></li>
        </li>
        <li><button class="tablinks" onclick="window.location='/rm/rmRoutine'">Routine</button></li>
        </li>
    </ul>

</div><br/>

<form class="form-inline" method="post" action="/rm/rmAttendance">
<?php echo $this->Session->flash(); ?>
  <div class="form-group col-md-3">
    <label for="report_date">Report Type:</label>
    <select class="form-control" id="report_type" name="report_type">
        <option value="">--Report Type--</option>
        <option <?php echo ($report_type==1)?"selected":"";?> value="1">Week 1 (1-7)</option>
        <option <?php echo ($report_type==2)?"selected":"";?> value="2">Week 2 (8-14)</option>
        <option <?php echo ($report_type==3)?"selected":"";?> value="3">Week 3 (15-21)</option>
        <option <?php echo ($report_type==4)?"selected":"";?> value="4">Week 4 (22-31)</option>
        <option <?php echo ($report_type==5)?"selected":"";?> value="5">Monthly (1-31)</option>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label for="report_month">Report Month:</label>
    <select class="form-control" id="report_month" name="report_month">
        <option value="">--Report Month--</option>
        <option value="01" <?php echo ($report_month=="01")?"selected":"";?>>Janaury</option>
        <option value="02" <?php echo ($report_month=="02")?"selected":"";?>>February</option>
        <option value="03" <?php echo ($report_month=="03")?"selected":"";?>>March</option>
        <option value="04" <?php echo ($report_month=="04")?"selected":"";?>>April</option>
        <option value="05" <?php echo ($report_month=="05")?"selected":"";?>>May</option>
        <option value="06" <?php echo ($report_month=="06")?"selected":"";?>>June</option>
        <option value="07" <?php echo ($report_month=="07")?"selected":"";?>>July</option>
        <option value="08" <?php echo ($report_month=="08")?"selected":"";?>>August</option>
        <option value="09" <?php echo ($report_month=="09")?"selected":"";?>>September</option>
        <option value="10" <?php echo ($report_month=="10")?"selected":"";?>>October</option>
        <option value="11" <?php echo ($report_month=="11")?"selected":"";?>>November</option>
        <option value="12" <?php echo ($report_month=="12")?"selected":"";?>>December</option>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label for="report_year">Report Year:</label>
    <select class="form-control" id="report_year" name="report_year">
        <option value="">--Report Year--</option>
        <?php
        $current_year = date('Y');
        for($i=$current_year;$i>=2012;$i--){
        ?>
            <option <?php echo ($report_year==$i)?"selected":"";?> value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php
        }
        ?>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
  <?php
    if($date_range){
        ?>
        <div class="alert alert-info text-center" style="margin: 20px 10px 0px;"><?php echo date('d M, Y',strtotime($date_range['from_date']));?> - <?php echo date('d M, Y',strtotime($date_range['to_date']));?></div>
        <?php
    }
    ?>
</form>



<br/>
<table class="table table-bordered table-hover table-striped table-responsive" style="width:100%;">            
    <thead>             
        <tr>   
            <th>ID</th>
            <th>RM Name</th>
            <th>Total no. of days</th>
            <th id="sort_half_day">Late Mark / Half Days</th>
            <th>Leaves</th>
        </tr>
    </thead>
            
    <tbody style="color: #4e4e4e;">
        <?php
         $i = 1; 
         if($allRM) { 
             foreach($allRM as $RM) { 
                ?>
                <tr style="cursor:pointer;" onclick="get_individual_date_detail('<?php echo $RM['users']['id']; ?>','<?php echo $RM['rm']['name']; ?>','<?php echo $date_range['from_date']; ?>','<?php echo $date_range['to_date']; ?>','<?php echo $date_range['total_days']; ?>','<?php echo $RM['rm']['half_day_count']; ?>','<?php echo $RM['rm']['leaves']; ?>');">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $RM['rm']['name']; ?></td>
                    <td><?php echo $date_range['working_days'];?></td>
                    <td style="color:<?php echo ($RM['rm']['half_day_count']!=0)?'red':'';?>"><?php echo $RM['rm']['half_day_count']; ?></td>
                    <td><?php echo $RM['rm']['leaves']; ?></td>
                </tr>
        <?php 
                $i++; 
             } 
        } 
        ?>
    </tbody>
</table>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.FixedHeaderDataTables.min.js"></script>
<script type="text/javascript" src="/boot/js/dataTables.fixedHeader.min.js"></script>

<script>
$(document).ready(function(){
    $("#sort_half_day").click();
    $("#sort_half_day").click();
});
    
    $('#date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });
    
    var table = $('.table').DataTable( {
        fixedHeader: {
            header: true,
            footer: true
        },
        "order": [[0, "desc" ]],
        "pageLength":100,
        "lengthMenu": [100, 200, 500]
    } );

    function get_individual_date_detail(rm_user_id,name,from_date,to_date,total_days,half_day_count,leaves){

        var form = $(document.createElement('form'));
        $(form).attr("action", "/rm/rmAttendanceDetail/");
        $(form).attr("method", "POST");

        var rm_user_id = $("<input>")
            .attr("type", "hidden")
            .attr("name", "rm_user_id")
            .val(rm_user_id );

        var name = $("<input>")
            .attr("type", "hidden")
            .attr("name", "name")
            .val(name );

        /*var from_date = $("<input>")
            .attr("type", "hidden")
            .attr("name", "from_date")
            .val(from_date );

        var to_date = $("<input>")
            .attr("type", "hidden")
            .attr("name", "to_date")
            .val(to_date );

        var total_days = $("<input>")
            .attr("type", "hidden")
            .attr("name", "total_days")
            .val(total_days );

        var half_day_count = $("<input>")
            .attr("type", "hidden")
            .attr("name", "half_day_count")
            .val(half_day_count );

        var leaves = $("<input>")
            .attr("type", "hidden")
            .attr("name", "leaves")
            .val(leaves );*/

        var report_type = $("<input>")
            .attr("type", "hidden")
            .attr("name", "report_type")
            .val(<?php echo @$report_type;?> );

        var report_month = $("<input>")
            .attr("type", "hidden")
            .attr("name", "report_month")
            .val(<?php echo @$report_month;?> );

        var report_year = $("<input>")
            .attr("type", "hidden")
            .attr("name", "report_year")
            .val(<?php echo @$report_year;?> );


        $(form).append($(rm_user_id));
        $(form).append($(name));
        /*$(form).append($(from_date));
        $(form).append($(to_date));
        $(form).append($(total_days));
        $(form).append($(half_day_count));
        $(form).append($(leaves));*/
        $(form).append($(report_type));
        $(form).append($(report_month));
        $(form).append($(report_year));

        form.appendTo( document.body )

        $(form).submit();
    }
    
</script>
