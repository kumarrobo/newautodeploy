<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'sale'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  				<div class="appTitle" style="margin-top:20px;">Sale Report</div>
                    <div>
                    <span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
                    <span style="font-weight:bold;margin-right:10px;">State :</span>
                    <select id="state">
                        <option value="0">--All--</option>
                        <?php foreach ($states as $key => $value){  ?>
                         <option value="<?php echo $value['locator_state']['name'] ;  ?>" <?php if(!empty($state) && $state == $value['locator_state']['name']) echo "selected";?>><?php echo $value['locator_state']['name'] ;  ?></option>
                    <?php }?>
                    </select>
                    <?php if ($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) { ?>
                    <span style="font-weight:bold;margin-right:10px;"><?php if($_SESSION['Auth']['show_sd'] == 1){ echo 'Master Distributor'; }else { echo 'RM'; } ?> :</span>
                    <select id="rm">
                        <option value="0">--All--</option>
                        <?php foreach ($rmList as $key => $rm){  ?>
                         <option value="<?php echo $rm['rm']['id'] ;  ?>" <?php if(isset($rm_id) && $rm_id == $rm['rm']['id']) echo "selected";?>><?php echo $rm['rm']['name'] ;  ?></option>
                    <?php }?>
                    </select>
                    <?php } ?>





                    <span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="report();"></span>


                    <div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
                    </div>
                                        <table style="margin-top:20px;" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">

                    <tr align="center">
                    	<table width="100%" cellspacing="0" cellpadding="0" border="1" class="ListTable" summary="Transactions">
                    		<tr>
                    			<td></td>
                    			<td><b>Today</b></td>
                    			<td><b>Yesterday</b></td>
                    			<td><b>Last 7 days</b></td>
                    			<td><b>Last 30 days</b></td>
                    		</tr>
                    		<?php $today=0; $yest = 0; $week= 0; $month = 0;
                    		foreach($datas as $distid=>$data) {
                                    $per = $data['week']==0 ? 0 : ((isset($data['yesterday'])?$data['yesterday'] : 0)-($data['week']))/($data['week'])*100;
                                    $color = "white";
                                    if($per < -15){
                                        //below average
                                        $color = "#c73525";
                                    }else if($per > 15){
                                        //above average
                                        $color = "#99ff99";
                                    }
                    		$today += $data['today'];
                    		$yest += $data['yesterday'];
                    		$week += $data['week'];
                    		$month += $data['month'];
                    		?>
                    		<tr style="background: none repeat scroll 0 0 <?php echo $color?>;">
                    			<td><?php echo $data['name'];?></td>
                    			<td><?php echo $data['today'];?></td>
                    			<td><?php echo $data['yesterday'];?></td>
                    			<td><?php echo $data['week'];?></td>
                    			<td><?php echo $data['month'];?></td>
                    		</tr>
                    		<?php } ?>
                    		<tr>
                    			<td><b>Total</b></td>
                    			<td><b><?php echo $today;?></b></td>
                    			<td><b><?php echo $yest;?></b></td>
                    			<td><b><?php echo $week;?></b></td>
                    			<td><b><?php echo $month;?></b></td>
                    		</tr>
                    	</table>
                    </tr>
			   	</table>

			</fieldset>
   		</div>
   		<br class="clearLeft" />
    </div>
 </div>
<br class="clearRight" />
</div>
<script>
function report(){
	var html = $('submit').innerHTML;
	showLoader3('submit');

	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
        var state = $('state').value;
        var rm = $('rm').value;

        if(date_from=="" && date_to==""  ){
		$('date_err').innerHTML = "Error: Please select proper dates";
		$('date_err').show();
		$('submit').innerHTML = html;
	}

        var dt_from = new Date(date_from.split("-").reverse().join("-")).getTime();
        var dt_to = new Date(date_to.split("-").reverse().join("-")).getTime();
	//(dt_from = "" && dt_to == "")
        //alert(dt_from);alert(dt_to);
        if(dt_from > dt_to  ){
		$('date_err').innerHTML = "Error: Please select proper dates";
		$('date_err').show();
		$('submit').innerHTML = html;
	} else {
		$('date_err').hide();
		date_from = date_from.replace(/-/g,"");
		date_to = date_to.replace(/-/g,"");
		document.location.href="/shops/sReport/"+date_from+"-"+date_to+"/"+state+"/"+rm;
	}
}
</script>