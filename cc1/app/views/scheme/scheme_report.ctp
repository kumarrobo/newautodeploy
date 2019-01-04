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
	
        <div class="sales-report-filter">
            <div class="row">
                <div id="filter-panel" class="filter-panel">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline" role="form" method="get" action="/scheme/schemeReport/<?php echo $schemeId;?>/<?php echo $dist_id;?>">
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
                            <?php if(!empty($schemeId)) { ?>
                            	<button type="button" class="btn btn-primary btn-sm" onclick="sendNotification(<?php echo $schemeId; ?>,'<?php echo $year_month; ?>')">Send Notification</button>
                           	<?php } ?>                             
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="sales-report-container">
    
            <table class="table table-striped table-responsive">
                <thead>
                	<?php if(empty($schemeId)) { ?>
                    <tr>
                        <th style="vertical-align: top;">Scheme Name</th>
                        <th style="vertical-align: top;">Services</th>
                        <th style="vertical-align: top;">Distributors</th>
                        <th style="vertical-align: top;">Scheme Type</th>
                        <th style="vertical-align: top;">Targets</th>
                        
                        
                    </tr> 
                    <?php } else { ?>
                   	<tr>
                        <th style="vertical-align: top;">Scheme Name</th>
                        <th style="vertical-align: top;">Distributor</th>
                        <th style="vertical-align: top;">Services</th>
                        <th style="vertical-align: top;">Targets</th>
                        <th style="vertical-align: top;">Sale Achieved</th>
                        <th style="vertical-align: top;">Incentive Given</th>
                        <th style="vertical-align: top;">Scheme Type</th>
                        <th style="vertical-align: top;">Scheme Period</th>
                        <th style="vertical-align: top;">Scheme Status</th>
                    </tr> 
                    <?php } ?>
                </thead>
                <tbody>
                <?php foreach($target_report as $scheme_id=>$dist_target) {
                	if(empty($schemeId)) { 
                		$firstKey = array_keys($dist_target);
                		$firstKey = $firstKey[0];
                	?>
                	<tr>
                		<td><a href="/scheme/schemeReport/<?php echo $scheme_id;?>"><?php echo $dist_target[$firstKey]['scheme']; ?></a></td>
                        <td><?php echo $dist_target[$firstKey]['services']; ?></td>
                        <td><?php echo count($dist_target); ?></td>
                        <td><?php echo $dist_target[$firstKey]['scheme_type']; ?></td>
                        <td>
                        <div>
                        <?php foreach($dist_target[$firstKey]['scheme_data'] as $slab=>$slab_data){  ?>
                        	<div class='col-md-12'>
                        		<?php $i = "";foreach($slab_data['target'] as $key => $target){
                        			if(count($slab_data['target']) >1){
                        				$i = $key + 1;
                        			}
                        			if($key == 0) {echo "<div class='col-md-3'>". $slab ."</div>";}
                        			else {echo "<div></div>";}
                        			
                        		 	echo "<div class='col-md-3'>Target$i : ".$target . ", Incentive: ".$slab_data['incentive'][$key] . "</div>";
                        			
                        		} ?>
                        	</div>
                        <?php } ?>
                        </div>
                        </td>
                	</tr>
                	<?php } else {
                		foreach($dist_target as $dist_id=>$target) {
                	?>
                
                    <tr>
                    	<td><?php echo $target['scheme']; ?></td>
                    	<td><?php echo $target['company']; ?></td>
                        <td><?php echo $target['services']; ?></td>
                        <td>
                        <?php foreach($target['target'] as $key => $val) {?>
                        <?php echo "$key : ".((isset($val['recharge'])) ? $val['recharge'] : $val['sale']) . ", Incentive: ".$val['incentive'] . "<br/>"; ?>
                        <?php } ?>
                        </td>
                        <td><?php echo $target['achieved']; ?></td>
                        <td><?php if($target['incentive_given'] > 0) { echo $target['incentive_given']; } else if($target['scheme_completed'] > 0 && $target['settlement_flag'] == 1){ 
                        	$percent = (int)($target['achieved']*100/$target['target']['target1']['sale']);
                        	if($percent >= MANUAL_DIST_INCENTIVE_LIMIT){ echo "$percent%"; 
                        ?>
                        	<button type="button" class="btn btn-primary btn-sm" onclick="giveIncentive(<?php echo $target['scheme_id']; ?>)">Manual Incentive</button>
                                                        
                        <?php }} ?></td>
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
                    <?php } } }?>
        	</tbody>
         </table>
         
         <!-- Send notification modal -->
                      <div class="modal fade" id="sendNotif" role="dialog">
                        <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                              <form  method="post" name="sendnotifscheme" id="sendnotifscheme">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Send Notification</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 error-msg">
                                        
                                    </div>
                                </div>
                                <div class="row">
                                      <div class="col-md-6">
                                          <input type="text" class="form-control" name="notif_title" placeholder="notif_title" required>
                                      </div>
                                </div>
                                    
                            </div>
                            <div class="modal-footer">
                                
                                <button type="submit" class="btn btn-primary">Submit </button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                              </form>
                        
                          </div>

                        </div>
                      </div>
                <!-- Send notification Close Modal -->
    </div>
<br class="clearRight" />
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script>

    $('input[name="year_month"]').datepicker({
        minViewMode:1,
        format: 'yyyy-mm',
        orientation: 'bottom'
    });
    
    function giveIncentive(schemeTargetId){
     
     if(confirm("Are you sure, you want to give incentive manually?")){
            $.ajax({
		url: window.location.protocol+'//'+window.location.hostname+'/scheme/giveDistributorScheme',
                type: 'get',
                dataType: 'json',
                success: function (data) {
                   alert(data.msg);
                   window.location.href = "";
                },
                data: {'schemetarget_id':schemeTargetId}
        });
     }
     
    }
    
    
    $('#sendnotifscheme').on('submit',function(event){
         event.preventDefault();
         var formData = $('form#sendnotifscheme').serializeArray();
        
        $.ajax({
            url: window.location.protocol+'//'+window.location.hostname+'/scheme/sendSchemeNotification',
	    type: 'post',
            dataType: 'json',
            success: function (data) {
               $('form#sendnotifscheme div.error-msg').html('');
                if(data.error==1){
                    $('form#sendnotifscheme div.error-msg').append('<div class="alert alert-danger">'+data.msg+'</div>')
                }else{
                    $('form#sendnotifscheme div.error-msg').append('<div class="alert alert-success">'+data.msg+'</div>')
                }
                
            },
            data: formData
        });
       
     });
    
    /*function sendNotification(schemeId,year_month){
    	if(confirm("Are you sure, you want to send notification to all the distributors?")){
            $.ajax({
		url: window.location.protocol+'//'+window.location.hostname+'/scheme/sendSchemeNotification',
                type: 'get',
                dataType: 'json',
                success: function (data) {
                   alert(data.msg);
                   window.location.href = "";
                },
                data: {'scheme_id':schemeId,'year_month':year_month}
        });
     }*/
     
     function sendNotification(schemeId,year_month){
     		$('form#sendnotifscheme input[name=notif_title]').before('<input type="hidden" name="scheme_id" value="'+schemeId+'">');
            $('form#sendnotifscheme input[name=notif_title]').before('<input type="hidden" name="year_month" value="'+year_month+'">');
           
            $('div#error-msg').html(''); 
            $('#notif_title').html('');
            $('div#sendNotif').modal('show');
     }
     
</script>
