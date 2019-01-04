<div class="scribeSuccess" style="padding-top:20px;">
Your mobile <strong><?php echo $mobile; ?></strong> is successfully registered. To receive your password <strong>give a missed call to 07666888676</strong>.<br/><br/>
 Enter the received password below<br/>
<input type="hidden" id="userMobile" name="userMobile" value="<?php echo $mobile; ?>">
<input type="password" id="userPassword" name="userPassword" value=""><br/>
<div style="height:21px;padding-top:10px">
<input type="image" onclick="login('in');" src="/img/spacer.gif" class="otherSprite oSPos25 leftFloat" value="Login now" tabindex="3">
</div>
<div class="errMessage" id="UloginErrMessage" style="padding-top: 15px; display:block"></div>
<div style="font-size:1.0em" id="responseMessage" style="padding-top: 15px;"></div>
</div>