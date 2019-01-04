/* scripts.js starts here */
var siteName = window.location.hostname;
//var siteName = 'www.ashops.com';
var siteprotocol = window.location.protocol;

var noFriends = 0;
/*new script to detect browser by dinesh */
(function(){
	  
	  var eventMatchers = {
	    'HTMLEvents': /^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll)$/,
	    'MouseEvents': /^(?:click|mouse(?:down|up|over|move|out))$/
	  };
	  var defaultOptions = {
	    pointerX: 0,
	    pointerY: 0,
	    button: 0,
	    ctrlKey: false,
	    altKey: false,
	    shiftKey: false,
	    metaKey: false,
	    bubbles: true,
	    cancelable: true
	  };
	  
	  Event.simulate = function(element, eventName) {
	    var options = Object.extend(defaultOptions, arguments[2] || { });
	    var oEvent, eventType = null;
	    
	    element = $(element);
	    
	    for (var name in eventMatchers) {
	      if (eventMatchers[name].test(eventName)) { eventType = name; break; }
	    }

	    if (!eventType)
	      throw new SyntaxError('Only HTMLEvents and MouseEvents interfaces are supported');

	    if (document.createEvent) {
	      oEvent = document.createEvent(eventType);
	      if (eventType == 'HTMLEvents') {
	        oEvent.initEvent(eventName, options.bubbles, options.cancelable);
	      }
	      else {
	        oEvent.initMouseEvent(eventName, options.bubbles, options.cancelable, document.defaultView,
	          options.button, options.pointerX, options.pointerY, options.pointerX, options.pointerY,
	          options.ctrlKey, options.altKey, options.shiftKey, options.metaKey, options.button, element);
	      }
	      element.dispatchEvent(oEvent);
	    }
	    else {
	      options.clientX = options.pointerX;
	      options.clientY = options.pointerY;
	      oEvent = Object.extend(document.createEventObject(), options);
	      element.fireEvent('on' + eventName, oEvent);
	    }
	    return element;
	  };
	  
	  Element.addMethods({ simulate: Event.simulate });
	})();
	
var BrowserDetect = {
	init: function () {
		this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
		this.version = this.searchVersion(navigator.userAgent)
			|| this.searchVersion(navigator.appVersion)
			|| "an unknown version";
		this.OS = this.searchString(this.dataOS) || "an unknown OS";
	},
	searchString: function (data) {
		for (var i=0;i<data.length;i++)	{
			var dataString = data[i].string;
			var dataProp = data[i].prop;
			this.versionSearchString = data[i].versionSearch || data[i].identity;
			if (dataString) {
				if (dataString.indexOf(data[i].subString) != -1)
					return data[i].identity;
			}
			else if (dataProp)
				return data[i].identity;
		}
	},
	searchVersion: function (dataString) {
		var index = dataString.indexOf(this.versionSearchString);
		if (index == -1) return;
		return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
	},
	dataBrowser: [
		{
			string: navigator.userAgent,
			subString: "Chrome",
			identity: "Chrome"
		},
		{ 	string: navigator.userAgent,
			subString: "OmniWeb",
			versionSearch: "OmniWeb/",
			identity: "OmniWeb"
		},
		{
			string: navigator.vendor,
			subString: "Apple",
			identity: "Safari",
			versionSearch: "Version"
		},
		{
			prop: window.opera,
			identity: "Opera"
		},
		{
			string: navigator.vendor,
			subString: "iCab",
			identity: "iCab"
		},
		{
			string: navigator.vendor,
			subString: "KDE",
			identity: "Konqueror"
		},
		{
			string: navigator.userAgent,
			subString: "Firefox",
			identity: "Firefox"
		},
		{
			string: navigator.vendor,
			subString: "Camino",
			identity: "Camino"
		},
		{		// for newer Netscapes (6+)
			string: navigator.userAgent,
			subString: "Netscape",
			identity: "Netscape"
		},
		{
			string: navigator.userAgent,
			subString: "MSIE",
			identity: "Explorer",
			versionSearch: "MSIE"
		},
		{
			string: navigator.userAgent,
			subString: "Gecko",
			identity: "Mozilla",
			versionSearch: "rv"
		},
		{ 		// for older Netscapes (4-)
			string: navigator.userAgent,
			subString: "Mozilla",
			identity: "Netscape",
			versionSearch: "Mozilla"
		}
	],
	dataOS : [
		{
			string: navigator.platform,
			subString: "Win",
			identity: "Windows"
		},
		{
			string: navigator.platform,
			subString: "Mac",
			identity: "Mac"
		},
		{
			   string: navigator.userAgent,
			   subString: "iPhone",
			   identity: "iPhone/iPod"
	    },
		{
			string: navigator.platform,
			subString: "Linux",
			identity: "Linux"
		}
	]

};
BrowserDetect.init();

function changeTabClass(obj){
	var navTabs = $('navTabs').childElements();
	var len = navTabs.length;
	
	for(var j=0;j<len; j++)
	{	
		if(navTabs[j].id)
			navTabs[j].removeClassName('sel');
	}
	
	obj.parentNode.className = 'sel';
	showLoader('pageContent');
}

