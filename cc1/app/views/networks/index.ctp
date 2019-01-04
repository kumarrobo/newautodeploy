<div style="width:330px;height:300px;">	
      	<fieldset>
			<legend>Enter Your 10 Digit Mobile Number</legend>
			<div class="field">
				<?php echo $this->Session->flash();?>
			</div>
			<div class="field">
				<label for="userName" style=" display:block; float:left; width:94px; padding-top:10px;">Username</label><input type="text" id="userName" tabindex="1" style="width: 200px;" />
 			</div>
            <div class="field">				
				<label for="userPassword" style=" display:block; float:left; width:94px; padding-top:10px;">Password</label><input tabindex="2" type="password" id="userPassword" style="width: 200px;"  value="" onkeydown="javascript: signin(event,'network')"/>
			</div>
			
			<div class="field">
              <label style=" display:block; float:left; width:94px;">&nbsp;</label>
              <div id="loginSignIn" style="margin-left:94px;"><input tabindex="3" type="image" value="Submit" src="/img/spacer.gif" class="otherSprite oSPos9" onclick="login('network');"/>
	       		</div>
	       	</div>
	       	<div class="field">
               <label style=" display:block; float:left; width:94px;">&nbsp;</label><span id="UloginErrMessage" class="errMessage"></span> 
	       	</div>
                
		</fieldset>
		
  		</div>
	</div>
</div>	
      <script>
if($('userName'))
	$('userName').focus();
</script>