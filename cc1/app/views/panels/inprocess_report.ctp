<?php
error_reporting(0);
?>

<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
 <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
   <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
   <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
	</script>
	<style type="text/css">
                .DateLabel {
                    margin-right: 60px;
                }
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
		}.scroll{
                        overflow-y: scroll;      
                        height: 200px;            
                        width: 100%;
                        position: relative;
	   }
	</style>
	
	<div class="container">
  <h2></h2>

    <div class="panel panel-info center-block">
        <div class="panel-heading">In Process Report
        <div class="row pull-right">
             <div class="col-sm-3">   
		 <label class="control-label">Date:- </label>
		 </div> 
              <div class="col-sm-6">   
		<input type="text" class="form-control" id="frm_date"  value="<?php echo $frmdate; ?>">
		 </div>  
		</div>	
        </div>
    </div>
  
    <div class="row">
        
            <div class="col-sm-1">    
               <label class="control-label">Hours:-   </label>
            </div>
            <div class="col-sm-1">    
               <label class="control-label"> From:-  </label>
            </div>
        
            <div class="col-sm-2">    
                <select class="form-control" name ="from_time" id="from_time">
                    <option value="">--Select time--</option>
                    <?php for($i=0;$i<24;$i++){ ?>
                    <option value="<?php echo $i; ?>" <?php if($frm == $i) { echo "selected" ;}?>><?php echo  date("H.iA", strtotime("$i:00")); ?></option>
                    <?php } ?>

                </select>
            </div>
        
            <div class="col-sm-1">
                      <label class="control-label">To:- </label>
            </div> 
        
            <div class="col-sm-2">
                <select class="form-control" name ="to_time" id="to_time">
                    <option value="">--Select time--</option>
                    <?php for($i=0;$i<24;$i++){ ?>
                    <option value="<?php echo $i; ?>" <?php if($to == $i) { echo "selected" ;}?>><?php echo  date("H.iA", strtotime("$i:00")); ?></option>
                    <?php } ?>

                </select>
            </div>    
        
            <div class="col-md-3 pull-right">
                <label class="checkbox-inline"><input type="checkbox" id="modem_check" value="" <?php if($modem_flag) echo "checked" ?>>Modem</label>
                <label class="checkbox-inline"><input type="checkbox" id="api_check" value="" <?php if($api_flag) echo "checked" ?>>API</label>
                <button type="submit" class="btn btn-info pull-right" onclick="submit();">Submit</button>  
            </div>
    </div>    

  
<!--  <div class="row">
	  <div class="col-lg-3" style="text-align: center;border:1px;">
		  <span><input type="text" class="form-control" style='width:270px;'  id="frm_date"   value="<?php echo $frmdate; ?>">
		  </span>
			</div>
			<div class="col-lg-3" style="text-align: center;border:1px;">
				<span><input type="text" class="form-control" style='width:270px;margin-bottom: 20px;'  id="to_date"   value="<?php echo $todate; ?>">
				</span>
				</div>
			
			<div class="col-lg-2" style="text-align:left;border:1px;">
				<input type="button" value="submit" onclick="submit();" class="btn btn-primary">
			</div>

		</div>-->
  
    


  
  
  <!--<div class="row">-->
       <div class="container">  
