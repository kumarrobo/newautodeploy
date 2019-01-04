<link type='text/css' rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
<link type='text/css' rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

<script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link type='text/css' rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<link type='text/css' rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">
<link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

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
    .progress{
        width: 100%;
        background-color: #ddd;
        height: 18px;
        border-radius: 0px;
        margin-bottom: 2px;
    }
    /* .achieved-bar{
        height: 30px;
        background-color: #4CAF50;
        text-align: center;
        line-height: 30px;
        color: white;
    }
    .expected-bar{
        height: 30px;
        background-color: red;
        text-align: center;
        line-height: 30px;
        color: white;
    } */

</style>
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'target_report'));?>
        <div class="sales-report-filter">
            <div class="row">
                <div id="filter-panel" class="filter-panel">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline" role="form" method="get" action="/shops/rmTargetReport">
                                
                                <div class="row">
                                    <div class="col-md-1"><label class="filter-col"  for="year_month">Month</label></div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control input-sm" name="year_month" value="<?php echo isset($year_month) ? $year_month : null; ?>">
                                    </div>
                                    
                                    <div class="col-md-1"><label class="filter-col"  for="dists">Label</label></div>
                                    <div class="col-md-4">
                                        <select id="dist" class="form-control selectpicker" name="label[]" multiple>
                                        <!--<option value="" >--Select Label--</option>-->
                                        <?php
                                        foreach ($labels as $label_id => $label) {
                                            $selected = null;
                                            
                                            if(in_array($label_id, explode(',',$selected_label))){
                                                $selected = 'selected="selected"';
                                            }
                                            echo '<option value="'.$label_id.'" '.$selected.'>'.$label['label'].'</option>';
                                        }?>
                                    </select>
                                    </div>
                                    
                                    <div class="col-md-3" style="float:right;">
                                        <button type="submit" class="btn btn-primary" >
                                            <span class="glyphicon glyphicon-search"></span> Search
                                        </button>
                                    </div>
                                </div>
                                
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="sales-report-container">
    <?php
        if( ($validation_error) && !empty($validation_error) ){ ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> <?php echo $validation_error; ?>
                    </div>
        <?php } else if( count($schemes) > 0 ){ ?>
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th style="vertical-align: top;">#</th>
                        <th style="vertical-align: top;"><?php if($_SESSION['Auth']['show_sd'] == 1)echo "SD"; else echo "RM"; ?></th>
                        <th style="vertical-align: top;">Slab</th>
                        <th style="vertical-align: top;">Distributor</th>
                        <th style="vertical-align: top;">Total Retailer Base</th>
                        
                        <th style="vertical-align: top;">Scheme</th>
                        <th style="vertical-align: top;">Services</th>
                        <th style="vertical-align: top;">Targets</th>
                        <th style="vertical-align: top;">Sale Achieved</th>
                        <th style="vertical-align: top;">Incentive Given</th>
                        <th style="vertical-align: top;">Scheme Period</th>
                        
                        <th style="vertical-align: top;">Target Monitoring</th>
                        <th style="vertical-align: top;">View Profile/Sales</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                foreach ($schemes as $scheme_id => $dist_data) {
                	foreach($dist_data as $dist_id => $dist) {
                $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $sales_report[$dist_id]['rm']; ?></td>
                        <td><?php echo $sales_report[$dist_id]['slab']; ?></td>
						<td><?php echo $sales_report[$dist_id]['dist_name']; ?></td>
                        <td><?php echo number_format($sales_report[$dist_id]['retailer_count']); ?></td>
                        
                        <td><?php echo $dist['scheme'] . "( ". $dist['scheme_type'] . ")"; ?></td>
                        <td><?php echo $dist['services']; ?></td>
                        <td>
                        <?php foreach($dist['target'] as $key => $val) {?>
                        <?php echo "$key : ".((isset($val['recharge'])) ? $val['recharge'] : $val['sale']) . ", Incentive: ".$val['incentive'] . "<br/>"; ?>
                        <?php } ?>
                        </td>
                        <td><?php echo $dist['achieved']; ?></td>
                        <td><?php echo $dist['incentive_given']; ?></td>
                        <td><?php echo $dist['scheme_start'] . " to " . $dist['scheme_end']; ?></td>
                        
                        
                        
                                                <td style="text-align:center;" data-order='<?php echo ( ($dist['overall_expected_percent']-$dist['overall_achieved_percent']) > 0 ) ? $dist['overall_expected_percent']-$dist['overall_achieved_percent'] : 0 ; ?>'>
                            <?php

                        if(!empty($dist['label'])){
                            echo '<span style="font-size: 14px;'.$labels[$dist["label"]]["style"].'">'.$labels[$dist["label"]]["label"].'</span>';?>
                             <div class="progress">
                             <!-- progress-bar-striped  progress-bar-striped active-->
                                <div class="progress-bar" role="progressbar" style="background-color: lightgreen;width:<?php echo ( ($dist['overall_achieved_percent']-$dist['overall_expected_percent']) > 0 ) ? $dist['overall_expected_percent'] : $dist['overall_achieved_percent'] ; ?>%"></div>
                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo ( ($dist['overall_expected_percent']-$dist['overall_achieved_percent']) > 0 ) ? $dist['overall_expected_percent']-$dist['overall_achieved_percent'] : 0 ; ?>%"></div>
                                <div class="progress-bar" role="progressbar" style="background-color: green;width:<?php echo ( ($dist['overall_achieved_percent']-$dist['overall_expected_percent']) > 0 ) ? $dist['overall_achieved_percent']-$dist['overall_expected_percent'] : 0 ; ?>%"></div>
                            </div>
                            
                        <?php }
                            ?>
                        </td>
                        <td><a title="Click to view profile" href="/shops/distProfile?dist=<?php echo $dist_id; ?>" >Profile</a> <!-- | <a title="Click to view monthly targets" href="/shops/distSales?dist=<?php echo $dist_id; ?>" >Targets</a></td> -->
                    </tr>
                <?php }} ?>
                </tbody>
            </table>

        <?php } else {
            echo '<h3>No records Found</h3>';
        }
    ?>
    </div>
<br class="clearRight" />
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function(){
        $('.table').dataTable({
            "order": [[ 4, "desc" ]],
	    "pageLength":100
        });
    });

    $('input[name="year_month"]').datepicker({
        minViewMode:1,
        format: 'yyyy-mm',
        endDate: "0m",
        orientation: 'bottom'
    });
    // $('input[name="to"]').datepicker({
    //     minViewMode:3,
    //     format: 'yyyy-mm-dd',
    //     endDate: "0m"
    // });
</script>