function changeInnerTabClass(obj){
	var navTabs = $$('ul#innerul li');
	var len = navTabs.length;
	
	for(var j=0;j<len; j++)
	{	
		if(navTabs[j].name="innerli"){
			if(navTabs[j].firstDescendant().className != "hList")
				navTabs[j].firstDescendant().className = '';
		}
	}
	if($('innerDiv')) {
		showLoader('innerDiv');
	}
	else {
		obj.addClassName('loader');
	}
	obj.className = 'sel';
	
}

function changeSubarea(text,li){
	var txt = '';
	
	if (document.all) // IE Stuff
	{
	   txt = li.innerText;   
	} 
	else // Mozilla does not work with innerText
	{
	   txt = li.textContent;
	}
	
	
	var html = $('subareaOptions').innerHTML;
	if(html=== '')
		html=txt;
	else
	html +=" "+txt;
	$('subareaOptions').value = html;
	//html = '<input type="button"  value="'+txt+'" style="margin:0">';
	$('subareaOptions').innerHTML = html;
	
}


function changeIndexTab(obj){
	var id = obj.parentNode.className;
	var navTabs = $$('ul#landingPgTabs li a');
	var len = navTabs.length;
	for(var j=0;j<len; j++)
	{
		navTabs[j].removeClassName('sel');
	}
	obj.addClassName('sel');
	var divs = $('landingPgTabCont').immediateDescendants();
	var len = divs.length;
	for(var j=0;j<len; j++)
	{
		if(divs[j].id != '')
		divs[j].hide();
	}
	$(id).show();
}

function amtValidate(y) {

	if (y == "")
	{
		alert("Plese enter proper amount");
      	return false;
	}
	if(isNaN(y)||y.indexOf(" ")!=-1)
   	{
      	alert("Plese enter proper amount");
      	return false;
   	}
	if (y < 20)
	{
		alert("Plese enter minimum 20");
      	return false;
	}
	//alert(y.length)
	for (var i=0;i<(y.length);i++)
	{
		//alert(y.charCodeAt(i));
		if (y.charCodeAt(i) == 46) {
			alert("Recharge amount should not be decimal.");
			$('amount').focus();
			return false;
		}
	}

}

function mobileValidate(y){
  if(isNaN(y)||y.indexOf(" ")!=-1)
   {
	  /*if($('divErr')){
    	  $('divErr').innerHTML = '<div class="errMessage1" id="flashMessage">Your mobile number should contain numeric values</div>';
      }
      else{*/
    	  alert("Your mobile number should contain numeric values");
      //}	  
      return false;
   }
   if (y.length != 10)
   {
	  /*if($('divErr')){
		  $('divErr').innerHTML = '<div class="errMessage1" id="flashMessage">Your mobile number should be a 10 digit number</div>';
      }
      else{*/ 
    	  alert("Your mobile number should be a 10 digit number");
      //}
      return false;
   }
   if (y.charAt(0)!="9" && y.charAt(0)!="8" && y.charAt(0)!="7")
   {
	   /*if($('divErr')){
		  $('divErr').innerHTML = '<div class="errMessage1" id="flashMessage">Your mobile number should start with 9, 8 or 7</div>';
	   }
	   else{*/
		   alert("Your mobile number should start with 9, 8 or 7");
	   //}
	   return false;
   }
  return true;
}

function nameValidate(name,fieldName,maxlength){
	if( (name == null) || (name.length == 0)){
		/*if($('divErr')){
			$('divErr').innerHTML = '<div class="errMessage1" id="flashMessage">Enter '+fieldName+'</div>';
		}
		else{*/
			alert("Enter "+fieldName);
		//}		
		return false;
	}
	if(maxlength != -1 && name.length > maxlength){
		/*if($('divErr')){
			$('divErr').innerHTML = '<div class="errMessage1" id="flashMessage">'+fieldName+' should contain maximum of '+maxlength+' characters</div>';
		}
		else{*/
			alert(fieldName + " should contain maximum of " + maxlength + " characters");
		//}		
		return false;
	}
	var re = /[^a-zA-Z0-9 ]/g;
	if (re.test(name)){
		/*if($('divErr')){
			$('divErr').innerHTML = '<div class="errMessage1" id="flashMessage">'+fieldName+' should not contain any special characters</div>';
		}
		else{*/
			alert(fieldName + " should contain alphanumeric (A-Z, a-z, 0-9) characters only." );
		//}		
		return false;
	}	
	return true;	
}

function changePassValidation(){
    
    var regularExpression = /^[a-zA-Z]+[0-9a-z]+[0-9]+$/;
    var newpassword = $('pass2').value;
    
	if($('pass1').value.strip() == ''){
		alert("Enter your Current password.");
		return false;
	}
	else if($('pass2').value.strip() == ''){
		alert("Enter new password.");
		return false;
	}
     else if(newpassword.length < 8){
		alert("Password length should be greater than or equal to 8 character!!!");
		return false;
	}
    
    else if(newpassword=='test1234'){
        
        alert("You can not set "+ newpassword + " as a new password!!!");
		return false;
    }
    
    
     else if(!regularExpression.test(newpassword)) {
       
       alert("Password should contain alphanumeric (A-Z, a-z, 0-9) characters only.\n Example(test1234)" );
       return false;
    }
	else if($('pass3').value.strip() == ''){
		alert("Re-enter new password");
		return false;
	}
	else if($('pass2').value.strip() != $('pass3').value.strip()){
		alert("New passwords do not match");
		return false;
	}
	else return nameValidate($('pass2').value.strip(),"your password",-1);
}

