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
	
    <div class="incentive-report-container">
        <div><h3>Scheme - <?php echo $data['scheme_info']['name'];?></h3></div>
        <div class="panel panel-default">
            <div class="panel-heading">Notification</div>
            <div class="panel-body">

        
        <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="col-md-5 control-label" for="services">Type</label>
                            <div class="col-md-7">
                                <select class="form-control" id="notify_type" name="notify_type">                                            
                                    <option value="1" <?php if($params['notify_type'] == 1){ echo "selected"; }?>>Scheme</option>
                                    <option value="2" <?php if($params['notify_type'] == 2){ echo "selected"; }?>>Reminder</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="col-md-5 control-label" for="services">Channel</label>
                            <div class="col-md-7">
                                <select class="form-control" id="notify_channel" name="notify_channel">                                            
                                    <option value="1" <?php if($params['notify_channel'] == 1){ echo "selected"; }?>>SMS</option>
                                    <option value="2" <?php if($params['notify_channel'] == 2){ echo "selected"; }?>>Notification</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success" onclick="sendNotification()">Send Notification</button>
                        </div>
                    </div>
        </div>
                </div>
    </div>
    	
        <?php if( count($data) > 0 ){ ?>
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th style="vertical-align: top;" rowspan="2" scope="col">#</th>
                       <?php foreach($data['old_data_cols'] as $cols){ ?>
                        <th style="vertical-align: top;" rowspan="2" scope="col"><?php echo $cols;?></th>
                       <?php } ?>
                        <th style="vertical-align: top;" rowspan="2" scope="col">Achieved</th>
                       <?php foreach($data['new_data_cols'] as $cols){ 
                       	if($cols == 'datewise_sale'){ 
                       	    $col_span = date_diff(date_create($data['scheme_dates']['from_date']),date_create($data['scheme_dates']['to_date']))->format('%a');
                       		$col_span += 1;
                       	?>
                       		<th style="vertical-align: top;" colspan="<?php echo $col_span;?>" scope="col"><?php echo $cols;?></th>
                       	<?php } else { ?>
                       	<th style="vertical-align: top;" rowspan="2" scope="col"><?php echo $cols;?></th>
                       	
                       <?php }} ?>
                       
                    </tr>
                    <tr>
                    	<?php $date = $data['scheme_dates']['from_date'];
                    	  while($data['scheme_dates']['to_date'] != $date) {?>
                        <th style="vertical-align: top;"><?php echo $date;?></th>
                        <?php 
                          $date = date('Y-m-d',strtotime($date . "+ 1 days"));
                        } ?>
                       
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                foreach ($data['old_data'] as $ret_id => $val) {
                $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <?php foreach($data['old_data_cols'] as $col){                            
                            if($col == 'retailer_id'){ ?>
                            <td><a target="_blank" href="/panels/retInfo/temp/<?php echo $val[$col]; ?>/<?php echo date("d-m-Y", strtotime("-1 day"));?>/<?php echo date("d-m-Y");?>"><?php echo $val[$col]; ?></a></td>
                            <?php }
                            else{?>
                            <td><?php echo $val[$col]; ?></td>
                        <?php }}?>

                    	<td><?php echo $data['new_data'][$ret_id]['total_sale']; ?></td>
                    	
                    	<?php foreach($data['new_data_cols'] as $col){ 
                       	if($col == 'datewise_sale'){ 
                       		$date = $data['scheme_dates']['from_date'];
                       		while($data['scheme_dates']['to_date'] != $date) {?>
                       		
                       	<td><?php echo $data['new_data'][$ret_id]['datewise_data'][$date]['sale']; ?></td>
                       		
                       	<?php 
                                $date = date('Y-m-d',strtotime($date . "+ 1 days"));
                        	}
                       	} else { ?>
                       	<td><?php echo $data['new_data'][$ret_id][$col]; ?></td>
                       	<?php }} ?>
                    	
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        <?php } else {
            echo '<h3>No records Found</h3>';
        }
    ?>
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
            "columnDefs": [{
            "defaultContent": "-",
            "targets": "_all"
            }],
            "order": [[ 3, "desc" ]],
            "pageLength":1000,
            "lengthMenu": [ 10, 100, 1000 ]
        });
    });
    function sendNotification()
    {
        var notify_type = $('#notify_type').val();
        var notify_channel = $('#notify_channel').val();
        window.location = "/incentives/schemePanel/<?php echo $scheme_id?>/"+notify_type+"/"+notify_channel;
    }
</script>