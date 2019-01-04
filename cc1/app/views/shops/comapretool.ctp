<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link type='text/css' rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">

<style>
    thead{
        background-color: #428bca;
        color: #fff;
    }
    .sales-report-container,.sales-report-filter{
        margin-top: 25px;
        margin-bottom: 25px;
    }
    th,td{
        text-align:center;
    }
    table.dataTable tbody td{padding:8px 0px !important;}
div.form-group { margin-left:10px }
div label  { margin-left: 10px;margin-right:10px }
</style>
<div>
    
    <?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'compare_tool'));?>
        <div class="sales-report-filter">
            <div class="row">
                <div id="filter-panel" class="filter-panel">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline" role="form" method="get" action="/shops/rmComapreTool">
                                
                                <div class="form-group">
                                    <label class="filter-col"  for="services">Compare by </label>
                                    <select id="compare" class="form-control" name="compare">
                                        <?php $selected = 'selected="selected"'; ?>
                                        <option value="date" <?php echo ($compareBy=='date') ? $selected : ''; ?>>date</option>
                                        <option value="month" <?php echo ($compareBy=='month') ? $selected : ''; ?>>month</option>
                                        <option value="week" <?php echo ($compareBy=='week') ? $selected : ''; ?>>week</option>
                                    </select>
                                </div>
                                <div  class="form-group showdate" <?php if(isset($compareBy) && $compareBy!='date'){ echo "style='display:none'"; } ?>>
                                        <div class="form-group">
                                            <label class="filter-col"  for="from">Date 1 </label>
                                            <input type="text" class="form-control input-sm" name="from" value="<?php echo (isset($from) && !empty($from)) ? $from : null ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="filter-col"  for="to">Date 2</label>
                                            <input type="text" class="form-control input-sm" name="to"value="<?php echo (isset($to) && !empty($to)) ? $to : null ?>">

                                        </div>
                                </div>
                                
                                <div  class="form-group showweek" <?php if(!isset($compareBy) || $compareBy!='week'){ echo "style='display:none'"; } ?>>
                                        <div class="form-group">
                                            <label class="filter-col"  for="from_week">Week 1 </label>
                                             <input type="text" id="week1" name="week1" class="form-control input-sm" style="width:40px" readonly  value="<?php echo (isset($week1) && !empty($week1)) ? "$week1" : null ?>" />
                                            <input type="text" class="form-control input-sm" name="from_week" value="<?php echo (isset($from_week) && !empty($from_week)) ? "$from_week" : null ?>">
                                        </div>
                                    
                                        <div class="form-group">
                                            <label class="filter-col"  for="to_week">Week 2</label>
                                            <input type="text" id="week2" name="week2" class="form-control input-sm" style="width:40px" readonly  value="<?php echo (isset($week2) && !empty($week2)) ? "$week2" : null ?>" />
                                            <input type="text" class="form-control input-sm" name="to_week" value="<?php echo (isset($to_week) && !empty($to_week)) ? $to_week : null ?>">

                                        </div>
                                    
                                </div>
                                
                                <div  class="form-group showmonth"  <?php if(!isset($compareBy) || $compareBy!='month'){ echo "style='display:none'"; } ?>>
                                        <div class="form-group">
                                            <label class="filter-col"  for="from_month">Month 1 </label>
                                            <input type="text" class="form-control input-sm" name="from_month" value="<?php echo (isset($from_month) && !empty($from_month)) ? "$from_month" : null ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="filter-col"  for="to_month">Month 2</label>
                                            <input type="text" class="form-control input-sm" name="to_month" value="<?php echo (isset($to_month) && !empty($to_month)) ? $to_month : null ?>">

                                        </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="filter-col"  for="services">Services</label>
                                    <select id="services" class="form-control" name="label">
                                        <option value="" >--Select Services--</option>
                                        <?php
                                        foreach ($labels as $label_id => $label) {
                                            $selected = null;
                                            if($selected_label == $label_id){
                                                $selected = 'selected="selected"';
                                            }
                                            echo '<option value="'.$label_id.'" '.$selected.'>'.$label.'</option>';
                                        }?>
                                    </select>
                                </div>
                                <p></p>
                               <br/>
                                <div class="form-group">
                                    <label class="filter-col"  for="basic_compare">Basic comparision</label>
                                    <select id="basic_compare" class="form-control" name="basic_compare">
                                        <?php $selected = 'selected="selected"'; ?>
                                        <option value="all" <?php echo ($basicLabel=='all') ? $selected : ''; ?>>All</option>
                                        <option value="state" <?php echo ($basicLabel=='state') ? $selected : ''; ?>>State</option>
                                        <option value="city" <?php echo ($basicLabel=='city') ? $selected : ''; ?>>City</option>
                                        <option value="rm" <?php echo ($basicLabel=='rm') ? $selected : ''; ?> ><?php if($_SESSION['Auth']['show_sd'] == 1){ echo 'Master Distributor'; }else { echo 'RM'; } ?></option>
                                        <option value="distributor"  <?php echo ($basicLabel=='distributor') ? $selected : ''; ?> >Distributor</option>
