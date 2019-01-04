<div style="width:330px;">
<?php 
	if(isset($_SESSION['displayDiv'])) $displayDiv = $_SESSION['displayDiv'];
?>
	
      	<div class="popupTitle color2 popupTitlePadd">Already Registered? Sign In</div>
  		<div style="padding:0px 0px 0px 10px">
  		
  		<fieldset>
			<legend>Enter Your 10 Digit Mobile Number</legend>
			<div class="field">
				<?php echo $this->Session->flash();?>
			</div>
			<div class="field">
				<label for="userMobile" style=" display:block; float:left; width:94px; padding-top:10px;">Mobile No.</label><input type="text" id="userMobile" tabindex="4" value="<?php if(isset($mobile)) echo $mobile; ?>" style="width: 200px;" />
 			</div>
            <div class="field">				
				<label for="userPassword" style=" display:block; float:left; width:94px; padding-top:10px;">Password</label><input tabindex="5" type="password" id="userPassword" style="width: 200px;"  value="" onkeydown="javascript: signin(event,'in')"/>
			</div>
			
			<div class="field">
              <label style=" display:block; float:left; width:94px;">&nbsp;</label>
              <div id="loginSignIn" style="margin-left:94px;"><input tabindex="6" type="image" value="Submit" src="/img/spacer.gif" class="otherSprite oSPos9" onclick="login('in');"/>
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
if($('userPassword'))
	$('userPassword').focus();	
</script>
<?php if($flag == 'redeem') echo "<script>$('userMobile').focus();</script>"; ?>