<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php 
                if($retailer_type == 'deleted'){
                    $c_type = 'deletedRetailer';
                }else{
                    $c_type = 'allretailer';
                } 
                echo $this->element('shop_side_activities',array('side_tab' => $c_type));?>
    		<div id="innerDiv">
    		<?php echo $this->element('allRetailers',array('retailer_type'=>$retailer_type)); ?>	  			  			  			
   			</div>
   			<br class="clearLeft" />
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>