<!--	  <div class="col-lg-12" style="text-align: center;">
				<div class="col-lg-6" style="text-align: center;border:1px;">
				<input type="text" class="form-control" style='width:270px;margin-bottom: 20px;'  id="datepicker"   value="<?php echo $date; ?>">
				 <input type="button" value="submit" onclick="submit();" class="btn btn-primary">
				</div>
	  </div>-->
    
    <?php $link = '/panels/inProcessTransactionList/'.trim($frmdate).'/'.trim($todate).'/'.($frm == '' ? 0 : $frm).'/'.($to == '' ? 0 : $to) .'/'.$modem_flag.'/'.$api_flag; ?>
		
    <?php foreach ($datearray as $key =>$val){ ?>

        <span class="DateLabel"><?php echo $key; ?></span>
        <span class="PendingLabel"> Pending txn : <?php echo $val['pending']; ?></span>    
        
        
        <table class="table table-bordered">
            <thead>
                <tr>
                  <th rowspan="2">Pay1</th>  
                  <th colspan="2" style="text-align:center" class="info">Total</th>
                  <th colspan="2" style="text-align:center" class="info">5  to 15 mins</th>
                  <th colspan="2" style="text-align:center" class="info">15 To 45 mins</th>
                  <th colspan="2" style="text-align:center" class="info">45 mins To 1:15 hr</th>
                  <th colspan="2" style="text-align:center" class="info">1:15 to 2:00 hours</th>
                  <th colspan="2" style="text-align:center" class="info">2 Hours +</th>
                  
                </tr>
                <tr>
                  <th style="text-align:center" class="success">Success</th>
                  <th style="text-align:center" class="danger">Failed</th>
                  <th style="text-align:center" class="success">Success</th>
                  <th style="text-align:center" class="danger">Failed</th>
                  <th style="text-align:center" class="success">Success</th>
                  <th style="text-align:center" class="danger">Failed</th>
                  <th style="text-align:center" class="success">Success</th>
                  <th style="text-align:center" class="danger">Failed</th>
                  <th style="text-align:center" class="success">Success</th>
                  <th style="text-align:center" class="danger">Failed</th>
                  <th style="text-align:center" class="success">Success</th>
                  <th style="text-align:center" class="danger">Failed</th>
                  
                </tr>
            </thead>
            <tr><td>(Total) <a href="<?php echo $link.'/whole_total'; ?>" target="_blank" ><?php echo $val['manualTotalSuccees']+$val['autoTotalSuccees']+$val['manualTotalFailed']+$val['autoTotalFailed']; ?></a></td>
                <td><a href="<?php echo $link.'/total_success'; ?>" target="_blank" ><?php echo $val['manualTotalSuccees']+$val['autoTotalSuccees']; ?></a></td>
                <td><a href="<?php echo $link.'/total_fail'; ?>" target="_blank" ><?php echo $val['manualTotalFailed']+$val['autoTotalFailed']; ?></a></td>
                <td><a href="<?php echo $link.'/success_5_to_15'; ?>" target="_blank" ><?php echo $val['manuallySuccessProcessing_Time_5_to_15']+$val['autoSuccessProcessing_Time_5_to_15']; ?></a></td>
                <td><a href="<?php echo $link.'/fail_5_to_15'; ?>" target="_blank" ><?php echo $val['manuallyFailProcessing_Time_5_to_15']+$val['autoFailProcessing_Time_5_to_15']; ?></a></td>
                <td><a href="<?php echo $link.'/success_15_to_45'; ?>" target="_blank" ><?php echo $val['manuallySuccessProcessing_Time_15_to_45']+$val['autoSuccessProcessing_Time_15_to_45']; ?></a></td>
                <td><a href="<?php echo $link.'/fail_15_to_45'; ?>" target="_blank" ><?php echo $val['manuallyFailProcessing_Time_15_to_45']+$val['autoFailProcessing_Time_15_to_45']; ?></a></td>
                <td><a href="<?php echo $link.'/success_45_to_115'; ?>" target="_blank" ><?php echo $val['manuallySuccessProcessing_Time_45_to_115']+$val['autoSuccessProcessing_Time_45_to_115']; ?></a></td>
                <td><a href="<?php echo $link.'/fail_45_to_115'; ?>" target="_blank" ><?php echo $val['manuallyFailProcessing_Time_45_to_115']+$val['autoFailProcessing_Time_45_to_115']; ?></a></td>
                <td><a href="<?php echo $link.'/success_115_to_2'; ?>" target="_blank" ><?php echo $val['manuallySuccessProcessing_Time_115_to_2']+$val['autoSuccessProcessing_Time_115_to_2']; ?></a></td>
                <td><a href="<?php echo $link.'/fail_115_to_2'; ?>" target="_blank" ><?php echo $val['manuallyFailProcessing_Time_115_to_2']+$val['autoFailProcessing_Time_115_to_2']; ?></a></td>
                <td><a href="<?php echo $link.'/success_200_to_more'; ?>" target="_blank" ><?php echo $val['manuallySuccessProcessing_Time_200_to_more']+$val['autoSuccessProcessing_Time_200_to_more']; ?></a></td>
                <td><a href="<?php echo $link.'/fail_200_to_more'; ?>" target="_blank" ><?php echo $val['manuallyFailProcessing_Time_200_to_more']+$val['autoFailProcessing_Time_200_to_more']; ?></a></td>
                
            </tr>
           <tr><td>(Auto) <a href="<?php echo $link.'/whole_auto'; ?>" target="_blank" ><?php echo $val['autoTotalSuccees']+$val['autoTotalFailed']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_total_success'; ?>" target="_blank" ><?php echo $val['autoTotalSuccees']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_total_fail'; ?>" target="_blank" ><?php echo $val['autoTotalFailed']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_success_5_to_15'; ?>" target="_blank" ><?php echo $val['autoSuccessProcessing_Time_5_to_15']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_fail_5_to_15'; ?>" target="_blank" ><?php echo $val['autoFailProcessing_Time_5_to_15']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_success_15_to_45'; ?>" target="_blank" ><?php echo $val['autoSuccessProcessing_Time_15_to_45']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_fail_15_to_45'; ?>" target="_blank" ><?php echo $val['autoFailProcessing_Time_15_to_45']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_success_45_to_115'; ?>" target="_blank" ><?php echo $val['autoSuccessProcessing_Time_45_to_115']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_fail_45_to_115'; ?>" target="_blank" ><?php echo $val['autoFailProcessing_Time_45_to_115']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_success_115_to_2'; ?>" target="_blank" ><?php echo $val['autoSuccessProcessing_Time_115_to_2']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_fail_115_to_2'; ?>" target="_blank" ><?php echo $val['autoFailProcessing_Time_115_to_2']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_success_200_to_more'; ?>" target="_blank" ><?php echo $val['autoSuccessProcessing_Time_200_to_more']; ?></a></td>
                <td><a href="<?php echo $link.'/auto_fail_200_to_more'; ?>" target="_blank" ><?php echo $val['autoFailProcessing_Time_200_to_more']; ?></a></td>
            </tr>
            <tr><td>(Manually) <a href="<?php echo $link.'/whole_manual'; ?>" target="_blank" ><?php echo $val['manualTotalSuccees']+$val['manualTotalFailed']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_total_success'; ?>" target="_blank" ><?php echo $val['manualTotalSuccees']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_total_fail'; ?>" target="_blank" ><?php echo $val['manualTotalFailed']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_success_5_to_15'; ?>" target="_blank" ><?php echo $val['manuallySuccessProcessing_Time_5_to_15']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_fail_5_to_15'; ?>" target="_blank" ><?php echo $val['manuallyFailProcessing_Time_5_to_15']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_success_15_to_45'; ?>" target="_blank" ><?php echo $val['manuallySuccessProcessing_Time_15_to_45']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_fail_15_to_45'; ?>" target="_blank" ><?php echo $val['manuallyFailProcessing_Time_15_to_45']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_success_45_to_115'; ?>" target="_blank" ><?php echo $val['manuallySuccessProcessing_Time_45_to_115']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_fail_45_to_115'; ?>" target="_blank" ><?php echo $val['manuallyFailProcessing_Time_45_to_115']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_success_115_to_2'; ?>" target="_blank" ><?php echo $val['manuallySuccessProcessing_Time_115_to_2']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_fail_115_to_2'; ?>" target="_blank" ><?php echo $val['manuallyFailProcessing_Time_115_to_2']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_success_200_to_more'; ?>" target="_blank" ><?php echo $val['manuallySuccessProcessing_Time_200_to_more']; ?></a></td>
                <td><a href="<?php echo $link.'/manual_fail_200_to_more'; ?>" target="_blank" ><?php echo $val['manuallyFailProcessing_Time_200_to_more']; ?></a></td>
            </tr>
            </table>
        
        
        
        

  <!--<table class="table" width = '100%'>-->
    
      <!--<tr>-->
<!--		  <td style="width:10%">
			  <table class="table-bordered table table-hover" >
				  <thead>
				  <tr>
					  <th>Date</th>
					  
				  </tr>
				   </thead>
				   <tbody>
				  <tr>
					  <th><?php echo $key; ?></th>
					  
				  </tr>
				   </tbody>
				 
				  
			  </table>
		  </td>-->
<!--		  <td style="width:25%">
			  <table class="table-bordered table table-hover" >
				  <thead>
				  <tr>
					  <th>Modem In process</th>
					  <th>Api In process</th>
					  <th>Execeed</th>
				  </tr>
				   </thead>
				   <tbody>
				  <tr>
					  <th><?php echo $val['modemInprocess']; ?></th>
					  <th><?php echo $val['apiinProcess']; ?></th>
					  <th><?php echo $val['totalexceed']; ?></th>
				  </tr>
				   </tbody>
				 
				  
			  </table>
		  </td>-->
<!--		  <td style="width:25%">
			  <table class="table-bordered  table table-hover">
				  <thead>
				  <tr>
					  <th></th>
					  <th>Success</th>
					  <th>Fail</th>
					  <th>Total</th>
				  </tr>
				  </thead>
				  <tbody>
				  <tr>
					 <th>Auto</th>
					  <th><?php echo $val['autosuccess']; ?></th>
					  <th><?php echo $val['autofailure']; ?></th>
					  <th><?php echo $val['autosuccess']+$val['autofailure']; ?></th>
				  </tr>
				  <tr>
					 <th>Manual</th>
					  <th><?php echo $val['manualsuccess']; ?></th>
					  <th><?php echo $val['manualfailure']; ?></th>
					  <th><?php echo $val['manualsuccess']+$val['manualfailure']; ?></th>
				  </tr>
				  </tbody>
				  
			  </table>
		  </td>-->
                  
                <!--Exceed Auto  & Exceed Manual Table-->
<!--		  <td style="width:30%">
			  <table class="table-bordered table table-hover">
				  <thead>
				  <tr>
					  <th></th>
					  <th>Success</th>
					  <th>Fail</th>
					  <th>Total</th>
				  </tr>
				  </thead>
				  <tbody>
				  <tr>
					 <th> Exceed Auto</th>
					  <th><?php echo $val['exceedautoSuccess']; ?></th>
					  <th><?php echo $val['exceedautoFailure']; ?></th>
					  <th><?php echo $val['exceedautoSuccess']+$val['exceedautoFailure']; ?></th>
				  </tr>
				  <tr>
					 <th> Exceed Manual</th>
					  <th><?php echo $val['exceedmanualsuccess']; ?></th>
					  <th><?php echo $val['exceedmanualfailure']; ?></th>
					  <th><?php echo $val['exceedmanualfailure']+$val['exceedmanualsuccess']; ?></th>
				  </tr>
				  </tbody>
				  
			  </table>
		  </td>-->
<!--		  <td style="width:20%">
			  <table class="table-bordered table table-hover">
				  <thead>
				  <tr>
					  <th>Pending</th>
				  </tr>
				  </thead>
				  <tbody>
				  <tr>
					  <th><?php echo $val['pending']; ?></th>
				  </tr>
				  </tbody>
			  </table>
			  
		  </td>-->
	  <!--</tr>-->
	
	  
	  <!--</table>-->
  <?php } ?>