function signUpValidation(mobile,captcha){
	if(mobileValidate(mobile)){
		if(nameValidate(captcha,'Verification code',4)){
			/*if($('frgetSub')){
				showLoader2('frgetSub');
				$('frgetSub').show();
			}*/
			return true;
		}	
	}
	return false;
}

function defineSMSCode(id,code){
	$(id).innerHTML = 'Registered user can also subscribe to this package by sending SMS as '+code+' to 09223178889.';
	$(id).show();
}

/*document.observe('dom:loaded', function(){
	$$('table.dataTableBody tr:nth-child(odd)').invoke("addClassName", "altRow");
	

});*/
document.observe('dom:loaded', function(){
	if($('vertical_carousel')){
		var VCLength = $$('#vertical_carousel ul li.ie6Fix2');
		switch(VCLength.length)
		{
			case 5: $('vertical_carousel').setStyle({ height:'680px'}); break;
			case 4: $('vertical_carousel').setStyle({ height:'445px'}); break;
			case 3: $('vertical_carousel').setStyle({ height:'330px'}); break;
			case 2: $('vertical_carousel').setStyle({ height:'230px'}); break;
			case 1: $('vertical_carousel').setStyle({ height:'130px'}); break;
			default: $('vertical_carousel').setStyle({ height:'665px'});
		}
	}
	
	
});

function altRow() {
	//alert("he");
	$$('table.ListTable tr:nth-child(even)').invoke("addClassName", "altRow");
	$$('table.dataTableBody tr:nth-child(even)').invoke("addClassName", "altRow");
	//alert("he");
}

var signupDefault = 'Enter 10 digit Mobile Number here';


function getWinDimension(){
		var winDim = document.viewport.getDimensions();
		this.windowHeight = winDim.height;
		this.windowWidth = winDim.width;
		
		if(BrowserDetect.browser == 'Explorer')
		{
			if (typeof window.innerWidth != 'undefined')
            {
                 this.windowWidth = window.innerWidth,
                 this.windowHeight = window.innerHeight
            }
           
           // IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)
           
            else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0)
            {
                  this.windowWidth = document.documentElement.clientWidth;
                  this.windowHeight = document.documentElement.clientHeight;
            }
           
            // older versions of IE
           
            else
            { 
            	if(document.getElementsByTagName('body')['0'])
            	{
                	this.windowWidth = document.getElementsByTagName('body')['0'].clientWidth;
                	this.windowHeight = document.getElementsByTagName('body')['0'].clientHeight;
            	}
            }
		}
		
	}
	getWinDimension();
