<?php echo $form->create('billdetails'); ?>
     	<fieldset class="fields">
			<div class="title3">Billing Details</div>
				<p class="fieldDetail" style="margin-top:5px;margin-bottom:15px;">Enter your billing details below for easy recharge process</p>
				<div class="field" style="padding-top:10px;">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="username"> Name </label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="1" type="text" id="username" name="custName" autocomplete="off" value="<?php if(isset($data['billing_user']['name']))echo $data['billing_user']['name']; ?>"/>
                         </div>                     
                 	</div>
            	 </div>
              
                 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="address">Billing Address</label></div>
                         <div class="fieldLabelSpace">
                            <textarea tabindex="2" id="address" name="custAddress" style="width:215px;height:55px;"><?php if(isset($data['billing_user']['address']))echo $data['billing_user']['address']; ?></textarea>
                         </div>
                    </div>
            	 </div>
            	 
            	  <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="city">City</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="3" type="text" id="city" name="custCity" value="<?php if(isset($data['billing_user']['city']))echo $data['billing_user']['city']; ?>"/>
                            </div>
                    </div>
            	 </div>
            	 
            	  <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="state">State</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="4" type="text" id="state" name="custState" value="<?php if(isset($data['billing_user']['state']))echo $data['billing_user']['state']; ?>"/>
                            </div>
                    </div>
            	 </div>
            	 
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="zip">Pin Code</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="5" type="text" id="zip" name="custPinCode" value="<?php if(isset($data['billing_user']['zip']))echo $data['billing_user']['zip']; ?>"/>
                            </div>
                    </div>
            	 </div>
            	 
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="country">Country</label></div>
                         <div class="fieldLabelSpace">
                            <span id="country" name="custCountry"> India </span>
                         </div>
                    </div>
            	 </div>
            	 
            	 
            	 
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="email">Email Address</label></div>
                         <div class="fieldLabelSpace">
                             <input tabindex="6" type="text" id="email" name="custEmailId" value="<?php if(isset($data['billing_user']['email']))echo $data['billing_user']['email']; ?>"/>
                         </div>
                    </div>
            	 </div>
            	 
            	  <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="other">Other Notes or Instructions</label></div>
                         <div class="fieldLabelSpace">
                             <textarea tabindex="7" id="other" name="otherNotes" style="width:215px;height:55px;"><?php if(isset($data['billing_user']['other']))echo $data['billing_user']['other']; ?></textarea>
                         </div>
                    </div>
            	 </div>
            	 
            	 
                 
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace" id="sub_butt">
                            <?php echo $ajax->submit('spacer.gif', array('id' => 'sub', 'tabindex'=>'8','url'=> array('controller'=>'users', 'action'=>'changeBillDetails'),'class' => 'otherSprite oSPos7','after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <div class="field">    
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace" style="color:#004B91">
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
            	 </div>			
		</fieldset>
<?php echo $form->end(); ?>
<script>
if($('username'))
	$('username').focus();	
</script>