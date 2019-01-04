<?php if($pageType != 'csv'){?>

<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'salesman'));?>
                <?php if(isset($_SESSION['Auth']['system_used']) && $_SESSION['Auth']['system_used'] == 1) { ?>
            <b>-> <a href="/shops/salesmanReport/<?php echo date('d-m-Y')."/".date('d-m-Y'); ?>/0/0/0/1">Fetch New Data</a></b><br><br>
                <?php } ?>
    		<div id="innerDiv">
	  			<div>
	  				<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($from)) echo $from;?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($to)) echo $to;?>">
	
					<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="salesmanTranSearch();"></span>
					<a href="javascript:void(0);" title="Download old data ( 3 months before data ) " onclick="dwnldArchData ()">
<!--                            <img id="export_csv" class="export_csv" style="height:25px" src="/img/csv1.jpg" alt="xp" type="button"/>-->
                            Download Old Data
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
	  			<?php } else {
	  			
	  			?>
	  			<div class="appTitle">Topup Report (<?php echo $from . " - " . $to;?>)</div>
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Topup Report">
                <thead>
		          <tr class="noAltRow altRow">
		          	<th style="width:100px;">Transaction Id</th>
		            <th style="width:100px;">Salesman</th>
		            <th style="width:150px;">Retailer</th>
		            <th style="width:100px;">Retailer Mobile</th>
		            <th style="width:150px;">Note</th>
		            <th style="width:80px;" class="number">Amount</th>
		            <th style="width:80px;" class="number">Opening</th>
                            <th style="width:80px;" class="number">Closing</th>
                            <th style="width:100px;" class="number">Time</th>
		            <th style="width:80px;" class="number">Action</th>
		          </tr>
		        </thead>
		        <tbody>
		        <?php 
		        $i=0; $total_amt=0; 
	  			foreach($salesResult as $topup){ 
	  			$total_amt += $topup['st']['amount'];
	  			if($i%2 == 0)$class = '';
                	else $class = 'altRow';
                ?>	
                  <tr class="<?php echo $class; ?>">
                  	<td><?php echo $topup['st']['id']; ?></td>
		            <td><?php echo $topup['salesmen']['name']; ?></td>
		            <td><?php echo ($topup['ur']['shopname'] != '' ? $topup['ur']['shopname'] : $topup['r']['shopname']); ?></td>
		            <td><?php echo $topup['r']['mobile']; ?></td>
		            <td><?php if($topup['st']['type_flag'] == 1) echo 'Cash'; else if($topup['st']['type_flag'] == 2) echo 'NEFT'; else if($topup['st']['type_flag'] == 3) echo 'ATM Transfer'; else if($topup['st']['type_flag'] == 4) echo 'Cheque'; else if($topup['st']['type_flag'] == 5) echo 'Payment Gateway'; echo " - " . $topup['st']['note']; ?></td>
		            <td class="number"><?php echo $topup['st']['amount']; ?></td>
                            <td class="number"><?php echo $topup['st']['source_opening']; ?></td>
                            <td class="number"><?php echo $topup['st']['source_closing']; ?></td>
		            <td class="number"><?php echo $topup['sst']['created']; ?></td>
		            <?php 
		            	$date1 = new DateTime($topup['sst']['created']);
						$date2 = new DateTime(date('Y-m-d H:i:s'));
						$interval = $date1->diff($date2);
						//if($interval->d <= 3){
						if($topup['st']['type_flag'] != 5){
		            ?>
                    <td id="pullback_<?php echo $topup['sst']['id']; ?>" class="number"><a href="javascript:void(0);" onclick="pullback(<?php echo $topup['sst']['id']; ?>,<?php echo $topup['st']['id']; ?>)">Pull Back</a> </td>
		          	<?php } else {?>
		          	<td></td>
		          	<?php } ?>
		          </tr> 
                    <?php $i++; } if($i > 0) { ?>
                 <tfoot>   
			     <tr style="font-weight:bold"> 
		            <td><b>Total</b></td>
		            <td></td>
		            <td></td>
		            <td></td>
		            <td></td>
		            <td class="number"><b><?php echo $total_amt; ?></b></td>
		            <td></td>
		            <td></td>
		            <td></td>
		            <td></td>
		          </tr>
		          </tfoot>
			    <?php } ?> 					    			      
		         </tbody>	         
		   	</table>
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

function pullback(id,shop_tran_id){
	var r=confirm("Are You sure, you want to pull back this amount?");
	if(r==true){
		var html = $('pullback_'+id).innerHTML;
		$('pullback_'+id).innerHTML = "Submitted";
		var url = '/shops/pullback';
		var params = {'shop_transid': shop_tran_id,'salesman_transid':id};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{
					if(transport.responseText == 'success'){
						$('pullback_'+id).innerHTML = "Completed";
						alert('done');
						
					}else{
						$('pullback_'+id).innerHTML = html;
						alert(transport.responseText);
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