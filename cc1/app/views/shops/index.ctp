
<div>
    <div style="width:330px; height:300px; float:left;">
        <fieldset>
			<legend>Enter Your 10 Digit Mobile Number</legend>
			<div class="field">
				<?php echo $this->Session->flash();?>
			</div>
			<div class="field">
				<label for="userMobile" style=" display:block; float:left; width:94px; padding-top:10px;">Username</label><input type="text" id="userMobile" tabindex="1" style="width: 200px;" />
 			</div>
                        <div class="field">				
				<label for="userPassword" style=" display:block; float:left; width:94px; padding-top:10px;">Password</label><input tabindex="2" type="password" id="userPassword" style="width: 200px;"  value="" onkeydown="javascript: signin(event,'retail')"/>
			</div>
                        <div class="field">				
				<label for="userGroup" style=" display:block; float:left; width:94px; padding-top:10px;">Login as</label>
                                <select id="userGroup" style="margin-top: 6px; height: 25px; border-radius: 5px; background-color: white;" tabindex="3">
                                   <?php foreach($login_as as $login) { ?>
                                    <option value="<?php echo $login['groups']['id'] ?>"
                                            <?php if ($usergroup ==  $login['groups']['id'] ){
                                                echo "selected";
                                            }
                                    ?>><?php echo $login['groups']['name']; ?></option>
                                    <?php } ?>
                                </select>
			</div>
			
			<div class="field">
              <label style=" display:block; float:left; width:94px;">&nbsp;</label>
              <div id="loginSignIn" style="margin-left:94px;"><input tabindex="4" type="image" value="Submit" src="/img/spacer.gif" class="otherSprite oSPos9" onclick="login('retail');"/>
	       		</div>
	       	</div>
	       	<div class="field">
               <label style=" display:block; float:left; width:94px;">&nbsp;</label><span id="UloginErrMessage" class="errMessage"></span> 
	       	</div>
                        <div class="field">
                                    <br/>
                                    <label>Did you forgot password ?</label><br/>
                                   <a href="/users/reset">Click here</a>
                                   <label>to get new one</label>
                        </div>
                
        </fieldset>
    </div>
    <div style="float: left; margin-left: 200px;">
        <table style="border-collapse: collapse;">
            <tr><th style="border: 2px solid black; line-height: 2em;">&nbsp;&nbsp;* GST Helpline</th></tr>
            <tr><td style="border: 2px solid black; line-height: 1.8em; width:255px; padding: 10px;">For any queries related to <span style="color: blue; font-weight: bold;">GST</span> & <span style="color: blue; font-weight: bold;">TDS</span>, Please contact <span style="font-weight: bold; color: red;">022 42932220</span> <br>Working hours : Mon - Fri (10am - 8pm) <br/> <br/>Mindsarray Technologies Pvt Ltd (Pay1) <br/> GST registration no. : <b> 27AAFCM5069H2Z7</b></td></tr>
        </table>
    </div>
    <div style="clear:both;"></div>
</div>  



                     
      <script>
if($('userMobile'))
	$('userMobile').focus();
</script>