/*** Position Elements to center of the page ***/
	function centerPos(element){
		  var deltaX;
		  var deltaY;
		  element = $(element);
		  
		  if(!element._centered){
			  Element.setStyle(element, {position: 'absolute', zIndex: 390});
			  element._centered = true;
		  }
		  
		  if(element.id == 'popUpDiv'){
			  var width = '';
			  var elem = ($('messagePopUpDiv').childElements())[0];
			  if(!elem || !elem.style.width)
				  width = '400px';
			  else 
				  width = (elem.style.width).slice(0, -2).strip()*1 + 30 + 'px';
			  Element.setStyle(element, {width: width});
		  }
		  else {
			  Element.setStyle(element, {width: 'auto'});
		  }
		  
		  var dims = Element.getDimensions(element);
		  Position.prepare(); 
		  var winWidth,winHeight;
		  
		  var paddingPopupTop = 0;
		  winWidth = this.windowWidth;
		  winHeight = this.windowHeight;
		  if(BrowserDetect.browser == 'Explorer'){		 	
		 		deltaX = (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
				deltaY = (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
		  }else{
		 		deltaX = Position.deltaX;
				deltaY = Position.deltaY;				
		  }
		 
		 var offLeft = ( deltaX + Math.floor((winWidth-dims.width)/2));
		 var offTop = ( deltaY + Math.floor((winHeight-dims.height)/2));
		 var adjustRatio = winHeight*paddingPopupTop;
		
		 element.style.top = (dims.height >= winHeight)? ((deltaY)? (adjustRatio + deltaY + "px") : adjustRatio + "px"):((offTop != null && offTop > 0) ? offTop : '0')+ 'px';
		 element.style.left = ((offLeft != null && offLeft > 0) ? offLeft :'0') + 'px';
		 
		 /*var tempTop = element.style.top;
		 var tempIntTop = tempTop.slice(0,element.style.top.length-2);
		 if(tempIntTop < 0)
		 {
		 	element.style.top = '0px';
		 }*/
		 
		$(element).show();
	}
	
	function calculateCost(length){
		return Math.ceil(length/DEFAULT_MESSAGE_LENGTH)*EACH_MESSAGE_COST;
	}
	
	function countCharacters(id,out){
		var str = $(id).value.length;
		var cost = calculateCost(str);
		//var cost = calculateCost(str-DEFAULT_MESSAGE_LENGTH+ADSPACE);
		
		$(out).innerHTML=str + " chars "+cost + " Paise";
	}
	
	
	function ajaxUpdaterCall(url,params,updateDiv,success,complete){
		
		new Ajax.Updater(updateDiv, url, {
	  			parameters: params,
	  			evalScripts:true,
	  			onSuccess: function(response){ if(success != '') {eval(success);}},
	  			onComplete: function(response){ if(complete != ''){eval(complete);}}
			});
	}
	
	
	function signin(e,par)
	{
	 	var characterCode;
		if(e && e.which)
		{
			 e = e;
			 characterCode = e.which;
		}
		else
		{    
			 e = event;
			 characterCode = e.keyCode; 
		}
		
		if(characterCode == 13)
		{
			 login(par);
			 return false;
		}
		return true;
	}
	
	function dndVal(e,par)
	{
	 	var characterCode;
		if(e && e.which)
		{
			 e = e;
			 characterCode = e.which;
		}
		else
		{    
			 e = event;
			 characterCode = e.keyCode; 
		}
		
		if(characterCode == 13)
		{
			 if(mobileValidate(par)){
			 	dndChk();
			 }
			 
		}
		
	}
	
	function signup(e,par)
	{
	 	var characterCode;
		if(e && e.which)
		{
			 e = e;
			 characterCode = e.which;
		}
		else
		{    
			 e = event;
			 characterCode = e.keyCode; 
		}
		
		if(characterCode == 13)
		{
			if(par == "main"){
				captchaValidate();
			}
			else {
				//alert('hello');
			}
			return false;
		}
		return true;
	}
	
	function login(par){            
		var mobile = '';
		var password = '';
		var group = '';
		var func = 'login("'+par+'")';
		mobile = $('userMobile').value;
		password = $('userPassword').value;
		group = $('userGroup').value;
                			
                var d_u_n =  new Fingerprint().get();

                if(nameValidate(password,"your password",-1)){

			var innerHTML = $('loginSignIn').innerHTML;
			showLoader2('loginSignIn');
			
						
			
			var url = '/users/afterLogin';
			var params = {'mobile' : mobile,'password' : password,'group_id' : group,'param' : par,'uuid' : d_u_n};
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,datatype:"JSON",
			onSuccess:function(transport)
					{
						
						var data ;//= JSON.parse(transport.responseText); 
                                                try {
                                                    data = JSON.parse(transport.responseText);
                                                } catch (e) {
                                                    data = {'status':"FALSE"};
                                                }
                                                
                                                if( data.status && data.status == "TRUE"){	
                                                    window.location = siteprotocol+"//"+siteName+"/shops/view";
                                                } else if (data.errors.code == '101') {
                                                    window.location = siteprotocol+"//"+siteName+"/users/verifyLoginOtp/"+mobile+"/"+data.errors.user_id+"/"+group;
						} else {
							
                                                        $('userMobile').addClassName('err');
                                                        $('userPassword').addClassName('err');
                                                        var msg = "Login&nbsp;Failed";
                                                        if(data.errors && data.errors.msg){
                                                            msg = data.errors.code+" # "+data.errors.msg;
                                                        }
                                                        $('UloginErrMessage').innerHTML = msg ;
                                                        $('loginSignIn').innerHTML = "<input type='image' onclick='"+func+"' src='/img/spacer.gif' class='otherSprite oSPos5' value='Submit' tabindex='3'>";
						
						}
					}
			});
		}
	}
	
	function asynchronousCall(random){
		var url = '/groups/asynchronousCall';
		var params = {'random' : random};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params});		
	}
	
	
	function changeLoginStatus(){
		
		var url = '/users/rightHeader';
		var params = {};
		
		new Ajax.Updater('rightHeaderSpace', url, {
	  			parameters: params,
	  			evalScripts:true
		});
	}
	
	function showLoader(id){
		$(id).innerHTML = '<div id="loader1" class="loader1">&nbsp;</div>';
	}
	
	function showLoader2(id){
		$(id).innerHTML = '<div id="loader2" class="loader2">&nbsp;</div>';
	}
	
	function ajax403Handling(){
		popupSwap();
		if($('errMessagePopUp')){
			showLoader2('errMessagePopUp');		
			centerPos('errPopUp');
		}
		if($('loader1'))$('loader1').hide();
    	if($('loader2'))$('loader2').hide();
		if($('subscribePopup')){
			closeSubscribe(1);
			if($('sub_butt1'))$('sub_butt1').innerHTML = '<a href="javascript:void(0);" class="retailBut enabledBut" onclick="subPackage()">Ok</a>';
		}
		$('messagePopUpDiv').innerHTML = $('login_user').innerHTML;
		if($('errPopUp'))$('errPopUp').hide();
		$('message').innerHTML = "<div class='popupTitle color2 popupTitlePadd'>Please login to continue ..</div>";
    	$('message').show();
    	centerPos('popUpDiv');
	}
	
	function reloadShopBalance(bal){
		if($('UserBalance')){
	 		$('UserBalance').innerHTML = 'Balance : <span><img class="rupee1" src="/img/rs.gif"/></span>' + bal +'&nbsp;&nbsp;<span style="color:#CCC;font-weight:normal">';
	 	}
	 }
	
		
	function closePopUp(){
		$('popUpDiv').hide();
		$('messagePopUpDiv').innerHTML = '';
		if($('mobile'))
	    	$('mobile').focus();
		
		if($('bg'))$('bg').hide();
	}
	
	function closePopUp1(obj){
		//alert(obj);
		Effect.Shrink(obj,{'direction':'top-left'});

	}
	
	function selectAll(obj,divId,name){
		var elems = $$('div#'+divId+ ' input');
		var len = elems.length;
		if(obj.checked){
			for (var i=0;i<len;i++){
				if(elems[i].name == name){
					elems[i].checked = true;
				}
			}
		}else {
			for (var i=0;i<len;i++){
				if(elems[i].name == name){
					elems[i].checked = false;
				}
			}
		}
		
		selectFriend(divId,name);
	}
	
		
	function forgetPassword(){
		popupSwap();
		var url = '/users/forgotPassword';
		var params = {};
		showLoader2('errMessagePopUp');
		//$('errMessagePopUp').innerHTML = '<div align="center"><img src="/img/loader2.gif" /></div>';
		centerPos('errPopUp');
		//centerPos('popUpDiv');
		
		new Ajax.Updater('messagePopUpDiv', url, {
	  			parameters: params,
	  			evalScripts:true,
	  			onComplete: function(response){ $('errPopUp').hide(); centerPos('popUpDiv');}
		});
	
	}
	
	function popupSwap(){
		if($('popUpDiv'))
			$('popUpDiv').hide();		
	}
		
	function trim(str, chars) {
		return ltrim(rtrim(str, chars), chars);
	}
 
	function ltrim(str, chars) {
		chars = chars || "\\s";
		return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
	}
	 
	function rtrim(str, chars) {
		chars = chars || "\\s";
		return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
	} 
	
	function openPopup(bitly){
    	var url = '/users/twitt/'+bitly;
    	newwindow=window.open(url,'name','height=600,width=800,left=600,top=150');
		if (window.focus) {newwindow.focus()}
    
    }
	
