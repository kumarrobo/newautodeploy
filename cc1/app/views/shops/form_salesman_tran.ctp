<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_activities',array('side_tab' => 'cashtran'));?>
    		<div id="innerDiv">
	  			<?php echo $this->element('shop_salesman_tran',array('salesmans' => $salesmans)); ?>	  			
   			</div>
   			<br class="clearLeft" />
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>