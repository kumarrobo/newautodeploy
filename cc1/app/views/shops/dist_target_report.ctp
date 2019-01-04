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
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'targetreport'));?>
        <div class="sales-report-filter">
            <div class="row">
                <div id="filter-panel" class="filter-panel">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline" role="form" method="get" action="/shops/distTargetReport">
                                <div class="form-group">
                                    <label class="filter-col"  for="year_month">Month</label>
                                    <input type="text" class="form-control input-sm" name="year_month" value="<?php echo isset($year_month) ? $year_month : null; ?>">
                                </div>
                                <div class="form-group" >
                                    <button type="submit" class="btn btn-xs btn-primary" >
                                        <span class="glyphicon glyphicon-search"></span> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="sales-report-container">
    <?php
        //if( !empty($target_report['target1']) ||  !empty($target_report['target2']) || !empty($target_report['achieved'])){ ?>
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th style="vertical-align: top;">Scheme Name</th>
                        <th style="vertical-align: top;">Services</th>
                        <th style="vertical-align: top;">Targets</th>
                        <th style="vertical-align: top;">Sale Achieved</th>
                        <th style="vertical-align: top;">Incentive Given</th>
                        <th style="vertical-align: top;">Scheme Type</th>
                        <th style="vertical-align: top;">Scheme Period</th>
                        <th style="vertical-align: top;">Scheme Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($target_report > 0)) { ?>
                <?php foreach($target_report as $scheme_id=>$dist_target) {
                		foreach($dist_target as $dist_id=>$target) {
                	?>
                
                    <tr>
                    	<td><?php echo $target['scheme']; ?></td>
                        <td><?php echo $target['services']; ?></td>
                        <td>
                        <?php foreach($target['target'] as $key => $val) {?>
                        <?php echo "$key : ".((isset($val['recharge'])) ? $val['recharge'] : $val['sale']) . ", Incentive: ".$val['incentive'] . "<br/>"; ?>
                        <?php } ?>
                        </td>
                        <td><?php echo $target['achieved']; ?></td>
                        <td><?php echo $target['incentive_given']; ?></td>
                        <td><?php echo $target['scheme_type']; ?></td>
                        <td><?php echo $target['scheme_start'] . " to " . $target['scheme_end']; ?></td>
                        <td>
                            <?php if($target['scheme_completed'] == 0) { 
                            	echo 'Active';
                            }
                            else {
                            	echo 'InActive';
                            }?>
                        </td>
                    </tr>
                    <?php } } } else {
            			echo '<h3>No records Found</h3>';
        			} ?>
        	</tbody>
         </table>
    </div>
<br class="clearRight" />
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script>

    $('input[name="year_month"]').datepicker({
        minViewMode:1,
        format: 'yyyy-mm',
        endDate: "0m",
        orientation: 'bottom'
    });
</script>