<!--                                        <option value="application" <?php echo ($basicLabel=='application') ? $selected : ''; ?> >Application</option>
                                        <option value="operator" <?php echo ($basicLabel=='operator') ? $selected : ''; ?> >Operator</option>-->
                                      </select>
                                </div>
                               
                               <div class="form-group" >
                                    <button type="submit" class="btn btn-primary" >
                                        <span class="glyphicon glyphicon-search"></span> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                        </div>
                        <?php
                                if( ($validation_error) && !empty($validation_error) ){ ?>
                                            <div class="alert alert-danger">
                                                <strong>Error!</strong> <?php echo $validation_error; ?>
                                            </div>
                                <?php } else if( isset($overviewdata) && !empty($overviewdata) ){ ?>
                                    <div>
                                        <div style="text-align: center; padding: 10px;"><b>Overview Report</b></div>
                                        <table class="table table-striped table-responseive">
                                            <thead>
                                            <th>&nbsp;</th>
                                            <th>Compare 1</th>
                                            <th>Compare 2</th>
                                            <th>Difference</th>
                                            <th>Percentage</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Sale</td>
                                                    <td><?php echo !empty($overviewdata[0]['sell']) ? $overviewdata[0]['sell'] : 0; ?></td>
                                                    <td><?php echo !empty($overviewdata[1]['sell']) ? $overviewdata[1]['sell'] : 0; ?></td>
                                                    <td><?php if(($overviewdata[1]['sell'] - $overviewdata[0]['sell'])>0){ ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php }elseif(($overviewdata[1]['sell'] - $overviewdata[0]['sell'])<0){ ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php } ?><?php echo $overviewdata[1]['sell'] - $overviewdata[0]['sell'];  ?></td>
                                                    <td><?php   if(!empty($overviewdata[0]['sell'])){ 
                                                                                if($overviewdata[1]['sell'] - $overviewdata[0]['sell']>0) {
                                                                                    echo '<span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span>';
                                                                                }
                                                                                elseif($overviewdata[1]['sell'] - $overviewdata[0]['sell']<0) {
                                                                                     echo  '<span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span> ';
                                                                                }
                                                                                echo round(($overviewdata[1]['sell']- $overviewdata[0]['sell'])*100/$overviewdata[0]['sell']). "%" ;
                                                                             } else{ echo  "0 %" ; }?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>Transacting Retailer</td>
                                                    <td><?php echo !empty($overviewdata[0]['transaction_retailer']) ? $overviewdata[0]['transaction_retailer'] : 0; ?></td>
                                                    <td><?php echo  !empty($overviewdata[1]['transaction_retailer']) ? $overviewdata[1]['transaction_retailer'] : 0; ?></td>
                                                    <td><?php if(($overviewdata[1]['transaction_retailer'] - $overviewdata[0]['transaction_retailer'])>0){ ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php }elseif(($overviewdata[1]['transaction_retailer'] - $overviewdata[0]['transaction_retailer'])<0){ ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php } ?>
                                                        <?php echo $overviewdata[1]['transaction_retailer'] - $overviewdata[0]['transaction_retailer'];  ?></td>
                                                    <td><?php   if(!empty($overviewdata[0]['transaction_retailer'])){ 
                                                                                if($overviewdata[1]['transaction_retailer'] - $overviewdata[0]['transaction_retailer']>0) {
                                                                                    echo '<span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span>';
                                                                                }
                                                                                elseif($overviewdata[1]['transaction_retailer'] - $overviewdata[0]['transaction_retailer']<0) {
                                                                                     echo  '<span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span> ';
                                                                                }
                                                                                echo round(($overviewdata[1]['transaction_retailer']- $overviewdata[0]['transaction_retailer'])*100/$overviewdata[0]['transaction_retailer']). "%" ;
                                                                             } else{ echo  "0 %" ; }?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>Avg Sale / Retailer</td>
                                                    <td><?php echo !empty($overviewdata[0]['avg']) ? $overviewdata[0]['avg'] : 0; ?></td>
                                                    <td><?php echo  !empty($overviewdata[1]['avg']) ? $overviewdata[1]['avg']: 0; ?></td>
                                                    <td><?php if(($overviewdata[1]['avg'] - $overviewdata[0]['avg'])>0){ ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php }elseif(($overviewdata[1]['avg'] - $overviewdata[0]['avg'])<0){ ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php } ?>
                                                        <?php echo $overviewdata[1]['avg'] - $overviewdata[0]['avg'] . " (".round(($overviewdata[1]['avg'] - $overviewdata[0]['avg'])*100/$overviewdata[0]['avg']). "%)" ;  ?></td>
                                                    <td><?php   if(!empty($overviewdata[0]['avg'])){ 
                                                                                if($overviewdata[1]['avg'] - $overviewdata[0]['avg']>0) {
                                                                                    echo '<span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span>';
                                                                                }
                                                                                elseif($overviewdata[1]['avg'] - $overviewdata[0]['avg']<0) {
                                                                                     echo  '<span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span> ';
                                                                                }
                                                                                echo round(($overviewdata[1]['avg']- $overviewdata[0]['avg'])*100/$overviewdata[0]['avg']). "%" ;
                                                                             } else{ echo  "0 %" ; }?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <!--graph-->
                        <?php }        ?>
                        <?php if(isset($data) && !empty($data)){   ?>
                                <div class="table-div" >
                                 <?php if($basicLabel=='state' || $basicLabel=='all'){  ?>
                                <div  style="text-align: center; padding:  10px 0px;"><b>State Compare Report</b></div>
                                    <table class="table table-striped table-responseive">
                                        <thead>
                                                <th>State</th>
                                                <th>Compare 1</th>
                                                <th>Compare 2</th>
                                                <th>Difference</th>
                                                <th>Percent</th>
                                        </thead>
                                        <tbody>
                                                 <?php foreach($state as $key=>$val) { 
                                                     $stateId =$val['state_id']; ?>
                                                <tr onclick="showCompareBy('state','<?php  echo $stateId; ?>')" style="cursor:pointer">
                                                    <td><?php echo $key; ?></td>
                                                    <td><?php echo !empty($val['comp1']) ? $val['comp1']: 0; ?></td>
                                                    <td><?php echo !empty($val['comp2']) ? $val['comp2'] : 0; ?></td>
                                                    <td><?php if($val['difference']>0){ ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php }elseif($val['difference']<0){ ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php } ?><?php echo $val['difference'];  ?></td>
                                                    <td><?php   if(!empty($val['comp1'])){ 
                                                       if($val['comp2'] - $val['comp1']>0) {
                                                           echo '<span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span>';
                                                       }
                                                       elseif($val['comp2'] - $val['comp1']<0) {
                                                            echo  '<span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span> ';
                                                       }
                                                       echo round(($val['comp2'] - $val['comp1'])*100/$val['comp1']). "%" ;
                                                    } else{ echo  "0 %" ; }?></td>
                                               </tr>
                                                 <?php } ?>
                                        </tbody>
                                    </table>
                                <!--<div style="float:right;margin: 10px" class="btn btn-primary showstate">Read More</div>-->
                                 <?php }  ?>
                                
                                <?php  if($basicLabel=='distributor' || $basicLabel=='all'){   ?>
                                <div  style="text-align: center; padding:  10px 0px;"><b>Distributor Comapre Report</b></div>
                                    <table class="table table-striped table-responseive">
                                        <thead>
                                                <th>Distributor</th>
                                                <th><?php if($_SESSION['Auth']['show_sd'] == 1)echo "SD"; else echo "RM"; ?></th>
                                                <th>State</th>
                                                <th>Compare 1</th>
                                                <th>Compare 2</th>
                                                <th>Difference</th>
                                                <th>Percent</th>
                                        </thead>
                                        <tbody>
                                                 <?php foreach($distributor as $distKey=>$distval) { 
                                                     $distId = $distval['id']; ?>
                                            <tr onclick="showCompareBy('distributor','<?php  echo $distId; ?>')" style="cursor:pointer">
                                                     <td><?php echo $distKey;  ?></td>
                                                     <td><?php echo $distval['rm'];  ?></td>
                                                     <td><?php echo $distval['state'];  ?></td>
                                                    <td><?php echo !empty($distval['comp1']) ? ($distval['comp1']) : 0; ?></td>
                                                    <td><?php echo !empty($distval['comp2']) ? $distval['comp2'] : 0; ?></td>
                                                    <td><?php if($distval['difference']>0){ ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php }elseif($distval['difference']<0){ ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php } ?><?php echo $distval['difference'];  ?></td>
                                                    <td><?php   if(!empty($distval['comp1'])){ 
                                                                                if($distval['comp2'] - $distval['comp1']>0) 
                                                                                {
                                                                                    echo '<span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span>';
                                                                                }
                                                                                elseif($distval['comp2'] - $distval['comp1']<0)
                                                                                {
                                                                                    echo '<span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span> ';
                                                                                }
                                                                                echo round(($distval['comp2'] - $distval['comp1'])*100/$distval['comp1']). "%" ;
                                                            } else{ echo  "0 %" ; }?></td>
                                                </tr>
                                                 <?php } ?>
                                        </tbody>
                                    </table>
                                <!--<div style="float:right;margin: 10px" class="btn btn-primary showdistributors">Read More</div>-->
                                 <?php }  ?>
                                
                                
                                <?php if(strtoupper($basicLabel)=='RM' || $basicLabel=='all'){  ?>
                                <div  style="text-align: center; padding:  10px 0px;"><b><?php if($_SESSION['Auth']['show_sd'] == 1)echo "SD"; else echo "RM"; ?>Compare Report</b></div>
                                    <table class="table table-striped table-responseive">
                                        <thead>
                                                <th><?php if($_SESSION['Auth']['show_sd'] == 1)echo "SD"; else echo "RM"; ?></th>
                                                
                                                <th>Compare 1</th>
                                                <th>Compare 2</th>
                                                <th>Difference</th>
                                                <th>Percent</th>
                                        </thead>
                                        <tbody>
                                                 <?php foreach($rm as $rmkey=>$rmval) { ?>
                                                <tr onclick="showCompareBy('RM','<?php  echo $rmval['id']; ?>')" style="cursor:pointer">
                                                     <td><?php echo $rmkey;  ?></td>
                                                    <td><?php echo !empty($rmval['comp1']) ? ($rmval['comp1']) : 0; ?></td>
                                                    <td><?php echo !empty($rmval['comp2']) ? $rmval['comp2'] : 0; ?></td>
                                                    <td><?php if($rmval['difference']>0){ ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php }elseif($rmval['difference']<0){ ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php } ?><?php echo $rmval['difference'];  ?></td>
                                                     <td><?php   if(!empty($rmval['comp1'])){ 
                                                                                    if($rmval['comp2'] - $rmval['comp1']>0)
                                                                                    {
                                                                                        echo '<span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span>';
                                                                                    }
                                                                                    elseif ($rmval['comp2'] - $rmval['comp1']<0) 
                                                                                    {
                                                                                         echo  '<span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span> '  ;
                                                                                    }
                                                                        echo round(($rmval['comp2'] - $rmval['comp1'])*100/$rmval['comp1']). "%" ;
                                                                     }
                                                                     else{ echo  "0 %" ; }?></td>
                                                </tr>
                                                 <?php } ?>
                                        </tbody>
                                    </table>
                                <!--<div style="float:right;margin: 10px" class="btn btn-primary showdistributors">Read More</div>-->
                                 <?php }  ?>
                                
                                <?php if($basicLabel=='city' || $basicLabel=='all'){  ?>
                                <div  style="text-align: center; padding:  10px 0px;"><b>City Compare Report</b></div>
                                    <table class="table table-striped table-responseive">
                                        <thead>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Compare 1</th>
                                                <th>Compare 2</th>
                                                <th>Difference</th>
                                                <th>Percent</th>
                                        </thead>
                                        <tbody>
                                                 <?php foreach($city as $cityKey=>$cityval) { 
                                                     $cityId = $cityval['id'];
                                                     ?>
                                                <tr onclick="showCompareBy('city','<?php  echo $cityId; ?>')"  style="cursor:pointer">
                                                    <td><?php echo $cityKey;  ?></td>
                                                     <td><?php echo $cityval['state'];  ?></td>
                                                    <td><?php echo !empty($cityval['comp1']) ? ($cityval['comp1']) : 0; ?></td>
                                                    <td><?php echo !empty($cityval['comp2']) ? $cityval['comp2'] : 0; ?></td>
                                                    <td><?php if($cityval['difference']>0){ ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php }elseif($cityval['difference']<0){ ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php } ?><?php echo $cityval['difference'];  ?></td>
                                                    <td><?php   if(!empty($cityval['comp1'])){ 
                                                                                if($cityval['comp2'] - $cityval['comp1']>0)
                                                                                {
                                                                                    echo '<span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span>';
                                                                                }
                                                                                elseif($cityval['comp2'] - $cityval['comp1']<0)
                                                                                {
                                                                                    echo '<span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span> ';

                                                                                }
                                                                        echo round(($cityval['comp2'] - $cityval['comp1'])*100/$cityval['comp1']). "%" ;
                                                                     }
                                                                     else{ echo  "0 %" ; }?></td>
                                                </tr>
                                                 <?php } ?>
                                        </tbody>
                                    </table>
                                <!--<div style="float:right;margin: 10px" class="btn btn-primary showdistributors">Read More</div>-->
                                 <?php }  ?>
                                     <?php }  ?>
                                
                                  
                                </div>
                              
                                
                    </div>
                </div>
            </div>
        </div>
    <div class="sales-report-container">
   
    </div>
