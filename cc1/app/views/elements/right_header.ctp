<?php if (!$this->Session->read('Auth.User')) { ?>
<div class="leftFloat smallerFont indiaOnly positionIndiOnly" ><i>[ Services available only in India ]</i></div>
<?php } ?>
<div class="rightFloat">
<?php if (!($this->Session->read('Auth.User'))) { ?>
<!-- Banner class="signup_T" -->
<div class="leftFloat">
	<div style="float:left; width:17px;"><img src="/img/bgLeft.png" style="height:84px;width:17px"></div>
	<div style="width:200px; margin-left:0px;">
	  <!-- <div class="signup_TL"> -->	    
	      <div style="margin:0 0 0 10px;background:#f5f5f5;height:8px;">&nbsp;</div>	    
	  <!-- </div> -->
	  <!-- <div class="signup_L"> -->
	  	<div class="signup_M" style="margin:0 0 0 17px;padding-left:0px;">
	  	  <a href="/retailers/all" border="0"><img src="/img/homeBanner.gif"></a>
	  	</div>
	  <!-- </div> -->
	  <!-- <div class="signup_BL"> -->
	      <div class="" style="margin:0 0 0 14px;background:#f5f5f5;border-bottom:1px solid #e0dede;height:8px;">&nbsp;</div>	    
	  <!-- </div> -->
	</div>
</div>
<!-- Banner ends -->
<?php } ?>
<div class="leftFloat">

<?php if ($this->Session->read('Auth.User')) { ?>

<div class="headerLinks1">
 <div class="globalLinks strng" style="float:right;">
  <ul>
    <li style="border-right:0px; padding-right:15px !important;font-weight:normal;">Welcome +91 <?php echo $this->Session->read('Auth.User.mobile');?>,</li>
    <?php if($objGeneral && $_SERVER['REQUEST_URI'] != "/users/view" && $_SERVER['REQUEST_URI'] != "/users/view/") {?>
    <!-- <li id="UserBalance" style="position:relative" class="fntSz17">Balance : <span style="position:absolute;top:-2px;"><img class='rupee1' src='/img/rs.gif'/></span><span style="padding-left:10px;">&nbsp;</span><?php echo number_format($objGeneral->getBalance($_SESSION['Auth']['User']['id']),2,'.','') ?> </li> -->
    <li><a href='/users/view'>Dashboard</a></li>
    <!--<li><a href='/users/rechargeBalance'>Recharge</a></li>-->
    <?php } ?>
	<li id="liCustSupport"><a href="/promotions/CSupport" alt="Customer Support">Customer Support</a></li>
    <li><a href="http://blog.smstadka.com/feedback" alt="Feedback opens in new window" target="_blank">Feedback</a></li>
    <li><a href="http://blog.smstadka.com/faq" target="_blank">FAQs</a></li>
    <li class="lastElement" style="padding-right:0px !important;margin-right:0px !important;"> <?php echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?></li>
  </ul>
  <br class="clearLeft" />
</div>
<br class="clearRight" />
 <div id="UserBalance" class="globalLinks strng fntSz17" style="text-align:right; float:right; padding-top:50px;">
 Balance : <span><img class='rupee1' src='/img/rs.gif'/></span><?php echo number_format($objGeneral->getBalance($_SESSION['Auth']['User']['id']),2,'.','');?>
  &nbsp;&nbsp;<span style="color:#CCC;font-weight:normal">|</span>&nbsp;&nbsp; <a href="/users/paynow">Pay here</a>
</div> <!-- -->
<br class="clearRight" />
<?php
						}
						else {?>
						
<div style="width:433px; margin-right:20px;">
  <div class="signup_TL">
    <div class="signup_TR">
      <div class="signup_T">&nbsp;</div>
    </div>
  </div>
  <div class="signup_L">
    <div class="signup_R">
      <div class="signup_M">
        <div class="signupCont">
          <div class="leftFloat" style="padding-right:10px">
            <div>
              <label for="mobile" class="fontType1">Mobile No.</label>
            </div>
            <div style="padding-top:5px;">
              <input type="text" id="mobile" tabindex="1" style="width:143px" name="data[User][mobile]"/>
            </div>
            <?php if($_SERVER['REQUEST_URI'] != "/index.php") {?>
            <div class="signup_link2">New User?<a href="javascript:void(0);" onclick="register();"> Register here</a></div>
            <?php } ?>
          </div>
          <div class="leftFloat" style="padding-right:10px">
            <div>
              <label for="password" class="fontType1">Password</label>
            </div>
            <div style="padding-top:5px;">
              <input type="password" id="password" tabindex="2" name="data[User][password]" style="width:143px" autocomplete="off" onkeydown="javascript: signin(event,'top')"/>
            </div>
            <div class="lightText signup_link2"><a href="javascript:void(0);" onclick="forgetPassword();">Forgot&nbsp;password?</a></div>
          </div>
          <div class="leftFloat">
            <div>&nbsp;</div>
            <div id="loginButt" style="padding-top:5px;">
              <input tabindex="3" type="image" value="Submit" src="/img/spacer.gif" class="otherSprite oSPos5" onclick="login('top')" />
            </div>
            <div style="padding-top:5px;" id="loginErrMessage" class="errMessage"></div>
          </div>
          <br class="clearLeft" />
        </div>
      </div>
    </div>
  </div>
  <div class="signup_BL">
    <div class="signup_BR">
      <div class="signup_B">&nbsp;</div>
    </div>
  </div>
  <?php } ?>
</div>
</div>
</div>
<script>
	                    if($('mobile'))
	                    	$('mobile').focus();
	                    </script>
