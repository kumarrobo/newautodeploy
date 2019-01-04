<?php if($pageType != 'csv') { ?>

<div>
    <?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports')); ?>
    <div id="pageContent" style="min-height:500px;position:relative;">
            <div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'salesman'));?>
                <?php if(isset($_SESSION['Auth']['system_used']) && $_SESSION['Auth']['system_used'] == 1) { ?>
                <span><b>-> <a href="/shops/salesmanReport/<?php echo date('d-m-Y')."/".date('d-m-Y'); ?>">Fetch Old Data</a></b></span>
                        <?php if($reports == 1) { ?>
                        <span style='margin-left: 50px;'><b>-><a href='/shops/salesmanReport/<?php echo date('d-m-Y')."/".date('d-m-Y'); ?>/0/0/0/2'> Switch to Salesman Transactions</a></b></span>
                        <?php } else { ?>
                        <span style='margin-left: 50px;'><b>-><a href='/shops/salesmanReport/<?php echo date('d-m-Y')."/".date('d-m-Y'); ?>/0/0/0/1'> Switch to Distributor Transactions</a></b></span>
                        <?php } ?>
                <br><br>
                <?php } ?>
    		<div id="innerDiv">
	  			<div>
	  				<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($from)) echo $from;?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($to)) echo $to;?>">
	
					<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="salesmanTranSearch();"></span>
					<a href="javascript:void(0);" title="Download old data ( 3 months before data ) " onclick="dwnldArchData ()">
