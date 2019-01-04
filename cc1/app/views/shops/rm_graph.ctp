<script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link type='text/css' rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<link type='text/css' rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<link type='text/css' rel='stylesheet' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">
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
    table.dataTable tbody td{padding:8px 0px !important;}

</style>
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'rm_dashboard'));?>
        <div class="sales-report-filter">
            <div class="row">
                <div id="filter-panel" class="filter-panel">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline" role="form" method="get" action="/shops/rmGraph">
                                
                                <div class="row">
                                    <div class="col-md-2">
                                    <label  for="from">From </label>
                                    <input type="text" class="form-control input-sm" name="from" value="<?php echo (isset($from) && !empty($from)) ? $from : null ?>">
                                </div>
                                <div class="col-md-2">
                                    <label  for="to">To</label>
                                    <input type="text" class="form-control input-sm" name="to"value="<?php echo (isset($to) && !empty($to)) ? $to : null ?>">
                                    
                                </div>
           
                                <div class="col-md-3">
                                    <label  for="services">Services</label>
                                    <select id="services" class="form-control selectpicker"  name="label[]" multiple>
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
                                <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) { ?>
                                <div class="col-md-3">
                                    <label class="filter-col"  for="rm">RM</label>
                                    <select id="rm" class="form-control" name="rm">
                                        <option value="" >--Select Rm--</option>
                                        <?php
                                        foreach ($rms as $rm_id => $rm_name) {
                                            $selected = null;
                                            if($selected_rm == $rm_id){
                                                $selected = 'selected="selected"';
                                            }
                                            echo '<option value="'.$rm_id.'" '.$selected.'>'.$rm_name['name']."(".$rm_name['mobile'].")".'</option>';
                                        }?>
                                    </select>
                                </div>
                                <?php }?>
                                
                                <div class="col-md-3">
                                    <label  for="services">Distributors</label>
                                    <select id="distributor" class="form-control" name="distributor">
                                        <option value="" >--Select Distributors--</option>
                                        <?php
                                        foreach ($distributors as $key => $val) {
                                            $selected = null;
                                            if($selected_distributor == $key){
                                                $selected = 'selected="selected"';
                                            }
                                            echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
                                        }?>
                                    </select>
                                </div>
                                
                                    <div class="col-md-2" style="float:right;">
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
                                <?php } else if(empty($norecord)){
                                            echo '<div style="text-align:center"><h3>No records Found</h3></div>';
                                }
                                else if( isset($datas) && !empty($datas['data']) ){  ?>
                                <?php
                                                echo $this->GChart->start('test2');
                                                echo $this->GChart->visualize('test2', $datas);
                                                ?>
                                <!--graph-->
                        <?php }  elseif(!empty($norecord) && empty($datas)) {
                                echo '<div style="text-align:center"><h3>No records Found</h3></div>';
                            }
                        ?>
                                 <?php
                                            if(isset($trans_retailer) && !empty($trans_retailer)){
                                                echo $this->GChart->start('test3');
                                                echo $this->GChart->visualize('test3', $trans_retailer);
                                            }
                                            elseif((!empty($norecord) && empty($trans_retailer)))
                                            {
                                                echo '<div style="text-align:center"><h3>No records Found for Transacting Retailer</h3></div>';
                                            }
                                                ?>
                                 <?php
                                            if(isset($avg_sale) && !empty($avg_sale)){
                                                echo $this->GChart->start('test4');
                                                echo $this->GChart->visualize('test4', $avg_sale);
                                            }
                                            elseif((!empty($norecord) && empty($avg_sale)))
                                            {
                                                echo '<div style="text-align:center"><h3>No records Found for Average Sale per Retailer</h3></div>';
                                            }
                                                ?>

                                                <?php
                                            if(isset($no_of_transaction) && !empty($no_of_transaction)){
                                                echo $this->GChart->start('test5');
                                                echo $this->GChart->visualize('test5', $no_of_transaction);
                                            }
                                            elseif((!empty($norecord) && empty($no_of_transaction)))
                                            {
                                                echo '<div style="text-align:center"><h3>No records Found for No. of Transaction</h3></div>';
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