<br class="clearRight" />
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function(){
        $('.table').dataTable({
            "order": [[ 1, "desc" ],[0, "desc" ]],
           //"ordering": false,
           "pageLength":5,
           "lengthMenu": [5, 10, 25, 50, 100],
           "info": false
        });
         $('[data-toggle="popover"]').popover();   
         
         $("#compare").on('change',function(event){ 
             console.log($("#compare").val());
              if($("#compare").val()=='date'){ 
                  $('.showdate').show();  
                  $('.showmonth').hide();
                  $('.showweek').hide();
                  $('#from_month').val('');
                  $('#to_month').val('');
                  $('#from_week').val('');
                  $('#to_week').val('');
              }
              else if($("#compare").val()=='month'){ 
                  $('.showmonth').show(); 
                  $('.showdate').hide();
                  $('.showweek').hide();
                  $('#from').val('');
                  $('#to').val('');
                  $('#from_week').val('');
                  $('#to_week').val('');
              }
               else if($("#compare").val()=='week'){ 
                   console.log('week selected');
                   $('.showweek').show();
                  $('.showmonth').hide(); 
                  $('.showdate').hide();
                  $('#from_month').val('');
                  $('#to_month').val('');
                  $('#from').val('');
                  $('#to').val('');
              }
         });
         
         $('input[name="from"]').datepicker({
        minViewMode:3,
        format: 'yyyy-mm-dd',
        endDate: "-1d",
        orientation: 'bottom'
    });
     $('input[name="to"]').datepicker({
         minViewMode:3,
         format: 'yyyy-mm-dd',
         endDate: "-1d",
     });
     $('input[name="from_month"]').datepicker({
                format: "mm-yyyy",
                viewMode: "months", 
                minViewMode: "months",
                endDate: "-1d",
    });
     $('input[name="to_month"]').datepicker({
                format: "mm-yyyy",
                viewMode: "months", 
                minViewMode: "months",
                endDate: "-1d",
     });
     $('input[name="from_week"]').datepicker({
                minViewMode:3,
                format: 'yyyy-mm-dd',
                endDate: "-1d",
    }).on('change',function(e){
         var today = new Date($('input[name="from_week"]').datepicker( "getDate" ));
        $("#week1").val( Math.ceil(today.getDate()/7)  );
       // $("#week1").parent().show();
     });
     $('input[name="to_week"]').datepicker({
                minViewMode:3,
                format: 'yyyy-mm-dd',
                endDate: "-1d",
     }).on('change',function(e){
         var today = new Date($('input[name="to_week"]').datepicker( "getDate" ));
        $("#week2").val( Math.ceil(today.getDate()/7)  );
        //$("#week2").parent().show();
     });
     
         

    });
