function enter(par){
		var username = $('userName').value;
		var password = $('userPassword').value;
		

		var innerHTML = $('loginSignIn').innerHTML;
		showLoader2('loginSignIn');
		var url = '/networks/afterLogin';
		var params = {'username' : username,'password' : password};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{		
					var text = transport.responseText.replace(/<!--[^(-->)]+-->/g, '');
					if(text == '1'){ //login success
						window.location = "http://"+siteName+"/networks/subscriptions/";
					}
					else {
						$('userName').addClassName('err');
						$('userPassword').addClassName('err');
						$('UloginErrMessage').innerHTML = "Login&nbsp;Failed";
						if($('loginSignIn')){
							$('loginSignIn').innerHTML = innerHTML;
						}
					}
				}
		});
	}

function userSwitch(id){
	popupSwap();
	centerPos('popUpDiv');
	showLoader2('messagePopUpDiv');
	var url = '/networks/userSwitch';
	var params = {'id' : id};
	
	new Ajax.Updater('messagePopUpDiv', url, {
  			parameters: params,
  			evalScripts:true,
  			onComplete: function(response){
	  			centerPos('popUpDiv');
	  		}
	});
}