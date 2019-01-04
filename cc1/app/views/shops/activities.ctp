<div class="loginCont">
    <div class="leftFloat dashboardPack">
      <div class="catList">
        <ul id='innerul'>
        	<?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) { ?>
        	 <li>
        	 <a class="hList" href="javascript:void(0);"><p>
        	 	<?php echo 'Distributor';?>
        	 	</p>
        	 </a>
          	
        	<div class="sublist">
        		<ul>
        			<li name='innerli'><?php echo $ajax->link( 
    				'Balance Transfer', 
    				array('action' => 'transfer'),
    				array('id' => 'transfer','update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("transfer").removeClassName("loader")' )
					); ?> </li>
					<!--<li name='innerli'><?php /*echo $ajax->link( 
    				'Allot Cards', 
    				array('action' => 'allotCards'),
    				array('id' => 'allotCards','update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("allotCards").removeClassName("loader")' )
					); */?> </li>-->
        			<li name='innerli'><?php echo $ajax->link( 
    				'List Distributors', 
    				array('action' => 'allDistributor'),
    				array('id' => 'allDistributor', 'class' => 'sel','update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("allDistributor").removeClassName("loader")' )
					); ?> </li>
		  			<li name='innerli'><?php echo $ajax->link( 
    				'Create Distributor',
    				array('action' => 'formDistributor'),
    				array('id' => 'formDistributor','update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("formDistributor").removeClassName("loader")' )
					); ?> </li>
					
        		</ul>
        	</div>
        	</li>
        	<?php } else if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) { ?>
        		<li>
        	 <a class="hList" href="javascript:void(0);"><p>
        	 	<?php echo 'Retailer';?>
        	 	</p>
        	 </a>
          	
        	<div class="sublist">
        		<ul>
        			<li name='innerli'><?php echo $ajax->link( 
    				'Balance Transfer', 
    				array('action' => 'transfer'),
    				array('id' => 'transfer','update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("transfer").removeClassName("loader")' )
					); ?> </li>
					<li name='innerli'><?php echo $ajax->link( 
    				'Activate Cards', 
    				array('action' => 'allotCards'),
    				array('id' => 'allotCards','update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("allotCards").removeClassName("loader")' )
					); ?> </li>
        			<li name='innerli'><?php echo $ajax->link( 
    				'All Retailers', 
    				array('action' => 'allRetailer'),
    				array('id' => 'allRetailer', 'class' => 'sel','update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("allRetailer").removeClassName("loader")' )
					); ?> </li>
		  			<li name='innerli'><?php echo $ajax->link( 
    				'Create Retailer',
    				array('action' => 'formRetailer'),
    				array('id' => 'formRetailer','update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("formRetailer").removeClassName("loader")' )
					); ?> </li>
					
        		</ul>
        	</div>
        	</li>
        	<?php } ?>
        	
		</ul>
    </div>
  </div>  
  <div id="innerDiv"> <!-- style=" float:left;" -->
	  	<?php
	  	echo "<script>$('transfer').simulate('click');</script>";
	  	?>
   </div>
   <br class="clearLeft" />
 </div>			