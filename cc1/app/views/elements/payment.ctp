<div class="loginCont">
  <div>
    <div class="leftFloat dashboardPack">
      <div class="catList">
        <ul id='innerul'>                       
          <li name='innerli'><?php echo $ajax->link( 
    				'Online Payment ', 
    				array('action' => 'recharge'),
    				array('id' => 'directPay', 'class' => 'sel', 'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);' ,'complete' => '$("directPay").removeClassName("loader")')
					); ?> </li>
          <li name='innerli'><?php echo $ajax->link( 
    				'Cheque/DD ', 
    				array('action' => 'cheque'),
    				array('id' => 'cheque', 'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("cheque").removeClassName("loader")' )
					); ?> </li>
		 <li name='innerli'><?php echo $ajax->link( 
    				'Billing Details ', 
    				array('action' => 'billDetails'),
    				array('id' => 'bdetails', 'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("bdetails").removeClassName("loader")' )
					); ?> </li>
		<li name='innerli'><?php echo $ajax->link( 
    				'Customer Support ', 
    				array('action' => 'custSupport'),
    				array('id' => 'custSupport', 'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("custSupport").removeClassName("loader")' )
					); ?> </li>						                  
        </ul>
      </div>
    </div>
    
    <div style="margin-left:223px;" id="innerDiv">
        <?php echo $this->element('recharge',array('data' => $data));?>
    </div>
    <br class="clearLeft" />
 </div>