<div id= "sendsms<?php echo $msgId; ?>" class="calenderPopInner" style="width:385px;">

 <form accept-charset="utf-8" method="post" id="MessageSendForm">
 		
          <fieldset>
         
          <div class="field">
          	
          	Message will be sent to <?php echo $_SESSION['Auth']['User']['mobile']?>. Click OK to proceed
           	<input type="hidden" name="data[Message][id]" value="<?php echo $objMd5->encrypt($msgId,encKey);?>">
           	
          </div>
          <div class="field" id="cp_sub_sutt">	
            <?php echo $ajax->submit('OK', array('url'=> array('controller'=>'messages', 'action'=>'sendSMS'), 'update' => 'messagePopUpDiv' ,'after' => 'showLoader2("cp_sub_sutt");centerPos("popUpDiv");')); ?>
			
          </div>
         	
          </fieldset>
       </form>
             
</div>
<script>
function sendMessage(){
		popupSwap();
		var url = '/users/register';
		var params = {};
		//$('messagePopUpDiv').innerHTML = '<div align="center"><img src="/img/loader2.gif" /></div>';
		//centerPos('popUpDiv');
		showLoader2('errMessagePopUp');
		//$('errMessagePopUp').innerHTML = '<div align="center"><img src="/img/loader2.gif" /></div>';
		centerPos('errPopUp');
		new Ajax.Updater('messagePopUpDiv', url, {
	  			parameters: params,
	  			evalScripts:true,
	  			onComplete: function(response){$('errPopUp').hide();centerPos('popUpDiv');}
		});
	}
</script>