
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'incentive_pullback'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<div>
    			<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    			
    			<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="refunddata();"></span>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
				<div class="appTitle" style="margin-top:20px;">Transaction History <?php if(isset($date_from) && isset($date_to)) echo "(". date('d-m-Y', strtotime($date_from)) . " - " .  date('d-m-Y', strtotime($date_to)) . ")"; ?></div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:80px;">Txn Id</th>
						<th style="width:80px;">Txn Date</th>
			            <th style="width:80px;">Particulars</th>
						<th style="width:80px;">User Type</th>
						<th style="width:80px;">Company</th>
			            <th style="width:80px;">Amount</th>
			            <th style="width:80px;">Narration</th>
					    <th style="width:110px;">Time</th>
						<th style="width:110px;">Action</th>
			          </tr>
			        </thead>
                    <tbody>
                     <?php if(isset($date_limit) && $date_limit == 0) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">Date difference cannot be greater than 7 days !!</span></td>
                    </tr>
                    
                    <?php }  ?>
                    <?php $i=0; foreach($transaction as $transrecord){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
						
						if($transrecord[0]['company'] == ''){
							$comp = $transrecord['users']['mobile'];
						} else {
							$comp = $transrecord[0]['company'];
						}
						if($transrecord['shop_transactions']['target_id'] == RETAILER){
							$usertype = 'Retailer';
						} else {
							$usertype = 'Distributor';
						}
						
					
                    ?>
                      <tr class="<?php echo $class; ?>"> 
			            <td><?php echo $transrecord['shop_transactions']['id'];?></td>
						 <td><?php echo $transrecord['shop_transactions']['date'];?></td>
			            <td style="align:center"><?php echo "Incentive" ;?></td>
						<td style="align:center"><?php echo $usertype ;?></td>
						<td style="align:center"><?php echo $comp ;?></td>
			            <td class="" style="align:center;"><?php echo $transrecord['shop_transactions']['amount'];?></td>
			            <td class="" style="align:center;"><?php echo $transrecord['shop_transactions']['note'];?></td>
			            <td class="" style="align:center;"><?php echo date('Y-m-d H:i:s',strtotime($transrecord['shop_transactions']['timestamp']));?></td>
						<td><?php if($transrecord['shop_transactions']['confirm_flag'] == 0) { ?><a href ="javascript:void(0);" onclick="Pullback(<?php echo $transrecord['shop_transactions']['id']; ?>)">Pullback</a><?php } ?></td>
			          </tr>
			         <?php $i++;} ?>    					    			      
			         </tbody>	         
			   	</table>
			  
			</fieldset>
   			</div>
</div>
 </div>
<br class="clearRight" />
</div>

<script>
function refunddata(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
	if(date_from == '' || date_to == ''){
		$('date_err').show();
		$('submit').innerHTML = html;
	}
	else {
		$('date_err').hide();
		date_from = date_from.replace(/-/g,"");
		date_to = date_to.replace(/-/g,"");
		window.location = siteprotocol + "//" + siteName + "/shops/incentivePullback/"+date_from+"-"+date_to;
	}
}

function Pullback(shopid){
	
	var url = '/shops/pullbackRefund/';
	var pars = "shop_id="+shopid;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{
						    alert(transport.responseText);
							var date_from = $('fromDate').value;
	                        var date_to = $('toDate').value;
						     date_from = date_from.replace(/-/g,"");
		                     date_to = date_to.replace(/-/g,"");
							 window.location = siteprotocol + "//" + siteName + "/shops/incentivePullback/"+date_from+"-"+date_to;
					}
				});
	
}
</script>
