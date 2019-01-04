<div id="loginDiv">
<form id="UserCaptchaForm" name= "usercaptchaform" method="post" accept-charset="utf-8" onsubmit="javascript: return false;" >
		<div class="fntSz24 strng"><span class="color2">Register here</span> </div>
        
        <div class="fntSz17" style="margin-top:20px;">
		<span class="color2"> <span class="strng">Step 1:</span> <br/>Give a missed call to <span class="color3  strng">07 666 888 676</span> <br /><br/>
<span class="strng">Step 2:</span> <br/>Receive password on your mobile<br/><br/>
<span class="strng">Step 3:</span> <br/>Login using mobile number and the password</span>
		</div>
</div>
<script>
	
	document.usercaptchaform.reset();
	
	Event.observe('mobileLogin', 'click', function(event) {
		if ($('mobileLogin').value == signupDefault) {
			$('mobileLogin').removeClassName('disableTextStart');
			$('mobileLogin').clear();
		}				
	});
				
	Event.observe('mobileLogin', 'blur', function(event) {
		var valid;
		valid = $('mobileLogin').present();		
		if (!valid) {			
			$('mobileLogin').addClassName('disableTextStart');
			$('mobileLogin').value = signupDefault;		
		}		
	});

</script>
