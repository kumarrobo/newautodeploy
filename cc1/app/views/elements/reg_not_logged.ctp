<div class="scribeSuccess">
You are already registered with us, If you have not received the password yet, just <strong>give a miss call on 07666888676</strong> to get it.
<br/><br/>
Enter password received on <strong><?php echo $mobile; ?></strong>
<input type='hidden' name='userMobile' id='userMobile' value='<?php echo $mobile; ?>'>
<input type='password' name='userPassword' id='userPassword' value=''><br />
<div style="height:21px;padding-top:10px">
<input type='image' onclick='login("in");' src="/img/spacer.gif" class="otherSprite oSPos25 leftFloat" value='Login now' tabindex='3'><span id='resPass' style='font-size:0.8em'  class="leftFloat">&nbsp;or&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick='resendPassword();'>Re-send Password</a></span>
</div>
<div class='errMessage' id='UloginErrMessage' style='padding-top: 5px;'></div>
<div id='responseMessage' style='padding-top: 5px;font-size:1.0em'></div>
</div>