function showCompareBy(key,value){
//    $('input#h_'+key).remove();
//    $('<input/>', {'name':'h_'+key,'type': 'hidden','value':value}).appendTo("button");
//    
//    $( "form" ).submit();

var url = window.location.href+'&'+key+'='+value;
window.open(url);
}

function showRm(state){
    $('input#h_state').remove();
    $('<input/>', {'id':'h_state','type': 'hidden','value':state}).appendTo("body");
    $.ajax({url : window.location.origin+'/shops/getRm'+window.location.search+'&state='+state}).then(res=>{
        var data = JSON.parse(res);
        var html='<div style="text-align: center; padding: 10px;"><b>RM List Report</b></div><table class="rm-list table table-striped table-responseive ">';
        html +='<thead>';
         html +='<tr>';
         html +='<th>RM</th><th>State</th><th>Compare 1</th><th>Compare 2</th><th>Difference</th>';
         html +='</tr>';
         html +='</thead>';
         html +='<tbody>';
        $.each(data,function(index, value){
            html+='<tr onclick="showDistributor('+index+')"><td>'+value.comp1.name+'</td><td>'+value.state+'</td>';
            html+='<td>'+value.comp1.sale+'</td><td>'+value.comp2.sale+'</td><td>'+(value.comp2.sale-value.comp1.sale)+'</td></tr>';
        });
        html +='</tbody>';
        html+='</table>';
        
        $('div .rm-list').html(html);
        $('div .table-div').hide();
        
        $('.table.rm-list').dataTable({
           // "order": [[ 4, "desc" ]]
        });
         $('[data-toggle="popover"]').popover();   
         
    });
    
}
    
