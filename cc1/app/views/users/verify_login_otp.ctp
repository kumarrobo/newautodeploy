<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<div class="container">
    <fieldset>
        <form action="/users/verifyOTPAuthenticate" method="post">
            <font size="3">Please enter your OTP below :</font>
                <div class="field">
                    <div style="float:left;">
                        <label for="userMobile" style=" display:block; float:left; width:94px; padding-top:5px;">OTP :</label>
                        <input type="text" name="otp" tabindex="1" style="width: 200px;" />
                    </div>
                    <div style="color: red; margin-left: 20px; float: left;"><?php echo $this->Session->flash(); ?></div>
                    <input type="hidden" name="mobile" value="<?php echo $mobile; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                    <div style="clear:both;"></div>
                </div>
                <div class="field">
                    <label style=" display:block; float:left; width:94px;">&nbsp;</label>
                    <button style="background-color: black; color: white;" class="otherSprite" type="submit">Verify</button>
                    <span id="resend"><button style="background-color: black; color: white;" class="otherSprite" type="button" onclick="resendOTP();">Resend OTP</button></span>
	       	</div>
        </form>
    </fieldset>
</div>
<script>
    
    function resendOTP () {
        $.post('/users/resendOTPAuthenticate', {'mobile':'<?php echo $mobile ?>','user_id':'<?php echo $user_id ?>'}, function (e) {
            if (e.code == '101') {
                $('#resend').html("<span style='color:green; font-weight:bold; margin-left:20px;'>OTP sent to xxxxxx<?php echo substr($mobile,6,4) ?></span>");
            }
        }, 'json');
    }
    
</script>
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>