<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
  <script type="text/javascript">
    $(document).ready(function() {
        $("#content").removeClass("container");
        $("#content").addClass("container-fluid");

    });
    
    function changemonth(){
            var month = $('select#month').val();
            var year = $('select#year').val();
            window.location = "/shops/targetReport/"+month+"/"+year;
    }
	
</script>
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'scheme'));?>
    		<div id="innerDiv">
    		
    		<?php //echo "<pre>"; print_r($target_data); echo "</pre>";?>
    		
    		<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<div>
					<span style="font-weight:bold;margin-right:10px;">Select Month Range:
						<!--<select onchange="changemonth(this.value)">-->
						<select onchange="changemonth()" id="month">
							<?php foreach ($month as $key => $val):?>
							<option value="<?php echo $key; ?>" <?php  if($key==$monthval){ echo"selected"; } ?>><?php echo $val; ?></option>
							<?php endforeach; ?>
						</select> 
					</span>
					<span style="font-weight:bold;margin-right:10px;">Select Year Range:
						<select onchange="changemonth()" id="year">
							<?php foreach ($year as $key => $val):?>
							<option value="<?php echo $key; ?>" <?php  if($key==$yearval){ echo"selected"; } ?>><?php echo $val; ?></option>
							<?php endforeach; ?>
						</select> 
					</span>
    			
    			</div>
	  				<div class="appTitle" style="margin-top:20px;">Target Report</div>
	  				<table style="margin-top:20px;" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
                    
                    <tr align="center">
                    	<table width="100%" cellspacing="0" cellpadding="0" border="1" class="ListTable" summary="Transactions">
                    		<tr>
                                <td><b>Distributor</b></td>
								 <td><b>Id</b></td>
								  <td><b>Reg Date</b></td>
								<td><b>Target1 - Recharge</b></td>
								<td><b>Target1 - Mpos</b></td>
								<td><b>Target1 - Smartbuy</b></td>
								<td><b>Target1 - Incentive</b></td>
                    			<td><b>Target2 - Recharge</b></td>
								<td><b>Target2 - Mpos</b></td>
								<td><b>Target2 - Smartbuy</b></td>
                    			<td><b>Target2 - Incentive</b></td>
                    			<td><b>Achieved - Recharge</b></td>
                    			<td><b>Achieved - Mpos</b></td>
                    			<td><b>Achieved - DMT</b></td>
                    			<td><b>Achieved - Smartbuy</b></td>
                                 
                    		</tr>
                    		<?php  
                    		foreach($target_data as $id => $data) {
								
                                    if(isset($id)) {
	                    		
                                                  
                    		?>
                    		<tr>
                                <td><?php echo $data['company'];?></td>
								 <td><?php echo $id;?></td>
                    			<td><?php echo date('Y-m-d',  strtotime($data['created']));?></td>
								<td><?php echo $data['target']['target1']['recharge'];?></td>
								<td><?php echo $data['target']['target1']['mpos'];?></td>
								<td><?php echo $data['target']['target1']['smartbuy'];?></td>
								<td><?php echo $data['target']['target1']['incentive_ex'] . " (".$data['target']['target1']['incentive'] . ")";?></td>
								<td><?php echo $data['target']['target2']['recharge'];?></td>
								<td><?php echo $data['target']['target2']['mpos'];?></td>
								<td><?php echo $data['target']['target2']['smartbuy'];?></td>
								<td><?php echo $data['target']['target2']['incentive_ex'] . " (".$data['target']['target2']['incentive'] . ")";?></td>
								<td><?php if($data['achieved']['0']['sale'] > 0)echo $data['achieved']['0']['sale'] . " (".intval($data['achieved']['0']['sale']*100/$data['target']['target1']['recharge'])."%)";?></td>
								<td><?php echo $data['achieved']['8']['kits'];?></td>
								<td><?php if($data['achieved']['12']['sale'] > 0)echo $data['achieved']['12']['sale'] . " (".$data['achieved']['12']['rets'] . ")";?></td>
								<td><?php if($data['achieved']['13']['sale'] > 0)echo $data['achieved']['13']['sale'] . " (".$data['achieved']['13']['rets'] . ")";?></td>
								
                    			
                    		</tr>
                                <?php } } ?>
                    		
                    	</table>
                    </tr>      
			   	</table>
			   	
			</fieldset>
   		
    		
    		
    		</div>
   			<br class="clearLeft" />
 		</div>

    </div>
 </div>
<br class="clearRight" />
</div>