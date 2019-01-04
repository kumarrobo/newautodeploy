<?php echo $form->create('email'); ?>
     	<fieldset class="fields">
				<div class="field" style="padding-top:10px;">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="email"> Email ID </label></div>
                         <div class="fieldLabelSpace">
                            <input style="width:200px;" tabindex="4" type="text" id= "email" name="data[email]"/>
                         	<span class="hints">Give multiple email-ids in comma seperated format</span>
                         </div>                     
                 	</div>
            	 </div>
            	 
            	 <div class="field" style="padding-top:10px;">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="subject"> Subject </label></div>
                         <div class="fieldLabelSpace">
                            <input style="width:200px;" tabindex="5" type="text" id= "subject" name="data[subject]"/>
                         </div>                     
                 	</div>
            	 </div>
              
                 <div class="field" style="padding-top:10px;">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="body">Body</label></div>
                         <div class="fieldLabelSpace">
                            <textarea tabindex="6" id="body" name="data[body]" style="width:500px;height:355px;"></textarea>
                         </div>
                    </div>
            	 </div>
                 
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace" id="sub_butt">
                            <?php echo $ajax->submit('spacer.gif', array('id' => 'sub', 'tabindex'=>'7','url'=> array('action'=>'sendEmail'),'class' => 'otherSprite oSPos7','after' => 'showLoader2("sub_butt");$("submitted").innerHTML="Email Sent Successfully"', 'update' => 'content')); ?>
                         </div>
                         <div class="fieldLabelSpace" id="submitted"></div>                      
                    </div>
                </div>
		</fieldset>
<?php echo $form->end(); ?>