var currSMS = 1;

function showSample(direction)
{	
	var id = $('msgNum').value;
	var msg = $$('ul.recentMsgsCont li');
	var len = msg.length;
	if(direction == 'pre'){
		if(id > 1) {
			id--;
		}
		else id = len;
	}
	if(direction == 'next'){
		if(id < len) {
			id++;
		}
		else id = 1;
	}
	
	for(var i =1;i<=len;i++){
		$('sampSMS'+i).hide();
	}
	$('sampSMS'+id).show();
	$('msgNum').value = id;
	
	
	/*var counter = $$('ul.sampleNo li');
	counter = parseInt((counter.length) - 3);	
	if ((id != 0) && (currSMS != id)) // If no. not click and curr sms not clicked
	{	
		changeSample(id);
	}
	else if (currSMS != id)
	{
		if(direction == 'pre') { // If 1 than go to no 5 else come back			
			if (currSMS == 1) {
				changeSample(counter);
			}				
			else {
				id = parseInt(currSMS);changeSample(id-1);
			}
		}			
		else {			
			if (currSMS == counter) {
				changeSample(1);
			} else {				
				id = parseInt(currSMS); id += 1;
				changeSample(id); 
			}
		}		
	}*/
}

	
	function encodeValue(val)
	{
		var encodedText = Base64.encode(val);
		
		var lenEncTxt = encodedText.length;

		var str1 = encodedText.slice(0,1);
		var str2 = encodedText.slice(1, lenEncTxt);

		var str3 = str1 + "T" + str2;

		var encVal = Base64.encode(str3);
		
		return encVal;
	}

	function decodeValue(val)
	{
		var decodedText = Base64.decode(val);

		var lenDecTxt = decodedText.length;

		var str1 = decodedText.slice(0,1);
		var str2 = decodedText.slice(2, lenDecTxt);

		var str3 = str1 + str2;
		
		var decVal = Base64.decode(str3);

		return decVal;
	}

	var Base64 = {
		// private property
		_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
	 
		// public method for encoding
		encode : function (input) {
			var output = "";
			var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
			var i = 0;
	 
			input = Base64._utf8_encode(input);
	 
			while (i < input.length) {
	 			chr1 = input.charCodeAt(i++);
				chr2 = input.charCodeAt(i++);
				chr3 = input.charCodeAt(i++);
	 
				enc1 = chr1 >> 2;
				enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
				enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
				enc4 = chr3 & 63;
	 
				if (isNaN(chr2)) {
					enc3 = enc4 = 64;
				} else if (isNaN(chr3)) {
					enc4 = 64;
				}
	 
				output = output +
				this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
				this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
	 		}
	 
			return output;
		},
	 
		// public method for decoding
		decode : function (input) {
			var output = "";
			var chr1, chr2, chr3;
			var enc1, enc2, enc3, enc4;
			var i = 0;
	 
			input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
	 
			while (i < input.length) {
	 			enc1 = this._keyStr.indexOf(input.charAt(i++));
				enc2 = this._keyStr.indexOf(input.charAt(i++));
				enc3 = this._keyStr.indexOf(input.charAt(i++));
				enc4 = this._keyStr.indexOf(input.charAt(i++));
	 
				chr1 = (enc1 << 2) | (enc2 >> 4);
				chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
				chr3 = ((enc3 & 3) << 6) | enc4;
	 
				output = output + String.fromCharCode(chr1);
	 
				if (enc3 != 64) {
					output = output + String.fromCharCode(chr2);
				}
				if (enc4 != 64) {
					output = output + String.fromCharCode(chr3);
				}
	 		}
	 
			output = Base64._utf8_decode(output);
	 
			return output;
	 	},
	 
		// private method for UTF-8 encoding
		_utf8_encode : function (string) {
			string = string.replace(/\r\n/g,"\n");
			var utftext = "";
	 
			for (var n = 0; n < string.length; n++) {
	 			var c = string.charCodeAt(n);
	 
				if (c < 128) {
					utftext += String.fromCharCode(c);
				}
				else if((c > 127) && (c < 2048)) {
					utftext += String.fromCharCode((c >> 6) | 192);
					utftext += String.fromCharCode((c & 63) | 128);
				}
				else {
					utftext += String.fromCharCode((c >> 12) | 224);
					utftext += String.fromCharCode(((c >> 6) & 63) | 128);
					utftext += String.fromCharCode((c & 63) | 128);
				}
	 		}

			return utftext;
		},
	 
		// private method for UTF-8 decoding
		_utf8_decode : function (utftext) {
			var string = "";
			var i = 0;
			var c = c1 = c2 = 0;
	 
			while ( i < utftext.length ) {
	 			c = utftext.charCodeAt(i);
	 
				if (c < 128) {
					string += String.fromCharCode(c);
					i++;
				}
				else if((c > 191) && (c < 224)) {
					c2 = utftext.charCodeAt(i+1);
					string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
					i += 2;
				}
				else {
					c2 = utftext.charCodeAt(i+1);
					c3 = utftext.charCodeAt(i+2);
					string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
					i += 3;
				}
	 		}

			return string;
		}
	};
	
		function typeChange(val){
			if($('urlType'+val).options[$('urlType'+val).selectedIndex].value == 1){
				$('urlUrl'+val).value = "http://www.youtube.com/v/";
			}else{
				$('urlUrl'+val).value = "";
			}
		}
		
		function createShortUrl(pkg_id,url_id){
			var cnt = $('urlDataCnt').value;
			var title = new Array();
			var type = new Array();
			var urls = new Array();			
			
			//urlTable
			//urlErr
			var noRecArr = new Array();			
			var j = 0;
			for(var i=0;i<cnt;i++){
				$('urlTable'+i).style.border = '1px solid #FFFFFF';
				$('urlErr'+i).style.border = '1px solid #FFFFFF';
				$('urlErr'+i).innerHTML = '';
								
				if($('urlTitle'+i).value.strip() == "" && $('urlUrl'+i).value.strip() == ''){
					 noRecArr[j] = 1;
					 j++;
				}								
			}
			
			
			if(noRecArr.length == cnt){
				alert('Enter at least one record');
				return false;
			}
			
			var errVar1 = 0;
			for(var i=0;i<cnt;i++){
				$('urlTable'+i).style.border = '1px solid #FFFFFF';
				$('urlErr'+i).style.border = '1px solid #FFFFFF';
				$('urlErr'+i).innerHTML = '';
								
				if($('urlTitle'+i).value.strip() != "" && $('urlUrl'+i).value.strip() == ''){
					$('urlTable'+i).style.border = '1px solid #ec724a';
					$('urlErr'+i).style.border = '1px solid #ec724a';
					$('urlErr'+i).innerHTML = 'Please enter URL';
					errVar1 = 1; 
				}else if($('urlTitle'+i).value.strip() == "" && $('urlUrl'+i).value.strip() != ''){
					if(!isValidURL($('urlUrl'+i).value.strip())){
						$('urlTable'+i).style.border = '1px solid #ec724a';
						$('urlErr'+i).style.border = '1px solid #ec724a';
						$('urlErr'+i).innerHTML = 'Invalid URL';
						errVar1 = 1;
					}
				}else if($('urlTitle'+i).value.strip() != "" && $('urlUrl'+i).value.strip() != ''){
					if(!isValidURL($('urlUrl'+i).value.strip())){
						$('urlTable'+i).style.border = '1px solid #ec724a';
						$('urlErr'+i).style.border = '1px solid #ec724a';
						$('urlErr'+i).innerHTML = 'Invalid URL';
						errVar1 = 1;
					}
				}
				
				title[i] = $('urlTitle'+i).value;
				type[i] = $('urlType'+i).options[$('urlType'+i).selectedIndex].value;
				urls[i] = $('urlUrl'+i).value;
			}
			
			if(errVar1 == 1)
			return false;
			/*validation			
			if(title && no url){
				ERROR
			}else if(no title && but url){
				if(invalid url){
					ERROR
				}
			}else if(title && url){
				if(invalid url){
					ERROR
				}
			}*/
			
			var url    = '/groups/createShortUrl';
			showLoader2('createShortUrl');
			var rand   = Math.random(9999);
									
			var newGRpID = 1;
			var pars   = {'url_id':url_id,'pkg_id':pkg_id, 'title[]':title, 'type[]':type, 'urls[]':urls};
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{
						var res = transport.responseText.split('^^^');
						$('shortDataUrl').innerHTML = "<div style='margin:10px;padding:3px;background-color:#F88017'>"+res[1]+"</div>"; 
						$('createShortUrl').innerHTML = '<input type="button" style="background-color:#657383;" value="Delete & get new" onclick="createShortUrl('+pkg_id+','+res[0]+');">';
					}
			});
		}
		
		function isValidURL(url){ 
  		  	var RegExp = /^(((ht|f){1}(tp:[/][/]){1})|((www.){1}))[-a-zA-Z0-9@:%_\+.~#?&//=]+$/; 
    		if(RegExp.test(url)){ 
        		return true; 
    		}else{ 
        		return false; 
    		} 
		} 
		
		
		function getRetailers(pageNum,loader){					
			id = $('area').options[$('area').selectedIndex].value;
			var url = '/retailers/getRetailersByArea';
			var sndBut = $('sendButt').innerHTML;
			if(loader == 1)showLoader2('sendButt');
			
			if($('state').options[$('state').selectedIndex].value == '0'){
				$('locateErr').innerHTML = 'Please select state.';
				if(loader == 1)$('sendButt').innerHTML = sndBut;
				return false;
			}else if($('city').options[$('city').selectedIndex].value == '0'){
				$('locateErr').innerHTML = 'Please select city.';
				if(loader == 1)$('sendButt').innerHTML = sndBut;
				return false;
			}else if($('area').options[$('area').selectedIndex].value == '0'){
				$('locateErr').innerHTML = 'Please select area.';
				if(loader == 1)$('sendButt').innerHTML = sndBut;
				return false;
			}
			
			$('locateErr').innerHTML = '';
			//if(loader == 0)showLoader('retailersData');
			/*new Ajax.Updater('retailersData', url, {
		  			parameters: {area_id: id, page: pageNum},
		  			evalScripts:true
				});*/
			var pars   = {area_id: id, page: pageNum};
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,evalScripts:true,
				onSuccess:function(transport)
				{
					var res = transport.responseText;
					$('retailersData').innerHTML = res;
					if(loader == 1)$('sendButt').innerHTML = sndBut;
				}
			});
				
		}
		
		
		function getAreas(id, type){
			var url = '/retailers/getAreasByCity';
			var pars   = {city_id: id, type: type};
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{
					var res = transport.responseText;
					$('areaDD').innerHTML = res;
				}
			});	
		}
		
		function getCities(id,type){
			
			var url = '/retailers/getCitiesByState';
			var pars   = {state_id: id, type: type};
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{
					var res = transport.responseText;
					$('cityDD').innerHTML = res;

					//getAreas($('city').options[$('city').selectedIndex].value);
				}
			});	
		}
		
		/*function fbs_click() {
			u="http://www.smstadka.com/messages/facebook/free-credits/"+Math.floor(Math.random()*1001);
			window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u),'sharer','toolbar=0,status=0,width=626,height=436');
			return false;
		}
		*/
		function loginWin(){						
			popupSwap();
			var url = '/users/loginWin';
			var params = {};			
			showLoader2('errMessagePopUp');
			centerPos('errPopUp');
			new Ajax.Updater('messagePopUpDiv', url, {
		  			parameters: params,
		  			evalScripts:true,
		  			onComplete: function(response){$('errPopUp').hide();centerPos('popUpDiv');}
			});
		}
		
		function findQty(obj,count)
		{
			if ((obj.className) == 'start')
			{				
				if (isNaN($('start_'+count).value) || isNaN($('end_'+count).value) || $('end_'+count).value == "")
					return false;
				else
				{
					if ($('end_'+count).value < $('start_'+count).value)
					{						
						$('qty_'+count).innerHTML = "error";
					}
					else
						$('qty_'+count).innerHTML = ($('end_'+count).value - $('start_'+count).value)+1;
				}					
			}
			else
			{
				if (isNaN($('end_'+count).value) || isNaN($('start_'+count).value) || ($('start_'+count).value == ""))
					return false;
				else
				{
					if ($('end_'+count).value < $('start_'+count).value)
					{
						$('qty_'+count).innerHTML = "error";
					}
					else
						$('qty_'+count).innerHTML = ($('end_'+count).value - $('start_'+count).value)+1;
				}				
			}			 
		}
		
		function $m(quem){
			return document.getElementById(quem)
		}
		
		function remove(quem){
		 quem.removeChild(quem);
		}
		
		function addEvent(obj, evType, fn){
		
		    if (obj.addEventListener)
		        obj.addEventListener(evType, fn, true)
		    if (obj.attachEvent)
		        obj.attachEvent("on"+evType, fn)
		}
		
		function removeEvent( obj, type, fn ) {
		  if ( obj.detachEvent ) {
		    obj.detachEvent( 'on'+type, fn );
		  } else {
		    obj.removeEventListener( type, fn, false ); }
		} 
		
		function micoxUpload(form,url_action,id_element,html_show_loading,html_error_http){									 
			 form = typeof(form)=="string"?$m(form):form;
			 
			 var erro="";
			 if(form==null || typeof(form)=="undefined"){ erro += "The form of 1st parameter does not exists.\n";}
			 else if(form.nodeName.toLowerCase()!="form"){ erro += "The form of 1st parameter its not a form.\n";}
			 if($m(id_element)==null){ erro += "The element of 3rd parameter does not exists.\n";}
			 if(erro.length>0) {
			  alert("Error in call micoxUpload:\n" + erro);
			  return;
			 }
	
			
			 var iframe = document.createElement("iframe");
			 iframe.setAttribute("id","micox-temp");
			 iframe.setAttribute("name","micox-temp");
			 iframe.setAttribute("width","0");
			 iframe.setAttribute("height","0");
			 iframe.setAttribute("border","0");
			 iframe.setAttribute("style","width: 0; height: 0; border: none;");
			 
			
			 form.appendChild(iframe);
			 window.frames['micox-temp'].name="micox-temp"; //ie sucks
			 
			
			 var carregou = function() { 
			   removeEvent( $m('micox-temp'),"load", carregou);
			   var cross = "javascript: ";
			   cross += "window.parent.$m('" + id_element + "').innerHTML = document.body.innerHTML; void(0); ";
			   
			   $m(id_element).innerHTML = html_error_http;
			   $m('micox-temp').src = cross;
			
			   setTimeout(function(){ remove($m('micox-temp'))}, 250);
			  }
			 addEvent( $m('micox-temp'),"load", carregou)
			 
			
			 form.setAttribute("target","micox-temp");
			 form.setAttribute("action",url_action);
			 form.setAttribute("method","post");
			 form.setAttribute("enctype","multipart/form-data");
			 form.setAttribute("encoding","multipart/form-data");
			
			 form.submit();
			 
			
			 if(html_show_loading.length > 0){
			  $m(id_element).innerHTML = html_show_loading;
			 }		 
		}
		
		function simContact(){
			$('appRemfrndList').simulate('click');
		}
		
		
		
	/* app.js ends here */
        
        
        
        
        !function(r){"use strict";var t=function(r){var t=Array.prototype.forEach,e=Array.prototype.map;this.each=function(r,e,n){if(null!==r)if(t&&r.forEach===t)r.forEach(e,n);else if(r.length===+r.length){for(var a=0,h=r.length;h>a;a++)if(e.call(n,r[a],a,r)==={})return}else for(var o in r)if(r.hasOwnProperty(o)&&e.call(n,r[o],o,r)==={})return},this.map=function(r,t,n){var a=[];return null==r?a:e&&r.map===e?r.map(t,n):(this.each(r,function(r,e,h){a[a.length]=t.call(n,r,e,h)}),a)},r&&(this.hasher=r)};t.prototype={get:function(){var r=[];r.push(navigator.userAgent),r.push(navigator.language),r.push(screen.colorDepth),r.push((new Date).getTimezoneOffset()),r.push(!!window.sessionStorage),r.push(!!window.localStorage);var t=this.map(navigator.plugins,function(r){var t=this.map(r,function(r){return[r.type,r.suffixes].join("~")}).join(",");return[r.name,r.description,t].join("::")},this).join(";");return r.push(t),this.hasher?this.hasher(r.join("###"),31):this.murmurhash3_32_gc(r.join("###"),31)},murmurhash3_32_gc:function(r,t){var e,n,a,h,o,i,s,c;for(e=3&r.length,n=r.length-e,a=t,o=3432918353,i=461845907,c=0;n>c;)s=255&r.charCodeAt(c)|(255&r.charCodeAt(++c))<<8|(255&r.charCodeAt(++c))<<16|(255&r.charCodeAt(++c))<<24,++c,s=4294967295&(65535&s)*o+((65535&(s>>>16)*o)<<16),s=s<<15|s>>>17,s=4294967295&(65535&s)*i+((65535&(s>>>16)*i)<<16),a^=s,a=a<<13|a>>>19,h=4294967295&5*(65535&a)+((65535&5*(a>>>16))<<16),a=(65535&h)+27492+((65535&(h>>>16)+58964)<<16);switch(s=0,e){case 3:s^=(255&r.charCodeAt(c+2))<<16;case 2:s^=(255&r.charCodeAt(c+1))<<8;case 1:s^=255&r.charCodeAt(c),s=4294967295&(65535&s)*o+((65535&(s>>>16)*o)<<16),s=s<<15|s>>>17,s=4294967295&(65535&s)*i+((65535&(s>>>16)*i)<<16),a^=s}return a^=r.length,a^=a>>>16,a=4294967295&2246822507*(65535&a)+((65535&2246822507*(a>>>16))<<16),a^=a>>>13,a=4294967295&3266489909*(65535&a)+((65535&3266489909*(a>>>16))<<16),a^=a>>>16,a>>>0}},r.Fingerprint=t}(window);
