<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'topup'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<div>
    			<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    			
    			<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findHistory();"></span>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
				<div class="appTitle" style="margin-top:20px;">Topup History <?php if(isset($date_from) && isset($date_to)) echo "(". date('d-m-Y', strtotime($date_from)) . " - " .  date('d-m-Y', strtotime($date_to)) . ")"; ?></div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:80px;">Date</th>
			            <th style="width:150px;">Particulars</th>
			            <th class="number" style="width:70px">Credit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                    <th class="number" style="width:70px">Opening (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                    <th class="number" style="width:70px">Closing (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                    <!--<th class="number"  style="width:70px;">Invoice</th>-->                                    
			          	
                                  </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($transactions)) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Results Found !!</span></td>
                    </tr>
                    
                    <?php } else { ?>
                    <?php $i=0; foreach($transactions as $transaction){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    ?>
                      <tr class="<?php echo $class; ?>"> 
			            <td><?php echo date('d-m-Y H:i:s', strtotime($transaction['timestamp'])); ?></td>
                                    <td><?php $trans_type = $objGeneral->getTransferTypeName($transaction['type']);
                                        echo empty($trans_type) ? "---" : $trans_type;?></td>
			            <td class="number"><?php echo $transaction['amount']; ?></td>
                                    <td class="number"><?php echo empty($transaction['opening'])? 0 : $transaction['opening']; ?></td> 
                                    <td class="number"><?php echo empty($transaction['closing']) ? 0 : $transaction['closing']; ?></td> 
                                   <!-- <td class="number"><?php if(date('Y-m-d', strtotime($transaction['timestamp']))!=date('Y-m-d')): ?><a href="/shops/getInvoiceData/<?php echo $transaction['id']; ?>">Get Invoice</a><?php else: echo "NA"; endif; ?></td> -->
                                    
    			    <?php }
    			    $i++; } ?> 					    			      
			         </tbody>	         
			   	</table>
			   	<?php if(!empty($transactions)) { 
			   	$total_num = ceil($trans_count/PAGE_LIMIT);
			   	$min = (intval($page/5))*5 + 1;
			   	$max = (intval($page/5))*5 + 5;
			   	if($total_num < $max) $max = $total_num;
			   	$url = "/" . $this->params['controller'] . "/" . $this->params['action'] . "/" . (isset($this->params['pass'][0]) ? $this->params['pass'][0] : "") ;
			   	?>
			   	<div class="ie6Fix2 pagination" style="float:right;"> 
		           <div class="leftFloat"><span class="<?php if($page == $min) echo 'lightText';?>"><?php if($page != $min) {?> 
		           		<a href="<?php echo $url.'/'.$min; ?>" class="noAffect"><?php } echo "&lt;&lt; Previous"; if($page != $min) echo "</a>"; ?></span>
		           </div>
		           <div class="leftFloat paginationNo">
		           	<?php for($i = $min; $i <= $max; $i++) {?>
		           		<span class="<?php if($i == $page) echo 'current'; ?>"><?php if($i != $page) { ?> <a href="<?php echo $url.'/'.$i ;?>" class="lightText"> <?php } echo $i; if($i != $page) { echo "</a>"; } ?></span>
		           	<?php } ?>
		            <br class="clearLeft">
		          </div>
		          <div class="leftFloat"><span class="<?php if($page == $max) echo 'lightText';?>">
		          <?php if($page != $max){?> 
		           		<a href="<?php echo $url.'/'.$max; ?>" class="noAffect"><?php } echo "Next &gt;&gt;"; if($page != $max) echo "</a>"; ?></span>
		           </div> 
		          <div class="clearLeft"></div>
          		</div>
          		<div class="clearRight"></div>
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
function findHistory(){
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
		window.location.href = "/shops/topup/"+date_from+"-"+date_to;
	}
}
</script>