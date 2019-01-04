<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'credit'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<div>
    			<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    			
    			<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findNotes();"></span>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
				<div class="appTitle" style="margin-top:20px;">Credit/Debit Note For You</div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:80px;">Date</th>
			            <th style="width:80px;">Type</th>
			            <th style="width:80px;">Receipt</th>
			            <th style="width:120px;">Particulars</th>
			            <th class="number" style="width:70px">Amount (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($creditDebits['to'])) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Results Found !!</span></td>
                    </tr>
                    
                    <?php } ?>
                    <?php $i=0; foreach($creditDebits['to'] as $transaction){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    ?>
                      <tr class="<?php echo $class; ?>"> 
                    	<td><?php echo date('d-m-Y H:i:s', strtotime($transaction['shop_creditdebit']['timestamp'])); ?></td>
                    	<td><?php if($transaction['shop_creditdebit']['type'] == 0) echo "Credit Note"; else echo "Debit Note";?></td>
                    	<td><a href="javascript:void(0);" onclick="showNote('<?php echo $objMd5->encrypt($transaction['shop_creditdebit']['id'],encKey); ?>')"><?php echo $transaction['shop_creditdebit']['numbering'];?></a></td>
                    	<td>From: <?php if(!empty($transaction['0']['shop']))echo $transaction['0']['shop']; else echo SMSTADKA_COMPANY;?><br/><span style="font-size:10pt">Desc: <?php echo $transaction['shop_creditdebit']['description']; ?></span></td>
                    	<td class="number"><?php echo $transaction['shop_creditdebit']['amount']; ?></td>
                      </tr>	
                    <?php } ?>
                    </tbody>
                  </table>
                <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) { ?>  
                <div class="appTitle" style="margin-top:20px;">Credit/Debit Note Given By You</div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:80px;">Date</th>
			            <th style="width:80px;">Type</th>
			            <th style="width:80px;">Receipt</th>
			            <th style="width:120px;">Particulars</th>
			            <th class="number" style="width:70px">Amount (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($creditDebits['from'])) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Results Found !!</span></td>
                    </tr>
                    
                    <?php } ?>
                    <?php $i=0; foreach($creditDebits['from'] as $transaction){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    ?>
                      <tr class="<?php echo $class; ?>"> 
                    	<td><?php echo date('d-m-Y H:i:s', strtotime($transaction['shop_creditdebit']['timestamp'])); ?></td>
                    	<td><?php if($transaction['shop_creditdebit']['type'] == 0) echo "Credit Note"; else echo "Debit Note";?></td>
                    	<td><a href="javascript:void(0);" onclick="showNote('<?php echo $objMd5->encrypt($transaction['shop_creditdebit']['id'],encKey); ?>')"><?php echo $transaction['shop_creditdebit']['numbering'];?></a></td>
                    	<td>To: <?php echo $transaction['0']['shop'];?><br/><span style="font-size:10pt">Desc: <?php echo $transaction['shop_creditdebit']['description']; ?></span></td>
                    	<td class="number"><?php echo $transaction['shop_creditdebit']['amount']; ?></td>
                      </tr>	
                    <?php } ?>
                    </tbody>
                  </table>
                <?php } ?>    
			</fieldset>
   			</div>
   			<br class="clearLeft" />
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>
<script>
function showNote(id){
	var url = "/shops/printCreditDebitNote/" + id;
	window.open(url,"Request","width=700,height=600,scrollbars=1");
}
function findNotes(){
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
		window.location = "http://" + siteName + "/shops/getCreditDebitNotes/"+date_from+"-"+date_to;
	}
}
</script>