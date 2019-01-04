<?php if($status == 'S'){ ?>
<div style="border:1px solid;padding:10px 10px 10px 10px;text-align:left;color:#008000;"><strong>Transaction Successful</strong></div>
<?php } ?>
<?php if($status == 'F'){ ?>
<div style="border:1px solid;padding:10px 10px 10px 10px;text-align:left;color:#FF0000;"><strong>Transaction Failed</strong></div>
<?php } ?>
<div class="blankState">&nbsp;</div>
<div class="field">Your account balance is <span><img src="/img/rs.gif" class="rupee1"></span><span class="highlight"><?php echo number_format($balance,2,'.','') ?></span>
<br><br>
<div class="field">Your Transaction ID is <span class="highlight"><?php echo $tid; ?></span>
<br> 
<span style="font-size: 0.9em;"> (Please note down your Transaction ID. It can be used for transaction related queries)</span>


</div>
<!--<div>Following is your pending subscription</div>-->

<div class="blankState">&nbsp;</div>
<?php if($_SESSION['Auth']['User']['id'] == ""){ ?>
<div class="popOutline" style="width:350px;margin:auto;">
 	<div class="popCont popContWidth">
 		<div>
			
			<div style="height:22px;">&nbsp;</div> 
			<div >			
      			<div class="signUp" >			
				<div class="innerSignin" style="width:339px;margin:auto;">
					<div class="SampJokesTitle" style="padding-left:20px;color:#FF0000;font-size:1em">Session Expired!!!<br><br></div>
					<div class="SampJokesTitle" style="padding-left:20px;padding-bottom:20px;font-size:1em">Please sign in to continue.</div>
			      	<!--<div class="SampJokesTitle" style="padding-left:20px;">Sign in</div> -->
			  		<div style="padding:0px 10px">
			  		<fieldset>
						<legend>Enter Your 10 Digit Mobile Number</legend>
						<div class="field">
							<label for="userMobile" style=" display:block; float:left; width:94px; padding-top:10px;">Mobile No.</label><input tabindex="4" type="text" id="userMobile" value="" style="width: 200px;"/>
			 			</div>
			            <div class="field">				
							<label for="userPassword" style=" display:block; float:left; width:94px; padding-top:10px;">Password</label><input tabindex="5" type="password" id="userPassword" style="width: 200px;" onkeydown="javascript: signin(event,'in')" value="" />
						</div>
						
						<div class="field">
			               <label style=" display:block; float:left; width:94px;">&nbsp;</label><input tabindex="6" type="image" value="Submit" src="/img/spacer.gif" class="otherSprite oSPos9" onclick="login('in');"/> 
				       	</div>
			            <div class="field">
			               <label style=" display:block; float:left; width:94px;">&nbsp;</label><span id="UloginErrMessage" class="errMessage"></span> 
				       	</div>    
					</fieldset>
			  		</div>
				</div>
			</div> 
  			</div>
  		</div>
  	</div>	
</div>
<?php }else{ ?>
					<div class="SampJokesTitle" style="padding: 10px 0px 20px 0px;font-size:1em"><a href="/users/view" style="color:white !important" class="retailBut enabledBut strng">Continue browsing SMSTadka ...</a></div>			      	
<?php } ?> 