<!--                            <img id="export_csv" class="export_csv" style="height:25px" src="/img/csv1.jpg" alt="xp" type="button"/>-->
                            Download Data
                    </a>
                    <div style="margin-top:10px;">
                                            <span style="font-weight:bold;margin-right:10px;">Select Salesman: </span>
						<select id="salesman">
					   		<option value="0">ALL</option>
							<?php foreach($salesmans as $salesman) {?>
								<option value="<?php echo $salesman['salesmen']['id'];?>" <?php if(isset($id) && $id == $salesman['salesmen']['id']) echo "selected";?>><?php echo $salesman['salesmen']['name'] . " - " . $salesman['salesmen']['mobile'] ; ?></option>
							<?php } ?>
						</select>
                                            <span style="font-weight:bold;margin-right:10px;">Select Retailer: </span>
						<select id="retailer">
					   		<option value="0">ALL</option>
							<?php foreach($retailers as $retailer) {?>
								<option value="<?php echo $retailer['Retailer']['id'];?>" <?php if(isset($rid) && $rid == $retailer['Retailer']['id']) echo "selected";?>><?php echo $retailer['Retailer']['shopname'] . " - " . $retailer['Retailer']['mobile'] ; ?></option>
							<?php } ?>
						</select>
					</div>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
	  			<fieldset style="padding:0px;border:0px;margin:0px;margin-top:15px;">
	  			<?php if(empty($salesResult)) { ?>
	  			<span class="success">No Results Found !!</span>
	  			<?php } else { ?>
	  			<div class="appTitle">Topup Report (<?php echo $from . " - " . $to;?>)</div>
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Topup Report">
                        <thead>
                            <tr class="noAltRow altRow">
                                <th style="width:100px;">Transfer Type</th>
                                <th style="width:100px;">Txn Id</th>
                                <th style="width:50px;" class="number">Amount</th>
                                <th style="width:100px;">Salesman</th>
                                <th style="width:100px;">Salesman Mobile</th>
                                <th style="width:80px;">Salesman Opening</th>
                                <th style="width:80px;">Salesman Closing</th>
                                <th style="width:150px;">Retailer</th>
                                <th style="width:100px;">Retailer Mobile</th>
                                <th style="width:80px;">Retailer Opening</th>
                                <th style="width:80px;">Retailer Closing</th>
                                <th style="width:150px;">Note</th>
                                <th style="width:200px;">Time</th>
                                <th style="width:125px;" class="number">Action</th>
                            </tr>
		        </thead>
		        <tbody>
                        <?php if($reports == 1) { ?>
		        <?php 
                                $i=0; $total_amt=0;
	  			foreach($salesResult as $topup){ 
	  			$total_amt += $topup['txn']['amount'];
	  			if($i%2 == 0) $class = '';
                                else $class = 'altRow';
                                $salesman_id = $topup['txn']['retailer_mobile'] != '' ? $topup['txn']['ref1_id'] : $topup['txn']['ref2_id'];
                        ?>
                        <tr class="<?php echo $class; ?>">
		            <td><?php echo trim($topup['txn']['retailer_mobile']) != '' ? '<span style="color:blue;">Retailer</span>' : '<span style="color:red;">Salesman</span>'; ?></td>
                            <td><?php echo trim($topup['txn']['retailer_mobile']) != '' ? $topup['txn']['id'].' / '.$topup['txn']['user_id'] : $topup['txn']['id']; ?></td>
		            <td><center><?php echo round($topup['txn']['amount']); ?></center></td>
                            <td><a href="/shops/salesmanReport/<?php echo date('d-m-Y').'/'.date('d-m-Y').'/'.$salesman_id; ?>/0/0/2"><?php echo $topup['txn']['salesmen_name']; ?></a></td>
		            <td><?php echo $topup['txn']['salesmen_mobile']; ?></td>
                            <td><?php echo round($topup['txn']['salesman_opening'],2); ?></td>
                            <td><?php echo round($topup['txn']['salesman_closing'],2); ?></td>
		            <td><?php echo $topup['txn']['ur_shopname'] != '' ? $topup['txn']['ur_shopname'] : ($topup['txn']['retailer_shopname'] != '' ? $topup['txn']['retailer_shopname'] : '<center>-</center>'); ?></td>
		            <td><?php echo $topup['txn']['retailer_mobile'] != '' ? $topup['txn']['retailer_mobile'] : '<center>-</center>'; ?></td>
		            <td><?php echo $topup['txn']['retailer_opening'] != '' ? round($topup['txn']['retailer_opening'],2) : '<center>-</center>'; ?></td>
		            <td><?php echo $topup['txn']['retailer_closing'] != '' ? round($topup['txn']['retailer_closing'],2) : '<center>-</center>'; ?></td>
		            <td><?php if($topup['txn']['type_flag'] == 1) echo 'Cash'; else if($topup['txn']['type_flag'] == 2) echo 'NEFT'; else if($topup['txn']['type_flag'] == 3) echo 'ATM Transfer'; else if($topup['txn']['type_flag'] == 4) echo 'Cheque'; else if($topup['txn']['type_flag'] == 5) echo 'Payment Gateway'; echo $topup['txn']['note'] != '' ? " - " . $topup['txn']['note'] : ''; ?></td>
		            <td><?php echo date('d-M-Y h:i:s A', strtotime($topup['txn']['timestamp'])); ?></td>
		            <?php 
                                    $date1 = new DateTime($topup['txn']['salesmen_created']);
                                    $date2 = new DateTime(date('Y-m-d H:i:s'));
                                    $interval = $date1->diff($date2);
                                    //if($interval->d <= 3){
                                    if($topup['txn']['type_flag'] != 5){
		            ?>
                            <td id="pullback_<?php echo $topup['txn']['id']; ?>" class="number"><a href="javascript:void(0);" onclick="pullback(<?php echo $topup['txn']['retailer_mobile'] != '' ? $topup['txn']['id'].','.RETAILER : $topup['txn']['id'].','.SALESMAN; ?>)">Pull Back</a> </td>
		          	<?php } else { ?>
		          	<td></td>
		          	<?php } ?>
		          </tr> 
                    <?php $i++; } if($i > 0) { ?>
                        <tfoot>   
                            <tr style="font-weight:bold"> 
                                <td colspan="2"><b>Total</b></td>
                                <td class="number"><b><center><?php echo $total_amt; ?></center></b></td>
                                <td colspan="11"></td>
                            </tr>
                        </tfoot>
                        <?php } } else { ?>
                        <?php 
                                $i=0; $total_amt=0; 
	  			foreach($salesResult as $topup){ 
	  			$total_amt += $topup['st']['amount'];
	  			if($i%2 == 0) $class = '';
                                else $class = 'altRow';
                        ?>
                        <tr class="<?php echo $class; ?>">
                            <td style="color: #E01E31">Salesman-Retailer</td>
                            <td><?php echo $topup['st']['id']; ?></td>
		            <td><center><?php echo round($topup['st']['amount']); ?></center></td>
		            <td><?php echo $topup['salesmen']['name']; ?></td>
		            <td><?php echo $topup['salesmen']['mobile']; ?></td>
                            <td><?php echo $topup['st']['source_opening']; ?></td>
                            <td><?php echo $topup['st']['source_closing']; ?></td>
		            <td><?php echo ($topup['ur']['shopname'] != '' ? $topup['ur']['shopname'] : $topup['retailers']['shopname']); ?></td>
		            <td><?php echo $topup['retailers']['mobile']; ?></td>
                            <td><?php echo $topup['st']['target_opening']; ?></td>
                            <td><?php echo $topup['st']['target_closing']; ?></td>
		            <td><?php if($topup['st']['type_flag'] == 1) echo 'Cash'; else if($topup['st']['type_flag'] == 2) echo 'NEFT'; else if($topup['st']['type_flag'] == 3) echo 'ATM Transfer'; else if($topup['st']['type_flag'] == 4) echo 'Cheque'; else if($topup['st']['type_flag'] == 5) echo 'Payment Gateway'; echo $topup['st']['note'] != '' ? " - " . $topup['st']['note'] : ''; ?></td>
		            <td><?php echo date('d-M-Y h:i:s A', strtotime($topup['st']['timestamp'])); ?></td>
		            <?php 
                                    $date1 = new DateTime($topup['salesmen']['created']);
                                    $date2 = new DateTime(date('Y-m-d H:i:s'));
                                    $interval = $date1->diff($date2);
                                    //if($interval->d <= 3){
                                    if($topup['st']['type_flag'] != 5){
		            ?>
                            <td id="pullback_<?php echo $topup['st']['id']; ?>" class="number"><a href="javascript:void(0);" onclick="pullback(<?php echo $topup['st']['id'].','.SLMN_RETL_BALANCE_TRANSFER; ?>)">Pull Back</a> </td>
		          	<?php } else {?>
		          	<td></td>
		          	<?php } ?>
		          </tr> 
                        <?php $i++; } if($i > 0) { ?>
                        <tfoot>   
                            <tr style="font-weight:bold"> 
                                <td colspan="2"><b>Total</b></td>
                                <td class="number"><b><center><?php echo $total_amt; ?></center></b></td>
                                <td colspan="11"></td>
                            </tr>
                        </tfoot>
		   	<?php } ?>
		   	<?php } ?>
                        </tbody>	         
		   	</table>
                        </div>
		   	<?php } ?>
		   	</fieldset>
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>
<script>

