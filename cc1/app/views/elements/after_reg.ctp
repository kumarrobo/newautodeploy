<div class="scribeSuccess">
Check your mobile. You will receive a password shortly on <strong><?php echo $mobile; ?></strong>.<br/><br/>
 Enter the received password below<br/>
<input type="hidden" id="userMobile" name="userMobile" value="<?php echo $mobile; ?>">
<input type="password" id="userPassword" name="userPassword" value=""><br/>
<div style="height:21px;padding-top:10px">
<input type="image" onclick="login('in');" src="/img/spacer.gif" class="otherSprite oSPos25 leftFloat" value="Login now" tabindex="3"><span id="resPass" style="font-size:0.8em"  class="leftFloat">&nbsp;or&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="resendPassword();">Re-send Password</a></span>
</div>
<div class="errMessage" id="UloginErrMessage" style="padding-top: 15px; display:block"></div>
<div style="font-size:1.0em" id="responseMessage" style="padding-top: 15px;"></div>
<div style="padding-top: 2px;" >If not received for 2 mins, <strong>give a missed call to 07666888676</strong> to get your password.</div>
</div>