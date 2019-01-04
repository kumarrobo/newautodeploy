<div style="width:310px;">
		<div id="message" class="SampJokesTitle" style="padding-left:20px;display:none">Already Registered? Sign In</div>
  		<div style="padding: 0px 10px 0px 40px">
  		
	  		<fieldset>
				<legend>Enter Your 10 Digit Mobile Number</legend>
				<div class="field">
					<?php echo $this->Session->flash();?>
				</div>
				<div class="field">
					<label for="suserMobile" style=" display:block; float:left; width:94px; padding-top:10px;">Mobile No.</label><input type="text" id="suserMobile" tabindex="4" value="<?php if(isset($mobile)) echo $mobile; ?>" style="width: 200px;" />
	 			</div>
	            <div class="field">				
					<label for="suserPassword" style=" display:block; float:left; width:94px; padding-top:10px;">Password</label><input tabindex="5" type="password" id="suserPassword" style="width: 200px;"  value="" onkeydown="javascript: signin(event,'')"/>
				</div>
				
				<div class="field">
	              <label style=" display:block; float:left; width:70px;">&nbsp;</label>
	              <div id="sloginSignIn" style="margin-left:70px;"><input tabindex="6" type="image" value="Submit" src="/img/spacer.gif" class="otherSprite oSPos9" onclick="login('');"/>
		       		</div>
		       	</div>
		       	<div class="field">
	               <label style=" display:block; float:left; width:94px;">&nbsp;</label><span id="sUloginErrMessage" class="errMessage"></span> 
		       	</div>
	                
			</fieldset>
		
  		</div>
</div>