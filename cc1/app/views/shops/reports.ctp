<div class="loginCont">
  <div>
    <div class="leftFloat dashboardPack">
      <div class="catList">
        <ul id='innerul'>
        	<li name='innerli'><?php echo $ajax->link( 
    				'Transfer Details', 
    				array('action' => 'transDetails'),
    				array('id' => 'transDetails','update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("transDetails").removeClassName("loader")' )
					); ?> </li>
					
		</ul>
      </div>
    </div>
    <div style=" float:left;" id="innerDiv">
      	<?php
      	echo "<script>$('transDetails').simulate('click');</script>";
      	?>
    </div>
    <br class="clearLeft" />
 </div>