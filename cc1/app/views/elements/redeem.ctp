<div style="width:760px;"> 
    <div>       
        <div>
          <div class="box2" style="margin:0px;">
            <!-- <div class="header ie6Fix2">Active Packages</div> -->
            <div class="pack2" style="margin:0px 10px 15px 0px;" >
	            <table border="0" cellpadding="0" cellspacing="0" width="100%" summary="Transactions">
		        	<caption class="header">Redeem Coupon</caption>        
		           	<tr class="noAltRow">
		           		<td>
		           			<br/>SMSTadka is offering FREE credits to its users. If you have an SMSTadka free coupon code, you can redeeem it below and earn Rs.<?php echo REDEEM_AMT; ?> credits! <a href="javascript:void(0);" onclick="$('hiw').simulate('click');">Click here to know more</a>.
		           			<br/><br/><b>Get Rs.<?php echo REDEEM_AMT; ?> SMSTadka FREE credits instantly!</b><br/>
		           			<p class="blankState">
		           				<?php //$url="http://www.dtadka.com/users/view/"; ?>
		           			<?php echo $form->create('redeem'); ?>
		           			Coupon Code: <input type="text" name="data[Promotion][freeCreditCode]" id="freeCreditCode" value="<?php if(isset($cc))echo $cc; ?>"> </p>
		           			<div id="sub_butt" style="padding-top:5px">
		           			<?php echo $ajax->submit('Redeem Now', array('id' => 'sub', 'tabindex'=>'8','url'=> array('controller'=>'promotions', 'action'=>'redeemCredits'), 'class' => 'retailBut enabledBut strng', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
		           			</div>
		           			 <div style="margin-bottom:20px"><?php echo $this->Session->flash();?></div>		           			
		           			<?php echo $form->end(); ?>
		           			And also you can create your own coupon code and share it with your friends, family, workmates or anybody on the internet. If people redeem the coupon code shared by you, on every redemption, you get Rs.<?php echo SHARER_CREDIT; ?> instantly in your account. <br/><a href="/messages/refCode">Start sharing</a> or <a href="javascript:void(0);" onclick="$('hiw').simulate('click');">Click here to know more</a>.
		           		</td>
		           	</tr>          
	        	</table>
          	</div>
        </div>
    </div>
</div>
<br class="clearRight" />
</div>