<!--<div class="scroll" style="width:400px; height: 500px;">
	<table class="table" style="width:400px; height: 500px;">
	  <tr>
		  <td style="width:50%" >
		<table class="table-bordered table  table-hover">
			<thead>
		 <tr>
		   <th>Employee Name</th>
		  <th>Manual Success</th>
		  <th>Manual Failure</th>
		  <th>Total</th>
			  
	  </tr>
			</thead>
			<tbody>
	  <?php foreach ($usrdata as $key => $val){ ?>
	         <tr>
	      <th><?php echo $key; ?></th>
		  <th><?php echo $val['success']; ?></th>
		  <th><?php echo $val['failure']; ?></th>
		  <th><?php echo $val['success']+$val['failure']; ?></th>
			</tr>
			</tbody>

	  <?php }?>	 
				   
	 </table>
	</td>
	  </tr>
	  </table>
</div>-->
	  
	
</div>
 
<script>
	

            // When the document is ready
            $(document).ready(function () {
                $('#frm_date').datepicker({
                    format: "yyyy-mm-dd",
					//startDate: "-365d",
						endDate: "1d",
						multidate: false,
						autoclose: true,
						todayHighlight: true
                }); 
				$('#to_date').datepicker({
                    format: "yyyy-mm-dd",
					//startDate: "-365d",
						endDate: "1d",
						multidate: false,
						autoclose: true,
						todayHighlight: true
                });
            
            });
       

	function submit(){
            
            var start_time=document.getElementById("from_time");
            var from_time = start_time[start_time.selectedIndex].value;
            var end_time=document.getElementById("to_time");
            var to_time = end_time[end_time.selectedIndex].value;
            
            if(parseInt(from_time) > parseInt(to_time)){
                alert("From time should be greater than To time !! ");
                return ;
            }
            
            var modem_flag = 0, api_flag = 0;
            if($('#modem_check').is(':checked')){
                modem_flag = 1;
            }	
            if($('#api_check').is(':checked')){
                api_flag = 1;
            }
            if((modem_flag == 0) && (api_flag == 0)){
               modem_flag = 1;
               api_flag = 1;
            }
            
//	        var url = '/panels/inprocessReport/?frmdate='+$("#frm_date").val()+'&todate='+$("#to_date").val();
               var url = '/panels/inprocessReport/?frmdate='+$("#frm_date").val().trim()+'&from_time='+from_time+'&to_time='+to_time+'&modem_flag='+modem_flag+'&api_flag='+api_flag;
		window.location = url;
	}
	

</script>







