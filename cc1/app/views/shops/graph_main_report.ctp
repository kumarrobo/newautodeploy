<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'main'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER){?>
	  			<div>
    			<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    			
<!--    			<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="refunddata();"></span>-->
    			</div>
					<div>
	  				<span style="font-weight:bold;margin-right:10px;">Select <?php echo $modelName;?>: </span>
					<select id="shop">
               		<option value="0">Select</option>
					<?php foreach($records as $distributor) {?>
						<option value="<?php echo $distributor[$modelName]['id'];?>" <?php if(isset($id) && $id == $distributor[$modelName]['id']) echo "selected";?>><?php echo $distributor[$modelName]['company']; ?></option>
					<?php } ?>
					</select>
					<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findDistributor();"></span>
					<a href="/shops/mainReport/">Click Here to go Back</a>
					
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select <?php echo $modelName;?></span></div>
				<?php } ?>
				
				<table>
					<tr>
					
						<?php
						echo $this->GChart->start('test1');
						echo $this->GChart->visualize('test1', $data1);
						echo $this->GChart->start('test2');
						echo $this->GChart->visualize('test2', $data2);
						echo $this->GChart->start('test3');
						echo $this->GChart->visualize('test3', $data3);
						echo $this->GChart->start('test4');
						echo $this->GChart->visualize('test4', $data4);
						echo $this->GChart->start('test5');
						echo $this->GChart->visualize('test5', $data5);
						echo $this->GChart->start('test6');
						echo $this->GChart->visualize('test6', $data6);
						?>
						</td></tr>
				</table>
			   	
			</fieldset>
   			</div>
   			<br class="clearLeft" />
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>
<script>
function findDistributor(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
	var salesman = $('shop').options[$('shop').selectedIndex].value;
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
	if(date_from == '' || date_to == ''){
		$('date_err').show();
		$('submit').innerHTML = html;
	} else {
		date_from = date_from.replace(/-/g,"");
		date_to = date_to.replace(/-/g,"");
	}
	if(salesman == 0){
		window.location.href = "/shops/graphMainReport/0/"+date_from+"-"+date_to;
	}
	else {
		$('date_err').hide();
		window.location.href = "/shops/graphMainReport/"+salesman+"/"+date_from+"-"+date_to;
	}
}
</script>