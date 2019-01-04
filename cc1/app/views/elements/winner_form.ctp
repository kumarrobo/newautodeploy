<div style="width:500px;"><?php echo $form->create('winner'); ?>
     	<fieldset class="fields">
				<div class="field" style="padding-top:10px;">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="username"> Name </label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="1" type="text" id="username" name="data[name]" autocomplete="off" value="<?php if(!empty($data['vendors_retailers']['name'])) echo $data['vendors_retailers']['name']; ?>"/>
                         </div>                     
                 	</div>
            	 </div>
                 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="mobile">Mobile Number</label></div>
                         <div class="fieldLabelSpace">
                         	<input type="hidden" name="data[id]" value="<?php echo $data['vendors_retailers']['id']; ?>">
                         	<input tabindex="2" type="text" id="mobile" name="data[mobile]" value="<?php if(!empty($data['vendors_retailers']['mobile'])) echo $data['vendors_retailers']['mobile']; ?>"/>
                         </div>
                    </div>
            	 </div>
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="emailid">Email ID</label></div>
                         <div class="fieldLabelSpace">
                         	<input tabindex="3" type="text" id="email" name="data[email]" value="<?php if(!empty($data['vendors_retailers']['email'])) echo $data['vendors_retailers']['email']; ?>"/>
                         </div>
                    </div>
            	 </div>
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="address">Address</label></div>
                         <div class="fieldLabelSpace">
                         	<textarea tabindex="4" id="address" name="data[address]" style="width:140px;height:60px;"><?php if(!empty($data['vendors_retailers']['address'])) echo $data['vendors_retailers']['address']; ?></textarea>
                         </div>
                    </div>
            	 </div>
            	                  
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace" id="sub_butt">
                            <?php echo $ajax->submit('spacer.gif', array('id' => 'sub', 'tabindex'=>'8','url'=> array('controller'=>'offers', 'action'=>'winnerInfoUpdate'), 'class' => 'otherSprite oSPos7', 'after' => 'showLoader2("sub_butt");', 'update' => 'successPopupMsg')); ?>
                         
                         </div>       
                    </div>
                </div>		
		</fieldset>
<?php echo $form->end(); ?>
</div>
<script>
if($('username'))
	$('username').focus();	
</script>