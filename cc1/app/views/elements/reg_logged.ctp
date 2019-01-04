<div class="scribeSuccess">
You are already registered with us. Please enter your password below to proceed.<br /><br />
<label style="margin-right:7px;">Mobile No.</label><input type='text' onkeydown='javascript: signup(event,"main")' value='<?php echo $mobile; ?>' id="userMobile" name='userMobile' class='' tabindex='4'><br /><br />
<label style="margin-right:15px;">Password</label><input type='password' name='userPassword' id="userPassword" value=''><br/>
<div style="height:21px;padding-top:10px">
<input type='image' onclick='login("in");' src="/img/spacer.gif" class="otherSprite oSPos25 leftFloat" value='Login now' tabindex='3'><span id='resPass' style='font-size:0.8em' class="leftFloat">&nbsp;or&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick='forgetPassword();'>Forgot Password</a></span>
</div>
<div class='errMessage' id='UloginErrMessage' style='padding-top: 5px;'></div>
<div id='responseMessage' style='padding-top: 5px;font-size:1.0em'></div>
<hr /><br />
<div id='resReg'><a href='javascript:void();' onclick='restoreReg("<?php echo $page; ?>");'>New User? SignUp now</a></div>
</div>                    