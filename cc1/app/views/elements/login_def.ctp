<div id="loginDiv" class="signUp" style="height:auto;width:685px;">			
	<div class="clearLeft"></div>
	<div class="leftFloat innerSignin" style="width:339px; border-right:1px solid #ccc;">
      	<div class="popupTitle color2 popupTitlePadd" style="margin-top:0px">Already Registered? Sign in</div>
  		<div style="padding:0px 10px">
  		<fieldset>
			<legend>Enter Your 10 Digit Mobile Number</legend>
			<div class="field">
				<label for="userMobile" style=" display:block; float:left; width:94px; padding-top:10px;">Mobile No.</label><input tabindex="4" type="text" id="userMobile" value="" style="width: 185px !important;"/>
 			</div>
            <div class="field">				
				<label for="userPassword" style=" display:block; float:left; width:94px; padding-top:10px;">Password</label><input tabindex="5" type="password" id="userPassword" style="width: 185px !important;" onkeydown="javascript: signin(event,'in')" value="" />
			</div>
			
			<div class="field" style="padding-top:16px;">
               <label style=" display:block; float:left; width:94px;">&nbsp;</label><div id="loginSignIn" style="float:left"><input tabindex="6" type="image" value="Submit" src="/img/spacer.gif" class="otherSprite oSPos9" onclick="login('in');" style="padding-left:0px; float:left;"/></div><span style="font-size:0.8em;">&nbsp;or&nbsp;<a href="javascript:void(0)" onclick="forgetPassword();">Forgot password?</a></span>
	       	</div>
	       	<div class="clearLeft"></div>
            <div class="field">
               <label style=" display:block; float:left; width:94px;">&nbsp;</label><span id="UloginErrMessage" class="errMessage"></span> 
	       	</div>    
		</fieldset>
  		</div>
	</div>	
    <div style="margin-left:339px;" class="innerSignup">        
        <div class="popupTitle color2 popupTitlePadd" style="margin-top:0px">Register here</span></div>
        <div style="padding:0px 10px">
        	<div class="fntSz17" style="margin-top:20px;">
				<span class="color2"> <span class="strng">Step 1:</span> <br/>Give a missed call to <span class="color3  strng">07 666 888 676</span> <br /><br/>
				<span class="strng">Step 2:</span> <br/>Receive password on your mobile<br/><br/>
				<span class="strng">Step 3:</span> <br/>Login using mobile number and the password</span>
			</div>
        </div>
        </div>
      </div>
      <script>
/*if($('userMobile'))
	$('userMobile').focus();*/ //for IE fix
</script>