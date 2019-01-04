<div style="width:500px; float: left;"><?php echo $form->create('ChangePassword'); ?>
     	<!--<form id="UserLoginForm" method="post" accept-charset="utf-8" action="/users/login">-->
			
		<fieldset class="fields">
			<legend><?php if(isset($par)) echo "Change Your Password"; else echo "Change Password";?></legend>
				<div class="field" style="padding-top:10px;">
               		<div class="fieldLabelMand leftFloat">
						<label>*</label>
					</div>
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="pass1"> Current password</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="1" type="password" id="pass1" name="data[User][pass1]" autocomplete="off"/>
                            <br /><?php if(isset($errFlag) && $errFlag == '1') {?><span id="err_pname" class="inlineErr" >You have entered a wrong password</span> <?php } ?>
                         </div>                     
                 	</div>
            	 </div>
                 
                 <div class="field">
               		<div class="fieldLabelMand leftFloat">
						<label>*</label>
					</div>
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="pass2">New password</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="2" type="password" id="pass2" name="data[User][pass2]"  />
                            <br /><span id="err_pname" class="inlineErr" style="display:none">Please enter a new password</span>
                         </div>
                    </div>
            	 </div>
            	 
            	 <div class="field">
               		<div class="fieldLabelMand leftFloat">
						<label>*</label>
					</div>
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="pass3">Re-enter new password</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="3" type="password" id="pass3" name="data[User][pass3]"  />
                            <br /><span id="err_pname" class="inlineErr" style="display:none">password doesn't match</span>
                         </div>
                    </div>
            	 </div>
                 
                 <div class="field">
               		<div class="fieldLabelMand leftFloat">
						<label>&nbsp;</label>
					</div>
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace" id="cp_sub_sutt">
                         	
                            <?php 
                            	echo $ajax->submit('spacer.gif', array('tabindex' => '4','url'=> array('controller'=>'users', 'action'=>'changePassword'), 'class' => 'otherSprite oSPos7', 'after' => 'showLoader2("cp_sub_sutt")', 'update' => 'innerDiv','condition' => 'changePassValidation()')); 
                            
                            ?>
                         </div>
                    </div>
            	 </div>			
		</fieldset>
<?php echo $form->end(); ?>
</div>
<div style="float: left; margin-left: 50px; margin-top: 50px;">
    <ul>
        <li>New Password should be more than 8 characters</li>
        <li>New Password should be atleast 1 alphabet</li>
        <li>New Password should be atleast 1 number</li>
    </ul>
</div>
<script>
if($('pass1'))
	$('pass1').focus();
/*if($('popUpDiv').style.display != 'none')	
	$('popUpDiv').hide();*/	
</script>