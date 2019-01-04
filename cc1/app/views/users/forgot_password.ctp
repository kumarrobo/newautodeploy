<div class="signUp" style="height:auto;width:335px;"> 
<div id='forgotPassword' class="innerSignup">
        <div class="popupTitle color2 popupTitlePadd"><?php if(isset($msg)) echo $msg; else echo "Forgot Password?";?></div>
        <div style="padding:0px 10px"> 
        	<?php if(isset($_SESSION['displayDiv'])) $displayDiv = $_SESSION['displayDiv'];?>
			<span class="color2"> <span class="strng">Step 1:</span> Send SMS: FORGOT to <span class="color3  strng">0<?php echo VIRTUAL_NUMBER;?></span> <br/><br/>
<span class="strng">Step 2:</span> Receive password on your mobile<br/><br/>
<span class="strng">Step 3:</span> Login using your new password</span><br/><br/>
        </div>
        </div>
      </div>
      
 </div>     

<script>
	
	Event.observe('fmobileLogin', 'click', function(event) {
		if ($('fmobileLogin').value == signupDefault) {
			$('fmobileLogin').removeClassName('disableTextStart');
			$('fmobileLogin').clear();
		}				
	});
				
	Event.observe('fmobileLogin', 'blur', function(event) {
		var valid;
		valid = $('fmobileLogin').present();		
		if (!valid) {			
			$('fmobileLogin').addClassName('disableTextStart');
			$('fmobileLogin').value = signupDefault;		
		}		
	});
	
	//$('fmobileLogin').focus();

</script>