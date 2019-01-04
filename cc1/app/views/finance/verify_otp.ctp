<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<?php
$error = $this->Session->flash();
if($error != ""){
    ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php
}
if($this->Session->read('Auth.User.group_id') == SUPER_ADMIN){
?>
<form action="/finance/verifyOtp" method="post">
    

<br/>
<div style="float:left;"><span style="font-weight:bold;">Verify OTP :</span></div>
<div style="float:left;"><input type="text" class="form-control" maxlength="6" minlength="6" style="width: 150px; margin-top: -5px; margin-left: 15px;" id="verify_otp" name="verify_otp" required ></div>

<div style="float:left; margin-left: 30px; margin-top: -5px;"><input class="btn btn-primary" type="submit" value="Submit" style="padding: 5px 10px;"></div>
</form>
<br/><br/><br/>
<?php
}
?>

