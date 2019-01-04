<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
 <link rel="stylesheet" href="/css/jquery-ui.css">
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
    tbody tr td, th{
       text-align:center;
    }
</style>
<div class="tab">

    <ul class="nav nav-tabs">
        <li><button class="tablinks" onclick="window.location='/rm/rmAttendance'">Attendance</button></li>
        <li><button class="tablinks" onclick="window.location='/rm/rmTask'">Task</button></li>
        </li>
        <li><button class="tablinks active" onclick="window.location='/rm/rmRoutine'">Routine</button></li>
        </li>
    </ul>

</div><br/>

<form class="form-inline" method="post" action="/rm/rmRoutine" style="margin-bottom:1%;">
<?php echo $this->Session->flash(); ?>
  <div class="form-group col-md-2">
    <select class="form-control" id="report_type" name="report_type">
        <option value="">--Report Type--</option>
        <option <?php echo ($report_type==1)?"selected":"";?> value="1">Week 1 (1-7)</option>
        <option <?php echo ($report_type==2)?"selected":"";?> value="2">Week 2 (8-14)</option>
        <option <?php echo ($report_type==3)?"selected":"";?> value="3">Week 3 (15-21)</option>
        <option <?php echo ($report_type==4)?"selected":"";?> value="4">Week 4 (22-31)</option>
        <option <?php echo ($report_type==5)?"selected":"";?> value="5">Monthly (1-31)</option>
    </select>
  </div>
  <div class="form-group col-md-2">
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
  <div class="form-group col-md-2">
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
  <div class="form-group col-md-3">
    <input type="text" class="form-control" id="name" name="name" placeholder="RM name" value="<?php echo $name;?>">
  </div>
  <!--div class="form-group col-md-3">
    <label for="report_year">Attendance:</label>
    <select class="form-control" id="late_or_half_mark" name="late_or_half_mark">
        <option value="" <?php echo ($late_or_half_mark=="")?"selected":"";?>>All</option>
        <option value="1" <?php echo ($late_or_half_mark=="1")?"selected":"";?>>Half Day</option>
        
    </select>
    
  </div-->
  <button type="submit" class="btn btn-primary">Submit</button>
  <input type="hidden" id="rm_user_id" name="rm_user_id" value="<?php echo $rm_user_id;?>">
</form>


  <?php
    if($date_range){
        ?>
        <span class="alert alert-info col-md-12 text-center" ><b><?php echo @$name;?></b> : <?php echo date('d M, Y',strtotime($date_range['from_date']));?> - <?php echo date('d M, Y',strtotime($date_range['to_date']));?></span>
        <?php
    }
    ?>



<table class="table table-bordered table-responsive" style="width:100%;">            
    <thead>             
        <tr>   
            <th>Date</th>
            <th>Halts</th>
            <th>15 min halt</th>
            <th>30 min halt</th>
            <th>More than hour halt</th>
            <?php if($_SESSION['Auth']['User']['group_id'] == SUPER_ADMIN) { ?>
            <th>Routine Check</th>
            <?php } ?>
        </tr>
    </thead>
            
    <tbody style="color: #4e4e4e;">
        <?php
         $i = 1; 
         if($rm_task_detail) { 
             foreach($rm_task_detail as $RM) {
              if($RM['total_halt'] > 0){
                 $background = "";
                ?>
                <tr style="background-color:<?php echo @$background;?> ">
                    <td><?php echo $RM['date']; ?></td>
                    <td><?php echo $RM['total_halt']; ?></td>
                    <td><?php echo $RM['minute15_halt']; ?></td>
                    <td><?php echo $RM['minute30_halt']; ?></td>
                    <td><?php echo $RM['more_than_hour_halt']; ?></td>
                    <?php if($_SESSION['Auth']['User']['group_id'] == SUPER_ADMIN){ ?>
                    <td><a href="/rm/rmMapRoutine/?rm_user_id=<?php echo $RM['rm_user_id']; ?>&date=<?php echo $RM['date']; ?>">View Map</a></td>
                    <?php } ?>
                </tr>
        <?php 
                $i++; 
                }
             } 
        } 
        ?>
    </tbody>
</table>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.FixedHeaderDataTables.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/boot/js/dataTables.fixedHeader.min.js"></script>
  <script>
  $( function() {

    <?php
    $RM_Array = '[';
     foreach($allRM as $RM){
        $RM_Array .= '{value:'.$RM['users']['id'].',label:"'.$RM['rm']['name'].'"},';
    }
    $RM_Array = rtrim($RM_Array,",");
    $RM_Array .= "]";
    ?>
    var RM_Array = <?php echo $RM_Array;?>;
    $( "#name" ).autocomplete({
      source: function(request, response) {
            var results = $.ui.autocomplete.filter(RM_Array, request.term);

            response(results.slice(0, 20));
        },
        select: function (event, ui) {
             $('#name').val(ui.item.label); // display the selected text
             $('#rm_user_id').val(ui.item.value); // save selected id to hidden input
             return false;
         },
    });
  } );
  </script>
<script>
     var table = $('.table').DataTable( {
        fixedHeader: {
            header: true,
            footer: true
        },
        "order": [[0, "desc" ]],
        "pageLength":100,
        "lengthMenu": [100, 200, 500]
    } );
    
</script>
