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
                 /*<-- Select the height of the body*/
		 width: 100%;
		 position: relative;
	   }
	</style>
	
	<div class="container">
  <h2></h2>

    <div class="panel panel-info center-block">
        <div class="panel-heading">In Process Report
        <div class="row pull-right">
             <div class="col-sm-2">   
		 <label class="control-label">Date:- </label>
		 </div> 
              <div class="col-sm-3">   
		<input type="text" class="form-control" id="frm_date"  value="<?php echo $frmdate; ?>">
              </div>
            <div class="col-sm-3">   
		<input type="text" class="form-control" id="to_date"  value="<?php echo $todate; ?>">
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
	  
			
  <?php foreach($datearray as $key=>$values){
        $auto_success = $values['auto success range 5-15 min']+$values['auto success range 15-45 min']+$values['auto success range 45-1.5 hr']+$values['auto success range 1.5-2 hr']+$values['auto success range 2+ hr'];
        $manual_success = $values['manual success range 5-15 min']+$values['manual success range 15-45 min']+$values['manual success range 45-1.5 hr']+$values['manual success range 1.5-2 hr']+$values['manual success range 2+ hr'];
        $total_sucess = $auto_success+$manual_success;

        $auto_fail = $values['auto fail range 1.5-2 hr']+$values['auto fail range 15-45 min']+$values['auto fail range 2+ hr']+$values['auto fail range 45-1.5 hr']+$values['auto fail range 5-15 min'];
        $manual_fail = $values['manual fail range 1.5-2 hr']+$values['manual fail range 45-1.5 hr']+$values['manual fail range 5-15 min']+$values['manual fail range 15-45 min']+$values['manual fail range 2+ hr'];
        $total_fail = $auto_fail+$manual_fail;

        $total = $total_sucess+$total_fail;
        $auto = $auto_fail+$auto_success;
        $manual = $manual_success+$manual_fail;
   echo  "<b>".$key."</b><br>";
?>
<table class="table table-bordered">
            <thead>
                <tr>
                  <th rowspan="2">Pay1</th>  
                  <th rowspan="2" style="text-align:center">Success</th>
                  <th rowspan="2" style="text-align:center">Failed</th>
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
                  
                </tr>
            </thead>
                <tr><td>(Total) <?php echo $total; ?></td>
                <td><?php echo $total_sucess; ?></td>
                <td><?php echo $total_fail; ?></td>
                <td><?php echo $values['auto success range 5-15 min']+$values['manual success range 5-15 min']; ?></td>
                <td><?php echo $values['auto fail range 5-15 min']+$values['manual fail range 5-15 min']; ?></td>
                <td><?php echo $values['auto success range 15-45 min']+$values['manual success range 15-45 min']; ?></td>
                <td><?php echo $values['auto fail range 15-45 min']+$values['manual fail range 15-45 min']; ?></td>
                <td><?php echo $values['auto success range 45-1.5 hr']+$values['manual success range 45-1.5 hr']; ?></td>
                <td><?php echo $values['auto fail range 45-1.5 hr']+$values['manual fail range 45-1.5 hr']; ?></td>
                <td><?php echo $values['auto success range 1.5-2 hr']+$values['manual success range 1.5-2 hr']; ?></td>
                <td><?php echo $values['auto fail range 1.5-2 hr']+$values['manual fail range 1.5-2 hr']; ?></td>
                <td><?php echo $values['auto success range 2+ hr']+$values['manual success range 2+ hr']; ?></td>
                <td><?php echo $values['auto fail range 2+ hr']+$values['manual fail range 2+ hr']; ?></td>
                
            </tr>
           <tr><td>(Auto) <?php echo $auto; ?></td>
                <td><?php echo $auto_success; ?></td>
                <td><?php echo $auto_fail; ?></td>
               <td><?php echo $values['auto success range 5-15 min']; ?></td>
                <td><?php echo $values['auto fail range 5-15 min']; ?></td>
                <td><?php echo $values['auto success range 15-45 min']; ?></td>
                <td><?php echo $values['auto fail range 15-45 min']; ?></td>
                <td><?php echo $values['auto success range 45-1.5 hr']; ?></td>
                <td><?php echo $values['auto fail range 45-1.5 hr']; ?></td>
                <td><?php echo $values['auto success range 1.5-2 hr']; ?></td>
                <td><?php echo $values['auto fail range 1.5-2 hr']; ?></td>
                <td><?php echo $values['auto success range 2+ hr']; ?></td>
                <td><?php echo $values['auto fail range 2+ hr']; ?></td>
            </tr>
            <tr><td>(Manually) <?php echo $manual; ?></td>
                <td><?php echo $manual_success; ?></td>
                <td><?php echo $manual_fail; ?></td>
               <td><?php echo $values['manual success range 5-15 min']; ?></td>
                <td><?php echo $values['manual fail range 5-15 min']; ?></td>
                <td><?php echo $values['manual success range 15-45 min']; ?></td>
                <td><?php echo $values['manual fail range 15-45 min']; ?></td>
                <td><?php echo $values['manual success range 45-1.5 hr']; ?></td>
                <td><?php echo $values['manual fail range 45-1.5 hr']; ?></td>
                <td><?php echo $values['manual success range 1.5-2 hr']; ?></td>
                <td><?php echo $values['manual fail range 1.5-2 hr']; ?></td>
                <td><?php echo $values['manual success range 2+ hr']; ?></td>
                <td><?php echo $values['manual fail range 2+ hr']; ?></td>
            </tr>
            </table>
<?php } //end of foreach loop ?>
	  
	
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
            var startDate = new Date($('#frm_date').val());
            var endDate = new Date($('#to_date').val());

            if (startDate > endDate){
                alert('start date should be less than end date');
                $('#frm_date').focus();
                return false;
            }

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
               var url = '/panels/inprocessReportMongo/?frmdate='+$("#frm_date").val().trim()+'&todate='+$("#to_date").val().trim()+'&from_time='+from_time+'&to_time='+to_time+'&modem_flag='+modem_flag+'&api_flag='+api_flag;
		window.location = url;
	}
	

</script>







