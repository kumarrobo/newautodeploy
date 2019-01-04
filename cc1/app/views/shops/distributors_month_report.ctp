
<?php error_reporting(0);if(!isset($pageType) || $pageType != 'csv'){ ?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");

    });
</script>




<div>

	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));
	
	$month = array("Select Month Range","Jan","Feb","Mar","Apr","May","June","July","Aug","Sep","Oct","Nov","Dec");
	?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    	<?php echo $this->element('shop_side_reports',array('side_tab' => 'distributors_month_report'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<div>
					<span style="font-weight:bold;margin-right:10px;">Select Month Range:
						<select id="month" onchange="changemonth()">
							<?php foreach ($month as $key => $val):?>
							<option value="<?php echo $key; ?>" <?php  if($key==$monthval){ echo"selected"; } ?>><?php echo $val; ?></option>
							<?php endforeach; ?>
						</select> 
					</span>
					<span style="font-weight:bold;margin-right:10px;">Select Services:
						<select id="service" onchange="changemonth()">
							<option value="">-All-</option>
							<?php foreach ($services as $key=>$service):?>
							<option value="<?php echo $key; ?>" <?php  if($key==$serviceval){ echo"selected"; } ?>><?php echo $service; ?></option>
							<?php endforeach; ?>
						</select> 
					</span>
					 <span><a href="?res_type=csv" ><img id="export_csv" type="button" alt="xp" class="export_csv" src="/img/csv1.jpg" style="height:25px" /></a></span>
    			
<!--    			<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="refunddata();"></span>-->
    			</div>
<!--    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>-->
				<div class="appTitle" style="margin-top:20px;">Distributor Sale</div>
					<table width="200%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered" summary="Transactions">
        			<thead>
			          <tr>
			            <th style="width:80px;">Distributors Name</th>
						 <th style="width:80px;">City</th>
						  <th style="width:80px;">State</th>
						   <th style="width:30px;">Id</th>
						   <th style="width:30px;">Transacting Retailer</th>
						    <th style="width:80px;">Reg Date</th>
							<th style="width:80px;">Margin Slab</th>
							<!--<th style="width:80px;">Mobile No</th>-->
						
						<?php while (strtotime($fromdate) < strtotime($todate)) { 
							?>
						<th style="width:120px;"><?php echo $fromdate; ?></th>
						<?php $fromdate = date ("Y-m-d", strtotime("+1 day", strtotime($fromdate)));
						  } ?>
						
			          </tr>
					  <?php $i = 0; foreach ($distRecords as $key => $val){ if($i%2 == 0)$class = '';
                    	else $class = 'altRow'; ?>
					  <tr class="<?php echo $class; ?>">
						  <td><?php echo $distId[$key]['distributors']['company']; ?></td>
						  <td><?php echo $distId[$key]['distributors']['city']; ?></td>
						  <td><?php echo $distId[$key]['distributors']['state']; ?></td>
						  <td><?php echo $distId[$key]['distributors']['id']; ?></td>
						  <td><?php echo $distId[$key]['distributors']['no_of_transacting_retailer']; ?></td>
						  <td><?php echo $distId[$key][0]['created_date']; ?></td>
						  <td><?php echo $distId[$key]['distributors']['margin']; ?></td>
						  <!--<td><?php echo $distId[$key]['distributors']['mobile']; ?></td>-->
						   <?php foreach ($val as $k => $v){ ?>
						  <td><?php echo isset($v['sale']) ? $v['sale'] : ""; ?></td>
					  <?php } $i++;}?>
					  </tr>
			        </thead>
                    <tbody>
                     					    			      
			         </tbody>	         
			   	</table>
			  
			</fieldset>
   			</div>
</div>
 </div>
<br class="clearRight" />
</div>

<script  type="text/javascript">
	
	
	function changemonth(){
		var month = document.getElementById('month').value;
		var service = document.getElementById('service').value;
		window.location = "/shops/distributorsMonthReport/"+month+"/"+service;
		
	}
	</script>
	
<?php } ?>