function showDistributor(rmId){
    $('div .rm-list').hide();
    var state = $('input#h_state').val();
    $.ajax({url : window.location.origin+'/shops/getDistributors'+window.location.search+'&state='+state+'&rm='+rmId}).then(res=>{
        var data = JSON.parse(res);
        console.log(data);
        var html='<div style="text-align: center; padding: 10px;"><b>Distributor List Report</b></div><table class="dist-list table table-striped table-responseive ">';
        html +='<thead>';
        html +='<tr>';
        html +='<th>Company</th><th>RM</th><th>State</th><th>Compare 1</th><th>Compare 2</th><th>Difference</th>';
        html +='</tr>';
        html +='</thead>';
        html +='<tbody>';
        
        $.each(data,function(index, value){
            html+='<tr ><td>'+value.comp1.company+'</td><td>'+value.comp1.rm+'</td>';
            html+='<td>'+value.comp1.state+'</td><td>'+value.comp1.sale+'</td><td>'+value.comp2.sale+'</td><td>'+(value.comp2.sale-value.comp1.sale)+'</td></tr>';
        });
        html +='</tbody>';
        html+='</table>';
        
        $('div .distributor-list').html(html);
        $('div .rm-list').hide();
        console.log(html);
        $('.table.dist-list').dataTable({
           // "order": [[ 4, "desc" ]]
        });
         $('[data-toggle="popover"]').popover();   
         
    });
    
}
</script>
