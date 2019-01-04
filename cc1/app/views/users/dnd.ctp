<div class="title3" style="padding-top: 12px;margin-left:24px;margin-bottom:0px;">Do Not Disturb Registry</div>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody>
  	<!-- <tr id="Title">
		<td width="100%" style="padding-left:24px;"></td>
		<td colspan="2" width="216" align="right" style="padding-right: 8px; padding-top: 12px;"/>
	
  	</tr> -->   
  	<tr>  
    	<td colspan="2" style="padding-top: 12px; background-color: rgb(255, 255, 255);">

			<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding-left: 23px;">
  			<tbody>
  			
		  <tr><td></td><td>
		  
		  <div name="blockform" id="blockform">
		  <table><tbody>
		  <tr>
		  	<td  style="padding-top: 0px;" width="350px;" valign="top">
			  	<h3>
			  		<?php 
			  		if($mobile){
			  			echo "Your mobile number :";
			  		}else{
			  			echo "Enter your mobile number :";
			  		} 
			  		?>
			  	</h3>
		  	</td>
		  	<td>
		  		<?php if($mobile){
		  				echo $mobile."<input type='hidden'  name='dnd' id='dnd' value='".$mobile."' />";
		  			  }else{ 
		  		?>
		  		<input type="text"  name="dnd" id="dnd" />
		  		<?php } ?>
		  	</td>
		  </tr>
		  <tr>
		  	<td style="padding-top: 0px;" width="350px;" valign="top">
		  		<h3>Enter mobile number(s) you wish to block :</h3>
		  		<span style="font-size:0.9em"> Maximum 10 numbers and use comma (,) to separate multiple numbers. You will not get any message from these numbers. If you wish to unblock them, contact support@smstadka.com</span> 
		  	</td>
		  	<td>
		  		<textarea name="tobeblocked" id="tobeblocked" rows="6" style="overflow: auto;"></textarea>
		  	</td>		  	
		  </tr>
		  <tr>
		  	<td style="padding-top: 0px;" width="350px;" valign="top">
		  		<h3>Word Verification :</h3></td>
		  	<td style="padding-top: 20px;">
		  		Type the characters you see in the picture below. <br><br>
		  		<?php $i = rand(0,1000);?>
		  		<img id="captcha" src="/users/captcha?<?php echo $i;?>" alt="" />
		  		<a href="javascript:void(0);" class="smallerFont" onclick="javascript:var rNumber = Math.floor(Math.random()*1001); document.images.captcha.src='/users/captcha?'+rNumber"><img alt="Click to reload the code" title="Click to reload the code" src="/img/spacer.gif" class="oSPos3 otherSprite" /></a>
		  		<br><br><input type="text"  name="captchaCode" id="captchaCode" />
		  		<br><br><div id="dnderrordiv" name="dnderrordiv" style="display:none;"></div>
		  	</td>		  	
		  </tr>
		  
		  <tr>
		  	<td  style="padding-top: 0px;" width="350px;" valign="top">&nbsp;
		  		
		  	</td>
		  	<td style="padding-top: 10px;">
		  		<a href="javascript: void(0);" onclick="mobileVerCodeSend()"><img src="/img/spacer.gif" class="otherSprite oSPos10"  /></a>
		  	</td>	
		  		  	
		  </tr>
		  
		</tbody>
		</table>
		  </div>
		  </td></tr>
		</tbody>
		</table>
		


    	</td>
	</tr>
	  <tr>
	    <td  colspan="2" style="padding-top: 12px;"></td>
	  </tr>
	  <tr>
	     <td colspan="2" style="padding-top: 12px;"/>
		</tr>
	  <tr>
	     <td colspan="2" style="padding-top: 12px;"/>
	  </tr>
	  <tr>
	     <td colspan="2" style="padding-top: 12px;"/>
	  </tr>
	  <tr>
	     <td colspan="2" style="padding-top: 12px;"/>
	  </tr>
	</tbody>
</table>