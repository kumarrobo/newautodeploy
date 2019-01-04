<script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link type='text/css' rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<link type='text/css' rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<link type='text/css' rel='stylesheet' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">
<link type='text/css' rel='stylesheet' href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

<link type='text/css' rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script>


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
    table.dataTable tbody td{padding:8px 2px !important;}
</style>
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'overall_report'));?>
        <div class="sales-report-filter">
            <div class="row">
                <div id="filter-panel" class="filter-panel">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline" role="form" method="get" action="/shops/rmOverAll" id="report">
                                <div class="form-group">
                                    <div class="col-md-2">
                                    <label for="from">From </label>
                                    <input type="text" class="form-control input-sm" name="from" value="<?php echo (isset($from) && !empty($from)) ? $from : null ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="to">To</label>
                                    <input type="text" class="form-control input-sm" name="to"value="<?php echo (isset($to) && !empty($to)) ? $to : null ?>">
                                    
                                </div>
           
                                <div class="col-md-2" style="width:180px;">
                                    <label  for="services">Services</label>
                                    <select id="services" class="form-control selectpicker" name="label[]" multiple>
                                        <!--<option value="" >--Select Services--</option>-->
                                        <?php 
                                        foreach ($labels as $label_id => $label) {
                                            $selected = null;
                                            if(in_array($label_id, explode(',',$selected_label))){
                                                $selected = 'selected="selected"';
                                            }
                                            echo '<option value="'.$label_id.'" '.$selected.'>'.$label.'</option>';
                                        }?>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label  for="active_inactive">Active - Inactive</label>
                                    <select id="active_inactive" class="form-control" name="active_inactive">
                                        <option value="1" <?php if($activated=='1') echo "selected='selected'";  ?> >Active</option>
                                        <option value="0" <?php if($activated=='0') echo "selected='selected'";  ?> >In-active</option>                                       
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label  for="services">Report of </label>
                                    <select id="reporttype" class="form-control" name="reporttype">
                                        <option value="distributor" >Distributor</option>
                                        <option value="rm" <?php if($type=='rm') echo "selected='selected'";  ?>><?php if($_SESSION['Auth']['show_sd'] == 1){ echo 'Master Distributor'; }else { echo 'RM'; } ?> </option>
                                       </select>
                                </div>
                                <div class="form-group" >
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="res_type" value="csv" style="display:none" /><i title="Download data as file" class="fa fa-2x fa-file-excel-o" aria-hidden="true"></i></label>
                                      </div>
                                </div>
                                <div class="form-group" style="float:right;">
                                    <button type="submit" class="btn btn-primary" >
                                        <span class="glyphicon glyphicon-search"></span> Search
                                    </button>
                                </div>
                             </div>                                
                                
                                
                               
                            </form>
                        </div>
                        </div>
                        <?php
                                if( ($validation_error) && !empty($validation_error) ){ ?>
                                            <div class="alert alert-danger">
                                                <strong>Error!</strong> <?php echo $validation_error; ?>
                                            </div>
                                <?php } else if( isset($datas) && !empty($datas) ){ ?>
                        
                        <div>
                            
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <?php if($type!='rm') { ?>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <?php } ?>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th colspan="3" style="border-left: 1px solid #ccc;border-right: 1px solid #ccc" >Previous month average</th>
                                        <th colspan="4" style="border-right: 1px solid #ccc">Week 1 average</th>
                                        <?php if(in_array('week2', $week)){ ?>
                                        <th colspan="4" style="border-right: 1px solid #ccc">Week 2 average</th>
                                        <?php } ?>
                                        <?php if(in_array('week3', $week)){ ?>
                                        <th colspan="4" style="border-right: 1px solid #ccc">Week 3 average</th>
                                        <?php } ?>
                                       <?php if(in_array('week4', $week)){ ?>
                                        <th colspan="4" style="border-right: 1px solid #ccc">Week 4 average</th>
                                        <?php } ?>
                                        <?php if(in_array('week5', $week)){ ?>
                                        <th colspan="4" style="border-right: 1px solid #ccc">Week 5 average</th>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <th>Sr No.</th>
                                        <?php if($type!='rm') { ?>
                                        <th>Distibutor </th>
                                        <th>State</th>
                                        <?php } ?>
                                        <th><?php if($_SESSION['Auth']['show_sd'] == 1)echo "SD"; else echo "RM"; ?></th>
                                        <th>Pay1 Age</th>
                                        <th style="border-left: 1px solid #ccc">Primary</th>
                                        <th>Secondary</th>
                                        <th >Tertiary</th>
                                        <th style="border-left: 1px solid #ccc">Primary</th>
                                        <th>Secondary</th>
                                        <th ><span>Tertiary</th>
                                        <th style="border-right: 1px solid #ccc"><span>Tertiary differnce</th>
                                        <?php if(in_array('week2', $week)){ ?>
                                        <th style="border-left: 1px solid #ccc">Primary</th>
                                        <th>Secondary</th>
                                        <th >Tertiary</th>
                                        <th style="border-right: 1px solid #ccc"><span>Tertiary differnce</th>
                                        <?php } ?>
                                        <?php if(in_array('week3', $week)){ ?>
                                        <th style="border-left: 1px solid #ccc">Primary</th>
                                        <th>Secondary</th>
                                        <th >Tertiary</th>
                                        <th style="border-right: 1px solid #ccc"><span>Tertiary differnce</th>
                                        <?php } ?>
                                        <?php if(in_array('week4', $week)){ ?>
                                        <th style="border-left: 1px solid #ccc">Primary</th>
                                        <th>Secondary</th>
                                        <th >Tertiary</th>
                                        <th style="border-right: 1px solid #ccc"><span>Tertiary differnce</th>
                                        <?php } ?>
                                         <?php if(in_array('week5', $week)){ ?>
                                        <th style="border-left: 1px solid #ccc">Primary</th>
                                        <th>Secondary</th>
                                        <th >Tertiary</th>
                                        <th style="border-right: 1px solid #ccc"><span>Tertiary differnce</th>
                                         <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php  $primary=0; $secondary = 0; $tertiary= 0;
                            $i=1;
                    		foreach($datas as $id => $data) {
                                                     if(isset($data['id'])) {
                                                            
                                                         ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <?php if($type!='rm') { ?>
                                        <td onclick="alert('<?php echo $data['distributor_name']."  :  ".$data['mobile']; ?>' )" style="cursor:pointer"><?php echo $data['company']; ?> <!-- <a href="javascript:;" title="<?php echo $data['distributor_name']; ?>" data-toggle="popover" data-trigger="focus" data-content="<?php echo $data['mobile']; ?>"> <?php echo $data['company']; ?>  </a> --></td>
                                        <td > <?php echo $data['state'];?></td>
                                        <?php } ?>
                                        <td onclick="alert('<?php echo $data['rmmobile']; ?>' )" style="cursor:pointer"><?php echo $data['rmname']; ?> </td>
                                        <td><?php $created =  new DateTime($data['created_date']   ); $today = new DateTime(); 
                                                            $exp = '';
                                                            if($today->diff($created)->y>0 && $today->diff($created)->m>0){
                                                                $exp = $today->diff($created)->y.'.'.$today->diff($created)->m.' year';
                                                            }
                                                            elseif($today->diff($created)->y>0){
                                                                    $exp = $today->diff($created)->y.' year';
                                                            }
                                                            elseif( $today->diff($created)->m>0){
                                                                $exp=  $today->diff($created)->m.' month';
                                                            }
                                                            elseif($today->diff($created)->d>0){
                                                                $exp= $today->diff($created)->d.' days';
                                                            }
                                                           // if($today->diff($created)->y > 0 ){ $exp =  $today->diff($created)->y.' year '; } 
                                                            //if($today->diff($created)->m > 0 ){ $exp .=  $today->diff($created)->m.' month '; }  if($today->diff($created)->d > 0 ){ $exp .=  $today->diff($created)->d.' days '; } 
                                                            echo $exp; ?></td>
                                        <td style="border-left: 1px solid #ccc"><?php echo !empty($data['prev_primary']) ?  $data['prev_primary'] : 0;  ?> </td>
                                        <td><?php echo !empty($data['prev_secondary']) ? $data['prev_secondary']: 0;  ?> </td>
                                        <td style="border-right: 1px solid #ccc"><?php echo !empty($data['prev_tertiary']) ? $data['prev_tertiary']: 0;   ?> </td>
                                        <td><?php echo !empty($data['week1primary']) ? $data['week1primary']: 0;?> </td>
                                        <td><?php echo !empty($data['week1secondary']) ? $data['week1secondary']: 0;  ?> </td>
                                        <td  > <?php  echo !empty($data['week1tertiary']) ? $data['week1tertiary']: 0;   ?>  </td>
                                        <td style="border-right: 1px solid #ccc"><?php if($data['week1tertiary']-$data['prev_tertiary']<0){  ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php }elseif($data['week1tertiary']-$data['prev_tertiary']>0){  ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php } ?>  <b> <?php echo $data['week1tertiary']-$data['prev_tertiary']; ?></b>  </td>
                                        <?php  if(in_array('week2', $week)){?>
                                         <td><?php echo !empty($data['week2primary']) ? $data['week2primary']: 0; ?> </td>
                                        <td><?php echo !empty($data['week2secondary']) ? $data['week2secondary']: 0;  ?> </td>
                                        <td ><?php echo !empty($data['week2tertiary']) ? $data['week2tertiary']: 0;  ?>  </td>
                                        <td style="border-right: 1px solid #ccc"><?php if($data['week2tertiary']-$data['prev_tertiary']<0){  ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php }elseif($data['week2tertiary']-$data['prev_tertiary']>0){  ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php } ?> <b> <?php echo $data['week2tertiary']-$data['prev_tertiary']; ?></b>  </td>
                                        <?php } ?>
                                         <?php  if(in_array('week3', $week)){ ?>
                                        <td><?php echo !empty($data['week3primary']) ? $data['week3primary']: 0; ?> </td>
                                        <td><?php  echo !empty($data['week3secondary']) ? $data['week3secondary']: 0;  ?> </td>
                                        <td  ><?php   echo !empty($data['week3tertiary']) ? $data['week3tertiary']: 0;?></td>
                                        <td style="border-right: 1px solid #ccc"><?php if($data['week3tertiary']-$data['prev_tertiary']<0){  ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php }elseif($data['week3tertiary']-$data['prev_tertiary']>0){  ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php } ?> <b> <?php echo $data['week3tertiary']-$data['prev_tertiary']; ?></b>  </td>
                                        <?php } ?>
                                        <?php  if(in_array('week4', $week)){ ?>
                                        <td><?php  echo !empty($data['week4primary']) ? $data['week4primary']: 0; ?> </td>
                                        <td><?php  echo !empty($data['week4secondary']) ? $data['week4secondary']: 0; ?> </td>
                                        <td  ><?php echo !empty($data['week4tertiary']) ? $data['week4tertiary']: 0; ?></td>
                                        <td style="border-right: 1px solid #ccc"><?php if($data['week4tertiary']-$data['prev_tertiary']<0){  ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php }elseif($data['week4tertiary']-$data['prev_tertiary']>0){  ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php } ?> <b> <?php echo $data['week4tertiary']-$data['prev_tertiary']; ?></b> </td>
                                        <?php } ?>
                                        <?php  if(in_array('week5', $week)){ ?>
                                        <td><?php echo !empty($data['week5primary']) ? $data['week5primary']: 0; ?> </td>
                                        <td><?php  echo !empty($data['week5secondary']) ? $data['week5secondary']: 0; ?> </td>
                                        <td  ><?php echo !empty($data['week5tertiary']) ? $data['week5tertiary']: 0;  ?> </td>
                                        <td style="border-right: 1px solid #ccc"><?php if($data['week5tertiary']-$data['prev_tertiary']<0){  ?><span><i style="color:red;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i></span><?php }elseif($data['week5tertiary']-$data['prev_tertiary']>0){  ?> <span><i style="color:green;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i></span> <?php } ?> <b> <?php echo $data['week5tertiary']-$data['prev_tertiary']; ?></b> </td>
                                        <?php } ?>
                                    </tr>
                                    <?php
                                    $i++;
                                                     }
                                            }
                                ?>
                                 </tbody>
                            </table>
                        </div>
                        <?php }  else {
                                echo '<div style="text-align:center"><h3>No records Found</h3></div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <div class="sales-report-container">
   
    </div>
<br class="clearRight" />
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function(){
        $( "input[type=checkbox]" ).on( "click", function(){
            $('#report').submit();
            alert('Downloading  file');
            $( "input[type=checkbox]" ).prop('checked',false);
        }) ;
        $('.table').dataTable({
            "order": [[ 4, "desc" ]]
        });
         $('[data-toggle="popover"]').popover();   
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
         endDate: "-1d"
     });

</script>