function salesmanTranSearch(){
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
	
    var dt_from = new Date(date_from.split("-").reverse().join("-")).getTime();
    var dt_to = new Date(date_to.split("-").reverse().join("-")).getTime();
	
    if(dt_from > dt_to){//if(date_from > date_to){
		$('date_err').innerHTML = "Error: Please select proper dates";
		$('date_err').show();
	} else {
		var salesman = $('salesman').options[$('salesman').selectedIndex].value;
        var retailer = $('retailer').options[$('retailer').selectedIndex].value;
		document.location.href="/shops/salesmanReport/"+$('fromDate').value+"/"+$('toDate').value+"/"+salesman+"/"+retailer+"/0/<?php echo $reports ?>";
	}
}

function pullback(shop_tran_id,transfer_type){
	var r=confirm("Are You sure, you want to pull back this amount?");
	if(r==true){
		var html = $('pullback_'+shop_tran_id).innerHTML;
		$('pullback_'+shop_tran_id).innerHTML = "Submitted";
		var url = '/shops/pullbackNew';
		var params = {'shop_transid': shop_tran_id,'transfer_type':transfer_type};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{
					if(trim(transport.responseText) == 'success'){
						$('pullback_'+shop_tran_id).innerHTML = "Completed";
						alert('done');
						
					}else{
						$('pullback_'+shop_tran_id).innerHTML = html;
						alert(trim(transport.responseText));
					}
				}
		});
		
	}
}
        
function dwnldArchData (){
        
        var date_from = $('fromDate').value;
        var date_to = $('toDate').value;

        var dt_from = new Date(date_from.split("-").reverse().join("-")).getTime();
        var dt_to = new Date(date_to.split("-").reverse().join("-")).getTime();
        if(dt_from > dt_to){//if(date_from > date_to){
            $('date_err').innerHTML = "Error: Please select proper dates";
            $('date_err').show();
            return;
        }else if((dt_to - dt_from)/(1000*60*60*24) > 365 ){//if(date_from > date_to){
            $('date_err').innerHTML = "Error: Can't get more than 1 year data .";
            $('date_err').show();
            return;
        }else{
            $('date_err').hide();
            //var salesman = $('shop').options[$('shop').selectedIndex].value;
            date_from = date_from.replace(/-/g,"");
            date_to = date_to.replace(/-/g,"");
            var salesman = $('salesman').options[$('salesman').selectedIndex].value;
            var retailer = $('retailer').options[$('retailer').selectedIndex].value;
            var url ="/shops/salesmanReport/"+$('fromDate').value+"/"+$('toDate').value+"/"+salesman+"/"+retailer+"/0/<?php echo $reports ?>";
            var newWindow = url+"?res_type=csv&old_data=old_csv";
            window.open(newWindow, '_blank');
        }
        
}

  
</script>

<?php } ?>