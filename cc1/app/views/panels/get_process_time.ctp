<?php
//error_reporting(1);
//ini_set('display_error','On');
?>

<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
	</script>
	<style type="text/css">
		.checkbox {
			font-size: 12px;
			line-height: 23px;
		}
		.reddiv{
			background-color:#FF0000;
			/*border: 1px solid;*/
			box-shadow: 0 0 2px grey;
		}
		.greendiv{
			background-color: #00FF00;
			/*border: 1px solid;*/
			box-shadow: 0 0 2px grey;
		}
	</style>
	
	<div class="container">
  <h2>In Process Report</h2>
  <div class="panel panel-default">
    <div class="panel-body">Average Processing Time</div>
  </div>
  <div class="row" style="padding: 40px 10px 10px;">
	  
        <div class="col-lg-12">
                <div class="col-lg-2">
                        <label class="control-label">Select Date: </label><br>
                </div>
                <div class="col-lg-4">
                        <span style="float:left">From : &nbsp;</span>
                        <span style="float:left"><input type="text" class="form-control" style='width:100px;margin-top: -5px;'  id="frm" value="<?php echo $frm; ?>"></span>&nbsp;
                        <select name="frm_time_hrs" id="frm_time_hrs">
                                <?php for($i=0;$i<24;$i++) { ?>
                                <option <?php if($i == $frm_time[0]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
                                <?php } ?>
                        </select> :
                        <select name="frm_time_mins" id="frm_time_mins">
                                <?php for($i=0;$i<60;$i++) { ?>
                                <option <?php if($i == $frm_time[1]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
                                <?php } ?>
                        </select>
                </div>
                <div class="col-lg-4">
                        <span style="float:left">To : &nbsp;</span>
                        <span style="float:left"><input type="text" class="form-control" style='width:100px; margin-top: -5px;' id="to" value="<?php echo $to; ?>"></span>&nbsp;
                        <select name="to_time_hrs" id="to_time_hrs">
                                <?php for($i=0;$i<24;$i++) { ?>
                                <option <?php if($i == $to_time[0]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
                                <?php } ?>
                        </select> :
                        <select name="to_time_mins" id="to_time_mins">
                                <?php for($i=0;$i<60;$i++) { ?>
                                <option <?php if($i == $to_time[1]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
                                <?php } ?>
                        </select>
                        <input type="button" value="Submit" onclick="submit();" class="btn btn-primary" style="margin-left: 30px; padding: 5px 10px;">
                        (Note : 24 Hours Format)
                </div>

        </div>
				
			
</div>
  
  <table class="table">
    <thead>
      <tr>
		<th class="col-lg-1"></th>
		<th class="col-lg-1">Benchmark Failure</th>
		<th class="col-lg-1">Benchmark ProcessTime</th>
		<th class="col-lg-1">Overall Faliure</th>
        <th>Modems</th>
        <th>Api</th>
		<th>0-40(Secs)</th>
		<th>40-60(Secs)</th>
		<th>60-90(Secs)</th>
		<th>90+(Secs)</th>
		 <th></th>
      </tr>
    </thead>
    <tbody>
		<?php
		
		

		
		
		foreach ($processtime as $key => $val){
			
		$modemCount = $val['modemprocesstime']['totatcount'] - $val['apiprocesstime']['totatcount'];
		$cls = '';
		$cls1 ='';
		$cls2 = '';
		
		$modemFailure = isset($val['apiprocesstime']['processtime']) ? round(($modemCount) / $val['modemprocesstime']['totatcount']*100,2) : round(($val['modemprocesstime']['totatcount']) / $val['modemprocesstime']['totatcount']*100,2);
		
		$apiFailure = isset($val['apiprocesstime']['processtime']) ? round(($val['apiprocesstime']['totatcount'] /$val['modemprocesstime']['totatcount'])*100,2) : 0;
				
		
		if(isset($faliure[$key]['overallfaliure']) && round($faliure[$key]['overallfaliure']/$faliure[$key]['overaallcount']*100,2)>$processtime[$key][0]['benchmark_failure']){
			$cls = 'reddiv';
		} 
		if(((isset($faliure[$key]['modemfaliure']) && round($faliure[$key]['modemfaliure']/$faliure[$key]['modemcount']*100,2)>$processtime[$key][0]['benchmark_failure'])
				|| (isset($val['modemprocesstime']['processtime']) && round($val['modemprocesstime']['processtime'],2)>$processtime[$key][0]['benchmark_processtime']) 
				&& (intval($modemFailure)>5)))
		{
			$cls1 = 'reddiv';
		}
		if(((isset($faliure[$key]['modemfaliure']) && round($faliure[$key]['apifaliure']/$faliure[$key]['apicount']*100,2)>$processtime[$key][0]['benchmark_failure']) 
				&& (isset($val['apiprocesstime']['processtime']) && round($val['apiprocesstime']['processtime'],2)>$processtime[$key][0]['benchmark_processtime']) 
				|| (intval($apiFailure)>5))){
			$cls2 = 'reddiv';
		}
		
			?>
      <tr>
        <td>
			<?php echo isset($val['modemprocesstime']['oprname']) ? $val['modemprocesstime']['oprname'] : $val['apiprocesstime']['oprname']; ?>
		</td>
		<td>
			<?php echo (isset($processtime[$key][0]['benchmark_failure']) && !empty($processtime[$key][0]['benchmark_failure'])) ? $processtime[$key][0]['benchmark_failure']."%" : 0 ; ?>
		</td>
		<td>
			<?php echo (isset($processtime[$key][0]['benchmark_processtime']) && !empty($processtime[$key][0]['benchmark_processtime'])) ? $processtime[$key][0]['benchmark_processtime']."Secs" : 0 ; ?>
		</td>
		<td class="<?php echo $cls; ?>">
			<a href="/panels/failureInfo/?date=<?php echo $frm ?>&frm=<?php echo implode(".", $frm_time) ;?>&to=<?php echo implode(".", $to_time) ;?>&oprId=<?php echo $key; ?>&type=all" target="_blank">
			<?php echo isset($faliure[$key]['overallfaliure']) ? round($faliure[$key]['overallfaliure']/$faliure[$key]['overaallcount']*100,2) : 0; ?>%
		        </a>    
                            (<?php echo isset($faliure[$key]['overallfaliure']) ? $faliure[$key]['overallfaliure'] : 0;
                                   echo "/";
                                   echo isset($faliure[$key]['overaallcount']) ? $faliure[$key]['overaallcount'] : 0 
                            ?>)
                    
			</td>
		
        <td class="<?php echo $cls1; ?>">
			<?php echo isset($val['modemprocesstime']['processtime']) ? round($val['modemprocesstime']['processtime'],2)."sec" : "0"; echo "<br/>"; ?>
			
			<a href="/panels/failureInfo/?date=<?php echo $frm ?>&frm=<?php echo implode(".", $frm_time) ;?>&to=<?php echo implode(".", $to_time) ;?>&oprId=<?php echo $key; ?>&transtype=modem" target="_blank">
				<?php
				echo isset($faliure[$key]['modemcount']) ? round($faliure[$key]['modemcount']/$faliure[$key]['overaallcount']*100,2) : 0;
				///echo isset($val['apiprocesstime']['processtime']) ? round(($modemCount) / $val['modemprocesstime']['totatcount']*100,2) : round(($val['modemprocesstime']['totatcount']) / $val['modemprocesstime']['totatcount']*100,2) ?>
				%</a>
			
			<br/>Faliure<a href="/panels/failureInfo/?date=<?php echo $frm ?>&frm=<?php echo implode(".", $frm_time) ;?>&to=<?php echo implode(".", $to_time) ;?>&oprId=<?php echo $key; ?>&type=modem" target="_blank">(<?php echo isset($faliure[$key]['modemfaliure']) ? round($faliure[$key]['modemfaliure']/$faliure[$key]['modemcount']*100,2):0; ?>)</a>%
                        <br/>(<?php echo isset($faliure[$key]['modemfaliure']) ? $faliure[$key]['modemfaliure'] : 0;
                                   echo "/";
                                   echo isset($faliure[$key]['modemcount']) ? $faliure[$key]['modemcount'] : 0 ?>)
		</td>
        <td class="<?php echo $cls2; ?>">
			<?php echo isset($val['apiprocesstime']['processtime']) ? round($val['apiprocesstime']['processtime'],2)."sec" : ""; echo "<br/>";  ?>
			<a href="/panels/failureInfo/?date=<?php echo $frm ?>&frm=<?php echo implode(".", $frm_time) ;?>&to=<?php echo implode(".", $to_time) ;?>&oprId=<?php echo $key; ?>&transtype=api" target="_blank">
				<?php echo isset($faliure[$key]['apicount']) ? round($faliure[$key]['apicount']/$faliure[$key]['overaallcount']*100,2) : 0; ?>%
			</a>
			<br/>Faliure<a href="/panels/failureInfo/?date=<?php echo $frm ?>&frm=<?php echo implode(".", $frm_time) ;?>&to=<?php echo implode(".", $to_time) ;?>&oprId=<?php echo $key; ?>&type=api" target="_blank">(<?php echo isset($faliure[$key]['modemfaliure']) ? round($faliure[$key]['apifaliure']/$faliure[$key]['apicount']*100,2):0; ?>)</a>%
                        <br/>(<?php echo isset($faliure[$key]['apifaliure']) ? $faliure[$key]['apifaliure'] : 0;
                                   echo "/";
                                   echo isset($faliure[$key]['apicount']) ? $faliure[$key]['apicount'] : 0 ?>)
		</td>
		<?php
		$class = '';
		$class1 = '';
		$class2 = '';
		$class3 = '';
		if (isset($data[$key]['0-40']) && isset($processtime[$key][0]['benchmark_processtime'])  && round((count($data[$key]['0-40'])) / ($val['modemprocesstime']['totatcount']) * 100, 2) > 10) {
                        $class = 'greendiv';
		} 
		if (isset($data[$key]['40-60']) && isset($processtime[$key][0]['benchmark_processtime']) && round((count($data[$key]['40-60'])) / ($val['modemprocesstime']['totatcount']) * 100, 2) > 10) {
                        $class1 = 'greendiv';
		} 
		if (isset($data[$key]['60-90']) && isset($processtime[$key][0]['benchmark_processtime']) && round((count($data[$key]['60-90'])) / ($val['modemprocesstime']['totatcount']) * 100, 2) > 10) {
                        $class2 = 'greendiv';
		} 
		if (isset($data[$key]['90-100']) && isset($processtime[$key][0]['benchmark_processtime']) && round((count($data[$key]['90-100'])) / ($val['modemprocesstime']['totatcount']) * 100, 2) > 10 ) {
                        $class3 = 'greendiv';
		}
		
	?>
		<td class="<?php echo $class; ?>">
                        <a href="/panels/failureInfo/?date=<?php echo $frm ?>&frm=<?php echo implode(".", $frm_time) ;?>&to=<?php echo implode(".", $to_time) ;?>&timerange=0-40&oprId=<?php echo $key;?>" target="_blank"><?php echo isset($data[$key]['0-40']) ? round((count($data[$key]['0-40']))/($faliure[$key]['modemcount'] - $faliure[$key]['modemfaliure'])*100,2).'%': 0 .'%'; ?></a>
		        <br/><?php echo "(".count($data[$key]['0-40']).")"; ?>
                </td>
		<td class="<?php echo $class1; ?>">
			<a href="/panels/failureInfo/?date=<?php echo $frm ?>&frm=<?php echo implode(".", $frm_time) ;?>&to=<?php echo implode(".", $to_time) ;?>&timerange=40-60&oprId=<?php echo $key;?>" target="_blank"><?php echo isset($data[$key]['40-60']) ? round((count($data[$key]['40-60']))/($faliure[$key]['modemcount'] - $faliure[$key]['modemfaliure'])*100,2).'%': 0 ."%" ;?></a>
		        <br/><?php echo "(".count($data[$key]['40-60']).")"; ?>
                </td>
		<td class="<?php echo $class2; ?>">
			<a href="/panels/failureInfo/?date=<?php echo $frm ?>&frm=<?php echo implode(".", $frm_time) ;?>&to=<?php echo implode(".", $to_time) ;?>&timerange=60-90&oprId=<?php echo $key;?>" target="_blank"><?php echo isset($data[$key]['60-90']) ? round((count($data[$key]['60-90']))/($faliure[$key]['modemcount'] - $faliure[$key]['modemfaliure'])*100,2).'%': 0 ."%" ; ?></a>
		        <br/><?php echo "(".count($data[$key]['60-90']).")"; ?>
                </td>
		<td class="<?php echo $class3; ?>">
			<a href="/panels/failureInfo/?date=<?php echo $frm ?>&frm=<?php echo implode(".", $frm_time) ;?>&to=<?php echo implode(".", $to_time) ;?>&timerange=90-100&oprId=<?php echo $key;?>" target="_blank"><?php echo isset($data[$key]['90-100']) ? round((count($data[$key]['90-100']))/($faliure[$key]['modemcount'] - $faliure[$key]['modemfaliure'])*100,2)."%": 0 ."%" ; ?></a>
		        <br/><?php echo "(".count($data[$key]['90-100']).")"; ?>
                </td>
		<td>
			<a href="/panels/graphReport/?date=<?php echo $frm; ?>&oprId=<?php echo $key;?>" target="_blank">Analyze Report</a>
		</td>
		
      </tr>
	  
		<?php } ?>
     
    </tbody>
  </table>

			

		
 
</div>
 
	



<script>
	

            // When the document is ready
            $(document).ready(function () {
                $('#frm, #to').datepicker({
                    format: "yyyy-mm-dd",
					//startDate: "-365d",
						endDate: "1d",
						multidate: false,
						autoclose: true,
						todayHighlight: true
                });  
            
            });
       

	function submit(){
		var frm = $("#frm").val();
		var to = $("#to").val();
		var from_time = $("#frm_time_hrs").val()+"."+$("#frm_time_mins").val();
		var to_time = $("#to_time_hrs").val()+"."+$("#to_time_mins").val();
		var url = '/panels/getProcessTime/'+frm+'/'+to+'/'+from_time+'/'+to_time;
		window.location = url;
	}
	

</script>