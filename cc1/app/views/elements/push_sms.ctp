 <textarea id='textContent' class="editSMS" onKeyUp="resCharacters('textContent',<?php echo APP_REM_MSG_LMT; ?>,'charCount');"></textarea>
 <span id='charCount' class="hints">Upto <?php echo APP_REM_MSG_LMT; ?> chars</span>
 
 <br/><div id='sendSMS'><input tabindex='3' type="image" src="/img/spacer.gif" class="otherSprite oSPos8" onClick="pushMessage('<?php echo $mobile; ?>');"></div>
 <br><span id="success"></span>
 
 <script>
 function pushMessage(mobile){
	var url = '/messages/pushSMS';
	var rand   = Math.random(9999);
	var pars   = "mobile="+mobile+"&text="+encodeURIComponent($('textContent').value);
	var html = $('sendSMS').innerHTML;
	showLoader2('sendSMS');
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{ 	
						$('sendSMS').innerHTML = html;
						$('success').innerHTML = "SMS sent successfully";
						$('textContent').value = '';
					}
				});
}
</script>
