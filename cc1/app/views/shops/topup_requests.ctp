<div id="bg" class="popOutline" style="position: absolute; z-index: 989; top:0;left:0;display:none;width:98%;">
</div>
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_activities',array('side_tab' => 'topup'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<div class="appTitle" style="margin-top:20px;">Topup Requests</div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Topup Requests">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:150px;">Date</th>
			            <th style="width:150px;">Retailer</th>
			            <th>Amount</th>
			            <th>Payment Mode</th>
			            <th>Status</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($data)) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Results Found !!</span></td>
                    </tr>
                    <?php }?>
                    <?php $i=0; foreach($data as $dt){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    ?>
                    <tr class="<?php echo $class; ?>">
                    	<td><?php echo date('d-m-Y H:i:s', strtotime($dt['topup_request']['created'])); ?></td>
			            <td><?php echo $dt['retailers']['name'] . " - " . $dt['retailers']['id']; ?></td>
                    	<td><?php echo $dt['topup_request']['amount'];?></td>
                    	<td><?php if($dt['topup_request']['type'] == MODE_CASH) echo "Cash"; else if($dt['topup_request']['type'] == MODE_CHEQUE) echo "Cheque"; else if($dt['topup_request']['type'] == MODE_DD) echo "DD"; else echo "NEFT";?></td>
                    	<td id="status"><?php if($dt['topup_request']['approveStatus'] == 0) 
                    	echo $ajax->link( 
	    				'Approve', 
	    				array('action' => 'approve',$dt['topup_request']['id']),
	    				array('id' => 'approve', 'class' => 'sel','update' => 'status')
						);
						else echo 'Approved';?></td>
                    	<td></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table>
                 </fieldset>
   			</div>
   			<br class="clearLeft" />
    	</div>
 	</div>
	<br class="clearRight" />
</div>