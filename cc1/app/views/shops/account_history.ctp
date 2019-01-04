<div>
    <?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">

    		<?php
    		 
    		 echo $this->element('shop_side_reports',array('side_tab' => 'history'));

    		 	if($this->Session->read('Auth.User.group_id') != SUPER_DISTRIBUTOR){
    		 ?>
                <b>-> <a href="/shops/salesmenAccountHistory/">Salesmen Account History</a></b><br><br>
                <?php } if(isset($_SESSION['Auth']['system_used']) && $_SESSION['Auth']['system_used'] == 1) { ?>
                <b>-> <a href="/shops/accountHistory/0/1/<?php echo $report == 1 ? 0 : 1 ?>">Fetch <?php echo $report == 1 ? "Old" : "New" ?> Data</a></b><br><br>
                <?php } ?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<div>
    			<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    			
    			<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findHistory();"></span>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
				<div class="appTitle" style="margin-top:20px;">Transaction History <?php if(isset($date_from) && isset($date_to)) echo "(". date('d-m-Y', strtotime($date_from)) . " - " .  date('d-m-Y', strtotime($date_to)) . ")"; ?></div>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:80px;">Txn Id</th>
			            <th style="width:150px;">Particulars</th>
			            <th class="number" style="width:70px">Debit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			            <th class="number" style="width:70px">Credit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			            <th class="number" style="width:70px">Opening (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			            <th class="number" style="width:70px">Closing (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			            <th style="width:80px;">Time</th>
						
			          </tr>
			        </thead>
                    <tbody>
                     <?php if(isset($date_limit) && $date_limit == 0) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">Date difference cannot be greater than 7 days !!</span></td>
                    </tr>
                    
                    <?php } else if(!isset($empty) && empty($transactions)) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Results Found !!</span></td>
                    </tr>
                    
                    <?php } ?>
                    <?php $i=0; foreach($transactions as $transaction){
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';

                    	if(in_array($transaction['transactions']['type'],array(MDIST_SDIST_BALANCE_TRANSFER,COMMISSION_SUPERDISTRIBUTOR,PULLBACK_SUPERDISTRIBUTOR))) {
                    		$style="background-color: greenyellow;";
                    	}else{
                    		$style="";
                    	}
                    ?>
                      <tr class="<?php echo $class; ?>" style="<?php echo $style; ?>">
                            <?php //if($report == 0) { ?>
                                    <td><?php echo $transaction['transactions']['id'];?></td>
			            <td><?php echo $transaction['transactions']['name'] . " (" . $transaction['transactions']['refid'] . ")" ;?></td>
			            <td class="number"><?php if(!empty($transaction['transactions']['debit'])) echo round($transaction['transactions']['debit'],2); else echo "-";?></td>
			            <td class="number"><?php if(!empty($transaction['transactions']['credit'])) echo round($transaction['transactions']['credit'],2); else echo "-";?></td>
			            <td class="number"><?php echo $transaction['transactions']['opening'];?></td>
			            <td class="number"><?php echo $transaction['transactions']['closing'];?></td>
			            <td><?php echo $transaction['transactions']['timestamp'];?></td>




<?php //} else { ?>
			           <!-- <td><?php echo $transaction['transactions']['id'];?></td>
			            <td><?php echo $transaction['transactions']['name'] . " (" . $transaction['transactions']['refid'] . ")" ;?></td>
                                    <td class="number"><?php if(!empty($transaction['transactions']['debit'])) echo round($transaction['transactions']['debit'],2); else echo "-";?></td>
                                    <td class="number"><?php if(!empty($transaction['transactions']['credit'])) echo round($transaction['transactions']['credit'],2); else echo "-";?></td>
                                    <td class="number"><?php // if($transaction['opening_closing']['opening'] == '' && !isset($oc[$transaction['transactions']['id']]['opening'])) { echo "-"; } else { if(!empty($transaction['transactions']['credit'])) echo round($transaction['transactions']['credit'],2); else echo "-"; }?></td>
			            <td class="number"><?php echo $transaction['transactions']['opening'] == '' ? $oc[$transaction['transactions']['id']]['opening'] ? $oc[$transaction['transactions']['id']]['opening'] : '-' : $transaction['opening_closing']['opening'];?></td>
			            <td class="number"><?php echo $transaction['transactions']['closing'] == '' ? $oc[$transaction['transactions']['id']]['closing'] ? $oc[$transaction['transactions']['id']]['closing'] : '-' : $transaction['opening_closing']['closing'];?></td>
			            <td><?php echo $transaction['0']['stamp'];?></td> -->
                            <?php //} ?>
			          </tr>
                                <?php 
                                    $corr = $transaction['transactions']['opening'];
                                    $i++;
                            } 
                                 ?>    					    			      
			         </tbody>	         
			   	</table>
			   	<?php if(!empty($transactions)) { 
			   	$total_num = ceil($trans_count/PAGE_LIMIT);
			   	$min = (intval($page/5))*5 + 1;
			   	$max = (intval($page/5))*5 + 5;
			   	if($total_num < $max) $max = $total_num;
			   	$url = "/" . $this->params['controller'] . "/" . $this->params['action'] . "/" . $this->params['pass'][0];
			   	?>
			   	<div class="ie6Fix2 pagination" style="float:right;"> 
		           <div class="leftFloat"><span class="<?php if($page == $min) echo 'lightText';?>"><?php if($page != $min) {?> 
		           		<a href="<?php echo $url.'/'.$min."/".$this->params['pass'][2]; ?>" class="noAffect"><?php } echo "&lt;&lt; Previous"; if($page != $min) echo "</a>"; ?></span>
		           </div>
		           <div class="leftFloat paginationNo">
		           	<?php for($i = $min; $i <= $max; $i++) {?>
		           		<span class="<?php if($i == $page) echo 'current'; ?>"><?php if($i != $page) { ?><a href="<?php echo $url.'/'.$i ."/".$this->params['pass'][2];?>" class="lightText"><?php } echo $i; if($i != $page) { echo "</a>"; } ?></span>
		           	<?php } ?>
		            <br class="clearLeft">
		          </div>
		          <div class="leftFloat"><span class="<?php if($page == $max) echo 'lightText';?>">
		          <?php if($page != $max){?> 
		           		<a href="<?php echo $url.'/'.$max."/".$this->params['pass'][2]; ?>" class="noAffect"><?php } echo "Next &gt;&gt;"; if($page != $max) echo "</a>"; ?></span>
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
                window.location = siteprotocol+"//" + siteName + "/shops/accountHistory/"+date_from+"-"+date_to+"/1/"+<?php echo $report; ?>;
	}
}


</script>
