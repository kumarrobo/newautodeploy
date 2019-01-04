<link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">
<style>
    thead{
        background-color: #428bca;
        color: #fff;
    }
    .sales-report-container,.sales-filter{
        margin-top: 25px;
        margin-bottom: 25px;
    }

</style>
<div>
    <?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'target_report'));?>
        <div class="sales-filter">
            <div class="row">
                <div id="filter-panel" class="filter-panel">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline" role="form" method="get" action="/shops/distSales">
                                <div class="form-group">
                                    <label class="filter-col"  for="dists">Distributor</label>
                                    <select id="dist" class="form-control" name="dist">
                                        <option value="" >--Select Distributor--</option>
                                        <?php foreach ($all_dists as $id => $name) {
                                            $selected = null;
                                            if($dist_id == $id){
                                                $selected = 'selected="selected"';
                                            }
                                            echo '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
                                        }?>
                                    </select>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="filter-col"  for="service">Service</label>
                                    <select id="service" class="form-control" name="service">
                                        <option value="" >--Select Service--</option> -->
                                        <?php
                                        /* foreach ($services as $id => $name) {
                                            $selected = null;
                                            if($selected_service == $id){
                                                $selected = 'selected="selected"';
                                            }
                                            echo '<option value="'.strtolower($name).'" '.$selected.'>'.$name.'</option>';
                                        }
                                        */
                                        ?>
                                    <!-- </select> -->
                                <!-- </div> -->
                                <div class="form-group">
                                    <label class="filter-col"  for="sale_from">From</label>
                                    <input type="text" class="form-control input-sm" name="sale_from" value="<?php echo isset($sale_from) ? $sale_from : null; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="filter-col"  for="sale_to">To</label>
                                    <input type="text" class="form-control input-sm" name="sale_to" value="<?php echo isset($sale_to) ? $sale_to : null; ?>">
                                </div>
                                <div class="form-group" style="float:right;">
                                    <button type="submit" class="btn btn-primary" >
                                        <span class="glyphicon glyphicon-search"></span> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sales-container">
            <div class="dist-details">
                Name : <?php echo '<b>'.$dist_name.'</b>'; ?>
                Mobile : <?php echo '<b>'.$dist_mobile.'</b>'; ?>
            </div>

            <?php
                if( ($validation_error) && !empty($validation_error) ){ ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> <?php echo $validation_error; ?>
                    </div>
				<?php } else if( count($sales) > 0 ){ ?>
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Recharge Target</th>
                                <th>Remit Target</th>
                                <th>Smartbuy Target</th>
                                <th>MPOS Kit Target</th>
                                <th>Target Incentives</th>
                                <!-- <th>Target2 Incentive</th> -->
                                <!-- <th>Recharge Sales</th>
                                <th>% increase/decrease</th>
                                <th>Recharge Earning</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($sale_duration_full as $key => $label) {
                                $month_year = explode('-',$key);
                                $i++;
                                ?>
                                    <tr>
                                        <th style="background-color: #428bca;color: #fff;"><?php echo $label; ?></th>
                                        <td><?php
                                            if( isset($sales[1][$month_year[0]][$month_year[1]]['targets']) && count($sales[1][$month_year[0]][$month_year[1]]['targets'])  > 0 ){
                                                foreach ($sales[1][$month_year[0]][$month_year[1]]['targets'] as $name => $value) {
                                                    echo $name.': '.number_format($value).'<br>';
                                                }
                                            }
                                        ?></td>
                                        <td><?php
                                            if( isset($sales[12][$month_year[0]][$month_year[1]]['targets']) && count($sales[12][$month_year[0]][$month_year[1]]['targets'])  > 0 ){
                                                foreach ($sales[12][$month_year[0]][$month_year[1]]['targets'] as $name => $value) {
                                                    echo $name.': '.number_format($value).'<br>';
                                                }
                                            }
                                        ?></td>
                                        <td><?php
                                            if( isset($sales[13][$month_year[0]][$month_year[1]]['targets']) && count($sales[13][$month_year[0]][$month_year[1]]['targets'])  > 0 ){
                                                foreach ($sales[13][$month_year[0]][$month_year[1]]['targets'] as $name => $value) {
                                                    echo $name.': '.number_format($value).'<br>';
                                                }
                                            }
                                        ?></td>
                                        <td><?php
                                            if( isset($sales[8][$month_year[0]][$month_year[1]]['targets']) && count($sales[8][$month_year[0]][$month_year[1]]['targets'])  > 0 ){
                                                foreach ($sales[8][$month_year[0]][$month_year[1]]['targets'] as $name => $value) {
                                                    echo $name.': '.number_format($value).' kits<br>';
                                                }
                                            }
                                        ?></td>
                                        <td><?php
                                            if( isset($sales[1][$month_year[0]][$month_year[1]]['target_incentives']) && count($sales[1][$month_year[0]][$month_year[1]]['target_incentives'])  > 0 ){
                                                foreach ($sales[1][$month_year[0]][$month_year[1]]['target_incentives'] as $name => $value) {
                                                    echo $name.': '.number_format($value).'<br>';
                                                }
                                            }
                                        ?></td>


                                        <!-- <td><?php // echo ( isset($sales[1][$month_year[0]][$month_year[1]]['sale']) && !empty($sales[1][$month_year[0]][$month_year[1]]['sale']) ) ? number_format($sales[1][$month_year[0]][$month_year[1]]['sale']) : 0; ?></td>
                                        <td><?php // echo ( isset($sales[1][$month_year[0]][$month_year[1]]['inc_dec']) && !empty($sales[1][$month_year[0]][$month_year[1]]['inc_dec']) ) ? $sales[1][$month_year[0]][$month_year[1]]['inc_dec']: '--'; ?></td>
                                        <td><?php // echo ( isset($sales[1][$month_year[0]][$month_year[1]]['earning']) && !empty($sales[1][$month_year[0]][$month_year[1]]['earning']) ) ? number_format($sales[1][$month_year[0]][$month_year[1]]['earning'] + $sales[1][$month_year[0]][$month_year[1]]['incentive']) : 0; ?></td> -->
                                    </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                <?php } else {
                    echo '<h3>No records Found</h3>';
                } ?>
        </div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script>
    $('input[name="sale_from"]').datepicker({
        minViewMode:1,
        format: 'yyyy-mm',
        endDate: "0m",
        orientation: 'bottom'
    });
    $('input[name="sale_to"]').datepicker({
        minViewMode:1,
        format: 'yyyy-mm',
        endDate: "0m",
        orientation: 'bottom'
    });
</script>